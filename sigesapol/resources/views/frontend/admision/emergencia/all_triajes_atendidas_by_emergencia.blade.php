<?php
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
@extends('frontend.layouts.master2')
@section('renderjs')
@include('frontend.admision.emergencia.triaje.ver_triaje.function.function_edad')
    <style>
        .btn-app {
            padding: 1px 5px;
            font-size: 12px;
            line-height: 1.5;
            border-radius: 5px; 
            position: relative;
            text-align: center;
            color: #FFF; 
            background-color: #00a65a; 
        }
        .btn-app:hover {
            color: #FFF;
        }
      .btn-success .badge {
        color: white !important; 
        background-color: #fff;
         }
    
        .btn-app>.badge {
            position: absolute;
            top: -10px;
            right: -15px;
            font-size: 15px;
            font-weight: 400; 
        }
        .table-sortable tbody tr {
            cursor: move;
        }

        #global {
            min-height: 600px !important; 
            width: 100%; 
            margin: 8px 
        }

        .margin {

            margin-bottom: 20px;
        }
 
        .margin-buscar {
            margin-bottom: 5px;
            margin-top: 5px;
        }

        .row {
            margin-top: 10px;
            padding: 0 10px;
        }

        .clickable {
            cursor: pointer;
        }

        .panel-heading div {
            margin-top: -18px;
            font-size: 15px;
        }

        .panel-heading div span {
            margin-left: 5px;
        }

        .panel-body {
            display: block;
        }

        .dataTables_filter {
            display: none;
        }

        .help-block {
            display: block;
            margin-top: 1px;
            margin-bottom: 10px;
            color: #737373;
        }
 

        .contenido-btn-div {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }
    </style>
    <style>
        .fila-verde {
            background-color: #8ED68C; 
        }
    </style>
    <script src="<?php echo URL::to('/'); ?>/js/js.util.grid.js"></script>
    <script>
        
  presionar_keypress_input('#dni_beneficiario');
  presionar_keypress_input('#nombre_beneficiario');

    function presionar_keypress_input(input){ 
        $(input).keypress(function(event) {
            if (event.which === 13) { 
                event.preventDefault();  
                datatablenew();
            }
        });
    }
    var intervalID;  // Declarar la variable fuera de la función para que sea accesible en diferentes contextos

    function activarRecargaAutomatica() {
        intervalID = setInterval(function() {
            $("#tblEmergenciaTriaje").DataTable().ajax.reload();
        }, 5000);
    }

    function desactivarRecargaAutomatica() {
        clearInterval(intervalID);
    }

    function valida_estado_triaje(){ 
            var estado=$('#estado').val();
            if (estado == -1) {
                activarRecargaAutomatica();
            } else {
                desactivarRecargaAutomatica();
            }

            if(estado==-1 || estado==-2){
                $('#fecha_hora_admision').text('Fecha Totem');
                $('#fecha_hora_triaje').text('Hora Totem');
                $('#nro_documento').text('Número Doc');
                $('#paciente_it').text('Paciente');
                $('#secuencia').text('Cod. Ticket');
                $('#prioridad').text('Totem'); 
                $('#registrar').text('Llamado'); 
                $('#tipo_medico').text('Usuario de Atención');
 
                if(estado==-2){ 
                    $('#tipo_medico').text('Cnt.Llamado');
                    $('#estado_admin').text('Mot. Anulación');
                    $('#acompaniante').text('Fecha/Hora Anulación'); 
                }

            }else  if(estado==1){
                $('#tipo_medico').text('Médico de Triaje');
                $('#secuencia').text('N° Secuencia');
                $('#prioridad').text('Prioridad / Área'); 
            }else if(estado==2){
                $('#tipo_medico').text('Usuario');
                $('#secuencia').text('N° Secuencia');
                $('#prioridad').text('Prioridad / Área');
            }else if(estado==9){
                $('#tipo_medico').text('Usuario');
                $('#secuencia').text('N° Secuencia');
                $('#prioridad').text('Prioridad / Área');
            }else if(estado==0){
                $('#tipo_medico').text('Usuario');
                $('#secuencia').text('N° Secuencia');
                $('#prioridad').text('Prioridad / Área');
                $('#estado_admin').text('Estado Admisión');
            }
    }
        $('#estado').change(function() {
            datatablenew();
            valida_estado_triaje();
        });
 
        //--------------------------------------------------------------------------
        $(document).ready(function() {

            if('{{$establecimiento->flag_colas}}'==1){ 
                if('{{Auth::user()->flagservicios}}'!=1){  
                        $('#ventanilla').val('{{ Auth::user()->id_ventanilla }}'); 
                        let ventanilla = $('#ventanilla').val(); 
                        if (ventanilla == '0' || ventanilla == '' || ventanilla == undefined) { 
                            $.ajax({
                                type: "GET",
                                url: "modal_ventanillas",
                                dataType: "HTML",
                                success: function (response) {
                                console.log(response);
                                $('#modal_ventanillas').html(response);
                                }
                            });
                        }  
                }
            } 


            $('#fecha_fin').appendDtpicker({
                "dateOnly": true,
                "autodateOnStart": false,
                "minuteInterval": 5,
                "amPmInTimeList": false,
                "locale": "es",
                "closeOnSelected": true,
                "dateFormat": "DD-MM-YYYY"
            });


            $('#fecha_ini').appendDtpicker({
                "dateOnly": true,
                "autodateOnStart": false,
                "minuteInterval": 5,
                "amPmInTimeList": false,
                "locale": "es",
                "closeOnSelected": true,
                "dateFormat": "DD-MM-YYYY"
            });
            
            $('#fecha_ini').click(function() {
                var fechaDesdeIni = $('#fecha_ini').val();

                $('#fecha_ini').change(function() {
                    var fechaDesde = $('#fecha_ini').val();
                    var fechaHasta = $('#fecha_fin').val();
                    f1 = fechaDesde.split("-");
                    f2 = fechaHasta.split("-");
                    f3 = new Date();
                    var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
                    var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

                    if (f1 > f2) {
                        bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta");
                        $('#fecha_ini').val(fechaDesdeIni);
                        return false;
                    }else{ 
                        datatablenew();
                        valida_estado_triaje();
                    }
                });
            });

            $('#fecha_fin').click(function() {
                var fechaHastaIni = $('#fecha_fin').val();
                $('#fecha_fin').change(function() {
                    var fechaDesde = $('#fecha_ini').val();
                    var fechaHasta = $('#fecha_fin').val();
                    f1 = fechaDesde.split("-");
                    f2 = fechaHasta.split("-");
                    f3 = new Date();
                    var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
                    var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[
                        0])); //30 de noviembre de 2014

                    if (f2 < f1) {
                        bootbox.alert("La Fecha Hasta no debe ser menor a la Fecha Desde");
                        $('#fecha_fin').val(fechaHastaIni);
                        return false;
                    }else{ 
                        datatablenew();
                        valida_estado_triaje();
                    }

                });
            });

            $('#fecha_fin').trigger('change');
 

            var activeSystemClass = $('.list-group-item.active');
            $('#example').DataTable({
                "language": {
                    "emptyTable": "No se encontraron resultados"
                }
            });

            $("#system-search").keyup(function() {
                var dataTable = $('#example').dataTable();
                dataTable.fnFilter(this.value);
            });

            $('#btnBuscar').click(function() {
                fn_ListarBusqueda();
            });

            datatablenew();
            valida_estado_triaje();


        });
 

        function reporteEmergenciaTriaje(idUser) {
            let fecha_ini = $('#fecha_ini').val();
            let fecha_fin = $('#fecha_fin').val();
            let estado_admision = $('#estado').val();

            if (fecha_ini == "") fecha_ini = 0;
            if (fecha_fin == "") fecha_fin = 0;

            location.href = '{{ url('exportar_reporte_emergencia_triaje') }}' + '/' + idUser + '/' + fecha_ini + '/' +
                fecha_fin + '/' + estado_admision;
        }

        function soloNumeros(e) {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)
        }

        function Edad(dateString) {
            var today = new Date();
            var birthDate = new Date(dateString);
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            return age;
        }

        var m = 0;


        function datatablenew() {
            var oTable1 = $('#tblEmergenciaTriaje').dataTable({
                "bServerSide": true,
                "sAjaxSource": "/listar_triaje_emergencia_ajax",
                "bProcessing": false,
                "sPaginationType": "full_numbers",
                "bFilter": false,
                "bSort": false,
                "info": true,
                "language": {
                    "url": "/js/Spanish.json"
                },
                "autoWidth": false,
                "bLengthChange": true,
                "destroy": true,
                "lengthMenu": [
                    [20, 50, 100, 200, 60000],
                    [20, 50, 100, 200, "Todos"]
                ],
                "aoColumns": [{}, ],
                "dom": '<"top">rt<"bottom"flpi><"clear">',
                "fnDrawCallback": function(json) {
                    $('[data-toggle="tooltip"]').tooltip();
                },

                "fnServerData": function(sSource, aoData, fnCallback, oSettings) {
 
                    var iNroPagina = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value))
                        .toFixed();
                    var iCantMostrar = aoData[4].value;
                    var fecha_ini = $('#fecha_ini').val();
                    var fecha_fin = $('#fecha_fin').val();
                    var dni_beneficiario = $('#dni_beneficiario').val();
                    var nombre_beneficiario = $('#nombre_beneficiario').val();
                    var id_establecimiento = <?php echo Auth::user()->id_establecimiento; ?>;
                    var estado = $('#estado').val();
                    var opcion = $('#opcion').val();
                    var _token = $('#_token').val();
                    oSettings.jqXHR = $.ajax({
                        "dataType": 'json',
                        "type": "POST",
                        "url": sSource,
                        "data": {
                            NumeroPagina: iNroPagina,
                            NumeroRegistros: iCantMostrar,
                            opcion: opcion,
                            fecha_ini: fecha_ini,
                            fecha_fin: fecha_fin,
                            dni_beneficiario: dni_beneficiario,
                            nombre_beneficiario: nombre_beneficiario,
                            id_establecimiento: id_establecimiento,
                            estado: estado,
                            _token: _token
                        },
                        "success": function(result) {
                            fnCallback(result);
                        },
                        "error": function(msg, textStatus, errorThrown) {
                            //location.href="login";
                        }
                    });
                },

                "aoColumnDefs": [
                    {
                        "mRender": function(data, type, row) {
                            //  cod(id emergia)
                            var cod = ""; 
                            if ($('#estado').val() == -1 || $('#estado').val() == -2) {  
                                if(row.cod_emergencia!= null)cod = row.cod_emergencia;  
                            }else{
                                if (row.id != null) cod = row.id;
                            }
                            return cod;
                        },
                        "bSortable": false,
                        "aTargets": [0]
                    },
                    {
                        "mRender": function(data, type, row) { 
                            if ($('#estado').val() == -1 || $('#estado').val() == -2) {   
                                var fecha_triaje = "";
                                if(row.fecha_triaje!= null)fecha_triaje = row.fecha_triaje;
                                return fecha_triaje; 
                            }else{
                                let fecha_ingreso = "";
                                let hora_ingreso = "";
                                let fecha_hora = "";
                                if (row.fecha_ingreso_admision != null) fecha_ingreso = row.fecha_ingreso_admision;
                                if (row.hora_ingreso_admision != null) hora_ingreso = row.hora_ingreso_admision; 
                                if(fecha_ingreso!='' && hora_ingreso!=''){
                                    return '<b>Fecha Admisión:</b><br>'+fecha_ingreso+'<br><b>Hora Admisión:</b><br>'+hora_ingreso+'<br>';
                                } else{ 
                                    return '';
                                }
                            }
                        },
                        "bSortable": false,
                        "aTargets": [1]
                    },
                    {
                        "mRender": function(data, type, row) {
                            if ($('#estado').val() == -1 || $('#estado').val() == -2) { 
                                var hora_triaje = "";
                                if(row.hora_triaje!= null)hora_triaje = row.hora_triaje;
                                return hora_triaje; 
                            }else{
                                let fecha_ingreso = "";
                                let hora_ingreso = "";
                                let fecha_hora = "";
                                if (row.fecha_registro_ingreso != null) fecha_ingreso = row.fecha_registro_ingreso;
                                if (row.hora_registro_ingreso != null) hora_ingreso = row.hora_registro_ingreso;  
                                return '<b>Fecha Triaje:</b><br>'+fecha_ingreso+'<br><b>Hora Triaje:</b><br>'+hora_ingreso+'<br>';
                            }
                        },
                        "bSortable": false,
                        "aTargets": [2]
                    },
                    {
                        "mRender": function(data, type, row) {
                            if ($('#estado').val() == -1 || $('#estado').val() == -2) {  
                                var numero_doc = "";
                                if(row.nro_doc_ident!= null)numero_doc = row.nro_doc_ident;
                                return numero_doc;
                            }else{
                                //Paciente
                                var asegurado = "";
                                if (row.dni_beneficiario != null) dni_beneficiario = row.dni_beneficiario;
                                if (row.paterno != null) asegurado += row.paterno;
                                if (row.materno != null) asegurado += " " + row.materno;
                                if (row.nombre != null) asegurado += " " + row.nombre;
                                if(row.tipo_de_beneficiario!=null){
                                    return '<b>T.Beneficiario:</b>'+row.tipo_de_beneficiario+'<br><b>Documento:</b>'+dni_beneficiario+'<br><b>Paciente:</b><br>'+asegurado+'<br>';
                                }else{
                                    return asegurado;
                                }
                            }
                        },
                        "bSortable": false,
                        "aTargets": [3]
                    },
                    {
                        "mRender": function(data, type, row) {
                            if ($('#estado').val() == -1 || $('#estado').val() == -2) {  
                                var asegurado = ""; 
                                if(row.asegurado!= null)asegurado += row.asegurado;
                                return asegurado;
                            }else{ 
                                var edad = "";
                                var sexo = "";
                                var tel = "";
                                if (row.sexo != null) sexo = row.sexo;
                                if (row.fecha_nac != null) edad = calcularEdadPersonaReturn(row.fecha_nac);
                                if (row.telefono_paciente != null) tel = row.telefono_paciente; 
                                return '<b>Sexo:</b>'+sexo+'<br><b>Edad:</b>'+edad+'<br><b>Contacto:</b>'+tel+'<br>';  
                            }
                        },
                        "bSortable": false,
                        "aTargets": [4]
                    },
                    {
                        "mRender": function(data, type, row) {
                            if ($('#estado').val() == -1 || $('#estado').val() == -2) {  
                                var cod_totem = "";
                                if(row.cod_totem!= null)cod_totem = row.cod_totem;
                                return cod_totem;
                            }else{
                                //N° Secuencia
                                var secuencia = "";
                                if (row.secuencia != null) secuencia = row.secuencia;
                                return secuencia;
                            }
                        },
                        "bSortable": false,
                        "aTargets": [5]
                    },
                    {
                        "mRender": function(data, type, row) {
                            if ($('#estado').val() == -1 || $('#estado').val() == -2) {  
                                var prioridad = "";
                                if(row.totem_nombre!= null)prioridad = row.totem_nombre; 
                                return prioridad;
                            }else{
                                //Prioridad
                                var prioridad = "";
                                var descripcion = "";
                                if (row.prioridad != null) prioridad = row.prioridad;
                                if (row.descripcion != null) descripcion = row.descripcion; 
                                return '<b>Área:</b><br>'+descripcion +'<br><b>Prioridad:</b><br>'+prioridad+'<br>';  
                            }
                        },
                        "bSortable": false,
                        "aTargets": [6]

                    },
                    {
                        "mRender": function(data, type, row) {
                            if ($('#estado').val() == -1 || $('#estado').val() == -2) {  
                                if ($('#estado').val() == -1) {  
                                    if(row.seleccionado_us == null || row.seleccionado_us == ''){
                                        return '-';
                                    }else{
                                        return row.seleccionado_us; 
                                    }
                                }else if ($('#estado').val() == -2) {  
                                    return row.cantidad_llamada; 
                                }
                            }else{
                                //Médico triaje
                                var medico = ""; 
                                var medico_triaje = ""; 
                                if ($('#estado').val() == 1) {   
                                    if (row.medico_triaje != null) medico = row.medico_triaje;
                                    return '<b>Responsable de Triaje:</b><br>'+medico;
                                }else if ($('#estado').val() == 2) {
                                    if (row.medico_triaje != null) medico_triaje = row.medico_triaje;
                                    if (row.medico_admision != null) medico = row.medico_admision;
                                    return '<b>Responsable de Triaje:</b><br>'+medico_triaje+'<br><b>Responsable de Admisión:</b><br>'+medico;   
                                }else if ($('#estado').val() == 9) {   
                                    
                                    if (row.estado != null) {
                                            var medico = ""; 
                                        if (row.estado == '1' || row.estado == '0') { 
                                            if (row.medico_triaje != null) medico = row.medico_triaje;
                                            return '<b>Responsable de Triaje:</b><br>'+medico;
                                        } else if (row.estado >= '2') {
                                            if (row.medico_triaje != null) medico_triaje = row.medico_triaje;
                                            if (row.medico_admision != null) medico = row.medico_admision;
                                            return '<b>Responsable de Triaje:</b><br>'+medico_triaje+'<br><b>Responsable de Admisión:</b><br>'+medico;   
                                        }
                                        return medico;
                                    } else {
                                        return '-';
                                    }
                                }else if ($('#estado').val() == 0) { 
                                    if (row.medico_triaje != null) medico = row.medico_triaje;
                                    return '<b>Responsable de Triaje:</b><br>'+medico;
                                } 
                            }
                        },
                        "bSortable": false,
                        "aTargets": [7]

                    },
                    {
                        "mRender": function(data, type, row) {
                            if ($('#estado').val() == -1 || $('#estado').val() == -2) {  
                                if ($('#estado').val() == -1) {  
                                    return '-';
                                }else if ($('#estado').val() == -2) {  
                                    var html = ""; 
                                    if(row.motivo_anulacion==null){
                                        html += '-' ;
                                    }else{
                                        if(row.anulado_us!=null){
                                            html += '<b>Usuario Responsable de Anulación:</b><br>'+row.anulado_us+'<br>';
                                        }
                                        if(row.motivo_anulacion!=null){
                                            html += '<b>Motivo de Anulación:</b><br>'+row.motivo_anulacion ;
                                        }
                                    }
                                    return html;
                                }
                            }else{
                                //Estado
                                var estado = "";
                                var motivo_anulacion = "";
                                var usuario_anula = "";
                                var fecha_anulacion = "";
                                var hora_anulacion = "";
                                if ($('#estado').val() == 0) {  
                                    
                                    if(row.motivo_anulacion_admision!= null) motivo_anulacion += row.motivo_anulacion_admision;
                                    if(row.usuario_anula_admision!= null) usuario_anula += " "+row.usuario_anula_admision;
                                    if(row.fecha_anulacion_admision!= null) fecha_anulacion += " "+row.fecha_anulacion_admision;
                                    if(row.hora_anulacion_admision!= null) hora_anulacion += " "+row.hora_anulacion_admision;
                                    return '<b>Motivo:</b> '+motivo_anulacion+'<br><b>Responsable:</b> '+usuario_anula+'<br>'+fecha_anulacion+' - '+hora_anulacion+'<br>';  
                                
                                }else{
                                    if (row.estado != null) {
                                        if (row.estado == '1') {
                                            estado = 'Pendiente'
                                        } else if (row.estado >= '2') {
                                            estado = 'Atendido'
                                        }else if (row.estado == '0') {
                                    
                                            if(row.motivo_anulacion_admision!= null) motivo_anulacion += row.motivo_anulacion_admision;
                                            if(row.usuario_anula_admision!= null) usuario_anula += " "+row.usuario_anula_admision;
                                            if(row.fecha_anulacion_admision!= null) fecha_anulacion += " "+row.fecha_anulacion_admision;
                                            if(row.hora_anulacion_admision!= null) hora_anulacion += " "+row.hora_anulacion_admision;
                                            return '<b>Motivo:</b> '+motivo_anulacion+'<br><b>Responsable:</b> '+usuario_anula+'<br>'+fecha_anulacion+' - '+hora_anulacion+'<br>';  
                                
                                        }
                                        return estado;
                                    } else {
                                        return estado;
                                    }    
                                }
                            }
                        },
                        "bSortable": false,
                        "aTargets": [8]

                    },
                    {
                        "mRender": function(data, type, row) { 
                            if ($('#estado').val() == -1 || $('#estado').val() == -2) {  
                                if ($('#estado').val() == -1) {  
                                    return '-';
                                }else if ($('#estado').val() == -2) {   
                                    var hora_triaje_anulado = "";
                                    var fecha_triaje_anulado = "";
                                    if(row.fecha_triaje_anulado!= null)fecha_triaje_anulado = row.fecha_triaje_anulado;
                                    if(row.hora_triaje_anulado!= null)hora_triaje_anulado = row.hora_triaje_anulado;
                                    return html =  '<b>F.Anulación:</b><br>'+fecha_triaje_anulado + '<br><b>H.Anulación:</b><br>'+hora_triaje_anulado;  
                                }
                            }else{
                                var dni_conducido = "-"; 
                                var nombre_conducido = "-"; 
                                var telf_conducido = "-"; 
                                if(row.dni_conducido!=null ){  
                                    dni_conducido = row.dni_conducido; 
                                }
                                if(row.nombre_conducido!=null){  
                                    nombre_conducido = row.nombre_conducido; 
                                }
                                if(row.telf_conducido!=null ){  
                                    telf_conducido = row.telf_conducido; 
                                }

                                if(nombre_conducido!=null&&nombre_conducido!=''){
                                    return '<b>Documento:</b> '+dni_conducido +'<br><b>Acompañante:</b><br>'+nombre_conducido+'<br><b>Contacto:</b>'+telf_conducido+'<br>';
                                }else{ 
                                    return '<b>Documento:</b> '+dni_conducido +'<br><b>Contacto:</b>'+telf_conducido+'<br>';
                                }
                            }
                        },
                        "bSortable": false,
                        "aTargets": [9]

                    },
                    {
                        "mRender": function(data, type, row) {
                            var html = "";
                            if ($('#estado').val() == -1) {  
                                html += '<a   href="javascript:void(0)" class="btn btn-app  btn-success btn-xs" data-id_ticket="'+row.cod_emergencia+'" ';
                                html += ' data-fecha_ticket="'+row.fecha_triaje+' - '+row.hora_triaje+'"  data-id_emergencia="'+row.id_emergencia+'" ';
                                html += ' data-cod_totem="'+row.cod_totem+'" data-totem_nombre="'+row.totem_nombre+'" ';
                                html += ' data-paciente_="'+row.nro_doc_ident+' - '+row.asegurado+'"  data-id_estado_tv="'+row.id_estado_tv+'"';
                                html += ' id="opciones_de_llamado" name="opciones_de_llamado" "> <i class="fa fa-tv"></i> LLAMAR';
                                if(row.cantidad_llamada>0){
                                    html += ' <span class="badge bg-purple" style="background-color: red;"><b>'+row.cantidad_llamada+'</b></span></a>';
                                }else{
                                    html += ' </a>';
                                } 
                                
                                if('{{Auth::user()->flagservicios}}'==1){
                                    html += ' <a href="javascript:void(0)" class="btn btn-warning btn-xs" data-id_ticket="'+row.cod_emergencia+'" ';
                                    html += ' id="liberar_de_llamado" name="liberar_de_llamado" style="margin-left: 15px;"> ';
                                    html += ' <i class="fa fa-check" aria-hidden="true"></i> </a>';
                                }
                            }else{
                                if (row.estado == 1) {
                                    html += ' <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Registrar</b>" class="contenido-btn-div">';
                                    html += ' <a href="/atender_admision/' + row.id +'/1/" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a></div><br> ';
                                } else {
                                    html +=' <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Registrar</b>" ';
                                    html +=' class="contenido-btn-div"><button class="btn btn-info btn-xs" disabled><i class="fa fa-eye"></i></button></div>';
                                }
                            }
                            return html;
                        },
                        "bSortable": false,
                        "aTargets": [10]

                    }, 
                    {
                        "mRender": function(data, type, row) {
                            if ($('#estado').val() == -1 || $('#estado').val() == -2) {  
                                return '-';
                            }else{
                                var html = "";
                                if (row.estado == 0) {  
                                    html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" class="contenido-btn-div"><button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button></div>';
                                } else if (row.estado != 1) {
                                    html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Admision</b>" class="contenido-btn-div">';
                                    html += '<a href="/ver_datos_admision/' + row.id +'/2/" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';
                                } else {
                                    html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" class="contenido-btn-div"><button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button></div>';
                                }
                                return html;
                            }
                        },
                        "bSortable": false,
                        "aTargets": [11]

                    },
                    {
                        "mRender": function(data, type, row) {
                            if ($('#estado').val() == -1 || $('#estado').val() == -2) {  
                                return '-';
                            }else{
                                var html = "";
                                if (row.ruta_admision != null && row.estado != 1) {
                                    html +=
                                        '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" class="contenido-btn-div">';
                                    html += '<a href="/PdfEmergenciaAdmision/' + row.id +
                                        '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
                                } else {
                                    html +=
                                        '<div class="contenido-btn-div"><button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button></div>';
                                }
                                return html;
                            }
                        },
                        "bSortable": false,
                        "aTargets": [12]

                    },
                    {
                        "mRender": function(data, type, row) {
                            if ($('#estado').val() == -1 || $('#estado').val() == -2) {  
                                return '-';
                            }else{
                                var html = "";
                                var id_usuario=0;
                                if ((row.estado == 1 || row.estado == 2) && row.flag_admision==0 ) {
                                    
                                    if ($('#estado').val() == 1) {  
                                        id_usuario=row.id_medico_triaje;
                                    }else if ($('#estado').val() == 2) {
                                        id_usuario=row.id_usuario_admision;
                                    }else if ($('#estado').val() == 9) {
                                        if(row.id_usuario_admision == null){
                                            id_usuario=row.id_medico_triaje;
                                        }else{
                                            id_usuario=row.id_usuario_admision;
                                        }
                                    }

                                    if(row.estado == 0){
                                        html +='<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>';
                                    }else if ((row.estado == 1 || row.estado == 2) && (id_usuario == {{Auth::user()->id}}) && row.flag_admision==0 ) {
                                        if($('#estado').val() == 9){
                                            if ((row.estado == 1 || row.estado == 2) && (id_usuario == {{Auth::user()->id}}) && row.flag_admision==0 ) {
                                                html +='<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>';
                                            }else{
                                                html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Admisión</b>" class="contenido-btn-div"> ';
                                                html += '<a href="javascript:void(0)" class="btn btn-danger btn-xs" data-id_emergencia="'+row.id+'" ';
                                                html += 'id="anular_triaje" name="anular_triaje"><i class="fa fa-remove"> </i></a></div> '; 
                                            }
                                        }else{
                                            html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Admisión</b>" class="contenido-btn-div"> ';
                                            html += '<a href="javascript:void(0)" class="btn btn-danger btn-xs" data-id_emergencia="'+row.id+'" ';
                                            html += 'id="anular_triaje" name="anular_triaje"><i class="fa fa-remove"> </i></a></div> '; 
                                        }
                                    } else {
                                        html +='<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>';
                                    }
                                } else {
                                    html +='<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>';
                                }
                                return html;
                            }
                        },
                        "bSortable": false,
                        "aTargets": [13],
                        "className": "text-center",
                    },
                    {
                        "mRender": (data, type, row) => {
                            let htmlString = "";
                            if (row.cod_siteds_susalud) {
                                if (row.cod_siteds_susalud[row.cod_siteds_susalud.length - 1] === "T" || String(row.cod_siteds_susalud).length <= 6) {
                                    htmlString = `
                                            <p data-toggle="tooltip" data-placement="top" title="Código Siteds Temporal">
                                                <span class="label label-warning" style='font-weight: lighter; font-size: 10px'>${(row.cod_siteds_susalud).toUpperCase()}</span>
                                            </p>`

                                    if ($('#estado').val() == 2 && row.id && row.id_asegurado && row.id_establecimiento && row.id_servicio) { 
                                        htmlString += `
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Regularizar</b>" class="contenido-btn-div">
                                                <button class="btn btn-primary btn-xs" onclick="regularizeCodeSiteds(${row.id}, ${row.id_asegurado}, ${row.id_establecimiento}, ${row.id_servicio})">
                                                    <i class="fa fa-check-circle-o"></i> 
                                                </button>
                                            </div>`;
                                    } 
                                } else {
                                    htmlString = `<span class="label label-success" style='font-weight: lighter; font-size: 10px'>${(row.cod_siteds_susalud)}</span>`
                                }
                            }
                            return htmlString;
                        },
                        "bSortable": false,
                        "aTargets": [14]
                    
                    }
                ],
                // Agregar la propiedad createdRow
                "createdRow": function(row, data, dataIndex) { 
                    var estado = $('#estado').val(); 
                    if (estado == -1) { 
                        if (data.id_estado_tv == 1 || data.id_estado_tv == 4){
                            $(row).addClass('fila-verde'); 
                        } else{
                            $(row).removeClass('fila-verde'); 
                        }
                    } 
                }
            }); 
           
            /*
            */
             
 
            var oTable2= $('#tblEmergenciaTriaje').DataTable();    
            if ($('#estado').val() == -1) {   
                oTable2.column(8).visible(false);
                oTable2.column(9).visible(false);
                oTable2.column(11).visible(false);
                oTable2.column(12).visible(false);
                oTable2.column(13).visible(false);
                oTable2.column(14).visible(false);
            }else if ($('#estado').val() == -2) {   
                oTable2.column(10).visible(false); 
                oTable2.column(11).visible(false);
                oTable2.column(12).visible(false);
                oTable2.column(13).visible(false);
                oTable2.column(14).visible(false);
                oTable2.column(14).visible(false);
            }else if ($('#estado').val() == 1) {  
                oTable2.column(1).visible(false);
                oTable2.column(8).visible(false);
                oTable2.column(11).visible(false);
                oTable2.column(12).visible(false); 
                oTable2.column(13).visible(false);
                oTable2.column(14).visible(false);
            }else if ($('#estado').val() == 2) {  
                oTable2.column(8).visible(false);
                oTable2.column(10).visible(false);  
            } else if ($('#estado').val() == 9) {   
                oTable2.column(10).visible(false); 
                oTable2.column(11).visible(false);
                oTable2.column(12).visible(false);
                oTable2.column(13).visible(false);
                oTable2.column(14).visible(false);
            }else if ($('#estado').val() == 0) {   
                oTable2.column(9).visible(false);
                oTable2.column(10).visible(false);
                oTable2.column(11).visible(false);
                oTable2.column(12).visible(false);
                oTable2.column(13).visible(false);
                oTable2.column(14).visible(false);
            }
        }

        function fn_ListarBusqueda() {
            datatablenew();
        }

        // TAGSITEDS
        function regularizeCodeSiteds(id, aseguradoId, establecimientoId, servicioId) {
            Swal.fire ({
                title:  "<div style='margin: 20px 0px 0px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZAR CÓDIGO SITEDS</span></div>",
                html: "<div style='margin: 0px 0px 20px 0px'><span style='color: #000000; font-size: 18px'>Esperé un momento, por favor...</span></div>",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
            })
            const body = {
                "idCorrelativo": id,
                "aseguradoId": aseguradoId,
                "establecimientoId": establecimientoId,
                "tipoAtencionId": "21",
                "servicioId": servicioId
            };
            $.ajax({
                url:'/v2/ws-dirsapol-siteds',
                type:'POST',
                contentType: "application/json",
                dataType: "json",
                data: JSON.stringify(body),
                success: (result, textStatus, jqXHR) => {
                    if (result && result.nuAutorizacion && (parseInt(jqXHR.status) === 200 || parseInt(jqXHR.status) === 201)) {
                        handlerUpdatedCodeSiteds(id, result.nuAutorizacion, result.message);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>¡OPERACIÓN NO EXITOSA!</span></div>",
                            html: `<div style='margin: 0px 0px; padding: 0px 20px'>
                                    <span style='color: #000000; font-size: 18px'>Servicio no disponible. Por favor, inténtelo más tarde o utilice el sistema web de SUSALUD para regularizar el SITEDS manualmente. <a href="https://app8.susalud.gob.pe:8080/wb-siteds/login.htm" target='_blank'>SITEDS WEB</a></span>
                                    </div>`,
                            confirmButtonText: 'ENTENDIDO',
                        });
                    }
                },
                error: function(xhr, status, error){
                    const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
                    Swal.fire({
                        icon: 'error',
                        title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>¡OPERACIÓN NO EXITOSA!</span></div>",
                        html: `<div style='margin: 0px 0px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
                        confirmButtonText: 'ENTENDIDO',
                    });
                }, 
                complete: function(xhr, status) {
                    Swal.hideLoading();
                }
            })
        }

        function handlerUpdatedCodeSiteds(id, nuAutorizacion, message) {
            Swal.fire ({
                title:  "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZAR CÓDIGO SITEDS</span></div>",
                html: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>Esperé un momento, por favor...</span></div>",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                onOpen: () => {
                    Swal.showLoading();
                }
            })
            const auth = {!! json_encode(Auth::user()) !!};
            const body = {
                cod_siteds_susalud: nuAutorizacion,
                updated_us_cod_siteds: auth.id
            };
            $.ajax({
                //url: `/evaluacion-emergencia/${id}/update-code-siteds`,
                url: `/emergencia/${id}/update-code-siteds`,
                type: 'PUT',
                data: body,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        icon:'success',
                        title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>REGULARIZAR CÓDIGO SITEDS</span></div>",
                        html: `<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>${message || "Código SITEDS actualizado correctamente"}</span></div>`,
                        confirmButtonText: 'ENTENDIDO',
                    });
                    fn_ListarBusqueda();
                },
                error: function(jqXHR, textStatus, errorThrown) {},
                complete: function(xhr, status) {
                    Swal.hideLoading();
                    Swal.close();
                }
            });
        };

    </script>
@endsection
@section('content')
<div id="modal_ventanillas"></div>
    <div class="container-fluid">
        <div class="panel panel-primary filterable">
        <div class="panel-heading" style="padding: 0;">
        <h3 style="display: block; height: 100%; padding: 0 15px;" class="panel-title"> <span style="display: inline-block; padding: 10px 15px; background-color: rgb(255, 24, 24); color: rgb(255, 255, 255);">E</span> Lista de Atenciones de Triaje para ser admisionado en Emergencia </h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" method="post" action="{{ route('listar_triaje_emergencia_ajax') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
                    <input type="hidden" name="id_establecimiento" id="id_establecimiento"
                        value="{{ Auth::user()->id_establecimiento }}">
                    <input type="hidden" name="opcion" id="opcion" value="{{ $opcion }}">
                    <input value="" name="ventanilla"  id="ventanilla" type="hidden">
                    <div class="row">
                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                            <label class="form-control-sm">Fecha Desde</label>
                            <input class="form-control input-sm" type="text" name="fecha_ini" id="fecha_ini"
                                placeholder="Fecha Desde" value="{{\Carbon\Carbon::now()->format('d-m-Y')}}" maxlength="10">
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                            <label class="form-control-sm">Fecha Hasta</label>
                            <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin"
                                placeholder="Fecha Hasta" value="{{\Carbon\Carbon::now()->format('d-m-Y')}}" maxlength="10">
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                            <label class="form-control-sm">Nro. Documento</label>
                            <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder=""
                                class="form-control input-sm" maxlength="12" value="<?php echo $dni_beneficiario; ?>">
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <label class="form-control-sm">Nombres</label>
                            <input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder=""
                                class="form-control input-sm text-uppercase" maxlength="60" value="<?php echo $nombre_beneficiario; ?>">
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="form-control-sm">Estado</label>
                            <select class="form-control" name="estado" id="estado" tabindex="15"> 
                                    @if($establecimiento->flag_colas==1)  
                                            <option value="-1" selected> PENDIENTES DE ADMISIÓN</option> 
                                            <option value="-2"> TICKET DE ADMISIÓN ANULADOS</option>  

                                        <option value="1"> ADMISIONES PENDIENTES</option> 
                                        <option value="2">ADMISIONES ATENDIDAS</option>
                                        <option value="0">ADMISIONES ANULADAS</option>
                                        <option value="9"> TODOS</option>
                                    @else
                                        <option value="1" selected> ADMISIONES PENDIENTES</option>
                                        <option value="2">ADMISIONES ATENDIDAS</option>
                                        <option value="0">ADMISIONES ANULADAS</option>
                                        <option value="9"> TODOS</option>
                                    @endif 
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12"><br />
                            <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
                        </div>

                        <!--div class="col-lg-2 col-md-2 col-sm-12 col-xs-12"><br />
                            <a class="btn btn-primary form-control input-sm" href="javascript:void(0)"
                                onclick="reporteEmergenciaTriaje('{{ Auth::user()->id }}')">
                                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                            </a>
                        </div-->
                    </div>
                </form>
                <div id="global">
                    <table id="tblEmergenciaTriaje" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Cod</th>
                                <th id="fecha_hora_admision">Fecha y Hora de Admisión</th>
                                <th id="fecha_hora_triaje">Fecha y Hora de Triaje</th>
                                <th id="nro_documento">Paciente</th>
                                <th id="paciente_it">Datos del Paciente</th>
                                <th id="secuencia">N° Secuencia</th>
                                <th id="prioridad">Prioridad / Área</th>  

                                    <th id="tipo_medico" name="tipo_medico" style="width: 17%;">Usuario</th> 

                                <th id="estado_admin">Estado de Admisión</th>
                                <th id="acompaniante">Acompañanate</th>
                                <th id="registrar" style="text-align:center; width: 130px;">Admisionar</th> 
                                <th id="ver" style="text-align:center">Ver</th>
                                <th id="pdf" style="text-align:center">Pdf</th>
                                <th id="anular" style="text-align:center">Anular</th>
                                <th id="siteds" style="text-align:center">SITEDS</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    
   
<div class="modal fade" id="modalOpcionesLlamado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content"> 
            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <b>OPCIONES DE LLAMADO</b>
            </div> 
            <div class="modal-body">
                <form id="FormularioOpcionesLlamado" name="FormularioOpcionesLlamado" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}"> 
                    <input type="hidden" id="id_ticket_" name="id_ticket_"> 
                    <input type="hidden" id="id_emergencia_" name="id_emergencia_"> 
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;"> 
                                <fieldset>
                                    <div class="form-group"  style="text-align: center"> 
                                      <a href="javascript:void(0)" class="btn btn-success btn-md" id="llamar_paciente" name="llamar_paciente" 
                                        style="margin: 0px 20px 0px 20px"> <i class="fa fa-tv"></i> LLAMAR PACIENTE
                                      </a>
                                      <a href="javascript:void(0)" class="btn btn-warning btn-md"  id="atender_paciente" name="atender_paciente" 
                                        style="margin: 0px 20px 0px 20px"> <i class="fa fa-tv"></i> ATENDER PACIENTE
                                      </a>
                                    </div>

                                    <div class="form-group" style="font-size: 14px; text-align: center;"> 
                                      <br>
                                      <p>
                                        <b>PACIENTE</b><br><paciente><b id="paciente" name="paciente">---</b></paciente> <br>
                                        <b id="fecha_ticket" name="fecha_ticket">---</b><br><br>
                                        <ticket style="color: red;">
                                          <b>TICKET:</b>
                                          <b id="cod_totem" name="cod_totem">---</b> <br> 
                                          <b id="totem_nombre" name="totem_nombre">---</b>
                                        </ticket>
                                      </p>
                                      <br>
                                    </div>

                                    <div class="form-group"  style="text-align: center"> 
                                      <a href="javascript:void(0)" class="btn btn-danger btn-md btn-block" id="anular_ticket" name="anular_ticket" >
                                        <i class="fa fa-tv"></i> ANULAR TICKET DE ADMISIÓN
                                      </a>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </form>
            </div> 
        </div>
    </div>
</div>

<script> 
    
    function anularTicket(id_ticket, motivo_anulacion){ 
        bootbox.confirm({ 
          size: "small", 
          message: "&iquest;Desea anular el Ticket seleccionado?", 
          callback: function(result){
            if (result==true) { 
              fn_anularTicket(id_ticket, motivo_anulacion.replace(/\//g, "-"));
            }
          }
        }); 
    }

    function fn_anularTicket(id_ticket, motivo_anulacion) {
      var data = {
        id_ticket: id_ticket,
        motivo_anulacion: motivo_anulacion
      };

      $.ajax({
        url: '/anular_emergencia_ticket',
        method: 'POST',
        data: JSON.stringify(data),
        contentType: "application/json",
        dataType: "json",
        success: function(result) {
          if (result.sw == true) {
            $('#modalOpcionesLlamado').modal('hide');
            $('#modalAnularLlamado').modal('hide');
            var oTable = $('#tblEmergenciaTriaje').dataTable();
            oTable.fnDraw(false);

            Swal.fire({
              title: '<div class="titulo-padding"><p>Mensaje de Llamado</p></div>',
              html: '<p class="descripcion-alert"><span><b>EL TICKET SELECCIONADO FUE ANULADO DE FORMA CORRECTA.</b><br></span></p>',
              allowOutsideClick: false,
              timer: 3000,
              timerProgressBar: true,
              showConfirmButton: false,
              allowOutsideClick: false,
            });
          }
        }
      });
    } 
 
    $('body').on('click', '#opciones_de_llamado', function () {  
        $.ajax({
            url: '/validar_seleccion_llamado/' + $(this).data('id_ticket'),
            dataType: "json",
            success: function(result){
                console.log(result[0]);
                if(result[0].id_estado_tv == 1 || result[0].id_estado_tv == 4){
                    var oTable = $('#tblEmergenciaTriaje').dataTable();
                    oTable.fnDraw(false);
                    Swal.fire({
                        title: '<div class="titulo-padding"><p>Mensaje de Triaje</p></div>',
                        html: '<p class="descripcion-alert"><span><b style="color: red;">EL PACIENTE SELECCIONADO SE ENCUENTRA SIENDO ATENDIDO POR OTRO USUARIO.</b><br></span></p>', 
                        allowOutsideClick: false,
                        timer: 2800,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    });
                }else{
                    var oTable = $('#tblEmergenciaTriaje').dataTable();
                    oTable.fnDraw(false);
                    $('#FormularioOpcionesLlamado').trigger("reset");
                    $('#modalOpcionesLlamado').modal({backdrop: 'static', keyboard: false, show: true})
                    $('#modalOpcionesLlamado').modal('show');  

                    $('#id_emergencia_').val(result[0].id_emergencia);
                    $('#id_ticket_').val(result[0].cod_emergencia);
                    $('#paciente').html(result[0].nro_doc_ident+' - '+result[0].asegurado);
                    $('#fecha_ticket').html(result[0].fecha_triaje +' - '+ result[0].hora_triaje);
                    $('#cod_totem').html(result[0].cod_totem);
                    $('#totem_nombre').html(result[0].totem_nombre); 

                    llamada_triaje_amergencia(result[0].cod_emergencia, 4,0,0,'seleccionado');  
                }
            }
        }); 
    });


    $('body').on('click', '#llamar_paciente', function () {  
        var oTable = $('#tblEmergenciaTriaje').dataTable();
        oTable.fnDraw(false);
        var id_ticket=$('#id_ticket_').val();  
        llamada_triaje_amergencia(id_ticket, 1,0,0,'llamado'); 
    });

    $('body').on('click', '#liberar_de_llamado', function () {  
        var oTable = $('#tblEmergenciaTriaje').dataTable();
        oTable.fnDraw(false); 
        llamada_triaje_amergencia($(this).data('id_ticket'), 2,0,0,'cancelar');
    });

    $(".close").click(function() {
        var oTable = $('#tblEmergenciaTriaje').dataTable();
        oTable.fnDraw(false);
        $("#modalOpcionesLlamado").hide();
        var id_ticket_=$('#id_ticket_').val(); 
        llamada_triaje_amergencia(id_ticket_, 2,0,0,'cancelar');
    });
 
    function llamada_triaje_amergencia(id_ticket, id_estado_tv, estado_llamada, cantidad_llamada,tipo_proceso){ 
        $.ajax({
            url: '/llamada_triaje_amergencia/' + id_ticket+'/'+ id_estado_tv+'/'+ estado_llamada+'/'+ cantidad_llamada,
            dataType: "json",
            success: function(result){

                var oTable = $('#tblEmergenciaTriaje').dataTable();
                oTable.fnDraw(false);
                
                if(result.sw == true){
                if(tipo_proceso == 'llamado'){
                    Swal.fire({
                    title: '<div class="titulo-padding"><p>Mensaje de Llamado</p></div>',
                    html: '<p class="descripcion-alert"><span><b>ESPERE UN MOMENTO, PROCESO DE LLAMADO EN CURSO.</b><br></span></p>', 
                    allowOutsideClick: false,
                    timer: 4500,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    }); 
                }else if(tipo_proceso == 'cancelar'){
                    Swal.fire({
                    title: '<div class="titulo-padding"><p>Mensaje de Llamado</p></div>',
                    html: '<p class="descripcion-alert"><span><b>PROCESO DE LLAMADO, POSPUESTO.</b><br></span></p>', 
                    allowOutsideClick: false,
                    timer: 700,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    });
                }
                }
            }
        });
    }

    $('#atender_paciente').click(function () {
        var oTable = $('#tblEmergenciaTriaje').dataTable();
        oTable.fnDraw(false); 
 
        var id_emergencia_=$('#id_emergencia_').val();  
        window.location.href = "/atender_admision/" + id_emergencia_ +"/1/" ; 
    });

    $('#anular_ticket').click(function () {
        var oTable = $('#tblEmergenciaTriaje').dataTable();
        oTable.fnDraw(false);

        $('#FormularioAnularLlamado').trigger("reset");
        $('#modalAnularLlamado').modal({backdrop: 'static', keyboard: false, show: true})
        $('#modalAnularLlamado').modal('show');

        var oTable = $('#tblEmergenciaTriaje').dataTable();
        oTable.fnDraw(false);
    }); 
    
    $('body').on('click', '#registrar_anular_ticket', function () {  
      var id_ticket_=$('#id_ticket_').val();
      var motivo_anulacion=$('#motivo_anulacion').val();
      if(motivo_anulacion == '' || motivo_anulacion==null){
        Swal.fire({
          title: '<div class="titulo-padding"><p>Mensaje de Llamado</p></div>',
          html: '<p class="descripcion-alert"><span><b style="color: red;">INGRESE UN DETALLE DE ANULACIÓN DE FORMA CORRECTA.</b><br></span></p>', 
          allowOutsideClick: false,
          timer: 1800,
          timerProgressBar: true,
          showConfirmButton: false,
          allowOutsideClick: false,
        });
                
        var oTable = $('#tblEmergenciaTriaje').dataTable();
        oTable.fnDraw(false);
      }else{ 
      
        var motivo_anulacion=motivo_anulacion.toUpperCase();
        if(motivo_anulacion.length > 10){
            anularTicket(id_ticket_ , motivo_anulacion); 
            var oTable = $('#tblEmergenciaTriaje').dataTable();
            oTable.fnDraw(false);
        }else{
            Swal.fire({
            title: '<div class="titulo-padding"><p>Mensaje de Triaje</p></div>',
            html: '<p class="descripcion-alert"><span><b style="color: red;">RECUERDE QUE TIENE QUE TENER UNA CANTIDAD MÍMINA DE 10 CARACTERES.</b><br></span></p>', 
            allowOutsideClick: false,
            timer: 1800,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: false,
            });
        } 
      } 
    });
   
</script> 

   
<div class="modal fade" id="modalAnularLlamado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content"> 
            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <b>DETALLE DE ANULACIÓN</b>
            </div> 
            <div class="modal-body">
                <form id="FormularioAnularLlamado" name="FormularioAnularLlamado" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}"> 
                    <input type="hidden" id="id_ticket_" name="id_ticket_"> 
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active"> 
                                <fieldset>
                                    <div class="form-group"  style="text-align: center">   
                                      <textarea name="motivo_anulacion" id="motivo_anulacion" rows="10" style="width: 100%;"></textarea>
                                    </div>

                                    <div class="form-group"  style="text-align: center"> 
                                      <a href="javascript:void(0)" class="btn btn-danger btn-md btn-block" id="registrar_anular_ticket" name="registrar_anular_ticket" >
                                          ANULAR TICKET DE ADMISIÓN
                                      </a>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </form>
            </div> 
        </div>
    </div>
</div>


<script> 
    
    function anularAdmision(id_emergencia, motivo_anulacion) { 
        bootbox.confirm({
            size: "small",
            message: "&iquest;Deseas finalizar la atenci&oacute;n de Admisión?",
            callback: function(result) {
                if (result == true) {
                    fn_anularAdmision(id_emergencia, motivo_anulacion.replace(/\//g, "-"));
                }
            }
        }); 
    }

    function fn_anularAdmision(id, motivo_anulacion) {
        var data = {
            id: id,
            motivo_anulacion: motivo_anulacion
        };

        $.ajax({
            url: '/anular_emergencia_admision',
            method: 'POST',
            data: JSON.stringify(data),
            contentType: "application/json",
            dataType: "json",
            success: function(result) {
            if (result.sw == true) {
                $('#modalAnularAdmision').modal('hide');
                var oTable = $('#tblEmergenciaTriaje').dataTable();
                oTable.fnDraw(false);

                Swal.fire({
                title: '<div class="titulo-padding"><p>Mensaje de Admisión</p></div>',
                html: '<p class="descripcion-alert"><span><b>LA ADMISIÓN SELECCIONADA FUE ANULADA DE FORMA CORRECTA.</b><br></span></p>',
                allowOutsideClick: false,
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                allowOutsideClick: false,
                });
            }
            }
        });
    } 

    $('body').on('click', '#anular_triaje', function () {  
        var oTable = $('#tblEmergenciaTriaje').dataTable();
        oTable.fnDraw(false);

        $('#FormularioAnularAdmision').trigger("reset");
        $('#modalAnularAdmision').modal({backdrop: 'static', keyboard: false, show: true})
        $('#modalAnularAdmision').modal('show');

        $('#id_emergencia_').val($(this).data('id_emergencia'));

        var oTable = $('#tblEmergenciaTriaje').dataTable();
        oTable.fnDraw(false); 
    });
    
    
    $('body').on('click', '#registrar_anular_triaje', function () {  
        var id_emergencia_=$('#id_emergencia_').val();
        var motivo_anulacion_triaje=$('#motivo_anulacion_triaje').val();
        if(motivo_anulacion_triaje == '' || motivo_anulacion_triaje==null){
            Swal.fire({
                title: '<div class="titulo-padding"><p>Mensaje de Triaje</p></div>',
                html: '<p class="descripcion-alert"><span><b style="color: red;">INGRESE UN DETALLE DE ANULACIÓN DE FORMA CORRECTA.</b><br></span></p>', 
                allowOutsideClick: false,
                timer: 1800,
                timerProgressBar: true,
                showConfirmButton: false,
                allowOutsideClick: false,
            });
                
            var oTable = $('#tblEmergenciaTriaje').dataTable();
            oTable.fnDraw(false);
        }else{ 
      
            var motivo_anulacion_triaje=motivo_anulacion_triaje.toUpperCase();
            if(motivo_anulacion_triaje.length > 10){
                anularAdmision(id_emergencia_ , motivo_anulacion_triaje); 
                var oTable = $('#tblEmergenciaTriaje').dataTable();
                oTable.fnDraw(false);
            }else{
                Swal.fire({
                title: '<div class="titulo-padding"><p>Mensaje de Triaje</p></div>',
                html: '<p class="descripcion-alert"><span><b style="color: red;">RECUERDE QUE TIENE QUE TENER UNA CANTIDAD MÍMINA DE 10 CARACTERES.</b><br></span></p>', 
                allowOutsideClick: false,
                timer: 1800,
                timerProgressBar: true,
                showConfirmButton: false,
                allowOutsideClick: false,
                });
            }

        } 
    });
</script> 

<div class="modal fade" id="modalAnularAdmision" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content"> 
            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <b>DETALLE DE ANULACIÓN</b>
            </div> 
            <div class="modal-body">
                <form id="FormularioAnularAdmision" name="FormularioAnularAdmision" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}"> 
                    <input type="hidden" id="id_emergencia_" name="id_emergencia_"> 
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active"> 
                                <fieldset>
                                    <div class="form-group"  style="text-align: center">   
                                      <textarea name="motivo_anulacion_triaje" id="motivo_anulacion_triaje" rows="10" style="width: 100%;"></textarea>
                                    </div>

                                    <div class="form-group"  style="text-align: center"> 
                                      <a href="javascript:void(0)" class="btn btn-danger btn-md btn-block" id="registrar_anular_triaje" name="registrar_anular_triaje" >
                                          ANULAR ADMISIÓN
                                      </a>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </form>
            </div> 
        </div>
    </div>
</div>

@include('frontend.includes.footer')
@endsection
