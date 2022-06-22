<?php

namespace Media24si\eSlog2;

class FreeText
{
    public const CODE_TERMS_OF_PAYMENTS = 'AAB';
    public const CODE_REGULATORY_INFORMATION = 'REG';
    public const CODE_ENTIRE_TRANSACTION_SET = 'GEN';
    public const CODE_DOCUMENTATION_INSTRUCTIONS = 'DOC';
    public const CODE_MODE_OF_SETTLEMENT_INFORMATION = 'AAT';
    public const CODE_EXEMPTION = 'AGM';
    public const CODE_PAYMENT_DETAIL_REMITTANCE_INFORMATION = 'PMD';
    public const CODE_TAX_DECLARATION = 'TXD';
    public const CODE_PAYMENT_INSTRUCTIONS_INFORMATION = 'PAI';
    public const CODE_PURPOSE_OF_SERVICE = 'ALQ';

    public ?string $textValue2 = null;
    public ?string $textValueCode = null;

    public function __construct(
        public string $textCode,
        public ?string $textValue = null
    ) {
    }

    public function setTextValue2(?string $textValue2): FreeText
    {
        $this->textValue2 = $textValue2;

        return $this;
    }

    public function setTextValueCode(?string $textValueCode): FreeText
    {
        $this->textValueCode = $textValueCode;

        return $this;
    }

    public function generateXml(): \SimpleXMLElement
    {
        $xml = new \Media24si\eSlog2\ExtendedSimpleXMLElement('<S_FTX></S_FTX>');

        $xml->addChild('D_4451', $this->textCode);

        if ($this->textValueCode !== null) {
            $xml->addChild('C_C107')
                ->addChild('D_4441', $this->textValueCode);
        }

        if ($this->textValue !== null) {
            $value = $xml->addChild('C_C108');
            $value->addChildWithCDATA('D_4440', htmlspecialchars(strip_tags($this->textValue)));

            if ($this->textValue2 !== null) {
                $value->addChildWithCDATA('D_4440_2', htmlspecialchars($this->textValue2));
            }
        }

        return $xml;
    }
}
