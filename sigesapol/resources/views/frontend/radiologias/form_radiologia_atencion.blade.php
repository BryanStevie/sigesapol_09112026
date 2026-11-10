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

	  //--------------------------------------------------------------------------
    function submitFrm() {
        if ($('#frmReceta').find("#nro_med_solictados").val() >= 10) {
            bootbox.confirm({
                message: "Aviso: Revise la(s) cantidad(s) prescrita(s) y dispensada(s) antes de registrar. ÓDesea Grabar?.",
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
        } else {
            bootbox.confirm({
                message: "Revise que los datos de la receta se encuentren correctos antes de registrar. ¿Desea Grabar?.",
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
    }
    //--------------------------------------------------------------------------
	  
	
	
	
    $(document).ready(function () {
		
        $('#fecha_atencion').click(function(){
            var fec_expedicion = $('#fecha_expedicion').val();
            if(fec_expedicion == ""){
                alert('ingrese la fecha de expedición.');
                $('#fecha_expedicion').focus();
            }
        });
        
        $('#fecha_expedicion').click(function(){
            var fecha_atencion = $('#fecha_atencion').val();
            if(fecha_atencion == ""){
                alert('ingrese la fecha de atención.');
                $('#fecha_atencion').focus();
            }
        });
 		
         $('#fecha_atencion').datepicker().on('hide', function (ev) {
            var fechaFinIni = $('#fecha_expedicion').val();
            var fechaIni = $('#fecha_atencion').val();
            f1 = fechaIni.split("-");
            var fechaFin = $('#fecha_expedicion').val();
            f2 = fechaFin.split("-");
            f3 = new Date();
            var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0], 0, 0, 59));
            var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0], 0, 0, 59));
            
            var diasdif= f2.getTime()-f1.getTime();
            var contdias = Math.round(diasdif/(1000*60*60*24));
            
            if (f1 > f2) {
                bootbox.alert('La Fecha de Expedición debe ser menor o igual a la fecha Dispensación.');
            }
            
            if (f1 > f3) {
                 bootbox.alert('La Fecha de Expedición debe ser menor o igual a la fecha Actual.');
            }
            
            if (contdias >= 30){
                bootbox.alert('Existe una diferencia mayor a 30 días entre la fecha de Expedición y la fecha de Dispensación, Verifique por favor.');
            }
            		
            $('#fecha_expedicion').focus();
            
        });
       	
        $('#fecha_expedicion').change(function(){
            var fec_atencion = $('#fecha_atencion').val();
            var fec_expedicion = $('#fecha_expedicion').val();
            
            f1 = fec_atencion.split("-");
            f2 = fec_expedicion.split("-");
            f3 = new Date();

            var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
            var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014
            
            var diasdif= f2.getTime()-f1.getTime();
            var contdias = Math.round(diasdif/(1000*60*60*24));

            if (f2 < f1) {
                 alert('La Fecha de Dispensación debe ser mayor o igual a la fecha Expedición.');
                 $('#fecha_expedicion').val('');
            }
            
            if (f2 > f3) {
                 alert('La Fecha de Dispensación debe ser menor o igual a la fecha Actual.');
                 $('#fecha_expedicion').val('');
            }

            if (contdias >= 30){
                alert('Existe una diferencia mayor a 30 días entre la fecha de Expedición y la fecha de Dispensación, Verifique por favor.');
            }
            
            $('#fecha_expedicion').focus();
       });
	   
    });  
	    
	function htmlEntities(str) {
      return String(str).replace('&ntilde;', 'ñ')
                        .replace('&Ntilde;', 'Ñ')
                        .replace('&amp;', '&')
                        .replace('&Ntilde;', 'Ñ')
                        .replace('&ntilde;', 'ñ')
                        .replace('&Ntilde;', 'Ñ')
                        .replace('&Agrave;', 'À')
                        .replace('&Aacute;', 'Á')  
                        .replace('&Acirc;','Â')
                        .replace('&Atilde;','Ã')   
                        .replace('&Auml;','Ä')
                        .replace('&Aring;','Å')
                        .replace('&AElig;','Æ')
                        .replace('&Ccedil;','Ç')
                        .replace('&Egrave;','È')
                        .replace('&Eacute;','É')
                        .replace('&Ecirc;', 'Ê')
                        .replace('&Euml;','Ë')
                        .replace(   '&Igrave;', 'Ì')
                        .replace('&Iacute;', 'Í'    )
                        .replace('&Icirc;', 'Î' )
                        .replace(   '&Iuml;', 'Ï')
                        .replace(   '&ETH;', 'Ð')
                        .replace(   '&Ntilde;', 'Ñ')
                        .replace(   '&Ograve;', 'Ò')
                        .replace(   '&Oacute;', 'Ó')
                        .replace('&Ocirc;', 'Ô' )
                        .replace(   '&Otilde;', 'Õ')
                        .replace('&Ouml;', 'Ö'  )
                        .replace('&Oslash;', 'Ø'    )
                        .replace(   '&Ugrave;' ,'Ù')
                        .replace(   '&Uacute;', 'Ú')
                        .replace(   '&Ucirc;', 'Û')
                        .replace(   '&Uuml;', 'Ü')
                        .replace(   '&Yacute;', 'Ý')
                        .replace('&THORN;', 'Þ' )
                        .replace(   '&szlig;', 'ß')
                        .replace(   '&agrave;', 'à')
                        .replace(   '&aacute;', 'á')
                        .replace(   '&acirc;', 'â')
                        .replace(   '&atilde;', 'ã')
                        .replace('&auml;', 'ä'  )
                        .replace(   '&aring;', 'å')
                        .replace(   '&aelig;', 'æ')
                        .replace(   '&ccedil;', 'ç')
                        .replace('&egrave;', 'è'    )
                        .replace('&eacute;', 'é'    )
                        .replace('&ecirc;', 'ê' )
                        .replace('&euml;', 'ë'  )
                        .replace(   '&igrave;', 'ì')
                        .replace('&iacute;', 'í'    )
                        .replace('&icirc;', 'î' )
                        .replace('&iuml;', 'ï'  )
                        .replace('&eth;', 'ð'   )
                        .replace(   '&ntilde;', 'ñ')
                        .replace('&ograve;','ò')
                        .replace('&oacute;','ó')
                        .replace('&ocirc;','ô')
                        .replace('&otilde;','õ')
                        .replace('&ouml;','ö')
                        .replace('&oslash;','ø')
                        .replace('&ugrave;','ù')
                        .replace('&uacute;','ú')
                        .replace('&ucirc;','û')
                        .replace('&uuml;' , 'ü')   
                        .replace('&yacute;', 'ý')  
                        .replace('&thorn;', 'þ')
                        .replace('&yuml;', 'ÿ');
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
	text-align:left!important;
}

