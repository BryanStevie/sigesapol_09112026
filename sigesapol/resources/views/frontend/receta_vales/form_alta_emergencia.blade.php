@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
< link rel ="stylesheet" type ="text/css" href ="plugins/jQuery/jquery-ui.css" />

<?php

if($hospitalizado->fecha_egreso!=NULL){
  $fecha_egreso=date("d-m-Y", strtotime($hospitalizado->fecha_egreso));
  $hora_egreso =$hospitalizado->hora_egreso;
  $id_condicion=$hospitalizado->id_condicion;     
  $id_destino=$hospitalizado->id_destino;      
  $codigo_usuario_egreso=$hospitalizado->codigo_usuario_egreso;
  $id_sala_egreso=$hospitalizado->id_sala_egreso;

  $id_medico_egreso=$hospitalizado->id_medico_egreso; 
  $folio =$hospitalizado->folio;
  $numero_descanso_medico =$hospitalizado->numero_descanso_medico;
  $observacion=$hospitalizado->observacion;   

  $id_diagnostico = $id_diagnostico_egreso;

}else{
  $fecha_egreso=date("d-m-Y", strtotime($fechaServidor));
  $hora_egreso=date("H:i:s");
  $id_condicion="";     
  $id_destino="";      
  $codigo_usuario_egreso=""; 
  $id_sala_egreso=$hospitalizado->id_triaje;

  $id_medico_egreso=""; 
  $folio =""; 
  $numero_descanso_medico =""; 
  $observacion="";  

  $id_diagnostico ="";
}
 



?>
<style>

.title-dialog {
  font-size: 18px;
  font-family:Arial, Helvetica, sans-serif;
}
    
.ui-autocomplete {
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 1000;
  display: none;
  float: left;
  min-width: 160px;
  padding: 5px 7px;
  margin: 2px 0 0;
  list-style: none;
  font-size: 15px;
  font-family:Arial, Helvetica, sans-serif;
  text-align: left;
  background-color: #ffffff;
  border: 1px solid #cccccc;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: 4px;
  -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
  background-clip: padding-box;
}

.ui-autocomplete > li > div {
  display: block;
  padding: 3px 20px;
  clear: both;
  font-weight: normal;
  line-height: 1.42857143;
  color: #333333;
  white-space: nowrap;
}

.ui-state-hover,
.ui-state-active,
.ui-state-focus {
  text-decoration: none;
  color: #FFFFFF!important;
  background: #1d71b8!important;
  cursor: pointer;
}

.ui-helper-hidden-accessible {
  border: 0;
  clip: rect(0 0 0 0);
  height: 1px;
  margin: -1px;
  overflow: hidden;
  padding: 0;
  position: absolute;
  width: 1px;
}

input[readonly]{
    background-color:#eeeeee;
    text-align: center
}


span:focus,
textarea:focus,
input[type="text"]:focus,
input[type="password"]:focus,
input[type="datetime"]:focus,
input[type="datetime-local"]:focus,
input[type="date"]:focus,
input[type="month"]:focus,
input[type="time"]:focus,
input[type="week"]:focus,
input[type="number"]:focus,
input[type="email"]:focus,
input[type="url"]:focus,
input[type="search"]:focus,
input[type="tel"]:focus,
input[type="color"]:focus,
.uneditable-input:focus{   
  border-color: #2196F3;
  box-shadow: 0 1px 1px #2196F3 inset, 0 0 8px #2196F3;
  outline: 0 none;
}

/*
.select2-container:focus {
	border-color: rgba(126, 239, 104, 0.8);
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(126, 239, 104, 0.6);
	outline: 0 none;
}
*/
/*
.select2-container-active .select2-choice {
  border: 1px solid #999;
  box-shadow: inset 0 2px 3px 0 rgba(0,0,0,.06);
  border-radius: 4px;
} 
 
.select2-container-active.select2-dropdown-open .select2-choice {
  border-bottom: 2px solid #ccc;
  border-left: 1px solid #ccc;
  border-right: 1px solid #ccc;
  border-top: 1px solid #ccc;
  box-shadow: none;
}
*/

</style>

