<?php

namespace Media24si\eSlog2\Segments;

class AllowanceInformation
{
    public string $reason;
    public int $code;

    public function setReason(string $reason): AllowanceInformation
    {
        $this->reason = $reason;

        return $this;
    }

    public function setCode(int $code): AllowanceInformation
    {
        $this->code = $code;

        return $this;
    }
    
    public function generateXml(): \SimpleXMLElement
    {
        $xml = new \SimpleXMLElement('<root></root>');

        $c552 = $xml->addChild('C_C552');
            $c552->addChild('D_1230', $this->reason);
            $c552->addChild('D_5189', $this->code);

        return $xml;
    }
}
