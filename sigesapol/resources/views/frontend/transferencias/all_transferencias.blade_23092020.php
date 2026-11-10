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

  function anular(id){
  bootbox.confirm("Desea anular el Registro?", function(result) {
    if (result==true) {       
    $.ajax({
      url: '/anular_transferencia/'+id,
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
            <?php if ($permiso->crear == 1): ?>
              <a href="/add_documento_transferencia/{{ Auth::user()->id}}" class="btn btn-success btn-md">Nueva Tranferencia <i class="fa fa-file"></i> </a>
            <?php endif; ?>
            <?php if ($permiso->lectura == 1): ?>
                <a href="" class="btn btn-info btn-md">Ver Transferencias Recibidas <i class="fa fa-eye"></i></a>
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
                          <th>Fecha</th>
                          <th>Destino</th>
                          <th>Tipo de transferencia y Distribuci&oacute;n</th>
                          <th>Motivo</th>
                          <th>Situacion</th>                                                              
                          <th>Observaci&oacute;n</th>
                          <th style="text-align:center;width:10%">Editar</th>
						              <th style="text-align:center;width:10%">Eliminar</th>                          
                          <th style="text-align:center;width:10%">Agregar Productos</th>                          
                          <th style="text-align:center;width:10%">Imprimir</th>
                          <?php if(Auth::user()->flagestablecimiento==1): ?>
                            <th style="text-align:center;width:10%">Anular</th>
                          <?php endif; ?>
                        </tr>
                      </thead>
                      <tbody>
                      <?php $n = 0; ?>
                      @foreach($transferencias as $t)
                      <?php                                    
                        $fechaIngreso = Carbon::parse($t->created_at);
                        $tiempo = $fechaActual->diffInDays($fechaIngreso);

					  ?>
                        <tr>
                          <td>
                            <?php
                              $n++; echo $n;
                            ?>
						  </td>
                          <?php
                            $originalDate = $t->fecha;
                            $fecha = date("d-m-Y", strtotime($originalDate));
                            switch ($t->tipo) {
                                case '1':
                                    $texto='Distribucion Interna de Medicamentos';
                                    $destino=$t->farmacia_destino;
                                    break;
                                case '2':
                                    $texto='Transferencia Interna Servicios';
                                    $destino=$t->consultorio_destino;
                                    break;
                                case '3':
                                    $texto='Transferencia Externa EESS PNP';
                                    $destino=$t->establecimiento_destino;
                                    break;
                                case '4':
                                    $texto='Transferencia No PNP';
                                    $destino=$t->dependencia_destino;
                                    break;
                            } ?>                                        
                          <td>{{$t->nro_acta}}</td>
                          <td><?php echo $fecha; ?></td>
                          <td><?php echo $destino; ?></td>
                          <td><?php echo $texto; ?></td>
                          <td><?php echo $t->motivo; ?></td>
                          <td>
                          <?php
                            switch ($t->estado) {
                              case 0:
                                if($t->anulado==0)
                                  echo "<font color='red'>Eliminado.</font>";
                                else
                                  echo "<font color='red'>Eliminado.</font>";
                                break;
                              case 1:
                                if($t->anulado==0)
                                  echo "<font color='red'>Anulado.</font>";
                                else
                                  echo "Acta de Transferencia registrada, agregar productos para tranferir.";
                                break;
                              case 3:
                                if($t->anulado==0)
                                  echo "<font color='red'>Anulado.</font>";
                                else
                                  echo "<font color='blue'>Por recepcionar.</font>";
                                break;
                              case 4:
                                if($t->anulado==0){
                                  echo "<font color='red'>Anulado.</font>";
                                }else
                                { if($farmacia->almacen==1){
                                      echo "Atendido";
                                    }else{
                                      echo "Transferencia finalizada.";
                                  }
                                }
                                break;
                              case 5:
                                if($t->anulado==0)
                                  echo "<font color='red'>Anulado.</font>";
                                else
                                  echo "<font color='blue'>Pendiente.</font>";
                                break;
                              case 6:
                                if($t->anulado==0)
                                  echo "<font color='red'>Anulado.</font>";
                                else
                                  echo "Atendido";
                                break;
                            }
                          ?>
                          </td>                                        
                          <td><?php echo $t->observacion; ?></td>                                        
                          <td class="text-center">
                          <?php if ($t->estado > 0 && $t->estado <= 2): ?>
                            <?php if ($permiso->crear == 1 || $permiso->modificar == 1): ?>
                                    <a href="/edit_documento_transferencia/{{$t->id}}" class="btn btn-success btn-md">
                                      <i class="fa fa-edit"></i>
                                    </a>
                            <?php endif; ?>
                          <?php else:  ?>                                
                                  <?php if ($t->estado == 0): ?>
                                    <a href="#" class="btn btn-success btn-md disabled"><i class="fa fa-eye"></i></a>
                                  <?php else:?>
                                    <a href="/edit_documento_transferencia/{{$t->id}}" class="btn btn-success btn-md"><i class="fa fa-eye"></i></a>
                                  <?php endif; ?>
                                <?php 
                                endif;            
                          ?>
                          </td>
            						  <td class="text-center">                          
              						  <?php if($t->estado<4):?>
                              <?php if ($t->estado == 0): ?>
                                <a class="btn btn-danger btn-md disabled" href="javascript:void(0)" onclick="javascript:void(0)"><i class="fa fa-remove"></i></a>
                              <?php else:?>
                                  <?php if($farmacia->almacen!=1): ?>
                                      <a class="btn btn-danger btn-md" href="javascript:void(0)" onclick="eliminar(<?php echo $t->id ?>)"><i class="fa fa-remove"></i></a>
                                    <?php else:?>
                                      <a class="btn btn-danger btn-md" href="javascript:void(0)" onclick="anular(<?php echo $t->id ?>)"><i class="fa fa-remove"></i></a>
                                    <?php endif;?>                                
                              <?php endif;?>              								  
              						  <?php else:?>
              								  <a class="btn btn-danger btn-md disabled" href="javascript:void(0)" onclick="javascript:void(0)"><i class="fa fa-remove"></i></a>
              						  <?php endif;?>
                          <?php //endif;?>
            						  </td>                          
                          <td class="text-center">
                          <?php if ($t->estado < 3): ?>
                            <?php if ($permiso->crear == 1 || $permiso->modificar == 1): ?>
                                    <?php if ($t->estado == 0): ?>
                                      <a href="#" class="btn btn-primary btn-md disabled"><i class="fa fa-eye"></i> 
                                    </a>
                                  <?php else:?>
                                      <a href="/registrar_productos_transferir/{{$t->id}}/{{$farmacia->id}}" class="btn btn-primary btn-md"><i class="fa fa-edit"></i> 
                                    </a>
                                  <?php endif;?>
                            <?php endif;
                                else:
                                   ?>                                            
                                    <a href="/registrar_productos_transferir/{{$t->id}}/{{$farmacia->id}}" class="btn btn-primary btn-md"><i class="fa fa-eye"></i></a>
                            <?php                                              
                                endif; ?>
                          </td>
                          <td class="text-center">
                          <?php if ($t->estado <= 2): ?>
                            <?php if ($permiso->crear == 1 || $permiso->modificar == 1): ?>
                                  <?php if ($t->estado == 0): ?>
                                    <a target="_blank" href="/imprimir_transferencia/{{$t->id}}" class="btn btn-danger btn-md"><i class="fa fa-file-pdf-o"></i> </a>
                                    <?php if($farmacia->almacen==1): ?>
                                      <a target="_blank" href="/imprimir_transferencia/{{$t->id}}/1" class="btn btn-default btn-md"><i class="fa fa-print"></i> </a>
                                    <?php endif; ?>  
                                  <?php else: ?>
                                    <?php if($farmacia->almacen==1): ?>                                      
                                      <a target="_blank" href="/imprimir_transferencia/{{$t->id}}" class="btn btn-danger btn-md"><i class="fa fa-file-pdf-o"></i> </a>
                                      <a target="_blank" href="/imprimir_transferencia/{{$t->id}}/1" class="btn btn-default btn-md"><i class="fa fa-print"></i> </a>
                                      <?php else: ?>
                                        <a href="#" class="btn btn-danger btn-md disabled"><i class="fa fa-file-pdf-o"></i></a>  
                                    <?php endif; ?>                                    
                                  <?php endif; ?>
                            <?php endif; ?>
                          <?php else: ?>
                                  <a target="_blank" href="/imprimir_transferencia/{{$t->id}}" class="btn btn-danger btn-md"><i class="fa fa-file-pdf-o"></i> </a>
                                  <?php if($farmacia->almacen==1): ?>
                                    <a target="_blank" href="/imprimir_transferencia/{{$t->id}}/1" class="btn btn-default btn-md"><i class="fa fa-print"></i> </a>
                                  <?php endif; ?>          
                          <?php endif; ?>
                          </td>
                          <?php if(Auth::user()->flagestablecimiento==1): ?>
                          <td class="text-center">                          
                            <?php if($t->estado==5):?>
                                <a class="btn btn-danger btn-md" href="javascript:void(0)" onclick="anular(<?php echo $t->id ?>)"><i class="fa fa-remove"></i></a>
                            <?php else:?>
                              <?php if($t->estado==4):?>
                                  <?php if($t->tipo==4 || $t->tipo==2) :?>
                                    <a class="btn btn-danger btn-md" href="javascript:void(0)" onclick="anular(<?php echo $t->id ?>)"><i class="fa fa-remove"></i></a>
                                  <?php else:?>
                                    <a class="btn btn-danger btn-md disabled" ><i class="fa fa-remove"></i></a>
                                  <?php endif;?> 
                              <?php else:?>
                                  <a class="btn btn-danger btn-md disabled" ><i class="fa fa-remove"></i></a>
                              <?php endif;?> 
                            <?php endif;?>                          
                          </td> 
                        <?php endif; ?>
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