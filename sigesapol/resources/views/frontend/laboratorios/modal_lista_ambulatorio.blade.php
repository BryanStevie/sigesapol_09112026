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

    function VerOrdenesSinReactivo(ids, servicios, cantidades) {
        // 1. Convertir string a array
        var idArray = ids.split(',').map(function (id) {
            return id.trim();
        });
        var idArray_servicios = servicios.split(',').map(function (id) {
            return id.trim();
        });
        let id_array_cantidades = cantidades.split(',').map(function (id) {
            return id.trim();
        });

        // 2. Crear HTML de los links
        var htmlLinks = '<ul style="text-align: left;">';
        idArray.forEach(function (id,index) {
            if (parseInt(id_array_cantidades[index]) > 0) {
                htmlLinks += '<li><a href="/ver_lab_orden_sin_reactivo/' + id + '" target="_blank">' +
                            '<i class="fa fa-search font-iconos-tables" title="Ver Orden (RUA)" aria-hidden="true"></i> Ver Orden Sin Reactivo ' + idArray_servicios[index] + 
                        '</a></li>';
            }
        });
        htmlLinks += '</ul>';

        // 3. Mostrar SweetAlert
        Swal.fire({
            title: 'Órdenes Sin Reactivo',
            html: htmlLinks,
            width: 300,
            confirmButtonText: 'Cerrar'
        });
    }

    function textoEstadoResultadoOrden(orden) {
        if (parseInt(orden.estado_lab) === 0) {
            return 'Pendiente por migrar';
        }
        if (parseInt(orden.estado_lab) === 1) {
            if (parseInt(orden.cantidad_pendiente) === 0) {
                return 'Completado';
            }
            return 'Con resultados: ' + orden.cantidad_enviado + ' de ' + orden.cantidad_total;
        }
        return 'Procesando';
    }

    function VerOrdenesResultados(ordenes, nroDocIdent) {
        var htmlLinks = '<ul style="text-align: left; list-style: none; padding-left: 0;">';

        ordenes.forEach(function (orden) {
            var estadoTexto = textoEstadoResultadoOrden(orden);
            var btnClass = parseInt(orden.estado_lab) === 1 ? 'btn-success' : 'btn-warning';
            htmlLinks += '<li style="margin-bottom: 10px;">';
            htmlLinks += '<b>' + orden.cod_orden_lab + '</b><br>';
            htmlLinks += '<span>' + estadoTexto + '</span> ';

            if (parseInt(orden.estado_lab) === 2 && orden.orden_info_detallado) {
                htmlLinks += '<br><b>Detalle: </b><span>' + orden.orden_info_detallado + '</span>';
            }

            if (parseInt(orden.estado_lab) !== 0 && parseInt(orden.cantExamenConResultado) > 0) {
                var codOrden = orden.cod_orden_lab ? orden.cod_orden_lab : 'Pendiente';
                htmlLinks += '<a target="_blank" href="/print_examen_laboratorio_lns/0/' + nroDocIdent + '/00/00/00/' + codOrden + '" ' +
                    'class="btn btn-xs ' + btnClass + '" style="margin-left: 6px;">' +
                    '<i class="fa fa-eye" title="Ver Resultado" aria-hidden="true"></i></a>';
            }

            htmlLinks += '</li>';
        });

        htmlLinks += '</ul>';

        Swal.fire({
            title: 'Resultados por orden',
            html: htmlLinks,
            width: 420,
            confirmButtonText: 'Cerrar'
        });
    }

    function validarCitaProgramadaParaRegistroTomaMuestra(fechaCitaLaboratorio, id_laboratorio, id_tipo_atencion, cantidad =0,idPaciente = 0,idCita=0){
        if (cantidad == 0 || cantidad == 1 ){
            location.href="formulario_atencion_laboratorio_lns/" + id_laboratorio + "/" + id_tipo_atencion;
        }else{
            location.href="laboratorio_agrupado/" + idPaciente+"/"+ idCita;
        }
    }

    function validarCitaProgramadaParaEdicionTomaMuestra(fechaCitaLaboratorio, id_laboratorio, id_tipo_atencion,id_dependiente=0,idPaciente=0) {
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
                    window.open("laboratorio_agrupado_editar/" + idPaciente + "/" + id_dependiente, '_blank');
                }
            }
        });
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
                <?php $n = 0; ?>
                @foreach ($listadosAgrupados as $id_cita_agrupado => $ordenes)
                    <?php 
                        $primera_fila = $ordenes[0]; 
                        $cantidad_ordenes = count($ordenes);

                        $ids_orden_lab_texto = "";
                        $servicios_texto = "";
                        $cantidad_sr_por_orden_texto = "";
                        $alguna_orden_con_toma_muestra = false;
                        $total_cantidad_sr_grupo = 0;

                        foreach($ordenes as $i => $orden):
                            $ids_orden_lab_texto .= $orden->id_orden_lab;
                            $servicios_texto .= $orden->servicio;
                            $cantidad_sr_por_orden_texto .= $orden->cantidad_sr;
                            $total_cantidad_sr_grupo += intval($orden->cantidad_sr);

                            if (!is_null($orden->fecha_toma_muestra)):
                                $alguna_orden_con_toma_muestra = true;
                            endif;

                            if ($cantidad_ordenes - 1 > $i):
                                $ids_orden_lab_texto .= ",";
                                $servicios_texto .= ",";
                                $cantidad_sr_por_orden_texto .= ",";
                            endif;
                        endforeach;
                    ?>
                    <tr>
                        {{-- Correlativo --}}
                        <td > <?php $n++; echo $n; ?> </td>
                        
                        {{-- Paciente --}}
                        <td style="width: 275px;">
                            <div>{{ $primera_fila->paciente }}</div>
                            <div>{{$primera_fila->tipo_doc_ident}} - {{$primera_fila->nro_doc_ident}} - ({{$primera_fila->sexo == "F" ? "FEMENINO":"MASCULINO"}})</div>
                            <div>
                                <b>Fech. Nac.</b> {{$primera_fila->fecha_nac}} - <b>Edad.</b> {{ $primera_fila->edad }}
                            </div>
                            <div><b>Condición:</b> {{$primera_fila->referido == "NO" ? "NO REFERIDO":"REFERIDO"}}</div>
                        </td>

                        {{-- Orden de Laboratorio --}}
                        <td style="position: relative; width: 255px;">
                            
                            <div>
                                <b>N°:</b>
                                @foreach($ordenes as $r)
                                {{ $r->id_orden_lab }} - {{ $r->cod_orden_lab }}<br>
                                @endforeach
                            </div>
                            <div><b>Origen:</b> AMBULATORIO</div>
                            <div>
                                <b>Servicios:</b> 
                                @foreach($ordenes as $r)
                                {{ $r->servicio }}<br>
                                @endforeach
                            </div>
                            <div><b>Cita Dependiente:</b> {{$primera_fila->id_cita_laboratorio_agrupa}}</div>

                            <div class="div-icon">
                                @if ($cantidad_ordenes > 1)
                                    <div onclick="VerOrdenes('{{ $ids_orden_lab_texto }}','{{ $servicios_texto }}')" class="btn btn-success btn-xs">
                                        <i class="fa fa-file-pdf-o" title="Ver Orden (RUA)" aria-hidden="true"></i>
                                    </div>
                                @else
                                    <a href="/ver_laboratorio_imprimir/{{ $primera_fila->id_orden_lab }}" target="_blank" class="btn btn-success btn-xs">
                                        <i class="fa fa-file-pdf-o" title="Ver Orden (RUA)" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </div>
                            @if ($alguna_orden_con_toma_muestra && $total_cantidad_sr_grupo > 0)
                                @if ($cantidad_ordenes > 1)
                                    <div onclick="VerOrdenesSinReactivo('{{ $ids_orden_lab_texto }}','{{ $servicios_texto }}', '{{ $cantidad_sr_por_orden_texto }}')" class="btn btn-success btn-xs">
                                        <i class="fa fa-file-text-o" title="Ver RUA Sin Reactivo" aria-hidden="true"> RUA Sin Reactivo</i>
                                    </div>
                                @else
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="Sin Reactivo">
                                        <a href="/ver_lab_orden_sin_reactivo/{{ $primera_fila->id_orden_lab }}" target="_blank" class="btn btn-success btn-xs">
                                            <i class="fa fa-file-text-o" title="Ver RUA Sin Reactivo" aria-hidden="true"> RUA Sin Reactivo</i>
                                        </a>
                                    </div>
                                @endif
                            @endif
                        </td>
                    
                        {{-- Cita --}}
                        <td style="position: relative;width: 240px;">
                            <div><b>Fecha:</b> {{$primera_fila->fecha_hora_cita_lab }}</div>
                            <div><b>Consultorio:</b> 
                                <div title="{{$primera_fila->consultorio_lab}}">
                                    <?php
                                    $texto_recortado = substr($primera_fila->consultorio_lab, 0, 30);
                                    if (strlen($primera_fila->consultorio_lab) > 30) {
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

                                <button class="btn btn-success btn-xs" onclick="getCitaConsultaExterna({{$primera_fila->id_cita_laboratorio_agrupa}})">
                                <i class="fa fa-eye" title="Ver cita" aria-hidden="true"></i>
                                </button>
                            </div>
                        </td>

                        {{-- Observaciones del Profesional --}}
                        <td style="min-width: 150px;">
                            <b>Observaciones Profesional:</b><br>
                            @foreach($ordenes as $r)
                                <b>{{ $r->servicio }}:</b> {{ $r->observacion_orden_lab }}<br>
                            @endforeach
                        </td>

                        {{-- Toma de muestra --}}
                        <td style="width: 200px;">
                            <div style="position: relative;">
                                @if (is_null($primera_fila->flag_observado))
                                    <div>
                                        <div>
                                            <b>Fecha:</b> {{$primera_fila->fecha_toma_muestra ==null ? "Pendiente": $primera_fila->fecha_toma_muestra}}
                                        </div>
                                        <div>
                                            <b>Usuario 1:</b> {{ $primera_fila->usuario_toma_muestra }}
                                        </div>
                                        <div>
                                            <b>Estado:</b> {{$primera_fila->fecha_toma_muestra ==null ? "Pendiente": "Atendido"}}
                                        </div>

                                        @if ($primera_fila->fecha_last_toma_muestra != null)
                                            <div>
                                                <b>Fecha segunda toma:</b> {{$primera_fila->fecha_last_toma_muestra ==null ? "Pendiente": $primera_fila->fecha_last_toma_muestra}}
                                            </div>
                                            <div>
                                                <b>Usuario 2:</b> {{ $primera_fila->usuario_update_toma_muestra }}
                                            </div>
                                        @endif

                                        <div style="margin: 5px;"></div>
                                        
                                        @if (!is_null($primera_fila->fecha_toma_muestra))
                                            <?php
                                                $cantidad_enviado = 0;
                                                $cantidad_pendiente = 0;
                                                $cantidad_sr = 0;
                                            
                                                foreach($ordenes as $r):
                                                    $cantidad_enviado += $r->cantidad_enviado;
                                                    $cantidad_pendiente += $r->cantidad_pendiente;
                                                    $cantidad_sr += $r->cantidad_sr;
                                                endforeach;
                                            ?>
                                            <div><b>Enviados:</b> {{ $cantidad_enviado }}</div>
                                            <div><b>Pendientes:</b> {{ $cantidad_pendiente }}</div>
                                            <div><b>Sin Reactivo:</b> {{ $cantidad_sr }}</div>
                                        @endif
                                    </div>

                                    <div class="div-icon">
                                        <div data-toggle="tooltip" data-placement="top" data-html="true">
                                            @if ($primera_fila->fecha_toma_muestra != null)
                                                @if (intval($primera_fila->cantidad_toma_muestra) < 3)
                                                    @if (intval($cantidad_ordenes) == 1)
                                                        <button class="btn btn-warning btn-xs" onclick="mofificarPruebasEnviadasLNS({{ $primera_fila->id_orden_lab }}, 1)" title="Modificar toma de muestra">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                    @elseif (intval($cantidad_ordenes) > 1)
                                                        <button onClick="validarCitaProgramadaParaEdicionTomaMuestra('','{{ $ids_orden_lab_texto }}', '1', {{ $primera_fila->id_cita_laboratorio_agrupa }}, 0)" class="btn btn-warning btn-xs" title="Modificar toma de muestra">
                                                            <i class="fa fa-edit"> {{ $cantidad_ordenes }}</i>
                                                        </button>
                                                    @endif
                                                @endif
                                            @else
                                                @if (intval($cantidad_ordenes) == 1)
                                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="Registrar toma de muestra">
                                                        <a href="/formulario_atencion_laboratorio_lns/{{ $primera_fila->id_orden_lab }}/1" class="btn btn-success btn-xs"><i class="fa fa-edit "></i></a>
                                                    </div>
                                                @elseif (intval($cantidad_ordenes) > 1)
                                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="Registrar toma de muestra">
                                                        <button onClick="validarCitaProgramadaParaRegistroTomaMuestra('', 0, 1, {{ $cantidad_ordenes }}, 0, {{ $primera_fila->id_cita_laboratorio_agrupa }})" class="btn btn-success btn-xs shadow"><i class="fa fa-edit"></i> {{ $cantidad_ordenes }}</button>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                        
                                    </div>
                                @else
                                    <div><b>Estado: <span style="color:red">{{ intval($primera_fila->flag_observado) === 2 ? "Observado" : "Anulado" }}</span></b></div>
                                    <div><b>Fecha: <span style="color:red">{{ $primera_fila->fecha_observado }}</span></b></div>
                                    <div><b>Motivo: <span style="color:red">{{ $primera_fila->motivo_observado }}</span></b></div>
                                @endif
                            </div>
                        </td>

                        {{-- Observaciones de la Muestra --}}
                        <td style="width: 200px;">
                            <b>Observación 1:</b> {{ $primera_fila->observacion_toma_muestra }}<br>
                            <b>Observación 2:</b> {{ $primera_fila->obs_last_toma_muestra }}
                        </td>

                        {{-- resultado --}}
                        <td style="position: relative; width: 150px;">
                            @if (intval($cantidad_ordenes) > 1)
                                @if (is_null($primera_fila->flag_observado) && !is_null($primera_fila->fecha_toma_muestra))
                                    <?php
                                        $ordenes_resultado_grupo = [];
                                        foreach ($ordenes as $r_res):
                                            $ordenes_resultado_grupo[] = [
                                                'cod_orden_lab' => $r_res->cod_orden_lab,
                                                'estado_lab' => $r_res->estado_lab,
                                                'cantidad_pendiente' => $r_res->cantidad_pendiente,
                                                'cantidad_enviado' => $r_res->cantidad_enviado,
                                                'cantidad_total' => $r_res->cantidad_total,
                                                'cantExamenConResultado' => $r_res->cantExamenConResultado,
                                                'orden_info_detallado' => $r_res->orden_info_detallado,
                                            ];
                                        endforeach;
                                    ?>
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Resultados">
                                        <button
                                            type="button"
                                            class="btn btn-success btn-xs shadow"
                                            onclick='VerOrdenesResultados({{ json_encode($ordenes_resultado_grupo, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG) }}, "{{ $primera_fila->nro_doc_ident }}")'
                                        ><i class="fa fa-eye" title="Ver Resultado" aria-hidden="true"></i> {{ $cantidad_ordenes }}</button>
                                    </div>
                                @endif
                            @else
                                @if (is_null($primera_fila->flag_observado) && !is_null($primera_fila->fecha_toma_muestra))
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
                                        <div class="">
                                            <a target="_blank" href="/print_examen_laboratorio_lns/0/{{$r->nro_doc_ident}}/00/00/00/{{$r->cod_orden_lab == null ? "Pendiente" :$r->cod_orden_lab }}" class="btn btn-xs @if($r->estado_lab == 1) btn-success @else btn-warning @endif"><i class="fa fa-eye" title="Ver Resultado" aria-hidden="true"></i></a>
                                        </div>
                                    @endif
                                @endif
                            @endif
                        </td>

                        {{-- Acciones --}}
                        <td style="text-align: center">
                            @if (is_null($primera_fila->fecha_toma_muestra))
                                <?php 
                                    $estado_muestra = "0"; // Ordenes sin toma de muestra 
                                ?>
                                @foreach($ordenes as $r)
                                    <?php
                                        $estado_muestra = "0";
                                        if (is_null($r->flag_observado) && !is_null($r->fecha_toma_muestra)):
                                            $estado_muestra = "1"; // Al menos una Orden ya tiene toma de muestra
                                            break;
                                        endif;
                                    ?>
                                @endforeach
                                
                                @if (is_null($r->flag_observado) && $estado_muestra == "0")
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="Anular" style="display: inline-block;">
                                        <button
                                            style="margin-right: 5px;"
                                            id="btnEstadoOrdenLab"
                                            data-accion="anular"
                                            data-estado-toma-muestra = "{{ $estado_muestra }}"
                                            data-tipo-atencion = "{{ $tipo_atencion }}"
                                            data-id="{{ $primera_fila->id_cita_laboratorio_agrupa }}"
                                            class="btn btn-danger btn-xs shadow"
                                        ><i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="Observar" style="display: inline-block;">
                                        <button
                                            style="margin-right: 5px;"
                                            id="btnObservarOrdenLab"
                                            data-accion="observar"
                                            data-estado-toma-muestra = "{{ $estado_muestra }}"
                                            data-tipo-atencion = "{{ $tipo_atencion }}"
                                            data-id="{{ $primera_fila->id_cita_laboratorio_agrupa }}"
                                            class="btn btn-warning btn-xs shadow"
                                        ><i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>  

    </div>
</div>

