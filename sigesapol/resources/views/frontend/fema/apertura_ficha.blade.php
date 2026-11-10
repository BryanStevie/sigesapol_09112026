@extends('frontend.layouts.master')
@section('renderjs')
<style>
</style>

<!-- <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script> -->

<script type="text/javascript">

$(document).ready(function() {
    $("input[type=radio]").click(function(event){
        var valor = $(event.target).val();
        if(valor =="1"){
            $("#divlicencianuevo").show();
            $("#divlicenciareno").hide();

        } else if (valor == "2") {
            $("#divlicencianuevo").hide();
            $("#divlicenciareno").show();
        } else { 
            // Otra cosa
        }
    });
    $('#div-apertura1').hide();
    $('#div-apertura').hide();
    $('#cierre').hide();
});

function mostrar(id) {
    if (id == "0") {
        $("#0").show();
        $("#1").hide();
        $("#2").hide();
        id_fema_tipo_proceso = 0;
    }

    if (id == "1") {
        $("#0").hide();
        $("#1").show();
        $("#2").hide(); 
        id_fema_tipo_proceso = 1;
    }
    if (id == "2") {
        $("#0").hide();
        $("#1").hide();
        $("#2").show();
        id_fema_tipo_proceso = 2;
    }
}
function mostrar1(id) {
    if (id == "0") {
        $("#0").show();
        $("#21").hide();
        id_fema_tipo_proceso = 0;
    }

    if (id == "2") {
        $("#0").hide();
        $("#22").show();
        id_fema_tipo_proceso = 2;
    }
}

function mostrarsubfema(id) {
    if (id == "0") {
            $("#div1").show();
            $("#div2").hide();
            $("#div3").hide();
            $("#div4").hide();
            $("#div5").hide();
            $("#div6").hide();
    }

    if (id == "1") {
            $("#div1").hide();
            $("#div2").show();
            $("#div3").hide();
            $("#div4").hide();
            $("#div5").hide();
            $("#div6").hide();
    }
    if (id == "2") {
            $("#div1").hide();
            $("#div2").hide();
            $("#div3").show();
            $("#div4").hide();
            $("#div5").hide();
            $("#div6").hide();
    }
    if (id == "3") {
            $("#div1").hide();
            $("#div2").hide();
            $("#div3").hide();
            $("#div4").show();
            $("#div5").hide();
            $("#div6").hide();
    }
    if (id == "4") {
            $("#div1").hide();
            $("#div2").hide();
            $("#div3").hide();
            $("#div4").hide();
            $("#div5").show();
            $("#div6").hide();
    }
    if (id == "5") {
            $("#div1").hide();
            $("#div2").hide();
            $("#div3").hide();
            $("#div4").hide();
            $("#div5").hide();
            $("#div6").show();
    }
}

function mostrarsubfemaespecialfiltro(id) {
    if (id == "0") {
            $("#div111").show();
            $("#div121").hide();
            $("#div131").hide();
            $("#div141").hide();
            $("#div151").hide();
            $("#div161").hide();
            $("#div171").hide();
            $("#div181").hide();
            $("#div191").hide();
    }

    if (id == "6") {
            $("#div111").hide();
            $("#div121").show();
            $("#div131").hide();
            $("#div141").hide();
            $("#div151").hide();
            $("#div161").hide();
            $("#div171").hide();
            $("#div181").hide();
            $("#div191").hide();
    }
    if (id == "7") {
            $("#div111").hide();
            $("#div121").hide();
            $("#div131").show();
            $("#div141").hide();
            $("#div151").hide();
            $("#div161").hide();
            $("#div171").hide();
            $("#div181").hide();
            $("#div191").hide();
    }
    if (id == "8") {
            $("#div111").hide();
            $("#div121").hide();
            $("#div131").hide();
            $("#div141").show();
            $("#div151").hide();
            $("#div161").hide();
            $("#div171").hide();
            $("#div181").hide();
            $("#div191").hide();
    }
    if (id == "9") {
            $("#div111").hide();
            $("#div121").hide();
            $("#div131").hide();
            $("#div141").hide();
            $("#div151").show();
            $("#div161").hide();
            $("#div171").hide();
            $("#div181").hide();
            $("#div191").hide();
    }
    if (id == "10") {
            $("#div111").hide();
            $("#div121").hide();
            $("#div131").hide();
            $("#div141").hide();
            $("#div151").hide();
            $("#div161").show();
            $("#div171").hide();
            $("#div181").hide();
            $("#div191").hide();
    }
    if (id == "11") {
            $("#div111").hide();
            $("#div121").hide();
            $("#div131").hide();
            $("#div141").hide();
            $("#div151").hide();
            $("#div161").hide();
            $("#div171").show();
            $("#div181").hide();
            $("#div191").hide();
    }
    if (id == "12") {
            $("#div111").hide();
            $("#div121").hide();
            $("#div131").hide();
            $("#div141").hide();
            $("#div151").hide();
            $("#div161").hide();
            $("#div171").hide();
            $("#div181").show();
            $("#div191").hide();
    }
    if (id == "13") {
            $("#div111").hide();
            $("#div121").hide();
            $("#div131").hide();
            $("#div141").hide();
            $("#div151").hide();
            $("#div161").hide();
            $("#div171").hide();
            $("#div181").hide();
            $("#div191").show();
    }
}



