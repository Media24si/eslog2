<?php

namespace Media24si\eSlog2;

use Media24si\eSlog2\Segments\Allowance;
use Media24si\eSlog2\Segments\AllowanceInformation;
use Media24si\eSlog2\Segments\MonetaryAmount;
use Media24si\eSlog2\Segments\PercentageDetail;

/**
 * A group of business terms providing information about allowances applicable to the individual Invoice line. (BG-27)
 */
class InvoiceItemDiscount
{
    public float $amount = 0;
    public float $baseAmount = 0;
    public float $percentage = 0;
    public string $reason = '';
    public int $reasonCode = 95;

    /**
     * Invoice line allowance amount (BT-136)
     * 
     * The amount of an allowance, without VAT.
     * 
     * @param float $amount
     * @return InvoiceItemDiscount
     */
    public function setAmount(float $amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Invoice line allowance base amount (BT-137)
     * 
     * The base amount that may be used, in conjunction with the Invoice line allowance percentage, to calculate the Invoice line allowance amount.
     * 
     * @param float $baseAmount
     * @return InvoiceItemDiscount
     */
    public function setBaseAmount(float $baseAmount)
    {
        $this->baseAmount = $baseAmount;

        return $this;
    }

    /**
     * Invoice line allowance percentage (BT-138)
     * 
     * The percentage that may be used, in conjunction with the Invoice line allowance base amount, to calculate the Invoice line allowance amount.
     * 
     * @param float $percentage
     * @return InvoiceItemDiscount
     */
    public function setPercentage(float $percentage)
    {
        $this->percentage = $percentage;

        return $this;
    }

    /**
     * Invoice line allowance reason (BT-139)
     * 
     * The reason for the Invoice line allowance, expressed as text.
     * 
     * @param string $reason
     * @return InvoiceItemDiscount
     */
    public function setReason(string $reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Invoice line allowance reason code (BT-140)
     * 
     * The reason for the Invoice line allowance, expressed as a code. Use code list UNTDID 5189 — Allowance codes
     * 
     * @param integer $reasonCode
     * @return InvoiceItemDiscount
     */
    public function setReasonCode(int $reasonCode)
    {
        $this->reasonCode = $reasonCode;

        return $this;
    }

    public function getAmount(): float
    {
        if ($this->amount) {
            return $this->amount;
        }

        if ($this->baseAmount && $this->percentage) {
            return bcdiv(bcmul($this->baseAmount, $this->percentage, 2), 100, 2);
        }

        return 0;
    }

    public function generateXml(): \SimpleXMLElement
    {
        $xml = new \SimpleXMLElement('<root></root>');

        XMLHelpers::append(
            $xml, 
            (new Allowance())
                ->setAllowanceInformation(
                    (new AllowanceInformation())
                        ->setReason($this->reason)
                        ->setCode($this->reasonCode)
                )
            ->generateXml()
        );

        if ($this->percentage) {
            // BT-138
            $sg41 = $xml->addChild('G_SG41');
            XMLHelpers::append(
                $sg41,
                (new PercentageDetail())
                    ->setPercentage($this->percentage)
                    ->setCode(1)
                    ->generateXml()
            );
        }

        if ($this->amount) {
            // BT-136
            $sg42 = $xml->addChild('G_SG42');
            XMLHelpers::append(
                $sg42,
                (new MonetaryAmount())
                    ->setAmount(round($this->amount, 2))
                    ->setCode(204)
                    ->generateXml()
            );
        }

        if ($this->baseAmount) {
            // BT-137
            $sg42 = $xml->addChild('G_SG42');
            XMLHelpers::append(
                $sg42,
                (new MonetaryAmount())
                    ->setAmount(round($this->baseAmount, 2))
                    ->setCode(25)
                    ->generateXml()
            );
        }

        return $xml;
    }
}
