<?php

namespace App\class;

class Uploads
{
    private $file;
    protected $newName;

    public function __construct($file)
    {
        $this->file = $file;
    }

    protected function extension(): array|string
    {
        return pathinfo($this->file, PATHINFO_EXTENSION);
    }

    protected function rename()
    {
        $uniqId = uniqid(true);
        return $uniqId . '.' . $this->extension();
    }
}