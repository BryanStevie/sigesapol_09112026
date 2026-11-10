<style>
    .deco-red td {
        font-weight: bold;
        color: rgb(255, 255, 255);
        font-size: 1rem;
    }
    .deco-red{
        background: rgb(255, 16, 16); 
        -webkit-box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8);
        -moz-box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8);
        box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8);
    }
    .deco-red:hover{
        background: rgb(219, 2, 2); 
    }
    .deco-yellow td {
        font-weight: bold;
        color: rgb(255, 255, 255);
        font-size: 1rem;
    }
    .deco-yellow{
        background: rgb(221, 221, 15); 
        -webkit-box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8);
        -moz-box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8);
        box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8);
    }
    .deco-yellow:hover{
        background: rgb(212, 212, 2); 
    }
    .deco-green td {
        font-weight: bold;
        color: rgb(255, 255, 255);
        font-size: 1rem;
    }
    .deco-green{
        background: rgb(12, 143, 12); 
        -webkit-box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8);
        -moz-box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8);
        box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8);
    }
    .deco-green:hover{
        background: rgb(0, 121, 0); 
    }
    .deco-ce td {
        font-weight: bold;
        color: rgb(0, 0, 0);
        font-size: 1rem;
    }
    .deco-ce{
        -webkit-box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8);
        -moz-box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8);
        box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8);
    }
    .deco-ce:hover{
        background: rgb(238, 238, 238); 
    }
    .shadow{
        -webkit-box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8);
        -moz-box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8);
        box-shadow: 2px 3px 13px 0px rgba(0,0,0,0.8);
    }
    .icon-doc-firmado {
        color: #3c763d;
        font-size: 2.5rem;
    }
</style>

<script>
    $(document).ready(function () {
    $('#example').DataTable({
    "language": {
    "emptyTable": "No se encontraron resultados"
    }
    });
    $("#system-search").keyup(function() {
    var dataTable = $('#example').dataTable();
    dataTable.fnFilter(this.value);
    });

    function soloNumeros(e) {
    var key = window.Event ? e.which : e.keyCode;
    //alert(key);
    return ((key >= 48 && key <= 57) || key == 0 || key == 8)
    }

    });
</script>
<div class="row">
    <div class="col-md-12" style="margin-top: 10px">
        <legend>
            <div class="pull-right">Total: {{$cantidad}} examen(es) encontrado(s)
            </div>
        </legend>
    </div>                
    <div class="col-md-12">
        <input class="form-control input-sm" id="system-search" name="q" placeholder="Buscar examen">
    </div>
