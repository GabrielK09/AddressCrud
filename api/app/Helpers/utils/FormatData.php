<?php

use Illuminate\Support\Facades\Log;

function replaceNumbersFunction(string $value): string 
{
    //Log::channel('utils')->debug('Start replaceNumbersFunction');
    
    return preg_replace('/[^0-9]/', '', $value);
}

function formatCEP(string $cep): string|null
{
    if(empty($cep)) return null;
    //Log::channel('utils')->debug('Start formatCEP');

    return replaceNumbersFunction($cep);   
}
