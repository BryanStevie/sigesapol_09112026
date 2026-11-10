<?php ini_set('memory_limit', '-1');
set_time_limit(0); ?>
@extends('frontend.layouts.master3')
<style>
  label {
    font-size: 11px;
  }
  .label-antecedentes { 
    color: #060606; 
    font-size: 11px;
    font-weight: bold
  }

  .antecedentes-content { 
    color: #060606; 
    font-size: 10px;
    font-weight: 100
  }

  li {
    color: #060606; 
    font-size: 11px;
  }

  .separate-addon {
    background-color: #ffffff !important;
    border: none !important;
  }

</style>
@section('renderjs')
    <script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>
  <script src="{{ asset('enfermeria/consulta_externa/js/handlerHistorial.js') }}"></script>
  <script>
    $(document).ready(function () {
        $("#btnFirmar").on("click", function () {
            Swal.fire({
                title: 'NOTA DE ENFERMERIA',
                text: "¿Está seguro que desea firmar la nota de enfermeria?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, firmar!',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    firmarPdf();
                }
            })    
        });

        $("#btnDownloadFirmar").on("click", function () {
            const enfermeria_ruta_firma = $("#enfermeria_ruta_firma").val();
            if (!enfermeria_ruta_firma) {
                Swal.fire({
                    icon: 'error',
                    title: "NOTA DE INGRESO DE ENFERMERÍA",
                    html: `<p style="font-size: 13px">No pudimos encontrar el archivo firmado. Por favor, verifica el enlace y vuelve a intentarlo.!</p>`,
                    confirmButtonText: "ENTENDIDO",
                });
                return null;
            }
            window.location.href = `/${moment().format("YYYY/MM/DD")}/${enfermeria_ruta_firma}`
        });

        $('a[href="#tabHistorialAtenciones"]').one('shown.bs.tab', function (e) {
          const asegurado_nro_doc_ident = $("#asegurado_nro_doc_ident").val();
          getHistorialAtencionMedica(asegurado_nro_doc_ident);
        });

        $('a[href="#tabHistorialLaboratorio"]').one('shown.bs.tab', function (e) {
          const asegurado_nro_doc_ident = $("#asegurado_nro_doc_ident").val();
          getHistorialLaboratorio(asegurado_nro_doc_ident);
        });

        $('a[href="#tabHistorialRadiologia"]').one('shown.bs.tab', function (e) {
          const asegurado_nro_doc_ident = $("#asegurado_nro_doc_ident").val();
          getHistorialRadiologia(asegurado_nro_doc_ident);
        });

        $('a[href="#tabHistorialnterconsulta"]').one('shown.bs.tab', function (e) {
          const asegurado_nro_doc_ident = $("#asegurado_nro_doc_ident").val();
          getHistorialInterconsulta(asegurado_nro_doc_ident);
        });

        $('a[href="#tabHistorialReferencia"]').one('shown.bs.tab', function (e) {
          const asegurado_nro_doc_ident = $("#asegurado_nro_doc_ident").val();
          getHistorialReferenciaContrareferencia(asegurado_nro_doc_ident);
        }); 

        $('a[href="#tabHistorialEnfermeria"]').one('shown.bs.tab', function (e) {
          const asegurado_nro_doc_ident = $("#asegurado_nro_doc_ident").val();
          getHistorialEnfermeria(asegurado_nro_doc_ident);
        }); 

        $('a[href="#tabHistorialEnfermeria"]').one('shown.bs.tab', function (e) {
          const asegurado_id = $("#asegurado_id").val();
          getHistorialEnfermeria(asegurado_id);
        }); 

        async function handlerInit() {
        try {
            Swal.fire({
                html: '<h5>CARGANDO NOTA ENFERMERIA...</h5>',
                onOpen: () => Swal.showLoading()
            });
            handlerSignosVitales(); 
            const enfermeria = {!! json_encode($enfermeria) !!};
            if (enfermeria.prestacion_id) {
              const productos = await obtenerProductosReceta(enfermeria.prestacion_id);
              if (productos && productos.length > 0) {
              buildTableRecetas(productos);
              }
              const diagnosticos = await getDiagnosticosByPrestacionId(enfermeria.prestacion_id);
              if (diagnosticos && diagnosticos.length > 0) {
              buildTableDiagnosticos(diagnosticos);
              }
            }
        } catch (error) {
            console.log(error);
            Swal.fire({
                icon: 'error',
                title: "NOTA DE INGRESO DE ENFERMERÍA",
                html: `<p style="font-size: 13px">OCURRIO UN ERROR AL CARGAR LA NOTA DE ENFERMERIA!</p>`,
                confirmButtonText: "ENTENDIDO",
            });
        } finally {
            Swal.close();
            Swal.hideLoading();
        }
        }

        function obtenerProductosReceta(prestacionId) {
        return new Promise((resolve, reject) => {
            $.ajax({
            type: "GET",
            url: `/api/receta-vale/prestacion/${prestacionId}`,
            dataType: "json",
            success: function (response) {
                resolve(response)
            },
            error: function(err) {
                reject(err);
            }	
            });
        });
        }

        function handlerSignosVitales() {
        const signosVitales = {!! json_encode($signosVitales) !!};
        if (signosVitales && signosVitales.length > 0) {
            signosVitales.forEach(element => {
            if (parseInt(element.id_caracter_fisico) === 1) {
                $("#presion_arterial_1").val(element.descripcion || 0);
            }
            if (parseInt(element.id_caracter_fisico) === 2) {
                $("#presion_arterial_2").val(element.descripcion || 0);
            }
            if (parseInt(element.id_caracter_fisico) === 3) {
                $("#talla").val(element.descripcion || 0);
            }
            if (parseInt(element.id_caracter_fisico) === 4) {
                $("#pulso_frec_cardiaca").val(element.descripcion || 0);
            }
            if (parseInt(element.id_caracter_fisico) === 5) {
                $("#peso").val(element.descripcion || 0);
            }
            if (parseInt(element.id_caracter_fisico) === 6) {
                $("#frec_respiratorio").val(element.descripcion || 0);
            }
            if (parseInt(element.id_caracter_fisico) === 7) {
                $("#fi02").val(element.descripcion || 0);
            }
            if (parseInt(element.id_caracter_fisico) === 8) {
                $("#sa02").val(element.descripcion || 0);
            }
            if (parseInt(element.id_caracter_fisico) === 9) {
                $("#temperatura").val(element.descripcion || 0);
            }
            if (parseInt(element.id_caracter_fisico) === 10) {
                $("#imc").val(element.descripcion || 0);
            }
            if (parseInt(element.id_caracter_fisico) === 11) {
                $("#peso_ideal").val(element.descripcion || 0);
            }
            });
        }
        }

        function buildTableRecetas(data) {
        $('#tableRecetas').DataTable( {
            searching: false, paging: false, info: false, ordering: false, destroy: true,
            data: data,
            columns: [
                { data: 'receta_vale_id' },
                { data: 'producto_nombre' },
                { data: 'unidad_abreviatura' },
                { data: 'cantidad_prescrita' },
                { data: 'tiempo_periodo' },
                { data: 'via_administracion_nombre' },
                { data: 'posologia' }
            ]
        });
        }

        function getDiagnosticosByPrestacionId(prestacionId) {
        return new Promise((resolve, reject) => { 
            $.ajax({
            type: 'GET',
            url: `/buscar_datos_prestacion_enfermeria/${prestacionId}`,
            dataType: "json",
            success: function (response) {
                resolve(response);
            },
            error: function (error) {
                reject(error);
            }
            });
        })
        }

        function buildTableDiagnosticos(data) {
        $('#tableDiagnosticos').DataTable( {
            searching: false, paging: false, info: false, ordering: false, destroy: true,
            data: data,
            columns: [
                { data: 'codigo_diagnostico' },
                { data: 'nombre_diagnostico' },
                { data: 'tipo_diagnostico' },
                { data: 'clasificacion' }
            ]
        });
        }

        function firmarPdf() {
            Swal.fire({
                html: '<h5>FIRMANDO NOTA ENFERMERIA</h5>',
                onOpen: () => Swal.showLoading()
            });
            $('#btnFirmar').attr("disabled", true);
            const enfermeria_id = $('#enfermeria_id').val();
            const data = {
                enfermeria_id,
            }
            $.ajax({
                type: "POST",
                url: `/api/enfermeria/consulta-externa/pdf-firmar`,
                data: data,
                dataType: "json",
                success: function (resultado) {
                    if (resultado.sw) {
                        const csv = "<?php echo url()?>/tmp/csv/" + resultado.csv + ".csv";
                        $("#doc_firmar").val(`${resultado.csv}.7z`);
                        $("#opt_firma").val(`${resultado.opt}`);
                        initInvoker('W');
                    }
                    Swal.close();
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: "NOTA DE INGRESO DE ENFERMERÍA",
                        html: `<p style="font-size: 13px">Ocurrió un error al firmar la nota de enfermería. Por favor, intente nuevamente.!</p>`,
                        confirmButtonText: "ENTENDIDO",
                    });
                },
                complete: function(xhr, status) {
                    $('#btnFirmar').attr("disabled", false);
                }
            });
        }

        window.addEventListener('getArguments', async function(e) {
            await ObtieneArgumentosParaFirmaDesdeLaWeb();
        });

        async function getArguments() {
            arg = document.getElementById("argumentos").value;
            await dispatchEventClient('sendArguments', arg);
        }

        window.addEventListener('invokerOk', function(e) {
            MiFuncionOkWeb();
        });

        window.addEventListener('invokerCancel', function(e) {
            MiFuncionCancel();
        });

        async function ObtieneArgumentosParaFirmaDesdeLaWeb() {
            const CSRF_TOKEN = $('meta[name="_token"]').attr('content');
            const entidad_firma = $("#entidad_firma").val();
            $.post("{{route('postArgumentsKardexEnfermeria')}}", {
                _token: CSRF_TOKEN,
                type: "W",
                documentName: $('#doc_firmar').val(),
                entidad: entidad_firma,
            }, function(data, status) {
                $("#argumentos_firma").val(data);
                getArgumentsConsultaExternaEnfermeria();

            });
        }

        async function getArgumentsConsultaExternaEnfermeria() {
            const arg = document.getElementById("argumentos_firma").value;
            await dispatchEventClient('sendArguments', arg);
        }

        async function MiFuncionOkWeb() {
            const enfermeria_id = $('#enfermeria_id').val();
            $.ajax({
                type: "put",
                url: `/api/enfermeria/consulta-externa/actualizar-firma/${enfermeria_id}`,
                data: JSON.stringify({}),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function (response) {
                    Swal.fire({
                        icon: "success",
                        title: "NOTAS DE ENFERMERIA",
                        text: `${response.message || "SE FIRMO EXITOSAMENTE EL PDF"}`,
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                },
            });
        }

        function MiFuncionCancel() {
            Swal.fire({
              icon: 'warning',
              title: "NOTA DE INGRESO DE ENFERMERÍA",
              html: '<h5>PROCESO DE FIRMA CANCELADO</h5>',
              timer: 2000,
              timerProgressBar: true,
              showConfirmButton: false
            });
        }

        handlerInit();
    });
  </script>
  
