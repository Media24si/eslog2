<?php

namespace Media24si\eSlog2\Segments;

class Identifier
{
    public string $identifier;
    public string $identifierScheme;

    public function setIdentifier(string $identifier): Identifier
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function setIdentifierScheme(string $identifierScheme): Identifier
    {
        $this->identifierScheme = $identifierScheme;

        return $this;
    }

    public function generateXml(): \SimpleXMLElement
    {
        $xml = new \SimpleXMLElement('<root></root>');

        $identifier = $xml->addChild('C_C212');
        $identifier->addChild('D_7140', $this->identifier);
        $identifier->addChild('D_7143', $this->identifierScheme);

        return $xml;
    }
}
