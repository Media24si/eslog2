<?php

namespace Media24si\eSlog2;

class InvoiceItem
{
    public const UNIT_UNIT = 'C62';
    public const UNIT_PIECE = 'H87';

    public int $rowNumber;
    public string $name;
    public string $description = '';
    public string $additionalDescription = '';
    public float $quantity;
    public float $priceWithoutTax;

    public float $itemPrice;
    public float $itemPriceWithTax;

    public float $totalWithTax;      # total - after discount and tax
    public float $totalWithoutTax;   # total - after discount

    public float $discountPercentage = 0;
    public float $discountAmount = 0;

    public float $taxRate;
    public string $taxRateType = 'S';

    public string $unit = self::UNIT_UNIT;
    public string $ean = '';

    public int $quantityType = 47;

    public string $buyerItemIdentifier = '';
    public string $sellerItemIdentifier = '';

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

    public function setRowNumber(int $rowNumber): InvoiceItem
    {
        $this->rowNumber = $rowNumber;

        return $this;
    }

    public function setName(string $name): InvoiceItem
    {
        $this->name = $name;

        return $this;
    }

    public function setQuantity(float $quantity): InvoiceItem
    {
        $this->quantity = $quantity;

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

    public function setPriceWithoutTax(float $priceWithoutTax): InvoiceItem
    {
        $this->priceWithoutTax = $priceWithoutTax;

        return $this;
    }

    public function setTotalWithTax(float $totalWithTax): InvoiceItem
    {
        $this->totalWithTax = $totalWithTax;

        return $this;
    }

    public function setTotalWithoutTax(float $totalWithoutTax): InvoiceItem
    {
        $this->totalWithoutTax = $totalWithoutTax;

        return $this;
    }

    public function setDiscountPercentage(float $discountPercentage): InvoiceItem
    {
        $this->discountPercentage = $discountPercentage;

        return $this;
    }

    public function setDiscountAmount(float $discountAmount): InvoiceItem
    {
        $this->discountAmount = $discountAmount;

        return $this;
    }

    public function setTaxRate(float $taxRate): InvoiceItem
    {
        $this->taxRate = $taxRate;

        return $this;
    }

    public function setTaxRateType(float $taxRateType): InvoiceItem
    {
        $this->taxRateType = $taxRateType;

        return $this;
    }

    public function setUnit(string $unit): InvoiceItem
    {
        $this->unit = $unit;

        return $this;
    }

    public function setEan(string $ean): InvoiceItem
    {
        $this->ean = $ean;

        return $this;
    }

    public function setQuantityType(int $quantityType): InvoiceItem
    {
        $this->quantityType = $quantityType;

        return $this;
    }

    public function setDescription(string $description): InvoiceItem
    {
        $this->description = $description;

        return $this;
    }

    public function setAdditionalDescription(string $additionalDescription): InvoiceItem
    {
        $this->additionalDescription = $additionalDescription;

        return $this;
    }

    public function generateXml(): \SimpleXMLElement
    {
        $xml = new \SimpleXMLElement('<G_SG26></G_SG26>');

        $sLine = $xml->addChild('S_LIN');
        $sLine->addChild('D_1082', $this->rowNumber);
        if ($this->ean) {
            $ean = $sLine->addChild('C_C212');
            $ean->addChild('D_7140', $this->ean);
            $ean->addChild('D_7143', '0160');
        }

        // Item identifiers
        if ($this->buyerItemIdentifier) {
            $sLine = $xml->addChild('S_PIA');
            $sLine->addChild('D_4347', 5);
            $pia = $sLine->addChild('C_C212');
            $pia->addChild('D_7140', $this->buyerItemIdentifier);
            $pia->addChild('D_7143', 'IN');
        }

        if ($this->sellerItemIdentifier) {
            $sLine = $xml->addChild('S_PIA');
            $sLine->addChild('D_4347', 5);
            $pia = $sLine->addChild('C_C212');
            $pia->addChild('D_7140', $this->sellerItemIdentifier);
            $pia->addChild('D_7143', 'SA');
        }

        // Item name
        $desc = $xml->addChild('S_IMD');
        $desc->addChild('D_7077', 'F');
        $desc->addChild('C_C273')
            ->addChild('D_7008', mb_substr($this->name, 0, 35));

        // Item description
        if ($this->description) {
            $desc = $xml->addChild('S_IMD');
            $desc->addChild('D_7077', 'A');
            $desc->addChild('C_C273')
                ->addChild('D_7008', mb_substr($this->description, 0, 256));
        }

        // Quantity
        $qnt = $xml->addChild('S_QTY')
            ->addChild('C_C186');
        $qnt->addChild('D_6063', $this->quantityType);
        $qnt->addChild('D_6060', round($this->quantity, 2));
        $qnt->addChild('D_6411', $this->unit);

        // Additional item description
        if ($this->additionalDescription) {
            $desc = $xml->addChild('S_FTX');
            $desc->addChild('D_44517', 'ACB');
            $desc->addChild('C_C108')
                ->addChild('D_4440', mb_substr($this->additionalDescription, 0, 512));
        }

        // Value total
        $vbd = $xml->addChild('G_SG27')
            ->addChild('S_MOA')
            ->addChild('C_C516');
        $vbd->addChild('D_5025', '38');
        $vbd->addChild('D_5004', round($this->totalWithTax, 2));

        $taxAmount = $xml->addChild('G_SG27')
            ->addChild('S_MOA')
            ->addChild('C_C516');
        $taxAmount->addChild('D_5025', '203');
        $taxAmount->addChild('D_5004', round($this->totalWithoutTax, 2));

        // Value before discount
//        $vbd = $xml->addChild('G_SG29')
//            ->addChild('S_PRI')
//            ->addChild('C_C516');
//        $vbd->addChild('D_5025', '203');
//        $vbd->addChild('D_5004', round($this->price_without_tax * $this->quantity, 2));

        // Price without tax
        $priceWoTax = $this->priceWithoutTax;
        if ($this->discountPercentage) {
            $priceWoTax = $priceWoTax * (1 - ($this->discountPercentage / 100));
        }

        $woTax = $xml->addChild('G_SG29')
            ->addChild('S_PRI')
            ->addChild('C_C509');
        $woTax->addChild('D_5125', 'AAA');
        $woTax->addChild('D_5118', round($priceWoTax, 2));
        $woTax->addChild('D_5284', 1);
        $woTax->addChild('D_6411', 'C62');

        // if ($this->discountPercentage) {
            $priceWoDiscount = $xml->addChild('G_SG29')
                ->addChild('S_PRI')
                ->addChild('C_C509');
            $priceWoDiscount->addChild('D_5125', 'AAB');
            $priceWoDiscount->addChild('D_5118', round($this->priceWithoutTax, 2));
            $priceWoDiscount->addChild('D_5284', 1);
            $priceWoDiscount->addChild('D_6411', 'C62');
        // }

        // Tax
        $tax = $xml->addChild('G_SG34');
        $taxes = $tax->addChild('S_TAX');
        $taxes->addChild('D_5283', '7');
        $taxes->addChild('C_C241')
            ->addChild('D_5153', 'VAT');
        $taxes->addChild('C_C243')
            ->addChild('D_5278', round($this->taxRate, 2));
        $taxes->addChild('D_5305', $this->taxRateType);

        $taxAmount = $tax->addChild('S_MOA')
            ->addChild('C_C516');
        $taxAmount->addChild('D_5025', '124');
        $taxAmount->addChild('D_5004', round($this->totalWithTax - $this->totalWithoutTax, 2));

        $taxAmount = $tax->addChild('S_MOA')
            ->addChild('C_C516');
        $taxAmount->addChild('D_5025', '125');
        $taxAmount->addChild('D_5004', round($this->totalWithoutTax, 2));

        if ($this->discountPercentage) {
            $sg39 = $xml->addChild('G_SG39');
            $sg39->addChild('S_ALC')
                ->addChild('D_5463', 'A');

            $percentage = $sg39->addChild('G_SG41')
                ->addChild('S_PCD')
                ->addChild('C_C501');
            $percentage->addChild('D_5245', 1);
            $percentage->addChild('D_5482', $this->discountPercentage);

            $amount = $sg39->addChild('G_SG42')
                ->addChild('S_MOA')
                ->addChild('C_C516');
            $amount->addChild('D_5025', '204');
            $amount->addChild('D_5004', round($this->discountAmount, 2));
        }

        return $xml;
    }
}
