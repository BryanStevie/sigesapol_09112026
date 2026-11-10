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
                    <h3 class="panel-title">TRANFERENCIA RECIBIDAS DE PRODUCTOS FARMACÉUTICOS Y DISPOSITIVOS MÉDICOS</h3>                    
                </div>
                <div class="panel-body">
                    <div class="col-md-12 pull-right">
                        <!--<a href="/form_transferencia/{{ Auth::user()->id }}" class="btn btn-default btn-xs">Nueva Tranferencia</a>
                        <a href="/add_documento_transferencia/{{ Auth::user()->id}}" class="btn btn-default btn-xs">Nueva Tranferencia</a>
                        <a href="" class="btn btn-default btn-xs">Ver Transferencias Recibidas</a>-->
                    </div>
                    <legend style="text-align:center">{{$farmacia->nombre}}</legend>
                    @if(count($transferencias)>0)
                    <div class="col-md-12" style="border: 1px solid gray; padding-top: 10px; margin-top: 10px">
                        <legend>Transferencias y Distribuciones Recibidas</legend>
                        <table id="example" class="display" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>N</th>
                                    <th>Nro. Acta de transferencia</th>
                                    <th>Tipo de transferencia</th>
                                    <th>Farmacia Origen</th>
                                    <th>Situacion</th>
                                    <th>Fecha</th>
									<th align="center" style="text-align:center">Nota de Entrada</th>
                                    <th>Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $n = 0;
                                ?>
                                @foreach($transferencias as $t)
								<?php
									$flag = \App\Models\Farmacia::ExisteFarmaciaInternaorExterna($farmacia->id_establecimiento, $t->id_farmacia_destino);
								?>
                                <tr>
                                    <td><?php $n=$n+1; echo $n ?></td>
                                    <td>{{$t->nro_acta}}</td>
									
									<?php if(($t->tipo==1 and $flag == 2) or $t->tipo==3):?>
									<td>Transferencia Externa EESS PNP</td>
									<?php endif; ?>
									<?php if($t->tipo==1 and $flag == 1):?>
									<td>Distribucion Interna de Medicamentos</td>
									<?php endif; ?>
									
                                    <td>{{$t->farmacia_origen}}</td>
                                    <td>
                                        @if($t->estado<=3)
                                            Por Recepcionado <i class="fa fa-flag" style="color:red"></i>
                                        @else
                                            Recepcionado <i class="fa fa-flag" style="color:green"></i>
                                        @endif                                        
                                    </td>                                     
                                    <td>
                                        <?php
                                            $originalDate = $t->fecha;
                                            $fecha = date("d-m-Y", strtotime($originalDate));
                                            echo $fecha; 
                                        ?>
                                    </td>
									<td align="center"><?php echo $t->ano_nota." ".$t->nota_entrada?></td>
                                    <td>
									
										<?php if($t->estado<=3):?>
											<?php if($permiso->crear == 1 || $permiso->modificar == 1):?>
                                            <a href="/mostrar_productos_recibidos/{{$t->id}}" class="btn btn-primary btn-xs"> ver </a>
											<?php endif;?>
                                        <?php else:?>
                                            <a href="/mostrar_productos_recibidos/{{$t->id}}" class="btn btn-primary btn-xs"> ver </a>
                                        <?php endif;?>
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