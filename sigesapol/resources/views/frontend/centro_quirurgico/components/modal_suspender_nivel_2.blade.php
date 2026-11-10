<div class="modal fade" id="modalSuspender" tabindex="-1" role="dialog" aria-labelledby="modalSuspenderLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalSuspenderLabel">SUSPENDER INTERVENCIÓN QUIRÚRGICA</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="cpx_suspender_motivo">MOTIVO DE SUSPENSION</label>
          <textarea class="form-control" id="cpx_suspender_motivo" name="cpx_suspender_motivo" rows="4" maxlength="255" style="resize: none; overflow: auto;" aria-describedby="helpBlock2"></textarea>
          <span id="helpBlock2" class="help-block" style="text-align: right; font-size: 12px;"><span id="reason-length">0</span>/255</span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-danger" style="width: 200px" data-dismiss="modal"><i class="fa fa-ban"></i> CANCELAR</button>
        <button type="button" class="btn btn-sm btn-primary" style="width: 200px" id="btnSuspenderInt"><i class="fa fa-floppy-o"></i> GUARDAR</button>
      </div>
    </div>
  </div>
</div>