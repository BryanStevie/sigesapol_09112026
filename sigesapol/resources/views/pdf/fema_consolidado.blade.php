<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>
          Ficha Consolidado
  </title>
  <link rel="stylesheet" type="text/css" href="assets/css/pdf.css" />
  <style>
    .td_left {
    text-align: left !important;
  }

  .td_ancho_n {
    width: 4%;
    text-align: center
  }
  .page_break {
  page-break-before: always;
}
  .cantidad {
    width: 11%;
  }

  .td_ancho_espacios {
    width: 5%;
  }

  .td_ancho_codigo {
    width: 10%;
  }

  .td_ancho_especialidad {
    width: 25%;
  }

  td {
    padding-left: 2px !important;
  }

  table {
    font-size: 11px;
    font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
  }

  .ancho_nro {
    width: 5%;
  }

  .ancho_producto {
    width: 45%;
  }

  .titulos {
    padding: 0px 10px;
  }

  h2 {
    font-size: 15px !important
  }

  .titulo_principal {
    width: 60%;
  }

  .logo {
    width: 15%;
  }

  .grado {
    width: 14%;
  }

  .dni {
    width: 8%;
  }

  .rectangulo {
    padding: 3px
  }

  footer { 
    border: 0px;
    margin-left: 10px;
    font-size: 7px !important;
    font-family: Arial, Helvetica, sans-serif
  }
  </style>
</head>

<body style="font-size: 12px; font-family: arial;padding:-10px !important;background-color:white !important">
 
  <table style="margin:0px;padding:0px;padding-bottom:10px;">
    <tbody>
      <tr>
        <td colspan="2" class="" width="10%">
          <img src="images/logo_sanidad.png" width="40rem"/>
        </td>
        <td colspan="2" class="" width="80%">
          <span style="display: block; font-size: 1.3rem; text-align: left; margin: 0; font-weight: bold; text-transform: uppercase;color: red;">
          {{$localescierre->nombre}}</span>
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.1rem">FEMA </span>
        </td>
      </tr>
    </tbody>
  </table>

  <table style="margin:0px;padding:0px;padding-bottom:-25px;">
    <tbody>
      <tr> 
          <td colspan="2" width="10%" >
              <span style="margin: 0; font-size: 0.9rem; font-weight: bold; font-size: 17px !important;">
             FEMA - FICHA CONSOLIDADO <br>
              </span>
              </span><br>
          </td> 
      </tr> 
    </tbody>
  </table>

  <table style="margin:0px;padding-top:30px;">
    <tbody>
        <tr>
           
            <td colspan="3" width="40%" style="vertical-align:bottom;"> 
            </td>
            <br>
        </tr>
    </tbody>
  </table>
  <hr/>
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">I.- PERSONAL ATENDIDO: {{$personal->nombre}} {{$personal->paterno}} {{$personal->materno}}</span>
      </td>
      <td width="20%">
        <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem; text-align: center; ">DNI : <b>{{$personal->nro_doc_ident}}</b></span>
      </td>
   
    </tr>
  </table>
  <table  style="margin:0px;padding:0px;" >
    <tr>
  
      <td width="35%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem; text-align: center">GRADO:  <b>{{$personal->grado}}</b></span>
      </td>
      <td width="20%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: center">CIP:  <b>{{$personal->nro_doc_ident}}</b></span>
      </td>
      <td width="20%">
        <span style="display: block; margin: 0; font-size: 0.9rem;  padding-top: 0.3rem;text-align: center">EDAD:  <b>{{$personal->edad}}</b></span>
      </td>
      <td width="10%">
        <span style="display: block; margin: 0; font-size: 0.9rem; padding-top: 0.3rem;text-align: center">SEXO: <b>{{$personal->sexo}} </b></span>
      </td>
    </tr>
  </table>

  <hr/>



  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="70%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">II. APERTURA </span>
      </td>
    </tr>
  </table>
  <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="40%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> TIPO DE FEMA : <b>{{$procesos->nombre}}</b></span>
        </td>
        <td width="40%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: right"> PROCESO DE FEMA : <b>{{$subprocesos->nombre}}</b></span>
        </td>
      </tr>
    </table> 

    <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="68%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> ENCARGADO DE LA APERTURA: <b>{{$personalapertura->name}}</b> <b>{{$personalapertura->paterno}}</b> <b>{{$personalapertura->materno}}</b></span>
        </td>
        <td width="32%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> FECHA DE APERTURA: <b>
          {{$personal->created_at}}
          </b></span>
        </td>
      </tr>
    </table> 
    <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="50%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> LOCAL DE APERTURA: <b>{{$locales->nombre}}</b></span>
        </td>
      </tr>
    </table>
    <hr/>
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">III.- DATOS DE  TRIAJE</span>
      </td> 
    </tr>
  </table>
  <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="68%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> ENCARGADO DE LA APERTURA: <b>{{$personaltriaje->name}}</b> <b>{{$personaltriaje->paterno}}</b> <b>{{$personaltriaje->materno}}</b></span>
        </td>
        <td width="32%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> FECHA DE TRIAJE: <b>
          {{$triaje->created_at}}
          </b></span>
        </td>
      </tr>
    </table>
    
    <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="25%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> TEMPERATURA: <b>{{$triaje->temperatura}}</b></span>
        </td>
        <td width="25%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> TALLA: <b>{{$triaje->talla_cm}}</b></span>
        </td>
        <td width="25%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> PESO: <b>{{$triaje->peso_kg}}</b></span>
        </td>
        <td width="25%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> PRESION ARTIAL: <b>{{$triaje->presion_arterial_x}}</b>/<b>{{$triaje->presion_arterial_y}}</b></span>
        </td>
      </tr>
    </table>

    <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="25%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> FRECUENCIA RESPIRATORIA: <b>{{$triaje->frecuencia_respiratoria}}</b></span>
        </td>
        <td width="25%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> FRECUENCIA CARDIACA: <b>{{$triaje->frecuencia_cardiaca}}</b></span>
        </td>
        <td width="25%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> SA02: <b>{{$triaje->sa02_porcentaje}}</b></span>
        </td>
        <td width="25%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> OBSERVACION: <b>{{$triaje->observacion}}</b></span>
        </td>
      </tr>
    </table>

    <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="50%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> LOCAL DE TRIAJE: <b>{{$locales->nombre}}</b></span>
        </td>
      </tr>
    </table>
  <hr/>
  <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">IV.- DATOS DEL EXAMEN ODONTOLOGIA</span>
      </td> 
    </tr>
  </table>
  <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="68%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> ENCARGADO DEL EXAMEN: <b>{{$examen1->name}}</b> <b>{{$examen1->paterno}}</b> <b>{{$examen1->materno}}</b></span>
        </td>
        <td width="32%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> FECHA DEL EXAMEN: <b>
          {{$examen1->fecha}}
          </b></span>
        </td>
      </tr>
    </table>
    
    <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="33%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> CAPACIDAD MASTICATORIA: <b>{{$examen1->capacidad_masticatoria}}</b></span>
        </td>
        <td width="33%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> CODIGO ODONTOGRAMA: <b>{{$examen1->cod_odontograma}}</b></span>
        </td>
        <td width="33%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> DESCRIPCION ODONTOGRAMA: <b>{{$examen1->descripcion_odontograma}}</b></span>
        </td>
      </tr>
    </table>

    <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="50%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> LOCAL DEL EXAMEN: <b><?php if(isset($examen1->nombre_establecimiento)):echo $examen1->nombre_establecimiento; else: echo $locales->nombre; endif;?></b></span>
        </td>
      </tr>
    </table>

    <hr/>

    <table  style="margin:0px;padding:0px;" >
    <tr>
      <td width="50%" style="vertical-align:top;">
        <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;"> V.- DATOS DEL EXAMEN CLINICO GENERAL</span>
      </td> 
    </tr>
  </table>
   <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="68%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> ENCARGADO DEL EXAMEN: <b>{{$examen3->name}}</b> <b>{{$examen3->paterno}}</b> <b>{{$examen3->materno}}</b></span>
        </td>
        <td width="32%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> FECHA DEL EXAMEN: <b>
          {{$examen3->fecha}}
          </b></span>
        </td>
      </tr>
    </table>
    <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="50%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> ANTECEDENTES: <b>{{$examen3->antecedentes}}</b></span>
        </td>
        <td width="50%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> EXAMEN CLINICO : <b>{{$examen3->examen_clinico}}</b></span>
        </td>

      </tr>
    </table>

    <table  style="margin:0px;padding:0px;" >
      <tr>
        <td width="50%" style="vertical-align:top;">
          <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> LOCAL DEL EXAMEN: <b><?php if(isset($examen3->nombre_establecimiento)):echo $examen3->nombre_establecimiento; else: echo $locales->nombre; endif;?></b></span>
        </td>
      </tr>
    </table>

    <hr/>

