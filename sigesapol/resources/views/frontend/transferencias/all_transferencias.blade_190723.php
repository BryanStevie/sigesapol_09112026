<?php
    use Carbon\Carbon;
?>
@extends('frontend.layouts.master')
@section('renderjs')
<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
	
	function eliminar(id){
		bootbox.confirm("Desea eliminar el Registro?", function(result) {
			if (result==true) {				
				$.ajax({
					url: '/eliminar_transferencia/'+id,
					success: function(result){
						//$(".alert-danger").html("sddsssfc").show();
						location.reload();
					}
				});
            }
		});
	}
	
</script>
<style>

</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Lista de Transferencias</h3>                    
                </div>
                <div class="panel-body">
                    <div class="col-sm-12 pull-right">
                        <!--<a href="/form_transferencia/{{ Auth::user()->id }}" class="btn btn-default btn-xs">Nueva Tranferencia</a>-->
                        <?php if ($permiso->crear == 1): ?>
                            <a href="/add_documento_transferencia/{{ Auth::user()->id}}" class="btn btn-default btn-xs">Nueva Tranferencia</a>
                        <?php endif; ?>
                        <?php if ($permiso->lectura == 1): ?>
                            <a href="" class="btn btn-default btn-xs">Ver Transferencias Recibidas</a>
                        <?php endif; ?>
                    </div>
                    <legend style="text-align:center">{{$farmacia->nombre}}</legend>
                    @if(count($transferencias)>0)
                    <div class="col-sm-12" style="border: 1px solid gray; padding-top: 10px; margin-top: 10px">
                        <legend>Transferencias y Distribuciones Realizadas</legend>
                        <div class="table-responsive">
                            <table id="example" class="display table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>N</th>
                                        <th>Nro Acta</th>
                                        <th>Tipo de transferencia y Distribuci&oacute;n</th>
                                        <th>Situacion</th>
                                        <th>Motivo</th>
                                        <th>Observaci&oacute;n</th>
                                        <th>Fecha</th>
                                        <th style="text-align:center;width:10%">Editar</th>
										<th style="text-align:center;width:10%">Eliminar</th>
                                        <th style="text-align:center;width:10%">Agregar Productos</th>
                                        <th style="text-align:center;width:10%">Imprimir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $n = 0;
                                    ?>
                                    @foreach($transferencias as $t)
									<?php
                                    
                                        $fechaIngreso = Carbon::parse($t->updated_at);
                                        $tiempo = $fechaActual->diffInDays($fechaIngreso);
                                    
									   $flag = \App\Models\Farmacia::ExisteFarmaciaInternaorExterna($farmacia->id_establecimiento, $t->id_farmacia_destino);
									?>
                                    <tr>
                                        <td><?php
                                            $n++;
                                            echo $n;
                                            ?>
										</td>
                                        <td>{{$t->nro_acta}}</td>
                                        <?php if($t->tipo==1 and $flag == 2):?>
                                        <td>Transferencia Externa EESS PNP</td>
										<?php endif; ?>
										<?php if($t->tipo==1 and $flag == 1):?>
                                        <td>Distribucion Interna de Medicamentos</td>
										<?php endif; ?>
                                        <?php if($t->tipo==2):?>
                                        <td>Transferencia Interna Servicios</td>
                                        <?php endif; ?>
                                        <?php if($t->tipo==3):?>
                                        <td>Transferencia Externa EESS PNP</td>
                                        <?php endif; ?>
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
                                        <td><?php echo $t->nombre; ?></td>
                                        <td><?php echo $t->observacion; ?></td>
                                        <td><?php echo $fecha; ?></td>
                                        <td class="text-center">
                                            <?php if ($t->estado <= 2): ?>
                                                <?php if ($permiso->crear == 1 || $permiso->modificar == 1): ?>
                                                    <a href="/edit_documento_transferencia/{{$t->id}}" class="btn btn-success btn-md">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php if (Auth::user()->id_farmacia ==46): ?>
                                                    <?php if ($tiempo < 5): ?>
                                                        <?php if ($t->estado == 4): ?>
                                                            <?php if ($permiso->crear == 1 || $permiso->modificar == 1): ?>
                                                                <?php if (Auth::user()->flagresponsablefarmacia==1): ?>
                                                                    <a href="/edit_documento_transferencia/{{$t->id}}" class="btn btn-warning btn-md"><i class="fa fa-edit"></i></a>
                                                                <?php else: ?>                                            
                                                                    <a href="/edit_documento_transferencia/{{$t->id}}" class="btn btn-success btn-md"><i class="fa fa-eye"></i></a>
                                                                <?php endif; ?>
                                                            <?php else: ?>                                            
                                                                <a href="/edit_documento_transferencia/{{$t->id}}" class="btn btn-success btn-md"><i class="fa fa-eye"></i></a>
                                                            <?php endif; ?>
                                                        <?php else: ?>                                            
                                                            <a href="/edit_documento_transferencia/{{$t->id}}" class="btn btn-success btn-md"><i class="fa fa-eye"></i></a>
                                                        <?php endif; ?>
                                                    <?php else: ?>                                            
                                                        <a href="/edit_documento_transferencia/{{$t->id}}" class="btn btn-success btn-md"><i class="fa fa-eye"></i></a>
                                                    <?php endif; ?>
                                                <?php else: ?>                                            
                                                        <a href="/edit_documento_transferencia/{{$t->id}}" class="btn btn-success btn-md"><i class="fa fa-eye"></i></a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
										<td class="text-center">
											<?php if($t->estado!=4):?>
												<a class="btn btn-danger btn-md" href="javascript:void(0)" onclick="eliminar(<?php echo $t->id ?>)"><i class="fa fa-remove"></i></a>                                    
											<?php else:?>
												<a class="btn btn-danger btn-md disabled" href="javascript:void(0)" onclick="javascript:void(0)"><i class="fa fa-remove"></i></a>
											<?php endif;?>
										</td>
                                        <td class="text-center">
                                            <?php if ($t->estado <= 3): ?>
                                                <?php if ($permiso->crear == 1 || $permiso->modificar == 1): ?>
                                                    <a href="/registrar_productos_transferir/{{$t->id}}/{{$farmacia->id}}" class="btn btn-primary btn-md">
                                                        <i class="fa fa-edit"></i> 
                                                    </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php if (Auth::user()->id_farmacia==46): ?>
                                                    <?php if ($tiempo < 5): ?>
                                                        <?php if ($t->estado == 4): ?>
                                                            <?php if ($permiso->crear == 1 || $permiso->modificar == 1): ?>
                                                                <?php if (Auth::user()->flagresponsablefarmacia==1): ?>
                                                                    <a href="/registrar_productos_transferir/{{$t->id}}/{{$farmacia->id}}" class="btn btn-info btn-md">
                                                                    <i class="fa fa-edit"></i> </a>
                                                                <?php else:?>
                                                                    <a href="/registrar_productos_transferir/{{$t->id}}/{{$farmacia->id}}" class="btn btn-primary btn-md">
                                                                        <i class="fa fa-eye"></i> 
                                                                    </a>

                                                                <?php endif; ?>
                                                            <?php else:?>
                                                                <a href="/registrar_productos_transferir/{{$t->id}}/{{$farmacia->id}}" class="btn btn-primary btn-md"><i class="fa fa-eye"></i></a>
                                                            <?php endif; ?>
                                                        <?php else:?>
                                                            <a href="/registrar_productos_transferir/{{$t->id}}/{{$farmacia->id}}" class="btn btn-primary btn-md"><i class="fa fa-eye"></i></a>
                                                        <?php endif; ?>
                                                    <?php else:?>
                                                        <a href="/registrar_productos_transferir/{{$t->id}}/{{$farmacia->id}}" class="btn btn-primary btn-md"><i class="fa fa-eye"></i></a>
                                                    <?php endif; ?>
                                                <?php else:?>
                                                        <a href="/registrar_productos_transferir/{{$t->id}}/{{$farmacia->id}}" class="btn btn-primary btn-md"><i class="fa fa-eye"></i></a>
                                                    <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($t->estado <= 2): ?>
                                                <?php if ($permiso->crear == 1 || $permiso->modificar == 1): ?>
                                                    <a href="#" class="btn btn-danger btn-md disabled">
                                                        <i class="fa fa-file-pdf-o"></i> 
                                                    </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <a target="_blank" href="/imprimir_transferencia/{{$t->id}}" class="btn btn-danger btn-md">
                                                    <i class="fa fa-file-pdf-o"></i> 
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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




