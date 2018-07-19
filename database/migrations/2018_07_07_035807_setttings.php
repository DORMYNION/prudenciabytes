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
use FI\Modules\Settings\Models\Setting;
use FI\Modules\Users\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class Setttings extends Migration
{

    public function up()    {
      $maxDisplayOrder = Setting::where('setting_key', 'like', 'widgetDisplayOrder%')->max('setting_value');
      $settings = [
          'addressFormat' => "{{ address }}\r\n{{ city }}, {{ state }} {{ postal_code }}",
          'allowPaymentsWithoutBalance' => 0,
          'amountDecimals' => 2,
          'attachPdf' => 1,
          'automaticEmailOnRecur' => 1,
          'convertInvestTerms' => 'invest',
          'convertInvestWhenApproved' => 1,
          'currencyConversionDriver' => 'YQLCurrencyConverter',
          'dashboardTotals' => 'year_to_date',
          'dateFormat' => 'm/d/Y',
          'displayClientUniqueName' => '0',
          'displayProfileImage' => '1',
          'headerTitleText' => 'Prudencia Bytes',
          'loansDueAfter' => 30,
          'loanGroup' => 1,
          'loanEmailBody' => '<p>To view your loan from {{ $loan->user->name }} for {{ $loan->amount->formatted_total }}, click the link below:</p>' . "\r\n\r\n" . '<p><a href="{{ $loan->public_url }}">{{ $loan->public_url }}</a></p>',
          'loanEmailSubject' => 'Loan #{{ $loan->number }}',
          'loanStatusFilter' => 'all_statuses',
          'loanTemplate' => 'default.blade.php',
          'language' => 'en',
          'markLoansSentPdf' => 0,
          'markInvestsSentPdf' => 0,
          'merchant_Stripe_enableBitcoinPayments', 0,
          'overdueLoanEmailBody' => '<p>This is a reminder to let you know your loan from {{ $loan->user->name }} for {{ $loan->amount->formatted_total }} is overdue. Click the link below to view the loan:</p>' . "\r\n\r\n" . '<p><a href="{{ $loan->public_url }}">{{ $loan->public_url }}</a></p>',
          'overdueLoanEmailSubject' => 'Overdue Loan Reminder: Loan #{{ $loan->number }}',
          'paperOrientation' => 'portrait',
          'paperSize' => 'a4',
          'paymentReceiptBody' => '<p>Thank you! Your payment of {{ $payment->formatted_amount }} has been applied to Loan #{{ $payment->loan->number }}.</p>',
          'paymentReceiptEmailSubject' => 'Payment Receipt for Loan #{{ $payment->loan->number }}',
          'pdfDriver' => 'domPDF',
          'profileImageDriver' => 'Gravatar',
          'investApprovedEmailBody' => '<p><a href="{{ $invest->public_url }}">Invest #{{ $invest->number }}</a> has been APPROVED.</p>',
          'investEmailSubject' => 'Invest #{{ $invest->number }}',
          'investsExpireAfter' => 15,
          'investGroup' => 2,
          'investEmailBody' => '<p>To view your invest from {{ $invest->user->name }} for {{ $invest->amount->formatted_total }}, click the link below:</p>' . "\r\n\r\n" . '<p><a href="{{ $invest->public_url }}">{{ $invest->public_url }}</a></p>',
          'investRejectedEmailBody' => '<p><a href="{{ $invest->public_url }}">Invest #{{ $invest->number }}</a> has been REJECTED.</p>',
          'investStatusFilter' => 'all_statuses',
          'investTemplate' => 'default.blade.php',
          'resultsPerPage' => 15,
          'roundTaxDecimals' => 3,
          'skin' => 'skin-invoiceplane.min.css',
          'timezone' => 'Africa/Lagos',
          'upcomingPaymentNoticeEmailBody' => '<p>This is a notice to let you know your loan from {{ $loan->user->name }} for {{ $loan->amount->formatted_total }} is due on {{ $loan->formatted_due_at }}. Click the link below to view the loan:</p>' . "\r\n\r\n" . '<p><a href="{{ $loan->public_url }}">{{ $loan->public_url }}</a></p>',
          'upcomingPaymentNoticeEmailSubject' => 'Upcoming Payment Due Notice: Loan #{{ $loan->number }}',
          'widgetColumnWidthClientActivity' => 4,
          'widgetColumnWidthLoanSummary' => 6,
          'widgetColumnWidthInvestSummary' => 6,
          'widgetDisplayOrderClientActivity' => ($maxDisplayOrder + 1),
          'widgetDisplayOrderLoanSummary' => 1,
          'widgetDisplayOrderInvestSummary' => 2,
          'widgetEnabledClientActivity' => 0,
          'widgetEnabledLoanSummary' => 1,
          'widgetEnabledInvestSummary' => 1,
          'widgetLoanSummaryDashboardTotals' => 'year_to_date',
          'widgetInvestSummaryDashboardTotals' => 'year_to_date',
          'version' => '2.0.0-alpha1',
      ];

      foreach ($settings as $key => $value) {
          Setting::saveByKey($key, $value);
      }

      if (Setting::getByKey('widgetLoanSummaryDashboardTotals') == 'custom_date_range')      {
          Setting::saveByKey('widgetLoanSummaryDashboardTotals', 'year_to_date');
          Setting::deleteByKey('widgetLoanSummaryDashboardTotalsFromDate');
          Setting::deleteByKey('widgetLoanSummaryDashboardTotalsToDate');
      }

      if (Setting::getByKey('widgetInvestSummaryDashboardTotals') == 'custom_date_range')      {
          Setting::saveByKey('widgetInvestSummaryDashboardTotals', 'year_to_date');
          Setting::deleteByKey('widgetInvestSummaryDashboardTotalsFromDate');
          Setting::deleteByKey('widgetInvestSummaryDashboardTotalsToDate');
      }




      foreach ($settings as $key => $value) {
          Setting::saveByKey($key, $value);
      }

      // Client language
      Client::where('language', null)->update(['language' => Setting::getByKey('language')]);

      // Merchant Settings
      $merchantConfig = json_decode(Setting::getByKey('merchant'), true);

      Setting::saveByKey('merchant', json_encode([
          'PayPalExpress' => ['enabled' => 0, 'username' => '', 'password' => '', 'signature' => ''],
          'Stripe' => ['enabled' => 0, 'secretKey' => '', 'publishableKey' => ''],
          'Mollie' => ['enabled' => 0, 'apiKey' => ''],
      ]));

      $merchantConfig['Stripe']['requireBillingName']    = 0;
      $merchantConfig['Stripe']['requireBillingAddress'] = 0;
      $merchantConfig['Stripe']['requireBillingCity']    = 0;
      $merchantConfig['Stripe']['requireBillingState']   = 0;
      $merchantConfig['Stripe']['requireBillingZip']     = 0;
      $merchantConfig['PayPalExpress']['testMode'] = 0;
      $merchantConfig['PayPalExpress']['paymentButtonText'] = 'Pay with PayPal';
      $merchantConfig['Stripe']['paymentButtonText']        = 'Pay with Stripe';
      $merchantConfig['Mollie']['paymentButtonText']        = 'Pay with Mollie';

      Setting::saveByKey('merchant', json_encode($merchantConfig));

      $merchantSettings = json_decode(Setting::where('setting_key', 'merchant')->first()->setting_value, true);

      Setting::whereIn('setting_key', [
          'merchant_Stripe_enabled',
          'merchant_Stripe_publishableKey',
          'merchant_Stripe_secretKey',
          'merchant_Stripe_paymentButtonText',
          'merchant_PayPal_paymentButtonText',
          'merchant_Mollie_enabled',
          'merchant_Mollie_apiKey',
          'merchant_Mollie_paymentButtonText',
      ])->delete();

      if (isset($merchantSettings['Stripe']['enabled']))
      {
          Setting::create(['setting_key' => 'merchant_Stripe_enabled', 'setting_value' => $merchantSettings['Stripe']['enabled']]);
      }

      if (isset($merchantSettings['Stripe']['publishableKey']))
      {
          Setting::create(['setting_key' => 'merchant_Stripe_publishableKey', 'setting_value' => $merchantSettings['Stripe']['publishableKey']]);
      }

      if (isset($merchantSettings['Stripe']['secretKey']))
      {
          Setting::create(['setting_key' => 'merchant_Stripe_secretKey', 'setting_value' => $merchantSettings['Stripe']['secretKey']]);
      }

      if (isset($merchantSettings['Stripe']['paymentButtonText']))
      {
          Setting::create(['setting_key' => 'merchant_Stripe_paymentButtonText', 'setting_value' => $merchantSettings['Stripe']['paymentButtonText']]);
      }
      else
      {
          Setting::create(['setting_key' => 'merchant_Stripe_paymentButtonText', 'setting_value' => 'Pay with Stripe']);
      }

      if (isset($merchantSettings['PayPalExpress']['paymentButtonText']))
      {
          Setting::create(['setting_key' => 'merchant_PayPal_paymentButtonText', 'setting_value' => $merchantSettings['PayPalExpress']['paymentButtonText']]);
      }
      else
      {
          Setting::create(['setting_key' => 'merchant_PayPal_paymentButtonText', 'setting_value' => 'Pay with PayPal']);
      }

      if (isset($merchantSettings['Mollie']['enabled']))
      {
          Setting::create(['setting_key' => 'merchant_Mollie_enabled', 'setting_value' => $merchantSettings['Mollie']['enabled']]);
      }

      if (isset($merchantSettings['Mollie']['apiKey']))
      {
          Setting::create(['setting_key' => 'merchant_Mollie_apiKey', 'setting_value' => $merchantSettings['Mollie']['apiKey']]);
      }

      if (isset($merchantSettings['Mollie']['paymentButtonText']))
      {
          Setting::create(['setting_key' => 'merchant_Mollie_paymentButtonText', 'setting_value' => $merchantSettings['Mollie']['paymentButtonText']]);
      }
      else
      {
          Setting::create(['setting_key' => 'merchant_Mollie_paymentButtonText', 'setting_value' => 'Pay with Mollie']);
      }

      //Currency
      Currency::create(['name' => 'Australian Dollar', 'code' => 'AUD', 'symbol' => '$', 'placement' => 'before', 'decimal' => '.', 'thousands' => ',']);
      Currency::create(['name' => 'Canadian Dollar', 'code' => 'CAD', 'symbol' => '$', 'placement' => 'before', 'decimal' => '.', 'thousands' => ',']);
      Currency::create(['name' => 'Euro', 'code' => 'EUR', 'symbol' => '€', 'placement' => 'before', 'decimal' => '.', 'thousands' => ',']);
      Currency::create(['name' => 'Nigerian Naira', 'code' => 'NGN', 'symbol' => '&#x20A6;', 'placement' => 'before', 'decimal' => '.', 'thousands' => ',']);
      Currency::create(['name' => 'Pound Sterling', 'code' => 'GBP', 'symbol' => '£', 'placement' => 'before', 'decimal' => '.', 'thousands' => ',']);
      Currency::create(['name' => 'US Dollar', 'code' => 'USD', 'symbol' => '$', 'placement' => 'before', 'decimal' => '.', 'thousands' => ',']);

      Setting::saveByKey('baseCurrency', 'NGN');
      Setting::saveByKey('exchangeRateMode', 'automatic');

      $baseCurrency = Setting::where('setting_key', 'baseCurrency')->first()->setting_value;

      // There may be some records with null currency_code values so we need to update these
      DB::table('clients')->whereNull('currency_code')->update(['currency_code' => $baseCurrency]);

      // Payment
      if (PaymentMethod::count() == 0) {
          PaymentMethod::create(['name' => trans('fi.cash')]);
          PaymentMethod::create(['name' => trans('fi.credit_card')]);
          PaymentMethod::create(['name' => trans('fi.online_payment')]);
      }

      // Template Default
      Loan::whereNull('template')->orWhere('template', '')->update(['template' => config('fi.loanTemplate')]);
      Invest::whereNull('template')->orWhere('template', '')->update(['template' => config('fi.investTemplate')]);



      DB::table('loans')->whereIn('id', function ($query) {
          $query->select('audit_id')->from('activities')->where('audit_type', 'FI\Modules\Loans\Models\Loan');
      })->update(['viewed' => 1]);

      DB::table('invests')->whereIn('id', function ($query) {
          $query->select('audit_id')->from('activities')->where('audit_type', 'FI\Modules\Invests\Models\Invest');
      })->update(['viewed' => 1]);

      // Fix Date
      $loansDueAfter = Setting::getByKey('loansDueAfter');

      if (is_numeric($loansDueAfter)) {
          $loans = Loan::where('due_at', '0000-00-00')->get();

          foreach ($loans as $loan) {
              $loan->due_at = Carbon::createFromFormat('Y-m-d H:i:s', $loan->created_at)->addDays($loansDueAfter);

              $loan->save();
          }
      }

      Payment::where('paid_at', '0000-00-00')->update(['paid_at' => DB::raw('created_at')]);


      // Email Template
      $emailTemplates = [
          'loanEmailBody',
          'investEmailBody',
          'overdueLoanEmailBody',
          'upcomingPaymentNoticeEmailBody',
          'investApprovedEmailBody',
          'investRejectedEmailBody',
          'paymentReceiptBody',
          'investEmailSubject',
          'loanEmailSubject',
          'overdueLoanEmailSubject',
          'upcomingPaymentNoticeEmailSubject',
          'paymentReceiptEmailSubject',
      ];

      $findReplace = [
          'user->company'           => 'companyProfile->company',
          'user->formatted_address' => 'companyProfile->formatted_address',
          'user->phone'             => 'companyProfile->phone',
          'user->fax'               => 'companyProfile->fax',
          'user->mobile'            => 'companyProfile->mobile',
          'user->web'               => 'companyProfile->web',
          'user->address'           => 'companyProfile->address',
          'user->city'              => 'companyProfile->city',
          'user->state'             => 'companyProfile->state',
          'user->zip'               => 'companyProfile->zip',
          'user->country'           => 'companyProfile->country',
      ];

      foreach ($emailTemplates as $emailTemplate)
      {
          $template = Setting::getByKey($emailTemplate);

          foreach ($findReplace as $find => $replace)
          {
              $template = str_replace($find, $replace, $template);
          }

          Setting::saveByKey($emailTemplate, $template);
      }

      Setting::writeEmailTemplates();

      Setting::deleteByKey('logo');
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
