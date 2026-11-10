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

input.id_pres_proc {
    transform: scale(1.0) !important;
    margin-right: 4px;
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


.swal2-icon.swal2-warning {
    font-size: 15px !important; 
}

.table-borderless tbody > tr > td,.table-borderless tbody > tr > th{
  
  padding: 2px 8px !important;
  border: 0px solid;
}
  .table-borderless{
         margin-bottom: 0px !important;
  }

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

    .contenedor-datos-generales input,
    .contenedor-especialista input,
    .contenedor-paciente input,
    .contenedor-informacion-atencion input,
    .contenedor-referencia input{
        text-align: center !important;
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

    .btn-toggle {
        margin: 0 4rem;
        padding: 0;
        position: relative;
        border: none;
        height: 1.5rem;
        width: 3rem;
        border-radius: 1.5rem;
        color: #6b7381;
        background: #bdc1c8;
    }
    .btn-toggle:focus,
    .btn-toggle.focus,
    .btn-toggle:focus.active,
    .btn-toggle.focus.active {
        outline: none;
    }
    .btn-toggle:before,
    .btn-toggle:after {
        line-height: 1.5rem;
        width: 4rem;
        text-align: center;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        position: absolute;
        bottom: 0;
        transition: opacity 0.25s;
    }
    .btn-toggle:before {
        content: 'NO';
        left: -4rem;
    }
    .btn-toggle:after {
        content: 'SI';
        right: -4rem;
        opacity: 0.5;
    }
    .btn-toggle>.handle {
        position: absolute;
        top: 0.1875rem;
        left: 0.1875rem;
        width: 1.125rem;
        height: 1.125rem;
        border-radius: 1.125rem;
        background: #fff;
        transition: left 0.25s;
    }
    .btn-toggle.active {
        transition: background-color 0.25s;
    }
    .btn-toggle.active>.handle {
        left: 1.6875rem;
        transition: left 0.25s;
    }
    .btn-toggle.active:before {
        opacity: 0.5;
    }
    .btn-toggle.active:after {
        opacity: 1;
    }
    .btn-toggle.btn-sm:before,
    .btn-toggle.btn-sm:after {
        line-height: -0.5rem;
        color: #fff;
        letter-spacing: 0.75px;
        left: 0.4125rem;
        width: 2.325rem;
    }
    .btn-toggle.btn-sm:before {
        text-align: right;
    }
    .btn-toggle.btn-sm:after {
        text-align: left;
        opacity: 0;
    }
    .btn-toggle.btn-sm.active:before {
        opacity: 0;
    }
    .btn-toggle.btn-sm.active:after {
        opacity: 1;
    }
    .btn-toggle.btn-xs:before,
    .btn-toggle.btn-xs:after {
        display: none;
    }
    .btn-toggle:before,
    .btn-toggle:after {
        color: #6b7381;
    }
    .btn-toggle.active {
        background-color: #29b5a8;
    }
    .btn-toggle.btn-lg {
        margin: 0 5rem;
        padding: 0;
        position: relative;
        border: none;
        height: 2.5rem;
        width: 5rem;
        border-radius: 2.5rem;
    }
    .btn-toggle.btn-lg:focus,
    .btn-toggle.btn-lg.focus,
    .btn-toggle.btn-lg:focus.active,
    .btn-toggle.btn-lg.focus.active {
        outline: none;
    }
    .btn-toggle.btn-lg:before,
    .btn-toggle.btn-lg:after {
        line-height: 2.5rem;
        width: 5rem;
        text-align: center;
        font-weight: 600;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        position: absolute;
        bottom: 0;
        transition: opacity 0.25s;
    }
    .btn-toggle.btn-lg:before {
        content: 'NO';
        left: -5rem;
    }
    .btn-toggle.btn-lg:after {
        content: 'SI';
        right: -5rem;
        opacity: 0.5;
    }
    .btn-toggle.btn-lg>.handle {
        position: absolute;
        top: 0.3125rem;
        left: 0.3125rem;
        width: 1.875rem;
        height: 1.875rem;
        border-radius: 1.875rem;
        background: #fff;
        transition: left 0.25s;
    }
    .btn-toggle.btn-lg.active {
        transition: background-color 0.25s;
    }
    .btn-toggle.btn-lg.active>.handle {
        left: 2.8125rem;
        transition: left 0.25s;
    }
    .btn-toggle.btn-lg.active:before {
        opacity: 0.5;
    }
    .btn-toggle.btn-lg.active:after {
        opacity: 1;
    }
    .btn-toggle.btn-lg.btn-sm:before,
    .btn-toggle.btn-lg.btn-sm:after {
        line-height: 0.5rem;
        color: #fff;
        letter-spacing: 0.75px;
        left: 0.6875rem;
        width: 3.875rem;
    }
    .btn-toggle.btn-lg.btn-sm:before {
        text-align: right;
    }
    .btn-toggle.btn-lg.btn-sm:after {
        text-align: left;
        opacity: 0;
    }
    .btn-toggle.btn-lg.btn-sm.active:before {
        opacity: 0;
    }
    .btn-toggle.btn-lg.btn-sm.active:after {
        opacity: 1;
    }
    .btn-toggle.btn-lg.btn-xs:before,
    .btn-toggle.btn-lg.btn-xs:after {
        display: none;
    }
    .btn-toggle.btn-sm {
        margin: 0 0.5rem;
        padding: 0;
        position: relative;
        border: none;
        height: 1.5rem;
        width: 3rem;
        border-radius: 1.5rem;
    }
    .btn-toggle.btn-sm:focus,
    .btn-toggle.btn-sm.focus,
    .btn-toggle.btn-sm:focus.active,
    .btn-toggle.btn-sm.focus.active {
        outline: none;
    }
    .btn-toggle.btn-sm:before,
    .btn-toggle.btn-sm:after {
        line-height: 1.5rem;
        width: 0.5rem;
        text-align: center;
        font-weight: 600;
        font-size: 0.55rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        position: absolute;
        bottom: 0;
        transition: opacity 0.25s;
    }
    .btn-toggle.btn-sm:before {
        content: 'NO';
        left: -0.5rem;
    }
    .btn-toggle.btn-sm:after {
        content: 'SI';
        right: -0.5rem;
        opacity: 0.5;
    }
    .btn-toggle.btn-sm>.handle {
        position: absolute;
        top: 0.1875rem;
        left: 0.1875rem;
        width: 1.125rem;
        height: 1.125rem;
        border-radius: 1.125rem;
        background: #fff;
        transition: left 0.25s;
    }
    .btn-toggle.btn-sm.active {
        transition: background-color 0.25s;
    }
    .btn-toggle.btn-sm.active>.handle {
        left: 1.6875rem;
        transition: left 0.25s;
    }
    .btn-toggle.btn-sm.active:before {
        opacity: 0.5;
    }
    .btn-toggle.btn-sm.active:after {
        opacity: 1;
    }
    .btn-toggle.btn-sm.btn-sm:before,
    .btn-toggle.btn-sm.btn-sm:after {
        line-height: -0.5rem;
        color: #fff;
        letter-spacing: 0.75px;
        left: 0.4125rem;
        width: 2.325rem;
    }
    .btn-toggle.btn-sm.btn-sm:before {
        text-align: right;
    }
    .btn-toggle.btn-sm.btn-sm:after {
        text-align: left;
        opacity: 0;
    }
    .btn-toggle.btn-sm.btn-sm.active:before {
        opacity: 0;
    }
    .btn-toggle.btn-sm.btn-sm.active:after {
        opacity: 1;
    }
    .btn-toggle.btn-sm.btn-xs:before,
    .btn-toggle.btn-sm.btn-xs:after {
        display: none;
    }
    .btn-toggle.btn-xs {
        margin: 0 0;
        padding: 0;
        position: relative;
        border: none;
        height: 1rem;
        width: 2rem;
        border-radius: 1rem;
    }
    .btn-toggle.btn-xs:focus,
    .btn-toggle.btn-xs.focus,
    .btn-toggle.btn-xs:focus.active,
    .btn-toggle.btn-xs.focus.active {
        outline: none;
    }
    .btn-toggle.btn-xs:before,
    .btn-toggle.btn-xs:after {
        line-height: 1rem;
        width: 0;
        text-align: center;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        position: absolute;
        bottom: 0;
        transition: opacity 0.25s;
    }
    .btn-toggle.btn-xs:before {
        content: 'NO';
        left: 0;
    }
    .btn-toggle.btn-xs:after {
        content: 'SI';
        right: 0;
        opacity: 0.5;
    }
    .btn-toggle.btn-xs>.handle {
        position: absolute;
        top: 0.125rem;
        left: 0.125rem;
        width: 0.75rem;
        height: 0.75rem;
        border-radius: 0.75rem;
        background: #fff;
        transition: left 0.25s;
    }
    .btn-toggle.btn-xs.active {
        transition: background-color 0.25s;
    }
    .btn-toggle.btn-xs.active>.handle {
        left: 1.125rem;
        transition: left 0.25s;
    }
    .btn-toggle.btn-xs.active:before {
        opacity: 0.5;
    }
    .btn-toggle.btn-xs.active:after {
        opacity: 1;
    }
    .btn-toggle.btn-xs.btn-sm:before,
    .btn-toggle.btn-xs.btn-sm:after {
        line-height: -1rem;
        color: #fff;
        letter-spacing: 0.75px;
        left: 0.275rem;
        width: 1.55rem;
    }
    .btn-toggle.btn-xs.btn-sm:before {
        text-align: right;
    }
    .btn-toggle.btn-xs.btn-sm:after {
        text-align: left;
        opacity: 0;
    }
    .btn-toggle.btn-xs.btn-sm.active:before {
        opacity: 0;
    }
    .btn-toggle.btn-xs.btn-sm.active:after {
        opacity: 1;
    }
    .btn-toggle.btn-xs.btn-xs:before,
    .btn-toggle.btn-xs.btn-xs:after {
        display: none;
    }
    .btn-toggle.btn-secondary {
        color: #6b7381;
        background: #bdc1c8;
    }
    .btn-toggle.btn-secondary:before,
    .btn-toggle.btn-secondary:after {
        color: #6b7381;
    }
    .btn-toggle.btn-secondary.active {
        background-color: #ff8300;
    }
    .size-titulo {
        font-size: 1.2rem;
        font-weight: 700;
        text-align: center;
    }
    .panel-body .analisis-clinico{
        overflow-x: scroll;
    }

    .swal2-header-popstate {
        margin-top: 1rem;
    }


</style>
<div class="container" style="width:100%">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="row">
                <div class="panel panel-default filterable">            
                    <div style=" font-size: 11px" class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tabRegistroImagenes">
                                <form name="frmRadiologia" id="frmRadiologia" class="form-horizontal" role="form" method="post"  action="{{ route('registrar_radiologia_informe') }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" value="<?php echo $radiologia->id?>" name="nro_prestacion" id="nro_prestacion">
                                    <input type="hidden" value="<?php echo $radiologia->id_prestacion?>" name="id_prestacion" id="id_prestacion">
                                    <input type="hidden" value="<?php echo $radiologia->id_establecimiento?>" name="id_establecimiento" id="id_establecimiento">
                                    <input type="hidden" value="<?php echo $radiologia->id?>" name="id_radiologia" id="id_radiologia">
                                    <input type="hidden" value="{{count($procradiologia)}}" name="total_examenes" id="total_examenes">
                                    <input type="hidden" value="{{ auth()->user()->tipo_registro }}" name="tipo_registro" id="tipo_registro">
                                    <input type="hidden" value="true" name="bool_contenedor_profesional_imagenes" id="bool_contenedor_profesional_imagenes">
                                    <input type="hidden" value="{{ $id_cita }}" id="id_cita" name="id_cita" />
                                    <div class="row">
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
                                    </div>

                                    <div class="row">
                                        <!-- Columna izquierda: Datos del Paciente -->
                                        <div class="col-md-6" style="height: 100%;">
                                            <div class="panel panel-info" style="height: 100%;">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title size-titulo">DATOS DEL PACIENTE</h3>
                                                </div>
                                                <div class="panel-body" style="margin: 0px 10px;">
                                                    <div class="row">
                                                        <table class="table table-borderless" style="width: auto; border: 0px solid;">
                                                            <tr>
                                                                <th style="text-align: left; width: 20%; border: 0px;"><strong>N° DE DOCUMENTO</strong></th>
                                                                <th style="text-align: left; width: 1%; border: 0px;"><strong>:</strong></th>
                                                                <input id="nro_dni_paciente" type="hidden" value="{{ $prestacion->nro_doc_ident }}" />
                                                                <td style="border: 0px; width: 34%;">
                                                                    <span id="nro_doc_ident" style="font-weight: bold; font-size: 13px;">
                                                                        {{ $prestacion->tipo_doc_ident }} - <?php echo isset($prestacion->nro_doc_ident) ? $prestacion->nro_doc_ident : ''; ?>
                                                                    </span>
                                                                </td>
                                                                <th style="text-align: left; width: 8%; border: 0px;"><strong>N° H.C</strong></th>
                                                                <th style="text-align: left; width: 1%; border: 0px;"><strong>:</strong></th>
                                                                <td style="border: 0px; width: 20%; text-transform: uppercase;">
                                                                    <span id="nro_historia" style="font-weight: bold;">
                                                                        <?php echo isset($prestacion->nro_historia) ? $prestacion->nro_historia : ''; ?>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="text-align: left;border: 0px solid;"><strong>APELLIDOS Y NOMBRES</strong></th>
                                                                <th style="text-align: left;border: 0px solid;"><strong>:</strong></th>
                                                                <td style="text-transform: uppercase;border: 0px solid;">
                                                                    <span id="nro_historia" style="font-weight: bold;font-size: 13px;"><?php  echo (isset($prestacion->asegurado_paterno) ? $prestacion->asegurado_paterno : '') . " " . 
                                                                                (isset($prestacion->asegurado_materno) ? $prestacion->asegurado_materno : '') . ", " . 
                                                                                (isset($prestacion->asegurado_nombre) ? $prestacion->asegurado_nombre : ''); ?></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="text-align: left;border: 0px solid;"><strong>FECHA DE NACIMIENTO</strong></th>
                                                                <th style="text-align: left;border: 0px solid;"><strong>:</strong></th>
                                                                <td style="border: 0px solid;">
                                                                    <span id="nro_historia">
                                                                    <?php  echo isset($prestacion->fecha_nac) ? $prestacion->fecha_nac : ''; ?>
                                                                    - <strong>EDAD (
                                                                                        <?php
                                                                            $fecha_nac = DateTime::createFromFormat('d/m/Y', $prestacion->fecha_nac);
                                                                            $hoy = new DateTime();

                                                                            if ($fecha_nac !== false) {
                                                                                $edad = $fecha_nac->diff($hoy);
                                                                                echo $edad->y . ' a ' . $edad->m . ' m y ' . $edad->d . ' d';
                                                                            } else {
                                                                                echo "Formato de fecha inválido";
                                                                            }
                                                                            ?>
                                                                        )</strong>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="text-align: left;border: 0px solid;"><strong>SEXO</strong></th>
                                                                <th style="text-align: left;border: 0px solid;"><strong>:</strong></th>
                                                                <td style="text-transform: uppercase;border: 0px solid;">
                                                                    <span id="nro_historia">
                                                                        <?php echo isset($prestacion->sexo) ? ($prestacion->sexo == "F" ? "Femenino":"Masculino") : ''; ?>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="text-align: left;border: 0px solid;"><strong>TIPO BENEFICIARIO</strong></th>
                                                                <th style="text-align: left;border: 0px solid;"><strong>:</strong></th>
                                                                <td style="text-transform: uppercase;border: 0px solid;">
                                                                    <span id="nro_historia">
                                                                        <?php echo isset($prestacion->parentesco) ? $prestacion->parentesco : ''; ?>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="text-align: left;border: 0px solid;"><strong>CONDICIÓN</strong></th>
                                                                <th style="text-align: left;border: 0px solid;"><strong>:</strong></th>
                                                                <td style="text-transform: uppercase;border: 0px solid;">
                                                                    <span id="nro_historia">
                                                                        {{ $referencia == 'NO' ? 'REFERIDO' : 'NO REFERIDO' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <!-- Columna derecha: Datos Generales -->
                                        <div class="col-md-6">
                                            <div class="panel panel-info contenedor-datos-generales">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title size-titulo">DATOS GENERALES DE LA ATENCIÓN</h3>
                                                </div>
                                                <div class="panel-body" style="margin: 0px 10px;">
                                                    <table class="table table-borderless" style="width: 100%;MARGIN: 0px;">
                                                        <tr>
                                                            <th><strong>COD.SITEDS SUSALUD</strong></th>
                                                            <th>:</th>                        
                                                            <td style="text-transform: uppercase;">
                                                                @if (isset($radiologia->cod_siteds_susalud))
                                                                    <span id="cod_siteds_susalud">{{ $radiologia->cod_siteds_susalud }}</span>
                                                                @else
                                                                    <span class="cod_siteds_susalud"></span>
                                                                    <input type="hidden" class="form-control" id="cod_siteds_susalud" name="cod_siteds_susalud" onkeypress="return esEntero(event);" style="text-transform: uppercase;border-color: red !important;border: 2px red solid !important" maxlength="12" pattern="[0-9]*">
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width: 35%;"><strong>TIPO ATENCIÓN</strong></th>
                                                            <th style="width: 1%;">:</th>
                                                            <td>
                                                                <?php
                                                                    switch ($prestacion->id_tipo_atencion) {
                                                                    case '1':$tipo_atencion = "AMBULATORIO"; break;
                                                                    case '2':$tipo_atencion = "EMERGENCIA"; break;
                                                                    case '3':$tipo_atencion = "HOSPITALIZACION"; break;
                                                                    case '4':$tipo_atencion = "CENTRO QUIRURGICO"; break;
                                                                    case '5':$tipo_atencion = "SERVICIO NUTRICIONAL"; break;
                                                                    }
                                                                ?>
                                                                <?php
                                                                    if ($prestacion->id_tipo_atencion == 2 && $radiologia->id_evaluacion > 0){
                                                                        $tipo_atencion = "EMERGENCIA - EVALUACION";                                                                    
                                                                    }elseif($prestacion->id_tipo_atencion == 3 && $radiologia->id_evaluacion > 0){
                                                                        $tipo_atencion = "HOSPITALIZACION - EVOLUCION";
                                                                    }else{
                                                                        $tipo_atencion = $prestacion->tipo_atencion;
                                                                    }
                                                                        echo '<span id="tipo_atencion" style="font-size: 14px;font-weight: bold;">' . $tipo_atencion . '</span>';
                                                                ?>
                                                            </td>
                                                        </tr>
                                                
                                                        @if($id_tipo_atencion== 2)
                                                        <tr>
                                                            <th style="color:red;"><strong>PRIORIDAD</strong></th>
                                                            <th style="color:red;">:</th>
                                                            <td style="text-transform: uppercase;color:red;">
                                                                <span id="prioridad"><?php echo isset($prestacion->prioridades) ? $prestacion->prioridades : ''; ?></span>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <th><strong>SERVICIO DE PROCEDENCIA</strong></th>
                                                            <th>:</th>
                                                            <td style="text-transform: uppercase;">
                                                                <span id="servicio"><?php echo isset($prestacion->servicio) ? $prestacion->servicio : ''; ?></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th><strong>FECHA ATENCIÓN</strong></th>
                                                            <th>:</th>
                                                            <td>                                                              
                                                                @if($id_tipo_atencion == 1)
                                                                    <span id="fecha_atencion" style=" font-size:12px;"><?php echo isset($prestacion->fecha_atencion) ? date('d/m/Y', strtotime($prestacion->fecha_atencion)) : ''; ?></span>
                                                                @elseif($id_tipo_atencion == 2)
                                                                    @if(!empty($prestacion->id_evaluacion))
                                                                        <span id="fecha_atencion" style=" font-size:12px;"><?php echo isset($prestacion->fecha_evaluacion) ? date('d/m/Y', strtotime($prestacion->fecha_evaluacion)) : ''; ?></span>
                                                                    @else
                                                                        <span id="fecha_atencion" style=" font-size:12px;"><?php echo isset($prestacion->fecha_atencion) ? date('d/m/Y', strtotime($prestacion->fecha_atencion)) : ''; ?></span>
                                                                    @endif
                                                                @elseif($id_tipo_atencion == 3)
                                                                    @if(!empty($evolucion->id_evaluacion))
                                                                        <span id="fecha_atencion" style=" font-size:12px;"><?php echo isset($evolucion->fecha_evolucion) ? date('d/m/Y', strtotime($evolucion->fecha_evolucion)) : ''; ?></span>
                                                                    @else
                                                                        <span id="fecha_atencion" style=" font-size:12px;"><?php echo isset($prestacion->fecha_atencion) ? date('d/m/Y', strtotime($prestacion->fecha_atencion)) : ''; ?></span>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @if($id_tipo_atencion == 2 || $id_tipo_atencion==3)
                                                        <tr>
                                                            <th><strong>UBICACIÓN DE PACIENTE</strong></th>
                                                            <th>:</th>
                                                            <td>
                                                                <!--?php dd($prestacion)?-->
                                                                <span id="ubicacion" style="font-size: 14px;font-weight: bold;"><?php echo isset($prestacion->ubicacion) ? $prestacion->ubicacion : ''; ?> / <strong> CAMA: <?php echo isset($prestacion->nmro_cama) ? $prestacion->nmro_cama : ''; ?> </strong> </span>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <th><strong>PROFESIONAL DE SALUD</strong></th>
                                                            <th>:</th>
                                                            <td style="text-transform: uppercase; font-size:12px">
                                                                <span id="dni_medico"><b></b><strong>{{$prestacion->descripcion}} -</strong></th>
                                                                <?php echo isset($prestacion->dni) ? $prestacion->dni : ''; ?>
                                                                - <?php 
                                                                    echo (isset($prestacion->medico_paterno) ? $prestacion->medico_paterno : '') . " " . 
                                                                        (isset($prestacion->medico_materno) ? $prestacion->medico_materno : '') . ", " . 
                                                                        (isset($prestacion->medico_nombre) ? $prestacion->medico_nombre : ''); 
                                                                ?>
                                                                (<?php echo isset($prestacion->profesion) ? $prestacion->profesion : ''; ?>)
                                                                / <b>N.Coleg: </b>{{$prestacion->colegiatura}}
                                                            
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                    </div>                                      
                                    <div class="panel panel-info" style="height: 100%;">
                                        <div class="panel-heading">
                                            <h3 class="panel-title size-titulo">PROFESIONAL QUE REGISTRA EXAMEN</h3>
                                        </div>
                                        <div class="panel-body" style="margin: 0px 10px;">
                                            <div class="row">
                                                <table class="table table-borderless" style="width: auto; border: 0px solid;">
                                                    <tr>
                                                        <th class="label-th" style="text-align: left; border: 0px solid; width: 10%;"><strong>N° DE DOCUMENTO</strong></th>
                                                        <th class="label-th" style="font-size: 11px; color: #333333; text-align: left; border: 0px solid;"><strong>:</strong></th>
                                                        <input id="nro_dni_paciente" type="hidden" value="{{$prestacion->nro_doc_ident}}" />
                                                        <td class="label-td" style="font-weight: bold; border: 0px solid; width: 30%;">
                                                            <span id="nro_doc_ident" style="font-size: 11px;">
                                                                <?php 
                                                                    echo (!empty($procradiologia[0]->created_nrodoc_digitador_radiologia)) 
                                                                        ? $procradiologia[0]->created_nrodoc_digitador_radiologia 
                                                                        : auth()->user()->dni;
                                                                ?>
                                                            </span>
                                                        </td>
                                                        <th class="label-th" style="text-align: left; border: 0px solid; width: 15%;"><strong>APELLIDOS Y NOMBRES</strong></th>
                                                        <th class="label-th" style="font-size: 11px; color: #333333; text-align: left; border: 0px solid;"><strong>:</strong></th>
                                                        <td class="label-td" style="border: 0px solid;">
                                                            <span id="nro_historia">
                                                                <?php 
                                                                    echo (!empty($procradiologia[0]->created_nombre_digitador_radiologia)) 
                                                                        ? $procradiologia[0]->created_nombre_digitador_radiologia 
                                                                        : auth()->user()->paterno . " " . auth()->user()->materno . " " . auth()->user()->name;
                                                                ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title size-titulo">EXÁMENES SOLICITADOS</h3>
                                        </div>
                                        <div class="panel-body analisis-clinico">
                                            <table id="tblDiagnostico" class="display" cellspacing="0" width="100%" style="font-size: 12px !important;">
                                                <thead>
                                                    <tr>
                                                        <th width="35%" style="text-align: center;">Nombre de Examen</th>
                                                        <th width="5%" style="text-align: center;">Cantidad</th>
                                                        <th width="12%" style="text-align: center;">Lado</th>
                                                        <th width="12%" style="text-align: center;">Comparativo</th>
                                                        <th width="8%" style="text-align: center;">DX</th>
                                                        <th width="23%" style="text-align: center;">Obervacion</th>
                                                        <th width="5%" style="text-align: center;">Radiología Portátil</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $i=0;
                                                    foreach($procradiologia as $row):
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <!--input type="checkbox" name="id_pres_proc[]" value="{{ $row->id }}" class="id_pres_proc" data-id="{{ $row->id }}" @if ($row->confirmar_toma == '0' || $row->confirmar_toma == '3') checked onclick="return false;" @else onChange="seleccionGrupo('{{ $row->id }}',this);" @endif style="font-size: 12px !important;"><span class="checkboxtexto" style="font-size: 12px !important;">&nbsp;&nbsp;&nbsp;<-?php echo $row->codigo." - ".$row->nombre?></span-->
                                                                <input type="checkbox" name="id_pres_proc[]" value="{{ $row->id }}"  class="id_pres_proc" data-id="{{ $row->id }}" @if ($row->confirmar_toma == '0' || $row->confirmar_toma == '3')  checked @endif disabled style="font-size: 12px !important;"><span class="checkboxtexto" style="font-size: 12px !important;">&nbsp;&nbsp;&nbsp;{{ $row->codigo }} - {{ $row->nombre }}</span>
                                                            </td>
                                                            <td style="padding-left: 1rem;">
                                                                <p class="form-control-plaintext text-center m-0">{{ $row->cantidad }}</p>
                                                            </td>
                                                            <td style="padding-left: 1rem;">
                                                                <p class="form-control-plaintext text-center m-0" >
                                                                <?php
                                                                    switch($row->lado_radio){
                                                                        case 0 : echo ""; break;
                                                                        case 1 : echo "Izquierdo"; break;
                                                                        case 2 : echo " Derecho "; break;
                                                                    }
                                                                ?>  
                                                                </p>
                                                            </td>
                                                            <td style="padding-left: 1rem;">
                                                                <p class="form-control-plaintext text-center m-0" >
                                                                <?php
                                                                    switch($row->comparativo_radio){ 
                                                                        case 0 : echo ""; break;
                                                                        case 1 : echo "SI"; break;
                                                                        case 2 : echo "NO"; break;  
                                                                    }
                                                                ?> 
                                                                </p> 
                                                            </td>
                                                            <td style="padding-left: 1rem;">
                                                                <p class="form-control-plaintext text-left m-0" >{{ $row->diagnostico }}</p>
                                                            </td>
                                                            <td style="padding-left: 1rem;">
                                                                <p class="form-control-plaintext text-left m-0" >{{ $row->observacion }}</p>
                                                            </td>
                                                            <td style="padding-left: 1rem;">
                                                                <p class="form-control-plaintext text-left m-0" >{{ $row->radiologia_portatil }}</p>
                                                            </td>
                                                            <input type="hidden" name="id_pres_proc_total[]" class="id_pres_proc_total" value="<?php echo $row->id ?>" />
                                                        </tr>
                                                    <?php 
                                                        $i++;
                                                    endforeach;
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="control-label col-lg-2" style="text-align: left; padding-bottom: 2rem;">CANTIDAD DE EXÁMENES: {{$i}}</label>
                                    </div>
                                    <div class="row" style="margin-bottom: 2rem;">
                                      <div class="panel panel-info" style="height: 100%;">
                                        <div class="panel-heading">
                                            <h3 class="panel-title size-titulo">OBSERVACIÓN</h3>
                                        </div>
                                        <div class="panel-body" style="margin: 0px 10px;">
                                            <div class="row">
                                              <p class="form-control-plaintext text-left m-0" style="font-size: 13px;font-weight: bold;">{{ $row->observacion_registro_informe }}</p>
                                            </div>
                                        </div> 
                                      </div> 
                                    </div> 
                        </div>    
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
