<?php

namespace Media24si\eSlog2\Segments;

use Media24si\eSlog2\XMLHelpers;

class Allowance
{
    public ?AllowanceInformation $allowanceInformation = null;

    public function setAllowanceInformation(AllowanceInformation $allowanceInformation): Allowance
    {
        $this->allowanceInformation = $allowanceInformation;

        return $this;
    }

    public function generateXml(): \SimpleXMLElement
    {
        $xml = new \SimpleXMLElement('<root></root>');

        $allowance = $xml->addChild('S_ALC');
            $allowance->addChild('D_5463', 'A');
        
        if ($this->allowanceInformation) {
            XMLHelpers::append($allowance, $this->allowanceInformation->generateXml());
        }

        return $xml;
    }
}
