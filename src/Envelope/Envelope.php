<?php

namespace Media24si\eSlog2\Envelope;

use Media24si\eSlog2\Business;
use Media24si\eSlog2\Invoice;

abstract class Envelope
{
    public $attachemnts = [];

    public Business $issuer;
    public Business $recipient;

    public float $amount;
    public string $currency;

    public string $paymentReference;

    public \DateTime $dateIssued;

    public abstract function generateXml(): \SimpleXMLElement;

    public function setFromInvoice(Invoice $invoice): self
    {
        $this->issuer = $invoice->issuer;
        $this->recipient = $invoice->recipient;

        $this->amount = $invoice->totalWithTax;
        $this->currency = $invoice->currency;

        $this->paymentReference = $invoice->paymentReference;
        $this->dateIssued = $invoice->dateIssued;

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
