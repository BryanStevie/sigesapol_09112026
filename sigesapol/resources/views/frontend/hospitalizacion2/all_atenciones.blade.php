@extends('frontend.layouts.master2')
@section('content')
<div class="container-fluid">
    <div class="panel panel-primary filterable">
        <div class="panel-heading">
            <h3 class="panel-title">Lista de Atenciones en Hospitalizacion de un Paciente</h3>
            <!-- <div class="pull-right">
                <a href="#" class="btn btn-default btn-xs btn-filter">Lista de Pacientes</a>
            </div> -->
        </div>
        <div class="panel-body">
            
            <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-heading">
                    <div class="panel-title text-center">
                        <b>INFORMACIÓN DEL PACIENTE</b>
                    </div>
                </div>
                <div class="panel-body" id="collapseprestacionespaciente">
                    <div class="form-group">
                        <div class="col-md-2">
                            <label for="tipo">TIPO DE DOCUMENTO</label>
                            <p>{{$hospitalizacion->id_tipo_doc_ident}}</p>
                        </div>
                        <div class="col-md-2">
                            <label for="dni_beneficiario">N° DE DOCUMENTO</label>
                            <p>{{$hospitalizacion->dni_beneficiario}}</p>
                        </div>
                        <div class="col-md-2">
                            <label>N° HISTORIA CLINICA</label>
                            <p>{{$hospitalizacion->nro_historia}}</p>
                        </div>
                        <div class="col-md-2">
                            <label for="dni_beneficiario">SEXO</label>
                            <p>{{$hospitalizacion->sexo}}</p>
                        </div>
                        <div class="col-md-4">
                            <label for="tipo">NOMBRE DE LA IPRESS</label>
                            <p>&nbsp;</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-2"><br />
                            <label>APELLIDO PATERNO</label>
                            <p>{{$hospitalizacion->paterno_beneficiario}}</p>
                        </div>
                        <div class="col-md-2"><br />
                            <label>APELLIDO MATERNO</label>
                            <p>{{$hospitalizacion->materno_beneficiario}}</p>
                        </div>
                        <div class="col-md-2"><br />
                            <label>NOMBRES</label>
                            <p>{{$hospitalizacion->nombre_beneficiario}}</p>
                        </div>
                        <div class="col-md-2"><br />
                            <label>FECHA DE NACIMIENTO</label>
                            <p>{{$hospitalizacion->fecha_nacimiento_beneficiario}}</p>
                        </div>
                        <div class="col-md-4"><br />
                            <label>PARENTESCO &nbsp;</label>
                            <p>{{$hospitalizacion->tipo_beneficiario}}</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-2"><br />
                            <label>GRADO &nbsp;</label>
                            <p>&nbsp;</p>
                        </div>
                        <div class="col-md-2">
                            <label for="tipo">PABELLON</label>
                            <p>{{$cama->pabellon}}</p>
                        </div>
                        <div class="col-md-2">
                            <label for="dni_beneficiario">PISO</label>
                            <p>{{$cama->piso}}</p>
                        </div>
                        <div class="col-md-2">
                            <label>HABITACION</label>
                            <p>{{$cama->cuarto}}</p>
                        </div>
                        <div class="col-md-4">
                            <label>CAMA</label>
                            <p>{{$cama->cama}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-info panel-bordered panel-sm">
                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="tblPrestacion" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>N</th>
                                        <th>Fecha y Hora Atencion</th>
                                        <th>Servicio</th>
                                        <th>Medico</th>
                                        <th style="text-align:center">Ver Atención</th>
                                        <th style="text-align:center">Pdf Atención</th>
                                        <th style="text-align:center;min-width:106px">Receta</th>
                                        <th style="text-align:center">Lab</th>
                                        <th style="text-align:center">Rad</th>
                                        <th style="text-align:center">Interc.</th>
                                        <th style="text-align:center">Interc. Rpta</th>
                                        <th>Anular</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($atenciones as $a)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$a->fecha_atencion.' '.$a->hora_atencion}}</td>
                                        <td>{{$a->servicio}}</td>
                                        <td>{{$a->medico_paterno.' '.$a->medico_materno.' '.$a->medico_nombre}}</td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" style="text-align: center;" data-html="true" title="<b>Ver Atención</b>">
                                                <a href="/ver_prestacion_hospitalizacion/{{$id_user}}/{{$a->id_prestacion}}" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($a->ruta_prestacion != null && $a->id_estado_reg == 1)
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">
                                                    <!--a href="/print_hospitalizacion_atencion/{{$a->id_prestacion}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i>
                                                    </a-->
                                                    <a href="/1/{{$a->ruta_prestacion}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>
                                                </div>
                                            @else
                                                <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-download"></i></a></button>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($a->ruta_receta != null && $a->id_estado_reg == 1)
                                                <?php
                                                $ruta_receta = str_replace(['{', '}'], '', $a->ruta_receta);
                                                $ruta = explode(',', $ruta_receta);
                                                
                                                $flag_receta = str_replace(['{', '}'], '', $a->flag_receta);
                                                $flag_firma = explode(',', $flag_receta);
                                                ?>
                                                @for ($i = 0; $i < count($ruta); $i++)
                                                    @if ($ruta[$i] != "NULL")
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" style="float:left;padding-left:3px">
                                                            <a href="/{{$flag_firma[0]}}/{{$ruta[$i]}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>
                                                        </div>
                                                    @else
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>" style="float:left;padding-left:3px">
                                                            <button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>
                                                        </div>
                                                    @endif
                                                @endfor
                                            @endif
                                        </td>
                                        <td>
                                            @if ($a->ruta_laboratorio != null && $a->id_estado_reg == 1)
                                                <?php
                                                $ruta_laboratorio = str_replace(['{', '}'], '', $a->ruta_laboratorio);
                                                $ruta = explode(',', $ruta_laboratorio);
                                            
                                                $flag_laboratorio = str_replace(['{', '}'], '', $a->flag_laboratorio);
                                                $flag_firma = explode(',', $flag_laboratorio);
                                                ?>
                                                @for ($i = 0; $i < count($ruta); $i++)
                                                    @if ($ruta[$i] != "NULL")
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver</b>" style="float:left;padding-left:3px">
                                                            <a href="/{{$flag_firma[0]}}/{{$ruta[$i]}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>
                                                        </div>
                                                    @else
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver</b>" style="float:left;padding-left:3px">
                                                            <button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>
                                                        </div>
                                                    @endif
                                                @endfor
                                            @endif
                                        </td>
                                        <td>
                                            @if ($a->ruta_radiologia !== null)
                                                <?php
                                                $ruta_radiologia = str_replace(['{', '}'], '', $a->ruta_radiologia);
                                                $ruta = explode(',', $ruta_radiologia);
                                            
                                                $flag_radiologia = str_replace(['{', '}'], '', $a->flag_radiologia);
                                                $flag_firma = explode(',', $flag_radiologia);
                                                ?>
                                                @for ($i = 0; $i < count($ruta); $i++)
                                                    @if ($ruta[$i] != "NULL")
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver</b>" style="float:left;padding-left:3px">
                                                            <a href="/{{$flag_firma[0]}}/{{$ruta[$i]}}" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>
                                                        </div>
                                                    @else
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver</b>" style="float:left;padding-left:3px">
                                                            <button class="btn btn-primary btn-xs" disabled=""><i class="fa fa-download"></i></button>
                                                        </div>
                                                    @endif
                                                @endfor
                                            @endif
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            @if ($a->estado == 0 || $a->estado === 6)
                                            @elseif ($a->estado == 3)
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anular Atención</b>" style="float:left;padding-left:3px"><button class="btn btn-danger btn-xs" disabled=""><i class="fa fa-remove"></i></button></div>
                                            @endif
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@include('frontend.includes.footer')
@endsection