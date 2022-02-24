<?php

class UploadFotos
{
    public string $file;
    public string $newName;
    public array $extensions = ['png', 'jpg'];

    public function file(string $file): string
    {
        return $this->file = $file;
    }

    public function extension(): array|string
    {
        return pathinfo($this->file, PATHINFO_EXTENSION);
    }

    public function rename()
    {
        $uniqId = uniqid(true);
        $this->newName = $uniqId . '.' . $this->extension();
    }

    public function upload()
    {
        return $this->newName;
    }
}