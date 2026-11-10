<style>
    label, p {
        font-size: 1.2rem;
    }
    
    input[type=checkbox] {
        /* Doble-tamaño Checkboxes */
        -ms-transform: scale(2); /* IE */
        -moz-transform: scale(2); /* FF */
        -webkit-transform: scale(2); /* Safari y Chrome */
        -o-transform: scale(2); /* Opera */
        padding: 10px;
    }

    .size-titulo {
        font-size: 1.2rem;
    }

    .checkboxtexto {
        /* Checkbox texto */
        font-size: 130%;
        display: inline;
    }
    
</style>
<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title size-titulo">
            DATOS GENERALES
        </h3>
    </div>
    <div class="panel-body">
        <div class="col-lg-3">
            <label class="control-label">TIPO DE ATENCIÓN</label>
            @if ($laboratorio->id_evaluacion > 0)
                <p>EMERGENCIA - EVALUACIÓN</p>
            @else
                <p>{{$prestacion->tipo_atencion}}</p>
            @endif
        </div>
        @if ($id_tipo_atencion == 1)
        <div class="col-lg-3">
            <label class="control-label">CONSULTORIO DE PROCENDENCIA</label>
            <p>{{ $prestacion->servicio}}</p>
        </div>
        <div class="col-lg-3">
            <label class="control-label">FECHA DE ORDEN</label>
            <p>{{$prestacion->fecha_atencion}}</p>
        </div>
        <div class="col-lg-3">
            <label class="control-label">PROCEDENCIA</label>
            <p>{{$prestacion->establecimiento}}</p>
        </div>
        @else
        <div class="col-md-3">
            <label>PRIORIDAD</label>
            <p><?php 
            if(isset( $prestacion->prioridades)){
              echo $prestacion->prioridades;
            }

            ?></p>
        </div>
        <div class="col-md-2">
            <label>UBICACIÓN</label>
            <p><?php
             if(isset( $prestacion->ubicacion)){
              echo $prestacion->ubicacion;
            }
            ?></p>
        </div>
        <div class="col-md-2">
            <label>CAMA</label>
            <p><?php echo $prestacion->nmro_cama?></p>
        </div>
        <div class="col-md-2">
            <label>FECHA ATENCIÓN</label>
            <p><?php echo $prestacion->fecha_atencion?></p>
        </div>
        @endif
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title size-titulo">
            ESPECIALISTA
        </h3>
    </div>
    <div class="panel-body">
        
        <div class="col-lg-3">
            <label class="control-label">NRO. DOCUMENTO</label>
            @if($id_tipo_atencion == 2)
              
              @if (isset($tabla_prestacion_evaluacion))
                  {{-- La variable existe --}}
                  <p>{{$tabla_prestacion_evaluacion->medico_dni  }}</p>
              @else
                  {{-- La variable NO existe --}}
                  <p>{{ $medico->dni }}</p>
              @endif
            @else
              <p>{{$prestacion->dni}}</p>
            @endif
           
        </div>
        <div class="col-lg-5">
            <label class="control-label">NOMBRES Y APELLIDOS</label>
            <label class="control-label">NRO. DOCUMENTO</label>
            @if($id_tipo_atencion == 2)


              @if (isset($tabla_prestacion_evaluacion))
                  {{-- La variable existe --}}
                  <p>{{$tabla_prestacion_evaluacion->medico_nombre}}, {{$tabla_prestacion_evaluacion->medico_paterno}} {{$tabla_prestacion_evaluacion->medico_materno}}</p>
              @else
                  {{-- La variable NO existe --}}
                  <p>{{ $medico->nombre }}, {{ $medico->paterno }} {{ $medico->materno }}</p>
              @endif
              
            @else
              <p>{{$prestacion->medico_nombre}}, {{$prestacion->medico_paterno}} {{$prestacion->medico_materno}}</p>
            @endif
            
        </div>
        <div class="col-lg-4">
            <label class="control-label">PROFESIÓN</label>
          @if($id_tipo_atencion == 2)
           
            @if (isset($tabla_prestacion_evaluacion))
                {{-- La variable existe --}}
                <p>{{$tabla_prestacion_evaluacion->medico_profecion}}</p>
            @else
                {{-- La variable NO existe --}}
                <p>{{ $medico->nombre_profecion }}</p>
            @endif
          @else
            <p>{{$prestacion->profesion}}</p>
          @endif
            
        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title size-titulo">
            PACIENTE
        </h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-3">
                <label class="control-label">NRO. DOCUMENTO</label>
                <p>{{$prestacion->nro_doc_ident}}</p>
            </div>
            <div class="col-lg-3">
                <label class="control-label">NOMBRES Y APELLIDOS</label>
                <p>{{$prestacion->asegurado_nombre}}, {{$prestacion->asegurado_paterno}} {{$prestacion->asegurado_materno}}</p>
            </div>
            <div class="col-lg-2">
                <label class="control-label">TIPO DE BENEFICIARIO</label>
                <p>{{$prestacion->parentesco}}</p>
            </div>
            <div class="col-lg-2">
                <label class="control-label">NRO. HC</label>
                <p>{{$prestacion->nro_historia}}</p>
            </div>
            <div class="col-lg-1">
                <label class="control-label">SEXO</label>
                <p>{{$prestacion->sexo}}</p>
            </div>
            <div class="col-lg-1">
                <label class="control-label">EDAD</label>
                <p>{{$edad_paciente}} AÑOS</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <label class="control-label">REFERIDO</label>
                @if ($id_tipo_atencion == 1)
                    <p>{{$prestacion->referido}}</p>
                @else
                    <p>
                      <?php 
                        if(isset($prestacion->unidad_policial)){
                          echo $prestacion->unidad_policial == null ? "NO" : "SI";
                        }
                        ?>
                    </p>
                @endif
            </div>
            @if ($id_tipo_atencion == 1)
                @if ($prestacion->referido == "SI")    
                    <div class="col-lg-5">
                        <label class="control-label">ESTABLECIMIENTO DE LA REFERENCIA</label>
                        <p>{{$prestacion->establecimiento_referencia}}</p>
                    </div>
                    <div class="col-lg-4">
                        <label class="control-label">DOCUMENTO DE LA REFERENCIA</label>
                        <p>{{$prestacion->numero_doc}}</p>
                    </div>
                @endif
            @else
            <?php if (isset($prestacion->unidad_policial) && $prestacion->unidad_policial != null): ?>
                  <div class="col-lg-5">
                      <label class="control-label">ESTABLECIMIENTO DE LA REFERENCIA</label>
                      <p><?php echo htmlspecialchars($prestacion->esta_eva); ?></p>
                  </div>
                  <div class="col-lg-4">
                      <label class="control-label">DOCUMENTO DE LA REFERENCIA</label>
                      <p><?php echo htmlspecialchars($prestacion->numero_oficio); ?></p>
                  </div>
              <?php endif; ?>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <label class="control-label col-lg-4">Cantidad de Diagnósticos:</label>
        <p class="col-lg-4">
          <?php
            if(isset($diagnostico)){
              echo count($diagnostico);
            }
            ?>
        </p>
    </div>
    @if (auth()->user()->tipo_registro == 1)
        @if ($id_tipo_atencion == 1)
            @if ( $prestacion->ruta_prestacion_atencion_medica !== null && !empty($prestacion->ruta_prestacion_atencion_medica))
                <div class="col-lg-6" style="display: flex; align-items: center">
                    <label class="control-label col-lg-3">Ver la Atención Médica:</label>
                    <a href="/{{ $prestacion->flag_atencion_medica }}/{{ $prestacion->ruta_prestacion_atencion_medica }}" class="btn btn-success"
                        target="_blank"><i class="fa fa-file-pdf-o"></i>
                    </a>
                </div>
            @else
                <div class="col-lg-6" style="display: flex; align-items: center">
                    <label class="control-label col-lg-3">Ver la Atención Médica:</label>
                    <button class="btn btn-success" disabled=""><i class="fa fa-file-pdf-o"></i></button>
                </div>
            @endif   
        @endif
        @if ($id_tipo_atencion == 2)
            @if ($laboratorio->id_evaluacion > 0)
                @if ( $tabla_prestacion_evaluacion->ruta_evaluacion !== null && !empty($tabla_prestacion_evaluacion->ruta_evaluacion))
                    <div class="col-lg-6" style="display: flex; align-items: center">
                        <label class="control-label col-lg-3">Ver la Atención Médica:</label>
                        <a href="/{{ $tabla_prestacion_evaluacion->flag }}/{{ $tabla_prestacion_evaluacion->ruta_evaluacion }}" class="btn btn-success"
                            target="_blank"><i class="fa fa-file-pdf-o"></i>
                        </a>
                    </div>
                @else
                    <div class="col-lg-6" style="display: flex; align-items: center">
                        <label class="control-label col-lg-3">Ver la Atención Médica:</label>
                        <button class="btn btn-success" disabled=""><i class="fa fa-file-pdf-o"></i></button>
                    </div>
                @endif
            @else
                @if ( $tabla_prestacion_emergencia->ruta_prestacion !== null && !empty($tabla_prestacion_emergencia->ruta_prestacion))
                    <div class="col-lg-6" style="display: flex; align-items: center">
                        <label class="control-label col-lg-3">Ver la Atención Médica:</label>
                        <a href="/{{ $tabla_prestacion_emergencia->flag }}/{{ $tabla_prestacion_emergencia->ruta_prestacion }}" class="btn btn-success"
                            target="_blank"><i class="fa fa-file-pdf-o"></i>
                        </a>
                    </div>
                @else
                    <div class="col-lg-6" style="display: flex; align-items: center">
                        <label class="control-label col-lg-3">Ver la Atención Médica:</label>
                        <button class="btn btn-success" disabled=""><i class="fa fa-file-pdf-o"></i></button>
                    </div>
                @endif
            @endif     
        @endif
    @endif
