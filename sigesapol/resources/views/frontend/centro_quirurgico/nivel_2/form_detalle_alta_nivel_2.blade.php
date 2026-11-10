<style>
  textarea {
    resize: none;
  }
  .input-readonly {
    background-color: #ffffff !important;
    cursor: default;
  }
</style>
<script src="{{ asset('centro_quirurgico/nivel2/js/ver_detalle_alta_cq_nivel_2.js') }}"></script>

<div class="modal fade" id="modalCqDetalleAltaNivel2" tabindex="-1" role="dialog" aria-labelledby="modalCqDetalleAltaNivel2Label" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg" role="document" style="width: 80%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCqDetalleAltaNivel2Label" style="text-align: left">DETALLE DEL DESTINO DEL PACIENTE</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="fr_det_alta_cqx_intervencion_id" id="fr_det_alta_cqx_intervencion_id">
        <input type="hidden" name="fr_det_alta_cqx_atencion_id" id="fr_det_alta_cqx_atencion_id">
        <input type="hidden" name="fr_det_alta_hospitalizacion_id" id="fr_det_alta_hospitalizacion_id">
        <input type="hidden" name="fr_det_doc_firmar" id="fr_det_doc_firmar">
        <input type="hidden" name="fr_det_opt_firma" id="fr_det_opt_firma">
        <input type="hidden" name="fr_det_argumentos" id="fr_det_argumentos">
        <input type="hidden" name="fr_det_user_dni" id="fr_det_user_dni" value="{{ Auth::user()->dni }}">
        <div class="row">
          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
            <label for="cq_det_alta_destino">DESTINO</label>
            <input type="text" class="form-control input-sm" name="cq_det_alta_destino" id="cq_det_alta_destino" readonly>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
            <label for="cq_det_alta_condicion">CONDICION DE ALTA</label>
            <input type="text" class="form-control input-sm" name="cq_det_alta_condicion" id="cq_det_alta_condicion" readonly>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
            <label for="cq_det_alta_fecha_egreso">FECHA EGRESO</label>
            <input class="form-control input-sm" type="text" name="cq_det_alta_fecha_egreso" id="cq_det_alta_fecha_egreso" readonly>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
            <label for="cq_det_alta_hora_egreso">HORA EGRESO</label>
            <input type="text" class="form-control input-sm" name="cq_det_alta_hora_egreso" id="cq_det_alta_hora_egreso" readonly>
          </div>
        </div>
        {{-- <div class="row" id="cq_det_section_servicio_upss" style="display: none">
          <form class="form-horizontal">
            <div class="form-group">
              <label for="cq_det_alta_servicio_hospitalizacion" class="col-sm-2 control-label">SERVICIO DE HOSPITALIZACION / UPSS</label>
              <div class="col-sm-9">
                <input class="form-control input-sm input-readonly" type="text" name="cq_det_alta_servicio_hospitalizacion" id="cq_det_alta_servicio_hospitalizacion" readonly>
              </div>
            </div>
          </form>
        </div> --}}
        <div class="row" id="cq_det_section_destino_ubicacion" style="display: none">
          <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 form-group">
            <label for="cq_det_alta_servicio_hospitalizacion">SERVICIO DE HOSPITALIZACION / UPSS</label>
            <input type="text" class="form-control input-sm" name="cq_det_alta_servicio_hospitalizacion" id="cq_det_alta_servicio_hospitalizacion" readonly>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 form-group">
            <label for="cq_det_alta_sala_cargo">Sala a Cargo</label>
            <input type="text" class="form-control input-sm" name="cq_det_alta_sala_cargo" id="cq_det_alta_sala_cargo" readonly>
          </div>
          {{-- <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 form-group">
            <label for="cq_det_alta_sala_destino">Sala Destino</label>
            <input type="text" class="form-control input-sm" name="cq_det_alta_sala_destino" id="cq_det_alta_sala_destino" readonly>
          </div> --}}
          <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 form-group">
            <label for="cq_det_alta_cama">Cama</label>
            <input type="text" class="form-control input-sm" name="cq_det_alta_cama" id="cq_det_alta_cama" readonly>
          </div>
        </div>
        <div class="panel panel-primary" id="cq_det_alta_section_epicrisis">
          <div class="panel-heading">
            <h3 class="panel-title">EPICRISIS</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                <label for="cq_det_alta_resumen_enfermedad_actual">RESUMEN DE LA ENFERMEDAD ACTUAL</label>
                <textarea class="form-control" rows="3" maxlength="540" id="cq_det_alta_resumen_enfermedad_actual" name="cq_det_alta_resumen_enfermedad_actual" readonly></textarea>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                <label for="cq_det_alta_examen_clinico">EXAMEN CLINICO</label>
                <textarea class="form-control" rows="3" maxlength="540" id="cq_det_alta_examen_clinico" name="cq_det_alta_examen_clinico" readonly></textarea>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                <label for="cq_det_alta_examenes_auxiliares">EXAMENES AUXILIARES</label>
                <textarea class="form-control" rows="3" maxlength="540" id="cq_det_alta_examenes_auxiliares" name="cq_det_alta_examenes_auxiliares" readonly></textarea>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                <label for="cq_det_alta_evolucion">EVOLUCION</label>
                <textarea class="form-control" rows="3" maxlength="540" id="cq_det_alta_evolucion" name="cq_det_alta_evolucion" readonly></textarea>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                <label for="cq_det_alta_tratamiento">TRATAMIENTO</label>
                <textarea class="form-control" rows="3" maxlength="540" id="cq_det_alta_tratamiento" name="cq_det_alta_tratamiento" readonly></textarea>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                <label for="cq_det_alta_procedimientos_terap_diag">PROCEDIMIENTOS TERAPEUTICOS Y/O DIAGNOSTICO</label>
                <textarea class="form-control" rows="3" maxlength="540" id="cq_det_alta_procedimientos_terap_diag" name="cq_det_alta_procedimientos_terap_diag" readonly></textarea>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                <label for="cq_det_alta_complicaciones">COMPLICACIONES</label>
                <textarea class="form-control" rows="3" maxlength="540" id="cq_det_alta_complicaciones" name="cq_det_alta_complicaciones" readonly></textarea>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                <label for="cq_det_alta_medidas_generales">MEDIDAS GENERALES AL ALTA</label>
                <textarea class="form-control" rows="3" maxlength="540" id="cq_det_alta_medidas_generales" name="cq_det_alta_medidas_generales" readonly></textarea>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                <label for="cq_det_alta_medidamentos_prescritos">MEDICAMENTOS PRESCRITOS AL ALTA</label>
                <textarea class="form-control" rows="3" maxlength="540" id="cq_det_alta_medidamentos_prescritos" name="cq_det_alta_medidamentos_prescritos" readonly></textarea>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                <label for="cq_det_alta_pronostico">PRONOSTICO</label>
                <textarea class="form-control" rows="3" maxlength="540" id="cq_det_alta_pronostico" name="cq_det_alta_pronostico" readonly></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" style="width: 35%" id="btnFirmarCqxAltaNivel2"><i class="fa fa-floppy-o"></i> FIRMAR</button>
      </div>
    </div>
  </div>
</div>