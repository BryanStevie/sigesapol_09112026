
@extends('frontend.layouts.master2')
@section('renderjs')

<!-- Incluir jQuery -->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}
<script src="{{asset('js/jquery.inputmask.bundle.min.js')}}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>
  	$('#btnEnvio').on("click", function (e) {
		guardarPrestacion();

    function guardarPrestacion(){
          $("#frmPrestacion").submit();
    }
	});
</script>
@endsection
@section('content')

<div class="loader"></div>
<div class="container" style="width:90%">
  <div class="row">
    <div class="col-md-12">
      <div class="panel with-nav-tabs panel-default">
        <div class="panel-body">
          <div class="tab-content">
            <div class="tab-pane fade in active" id="tab2default">
              <fieldset>
                <div class="panel-body">
                  <div class="row">
                    <div class="col-sm-12 col-md-12">
                      <div class="row">
                        <div class="panel panel-primary filterable">
                          <div class="panel-heading">
                            <h3 class="panel-title">MÓDULO DE ENVIO MASIVO 10</h3>
                          </div>
                          <div class="panel-body">

                          <form class="form-horizontal" method="post" action="{{ route('envio_masivo_10')}}" id="frmPrestacion" autocomplete="off">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">


                        
                                <fieldset>
                             
                                    <div class="panel panel-info panel-bordered panel-sm">
                                      <div class="panel-heading">
                                        <div class="panel-title text-center">
                                          <b>DATA AUN NO ENVIADA</b>
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                            <div class="panel-body">               
                                                
                                            <div class="row">
                                                <div class="col-md-1">
                                                  <label>Id Trama</label>
                                                  <span class="form-control" style="text-align:left" name="id1" id="id1"><?php echo $listado_trama[0]->id  ?>
                                                  </span>
                                                  </div>
                                                  <div class="col-md-2">
                                                    <label>Codigo Renipress</label>
                                                    <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[0]->codigo_renipress ?> 
                                                  </div>
                                                <div class="col-md-1">
                                                  <label>Numero Lote</label>
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[0]->numero_lote ?>
                                                </div>
                                                <div class="col-md-1">
                                                  <label>Dni Paciente</label>
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[0]->paciente_nro_doc_identidad ?>
                                                </div>
                                                <div class="col-md-3">
                                                  <label>Datos Paciente</label>
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[0]->paciente_nombres.','.$listado_trama[0]->paciente_apellido_paterno.' '.$listado_trama[0]->paciente_apellido_materno ?>
                                                </div>
                                                <div class="col-md-1">
                                                  <label>Upss</label>
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[0]->id_upss_fups ?>
                                                </div>
                                                <div class="col-md-1">
                                                  <label>Estado</label>
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[0]->id_estado ?>
                                                </div>
                                                <div class="col-md-2">
                                                  <label>Seleccionar</label><br>
                                                  <input type="checkbox" name="check1">
                                                </div>
                                                <br>
                                              </div>
                                              <br>
                                              <!--------->
                                              <div class="row">
                                                 <div class="col-md-1">
                                                    <span class="form-control" style="text-align:left" name="id2" id="id2"><?php echo $listado_trama[1]->id  ?>
                                                    </span>
                                                  </div>
                                                  <div class="col-md-2">
                                                    <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[1]->codigo_renipress ?> 
                                                  </div>
                                                  <div class="col-md-1">
                                                    <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[1]->numero_lote ?>
                                                  </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[1]->paciente_nro_doc_identidad ?>
                                                </div>
                                                <div class="col-md-3">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[1]->paciente_nombres.','.$listado_trama[0]->paciente_apellido_paterno.' '.$listado_trama[0]->paciente_apellido_materno ?>
                                                </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[1]->id_upss_fups ?>
                                                </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[1]->id_estado ?>
                                                </div>
                                                <div class="col-md-2">
                                                  <input type="checkbox" name="check2">
                                                </div>
                                                <br>
                                              </div>
                                              <br>
                                              <!--------->
                                              <div class="row">
                                                 <div class="col-md-1">
                                                    <span class="form-control" style="text-align:left" name="id3" id="id3"><?php echo $listado_trama[2]->id  ?>
                                                    </span>
                                                  </div>
                                                  <div class="col-md-2">
                                                    <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[2]->codigo_renipress ?> 
                                                  </div>
                                                  <div class="col-md-1">
                                                    <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[2]->numero_lote ?>
                                                  </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[2]->paciente_nro_doc_identidad ?>
                                                </div>
                                                <div class="col-md-3">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[2]->paciente_nombres.','.$listado_trama[0]->paciente_apellido_paterno.' '.$listado_trama[0]->paciente_apellido_materno ?>
                                                </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[2]->id_upss_fups ?>
                                                </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[2]->id_estado ?>
                                                </div>
                                                <div class="col-md-2">
                                                  <input type="checkbox" name="check3">
                                                </div>
                                                <br>
                                              </div>
                                              <br>
                                                <!--------->
                                              <div class="row">
                                                 <div class="col-md-1">
                                                    <span class="form-control" style="text-align:left" name="id4" id="id4"><?php echo $listado_trama[3]->id  ?>
                                                    </span>
                                                  </div>
                                                  <div class="col-md-2">
                                                    <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[3]->codigo_renipress ?> 
                                                  </div>
                                                  <div class="col-md-1">
                                                    <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[3]->numero_lote ?>
                                                  </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[3]->paciente_nro_doc_identidad ?>
                                                </div>
                                                <div class="col-md-3">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[3]->paciente_nombres.','.$listado_trama[0]->paciente_apellido_paterno.' '.$listado_trama[0]->paciente_apellido_materno ?>
                                                </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[0]->id_upss_fups ?>
                                                </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[3]->id_estado ?>
                                                </div>
                                                <div class="col-md-2">
                                                  <input type="checkbox" name="check4">
                                                </div>
                                                <br>
                                              </div>
                                              <br>
                                                <!--------->
                                              <div class="row">
                                                 <div class="col-md-1">
                                                    <span class="form-control" style="text-align:left" name="id5" id="id5"><?php echo $listado_trama[4]->id  ?>
                                                    </span>
                                                  </div>
                                                  <div class="col-md-2">
                                                    <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[4]->codigo_renipress ?> 
                                                  </div>
                                                  <div class="col-md-1">
                                                    <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[4]->numero_lote ?>
                                                  </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[4]->paciente_nro_doc_identidad ?>
                                                </div>
                                                <div class="col-md-3">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[4]->paciente_nombres.','.$listado_trama[0]->paciente_apellido_paterno.' '.$listado_trama[0]->paciente_apellido_materno ?>
                                                </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[4]->id_upss_fups ?>
                                                </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[4]->id_estado ?>
                                                </div>
                                                <div class="col-md-2">
                                                  <input type="checkbox" name="check5">
                                                </div>
                                                <br>
                                              </div>
                                              <br>
                                                 <!--------->
                                              <div class="row">
                                                 <div class="col-md-1">
                                                    <span class="form-control" style="text-align:left" name="id6" id="id6"><?php echo $listado_trama[5]->id  ?>
                                                    </span>
                                                  </div>
                                                  <div class="col-md-2">
                                                    <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[5]->codigo_renipress ?> 
                                                  </div>
                                                  <div class="col-md-1">
                                                    <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[5]->numero_lote ?>
                                                  </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[5]->paciente_nro_doc_identidad ?>
                                                </div>
                                                <div class="col-md-3">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[5]->paciente_nombres.','.$listado_trama[0]->paciente_apellido_paterno.' '.$listado_trama[0]->paciente_apellido_materno ?>
                                                </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[5]->id_upss_fups ?>
                                                </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[5]->id_estado ?>
                                                </div>
                                                <div class="col-md-2">
                                                  <input type="checkbox" name="check6">
                                                </div>
                                                <br>
                                              </div>
                                              <br>
                                                   <!--------->
                                                   <div class="row">
                                                 <div class="col-md-1">
                                                    <span class="form-control" style="text-align:left" name="id7" id="id7"><?php echo $listado_trama[6]->id  ?>
                                                    </span>
                                                  </div>
                                                  <div class="col-md-2">
                                                    <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[6]->codigo_renipress ?> 
                                                  </div>
                                                  <div class="col-md-1">
                                                    <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[6]->numero_lote ?>
                                                  </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[6]->paciente_nro_doc_identidad ?>
                                                </div>
                                                <div class="col-md-3">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[6]->paciente_nombres.','.$listado_trama[0]->paciente_apellido_paterno.' '.$listado_trama[0]->paciente_apellido_materno ?>
                                                </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[6]->id_upss_fups ?>
                                                </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[6]->id_estado ?>
                                                </div>
                                                <div class="col-md-2">
                                                  <input type="checkbox" name="check7">
                                                </div>
                                                <br>
                                              </div>
                                              <br>
                                              <!--------->
                                              <div class="row">
                                                 <div class="col-md-1">
                                                    <span class="form-control" style="text-align:left" name="id8" id="id8"><?php echo $listado_trama[7]->id  ?>
                                                    </span>
                                                  </div>
                                                  <div class="col-md-2">
                                                    <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[7]->codigo_renipress ?> 
                                                  </div>
                                                  <div class="col-md-1">
                                                    <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[7]->numero_lote ?>
                                                  </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[7]->paciente_nro_doc_identidad ?>
                                                </div>
                                                <div class="col-md-3">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[7]->paciente_nombres.','.$listado_trama[0]->paciente_apellido_paterno.' '.$listado_trama[0]->paciente_apellido_materno ?>
                                                </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[7]->id_upss_fups ?>
                                                </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[7]->id_estado ?>
                                                </div>
                                                <div class="col-md-2">
                                                  <input type="checkbox" name="check8">
                                                </div>
                                                <br>
                                              </div>
                                              <br>
                                              <!--------->
                                              <div class="row">
                                                 <div class="col-md-1">
                                                    <span class="form-control" style="text-align:left" name="id9" id="id9"><?php echo $listado_trama[8]->id  ?>
                                                    </span>
                                                  </div>
                                                  <div class="col-md-2">
                                                    <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[8]->codigo_renipress ?> 
                                                  </div>
                                                  <div class="col-md-1">
                                                    <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[8]->numero_lote ?>
                                                  </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[8]->paciente_nro_doc_identidad ?>
                                                </div>
                                                <div class="col-md-3">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[8]->paciente_nombres.','.$listado_trama[0]->paciente_apellido_paterno.' '.$listado_trama[0]->paciente_apellido_materno ?>
                                                </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[8]->id_upss_fups ?>
                                                </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[8]->id_estado ?>
                                                </div>
                                                <div class="col-md-2">
                                                  <input type="checkbox" name="check9">
                                                </div>
                                                <br>
                                              </div>
                                              <br>
                                              <!--------->
                                              <div class="row">
                                                 <div class="col-md-1">
                                                    <span class="form-control" style="text-align:left" name="id10" id="id10"><?php echo $listado_trama[9]->id  ?>
                                                    </span>
                                                  </div>
                                                  <div class="col-md-2">
                                                    <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[9]->codigo_renipress ?> 
                                                  </div>
                                                  <div class="col-md-1">
                                                    <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[9]->numero_lote ?>
                                                  </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[9]->paciente_nro_doc_identidad ?>
                                                </div>
                                                <div class="col-md-3">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[9]->paciente_nombres.','.$listado_trama[0]->paciente_apellido_paterno.' '.$listado_trama[0]->paciente_apellido_materno ?>
                                                </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[9]->id_upss_fups ?>
                                                </div>
                                                <div class="col-md-1">
                                                  <span class="form-control" style="text-align:left" name="" id=""><?php echo $listado_trama[9]->id_estado ?>
                                                </div>
                                                <div class="col-md-2">
                                                  <input type="checkbox" name="check10">
                                                </div>
                                                <br>
                                              </div>
                                              <br>
                                          </div>
                                        </div>  
                                    
                   
                                </fieldset>

                            </form>
                          </div>
                        </div>
                        <a href="/all_auditoria" class="btn btn-warning pull-left"  id="btnPrestacion">LISTADO </a>
                        <a class="btn btn-primary pull-right"  id="btnEnvio">Enviar Datos Seleccionados </a>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
          
            </fieldset>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div id="openOverlayOpc" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <div id="id_content_OverlayoneOpc" class="modal-content" style="padding: 0px;margin: 0px">

        <div class="modal-body">

          <div id="diveditpregOpc"></div>

        </div>

      </div>

    </div>
  </div>





  @include('frontend.includes.footer')
  @endsection