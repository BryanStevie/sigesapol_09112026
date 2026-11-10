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
                <h3 class="panel-title">Consolidado - {!!$descripcion_tipo!!}</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="btn-group">
                            <a target="_blank" data-toggle="tooltip" title="Material Biomedico por Establecimientos!" href="/consolidado_nacional_tipo/{{$can_id}}/2" class='btn-sm bg-maroon btn-flat margin'><i class="fa fa-fire-extinguisher"></i></a>
                            <a target="_blank" data-toggle="tooltip" title="Instrumental Quirurgico por Establecimientos!" href="/consolidado_nacional_tipo/{{$can_id}}/3" class='btn-sm bg-primary btn-flat margin'><i class="fa fa-bed"></i></a>
                            <a target="_blank" data-toggle="tooltip" title="Material e Insumos Odontológico por Establecimientos!" href="/consolidado_nacional_tipo/{{$can_id}}/4" class='btn-sm bg-aqua btn-flat margin'><i class="fa fa-wrench"></i></a>
                            <a target="_blank" data-toggle="tooltip" title="Insumo de Laboratorio por Establecimientos!" href="/consolidado_nacional_tipo/{{$can_id}}/5" class='btn-sm bg-olive btn-flat margin'><i class="fa fa-hourglass-start"></i></a>
                            <a target="_blank" data-toggle="tooltip" title="Material de Laboratorio por Establecimientos!" href="/consolidado_nacional_tipo/{{$can_id}}/6" class='btn-sm bg-purple btn-flat margin'><i class="fa fa-fw fa-flask"></i></a>
                            <a target="_blank" data-toggle="tooltip" title="Material Fotográfico y fonotécnico por Establecimientos!" href="/consolidado_nacional_tipo/{{$can_id}}/7" class='btn-sm bg-navy btn-flat margin'><i class="fa fa-odnoklassniki-square"></i></a>
                            <a target="_blank" data-toggle="tooltip" title="Productos Afines por Establecimientos!" href="/consolidado_nacional_tipo/{{$can_id}}/8" class='btn-sm bg-orange btn-flat margin'><i class="fa fa-hand-lizard-o"></i></a>
                            <a target="_blank" data-toggle="tooltip" title="Uso Restringido por Establecimientos!" href="/consolidado_nacional_tipo/{{$can_id}}/9" class='btn-sm bg-red btn-flat margin'><i class="fa fa-fw fa-expeditedssl"></i></a>
                            <a target="_blank" data-toggle="tooltip" title="Instrumental Traumatologia por Establecimientos!" href="/consolidado_nacional_tipo/{{$can_id}}/11" class='btn-sm bg-primary btn-flat margin'><i class="fa fa-fw fa-expeditedssl"></i></a>  .
                            <a target="_blank" data-toggle="tooltip" title="Material Traumatologia por Establecimientos!" href="/consolidado_nacional_tipo/{{$can_id}}/12" class='btn-sm bg-aqua btn-flat margin'><i class="fa fa-fw fa-expeditedssl"></i></a>  .
                            <a target="_blank" data-toggle="tooltip" title="Ayuda Biomecanicas por Establecimientos!" href="/consolidado_nacional_tipo/{{$can_id}}/13" class='btn-sm bg-maroon btn-flat margin'><i class="fa fa-fw fa-expeditedssl"></i></a>  .  .
                            <br><br>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <table id="example" class="stripe row-border order-column" cellspacing="0" >  
                                <thead>
                                    <tr>
                                        <th bgcolor="#D4E6F1" style="text-align:center;">#</th>
                                        <th bgcolor="#D4E6F1" style="text-align:center;">Código</th>
                                        <th bgcolor="#D4E6F1" style="text-align:center;">Descripción Producto</th>
                                        <th bgcolor="#D4E6F1" style="text-align:center;" >Necesidad - NIVEL I</th>
                                        @if($can_id<3)
                                            <th bgcolor="#D4E6F1" style="text-align:center;" >Necesidad Actual - NIVEL I</th>
                                        @endif
                                        <th bgcolor="#D4E6F1" style="text-align:center;" >Necesidad - NIVEL II</th>
                                        @if($can_id<3)
                                            <th bgcolor="#D4E6F1" style="text-align:center;" >Necesidad Actual - NIVEL II</th>
                                        @endif
                                            <th bgcolor="#D4E6F1" style="text-align:center;" >Necesidad - NIVEL III</th>
                                        @if($can_id<3)
                                            <th bgcolor="#D4E6F1" style="text-align:center;" >Necesidad Actual - NIVEL III</th>
                                        @endif                                    
                                        <th bgcolor="#D4E6F1" style="text-align:center;">Necesidad NACIONAL</th>
                                        @if($can_id<3)
                                            <th bgcolor="#D4E6F1" style="text-align:center;">Necesidad ACTUAL NACIONAL</th>
                                        @endif
                                    </tr>
                                </thead>                
                                <tbody>
                                <?php
                                    $nnivel1=0;$nnivel2=0;$nnivel3=0;$ntotal=0;$ntotal1=0;$ntotal2=0;$ntotal3=0;
                                    if (isset($productos)):
                                        foreach ($productos as $key => $rowResumen):
                                            $nnivel1=$nnivel1+$rowResumen->_nivel_1;
                                            $nnivel2=$nnivel2+$rowResumen->_nivel_2;
                                            $nnivel3=$nnivel3+$rowResumen->_nivel_3;
                                            $ntotal=$ntotal +$rowResumen->_nivel_1+$rowResumen->_nivel_2 + $rowResumen->_nivel_3;
                                            
                                ?>
                                    <tr>
                                        <td><?= ($key + 1) ?></td>
                                        <td><?= $rowResumen->_codigo ?></td>
                                        <td><?= $rowResumen->_descripcion ?></td>
                                        <td><?= $rowResumen->_nivel_1 ?></td>
                                        <td><?= $rowResumen->_nivel_2 ?></td>
                                        <td><?= $rowResumen->_nivel_3 ?></td>
                                        <td><strong><?= $rowResumen->_nivel_1 + $rowResumen->_nivel_2 + $rowResumen->_nivel_3 ?></strong></td>
                                    </tr>
                                <?php   endforeach; ?>
                                    <tr>
                                        <td colspan="3"><strong>TOTAL</strong></td>
                                        <td><strong><?= $nnivel1 ?></strong></td>
                                        <td><strong><?= $nnivel2 ?></strong></td>
                                        <td><strong><?= $nnivel3 ?></strong></td>
                                        <td><strong><?= $ntotal ?></strong></td>
                                    </tr>
                                    <?php endif; ?>
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
        dom: 'Bfrtip',
        buttons: [
            
                'excelHtml5',                
        ]
    });
})

