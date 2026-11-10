@extends('frontend.layouts.master')
@section('renderjs')
<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>
<style>

</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">GENERAR PECOSAS</h3>                    
                </div>
                <div class="panel-body">
                    <div class="col-md-12 pull-right">
                        <!--<a href="/form_transferencia/{{ Auth::user()->id }}" class="btn btn-default btn-xs">Nueva Tranferencia</a>-->
                        <a href="/add_documento_transferencia_almacen/{{ Auth::user()->id}}" class="btn btn-default btn-xs">Nueva Pecosa</a>
                        <!--<a href="" class="btn btn-default btn-xs">Ver Pecosas Recibidas</a>-->
                    </div>
                    <legend style="text-align:center">{{$farmacia->nombre}}</legend>
                    @if(count($transferencias)>0)
                    <div class="col-md-12" style="border: 1px solid gray; padding-top: 10px; margin-top: 10px">
                        <legend>Transferencias Realizadas</legend>
                        <table id="example" class="display" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>N</th>
                                    <th>Nro Acta</th>
                                    <th>Tipo de transferencia</th>
                                    <th>Situacion</th>
                                    <th>Fecha</th>
                                    <th>Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $n = 0;
                                ?>
                                @foreach($transferencias as $t)
                                <tr>
                                    <td><?php $n++;
                                echo $n;
                                ?></td>
                                    <td>{{$t->nro_acta}}</td>
                                    @if($t->tipo==1)
                                    <td>Transferencia Externa</td>
                                    @else
                                    <td>Entrega Interna</td>
                                    @endif                                    
                                    <td>
                                        <?php
                                        switch ($t->estado) {
                                            case 1:
                                                echo "Acta de Transaferencia registrada, agregar productos para tranferir.";
                                            break;      
                                            case 3:
                                                echo "Por recepcionar.";
                                            break; 
                                            case 4:
                                                echo "Transferencia finalizada.";
                                            break; 
                                        }
                                        ?>
                                    </td>
                                    <?php
                                    $originalDate = $t->fecha;
                                    $fecha = date("d-m-Y", strtotime($originalDate));
                                    ?>
                                    <td><?php echo $fecha; ?></td>                                      
                                    <td>
                                        <a href="/registrar_productos_transferir/{{$t->id}}/{{$farmacia->id}}" class="btn btn-primary">
                                            <i class="fa fa-eye"></i> 
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="col-md-12" style="border: 1px solid gray; padding-top: 10px; margin-top: 10px">
                        No se registro ninguna transferencia o entrega.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection