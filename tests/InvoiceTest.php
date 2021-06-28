<?php

namespace Media24si\eSlog2\Tests;

use Media24si\eSlog2\Business;
use Media24si\eSlog2\Invoice;
use Media24si\eSlog2\InvoiceItem;
use Media24si\eSlog2\ReferenceDocument;
use Media24si\eSlog2\TaxSummary;
use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
    /** @test */
    public function generateBasicInvoice()
    {
        $invoice = (new Invoice())
            ->setIssuer(
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
            ->setRecipient(
                (new Business())
                    ->setName('Recipient Name')
                    ->setAddress('Recipient Address 101')
                    ->setZipCode(1000)
                    ->setCity('Ljubljana')
                    ->setCountry('Slovenia')
                    ->setCountryIsoCode('SI')
                    ->setVatId('87654321')
                    ->setRegistrationNumber('666666666')
                    ->setIban('SI56111122223333444')
                    ->setBic('BAKOSI2XXXX')
            )
            ->setInvoiceNumber('1-2019-154')
            ->setIntroText('Some general description about the invoice.')
            ->setTotalWithoutTax(1.48)
            ->setTotalWithTax(1.81)
            ->setGlobalDiscountAmount(null)
            ->setGlobalDiscountPercentage(null)
            ->setLocationAddress('Ljubljana')
            ->setDateIssued(new \DateTime())
            ->setDateOfService(new \DateTime())
            ->setDateDue(new \DateTime())
            ->setPaymentReference('SI001-2019-154')
            ->addItem(
                (new InvoiceItem())
                    ->setRowNumber(1)
                    ->setItemName('Acme 0.33L')
                    ->setQuantity(2)
                    ->setPriceWithoutTax(0.82)
                    ->setTotalWithTax(1.81)
                    ->setTotalWithoutTax(1.48)
                    ->setTaxRate(22)
                    ->setDiscountPercentage(10)
                    ->setDiscountAmount(0.16)
            )
            ->addTaxSummary(
                (new TaxSummary())
                    ->setRate(22)
                    ->setBase(1.48)
                    ->setAmount(0.33)
            )
            ->addReferenceDocument(
                (new ReferenceDocument())
                    ->setDocumentNumber('NAR-123456')
                    ->setTypeCode(ReferenceDocument::TYPE_ORDER_NUMBER)
            );

        $dom = dom_import_simplexml($invoice->generateXml())->ownerDocument;
        $dom->formatOutput = true;

        var_dump($dom->saveXML());

        $invoice->generateXml()->saveXML('inv.xml');
    }
}
