<?php

namespace App\Class;

class UploadFoto extends Uploads
{
    public array $extension = ['png', 'jpg'];

    public function upload()
    {
        return $this->newName;
    }
}