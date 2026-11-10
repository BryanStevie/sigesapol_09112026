<?php 
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
@extends('frontend.layouts.master2')

@section('renderjs')
@include('frontend.admision.emergencia.triaje.ver_triaje.function.function_edad')
@include('frontend.admision.emergencia.triaje.ver_triaje.function.ver_triaje')
<style> 
 
  /*************Styles de sweetAlert2*************/
  .swal2-container:not(.swal2-top):not(.swal2-top-start):not(.swal2-top-end):not(.swal2-top-left):not(.swal2-top-right):not(.swal2-center-start):not(.swal2-center-end):not(.swal2-center-left):not(.swal2-center-right):not(.swal2-bottom):not(.swal2-bottom-start):not(.swal2-bottom-end):not(.swal2-bottom-left):not(.swal2-bottom-right):not(.swal2-grow-fullscreen)>.swal2-modal {
    margin: 15rem auto auto auto !important;
  }

  .swal2-popup {
    width: 50em !important; 
  }

  .swal2-popup,
  .swal2-header {
    padding: 0 !important;
  }

  .swal2-header {
    background: #3c763d;
  }

  .swal2-html-container {
    margin: 0 !important;
    padding: 1rem 0 !important;
  }

  .titulo-padding {
    padding: 1rem 0 0 0;
  }

  .titulo-padding p {
    color: white;
    margin: 0;
    font-size: 2.5rem;
  }

  .descripcion-alert {
    font-size: 1.5rem;
    padding: 0 1rem;
  }

  .descripcion-alert {
    margin: 0;
  }

  .descripcion-alert span {
    font-weight: 700;
  }

  .swal2-actions {
    margin: 0 !important;
    padding: 1rem;
    justify-content: space-evenly;
  }

  .swal2-actions button {
    margin: 0 !important;
  }

  .borde-alerta {
    border-color: red !important;
    border: 2px red solid !important;
    outline: 0;
    border-radius: 4px;
    box-shadow: inset 0 1px 1px red !important, 0 0 8px red !important;
  }
