<fieldset id="panelLaboratorioOrden">
  <div class="panel panel-info panel-bordered panel-sm">
    <div class="panel-body">
      <div class="form-group">
        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
          <label>EXAMEN DE LABORATORIO</label>
        </div>
        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
          <button type="button" id="addExamenLab" class="btn btn-lg btn-toggle"
            data-toggle="button" aria-pressed="false" autocomplete="off">
            <div class="handle"></div>
          </button>
        </div>
      </div>
    </div>
  </div>
  <fieldset id="fsExamenLab" style="display:none">
    <div class="panel panel-info panel-bordered panel-sm">
      <div class="panel-heading">
        <div class="panel-title text-center">
          <b>EXAMEN LABORATORIO </b>
        </div>
      </div>
      <div class="panel-body">
        <div class="form-group">
          <div class="col-md-4">
            <button type="button" id="addExamenLab2" class="btn btn-primary" data-toggle="modal" data-target="#addClassModal">Agregar Examen</button>
          </div>
          <div class="col-md-4">
            <?php $display = "none"; ?>
            <div id="section_perfil_preoperatorio" style="display: {{ $display }};">
              <div class="col-md-6">
                <label for="id_perfil_preoperatorio">Perfil Preoperatorio:</label>
              </div>
              <div class="col-md-6">
                <select name="id_perfil_preoperatorio" id="id_perfil_preoperatorio" class="form-control">
                  <option value="0">-Seleccionar-</option>
                  <option value="1">CIRUGÍA MAYOR</option>
                  <option value="2">CIRUGÍA MENOR</option>
                </select>
              </div>
            </div>
          </div>
          @if (intval($establecimiento->id) === 76)
            <div class="col-md-4" style="text-align: right;">
              <button type="button" id="RUALab2" class="btn btn-primary" data-toggle="modal" data-target="#modalDX">RUA LABORATORIO</button>
            </div>
          @endif
        </div>
        <br />
        <div class="form-group">
          <table class="display" id="tblExamenLab" cellspacing="0" width="100%"
            style="margin-top:5px;">
            <thead>
              <tr>
                @if (intval($nivel) == 3)
                  <th width="65%">Exámen</th>
                  <th width="10%">Cantidad</th>
                  <th width="20%">Diagnóstico</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th width="5%">Eliminar</th>
                @elseif (!empty($nivel) && (int)$nivel < 3)
                  <th width="45%">Exámen</th>
                  <th width="20%">Observación</th>
                  <th width="10%">Cantidad</th>
                  <th width="20%">Diagnóstico</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th width="5%">Eliminar</th>
                @endif
              </tr>
            </thead>
            <tfoot>
            </tfoot>
            <tbody>
            </tbody>
          </table>
        </div>
        @if (intval($nivel) === 3)
          <div class="form-group">
            <div class="col-xs-12">
              <label for="">Observación</label>
              <input type="text" class="form-control" name="lab_orden_observacion" maxlength="255">
            </div>
          </div>
        @endif
      </div>
    </div>
  </fieldset>
</fieldset>
