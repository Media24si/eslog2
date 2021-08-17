<?php

namespace Media24si\eSlog2;

class ReferenceDocument
{
    public const TYPE_PROFORMA_INVOICE = 'AAB';
    public const TYPE_DELIVERY_ORDER_NUMBER = 'AAJ';
    public const TYPE_DELIVERY_FORM = 'AAK';
    public const TYPE_BENEFICIARYS_REFERENCE = 'AFO';
    public const TYPE_PROJECT_NUMBER = 'AEP';
    public const TYPE_CONSODILDATED_INVOICE = 'AIZ';
    public const TYPE_MESSAGE_BATCH_NUMBER = 'ALL';
    public const TYPE_RECEIVING_ADVICE_NUMBER = 'ALO';
    public const TYPE_EXTERNAL_OBJECT_REFERENCE = 'ATS';
    public const TYPE_COMMERCIAL_ACCOUNT_SUMMARY = 'APQ';
    public const TYPE_CREDIT_NOTE = 'CD';
    public const TYPE_CUSTOMER_REFERENCE_NUMBER = 'CR';
    public const TYPE_CONTRACT = 'CT';
    public const TYPE_DEBIT_NOTE = 'DL';
    public const TYPE_DELIVERY_NOTE = 'DQ';
    public const TYPE_IMPORT_LICENCE_NUMBER = 'IP';
    public const TYPE_INVOICE = 'IV';
    public const TYPE_GOVERNMENT_CONTRACT_NUMBER = 'GC';
    public const TYPE_ORDER_NUMBER = 'ON';
    public const TYPE_PREVIOUS_INVOICE_NUMBER = 'OI';
    public const TYPE_PRICE_LIST_NUMBER = 'PL';
    public const TYPE_PURCHASE_ORDER_RESPONSE_NUMBER = 'POR';
    public const TYPE_PAYMENT_REFERENCE = 'PQ';
    public const TYPE_EXPORT_REFERENCE_NUMBER = 'RF';
    public const TYPE_SPECIFICATION_NUMBER = 'SZ';
    public const TYPE_ORDER_NUMBER_SUPPLIER = 'VN';

    public const DTM_TYPE_PREVIOUS_INVOICE_DATE = '384';

    public ?string $dtmType = null;
    public ?\DateTime $dtmValue = null;

    public function __construct(
        public string $typeCode = self::TYPE_ORDER_NUMBER,
        public ?string $documentNumber = null,
    ) {
    }

    public function setDocumentNumber(string $documentNumber): ReferenceDocument
    {
        $this->documentNumber = $documentNumber;

        return $this;
    }

    public function setTypeCode(string $typeCode): ReferenceDocument
    {
        $this->typeCode = $typeCode;

        return $this;
    }

    public function setDTM($dtmType, \DateTime $dtmValue): ReferenceDocument
    {
        $this->dtmType = $dtmType;
        $this->dtmValue = $dtmValue;

        return $this;
    }

    public function generateXml(): \SimpleXMLElement
    {
        $xml = new \SimpleXMLElement('<G_SG1></G_SG1>');

        $ref = $xml->addChild('S_RFF')
            ->addChild('C_C506');
        $ref->addChild('D_1153', $this->typeCode);

        if ($this->documentNumber) {
            $ref->addChild('D_1154', $this->documentNumber);
        }

        if ($this->dtmType) {
            $dtm = $xml->addChild('S_DTM')
                ->addChild('C_C507');

            $dtm->addChild('D_2005', $this->dtmType);
            $dtm->addChild('D_2380', $this->dtmValue->format('Y-m-d'));
        }

        return $xml;
    }
}
