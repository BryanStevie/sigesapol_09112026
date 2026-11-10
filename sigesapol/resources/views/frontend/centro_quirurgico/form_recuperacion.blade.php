@extends('frontend.layouts.master')
@section('renderjs')

<style>

.table-sortable tbody tr {
 cursor: move;
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
 margin-left: -272%;
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

.panel-heading2 {
 padding: 15px ;
 color: #3c763d;
 background-color: #e0e9e0;
 border-color: #e0e9e0;
 margin-left: 20px;
}

.titulo{
 margin-left: 75%;
}

.thead-dark {
 position: relative;
 left: 80px; 
 border: none;
}

#tblpaciente th{
  padding: 15px;
  text-align: center; 
  white-space: nowrap;
}

#tblpaciente td{
  text-align: center; 
}

.diagnostic-item1 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
    padding: 10px 0;
    border-bottom: 1px solid #ccc;
    width: 200%;
}

.diagnostic-item{
   margin-top: 6px;
}

.diagnostic-item1:last-child {
    border-bottom: none;
}

.btn-diagnost {
    display: flex;
    justify-content: space-around;
    margin-top: 20px;
    width: 53%;
    padding: 10px;
    margin-left: 80%;
}

.diagnostic-item1 input[type="checkbox"],
.diagnostic-item input[type="checkbox"] {
    appearance: none;
    -webkit-appearance: none;
    width: 20px;
    height: 20px;
    border: 2px solid #808080; 
    background-color: white;
    cursor: pointer;
    position: relative;
}

.diagnostic-item1 input[type="checkbox"]:checked,
.diagnostic-item input[type="checkbox"]:checked {
    background-color: #808080; 
    border-color: #808080;
}

.diagnostic-item1 input[type="checkbox"]:checked::before,
.diagnostic-item input[type="checkbox"]:checked::before {
    content: "✔";
    position: absolute;
    top: -2px;
    left: 2px;
    font-size: 16px;
    color: white; 
}

.btn-funcionvital{
    display: flex;
    justify-content: space-around;
    margin-top: 28px;
    width: 27%;
    padding: 10px;
    margin-left: 24%;
}

#table td{
    padding: 8px;
}

</style>

<script>

 $('#addBalanceHidrico').on('click', function () {
 var ariaPressed = $('#addBalanceHidrico').attr("aria-pressed");
   $("#fsaddBalanceHidrico").hide();
 if(ariaPressed == "false"){
   $("#fsaddBalanceHidrico").show();
 }
});

$('#tratamiento').on('click', function () {
 var ariaPressed = $('#tratamiento').attr("aria-pressed");
   $("#fsatratamiento").hide();
 if(ariaPressed == "false"){
   $("#fsatratamiento").show();
 }
});

$('#funciones-vitales').on('click', function () {
 var ariaPressed = $('#funciones-vitales').attr("aria-pressed");
   $("#fsfunciones-vitales").hide();
 if(ariaPressed == "false"){
   $("#fsfunciones-vitales").show();
 }
});

$('#diagnostico-enfermeria').on('click', function () {
 var ariaPressed = $('#diagnostico-enfermeria').attr("aria-pressed");
   $("#fsdiagnostico-enfermeria").hide();
 if(ariaPressed == "false"){
   $("#fsdiagnostico-enfermeria").show();
 }
});

function funcionVital(){
  $('#modalFuncionVital').modal('show');
}

</script>

