<?php

namespace Media24si\eSlog2;

/**
 * VAT group (skupina za DDV) that a seller or buyer is a member of.
 *
 * The group's VAT ID is written to the party's VA reference (BT-31 / BT-48),
 * while the group's name and address are written to the invoice note
 * (BT-22) with the SUR (seller) or CUR (buyer) text subject code.
 */
class VatGroup
{
    /**
     * VAT group name (BT-22)
     *
     * @var string
     */
    public string $name;

    /**
     * VAT group address line (BT-22)
     *
     * @var string
     */
    public string $address;

    /**
     * VAT group post code (BT-22)
     *
     * @var string|null
     */
    public ?string $zipCode = null;

    /**
     * VAT group city (BT-22)
     *
     * @var string
     */
    public string $city;

    /**
     * VAT group VAT identifier (BT-31 / BT-48), preferably with the country prefix, e.g. SI87654321
     *
     * @var string
     */
    public string $vatId;

    public function setName(string $name): VatGroup
    {
        $this->name = $name;

        return $this;
    }

    public function setAddress(string $address): VatGroup
    {
        $this->address = $address;

        return $this;
    }

    public function setZipCode(?string $zipCode): VatGroup
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function setCity(string $city): VatGroup
    {
        $this->city = $city;

        return $this;
    }

    public function setVatId(string $vatId): VatGroup
    {
        $this->vatId = $vatId;

        return $this;
    }

    /**
     * Address in a single line, e.g. "Nova ulica 5, 1000 Ljubljana"
     */
    public function getFullAddress(): string
    {
        return $this->address . ', ' . trim($this->zipCode . ' ' . $this->city);
    }
}
