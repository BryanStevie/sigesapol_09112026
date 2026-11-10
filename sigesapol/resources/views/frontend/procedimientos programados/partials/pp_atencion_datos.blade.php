          <div class="row pp-fila-igual">
            <div class="col-md-6">
              <div class="pp-seccion">
                <div class="pp-seccion-titulo">DATOS DEL PACIENTE</div>
                <div class="pp-seccion-cuerpo">
                  <div class="pp-dato"><span class="pp-etq">TIPO Y Nº DE DOC</span><span class="pp-sep">:</span><span>{{ $solicitud->tipo_doc }} - {{ $solicitud->nro_doc_ident }}</span></div>
                  <div class="pp-dato"><span class="pp-etq">APELLIDOS Y NOMBRES</span><span class="pp-sep">:</span><span>{{ $solicitud->paterno }} {{ $solicitud->materno }} {{ $solicitud->nombre }}</span></div>
                  <div class="pp-dato"><span class="pp-etq">FECHA DE NACIMIENTO</span><span class="pp-sep">:</span><span>{{ $solicitud->fecha_nac }}</span></div>
                  <div class="pp-dato"><span class="pp-etq">EDAD / SEXO</span><span class="pp-sep">:</span><span>{{ $solicitud->edad }} - {{ $solicitud->sexo_txt }}</span></div>
                  <div class="pp-dato"><span class="pp-etq">TIPO DE BENEFICIARIO</span><span class="pp-sep">:</span><span>{{ $solicitud->tipo_beneficiario }}</span></div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="pp-seccion">
                <div class="pp-seccion-titulo">DATOS GENERALES DE LA ATENCIÓN</div>
                <div class="pp-seccion-cuerpo">
                  <div class="pp-dato"><span class="pp-etq">COD. SITEDS SUSALUD</span><span class="pp-sep">:</span><span class="pp-siteds">{{ $solicitud->codigo_siteds ? $solicitud->codigo_siteds : '-' }}</span></div>
                  <div class="pp-dato"><span class="pp-etq">FECHA Y HORA DE ATENCIÓN</span><span class="pp-sep">:</span><span>{{ $solicitud->fecha_atencion_origen }}</span></div>
                  <div class="pp-dato"><span class="pp-etq">TIPO DE ATENCIÓN</span><span class="pp-sep">:</span><span class="pp-texto-rojo">{{ $solicitud->tipo_atencion }}</span></div>
                  <div class="pp-dato"><span class="pp-etq">SERVICIO SOLICITANTE</span><span class="pp-sep">:</span><span>{{ $solicitud->servicio_solicitante }}</span></div>
                  <div class="pp-dato"><span class="pp-etq">PROFESIONAL DE SALUD</span><span class="pp-sep">:</span>
                    <span>DNI - {{ $solicitud->medico_dni }} / {{ $solicitud->medico_origen }}<br>CMP: {{ $solicitud->medico_cmp }} - RNE: {{ $solicitud->medico_rne }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="pp-seccion">
            <div class="pp-seccion-titulo text-center">DATOS DEL RESPONSABLE DEL PROCEDIMIENTO</div>
            <div class="pp-seccion-cuerpo">
              @if($responsable)
              <table class="table table-condensed pp-resp" style="margin-bottom:0">
                <thead>
                  <tr>
                    <th>TIPO Y Nº DE DOC</th>
                    <th>APELLIDOS Y NOMBRES</th>
                    <th>ESPECIALIDAD</th>
                    <th>Nº DE CMP</th>
                    <th>Nº DE RNE</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>DNI - {{ $responsable->dni }}</td>
                    <td>{{ $responsable->nombre_completo }}</td>
                    <td>{{ $responsable->especialidad }}</td>
                    <td>{{ $responsable->cmp }}</td>
                    <td>{{ $responsable->rne }}</td>
                  </tr>
                </tbody>
              </table>
              @elseif(!empty($sin_medico_programado))
              {{-- Cita sin médico programado: responsable vacío, igual que formulario_atencion --}}
              <table class="table table-condensed pp-resp" style="margin-bottom:0">
                <thead>
                  <tr>
                    <th>TIPO Y Nº DE DOC</th>
                    <th>APELLIDOS Y NOMBRES</th>
                    <th>ESPECIALIDAD</th>
                    <th>Nº DE CMP</th>
                    <th>Nº DE RNE</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
              <div class="text-warning" style="margin-top:5px"><i class="fa fa-exclamation-triangle"></i> La cita de este procedimiento no tiene médico programado en el turno. Programe al médico en el consultorio para que figure como responsable.</div>
              @else
              <div class="text-danger">Su usuario no está registrado como profesional de salud en este establecimiento. No podrá registrar la atención.</div>
              @endif
            </div>
          </div>

          @if(!$confirmacion)
          <div id="ppEdicion">
          @include('frontend.procedimientos programados.partials.pp_form_ocultos')
          <div class="pp-seccion">
            <div class="pp-seccion-titulo text-center">PROCEDIMIENTO SOLICITADO</div>
            <div class="pp-seccion-cuerpo">
              <table class="table table-condensed pp-proc" style="margin-bottom:0">
                <thead>
                  <tr>
                    <th width="4%"></th>
                    <th width="9%">Código</th>
                    <th width="45%">Descripción</th>
                    <th width="8%">Cantidad</th>
                    <th width="28%">Diagnóstico</th>
                    <th width="6%" class="text-center">Atención origen</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($procedimientos as $p)
                  <tr>
                    <td class="text-center"><input type="checkbox" class="chk-proc-pp" value="{{ $p->id }}" style="transform: scale(1.4);" checked></td>
                    <td>{{ $p->codigo }}</td>
                    <td>{{ $p->descripcion }}</td>
                    <td>{{ str_pad($p->cantidad ? $p->cantidad : 1, 2, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $p->diagnostico }}</td>
                    <td class="text-center">
                      <a href="javascript:void(0)" class="btn btn-success btn-sm btnVerAtencionOrigen" title="Ver la atención asociada a la orden"><i class="fa fa-eye"></i></a>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="6" class="text-center">No hay procedimientos por atender en esta solicitud.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <div class="form-group">
            <label style="font-size:12px">Observaciones de la realización del procedimiento</label>
            <textarea id="observacion_pp" class="form-control" rows="5" maxlength="2000" placeholder="--- Registro de observaciones de la realización del procedimiento ---"></textarea>
          </div>

          <div class="form-horizontal">
          @if (intval($establecimiento->id) === 76 && intval($solicitud->id_servicio) === 102)
          @include('frontend.procedimientos programados.partials.pp_form_gastro')
          @endif

          @include('frontend.procedimientos programados.partials.pp_form_receta')

          @include('frontend.procedimientos programados.partials.pp_form_lab')
          @include('frontend.procedimientos programados.partials.pp_form_rad')
          </div>
          
          @unless($modal)
          <div class="text-right">
            <button type="button" id="btnCancelarPP" class="btn btn-danger" style="min-width:140px"><b>CANCELAR</b></button>
            @if(($responsable || !empty($sin_medico_programado)) && count($procedimientos) > 0)
            <button type="button" id="btnGuardarPP" class="btn btn-success" style="min-width:140px"><b>GUARDAR</b></button>
            @endif
          </div>
          @endunless
          </div>

          @else
          <div id="ppConfirmacion">
            <div class="pp-seccion">
              <div class="pp-seccion-titulo text-center">PROCEDIMIENTO REALIZADO</div>
              <div class="pp-seccion-cuerpo">
                <table class="table table-condensed pp-proc" style="margin-bottom:0">
                  <thead>
                    <tr>
                      <th width="10%">Código</th>
                      <th width="50%">Descripción</th>
                      <th width="10%">Cantidad</th>
                      <th width="30%">Diagnóstico</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($procedimientos as $p)
                    <tr>
                      <td>{{ $p->codigo }}</td>
                      <td>{{ $p->descripcion }}</td>
                      <td>{{ str_pad($p->cantidad ? $p->cantidad : 1, 2, '0', STR_PAD_LEFT) }}</td>
                      <td>{{ $p->diagnostico }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>

            <div class="form-group">
              <label style="font-size:12px">Observaciones de la realización del procedimiento</label>
              <textarea id="observacion_pp_confirmacion" class="form-control" rows="5" maxlength="2000" readonly style="background:#f9f9f9;">{{ $atencion_registrada->observacion_atencion }}</textarea>
            </div>

            @include('frontend.procedimientos programados.partials.pp_atencion_ordenes_ver')

            @unless($modal)
            <div class="text-right">
              <button type="button" id="btnVolverPP" class="btn btn-danger" style="min-width:140px;{{ $en_atencion ? '' : ' display:none' }}"><b>CANCELAR</b></button>
              @if(!empty($pp_puede_editar_observacion))
              <button type="button" id="btnEditarObsPP" class="btn btn-warning" style="min-width:140px"><b>EDITAR</b></button>
              <button type="button" id="btnGuardarObsPP" class="btn btn-success" style="min-width:140px; display:none"><b>GUARDAR CAMBIOS</b></button>
              @endif
              <button type="button" id="btnGuardarOrdenesPP" class="btn btn-success" style="min-width:140px; display:none"><b>{{ $en_atencion ? 'GUARDAR' : 'GUARDAR Y FIRMAR' }}</b></button>
              @if($en_atencion)
              <button type="button" id="btnFinalizarPP" class="btn btn-success" style="min-width:140px"><b>{{ !empty($pp_requiere_token) ? 'FIRMAR' : 'FINALIZAR' }}</b></button>
              @elseif(!empty($pp_puede_firmar_token))
              <button type="button" id="btnFirmarTokenPP" class="btn btn-info" style="min-width:140px"><b>FIRMAR</b></button>
              @endif
            </div>
            @endunless
          </div>
          @endif
