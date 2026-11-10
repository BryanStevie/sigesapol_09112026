
<title>Sistema de Farmacia</title>

<style>
/*****************/
.modal-dialog {
  width: 90%;
  }
  
#tablemodal{
    border-spacing: 0;
    display: flex;/*Se ajuste dinamicamente al tamano del dispositivo**/
    max-height: 80vh; /*El alto que necesitemos**/
    overflow-y: auto; /**El scroll verticalmente cuando sea necesario*/
    overflow-x: hidden;/*Sin scroll horizontal*/
    table-layout: fixed;/**Forzamos a que las filas tenga el mismo ancho**/
    width: 98vw; /*El ancho que necesitemos*/
    border:1px solid #c4c0c9;
}

#tablemodal thead{
    background-color: #e2e3e5;
    position: fixed !important;
}


#tablemodal th{
    border-bottom: 1px solid #c4c0c9;
    border-right: 1px solid #c4c0c9;
}

#tablemodal th{
    font-weight: normal;
    margin: 0;
    max-width: 9.5vw; 
    min-width: 9.5vw;
    word-wrap: break-word;
    font-size: 10px;
  font-weight:bold;
    height: 3.5vh !important;
  line-height:12px;
  vertical-align:middle;
  /*height:20px;*/
    padding: 4px;
    border-right: 1px solid #c4c0c9;
}

#tablemodal td{
    font-weight: normal;
    margin: 0;
    max-width: 9.5vw; 
    min-width: 9.5vw;
    word-wrap: break-word;
    font-size: 11px;
    height: 3.5vh !important;
    padding: 4px;
    border-right: 1px solid #c4c0c9;
}

#tablemodal tbody tr:hover td, #tablemodal1 tbody tr:hover th {
  /*background-color: red!important;*/
  font-weight:bold;
  /*mix-blend-mode: difference;*/
  
}

#tablemodalm7{
  
}
</style>

<script type="text/javascript">

$(document).ready(function() {
    
});

function ver_det_atencion(id){
    $('#nrorecmodal').text(id);
    $.ajax({
    url: '/mostrar_atencion/' + id,
            async: false,
            success: function(result){
            $("#muestra-det-atencion").html(result);
            },
            complete: function () {
            $("#myModalAtencion").modal();
            }
    });
    }
</script>