</div>
<div class="row">
    <div class="col-lg-6">
        <label class="control-label col-lg-4">Cantidad de Análisis Clínico:</label>
        <p class="col-lg-4">{{$total_examenes_prestacion_procedimientos}}</p>
    </div>
</div>

<div class="row">
    @if ($laboratorio->fecha_firma)
        <a href="/{{$laboratorio->flag}}/{{$laboratorio->ruta_laboratorio}}" class="btn btn-danger"
            target="_blank">
            PDF Orden de Examenes<i class="fa fa-file-pdf-o"></i>
        </a>
    @else
        <button class="btn btn-danger" disabled="">PDF Orden de Examenes<i class="fa fa-file-pdf-o"></i></button>
    @endif
    @if ($id_tipo_atencion == 1)    
        @if ($prestacion->id_cita_lab)
            <a href="/reporte_cita_nueva_lab/{{$laboratorio->flag}}/{{$laboratorio->ruta_laboratorio}}" class="btn btn-danger"
                target="_blank">
                PDF Próxima Cita del Servicio<i class="fa fa-file-pdf-o"></i>
            </a>
        @else
            <button class="btn btn-danger" disabled="">PDF Próxima Cita del Servicio<i class="fa fa-file-pdf-o"></i></button>
        @endif
    @endif
    @if ($id_tipo_atencion == 1)
        <!-- Crear varible validadora  -->
        @if ($laboratorio_prestacion[0]->fecha_toma_muestra && $prestacion->id_cita_lab)
            <a href="/reporte_checklist_laboratorio/{{$laboratorio->id}}" class="btn btn-danger"
                target="_blank">
                PDF Checklist de Exámenes<i class="fa fa-file-pdf-o"></i>
            </a>
        @else
            <button class="btn btn-danger" disabled="">PDF Checklist de Exámenes<i class="fa fa-file-pdf-o"></i></button>
        @endif
    @endif
    @if ($id_tipo_atencion == 2)
        <!-- Crear varible validadora  -->
        @if ($laboratorio_prestacion[0]->fecha_toma_muestra)
            <a href="/reporte_checklist_laboratorio/{{$laboratorio->id}}" class="btn btn-danger"
                target="_blank">
                PDF Checklist de Exámenes<i class="fa fa-file-pdf-o"></i>
            </a>
        @else
            <button class="btn btn-danger" disabled="">PDF Checklist de Exámenes<i class="fa fa-file-pdf-o"></i></button>
        @endif
    @endif
    @if ($id_tipo_atencion == 3)
        <!-- Crear varible validadora  -->
        @if ($laboratorio_prestacion[0]->fecha_toma_muestra)
            <a href="/reporte_checklist_laboratorio/{{$laboratorio->id}}" class="btn btn-danger"
                target="_blank">
                PDF Checklist de Exámenes<i class="fa fa-file-pdf-o"></i>
            </a>
        @else
            <button class="btn btn-danger" disabled="">PDF Checklist de Exámenes<i class="fa fa-file-pdf-o"></i></button>
        @endif
    @endif
    @if ($id_tipo_atencion == 1)
        @if ($laboratorio_prestacion[0]->fecha_toma_muestra && $prestacion->id_cita_lab)
            <a href="/print_examen_laboratorio_lns/{{$laboratorio_prestacion[0]->id}}/{{$prestacion->nro_doc_ident}}/{{$laboratorio_prestacion[0]->fecha_toma_muestra}}/{{$laboratorio_prestacion[0]->cod_orden_lab}}" class="btn btn-danger"
                target="_blank">
                PDF Resultado de Laboratorio<i class="fa fa-file-pdf-o"></i>
            </a>
        @else
            <button class="btn btn-danger" disabled="">PDF Resultado de Laboratorio<i class="fa fa-file-pdf-o"></i></button>
        @endif
    @endif
    @if ($id_tipo_atencion == 2)
        @if ($laboratorio_prestacion[0]->fecha_toma_muestra)
            <a href="/print_examen_laboratorio_lns/{{$laboratorio_prestacion[0]->id}}/{{$prestacion->nro_doc_ident}}/{{$laboratorio_prestacion[0]->fecha_toma_muestra}}/{{$laboratorio_prestacion[0]->cod_orden_lab}}" class="btn btn-danger"
                target="_blank">
                PDF Resultado de Laboratorio<i class="fa fa-file-pdf-o"></i>
            </a>
        @else
            <button class="btn btn-danger" disabled="">PDF Resultado de Laboratorio<i class="fa fa-file-pdf-o"></i></button>
        @endif
    @endif
    @if ($id_tipo_atencion == 3)
        @if ($laboratorio_prestacion[0]->fecha_toma_muestra)
            <a href="/print_examen_laboratorio_lns/{{$laboratorio_prestacion[0]->id}}/{{$prestacion->nro_doc_ident}}/{{$laboratorio_prestacion[0]->fecha_toma_muestra}}/{{$laboratorio_prestacion[0]->cod_orden_lab}}" class="btn btn-danger"
                target="_blank">
                PDF Resultado de Laboratorio<i class="fa fa-file-pdf-o"></i>
            </a>
        @else
            <button class="btn btn-danger" disabled="">PDF Resultado de Laboratorio<i class="fa fa-file-pdf-o"></i></button>
        @endif
    @endif
</div>