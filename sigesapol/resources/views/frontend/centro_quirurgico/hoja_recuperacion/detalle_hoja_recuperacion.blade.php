<script>

  /****************FUNCIONES PARA LA FIRMA***************************/
  async function getArguments() {
    arg = document.getElementById("argumentos").value;
    await dispatchEventClient('sendArguments', arg);
  }
  async function ObtieneArgumentosParaFirmaDesdeLaWebHojaRecuperacion() {
    var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    $.post("{{route('hoja_recuperacion_post_arguments')}}", {
    _token: CSRF_TOKEN,
    type: "W",
    documentName: $('#doc_firmar').val()
    }, function(data, status) {
      document.getElementById("argumentos").value = data;
      getArguments();
    });
  }
  async function actualiza_datos_firma_con_token_io_hr() {
    var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    let id_resol = $('#id_hoja_recuperacion').val();
    console.log(id_resol);
    $.post("{{route('actualiza_datos_firma_con_token_io_hr')}}", {
      _token: CSRF_TOKEN,
      id_resol: id_resol,
    }, function(data, status) {
      location.reload();
    });
  }

  $(document).on('click', '#btnFinalizarHojaRecuperacion', function(e) {
    e.preventDefault();
    ActualizaHojaRecuperacionSinTokens();
  })
  const ActualizaHojaRecuperacionSinTokens = () => {
    var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    let id_resol = $('#id_hoja_recuperacion').val();
    console.log(id_resol);
    $.post("{{route('actualiza_datos_firma_sin_token_io')}}", {
      _token: CSRF_TOKEN,
      id_resol: id_resol,
    }, function(data, status) {
      location.reload();
    });
  }
  $(document).ready(function() {
    $(document).on('click', '#btnFirmaHojaRecuperacion', function(e) {
      e.preventDefault();
      firmarHojaRecuperacion();
    })

    const firmarHojaRecuperacion = () => {
      const id_hoja_recuperacion = $('#id_hoja_recuperacion').val();
      $.ajax({
          url: `/hoja_recuperacion_firmar/${id_hoja_recuperacion}`,//bh_firmar_por_turno
          type: 'POST',
          data: $(this).serialize(),
          dataType: 'json'
      })
      .done(function(resultado) {
        console.log(resultado);
        if(resultado){
          if (resultado.sw) {
              var csv = "<?php echo url()?>/tmp/csv/" + resultado.csv + ".csv";
              $("#doc_firmar").val(`${resultado.csv}.7z`);
              initInvoker('W');
          }
        }else{
          Swal.fire({
              title: "Información de Servicio de Firma",
              text: "No se puede procesar la información",
              icon: "info",
              timer: 4000,
              showConfirmButton: true
          });
        }
      })
      .fail(function(xhr, status, error) {
          console.log('Error:', error);
      })
      .always(function() {
          console.log('Esta función siempre se ejecuta, éxito o error');
      });
    }
  });
</script>
<style>
  body{
    font-size: 11px !important;
  }
  @page {
    margin-left: 0.5in;
    margin-right: 0.5in;
    margin-top: 0.15in;
    margin-bottom: 0.15in;
    size: 8.6in 14in;
  }

  td {
      padding-left: 2px !important;
  }

  table {
      font-size: 11px;
      font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
  }

  h2 {
      font-size: 15px !important
  }

  .logo {
      width: 15%;
  } 

  footer {
      border: 0px;
      margin-left: 10px;
      font-size: 8px !important;
      font-family: Arial, Helvetica, sans-serif
  }
  .row {
      display: flex;
      justify-content: space-between;
  }
</style>

