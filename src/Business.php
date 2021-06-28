<?php

namespace Media24si\eSlog2;

class Business
{
    public string $name;
    public string $address;
    public string $city;
    public string $zip_code;
    public string $country;
    public string $country_iso_code;
    public string $iban;
    public string $bic;
    public string $registration_number;
    public string $vat_id;

    public function setName(string $name): Business
    {
        $this->name = $name;

        return $this;
    }

    public function setAddress(string $address): Business
    {
        $this->address = $address;

        return $this;
    }

    public function setCity(string $city): Business
    {
        $this->city = $city;

        return $this;
    }

    public function setZipCode(string $zip_code): Business
    {
        $this->zip_code = $zip_code;

        return $this;
    }

    public function setCountry(string $country): Business
    {
        $this->country = $country;

        return $this;
    }

    public function setCountryIsoCode(string $country_iso_code): Business
    {
        $this->country_iso_code = $country_iso_code;

        return $this;
    }

    public function setIban(string $iban): Business
    {
        $this->iban = $iban;

        return $this;
    }

    public function setBic(string $bic): Business
    {
        $this->bic = $bic;

        return $this;
    }

    public function setRegistrationNumber(string $registration_number): Business
    {
        $this->registration_number = $registration_number;

        return $this;
    }

    public function setVatId(string $vat_id): Business
    {
        $this->vat_id = $vat_id;

        return $this;
    }

    public function generateXml($type = 'SE'): \SimpleXMLElement
    {
        $xml = new \SimpleXMLElement('<G_SG2></G_SG2>');

        $nad = $xml->addChild('S_NAD');
        $nad->addChild('D_3035', $type);

        // Name
        $nad->addChild('C_C080')
            ->addChild('D_3036', mb_substr($this->name, 0, 70));

        // Address
        $nad->addChild('C_C059')
            ->addChild('D_3042', mb_substr($this->address, 0, 35));

        $nad->addChild('D_3164', $this->city);
        $nad->addChild('D_3251', $this->zip_code);
        $nad->addChild('D_3207', $this->country_iso_code);

        // IBAN
        $fii = $xml->addChild('S_FII');
        $fii->addChild('D_3035', $type === 'SE' ? 'RB' : 'BB');
        $fii->addChild('C_C078')
            ->addChild('D_3194', $this->iban);
        $fii->addChild('C_C088')
            ->addChild('D_3433', $this->bic);

        // VAT
        $vat = $xml->addChild('G_SG3')
            ->addChild('S_RFF')
            ->addChild('C_C506');
        $vat->addChild('D_1153', 'AHP');
        $vat->addChild('D_1154', $this->vat_id);

        // Registration number
        $vat = $xml->addChild('G_SG3')
            ->addChild('S_RFF')
            ->addChild('C_C506');
        $vat->addChild('D_1153', '0199');
        $vat->addChild('D_1154', $this->registration_number);

        return $xml;
    }
}
