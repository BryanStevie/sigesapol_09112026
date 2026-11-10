<?php
  $form_urpa_list_signos_vitales = array("PALIDEZ", "CLANOSIS", "RUBOR", "RASH CUTÁNEO", "DIAFORESIS", "TOS", "BRONCOESPASMO", "EXITACIÓN", "HIPERTERMIA");
  $form_urpa_list_via_aerea = array("LIBRE", "T.ENDOTRAQUEAL", "T.OTRO-FARING", "TRAQUEOSTOMIA");
  $form_urpa_list_apoyo_ventilat = array("VENT.MEC", "MASCARILLA", "VENTURI", "TUBO EN T.");
  $form_urpa_list_cateteres = array("VIA PERIF", "VIA CENTRAL", "LINEA ART.", "CAT.EPIDURI");
  $form_urpa_x = array("S.NASOG", "S.VESICAL", "DRENAJES");

  $form_urpa_score_alderete_parametros = [
    [ "id" => "urpa_parametro_actividad_muscular", "label" => "ACTIV.MUSCULAR" ],
    [ "id" => "urpa_parametro_respiracion", "label" => "RESPIRACIÓN" ],
    [ "id" => "urpa_parametro_circulacion", "label" => "CIRCULACIÓN" ],
    [ "id" => "urpa_parametro_conciencia", "label" => "CONCIENCIA (PA)" ],
    [ "id" => "urpa_parametro_color_set02", "label" => "COLOR / SET 02" ]
  ];

  $form_urpa_score_andersen = array(
      array("id" => "urpa_score_andersen_01", "label" => "SIN DOLOR AL REPOSO Y DOLOR LIGERO AL MOVIMIENTO O TOS"),
      array("id" => "urpa_score_andersen_02", "label" => "DOLOR LIGERO EN REPOSO Y MODERADO CON EL MOV. O TOS"),
      array("id" => "urpa_score_andersen_03", "label" => "DOLOR MOD. EN REPOSO Y SEVERO CON EL MOV. O TOS"),
      array("id" => "urpa_score_andersen_04", "label" => "DOLOR SEVERO EN REPOSO Y EXTREMO CON EL MOV. O TOS"),
      array("id" => "urpa_score_andersen_05", "label" => "DOLOR INTENSO AL REPOSO")
  );

  $form_urpa_score_bromage = [
    [ "id" => "urpa_bromage_completo", "label" => "COMPLETO" ],
    [ "id" => "urpa_bromage_casi_completo", "label" => "CASI COMPLETO" ],
    [ "id" => "urpa_bromage_parcial", "label" => "PARCIAL" ],
    [ "id" => "urpa_bromage_nulo", "label" => "NULO" ]
  ];

  $form_urpa_score_glasgow = [
    [ "id" => "urpa_glasgow_ao", "label" => "AO" ],
    [ "id" => "urpa_glasgow_rv", "label" => "RV" ],
    [ "id" => "urpa_glasgow_rm", "label" => "RM" ],
    [ "id" => "urpa_glasgow_tubo", "label" => "TUBO" ]
  ];
