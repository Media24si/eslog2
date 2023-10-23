<?php

namespace Media24si\eSlog2\Segments;

class PercentageDetail
{
    public float $percentage;
    public int $code;

    public function setPercentage(float $percentage): PercentageDetail
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function setCode(int $code): PercentageDetail
    {
        $this->code = $code;

        return $this;
    }

    public function generateXml(): \SimpleXMLElement
    {
        $xml = new \SimpleXMLElement('<root></root>');

        $percentageDetail = $xml->addChild('S_PCD');
                $c501 = $percentageDetail->addChild('C_C501');
                    $c501->addChild('D_5245', $this->code);
                    $c501->addChild('D_5482', round($this->percentage, 2));

        return $xml;
    }
}
