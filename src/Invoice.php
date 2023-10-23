<?php

namespace Media24si\eSlog2;

use Illuminate\Support\Arr;
use Media24si\eSlog2\Segments\DateTimePeriod;
use Media24si\eSlog2\Segments\MonetaryAmount;

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

    /**
     * Invoice number (BT-1)
     *
     * @var string
     */
    public string $invoiceNumber;

    /**
     * Invoice issue date (BT-2)
     *
     * @var \DateTime
     */
    public \DateTime $dateIssued;

    /**
     * Invoice type code (BT-3)
     * 
     * A code specifying the functional type of the Invoice.
     *
     * @var integer
     */
    public int $invoiceType = self::TYPE_INVOICE;

    /**
     * Invoice currency code (BT-5)
     * 
     * The currency in which all Invoice amounts are given, except for the Total VAT amount in accounting currency.
     *
     * @var string
     */
    public string $currency = 'EUR';

    /**
     * Payment due date (BT-9)
     * 
     * The date on which payment is due.
     *
     * @var \DateTime
     */
    public \DateTime $dateDue;

    /**
     * Actual delivery date (BT-72)
     * 
     * The date on which the delivery is made.
     *
     * @var \DateTime|null
     */
    public ?\DateTime $dateOfService = null;

    /**
     * Seller (BG-5)
     * 
     * A group of business terms providing information about the Seller.
     *
     * @var Business
     */
    public Business $seller;

    /**
     * Buyer (BG-7)
     * 
     * A group of business terms providing information about the Buyer.
     *
     * @var Business
     */
    public Business $buyer;

    /**
     * Document level allowances (BG-20)
     * 
     * A group of business terms providing information about allowances applicable to the Invoice as a whole.
     *
     * @var array<InvoiceDiscount>
     */
    public array $documentAllowances = [];

    /**
     * Payment means type code (BT-81)
     * 
     * The means, expressed as code, for how a payment is expected to be or has been settled.
     * Use code list UNTDID 4461 — Payment means
     *
     * @var integer
     */
    public int $paymentMeans = 1;

    /**
     * Sum of invoice line net amount (BT-106)
     * 
     * Sum of all Invoice line net amounts in the Invoice.
     *
     * @var float
     */
    public float $totalWithoutDiscount = 0;

    /**
     * Sum of allowances on document level (BT-107)
     * 
     * The total amount of all allowances on document level.
     *
     * @var float
     */
    public float $sumOfAllowances = 0;

    /**
     * Sum of charges on document level (BT-108)
     * 
     * The total amount of all charges on document level.
     *
     * @var float
     */
    public float $sumOfCharges = 0;

    /**
     * Invoice total amount without VAT (BT-109)
     * 
     * The total amount of the Invoice without VAT.
     *
     * @var float
     */
    public float $totalWithoutTax;

    /**
     * Invoice total amount with VAT (BT-112)
     * 
     * The total amount of the Invoice including VAT.
     *
     * @var float
     */
    public float $totalWithTax;

    /**
     * Paid amount (BT-113)
     * 
     * The amount paid in advance.
     *
     * @var float
     */
    public float $paidAmount = 0;

    /**
     * Rounding amount (BT-114)
     * 
     * The amount to be added to the invoice total to round the amount to be paid.
     *
     * @var float
     */
    public float $roundingAmount = 0;

    /**
     * Amount due for payment (BT-115)
     * 
     * The outstanding amount that is requested to be paid.
     *
     * @var float
     */
    public float $amountDueForPayment = 0;

    /**
     * VAT breakdown (BG-23)
     * 
     * A group of business terms providing information about VAT breakdown by different categories, rates and exemption reasons
     *
     * @var array<TaxSummary>
     */
    public array $taxSummaries = [];

    /**
     * Invoice line (BG-25)
     * 
     * A group of business terms providing information on individual Invoice lines.
     *
     * @var array<InvoiceItem>
     */
    public array $items = [];





    
    

    public string $locationAddress;


    

    public ?string $introText = null;
    public ?string $outroText = null;

    public int $invoiceFunction = self::FUNCTION_ORIGINAL;
    public ?string $additionalRemittanceInformation = null;

    public int $locationCode = self::LOCATION_ISSUED;

    /**
     * @var array<FreeText>
     */
    public array $freeText = [];

    /**
     * @var array<ReferenceDocument>
     */
    public array $referenceDocuments = [];




    public $devNotes;

    /**
     * Invoice number (BT-1)
     *
     * @param string $invoiceNumber
     * @return Invoice
     */
    public function setInvoiceNumber(string $invoiceNumber): Invoice
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    /**
     * Invoice issue date (BT-2)
     * 
     * The date on which the Invoice was issued.
     *
     * @param \DateTime $dateIssued
     * @return Invoice
     */
    public function setDateIssued(\DateTime $dateIssued): Invoice
    {
        $this->dateIssued = $dateIssued;

        return $this;
    }


    /**
     * Invoice type code (BT-3)
     * 
     * A code specifying the functional type of the Invoice.
     *
     * @param integer $invoiceType
     * @return Invoice
     */
    public function setInvoiceType(int $invoiceType): Invoice
    {
        $this->invoiceType = $invoiceType;

        return $this;
    }

    /**
     * Invoice currency code (BT-5)
     * 
     * The currency in which all Invoice amounts are given, except for the Total VAT amount in accounting currency.
     *
     * @param string $currency
     * @return Invoice
     */
    public function setCurrency(string $currency): Invoice
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Payment due date (BT-9)
     * 
     * The date on which payment is due.
     *
     * @param \DateTime $dateDue
     * @return Invoice
     */
    public function setDateDue(\DateTime $dateDue): Invoice
    {
        $this->dateDue = $dateDue;

        return $this;
    }

    /**
     * Actual delivery date (BT-72)
     * 
     * The date on which the delivery is made.
     *
     * @param \DateTime|null $dateOfService
     * @return Invoice
     */
    public function setDateOfService(\DateTime $dateOfService): Invoice
    {
        $this->dateOfService = $dateOfService;

        return $this;
    }

    /**
     * Seller (BG-5)
     * 
     * @param Business $seller
     * @return Invoice
     */
    public function setSeller(Business $seller): Invoice
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * Buyer (BG-7)
     * 
     * @param Business $buyer
     * @return Invoice
     */
    public function setBuyer(Business $buyer): Invoice
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * Document level allowances (BG-20)
     * 
     * A group of business terms providing information about allowances applicable to the Invoice as a whole.
     *
     * @param InvoiceDiscount $discount
     * @return Invoice
     */
    public function addDocumentAllowance(InvoiceDiscount $discount): Invoice
    {
        $this->documentAllowances[] = $discount;

        return $this;
    }

    /**
     * Sum of Invoice line net amount (BT-106)
     * 
     * Sum of all Invoice line net amounts in the Invoice.
     *
     * @param float $totalWithoutDiscount
     * @return Invoice
     */
    public function setTotalWithoutDiscount(float $totalWithoutDiscount): Invoice
    {
        $this->totalWithoutDiscount = $totalWithoutDiscount;

        return $this;
    }

    /**
     * Sum of allowances on document level (BT-107)
     * 
     * The total amount of all allowances on document level.
     *
     * @param float $sumOfAllowances
     * @return Invoice
     */
    public function setSumOfAllowances(float $sumOfAllowances): Invoice
    {
        $this->sumOfAllowances = $sumOfAllowances;

        return $this;
    }

    /**
     * Sum of charges on document level (BT-108)
     * 
     * The total amount of all charges on document level.
     *
     * @param float $sumOfCharges
     * @return Invoice
     */
    public function setSumOfCharges(float $sumOfCharges): Invoice
    {
        $this->sumOfCharges = $sumOfCharges;

        return $this;
    }

    /**
     * Invoice total amount without VAT (BT-109)
     * 
     * The total amount of the Invoice without VAT.
     *
     * @param float $totalWithoutTax
     * @return Invoice
     */
    public function setTotalWithoutTax(float $totalWithoutTax): Invoice
    {
        $this->totalWithoutTax = $totalWithoutTax;

        return $this;
    }

    /**
     * Invoice total amount with VAT (BT-112)
     * 
     * The total amount of the Invoice including VAT.
     *
     * @param float $totalWithTax
     * @return Invoice
     */
    public function setTotalWithTax(float $totalWithTax): Invoice
    {
        $this->totalWithTax = $totalWithTax;

        return $this;
    }

    /**
     * Paid amount (BT-113)
     * 
     * The amount paid in advance.
     *
     * @param float $paidAmount
     * @return Invoice
     */
    public function setPaidAmount(float $paidAmount): Invoice
    {
        $this->paidAmount = $paidAmount;

        return $this;
    }

    /**
     * Rounding amount (BT-114)
     * 
     * The amount to be added to the invoice total to round the amount to be paid.
     *
     * @param float $roundingAmount
     * @return Invoice
     */
    public function setRoundingAmount(float $roundingAmount): Invoice
    {
        $this->roundingAmount = $roundingAmount;

        return $this;
    }

    /**
     * Amount due for payment (BT-115)
     * 
     * The outstanding amount that is requested to be paid.
     *
     * @param float $amountDueForPayment
     * @return Invoice
     */
    public function setAmountDueForPayment(float $amountDueForPayment): Invoice
    {
        $this->amountDueForPayment = $amountDueForPayment;

        return $this;
    }

    /**
     * VAT breakdown (BG-23)
     * 
     * A group of business terms providing information about VAT breakdown by different categories, rates and exemption reasons
     *
     * @param TaxSummary $taxSummary
     * @return Invoice
     */
    public function addTaxSummary(TaxSummary $taxSummary): Invoice
    {
        $this->taxSummaries[] = $taxSummary;

        return $this;
    }

    /**
     * Invoice line (BG-25)
     * 
     * A group of business terms providing information on individual Invoice lines.
     *
     * @param InvoiceItem $item
     * @return Invoice
     */
    public function addItem(InvoiceItem $item): Invoice
    {
        $this->items[] = $item;

        return $this;
    }

    public function addFreeText(FreeText $freeText): Invoice
    {
        $this->freeText[] = $freeText;

        return $this;
    }

    public function getFreeText(string $textCode): ?FreeText
    {
        foreach ($this->freeText as $text) {
            if ($text->textCode === $textCode) {
                return $text;
            }
        }

        return null;
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

    /**
     * Currently unused or otherwise helper functions
     */
    public function setLocationAddress(string $locationAddress): Invoice
    {
        $this->locationAddress = $locationAddress;

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

    public function setLocationCode(int $locationCode): Invoice
    {
        $this->locationCode = $locationCode;

        return $this;
    }

    public function setInvoiceFunction(int $invoiceFunction): Invoice
    {
        $this->invoiceFunction = $invoiceFunction;

        return $this;
    }

    public function setAdditionalRemittanceInformation(?string $additionalRemittanceInformation): Invoice
    {
        $this->additionalRemittanceInformation = $additionalRemittanceInformation;

        return $this;
    }

    public function getAllLineItemDiscounts(): float
    {
        return array_reduce($this->items, function ($carry, $item) {
            return $carry + $item->getFullDiscountAmount();
        }, 0);
    }
    /**
     * End of unused or helper functions
     */

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
        XMLHelpers::append(
            $mInvoice,
            (new DateTimePeriod())
                ->setCode(137)
                ->setDate($this->dateIssued)
                ->generateXml()
        );

        // Date Of service
        if ($this->dateOfService) {
            XMLHelpers::append(
                $mInvoice,
                (new DateTimePeriod())
                    ->setCode(35)
                    ->setDate($this->dateOfService)
                    ->generateXml()
            );
        }

        // Free text
        foreach ($this->freeText as $text) {
            $ftx = $mInvoice->addChild('S_FTX');
            XMLHelpers::append($ftx, $text->generateXml());
        }

        // Reference documents
        foreach ($this->referenceDocuments as $doc) {
            $refDoc = $mInvoice->addChild('G_SG1');
            XMLHelpers::append($refDoc, $doc->generateXml());
        }

        $buyer = $mInvoice->addChild('G_SG2');
        XMLHelpers::append($buyer, $this->buyer->generateXml('BY'));

        $seller = $mInvoice->addChild('G_SG2');
        XMLHelpers::append($seller, $this->seller->generateXml('SE'));

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
        // BT-9 - Payment due date
        XMLHelpers::append(
            $paymentTerms,
            (new DateTimePeriod())
                ->setCode(13)
                ->setDate($this->dateDue)
                ->generateXml()
        );
        $paymentTerms->addChild('S_PAI')
            ->addChild('C_C534')
            ->addChild('D_4461', $this->paymentMeans); // 30?

        // Document discounts
        foreach ($this->documentAllowances as $documentAllowance) {
            $allowance = $mInvoice->addChild('G_SG16');
            XMLHelpers::append($allowance, $documentAllowance->generateXml());
        }

        // Line items
        foreach ($this->items as $line => $item) {
            $line = $mInvoice->addChild('G_SG26');
            XMLHelpers::append($line, $item->generateXml());
        }

        // BT-106 - Total without discount
        $this->addMonetaryValue($mInvoice, $this->totalWithoutDiscount, 79);
        // BT-107 - Discounts amount (all discounts - item and document)
        $this->addMonetaryValue($mInvoice, $this->sumOfAllowances, 260);
        // BT-108 - Charges amount (all discounts - item and document)
        $this->addMonetaryValue($mInvoice, $this->sumOfCharges, 259);
        // BT-109 - Tax base sums
        $this->addMonetaryValue($mInvoice, $this->totalWithoutTax, 389);
        // BT-110 - Total tax amount
        $this->addMonetaryValue($mInvoice, $this->totalWithTax - $this->totalWithoutTax, 176);
        // BT-112 - Total amount with taxes
        $this->addMonetaryValue($mInvoice, $this->totalWithTax, 388);
        // BT-113 - Paid in advance
        if ($this->paidAmount)
            $this->addMonetaryValue($mInvoice, $this->paidAmount, 113);
        // BT-114 - Rounding amount
        if ($this->roundingAmount)
            $this->addMonetaryValue($mInvoice, $this->roundingAmount, 366);
        // BT-115 - Amount due for payment
        if (!$this->amountDueForPayment) {
            // If the amount has not been set - we select the total amount as payment
            $this->amountDueForPayment = $this->totalWithTax;
        }
        $this->addMonetaryValue($mInvoice, $this->amountDueForPayment, 9);

        // VAT summary
        foreach ($this->taxSummaries as $taxSummary) {
            $tax = $mInvoice->addChild('G_SG52');
            XMLHelpers::append($tax, $taxSummary->generateXml());
        }

        return $xml;
    }

    private function addMonetaryValue(\SimpleXMLElement &$xml, $amount, $type)
    {
        $sum = $xml->addChild('G_SG50');
        XMLHelpers::append(
            $sum,
            (new MonetaryAmount())
                ->setAmount(round($amount, 2))
                ->setCode($type)
                ->generateXml()
        );
    }

    public function setDevNotes($notes): Invoice
    {
        $this->devNotes = $notes;

        return $this;
    }
}
