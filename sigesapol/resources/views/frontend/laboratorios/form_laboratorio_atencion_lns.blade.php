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

</style>

<script>

  $('#idjefelaboratorio').select2(); $('#idresponsable').select2();

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

    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }
    
  //------------------------------------------------------------------
	
	$(".chosen-select").select2(
		{
			minimumInputLength: 3,
  			tags: true,
			cache: true,
			language: { inputTooShort: function () { return 'Ingresar 3 caracteres para buscar el diagnostico'; } },

		}
	);
	
	
	$('.chosen-select').on("select2:select", function (e) {
	 	if(e.params.data.id > 0){
		
		}else{
			$(this).select2('val', '');
		}
		
	 });
	
  function validarFormulario() {
    const pruebasCheckeadas = document.querySelectorAll('input[type=checkbox]:checked'); 
    if(pruebasCheckeadas.length <= 0){
      bootbox.alert("Debe seleccionar al menos una prueba.");
      return;
    }
    $("#frmOrdenLaboratorio").submit();
  }
	  
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

input[type=checkbox]
{
  /* Doble-tamaño Checkboxes */
  -ms-transform: scale(2); /* IE */
  -moz-transform: scale(2); /* FF */
  -webkit-transform: scale(2); /* Safari y Chrome */
  -o-transform: scale(2); /* Opera */
  padding: 10px;
}