function ver_datos_nivel_1(id, descripcion,opcion,can_id){
  $('#descripcionproducto').text(descripcion);
    $.ajax({
    url: "{{ url('mostrar_medicamento_nivel_1') }}" + '/' + id + '/' + opcion + '/' + can_id,
            async: false,
            success: function(estimacion){
            $("#muestra-detalle").html(estimacion);
            },
            complete: function () {
            $("#myModalDatosProductos").modal();
            }
    });
  }

function ver_datos_nivel_2(id, descripcion,opcion,can_id){
  $('#descripcionproducto').text(descripcion);
    $.ajax({
    url: "{{ url('mostrar_medicamento_nivel_2') }}" + '/' + id + '/' + opcion + '/' + can_id,
            async: false,
            success: function(estimacion){
            $("#muestra-detalle").html(estimacion);
            },
            complete: function () {
            $("#myModalDatosProductos").modal();
            }
    });
  }

function ver_datos_nivel_3(id, descripcion,opcion,can_id){
  $('#descripcionproducto').text(descripcion);
    $.ajax({
    url: "{{ url('mostrar_medicamento_nivel_3') }}" + '/' + id+ '/' + opcion + '/' + can_id,
            async: false,
            success: function(estimacion){
            $("#muestra-detalle").html(estimacion);
            },
            complete: function () {
            $("#myModalDatosProductos").modal();
            }
    });
  }
function ver_datos_ajuste_nivel_2(id, descripcion){
  $('#descripcionproducto').text(descripcion);
    $.ajax({
    url: "{{ url('mostrar_medicamento_ajuste_nivel_2') }}" + '/' + id,
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
<script src="{{ asset('assets/dataTables/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('js/jszip.min.js')}}"></script>
<!--script src="{{ asset('js/pdfmake.min.js')}}"></script>
<script src="{{ asset('js/vfs_fonts.js')}}"></script-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="{{ asset('js/buttons.html5.min.js')}}"></script>
<script src='{{ asset ("/js/dataTables.fixedColumns.min.js") }}'></script>

@stop
@include('frontend.includes.footer')
@endsection