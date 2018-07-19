<?php

 use FI\Modules\Activity\Models\Activity;
 use FI\Modules\CompanyProfiles\Models\CompanyProfile;
 use FI\Modules\CustomFields\Models\CompanyProfileCustom;
 use FI\Modules\Currencies\Models\Currency;
 use FI\Modules\Expenses\Models\Expense;
 use FI\Modules\MailQueue\Models\MailQueue;
 use FI\Modules\Settings\Models\Setting;
 use FI\Modules\Users\Models\User;
 use Illuminate\Database\Migrations\Migration;
 use Illuminate\Database\Schema\Blueprint;
 use Illuminate\Support\Facades\DB;

class Setup extends Migration{
    public function up()    {
      Schema::create('activities', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('audit_type');
          $table->string('activity');
          $table->integer('audit_id');
          $table->text('info')->nullable();

          $table->index('audit_type');
          $table->index('activity');
          $table->index('audit_id');
      });

      Schema::create('addons', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('name');
          $table->string('author_name');
          $table->string('author_url');
          $table->longText('navigation_menu')->nullable();
          $table->longText('system_menu')->nullable();
          $table->longText('navigation_reports')->nullable();
          $table->string('path');
          $table->boolean('enabled')->default(0);
      });

      Schema::create('attachments', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('user_id');
          $table->integer('attachable_id');
          $table->string('attachable_type');
          $table->integer('client_visibility');
          $table->string('filename');
          $table->string('mimetype');
          $table->integer('size');
          $table->string('url_key');
      });

      Schema::create('clients', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('name');
          $table->text('address')->nullable();
          $table->string('city')->nullable();
          $table->string('state')->nullable();
          $table->string('country')->nullable();
          $table->date('dob')->nullable();
          $table->string('marital_status')->nullable();
          $table->string('gender')->nullable();
          $table->string('children')->nullable();
          $table->string('monthly_income')->nullable();
          $table->string('phone')->nullable();
          $table->string('phone2')->nullable();
          $table->string('email')->nullable();
          $table->string('profile_img')->nullable();
          $table->string('nok')->nullable();
          $table->string('phone_nok')->nullable();
          $table->string('relationship_nok')->nullable();
          $table->string('address_nok')->nullable();
          $table->string('bank_name')->nullable();
          $table->string('acc_no')->nullable();
          $table->string('acc_name')->nullable();
          $table->string('url_key');
          $table->boolean('active')->default(1);
          $table->string('language')->nullable();
          $table->string('currency_code')->nullable();
          $table->string('unique_name')->nullable();

          $table->index('unique_name');
          $table->index('name');
          $table->index('active');
      });

      Schema::create('clients_custom', function (Blueprint $table) {
          $table->integer('client_id');
          $table->timestamps();

          $table->primary('client_id');
      });

      Schema::create('company_profiles', function (Blueprint $table)  {
          $table->increments('id');
          $table->timestamps();
          $table->string('company')->nullable();
          $table->text('address')->nullable();
          $table->string('city')->nullable();
          $table->string('state')->nullable();
          $table->string('zip')->nullable();
          $table->string('country')->nullable();
          $table->string('phone')->nullable();
          $table->string('fax')->nullable();
          $table->string('mobile')->nullable();
          $table->string('web')->nullable();
          $table->string('logo')->nullable();
          $table->string('invest_template');
          $table->string('loan_template');
      });

      Schema::create('company_profiles_custom', function (Blueprint $table) {
          $table->integer('company_profile_id');
          $table->timestamps();

          $table->primary('company_profile_id');
      });


      Schema::create('contacts', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('client_id');
          $table->string('name');
          $table->string('email');
          $table->boolean('default_to');
          $table->boolean('default_cc');
          $table->boolean('default_bcc');

          $table->index('client_id');
      });

      Schema::create('currencies', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('code');
            $table->string('name');
            $table->string('symbol');
            $table->string('placement');
            $table->string('decimal');
            $table->string('thousands');

            $table->index('name');
        });

      Schema::create('custom_fields', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('tbl_name');
          $table->string('column_name');
          $table->string('field_label');
          $table->string('field_type');
          $table->text('field_meta');

          $table->index('tbl_name');
      });

      Schema::create('expenses', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->date('expense_date');
          $table->integer('company_profile_id');
          $table->integer('user_id');
          $table->integer('category_id');
          $table->integer('client_id');
          $table->integer('vendor_id');
          $table->integer('loan_id');
          $table->string('description')->nullable();
          $table->decimal('amount', 15, 2);
          $table->decimal('tax', 20, 4);

          $table->index('category_id');
          $table->index('client_id');
          $table->index('company_profile_id');
          $table->index('vendor_id');
          $table->index('loan_id');
      });

      Schema::create('expense_vendors', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('name');
      });

      Schema::create('expense_categories', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('name');
      });

      Schema::create('expenses_custom', function (Blueprint $table) {
          $table->integer('expense_id');
          $table->timestamps();
          $table->integer('company_profile_id');

          $table->primary('expense_id');
          $table->index('company_profile_id');
      });

      Schema::create('groups', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('name');
          $table->integer('next_id')->default(1);
          $table->integer('left_pad')->default(0);
          $table->string('format');
          $table->integer('reset_number');
          $table->integer('last_id');
          $table->integer('last_year');
          $table->integer('last_month');
          $table->integer('last_week');
          $table->string('last_number');

      });

      Schema::create('loans', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->date('loan_date');
          $table->integer('user_id');
          $table->integer('client_id');
          $table->integer('group_id');
          $table->integer('loan_status_id');
          $table->date('due_at');
          $table->string('number');
          $table->text('terms')->nullable();
          $table->text('footer')->nullable();
          $table->string('url_key');
          $table->string('template')->nullable();
          $table->string('summary', 255)->change();
          $table->integer('company_profile_id');
          $table->string('currency_code')->nullable();
          $table->decimal('discount', 15, 2)->default(0.00);
          $table->decimal('exchange_rate', 10, 7)->default('1');
          $table->string('summary', 100)->nullable();
          $table->boolean('viewed')->default(0);

          $table->index('user_id');
          $table->index('client_id');
          $table->index('group_id');
          $table->index('loan_status_id');
          $table->index('company_profile_id');
      });

      Schema::create('loans_custom', function (Blueprint $table) {
          $table->integer('loan_id');
          $table->timestamps();

          $table->primary('loan_id');
      });

      Schema::create('loan_amounts', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('loan_id');
          $table->decimal('subtotal', 20, 4);
          $table->decimal('discount', 20, 4);
          $table->decimal('tax', 20, 4);
          $table->decimal('total', 20, 4);
          $table->decimal('paid', 20, 4);
          $table->decimal('balance', 20, 4);

          $table->index('loan_id');
      });


      Schema::create('loan_items', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('loan_id');
          $table->integer('tax_rate_id');
          $table->integer('tax_rate_2_id')->default(0);
          $table->string('name');
          $table->text('description');
          $table->decimal('quantity',  20, 4);
          $table->decimal('price',  20, 4);
          $table->integer('display_order')->default(0);

          $table->index('tax_rate_2_id');
          $table->index('loan_id');
          $table->index('tax_rate_id');
          $table->index('display_order');
      });

      Schema::create('loan_item_amounts', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('item_id');
          $table->decimal('subtotal',  20, 4);
          $table->decimal('tax_1',  20, 4);
          $table->decimal('tax_2',  20, 4);
          $table->decimal('tax',  20, 4);
          $table->decimal('total',  20, 4);

          $table->index('item_id');
      });

      Schema::create('loan_tax_rates', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('loan_id');
          $table->integer('tax_rate_id');
          $table->boolean('include_item_tax')->default(0);
          $table->decimal('tax_total', 15, 2)->default(0.00);

          $table->index('loan_id');
          $table->index('tax_rate_id');
      });

      Schema::create('loan_transactions', function (Blueprint $table)  {
          $table->increments('id');
          $table->timestamps();
          $table->integer('loan_id');
          $table->boolean('is_successful');
          $table->string('transaction_reference')->nullable();
      });

      Schema::create('item_lookups', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('name');
          $table->text('description');
          $table->decimal('tax_total',  20, 4);
          $table->integer('tax_rate_id')->default(0);
          $table->integer('tax_rate_2_id')->default(0);

          $table->index('tax_rate_id');
          $table->index('tax_rate_2_id');
      });

      Schema::create('mail_queue', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('mailable_id');
          $table->string('mailable_type');
          $table->string('from');
          $table->string('to');
          $table->string('cc');
          $table->string('bcc');
          $table->string('subject');
          $table->longText('body');
          $table->boolean('attach_pdf');
          $table->boolean('sent');
          $table->text('error')->nullable();
      });

      Schema::create('merchant_clients', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('driver');
          $table->integer('client_id');
          $table->string('merchant_key');
          $table->string('merchant_value');

          $table->index('driver');
          $table->index('client_id');
          $table->index('merchant_key');
      });

      Schema::create('merchant_payments', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('driver');
          $table->integer('payment_id');
          $table->string('merchant_key');
          $table->string('merchant_value');

          $table->index('driver');
          $table->index('payment_id');
          $table->index('merchant_key');
      });

      Schema::create('notes', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('user_id');
          $table->integer('notable_id');
          $table->string('notable_type');
          $table->longText('note');
          $table->boolean('private');
      });

      Schema::create('payments', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('loan_id');
          $table->integer('payment_method_id');
          $table->date('paid_at');
          $table->decimal('amount',  20, 4);
          $table->text('note');

          $table->index('loan_id');
          $table->index('payment_method_id');
          $table->index('amount');
      });

      Schema::create('payments_custom', function (Blueprint $table) {
          $table->integer('payment_id');
          $table->timestamps();

          $table->primary('payment_id');
      });

      Schema::create('payment_methods', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('name');
      });

      Schema::create('invests', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->date('invest_date');
          $table->integer('loan_id')->default('0');
          $table->integer('user_id');
          $table->integer('client_id');
          $table->integer('group_id');
          $table->integer('invest_status_id');
          $table->date('expires_at');
          $table->string('number');
          $table->text('footer')->nullable();
          $table->string('url_key');
          $table->string('template')->nullable();
          $table->integer('company_profile_id');
          $table->text('terms')->nullable();
          $table->string('summary', 255)->change();
          $table->decimal('discount', 15, 2)->default(0.00);
          $table->string('currency_code')->nullable();
          $table->decimal('exchange_rate', 10, 7)->default('1');
          $table->string('summary', 100)->nullable();
          $table->boolean('viewed')->default(0);

          $table->index('user_id');
          $table->index('client_id');
          $table->index('group_id');
          $table->index('number');
          $table->index('company_profile_id');
      });

      Schema::create('invests_custom', function (Blueprint $table) {
          $table->integer('invest_id');
          $table->timestamps();

          $table->primary('invest_id');
      });

      Schema::create('invest_amounts', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('invest_id');
          $table->decimal('subtotal',  20, 4);
          $table->decimal('discount',  20, 4);
          $table->decimal('tax',  20, 4);
          $table->decimal('total',  20, 4);

          $table->index('invest_id');
      });

      Schema::create('invest_items', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('invest_id');
          $table->integer('tax_rate_id');
          $table->integer('tax_rate_2_id')->default(0);
          $table->string('name');
          $table->text('description');
          $table->decimal('quantity',  20, 4);
          $table->decimal('price',  20, 4);
          $table->integer('display_order');

          $table->index('tax_rate_2_id');
          $table->index('invest_id');
          $table->index('display_order');
          $table->index('tax_rate_id');
      });

      Schema::create('invest_item_amounts', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('item_id');
          $table->decimal('subtotal',  20, 4);
          $table->decimal('tax_1',  20, 4);
          $table->decimal('tax_2',  20, 4);
          $table->decimal('tax',  20, 4);
          $table->decimal('total',  20, 4);

          $table->index('item_id');
      });

      Schema::create('invest_tax_rates', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('invest_id');
          $table->integer('tax_rate_id');
          $table->boolean('include_item_tax')->default(0);
          $table->decimal('tax_total', 15, 2)->default(0.00);

          $table->index('invest_id');
          $table->index('tax_rate_id');
      });

      Schema::create('recurring_loans', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('user_id');
          $table->integer('client_id');
          $table->integer('group_id');
          $table->integer('company_profile_id');
          $table->text('terms')->nullable();
          $table->text('footer')->nullable();
          $table->string('currency_code');
          $table->decimal('exchange_rate', 10, 7);
          $table->string('template');
          $table->string('summary', 100)->nullable();
          $table->decimal('discount', 15, 2);
          $table->integer('recurring_frequency');
          $table->integer('recurring_period');
          $table->date('next_date');
          $table->date('stop_date');

          $table->index('user_id');
          $table->index('client_id');
          $table->index('company_profile_id');
      });

      Schema::create('recurring_loan_amounts', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('recurring_loan_id');
          $table->decimal('subtotal', 20, 4);
          $table->decimal('discount', 20, 4);
          $table->decimal('tax', 20, 4);
          $table->decimal('total', 20, 4);

          $table->index('recurring_loan_id');
      });

      Schema::create('recurring_loan_items', function(Blueprint $table)  {
          $table->increments('id');
          $table->timestamps();
          $table->integer('recurring_loan_id');
          $table->integer('tax_rate_id')->default(0);
          $table->integer('tax_rate_2_id')->default(0);
          $table->string('name');
          $table->text('description');
          $table->decimal('quantity', 20, 4);
          $table->integer('display_order')->default(0);
          $table->decimal('price', 20, 4);

          $table->index('recurring_loan_id');
          $table->index('tax_rate_id');
          $table->index('tax_rate_2_id');
          $table->index('display_order');
      });

      Schema::create('recurring_loan_item_amounts', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('item_id');
          $table->decimal('subtotal', 20, 4);
          $table->decimal('tax_1', 20, 4);
          $table->decimal('tax_2', 20, 4);
          $table->decimal('tax', 20, 4);
          $table->decimal('total', 20, 4);

          $table->index('item_id');
      });

      Schema::create('recurring_loans_custom', function (Blueprint $table) {
          $table->integer('recurring_loan_id');
          $table->timestamps();

          $table->primary('recurring_loan_id');
      });

      Schema::create('settings', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('setting_key');
          $table->text('setting_value');

          $table->index('setting_key');
      });

      Schema::create('tax_rates', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('name');
          $table->decimal('percent', 5, 3)->default(0.00);
          $table->boolean('is_compound')->default(0);
          $table->boolean('calculate_vat');

      });

      Schema::create('users', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('email');
          $table->string('password');
          $table->string('name');
          $table->string('remember_token', 100)->nullable();
          $table->string('api_public_key')->nullable();
          $table->string('api_secret_key')->nullable();
          $table->integer('client_id');

          $table->index('client_id');
      });

      Schema::create('users_custom', function (Blueprint $table) {
          $table->integer('user_id');
          $table->timestamps();

          $table->primary('user_id');
      });

        DB::table('groups')->insert(
            [
                'name' => trans('fi.loan_default'),
                'next_id' => 1,
                'left_pad' => 0,
                'format' => 'INV{NUMBER}'
            ]
        );

        DB::table('groups')->insert(
            [
                'name' => trans('fi.invest_default'),
                'next_id' => 1,
                'left_pad' => 0,
                'format' => 'QUO{NUMBER}'
            ]
        );


    }

    public function down()   {
      Schema::table('tax_rates', function (Blueprint $table) {
          $table->dropColumn('calculate_vat');
      });
    }
}
