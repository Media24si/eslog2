<?php

namespace Media24si\eSlog2;

class XMLHelpers
{
    public static function append(\SimpleXMLElement &$to, \SimpleXMLElement $from)
    {
        foreach ($from->children() as $simplexml_child) {
            $simplexml_temp = $to->addChild($simplexml_child->getName(), (string)$simplexml_child);
            foreach ($simplexml_child->attributes() as $attr_key => $attr_value) {
                $simplexml_temp->addAttribute($attr_key, $attr_value);
            }

            self::append($simplexml_temp, $simplexml_child);
        }
    }
}
