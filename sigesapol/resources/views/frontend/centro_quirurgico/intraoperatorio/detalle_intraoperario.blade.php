<script>
  //  $(document).ready(function() {
    // var id_intraoperatorio = $(this).data('id_intraoperatorio'); 
      // var flag_firma = $('#flag_firma').val(); 
    // if (flag_firma<=0) {
    //   $("#btnFirmaIntraoperatorio").show();
    //   $("#icon-doc-firmado-intraoperatorio").hide();
    // } else {
    //   $("#btnFinalizarIntraoperatorio").hide();
    //   if (flag_firma===1) {
    //     $("#icon-doc-firmado-intraoperatorio").show();
    //   }else{
    //     $("#icon-doc-firmado-intraoperatorio").hide();
    //   }
    // }
  // });
  /****************FUNCIONES PARA LA FIRMA***************************/
  async function getArguments() {
    arg = document.getElementById("argumentos").value;
    await dispatchEventClient('sendArguments', arg);
  }
  async function ObtieneArgumentosParaFirmaDesdeLaWebIntraoperatorio() {
    var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    $.post("{{route('intraoperatorio_post_arguments')}}", {
    _token: CSRF_TOKEN,
    type: "W",
    documentName: $('#doc_firmar').val()
    }, function(data, status) {
      document.getElementById("argumentos").value = data;
      getArguments();
    });
  }
  async function actualiza_datos_firma_con_token_io() {
    var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    let id_resol = $('#id_intraoperatorio').val();
    console.log(id_resol);
    $.post("{{route('actualiza_datos_firma_con_token_io')}}", {
      _token: CSRF_TOKEN,
      id_resol: id_resol,
    }, function(data, status) {
      location.reload();
    });
  }

  $(document).on('click', '#btnFinalizarIntraoperatorio', function(e) {
    e.preventDefault();
    ActualizaIntraoperatorioSinTokens();
  })
  const ActualizaIntraoperatorioSinTokens = () => {
    var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    let id_resol = $('#id_intraoperatorio').val();
    console.log(id_resol);
    $.post("{{route('actualiza_datos_firma_sin_token_io')}}", {
      _token: CSRF_TOKEN,
      id_resol: id_resol,
    }, function(data, status) {
      location.reload();
    });
  }
  $(document).ready(function() {
    $(document).on('click', '#btnFirmaIntraoperatorio', function(e) {
      e.preventDefault();
      firmarIntraoperatorio();
    })

    const firmarIntraoperatorio = () => {
      const id_intraoperatorio = $('#id_intraoperatorio').val();
      $.ajax({
          url: `/intraoperatorio_firmar/${id_intraoperatorio}`,//bh_firmar_por_turno
          type: 'POST',
          data: $(this).serialize(),
          dataType: 'json'
      })
      .done(function(resultado) {
        console.log(resultado);
        if(resultado){
          if (resultado.sw) {
              var csv = "<?php echo url()?>/tmp/csv/" + resultado.csv + ".csv";
              $("#doc_firmar").val(`${resultado.csv}.7z`);
              initInvoker('W');
          }
        }else{
          Swal.fire({
              title: "Información de Servicio de Firma",
              text: "No se puede procesar la información",
              icon: "info",
              timer: 4000,
              showConfirmButton: true
          });
        }
      })
      .fail(function(xhr, status, error) {
          console.log('Error:', error);
      })
      .always(function() {
          console.log('Esta función siempre se ejecuta, éxito o error');
      });
    }
  });
</script>

