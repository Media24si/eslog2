<?php

namespace Media24si\eSlog2\Envelope;

use Media24si\eSlog2\Business;

class Hal extends Envelope
{
    public string $doc_id;
    public string $externalDocId;
    public string $remittanceInformation;

    public function setDocId(string $doc_id): Hal
    {
        $this->doc_id = $doc_id;

        return $this;
    }

    public function setExternalDocId(string $externalDocId): Hal
    {
        $this->externalDocId = $externalDocId;

        return $this;
    }

    public function setRemittanceInformation(string $remittanceInformation): Hal
    {
        $this->remittanceInformation = $remittanceInformation;

        return $this;
    }

    public function generateXml(): \SimpleXMLElement
    {
        /**
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><package xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="hal:icl:01" pkg_type="einvoice"></package>');
        $xml->addChild('timestamp', (new \DateTime())->format('Y-m-d\TH:i:s'));

        $envelope = $xml->addChild('envelope');
        $envelope->addAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance', 'http://www.w3.org/2001/XMLSchema-instance');
        $envelope->addAttribute('xsi:noNamespaceSchemaLocation', 'icl_eb_envelope_einvoice.xsd', 'http://www.w3.org/2001/XMLSchema-instance');
         */
        $envelope = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="icl_eb_envelope_einvoice.xsd"></envelope>');

        $this->addSenderReceiver($envelope, 'sender', $this->issuer);
        $this->addSenderReceiver($envelope, 'receiver', $this->recipient);

        $docData = $envelope->addChild('doc_data');
        $docData->addChild('doc_type', '0002');
        $docData->addChild('doc_type_ver', '01');
        $docData->addChild('doc_id', $this->doc_id);
        $docData->addChild('external_doc_id', $this->externalDocId);
        $docData->addChild('timestamp', $this->dateIssued->format('Y-m-d\TH:i:s'));

        $paymentData = $envelope->addChild('payment_data');
        $paymentData->addChild('payment_method', 0);

        $this->addCreditorDebtor($paymentData, 'creditor', $this->issuer);
        $this->addCreditorDebtor($paymentData, 'debtor', $this->recipient);

        $paymentData->addChild('requested_execution_date', $this->deadline->format('Y-m-d'));
        $paymentData->addChild('amount', $this->amount);
        $paymentData->addChild('currency', $this->currency);

        $remittanceInformation = $paymentData->addChild('remittance_information');
        $remittanceInformation->addChild('creditor_structured_reference', str_replace(' ', '', $this->paymentReference));
        $remittanceInformation->addChild('additional_remittance_information', $this->remittanceInformation);

        $paymentData->addChild('purpose', 'COST');

        if (count($this->attachemnts)) {
            $sumSize = 0;
            foreach ($this->attachemnts as $attach) {
                $sumSize += $attach->size;
            }

            $attachments = $envelope->addChild('attachments');
            $attachments->addChild('hash', '0000000000000000000000000000000000000000');
            $attachments->addChild('size', $sumSize);
            $attachments->addChild('count', count($this->attachemnts));

            foreach ($this->attachemnts as $attach) {
                $attachment = $attachments->addChild('attachment');
                $attachment->addChild('filename', $attach->filename);
                $attachment->addChild('size', $attach->size);
                $attachment->addChild('type', $attach->type);
                $attachment->addChild('description', $attach->description);
            }
        }

        return $envelope;
    }

    private function addSenderReceiver(\SimpleXMLElement &$envelope, $type, Business $who)
    {
        $entity = $envelope->addChild($type);
        $entity->addChild('name', htmlspecialchars($who->name));
        $entity->addChild('country', $who->countryIsoCode);
        $entity->addChild('address', htmlspecialchars($who->address));
        $entity->addChild('address', htmlspecialchars($who->zipCode . ' ' . $who->city));
        $entity->addChild($type . '_identifier', $who->vatId);

        $entityEddress = $entity->addChild($type . '_eddress');
        $entityEddress->addChild($type . '_agent', $who->receiver_agent ?? str_pad($who->bic, 11, 'X', STR_PAD_RIGHT));
        $entityEddress->addChild($type . '_mailbox', $who->iban);

        $entity->addChild('phone', $who->phone);
    }

    private function addCreditorDebtor(\SimpleXMLElement &$paymentData, $type, Business $who)
    {
        $entity = $paymentData->addChild($type);
        $entity->addChild('name', htmlspecialchars($who->name));
        $entity->addChild('country', $who->countryIsoCode);
        $entity->addChild('address', htmlspecialchars($who->address));
        $entity->addChild('address', htmlspecialchars($who->zipCode . ' ' . $who->city));
        $entity->addChild('identification');
        $entity->addChild($type . '_agent', str_pad($who->bic, 11, 'X', STR_PAD_RIGHT));
        $entity->addChild($type . '_account', $who->iban);
    }
}
