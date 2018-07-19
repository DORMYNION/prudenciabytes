<?php

namespace FI\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'FI\Events\AttachmentCreating' => [
            'FI\Events\Listeners\AttachmentCreatingListener',
        ],

        'FI\Events\AttachmentDeleted' => [
            'FI\Events\Listeners\AttachmentDeletedListener',
        ],

        'FI\Events\CheckAttachment' => [
            'FI\Events\Listeners\CheckAttachmentListener',
        ],

        'FI\Events\ClientCreated' => [
            'FI\Events\Listeners\ClientCreatedListener',
        ],

        'FI\Events\ClientCreating' => [
            'FI\Events\Listeners\ClientCreatingListener',
        ],

        'FI\Events\ClientDeleted' => [
            'FI\Events\Listeners\ClientDeletedListener',
        ],

        'FI\Events\ClientSaving' => [
            'FI\Events\Listeners\ClientSavingListener',
        ],

        'FI\Events\CompanyProfileCreated' => [
            'FI\Events\Listeners\CompanyProfileCreatedListener',
        ],

        'FI\Events\CompanyProfileCreating' => [
            'FI\Events\Listeners\CompanyProfileCreatingListener',
        ],

        'FI\Events\CompanyProfileDeleted' => [
            'FI\Events\Listeners\CompanyProfileDeletedListener',
        ],

        'FI\Events\CompanyProfileSaving' => [
            'FI\Events\Listeners\CompanyProfileSavingListener',
        ],

        'FI\Events\ExpenseCreated' => [
            'FI\Events\Listeners\ExpenseCreatedListener',
        ],

        'FI\Events\ExpenseDeleting' => [
            'FI\Events\Listeners\ExpenseDeletingListener',
        ],

        'FI\Events\ExpenseSaved' => [],

        'FI\Events\ExpenseSaving' => [
            'FI\Events\Listeners\ExpenseSavingListener',
        ],

        'FI\Events\LoanCreated' => [
            'FI\Events\Listeners\LoanCreatedListener',
        ],

        'FI\Events\LoanCreating' => [
            'FI\Events\Listeners\LoanCreatingListener',
        ],

        'FI\Events\LoanCreatedRecurring' => [
            'FI\Events\Listeners\LoanCreatedRecurringListener',
        ],

        'FI\Events\LoanDeleted' => [
            'FI\Events\Listeners\LoanDeletedListener',
        ],

        'FI\Events\LoanEmailing' => [
            'FI\Events\Listeners\LoanEmailingListener',
        ],

        'FI\Events\LoanEmailed' => [
            'FI\Events\Listeners\LoanEmailedListener',
        ],

        'FI\Events\LoanItemSaving' => [
            'FI\Events\Listeners\LoanItemSavingListener',
        ],

        'FI\Events\LoanModified' => [
            'FI\Events\Listeners\LoanModifiedListener',
        ],

        'FI\Events\LoanViewed' => [
            'FI\Events\Listeners\LoanViewedListener',
        ],

        'FI\Events\LoanCreated' => [
            'FI\Events\Listeners\LoanCreatedListener',
        ],

        'FI\Events\LoanCreating' => [
            'FI\Events\Listeners\LoanCreatingListener',
        ],

        'FI\Events\LoanCreatedRecurring' => [
            'FI\Events\Listeners\LoanCreatedRecurringListener',
        ],

        'FI\Events\LoanDeleted' => [
            'FI\Events\Listeners\LoanDeletedListener',
        ],

        'FI\Events\LoanEmailing' => [
            'FI\Events\Listeners\LoanEmailingListener',
        ],

        'FI\Events\LoanEmailed' => [
            'FI\Events\Listeners\LoanEmailedListener',
        ],

        'FI\Events\LoanItemSaving' => [
            'FI\Events\Listeners\LoanItemSavingListener',
        ],

        'FI\Events\LoanModified' => [
            'FI\Events\Listeners\LoanModifiedListener',
        ],

        'FI\Events\LoanViewed' => [
            'FI\Events\Listeners\LoanViewedListener',
        ],

        'FI\Events\NoteCreated' => [
            'FI\Events\Listeners\NoteCreatedListener',
        ],

        'FI\Events\OverdueNoticeEmailed' => [],

        'FI\Events\PaymentCreated' => [
            'FI\Events\Listeners\PaymentCreatedListener',
        ],

        'FI\Events\PaymentCreating' => [
            'FI\Events\Listeners\PaymentCreatingListener',
        ],

        'FI\Events\InvestCreated' => [
            'FI\Events\Listeners\InvestCreatedListener',
        ],

        'FI\Events\InvestCreating' => [
            'FI\Events\Listeners\InvestCreatingListener',
        ],

        'FI\Events\InvestDeleted' => [
            'FI\Events\Listeners\InvestDeletedListener',
        ],

        'FI\Events\InvestItemSaving' => [
            'FI\Events\Listeners\InvestItemSavingListener',
        ],

        'FI\Events\InvestModified' => [
            'FI\Events\Listeners\InvestModifiedListener',
        ],

        'FI\Events\InvestEmailed' => [
            'FI\Events\Listeners\InvestEmailedListener',
        ],

        'FI\Events\InvestEmailing' => [
            'FI\Events\Listeners\InvestEmailingListener',
        ],

        'FI\Events\InvestApproved' => [
            'FI\Events\Listeners\InvestApprovedListener',
        ],

        'FI\Events\InvestRejected' => [
            'FI\Events\Listeners\InvestRejectedListener',
        ],

        'FI\Events\InvestViewed' => [
            'FI\Events\Listeners\InvestViewedListener',
        ],

        'FI\Events\RecurringLoanCreated' => [
            'FI\Events\Listeners\RecurringLoanCreatedListener',
        ],

        'FI\Events\RecurringLoanCreating' => [
            'FI\Events\Listeners\RecurringLoanCreatingListener',
        ],

        'FI\Events\RecurringLoanDeleted' => [
            'FI\Events\Listeners\RecurringLoanDeletedListener',
        ],

        'FI\Events\RecurringLoanItemSaving' => [
            'FI\Events\Listeners\RecurringLoanItemSavingListener',
        ],

        'FI\Events\RecurringLoanModified' => [
            'FI\Events\Listeners\RecurringLoanModifiedListener',
        ],

        'FI\Events\SettingSaving' => [
            'FI\Events\Listeners\SettingSavingListener',
        ],

        'FI\Events\UserCreated' => [
            'FI\Events\Listeners\UserCreatedListener',
        ],

        'FI\Events\UserDeleted' => [
            'FI\Events\Listeners\UserDeletedListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
