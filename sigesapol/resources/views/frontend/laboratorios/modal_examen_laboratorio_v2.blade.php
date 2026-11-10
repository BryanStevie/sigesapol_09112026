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
                    title: '',
                    html: response, // contenido HTML devuelto por AJAX
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    confirmButtonText: 'CERRAR',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    },
                    buttonsStyling: false,
                    didOpen: () => {
                        document.querySelector('.swal2-popup').style.setProperty('width', '500px', 'important');
                    }
                });
            },
            error: function () {
                Swal.fire('Error', 'No se pudo cargar el contenido.', 'error');
            }
        });
    }

    function VerOrdenes(ids,servicios) {
        // 1. Convertir string a array
        var idArray = ids.split(',').map(function (id) {
            return id.trim();
        });
        var idArray_servicios = servicios.split(',').map(function (id) {
            return id.trim();
        });

        // 2. Crear HTML de los links
        var htmlLinks = '<ul style="text-align: left;">';
        idArray.forEach(function (id,index) {
            htmlLinks += '<li><a href="/ver_laboratorio_imprimir/' + id + '" target="_blank">' +
                            '<i class="fa fa-search font-iconos-tables" title="Ver Orden (RUA)" aria-hidden="true"></i> Ver Orden ' + idArray_servicios[index] +
                        '</a></li>';
        });
        htmlLinks += '</ul>';

        // 3. Mostrar SweetAlert
        Swal.fire({
            title: 'Órdenes disponibles',
            html: htmlLinks,
            width: 300,
            confirmButtonText: 'Cerrar'
        });
    }



    function validarCitaProgramadaModificarConsultaExterna(fechaCitaLaboratorio, id_laboratorio, id_tipo_atencion,id_dependiente=0,idPaciente=0) {
        let fecha = new Date(fechaCitaLaboratorio);
        let hoy = new Date();

        fecha.setHours(0, 0, 0, 0);
        hoy.setHours(0, 0, 0, 0);

        if (true) { // puedes reemplazar con: fecha.getTime() === hoy.getTime()
            const ids = id_laboratorio.split(',').map(id => id.trim()).filter(id => id !== "");

            Swal.fire({
                title: "Desea modificar la toma de muestra de las pruebas de laboratorio?",
                showCancelButton: true,
                confirmButtonColor: "#3c763d",
                cancelButtonColor: "#d33",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    if (ids.length === 1) {
                        // Solo un ID
                        location.href = "formulario_edicion_atencion_laboratorio_lns/" + ids[0] + "/" + id_tipo_atencion;
                    } else {
                        // Más de un ID
                        window.open("laboratorio_emergencia_editar/" + idPaciente + "/" + id_dependiente, '_blank');
                    }
                }
            });

        } else {
            Swal.fire({
                icon: 'error',
                title: `No puedes editar si la cita no está programada para el día de hoy.`,
                showConfirmButton: false,
                timer: 3000
            });
        }
    }
</script>
<div class="row">
    <div class="col-md-12" style="margin-top: 10px">
        <legend>
            <div class="pull-right">Total: {{$cantidad}} examen(es) encontrado(s)</div>
        </legend>
    </div>
