<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Clients\Models;

use FI\Events\ClientCreated;
use FI\Events\ClientCreating;
use FI\Events\ClientDeleted;
use FI\Events\ClientSaving;
use FI\Support\CurrencyFormatter;
use FI\Support\Statuses\LoanStatuses;
use FI\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Client extends Model
{
    use Sortable;

    protected $guarded = ['id', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected $sortable = ['unique_name', 'email', 'phone', 'balance', 'active', 'custom'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($client) {
            event(new ClientCreating($client));
        });

        static::created(function ($client) {
            event(new ClientCreated($client));
        });

        static::saving(function ($client) {
            event(new ClientSaving($client));
        });

        static::deleted(function ($client) {
            event(new ClientDeleted($client));
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Static Methods
    |--------------------------------------------------------------------------
    */

    public static function firstOrCreateByUniqueName($uniqueName)
    {
        $client = self::firstOrNew([
            'unique_name' => $uniqueName,
        ]);

        if (!$client->id) {
            $client->name = $uniqueName;
            $client->save();
            return self::find($client->id);
        }

        return $client;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function attachments()
    {
        return $this->morphMany('FI\Modules\Attachments\Models\Attachment', 'attachable');
    }

    public function contacts()
    {
        return $this->hasMany('FI\Modules\Clients\Models\Contact');
    }

    public function currency()
    {
        return $this->belongsTo('FI\Modules\Currencies\Models\Currency', 'currency_code', 'code');
    }

    public function custom()
    {
        return $this->hasOne('FI\Modules\CustomFields\Models\ClientCustom');
    }

    public function expenses()
    {
        return $this->hasMany('FI\Modules\Expenses\Models\Expense');
    }

    public function loans()
    {
        return $this->hasMany('FI\Modules\Loans\Models\Loan');
    }

    public function merchant()
    {
        return $this->hasOne('FI\Modules\Merchant\Models\MerchantClient');
    }

    public function notes()
    {
        return $this->morphMany('FI\Modules\Notes\Models\Note', 'notable');
    }

    public function invests()
    {
        return $this->hasMany('FI\Modules\Invests\Models\Invest');
    }

    public function recurringLoans()
    {
        return $this->hasMany('FI\Modules\RecurringLoans\Models\RecurringLoan');
    }

    public function user()
    {
        return $this->hasOne('FI\Modules\Users\Models\User');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getAttachmentPathAttribute()
    {
        return attachment_path('clients/' . $this->id);
    }

    public function getAttachmentPermissionOptionsAttribute()
    {
        return ['0' => trans('fi.not_visible')];
    }

    public function getFormattedBalanceAttribute()
    {
        return CurrencyFormatter::format($this->balance, $this->currency);
    }

    public function getFormattedPaidAttribute()
    {
        return CurrencyFormatter::format($this->paid, $this->currency);
    }

    public function getFormattedTotalAttribute()
    {
        return CurrencyFormatter::format($this->total, $this->currency);
    }

    public function getFormattedAddressAttribute()
    {
        return nl2br(formatAddress($this));
    }

    public function getClientEmailAttribute()
    {
        return $this->email;
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeGetSelect()
    {
        return self::select('clients.*',
            DB::raw('(' . $this->getBalanceSql() . ') as balance'),
            DB::raw('(' . $this->getPaidSql() . ') AS paid'),
            DB::raw('(' . $this->getTotalSql() . ') AS total')
        );
    }

    private function getBalanceSql()
    {
        return DB::table('loan_amounts')->select(DB::raw('sum(balance)'))->whereIn('loan_id', function ($q) {
            $q->select('id')
                ->from('loans')
                ->where('loans.client_id', '=', DB::raw(DB::getTablePrefix() . 'clients.id'))
                ->where('loans.loan_status_id', '<>', DB::raw(LoanStatuses::getStatusId('canceled')));
        })->toSql();
    }

    private function getPaidSql()
    {
        return DB::table('loan_amounts')->select(DB::raw('sum(paid)'))->whereIn('loan_id', function ($q) {
            $q->select('id')->from('loans')->where('loans.client_id', '=', DB::raw(DB::getTablePrefix() . 'clients.id'));
        })->toSql();
    }

    /*
    |--------------------------------------------------------------------------
    | Subqueries
    |--------------------------------------------------------------------------
    */

    private function getTotalSql()
    {
        return DB::table('loan_amounts')->select(DB::raw('sum(total)'))->whereIn('loan_id', function ($q) {
            $q->select('id')->from('loans')->where('loans.client_id', '=', DB::raw(DB::getTablePrefix() . 'clients.id'));
        })->toSql();
    }

    public function scopeStatus($query, $status)
    {
        if ($status == 'active') {
            $query->where('active', 1);
        } elseif ($status == 'inactive') {
            $query->where('active', 0);
        }

        return $query;
    }

    public function scopeKeywords($query, $keywords)
    {
        if ($keywords) {
            $keywords = explode(' ', $keywords);

            foreach ($keywords as $keyword) {
                if ($keyword) {
                    $keyword = strtolower($keyword);

                    $query->where(DB::raw("CONCAT_WS('^',LOWER(name),LOWER(unique_name),LOWER(email),phone,fax,mobile)"), 'LIKE', "%$keyword%");
                }
            }
        }

        return $query;
    }
}