<form id="FormularioHojaRecuperacion2" name="FormularioHojaRecuperacion2" enctype="multipart/form-data" method="POST">
  <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
  <input type="hidden" id="id_hoja_recuperacion" value="{{$hoja_recuperacion['id']}}">
  <input type="hidden" id="id_resol" value="{{$hoja_recuperacion['id']}}">
  <input type="hidden" id="argumentos"/>
  <input type="hidden" id="doc_firmar" />
  <input type="hidden" name="flag_firma" id="flag_firma" value="{{$hoja_recuperacion['flag_firma']}}">
  <input type="hidden" name="flag_firma_centro_quirurgico" id="flag_firma_centro_quirurgico" value="2">

  <div class="nav-tabs-custom">
      <div class="tab-content" style="padding: 30px;">
        <table style="margin:0px;padding:0px;" >
          <tr>
            <td width="100%" style="vertical-align:top;" colspan="2">
              <span style="display: block; font-size: 1.1rem; text-align: left; margin: 0; font-weight:bold;">
                <b>I.-PACIENTE:</b> 
                {{ $datos_paciente_intervencion['paciente']}}</span>
               
            </td> 
          </tr>
          <tr>
            <td width="20%">
              <span style="display: block; font-size: 1.1rem; padding-left: 5px; text-align: left; margin: 0;">
                <b>EDAD:</b>  {{ $datos_paciente_intervencion['fec_nacim_descrip']}}</span> 
            </td>
            <td width="20%">
              <span style="display: block; margin: 0; font-size: 0.9rem; padding-left: 15px;text-transform: uppercase;">
               <b> CAMA: </b> {{$datos_paciente_intervencion['cama']}} </span>
            </td>
            <td width="20%">
              <span style="display: block; margin: 0; font-size: 0.9rem; padding-left: 15px;text-transform: uppercase;">
               <b> SALA: </b> {{ $datos_paciente_intervencion['sala']}} </span>
            </td>
          </tr>
          <tr>
            <td width="100%" style="padding-left: 10px; vertical-align:top;" colspan="3">
              <h6 style="text-align: left;"><b>DIAGNOSTICO OPERATORIO:</b></h6>
              <ul style="list-style: none; padding: 0;">
                  <!-- Encabezados para la lista -->
                  <li style="margin-bottom: 1px; padding: 1px 0; border-bottom: 1px solid #ddd; font-weight: bold;">
                      <span style="width: 20%; display: inline-block;">Código</span>
                      <span style="width: 40%; display: inline-block;">Nombre</span>
                      <span style="width: 20%; display: inline-block;">Tipo</span>
                  </li>
                  <!-- Datos de cada diagnóstico -->
                  <?php if(!empty($diagnostico_operatorio)): ?>
                    <?php foreach($diagnostico_operatorio as $key=> $row): ?>
                    <li style="margin-bottom: 1px; padding: 1px 0; border-bottom: 1px solid #ddd;">
                        <span style="width: 20%; display: inline-block;">{{$key+1}}. {{ $row->codigo }}</span>
                        <span style="width: 40%; display: inline-block;">{{ $row->nombre }}</span>
                        <span style="width: 20%; display: inline-block;">{{ $row->descripcion }}</span>
                    </li>
                    <?php endforeach; ?>
                  <?php endif; ?>
              </ul>
            </td> 
          </tr>
          <tr>
            <td width="100%" style="vertical-align:top;" colspan="3">
              <span style="display: block; font-size: 1.1rem;text-align: left; margin: 0;">
                <b>ANTECEDENTES:</b> </span>
                <ul style="list-style: none; padding: 0;">
                  <li style="margin-bottom: 1px; padding: 1px 0; border-bottom: 1px solid #ddd;">
                    <span style="width: 100%; display: inline-block;">1. {{ $datos_paciente_intervencion['antecedente_personal'] }}</span>
                  </li>
                  <li style="margin-bottom: 1px; padding: 1px 0; border-bottom: 1px solid #ddd;">
                    <span style="width: 100%; display: inline-block;">2. {{ $datos_paciente_intervencion['antecedente_familiar'] }}</span>
                  </li>
                </ul>
            </td> 
          </tr>
          <tr>
            <td width="100%" style="padding-left: 10px; vertical-align:top;" colspan="3">
              <h6 style="text-align: left;"><b>TIPO DE ANESTESIA:</b></h6>
              <ul style="list-style: none; padding: 0;">
                @if(!empty($tipos_anestesia_aq) && (is_array($tipos_anestesia_aq) || is_object($tipos_anestesia_aq)))
                    <ul style="list-style: none; padding: 0;">
                        @foreach($tipos_anestesia_aq as $key => $row)
                            <li style="margin-bottom: 1px; padding: 1px 0; border-bottom: 1px solid #ddd;">
                                <span style="width: 20%; display: inline-block;">{{ $key + 1 }}. {{ $row->descripcion }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
              </ul>
            </td> 
          </tr>
          <tr>
            <td width="100%" style="padding-left: 10px; vertical-align:top;" colspan="3">
              <h6 style="text-align: left;"><b>CIRUJANOS:</b></h6>
              <ul style="list-style: none; padding: 0;">
                  <!-- Encabezados para la lista -->
                  <li style="margin-bottom: 1px; padding: 1px 0; border-bottom: 1px solid #ddd; font-weight: bold;">
                      <span style="width: 70%; display: inline-block;">Nombre</span>
                      <span style="width: 20%; display: inline-block;">Tipo</span>
                  </li>
                  <!-- Datos de cada diagnóstico -->
                  @if(!empty($cirujanos) && (is_array($cirujanos) || is_object($cirujanos)))
                    @foreach($cirujanos as $key=> $row)
                    <li style="margin-bottom: 1px; padding: 1px 0; border-bottom: 1px solid #ddd;">
                        <span style="width: 70%; display: inline-block;">{{$key+1}}. {{ $row->medico }}</span>
                        <span style="width: 20%; display: inline-block;">{{ $row->tipo }}</span>
                    </li>
                    @endforeach
                  @endif
              </ul>
            </td> 
          </tr>
          <tr>
            <td width="100%" style="padding-left: 10px; vertical-align:top;" colspan="3">
              <h6 style="text-align: left;"><b>ANESTESIOLOGOS:</b></h6>
              <ul style="list-style: none; padding: 0;">
                  <!-- Encabezados para la lista -->
                  <li style="margin-bottom: 1px; padding: 1px 0; border-bottom: 1px solid #ddd; font-weight: bold;">
                      <span style="width: 70%; display: inline-block;">Nombre</span>
                      <span style="width: 20%; display: inline-block;">Tipo</span>
                  </li>
                  <!-- Datos de cada diagnóstico -->
                  @if(!empty($anestesiologos) && (is_array($anestesiologos) || is_object($anestesiologos)))
                    @foreach($anestesiologos as $key=> $row)
                    <li style="margin-bottom: 1px; padding: 1px 0; border-bottom: 1px solid #ddd;">
                        <span style="width: 70%; display: inline-block;">{{$key+1}}. {{ $row->medico }}</span>
                        <span style="width: 20%; display: inline-block;">{{ $row->tipo }}</span>
                    </li>
                    @endforeach
                  @endif
              </ul>
            </td> 
          </tr>
        </table>
        <br>
        {{-- BALANCE HIDRICO --}}
        <b>II. BALANCE HIDRICO</b><br>
        <div style="display: flex; justify-content: center; width: 100%; margin: 0 auto; border: 1px solid #000; padding: 10px;">
          <table style="margin: 0 auto;">
            <tr>
              <td style="vertical-align: top;">
                <table class="table table-striped">
                  <thead>
                      <tr>
                          <td colspan="6" style="font-weight: bold;">INGRESOS</td>
                      </tr>
                      <tr>
                          <td style="font-weight: bold;">DESCRIPCIÓN</td>
                          <td style="font-weight: bold;">M</td>
                          <td style="font-weight: bold;">T</td>
                          <td style="font-weight: bold;">N</td>
                          <td style="font-weight: bold;">MD</td>
                          <td style="font-weight: bold;">TOTAL</td>
                      </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $total_m=0;
                      $total_t=0;
                      $total_n=0;
                      $total_md=0;
                      $totales=0;
  
                      $total_m2=0;
                      $total_t2=0;
                      $total_n2=0;
                      $total_md2=0;
                      $totales2=0;
                      foreach($egresos as $egreso){
                        $total_m2 += intval($egreso->total_m); 
                        $total_t2 += intval($egreso->total_t); 
                        $total_n2 += intval($egreso->total_n); 
                        $total_md2 += intval($egreso->total_md);
                        $totales2 += intval($egreso->total_m) + intval($egreso->total_t) + intval($egreso->total_n) + intval($egreso->total_md);
                      }
                    foreach($ingresos as $ingreso){
                      $total_m += intval($ingreso->total_m); 
                      $total_t += intval($ingreso->total_t); 
                      $total_n += intval($ingreso->total_n); 
                      $total_md += intval($ingreso->total_md);
                      $totales += intval($ingreso->total_m) + intval($ingreso->total_t) + intval($ingreso->total_n) + intval($ingreso->total_md);
                    ?>
                        <tr>
                            <td style="text-align: left;">{{ $ingreso->caracter_fisico }}</td>
                            <td>{{ intval($ingreso->total_m) }}</td>
                            <td>{{ intval($ingreso->total_t) }}</td>
                            <td>{{ intval($ingreso->total_n) }}</td>
                            <td>{{ intval($ingreso->total_md) }}</td>
                            <td>{{ intval($ingreso->total_m) + intval($ingreso->total_t) + intval($ingreso->total_n) + intval($ingreso->total_md) }}</td>
                        </tr>
                    <?php } ?>
                    <tr>
                      <td style="text-align: left;" colspan="6">&nbsp;</td>
                    </tr>
                    <tr>
                      <td style="text-align: left;" colspan="6">&nbsp;</td>
                    </tr>
                    <tr>
                      <td style="text-align: left;" colspan="6">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><b>TOTAL</b></td>
                        <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_m; ?></td>
                        <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_t; ?></td>
                        <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_n; ?></td>
                        <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_md; ?></td>
                        <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $totales; ?></td>
                    </tr>
                    <tr>
                      <td style="text-align: left;"><b>BALANCE POR TURNOS</b></td>
                      <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_m-$total_m2; ?></td>
                      <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_t-$total_t2; ?></td>
                      <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_n-$total_n2; ?></td>
                      <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_md-$total_md2; ?></td>
                      <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $totales-$totales2; ?></td>
                  </tr>
                  </tbody>
              </table>
              </td>
              <td></td>
              <td>
                <table class="table table-striped">
                  <thead>
                      <tr>
                          <td colspan="6" style="font-weight: bold;">EGRESOS</td>
                      </tr>
                      <tr>
                          <td style="font-weight: bold;">DESCRIPCIÓN</td>
                          <td style="font-weight: bold;">M</td>
                          <td style="font-weight: bold;">T</td>
                          <td style="font-weight: bold;">N</td>
                          <td style="font-weight: bold;">MD</td>
                          <td style="font-weight: bold;">TOTAL</td>
                      </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $total_m2_egreso=0;
                    $total_t2_egreso=0;
                    $total_n2_egreso=0;
                    $total_md2_egreso=0;
                    $totales2_egreso=0;
                  foreach($egresos as $egreso){
                    $total_m2_egreso += intval($egreso->total_m); 
                    $total_t2_egreso += intval($egreso->total_t); 
                    $total_n2_egreso += intval($egreso->total_n); 
                    $total_md2_egreso += intval($egreso->total_md);
                    $totales2_egreso += intval($egreso->total_m) + intval($egreso->total_t) + intval($egreso->total_n) + intval($egreso->total_md);
                  ?>
                      <tr>
                          <td style="text-align: left;">{{ $egreso->caracter_fisico }}</td>
                          <td>{{ intval($egreso->total_m) }}</td>
                          <td>{{ intval($egreso->total_t) }}</td>
                          <td>{{ intval($egreso->total_n) }}</td>
                          <td>{{ intval($egreso->total_md) }}</td>
                          <td>{{ intval($egreso->total_m) + intval($egreso->total_t) + intval($egreso->total_n) + intval($egreso->total_md) }}</td>
                      </tr>
                  <?php } ?>
                  <tr>
                    <td style="text-align: left;" colspan="6">&nbsp;</td>
                  </tr>
                  <tr>
                    <td style="text-align: left;" colspan="6">&nbsp;</td>
                  </tr>
                  <tr>
                      <td style="text-align: left;"><b>TOTAL</b></td>
                      <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_m2_egreso; ?></td>
                      <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_t2_egreso; ?></td>
                      <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_n2_egreso; ?></td>
                      <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $total_md2_egreso; ?></td>
                      <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $totales2_egreso; ?></td>
                  </tr>
                  <tr>
                    <td style="text-align: left;"><b>BH 24 HORAS</b></td>
                    <td style="border-top: 1px solid black;">-</td>
                    <td style="border-top: 1px solid black;">-</td>
                    <td style="border-top: 1px solid black;">-</td>
                    <td style="border-top: 1px solid black;">-</td>
                    <td style="font-weight: bold; border-top: 1px solid black;"><?php echo $totales-$totales2; ?></td>
                </tr>
                  </tbody>
              </table>
              </td>
            </tr>
            
          </table>
        </div>
        <br>
        <b style="font-weight: bold;">III. TRATAMIENTO</b><br>
        <div style="border: 1px solid #000; padding: 10px;">
          {{ $hoja_recuperacion->descripcion_tratamiento }}
        </div>
        <br>
        <b style="font-weight: bold;">IV. FUNCIONES VITALES</b><br>
        <div style="width: 100%;">
          <table   class="table">
           
                <tr>
                  @if(!empty($encabezado_fv) && (is_array($encabezado_fv) || is_object($encabezado_fv)))
                    @foreach ($encabezado_fv as $encabezado)
                        <th style="background-color: #f2f2f2; color: #030202; text-align: center; padding: 5px; border: 1px solid #070606; vertical-align: middle;">
                            {{ $encabezado->descripcion_corta }}
                        </th>
                    @endforeach
                  @endif
                </tr>
            
                @if(!empty($funciones_vitales) && (is_array($funciones_vitales) || is_object($funciones_vitales)))
                  @foreach ($funciones_vitales as $fila)
                      <tr style="text-align: center; page-break-inside: avoid;">
                          @foreach ($fila['registros'] as $registro)
                              @if ($registro['secuencia'] == 1)
                                  <td style="padding: 5px; border: 1px solid #070606; vertical-align: middle;">
                                      {{ $registro['hora_registro'] }}
                                  </td>
                              @else
                                  <td style="padding: 5px; border: 1px solid #070606; vertical-align: middle;">
                                      {{ $registro['valor_caracter_fisico'] }}
                                  </td>
                              @endif
                          @endforeach
                      </tr>
                  @endforeach
                @endif
            
          </table>
        </div>
      
      
        <br>
        <b style="font-weight: bold;">V. DIAGNOSTICOS DE ENFERMERIA</b><br>
        <fieldset>
          <table width="100%"  style="text-align: left;">
            <tr>
              <td style="padding: 10px;  text-align: left;">1.- PATRÓN RESPIRATORIO INEFICAZ RELACIONADO CON DOLOR QUE SE EVIDENCIA POR DISNEA:</td>
              <td style="padding: 10px;  text-align: left;"><input type="checkbox" id="campo1_diagnostico_enfermeria" name="campo1_diagnostico_enfermeria" @if($hoja_recuperacion['campo1_diagnostico_enfermeria']==1) checked @endif disabled></td>
            </tr>
            <tr>
              <td style="padding: 10px;  text-align: left;"> 2.- LIMPIEZA INEFICAZ DE LAS VÍAS AÉREAS RELACIONADO CON LA RETENCIÓN DE LAS SECRECIONES SECUNDARIO A<br>
                ESTADO DE INCONSCIENCIA:</td>
              <td style="padding: 10px;  text-align: left;"><input type="checkbox" id="campo2_diagnostico_enfermeria" name="campo2_diagnostico_enfermeria" @if($hoja_recuperacion['campo2_diagnostico_enfermeria']==1) checked @endif disabled></td>
            </tr>
            <tr>
              <td style="padding: 10px;  text-align: left;"> 3.- TERMORREGULACIÓN INEFICAZ RELACIONADO CON TEMPERATURA DEL AMBIENTE LUCTUANTE:</td>
              <td style="padding: 10px;  text-align: left;"> <input type="checkbox" id="campo3_diagnostico_enfermeria" name="campo3_diagnostico_enfermeria" @if($hoja_recuperacion['campo3_diagnostico_enfermeria']==1) checked @endif disabled></td>
            </tr>
          </table>
        </fieldset>
      </div>
  </div>
  <div class="modal-footer">
    <table width="100%">
      <tr>
        <td>
          @if($hoja_recuperacion['flag_firma']===1)
          <a href="/{{$hoja_recuperacion['ruta_firma']}}" target="_blank" data-toggle="tooltip" title="PDF Firmado" 
          class="btn btn-xs" 
          style="border: 2px solid #337ab7; padding: 5px 10px; border-radius: 4px; display: inline-block;">PDF FIRMADO
           <i class="fa fa-pencil" aria-hidden="true"></i>
           <i class="fa fa-download" aria-hidden="true"></i>
          </a>
          @elseif($hoja_recuperacion['flag_firma']===2)
            <a href="/hoja_recuperacion_pdf/{{$hoja_recuperacion['id']}}" target="_blank"  data-toggle="tooltip" title="PDF sin Firma"
            class="btn btn-xs" style="border: 2px solid #337ab7; padding: 5px 10px; border-radius: 4px; display: inline-block;"> PDF SIN FIRMA <i class="fa fa-download"></i>
            </a>
          @else
            @if (Auth::user()->status_token==1)
              <a class="btn btn-info" id="btnFinalizarHojaRecuperacion">
                <i class="fa-solid fa-file-signature"></i> FINALIZAR
              </a>
            @else 
                <a class="btn btn-info" id="btnFirmaHojaRecuperacion">
                  <i class="fa-solid fa-file-signature"></i> FIRMAR
                </a>
            @endif
          @endif
          
        </td>
        <td>
          <div><button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button></div>
        </td>
      </tr>
    </table>
  </div>
</form>