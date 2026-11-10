<?php 
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
@extends('frontend.layouts.master2')
@section('renderjs')
<style>
    .table-sortable tbody tr {
        cursor: move;
    }
    #global {
        min-height: 650px !important;
        /*width: auto;*/
        width: 100%;
        border: 1px solid #ddd;
        margin:15px
            /* background: #f1f1f1;*/
            /*overflow-y: scroll !important;*/
    }

    .margin{

        margin-bottom: 20px;
    }
    .margin-buscar{
        margin-bottom: 5px;
        margin-top: 5px;
    }

    .row{
        margin-top:10px;
        padding: 0 10px;
    }
    .clickable{
        cursor: pointer;
    }

    .panel-heading div {
        margin-top: -18px;
        font-size: 15px;
    }
    .panel-heading div span{
        margin-left:5px;
    }
    .panel-body{
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
    
    .modal-dialog{
        width: 70% !important;
    }
    
    .swal2-icon.swal2-warning {
    font-size: 15px;
  }

  .swal-wide-form{
            width: 500px !important;
            padding: 0px 20px !important;
        }

</style>

<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>
    //--------------------------------------------------------------------------
    $(document).ready(function () {

        $('#fecha_ini').appendDtpicker({
        "dateOnly": true,
                "autodateOnStart": false,
                "minuteInterval": 5,
                "amPmInTimeList": false,
                "locale": "es",
                "closeOnSelected": true,
                "dateFormat": "DD-MM-YYYY"
        });
    
        $('#fecha_fin').appendDtpicker({
        "dateOnly": true,
                "autodateOnStart": false,
                "minuteInterval": 5,
                "amPmInTimeList": false,
                "locale": "es",
                "closeOnSelected": true,
                "dateFormat": "DD-MM-YYYY"
        });
    
        $('#fecha_ini').click(function () {
            var fechaDesdeIni = $('#fecha_ini').val();
        
            $('#fecha_ini').change(function () {
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
                }
            });
        });

        $('#fecha_fin').click(function () {
            var fechaHastaIni = $('#fecha_fin').val();
            $('#fecha_fin').change(function () {
                var fechaDesde = $('#fecha_ini').val();
                var fechaHasta = $('#fecha_fin').val();
                f1 = fechaDesde.split("-");
                f2 = fechaHasta.split("-");
                f3 = new Date();
                var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
                var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

                if (f2 < f1) {
                    bootbox.alert("La Fecha Hasta no debe ser menor a la Fecha Desde");
                    $('#fecha_fin').val(fechaHastaIni);
                    return false;
                }
        
            });
        });

        $('#fecha_fin').trigger('change');
        
        $('#fecha_ini').trigger('change');
        
        var activeSystemClass = $('.list-group-item.active');
        
    });
    
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
    });
    
    function getTopico (){ 
    var id_prioridad = $('#id_prioridad').val(); 
    var sexo = $('#sexo').val();
    var edad = $('#edadpaciente').val();
    
    if(id_prioridad!=''){
      //valicion de tipo de listado
      var url_consulta='/get_topico_por_id_prioridad/' + id_prioridad; 
        ///validamos que sea de prioridad 2 y 3 para colocar por defecto gineco  
        if(id_prioridad=='2'||id_prioridad=='3'){
            url_consulta='/get_topico_por_id_prioridad_sexo_edad/'+id_prioridad+'/'+sexo+'/'+edad;
        }  
       
        $.ajax({
          url: url_consulta,
          success: function(result) {   
            $('#id_topico').empty();  
            $('#id_topico').append("<option value='0'>--SELECCIONAR--</option>"); 
            $(result).each(function(ii, oo) {  
              $('#id_topico').append("<option value='" + oo.id + "'>" + oo.descripcion + "</option>"); 
            }); 
            $('#id_topico').val("").change();  
            if(id_prioridad=='1'){
                $('#id_topico').val("1").change(); 
                $('#observacion').focus(); 
            }else if(id_prioridad=='0'){
                $('#id_topico').val("0").change(); 
                $('#observacion').focus(); 
            }else if(id_prioridad=='2'){
                $('#id_topico').val("10").change(); 
                $('#observacion').focus(); 
            }else if(id_prioridad=='3'){
                $('#id_topico').val("11").change(); 
                $('#observacion').focus(); 
            }else if(id_prioridad=='4'){
                $('#id_topico').append("<option value='19'>CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)</option>"); 
                $('#id_topico').val("19").change(); 
                $('#observacion').focus(); 
            }else{
                $('#id_topico').val("").change();
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            if(id_prioridad!='4'){
              $('#id_topico').empty();  
              $('#id_topico').append("<option value='0'>--SELECCIONAR--</option>"); 
              $('#id_topico').val("").change(); 
              $('#observacion').focus(); 
            }else{
              $('#id_topico').empty();  
              $('#id_topico').append("<option value='19'>CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)</option>"); 
              $('#id_topico').val("19").change(); 
              $('#observacion').focus(); 
            }
          }
        }); 
    }else{
      $('#id_topico').empty();  
      $('#id_topico').append("<option value='0'>--SELECCIONAR--</option>"); 
      $('#id_topico').val("").change(); 
    }
  }


function reporteEvaluacion2(idUser) {

    Swal.fire({
            title: '<div class="titulo-padding"><p>Generando el reporte...</p></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                header : 'swal2-header-generar'
            },
            onBeforeOpen: () => {
                $('#blur-container').append('<div class="blur-background"></div>');
                let fecha_ini = $('#fecha_ini').val();
                let fecha_fin = $('#fecha_fin').val();
                let dni = $('#dni_beneficiario').val();
                let paciente = $('#nombre_beneficiario').val();
                let estado = $('#estado').val();
                let id_prioridad = $('#id_prioridad').val();
                let id_topico = $('#id_topico').val();
                let id_atencion = 2;
                let id_establecimiento = <?php echo Auth::user()->id_establecimiento; ?>;

                if (fecha_ini == "") fecha_ini = 0;
                if (fecha_fin == "") fecha_fin = 0;
                if (dni == "") dni = 0;
                if (paciente == "") paciente = 0;
                if (estado == "") estado = 0;
                if (id_prioridad == "") id_prioridad = 0;
                if (id_topico == "") id_topico = 0;
                if (id_atencion == "") id_atencion = 0;
                Swal.showLoading();
                $.ajax({
                    type: "POST",
                    url: "exportar_reporte_evaluacion2",
                    data: {
                        "id_establecimiento" : id_establecimiento,
                        "fecha_ini" : fecha_ini,
                        "fecha_fin" : fecha_fin,
                        "dni" : dni,
                        "paciente" : paciente,
                        "estado" : estado,
                        "id_atencion" : id_atencion,
                        "id_prioridad" : id_prioridad,
                        "id_topico" : id_topico,
                    },
                    success: function (response) {
                        console.log(response);
                        console.log(response.excelNameResponse);
                        if (response.excelNameResponse) {
                            // Obtener la URL del archivo del response
                            let fileUrl = `/download_reportes/${response.excelNameResponse}`;
                            // Crear un enlace de descarga
                            let link = document.createElement('a');
                            link.href = fileUrl;
                            link.download = 'Exportar_Reportes_de_Evaluacion_.xlsx';
                            link.innerText = 'Descargar archivo';

                            // Simular un clic en el enlace para iniciar la descarga
                            let divLinkReporte = document.getElementById('link-reporte');
                            divLinkReporte.appendChild(link);
                            link.click();
                            divLinkReporte.removeChild(link);

                            Swal.hideLoading();
                            Swal.fire({
                                icon: 'success',
                                title: 'El reporte se ha generado correctamente!',
                                showConfirmButton: false,
                                showConfirmButton: false,
                                timer: 1500,
                                onClose: () => {
                                    $('.blur-background').remove(); // Elimina el fondo desenfocado cuando se cierra la alerta
                                }
                            });
                        }
                    },
                    error: function() {
                        Swal.hideLoading();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al generar el reporte',
                            text: 'Por favor, inténtelo de nuevo más tarde',
                            confirmButtonText: 'Cerrar',
                            customClass: {
                                confirmButton : 'custom-swal-button-cerrar'
                            },
                            onClose: () => {
                                $('.blur-background').remove(); // Elimina el fondo desenfocado cuando se cierra la alerta
                            }
                        });
                    }
                });
            }
        });

    }

     function reporteEvaluacionDetallado(idUser) {
        Swal.fire({
            title: '<div class="titulo-padding"><p>Generando el reporte...</p></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                header : 'swal2-header-generar'
            },
            onBeforeOpen: () => {
                $('#blur-container').append('<div class="blur-background"></div>');
                var fecha_ini = $('#fecha_ini').val();
                var fecha_fin = $('#fecha_fin').val();
                var dni = $('#dni_beneficiario').val();
                var paciente = $('#nombre_beneficiario').val();
                var estado = $('#estado').val(); //ESTADO
                var id_prioridad = $('#id_prioridad').val();//PRIORIDAD
                var id_topico = $('#id_topico').val();//AREA
                if (fecha_ini == "")fecha_ini = 0;
                if (fecha_fin == "")fecha_fin = 0;
                if (dni == "")dni = 0;
                if (paciente == "")paciente = 0;
                if (estado == "")estado = 0;
                if (id_prioridad == "")id_prioridad = 0;
                if (id_topico == "")id_topico = 0;
               
                Swal.showLoading();
                $.ajax({
                    type: "POST",
                    url: "exportar_reporte_evaluacion_detallado",
                    data: {
                        "id_user" : idUser,
                        "fecha_ini" : fecha_ini,
                        "fecha_fin" : fecha_fin,
                        "dni" : dni,
                        "paciente" : paciente,
                        "estado" : estado,
                        "id_prioridad" : id_prioridad, 
                        "id_topico" : id_topico,                       
                    },
                    success: function (response) {
                       console.log(response.excelName);
                       if (response.excelNameResponse) {
                        //let fileUrl = response.fileUrl;
                        let fileUrl = `/download_reportes/${response.excelNameResponse}`;
                        
                        // Crear un enlace de descarga
                        let link = document.createElement('a');
                        link.href = fileUrl;
                        link.download = 'Exportar_Reportes_Evaluaciones_Detallados.xlsx';
                        link.innerText = 'Descargar archivo';
                        // Simular un clic en el enlace para iniciar la descarga
                        let divLinkReporte = document.getElementById('link-reporte');
                        
                        divLinkReporte.appendChild(link);
                        link.click();
                        divLinkReporte.removeChild(link);
                        Swal.hideLoading();
                        Swal.fire({
                            icon: 'success',
                            title: 'El reporte se ha generado correctamente!',
                            showConfirmButton: false,
                            showConfirmButton: false,
                            timer: 1500,
                            onClose: () => {
                                $('.blur-background').remove(); // Elimina el fondo desenfocado cuando se cierra la alerta
                            }
                            
                        });
                      }
                    },
                    error: function() {
                        Swal.hideLoading();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al generar el reporte',
                            text: 'Por favor, inténtelo de nuevo más tarde',
                            confirmButtonText: 'Cerrar',
                            customClass: {
                                confirmButton : 'custom-swal-button-cerrar'
                            },
                            onClose: () => {
                                $('.blur-background').remove(); // Elimina el fondo desenfocado cuando se cierra la alerta
                            }
                        });
                    }
                });
            }
        });
      }


    function reporteEmergenciaEvolucion(idUser){

            var fecha_ini = $('#fecha_ini').val();
            var fecha_fin = $('#fecha_fin').val();
            
            if (fecha_ini == "")fecha_ini = 0;
            if (fecha_fin == "")fecha_fin = 0;
            
            location.href = 'exportar_reporte_emergencia_triaje/' + idUser + '/' + fecha_ini + '/' + fecha_fin ;
            
            
    }
    
    function soloNumeros(e) {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)
    }



    $(document).ready(function () {
        
        $('#btnBuscar').click(function () {
            fn_ListarBusqueda();
        });
        
        datatablenew();
            
    });

    

    function Edad(dateString) 
    {
        var today = new Date();
        var birthDate = new Date(dateString);
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) 
        {
            age--;
        }
        return age;
    }

    var m = 0;

    function datatablenew(){
        var oTable1 = $('#tblEmergenciaTriaje').dataTable({
            "bServerSide": true,
            "sAjaxSource": "/listar_evaluacion_emergencia_ajax",
            "bProcessing": true,
            "sPaginationType": "full_numbers",
            "bFilter": false,
            "bSort": false,
            "info": true,
            "language": {"url": "/js/Spanish.json"},
            "autoWidth": false,
            "bLengthChange": true,
            "destroy": true,
            "lengthMenu": [[10, 50, 100, 200, 60000], [10, 50, 100, 200, "Todos"]],
            "aoColumns": [
                            {},
            ],
            "dom": '<"top">rt<"bottom"flpi><"clear">',
            "fnDrawCallback": function(json) {
                $('[data-toggle="tooltip"]').tooltip();
            },

            "fnServerData": function (sSource, aoData, fnCallback, oSettings) {

                var sEcho           = aoData[0].value;
                var iNroPagina  = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
                var iCantMostrar    = aoData[4].value;
                var fecha_ini = $('#fecha_ini').val();
                var fecha_fin = $('#fecha_fin').val();
                var dni_beneficiario = $('#dni_beneficiario').val();
                var nombre_beneficiario = $('#nombre_beneficiario').val();
                var id_prioridad = $('#id_prioridad').val();
                var id_topico = $('#id_topico').val();
                var id_establecimiento = <?php echo Auth::user()->id_establecimiento; ?>;
                var estado = $('#estado').val();
                var id_atencion = 2;
                var _token = $('#_token').val();
                oSettings.jqXHR = $.ajax({
                    "dataType": 'json',
                    "type": "POST",
                    "url": sSource,
                    "data":{NumeroPagina:iNroPagina,
                            NumeroRegistros:iCantMostrar,
                            fecha_ini:fecha_ini,
                            fecha_fin:fecha_fin,
                            dni_beneficiario:dni_beneficiario,
                            nombre_beneficiario:nombre_beneficiario,
                            id_establecimiento:id_establecimiento,
                            estado:estado,
                            id_atencion:id_atencion,
                            id_prioridad:id_prioridad,
                            id_topico:id_topico,
                            _token:_token
                           },
                    "success": function (result) {
                        fnCallback(result);
                    },
                    "error": function (msg, textStatus, errorThrown) {
                        //location.href="login";
                    }
                });
            },

            "aoColumnDefs":
                [   
                    {
                        "mRender": function (data, type, row) {
                            var id = "";
                            if(row.id!= null)id = row.id;
                            return id;
                        },
                        "bSortable": false,
                        "aTargets": [0]
                        },
                    {
                        "mRender": function (data, type, row) {
                            var fecha_ingreso = "";
                            if(row.fecha_ultima_atencion!= null)fecha_ingreso = row.fecha_ultima_atencion;
                            return fecha_ingreso;
                        },
                        "bSortable": false,
                        "aTargets": [1]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var hora_atencion = "";
                            if(row.hora_ultima_atencion!= null)hora_atencion = row.hora_ultima_atencion;
                            return hora_atencion;
                        },
                        "bSortable": false,
                        "aTargets": [2]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var prioridad = "";
                            if(row.prioridad!= null)prioridad = row.prioridad;
                            return prioridad;
                        },
                        "bSortable": false,
                        "aTargets": [3]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var descripcion = "";
                            if(row.descripcion!= null)descripcion = row.descripcion;
                            return descripcion;
                        },
                        "bSortable": false,
                        "aTargets": [4]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var dni_beneficiario = "";
                            if(row.dni_beneficiario!= null)dni_beneficiario = row.dni_beneficiario;
                            return dni_beneficiario;
                        },
                        "bSortable": false,
                        "aTargets": [5]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var asegurado = "";
                            if(row.paterno!= null)asegurado += row.paterno;
                            if(row.materno!= null)asegurado += " "+row.materno;
                            if(row.nombre!= null)asegurado += " ,"+row.nombre;
                            return asegurado;
                        },
                        "bSortable": false,
                        "aTargets": [6]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var grado = "";
                            if(row.grado!= null)grado = row.grado;
                            return grado;
                        },
                        "bSortable": false,
                        "aTargets": [7]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var parentesco = "";
                            if(row.parentesco!= null)parentesco = row.parentesco;
                            return parentesco;
                        },
                        "bSortable": false,
                        "aTargets": [8]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var sexo = "";
                            if(row.sexo!= null)sexo = row.sexo;
                            return sexo;
                        },
                        "bSortable": false,
                        "aTargets": [9]
                        
                    },
                    {
                        "mRender": function (data, type, row) {
                            var edad = "";
                            if(row.edadpaciente!= null)edad = row.edadpaciente;
                            return edad;
                        },
                        "bSortable": false,
                        "aTargets": [10]
                    }, 
                    {
                        "mRender": function (data, type, row) {
                            var html = "";
                            if( row.id_prioridad<=3){
                                
                                <?php if(Auth::user()->tipo_registro==1): ?>
                                if( row.estado==3){
                                <?php if($permiso->crear == 1): ?>
                                html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Registrar Evaluacion</b>" style="float:left;padding-left:3px">';
                                html += '<a href="/formulario_evaluacion_emergencia/'+row.id+'"  class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a></div>';
                                html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Evaluaciones</b>" style="float:left;padding-left:3px">';
                                html += '<a href="/ver_atenciones_evaluacion/'+row.id+'/'+row.estado+'/2"  class="btn btn-warning btn-xs"><i class="fa fa-eye"></i></a></div>';
                                 <?php else: ?>
                                html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Evaluaciones</b>" style="float:left;padding-left:3px">';
                                html += '<a href="/ver_atenciones_evaluacion/'+row.id+'/'+row.estado+'/2"  class="btn btn-warning btn-xs"><i class="fa fa-eye"></i></a></div>';
                                 <?php endif; ?>
                                }
                                else{
                                  if( row.estado>3){
                                    html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Paciente de Alta</b>" style="float:left;padding-left:3px">';
                                    html += '<a href="#" disabled="" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a></div>';
                                     html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Evaluaciones</b>" style="float:left;padding-left:3px">';
                                    html += '<a href="/ver_atenciones_evaluacion/'+row.id+'/'+row.estado+'/2"  class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a></div>';
                                  }
                                }
                                <?php else: ?>
                                  html += '<button class="btn btn-info btn-xs" disabled=""><i class="fa fa-medkit"></i></button>';  
                                <?php endif; ?>
                                
                            }else{
                                html += '<button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-edit"></i></button>';
                            }
                            return html;
                        },
                        "bSortable": false,
                        "aTargets": [11],
                        "className": "text-center",
                    },
                    {
                        "mRender": function (data, type, row) {
                            let htmlString = '<div style="text-align: center">';
                            if (row.cod_siteds_susalud) {
                                let isSitedsTemp = false;
                                if (row.cod_siteds_susalud && (row.cod_siteds_susalud[row.cod_siteds_susalud.length - 1] === 'T' || row.cod_siteds_susalud.length === 4)) {
                                    isSitedsTemp = true;
                                }
                                htmlString += `<p><span class="label ${ isSitedsTemp ? 'label-warning' : 'label-success'}">${row.cod_siteds_susalud}</span></p>`;
                                if (isSitedsTemp) {
                                    htmlString += `<button class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="Regularizar" onclick="regularizeCodeSiteds(${row.id}, ${row.id_asegurado}, ${row.id_establecimiento}, ${row.id_servicio})"><i class="fa fa-check-square"></i></button>`
                                }
                            }
                            return htmlString += "</div>";
                        },
                        "bSortable": false,
                        "aTargets": [12]
                    }
                    /*{
                        "mRender": function(data, type, row) {
                        var html = "";
                        if( row.id_prioridad<3){
                            <?php if(Auth::user()->tipo_registro==1): ?>
                              html +=
                                '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Asignar Receta</b>">';
                                html += '<a href="/ver_examenreceta/' + row.id +
                                '" class="btn btn-success btn-xs"><i class="fa fa-medkit"></i></a></div>';
                            <?php else: ?>
                              html += '<button class="btn btn-info btn-xs" disabled=""><i class="fa fa-medkit"></i></button>';  
                            <?php endif; ?>
                        }else{
                                html += '<button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-edit"></i></button>';
                        }
                          
                          return html;
                        },
                        "bSortable": false,
                        "aTargets": [10],
                        "className": "text-center",
                    },*/
                ]
        });
    }

    function fn_ListarBusqueda() {
       datatablenew();
    }

    function anularAtencion(id_atencion){
    
        bootbox.confirm({ 
            size: "small",
            message: "&iquest;Deseas finalizar la atenci&oacute;n de triaje?", 
            callback: function(result){
                if (result==true) {
                    fn_anularAtencion(id_atencion);
                }
            }
        });
        
    }

    function fn_anularAtencion(id){
        
        $.ajax({
            url: '/cerrar_emergencia_triaje/' + id,
            dataType: "json",
            success: function(result){
                
                if(result.sw == true){
                    location.href="all_triajes_atendidas_by_emergencia";
                }
            }
        });
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
                    updateSiteds(id, result.nuAutorizacion, result.message);
                } else {
                    const session = {!! json_encode(Auth::user()) !!}
                    const hasPermissionUpdateSitedsManual = session.flag_editor_siteds || 0;

                    Swal.fire({
                    icon: 'warning',
                    title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 20px'>¡OPERACIÓN NO EXITOSA!</span></div>",
                    html: `<div style='margin: 0px 0px; padding: 0px 20px'>
                            <span style='color: #000000; font-size: 18px'>Servicio no disponible. Por favor, inténtelo más tarde o utilice el sistema web de SUSALUD para regularizar el SITEDS manualmente. <a href="https://app8.susalud.gob.pe:8080/wb-siteds/login.htm" target='_blank'>SITEDS WEB</a></span>
                            </div>`,
                    showCancelButton: true,
                    showConfirmButton: hasPermissionUpdateSitedsManual,
                    cancelButtonText: hasPermissionUpdateSitedsManual ? "CANCELAR" : "ENTENDIDO",
                    confirmButtonColor: "#5cb85c",
                    cancelButtonColor: "#d9534f",
                    confirmButtonText: "INGRESAR MANUALMENTE",
                    }).then((result) => {
                    if (result.isConfirmed) {
                        const session = {!! json_encode(Auth::user()) !!}
                        const hasPermissionUpdateSitedsManual = session.flag_editor_siteds || 0;
                        showModalSitedsManual().then((resultModal) => {
                        if (resultModal && resultModal.isConfirmed && resultModal.value) {
                            updateSiteds(id, resultModal.value, "Se actualizó correctamente el código siteds.")
                        }
                        })
                    }
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

    function updateSiteds(id, nuAutorizacion, message) {
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

    function showModalSitedsManual(id) {
        return new Promise((resolve, reject) => {
            Swal.fire({
            title: "<div style='margin: 20px 0px 0px 0px'><span style='color: #000000; font-size: 20px'>Regularización Manual del SITEDS</span></div>",
            html: "<div style='padding: 0px 20px'><span style='color: #000000; font-size: 18px'>Le informamos que, para regularizar su código SITEDS, debe generarlo en la página oficial de SITEDS. Una vez generado, ingréselo en este formulario para continuar con el proceso.</span></div>",
            input: 'text',
            inputLabel: 'Código SITEDS',
            inputPlaceholder: "000000000001",
            showCancelButton: true,
            cancelButtonText: 'CANCELAR',
            confirmButtonText: 'GUARDAR',
            allowOutsideClick: false,
            allowEscapeKey: false,
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger',
                popup: 'swal-wide-form',
            },
            inputAttributes: {
                maxlength: 12,
                pattern: '[A-Za-z0-9 ]*'
            },
            buttonsStyling: false,
            inputValidator: (value) => {
                if (!value) {
                    return 'Ingresa el código SITED para continuar';
                } else if (value.length < 12) {
                    return 'El código SITEDS debe tener 12 caracteres';
                }
            }
            }).then((result) => {
                if (result.isConfirmed) {
                    resolve(result);
                } else {
                    reject(result);
                }
            }).catch((error) => {
                reject(error);
            });
        });
    }

</script>
@endsection
@section('content')
<div id="blur-container">
<div class="container-fluid">
    <div class="panel panel-primary filterable" >
    <div class="panel-heading" style="padding: 0;">
    <h3 style="display: block; height: 100%; padding: 0 15px;" class="panel-title"> <span style="display: inline-block; padding: 10px 15px; background-color: rgb(255, 24, 24); color: rgb(255, 255, 255);">E</span> Lista de Evaluaciones Médicas - Emergencia </h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="post" action="{{ route('listar_triaje_emergencia_ajax')}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
                <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{Auth::user()->id_establecimiento}}">
                <input type="hidden" name="opcion" id="opcion" value="{{$opcion}}">
                <div class="row">
                  <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                      <label class="form-control-sm">Fecha Desde</label>
                      <input class="form-control input-sm" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde" value="<?php echo $fechaDesde?>" maxlength="10">
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                      <label class="form-control-sm">Fecha Hasta</label>
                      <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta" value="<?php echo $fechaHasta?>" maxlength="10">
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                      <label class="form-control-sm">Nro. Documento</label>
                      <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder=""
                        class="form-control input-sm" maxlength="12" value="<?php echo $dni_beneficiario ?>">
                  </div>

                  <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                      <label class="form-control-sm">Nombres</label>
                      <input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder=""
                        class="form-control input-sm text-uppercase" maxlength="60" value="<?php echo $nombre_beneficiario ?>">
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="padding-top:20px;padding-right:0px">
                      <div class="col-md-4">
                          <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
                      </div>
                      <div class="col-md-4">
                            <a class="btn btn-success form-control input-sm" href="javascript:void(0)"
                                onclick="reporteEvaluacion2('{{ Auth::user()->id }}')">
                                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                            </a>
                      </div>
                      <div class="col-md-4">
                            <a class="btn btn-success form-control input-sm" href="javascript:void(0)"
                                onclick="reporteEvaluacionDetallado('{{ Auth::user()->id }}')">
                                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Detallado
                            </a>
                      </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Prioridad</label>
                    <select id="id_prioridad" name="id_prioridad"   onchange="getTopico();" class="form-control">
                      <option value="0">-Seleccionar-</option>
                      @foreach($opcionesprioridad as $e)
                        <option value="{{$e->id}}">{{$e->descripcion}}</option>

                      @endforeach
                    </select>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Area</label>
                    <select class="form-control input-sm" name="id_topico" id="id_topico" required="" style="width: 100%">
                      <option value="0">-Seleccionar-</option>
                      <option value="1">SHOCK TRAUMA</option>
                      <option value="2">OBS TRAUMATOLOGIA</option>
                      <option value="4">OBS PEDIATRIA</option>
                      <option value="6">OBS GINECOLOGÍA Y OBSTETRICIA</option>
                      <option value="10">OBS CIRUGIA</option>
                      <option value="9">OBS MEDICINA</option>
                      <option value="3">TOP TRAUMATOLOGIA</option>
                      <option value="5">TOP PEDIATRIA</option>
                      <option value="7">TOP GINECOLOGÍA Y OBSTETRICIA</option>
                      <option value="11">TOP CIRUGIA</option>
                      <option value="8">TOP MEDICINA</option>
                    </select>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <label class="form-control-sm">Estado</label>
                    <select class="form-control input-sm" id="estado" name="estado" tabindex="5" style="width: 100%">
                      <option value="3" selected="selected">En Evaluacion</option>
                      <option value="4">Todos</option>
                    </select>
                  </div>
                </div>
            </form>
            <div class="col-md-12"  id="global">
                <table id="tblEmergenciaTriaje" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>N</th>
                            <th>Última Atención</th>
                            <th>Hora</th>
                            <th>Prioridad</th>
                            <th>Topico</th>
                            <th>Numero Doc</th>
                            <th>Paciente</th>
                            <th>Grado</th>
                            <th>Parentesco</th>
                            <th>Sexo</th>
                            <th>Edad</th>
                            <th style="text-align:center">Evaluacion</th>
                            <th style="text-align:center">Siteds</th>
                            <!--th style="text-align:center">Agregar</th-->
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="link-reporte"></div>
@include('frontend.includes.footer')
@endsection

