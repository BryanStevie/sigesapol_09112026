@extends('frontend.layouts.master')
@section('renderjs')
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}
<style>
  .loader {
    width: 100%;
    height: 100%;
    /*height: 1500px;*/
    overflow: hidden;
    top: 0px;
    left: 0px;
    z-index: 10000;
    text-align: center;
    position: absolute;
    background-color: #000;
    opacity: 0.6;
    filter: alpha(opacity=40);
    display: none;
  }
  .dataTables_processing {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 500px !important;
    font-size: 1.7em;
    border: 0px;
    margin-left: -17% !important;
    text-align: center;
    background: #3c8dbc;
    color: #FFFFFF;
  }
  .modal-dialog {
    width: 90%;
  }
  .title-dialog {
    font-size: 18px;
    font-family: Arial, Helvetica, sans-serif;
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
    font-family: Arial, Helvetica, sans-serif;
    text-align: left;
    background-color: #ffffff;
    border: 1px solid #cccccc;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    background-clip: padding-box;
  }
  .ui-autocomplete>li>div {
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
    color: #FFFFFF !important;
    background: #1d71b8 !important;
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
  input[readonly] {
    background-color: #eeeeee;
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
  .uneditable-input:focus {
    border-color: #2196F3;
    box-shadow: 0 1px 1px #2196F3 inset, 0 0 8px #2196F3;
    outline: 0 none;
  }
  /*.fc-event-container{margin:20px!important}*/
  .fc-day-grid-event {
    /*width:150px;*/
    margin: 10px;
    text-align: center;
    margin-top: 40px;
    /*background:#5cb85c*/
    background: transparent;
    color: #009966
  }
  .fc-day-grid-event :hover {
    color: #009966
  }
  .fc-content {
    padding: 5px !important;
  }
  .fc-title {
    text-align: center;
    font-size: 12px
  }
</style>
<script>

    $(document).ready(function () {
        
        
        
  $('#btnPrestacion').on("click", function(e) {
    anularatencion();
  });

        
function anularatencion() {

  var msg = "";
  
  if (msg != "") {
    bootbox.alert(msg);
    return false;
  } else {   
    var r = confirm("¿Seguro de Anular el Triaje de la FICHA de atencion?");
    if (r == true) {
      $('#frm').submit();
    } 
  }
}
    });

</script>
@endsection
@section('content')

<div class="container">
    <div class="row ">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">FICHA MEDICA</h3>
                </div>
                <div class="panel-body">
               
                <form method="post" id="frm"
              action="{{ route('anularficha_triaje_ft')}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id_ficha" value="{{$ficha->id}}">
                    <input type="hidden" name="id_proceso" value="{{$id_proceso}}">


                    <input type="hidden" id="nomsexo" value="{{$ficha->sexo}}">
                    <div class="panel panel-info ">
                        <div class="panel-body "> 
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input value="{{$ficha->fecha_reg}} {{$ficha->hora_reg}}" type="text" name="" id="" class="form-control" readonly="" tabindex="" placeholder="FECHA Y HORA REGISTRO">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input value="{{$ficha->proceso}}" type="text" name="" id="" class="form-control" readonly="" tabindex="" placeholder="TIPO PROCESO">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input value="{{$ficha->tipo_proceso}}" type="text" name="" id="" class="form-control" readonly="" tabindex="" placeholder="SUB PROCESO">    
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">INFORMACIÓN PERSONAL</h3>
                        </div>
                        <div class="panel-body "> <!-- .col-md-12 -->
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">TIPO DOCUMENTO</label>
                                        <input value="DNI" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">N° DOCUMENTO</label>
                                        <input value="{{$ficha->nro_doc_ident}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">GRADO</label>
                                        <input value="{{$ficha->grado}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">APE PATERNO</label>
                                        <input value="{{$ficha->paterno}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">APE MATERNO</label>
                                        <input value="{{$ficha->materno}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">NOMBRES</label>
                                        <input value="{{$ficha->nombre}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">HISTORIA CLÍNICA DE IPRESS</label>
                                        <input value="{{$ficha->historia_clinica_ipress}}" type="text" name="" id="" class="form-control" placeholder="" readonly=""  tabindex="">
                                    </div>
                                </div> 
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">HISTORIA CLÍNICA</label>
                                        <input value="{{$ficha->historia_clinica}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">N° CIP</label>
                                        <input value="{{$ficha->carnet}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="">SEXO</label>
                                        <input value="{{$sexo}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="">CELULAR</label>
                                        <input value="{{$ficha->celular}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">EDAD</label>
                                        <input value="{{$ficha->edad}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">MOTIVO DE ANULAR EL TRIAJE</h3>
                        </div>
                        <div class="panel-body ">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea name="motivo" id="motivo" class="form-control" ></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <input type="submit" class="btn btn-info input-sm pull-right" value="Anular Triaje de FICHA" name="anular" form="prestacionanular" id="btnPrestacion" style="margin-left:20px" />
                        </div>
                    </div>
                   
                    </form>
                </div> <!-- .panel-body -->
            </div> <!-- .panel -->
        </div> <!-- .col -->
    </div> <!-- .row -->
</div>
@include('frontend.includes.footer')
@endsection