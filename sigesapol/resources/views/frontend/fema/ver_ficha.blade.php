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
        
        <?php 
        if ($id_proceso == 1):
            switch($id_tipo_examen):
                case 1: ?> 
                    $('#fsOdontologicoGeneral').show(); <?php
                    break;
                case 3: ?> 
                    $('#fsClinicoGeneral').show(); <?php
                    break;
                case 4: ?> 
                    $('#fsSaludMentalGeneral').show(); <?php
                    break;
                case 5: ?> 
                    $('#fsRespiratorioGeneral').show(); <?php
                    break;
                case 6: ?> 
                    $('#fsGinecologicoGeneral').show(); <?php
                    break;
                case 7: ?> 
                    $('#fsCardiovascularGeneral').show(); <?php
                    break;
                case 8: ?> 
                    $('#fsOftalmologicoGeneral').show(); <?php
                    break;
                case 2: ?> 
                    $('#fsLaboratorioGeneral').show(); <?php
                    break;
                case 9: ?> 
                    $('#fsOtorrinoGeneral').show(); <?php
                    break;
            endswitch;
        elseif ($id_proceso == 2):
            switch($id_tipo_examen):
                case 1: ?> 
                    $('#fsOdontologicoEspecial').show(); <?php
                    break;
            endswitch;
        endif;
        ?>
        
    });

</script>
@endsection
@section('content')