</div>
<div class="col-md-12" id="global">
    <input type="hidden" id = "id_cita_modificar" />
    <div class="table-responsive">
        <table id="example" class="table table-bordered" style="font-size: 12px;">
            <thead>
                <tr class="table-header">
                    <th style="width: 8px;">N</th>
                    <th>Paciente</th>
                    <th>Orden de laboratorio</th>
                    <th>Cita</th>
                    <th>Observaciones del Profesional</th>
                    <th>Toma de Muestra</th>
                    <th>Observaciones de la Muestra</th>
                    <th>Resultados</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php $n = 0;
                function calcularEdad($fechaNacimiento) {
                    // Convertir la fecha de formato DD/MM/YYYY a un objeto DateTime
                    $partes = explode('/', $fechaNacimiento);
                    $dia = (int)$partes[0];
                    $mes = (int)$partes[1];
                    $anio = (int)$partes[2];
                    
                    $fechaNac = new DateTime("$anio-$mes-$dia");
                    $hoy = new DateTime('now');
                    
                    // Calculamos la diferencia entre las fechas
                    $diferencia = $hoy->diff($fechaNac);
                    
                    // Retornamos un array con los años, meses y días
                    return array(
                        'anios' => $diferencia->y,
                        'meses' => $diferencia->m,
                        'dias' => $diferencia->d
                    );
                }
                ?>
                @foreach ( $listados as $r )
                    <tr>
                        {{-- Correlativo --}}
                        <td > <?php $n++; echo $n; ?> </td>
                        
                        {{-- Paciente --}}
                        <td style="width: 275px;">
                            <div> <?php echo $r->nombre_beneficiario . ", ".  $r->paterno_beneficiario ." ".  $r->materno_beneficiario ?></div>
                            <div>  {{$r->tipo_doc_ident}} - {{$r->nro_doc_ident}} - ({{$r->sexo_beneficiario == "F" ? "FEMENINO":"MASCULINO"}})</div>
                            <div> <b>Fech. Nac.</b>  {{$r->fecha_nac_beneficiario}} -
                                <b>Edad.</b><?php
                                $edad = calcularEdad($r->fecha_nac_beneficiario);
                                echo $edad['anios'] . " A, " . $edad['meses'] . " M y " . $edad['dias'] . " D";
                                ?>
                            </div>
                            <div><b>Condición:</b> {{$r->referido == "NO" ? "NO REFERIDO":"REFERIDO"}}</div>
                        </td>

                        {{-- Orden de Laboratorio --}}
                        <td style="position: relative; width: 255px;">
                            <div><b>N°:</b> {{$r->id_lab}} - {{$r->cod_orden_lab }}</div>
                            <div><b>Origen:</b> {{$r->nom_tipo_atencion}}</div>
                            <div><b>Servicio:</b> {{$r->consultorio}}</div>

                            <div class="div-icon">
                                @if ($r->cantidad > 1)
                                    <div onclick="VerOrdenes('<?php echo $r->id_lab; ?>','{{$r->consultorio}}')" class="btn btn-success btn-xs">
                                        <i class="fa fa-file-pdf-o" title="Ver Orden (RUA)" aria-hidden="true"></i>
                                    </div>
                                @else
                                    <a href="/ver_laboratorio_imprimir/<?php echo $r->id_lab; ?>" target="_blank" class="btn btn-success btn-xs">
                                        <i class="fa fa-file-pdf-o" title="Ver Orden (RUA)" aria-hidden="true"></i>
                                    </a>
                                @endif

                                <!-- <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver todos los pdf">
                                    <button type="button" class="btn btn-success btn-xs shadow" onclick="detalleAtencionLaboratorio('{{$r->id_tipo_atencion}}','{{$r->id_lab}}','{{$r->id_prestacion}}')"><i class="fa fa-search"></i></button>
                                </div> -->
                            </div>

                            @if (!is_null($r->fecha_registro_toma_muestra) && $r->cantidad_sr >= 1)
                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Sin Reactivo">
                                    <a href="/ver_lab_orden_sin_reactivo/{{ $r->id_lab }}" target="_blank" class="btn btn-success btn-xs">
                                        <i class="fa fa-file-text-o" title="Ver RUA Sin Reactivo" aria-hidden="true"> RUA Sin Reactivo</i>
                                    </a>
                                </div>
                            @endif
                        </td>
                    
                        {{-- Cita --}}
                        <td style="position: relative;width: 240px;">
                            <div><b>Fecha:</b> {{$r->fecha_hora_cita_laboratorio }}</div>
                            <div><b>Consultorio:</b> 
                                <div title="{{$r->cita_nombre_sub_consultorio}}">
                                    <?php
                                    $texto_recortado = substr($r->cita_nombre_sub_consultorio, 0, 30);
                                    if (strlen($r->cita_nombre_sub_consultorio) > 30) {
                                        $texto_recortado .= "...";
                                    }
                                    echo $texto_recortado;
                                    ?>
                                </div>
                                
                            </div>
                                <?php 
                                // if($r->fecha_registro_toma_muestra == null && $r->estado_asistencia_paciente == null){
                                //   echo "Pendiente";
                                // }else{
                                //   echo "Atendido";
                                // }
                            ?> 
                            <div class="div-icon">

                                <button class="btn btn-success btn-xs" onclick="getCitaConsultaExterna({{$r->id_cita_laboratorio_ce}})">
                                <i class="fa fa-eye" title="Ver cita" aria-hidden="true"></i>
                                </button>
                            </div>
                        </td>

                        {{-- Observaciones del Profesional --}}
                        <td style="min-width: 150px;">
                            <b>Observaciones Profesional:</b> {{ $r->observacion }}
                        </td>

                        {{-- Toma de muestra --}}
                        <td>
                            <div style="position: relative;">
                                @if (is_null($r->estado_asistencia_paciente))
                                    <div>
                                        <div>
                                            <b>Fecha:</b> {{$r->fecha_registro_toma_muestra ==null ? "Pendiente": $r->fecha_registro_toma_muestra}}
                                        </div>
                                        <div>
                                            <b>Usuario 1:</b> {{ $r->usuario_toma_muestra }}
                                        </div>
                                        <div>
                                            <b>Estado:</b> {{$r->fecha_registro_toma_muestra ==null ? "Pendiente": "Atendido"}}
                                        </div>

                                        @if ($r->fecha_lastupdate_tomamuestra != null)
                                            <div>
                                                <b>Fecha segunda toma:</b> {{$r->fecha_lastupdate_tomamuestra ==null ? "Pendiente": $r->fecha_lastupdate_tomamuestra}}
                                            </div>
                                            <div>
                                                <b>Usuario 2:</b> {{ $r->usuario_update_toma_muestra }}
                                            </div>
                                        @endif

                                        <div style="margin: 5px;"></div>
                                        
                                        @if (!is_null($r->fecha_registro_toma_muestra))
                                            <div><b>Enviados:</b> {{ $r->cantidad_enviado }}</div>
                                            <div><b>Pendientes:</b> {{ $r->cantidad_pendiente }}</div>
                                            <div><b>Sin Reactivo:</b> {{ $r->cantidad_sr }}</div>
                                        @endif
                                    </div>

                                    <div class="div-icon">
                                        <div data-toggle="tooltip" data-placement="top" data-html="true">
                                            @if ($r->fecha_registro_toma_muestra !=null)
                                                @if($r->fecha_lastupdate_tomamuestra == null)
                                                    <button class="btn btn-warning btn-xs" 
                                                        @if(true)
                                                            onclick="mofificarPruebasEnviadasLNS({{ $r->id_lab }}, 1)" 
                                                            title="Registrar toma de muestra"
                                                            @else
                                                            style="background-color: gray; border: 0px solid;"
                                                            title="No se puede editar"
                                                            @endif
                                                            
                                                    ><i class="fa fa-edit"></i></button>
                                                @endif
                                            @else
                                                @if (intval($r->cantidad == 1))
                                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="Registrar toma de muestra">
                                                        <a href="/formulario_atencion_laboratorio_lns/{{$r->id_lab}}/{{ $tipo_atencion }}" class="btn btn-success btn-xs"><i class="fa fa-edit "></i> {{ $r->cantidad == 0 ? "1" : $r->cantidad }}</a>
                                                    </div>
                                                @elseif (intval($r->cantidad > 1))
                                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="Registrar toma de muestra">
                                                        <button onClick="validarCitaProgramada('{{$r->fecha_hora_cita_laboratorio}}', 0, 1, {{$r->cantidad == '' ? '0': $r->cantidad}}, {{$r->id_asegurado}},{{$r->id_cita_d == null ? '0': $r->id_cita_d}})" class="btn btn-success btn-xs shadow"><i class="fa fa-edit"></i> {{$r->cantidad}}</button>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                        
                                    </div>
                                @else
                                    <div><b>Estado: <span style="color:red">Anulado</span></b></div>
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

                        {{-- resultado --}}
                        <td style="position: relative;">
                            @if (is_null($r->estado_asistencia_paciente))
                                <div>
                                    <b>Estado:</b>
                                    <span>
                                        <?php
                                            if($r->estado_lab == 0){
                                                echo "Pendiente por migrar";
                                            }
                                            else if($r->estado_lab == 1){
                                                echo "Completado";
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
                                        data-estado-toma-muestra = "{{ $estado_muestra }}"
                                        data-tipo-atencion = "{{ $tipo_atencion }}" 
                                        @if ($tipo_atencion == 1) data-id="{{$r->id_cita_laboratorio_ce}}" @else data-id="{{$r->id_lab}}" @endif
                                        <?php /*echo ($r->diff_horas_desde_toma_muestra != null ) ? "disabled" : "";*/ ?> 
                                        class="btn btn-danger btn-xs shadow"
                                    ><i class="fa fa-trash"></i>
                                    </button>
                                </div>
                                
                                @if($r->diff_horas_desde_toma_muestra != null && $r->cantidad_sr >= 1)
                                    <button class="btn btn-xs btn-success" style="display:none" onclick="fusionarPDFsDesdeURLs('/ver_laboratorio_imprimir/{{$r->id_lab}}','/laboratorio_ver_rua_sr/{{$r->id_lab}}')"><i class="fa fa-eye" title="Ver RUA (SR)"></i></button>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>  

    </div>
</div>

