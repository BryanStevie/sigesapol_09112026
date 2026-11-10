@extends('frontend.layouts.master')
<?php
    use Carbon\Carbon;

    $date = Carbon::now();
            $fecha = $date->now();       
            $fechaFin = Carbon::parse($cans[0]->fin_first_login);
                                         
            $fechaActual = Carbon::parse($fecha);
            
            if($fechaActual<=$fechaFin)
                $diasDiferencia = $fechaActual->diffInMinutes($fechaFin);
            else
                $diasDiferencia =0;           

    ?>
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

</style>
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">Cuadro Anual de Necesidad CAN</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <?php $x=1; ?>
                    <table class="table" id="tblcans">
                        <thead>
                            <tr>
                            <th>#</th>
                            <th>Descripcion</th>
                            <!--th>Actualizado</th-->                            
                            @if (Auth::user()->tipo_registro==2 )
                                <th>

                                    Med./
                                    
                                    Disp.
                                </th>                                
                            @endif
                            <th>Descargar</th>
                            <th>Observ.</th>
                            <th>Responsables</th>
                            @if(Auth::user()->nivel_user==1)
                            <th>Consultorios</th>
                            @endif
                            <th>Ver/Subir PDF CAN</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                            <?php $x=1; ?>
                            @foreach($cans as $can)
                                @if($can->id==1)
                                    <tr>
                                        <td><?php echo $x++;?></td>
                                        @if(Auth::user()->nivel_user!=1)   
                                            <td><a href="{!! route('farmacia.listar_distribucion', ['can_id'=>$can->id]) !!}"> CAN2020 - Ratificacion Julio ,2020</a></td>
                                        @else
                                            <td> CAN2020 - Ratificacion Julio ,2020</a></td>
                                        @endif
                                        <!--td></td-->
                                        <td>
                                            @if(Auth::user()->nivel_user==1)
                                                
                                                    @if ($can->id==1 )                                                        
                                                        @if ($can->medicamento_cerrado==1 )
                                                            @if ($diasDiferencia!=0 )
                                                                    <a href="{!! route('farmacia.cargar_medicamentos_rectificacion', ['can_id'=>$can->id,'tipo'=>1]) !!}" class='btn bg-maroon btn-md'><i class="fa fa-medkit"></i></a>
                                                            @else
                                                                    <a href="#" disabled class='btn bg-maroon btn-md'><i class="fa fa-medkit"></i></a>
                                                            @endif
                                                        @else
                                                                <a href="#" disabled class='btn bg-maroon btn-md'><i class="fa fa-medkit"></i></a>
                                                        @endif
                                                    @endif
                                                
                                                @if ($can->id==1 )                                                    
                                                    @if ($can->dispositivo_cerrado==1)
                                                        @if ($diasDiferencia!=0 )
                                                                    <a href="{!! route('farmacia.cargar_medicamentos_rectificacion', ['can_id'=>$can->id,'tipo'=>2]) !!}" class='btn bg-maroon btn-md'><i class="fa fa-stethoscope"></i></a>
                                                        @else
                                                                    <a href="#" disabled class='btn bg-maroon btn-md'><i class="fa fa-stethoscope"></i></a>
                                                        @endif
                                                    @else
                                                                <a href="#" disabled class='btn bg-maroon btn-md'><i class="fa fa-stethoscope"></i></a>
                                                    @endif
                                                @endif
                                            @else
                                                
                                                    @if ($can->id==1 )    
                                                        @if ($can->medicamento_cerrado==1 )
                                                            @if ($diasDiferencia!=0 )
                                                                    <a href="{!! route('farmacia.cargar_medicamentos_rectificacion', ['can_id'=>$can->id,'tipo'=>1]) !!}" class='btn bg-maroon btn-md'><i class="fa fa-medkit"></i></a>
                                                            @else
                                                                    <a href="#" disabled class='btn bg-maroon btn-md'><i class="fa fa-medkit"></i></a>
                                                            @endif
                                                         @else
                                                                <a href="#" disabled class='btn bg-maroon btn-md'><i class="fa fa-medkit"></i></a>
                                                        @endif
                                                    @endif
                                                
                                                @if ($can->id==1 )   
                                                    @if ($can->dispositivo_cerrado==1)
                                                        @if ($diasDiferencia!=0 )
                                                                <a href="{!! route('farmacia.cargar_medicamentos_rectificacion', ['can_id'=>$can->id,'tipo'=>2]) !!}" class='btn bg-maroon btn-md'><i class="fa fa-stethoscope"></i></a>
                                                        @else
                                                                <a href="#" disabled class='btn bg-maroon btn-md'><i class="fa fa-stethoscope"></i></a> 
                                                        @endif
                                                    @else
                                                            <a href="#" disabled class='btn bg-maroon btn-md'><i class="fa fa-stethoscope"></i></a> 
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if(Auth::user()->nivel_user==1)
                                                <div class='btn-group'>
                                                    
                                                        @if ($can->id==1 )
                                                            @if ($can->medicamento_cerrado==2 )
                                                                    <a href="#" class='btn bg-orange btn-md'><i class="fa fa-medkit"></i></a>
                                                            @else
                                                                    <a href="#" disabled class='btn bg-orange btn-md'><i class="fa fa-medkit"></i></a>
                                                            @endif
                                                        @endif
                                                    
                                                    @if ($can->id==1 )                                                     
                                                        @if ($can->dispositivo_cerrado==2)
                                                                <a href="#" class='btn bg-olive btn-md'><i class="fa fa-stethoscope"></i></a>
                                                        @else
                                                                <a href="#" disabled class='btn bg-olive btn-md'><i class="fa fa-stethoscope"></i></a>
                                                        @endif
                                                    @endif
                                                </div>
                                            @else
                                                <div class='btn-group'>
                                                    
                                                        @if ($can->id==1 )
                                                            @if ($can->medicamento_cerrado==2)
                                                                        <a href="#" class='btn bg-orange btn-md'><i class="fa fa-medkit"></i></a>
                                                            @else
                                                                    <a href="#" disabled class='btn bg-orange btn-md'><i class="fa fa-medkit"></i></a>
                                                            @endif
                                                        @endif        
                                                    
                                                    @if ($can->id==1 )
                                                        @if ($can->dispositivo_cerrado==2)
                                                                    <a href="#" class='btn bg-olive btn-md'><i class="fa fa-stethoscope"></i></a>
                                                        @else
                                                                <a href="#" disabled class='btn bg-olive btn-md'><i class="fa fa-stethoscope"></i></a>
                                                        @endif
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="/listar_observaciones/{{$can->id}} class='btn bg-purple btn-md'><i class="fa fa-tripadvisor"></i></a>
                                        </td>       
                                        <td>
                                            @if ($can->id==1 )
                                                    <a href="#" class='btn bg-black btn-md'> <i class="fa fa-fw fa-user"></i></a>
                                            @endif
                                        </td>     
                                        <td></td>
                                        <td></td>
                                        
                                    </tr>
                                    <tr>
                                        <td><?php echo $x++;                                        
                                        ?></td>
                                        @if(Auth::user()->nivel_user!=1)   
                                            <td><a href="#"> {!! $can->nombre_can!!}</a></td>
                                        @else
                                            <td> {!! $can->nombre_can!!}</td>
                                        @endif
                                        <!--td></td-->
                                        @if ( Auth::user()->tipo_registro==2 )
                                            <td>
                                                                                          
                                                @if ($can->medicamento_cerrado==1 )
                                                        @if ($diasDiferencia!=0 )
                                                            <a href="#" class='btn bg-purple btn-md'><i class="fa fa-medkit"></i></a>
                                                        @else
                                                            <a href="#" disabled class='btn bg-red btn-md'><i class="fa fa-medkit"></i></a>
                                                        @endif
                                                     @else
                                                        <a href="#" disabled class='btn bg-red btn-md'><i class="fa fa-medkit"></i></a>
                                                    @endif
                                                
                                                @if ($can->dispositivo_cerrado==1)
                                                        @if ($diasDiferencia!=0 )
                                                            <a href="#" class='btn bg-purple btn-md'><i class="fa fa-stethoscope"></i></a>
                                                        @else
                                                            <a href="#" disabled class='btn bg-red btn-md'><i class="fa fa-stethoscope"></i></a> 
                                                        @endif
                                                @else
                                                        <a href="#" disabled class='btn bg-red btn-md'><i class="fa fa-stethoscope"></i></a> 
                                                @endif
                                                
                                                
                                            </td>
                                            <td>
                                                <div class='btn-group'>
                                                    
                                                        @if ($can->medicamento_cerrado==2)
                                                                <a href="#" class='btn bg-orange btn-md'><i class="fa fa-medkit"></i></a>
                                                        @else
                                                            <a href="#" disabled class='btn bg-orange btn-md'><i class="fa fa-medkit"></i></a>
                                                        @endif
                                                    
                                                    @if ($can->dispositivo_cerrado==2)
                                                            <a href="#" class='btn bg-olive btn-md'><i class="fa fa-stethoscope"></i></a>
                                                    @else
                                                        <a href="#" disabled class='btn bg-olive btn-md'><i class="fa fa-stethoscope"></i></a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <a href="/listar_observaciones/{{$can->id}}" class='btn bg-purple btn-md'><i class="fa fa-tripadvisor"></i></a>
                                            </td>
                                            <td>
                                                @if(Auth::user()->nivel_user!=1)
                                                    <a href="#" class='btn bg-black btn-md'> <i class="fa fa-fw fa-user"></i></a>
                                                @else
                                                    <a href="#" class='btn bg-black btn-md'> <i class="fa fa-fw fa-user"></i></a>
                                                @endif                                                                                   
                                            </td>
                                            @if(Auth::user()->nivel_user==1)
                                            <td></td>
                                            @endif
                                                                    
                                            <td>
                                                @if($can->estado_activo=1)
                                                    @if ($can->medicamento_cerrado!=1 and $can->dispositivo_cerrado!=1)
                                                        <a href="#" class='btn bg-blue btn-md'><i class="fa fa-upload"></i></a>
                                                    @else
                                                        <a href="#" disabled class='btn bg-blue btn-md'><i class="fa fa-upload"></i></a>
                                                    @endif
                                                @else
                                                    <a href="#" class='btn bg-blue btn-md'><i class="fa fa-eye"></i></a>
                                                @endif
                                                
                                            </td>
                                        @endif
                                    </tr>
                                    
                                @else
                                    <tr>
                                        <td><?php echo $x++;?></td>
                                        @if(Auth::user()->nivel_user!=1)   
                                            <td><a href="#"> {!! $can->nombre_can!!}</a></td>
                                        @else

                                            <td> {!! $can->nombre_can!!} <?php if($can->estado_activo==1): ?> <small class="label label-danger"> nuevo </small> <?php endif; ?></td>
                                        @endif
                                        @if ($can->id==9 )
                                        <!--td>
                                            @if ($can->actualizacion==1 )
                                                
                                                <a href="#" class='btn bg-olive btn-md'>SI</a>
                                            @else
                                                <a href="{!! route('farmacia.actualizar_can_2027', ['can_id'=>$can->id,'establecimiento'=>$can->establecimiento_id]) !!}" class='btn bg-maroon btn-md'>NO</a>
                                            @endif
                                        </td-->
                                        @else
                                        <!--td></td-->
                                        @endif
                                        @if ( Auth::user()->tipo_registro==2 )
                                            <td>
                                                    @if ($can->medicamento_cerrado==1 )
                                                        @if ($diasDiferencia!=0 )
                                                            <a href="ver_productos_can/{{$can->id}}/1" class='btn bg-purple btn-md'><i class="fa fa-medkit"></i></a>
                                                        @else
                                                            <a href="#" disabled class='btn bg-red btn-md'><i class="fa fa-medkit"></i></a>
                                                        @endif
                                                    @else
                                                        <a href="#" disabled class='btn bg-red btn-md'><i class="fa fa-medkit"></i></a>
                                                    @endif
                                                
                                                    @if ($can->dispositivo_cerrado==1)
                                                        @if ($diasDiferencia!=0 )
                                                            <a href="ver_productos_can/{{$can->id}}/2" class='btn bg-purple btn-md'><i class="fa fa-stethoscope"></i></a>
                                                        @else
                                                            <a href="#" disabled class='btn bg-red btn-md'><i class="fa fa-stethoscope"></i></a> 
                                                        @endif
                                                    @else
                                                        <a href="#" disabled class='btn bg-red btn-md'><i class="fa fa-stethoscope"></i></a> 
                                                    @endif

                                            </td>
                                            
                                            <td>
                                                <div class='btn-group'>
                                                    
                                                        @if ($can->medicamento_cerrado==2)
                                                                <a href="ver_consolidado/1/{{$can->id}}" class='btn bg-orange btn-md'><i class="fa fa-medkit"></i></a>
                                                        @else
                                                            <a href="#" disabled class='btn bg-orange btn-md'><i class="fa fa-medkit"></i></a>
                                                        @endif
                                                    
                                                    @if ($can->dispositivo_cerrado==2)
                                                            <a href="ver_consolidado/2/{{$can->id}}" class='btn bg-olive btn-md'><i class="fa fa-stethoscope"></i></a>
                                                    @else
                                                        <a href="#" disabled class='btn bg-olive btn-md'><i class="fa fa-stethoscope"></i></a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <a href="/listar_observaciones/{{$can->id}}" class='btn bg-purple btn-md'><i class="fa fa-tripadvisor"></i></a>
                                            </td>
                                            <td>
                                                @if(Auth::user()->nivel_user!=1)
                                                    <a href="#" class='btn bg-black btn-md'> <i class="fa fa-fw fa-user"></i></a>
                                                @else
                                                    <a href="/responsables/{{$can->id}}" class='btn bg-black btn-md'> <i class="fa fa-fw fa-user"></i></a>
                                                @endif 

                                            </td>
                                            @if(Auth::user()->nivel_user==1)
                                            <td> 
                                                    @if($can->id>=4 and $can->medicamento_cerrado==2 and $can->dispositivo_cerrado==2)
                                                        <a href="/atencion_consultorios/{{$can->id}}" class='btn bg-orange btn-md'> <i class="fa fa-fw fa-edit"></i></a>
                                                    @else
                                                        @if($can->id>=4 )
                                                            <a href="#" disabled class='btn bg-orange btn-md'><i class="fa fa-edit"></i></a>
                                                        @endif
                                                    @endif
                                            </td>
                                            @endif                  
                                            <td>
                                                @if($can->estado_activo==1)
                                                    @if ($can->medicamento_cerrado!=1 and $can->dispositivo_cerrado!=1)
                                                        <a href="#" class='btn bg-blue btn-md'><i class="fa fa-upload"></i></a>
                                                    @else
                                                        <a href="#" disabled class='btn bg-blue btn-md'><i class="fa fa-upload"></i></a>
                                                    @endif
                                                @else
                                                    <a href="#" class='btn bg-blue btn-md'><i class="fa fa-eye"></i></a>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endif
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
