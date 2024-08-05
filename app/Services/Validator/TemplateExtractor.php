<?php

namespace App\Services\Validator;
use App\Services\Validator\Extractors\FileDataExtractor;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class TemplateExtractor {
    public function __construct(
        protected FileDataExtractor $extractor
    )
    {
        $this->extractor->extract();
    }

    public function getFileInformation(): array {
        return $this->extractor->getFileData();
    }
}