?>
<div class="panel panel-info panel-bordered panel-sm">
  <div class="panel-heading d-flex justify-content-between align-items-center">
    <h3 class="panel-title size-titulo" style="display: inline;">INGRESO URPA</h3>
    <button tabindex="70" type="button" id="toogleIngresoUrap" class="btn btn-lg btn-toggle-heading" data-toggle="button" aria-pressed="false" autocomplete="off">
      <div class="handle-heading"></div>
    </button>
  </div>
  <div class="panel-body">
    <div class="row">
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">FECHA INGRESO:</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso" readonly style="background-color: #ffffff;">
        </div>
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">HORA INGRESO</label>
          <input type="time" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">CAMA Nº.</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <p style="text-align: center; font-size: 12px; font-weight: 800;">SIGNOS SÍNTOMAS</p>
        @foreach ($form_urpa_list_signos_vitales as $item_signo_vitales)
        <hr>
        <div class="form-check" style="display: flex; flex-direction: row; justify-content: space-between; padding-bottom: -10px; margin: -10px 0px -20px 0px;">
          <label class="form-check-label" for="exampleCheck1" style="font-weight: 400">{{ $item_signo_vitales }}</label>
          <input type="checkbox" class="form-check-input" id="exampleCheck1" style="margin-top: -10px">
        </div>
        @endforeach
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <p style="text-align: center; font-size: 12px; font-weight: 800;">VÍA AEREA</p>
        @foreach ($form_urpa_list_via_aerea as $item_via_aerea)
        <hr>
        <div class="form-check" style="display: flex; flex-direction: row; justify-content: space-between; padding-bottom: -10px; margin: -10px 0px -20px 0px;">
          <label class="form-check-label" for="exampleCheck1" style="font-weight: 400">{{ $item_via_aerea }}</label>
          <input type="checkbox" class="form-check-input" id="exampleCheck1" style="margin-top: -10px">
        </div>
        @endforeach
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <p style="text-align: center; font-size: 12px; font-weight: 800;">APOY.VENTILAT</p>
        @foreach ($form_urpa_list_apoyo_ventilat as $item_apoyo_ventilat)
        <hr>
        <div class="form-check" style="display: flex; flex-direction: row; justify-content: space-between; padding-bottom: -10px; margin: -10px 0px -20px 0px;">
          <label class="form-check-label" for="exampleCheck1" style="font-weight: 400">{{ $item_apoyo_ventilat }}</label>
          <input type="checkbox" class="form-check-input" id="exampleCheck1" style="margin-top: -10px">
        </div>
        @endforeach
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <p style="text-align: center; font-size: 12px; font-weight: 800;">CATÉTERES</p>
        @foreach ($form_urpa_list_cateteres as $item_cateteres)
        <hr>
        <div class="form-check" style="display: flex; flex-direction: row; justify-content: space-between; padding-bottom: -10px; margin: -10px 0px -20px 0px;">
          <label class="form-check-label" for="exampleCheck1" style="font-weight: 400">{{ $item_cateteres }}</label>
          <input type="checkbox" class="form-check-input" id="exampleCheck1" style="margin-top: -10px">
        </div>
        @endforeach
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <p style="color: #ffffff;">-</p>
        @foreach ($form_urpa_x as $item_urpa_x)
        <hr>
        <div class="form-check" style="display: flex; flex-direction: row; justify-content: space-between; padding-bottom: -10px; margin: -10px 0px -20px 0px;">
          <label class="form-check-label" for="exampleCheck1" style="font-weight: 400">{{ $item_urpa_x }}</label>
          <input type="checkbox" class="form-check-input" id="exampleCheck1" style="margin-top: -10px">
        </div>
        @endforeach
      </div>
    </div>
    <div class="row">
      <div class="col-0 col-sm-0 col-md-0 col-lg-2 col-xl-2"></div>
      <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
        <hr>
        <p style="text-align: center; font-size: 12px; font-weight: 800;">FUNCIONES VITALES</p>
        <hr>
        <div class="row">
          <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
            <div class="form-group">
              <label for="form_urpa_fecha_ingreso">PA:</label>
              <input type="text" class="form-control" id="form_urpa_fecha_ingreso" readonly style="background-color: #ffffff;">
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
            <div class="form-group">
              <label for="form_urpa_fecha_ingreso">FC:</label>
              <input type="text" class="form-control" id="form_urpa_fecha_ingreso" readonly style="background-color: #ffffff;">
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
            <div class="form-group">
              <label for="form_urpa_fecha_ingreso">FR:</label>
              <input type="text" class="form-control" id="form_urpa_fecha_ingreso" readonly style="background-color: #ffffff;">
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
            <div class="form-group">
              <label for="form_urpa_fecha_ingreso">SAT 02:</label>
              <input type="text" class="form-control" id="form_urpa_fecha_ingreso" readonly style="background-color: #ffffff;">
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
            <div class="form-group">
              <label for="form_urpa_fecha_ingreso">Tª:</label>
              <input type="text" class="form-control" id="form_urpa_fecha_ingreso" readonly style="background-color: #ffffff;">
            </div>
          </div>
        </div>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
        <p style="text-align: center; font-size: 12px; font-weight: 800;">SCORE ALDERETE</p>
        <hr>
        <div class="row">
          <div class="col-md-6">PARAMETROS</div>
          <div class="col-md-2" style="text-align: center">0</div>
          <div class="col-md-2" style="text-align: center">1</div>
          <div class="col-md-2" style="text-align: center">2</div>
        </div>
        @foreach ($form_urpa_score_alderete_parametros as $item_parametro)
        <div class="row">
          <div class="col-md-6"><span>{{$item_parametro['label']}}</span></div>
          <div class="col-md-2" style="text-align: center">
            <input type="checkbox" class="form-check-input" id="{{$item_parametro['id']}}_check_0" style="margin-top: -10px">
          </div>
          <div class="col-md-2" style="text-align: center">
            <input type="checkbox" class="form-check-input" id="{{$item_parametro['id']}}_check_1" style="margin-top: -10px">
          </div>
          <div class="col-md-2" style="text-align: center">
            <input type="checkbox" class="form-check-input" id="{{$item_parametro['id']}}_check_2" style="margin-top: -10px">
          </div>
        </div>
        @endforeach
        <div class="row">
          <div class="col-md-10" style="text-align: right"><span><b>TOTAL</b></span></div>
          <div class="col-md-2" style="text-align: center"><span>1</span></div>
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
        <p style="text-align: center; font-size: 12px; font-weight: 800;">SCORE ANDERSEN - DOLOR CRITERIOS</p>
        <div class="row">
          <div class="col-md-10"></div>
          <div class="col-md-2" style="text-align: center"><span><b>PUNTOS</b></span></div>
        </div>
        @foreach ($form_urpa_score_andersen as $item_score_andersen)
        <div class="row">
          <div class="col-md-10"><span>{{$item_score_andersen['label']}}</span></div>
          <div class="col-md-2" style="text-align: center">
            <input class="form-control input-sm" type="text">
          </div>
        </div>
        @endforeach
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <p style="text-align: center; font-size: 12px; font-weight: 800;">SCORE BROMAGE BLOQUEO MOTOR</p>
        @foreach ($form_urpa_score_bromage as $item_urpa_score_bromage)
        <div class="row">
          <div class="col-md-7"><span>{{$item_urpa_score_bromage['label']}}</span></div>
          <div class="col-md-5" style="text-align: center">
            <input class="form-control input-sm" type="text">
          </div>
        </div>
        @endforeach
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <p style="text-align: center; font-size: 12px; font-weight: 800;">GLASGOW</p>
        @foreach ($form_urpa_score_glasgow as $item_urpa_score_glasgow)
        <div class="row">
          <div class="col-md-7"><span>{{$item_urpa_score_glasgow['label']}}</span></div>
          <div class="col-md-5" style="text-align: center">
            <input class="form-control input-sm" type="text">
          </div>
        </div>
        @endforeach
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">INDICACIONES Y TERAPIA DE DOLOR / OBSERVACIONES</label>
          <textarea class="form-control" rows="5"></textarea>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="panel panel-info panel-bordered panel-sm">
  <div class="panel-heading d-flex justify-content-between align-items-center">
    <h3 class="panel-title size-titulo" style="display: inline;">CONDICIÓN ALTA</h3>
    <button tabindex="70" type="button" id="toogleIngresoUrap" class="btn btn-lg btn-toggle-heading" data-toggle="button" aria-pressed="false" autocomplete="off">
      <div class="handle-heading"></div>
    </button>
  </div>
  <div class="panel-body">
    <div class="row">
      <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
        <p style="text-align: center; font-size: 12px; font-weight: 800;">SCORE ALDERETE</p>
        <hr>
        <div class="row">
          <div class="col-md-6">PARAMETROS</div>
          <div class="col-md-2" style="text-align: center">0</div>
          <div class="col-md-2" style="text-align: center">1</div>
          <div class="col-md-2" style="text-align: center">2</div>
        </div>
        @foreach ($form_urpa_score_alderete_parametros as $item_parametro)
        <div class="row">
          <div class="col-md-6"><span>{{$item_parametro['label']}}</span></div>
          <div class="col-md-2" style="text-align: center">
            <input type="checkbox" class="form-check-input" id="{{$item_parametro['id']}}_check_0" style="margin-top: -10px">
          </div>
          <div class="col-md-2" style="text-align: center">
            <input type="checkbox" class="form-check-input" id="{{$item_parametro['id']}}_check_1" style="margin-top: -10px">
          </div>
          <div class="col-md-2" style="text-align: center">
            <input type="checkbox" class="form-check-input" id="{{$item_parametro['id']}}_check_2" style="margin-top: -10px">
          </div>
        </div>
        @endforeach
        <div class="row">
          <div class="col-md-10" style="text-align: right"><span><b>TOTAL</b></span></div>
          <div class="col-md-2" style="text-align: center"><span>1</span></div>
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
        <p style="text-align: center; font-size: 12px; font-weight: 800;">SCORE ANDERSEN - DOLOR CRITERIOS</p>
        <div class="row">
          <div class="col-md-10"></div>
          <div class="col-md-2" style="text-align: center"><span><b>PUNTOS</b></span></div>
        </div>
        @foreach ($form_urpa_score_andersen as $item_score_andersen)
        <div class="row">
          <div class="col-md-10"><span>{{$item_score_andersen['label']}}</span></div>
          <div class="col-md-2" style="text-align: center">
            <input class="form-control input-sm" type="text">
          </div>
        </div>
        @endforeach
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <p style="text-align: center; font-size: 12px; font-weight: 800;">SCORE BROMAGE BLOQUEO MOTOR</p>
        @foreach ($form_urpa_score_bromage as $item_urpa_score_bromage)
        <div class="row">
          <div class="col-md-7"><span>{{$item_urpa_score_bromage['label']}}</span></div>
          <div class="col-md-5" style="text-align: center">
            <input class="form-control input-sm" type="text">
          </div>
        </div>
        @endforeach
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
        <p style="text-align: center; font-size: 12px; font-weight: 800;">GLASGOW</p>
        @foreach ($form_urpa_score_glasgow as $item_urpa_score_glasgow)
        <div class="row">
          <div class="col-md-7"><span>{{$item_urpa_score_glasgow['label']}}</span></div>
          <div class="col-md-5" style="text-align: center">
            <input class="form-control input-sm" type="text">
          </div>
        </div>
        @endforeach
      </div>
    </div>
    <div class="row">
      <hr>
      <p style="text-align: center; font-size: 12px; font-weight: 800;">FUNCIONES VITALES</p>
      <hr>
      <div class="col-md-12">
        <div class="col-12 col-sm-12 col-md-6 col-lg-1 col-xl-1">
          <div class="form-group">
            <label for="form_urpa_fecha_ingreso">PA:</label>
            <input type="text" class="form-control" id="form_urpa_fecha_ingreso" readonly style="background-color: #ffffff;">
          </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-1 col-xl-1">
          <div class="form-group">
            <label for="form_urpa_fecha_ingreso">FC:</label>
            <input type="text" class="form-control" id="form_urpa_fecha_ingreso" readonly style="background-color: #ffffff;">
          </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
          <div class="form-group">
            <label for="form_urpa_fecha_ingreso">FR:</label>
            <input type="text" class="form-control" id="form_urpa_fecha_ingreso" readonly style="background-color: #ffffff;">
          </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
          <div class="form-group">
            <label for="form_urpa_fecha_ingreso">SAT 02:</label>
            <input type="text" class="form-control" id="form_urpa_fecha_ingreso" readonly style="background-color: #ffffff;">
          </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
          <div class="form-group">
            <label for="form_urpa_fecha_ingreso">Tª:</label>
            <input type="text" class="form-control" id="form_urpa_fecha_ingreso" readonly style="background-color: #ffffff;">
          </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
          <div class="form-group">
            <label for="form_urpa_fecha_ingreso">EXTUBADO:</label>
            <input type="text" class="form-control" id="form_urpa_fecha_ingreso" readonly style="background-color: #ffffff;">
          </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
          <div class="form-group">
            <label for="form_urpa_fecha_ingreso">RETIRO DE CATETER EPIDRURAL:</label>
            <input type="text" class="form-control" id="form_urpa_fecha_ingreso" readonly style="background-color: #ffffff;">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-sm-12 col-md-6 col-lg-8 col-xl-8">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">INDICACIONES DE ALTA URPA / OBSERVACIONES</label>
          <textarea class="form-control" rows="3"></textarea>
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">FECHA EGRESO:</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso" readonly style="background-color: #ffffff;">
        </div>
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">HORA EGRESO</label>
          <input type="time" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">PASA A</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">PACIENTE</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">EDAD</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
      <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
        <div class="form-group">
          <label for="form_urpa_fecha_ingreso">HC</label>
          <input type="text" class="form-control" id="form_urpa_fecha_ingreso">
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
    <button type="button" class="btn btn-sm btn-danger btn-block"><i class="fa fa-ban" aria-hidden="true"></i> CANCELAR</button>
  </div>
  <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
    <button type="button" class="btn btn-sm btn-success btn-block"><i class="fa fa-floppy-o" aria-hidden="true"></i> GUARDAR</button>
  </div>
</div>