<?php

namespace App\Services\Validator;

trait CsvReader
{
    static function parseLines($file) {
        // a ideia é o csv vir assim:
        // template:nome_dos_campos[]
        // nome_template:dados_dos_campos[]

        // transformar isso em array: chave: valor e agrupar por template
    }
}
