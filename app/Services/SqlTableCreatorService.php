<?php

namespace App\Services;

use App\Enums\FieldType;
use App\Models\TemplateValidator;
use App\Models\TemplateValidatorFields;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SqlTableCreatorService
{
    static function createForTemplate(TemplateValidator $templateValidator): void
    {
        $sqlColumns =  self::getColumnsSql($templateValidator->fields);
        $primaryKeySql = "PRIMARY KEY ({$templateValidator->primary_info})";

        $foreignKeys = self::getForeignKeysSql($templateValidator->fields->whereNotNull('refers_to'));

        if (!empty($foreignKeys)) {
            $foreignKeys = ",\n" . $foreignKeys;
        }

        $sqlCreateTable = <<<SQL
            CREATE TABLE `{$templateValidator->name}` (
                {$sqlColumns},
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                $primaryKeySql
                $foreignKeys
            )
SQL;

        $directory = "templateTables/{$templateValidator->id}";

        Storage::createDirectory($directory);
        Storage::put("$directory/creator.sql", $sqlCreateTable);
    }


    private static function getColumnsSql($fields): string
    {
        /** @var TemplateValidatorFields $field */
        $sqlColumns = [];
        foreach ($fields as $field) {
            $columnType = FieldType::getSqlType($field->type);
            $sqlColumn = "{$field->name} {$columnType}";
            if ($field->type === FieldType::STRING) {
                if ($field->max_length) {
                    $sqlColumn .= "({$field->max_length})";
                } else {
                    $sqlColumn .= "(255)";
                }
            }

            if ($field->required) {
                $sqlColumn .= " NOT NULL";
            } else {
                $sqlColumn .= " NULL";
            }

            if ($field->unique) {
                $sqlColumn .= " UNIQUE";
            }

            if ($field->default_value) {
                $sqlColumn .= " DEFAULT '{$field->default_value}'";
            }

            if ($field->min_length) {
                $sqlColumn .= " CHECK(LENGTH({$field->name}) >= {$field->min_length})";
            }

            if ($field->max_length) {
                $sqlColumn .= " CHECK(LENGTH({$field->name}) <= {$field->max_length})";
            }

            if ($field->pattern) {
                $sqlColumn .= " CHECK({$field->name} ~ '{$field->pattern}')";
            }

            $sqlColumns[] = $sqlColumn;

        }

        return join(",\n", $sqlColumns);
    }

    private static function getForeignKeysSql(Collection $fields): string
    {
        $sqlForeignKeys = [];
        foreach ($fields as $field) {
            $reference = $field->reference;

            if ($reference->type !== $field->type) {
                Log::info("Field {$field->name} is type {$field->type->value} but reference {$reference->name} is type {$reference->type->value}");
                continue;
            }

            $sqlForeignKey = "FOREIGN KEY ({$field->name}) REFERENCES {$reference->template->name}({$reference->name})";
            $sqlForeignKeys[] = $sqlForeignKey;
        }

        return join(",\n", $sqlForeignKeys);
    }
}