@endsection
@section('content')
<div class="container-fluid">
    <div class="panel panel-primary filterable">
      <div class="panel-heading">
        <h3 class="panel-title">HOJA DE RECUPERACION</h3>
      </div>
      <form name="frmRecuperacion" id="frmRecuperacion" role="form" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="panel-body">
            <div class="form-horizontal">
               <div class="row">
                   <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                   <label class="form-control-sm">NOMBRE DEL PACIENTE :</label>
                   <input class="form-control input-sm" type="text" name="nombre_paciente" id="nombre_paciente" placeholder=""
                       value="GOMEZ DIAZ YHOEL" disabled>
                   </div>
                   <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                   <label class="form-control-sm">EDAD :</label>
                   <input class="form-control input-sm" type="text" name="edad" id="edad" placeholder=""
                       value="59" disabled>
                   </div>
                   <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                   <label class="form-control-sm">CAMA :</label>
                   <input class="form-control input-sm" type="text" name="cama" id="cama" placeholder=""
                       value="06" disabled>
                   </div>
                   <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                   <label class="form-control-sm">FECHA :</label>
                   <input class="form-control input-sm" type="text" name="fecha" id="fecha" placeholder=""
                       value="15/08/2024" disabled>
                   </div>
               </div>
               <div class="row">
                   <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                   <label class="form-control-sm">DIAGNOSTICO OPERATORIO:</label>
                   <input class="form-control input-sm" type="text" name="diagnostico" id="diagnostico" placeholder=""
                      style="width: 433%;" disabled>
                   </div>
               </div>
               <div class="row">
                   <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                   <label class="form-control-sm">ANTECEDENTES :</label>
                   <input class="form-control input-sm" type="text" name="antecedentes" id="antecedentes" placeholder=""
                       value="OBESIDAD" disabled>
                   </div>
                   <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                       <label class="form-control-sm">TIPO DE ANESTESIA :</label>
                       <input class="form-control input-sm" type="text" name="tipo_anestesia" id="tipo_anestesia" placeholder=""
                       value="GENERAL" disabled>
                   </div>
               </div>
               <div class="row">
                   <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                   <label class="form-control-sm">CIRUJANO :</label>
                   <input class="form-control input-sm" type="text" name="cirujano" id="cirujano" placeholder=""
                       value="MEDINA CUBAS ELIAS" disabled>
                   </div>
                   <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                       <label class="form-control-sm">ANESTESI&OacuteLOGO :</label>
                       <input class="form-control input-sm" type="text" name="tipo_anestesia" id="tipo_anestesia" placeholder=""
                       value="CAMPOS DEL SOLAR JUAN" disabled>
                   </div>
               </div>
            </div>
        </div>
       <div class="panel panel-info panel-bordered panel-sm">
           <div class="panel-heading2">
             <div class="form-group">
               <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                 <label>BALANCE HIDRICO</label>
               </div>
               <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                 <button tabindex="70" type="button" id="addBalanceHidrico" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                 <div class="handle"></div>
                 </button>
               </div>
             </div>
           </div>
           <fieldset id="fsaddBalanceHidrico" style="display:none">
               <div class="d-flex">
                 <div class="col-md-6"><br>
                   <div class="panel-heading2" style="width:104%;">
                       <div class="form-group">
                         <div class="col-sm-7">
                           <label class="titulo">INGRESO</label>
                         </div>
                       </div>
                   </div><br>
                   <table id="table">
                       <thead class="thead-dark">
                           <tr>
                               <th></th>
                               <th>SOP</th>
                               <th>M</th>
                               <th>T</th>
                               <th>N</th>
                               <th>TOT</th>
                           </tr>
                       </thead>
                       <tbody>
                           <tr>
                               <td>DEXT</td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                           </tr>
                           <tr>
                               <td>CLNA</td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm" value="100"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                           </tr>
                           <tr>
                               <td>V&Oacute</td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                           </tr>
                           <tr>
                               <td>AO</td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                           </tr>
                           <tr>
                               <td>SAN</td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                           </tr>
                           <tr>
                               <td>PLA</td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                           </tr>
                           <tr>
                               <td>MAN</td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                           </tr>
                           <tr>
                               <td>OTR</td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                           </tr>
                           <tr>
                               <td>IV</td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                               <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                           </tr>
                       </tbody>
                   </table><br>
                 </div>
                 <div class="col-md-6"><br>
                   <div class="panel-heading2">
                       <div class="form-group">
                         <div class="col-sm-7">
                           <label class="titulo">EGRESO</label>
                         </div>
                       </div>
                   </div><br>
                   <table id="table">
                     <thead class="thead-dark">
                         <tr>
                             <th></th>
                             <th>SOP</th>
                             <th>M</th>
                             <th>T</th>
                             <th>N</th>
                             <th>TOT</th>
                         </tr>
                     </thead>
                     <tbody>
                         <tr>
                             <td>PI</td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                         </tr>
                         <tr>
                             <td>SAN</td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm" value="20"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                         </tr>
                         <tr>
                             <td>OR</td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                         </tr>
                         <tr>
                             <td>VOM</td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                         </tr>
                         <tr>
                             <td>DRE</td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                         </tr>
                         <tr>
                             <td>HE</td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                             <td><input type="text" id="abierto" class="form-control form-control-sm"></td>
                         </tr>
                     </tbody>
                   </table><br>
                 </div>
               </div>
           </fieldset>
       </div>
       <fieldset class="panel panel-info panel-bordered panel-sm" style="border: none;">
           <div class="panel-heading2">
             <div class="form-group">
               <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                 <label>TRATAMIENTO</label>
               </div>
               <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                   <button tabindex="70" type="button" id="tratamiento" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                   <div class="handle"></div>
                   </button>
               </div>
             </div>
           </div>
           <div class="col-md-4 d-flex flex-column justify-content-between" id="fsatratamiento" style="display: none;">
               <div class="col-md-12"><br>
                 <label style="margin-left: 20px;">DESCRIPCION:</label>
                 <textarea style="margin-left: 16%;width:300%;" name="descripcion" id="descripcion" cols="50" rows="7" class="form-control">NACT 0.9% PPS 1000
