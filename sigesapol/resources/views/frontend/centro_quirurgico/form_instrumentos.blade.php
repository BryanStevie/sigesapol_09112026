@extends('frontend.layouts.master')
@section('renderjs')
<style>
    .intro-contenedor {
        padding-top: 1rem;
    }
    /* input[type=checkbox]
    {
    -ms-transform: scale(2);
    -moz-transform: scale(2);
    -webkit-transform: scale(2);
    -o-transform: scale(2);
    padding: 10px;
    } */
</style>
@endsection

@section('content')

<div class="container" style="width:80%">
    <div class="row">
      <div class="col-sm-12 col-md-12">
        <div class="row">
          <div class="panel panel-primary filterable">   
              <div class="panel-heading">
                  <h3 class="panel-title">SOLICITUD DE MATERIAL E INSTRUMENTAL QUIRÚRGICO</h3>      
              </div>
              <div style=" font-size: 11px" class="panel-body">
                  <form name="frmIntraoperario" id="frmIntraoperario" class="form-horizontal" role="form" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="panel panel-info contenedor-datos-generales">
                        <div class="panel-heading">
                            <h3 class="panel-title size-titulo">
                                DATOS DE LA ATENCIÓN
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="row intro-contenedor">
                                <div class="col-lg-2">
                                    <label>TIPO DE ATENCION</label>
                                    <input type="text" readonly="" value="CENTRO QUIRÚRGICO" name="tipo_atencion" id="tipo_atencion" class="form-control">
                                </div>
                                <div class="col-lg-2">
                                    <label>MODALIDAD</label>
                                    <input type="text" readonly="" value="Presencial" name="modalidad" id="modalidad" class="form-control">
                                </div>
                                <div class="col-lg-3">
                                    <label for="tipo" style="color: red;"><b>COD.SITEDS SUSALUD</b></label>
                                    <div class="">
                                        <input type="text" class="form-control"  id="cod_siteds_susalud" name="cod_siteds_susalud"  onkeypress="return esEntero(event);" style="text-transform: uppercase;border-color: red !important;border: 2px red solid !important"   maxlength="12"  pattern="[0-9]*" 
                                        value="000001234586">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label class="control-label">SERVICIO</label>
                                    <input type="text" class="form-control" readonly=""  value="REPRODUCCION HUMANA" name="servicio_procedencia" id="servicio_procedencia">
                                </div>
                                <div class="col-lg-2">
                                    <label>SALA</label>
                                    <input type="text" class="form-control" readonly="" value="SALA 1"  name="sala" id="sala" style="text-transform:uppercase;">
                                </div>
                            </div>
                            <div class="row intro-contenedor">
                                <div class="col-lg-2">
                                    <label>FECHA DE REGISTRO</label>
                                    <input type="text" class="form-control" readonly=""  value="20-03-2023" name="fecha_registro" id="fecha_registro">
                                </div>
                                <div class="col-lg-2">
                                    <label>HORA DE REGISTRO</label>
                                    <input type="text" class="form-control" readonly=""  value="14:30" name="hora_registro" id="hora_registro">
                                </div>
                                <div class="col-lg-2">
                                    <label>CÓDIGO UPSS</label>
                                    <input type="text" class="form-control" readonly=""  value="040100" name="codigo_upss" id="codigo_upss">
                                </div>
                                <div class="col-lg-3">
                                    <label>NOMBRE DE LA UPSS</label>
                                    <input type="text" class="form-control" readonly=""  value="CENTRO QUIRÚRGICO Y ANESTESIOLOGÍA" name="nombre_upss" id="nombre_upss">
                                </div>
                                <div class="col-lg-3">
                                    <label>NOMBRE DE LA IPRESS</label>
                                    <input type="text" class="form-control" readonly=""  value="00013591 - Hospital Nacional PNP Luis N. Saenz" name="nombre_ipress" id="nombre_ipress">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title size-titulo">
                                INFORMACIÓN DEL PACIENTE
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="row intro-contenedor">
                                <div class="col-lg-2">
                                    <label>DOCUMENTO DE IDENTIDAD</label>
                                    <input type="text" readonly="" value="DNI: 09924042" name="documento_identidad" id="documento_identidad" class="form-control">
                                </div>
                                <div class="col-lg-2">
                                    <label class="control-label">APELLIDO PATERNO</label>
                                    <input type="text" class="form-control" readonly=""  value="CONDOR" name="asegurado_apellido_paterno" id="asegurado_apellido_paterno">
                                </div>
                                <div class="col-lg-2">
                                    <label class="control-label">APELLIDO MATERNO</label>
                                    <input type="text" class="form-control" readonly=""  value="QUISPE" name="asegurado_apellido_materno" id="asegurado_apellido_materno">
                                </div>
                                <div class="col-lg-3">
                                    <label class="control-label">NOMBRES</label>
                                    <input type="text" class="form-control" readonly=""  value="CALIN " name="asegurado_nombres" id="asegurado_nombres">
                                </div> 
                                <div class="col-lg-3">
                                    <label>PARENTESCO</label>
                                    <input type="text" class="form-control" readonly="" value="TITULAR"  name="parentesco" id="parentesco">
                                </div>
                            </div>
                            <div class="row intro-contenedor">
                                <div class="col-lg-1">
                                    <label>F. DE NACIMIENTO</label>
                                    <input type="text" class="form-control" readonly=""  value="06/08/1985" name="fecha_nacimiento" id="fecha_nacimiento">
                                </div>
                                <div class="col-lg-1">
                                    <label>CIP</label>
                                    <input type="text" class="form-control" readonly=""  value="31554758" name="cip" id="cip">
                                </div>
                                <div class="col-lg-2">
                                    <label>GRADO</label>
                                    <input type="text" class="form-control" readonly=""  value="SUBOFICIAL DE TERCERA" name="grado" id="grado">
                                </div>
                                <div class="col-lg-2">
                                    <label>SITUACIÓN</label>
                                    <input type="text" class="form-control" readonly=""  value="ACTIVIDAD" name="situacion" id="situacion">
                                </div>
                                <div class="col-lg-1">
                                    <label>SEXO</label>
                                    <input type="text" class="form-control" readonly=""  value="M" name="sexo" id="sexo">
                                </div>
                                <div class="col-lg-1">
                                    <label>EDAD</label>
                                    <input type="text" class="form-control" readonly=""  value="38" name="edad" id="edad">
                                </div>
                                <div class="col-lg-1">
                                    <label>T. SANGRE</label>
                                    <input type="text" class="form-control" readonly=""  value="O+" name="tipo_sangre" id="tipo_sangre">
                                </div>
                                <div class="col-lg-3">
                                    <label>DIRECCIÓN</label>
                                    <input type="text" class="form-control" readonly=""  value="JESUS MARIA - AV. POLICIA NRO 126" name="direccion" id="direccion">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title size-titulo">
                                INFORMACIÓN DEL RESPONSABLE DEL REGISTRO
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="row intro-contenedor">
                                <div class="col-lg-2">
                                    <label>DOCUMENTO DE IDENTIDAD</label>
                                    <input type="text" readonly="" value="DNI: 71254863" name="documento_identidad" id="documento_identidad" class="form-control">
                                </div>
                                <div class="col-lg-2">
                                    <label class="control-label">APELLIDO PATERNO</label>
                                    <input type="text" class="form-control" readonly=""  value="ROMAN" name="asegurado_apellido_paterno" id="asegurado_apellido_paterno">
                                </div>
                                <div class="col-lg-2">
                                    <label class="control-label">APELLIDO MATERNO</label>
                                    <input type="text" class="form-control" readonly=""  value="VENTURA" name="asegurado_apellido_materno" id="asegurado_apellido_materno">
                                </div>
                                <div class="col-lg-3">
                                    <label class="control-label">NOMBRES</label>
                                    <input type="text" class="form-control" readonly=""  value="RUTTY " name="asegurado_nombres" id="asegurado_nombres">
                                </div> 
                            </div>
                            <div class="row intro-contenedor">
                                <div class="col-lg-3">
                                    <label>PROFESIÓN</label>
                                    <input type="text" class="form-control" readonly="" value="MEDICO ESPECIALISTA"  name="medico_especialista" id="medico_especialista">
                                </div>
                                <div class="col-lg-2">
                                    <label>N° COLEGIATURA</label>
                                    <input type="text" class="form-control" readonly=""  value="6118" name="colegiatura" id="colegiatura">
                                </div>
                                <div class="col-lg-2">
                                    <label>N° RNE</label>
                                    <input type="text" class="form-control" readonly=""  value="1014" name="rne" id="rne">
                                </div>
                                <div class="col-lg-2">
                                    <label>ESPECIALIDAD</label>
                                    <input type="text" class="form-control" readonly=""  value="NEUMOLOGÍA" name="especialidad" id="especialidad">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info contenedor-datos-generales">
                        <div class="panel-heading">
                            <h3 class="panel-title size-titulo">
                                PROFESIONALES QUE INTERVIENEN
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="row intro-contenedor">
                                <div class="col-lg-3" style="padding-bottom: 1rem;">
                                    <button type="button" class="btn btn-success btn-xs" style="width: 100%;" id="">Cirujanos que Intervienen Operación</button>
                                </div>
                                <div class="col-lg-12">
                                    <tr>
                                        <td><input type="text" data-toggle="tooltip" data-placement="top" value="MASCO QUISPE WILLIAMS" name="" readonly id="" class="input-sm" style="width:80%; text-align:left" /></td>
                                        <td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" data-toggle="tooltip" data-placement="top" value="MASCO QUISPE WILLIAMS" name="" readonly id="" class="input-sm" style="width:80%; text-align:left" /></td>
                                        <td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>
                                    </tr>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row" style="padding: 1.5rem 0;">
                        <div class="col-sm-10"></div>
                        <div class="col-sm-2">
                            <input onclick="validarFormulario();" type="button" value="GUARDAR" class="form-control btn btn-success" id="btnReg" style="text-align:center!important">
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