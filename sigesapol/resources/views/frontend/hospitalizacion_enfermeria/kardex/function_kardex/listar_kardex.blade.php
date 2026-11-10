<script>
    $(document).ready(function() {
      let data_asegurado_kardex_enfermeria = null;
      let prioridad = null;

      $('#kardex_filter_fecha_inicio').datepicker({
        useCurrent: false,
        format: 'dd/mm/yyyy', 
        todayHighlight: true,
        autoclose: true,  
        orientation: "bottom left" 
      });
  
      $('#kardex_filter_fecha_fin').datepicker({
          useCurrent: false,
          format: 'dd/mm/yyyy', 
          todayHighlight: true,
          autoclose: true,  
          orientation: "bottom left" 
      });

      getTurnos();
      
      function obtenerAsegurado(id_asegurado) {
        $.ajax({
          url: `/getDatosPacienteForEnfermeria/${id_asegurado}`,
          method: "GET",
          dataType: 'json',
          success: function(result) {
            if (result) {
              data_asegurado_kardex_enfermeria = result;
              dataCabecera = result;
              setFormPacienteModalKardex(result)
            }
          },
          error: function(xhr, status, error) {
              console.error('Error en la solicitud:', error);
          }
        });
      }

      function setFormPacienteModalKardex(data) {
        $("#mk_paciente").val(`${data.nro_doc_ident || ""} ${data.nombre || ""} ${data.paterno || ""} ${data.materno || ""}`);
        $("#mk_gs").val(`${data.tipo_sangre || "-"}`);
        $("#mk_alergia").val(``);
        $("#mk_edad").val(`${data.edad_corta || "-"}`);
        $("#mk_sexo").val(`${String(data.sexo).toUpperCase() === "M" ? "MASCULINO" : "FEMENINO" || ""}`);
        $("#mk_hc").val(`${data.nro_historia || "-"}`);
      }

      function getTurnos() {
        $.ajax({
          type: "GET",
          url: "/api/turnos",
          dataType: 'json',
          success: function (turnos) {
            if (turnos && turnos.length > 0) {
              let htmlString = `<option value="" selected>--- seleccionar ---</option>`;
              turnos.forEach((turno, index) => {
                htmlString += `<option value="${turno.id}">${turno.descripcion}</option>`;
              });
              $("#kardex_filter_turno").html(htmlString);
            } 
          },
          error: function (xhr, status, error) {
            console.log("[error] => ", error);
          }
        });
      }

      /*=============================================
      AJAX PARA crear Listar
      =============================================*/ 
      $('body').on('click', '#listarKardex', async function () {
        const id_hospitalizacion = $(this).data('id');
        const id_asegurado = $(this).data('id_asegurado');
        const id_establecimiento = $(this).data('id_establecimiento');
        const id_prestacion = $(this).data('id_prestacion');
        const id_enfermeria = $(this).data('id_enfermeria');

        prioridad = $(this).data('prioridad');

        $('#FormularioListarKardex').trigger("reset");
        $('#tituloFormularioListarKardex').html("Kardex de Enfermería");
        $('#modalListarKardex').modal('show'); 
        
        $('#id_hospitalizacion_kardex').val(id_hospitalizacion);  
        $('#id_hospitalizacion').val(id_hospitalizacion);  
        $('#id_asegurado').val(id_asegurado);  
        $('#id_establecimiento').val(id_establecimiento);  
        $('#id_prestacion').val(id_prestacion);

        obtenerAsegurado(id_asegurado);

        searchListKardex();

        $('[data-toggle="tooltip"]').tooltip({
            delay: { show: 0, hide: 0 }
        });
      });

      $("#buscarKardex").on("click", function () {
        searchListKardex();
      });

      $('#crearKardex').click(async function () {
        $('#id').val('');
        $("#registrarKardex").attr("id","registrarKardex");
        $("#registrarKardex").attr("disabled",false);
        $('#FormularioKardex').trigger("reset");
        $('#tituloFormularioKardex').html("REGISTRAR KARDEX ENFERMERIA");
        $('#modalKardex').modal('show'); 

        setFormModalKardexSave();
        await buildOptionsSelectTurno();
        await buildRowsTableDiagnosticos();
      });

      function anularKardex(kardex_enfermerias_id, id_hospitalizacion) {
        $.ajax({
          type: "PUT",
          url: `/api/kardex-enfermerias/change-estado/${kardex_enfermerias_id}`,
          data: JSON.stringify({estado: 0}),
          contentType: 'application/json',
          dataType: 'json',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function (response) {
            Swal.fire({
              icon: "success",
              title: 'Anulado!',
              text: 'El registro de kardex ha sido anulado.',
              confirmButtonText: 'ENTENDIDO',
            });
            searchListKardex(id_hospitalizacion);
          },
          error: function (error) {
            console.log("[error] => ", error);
          },
        });
      }

      function obtenerTurnos() {
        return new Promise(function (resolve, reject) {
          $.ajax({
            url: `/api/turnos`,
            method: "GET",
            dataType: 'json',
            success: function(result) {
              resolve(result);
            },
            error: function(xhr, status, error) {
              reject(error)
            }
          });
        });
      }

      async function buildOptionsSelectTurno() {
        $('#mkr_turno').empty();
        $('#mkr_turno').append(`<option value="" selected>--- seleccionar opción ---</option>`);
        const turnos = await obtenerTurnos();
        if (turnos && turnos.length > 0) {
          for (const turno of turnos) {
            $('#mkr_turno').append(`<option value="${turno.id}">${turno.descripcion}</option>`);
          }
        }
      }

      async function obtenerDiagnosticosPorPrestacionId(id_prestacion) {
        return new Promise(function (resolve, reject) {
          $.ajax({
            url: `/buscar_datos_prestacion_enfermeria/${id_prestacion}`,
            method: "GET",
            dataType: 'json',
            success: function(result) {
              resolve(result);
            },
            error: function(xhr, status, error) {
              resolve(null)
            }
          });
        });
      }

      async function buildRowsTableDiagnosticos() {
        $("#mkr_table_diagnostivos_atencion tbody").empty();
        const id_prestacion = $("#id_prestacion").val();
        let diagnosticos = [];
        if (id_prestacion && parseInt(id_prestacion) > 0) {
          diagnosticos = await obtenerDiagnosticosPorPrestacionId(id_prestacion);
        }
        if (diagnosticos && diagnosticos.length > 0) {
          // $("#mkr_tratamiento").val(diagnosticos[0].tratamiento_auxiliar || " - ");
          diagnosticos.forEach((element, index) => {
            $('#mkr_table_diagnostivos_atencion tbody').append(`
              <tr>
                <td>${++index}</td>
                <td>${element.codigo_diagnostico}</td>
                <td>${element.nombre_diagnostico}</td>
                <td>${element.tipo_diagnostico}</td>
                <td>${element.clasificacion}</td>
              </tr>
            `);
          });
        } else {
          $("#mkr_tratamiento").val('No disponible, no existe la atencion por el momento.');
          $('#mkr_table_diagnostivos_atencion tbody').append(`
            <tr>
              <td colspan="5">No disponible, no existe la atencion por el momento.</td>
            </tr>
          `);
        }
      }

      $(document).on('click', '[id^="show_detail_kardex-"]', async function() {
        let kardex_enfermerias_id = $(this).data('id');
        $("#id_kardex").val(kardex_enfermerias_id);
        $('#modalShowDetailKardex').modal('show');
      })

      $(document).on('click', '[id^="edit-"]', async function() {
        let kardex_enfermerias_id = $(this).data('id');
        $('#id').val(kardex_enfermerias_id);
        $("#id_kardex").val(kardex_enfermerias_id);
        $('#tituloFormularioKardex').html("EDITAR KARDEX ENFERMERIA");
        $('#modalKardex').modal('show'); 

        setFormModalKardexSave();
        await buildOptionsSelectTurno();
        await buildRowsTableDiagnosticos();
      });

      $(document).on('click', '[id^="anular-"]', async function() {
          const result = await Swal.fire({
              title: '¿Está seguro?',
              text: "¿Desea anular el registro de kardex? Esta acción no se puede deshacer.",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Si, anularlo!'
          });
          if (result.isConfirmed) {
              const kardex_enfermerias_id = $(this).data('id');
              const id_hospitalizacion = $('#id_hospitalizacion').val();
              anularKardex(kardex_enfermerias_id, id_hospitalizacion);
          }
      });

      function setFormModalKardexSave() {
          if (data_asegurado_kardex_enfermeria) {
              $("#mkr_nro_doc_ident").val(`${data_asegurado_kardex_enfermeria.nro_doc_ident || ""}`);
              $("#mkr_asegurado").val(`${data_asegurado_kardex_enfermeria.nombre || ""} ${data_asegurado_kardex_enfermeria.paterno || ""} ${data_asegurado_kardex_enfermeria.materno || ""}`);
              $("#mkr_parentesco").val(`${data_asegurado_kardex_enfermeria.parentesgo || "-"}`);
              $("#mkr_sexo").val(`${String(data_asegurado_kardex_enfermeria.sexo).toUpperCase() === "M" ? "MASCULINO" : "FEMENINO" || ""}`);
              $("#mkr_edad").val(`${data_asegurado_kardex_enfermeria.edad_corta || "-"}`);
              $("#mkr_sangre").val(`${data_asegurado_kardex_enfermeria.tipo_sangre || "-"}`);
              $("#mkr_historia_clinica").val(`${data_asegurado_kardex_enfermeria.nro_historia || "-"}`);
              $("#mkr_alergia").val(`${data_asegurado_kardex_enfermeria.alergia || "-"}`);

              $("#mkr_prioridad_atencion").val(`${prioridad || "-"}`);

              const datetimeMoment = moment();
              $("#mkr_fecha_atencion").val(`${datetimeMoment.format("DD/MM/YYYY") || "-"}`);
              $("#mkr_hora_atencion").val(`${datetimeMoment.format("HH:mm") || "-"}`);
          }
      }
    })
  </script>
  <style>
    .modal .tooltip {
        z-index: 1060; /* Ajusta según sea necesario */
    }
  </style>