function mostrarsubfemaespecial(id) {
    if (id == "0") {
            $("#div11").show();
            $("#div12").hide();
            $("#div13").hide();
            $("#div14").hide();
            $("#div15").hide();
            $("#div16").hide();
            $("#div17").hide();
            $("#div18").hide();
            $("#div19").hide();
    }

    if (id == "6") {
            $("#div11").hide();
            $("#div12").show();
            $("#div13").hide();
            $("#div14").hide();
            $("#div15").hide();
            $("#div16").hide();
            $("#div17").hide();
            $("#div18").hide();
            $("#div19").hide();
    }
    if (id == "7") {
            $("#div11").hide();
            $("#div12").hide();
            $("#div13").show();
            $("#div14").hide();
            $("#div15").hide();
            $("#div16").hide();
            $("#div17").hide();
            $("#div18").hide();
            $("#div19").hide();
    }
    if (id == "8") {
            $("#div11").hide();
            $("#div12").hide();
            $("#div13").hide();
            $("#div14").show();
            $("#div15").hide();
            $("#div16").hide();
            $("#div17").hide();
            $("#div18").hide();
            $("#div19").hide();
    }
    if (id == "9") {
            $("#div11").hide();
            $("#div12").hide();
            $("#div13").hide();
            $("#div14").hide();
            $("#div15").show();
            $("#div16").hide();
            $("#div17").hide();
            $("#div18").hide();
            $("#div19").hide();
    }
    if (id == "10") {
            $("#div11").hide();
            $("#div12").hide();
            $("#div13").hide();
            $("#div14").hide();
            $("#div15").hide();
            $("#div16").show();
            $("#div17").hide();
            $("#div18").hide();
            $("#div19").hide();
    }
    if (id == "11") {
            $("#div11").hide();
            $("#div12").hide();
            $("#div13").hide();
            $("#div14").hide();
            $("#div15").hide();
            $("#div16").hide();
            $("#div17").show();
            $("#div18").hide();
            $("#div19").hide();
    }
    if (id == "12") {
            $("#div11").hide();
            $("#div12").hide();
            $("#div13").hide();
            $("#div14").hide();
            $("#div15").hide();
            $("#div16").hide();
            $("#div17").hide();
            $("#div18").show();
            $("#div19").hide();
    }
    if (id == "13") {
            $("#div11").hide();
            $("#div12").hide();
            $("#div13").hide();
            $("#div14").hide();
            $("#div15").hide();
            $("#div16").hide();
            $("#div17").hide();
            $("#div18").hide();
            $("#div19").show();
    }
}


