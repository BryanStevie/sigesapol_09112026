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
					url: '/eliminar_nota_entrada/'+id,
					success: function(result){						
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
                    <h3 class="panel-title">Lista de Notas de Entrada</h3>                    
                </div>
                <div class="panel-body">
                    <div class="col-sm-12 pull-right">
                        <!--<a href="/form_transferencia/{{ Auth::user()->id }}" class="btn btn-default btn-xs">Nueva Tranferencia</a>-->
                        <?php if ($permiso->crear == 1): ?>
                            <a href="/add_documento_nota_entrada/{{ Auth::user()->id}}" class="btn btn-default btn-xs">Nueva Nota de Entrada</a>
                        <?php endif; ?>
                        <?php if ($permiso->lectura == 1): ?>
                            <a href="" class="btn btn-default btn-xs">Ver Notas de Entrada</a>
                        <?php endif; ?>
                    </div>
                    <legend style="text-align:center">{{$farmacia->nombre}}</legend>
                    @if(count($pecosas)>0)
                    <div class="col-sm-12" style="border: 1px solid gray; padding-top: 10px; margin-top: 10px">
                        <legend>NOTAS DE INGRESOS</legend>
                        <div class="table-responsive">
                            <table id="example" class="display table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>N</th>                             
                                        <th>Nro Doc</th>
                                        <th>Origen</th>                           
                                        <th>Motivo</th>  
                                        <th>Fecha</th>            
                                        <th>Observacion</th>
                                        <th style="text-align:center;width:10%">Editar</th>
										<th style="text-align:center;width:10%">Eliminar</th>
                                        <th style="text-align:center;width:10%">Agregar Productos</th>
                                        <th style="text-align:center;width:10%">Imprimir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $n = 0;
                                    ?>
                                    @foreach($pecosas as $t)
                                    <?php 
                                        $fechaIngreso = Carbon::parse($t->updated_at);
                                        $tiempo = $fechaActual->diffInDays($fechaIngreso);
                                    ?>
									<tr>
                                        <td><?php
                                            $n++;
                                            echo $n;
                                            ?>
										</td>
                                        <td>{{$t->nota_entrada}}</td>           
                                        <td><?php 
                                        switch ($t->tipo) {
                                            case 2:echo $t->consultorio; break;
                                            case 4:echo $t->nombre_dependencia; break;
                                            case 5:echo $t->nombre_est; break;
                                         } 
                                            ?></td>
                                        <td><?php echo $t->motivo; ?></td>
                                        <td><?php echo $t->fecha_entrada; ?></td>
										<td><?php echo $t->referencia; ?></td>
                                        <td class="text-center">
                                            <?php if ($t->estado == 1): ?>
                                                <?php if ($permiso->crear == 1 || $permiso->modificar == 1): ?>
                                                    <a href="/edit_nota_entrada/{{$t->id}}/{{ Auth::user()->id}}" class="btn btn-success btn-md">
                                                        <i class="fa fa-edit"></i>
                                                    </a>  
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php if ($tiempo < 4): ?>
                                                    <?php if ($t->estado == 2): ?>
                                                        <?php if ($permiso->crear == 1 || $permiso->modificar == 1): ?>
                                                            <?php if (Auth::user()->flagresponsablefarmacia==1): ?>
                                                            <a href="/edit_nota_entrada/{{$t->id}}/{{ Auth::user()->id}}" class="btn btn-warning btn-md">
                                                                <i class="fa fa-edit"></i></a>
                                                            <?php else: ?>
                                                                <a href="/edit_nota_entrada/{{$t->id}}/{{ Auth::user()->id}}" class="btn btn-success btn-md">
                                                                <i class="fa fa-eye"></i></a>
                                                            <?php endif; ?>
                                                        <?php endif; ?>                          
                                                    <?php else: ?>
                                                        <a href="/edit_nota_entrada/{{$t->id}}/{{ Auth::user()->id}}" class="btn btn-success btn-md disabled">
                                                        <i class="fa fa-eye"></i></a>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                        <a href="/edit_nota_entrada/{{$t->id}}/{{ Auth::user()->id}}" class="btn btn-success  btn-md disabled">
                                                        <i class="fa fa-eye"></i></a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
										<td class="text-center">
											<?php if($t->estado==1):?>
												<a class="btn btn-danger btn-md" href="javascript:void(0)" onclick="eliminar(<?php echo $t->id ?>)"><i class="fa fa-remove"></i></a>
											<?php else:?>
												<a class="btn btn-danger btn-md disabled" href="javascript:void(0)" onclick="javascript:void(0)"><i class="fa fa-remove"></i></a>
											<?php endif;?>
										</td>
                                        <td class="text-center">
                                            <?php if ($t->estado == 1): ?>
                                                <?php if ($permiso->crear == 1 || $permiso->modificar == 1): ?>
                                                    <a href="/recepcionar_productos_guia_entrada/{{$t->id}}/{{ Auth::user()->id}}" class="btn btn-primary btn-md"><i class="fa fa-edit"></i> </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php if ($tiempo < 4): ?>
                                                    <?php if ($t->estado == 2): ?>                      
                                                        <?php if ($permiso->crear == 1 || $permiso->modificar == 1): ?>
                                                            <?php if (Auth::user()->flagresponsablefarmacia==1): ?>
                                                                <a href="/recepcionar_productos_guia_entrada/{{$t->id}}/{{ Auth::user()->id}}" class="btn btn-info btn-md"><i class="fa fa-edit"></i></a>
                                                            <?php else: ?>
                                                                <a href="/recepcionar_productos_guia_entrada/{{$t->id}}/{{ Auth::user()->id}}" class="btn btn-primary btn-md disabled">
                                                                <i class="fa fa-eye"></i></a>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <a href="/recepcionar_productos_guia_entrada/{{$t->id}}/{{ Auth::user()->id}}" class="btn btn-primary btn-md disabled">
                                                            <i class="fa fa-eye"></i></a>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <a href="/recepcionar_productos_guia_entrada/{{$t->id}}/{{ Auth::user()->id}}" class="btn btn-primary btn-md">
                                                        <i class="fa fa-eye"></i></a>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                        <a href="/recepcionar_productos_guia_entrada/{{$t->id}}/{{ Auth::user()->id}}" class="btn btn-primary btn-md">
                                                        <i class="fa fa-eye"></i></a>
                                                <?php endif; ?>

                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($t->estado == 1): ?>
                                                <?php if ($permiso->crear == 1 || $permiso->modificar == 1): ?>
                                                    <a href="#" class="btn btn-danger btn-md disabled">
                                                        <i class="fa fa-file-pdf-o"></i> 
                                                    </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <a target="_blank" href="/print_nota_entrada/{{$t->id}}" class="btn btn-danger btn-md">
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