@endsection
@section('content')
<div class="container" style="width:95%">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="row">
              <div class="panel with-nav-tabs panel-default">
                <div class="panel-heading">
                  <ul class="nav nav-tabs" id="tabs">
                    <li class="active"><a href="#tabNuevaAtencion" data-toggle="tab" style="font-size:12px">VER DETALLE ATENCIÓN</a></li>
                    <li><a href="#tabHistorialAtenciones" data-toggle="tab" style="font-size:12px">HIST. ATENCIONES</a></li>
                    <li><a href="#tabHistorialLaboratorio" data-toggle="tab" style="font-size:12px">HIST. EXAMENES DE LABORATORIO</a></li>
                    <li><a href="#tabHistorialRadiologia" data-toggle="tab" style="font-size:12px">HIST. EXAMENES DE RADIOLOGIA</a></li>
                    <li><a href="#tabHistorialnterconsulta" data-toggle="tab" style="font-size:12px">HIST. INTERCONSULTA</a></li>
                    <li><a href="#tabHistorialReferencia" data-toggle="tab" style="font-size:12px">HIST. REFERENCIAS / CONTRAREFERENCIAS</a></li>
                    <li><a href="#tabHistorialEnfermeria" data-toggle="tab" style="font-size:12px">HIST. ENFERMERIA</a></li>
                  </ul>
                </div>
                <div class="panel-body">
                  <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="tabNuevaAtencion">
                      <div class="panel panel-primary filterable">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    <h3 class="panel-title" style="padding-top: 5px;">VER DETALLE NOTA DE INGRESO DE ENFERMERÍA - CONSULTA EXTERNA</h3> 
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <form method="post" action="{{ route('registrar_nota_ingreso_emergencia')}}" id="frmNotaIngreso" name="frmNotaIngreso" autocomplete="off">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="enfermeria_id" id="enfermeria_id" value="{{$enfermeria->enfermeria_id}}">
                                <input type="hidden" name="id_prestacion" id="id_prestacion" value="{{$enfermeria->prestacion_id}}">
                                <input type="hidden" name="asegurado_edad" id="asegurado_edad" value="{{$enfermeria->asegurado_edad}}">
                                <input type="hidden" name="asegurado_id" id="asegurado_id" value="{{$enfermeria->asegurado_id}}">
                                <input type="hidden" name="enfermeria_ruta_firma" id="enfermeria_ruta_firma" value="{{$enfermeria->enfermeria_ruta_firma}}">
                                <input type="hidden" name="entidad_firma" id="entidad_firma" value="{{Auth::user()->dni}}">
                                <input type="hidden" name="asegurado_sexo_abreviatura" id="asegurado_sexo_abreviatura">
                                <input type="hidden" name="establecimiento_nivel" id="establecimiento_nivel">
                                <input type="hidden" id="argumentos_firma" value="" />
                                <input type="hidden" name="doc_firmar" id="doc_firmar">
                                <input type="hidden" name="opt_firma" id="opt_firma">
                                <input type="hidden" id="user_firma" value="{{ Auth::user()->dni }}" />
                                <input type="hidden" name="flag_firmar" id="flag_firmar" value="1">
                                <fieldset>
                                  <div class="panel panel-info panel-bordered panel-sm">
                                      <div class="panel-heading">
                                          <div class="panel-title text-center">
                                              <b>DATOS DE LA ATENCION</b>
                                          </div>
                                      </div>
                                      <div class="panel-body">
                                          <div class="row">
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="tipo_atencion_nombre">TIPO DE LA ATENCION</label>
                                                  <input class="form-control input-sm" name="tipo_atencion_nombre" id="tipo_atencion_nombre" value="{{$enfermeria->tipo_atencion_nombre}}" readonly />
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="consultorio">SERVICIO</label>
                                                  <input class="form-control input-sm" name="consultorio" id="consultorio" value="{{$enfermeria->consultorio}}" readonly />
                                              </div> 
                                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 form-group">
                                                  <label class="control-label" for="establecimiento">IPRESS DONDE SE ATENDIO</label>
                                                  <input class="form-control input-sm" name="establecimiento" id="establecimiento" value="{{$enfermeria->establecimiento}}" readonly />
                                              </div> 
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="fecha_atencion">FECHA DE ULTIMA ATENCION</label>
                                                  <input class="form-control input-sm" name="fecha_atencion" id="fecha_atencion" value="{{$enfermeria->fecha_atencion_formatter}}" readonly />
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                <label class="control-label" for="nro_historia">NUMERO DE HISTORIA</label>
                                                <input class="form-control input-sm" name="nro_historia" id="nro_historia" value="{{$enfermeria->nro_historia}}" readonly />
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                <label class="control-label" for="fecha_atencion_enfermeria">FECHA ATENCIÓN ENFERMERIA</label>
                                                <input class="form-control input-sm" name="fecha_atencion_enfermeria" id="fecha_atencion_enfermeria" value="{{$enfermeria->fecha_atencion_enfermeria_formatter}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                <label class="control-label" for="hora_atencion_enfermeria">HORA ATENCIÓN ENFERMERIA</label>
                                                <input type="text" class="form-control input-sm" name="hora_atencion_enfermeria" id="hora_atencion_enfermeria" value="{{$enfermeria->hora_atencion_enfermeria_formatter}}" readonly>
                                              </div>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="panel panel-info panel-bordered panel-sm" id="formDataPaciente">
                                      <div class="panel-heading">
                                          <div class="panel-title text-center">
                                              <b>INFORMACIÓN DEL PACIENTE</b>
                                          </div>
                                      </div>
                                      <div class="panel-body">
                                          <div class="row">
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="asegurado_tipo_doc_identidad">TIPO DE DOC DE IDENTIDAD</label>
                                                  <input class="form-control input-sm" name="asegurado_tipo_doc_identidad" id="asegurado_tipo_doc_identidad" value="{{$enfermeria->asegurado_tipo_documento_identidad}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                <label class="control-label" for="asegurado_nro_doc_ident">NUMERO DE DOCUMENTO</label>
                                                <input class="form-control input-sm" name="asegurado_nro_doc_ident" id="asegurado_nro_doc_ident" value="{{$enfermeria->asegurado_nro_doc_ident}}" readonly>
                                            </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="asegurado_sexo">SEXO</label>
                                                  <input class="form-control input-sm" name="asegurado_sexo" id="asegurado_sexo" value="{{$enfermeria->asegurado_sexo}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="asegurado_paterno">APELLIDO PATERNO</label>
                                                  <input class="form-control input-sm" name="asegurado_paterno" id="asegurado_paterno" value="{{$enfermeria->asegurado_paterno}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="asegurado_materno">APELLIDO MATERNO</label>
                                                  <input class="form-control input-sm" name="asegurado_materno" id="asegurado_materno" value="{{$enfermeria->asegurado_materno}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="asegurado_nombre">NOMBRES</label>
                                                  <input class="form-control input-sm" name="asegurado_nombre" id="asegurado_nombre" value="{{$enfermeria->asegurado_nombre}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="asegurado_fecha_nacimiento">FECHA DE NACIMIENTO</label>
                                                  <input class="form-control input-sm" name="asegurado_fecha_nacimiento" id="asegurado_fecha_nacimiento" value="{{$enfermeria->asegurado_fecha_nacimiento}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="asegurado_tipo_beneficiario">PARENTESCO</label>
                                                  <input class="form-control input-sm" name="asegurado_tipo_beneficiario" id="asegurado_tipo_beneficiario" value="{{$enfermeria->asegurado_tipo_beneficiario}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="asegurado_grado">GRADO</label>
                                                  <input class="form-control input-sm" name="asegurado_grado" id="asegurado_grado" value="{{$enfermeria->grado}}" readonly>
                                              </div>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="panel panel-info panel-bordered panel-sm">
                                      <div class="panel-heading">
                                          <div class="panel-title text-center">
                                              <b>INFORMACIÓN DEL RESPONSABLE DE ENFERMERIA</b>
                                          </div>
                                      </div>
                                      <div class="panel-body">
                                          <div class="row">
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="tipo_documento_enfermero">TIPO DE DOC DE IDENTIDAD</label>
                                                  <input class="form-control input-sm" name="tipo_documento_enfermero" id="tipo_documento_enfermero" value="{{$enfermeria->enfermero_tipo_documento_identidad}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="nro_documento_enfermero">DOCUMENTO DE IDENTIDAD</label>
                                                  <input class="form-control input-sm" name="nro_documento_enfermero" id="nro_documento_enfermero" value="{{$enfermeria->enfermero_nro_doc_ident}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="paterno_enfermero">APELLIDO PATERNO</label>
                                                  <input class="form-control input-sm" name="paterno_enfermero" id="paterno_enfermero" value="{{$enfermeria->enfermero_paterno}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="materno_enfermero">APELLIDO MATERNO</label>
                                                  <input class="form-control input-sm" name="materno_enfermero" id="materno_enfermero" value="{{$enfermeria->enfermero_materno}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="nombre_enfermero">NOMBRES</label>
                                                  <input class="form-control input-sm" name="nombre_enfermero" id="nombre_enfermero" value="{{$enfermeria->enfemero_nombre}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="especialidad_enfermero">ESPECIALIDAD</label>
                                                  <input class="form-control input-sm" name="especialidad_enfermero" id="especialidad_enfermero" value="{{$enfermeria->enfemero_especialidad}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="profesion_enfermero">PROFESION</label>
                                                  <input class="form-control input-sm" name="profesion_enfermero" id="profesion_enfermero" value="{{$enfermeria->enfemero_profesion}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="nro_colegiatura_enfermero">N° COLEGIATURA</label>
                                                  <input class="form-control input-sm" name="nro_colegiatura_enfermero" id="nro_colegiatura_enfermero"   value="{{$enfermeria->enfemero_colegiatura}}" readonly>
                                              </div>
                                          </div>          
                                      </div>
                                  </div>

                                  <div class="panel panel-info panel-bordered panel-sm" id="formDataMedicoAtencion">
                                      <div class="panel-heading">
                                          <div class="panel-title text-center">
                                              <b>INFORMACION DEL RESPONSABLE DE ATENCION</b>
                                          </div>
                                      </div>
                                      <div class="panel-body">
                                          <div class="row">
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="medico_tipo_documento">TIPO DE DOC DE IDENTIDAD</label>
                                                  <input class="form-control input-sm" name="medico_tipo_documento" id="medico_tipo_documento" value="{{$enfermeria->medico_tipo_documento_identidad}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="medico_nro_doc_ident">N° DE DOCUMENTO DE IDENTIDAD</label>
                                                  <input class="form-control input-sm" name="medico_nro_doc_ident" id="medico_nro_doc_ident" value="{{$enfermeria->medico_nro_doc_ident}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="medico_paterno">APELLIDO PATERNO</label>
                                                  <input class="form-control input-sm" name="medico_paterno" id="medico_paterno" value="{{$enfermeria->medico_paterno}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="medico_materno">APELLIDO MATERNO</label>
                                                  <input class="form-control input-sm" name="medico_materno" id="medico_materno" value="{{$enfermeria->medico_materno}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="medico_nombre">NOMBRES</label>
                                                  <input class="form-control input-sm" name="medico_nombre" id="medico_nombre" value="{{$enfermeria->medico_nombre}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="medico_colegiatura">N° COLEGIATURA</label>
                                                  <input class="form-control input-sm" name="medico_colegiatura" id="medico_colegiatura" value="{{$enfermeria->medico_colegiatura}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="medico_rne">N° DE RNE</label>
                                                  <input class="form-control input-sm" name="medico_rne" id="medico_rne" value="{{$enfermeria->medico_rne}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="medico_profesion">PROFESION</label>
                                                  <input class="form-control input-sm" name="medico_profesion" id="medico_profesion" value="{{$enfermeria->medico_profesion}}" readonly>
                                              </div>
                                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                  <label class="control-label" for="medico_especializacion">ESPECIALIDAD</label>
                                                  <input class="form-control input-sm" name="medico_especializacion" id="medico_especializacion" value="{{$enfermeria->medico_especialidad}}" readonly>
                                              </div>
                                          </div>          
                                      </div>
                                  </div>

                                  <div class="panel panel-info panel-bordered panel-sm" id="tableReceta">
                                      <div class="panel-heading">
                                          <div class="panel-title text-center">
                                              <b>INFORMACION DE RECETA</b>
                                          </div>
                                      </div>
                                      <div class="panel-body">
                                          <table id="tableRecetas" class="table table-striped" style="font-size: 11px; width: 100%">
                                              <thead>
                                                  <tr>
                                                      <th>N</th>
                                                      <th>PRODUCTO</th>
                                                      <th>UN MED</th>
                                                      <th>CANT</th>
                                                      <th>DIAS</th>
                                                      <th>VIA ADM</th>
                                                      <th>POSOLOGIA</th>
                                                  </tr>
                                              </thead>
                                          </table>
                                      </div>
                                  </div>

                                  <div class="panel with-nav-tabs panel-default" id="sectionTabs">
                                      <div class="panel-heading">
                                          <ul class="nav nav-tabs" role="tablist" id="tabsRegistrarNotaIngreso">
                                              <li role="presentation" class="active">
                                                  <a href="#tabSignosVitales" aria-controls="tabSignosVitales" role="tab" data-toggle="tab">SIGNOS VITALES</a>
                                              </li>
                                              <li role="presentation">
                                                  <a href="#tabAntecedentes" aria-controls="tabAntecedentes" role="tab" data-toggle="tab">ANTECEDENTES</a>
                                              </li>
                                              <li role="presentation">
                                                  <a href="#tabCpmsCpt" aria-controls="tabCpmsCpt" role="tab" data-toggle="tab">CPMS/CPT</a>
                                              </li>
                                              <li role="presentation">
                                                  <a href="#tabObservaciones" aria-controls="tabObservaciones" role="tab" data-toggle="tab">OBSERVACIONES</a>
                                              </li>
                                          </ul>
                                      </div>
                                      <div class="panel-body">
                                          <div class="tab-content">
                                              <div role="tabpanel" class="tab-pane active" id="tabSignosVitales">
                                                  <div class="row">
                                                      <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                          <label class="control-label">PRESION ARTERIAL</label>
                                                          <div class="input-group">
                                                              <input class="form-control input-sm" name="presion_arterial_1" id="presion_arterial_1" maxlength="10" readonly>
                                                              <span class="input-group-addon separate-addon">/</span>
                                                              <input class="form-control input-sm" name="presion_arterial_2" id="presion_arterial_2" maxlength="10" readonly>
                                                          </div>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                          <label class="control-label" for="pulso_frec_cardiaca">PULSO/FREC. CARDIACA</label>
                                                          <input type="text" class="form-control input-sm" name="pulso_frec_cardiaca" id="pulso_frec_cardiaca" maxlength="10" placeholder="50" readonly>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                          <label class="control-label" for="frec_respiratorio">FREC. RESPIRATORIA (/MIN)</label>
                                                          <input type="text" class="form-control input-sm" name="frec_respiratorio" id="frec_respiratorio" maxlength="10" placeholder="10" readonly>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                          <label class="control-label" for="fi02">FIO2 (%)</label>
                                                          <input type="text" class="form-control input-sm" name="fi02" id="fi02" maxlength="10" placeholder="21" readonly>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                          <label class="control-label" for="sa02">SA02 (%)</label>
                                                          <input type="text" class="form-control input-sm" name="sa02" id="sa02" maxlength="10" placeholder="60" readonly>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                          <label class="control-label" for="temperatura">TEMPERATURA (°C)</label>
                                                          <input type="text" class="form-control input-sm" name="temperatura" id="temperatura" maxlength="10" placeholder="35" readonly>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                          <label class="control-label" for="talla">TALLA (CM)</label>
                                                          <input type="text" class="form-control input-sm" name="talla" id="talla" maxlength="10" placeholder="150" readonly>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                          <label class="control-label" for="peso">PESO (KG)</label>
                                                          <input type="text" class="form-control input-sm" name="peso" id="peso" maxlength="10" placeholder="50" readonly>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                          <label class="control-label" for="imc">IMC</label>
                                                          <input class="form-control input-sm" name="imc" id="imc" readonly placeholder="0" readonly>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                                          <label class="control-label" for="peso_ideal">PESO IDEAL</label>
                                                          <input class="form-control input-sm" name="peso_ideal" id="peso_ideal" readonly placeholder="0" readonly>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div role="tabpanel" class="tab-pane" id="tabAntecedentes">
                                                  <div class="panel panel-info panel-bordered panel-sm">
                                                      <div class="panel-body">
                                                          <div class="row">
                                                              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                  <p class="label-antecedentes">ANTECEDENTES</p>
                                                                  <p id="antecedente" class="antecedentes-content">{{$enfermeria->antecedente}}</p>
                                                              </div>
                                                          </div>
                                                          <hr>
                                                          <div class="row">
                                                              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                  <p class="label-antecedentes">ANTECEDENTES FAMILIARES</p>
                                                                  <p id="antecedente_familiar" class="antecedentes-content">{{$enfermeria->antecedente_familiar}}</p>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                          <label class="label-antecedentes">HIPERTENSION ARTERIAL</label>
                                                          <p id="antecedente" class="antecedentes-content">{{intval($enfermeria->id_hipertension) === 1 ? "SI" : "NO"}}</p>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                          @if (intval($enfermeria->id_hipertension) === 1)
                                                              <label class="control-label" for="tratamiento_hipertension">TRATAMIENTO (HIPERTENSION)</label>
                                                              <input class="form-control input-sm" name="tratamiento_hipertension" id="tratamiento_hipertension" value="{{$enfermeria->descripcion_hipertension}}" readonly>
                                                          @endif
                                                      </div>

                                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                          <label class="label-antecedentes">DIABETES MELLITUS</label><br>
                                                          <p id="antecedente" class="antecedentes-content">{{intval($enfermeria->id_diabetes_millitus) === 1 ? "SI" : "NO"}}</p>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                          @if (intval($enfermeria->id_diabetes_millitus) === 1)
                                                            <label class="control-label" for="tratamiento_diabetes">TRATAMIENTO (DIABETES)</label>
                                                            <input class="form-control input-sm" name="tratamiento_diabetes" id="tratamiento_diabetes" value="{{$enfermeria->descripcion_diabetes_millitus}}" readonly>
                                                          @endif
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                          <label class="control-label">ALERGIA</label><br>
                                                          <p id="antecedente" class="antecedentes-content">{{intval($enfermeria->id_alergia) === 1 ? "SI" : "NO"}}</p>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                          @if (intval($enfermeria->id_alergia) === 1)
                                                            <label class="control-label" for="detalle_alergia">DETALLE (ALERGIA)</label>
                                                            <input class="form-control input-sm" name="detalle_alergia" id="detalle_alergia" value="{{$enfermeria->descripcion_alergia}}" readonly>
                                                          @endif
                                                      </div>

                                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                          <label class="control-label">HEMODIALISIS</label><br>
                                                          <p id="antecedente" class="antecedentes-content">{{intval($enfermeria->id_hemodialisis) === 1 ? "SI" : "NO"}}</p>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                                          @if (intval($enfermeria->id_hemodialisis) === 1)
                                                              <label class="control-label" for="detalle_hemodialisis">DETALLE (HEMODIALISIS)</label>
                                                              <input class="form-control input-sm" name="detalle_hemodialisis" id="detalle_hemodialisis" value="{{$enfermeria->descripcion_hemodialisis}}" readonly>
                                                          @endif
                                                      </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 form-group">
                                                          <label class="control-label" for="cardiologico">CARDIOLOGICO</label>
                                                          <ul>
                                                              @if (isset($enfermeria->cardio_1_descripcion)) <li>{{$enfermeria->cardio_1_descripcion}}</li>@endif
                                                              @if (isset($enfermeria->cardio_2_descripcion)) <li>{{$enfermeria->cardio_2_descripcion}}</li>@endif
                                                              @if (isset($enfermeria->cardio_3_descripcion)) <li>{{$enfermeria->cardio_3_descripcion}}</li>@endif
                                                              @if (isset($enfermeria->cardio_4_descripcion)) <li>{{$enfermeria->cardio_4_descripcion}}</li>@endif
                                                          </ul>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 form-group">
                                                          <label class="control-label" for="observacion">OBSERVACION</label>
                                                          <input class="form-control input-sm" name="observacion" id="observacion" value="{{$enfermeria->descripcion_observacion}}" readonly>
                                                      </div>
                                                      <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 form-group">
                                                          <label class="control-label" for="otros_antecedentes">OTROS ANTECEDENTES</label>
                                                          <input class="form-control input-sm" name="otros_antecedentes" id="otros_antecedentes" value="{{$enfermeria->descripcion_otro}}" readonly>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div role="tabpanel" class="tab-pane" id="tabCpmsCpt">
                                                  <div class="panel panel-info panel-bordered panel-sm">
                                                      <div class="panel-heading">
                                                          <div class="panel-title text-center">
                                                              <b>DIAGNOSTICOS DE LA ATENCION</b>
                                                          </div>    
                                                      </div>
                                                      <div class="panel-body">
                                                          <table class="table table-striped" style="font-size: 11px; width: 100%" id="tableDiagnosticos">
                                                              <thead>
                                                                  <tr>
                                                                      <th width="10%">CÓDIGO</th>
                                                                      <th width="50%">NOMBRE</th>
                                                                      <th width="20%">TIPO</th>
                                                                      <th width="20%">CLASIFICACIÓN</th>
                                                                  </tr>
                                                              </thead>
                                                              <tbody></tbody>
                                                          </table>
                                                      </div>
                                                  </div>
                                                  <div class="panel panel-info panel-bordered panel-sm">
                                                    <div class="panel-heading">
                                                        <div class="panel-title text-center">
                                                            <b>PROCEDIMIENTOS MEDICOS</b>
                                                        </div>    
                                                    </div>
                                                    <div class="panel-body">
                                                        <table class="table table-striped" style="font-size: 11px; width: 100%" id="tableDiagnosticos">
                                                            <thead>
                                                                <tr>
                                                                    <th width="10%">CÓDIGO</th>
                                                                    <th width="50%">PROCEDIMIENTO</th>
                                                                    <th width="20%">CANTIDAD</th>
                                                                    <th width="20%">DIAGNOSTICO</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                              @if (isset($procedimientos) && count($procedimientos) > 0)
                                                                  @foreach ($procedimientos as $procedimiento)
                                                                    <tr>
                                                                      <td>{{$procedimiento->procedimiento_codigo}}</td>
                                                                      <td>{{$procedimiento->procedimiento_descripcion}}</td>
                                                                      <td>{{$procedimiento->prestacion_procedimiento_cantidad}}</td>
                                                                      <td>{{$procedimiento->diagnostico_nombre}}</td>
                                                                    </tr>
                                                                  @endforeach
                                                              @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                              </div>
                                              <div role="tabpanel" class="tab-pane" id="tabObservaciones">
                                                  <label class="control-label" for="observaciones">DETALLE DE OBSERVACIONES</label>
                                                  <textarea class="form-control" id="observaciones" name="observaciones" rows="3" readonly>{{$enfermeria->observacion_enfermeria}}</textarea>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                </fieldset>
                            </form>
                        </div>
                        <div class="panel-footer" style="padding: 20px 15px 30px 15px">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <a href="/enfermeria/consulta-externa" class="btn btn-danger btn-block"><i class="fa fa-ban"></i> CANCELAR</a>
                                </div>
                                @if (isset($enfermeria->enfermeria_flag_firma))
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <button type="button" class="btn btn-primary btn-block"  id="btnDownloadFirmar" ><i class="fa fa-download"></i> DESCARGAR PDF FIRMADO</button>
                                    </div>
                                @else
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <button type="button" class="btn btn-primary btn-block"  id="btnFirmar" ><i class="fa fa-pencil"></i> FIRMAR NOTA DE ENFERMERIA</button>
                                    </div>
                                @endif
                            </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tabHistorialAtenciones">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>HISTORIAL DE ATENCIONES MEDICAS</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div id='contentHistorialAtenciones'></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tabHistorialLaboratorio">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>HISTORIAL DE EXAMENES DE LABORATORIO</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div id='divLaboratorio'></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tabHistorialRadiologia">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>HISTORIAL DE EXAMENES DE IMAGENOLOGIA</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div id='divRadiologico'></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tabHistorialnterconsulta">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>HISTORIAL DE INTERCONSULTA</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div id='divInterconsultaH'></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tabHistorialReferencia">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>HISTORIAL DE REFERENCIAS / CONTRAREFERENCIAS</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div id='divRC1'></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tabHistorialEnfermeria">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>HISTORIAL DE ENFERMERIA</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div id='divHistorialEnfermeria'></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>

<div id="addComponent"></div>

	@include('frontend.includes.footer')
@endsection