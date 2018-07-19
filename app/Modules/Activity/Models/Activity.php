<?php

namespace FI\Modules\Activity\Models;

use FI\Support\DateFormatter;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activities';

    protected $guarded = ['id'];

    public function audit()
    {
        return $this->morphTo();
    }

    public function getFormattedActivityAttribute()
    {
        if ($this->audit) {
            switch ($this->audit_type) {
                case 'FI\Modules\Invests\Models\Invest':

                    switch ($this->activity) {
                        case 'public.viewed':
                            return trans('fi.activity_invest_viewed', ['number' => $this->audit->number, 'link' => route('invests.edit', [$this->audit->id])]);
                            break;

                        case 'public.approved':
                            return trans('fi.activity_invest_approved', ['number' => $this->audit->number, 'link' => route('invests.edit', [$this->audit->id])]);
                            break;

                        case 'public.rejected':
                            return trans('fi.activity_invest_rejected', ['number' => $this->audit->number, 'link' => route('invests.edit', [$this->audit->id])]);
                            break;
                    }

                    break;

                case 'FI\Modules\Loans\Models\Loan':

                    switch ($this->activity) {
                        case 'public.viewed':
                            return trans('ln.activity_loan_viewed', ['number' => $this->audit->number, 'link' => route('loans.edit', [$this->audit->id])]);
                            break;
                        case 'public.paid':
                            return trans('ln.activity_loan_paid', ['number' => $this->audit->number, 'link' => route('loans.edit', [$this->audit->id])]);
                            break;
                    }

                    break;
            }
        }

        return '';
    }

    public function getFormattedCreatedAtAttribute()
    {
        return DateFormatter::format($this->created_at, true);
    }
}
