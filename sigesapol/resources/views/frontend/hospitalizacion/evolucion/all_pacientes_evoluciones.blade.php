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

    .swal-hc{
        width: 350px !important;
        padding: 1rem !important;
    }
    .swal-hc-popup {
        border: 3px solid #e74c3c;
        border-radius: 16px;
    }
    .swal-hc-icon {
        transform: scale(0.5);
        margin: 0;
        font-size: 40px!important;
    }
    .swal-hc-title {
        color: #e74c3c !important;
        font-weight: 700 !important;
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
                if(fechaDesde==""){
                    bootbox.alert("Debe seleccionar una fecha inicial");
                    return false;
                }
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
                if(fechaHasta==""){
                    bootbox.alert("Debe seleccionar una fecha final");
                    return false;
                }
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

        $("#id_servicio_destino").select2({ width: 'resolve' });

        <?php if($id_user_consultorio > 0){?>
          $("#id_servicio_destino").val("<?php echo $id_user_consultorio; ?>").prop('disabled', true).select2();
        <?php }?>
        
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

  function irANuevaEvolucionHospitalizacion(idHospitalizacion) {
    $.ajax({
      url: '/ajax/verificar_hc_evolucion/' + idHospitalizacion,
      type: 'GET',
      dataType: 'json',
      success: function(resp) {
        if (resp.status === 'ok') {
          window.location.href = '/form_evolucion_hospitalizacion/' + idHospitalizacion;
        } else if (resp.status === 'completar') {
          window.location.href = '/form_atencion_prestacion_hspcu/<?php echo Auth::user()->id; ?>/' + idHospitalizacion;
        } else {
          Swal.fire({
            icon: 'warning',
            iconColor: '#e74c3c',
            title: 'ALERTA',
            html: '<span style="font-size:23px;">El registro de la Historia Clínica aún no está terminada, por lo que no se puede realizar dicha acción.<br><br><b>Médico responsable del registro:</b><br><span style="color:#e74c3c;font-weight:bold;font-size:19px;">' + resp.medico + '</span></span>',
            width: 500,
            customClass: {
              popup: 'swal-hc-popup',
              icon: 'swal-hc-icon',
              title: 'swal-hc-title'
            }
          });
        }
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: 'ERROR',
          text: 'No se pudo verificar el estado de la Historia Clínica. Intente nuevamente.'
        });
      }
    });
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
            let paciente_ndocumento = $('#dni_beneficiario').val();
            let paciente_nombre = $('#nombre_beneficiario').val();
            let id_establecimiento = <?php echo Auth::user()->id_establecimiento; ?>;
            let id_servicio = $('#id_servicio_destino').val();
            let estado = $('#estado').val();

            Swal.showLoading();
            $.ajax({
                type: "POST",
                url: "exportar_reporte_hospitalizacion_evaluaciones",
                data: {
                    "fecha_ini" : fecha_ini,
                    "fecha_fin" : fecha_fin,
                    "paciente_ndocumento" : paciente_ndocumento,
                    "paciente_nombre" : paciente_nombre,
                    "id_establecimiento": id_establecimiento,
                    "id_servicio": id_servicio,
                    "estado" : estado
                },
                success: function (response) {
                    
                    if (response.excelNameResponse) {
                        // Obtener la URL del archivo del response
                        let fileUrl = `/download_reportes/${response.excelNameResponse}`;
                        // Crear un enlace de descarga
                        let link = document.createElement('a');
                        link.href = fileUrl;
                        link.download = 'Exportar_Reportes_de_Evaluacion_.xlsx';
                        link.innerText = 'Descargar archivo';
                        // Simular un clic en el enlace para iniciar la descarga
                        let divLinkReporte = $('#link-reporte');
                        divLinkReporte.append(link);
                        link.click();
                        divLinkReporte.empty(); // o divLinkReporte.remove(); dependiendo de lo que se necesite hacer


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
            "sAjaxSource": "/listar_evolucion_hospitalizacion_ajax",
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
                var id_establecimiento = <?php echo Auth::user()->id_establecimiento; ?>;
                let id_servicio = $('#id_servicio_destino').val();

if (!id_servicio || id_servicio.trim() === '') {
    id_servicio = 0;
}
                var estado = $('#estado').val();
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
                            id_servicio:id_servicio,
                            estado:estado,
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
                            return (row.fecha_primera_atencion != null) ? row.fecha_primera_atencion : '';
                        },
                        "bSortable": false,
                        "aTargets": [1]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var hora_atencion = "";
                            if(row.hora_primera_atencion!= null)hora_atencion = row.hora_primera_atencion;
                            return hora_atencion;
                        },
                        "bSortable": false,
                        "aTargets": [2]
                    },
                    {
                        "mRender": function (data, type, row) {
                            return (row.dni_beneficiario != null) ? row.dni_beneficiario : '';
                        },
                        "bSortable": false,
                        "aTargets": [3]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var asegurado = "";
                            if(row.paterno != null) asegurado += row.paterno;
                            if(row.materno != null) asegurado += " "+row.materno;
                            if(row.nombre != null) asegurado += " ,"+row.nombre;
                            return asegurado;
                        },
                        "bSortable": false,
                        "aTargets": [4]
                    },
                    {
                        "mRender": function (data, type, row) {
                            return (row.grado != null) ? row.grado : '';
                        },
                        "bSortable": false,
                        "aTargets": [5]
                    },
                    {
                        "mRender": function (data, type, row) {
                            return (row.parentesco != null) ? row.parentesco : '';
                        },
                        "bSortable": false,
                        "aTargets": [6]
                    },
                    {
                        "mRender": function (data, type, row) {
                            return (row.sexo != null) ? row.sexo : '';
                        },
                        "bSortable": false,
                        "aTargets": [7]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var edad = "";
                            if(row.edadpaciente!= null)edad = row.edadpaciente;
                            return edad;
                        },
                        "bSortable": false,
                        "aTargets": [8]
                    }, 
                    {
                        "mRender": function (data, type, row) {
                            return (row.servicio_encargado != null) ? row.servicio_encargado : "";
                        },
                        "bSortable": false,
                        "aTargets": [9]
                    },
                    {
                        "mRender": function (data, type, row) {
                            let servicio_ubicacion = "";
                            if (row.servicio_ubicacion_fisica != null) {
                                servicio_ubicacion += "<b>Servicio: </b>" + row.servicio_ubicacion_fisica;
                            } else {
                                servicio_ubicacion += "<b>Servicio: </b>" + row.cama_servicio_nombre;
                            }
                            servicio_ubicacion += "<br><b>Cama: </b>" + row.cama_nombre;
                            return servicio_ubicacion;
                        },
                        "bSortable": false,
                        "aTargets": [10]
                    },
                    {
                        "mRender": function (data, type, row) {
                            var html = "";
                                
                            <?php if(Auth::user()->tipo_registro==1): ?>
                                if(row.estado == 4){
                                    <?php if($permiso->crear == 1): ?>
                                        html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Registrar Evolución</b>" style="float:left;padding-left:3px">';
                                        <?php if(Auth::user()->id_establecimiento == 76): ?>
                                        html += '<a href="/form_evolucion_hospitalizacion/'+row.id+'" onclick="irANuevaEvolucionHospitalizacion('+row.id+'); return false;"  class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a></div>';
                                        <?php else: ?>
                                        html += '<a href="/form_evolucion_hospitalizacion/'+row.id+'"  class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a></div>';
                                        <?php endif; ?>
                                        html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Evoluciones</b>" style="float:left;padding-left:3px">';
                                        html += '<a href="/ver_atenciones_evaluacion_hospitalizacion/'+row.id+'/'+row.estado+'/3"  class="btn btn-warning btn-xs"><i class="fa fa-eye"></i></a></div>';
                                    <?php else: ?>
                                        html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Evoluciones</b>" style="float:left;padding-left:3px">';
                                        html += '<a href="/ver_atenciones_evaluacion_hospitalizacion/'+row.id+'/'+row.estado+'/3"  class="btn btn-warning btn-xs"><i class="fa fa-eye"></i></a></div>';
                                    <?php endif; ?>
                                } else {
                                    if(row.estado>4) {
                                        html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Paciente de Alta</b>" style="float:left;padding-left:3px">';
                                        html += '<a href="#" disabled="" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a></div>';
                                        html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Evoluciones</b>" style="float:left;padding-left:3px">';
                                        html += '<a href="/ver_atenciones_evaluacion_hospitalizacion/'+row.id+'/'+row.estado+'/3"  class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a></div>';
                                    }
                                }
                            <?php else: ?>
                                html += '<button class="btn btn-info btn-xs" disabled=""><i class="fa fa-medkit"></i></button>';  
                            <?php endif; ?>
                            
                            return html;
                        },
                        "bSortable": false,
                        "aTargets": [11],
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
                                            </p>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Regularizar</b>" class="contenido-btn-div">
                                                <button class="btn btn-primary btn-xs" onclick="regularizeCodeSiteds(${row.id}, ${row.id_asegurado}, ${row.id_establecimiento}, ${row.id_consultorio})">
                                                    <i class="fa fa-check-circle-o"></i> 
                                                </button>
                                            </div>`;
                                } else {
                                    htmlString = `<span class="label label-success" style='font-weight: lighter; font-size: 10px'>${(row.cod_siteds_susalud)}</span>`
                                }
                            }
                            return htmlString;
                        },
                        "bSortable": false,
                        "aTargets": [12],
                        "className": "text-center",
                    }
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
    function regularizeCodeSiteds(id, id_asegurado, id_establecimiento, id_consultorio) {
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
            "aseguradoId": id_asegurado,
            "establecimientoId": id_establecimiento,
            "tipoAtencionId": "3",
            "servicioId": id_consultorio
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
            url: `/hospitalizacion/${id}/update-code-siteds`,
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
                datatablenew();
            },
            error: function(jqXHR, textStatus, errorThrown) {},
            complete: function(xhr, status) {
                Swal.hideLoading();
            }
        });
    };

</script>
@endsection
@section('content')
<div id="blur-container">
<div class="container-fluid">
    <div class="panel panel-primary filterable" >
        <div class="panel-heading" style="padding: 0;">
            <h3 style="display: block; height: 100%; padding: 0 15px;" class="panel-title"> <span style="display: inline-block; padding: 10px 15px; background-color: rgb(236, 236, 117); color: green;">H</span> Lista de Evoluciones Médicas - Hospitalización </h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
                <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{Auth::user()->id_establecimiento}}">
                <div class="row">
                  <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                      <label class="form-control-sm">Fecha Desde</label>
                      <input class="form-control input-sm" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde" value="<?php echo $fechaDesde?>" maxlength="10" required >
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                      <label class="form-control-sm">Fecha Hasta</label>
                      <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta" value="<?php echo $fechaHasta?>" maxlength="10" required = "required" >
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                      <label class="form-control-sm">Nro. Documento</label>
                      <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder=""
                        class="form-control input-sm" maxlength="12" value="">
                  </div>
                  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                      <label class="form-control-sm">Nombres</label>
                      <input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder=""
                        class="form-control input-sm text-uppercase" maxlength="60" value="">
                  </div>
                  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <label>Servicio</label>
                    <select id="id_servicio_destino" name="id_servicio_destino" class="form-control" style="width: 100%">
                        <option value="0">--Seleccionar--</option>
                        @foreach($upss as $row)
                        <option value="{{$row->id_upss}}">{{$row->nombre}}</option>
                        @endforeach
                    </select>
                  </div>
                </div>
                <div class="row">
                  
                  <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                    <label class="form-control-sm">Estado</label>
                    <select class="form-control input-sm" id="estado" name="estado" tabindex="5" style="width: 100%">
                      <option value="4" selected="selected">En Evolución</option>
                      <option value="5">Todos</option>
                    </select>
                  </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <label>&nbsp;</label>
                        <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <label>&nbsp;</label>
                        <a class="btn btn-success form-control input-sm" href="javascript:void(0)"
                            onclick="reporteEvaluacion2('{{ Auth::user()->id }}')">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                        </a>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <label>&nbsp;</label>
                        <a class="btn btn-success form-control input-sm" href="javascript:void(0)"
                            onclick="reporteEvaluacionDetallado('{{ Auth::user()->id }}')">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Detallado
                        </a>
                    </div>
                </div>
            </form>
            <br>
            <div class="table-responsive">
                <table id="tblEmergenciaTriaje" class="table table-bordered table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>N</th>
                            <th>Fecha Última<br>Atención</th>
                            <th>Hora Última<br>Atención</th>
                            <th>N° Documento</th>
                            <th>Paciente</th>
                            <th>Grado</th>
                            <th>Parentesco</th>
                            <th>Sexo</th>
                            <th>Edad</th>
                            <th>Servicio A Cargo</th>
                            <th>Ubicación</th>
                            <th style="text-align:center">Evolución</th>
                            <th style="text-align:center">Siteds</th>
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

