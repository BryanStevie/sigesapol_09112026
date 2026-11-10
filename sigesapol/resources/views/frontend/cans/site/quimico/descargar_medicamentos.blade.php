@extends('frontend.layouts.master2')
@section('renderjs')
<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script type="text/javascript">
/*  $(document).ready(function () {
        $('#example').dataTable( {
            "language": {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                },
                "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        } );
    });
*/
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
            <a href="/all_cans" class="btn btn-default pull-right"><i class="fa fa-hand-o-left"></i> Regresar </a>
            <div class="panel panel-primary filterable">
                <div class="panel-heading">
                    <h3 class="panel-title">Listado de Productos </h3>
                </div>
                <div class="panel-body">
                  <h1 class="pull-right">
                          @if($cerrado==2)
                              <div class="box-body">
                                <a class="btn btn-success pull-right" target="_blank" href="/descargar_avance/{{$can_id}}/{{$establecimiento_id}}/{{$tipo}}/'xlsx'/{{$tipo}}">Excel <i class="fa fa-file-excel-o"></i></a> 
                                <a target="_blank" href="/pdf-export-can/{{$can_id}}/{{$establecimiento_id}}/{{$tipo}}/0/3" class="btn btn-danger pull-right" >PDF AÑO 3<i class="fa fa-download"></i> </a> 
                                <a target="_blank" href="/pdf-export-can/{{$can_id}}/{{$establecimiento_id}}/{{$tipo}}/0/2" class="btn btn-danger pull-right" >PDF AÑO 2<i class="fa fa-download"></i> </a> 
                                <a target="_blank" href="/pdf-export-can/{{$can_id}}/{{$establecimiento_id}}/{{$tipo}}/0/1" class="btn btn-danger pull-right" >PDF AÑO 1<i class="fa fa-download"></i> </a> 
                              
                              </div>
                          @endif
                      
                  </h1>
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
                                          <div class="table-responsive">
                                          <table id="" class="table">
                                          <!--table class="table"  id="example"-->
                                      <!--table id="example" class="stripe row-border order-column" cellspacing="0"-->  
                                          <thead>
                                              <tr>
                                                  <th rowspan="2" style="text-align:center;">Descripción</th>
                                                  @if($cerrado_stock==2)
                                                  <th rowspan="2" style="text-align:center;">Stock</th>
                                                  @endif
                                                  <th rowspan="2" style="text-align:center;">CPMA</th>
                                                  <th rowspan="2" style="text-align:center;">Necesidad Anual</th>
                                                  <th colspan="12" bgcolor="#3c763d" style="text-align:center;">
                                                    <span style="color: white;"> PRORRATEO</span></th>
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
                                          </thead>                
                                          <tbody>

                                      <?php  }
                                      ?>
                                          
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

                                          
                                  @endforeach
                                     </tbody>
                                              </table><hr>
                                              </div>
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
<!--div class="modal fade" id="showAmpliarTiempo" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    
      <div class="modal-body">
        <div id="diveditpreg2"></div>
      </div>
    
  </div>
</div>
<div class="modal fade" id="showActivarTodos" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    
      <div class="modal-body">
        <div id="diveditpreg1"></div>
      </div>
    
  </div>
</div>
<div class="modal fade" id="showHabilitar" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    
      <div class="modal-body">
        <div id="diveditpreg4"></div>
      </div>
    
  </div>
</div>
<div class="modal fade" id="showAmpliarTiempoTotal" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    
      <div class="modal-body">
        <div id="diveditpreg5"></div>
      </div>
    
  </div>
</div-->
@section('scripts')
    <script>
        /*$(document).ready( function () {
            var table = $('table.display').DataTable({
                "responsive": true,
                "order": [[ 0, "asc" ]],                       
                "scrollY":        "400px",
                "scrollX":        true,
                "scrollCollapse": true,
                "pageLength": 15,  
                fixedColumns:   {
                    leftColumns: 1
                },                
            });
        })*/

        

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