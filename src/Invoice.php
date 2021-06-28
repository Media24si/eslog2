<?php

namespace Media24si\eSlog2;

class Invoice
{
    public const TYPE_MEASURED_SERVICES = 82;
    public const TYPE_FINANCIAL_CREDIT_NOTE = 83;
    public const TYPE_FINANCIAL_DEBIT_NOTE = 84;
    public const TYPE_INVOICING_DATA_SHEET = 130;
    public const TYPE_PROFORMA_INVOICE = 325;
    public const TYPE_INVOICE = 380;
    public const TYPE_CREDIT_NOTE = 381;
    public const TYPE_COMMISION_NOTE = 382;
    public const TYPE_DEBIT_NOTE = 383;
    public const TYPE_CORRECTED_INVOICE = 384;
    public const TYPE_CONSOLIDATED_INVOICE = 385;
    public const TYPE_PREPAYMENT_INVOICE = 386;
    public const TYPE_SELF_BILLED_INVOICE = 389;
    public const TYPE_DELCREDRE_INVOICE = 390;
    public const TYPE_FACTORED_INVOICE = 393;

    public const FUNCTION_CANCELLATION = 1;
    public const FUNCTION_REPLACE = 5;
    public const FUNCTION_DUPLICATE = 7;
    public const FUNCTION_ORIGINAL = 9;
    public const FUNCTION_COPY = 31;
    public const FUNCTION_ADDITIONAL_TRANSMISSION = 43;

    public const PAYMENT_REQUIRED = 0;
    public const PAYMENT_DIRECT_DEBIT = 1;
    public const PAYMENT_ALREADY_PAID = 2;
    public const PAYMENT_OTHER_NO_PAYMENT = 3;

    public const LOCATION_PAYMENT = 57;
    public const LOCATION_ISSUED = 91;
    public const LOCATION_SALE = 162;

    public Business $issuer;
    public Business $recipient;

    public string $invoice_number;

    public float $total_without_tax;
    public float $total_with_tax;

    public string $location_address;

    public \DateTime $date_issued;
    public \DateTime $date_of_service;
    public \DateTime $date_due;

    public string $payment_reference;

    public ?float $global_discount_amount = null;
    public ?float $global_discount_percentage = null;

    public ?string $intro_text = null;
    public ?string $outro_text = null;

    public int $invoice_type = self::TYPE_INVOICE;
    public string $currency = 'EUR';
    public int $invoice_function = self::FUNCTION_ORIGINAL;
    public int $payment_type = self::PAYMENT_REQUIRED;
    public string $payment_purpose = 'GDSV';
    public ?string $additional_remittance_information = null;

    public int $location_code = self::LOCATION_ISSUED;

    public int $date_issued_code = 137;
    public int $date_of_service_code = 35;
    public int $date_due_code = 13;

    public array $reference_documents = [];

    public array $document_items = [];

    public float $total_without_discount = 0;

    public array $tax_summaries = [];

    public function setIssuer(Business $issuer): Invoice
    {
        $this->issuer = $issuer;

        return $this;
    }

    public function setRecipient(Business $recipient): Invoice
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function setInvoiceNumber(string $invoice_number): Invoice
    {
        $this->invoice_number = $invoice_number;

        return $this;
    }

    public function setTotalWithoutTax(float $total_without_tax): Invoice
    {
        $this->total_without_tax = $total_without_tax;

        return $this;
    }

    public function setTotalWithTax(float $total_with_tax): Invoice
    {
        $this->total_with_tax = $total_with_tax;

        return $this;
    }

    public function setLocationAddress(string $location_address): Invoice
    {
        $this->location_address = $location_address;

        return $this;
    }

    public function setDateIssued(\DateTime $date_issued): Invoice
    {
        $this->date_issued = $date_issued;

        return $this;
    }

    public function setDateOfService(\DateTime $date_of_service): Invoice
    {
        $this->date_of_service = $date_of_service;

        return $this;
    }

    public function setDateDue(\DateTime $date_due): Invoice
    {
        $this->date_due = $date_due;

        return $this;
    }

    public function setPaymentReference(string $payment_reference): Invoice
    {
        $this->payment_reference = $payment_reference;

        return $this;
    }

    public function setGlobalDiscountAmount(?float $global_discount_amount): Invoice
    {
        $this->global_discount_amount = $global_discount_amount;

        return $this;
    }

