<!-- Modal -->
<div class="modal fade" id="modalListarResultados" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarResultados"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioListarResultados" name="FormularioResultados" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id" name="id">
                    <div class="nav-tabs-custom">  
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset> 
                                    
                                    <!-- Cabecera de pacientes -->
                                    @include('frontend.hospitalizacion.paciente_cabecera.paciente_cabecera') 
                                    <div class="col-md-12">
                                        <hr>
                                    </div>
                                    
                                    <div class="form-group"> 
                                        <div class="col-md-4">
                                            <label for="tipo">TIPO</label>
                                            <select class="form-control select2 w-full" style="width: 100%">
                                                <option value="TODOS">TODOS</option>
                                                <option value="HOSPITALIZACION">HOSPITALIZACION</option>
                                                <option value="EMERGENCIA">EMERGENCIA</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="tipo">TIPO RESULTADOS</label>
                                            <select class="form-control select2 w-full" style="width: 100%">
                                                <option value="TODOS">TODOS</option>
                                                <option value="RESULTADOS DE INTERCONSULTA">RESULTADOS DE INTERCONSULTA</option>
                                                <option value="RESULTADOS DE LABORATORIO">RESULTADOS DE LABORATORIO</option>
                                                <option value="RESULTADOS DE RX">RESULTADOS DE RX</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="tipo">FILTRO POR</label>
                                            <select class="form-control select2 w-full" style="width: 100%"> 
                                                <option value="FECHA DE EXAMEN">FECHA DE EXAMEN</option>
                                                <option value="FECHA DE RESULTADOS">FECHA DE RESULTADOS</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group"> 
                                        <div class="col-md-4"><br> 
                                            <label for="tipo">ESTADO</label>
                                            <select class="form-control select2 w-full" style="width: 100%">
                                                <option value="TODOS">TODOS</option>
                                                <option value="VISUALIZADO">VISUALIZADO</option>
                                                <option value="POR VISUALIZAR">POR VISUALIZAR</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4"><br> 
                                            <label for="tipo">FECHA INICIAL</label>
                                            <input type="text" class="form-control datepicker" style="height: 28px !important;"
                                                name="fecha_evolucion_inicial" id="fecha_evolucion_inicial" autocomplete="off" 
                                                value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                        </div>
                                        <div class="col-md-4"><br> 
                                            <label for="tipo">FECHA FINAL</label>
                                            <input type="text" class="form-control datepicker" style="height: 28px !important;"
                                                name="fecha_evolucion_final" id="fecha_evolucion_final" autocomplete="off" 
                                                value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                        </div> 
                                    </div> 
                                </fieldset> 
                                <fieldset>
                                    <div class="form-group"><br>
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table id="example" class="table table-striped table-list-search">
                                                    <thead>
                                                        <tr>
                                                            <th>FECHA DE EXAMEN</th>
                                                            <th>FECHA DE RESULTADO</th>
                                                            <th>TIPO DE RESULTADO</th>  
                                                            <th>TIPO</th>
                                                            <th>ESTADO</th>
                                                            <th>FUNCIONES</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @for($i = 1; $i <= 10; $i++) <tr>
                                                            <td>01/04/2023 - 02:20:42</td>
                                                            <td>10/04/2023 - 02:20:42</td> 
                                                            @if ($i%2==0)
                                                                <td>LABORATORIO</td>
                                                                <td>HOSPITALIZACIÓN</td>
                                                                <td><small class="label label-danger" style="font-size: 10x;">POR VISUALIZAR</small></td>
                                                            @else
                                                                <td>INTERCONSULTA</td>
                                                                <td>EMERGENCIA</td>
                                                                <td><small class="label label-success" style="font-size: 10x;">VISUALIZADO</small></td>
                                                            @endif 
                                                            <td>
                                                                <a href="javascript:void(0)" aria-hidden="true"
                                                                    data-toggle="tooltip" title="Visualizar"
                                                                    class="btn btn-success btn-xs verResultado"> <i
                                                                        class="fa fa-eye"></i>
                                                                </a> 
                                                            </td>
                                                            </tr>
                                                            @endfor
                                                    </tbody>
                                                </table>
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
            </div>
        </div>
        </form>
    </div>
</div>