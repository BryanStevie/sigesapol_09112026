@extends('frontend.layouts.master')
@section('renderjs')

<style>
  body {
    background-color: #eee;
  }

  *[role="form"] {
    max-width: 530px;
    padding: 15px;
    margin: 0 auto;
    background-color: #fff;
    border-radius: 0.3em;
  }

  *[role="form"] h2 {
    margin-left: 5em;
    margin-bottom: 1em;
  }

  .panel-heading div {
    margin-top: -18px;
    font-size: 15px;
  }

  .panel-heading div span {
    margin-left: 5px;
  }
</style>

<script>
  $(document).ready(function() {
  $("#frmlotizar").submit(function(e) {
    disponible = parseFloat($("#frmlotizar").find("#disponible").val());
    cantidad = parseFloat($("#frmlotizar").find("#cantidad").val());
    if (cantidad > disponible) {
      alert("La cantidad (" + cantidad + ") no debe ser mayor a la cantidad disponible por lotizar (" + disponible + ")");
      e.preventDefault();
      return false;
    }
  });
});
</script>
@endsection
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel-group">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-th"></i> {{$kardex->nombre_producto}}</h3>
            <div class="pull-right">
              <a class="btn btn-default btn-xs" href="/stocksbyFamarcias/{{$id_farmacia}}">
                <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
              </a>
            </div>
          </div>
          <div class="panel-body">
            <div class="pull-right col-md-12" style="margin-bottom:20px; text-align: right">
              <!--
                            @if(Auth::user()->id_farmacia!=2 &&  Auth::user()->id_farmacia!=3 && Auth::user()->id_farmacia!=4)
                            <a class="btn btn-default btn-xs" href="/stocksbyFamarcia/{{ Auth::user()->id }}"><i class="fa fa-medkit"></i> Ver Stock General</a>
                            @endif
							-->

              <!--<a href="/stocksbyFamarcias/{{$kardex->id_farmacia}}" class="btn btn-default btn-xs">
                                <i class="fa fa-medkit"></i> Ver Stock General
                            </a>-->
            </div>
            <div class="col-md-2" style="margin-bottom: 20px !important">
              <label>Código</label>
              <input class="form-control" value="{{$kardex->codigo_producto}}">
            </div>
            <div class="col-md-6" style="margin-bottom: 20px !important">
              <label>Producto Farmaceútico</label>
              <input class="form-control" value="{{$kardex->nombre_producto}}">
            </div>
            <div class="col-md-2" style="margin-bottom: 20px !important">
              <label>Unidad</label>
              <input class="form-control" value="{{$kardex->abreviatura}}">
            </div>
            <div class="col-md-2" style="margin-bottom: 20px !important">
              <label>Cantidad Total</label>
              @if($kardex->id_producto==11045 || $kardex->id_producto==15938 || $kardex->id_producto==14607 || $kardex->id_producto==14608)
              <input class="form-control" value="{{($kardex->total)/1000}}">
              @else
              <input class="form-control" value="{{$kardex->total}}">              
              @endif

            </div>
            <!--@if(Auth::user()->id_farmacia==1)
                        <div class="col-md-2" style="margin-bottom: 20px !important">
                            <button data-toggle="modal" data-target="#squarespaceModal" class="form-control btn btn-success center-block">Lotizar</button>
                        </div>
                        @endif-->
            <div class="row">
              <div class="col-md-12">
                <div class="panel-group">
                  <div class="panel panel-info">
                    <div class="panel-heading">
                      <h3 class="panel-title"><i class="glyphicon glyphicon-th"></i> LOTES ASIGNADOS</h3>
                    </div>

                    <div class="panel-body">
                      @if(count($lotes)>0)
                      <table class="table">
                        <thead>
                          <tr>
                            <th>Código - Producto Farmaceútico</th>
                            <th>Unidad</th>
                            <th>Reg. Sanitario</th>
                            <th>Lote</th>
                            <th>Fec. Venc.<br><span style="font-size:10px">(mm/aaaa)</span></th>
                            <th>Fecha de Creación</th>
                            <th>Cantidad</th>
                            @permission('direoss_saludpol')
                            @endauth
                            <!--@role('Almacenero_recetas')
                                                        <th>Editar</th>
                                                        @endauth-->
                          </tr>
                        </thead>
                        <tbody>
                          <?php $c = 0; ?>
                          @foreach($lotes as $l)
                          <tr>
                            <td>{{$l->codigo_producto}} - {{$l->prod_farmaceutico}}
                              <?php if($l->presentacion==1)
                                    echo ' ('.$l->cantidad.' X '.$l->presentacion_comercial.' )';
                            ?>



                            </td>
                            <td>{{$l->abreviatura}}</td>
                            <td>{{$l->registro_sanitario}}</td>
                            <td>{{$l->lote}}</td>
                            <td>
                              <?php
                                                            $originalDate2 = $l->fecha_vencimiento;
                                                            //$fecha = date("m-Y", strtotime($originalDate2));
                                                            //echo $fecha;
                                                            echo $originalDate2;
                                                            ?>
                            </td>
                            <td>
                              <?php
                                                                $originalDate5 = $l->created_at;
                                                                $fecha_creacion = date("d-m-Y (H:i:s)", strtotime($originalDate5));  
                                                                echo $fecha_creacion;
                                                            ?>
                            </td>
                            <td style="text-align:center">
                            @if($l->id_producto==11045 || $l->id_producto==15938 || $l->id_producto==14607 || $l->id_producto==14608)
                            {{($l->cantidad*$l->presentacion_comercial)/1000}}
                            @else
                              {{$l->cantidad*$l->presentacion_comercial}}
                            @endif
                              
                              <?php
                                if($l->id_producto==11045 || $l->id_producto==15938 || $l->id_producto==14607 || $l->id_producto==14608):
                                  $c = $c + ($l->cantidad*$l->presentacion_comercial)/1000; 
                                else:
                                  $c = $c + $l->cantidad*$l->presentacion_comercial; 
                                endif;
                              ?>
                            </td>


                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                      <div class="col-md-10" style="text-align: right; margin-top: 15px"><label>Total</label></div>
                      <div class="col-md-2" style="text-align: right; margin-top: 15px">
                        <input class="form-control" value="<?php echo $c; ?>">
                      </div>
                      @else
                      <div class="col-md-12">
                        <label>Es necesario lotizar el medicamento selecionado.</label>
                      </div>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> <!-- ./container -->

<!-- line modal -->

@include('frontend.includes.footer')
@endsection