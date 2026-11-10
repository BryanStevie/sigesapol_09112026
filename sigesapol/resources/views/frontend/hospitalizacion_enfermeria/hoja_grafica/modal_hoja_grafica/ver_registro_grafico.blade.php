<!-- Modal -->
<div class="modal fade" id="modalGraficoHojaGrafica" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioGraficoHojaGrafica"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioGraficoHojaGrafica" name="FormularioGraficoHojaGrafica"
                    enctype="multipart/form-data" method="POST">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <!-- Cabecera de pacientes -->
                                    @include('frontend.hospitalizacion.paciente_cabecera.paciente_cabecera')  
                                    <div class="form-group">  
                                        <div class="col-md-8"> 
                                        </div>
                                        <div class="col-md-2">
                                            <label for="tipo">FECHA INICIAL</label>
                                            <input type="text" class="form-control datepicker" style="height: 28px !important;"
                                                name="fecha_registro_ver_registro_grafico_inicial" id="fecha_registro_ver_registro_grafico_inicial" autocomplete="off" 
                                                value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                        </div> 
                                        <div class="col-md-2">
                                            <label for="tipo">FECHA FINAL</label>
                                            <input type="text" class="form-control datepicker" style="height: 28px !important;"
                                                name="fecha_registro_ver_registro_grafico_final" id="fecha_registro_ver_registro_grafico_final" autocomplete="off" 
                                                value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                        </div> 
                                    </div>
                                    <div class="col-md-12">
                                        <hr>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="col-md-12">
                                        <div class="panel with-nav-tabs panel-default"
                                            style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                                            <div class="panel-heading">
                                                <ul class="nav nav-tabs">
                                                    <li class="active"><a href="#tab0defaultGraficaSignosVitales" data-toggle="tab"
                                                            style="font-size:12px" aria-expanded="true"><b><i
                                                                    class="fa fa-file-text-o" aria-hidden="true"></i>
                                                                GRÁFICO DE SIGNOS VITALES  </b></a></li>
                                                    <li><a href="#tab1defaultGraficaSignosVitales" data-toggle="tab" style="font-size:12px"
                                                            aria-expanded="true"><b><i class="fa fa-file-text-o"
                                                                    aria-hidden="true"></i>
                                                                GRÁFICO DE PRESIÓN ARTERIAL  </b></a></li>
                                                </ul>
                                            </div>
                                            <div class="panel-body" style="padding-bottom: 0px !important;">
                                                <div class="tab-content">

                                                    <div class="tab-pane fade active in" id="tab0defaultGraficaSignosVitales"> 
                                                        <fieldset> 
                                                            <div class="row"><br>
                                                                <div class="col-md-12">   
                                                                    <label for="tipo">TIPO DE ESCALA</label>
                                                                    <select class="form-control select2" style="width: 100%">
                                                                        <option value="">GRÁFICA DE RESPIRACION</option>
                                                                        <option value="">GRÁFICA DE TEMPERATURA</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-12">  <br>
                                                                    <div class="box-body">
                                                                        <div class="chart"  style="height:500px !important;width: 100% !important">
                                                                            <canvas id="myChart" ></canvas>
                                                                        </div>
                                                                        <br> 
                                                                    </div> 
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>

                                                    <div class="tab-pane fade" id="tab1defaultGraficaSignosVitales"> 
                                                        <fieldset> 
                                                            <div class="row"><br>
                                                                <div class="col-md-12">  
                                                                    <div class="box-body">
                                                                        <div class="chart"  style="height:500px !important;width: 100% !important">
                                                                            <canvas id="myChartBar" ></canvas>
                                                                        </div>
                                                                        <br> 
                                                                    </div> 
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>