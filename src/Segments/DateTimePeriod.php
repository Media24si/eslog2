<?php

namespace Media24si\eSlog2\Segments;

class DateTimePeriod
{
    public int $code;
    public \DateTime $date;
    public string $format = 'Y-m-d';

    public function setCode(int $code): DateTimePeriod
    {
        $this->code = $code;

        return $this;
    }

    public function setDate(\DateTime $date): DateTimePeriod
    {
        $this->date = $date;

        return $this;
    }

    public function setFormat(string $format): DateTimePeriod
    {
        $this->format = $format;

        return $this;
    }

    public function generateXml(): \SimpleXMLElement
    {
        $xml = new \SimpleXMLElement('<root></root>');

        $dateTimePeriod = $xml->addChild('S_DTM');
            $c507 = $dateTimePeriod->addChild('C_C507');
                $c507->addChild('D_2005', $this->code);
                $c507->addChild('D_2380', $this->date->format($this->format));

        return $xml;
    }
}