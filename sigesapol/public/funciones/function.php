<?php	

function gfecha($fecha){ //gfecha('31/12/2010'); Res : 2010-12-31
	$fecha = substr($fecha,0,10);
    list($dia,$mes,$anio)=explode("/",$fecha);
    return $anio."-".$mes."-".$dia;
}

function sfechahora($fechahora){ //gfecha('2010-31-05 20:00:00	'); Res : 2010-05-31 20:00:00	
	$fecha = substr($fechahora,0,10);
    list($anio,$dia,$mes)=explode("-",$fecha);
    return $anio."-".$mes."-".$dia." ".substr($fechahora,11,8);
}

function vfecha($fecha){ //vfecha('2010-12-31'); Res : 31/12/2010
	$fecha = substr($fecha,0,10);
    list($anio,$mes,$dia)=explode("-",$fecha);
    return $dia."/".$mes."/".$anio;
}

function dfecha($fecha){ //vfecha('2010-12-31'); Res : 31-12-2010
	$fecha = substr($fecha,0,10);
    list($anio,$mes,$dia)=explode("-",$fecha);
    return $dia."-".$mes."-".$anio;
}

function vfechahora($fechahora){//vfecha('2011-06-01 20:00:00'); Res : 01/06/2011 20:00:00	 
	$newfecha=substr($fechahora,0,10);
	if($newfecha!=NULL){
    list($anio,$mes,$dia)=explode("-",$newfecha);	
    return $dia."/".$mes."/".$anio." ".$hora=substr($fechahora,11,5);
	}
}

function bisiesto($anio_actual){ 
   $bisiesto=false; 
   //probamos si el mes de febrero del año actual tiene 29 días 
     if (checkdate(2,29,$anio_actual)) 
     { 
      $bisiesto=true; 
   } 
   return $bisiesto; 
}

