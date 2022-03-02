<?php

namespace App\class;

use JetBrains\PhpStorm\Pure;

class UploadFile extends Uploads
{
    use ValidationFile;

    private array $extension = ['zip', 'rar', 'pdf'];

    public function __construct($file)
    {
        parent::__construct($file);
    }

    public function upload()
    {
        return $this->rename();
    }
}