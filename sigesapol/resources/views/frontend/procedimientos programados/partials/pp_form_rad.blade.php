<fieldset>
  <div class="panel panel-info panel-bordered panel-sm">
    <div class="panel-body">
      <div class="form-group">
        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
          <label>EXAMEN DE IMAGENOLOGIA</label>
        </div>
        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
          <button type="button" id="addExamenRad" class="btn btn-lg btn-toggle"
            data-toggle="button" aria-pressed="false" autocomplete="off">
            <div class="handle"></div>
          </button>
        </div>
      </div>
    </div>
  </div>
  <fieldset id="fsExamenRad" style="display:none">
    <div class="panel panel-info panel-bordered panel-sm">
      <div class="panel-heading">
        <div class="panel-title text-center">
          <b>EXAMEN IMAGENOLOGIA</b>
        </div>
      </div>
      <div class="panel-body">
        <div class="form-group">
          <div class="col-sm-12">
            <button type="button" id="addExamenRad2" class="btn btn-primary"
              data-toggle="modal" data-target="#addClassModal">Agregar Examen</button>
          </div>
        </div>
        <br />
        <div class="form-group">
          <table class="display" id="tblExamenRad" cellspacing="0" width="100%"
            style="margin-top:5px;">
            <thead>
              <tr>
                <th width="35%">Examen</th>
                <th width="3%">Comparativo</th>
                <th width="3%">Lado</th>
                <th width="20%">Observacion</th>
                <th width="5%">Cant</th>
                <th width="20%">Diagnostico</th>
                <th width="14%">Radiología Portátil</th>
                <th width="5%">Eliminar</th>
              </tr>
            </thead>
            <tfoot>
            </tfoot>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </fieldset>
</fieldset>
