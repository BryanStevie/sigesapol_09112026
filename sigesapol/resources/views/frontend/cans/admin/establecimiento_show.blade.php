@extends('frontend.layouts.master2')
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
@section('renderjs')
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">{!!$descripcion_tipo!!} POR ESTABLECIMIENTO DE SALUD</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="btn-group">
                            <a target="_blank" data-toggle="tooltip" title="Productos Farmaceuticos por Establecimientos!" href="/consolidado_x_establecimiento_and_tp/{{$can_id}}/1" class='btn bg-red btn-flat margin'><i class="fa fa-expeditedssl"></i></a>
                            <a target="_blank" data-toggle="tooltip" title="Material Biomedico por Establecimientos!" href="/consolidado_x_establecimiento_and_tp/{{$can_id}}/2" class='btn bg-maroon btn-flat margin'><i class="fa fa-fire-extinguisher"></i></a>
                            <a target="_blank" data-toggle="tooltip" title="Instrumental Quirurgico por Establecimientos!" href="/consolidado_x_establecimiento_and_tp/{{$can_id}}/3" class='btn bg-primary btn-flat margin'><i class="fa fa-bed"></i></a>
                            <a target="_blank" data-toggle="tooltip" title="Material e Insumos Odontológico por Establecimientos!" href="/consolidado_x_establecimiento_and_tp/{{$can_id}}/4" class='btn bg-aqua btn-flat margin'><i class="fa fa-wrench"></i></a>
                            <a target="_blank" data-toggle="tooltip" title="Insumo de Laboratorio por Establecimientos!" href="/consolidado_x_establecimiento_and_tp/{{$can_id}}/5" class='btn bg-olive btn-flat margin'><i class="fa fa-hourglass-start"></i></a>
                            <a target="_blank" data-toggle="tooltip" title="Material de Laboratorio por Establecimientos!" href="/consolidado_x_establecimiento_and_tp/{{$can_id}}/10" class='btn bg-purple btn-flat margin'><i class="fa fa-fw fa-flask"></i></a>
                            <a target="_blank" data-toggle="tooltip" title="Material Fotográfico y fonotécnico por Establecimientos!" href="/consolidado_x_establecimiento_and_tp/{{$can_id}}/6" class='btn bg-navy btn-flat margin'><i class="fa fa-odnoklassniki-square"></i></a>
                            <a target="_blank" data-toggle="tooltip" title="Productos Afines por Establecimientos!" href="/consolidado_x_establecimiento_and_tp/{{$can_id}}/7" class='btn bg-orange btn-flat margin'><i class="fa fa-hand-lizard-o"></i></a>
                            <a target="_blank" data-toggle="tooltip" title="Instrumental Traumatologia por Establecimientos!" href="/consolidado_x_establecimiento_and_tp/{{$can_id}}/11" class='btn bg-red btn-flat margin'><i class="fa fa-hand-lizard-o"></i></a>
                            <a target="_blank" data-toggle="tooltip" title="Material Traumatologia por Establecimientos!" href="/consolidado_x_establecimiento_and_tp/{{$can_id}}/12" class='btn bg-olive btn-flat margin'><i class="fa fa-hand-lizard-o"></i></a>
                            <a target="_blank" data-toggle="tooltip" title="Ayudas Biomecanicas por Establecimientos!" href="/consolidado_x_establecimiento_and_tp/{{$can_id}}/13" class='btn bg-primary btn-flat margin'><i class="fa fa-hand-lizard-o"></i></a>            
                            <br><br>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <table id="example" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                      <th>Codigo</th>
                                      <th>PRODUCTOS</th>
                                      <th>Necesidad</th>
                                      @for($j=0;$j<$col;$j++)
                                        @if($descripcion[$j]!="")
                                          <th><p align="center">{!!$descripcion[$j][1]!!}</p></th>
                                        @endif
                                      @endfor
                                      
                                    </tr>
                                    
                                </thead>
                                <tbody>
                                  @for($i=0;$i<$fila;$i++)
                                    @if($can_productos[$i][97]!="")
                                      <tr>
                                        <td>{!!$can_productos[$i][97]!!}</td>
                                        <td>{!!$can_productos[$i][95]!!}</td>
                                        <td>{!!$can_productos[$i][94]!!}</td>
                                        @for($j=0;$j<$col;$j++)
                                          <td>{!!$can_productos[$i][$j]!!}</td>
                                        @endfor
                                      </tr>
                                    @endif
                                  @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>    


@section('scripts')
<script>
$(document).ready( function () {
    var table = $('#example').DataTable({
        "searching": true,
        "paging":   false,
        "order": [[ 1, "asc" ]],
        "scrollY":        "400px",
        "scrollX":        true,
        "scrollCollapse": true,
        "pageLength": 1000,  
        fixedColumns:   {
            leftColumns: 3
        },
        dom: 'Bfrtip',
        buttons: [
            
                'excel',                
        ]
    });
})

</script>
<script src="{{ asset('assets/dataTables/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('js/jszip.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="{{ asset('js/buttons.html5.min.js')}}"></script>
<script src='{{ asset ("/js/dataTables.fixedColumns.min.js") }}'></script>

@stop
@include('frontend.includes.footer')
@endsection