    public function setGlobalDiscountPercentage(?float $global_discount_percentage): Invoice
    {
        $this->global_discount_percentage = $global_discount_percentage;

        return $this;
    }

    public function setIntroText(?string $intro_text): Invoice
    {
        $this->intro_text = $intro_text;

        return $this;
    }

    public function setOutroText(?string $outro_text): Invoice
    {
        $this->outro_text = $outro_text;

        return $this;
    }

    public function setInvoiceType(int $invoice_type): Invoice
    {
        $this->invoice_type = $invoice_type;

        return $this;
    }

    public function setCurrency(string $currency): Invoice
    {
        $this->currency = $currency;

        return $this;
    }

    public function setInvoiceFunction(int $invoice_function): Invoice
    {
        $this->invoice_function = $invoice_function;

        return $this;
    }

    public function setPaymentType(int $payment_type): Invoice
    {
        $this->payment_type = $payment_type;

        return $this;
    }

    public function setPaymentPurpose(string $payment_purpose): Invoice
    {
        $this->payment_purpose = $payment_purpose;

        return $this;
    }

    public function setAdditionalRemittanceInformation(?string $additional_remittance_information): Invoice
    {
        $this->additional_remittance_information = $additional_remittance_information;

        return $this;
    }

    public function setLocationCode(int $location_code): Invoice
    {
        $this->location_code = $location_code;

        return $this;
    }

    public function setDateIssuedCode(int $date_issued_code): Invoice
    {
        $this->date_issued_code = $date_issued_code;

        return $this;
    }

    public function setDateOfServiceCode(int $date_of_service_code): Invoice
    {
        $this->date_of_service_code = $date_of_service_code;

        return $this;
    }

    public function setDateDueCode(int $date_due_code): Invoice
    {
        $this->date_due_code = $date_due_code;

        return $this;
    }

    public function setTotalWithoutDiscount(float $total_without_discount): Invoice
    {
        $this->total_without_discount = $total_without_discount;

        return $this;
    }

    public function addReferenceDocument(ReferenceDocument $referenceDocument): Invoice
    {
        $this->reference_documents[] = $referenceDocument;

        return $this;
    }

    public function addItem(InvoiceItem $item): Invoice
    {
        $this->document_items[] = $item;

        return $this;
    }

    public function addTaxSummary(TaxSummary $taxSummary): Invoice
    {
        $this->tax_summaries[] = $taxSummary;

        return $this;
    }

    public function generateXml(): \SimpleXMLElement
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Invoice xmlns="urn:eslog:2.00" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"></Invoice>');

        $mInvoice = $xml->addChild('M_INVOIC');
        $mInvoice->addAttribute('Id', 'data');

        // Document header
        $docHeader = $mInvoice->addChild('S_UNH');
        $docHeader->addChild('D_0062', $this->invoice_number);
        $docHeader_C = $docHeader->addChild('C_S009');
        $docHeader_C->addChild('D_0065', 'INVOIC');
        $docHeader_C->addChild('D_0052', 'D');
        $docHeader_C->addChild('D_0054', '01B');
        $docHeader_C->addChild('D_0051', 'UN');

        // Header
        $header = $mInvoice->addChild('S_BGM');
        $header->addChild('C_C002')
            ->addChild('D_1001', $this->invoice_type);
        $header->addChild('C_C106')
            ->addChild('D_1004', $this->invoice_number);

        // Date Issued
        $dateIssues = $mInvoice->addChild('S_DTM')
            ->addChild('C_C507');
        $dateIssues->addChild('D_2005', $this->date_issued_code);
        $dateIssues->addChild('D_2380', $this->date_issued->format('Y-m-d'));

        // Date Of service
        $dateIssues = $mInvoice->addChild('S_DTM')
            ->addChild('C_C507');
        $dateIssues->addChild('D_2005', $this->date_of_service_code);
        $dateIssues->addChild('D_2380', $this->date_of_service->format('Y-m-d'));

        // Payment type
        $paymentType = $mInvoice->addChild('S_FTX');
        $paymentType->addChild('D_4451', 'PAI');
        $paymentType->addChild('C_C108')
            ->addChild('D_4440', $this->payment_type);

        // Payment purpose
        $paymentType = $mInvoice->addChild('S_FTX');
        $paymentType->addChild('D_4451', 'ALQ');
        $paymentType->addChild('C_C108')
            ->addChild('D_4440', $this->payment_purpose);