NACT 0.9% (100 MT) >XXXGTS I - II</textarea>
               </div>
           </div>
       </fieldset>
       <fieldset class="panel panel-info panel-bordered panel-sm" style="border: none;">
           <div class="panel-heading2">
             <div class="form-group">
               <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                 <label>FUNCIONES VITALES</label>
               </div>
               <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                   <button tabindex="70" type="button" id="funciones-vitales" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                   <div class="handle"></div>
                   </button>
               </div>
             </div>
           </div>
           <div class="col-md-4 d-flex flex-column justify-content-between" id="fsfunciones-vitales" style="display: none;margin-left:12px;">
               <div class="row">
                   <div class="col-md-3" style="margin-top:20px;">
                     <label class="form-control-sm">FECHA INICIO:</label>
                     <input class="form-control input-sm" style="text-align:center;" type="text" name="fecha_inicio" id="fecha_inicio" placeholder=""
                      value="08-14-2024">
                   </div>
                   <div class="col-md-3" style="margin-top:20px;">
                     <label class="form-control-sm">FECHA FIN:</label>
                     <input class="form-control input-sm" style="text-align:center;" type="text" name="fecha_fin" id="fecha_fin" placeholder=""
                         value="08-14-2024">
                   </div>
                   <div class="col-md-3" style="margin-top:20px;">
                     <label class="form-control-sm">HORA:</label>
                     <input class="form-control input-sm" type="time" name="nombre_paciente" id="nombre_paciente" placeholder=""
                      value="04:01">
                   </div>
                   <div class="col-md-3">
                       <label>&nbsp;</label><br><br>
                       <a href="javascript:void(0)" aria-hidden="true" class="btn btn-success btn-sm" onClick="funcionVital()">NUEVA FUNCION VITAL</a>
                   </div>
               </div>
               <div id="global"><br><br>
                 <table id="tblpaciente" class="table table-condensed table-bordered">
                   <thead>
                     <tr>
                       <th>COD</th>
                       <th>FECHA INCIO</th>
                       <th>HORA FINAL</th>
                       <th>SAT</th>
                       <th>Co2</th>
                       <th>R.A</th>
                       <th>T</th>
                       <th>F.C</th>
                       <th>P.V.C</th>
                       <th>VENTILADOR</th>
                       <th>MASC. VENT</th>
                       <th>ESPONTANEO</th>
                     </tr>
                   </thead>
                   <tbody>
                     <tr>
                       <td>435345</td>
                       <td>15/08/2024</td>
                       <td>06:30 AM</td>
                       <td>98%</td>
                       <td></td>
                       <td>199/23</td>
                       <td></td>
                       <td>77X</td>
                       <td></td>
                       <td></td>
                       <td>MR</td>
                       <td>1235</td>         
                     <tr>
                     <tr>
                       <td>345345</td>
                       <td>15/08/2024</td>
                       <td>06:33 AM</td>
                       <td>100%</td>
                       <td></td>
                       <td>120/45</td>
                       <td></td>
                       <td>64</td>
                       <td></td>
                       <td></td>
                       <td>MR</td>
                       <td>1234</td>         
                     <tr>
                       <td>234344</td>
                       <td>15/08/2024</td>
                       <td>09:40 AM</td>
                       <td>96%</td>
                       <td></td>
                       <td>213/23</td>
                       <td></td>
                       <td>63</td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td>1245</td>       
                     <tr>  
                     <tr>
                       <td>234344</td>
                       <td>15/08/2024</td>
                       <td>11:40 AM</td>
                       <td>94%</td>
                       <td></td>
                       <td>213/23</td>
                       <td></td>
                       <td>74</td>
                       <td></td>
                       <td></td>
                       <td>MR</td>
                       <td>1435</td>       
                     <tr>
                   </tbody>
                 </table>
               </div>  
           </div>
       </fieldset>
       <fieldset class="panel panel-info panel-bordered panel-sm" style="border: none;">
           <div class="panel-heading2">
             <div class="form-group">
               <div class="col-sm-7" style="margin-right:0px;padding-right:0px">
                 <label>DIAGNOSTICO DE ENFERMERIA</label>
               </div>
               <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                   <button tabindex="70" type="button" id="diagnostico-enfermeria" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                   <div class="handle"></div>
                   </button>
               </div>
             </div>
           </div>      
           <div class="col-md-4 d-flex flex-column justify-content-between" id="fsdiagnostico-enfermeria" style="display: none;">
             <div class="row" style="margin-left:30px;white-space: nowrap;">
               <div class="diagnostic-item1">
                   1.- PATRÓN RESPIRATORIO INEFICAZ RELACIONADO CON DOLOR QUE SE EVIDENCIA POR DISNEA
                   <input type="checkbox" checked>
               </div>
               <div class="diagnostic-item1">
                   2.- LIMPIEZA INEFICAZ DE LAS VÍAS AÉREAS RELACIONADO CON LA RETENCIÓN DE LAS SECRECIONES SECUNDARIO A<br>
                    ESTADO DE INCONSCIENCIA
                   <input type="checkbox">
               </div>
               <div class="diagnostic-item">
                   3.- TERMORREGULACIÓN INEFICAZ RELACIONADO CON TEMPERATURA DEL AMBIENTE LUCTUANTE
                   <input style="margin-left: 80.4%;" type="checkbox" checked>
               </div>
               <div class="btn-diagnost">
                   <button type="submit" class="btn btn-success guardar" id="GuardarDiagEnfermeria" value="create">GUARDAR</button>
                   <button type="button" class="btn btn-danger pull-left">CANCELAR</button>
               </div>
           </div>
       </fieldset>
      </form>
    </div>
