
<div class="panel panel-info panel-bordered panel-sm panel-body">
  <div class="row" style="margin-bottom: 20px">
    <div class="col-md-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
      <label>PROGRAMACIÓN QUIRÚRGICA</label>
    </div>
    <input type="hidden" id="valueToogleProgramacionQuirurgica_pq" name="valueToogleProgramacionQuirurgica_pq" value="0">
  </div>
  <div id="panelProgramacionQuirurgica_pq">
    <div class="panel panel-info panel-bordered">
      <div class="panel-heading" style="text-align: center">
        <p class="panel-title" style="font-size: 14px"><b>REGISTRAR PROGRAMACIÓN QUIRÚRGICA</b></p>
      </div>
      <div class="panel-body" style="font-size: 12px">
        <div class="row form-group">
          <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3" style="margin-top:25px; display:flex; align-items:center; gap:6px;">
            <label for="id_tipo_cirugia" style="font-size:11px; margin:0;">TIPO DE CIRUGÍA</label>
            <select name="id_tipo_cirugia" id="id_tipo_cirugia" class="form-control" style="width:300px;">
              @foreach ($tipo_cirugias as $row)
              <option value="{{ $row->id }}" @if($row->id==1) selected  @endif>{{ $row->descripcion }}</option>
              @endforeach
            </select> 
          </div>
          <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3" style="margin-top:25px; display:flex; align-items:center; gap:6px;">
            <label for="id_prioridad_programacion" style="font-size:11px; margin:0;">TURNOS QUIRURGICOS</label>
            <input type="number" name="id_prioridad_programacion" id="id_prioridad_programacion" class="form-control" style="width:105px;"  value="1">
          </div>
        </div>
        <br>
        <div class="row form-group">
             <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3" style="margin-top: 25px">
            <label for="toogleRequiereInternPreQx_pq" style="font-size: 11px"><b>REQUIERE INTERNAMIENTO PRE QX</b></label>
            <button tabindex="62" type="button" id="toogleRequiereInternPreQx_pq" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
              <div class="handle"></div>
            </button>
            <input type="hidden" id="valueToogleRequiereInternPreQx_pq" name="valueToogleRequiereInternPreQx_pq" value="0">
          </div>
          <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
            <div style="display: none;" id="formControlTiempoInternamiento">
              <label for="id_tiempo_internamiento_preqx"></label>
              <select class="form-control" id="id_tiempo_internamiento_preqx" name="id_tiempo_internamiento_preqx"></select>
            </div>
          </div>
          <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3" style="margin-top: 25px">
            <label for="exampleInputEmail1" style="font-size: 11px"><b>DIAS DE HOSPITALIZADO POST QX</b></label>
            <button tabindex="62" type="button" id="toogleDiasHospitPostQx_pq" class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
              <div class="handle"></div>
            </button>
            <input type="hidden" id="valueToogleDiasHospitPostQx_pq" name="valueToogleDiasHospitPostQx_pq" value="0">
            <input type="hidden" id="id_cqx_cita" name="id_cqx_cita">
          </div>
          <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
            <div style="display: none;" id="formControlTiempoHospitalizacion">
              <label for="id_tiempo_hospitalizacion_postqx" ></label>
              <select class="form-control" id="id_tiempo_hospitalizacion_postqx" name="id_tiempo_hospitalizacion_postqx"></select>
            </div>
          </div>
        </div> 
        <br>
        <div class="row form-group">
          <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
            <label for="id_lugar_operacion">A SER OPERADO EN</label>
            <select class="form-control" id="id_lugar_operacion" name="id_lugar_operacion">
              <option value="1">CLINICA DIA</option>
              <option value="2">HOSPITALIZACIÓN.</option>
            </select>
          </div>
          <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
            <label for="hora_inicio_pq">HORA DE INICIO</label>
            <input type="time" class="form-control" id="hora_inicio_pq" name="hora_inicio_pq" >
          </div>
          <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
            <label for="hora_fin_pq">HORA DE FIN</label>
            <input type="time" class="form-control" id="hora_fin_pq" name="hora_fin_pq">
          </div>
          <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
            <label for="tiempo_curacion_cqx">TIEMPO DE DURACIÓN(Minutos)</label>
            <input type="text" class="form-control" id="tiempo_curacion_cqx" name="tiempo_curacion_cqx" readonly>
          </div>
        </div>
        <div class="panel panel-info panel-bordered">
            <div class="panel-heading" style="text-align: center">
                <p class="panel-title" style="font-size: 14px"><b>CIRUJANOS QUE INTERVIENEN EN LA OPERACIÓN</b></p>
            </div>
            <div class="panel-body" style="font-size: 12px">
            <button type="button" class="btn btn-success btn-sm" id="btnAddRowTableCirujanoIntOper" style="margin-bottom: 10px"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Cirujano que intervienen en la operación</button>
            <table class="table table-striped" id="tableCirujanoIntOper">
                <thead id="tableCirujanoIntOperHeader">
                <tr>
                    <th style="width: 70%">Apellidos y Nombres</th>
                    <th style="width: 25%">Cargo</th>
                    <th style="width: 5%">Eliminar</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
            </div>
        </div>
      </div>
      <div class="panel panel-info panel-bordered">
        <div class="panel-heading" style="text-align: center">
          <p class="panel-title" style="font-size: 14px"><b>PROCEDIMIENTOS DE PROGRAMACIÓN QUIRÚRGICA</b></p>
        </div>
        <div class="panel-body" style="font-size: 12px">
          <button type="button" class="btn btn-success btn-sm" id="btnAddRowTableProcedure_pq" style="margin-bottom: 10px"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Procedimiento</button>
          <table class="table table-striped" id="tableProcedures_pq">
            <thead id="tableProceduresHeader_pq">
              <tr>
                <th style="width: 95%">Código y Descripción</th>
                <th style="width: 5%">Eliminar</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="panel panel-info panel-bordered panel-sm panel-body">
    <div class="panel panel-info panel-bordered panel-sm panel-body">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-1 col-lg-1">
            <p><b>MOPRI</b></p>
            </div>

            <div class="col-xs-6 col-sm-6 col-md-11 col-lg-11">
            <!-- INPUT PARA SUBIR ARCHIVO -->
            <input type="file" id="fileMopri_pq" name="fileMopri_pq[]" style="display:none;" accept="application/pdf,image/*"/>

            <!-- BOTÓN PARA SELECCIONAR ARCHIVO -->
            <button class="btn btn-success btn-sm" id="btnMopriQx_pq" type="button">
                <i class="fa fa-file-pdf-o"></i> Seleccionar Archivo
            </button>
            </div>
        </div>

        <!-- LISTA DONDE SE MUESTRA EL MOPRI EXISTENTE -->
        <div class="row" style="margin-top: 20px; padding: 0px 15px">
            <ul id="fileList_pq" class="list-group" style="margin-top:10px;"></ul>
        </div>
    </div>
    <fieldset class="row">
        <div class="col-md-offset-3 col-md-6" >
        <div class="panel panel-info panel-sm">
        <div class="panel-heading">
        <div class="panel-title text-center"><b>FECHA PROBABLE DE INTERVENCIÓN QUIRÚRGICA</b></div>
        </div>
        <div class="panel-body">
        <div class="form-group">
        <div class="col-md-offset-4 col-md-4">
        <input type="text" id="fecha_probable_pq" name="fecha_probable_pq" class="form-control" readonly style="background-color: #ffffff">
        </div>
        </div>
        </div>
        </div>
        </div>
    </fieldset>
  </div>
</div>
