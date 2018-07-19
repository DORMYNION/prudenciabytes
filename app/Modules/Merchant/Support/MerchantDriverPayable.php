<?php

namespace FI\Modules\Merchant\Support;

use FI\Modules\Loans\Models\Loan;

abstract class MerchantDriverPayable extends MerchantDriver
{
    abstract public function pay(Loan $loan);
}