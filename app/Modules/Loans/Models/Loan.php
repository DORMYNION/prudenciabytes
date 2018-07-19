<?php

namespace FI\Modules\Loans\Models;

use Carbon\Carbon;
use FI\Events\LoanCreated;
use FI\Events\LoanCreating;
use FI\Events\LoanDeleted;
use FI\Support\CurrencyFormatter;
use FI\Support\DateFormatter;
use FI\Support\FileNames;
use FI\Support\HTML;
use FI\Support\NumberFormatter;
use FI\Support\Statuses\LoanStatuses;
use FI\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Loan extends Model
{
    use Sortable;

    protected $guarded = ['id'];

    protected $sortable = [
        'number' => ['LENGTH(number)', 'number'],
        'loan_date',
        'due_at',
        'clients.name',
        'summary',
        'loan_amounts.total',
        'loan_amounts.balance',
        'loan_amounts.tax',
        'loan_amounts.subtotal',
    ];

    protected $dates = ['due_at', 'loan_date'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($loan) {
            event(new LoanCreating($loan));
        });

        static::created(function ($loan) {
            event(new LoanCreated($loan));
        });

        static::deleted(function ($loan) {
            event(new LoanDeleted($loan));
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function activities()
    {
        return $this->morphMany('FI\Modules\Activity\Models\Activity', 'audit');
    }

    public function amount()
    {
        return $this->hasOne('FI\Modules\Loans\Models\LoanAmount');
    }

    public function attachments()
    {
        return $this->morphMany('FI\Modules\Attachments\Models\Attachment', 'attachable');
    }

    public function client()
    {
        return $this->belongsTo('FI\Modules\Clients\Models\Client');
    }

    public function clientAttachments()
    {
        $relationship = $this->morphMany('FI\Modules\Attachments\Models\Attachment', 'attachable');

        if ($this->status_text == 'paid') {
            $relationship->whereIn('client_visibility', [1, 2]);
        } else {
            $relationship->where('client_visibility', 1);
        }

        return $relationship;
    }

    public function companyProfile()
    {
        return $this->belongsTo('FI\Modules\CompanyProfiles\Models\CompanyProfile');
    }

    public function currency()
    {
        return $this->belongsTo('FI\Modules\Currencies\Models\Currency', 'currency_code', 'code');
    }

    public function custom()
    {
        return $this->hasOne('FI\Modules\CustomFields\Models\LoanCustom');
    }

    public function group()
    {
        return $this->belongsTo('FI\Modules\Groups\Models\Group');
    }

    public function items()
    {
        return $this->hasMany('FI\Modules\Loans\Models\LoanItem')
            ->orderBy('display_order');
    }

    // This and items() are the exact same. This is added to appease the IDE gods
    // and the fact that Laravel has a protected items property.
    public function loanItems()
    {
        return $this->hasMany('FI\Modules\Loans\Models\LoanItem')
            ->orderBy('display_order');
    }

    public function mailQueue()
    {
        return $this->morphMany('FI\Modules\MailQueue\Models\MailQueue', 'mailable');
    }

    public function notes()
    {
        return $this->morphMany('FI\Modules\Notes\Models\Note', 'notable');
    }

    public function payments()
    {
        return $this->hasMany('FI\Modules\Payments\Models\Payment');
    }

    public function invest()
    {
        return $this->hasOne('FI\Modules\Invests\Models\Invest');
    }

    public function transactions()
    {
        return $this->hasMany('FI\Modules\Merchant\Models\LoanTransaction');
    }

    public function user()
    {
        return $this->belongsTo('FI\Modules\Users\Models\User');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getAttachmentPathAttribute()
    {
        return attachment_path('loans/' . $this->id);
    }

    public function getAttachmentPermissionOptionsAttribute()
    {
        return [
            '0' => trans('fi.not_visible'),
            '1' => trans('fi.visible'),
            '2' => trans('fi.visible_after_payment'),
        ];
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->formatted_loan_date;
    }

    public function getFormattedLoanDateAttribute()
    {
        return DateFormatter::format($this->attributes['loan_date']);
    }

    public function getFormattedUpdatedAtAttribute()
    {
        return DateFormatter::format($this->attributes['updated_at']);
    }

    public function getFormattedDueAtAttribute()
    {
        return DateFormatter::format($this->attributes['due_at']);
    }

    public function getFormattedTermsAttribute()
    {
        return nl2br($this->attributes['terms']);
    }

    public function getFormattedFooterAttribute()
    {
        return nl2br($this->attributes['footer']);
    }

    public function getStatusTextAttribute()
    {
        $statuses = LoanStatuses::statuses();

        return $statuses[$this->attributes['loan_status_id']];
    }

    public function getIsOverdueAttribute()
    {
        // Only loans in Sent status qualify to be overdue
        if ($this->attributes['due_at'] < date('Y-m-d') and $this->attributes['loan_status_id'] == LoanStatuses::getStatusId('sent'))
            return 1;

        return 0;
    }

    public function getPublicUrlAttribute()
    {
        return route('clientCenter.public.loan.show', [$this->url_key]);
    }

    public function getIsForeignCurrencyAttribute()
    {
        if ($this->attributes['currency_code'] == config('fi.baseCurrency')) {
            return false;
        }

        return true;
    }

    public function getHtmlAttribute()
    {
        return HTML::loan($this);
    }

    public function getPdfFilenameAttribute()
    {
        return FileNames::loan($this);
    }

    public function getFormattedNumericDiscountAttribute()
    {
        return NumberFormatter::format($this->attributes['discount']);
    }

    public function getIsPayableAttribute()
    {
        return $this->status_text <> 'canceled' and $this->amount->balance > 0;
    }

    /**
     * Gathers a summary of both loan and item taxes to be displayed on loan.
     *
     * @return array
     */
    public function getSummarizedTaxesAttribute()
    {
        $taxes = [];

        foreach ($this->items as $item) {
            if ($item->taxRate) {
                $key = $item->taxRate->name;

                if (!isset($taxes[$key])) {
                    $taxes[$key] = new \stdClass();
                    $taxes[$key]->name = $item->taxRate->name;
                    $taxes[$key]->percent = $item->taxRate->formatted_percent;
                    $taxes[$key]->total = $item->amount->tax_1;
                    $taxes[$key]->raw_percent = $item->taxRate->percent;
                } else {
                    $taxes[$key]->total += $item->amount->tax_1;
                }
            }

            if ($item->taxRate2) {
                $key = $item->taxRate2->name;

                if (!isset($taxes[$key])) {
                    $taxes[$key] = new \stdClass();
                    $taxes[$key]->name = $item->taxRate2->name;
                    $taxes[$key]->percent = $item->taxRate2->formatted_percent;
                    $taxes[$key]->total = $item->amount->tax_2;
                    $taxes[$key]->raw_percent = $item->taxRate2->percent;
                } else {
                    $taxes[$key]->total += $item->amount->tax_2;
                }
            }
        }

        foreach ($taxes as $key => $tax) {
            $taxes[$key]->total = CurrencyFormatter::format($tax->total, $this->currency);
        }

        return $taxes;
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeClientId($query, $clientId = null)
    {
        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        return $query;
    }

    public function scopeDraft($query)
    {
        return $query->where('loan_status_id', '=', LoanStatuses::getStatusId('draft'));
    }

    public function scopeSent($query)
    {
        return $query->where('loan_status_id', '=', LoanStatuses::getStatusId('sent'));
    }

    public function scopePaid($query)
    {
        return $query->where('loan_status_id', '=', LoanStatuses::getStatusId('paid'));
    }

    public function scopeCanceled($query)
    {
        return $query->where('loan_status_id', '=', LoanStatuses::getStatusId('canceled'));
    }

    public function scopeCompanyProfileId($query, $companyProfileId)
    {
        if ($companyProfileId) {
            $query->where('company_profile_id', $companyProfileId);
        }

        return $query;
    }

    public function scopeNotCanceled($query)
    {
        return $query->where('loan_status_id', '<>', LoanStatuses::getStatusId('canceled'));
    }

    public function scopeStatusIn($query, $statuses)
    {
        $statusCodes = [];

        foreach ($statuses as $status) {
            $statusCodes[] = LoanStatuses::getStatusId($status);
        }

        return $query->whereIn('loan_status_id', $statusCodes);
    }

    public function scopeStatus($query, $status = null)
    {
        switch ($status) {
            case 'draft':
                $query->draft();
                break;
            case 'sent':
                $query->sent();
                break;
            case 'viewed':
                $query->viewed();
                break;
            case 'paid':
                $query->paid();
                break;
            case 'canceled':
                $query->canceled();
                break;
            case 'overdue':
                $query->overdue();
                break;
        }

        return $query;
    }

    public function scopeOverdue($query)
    {
        // Only loans in Sent status qualify to be overdue
        return $query
            ->where('loan_status_id', '=', LoanStatuses::getStatusId('sent'))
            ->where('due_at', '<', date('Y-m-d'));
    }

    public function scopeYearToDate($query)
    {
        return $query->where('loan_date', '>=', date('Y') . '-01-01')
            ->where('loan_date', '<=', date('Y') . '-12-31');
    }

    public function scopeThisQuarter($query)
    {
        return $query->where('loan_date', '>=', Carbon::now()->firstOfQuarter())
            ->where('loan_date', '<=', Carbon::now()->lastOfQuarter());
    }

    public function scopeDateRange($query, $fromDate, $toDate)
    {
        return $query->where('loan_date', '>=', $fromDate)
            ->where('loan_date', '<=', $toDate);
    }

    public function scopeKeywords($query, $keywords = null)
    {
        if ($keywords) {
            $keywords = strtolower($keywords);

            $query->where(DB::raw('lower(number)'), 'like', '%' . $keywords . '%')
                ->orWhere('loans.loan_date', 'like', '%' . $keywords . '%')
                ->orWhere('due_at', 'like', '%' . $keywords . '%')
                ->orWhere('summary', 'like', '%' . $keywords . '%')
                ->orWhereIn('client_id', function ($query) use ($keywords) {
                    $query->select('id')->from('clients')->where(DB::raw("CONCAT_WS('^',LOWER(name),LOWER(unique_name))"), 'like', '%' . $keywords . '%');
                });
        }

        return $query;
    }
}
