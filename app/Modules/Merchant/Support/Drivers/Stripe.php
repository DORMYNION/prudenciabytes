<?php

namespace FI\Modules\Merchant\Support\Drivers;

use FI\Modules\Loans\Models\Loan;
use FI\Modules\Merchant\Models\MerchantClient;
use FI\Modules\Merchant\Models\MerchantPayment;
use FI\Modules\Merchant\Support\MerchantDriver;
use FI\Modules\Payments\Models\Payment;
use Stripe\Charge;
use Stripe\Customer;

class Stripe extends MerchantDriver
{
    protected $isRedirect = false;

    public function getSettings()
    {
        return ['publishableKey', 'secretKey'];
    }

    public function verify(Loan $loan)
    {
        \Stripe\Stripe::setApiKey($this->getSetting('secretKey'));

        $clientMerchantId = MerchantClient::getByKey($this->getName(), $loan->client_id, 'id');

        if ($clientMerchantId) {
            try {
                $customer = Customer::retrieve($clientMerchantId);
            } catch (\Exception $e) {
                // Don't need to do anything here.
            }
        }

        if (!isset($customer) or $customer->deleted) {
            $customer = $this->createCustomer($loan, request('token'));
        } else {
            $customer->source = request('token');
            $customer->save();
        }

        try {
            $charge = Charge::create([
                'customer' => $customer->id,
                'amount' => $loan->amount->balance * 100,
                'currency' => $loan->currency_code,
                'description' => trans('fi.loan') . ' #' . $loan->number,
            ]);

            $payment = Payment::create([
                'loan_id' => $loan->id,
                'amount' => $charge->amount / 100,
                'payment_method_id' => config('fi.onlinePaymentMethod'),
            ]);

            MerchantPayment::saveByKey($this->getName(), $payment->id, 'id', $charge->id);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function createCustomer($loan, $source)
    {
        $customer = Customer::create([
            'description' => $loan->client->name,
            'email' => $loan->client->email,
            'source' => $source,
        ]);

        MerchantClient::saveByKey($this->getName(), $loan->client_id, 'id', $customer->id);

        return $customer;
    }
}