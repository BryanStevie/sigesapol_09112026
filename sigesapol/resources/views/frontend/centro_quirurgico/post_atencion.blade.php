@extends('frontend.layouts.master')
@section('renderjs')
<style>
  .table-sortable tbody tr {
    cursor: move;
  }

  #global {
    min-height: 650px !important;
    margin: 15px;
  }

  .margin {

    margin-bottom: 20px;
  }

  .margin-buscar {
    margin-bottom: 5px;
    margin-top: 5px;
  }

  .row {
    margin-top: 10px;
    padding: 0 10px;
  }

  .clickable {
    cursor: pointer;
  }

  .panel-heading div {
    margin-top: -18px;
    font-size: 15px;
  }

  .panel-heading div span {
    margin-left: 5px;
  }

  .panel-body {
    display: block;
  }

  .dataTables_filter {
    display: none;
  }

  .help-block {
    display: block;
    margin-top: 1px;
    margin-bottom: 10px;
    color: #737373;
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
</style>
@endsection
@section('content')
<div class="container">
    <div class="row"> <!-- row TAB -->
        <div class="panel with-nav-tabs panel-default">
            <div class="panel-heading">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab1" data-toggle="tab" style="font-size:12px">NUEVA ATENCIÓN</a></li>
                    <li><a href="#tab2" data-toggle="tab" style="font-size:12px">HISTORIAL CENTRO QUIRÚRGICO</a></li>
                    <li><a href="#tab3" data-toggle="tab" style="font-size:12px">HISTORIAL DE ATENCIONES</a></li>
                    <li><a href="#tab4" data-toggle="tab" style="font-size:12px">HISTORIAL DE EXAMENES DE LABORATORIO</a></li>
                    <li><a href="#tab5" data-toggle="tab" style="font-size:12px">HISTORIAL DE EXAMENES DE IMAGENOLOGIA</a></li>
                </ul>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab1">

                        <div class="panel panel-primary">
                            <div class="panel-heading">MÓDULO DE GESTIÓN DE ATENCIONES MÉDICAS</div>
                            <div class="panel-body">
                                <form id="FormularioAnularAdmision" name="FormularioAnularAdmision" method="POST" class="form-horizontal">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">DATOS DE LA ATENCIÓN</div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="col-md-2">
                                                    <label>TIPO DE ATENCIÓN</label>
                                                    <input type="text" class="form-control" value="CENTRO QUIRÚRGICO" readonly name="" id="">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="">MODALIDAD</label>
                                                    <input type="text" class="form-control" value="PRESENCIAL" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="" style="color:red">COD. SITEDS SUSALUD</label>
                                                    <input type="text" class="form-control" value="000001234586" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="">SERVICIO</label>
                                                    <input type="text" class="form-control" value="REPRODUCCION HUMANA" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="">SALA</label>
                                                    <input type="text" class="form-control" value="SALA 1" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-2">
                                                    <label for="">FECHA DE ATENCIÓN</label>
                                                    <input type="text" class="form-control datepicker" value="{{date('d-m-Y')}}" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="">HORA DE ATENCION</label>
                                                    <input type="time" class="form-control" value="{{date('H:m')}}" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="">CODIGO UPSS</label>
                                                    <input type="text" class="form-control" value="040100" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="">NOMBRE DE LA UPSS</label>
                                                    <input type="text" class="form-control" value="CENTRO QUIRÚRGICO Y ANESTESIOLOGÍA" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="tipo">NOMBRE DE LA IPRESS</label>
                                                    <input type="text" class="form-control" value="00013591 - Hospital Nacional PNP Luis N. Saenz" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-info">
                                        <div class="panel-heading">INFORMACIÓN DEL PACIENTE <button class="btn btn-xs btn-success pull-right" onclick="mostrarModalInfoPaciente()">VER</button></div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="col-md-2">
                                                    <label for="">DOCUMENTO DE IDENTIDAD</label>
                                                    <input type="text" class="form-control" value="DNI: 09924042" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="">APELLIDO PATERNO</label>
                                                    <input type="text" class="form-control" value="CONDOR" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="">APELLIDO MATERNO</label>
                                                    <input type="text" class="form-control" value="QUISPE" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="">NOMBRES</label>
                                                    <input type="text" class="form-control" value="CALIN" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="">PARENTESCO</label>
                                                    <input type="text" class="form-control" value="TITULAR" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-2">
                                                    <label for="">F. DE NACIMIENTO</label>
                                                    <input type="text" class="form-control" value="06/08/1985" readonly>
                                                </div>
                                                <div class="col-md-1">
                                                    <label for="">CIP</label>
                                                    <input type="text" class="form-control" value="31554758" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="">GRADO</label>
                                                    <input type="text" class="form-control" value="SUBOFICIAL DE TERCERA" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="">SITUACION</label>
                                                    <input type="text" class="form-control" value="ACTIVIDAD" readonly>
                                                </div>
                                                <div class="col-md-1">
                                                    <label for="">SEXO</label>
                                                    <input type="text" class="form-control" value="M" readonly>
                                                </div>
                                                <div class="col-md-1">
                                                    <label for="">EDAD</label>
                                                    <input type="text" class="form-control" value="38" readonly>
                                                </div>
                                                <div class="col-md-1">
                                                    <label for="">T. SANGRE</label>
                                                    <input type="text" class="form-control" value="O+" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="">DIRECCIÓN</label>
                                                    <input type="text" class="form-control" value="JESUS MARIA - AV. POLICIA NRO 126" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-info">
                                        <div class="panel-heading">INFORMACIÓN DEL RESPONSABLE DE LA ATENCION</div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                    <label for="">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                                                    <input type="text" class="form-control datepicker" value="DNI" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="">N° DE DOCUMENTO DE IDENTIDAD</label>
                                                    <input type="text" class="form-control" value="71254863" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                    <label for="">APELLIDO PATERNO</label>
                                                    <input type="text" class="form-control" value="ROMAN" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="">APELLIDO MATERNO</label>
                                                    <input type="text" class="form-control" value="VENTURA" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="">NOMBRES</label>
                                                    <input type="text" class="form-control" value="RUTTY" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                    <label for="">PROFESIÓN</label>
                                                    <input type="text" class="form-control" value="MEDICO ESPECIALISTA" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="">N° COLEGIATURA</label>
                                                    <input type="text" class="form-control" value="6118" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="">N° RNE</label>
                                                    <input type="text" class="form-control" value="1014" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="">ESPECIALIDAD</label>
                                                    <input type="text" class="form-control" value="NEUMOLOGÍA" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="panel with-nav-tabs panel-info">
                                            <div class="panel-heading">
                                                <ul class="nav nav-tabs">
                                                    <li class="active"><a href="#sub-tab1" data-toggle="tab" style="font-size:12px">ANTECEDENTE</a></li>
                                                    <li><a href="#sub-tab2" data-toggle="tab" style="font-size:12px">ANAMNESIS</a></li>
                                                    <li><a href="#sub-tab3" data-toggle="tab" style="font-size:12px">EXAMEN FÍSICO</a></li>
                                                    <li><a href="#sub-tab4" data-toggle="tab" style="font-size:12px">TRATAMIENTO Y/O TERAPEUTICA</a></li>
                                                    <li><a href="#sub-tab5" data-toggle="tab" style="font-size:12px">DIAGNOSTICO</a></li>
                                                    <li><a href="#sub-tab6" data-toggle="tab" style="font-size:12px">PROCEDIMIENTO Y PLAN DE TRABAJO</a></li>
                                                </ul>
                                            </div>
                                            <div class="panel-body">
                                                <div class="tab-content">
                                                    <div class="tab-pane fade in active" id="sub-tab1">
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <label for="">ANTECEDENTE PERSONAL</label>
                                                                <textarea name="" id="" class="form-control">NIEGA</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <label for="">ANTECEDENTE FAMILIAR</label>
                                                                <textarea name="" id="" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-info">
                                        <div class="panel-heading">INFORMACION DE LA ATENCION MEDICA</div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                    <label for="">DOCUMENTO DE IDENTIDAD</label>
                                                    <input type="text" class="form-control" value="DNI:71254863" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="">APELLIDO PATERNO</label>
                                                    <input type="text" class="form-control" value="ROMAN" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="">APELLIDO MATERNO</label>
                                                    <input type="text" class="form-control" value="VENTURA" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="">NOMBRES</label>
                                                    <input type="text" class="form-control" value="RUTTY" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-3">
                                                    <label for="">ESPECIALIDAD</label>
                                                    <input type="text" class="form-control" value="NEFROLOGIA" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="">PROFESIÓN</label>
                                                    <input type="text" class="form-control" value="ENFERMERIA" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="">N° COLEGIATURA</label>
                                                    <input type="text" class="form-control" value="6118" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="">N° RNE</label>
                                                    <input type="text" class="form-control" value="1254" readonly>
                                                </div>
                                            </div>

                                            <div class="panel panel-info">
                                                <div class="panel-heading">DIAGNÓSTICO</div>
                                                <div class="panel-body">
                                                    <table class="table table-condensed table-bordered">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>CÓDIGO</th>
                                                            <th>DESCRIPCIÓN</th>
                                                            <th>TIPO</th>
                                                            <th>CLASIFICACIÓN</th>
                                                        </tr>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>L03.9</td>
                                                            <td>CELULITIS DE SITIO NO ESPECIFICADO</td>
                                                            <td>DEFINITIVO</td>
                                                            <td>ATENCION</td>
                                                        </tr>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>M02.8</td>
                                                            <td>OTRAS ARTROPATIAS REACTIVAS</td>
                                                            <td>PRESUNTIVO</td>
                                                            <td>EVALUACION</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="panel panel-bordered panel-info">
                                                <div class="panel-heading">PROGRAMACIÓN</div>
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <div class="col-md-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                            <label>CONFIRMAR INTERVENCION</label>
                                                        </div>
                                                        <div class="col-md-2" style="margin-left:0px;padding-left:0px">
                                                            <button tabindex="110" type="button" id="switch1" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                            <div class="handle"></div>
                                                            </button>
                                                        </div>
                                                        <fieldset id="fsSwitch1" style="display:none">
                                                            <div class="col-md-2">
                                                                <label for="">FECHA DE INTERVENCIÓN</label>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <input type="text" class="form-control datepicker" readonly>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="">HORA DE INICIO</label>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <input type="time" class="form-control">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label for="">HORA DE TÉRMINO</label>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <input type="time" class="form-control">
                                                            </div>
                                                        </fieldset>
                                                    </div>

                                                    <div class="form-group" >
                                                        <div class="col-md-2">
                                                            <label>SALA DE INTERVENCIÓN</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select name="" id="" class="form-control">
                                                                <option value="">SALA 01</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>TIPO DE ANESTESIA</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select name="" id="" class="form-control">
                                                                <option value="">ANESTESIA GENERAL: INHALATORIA</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group" >
                                                        <div class="col-md-2">
                                                            <button class="btn btn-sm btn-primary">Anestesiologos que intervienen operación</button>
                                                        </div>
                                                    </div>

                                                    <div class="form-group" >
                                                        <div class="col-md-11">
                                                            <input type="text"class="form-control input-sm" value="CARRANZA SOLIS PATRICIA" style="text-align:center">
                                                        </div>
                                                        <div class="col-md-1">
                                                            <button class="btn btn-xs btn-danger"><i class="fa fa-close"></i> Eliminar</button>
                                                        </div>
                                                    </div>

                                                    <div class="form-group" >
                                                        <div class="col-md-11">
                                                            <input type="text"class="form-control input-sm" value="CARRANZA SOLIS PATRICIA" style="text-align:center">
                                                        </div>
                                                        <div class="col-md-1">
                                                            <button class="btn btn-xs btn-danger"><i class="fa fa-close"></i> Eliminar</button>
                                                        </div>
                                                    </div>

                                                    
                                                </div>
                                            </div>

                                            <div class="panel panel-bordered panel-success">
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                            <label>REGISTRAR RECETA</label>
                                                        </div>
                                                        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                                            <button tabindex="70" type="button" id="addRecetaVale" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                            <div class="handle"></div>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <fieldset id="fsRecetaVale" style="display:none">
                                                        <div class="panel panel-success panel-bordered">
                                                            <div class="panel-heading">
                                                                <b>PRODUCTOS MEDICOS</b>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="form-group">
                                                                    <div class="col-md-2">
                                                                        <label>FEC. EXPEDICIÓN (A)</label>
                                                                        <input tabindex="71" type="text" class="form-control datepicker" value="{{date('d-m-Y')}}" name="fecha_expedicion" id="fecha_expedicion" tabindex="2" data-date-end-date="0d" autocomplete="off" />
                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <label>FARMACIA</label>
                                                                        <select tabindex="72" class="form-control" name="id_farmacia_receta" id="id_farmacia_receta" style="width:100%" onchange="getProductoAndAddProducto()">
                                                                            <option value="">-Seleccionar-</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <div class="col-sm-12">
                                                                        <table>
                                                                            <tr>
                                                                                <td>
                                                                                    <button tabindex="75" type="button" id="addProducto" class="btn btn-primary" data-toggle="modal" data-target="#addClassModal">Agregar Producto</button>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <br />
                                                                <div class="form-group" style="display: none">
                                                                    <select class="form-control" id="cmbMedicamentoTemp" tabindex="16" style="width: 500px">
                                                                        <option value="">Seleccionar Producto</option>
                                                                    </select>
                                                                </div>
                                                                <input type="hidden" value="0" name="hidden_rowIndex" id="hidden_rowIndex">
                                                                <div class="form-group">
                                                                    <table id="example" class="display" cellspacing="0" width="100%">
                                                                        <thead>
                                                                            <tr>
                                                                            <th>Producto farmacéutico o dispositivo médico</th>
                                                                            <th style="width:10%">Stock UE-020</th>
                                                                            <th style="width:10%">Stock Establecimiento</th>
                                                                            <th style="width:10%">Stock Farmacia</th>
                                                                            <th>Tiempo(Dias)</th>
                                                                            <th> Via Adm. </th>
                                                                            <th>Posologia</th>
                                                                            <th> Cant. Pres.</th>
                                                                            <th> DiagnosticoxProducto </th>
                                                                            <th>  </th>
                                                                            <th>  </th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tfoot>
                                                                        </tfoot>
                                                                        <tbody>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>

                                            <div class="panel panel-bordered panel-info">
                                                <div class="panel-heading">REGISTRO POSTOPERATORIO</div>
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <div class="col-md-2">
                                                            <label for="">REGISTRAR RECETA</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select name="" id="" class="form-control">
                                                                <option value="">ANESTESIA GENERAL</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-2">
                                                            <label for="">INICIO DE ANESTESIA</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="time" class="form-control" value="">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="">TÉRMINO DE ANESTESIA</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="time" class="form-control" value="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-2">
                                                            <label for="">INICIO DE CIRUGÍA</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="time" class="form-control" value="">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="">TÉRMINO DE CIRUGÍA</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="time" class="form-control" value="">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="">TIEMPO REAL</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="text" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-2">
                                                            <label for="">INICIO DE PACIENTE</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="time" class="form-control" value="">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="">SALIDA DE PACIENTE</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="time" class="form-control" value="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-2">
                                                            <label for="">HORA LLEGADA URPA</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="time" class="form-control" value="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-2">
                                                            <label for="">COMPLICACIÓN</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="radio" name="complicacion" class=""> SI
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="radio" name="complicacion" class=""> NO
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="">DESCRIPCION DE LA COMPLICACIÓN</label>
                                                            <input type="text" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                            <label>REQUIERE HOSPITALIZACIÓN</label>
                                                        </div>
                                                        <div class="col-md-2" style="margin-left:0px;padding-left:0px">
                                                            <button tabindex="110" type="button" id="switch2" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                            <div class="handle"></div>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <fieldset id="fsSwitch2" style="display:none">
                                                        <div class="col-md-2">
                                                            <label for="">DESTINO</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select name="" id="" class="form-control">
                                                                <option value="">HOSPITALIZACIÓN</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="">SALA</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select name="" id="" class="form-control">
                                                                <option value="">UROLOGÍA</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="">CAMA</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select name="" id="" class="form-control">
                                                                <option value="">601</option>
                                                            </select>
                                                        </div>
                                                    </fieldset>
                                                    <br>
                                                    <div class="form-group">
                                                        <div class="col-md-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                            <label>REQUIERE HOSPITALIZACIÓN</label>
                                                        </div>
                                                        <div class="col-md-2" style="margin-left:0px;padding-left:0px">
                                                            <button tabindex="110" type="button" id="switch3" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                            <div class="handle"></div>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <fieldset id="fsSwitch3" style="display:none">
                                                        <div class="col-md-2">
                                                            <label for="">SALA</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="text" class="form-control" value="UROLOGÍA" readonly>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="">CAMA</label>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="text" class="form-control" value="601" readonly>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>

                                            <div class="panel panel-info">
                                                <div class="panel-heading">PROCEDIMIENTOS</div>
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <div class="col-md-2">
                                                            <button class="btn btn-sm btn-primary">Agregar Procedimiento</button>
                                                        </div>
                                                    </div>
                                                    <table class="">
                                                        <tr>
                                                            <th width="60%">Descripción</th>
                                                            <th width="10%">Cantidad</th>
                                                            <th width="20%">Diagnóstico</th>
                                                            <th width="10%"></th>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-right:10px"><input type="text"class="form-control input-sm"></td>
                                                            <td style="padding-right:10px"><input type="text"class="form-control input-sm" value=""></td>
                                                            <td style="padding-right:10px"><input type="text"class="form-control input-sm" value=""></td>
                                                            <td><button class="btn btn-xs btn-danger"><i class="fa fa-close"></i> Eliminar</button></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <button class="btn btn-info pull-right">GUARDAR</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Fin row TAB -->
</div>


<script>
  function mostrarModal() {
    $('#modalAnularAdmision').modal('show');
  }

  function mostrarModalInfoPaciente() {
    $('#modal-info-paciente').modal('show');
  }

  $(document).ready(function() {
    $('#switch1').on('click', function () {
      var switch1 = $('#switch1').attr("aria-pressed");
      $("#fsSwitch1").hide();
      if(switch1 == "false"){
        $("#fsSwitch1").show();
      }
    });

    $('#switch2').on('click', function () {
      var switch2 = $('#switch2').attr("aria-pressed");
      $("#fsSwitch2").hide();
      if(switch2 == "false"){
        $("#fsSwitch2").show();
      }
    });

    $('#switch3').on('click', function () {
      var switch3 = $('#switch3').attr("aria-pressed");
      $("#fsSwitch3").hide();
      if(switch3 == "false"){
        $("#fsSwitch3").show();
      }
    });

    $('#addRecetaVale').on('click', function () {
		var addRecetaVale = $('#addRecetaVale').attr("aria-pressed");
		$("#fsRecetaVale").hide();
		if(addRecetaVale == "false"){
        // getProductoAndAddProducto();
        $("#fsRecetaVale").show();
        }
	});
  });

</script>


@include('frontend.includes.footer')
@endsection
