<?php

namespace Media24si\eSlog2;

use Illuminate\Support\Arr;
use Media24si\eSlog2\Segments\Identifier;

/**
 * Invoice line (BG-25)
 * 
 * A group of business terms providing information about individual Invoice lines.
 */
class InvoiceItem
{
    public const UNIT_UNIT = 'C62';
    public const UNIT_PIECE = 'H87';
    public const UNIT_THOUSAND = 'T3';

    /**
     * Invoice line identifier (BT-126)
     *
     * @var integer
     */
    public int $rowNumber;

    /**
     * Item name (BT-153)
     *
     * @var string
     */
    public string $name;
    
    /**
     * Item description (BT-154)
     *
     * @var string
     */
    public string $description = '';

    /**
     * Invoice line note (BT-127)
     *
     * @var string
     */
    public string $additionalDescription = '';

    /**
     * Invoiced quantity (BT-129)
     * 
     * @var float
     */
    public float $quantity;

    /**
     * Invoiced quantity unit of measure (BT-130)
     * 
     * The unit of measure that applies to the invoiced quantity.
     *
     * @var string
     */
    public string $unit = self::UNIT_UNIT;

    /**
     * Item net price (BT-146)
     * 
     * The price of an item, exclusive of VAT, after subtracting item price discount.
     * 
     * @var float
     */
    public float $priceWithoutTax;

    /**
     * Item price discount (BT-147)
     * 
     * The total discount subtracted from the Item gross price to calculate the Item net price (BT-146).
     *
     * @var float
     */
    public float $itemPriceDiscount = 0;

    /**
     * Item gross price (BT-148)
     * 
     * The unit price, exclusive of VAT, before subtracting Item price discount.
     * 
     * @var float
     */
    public float $priceWithoutTaxBeforeDiscounts;

    /**
     * Invoice line net amount including VAT (NBT-031)
     * 
     * The total amount of the Invoice line, including VAT.
     *
     * @var float
     */
    public float $totalWithTax;      # total - after discount and tax

    /**
     * Invoice line net amount (BT-131)
     * 
     * The total amount of the Invoice line
     *
     * @var float
     */
    public float $totalWithoutTax;   # total - after discount

    /**
     * Invoice line allowances (BG-27)
     * 
     * Allowances (such as discounts) for a line item.
     *
     * @var array
     */
    public array $itemAllowances = [];

    /**
     * Invoiced item VAT rate (BT-152)
     * 
     * The VAT rate, represented as percentage that applies to the invoiced Item.
     *
     * @var array
     */
    public float $taxRate;

    /**
     * Invoiced item VAT category code (BT-151)
     * 
     * The VAT category code for the invoiced item.
     *
     * @var string
     */
    public string $taxRateType = TaxSummary::CODE_STANDARD_RATE;

    /**
     * Item standard identifier (BT-157)
     * 
     * An item identifier based on a registered scheme.
     *
     * @var string
     */
    public string $ean = '';

    /**
     * Item Buyer's identifier (BT-156)
     * 
     * An identifier assigned by the Buyer to the Item.
     *
     * @var string
     */
    public string $buyerItemIdentifier = '';

    /**
     * Item Seller's identifier (BT-155)
     * 
     * An identifier assigned by the Seller to the Item.
     *
     * @var string
     */
    public string $sellerItemIdentifier = '';

    // Helpers - Store
    public float $itemPrice;
    public float $itemPriceWithTax;

    public function setBuyerItemIdentifier(string $buyerItemIdentifier): InvoiceItem
    {
        $this->buyerItemIdentifier = $buyerItemIdentifier;

        return $this;
    }

    public function setSellerItemIdentifier(string $sellerItemIdentifier): InvoiceItem
    {
        $this->sellerItemIdentifier = $sellerItemIdentifier;

        return $this;
    }

    /**
     * Invoice line identifier (BT-126)
     * 
     * A unique identifier for the individual line within the Invoice
     *
     * @param integer $rowNumber
     * @return InvoiceItem
     */
    public function setRowNumber(int $rowNumber): InvoiceItem
    {
        $this->rowNumber = $rowNumber;

        return $this;
    }

    /**
     * Item name (BT-153)
     *
     * @param string $name
     * @return InvoiceItem
     */
    public function setName(string $name): InvoiceItem
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Item description (BT-154)
     *
     * @param string $description
     * @return InvoiceItem
     */
    public function setDescription(string $description): InvoiceItem
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Invoiced quantity (BT-129)
     * 
     * The quantity of Items (goods or services) that is charged in the Invoice line.
     *
     * @param float $quantity
     * @return InvoiceItem
     */
    public function setQuantity(float $quantity): InvoiceItem
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Item net price (BT-146)
     * 
     * The price of an item, exclusive of VAT, after subtracting item price discount.
     *
     * @param float $priceWithoutTax
     * @return InvoiceItem
     */
    public function setPriceWithoutTax(float $priceWithoutTax): InvoiceItem
    {
        $this->priceWithoutTax = $priceWithoutTax;

        return $this;
    }

