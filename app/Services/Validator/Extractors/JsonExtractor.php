<?php

namespace App\Services\Validator\Extractors;

use Exception;

class JsonExtractor extends FileDataExtractor
{
    /**
     * @throws Exception
     */
    public function extract(): void
    {
        $fileDataRaw = file_get_contents($this->file->path());

        if (!json_validate($fileDataRaw)) {
            throw new Exception('Invalid JSON structure');
        }

        $this->fileData = json_decode($fileDataRaw, true);
    }
}