<div class="container">
    <div class="row ">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">EXAMEN MEDICO</h3>
                </div>
                <div class="panel-body">
                    <form id="frm" action="{{route('guardarFichaFema')}}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id_ficha" value="{{$ficha->id}}">
                    <input type="hidden" name="id_proceso" value="{{$id_proceso}}">
                    <input type="hidden" name="id_tipo_examen" value="{{$id_tipo_examen}}">
                    <input type="hidden" name="id_profesional" value="{{$profesional->id}}">
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
                                <!--<div class="col-md-3">
                                    <div class="form-group">
                                        <button class="btn btn-success btn-sm form-control">LISTA DE ATENCION MEDICA</button>
                                    </div>
                                </div>  -->
                                
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group text-center">
                                        <label for="">FICHA MEDICA  N°  {{$establecimientos->codigo}} - {{$ficha->abreviatura}} - {{$ficha->id}} - 2023</label>
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
                            <h3 class="panel-title">INFORMACIÓN DEL MEDICO</h3>
                        </div>
                        <div class="panel-body "> <!-- .col-md-12 -->
                           
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" >PERSONAL DE SALUD</label>
                                        <input value="{{$profesional->paterno}} {{$profesional->materno}} {{$profesional->nombre}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" >FECHA DE LA ATENCION</label>
                                        <input value="{{$examen->created_at}} " type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                <!--<div class="col-md-3">
                                    <div class="form-group">
                                        <label for="" >N° CIP</label>
                                        <input value="" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>-->
                            </div>
                        </div>
                    </div>

                    <!-- TRIAJE -->
                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">SIGNOS VITALES</h3>
                        </div>
                        <div class="panel-body "> <!-- .col-md-12 -->
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >TEMPERATURA</label>
                                        <input value="{{$ficha->temperatura}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >TALLA (CM)</label>
                                        <input value="{{$ficha->talla_cm}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >PESO (KG)</label>
                                        <input value="{{$ficha->peso_kg}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >IMC</label>
                                        <input value="{{$imc}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" >DESCRIPCIÓN IMC</label>
                                        <input value="{{$indice}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >PRESION ARTERIAL (MM/HG)</label>
                                        <input value="{{$ficha->presion_arterial_x}}/{{$ficha->presion_arterial_y}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >FREC. RESPIRATORIA (EN UN MIN)</label>
                                        <input value="{{$ficha->frecuencia_respiratoria}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >FREC. CARDIACA (LPM/MIN)</label>
                                        <input value="{{$ficha->frecuencia_cardiaca}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >SaO2<br></label><br>
                                        <br><input value="{{$ficha->sa02_porcentaje}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                            <!--
                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <div class="row">
                                    <div class="col-md-6">CALIFICACION Y OBSERVACIONES (2 AÑOS)</div>
                                    <div class="col-md-6">VACUNAS COVID-19</div>
                                </div>
                            </h3>
                        </div>
                    </div>
                            -->
                    <div class="modalito">
                        <!-- ****************** -->
                        
                        <!-- ****************** -->
                        <fieldset id="fsOdontologicoGeneral" style="display:none">
                            @include('frontend.fema.odonto_general')
                        </fieldset>
                        <fieldset id="fsOdontologicoEspecial" style="display:none">
                            @include('frontend.fema.odonto_especifico')
                        </fieldset>
                        <fieldset id="fsLaboratorioGeneral" style="display:none">
                            @include('frontend.fema.laboratorio')
                        </fieldset>
                        <fieldset id="fsClinicoGeneral" style="display:none">
                            @include('frontend.fema.clinico_general')
                        </fieldset>
                        <fieldset id="fsClinicoEspecial" style="display:none">
                            @include('frontend.fema.clinico_general_especifico')
                        </fieldset>
                        <fieldset id="fsSaludMentalGeneral" style="display:none">
                            @include('frontend.fema.salud_mental')
                        </fieldset>
                        <fieldset id="fsSaludMentalEspecial" style="display:none">
                            @include('frontend.fema.salud_mental_especifico')
                        </fieldset>
                        <fieldset id="fsRespiratorioGeneral" style="display:none">
                            @include('frontend.fema.aparato_respiratorio')
                        </fieldset>
                        <fieldset id="fsRespiratorioEspecial" style="display:none">
                            @include('frontend.fema.aparato_respiratorio_especifico')
                        </fieldset>
                        <fieldset id="fsGinecologicoGeneral" style="display:none">
                            @include('frontend.fema.ginecologico')
                        </fieldset>
                        <fieldset id="fsCardiovascularGeneral" style="display:none">
                            @include('frontend.fema.cardiovascular')
                        </fieldset>
                        <fieldset id="fsCardiovascularEspecial" style="display:none">
                            @include('frontend.fema.cardiovascular_especifico')
                        </fieldset>
                        <fieldset id="fsOftalmologicoGeneral" style="display:none">
                            @include('frontend.fema.oftalmologico')
                        </fieldset>
                        <fieldset id="fsOtorrinoGeneral" style="display:none">
                            @include('frontend.fema.otorrinolaringologico')
                        </fieldset>
                        <fieldset id="fsDigestivoGeneral" style="display:none">
                            @include('frontend.fema.digestivo')
                        </fieldset>
                        
                    </div>
                    

                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">OBSERVACIONES</h3>
                        </div>
                        <div class="panel-body ">
                            <div class="row">
                                <div class="col-md-1">
                                    <label for="">#</label>
                                </div>
                                <div class="col-md-11">
                                    <label for="">OBSERVACIÓN</label>
                                </div>
                            </div>
                            <?php $i = 1;?>
                            @foreach($observaciones as $o)
                            <div class="row">
                                <div class="col-md-1">
                                    <input value="{{$i}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                </div>
                                <div class="col-md-11">
                                    <input value="{{$o->observacion}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                </div>
                            </div>
                            <?php $i++; ?>
                            @endforeach
                        </div>
                    </div>

                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">DIAGNOSTICOS</h3>
                        </div>
                        <div class="panel-body ">
                            <div class="row">
                                <div class="col-md-1">
                                    <label for="">#</label>
                                </div>
                                <div class="col-md-9">
                                    <label for="">DIAGNOSTICO</label>
                                </div>
                                <div class="col-md-2">
                                    <label for="">TIPO DIAGNOSTICO</label>
                                </div>
                            </div>
                            <?php $i = 1;?>
                            @foreach($diagnosticos as $d)
                            <div class="row">
                                <div class="col-md-1">
                                    <input value="{{$i}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                </div>
                                <div class="col-md-9">
                                    <input value="{{$d->cod_cie}} - {{$d->diagnostico}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                </div>
                                <div class="col-md-2">
                                    <input value="{{$d->tipo_diagnostico}}" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                </div>
                            </div>
                            <?php $i++; ?>
                            @endforeach
                        </div>
                    </div>

                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">ALERTA</h3>
                        </div>
                        <div class="panel-body ">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea name="alerta" id="alerta" class="form-control" readonly="">{{$examen->alerta}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--
                    <div class="row">
                        <div class="col-md-12">
                            <input type="button" id="btnPrestacion" value="GUARDAR" class="btn btn-primary pull-right">
                        </div>
                    </div>
                        -->
                    <!-- ***************************** -->
                    <div class="row">
                        <div class="col-md-12" align='right'>
                            <a href="/busqueda_fema_ficha" class="btn btn-success">Atras</a>
                            
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