@extends('frontend.layouts.master2')
@section('renderjs')
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">ESTABLECIMIENTOS POR TIPO DE DISPOSITIVO MEDICO</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12">
                      @if($fila>0)
                      <div class="table-responsive">
                        <?php $col=$col*2; $m=0;?>
                        <table id="example" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th rowspan="2">ID</th>
                                  <th rowspan="2">ESTABLECIMIENTOS</th>
                                  @for($j=0;$j<$col;$j=$j+2)
                                    @if($descripcion[$m]!="")
                                      <th colspan="2"><p align="center">{!!$descripcion[$m][1]!!}</p></th>
                                      
                                    @endif
                                    <?php $m++;?>
                                  @endfor
                                </tr>
                                <tr>
                                  @for($j=0;$j<$col;$j=$j+2)
                                    @if($descripcion[$m]!="")
                                      <th>Cantidad</th>
                                      <th>Necesidad</th>
                                    @endif
                                    <?php $m++;?>
                                  @endfor
                                </tr>
                            </thead>
                            <tbody>
                              @for($i=0;$i<$fila;$i++)
                                @if($can_productos[$i][35]!="")
                                  <tr>
                                    <td>{!!$i+1!!}</td>
                                    <td>{!!$can_productos[$i][35]!!}</td>
                                    @for($j=0;$j<$col;$j=$j+2)
                                    @if($i<80)
                                      <td>{!!$can_productos[$i][$j+1]!!}</td>
                                      <td>{!!$can_productos[$i][$j]!!}</td>
                                    @endif
                                    @endfor
                                  </tr>
                                @else

                                @endif
                              @endfor
                            </tbody>
                        </table>
                    </div>
                    @else
                       No hay registro alguno.
                    @endif
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
        "order": [[ 0, "asc" ]],
        "scrollY":        "400px",
        "scrollX":        true,
        "scrollCollapse": true,
        "pageLength": 1000,  
        fixedColumns:   {
            leftColumns: 1
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
<!--script src="{{ asset('js/pdfmake.min.js')}}"></script>
<script src="{{ asset('js/vfs_fonts.js')}}"></script-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="{{ asset('js/buttons.html5.min.js')}}"></script>
<script src='{{ asset ("/js/dataTables.fixedColumns.min.js") }}'></script>

@stop
@include('frontend.includes.footer')
@endsection