<!-- Modal -->
<style>
  #example thead th {
    position: sticky;
    top: 0;
    background-color: white;
    z-index: 1;
  }
</style>
<div class="modal fade" id="modalListarEvoluciones" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false"
  data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header" style="font-size: 20px !important;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <b id="tituloFormularioListarEvoluciones"></b>
      </div>

      <div class="modal-body" style="font-size: 11px !important">
        <form id="FormularioListarEvoluciones" name="FormularioListarEvoluciones" enctype="multipart/form-data"
          method="POST">
          <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" id="id_atencion_evolucion_listado" name="id_atencion_evolucion_listado">
          <input type="hidden" id="id_tipo_atenciones_evolucion_listado" name="id_tipo_atenciones_evolucion_listado">
          <input type="hidden" id="id_nota_enfermeria_evolucion_listado" name="id_nota_enfermeria_evolucion_listado">
          <div class="nav-tabs-custom">
            <div class="tab-content">
              <div class="tab-pane active">
                <fieldset>
                  <!-- Cabecera de pacientes -->
                  <div class="form-group">
                    <div class="form-group">
                      <div class="col-md-6">
                        <label for="tipo">DNI - PACIENTE: </label> <br>
                        <span style="font-size: 14px;"> 
                          <span id="num_documento_evolucion" ></span> -
                          <span id="nom_paciente_evolucion"></span>
                        </span>
                      </div>
                      <div class="col-md-1">
                        <label for="tipo">G.S.: </label> <br>
                        <label style="font-size: 14px;"><span id="tipo_sangre_evolucion" ></span></label>
                      </div>
                      <div class="col-md-1">
                        <label for="tipo">ALERGIA: </label> <br>
                        <a href="javascript:void(0)" aria-hidden="true" data-toggle="tooltip" style="cursor: pointer;"
                          title="Alergia" class="listarAlergias" id="listarAlergias"><label id=""
                            style="font-size: 14px;cursor: pointer;">SI</label></a>
                      </div>
                      <div class="col-md-1">
                        <label for="tipo">EDAD: </label> <br>
                        <label style="font-size: 14px;" id="edad_evolucion"></label>
                      </div>
                      <div class="col-md-1">
                        <label for="tipo">SEXO: </label> <br>
                        <label style="font-size: 14px;" id="sexo_evolucion"></label>
                      </div>
                      <div class="col-md-2">
                        <label for="tipo">HC: </label> <br>
                        <label style="font-size: 14px;" id="hc_evolucion"></label>
                      </div>
                    </div>
                  </div>
                  <!-- Cabecera de pacientes -->
                  
                  <div class="col-md-12">
                    <hr>
                  </div>
                  <div class="form-group">
                    <div class="col-md-2">
                      <label for="tipo">FECHA INICIAL</label>
                      <input type="text" class="form-control datepicker"
                        style="height: 28px !important;font-size: 12px !important"
                        name="fecha_evolucion_bus_inicial_listado" id="fecha_evolucion_bus_inicial_listado"
                        autocomplete="off" value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                    </div>
                    <div class="col-md-2">
                      <label for="tipo">FECHA FINAL</label>
                      <input type="text" class="form-control datepicker"
                        style="height: 28px !important;font-size: 12px !important"
                        name="fecha_evolucion_bus_final_listado" id="fecha_evolucion_bus_final_listado"
                        autocomplete="off" value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                    </div>
                    <div class="col-md-2">
                      <label for="tipo">TURNO</label>
                      <select class="form-control select2" style="width: 100%" id="descripcion_turno_bus_listado"
                        name="descripcion_turno_bus_listado">
                        <option value="">TODOS</option>
                        <option value="M">MAÑANA</option>
                        <option value="T">TARDE</option>
                        <option value="N">NOCHE</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label>PROCESO</label><br>
                      <a href="javascript:void(0)" aria-hidden="true" data-toggle="tooltip" title="Nueva Evolución"
                        class="btn btn-success btn-sm crearEvoluciones" id="crearEvoluciones"> NUEVA EVOLUCIÓN
                      </a>
                    </div>
                  </div>
                </fieldset>
                <fieldset><br>
                  <div class="col-md-12">
                    <div class="table-responsive" style=" height: 350px; ">
                      <table class="table table-bordered dt-responsive" id="example"
                        style="overflow: auto; width:100%;text-align: left;">
                        <thead>
                          <tr>
                            <th style="width: 130px;">FECHA/HORA</th>
                            <th>USUARIO</th>
                            <th>OBSERVACIONES</th>
                            <th style="width: 90px;">FUNCIONES</th>
                          </tr>
                        </thead>
                        <tbody id="listaDatosEvoluciones" class="listaDatosEvoluciones">
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