@extends('frontend.layouts.master')
@section('renderjs')
<script>
    
    $(document).ready(function () {
        $('#example').DataTable();
    });
  
</script>
@endsection
@section('content')
<div class="container-fluid">
      <div class="panel panel-body">
        <legend style="text-align:center">Cierre de Fema</legend>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Busquedas de Llamadas</h3>
            </div>
            <div class="panel-body">
                <form id="frm_llamado_ticket" class="" method="post" action="{{ route('busca_fema_cierre')}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div clas="row">                              
                        <div class="col-md-12">  
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="">N° DOCUMENTO</label>
                                    <input class="form-control input-sm" type="text" name="nroDocumento" id="nroDocumento" placeholder="" value="{{$nroDocumento}}" maxlength="8">
                                </div>
                            </div>
                        </div>   
                    </div>

                    <div clas="row">     
                        <div class="col-md-12">                     
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="">PROCESO</label>
                                    <input class="form-control input-sm" type="text" name="idProceso" id="idProceso" placeholder="" value="{{$idProceso}}" maxlength="10">
                                </div> 
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="">SUB PROCESO</label>
                                    <input class="form-control input-sm" type="text" name="idSubProceso" id="idSubProceso" placeholder="" value="{{$idSubProceso}}" maxlength="10">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="">N° FICHA</label>
                                    <input class="form-control input-sm" type="text" name="nroFicha" id="nroFicha" placeholder="" value="{{$nroFicha}}" maxlength="8">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="">&nbsp;</label>
                                    <input class="form-control btn btn-primary input-sm" value="BUSCAR" type="submit">
                                </div>
                            </div>
                        </div>   
                    </div>
                </form>
            </div>
        </div>     
        
        <div class="col-sm-12" style="border: 1px solid gray; padding-top: 10px; margin-top: 10px">
          <!-- <legend>Historias Clínicas Enviadas a los Servicios</legend> -->
          <div class="table-responsive">
            <table id="example" class="display table" cellspacing="0" width="100%">
              <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha Apertura</th>
                    <th>Hora</th>
                    <th>Tipo Doc</th>
                    <th>Num Doc</th>
                    <th>Apellidos y Nombres</th>
                    <th>Proceso</th>
                    <th>Sub-Proceso</th>
                    <th><center>Ficha Derivacion</center></th>
                    <th><center>Levantar Diagnostico</center></th>
                    <th><center>Concluir Ficha</center></th>
                </tr>
              </thead>
              <tbody>
                @foreach($fichas as $f)
                    <tr>
                        @if($f->sexo == 'M' && $f->validacionfiltro == '1' && $f->atencionrespiratorio == '1')<!-- valida si la ficha esta concluida por sexo --> 
                            @if($f->cie10 == '0')   <!-- valida si tiene no tenga diagnostico con cie10 -->
                                <td>{{$f->id}}</td>
                                <td>{{date('d/m/Y', strtotime($f->fecha_reg))}}</td>
                                <td>{{$f->hora_reg}}</td>
                                <td>{{$f->tipo_doc_ident}}</td>
                                <td>{{$f->nro_doc_ident}}</td>
                                <td>{{$f->paterno}} {{$f->materno}} {{$f->nombre}}</td>
                                <td>{{$f->proceso}}</td>
                                <td>{{$f->tipo_proceso}}</td>
                                <td></td>
                                <td></td>
                                <td> 
                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cerrar Ficha">
                                 <center><a href="/cierre/{{$f->nro_doc_ident}}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> </a></center>
                                </div>  
                                </td>
                            @else
                                @if($f->yaderivado == '1')  <!-- valida que si ha sido derivado -->
                                    @if($f->tatuaje == '0')  <!-- valida que la derivacion no sea por tatuaje -->
                                        <td>{{$f->id}}</td>
                                        <td>{{date('d/m/Y', strtotime($f->fecha_reg))}}</td>
                                        <td>{{$f->hora_reg}}</td>
                                        <td>{{$f->tipo_doc_ident}}</td>
                                        <td>{{$f->nro_doc_ident}}</td>
                                        <td>{{$f->paterno}} {{$f->materno}} {{$f->nombre}}</td>
                                        <td>{{$f->proceso}}</td>
                                        <td>{{$f->tipo_proceso}}</td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Derivaciones">
                                            <center><a href="/verderivacion/{{$f->nro_doc_ident}}" class="btn btn-sm btn-primary"> <i class="fa fa-search"></i></a></center>
                                            </div> 
                                        </td>
                                        <td></td>
                                        <td> 
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cerrar Ficha">
                                        <center><a href="/cierre/{{$f->nro_doc_ident}}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> </a></center>
                                        </div>  
                                        </td>    
                                    @else
                                        @if($f->levantartatuaje == '0') <!-- valida que no se ha levantado la derivacion -->
                                            <td>{{$f->id}}</td>
                                            <td>{{date('d/m/Y', strtotime($f->fecha_reg))}}</td>
                                            <td>{{$f->hora_reg}}</td>
                                            <td>{{$f->tipo_doc_ident}}</td>
                                            <td>{{$f->nro_doc_ident}}</td>
                                            <td>{{$f->paterno}} {{$f->materno}} {{$f->nombre}}</td>
                                            <td>{{$f->proceso}}</td>
                                            <td>{{$f->tipo_proceso}}</td>
                                            <td>
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Derivaciones">
                                                <center><a href="/verderivacion/{{$f->nro_doc_ident}}" class="btn btn-sm btn-primary"> <i class="fa fa-search"></i></a></center>
                                                </div> 
                                            </td>
                                            <td>
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Levantar Observaciones">
                                                <center><a href="/levantarobservacion/{{$f->nro_doc_ident}}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> </a></center>
                                                </div>
                                            </td>
                                            <td> 
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cerrar Ficha">
                                                <center><a class="btn btn-sm btn-warning"> <i class="fa fa-edit"></i> </a></center>
                                                </div>  
                                            </td> 
                                        @else   <!-- valida que si ha levantado la derivacion -->
                                            <td>{{$f->id}}</td>
                                            <td>{{date('d/m/Y', strtotime($f->fecha_reg))}}</td>
                                            <td>{{$f->hora_reg}}</td>
                                            <td>{{$f->tipo_doc_ident}}</td>
                                            <td>{{$f->nro_doc_ident}}</td>
                                            <td>{{$f->paterno}} {{$f->materno}} {{$f->nombre}}</td>
                                            <td>{{$f->proceso}}</td>
                                            <td>{{$f->tipo_proceso}}</td>
                                            <td>
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Derivaciones">
                                                <center><a href="/verderivacion/{{$f->nro_doc_ident}}" class="btn btn-sm btn-primary"> <i class="fa fa-search"></i></a></center>
                                                </div> 
                                            </td>
                                            <td>
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Levantar Observaciones">
                                                <center><a class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> </a></center>
                                                </div>
                                            </td>
                                            <td> 
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cerrar Ficha">
                                                <center><a href="/cierre/{{$f->nro_doc_ident}}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> </a></center>
                                                </div>  
                                            </td> 
                                        @endif
                                    @endif
                                @else 
                                @endif    
                            @endif
                        @elseif($f->sexo == 'F' && $f->validacionfiltro == '1' )<!-- valida si la ficha esta concluida por sexo--> 
                            @if($f->cie10 == '0')   <!-- valida si tiene no tenga diagnostico con cie10 -->
                                <td>{{$f->id}}</td>
                                <td>{{date('d/m/Y', strtotime($f->fecha_reg))}}</td>
                                <td>{{$f->hora_reg}}</td>
                                <td>{{$f->tipo_doc_ident}}</td>
                                <td>{{$f->nro_doc_ident}}</td>
                                <td>{{$f->paterno}} {{$f->materno}} {{$f->nombre}}</td>
                                <td>{{$f->proceso}}</td>
                                <td>{{$f->tipo_proceso}}</td>
                                <td></td>
                                <td></td>
                                <td> 
                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cerrar Ficha">
                                <center><a href="/cierre/{{$f->nro_doc_ident}}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> </a></center>
                                </div>  
                                </td>
                            @else
                                @if($f->yaderivado == '1')  <!-- valida que si ha sido derivado -->
                                    @if($f->tatuaje == '0')  <!-- valida que la derivacion no sea por tatuaje -->
                                        <td>{{$f->id}}</td>
                                        <td>{{date('d/m/Y', strtotime($f->fecha_reg))}}</td>
                                        <td>{{$f->hora_reg}}</td>
                                        <td>{{$f->tipo_doc_ident}}</td>
                                        <td>{{$f->nro_doc_ident}}</td>
                                        <td>{{$f->paterno}} {{$f->materno}} {{$f->nombre}}</td>
                                        <td>{{$f->proceso}}</td>
                                        <td>{{$f->tipo_proceso}}</td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Derivaciones">
                                            <center><a href="/verderivacion/{{$f->nro_doc_ident}}" class="btn btn-sm btn-primary"> <i class="fa fa-search"></i></a></center>
                                            </div> 
                                        </td>
                                        <td></td>
                                        <td> 
                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cerrar Ficha">
                                        <center><a href="/cierre/{{$f->nro_doc_ident}}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> </a></center>
                                        </div>  
                                        </td>    
                                    @else
                                        @if($f->levantartatuaje == '0')
                                            <td>{{$f->id}}</td>
                                            <td>{{date('d/m/Y', strtotime($f->fecha_reg))}}</td>
                                            <td>{{$f->hora_reg}}</td>
                                            <td>{{$f->tipo_doc_ident}}</td>
                                            <td>{{$f->nro_doc_ident}}</td>
                                            <td>{{$f->paterno}} {{$f->materno}} {{$f->nombre}}</td>
                                            <td>{{$f->proceso}}</td>
                                            <td>{{$f->tipo_proceso}}</td>
                                            <td>
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Derivaciones">
                                                <center><a href="/verderivacion/{{$f->nro_doc_ident}}" class="btn btn-sm btn-primary"> <i class="fa fa-search"></i></a></center>
                                                </div> 
                                            </td>
                                            <td>
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Levantar Observaciones">
                                                <center><a href="/levantarobservacion/{{$f->nro_doc_ident}}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> </a></center>
                                                </div>
                                            </td>
                                            <td> 
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cerrar Ficha">
                                                <center><a class="btn btn-sm btn-warning"> <i class="fa fa-edit"></i> </a></center>
                                                </div>  
                                            </td> 
                                        @else
                                            <td>{{$f->id}}</td>
                                            <td>{{date('d/m/Y', strtotime($f->fecha_reg))}}</td>
                                            <td>{{$f->hora_reg}}</td>
                                            <td>{{$f->tipo_doc_ident}}</td>
                                            <td>{{$f->nro_doc_ident}}</td>
                                            <td>{{$f->paterno}} {{$f->materno}} {{$f->nombre}}</td>
                                            <td>{{$f->proceso}}</td>
                                            <td>{{$f->tipo_proceso}}</td>
                                            <td>
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Derivaciones">
                                                <center><a href="/verderivacion/{{$f->nro_doc_ident}}" class="btn btn-sm btn-primary"> <i class="fa fa-search"></i></a></center>
                                                </div> 
                                            </td>
                                            <td>
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Levantar Observaciones">
                                                <center><a class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a></center>
                                                </div>
                                            </td>
                                            <td> 
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cerrar Ficha">
                                                <center><a href="/cierre/{{$f->nro_doc_ident}}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> </a></center>
                                                </div>  
                                            </td> 
                                        @endif
                                    @endif
                                @else 

                                @endif    
                            @endif
                        @else
                            
                        @endif
                    </tr> 
                @endforeach
                
              </tbody>
            </table>
          </div>
        </div>
        
      </div>
</div>

@include('frontend.includes.footer')
@endsection