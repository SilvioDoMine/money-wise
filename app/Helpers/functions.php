<?php

if (! function_exists('onlyNumbers')) {
    /**
     * Remove todos os caracteres não numéricos da string.
     *
     * @param   string  $string
     * @return  string
     */
    function onlyNumbers($string): string
    {
        return preg_replace('/\D/', '', $string);
    }
}