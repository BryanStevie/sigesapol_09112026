<!-- Modal -->
<div class="modal fade" id="modalListarHojaConduccion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarHojaConduccion"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioListarHojaConduccion" name="FormularioListarHojaConduccion"
                    enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_hospitalizacion_incidencia" name="id_hospitalizacion_incidencia">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <!-- Cabecera de pacientes -->
                                    @include('frontend.hospitalizacion.paciente_cabecera.paciente_cabecera')
                                    <div class="form-group">
                                        <div class="form-group">
                                            <div class="col-md-2">
                                                <label for="tipo">TIPO DE ATENCIÓN:</label> <br>
                                                <label id="" style="font-size: 14px;">HOSPITALIZADO</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="tipo">SERVICIO:</label> <br>
                                                <label id="" style="font-size: 14px;">CARDIOLOGIA</label>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="tipo">ANEXO:</label> <br>
                                                <label id="" style="font-size: 14px;">963258</label>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">CAMA:</label> <br>
                                                <label id="" style="font-size: 14px;">2</label>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="tipo">PISO:</label> <br>
                                                <label id="" style="font-size: 14px;">2</label>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="tipo">CONDICIÓN:</label> <br>
                                                <label id="" style="font-size: 14px;">HIJO NO INCAPACITADO</label>
                                            </div>
                                            
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <hr style="margin-top: 0px;margin-bottom: 15px;">
                                    </div>
                                    
                                </fieldset>
                                <fieldset><br>
                                    <div class="col-md-12">
                                        <div class="table-responsive" style="overflow: auto; height: 200px !important;">
                                            <table id="example" class="table table-striped table-list-search">
                                                <thead>
                                                    <tr>
                                                        <th>FECHA ENVÍO</th>
                                                        <th>USUARIO ENVÍO</th>
                                                        <th>FECHA RECIBE</th>
                                                        <th>USUARIO RECIBE</th>
                                                        
                                                        <th>REGISTRAR</th>
                                                        <th>FIRMAR</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @for ($i = 1; $i <= 2; $i++)
                                                        <tr>
                                                            <td>01/04/2023 - 02:20:42</td>
                                                            <td>USUARIO QUE ENVÍA</td>
                                                            <td>01/04/2023 - 02:25:42</td>
                                                            <td>USUARIO QUE RECIBE</td>
                                                            @if ($i % 2 == 0)
                                                            <td style="text-align: center;">
                                                                <a href="javascript:void(0)" aria-hidden="true" data-toggle="tooltip" title="" class="btn btn-success btn-xs registrarHojaConduccion" data-original-title="Registrar" disabled> <i class="fa fa-pencil-square-o"></i>
                                                                </a>
                                                            </td>
                                                            <td style="text-align: center;">
                                                                <button href="/alta_hospitalizado/3/2347"
                                                                    class="btn btn-danger  btn-xs" disabled>FIRMAR</button>
                                                            </td>
                                                            @else
                                                            <td style="text-align: center;">
                                                                <a href="javascript:void(0)" aria-hidden="true" data-toggle="tooltip" title="" class="btn btn-success btn-xs registrarHojaConduccion" data-original-title="Registrar"> <i class="fa fa-pencil-square-o"></i>
                                                                </a>
                                                            </td>
                                                            <td style="text-align: center;">
                                                                <button href="/alta_hospitalizado/3/2347"
                                                                    class="btn btn-danger  btn-xs" >FIRMAR</button>
                                                            </td>
                                                            @endif
                                                             
                                                        </tr>
                                                    @endfor
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div><br>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
