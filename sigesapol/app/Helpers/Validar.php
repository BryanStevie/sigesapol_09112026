<?php
namespace App\Helpers;

class Validar
{
    // Validación de datos numéricos en decimales

    public static function decimalEnRango($valor, $minimo = 0, $maximo = 100)
    {
        $valor = floatval($valor);
        $minimo = floatval($minimo); 
        $maximo = floatval($maximo);
        if ($valor >= $minimo && $valor <= $maximo) {
            return true;
        } 
        return false;
    }

    public static function enteroEnRango($valor, $minimo = 0, $maximo = 100)
    {
        $valor = intval($valor);
        $minimo = intval($minimo); 
        $maximo = intval($maximo);
        if ($valor >= $minimo && $valor <= $maximo) {
            return true;
        } 
        return false;
    }
}