</style>
<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>  

    presionar_keypress_input('#edad_desde');
    presionar_keypress_input('#edad_hasta');
    presionar_keypress_input('#nombre_beneficiario');

    function presionar_keypress_input(input){ 
        $(input).keypress(function(event) {
            if (event.which === 13) { 
                event.preventDefault();  
                datatablenew();
            }
        });
    }


    $('#ubicacion').change(function(){ 
      validar_visible();
      datatablenew();
    });
    $('#id_prioridad').change(function(){   
        datatablenew();
    });
    $('#id_topico').change(function(){  
        datatablenew();
    });
    $('#id_motivo_ingreso').change(function(){  
        datatablenew();
    });
    $('#id_tipo_violencia').change(function(){  
        datatablenew();
    });
    

  function validar_visible(){ 
    $ubicacion = $('#ubicacion').val();
    
    $('#id_prioridad').val('').change();
    $('#id_topico').val('').change();
    $('#id_motivo_ingreso').val('').change();
    $('#id_tipo_violencia').val('').change();

    //validamos visible 
    if($ubicacion==0){  
      $('#seccion_filtro_add').hide();
    }else if($ubicacion==1 ||$ubicacion==3 ||$ubicacion==4){ 
      $('#seccion_filtro_add').show(); 
    }else{ 
      $('#seccion_filtro_add').hide();
    }
  } 

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
                }else{ 
                  datatablenew();
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
                }else{ 
                  datatablenew();
                }
    	
            });
        });

    $('#btnBuscar').click(function () { 
      var edad_inicial = $('#edad_desde').val();
      var edad_final = $('#edad_hasta').val(); 

        if(edad_inicial!='' && edad_final!=''){  
            if(edad_inicial  > edad_final){ 
                Swal.fire({
                title: '<div class="titulo-padding"><p>Alerta de Reporte</p></div>',
                html: '<p class="descripcion-alert"><span>RECUERDE INGRESAR DE FORMA CORRECTA UNA EDAD FINAL VÁLIDA.<br><br></span></p>', 
                allowOutsideClick: false,
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
                });

                setTimeout(function() {  
                    $('#edad_hasta').val(""); 
                    $('#edad_hasta').focus();  
                }, 1000); 
                return 0;
            }else{
                fn_ListarBusqueda();
            }
        }else if(edad_inicial=='' && edad_final!=''){
                Swal.fire({
                title: '<div class="titulo-padding"><p>Alerta de Reporte</p></div>',
                html: '<p class="descripcion-alert"><span>RECUERDE INGRESAR DE FORMA CORRECTA UNA EDAD INICIAL VÁLIDA.<br><br></span></p>', 
                allowOutsideClick: false,
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
                });

                setTimeout(function() {  
                    $('#edad_desde').val(""); 
                    $('#edad_desde').focus();   
                }, 1000); 
                return 0;

        }else if(edad_inicial!='' && edad_final==''){

            Swal.fire({
                title: '<div class="titulo-padding"><p>Alerta de Reporte</p></div>',
                html: '<p class="descripcion-alert"><span>RECUERDE INGRESAR DE FORMA CORRECTA UNA EDAD FINAL VÁLIDA.<br><br></span></p>', 
                allowOutsideClick: false,
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
                });

                setTimeout(function() {  
                    $('#edad_hasta').val(""); 
                    $('#edad_hasta').focus(); 
                }, 1000); 
                return 0;
        }else{
            fn_ListarBusqueda();
        }
    });


    $(document).ready(function () {
        $('#seccion_tipo_violencia').hide(); 
        $("#system-search").keyup(function() {
            var dataTable = $('#example').dataTable();
            dataTable.fnFilter(this.value);
        });
 
        $('#fecha_fin').trigger('change');
        $('#fecha_ini').trigger('change');
         
        var activeSystemClass = $('.list-group-item.active');
        
        validar_visible();
        
    }); 

    $(document).ready(function () {
    	
    	$('#btnBuscar').click(function () {
    		fn_ListarBusqueda();
    	});
    	
    	datatablenew();
    	 	
    });
 


    function fn_ListarBusqueda() {
       datatablenew();
    }

    function anularTriaje(id_atencion){
    
        bootbox.confirm({ 
          size: "small",
          //size: "short",
          message: "&iquest;Desea anular el triaje registrado?", 
          callback: function(result){
            if (result==true) {
              fn_anularTriaje(id_atencion);
            }
          }
        });
        
    }

    function fn_anularTriaje(id){
      $.ajax({
        url: '/anular_emergencia_triaje/' + id,
        dataType: "json",
        success: function(result){
          if(result.sw == true){
              location.href="all_triajes_atendidos";
          }
        }
      });
    }

    $('#edad_desde').keydown(function(event) {
        // Permitir solo teclas numéricas y algunas teclas especiales
        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||
        // Permitir: Ctrl+A
        (event.keyCode == 65 && event.ctrlKey === true) ||
        // Permitir: Ctrl+C
        (event.keyCode == 67 && event.ctrlKey === true) ||
        // Permitir: Ctrl+V
        (event.keyCode == 86 && event.ctrlKey === true) ||
        // Permitir: Ctrl+X
        (event.keyCode == 88 && event.ctrlKey === true) ||
        // Permitir: home, end, left, right
        (event.keyCode == 13) ||
        //Permitir ENTER
        (event.keyCode >= 35 && event.keyCode <= 39)) {
             
        // Permitir la tecla
        return;
        }
        else {
        // Rechazar cualquier otro carácter que no sea número
        if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
            event.preventDefault();
        }
        }
    }); 

    $('#edad_hasta').keydown(function(event) {
        // Permitir solo teclas numéricas y algunas teclas especiales
        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||
        // Permitir: Ctrl+A
        (event.keyCode == 65 && event.ctrlKey === true) ||
        // Permitir: Ctrl+C
        (event.keyCode == 67 && event.ctrlKey === true) ||
        // Permitir: Ctrl+V
        (event.keyCode == 86 && event.ctrlKey === true) ||
        // Permitir: Ctrl+X
        (event.keyCode == 88 && event.ctrlKey === true) ||
        // Permitir: home, end, left, right
        (event.keyCode == 13) ||
        //Permitir ENTER
        (event.keyCode >= 35 && event.keyCode <= 39)) {
        // Permitir la tecla
        return;
        }
        else {
        // Rechazar cualquier otro carácter que no sea número
        if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
            event.preventDefault();
        }
        }
    });     
 
  

    function reporteTriajesxEmergencia(idUser){ //--------- vista --- lista de triajes x emergencia
      var id_establecimiento = $('#id_establecimiento').val();
      var fecha_ini = $('#fecha_ini').val();
      var fecha_fin = $('#fecha_fin').val();
      var dni_beneficiario = "0"; //$('#dni_beneficiario').val();
      var nombre_beneficiario = $('#nombre_beneficiario').val();
      
      var estado_triaje = $('#estado_emergencia').val(); 
      
      var ubicacion = $('#ubicacion').val();
      var id_prioridad = $('#id_prioridad').val();
      var id_topico = $('#id_topico').val();
      var id_motivo_ingreso = $('#id_motivo_ingreso').val();
      var id_tipo_violencia = $('#id_tipo_violencia').val();
      var edad_inicial = $('#edad_desde').val();
      var edad_final = $('#edad_hasta').val(); 
 

        if(edad_inicial!='' && edad_final!=''){  
            if(edad_inicial  > edad_final){ 
                Swal.fire({
                title: '<div class="titulo-padding"><p>Alerta de Reporte</p></div>',
                html: '<p class="descripcion-alert"><span>RECUERDE INGRESAR DE FORMA CORRECTA UNA EDAD FINAL VÁLIDA.<br><br></span></p>', 
                allowOutsideClick: false,
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
                });

                setTimeout(function() {  
                    $('#edad_hasta').val(""); 
                    $('#edad_hasta').focus();  
                }, 1000); 
                return 0;
            }else{ 
                if (fecha_ini == "")fecha_ini = 0;
                if (fecha_fin == "")fecha_fin = 0;
                if (dni_beneficiario == "")dni_beneficiario = "0";         //
                if (nombre_beneficiario == "")nombre_beneficiario = "-";   // hice la asignación = ""; ... y habia error ... por q   0 ?  

                if (ubicacion == "")ubicacion = "0"; 
                if (id_prioridad == "")id_prioridad = "0";
                if (id_motivo_ingreso == "")id_motivo_ingreso = "0"; 
                if (id_tipo_violencia == "")id_tipo_violencia = "0"; 
                if (id_topico == "")id_topico = "0"; 
                if (edad_inicial == "")edad_inicial = "0"; 
                if (edad_final == "")edad_final = "0"; 
                location.href = '{{ url('exportar_emergencia_atenciones_repo') }}' + '/' + idUser + '/' + fecha_ini + '/' + fecha_fin + '/' + dni_beneficiario + '/' + nombre_beneficiario + '/' + estado_triaje + '/' + ubicacion+ '/' + id_prioridad + '/' + id_topico + '/' +  id_motivo_ingreso + '/' +  id_tipo_violencia + '/' + edad_inicial + '/' + edad_final+ '/exportar'  ;
            }    
        }else if(edad_inicial=='' && edad_final!=''){
                Swal.fire({
                title: '<div class="titulo-padding"><p>Alerta de Reporte</p></div>',
                html: '<p class="descripcion-alert"><span>RECUERDE INGRESAR DE FORMA CORRECTA UNA EDAD INICIAL VÁLIDA.<br><br></span></p>', 
                allowOutsideClick: false,
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
                });

                setTimeout(function() {  
                    $('#edad_desde').val(""); 
                    $('#edad_desde').focus();   
                }, 1000); 
                return 0;

        }else if(edad_inicial!='' && edad_final==''){

            Swal.fire({
                title: '<div class="titulo-padding"><p>Alerta de Reporte</p></div>',
                html: '<p class="descripcion-alert"><span>RECUERDE INGRESAR DE FORMA CORRECTA UNA EDAD FINAL VÁLIDA.<br><br></span></p>', 
                allowOutsideClick: false,
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
                });

                setTimeout(function() {  
                    $('#edad_hasta').val(""); 
                    $('#edad_hasta').focus(); 
                }, 1000); 
                return 0;
        }   else{ 
                if (fecha_ini == "")fecha_ini = 0;
                if (fecha_fin == "")fecha_fin = 0;
                if (dni_beneficiario == "")dni_beneficiario = "0";         //
                if (nombre_beneficiario == "")nombre_beneficiario = "-";   // hice la asignación = ""; ... y habia error ... por q   0 ?  

                if (ubicacion == "")ubicacion = "0"; 
                if (id_prioridad == "")id_prioridad = "0";                 
                if (id_topico == "")id_topico = "0"; 
                if (id_motivo_ingreso == "")id_motivo_ingreso = "0"; 
                if (id_tipo_violencia == "")id_tipo_violencia = "0"; 
                if (edad_inicial == "")edad_inicial = "0"; 
                if (edad_final == "")edad_final = "0"; 
                location.href = '{{ url('exportar_emergencia_atenciones_repo') }}' + '/' + idUser + '/' + fecha_ini + '/' + fecha_fin + '/' + dni_beneficiario + '/' + nombre_beneficiario + '/' + estado_triaje + '/' + ubicacion+ '/' + id_prioridad + '/' + id_topico + '/' + id_motivo_ingreso  + '/' + id_tipo_violencia  + '/' + edad_inicial + '/' + edad_final+ '/exportar'  ;
            }    
    }
 
    function getArea_topico (){ 
        var id_prioridad = $('#id_prioridad').val(); 
        var sexo = $('#sexo').val();
        var edad = $('#edad_pac').val();
        var id_prioridad = $('#id_prioridad').val();
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
                $('#id_topico').append("<option value=''>--SELECCIONAR--</option>"); 
                $(result).each(function(ii, oo) {  
                $('#id_topico').append("<option value='" + oo.id + "'>" + oo.descripcion + "</option>"); 
                }); 
                $('#id_topico').val("").change();  
                if(id_prioridad=='1'){
                    $('#id_topico').val("1").change(); 
                }else if(id_prioridad=='4'){
                    $('#id_topico').append("<option value='19'>CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)</option>"); 
                    $('#id_topico').val("19").change(); 
                }else{
                    $('#id_topico').val("").change();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if(id_prioridad!='4'){
                $('#id_topico').empty();  
                $('#id_topico').append("<option value=''>--SELECCIONAR--</option>"); 
                $('#id_topico').val("").change(); 
                $('#observacion').focus(); 
                }else{
                $('#id_topico').empty();  
                $('#id_topico').append("<option value='19'>CONSULTORIO DE ATENCIÓN RÁPIDA (CAR)</option>"); 
                $('#id_topico').val("19").change(); 
                }
            }
            }); 
        }else{
        $('#id_topico').empty();  
        $('#id_topico').append("<option value=''>--SELECCIONAR--</option>"); 
        $('#id_topico').val("").change(); 
        }
    }

    function getTipoViolencia (){ 
        var id_motivo_ingreso = $('#id_motivo_ingreso').val();
        var id_tipo_violencia = $('#id_tipo_violencia').val(); 
        if(id_motivo_ingreso!='' && id_motivo_ingreso == 3){
            $('#seccion_tipo_violencia').show();
            //Listar Tipo de Violencia
            var url_consulta='/get_tipo_violencia'; 
            $.ajax({
                url: url_consulta,
                success: function(result) {   
                    $('#id_tipo_violencia').empty();  
                    $('#id_tipo_violencia').append("<option value=''>--SELECCIONAR--</option>"); 
                    $(result).each(function(ii, oo) {  
                    $('#id_tipo_violencia').append("<option value='" + oo.id + "'>" + oo.descripcion + "</option>"); 
                    }); 
                    $('#id_tipo_violencia').val("").change();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#id_tipo_violencia').empty();  
                    $('#id_tipo_violencia').append("<option value=''>--SELECCIONAR--</option>"); 
                    $('#id_tipo_violencia').val("").change();
                }
            }); 
        }else{
        $('#seccion_tipo_violencia').hide();
        $('#id_tipo_violencia').empty();  
        $('#id_tipo_violencia').append("<option value=''>--SELECCIONAR--</option>"); 
        $('#id_tipo_violencia').val("").change(); 

        }
    }

    $("#estado_emergencia").change(function() { 
      datatablenew(); 
    });
    
    $('body').on('click', '#openPdfEmergenciaTriaje', function () {  
        var id_emergencia=$(this).data('id_emergencia');   
        var pdfUrl = "{{ asset('/PdfEmergenciaTriaje/') }}/"+id_emergencia;
        $('#pdfFrame').attr('src', pdfUrl); 
        $('#pdfModal').modal('show');
    });

    function datatablenew(){
      var oTable1 = $('#tblEmergenciaTriaje').dataTable({
        "bServerSide": true,
        "sAjaxSource": "/listar_emergencia_atenciones_repo",
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
        var iNroPagina 	= parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
        var iCantMostrar 	= aoData[4].value;
        var fecha_ini = $('#fecha_ini').val();
        var fecha_fin = $('#fecha_fin').val();
        var estado_triaje = $('#estado_emergencia').val(); 
        var dni_beneficiario = $('#dni_beneficiario').val();
        var nombre_beneficiario = $('#nombre_beneficiario').val();
        var ubicacion = $('#ubicacion').val();
        var id_prioridad = $('#id_prioridad').val();
        var id_topico = $('#id_topico').val();
        var id_motivo_ingreso = $('#id_motivo_ingreso').val();
        var id_tipo_violencia = $('#id_tipo_violencia').val();
        var edad_desde = $('#edad_desde').val();
        var edad_hasta = $('#edad_hasta').val();  
        var id_establecimiento = <?php echo Auth::user()->id_establecimiento; ?>;
              
        var _token = $('#_token').val();
          oSettings.jqXHR = $.ajax({
              "dataType": 'json',
              "type": "POST",
              "url": sSource,
              "data":{
                NumeroPagina:iNroPagina,
                NumeroRegistros:iCantMostrar,
                fecha_ini:fecha_ini,
                fecha_fin:fecha_fin, 
                dni_beneficiario:dni_beneficiario,
                nombre_beneficiario:nombre_beneficiario,
                id_establecimiento:id_establecimiento,
                estado_triaje: estado_triaje,
                ubicacion: ubicacion,
                id_prioridad: id_prioridad,
                id_topico: id_topico,
                id_motivo_ingreso: id_motivo_ingreso,
                id_tipo_violencia: id_tipo_violencia,
                edad_desde: edad_desde,
                edad_hasta: edad_hasta, 
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
                var num = "";
                if(row.cod_emergencia!= null)num = row.cod_emergencia;  //num
                return num;
              },
              "bSortable": false,
              "aTargets": [0]
            },
            {
              "mRender": function (data, type, row) {
                var num ="<b>"+row.tipo_doc_ident +": "+row.nro_doc_ident + "</b><br>" +
                row.paterno+ " " +row.materno+ " " +row.nombre;
                var fecha_nac='';
                if(row.fecha_nac == null){
                  fecha_nac='---';
                }else{ 
                  fecha_nac = calcularEdadPersonaReturn(row.fecha_nac);
                }

                var seccion ="<br><b>EDAD:</b> "+ fecha_nac +" <br> <b>SEXO:</b> "+ row.sexo_asegurado ; 
                return num + seccion;
              },
              "bSortable": false,
              "aTargets": [1]
            },
            {
              "mRender": function (data, type, row) {
                var area_servicio = "";var tipo_violencia = "";
                var area_servicio ="<b>PRIORIDAD: "+row.prioridad + "</b>" +
                    "<br><b>SERVICIO: </b>"+row.topico + 
                    "<br><b>F.ING: </b>"+row.forma_ingreso+" - ("+row.estado_ingreso+")"+
                    "<br><b>MOTIVO: </b>"+row.motivo_ingreso;
                if(row.violencia!=null){
                    var tipo_violencia ="<br><b>TIPO DE VIOLENCIA: </b>"+row.violencia  ;
                }
                return area_servicio +' '+ tipo_violencia;
              },
              "bSortable": false,
              "aTargets": [2]
            },  
            {
              "mRender": function (data, type, row) {
                var totem =(row.hora_totem == null) ? "<b>FECHA:</b>   "+row.fecha_totem : "<b>FECHA:</b>   "+row.fecha_totem + '<br><b>HORA:</b>  ' + row.hora_totem; 
                if(totem==null){
                    return ""; 
                }else{
                    if(row.fecha_totem==null){
                        return ""; 
                    }else{
                        return "<b>TICKET: "+row.cod_totem + "</b><br>"+ totem; 
                    }
                } 
              },
              "bSortable": false,
              "aTargets": [3]
            }, 
            {
              "mRender": function (data, type, row) {
                var totem =(row.hora_registro_ingreso_triaje == null) ? "<b>FECHA:</b>  "+row.fecha_registro_ingreso_triaje : "<b>FECHA:</b>  "+row.fecha_registro_ingreso_triaje + '<br><b>HORA:</b>  ' + row.hora_registro_ingreso_triaje; 
                if(totem==null){
                    return ""; 
                }else{ 
                    if(row.apepaterno_profesional==null){
                        return ""; 
                    }else{
                        var ver_mas='<a href="javascript:void(0)" id="openPdfEmergenciaTriaje" name="openPdfEmergenciaTriaje"  data-id_emergencia="'+row.cod_emergencia+'" > <b>VER DETALLES</b> </a>';
                        
                        var ver_anulado='';
                        if(row.id_user_anula_triaje !=null){
                          ver_anulado='<a href="javascript:void(0)" id="ver_detalles_anulación" name="ver_detalles_anulación" ';
                          ver_anulado+=' data-id_emergencia="'+row.cod_emergencia+'" > <b style="color:red;">VER DETALLES DE ANULACIÓN</b> </a>';
                        }
                        return "<b>"+row.apepaterno_profesional +" "+row.nombre_profesional+ "</b><br>"+ totem+ "<br><b>"+ row.trama_triaje+"</b><br>"; 
                    }
                } 
              },
              "bSortable": false,
              "aTargets": [4]
            },
            {
              "mRender": function (data, type, row) {
                var totem = (row.hora_admision == null) ? "<b>FECHA:</b>  "+row.fecha_admision : "<b>FECHA:</b>  "+row.fecha_admision + '<br><b>HORA:</b>  ' + row.hora_admision; 
                if(totem==null){
                    return ""; 
                }else{
                    if(row.apepaterno_user_adm==null||row.hora_admision==null||row.fecha_admision==null){
                        return ""; 
                    }else{
                        return "<b>"+row.apepaterno_user_adm +" "+row.nombre_user_adm+ "</b><br>"+ totem+ "<br><b>"+ row.trama_admision+"</b>";  
                    }
                } 
              },
              "bSortable": false,
              "aTargets": [5]
            },
            {
              "mRender": function (data, type, row) {
                var totem = (row.hora_atencion == null) ? "<b>FECHA:</b>  "+row.fecha_atencion : "<b>FECHA:</b>  "+row.fecha_atencion + '<br><b>HORA:</b>  ' + row.hora_atencion; 
                if(totem==null){
                    return ""; 
                }else{
                    if(row.hora_atencion==null||row.hora_admision==null||row.fecha_atencion==null){
                        return ""; 
                    }else{
                        return "<b>"+row.apepaterno_user_atencion +" "+row.nombre_user_atencion+ "</b><br>"+ totem+ "<br><b>"+ row.trama_atencion+"</b>";  
                    } 
                } 
              },
              "bSortable": false,
              "aTargets": [6]
            },
            {
              "mRender": function (data, type, row) {
                let data_ultimo_personal_atencion_evaluacion = "";
                if (row.ultimo_personal_evaluacion != "") {
                  data_ultimo_personal_atencion_evaluacion = `<b>${row.ultimo_personal_evaluacion}</b><br>
                    <b>FECHA: </b>${row.ultima_fecha_atencion_evaluacion}<br>
                    <b>HORA: </b>${row.ultima_hora_atencion_evaluacion}<br>
                    <b>${row.trama_ultima_atencion_evaluacion}</b>`;
                }
                return data_ultimo_personal_atencion_evaluacion;
              },
              "bSortable": false,
              "aTargets": [7]
            },
            {
              "mRender": function (data, type, row) {
                var totem = ''; 
                if(row.hora_alta_medica_alterna==null){
                    totem = (row.hora_alta_medica == null) ? "<b>FECHA:</b>  "+row.fecha_alta_medica : "<b>FECHA:</b>  "+row.fecha_alta_medica + '<br><b>HORA:</b>  ' + row.hora_alta_medica;
                }else{
                    totem = (row.hora_alta_medica_alterna == null) ? "<b>FECHA:</b>  "+row.fecha_alta_medica : "<b>FECHA:</b>  "+row.fecha_alta_medica + '<br><b>HORA:</b>  ' + row.hora_alta_medica_alterna;
                }
                if(totem==null){
                    return ""; 
                }else{
                    if(row.apepaterno_user_alta==null||row.fecha_alta_medica==null){
                        return ""; 
                    }else{
                      var tipo_egreso_medico='';
                        if(row.tipo_egreso_medico!=null){
                          tipo_egreso_medico = '<br><b>TIPO DE ALTA MÉDICA:</b><br>' + row.tipo_egreso_medico;
                        }
                        return "<b>"+row.apepaterno_user_alta +" "+row.nombre_user_alta+ "</b><br>"+ totem+ "<br><b>"+ row.trama_alta_medica+"</b>"+tipo_egreso_medico;   
                    }
                } 
              },
              "bSortable": false,
              "aTargets": [8]
            },
            {
              "mRender": function (data, type, row) {
                var totem = ''; 
                if(row.hora_egreso_administrativa_alterna==null){
                    totem = (row.hora_egreso_administrativa == null) ? "<b>FECHA:</b>  "+row.fecha_egreso_administrativa : "<b>FECHA:</b>  "+row.fecha_egreso_administrativa + '<br><b>HORA:</b>  ' + row.hora_egreso_administrativa; 
                }else{
                    totem = (row.hora_egreso_administrativa_alterna == null) ? "<b>FECHA:</b>  "+row.fecha_egreso_administrativa : "<b>FECHA:</b>  "+row.fecha_egreso_administrativa + '<br><b>HORA:</b>  ' + row.hora_egreso_administrativa_alterna; 
                }
                if(totem==null){
                    return ""; 
                }else{
                  var trama_alta_administrativa='';
                    if(row.apepaterno_user_admins==null||row.fecha_egreso_administrativa==null){
                        return ""; 
                    }else{
                      if(row.trama_alta_medica==null){
                        if(row.trama_alta_administrativa_sin_medica!=null){
                          trama_alta_administrativa=row.trama_alta_administrativa_sin_medica;
                        }
                      } else{
                        trama_alta_administrativa=row.trama_alta_administrativa;
                      }

                      return "<b>"+row.apepaterno_user_admins +" "+row.nombre_user_admins+ "</b><br>"+ totem+ "<br><b>"+ trama_alta_administrativa+"</b>";
                    } 
                }
              },
              "bSortable": false,
              "aTargets": [9]
            },
          ]
      });
    }
