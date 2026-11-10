<?php 
  $list_check_metodo_anestesia = [
    [ "id" => "cma_abierto", "label" => "ABIERTO" ],
    [ "id" => "cma_semi_abierto", "label" => "SEMI ABIERTO" ],
    [ "id" => "cma_cerrado", "label" => "CERRADO" ],
    [ "id" => "cma_semi_cerrado", "label" => "SEMI CERRADO" ],
    [ "id" => "cma_balanceado", "label" => "BALANCEADO" ],
    [ "id" => "cma_tiva", "label" => "TIVA" ]
  ];

  $list_checks_metodos_two = [
    [ "id" => "cma_raquidea", "label" => "RAQUIDEA" ],
    [ "id" => "cma_epidural", "label" => "EPIDURAL" ],
    [ "id" => "cma_caudal", "label" => "CAUDAL" ],
    [ "id" => "cma_bloqueo", "label" => "BLOQUEO" ]
  ];

  $list_observaciones_induccion_column_one = [
    [ "id" => "oico_1", "label" => "SATISFACTORIO" ],
    [ "id" => "oico_2", "label" => "NO SATISFACTORIO" ]
  ];

  $list_observaciones_induccion_column_two = [
    [ "id" => "oictw_1", "label" => "EXCIT" ],
    [ "id" => "oictw_2", "label" => "NAUSEAS" ],
    [ "id" => "oictw_3", "label" => "VOMITOS" ]
  ];

  $list_observaciones_induccion_column_three = [
    [ "id" => "oictr_1", "label" => "LARINGOESPASMO" ],
    [ "id" => "oictr_2", "label" => "TOS" ],
    [ "id" => "oictr_3", "label" => "CIANOSIS" ]
  ];

  $list_observaciones_induccion_column_four = [
    [ "id" => "oicf_1", "label" => "ASPIRACIÓN" ],
  ];

   $list_options_posiones = [
    [ "id" => "c_posicion_1", "label" => "TRENDELENBURG" ],
    [ "id" => "c_posicion_2", "label" => "TRENDEL INVERTIDO" ],
    [ "id" => "c_posicion_3", "label" => "LITOTOMIA" ]
  ];

  $list_options_posiones_two = [
    [ "id" => "c_posicion_two_1", "label" => "KRASKE" ],
    [ "id" => "c_posicion_two_2", "label" => "LAMINECTOMIA" ],
    [ "id" => "c_posicion_two_3", "label" => "CRANEOOTOMIA" ]
  ];
?>

