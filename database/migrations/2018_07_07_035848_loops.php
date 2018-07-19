<?php

use FI\Modules\Activity\Models\Activity;
use Carbon\Carbon;
use FI\Modules\Clients\Models\Client;
use FI\Modules\CompanyProfiles\Models\CompanyProfile;
use FI\Modules\CustomFields\Models\CompanyProfileCustom;
use FI\Modules\Currencies\Models\Currency;
use FI\Modules\Expenses\Models\Expense;
use FI\Modules\Loans\Models\Loan;
use FI\Modules\MailQueue\Models\MailQueue;
use FI\Modules\Payments\Models\Payment;
use FI\Modules\PaymentMethods\Models\PaymentMethod;
use FI\Modules\Invests\Models\Invest;
use FI\Modules\RecurringLoans\Models\RecurringLoan;
use FI\Modules\Settings\Models\Setting;
use FI\Modules\Users\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class Loops extends Migration
{
  public function up()
  {
      foreach (CompanyProfile::get() as $companyProfile)
      {
          $companyProfile->custom()->save(new CompanyProfileCustom());
      }

      foreach (Expense::get() as $expense)
      {
          $expense->custom()->save(new ExpenseCustom());
      }




      foreach (MailQueue::where('to', 'like', '"%')->get() as $record)
      {
          $record->to = json_encode([json_decode($record->to)]);
          $record->save();
      }

      foreach (MailQueue::where('cc', 'like', '"%')->get() as $record)
      {
          $record->cc = json_encode([json_decode($record->cc)]);
          $record->save();
      }

      foreach (MailQueue::where('bcc', 'like', '"%')->get() as $record)
      {
          $record->bcc = json_encode([json_decode($record->bcc)]);
          $record->save();
      }

      MailQueue::where('to', 'like', '{%')
          ->orWhere('cc', 'like', '{%')
          ->orWhere('bcc', 'like', '{%')
          ->delete();



      $loans = Loan::with('client')->whereNull('currency_code')->get();

      foreach ($loans as $loan) {
      $loan->currency_code = $loan->client->currency_code;
      $loan->save();
      }

      $invests = Invest::with('client')->whereNull('currency_code')->get();

      foreach ($invests as $invest) {
          $invest->currency_code = $invest->client->currency_code;
          $invest->save();
      }


      $recurringLoans = RecurringLoan::whereNotIn('id', function ($query)
      {
          $query->select('id')->from('recurring_loans_custom');
      })->get();

      foreach ($recurringLoans as $recurringLoan)
      {
          $recurringLoan->custom()->save(new RecurringLoanCustom());
      }

      // Insert missing client custom records.
      $clients = Client::whereNotIn('id', function ($query)
      {
          $query->select('client_id')->from('clients_custom');
      })->get();

      foreach ($clients as $client)
      {
          $client->custom()->save(new ClientCustom());
      }

      // Insert missing invest custom records.
      $invests = Invest::whereNotIn('id', function ($query)
      {
          $query->select('invest_id')->from('invests_custom');
      })->get();

      foreach ($invests as $invest)
      {
          $invest->custom()->save(new InvestCustom());
      }

      // Insert missing loan custom records.
      $loans = Loan::whereNotIn('id', function ($query)
      {
          $query->select('loan_id')->from('loans_custom');
      })->get();

      foreach ($loans as $loan)
      {
          $loan->custom()->save(new LoanCustom());
      }

      // Insert missing payment custom records.
      $payments = Payment::whereNotIn('id', function ($query)
      {
          $query->select('payment_id')->from('payments_custom');
      })->get();

      foreach ($payments as $payment)
      {
          $payment->custom()->save(new PaymentCustom());
      }

      // Insert missing user custom recors.
      $users = User::whereNotIn('id', function ($query)
      {
          $query->select('user_id')->from('users_custom');
      })->get();

      foreach ($users as $user)
      {
          $user->custom()->save(new UserCustom());
      }
  }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
