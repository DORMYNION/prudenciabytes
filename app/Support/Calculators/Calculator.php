<?php

namespace FI\Support\Calculators;

abstract class Calculator
{
    /**
     * The id of the invest or loan.
     *
     * @var int
     */
    protected $id;

    /**
     * An array to store items.
     *
     * @var array
     */
    protected $items = [];

    /**
     * An array to store calculated item amounts.
     *
     * @var array
     */
    protected $a_total = [];

    /**
     * An array to store overall calculated amounts.
     *
     * @var array
     */
    protected $calculatedAmount = [];

    /**
     * Whether or not the document is canceled.
     *
     * @var boolean
     */
    protected $isCanceled = false;

    protected $discount;

    /**
     * Initialize the calculated amount array.
     */
    public function __construct()
    {
        $this->calculatedAmount = [
            'subtotal' => 0,
            'discount' => 0,
            'tax' => 0,
            'total' => 0,
        ];
    }

    /**
     * Sets the id.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    public function setIsCanceled($isCanceled)
    {
        $this->isCanceled = $isCanceled;
    }

    /**
     * Adds a item for calculation.
     *
     * @param int   $itemId
     * @param float $price
     * @param float $tenor
     * @param float $interest
     */
    public function addItem($itemId, $quantity, $price, $tenor = 0.00, $interest = 0.00)
    {
        $this->items[] = [
            'itemId' => $itemId,
            'price' => $price,
            'tenor' => $tenor,
            'interest' => $interest,
        ];
    }

    /**
     * Call the calculation methods.
     */
    public function calculate()
    {
        $this->calculateItems();
    }

    /**
     * Calculates the items.
     */
    protected function calculateItems()
    {
        foreach ($this->items as $item) {
            $principal  = $item['price'];
            $tenor  = $item['tenor'];
            $interest  = $item['interest'];


            $subtotal = round($item['price'], 2);

            $discount = $subtotal * ($this->discount / 100);
            $discountedSubtotal = $subtotal - $discount;

            if ($item['tenor']) {
                // if (!$item['calculateVat']) {
                //     $tax1 = round($discountedSubtotal * ($item['taxRatePercent'] / 100), config('fi.roundTaxDecimals'));
                // } else {
                //     $tax1 = $discountedSubtotal - ($discountedSubtotal / (1 + $item['taxRatePercent'] / 100));
                //     $subtotal = $subtotal - $tax1;
                // }

            // } else {
            //     $tax1 = 0;
                $tenor_amount  = round($item['price'] / $item['tenor'], 2);
            }

            if ($item['interest']) {
                $all_total = 0;
            //     if ($item['taxRate2IsCompound']) {
            //         $tax2 = round(($discountedSubtotal + $tax1) * ($item['taxRate2Percent'] / 100), config('fi.roundTaxDecimals'));
            //     } else {
            //         $tax2 = round($discountedSubtotal * ($item['taxRate2Percent'] / 100), config('fi.roundTaxDecimals'));
            //     }
            // } else {
            //     $tax2 = 0;
                for ($i=$interest; $i = 0  ; $i--) {
                  $j = 1;
                  $this->a_total['sn'] = $j;
                  $this->a_total['principal'] = [$a_total['sn'] => $principal];
                  $m_total = (($principal * $interest) / 100) + $tenor_amount;
                  $this->a_total['m_total'] = [$a_total['principal'] => $m_total];
                  $j++;
                  $all_total += $m_total;
                  $principal = $principal - $m_total

                }
            }

            $interestTotal = $all_total - $item['price'];
            $total = $subtotal + $interestTotal;

            $this->a_total[] = [
                'item_id' => $item['itemId']
            ];

            $this->calculatedAmount['subtotal'] += $subtotal;
            $this->calculatedAmount['discount'] += $discount;
            $this->calculatedAmount['interest'] += $interestTotal;
            $this->calculatedAmount['total'] += $total;
        }
    }

    /**
     * Returns calculated item amounts.
     *
     * @return array
     */
    public function getCalculatedItemAmounts()
    {
        return $this->a_total;
    }

    /**
     * Returns overall calculated amount.
     *
     * @return array
     */
    public function getCalculatedAmount()
    {
        return $this->calculatedAmount;
    }
}
