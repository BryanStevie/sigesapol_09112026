@section('renderjs')

{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}

@endsection
@section('content')

<div class="container" style="width:100%">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="row">
        <div class="panel-body">
          <fieldset>
            <div class="panel panel-info panel-bordered panel-sm">
              <div class="panel-heading">
                <div class="panel-title text-center" style="position: relative">
                  <b>TRATAMIENTO Y/O TERAPIA</b>
                </div>
              </div>
              <div class="panel-body">
                <div class="col-md-12">
                  <?php $model_receta = new \App\Models\Receta_vale;
                    foreach($indicaciones as $row1): ?>
                      <p><b><p>{{ \Carbon\Carbon::parse($row1->fecha_atencion)->format('d-m-Y H:i') }}</b></p><br/>
                      <p>{!! nl2br(e($row1->tratamiento_auxiliar)) !!}</p><br/>
                      <?php $receta = $model_receta->GetDatosRecetasByIdEvaluacion($row1->id); ?>
                      <br/>
                      @if(count($receta)>0)
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>PRODUCTOS MEDICOS</b>
                          </div>
                        </div>
                        <div class="panel-body panel-body-receta">
                          <?php foreach($receta as $rowReceta):
                                if ($rowReceta->estado == 1) { ?>
                                  <div style="padding-bottom:0px;line-height:30px;color:#008000;font-weight:bold"> LA RECETA HA SIDO DISPENSADA EL {{$rowReceta->fecha_expedicion }}</div>
                          <?php                
                                }
                                else{ //estado 2 
                          ?>
                                  <div style="padding-bottom:0px;line-height:30px;color:#FF5151;font-weight:bold"> LA RECETA AUN NO HA SIDO DISPENSADA </div>    
                          <?php }
                                $model_productos_receta = new \App\Models\Producto_receta;
                                $medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($rowReceta->id);
                          ?>
                          <br/>
                          <div style="clear:both"></div>
                          <fieldset>
                            <div class="panel panel-info panel-bordered panel-sm">
                              <div class="panel-body">
                                <div class="form-group">
                                  <div class="col-md-12">
                                    <label class="col-md-2" style="padding-left:0px">RECETA VALE N°</label>
                                    <div class="col-md-1">
                                      <p>{{$rowReceta->nro_receta}}</p>
                                    </div>
                                    <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. EXPEDICIÓN</label>
                                    <div class="col-md-2">
                                      <p><?php echo date("d-m-Y", strtotime($rowReceta->fecha_registro)); ?></p>
                                    </div>
                                    <label class="col-md-1" style="padding-left:0px">FARMACIA</label>
                                    <div class="col-md-4" style="padding:0px">
                                      <p>{{$rowReceta->farmacia }}</p>
                                    </div>
                                  </div>
                                </div>
                                <table class="display" cellspacing="0" width="100%">
                                  <thead>
                                    <tr>
                                      <th width="28%">Producto farmacéutico o dispositivo médico</th>
                                      <th width="8%">Unidad Medida</th>
                                      <th width="8%">Tiempo(Dias)</th>
                                      <th width="15%">Posologia</th>
                                      <th width="10%">Via Administración</th>
                                      <th width="8%">Cantidad Prescrita</th>
                                      <th width="10%">diagnosticos</th>
                                      @if ($rowReceta->estado == 1)
                                      <th width="15%">observacion</th>
                                      <th width="8%">(Dias) Farmacia</th>
                                      @endif
                                    </tr>
                                  </thead>
                                  <tfoot>
                                  </tfoot>
                                  <?php foreach($medicamentos as $row): ?>
                                  <tr>
                                    <td>{{$row->codigo}}-{{$row->nombre}}
                                        <?php 
                                          if($row->codigo=='59075700'):
                                            echo "(".$row->cantidad_prescrita."L)";
                                          endif; 
                                        ?>
                                    </td>
                                    <td>{{$row->abreviatura}}</td>
                                    <td>{{$row->tiempo_periodo}}</td>
                                    <td>{{$row->posologia}}</td>
                                    <td>{{$row->via}}</td>
                                    <td><?php if($row->codigo=='59075700' || $row->codigo=='PF00718' || $row->codigo=='PF01047' || $row->codigo=='PF01007' || $row->codigo=='PF01008'):
                                                echo $row->cantidad_prescrita/1000;
                                              else:
                                                echo $row->cantidad_prescrita;
                                        endif; ?>
                                    </td>
                                    <td><?php echo $row->cod_diagnostico; ?>    
                                    </td>
                                    @if ($rowReceta->estado == 1) 
                                    <td><?php
                                            if($row->cantidad_prescrita>$row->cantidad_dispensada ):
                                              switch ($row->id_observacion) {
                                                case 0: if(($row->cantidad_dispensada==0) || ($row->cantidad_dispensada!=0)): echo "Sin Stock"; else: echo " "; endif;break;
                                                case 1: if(($row->cantidad_dispensada==0) || ($row->cantidad_dispensada!=0)) echo "Sin Stock"; break;
                                                case 2: if(($row->cantidad_dispensada==0) || ($row->cantidad_dispensada!=0)) echo "Paciente tiene"; break;
                                                case 3: if($row->cantidad_dispensada==0) echo "No requiere"; break;
                                                case 4: if($row->cantidad_dispensada==0) echo "No corresponde"; break;
                                                case 5: if($row->cantidad_dispensada==0) echo "Mal prescrito"; break;
                                                case 6: if($row->cantidad_dispensada==0) echo "Alerta DIGEMID"; break;
                                                case 7: if($row->cantidad_dispensada==0) echo "Suspendido"; break;
                                                case 8: if($row->cantidad_dispensada==0) echo "CIE 10 (DIAGNOSTICO)"; break;
                                                case 9: if($row->cantidad_dispensada==0) echo "N DE CAMA "; break;
                                                case 10: if($row->cantidad_dispensada==0) echo "NOMBRE DEL SERVICIO"; break;
                                                case 11: if($row->cantidad_dispensada==0) echo "CONCENTRACION DEL PRODUCTO FARMACEUTICO "; break;
                                                case 12: if($row->cantidad_dispensada==0) echo "FORMA FARMACEUTICA DEL PRODUCTO FARMACEUTICO"; break;
                                                case 13: if($row->cantidad_dispensada==0) echo "VIA DE ADMINISTRACION"; break;
                                                case 14: if($row->cantidad_dispensada==0) echo "CANTIDAD PRESCRITA"; break;
                                                case 15: if($row->cantidad_dispensada==0) echo "FIRMA DEL MEDICO PRESCRIPTOR"; break;
                                                case 16: if($row->cantidad_dispensada==0) echo "ERROR DE DOSIFICACIÓN"; break;
                                                case 17: if($row->cantidad_dispensada==0) echo "SEGÚN INDICACIÓN MÉDICA"; break;
                                              } 
                                            else:
                                              echo " ";
                                            endif;
                                      ?>   
                                    </td>
                                    <td><?php echo $row->dia_farmacia; ?>    
                                    </td>
                                    @endif
                                  </tr>
                                  <?php endforeach;?>
                                  <tbody>
                                  </tbody>
                                </table>
                              </div> <!--div panel-body -->
                            </div> <!--div panel-info -->
                          </fieldset>
                          <?php endforeach;?>
                        </div> <!--div panel-body-receta -->
                      </div> <!--div panel-info -->
                      @endif
                    <?php endforeach;?>
                    </div> <!--div col-md-12 -->
                  </div> <!--div panel-body -->
                </div><!--div panel-info -->
              </fieldset>
              
        </div>
      </div>
    </div>
  </div>
</div>

