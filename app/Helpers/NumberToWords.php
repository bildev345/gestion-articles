<?php

namespace App\Helpers;

class NumberToWords
{
    public static function convert($number)
    {
        $formatter = new \NumberFormatter('fr', \NumberFormatter::SPELLOUT);
        $text = $formatter->format($number);
        return ucfirst($text);
    }
}