<?php 
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
@extends('frontend.layouts.master2')

@section('renderjs')
@include('frontend.admision.emergencia.triaje.ver_triaje.function.function_edad')
@include('frontend.admision.emergencia.triaje.ver_triaje.function.ver_triaje')

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
</style>

<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
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

  $('#estado_triaje').change(function(){    
    datatablenew();
    valida_estado_triaje();
  });

  function valida_estado_triaje(){ 
    $('#id_anular_column').text('Anular');
      if($('#estado_triaje').val() == 0 ){
        $('#id_topico_column').text('Estado Triaje'); 
      }  
    if ($('#estado_triaje').val() == 0 || $('#estado_triaje').val() == 3 ) { 
      $('#fecha_de_registro').text('Fecha Triaje');  
      $('#hora_de_registro').text('Hora Triaje');   
      $('#codigo_totem_column').text('Personal de Salud');
      $('#id_topico_column').text('Área/Servicio'); 
      $('#id_prioridad_column').text('Prioridad');
      $('#opcion_exportar').show();   
      $('#id_ver_column').text('Ver');    
      $('#id_pdf_column').text('PDF');  
      
      if($('#estado_triaje').val() == 0 ){
        $('#id_topico_column').text('Estado Triaje'); 
      }
    } else if ($('#estado_triaje').val() == -1||$('#estado_triaje').val() == -2) {  
      $('#fecha_de_registro').text('Fecha Totem');  
      $('#hora_de_registro').text('Hora Totem');  
      $('#id_ver_column').text('Ver');  
      $('#id_pdf_column').text('PDF');     
      if($('#estado_triaje').val() == -1){
        $('#id_topico_column').text('Cnt Llamado');
        $('#id_anular_column').text('Llamado');
        
      }else if($('#estado_triaje').val() == -2){
        $('#id_topico_column').text('Cnt.Llamado');
        $('#id_ver_column').text('Mot. Anulación'); 
        $('#id_pdf_column').text('Fecha/Hora Anulación');     
      }
      $('#id_prioridad_column').text('Totem');
      $('#codigo_totem_column').text('Cod. Ticket');
      $('#opcion_exportar').hide();
      $('#ubicacion').val("0").change(); 

    } else if ($('#estado_triaje').val() == 1) {   
      $('#id_topico_column').text('Área/Servicio'); 
      $('#codigo_totem_column').text('Personal de Salud');
      $('#id_prioridad_column').text('Prioridad');
      $('#opcion_exportar').show();    
      $('#id_ver_column').text('Ver');  
      $('#id_pdf_column').text('PDF');     
    }else{ 
      $('#fecha_de_registro').text('Fecha Triaje');  
      $('#hora_de_registro').text('Hora Triaje');  
      $('#id_topico_column').text('Estado Triaje');
      $('#codigo_totem_column').text('Personal de Salud');
      $('#id_prioridad_column').text('Prioridad');
      $('#opcion_exportar').show();
      $('#id_ver_column').text('Ver');
      $('#id_pdf_column').text('PDF');     
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
    	fn_ListarBusqueda();
    });
    
    $(document).ready(function () {   

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
         
        $("#system-search").keyup(function() {
            var dataTable = $('#example').dataTable();
            dataTable.fnFilter(this.value);
        });
 
        $('#fecha_fin').trigger('change');
        $('#fecha_ini').trigger('change');
         
        var activeSystemClass = $('.list-group-item.active'); 
      $('#opcion_exportar').hide();

      $('#id_ver_column').text('Ver');  
    	
      valida_estado_triaje();
    	datatablenew();
      //setInterval(datatablenew, 10000);
        
    });

	
    function soloNumeros(e) {
      var key = window.Event ? e.which : e.keyCode;
      //alert(key);
      return ((key >= 48 && key <= 57) || key == 0 || key == 8)
    } 

    function  datatablenew(){
      var oTable1 = $('#tblEmergenciaTriaje').dataTable({
        "bServerSide": true,
        "sAjaxSource": "/listar_triaje_emergencia_atendidos",
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
        var estado_triaje       = $('#estado_triaje').val();
        var dni_beneficiario = $('#dni_beneficiario').val();
        var nombre_beneficiario = $('#nombre_beneficiario').val(); 
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
                if(row.cod_emergencia!= null)num = row.cod_emergencia;  
                return num;
              },
              "bSortable": false,
              "aTargets": [0]
            },
            {
              "mRender": function (data, type, row) {
                var fecha_triaje = "";
                if(row.fecha_triaje!= null)fecha_triaje = row.fecha_triaje;
                return fecha_triaje;
              },
              "bSortable": false,
              "aTargets": [1]
            },
            {
              "mRender": function (data, type, row) {
                  var hora_triaje = "";
                  if(row.hora_triaje!= null)hora_triaje = row.hora_triaje;
                  return hora_triaje; 
              },
              "bSortable": false,
              "aTargets": [2]
            },
            {
              "mRender": function (data, type, row) {
                  var numero_doc = "";
                  if(row.nro_doc_ident!= null)numero_doc = row.nro_doc_ident;
                  return numero_doc;
              },
              "bSortable": false,
              "aTargets": [3]
            },
            {
              "mRender": function (data, type, row) {
                var asegurado = "";
                if ($('#estado_triaje').val() == -1 || $('#estado_triaje').val() == -2) {   
                  if(row.asegurado!= null)asegurado += row.asegurado;
                }else{
                  if(row.paterno!= null)asegurado += row.paterno;
                  if(row.materno!= null)asegurado += " "+row.materno;
                  if(row.nombre!= null)asegurado += ", "+row.nombre; 
                }
                return asegurado;
              },
              "bSortable": false,
              "aTargets": [4]
            },
            {
              "mRender": function (data, type, row) {
                var sexo_asegurado = "";
                if ($('#estado_triaje').val() == -1 || $('#estado_triaje').val() == -2) {   
                  sexo_asegurado = '---';
                }else{
                  if(row.sexo_asegurado!= null)sexo_asegurado = row.sexo_asegurado;
                }
                return sexo_asegurado;
              },
              "bSortable": false,
              "aTargets": [5]
            },
            {
              "mRender": function (data, type, row) {
                var fecha_nac = "";
                if ($('#estado_triaje').val() == -1 || $('#estado_triaje').val() == -2) {   
                  fecha_nac = '---';
                  return (fecha_nac);
                }else{
                  if(row.fecha_nac == null){
                    return (fecha_nac);
                  }else{
                    fecha_nac = row.fecha_nac;
                    return calcularEdadPersonaReturn(fecha_nac);
                  }
                }
              },
              "bSortable": false,
              "aTargets": [6]
            },
            {
              "mRender": function (data, type, row) {
                if ($('#estado_triaje').val() == -1 || $('#estado_triaje').val() == -2) {   
                  var cod_totem = "";
                  if(row.cod_totem!= null)cod_totem = row.cod_totem;
                  return cod_totem;
                }else{
                  var medico = "";
                  if(row.apepaterno_profesional!= null)medico += row.apepaterno_profesional;
                  if(row.apematerno_profesional!= null)medico += " "+row.apematerno_profesional;
                  if(row.nombre_profesional!= null)     medico += ", "+row.nombre_profesional;
                    return medico;
                }
              },
              "bSortable": false,
              "aTargets": [7]
            },
            {
              "mRender": function (data, type, row) {
                var prioridad = "";
                if ($('#estado_triaje').val() == -1 || $('#estado_triaje').val() == -2) { 
                  if(row.totem_nombre!= null)prioridad = row.totem_nombre; 
                }else{
                  if(row.prioridad!= null)prioridad = row.prioridad+'<br>'+row.topico;
                }
                return prioridad;
              },
              "bSortable": false,
              "aTargets": [8]
            },
            {
              "mRender": function (data, type, row) {
                if ($('#estado_triaje').val() == -1) { 
                  var html =   '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Realizar Atención</b>" class="contenido-btn-div">';
                    html += '<a href="/all_emergencia_triaje/' + row.cod_emergencia +'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a></div>';
                  return html;
                  
                }else if ($('#estado_triaje').val() == -2) { 
                   
                  return row.cantidad_llamada;

                }else if ($('#estado_triaje').val() == 9) { 
                    if(row.estado==0){
                      var motivo_anulacion = "";
                      var usuario_anula = "";
                      var fecha_anulacion = "";
                      var hora_anulacion = "";
                      if(row.motivo_anulacion!= null) motivo_anulacion += row.motivo_anulacion;
                      if(row.usuario_anula!= null) usuario_anula += " "+row.usuario_anula;
                      if(row.fecha_anulacion!= null) fecha_anulacion += " "+row.fecha_anulacion;
                      if(row.hora_anulacion!= null) hora_anulacion += " "+row.hora_anulacion;
                      return '<b>Motivo:</b> '+motivo_anulacion+'<br><b>Responsable:</b> '+usuario_anula+'<br>'+fecha_anulacion+' - '+hora_anulacion+'<br>'; 
                    }else{
                      return '-';
                    }
                }else{
                  var topico = "";
                  var motivo_anulacion = "";
                  var usuario_anula = "";
                  var fecha_anulacion = "";
                  var hora_anulacion = "";
                  if(row.topico!= null){
                    topico = row.topico;
                    if ($('#estado_triaje').val() == 0) {  
                      if(row.motivo_anulacion!= null) motivo_anulacion += row.motivo_anulacion;
                      if(row.usuario_anula!= null) usuario_anula += " "+row.usuario_anula;
                      if(row.fecha_anulacion!= null) fecha_anulacion += " "+row.fecha_anulacion;
                      if(row.hora_anulacion!= null) hora_anulacion += " "+row.hora_anulacion;
                      return '<b>Motivo:</b> '+motivo_anulacion+'<br><b>Responsable:</b> '+usuario_anula+'<br>'+fecha_anulacion+' - '+hora_anulacion+'<br>';                  
                    }else{ 
                      return topico;
                    }
                  }else{
                    return topico;
                  }
                }
              },
              "bSortable": false,
              "aTargets": [9]
            },
            {
              "mRender": function (data, type, row) {  
                var html = "";
                if((row.estado==1 || row.estado == 2|| row.estado == 3 || row.estado == 5) && (row.flag_triaje == 0)){ // SI ES ESTADO = 2 ... ATENDIDO ... DEBERIA MODIFICAR EL TRIAJE ??? SI YA FUE ATENDIDO ADMISION ????
                  html =   '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" class="contenido-btn-div">';
                  html += '<a href="/ver_datos_triaje/' + row.cod_emergencia +
                  '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a></div>';

                }else if ($('#estado_triaje').val() == -1 || $('#estado_triaje').val() == 9) { 
                    html += '<button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-eye"></i></button>';

                }else if ($('#estado_triaje').val() == -2) { 
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
                }  
                return html;
              },
              "bSortable": false,
              "aTargets": [10]
            },  
            {
              "mRender": function(data, type, row) {
                var html = ""; 
                if ($('#estado_triaje').val() == -2) { 
                  var hora_triaje_anulado = "";
                  var fecha_triaje_anulado = "";
                  if(row.fecha_triaje_anulado!= null)fecha_triaje_anulado = row.fecha_triaje_anulado;
                  if(row.hora_triaje_anulado!= null)hora_triaje_anulado = row.hora_triaje_anulado;
                  html =  '<b>F.Anulación:</b><br>'+fecha_triaje_anulado + '<br><b>H.Anulación:</b><br>'+hora_triaje_anulado; 
                }else{

                  if (row.ruta_triaje != null) { 
                    if((row.estado==1 || row.estado == 2 || row.estado == 3 || row.estado == 5) && (row.flag_triaje == 0)){ 
                      html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>PDF Atencions</b>" class="contenido-btn-div">';
                        html += '<a href="/' + row.flag_triaje + '/' + row.ruta_triaje +
                        '" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>'; 
                    }else{
                        html += '<button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>';
                    }                    
                  } else {
                    if((row.estado==1 || row.estado == 2 || row.estado == 3 || row.estado == 5) && (row.flag_triaje == 0)){ 
                      html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>PDF Atencion</b>" class="contenido-btn-div">';
                      html += '<a href="/PdfEmergenciaTriaje/' + row.cod_emergencia +'" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a></div>';
                    }else{
                        html += '<button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>';
                    }   
                  }

                }
                return html;
              },
              "bSortable": false,
              "aTargets": [11],
              /*"className": "text-center",*/
            },
            {
              "mRender": function (data, type, row) {
                var html = "";  
                if ($('#estado_triaje').val() == -1 || $('#estado_triaje').val() == -2) { 
                    html += '<a   href="javascript:void(0)" class="btn btn-app  btn-success btn-xs" data-id_ticket="'+row.cod_emergencia+'" ';
                    html += ' data-fecha_ticket="'+row.fecha_triaje+' - '+row.hora_triaje+'" ';
                    html += ' data-cod_totem="'+row.cod_totem+'" data-totem_nombre="'+row.totem_nombre+'" ';
                    html += ' data-paciente="'+row.nro_doc_ident+' - '+row.asegurado+'"  ';
                    html += ' id="opciones_de_llamado" name="opciones_de_llamado" "> <i class="fa fa-tv"></i> LLAMAR';
                    if(row.cantidad_llamada>0){
                      html += ' <span class="badge bg-purple" style="background-color: red;"><b>'+row.cantidad_llamada+'</b></span></a>';
                    }else{
                      html += ' </a>';
                    } 
                }else{ 
                  if((row.estado==1 || row.estado == 2) && (row.flag_triaje == 0 && (row.id_medico_triaje == {{Auth::user()->id}})) ){  //so estado 1 en triaje y 2 en admision sin firmar triaje
                      if($('#estado_triaje').val() == 1){
                        html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Triaje</b>" class="contenido-btn-div"> ';
                        html += '<a href="javascript:void(0)" class="btn btn-danger btn-xs" data-id_triaje="'+row.cod_emergencia+'" ';
                        html += 'id="anular_triaje" name="anular_triaje"><i class="fa fa-remove"> </i></a></div> ';
                      }else{
                        html += '<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>'; 
                      }
                  }else{ 
                      html += '<button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button>'; 
                  }
                }
                return html;
              },
              "bSortable": false,
              "aTargets": [12],
              "className": "text-center",
            },
          ]
      });
  
      var oTable2= $('#tblEmergenciaTriaje').DataTable();  
      if ($('#estado_triaje').val() == -1 || $('#estado_triaje').val() == -2) { 
          oTable2.column(5).visible(false);
          oTable2.column(6).visible(false);
          if ($('#estado_triaje').val() == -1) { 
            oTable2.column(9).visible(false);
            oTable2.column(10).visible(false);
            oTable2.column(11).visible(false);
          }
          if ($('#estado_triaje').val() == -2) { 
            oTable2.column(12).visible(false);
          }
        }

        if ($('#estado_triaje').val() == '0') { 
            oTable2.column(10).visible(false);
            oTable2.column(11).visible(false);
            oTable2.column(12).visible(false);
        }

    }
 
 

    function fn_ListarBusqueda() {
       datatablenew();
    }

  function reporteTriajesxEmergencia(idUser){ //--------- vista --- lista de triajes x emergencia
      var id_establecimiento = $('#id_establecimiento').val();
      var fecha_ini = $('#fecha_ini').val();
      var fecha_fin = $('#fecha_fin').val();
      var dni_beneficiario = $('#dni_beneficiario').val();
      var nombre_beneficiario = $('#nombre_beneficiario').val();
      var estado_triaje = $('#estado_triaje').val(); 
      if (fecha_ini == "")fecha_ini = 0;
      if (fecha_fin == "")fecha_fin = 0;
      if (dni_beneficiario == "")dni_beneficiario = "0";         //
       if (nombre_beneficiario == "")nombre_beneficiario = "-";   // hice la asignación = ""; ... y habia error ... por q   0 ?  
      location.href = '{{ url('exportar_emergencia_triaje') }}' + '/' + idUser + '/' + fecha_ini + '/' + fecha_fin + '/' + dni_beneficiario + '/' + nombre_beneficiario + '/' + estado_triaje  ;
  }
  

