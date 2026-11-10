<?php
namespace App\Ws;
use SoapClient;
class Saludpol
{
  public function BuscarTitularFamiliar($tipo_doc, $nro_doc, $fecha_validacion='') {
    $s=new SoapClient(env('WI_SALUDPOL_URL')."/ws/WS_AfiliadoSP_Serv.php?wsdl",array('login'=>env('WI_SALUDPOL_USERNAME'),'password'=>env('WI_SALUDPOL_PASSWORD'))); $b=$s->getAseguradoValidate($tipo_doc,$nro_doc,$fecha_validacion); foreach($b as $k=>$v) if(!is_object($v)) $b->$k=utf8_decode($v); return $b;
  }
  public function BuscarTitularFamiliar_paciente($id_tipo_doc_ident, $nro_doc_ident, $fecha_validacion='') {
    $s=new SoapClient(env('WI_SALUDPOL_URL')."/ws/WS_AfiliadoSP_Serv.php?wsdl",array('login'=>env('WI_SALUDPOL_USERNAME'),'password'=>env('WI_SALUDPOL_PASSWORD'))); $b=$s->getAseguradoValidate($id_tipo_doc_ident,$nro_doc_ident,$fecha_validacion); foreach($b as $k=>$v) if(!is_object($v)) $b->$k=utf8_decode($v); return $b;
  }
  public function BuscarMedico($nro_doc) {
    $s=new SoapClient(env('WI_SALUDPOL_URL')."/ws/ws_AfiliadoSALUDPOL.php?wsdl",array('login'=>env('WI_SALUDPOL_USERNAME1'),'password'=>env('WI_SALUDPOL_PASSWORD1'),'trace'=>1,"encoding"=>"UTF-8")); return $s->get_busafi_activo($nro_doc);
  }
}
