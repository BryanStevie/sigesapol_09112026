@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}

<style>

.modal-dialog {
    width: 90%;
  }

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
    var observacion = $('#observacion').val();
    var idresponsable = $('#idresponsable').val();
    var msg = "";
    var sw = true;

    const pruebasCheckeadas = document.querySelectorAll('input[type=checkbox]:checked'); 
    total_proc= {{count($proclaboratorio)}};
    
    if(pruebasCheckeadas.length < total_proc){
      if(observacion==""){
        msg+="Debe ingresar una observación.<br>";
        sw = false;
      }
    }

    if(idresponsable==""){
      msg+="Debe seleccionar un responsable.<br>";
      sw = false;
    }

    if (sw == false) {
        bootbox.alert(msg);
        $('#btnReg').prop("disabled",false);
        return sw;
    } else {
        submitFrm();
    }
    return false;

  }

  function submitFrm() {
        bootbox.confirm({
            message: "Se registrará todos los examenes ingresados, ¿Está seguro de continuar?",
            buttons: {
                confirm: {
                    label: 'Si',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result == true) {
                    document.frmOrdenLaboratorio.submit();
                }
            }
        });
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
            <form name="frmOrdenLaboratorio" id="frmOrdenLaboratorio" class="form-horizontal" method="post"  action="{{ route('registrar_examen_laboratorio') }}" autocomplete="off">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" value="<?php echo $prestacion->id?>" name="id_prestacion" id="prestacion">
              <input type="hidden" value="<?php echo $tipo_examen?>" name="tipo_examen" id="tipo_examen">
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
                    <input class="form-control" readonly="" value="<?php echo $laboratorio->id?>"  onKeyPress="return soloNumeros(event)" type="text" name="id_laboratorio" id="id_laboratorio" tabindex="1"  style="text-transform:uppercase;">
                  </div>
                  <div class="col-md-2">
                    <label>FEC. ORDEN </label>
                    <input type="text" class="form-control datepicker" value="<?php echo date("d-m-Y", strtotime($prestacion->fecha_atencion)) ?>" name="fecha_atencion" id="fecha_atencion" tabindex="2"  data-date-end-date="0d" autocomplete="off" readonly=""/>
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
                  <div class="col-md-5">
                    <label>APELLIDOS Y NOMBRES</label>
                    <input readonly="" type="text" class="form-control" value="<?php echo $prestacion->asegurado_paterno." ".$prestacion->asegurado_materno.", ".$prestacion->asegurado_nombre ?>" name="beneficiario" id="beneficiario" style="text-align:left">
                  </div> 
                        <div class="col-md-2">
                            <label>NRO HC</label>
                            <input readonly="" type="text" class="form-control" value="<?php echo $prestacion->nro_historia ?>" id="nro_historia" name="nro_historia" tabindex="-1" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                        </div>
                  <div class="col-md-1">
                    <label>SEXO</label>
                    <input readonly=""type="text" class="form-control" value="<?php echo $prestacion->sexo ?>" id="nro_historia" name="nro_historia" tabindex="-1" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                  </div>
                  <?php use Carbon\Carbon;
                        //$fch=explode("/",$prestacion->fecha_nac);
                        //$tfecha=$fch[2]."-".$fch[1]."-".$fch[0];
                        //$edad = Carbon::parse($tfecha)->age;
                  ?>
                  <div class="col-md-1">
                    <label>EDAD</label>
                    <input readonly="" type="text" class="form-control" value="<?php echo $prestacion->edadpaciente ?>" id="nro_historia" name="nro_historia" tabindex="-1" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();">
                  </div>
                </div>
              </div>
              <div class="col-sm-12 col-md-12">
                <div class="form-group">
                  <div class="col-md-6">
                    <label>JEFE DE LABORATORIO</label>
                    <select class="form-control input-sm" name="idjefelaboratorio" id="idjefelaboratorio" tabindex="16" style="width: 100%">
                      <?php

                      foreach ($jefe_laboratorio as $d) {
                          echo '<option value="' . $d->id . '"';
                          if (isset($idjefelaboratorio)) {
                              if ($idjefelaboratorio == $d->id)
                                  echo " selected";
                          }
                          echo '>' . $d->nombre . ' ' . $d->paterno . ' ' . $d->materno . '</option>';
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label>PERSONAL RESPONSABLE DEL REGISTRO</label>
                    <select class="form-control input-sm" name="idresponsable" id="idresponsable" tabindex="16" style="width: 100%">
                      @if(count($user)==0)
                        <option value="">--Seleccionar--</option>
                      @else
                        <?php
                        foreach ($user as $d) {
                            echo '<option value="' . $d->id . '"';
                            if (isset($idresponsable)) {
                                if ($idresponsable == $d->id)
                                    echo " selected";
                            }
                            echo '>' . $d->name . ' ' . $d->paterno . ' ' . $d->materno . '</option>';
                        }
                        ?>
                      @endif
                    </select>
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
                        <th width="100%"></th>
                      </tr>
                    </thead>
                    <tfoot>
                    </tfoot>
                    <tbody>
                      <tr>
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
                      </tr>
                    <tr>
                      <td>
                        <?php if($tipo_examen==1): ?>
                        <fieldset>
                          <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-heading">
                              <div class="panel-title text-center">
                                <b>INFORMACIÓN DE BIOQUIMICA</b>
                              </div>
                            </div>
                            <div class="panel-body" id="collapseprestacionespaciente">
                              <div class="form-group">
                                <div class="col-md-2">
                                    <label>GLUCOSA &nbsp;</label>
                                    <input value="" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" type="text" name="glucosa1" id="glucosa1" class="form-control" >
                                </div>
                                <div class="col-md-2">
                                    <label>UREA</label>
                                    <input value="" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" type="text" name="urea" id="urea" class="form-control" >
                                </div>                        
                                <div class="col-md-2">
                                    <label>CREATININA &nbsp;</label>
                                    <input value="" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" type="text" name="creatinina" id="creatinina" class="form-control" >
                                </div>                        
                                <div class="col-md-2">
                                    <label>COLESTEROL &nbsp;</label>
                                    <input value="" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" type="text" name="colesterol" id="colesterol" class="form-control" >
                                </div>
                                <div class="col-md-2">
                                    <label>TRIGLICÉRIDOS &nbsp;</label>
                                    <input value="" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" type="text" name="trigleceridos" id="trigleceridos" class="form-control" >
                                </div>
                                <div class="col-md-2">
                                    <label>HDL - COLESTEROL &nbsp;</label>
                                    <input value="" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" type="text" name="hdl_colesterol" id="hdl_colesterol" class="form-control" >
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-md-2">
                                    <label>LDL - COLESTEROL &nbsp;</label>
                                    <input value="" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" type="text" name="ldl_colesterol" id="ldl_colesterol" class="form-control" >
                                </div>
                                <div class="col-md-2">
                                    <label>AMILASA</label>
                                    <input value="" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" type="text" name="amilasa" id="amilasa" class="form-control" >
                                </div>                        
                                <div class="col-md-2">
                                    <label>ACIDO URICO &nbsp;</label>
                                    <input value="" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" type="text" name="acido_urico" id="acido_urico" class="form-control" >
                                </div>                        
                                <div class="col-md-2">
                                    <label>PROTEINAS TOTALES &nbsp;</label>
                                    <input value="" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" type="text" name="proteinas_totales" id="proteinas_totales" class="form-control" >
                                </div>
                                <div class="col-md-2">
                                    <label>ALBÚMINA &nbsp;</label>
                                    <input value="" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" type="text" name="albumina1" id="albumina1" class="form-control" >
                                </div>
                                <div class="col-md-2">
                                    <label>GLOBULINAS &nbsp;</label>
                                    <input value="" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" type="text" name="globulinas" id="globulinas" class="form-control" >
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-md-2">
                                    <label>TGO &nbsp;</label>
                                    <input value="" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" type="text" name="tgo" id="tgo" class="form-control" >
                                </div>
                                <div class="col-md-2">
                                    <label>TGP</label>
                                    <input value="" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" type="text" name="tgp" id="tgp" class="form-control" >
                                </div>                        
                                <div class="col-md-2">
                                    <label>BILIRRUBINA TOTAL &nbsp;</label>
                                    <input value="" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" type="text" name="bilirrubina_total" id="bilirrubina_total" class="form-control" >
                                </div>                        
                                <div class="col-md-2">
                                    <label>BILIRRUBINA DIRECTA &nbsp;</label>
                                    <input value="" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" type="text" name="bilirrubina_directa" id="bilirrubina_directa" class="form-control" >
                                </div>
                                <div class="col-md-2">
                                    <label>BILIRRUBINA INDIRECTA &nbsp;</label>
                                    <input value="" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" type="text" name="bilirrubina_indirecta" id="bilirrubina_indirecta" class="form-control" >
                                </div>
                                <div class="col-md-2">
                                    <label>FOSFATASA ALCALINA &nbsp;</label>
                                    <input value="" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" type="text" name="fosfatasa_alcalina" id="fosfatasa_alcalina" class="form-control" >
                                </div>
                              </div>
                            </div>
                          </div>
                        </fieldset>
                  <?php endif;
                        
                        if($tipo_examen==7): ?>
                        <fieldset>
                          <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-heading">
                              <div class="panel-title text-center">
                                <b>INFORMACIÓN DE HEMATOLOGIA</b>
                              </div>
                            </div>
                            <div class="panel-body" id="collapseprestacionespaciente">
                              <div class="form-group">
                                <div class="col-md-2">
                                    <label>HEMATIES &nbsp;</label>
                                    <input value="" type="text" name="hematies1" id="hematies1" class="form-control" >
                                </div>
                                <div class="col-md-2">
                                    <label>HEMOGLOBINA</label>
                                    <input value="" type="text" name="hemoglobina" id="hemoglobina" class="form-control" >
                                </div>                        
                                <div class="col-md-2">
                                    <label>HEMATOCRITO &nbsp;</label>
                                    <input value="" type="text" name="hematocrito" id="hematocrito" class="form-control" >
                                </div>                        
                                <div class="col-md-2">
                                    <label>VSG &nbsp;</label>
                                    <input value="" type="text" name="vsg" id="vsg" class="form-control" >
                                </div>
                                <div class="col-md-2">
                                    <label>T. DE COAGULACION &nbsp;</label>
                                    <input value="" type="text" name="t_coagulacion" id="t_coagulacion" class="form-control" >
                                </div>
                                <div class="col-md-2">
                                    <label>T. DE SANGRIA &nbsp;</label>
                                    <input value="" type="text" name="t_sangria" id="t_sangria" class="form-control" >
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-md-2">
                                    <label>T. DE PROTOMBINA &nbsp;</label>
                                    <input value="" type="text" name="t_protombina" id="t_protombina" class="form-control" >
                                </div>
                                <div class="col-md-2">
                                    <label>LEUCOCITOS</label>
                                    <input value="" type="text" name="leucocitos" id="leucocitos" class="form-control" >
                                </div>                        
                                <div class="col-md-2">
                                    <label>NEUTROFILOS &nbsp;</label>
                                    <input value="" type="text" name="neutrofilos" id="neutrofilos" class="form-control" >
                                </div>                        
                                <div class="col-md-2">
                                    <label>BASOFILOS &nbsp;</label>
                                    <input value="" type="text" name="basofilos" id="basofilos" class="form-control" >
                                </div>
                                <div class="col-md-2">
                                    <label>MONOCITOS &nbsp;</label>
                                    <input value="" type="text" name="monocitos" id="monocitos" class="form-control" >
                                </div>
                                <div class="col-md-2">
                                    <label>LINFOCITOS &nbsp;</label>
                                    <input value="" type="text" name="linfocitos" id="linfocitos" class="form-control" >
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-md-2">
                                    <label>PLAQUETAS &nbsp;</label>
                                    <input value="" type="text" name="plaquetas" id="plaquetas" class="form-control" >
                                </div>
                                <div class="col-md-2">
                                    <label>MIELCITOS</label>
                                    <input value="" type="text" name="mielcitos" id="mielcitos" class="form-control" >
                                </div>                        
                                <div class="col-md-2">
                                    <label>METAMIELOCITOS &nbsp;</label>
                                    <input value="" type="text" name="metamielcitos" id="metamielcitos" class="form-control" >
                                </div>                        
                                <div class="col-md-2">
                                    <label>ABASTONADOS &nbsp;</label>
                                    <input value="" type="text" name="abastonados" id="abastonados" class="form-control" >
                                </div>
                                <div class="col-md-2">
                                    <label>SEGMENTADOS &nbsp;</label>
                                    <input value="" type="text" name="segmentados" id="segmentados" class="form-control" >
                                </div>
                              </div>
                            </div>
                          </div>
                        </fieldset>
                  <?php endif;
                        if($tipo_examen==9): ?>
                          <div style="font-size: 11px" class="panel-body" >
                            <div class="panel panel-info panel-bordered panel-sm panel-body">
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>INFORMACIÓN DE MICROBIOLOGIA</b>
                                    </div>
                                  </div>
                                  <div class="panel-body" id="collapseprestacionespaciente">
                                    <div class="form-group">
                                      <div class="col-md-3">
                                          <label>MUESTRA &nbsp;</label>
                                          <input value="" type="text" name="muestra1" id="muestra1" class="form-control" >
                                      </div>
                                      <div class="col-md-3">
                                          <label>ASPECTO</label>
                                          <input value="" type="text" name="aspecto1" id="aspecto1" class="form-control" >
                                      </div>                        
                                      <div class="col-md-3">
                                          <label>CONSISTENCIA &nbsp;</label>
                                          <input value="" type="text" name="consistencia" id="consistencia" class="form-control" >
                                      </div>                        
                                      <div class="col-md-3">
                                          <label>COLOR &nbsp;</label>
                                          <input value="" type="text" name="color1" id="color1" class="form-control" >
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-md-3">
                                          <label>LEUCOCITOS &nbsp;</label>
                                          <input value="" type="text" name="lecucitos1" id="leucocitos1" class="form-control" >
                                      </div>
                                      <div class="col-md-3">
                                          <label>HEMATIES  &nbsp;</label>
                                          <input value="" type="text" name="hematies2" id="hematies2" class="form-control" >
                                      </div>                        
                                      <div class="col-md-3">
                                          <label>LEVADURAS &nbsp;</label>
                                          <input value="" type="text" name="levaduras" id="levaduras" class="form-control" >
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>EXAMEN DE DIFERENCIACION CELULAR</b>
                                    </div>
                                  </div>
                                  <div class="panel-body" id="collapseprestacionespaciente">
                                    <div class="form-group">
                                      <div class="col-md-3">
                                          <label>PARASITOS &nbsp;</label>
                                          <input value="" type="text" name="parasitos" id="parasitos" class="form-control" >
                                      </div>
                                      <div class="col-md-3">
                                          <label>POLIMORFONUCLEARES &nbsp;</label>
                                          <input value="" type="text" name="polimorfonucleares" id="polimorfonucleares" class="form-control" >
                                      </div>                        
                                      <div class="col-md-3">
                                          <label>MONONUCLEARES &nbsp;</label>
                                          <input value="" type="text" name="mononucleares" id="mononucleares" class="form-control" >
                                      </div>                        
                                      <div class="col-md-3">
                                          <label>OBSERVACIONES &nbsp;</label>
                                          <input value="" type="text" name="observaciones1" id="observaciones1" class="form-control" >
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>                         
                            </div>
                          </div>
                          <div style="font-size: 11px" class="panel-body" >
                            <div class="panel panel-info panel-bordered panel-sm panel-body">
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>EXÁMEN COMPLETO DE ORINA</b>
                                    </div>
                                  </div>
                                  <div class="panel-body" id="collapseprestacionespaciente">
                                    <div class="form-group">
                                      <div class="col-md-3">
                                          <label>COLOR &nbsp;</label>
                                          <input value="" type="text" name="color2" id="color2" class="form-control" >
                                      </div>
                                      <div class="col-md-3">
                                          <label>ASPECTO &nbsp;</label>
                                          <input value="" type="text" name="aspecto2" id="aspecto2" class="form-control" >
                                      </div>
                                      <div class="col-md-3">
                                          <label>DENSIDAD &nbsp;</label>
                                          <input value="" type="text" name="densidad" id="densidad" class="form-control" >
                                      </div>
                                      <div class="col-md-3">
                                          <label>MUESTRA &nbsp;</label>
                                          <input value="" type="text" name="muestra2" id="muestra2" class="form-control" >
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>SEDIMENTOS</b>
                                    </div>
                                  </div>
                                  <div class="panel-body" id="collapseprestacionespaciente">
                                    <div class="form-group">
                                      <div class="col-md-3">
                                          <label>HEMATIES &nbsp;</label>
                                          <input value="" type="text" name="hematies3" id="hematies3" class="form-control" >
                                      </div>
                                      <div class="col-md-3">
                                          <label>LEUCOCITOS &nbsp;</label>
                                          <input value="" type="text" name="leucocitos2" id="lecucitos2" class="form-control" >
                                      </div>
                                      <div class="col-md-3">
                                          <label>CELULAS EPITELIALES &nbsp;</label>
                                          <input value="" type="text" name="celulas_epiteliales" id="celulas_epiteliales" class="form-control" >
                                      </div>
                                      <div class="col-md-3">
                                          <label>CRISTALES &nbsp;</label>
                                          <input value="" type="text" name="cristales" id="cristales" class="form-control" >
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-md-3">
                                          <label>CILINDROS &nbsp;</label>
                                          <input value="" type="text" name="cilindros" id="cilindros" class="form-control" >
                                      </div>
                                      <div class="col-md-3">
                                          <label>GERMENES &nbsp;</label>
                                          <input value="" type="text" name="germenes" id="germenes" class="form-control" >
                                      </div>
                                      <div class="col-md-3">
                                          <label>PIOCITOS &nbsp;</label>
                                          <input value="" type="text" name="piocitos" id="piocitos" class="form-control" >
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>EXAMEN QUIMICO</b>
                                    </div>
                                  </div>
                                  <div class="panel-body" id="collapseprestacionespaciente">
                                    <div class="form-group">
                                      <div class="col-md-2">
                                          <label>ALBUMINA &nbsp;</label>
                                          <input value="" type="text" name="albumina2" id="albumina2" class="form-control" >
                                      </div>
                                      <div class="col-md-2">
                                          <label>GLUCOSA &nbsp;</label>
                                          <input value="" type="text" name="glucosa2" id="glucosa2" class="form-control" >
                                      </div>
                                      <div class="col-md-2">
                                          <label>UROBILINA &nbsp;</label>
                                          <input value="" type="text" name="urobilina" id="urobilina" class="form-control" >
                                      </div>
                                      <div class="col-md-2">
                                          <label>PIGMENTOS BILIARES &nbsp;</label>
                                          <input value="" type="text" name="pigmentos_biliares" id="pigmentos_biliares" class="form-control" >
                                      </div>
                                      <div class="col-md-2">
                                          <label>CUERPOS CETONICOS &nbsp;</label>
                                          <input value="" type="text" name="cuerpos_cetonicos" id="cuerpos_cetonicos" class="form-control" >
                                      </div>
                                      <div class="col-md-2">
                                          <label>AC ASCORBICO &nbsp;</label>
                                          <input value="" type="text" name="ac_ascorbico" id="ac_ascorbico" class="form-control" >
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-md-3">
                                          <label>NITRITOS &nbsp;</label>
                                          <input value="" type="text" name="nitritos" id="nitritos" class="form-control" >
                                      </div>
                                      <div class="col-md-3">
                                          <label>THEVENON &nbsp;</label>
                                          <input value="" type="text" name="thevenon" id="thevenon" class="form-control" >
                                      </div>
                                      <div class="col-md-6">
                                          <label>OBSERVACIONES &nbsp;</label>
                                          <input value="" type="text" name="observaciones2" id="observaciones2" class="form-control" >
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                            </div>
                          </div>
                  <?php endif;
                        if($tipo_examen==4): ?>
                          <fieldset>
                            <div class="panel panel-info panel-bordered panel-sm">
                              <div class="panel-heading">
                                <div class="panel-title text-center">
                                  <b>INFORMACIÓN DE INMUNOSEROLOGIA</b>
                                </div>
                              </div>
                              <div class="panel-body" id="collapseprestacionespaciente">
                                <div class="form-group">
                                  <div class="col-md-2">
                                      <label>AGLUTINACIONES FEBRILES &nbsp;</label>
                                      <input value="" type="text" name="aglutinaciones_febriles" id="aglutinaciones_febriles" class="form-control" >
                                  </div>
                                  <div class="col-md-2">
                                      <label>TIFICO "H"</label>
                                      <input value="" type="text" name="tifico_h" id="tifico_h" class="form-control" >
                                  </div>                        
                                  <div class="col-md-2">
                                      <label>TIFICO "O" &nbsp;</label>
                                      <input value="" type="text" name="tifico_o" id="tifico_o" class="form-control" >
                                  </div>                        
                                  <div class="col-md-2">
                                      <label>PARATIFICO "A" &nbsp;</label>
                                      <input value="" type="text" name="paratifico_a" id="paratifico_a" class="form-control" >
                                  </div>
                                  <div class="col-md-2">
                                      <label>PARATIFICO "B" &nbsp;</label>
                                      <input value="" type="text" name="paratifico_b" id="paratifico_b" class="form-control" >
                                  </div>
                                  <div class="col-md-2">
                                      <label>BRUCELLAS PLACA &nbsp;</label>
                                      <input value="" type="text" name="brucellas_placa" id="brucellas_placa" class="form-control" >
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-md-2">
                                      <label>VDRL o RPR &nbsp;</label>
                                      <input value="" type="text" name="vdrl_rpr" id="vdrl_rpr" class="form-control" >
                                  </div>
                                  <div class="col-md-2">
                                      <label>PROTEINA "C" REACTIVA </label>
                                      <input value="" type="text" name="proteina_c_reactiva" id="proteina_c_reactiva" class="form-control" >
                                  </div>                        
                                  <div class="col-md-2">
                                      <label>LATEX REUMATOIDÉO &nbsp;</label>
                                      <input value="" type="text" name="latex_reumatoideo" id="latex_reumatoideo" class="form-control" >
                                  </div>                        
                                  <div class="col-md-2">
                                      <label>ANTIESTREPTOLISINA &nbsp;</label>
                                      <input value="" type="text" name="antiestreptolisina" id="antiestreptolisina" class="form-control" >
                                  </div>
                                  <div class="col-md-2">
                                      <label>ROSA DE BANGALS &nbsp;</label>
                                      <input value="" type="text" name="rosa_bangals" id="rosa_bangals" class="form-control" >
                                  </div>
                                  <div class="col-md-2">
                                      <label>PRUEBA EN TUBO &nbsp;</label>
                                      <input value="" type="text" name="prueba_tubo" id="prueba_tubo" class="form-control" >
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-md-2">
                                      <label>MERCAPTOETANOL &nbsp;</label>
                                      <input value="" type="text" name="mercaptoetanol" id="mercaptoetanol" class="form-control" >
                                  </div>
                                </div>
                              </div>
                            </div>
                          </fieldset>
                          <fieldset>
                            <div class="panel panel-info panel-bordered panel-sm">
                              <div class="panel-heading">
                                <div class="panel-title text-center">
                                  <b>INFORMACIÓN DE SEROLOGIA</b>
                                </div>
                              </div>
                              <div class="panel-body" id="collapseprestacionespaciente">
                                <div class="form-group">
                                  <div class="col-md-2">
                                      <label>HBcTOTAL &nbsp;</label>
                                      <input value="" type="text" name="hbctotal" id="hbctotal" class="form-control" >
                                  </div>
                                  <div class="col-md-2">
                                      <label>HAVgM</label>
                                      <input value="" type="text" name="havgm" id="havgm" class="form-control" >
                                  </div>                        
                                  <div class="col-md-2">
                                      <label>HB c IgM &nbsp;</label>
                                      <input value="" type="text" name="hbcigm" id="hbcigm" class="form-control" >
                                  </div>                        
                                  <div class="col-md-2">
                                      <label>AgHBs &nbsp;</label>
                                      <input value="" type="text" name="aghbs" id="aghbs" class="form-control" >
                                  </div>
                                  <div class="col-md-2">
                                      <label>HCV &nbsp;</label>
                                      <input value="" type="text" name="hcv" id="hcv" class="form-control" >
                                  </div>
                                  <div class="col-md-2">
                                      <label>HIV &nbsp;</label>
                                      <input value="" type="text" name="hiv" id="hiv" class="form-control" >
                                  </div>
                                </div>
                              </div>
                            </div>
                          </fieldset>
                  <?php endif;
                        if($tipo_examen==2): ?>
                  <?php endif;
                        if($tipo_examen==3): ?>
                          
                  <?php endif;
                        if($tipo_examen==5): ?>
                  <?php endif;
                        if($tipo_examen==6): ?>
                  <?php endif;
                  ?>
                  <?php endif;
                        if($tipo_examen==9): ?>
                  <?php endif;
                        if($tipo_examen==10): ?>
                          
                  <?php endif;
                        if($tipo_examen==13): ?>
                  <?php endif;
                  ?>
                      </td>
                    </tr>
                    </tbody>
                  </table>

                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="observacion">OBSERVACION</label>
                            <input type="text" name="observacion" id="observacion" class="form-control input-sm text-uppercase" autocomplete="off" >
                        </div>
                    </div>
                </div>
                <div class="form-group">
                  <div class="col-md-6">
                    <a href="/all_laboratorio_prescritos_by_ipress" class="form-control btn btn-danger" tabindex="99922" style="text-align:center!important">CANCELAR</a>
                  </div>

                  <div class="col-md-6">
                    <!--button id="btnReg"  <?php //echo $disabled_laboratorio?> class="form-control btn btn-success" type="button" tabindex="99921"  style="text-align:center!important">GUARDAR</button-->
                    <a <?php echo $disabled_laboratorio?> class="form-control btn btn-success" onclick="validarFormulario()" style="text-align:center!important">GUARDAR</a>
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
