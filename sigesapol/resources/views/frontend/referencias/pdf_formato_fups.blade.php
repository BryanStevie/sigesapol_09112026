<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Anexo 02: FORMATO ÚNICO DE PRESTACIONES DE SALUD (FUPS)</title>
    <link rel="stylesheet" type="text/css" href="assets/css/pdf.css" />
    <style>
     @page {
      /* margin-left: 0.15in;
      margin-right: 0.15in;
      margin-top: 0.15in;
      margin-bottom: 0.15in;
      size: 8.4in 13.1in;  */
      margin-left: 0.25in;
      margin-right: 0.25in;
      margin-top: 0.15in;
      margin-bottom: 0.15in;
      size: 8.5in 14in;
    }

    body {
        margin: 0; /* Eliminar márgenes predeterminados del cuerpo */
    }
    .tabla {
        display: table;
        width: 100%;
        border-collapse: collapse;
        font-size: 9.5px;
        font-weight: bold;
    }
    .tabla-center{
      margin: auto;
    }
    .fila {
        display: table-row;
    }
    .celda {
        display: table-cell;
        padding: 2px;
        border: 1px solid black;
        background-color: #008080;
        color: white;
        text-align: center;
    }
    .celda2 {
        display: table-cell;
        padding: 3px;
    }
    .celda_blanco1 {
        display: table-cell;
        border: 1px solid black;
        padding: 2px; 
    }
    .celda_solo_border_izquierdo{
      display: table-cell;
      border-left: 1px solid black;
      border-right: 1px solid rgb(255, 255, 255);
      padding: 2px; 
    }
    .celda_sin_border {
        display: table-cell;
        border-left: 1px solid rgb(255, 255, 255);
        border-right: 1px solid rgb(255, 255, 255);
        padding: 2px; 
    }
    .celda_blanco2 {
        display: table-cell;
        border: 1px solid black;
        padding: 2px;
        height: 200px; /* Altura reducida */
    }
    .celda_blanco3 {
        display: table-cell;
        padding: 2px;
        height: 200px; /* Altura reducida */
    }
    /* .celda_blanco2_izquierda{
      display: table-cell;
      padding: 2px;
      height: 200px; 
    }
    .celda_blanco2_derecha{
      
    } */
    .celda_plomo1 {
        display: table-cell;
        border: 1px solid black;
        background-color: #E1E1E1;
        padding: 2px;
        height: 10px; /* Altura reducida */
    }
    .titulo {
      text-align: center;
    }
    .celda_firma{
      border-top:#021919 solid 2px;
      width: 36%;
      text-align: center;
    }

    .contenedor {
      position: relative;
      width: 30px; /* Ajusta el ancho según sea necesario */
      height: 17px; /* Ajusta la altura según sea necesario */
    }

    .valor {
      top: 1px;
      position: relative;
      z-index: 1;
    }

    .marca {
      position: absolute;
      top: -0.2%;
      right: 30%;
      z-index: 2;
      color: rgba(22, 125, 189, 0.1);
      opacity: 0.7; /* Opacidad del 50% */
      font-size: 18px; 
      transform: translate(-50%, -50%); 
      font-weight: lighter;
      font-family: Helvetica,Arial,sans-serif;
    }
    </style>
</head>