function getDirrehumByDni(dni_paciente) {
  limpiar();
     let valor = dni_paciente;

    if(valor==""){
       bootbox.alert("Falta ingresar el Documento del paciente");
       $('#dni').focus();
       return 0;
    }

    if (valor.length != 8) {
      bootbox.alert("<b>El N° DNI debe tener 8 dígitos.</b>")
      $('#dni').focus();
      return 0;
    }

    $('#content').html('<div class="loading"><img src="images/loader.gif" alt="loading" /><br/>Consultando ...</div>');
    $('#btnBuscar').prop('disabled', true);
    $('#btnBuscarReniec').prop('disabled', true);

    var paciente_no_existe=false;
    $.ajax({
      url: "/ws_dirrehum/1/"+valor,   ///ws_dirrehum/{tipo_doc}/{nro_doc}
      type: "GET",
      dataType: "JSON",
      success: function(result) { //alert(result.id);
    console.log(result);

    let personaActiva = null;

    // Si es arreglo
    if (Array.isArray(result)) {

        personaActiva = result.find(item =>
            item.SITUACION &&
            item.SITUACION.trim().toUpperCase() === "ACTIVIDAD"
        );

    } else if (typeof result === "object" && result !== null) {

        // Si es objeto único
        if (result.SITUACION &&
            result.SITUACION.trim().toUpperCase() === "ACTIVIDAD") {

            personaActiva = result;
        }
    }

    if (personaActiva) {

        $('#primer_apellido').val(personaActiva.PATERNO);
        $('#segundo_apellido').val(personaActiva.MATERNO);
        $('#nombres').val(personaActiva.NOMBRES);
        $('#dni_titular').val(personaActiva.DNI);
        $('#carnet').val(personaActiva.CARNE);
        $('#grado').val(personaActiva.GRADO);
        $('#situacion').val(personaActiva.SITUACION);
        $('#estado_civil').val(personaActiva.EST_CIVIL);
        $('#sexo').val(personaActiva.SEXO);
        $('#fecha_nacimiento').val(personaActiva.NACIMIENTO);
        $('#tipo_sangre').val(personaActiva.TSANGRE_DES);
        $('#unidad').val(personaActiva.UNIDAD);
        $('#situacion_especial').val(personaActiva.TSITE_DES);
        $('#fecha_retiro').val(personaActiva.FRET);
        } else {
          $('#primer_apellido').val(result.PATERNO);
          $('#segundo_apellido').val(result.MATERNO);
          $('#nombres').val(result.NOMBRES);
          $('#dni_titular').val(result.DNI);
          // $('#tipo').val(result.TIPO);
          $('#carnet').val(result.CARNE);
          $('#grado').val(result.GRADO);
          $('#situacion').val(result.SITUACION);
          if(result.SITUACION == 'ACTIVIDAD'){
              
          }else{
            alert("El N° Documento consultado pertenece a un PNP Retirado.");
         $('#div-apertura').hide();
         $('#div-apertura1').hide();
         $('#cierre').hide();
          }
          $('#estado_civil').val(result.EST_CIVIL);
          $('#sexo').val(result.SEXO);
          $('#fecha_nacimiento').val(result.NACIMIENTO);
          $('#tipo_sangre').val(result.TSANGRE_DES);
          $('#unidad').val(result.UNIDAD);
          $('#situacion_especial').val(result.TSITE_DES);
          $('#fecha_retiro').val(result.FRET);
        }
        
        $('#content').html('');
        $('#btnBuscar').prop('disabled', false);
        $('#btnBuscarReniec').prop('disabled', false);

      },
      error: function(xhr, status) {
       
        bootbox.alert("El N° Documento consultado No pertenece a la PNP.");
         $('#div-apertura').hide();
         $('#div-apertura1').hide();
         $('#cierre').hide();
       
      },
      complete : function(xhr, status) {
        $('#content').html('');
        $('#btnBuscar').prop('disabled', false);
        $('#btnBuscarReniec').prop('disabled', false);
      }
    });

  }

  function buscafemageneral(dni_paciente) {
  limpiar();
     let valor = dni_paciente;
    $.ajax({
      url: "/buscafemageneral/"+valor,   ///ws_dirrehum/{tipo_doc}/{nro_doc}
      type: "GET",
      dataType: "JSON",
      success: function(result) { //alert(result.id);
        
        if (result.length > 0) {
          
          let mostrarnnombres="";
          let mostrardatosString = result.map(function(e) {
            return e.fema_general; 
           });
           
           for( let i=0;i<mostrardatosString.length;i++){
            mostrarnnombres = mostrardatosString[i]  ;  

           }
           if(mostrarnnombres == "0"){
            $('#div-apertura').show();
            $('#div-apertura1').hide();
            $('#cierre').show();
           }else{
            $('#div-apertura1').show();
            $('#div-apertura').hide();
            $('#cierre').show();
           }
        } else {

        }

        $('#content').html('');
        $('#btnBuscar').prop('disabled', false);
        $('#btnBuscarReniec').prop('disabled', false);

      },
      error: function(xhr, status) {
       // bootbox.alert("Ha ocurrido un error al consultar el N° Documento del paciente.");
      },
      complete : function(xhr, status) {
        $('#content').html('');
        $('#btnBuscar').prop('disabled', false);
        $('#btnBuscarReniec').prop('disabled', false);
      }
    });

  }

  function buscafichaabierta(dni_paciente) {
  limpiar();
     let valor = dni_paciente;
    $.ajax({
      url: "/buscafichaabierta/"+valor,
      type: "GET",
      dataType: "JSON",
      success: function(result) { //alert(result.id);
        
        if (result.length > 0) {
          
          let mostrarnnombres="";
          let establecimientos="";
          let mostrardatosString = result.map(function(e) {
            return e.nombre; 
           });
          let mostrarestablecimientosString = result.map(function(e) {
            return e.establecimientos; 
           });
           for( let i=0;i<mostrardatosString.length;i++){
            mostrarnnombres = mostrarnnombres +  " " + mostrardatosString[i]  + " - " ;  
           }
           for( let i=0;i<mostrarestablecimientosString.length;i++){
            establecimientos = establecimientos +  " " + mostrarestablecimientosString[i]  + " - " ;  
           }
         $("#fichasabiertas").val(mostrarnnombres);
         $("#establecimientosaperturados").val(establecimientos);
        } else {
          $("#fichasabiertas").val("--");  
          $("#establecimientosaperturados").val("--");
        }
        $('#content').html('');
        $('#btnBuscar').prop('disabled', false);
        $('#btnBuscarReniec').prop('disabled', false);

      },
      error: function(xhr, status) {
       // bootbox.alert("Ha ocurrido un error al consultar el N° Documento del paciente.");
      },
      complete : function(xhr, status) {
        $('#content').html('');
        $('#btnBuscar').prop('disabled', false);
        $('#btnBuscarReniec').prop('disabled', false);
      }
    });

  }
  function getReniecByDni() {
    limpiar();
    var dni_paciente = $("#dniReniec").val();
    
    getDirrehumByDni(dni_paciente);
    buscafichaabierta(dni_paciente);
    buscafemageneral(dni_paciente);
    if(dni_paciente==""){
       bootbox.alert("Falta ingresar el Documento del paciente");
       $('#dniReniec').focus();
       return 0;
    }

    if (dni_paciente.length != 8) {
      bootbox.alert("<b>El N° DNI debe tener 8 dígitos.</b>");
      $('#dniReniec').focus();
      return 0;
    }

    $('#content').html('<div class="loading"><img src="images/loader.gif" alt="loading" /><br/>Consultando ...</div>');
    $('#btnBuscar').prop('disabled', true);
    $('#btnBuscarReniec').prop('disabled', true);

    var paciente_no_existe=false;
    $.ajax({
      //url: '/validar_documento/' + dni_paciente,
      url: "/validate_entity/"+dni_paciente,
      type: "GET",
      dataType: "JSON",
      success: function(result) {
        if (result.codigoMensaje == '00') {
          
          $('#primer_apellido').val(result.paterno);
          $('#segundo_apellido').val(result.materno);
          $('#nombres').val(result.nombres);
          $('#dni_titular').val(result.dni);
          // $('#tipo').val(result.TIPO);
          //$('#carnet').val('');
          //$('#grado').val('');
          //$('#situacion').val('');
          $('#estado_civil').val(result.estado_civil);
          $('#sexo').val(result.sexo);
          $('#fecha_nacimiento').val(result.vfecha_nac);
          $('#tipo_doc_ident').val('1');
          $('#tipo_sangre').val('');
          //$('#unidad').val('');
          //$('#situacion_especial').val('');
          $('#fecha_retiro').val('');
          $('#fecha_emision').val(result.vfecha_expedicion);
          $('#fecha_caducidad').val(result.vfecha_caducidad);
          $('#distrito_nacimiento').val(result.departamento_nacimiento+'/'+result.provincia_nacimiento+'/'+result.distrito_nacimiento)
          $('#distrito_domicilio').val(result.departamentoDomicilio+'/'+result.provinciaDomicilio+'/'+result.distritoDomicilio)
          $('#localidad_domicilio').val(result.localidad_domicilio);
          $('#direccion_domicilio').val(result.direccionDomicilio);
          $('#foto').val(result.foto);
          $('#foto-reniec').html('<img src="data:image/png;base64,'+result.foto+'" title="foto reniec" width="210px" alt=""/>')
          $('#descripcion1').val('');
          $('#descripcion3').val('');
          $('#tipo_licencia_nueva').prop('checked', false);
          $('#tipo_licencia_renovacion').prop('checked', false);
          $('#fechaexamenconducirnuevo').val('');
          $('#fechaexamenconducirreno').val('');
          $('#fechalicenciavencida').val('');
          $('#descripcion2').val('');
          $('#ht').val('');
          $('#oficio').val('');
          $('#certificadoth').val('');
          $('#actareincorporacion').val('');
          $('#descripcion4').val('');
          $('#descripcion5').val('');

      /* abre la ficha  */ 

        } 
        
        $('#content').html('');
        $('#btnBuscar').prop('disabled', false);
        $('#btnBuscarReniec').prop('disabled', false);
      },
      error: function(xhr, status) {
        bootbox.alert("Ha ocurrido un error al consultar el N° Documento del paciente.");
      },
      complete : function(xhr, status) {
        $('#content').html('');
        $('#btnBuscar').prop('disabled', false);
        $('#btnBuscarReniec').prop('disabled', false);
      }
    });

  }