.form-group{
    margin-bottom: 10px;
}

</style>
@endsection
@section('content')

<?php $grados = array("NINGUNO","ALUMNO","CADETE","ALFÉREZ DE SERVICIO","ALFÉREZ MAESTRO ARMERO","CAPITÁN","CAPITÁN DE SERVICIO","CAPITÁN MAESTRO ARMERO","COMANDANTE","COMANDANTE DE SERVICIO","COMANDANTE MAESTRO ARMERO","CORONEL","CORONEL DE SERVICIO","ESPECIALISTA BRIGADIER","ESPECIALISTA DE PRIMERA","ESPECIALISTA DE SEGUNDA","ESPECIALISTA DE TERCERA","ESPECIALISTA SUPERIOR","ESPECIALISTA TÉCNICO DE 1RA","ESPECIALISTA TÉCNICO DE 2DA","ESPECIALISTA TÉCNICO DE 3RA","GENERAL","GENERAL DE SERVICIO","MAYOR","MAYOR DE SERVICIO","MAYOR MAESTRO ARMERO","SUBOFICIAL BRIGADIER","SUBOFICIAL BRIGADIER DE SERVICIO","SUBOFICIAL DE PRIMERA","SUBOFICIAL DE PRIMERA DE SERVICIO","SUBOFICIAL DE SEGUNDA","SUBOFICIAL DE SEGUNDA DE SERVICIO","SUBOFICIAL DE TERCERA","SUBOFICIAL DE TERCERA DE SERVICIO","SUBOFICIAL SUPERIOR","SUBOFICIAL SUPERIOR DE SERVICIO","SUBOFICIAL TECNICO DE 1RA","SUBOFICIAL TECNICO DE 1RA DE SERVICIO","SUBOFICIAL TECNICO DE 2DA","SUBOFICIAL TECNICO DE 2DA DE SERVICIO","SUBOFICIAL TECNICO DE 3RA","SUBOFICIAL TECNICO DE 3RA DE SERVICIO","TENIENTE","TENIENTE DE SERVICIO","TENIENTE GENERAL","TENIENTE MAESTRO ARMERO");
?>

