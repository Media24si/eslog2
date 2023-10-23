<?php

namespace Media24si\eSlog2\Segments;

class MonetaryAmount
{
    public float $amount;
    public int $code;

    public function setAmount(float $amount): MonetaryAmount
    {
        $this->amount = $amount;

        return $this;
    }

    public function setCode(int $code): MonetaryAmount
    {
        $this->code = $code;

        return $this;
    }

    public function generateXml(): \SimpleXMLElement
    {
        $xml = new \SimpleXMLElement('<root></root>');

        $monetaryAmount = $xml->addChild('S_MOA');
                $c516 = $monetaryAmount->addChild('C_C516');
                    $c516->addChild('D_5025', $this->code);
                    $c516->addChild('D_5004', $this->amount);

        return $xml;
    }
}