</script>
@endsection
@section('content')
<div class="container-fluid" style="width:90%">
  <div class="panel panel-primary filterable">
    <div class="panel-heading">
      <h3 class="panel-title">Lista de Pacientes con Triaje</h3>
    </div>
    <div class="panel-body">
      <form class="form-horizontal" method="post" action="{{ route('listar_triaje_emergencia_atendidos')}}" name="frmTriajeEmergencia" >
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
        <input type="hidden"  value="" name="dni_beneficiario" type="dni_beneficiario">
        <input type="hidden" name="id_establecimiento" id="id_establecimiento"
          value="{{Auth::user()->id_establecimiento}}">
        <div class="row">
          <div class="col-lg-1 col-md-2 col-sm-6 col-xs-12">
            <label class="form-control-sm">FEC.DESDE</label>
            <input class="form-control input-sm" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde"
              value="<?php echo $fechaDesde?>" maxlength="10">
          </div>
          <div class="col-lg-1 col-md-2 col-sm-6 col-xs-12">
            <label class="form-control-sm">FEC.HASTA</label>
            <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta"
              value="<?php echo $fechaHasta?>" maxlength="10">
          </div>
          <div class="col-lg-1 col-md-3 col-sm-6 col-xs-12">
            <label class="form-control-sm">EDAD INICIAL</label>
            <input id="edad_desde" name="edad_desde" type="text" placeholder="" autocomplete="off"
              class="form-control input-sm text-uppercase" maxlength="3" value="" >
          </div>
          <div class="col-lg-1 col-md-3 col-sm-6 col-xs-12">
            <label class="form-control-sm">EDAD FINAL</label>
            <input id="edad_hasta" name="edad_hasta" type="text" placeholder="" autocomplete="off"
              class="form-control input-sm text-uppercase" maxlength="3" value="">
          </div>

          <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
            <label class="form-control-sm">BUSQUEDA NOMB/DNI</label>
            <input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder=""
              class="form-control input-sm text-uppercase" maxlength="60" value="" autocomplete="off">
          </div> 
          

          <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
            <label class="form-control-sm">UBICACIÓN</label>
            <select class="form-control input-sm" name="ubicacion" id="ubicacion" style="height: 30px;"> 
              <option value="0"> --SELECCIONAR--</option>
              <option value="1"> TRIAJE</option>
              <option value="2"> ADMISIÓN</option>
              <option value="3"> ATENCIÓN MÉDICA</option>
              <option value="4"> EVALUACIÓN</option>
              <option value="5"> ALTA MÉDICA</option>
              <option value="6"> ALTA ADMINISTRATIVA</option>
            </select>
          </div> 
           
           <div id="seccion_filtro_add" name="seccion_filtro_add">
             <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
               <label>PRIORIDAD</label>
               <select class="form-control input-sm" id="id_prioridad" name="id_prioridad" onchange="getArea_topico();">
                 <option value="" selected="">--SELECCIONAR--</option>
                 @foreach($prioridades as $p)
                  <option value="{{$p->id}}">{{$p->prioridad}}</option>
                 @endforeach
               </select>
             </div>
 
             <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
               <label for="topico">AREA/SERVICIO</label>
               <select class="form-control input-sm" id="id_topico" name="id_topico">
                 <option value="" selected="">--SELECCIONAR--</option>
               </select>
             </div> 
           </div>
        </div>
        <div class="row"  >
          <br>
            <div id="seccion_filtro_add" name="seccion_filtro_add">
                <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                    <label>MOTIVO DE INGRESO</label>
                    <select class="form-control input-sm" id="id_motivo_ingreso" name="id_motivo_ingreso" onchange="getTipoViolencia();">
                      <option value="" selected="">--SELECCIONAR--</option>
                      @foreach($motivo_ingreso as $mot_ing)
                      <option value="{{$mot_ing->id}}">{{$mot_ing->descripcion}}</option>
                      @endforeach
                    </select>
                </div>
                <div id="seccion_tipo_violencia" name="seccion_tipo_violencia" class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                    <label for="topico">TIPO DE VIOLENCIA</label>
                    <select class="form-control input-sm" id="id_tipo_violencia" name="id_tipo_violencia">
                        <option value="" selected="">--SELECCIONAR--</option>
                    </select>
                </div> 
            </div>
            <div class="col-lg-2" >
               <label for="topico" style="color: red">ESTADO DE REGISTROS</label>
               <select class="form-control input-sm" id="estado_emergencia" name="estado_emergencia">
                 <option value="-1" selected>TODOS LOS REGISTROS</option>
                 <option value="1">REGISTROS ACTIVOS</option>
                 <option value="0">REGISTROS ANULADOS</option>
               </select>   
            </div>
        </div>

        <div class="row" > 
          <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12" style="padding-top:20px;">
          </div> 

          <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12" style="padding-top:20px;">
            <a id="btnBuscar" class="form-control btn btn-success input-sm">BUSCAR</a>
          </div> 
          
          <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12" style="padding-top:20px;">
            <a href="javascript:void(0)" class="btn btn-primary form-control input-sm" id="btn_exportar_repo"
              name="btn_exportar_repo" onclick="reporteTriajesxEmergencia('{{ Auth::user()->id }}')">
              <i class="fa fa-file-excel-o" aria-hidden="true"></i> EXPORTAR</a>
          </div> <br><br><br>
        </div>


      </form>
      <div class="" id="global">
        <div class="table-responsive">
          <table id="tblEmergenciaTriaje" class="table table-condensed table-bordered" style="font-size: 14px !important;">
            <thead>
              <tr>
                <th>COD</th>
                <th>PACIENTE</th>
                <th name='fecha_de_registro' id='fecha_de_registro'>AREA/SERVICIO</th>
                <th name='hora_de_registro' id='hora_de_registro' style="width: 120px;">TOTEM</th>
                <th name='hora_de_registro' id='hora_de_registro' style="width: 180px;">TRIAJE</th> 
                <th name='hora_de_registro' id='hora_de_registro' style="width: 180px;">ADMISIÓN</th> 
                <th name='hora_de_registro' id='hora_de_registro' style="width: 180px;">ATENCIÓN</th> 
                <th name='hora_de_registro' id='hora_de_registro' style="width: 180px;">ÚLTIMO PERSONAL EVALUACIÓN</th>
                <th name='hora_de_registro' id='hora_de_registro' style="width: 180px;">ALTA MÉDICA</th>
                <th name='hora_de_registro' id='hora_de_registro' style="width: 180px;">ALTA ADMINISTRATIVA</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal" id="pdfModal" name="pdfModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <b>VISUALIZACIÓN DETALLADO DE PDF</b>
            </div> 
 
            <div class="modal-body">
                <iframe id="pdfFrame" src="" width="100%" height="600"></iframe>
            </div>
        </div>
    </div>
</div>

@include('frontend.admision.emergencia.triaje.ver_triaje.modal.ver_triaje')

@include('frontend.includes.footer')
@endsection