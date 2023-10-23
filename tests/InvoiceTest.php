<?php

namespace Media24si\eSlog2\Tests;

use DateTime;
use Media24si\eSlog2\Business;
use Media24si\eSlog2\Envelope\Hal;
use Media24si\eSlog2\FreeText;
use Media24si\eSlog2\Invoice;
use Media24si\eSlog2\InvoiceItem;
use Media24si\eSlog2\InvoiceItemDiscount;
use Media24si\eSlog2\ReferenceDocument;
use Media24si\eSlog2\TaxSummary;
use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
    /** @test */
    public function generateBasicInvoice()
    {
        $invoice = (new Invoice())
            ->setSeller(
                (new Business())
                    ->setName('Company d.o.o.')
                    ->setAddress('Our Address 100')
                    ->setZipCode(1000)
                    ->setCity('Ljubljana')
                    ->setCountry('Slovenia')
                    ->setCountryIsoCode('SI')
                    ->setVatId('12345678')
                    ->setRegistrationNumber('555555555')
                    ->setIban('SI56111122223333456')
                    ->setBic('BAKOSI2XXXX')
            )
            ->setBuyer(
                (new Business())
                    ->setName('Recipient & Name')
                    ->setAddress('Recipient Address & 101')
                    ->setZipCode(1000)
                    ->setCity('Ljubljana')
                    ->setCountry('Slovenia')
                    ->setCountryIsoCode('SI')
                    ->setVatId('87654321')
                    ->setRegistrationNumber('666666666')
                    ->setIban('SI56111122223333444')
                    ->setBic('BAKOSI2XXXX')
                    ->setBankTitle('Bank & assiciates')
            )
            ->setInvoiceNumber('1-2019-154')
            ->setIntroText('Some general description about the invoice. <br> &')
            ->setTotalWithoutTax(1.48)
            ->setTotalWithTax(1.81)
            ->setLocationAddress('Ljubljana')
            ->setDateIssued(new \DateTime())
            ->setDateOfService(new \DateTime())
            ->setDateDue(new \DateTime())
            // ->setPaymentReference('SI001-2019-154')
            ->addItem(
                (new InvoiceItem())
                    ->setRowNumber(1)
                    ->setName('Acme 0.33L')
                    ->setQuantity(2)
                    ->setPriceWithoutTax(0.82)
                    ->setPriceWithoutTaxBeforeDiscounts(0.82)
                    ->setTotalWithTax(1.81)
                    ->setTotalWithoutTax(1.48)
                    ->setTaxRate(22)
                    ->addItemAllowance(
                        (new InvoiceItemDiscount())
                            ->setPercentage(10)
                            ->setAmount(0.16)
                    )
            )
            ->addTaxSummary(
                (new TaxSummary())
                    ->setRate(22)
                    ->setBaseAmount(1.48)
                    ->setAmount(0.33)
            )
            ->addReferenceDocument(
                (new ReferenceDocument())
                    ->setDocumentNumber('NAR-123456')
                    ->setTypeCode(ReferenceDocument::TYPE_ORDER_NUMBER)
            );
        // TODO: Enter special chars 
        $invoice->addFreeText(new FreeText(FreeText::CODE_TERMS_OF_PAYMENTS, "PROSIMO, DA PRI PLAČILU & NAVEDETE SKLICEVALNO ŠTEVILKO ŠT. 873912783219731."));
        $invoice->addFreeText(new FreeText(FreeText::CODE_ENTIRE_TRANSACTION_SET,
            'Reklamacije sprejemamo v roku 8 dni od dneva izstavitve računa.<br> & V primeru zamude s plačilom si pridržujemo pravico obračuna zakonitih zamudnih obresti.'));
        $invoice->addFreeText(new FreeText(FreeText::CODE_REGULATORY_INFORMATION,
            'Matična št.: 6589049, Davčna št.: SI21423512, Okrožno sodišče v Ljubljani, < & vložna št. Srg 2014/14983, Osnovni kapital: 7.500,00 EUR <br> TRR račun pri LON D.D., št.: SI56 6000 0000 0997 522, BIC: HLONSI22'));

        // Reference documents
        $invoice->addReferenceDocument(new ReferenceDocument(ReferenceDocument::TYPE_PROJECT_NUMBER));//AEP
        $invoice->addReferenceDocument(new ReferenceDocument(ReferenceDocument::TYPE_CONTRACT));//CT
        $invoice->addReferenceDocument(new ReferenceDocument(ReferenceDocument::TYPE_ORDER_NUMBER, 1));//ON
        $invoice->addReferenceDocument(new ReferenceDocument(ReferenceDocument::TYPE_ORDER_NUMBER_SUPPLIER));//VN
        $invoice->addReferenceDocument(new ReferenceDocument(ReferenceDocument::TYPE_RECEIVING_ADVICE_NUMBER)); //ALO
        $invoice->addReferenceDocument(new ReferenceDocument(ReferenceDocument::TYPE_DELIVERY_FORM));//AAK
        $invoice->addReferenceDocument(new ReferenceDocument(ReferenceDocument::TYPE_GOVERNMENT_CONTRACT_NUMBER));//GC
        $invoice->addReferenceDocument(new ReferenceDocument(ReferenceDocument::TYPE_EXTERNAL_OBJECT_REFERENCE));//ATS
        $invoice->addReferenceDocument(
            (new ReferenceDocument(
                ReferenceDocument::TYPE_PREVIOUS_INVOICE_NUMBER,
                'SI123838173927'
            ))
                ->setDTM(ReferenceDocument::DTM_TYPE_PREVIOUS_INVOICE_DATE, new DateTime())
        );//OI
        $invoice->addReferenceDocument(new ReferenceDocument(ReferenceDocument::TYPE_PAYMENT_REFERENCE, 'SI123838173927'));//PQ

        $dom = dom_import_simplexml($invoice->generateXml())->ownerDocument;
        $dom->formatOutput = true;

        var_dump($dom->saveXML());

        $invoice->generateXml()->saveXML('inv.xml');

        $envelope = (new Hal())
            ->setFromInvoice($invoice)
            ->setDocId(1)
            ->setExternalDocId(1)
            ->setRemittanceInformation('Plačilo računa ' . 1);
        $envelope->generateXml()->saveXML('envelope.xml');
    }
}
