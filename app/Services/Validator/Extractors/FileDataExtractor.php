<?php

namespace App\Services\Validator\Extractors;


use Illuminate\Http\UploadedFile;

abstract class FileDataExtractor
{
    protected array $fileData = [];
    public function __construct(
        protected UploadedFile $file,
        protected $templateOnly = false
    ) {

    }
    abstract public function extract(): void;

    public function getFileData(): array
    {
        return $this->fileData;
    }
}
