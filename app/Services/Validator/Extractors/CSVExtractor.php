<?php

namespace App\Services\Validator\Extractors;

use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Support\Collection;

class CSVExtractor extends FileDataExtractor
{

    public function extract(): void
    {
        $rows   = collect(file($this->file->path()));

        if ($this->templateOnly) {
            $this->defineOnlyTemplates($rows);
            return;
        }

        $rows->each(function ($row) {
            if ($this->isTemplateRow($row)) {
                [$templateName, $campos] = explode(":", $row);
                $this->fileData[$templateName] = [
                    'campos' => trim($campos),
                    'itens' => []
                ];
            } else {
                $lastCreatedTemplate = array_keys($this->fileData)[count($this->fileData) - 1];
                $templateInfo = $this->fileData[$lastCreatedTemplate];
                $values = array_map('trim', explode(";", $row));
                $this->fileData[$lastCreatedTemplate]['itens'][] = array_combine(
                    explode(";", $templateInfo['campos']),
                    $values
                );
            }

        });
    }

    private function defineOnlyTemplates(Collection $rows)
    {
        $rows = $rows->filter(function ($row) {
            return str_contains($row, ":");
        });
        $rows = $rows->map(function ($row) {
            return trim(explode(":", $row)[0]);
        });
        $this->fileData = $rows->flatten()->toArray();
    }


    private function isTemplateRow($row): bool {
        return str_contains($row, ":");
    }
}
