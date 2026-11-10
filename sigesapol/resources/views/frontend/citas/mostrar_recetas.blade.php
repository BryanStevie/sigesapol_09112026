@section('renderjs')

{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}

@endsection
@section('content')



      <div class="row">
            <div class="col-sm-12 col-md-12">
              @if(count($receta)>0)
                    <?php foreach($receta as $rowReceta): ?>
                    <br />
                    <div style="clear:both"></div>
                    <fieldset>
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-md-12">
                              <label class="col-md-1" style="padding-left:0px">RECETA VALE N°</label>
                              <div class="col-md-1">
                                <p><?php echo $rowReceta->nro_receta; ?></p>
                              </div>
                              <label class="col-md-1" style="padding-left:0px;text-align:right">FEC. EXPEDICIÓN</label>
                              <div class="col-md-1">
                                <p><?php echo date("d-m-Y", strtotime($rowReceta->fecha_registro)); ?></p>
                              </div>
                              <label class="col-md-1" style="padding-left:0px">FARMACIA</label>
                              <div class="col-md-4" style="padding:0px">
                                <p><?php echo $rowReceta->farmacia; ?></p>
                              </div>
                              <?php if ($rowReceta->estado == 1) { ?>
                                <div class="col-md-3" style="padding-bottom:0px;line-height:30px;color:#008000;font-weight:bold">
                                    DISPENSADA EL <?php echo $rowReceta->fecha_expedicion ?>          
                                </div>
                              <?php                
                              }
                              else{ //estado 2 
                              ?>
                                <div class="col-md-3" style="padding-bottom:0px;line-height:30px;color:#FF5151;font-weight:bold">
                                    NO DISPENSADA
                                </div>    
                              <?php } ?>
                            </div>
                          </div>
                          
                        </div>
                      </div>
                    </fieldset>
                    <?php endforeach;?>
              @else
                <div style="padding-bottom:0px;line-height:30px;color:#0000FF;font-weight:bold">
                            ESTA ATENCIÓN NO CUENTA CON RECETAS PRESCRITAS          
                </div>
              @endif
    </div>
  

