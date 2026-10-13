# WIP - Work in progress

# eSlog 2.0 php generator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/media24si/eslog2.svg?style=flat-square)](https://packagist.org/packages/media24si/eslog2)
[![Total Downloads](https://img.shields.io/packagist/dt/media24si/eslog2.svg?style=flat-square)](https://packagist.org/packages/media24si/eslog2)
![GitHub Actions](https://github.com/media24si/eslog2/actions/workflows/main.yml/badge.svg)

PHP eSlog 2.0 generator. Inspired by [Python e-Racun](https://github.com/boris-savic/python-eracun) package.

## Installation

You can install the package via composer:

```bash
composer require media24si/eslog2
```

## Usage

The library writes the values you give it into an eSLOG 2.0 XML document. It does **not** calculate anything: line totals, VAT amounts and invoice totals are all provided by you, so make sure they add up.

### Quick start

```php
<?php

require 'vendor/autoload.php';

use Media24si\eSlog2\Business;
use Media24si\eSlog2\Invoice;
use Media24si\eSlog2\InvoiceItem;
use Media24si\eSlog2\TaxSummary;

$seller = (new Business())
    ->setName('Prodajalec d.o.o.')
    ->setAddress('Dunajska cesta 100')
    ->setZipCode('1000')
    ->setCity('Ljubljana')
    ->setCountry('Slovenia')
    ->setCountryIsoCode('SI')
    ->setVatId('SI12345678')
    ->setTaxNumber('12345678')
    ->setRegistrationNumber('1234567000')
    ->setIban('SI56191000000123438')
    ->setBic('BAKOSI2XXXX');

$buyer = (new Business())
    ->setName('Kupec d.o.o.')
    ->setAddress('Slovenska ulica 1')
    ->setZipCode('2000')
    ->setCity('Maribor')
    ->setCountry('Slovenia')
    ->setCountryIsoCode('SI')
    ->setVatId('SI11223344')
    ->setTaxNumber('11223344')
    ->setRegistrationNumber('7654321000')
    ->setIban('SI56020100012345678');

$invoice = (new Invoice())
    ->setInvoiceNumber('2026-0001')
    ->setDateIssued(new DateTime('2026-10-12'))
    ->setDateOfService(new DateTime('2026-10-10'))
    ->setDateDue(new DateTime('2026-10-27'))
    ->setSeller($seller)
    ->setBuyer($buyer)
    ->addItem(
        (new InvoiceItem())
            ->setRowNumber(1)
            ->setName('Svetovanje')
            ->setQuantity(2)
            ->setPriceWithoutTaxBeforeDiscounts(10.00)
            ->setPriceWithoutTax(10.00)
            ->setTotalWithoutTax(20.00)
            ->setTotalWithTax(24.40)
            ->setTaxRate(22)
    )
    ->addTaxSummary(
        (new TaxSummary())
            ->setRate(22)
            ->setBaseAmount(20.00)
            ->setAmount(4.40)
    )
    ->setTotalWithoutDiscount(20.00)
    ->setTotalWithoutTax(20.00)
    ->setTotalWithTax(24.40);

$invoice->generateXml()->saveXML('invoice.xml');
```

### Invoice header and totals

| Method | eSLOG 2.0 field | Notes |
|---|---|---|
| `setInvoiceNumber()` | BT-1 | Required |
| `setInvoiceType()` | BT-3 | Defaults to `Invoice::TYPE_INVOICE` (380). Other values include `TYPE_CREDIT_NOTE`, `TYPE_PREPAYMENT_INVOICE` and `TYPE_CORRECTED_INVOICE`. |
| `setCurrency()` | BT-5 | Defaults to `EUR` |
| `setDateIssued()` | BT-2 | Required |
| `setDateOfService()` | BT-72 | Optional |
| `setDateDue()` | BT-9 | Required |
| `setTotalWithoutDiscount()` | BT-106 | Sum of the line net amounts |
| `setSumOfAllowances()` | BT-107 | Sum of the document-level allowances |
| `setSumOfCharges()` | BT-108 | Sum of the document-level charges |
| `setTotalWithoutTax()` | BT-109 | Required. Total without VAT. |
| `setTotalWithTax()` | BT-112 | Required. Total with VAT. The VAT total (BT-110) is the difference between this and BT-109. |
| `setPaidAmount()` | BT-113 | Amount paid in advance |
| `setRoundingAmount()` | BT-114 | |
| `setAmountDueForPayment()` | BT-115 | Defaults to the total with VAT |

### Businesses (seller and buyer)

Both parties are `Business` objects. Pass them to the invoice with `setSeller()` and `setBuyer()`.

```php
$business = (new Business())
    ->setName('Podjetje d.o.o.')            // BT-27 / BT-44, max. 70 characters
    ->setAddress('Dunajska cesta 100')      // BT-35 / BT-50, max. 35 characters
    ->setZipCode('1000')                    // BT-38 / BT-53
    ->setCity('Ljubljana')                  // BT-37 / BT-52
    ->setCountry('Slovenia')
    ->setCountryIsoCode('SI')               // BT-40 / BT-55
    ->setVatId('SI12345678')                // RFF VA:   BT-31 / BT-48
    ->setTaxNumber('12345678')              // RFF AHP:  BT-32 / NBT-013 (optional, see below)
    ->setRegistrationNumber('1234567000')   // RFF 0199: BT-30 / BT-47 (matična številka)
    ->setIban('SI56191000000123438')
    ->setBic('BAKOSI2XXXX')
    ->setBankTitle('Banka d.d.')
    ->setInternalIdentifier('STR-001')      // RFF CR
    ->setPhone('+386 1 234 56 78')
    ->setEmail('racuni@podjetje.si');
```

**VAT ID and tax number.** `setVatId()` fills the VAT identifier (`VA`) and `setTaxNumber()` fills the tax registration number (`AHP`). If you don't call `setTaxNumber()`, `AHP` falls back to the `setVatId()` value, so a single `setVatId('12345678')` gives you the same value in both fields. e-SLOG 2.0 requires `AHP` for both the seller and the buyer, so set at least one of the two.

### VAT groups (skupina za DDV)

If the seller or the buyer is a member of a VAT group, attach a `VatGroup` to that `Business`. The invoice is then written as described in the FURS guidance *Pojasnilo zapisa podatkov o skupini za DDV v e-računih e-SLOG 2.0* (August 2026).

```php
use Media24si\eSlog2\VatGroup;

$seller = (new Business())
    ->setName('X d.o.o.')                   // the member's own name and address
    ->setAddress('Novo naselje 3')
    ->setZipCode('1000')
    ->setCity('Ljubljana')
    ->setCountry('Slovenia')
    ->setCountryIsoCode('SI')
    ->setTaxNumber('12345678')              // the member's tax number -> AHP
    ->setRegistrationNumber('1234567000')
    ->setIban('SI56191000000123438')
    ->setVatGroup(
        (new VatGroup())
            ->setName('Y - skupina za DDV')
            ->setAddress('Nova ulica 5')
            ->setZipCode('1000')
            ->setCity('Ljubljana')
            ->setVatId('SI87654321')        // the group's VAT ID -> VA
    );

$invoice->setSeller($seller);
```

`setVatId()` on the member works in place of `setTaxNumber()`. For a group member, the member's own VAT ID is only used for `AHP`, because `VA` always carries the group's VAT ID.

The same works for the buyer (`$invoice->setBuyer(...)`). This is what changes in the XML:

| | Seller in a VAT group | Buyer in a VAT group |
|---|---|---|
| Name (`D_3036`) | Member name + ` - član skupine za DDV` | Member name |
| Address | Member's address | Member's address |
| `RFF VA` | Group's VAT ID | Group's VAT ID |
| `RFF AHP` | Member's tax number (**required**) | Member's tax number |
| Free text | `FTX SUR`: group name and address | `FTX CUR`: group name and address |

- If a seller in a VAT group has neither a tax number nor a VAT ID, `generateXml()` throws a `Media24si\eSlog2\Exceptions\ValidationException`.
- The HAL envelope also uses the group's VAT ID as the sender or receiver identifier.

For the seller above, the generated XML contains:

```xml
<S_FTX>
  <D_4451>SUR</D_4451>
  <C_C108>
    <D_4440>Y - skupina za DDV</D_4440>
    <D_4440_2>Nova ulica 5, 1000 Ljubljana</D_4440_2>
  </C_C108>
</S_FTX>
<!-- ... -->
<G_SG2>
  <S_NAD>
    <D_3035>SE</D_3035>
    <C_C080>
      <D_3036>X d.o.o. - član skupine za DDV</D_3036>
    </C_C080>
    <!-- member's address -->
  </S_NAD>
  <!-- ... -->
  <G_SG3><S_RFF><C_C506><D_1153>VA</D_1153><D_1154>SI87654321</D_1154></C_C506></S_RFF></G_SG3>
  <G_SG3><S_RFF><C_C506><D_1153>AHP</D_1153><D_1154>12345678</D_1154></C_C506></S_RFF></G_SG3>
</G_SG2>
```

### Line items

```php
use Media24si\eSlog2\InvoiceItemDiscount;

$invoice->addItem(
    (new InvoiceItem())
        ->setRowNumber(2)                           // BT-126
        ->setName('Letna licenca')                  // BT-153, max. 35 characters
        ->setDescription('Licenca za leto 2027')    // BT-154
        ->setAdditionalDescription('Velja od 1. 1. 2027') // BT-127, line note
        ->setSellerItemIdentifier('LIC-01')         // BT-155
        ->setBuyerItemIdentifier('K-778')           // BT-156
        ->setEan('3830000000000')                   // BT-157
        ->setQuantity(1)                            // BT-129
        ->setUnit(InvoiceItem::UNIT_PIECE)          // BT-130, defaults to UNIT_UNIT (C62)
        ->setPriceWithoutTaxBeforeDiscounts(100.00) // BT-148, gross unit price
        ->setPriceWithoutTax(100.00)                // BT-146, net unit price
        ->setTotalWithoutTax(90.00)                 // BT-131, line net amount after line discounts
        ->setTotalWithTax(98.55)                    // NBT-031, line amount with VAT
        ->setTaxRate(9.5)                           // BT-152
        ->setTaxRateType(TaxSummary::CODE_STANDARD_RATE) // BT-151, the default
        ->addItemAllowance(                         // BG-27, line discount
            (new InvoiceItemDiscount())
                ->setReason('Popust')               // BT-139
                ->setPercentage(10)                 // BT-138
                ->setBaseAmount(100.00)             // BT-137
                ->setAmount(10.00)                  // BT-136
        )
);
```

### Document-level discounts

```php
use Media24si\eSlog2\InvoiceDiscount;

$invoice
    ->addDocumentAllowance(                         // BG-20
        (new InvoiceDiscount())
            ->setReason('Količinski popust')        // BT-97
            ->setPercentage(5)                      // BT-94
            ->setBaseAmount(20.00)                  // BT-93
            ->setAmount(1.00)                       // BT-92
            ->setVatRate(22)                        // BT-96
            ->setVatCategoryCode(TaxSummary::CODE_STANDARD_RATE) // BT-95
    )
    ->setSumOfAllowances(1.00);                     // BT-107
```

### VAT breakdown

Add one `TaxSummary` per VAT rate and category (BG-23):

```php
$invoice
    ->addTaxSummary((new TaxSummary())->setRate(22)->setBaseAmount(20.00)->setAmount(4.40))
    ->addTaxSummary((new TaxSummary())->setRate(9.5)->setBaseAmount(90.00)->setAmount(8.55))
    ->addTaxSummary(
        (new TaxSummary())
            ->setRate(0)
            ->setBaseAmount(50.00)
            ->setAmount(0)
            ->setCategoryCode(TaxSummary::CODE_VAT_REVERSE_CHARGE) // AE
    );
```

Available category codes: `CODE_STANDARD_RATE` (S, the default), `CODE_ZERO_RATE` (Z), `CODE_EXEMPT_FROM_TAX` (E), `CODE_VAT_REVERSE_CHARGE` (AE), `CODE_VAT_EXEMPT_FOR_EEA_INTRA_COMMUNITY_SUPPLY_OF_GOODS_AND_SERVICES` (K), `CODE_FREE_EXPORT_ITEM_TAX_NOT_CHARGED` (G) and `CODE_SERVICES_OUTSIDE_SCOPE_OF_TAX` (O).

### Free texts

```php
use Media24si\eSlog2\FreeText;

$invoice
    ->addFreeText(new FreeText(FreeText::CODE_TERMS_OF_PAYMENTS, 'Plačilo v 15 dneh.'))   // BT-20
    ->addFreeText(new FreeText(FreeText::CODE_ENTIRE_TRANSACTION_SET, 'Hvala za nakup!')) // BT-22
    ->addFreeText(new FreeText(FreeText::CODE_REGULATORY_INFORMATION, 'Okrožno sodišče v Ljubljani, Srg 2026/123')) // BT-33
    ->addFreeText(
        (new FreeText(FreeText::CODE_EXEMPTION, 'Oproščeno po 1. odstavku 46. člena ZDDV-1'))
            ->setTextValue2('Druga vrstica besedila')   // second line, D_4440_2
    );
```

### Reference documents

```php
use Media24si\eSlog2\ReferenceDocument;

$invoice
    ->addReferenceDocument(new ReferenceDocument(ReferenceDocument::TYPE_ORDER_NUMBER, 'NAR-123')) // BT-13
    ->addReferenceDocument(new ReferenceDocument(ReferenceDocument::TYPE_CONTRACT, 'POG-2026-7'))  // BT-12
    ->addReferenceDocument(new ReferenceDocument(ReferenceDocument::TYPE_PAYMENT_REFERENCE, 'SI00 20260001')) // payment reference, required by the HAL envelope
    ->addReferenceDocument(
        (new ReferenceDocument(ReferenceDocument::TYPE_PREVIOUS_INVOICE_NUMBER, '2026-0000'))     // BT-25
            ->setDTM(ReferenceDocument::DTM_TYPE_PREVIOUS_INVOICE_DATE, new DateTime('2026-09-30')) // BT-26
    );
```

### Generating the XML

`generateXml()` returns a `\SimpleXMLElement`:

```php
$xml = $invoice->generateXml();

$xml->saveXML('invoice.xml');   // save to a file
$string = $xml->asXML();        // or get it as a string

// pretty-printed
$dom = dom_import_simplexml($xml)->ownerDocument;
$dom->formatOutput = true;
$pretty = $dom->saveXML();
```

### HAL bank envelope

The HAL envelope used for e-invoice exchange through the banks can be built from the invoice. The invoice must contain a payment reference (`ReferenceDocument::TYPE_PAYMENT_REFERENCE`).

```php
use Media24si\eSlog2\Envelope\Attachment;
use Media24si\eSlog2\Envelope\Hal;

$envelope = (new Hal())
    ->setFromInvoice($invoice)
    ->setDocId('2026-0001')
    ->setExternalDocId('2026-0001')
    ->setRemittanceInformation('Plačilo računa 2026-0001');

$envelope->addAttachment(new Attachment('racun-2026-0001.pdf', 48213, 'application/pdf', 'Račun v PDF obliki'));

$envelope->generateXml()->saveXML('envelope.xml');
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email bostjan.oblak@salomon.si instead of using the issue tracker.

## Credits

-   [Bostjan Oblak](https://github.com/media24si)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com) by [Beyond Code](http://beyondco.de/).
