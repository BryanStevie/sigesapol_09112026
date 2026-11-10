<fieldset>
  <div class="panel panel-info panel-bordered panel-sm">
    <div class="panel-body">
      <div class="form-group">
        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
          <label>PRESCRIBIR RECETA</label>
        </div>
        <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
          <button type="button" id="addRecetaVale" class="btn btn-lg btn-toggle"
            data-toggle="button" aria-pressed="false" autocomplete="off">
            <div class="handle"></div>
          </button>
        </div>
      </div>
    </div>
  </div>
  <fieldset id="fsRecetaVale" style="display:none">
    <div class="panel panel-info panel-bordered panel-sm">
      <div class="panel-heading">
        <div class="panel-title text-center">
          <b>PRODUCTOS MEDICOS</b>
        </div>
      </div>
      <div class="panel-body">
        <div class="form-group">
          <div class="col-md-2">
            <label>FEC. EXPEDICIÓN (A)</label>
            <input type="text" class="form-control datepicker"
              value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>"
              name="fecha_expedicion" id="fecha_expedicion" tabindex="2"
              data-date-end-date="0d" autocomplete="off" />
          </div>
          <div class="col-md-5">
            <label>FARMACIA</label>
            <select class="form-control" name="id_farmacia_receta" id="id_farmacia_receta"
              style="width:100%" onchange="getProducto()">
              <option value="">-Seleccionar-</option>
              <?php if(Auth::user()->id_establecimiento != 76):?>
                  <?php foreach($farmacias as $row):?>
                  <option value="<?php echo $row->id?>"
                    <?php if($row->id==Auth::user()->id_farmacia) echo "selected='selected'"?>>
                    <?php echo $row->nombre?></option>
                  <?php endforeach;?>
                <?php else:?>
                  <option value="121" <?php if(Auth::user()->id_farmacia==121) echo "selected"; ?>>Farmacia PIP I del Hospital Nacional PNP Luis N. Saenz</option>
                  <option value="3" <?php if(Auth::user()->id_farmacia==3) echo "selected"; ?>>Farmacia Central del Hospital Nacional PNP Luis N. Saenz</option>
                  <option value="10" <?php if(Auth::user()->id_farmacia==10) echo "selected"; ?>>Farmacia Oncologica del Hospital Nacional PNP Luis N. Saenz</option>
                  <option value="21" <?php if(Auth::user()->id_farmacia==21) echo "selected"; ?>>Farmacia Soporte Nutricial del Hospital Nacional PNP Luis N. Saenz</option>
                  <option value="17" <?php if(Auth::user()->id_farmacia==17) echo "selected"; ?>>Farmacia TARGA del Hospital Nacional PNP Luis N. Saenz</option>
                  <option value="109" <?php if(Auth::user()->id_farmacia==109) echo "selected"; ?>>Almacén Rehabilitacion del Hospital Nacional PNP Luis N. Saenz</option>
                    <option value="132" <?php if(Auth::user()->id_farmacia==132) echo "selected"; ?>>Farmacia de Preparados Farmacéutico No Estériles del Hospital Nacional PNP Luis N. Saenz</option>
                <?php endif;?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-2"
            style="padding-top:5px;margin-right:0px;padding-right:0px;font-size:13px">
            <label>PACIENTE CONTINUADOR</label>
          </div>
          <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
            <button type="button" id="addCondicion" class="btn btn-lg btn-toggle"
              data-toggle="button" aria-pressed="false" autocomplete="off">
              <div class="handle"></div>
            </button>
          </div>
          <input type="hidden" name="condicion" id="condicion" value="0">
          <div id="divCondicion" style="display:none">
            <div class="col-md-2"
              style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px;font-size:13px">
              <label>DIAS DE RECETAS</label>
            </div>
            <div class="col-md-1" style="margin-left:0px;padding-left:0px">
              <select class="form-control" name="cantidadReceta" id="cantidadReceta"
                tabindex="8" style="width: 100%">
                <option value="0"></option>
                <option value="1">30</option>
                <option value="2">60</option>
                <option value="3">90</option>
                <option value="4">120</option>
              </select>
            </div>
          </div>
        </div>
          <div class="form-group">
            <div class="col-sm-12">
              <table>
                <tr>
                  <td>
                    <button type="button" id="addProducto" class="btn btn-primary"
                      data-toggle="modal" data-target="#addClassModal">Agregar Producto</button>
                  </td>
                  <td style="padding-left: 10px;">
                    <div id="divCopiarUltimaReceta" style="display:none;">
                      <button type="button"
                        id="btnCopiarUltimaReceta"
                        class="btn btn-info"
                        onclick="getLastRecipeConsultaExterna()"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="Copiar Última Receta">
                        <i class="fa fa-files-o"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <br />
          <div style="display: none">
            <select class="form-control" id="cmbMedicamentoTemp" tabindex="16"
              style="width: 500px">
              <option value="">Seleccionar Producto</option>
            </select>
          </div>
          <input type="hidden" value="0" name="hidden_rowIndex" id="hidden_rowIndex">
          <input type="hidden" value="1" name="receta_prescrita" id="receta_prescrita">
          <div class="form-group">                                    
            <table id="example" class="display" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th>Producto farmacéutico o dispositivo médico</th>
                  <th style="width:10%">Stock Farmacia</th>
                  <th>Tiempo(Dias)</th>
                  <th> Via Adm. </th>
                  <th>Posologia</th>
                  <th> Cant. Pres.</th>
                  <th> DiagnosticoxProducto </th>
                  <th>  </th>
                  <th>  </th>
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
