$(document).ready(function () {
  let form = [
    {
      id: 1,
      name: "asegurado_tipo_doc_identidad",
      required: false,
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
      required: false,
      tr_id: null,
      tab: null,
    },
    {
      id: 10,
      name: "nro_documento_enfermero",
      required: false,
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
      id: 43,
      name: "fecha_atencion_enfermeria",
      required: true,
      tr_id: null,
      tab: null,
    },
    {
      id: 44,
      name: "hora_atencion_enfermeria",
      required: true,
      tr_id: null,
      tab: null,
    },
  ];

  let listProcedimientosEliminados = [];

  $("#fecha_atencion_enfermeria").datepicker({
    useCurrent: false,
    format: "dd/mm/yyyy",
    todayHighlight: true,
    autoclose: true,
    orientation: "bottom left",
  });

  let diagnosticosArray = [];
  $("#cardiologico").select2({ maximumSelectionLength: 4 });

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
      await onClickBtnBuscarAsegurado();
    }));

  $("#btnSubmit").on("click", function () {
    onSubmit();
  });

  const prestacion = window.prestacion;
  if (!(prestacion.fecha_firma != null ? true : false)) {
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

  $('a[href="#tabHistorialAtenciones"]').one("shown.bs.tab", function (e) {
    const asegurado_nro_doc_ident = $("#asegurado_nro_doc_ident").val();
    getHistorialAtencionMedica(asegurado_nro_doc_ident);
  });

  $('a[href="#tabHistorialLaboratorio"]').one("shown.bs.tab", function (e) {
    const asegurado_nro_doc_ident = $("#asegurado_nro_doc_ident").val();
    getHistorialLaboratorio(asegurado_nro_doc_ident);
  });

  $('a[href="#tabHistorialRadiologia"]').one("shown.bs.tab", function (e) {
    const asegurado_nro_doc_ident = $("#asegurado_nro_doc_ident").val();
    getHistorialRadiologia(asegurado_nro_doc_ident);
  });

  $('a[href="#tabHistorialnterconsulta"]').one("shown.bs.tab", function (e) {
    const asegurado_nro_doc_ident = $("#asegurado_nro_doc_ident").val();
    getHistorialInterconsulta(asegurado_nro_doc_ident);
  });

  $('a[href="#tabHistorialReferencia"]').one("shown.bs.tab", function (e) {
    const asegurado_nro_doc_ident = $("#asegurado_nro_doc_ident").val();
    getHistorialReferenciaContrareferencia(asegurado_nro_doc_ident);
  });

  $('a[href="#tabHistorialEnfermeria"]').one("shown.bs.tab", function (e) {
    const asegurado_id = $("#asegurado_id").val();
    getHistorialEnfermeria(asegurado_id);
  });

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

  async function handlerInit() {
    try {
      Swal.fire({
        html: "<h5>CARGANDO DATOS DE LA ENFERMERIA</h5>",
        showConfirmButton: false,
        onOpen: () => Swal.showLoading(),
      });
      const prestacion_id = $("#id_prestacion").val();
      if (prestacion_id) {
        const productos = await obtenerProductosReceta(prestacion_id);
        if (productos) {
          buildTableRecetas(productos);
        }
        const diagnosticos = await getDiagnosticosByPrestacionId(prestacion_id);
        if (diagnosticos && diagnosticos.length > 0) {
          diagnosticosArray = diagnosticos;
          buildTableDiagnosticos(diagnosticos);
        }
      }
      handlerSignosVitales();
      handlerRadioGroup();
      setSelectMultipleCardiologico();
      handlerSetRowsTableProcedure();
      Swal.close();
    } catch (error) {
      console.log(error);
      Swal.fire({
        icon: "error",
        title: "NOTA DE INGRESO DE ENFERMERÍA",
        html: `<p style="font-size: 13px">OCURRIO UN ERROR AL CARGAR LA PANTALLA DE EDITAR</p>`,
        confirmButtonText: "REVISAR BUSQUEDA",
      });
    } finally {
      Swal.hideLoading();
    }
  }

  function handlerSignosVitales() {
    const signosVitales = window.signosVitales;
    if (signosVitales && signosVitales.length > 0) {
      signosVitales.forEach((element) => {
        if (parseInt(element.id_caracter_fisico) === 1) {
          $("#presion_arterial_1").val(element.descripcion ?? "");
          $("#presion_arterial_1").data("id", element.id);
        }
        if (parseInt(element.id_caracter_fisico) === 2) {
          $("#presion_arterial_2").val(element.descripcion ?? "");
          $("#presion_arterial_2").data("id", element.id);
        }
        if (parseInt(element.id_caracter_fisico) === 3) {
          $("#talla").val(element.descripcion ?? "");
          $("#talla").data("id", element.id);
        }
        if (parseInt(element.id_caracter_fisico) === 4) {
          $("#pulso_frec_cardiaca").val(element.descripcion ?? "");
          $("#pulso_frec_cardiaca").data("id", element.id);
        }
        if (parseInt(element.id_caracter_fisico) === 5) {
          $("#peso").val(element.descripcion ?? "");
          $("#peso").data("id", element.id);
        }
        if (parseInt(element.id_caracter_fisico) === 6) {
          $("#frec_respiratorio").val(element.descripcion ?? "");
          $("#frec_respiratorio").data("id", element.id);
        }
        if (parseInt(element.id_caracter_fisico) === 7) {
          $("#fi02").val(element.descripcion ?? "");
          $("#fi02").data("id", element.id);
        }
        if (parseInt(element.id_caracter_fisico) === 8) {
          if (element.descripcion) {
            const result = validateValueSaO2(element.descripcion);
            $("#sa02").css("border", `2px solid ${result.borderColor}`);
            $("#sa02").after(
              `<span style="color: ${result.borderColor}; font-size: 11px">${result.message}</span>`,
            );
          }
          $("#sa02").val(element.descripcion ?? "");
          $("#sa02").data("id", element.id);
        }
        if (parseInt(element.id_caracter_fisico) === 9) {
          if (element.descripcion) {
            const result = validateValueTemperature(element.descripcion);
            $("#temperatura").css("border", `2px solid ${result.borderColor}`);
            $("#temperatura").after(
              `<span style="color: ${result.borderColor}; font-size: 11px">${result.message}</span>`,
            );
          }
          $("#temperatura").val(element.descripcion ?? "");
          $("#temperatura").data("id", element.id);
        }
        if (parseInt(element.id_caracter_fisico) === 10) {
          $("#imc").val(element.descripcion ?? "");
          $("#imc").data("id", element.id);
        }
        if (parseInt(element.id_caracter_fisico) === 11) {
          $("#peso_ideal").val(element.descripcion ?? "");
          $("#peso_ideal").data("id", element.id);
        }
      });
    }
  }

  function handlerRadioGroup() {
    const enfermeria = window.enfermeria;
    if (enfermeria) {
      if (!enfermeria.id_hipertension) {
        $("label[for=tratamiento_hipertension]").hide();
        $("#tratamiento_hipertension").hide();
        updateRequiredField("tratamiento_hipertension", false);
      }
      if (!enfermeria.id_diabetes_millitus) {
        $("label[for=tratamiento_diabetes]").hide();
        $("#tratamiento_diabetes").hide();
        updateRequiredField("tratamiento_diabetes", false);
      }
      if (!enfermeria.id_alergia) {
        $("label[for=detalle_alergia]").hide();
        $("#detalle_alergia").hide();
        updateRequiredField("detalle_alergia", false);
      }
      if (!enfermeria.id_hemodialisis) {
        $("label[for=detalle_hemodialisis]").hide();
        $("#detalle_hemodialisis").hide();
        updateRequiredField("detalle_hemodialisis", false);
      }
    }
  }

  function setSelectMultipleCardiologico() {
    const enfermeria = window.enfermeria;
    cardiologiaArray = [];
    for (let i = 1; i < 5; i++) {
      cardiologiaArray.push(enfermeria[`cardio_parametro_${i}`]);
    }
    $("#cardiologico").val(cardiologiaArray).trigger("change");
  }

  function handlerSetRowsTableProcedure() {
    const procedimientos = window.procedimientos;
    if (procedimientos && procedimientos.length > 0) {
      for (const procedimiento of procedimientos) {
        onClickAddRowTableProcedimientos(procedimiento);
      }
    }
  }

  function clearForm() {
    $("#asegurado_edad").val("");
    $("#id_prestacion").val("");
    $("#asegurado_sexo_abreviatura").val("");
    $("#establecimiento_nivel").val("");
    form.forEach((element) => {
      $(`#${element.name}`).val("");
    });
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

  function onClickAddRowTableProcedimientos(procedimientoParam = null) {
    if (procedimientoParam === null) {
      const lastRowValid = validateLastRowTable("tableProcedures");
      if (!lastRowValid) {
        Swal.fire({
          icon: "warning",
          title: "<p>NOTA DE INGRESO DE ENFERMERÍA</p>",
          html: '<p class="descripcion-alert">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UN NUEVO PROCEDIMIENTO</p>',
        });
        return null;
      }
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
          <td><input type="hidden" name="prestacion_procedimiento_id" id="prestacion_procedimiento_id_${indexRow}" value=""></td>
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
      const prestacionProcedimientoId = row
        .find("input[name='prestacion_procedimiento_id']")
        .val();
      if (
        prestacionProcedimientoId &&
        !listProcedimientosEliminados.includes(prestacionProcedimientoId)
      ) {
        listProcedimientosEliminados.push(prestacionProcedimientoId);
      }
      form = form.filter((obj) => String(obj.tr_id) !== String(rowId));
      row.remove();
    });

    if (procedimientoParam) {
      $(`#procedimiento_nombre${indexRow}`).val(
        `${procedimientoParam.procedimiento_codigo} - ${procedimientoParam.procedimiento_descripcion}`,
      );
      $(`#procedimiento_cantidad${indexRow}`).val(
        procedimientoParam.prestacion_procedimiento_cantidad,
      );
      $(`#procedimiento_diagnostico${indexRow}`).val(
        procedimientoParam.diagnostico_id,
      );
      $(`#procedure_id_${indexRow}`).val(procedimientoParam.prestacion_id);
      $(`#prestacion_procedimiento_id_${indexRow}`).val(
        procedimientoParam.prestacion_procedimiento_id,
      );
    }

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
    const body = buildRequestUpdate();
    const enfermeria_id = $("#enfermeria_id").val();
    $.ajax({
      type: "PUT",
      url: `/api/enfermeria/consulta-externa/editar/${enfermeria_id}`,
      contentType: "application/json",
      data: JSON.stringify(body),
      dataType: "json",
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      beforeSend: function () {
        $("#btnSubmit").prop("disabled", true);
        Swal.fire({
          html: "<h5>ACTUALIZANDO NOTA DE ENFERMERIA...</h5>",
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
        console.log(error);
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

  function buildRequestUpdate() {
    const hipertensionArterial = $(
      'input[name="hipertensionArterial"]:checked',
    ).val();
    const diabetesMellitus = $('input[name="diabetes_mellitus"]:checked').val();
    const alergia = $('input[name="alergia"]:checked').val();
    const hemodialisis = $('input[name="hemodialisis"]:checked').val();

    const arrayCardiologico = $("#cardiologico").val();

    const request = {
      observacion_enfermeria: $("#observaciones").val(),
      antecedentes_enfermeria_id: $("#antecedentes_enfermeria_id").val(),
      id_prestacion: $("#id_prestacion").val(),
      fecha_atencion_enfermeria: $("#fecha_atencion_enfermeria").val(),
      hora_atencion_enfermeria: $("#hora_atencion_enfermeria").val(),
      caracter_fisicos: [
        {
          id: $("#presion_arterial_1").data("id"),
          value: $("#presion_arterial_1").val(),
        },
        {
          id: $("#presion_arterial_2").data("id"),
          value: $("#presion_arterial_2").val(),
        },
        { id: $("#talla").data("id"), value: $("#talla").val() },
        {
          id: $("#pulso_frec_cardiaca").data("id"),
          value: $("#pulso_frec_cardiaca").val(),
        },
        { id: $("#peso").data("id"), value: $("#peso").val() },
        {
          id: $("#frec_respiratorio").data("id"),
          value: $("#frec_respiratorio").val(),
        },
        { id: $("#fi02").data("id"), value: $("#fi02").val() },
        { id: $("#sa02").data("id"), value: $("#sa02").val() },
        { id: $("#temperatura").data("id"), value: $("#temperatura").val() },
        { id: $("#imc").data("id"), value: $("#imc").val() },
        { id: $("#peso_ideal").data("id"), value: $("#peso_ideal").val() },
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
      procedimientos_eliminados: listProcedimientosEliminados,
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

  handlerInit();

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
      ? moment(fecha_atencion_enfermeria, "DD/MM/YYYY").format("YYYY-MM-DD")
      : moment().format("YYYY-MM-DD");
    $.ajax({
      url: `/api/enfermeria/consulta-externa/validar/procedimiento/${id_procedimiento}`,
      type: "GET",
      data: {
        id_asegurado: $("#asegurado_id").val(),
        id_consultorio: $("#consultorio_id").val(),
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
});
