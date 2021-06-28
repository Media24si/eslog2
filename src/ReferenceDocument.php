<?php

namespace Media24si\eSlog2;

class ReferenceDocument
{
    public const TYPE_PROFORMA_INVOICE = 'AAB';
    public const TYPE_DELIVERY_ORDER_NUMBER = 'AAJ';
    public const TYPE_DELIVERY_FORM = 'AAK';
    public const TYPE_BENEFICIARYS_REFERENCE = 'AFO';
    public const TYPE_CONSODILDATED_INVOICE = 'AIZ';
    public const TYPE_MESSAGE_BATCH_NUMBER = 'ALL';
    public const TYPE_RECEIVING_ADVICE_NUMBER = 'ALO';
    public const TYPE_COMMERCIAL_ACCOUNT_SUMMARY = 'APQ';
    public const TYPE_CREDIT_NOTE = 'CD';
    public const TYPE_CUSTOMER_REFERENCE_NUMBER = 'CR';
    public const TYPE_CONTRACT = 'CT';
    public const TYPE_DEBIT_NOTE = 'DL';
    public const TYPE_DELIVERY_NOTE = 'DQ';
    public const TYPE_IMPORT_LICENCE_NUMBER = 'IP';
    public const TYPE_INVOICE = 'IV';
    public const TYPE_ORDER_NUMBER = 'ON';
    public const TYPE_PRICE_LIST_NUMBER = 'PL';
    public const TYPE_PURCHASE_ORDER_RESPONSE_NUMBER = 'POR';
    public const TYPE_EXPORT_REFERENCE_NUMBER = 'RF';
    public const TYPE_SPECIFICATION_NUMBER = 'SZ';
    public const TYPE_ORDER_NUMBER_SUPPLIER = 'VN';

    public string $document_number;
    public string $type_code = self::TYPE_ORDER_NUMBER;

    public function setDocumentNumber(string $document_number): ReferenceDocument
    {
        $this->document_number = $document_number;

        return $this;
    }

    public function setTypeCode(string $type_code): ReferenceDocument
    {
        $this->type_code = $type_code;

        return $this;
    }
}
