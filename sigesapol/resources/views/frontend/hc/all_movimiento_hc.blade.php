<?php
    use Carbon\Carbon;
?>
@extends('frontend.layouts.master2')

@section('renderjs')
<script>
    
  $(document).ready(function () {
    $('#example').DataTable();

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
        
  });
	
  
  function eliminar(id){
	bootbox.confirm("Desea eliminar el Registro?", function(result) {
	  if (result==true) {				
		$.ajax({
		  url: '/eliminar_movimiento_hc/'+id,
			success: function(result){
			  //$(".alert-danger").html("sddsssfc").show();
				location.reload();
			}
		});
      }
	});
  }

  /*function anular(id){
  bootbox.confirm("Desea anular el Registro?", function(result) {
    if (result==true) {       
    $.ajax({
      url: '/anular_num_historia/'+id,
      success: function(result){
        location.reload();
      }
    });
      }
  });
  } */
  
</script>
<style>

</style>
@endsection
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-2">
      <div class="col-sm-12 pull-right">
        <?php if ($permiso->crear == 1): ?>
          <ul class="nav nav-pills nav-stacked">
            <li class="active"><a href="/all_movimiento_hc/"><i class="fa fa-table fa-fw"></i> Listar Salidas de Historias</a></li>
            <li><a href="/add_hc_registro_salida/{{ Auth::user()->id}}"><i class="fa fa-table fa-fw"></i> Registrar Salidas</a></li>
            <li><a href="/all_historias_recibidas/{{ Auth::user()->id}}"><i class="fa fa-table fa-fw"></i> Ingreso de Historias</a></li>
            <li><a href="/nueva_historia/"><i class="fa fa-table fa-fw"></i> Nueva Historia</a></li>
            <li><a href="/buscar_movimiento_historia/"><i class="fa fa-table fa-fw"></i> Buscar Historias</a></li>
          </ul>
        <?php endif; ?>
      </div>
    </div>
    <div class="col-sm-10">
      <div class="panel panel-body">
        <legend style="text-align:center">{{$farmacia->nombre}} <br/> {{$nombre_consultorio->nombre}}</legend>
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">Lista de Movimientos de Historias Clínicas</h3>
          </div>
          <div class="panel-body">
            <form id="frm_transferencia" class="form-horizontal" method="post" action="{{ route('busca_movimiento_hc')}}">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div clas="row">       
                <div class="row">                               
                  <div class="col-md-3">                            
                    <label class="help-block">Fecha Desde</label>
                    <input class="form-control input-sm" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde" value="<?php echo $fechaDesde ?>" maxlength="10">
                    <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
                    <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}">  
                  </div>                        
                  <div class="col-md-3">
                    <label class="help-block">Fecha Hasta</label>
                    <input class="form-control input-sm" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Hasta" value="<?php echo $fechaHasta ?>" maxlength="10">
                  </div>
                  <div class="col-md-3">
                    <br/><br/>
                    <input class="form-control btn btn-info input-sm" value="Buscar" type="submit">
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>     
        @if(count($movimiento_historias)>0)
        <div class="col-sm-12" style="border: 1px solid gray; padding-top: 10px; margin-top: 10px">
          <legend>Historias Clínicas Enviadas a los Servicios</legend>
          <div class="table-responsive">
            <table id="example" class="display table" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th>N</th>
                  <th>Nro Orden</th>
                  <th>Fecha</th>
                  <th>Destino</th>
                  <th>Area</th>
                  <th>Estado</th>
                  <th>Observaci&oacute;n</th>
                  <th style="text-align:center;width:5%">Editar</th>
		              <th style="text-align:center;width:5%">Agregar Historias</th> 
                  <th style="text-align:center;width:5%">Imprimir</th>
                  <th style="text-align:center;width:5%">Eliminar</th>
                  <?php //if(Auth::user()->flagestablecimientos==1 || Auth::user()->flagservicios==1): ?>
                    <?php //if ($permiso->modificar == 1): ?>
                      <!--th style="text-align:center;width:5%">Anular</th-->
                    <?php //endif; ?>
                  <?php //endif; ?>
                </tr>
              </thead>
              <tbody>
                <?php $n = 0; ?>
                @foreach($movimiento_historias as $t)
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
                            $texto='Transferencia Interna Servicios';
                            $destino=$t->consultorio_destino;
                  ?>                                        
                  <td>{{$t->nro_movimiento}}</td>
                  <td><?php echo $fecha; ?></td>
                  <td><?php echo $destino; ?></td>
                  <td><?php 
                          switch ($t->id_area) { 
                            case 1: echo "CONSULTORIO 1"; break;
                            case 2: echo "CONSULTORIO 2"; break;
                            case 3: echo "CONSULTORIO 3"; break;
                          }
                    ?>
                  </td>
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
                            echo "Pedido registrado, agregar historias para enviar.";
                          break;
                        case 2:
                          if($t->anulado==0)
                            echo "<font color='red'>Anulado.</font>";
                          else
                            echo "<font color='blue'>Por recepcionar.</font>";
                          break;
                        case 3:
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
                        <?php if ($permiso->crear == 1|| $permiso->modificar == 1): ?>
                                <a href="/editar_movimiento_hc/{{$t->id}}" class="btn btn-success btn-md">
                                  <i class="fa fa-edit"></i>
                                </a>                                  
                        <?php else:  ?>                                
                                <?php if ($permiso->lectura == 1):?>
                                  <a href="/editar_movimiento_hc/{{$t->id}}" class="btn btn-success btn-md">
                                    <i class="fa fa-edit"></i>
                                  </a>
                                <?php endif; ?>
                        <?php endif; ?>
                    <?php else:  ?>                                
                              <?php if ($t->estado == 0): ?>
                                  <a href="#" class="btn btn-success btn-md disabled"><i class="fa fa-eye"></i></a>
                              <?php else:?>
                                 <a href="/editar_movimiento_hc/{{$t->id}}" class="btn btn-success btn-md"><i class="fa fa-eye"></i></a>
                              <?php endif; ?>
                          <?php endif; ?>
                  </td>
      						                           
                  <td class="text-center">
                    <?php if ($t->estado < 3): ?>
                      <?php if ($permiso->crear == 1 || $permiso->modificar == 1): ?>
                                <?php if ($t->estado == 0): ?>
                                  <a href="#" class="btn btn-primary btn-md disabled"><i class="fa fa-eye"></i> </a>
                                <?php else:?>
                                  <a href="/registrar_movimiento_hc/{{$t->id}}/{{$farmacia->id}}/{{$fechaDesde}}/{{$fechaHasta}}" class="btn btn-primary btn-md"><i class="fa fa-edit"></i></a>
                                <?php endif;?>
                      <?php else:?>
                            <?php if ($permiso->lectura == 1):?>
                                    <a href="/registrar_movimiento_hc/{{$t->id}}/{{$farmacia->id}}/{{$fechaDesde}}/{{$fechaHasta}}" class="btn btn-primary btn-md"><i class="fa fa-edit"></i></a>
                            <?php endif;?>
                      <?php endif;?>
                      <?php 
                          else:
                             ?>                                            
                              <a href="/registrar_movimiento_hc/{{$t->id}}/{{$farmacia->id}}/{{$fechaDesde}}/{{$fechaHasta}}" class="btn btn-primary btn-md"><i class="fa fa-eye"></i></a>
                      <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <?php if ($t->estado <= 2): ?>
                        <?php if ($permiso->crear == 1 || $permiso->modificar == 1): ?>
                              <?php if ($t->estado == 0): ?>
                                  <a target="_blank" href="/imprimir_historias_servicios/{{$t->id}}" class="btn btn-danger btn-md"><i class="fa fa-file-pdf-o"></i> </a>                                         
                              <?php else: ?>
                                  <a href="#" class="btn btn-danger btn-md disabled"><i class="fa fa-file-pdf-o"></i></a>  
                                                                      
                              <?php endif; ?>
                        <?php else: ?>
                          <?php if ($permiso->lectura == 1):?>
                                <a target="_blank" href="/imprimir_historias_servicios/{{$t->id}}" class="btn btn-danger btn-md"><i class="fa fa-file-pdf-o"></i> </a>
                          <?php endif; ?>
                        <?php endif; ?>
                    <?php else: ?>
                            <a target="_blank" href="/imprimir_historias_servicios/{{$t->id}}" class="btn btn-danger btn-md"><i class="fa fa-file-pdf-o"></i> </a>
                                      
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <?php if ($permiso->crear == 1|| $permiso->modificar == 1): ?>                    
                        <?php if($t->estado<3):?>
                            <?php if ($t->estado == 0): ?>
                              <a class="btn btn-danger btn-md disabled" href="javascript:void(0)" onclick="javascript:void(0)"><i class="fa fa-remove"></i></a>
                            <?php else:?>
                              <a class="btn btn-danger btn-md" href="javascript:void(0)" onclick="eliminar(<?php echo $t->id ?>)"><i class="fa fa-remove"></i></a>
                            <?php endif;?>                                
                        <?php else:?>
                          <a class="btn btn-danger btn-md disabled" href="javascript:void(0)" onclick="javascript:void(0)"><i class="fa fa-remove"></i></a>
                        <?php endif;?>
                    <?php else:?>
                        <a class="btn btn-danger btn-md disabled" href="javascript:void(0)" onclick="javascript:void(0)"><i class="fa fa-remove"></i></a>
                    <?php endif;?>
                  </td> 
                    
                    <?php //if(Auth::user()->flagservicios==1): ?>
                      <?php //if ($permiso->modificar == 1): ?>
                  <!--td class="text-center">
                      <?php //if($t->estado==3):?>
                          <a class="btn btn-danger btn-md" href="javascript:void(0)" onclick="anular(<?php //echo $t->id ?>)"><i class="fa fa-remove"></i></a>
                      <?php //else:?>
                          <a class="btn btn-danger btn-md disabled" ><i class="fa fa-remove"></i></a>                                 
                      <?php //endif;?>                  
                  </td-->
                    <?php //endif; ?>
                  <?php //endif; ?>
                </tr>                        
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        @else
        <div class="col-md-12" style="border: 1px solid gray; padding-top: 10px; margin-top: 10px">
              No se registro ningun movimiento o devolución.
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

@include('frontend.includes.footer')
@endsection