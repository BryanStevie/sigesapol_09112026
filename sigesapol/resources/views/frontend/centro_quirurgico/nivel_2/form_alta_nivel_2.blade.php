<style>
  textarea {
    resize: none;
  }
  .input-readonly {
    background-color: #ffffff !important;
    cursor: default;
  }
</style>
<script src="{{ asset('centro_quirurgico/nivel2/js/registrar_alta_cq_nivel_2.js') }}"></script>
<div class="modal fade" id="modalCqAltaNivel2" tabindex="-1" role="dialog" aria-labelledby="modalCqAltaNivel2Label" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg" role="document" style="width: 80%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCqAltaNivel2Label" style="text-align: left">DESTINO DEL PACIENTE</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="fa_id_intervencion" id="fa_id_intervencion">
        <input type="hidden" name="fa_id_establecimiento" id="fa_id_establecimiento">
        <input type="hidden" name="fa_servicio_cargo_id" id="fa_servicio_cargo_id">
        <div class="row">
          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
            <label for="cq_alta_destino">DESTINO</label>
            <select id="cq_alta_destino" name="cq_alta_destino" class="form-control input-sm">
              <option value="" selected>-- seleccionar --</option>
              <option value="1">DOMICILIO</option>
              <option value="2">HOSPITALIZACIÓN</option>
            </select>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
            <label for="cq_alta_condicion">CONDICION DE ALTA</label>
            <select id="cq_alta_condicion" name="cq_alta_condicion" class="form-control input-sm">
              <option value="" selected>-- seleccionar --</option>
              <option value="8">MEJORADO</option>
              <option value="1">CURADO</option>
              <option value="1">CURADO</option>
            </select>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
            <label for="cq_alta_fecha_egreso">FECHA EGRESO</label>
            <input class="form-control input-sm input-readonly" type="text" name="cq_alta_fecha_egreso" id="cq_alta_fecha_egreso" readonly>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
            <label for="cq_alta_hora_egreso">HORA EGRESO</label>
            <input type="time" class="form-control input-sm" name="cq_alta_hora_egreso" id="cq_alta_hora_egreso" step="60">
          </div>
        </div>
        <div class="row" id="section_servicio_upss" style="display: none">
          <form class="form-horizontal">
            <div class="form-group">
              <label for="cq_alta_servicio_hospitalizacion" class="col-sm-2 control-label">SERVICIO DE HOSPITALIZACION / UPSS</label>
              <div class="col-sm-9">
                <select id="cq_alta_servicio_hospitalizacion" name="cq_alta_servicio_hospitalizacion" class="form-control input-sm">
                  <option value="" selected>-- seleccionar --</option>
                  <option value="1">DOMICILIO</option>
                  <option value="2">HOSPITALIZACIÓN</option>
                </select>
              </div>
            </div>
          </form>
        </div>
        <div class="row" id="section_destino_ubicacion" style="display: none">
          <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 form-group">
            <label for="cq_alta_sala_cargo">Sala a Cargo</label>
            <input type="text" class="form-control input-sm" name="cq_alta_sala_cargo" id="cq_alta_sala_cargo" readonly>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 form-group">
            <label for="cq_alta_sala_destino">Sala Destino</label>
            <select id="cq_alta_sala_destino" name="cq_alta_sala_destino" class="form-control input-sm">
              <option value="" selected>-- seleccionar --</option>
            </select>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 form-group">
            <label for="cq_alta_cama">Cama</label>
            <select id="cq_alta_cama" name="cq_alta_cama" class="form-control input-sm" disabled>
              <option value="" selected>-- seleccionar --</option>
            </select>
          </div>
        </div>
        <div class="panel panel-primary" id="cq_alta_section_epicrisis">
          <div class="panel-heading">
            <h3 class="panel-title">EPICRISIS</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                <label for="cq_alta_resumen_enfermedad_actual">RESUMEN DE LA ENFERMEDAD ACTUAL</label>
                <textarea class="form-control" rows="3" id="cq_alta_resumen_enfermedad_actual" name="cq_alta_resumen_enfermedad_actual" maxlength="250"></textarea>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                <label for="cq_alta_examen_clinico">EXAMEN CLINICO</label>
                <textarea class="form-control" rows="3" id="cq_alta_examen_clinico" name="cq_alta_examen_clinico" maxlength="250"></textarea>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                <label for="cq_alta_examenes_auxiliares">EXAMENES AUXILIARES</label>
                <textarea class="form-control" rows="3" id="cq_alta_examenes_auxiliares" name="cq_alta_examenes_auxiliares" maxlength="250"></textarea>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                <label for="cq_alta_evolucion">EVOLUCION</label>
                <textarea class="form-control" rows="3" id="cq_alta_evolucion" name="cq_alta_evolucion" maxlength="250"></textarea>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                <label for="cq_alta_tratamiento">TRATAMIENTO</label>
                <textarea class="form-control" rows="3" id="cq_alta_tratamiento" name="cq_alta_tratamiento" maxlength="250"></textarea>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                <label for="cq_alta_procedimientos_terap_diag">PROCEDIMIENTOS TERAPEUTICOS Y/O DIAGNOSTICO</label>
                <textarea class="form-control" rows="3" id="cq_alta_procedimientos_terap_diag" name="cq_alta_procedimientos_terap_diag" maxlength="250"></textarea>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                <label for="cq_alta_complicaciones">COMPLICACIONES</label>
                <textarea class="form-control" rows="3" id="cq_alta_complicaciones" name="cq_alta_complicaciones" maxlength="250"></textarea>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                <label for="cq_alta_medidas_generales">MEDIDAS GENERALES AL ALTA</label>
                <textarea class="form-control" rows="3" id="cq_alta_medidas_generales" name="cq_alta_medidas_generales" maxlength="250"></textarea>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                <label for="cq_alta_medidamentos_prescritos">MEDICAMENTOS PRESCRITOS AL ALTA</label>
                <textarea class="form-control" rows="3" id="cq_alta_medidamentos_prescritos" name="cq_alta_medidamentos_prescritos" maxlength="250"></textarea>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                <label for="cq_alta_pronostico">PRONOSTICO</label>
                <textarea class="form-control" rows="3" id="cq_alta_pronostico" name="cq_alta_pronostico" maxlength="250"></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" style="width: 35%" id="cqBtnCancelAlta" data-dismiss="modal"><i class="fa fa-ban"></i> CANCELAR</button>
        <button type="button" class="btn btn-primary" style="width: 35%" id="cqBtnRegisterAlta"><i class="fa fa-floppy-o"></i> GUARDAR</button>
      </div>
    </div>
  </div>
</div>