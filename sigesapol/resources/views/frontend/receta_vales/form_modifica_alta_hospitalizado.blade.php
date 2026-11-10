@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}

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

    
    $tur = $("#frmReceta").find("#turno");
    //$tur.select2();
    $tur.select2({
        width: 'resolve' // need to override the changed default
    });
   
    $medicos = $('#frmReceta').find("#id_medico")
    //$medicos.select2();
    $medicos.select2({
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
	
  function validar_rec(nro_receta, id_farmacia){
        var consultorio = $("#id_consultorio option:selected").text();
            option = {                
                url: '/evitar_duplicados_rectas/' + nro_receta+ '/' + id_farmacia,
                type: 'GET',
                async:false,
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                existe=data; 
                if(existe>0){
                    $('#frmReceta').find("#nro_receta").focus();
                    bootbox.alert("La receta: " + nro_receta+ " se encuentra actualmente registrada");
                    $('#btnReg').prop('disabled', false);
                    return false;
                } else {
                    validate_otros();
                }
            });     
        }
    
    function validateFrm(){
        val_nro_rec=$('#frmReceta').find("#valid_nro_receta").val();
        $('#btnReg').prop('disabled', true);
        nro_receta='P-'+$('#frmReceta').find("#nro_receta").val()+'-'+$('#frmReceta').find("#digitos_adicionales").val();
        id_farmacia=$('#frmReceta').find("#id_farmacia").val();
        id_consultorio=$('#frmReceta').find("#id_consultorio").val();
        
        validar_rec(nro_receta, id_farmacia);
        
    }
  
	  
    function validate_otros(){
        
        if ($('#frmReceta').find("#condicion").val() == "") {
            bootbox.alert("Falta seleccionar la Condicion");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        if ($('#frmReceta').find("#turno").val() == "") {
            bootbox.alert("Falta seleccionar el Turno");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        if ($('#frmReceta').find("#id_medico").val() == "") {
            bootbox.alert("Falta seleccionar el Medico");
            $('#btnReg').prop('disabled', false);
            return false;
        }

        if ($('#frmReceta').find("#id_diagnostico").val() == "") {
            bootbox.alert("Falta seleccionar el diagnostico (CIE10)");
            $('#btnReg').prop('disabled', false);
            return false;
        }
        
        if ($('#frmReceta').find("#fecha_atencion").val() == "") {
            bootbox.alert("Falta seleccionar la fecha de ingreso del paciente");
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
	
	  
 
   $('#fecha_atencion').datepicker().on('hide', function (ev) {
      
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
      
  });
       
    
	
  function ocultar_titu(){
      if($("#muestra-datos-titu").is(':hidden')){
          $('#muestra-datos-titu').show();
      } else {
          $('#muestra-datos-titu').hide();
      }
  }
    
    /*esta es la función estática*/
    function calcular(fecini,fecfin){
            var fechaini = new Date(fecini);
            var fechafin = new Date(fecfin);
            var diasdif= fechafin.getTime()-fechaini.getTime();
            var contdias = Math.round(diasdif/(1000*60*60*24));
            alert(contdias);
    }
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
.alert.alert-danger {
background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAARVBMVEX////7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0NAQECdhUHYsUKGdEG1l0JLSEBuYkHkukPKU8NPAAAADnRSTlMA4EDAoCAQ8IBgkDDQUFItelQAAAC0SURBVHherZBLDsMwCAVD7Nj5gvPr/Y/a0FpGLTibdnbozQJN829agPZu7/CiuxE8C76+T/hiqgruLbjqh5ip/TljZrb3AQuDKYAIYO09L3TBRq/3EHk4shCDEkZkTqITmdGMLIIO7r8FbzfaiDappSNjIkpZcCqyCDo4WAJYkR8prTp4ADSBUCIX9uPY5epL5AJdyBWDRDYFHCWyCiXBObKwrh+nl4g2TiLVhSXW97g0v/MEHIQbCYeFmYAAAAAASUVORK5CYII=);
border-top: 1px solid rgba(140, 0, 0, 0.4);
border-bottom: 1px solid rgba(140, 0, 0, 0.4);
}
.alert.alert-info {
background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAADBklEQVR42u3YyWsTURwHcP8aQUWtWlu7JEbtpngQvIqXevDWm1fXehO12BTErdQiVMFQ8ODBusWkmSa1lVrTxdrG0FA9WJtkljfL19/LTDKMibl0EorkwReSCQ8+fN+bl2G2AdhSqYH+P1CpAS0DfXUI+ufzUCfaoQptYMIxsMhRsE/d0L4/BNR0qcnug4y159CmTkOLdkKNdnBQAaNEjkAZ90EOH4YcOQ51dQQVBRnLN6DHuqDFOqGZGEpbaVDICynkAVvoRUVAxuqQE8PjXC7C/AX64KG0gq3ch6sgQ1zkEBtTph2FY8JeApkYKdgCMdgMPTMP10D64kUOsTDtDsw/27FAIge9b4Y8e8EdELS0jTAh5TF2Ow5Q9l0TDDWNTYOMjSkLQREcEAdGCRfvHdFcLgI1EegQtPXY5kFactAE2JDyGHvvONrhILZ81w3QIxNQCEfw2JvYxpRYKrMdSqNLoNSzPMBGTJwEmz4HuSSGMnkWYrgjv1Rm3jZATQ5vHqT//sgRVhtmI8p0NwADbOF6EUaOX4GiZCHGzhAkD2ogUD20XxNw5baXhRN2G2EvR+QwhqFDme91YJgiQfpyCTamkTAHaQm97p1DauIBR1jxWI14CHMNuqZCmbsKmcKYBLkYQzkA9m3APRA/P6TxrvztbCfYkoOoqgKVydTQ5ZIYMdgKQ91wD8SHvj7JAYXb2T5jmqEkhiEnHnNIESb7po72joCK/Ntray8sRD7UBkVeGoBEIQilwYFRUwFU9HmIrdzjCHtpKNJXP48FqSfIfspe2jf+Sj4P2UOMnOKQfCOE6Ye0eMdqZR+yr/fwc8icXA2QmnzCD7pCI+JiH8SFPt4KYXYjO7YTLDFYPZCensu3QakjzC2I8zcJsguZsR3IvNoOfWO2eiA+tJ9jfI9Q+qEkn1JGoCz1UW5D+/HSnFhNUCqVQjwezyUajSIUChW+02/VB42OjqJMaqCtB5qZmUEgEIDf73eEXxMEofogPtLpNHp6euDz+Xhyn+lauUm1tx81UMXzBw5wT9JYWi53AAAAAElFTkSuQmCC);
border-top:1px solid rgba(255, 165, 0,0.4);
border-bottom:1px solid rgba(255, 165, 0,0.4);
color:  rgb(225, 75, 0);
}
.alert.alert-success {
background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAqFBMVEX////w8PDw8PDw8PDw8PDw8PDw8PDw8PDW1tbn5+ff398PnVjd3d3i4uIPnVjk5OQPnVjg4OAPnVgzqW8PnVgPnVjT09Pw8PDG4NOp1sBjvJHu7u4PmVYqp2ri6+YcomFHsn4bmlzHzsoPnFeuxbl9tJk0n2t/xqSVvqqNzK1ZqYKJuaJVt4c4rHQnnWPl5eXj4+MPm1fe3t6b0bfc3NxBo3TY2NjX19eKj23kAAAAFXRSTlMA8BCwcFCgkP4f3NDnrzCHoM8Qc/Avz3otAAAA9klEQVR4Xq2T2XKDMAwAMRACNGdbyUDus/d9/f+f1VHEiFamfcm+7qIZGTs4IWEcGeswURz6dGIbJCrp0MeC6QTBCADO2Hetokv+vNXbyvnLAc9v8cA+NH4/veAFEr/f7HIe0OJvETMKYhLlLw8fiNinICKxnHHCfr92QY8CcwwAloX41cFjSoElCnBU89pvkeCAeAPH4or89Bp1YG+g5uUOPQEP5wWbgZzjPRDiU1mTmD/wggyvGVspZgCP4rGvjrpYuAWFTP+sounzwPO7n8SnNEBdmGesGZLUV+6V/ZilLt7F+6/95BMxHf71cCZfefbf0zsl3w3QOjVy6QFpAAAAAElFTkSuQmCC);
border-top:1px solid limegreen;
border-bottom:1px solid limegreen;
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
                        <h3 class="panel-title">MODIFICAR ALTA DE PACIENTES</h3>      
                    </div> 
                    <div style=" font-size: 11px" class="panel-body">
                        <form class="form-horizontal" method="post" action="{{ route('actualizar_alta_hospitalizacion')}}" id="frmReceta" autocomplete="off">
                            <!--  action="{{ route('registrar_receta_rua')}}" -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="col-md-4 text-center">
                                    <img src="../../images/logo_sanidad.png" width="50" height="auto">
                                        <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
                                        <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                                        <input type="hidden" value="dni" name="tipo">
                                        <input type="hidden" name="id_hospitalizado" id="id_hospitalizado" value="{{$id_hospitalizado}}"/>
                                    <input type="hidden" value="<?php echo $id_ipress ?>" name="id_ipress" id="id_ipress">
                                    <input type="hidden" value="<?php echo $valor ?>" name="valor" id="valor">
                                </div>
                                <div class="col-md-4" style="text-align: center">
                                    <label>{{$establecimiento->nombre}}</label><br>
                                    <label style="font-size: 24px">MODIFICAR ALTA PACIENTE</label><br>
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
                                <div class="col-md-3">
                                    <label>TIPO ATENCIÓN</label>
                                    <input type="text" readonly="" value="<?php echo $tipo_receta ?>" name="tipo_receta" id="tipo_receta" class="form-control" style="text-align:left">
                                </div>
                                <div class="col-md-2">
                                    <label>FEC. SALIDA</label>
                                    <input type="text" class="form-control datepicker" value="<?php echo date("d-m-Y", strtotime($hospitalizado->fecha_salida)); ?>" name="fecha_atencion" id="fecha_atencion" tabindex="1"  data-date-end-date="0d" autocomplete="off"/>
                                </div>
                                @if($nivel==3)
                                <div class="col-md-2">
                                    <label>TARIFA</label>
                                    <input type="text" readonly="" value="<?php 
                                      switch($hospitalizado->tarifa){
                                        case 'T1': echo 'TARIFA 1'; break;
                                        case 'T2': echo 'TARIFA 2'; break;
                                        case 'T3': echo 'TARIFA 3'; break;
                                        case 'T4': echo 'TARIFA 4'; break;
                                      }
                                     ?>" name="tarifa" id="tarifa" class="form-control" style="text-align:left">
                                </div>                                
                                @endif
                                <div class="col-md-2">
                                    <label>CONDICION</label>
                                    <select class="form-control" onchange="limpiaDatos();" name="condicion" id="condicion"  tabindex="3" style="width: 100%">
                                        <option value="">-Seleccionar-</option>
                                        <option value="CU" <?php if ($hospitalizado->condicion=='CU') echo 'selected';?>>CURADO</option>
                                        <option value="AL" <?php if ($hospitalizado->condicion=='AL') echo 'selected';?>>ALIVIADO</option>
                                        <option value="NT" <?php if ($hospitalizado->condicion=='NT') echo 'selected';?>>NO TRATADO</option>
                                        <option value="FU" <?php if ($hospitalizado->condicion=='FU') echo 'selected';?>>FUGADO</option>
                                        <option value="FA" <?php if ($hospitalizado->condicion=='FA') echo 'selected';?>>FALLECIDO</option>
										<option value="RE" <?php if ($hospitalizado->condicion=='RE') echo 'selected';?>>REFERIDO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                
                                @if($nivel==2)
                                <div class="col-md-4">
                                    <label>SALA</label>
                                    <input type="text" class="form-control" value="{{$nombre_consultorio}}" id="consultorio" name="consultorio"  readonly="">
                                    <input type="hidden" class="form-control" value="{{$id_consultorio}}" id="id_consultorio" name="id_consultorio"  readonly="">
                                </div>
                                <div class="col-md-2">
                                    <label>TURNO</label>
                                    <select class="form-control" name="turno" id="turno"  tabindex="3" style="width: 100%" onchange="getMedico()">
                                        <option value="">-Seleccionar-</option>
                                        <option value="MD" <?php if ($hospitalizado->turno_salida=='MD') echo 'selected';?>>MADRUGADA</option>
                                        <option value="M" <?php if ($hospitalizado->turno_salida=='M') echo 'selected';?>>MAÑANA</option>
                                        <option value="T" <?php if ($hospitalizado->turno_salida=='T') echo 'selected';?>>TARDE</option>
                                        <option value="N" <?php if ($hospitalizado->turno_salida=='N') echo 'selected';?> >NOCHE</option>
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
                                          <option value="{{$m->id}}" <?php if($hospitalizado->id_medico_alta==$m->id) echo 'selected'?>>{{$m->paterno}} {{$m->materno}}, {{$m->nombre}} - {{$m->colegiatura}}</option>
                                        @endforeach                                            
                                    </select>
                                </div>
                                @endif
                                @if ($nivel==3)
                                  <div class="col-md-3">
                                    <label>SERVICIO</label>
                                    <input type="text" class="form-control" value="{{$nombre_sala}}" id="consultorio" name="consultorio"  readonly="">
                                    <input type="hidden" class="form-control" value="{{$id_sala}}" id="id_consultorio" name="id_consultorio"  readonly="">
                                </div>
                              
                                <div class="col-md-3">
                                    <label>SALA</label>
                                    <input type="text" class="form-control" value="{{$nombre_consultorio}}" id="consultorio" name="consultorio"  readonly="">
                                    <input type="hidden" class="form-control" value="{{$id_consultorio}}" id="id_consultorio" name="id_consultorio"  readonly="">
                                </div>
                                <div class="col-md-4">
                                    <label>MEDICO (DNI | CMP | Nombre y Apellidos)</label>
                                    <?php if (isset($existe_medico) && $existe_medico == 1): ?>
                                        <input  name="exite_medico" value="{{$existe_medico}}" type="hidden">
                                    <?php endif; ?>
                                    <select class="form-control" name="id_medico"  id="id_medico" tabindex="5" style="width: 100%">
                                        <option value="">--Seleccionar--</option>
                                        @foreach($medico as $m)
                                          <option value="{{$m->id}}" <?php if($hospitalizado->id_medico_alta==$m->id) echo 'selected'?>>{{$m->paterno}} {{$m->materno}}, {{$m->nombre}} - {{$m->colegiatura}}</option>
                                        @endforeach                                            
                                    </select>
                                </div>
                                @endif
                                <div class="col-md-2">
                                    <label>CAMA</label>
                                    <input type="text" class="form-control" value="{{$nombre_cuarto}} - {{$nombre_cama}}" id="id_cama" name="id_cama"  readonly="">
                                </div>
                            </div>
                            @if($beneficiario->parentesco!="TITULAR")
                            <div id="muestra-datos-titu" style="display: none;">
                                <div class="form-group">
                                     <div class="col-sm-12">
                                        <label><a href="#" onclick="ocultar_titu()">Ocultar Datos del Titular</a></label>
                                     </div>
                                    <div class="col-md-2">
                                        <label>DNI (TITULAR)</label>
                                        <?php if ($opcReceta == 1): ?>
                                            <input type="hidden" class="form-control" value="{{$beneficiario->nomtipdoctitular}}" name="tipdoc_titular" id="tipdoc_titular" readonly=""/>
                                            <input readonly="" type="text" class="form-control" value="{{$beneficiario->nrodoctitular}}" name="dni_titular">
                                        <?php endif; ?>
                                        <?php if ($opcReceta == 2): ?>
                                            <input type="hidden" class="form-control" value="{{$beneficiario->nomtipdoctitular}}" name="tipdoc_titular" id="tipdoc_titular" readonly=""/>
                                            <input type="text" class="form-control" value="{{$beneficiario->dni_titular}}" name="dni_titular" readonly="">
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-7">
                                        <label>APELLIDOS Y NOMBRES (TITULAR)</label>
                                        <?php if ($opcReceta == 1): ?>
                                            <input type="text" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apepattitular . " " . trim($beneficiario->apemattitular . " " . $beneficiario->apecastitular)) . ", " . $beneficiario->nomtitular) ?>" name="paterno_beneficiario"  readonly="">
                                        <?php endif; ?>

                                        <?php if ($opcReceta == 2): ?>
                                            <input type="text" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apepattitular . " " . trim($beneficiario->apemattitular . " " . $beneficiario->apecastitular)) . ", " . $beneficiario->nomtitular) ?>" name="paterno_beneficiario" readonly="">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <div class="col-md-2">
                                    <label>PARENTESCO &nbsp;
                                        <?php 
                                        if($beneficiario->parentesco<>"TITULAR") {
                                            ?>
                                        <a href="#" onclick="ocultar_titu()">Datos Titular</a>
                                        <?php
                                        }
                                            ?></label>
                                    <input readonly="" value="{{$beneficiario->parentesco}}" type="text" name="tipo_beneficiario" id="" class="form-control" >

                                </div>
                                <div class="col-md-2">
                                    <label>{{$beneficiario->nomtipdocafiliado}}</label>
                                    <?php if ($opcReceta == 1): ?>
                                        <input type="hidden" class="form-control" value="{{$beneficiario->nomtipdocafiliado}}" name="tipdoc_beneficiario" id="tipdoc_beneficiario" readonly=""/>
                                        <input type="hidden" class="form-control" value="{{$beneficiario->cip}}" name="cip_beneficiario" id="cip_beneficiario" readonly=""/>
                                        <input type="text" class="form-control" value="{{$beneficiario->nrodocafiliado}}" name="dni_beneficiario" id="dni_beneficiario" readonly=""/>
                                    <?php endif; ?>

                                    <?php if ($opcReceta == 2): ?>
                                        <input type="hidden" class="form-control" value="{{$beneficiario->nomtipdocafiliado}}" name="tipodoc_beneficiario" id="tipo_beneficiario" readonly="">
                                        <input type="hidden" class="form-control" value="{{$beneficiario->cip}}" name="cip_beneficiario" id="cip_beneficiario" readonly="">
                                        <input type="text" class="form-control" value="{{$beneficiario->dni}}" name="dni_beneficiario" id="dni_beneficiario" required="" readonly="">
                                    <?php endif; ?>

                                </div>
                                @if($beneficiario->parentesco=="TITULAR")<?php //print_r($beneficiario);?> <!-- titular  -->
                                <div class="col-md-4">
                                    <label>APELLIDOS Y NOMBRES</label>
                                    <?php if ($opcReceta == 1): ?>
                                        <input readonly="" type="text" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apepatafiliado . " " . trim($beneficiario->apematafiliado . " " . $beneficiario->apecasafiliado)) . ", " . $beneficiario->nomafiliado) ?>" name="paterno_beneficiario" >
                                        <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->nomafiliado)) ?>" name="nombre_beneficiario"/>
                                        <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apepatafiliado)) ?>" name="paterno_beneficiario"/>
                                        <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apematafiliado . " " . $beneficiario->apecasafiliado)) ?>" name="materno_beneficiario"/>
                                    <?php endif; ?>

                                    <?php if ($opcReceta == 2): ?>
                                        <input type="text" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apepatafiliado . " " . trim($beneficiario->apematafiliado . " " . $beneficiario->apecasafiliado)) . ", " . $beneficiario->nomafiliado) ?>" name="paterno_beneficiario" readonly="">
                                        <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->nomafiliado)) ?>" name="nombre_beneficiario"/>
                                        <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apepatafiliado)) ?>" name="paterno_beneficiario"/>
                                        <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apematafiliado . " " . $beneficiario->apecasafiliado)) ?>" name="materno_beneficiario"/>
                                    <?php endif; ?>
                                </div> 
                                    
                                    <div class="col-md-2">
                                        <label>HISTORIA CLINICA</label>
                                        <input type="text" class="form-control" value="{{$hospitalizado->nro_historia}}" id="nro_historia" readonly  name="nro_historia" tabindex="5" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <label>GRADO</label>
                                        <input type="text" class="form-control" value="{{$hospitalizado->grado}}" id="grado" readonly  name="grado" tabindex="5" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                    </div> 
                                @else <!-- Derechohabienre  -->
                                    <div class="col-md-4">
                                        <label>APELLIDOS Y NOMBRES</label>
                                        <?php if ($opcReceta == 1): ?>
                                            <input readonly="" type="text" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apepatafiliado . " " . trim($beneficiario->apematafiliado . " " . $beneficiario->apecasafiliado)) . ", " . $beneficiario->nomafiliado) ?>" name="paterno_beneficiario" >
                                            <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->nomafiliado)) ?>" name="nombre_beneficiario">
                                            <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apepatafiliado)) ?>" name="paterno_beneficiario">
                                            <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apematafiliado . " " . $beneficiario->apecasafiliado)) ?>" name="materno_beneficiario">
                                        <?php endif; ?>

                                        <?php if ($opcReceta == 2): ?>
                                            <input type="text" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apepatafiliado . " " . trim($beneficiario->apematafiliado . " " . $beneficiario->apecasafiliado)) . ", " . $beneficiario->nomafiliado) ?>" name="paterno_beneficiario" id="paterno_beneficiario" readonly="">
                                            <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->nomafiliado)) ?>" name="nombre_beneficiario">
                                            <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apepatafiliado)) ?>" name="paterno_beneficiario">
                                            <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apematafiliado . " " . $beneficiario->apecasafiliado)) ?>" name="materno_beneficiario">
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <label>HISTORIA CLINICA</label>
                                        <input type="text" class="form-control" value="{{$hospitalizado->nro_historia}}" id="nro_historia" readonly name="nro_historia" tabindex="5" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                    </div>

                                    
                                    <div class="col-md-2">
                                        <label>GRADO TITULAR</label>
                                        <input type="text" class="form-control" value="{{$hospitalizado->grado}}" id="grado" readonly  name="grado" tabindex="5" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                                    </div>
                                @endif                                 
                            </div>  
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>DIAGNOSTICO</label>
                                        <input type="text" value="{{$cie[0]->codigo}} - {{$cie[0]->nombre}}"class="form-control typeahead tt-query" id="nombre_diagnostico" name="nombre_diagnostico" tabindex="10">
                                        <input type="hidden" value="{{$cie[0]->id}}" class="form-control typeahead tt-query" id="id_diagnostico" name="id_diagnostico">
                                </div>
                            </div>
                            
                            
                            <div class="">
                              <?php if (isset($nro_rua) && $nro_rua != ""): ?>
                                  <div class="col-md-2" style="text-align: center; margin-top: 20px;float:right">
                                      <label>N° RUA</label>
                                      <input type="text" value="{{$nro_rua}}" name="nro_rua" class="form-control" readonly="readonly" >
                                  </div>
                              <?php else: ?>
                                  <input type="hidden" value="" name="nro_rua" class="form-control" >
                                  <input type="hidden" value="{{$nivel}}" name="nivel" class="form-control" readonly="readonly" >
                                  <input type="hidden" value="{{$beneficiario->fecnacafiliado}}" name="fecha_nacimiento_beneficiario" class="form-control" readonly="readonly" >
                              <?php endif; ?>

                            </div>
                        
                            <div class="form-group">
                                <div class="col-md-6">
                                    <a href="/all_hospitalizado_alta_by_farmacia/" class="form-control btn btn-danger" tabindex="99922">CANCELAR</a>
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