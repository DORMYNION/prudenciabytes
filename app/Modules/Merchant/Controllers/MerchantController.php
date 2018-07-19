<?php

namespace FI\Modules\Merchant\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Loans\Models\Loan;
use FI\Modules\Merchant\Support\MerchantFactory;

class MerchantController extends Controller
{
    public function pay()
    {
        $merchant = MerchantFactory::create(request('driver'));

        $loan = Loan::where('url_key', request('urlKey'))->first();

        try {
            if ($merchant->isRedirect()) {
                return [
                    'redirect' => 1,
                    'url' => $merchant->pay($loan),
                ];
            } else {
                return [
                    'redirect' => 0,
                    'modalContent' => view('merchant.' . strtolower(request('driver')))
                        ->with('driver', MerchantFactory::create(request('driver')))
                        ->with('loan', $loan)
                        ->with('urlKey', request('urlKey'))
                        ->render(),
                ];
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return redirect()->route('clientCenter.public.loan.show', [request('urlKey')])
                ->with('alert', $e->getMessage());
        }
    }

    public function cancelUrl($driver, $urlKey)
    {
        return redirect()->route('clientCenter.public.loan.show', [$urlKey]);
    }

    public function returnUrl($driver, $urlKey)
    {
        $loan = Loan::where('url_key', $urlKey)->first();

        $merchant = MerchantFactory::create($driver);

        if ($merchant->verify($loan)) {
            $messageStatus = 'alertSuccess';
            $messageContent = trans('fi.payment_applied');
        } else {
            $messageStatus = 'error';
            $messageContent = trans('fi.error_applying_payment');
        }

        return redirect()->route('clientCenter.public.loan.show', [$urlKey])
            ->with($messageStatus, $messageContent);
    }

    public function webhookUrl($driver, $urlKey)
    {
        $loan = Loan::where('url_key', $urlKey)->first();

        $merchant = MerchantFactory::create($driver);

        $merchant->verify($loan);
    }
}