    /**
     * Item gross price (BT-148)
     * 
     * The unit price, exclusive of VAT, before subtracting Item price discount.
     *
     * @param float $priceWithoutTaxBeforeDiscounts
     * @return InvoiceItem
     */
    public function setPriceWithoutTaxBeforeDiscounts(float $priceWithoutTaxBeforeDiscounts): InvoiceItem
    {
        $this->priceWithoutTaxBeforeDiscounts = $priceWithoutTaxBeforeDiscounts;

        return $this;
    }

    /**
     * Invoice line net amount including VAT (NBT-031)
     * 
     * The total amount of the Invoice line, including VAT.
     *
     * @param float $totalWithTax
     * @return InvoiceItem
     */
    public function setTotalWithTax(float $totalWithTax): InvoiceItem
    {
        $this->totalWithTax = $totalWithTax;

        return $this;
    }

    /**
     * Invoice line net amount (BT-131)
     * 
     * The total amount of the Invoice line
     *
     * @param float $totalWithoutTax
     * @return InvoiceItem
     */
    public function setTotalWithoutTax(float $totalWithoutTax): InvoiceItem
    {
        $this->totalWithoutTax = $totalWithoutTax;

        return $this;
    }

    /**
     * Item price discount (BT-147)
     * 
     * The total discount subtracted from the Item gross price to calculate the Item net price.
     *
     * @param float $itemPriceDiscount
     * @return InvoiceItem
     */
    public function setItemPriceDiscount(float $itemPriceDiscount): InvoiceItem
    {
        $this->itemPriceDiscount = $itemPriceDiscount;

        return $this;
    }

    /**
     * Invoiced item VAT rate (BT-152)
     * 
     * The VAT rate, represented as percentage that applies to the invoiced Item.
     *
     * @param float $taxRate
     * @return InvoiceItem
     */
    public function setTaxRate(float $taxRate): InvoiceItem
    {
        $this->taxRate = $taxRate;

        return $this;
    }

    /**
     * Invoiced item VAT category code (BT-151)
     * 
     * The VAT category code for the invoiced item.
     *
     * @param string $taxRateType
     * @return InvoiceItem
     */
    public function setTaxRateType(string $taxRateType): InvoiceItem
    {
        $this->taxRateType = $taxRateType;

        return $this;
    }

    /**
     * Invoice line unit of measure code (BT-130)
     * 
     * The unit of measure that applies to the invoiced quantity.
     *
     * @param string $unit
     * @return InvoiceItem
     */
    public function setUnit(string $unit): InvoiceItem
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Item standard identifier (BT-157)
     * 
     * An item identifier based on a registered scheme.
     *
     * @param string $ean
     * @return InvoiceItem
     */
    public function setEan(string $ean): InvoiceItem
    {
        $this->ean = $ean;

        return $this;
    }

    /**
     * Invoice line note (BT-127)
     * 
     * A textual note that gives unstructured information that is relevant to the Invoice line.
     *
     * @param string $additionalDescription
     * @return InvoiceItem
     */
    public function setAdditionalDescription(string $additionalDescription): InvoiceItem
    {
        $this->additionalDescription = $additionalDescription;

        return $this;
    }

    /**
     * Invoice line allowances (BG-27)
     * 
     * Add allowances (such as discounts) to the line item.
     *
     * @param InvoiceItemDiscount $itemAllowance
     * @return InvoiceItem
     */
    public function addItemAllowance(InvoiceItemDiscount $itemAllowance): InvoiceItem
    {
        $this->itemAllowances[] = $itemAllowance;

        return $this;
    }

    public function setItemPrice(float $itemPrice): InvoiceItem
    {
        $this->itemPrice = $itemPrice;

        return $this;
    }

    public function setItemPriceWithTax(float $itemPriceWithTax): InvoiceItem
    {
        $this->itemPriceWithTax = $itemPriceWithTax;

        return $this;
    }

    /**
     * Item discount helper functions
     */
    public function getFullDiscountAmount(): float
    {
        // Get the sum of every itemAllowance amount field 
        return array_reduce($this->itemAllowances, function ($carry, $itemAllowance) {
            return $carry + $itemAllowance->getAmount();
        }, 0);
    }

    /**
     * End Item discount helper functions
     */

