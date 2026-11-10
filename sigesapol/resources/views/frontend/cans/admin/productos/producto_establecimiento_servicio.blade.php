@extends('frontend.layouts.master2')
@section('renderjs')
<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script type="text/javascript">
    $(document).ready( function () {
            var table = $('table.table').DataTable({
                "responsive": true,
                //"order": [[ 0, "asc" ]],                       
                "scrollY":        "500px",
                //"scrollX":        true,
                "scrollCollapse": true,
                "pageLength": 15,  
                               
            }); 
    })

</script>
@endsection
@section('content')
<div class="content">
  <div class="clearfix"></div>
    <div class="container-fluid">
        <div class="row">
          <a href="/monitorear_can_servicio/{{$id_can}}/{{$id_establecimiento}}" class="btn btn-default  pull-right"><i class="fa fa-hand-o-left"></i> Regresar </a>
          <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">Cuadro Anual de Necesidad - {{$nombre_establecimiento}} @if($id_servicio!=0) - {{$servicio->nombre}} @endif</h3>
                <input type="hidden" value="<?php echo $id_can?>" name="id_can" id="id_can" class="form-control">
                <input type="hidden" value="<?php echo $id_establecimiento?>" name="id_establecimiento" id="id_establecimiento" class="form-control">
                <input type="hidden" value="<?php echo $id_servicio?>" name="id_servicio" id="id_servicio" class="form-control">
                <input type="hidden" value="1" name="tipo_producto" id="tipo_producto" class="form-control">
            </div>
            <div class="panel-body">
              <h1 class="pull-right"></h1>
              <div class="row">
                <div class="col-xs-12">
                  <div class="box-body">
                    <?php $tipo_dispositivo_anterior=0; $i=1;?>
                    @foreach($estimacions as $key => $estimacion)
                      <?php 
                        if($tipo_dispositivo_anterior!=$estimacion->id_tipo_dispositivo){
                          $descripcion_tipo_dispositivo=DB::table('tipo_productos')->where('id',$estimacion->id_tipo_dispositivo)->get();

                            $nombre_dispositivo=$descripcion_tipo_dispositivo[0]->nombre;
                            $name_tabla='id=example'.$i;
                            
                            $i++;
                            if($tipo_dispositivo_anterior!=0){ ?>
                                </tbody>
                            </table>
                            <hr>
                      <?php
                            }
                            $tipo_dispositivo_anterior=$estimacion->id_tipo_dispositivo;
                      ?>
                      <h3><b>{!!$nombre_dispositivo!!}</b></h3>
                      <table id="" class="table">
                        <thead>
                            @if($id_can < 6)
                            <tr>
                                <th rowspan="2" style="text-align:center;">Descripción</th>
                                @if($cerrado_stock==2)
                                <th rowspan="2" style="text-align:center;">Stock</th>
                                @endif
                                <th rowspan="2" style="text-align:center;">CPMA</th>
                                <th rowspan="2" style="text-align:center;">Necesidad Anual</th>
                                <th colspan="12" bgcolor="#3c763d" style="text-align:center;">
                                  <span style="color: white;"> PRORRATEO AÑO 1</span></th>
                                  
                            </tr>
                            <tr>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 1</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 2</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 3</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 4</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 5</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 6</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 7</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 8</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 9</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 10</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 11</small></span></th>
                                <th bgcolor="#3c763d" style="text-align:center;"><span style="color: white;"> <small>Mes 12</small></span></th>
                            </tr>
                            @else
                            <tr>
                                <th style="text-align:center;">Descripción</th>
                                <th style="text-align:center;">Necesidad Anual</th>
                            </tr>
                            @endif
                        </thead>                
                        <tbody>
                        <?php  } ?> 
                        @if($id_can < 6)      
                          <tr>
                              <td><small>{!! $estimacion->descripcion !!}</small></td>
                              <td><small>{!! $estimacion->cpma !!}</small></td>
                              <td><small>{!! $estimacion->necesidad_anual !!}</small></td>
                              <td><small>{!! $estimacion->mes1 !!}</small></td>
                              <td><small>{!! $estimacion->mes2 !!}</small></td>
                              <td><small>{!! $estimacion->mes3 !!}</small></td>
                              <td><small>{!! $estimacion->mes4 !!}</small></td>
                              <td><small>{!! $estimacion->mes5 !!}</small></td>
                              <td><small>{!! $estimacion->mes6 !!}</small></td>
                              <td><small>{!! $estimacion->mes7 !!}</small></td>
                              <td><small>{!! $estimacion->mes8 !!}</small></td>
                              <td><small>{!! $estimacion->mes9 !!}</small></td>
                              <td><small>{!! $estimacion->mes10 !!}</small></td>
                              <td><small>{!! $estimacion->mes11 !!}</small></td>
                              <td><small>{!! $estimacion->mes12 !!}</small></td>
                          </tr>   
                        @else
                          <tr>
                              <td><small>{!! $estimacion->descripcion !!}</small></td>
                              <td><small>{!! $estimacion->necesidad_anual !!}</small></td>
                          </tr>   
                        @endif        
                        @endforeach
                        </tbody>
                      </table>
                      <hr>
                    </div>      
                  </div>    
                </div>
              </div>
            </div>
        </div>
    </div>
  </div>
</div>
<div class="modal fade" id="showActivar" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    
      <div class="modal-body">
        <div id="diveditpreg3"></div>
      </div>
    
  </div>
</div>

@section('scripts')
    <script>
        function ver_datos(id, descripcion){
          $('#descripcionproducto').text(descripcion);
          $.ajax({
          url: "{{ url('farmacia') }}" + '/' + id,
                  async: false,
                  success: function(estimacion){
                  $("#muestra-detalle").html(estimacion);
                  },
                  complete: function () {
                  $("#myModalDatosProductos").modal();
                  }
          });
          }
    </script>
<script src='{{ asset ("/js/dataTables.fixedColumns.min.js") }}'></script>
@stop
@include('frontend.includes.footer')
@endsection