function validarDni() {
  var dni = $('#dni').val();
  let tipoDocumento = $("#tipo_documento").val();
  $('#tipo_documento_text').val($("#tipo_documento option:selected").text());

  var len = dni.length;
  if (tipoDocumento == "1" && len != 8) {
    bootbox.alert("El DNI tiene que ser de 8 digitos");
    return false;
  } else if (tipoDocumento != "1" && len < 9) {
    bootbox.alert("El Documento tiene que ser de 9 a más digitos");
    return false;
  }
  document.getElementById("frmfarmacia").submit();
}

function maxlength_doc_usu() {

  $('#dni').val('');

  if ($("#tipo_documento").val() == "1") {
    $("#dni").attr('maxlength', '8');
  } else {
    $("#dni").attr('maxlength', '12');
  }
  document.getElementById("dni").focus();
}

$( "#guarda" ).click(function() {

  if ($("#fema_procesos").val() == "0"  && $("#fema_procesos1").val() == "0") {
    bootbox.alert("debe seleccionar un proceso");
  }else{
    

    if ($("#fema_tipo_procesosgeneral").val() == "0"  && $("#fema_tipo_procesos").val() == "0" && $("#fema_tipo_procesosfiltro").val() == "0") {
      console.log($("#fema_tipo_procesosgeneral").val());
      console.log($("#fema_tipo_procesos").val());
      console.log($("#fema_tipo_procesosfiltro").val());
      
      bootbox.alert("debe seleccionar un tipo de subproceso");
  }else{

    Swal.fire({
  position: 'top-end',
  icon: 'success',
  title: 'Se guardo correctamente',
  showConfirmButton: false,
  timer: 2000,
  didOpen: () => {
  Swal.showLoading()
  },
  willClose: () => {
   $('#frm').submit();
  }
})
  }

  }
  
});

