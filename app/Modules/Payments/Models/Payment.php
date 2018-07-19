<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Payments\Models;

use Carbon\Carbon;
use FI\Events\LoanModified;
use FI\Events\PaymentCreated;
use FI\Events\PaymentCreating;
use FI\Support\CurrencyFormatter;
use FI\Support\DateFormatter;
use FI\Support\FileNames;
use FI\Support\HTML;
use FI\Support\NumberFormatter;
use FI\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
    use Sortable;

    /**
     * Guarded properties
     * @var array
     */
    protected $guarded = ['id'];

    protected $sortable = ['paid_at', 'loans.loan_date', 'loans.number', 'loans.summary', 'clients.name', 'amount', 'payment_methods.name', 'note'];

    protected $dates = ['paid_at'];

    public static function boot()
    {
        static::created(function ($payment) {
            event(new LoanModified($payment->loan));
            event(new PaymentCreated($payment));
        });

        static::creating(function ($payment) {
            event(new PaymentCreating($payment));
        });

        static::updated(function ($payment) {
            event(new LoanModified($payment->loan));
        });

        static::deleting(function ($payment) {
            foreach ($payment->mailQueue as $mailQueue) {
                $mailQueue->delete();
            }

            $payment->custom()->delete();
        });

        static::deleted(function ($payment) {
            if ($payment->loan) {
                event(new LoanModified($payment->loan));
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function custom()
    {
        return $this->hasOne('FI\Modules\CustomFields\Models\PaymentCustom');
    }

    public function loan()
    {
        return $this->belongsTo('FI\Modules\Loans\Models\Loan');
    }

    public function mailQueue()
    {
        return $this->morphMany('FI\Modules\MailQueue\Models\MailQueue', 'mailable');
    }

    public function notes()
    {
        return $this->morphMany('FI\Modules\Notes\Models\Note', 'notable');
    }

    public function paymentMethod()
    {
        return $this->belongsTo('FI\Modules\PaymentMethods\Models\PaymentMethod');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFormattedPaidAtAttribute()
    {
        return DateFormatter::format($this->attributes['paid_at']);
    }

    public function getFormattedAmountAttribute()
    {
        return CurrencyFormatter::format($this->attributes['amount'], $this->loan->currency);
    }

    public function getFormattedNumericAmountAttribute()
    {
        return NumberFormatter::format($this->attributes['amount']);
    }

    public function getFormattedNoteAttribute()
    {
        return nl2br($this->attributes['note']);
    }

    public function getUserAttribute()
    {
        return $this->loan->user;
    }

    public function getHtmlAttribute()
    {
        return HTML::loan($this->loan);
    }

    public function getPdfFilenameAttribute()
    {
        return FileNames::loan($this->loan);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeYearToDate($query)
    {
        return $query->where('paid_at', '>=', date('Y') . '-01-01')
            ->where('paid_at', '<=', date('Y') . '-12-31');
    }

    public function scopeThisQuarter($query)
    {
        return $query->where('paid_at', '>=', Carbon::now()->firstOfQuarter())
            ->where('paid_at', '<=', Carbon::now()->lastOfQuarter());
    }

    public function scopeDateRange($query, $from, $to)
    {
        return $query->where('paid_at', '>=', $from)->where('paid_at', '<=', $to);
    }

    public function scopeYear($query, $year)
    {
        return $query->where('paid_at', '>=', $year . '-01-01')
            ->where('paid_at', '<=', $year . '-12-31');
    }

    public function scopeKeywords($query, $keywords)
    {
        if ($keywords) {
            $keywords = strtolower($keywords);

            $query->where('payments.created_at', 'like', '%' . $keywords . '%')
                ->orWhereIn('loan_id', function ($query) use ($keywords) {
                    $query->select('id')->from('loans')->where(DB::raw('lower(number)'), 'like', '%' . $keywords . '%')
                        ->orWhere('summary', 'like', '%' . $keywords . '%')
                        ->orWhereIn('client_id', function ($query) use ($keywords) {
                            $query->select('id')->from('clients')->where(DB::raw("CONCAT_WS('^',LOWER(name),LOWER(unique_name))"), 'like', '%' . $keywords . '%');
                        });
                })
                ->orWhereIn('payment_method_id', function ($query) use ($keywords) {
                    $query->select('id')->from('payment_methods')->where(DB::raw('lower(name)'), 'like', '%' . $keywords . '%');
                });
        }

        return $query;
    }

    public function scopeClientId($query, $clientId)
    {
        if ($clientId) {
            $query->whereHas('loan', function ($query) use ($clientId) {
                $query->where('client_id', $clientId);
            });
        }

        return $query;
    }

    public function scopeLoanId($query, $loanId)
    {
        if ($loanId) {
            $query->whereHas('loan', function ($query) use ($loanId) {
                $query->where('id', $loanId);
            });
        }

        return $query;
    }

    public function scopeLoanNumber($query, $loanNumber)
    {
        if ($loanNumber) {
            $query->whereHas('loan', function ($query) use ($loanNumber) {
                $query->where('number', $loanNumber);
            });
        }

        return $query;
    }
}