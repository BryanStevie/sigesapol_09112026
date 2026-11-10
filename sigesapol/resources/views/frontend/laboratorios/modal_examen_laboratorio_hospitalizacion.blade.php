<style>
    .deco-red td {
        font-weight: bold;
        color: rgb(0, 0, 0);
        font-size: 1rem;
    }
    .deco-red{
        
        background: #f9b6b6 !important; 
        -webkit-box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8) !important;
        -moz-box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8) !important;
        box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8) !important;
    }
    .deco-red:hover{
        background: #f9b6b6 !important;
    }
    .deco-yellow td {
        font-weight: bold;
        color: rgb(0, 0, 0);
        font-size: 1rem;
    }
    .deco-yellow{
        background: #fbf5a1 !important;
        -webkit-box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8) !important;
        -moz-box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8) !important;
        box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8) !important;
    }
    .deco-yellow:hover{
        background: #fbf5a1 !important;
    }
    .deco-green td {
        font-weight: bold;
        color: rgb(255, 255, 255);
        font-size: 1rem;
    }
    .deco-green{
        background: #adddb6 !important;
        -webkit-box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8) !important;
        -moz-box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8) !important;
        box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8) !important;
    }
    .deco-green:hover{
        background: #adddb6 !important;
    }
    .deco-ce td {
        font-weight: bold;
        color: rgb(0, 0, 0);
        font-size: 1rem;
    }
    .deco-ce{
        -webkit-box-shadow: 2px 2px 5px -1px rgba(0,0,0,0.75) !important;
        -moz-box-shadow: 2px 2px 5px -1px rgba(0,0,0,0.75) !important;
        box-shadow: 2px 2px 5px -1px rgba(0,0,0,0.75) !important;
    }
    .deco-ce:hover{
        background: rgb(238, 238, 238) !important;
    }
    .shadow{
        /* -webkit-box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8);
        -moz-box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8);
        box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8); */
        -webkit-box-shadow: 2px 2px 7px -3px rgba(0,0,0,0.75);
        -moz-box-shadow: 2px 2px 7px -3px rgba(0,0,0,0.75); 
        box-shadow: 2px 2px 7px -3px rgba(0,0,0,0.75);
    }
    #mi-modal-reprogramar{
      width: 80% !important;
    }
    .font-iconos-tables{
    font-size: 10px;
    cursor: pointer;
    background-color: #c4e3c5;
    color: #2b620a;
    padding: 5px;
    border-radius: 3px;
    }
    .div-icon{
      position: absolute;
      right: 10px;
      top: 1px;
      bottom: 8px;
      display: flex;
      align-items: flex-start;
      align-content: stretch;
      flex-wrap: wrap;
    }
    .table > tbody > tr > td{
    padding: 2px;
    }
    
 
</style>

