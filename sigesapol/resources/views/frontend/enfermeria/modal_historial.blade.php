
  <title>Sistema de Farmacia</title>

  <style>
  /*****************/
  .modal-dialog {
    width: 90%;
    }
    
  #tablemodal{
      border-spacing: 0;
      display: flex;/*Se ajuste dinamicamente al tamano del dispositivo**/
      max-height: 80vh; /*El alto que necesitemos**/
      overflow-y: auto; /**El scroll verticalmente cuando sea necesario*/
      overflow-x: hidden;/*Sin scroll horizontal*/
      table-layout: fixed;/**Forzamos a que las filas tenga el mismo ancho**/
      width: 98vw; /*El ancho que necesitemos*/
      border:1px solid #c4c0c9;
  }

  #tablemodal thead{
      background-color: #e2e3e5;
      position: fixed !important;
  }


  #tablemodal th{
      border-bottom: 1px solid #c4c0c9;
      border-right: 1px solid #c4c0c9;
  }

  #tablemodal th{
      font-weight: normal;
      margin: 0;
      max-width: 9.5vw; 
      min-width: 9.5vw;
      word-wrap: break-word;
      font-size: 10px;
    font-weight:bold;
      height: 3.5vh !important;
    line-height:12px;
    vertical-align:middle;
    /*height:20px;*/
      padding: 4px;
      border-right: 1px solid #c4c0c9;
  }

  #tablemodal td{
      font-weight: normal;
      margin: 0;
      max-width: 9.5vw; 
      min-width: 9.5vw;
      word-wrap: break-word;
      font-size: 11px;
      height: 3.5vh !important;
      padding: 4px;
      border-right: 1px solid #c4c0c9;
  }

  #tablemodal tbody tr:hover td, #tablemodal1 tbody tr:hover th {
    /*background-color: red!important;*/
    font-weight:bold;
    /*mix-blend-mode: difference;*/
    
  }

  #tablemodalm4{
    
  }
  </style>

  <script type="text/javascript">
    $(document).on('click', '#btnShowDetail', async function() {
        const id = $(this).data('id');
        const tipo_atencion_id = $(this).data('tipo_atencion_id');

        const result = await getEnfermeriaIdTipoAtencionId(id, tipo_atencion_id);
        if (result) {
          let enfermeria = null;
          let signosVitales = null;
          let diagnosticos = [];
          let procedimientos = [];
          if (result.enfermeria && Array.isArray(result.enfermeria) && result.enfermeria.length > 0) {
            enfermeria = result.enfermeria[0];
          } else {
            enfermeria = result.enfermeria;
          }

          if (result.procedimientos && Array.isArray(result.procedimientos) && result.procedimientos.length > 0) {
            procedimientos = result.procedimientos;
          }

          if (result.signosVitales && result.signosVitales.length > 0) {
            signosVitales = result.signosVitales;
          }

          if (result.diagnosticos && result.diagnosticos.length > 0) {
            diagnosticos = result.diagnosticos;
          }
          if (enfermeria) {
            $('#modalDetailEnfermeria').modal('show');
            $('#modalDetailEnfermeria input').val('');
            setFormValues(enfermeria, tipo_atencion_id, procedimientos, signosVitales);
          }

          if (parseInt(tipo_atencion_id) == 1) {
            $("#div-diagnostic").hide();
          }

          if (parseInt(tipo_atencion_id) == 2 || parseInt(tipo_atencion_id) == 3) {
            $("#div-diagnostic").show();
            buildRowsTable(diagnosticos);
          }

        }
        
    });

    function getEnfermeriaIdTipoAtencionId(id, tipoAtencionId, signosVitales) {
      return new Promise((resolve, reject) => {
        $.ajax({
          type: "GET",
          url: `/api/enfermeria/tipo-atencion/${id}/${tipoAtencionId}`,
          dataType: "json",
          beforeSend: function() {
            Swal.fire({
              html: '<h5>OBTENIENDO INFORMACIÓN..</h5>',
              showLoaderOnConfirm: true,
              onOpen: () => Swal.showLoading()
            });
          },
          success: function (response) {
            resolve(response);
          },
          error: function(err) {
            reject(err)
          },
          complete: function() {
            Swal.hideLoading();
            Swal.close();
          }
        });
      });
    }

    function setFormValues(data, tipoAtencionId, procedimientos, signosVitales) {
      $("#panel_responsable_atencion").hide();
      $("#panel_tabs").hide();
      $("#ha_cardiologico").empty()
      $("#ha_table_procedimientos tbody").empty();
      $("#ha_descripcion_hipertension_div").hide();
      $("#ha_descripcion_diabetes_millitus_div").hide();
      $("#detalle_alergia_div").hide();
      $("#ha_descripcion_hemodialisis_div").hide();
      if (parseInt(tipoAtencionId) === 2) {
        $('label[for="area_h"]').text("AREA");

        $("#tipo_atencion_h").val("EMERGENCIA");
        $("#prioridad_h_div").show();
        $("#area_h_div").show();

        $("#hora_atencion_h_div").show();
        $("#nro_orden_h_div").show();
        $("#nro_historia_clinica_h_div").show();
        $("#codigo_ups_h_div").show();
        $("#ipress_h_div").show();
        $("#cip_beneficiario_h").show();
        $("#situacion_paciente_h").show();
      }
      if (parseInt(tipoAtencionId) === 3) {
        $('label[for="area_h"]').text("AREA");

        $("#tipo_atencion_h").val("HOSPITALIZACION");
        $("#prioridad_h_div").hide();
        $("#area_h_div").hide();

        $("#hora_atencion_h_div").show();
        $("#nro_orden_h_div").show();
        $("#nro_historia_clinica_h_div").show();
        $("#codigo_ups_h_div").show();
        $("#ipress_h_div").show();
        $("#cip_beneficiario_h").show();
        $("#situacion_paciente_h").show();
      }

      if (parseInt(tipoAtencionId) === 1) {
        $("#panel_responsable_atencion").show();
        $("#panel_tabs").show();

        $("#hora_atencion_h_div").hide();
        $("#prioridad_h_div").hide();
        $("#cama_h_div").hide();
        $("#nro_orden_h_div").hide();
        $("#nro_historia_clinica_h_div").hide();
        $("#codigo_ups_h_div").hide();
        $("#ipress_h_div").hide();
        $("#cip_beneficiario_h").hide();
        $("#situacion_paciente_h").hide();

        $('label[for="area_h"]').text("SERVICIO");

        $("#tipo_atencion_h").val(data.tipo_atencion_nombre);
        $("#fecha_atencion_h").val(data.fecha_atencion);
        $("#ipress_h").val(data.establecimiento);
        $("#area_h").val(data.consultorio);

        $("#dni_beneficiario_h").val(data.asegurado_nro_doc_ident || "");
        $("#paterno_beneficiario_h").val(data.asegurado_paterno || "");
        $("#materno_beneficiario_h").val(data.asegurado_materno || "");
        $("#nombre_beneficiario_h").val(data.asegurado_nombre || "");
        $("#tipo_beneficiario_h").val(data.asegurado_tipo_beneficiario || "");
        $("#fecha_nac_paciente_h").val(data.asegurado_fecha_nacimiento);
        // $("#cip_beneficiario_h").val(data.cip_beneficiario || "");
        $("#grado_beneficiario_h").val(data.grado || "");
        // $("#situacion_paciente_h").val(data.asegurado_tipo_beneficiario || "");
        $("#sexo_h").val(data.asegurado_sexo || "");
        $("#sangre_beneficiario_h").val(data.sangre_beneficiario || "");
        $("#edad_beneficiario_h").val(data.edad_beneficiario || "");

        $("#profesional_dni_h").val(data.enfermero_nro_doc_ident || "");
        $("#profesional_paterno_h").val(data.enfermero_paterno || "");
        $("#profesional_materno_h").val(data.enfermero_materno || "");
        $("#profesional_nombre_h").val(data.enfemero_nombre || "");
        $("#especialidad_medico_h").val(data.enfemero_especialidad || "");
        $("#profesion_medico_h").val(data.enfemero_profesion || "");
        $("#colegiatura_h").val(data.enfemero_colegiatura || "");

        $("#he_resp_aten_tipo_doc").val(data.medico_tipo_documento_identidad || "");
        $("#he_resp_aten_nro_documento").val(data.medico_nro_doc_ident || "");
        $("#he_resp_aten_apellido_paterno").val(data.medico_paterno || "");
        $("#he_resp_aten_apellido_materno").val(data.medico_materno || "");
        $("#he_resp_aten_nombres").val(data.medico_nombre || "");
        $("#he_resp_aten_colegiatura").val(data.medico_colegiatura || "");
        $("#he_resp_aten_rne").val(data.medico_rne || "");
        $("#he_resp_aten_profesion").val(data.medico_profesion || "");
        $("#he_resp_aten_especialidad").val(data.medico_especialidad || "");
        $("#ha_descripcion_observacion").val(data.descripcion_observacion || "");
        $("#ha_descripcion_otro").val(data.descripcion_otro || "");

        $("#ha_antecedente").text(data.antecedente || "");
        $("#ha_antecedente_familiar").text(data.antecedente_familiar || "");
        $("#ha_id_hipertension").text(parseInt(data.id_hipertension) === 1 ? "SI" : "NO" || "");
        if (parseInt(data.id_hipertension) === 1) {
          $("#ha_descripcion_hipertension_div").show();
        }

        $("#ha_descripcion_hipertension").val(data.descripcion_hipertension || "");
        $("#ha_id_diabetes_millitus").val(parseInt(data.id_diabetes_millitus) === 1 ? "SI" : "NO" || "");
        if (parseInt(data.id_diabetes_millitus) === 1) {
          $("#ha_descripcion_diabetes_millitus_div").show();
        }

        $("#ha_descripcion_diabetes_millitus").text(data.descripcion_diabetes_millitus || "");
        $("#ha_id_alergia").val(parseInt(data.id_alergia) === 1 ? "SI" : "NO" || "");
        if (parseInt(data.ha_id_alergia) === 1) {
          $("#detalle_alergia_div").show();
        }

        $("#descripcion_alergia").val(data.descripcion_alergia || "");
        $("#ha_id_hemodialisis").val(parseInt(data.id_hemodialisis) === 1 ? "SI" : "NO" || "");
        if (parseInt(data.ha_id_hemodialisis) === 1) {
          $("#ha_descripcion_hemodialisis_div").show();
        }

        if (data.cardio_1_descripcion) {
          $("#ha_cardiologico").append(`<li>${data.cardio_1_descripcion}</li>`);
        }
        if (data.cardio_2_descripcion) {
           $("#ha_cardiologico").append(`<li>${data.cardio_2_descripcion}</li>`);
        }
        if (data.cardio_3_descripcion) {
          $("#ha_cardiologico").append(`<li>${data.cardio_3_descripcion}</li>`);
        }
        if (data.cardio_4_descripcion) {
          $("#ha_cardiologico").append(`<li>${data.cardio_4_descripcion}</li>`);
        }

        $("#ha_observacion_enfermeria").val(data.observacion_enfermeria || "");

        if (signosVitales && signosVitales.length > 0) {
          handlerSignosVitales(signosVitales);
        }

        if (procedimientos && procedimientos.length > 0) {
          procedimientos.forEach((procedimiento) => {
            let newRow = `<tr>
                            <td>${procedimiento.procedimiento_codigo}</td>
                            <td>${procedimiento.procedimiento_descripcion}</td>
                            <td>${procedimiento.prestacion_procedimiento_cantidad}</td>
                            <td>${procedimiento.diagnostico_nombre}</td>
                          </tr>`;
            $('#ha_table_procedimientos tbody').append(newRow);
          });
        }
      }

      if (parseInt(tipoAtencionId) === 2 || parseInt(tipoAtencionId) === 3) {
        $("#fecha_atencion_h").val(data.fecha_creacion || "");
        $("#hora_atencion_h").val(data.hora_creacion || "");
        $("#prioridad_h").val(data.des_prioridades || "");
        $("#area_h").val(data.topico || "");
        $("#cama_h").val(data.cama || "");
        $("#nro_orden_h").val("2");
        $("#nro_historia_clinica_h").val(data.nro_doc_ident || "");
        $("#codigo_ups_h").val(data.codigo_ups || "");
        $("#ipress_h").val(data.nombre_estab || "");

        $("#dni_beneficiario_h").val(data.dni_beneficiario || "");
        $("#paterno_beneficiario_h").val(data.paterno_beneficiario || "");
        $("#materno_beneficiario_h").val(data.materno_beneficiario || "");
        $("#nombre_beneficiario_h").val(data.nombre_beneficiario || "");
        $("#tipo_beneficiario_h").val(data.tipo_beneficiario || "");
        $("#fecha_nac_paciente_h").val(data.fecha_nac_paciente);
        $("#cip_beneficiario_h").val(data.cip_beneficiario || "");
        $("#grado_beneficiario_h").val(data.grado_beneficiario || "");
        $("#situacion_paciente_h").val(data.situacion_paciente || "");
        $("#sexo_h").val(data.sexo == "M" ? 'MASCULINO' : 'FEMENINO' || "");
        $("#sangre_beneficiario_h").val(data.sangre_beneficiario || "");
        $("#edad_beneficiario_h").val(data.edad_beneficiario || "");

        if (data.conducio_por !== "SOLO") {
          $("#div_acompaniea").show();

          $("#conducio_por").val(data.conducio_por || "");
          $("#dni_conducido").val(data.dni_conducido || "" + " - " + data.nombre_conducido || "");
          $("#telf_conducido").val(data.telf_conducido || "");
          $("#observaciones_benefi").val(data.observaciones_benefi || "");
        }

        $("#profesional_dni_h").val(data.profesional_dni || "");
        $("#profesional_paterno_h").val(data.profesional_paterno || "");
        $("#profesional_materno_h").val(data.profesional_materno || "");
        $("#profesional_nombre_h").val(data.profesional_nombre || "");
        $("#especialidad_medico_h").val(data.especialidad_medico || "");
        $("#profesion_medico_h").val(data.profesion_medico || "");
        $("#colegiatura_h").val(data.colegiatura || "");
      }
    }

    function buildRowsTable(diagnosticos) {
      if ($.fn.DataTable.isDataTable('#table-diagnostic-history')) {
        $('#table-diagnostic-history').DataTable().destroy();
      }
      $('#table-diagnostic-history').DataTable({
        destroy: true,
        data: diagnosticos,
        searching: false,
        paging: false,
        info: false,
        ordering: false,
        columns: [
          { data: "codigo_diagnostico" },
          { data: "descripcion_diagnostico" },
          { data: "descripcion_tipo_diagnosticos" },
          { 
            data: "id_evaluacion",
            render: function(data) {
                return parseInt(data) == 0 ? "ATENCIÓN" : "EVALUACIÓN" 
            }
          }
        ]
      });
    }

    function handlerSignosVitales(signosVitales) {
      if (signosVitales && signosVitales.length > 0) {
          signosVitales.forEach(element => {
          if (parseInt(element.id_caracter_fisico) === 1) {
              $("#ha_presion_arterial_1").val(element.descripcion || 0);
          }
          if (parseInt(element.id_caracter_fisico) === 2) {
              $("#ha_presion_arterial_2").val(element.descripcion || 0);
          }
          if (parseInt(element.id_caracter_fisico) === 3) {
              $("#ha_talla").val(element.descripcion || 0);
          }
          if (parseInt(element.id_caracter_fisico) === 4) {
              $("#ha_pulso_frec_cardiaca").val(element.descripcion || 0);
          }
          if (parseInt(element.id_caracter_fisico) === 5) {
              $("#ha_peso").val(element.descripcion || 0);
          }
          if (parseInt(element.id_caracter_fisico) === 6) {
              $("#ha_frec_respiratorio").val(element.descripcion || 0);
          }
          if (parseInt(element.id_caracter_fisico) === 7) {
              $("#ha_fi02").val(element.descripcion || 0);
          }
          if (parseInt(element.id_caracter_fisico) === 8) {
              $("#ha_sa02").val(element.descripcion || 0);
          }
          if (parseInt(element.id_caracter_fisico) === 9) {
              $("#ha_temperatura").val(element.descripcion || 0);
          }
          if (parseInt(element.id_caracter_fisico) === 10) {
              $("#ha_imc").val(element.descripcion || 0);
          }
          if (parseInt(element.id_caracter_fisico) === 11) {
              $("#ha_peso_ideal").val(element.descripcion || 0);
          }
          });
      }
    }

  </script>


  <body class="hold-transition skin-blue sidebar-mini">
      <div>
          <section class="content">
                  <div class="col-md-12">
                    <div class="box box-primary">
                      <br>
                      <div class="box-body">
                          <div class="table-responsive">
                              <table class="table table-bordered table-sm" id="tablemodalm6" style="font-size: 11px">
                                  <thead>
                                      <tr>
                                        <th>ID</th>
                                        <th>SERVICIO</th> 
                                        <th>TIPO ATENCIÓN</th>
                                        <th>FECHA INGRESO</th> 
                                        <th>HORA INGRESO</th> 
                                        <th>RESPONSABLE</th>
                                        <th>VER</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                    @if (isset($enfermerias))
                                        @foreach ($enfermerias as $r)
                                            <tr>
                                              <td>{{ $r->enfermeria_id }}</td>
                                              <td>{{ $r->consultorio_nombre }}</td>
                                              <td>{{ $r->tipo_atencion_nombre }}</td>
                                              <td>
                                                <span>
                                                  {{ isset($r->enfermeria_fecha_ingreso) ? \Carbon\Carbon::parse($r->enfermeria_fecha_ingreso)->format('d/m/Y') : '-' }}
                                                </span>                                            
                                              </td>
                                              <td>{{ isset($r->enfermeria_hora_ingreso) ? \Carbon\Carbon::parse($r->enfermeria_hora_ingreso)->format('h:i A') : '-' }}</td>
                                              <td>
                                                <div>
                                                  <span><strong>DNI: </strong>{{ $r->enfermero_dni }}</span><br>
                                                  <span><strong>NOMBRE: </strong>{{ $r->enfermero_nombre }}</span><br>
                                                  <span><strong>APELLIDOS: </strong>{{ isset($r->enfermero_paterno) ? $r->enfermero_paterno : "" }} {{isset($r->enfermero_materno) ? $r->enfermero_materno : ""}}</span>
                                                </div>
                                              </td>
                                              <td>
                                                <button 
                                                  type="button" 
                                                  class="btn btn-success btn-xs" 
                                                  data-toggle="tooltip" 
                                                  data-placement="top" 
                                                  title="Ver Detalle"
                                                  id="btnShowDetail"
                                                  data-id="{{ $r->enfermeria_id }}"
                                                  data-tipo_atencion_id="{{ $r->tipo_atencion_id }}"
                                                ><i class="fa fa-eye" aria-hidden="true"></i></button>
                                              </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                  </tbody>
                              </table>
                          </div>
                      </div>
                    </div>
                  <!-- /.box -->
                  </div>
                  <!--/.col (left) -->
              
            <!-- /.r -->
          </section>
          <!-- /.content -->
      </div>

  <!-- /.content-wrapper -->
  <div class="modal fade" id="myModalInterconsulta1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h5 class="modal-title">DETALLE DEL EXAMEN DE INTERCONSULTA. <span id="nrorecmodal1"></span></h5>
              </div>
              <div class="modal-body">
                  <div id="muestra-det-interconsulta1"></div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              </div>
          </div>
      </div>
  </div>
  <div class="modal fade" id="myModalInterconsulta2" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h5 class="modal-title">DETALLE DEL RPTA DE INTERCONSULTA. <span id="nrorecmodal2"></span></h5>
              </div>
              <div class="modal-body">
                  <div id="muestra-det-interconsulta2"></div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              </div>
          </div>
      </div>
  </div>

  <div class="modal fade" id="modalDetailEnfermeria" tabindex="-1" role="dialog" aria-labelledby="modalDetailEnfermeriaLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg " role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
          <h5 class="modal-title" id="exampleModalLabel">DETALLE ENFERMERIA</h5>
        </div>
        <div class="modal-body" style="font-size: 11px">
          <div class="container-fluid">
            <div class="panel panel-info panel-bordered panel-sm">
              <div class="panel-heading">
                <div class="panel-title text-center">
                  <b>DATOS DE LA ATENCION</b>
                </div>
              </div>
              <div class="panel-body">
                <div class="row form-group">
                  <div class="col-md-2">
                    <label for="tipo_atencion_h">TIPO DE LA ATENCION</label>
                    <input class="form-control" id="tipo_atencion_h" readonly>
                  </div>
                  <div class="col-md-2">
                    <label for="fecha_atencion_h">FECHA DE LA ATENCION</label>
                    <input class="form-control" id="fecha_atencion_h" readonly>
                  </div>
                  <div class="col-md-2" id="hora_atencion_h_div">
                    <label for="hora_atencion_h">HORA DE LA ATENCION</label>
                    <input class="form-control" id="hora_atencion_h" readonly>
                  </div>
                  <div class="col-md-3" id="prioridad_h_div">
                    <label for="prioridad_h">PRIORIDAD</label>
                    <input class="form-control" id="prioridad_h" readonly>
                  </div>
                  <div class="col-md-3" id="area_h_div">
                    <label for="area_h">AREA</label>
                    <input class="form-control" id="area_h" readonly>
                  </div>
                </div>
                <div class="row form-group" >
                  <div class="col-md-2" id="cama_h_div">
                    <label for="medico_nombre">CAMA</label>
                    <input class="form-control" id="cama_h" readonly>
                  </div>
                  <div class="col-md-2" id="nro_orden_h_div">
                    <label for="medico_nombre">N° ORDEN</label>
                    <input class="form-control" id="nro_orden_h" readonly>
                  </div>
                  <div class="col-md-2" id="nro_historia_clinica_h_div">
                    <label for="medico_nombre">N° HISTORIA CLINICA</label>
                    <input class="form-control" id="nro_historia_clinica_h" readonly>
                  </div>
                  <div class="col-md-2" id="codigo_ups_h_div">
                    <label for="medico_nombre">CODIGO UPS</label>
                    <input class="form-control" id="codigo_ups_h" readonly>
                  </div>
                  <div class="col-md-3" id="ipress_h_div">
                    <label for="medico_nombre">NOMBRE DE LA IPRESS</label>
                    <input class="form-control" id="ipress_h" readonly>
                  </div>
                </div>
              </div>
            </div>

            <div class="panel panel-info panel-bordered panel-sm">
              <div class="panel-heading">
                <div class="panel-title text-center">
                  <b>INFORMACIÓN DEL PACIENTE</b>
                </div>
              </div>
              <div class="panel-body">
                <div class="row form-group">
                  <div class="col-md-2">
                    <label for="dni_beneficiario_h">DOCUMENTO DE IDENTIDAD</label>
                    <input class="form-control" id="dni_beneficiario_h" readonly>
                  </div>
                  <div class="col-md-2">
                    <label for="tipo_doc_medico_h">APELLIDO PATERNO</label>
                    <input class="form-control" id="paterno_beneficiario_h" readonly>
                  </div>
                  <div class="col-md-2">
                    <label for="medico_materno">APELLIDO MATERNO</label>
                    <input class="form-control" id="materno_beneficiario_h" readonly>
                  </div>
                  <div class="col-md-2">
                    <label for="medico_nombre">NOMBRES</label>
                    <input class="form-control" id="nombre_beneficiario_h" readonly>
                  </div>
                  <div class="col-md-3">
                    <label for="medico_nombre">BENEFICIARIO</label>
                    <input class="form-control" id="tipo_beneficiario_h" readonly>
                  </div>
                </div>
                <div class="row form-group">
                  <div class="col-md-2">
                    <label for="medico_nombre">F. DE NACIMIENTO</label>
                    <input class="form-control" id="fecha_nac_paciente_h" readonly>
                  </div>
                  <div class="col-md-2">
                    <label for="medico_nombre">CIP</label>
                    <input class="form-control" id="cip_beneficiario_h" readonly>
                  </div>
                  <div class="col-md-2">
                    <label for="medico_nombre">GRADO</label>
                    <input class="form-control" id="grado_beneficiario_h" readonly>
                  </div>
                  <div class="col-md-2">
                    <label for="medico_nombre">SITUACION</label>
                    <input class="form-control" id="situacion_paciente_h" readonly>
                  </div>
                  <div class="col-md-2">
                    <label for="medico_nombre">SEXO</label>
                    <input class="form-control" id="sexo_h" readonly>
                  </div>
                  <div class="col-md-2">
                    <label for="medico_nombre">T. SANGRE</label>
                    <input class="form-control" id="sangre_beneficiario_h" readonly>
                  </div>
                </div>
                <div class="row form-group">
                  <div class="col-md-2">
                    <label for="medico_nombre">EDAD</label>
                    <input class="form-control" id="edad_beneficiario_h" readonly>
                  </div>
                </div>
                <div class="row form-group" id="div_acompaniea" style="display: none">
                  <div class="col-md-12" style="margin-bottom: 10px;"><label for="tipo_doc_medico">DATOS DEL ACOMPAÑANTE</label></div>
                  <div class="col-md-12">
                    <hr style="border-top: 2px solid rgb(75, 68, 68); margin-top: 0;">
                  </div>
                  <div class="col-md-2">
                    <label for="medico_nombre">CONDUCIDO POR</label>
                    <input class="form-control" id="conducio_por" readonly>
                  </div>
                  <div class="col-md-4">
                    <label for="medico_nombre">DOC IDENTIDAD Y NOMBRES:</label>
                    <input class="form-control" id="dni_conducido" readonly>
                  </div>
                  <div class="col-md-2">
                    <label for="medico_nombre">TELEFONO</label>
                    <input class="form-control" id="telf_conducido" readonly>
                  </div>
                </div>
                <div class="row form-group" style="display: none">
                  <div class="col-md-10">
                    <label for="medico_nombre">DESCRIPCION</label>
                    <input class="form-control" id="observaciones_benefi" readonly>
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
                <div class="row form-group">
                  <div class="col-md-2">
                    <label for="tipo_doc_medico">DOCUMENTO DE IDENTIDAD</label>
                    <input class="form-control" id="profesional_dni_h" readonly>
                  </div>
                  <div class="col-md-2">
                    <label for="tipo_doc_medico">APELLIDO PATERNO</label>
                    <input class="form-control" id="profesional_paterno_h" readonly>
                  </div>
                  <div class="col-md-2">
                    <label for="medico_materno">APELLIDO MATERNO</label>
                    <input class="form-control" id="profesional_materno_h" readonly>
                  </div>
                  <div class="col-md-2">
                    <label for="medico_nombre">NOMBRES</label>
                    <input class="form-control" id="profesional_nombre_h" readonly>
                  </div>
                  <div class="col-md-2">
                    <label for="especialidad_medico">ESPECIALIDAD</label>
                    <input class="form-control" id="especialidad_medico_h" readonly>
                  </div>
                </div>
                <div class="row form-group">
                  <div class="col-md-2">
                    <label for="profesion_medico">PROFESION</label>
                    <input class="form-control" id="profesion_medico_h" readonly>
                  </div>
                  <div class="col-md-2">
                    <label for="colegiatura">N° COLEGIATURA</label>
                    <input class="form-control" id="colegiatura_h" readonly>
                  </div>
                </div>
              </div>
            </div>

            <div class="panel panel-info panel-bordered panel-sm" id="panel_responsable_atencion">
              <div class="panel-heading">
                <div class="panel-title text-center">
                  <b>INFORMACION DEL RESPONSABLE DE ATENCION</b>
                </div>
              </div>
              <div class="row">
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label for="he_resp_aten_tipo_doc">TIPO DE DOC DE IDENTIDAD</label>
                  <input type="text" class="form-control" id="he_resp_aten_tipo_doc" name="he_resp_aten_tipo_doc">
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label for="he_resp_aten_nro_documento">N° DE DOCUMENTO DE IDENTIDAD</label>
                  <input type="text" class="form-control" id="he_resp_aten_nro_documento" name="he_resp_aten_nro_documento">
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label for="he_resp_aten_apellido_paterno">APELLIDO PATERNO</label>
                  <input type="text" class="form-control" id="he_resp_aten_apellido_paterno" name="he_resp_aten_apellido_paterno">
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label for="he_resp_aten_apellido_materno">APELLIDO MATERNO</label>
                  <input type="text" class="form-control" id="he_resp_aten_apellido_materno" name="he_resp_aten_apellido_materno">
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label for="he_resp_aten_nombres">NOMBRES</label>
                  <input type="text" class="form-control" id="he_resp_aten_nombres" name="he_resp_aten_nombres">
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label for="he_resp_aten_colegiatura">N° COLEGIATURA</label>
                  <input type="text" class="form-control" id="he_resp_aten_colegiatura" name="he_resp_aten_colegiatura">
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label for="he_resp_aten_rne">N° DE RNE</label>
                  <input type="text" class="form-control" id="he_resp_aten_rne" name="he_resp_aten_rne">
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label for="he_resp_aten_profesion">PROFESION</label>
                  <input type="text" class="form-control" id="he_resp_aten_profesion" name="he_resp_aten_profesion">
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 form-group">
                  <label for="he_resp_aten_especialidad">ESPECIALIDAD</label>
                  <input type="text" class="form-control" id="he_resp_aten_especialidad" name="he_resp_aten_especialidad">
                </div>
              </div>
            </div>

            <div class="panel with-nav-tabs panel-default" id="panel_tabs">
              <div class="panel-heading">
                  <ul class="nav nav-tabs" role="tablist" id="tabsRegistrarNotaIngreso">
                      <li role="presentation" class="active">
                          <a href="#fa_tabSignosVitales" aria-controls="fa_tabSignosVitales" role="tab" data-toggle="tab">SIGNOS VITALES</a>
                      </li>
                      <li role="presentation">
                          <a href="#fa_tabAntecedentes" aria-controls="fa_tabAntecedentes" role="tab" data-toggle="tab">ANTECEDENTES</a>
                      </li>
                      <li role="presentation">
                          <a href="#fa_tabCpmsCpt" aria-controls="fa_tabCpmsCpt" role="tab" data-toggle="tab">CPMS/CPT</a>
                      </li>
                      <li role="presentation">
                          <a href="#fa_tabObservaciones" aria-controls="fa_tabObservaciones" role="tab" data-toggle="tab">OBSERVACIONES</a>
                      </li>
                  </ul>
              </div>
              <div class="panel-body">
                  <div class="tab-content">
                      <div role="tabpanel" class="tab-pane active" id="fa_tabSignosVitales">
                          <div class="row">
                              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                  <label class="control-label">PRESION ARTERIAL</label>
                                  <div class="input-group">
                                      <input class="form-control input-sm" name="ha_presion_arterial_1" id="ha_presion_arterial_1" maxlength="10" readonly>
                                      <span class="input-group-addon separate-addon">/</span>
                                      <input class="form-control input-sm" name="ha_presion_arterial_2" id="ha_presion_arterial_2" maxlength="10" readonly>
                                  </div>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                  <label class="control-label" for="ha_pulso_frec_cardiaca">PULSO/FREC. CARDIACA</label>
                                  <input type="text" class="form-control input-sm" name="ha_pulso_frec_cardiaca" id="ha_pulso_frec_cardiaca" maxlength="10" placeholder="50" readonly>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                  <label class="control-label" for="ha_frec_respiratorio">FREC. RESPIRATORIA (/MIN)</label>
                                  <input type="text" class="form-control input-sm" name="ha_frec_respiratorio" id="ha_frec_respiratorio" maxlength="10" placeholder="10" readonly>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                  <label class="control-label" for="ha_fi02">FIO2 (%)</label>
                                  <input type="text" class="form-control input-sm" name="ha_fi02" id="ha_fi02" maxlength="10" placeholder="21" readonly>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                  <label class="control-label" for="ha_sa02">SA02 (%)</label>
                                  <input type="text" class="form-control input-sm" name="ha_sa02" id="ha_sa02" maxlength="10" placeholder="60" readonly>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                  <label class="control-label" for="ha_temperatura">TEMPERATURA (°C)</label>
                                  <input type="text" class="form-control input-sm" name="ha_temperatura" id="ha_temperatura" maxlength="10" placeholder="35" readonly>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                  <label class="control-label" for="ha_talla">TALLA (CM)</label>
                                  <input type="text" class="form-control input-sm" name="ha_talla" id="ha_talla" maxlength="10" placeholder="150" readonly>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                  <label class="control-label" for="ha_peso">PESO (KG)</label>
                                  <input type="text" class="form-control input-sm" name="ha_peso" id="ha_peso" maxlength="10" placeholder="50" readonly>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                  <label class="control-label" for="ha_imc">IMC</label>
                                  <input class="form-control input-sm" name="ha_imc" id="ha_imc" readonly placeholder="0" readonly>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 form-group">
                                  <label class="control-label" for="ha_peso_ideal">PESO IDEAL</label>
                                  <input class="form-control input-sm" name="ha_peso_ideal" id="ha_peso_ideal" readonly placeholder="0" readonly>
                              </div>
                          </div>
                      </div>
                      <div role="tabpanel" class="tab-pane" id="fa_tabAntecedentes">
                          <div class="panel panel-info panel-bordered panel-sm">
                              <div class="panel-body">
                                  <div class="row">
                                      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                          <p class="label-antecedentes">ANTECEDENTES</p>
                                          <p id="ha_antecedente" class="antecedentes-content"></p>
                                      </div>
                                  </div>
                                  <hr>
                                  <div class="row">
                                      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                          <p class="label-antecedentes">ANTECEDENTES FAMILIARES</p>
                                          <p id="ha_antecedente_familiar" class="antecedentes-content"></p>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                  <label class="label-antecedentes">HIPERTENSION ARTERIAL</label>
                                  <p id="ha_id_hipertension" class="antecedentes-content"></p>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                  {{-- @if (intval($enfermeria->id_hipertension) === 1) --}}
                                  <div id="ha_descripcion_hipertension_div" class="form-group">
                                    <label class="control-label" for="ha_descripcion_hipertension">TRATAMIENTO (HIPERTENSION)</label>
                                    <input class="form-control input-sm" name="ha_descripcion_hipertension" id="ha_descripcion_hipertension" readonly>
                                  </div>
                                  {{-- @endif --}}
                              </div>

                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                  <label class="label-antecedentes">DIABETES MELLITUS</label><br>
                                  <p id="ha_id_diabetes_millitus" class="antecedentes-content"></p>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                  {{-- @if (intval($enfermeria->id_diabetes_millitus) === 1) --}}
                                  <div id="ha_descripcion_diabetes_millitus_div" class="form-group">
                                    <label class="control-label" for="ha_descripcion_diabetes_millitus">TRATAMIENTO (DIABETES)</label>
                                    <input class="form-control input-sm" name="ha_descripcion_diabetes_millitus" id="ha_descripcion_diabetes_millitus" readonly>
                                  </div>
                                  {{-- @endif --}}
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                  <label class="control-label">ALERGIA</label><br>
                                  <p id="ha_id_alergia" class="antecedentes-content"></p>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                  {{-- @if (intval($enfermeria->id_alergia) === 1) --}}
                                    <div class="form-group" id="detalle_alergia_div">
                                      <label class="control-label" for="detalle_alergia">DETALLE (ALERGIA)</label>
                                      <input class="form-control input-sm" name="detalle_alergia" id="detalle_alergia" readonly>
                                    </div>
                                  {{-- @endif --}}
                              </div>

                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 form-group">
                                  <label class="control-label">HEMODIALISIS</label><br>
                                  <p id="ha_id_hemodialisis" class="antecedentes-content"></p>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                  {{-- @if (intval($enfermeria->id_hemodialisis) === 1) --}}
                                    <div class="form-group" id="ha_descripcion_hemodialisis_div">
                                      <label class="control-label" for="ha_descripcion_hemodialisis">DETALLE (HEMODIALISIS)</label>
                                      <input class="form-control input-sm" name="ha_descripcion_hemodialisis" id="ha_descripcion_hemodialisis" readonly>
                                    </div>
                                  {{-- @endif --}}
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 form-group">
                                  <label class="control-label" for="cardiologico">CARDIOLOGICO</label>
                                  <ul id="ha_cardiologico">
                                      {{-- @if (isset($enfermeria->cardio_1_descripcion)) <li>{{$enfermeria->cardio_1_descripcion}}</li>@endif
                                      @if (isset($enfermeria->cardio_2_descripcion)) <li>{{$enfermeria->cardio_2_descripcion}}</li>@endif
                                      @if (isset($enfermeria->cardio_3_descripcion)) <li>{{$enfermeria->cardio_3_descripcion}}</li>@endif
                                      @if (isset($enfermeria->cardio_4_descripcion)) <li>{{$enfermeria->cardio_4_descripcion}}</li>@endif --}}
                                  </ul>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 form-group">
                                  <label class="control-label" for="ha_descripcion_observacion">OBSERVACION</label>
                                  <input class="form-control input-sm" name="ha_descripcion_observacion" id="ha_descripcion_observacion" readonly>
                              </div>
                              <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 form-group">
                                  <label class="control-label" for="ha_descripcion_otro">OTROS ANTECEDENTES</label>
                                  <input class="form-control input-sm" name="ha_descripcion_otro" id="ha_descripcion_otro" readonly>
                              </div>
                          </div>
                      </div>
                      <div role="tabpanel" class="tab-pane" id="fa_tabCpmsCpt">
                          {{-- <div class="panel panel-info panel-bordered panel-sm">
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
                          </div> --}}
                          <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-heading">
                                <div class="panel-title text-center">
                                    <b>PROCEDIMIENTOS MEDICOS</b>
                                </div>    
                            </div>
                            <div class="panel-body">
                                <table class="table table-striped" style="font-size: 11px; width: 100%" id="ha_table_procedimientos">
                                    <thead>
                                        <tr>
                                            <th width="10%">CÓDIGO</th>
                                            <th width="50%">PROCEDIMIENTO</th>
                                            <th width="20%">CANTIDAD</th>
                                            <th width="20%">DIAGNOSTICO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      {{-- @if (isset($procedimientos) && count($procedimientos) > 0)
                                          @foreach ($procedimientos as $procedimiento)
                                            <tr>
                                              <td>{{$procedimiento->procedimiento_codigo}}</td>
                                              <td>{{$procedimiento->procedimiento_descripcion}}</td>
                                              <td>{{$procedimiento->prestacion_procedimiento_cantidad}}</td>
                                              <td>{{$procedimiento->diagnostico_nombre}}</td>
                                            </tr>
                                          @endforeach
                                      @endif --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                      </div>
                      <div role="tabpanel" class="tab-pane" id="fa_tabObservaciones">
                          <label class="control-label" for="ha_observacion_enfermeria">DETALLE DE OBSERVACIONES</label>
                          <textarea class="form-control" id="ha_observacion_enfermeria" name="ha_observacion_enfermeria" rows="3" readonly></textarea>
                      </div>
                  </div>
              </div>
            </div>

            <div class="panel panel-info panel-bordered panel-sm" id="div-diagnostic">
              <div class="panel-heading">
                <div class="panel-title text-center">
                  <b>DIAGNOSTICOS</b>
                </div>
              </div>
              <div class="panel-body">
                <div class="form-group">
                  <br>
                  <div class="col-md-12">
                      <div class="table-responsive">
                          <table id="table-diagnostic-history" class="table table-striped" style="width: 100%">
                              <thead>
                                <tr>
                                  <th>CIE10</th>
                                  <th>DESCRIPCIÓN</th>
                                  <th>TIPO</th>
                                  <th>CLASIFICACION</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                          </table>
                      </div>
                  </div>
              </div>
            </div>
        
          </div>
        </div>
      </div>
    </div>
  </div>

  </body>

      
      