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
</script>
@endsection
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-2">
      <div class="col-sm-12 pull-right">
          <ul class="nav nav-pills nav-stacked">
            <li><a href="/all_movimiento_hc/"><i class="fa fa-table fa-fw"></i> Listar Salidas de Historias</a></li>
            <li><a href="/add_hc_registro_salida/{{ Auth::user()->id}}"><i class="fa fa-table fa-fw"></i> Registrar Salidas</a></li>
            <li  class="active"><a href="/all_historias_recibidas/{{ Auth::user()->id}}"><i class="fa fa-table fa-fw"></i> Ingreso de Historias</a></li>
            <li><a href="/nueva_historia/"><i class="fa fa-table fa-fw"></i> Nueva Historia</a></li>
            <li><a href="/buscar_movimiento_historia/"><i class="fa fa-table fa-fw"></i> Buscar Historias</a></li>
          </ul>
        
      </div>
    </div>
    <div class="col-sm-10">
      <div class="panel panel-body">
          <legend style="text-align:center">{{$establecimiento->nombre}} <br/> {{$nombre_consultorio->nombre}}</legend>
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Lista de Movimientos de Historias Clínicas Recibidas</h3>
            </div>
            <div class="panel-body">
              <div class="col-sm-12">
                  <form id="frm_transferencia" class="form-horizontal" method="post" action="{{ route('busca_hc_recibidas')}}">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <div clas="row">       
                          <div class="row">                               
                            <div class="col-md-2">                            
                                <label class="help-block">Fecha Desde</label>
                                <input class="form-control input-sm" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde" value="<?php echo $fechaDesde ?>" maxlength="10">
                                <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
                                
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
          
            @if(count($movimiento_historias)>0)
              <div class="col-sm-12" style="border: 1px solid gray; padding-top: 10px; margin-top: 10px">
                <legend>Historias Clínicas Recibidas por los Servicios</legend>
                <div class="table-responsive">
                    <table id="example" class="display table" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>N</th>
                          <th>Nro Orden</th>
                          <th>Fecha</th>
                          <th>Origen</th>
                          <th>Area</th>
                          <th>Situación</th>
                          <th>Nro Recepción</th>
                          <th style="text-align:center;width:5%">Ver</th>
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
                              $origen=$t->consultorio_origen;
                             ?>                                        
                          <td>{{$t->nro_movimiento}}</td>
                          <td><?php echo $fecha; ?></td>
                          <td><?php echo $origen; ?></td>
                          <td><?php 
                                    switch ($t->id_area) { 
                                      case 1: echo "CONSULTORIO 1"; break;
                                      case 2: echo "CONSULTORIO 2"; break;
                                      case 3: echo "CONSULTORIO 3"; break;
                                    }
                            ?></td>
                          <td>   
                          <?php
                            switch ($t->estado) {
                              case 2:
                                    echo " Por Recepcionar <i class='fa fa-flag' style='color:red'></i>"; break;
                              case 3:
                                    echo " Recepcionado <i class='fa fa-flag' style='color:green'></i>"; break;
                                
                            }
                          ?>
                          </td>                                        
                          <td>{{$t->nro_entrada}}</td>
                          <td class="text-center">
                          <?php if ($t->estado < 3): ?>
                                      <?php if ($t->estado == 0): ?>
                                        <a href="#" class="btn btn-primary btn-md disabled"><i class="fa fa-eye"></i> </a>
                                      <?php else:?>
                                        <a href="/ver_movimiento_hc/{{$t->id}}/{{$establecimiento->id}}" class="btn btn-primary btn-md"><i class="fa fa-edit"></i></a>
                                      <?php endif;?>
                            <?php 
                                else:
                                   ?>                                            
                                    <a href="/ver_movimiento_hc/{{$t->id}}/{{$establecimiento->id}}" class="btn btn-primary btn-md"><i class="fa fa-eye"></i></a>
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
                    No se registro ningun movimiento.
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
</div>

@include('frontend.includes.footer')
@endsection