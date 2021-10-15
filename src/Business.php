<?php

namespace Media24si\eSlog2;

class Business
{
    public string $name;
    public string $address;
    public string $city;
    public string $zipCode;
    public string $country;
    public string $countryIsoCode;
    public string $iban;
    public string $bic = '';
    public string $bankTitle = '';
    public string $registrationNumber;
    public string $vatId;
    public string $internalIdentifier = '';

    public string $phone = '';
    public string $email = '';

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

    public function setZipCode(?string $zipCode): Business
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function setCountry(string $country): Business
    {
        $this->country = $country;

        return $this;
    }

    public function setCountryIsoCode(string $countryIsoCode): Business
    {
        $this->countryIsoCode = $countryIsoCode;

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

    public function setRegistrationNumber(string $registrationNumber): Business
    {
        $this->registrationNumber = $registrationNumber;

        return $this;
    }

    public function setVatId(string $vatId): Business
    {
        $this->vatId = $vatId;

        return $this;
    }

    public function setInternalIdentifier(string $internalIdentifier): Business
    {
        $this->internalIdentifier = $internalIdentifier;

        return $this;
    }

    public function setPhone(string $phone): Business
    {
        $this->phone = $phone;

        return $this;
    }

    public function setEmail(string $email): Business
    {
        $this->email = $email;

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

        $nad->addChild('C_C819')
            ->addChild('D_3228', $this->country);
        if ($this->zipCode != null) {
            $nad->addChild('D_3251', $this->zipCode);
        }
        $nad->addChild('D_3207', $this->countryIsoCode);

        // IBAN
        $fii = $xml->addChild('S_FII');
        $fii->addChild('D_3035', $type === 'SE' ? 'RB' : 'BB');
        $c078 = $fii->addChild('C_C078');
        $c078->addChild('D_3194', $this->iban);
        if ($this->bankTitle) {
            $c078->addChild('D_3192', $this->bankTitle);
        }

        if ($this->bic) {
            $fii->addChild('C_C088')
                ->addChild('D_3433', $this->bic);
        }

        // VAT
        $vat = $xml->addChild('G_SG3')
            ->addChild('S_RFF')
            ->addChild('C_C506');
        $vat->addChild('D_1153', 'VA');
        $vat->addChild('D_1154', $this->vatId);
        $vat = $xml->addChild('G_SG3')
            ->addChild('S_RFF')
            ->addChild('C_C506');
        $vat->addChild('D_1153', 'AHP');
        $vat->addChild('D_1154', $this->vatId);

        // Internal identifier
        if ($this->internalIdentifier) {
            $identifier = $xml->addChild('G_SG3')
            ->addChild('S_RFF')
            ->addChild('C_C506');
            $identifier->addChild('D_1153', 'CR');
            $identifier->addChild('D_1154', $this->internalIdentifier);
        }

        // Registration number
        $vat = $xml->addChild('G_SG3')
            ->addChild('S_RFF')
            ->addChild('C_C506');
        $vat->addChild('D_1153', '0199');
        $vat->addChild('D_1154', $this->registrationNumber);

        // Contact
        if ($this->phone || $this->email) {
            $contact = $xml->addChild('G_SG5');
            $contact->addChild('S_CTA')
                ->addChild('D_3139', $type === 'SE' ? 'SU' : 'PD');


            if ($this->phone) {
                $p = $contact->addChild('S_COM')
                    ->addChild('C_C076');
                $p->addChild('D_3148', $this->phone);
                $p->addChild('D_3155', 'TE');
            }

            if ($this->email) {
                $e = $contact->addChild('S_COM')
                    ->addChild('C_C076');
                $e->addChild('D_3148', $this->email);
                $e->addChild('D_3155', 'EM');
            }
        }

        return $xml;
    }

    public function setBankTitle(string $bankTitle): Business
    {
        $this->bankTitle = $bankTitle;

        return $this;
    }
}