<table  style="margin:0px;padding:0px;" >
<tr>
  <td width="50%" style="vertical-align:top;">
    <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;"> VI.- DATOS DEL EXAMEN PSICOLOGICO</span>
  </td> 
</tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="68%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> ENCARGADO DEL EXAMEN: <b>{{$examen4->name}}</b> <b>{{$examen4->paterno}}</b> <b>{{$examen4->materno}}</b></span>
    </td>
    <td width="32%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> FECHA DEL EXAMEN: <b>
      {{$examen4->fecha}}
      </b></span>
    </td>
  </tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="33%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> AREA EMOCIONAL: <b>{{$examen4->area_emocional}}</b></span>
    </td>
    <td width="33%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> AREA COGNITIVA : <b>{{$examen4->area_cognitiva}}</b></span>
    </td>
    <td width="33%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> DERIVACION : <b> <?php if($examen4->derivacion == "0"): echo "NO" ;else: echo "SI" ;endif;?></b></span>
    </td>
  </tr>
</table>

<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> LOCAL DEL EXAMEN: <b><?php if(isset($examen4->nombre_establecimiento)):echo $examen4->nombre_establecimiento; else: echo $locales->nombre; endif;?></b></span>
    </td>
  </tr>
</table>

<hr>

<table  style="margin:0px;padding:0px;" >
<tr>
  <td width="50%" style="vertical-align:top;">
    <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;"> VII.- DATOS DEL EXAMEN OFTALMOLOGIA</span>
  </td> 
</tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="68%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> ENCARGADO DEL EXAMEN: <b>{{$examen8->name}}</b> <b>{{$examen8->paterno}}</b> <b>{{$examen8->materno}}</b></span>
    </td>
    <td width="32%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> FECHA DEL EXAMEN: <b>
      {{$examen8->fecha}}
      </b></span>
    </td>
  </tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> VISION CERCANA OD: <b>{{$examen8->vision_cercana_od}}</b></span>
    </td>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> VISION CERCANA OD CORREGIDA : <b>{{$examen8->vision_cercana_od_corregida}}</b></span>
    </td>
    </tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> VISION CERCANA OI: <b>{{$examen8->vision_cercana_oi}}</b></span>
    </td>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> VISION CERCANA OI CORREGIDA : <b>{{$examen8->vision_cercana_oi_corregida}}</b></span>
    </td>
  </tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> VISION LEJANA OD: <b>{{$examen8->vision_cercana_od2}}</b></span>
    </td>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> VISION LEJANA OD CORREGIDA : <b>{{$examen8->vision_cercana_od_corregida2}}</b></span>
    </td>
    </tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> VISION LEJANA OI: <b>{{$examen8->vision_cercana_oi2}}</b></span>
    </td>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> VISION LEJANA OI CORREGIDA: <b>{{$examen8->vision_cercana_oi_corregida2}}</b></span>
    </td>
  </tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> TONOMETRIA: <b>{{$examen8->tonometria}}</b></span>
    </td>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> PUPILAS: <b>{{$examen8->pupilas}}</b></span>
    </td>
    </tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> MOTIL OCULAR  : <b>{{$examen8->motil_ocular}}</b></span>
    </td>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> FONDO OJO: <b>{{$examen8->fondo_ojo}}</b></span>
    </td>
    </tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> VISION COLORES: <b>{{$examen8->vision_colores}}</b></span>
    </td>
  </tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> LOCAL DEL EXAMEN: <b><?php if(isset($examen8->nombre_establecimiento)):echo $examen8->nombre_establecimiento; else: echo $locales->nombre; endif;?></span>
    </td>
  </tr>
