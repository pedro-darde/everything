<?php

namespace App\Enums;

enum FieldType: string
{
    /*
     *
     * {
        name: "Text",
        value: "string"
    },
    {
        name: "Integer",
        value: "int"
    },
    {
        name: "Numeric (Decimal or Float)",
        value: "number"
    },
    {
        name: "Date",
        value: "date"
    },
    {
        name: "Date Time",
        value: "datetime"
    },
    {
        name: "Boolean",
        value: "boolean"
    },
    {
        name: "Huge Text",
        value: "text"
    }
     *
     *
     * */

    case STRING = 'string';
    case INTEGER = 'int';
    case NUMERIC = 'number';
    case DATE = 'date';
    case DATETIME = 'datetime';
    case BOOLEAN = 'boolean';
    case TEXT = 'text';

    public static function getSqlType(FieldType $type): string {
        return match($type) {
            self::DATE => 'date',
            self::DATETIME => 'datetime',
            self::INTEGER => 'int',
            self::TEXT => 'text',
            self::NUMERIC => 'decimal',
            self::BOOLEAN => 'boolean',
            default => 'varchar'
        };

    }

}