function tiempo_transcurrido_texto($fecha_nacimiento, $fecha_control){ //date('d/m/Y') o date('Y-m-d')

	//if $fecha_nacimiento date('Y-m-d')
	$array_gnacimiento = explode ( "-", $fecha_nacimiento ); 	
	if(count($array_gnacimiento)>1){
		$fecha_nacimiento =  vfecha($fecha_nacimiento);
	}
	
	//if $fecha_control date('Y-m-d')
	$array_gcontrol = explode ( "-", $fecha_control ); 	
	if(count($array_gcontrol)>1){
		$fecha_control =  vfecha($fecha_control);
	}

   $fecha_actual = $fecha_control;   
   
   if(!strlen($fecha_actual))
   {
      $fecha_actual = date('d/m/Y');
   }

   // separamos en partes las fechas 
   $array_nacimiento = explode ( "/", $fecha_nacimiento ); 
   $array_actual = explode ( "/", $fecha_actual ); 

   $anos =  $array_actual[2] - $array_nacimiento[2]; // calculamos años 
   $meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
   $dias =  $array_actual[0] - $array_nacimiento[0]; // calculamos días 

   //ajuste de posible negativo en $días 
   if ($dias < 0) 
   { 
      --$meses; 

      //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual 
      switch ($array_actual[1]) { 
         case 1: 
            $dias_mes_anterior=31;
            break; 
         case 2:     
            $dias_mes_anterior=31;
            break; 
         case 3:  
            if (bisiesto($array_actual[2])) 
            { 
               $dias_mes_anterior=29;
               break; 
            } 
            else 
            { 
               $dias_mes_anterior=28;
               break; 
            } 
         case 4:
            $dias_mes_anterior=31;
            break; 
         case 5:
            $dias_mes_anterior=30;
            break; 
         case 6:
            $dias_mes_anterior=31;
            break; 
         case 7:
            $dias_mes_anterior=30;
            break; 
         case 8:
            $dias_mes_anterior=31;
            break; 
         case 9:
            $dias_mes_anterior=31;
            break; 
         case 10:
            $dias_mes_anterior=30;
            break; 
         case 11:
            $dias_mes_anterior=31;
            break; 
         case 12:
            $dias_mes_anterior=30;
            break; 
      } 

      $dias=$dias + $dias_mes_anterior;

      if ($dias < 0)
      {
         --$meses;
         if($dias == -1)
         {
            $dias = 30;
         }
         if($dias == -2)
         {
            $dias = 29;
         }
      }
   }

   //ajuste de posible negativo en $meses 
   if ($meses < 0) 
   { 
      --$anos; 
      $meses=$meses + 12; 
   }

   //$tiempo[0] = $anos;
   //$tiempo[1] = $meses;
   //$tiempo[2] = $dias;
	
	if($anos > 0){
		$tiempo =  $anos . " Años";
		
	}else if($meses > 0){
		$tiempo =  $meses . " Meses";
		
	}else if($dias > 0){
		$tiempo =  $dias . " Dias";
	}

   return $tiempo;
}



   
function tiempo_transcurrido_entero($fecha_nacimiento, $fecha_control){ //date('d/m/Y') o date('Y-m-d')

	//if $fecha_nacimiento date('Y-m-d')
	$array_gnacimiento = explode ( "-", $fecha_nacimiento ); 	
	if(count($array_gnacimiento)>1){
		$fecha_nacimiento =  vfecha($fecha_nacimiento);
	}
	
	//if $fecha_control date('Y-m-d')
	$array_gcontrol = explode ( "-", $fecha_control ); 	
	if(count($array_gcontrol)>1){
		$fecha_control =  vfecha($fecha_control);
	}

   $fecha_actual = $fecha_control;   
   
   if(!strlen($fecha_actual))
   {
      $fecha_actual = date('d/m/Y');
   }

   // separamos en partes las fechas 
   $array_nacimiento = explode ( "/", $fecha_nacimiento ); 
   $array_actual = explode ( "/", $fecha_actual ); 

   $anos =  $array_actual[2] - $array_nacimiento[2]; // calculamos años 
   $meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
   $dias =  $array_actual[0] - $array_nacimiento[0]; // calculamos días 

   //ajuste de posible negativo en $días 
   if ($dias < 0) 
   { 
      --$meses; 

      //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual 
      switch ($array_actual[1]) { 
         case 1: 
            $dias_mes_anterior=31;
            break; 
         case 2:     
            $dias_mes_anterior=31;
            break; 
         case 3:  
            if (bisiesto($array_actual[2])) 
            { 
               $dias_mes_anterior=29;
               break; 
            } 
            else 
            { 
               $dias_mes_anterior=28;
               break; 
            } 
         case 4:
            $dias_mes_anterior=31;
            break; 
         case 5:
            $dias_mes_anterior=30;
            break; 
         case 6:
            $dias_mes_anterior=31;
            break; 
         case 7:
            $dias_mes_anterior=30;
            break; 
         case 8:
            $dias_mes_anterior=31;
            break; 
         case 9:
            $dias_mes_anterior=31;
            break; 
         case 10:
            $dias_mes_anterior=30;
            break; 
         case 11:
            $dias_mes_anterior=31;
            break; 
         case 12:
            $dias_mes_anterior=30;
            break; 
      } 

      $dias=$dias + $dias_mes_anterior;

      if ($dias < 0)
      {
         --$meses;
         if($dias == -1)
         {
            $dias = 30;
         }
         if($dias == -2)
         {
            $dias = 29;
         }
      }
   }

   //ajuste de posible negativo en $meses 
   if ($meses < 0) 
   { 
      --$anos; 
      $meses=$meses + 12; 
   }

   $tiempo[0] = $anos;
   $tiempo[1] = $meses;
   $tiempo[2] = $dias;

   return $tiempo;
}

function BuscarPorDNI($nro_documento,$nro_documento_usuario){
	ini_set("soap.wsdl_cache_enabled", "0"); //disabling WSDL cache	
    $parametros=array(); //parametros de la llamada
	$parametros['clienteUsuario']="R3NI3C_HOSPITAL";
	$parametros['clienteClave']="Hospital@2020";
	$parametros['clienteSistema']="Seguridad";
	$parametros['clienteIp']="172.31.42.129";
	$parametros['clienteMac']="50-E0-85-FB-30-60";

	$parametros['tipoDocUserClieFin']="1";
	$parametros['nroDocUserClieFin']=$nro_documento_usuario;
	$parametros['inDni']=$nro_documento;
	$parametros['inPrioridad']="";
	$servicio="http://hospitalcentral.policia.gob.pe:7001/WsCHPNPLNS.asmx?op=CallConsultarDniMayor&WSDL"; //url del servicio
	$cliente = new SoapClient($servicio, $parametros);
	$resultado =$cliente->CallConsultarDniMayor($parametros);  	
	
	return $resultado;	
} 

function readFunctionPostgres($function, $parameters = null){
    $_parameters = '';
    if (count($parameters) > 0) {
      $_parameters = implode("','", $parameters);
      $_parameters = "'" . $_parameters . "',";
    }
    $data = DB::select("BEGIN;");
    $cad = "select " . $function . "(" . $_parameters . "'ref_cursor');";
    $data = DB::select($cad);
    $cad = "FETCH ALL IN ref_cursor;";
    $data = DB::select($cad);
    DB::select("COMMIT;");
    return $data;
    
}
	 
?>