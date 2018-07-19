<?php

namespace FI\Modules\Invests\Models;

use Carbon\Carbon;
use FI\Events\InvestCreated;
use FI\Events\InvestCreating;
use FI\Events\InvestDeleted;
use FI\Support\CurrencyFormatter;
use FI\Support\DateFormatter;
use FI\Support\FileNames;
use FI\Support\HTML;
use FI\Support\NumberFormatter;
use FI\Support\Statuses\InvestStatuses;
use FI\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Invest extends Model
{
    use Sortable;

    protected $guarded = ['id'];

    protected $sortable = [
        'number' => ['LENGTH(number)', 'number'],
        'invest_date',
        'expires_at',
        'clients.name',
        'summary',
        'invest_amounts.total',
        'invest_amounts.tax',
        'invest_amounts.subtotal',
    ];

    protected $dates = ['expires_at', 'invest_date'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($invest) {
            event(new InvestCreating($invest));
        });

        static::created(function ($invest) {
            event(new InvestCreated($invest));
        });

        static::deleted(function ($invest) {
            event(new InvestDeleted($invest));
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
        return $this->hasOne('FI\Modules\Invests\Models\InvestAmount');
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

        $relationship->where('client_visibility', 1);

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
        return $this->hasOne('FI\Modules\CustomFields\Models\InvestCustom');
    }

    public function group()
    {
        return $this->hasOne('FI\Modules\Groups\Models\Group');
    }

    public function loan()
    {
        return $this->belongsTo('FI\Modules\Loans\Models\Loan');
    }

    public function mailQueue()
    {
        return $this->morphMany('FI\Modules\MailQueue\Models\MailQueue', 'mailable');
    }

    public function items()
    {
        return $this->hasMany('FI\Modules\Invests\Models\InvestItem')
            ->orderBy('display_order');
    }

    public function notes()
    {
        return $this->morphMany('FI\Modules\Notes\Models\Note', 'notable');
    }

    // This and items() are the exact same. This is added to appease the IDE gods
    // and the fact that Laravel has a protected items property.
    public function investItems()
    {
        return $this->hasMany('FI\Modules\Invests\Models\InvestItem')
            ->orderBy('display_order');
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
        return attachment_path('invests/' . $this->id);
    }

    public function getAttachmentPermissionOptionsAttribute()
    {
        return ['0' => trans('fi.not_visible'), '1' => trans('fi.visible')];
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->formatted_invest_date;
    }

    public function getFormattedInvestDateAttribute()
    {
        return DateFormatter::format($this->attributes['invest_date']);
    }

    public function getFormattedUpdatedAtAttribute()
    {
        return DateFormatter::format($this->attributes['updated_at']);
    }

    public function getFormattedExpiresAtAttribute()
    {
        return DateFormatter::format($this->attributes['expires_at']);
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
        $statuses = InvestStatuses::statuses();

        return $statuses[$this->attributes['invest_status_id']];
    }

    public function getPdfFilenameAttribute()
    {
        return FileNames::invest($this);
    }

    public function getPublicUrlAttribute()
    {
        return route('clientCenter.public.invest.show', [$this->url_key]);
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
        return HTML::invest($this);
    }

    public function getFormattedNumericDiscountAttribute()
    {
        return NumberFormatter::format($this->attributes['discount']);
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

    public function scopeCompanyProfileId($query, $companyProfileId)
    {
        if ($companyProfileId) {
            $query->where('company_profile_id', $companyProfileId);
        }

        return $query;
    }

    public function scopeDraft($query)
    {
        return $query->where('invest_status_id', '=', InvestStatuses::getStatusId('draft'));
    }

    public function scopeSent($query)
    {
        return $query->where('invest_status_id', '=', InvestStatuses::getStatusId('sent'));
    }

    public function scopeApproved($query)
    {
        return $query->where('invest_status_id', '=', InvestStatuses::getStatusId('approved'));
    }

    public function scopeRejected($query)
    {
        return $query->where('invest_status_id', '=', InvestStatuses::getStatusId('rejected'));
    }

    public function scopeCanceled($query)
    {
        return $query->where('invest_status_id', '=', InvestStatuses::getStatusId('canceled'));
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
            case 'approved':
                $query->approved();
                break;
            case 'rejected':
                $query->rejected();
                break;
            case 'canceled':
                $query->canceled();
                break;
        }

        return $query;
    }

    public function scopeYearToDate($query)
    {
        return $query->where('invest_date', '>=', date('Y') . '-01-01')
            ->where('invest_date', '<=', date('Y') . '-12-31');
    }

    public function scopeThisQuarter($query)
    {
        return $query->where('invest_date', '>=', Carbon::now()->firstOfQuarter())
            ->where('invest_date', '<=', Carbon::now()->lastOfQuarter());
    }

    public function scopeDateRange($query, $fromDate, $toDate)
    {
        return $query->where('invest_date', '>=', $fromDate)
            ->where('invest_date', '<=', $toDate);
    }

    public function scopeKeywords($query, $keywords)
    {
        if ($keywords) {
            $keywords = strtolower($keywords);

            $query->where(DB::raw('lower(number)'), 'like', '%' . $keywords . '%')
                ->orWhere('invests.invest_date', 'like', '%' . $keywords . '%')
                ->orWhere('expires_at', 'like', '%' . $keywords . '%')
                ->orWhere('summary', 'like', '%' . $keywords . '%')
                ->orWhereIn('client_id', function ($query) use ($keywords) {
                    $query->select('id')->from('clients')->where(DB::raw("CONCAT_WS('^',LOWER(name),LOWER(unique_name))"), 'like', '%' . $keywords . '%');
                });
        }

        return $query;
    }
}