<div class="container" style="width:80%">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="row">
        <div class="panel panel-primary filterable">            
          <div class="panel-heading">
              <h3 class="panel-title">MÓDULO DE EXAMENES DE IMAGENOLOGÍA</h3>      
          </div>
          <div style=" font-size: 11px" class="panel-body">
            <form name="frm" id="frmReceta" class="form-horizontal" role="form" method="post"  action="{{ route('registrar_examen_radiologia') }}">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
		          <div class="col-sm-12 col-md-12">
                <div class="form-group">
                  <div class="col-md-4 text-center">
                    <img src="../../images/logo_sanidad.png" width="50" height="auto">
                  </div>
                  <div class="col-md-4" style="text-align: center">
                      <label><?php echo $prestacion->establecimiento ?></label><br>
                      <label style="font-size: 24px">EXAMEN DE IMAGENOLOGÍA</label><br>
                  </div>
                  <div class="col-md-4 text-center">
                    <img src="../../images/logo_sanidad.png" width="50" height="auto">
                  </div>
                </div>
              </div>
              <div class="col-sm-12 col-md-12">
                <div class="form-group">
  			         <?php 
  				          $disabled_radiologia = "";
  				          if($radiologia->ruta_radiologia==""){
  					           $disabled_radiologia = "disabled='disabled'";
  					           echo '<div class="col-sm-12">
  							               <div class="alert alert-danger" style="padding-bottom:10px;line-height:30px">
  								              El examen radiologico  aun no ha sido firmada por el m&eacute;dico
  							               </div>
  						              </div>';
  				          }
  			          ?>
                  <div class="col-sm-12">
                      <div id="msj-atenciones">
                          <div id="det-msj-atenciones"></div>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <label>TIPO ATENCION</label>
                      <?php
                        switch ($prestacion->id_tipo_atencion) {
                          case '1':$tipo_atencion = "AMBULATORIO"; break;
                          case '2':$tipo_atencion = "EMERGENCIA"; break;
                          case '3':$tipo_atencion = "HOSPITALIZACION"; break;
                          case '4':$tipo_atencion = "CENTRO QUIRURGICO"; break;
                          case '5':$tipo_atencion = "SERVICIO NUTRICIONAL"; break;
                        }
                      ?>
                      <input type="text" readonly="" value="<?php echo $tipo_atencion ?>" name="tipo_atencion" id="tipo_atencion" class="form-control" style="text-align:left">
                  </div>
                  <div class="col-md-2" id="divReceta">
  				          <label>N° ORDEN</label>
                  	<input class="form-control" readonly="" value="<?php echo $radiologia->id?>"  onKeyPress="return soloNumeros(event)" type="text" name="nro_prestacion" id="nro_prestacion" tabindex="1"  style="text-transform:uppercase;">
  					        <input type="hidden" value="<?php echo $radiologia->id_prestacion?>" name="id_prestacion" id="id_prestacion">
                  </div>
                  <div class="col-md-2" id="divReceta">
                    <label>N° PRESTACION</label>
                    <input class="form-control" readonly="" value="<?php echo $prestacion->id?>"  onKeyPress="return soloNumeros(event)" type="text" name="nro_prestacion" id="nro_prestacion" tabindex="1"  style="text-transform:uppercase;">
                    <input type="hidden" value="<?php echo $radiologia->id_establecimiento?>" name="id_establecimiento" id="id_establecimiento">
                    <input type="hidden" value="<?php echo $radiologia->id?>" name="id_radiologia" id="id_radiologia">
                  </div>
  			          <div class="col-md-2" id="divAno">
                    <label>AÑO ACTUAL</label>
                    <input class="form-control" onKeyPress="return soloNumeros(event)"type="text" value="<?php echo date("Y") ?>" id="digitos_adicionales" name="digitos_adicionales" maxlength="4" readonly="">
                  </div>
                  <div class="col-md-3">
                    <label>FEC. ORDEN </label>
                    <input type="text" class="form-control datepicker" value="<?php echo date("d-m-Y", strtotime($prestacion->fecha_atencion)) ?>" name="fecha_atencion" id="fecha_atencion" tabindex="2"  data-date-end-date="0d" autocomplete="off" readonly=""/>
                  </div>
                </div>
              </div>
              <div class="col-sm-12 col-md-12">
                <div class="form-group">
            			<div class="col-md-1">
            				<label for="tipo">TIPO DOC.</label>
            				<input type="text" readonly="" value="<?php echo $prestacion->tipo_doc_ident ?>" name="tipo_doc_ident" id="tipo_doc_ident" class="form-control">
            			</div>
            			<div class="col-md-2">
            				<label for="dni_beneficiario">NRO DOC.</label>
            				<input type="text" readonly="" value="<?php echo $prestacion->nro_doc_ident ?>" name="nro_doc_ident" id="nro_doc_ident" class="form-control"/>
            			</div>
                  <div class="col-md-4">
                    <label>APELLIDOS Y NOMBRES</label>
                    <input readonly="" type="text" class="form-control" value="<?php echo $prestacion->asegurado_paterno." ".$prestacion->asegurado_materno.", ".$prestacion->asegurado_nombre ?>" name="beneficiario" id="beneficiario" style="text-align:left">
                  </div> 
            			<div class="col-md-2">
            				<label>NRO HC</label>
            				<input type="text" class="form-control" value="<?php echo $prestacion->nro_historia ?>" id="nro_historia" name="nro_historia" tabindex="-1" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
            			</div>                  
                </div>
              </div>
              <div class="col-sm-12 col-md-12">
                <div class="form-group">
                  <div class="col-md-6">
                    <label>SERVICIOS</label>
            				<input type="text" readonly="" value="<?php echo $prestacion->servicio ?>" name="consultorio" id="consultorio" class="form-control" style="text-align:left">
                  </div>
                  <div class="col-md-6">
                    <label>MEDICO (Nombre y Apellidos)</label>
            				<input type="text" readonly="" value="<?php echo $prestacion->medico_paterno." ".$prestacion->medico_materno.", ".$prestacion->medico_nombre ?>" name="medico" id="medico" class="form-control" style="text-align:left">
            			</div>
                </div>
          		</div>
          		<div class="col-md-12" style="text-align: left; font-size: 18px">
				        DIAGNOSTICO
		          </div>
            <?php if (isset($existe_cie) && $existe_cie == 1): ?>
                <select class="form-control" name="id_diagnostico" id="id_diagnostico" required="" tabindex="10">
                    <option value="{{$cie->id}}">{{$cie->codigo}} - {{$cie->nombre}}</option>
                </select>
            <?php else: ?>
        				<div id="entregar_receta" class="row col-md-12" style="padding-top:10px; padding-bottom:10px; border: 1px solid gray; margin-bottom: 10px; margin-left: 3px">
        					<table id="tblDiagnostico" class="display" cellspacing="0" width="100%" style="margin-top:5px;">
        						<thead>
        							<tr>
        								<th width="100%"></th>
        							</tr>
      							</thead>
    								<tfoot>
    								</tfoot>
        						<tbody>
        						<?php foreach($diagnostico as $row):?>
        							<tr>
        								<td>
        									<input type="text" class="form-control input-sm" style="text-align:left;width:100%" readonly="" value="<?php echo $row->codigo." - ".$row->nombre?>" />
        								</td>
        							</tr>
        						<?php endforeach;?>
        						</tbody>
        					</table>
        				</div>
            <?php endif; ?>
                  <div class="col-md-12" style="text-align: left; font-size: 18px">
                ANALISIS CLINICO
              </div>
            <?php if (isset($existe_cie) && $existe_cie == 1): ?>
                <select class="form-control" name="id_diagnostico" id="id_diagnostico" required="" tabindex="10">
                    <option value="{{$cie->id}}">{{$cie->codigo}} - {{$cie->nombre}}</option>
                </select>
            <?php else: ?>
                <div id="entregar_receta" class="row col-md-12" style="padding-top:10px; padding-bottom:10px; border: 1px solid gray; margin-bottom: 10px; margin-left: 3px">
                  <table id="tblDiagnostico" class="display" cellspacing="0" width="100%" style="margin-top:5px;">
                    <thead>
                      <tr>
                        <th width="90%"></th>
                        <th width="10%"></th>
                      </tr>
                    </thead>
                    <tfoot>
                    </tfoot>
                    <tbody>
                    <?php foreach($procradiologia as $row):?>
                      <tr>
                        <td>
                          <input type="text" class="form-control input-sm" style="text-align:left;width:100%" readonly="" value="<?php echo $row->codigo." - ".$row->nombre?>" />
                        </td>
                      </tr>
                    <?php endforeach;?>
                    </tbody>
                  </table>
                </div>
                <div id="entregar_receta" class="row col-md-12" style="padding-top:10px; padding-bottom:10px; border: 1px solid gray; margin-bottom: 10px; margin-left: 3px">
                    <div class="col-md-12">
                    <label>OBSERVACION &nbsp;</label>
                    <input value="" type="text" name="observacion" id="observacion" class="form-control" >
                  </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                      <a href="/all_radiologia_prescritas_by_ipress" class="form-control btn btn-danger" tabindex="99922" style="text-align:center!important">CANCELAR</a>
                    </div>
                    <div class="col-md-6">
                      <!--button id="btnReg"  <?php //echo $disabled_laboratorio?> class="form-control btn btn-success" type="button" tabindex="99921"  style="text-align:center!important">GUARDAR</button-->
                      <input <?php echo $disabled_radiologia?> type="submit" value="GUARDAR" class="form-control btn btn-success" id="btnReg" style="text-align:center!important">
                    </div>
                </div>
                
            <?php endif; ?>
              </form>
            </div>  
          </div>
      </div>
    </div>
  </div>
</div>
@include('frontend.includes.footer')
@endsection
