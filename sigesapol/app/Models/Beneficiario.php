<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use SoapClient;
use Carbon\Carbon;

class Beneficiario extends Model
{
  protected $guarded = ['id'];

  public function ShowAll()
  {
    $cad = "select T1.*,T2.nombre farmacia,T3.nombre consultorio,T4.nombre establecimiento 
from users T1
left join establecimientos T4 on T1.id_establecimiento=T4.id
left join farmacias T2 on T1.id_farmacia=T2.id
left join consultorios T3 on T1.id_consultorio=T3.id
order by paterno";
    $data = DB::select($cad);
    return $data;
  }

  public function GetByBeneficiario($id)
  {

    $beneficiario = DB::table('beneficiarios3')
      ->select('beneficiarios3.*')
      ->where('dni_titular', $id)
      ->first();

    return $beneficiario;
  }

  public function buscar_personal_dni($nro_doc, $tipo_doc)
  {

    $location_URL = 'https://sigcp.policia.gob.pe:7071/TitularFamiliarWS.svc';
    $wsdl = 'https://sigcp.policia.gob.pe:7071/TitularFamiliarWS.svc?singleWsdl';

    $client = new SoapClient($wsdl, array(
      'location' => $location_URL,
      'uri'      => "",
      'trace'    => 1,
    ));

    $busca_datos = $client->BuscarTitularFamiliar(['TipoBusqueda' => 1, 'Documento' => $nro_doc, 'Usuario' => 'DirSaPol', 'Clave' => '6hHPb', 'Sistema' => 'SISTEMA DE GESTION DE FARMACIA', 'Operador' => '31081306']);

    //dd($busca_datos);
    //$beneficiario->apepatafiliado=$busca_datos['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['PATERNO'];
    $json = json_encode($busca_datos);
    $beneficiario_encontrado = json_decode($json, TRUE);

    //dd($beneficiario_encontrado);

    $beneficiario = new Beneficiario;
    $beneficiario->sw = 0;

    $dni_beneficiario = $nro_doc;
    $cont = 0;
    $ncont_titular = 0;
    $ncont_titular_2 = 0;
    $ncont_familiar = 0;


    if ($beneficiario_encontrado['BuscarTitularFamiliarResult'] != null) {
      if (count($beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar']) < 13) {
        $ncont_titular = count($beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar']);
      }
      if ($ncont_titular > 1) {
        $beneficiario->nompaisdelafiliado = 'PER';
        $beneficiario->nomtipdocafiliado = 'DNI';
        $beneficiario->nrodocafiliado = $dni_beneficiario;
        $beneficiario->apepatafiliado = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['PATERNO'];
        $beneficiario->apematafiliado = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['MATERNO'];
        $beneficiario->apecasafiliado = '';
        $beneficiario->nomafiliado = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['NOMBRES'];
        $beneficiario->fecnacafiliado = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['NACIMIENTO'];
        $originalDate = $beneficiario->fecnacafiliado;
        $fech_nac = explode("/", $originalDate);
        $nacimiento = $fech_nac[2] . '-' . $fech_nac[1] . '-' . $fech_nac[0];
        $dbDate = Carbon::parse($nacimiento);
        $diffYears = Carbon::now()->diffInYears($dbDate);
        $beneficiario->edadafiliado = $diffYears;
        $beneficiario->nomsexo = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['SEXO'];
        if ($beneficiario->nomsexo == 'FEMENINO')
          $beneficiario->nomsexo = 'F';
        else
          $beneficiario->nomsexo = 'M';
        $beneficiario->estado = 'ACTIVO';
        $beneficiario->parentesco = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TIPO']; //ver tipo
        $beneficiario->nomtipdoctitular = 'DNI';
        $beneficiario->nrodoctitular = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['DNI'];
        $beneficiario->apepattitular = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['PATERNO'];
        $beneficiario->apemattitular = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['MATERNO'];
        $beneficiario->apecastitular = '';
        $beneficiario->nomtitular = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['NOMBRES'];
        $beneficiario->cip = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['CARNE'];
        $beneficiario->ubigeo = '';
        $beneficiario->grado = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['GRADO'];
        $beneficiario->situacion = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['SITUACION'];
        $beneficiario->caducidad = '';
        $beneficiario->discapacidad = 0;
        $beneficiario->otroseguro = 'FONAFUN';
        /****************************************************************/
        $beneficiario->civil = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['EST_CIVIL'];
        $beneficiario->fing = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['FING'];
        $beneficiario->fret = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['FRET'];
        $beneficiario->id = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['ID'];
        $beneficiario->maspe_codunilr = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['MASPE_CODUNILR'];
        $beneficiario->teciv_cod = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TECIV_COD'];
        $beneficiario->tgrad_cod = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TGRAD_COD'];
        $beneficiario->tsangre_cod = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TSANGRE_COD'];
        $beneficiario->tsangre_des = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TSANGRE_DES'];
        $beneficiario->tsite_cod = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TSITE_COD'];
        $beneficiario->tsite_des = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TSITE_DES'];
        $beneficiario->unidad = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['UNIDAD'];
        $beneficiario->sw = 1;
      } else {
        $beneficiario->nompaisdelafiliado = 'PER';
        $beneficiario->nomtipdocafiliado = 'DNI';
        $beneficiario->nrodocafiliado = $dni_beneficiario;
        foreach ($beneficiario_encontrado as $beneficiario1) {
          $beneficiario->apepatafiliado = $beneficiario1['TitularFamiliar']['PATERNO'];
          $beneficiario->apematafiliado = $beneficiario1['TitularFamiliar']['MATERNO'];
          $beneficiario->apecasafiliado = '';
          $beneficiario->nomafiliado = $beneficiario1['TitularFamiliar']['NOMBRES'];
          $beneficiario->fecnacafiliado = $beneficiario1['TitularFamiliar']['NACIMIENTO'];
          $originalDate = $beneficiario->fecnacafiliado;
          $fech_nac = explode("/", $originalDate);
          //$nacimiento=$fech_nac[2].'-'.$fech_nac[1].'-'.$fech_nac[0];   
          $nacimiento = '';
          if (count($fech_nac) == 3) {
            $nacimiento = $fech_nac[2] . '-' . $fech_nac[1] . '-' . $fech_nac[0];
          }
          $dbDate = Carbon::parse($nacimiento);
          $diffYears = Carbon::now()->diffInYears($dbDate);
          $beneficiario->edadafiliado = $diffYears;
          $beneficiario->nomsexo = $beneficiario1['TitularFamiliar']['SEXO'];
          if ($beneficiario->nomsexo == 'FEMENINO')
            $beneficiario->nomsexo = 'F';
          else
            $beneficiario->nomsexo = 'M';
          $beneficiario->estado = 'ACTIVO';
          $beneficiario->parentesco = $beneficiario1['TitularFamiliar']['TIPO'];
          $beneficiario->nomtipdoctitular = 'DNI';
          $beneficiario->nrodoctitular = $beneficiario1['TitularFamiliar']['DNI'];
          $beneficiario->apepattitular = $beneficiario1['TitularFamiliar']['PATERNO'];
          $beneficiario->apemattitular = $beneficiario1['TitularFamiliar']['MATERNO'];
          $beneficiario->apecastitular = '';
          $beneficiario->nomtitular = $beneficiario1['TitularFamiliar']['NOMBRES'];
          $beneficiario->cip = $beneficiario1['TitularFamiliar']['CARNE'];
          $beneficiario->ubigeo = '';
          $beneficiario->grado = $beneficiario1['TitularFamiliar']['GRADO'];
          $beneficiario->situacion = $beneficiario1['TitularFamiliar']['SITUACION'];
          $beneficiario->caducidad = '';
          $beneficiario->discapacidad = 0;
          $beneficiario->otroseguro = 'FONAFUN';
          /*******************************************************************************/
          $beneficiario->civil = $beneficiario1['TitularFamiliar']['EST_CIVIL'];
          $beneficiario->fing = $beneficiario1['TitularFamiliar']['FING'];
          $beneficiario->fret = $beneficiario1['TitularFamiliar']['FRET'];
          $beneficiario->id = $beneficiario1['TitularFamiliar']['ID'];
          $beneficiario->maspe_codunilr = $beneficiario1['TitularFamiliar']['MASPE_CODUNILR'];
          $beneficiario->teciv_cod = $beneficiario1['TitularFamiliar']['TECIV_COD'];
          $beneficiario->tgrad_cod = $beneficiario1['TitularFamiliar']['TGRAD_COD'];
          $beneficiario->tsangre_cod = $beneficiario1['TitularFamiliar']['TSANGRE_COD'];
          $beneficiario->tsangre_des = $beneficiario1['TitularFamiliar']['TSANGRE_DES'];
          $beneficiario->tsite_cod = $beneficiario1['TitularFamiliar']['TSITE_COD'];
          $beneficiario->tsite_des = $beneficiario1['TitularFamiliar']['TSITE_DES'];
          $beneficiario->tsitua_cod = $beneficiario1['TitularFamiliar']['TSITUA_COD'];
          $beneficiario->unidad = $beneficiario1['TitularFamiliar']['UNIDAD'];
          $beneficiario->sw = 1;
        }
      }
    } else {
      $busca_datos = $client->BuscarTitularFamiliar(['TipoBusqueda' => 3, 'Documento' => $nro_doc, 'Usuario' => 'DirSaPol', 'Clave' => '6hHPb', 'Sistema' => 'SISTEMA DE GESTION DE FARMACIA', 'Operador' => '31081306']);
      $json = json_encode($busca_datos);
      $beneficiario_encontrado = json_decode($json, TRUE);
      if ($beneficiario_encontrado['BuscarTitularFamiliarResult'] != null) {
        if (count($beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar']) < 13) {
          $ncont_familiar = count($beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar']);
        }
        if ($ncont_familiar > 1) {

          $beneficiario->nompaisdelafiliado = 'PER';
          $beneficiario->nomtipdocafiliado = 'DNI';
          $beneficiario->nrodocafiliado = $dni_beneficiario;
          $beneficiario->apepatafiliado = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['PATERNO'];
          $beneficiario->apematafiliado = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['MATERNO'];
          $beneficiario->apecasafiliado = '';
          $beneficiario->nomafiliado = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['NOMBRES'];
          $beneficiario->fecnacafiliado = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['NACIMIENTO'];
          $originalDate = $beneficiario->fecnacafiliado;
          $fech_nac = explode("/", $originalDate);
          $nacimiento = $fech_nac[2] . '-' . $fech_nac[1] . '-' . $fech_nac[0];
          $dbDate = Carbon::parse($nacimiento);
          $diffYears = Carbon::now()->diffInYears($dbDate);
          $beneficiario->edadafiliado = $diffYears;
          $beneficiario->nomsexo = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['SEXO'];
          if ($beneficiario->nomsexo == 'FEMENINO')
            $beneficiario->nomsexo = 'F';
          else
            $beneficiario->nomsexo = 'M';
          $beneficiario->estado = 'ACTIVO';
          $beneficiario->parentesco = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TIPO'];
          $beneficiario->nomtipdoctitular = 'DNI';
          $beneficiario->cip = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['CARNE'];

          $busca_datos_titular = $client->BuscarTitularFamiliar(['TipoBusqueda' => 2, 'Documento' => $beneficiario->cip, 'Usuario' => 'DirSaPol', 'Clave' => '6hHPb', 'Sistema' => 'SISTEMA DE GESTION DE FARMACIA', 'Operador' => '31081306']);

          $json_titular = json_encode($busca_datos_titular);
          $beneficiario_encontrado_titular = json_decode($json_titular, TRUE);
          //$beneficiario["dni_titular"]='0';
          $beneficiario->sw = 1;

          if (count($beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar']) < 13) {
            $ncont_titular_2 = count($beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar']);
          }

          if ($ncont_titular_2 > 1) {

            $beneficiario->nrodoctitular = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['DNI'];
            $beneficiario->apepattitular = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['PATERNO'];
            $beneficiario->apemattitular = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['MATERNO'];
            $beneficiario->apecastitular = '';
            $beneficiario->nomtitular = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['NOMBRES'];
            $beneficiario->cip = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['CARNE'];
            $beneficiario->ubigeo = '';
            $beneficiario->grado = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['GRADO'];
            $beneficiario->situacion = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['SITUACION'];
            $beneficiario->caducidad = '';
            $beneficiario->discapacidad = 0;
            $beneficiario->otroseguro = 'FONAFUN';
            /*******************************************************************************/
            $beneficiario->civil = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['EST_CIVIL'];
            $beneficiario->fing = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['FING'];
            $beneficiario->fret = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['FRET'];
            $beneficiario->id = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['ID'];
            $beneficiario->maspe_codunilr = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['MASPE_CODUNILR'];
            $beneficiario->teciv_cod = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TECIV_COD'];
            $beneficiario->tgrad_cod = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TGRAD_COD'];
            $beneficiario->tsangre_cod = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TSANGRE_COD'];
            $beneficiario->tsangre_des = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TSANGRE_DES'];
            $beneficiario->tsite_cod = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TSITE_COD'];
            $beneficiario->tsite_des = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TSITE_DES'];
            $beneficiario->tsitua_cod = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TSITUA_COD'];
            $beneficiario->unidad = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['UNIDAD'];
          } else {
            //$beneficiario->nrodoctitular=$dni_beneficiario;
            foreach ($beneficiario_encontrado_titular as $beneficiario1) {
              $beneficiario->nrodoctitular = $beneficiario1['TitularFamiliar']['DNI'];
              $beneficiario->apepattitular = $beneficiario1['TitularFamiliar']['PATERNO'];
              $beneficiario->apemattitular = $beneficiario1['TitularFamiliar']['MATERNO'];
              $beneficiario->apecastitular = '';
              $beneficiario->nomtitular = $beneficiario1['TitularFamiliar']['NOMBRES'];
              $beneficiario->cip = $beneficiario1['TitularFamiliar']['CARNE'];
              $beneficiario->ubigeo = '';
              $beneficiario->grado = $beneficiario1['TitularFamiliar']['GRADO'];
              $beneficiario->situacion = $beneficiario1['TitularFamiliar']['SITUACION'];
              $beneficiario->caducidad = '';
              $beneficiario->discapacidad = 0;
              $beneficiario->otroseguro = 'FONAFUN';
              /****************************************************************************/
              $beneficiario->civil = $beneficiario1['TitularFamiliar']['EST_CIVIL'];
              $beneficiario->fing = $beneficiario1['TitularFamiliar']['FING'];
              $beneficiario->fret = $beneficiario1['TitularFamiliar']['FRET'];
              $beneficiario->id = $beneficiario1['TitularFamiliar']['ID'];
              $beneficiario->maspe_codunilr = $beneficiario1['TitularFamiliar']['MASPE_CODUNILR'];
              $beneficiario->teciv_cod = $beneficiario1['TitularFamiliar']['TECIV_COD'];
              $beneficiario->tgrad_cod = $beneficiario1['TitularFamiliar']['TGRAD_COD'];
              $beneficiario->tsangre_cod = $beneficiario1['TitularFamiliar']['TSANGRE_COD'];
              $beneficiario->tsangre_des = $beneficiario1['TitularFamiliar']['TSANGRE_DES'];
              $beneficiario->tsite_cod = $beneficiario1['TitularFamiliar']['TSITE_COD'];
              $beneficiario->tsite_des = $beneficiario1['TitularFamiliar']['TSITE_DES'];
              $beneficiario->tsitua_cod = $beneficiario1['TitularFamiliar']['TSITUA_COD'];
              $beneficiario->unidad = $beneficiario1['TitularFamiliar']['UNIDAD'];
            }
          }
        } else {
          $beneficiario->nompaisdelafiliado = 'PER';
          $beneficiario->nomtipdocafiliado = 'DNI';
          $beneficiario->nrodocafiliado = $dni_beneficiario;
          $beneficiario->apepatafiliado = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar']['PATERNO'];
          $beneficiario->apematafiliado = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar']['MATERNO'];
          $beneficiario->apecasafiliado = '';
          $beneficiario->nomafiliado = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar']['NOMBRES'];
          $beneficiario->apematafiliado = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar']['MATERNO'];
          $beneficiario->fecnacafiliado = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar']['NACIMIENTO'];
          $originalDate = $beneficiario->fecnacafiliado;
          $fech_nac = explode("/", $originalDate);
          $nacimiento = $fech_nac[2] . '-' . $fech_nac[1] . '-' . $fech_nac[0];
          $dbDate = Carbon::parse($nacimiento);
          $diffYears = Carbon::now()->diffInYears($dbDate);
          $beneficiario->edadafiliado = $diffYears;
          $beneficiario->nomsexo = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar']['SEXO'];
          if ($beneficiario->nomsexo == 'FEMENINO')
            $beneficiario->nomsexo = 'F';
          else
            $beneficiario->nomsexo = 'M';
          $beneficiario->estado = 'ACTIVO';
          $beneficiario->parentesco = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar']['TIPO'];
          $beneficiario->nomtipdoctitular = 'DNI';
          $beneficiario["cip_titular"] = $beneficiario_encontrado['BuscarTitularFamiliarResult']['TitularFamiliar']['CARNE'];

          $busca_datos_titular = $client->BuscarTitularFamiliar(['TipoBusqueda' => 2, 'Documento' => $beneficiario["cip_titular"], 'Usuario' => 'DirSaPol', 'Clave' => '6hHPb', 'Sistema' => 'SISTEMA DE GESTION DE FARMACIA', 'Operador' => '31081306']);

          $json_titular = json_encode($busca_datos_titular);
          $beneficiario_encontrado_titular = json_decode($json_titular, TRUE);
          $beneficiario->sw = 1;

          if (count($beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar']) < 13) {
            $ncont_titular_2 = count($beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar']);
          }
          if ($ncont_titular_2 > 1) {

            $beneficiario->nrodoctitular = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['DNI'];
            $beneficiario->apepattitular = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['PATERNO'];
            $beneficiario->apemattitular = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['MATERNO'];
            $beneficiario->apecastitular = '';
            $beneficiario->nomtitular = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['NOMBRES'];
            $beneficiario->cip = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['CARNE'];
            $beneficiario->ubigeo = '';
            $beneficiario->grado = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['GRADO'];
            $beneficiario->situacion = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['SITUACION'];
            $beneficiario->caducidad = '';
            $beneficiario->discapacidad = 0;
            $beneficiario->otroseguro = 'FONAFUN';
            /**********************************************************************************/
            $beneficiario->civil = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['EST_CIVIL'];
            $beneficiario->fing = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['FING'];
            $beneficiario->fret = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['FRET'];
            $beneficiario->id = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['ID'];
            $beneficiario->maspe_codunilr = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['MASPE_CODUNILR'];
            $beneficiario->teciv_cod = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TECIV_COD'];
            $beneficiario->tgrad_cod = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TGRAD_COD'];
            $beneficiario->tsangre_cod = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TSANGRE_COD'];
            $beneficiario->tsangre_des = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TSANGRE_DES'];
            $beneficiario->tsite_cod = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TSITE_COD'];
            $beneficiario->tsite_des = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TSITE_DES'];
            $beneficiario->tsitua_cod = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['TSITUA_COD'];
            $beneficiario->unidad = $beneficiario_encontrado_titular['BuscarTitularFamiliarResult']['TitularFamiliar'][0]['UNIDAD'];
          } else {

            foreach ($beneficiario_encontrado_titular as $beneficiario1) {
              $beneficiario->nrodoctitular = $beneficiario1['TitularFamiliar']['DNI'];
              $beneficiario->apepattitular = $beneficiario1['TitularFamiliar']['PATERNO'];
              $beneficiario->apemattitular = $beneficiario1['TitularFamiliar']['MATERNO'];
              $beneficiario->apecastitular = '';
              $beneficiario->nomtitular = $beneficiario1['TitularFamiliar']['NOMBRES'];
              $beneficiario->cip = $beneficiario1['TitularFamiliar']['CARNE'];
              $beneficiario->ubigeo = '';
              $beneficiario->grado = $beneficiario1['TitularFamiliar']['GRADO'];
              $beneficiario->situacion = $beneficiario1['TitularFamiliar']['SITUACION'];
              $beneficiario->caducidad = '';
              $beneficiario->discapacidad = 0;
              $beneficiario->otroseguro = 'FONAFUN';
              /**********************************************************************************/
              $beneficiario->civil = $beneficiario1['TitularFamiliar']['EST_CIVIL'];
              $beneficiario->fing = $beneficiario1['TitularFamiliar']['FING'];
              $beneficiario->fret = $beneficiario1['TitularFamiliar']['FRET'];
              $beneficiario->id = $beneficiario1['TitularFamiliar']['ID'];
              $beneficiario->maspe_codunilr = $beneficiario1['TitularFamiliar']['MASPE_CODUNILR'];
              $beneficiario->teciv_cod = $beneficiario1['TitularFamiliar']['TECIV_COD'];
              $beneficiario->tgrad_cod = $beneficiario1['TitularFamiliar']['TGRAD_COD'];
              $beneficiario->tsangre_cod = $beneficiario1['TitularFamiliar']['TSANGRE_COD'];
              $beneficiario->tsangre_des = $beneficiario1['TitularFamiliar']['TSANGRE_DES'];
              $beneficiario->tsite_cod = $beneficiario1['TitularFamiliar']['TSITE_COD'];
              $beneficiario->tsite_des = $beneficiario1['TitularFamiliar']['TSITE_DES'];
              $beneficiario->tsitua_cod = $beneficiario1['TitularFamiliar']['TSITUA_COD'];
              $beneficiario->unidad = $beneficiario1['TitularFamiliar']['UNIDAD'];
            }
          }
        }
      }
    }

    //$beneficiario=json_encode($beneficiario);
    //dd($beneficiario);

    return $beneficiario;
  }
}