.checkboxtexto
{
  /* Checkbox texto */
  font-size: 130%;
  display: inline;
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
                <h3 class="panel-title">MÓDULO DE EXAMENES DE LABORATORIO</h3>      
            </div>

            <div style=" font-size: 11px" class="panel-body">
                <form name="frmOrdenLaboratorio" id="frmOrdenLaboratorio" class="form-horizontal" role="form" method="post"  action="{{ route('registrar_examen_laboratorio_lns') }}">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" value="<?php echo $prestacion->id?>" name="id_prestacion" id="prestacion">
              <input type="hidden" value="<?php echo $id_ipress?>" name="id_ipress" id="id_ipress">
              
                <div class="col-sm-12 col-md-12">
                    <div class="form-group">
                        <div class="col-md-4 text-center">
                            <img src="../../images/logo_sanidad.png" width="50" height="auto">
                        </div>
                        <div class="col-md-4" style="text-align: center">
                            <label><?php echo $prestacion->establecimiento ?></label><br>
                            <label style="font-size: 24px">ANALISIS DE LABORATORIO</label><br>
                        </div>
                        <div class="col-md-4 text-center">
                            <img src="../../images/logo_sanidad.png" width="50" height="auto">
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-12">
                    <div class="form-group">
                        <?php 
                            $disabled_laboratorio = "";
                            if($laboratorio->ruta_laboratorio==""){
                                $disabled_laboratorio = "disabled='disabled'";
                                echo '<div class="col-sm-12">
                                            <div class="alert alert-danger" style="padding-bottom:10px;line-height:30px">
                                                EL examen de laboratorio aun no ha sido firmada por el m&eacute;dico
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
                        <div class="col-md-2">
                            <label>N° ORDEN</label>
                            <input class="form-control" readonly="" value="<?php echo $laboratorio->id?>"  onKeyPress="return soloNumeros(event)" type="text" name="id_laboratorio" id="id_laboratorio" style="text-transform:uppercase;">
                        </div>
                        <div class="col-md-2">
                            <label>FEC. ORDEN </label>
                            <input type="text" class="form-control" value="<?php echo date("d-m-Y", strtotime($prestacion->fecha_atencion)) ?>" name="fecha_atencion" id="fecha_atencion" data-date-end-date="0d" autocomplete="off" readonly=""/>
                        </div>
                        <div class="col-md-2">
                            <label>SERVICIO </label>
                            <input type="text" readonly="" value="<?php echo $prestacion->servicio ?>" name="consultorio" id="consultorio" class="form-control" style="text-align:left">
                        </div>
                        <div class="col-md-3">
                            <label>MEDICO (Nombre y Apellidos)</label>
                            <input type="text" readonly="" value="<?php echo $prestacion->medico_paterno." ".$prestacion->medico_materno.", ".$prestacion->medico_nombre ?>" name="medico" id="medico" class="form-control" style="text-align:left">
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
                        <div class="col-md-3">
                            <label>APELLIDOS Y NOMBRES</label>
                            <input readonly="" type="text" class="form-control" value="<?php echo $prestacion->asegurado_paterno." ".$prestacion->asegurado_materno.", ".$prestacion->asegurado_nombre ?>" name="beneficiario" id="beneficiario" style="text-align:left">
                        </div> 
                        <div class="col-md-2">
                            <label>NRO HC</label>
                            <input readonly="" type="text" class="form-control" value="<?php echo $prestacion->nro_historia ?>" id="nro_historia" name="nro_historia" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                        </div>
                        <div class="col-md-2">
                            <label>SEXO</label>
                            <input readonly=""type="text" class="form-control" value="<?php echo $prestacion->sexo ?>" id="nro_historia" name="nro_historia" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                        </div>
                        <?php use Carbon\Carbon;
                            //$fch=explode("/",$prestacion->fecha_nac);
                            //$tfecha=$fch[2]."-".$fch[1]."-".$fch[0];
                            //$edad = Carbon::parse($tfecha)->age;
                        ?>
                        <div class="col-md-2">
                            <label>EDAD</label>
                            <input readonly="" type="text" class="form-control" value="<?php echo $prestacion->edadpaciente ?>" id="nro_historia" name="nro_historia" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                        </div>
                    </div>
                </div>
              <!-- <div class="col-sm-12 col-md-12">
                <div class="form-group">
                  <div class="col-md-6">
                    <label>JEFE DE LABORATORIO</label>
                    <select class="form-control input-sm" name="idjefelaboratorio" id="idjefelaboratorio" tabindex="16" style="width: 100%">
                      <?php

                    //   foreach ($jefe_laboratorio as $d) {
                    //       echo '<option value="' . $d->id . '"';
                    //       if (isset($idjefelaboratorio)) {
                    //           if ($idjefelaboratorio == $d->id)
                    //               echo " selected";
                    //       }
                    //       echo '>' . $d->nombre . ' ' . $d->paterno . ' ' . $d->materno . '</option>';
                    //   }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label>PERSONAL RESPONSABLE DEL REGISTRO</label>
                    <select class="form-control input-sm" name="idresponsable" id="idresponsable" tabindex="16" style="width: 100%">
                      <?php
                    //   foreach ($user as $d) {
                    //       echo '<option value="' . $d->id . '"';
                    //       if (isset($idresponsable)) {
                    //           if ($idresponsable == $d->id)
                    //               echo " selected";
                    //       }
                    //       echo '>' . $d->name . ' ' . $d->paterno . ' ' . $d->materno . '</option>';
                    //   }
                      ?>
                    </select>
                  </div>
                </div>
              </div> -->

          		<div class="col-md-12" >
				    <span style="text-align: left; font-size: 18px">DIAGNOSTICO</span>
                    <div class="form-group">
                        <div class="col-md-12">
                        <?php if (isset($existe_cie) && $existe_cie == 1): ?>
                            <select class="form-control" name="id_diagnostico" id="id_diagnostico" required="">
                                <option value="{{$cie->id}}">{{$cie->codigo}} - {{$cie->nombre}}</option>
                            </select>
                        <?php else: ?>
                            <div id="entregar_receta" style="padding:10px; border: 1px solid gray; margin-bottom: 10px; margin-left: 3px">
                                <table id="tblDiagnostico" class="display" cellspacing="0" width="100%" style="margin-top:5px;">
                                    <thead>
                                        <tr>
                                            <th width="100%"></th>
                                        </tr>
                                    </thead>
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
                        </div>
                    </div>
                </div>

                <div class="col-md-12" >
                    <span style="text-align: left; font-size: 18px">ANALISIS CLINICO</span>
                    <div class="form-group">
                        <div class="col-md-12" >
                            <div id="entregar_receta" style="padding:10px; border: 1px solid gray; margin-bottom: 10px; margin-left: 3px">
                                <table id="tblDiagnostico" class="display" cellspacing="0" width="100%" style="">
                                    <thead>
                                    <tr>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($proclaboratorio as $row):?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="prueba[]" checked value="<?php echo $row->id_procedimiento ?>"><span class="checkboxtexto">&nbsp;&nbsp;&nbsp;<?php echo $row->codigo." - ".$row->nombre?></span><br><br/>
                                        </td>
                                    </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="observacion">OBSERVACION</label>
                            <input type="text" name="observacion" id="observacion" class="form-control input-sm text-uppercase" autocomplete="off" >
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <a href="/all_laboratorio_prescritos_by_ipress" class="form-control btn btn-danger" tabindex="99922" style="text-align:center!important">CANCELAR</a>
                        </div>
                        <div class="col-sm-6">
                            <input onclick="validarFormulario();" type="button" value="GUARDAR" class="form-control btn btn-success" id="btnReg" style="text-align:center!important">
                        </div>
                    </div>
                </div>
                </form>
            </div>  
            </div> <!-- .panel -->
      </div><!-- .row -->
    </div><!-- .col -->
  </div><!-- .row -->
</div><!-- .container -->
@include('frontend.includes.footer')
@endsection