<script>

	function __autocomplete__AjaxBusqueda(){
		
		$("#nombre_diagnostico").autocomplete({
		minLength: 1,
		delay : 400,
		source: function(request, response) {
		$.ajax({
			url: '/obtener_diagnostico/' + request.term,
			dataType: "json",
			success: function(data)  {
			response(data);
		  }
		})
		
		  },select:  function(e, ui) {
				var id_diagnostico = ui.item.id;
				$('#id_diagnostico').val(id_diagnostico);
		  }
		});
	}


    function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46) && (keynum == 9))
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }
    
    function soloNumeros(e)
        {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)
        }
        
    function soloNumerosMenosCero(e)
        {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)       
        }

    
   /* $tur = $("#frmReceta").find("#turno");
    //$tur.select2();
    $tur.select2({
        width: 'resolve' // need to override the changed default
    });*/
   
    $medicos = $('#frmReceta').find("#id_medico")
    //$medicos.select2();
    $medicos.select2({
        width: 'resolve' // need to override the changed default
    });

    $diagnosticosel = $('#frmReceta').find("#id_diagnostico")
    $diagnosticosel.select2({
        width: 'resolve' // need to override the changed default
    });
	
	jQuery(document).on('focus', '.select2', function() {
    	jQuery(this).siblings('select').select2('open');
	});


