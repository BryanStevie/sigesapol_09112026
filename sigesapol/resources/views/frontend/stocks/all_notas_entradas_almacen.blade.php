<?php
    use Carbon\Carbon;
?>

@extends('frontend.layouts.master2')
@section('renderjs')
<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });

    $('#fechaDesde').appendDtpicker({
    "dateOnly": true,
            "autodateOnStart": false,
            "minuteInterval": 5,
            "amPmInTimeList": false,
            "locale": "es",
            "closeOnSelected": true,
            "dateFormat": "DD-MM-YYYY"
    });

    $('#fechaHasta').appendDtpicker({
    "dateOnly": true,
            "autodateOnStart": false,
            "minuteInterval": 5,
            "amPmInTimeList": false,
            "locale": "es",
            "closeOnSelected": true,
            "dateFormat": "DD-MM-YYYY"
            //"dateFormat": "YYYY-MM-DD hh:mm",
    });
	
	function eliminar(id){
		bootbox.confirm("Desea eliminar el Registro?", function(result) {
			if (result==true) {				
				$.ajax({
					url: '/eliminar_nota_entrada_almacen/'+id,
					success: function(result){						
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
          url: '/anular_ingreso/'+id,
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
                    <h3 class="panel-title">Lista de Notas de Entrada</h3>                    
                </div>
                <div class="panel-body">
                    
                    

                    <legend style="text-align:center">{{$farmacia->nombre}}</legend>
                    <div class="panel panel-primary filterable" >
                        <div class="panel-body">
                          <div class="col-sm-12">
                              <form id="frm_transferencia" class="form-horizontal" method="post" action="{{ route('buscar_nota_entrada_almacen')}}">
                                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                  <div clas="row">       
                                      <div class="row">                               
                                        <div class="col-md-2">                            
                                            <label class="help-block">Fecha Desde</label>
                                            <input class="form-control input-sm" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde" value="<?php echo $fechaDesde ?>" maxlength="10">
                                            <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
                                            <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}">  
                                        </div>                        
                                        <div class="col-md-2">
                                            <label class="help-block">Fecha Hasta</label>
                                            <input class="form-control input-sm" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Hasta" value="<?php echo $fechaHasta ?>" maxlength="10">
                                        </div>
                                        <div class="col-md-2">
                                            <br/><br/>
                                            <input class="form-control btn btn-info input-sm" value="Buscar" type="submit">
                                        </div>
                                      </div>
                                  </div>                    
                                  <div class="row">                        
                                  </div>
                              </form>
                          </div>   
                        </div>  
                      </div>
                      <div class="col-sm-12 pull-right">
                        <?php if ($permiso->crear == 1): ?>
                            <a href="/add_documento_nota_entrada/{{ Auth::user()->id}}" class="btn btn-success btn-md">Nueva Nota de Entrada <i class="fa fa-file"></i> </a>
                        <?php endif; ?>
                        <?php if ($permiso->lectura == 1): ?>
                            <a href="" class="btn btn-info btn-md">Ver Notas de Entrada <i class="fa fa-eye"></i></a>
                        <?php endif; ?>
                    </div>
                    @if(count($pecosas)>0)
                    <div class="col-sm-12" style="border: 1px solid gray; padding-top: 10px; margin-top: 10px">
                        <legend>NOTAS DE INGRESOS</legend>
                        <div class="table-responsive">
                            <table id="example" class="display table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;width:5%"><p style="font-size:12px">N</p></th>                             
                                        <th style="text-align:center;width:5%"><p style="font-size:12px">Nro</p></th>
                                        <th style="text-align:center;width:5%"><p style="font-size:12px">#Nota</p></th>
                                        <th style="text-align:center;width:5%"><p style="font-size:12px">Fecha</p></th>     
                                        <th style="text-align:center;width:10%"><p style="font-size:12px">Estado</p></th>         
                                        <th style="text-align:center;width:30%"><p style="font-size:12px">Origen</p></th>
                                        <th style="text-align:center;width:10%"><p style="font-size:12px">Concepto</p></th>  
                                        <th style="text-align:center;width:5%"><p style="font-size:12px">SIAF</p></th>
                                        <th style="text-align:center;width:20%"><p style="font-size:12px">Proveedor</p></th>
                                        <th style="text-align:center;width:5%"><p style="font-size:12px">Ver</p></th>
										<th style="text-align:center;width:5%"><p style="font-size:12px">Eliminar</p></th>
                                        <th style="text-align:center;width:5%"><p style="font-size:12px">Agregar Productos</p></th>
                                        <th style="text-align:center;width:10%"><p style="font-size:12px">Imprimir/PDF</p></th>
                                        @if(Auth::user()->flagresponsablefarmacia==1)
                                        <th style="text-align:center;width:5%"><p style="font-size:12px">Anular</p></th>
                                        @endif
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
                                        <td><p style="font-size:12px"><?php
                                            $n++;
                                            echo $n;
                                            ?></p>
										</td>
                                        <td><p style="font-size:12px">{{$t->numero}}</p></td>
                                        <td><p style="font-size:12px">{{$t->nota_entrada}}</p></td>
                                        <td><p style="font-size:12px"><?php echo $t->fecha_entrada; ?></p></td>
                                        <td><p style="font-size:12px"><?php
                                                switch ($t->estado) {
                                                  case 0:
                                                    if($t->anulado==0)
                                                      echo "<font color='red'>Anulado.</font>";
                                                    else
                                                      echo "<font color='red'>Eliminado.</font>";
                                                    break;
                                                  case 1:
                                                    if($t->anulado==0)
                                                      echo "<font color='red'>Anulado.</font>";
                                                    else
                                                      echo "Agregar productos para ingreso.";
                                                    break;
                                                  case 2:
                                                    if($t->anulado==0)
                                                      echo "<font color='red'>Anulado.</font>";
                                                    else
                                                      echo "<font color='blue'>Finalizado.</font>";
                                                    break;
                                                }
                                              ?>
                                            </p>
                                        </td>
                                        <td><p style="font-size:12px">
                                            <?php
                                                if($t->concepto==11){
                                                    echo $t->nombre_est;
                                                }
                                                else
                                                {
                                                    if($t->concepto==7 || $t->concepto==8){
                                                        echo $t->proveedores2;
                                                    }
                                                    else
                                                    {
                                                        echo $t->nombre_dependencia; 
                                                    }
                                                }
                                            ?></p>
                                        </td>
                                        <td>@if($t->tipo_proceso==12)
                                            <p style="font-size:12px">CARTA</p>
                                            @else
                                            <p style="font-size:12px"><?php echo $t->descripcion_conceptos; ?></p>
                                            @endif
                                        </td>
                                        <td>@if($t->tipo_proceso==12)
                                            <p style="font-size:12px">  </p> 
                                            @else  
                                            <p style="font-size:12px"><?php echo $t->nro_exp_siaf; ?>  </p> 
                                            @endif                                           
                                        </td>
										<td><p style="font-size:12px"><?php echo $t->ruc_proveedor.'-'.$t->proveedores; ?></p></td>
                                        <td class="text-center">
                                            <?php if ($t->estado == 1): ?>
                                                <?php if ($permiso->crear == 1 || $permiso->modificar == 1): ?>
                                                    <a href="/edit_nota_entrada/{{$t->id}}/{{ Auth::user()->id}}" class="btn btn-success btn-md">
                                                        <i class="fa fa-edit"></i>
                                                    </a>       
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php if ($t->estado == 2): ?>
                                                        <a href="/edit_nota_entrada/{{$t->id}}/{{ Auth::user()->id}}" class="btn btn-success  btn-md">
                                                        <i class="fa fa-eye"></i></a>
                                                <?php else: ?>
                                                        <a href="#" disabled="disabled" class="btn btn-success  btn-md">
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
                                                <?php if ($t->estado == 2): ?>                                                
                                                        <a href="/recepcionar_productos_guia_entrada/{{$t->id}}/{{ Auth::user()->id}}" class="btn btn-primary btn-md">
                                                        <i class="fa fa-eye"></i></a>
                                                <?php else: ?>
                                                        <a href="#" disabled="disabled" class="btn btn-primary btn-md">
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
                                                <a target="_blank" href="/print_nota_entrada_almacen/{{$t->id}}/1" class="btn btn-default btn-md">
                                                    <i class="fa fa-print"></i> 
                                                </a>
                                                <a target="_blank" href="/print_nota_entrada_almacen/{{$t->id}}/2" class="btn btn-danger btn-md">
                                                    <i class="fa fa-file-pdf-o"></i> 
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        @if(Auth::user()->flagresponsablefarmacia==1)
                                        <td class="text-center">
                                            <?php if($t->estado==2):?>
                                                <?php if($tiempo<30):?>
                                                    <a class="btn btn-danger btn-md" href="javascript:void(0)" onclick="anular(<?php echo $t->id ?>)"><i class="fa fa-remove"></i></a>
                                                <?php else:?>
                                                    <a class="btn btn-danger btn-md disabled" ><i class="fa fa-remove"></i></a>
                                                <?php endif;?>                            
                                              <?php else:?>
                                                  <a class="btn btn-danger btn-md disabled" ><i class="fa fa-remove"></i></a>
                                            <?php endif;?>                          
                                          </td> 
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="col-md-12" style="border: 1px solid gray; padding-top: 10px; margin-top: 10px">
                        No se registro ninguna nota de entrada
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('frontend.includes.footer')
@endsection