    public function generateXml(): \SimpleXMLElement
    {
        $xml = new \Media24si\eSlog2\ExtendedSimpleXMLElement('<G_SG26></G_SG26>');

        $sLine = $xml->addChild('S_LIN');
        $sLine->addChild('D_1082', $this->rowNumber);
        if ($this->ean) {
            XMLHelpers::append($sLine, (new Identifier())
                ->setIdentifier($this->ean)
                ->setIdentifierScheme('0160')
                ->generateXml());
        }

        // Item identifiers
        if ($this->buyerItemIdentifier) {
            $sLine = $xml->addChild('S_PIA');
            $sLine->addChild('D_4347', 5);
            XMLHelpers::append($sLine, (new Identifier())
                ->setIdentifier($this->buyerItemIdentifier)
                ->setIdentifierScheme('IN')
                ->generateXml());
        }

        if ($this->sellerItemIdentifier) {
            $sLine = $xml->addChild('S_PIA');
            $sLine->addChild('D_4347', 5);
            XMLHelpers::append($sLine, (new Identifier())
                ->setIdentifier($this->sellerItemIdentifier)
                ->setIdentifierScheme('SA')
                ->generateXml());
        }

        // Item name
        $desc = $xml->addChild('S_IMD');
        $desc->addChild('D_7077', 'F');
        $desc->addChild('C_C273')
            ->addChildWithCDATA('D_7008', htmlspecialchars(mb_substr($this->name, 0, 35)));

        // Item description
        if ($this->description) {
            $desc = $xml->addChild('S_IMD');
            $desc->addChild('D_7077', 'A');
            $desc->addChild('C_C273')
                ->addChildWithCDATA('D_7008', htmlspecialchars(mb_substr($this->description, 0, 256)));
        }

        // Quantity
        $qnt = $xml->addChild('S_QTY')
            ->addChild('C_C186');
        $qnt->addChild('D_6063', 47);
        $qnt->addChild('D_6060', round($this->quantity, 2));
        $qnt->addChild('D_6411', $this->unit);

        // Additional item description
        if ($this->additionalDescription) {
            $desc = $xml->addChild('S_FTX');
            $desc->addChild('D_4451', 'ACB');
            $desc->addChild('C_C108')
                ->addChildWithCDATA('D_4440', htmlspecialchars(mb_substr($this->additionalDescription, 0, 512)));
        }

        // NBT-031
        // Value total
        $vbd = $xml->addChild('G_SG27')
            ->addChild('S_MOA')
            ->addChild('C_C516');
        $vbd->addChild('D_5025', '38');
        $vbd->addChild('D_5004', round($this->totalWithTax, 2));

        // BT-131
        $taxAmount = $xml->addChild('G_SG27')
            ->addChild('S_MOA')
            ->addChild('C_C516');
        $taxAmount->addChild('D_5025', '203');
        $taxAmount->addChild('D_5004', round($this->totalWithoutTax, 2));

        // BT-146 - Neto cena
        $woTax = $xml->addChild('G_SG29')
            ->addChild('S_PRI')
            ->addChild('C_C509');
        $woTax->addChild('D_5125', 'AAA');
        $woTax->addChild('D_5118', round($this->priceWithoutTax, 2));
        $woTax->addChild('D_5284', 1);
        $woTax->addChild('D_6411', 'C62');

        // BT-148 - Bruto cena
        $priceWoDiscount = $xml->addChild('G_SG29')
            ->addChild('S_PRI')
            ->addChild('C_C509');
        $priceWoDiscount->addChild('D_5125', 'AAB');
        $priceWoDiscount->addChild('D_5118', round($this->priceWithoutTaxBeforeDiscounts, 2));
        $priceWoDiscount->addChild('D_5284', 1);
        $priceWoDiscount->addChild('D_6411', 'C62');

        // Tax
        $tax = $xml->addChild('G_SG34');
        XMLHelpers::append($tax, (new TaxSummary())
            ->setRate($this->taxRate)
            ->setCategoryCode($this->taxRateType)
            ->setAmount(round($this->totalWithTax - $this->totalWithoutTax, 2))
            ->setBaseAmount(round($this->totalWithoutTax, 2))
            ->generateXml());

        foreach ($this->itemAllowances as $itemAllowance) {
            $allowance = $xml->addChild('G_SG39');
            XMLHelpers::append($allowance, $itemAllowance->generateXml());
        }

        if ($this->itemPriceDiscount) {
            // BT-147 - Popust na postavko
            $sg39 = $xml->addChild('G_SG39');
            $sg39alc = $sg39->addChild('S_ALC');
            $sg39alc->addChild('D_5463', 'A');
            $amount = $sg39->addChild('G_SG42')
                ->addChild('S_MOA')
                ->addChild('C_C516');
            $amount->addChild('D_5025', '509');
            $amount->addChild('D_5004', round($this->itemPriceDiscount, 2));
        }

        return $xml;
    }
}