<div class="panel panel-info panel-bordered panel-sm">
  <div class="panel-heading d-flex justify-content-between align-items-center">
    <h3 class="panel-title size-titulo" style="display: inline;">INFORME DE ANESTESIA</h3>
  </div>
  <div class="panel-body">
    <div class="row">
      <div class="col-12 col-sm-12 col-md-6 col-lg-8 col-xl-8">
        <div class="row">
          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
            <div class="form-group">
              <label for="form_urpa_fecha_ingreso">FECHA DE INGRESO</label>
              <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
            <div class="form-group">
              <label for="form_urpa_fecha_ingreso">HORADE INGRESO A SALA</label>
              <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
            <div class="form-group">
              <label for="form_urpa_fecha_ingreso">EDAD</label>
              <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
            <div class="form-group">
              <label for="form_urpa_fecha_ingreso">PESO</label>
              <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
            <div class="form-group">
              <label for="form_urpa_fecha_ingreso">HB</label>
              <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
            <div class="form-group">
              <label for="form_urpa_fecha_ingreso">HLO</label>
              <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
            <div class="form-group">
              <label for="form_urpa_fecha_ingreso">INR</label>
              <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
            <div class="form-group">
              <label for="form_urpa_fecha_ingreso">GRUPO FACTOR</label>
              <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
            <div class="form-group">
              <label for="form_urpa_fecha_ingreso">NNEUMOLO</label>
              <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
            <div class="form-group">
              <label for="form_urpa_fecha_ingreso">CARDIOLOGICO</label>
              <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
            <div class="form-group">
              <label for="form_urpa_fecha_ingreso">GLUCOSA</label>
              <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
            <div class="form-group">
              <label for="form_urpa_fecha_ingreso">CREATININA</label>
              <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
            <div class="form-group">
              <label for="form_urpa_fecha_ingreso">UREA</label>
              <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
            <div class="form-group">
              <label for="form_urpa_fecha_ingreso">ASA</label>
              <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="form_urpa_fecha_ingreso">COMPLICACIONES PRE OPERATORIO</label>
              <textarea class="form-control" rows="3"></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">PREDITACION:</label>
          <textarea class="form-control" rows="3"></textarea>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="panel panel-info panel-bordered panel-sm">
  <div class="panel-heading d-flex justify-content-between align-items-center">
    <h3 class="panel-title size-titulo" style="display: inline;">INFORMACION ANESTESIA</h3>
    <button tabindex="70" type="button" id="toogleIngresoUrap" class="btn btn-lg btn-toggle-heading" data-toggle="button" aria-pressed="false" autocomplete="off">
      <div class="handle-heading"></div>
    </button>
  </div>
  <div class="panel-body">
    <div class="row">
      <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">INICIO DE CIRUGÍA</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso" readonly>
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4"></div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">TÉRMINO DE CIRUGÍA</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso" readonly>
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">INICIO ANESTESIA</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso" readonly>
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
        <div class="form-group">
          <label for="inputEmail3" class="col-sm-2 control-label">INDUCCION</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso" readonly>
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">TÉRMINO DE ANESTESIA</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso" readonly>
        </div>
      </div>
    </div>
    <hr style="border: 0; height: 1px; background-color: #333333;">
    <div class="row">
      <div class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-4">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">MÉTODO DE ANESTESIA</label>
          <select class="form-control">
            <option>GENERAL</option>
          </select>
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-1 col-xl-2">
        @foreach ($list_check_metodo_anestesia as $check_metodo_anestesia)
        <div class="form-check" style="display: flex; flex-direction: row; justify-content: flex-start;">
          <input type="checkbox" class="form-check-input" id="{{ $check_metodo_anestesia['id'] }}" style="margin-top: -10px; margin-right: 5px;">
          <label class="form-check-label" for="{{ $check_metodo_anestesia['id'] }}" style="font-weight: 400">{{ $check_metodo_anestesia['label'] }}</label>
        </div>
        @endforeach
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-4">
        <div class="form-group">
          <select class="form-control">
            <option>GENERAL</option>
          </select>
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-1 col-xl-2">
        @foreach ($list_checks_metodos_two as $check_metodos_two)
        <div class="form-check" style="display: flex; flex-direction: row; justify-content: flex-start;">
          <input type="checkbox" class="form-check-input" id="{{ $check_metodos_two['id'] }}" style="margin-top: -10px; margin-right: 5px;">
          <label class="form-check-label" for="{{ $check_metodos_two['id'] }}" style="font-weight: 400">{{ $check_metodos_two['label'] }}</label>
        </div>
        @endforeach
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">MÉTODO DE ANESTESIA</label>
          <select class="form-control">
            <option>GENERAL</option>
          </select>
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <button type="button" class="btn btn-success btn-sm btn-block" style="margin-top: 20px"><i class="fa fa-plus-square" aria-hidden="true"></i> AGREGAR OTROS</button>
      </div>
    </div>
    <hr style="border: 0; height: 1px; background-color: #333333;">
    <div class="row">
      <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">RESPIRACION CONTROLADA</label>
          <div class="row" style="margin-left: -30px; margin-top: -5px">
             <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
              <div class="form-group">
                <select class="form-control">
                  <option>GENERAL</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
              <div class="form-group">
                <select class="form-control">
                  <option>GENERAL</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">MÁSCARA</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" style="margin-top: -10px">
        <div class="form-group">
          <button type="button" class="btn btn-success btn-xs" style="margin-left: -2px"><i class="fa fa-plus-square" aria-hidden="true"></i> AGREGAR OTROS</button>
          <div class="row" style="margin-left: -31px">
             <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
              <div class="form-group">
                <select class="form-control">
                  <option>GENERAL</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
              <div class="form-group">
                <select class="form-control">
                  <option>GENERAL</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">AIRE CC</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6"></div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">BLOQ. LARINGEO</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">TUBO NRO</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">TUBO ANILLADO</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">LARINGOS HOJA NRO</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">TÉCNICA DE ANESTESIA</label>
          <select class="form-control">
            <option>GENERAL</option>
          </select>
        </div>
      </div>
    </div>
    <hr style="border: 0; height: 1px; background-color: #333333;">
    <div class="row">
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">POSICION</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <div class="form-check" style="display: flex; flex-direction: row; justify-content: flex-start;">
          <input type="checkbox" class="form-check-input" id="" style="margin-top: -10px; margin-right: 5px;">
          <label class="form-check-label" for="" style="font-weight: 400">SIMPLE</label>
        </div>
        <div class="form-check" style="display: flex; flex-direction: row; justify-content: flex-start;"">
          <input type="checkbox" class="form-check-input" id="" style="margin-top: -10px; margin-right: 5px;">
          <label class="form-check-label" for="" style="font-weight: 400">CONTINUA</label>
        </div>
        <div class="form-check" style="display: flex; flex-direction: row; justify-content: flex-start;">
          <input type="checkbox" class="form-check-input" id="" style="margin-top: -10px; margin-right: 5px;">
          <label class="form-check-label" for="" style="font-weight: 400">VECES</label>
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <div class="form-check" style="display: flex; flex-direction: row; justify-content: flex-start;">
          <input type="checkbox" class="form-check-input" id="" style="margin-top: -10px; margin-right: 5px;">
          <label class="form-check-label" for="" style="font-weight: 400">LIDOCAÍNA</label>
        </div>
        <div class="form-check" style="display: flex; flex-direction: row; justify-content: flex-start;">
          <input type="checkbox" class="form-check-input" id="" style="margin-top: -10px; margin-right: 5px;">
          <label class="form-check-label" for="" style="font-weight: 400">BUPIVACAINA</label>
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">CONCENTRACION</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">VOL</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">mlGr</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">PUNCIÓN</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2"></div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2"></div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">CONCENTRACION</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">VOL</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">mlGr</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">NIVEL</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">AGUJA NRO</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <div class="form-group">
          <button type="button" class="btn btn-success btn-xs" style="margin-left: -2px"><i class="fa fa-plus-square" aria-hidden="true"></i> AGREGAR OTROS</button>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
    </div>
    <div style="border-bottom: 1px solid #333333; margin-top: 20px;">
      <p style="font-weight: 800;">OBSERVACIONES EN INDUCCIÓN</p>
    </div>
    <div class="row">
      <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
        @foreach ($list_observaciones_induccion_column_one as $observaciones_induccion_column_one)
        <div class="radio">
          <label>
            <input type="radio" name="observaciones_induccion_column_one" id="{{ $observaciones_induccion_column_one['id'] }}" value="option1" checked>
            {{ $observaciones_induccion_column_one['label'] }}
          </label>
        </div>
        @endforeach
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
        @foreach ($list_observaciones_induccion_column_two as $observaciones_induccion_column_two)
        <div class="form-check" style="display: flex; flex-direction: row; justify-content: flex-start">
          <input type="checkbox" class="form-check-input" id="{{ $observaciones_induccion_column_two['id'] }}" style="margin-top: -10px; margin-right: 5px;">
          <label class="form-check-label" for="{{ $observaciones_induccion_column_two['id'] }}" style="font-weight: 400">{{ $observaciones_induccion_column_two['label'] }}</label>
        </div>
        @endforeach
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
        @foreach ($list_observaciones_induccion_column_three as $observaciones_induccion_column_three)
        <div class="form-check" style="display: flex; flex-direction: row; justify-content: flex-start">
          <input type="checkbox" class="form-check-input" id="{{ $observaciones_induccion_column_three['id'] }}" style="margin-top: -10px; margin-right: 5px;">
          <label class="form-check-label" for="{{ $observaciones_induccion_column_three['id'] }}" style="font-weight: 400">{{ $observaciones_induccion_column_three['label'] }}</label>
        </div>
        @endforeach
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
        @foreach ($list_observaciones_induccion_column_four as $observaciones_induccion_column_four)
        <div class="form-check" style="display: flex; flex-direction: row; justify-content: flex-start">
          <input type="checkbox" class="form-check-input" id="{{ $observaciones_induccion_column_four['id'] }}" style="margin-top: -10px; margin-right: 5px;">
          <label class="form-check-label" for="{{ $observaciones_induccion_column_four['id'] }}" style="font-weight: 400">{{ $observaciones_induccion_column_four['label'] }}</label>
        </div>
        @endforeach
        <button type="button" class="btn btn-success btn-xs btn-block"><i class="fa fa-plus-square" aria-hidden="true"></i> AGREGAR OTROS</button>
      </div>
    </div>
    <div style="border-bottom: 1px solid #333333; margin-top: 20px;">
      <p style="font-weight: 800;">POSICIÓN DE PACIENTE</p>
    </div>
    <label style="margin: 20px 0 0 10px" >POSICIÓN</label>
    <div class="row">
      <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2">
        <div class="form-group">
          <select class="form-control">
            <option>DECÚBITO DORSAL</option>
            <option>DECÚBITO VENTRAL</option>
            <option>DECÚBITO LATERAL</option>
          </select>
        </div>
        @foreach ($list_options_posiones as $item_options_posiones)
        <div class="form-check" style="display: flex; flex-direction: row; justify-content: flex-start;">
          <input type="checkbox" class="form-check-input" id="{{ $item_options_posiones['id'] }}" style="margin-top: -10px; margin-right: 5px;">
          <label class="form-check-label" for="{{ $item_options_posiones['id'] }}" style="font-weight: 400">{{ $item_options_posiones['label'] }}</label>
        </div>
        @endforeach
      </div>
      <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2">
        <div class="form-group">
          <select class="form-control">
            <option>DECÚBITO DORSAL</option>
            <option>DECÚBITO VENTRAL</option>
            <option>DECÚBITO LATERAL</option>
          </select>
        </div>
        @foreach ($list_options_posiones_two as $item_options_posiones_two)
        <div class="form-check" style="display: flex; flex-direction: row; justify-content: flex-start;">
          <input type="checkbox" class="form-check-input" id="{{ $item_options_posiones_two['id'] }}" style="margin-top: -10px; margin-right: 5px;">
          <label class="form-check-label" for="{{ $item_options_posiones_two['id'] }}" style="font-weight: 400">{{ $item_options_posiones_two['label'] }}</label>
        </div>
        @endforeach
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
        <div class="form-group">
          <select class="form-control">
            <option>DECÚBITO DORSAL</option>
            <option>DECÚBITO VENTRAL</option>
            <option>DECÚBITO LATERAL</option>
          </select>
          <br>
          <select class="form-control">
            <option>DECÚBITO DORSAL</option>
            <option>DECÚBITO VENTRAL</option>
            <option>DECÚBITO LATERAL</option>
          </select>
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">SIDE IZQUIERDO</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">SIDE DERECHO</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">SIMS IZQUIERDO</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">SIMS DERECHO</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
    </div>
  </div>
</div>