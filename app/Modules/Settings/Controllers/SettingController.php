<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Settings\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\CompanyProfiles\Models\CompanyProfile;
use FI\Modules\Currencies\Models\Currency;
use FI\Modules\Groups\Models\Group;
use FI\Modules\Loans\Support\LoanTemplates;
use FI\Modules\MailQueue\Support\MailSettings;
use FI\Modules\Merchant\Support\MerchantFactory;
use FI\Modules\PaymentMethods\Models\PaymentMethod;
use FI\Modules\Invests\Support\InvestTemplates;
use FI\Modules\Settings\Models\Setting;
use FI\Modules\Settings\Requests\SettingUpdateRequest;
use FI\Modules\TaxRates\Models\TaxRate;
use FI\Support\DashboardWidgets;
use FI\Support\DateFormatter;
use FI\Support\Languages;
use FI\Support\PDF\PDFFactory;
use FI\Support\Skins;
use FI\Support\Statuses\LoanStatuses;
use FI\Support\Statuses\InvestStatuses;
use FI\Support\UpdateChecker;
use Illuminate\Support\Facades\Crypt;

class SettingController extends Controller
{
    public function index()
    {
        try {
            Crypt::decrypt(config('fi.mailPassword'));
            session()->forget('error');
        } catch (\Exception $e) {
            // Do nothing, already done in Config Provider
        }

        return view('settings.index')
            ->with([
                'languages' => Languages::listLanguages(),
                'dateFormats' => DateFormatter::dropdownArray(),
                'loanTemplates' => LoanTemplates::lists(),
                'investTemplates' => InvestTemplates::lists(),
                'groups' => Group::getList(),
                'taxRates' => TaxRate::getList(),
                'paymentMethods' => PaymentMethod::getList(),
                'emailSendMethods' => MailSettings::listSendMethods(),
                'emailEncryptions' => MailSettings::listEncryptions(),
                'yesNoArray' => ['0' => trans('fi.no'), '1' => trans('fi.yes')],
                'timezones' => array_combine(timezone_identifiers_list(), timezone_identifiers_list()),
                'paperSizes' => ['letter' => trans('fi.letter'), 'A4' => trans('fi.a4'), 'legal' => trans('fi.legal')],
                'paperOrientations' => ['portrait' => trans('fi.portrait'), 'landscape' => trans('fi.landscape')],
                'currencies' => Currency::getList(),
                'exchangeRateModes' => ['automatic' => trans('fi.automatic'), 'manual' => trans('fi.manual')],
                'pdfDrivers' => PDFFactory::getDrivers(),
                'convertInvestOptions' => ['invest' => trans('fi.convert_invest_option1'), 'loan' => trans('fi.convert_invest_option2')],
                'clientUniqueNameOptions' => ['0' => trans('fi.client_unique_name_option_1'), '1' => trans('fi.client_unique_name_option_2')],
                'dashboardWidgets' => DashboardWidgets::listsByOrder(),
                'colWidthArray' => array_combine(range(1, 12), range(1, 12)),
                'displayOrderArray' => array_combine(range(1, 24), range(1, 24)),
                'merchant' => config('fi.merchant'),
                'skins' => Skins::lists(),
                'resultsPerPage' => array_combine(range(15, 100, 5), range(15, 100, 5)),
                'amountDecimalOptions' => ['0' => '0', '2' => '2', '3' => '3', '4' => '4'],
                'roundTaxDecimalOptions' => ['2' => '2', '3' => '3', '4' => '4'],
                'companyProfiles' => CompanyProfile::getList(),
                'merchantDrivers' => MerchantFactory::getDrivers(),
                'loanStatuses' => LoanStatuses::listsAllFlat() + ['overdue' => trans('fi.overdue')],
                'investStatuses' => InvestStatuses::listsAllFlat(),
                'loanWhenDraftOptions' => [0 => trans('fi.keep_loan_date_as_is'), 1 => trans('fi.change_loan_date_to_todays_date')],
                'investWhenDraftOptions' => [0 => trans('fi.keep_invest_date_as_is'), 1 => trans('fi.change_invest_date_to_todays_date')],
            ]);
    }

    public function update(SettingUpdateRequest $request)
    {
        foreach (request('setting') as $key => $value) {
            $skipSave = false;

            if ($key == 'mailPassword' and $value) {
                $value = Crypt::encrypt($value);
            } elseif ($key == 'mailPassword' and !$value) {
                $skipSave = true;
            }

            if ($key == 'merchant') {
                $value = json_encode($value);
            }

            if (!$skipSave) {
                Setting::saveByKey($key, $value);
            }
        }

        Setting::writeEmailTemplates();

        return redirect()->route('settings.index')
            ->with('alertSuccess', trans('fi.settings_successfully_saved'));
    }

    public function updateCheck()
    {
        $updateChecker = new UpdateChecker;

        $updateAvailable = $updateChecker->updateAvailable();
        $currentVersion = $updateChecker->getCurrentVersion();

        if ($updateAvailable) {
            $message = trans('fi.update_available', ['version' => $currentVersion]);
        } else {
            $message = trans('fi.update_not_available');
        }

        return response()->json(
            [
                'success' => true,
                'message' => $message,
            ], 200
        );
    }

    public function saveTab()
    {
        session(['settingTabId' => request('settingTabId')]);
    }
}