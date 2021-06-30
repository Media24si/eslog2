<?php

namespace Media24si\eSlog2;

class TaxSummary
{
    public float $rate;
    public float $amount;
    public float $base;
    public string $type = 'S';

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

    public function setBase(float $base): TaxSummary
    {
        $this->base = $base;

        return $this;
    }

    public function setType(string $type): TaxSummary
    {
        $this->type = $type;

        return $this;
    }

    public function generateXml(): \SimpleXMLElement
    {
        $xml = new \SimpleXMLElement('<G_SG52></G_SG52>');

        $tax = $xml->addChild('S_TAX');
        $tax->addChild('D_5283', 7);

        $tax->addChild('C_C241')
            ->addChild('D_5153', 'VAT');
        $tax->addChild('C_C243')
            ->addChild('D_5278', round($this->rate, 2));
        $tax->addChild('D_5305', $this->type);

        $amount = $xml->addChild('S_MOA')
            ->addChild('C_C516');
        $amount->addChild('D_5025', '125');
        $amount->addChild('D_5004', round($this->base, 2));

        $amount_tax = $xml->addChild('S_MOA')
            ->addChild('C_C516');
        $amount_tax->addChild('D_5025', '124');
        $amount_tax->addChild('D_5004', round($this->amount, 2));

        return $xml;
    }
}