<script>
    $(document).ready(function () {
    $('#example').DataTable({
    "language": {
    "emptyTable": "No se encontraron resultados"
    }
    });
    $("#system-search").keyup(function() {
    var dataTable = $('#example').dataTable();
    dataTable.fnFilter(this.value);
    });

    function soloNumeros(e) {
    var key = window.Event ? e.which : e.keyCode;
    //alert(key);
    return ((key >= 48 && key <= 57) || key == 0 || key == 8)
    }

    });


      function modificarCita(id_sub_consultorio, fecha_cita, id_turno, adicional){
        var idCita = $("#id_cita_modificar").val();
        $.ajax({
          url: "/ReprogramarCitaLaboratorio/"+idCita+"/"+id_sub_consultorio+"/"+fecha_cita,
          method: "GET",
          success: function (response) {

            Swal.close();
            $('#btnBuscar').click();
            alert("Cita modificada");
          },
     
        });
        
      }
     function obtenerListadoHorario(dni){

      var fecha = $('#fecha_programacion').val();
      $.ajax({
        url: "/modal_cita_horario/76/113/"+fecha+"/"+dni+"/null",
        method: "GET",
        beforeSend: function() {
            // Código a ejecutar antes de enviar la solicitud
            $("#mostrar_horarios").html("Cargando datos...");
        },
        success: function (response) {

       
          $("#mostrar_horarios").html(response);
         
          $('.app-reserva-cita-hora-deparacion').each(function() {
              var onclickAttr = $(this).attr('onclick');
              if (onclickAttr) {
                  var nuevoOnclick = onclickAttr.replace('guardarCita', 'modificarCita');
                  $(this).attr('onclick', nuevoOnclick);
              }
          });
        },
        error: function () {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "No se pudo cargar el contenido."
            });
        }
      });
     }
    function reprogramar(idCita,dni){
      $("#id_cita_modificar").val(idCita);
      var hoy = new Date();
      var fechaHoy = hoy.toISOString().split('T')[0]; // Formato YYYY-MM-DD
      var nuevoRow = `
              <div class="row mb-2">
                  <div class="col-md-2">
                      <input type="date" class="form-control" name="fecha_programacion" id="fecha_programacion" value="${fechaHoy}" min="${fechaHoy}" />
                  </div>
                  <div class="col-md-6">
                      
                      <div class="btn btn-success" onclick="obtenerListadoHorario(${dni})"  >Buscar fecha disponible</div>
                  </div>
              </div>
              <div class="container">
                <div class="row mb-2">
                  <div class="col-12" id="mostrar_horarios" >
                  </div>
                </div>
              </div>
              
          `;
          Swal.fire({
              title: "Reprogramar cita",
              html: nuevoRow, // aquí metemos el HTML que trajimos
              showCancelButton: true,
              confirmButtonColor: "#3c763d",
              cancelButtonColor: "#d33",
              confirmButtonText: "OK",
              didOpen: () => {
                  const swalModal = document.querySelector('.swal2-popup');
                  if (swalModal) {
                      swalModal.setAttribute('id', 'mi-modal-reprogramar');
                  }

                  const swalConfirmButton = document.querySelector('.swal2-confirm');
                  if (swalConfirmButton) {
                      swalConfirmButton.style.display = 'none';
                  }
              }
          }).then((result) => {
              if (result.isConfirmed) {
                  // Aquí haces algo si confirma
              }
          });

    }

    // function verHorario(obj){
    //         let horarioContainer = $(obj).closest("ul").find(".horario-container");
    //         horarioContainer.slideToggle();
            
    //         let icon = $(obj).text() === "+" ? "-" : "+";
    //         $(this).text(icon);
    // }
    





    function validarFormatoEmail(email) {
    // Expresión regular para validar el formato de un correo electrónico
    var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
  }

  function fn_save_cita(id_medico, fecha_cita, id_turno, adicional) {

    /*var msg = "";
    var id_ipress = $('#id_ipress').val();
    var id_consultorio = $('#id_consultorio').val();
    var fecha_atencion = $('#fecha_atencion').val();
    let tipo_cita = $('#id_tipo_cita').val();
    let fecha_actual = moment();

    if(adicional==0){
      if (fecha_actual.diff(moment(fecha_cita).format("YYYY-MM-DD HH:mm"), 'minutes', true) >= 0) {
        msg += `Fecha de cita incorrecta, revisar que la hora sea mayor a la del sistema ${fecha_cita}<br>`;
      }  
    }


    var dni_beneficiario = $("#dni_beneficiario").val();
    if (dni_beneficiario == "") msg += "Debe ingresar el numero de documento <br>";
    if (id_ipress == "") {
      msg += "Debe ingresar una Ipress<br>";
    }
    if (id_consultorio == "") {
      msg += "Debe ingresar un Consultorio<br>";
    }
    if (fecha_atencion == "") {
      msg += "Debe ingresar una fecha de atencion<br>";
    }

    if (tipo_cita =="") {
      msg += "Debe ingresar un tipo de cita<br>";
    }

    if (msg != "") {
      bootbox.alert(msg);
      return false;
    }*/

    var correo = $('#correo').val();
    var msgLoader = "";
    let permisoParaEnviarCorreo = false;

    if (permisoParaEnviarCorreo) {
      if (correo.trim() == "") msgLoader = "Procesando, espere un momento por favor";
      else msgLoader = "Enviando correo, espere un momento por favor";
    } else {
      msgLoader = "Procesando, espere un momento por favor";
    }

    $('#btnCita').prop("disabled", true);

    var heightBrowser = $(window).width() + 500;

    $('.loader').css("opacity", "0.8").css("height", heightBrowser).html(
      "<div id='Grd1_wrapper' class='dataTables_wrapper'><div id='Grd1_processing' class='dataTables_processing panel-default'>" +
      msgLoader + "</div></div>");
    $('.loader').show();

    //var fecha_atencion_original = $('#fecha_atencion').val();
    var id_user = $('#id_user').val();
      
    $.ajax({
      url: "../registrar_cita",
      type: "POST",
      data: $("#frmCita").serialize() + "&id_consultorio=" + id_medico + "&fecha_cita=" + fecha_cita + "&id_turno=" + id_turno+ "&adicional=" + adicional,
      success: function(result) {
        console.log(result);
        $('#btnCita').prop("disabled", false);
        $('.loader').hide();
        location.href = "../ver_cita/" + id_user + "/" + result;
      }
    }); 
  }
  

  function getCitaConsultaExterna(idCita){
    $.ajax({
    url: '/ver_cita_laboratorio/'+idCita,
    type: 'GET', // o 'POST' según sea el caso
    success: function (response) {
        Swal.fire({
            title: 'Contenido dinámico',
          html: response, // contenido HTML devuelto por AJAX
          showCloseButton: true,
          showCancelButton: false,
          focusConfirm: false,
          confirmButtonText: 'Cerrar',
          customClass: {
              popup: 'swal-wide-cita' // aquí aplicas tu clase
          },
           didOpen: () => {
              $('.swal2-popup').css({
              'width': '1200px !important',
            });    // recomendado si deseas cambiar estilo
          }
        });
    },
    error: function () {
        Swal.fire('Error', 'No se pudo cargar el contenido.', 'error');
    }
});

  }
        
</script>
<div class="row">
    <div class="col-md-12" style="margin-top: 10px">
        <legend>
            <div class="pull-right">Total: {{$cantidad}} examen(es) encontrado(s)
            </div>
        </legend>
    </div>                

