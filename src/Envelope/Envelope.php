<?php

namespace Media24si\eSlog2\Envelope;

use Media24si\eSlog2\Business;
use Media24si\eSlog2\Invoice;
use Media24si\eSlog2\ReferenceDocument;

abstract class Envelope
{
    public $attachemnts = [];

    public Business $issuer;
    public Business $recipient;

    public float $amount;
    public string $currency;

    public string $paymentReference;

    public \DateTime $dateIssued;
    public \DateTime $deadline;

    public abstract function generateXml(): \SimpleXMLElement;

    public function setFromInvoice(Invoice $invoice): self
    {
        $this->issuer = $invoice->issuer;
        $this->recipient = $invoice->recipient;

        $this->amount = $invoice->totalWithTax;
        $this->currency = $invoice->currency;

        $reference = $invoice->getReferenceDocument(ReferenceDocument::TYPE_PAYMENT_REFERENCE);
        $this->paymentReference = $reference->documentNumber;
        
        $this->dateIssued = $invoice->dateIssued;
        $this->deadline = $invoice->dateDue;

        return $this;
    }

    public function setSender(Business $issuer): self
    {
        $this->issuer = $issuer;

        return $this;
    }

    public function setReceiver(Business $receiver): self
    {
        $this->recipient = $receiver;

        return $this;
    }

    public function addAttachment(Attachment $attachment): self
    {
        $this->attachemnts[] = $attachment;

        return $this;
    }

    public function setCreatedAt(\DateTime $createdAt): Envelope
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
