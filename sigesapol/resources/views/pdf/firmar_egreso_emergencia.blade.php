<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>ADMISION EMERGENCIA</title>
  <link rel="stylesheet" type="text/css" href="assets/css/pdf.css" />
  <style>
  .td_left {
    text-align: left !important;
  }

  .td_ancho_n {
    width: 4%;
    text-align: center
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
    margin-bottom: 0px;
    padding: 0px;
    position: fixed;
    height: 60px;
    line-height: 60px;
    text-align: left;
    border: 0px;
    margin-left: 10px;
    font-size: 7px !important;
    font-family: Arial, Helvetica, sans-serif
  }
  </style>
</head>

<body
  style="font-size: 12px; font-family: arial;border:1px solid #A4A4A4;padding:10px;background-color:white !important">

  <table style="margin:0px;padding:0px;padding-bottom:0px;">
    <tbody>
      <tr>
        <td colspan="2" width="70%" style="vertical-align:bottom;padding-bottom:30px">
          <?php echo $url_descarga?>
        </td>
        <td colspan="2" width="30%" style="margin-top:0px;padding-top:0px">
          <img src="data:image/png;base64,{!! base64_encode($codigo_qr) !!}">
        </td>
      </tr>
    </tbody>
  </table>

  <table style="margin:0px;padding:0px;padding-bottom:10px;">
    <tbody>
      <tr>
        <td colspan="2" class="td_left logo">
          <img src="images/logo_sanidad.png" width="60" />
        </td>
        <td colspan="2" width="90%" style="padding-right:80px;">
          <h2>
            EMERGENCIA MEDICA 
            <?php if($admision_emergencia->ruta_egreso==null):
                    echo "ADMISION - ";
                  else:
                    echo "EGRESO - ";
                  endif; 
             ?>Nº 
            <?php echo $admision_emergencia->id ?>
          </h2>
        </td>
      </tr>
    </tbody>
  </table>

  <table border="0px" style="background-color:white !important">
    <tr>
      <td class="titulos" width="5%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          EMERGENCIA
        </div>
      </td>
      <td class="grado titulos">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $admision_emergencia->establecimiento ?>
        </div>
      </td>
      <td class="grado titulos">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php
                  if($admision_emergencia->created_at <> ""){
                        $fecha_atencion = date("d-m-Y", strtotime($admision_emergencia->created_at));
                        echo $fecha_atencion;
                        } else {
                            echo "---";
                        }
          ?>
        </div>
      </td>
    </tr>
    <tr>
      <td class="td_center"><strong>TIPO DE ATENCIÓN</strong></td>
      <td class="td_center"><strong>ESTABLECIMIENTO</strong></td>
      <td class="td_center"><strong>FECHA DE ATENCION</strong></td>
    </tr>
  </table>

  <table border="0px" style="background-color:white !important">
    <tr>
      <td class="titulos" width="5%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $admision_emergencia->dni_beneficiario ?>
        </div>
      </td>
      <td class="grado titulos">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $admision_emergencia->paterno . " " . $admision_emergencia->materno . ", " . $admision_emergencia->nombre ?>
        </div>
      </td>
      <td class="titulos" width="4.5%" style="padding-right:0px">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $admision_emergencia->nro_historia."&nbsp;" ?>
        </div>
      </td>
    </tr>
    <tr>
      <td class="td_center"><strong><?php echo $admision_emergencia->tipo_doc_ident ?> DEL PACIENTE</strong></td>
      <td class="td_center"><strong>APELLIDOS Y NOMBRES COMPLETOS DEL PACIENTE</strong></td>
      <td class="td_center"><strong>HISTORIA CLINICA</strong></td>
    </tr>
  </table>


  <table border="0px" style="background-color:white !important">
    <tr>
      <td class="grado titulos">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $admision_emergencia->servicio ?>
        </div>
      </td>
      <td class="grado titulos">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $admision_emergencia->medico_nombre." ".$admision_emergencia->medico_paterno." ".$admision_emergencia->medico_materno ?>
        </div>
      </td>
    </tr>
    <tr>
      <td class="td_center"><strong>SERVICIO</strong></td>
      <td class="td_center"><strong>MEDICO</strong></td>
    </tr>
  </table>

  <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
        ALTA DE PACIENTE
      </td>
    </tr>
  </table>
  <table border="0px" style="background-color:white !important">
    <tr>
      <td class="grado titulos">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          {{$admision_emergencia->fecha_egreso}}
        </div>
      </td>
      <td class="grado titulos">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
         {{$admision_emergencia->hora_egreso}}
        </div>
      </td>
      <td class="grado titulos">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
         <?php 
              switch($admision_emergencia->id_tipo_egreso){
                case '1' : echo "ALTA"; break;
                case '2' : echo "HOSPITALIZACIÓN"; break;
                case '3' : echo "DEFUNCIÓN"; break;
                case '4' : echo "FUGADO"; break;
              }
          ?>
        </div>
      </td>
      <td class="grado titulos">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
         <?php 
              echo $admision_emergencia->id_condicion_alta;
              /*switch($admision_emergencia->id_condicion_alta){
                case '1' : echo "MEJORADO"; break;
                case '2' : echo "CURADO"; break;
                case '3' : echo "TRANSFERIDO"; break;
                case '4' : echo "FALLECIDO"; break;
                case '5' : echo "FUGADO"; break;
              }*/
          ?>
        </div>
      </td>
    </tr>
    <tr>
      <td class="td_center"><strong>FECHA EGRESO</strong></td>
      <td class="td_center"><strong>HORA EGRESO</strong></td>
      <td class="td_center"><strong>TIPO EGRESO</strong></td>
      <td class="td_center"><strong>CONDICION ALTA</strong></td>
    </tr>
  </table>

  
  @if($admision_emergencia->id_tipo_egreso=="1")
    <table border="0px" style="background-color:white !important">
      <tr>
        <td class="grado titulos">
          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
            <?php 
              switch($admision_emergencia->derivar){
                case 'SI' : echo "SI"; break;
                case 'NO' : echo "NO"; break;
              }
            ?>
          </div>
        </td>
        @if($admision_emergencia->derivar=="SI")
        <td class="grado titulos">
          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
            <?php if($consulta_externa!=NULL && $admision_emergencia->derivar=="SI"){ 
                  foreach($consulta_externa as $row):
                    if($row->id==$admision_emergencia->id_servicio_destino){
                      echo $row->nombre;
                    } 
                  endforeach; }
            ?> 
          </div>
        </td>
        @endif
      </tr>
      <tr>
        <td class="td_center"><strong>DERIVAR CONSULTA</strong></td>
        @if($admision_emergencia->derivar=="SI")
        <td class="td_center"><strong>SERVICIO</strong></td>
        @endif
      </tr>
    </table>
  @else
    @if($admision_emergencia->id_tipo_egreso=="2")
      <table border="0px" style="background-color:white !important">
        <tr>
          <td class="grado titulos">
            <div class="rectangulo" style="border: 1px solid #A4A4A4;">
              <?php
                  if($hospitalizacion!=NULL){ 
                    foreach($hospitalizacion as $row): 
                      if($row->id==$admision_emergencia->id_servicio_destino){ 
                        echo $row->nombre;
                      }
                    endforeach; 
                  }
                ?>
            </div>
          </td>
        </tr>
        <tr>
          <td class="td_center"><strong>SERVICIO DE HOSPITALIZACION</strong></td>
        </tr>
      </table>
    @else
      @if($emergencia->id_tipo_egreso=="3")
        <table border="0px" style="background-color:white !important">
          <tr>
            <td class="grado titulos">
              <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                <?php 
                    switch($emergencia->necropsia){
                      case 'SI' : echo "SI"; break;
                      case 'NO' : echo "NO"; break;
                    }
                ?>
              </div>
            </td>
            <td class="grado titulos">
              <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                {{ $emergencia->causas_muerte }}
              </div>
            </td>
          </tr>
          <tr>
            <td class="td_center"><strong>NECROPSIA</strong></td>
            <td class="td_center"><strong>CAUSAS DE MUERTE</strong></td>
          </tr>
        </table>
      @endif
    @endif
  @endif
    <br/><br/>
  <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
        TRIAJE
      </td>
    </tr>
  </table>
  <table border="0px" style="background-color:white !important">
    <tr>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $admision_emergencia->temperatura?> °C
        </div>
      </td>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $admision_emergencia->talla_cm?> cm
        </div>
      </td>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $admision_emergencia->peso_kg?> kg
        </div>
      </td>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $admision_emergencia->sa02_porcentaje?> %
        </div>
      </td>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $admision_emergencia->tamano_cintura_cm?> cm
        </div>
      </td>
    </tr>
    <tr>
      <td class="td_center"><strong>TEMPERATURA (°C)</strong></td>
      <td class="td_center"><strong>TALLA (cm)</strong></td>
      <td class="td_center"><strong>PESO (Kg)</strong></td>
      <td class="td_center"><strong>Sa02 (%)</strong></td>
      <td class="td_center"><strong>TAMAÑO DE CINTURA (cm)</strong></td>
    </tr>
    <tr>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $admision_emergencia->frecuencia_cardiaca_fetal?> /min
        </div>
      </td>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $admision_emergencia->presion_arterial_x?> 
        </div>
      </td>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $admision_emergencia->frecuencia_respiratoria?> /min
        </div>
      </td>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $admision_emergencia->frecuencia_cardiaca?> /min
        </div>
      </td>
      <td class="titulos" width="20%">
        <div class="rectangulo" style="border: 1px solid #A4A4A4;">
          <?php echo $admision_emergencia->fi02?> %
        </div>
      </td>
    </tr>
    <tr>
      <td class="td_center"><strong>FREC. CARDIACA FETAL (/min)</strong></td>
      <td class="td_center"><strong>PRESIÓN ARTERIAL</strong></td>
      <td class="td_center"><strong>FREC. RESPIRATORIA (/min)</strong></td>
      <td class="td_center"><strong>FREC. CARDIACA (/min)</strong></td>
      <td class="td_center"><strong>Fi02 (%)</strong></td>
    </tr>
  </table>

  <!--informacion de ingreso-->
              <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                  <div class="panel-title text-center">
                    <b>INFORMACIÓN DEL INGRESO</b>
                  </div>
                </div>
                <div class="panel-body" id="collapseprestacionespaciente">
                  <div class="col-md-12">
                    <fieldset>
                    <table border="0px" style="background-color:white !important">
                      <tr>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            @foreach($forma_ingreso as $f)
                              @if ($admision_emergencia->id_forma_ingreso == $f->id)
                                {{$f->descripcion}} 
                              @endif
                            @endforeach
                          </div>
                        </td>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            @foreach($estado_ingreso as $f)
                              @if ($admision_emergencia->id_estado_ingreso == $f->id)
                                  {{$f->descripcion}}
                              @endif
                            @endforeach
                          </div>
                        </td>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                              @foreach($categoria as $f)
                                @if ($admision_emergencia->id_motivo_ingreso == $f->id)
                                  {{$f->descripcion}}
                                @endif
                              @endforeach
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td class="td_center"><strong>FORMA INGRESO</strong></td>
                        <td class="td_center"><strong>ESTADO INGRESO</strong></td>
                        <td class="td_center"><strong>MOTIVO DE INGRESO</strong></td>
                      </tr>
                    </table>
                    <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td>
                          CONDUCIDO POR
                        </td>
                      </tr>
                      <tr>
                        <td style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                          Nro Doc: {{ $admision_emergencia->dni_conducido }} Nombre: {{ $admision_emergencia->nombre_conducido }} Parentesco:
                              @foreach($tipo_beneficiario as $f)
                                @if ($admision_emergencia->tipo_conducido == $f->id)
                                    {{$f->descripcion}}
                                @endif
                              @endforeach
                              Telefono: {{ $admision_emergencia->telf_conducido }} 
                        </td>
                      </tr>
                    </table>
                    <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td>
                          OBSERVACIÓN:
                        </td>
                      </tr>
                      <tr>
                        <td style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                          {{ $admision_emergencia->observacion_admision }}
                        </td>
                      </tr>
                    </table>
                    <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td>
                          OFICIAL DE GUARDIA:
                        </td>
                      </tr>
                      <tr>
                        <td style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                          @if($admision_emergencia->tipo_beneficiario=='CIVIL')
                            @foreach($personal_guardia as $f)
                              @if ($admision_emergencia->tipo_conducido == $f->id)
                                  {{$f->nombre}}, {{$f->paterno}} {{$f->materno}}
                              @endif
                            @endforeach
                          @endif
                        </td>
                      </tr>
                    </table>
                    </fieldset>
                  </div>
                </div>
              </div>
              <!--#informacion de ingreso-->

              <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                  <div class="panel-title text-center">
                    <b>INFORMACIÓN REFERENCIAL</b>
                  </div>
                </div>
                <div class="panel-body" id="collapseprestacionespaciente">
                  <div class="col-md-12">
                    <fieldset>
                    <table border="0px" style="background-color:white !important">
                      <tr>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            @foreach($departamento as $f)
                              @if ($admision_emergencia->id_departamento == $f->id_departamento)
                                {{$f->descripcion}}
                              @endif
                            @endforeach
                          </div>
                        </td>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            @if($provincia!='')
                              @foreach($provincia as $p)
                                @if ($admision_emergencia->id_provincia == $p->id_provincia)
                                  {{$p->descripcion}}
                                @endif
                              @endforeach
                            @endif
                          </div>
                        </td>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                              @if($distrito!='')
                                @foreach($distrito as $d)
                                  @if ($admision_emergencia->id_distrito == $d->id_distrito)
                                    {{$d->descripcion}}
                                  @endif
                                @endforeach
                              @endif
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td class="td_center"><strong>DEPARTAMENTO</strong></td>
                        <td class="td_center"><strong>PROVINCIA</strong></td>
                        <td class="td_center"><strong>DISTRITO</strong></td>
                      </tr>
                    </table>
                    <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td>
                          {{$admision_emergencia->domicilio}} 
                        </td>
                        <td>
                          {{$admision_emergencia->telefono}}<br />
                        </td>
                      </tr>
                      <tr>
                        <td class="td_center"><strong>DIRECCION REFERENCIAL</strong></td>
                        <td class="td_center"><strong>TELEFONO</strong></td>
                      </tr>
                    </table>
                    </fieldset>
                  </div>
                </div>
              </div>
              <!--informacion de violencia-->
              @if ($data_violencia->id_emergencia_violencia != null)
              <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                  <div class="panel-title text-center">
                    <b>INFORMACIÓN DE VIOLENCIA</b>
                  </div>
                </div>
                <div class="panel-body" id="collapseprestacionespaciente">
                  <div class="col-md-12">
                    <fieldset>
                    <table border="0px" style="background-color:white !important">
                      <tr>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            @foreach($violencia as $item)
                              @if ($data_violencia->id_violencia == $item->id)
                                {{$item->descripcion}}
                              @endif
                            @endforeach
                          </div>
                        </td>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            @if($modoviolencia!='')
                              @foreach($modoviolencia as $item2)
                                @if ($data_violencia->id_modalidad == $item2->id_tipo_violencia)
                                  {{$item2->descripcion}}
                                @endif
                              @endforeach
                            @endif
                          </div>
                        </td>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            @foreach($comisaria as $item)
                              @if ($data_violencia->id_comisaria == $item->id)
                                {{$item->comisaria}}
                              @endif
                            @endforeach
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td class="td_center"><strong>TIPO DE VIOLENCIA</strong></td>
                        <td class="td_center"><strong>MODALIDAD</strong></td>
                        <td class="td_center"><strong>COMISARIA</strong></td>
                      </tr>
                    </table>
                    <table border="0px" style="background-color:white !important">
                      <tr>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            @foreach($situacion as $item)
                              @if ($data_violencia->id_situacion == $item->id)
                                {{$item->descripcion}}
                              @endif
                            @endforeach
                          </div>
                        </td>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            {{ $data_violencia->fecha_accidente }}
                          </div>
                        </td>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            {{ $data_violencia->hora_accidente }}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td class="td_center"><strong>SITUACIÓN</strong></td>
                        <td class="td_center"><strong>FECHA ACCIDENTE</strong></td>
                        <td class="td_center"><strong>HORA DE ACCIDENTE</strong></td>
                      </tr>
                    </table>
                    <table border="0px" style="background-color:white !important">
                      <tr>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            @foreach($departamento_violencia as $f)
                              @if ($data_violencia->id_dpto == $f->id_departamento)
                                {{$f->descripcion}}
                              @endif
                            @endforeach
                          </div>
                        </td>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            @if($provincia_violencia!='')
                              @foreach($provincia_violencia as $p)
                                @if ($data_violencia->id_prov == $p->id_provincia)
                                  {{$p->descripcion}}
                                @endif
                              @endforeach
                            @endif
                          </div>
                        </td>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            @if($distrito_violencia!='')
                              @foreach($distrito_violencia as $d)
                                @if ($data_violencia->id_dist == $d->id_distrito)
                                  {{$d->descripcion}}
                                @endif
                              @endforeach
                            @endif
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td class="td_center"><strong>DEPARTAMENTO</strong></td>
                        <td class="td_center"><strong>PROVINCIA</strong></td>
                        <td class="td_center"><strong>DISTRITO</strong></td>
                      </tr>
                    </table>
                    <table border="0px" style="background-color:white !important">
                      <tr>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            {{ $data_violencia->lugar_accidente }}
                          </div>
                        </td>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            @foreach($unidad as $item)
                              @if ($data_violencia->id_unidad == $item->id)
                                {{$item->descripcion_larga}}
                              @endif
                            @endforeach
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td class="td_center"><strong>LUGAR DE ACCIDENTE</strong></td>
                        <td class="td_center"><strong>UNIDAD POLICIAL</strong></td>
                      </tr>
                    </table>
                    </fieldset>
                  </div>
                </div>
              </div>
              @endif
              @if ($data_evacuado->id_evacuado != null)
              <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                  <div class="panel-title text-center">
                    <b>INFORMACIÓN DEl EVACUADO</b>
                  </div>
                </div>
                <div class="panel-body" id="collapseprestacionespaciente">
                  <div class="col-md-12">
                    <fieldset>
                    <table border="0px" style="background-color:white !important">
                      <tr>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            @foreach($departamento as $f)
                              @if ($data_evacuado->id_departamento == $f->id_departamento)
                                {{$f->descripcion}}
                              @endif
                            @endforeach
                          </div>
                        </td>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            {{ $data_evacuado->numero_oficio }}
                          </div>
                        </td>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            @foreach($ipress as $item)
                              @if ($data_evacuado->unidad_policial == $item->id)
                                {{$item->nombre}}
                              @endif
                            @endforeach
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td class="td_center"><strong>DEPARTAMENTO</strong></td>
                        <td class="td_center"><strong>NUMERO DE OFICIO</strong></td>
                        <td class="td_center"><strong>ESTABLECIMIENTO</strong></td>
                      </tr>
                    </table>
                    <table border="0px" style="background-color:white !important">
                      <tr>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            @foreach($situacion as $item)
                              @if ($data_evacuado->id_situacion == $item->id)
                                {{$item->descripcion}}
                              @endif
                            @endforeach
                          </div>
                        </td>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            {{ $data_evacuado->diagnostico }}
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td class="td_center"><strong>SITUACION</strong></td>
                        <td class="td_center"><strong>DIAGNOSTICO INICIAL</strong></td>
                      </tr>
                    </table>
                    <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td>
                          OBSERVACIÓN EVACUADO:
                        </td>
                      </tr>
                      <tr>
                        <td style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                          {{ $data_evacuado->observacion }}
                        </td>
                      </tr>
                    </table>
                    </fieldset>
                    </div>
                  </div>
                </div>
              </div>
              @endif    
              <!--#informacion de evacuados-->
  <br/><br/>
  @if($admision_emergencia->estado>2)
    <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td>
          ANAMNESIS:
        </td>
      </tr>
      <tr>
        <td style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
          {{ $prestacion->anamnesis }}
        </td>
      </tr>
    </table>
    <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td>
          EXAMEN FISICO:
        </td>
      </tr>
      <tr>
        <td style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
          {{ $prestacion->examen_fisico }}
        </td>
      </tr>
    </table>
    @if(count($diagnosticos)>0)
    <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="250" valign="top">
          <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
            <tbody>
              <tr>
                <td colspan="3" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                  <b>DIAGNOSTICOS</b>
                </td>
              </tr>
              <tr>
                <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">N°</td>
                <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">CODIGO
                </td>
                <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">NOMBRE</td>
              </tr>
              <?php $n = 0; ?>
              @foreach($diagnosticos as $p)
              <?php $n++;?>
              <tr>
                <td style="border:1px solid #A4A4A4"><?php echo $n?></td>
                <td style="border:1px solid #A4A4A4">{{$p->codigo}}</td>
                <td style="border:1px solid #A4A4A4">{{$p->nombre}}</td>
              </tr>
              @endforeach

            </tbody>
          </table>
        </td>
      </tr>
    </table>
    @endif
    @if(count($procedimientos)>0)
      <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="250" valign="top">
            <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
              <tbody>
                <tr>
                  <td colspan="3" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                    <b>PROCEDIMIENTOS MÉDICOS</b>
                  </td>
                </tr>
                <tr>
                  <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">N°</td>
                  <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">CODIGO
                  </td>
                  <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">NOMBRE</td>
                </tr>
                <?php $n = 0; ?>
                @foreach($procedimientos as $p)
                <?php $n++;?>
                <tr>
                  <td style="border:1px solid #A4A4A4"><?php echo $n?></td>
                  <td style="border:1px solid #A4A4A4">{{$p->codigo}}</td>
                  <td style="border:1px solid #A4A4A4">{{$p->nombre}}</td>
                </tr>
                @endforeach

              </tbody>
            </table>
          </td>
        </tr>
      </table>
    @endif
    <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
      <tbody>
        <tr>
          <td colspan="3" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
            <b>PRODUCTOS MÉDICOS</b>
          </td>
        </tr>
      </tbody>
    </table>
      <div class="panel-body">
          <?php foreach($recetas as $rowReceta):
              if ($rowReceta->fecha_firma == "") {
                  $flag_firmar = 1;
              }
              $model_productos_receta = new \App\Models\Producto_receta;
              $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($rowReceta->id);
          ?>
      <div style="clear:both"></div>
      <fieldset>
          <div class="panel panel-info panel-bordered panel-sm">
              <div class="panel-body">

                  <table border="0px" style="background-color:white !important">
                      <tr>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <p><?php echo $rowReceta->nro_receta; ?></p>
                          </div>
                        </td>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <p><?php echo date("d-m-Y", strtotime($rowReceta->fecha_registro)); ?></p>
                          </div>
                        </td>
                        <td class="titulos" width="20%">
                          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                            <p><?php echo $rowReceta->farmacia; ?></p>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td class="td_center"><strong>RECETA VALE N°</strong></td>
                        <td class="td_center"><strong>FEC. EXPEDICIÓN</strong></td>
                        <td class="td_center"><strong>FARMACIA</strong></td>
                      </tr>
                    </table>
                  <table class="display" cellspacing="0" width="100%">
                      <thead>
                          <tr>
                              <th width="15%">Codigo</th>
                              <th width="30%">Producto farmacéutico o dispositivo médico</th>
                              <th width="15%">Unidad Medida</th>
                              <th width="10%">Tiempo(Dias)</th>
                              <th width="15%">Posologia</th>
                              <th width="15%">Cantidad Prescrita</th>
                          </tr>
                      </thead>
                      <tfoot>
                      </tfoot>
                      <?php foreach($medicamentos as $row):?>
                          <tr>
                              <td><?php echo $row->codigo?></td>
                              <td><?php echo $row->nombre?></td>
                              <td><?php echo $row->abreviatura?></td>
                              <td><?php echo $row->tiempo_periodo?></td>
                              <td><?php echo $row->posologia?></td>
                              <td><?php echo $row->cantidad_prescrita?></td>
                          </tr>
                      <?php endforeach;?>
                      <tbody>
                      </tbody>
                  </table>
              </div>
          </div>
      </fieldset>
      <div style="clear:both"></div>
      <?php endforeach;?>
      <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
      <tbody>
        <tr>
          <td colspan="3" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
            <b>EXAMENES DE LABORATORIOS</b>
          </td>
        </tr>
      </tbody>
    </table>
      @if(count($laboratorios)>0)
      <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="250" valign="top">
            <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
              <tbody>
                <tr>
                  <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">N°</td>
                  <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">CODIGO
                  </td>
                  <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">NOMBRE</td>
                  <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">CANTIDAD</td>
                </tr>
                <?php $n = 0; ?>
                @foreach($laboratorios as $p)
                <?php $n++;?>
                <tr>
                  <td style="border:1px solid #A4A4A4"><?php echo $n?></td>
                  <td style="border:1px solid #A4A4A4">{{$p->codigo}}</td>
                  <td style="border:1px solid #A4A4A4">{{$p->nombre}}</td>
                  <td style="border:1px solid #A4A4A4">{{$p->cantidad}}</td>
                </tr>
                @endforeach

              </tbody>
            </table>
          </td>
        </tr>
      </table>
      @endif
      <div style="clear:both"></div>
      <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
      <tbody>
        <tr>
          <td colspan="3" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
            <b>EXAMENES DE IMAGENOLOGIA</b>
          </td>
        </tr>
      </tbody>
    </table>
      @if(count($radiologias)>0)
      <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="250" valign="top">
            <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
              <tbody>
                <tr>
                  <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">N°</td>
                  <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">CODIGO
                  </td>
                  <td class="titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">NOMBRE</td>
                  <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">CANTIDAD</td>
                </tr>
                <?php $n = 0; ?>
                @foreach($radiologias as $p)
                <?php $n++;?>
                <tr>
                  <td style="border:1px solid #A4A4A4"><?php echo $n?></td>
                  <td style="border:1px solid #A4A4A4">{{$p->codigo}}</td>
                  <td style="border:1px solid #A4A4A4">{{$p->nombre}}</td>
                  <td style="border:1px solid #A4A4A4">{{$p->cantidad}}</td>
                </tr>
                @endforeach

              </tbody>
            </table>
          </td>
        </tr>
      </table>
      @endif
      
        <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
              EVOLUCIONES MEDICAS
            </td>
          </tr>
        </table>
        <?php foreach($evoluciones as $rowi):?>
        <fieldset>
        <table border="0px" style="background-color:white !important">
          <tr>
            <td class="titulos" width="20%">
              <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                <?php echo $rowi->temperatura?> °C
              </div>
            </td>
            <td class="titulos" width="20%">
              <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                <?php echo $rowi->talla_cm?> cm
              </div>
            </td>
            <td class="titulos" width="20%">
              <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                <?php echo $rowi->peso_kg?> kg
              </div>
            </td>
            <td class="titulos" width="20%">
              <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                <?php echo $rowi->sa02_porcentaje?> %
              </div>
            </td>
            <td class="titulos" width="20%">
              <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                <?php echo $rowi->tamano_cintura_cm?> cm
              </div>
            </td>
          </tr>
          <tr>
            <td class="td_center"><strong>TEMPERATURA (°C)</strong></td>
            <td class="td_center"><strong>TALLA (cm)</strong></td>
            <td class="td_center"><strong>PESO (Kg)</strong></td>
            <td class="td_center"><strong>Sa02 (%)</strong></td>
            <td class="td_center"><strong>TAMAÑO DE CINTURA (cm)</strong></td>
          </tr>
          <tr>
            <td class="titulos" width="20%">
              <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                <?php echo $rowi->frecuencia_cardiaca_fetal?> /min
              </div>
            </td>
            <td class="titulos" width="20%">
              <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                <?php echo $rowi->presion_arterial_x?> 
              </div>
            </td>
            <td class="titulos" width="20%">
              <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                <?php echo $rowi->frecuencia_respiratoria?> /min
              </div>
            </td>
            <td class="titulos" width="20%">
              <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                <?php echo $rowi->frecuencia_cardiaca?> /min
              </div>
            </td>
            <td class="titulos" width="20%">
              <div class="rectangulo" style="border: 1px solid #A4A4A4;">
                <?php echo $rowi->fi02?> %
              </div>
            </td>
          </tr>
          <tr>
            <td class="td_center"><strong>FREC. CARDIACA FETAL (/min)</strong></td>
            <td class="td_center"><strong>PRESIÓN ARTERIAL</strong></td>
            <td class="td_center"><strong>FREC. RESPIRATORIA (/min)</strong></td>
            <td class="td_center"><strong>FREC. CARDIACA (/min)</strong></td>
            <td class="td_center"><strong>Fi02 (%)</strong></td>
          </tr>
        </table>
        <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td><br/>
              SUBJETIVO:
            </td>
          </tr>
          <tr>
            <td style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
              <p><?php echo $rowi->subjetivo?></p>
            </td>
          </tr>
          <tr>
            <td><br/>
              OBJETIVO:
            </td>
          </tr>
          <tr>
            <td style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
              <p><?php echo $rowi->objetivo?></p>
            </td>
          </tr>
          <tr>
            <td><br/>
              ANALISIS:
            </td>
          </tr>
          <tr>
            <td style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
              <p><?php echo $rowi->analisis?></p>
            </td>
          </tr>
          <tr>
            <td><br/>
              TRATAMIENTO Y DIETA:
            </td>
          </tr>
          <tr>
            <td style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
              <p><?php echo $rowi->tratamiento_dieta?></p>
            </td>
          </tr>
          <tr>
            <td><br/>
              INDICACIONES:
            </td>
          </tr>
          <tr>
            <td style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
              <p><?php echo $rowi->indicaciones?></p>
            </td>
          </tr>

        </table>
        </fieldset>
        <?php endforeach;?>
    </div>
    <br/><br/>
    <!--FIN DE EVOLUCIONES-->
    <div style="clear:both"></div>
    <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
      <tbody>
        <tr>
          <td colspan="3" style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
            <b>INTERCONSULTAS</b>
          </td>
        </tr>
      </tbody>
    </table>
    <?php foreach($interconsultas as $rowi):?>
    <table border="0px" style="background-color:white !important">
      <tr>
        <td class="titulos" width="20%">
          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
            <?php echo $rowi->servicio ?>
          </div>
        </td>
        <td class="titulos" width="20%">
          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
            <?php echo $rowi->motivo_interconsulta ?>
          </div>
        </td>
      </tr>
      <tr>
        <td class="td_center"><strong>SERVICIO</strong></td>
        <td class="td_center"><strong>MOTIVO</strong></td>
      </tr>
    </table>
    <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td>
          RESUMEN:
        </td>
      </tr>
      <tr>
        <td style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
          <?php echo $rowi->resumen_interconsulta ?>
        </td>
      </tr>
    </table>
    <?php endforeach;?>
    <br/><br/>
    <!--FIN DE INTERCONULTAS-->
  <?php if(count($referencia)>0):?>
    <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
          REFERENCIA MEDICA
        </td>
      </tr>
    </table>
    <table border="0px" style="background-color:white !important">
      <tr>
        <td class="titulos" width="20%">
          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
            <?php  echo $referencia->establecimiento?>
          </div>
        </td>
        <td class="titulos" width="20%">
          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
            <?php if($referencia->id_uci==1):  echo 'SI'; else: echo 'NO'; endif;?>
          </div>
        </td>
        @if($referencia->id_uci==1)
        <td class="titulos" width="20%">
          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
            
            <?php 
                switch($referencia->id_uci_cv){
                  case '1' : echo "CON VENTILADOR MECANICO"; break;
                  case '2' : echo "SIN VENTILADOR MECANICO"; break;
                  case '3' : echo "NINGUNO"; break;
                }
            ?>
            
          </div>
        </td>
        @endif
      </tr>
      <tr>
        <td class="td_center"><strong>ESTABLECIMIENTO</strong></td>
        <td class="td_center"><strong>ESPECIFICAR SI REQUIERE UCI</strong></td>
        @if($referencia->id_uci==1)
          <td class="td_center"></td>
        @endif
      </tr>
    </table>
    <table border="0px" style="background-color:white !important">
      <tr>
        <td class="titulos" width="20%">
          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
            <?php if($referencia->id_ambulancia==1):  echo 'SI'; else: echo 'NO'; endif;?>
          </div>
        </td>
        @if($referencia->id_ambulancia==1)
        <td class="titulos" width="20%">
          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
            <?php if($referencia->id_ambulancia_ref==1):  echo 'IDA'; else: echo 'VUELTA'; endif;?>
          </div>
        </td>
        <td class="titulos" width="20%">
          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
            <?php 
                  switch($referencia->id_tipo_ambulancia){
                    case '1' : echo "Tipo I"; break;
                    case '2' : echo "Tipo II"; break;
                    case '3' : echo "Tipo III"; break;
                  }
            ?>
          </div>
        </td>
        @endif
      </tr>
      <tr>
        <td class="td_center"><strong>REQUIERE TRASLADO EN AMBULANCIA</strong></td>
        @if($referencia->id_ambulancia==1)
          <td class="td_center"></td>
          <td class="td_center"><strong>TIPO DE AMBULANCIA</strong></td>
        @endif
      </tr>
    </table>
    <table border="0px" style="background-color:white !important">
      <tr>
        <td class="titulos" width="20%">
          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
            <?php if($referencia->id_pasaje==1):  echo 'SI'; else: echo 'NO'; endif;?>
          </div>
        </td>
        @if($referencia->id_ambulancia==1)
        <td class="titulos" width="20%">
          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
            <?php if($referencia->id_acompanante==1):  echo 'SI'; else: echo 'NO'; endif;?>
          </div>
        </td>
        <td class="titulos" width="20%">
          <div class="rectangulo" style="border: 1px solid #A4A4A4;">
            <?php 
                switch($referencia->id_situacion){
                  case '1' : echo "ELECTIVO"; break;
                  case '2' : echo "URGENCIA"; break;
                  case '3' : echo "EMERGENCIA"; break;
                }
            ?>
          </div>
        </td>
        @endif
      </tr>
      <tr>
        <td class="td_center"><strong>REQUIERE PASAJE</strong></td>
        <td class="td_center"><strong>NECESITA ACOMPAÑANTE</strong></td>
        <td class="td_center"><strong>SITUACION CLINICA DEL PACIENTE</strong></td>
      </tr>
    </table>
    <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td>
          OBSERVACION:
        </td>
      </tr>
      <tr>
        <td style="border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
          <?php echo $referencia->observacion_referencia;?>
        </td>
      </tr>
    </table>
  <?php endif;?>
  @endif  
  
  <table style="margin-top: 10px">
    <tr>
      <td class="td_ancho_espacios"></td>
      <td class="td_ancho_espacios"></td>
    </tr>
  </table>

  <footer>
    * CUALQUIER OMISIÓN DE REGISTRO, INVALIDA LA PRESENTE RECETA
  </footer>
  <script type="text/php">
    if (isset($pdf)) {
                $x = 490;
                $y = 790;
                $text = "Pagina {PAGE_NUM} de {PAGE_COUNT}";
                $font = null;
                $size = 10;
                $color = array(255,0,0);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle = 0.0;   //  default
                $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
            }
        </script>
</body>

</html>
