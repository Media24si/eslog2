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

    public string $invoiceNumber;

    public float $totalWithoutTax;
    public float $totalWithTax;

    public string $locationAddress;

    public \DateTime $dateIssued;
    public ?\DateTime $dateOfService = null;
    public \DateTime $dateDue;

    public ?float $globalDiscountAmount = null;
    public ?float $globalDiscountPercentage = null;

    public ?string $introText = null;
    public ?string $outroText = null;

    public int $invoiceType = self::TYPE_INVOICE;
    public string $currency = 'EUR';
    public int $invoiceFunction = self::FUNCTION_ORIGINAL;
    public int $paymentType = self::PAYMENT_REQUIRED;
    public string $paymentPurpose = 'GDSV';
    public ?string $additionalRemittanceInformation = null;

    public int $locationCode = self::LOCATION_ISSUED;

    public int $dateIssuedCode = 137;
    public int $dateOfServiceCode = 35;
    public int $dateDueCode = 13;

    /**
     * @var array<ReferenceDocument>
     */
    public array $referenceDocuments = [];

    public array $items = [];

    public float $totalWithoutDiscount = 0;

    public array $taxSummaries = [];

    public $devNotes;

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

    public function setInvoiceNumber(string $invoiceNumber): Invoice
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    public function setTotalWithoutTax(float $totalWithoutTax): Invoice
    {
        $this->totalWithoutTax = $totalWithoutTax;

        return $this;
    }

    public function setTotalWithTax(float $totalWithTax): Invoice
    {
        $this->totalWithTax = $totalWithTax;

        return $this;
    }

    public function setLocationAddress(string $locationAddress): Invoice
    {
        $this->locationAddress = $locationAddress;

        return $this;
    }

    public function setDateIssued(\DateTime $dateIssued): Invoice
    {
        $this->dateIssued = $dateIssued;

        return $this;
    }

    public function setDateOfService(\DateTime $dateOfService): Invoice
    {
        $this->dateOfService = $dateOfService;

        return $this;
    }

    public function setDateDue(\DateTime $dateDue): Invoice
    {
        $this->dateDue = $dateDue;

        return $this;
    }

    public function setGlobalDiscountAmount(?float $globalDiscountAmount): Invoice
    {
        $this->globalDiscountAmount = $globalDiscountAmount;

        return $this;
    }

    public function setGlobalDiscountPercentage(?float $globalDiscountPercentage): Invoice
    {
        $this->globalDiscountPercentage = $globalDiscountPercentage;

        return $this;
    }

    public function setIntroText(?string $introText): Invoice
    {
        $this->introText = $introText;

        return $this;
    }

    public function setOutroText(?string $outroText): Invoice
    {
        $this->outroText = $outroText;

        return $this;
    }

    public function setInvoiceType(int $invoiceType): Invoice
    {
        $this->invoiceType = $invoiceType;

        return $this;
    }

    public function setCurrency(string $currency): Invoice
    {
        $this->currency = $currency;

        return $this;
    }

    public function setInvoiceFunction(int $invoiceFunction): Invoice
    {
        $this->invoiceFunction = $invoiceFunction;

        return $this;
    }

    public function setPaymentType(int $paymentType): Invoice
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    public function setPaymentPurpose(string $paymentPurpose): Invoice
    {
        $this->paymentPurpose = $paymentPurpose;

        return $this;
    }

    public function setAdditionalRemittanceInformation(?string $additionalRemittanceInformation): Invoice
    {
        $this->additionalRemittanceInformation = $additionalRemittanceInformation;

        return $this;
    }

    public function setLocationCode(int $locationCode): Invoice
    {
        $this->locationCode = $locationCode;

        return $this;
    }

    public function setDateIssuedCode(int $dateIssuedCode): Invoice
    {
        $this->dateIssuedCode = $dateIssuedCode;

        return $this;
    }

    public function setDateOfServiceCode(int $dateOfServiceCode): Invoice
    {
        $this->dateOfServiceCode = $dateOfServiceCode;

        return $this;
    }

    public function setDateDueCode(int $dateDueCode): Invoice
    {
        $this->dateDueCode = $dateDueCode;

        return $this;
    }

    public function setTotalWithoutDiscount(float $totalWithoutDiscount): Invoice
    {
        $this->totalWithoutDiscount = $totalWithoutDiscount;

        return $this;
    }

    public function addReferenceDocument(ReferenceDocument $referenceDocument): Invoice
    {
        $this->referenceDocuments[] = $referenceDocument;

        return $this;
    }

    public function getReferenceDocument(string $typeCode): ?ReferenceDocument
    {
        foreach ($this->referenceDocuments as $doc) {
            if ($doc->typeCode === $typeCode) {
                return $doc;
            }
        }

        return null;
    }

    public function addItem(InvoiceItem $item): Invoice
    {
        $this->items[] = $item;

        return $this;
    }

    public function addTaxSummary(TaxSummary $taxSummary): Invoice
    {
        $this->taxSummaries[] = $taxSummary;

        return $this;
    }

    public function generateXml(): \SimpleXMLElement
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Invoice xmlns="urn:eslog:2.00" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"></Invoice>');

        $mInvoice = $xml->addChild('M_INVOIC');
        $mInvoice->addAttribute('Id', 'data');

        // Document header
        $docHeader = $mInvoice->addChild('S_UNH');
        $docHeader->addChild('D_0062', $this->invoiceNumber);
        $docHeader_C = $docHeader->addChild('C_S009');
        $docHeader_C->addChild('D_0065', 'INVOIC');
        $docHeader_C->addChild('D_0052', 'D');
        $docHeader_C->addChild('D_0054', '01B');
        $docHeader_C->addChild('D_0051', 'UN');

        // Header
        $header = $mInvoice->addChild('S_BGM');
        $header->addChild('C_C002')
            ->addChild('D_1001', $this->invoiceType);
        $header->addChild('C_C106')
            ->addChild('D_1004', $this->invoiceNumber);

        // Date Issued
        $dateIssues = $mInvoice->addChild('S_DTM')
            ->addChild('C_C507');
        $dateIssues->addChild('D_2005', $this->dateIssuedCode);
        $dateIssues->addChild('D_2380', $this->dateIssued->format('Y-m-d'));

        // Date Of service
        if ($this->dateOfService) {
            $dateIssues = $mInvoice->addChild('S_DTM')
                ->addChild('C_C507');
            $dateIssues->addChild('D_2005', $this->dateOfServiceCode);
            $dateIssues->addChild('D_2380', $this->dateOfService->format('Y-m-d'));
        }

        // Payment type
        $paymentType = $mInvoice->addChild('S_FTX');
        $paymentType->addChild('D_4451', 'PAI');
        $paymentType->addChild('C_C108')
            ->addChild('D_4440', $this->paymentType);

        // Doc
        $ftxDoc = $mInvoice->addChild('S_FTX');
        $ftxDoc->addChild('D_4451', 'DOC');
        $ftxDoc->addChild('C_C107')
            ->addChild('D_4441', 'P1');
        $ftxDoc->addChild('C_C108')
            ->addChild('D_4440', 'urn:cen.eu:en16931:2017');

        // Payment purpose
        $paymentPurpose = $mInvoice->addChild('S_FTX');
        $paymentPurpose->addChild('D_4451', 'ALQ');
        $paymentPurpose->addChild('C_C108')
            ->addChild('D_4440', $this->paymentPurpose);

        // Reference documents
        foreach ($this->referenceDocuments as $doc) {
            $refDoc = $mInvoice->addChild('G_SG1');
            XMLHelpers::append($refDoc, $doc->generateXml());
        }

        $recipient = $mInvoice->addChild('G_SG2');
        XMLHelpers::append($recipient, $this->recipient->generateXml('BY'));

        $issuer = $mInvoice->addChild('G_SG2');
        XMLHelpers::append($issuer, $this->issuer->generateXml('SE'));

        // Currency
        $currency = $mInvoice->addChild('G_SG7')
            ->addChild('S_CUX')
            ->addChild('C_C504');
        $currency->addChild('D_6347', '2');
        $currency->addChild('D_6345', $this->currency);

        // Payment terms
        $paymentTerms = $mInvoice->addChild('G_SG8');
        $paymentTerms->addChild('S_PAT')
            ->addChild('D_4279', 1);
        $paymentTermsDueDate = $paymentTerms->addChild('S_DTM')
            ->addChild('C_C507');
        $paymentTermsDueDate->addChild('D_2005', $this->dateDueCode);
        $paymentTermsDueDate->addChild('D_2380', $this->dateDue->format('Y-m-d'));
        $paymentTerms->addChild('S_PAI')
            ->addChild('C_C534')
            ->addChild('D_4461', 1);

        // Global discount
        if ($this->globalDiscountAmount) {
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
            $discountPercentage->addChild('D_5482', $this->globalDiscountPercentage);

            $discountAmount = $discount->addChild('G_SG20')
                ->addChild('S_MOA')
                ->addChild('C_C516');
            $discountAmount->addChild('D_5025', 1);
            $discountAmount->addChild('D_5004', $this->globalDiscountAmount);
        }

        foreach ($this->items as $line => $item) {
            $line = $mInvoice->addChild('G_SG26');
            XMLHelpers::append($line, $item->generateXml());
        }

        // Payment data
        $paymentData = $mInvoice->addChild('G_SG50')
            ->addChild('S_MOA')
            ->addChild('C_C516');
        $paymentData->addChild('D_5025', 9);
        $paymentData->addChild('D_5004', $this->totalWithTax);

        // Total without discount
        $this->xmlSumsData($mInvoice, $this->totalWithoutDiscount, '79');
        // Discounts amount
        $this->xmlSumsData($mInvoice, $this->totalWithoutDiscount - $this->totalWithoutTax, '260');
        // Tax base sums
        $this->xmlSumsData($mInvoice, $this->totalWithoutTax, '389');
        // Taxes amount
        $this->xmlSumsData($mInvoice, $this->totalWithTax - $this->totalWithoutTax, '176');
        // Total amount - with taxes
        $this->xmlSumsData($mInvoice, $this->totalWithTax, '388');

        foreach ($this->taxSummaries as $taxSummary) {
            $tax = $mInvoice->addChild('G_SG52');
            XMLHelpers::append($tax, $taxSummary->generateXml());
        }

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

    public function setDevNotes($notes): Invoice
    {
        $this->devNotes = $notes;

        return $this;
    }
}
