
@extends('frontend.layouts.master2')
@section('renderjs')

<!-- Incluir jQuery -->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}
<script src="{{asset('js/jquery.inputmask.bundle.min.js')}}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>

@endsection
@section('content')
<div class="loader"></div>

<div class="container" style="width:90%">
  <div class="row">
    <div class="col-md-12">
      <div class="panel with-nav-tabs panel-default">
        <div class="panel-body">
          <div class="tab-content">
            <div class="tab-pane fade in active" id="tab2default">
              <fieldset>
                <div class="panel-body">
                  <div class="row">
                    <div class="col-sm-12 col-md-12">
                      <div class="row">
                        <div class="panel panel-primary filterable">
                          <div class="panel-heading">
                            <h3 class="panel-title">MÓDULO DE AUDITORÍA DE PRESTACIONES</h3>
                          </div>
                          <div class="panel-body">
                            <form class="form-horizontal" method="post" action=" " id="frmPrestacion" autocomplete="off">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                              <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">

                              <!-- Datos Principales -->
                              <fieldset style="border: 1px solid #d3d3d3; border-radius: 5px;">
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>DATOS PRINCIPALES</b>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="panel-body" id="collapseprestacionespaciente">
                                      <div class="form-group">
                                        <div class="row">
                                          <div class="col-md-4">
                                            <label>Codigo CUPS</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                              <b><?php echo $rc1->cups_pnp; ?></b>
                                            </span>
                                          </div>
                                          <div class="col-md-2">
                                            <label>Documento Paciente</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                              <b><?php echo $trama->paciente_nro_doc_identidad; ?></b>
                                            </span>
                                          </div>
                                          <div class="col-md-4">
                                            <label>Datos Pacientes</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                              <b><?php echo $trama->paciente_apellido_paterno . " " . $trama->paciente_apellido_materno . " " . $trama->paciente_nombres; ?></b>
                                            </span>
                                          </div>
                                          <div class="col-md-2">
                                            <label>Estado Trama</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                              <b><?php echo ($trama->id_estado == 0) ? 'No Enviado' : 'Enviado'; ?></b>
                                            </span>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>

                              <br>

                              <!-- Regla de Consistencia 01 -->
                              <fieldset style="border: 1px solid #d3d3d3; border-radius: 5px;">
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>REGLA DE CONSISTENCIA 01</b>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="panel-body" id="collapseprestacionespaciente">
                                      <div class="form-group">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <label>Descripción</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                              <b>PACIENTE NO SE ENCUENTRA VIGENTE EN LA FECHA DE ATENCIÓN</b>
                                            </span>
                                          </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                          <div class="col-md-2">
                                            <label>Resultado</label>
                                            <span class="form-control" style="text-align:left; background-color: <?php echo ($rc1->doc_identidad_valido == '1' and $rc1->codigo_siteds_validacion == '1') ? 'lightgreen' :($rc1->doc_identidad_valido != '1' ? 'yellow' : 'red'); ?>;">
                                              <b> 
                                                <?php echo ($rc1->doc_identidad_valido == '1' and $rc1->codigo_siteds_validacion == '1') ? 'Cumple Regla' : 'No Cumple Regla' ?>
                                              </b>
                                            </span>
                                          </div>
                                          <div class="col-md-10">
                                              <div class="col-md-12">
                                                <label>CONDICIONES DE LA REGLA</label>
                                              </div> 
                                              <div class="col-md-12">
                                              <div class="col-md-4">
                                                <label class="form-control" style="text-align:left; background-color: <?php echo ($rc1->doc_identidad_valido == '1') ? 'lightgreen' : 'yellow'; ?>;">1.- DATOS DE AFILIACION(TIPO/NUMERO DOC)</label>
                                              </div> 
                                              
                                                <div class="col-md-4">
                                                  <span class="form-control" style="text-align:left" ><?php echo $rc1->tipo_doc_identidad; ?></span>
                                                </div>
                                                <div class="col-md-4">
                                                  <span class="form-control" style="text-align:left" > <?php echo $rc1->paciente_nro_doc_identidad; ?></span>
                                                </div>

                                              </div> 
                                              <div class="col-md-12">
                                              <div class="col-md-4">
                                                <label class="form-control" style="text-align:left; background-color: <?php echo ($rc1->codigo_siteds_validacion == '1') ? 'lightgreen' : 'red'; ?>;">2.- ASEGURADOS CON COBERTURA(SITEDS)</label>                                   
                                              </div>
                                              <div class="col-md-8">
                                              <span class="form-control" style="text-align:left" ><?php echo $rc1->cod_siteds_susalud; ?></span>                                 
                                              </div>
                                              </div> 
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>

                              <br>

                              <fieldset style="border: 1px solid #d3d3d3; border-radius: 5px;">
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>REGLA DE CONSISTENCIA 02</b>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="panel-body" id="collapseprestacionespaciente">
                                      <div class="form-group">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <label>Descripción</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                              <b>LA IPRESS PNP REPORTADA NO ES PARTE DEL LISTADO DEL CONVENIO</b>
                                            </span>
                                          </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                          <div class="col-md-2">
                                            <label>Resultado</label>
                                            <span class="form-control" style="text-align:left; background-color: <?php echo ($rc2->longitud_codigo_ipress == '8' and $rc2->id_estado_reg == '1') ? 'lightgreen' :($rc2->longitud_codigo_ipress != '1' ? 'yellow' : 'red'); ?>;">
                                              <b> 
                                                <?php echo ($rc2->longitud_codigo_ipress == '8' and $rc2->id_estado_reg == '1') ? 'Cumple Regla' : 'No Cumple Regla' ?>
                                              </b>
                                            </span>
                                          </div>
                                          <div class="col-md-10">
                                              <div class="col-md-12">
                                                <label>CONDICIONES DE LA REGLA</label>
                                              </div> 
                                              <div class="col-md-12">
                                              <div class="col-md-4">
                                                <label class="form-control" style="text-align:left; background-color: <?php echo ($rc2->id_estado_reg == '1') ? 'lightgreen' : 'red'; ?>;">1.- IPRESS PNP DEBE SER PARTE DE LA BASE </label>
                                              </div> 
                                              
                                                <div class="col-md-4">
                                                  <span class="form-control" style="text-align:left" ><?php echo $rc2->id_estado_reg; ?></span>
                                                </div>
                                                <div class="col-md-4">
                                                  <span class="form-control" style="text-align:left" > <?php echo ($rc2->id_estado_reg == '1' ) ? 'Estado Activo' : 'Estado Inactivo' ?></span>
                                                </div>

                                              </div> 
                                              <div class="col-md-12">
                                              <div class="col-md-4">
                                                <label class="form-control" style="text-align:left; background-color: <?php echo ($rc2->longitud_codigo_ipress == '8') ? 'lightgreen' : 'yellow'; ?>;">2.- LONGITUD DEL CODIGO DE LA IPRESS</label>                                   
                                              </div>
                                              <div class="col-md-8">
                                              <span class="form-control" style="text-align:left" ><?php echo $rc2->codigo_ipress; ?></span>                                 
                                              </div>
                                              </div> 
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <br>

                              <fieldset style="border: 1px solid #d3d3d3; border-radius: 5px;">
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>REGLA DE CONSISTENCIA 03</b>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="panel-body" id="collapseprestacionespaciente">
                                      <div class="form-group">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <label>Descripción</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                              <b>PRESTACIONES DE SALUD NO ENCONTRADO DENTRO DEL PERIODO REPORTADO</b>
                                            </span>
                                          </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                          <div class="col-md-2">
                                            <label>Resultado</label>
                                            <span class="form-control" style="text-align:left; background-color: <?php echo ($rc2->longitud_codigo_ipress == '8' and $rc2->id_estado_reg == '1') ? 'lightgreen' :($rc2->longitud_codigo_ipress != '1' ? 'yellow' : 'red'); ?>;">
                                              <b> 
                                                <?php echo ($rc2->longitud_codigo_ipress == '8' and $rc2->id_estado_reg == '1') ? 'Cumple Regla' : 'No Cumple Regla' ?>
                                              </b>
                                            </span>
                                          </div>
                                          <div class="col-md-10">
                                              <div class="col-md-12">
                                                <label>CONDICIONES DE LA REGLA</label>
                                              </div> 
                                              <div class="col-md-12">
                                                <div class="col-md-12">
                                                  <label class="form-control" style="text-align:left; background-color: <?php echo ($rc2->id_estado_reg == '1') ? 'lightgreen' : 'red'; ?>;">1.- LA FECHA DE ATENCION/ALTA DEBE ESTAR DENTRO DEL PERIODO REPORTADO </label>
                                                </div>                                             
                                                <div class="col-md-4">
                                                  <span class="form-control" style="text-align:left" > <?php echo ($rc3->tipo_atencion === '1' ) ? 'TIPO DE ATENCION: <B>AMBULATIRIO</B>' : ($rc3->tipo_atencion === '2' ) ? 'TIPO DE ATENCION: <B>EMERGENCIA</B>' : 'TIPO DE ATENCION: <B>HOSPITALIZACION</B>'?></span>
                                                </div>
                                                <div class="col-md-4">
                                                  <span class="form-control" style="text-align:left" > <?php echo ($rc3->tipo_atencion == '1' ) ? 'FECHA ATENCION:': 'FECHA INGRESO' ;?> <B><?php echo $rc3->fecha_1; ?></B></span>
                                                </div>
                                                @if($rc3->tipo_atencion != 1)
                                                <div class="col-md-4">
                                                  <span class="form-control" style="text-align:left" ><?php echo ($rc3->tipo_atencion == '2' ) ? 'FECHA ALTA:': 'FECHA ALTA ADMINISTRATIVA:' ;?><B> <?php echo $rc3->fecha_2; ?></B></span>
                                                </div>
                                                @endif
                                                <div class="col-md-4">
                                                  <span class="form-control" style="text-align:left" >FECHA PERIODO:<B> <?php echo  $rc3->fecha_2_mas_45_dias;?> </B></span>
                                                </div>
                                                <div class="col-md-4">
                                                  <span class="form-control" style="text-align:left" >FECHA ACTUAL:<B> <?php echo $rc3->fecha_actual;?> </B></span>
                                                </div>
                                              </div>                                              
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>

                              <br>
                              <fieldset style="border: 1px solid #d3d3d3; border-radius: 5px;">
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>REGLA DE CONSISTENCIA 04</b>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="panel-body" id="collapseprestacionespaciente">
                                      <div class="form-group">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <label>Descripción</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                              <b>LA PRESTACION DE SALUD (TRAMA1) NO PUEDE SER REPORTADA MÁS DE UNA VEZ BAJO LOS CAMPOS CLAVES</b>
                                            </span>
                                          </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                          <div class="col-md-12">
                                          <div class="row">
                                          <div class="col-md-2">
                                            <label>Tipo Doc Paciente</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                               <b><?php echo $rc4->tdi_descripcion; ?></b>
                                            </span>
                                          </div>
                                          <div class="col-md-3">
                                            <label>Num Doc Paciente</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                               <b><?php echo $rc4->paciente_nro_doc_identidad; ?></b>
                                            </span>
                                          </div>
                                          <div class="col-md-2">
                                            <label>Fecha Atención</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                               <b><?php echo $rc4->fecha_atencion_fups; ?></b>
                                            </span>
                                          </div>
                                          <div class="col-md-5">
                                            <label>Código IPRESS</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                               <b><?php echo $rc4->codigo_renipress; ?></b>
                                            </span>
                                          </div>
                                          </div>
                                          <div class="row">
                                          <div class="col-md-2">
                                            <label>Doc Médico</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                               <b><?php echo $rc4->tdi1_descripcion; ?></b>
                                            </span>
                                          </div>
                                          <div class="col-md-3">
                                            <label>Num Doc Médico</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                               <b><?php echo $rc4->numdoc_ident; ?></b>
                                            </span>
                                          </div>
                                          <div class="col-md-2">
                                            <label>Código UPSS</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                               <b><?php echo $rc4->id_upss_fups; ?></b>
                                            </span>
                                          </div>
                                          <div class="col-md-5">
                                            <label>Descripción UPSS</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                               <b><?php echo $rc4->descripcion_upss; ?></b>
                                            </span>
                                          </div>
                                        </div>
                                          </div>
                                        </div>
                                        <br>
                                       
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <br>
                              <fieldset style="border: 1px solid #d3d3d3; border-radius: 5px;">
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>REGLA DE CONSISTENCIA 06</b>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="panel-body" id="collapseprestacionespaciente">
                                      <div class="form-group">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <label>Descripción</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                              <b>LOS PROCEDIMIENTOS MEDICO (TRAMA3) NO PUEDE SER REPORTADO MAS DE UNA VEZ BAJO LOS CAMPOS CLAVES</b>
                                            </span>
                                          </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                          <div class="col-md-2">
                                            <label>Resultado</label>
                                          </div>
                                          <div class="col-md-12">
                                          <div class="row">

                                          <div class="table-responsive">
                                              <table id="example" class="table table-striped">
                                                  <thead>
                                                      <tr>
                                                          <th>CPMS</th>
                                                          <th>DESCRIPCION</th>
                                                          <th style="text-align: center;">CANTIDAD</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                      @foreach ($rc9 as $diagHosp)
                                                      <tr>
                                                          <td>{{ $diagHosp->id_proced}}</td>
                                                          <td>{{ $diagHosp->descripcion}}</td>
                                                          <td style="text-align: center;">{{ $diagHosp->repeticiones}}
                                                          </td>
                                                      </tr>
                                                      @endforeach
                                                  </tbody>
                                              </table>
                                          </div> 

                                          </div>
                                         
                                          </div>
                                        </div>
                                        <br>
                                       
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <br>
                              <fieldset style="border: 1px solid #d3d3d3; border-radius: 5px;">
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>REGLA DE CONSISTENCIA 09</b>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="panel-body" id="collapseprestacionespaciente">
                                      <div class="form-group">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <label>Descripción</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                              <b>CODIGO DE PROCEDIMIENTOS MEDICOS NO TIENEN CONCORDANCIA CON EL CONVENIO</b>
                                            </span>
                                          </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                          <div class="col-md-2">
                                            <label>Resultado</label>
                                          </div>
                                          <div class="col-md-12">
                                          <div class="row">

                                          <div class="table-responsive">
                                              <table id="example" class="table table-striped">
                                                  <thead>
                                                      <tr>
                                                          <th>CPMS</th>
                                                          <th>DESCRIPCION</th>
                                                          <th style="text-align: center;">CONVENIO</th>
                                                          <th style="text-align: center;">ESTADO</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                      @foreach ($rc9 as $diagHosp)
                                                      <tr>
                                                          <td>{{ $diagHosp->id_proced}}</td>
                                                          <td>{{ $diagHosp->descripcion}}</td>
                                                          <td style="text-align: center;">
                                                              {{ $diagHosp->convenio == 1 ? 'ACTIVO' : 'INACTIVO' }}
                                                          </td>
                                                          <td style="text-align: center;">
                                                              {{ $diagHosp->estado == 1 ? 'ACTIVO' : 'INACTIVO' }}
                                                          </td>
                                                      </tr>
                                                      @endforeach
                                                  </tbody>
                                              </table>
                                          </div> 

                                          </div>
                                         
                                          </div>
                                        </div>
                                        <br>
                                       
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <br>

                              <fieldset style="border: 1px solid #d3d3d3; border-radius: 5px;">
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>REGLA DE CONSISTENCIA 12</b>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="panel-body" id="collapseprestacionespaciente">
                                      <div class="form-group">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <label>Descripción</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                              <b>PRESTACION DE SALUD INCOMPLETA TRAMA 2 Y 3</b>
                                            </span>
                                          </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                          <div class="col-md-2">
                                            <label>Resultado</label>
                                          </div>
                                          <div class="col-md-12">
                                          <div class="row">

                                          <div class="table-responsive">
                                              <table id="example" class="table table-striped">
                                                  <thead>
                                                      <tr>
                                                          <th>CIE10</th>
                                                          <th>DESCRIPCION CIE10</th>
                                                          <th>CPMS</th>
                                                          <th>DESCRIPCION CPMS</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                      @foreach ($rc12 as $diagHosp)
                                                      <tr>
                                                          <td>{{ $diagHosp->codigo}}</td>
                                                          <td>{{ $diagHosp->diagnostico}}</td>
                                                          <td>{{ $diagHosp->id_proced}}</td>
                                                          <td>{{ $diagHosp->procedimiento}}</td>
                                                      </tr>
                                                      @endforeach
                                                  </tbody>
                                              </table>
                                          </div> 

                                          </div>
                                         
                                          </div>
                                        </div>
                                        <br>
                                       
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <br>

                              <fieldset style="border: 1px solid #d3d3d3; border-radius: 5px;">
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>REGLA DE CONSISTENCIA 13</b>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="panel-body" id="collapseprestacionespaciente">
                                      <div class="form-group">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <label>Descripción</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                              <b>SERVICIO (UPSS) NO CORRESPONDE PARA SEXO DEL PACIENTE</b>
                                            </span>
                                          </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                          <div class="col-md-12">
                                          <div class="row">

                                          <div class="table-responsive">
                                              <table id="example" class="table table-striped">
                                                  <thead>
                                                      <tr>
                                                          <th>UPSS</th>
                                                          <th>DESCRIPCION UPSS</th>
                                                          <th>DNI PACIENTE</th>                                                   
                                                          <th>SEXO  PACIENTE</th>
                                                          <th>REGLA</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                      @foreach ($rc13 as $diagHosp)
                                                      <tr>
                                                          <td>{{ $diagHosp->id_upss_fups}}</td>
                                                          <td>{{ $diagHosp->descripcion_upss}}</td>
                                                          <td>{{ $diagHosp->paciente_nro_doc_identidad}}</td>
                                                          <td>{{ $diagHosp->sexo}}</td>
                                                          <td>{{ $diagHosp->regla}}</td>
                                                      </tr>
                                                      @endforeach
                                                  </tbody>
                                              </table>
                                          </div> 

                                          </div>
                                         
                                          </div>
                                        </div>
                                        <br>
                                       
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <br>
                              
                              <fieldset style="border: 1px solid #d3d3d3; border-radius: 5px;">
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>REGLA DE CONSISTENCIA 14</b>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="panel-body" id="collapseprestacionespaciente">
                                      <div class="form-group">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <label>Descripción</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                              <b>SERVICIO (UPSS) NO CORRESPONDE PARA LA EDAD DEL PACIENTE</b>
                                            </span>
                                          </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                          <div class="col-md-12">
                                          <div class="row">

                                          <div class="table-responsive">
                                              <table id="example" class="table table-striped">
                                                  <thead>
                                                      <tr>
                                                          <th>UPSS</th>
                                                          <th>DESCRIPCION UPSS</th>
                                                          <th>DNI PACIENTE</th>                                                   
                                                          <th>FEC NAC  PACIENTE</th>
                                                          <th>EDAD  PACIENTE</th>
                                                          <th>REGLA</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                      @foreach ($rc14 as $diagHosp)
                                                      <tr>
                                                          <td>{{ $diagHosp->id_upss_fups}}</td>
                                                          <td>{{ $diagHosp->descripcion_upss}}</td>
                                                          <td>{{ $diagHosp->paciente_nro_doc_identidad}}</td>
                                                          <td>{{ $diagHosp->fecha_nac}}</td>
                                                          <td>{{ $diagHosp->edad}}</td>
                                                          <td>{{ $diagHosp->regla}}</td>
                                                      </tr>
                                                      @endforeach
                                                  </tbody>
                                              </table>
                                          </div> 

                                          </div>
                                         
                                          </div>
                                        </div>
                                        <br>
                                       
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <br>
                                                            
                              <fieldset style="border: 1px solid #d3d3d3; border-radius: 5px;">
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>REGLA DE CONSISTENCIA 15</b>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="panel-body" id="collapseprestacionespaciente">
                                      <div class="form-group">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <label>Descripción</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                              <b>REGISTRO DE LAS PRESTACIONES DE SALUD CON CONSUMOS DE PRODUCTOS MÉDICOS PARA EL DIAGNOSTICO PRESUNTIVO</b>
                                            </span>
                                          </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                          <div class="col-md-12">
                                          <div class="row">

                                          <div class="table-responsive">
                                              <table id="example" class="table table-striped">
                                                  <thead>
                                                      <tr>
                                                          <th>TIPO ATENCION</th>
                                                          <th> CIE10</th>
                                                          <th>TIPO DIAGNOSTICO</th>                                                   
                                                          <th>   REGLA</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                      @foreach ($rc15 as $diagHosp)
                                                      <tr>
                                                          <td>
                                                              {{ $diagHosp->tipo_atencion == 1 ? 'Consulta Externa' : ($diagHosp->tipo_atencion == 2 ? 'Emergencia' : ($diagHosp->tipo_atencion == 3 ? 'Hospitalización' : '')) }}
                                                          </td>
                                                          <td>{{ $diagHosp->id_cie10}}</td>
                                                          <td>
                                                              {{ $diagHosp->id_tipo_diag == 1 ? 'PRESUNTIVO' : ($diagHosp->id_tipo_diag == 2 ? 'DEFINITIVO' : ($diagHosp->id_tipo_diag == 3 ? 'REITERATIVO' : '')) }}
                                                          </td>
                                                          <td>{{ $diagHosp->regla}}</td>
                                                      </tr>
                                                      @endforeach
                                                  </tbody>
                                              </table>
                                          </div> 

                                          </div>
                                         
                                          </div>
                                        </div>
                                        <br>
                                       
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <br>

                                                                                          
                              <fieldset style="border: 1px solid #d3d3d3; border-radius: 5px;">
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>REGLA DE CONSISTENCIA 16</b>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="panel-body" id="collapseprestacionespaciente">
                                      <div class="form-group">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <label>Descripción</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                              <b>NO CORRESPONDE DIA HOSPITALARIO PARA SERVICIO DE CONSULTA EXTERNA</b>
                                            </span>
                                          </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                          <div class="col-md-12">
                                          <div class="row">

                                          <div class="table-responsive">
                                              <table id="example" class="table table-striped">
                                                  <thead>
                                                      <tr>
                                                          <th>TIPO ATENCION</th>
                                                          <th> CPMS</th>
                                                          <th>DESCRIPCION CPMS</th>                                                   
                                                          <th>   REGLA</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                      @foreach ($rc16 as $diagHosp)
                                                      <tr>
                                                          <td>
                                                              {{ $diagHosp->tipo_atencion == 1 ? 'Consulta Externa' : ($diagHosp->tipo_atencion == 2 ? 'Emergencia' : ($diagHosp->tipo_atencion == 3 ? 'Hospitalización' : '')) }}
                                                          </td>
                                                          <td>{{ $diagHosp->id_proced}}</td>
                                                          <td>{{ $diagHosp->procedimiento}}</td>
                                                          <td>{{ $diagHosp->regla}}</td>
                                                      </tr>
                                                      @endforeach
                                                  </tbody>
                                              </table>
                                          </div> 

                                          </div>
                                         
                                          </div>
                                        </div>
                                        <br>
                                       
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <br>

                              <fieldset style="border: 1px solid #d3d3d3; border-radius: 5px;">
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center">
                                      <b>REGLA DE CONSISTENCIA 17</b>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="panel-body" id="collapseprestacionespaciente">
                                      <div class="form-group">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <label>Descripción</label>
                                            <span class="form-control" style="text-align:left" name="consultorio" id="consultorio">
                                              <b>NO CORRESPONDE DIA HOSPITALARIO PARA SERVICIO DE EMERGENCIA CON ESTANCIA DE UN DIA</b>
                                            </span>
                                          </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                          <div class="col-md-12">
                                          <div class="row">

                                          <div class="table-responsive">
                                              <table id="example" class="table table-striped">
                                                  <thead>
                                                      <tr>
                                                          <th>CPMS</th>
                                                          <th>DESCRIPCION</th>   
                                                          <th>REPETICIONES</th>                                                  
                                                          <th>REGLA</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                      @foreach ($rc17 as $diagHosp)
                                                      <tr>
                                                          <td>
                                                              {{ $diagHosp->id_proced}}
                                                          </td>
                                                          <td>{{ $diagHosp->descripcion}}</td>
                                                          <td>{{ $diagHosp->repeticiones}}</td>
                                                          <td>{{ $diagHosp->regla}}</td>
                                                      </tr>
                                                      @endforeach
                                                  </tbody>
                                              </table>
                                          </div> 

                                          </div>
                                         
                                          </div>
                                        </div>
                                        <br>
                                       
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <br>
                              <a href="/all_auditoria" class="btn btn-primary pull-right" id="btnPrestacion">LISTADO</a>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


  @include('frontend.includes.footer')
  @endsection