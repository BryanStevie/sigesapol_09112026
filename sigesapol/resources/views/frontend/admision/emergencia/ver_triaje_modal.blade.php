@section('renderjs')
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
@endsection
@section('content')


          
          <div class="panel-body">
              <!--informacion de paciente-->
              <fieldset>
                <div class="col-md-12">
                  <div class="panel with-nav-tabs panel-default"
                    style="font-size: 11px;padding-bottom: 0px !important; font-size: 14px !important;">
                    <div class="panel-heading">
                      <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab0default" data-toggle="tab" style="font-size:12px"
                            aria-expanded="true"><b><i class="fa fa-user" aria-hidden="true"></i> INFORMACIÓN DE ATENCIÓN</b></a></li>
                      </ul>
                    </div>
                    <div class="panel-body">
                      <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab0default">
                          <fieldset>

                            <div class="row" >
                              <div class="col-md-3">
                                <label style="padding-right: 10px;">TIPO DE ATENCIÓN: </label> TRIAJE - EMERGENCIA 
                              </div>  
                              <div class="col-md-2">
                                <label style="padding-right: 10px;">F. REGISTRO: </label> <?php echo date("d/m/Y", strtotime($data->fecha_registro_emergencia_triaje)); ?>
                              </div>
                              <div class="col-md-2">
                                <label style="padding-right: 10px;">N° DE REGISTRO: </label> {{$data->secuencia }}
                              </div>
                              <div class="col-md-5">
                                <label for="tipo" style="padding-right: 10px;">ESTABLECIMIENTO: </label> <r style="text-transform: uppercase;">{{$nombre_establecimiento}}</r> 
                              </div>
                            </div>

                            <div class="row" >
                              <hr  style="margin: 10px !important;">
                              <div class="col-md-2">
                                <label>TIPO DE DOCUMENTO: </label><br>
                                <p>DNI</p>
                              </div>
                              <div class="col-md-2">
                                <label>N° DE DOCUMENTO: </label><br>
                                <p>{{$data->nro_dni}}</p>
                              </div>
                              <div class="col-md-4">
                                <label>PACIENTE: </label><br>
                                <p>{{$data->apellido_pat_paciente}} {{$data->apellido_mat_paciente}}
                                  {{$data->nombres_paciente}}</p>
                              </div>
                              <div class="col-md-1">
                                <label>SEXO: </label><br>
                                <p>{{$data->sexo}}</p>
                              </div>
                              <div class="col-md-1">
                                <label>F.NACIM: </label><br>
                                <p>{{$data->fecha_nac_paciente}}</p>
                                <input type="hidden" name="fecha_nacimiento_beneficiario" id="fecha_nacimiento_beneficiario"
                                class="form-control" value="{{$data->fecha_nac_paciente}}">
                              </div>
                              <div class="col-md-2">
                                <label>EDAD: </label><br> 
                                <p>{{$data->edadpaciente}}</p>
                              </div> 
                            </div>


                            <div class="row" >
                              <hr  style="margin: 10px !important;"> 
                              <div class="col-md-12">
                                <b style="font-size: 20px;">DATOS DEL TITULAR</b><br><br>
                              </div>
                              <div class="col-md-2">
                                <label>N° DE DOCUMENTO: </label><br>
                                <p>{{$data->nomtipdoctitular}} - {{$data->nrodoctitular}}</p>
                              </div>
                              <div class="col-md-4">
                                <label>PACIENTE: </label><br>
                                <p>{{$data->apepattitular}} {{$data->apemattitular}}
                                  {{$data->nomtitular}}</p>
                              </div>
                              <div class="col-md-2">
                                <label>CIP: </label><br>
                                <p>{{$data->ciptitular}}</p>
                              </div> 
                              <div class="col-md-4">
                                <label>GRADO: </label><br> 
                                <p>{{$data->gradotitular}}</p>
                              </div> 
                            </div>
                            

                            @if($padre_paciente !=null || $madre_paciente !=null)
                              <div class="row" >
                                <hr  style="margin: 10px !important;">
                                @if($padre_paciente !=null)
                                  <div class="col-md-6">
                                    <label style="padding-right: 15px;">PADRE RESPONSABLE:</label> 
                                    {{ $padre_paciente->nro_doc_ident }} - {{ $padre_paciente->paterno }} {{ $padre_paciente->materno }} {{ $padre_paciente->nombres }}<br> 
                                  </div>
                                @endif
                                @if($madre_paciente !=null)
                                  <div class="col-md-6">
                                    <label style="padding-right: 15px;">MADRE RESPONSABLE:</label> 
                                    {{ $madre_paciente->nro_doc_ident }} - {{ $madre_paciente->paterno }} {{ $madre_paciente->materno }} {{ $madre_paciente->nombres }}<br> 
                                  </div>
                                @endif
                              </div>
                             @endif

                          </fieldset>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
              <!--#informacion de paciente-->

              <!--informacion de Signos vitales-->
              <fieldset>
                <div class="col-md-12">
                  <div class="panel with-nav-tabs panel-default"
                    style="font-size: 11px;padding-bottom: 0px !important;  font-size: 14px !important; ">
                    <div class="panel-heading">
                      <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab0default" data-toggle="tab" style="font-size:12px"
                            aria-expanded="true"><b><i class="fa fa-heartbeat" aria-hidden="true"></i> 
                              INFORMACIÓN DE SIGNOS VITALES</b></a></li>
                      </ul>
                    </div>
                    <div class="panel-body">
                      <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab0default">
                          <fieldset>
                            <div class="row">
                              <div class="col-md-2">
                                <label>TEMPERATURA (°C): </label><br>
                                <p>{{$data->temperatura}}</p>
                              </div>
                              <div class="col-md-2">
                                <label>TALLA (cm): </label><br>
                                <p>{{$data->talla_cm}}</p>
                              </div> 
                              <div class="col-md-2">
                                <label>PESO (Kg): </label><br>
                                <p>{{$data->peso_kg}}</p>
                              </div> 
                              <div class="col-md-2">
                                <label>IMC: </label><br>
                                @if($imc!='')
                                  <p>{{$imc}}</p>
                                @else
                                  <p>-</p>
                                @endif
                              </div> 
                              <div class="col-md-2">
                                <label>PESO IDEAL: </label><br>
                                @if($pesoIdeal!='')
                                  <p>{{$pesoIdeal}}</p>
                                @else
                                  <p>-</p>
                                @endif 
                              </div> 
                              <div class="col-md-2">
                                <label>Sa02 (%): </label><br>
                                <p>{{$data->sa02_porcentaje}}</p>
                              </div> 

                              @if ($data->es_gestante == 1) 
                                <div class="col-md-2">
                                  <label>ALTURA UTERINA (cm) </label><br>
                                  <p>{{$data->tamano_cintura_cm}}</p>
                                </div> 
                                <div class="col-md-2">
                                  <label>FREC. CARDIACA FETAL (/min): </label><br>
                                  <p>{{$data->frecuencia_cardiaca_fetal}}</p>
                                </div> 
                              @endif 

                              <div class="col-md-2">
                                <label>PRESIÓN ARTERIAL: </label><br>
                                <p>{{$data->presion_arterial_x}} / {{$data->presion_arterial_y}}</p>
                              </div>
                              <div class="col-md-2">
                                <label>FREC. RESPIRATORIA (/min): </label><br>
                                <p>{{$data->frecuencia_respiratoria}}</p>
                              </div>  
                              <div class="col-md-2">
                                <label>FREC. CARDIACA (/min): </label><br>
                                <p>{{$data->frecuencia_cardiaca}}</p>
                              </div>  
                              <div class="col-md-2">
                                <label>Fi02 (%): </label><br>
                                <p>{{$data->fi02}}</p>
                              </div>  
                            </div>
                          </fieldset>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </fieldset> 
              <!--#informacion de Signos Vitales-->
              <!--informacion de paciente-->
              <fieldset>
                <div class="col-md-12">
                  <div class="panel with-nav-tabs panel-default"
                    style="font-size: 11px;padding-bottom: 0px !important;  font-size: 14px !important;">
                    <div class="panel-heading">
                      <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab0default" data-toggle="tab" style="font-size:12px"
                            aria-expanded="true"><b><i class="fa fa-file-text" aria-hidden="true"></i> INFORMACIÓN DEL INGRESO</b></a></li>
                      </ul>
                    </div>
                    <div class="panel-body">
                      <div class="tab-content">
                        <div class="tab-pane fade active in" id="tab0default">
                          <fieldset>
                            <div class="row"> 
                              <div class="col-md-3">
                                <label style="padding-right: 10px;">FORMA DE INGRESO: </label>  {{$data->descripcion_forma_ingreso}} 
                              </div> 
                              <div class="col-md-3">
                                <label style="padding-right: 10px;">ESTADO DE INGRESO: </label> {{$data->descripcion_estado_ingreso}}
                              </div> 
                              <div class="col-md-3">
                                <label style="padding-right: 10px;">MOTIVO DE INGRESO: </label> {{$data->descripcion_motivo_ingreso}} 
                              </div> 
                            </div>
                            <div class="row"> 
                              <hr  style="margin: 10px !important;">
                              <div class="col-md-3">
                                <label style="padding-right: 10px;">PRIORIDAD: </label> {{$data->prioridad_nombre}}
                              </div> 
                              <div class="col-md-3">
                                <label style="padding-right: 10px;">TÓPICO: </label> {{$data->topico_nombre}} 
                              </div> 
                              <div class="col-md-6">
                                <label style="padding-right: 10px;">MOTIVO DE LA CONSULTA: </label>  <r style="text-transform: uppercase;">{{$data->observacion_triaje}} </r> 
                              </div> 
                            </div>
                          </fieldset>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </fieldset>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
