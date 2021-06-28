<?php

namespace Media24si\eSlog2;

class InvoiceItem
{
    public int $row_number;
    public string $item_name;
    public float $quantity;
    public float $price_without_tax;

    public float $total_with_tax;      # total - after discount and tax
    public float $total_without_tax;   # total - after discount

    public float $discount_percentage;
    public float $discount_amount;

    public float $tax_rate;
    public string $tax_rate_type = 'S';

    public string $unit = 'PCE';
    public string $ean = '';

    public string $item_description_code = 'F';
    public int $quantity_type = 47;

    public function setRowNumber(int $row_number): InvoiceItem
    {
        $this->row_number = $row_number;

        return $this;
    }

    public function setItemName(string $item_name): InvoiceItem
    {
        $this->item_name = $item_name;

        return $this;
    }

    public function setQuantity(float $quantity): InvoiceItem
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function setPriceWithoutTax(float $price_without_tax): InvoiceItem
    {
        $this->price_without_tax = $price_without_tax;

        return $this;
    }

    public function setTotalWithTax(float $total_with_tax): InvoiceItem
    {
        $this->total_with_tax = $total_with_tax;

        return $this;
    }

    public function setTotalWithoutTax(float $total_without_tax): InvoiceItem
    {
        $this->total_without_tax = $total_without_tax;

        return $this;
    }

    public function setDiscountPercentage(float $discount_percentage): InvoiceItem
    {
        $this->discount_percentage = $discount_percentage;

        return $this;
    }

    public function setDiscountAmount(float $discount_amount): InvoiceItem
    {
        $this->discount_amount = $discount_amount;

        return $this;
    }

    public function setTaxRate(float $tax_rate): InvoiceItem
    {
        $this->tax_rate = $tax_rate;

        return $this;
    }

    public function setTaxRateType(float $tax_rate_type): InvoiceItem
    {
        $this->tax_rate_type = $tax_rate_type;

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

    public function setItemDescriptionCode(string $item_description_code): InvoiceItem
    {
        $this->item_description_code = $item_description_code;

        return $this;
    }

    public function setQuantityType(int $quantity_type): InvoiceItem
    {
        $this->quantity_type = $quantity_type;

        return $this;
    }

    public function generateXml(int $line): \SimpleXMLElement
    {
        $xml = new \SimpleXMLElement('<G_SG26></G_SG26>');

        $sLine = $xml->addChild('S_LIN');
        $sLine->addChild('D_1082', $line);
        if ($this->ean) {
            $ean = $sLine->addChild('C_C212');
            $ean->addChild('D_7140', $this->ean);
            $ean->addChild('D_7143', '0160');
        }

        // Description
        $desc = $xml->addChild('S_IMD');
        $desc->addChild('D_7077', $this->item_description_code);
        $desc->addChild('C_C273')
            ->addChild('D_7008', mb_substr($this->item_name, 0, 35));

        // Quantity
        $qnt = $xml->addChild('S_QTY')
            ->addChild('C_C186');
        $qnt->addChild('D_6063', $this->quantity_type);
        $qnt->addChild('D_6060', $this->quantity);
        $qnt->addChild('D_6411', $this->unit);

        // Value before discount
        $vbd = $xml->addChild('G_SG29')
            ->addChild('S_MOA')
            ->addChild('C_C516');
        $vbd->addChild('D_5025', '203');
        $vbd->addChild('D_5004', round($this->price_without_tax * $this->quantity, 2));

        // Value total
        $vbd = $xml->addChild('G_SG29')
            ->addChild('S_MOA')
            ->addChild('C_C516');
        $vbd->addChild('D_5025', '38');
        $vbd->addChild('D_5004', $this->total_with_tax);

        // Price without tax
        $priceWoTax = $this->price_without_tax;
        if ($this->discount_percentage) {
            $priceWoTax = $priceWoTax * (1 - ($this->discount_percentage / 100));
        }

        $woTax = $xml->addChild('G_SG29')
            ->addChild('S_PRI')
            ->addChild('C_C509');
        $woTax->addChild('D_5125', 'AAA');
        $woTax->addChild('D_5118', round($priceWoTax, 2));
        $woTax->addChild('D_5284', 1);
        $woTax->addChild('D_6411', 'C62');

        if ($this->discount_percentage) {
            $priceWoDiscount = $xml->addChild('G_SG29')
                ->addChild('S_PRI')
                ->addChild('C_C509');
            $priceWoDiscount->addChild('D_5125', 'AAB');
            $priceWoDiscount->addChild('D_5118', $this->price_without_tax);
            $priceWoDiscount->addChild('D_5284', 1);
            $priceWoDiscount->addChild('D_6411', 'C62');
        }

        // Tax
        $tax = $xml->addChild('G_SG34');
        $taxes = $tax->addChild('S_TAX');
        $taxes->addChild('D_5283', '7');
        $taxes->addChild('C_C241')
            ->addChild('D_5153', 'VAT');
        $taxes->addChild('C_C243')
            ->addChild('D_5278', $this->tax_rate);
        $taxes->addChild('D_5305', $this->tax_rate_type);

        $taxAmount = $tax->addChild('S_MOA')
            ->addChild('C_C516');
        $taxAmount->addChild('D_5025', '125');
        $taxAmount->addChild('D_5004', $this->total_without_tax);

        $taxAmount = $tax->addChild('S_MOA')
            ->addChild('C_C516');
        $taxAmount->addChild('D_5025', '124');
        $taxAmount->addChild('D_5004', $this->total_with_tax - $this->total_without_tax);

        if ($this->discount_percentage) {
            $sg39 = $xml->addChild('G_SG39');
            $sg39->addChild('S_ALC')
                ->addChild('D_5463', 'A');

            $percentage = $sg39->addChild('G_SG41')
                ->addChild('S_PCD')
                ->addChild('C_C501');
            $percentage->addChild('D_5245', 1);
            $percentage->addChild('D_5482', $this->discount_percentage);

            $amount = $sg39->addChild('G_SG42')
                ->addChild('S_MOA')
                ->addChild('C_C516');
            $amount->addChild('D_5025', '204');
            $amount->addChild('D_5004', $this->discount_amount);
        }

        return $xml;
    }
}