function limpiar() {
  $('#primer_apellido').val("");
  $('#segundo_apellido').val("");
  $('#nombres').val("");
  $('#dni_titular').val("");
  //$('#tipo').val(result.TIPO);
  $('#carnet').val("");
  $('#grado').val("");
  $('#situacion').val("");
  $('#estado_civil').val("");
  $('#sexo').val("");
  $('#fecha_nacimiento').val("");
  $('#tipo_sangre').val("");
  $('#unidad').val("");
  $('#situacion_especial').val("");
  $('#fecha_retiro').val("");
  $('#fecha_emision').val("");
  $('#fecha_caducidad').val("");
  $('#distrito_nacimiento').val("");
  $('#distrito_domicilio').val("");
  $('#localidad_domicilio').val("");
  $('#direccion_domicilio').val("");
  $('#foto').val("");
  $('#foto-reniec').html("");
}

</script>
@endsection
@section('content')

<div class="container">
  <div class="row">
    <form id="frm" action="{{route('ficha_fema_envio')}}" method="post">
      <input type="hidden" name="_token" value="{{csrf_token()}}">
      <input type="hidden" name="situacion" id="situacion">
      <input type="hidden" name="unidad" id="unidad">
      <input type="hidden" name="foto" id="foto">
      <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title text-center">Aperturar Ficha de Evaluación Medica Anual (FEMA)</h3>
            <a href="/all_aperturas_fema/{{ Auth::user()->id_farmacia }}" class="btn btn-info pull-right">
                        <i class="fa fa-list"></i> Listado de Aperturas
                    </a>
        </div>
        <div class="panel-body">
        
          <div class="row">

            <div class="col-md-3">
                <div class="form-group">
                  <label for="">Consultar DNI</label>
                  <input value="" type="text" name="dniReniec" id="dniReniec" class="form-control" tabindex="" placeholder="" maxlength="8" required="">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                  <label for="">&nbsp;<br></label><br>
                  <button class="btn btn-info" type="button" name="btnBuscarReniec" id="btnBuscarReniec" onclick="getReniecByDni();">Buscar</button>
                </div>
            </div>
          </div>
          <div>
            <div class="col-md-9">
              <div class="col-md-4">
                  <div class="form-group">
                    <label for="">PRIMER APELLIDO</label>
                    <input value="" type="text" name="primer_apellido" id="primer_apellido" class="form-control" readonly="" tabindex="" placeholder="">
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                    <label for="">SEGUNDO APELLIDO</label>
                    <input value="" type="text" name="segundo_apellido" id="segundo_apellido" class="form-control" readonly="" tabindex="" placeholder="">
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                    <label for="">NOMBRES</label>
                    <input value="" type="text" name="nombres" id="nombres" class="form-control" readonly="" tabindex="" placeholder="">
                  </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="">DNI</label>
                  <input value="" type="text" name="dni_titular" id="dni_titular" class="form-control" readonly="" tabindex="" placeholder="">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                  <label for="">SEXO</label>
                  <input value="" type="text" name="sexo" id="sexo" class="form-control" readonly="" tabindex="" placeholder="">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                  <label for="">FECHA NACIMIENTO</label>
                  <input value="" type="text" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" readonly="" tabindex="" placeholder="">
                </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                  <label for="">CARNET</label>
                  <input value="" type="text" name="carnet" id="carnet" class="form-control" readonly="" tabindex="" placeholder="">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="">GRADO</label>
                <input value="" type="text" name="grado" id="grado" class="form-control" readonly="" tabindex="" placeholder="">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="">ESTADO CIVIL</label>
                <input value="" type="text" name="estado_civil" id="estado_civil" class="form-control" readonly="" tabindex="" placeholder="">
              </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                  <label for="">DEPARTAMENTO / PROVINCIA / DISTRITO DOMICILIO</label>
                  <input value="" type="text" name="distrito_domicilio" id="distrito_domicilio" class="form-control" readonly="" tabindex="" placeholder="">
                </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="">FICHAS ABIERTAS</label>
                <input value="" type="text" name="fichasabiertas" id="fichasabiertas" class="form-control" readonly="" tabindex="" placeholder=""size="10" style=" font-size: 30px; background-color: #19e619">
              </div>
              <div class="form-group">
                <label for="">ESTABLECIMIENTOS DONDE SE APERTURARON LAS FICHAS</label>
                <input value="" type="text" name="establecimientosaperturados" id="establecimientosaperturados" class="form-control" readonly="" tabindex="" placeholder="" style=" font-size: 13px; background-color: #e9f2e9">
              </div>
            </div>
            </div>
          </div>
          <div>
            <div class="col-md-3">
              <div class="form-group">                           
              </div>
            </div>
          </div>
        

        
        </div>
      </div> <!-- .panel -->

      <div id="div-apertura">
        <div class="panel panel-primary">
          <div class="panel-heading">
              <h3 class="panel-title text-center">APERTURA DE PROCESOS</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">SELECCIONAR PROCESO</label>
                  <select id="fema_procesos" name="fema_procesos" onChange="mostrar(this.value);" class="form-control">
                    <option value="0">-Select-</option>
                    <option value="1">General</option>
                    @if($establecimiento == '76' or $establecimiento == '15')
                      <option value="2">Especial</option> 
                     @endif
                  </select>
                </div>
              </div>

              <div id="1" style="display: none;">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="">TIPO PROCESO </label>
                    <select id="fema_tipo_procesosgeneral" name="fema_tipo_procesosgeneral" onChange="mostrarsubfema(this.value);" class="form-control">
                      <option value="0">-Select-</option>
                      <option value="1">Fema </option>
                      <option value="2">Licencia De Conduccion</option>
                      <option value="3">Cursos</option>
                      <option value="4">Reincorporacion</option>
                      <option value="5">Otros</option>
                    </select>
                  </div>
                </div>
              </div>

              <div id="2" style="display: none;">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="">TIPO PROCESO </label>
                    <select id="fema_tipo_procesos" name="fema_tipo_procesos" onChange="mostrarsubfemaespecial(this.value);" class="form-control">
                      <option value="0">-Select-</option>
                      <option value="6">Piloto </option>
                      <option value="7">Tripulante </option>
                      <option value="8">Aux. de Aviacíon</option>
                      <option value="9">Viaje al Extranjero</option>
                      <option value="10">Cursos en el Extranjero</option>
                      <option value="11">Cadete</option>
                      <option value="12">Alumno</option>
                      <option value="13">Otros</option>
                    </select>
                  </div>
                </div>
              </div>

            </div>

            <div id="div2" style="display:none;">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <BR>
                    <label for="">DESCRIPCION</label>
                    <textarea name="descripcion1" id="descripcion1" class="form-control"></textarea>
                  </div>
                </div>
               
              </div>
            </div>

            <div id="div4" style="display:none;">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="">DESCRIPCION</label>
                  <textarea name="descripcion3" id="descripcion3" class="form-control"></textarea>
                </div>
              </div>
            </div>
            </div>

            <div id="div3" style="display:none;">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <div class="radio">
                    <label for="">
                      <input name="tipo_licencia" id="tipo_licencia_nueva" type="radio" value="1"/> Nueva
                    </label>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <div class="radio"> 
                    <label for="">
                      <input name="tipo_licencia" id="tipo_licencia_renovacion" type="radio" value="2"/> Renovacion
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div id="divlicencianuevo" style="display:none;">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Act. Aprovacion Exam. Conducir</label>
                  <input type="text" name="fechaexamenconducirnuevo" id="fechaexamenconducirnuevo" class="form-control datepicker" />
                </div>
              </div>
            </div>
            </div>

            <div id="divlicenciareno" style="display:none;">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Act. Aprovacion Exam. Conducir</label>
                  <input type="text" name="fechaexamenconducirreno" id="fechaexamenconducirreno" class="form-control datepicker" />
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Lic. Vencida</label>
                  <input type="text" name="fechalicenciavencida" id="fechalicenciavencida" class="form-control datepicker" />
                </div>
              </div>
            </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="">DESCRIPCION</label>
                  <textarea name="descripcion2" id="descripcion2" id="descripcion2" class="form-control"></textarea>
                </div>
              </div>
            </div>
            </div>

            <div id="div5" style="display:none;">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">HT</label>
                  <input type="text" name="ht" id="ht" class="form-control">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">OFICIO</label>
                  <input type="text" name="oficio" id="oficio" class="form-control">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Certificado de Talento Humano</label>
                  <input type="text" name="certificadoth" id="certificadoth" class="form-control">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Acta de Reincorporacion</label>
                  <input type="text" name="actareincorporacion" id="actareincorporacion" class="form-control">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label for="">DESCRIPCION</label>
                  <textarea name="descripcion4" id="descripcion4" class="form-control"></textarea>
                </div>
              </div>
            </div>
            </div>

            <div id="div6" style="display:none;">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="">DESCRIPCION</label>
                  <textarea name="descripcion5" id="descripcion5" class="form-control"></textarea>
                </div>
              </div>
            </div>
            </div>

            <!-- ******************************************** -->
            <div id="div12" style="display: none;">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <div class="checkbox">
                    <label for="">
                      <input name="audiometria" type="checkbox" if(fema_tipo_procesos="6"){required } /> Audiometria
                    </label>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <div class="checkbox">
                    <label for="">
                      <input  name="CampoVisual" type="checkbox" if(fema_tipo_procesos="6"){required }/> Campo Visual
                    </label>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <div class="checkbox">
                    <label for="">
                      <input name="Electroencefalograma" type="checkbox" if(fema_tipo_procesos="6"){required }/> Electroencefalograma
                    </label>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <div class="checkbox">
                    <label for="">
                      <input  name="CarnetVacunaTropicales" type="checkbox" if(fema_tipo_procesos="6"){required }/> Carnet de Vacuna Enf. Tropicales
                    </label>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <div class="checkbox">
                    <label for="">
                      <input  name="CarnetVacunaCOVID" type="checkbox" if(fema_tipo_procesos="6"){required }/> Carnet de Vacuna COVID
                    </label>
                  </div>
                </div>
              </div>
            </div>
            </div>

            <div id="div13" style="display: none;">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <div class="checkbox">
                    <label for="">
                      <input  name="CarnetVacunaTropicales" type="checkbox" if(fema_tipo_procesos="7"){required }/> Carnet de Vacuna Enf. Tropicales
                    </label>
                  </div>
                </div>
              </div>
              
              <div class="col-md-3">
                <div class="form-group">
                  <div class="checkbox">
                    <label for="">
                      <input  name="CarnetVacunaCOVID" type="checkbox"/> Carnet de Vacuna COVID
                    </label>
                  </div>
                </div>
              </div>
            </div>
            </div>

            <div id="div15" style="display: none;">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <div class="checkbox">
                    <label for="">
                      <input  name="CarnetVacunaTropicales" type="checkbox" if(fema_tipo_procesos="9"){required }/> Carnet de Vacuna Enf. Tropicales
                    </label>
                  </div>
                </div>
              </div>
              
              <div class="col-md-3">
                <div class="form-group">
                  <div class="checkbox">
                    <label for="">
                      <input  name="CarnetVacunaCOVID" type="checkbox" if(fema_tipo_procesos="9"){required }/> Carnet de Vacuna COVID
                    </label>
                  </div>
                </div>
              </div>
            </div>
            </div>

            <div id="div16" style="display: none;">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <div class="checkbox">
                    <label for="">
                      <input  name="CarnetVacunaTropicales" type="checkbox" if(fema_tipo_procesos="10"){required }/> Carnet de Vacuna Enf. Tropicales
                    </label>
                  </div>
                </div>
              </div>
              
              <div class="col-md-3">
                <div class="form-group">
                  <div class="checkbox">
                    <label for="">
                      <input  name="CarnetVacunaCOVID" type="checkbox"/> Carnet de Vacuna COVID
                    </label>
                  </div>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label for="">DESCRIPCION</label>
                  <textarea name="descripcion10" id="descripcion10" class="form-control"></textarea>
                </div>
              </div>
            </div>
            </div>

            <div id="div14" style="display: none;">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="">DESCRIPCION</label>
                  <textarea name="descripcion8" id="descripcion8" class="form-control"></textarea>
                </div>
              </div>
            </div>
            </div>

            <div id="div17" style="display: none;">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">DESCRIPCION</label>
                    <textarea name="descripcion11" id="descripcion11" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>

            <div id="div18" style="display: none;">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">DESCRIPCION</label>
                    <textarea name="descripcion12" id="descripcion12" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>

            <div id="div19" style="display: none;">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">DESCRIPCION</label>
                    <textarea name="descripcion13" id="descripcion12" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>

        </div>

        </div> <!-- .panel -->
      </div>
      <div id="div-apertura1">
        <div class="panel panel-primary">
          <div class="panel-heading">
              <h3 class="panel-title text-center">APERTURA DE PROCESOS</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">SELECCIONAR PROCESO</label>
                  <select id="fema_procesos1" name="fema_procesos1" onChange="mostrar1(this.value);" class="form-control">
                    <option value="0">-Select-</option>
                    @if($establecimiento == '76' or $establecimiento == '15')
                      <option value="2">Especial</option> 
                     @endif
                  </select>
                </div>
              </div>

              <div id="22" style="display: none;">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="">TIPO PROCESO </label>
                    <select id="fema_tipo_procesosfiltro" name="fema_tipo_procesosfiltro" onChange="mostrarsubfemaespecialfiltro(this.value);" class="form-control">
                      <option value="0">-Select-</option>
                      <option value="6">Piloto </option>
                      <option value="7">Tripulante </option>
                      <option value="8">Aux. de Aviacíon</option>
                      <option value="9">Viaje al Extranjero</option>
                      <option value="10">Cursos en el Extranjero</option>
                      <option value="11">Cadete</option>
                      <option value="12">Alumno</option>
                      <option value="13">Otros</option>
                    </select>
                  </div>
                </div>
              </div>

            </div>

            <!-- ******************************************** -->
            <div id="div121" style="display: none;">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="checkbox">
                      <label for="">
                        <input name="audiometria" type="checkbox" if(fema_tipo_procesos="6"){required } /> Audiometria
                      </label>
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <div class="checkbox">
                      <label for="">
                        <input  name="CampoVisual" type="checkbox" if(fema_tipo_procesos="6"){required }/> Campo Visual
                      </label>
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <div class="checkbox">
                      <label for="">
                        <input name="Electroencefalograma" type="checkbox" if(fema_tipo_procesos="6"){required }/> Electroencefalograma
                      </label>
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <div class="checkbox">
                      <label for="">
                        <input  name="CarnetVacunaTropicales" type="checkbox" if(fema_tipo_procesos="6"){required }/> Carnet de Vacuna Enf. Tropicales
                      </label>
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <div class="checkbox">
                      <label for="">
                        <input  name="CarnetVacunaCOVID" type="checkbox" if(fema_tipo_procesos="6"){required }/> Carnet de Vacuna COVID
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div id="div131" style="display: none;">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="checkbox">
                      <label for="">
                        <input  name="CarnetVacunaTropicales" type="checkbox" if(fema_tipo_procesos="7"){required }/> Carnet de Vacuna Enf. Tropicales
                      </label>
                    </div>
                  </div>
                </div>
                
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="checkbox">
                      <label for="">
                        <input  name="CarnetVacunaCOVID" type="checkbox"/> Carnet de Vacuna COVID
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div id="div151" style="display: none;">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="checkbox">
                      <label for="">
                        <input  name="CarnetVacunaTropicales" type="checkbox" if(fema_tipo_procesos="9"){required }/> Carnet de Vacuna Enf. Tropicales
                      </label>
                    </div>
                  </div>
                </div>
                
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="checkbox">
                      <label for="">
                        <input  name="CarnetVacunaCOVID" type="checkbox" if(fema_tipo_procesos="9"){required }/> Carnet de Vacuna COVID
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div id="div161" style="display: none;">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="checkbox">
                      <label for="">
                        <input  name="CarnetVacunaTropicales" type="checkbox" if(fema_tipo_procesos="10"){required }/> Carnet de Vacuna Enf. Tropicales
                      </label>
                    </div>
                  </div>
                </div>
                
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="checkbox">
                      <label for="">
                        <input  name="CarnetVacunaCOVID" type="checkbox"/> Carnet de Vacuna COVID
                      </label>
                    </div>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">DESCRIPCION</label>
                    <textarea name="descripcion10" id="descripcion10" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>

            <div id="div141" style="display: none;">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">DESCRIPCION</label>
                    <textarea name="descripcion8" id="descripcion8" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>

            <div id="div171" style="display: none;">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">DESCRIPCION</label>
                    <textarea name="descripcion11" id="descripcion11" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>

            <div id="div181" style="display: none;">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">DESCRIPCION</label>
                    <textarea name="descripcion12" id="descripcion12" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>

            <div id="div191" style="display: none;">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">DESCRIPCION</label>
                    <textarea name="descripcion13" id="descripcion12" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>
            </div>
        </div> <!-- .panel -->
      </div>
      </div>
      <div class="panel-footer" id="cierre" style="display: none;">
            <a href="/apertura_fema" class="btn btn-default">Cerrar</a>

            <input id="guarda" class="btn btn-primary pull-right" value="Aperturar Ficha">
          </div>
    </form>
  

</div> <!-- .container -->
@include('frontend.includes.footer')
@endsection