<body>
    <div class="tabla">
        <div class="fila">
            <div class="celda titulo" colspan="14">FORMATO ÚNICO DE PRESTACIONES DE SALUD - FUPS</div>
        </div>
        <div class="fila">
            <div class="celda_blanco1 titulo" style="text-align: center;" colspan="14">
              <table border="1" class="tabla-center" style="width: 260px;">
                <tr>
                  <td colspan="3" style="background-color: #E1E1E1;">NÚMERO DE FORMATO</td>
                </tr>
                <tr>
                  <td style="height: 20px"><?php echo $datos['numero_formato_1'];?></td>
                  <td><?php echo $datos['numero_formato_2'];?></td>
                  <td><?php echo $datos['numero_formato_3'];?></td>
                </tr>
              </table>
            </div>
        </div>
        <div class="fila">
          <div class="celda titulo" colspan="14">DE LA INSTITUCIÓN PRESTADORA DE SERVICIOS DE SALUD</div>
        </div>
        <div class="fila">
          <div class="celda_plomo1 titulo" style="text-align: center; width: 35%;"  colspan="5"> CÓDIGO UNICO DE RENIPRESS</div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 65%;"  colspan="9">NOMBRE  DE LA IPRESS QUE REALIZA LA ATENCIÓN</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 titulo" style="text-align: center; width: 35%;"  colspan="5"><?php echo $datos['numero_formato_1']; //CODIGO DE LA IPRESS?></div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 65%;"  colspan="9"><?php echo $datos['nombre_ipress_atencion'];?></div>
        </div>
        <div class="fila">
          <div class="celda_plomo1 titulo" style="text-align: center; width: 6%;" colspan="2">  LUGAR DE ATENCIÓN</div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 6%;" colspan="2">  TIPOS DE INGRESO</div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 88%;" colspan="10"> REFERENCIA REALIZADA POR</div>
        </div>
        <div class="fila">
          <div class="celda_plomo1 titulo" style="text-align: center; width: 7%;">INTRAMURAL</div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 3%;"><?php if($datos['lugar_de_atencion']=='INTRAMURAL'){echo 'X';}?></div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 7%;"> AMBULATORIA(I/E)</div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 3%;"><?php if($datos['tipo_ingreso']=='AMBULATORIO'){ echo 'X';}?> </div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 10%;">  CÓD. IPRESS </div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 20%;" colspan="7" > NOMBRE DE LA IPRESS U OFERTA FLEXIBLE </div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 5%;"  colspan="2">  N° HOJA DE REFERENCIA</div>
          
        </div>
        <div class="fila">
          <div class="celda_plomo1 titulo" style="text-align: center; width: 7%;">  EXTRAMURAL</div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 3%;"><?php if($datos['lugar_de_atencion']=='EXTRAMURAL'){ echo 'X';}?> </div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 7%;">   REFERENCIA(E)</div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 3%;"><?php if($datos['tipo_ingreso']=='REFERENCIA'){ echo 'X';}?></div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 10%;" rowspan="2">
            <?php echo $datos['cod_ipress'];?>
          </div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 40%;" rowspan="2" colspan="7">
            <?php echo $datos['nombre_de_la_ipress'];?>
          </div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 30%;" rowspan="2" colspan="2"><?php echo $datos['n_hoja_de_referencia'];?></div>
        </div>
        <div class="fila">
          <div class="celda_plomo1 titulo" style="text-align: center; width: 7%;">  </div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 3%;"> </div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 7%;"> EMERGENCIA(I)</div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 3%;"><?php if($datos['tipo_ingreso']=='EMERGENCIA'){ echo 'X';}?></div>
        </div>
        <div class="fila">
          <div class="celda titulo" colspan="14"> DEL ASEGURADO / USUARIO</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 " style="text-align: center; width: 5%;" colspan="14">
            <table style="width: 100%;" border="1">
              <tr>
                <td class="celda_plomo1" style="width: 6%;" colspan="2">  IDENTIFICACIÓN</td>
                <td class="celda_plomo1" style="width: 10%;" >  HISTORIA CLINICA</td>
                <td  class="celda_plomo1" style="width: 3%;" colspan="2">SEXO</td>
                <td class="celda_plomo1"  style="width: 5%;" colspan="3">FECHA NAC. </td>
                <td class="celda_plomo1"  style="width: 5%;" colspan="3"> FECHA FALLEC.</td>
              </tr>
              <tr>
                <td class="celda_plomo1" style="width: 1%;">TDI</td>
                <td class="celda_plomo1" style="width: 5%;"> N° DOCUMENTO DE IDENTIDAD</td>
                <td class="celda_blanco1" rowspan="2"><?php echo $datos['historia_clinica'];?></td>
                <td class="celda_blanco1" style="width: 2%;">Masculino</td>
                <td class="celda_blanco1" style="width: 1%;"><?php if($datos['sexo']=='Masculino'){ echo 'X';}?></td>
                <td class="celda_blanco1" style="width: 1%;">DIA</td>
                <td class="celda_blanco1" style="width: 1%;">MES</td>
                <td class="celda_blanco1" style="width: 1%;">AÑO</td>
                <td class="celda_blanco1" style="width: 1%;">DIA</td>
                <td class="celda_blanco1" style="width: 1%;">MES</td>
                <td class="celda_blanco1" style="width: 1%;">AÑO</td>
              </tr>
              <tr>
                <td class="celda_blanco1" style="width: 1%;"><?php echo $datos['identificacion_tdi'];?></td>
                <td class="celda_blanco1" style="width: 5%;"><?php echo $datos['identificacion_dni'];?></td>
                <td class="celda_blanco1" style="width: 2%;">Femenino</td>
                <td class="celda_blanco1" style="width: 1%;"><?php if($datos['sexo']=='Femenino'){echo 'X';}?></td>
                <td class="celda_blanco1" style="width: 1%;"><?php echo $datos['dia_nacimiento'];?></td>
                <td class="celda_blanco1" style="width: 1%;"><?php echo $datos['mes_nacimiento'];?></td>
                <td class="celda_blanco1" style="width: 1%;"><?php echo $datos['anio_nacimiento'];?></td>
                <td class="celda_blanco1" style="width: 1%;"><?php echo $datos['dia_fallecido'];?></td>
                <td class="celda_blanco1" style="width: 1%;"><?php echo $datos['mes_fallecido'];?></td>
                <td class="celda_blanco1" style="width: 1%;"><?php echo $datos['anio_fallecido'];?></td>
              </tr>
            </table>
          </div>
        </div>
        <div class="fila">
          <div class="celda_plomo1 titulo" style="text-align: center; width: 50%;"  colspan="5"> APELLIDO PATERNO</div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 50%;"  colspan="9">APELLIDO MATERNO</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 titulo" style="text-align: center; width: 50%;"  colspan="5"><?php echo $datos['apellido_paterno'];?></div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 50%;"  colspan="9"><?php echo $datos['apellido_materno'];?> </div>
        </div>
        <div class="fila">
          <div class="celda_plomo1 titulo" style="text-align: center; width: 50%;"  colspan="5"> PRIMER NOMBRE</div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 50%;"  colspan="9"> OTROS NOMBRES</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 titulo" style="text-align: center; width: 50%;"  colspan="5"><?php echo $datos['primer_nombre'];?></div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 50%;"  colspan="9"><?php echo $datos['otros_nombres'];?></div>
        </div>

        <div class="fila">
          <div class="celda titulo" colspan="14">SALUD MATERNA</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 " style="text-align: center; width: 5%;" colspan="14">
            <table style="width: 100%;" border="1">
              <tr>
                <td class="celda_plomo1" style="width: 10%;" colspan="2"> ETAPA</td>
                <td class="celda_plomo1" style="width: 20%;"  rowspan="3"> FECHA PROBABLE DE  PARTO / FECHA DE PARTO </td>
                <td  class="celda_plomo1" style="width: 5%;" colspan="2">DIA</td>
                <td class="celda_plomo1"  style="width: 5%;" colspan="2">MES</td>
                <td class="celda_plomo1"  style="width: 5%;" colspan="4">AÑO</td>
                <td class="celda_blanco1" style="padding-bottom: -1.5px; width: 36%;" rowspan="3" > 
                  <div class="tabla">
                    <?php 
                    if($datos['etapa_gestante']=='Puerpera'){
                      if(!empty($datos['nacidos'])){
                        foreach ($datos['nacidos'] as $key => $n) {?>
                        <div class="fila" >
                          <div class="celda_plomo1 " style="text-align: left; width: 70%;">DNI / CNV / AFILIACIÓN DEL RN <?php echo $n->id;?></div>
                          <div class="celda_blanco1 " style="text-align: left; width: 30%;" > <?php echo $n->num_doc;?></div>
                        </div>
                    <?php 
                        }
                      }
                    }
                    ?>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="celda_plomo1">GESTANTE</td>
                <td  style="width: 5%;"><?php if($datos['etapa_gestante']=='Gestante'){echo 'X';}?></td>
                <td  rowspan="2" style="width: 5%;">
                  <?php  echo substr($datos['dia_probable_parto'], 0, 1);?>
                </td>
                <td  rowspan="2" style="width: 5%;">
                  <?php echo substr($datos['dia_probable_parto'], 1, 1); ?>
                </td>
                <td  rowspan="2" style="width: 5%;">
                  <?php  echo substr($datos['mes_probable_parto'], 0, 1);?>
                </td>
                <td  rowspan="2" style="width: 5%;">
                  <?php  echo substr($datos['mes_probable_parto'], 1, 1);?>  
                </td>
                <td  rowspan="2" style="width: 5%;">
                  <?php  echo substr($datos['anio_probable_parto'], 0, 1);?></td>
                <td  rowspan="2" style="width: 5%;">
                  <?php echo substr($datos['anio_probable_parto'], 1, 1);?></td>
                <td  rowspan="2" style="width: 5%;">
                  <?php  echo substr($datos['anio_probable_parto'], 2, 1);?></td>
                <td  rowspan="2" style="width: 5%;">
                  <?php  echo substr($datos['anio_probable_parto'], 3, 1);?></td>
              </tr>
              <tr>
                <td class="celda_plomo1">PUERPERA</td>
                <td style="width: 5%;"><?php if($datos['etapa_gestante']=='Puerpera'){echo 'X';}?></td>
              </tr>
            </table>
          </div>
        </div>
        
        <div class="fila">
          <div class="celda titulo" colspan="14"> DE LA ATENCIÓN</div>
        </div>
        <div class="fila">
          <div class="celda_solo_border_izquierdo " style="float: left;" colspan="2" >
            <table border="1" style="width: 160px;">
              <tr>
                <td colspan="2" class="celda_plomo1">TIPO DE PRESTACION</td>
              </tr>
              <tr> <td style="text-align: left;">1 = Extra hospitalario </td><td></td></tr>
              <tr> <td style="text-align: left;">4 = Ambulatorio  </td><td><?php if($datos['tipo_prestacion']==1){echo 'X';}?></td></tr>
              <tr> <td style="text-align: left;">5 = Hospitalaria </td><td><?php if($datos['tipo_prestacion']==3){echo 'X';}?></td></tr>
              <tr> <td style="text-align: left;">6 = Emergencia  </td><td><?php if($datos['tipo_prestacion']==2){echo 'X';}?></td></tr>
            </table>
            <table border="1"  style="width: 160px;">
              <tr>
                <td class="celda_plomo1" style="height: 35px;">UPSS</td>
                <td class="celda_plomo1" style="height: 35px;">DESCRIPCIÓN</td>
              </tr>
              <tr><td style="height: 40px;"><?php echo $datos['upss'];?></td><td><?php echo $datos['descripcion'];?></td></tr>
            </table>
          </div>
          <div class="celda_sin_border"    colspan="9" >
            <table border="1"  style="width: 100%;" cellspacing="0">
              <tr>
                <td  class="celda_plomo1"> (APLICA SOLO PARA TIPO DE PRESTACIÓN 1 y 4 )</td>
              </tr>
              <tr> 
                <td style="line-height: 1;">
                  <div class="tabla">
                    <div class="fila">
                        <div class="celda2 titulo ">
                          <table border="1" cellspacing="0">
                            <tr>
                              <td rowspan="2" class="celda_plomo1"> FECHA ATENCION/PRESTACION </td>
                              <td class="celda_plomo1">DIA</td>
                              <td class="celda_plomo1">MM</td>
                              <td class="celda_plomo1">AÑO</td>
                            </tr>
                            <tr>
                              <td class="celda_blanco1" style="height: 10px;"><?php echo $datos['dia_atencion']; ?></td>
                              <td class="celda_blanco1"><?php echo $datos['mes_atencion']; ?></td>
                              <td class="celda_blanco1"><?php echo $datos['anio_atencion']; ?></td>
                            </tr>
                          </table>
                        </div>
                        <div class="celda2 titulo" ></div>
                        <div class="celda2 titulo" >
                          <table border="1" cellspacing="0">
                            <tr>
                              <td colspan="2" class="celda_plomo1">HORA</td>
                            </tr>
                            <tr>
                              <td class="celda_blanco1" style="height: 10px;">
                                <?php 
                                  $hora = explode(":", $datos['hora_atencion']);
                                  if(!empty($hora[0])){
                                    echo $hora[0];
                                  }else{
                                    echo '  ';
                                  } 
                                ?>
                              </td>
                              <td class="celda_blanco1">
                                <?php  
                                  if(!empty($hora[1])){
                                    echo $hora[1];
                                  }else{
                                    echo '  ';
                                  } ?>
                              </td>
                            </tr>
                          </table>
                        </div>
                    </div>
                  </div>
                </td>
              </tr>
            </table>
            <table border="1"  cellspacing="0" style="width: 100%;">
              <tr>
                <td  class="celda_plomo1">  LLENAR EN CASO DE INTERNAMIENTO (APLICA SOLO PARA LA 5 Y 6)</td>
              </tr>
              <tr> 
                <td style="padding-left: 15px; width: auto; max-width: 100%; border-collapse: collapse;">
                  <div class="tabla">
                    <div class="fila">
                        <div class="celda2">
                          <table border="1" cellspacing="0">
                            <tr>
                              <td rowspan="2" class="celda_plomo1"> FECHA DE INGRESO </td>
                              <td class="celda_plomo1">DIA</td>
                              <td class="celda_plomo1">MM</td>
                              <td class="celda_plomo1">AÑO</td>
                            </tr>
                            <tr>
                              <td style="height: 13px;">
                                <?php
                                  $dia_ingreso = $datos['dia_ingreso'];
                                  if ($datos['dia_ingreso'] < 10) {
                                      $dia_ingreso = '0' . $datos['dia_ingreso'];
                                  }
                                  echo $dia_ingreso;
                                ?>
                                </td>
                              <td>
                                <?php
                                  $mes_ingreso = $datos['mes_ingreso'];
                                  if ($datos['mes_ingreso'] < 10) {
                                      $mes_ingreso = '0' . $datos['mes_ingreso'];
                                  }
                                  echo $mes_ingreso;
                                ?>
                               </td>
                              <td>
                                <?php echo $datos['anio_ingreso']; //SOLO CUANDO ES HOSPITALIZACION?></td>
                            </tr>
                          </table>
                        </div>
                        <div class="celda2 titulo" ></div>
                        <div class="celda2 titulo" >
                          <table border="1" cellspacing="0">
                            <tr>
                              <td colspan="2" class="celda_plomo1">HORA</td>
                            </tr>
                            <tr>
                              <td class="celda_blanco1" style="height: 10px;">
                                <?php
                                  $hora_ingreso = $datos['hora_ingreso'];
                                  if ($datos['hora_ingreso'] < 10) {
                                      $hora_ingreso = '0' . $datos['hora_ingreso'];
                                  }
                                  if(!empty($datos['anio_ingreso']) && $datos['hora_ingreso']==0){
                                    echo '00';
                                  }else{
                                    echo $hora_ingreso;
                                  }
                                ?>
                              </td>
                              <td class="celda_blanco1">
                                <?php
                                  $minuto_ingreso = $datos['minuto_ingreso'];
                                  if ($datos['minuto_ingreso'] < 10) {
                                      $minuto_ingreso = '0' . $datos['minuto_ingreso'];
                                  }
                                  echo $minuto_ingreso;
                                ?>
                               </td>
                            </tr>
                          </table>
                        </div>
                    </div>
                  </div>
                  <table border="1" style="width: 95%;">
                    <tr>
                      <td  class="celda_plomo1"> CORTE ADMINISTRATIVO (APLICA PARA TIPO 5)</td>
                    </tr>
                    <tr> 
                      <td style="padding: 0;">
                        <div class="tabla">
                          <div class="fila">
                              <div class="celda2">
                                <table border="0" cellspacing="0">
                                  <tr>
                                    <td style="border: #FFFF 1px;"></td>
                                    <td class="celda_plomo1">DIA</td>
                                    <td class="celda_plomo1">MM</td>
                                    <td class="celda_plomo1">AÑO</td>
                                  </tr>
                                  <tr>
                                    <td style="border: 1px solid black;" > INICIO C.A</td>
                                    <td class="celda_blanco1"  style="height: 10px;">
                                      <?php
                                      $dia_inicio_ca = $datos['dia_inicio_ca'];
                                      if ($datos['dia_inicio_ca'] < 10) {
                                          $dia_inicio_ca = '0' . $datos['dia_inicio_ca'];
                                      }
                                      echo $dia_inicio_ca?$dia_inicio_ca:'  ';
                                      ?>
                                    </td>
                                    <td class="celda_blanco1">
                                      <?php
                                      $mes_inicio_ca = $datos['mes_inicio_ca'];
                                      if ($datos['mes_inicio_ca'] < 10) {
                                          $mes_inicio_ca = '0' . $datos['mes_inicio_ca'];
                                      }
                                      echo $mes_inicio_ca?$mes_inicio_ca:'  ';
                                      ?>
                                    </td>
                                    <td class="celda_blanco1"><?php echo $datos['anio_inicio_ca']?$datos['anio_inicio_ca']:'    ';?></td>
                                  </tr>
                                  <tr>
                                    <td style="border: 1px solid black;">  CIERRE C.A</td>
                                    <td class="celda_blanco1"  style="height: 10px;">
                                      <?php
                                      $dia_fin_ca = $datos['dia_fin_ca'];
                                      if ($datos['dia_fin_ca'] < 10) {
                                          $dia_fin_ca = '0' . $datos['dia_fin_ca'];
                                      }
                                      echo $dia_fin_ca?$dia_fin_ca:'  ';
                                      ?>
                                    </td>
                                    <td class="celda_blanco1">
                                      <?php
                                      $mes_fin_ca = $datos['mes_fin_ca'];
                                      if ($datos['mes_fin_ca'] < 10) {
                                          $mes_fin_ca = '0' . $datos['mes_fin_ca'];
                                      }
                                      echo $mes_fin_ca?$mes_fin_ca:'  ';
                                      ?>
                                    </td>
                                    <td class="celda_blanco1"><?php echo $datos['anio_fin_ca']?$datos['anio_fin_ca']:'    ';?></td>
                                  </tr>
                                </table>
                              </div>
                              <div class="celda2" ></div>
                              <div class="celda2" >
                                <table border="1" cellspacing="0">
                                  <tr>
                                    <td colspan="2" class="celda_plomo1">HORA</td>
                                  </tr>
                                  <tr>
                                    <td class="celda_blanco1"  style="height: 10px;">
                                      <?php
                                        $hora_inicio_ca = $datos['hora_inicio_ca'];
                                        if ($datos['hora_inicio_ca'] < 10) {
                                            $hora_inicio_ca = '0' . $datos['hora_inicio_ca'];
                                        }
                                        if(!empty($datos['anio_inicio_ca']) && $datos['hora_inicio_ca']==0){
                                          echo '00';
                                        }else{
                                          echo $hora_inicio_ca?$hora_inicio_ca:'  ';
                                        }
                                      ?>
                                      </td>
                                    <td class="celda_blanco1">
                                      <?php
                                        $min_inicio_ca = $datos['min_inicio_ca'];
                                        if ($datos['min_inicio_ca'] < 10) {
                                            $min_inicio_ca = '0' . $datos['min_inicio_ca'];
                                        }
                                        echo $min_inicio_ca?$min_inicio_ca:'  ';
                                      ?>
                                      </td>
                                  </tr>
                                  <tr>
                                    <td class="celda_blanco1"  style="height: 10px;">
                                      <?php
                                        $hora_fin_ca = $datos['hora_fin_ca'];
                                        if ($datos['hora_fin_ca'] < 10) {
                                            $hora_fin_ca = '0' . $datos['hora_fin_ca'];
                                        }
                                        if(!empty($datos['anio_fin_ca']) && $datos['hora_fin_ca']==0){
                                          echo '00';
                                        }else{
                                          echo $hora_fin_ca?$hora_fin_ca:'  ';
                                        }
                                      ?>
                                      </td>
                                    <td class="celda_blanco1">
                                      <?php
                                        $min_fin_ca = $datos['min_fin_ca'];
                                        if ($datos['min_fin_ca'] < 10) {
                                            $min_fin_ca = '0' . $datos['min_fin_ca'];
                                        }
                                        echo $min_fin_ca?$min_fin_ca:'  ';
                                      ?>
                                      </td>
                                  </tr>
                                </table>
                              </div>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </table>
                  <div class="tabla">
                    <div class="fila">
                        <div class="celda2">
                          <table border="0" cellspacing="0">
                            <tr>
                              <td class="celda_plomo1" rowspan="2"> FECHA DE EGRESO</td>
                              <td class="celda_plomo1">DIA</td>
                              <td class="celda_plomo1">MM</td>
                              <td class="celda_plomo1">AÑO</td>
                            </tr>
                            <tr>
                              <td class="celda_blanco1" style="height: 10px;">
                                <?php
                                  $dia_egreso = $datos['dia_fecha_Egreso'];
                                  if ($datos['dia_fecha_Egreso'] < 10) {
                                      $dia_egreso = '0' . $datos['dia_fecha_Egreso'];
                                  }
                                  echo $dia_egreso;
                                  ?>
                              </td>
                              <td class="celda_blanco1">
                                <?php
                                  $mes_egreso = $datos['mes_fecha_Egreso'];
                                  if ($datos['mes_fecha_Egreso'] < 10) {
                                      $mes_egreso = '0' . $datos['mes_fecha_Egreso'];
                                  }
                                  echo $mes_egreso;
                                  ?>
                                </td>
                              <td class="celda_blanco1"><?php echo $datos['anio_fecha_Egreso'];?></td>
                            </tr>
                          </table>
                        </div>
                        <div class="celda2"></div>
                        <div class="celda2" >
                          <table border="1" cellspacing="0">
                            <tr>
                              <td colspan="2" class="celda_plomo1">HORA</td>
                            </tr>
                            <tr>
                              <td class="celda_blanco1" style="height: 10px;">
                                <?php
                                  $hora_fecha_Egreso = $datos['hora_fecha_Egreso'];
                                  if ($datos['hora_fecha_Egreso'] < 10) {
                                      $hora_fecha_Egreso = '0' . $datos['hora_fecha_Egreso'];
                                  }
                                  if(!empty($datos['anio_fecha_Egreso']) && $datos['hora_fecha_Egreso']==0){
                                    echo '00';
                                  }else{
                                    echo $hora_fecha_Egreso;
                                  }
                                ?>
                                </td>
                              <td class="celda_blanco1">
                                <?php
                                  $min_fecha_Egreso = $datos['min_fecha_Egreso'];
                                  if ($datos['min_fecha_Egreso'] < 10) {
                                      $min_fecha_Egreso = '0' . $datos['min_fecha_Egreso'];
                                  }
                                  echo $min_fecha_Egreso;
                                ?>
                                </td>
                            </tr>
                          </table>
                        </div>
                    </div>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class="celda_blanco1" style="text-align: center; width: 5%;" colspan="3">
            
            <table border="1">
              <tr>
                <td colspan="3" class="celda_plomo1"> N° DE FUPS A VINCULAR</td>
              </tr>
              <tr>
                <td class="celda_plomo1" >RENIPRESS</td>
                <td class="celda_plomo1">LOTE</td>
                <td class="celda_plomo1">CORELATIVO</td>
              </tr>
              <tr>
                <td class="celda_blanco1"  style="height: 35px"><?php echo $datos['vincular_renipress'];?></td>
                <td class="celda_blanco1"  style="height: 35px"><?php echo $datos['vincular_lote'];?></td>
                <td class="celda_blanco1"  style="height: 35px"><?php echo $datos['vincular_correlativo'];?></td>
              </tr>
            </table>
            <br>
            <div style="border:1px solid black;">
              INSTRUCCIONES DE LLENADO
              <p>
                Verificar que las fechas ingresadas sean 
                consistentes en una línea de tiempo.
                 Todas las prestaciones derivadas de una 
                principal, deben vincularse con el FUPS inicial 
                o anterior correspondiente.
              </p>

            </div>
          </div>
        </div>
        <div class="fila">
          <div class="celda titulo" colspan="14">  SE REFIERE / CONTRARREFIERE A</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 " style="text-align: center; width: 5%;" colspan="14">
            <table style="width: 100%;" border="1">
              <tr>
                <td class="celda_plomo1" style="width: 1%;" colspan="2">TIPO DESTINO</td>
                <td class="celda_plomo1" style="width: 15%;"> CÓDIGO RENAES DE LA IPRESS</td>
                <td  class="celda_plomo1"> NOMBRE DE LA IPRESS A LA QUE SE REFIERE / CONTRARREFIERE</td>
                <td class="celda_plomo1"  style="width: 20%;"> N° HOJA DE REFER / CONTRARR.</td>
              </tr>
              <tr>
                <td class="celda_plomo1">Referencia</td>
                <td><?php if($datos['tipo_destino']=='referencia'){echo 'X';}?></td>
                <td rowspan="2"> 
                  <?php echo $datos['codigo_renae'];?>
                  </td>
                <td rowspan="2">
                  <?php echo $datos['codigo_nombre'];?>
                </td>
                <td rowspan="2">
                  <?php echo $datos['n_hoja_ref_contra'];?> 
                </td>
              </tr>
              <tr>
                <td class="celda_plomo1">Contrarreferencia</td>
                <td><?php if($datos['tipo_destino']=='contrareferencia'){echo 'X';}?></td>
              </tr>
            </table>
          </div>
        </div>
        <div class="fila">
          <div class="celda titulo" colspan="14"> DIAGNÓSTICOS</div>
        </div>

        <div class="fila">
          <div class="celda_blanco1 " style="text-align: center; width: 5%;" colspan="14">
            <table style="width: 100%;" border="1">
              <tr>
                <td class="celda_plomo1" style="width: 1%;">N°</td>
                <td class="celda_plomo1" style="width: 6%;">CIE - 10</td>
                <td  class="celda_plomo1">DESCRIPCIÓN DEL DIAGNÓSTICO</td>
                <td class="celda_plomo1" colspan="3" style="width: 5%;"> TIPO DE DIAGNÓSTICO</td>
                <td class="celda_plomo1" colspan="2" style="width: 5%;"> INGRESO/EGRESO</td>
              </tr>
              <?php 
              if(!empty($fups_diagnosticos)):
              $n=1;
              foreach($fups_diagnosticos as $row):?>
              <tr>
                <td class="celda_plomo1"><?php echo $n++;?></td>
                <td><?php echo $row->id_cie10;?></td>
                <td ><?php echo $row->nombre;?></td>
                <td>
                  <div class="contenedor">
                    <div class="valor"  style="font-size: 14px;"> P </div>
                    <div class="marca"><?php if($row->tipo_diagnostico=='Presuntivo'){echo 'X';}?></div>
                  </div>
                </td>
                <td>
                  <div class="contenedor">
                  <div class="valor"  style="font-size: 12px;">D</div>
                  <div class="marca"><?php if($row->tipo_diagnostico=='Definitivo'){echo 'X';}?></div>
                  </div>
                </td>
                <td>
                  <div class="contenedor">
                    <div class="valor"  style="font-size: 12px;">R</div>
                    <div class="marca"><?php if($row->tipo_diagnostico=='Repetido'){echo 'X';}?></div>
                  </div>

                 </td>
                <td>
                  <div class="contenedor">
                    <div class="valor"  style="font-size: 12px;">I</div>
                      <div class="marca"><?php if($row->id_identificador_diag=='I'){echo 'X';}?></div>
                    </div>
                  </td>
                <td>
                  <div class="contenedor">
                    <div class="valor"  style="font-size: 12px;">E</div>
                      <div class="marca"><?php if($row->id_identificador_diag=='E'){echo 'X';}?></div>
                    </div>
                  </td>
                </td>
              </tr>
              <?php endforeach;
                endif;
              ?>
            </table>
          </div>
        </div>

        <div class="fila">
          <div class="celda titulo" colspan="14"> RESPONSABLE DE LA ATENCION</div>
        </div>
        <div class="fila">
          <div class="celda_plomo1 titulo" style="text-align: center; width: 50%;" >TIPO DOC</div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 50%;" colspan="2"> N° DE DOCUMENTO</div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 50%;"  colspan="6">NOMBRE DEL RESPONSABLE DE LA ATENCIÓN</div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 50%;" colspan="3">TIPO DE PROFESIONAL</div>
          <div class="celda_plomo1 titulo" style="text-align: center; width: 50%;" colspan="2">  N° DE COLEGIATURA</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 titulo" style="text-align: center; width: 1%;"><?php echo $datos['tipo_documento'];?></div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 5%;" colspan="2" ><?php echo $datos['dni'];?></div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 50%;"  colspan="6"><?php echo $datos['medico_responsable'];?></div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 50%;"  colspan="3"><?php echo $datos['tipo_profesion'];?></div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 50%;" rowspan="2"  colspan="2"><br>08873727<?php echo $datos['colegiatura'];?></div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 titulo" style="text-align: center; width: 10%;" colspan="2">ESPECIALIDAD</div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 5%;" colspan="4" ><?php echo $datos['especialidad'];?></div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 50%;"  colspan="4"> N° RNE</div>
          <div class="celda_blanco1 titulo" style="text-align: center; width: 50%;"  colspan="2"><?php echo $datos['n_rnie'];?> </div>
        </div>
        <div class="fila">
          <div class="celda_blanco1" style="padding-left: 25px;" colspan="14">
             <br><br> <br><br><br><br><br><br>
             <div class="celda_firma">
              FIRMA Y SELLO DEL RESPONSABLE DE LA ATENCIÓN <br><br>
           </div>
          </div>
        </div>
        <div class="fila">
          <div class="celda titulo" colspan="14"> PRODUCTOS FARMACEÚTICOS / MEDICAMENTOS</div>
        </div>
        {{-- CONSUMO DURANTE LA PRESTACIÓN DE SALUD
        PRODUCTOS FARMACEÚTICOS / MEDICAMENTOS --}}
        <div class="fila">
          <div class="celda_blanco1 " style="text-align: center; width: 5%;" colspan="14">
            <table style="width: 100%;" border="1">
              <tr>
                <td class="celda_plomo1">ID</td>
                <td class="celda_plomo1">CÓDIGO</td>
                <td colspan="7" class="celda_plomo1">NOMBRE</td>
                <td class="celda_plomo1">FORMA FARMACÉUTICA</td>
                <td class="celda_plomo1">CONCENTRACIÓN</td>
                <td class="celda_plomo1">PRESCRITO</td>
                <td class="celda_plomo1">ENTREGADO</td>
                <td class="celda_plomo1">DX</td>
              </tr>
              <?php 
              if(!empty($fups_productos_famaceuticos)):
              $n=1;
              foreach($fups_productos_famaceuticos as $r):?>
              <tr>
                <td class="celda_plomo1"><?php $n++;?></td>
                <td><?php echo $r->codigo;?></td>
                <td colspan="7"><?php echo $r->nombre;?></td>
                <td><?php echo $r->forma_farmaceutica;?></td>
                <td><?php echo $r->concentracion;?></td>
                <td><?php echo $r->preescrito;?></td>
                <td><?php echo $r->entregado;?></td>
                <td><?php echo $r->dx;?></td>
              </tr>
              <?php endforeach;
              endif;
              ?>
            </table>
          </div>
        </div>

        <div class="fila">
          <div class="celda titulo" colspan="14"> DISPOSITIVOS MÉDICOS / PRODUCTOS SANITARIOS</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 " style="text-align: center; width: 5%;" colspan="14">
            <table style="width: 100%;" border="1">
              <tr>
                <td class="celda_plomo1">ID</td>
                <td class="celda_plomo1">CÓDIGO</td>
                <td colspan="7" class="celda_plomo1">NOMBRE</td>
                <td class="celda_plomo1"> PRESENTACIÓN</td>
                <td class="celda_plomo1">CARACTERÍSTICA</td>
                <td class="celda_plomo1">PRESCRITO</td>
                <td class="celda_plomo1">ENTREGADO</td>
                <td class="celda_plomo1">DX</td>
              </tr>
              <?php 
              if(!empty($fups_dispositivos_medicos)):
              $n=1;
              foreach($fups_dispositivos_medicos as $r):?>
              <tr>
                <td class="celda_plomo1"><?php $n++;?></td>
                <td><?php echo $r->codigo;?></td>
                <td colspan="7"><?php echo $r->nombre;?></td>
                <td><?php echo $r->presentacion;?></td>
                <td><?php echo $r->caracteristica;?></td>
                <td><?php echo $r->preescrito;?></td>
                <td><?php echo $r->entregado;?></td>
                <td><?php echo $r->dx;?></td>
              </tr>
              <?php endforeach;
              endif;
              ?>
            </table>
          </div>
        </div>

        <div class="fila">
          <div class="celda titulo" colspan="14">  PROCEDIMIENTOS/DIAGNÓSTICO POR IMÁGENES/ LABORATORIO</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 " style="text-align: center; width: 5%;" colspan="14">
            <table style="width: 100%;" border="1">
              <tr>
                <td class="celda_plomo1">ID</td>
                <td class="celda_plomo1">CÓDIGO</td>
                <td colspan="9" class="celda_plomo1">  NOMBRE DEL PROCEDIMIENTO</td>
                <td class="celda_plomo1">INDICADO</td>
                <td class="celda_plomo1">EJECUTADO</td>
                <td class="celda_plomo1">DX</td>
              </tr>
              <?php 
              if(!empty($fups_procedimientos)):
              $n=1;
              foreach($fups_procedimientos as $r):?>
              <tr>
                <td class="celda_plomo1"><?php $n++;?></td>
                <td><?php echo $r->codigo;?></td>
                <td colspan="9"><?php echo $r->nombre_procedimiento;?></td>
                <td><?php echo $r->indicado;?></td>
                <td><?php echo $r->ejecutado;?></td>
                <td><?php echo $r->dx;?></td>
              </tr>
              <?php endforeach;
              endif;
              ?>
            </table>
          </div>
        </div>

        <div class="fila">
          <div class="celda titulo" colspan="14"> OBSERVACIONES</div>
        </div>
        <div class="fila">
          <div class="celda_blanco1 " style="text-align: left; height: 2%;" colspan="14">
            <br><?php echo $datos['observaciones'];?>
          </div>
        </div>
        <div class="fila">
          <div class="celda_blanco1" style="padding-left: 25px;" colspan="14">
             <br><br> <br><br><br><br><br><br>
             <div class="celda_firma">
              Firma y Sello del Responsable de 
            Procedimiento y/o Farmacia y/o Laboratorio
           </div>
          </div>
        </div>
        

    </div>
</body>

</html> 