</table>

<hr/>
@if($personal->sexo == "F")
<table  style="margin:0px;padding:0px;" >
<tr>
  <td width="50%" style="vertical-align:top;">
    <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;"> VIII.- DATOS DEL EXAMEN GINECOLOGICO</span>
  </td> 
</tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="68%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> ENCARGADO DEL EXAMEN: <b>{{$examen6->name}}</b> <b>{{$examen6->paterno}}</b> <b>{{$examen6->materno}}</b></span>
    </td>
    <td width="32%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> FECHA DEL EXAMEN: <b>
      {{$examen6->fecha}}
      </b></span>
    </td>
  </tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="20%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> G: <b>{{$examen6->g}}</b></span>
    </td>
    <td width="20%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> P: <b>{{$examen6->p}}</b></span>
    </td>
    <td width="20%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> MENARQUIA:<b>{{$examen6->menarquia}}</b></span>
    </td>
    <td width="20%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> MAMOGRAFIA: <b>{{$examen6->mamografia}}</b></span>
    </td>
    <td width="20%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> IRS:<b>{{$examen6->irs}}</b></span>
    </td>
  </tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="20%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> URS: <b>{{$examen6->urs}}</b></span>
    </td>
    <td width="20%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> FUR: <b>{{$examen6->fur}}</b></span>
    </td>
    <td width="20%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> ANDRIA:<b>{{$examen6->andria}}</b></span>
    </td>
    <td width="20%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> MAC: <b>{{$examen6->mac}}</b></span>
    </td>
    <td width="20%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> GESTANTE:<b>{{$examen6->gestante}}</b></span>
    </td>
  </tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="20%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> PAP: <b>{{$examen6->examen_pap}}</b></span>
    </td>
    <td width="26%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> FEC. EXAM. PAP: <b>{{$examen6->fecha_examen_pap}}</b></span>
    </td>
    <td width="18%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> RES. EXAM. PAP:<b>{{$examen6->resultado_examen_pap}}</b></span>
    </td>
    <td width="18%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> PRUEBA IVA: <b>{{$examen6->prueba_iva}}</b></span>
    </td>
    <td width="18%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> RES. IVA:<b>{{$examen6->resultado_iva}}</b></span>
    </td>
  </tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> ANTECEDENTES PERSONALES: <b>{{$examen6->antecedentes_personales}}</b></span>
    </td>
  </tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> LOCAL DEL EXAMEN: <b><?php if(isset($examen6->nombre_establecimiento)):echo $examen6->nombre_establecimiento; else: echo $locales->nombre; endif;?></b></span>
    </td>
  </tr>
</table>
<hr/>
@else
<table  style="margin:0px;padding:0px;" >
<tr>
  <td width="50%" style="vertical-align:top;">
    <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;"> VIII.- DATOS DEL EXAMEN GINECOLOGICO</span>
  </td> 
</tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="32%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"><b> NO AMERITA EL EXAMEN </b></span>
    </td>
  </tr>
</table>
<hr/>
@endif


<table  style="margin:0px;padding:0px;" >
<tr>
  <td width="50%" style="vertical-align:top;">
    <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;"> IX.- DATOS DEL EXAMEN OTORRINOLARINGOLOGIA</span>
  </td> 
</tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="68%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> ENCARGADO DEL EXAMEN: <b>{{$examen9->name}}</b> <b>{{$examen9->paterno}}</b> <b>{{$examen9->materno}}</b></span>
    </td>
    <td width="32%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> FECHA DEL EXAMEN: <b>
      {{$examen9->fecha}}
      </b></span>
    </td>
  </tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="25%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> AGUDEZA AUDITIVA OD: <b>{{$examen9->agudeza_auditiva_od}}</b></span>
    </td>
    <td width="25%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> AGUDEZA AUDITIVA OD SUFICIENTE: <b>{{$examen9->agudeza_auditiva_od_suficiente}}</b></span>
    </td>
    <td width="25%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> AGUDEZA AUDITIVA OI: <b>{{$examen9->agudeza_auditiva_oi}}</b></span>
    </td>
    <td width="25%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> AGUDEZA AUDITIVA OI SUFICIENTE: <b>{{$examen9->agudeza_auditiva_oi_suficiente}}</b></span>
    </td>
  </tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> EXAMEN NARIZ GARGANTA : <b>{{$examen9->examen_nariz_garganta}}</b></span>
    </td>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> MENBRANA TIMPANICA: <b>{{$examen9->membrana_timpanica}}</b></span>
    </td>
  </tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> LOCAL DEL EXAMEN: <b><?php if(isset($examen9->nombre_establecimiento)):echo $examen9->nombre_establecimiento; else: echo $locales->nombre; endif;?></b></span>
    </td>
  </tr>
</table>

@if($deri == "1")
  <hr>
<table  style="margin:0px;padding:0px;" >
<tr>
  <td width="50%" style="vertical-align:top;">
    <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;">X.- DATOS DE LA DERIVACION</span>
  </td> 
</tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="68%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> ENCARGADO DE LA DERIVACION: <b>{{$derivado->name}}</b> <b>{{$derivado->paterno}}</b> <b>{{$derivado->materno}}</b></span>
    </td>
    <td width="32%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> FECHA DEL CIERRE: <b>
      {{$derivado->derivado_at}}
      </b></span>
    </td>
  </tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> SERVICIO DERIVADO 1: <b>{{$derivado->consulto1}}</b></span>
    </td>
    @if($verttatu == "1")
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> SERVICIO DERIVADO 2: <b>{{$derivado->consulto2}}</b></span>
    </td>
    @endif
  </tr>
</table>
@endif


<hr>
<table  style="margin:0px;padding:0px;" >
<tr>
  <td width="50%" style="vertical-align:top;">
    <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; font-weight: bold; padding-top: 0.3rem;"><?php if($deri == "0"): echo "X" ;else: echo "XI" ;endif;?>.- DATOS DEL CIERRE</span>
  </td> 
</tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="68%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> ENCARGADO DEL CIERRE: <b>{{$personalcierre->name}}</b> <b>{{$personalcierre->paterno}}</b> <b>{{$personalcierre->materno}}</b></span>
    </td>
    <td width="32%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> FECHA DEL CIERRE: <b>
      {{$personal->cierre_updated_at}}
      </b></span>
    </td>
  </tr>
</table>
<table  style="margin:0px;padding:0px;" >
  <tr>
    <td width="50%" style="vertical-align:top;">
      <span style="display: block; font-size: 0.9rem; text-align: left; margin: 0; padding-top: 0.3rem;text-align: left"> LOCAL DEL EXAMEN: <b>{{$localescierre->nombre}}</b></span>
    </td>
  </tr>
</table>

<hr>
<table  style="margin:0px;padding:0px;" >
      <tr style="font-size: 6px !important;">
          <td style="text-align: left">  
      <span style="display: block; font-size: 0.9rem; text-align: right; margin: 0; padding-top: 0.3rem;text-align: right"> FECHA DE IMPRESION: <b>{{$fechaActual}} {{$horaActual}}</b></span>
          </td>   
      </tr>   
  </table>  
  <table  style="margin:0px;padding:0px;" >
      <tr style="font-size: 6px !important;">
      <td style="text-align: left">  
      <span style="display: block; font-size: 0.9rem; text-align: right; margin: 0; padding-top: 0.3rem;text-align: right"> ENCARGADO DE IMPRESION: <b>{{$personaimprimer->name}}</b> <b>{{$personaimprimer->paterno}}</b> <b>{{$personaimprimer->materno}}</b></span>
          </td>   
      </tr>   
  </table>  
</footer>  
  
</body>

</html>