</div>

<div class="modal fade" id="modalFuncionVital" tabindex="-1" role="dialog" aria-labelledby="modalFuncionVitalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 58%;" role="document">
    <div class="modal-content">
        <div class="modal-header" style="font-size: 20px !important;">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <b>NUEVA FUNCION VITAL</b>
        </div>
        <div style="font-size:11px;margin-left:30px;" class="panel-body">
          <form id="FormularioFuncionVital" name="FormularioFuncionVital" enctype="multipart/form-data"  method="POST">
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <div class="row">
              <div class="col-md-3">
                <label class="form-control-sm" style="display: inline-block; width: 30%;">HORA:</label>
                <input class="form-control input-sm" type="time" name="hora" id="hora" placeholder=""
                value="04:01">
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <label class="form-control-sm" style="display: inline-block; width: 30%;">SAT. O:</label>
                <input class="form-control input-sm" type="text" name="hora" id="hora" placeholder="">
              </div>
              <div class="col-md-3">
                <label class="form-control-sm" style="display: inline-block; width: 30%;">T:</label>
                <input class="form-control input-sm" type="text" name="hora" id="hora" placeholder="">
              </div>
              <div class="col-md-3">
                <label class="form-control-sm" style="display: inline-block; width: 30%;">VENTILADOR::</label>
                <input class="form-control input-sm" type="text" name="hora" id="hora" placeholder="">
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <label class="form-control-sm" style="display: inline-block; width: 30%;">Co2:</label>
                <input class="form-control input-sm" type="text" name="hora" id="hora" placeholder="">
              </div>
              <div class="col-md-3">
                <label class="form-control-sm" style="display: inline-block; width: 30%;">F.C:</label>
                <input class="form-control input-sm" type="text" name="hora" id="hora" placeholder="">
              </div>
              <div class="col-md-3">
                <label class="form-control-sm" style="display: inline-block; width: 30%;">MASC. VENT:</label>
                <input class="form-control input-sm" type="text" name="hora" id="hora" placeholder="">
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <label class="form-control-sm" style="display: inline-block; width: 30%;">P.A:</label>
                <input class="form-control input-sm" type="text" name="hora" id="hora" placeholder="">
              </div>
              <div class="col-md-3">
                <label class="form-control-sm" style="display: inline-block; width: 30%;">P.V.C:</label>
                <input class="form-control input-sm" type="text" name="hora" id="hora" placeholder="">
               </div>
               <div class="col-md-3">
                <label class="form-control-sm" style="display: inline-block; width: 30%;">ESPONTANEO:</label>
                <input class="form-control input-sm" type="text" name="hora" id="hora" placeholder="">
              </div>
            </div>
            <div class="btn-funcionvital">
              <button type="submit" class="btn btn-success guardar" id="GuardarFuncionVital" value="create">GUARDAR</button>
              <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">CANCELAR</button>
            </div>
          </form>
        </div>
    </div>
  </div>
</div>

@include('frontend.includes.footer')
@endsection