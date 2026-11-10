@extends('frontend.layouts.master')
@section('renderjs')
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
function modalHistorial(){
    var id_hospitalizado = $("#id_hospitalizado").val();
    $.ajax({
            url: "../../modal_historial_cama_historial/"+id_hospitalizado,
            type: "GET",
            success: function (result) {  
                    $("#divHistorial").html(result);

                    $('#tablemodalm').DataTable({
                        "sPaginationType": "full_numbers",
                        
                        "language": {"url": "/js/Spanish.json"},
                    });
            }
    });
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

</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="row">
                <div class="panel panel-primary filterable">            
                    <div class="panel-heading">
                        <h3 class="panel-title">MÓDULO PARA HISTORIAL DE PACIENTES - PACIENTES HOSPITALIZADOS</h3>
                    </div> 
                    <div style=" font-size: 11px" class="panel-body">
                        <form class="form-horizontal" method="post" action="{{ route('rotacion_cama')}}" id="frmReceta" autocomplete="off">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="col-md-4 text-center">
                                    <img src="../../images/logo_sanidad.png" width="50" height="auto">
                                    <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
                                    <input type="hidden" value="{{Auth::user()->id_establecimiento}}" name="id_establecimiento" id="id_establecimiento">
                                    <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                                    <input type="hidden" value="" name="dni_beneficiario">
                                    <input type="hidden" value="dni" name="tipo">
                                    <input type="hidden" name="id_hospitalizado" id="id_hospitalizado" value=""/>
                                    <input type="hidden" value="" name="id_ipress" id="id_ipress">
                                    <input type="hidden" value="" name="nivel" id="nivel">
                                </div>
                                <div class="col-md-4" style="text-align: center">
                                    <label></label><br>
                                    <label style="font-size: 24px">HISTORIAL DE PACIENTES</label><br>
                                </div>
                                <div class="col-md-4 text-center">
                                    <img src="../../images/logo_sanidad.png" width="50" height="auto">
                                </div>
                            </div>
                           
                                
                                
                               
                          
                            <div class="col-md-12" id="ver_cama" style="display:none">  
                                <div class="col-md-3">
                                   
                                    <label>CAMA</label>
                                    <select id="id_cama2" name="id_cama2" class="form-control" style="width: 100%">
                                        <option value="">--Seleccionar--</option>
                                                                                
                                    </select>
                                </div>
                            </div>    
                            <div class="col-md-12" id="ver_cambio" style="display:none">  
                                
                                    <div class="col-md-4">
                                        <label>SALA</label>
                                        <select class="form-control" id="id_consultorio" name="id_consultorio" onchange="getCama();" style="width: 100%">
                                            <option value="">--Seleccionar--</option>
                                                                                  
                                      </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>TURNO</label>
                                        <select class="form-control" name="turno" id="turno"  tabindex="3" style="width: 100%" onchange="getMedico()">
                                            <option value="">-Seleccionar-</option>
                                            <option value="MD" <?php if ('MD') echo 'selected';?>>MADRUGADA</option>
                                            <option value="M" <?php if ('M') echo 'selected';?>>MAÑANA</option>
                                            <option value="T" <?php if ('T') echo 'selected';?>>TARDE</option>
                                            <option value="N" <?php if ('N') echo 'selected';?> >NOCHE</option>
                                      </select>
                                    </div>
                                
                               
                                    
                                        <div class="col-md-3">
                                            <label>SERVICIO</label>
                                            <select class="form-control" id="id_consultorio" name="id_consultorio" onchange="getSala();" style="width: 100%">
                                                <option value="">--Seleccionar--</option>
                                                                                         
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>SALA</label>
                                            <select class="form-control" id="id_sala" name="id_sala" onchange="getCama2();" style="width: 100%">
                                                <option value="">--Seleccionar--</option>
                                                                                     
                                            </select>
                                        </div>
                                   
                                
                               
                                    <div class="col-md-2">
                                        <label>CAMA</label>
                                        <select id="id_cama" name="id_cama" class="form-control" style="width: 100%">
                                            <option value="">--Seleccionar--</option>
                                                                                   
                                        </select>
                                    </div>
                               
                                <div class="col-md-12">
                                    <div class="col-md-3"><br/>
                                        <label>Nro DOCUMENTO </label>
                                        <input type="text" class="form-control" name="documento" id="documento" style="text-align:left">
                                    </div>
                                    <div class="col-md-6"><br/>
                                        <label>MEDICO QUE AUTORIZA TRASLADO (DNI | CMP | Nombre y Apellidos)</label>
                                        <?php if (isset($existe_medico) && $existe_medico == 1): ?>
                                            <input  name="exite_medico" value="{{$existe_medico}}" type="hidden">
                                        <?php endif; ?>
                                        <select class="form-control" name="id_medico"  id="id_medico" tabindex="5" style="width: 100%">
                                            <option value="">--Seleccionar--</option>
                                                                                     
                                      </select>
                                    </div>
                                </div>
                            </div>
                            
                           
                        </form>
                        <div class="col-md-12">
                            <br/><br/>
                            <fieldset>
                            <div class="panel panel-info panel-bordered panel-sm">
                              <div class="panel-heading">
                                <div class="panel-title text-center">
                                  <b>HISTORIAL DE ROTACIONES DE CAMAS</b>
                                </div>
                              </div>
                              <div class="panel-body">
                                <div class="form-group">
                                  <div id='divHistorial'></div>
                                </div>
                              </div>
                            </div>
                          </fieldset>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection