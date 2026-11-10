@extends('frontend.layouts.master')
@section('renderjs')
<script>
    
    $(document).ready(function () {
        $('#example').DataTable();
    });

    function reporteFema(id_opcion,idUser){

var fecha_ini = $('#fechaDesde').val();
var fecha_fin = $('#fechaHasta').val();
var dni = $('#nroDocumento').val();

if (fecha_ini == "")fecha_ini = 0;
if (fecha_fin == "")fecha_fin = 0;
if (dni == "")dni = 0;
location.href = 'exportar_reporte_fema/' + fecha_ini + '/' + fecha_fin + '/' + dni + '/' + id_opcion;

}
</script>
@endsection
@section('content')
<div class="container-fluid">
      <div class="panel panel-body">
        <legend style="text-align:center"></legend>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Busquedas de Atenciones FEMA</h3>
            </div>
            <div class="panel-body">
                <form id="frm_llamado_ticket" class="" method="post" action="{{ route('busca_fema_ficha')}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <div clas="row">                              
                        <div class="col-md-12"> 
                        <div class="col-md-2">
                                <div class="form-group">
                                    <label class="">N° DOCUMENTO</label>
                                    <input class="form-control input-sm" type="text" name="nroDocumento" id="nroDocumento" placeholder="" value="{{$nroDocumento}}" maxlength="8">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="">&nbsp;</label>
                                    <input class="form-control btn btn-primary input-sm" value="BUSCAR" type="submit">
                                </div>
                            </div>
                        
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="">FECHA DESDE</label>
                                    <input class="form-control input-sm datepicker" type="text" name="fechaDesde" id="fechaDesde" value="{{$fechaDesde}}" autocomplete="off">
                                </div> 
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="">FECHA HASTA</label>
                                    <input class="form-control input-sm datepicker" type="text" name="fechaHasta" id="fechaHasta" value="{{$fechaHasta}}" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <div class="col-md-4">
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Odontología">
                                            <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteFema('1','{{ Auth::user()->id }}')" ><i class="fa fa-file-excel-o" aria-hidden="true"></i> </a>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Laboratorio">
                                            <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteFema('2','{{ Auth::user()->id }}')" ><i class="fa fa-file-excel-o" aria-hidden="true"></i> </a>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Clínico General">
                                            <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteFema('3','{{ Auth::user()->id }}')" ><i class="fa fa-file-excel-o" aria-hidden="true"></i> </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="col-md-4">
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Salud Mental">
                                            <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteFema('4','{{ Auth::user()->id }}')" ><i class="fa fa-file-excel-o" aria-hidden="true"></i> </a>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Aparato Respiratorio">
                                            <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteFema('5','{{ Auth::user()->id }}')" ><i class="fa fa-file-excel-o" aria-hidden="true"></i> </a>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ginecologico">
                                            <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteFema('6','{{ Auth::user()->id }}')" ><i class="fa fa-file-excel-o" aria-hidden="true"></i> </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="col-md-4">
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cardiovascular">
                                            <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteFema('7','{{ Auth::user()->id }}')" ><i class="fa fa-file-excel-o" aria-hidden="true"></i> </a>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Oftalmologia">
                                            <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteFema('8','{{ Auth::user()->id }}')" ><i class="fa fa-file-excel-o" aria-hidden="true"></i> </a>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Otorrinolaringologico">
                                            <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteFema('9','{{ Auth::user()->id }}')" ><i class="fa fa-file-excel-o" aria-hidden="true"></i> </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Todos">
                                            <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteFema('0','{{ Auth::user()->id }}')" >
                                                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                            </a>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div> 
                    </div>
                </form>
            </div>
        </div> 
        <div class="panel panel-primary">
        <div class="panel-heading">
                <h3 class="panel-title" align="CENTER"><strong>LISTA DEL DIA - FEMA</strong></h3>
            </div>
            <div class="panel-body">
                <!--
        <div class="col-sm-12" style="border: 1px solid gray; padding-top: 10px; margin-top: 10px">
        -->

        @if($variable==1)
        <div class="col-sm-12">
          <div class="table-responsive">
            <table id="example" class="display table" cellspacing="0" width="100%">
              <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                  <!--  <th>Hora</th> -->
                    <th>Tipo Doc</th>
                    <th>Núm Doc</th>
                    <th>Apellidos y Nombres</th>
                    <th>Edad</th>
                    <th>Proceso</th>
                    <th>Sub-Proceso</th>
                    <th>Establecimiento</th>
                    <th>Personal de Salud</th>
                    <th>Atención</th>
                    <th>Resultado Examenes</th>
                    <th>Epidemiologia</th>
                    <th>Ver Atención</th>
                    @if($fiscalisador->flagfiscalizador == 1)
                    <th>Anular Ficha</th>
                    <!--<th>Anular</th>-->
                    @endif
                    @if($fiscalisador->flagfiscalizador == 1)
                    <th>Anular Triaje</th>
                    <!--<th>Anular</th>-->
                    @endif
                    @if($fiscalisador->flagfiscalizador == 1)
                    <th>Anular Atención</th>
                    <!--<th>Anular</th>-->
                    @endif
                </tr>
              </thead>
              <tbody>
        @else
        <div class="col-sm-12">
          <div class="table-responsive">
            <table id="example" class="display table" cellspacing="0" width="100%">
              <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                  <!--  <th>Hora</th> -->
                    <th>Tipo Doc</th>
                    <th>Núm Doc</th>
                    <th>Apellidos y Nombres</th>
                    <th>Edad</th>
                    <th>Proceso</th>
                    <th>Sub-Proceso</th>
                    <th>Establecimiento</th>
                    <th>Personal de Salud</th>
                    <th>Atención</th>
                    <th>Resultado Examenes</th>
                    <th>Epidemiologia</th>
                    <th>Ver Atención</th>
                    @if($fiscalisador->flagfiscalizador == 1)
                    <th>Anular Ficha</th>
                    <!--<th>Anular</th>-->
                    @endif
                    @if($fiscalisador->flagfiscalizador == 1)
                    <th>Anular Triaje</th>
                    <!--<th>Anular</th>-->
                    @endif
                    @if($fiscalisador->flagfiscalizador == 1)
                    <th>Anular Atención</th>
                    <!--<th>Anular</th>-->
                    @endif
                   
                </tr>
              </thead>
              <tbody>
                @foreach($fichas as $f)
                @if($f->estado == 1)
                <tr>
                    <td>{{$f->id}}</td>
                    <td>{{date('d/m/Y', strtotime($f->fecha_reg))}}</td>
                  <!--  <td>{{$f->hora_reg}}</td>-->

                    @if($f->tipo_doc_ident == 1)
                        <td>DNI</td>
                    @else
                        <td>{{$f->tipo_doc_ident}}</td>
                    @endif
                    <td>{{$f->nro_doc_ident}}</td>
                    <td>{{$f->paterno}} {{$f->materno}} {{$f->nombre}}</td>
                    <td>{{$f->edad}}</td>
                    <td>{{$f->proceso}}</td>
                    <td>{{$f->tipo_proceso}}</td>
                    <td>{{$f->establecimiento}}</td>
                    <td>{{$f->usu_paterno}} {{$f->usu_materno}} {{$f->usu_nombre}}</td>
                    <td>
                        @if($f->id_proceso != 0)
                        <table>
                            <tr>
                                <th> 
                                    @if($f->odontologica == 1)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Odontología" >
                                            <a class="btn btn-sm btn-info" disabled = "disabled" ><i class="fa fa-edit"  ></i></a>
                                        </div>
                                    @else
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Odontología">
                                            <a href="/fema_atencion/{{$f->id}}/{{$f->id_proceso}}/1" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                        </div>
                                    @endif
                                </th>
                                <th> 
                                    @if($f->clinico == 1)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Clínico General">
                                            <a class="btn btn-sm btn-info" disabled = "disabled" ><i class="fa fa-edit"></i></a>
                                        </div>
                                    @else
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Clínico General">
                                            <a href="/fema_atencion/{{$f->id}}/{{$f->id_proceso}}/3" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                        </div>
                                    @endif
                                </th>
                                <th> 
                                    @if($f->mental == 1)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Salud Mental">
                                            <a class="btn btn-sm btn-info" disabled = "disabled" ><i class="fa fa-edit"></i></a>
                                        </div>
                                    @else
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Salud Mental">
                                            <a href="/fema_atencion/{{$f->id}}/{{$f->id_proceso}}/4" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                        </div>
                                    @endif
                                </th>
                                <th> 
                                    @if($f->oftalmologia == 1)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Oftalmologia">
                                            <a class="btn btn-sm btn-info" disabled = "disabled" ><i class="fa fa-edit"></i></a>
                                        </div>
                                    @else
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Oftalmologia">
                                            <a href="/fema_atencion/{{$f->id}}/{{$f->id_proceso}}/8" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                        </div>
                                    @endif
                                </th>
                            </tr>
                            <tr>
                                @if($f->sexo == 'M')
                                    <th>
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ginecologico">
                                            <a  class="btn btn-sm btn-warning" disabled = "disabled" ><i class="fa fa-edit"></i></a>
                                        </div>
                                    </th>
                                @else
                                <th> 
                                    @if($f->ginecologico == 1)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ginecologico">
                                            <a class="btn btn-sm btn-info" disabled = "disabled" ><i class="fa fa-edit"></i></a>
                                        </div>
                                    @else
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ginecologico">
                                            <a href="/fema_atencion/{{$f->id}}/{{$f->id_proceso}}/6" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                        </div>
                                    @endif
                                </th>
                                @endif
                                <th> 
                                  @if($f->edad < 40)
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cardiovascular">
                                        <a  class="btn btn-sm btn-warning" disabled = "disabled" ><i class="fa fa-edit"></i></a>
                                    </div>
                                  @else
                                    @if($f->atencioncardiologia == 1)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cardiovascular">
                                            <a class="btn btn-sm btn-info" disabled = "disabled" ><i class="fa fa-edit"></i></a>
                                        </div>
                                    @else
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cardiovascular">
                                                <a href="/fema_preatencion/{{$f->id}}/{{$f->id_proceso}}/7" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                            </div>
                                    @endif
                                  @endif
                                </th>
                                <th> 
                                    @if($f->atencionrespiratorio == 1)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Aparato Respiratorio">
                                            <a class="btn btn-sm btn-info" disabled = "disabled" ><i class="fa fa-edit"></i></a>
                                        </div>
                                    @else
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Aparato Respiratorio">
                                            <a href="/fema_preatencion/{{$f->id}}/{{$f->id_proceso}}/5" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                        </div>
                                    @endif
                                </th>

                                <th> 
                                    @if($f->atencionlaboratorio == 1)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Laboratorio">
                                            <a class="btn btn-sm btn-info" disabled = "disabled" ><i class="fa fa-edit"></i></a>
                                        </div>
                                    @else
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Laboratorio">
                                            <a href="/fema_preatencion/{{$f->id}}/{{$f->id_proceso}}/2" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                        </div>
                                    @endif
                                </th>
                            </tr>
                            <tr>
                                <th> 
                                @if($f->otorrinolaringologia == 1)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Otorrinolaringologico">
                                            <a class="btn btn-sm btn-info" disabled = "disabled" ><i class="fa fa-edit"></i></a>
                                        </div>
                                    @else
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="Otorrinolaringologico">
                                            <a href="/fema_atencion/{{$f->id}}/{{$f->id_proceso}}/9" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                        </div>
                                    @endif
                                </th>
                            </tr>
                        </table>    
                        @endif
                    </td>
                    <td>
                        @if($f->id_proceso != 0)
                        <table>
                            <tr>
                                <th> 
                                    @if($f->atencioncardiologia == 0)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cardiovascular">
                                            <a class="btn btn-sm btn-info" disabled = "disabled" ><i class="fa fa-edit"></i></a>
                                        </div>
                                    @else
                                        @if($f->cardiovascular == 0)
                                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cardiovascular">
                                                        <a href="/fema_atencion/{{$f->id}}/{{$f->id_proceso}}/7" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                                    </div>
                                            @else 
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cardiovascular">
                                                <a class="btn btn-sm btn-primary" disabled = "disabled"><i class="fa fa-edit"></i></a>
                                            </div>
                                            @endif 
                                    @endif
                                </th>

                                <th> 
                                    @if($f->atencionrespiratorio == 0)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Aparato Respiratorio">
                                            <a class="btn btn-sm btn-info" disabled = "disabled" ><i class="fa fa-edit"></i></a>
                                        </div>
                                    @else
                                        @if($f->respiratorio == 0)
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="Aparato Respiratorio">
                                                <a href="/fema_atencion/{{$f->id}}/{{$f->id_proceso}}/5" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                            </div>
                                        @else 
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="Aparato Respiratorio">
                                                <a class="btn btn-sm btn-primary" disabled = "disabled"><i class="fa fa-edit"></i></a>
                                            </div>
                                        @endif 
                                    @endif
                                </th>

                                <th> 
                                    @if($f->atencionlaboratorio == 0)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Laboratorio">
                                            <a class="btn btn-sm btn-info" disabled = "disabled" ><i class="fa fa-edit"></i></a>
                                        </div>
                                    @else
                                        @if($f->laboratorio == 0)
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="Laboratorio">
                                                <a href="/fema_atencion/{{$f->id}}/{{$f->id_proceso}}/2" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                            </div>
                                        @else 
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="Laboratorio">
                                                <a class="btn btn-sm btn-primary" disabled = "disabled"><i class="fa fa-edit"></i></a>
                                            </div>
                                        @endif 
                                    @endif
                                </th>
                            </tr>
                            
                        </table>    
                        @endif
                    </td>
                    @if($f->yaderivado == '0')
                    @if($f->cie10 == '1' )
                            @if($f->sexo == 'M' && $f->validacion == '5')
                                <td><a href="/epidemiologia/{{$f->id}}" class="btn btn-sm btn-success">Epidemiologia</a></td>
                            @elseif($f->sexo == 'F' && $f->validacion == '6')
                                <td><a href="/epidemiologia/{{$f->id}}" class="btn btn-sm btn-success">Epidemiologia</a></td>
                            @else
                                <td><a class="btn btn-sm btn-warning" disabled = "disabled">Epidemiologia</a></td>
                            @endif
                        @else
                        <td></td>
                        @endif
                    @else                   
                        <td><a class="btn btn-sm btn-info" disabled = "disabled">Epidemiologia</a></td>
                    @endif
                    <td>
                        @if($f->id_proceso != 0)
                        <table>
                            <tr>
                                <td>
                                    @if($f->odontologica == 0)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Odontología">
                                            <a class="btn btn-sm btn-info" disabled = "disabled"><i class="fa fa-search"></i></a>
                                        </div>
                                    @else
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Odontología">
                                            <a href="/fema_atencion/{{$f->id}}/{{$f->id_proceso}}/1" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></a>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($f->clinico == 0)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Clínico General">
                                            <a class="btn btn-sm btn-info" disabled = "disabled"><i class="fa fa-search"></i></a>
                                        </div>
                                    @else
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Clínico General">
                                            <a href="/fema_atencion/{{$f->id}}/{{$f->id_proceso}}/3" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></a>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($f->mental == 0)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Salud Mental">
                                            <a class="btn btn-sm btn-info" disabled = "disabled"><i class="fa fa-search"></i></a>
                                        </div>
                                    @else
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Salud Mental">
                                            <a href="/fema_atencion/{{$f->id}}/{{$f->id_proceso}}/4" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></a>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($f->oftalmologia == 0)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Oftalmologia">
                                            <a class="btn btn-sm btn-info" disabled = "disabled"><i class="fa fa-search"></i></a>
                                        </div>
                                    @else
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Oftalmologia">
                                            <a href="/fema_atencion/{{$f->id}}/{{$f->id_proceso}}/8" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></a>
                                        </div>
                                    @endif
                                </td>

                            </tr>
                            <tr>
                                @if($f->sexo == 'M')
                                <td>
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ginecologico">
                                    <a class="btn btn-sm btn-warning" disabled = "disabled"><i class="fa fa-search"></i></a>
                                    </div>
                                </td>
                                @else
                                <td>
                                     @if($f->ginecologico == 0)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ginecologico">
                                            <a class="btn btn-sm btn-info" disabled = "disabled"><i class="fa fa-search"></i></a>
                                        </div>
                                    @else
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ginecologico">
                                            <a href="/fema_atencion/{{$f->id}}/{{$f->id_proceso}}/6" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></a>
                                        </div>
                                    @endif
                                </td>
                                @endif
                                <td>
                                    @if($f->cardiovascular == 0)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cardiovascular">
                                            <a class="btn btn-sm btn-info" disabled = "disabled"><i class="fa fa-search"></i></a>
                                        </div>
                                    @else
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cardiovascular">
                                            <a href="/fema_atencion/{{$f->id}}/{{$f->id_proceso}}/7" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></a>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($f->respiratorio == 0)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Aparato Respiratorio">
                                            <a class="btn btn-sm btn-info" disabled = "disabled"><i class="fa fa-search"></i></a>
                                        </div>
                                    @else
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Aparato Respiratorio">
                                            <a href="/fema_atencion/{{$f->id}}/{{$f->id_proceso}}/5" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></a>
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    @if($f->laboratorio == 0)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Laboratorio">
                                            <a class="btn btn-sm btn-info" disabled = "disabled"><i class="fa fa-search"></i></a>
                                        </div>
                                    @else
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Laboratorio">
                                            <a href="/fema_atencion/{{$f->id}}/{{$f->id_proceso}}/2" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th> 
                                @if($f->otorrinolaringologia == 0)
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Otorrinolaringologico">
                                            <a class="btn btn-sm btn-info" disabled = "disabled"><i class="fa fa-search"></i></a>
                                        </div>
                                    @else
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="Otorrinolaringologico">
                                            <a href="/fema_atencion/{{$f->id}}/{{$f->id_proceso}}/9" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></a>
                                        </div>
                                    @endif
                                </th>
                            </tr>
                           
                        </table>    
                        @endif
                    </td>
                    <td>
                        @if($fiscalisador->flagfiscalizador == 1)
                            <table>
                                <tr>
                                    <th> 
                                    
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Anular Ficha">
                                                <a href="/anular_ficha_completa/{{$f->id}}/{{$f->id_proceso}}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                            </div>
                                       
                                    </th>
                                </tr>
                            
                            </table>  
                        @endif 
                    </td>
                    <td>
                        @if($fiscalisador->flagfiscalizador == 1)
                            <table>
                                <tr>
                                    <th> 
                                   
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Anular Triaje">
                                                <a href="/anular_triaje/{{$f->id}}/{{$f->id_proceso}}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                            </div>
                                        
                                    </th>
                                </tr>
                            
                            </table>  
                        @endif 
                    </td>
                    <td>
                        @if($fiscalisador->flagfiscalizador == 1)
                            <table>
                                    <tr>
                                        <td>
                                            @if($f->odontologica == 0)
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Odontología">
                                                    <a class="btn btn-sm btn-info" disabled = "disabled"><i class="fa fa-search"></i></a>
                                                </div>
                                            @else
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Odontología">
                                                    <a href="/fema_atencion_anular/{{$f->id}}/{{$f->id_proceso}}/1" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($f->clinico == 0)
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Clínico General">
                                                    <a class="btn btn-sm btn-info" disabled = "disabled"><i class="fa fa-search"></i></a>
                                                </div>
                                            @else
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Clínico General">
                                                    <a href="/fema_atencion_anular/{{$f->id}}/{{$f->id_proceso}}/3" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($f->mental == 0)
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Salud Mental">
                                                    <a class="btn btn-sm btn-info" disabled = "disabled"><i class="fa fa-search"></i></a>
                                                </div>
                                            @else
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Salud Mental">
                                                    <a href="/fema_atencion_anular/{{$f->id}}/{{$f->id_proceso}}/4" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($f->oftalmologia == 0)
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Oftalmologia">
                                                    <a class="btn btn-sm btn-info" disabled = "disabled"><i class="fa fa-search"></i></a>
                                                </div>
                                            @else
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Oftalmologia">
                                                    <a href="/fema_atencion_anular/{{$f->id}}/{{$f->id_proceso}}/8" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                                </div>
                                            @endif
                                        </td>

                                    </tr>
                                    <tr>
                                        @if($f->sexo == 'M')
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ginecologico">
                                            <a class="btn btn-sm btn-warning" disabled = "disabled"><i class="fa fa-search"></i></a>
                                            </div>
                                        </td>
                                        @else
                                        <td>
                                            @if($f->ginecologico == 0)
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ginecologico">
                                                    <a class="btn btn-sm btn-info" disabled = "disabled"><i class="fa fa-search"></i></a>
                                                </div>
                                            @else
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ginecologico">
                                                    <a href="/fema_atencion_anular/{{$f->id}}/{{$f->id_proceso}}/6" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                                </div>
                                            @endif
                                        </td>
                                        @endif
                                        <td>
                                            @if($f->cardiovascular == 0)
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cardiovascular">
                                                    <a class="btn btn-sm btn-info" disabled = "disabled"><i class="fa fa-search"></i></a>
                                                </div>
                                            @else
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cardiovascular">
                                                    <a href="/fema_atencion_anular/{{$f->id}}/{{$f->id_proceso}}/7" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($f->respiratorio == 0)
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Aparato Respiratorio">
                                                    <a class="btn btn-sm btn-info" disabled = "disabled"><i class="fa fa-search"></i></a>
                                                </div>
                                            @else
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Aparato Respiratorio">
                                                    <a href="/fema_atencion_anular/{{$f->id}}/{{$f->id_proceso}}/5" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            @if($f->laboratorio == 0)
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Laboratorio">
                                                    <a class="btn btn-sm btn-info" disabled = "disabled"><i class="fa fa-search"></i></a>
                                                </div>
                                            @else
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Laboratorio">
                                                    <a href="/fema_atencion_anular/{{$f->id}}/{{$f->id_proceso}}/2" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> 
                                        @if($f->otorrinolaringologia == 0)
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Otorrinolaringologico">
                                                    <a class="btn btn-sm btn-info" disabled = "disabled"><i class="fa fa-search"></i></a>
                                                </div>
                                            @else
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="Otorrinolaringologico">
                                                    <a href="/fema_atencion_anular/{{$f->id}}/{{$f->id_proceso}}/9" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                                </div>
                                            @endif
                                        </th>
                                    </tr>
                                
                            </table>  
                        @endif 
                    </td>

                  <!--  <td><button class="btn btn-sm btn-danger">A</button></td>  -->
                </tr>
                @endif
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        @endif
        </div>
        </div>

             

      </div>
</div>

@include('frontend.includes.footer')
@endsection
