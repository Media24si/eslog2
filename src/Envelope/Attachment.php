<?php

namespace Media24si\eSlog2\Envelope;

class Attachment
{
    public string $filename;
    public int $size;
    public string $type;
    public string $description = '';

    public function __construct(string $filename, int $size, string $type, string $description = '')
    {
        $this->filename = $filename;
        $this->size = $size;
        $this->type = $type;
        $this->description = $description;
    }

    public function setFilename(string $filename): Attachment
    {
        $this->filename = $filename;

        return $this;
    }

    public function setSize(int $size): Attachment
    {
        $this->size = $size;

        return $this;
    }

    public function setType(string $type): Attachment
    {
        $this->type = $type;

        return $this;
    }

    public function setDescription(string $description): Attachment
    {
        $this->description = $description;

        return $this;
    }
}
