<?php

namespace Media24si\eSlog2;

use Media24si\eSlog2\Segments\MonetaryAmount;

class TaxSummary
{
    public const CODE_STANDARD_RATE = 'S';
    public const CODE_ZERO_RATE = 'Z';
    public const CODE_EXEMPT_FROM_TAX = 'E';
    public const CODE_VAT_REVERSE_CHARGE = 'AE';
    public const CODE_VAT_EXEMPT_FOR_EEA_INTRA_COMMUNITY_SUPPLY_OF_GOODS_AND_SERVICES = 'K';
    public const CODE_FREE_EXPORT_ITEM_TAX_NOT_CHARGED = 'G';
    public const CODE_SERVICES_OUTSIDE_SCOPE_OF_TAX = 'O';
    public const CODE_CANARY_ISLANDS_GENERAL_INDIRECT_TAX = 'L';
    public const CODE_TAX_FOR_PRODUCTION_SERVICES_AND_IMPORTATION_IN_CEUTA_AND_MELILLA = 'M';

    public float $rate;
    public ?float $amount = null;
    public float $baseAmount = 0;
    public string $categoryCode = self::CODE_STANDARD_RATE;

    public function setRate(float $rate): TaxSummary
    {
        $this->rate = $rate;

        return $this;
    }

    public function setAmount(float $amount): TaxSummary
    {
        $this->amount = $amount;

        return $this;
    }

    public function setBaseAmount(float $baseAmount): TaxSummary
    {
        $this->baseAmount = $baseAmount;

        return $this;
    }

    public function setCategoryCode(string $categoryCode): TaxSummary
    {
        $this->categoryCode = $categoryCode;

        return $this;
    }

    public function generateXml(): \SimpleXMLElement
    {
        $xml = new \SimpleXMLElement('<root></root>');

        $tax = $xml->addChild('S_TAX');
        $tax->addChild('D_5283', 7);

        $tax->addChild('C_C241')
            ->addChild('D_5153', 'VAT');
        $tax->addChild('C_C243')
            ->addChild('D_5278', round($this->rate, 2));
        $tax->addChild('D_5305', $this->categoryCode);

        if ($this->amount !== null) {
            XMLHelpers::append(
                $xml,
                (new MonetaryAmount())
                    ->setAmount($this->amount)
                    ->setCode(124)
                    ->generateXml()
            );
        }

        if ($this->baseAmount) {
            XMLHelpers::append(
                $xml,
                (new MonetaryAmount())
                    ->setAmount($this->baseAmount)
                    ->setCode(125)
                    ->generateXml()
            );
        }

        return $xml;
    }
}
