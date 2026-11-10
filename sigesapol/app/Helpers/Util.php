<?php
namespace App\Helpers;

class Util 
{
  public static function GetNombreMes($mes) {
      $aMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
      if($mes < 1 || $mes > 12) {
        $mes = 1;
      }
      return $aMeses[intval($mes)-1];
  }

  public static function FormatoFechaATexto($fecha) {
    
    $aData = explode('-', $fecha);
    $nMes = (int)$aData[1];
    if ($nMes > 12) {
      return '';
    }
    return $aData[2] . ' ' . Util::GetNombreMes($nMes) . ' ' . $aData[0];
  }

  public static function GetFechaTexto($fecha) {
    $aFecha = explode('-', $fecha);
    if (count($aFecha) != 3) {
      return '--';
    }
    return $aFecha[2] . '/' . $aFecha[1] . '/' . $aFecha[0];
  }

  public static function DBClear($value)
  {
    $value = str_replace("'", "", $value);
    return $value;
  }

  public static function obtenerDiasDeSemana(){
    $arraysDias = ['--Seleccionar--','Todos','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'];
    return $arraysDias;
  }
  
  public static function obtenerDiaPorKey($key_dia){
    $arraysDias = ['--Seleccionar--','Todos','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'];
    foreach ($arraysDias as $key => $value) {
      if ($key == $key_dia) {
        return $value;
      }
    }
  }
  
  public static function obtenerDiaPorValue($value_dia){
    $arraysDias = ['--Seleccionar--','Todos','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'];
    foreach ($arraysDias as $key => $value) {
      if ($value == $value_dia) {
        return $key;
      }
    }
  }
}