</div>
<div class="col-md-12" id="global">
    <div class="table-responsive">
        <table id="example" class="table table-list-search" style="font-size: 11px !important;" >
            <thead>
                <tr>
                    <th>N</th>
                    <th>Prioridad</th>
                    <th>Nro. Prestacion</th>
                    <th>Nro. Ex. Lab</th>
                    <th>Tipo Atencion</th>
                    <th>Especialidad</th>
                    <th>Hora<br/>Registro</th>
                    <th>Fecha<br/>Orden</th>
                    <th>Tipo Doc.</th>
                    <th>Nro. Doc.</th>
                    <th>Beneficiario</th>
                    <th>Medico</th>
                    <th>Referido</th>
                    <th>Pabellón</th>
                    <th>Piso</th>
                    <th>Habitación</th>
                    <th>Cama</th>
                    <th style="text-align:center">Pdf Examen</th>
                    @if ($estado_labcore == 1)
                        <th style="text-align:left">Validado</th>
                    @endif
                    @if ($estado_labcore == 2)
                        <th style="text-align:left">Firmados</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <?php $n = 0; ?>
                @foreach($listados as $r)
                    <tr class="deco-ce">
                    {{-- N --}}
                    <td><?php
                        $n++;
                        echo $n;
                        ?></td>
                    <td>{{-- Prioridad --}}
                        <?php
                            if ($r->id_tipo_atencion == 2) {
                                echo $r->nom_prioridad;
                            } else {
                                echo "&nbsp";
                            }
                        ?>
                    </td>
                    <td>{{$r->id_prestacion}}</td>{{-- Nro. Prestacion --}}
                    <td>{{$r->id_lab}}</td>{{-- Nro. Ex. Lab --}}
                    <td>{{-- Tipo Atencion --}}
                        @if ($r->id_tipo_atencion == 1)
                            {{$r->nom_tipo_atencion}}
                        @endif
                        @if ($r->id_tipo_atencion == 2)
                            @if ($r->id_evaluacion > 0)
                                EMERGENCIA - EVALUACIÓN
                            @else
                                {{$r->nom_tipo_atencion}}
                            @endif
                        @endif
                    </td>
                    {{-- Especialidad --}}
                    <td><?php
                            if ($r->id_tipo_atencion == 1) {
                                echo $r->consultorio;
                            } elseif ($r->id_tipo_atencion == 2) {
                                echo $r->nom_especialidad_emergencia;
                            } else {
                                echo $r->consultorio;
                            }
                        ?>
                    </td>
                    <td>{{-- Hora / Registro --}}
                        <?php
                        $originalDate3 = $r->created_at;
                        $horaR = date("H:i:s", strtotime($originalDate3));
                        echo $horaR;
                        ?> 
                    </td>
                    <td>{{-- Fecha / Orden --}}
                        <?php
                        $originalDate1 = $r->firma_laboratorio;
                        $fechaE = date("d-m-Y", strtotime($originalDate1));
                        echo $fechaE;
                        ?> 
                    </td>
                    <td>{{$r->tipo_doc_ident}}</td> {{-- Tipo Doc. --}}
                    <td>{{$r->nro_doc_ident}}</td> {{-- Nro. Doc. --}}
                    <td>{{$r->paterno_beneficiario}} {{$r->materno_beneficiario}}, {{$r->nombre_beneficiario}} </td>   {{-- Beneficiario --}}
                    <!--td></td--> 
                    <td>{{$r->paterno_medico}} {{$r->materno_medico}}, {{$r->nombre_medico}}</td>{{-- Medico --}}
                    <td> {{-- Referido --}}
                        {{-- REFERIDO DE EMERGENCIA PENDIENTE --}}
                        <?php 
                            if ($r->id_tipo_atencion == 1) {
                                echo $r->referido; 
                            } else {
                                echo $r->unidad_policial == null ? "NO" : "SI"; 
                            }
                        ?>
                    </td>
                    <td> {{-- Pabellón --}}
                        <?php
                            if ($r->id_tipo_atencion == 2) {
                                echo $r->pabellon;
                            } else {
                                echo "&nbsp";
                            }
                        ?>
                    </td>
                    <td> {{-- Piso --}}
                        <?php
                            if ($r->id_tipo_atencion == 2) {
                                echo $r->piso;
                            } else {
                                echo "&nbsp";
                            }
                        ?>
                    </td>
                    <td> {{-- Habitación --}}
                        <?php
                            if ($r->id_tipo_atencion == 2) {
                                echo $r->cuarto;
                            } else {
                                echo "&nbsp";
                            }
                        ?>
                    </td>
                    <td> {{-- Cama --}}
                        <?php
                            if ($r->id_tipo_atencion == 2) {
                                echo $r->cama;
                            } else {
                                echo "&nbsp";
                            }
                        ?>
                    </td>
                    <td class="text-center"> {{-- Pdf Examen --}}
                      <?php 
                      if ($r->ruta_laboratorio == "") {
                      ?>
                        <button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-search"></i></button>
                      <?php 
                      } else {
                      ?>
                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Examen</b>">
                            <button type="button" class="btn btn-success btn-xs shadow" onclick="detalleAtencionLaboratorio('{{$r->id_tipo_atencion}}','{{$r->id_lab}}','{{$r->id_prestacion}}')"><i class="fa fa-search"></i></button>
                        </div>
                      <?php 
                      }
                      ?>
                    </td>
                    {{-- Atender ln --}}
                    <!-- LABORATORIO LNS -->
                    <td class="text-left">
                        <i class="fa fa-check-circle icon-doc-firmado" aria-hidden="true"></i>
                    </td>
                </tr>
                @endforeach                      
            </tbody>
        </table>   
    </div>
</div>