</script>
@endsection
@section('content')
<div id="modal_ventanillas"></div>
<div class="container-fluid">
  <div class="panel panel-primary filterable">
  <div class="panel-heading" style="padding: 0;">
      <h3 style="display: block; height: 100%; padding: 0 15px;" class="panel-title"> <span style="display: inline-block; padding: 10px 15px; background-color: rgb(255, 24, 24); color: rgb(255, 255, 255);">E</span> Lista de Pacientes con Triaje - {{$objConsultorio_descripcion}}</h3>
    </div>  
    <div class="panel-body">
      <form class="form-horizontal" method="post" action="{{ route('listar_triaje_emergencia_atendidos')}}" name="frmTriajeEmergencia" >
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
        <input value="" name="ventanilla"  id="ventanilla" type="hidden">
        <input type="hidden" name="id_establecimiento" id="id_establecimiento"
          value="{{Auth::user()->id_establecimiento}}">
        <div class="row">
          <div class="col-lg-1 col-md-2 col-sm-6 col-xs-12">
            <label class="form-control-sm">F.DESDE</label>
            <input class="form-control input-sm" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde"
              value="<?php echo $fechaDesde?>" maxlength="10">
          </div>
          <div class="col-lg-1 col-md-2 col-sm-6 col-xs-12">
            <label class="form-control-sm">F.HASTA</label>
            <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta"
              value="<?php echo $fechaHasta?>" maxlength="10">
          </div>
          <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <label class="form-control-sm">N°.DOC</label>
            <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder=""
              class="form-control input-sm text-uppercase" maxlength="15" value="">
          </div>

          <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
            <label class="form-control-sm">NOMBRES</label>
            <input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder=""
              class="form-control input-sm text-uppercase" maxlength="60" value="">
          </div>
          <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
            <label class="form-control-sm">ESTADO</label>
            <select class="form-control input-sm" name="estado_triaje" id="estado_triaje" style="height: 30px;">
              @if($establecimiento->flag_colas==1)
                @if($objConsultorio_descripcion!=null)
                <option value="-1" selected> PENDIENTES DE TRIAJE</option>
                <option value="-2"> TICKET DE TRIAJE ANULADO</option>
                @endif
                <option value="1"> ADMISIONES PENDIENTES</option>  
                <option value="3"> NO NECESITA ADMISIONAR</option>
                <option value="0"> TRIAJES ANULADOS</option>
                <option value="9"> TODOS</option>
              @else
                <option value="1" selected> ADMISIONES PENDIENTES</option>  
                <option value="3"> NO NECESITA ADMISIONAR</option>
                <option value="0"> TRIAJES ANULADOS</option>
                <option value="9"> TODOS</option>
              @endif
            </select>
          </div>
          <div class="col-lg-1 col-md-3 col-sm-6 col-xs-12" style="padding-top:20px;">
            <a id="btnBuscar" class="form-control btn btn-success input-sm">BUSCAR</a>
          </div>
          @if($permiso->crear == 1)
          <div class="col-lg-1 col-md-3 col-sm-6 col-xs-12" style="padding-top:20px;">
            <a href="/all_emergencia_triaje" class="form-control btn btn-success input-sm">NUEVO</a>
          </div>
          @endif
          <div class="col-lg-1 col-md-3 col-sm-12 col-xs-12" style="padding-top:20px; text-align: right" 
          id="opcion_exportar" name="opcion_exportar">
            <a href="javascript:void(0)" class="btn btn-primary form-control input-sm" id="btn_exportar_repo"
              name="btn_exportar_repo" onclick="reporteTriajesxEmergencia('{{ Auth::user()->id }}')">
              <i class="fa fa-file-excel-o" aria-hidden="true"></i> EXPORTAR</a>
          </div>  <br><br><br>
        </div>  
      </form>

      <div class="box-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered " id="tblEmergenciaTriaje" style="width:100%">
                <thead>
                    <tr>
                      <th>Cod</th>
                      <th name='fecha_de_registro' id='fecha_de_registro'>Fecha Triaje</th>
                      <th name='hora_de_registro' id='hora_de_registro'>Hora Triaje</th>
                      <th>Número Doc</th>
                      <th>Paciente</th>
                      <th>Sexo</th>
                      <th>Edad</th>
                      <th id="codigo_totem_column">Personal de Salud</th>
                      <th id="id_prioridad_column">Prioridad</th>
                      <th id="id_topico_column">Área</th> {{-- MODIFICAR CUANDO ESTADO = 3 ----- NO MOSTRAR th Tópico -> " "
                      --}}
                      <th id="id_ver_column">Ver</th>
                      {{-- <th>Editar</th>--}}
                      <th id="id_pdf_column" style="text-align:center">PDF</th>
                      <th id="id_anular_column" style="text-align:center">Anular</th>
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
                                        <i class="fa fa-tv"></i> ANULAR TICKET DE ATENCIÓN
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
    $('#FormularioOpcionesLlamado').trigger("reset");
    $('#modalOpcionesLlamado').modal({backdrop: 'static', keyboard: false, show: true})
    $('#modalOpcionesLlamado').modal('show');  

    $('#id_ticket_').val($(this).data('id_ticket'));
    $('#paciente').html($(this).data('paciente'));
    $('#fecha_ticket').html($(this).data('fecha_ticket'));
    $('#cod_totem').html($(this).data('cod_totem'));
    $('#totem_nombre').html($(this).data('totem_nombre')); 

    llamada_triaje_amergencia($(this).data('id_ticket'), 4,0,0,'seleccionado');
  });

  $('body').on('click', '#llamar_paciente', function () {  
    var oTable = $('#tblEmergenciaTriaje').dataTable();
    oTable.fnDraw(false);
    var id_ticket=$('#id_ticket_').val();  
    llamada_triaje_amergencia(id_ticket, 1,0,0,'llamado'); 
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

    var id_ticket_=$('#id_ticket_').val();  
    window.location.href = "/all_emergencia_triaje/"+ id_ticket_ ; 
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
        html: '<p class="descripcion-alert"><span><b style="color: red;">RECUERDE QUE TIENE QUE TENER UNA CANTIDAD MÍMINA DE 10 CARACTERES.</b><br></span></p>', 
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
                                          ANULAR TICKET DE ATENCIÓN
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
  
  function anularTriaje(id_triaje, motivo_anulacion){
    bootbox.confirm({ 
      size: "small", 
      message: "&iquest;Desea anular el triaje registrado?", 
      callback: function(result){
        if (result==true) {
          fn_anularTriaje(id_triaje, motivo_anulacion.replace(/\//g, "-"));
        }
      }
    }); 
  }

  function fn_anularTriaje(id, motivo_anulacion) {
    var data = {
      id: id,
      motivo_anulacion: motivo_anulacion
    };

    $.ajax({
      url: '/anular_emergencia_triaje',
      method: 'POST',
      data: JSON.stringify(data),
      contentType: "application/json",
      dataType: "json",
      success: function(result) {
        if (result.sw == true) {
          $('#modalAnularTriaje').modal('hide');
          var oTable = $('#tblEmergenciaTriaje').dataTable();
          oTable.fnDraw(false);

          Swal.fire({
            title: '<div class="titulo-padding"><p>Mensaje de Triaje</p></div>',
            html: '<p class="descripcion-alert"><span><b>EL TRIAJE SELECCIONADO FUE ANULADO DE FORMA CORRECTA.</b><br></span></p>',
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

    $('#FormularioAnularTriaje').trigger("reset");
    $('#modalAnularTriaje').modal({backdrop: 'static', keyboard: false, show: true})
    $('#modalAnularTriaje').modal('show');

    $('#id_triaje_').val($(this).data('id_triaje'));

    var oTable = $('#tblEmergenciaTriaje').dataTable();
    oTable.fnDraw(false); 
  });

  
  $('body').on('click', '#registrar_anular_triaje', function () {  
    var id_triaje_=$('#id_triaje_').val();
    var motivo_anulacion_triaje=$('#motivo_anulacion_triaje').val();
    if(motivo_anulacion_triaje == '' || motivo_anulacion_triaje==null){
      Swal.fire({
        title: '<div class="titulo-padding"><p>Mensaje de Triaje</p></div>',
        html: '<p class="descripcion-alert"><span><b style="color: red;">RECUERDE QUE TIENE QUE TENER UNA CANTIDAD MÍMINA DE 10 CARACTERES.</b><br></span></p>', 
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
        anularTriaje(id_triaje_ , motivo_anulacion_triaje); 
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
   
<div class="modal fade" id="modalAnularTriaje" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content"> 
            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <b>DETALLE DE ANULACIÓN</b>
            </div> 
            <div class="modal-body">
                <form id="FormularioAnularTriaje" name="FormularioAnularTriaje" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}"> 
                    <input type="hidden" id="id_triaje_" name="id_triaje_"> 
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active"> 
                                <fieldset>
                                    <div class="form-group"  style="text-align: center">   
                                      <textarea name="motivo_anulacion_triaje" id="motivo_anulacion_triaje" rows="10" style="width: 100%;"></textarea>
                                    </div>

                                    <div class="form-group"  style="text-align: center"> 
                                      <a href="javascript:void(0)" class="btn btn-danger btn-md btn-block" id="registrar_anular_triaje" name="registrar_anular_triaje" >
                                          ANULAR TRIAJE
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

@include('frontend.admision.emergencia.triaje.ver_triaje.modal.ver_triaje')
@include('frontend.includes.footer')
@endsection