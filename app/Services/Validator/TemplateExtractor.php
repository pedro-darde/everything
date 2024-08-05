<?php

namespace App\Services\Validator;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class TemplateExtractor {

    private array $templates = [];
    public function __construct(protected UploadedFile $file, protected $templateOnly = false)
    {
        $this->extract();
    }

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
              $this->templates[$templateName] = [
                  'campos' => trim($campos),
                  'itens' => []
              ];
           } else {
               $lastCreatedTemplate = array_keys($this->templates)[count($this->templates) - 1];
               $templateInfo = $this->templates[$lastCreatedTemplate];
               $values = array_map('trim', explode(";", $row));
               $this->templates[$lastCreatedTemplate]['itens'][] = array_combine(
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
        $this->templates = $rows->flatten()->toArray();
    }


    private function isTemplateRow($row): bool {
        return str_contains($row, ":");
    }

    public function getTemplates(): array {
        if ($this->templateOnly) {
            return $this->templates;
        }
        return $this->templates;
    }
}
