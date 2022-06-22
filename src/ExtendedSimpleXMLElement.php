<?php

namespace Media24si\eSlog2;

class ExtendedSimpleXMLElement extends \SimpleXMLElement
{
    /**
     * Adds a child with $value inside CDATA
     * @param $name
     * @param $value
     */
    public function addChildWithCDATA($name, $value = NULL) {
        $new_child = $this->addChild($name);

        if ($new_child !== NULL) {
            $node = dom_import_simplexml($new_child);
            $no   = $node->ownerDocument;
            $node->appendChild($no->createCDATASection($value));
        }

        return $new_child;
    }
}
