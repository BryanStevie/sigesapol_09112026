<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading bg-lightgreen">
                <strong>DETALLES DE ATENCIÓN ESTRATÉGICA</strong>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3">
                        <label>Tipo de Estrategia:</label>
                        <p>{{ $registro->tipo_estrategia == 1 ? 'Individual' : 'Masivo' }}</p>
                    </div>
                    <div class="col-md-3">
                        <label>N° Registro:</label>
                        <p>{{ $registro->nro_registro }}</p>
                    </div>
                    <div class="col-md-3">
                        <label>Fecha Atención:</label>
                        <p>{{ $registro->fecha_atencion->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-md-3">
                        <label>Periodo:</label>
                        <p>{{ $registro->periodo }}</p>
                    </div>
                </div>
                
                @if($registro->tipo_estrategia == 1)
                    <!-- Mostrar datos del paciente -->
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Datos del Paciente</h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Documento:</label>
                                    <p>{{ $registro->asegurado->tipoDocumento->descripcion }}: {{ $registro->asegurado->nro_doc_ident }}</p>
                                </div>
                                <div class="col-md-8">
                                    <label>Nombre:</label>
                                    <p>{{ $registro->asegurado->paterno }} {{ $registro->asegurado->materno }} {{ $registro->asegurado->nombre }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Mostrar datos de atención masiva -->
                    <div class="row">
                        <div class="col-md-3">
                            <label>Cant. Personas:</label>
                            <p>{{ $registro->cant_personas }}</p>
                        </div>
                    </div>
                @endif
                
                <!-- Mostrar diagnósticos (solo individual) -->
                @if($registro->tipo_estrategia == 1 && $registro->diagnosticos->count() > 0)
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Diagnósticos</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Diagnóstico</th>
                                        <th>Tipo</th>
                                        <th>Observación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($registro->diagnosticos as $diag)
                                        <tr>
                                            <td>{{ $diag->diagnostico->codigo }} - {{ $diag->diagnostico->nombre }}</td>
                                            <td>{{ $diag->tipo_diagnostico == 'P' ? 'Presuntivo' : 'Definitivo' }}</td>
                                            <td>{{ $diag->observacion }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
                
                <!-- Mostrar estrategias sanitarias -->
                <div class="row">
                    <div class="col-md-12">
                        <h4>Estrategias Sanitarias</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Estrategia</th>
                                    <th>Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registro->actividades as $act)
                                    <tr>
                                        <td>{{ $act->estrategia->codigo }} - {{ $act->estrategia->nombre }}</td>
                                        <td>{{ $act->observacion }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>