// return the id of the next focusable field
function getNextFocusableFieldId(idIn) {
    var focusables = jQuery("input, select, textarea");
    var reachedId = false;
    var id = '';
    var nextId = '';
    jQuery.each(focusables, function(index, value) {
        id = jQuery(this).attr('id');
        // if we reached the id last time set the nextId and exit each
        if (reachedId) {
            nextId = id;
            return false;
        }
        // if the ids match set the flag for the next iteration
        if (id == idIn) {
            reachedId = true;
        }
    });
    return nextId;
  }
    
    function validateFrm(){
        
        if ($('#frmReceta').find("#fecha_egreso").val() == "") {
            bootbox.alert("Falta ingresar la fecha de egreso");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        if ($('#frmReceta').find("#hora_egreso").val() == "") {
            bootbox.alert("Falta ingresar la hora de egreso");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        if ($('#frmReceta').find("#id_condicion").val() == "") {
            bootbox.alert("Falta seleccionar la condición de egreso");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        if ($('#frmReceta').find("#id_destino").val() == "") {
            bootbox.alert("Falta seleccionar el destino de egreso");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if ($('#frmReceta').find("#id_diagnostico").val() == "") {
            bootbox.alert("Falta seleccionar el diagnostico (CIE10)");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if ($('#frmReceta').find("#id_medico").val() == "") {
            bootbox.alert("Falta seleccionar el Medico");
            $('#btnReg').prop('disabled', false);
            return false;
        }        

        submitFrm();
        return false;
    };
    
    function submitFrm() {
      bootbox.confirm({
          message: "Revise que los datos se encuentren correctos antes de registrar. ¿Desea Grabar?.",
          className: 'title-dialog',
          buttons: {
              confirm: {
                  label: 'Si',
                  className: 'btn-success btn-lg'
              },
              cancel: {
                  label: 'No',
                  className: 'btn-danger btn-lg'
              }
          },
          callback: function (result) {
              if (result==true) {
                  $("#frmReceta").submit();
              } else {
                  $('#btnReg').prop('disabled', false);
              }
          }
      });
    }
    //--------------------------------------------------------------------------
	 
  function limpiaDatos()
  {
            
            $("#turno").val('').trigger("change");
            var newOption = "";
            newOption = "<option value=''>--Seleccionar-</option>";
            $('#id_medico').html(newOption);
            $("#id_medico").val('').trigger("change");

  }
	
	function getMedico(){
      var newOption = "";
      newOption = "<option value=''>--Seleccionar-</option>";
      $('#id_medico').html(newOption);
      $("#id_medico").val('').trigger("change");
      
      if($('#turno').val()=="") return false;
        
  		var id_ipress 		= $('#id_ipress').val();
      var motivo     = $('#condicion').val();
  		var id_consultorio 	= $('#id_consultorio').val();
  		var turno 		= $('#turno').val();
  		var fecha_admision 	= $('#fecha_atencion').val();
  		
  		$.ajax({
  			url: '/obtener_medico_por_idipress_idconsultorio_turno_hospitalizacion_motivo/' + id_ipress+ '/' + id_consultorio+'/'+turno + '/' + fecha_admision + '/' + motivo,
  			success: function(result){                    
  				var newOption = "";
  				newOption = "<option value=''>--Seleccionar-</option>";
  				$(result).each(function (ii, oo) {
  					newOption += "<option value='"+oo.id+"'>"+oo.paterno+" "+oo.materno+", "+oo.nombre+" - "+oo.colegiatura+"</option>";
  				});                 
  				$('#id_medico').html(newOption);
  				$("#id_medico").select2({max_selected_options: 4});
  			
  			}
  		});
        
    }

    function cambiarDestino(){
      var newOption = "";
      newOption = "<option value=''>--Seleccionar-</option>";
      $('#id_destino').html(newOption);
      
      if($('#id_condicion').val()=="") return false;

      var id_condicion  = $('#id_condicion').val();
      
      $.ajax({
        url: '/obtener_destino_por_condicion/' + id_condicion,
        success: function(result){                    
          var newOption = "", cant=0;
          cant= result.length;
          if(cant>1){
            newOption = "<option value=''>--Seleccionar-</option>";
          }          
          $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id+"'>"+oo.descripcion+"</option>";
          });                 
          $('#id_destino').html(newOption);
          $("#id_destino").select2({max_selected_options: 4});
        
        }
      });
    }
	
	  
 
   /*$('#fecha_atencion').datepicker().on('hide', function (ev) {
      
      var fechaIni = $('#fecha_atencion').val();
      
      f1 = fechaIni.split("-");
      var fechaFin = $('#fecha_expedicion').val();
      $("#condicion").val('').trigger("change");
      var newOption = "";
      newOption = "<option value=''>--Seleccionar-</option>";
      $("#turno").val('').trigger("change");
      var newOption = "";
      newOption = "<option value=''>--Seleccionar-</option>";
      $('#id_medico').html(newOption);
      $("#id_medico").val('').trigger("change");
      
  });*/ 
</script>
<style>
    .modal-dialog{
        width: 850px !important;
    }    
    .suggest-element{
        margin-left:5px;
        margin-top:5px;
        width:350px;
        cursor:pointer;
    }
    #suggestions {
        width:350px;
        height:150px;
        overflow: auto;
    }
    
        
    .alert {
        padding: 10px;
background-position: 2% 7px;
background-repeat: no-repeat;
background-size: auto 35px;
/*background-color: rgba(0, 0, 0, 0);*/
border: 0;
min-width: auto !important;
text-align: left;
padding-left: 68px;
font-size: 14px;
margin-bottom: 10px;
}

.form-control{
    height: 28px;
}

.form-group{
    margin-bottom: 10px;
}
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="row">
                <div class="panel panel-primary filterable">            
                    <div class="panel-heading">
                        <h3 class="panel-title">ALTA DE PACIENTES EN EMERGENCIA</h3>      
                    </div> 
                    <div style=" font-size: 11px" class="panel-body">
                        <form class="form-horizontal" method="post" action="{{ route('guardar_alta_emergencia')}}" id="frmReceta" autocomplete="off">
                            <!--  action="{{ route('registrar_receta_rua')}}" -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="col-md-4 text-center">
                                    <img src="../../images/logo_sanidad.png" width="50" height="auto">
                                        <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
                                        <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                                        <input type="hidden" value="dni" name="tipo">
                                        <input type="hidden" name="id_emergencia" id="id_emergencia" value="{{$id_emergencia}}"/>
                                        <input type="hidden" name="opcion" id="opcion" value="{{$opcion}}"/>
                                    <input type="hidden" value="<?php echo $id_ipress ?>" name="id_ipress" id="id_ipress">
                                    <input type="hidden" value="<?php echo $valor ?>" name="valor" id="valor">
                                </div>
                                <div class="col-md-4" style="text-align: center">
                                    <label>{{$establecimiento->nombre}}</label><br>
                                    <label style="font-size: 24px">ALTA PACIENTE EN EMERGENCIA</label><br>
                                </div>
                                <div class="col-md-4 text-center">
                                    <img src="../../images/logo_sanidad.png" width="50" height="auto">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div id="msj-atenciones">
                                        <div id="det-msj-atenciones"></div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label>TIPO ATENCIÓN</label>
                                    <input type="text" readonly="" value="<?php echo $tipo_receta ?>" name="tipo_receta" id="tipo_receta" class="form-control" style="text-align:left">
                                </div>                                
                                <div class="col-md-2">
                                    <label>NRO</label>
                                    <input type="text" class="form-control" readonly="" value="<?php echo $hospitalizado->nro_emergencia; ?>" name="nro_emergencia" id="nro_emergencia" tabindex="1"  data-date-end-date="0d" autocomplete="off"/>
                                </div>
                                <div class="col-md-3">
                                <label>PACIENTE</label>
                                    <input type="text" class="form-control" readonly="" value="<?php echo $Paciente; ?>" name="Paciente" id="Paciente"/>
                                </div>
                                <div class="col-md-2">
                                    <label>FEC. INGRESO</label>
                                    <input type="text" class="form-control" value="<?php echo date("d-m-Y", strtotime($hospitalizado->fecha_ingreso)); ?>" name="fecha_atencion" id="fecha_atencion" readonly />
                                </div>

                                <div class="col-md-2">
                                    <label>HORA INGRESO</label>
                                    <input type="text" class="form-control" value="<?php echo $hospitalizado->hora_ingreso?>" name="hora_ingreso" id="hora_ingreso" readonly/>
                                </div>
                               
                             </div>  
                            <div class="form-group"> 
                                <div class="col-md-2">
                                    <label>FEC. EGRESO</label>
                                    <input type="text" class="form-control datepicker" value="<?php echo $fecha_egreso; ?>" name="fecha_egreso" id="fecha_egreso" autocomplete="off"/>
                                </div> 

                                 <div class="col-md-2">
                                    <label>HORA EGRESO</label>
                                    <input type="text" class="form-control" value="<?php echo $hora_egreso; ?>"  name="hora_egreso" id="hora_egreso" />
                                </div>                             
                                
                                <div class="col-md-2">
                                    <label>CONDICION</label>
                                    <select class="form-control" name="id_condicion"  id="id_condicion" onchange="cambiarDestino();">                 
                                          <option value="">--Seleccionar--</option>
                                          @foreach($condicion as $f)
                                          <option value="{{$f->id}}" <?php if($id_condicion==$f->id) echo 'selected'?>>{{$f->descripcion}}</option>
                                          @endforeach

                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>DESTINO</label>
                                    <select class="form-control" name="id_destino"  id="id_destino">                 
                                          <option value="">--Seleccionar--</option>
                                          @if($destino!=NULL)
                                          @foreach($destino as $f)
                                          <option value="{{$f->id}}" <?php if($id_destino==$f->id) echo 'selected'?>>{{$f->descripcion}}</option>
                                          @endforeach
                                          @endif

                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>DIAGNOSTICO</label>
                                    <select class="form-control" name="id_diagnostico"  id="id_diagnostico" tabindex="5">
                                     <option value="">--Seleccionar--</option>
                                        @foreach($diagnosticos as $f)
                                        <option value="{{$f->id}}" <?php if($id_diagnostico==$f->id) echo 'selected'?>>{{$f->nombre}}</option>
                                        @endforeach                                        
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>MEDICO (DNI | CMP | Nombre y Apellidos)</label>
                                    <?php if (isset($existe_medico) && $existe_medico == 1): ?>
                                        <input  name="exite_medico" value="{{$existe_medico}}" type="hidden">
                                    <?php endif; ?>
                                    <select class="form-control" name="id_medico"  id="id_medico" tabindex="5" style="width: 100%">
                                        <option value="">--Seleccionar--</option>
                                        @foreach($medico as $m)
                                          <option value="{{$m->id}}" <?php if($id_medico_egreso==$m->id) echo 'selected'?>>{{$m->paterno}} {{$m->materno}}, {{$m->nombre}} - {{$m->colegiatura}}</option>
                                        @endforeach                                            
                                    </select>
                                </div>
                               
                               <div class="col-md-2">
                                    <label>SALA EGRESO</label>
                                    <select class="form-control" name="id_sala_egreso"  id="id_sala_egreso" onchange="cambiarDestino();"> 
                                          @foreach($Triaje as $m)
                                            <option value="{{$m->id}}" <?php if($id_sala_egreso==$m->id) echo 'selected'?>>{{$m->descripcion}}</option>
                                          @endforeach                                                                   
                                    </select>
                                </div>
                                <div class="col-md-5">
                                <label>OBSERVACIÓN</label>
                                    <input type="text" class="form-control" name="observacion" id="observacion" value="<?php echo $observacion; ?>" />
                                </div>
                                
                            </div>
                           
                            <div class="form-group">
                                <div class="col-md-2">
                                <label>DESCANSO MÉDICO</label>
                                    <input onKeyPress="return soloNumeros(event)" type="text" class="form-control" name="numero_descanso_medico" id="numero_descanso_medico" value="<?php echo $numero_descanso_medico; ?>" />
                                </div>
                                <div class="col-md-2">
                                <label>NRO FOLIOS</label>
                                    <input onKeyPress="return soloNumeros(event)" type="text" class="form-control" name="folio" id="folio" value="<?php echo $folio; ?>" />
                                </div>
                               
                            </div>
                            
                            
                        
                            <div class="form-group">
                                <div class="col-md-6">
                                    <a href="/all_emergencia_alta_by_farmacia/" class="form-control btn btn-danger" tabindex="99922">CANCELAR</a>
                                </div>
                                <div class="col-md-6">
                                    <button id="btnReg" class="form-control btn btn-success" type="button" tabindex="99921" onclick="validateFrm()">GUARDAR</button>
                                </div>
                            </div>

                            </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection