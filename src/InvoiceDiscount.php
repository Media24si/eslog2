<?php

namespace Media24si\eSlog2;

use Media24si\eSlog2\Segments\Allowance;
use Media24si\eSlog2\Segments\AllowanceInformation;
use Media24si\eSlog2\Segments\MonetaryAmount;
use Media24si\eSlog2\Segments\PercentageDetail;

/**
 * A group of business terms providing information about allowances applicable to the Invoice as a whole.
 * BG-20
 */
class InvoiceDiscount
{
    public string $reason = '';
    public int $reasonCode = 95;
    public float $amount = 0;
    public float $baseAmount = 0;
    public float $percentage = 0;
    public float $vatRate = 0;
    public string $vatCategoryCode = TaxSummary::CODE_STANDARD_RATE;

    /**
     * Document level allowance reason (BT-97) 
     * The reason for the document level allowance, expressed as text.
     *
     * @param string $reason
     * @return InvoiceDiscount
     */
    public function setReason(string $reason): InvoiceDiscount
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Document level allowance reason code (BT-98) 
     * The reason for the document level allowance, expressed as a code. 
     * Use code list UNTDID 5189 — Allowance codes
     *
     * @param integer $reasonCode
     * @return InvoiceDiscount
     */
    public function setReasonCode(int $reasonCode): InvoiceDiscount
    {
        $this->reasonCode = $reasonCode;

        return $this;
    }

    /**
     * Document level allowance amount (BT-92) 
     * The amount of an allowance, without VAT.
     *
     * @param float $amount
     * @return InvoiceDiscount
     */
    public function setAmount(float $amount): InvoiceDiscount
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Document level allowance base amount (BT-93) 
     * The base amount that may be used, in conjunction with the document level allowance percentage (BT-94), 
     * to calculate the document level allowance amount (BT-92).
     *
     * @param float $baseAmount
     * @return InvoiceDiscount
     */
    public function setBaseAmount(float $baseAmount): InvoiceDiscount
    {
        $this->baseAmount = $baseAmount;

        return $this;
    }

    /**
     * Document level allowance percentage (BT-94) 
     * The percentage that may be used, in conjunction with the document level allowance base amount (BT-93), 
     * to calculate the document level allowance amount (BT-92).
     *
     * @param float $percentage
     * @return InvoiceDiscount
     */
    public function setPercentage(float $percentage): InvoiceDiscount
    {
        $this->percentage = $percentage;

        return $this;
    }

    /**
     * Document level allowance VAT category code (BT-95) 
     * A coded identification of what VAT category applies to the document level allowance.
     *
     * @param string $vatCategoryCode
     * @return InvoiceDiscount
     */
    public function setVatCategoryCode(string $vatCategoryCode): InvoiceDiscount
    {
        $this->vatCategoryCode = $vatCategoryCode;

        return $this;
    }

    /**
     * Document level allowance VAT rate (BT-96) 
     * The VAT rate, represented as percentage that applies to the document level allowance.
     *
     * @param float $vatRate
     * @return InvoiceDiscount
     */
    public function setVatRate(float $vatRate): InvoiceDiscount
    {
        $this->vatRate = $vatRate;

        return $this;
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
            // BT-94
            $sg19 = $xml->addChild('G_SG19');
            XMLHelpers::append(
                $sg19,
                (new PercentageDetail())
                    ->setPercentage($this->percentage)
                    ->setCode(1)
                    ->generateXml()
            );
        }

        // BT-92
        $sg20 = $xml->addChild('G_SG20');
        XMLHelpers::append(
            $sg20,
            (new MonetaryAmount())
                ->setAmount(round($this->amount, 2))
                ->setCode(204)
                ->generateXml()
        );
        
        if ($this->baseAmount) {
            // BT-93
            $sg20 = $xml->addChild('G_SG20');
            XMLHelpers::append(
                $sg20,
                (new MonetaryAmount())
                    ->setAmount(round($this->baseAmount, 2))
                    ->setCode(25)
                    ->generateXml()
            );
        }

        // BT-96
        $sg22 = $xml->addChild('G_SG22');
        XMLHelpers::append(
            $sg22, 
            (new TaxSummary())
                ->setRate($this->vatRate)
                ->setCategoryCode($this->vatCategoryCode)
                ->generateXml()
        );

        return $xml;
    }
}