<form id="FormularioHojaIntraoperatorio" name="FormularioHojaIntraoperatorio" enctype="multipart/form-data" method="POST">
  <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
  <input type="hidden" id="id_intraoperatorio" value="{{$data['id']}}">
  <input type="hidden" id="id_resol" value="{{$data['id']}}">
  <input type="hidden" id="argumentos"/>
  <input type="hidden" id="doc_firmar" />
  <input type="hidden" name="flag_firma" id="flag_firma" value="{{$data['flag_firma']}}">
  <input type="hidden" name="flag_firma_centro_quirurgico" id="flag_firma_centro_quirurgico" value="1">

  <div class="nav-tabs-custom">
      <div class="tab-content">

          <div class="tab-pane active" style="font-size: 12px; color: #606060">
              {{-- <fieldset>
                  <!-- Cabecera de pacientes -->
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center"><br>
                          <b>INFORMACIÓN DEL PACIENTE</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <table class="table" cellspacing="0" width="100%" style="margin-top:5px;">
                          <tr>
                            <th>DNI</th>
                            <th>NOMBRES</th>
                            <th>PARENTESCO</th>
                            <th>SEXO</th>
                            <th>EDAD</th>
                            <th>ESTADO CIVIL</th>
                            <th>SALA</th>                          
                          </tr>
                          <tr>
                            <td>
                              {{$datos_paciente->dni}}
                            </td>
                            <td>
                              {{$datos_paciente->asegurado_nombre}} {{$datos_paciente->asegurado_paterno}} {{$datos_paciente->asegurado_materno}}
                            </td>
                            <td>
                              {{$datos_paciente->asegurado_tipo_beneficiario}}
                            </td>
                            <td>
                              {{$datos_paciente->sexo}}
                            </td>
                            <td>
                              {{$datos_paciente->asegurado_edad_anios}}
                            </td>
                            <td>
                              {{$datos_paciente->estado_civil}}
                            </td>
                            <td>
                              {{$datos_paciente->sala}}
                            </td>
                          </tr>
                      </table>
                    </div>
                  </div>
                  <div class="col-md-12">
                      <hr>
                  </div>
              </fieldset> 
              <fieldset>
                <div class="form-group">
                  <div class="row">
                      <div class="col-md-6">
                        <div class="table-responsive">
                          <table class="table table-sm">
                              <tbody>
                             
                                <tr>
                                  <td width="20%">Anestesiologos:</td>
                                  <td width="80%"> 
                                    @if(!empty($anestesiologos))
                                      {{$anestesiologos->medicos_nombres}}
                                    @endif
                                    </td>
                                </tr>
                              </tbody>
                          </table>
                        </div>

                        <div class="table-responsive">
                          <table class="table table-sm">
                              <tbody>
                                <tr>
                                  <td width="20%">Cirujanos:</td>
                                  <td width="80%"> 
                                    @if(!empty($cirujanos))
                                    {{$cirujanos->medicos_nombres}}
                                    @endif    
                                  </td>
                                </tr>
                              </tbody>
                          </table>
                        </div>
                        <div class="table-responsive">
                          <table class="table table-sm">
                              <tbody>
                              @if(!empty($instrumentistas) && (is_array($instrumentistas) || is_object($instrumentistas)))
                                @foreach($instrumentistas as $key => $row)
                                <tr>
                                  <td width="20%"> {{$row->descripcion}}:</td>
                                  <td width="80%">  {{$row->nombre}}   {{$row->paterno}}  {{$row->materno}} </td>
                                </tr>
                                @endforeach
                              @endif
                              </tbody>
                          </table>
                        </div>

                        <div class="table-responsive">
                          <table class="table table-sm">
                            <tbody>
                              <tr>
                                <td width="20%">Cajas de anestesia:</td>
                                <td width="80%">{{$data['cajas_instrumental_descripcion']}}</td>
                              </tr>
                            </tbody>
                        </table>
                        </div>
                        <div class="table-responsive">
                          <table class="table table-sm">
                            <tbody>
                              <tr>
                                <td width="20%">Maquina de Anestesia:</td>
                                <td width="80%">{{$data['maquina_anestesia_descripcion']}}</td>
                              </tr>
                            </tbody>
                        </table>
                        </div>

                      </div>
                    <div class="col-md-6">
                      <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                              <tr>
                                <td width="21%">Recepción del paciente:</td>
                                <td width="70%"> 
                                  {{$data['recepcion_paciente_descripcion']}}
                                </td>
                              </tr>
                            </tbody>
                        </table>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                              <tr>
                                <td width="20%">Tipo de Anestesia:</td>
                                <td width="70%"> 
                                  {{$data['recepcion_paciente_descripcion']}}
                                </td>
                              </tr>
                            </tbody>
                        </table>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                              <tr>
                                <td width="5%">Inicio de Anestesia:</td>
                                <td width="5%"> 
                                  {{$data['anestesia_inicio']}}
                                </td>
                                <td width="5%">Termino:</td>
                                <td width="5%"> 
                                  {{$data['anestesia_termino']}}
                                </td>
                              </tr>
                            </tbody>
                        </table>
                      </div>

                      <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                              <tr>
                                <td width="5%">Inicio de cirugia:</td>
                                <td width="5%"> 
                                  {{$data['cirugia_inicio']}}
                                </td>
                                <td width="5%">Termino:</td>
                                <td width="5%"> 
                                  {{$data['cirugia_termino']}}
                                </td>
                              </tr>
                            </tbody>
                        </table>
                      </div>

                      <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                              <tr>
                                <td width="20%">tiempo Real:</td>
                                <td width="70%"> 
                                  {{$data['tipo_real']}}
                                </td>
                              </tr>
                            </tbody>
                        </table>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                              <tr>
                                <td width="60%"> {{$data['tipo_operacion']}}:</td>
                                <td width="40%"></td>
                              </tr>
                            </tbody>
                        </table>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                              <tr>
                                <td width="20%">Operación Realizada:</td>
                                <td width="70%"></td>
                              </tr>
                            </tbody>
                        </table>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                              <tr>
                                <td width="20%">Salida del Paciente:</td>
                                <td width="70%">{{$data['salida_paciente']}}</td>
                              </tr>
                            </tbody>
                        </table>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                              <tr>
                                <td width="20%">Complicaciones:</td>
                                <td width="70%">{{$data['complicaciones']}}</td>
                              </tr>
                            </tbody>
                        </table>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                              <tr>
                                <td width="5%">Oxigeno:</td>
                                <td width="70%">{{$data['oxigeno_descripcion']}}</td>
                              </tr>
                            </tbody>
                        </table>
                      </div>

                    </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="table-responsive">
                        <table class="table table-sm">
                          <tbody>
                            <tr>
                              <td width="5%">Comentarios:</td>
                              <td width="80%">{{$data['comentarios']}}</td>
                            </tr>
                          </tbody>
                      </table>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset> --}}
              {{-- <fieldset>
                <div class="form-group">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="table-responsive">
                              <div class="col-md-12" style="text-align: center;">
                                  <b>DIAGNOSTICOS DE ENFERMERIA</b>
                              </div>
                              <table class="table table-sm">
                                  <tbody id="lista_diagnosticos">
                                    @if(!empty($diagnosticos_enfermeria) && (is_array($diagnosticos_enfermeria) || is_object($diagnosticos_enfermeria)))
                                      @foreach ($diagnosticos_enfermeria as $key => $diagnostico)
                                      <tr>
                                        <td width="95%"> {{ $diagnostico->descripcion }}</td>
                                        <td width="5%"> 
                                          @if($diagnostico->checked)
                                              (✔)
                                          @else
                                              ( )
                                          @endif
                                        </td>
                                      </tr>
                                      @endforeach
                                    @endif
                                  </tbody>
                              </table>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="table-responsive">
                              <div class="col-md-12" style="text-align: center;">
                                  <b>INTERVENCIÓN DE ENFERMERIA (NIC)</b>
                              </div>
                              <table class="table table-sm">
                                <tbody id="lista_diagnosticos">
                                  @if(!empty($intervencion_enfermeria) && (is_array($intervencion_enfermeria) || is_object($intervencion_enfermeria)))
                                    @foreach ($intervencion_enfermeria as $key => $nic)
                                    <tr>
                                      <td width="5%">{{ $nic->descripcion }}</td>
                                      <td width="95%">
                                        @if(!empty($nic->hijos))
                                          @foreach ($nic->hijos as $hijo)
                                          {{ $hijo['descripcion'] }} 
                                            @if($hijo['checked']==true)
                                                (✔)
                                            @else
                                                ( )
                                            @endif
                                          @endforeach
                                        @endif
                                      </td>
                                    </tr>
                                    @endforeach
                                  @endif
                                </tbody>
                            </table>
                          </div>
                      </div>
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <div class="form-group">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="table-responsive">
                              <div class="col-md-12" style="text-align: center;">
                                  <b>BALANCE DE FLUIDOS</b>
                              </div>
                              <table id="balance_fluidos" class="table table-bordered">
                                  <thead>
                                      <tr>
                                          <th style="text-align: center;" width="40%">INGRESO</th>
                                          <th style="text-align: center;" width="5%">EGRESO</th>
                                          <th style="text-align: center;" width="5%">TERAPEUTICA</th>
                                      </tr>
                                  </thead>
                                  <tbody id="lista_balance_fluidos">
                                    @if(!empty($balance_fluidos_enfermeria) && (is_array($balance_fluidos_enfermeria) || is_object($balance_fluidos_enfermeria)))
                                      @foreach ($balance_fluidos_enfermeria as $key => $bf)
                                        <tr>
                                          <td width="35%">{{ $bf->ingreso_fluido }}</td>
                                          <td width="35%">{{ $bf->egreso_fluido }}</td>
                                          <td width="30%">{{ $bf->terapeutica_fluido }}</td>
                                        </tr>
                                      @endforeach
                                    @endif
                                  </tbody>
                              </table>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="table-responsive">
                              <div class="col-md-12" style="text-align: center;">
                                  <b>RECUENTO DE GASAS</b>
                              </div>
                              <table id="recuento_gasas" class="table table-bordered">
                                  <thead>
                                    <tr>
                                          <th style="text-align: center;" width="35%">ENF. I</th>
                                          <th style="text-align: center;" width="35%">ENF. II</th>
                                          <th style="text-align: center;" width="30%">TOTAL</th>
                                      </tr>
                                  </thead>
                                  <tbody id="lista_recuento_gasas">
                                    @if(!empty($recuento_gasas_enfermeria) && (is_array($recuento_gasas_enfermeria) || is_object($recuento_gasas_enfermeria)))
                                      @foreach ($recuento_gasas_enfermeria as $key => $rg)
                                        <tr>
                                          <td width="35%">{{ $rg->enfermeria1 }}</td>
                                          <td width="35%">{{ $rg->enfermeria2 }}</td>
                                          <td width="30%">{{ $rg->total }}</td>
                                        </tr>
                                      @endforeach
                                    @endif
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
                </div>
              </fieldset> --}}
              <div class="row">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                  <fieldset>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6"><span><b>Fecha:</b> </span><span>{{isset($data->fecha_probable) ? $data->fecha_probable : "_____________________" }}</span></div>
                      <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6"><span><b>Tarifa:</b> </span><span>_________</span></div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-4 col-lg-8 col-xl-8"><span><b>Sala:</b> </span><span>{{isset($data->sala_intervencion) ? $data->sala_intervencion : "_____________________" }}</span></div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4"><span><b>Edad:</b> </span><span>{{$datos_paciente->asegurado_edad_anios}}</span></div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Nombre del Paciente:</b> </span><span>{{$datos_paciente->asegurado_nombre}} {{$datos_paciente->asegurado_paterno}} {{$datos_paciente->asegurado_materno}}</span></div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Anestesiologo:</b> </span>
                        <span>
                          @if(!empty($anestesiologos))
                            {{$anestesiologos->medicos_nombres}}
                          @endif
                        </span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Cirujanos:</b> </span>
                        <span>
                          @if(!empty($cirujanos))
                            {{$cirujanos->medicos_nombres}}
                          @endif
                        </span>
                      </div>
                    </div>
                    @if(!empty($instrumentistas) && (is_array($instrumentistas) || is_object($instrumentistas)))
                      @foreach($instrumentistas as $key => $row)
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>{{$row->descripcion}}:</b> </span><span>{{$row->nombre}}   {{$row->paterno}}  {{$row->materno}}</span></div>
                        </div>
                      @endforeach
                    @endif
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Cajas de Instrumental:</b> </span><span>{{$data['cajas_instrumental_descripcion']}}</span></div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <span><b>Disponibilidad de </b></span>
                        <span>Equipos ( @if($data->cajas_instrumental_equipo) ✔ @endif ) </span>
                        <span>Insumos ( @if($data->cajas_instrumental_insumos) ✔ @endif ) </span>
                        <span>Instrumental ( @if($data->cajas_instrumental_instrumental) ✔ @endif )</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <span><b>Enf I y Enf II verifica y controla:</b> </span>
                        <span>Insumos ( @if($data->enf1_enf2_vc_insumos) ✔ @endif ) </span>
                        <span>Instrumental ( @if($data->enf1_enf2_vc_instrumental) ✔ @endif ) </span>
                      </div>
                    </div>
                  </fieldset>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                  <fieldset>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Recepción del paciente:</b> </span><span>{{$data['recepcion_paciente_descripcion']}}</span></div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Tipo de Anestesia:</b> </span><span>{{$data->tipo_anestesia}}</span></div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6"><span><b>Inicio de Anestesia:</b> </span><span> {{$data['anestesia_inicio']}} </span></div>
                      <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6"><span><b>Término:</b> </span><span>{{$data['anestesia_termino']}}</span></div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6"><span><b>Inicio de Cirugía:</b> </span><span>{{$data['cirugia_inicio']}}</span></div>
                      <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6"><span><b>Término:</b> </span><span>{{$data['cirugia_termino']}}</span></div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Tiempo Real:</b> </span><span> {{$data['tipo_real']}} </span></div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Operación Programada:</b> </span><span>{{isset($data->operaciones_programadas) ? $data->operaciones_programadas : "__________________________________________" }}</span></div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Operación de Emergencia:</b> </span><span>{{isset($data->operaciones_emergencia) ? $data->operaciones_emergencia : "__________________________________________" }}</span></div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Operación Realizada:</b> </span><span>{{isset($data->operaciones_realizadas) ? $data->operaciones_realizadas : "__________________________________________" }}</span></div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Salida del Paciente:</b> </span><span>{{$data['salida_paciente']}}</span> </div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Hora llegada URPA:</b> </span><span>{{$data->hora_llegada_urpa}}</span></div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>COMPLICACIONES:</b> </span><span>{{$data['complicaciones']}}</span></div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Maquina de Anestesia:</b> </span><span>{{$data->maquina_anestesia_descripcion}}</span></div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><span><b>Oxigeno:</b> </span><span>{{$data['oxigeno_descripcion']}}</span></div>
                    </div>
                    </div>
                  </fieldset>
                </div>
              </div>

              <div class="row">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                  <p style="text-align: center; font-size: 12px; font-weight: 700">DIAGNOSTICOS DE ENFERMERIA:</p>
                  <table class="table table-sm">
                    <tbody id="lista_diagnosticos">
                      @if(!empty($diagnosticos_enfermeria) && (is_array($diagnosticos_enfermeria) || is_object($diagnosticos_enfermeria)))
                        @foreach ($diagnosticos_enfermeria as $key => $diagnostico)
                        <tr>
                          <td width="95%" style="font-size: 12px; color: #606060"> {{ $diagnostico->descripcion }}</td>
                          <td width="5%" style="font-size: 12px; color: #606060"> 
                            @if($diagnostico->checked)
                                (✔)
                            @else
                                ( )
                            @endif
                          </td>
                        </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                  <p style="text-align: center; font-size: 12px; font-weight: 700">INTERVENCIÓN DE ENFERMERIA (NIC):</p>
                  <table class="table table-sm">
                    <tbody id="lista_diagnosticos">
                      @if(!empty($intervencion_enfermeria) && (is_array($intervencion_enfermeria) || is_object($intervencion_enfermeria)))
                        @foreach ($intervencion_enfermeria as $key => $nic)
                        <tr>
                          <td width="5%"><span style="font-size: 12px; color: #606060">{{ $nic->descripcion }}</span></td>
                          <td width="95%">
                            @if(!empty($nic->hijos))
                              @foreach ($nic->hijos as $hijo)
                                <span style="font-size: 12px; color: #606060">                                
                                  {{ $hijo['descripcion'] }} 
                                  @if($hijo['checked']==true)
                                      (✔)
                                  @else
                                      ( )
                                  @endif
                                </span>
                              @endforeach
                            @endif
                          </td>
                        </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="row">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                  <p style="text-align: center; font-size: 12px; font-weight: 700">BALANCE DE FLUIDOS:</p>
                  <table id="balance_fluidos" class="table table-bordered">
                    <thead>
                      <tr>
                        <th style="text-align: center; font-size: 12px;" width="40%">INGRESO</th>
                        <th style="text-align: center; font-size: 12px;" width="5%">EGRESO</th>
                        <th style="text-align: center; font-size: 12px;" width="5%">TERAPEUTICA</th>
                      </tr>
                    </thead>
                    <tbody id="lista_balance_fluidos">
                      @if(!empty($balance_fluidos_enfermeria) && (is_array($balance_fluidos_enfermeria) || is_object($balance_fluidos_enfermeria)))
                        @foreach ($balance_fluidos_enfermeria as $key => $bf)
                          <tr>
                            <td width="35%" style="font-size: 12px; color: #606060">{{ $bf->ingreso_fluido }}</td>
                            <td width="35%" style="font-size: 12px; color: #606060">{{ $bf->egreso_fluido }}</td>
                            <td width="30%" style="font-size: 12px; color: #606060">{{ $bf->terapeutica_fluido }}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                  <p style="text-align: center; font-size: 12px; font-weight: 700">RECUENTO DE GASAS:</p>
                  <table id="recuento_gasas" class="table table-bordered">
                    <thead>
                      <tr>
                        <th style="text-align: center; font-size: 12px;" width="25%"></th>
                        <th style="text-align: center; font-size: 12px;" width="25%">ENF. I</th>
                        <th style="text-align: center; font-size: 12px;" width="25%">ENF. II</th>
                        <th style="text-align: center; font-size: 12px;" width="25%">TOTAL</th>
                      </tr>
                    </thead>
                    <tbody id="lista_recuento_gasas">
                      @if(!empty($recuento_gasas_enfermeria) && (is_array($recuento_gasas_enfermeria) || is_object($recuento_gasas_enfermeria)))
                        @foreach ($recuento_gasas_enfermeria as $key => $rg)
                          <tr>
                            @if (intval($key) === 0)
                              <td width="15%" style="font-size: 12px; color: #606060">GASA GRANDE</td>
                            @endif
                            @if (intval($key) === 1)
                              <td width="15%" style="font-size: 12px; color: #606060">GASA CHICA</td>
                            @endif
                            <td width="35%" style="font-size: 12px; color: #606060">{{ $rg->enfermeria1 }}</td>
                            <td width="35%" style="font-size: 12px; color: #606060">{{ $rg->enfermeria2 }}</td>
                            <td width="30%" style="font-size: 12px; color: #606060">{{ $rg->total }}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>
          </div>
          </fieldset>
      </div>
  </div>
  <div class="modal-footer">
    <table width="100%">
      <tr>
        <td>
          @if($data['flag_firma']===1)
          <a href="/{{$data['ruta_firma']}}" target="_blank" data-toggle="tooltip" title="PDF Firmado" 
          class="btn btn-xs" 
          style="border: 2px solid #337ab7; padding: 5px 10px; border-radius: 4px; display: inline-block;">PDF FIRMADO
           <i class="fa fa-pencil" aria-hidden="true"></i>
           <i class="fa fa-download" aria-hidden="true"></i>
          </a>
          @elseif($data['flag_firma']===2)
            <a href="/pdf_intraoperatorio/{{$data['id']}}" target="_blank"  data-toggle="tooltip" title="PDF sin Firma"
            class="btn btn-xs" style="border: 2px solid #337ab7; padding: 5px 10px; border-radius: 4px; display: inline-block;"> PDF SIN FIRMA <i class="fa fa-download"></i>
            </a>
          @else
            @if (Auth::user()->status_token==1)
              <a class="btn btn-info" id="btnFinalizarIntraoperatorio">
                <i class="fa-solid fa-file-signature"></i> FINALIZAR
              </a>
            @else 
                <a class="btn btn-info" id="btnFirmaIntraoperatorio">
                  <i class="fa-solid fa-file-signature"></i> FIRMAR
                </a>
            @endif
          @endif
          {{--<div id="icon-doc-firmado-intraoperatorio" class="btn btn-primary" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem">
            <i class="fa fa-check-circle icon-doc-firmado-intraoperatorio" aria-hidden="true"></i><span style="font-size: 1rem; font-weight: 600;">Documento Firmado</span>
          </div>--}}
        </td>
        {{--<td>
          <a href="/pdf_intraoperatorio/{{$data['id']}}" target="_blank" data-toggle="tooltip" title="Descargar PDF Intraoperatorio" class="btn enf_btn-verde-oscuro btn-xs" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
            <!-- Ícono PDF -->
            <i class="fa fa-file-pdf-o"></i>
            <!-- Texto "PDF Intraoperatorio" -->
            <span>PDF Intraoperatorio</span>
            <!-- Ícono de Descarga -->
            <i class="fa fa-download"></i>
          </a>
        </td>--}}
        <td>
          <div><button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button></div>
        </td>
      </tr>
    </table>
  </div>
</form>