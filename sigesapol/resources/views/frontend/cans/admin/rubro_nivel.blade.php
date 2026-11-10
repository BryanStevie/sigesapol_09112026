@extends('frontend.layouts.master2')
@section('renderjs')
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">CONSOLIDADO DE TIPO DE DISPOSITIVO POR NIVEL</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <table id="example" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                      <th>TIPO</th>
                                      <th>RUBRO</th>
                                      <th>NIVEL I</th>
                                      <th>NIVEL II</th>
                                      <th>NIVEL III</th>
                                      <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $x=1;?>
                                    <tr>
                                      <th><p>PRODUCTOS FARMACEUTICOS</p></th>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <th style="text-align:right;">{!! number_format($can_productos[0][0]+$can_productos[1][0]+$can_productos[2][0],0) !!}</th>
                                    </tr>
                                    <tr>
                                      <td></td>
                                      <td><p>PRODUCTOS FARMACEUTICOS</p></td>
                                      <td style="text-align:right;"><p>{!! number_format($can_productos[0][0],0) !!}</p></td>
                                      <td style="text-align:right;"><p>{!! number_format($can_productos[1][0],0) !!}</p></td>
                                      <td style="text-align:right;"><p>{!! number_format($can_productos[2][0],0) !!}</p></td>
                                      <td></td>
                                    </tr>
                                    <tr>
                                      <th>DISPOSITIVOS MEDICOS</th>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                      <th style="text-align:right;">{!! number_format(($can_productos[0][10]+$can_productos[1][10]+$can_productos[2][10])-($can_productos[0][0]+$can_productos[1][0]+$can_productos[2][0]),0) !!}</th>
                                    </tr>
                                    <?php 
                                        $total_nivel_1 = 0;$total_nivel_2 = 0;$total_nivel_3 = 0; $total = 0;
                                    ?>
                                    @for($j=1;$j<10;$j++)
                                        @if($descripcion[$j]!="")
                                        <tr>    
                                        
                                          <td></td>
                                          <td><p>{!! $descripcion[$j] !!}</p></td>
                                          <td style="text-align:right;"><p>{!! number_format($can_productos[0][$j],0) !!}</p></td>
                                          <td style="text-align:right;"><p>{!! number_format($can_productos[1][$j],0) !!}</p></td>
                                          <td style="text-align:right;"><p>{!! number_format($can_productos[2][$j],0) !!}</p></td>
                                          <td></td>
                                          <?php 
                                                $total_nivel_1 = $total_nivel_1 + $can_productos[0][$j];
                                                $total_nivel_2 = $total_nivel_2 + $can_productos[1][$j];
                                                $total_nivel_3 = $total_nivel_3 + $can_productos[2][$j];
                                                
                                            ?>
                                        </tr>    
                                        @endif
                                    @endfor
                                    <tr>
                                        <th>Total General</th>
                                        <td></td>
                                        <th style="text-align:right;">{!! number_format($total_nivel_1,0) !!}</th>
                                        <th style="text-align:right;">{!! number_format($total_nivel_2,0) !!}</th>
                                        <th style="text-align:right;">{!! number_format($total_nivel_3,0) !!}</th>
                                        <th bgcolor="#D4EFDF" style="text-align:right;">{!! number_format(($total_nivel_1+$total_nivel_2+$total_nivel_3),0) !!}</th>
                                  </tr>
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
                "searching": false,
                "paging":   false,
                "ordering": false,
                dom: 'Bfrtip',
                buttons: [
                    
                        'excelHtml5',
                        'pdfHtml5',
                    
                    
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

@stop
@include('frontend.includes.footer')
@endsection