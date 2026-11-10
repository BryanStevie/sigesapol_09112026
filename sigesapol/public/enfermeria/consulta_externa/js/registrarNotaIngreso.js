$(document).ready(function () {
  let form = [
    {
      id: 1,
      name: "asegurado_tipo_doc_identidad",
      required: true,
      tr_id: null,
      tab: null,
    },
    { id: 2, name: "asegurado_sexo", required: false, tr_id: null, tab: null },
    {
      id: 3,
      name: "asegurado_paterno",
      required: false,
      tr_id: null,
      tab: null,
    },
    {
      id: 4,
      name: "asegurado_materno",
      required: false,
      tr_id: null,
      tab: null,
    },
    {
      id: 5,
      name: "asegurado_nombre",
      required: false,
      tr_id: null,
      tab: null,
    },
    {
      id: 6,
      name: "asegurado_fecha_nacimiento",
      required: false,
      tr_id: null,
      tab: null,
    },
    {
      id: 7,
      name: "asegurado_tipo_beneficiario",
      required: false,
      tr_id: null,
      tab: null,
    },
    { id: 8, name: "asegurado_grado", required: false, tr_id: null, tab: null },

    {
      id: 9,
      name: "tipo_documento_enfermero",
      required: true,
      tr_id: null,
      tab: null,
    },
    {
      id: 10,
      name: "nro_documento_enfermero",
      required: true,
      tr_id: null,
      tab: null,
    },
    {
      id: 11,
      name: "paterno_enfermero",
      required: false,
      tr_id: null,
      tab: null,
    },
    {
      id: 12,
      name: "materno_enfermero",
      required: false,
      tr_id: null,
      tab: null,
    },
    {
      id: 13,
      name: "nombre_enfermero",
      required: false,
      tr_id: null,
      tab: null,
    },
    {
      id: 14,
      name: "especialidad_enfermero",
      required: false,
      tr_id: null,
      tab: null,
    },
    {
      id: 15,
      name: "profesion_enfermero",
      required: false,
      tr_id: null,
      tab: null,
    },
    {
      id: 16,
      name: "nro_colegiatura_enfermero",
      required: false,
      tr_id: null,
      tab: null,
    },

    {
      id: 17,
      name: "medico_tipo_documento",
      required: false,
      tr_id: null,
      tab: null,
    },
    {
      id: 18,
      name: "medico_nro_doc_ident",
      required: false,
      tr_id: null,
      tab: null,
    },
    { id: 19, name: "medico_paterno", required: false, tr_id: null, tab: null },
    { id: 20, name: "medico_materno", required: false, tr_id: null, tab: null },
    { id: 21, name: "medico_nombre", required: false, tr_id: null, tab: null },
    {
      id: 22,
      name: "medico_colegiatura",
      required: false,
      tr_id: null,
      tab: null,
    },
    { id: 23, name: "medico_rne", required: false, tr_id: null, tab: null },
    {
      id: 24,
      name: "medico_profesion",
      required: false,
      tr_id: null,
      tab: null,
    },
    {
      id: 25,
      name: "medico_especializacion",
      required: false,
      tr_id: null,
      tab: null,
    },

    // SIGNOS VITALES
    {
      id: 26,
      name: "presion_arterial_1",
      required: false,
      tr_id: null,
      tab: "tabSignosVitales",
    },
    {
      id: 26,
      name: "presion_arterial_2",
      required: false,
      tr_id: null,
      tab: "tabSignosVitales",
    },
    {
      id: 27,
      name: "pulso_frec_cardiaca",
      required: false,
      tr_id: null,
      tab: "tabSignosVitales",
    },
    {
      id: 28,
      name: "frec_respiratorio",
      required: false,
      tr_id: null,
      tab: "tabSignosVitales",
    },
    {
      id: 29,
      name: "fi02",
      required: false,
      tr_id: null,
      tab: "tabSignosVitales",
    },
    {
      id: 30,
      name: "sa02",
      required: false,
      tr_id: null,
      tab: "tabSignosVitales",
    },
    {
      id: 31,
      name: "temperatura",
      required: false,
      tr_id: null,
      tab: "tabSignosVitales",
    },
    {
      id: 32,
      name: "talla",
      required: false,
      tr_id: null,
      tab: "tabSignosVitales",
    },
    {
      id: 33,
      name: "peso",
      required: false,
      tr_id: null,
      tab: "tabSignosVitales",
    },
    {
      id: 34,
      name: "imc",
      required: false,
      tr_id: null,
      tab: "tabSignosVitales",
    },
    {
      id: 35,
      name: "peso_ideal",
      required: false,
      tr_id: null,
      tab: "tabSignosVitales",
    },

    // ANTECEDENTES
    {
      id: 36,
      name: "tratamiento_hipertension",
      required: false,
      tab: "tabAntecedentes",
    },
    {
      id: 37,
      name: "tratamiento_diabetes",
      required: false,
      tab: "tabAntecedentes",
    },
    {
      id: 38,
      name: "detalle_alergia",
      required: false,
      tab: "tabAntecedentes",
    },
    {
      id: 39,
      name: "detalle_hemodialisis",
      required: false,
      tab: "tabAntecedentes",
    },
    { id: 40, name: "cardiologico", required: false, tab: "tabAntecedentes" },
    { id: 41, name: "observacion", required: false, tab: "tabAntecedentes" },
    {
      id: 42,
      name: "otros_antecedentes",
      required: false,
      tab: "tabAntecedentes",
    },

    {
      id: 42,
      name: "fecha_atencion_enfermeria",
      required: true,
      tr_id: null,
      tab: null,
    },
    {
      id: 42,
      name: "hora_atencion_enfermeria",
      required: true,
      tr_id: null,
      tab: null,
    },
  ];

  let diagnosticosArray = [];

  $("#id_servicio").select2({ width: "100%" });
  $("#id_establecimiento").select2({ width: "100%" });
  $("#cardiologico").select2({ maximumSelectionLength: 4 });

  $("#fecha_ultima_atencion")
    .datepicker({
      useCurrent: false,
      format: "dd/mm/yyyy",
      todayHighlight: true,
      autoclose: true,
      orientation: "bottom left",
    })
    .datepicker("setDate", new Date());

  $("#fecha_atencion_enfermeria")
    .datepicker({
      useCurrent: false,
      format: "dd-mm-yyyy",
      todayHighlight: true,
      autoclose: true,
      orientation: "bottom left",
    })
    .datepicker("setDate", new Date());

  $("#id_servicio").change(function () {
    const nro_doc_ident = $("#nro_doc_ident").val();
    if ($(this).val() && nro_doc_ident && nro_doc_ident.length >= 7) {
      $("#btnBuscarAsegurado").prop("disabled", false);
      $("#btnBuscarAsegurado").tooltip("destroy");
    } else {
      $("#btnBuscarAsegurado").prop("disabled", true);
      $("#btnBuscarAsegurado")
        .attr(
          "title",
          "<div><span>- Seleccionar el servicio</span><br><span>- Ingrese el número de documento</span></div>",
        )
        .tooltip("fixTitle");
    }
  });

  $("#id_tipo_doc_ident")
    .select2({ width: "100%" })
    .on("change", function (e) {
      var selectedValue = $(this).val();
      maxlength_doc_usu(selectedValue);
    });

  $("#nro_doc_ident").on("keypress", function (event) {
    var charCode = event.which ? event.which : event.keyCode;
    if (charCode < 48 || charCode > 57) {
      event.preventDefault();
    }
  });

  $("#nro_doc_ident").on("input", function () {
    const id_servicio = $("#id_servicio").val();
    if ($(this).val() && $(this).val().length >= 7 && id_servicio) {
      $("#btnBuscarAsegurado").prop("disabled", false);
      $("#btnBuscarAsegurado").tooltip("destroy");
    } else {
      $("#btnBuscarAsegurado").prop("disabled", true);
      $("#btnBuscarAsegurado")
        .attr(
          "title",
          "<div><span>- Seleccionar el servicio</span><br><span>- Ingrese el número de documento</span></div>",
        )
        .tooltip("fixTitle");
    }
  });

  $("#nro_doc_ident").keydown(async function (e) {
    if (e.keyCode === 13) {
      e.preventDefault();
      // await onClickBtnBuscarAsegurado();
      await obtenerListadoAtenciones();
    }
  });

  $("#temperatura").on("input", function (event) {
    const value = $(this).val();
    $(this).siblings("span").remove();
    $(this).css("border", "");
    if (value) {
      const result = validateValueTemperature(value);
      $(this).css("border", `2px solid ${result.borderColor}`);
      $(this).after(
        `<span style="color: ${result.borderColor}; font-size: 11px">${result.message}</span>`,
      );
    }
  });

  $("#sa02").on("input", function (event) {
    const value = $(this).val();
    $(this).siblings("span").remove();
    $(this).css("border", "");
    if (value) {
      const result = validateValueSaO2(value);
      $(this).css("border", `2px solid ${result.borderColor}`);
      $(this).after(
        `<span style="color: ${result.borderColor}; font-size: 11px">${result.message}</span>`,
      );
    }
  });

  $("#modalListadoCitas").on("hidden.bs.modal", function () {
    if ($.fn.DataTable.isDataTable("#tableAtencionesCitas")) {
      $("#tableAtencionesCitas").DataTable().clear().draw();
    }
    if ($.fn.DataTable.isDataTable("#tableAtencionesEnfermeria")) {
      $("#tableAtencionesEnfermeria").DataTable().clear().draw();
    }
    $("#panelListadoAtencionesEnfermeria").slideUp();
    $("#panelListadoCitas").slideDown();
  });

  main();

  async function main() {
    const id_establecimiento = $("#id_establecimiento").val();
    if (id_establecimiento) {
      await setOptionsServiceByEstablecimiento(id_establecimiento);
    }
  }

  async function setOptionsServiceByEstablecimiento(id_establecimiento) {
    try {
      Swal.fire({
        title: "Cargando consultorios...",
        text: "Por favor espere",
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
      });
      const consultorios =
        await getConsultoriosByEstablecimiento(id_establecimiento);
      if (consultorios && consultorios.data && consultorios.data.length > 0) {
        const $select = $("#id_servicio");
        $select.empty().append('<option value="">--Seleccionar--</option>');
        consultorios.data.forEach((c) => {
          $select.append(`<option value="${c.id}">${c.nombre}</option>`);
        });
        $select.val("").trigger("change");
      }
      Swal.close();
    } catch (error) {
      Swal.close();
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Ocurrió un error al cargar los consultorios",
      });
    }
  }

  function validateValueTemperature(value) {
    let result = { borderColor: "	#28a745", message: "Temperatura Normal" };
    switch (true) {
      case parseFloat(value) >= 35.0 && parseFloat(value) < 36.0:
        result = { borderColor: "#007bff", message: "Hipotermia" };
        break;
      case parseFloat(value) >= 36.0 && parseFloat(value) < 37.5:
        result = { borderColor: "	#28a745", message: "Temperatura Normal" };
        break;
      case parseFloat(value) >= 37.5 && parseFloat(value) < 39.5:
        result = { borderColor: "#ffc107", message: "Fiebre" };
        break;
      case parseFloat(value) >= 39.5 && parseFloat(value) < 41.0:
        result = { borderColor: "#fd7e14", message: "Fiebre Alta" };
        break;
      case parseFloat(value) >= 41.0 && parseFloat(value) <= 45.0:
        result = { borderColor: "#dc3545", message: "Hipertermia" };
        break;
      default:
        result = { borderColor: "#28a745", message: "Hipotermia" };
        break;
    }
    return result;
  }

  function validateValueSaO2(value) {
    let result = { borderColor: "	#28a745", message: "Normal" };
    switch (true) {
      case parseFloat(value) >= 95 && parseFloat(value) <= 100:
        result = { borderColor: "#28a745", message: "Normal" };
        break;
      case parseFloat(value) >= 91 && parseFloat(value) <= 94:
        result = { borderColor: "	#ffc107", message: "Hipoxia leve" };
        break;
      case parseFloat(value) >= 87 && parseFloat(value) <= 90:
        result = { borderColor: "#fd7e14", message: "Hipoxia moderada" };
        break;
      case parseFloat(value) >= 60 && parseFloat(value) <= 86:
        result = { borderColor: "#dc3545", message: "Hipoxia severa" };
        break;
      default:
        result = { borderColor: "#28a745", message: "Normal" };
        break;
    }
    return result;
  }

  (restrictDecimalInput("presion_arterial_1"),
    restrictDecimalInput("presion_arterial_2"),
    restrictDecimalInput("pulso_frec_cardiaca"),
    restrictDecimalInput("rec_respiratorio"),
    restrictDecimalInput("fi02"),
    restrictDecimalInput("sa02"),
    restrictDecimalInput("temperatura"),
    restrictDecimalInput("talla"),
    restrictDecimalInput("pesa"),
    $("#btnBuscarAsegurado").on("click", async function () {
      // await onClickBtnBuscarAsegurado();
      await obtenerListadoAtenciones();
    }));

  $("#btnSubmit").on("click", function () {
    onSubmit();
  });

  $("input[type=radio][name=hipertensionArterial]").change(function () {
    let selectedValue = $(this).val();
    $("#tratamiento_hipertension").val("");
    clearInputError("tratamiento_hipertension");
    if (parseInt(selectedValue) === 1) {
      $("label[for=tratamiento_hipertension]").show();
      $("#tratamiento_hipertension").show();
      updateRequiredField("tratamiento_hipertension", true);
    } else {
      $("label[for=tratamiento_hipertension]").hide();
      $("#tratamiento_hipertension").hide();
      updateRequiredField("tratamiento_hipertension", false);
    }
  });

  $("input[type=radio][name=diabetes_mellitus]").change(function () {
    let selectedValue = $(this).val();
    $("#tratamiento_diabetes").val("");
    clearInputError("tratamiento_diabetes");
    if (parseInt(selectedValue) === 1) {
      $("label[for=tratamiento_diabetes]").show();
      $("#tratamiento_diabetes").show();
      updateRequiredField("tratamiento_diabetes", true);
    } else {
      $("label[for=tratamiento_diabetes]").hide();
      $("#tratamiento_diabetes").hide();
      updateRequiredField("tratamiento_diabetes", false);
    }
  });

  $("input[type=radio][name=alergia]").change(function () {
    let selectedValue = $(this).val();
    $("#detalle_alergia").val("");
    clearInputError("detalle_alergia");
    if (parseInt(selectedValue) === 1) {
      $("label[for=detalle_alergia]").show();
      $("#detalle_alergia").show();
      updateRequiredField("detalle_alergia", true);
    } else {
      $("label[for=detalle_alergia]").hide();
      $("#detalle_alergia").hide();
      updateRequiredField("detalle_alergia", false);
    }
  });

  $("input[type=radio][name=hemodialisis]").change(function () {
    let selectedValue = $(this).val();
    $("#detalle_hemodialisis").val("");
    clearInputError("detalle_hemodialisis");
    if (parseInt(selectedValue) === 1) {
      $("label[for=detalle_hemodialisis]").show();
      $("#detalle_hemodialisis").show();
      updateRequiredField("detalle_hemodialisis", true);
    } else {
      $("label[for=detalle_hemodialisis]").hide();
      $("#detalle_hemodialisis").hide();
      updateRequiredField("detalle_hemodialisis", false);
    }
  });

  $("#btnAddProcedure").on("click", function () {
    onClickAddRowTableProcedimientos();
  });

  $("#talla").on("input", function () {
    let talla = $(this).val();
    let peso = $("#peso").val();
    if (talla) {
      const edad = $("#asegurado_edad").val();
      const sexo = $("#asegurado_sexo").val();
      let pesoIdeal = calculatePesoIdeal(talla, edad, sexo);
      $("#peso_ideal").val(pesoIdeal);
    } else {
      $("#peso_ideal").val("");
    }

    if (peso && talla) {
      let imc = calculateIMC(peso, talla);
      $("#imc").val(imc);
    } else {
      $("#imc").val("");
    }
    evaluationPeso();
  });

  $("#peso").on("input", function () {
    let peso = $(this).val();
    let talla = $("#talla").val();
    if (peso && talla) {
      let imc = calculateIMC(peso, talla);
      $("#imc").val(imc);
    } else {
      $("#imc").val("");
    }
    evaluationPeso();
  });

  $("#id_establecimiento").on("change", async function () {
    const establecimientoId = $(this).val();
    await setOptionsServiceByEstablecimiento(establecimientoId);
  });

  handlerActivesEventsTab();

  function handlerActivesEventsTab() {
    $('a[href="#tabHistorialAtenciones"]').one("shown.bs.tab", function (e) {
      const nro_doc_ident_hidden = $("#nro_doc_ident_hidden").val();
      getHistorialAtencionMedica(nro_doc_ident_hidden);
    });

    $('a[href="#tabHistorialLaboratorio"]').one("shown.bs.tab", function (e) {
      const nro_doc_ident_hidden = $("#nro_doc_ident_hidden").val();
      getHistorialLaboratorio(nro_doc_ident_hidden);
    });

    $('a[href="#tabHistorialRadiologia"]').one("shown.bs.tab", function (e) {
      const nro_doc_ident_hidden = $("#nro_doc_ident_hidden").val();
      getHistorialRadiologia(nro_doc_ident_hidden);
    });

    $('a[href="#tabHistorialnterconsulta"]').one("shown.bs.tab", function (e) {
      const nro_doc_ident_hidden = $("#nro_doc_ident_hidden").val();
      getHistorialInterconsulta(nro_doc_ident_hidden);
    });

    $('a[href="#tabHistorialReferencia"]').one("shown.bs.tab", function (e) {
      const nro_doc_ident_hidden = $("#nro_doc_ident_hidden").val();
      getHistorialReferenciaContrareferencia(nro_doc_ident_hidden);
    });

    $('a[href="#tabHistorialEnfermeria"]').one("shown.bs.tab", function (e) {
      const asegurado_id = $("#asegurado_id").val();
      getHistorialEnfermeria(asegurado_id);
    });
  }

  addAsteriskToLabel();

  validateInputRange(
    "pulso_frec_cardiaca",
    50,
    150,
    "El valor de la Frecuencia Cardiaca debe ser entre 50 a 150",
  );
  validateInputRange(
    "frec_respiratorio",
    10,
    35,
    "El valor de la Frec. Respiratoria debe estar entre 10 a 35",
  );
  validateInputRange(
    "fi02",
    21,
    100,
    "El valor de la fIO2 debe estar entre 21 a 100 (%)",
  );
  validateInputRange(
    "sa02",
    60,
    100,
    "El valor de la Saturación debe ser entre 60 a 100 (%)",
  );
  validateInputRange(
    "temperatura",
    35,
    45,
    "La temperatura debe estar entre 35.00 a 45.00 ( °C )",
  );
  validateInputRange(
    "talla",
    30,
    200,
    "El valor de la talla debe ser entre 30 a 200 ( cm )",
  );

  lista_parametros(
    "CARDIOLOGICO_NOT_ENF",
    "cardiologico",
    "combo_multiple",
    "TABLA_PARAMETROS",
    0,
  );

  // async function onClickBtnBuscarAsegurado(prestacionId) {
  async function onClickBtnBuscarAsegurado(citaId) {
    try {
      Swal.fire({
        html: "<h5>OBTENIENDO DATOS DE CITA MEDICA...</h5>",
        onOpen: () => {
          Swal.showLoading();
        },
      });
      $("#btnSubmit").attr("disabled", true);
      $("#btnSubmit")
        .attr(
          "title",
          "Realize la busqueda de un cita para guardar la nota de enfermeria",
        )
        .tooltip("fixTitle");

      // const prestacion = await  obtenerBeneficiario();
      // const prestacion = await obtenerAtencionByPrestacionId(prestacionId);
      const prestacion = await obtenerAtencionByCitaId(citaId);
      // if (prestacion && prestacion.data && prestacion.data.prestacion_id) {
      if (prestacion && prestacion.data && prestacion.data.cita_id) {
        const cita_id = prestacion.data.cita_id;

        $("#formDataPaciente").slideDown();
        $("#formDataMedicoAtencion").slideDown();
        $("#tableReceta").slideDown();
        $("#sectionTabs").slideDown();

        $("#cita_id").val(cita_id);
        $("#btnSubmit").attr("disabled", false);
        $("#btnSubmit").tooltip("destroy");

        setValuesFormPacient(prestacion.data);

        const prestacion_id = prestacion.data.prestacion_id;
        if (prestacion_id) {
          $("#id_prestacion").val(prestacion_id);
          $('#tabs li:has(a[href="#tabCpmsCpt"])').removeClass("disabled-tab");
          const productos = await obtenerProductosReceta(prestacion_id);
          if (productos) {
            buildTableRecetas(productos);
          }
          const diagnosticos =
            await getDiagnosticosByPrestacionId(prestacion_id);
          if (diagnosticos && diagnosticos.length > 0) {
            diagnosticosArray = diagnosticos;
            buildTableDiagnosticos(diagnosticos);
          }
        }

        const tieneFirma =
          prestacion.data.fecha_firma_prestacion !== null ? true : false;
        $("#firmaPrestacion").val(tieneFirma === true ? 1 : 0);
        /* if (!tieneFirma) {
          $('a[href="#tabAntecedentes"]').removeAttr("href");
          $('a[href="#tabCpmsCpt"]').removeAttr("href");
          $('a[href="#tabObservaciones"]').removeAttr("href");
          $('a[href="#tabAntecedentes"]')
            .addClass("disabled")
            .attr("tabindex", "-1")
            .css("pointer-events", "none");
          $('a[href="#tabCpmsCpt"]')
            .addClass("disabled")
            .attr("tabindex", "-1")
            .css("pointer-events", "none");
          $('a[href="#tabObservaciones"]')
            .addClass("disabled")
            .attr("tabindex", "-1")
            .css("pointer-events", "none");
        } */
        if (!tieneFirma) {
          const tabs = ["#tabAntecedentes", "#tabCpmsCpt", "#tabObservaciones"];

          tabs.forEach(function (tab) {
            $('a[href="' + tab + '"]')
              .addClass("disabled")
              .attr("tabindex", "-1")
              .on("show.bs.tab", function (e) {
                e.preventDefault();
                return false;
              });
          });
        }

        Swal.close();
      } else {
        handlerNotSearchAsegurado();
        Swal.fire({
          icon: "warning",
          title: "NOTA DE INGRESO DE ENFERMERÍA",
          html: `<p style="font-size: 13px">El paciente con número de documento ${$("#nro_doc_ident").val()} no tiene citas registradas</p>`,
          confirmButtonText: "REVISAR BUSQUEDA",
        });
      }
    } catch (error) {
      const message =
        error.responseJSON && error.responseJSON.message
          ? error.responseJSON.message
          : "Ocurrio un error, intentelo en unos minutos";
      const code =
        error.responseJSON && error.responseJSON.code
          ? parseInt(error.responseJSON.code)
          : 500;
      Swal.fire({
        icon: code === 404 || code === 409 ? "warning" : "error",
        title: "NOTA DE INGRESO DE ENFERMERÍA",
        html: `<p style="font-size: 13px">${message}</p>`,
        confirmButtonText: "REVISAR BUSQUEDA",
      });
      handlerNotSearchAsegurado();
    } finally {
      Swal.hideLoading();
    }
  }

  $('a[href="#tabAntecedentes"]').on('click', function (e) {
     if ($(this).hasClass('disabled')) {
      e.preventDefault();
      Swal.fire({
            icon: 'warning',
            title: 'Tab deshabilitado',
            text: 'La prestacion medica no cuenta con firma.',
            timer: 3000,              // 3 segundos
            timerProgressBar: true,
            showConfirmButton: false  // No muestra botón OK
        });
      return; // Detiene la ejecución
    }
  });
  $('a[href="#tabCpmsCpt"]').on('click', function (e) {
     if ($(this).hasClass('disabled')) {
      e.preventDefault();
      Swal.fire({
            icon: 'warning',
            title: 'Tab deshabilitado',
            text: 'La prestacion medica no cuenta con firma.',
            timer: 3000,              // 3 segundos
            timerProgressBar: true,
            showConfirmButton: false  // No muestra botón OK
        });
      return; // Detiene la ejecución
    }
  });
  $('a[href="#tabObservaciones"]').on('click', function (e) {
     if ($(this).hasClass('disabled')) {
      e.preventDefault();
      Swal.fire({
            icon: 'warning',
            title: 'Tab deshabilitado',
            text: 'La prestacion medica no cuenta con firma.',
            timer: 3000,              // 3 segundos
            timerProgressBar: true,
            showConfirmButton: false  // No muestra botón OK
        });
      return; // Detiene la ejecución
    }
  });

  function handlerNotSearchAsegurado() {
    $("#formDataPaciente").slideUp();
    $("#formDataMedicoAtencion").slideUp();
    $("#tableReceta").slideUp();
    $("#sectionTabs").slideUp();
    clearForm();
  }

  function clearForm() {
    $("#asegurado_edad").val("");
    $("#id_prestacion").val("");
    $("#asegurado_sexo_abreviatura").val("");
    $("#establecimiento_nivel").val("");
    form.forEach((element) => {
      $(`#${element.name}`).val("");
    });
    const medico = window.medico;
    if (medico && JSON.stringify(medico) !== "{}") {
      $("#tipo_documento_enfermero").val(medico.tipo_documento_nombre);
      $("#nro_documento_enfermero").val(medico.dni);
      $("#paterno_enfermero").val(medico.paterno);
      $("#materno_enfermero").val(medico.materno);
      $("#nombre_enfermero").val(medico.nombre);
      $("#especialidad_enfermero").val(medico.especializacion);
      $("#profesion_enfermero").val(medico.profesion);
      $("#nro_colegiatura_enfermero").val(medico.colegiatura);
    }
  }

  function addAsteriskToLabel() {
    for (const control of form) {
      if (control.required) {
        const label = $('label[for="' + control.name + '"]');
        const labelText = label.text();
        if (!labelText.includes("*")) {
          label.html(labelText + ' <span style="color:red;">*</span>');
        }
      }
    }
  }

  function maxlength_doc_usu(selectId) {
    $("#nro_doc_ident").val("");
    if (parseInt(selectId) === 1) {
      $("#nro_doc_ident").attr("maxlength", "8");
    } else {
      $("#nro_doc_ident").attr("maxlength", "12");
    }
    $("#nro_doc_ident").focus();
  }

  function obtenerBeneficiario() {
    return new Promise((resolve, reject) => {
      const id_tipo_doc_ident = $("#id_tipo_doc_ident").val();
      const nro_doc_ident = $("#nro_doc_ident").val();
      const id_servicio = $("#id_servicio").val();
      const id_establecimiento = $("#id_establecimiento").val();
      // const fecha_atencion = $("#fecha_ultima_atencion").val();

      const params = {
        id_tipo_doc_ident: id_tipo_doc_ident, // 1
        nro_doc_ident: nro_doc_ident, // 10203167
        consultorio_id: id_servicio, // 13
        id_establecimiento: id_establecimiento, // 76
        // fecha_atencion: moment(fecha_atencion, "DD/MM/YYYY").format("YYYY-MM-DD"),
      };
      const queryString = objectToQueryString(params);
      $.ajax({
        type: "GET",
        url: `/api/citas/buscar-ultima-atencion-asegurado?${queryString}`,
        dataType: "json",
        success: function (response) {
          resolve(response);
        },
        error: function (err) {
          reject(err);
        },
      });
    });
  }

  function obtenerProductosReceta(prestacionId) {
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "GET",
        url: `/api/receta-vale/prestacion/${prestacionId}`,
        dataType: "json",
        success: function (response) {
          resolve(response);
        },
        error: function (err) {
          reject(err);
        },
      });
    });
  }

  function objectToQueryString(obj) {
    const params = new URLSearchParams(obj);
    return params.toString();
  }

  function setValuesFormPacient(data) {
    if (data && data.prestacion_fecha_atencion) {
      $("#fecha_ultima_atencion").val(
        moment(data.prestacion_fecha_atencion, "YYYY-MM-DD HH:mm:ss").format(
          "DD-MM-YYYY",
        ),
      );
    }
    $("#nro_historia_clinica").val(data.numero_historia || "");

    $("#asegurado_tipo_doc_identidad").val(
      data.asegurado_tipo_doc_identidad || "",
    );
    $("#asegurado_sexo").val(
      String(data.asegurado_sexo).toUpperCase() === "F"
        ? "FEMENINO"
        : "MASCULINO" || "",
    );
    $("#asegurado_sexo_abreviatura").val(data.asegurado_sexo || "M");
    $("#asegurado_paterno").val(data.asegurado_paterno || "");
    $("#asegurado_materno").val(data.asegurado_materno || "");
    $("#asegurado_nombre").val(data.asegurado_nombre || "");
    $("#asegurado_fecha_nacimiento").val(
      data.asegurado_fecha_nacimiento || "-",
    );
    $("#asegurado_tipo_beneficiario").val(data.tipo_beneficiario || "-");
    $("#asegurado_grado").val(data.asegurado_grado || "-");

    $("#medico_tipo_documento").val(data.medico_tipo_documento || "");
    $("#medico_nro_doc_ident").val(data.medico_nro_doc_ident || "");
    $("#medico_paterno").val(data.medico_paterno || "");
    $("#medico_materno").val(data.medico_materno || "");
    $("#medico_nombre").val(data.medico_nombre || "");
    $("#medico_colegiatura").val(data.medico_colegiatura || "");
    $("#medico_rne").val(data.medico_rne || "");
    $("#medico_profesion").val(data.medico_profesion || "");
    $("#medico_especializacion").val(data.medico_especializacion || "");

    $("#antecedente").text(data.prestacion_antecedente || "-");
    $("#antecedente_familiar").text(
      data.prestacion_antecedente_familiar || "-",
    );

    $("#establecimiento_nivel").val(data.establecimiento_nivel || 1);

    $("#asegurado_edad").val(data.asegurado_edad || 0);

    $("#cita_id").val(data.cita_id || "");

    if (data && data.antecente) {
      $(
        `#${parseInt(data.antecente.id_hipertension) === 1 ? "hipertensionArterial_01" : "hipertensionArterial_02"} `,
      )
        .prop("checked", true)
        .trigger("change");
      $("#tratamiento_hipertension").val(
        data.antecente.descripcion_hipertension || "",
      );

      $(
        `#${parseInt(data.antecente.id_diabetes_millitus) === 1 ? "diabetes_mellitus_01" : "diabetes_mellitus_02"} `,
      )
        .prop("checked", true)
        .trigger("change");
      $("#tratamiento_diabetes").val(
        data.antecente.descripcion_diabetes_millitus || "",
      );

      $(
        `#${parseInt(data.antecente.id_alergia) === 1 ? "alergia_01" : "alergia_02"} `,
      )
        .prop("checked", true)
        .trigger("change");
      $("#detalle_alergia").val(data.antecente.descripcion_alergia || "");

      $(
        `#${parseInt(data.antecente.id_hemodialisis) === 1 ? "hemodialisis_01" : "hemodialisis_02"} `,
      )
        .prop("checked", true)
        .trigger("change");
      $("#detalle_hemodialisis").val(
        data.antecente.descripcion_hemodialisis || "",
      );

      cardiologiaArray = [];
      for (let i = 1; i < 5; i++) {
        if (data.antecente[`cardio_parametro_${i}`]) {
          cardiologiaArray.push(data.antecente[`cardio_parametro_${i}`]);
        }
      }
      $("#cardiologico").val(cardiologiaArray).trigger("change");

      $("#observacion").val(data.antecente.descripcion_observacion || "");
      $("#otros_antecedentes").val(data.antecente.descripcion_otro || "");
    }
  }

  function getDiagnosticosByPrestacionId(prestacionId) {
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "GET",
        url: `/buscar_datos_prestacion_enfermeria/${prestacionId}`,
        dataType: "json",
        success: function (response) {
          resolve(response);
        },
        error: function (error) {
          reject(error);
        },
      });
    });
  }

  function buildTableDiagnosticos(data) {
    $("#tableDiagnosticos").DataTable({
      searching: false,
      paging: false,
      info: false,
      ordering: false,
      destroy: true,
      data: data,
      columns: [
        { data: "codigo_diagnostico" },
        { data: "nombre_diagnostico" },
        { data: "tipo_diagnostico" },
        { data: "clasificacion" },
      ],
    });
  }

  function buildTableRecetas(data) {
    $("#tableRecetas").DataTable({
      searching: false,
      paging: false,
      info: false,
      ordering: false,
      destroy: true,
      data: data,
      columns: [
        { data: "receta_vale_id" },
        { data: "producto_nombre" },
        { data: "unidad_abreviatura" },
        { data: "cantidad_prescrita" },
        { data: "tiempo_periodo" },
        { data: "via_administracion_nombre" },
        { data: "posologia" },
      ],
    });
  }

  function onClickAddRowTableProcedimientos() {
    const lastRowValid = validateLastRowTable("tableProcedures");
    if (!lastRowValid) {
      Swal.fire({
        icon: "warning",
        title: "<p>NOTA DE INGRESO DE ENFERMERÍA</p>",
        html: '<p class="descripcion-alert">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UN NUEVO PROCEDIMIENTO</p>',
      });
      return null;
    }
    const indexRow = $("#tableProcedures tbody tr").length + 1;
    // let options = "<option value=''>--Seleccionar--</option>";
    let options = "";
    diagnosticosArray.forEach((opcion) => {
      options += `<option value="${opcion.diagnostico_id}">${opcion.nombre_diagnostico}</option>`;
    });

    const newRow = `
      <tr id="tr_procedimientos_${indexRow}">
          <td><input type="text" name="procedimiento_nombre" id="procedimiento_nombre${indexRow}" class="form-control input-sm"></td>
          <td><input type="number" name="procedimiento_cantidad" id="procedimiento_cantidad${indexRow}" class="form-control input-sm" value="1"></td>
          <td>
            <select class="form-control input-sm" name="procedimiento_diagnostico" id="procedimiento_diagnostico${indexRow}">${options}</select>
          </td>
          <td><button id="btnRemoveRowProcedure" type="button" style="margin-left: 10px" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></td>
          <td><input type="hidden" name="id_procedimiento" id="procedure_id_${indexRow}"></td>
      </tr>`;

    $("#tableProcedures tbody").append(newRow);
    $("#tableProceduresHeader")
      .removeClass("table-error danger")
      .nextAll(".label-help")
      .remove();

    const sexo =
      String($("#asegurado_sexo_abreviatura").val()).toUpperCase() === "M"
        ? 1
        : 2;
    const establecimiento_nivel = $("#establecimiento_nivel").val() || "76";

    $(`#procedimiento_nombre${indexRow}`).autocomplete({
      minLength: 1,
      delay: 400,
      source: function (request, response) {
        $.ajax({
          url: `/obtener_procedimiento_convenio/${request.term}/${establecimiento_nivel}/${sexo}/${1}`,
          type: "GET",
          dataType: "json",
          success: function (data) {
            response(
              data.map(function (item) {
                return { id: item.id, label: item.label };
              }),
            );
          },
          error: function () {
            response([]);
          },
        });
      },
      select: function (event, ui) {
        let idItem = ui.item.id;
        const isRepeated = validateRepeatedProcedureId(idItem);
        if (!isRepeated) {
          $(this).val(ui.item.label);
          $(`#procedure_id_${indexRow}`).val(idItem);
          validateDuplicateProcedure(idItem);
        } else {
          Swal.fire({
            icon: "warning",
            title: "<p>NOTA DE INGRESO DE ENFERMERÍA</p>",
            html: '<p class="descripcion-alert">EL PROCEDIMIENTO YA FUE AGREGADO, NO SE PERMITEN PROCEDIMIENTOS REPETIDOS</p>',
          });
          $(this).val("");
          $(`#procedure_id_${indexRow}`).val("");
          $(`#procedimiento_nombre${indexRow}`).val("");
          event.preventDefault();
        }
      },
    });

    $(`#procedimiento_nombre${indexRow}`).on("input", function () {
      $(`#procedure_id_${indexRow}`).val("");
    });

    $(`#procedimiento_nombre${indexRow}`).blur(function () {
      const id_procedimiento = $(`#procedure_id_${indexRow}`).val();
      clearInputError($(this).attr("id"));
      if (!id_procedimiento) {
        markInputError(
          $(this).attr("id"),
          "espere los resultados y seleccione un procedimiento",
        );
      }
    });

    $("#tableProcedures").on("click", "#btnRemoveRowProcedure", function () {
      const row = $(this).closest("tr");
      const rowId = row.attr("id");
      form = form.filter((obj) => String(obj.tr_id) !== String(rowId));
      row.remove();
    });

    form.push({
      id: indexRow,
      name: `procedimiento_nombre${indexRow}`,
      required: true,
      tr_id: `tr_procedimientos_${indexRow}`,
      tab: "tabCpmsCpt",
    });
    form.push({
      id: indexRow,
      name: `procedimiento_cantidad${indexRow}`,
      required: true,
      tr_id: `tr_procedimientos_${indexRow}`,
      tab: "tabCpmsCpt",
    });
    form.push({
      id: indexRow,
      name: `procedimiento_diagnostico${indexRow}`,
      required: true,
      tr_id: `tr_procedimientos_${indexRow}`,
      tab: "tabCpmsCpt",
    });
  }

  function onSubmit() {
    const formValid = validateForm();
    if (!formValid) {
      Swal.fire({
        icon: "error",
        title: "NOTA DE INGRESO DE ENFERMERÍA",
        html: `<p>Faltan campos obligatorios. Asegúrese de llenar aquellos marcados con (*) para continuar.</p>`,
        showLoaderOnConfirm: false,
        confirmButtonText: "ENTENDIDO",
      });
      return null;
    }
    const body = buildRequestSave();
    $.ajax({
      type: "POST",
      url: "/api/nota-enfermeria/consulta-externa/registrar",
      contentType: "application/json",
      data: JSON.stringify(body),
      dataType: "json",
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      beforeSend: function () {
        $("#btnSubmit").prop("disabled", true);
        Swal.fire({
          html: "<h5>GUARDANDO NOTA DE ENFERMERIA...</h5>",
          showLoaderOnConfirm: true,
          onOpen: () => {
            Swal.showLoading();
          },
        });
      },
      success: (response) => {
        clearForm();
        Swal.close();
        window.location.pathname = "enfermeria/consulta-externa";
      },
      error: (error) => {
        Swal.fire({
          icon: "error",
          title: "NOTA DE INGRESO DE ENFERMERÍA",
          html: `<p>Ocurrio un error al registrar la nota de enfermería, intentelo en unos minutos</p>`,
          confirmButtonText: "ENTENDIDO",
        });
      },
      complete: function () {
        Swal.hideLoading();
        $("#btnSubmit").prop("disabled", false);
      },
    });
  }

  function validateForm() {
    let controlsError = [];
    let errorsTabs = [];
    const prestacionEstaFirmada = parseInt($("#firmaPrestacion").val()) === 1 ? true : false;
    for (const control of form) {
      if (!prestacionEstaFirmada && control.tab !== "tabSignosVitales") {
        console.log(`NO HAY FIRMA PERO NO ES DE SIGNOS VITALES => ${control.name}`);
        continue;
      }
      if (control.required) {
        const inputElement = $(`#${control.name}`);
        inputElement.nextAll(".label-help").remove();

        let parentDiv = null;
        if (control.tr_id) {
          parentDiv = inputElement.closest("td");
        } else {
          parentDiv = inputElement.closest(".form-group");
        }
        if (parentDiv) {
          if (inputElement.val() === "") {
            parentDiv.addClass("has-error");
            inputElement.after(
              `<span class="label-help">campo requerido</span>`,
            );
            controlsError.push(control);
            if (control.tab) {
              errorsTabs.push(control);
            }
          } else {
            parentDiv.removeClass("has-error");
            inputElement.nextAll(".label-help").remove();
          }
        }
      }
    }

    if (errorsTabs.length > 0) {
      const firstControl = errorsTabs[0];
      if (firstControl.tab) {
        $(`#tabsRegistrarNotaIngreso a[href="#${firstControl.tab}"]`).tab(
          "show",
        );
      }
      $(`#${firstControl.name}`).focus();
    }

    const id_prestacion = $("#id_prestacion").val();
    if (id_prestacion) {
      const rows = $("#tableProcedures tbody tr");
      if (rows.length === 0 && prestacionEstaFirmada) {
        $(`#tabsRegistrarNotaIngreso a[href="#tabCpmsCpt"]`).tab("show");
        $("#tableProceduresHeader")
          .addClass("table-error danger")
          .after(
            `<span class="label-help">Debe ingresar al menos un procedimiento médico.</span>`,
          );
        controlsError.push({ name: "tabProcedimiendos" });
      } else {
        $("#tableProceduresHeader")
          .removeClass("table-error danger")
          .nextAll(".label-help")
          .remove();
      }
    }
    return controlsError.length === 0;
  }

  function restrictDecimalInput(selector) {
    $(`#${selector}`).on("keypress", function (e) {
      var key = e.which || e.keyCode;
      var char = String.fromCharCode(key);
      if (char.match(/^[0-9.]$/)) {
        var currentValue = $(this).val();
        if (char === "." && currentValue.indexOf(".") !== -1)
          e.preventDefault();
      } else {
        e.preventDefault();
      }
    });
  }

  function validateInputRange(nameInput, minValue, maxValue, menssage = null) {
    $(`#${nameInput}`).on("blur", function () {
      let value = $(this).val();
      let name = $(this).attr("name");
      clearInputError(name);
      if (
        value &&
        (parseFloat(value) < minValue || parseFloat(value) > maxValue)
      ) {
        $(this).siblings("span").remove();
        $(this).css("border", "");
        markInputError(
          name,
          menssage || `El valor debe estar entre ${minValue} y ${maxValue}`,
        );
      }
    });
  }

  function markInputError(nameInput, messsageError = null) {
    const inputElement = $(`#${nameInput}`);
    const parentDiv = inputElement.closest(".form-group, td");
    parentDiv.addClass("has-error");
    inputElement.after(
      `<span class="label-help">${messsageError || "campo requerido"}</span>`,
    );
  }

  function clearInputSuccess(nameInput) {
    const inputElement = $(`#${nameInput}`);
    const parentDiv = inputElement.closest(".form-group, td");
    inputElement
      .siblings(".form-control-feedback, .label-help-success")
      .remove();
    parentDiv.removeClass("has-error has-success");
  }

  function markInputSuccess(nameInput, messsageError = null) {
    const inputElement = $(`#${nameInput}`);
    const parentDiv = inputElement.closest(".form-group");
    parentDiv.addClass("has-success");
    inputElement.after(
      `<span class="label-help-success">${messsageError || "campo correcto"}</span>`,
    );
  }

  function clearInputError(nameInput) {
    const inputElement = $(`#${nameInput}`);
    const parentDiv = inputElement.closest(".form-group, td");
    inputElement.siblings(".form-control-feedback, .label-help").remove();
    parentDiv.removeClass("has-error has-success");
  }

  function calculateIMC(peso = 0, talla = 0) {
    let talla_en_metros = talla * 0.01;
    let imc = peso / (talla_en_metros * talla_en_metros);
    return imc.toFixed(2);
  }

  function calculatePesoIdeal(talla = 0, anios = 0, sexo = "M") {
    let pesoIdeal = 50 + ((parseFloat(talla) - 150) / 4) * 3 + (anios - 20) / 4;
    if (sexo.toUpperCase() === "M") {
      pesoIdeal = pesoIdeal * 0.9;
    }
    return pesoIdeal.toFixed(2);
  }

  function evaluationPeso() {
    clearInputError("imc");
    clearInputSuccess("imc");
    let imcValue = $("#imc").val();
    if (imcValue && parseFloat(imcValue) > 0) {
      if (parseFloat(imcValue) < 18.5) {
        markInputError("imc", "Paciente Bajo de Peso");
      } else if (parseFloat(imcValue) >= 18.5 && parseFloat(imcValue) < 24.9) {
        markInputSuccess("imc", "Paciente Peso Normal");
        //NORMAL
      } else if (parseFloat(imcValue) >= 25.0 && parseFloat(imcValue) < 29.9) {
        markInputError("imc", "Paciente Sobrepeso");
        //SOBREPESO
      } else if (parseFloat(imcValue) >= 30.0 && parseFloat(imcValue) < 34.9) {
        markInputError("imc", "Paciente Obesidad I");
        //OBESIDAD I
      } else if (parseFloat(imcValue) >= 35.0 && parseFloat(imcValue) < 39.9) {
        markInputError("imc", "Paciente Obesidad II");
        message = "";
        // OBESIDAD II
      } else if (parseFloat(imcValue) >= 40.0) {
        markInputError("imc", "Paciente Obesidad III");
        message = "Paciente Obesidad III";
        // OBESIDAD III
      }
    }
  }

  function validateLastRowTable(tableId) {
    let lastRow = $(`#${tableId} tbody tr:last`);
    let isValid = true;
    lastRow.find("input:visible").each(function () {
      clearInputError($(this).attr("id"));
      if ($(this).val().trim() === "") {
        markInputError($(this).attr("id"));
        isValid = false;
      }
    });
    lastRow.find("select:visible").each(function () {
      clearInputError($(this).attr("id"));
      if ($(this).val() === "") {
        markInputError($(this).attr("id"));
        isValid = false;
      }
    });
    return isValid;
  }

  function validateRepeatedProcedureId(id_procedimiento) {
    let procedureId = null;
    let isRepeated = false;
    $("#tableProcedures tbody tr").each(function () {
      procedureId = $(this).find("input[name='id_procedimiento']").val();
      if (
        procedureId &&
        id_procedimiento &&
        parseInt(procedureId) === parseInt(id_procedimiento)
      ) {
        isRepeated = true;
        return false;
      }
    });
    return isRepeated;
  }

  function buildRequestSave() {
    const hipertensionArterial = $(
      'input[name="hipertensionArterial"]:checked',
    ).val();
    const diabetesMellitus = $('input[name="diabetes_mellitus"]:checked').val();
    const alergia = $('input[name="alergia"]:checked').val();
    const hemodialisis = $('input[name="hemodialisis"]:checked').val();

    const arrayCardiologico = $("#cardiologico").val();

    const request = {
      id_prestacion: $("#id_prestacion").val(),
      id_ext_enfermeria: $("#cita_id").val(),
      id_tipo_atencion: 1,
      id_estado_reg: 1,
      observacion_enfermeria: $("#observaciones").val(),
      fecha_atencion_enfermeria: $("#fecha_atencion_enfermeria").val()
        ? moment($("#fecha_atencion_enfermeria").val(), "DD-MM-YYYY").format(
            "YYYY-MM-DD",
          )
        : null,
      hora_atencion_enfermeria: $("#hora_atencion_enfermeria").val(),
      caracter_fisicos: [
        { id: 1, value: $("#presion_arterial_1").val() },
        { id: 2, value: $("#presion_arterial_2").val() },
        { id: 3, value: $("#talla").val() },
        { id: 4, value: $("#pulso_frec_cardiaca").val() },
        { id: 5, value: $("#peso").val() },
        { id: 6, value: $("#frec_respiratorio").val() },
        { id: 7, value: $("#fi02").val() },
        { id: 8, value: $("#sa02").val() },
        { id: 9, value: $("#temperatura").val() },
        { id: 10, value: $("#imc").val() },
        { id: 11, value: $("#peso_ideal").val() },
      ],
      antecedentes: {
        id_hipertension: parseInt(hipertensionArterial),
        descripcion_hipertension: $("#tratamiento_hipertension").val(),
        id_diabetes_millitus: parseInt(diabetesMellitus),
        descripcion_diabetes_millitus: $("#tratamiento_diabetes").val(),
        id_alergia: parseInt(alergia),
        descripcion_alergia: $("#detalle_alergia").val(),
        id_hemodialisis: parseInt(hemodialisis),
        descripcion_hemodialisis: $("#detalle_hemodialisis").val(),
        descripcion_observacion: $("#observacion").val(),
        descripcion_otro: $("#otros_antecedentes").val(),
        cardiologia: arrayCardiologico,
      },
      procedimientos: getValuesRowByTable("tableProcedures") || [],
    };

    return request;
  }

  function getValuesRowByTable(tableName) {
    const rows = $(`#${tableName} tbody tr`);
    const resultados = [];
    rows.each(function (index, fila) {
      let valores = {};
      $(fila)
        .find("input, select")
        .each(function () {
          valores[$(this).attr("name")] = $(this).val();
        });
      resultados.push(valores);
    });
    return resultados;
  }

  function updateRequiredField(fieldName, newRequiredValue) {
    const field = form.find((f) => f.name === fieldName);
    if (field) {
      field.required = newRequiredValue;
    }
  }

  function obtenerListadoAtenciones() {
    handlerNotSearchAsegurado();
    $("#fecha_atencion_enfermeria").val(moment().format("DD-MM-YYYY"));

    return new Promise((resolve, reject) => {
      const id_tipo_doc_ident = $("#id_tipo_doc_ident").val();
      const nro_doc_ident = $("#nro_doc_ident").val();
      const id_servicio = $("#id_servicio").val();
      const id_establecimiento = $("#id_establecimiento").val();
      const fecha_atencion = $("#fecha_ultima_atencion").val();
      const params = {
        id_tipo_doc_ident: id_tipo_doc_ident, // 1
        nro_doc_ident: nro_doc_ident, // 10203167
        consultorio_id: id_servicio, // 13
        id_establecimiento: id_establecimiento, // 76
        fecha_atencion: moment(fecha_atencion, "DD/MM/YYYY").format(
          "YYYY-MM-DD",
        ),
      };
      const queryString = objectToQueryString(params);
      Swal.fire({
        html: "<h5>BUSCANDO ATENCIONES...</h5>",
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        },
      });
      let tableAtencionesCitas = $("#tableAtencionesCitas").DataTable({
        searching: false,
        ordering: false,
        paging: true,
        pageLength: 10,
        lengthChange: false,
        destroy: true,
        ajax: {
          url: `/api/citas/buscar-atenciones-enfermeria/filtros?${queryString}`,
          type: "GET",
        },
        columns: [
          {
            data: null,
            render: function (data, type, row, meta) {
              return `
                <span>${row.prestacion_id ? row.prestacion_id : row.cita_id}</span>
              `;
            },
          },
          {
            data: null,
            render: function (data, type, row, meta) {
              return `
                <span>${moment(row.cita_fecha_hora).format("DD/MM/YYYY") || ""}</span>
              `;
            },
          },
          {
            data: null,
            render: function (data, type, row, meta) {
              return `
                <span>${moment(row.cita_fecha_hora).format("HH:mm") || ""}</span>
              `;
            },
          },
          {
            data: null,
            render: function (data, type, row, meta) {
              return `
                <span>${row.prestacion_fecha_atencion ? moment(row.prestacion_fecha_atencion).format("DD/MM/YYYY") : "-"}</span>
              `;
            },
          },
          {
            data: null,
            render: function (data, type, row, meta) {
              return `
                <span>${row.prestacion_fecha_atencion ? moment(row.prestacion_fecha_atencion).format("HH:mm") : "-"}</span>
              `;
            },
          },
          { data: "consultorio" },
          {
            data: null,
            render: function (data, type, row, meta) {
              if (!row.prestacion_fecha_atencion) {
                return `<span class="label label-warning">NO ATENDIDO</span>`;
              }
              return `
                <div style="text-align: left;">
                  <span><b>DNI: </b> ${row.medico_dni || ""}</span><br>
                  <span><b>Nombres:</b> ${row.medico_nombre || ""} ${row.medico_paterno || ""} ${row.medico_materno || ""}</span>
                </div>
              `;
            },
          },
          // {
          //   data: null,
          //   render: function(data, type, row, meta) {
          //     if (!row.enfermero_id) {
          //       return `<span class="label label-warning">NO ATENDIDO</span>`;
          //     }
          //     return `
          //       <div>
          //         <span><b>DNI: ${row.enfermero_dni || ""}</b></span><br>
          //         <span><b>Nombres:</b> ${row.enfermero_name || ""} ${row.enfermero_paterno || ""} ${row.enfermero_materno || ""}</span>
          //       </div>
          //     `;
          //   }
          // },
          {
            data: null,
            render: function (data, type, row, meta) {
              if (parseInt(row.count_notas_enfermeria) <= 0) {
                return ``;
              }
              return `<button 
                        type="button"
                        class="btn btn-info btn-sm"
                        data-toggle="tooltip"
                        title="Notas Enfermería"
                        id="btnVerListadoAtenciones"
                        data-cita_id="${row.cita_id}"
                      ><i class="fa fa-list"></i></button>`;
              // if (row.enfermeria_id) {
              //   return `<div>
              //     <button
              //       type="button"
              //       class="btn btn-success btn-sm"
              //       data-toggle="tooltip"
              //       title="Editar Ingreso Enfermeria"
              //       id="btnEditIngresoEnfermeria"
              //       data-id="${row.enfermeria_id}"
              //     ><i class="fa fa-pencil"></i></button>
              //     <button
              //       type="button"
              //       class="btn btn-info btn-sm"
              //       data-toggle="tooltip"
              //       title="Ver Detalle Enfermeria"
              //       id="btnVerIngresoEnfermeria"
              //       data-id="${row.enfermeria_id}"
              //     ><i class="fa fa-eye"></i></button>
              //   </div>`;
              // }
              // return `
              //   <div>
              //     <button
              //       type="button"
              //       class="btn btn-success btn-sm"
              //       data-toggle="tooltip"
              //       title="Registrar Ingreso Enfermeria"
              //       id="btnRegistrarIngresoEnfermeria"
              //       data-id_prestacion="${row.prestacion_id}"
              //     ><i class="fa fa-floppy-o "></i></button>
              //   </div>
              // `;
            },
          },
          {
            data: null,
            render: function (data, type, row, meta) {
              return `<div>
                        <button 
                          type="button"
                          class="btn btn-success btn-sm"
                          data-toggle="tooltip"
                          title="Nueva Nota de Ingreso"
                          id="btnRegistrarIngresoEnfermeria"
                          data-id_prestacion="${row.prestacion_id}"
                          data-cita_id="${row.cita_id}"
                        ><i class="fa fa-user-md" aria-hidden="true"></i></button>
                      </div>`;
            },
          },
        ],
      });

      // tableAtencionesCitas.on('xhr', function(e, settings, json) {
      tableAtencionesCitas.on("xhr.dt", function (e, settings, json) {
        Swal.close();
        if (!json.data || json.data.length <= 0) {
          $("#modalListadoCitas").modal("hide");
          Swal.fire({
            icon: "warning",
            title: "NOTA DE INGRESO DE ENFERMERÍA",
            html: `<p style="font-size: 13px">No se encontraron atenciones para el paciente con número de documento <b>${$("#nro_doc_ident").val()}</b></p>`,
            confirmButtonText: "ENTENDIDO",
          });
          $("#nro_doc_ident_hidden").val(nro_doc_ident);
          handlerClearHistories();
          handlerResetTabs();
          handlerDisabledTabs();
          return null;
        }
        $("#modalListadoCitas").modal("show");
        const firstAtencion = json.data[0];
        $("#asegurado_id").val(firstAtencion.asegurado_id || "");
        $("#tipo_documento_modal").val(
          firstAtencion.asegurado_tipo_documento_identidad || "",
        );
        $("#numero_documento_modal").val(
          firstAtencion.asegurado_nro_doc_ident || "",
        );
        $("#nombres_modal").val(firstAtencion.asegurado_nombre || "");
        $("#apellido_paterno_modal").val(firstAtencion.asegurado_paterno || "");
        $("#apellido_materno_modal").val(firstAtencion.asegurado_materno || "");
        $("#grado_modal").val(firstAtencion.asegurado_grado || "");
        $("#sexo_modal").val(
          firstAtencion.asegurado_sexo === "M" ? "Masculino" : "Femenino" || "",
        );
        $("#edad_modal").val(firstAtencion.asegurado_edad || "");

        $("#hora_atencion_enfermeria").val(moment().format("HH:mm"));

        $("#nro_doc_ident_hidden").val(nro_doc_ident);
        handlerClearHistories();
        handlerResetTabs();
        handlerDisabledTabs();
      });

      tableAtencionesCitas.on("draw", function () {
        $('[data-toggle="tooltip"]').tooltip();
      });
    });
  }

  function obtenerAtencionByCitaId(citaId) {
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "GET",
        url: `/api/citas/prestacion/${citaId}`,
        dataType: "json",
        success: function (response) {
          resolve(response);
        },
        error: function (err) {
          reject(err);
        },
      });
    });
  }

  $(document).on("click", "#btnEditIngresoEnfermeria", function () {
    const enfermeria_id = $(this).data("id");

    window.open(
      `/enfermeria/consulta-externa/editar/${enfermeria_id}`,
      "_blank",
    );
  });

  $(document).on("click", "#btnRegistrarIngresoEnfermeria", async function () {
    const id_prestacion = $(this).data("id_prestacion");
    const cita_id = $(this).data("cita_id");
    $("#modalListadoCitas").modal("hide");
    $("#tableAtencionesCitas tbody").empty();
    clearFormModal();
    // await onClickBtnBuscarAsegurado(id_prestacion);
    await onClickBtnBuscarAsegurado(cita_id);
    handlerEnabledTabs();
  });

  $(document).on("click", "#btnVerIngresoEnfermeria", function () {
    const enfermeria_id = $(this).data("id");

    window.open(
      `/enfermeria/consulta-externa/ver-detalle/${enfermeria_id}`,
      "_blank",
    );
  });

  $(document).on("click", "#btnVerListadoAtenciones", function () {
    const cita_id = $(this).data("cita_id");
    $("#panelListadoCitas").slideUp();
    $("#panelListadoAtencionesEnfermeria").slideDown();

    renderTableNotasEnfermeria(cita_id);
  });

  $("#btnBackPanelListadoCitas").on("click", function () {
    $("#panelListadoAtencionesEnfermeria").slideUp();
    $("#panelListadoCitas").slideDown();
    if ($.fn.DataTable.isDataTable("#tableAtencionesEnfermeria")) {
      $("#tableAtencionesEnfermeria").DataTable().clear().draw();
    }
  });

  function clearFormModal() {
    $("#tipo_documento_modal").val("");
    $("#numero_documento_modal").val("");
    $("#nombres_modal").val("");
    $("#apellido_paterno_modal").val("");
    $("#apellido_materno_modal").val("");
    $("#grado_modal").val("");
    $("#sexo_modal").val("");
    $("#edad_modal").val("");
    /* $('a[href="#tabAntecedentes"]').attr("href", "#tabAntecedentes");
    $('a[href="#tabCpmsCpt"]').attr("href", "#tabCpmsCpt");
    $('a[href="#tabObservaciones"]').attr("href", "#tabObservaciones");
    $('a[href="#tabAntecedentes"]')
      .removeClass("disabled")
      .removeAttr("tabindex")
      .css("pointer-events", "auto");
    $('a[href="#tabCpmsCpt"]')
      .removeClass("disabled")
      .removeAttr("tabindex")
      .css("pointer-events", "auto");
    $('a[href="#tabObservaciones"]')
      .removeClass("disabled")
      .removeAttr("tabindex")
      .css("pointer-events", "auto"); */
    const tabs = ["#tabAntecedentes", "#tabCpmsCpt", "#tabObservaciones"];

    tabs.forEach(function (tab) {
      $('a[href="' + tab + '"]')
        .removeClass("disabled")
        .removeAttr("tabindex")
        .off("show.bs.tab");
    });
  }

  function handlerDisabledTabs() {
    $('#tabs li:has(a[href="#tabHistorialAtenciones"])').addClass(
      "disabled-tab",
    );
    $('#tabs li:has(a[href="#tabHistorialLaboratorio"])').addClass(
      "disabled-tab",
    );
    $('#tabs li:has(a[href="#tabHistorialRadiologia"])').addClass(
      "disabled-tab",
    );
    $('#tabs li:has(a[href="#tabHistorialnterconsulta"])').addClass(
      "disabled-tab",
    );
    $('#tabs li:has(a[href="#tabHistorialReferencia"])').addClass(
      "disabled-tab",
    );
    $('#tabs li:has(a[href="#tabHistorialEnfermeria"])').addClass(
      "disabled-tab",
    );
  }

  function handlerEnabledTabs() {
    $('#tabs li:has(a[href="#tabHistorialAtenciones"])').removeClass(
      "disabled-tab",
    );
    $('#tabs li:has(a[href="#tabHistorialLaboratorio"])').removeClass(
      "disabled-tab",
    );
    $('#tabs li:has(a[href="#tabHistorialRadiologia"])').removeClass(
      "disabled-tab",
    );
    $('#tabs li:has(a[href="#tabHistorialnterconsulta"])').removeClass(
      "disabled-tab",
    );
    $('#tabs li:has(a[href="#tabHistorialReferencia"])').removeClass(
      "disabled-tab",
    );
    $('#tabs li:has(a[href="#tabHistorialEnfermeria"])').removeClass(
      "disabled-tab",
    );
  }

  function handlerResetTabs() {
    $('a[href="#tabHistorialAtenciones"]').off("shown.bs.tab");
    $('a[href="#tabHistorialLaboratorio"]').off("shown.bs.tab");
    $('a[href="#tabHistorialRadiologia"]').off("shown.bs.tab");
    $('a[href="#tabHistorialnterconsulta"]').off("shown.bs.tab");
    $('a[href="#tabHistorialReferencia"]').off("shown.bs.tab");
    $('a[href="#tabHistorialEnfermeria"]').off("shown.bs.tab");

    handlerActivesEventsTab();
  }

  function handlerClearHistories() {
    $("#contentHistorialAtenciones").empty();
    $("#divLaboratorio").empty();
    $("#divRadiologico").empty();
    $("#divInterconsultaH").empty();
    $("#divRC1").empty();
  }

  function renderTableNotasEnfermeria(cita_id) {
    if ($.fn.DataTable.isDataTable("#tableAtencionesEnfermeria")) {
      $("#tableAtencionesEnfermeria").DataTable().clear().destroy();
    }
    let tableNotasEnfermeria = $("#tableAtencionesEnfermeria").DataTable({
      searching: false,
      ordering: false,
      paging: true,
      pageLength: 10,
      lengthChange: false,
      destroy: true,
      ajax: {
        url: `/api/atenciones/enfermeria/${cita_id}?id_tipo_atencion=1`,
        type: "GET",
      },
      beforeSend: function () {
        Swal.fire({
          html: "<h5>BUSCANDO NOTAS ENFERMERIA...</h5>",
          showLoaderOnConfirm: true,
          onOpen: () => {
            Swal.showLoading();
          },
        });
      },
      complete: function () {
        Swal.close();
      },
      columns: [
        { data: "enfermeria_id", width: "10%" },
        { data: "fecha_atencion", width: "20%" },
        { data: "hora_atencion", width: "20%" },
        {
          data: null,
          width: "35%",
          render: function (data, type, row) {
            return `<div>
                      <span><b>DNI:  </b> ${row.responsable_dni || ""}</span></br>
                      <span><b>NOMBRES:  </b> ${row.responsable_nombre || ""}</span></br>
                      <span><b>APELLIDOS:  </b> ${row.responsable_paterno || ""} ${row.responsable_materno || ""}</span></br>
                    </div>`;
          },
        },
        {
          data: null,
          width: "15%",
          render: function (data, type, row) {
            let attributeBtnFirma = {
              titleTooltip: "Generar PDF",
              icon: "fa fa-file-pdf-o",
              href: `/enfermeria/consulta-externa/pdf/${row.enfermeria_id}`,
            };
            if (row.flag_firma) {
              attributeBtnFirma.titleTooltip = "PDF firmado";
              attributeBtnFirma.icon = "fa fa-download";
              attributeBtnFirma.href = `/${moment().format("YYYY/MM/DD")}/${row.ruta_firma}`;
            }
            // ${!row.flag_firma ? `<button
            //             type="button"
            //             class="btn btn-warning btn-sm"
            //             data-toggle="tooltip"
            //             data-placement="bottom"
            //             title="Firmar PDF"
            //             id="btnFirmarPdf"
            //             data-id="${row.enfermeria_id}"
            //           ><i class="fa fa-pencil-square" aria-hidden="true"></i></button>` : ""}
            return `<div>
                      ${
                        !row.flag_firma
                          ? `<a 
                        href="/enfermeria/consulta-externa/editar/${row.enfermeria_id}"
                        class="btn btn-success btn-sm"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Editar"
                      ><i class="fa fa-pencil"></i></a>`
                          : ``
                      }
                      <a 
                        href="/enfermeria/consulta-externa/ver-detalle/${row.enfermeria_id}"
                        class="btn btn-info btn-sm"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Ver Detalle"
                      ><i class="fa fa-eye"></i></a>
                      <a 
                        href="${attributeBtnFirma.href}"
                        class="btn btn-primary btn-sm"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="${attributeBtnFirma.titleTooltip}"
                        target="_blank"
                      ><i class="${attributeBtnFirma.icon}" aria-hidden="true"></i></a>
                    </div>`;
          },
        },
      ],
    });

    $("#tableAtencionesEnfermeria").on("draw.dt", function () {
      $('[data-toggle="tooltip"]').tooltip();
    });

    $('[data-toggle="tooltip"]').tooltip();
  }

  $(document).on("click", 'a[href="#tabCpmsCpt"]', function (e) {
    const id_prestacion = $("#id_prestacion").val();
    if (!id_prestacion) {
      $('a[href="#tabSignosVitales"]').tab("show");
      Swal.fire({
        icon: "warning",
        title: "NOTA DE INGRESO DE ENFERMERÍA",
        html: `<p>Debe atender esta cita antes de poder registrar CPMS/CPT</p>`,
        confirmButtonText: "ENTENDIDO",
      });
    }
  });

  function validateDuplicateProcedure(id_procedimiento) {
    const fecha_atencion_enfermeria = $("#fecha_atencion_enfermeria").val();
    const fecha_atencion_enfermeria_format = fecha_atencion_enfermeria
      ? moment(fecha_atencion_enfermeria, "DD-MM-YYYY").format("YYYY-MM-DD")
      : moment().format("YYYY-MM-DD");
    $.ajax({
      url: `/api/enfermeria/consulta-externa/validar/procedimiento/${id_procedimiento}`,
      type: "GET",
      data: {
        id_asegurado: $("#asegurado_id").val(),
        id_consultorio: $("#id_servicio").val(),
        fecha: fecha_atencion_enfermeria_format,
      },
      beforeSend: function () {
        Swal.fire({
          title: "Validando...",
          text: "Por favor espere",
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading(),
        });
      },
    })
      .done(function (response) {
        Swal.close();
        if (
          response &&
          response.data &&
          response.data.is_duplicate &&
          response.data.prestacion_procedimiento
        ) {
          let procedimiento =
            response.data.prestacion_procedimiento.procedimiento_descripcion;
          let procedimiento_codigo =
            response.data.prestacion_procedimiento.codigo;
          let consultorio =
            response.data.prestacion_procedimiento.consultorio_nombre;
          let fecha =
            response.data.prestacion_procedimiento.fecha_atencion_enfermeria;

          let message = `Ya existe el procedimiento <b>${procedimiento_codigo} - ${procedimiento}</b> registrado en la fecha <b>${moment(fecha).format("DD/MM/YYYY")}</b> en el consultorio <b>${consultorio}</b>.`;

          Swal.fire({
            icon: "warning",
            title: "Procedimiento Repetido",
            html: `<p style="font-size: 15px;">${message}</p>`,
            confirmButtonText: "Entendido",
            width: "35%",
          });
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        Swal.close();
        Swal.fire({
          icon: "error",
          title: "Error en validación",
          text: "Ocurrió un problema al validar el procedimiento.",
          confirmButtonText: "Reintentar",
        });
      })
      .always(function () {});
  }

  function getConsultoriosByEstablecimiento(establecimientoId) {
    return new Promise((resolve, reject) => {
      $.ajax({
        type: "GET",
        url: `/api/enfermeria/obtener-consultorios/${establecimientoId}/1`,
        dataType: "json",
        success: function (response) {
          resolve(response);
        },
        error: function (err) {
          reject(err);
        },
      });
    });
  }
});
