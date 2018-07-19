<?php

namespace FI\Modules\Merchant\Support\Drivers;

use FI\Modules\Loans\Models\Loan;
use FI\Modules\Merchant\Models\MerchantPayment;
use FI\Modules\Merchant\Support\MerchantDriverPayable;
use FI\Modules\Payments\Models\Payment as FIPayment;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;

class PayPal extends MerchantDriverPayable
{
    protected $isRedirect = true;

    public function getSettings()
    {
        return ['clientId', 'clientSecret', 'mode' => ['sandbox' => trans('fi.sandbox'), 'live' => trans('fi.live')]];
    }

    public function pay(Loan $loan)
    {
        $apiContext = $this->getApiContext();

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item = new Item();
        $item->setName(trans('fi.loan') . ' #' . $loan->number)
            ->setCurrency($loan->currency_code)
            ->setQuantity(1)
            ->setPrice($loan->amount->balance + 0);

        $itemList = new ItemList();
        $itemList->setItems([$item]);

        $amount = new Amount();
        $amount->setCurrency($loan->currency_code)
            ->setTotal($loan->amount->balance + 0);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setDescription(trans('fi.loan') . ' #' . $loan->number)
            ->setLoanNumber(uniqid())
            ->setItemList($itemList);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('merchant.returnUrl', [$this->getName(), $loan->url_key]))
            ->setCancelUrl(route('merchant.cancelUrl', [$this->getName(), $loan->url_key]));

        $payment = new Payment();

        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);

        try {
            $payment->create($apiContext);

            return $payment->getApprovalLink();
        } catch (PayPalConnectionException $ex) {
            \Log::info($ex->getData());
        }
    }

    private function getApiContext()
    {
        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $this->getSetting('clientId'),
                $this->getSetting('clientSecret')
            )
        );

        $apiContext->setConfig(['mode' => $this->getSetting('mode')]);

        return $apiContext;
    }

    public function verify(Loan $loan)
    {
        $paymentId = request('paymentId');
        $apiContext = $this->getApiContext();
        $payment = Payment::get($paymentId, $apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId(request('PayerID'));

        $transaction = new Transaction();
        $amount = new Amount();

        $amount->setCurrency($loan->currency_code)
            ->setTotal($loan->amount->balance + 0);

        $transaction->setAmount($amount);

        $execution->addTransaction($transaction);

        $payment->execute($execution, $apiContext);

        $payment = Payment::get($paymentId, $apiContext);

        if ($payment->getState() == 'approved') {
            foreach ($payment->getTransactions() as $transaction) {
                $fiPayment = FIPayment::create([
                    'loan_id' => $loan->id,
                    'amount' => $transaction->getAmount()->getTotal(),
                    'payment_method_id' => config('fi.onlinePaymentMethod'),
                ]);

                MerchantPayment::saveByKey($this->getName(), $fiPayment->id, 'id', $payment->getId());
            }

            return true;
        }

        return false;
    }
}