<body class="hold-transition skin-blue sidebar-mini">
    <div>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                  <div class="box box-primary">
                    <br>
                    <div class="box-body">
                        <div class="table-responsive">
                            <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-body">
                                        <div class="form-group">
                                          <div class="col-md-2">
                                            <label>TEMPERATURA (°C) &nbsp;</label>
                                            <p><?php echo $triaje_inicial->temperatura?></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label>TALLA (cm) &nbsp;</label>
                                            <p><?php echo $triaje_inicial->talla_cm?></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label>PESO (Kg) &nbsp;</label>
                                            <p><?php echo $triaje_inicial->peso_kg?></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label>IMC &nbsp;</label>
                                            <p></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label>PESO POR ALTURA &nbsp;</label>
                                            <p></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label>Sa02 (%) &nbsp;</label>
                                            <p><?php echo $triaje_inicial->sa02_porcentaje?></p>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <div class="col-md-2">
                                            <label>TAMAÑO DE CINTURA (cm) &nbsp;</label>
                                            <p><?php echo $triaje_inicial->tamano_cintura_cm?></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label>FREC. CARDIACA FETAL (/min) &nbsp;</label>
                                            <p><?php echo $triaje_inicial->frecuencia_cardiaca_fetal?></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label>PRESIÓN ARTERIAL &nbsp;</label>
                                            <p><?php echo $triaje_inicial->presion_arterial_x?></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label>FREC. RESPIRATORIA (/min) &nbsp;</label>
                                            <p><?php echo $triaje_inicial->frecuencia_respiratoria?></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label>FREC. CARDIACA (/min) &nbsp;</label>
                                            <p><?php echo $triaje_inicial->frecuencia_cardiaca?></p>
                                          </div>
                                          <div class="col-md-2">
                                            <label>Fi02 (%) &nbsp;</label>
                                            <p><?php echo $triaje_inicial->fi02?></p>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                            <div class="panel with-nav-tabs panel-default">
                                <div class="panel-heading">
                                  <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab20default" data-toggle="tab" style="font-size:12px">ANAMNESIS</a></li>
                                    <li><a href="#tab21default" data-toggle="tab" style="font-size:12px">EXAMEN FÍSICO</a></li>
                                    <li><a href="#tab22default" data-toggle="tab" style="font-size:12px">TRATAMIENTO HABITUAL</a></li>
                                    <li><a href="#tab23default" data-toggle="tab" style="font-size:12px">PLAN DE TRABAJO</a></li>
                                  </ul>
                                </div>
                                <div class="panel-body">
                                    <div class="tab-content">
                                        <div class="tab-pane fade in active" id="tab20default">
                                            <div class="panel-body">
                                                <div class="col-md-12">
                                                  <label>MOTIVO DE VISITA</label>
                                                  <p><?php echo $prestacion->motivo_visita?></p>
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="col-md-12">
                                                  <label>TIEMPO DE ENFERMEDAD</label>
                                                    <p><?php echo $prestacion->tiempo_enfermedad?></p>
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="col-md-12">
                                                  <label>RELATO CRONOLOGICO</label>
                                                  <p><?php echo $prestacion->anamnesis?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab21default">
                                        @if(Auth::user()->id_establecimiento==75)
                                            @if($prestacion->id_consultorio == 28 || $prestacion->id_consultorio == 106 || $prestacion->id_consultorio == 107)
                                            <div class="panel-body">
                                                <div class="panel panel-info panel-bordered panel-sm">
                                                    <div class="panel-heading">
                                                        <div class="panel-title text-center">
                                                          <b>ASPECTOS GENERALES</b>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <p><?php 
                                                            $aux_aspectos_generales = $prestacion->aspectos_generales;
                                                            if (!empty($aux_aspectos_generales)) {
                                                              echo str_replace(chr(13), '<br>', $prestacion->aspectos_generales);
                                                            }
                                                          ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @if($prestacion->id_consultorio == 27)
                                                <div class="panel-body">
                                                    <div class="panel panel-info panel-bordered panel-sm">
                                                        <div class="panel-heading">
                                                            <div class="panel-title text-center">
                                                              <b>INFORMACIÓN DE SIGNOS VITALES</b>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="form-group">
                                                              <div class="col-md-2">
                                                                <label>TEMPERATURA (°C) &nbsp;</label>
                                                                <p><?php echo $prestacion->temperatura?></p>
                                                              </div>
                                                              <div class="col-md-2">
                                                                <label>TALLA (cm) &nbsp;</label>
                                                                <p><?php echo $prestacion->talla_cm?></p>
                                                              </div>
                                                              <div class="col-md-2">
                                                                <label>PESO (Kg) &nbsp;</label>
                                                                <p><?php echo $prestacion->peso_kg?></p>
                                                              </div>
                                                              <div class="col-md-2">
                                                                <label>IMC &nbsp;</label>
                                                                <p>
                                                                <?php
                                                                  if($prestacion->talla_cm!=0):
                                                                   echo ($prestacion->peso_kg / (($prestacion->talla_cm * $prestacion->talla_cm)*(0.01*0.01)));
                                                                  endif;
                                                                ?>
                                                                 
                                                               </p>
                                                              </div>
                                                              <div class="col-md-2">
                                                                <label>PESO IDEAL &nbsp;</label>
                                                                <p><?php echo 45+(($prestacion->peso_kg-150)*3/4)?></p>
                                                              </div>
                                                              <div class="col-md-2">
                                                                <label>Sa02 (%) &nbsp;</label>
                                                                <p><?php echo $prestacion->sa02_porcentaje?></p>
                                                              </div>
                                                            </div>
                                                            <div class="form-group">
                                                              <div class="col-md-2">
                                                                <label>TAMAÑO DE CINTURA (cm)</label>
                                                                <p><?php echo $prestacion->tamano_cintura_cm?></p>
                                                              </div>
                                                              <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                                                                <label>FREC. CARDIACA FETAL(/min)</label>
                                                                <p><?php echo $prestacion->frecuencia_cardiaca_fetal?></p>
                                                              </div>
                                                              <div class="col-md-2">
                                                                <label>PRESIÓN ARTERIAL &nbsp;</label>
                                                                <div class="row">
                                                                  <div class="col-xs-5">                          
                                                                    <p><?php echo $prestacion->presion_arterial_x?></p>
                                                                  </div>  
                                                                    <div class="col-xs-2">
                                                                       <label>/</label> 
                                                                    </div>
                                                                  <div class="col-xs-5">
                                                                    <p><?php echo $prestacion->presion_arterial_y?></p>
                                                                  </div>  
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label>FREC.RESPIRATORIA(/min)</label>
                                                                <p><?php echo $prestacion->frecuencia_respiratoria?></p>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label>FREC. CARDIACA (/min) &nbsp;</label>
                                                                <p><?php echo $prestacion->frecuencia_cardiaca?></p>
                                                            </div>
                                                              <!--div class="col-md-2">
                                                                <label>FREC. CARDIACA (/min) &nbsp;</label>
                                                                <p><?php //echo $prestacion->frecuencia_cardiaca?></p>
                                                              </div-->
                                                            <div class="col-md-2">
                                                                <label>Fi02 (%) &nbsp;</label>
                                                                <p><?php echo $prestacion->fi02?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif  
                                        @endif
                                        <div class="panel-body">
                                            <div class="form-group">
                                              <div class="col-md-12">
                                                <label>EXAMEN FISICO</label>
                                                  <p><?php echo $prestacion->examen_fisico?></p>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="panel-body">
                                            <div class="form-group">
                                              <div class="col-md-12">
                                                <label>EXAMEN CLINICO</label>
                                                <p><?php echo $prestacion->examen_clinico?></p>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="panel-body">
                                            <div class="form-group">
                                              <div class="col-md-12">
                                                <label>EXAMENES AUXILIARES</label>
                                                <p><?php echo $prestacion->examen_auxiliar?></p>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="tab-pane" id="tab22default">
                                          <div class="panel-body">
                                            <div class="form-group">
                                              <div class="col-md-12">
                                                <label>TRATAMIENTO AUXILIAR</label>
                                                <p><?php echo $prestacion->tratamiento_auxiliar?></p>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="tab-pane" id="tab23default">
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label>PLAN DE TRABAJO</label>
                                                        <p><?php echo $prestacion->plan_trabajo?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                    
                                    </div>
                                </div>
                            </div>
                            <!--nav tab-->
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
</body>    