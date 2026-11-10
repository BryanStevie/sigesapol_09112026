
    

<!-- Modal -->
<div class="modal fade" id="modalHorarioKardex" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioHorarioKardex"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioHorarioKardex" name="FormularioHorarioKardex" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_hospitalizacion_horario_kardex"
                        name="id_hospitalizacion_horario_kardex">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset> 
                                    <div class="row">
                                       <div class="col-md-6">
                                            <select class="form-control select2" style="width: 100%">
                                                <option value="">TODOS</option>
                                                <option value="">PENDIENTES</option>
                                                <option value="">APLICADOS</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="time" class="form-control"  id="hora_inicial_de_rango" name="hora_inicial_de_rango" style="height: 28px !important;" />
                                        </div>
                                        <div class="col-md-3">
                                            <a href="javascript:void(0)" class="btn btn-success btn-sm btn-block generarHorario_x_Medicamento"
                                                id="generarHorario_x_Medicamento" style="height: 28px !important;">GENERAR</a>
                                        </div>
                                        <hr>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table id="example" class="table table-striped table-list-search">
                                                <thead style="text-align: center;">
                                                    <tr>
                                                        <th style="width: 10%;text-align: center;">HORA</th>
                                                        <th style="width: 5%;text-align: center;">APL</th> 
                                                        <th style="width: 15%;text-align: center;">NO APL</th>
                                                        <th style="width: 5%;text-align: center;color: red;">RAM</th>
                                                        <th>OBSERVACIÓN</th> 
                                                    </tr>
                                                </thead>
                                                <tbody style="text-align: center;">
                                                    @for($i = 1; $i <= 6; $i++) <tr>
                                                        @if ($i%2==0)
                                                        <td>
                                                            <input type="time" name="hora" id="hora"
                                                                class="form-control" disabled
                                                                style="height: 28px !important;" />
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" checked disabled >
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" checked disabled>
                                                        </td> 
                                                        <td>
                                                            <input type="checkbox" checked disabled>
                                                        </td> 
                                                        <td>
                                                            <input type="text" class="form-control" style="height: 28px !important;" disabled>
                                                        </td> 
                                                        @else
                                                        <td>
                                                            <input type="time" name="hora" id="hora"
                                                                class="form-control" style="height: 28px !important;" />
                                                        </td>
                                                        <td>
                                                            <input type="checkbox">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox">
                                                        </td> 
                                                        <td>
                                                            <input type="checkbox">
                                                        </td> 
                                                        <td>
                                                            <input type="text" class="form-control" style="height: 28px !important;">
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
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>