        // Payment reference
        if ($this->payment_reference !== null) {
            $reference = $mInvoice->addChild('G_SG1')
                ->addChild('S_RFF')
                ->addChild('C_C506');
            $reference->addChild('D_1153', 'PQ');
            $reference->addChild('D_1154', $this->payment_reference);
        }

        // Reference documents
        foreach ($this->reference_documents as $doc) {
            $ref = $mInvoice->addChild('G_SG1')
                ->addChild('S_RFF')
                ->addChild('C_C506');
            $ref->addChild('D_1153', $doc->type_code);
            $ref->addChild('D_1154', $doc->document_number);
        }

        $issuer = $mInvoice->addChild('G_SG2');
        XMLHelpers::append($issuer, $this->issuer->generateXml('SE'));

        $recipient = $mInvoice->addChild('G_SG2');
        XMLHelpers::append($recipient, $this->recipient->generateXml('BY'));

        // Currency
        $currency = $mInvoice->addChild('G_SG7')
            ->addChild('S_CUX')
            ->addChild('C_C504');
        $currency->addChild('D_6347', '2');
        $currency->addChild('D_6345', $this->currency);

//        'payment_terms': construct_payment_terms_data(invoice.date_due_code, invoice.date_due),
        $paymentTerms = $mInvoice->addChild('G_SG8');
        $paymentTerms->addChild('S_PAT')
            ->addChild('D_4279', 1);
        $paymentTermsDueDate = $paymentTerms->addChild('S_DTM')
            ->addChild('C_C507');
        $paymentTermsDueDate->addChild('D_2005', $this->date_due_code);
        $paymentTermsDueDate->addChild('D_2380', $this->date_due->format('Y-m-d'));

        // Global discount
        if ($this->global_discount_amount) {
            $discount = $mInvoice->addChild('G_SG16');

            $discount->addChild('S_ALC');
            $discount['S_ALC']->addChild('D_5463', 'A');
            $discount['S_ALC']->addChild('C_C552');
            $discount['S_ALC']['C_C552']->addChild('D_1230', 'SKUPNI POPUST');
            $discount['S_ALC']['C_C552']->addChild('D_5189', '42');

            $discountPercentage = $discount->addChild('G_SG19')
                ->addChild('S_PCD')
                ->addChild('C_C501');
            $discountPercentage->addChild('D_5245', 1);
            $discountPercentage->addChild('D_5482', $this->global_discount_percentage);

            $discountAmount = $discount->addChild('G_SG20')
                ->addChild('S_MOA')
                ->addChild('C_C516');
            $discountAmount->addChild('D_5025', 1);
            $discountAmount->addChild('D_5004', $this->global_discount_amount);
        }

        // if invoice.intro_text:
        //  data['invoice']['intro_text'] = construct_custom_text_data('GEN', invoice.intro_text)

        foreach ($this->document_items as $line => $item) {
            $line = $mInvoice->addChild('G_SG26');
            XMLHelpers::append($line, $item->generateXml($line + 1));
        }

        // Payment data
        $paymentData = $mInvoice->addChild('G_SG50')
            ->addChild('S_MOA')
            ->addChild('C_C516');
        $paymentData->addChild('D_5025', 9);
        $paymentData->addChild('D_5004', $this->total_with_tax);

        // Total without discount
        $this->xmlSumsData($mInvoice, $this->total_without_discount, '79');
        // Discounts amount
        $this->xmlSumsData($mInvoice, $this->total_without_discount - $this->total_without_tax, '260');
        // Tax base sums
        $this->xmlSumsData($mInvoice, $this->total_without_tax, '389');
        // Taxes amount
        $this->xmlSumsData($mInvoice, $this->total_with_tax - $this->total_without_tax, '176');
        // Total amount - with taxes
        $this->xmlSumsData($mInvoice, $this->total_with_tax, '388');

        foreach ($this->tax_summaries as $taxSummary) {
            $tax = $mInvoice->addChild('G_SG52');
            XMLHelpers::append($tax, $taxSummary->generateXml());
        }

        // if invoice.outro_text:
        //  data['invoice']['outro_text'] = construct_custom_text_data('GEN', invoice.outro_text)

        return $xml;
    }

    private function xmlSumsData(\SimpleXMLElement &$xml, $amount, $type)
    {
        $sum = $xml->addChild('G_SG50')
            ->addChild('S_MOA')
            ->addChild('C_C516');

        $sum->addChild('D_5025', $type);
        $sum->addChild('D_5004', round($amount, 2));
    }
}
