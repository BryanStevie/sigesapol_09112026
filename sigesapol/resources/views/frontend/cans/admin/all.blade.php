@extends('frontend.layouts.master')
<style>

    .bg-maroon {
      color: #fff;
      background-color: #d81b60;
      border-color: #46b8da;
    }

    .bg-red {
      color: #fff;
      background-color: #dd4b39;
      border-color: #46b8da;
    }

    .bg-orange {
      color: #fff;
      background-color: #ff851b;
      border-color: #46b8da;
    }

    .bg-purple {
      color: #fff;
      background-color: #605ca8;
      border-color: #46b8da;
    }

    .bg-black {
      color: #fff;
      background-color: #111;
      border-color: #46b8da;
    }

    .bg-blue {
      color: #fff;
      background-color: #0073b7;
      border-color: #46b8da;
    }

    .bg-olive {
      color: #fff;
      background-color: #3d9970;
      border-color: #46b8da;
    }

    .bg-navy {
      color: #fff;
      background-color: #001f3f;
      border-color: #46b8da;
    }
    

</style>
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">Cuadro Anual de Necesidad CAN</h3>
            </div>
            <div class="panel-body">
                <?php if ($permiso->crear == 1): ?>
                <a href="/create_can" class="btn btn-primary"><i class="fa fa-plus"></i> Nuevo </a>
                <?php endif; ?>
                <br /><br />
                <div class="table-responsive">
                    <?php $x=1; ?>
                    <table class="table" id="tblcans">
                        <thead>
                            <tr>
                                <th rowspan="2">#</th>
                                <th rowspan="2">Descripcion</th>
                                <th rowspan="2">CAN</th>
                                <th colspan="6"><p align="center">Reportes</p></th>
                                
                            </tr>
                            <tr>
                                <th><p align="center">Nacional</p></th>                            
                                <th><p align="center">Establecimiento</p></th>
                                <th><p align="center">Servicios</p></th>
                                <th><p align="center">Nivel</p></th>
                                <th><p align="center">Tipo</p></th>

                            </tr>
                        </thead>
                        <tbody>
                        @foreach($cans as $can)
                            <tr>
                                <td><br/>{{$x++}}</td>
                                <td><br/>@if($can->estado_activo==1)<a data-toggle="tooltip" title="Descripcion" href="/edit_can/{{$can->id}}">{!! $can->nombre_can !!}</a>@else {!! $can->nombre_can !!} @endif</td>
                                <td>
                                    <a data-toggle="tooltip" title="Por IPRESS!" href="/monitorear_can/{{$can->id}}" class='btn btn-md bg-maroon btn-flat margin'><i class="fa fa-fw fa-industry "></i></a>
                                </td> 
                                <td>
                                    <a data-toggle="tooltip" title="Producto Farmaceutico Nacional!" href="/consolidado_nacional_tipo/{{$can->id}}/1" class='btn btn-md bg-primary btn-flat margin'><i class="fa fa-medkit"></i></a>
                                    <a data-toggle="tooltip" title="Dispositivo Medico Nacional!" href="/consolidado_nacional_tipo/{{$can->id}}/2" class='btn btn-md bg-primary btn-flat margin'><i class="fa fa-stethoscope"></i></a> 
                                </td>
                                <td>
                                    <a data-toggle="tooltip" title="Producto Farmaceutico por Establecimientos!" href="/consolidado_x_establecimiento_and_tp/{{$can->id}}/1" class='btn btn-md bg-purple btn-flat margin'><i class="fa fa-medkit"></i></a>
                                    <a data-toggle="tooltip" title="Dispositivo Medico por Establecimientos!" href="/consolidado_x_establecimiento_and_tp/{{$can->id}}/2" class='btn btn-md bg-purple btn-flat margin'><i class="fa fa-stethoscope"></i></a> 
                                </td>
                                <td>
                                    <a data-toggle="tooltip" title="Producto Farmaceutico por Servicio!" href="/consolidado_x_establecimiento_and_tp/{{$can->id}}/1" class='btn btn-md bg-navy btn-flat margin'><i class="fa fa-medkit"></i></a>
                                    <a data-toggle="tooltip" title="Dispositivo Medico por Servicio!" href="/consolidado_x_establecimiento_and_tp/{{$can->id}}/2" class='btn btn-md bg-navy btn-flat margin'><i class="fa fa-stethoscope"></i></a> 
                                </td>
                                <td><a data-toggle="tooltip" title="Consolidado por Nivel!" href="/nivel_total/{{$can->id}}" class='btn btn-md bg-orange btn-flat margin'><i class="fa fa-fw fa-level-up "></i></a>
                                </td>
                                
                                <td>                            
                                    <a data-toggle="tooltip" title="Establecimientos por Tipo (Productos Farmaceutico / Dispositivo Medico)  !" href="/establecimientos_can_tipo/{{$can->id}}" class='btn btn-md bg-olive btn-flat margin'><i class="fa fa-fw fa-flask"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>    
                </div>        
            </div>    
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