</div>
<div class="col-md-12" id="global">
    <input type="hidden" id = "id_cita_modificar" />
    <div class="table-responsive">
        <table id="example" class="table table-bordered" style="font-size: 12px;">
            <thead>
                <tr class="table-header">
                    <th style="width: 5px;">N</th>
                    <th>Paciente</th>
                    <th>Atención</th>
                    <th>Programación</th>
                    <th>Indicación de Toma de Muestra</th>
                    <th>Observaciones del Profesional</th>
                    <th>Toma de Muestra</th>
                    <th>Observaciones de la Muestra</th>
                    <th>Resultados</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $n = 0;

                    function calcularEdad($fechaNacimiento) {
                    if($fechaNacimiento == null) return "-";
                        $partes = explode('/', $fechaNacimiento);
                        if (count($partes) !== 3) return ['anios' => 0, 'meses' => 0, 'dias' => 0];
                        $dia = (int)$partes[0];
                        $mes = (int)$partes[1];
                        $anio = (int)$partes[2];

                        try {
                            $fechaNac = new DateTime("$anio-$mes-$dia");
                            $hoy = new DateTime();
                            $diferencia = $hoy->diff($fechaNac);
                            return [
                                'anios' => $diferencia->y,
                                'meses' => $diferencia->m,
                                'dias' => $diferencia->d
                            ];
                        } catch (Exception $e) {
                            return ['anios' => 0, 'meses' => 0, 'dias' => 0];
                        }
                    }
                ?>

                @foreach($listados as $r)
                    <tr>
                        <?php $n++; ?>
                        {{-- Numero --}}
                        <td>{{ $n }}</td>

                        {{-- Paciente --}}
                        <td style="position: relative; width: 275px;">
                            <div>{{ $r->nombre_beneficiario }}, {{ $r->paterno_beneficiario }} {{ $r->materno_beneficiario }}</div>
                            <div>{{ $r->tipo_doc_ident }} - {{ $r->nro_doc_ident }} - ({{ $r->sexo_beneficiario == "F" ? "FEMENINO" : "MASCULINO" }})</div>
                            <div>
                                <strong>Fech. Nac. </strong>{{ $r->fecha_nac_beneficiario }} -
                                <strong>Edad. </strong>
                                <?php
                                    $edad = calcularEdad($r->fecha_nac_beneficiario);
                                    echo "{$edad['anios']} A, {$edad['meses']} M y {$edad['dias']} D";
                                    
                                ?>
                                
                            </div>
                            <div><strong>Condición:</strong> {{ $r->referido == "1" ? "REFERIDO" : "NO REFERIDO" }}</div>
                        </td>

                        {{-- Atencion --}}
                        <td style="position: relative; width: 365px;">
                            <div>
                                <strong>N°:</strong> {{$r->id_lab}} - {{$r->cod_orden_lab }}  
                            </div>
                            <div><strong>Origen:</strong> {{ $r->id_evaluacion > 0 ? 'HOSPITALIZACIÓN - EVOLUCIÓN' : 'HOSPITALIZACIÓN' }}</div>
                            <div><strong>Especialidad:</strong> {{ $r->servicio_hospitalizacion }}</div>
                            <div>
                                <strong>Ubicación:</strong>
                                @if(isset($r->ubicacion_cama) && is_object($r->ubicacion_cama))
                                    {{$r->ubicacion_cama->pabellon}}<br>
                                    <strong>Piso:</strong> {{ $r->ubicacion_cama->piso }}
                                    <strong>Cuarto:</strong> {{ $r->ubicacion_cama->cuarto }}
                                    <strong>Cama:</strong> {{ $r->ubicacion_cama->cama }}
                                @endif
                            </div>
                            <div class="div-icon">
                            
                                <a href="/ver_laboratorio_imprimir/<?php echo $r->id_lab; ?>" target="_blank" class="btn btn-success btn-xs">
                                    <i class="fa fa-file-pdf-o" title="Ver Orden (RUA)" aria-hidden="true"></i>
                                </a>
                            </div>
                            @if (!is_null($r->fecha_registro_toma_muestra) && $r->cantidad_sr >= 1)
                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Sin Reactivo">
                                    <a href="/ver_lab_orden_sin_reactivo/{{ $r->id_lab }}" target="_blank" class="btn btn-success btn-xs">
                                        <i class="fa fa-file-text-o" title="Ver RUA Sin Reactivo" aria-hidden="true"> RUA Sin Reactivo</i>
                                    </a>
                                </div>
                            @endif
                        </td>

                        {{-- Programacion --}}
                        <td style="position: relative; width: 240px;">
                            <div><strong>Fecha de orden:</strong> {{ $r->fecha_creacion }}</div>
                            <div><strong>Fecha de firma:</strong> {{ $r->fecha_firma }}</div>
                            <div>
                                <strong>Estado:</strong>
                                <?php 
                                    if ($r->fecha_firma == null) {
                                        echo "Pendiente";
                                    } else {
                                        echo "Atendido";
                                    }
                                ?>
                            </div>
                        </td>

                        {{-- Indicación de Toma de Muestra --}}
                        <td>{{ $r->hora_toma_muestra }}</td>

                        {{-- Observaciones del Profesional --}}
                        <td style="min-width: 150px;">
                            @if ($r->has_observacion)
                                <b>Observaciones Profesional:</b> {{ $r->observacion }}
                            @else
                                <b>Observaciones Profesional:</b> {{ str_replace("#|#", ", ", $r->medico_observaciones) }}
                            @endif
                        </td>

                        {{-- Toma de muestra --}}
                        <td style="width: 200px;">
                            <div style="position: relative;">
                                @if (is_null($r->estado_asistencia_paciente))
                                    <div>
                                        <div><strong>Fecha:</strong> {{$r->fecha_registro_toma_muestra ==null ? "Pendiente": $r->fecha_registro_toma_muestra}}</div>
                                        <div><b>Usuario 1:</b> {{ $r->usuario_toma_muestra }}<div>
                                        <div><strong>Estado:</strong>  {{$r->fecha_registro_toma_muestra ==null ? "Pendiente": "Atendido"}}</div>
                                        @if ($r->fecha_lastupdate_tomamuestra != null)
                                            <div><strong>Fecha segunda toma:</strong> {{$r->fecha_lastupdate_tomamuestra ==null ? "Pendiente": $r->fecha_lastupdate_tomamuestra}}</div>
                                            <div><b>Usuario 2:</b> {{ $r->usuario_update_toma_muestra }}</div>
                                        @endif
                                        <div style="margin: 5px;"></div>
                                        @if (!is_null($r->fecha_registro_toma_muestra))
                                            <div><b>Enviados:</b> {{ $r->cantidad_enviado }}</div>
                                            <div><b>Pendientes:</b> {{ $r->cantidad_pendiente }}</div>
                                            <div><b>Sin Reactivo:</b> {{ $r->cantidad_sr }}</div>
                                        @endif
                                    </div>
                                    <div class="div-icon">
                                        @if ($estado == "6")
                                            <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-check-square"></i></button>
                                        @else
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" >
                                                
                                                @if ($r->fecha_registro_toma_muestra !=null)
                                                    <?php
                                                        $esDeHoy = false;

                                                        if (!empty($r->fecha_registro_toma_muestra)) {
                                                            try {
                                                                $fechaTexto = trim(preg_replace('/[\x00-\x1F\x7F]/u', '', $r->fecha_registro_toma_muestra));
                                                                $fechaRegistro = \Carbon\Carbon::createFromFormat('d/m/Y h:i A', $fechaTexto);
                                                                $esDeHoy = $fechaRegistro->isToday();
                                                            } catch (\Exception $e) {
                                                                $esDeHoy = false;
                                                            }
                                                        }
                                                    
                                                    ?>
                                                    @if($r->fecha_lastupdate_tomamuestra == null)
                                                        <button class="btn btn-warning btn-xs" 
                                                            @if(true)
                                                                onclick="mofificarPruebasEnviadasLNS({{ $r->id_lab }}, {{ $tipo_atencion }})" 
                                                                title="Registrar toma de muestra {{ $tipo_atencion }}"
                                                                @else
                                                                style="background-color: gray; border: 0px solid;"
                                                                title="No se puede editar"
                                                                @endif
                                                                
                                                        ><i class="fa fa-edit"></i></button>
                                                    @endif
                                                @else
                                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="Registrar toma de muestra">
                                                        <a href="/formulario_atencion_laboratorio_lns/{{$r->id_lab}}/{{ $tipo_atencion }}" class="btn btn-success btn-xs"><i class="fa fa-edit "></i></a>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif                            
                                    </div>
                                @else
                                    <div><b>Estado: <span style="color:red">{{ intval($r->estado_asistencia_paciente) === 2 ? "Observado" : "Anulado" }}</span></b></div>
                                    <div><b>Fecha: <span style="color:red">{{ $r->fecha_anulacion }}</span></b></div>
                                    <div><b>Motivo: <span style="color:red">{{ $r->motivo_anulacion }}</span></b></div>
                                @endif
                            </div>

                        </td>

                        {{-- Observaciones de la Muestra --}}
                        <td style="width: 200px;">
                            <b>Observación 1:</b> {{ $r->observacion_toma_muestra }}<br>
                            <b>Observación 2:</b> {{ $r->obs_lastupdate_tomamuestra }}
                        </td>

                        {{-- Resultado --}}
                        <td style="position: relative;">
                            @if (is_null($r->estado_asistencia_paciente) && !is_null($r->fecha_registro_toma_muestra))
                                <div>
                                    <b>Estado:</b>
                                    <br>
                                    <span>
                                        <?php
                                        if($r->estado_lab == 0){
                                            echo "Pendiente por migrar";
                                        }
                                        else if($r->estado_lab == 1){
                                            if (intval($r->cantidad_pendiente) == 0) {
                                                echo "Completado";
                                            } else {
                                                echo "Con resultados: " . $r->cantidad_enviado . " de " . $r->cantidad_total;
                                            }
                                        }else{
                                            echo "Procesando";
                                        }
                                    ?>
                                    </span>
                                </div>
                                <div>
                                    @if($r->estado_lab == 2)
                                        <b>Detalle: </b><span>{{$r->orden_info_detallado}}</span>
                                    @endif
                                </div>
                                
                                @if($r->estado_lab != 0 && $r->cantExamenConResultado > 0)
                                    <div class="div-icon">
                                        <a target="_blank" href="/print_examen_laboratorio_lns/0/{{$r->nro_doc_ident}}/00/00/00/{{$r->cod_orden_lab == null ? "Pendiente" :$r->cod_orden_lab }}" class="btn btn-xs @if($r->estado_lab == 1) btn-success @else btn-warning @endif"><i class="fa fa-eye" title="Ver Resultado" aria-hidden="true"></i></a>
                                    </div>
                                @endif
                            @endif
                        </td>

                        {{-- Acciones --}}
                        <td style="text-align: center">
                            @if (is_null($r->fecha_registro_toma_muestra))
                                <?php
                                    $estado_muestra = "0";
                                    if (is_null($r->estado_asistencia_paciente) && !is_null($r->fecha_registro_toma_muestra)):
                                        $estado_muestra = "1"; // Orden con toma de muestra
                                    else:
                                        $estado_muestra = "0"; // Orden sin toma de muestra
                                    endif;
                                ?>                            
                                @if (is_null($r->estado_asistencia_paciente))
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="Anular">
                                        <button 
                                            style="margin-right: 5px;"
                                            id="btnEstadoOrdenLab" 
                                            data-accion="anular"
                                            data-estado-toma-muestra = "{{ $estado_muestra }}"
                                            data-tipo-atencion = "{{ $tipo_atencion }}"
                                            data-id="{{$r->id_lab}}"
                                            <?php /*echo ($r->diff_horas_desde_toma_muestra != null ) ? "disabled" : "";*/ ?> 
                                            class="btn btn-danger btn-xs shadow"
                                        ><i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="Observar">
                                        <button
                                            style="margin-right: 5px; display:none;"
                                            id="btnObservarOrdenLab"
                                            data-accion="observar"
                                            data-estado-toma-muestra = "{{ $estado_muestra }}"
                                            data-tipo-atencion = "{{ $tipo_atencion }}"
                                            data-id="{{$r->id_lab}}"
                                            class="btn btn-warning btn-xs shadow"
                                        ><i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                    @if($r->diff_horas_desde_toma_muestra != null && $r->cantidad_sr >= 1)
                                        <button class="btn btn-xs btn-success" style="display:none" onclick="fusionarPDFsDesdeURLs('/ver_laboratorio_imprimir/{{$r->id_lab}}','/laboratorio_ver_rua_sr/{{$r->id_lab}}')"><i class="fa fa-eye" title="Ver RUA (SR)"></i></button>
                                    @endif
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

              <table class="table table-list-search" style="font-size: 11px !important;display: none;" >
                  <thead>
                      <tr>
                          <th>N</th>
                          @if ($tipo_atencion == 2)
                              <th>Prioridad</th>
                          @endif
                          
                          <th>Nro. Ex. Lab</th>
                          <th>Tipo Atencion</th>
                          <th>Especialidad</th>
                          <th>Farmacia</th>
                          @if($tipo_atencion ==1)
                            <th>Registro Lab</th>
                            <th>Fecha cita<br/>Lab</th>
                          @endif
                          <th>Fec. Firmado</th>
                          @if ($tipo_atencion == 1)
                              <th>Fecha<br/>Cita Próxima del Servicio</th>
                          @endif
                          {{-- @if ($tipo_atencion == 1)
                              <th>Hora<br/>Cita Próxima</th>
                              <th>Fecha<br/>Cita Próxima</th>
                          @endif --}}
                          <th>Nro. Doc.</th>
                          <th>Beneficiario</th>
                          <th>Medico</th>
                          <th>Referido</th>
                          @if ($tipo_atencion == 2)
                              <th>Pabellón</th>
                              <th>Piso</th>
                              <th>Habitación</th>
                              <th>Cama</th>
                          @endif
                          <th style="text-align:center">Pdf Examen</th>
                          <th style="text-align:center">Atender ln</th>
                          <th style="text-align:center">Atender</th>
                          <th style="text-align:center">Anular</th>
                          @if ($tipo_atencion == 1)
                          <th style="text-align:center">Reprogramar</th>
                          @endif                    
                      </tr>
                  </thead>
                  <tbody>
                      <?php $n = 0; ?>
                      @foreach($listados as $r)
                      @if ($r->id_tipo_atencion == 1 || $r->id_tipo_atencion == 3 || $r->fecha_registro_toma_muestra != null)
                          <tr class="deco-ce">
                      @else
                          @if ($r->estado_emergencia == 0)
                              <tr class="deco-red">
                          @elseif ($r->estado_emergencia == 1)
                              <tr class="deco-yellow">
                          @elseif ($r->estado_emergencia == 2)
                              <tr class="deco-green">
                          @endif
                      @endif
                          {{-- N --}}
                          <td 
                          @if($r->id_tipo_atencion ==1) id_prestacion ="{{$r->id_prestacion}}" id_cita_laboratorio_ce="{{$r->id_cita_laboratorio_ce}}" id_cita_lab= "{{$r->id_cita_lab}}" id_cita= "{{$r->id_cita}}" @endif ><?php
                              $n++;
                              echo $n;
                              ?></td>
                          {{-- Solo Emergencia Prioridad --}}
                          @if ($tipo_atencion == 2)
                              <td>
                                  <?php
                                      if ($r->id_tipo_atencion == 2) {
                                          echo $r->nom_prioridad;
                                      } else {
                                          echo "&nbsp";
                                      }
                                  ?>
                              </td>
                          @endif
                          <td>{{$r->id_lab}}</td>{{-- Nro. Ex. Lab --}}
                          <td>{{-- Tipo Atencion --}}
                              @if ($r->id_tipo_atencion == 1)
                                  {{$r->nom_tipo_atencion}}
                              @endif
                              @if ($r->id_tipo_atencion == 2)
                                  @if ($r->id_evaluacion > 0)
                                      EMERGENCIA - EVALUACIÓN
                                  @else
                                      {{$r->nom_tipo_atencion}}
                                  @endif
                              @endif
                              @if ($r->id_tipo_atencion == 3)
                                  @if ($r->id_evaluacion > 0)
                                      HOSPITALIZACIÓN - EVOLUCIÓN
                                  @else
                                      HOSPITALIZACIÓN
                                  @endif
                              @endif
                          </td>
                          {{-- Especialidad --}}
                          <td><?php
                                  if ($r->id_tipo_atencion == 1) {
                                      echo $r->consultorio;
                                  } elseif ($r->id_tipo_atencion == 2) {
                                      echo $r->nom_especialidad_emergencia;
                                  } elseif ($r->id_tipo_atencion == 3) {
                                    echo $r->servicio_hospitalizacion;                             
                                  } else {
                                      echo $r->consultorio;
                                  }
                              ?>
                          </td>
                          <td>
                              {{ $r->nombre_farmacia }}
                          </td>
                          @if($r->id_tipo_atencion ==1)
                            <td>{{-- Registro laboratorio --}}
                                <?php
                                if($r->id_tipo_atencion ==1){
                                  $originalDate1 = $r->fecha_creacion_cita_lab;
                                  $fechaE = date("d/m/Y H:i:s A", strtotime($originalDate1));
                                  echo $fechaE;
                                }
                                
                                ?> 
                            </td>
                          
                          
                            <td>{{-- Hora / Registro cita lab --}}
                              <?php
                              $horaR1 = "";
                              if($r->id_tipo_atencion ==1){
                                $originalDate3 = $r->fecha_hora_cita_laboratorio;
                                $horaR1 = date("d/m/Y H:i:s A", strtotime($originalDate3));
                              }
          
                              echo $horaR1;
                              ?> 
                            </td>
                          @endif
                          <td>{{-- Fecha / firma --}}
                            <?php
                            //$originalDate1 = $r->created_at;
                            echo date("d/m/Y h:i:s A", strtotime($r->fecha_firma));
                            ?> 
                        </td>
                          {{-- Solo CE --}}
                          @if ($tipo_atencion == 1)
                              
                              {{-- Fecha / Cita Proxima --}}
                              @if (isset($r->id_cita_lab) && !empty($r->id_cita_lab))
                                  <?php
                                  $originalFechaCita = $r->fecha_hora;
                                  $FechaCita = date("d/m/Y h:i:s A", strtotime($originalFechaCita));
                                  echo "<td>" . $FechaCita . "</td>";
                                  ?>
                              @else
                                  <?php
                                      echo "<td>&nbsp</td>"
                                  ?>
                              @endif
                          @endif
                          
                          <td>{{$r->tipo_doc_ident}} <br>{{$r->nro_doc_ident}}</td> {{-- Nro. Doc. --}}
                          <td>{{$r->paterno_beneficiario}} {{$r->materno_beneficiario}}, {{$r->nombre_beneficiario}} </td>   {{-- Beneficiario --}}
                          <!--td></td--> 
                          <td>
                              @if ($r->id_tipo_atencion == 2)
                                  @if ($r->id_evaluacion > 0)
                                      {{$r->medico_evaluacion}}
                                  @else
                                      {{$r->paterno_medico}} {{$r->materno_medico}}, {{$r->nombre_medico}}
                                  @endif
                              @else
                                  {{$r->paterno_medico}} {{$r->materno_medico}}, {{$r->nombre_medico}}
                              @endif
                          </td>{{-- Medico --}}
                          <td> {{-- Referido --}}
                              {{-- REFERIDO DE EMERGENCIA PENDIENTE --}}
                              <?php 
                                  if ($r->id_tipo_atencion == 1) {
                                      echo $r->referido; 
                                  } elseif ($r->id_tipo_atencion == 2) {
                                      echo $r->unidad_policial == null ? "NO" : "SI"; 
                                  } else {
                                      
                                  }
                              ?>
                          </td>
                          {{-- Solo Emergencia --}}
                          @if ($tipo_atencion == 2)
                              <td> {{-- Pabellón --}}
                                  <?php
                                      if ($r->id_tipo_atencion == 2) {
                                          echo $r->pabellon;
                                      } else {
                                          echo "&nbsp";
                                      }
                                  ?>
                              </td>
                              <td> {{-- Piso --}}
                                  <?php
                                      if ($r->id_tipo_atencion == 2) {
                                          echo $r->piso;
                                      } else {
                                          echo "&nbsp";
                                      }
                                  ?>
                              </td>
                              <td> {{-- Habitación --}}
                                  <?php
                                      if ($r->id_tipo_atencion == 2) {
                                          echo $r->cuarto;
                                      } else {
                                          echo "&nbsp";
                                      }
                                  ?>
                              </td>
                              <td> {{-- Cama --}}
                                  <?php
                                      if ($r->id_tipo_atencion == 2) {
                                          echo $r->cama;
                                      } else {
                                          echo "&nbsp";
                                      }
                                  ?>
                              </td>
                          @endif
                          <td class="text-center"> {{-- Pdf Examen --}}
                            <?php 
                            if ($r->ruta_laboratorio == "") {
                            ?>
                              <button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-search"></i></button>
                            <?php 
                            } else {
                            ?>
                              <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver todos los pdf">
                                  <button type="button" class="btn btn-success btn-xs shadow" onclick="detalleAtencionLaboratorio('{{$r->id_tipo_atencion}}','{{$r->id_lab}}','{{$r->id_prestacion}}')"><i class="fa fa-search"></i></button>
                              </div>
                            <?php 
                            }
                            ?>
                          </td>
                          {{-- Atender ln --}}
                          <!-- LABORATORIO LNS -->
                          <td class="text-center" >
                              <?php 
                              
                              if ($r->id_establecimiento == 76):
                                  if ($r->ruta_laboratorio == ""):
                                  // dd($r);
                              ?>
                                      <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-check-square" ></i></button>
                              <?php 
                                  else:
                                      //Pediente
                                      if($r->fecha_registro_toma_muestra == null): 
                              ?>
                                      @if ($tipo_atencion == "1")    
                                          @if ($estado == "6"/*Estado No Atendido*/)
                                              <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-check-square"></i></button>
                                          @else
                                              <div data-toggle="tooltip" data-placement="top" data-html="true" title="Registrar toma de muestra">
                                                  <button onClick="validarCitaProgramada('{{$r->fecha_hora_cita_laboratorio}}',{{$r->id_lab}},{{$r->id_tipo_atencion}},<?php echo $r->cantidad == "" ? "0": $r->cantidad ?>,{{$r->id_asegurado}}, <?php echo $r->id_cita_d == null ? "0": $r->id_cita_d ?> )" class="btn btn-success btn-xs shadow"><i class="fa fa-check-square"></i> {{$r->cantidad  ==0 ? "1":$r->cantidad}}</button>
                                              </div>
                                          @endif
                                      @else
                                          @if ($estado == "6"/*Estado No Atendido*/)
                                              <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-check-square"></i></button>
                                          @else
                                              <div data-toggle="tooltip" data-placement="top" data-html="true" title="Registrar toma de muestra">
                                                  <a href="/formulario_atencion_laboratorio_lns/{{$r->id_lab}}/{{$r->id_tipo_atencion}}" class="btn btn-success btn-xs shadow"><i class="fa fa-check-square"></i></a>
                                              </div>
                                          @endif
                                      @endif
                                          
                              <?php 
                                      else:
                                      //Atendidos 
                              ?>
                                      @if ($tipo_atencion == "1")    
                                          @if ($estado == "6"/*Estado No Atendido*/)
                                              <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-check-square"></i></button>
                                          @else
                                              <div data-toggle="tooltip" data-placement="top" data-html="true" title="Resgitrado">
                                                  <button onClick="validarCitaProgramadaModificar('{{$r->fecha_hora_cita_laboratorio}}',{{$r->id_lab}},{{$r->id_tipo_atencion}})" class="btn btn-warning btn-xs" ><i class="fa fa-edit"></i></button>
                                              </div>
                                          @endif
                                      @else
                                          @if ($estado == "6"/*Estado No Atendido*/)
                                              <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-check-square"></i></button>
                                          @else
                                              <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Editar Toma<br>de muestra</b>">
                                                  <button onClick="mofificarPruebasEnviadasLNS({{$r->id_lab}},{{$r->id_tipo_atencion}})" <?php echo ($r->diff_horas_desde_toma_muestra != null && $r->diff_horas_desde_toma_muestra >= 24) ? "disabled" : ""; ?> class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></button>
                                              </div>
                                          @endif
                                      @endif
                              <?php 
                                      endif;
                                  endif;
                              endif;
                              ?>
                          </td>
                          <!-- FIN LABORATORIO LNS -->
                          <td class="text-center">
                              
                              <?php 
                              if ($r->id_establecimiento != 76):
                              if ($r->ruta_laboratorio == "") {
                              ?>
                                  <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-edit"></i></button>
                              <?php 
                              } else {
                              ?>      <table>
                                      <tr><td>
                                      <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b> Bioquimica</b>">
                                      <?php 
                                          if($r->tipo_bioquimica=='SI'):
                                              if($r->rpta_bioquimica=='SI'):
                                      ?>
                                                  &nbsp;<a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/1" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                      <?php 
                                              else: ?>
                                                  &nbsp;<a href="/formulario_atencion_laboratorio/{{$r->id_lab}}/1" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                      <?php   endif;
                                          endif;
                                      ?>
                                      </div>
                                      </td>
                                      <td>
                                          <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Hematologia</b>">
                                      <?php
                                          if($r->tipo_hematologia=='SI'):
                                              if($r->rpta_hematologia=='SI'):
                                      ?>
                                                  &nbsp;<a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/7" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                      <?php 
                                              else: ?>
                                                  &nbsp;<a href="/formulario_atencion_laboratorio/{{$r->id_lab}}/7" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                      <?php   endif;
                                          endif;
                                      ?>
                                      </div>
                                      </td>
                                      <td>
                                          <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Inmunologia</b>">
                                      <?php
                                          if($r->tipo_inmunologia=='SI'):
                                              if($r->rpta_inmunologia=='SI'):
                                      ?>
                                                  &nbsp;<a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/4" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                      <?php 
                                              else: ?>
                                                  &nbsp;<a  href="/formulario_atencion_laboratorio/{{$r->id_lab}}/4" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                      <?php   endif;
                                          endif;
                                      ?>
                                      </div>
                                      </td>
                                      <td>
                                          <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Microbiologia</b>">
                                      <?php
                                          if($r->tipo_microbiologia=='SI'):
                                              if($r->rpta_microbiologia=='SI'):
                                      ?>
                                                  &nbsp;<a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/9" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                      <?php 
                                              else: ?>
                                                  &nbsp;<a  href="/formulario_atencion_laboratorio/{{$r->id_lab}}/9" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                      <?php   endif;
                                          endif;
                                      ?>
                                      </div>
                                      </td>
                                      <td>
                                          <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anatomia Patologica</b>">
                                      <?php
                                          if($r->tipo_anatomia=='SI'):
                                              if($r->rpta_anatomia=='SI'):
                                      ?>
                                                  &nbsp;<a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/12" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                      <?php 
                                              else: ?>
                                                  &nbsp;<a href="/formulario_atencion_laboratorio/{{$r->id_lab}}/12" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                      <?php   endif;
                                          endif;
                                      ?>
                                      </div>
                                      </td>
                                      <td>
                                          <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Citopatologia</b>">
                                      <?php
                                          if($r->tipo_citopatologia=='SI'):
                                              if($r->rpta_citopatologia=='SI'):
                                      ?>
                                                  &nbsp;<a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/12" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                      <?php 
                                              else: ?>
                                                  &nbsp;<a href="/formulario_atencion_laboratorio/{{$r->id_lab}}/12" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                      <?php   endif;
                                          endif;
                                      ?>
                                      </div>
                                      </td>
                                      <td>
                                          <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Citogenetico</b>">
                                      <?php
                                          if($r->tipo_citogenetico=='SI'):
                                              if($r->rpta_citogenetico=='SI'):
                                      ?>
                                                  &nbsp;<a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/12" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                      <?php 
                                              else: ?>
                                                  &nbsp;<a  href="/formulario_atencion_laboratorio/{{$r->id_lab}}/12" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                      <?php   endif;
                                          endif;
                                      ?>
                                      </div>
                                      </td>
                                      <td>
                                          <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Quirurgica</b>">
                                      <?php
                                          if($r->tipo_quirurgica=='SI'):
                                              if($r->rpta_quirurgica=='SI'):
                                      ?>
                                                  &nbsp;<a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/12" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                      <?php 
                                              else: ?>
                                                  &nbsp;<a  href="/formulario_atencion_laboratorio/{{$r->id_lab}}/12" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                      <?php   endif;
                                          endif;
                                      ?>
                                      </div>
                                      </td>
                                      <td>
                                          <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Otro</b>">
                                      <?php
                                          if($r->tipo_otro=='SI'):
                                              if($r->rpta_otro=='SI'):
                                      ?>
                                                  &nbsp;<a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/12" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                      <?php 
                                              else: ?>
                                                  &nbsp;<a  href="/formulario_atencion_laboratorio/{{$r->id_lab}}/12" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                      <?php   endif;
                                          endif;
                                      ?>
                                      </div>
                                      </td>
                                      <td>
                                          <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Reproductivo</b>">
                                      <?php
                                          if($r->tipo_reproductiva=='SI'):
                                              if($r->rpta_reproductiva=='SI'):
                                      ?>
                                                  &nbsp;<a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/12" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                      <?php 
                                              else: ?>
                                                  &nbsp;<a href="/formulario_atencion_laboratorio/{{$r->id_lab}}/12" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                      <?php   endif;
                                          endif;
                                      ?>
                                      </div>
                                      </td>
                                      <td>
                                          <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Covid-19</b>">
                                      <?php
                                          if($r->tipo_covid=='SI'):
                                              if($r->rpta_covid=='SI'):
                                      ?>
                                                  &nbsp;<a target="_blank" href="/print_examen_laboratorio/{{$r->id_lab}}/12" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                      <?php 
                                              else: ?>
                                                  &nbsp;<a href="/formulario_atencion_laboratorio/{{$r->id_lab}}/12" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                      <?php   endif;
                                          endif;
                                      ?>
                                      </div>
                                      </td>
                                      </tr>
                                      </table>
                                      <?php
                                  }
                                  endif;
                              ?>
                          </td>
                          <td>
                              @if ($estado == "1"  || $estado == "3")
                                  <div data-toggle="tooltip" data-placement="top" data-html="true" title="Editar estado de la orden">
                                      <button 
                                          id="btnEstadoOrdenLab" 
                                          data-tipo-atencion = "{{ $tipo_atencion }}" 
                                          data-id-
                                          @if ($tipo_atencion == 1) data-id="{{$r->id_cita_laboratorio_ce}}" @else data-id="{{$r->id_lab}}" @endif
                                          <?php echo ($r->diff_horas_desde_toma_muestra != null ) ? "disabled" : ""; ?> 
                                          class="btn btn-danger btn-xs shadow"
                                      >
                                          <i class="fa fa-edit"></i>
                                      </button>
                                  </div>
                              @else
                                  <button class="btn btn-danger btn-xs shadow" disabled=""><i class="fa fa-edit"></i></button>
                              @endif
                          </td>
                          @if ($tipo_atencion == "1")
                            <td>
                              <div class="btn btn-success btn-xs "  data-idestado = "{{$r->estado}}"
                              <?php
                                  echo ($r->diff_horas_desde_toma_muestra != null)
                                      ? 'disabled'
                                      : 'onclick="reprogramar(' . $r->id_cita_laboratorio_ce . ', \'' . $r->nro_doc_ident . '\')"';
                                  ?>
                              >Reprogramar</div>
                            </td>
                          @endif
                          
                      </tr>
                      @endforeach                      
                  </tbody>
              </table> 
    </div>
</div>

