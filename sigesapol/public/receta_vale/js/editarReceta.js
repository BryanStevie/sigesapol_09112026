$(document).ready(function () {
  let form = [
    {
      name: "merv_id_tipo_atencion",
      enable_edit: true,
      required: true,
      is_select2: true,
    },
    { name: "merv_nro_receta", enable_edit: true, required: true },
    { name: "merv_anio_receta", enable_edit: false, required: false },
    { name: "merv_fecha_expedicion", enable_edit: true, required: true },
    { name: "merv_fecha_expiracion", enable_edit: true, required: true },
    { name: "merv_fecha_dispensacion", enable_edit: false, required: false },
    { name: "merv_tipo_doc_ident", enable_edit: false, required: false },
    { name: "merv_nro_doc_ident", enable_edit: false, required: false },
    { name: "merv_apellidos_nombres", enable_edit: false, required: false },
    { name: "merv_parentesco", enable_edit: false, required: false },
    { name: "merv_nro_hc", enable_edit: false, required: true },
    {
      name: "merv_grado",
      enable_edit: false,
      required: false,
      is_select2: false,
    },
    {
      name: "merv_servicios",
      enable_edit: true,
      required: true,
      is_select2: true,
    },
    { name: "merv_upss", enable_edit: true, required: true, is_select2: true },
    {
      name: "merv_turno",
      enable_edit: true,
      required: true,
      is_select2: true,
      tipo_receta: [18],
    },
    {
      name: "merv_interconsulta",
      enable_edit: true,
      required: true,
      is_select2: true,
      tipo_receta: [128],
    },
    {
      name: "merv_diastto",
      enable_edit: false,
      required: true,
      tipo_receta: [128],
    },
    {
      name: "merv_nro_guia",
      enable_edit: true,
      required: true,
      tipo_receta: [128],
    },
    {
      name: "merv_medico",
      enable_edit: true,
      required: true,
      is_select2: true,
    },
  ];

  let tables = [
    {
      id: "mervTableDiagnosticos",
      headId: "mervTableDiagnosticosHead",
      messageEmpty: "Debe ingresar al menos un diagnostico.",
      minLength: 1,
    },
    {
      id: "mervTablePrescripcion",
      headId: "mervTablePrescripcionHead",
      messageEmpty: "Debe ingresar al menos un procedimiento.",
      minLength: 1,
    },
  ];

  const OBSERVACIONES = {
    default: [
      { id: 1, text: "Sin stock" },
      { id: 2, text: "Paciente tiene" },
      { id: 3, text: "No requiere" },
      { id: 4, text: "No corresponde" },
      { id: 5, text: "Mal prescrito" },
      { id: 6, text: "Alerta DIGEMID" },
      { id: 20, text: "No Petitorio" },
    ],

    farmacia18Extra: [{ id: 19, text: "Por Recoger" }],

    farmacia128: [
      { id: 0, text: "" },
      { id: 1, text: "Sin stock" },
      { id: 18, text: "Atendido", autoSelect: true },
      { id: 2, text: "Paciente tiene" },
      { id: 3, text: "No requiere" },
      { id: 4, text: "No corresponde" },
      { id: 6, text: "Alerta DIGEMID" },
      { id: 7, text: "Suspendido" },
      { id: 17, text: "RP" },
      { id: 8, text: "Mal prescrito - A" },
      { id: 9, text: "Mal prescrito - B" },
      { id: 10, text: "Mal prescrito - C" },
      { id: 11, text: "Mal prescrito - D" },
      { id: 12, text: "Mal prescrito - E" },
      { id: 13, text: "Mal prescrito - F" },
      { id: 14, text: "Mal prescrito - G" },
      { id: 15, text: "Mal prescrito - H" },
      { id: 16, text: "Mal prescrito - I" },
      { id: 20, text: "No Petitorio" },
    ],
  };

  let listTiposDiagnostico = [
    { id: 1, value: "PRESUNTIVO" },
    { id: 2, value: "DEFINITIVO" },
    { id: 3, value: "REITERATIVO" },
  ];

  let listDiagnosticos = [];

  const grados = [
    "NINGUNO",
    "ALUMNO",
    "CADETE",
    "ALFÉREZ DE SERVICIO",
    "ALFÉREZ MAESTRO ARMERO",
    "CAPITÁN",
    "CAPITÁN DE SERVICIO",
    "CAPITÁN MAESTRO ARMERO",
    "COMANDANTE",
    "COMANDANTE DE SERVICIO",
    "COMANDANTE MAESTRO ARMERO",
    "CORONEL",
    "CORONEL DE SERVICIO",
    "ESPECIALISTA BRIGADIER",
    "ESPECIALISTA DE PRIMERA",
    "ESPECIALISTA DE SEGUNDA",
    "ESPECIALISTA DE TERCERA",
    "ESPECIALISTA SUPERIOR",
    "ESPECIALISTA TÉCNICO DE PRIMERA",
    "ESPECIALISTA TÉCNICO DE SEGUNDA",
    "ESPECIALISTA TÉCNICO DE TERCERA",
    "GENERAL",
    "GENERAL DE SERVICIO",
    "MAYOR",
    "MAYOR DE SERVICIO",
    "MAYOR MAESTRO ARMERO",
    "SUBOFICIAL BRIGADIER",
    "SUBOFICIAL BRIGADIER DE SERVICIO",
    "SUBOFICIAL DE PRIMERA",
    "SUBOFICIAL DE PRIMERA DE SERVICIO",
    "SUBOFICIAL DE SEGUNDA",
    "SUBOFICIAL DE SEGUNDA DE SERVICIO",
    "SUBOFICIAL DE TERCERA",
    "SUBOFICIAL DE TERCERA DE SERVICIO",
    "SUBOFICIAL SUPERIOR",
    "SUBOFICIAL SUPERIOR DE SERVICIO",
    "SUBOFICIAL TECNICO DE PRIMERA",
    "SUBOFICIAL TECNICO DE PRIMERA DE SERVICIO",
    "SUBOFICIAL TECNICO DE SEGUNDA",
    "SUBOFICIAL TECNICO DE SEGUNDA DE SERVICIO",
    "SUBOFICIAL TECNICO DE TERCERA",
    "SUBOFICIAL TECNICO DE TERCERA DE SERVICIO",
    "TENIENTE",
    "TENIENTE DE SERVICIO",
    "TENIENTE GENERAL",
    "TENIENTE MAESTRO ARMERO",
  ];

  // TODO: ¿Para qué es?
  let isClearingForm = false;

  const typesRecipe = {
    DIGITAL: 0,
    FISICA: 1,
  };

  const tipoReceta = {
    DOSIS: 128,
    EMERGENCIA: 19,
    CQ: 18,
    ONCOLOGICA: 10,
    CENTRAL: 3,
  };

  $("#mervBtnAddTableDiagnosticos").on("click", function () {
    onClickAddRowTableDiagnosticos();
  });

  $("#mervBtnShowDetail").on("click", function () {
    showHistoryRecipes();
  });

  $("#mervBtnSubmit").on("click", function () {
    onSubmitForm();
  });

  // $("#merv_servicios").on("change", function () {
  //   if (isClearingForm) return;
  //   console.log(isClearingForm, "Se detecta change en el select de SERVICIOS");
  //   /* const disabled = parseInt(this.value, 10) === 0; */
  //   $("#merv_turno")
  //     .val("")
  //     /* .prop("disabled", disabled) */
  //     .trigger("change.select2");
  //   $("#merv_upss")
  //     .val("")
  //     /* .prop("disabled", disabled) */
  //     .trigger("change.select2");
  //   $("#merv_medico")
  //     .val("0")
  //     /* .prop("disabled", disabled) */
  //     .trigger("change.select2");
  //   /* if (disabled) {
  //   } */
  // });

  $("#merv_turno").on("change", async function () {
    if (isClearingForm) return;
    let turno = this.value;
    if (turno === "" || turno === null) {
      turno = 0;
    }
    const disabled = parseInt(this.value, 10) === 0;
    $("#merv_medico")
      .val("0")
      .prop("disabled", disabled)
      .trigger("change.select2");

    Swal.fire({
      title: "Cargando Medicos...",
      text: "Por favor espere",
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => Swal.showLoading(),
    });
    try {
      const establecimiento_id = window.APP_CONFIG.idEstablecimiento;
      const id_consultorio = $("#merv_servicios").val();
      const medicos = await getMedicos(
        turno,
        establecimiento_id,
        id_consultorio,
        $("#farmacia_id").val(),
      );
      $("#merv_medico").val(null).trigger("change").empty();
      let optionsSelectMedicos =
        '<option value="0" selected>-- Seleccionar --</option>';
      if (medicos && medicos.length > 0) {
        medicos.forEach((medico) => {
          optionsSelectMedicos += `<option value="${medico.id}">${medico.dni} | ${medico.colegiatura} | ${medico.nombre} ${medico.paterno} ${medico.materno}</option>`;
        });
        $("#merv_medico").html(optionsSelectMedicos).trigger("change");
      }
    } catch (error) {
      Swal.fire({
        icon: "error",
        title: "Ocurrió un error",
        text: error.message || "No se pudo cargar la información",
      });
    } finally {
      Swal.close();
    }
  });

  $("#modalEditRecetaVale").on("show.bs.modal", function () {
    const recetaId = $(this).data("id");
    beforeShowModal(recetaId);
  });

  $("#modalEditRecetaVale").on("shown.bs.modal", function () {});

  $("#modalEditRecetaVale").on("hide.bs.modal", function () {
    clearFormAndTables();
  });

  // $('#modalEditRecetaVale').on('hidden.bs.modal', function () {
  //   console.log('4️ hidden: el modal ya se cerró');
  // });

  $(document).on("click", ".btn-det-atencion", function () {
    const idRecetaVale = $(this).data("id");
    showProductsOnHistoryRecipe(idRecetaVale);
  });

  $(document).on("hidden.bs.modal", ".modal", function () {
    if ($(".modal.show, .modal.in").length > 0) {
      $("body").addClass("modal-open");
    }
  });

  $(document).on("select2:open", function () {
    const dropdown = document.querySelector(
      ".select2-container--open .select2-dropdown",
    );
    if (dropdown) {
      dropdown.addEventListener("wheel", function (e) {
        e.stopPropagation();
      });
    }
  });

  async function beforeShowModal(recetaId) {
    Swal.fire({
      title: "Cargando información...",
      text: "Por favor espere.",
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => Swal.showLoading(),
    });
    try {
      const establecimiento_id = window.APP_CONFIG.idEstablecimiento;

      const [
        consultorios,
        /*  upss, */ turnos,
        viasAdministracion,
        receta,
        observaciones,
      ] = await Promise.all([
        getConsultorios(),
        /* getUpss(), */
        getTurnos(),
        getViasAdministracion(),
        getRecetaById(recetaId),
        getObservaciones(),
      ]);

      if (
        !receta ||
        !receta.data.recetaVale ||
        !receta.data.recetaVale.nro_receta ||
        receta.data.recetaVale.id_tipo_num === "" ||
        receta.data.recetaVale.id_tipo_num === null
      ) {
        throw new Error("No se pudo obtener la receta");
      }

      const typeRecipeId = receta.data.recetaVale.id_tipo_num;
      const typeService = receta.data.recetaVale.tipo_receta;
      const turno = receta.data.recetaVale.turno || 0;
      const medicos = await getMedicos(
        turno,
        establecimiento_id,
        receta.data.recetaVale.id_consultorio,
        $("#farmacia_id").val(),
      );

      setValuesForm(receta.data, consultorios, turnos, medicos, typeRecipeId);
      buildTableDiagnosticos(receta.data, typeRecipeId);
      buildTableProductos(
        receta.data,
        viasAdministracion,
        observaciones,
        typeRecipeId,
      );

      updateFormByTypeRecipe(typeRecipeId, typeService);
      updateTablePrescripcionByTypeRecipeId(typeRecipeId);
      buildForm();
      addAsteriskToLabel();
    } catch (error) {
      Swal.fire({
        icon: "error",
        title: "Ocurrió un error",
        text: error.message || "No se pudo cargar la información",
      });
    } finally {
      Swal.close();
    }
  }

  function buildForm() {
    form.forEach((control) => {
      if (control.tipo_receta) {
        const farmaciaId = parseInt($("#farmacia_id").val(), 10);
        if (!control.tipo_receta.includes(farmaciaId)) {
          $(`#${control.name}`).parent().hide();
          return;
        }
      }
      const $el = $("#" + control.name);
      if (!$el.length) return;
      if (!$el.is("select")) {
        if (!control.enable_edit) {
          $el.prop("readonly", true).addClass("input-readonly");
        } else {
          $el.prop("readonly", false).removeClass("input-readonly");
        }
      }
      if (control.is_select2) {
        if (!$el.hasClass("select2-hidden-accessible")) {
          $el.select2({
            width: "100%",
            dropdownParent: $("#modalEditRecetaVale"),
            closeOnSelect: true,
          });
        }
        if (!control.enable_edit) {
          disableSelect2($el);
        } else {
          enableSelect2($el);
        }
      } else if ($el.is("select")) {
        if (!control.enable_edit) {
          $el.prop("disabled", true).css("background-color", "#E0E0E0");
        } else {
          $el.prop("disabled", false).css("background-color", "");
        }
      }
    });
  }

  function disableSelect2($el) {
    $el.prop("disabled", true).trigger("change.select2");
    $el.next(".select2-container").addClass("select2-container--disabled");
  }

  function enableSelect2($el) {
    $el.prop("disabled", false).trigger("change.select2");
    $el.next(".select2-container").removeClass("select2-container--disabled");
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

  function updateFormByTypeRecipe(typeRecipeId, typeService) {
    if (parseInt(typeRecipeId) === typesRecipe.DIGITAL) {
      actualizarRequired("merv_id_tipo_atencion", false, false);
      actualizarRequired("merv_nro_receta", false, false);
      actualizarRequired("merv_anio_receta", false, false);
      actualizarRequired("merv_fecha_dispensacion", false, false);
      actualizarRequired("merv_tipo_doc_ident", false, false);
      actualizarRequired("merv_nro_doc_ident", false, false);
      actualizarRequired("merv_apellidos_nombres", false, false);
      actualizarRequired("merv_parentesco", false, false);
      actualizarRequired("merv_nro_hc", false, false);
      actualizarRequired("merv_grado", false, false);
      actualizarRequired("merv_servicios", false, false);
      actualizarRequired("merv_upss", false, false);
      actualizarRequired("merv_turno", false, false);
      actualizarRequired("merv_medico", false, false);
      actualizarRequired("merv_nro_guia", false, false);
      actualizarRequired("merv_interconsulta", false, false);
      actualizarRequired("merv_diastto", false, false);

      $("#mervBtnAddTableDiagnosticos").hide();
    }
    if (parseInt(typeRecipeId) === typesRecipe.FISICA) {
      actualizarRequired("merv_grado", false, false);
      actualizarRequired("merv_servicios", true, true);
      actualizarRequired("merv_turno", false, false);
      actualizarRequired("merv_upss", true, true);
      actualizarRequired("merv_medico", true, true);

      $("#mervBtnAddTableDiagnosticos").show();
    }

    // if (typeService.trim() === "AMBULATORIO") {
    //   actualizarRequired("merv_turno", true, true);
    // } else {
    //   actualizarRequired("merv_turno", false, true);
    // }

    if (parseInt(typeRecipeId) === typesRecipe.DIGITAL) {
      $('[name="merv_id_observacion"]').change(function () {
        const value = $(this).val();

        if (value >= 8 && value <= 16) {
          const tdActual = $(this).closest("td"); // TD donde está el select/input
          const tdAnterior1 = tdActual.prev(); // TD anterior
          const tdAnterior2 = tdAnterior1.prev(); // TD anterior del anterior
          const inputPrescrita = tdAnterior2.find("input"); // TD anterior del anterior
          inputPrescrita.prop("disabled", false);
          inputPrescrita.prop("readonly", false);
          inputPrescrita.removeClass("input-readonly");
        }
      });
    }
  }

  function updateTablePrescripcionByTypeRecipeId(typeRecipeId) {
    const colsOcultar = [
      "#mervTablePresThViaAdm",
      "#mervTablePresThPos",
      "#mervTablePresThObs",
    ];
    if (parseInt(typeRecipeId) === typesRecipe.DIGITAL) {
      colsOcultar.forEach((col) => {
        $(`${col}`).show();
        $(
          `#mervTablePrescripcion tbody td:nth-child(${$(`${col}`).index() + 1})`,
        ).show();
      });
      $("#mervTablePresThProd").css("width", "37%");
      $("#mervTablePresThDiag").css("width", "7%");
    }
    if (parseInt(typeRecipeId) === typesRecipe.FISICA) {
      colsOcultar.forEach((col) => {
        $(`${col}`).hide();
        $(
          `#mervTablePrescripcion tbody td:nth-child(${$(`${col}`).index() + 1})`,
        ).hide();
      });
      $("#mervTablePresThProd").css("width", "48%");
      $("#mervTablePresThDiag").css("width", "17%");
    }
  }

  function actualizarRequired(name, required, enableEdit) {
    const field = form.find((f) => f.name === name);
    if (field) {
      field.required = required;
      field.enable_edit = enableEdit;
    }
  }

  function getRecetaById(id) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: `/api/receta-vale/${id}`,
        type: "GET",
        dataType: "json",
      })
        .done(function (response) {
          resolve(response);
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
          reject({ jqXHR, textStatus, errorThrown });
        })
        .always(function () {});
    });
  }

  $("#merv_interconsulta").on("change", function () {
    const esInterconsulta = $(this).val();
    if (esInterconsulta === "SI") {
      $("#merv_diastto").prop("readonly", false).removeClass("input-readonly");
    } else {
      $("#merv_diastto").prop("readonly", true).addClass("input-readonly");
      $("#merv_diastto").val("0");
    }
  });

  async function setValuesForm(
    data,
    consultorios,
    turnos,
    medicos,
    typeRecipeId,
  ) {
    isClearingForm = true;
    if (consultorios && consultorios.length > 0) {
      let optionsSelectConsultorio =
        '<option value="">-- Seleccionar --</option>';
      consultorios.forEach((consultorio) => {
        optionsSelectConsultorio += `<option value="${consultorio.id}">${consultorio.nombre}</option>`;
      });
      $("#merv_servicios").html(optionsSelectConsultorio);
    }
    const consultorio_id = data.recetaVale.id_consultorio;
    const tipo_receta = data.recetaVale.tipo_receta;
    const upss = await getUpss(
      consultorio_id,
      tipo_receta == "AMBULATORIO" ? 1 : tipo_receta == "EMERGENCIA" ? 2 : 3,
    );
    if (upss && upss.length > 0) {
      let optionsSelectUpss = '<option value="">-- Seleccionar --</option>';
      upss.forEach((upss) => {
        optionsSelectUpss += `<option value="${upss.id}">${upss.codigo}</option>`;
      });
      $("#merv_upss").html(optionsSelectUpss);
    }
    if (tipo_receta == "CENTRO QUIRURGICO") {
      $("#merv_upss").append(`<option value="${"66"}">040000</option>`);
      $("#merv_upss").val("66").prop("disabled", true).trigger("change");
      $("#merv_servicios").prop("disabled", true);
    }
    if (turnos && turnos.length > 0) {
      let optionsSelectTurnos = '<option value="">-- Seleccionar --</option>';
      turnos.forEach((turno) => {
        optionsSelectTurnos += `<option value="${turno.abreviatura}">${turno.descripcion}</option>`;
      });
      $("#merv_turno").html(optionsSelectTurnos);
    }
    if (medicos && medicos.length > 0) {
      let optionsSelectMedicos = '<option value="">-- Seleccionar --</option>';
      medicos.forEach((medico) => {
        optionsSelectMedicos += `<option value="${medico.id}">${medico.dni} | ${medico.colegiatura} | ${medico.nombre} ${medico.paterno} ${medico.materno}</option>`;
      });
      $("#merv_medico").html(optionsSelectMedicos);
    }
    if (grados && grados.length > 0) {
      let optionsSelectMGrados = '<option value="">-- Seleccionar --</option>';
      grados.forEach((grado) => {
        optionsSelectMGrados += `<option value="${grado}">${grado}</option>`;
      });
      $("#merv_grado").html(optionsSelectMGrados);
    }
    if (data.recetaVale) {
      const recetaVale = data.recetaVale;

      const full_name = `${recetaVale.paterno_beneficiario} ${recetaVale.materno_beneficiario} ${recetaVale.nombre_beneficiario}`;

      $("#merv_type_recipe").val(typeRecipeId || "0");
      $("#merv_id_receta_vale").val(recetaVale.id || "-");
      $("#merv_sexo_beneficiario").val(recetaVale.sexo_beneficiario || "-");
      $("#merv_interconsulta").val(recetaVale.interconsulta).change();
      if (recetaVale.interconsulta === "SI") {
        $("#merv_diastto")
          .prop("readonly", false)
          .removeClass("input-readonly");
      }
      $("#merv_nro_guia").val(recetaVale.nro_guia);
      $("#merv_diastto").val(recetaVale.interconsulta_dias);
      const textoBuscar = recetaVale.tipo_receta;
      const $select_ta = $("#merv_id_tipo_atencion");
      const $option_ta = $select_ta.find("option").filter(function () {
        return $(this).text().trim() === textoBuscar.trim();
      });
      if ($option_ta.length) {
        $select_ta.val($option_ta.val()).trigger("change.select2");
      }
      $("#merv_nro_receta").val(recetaVale.nro_receta || "-");
      $("#merv_anio_receta").val(recetaVale.tipo_receta || "-");
      $("#merv_fecha_expedicion").val(recetaVale.fecha_expedicion || "-");
      $("#merv_fecha_expiracion").val(recetaVale.fecha_expiracion || "-");
      $("#merv_fecha_dispensacion").val(recetaVale.tipo_receta || "-");
      $("#merv_tipo_doc_ident").val(recetaVale.tipodoc_beneficiario || "-");
      $("#merv_nro_doc_ident").val(recetaVale.dni_beneficiario || "-");
      $("#merv_apellidos_nombres").val(full_name);
      $("#merv_parentesco").val(recetaVale.tipo_beneficiario || "-");
      $("#merv_nro_hc").val(recetaVale.nro_historia || "-");
      const textoGrado = recetaVale.grado;
      $("#merv_grado option")
        .filter(function () {
          return $(this).text().trim() === textoGrado.trim();
        })
        .prop("selected", true);
      $("#merv_servicios")
        .val(recetaVale.id_consultorio || "")
        .trigger("change");
      const textoUpss = recetaVale.codigo_upss ?? "";
      const $select_upss = $("#merv_upss");
      const $option = $select_upss.find("option").filter(function () {
        return $(this).text().trim() === textoUpss.trim();
      });
      if ($option.length) {
        $select_upss.val($option.val()).trigger("change.select2");
      }
      $("#merv_turno")
        .val(recetaVale.turno || "")
        .trigger("change");
      $("#merv_medico")
        .val(recetaVale.id_medico || "")
        .trigger("change");
    }
    isClearingForm = false;
  }

  /* $("#merv_servicios").on("change", async function () {
    if (isClearingForm) return;
    const tipoAtencion = $("#merv_id_tipo_atencion").val();
    const valorFinal = tipoAtencion == 4 ? 3 : tipoAtencion;
    if (tipoAtencion != 4) {
      const upss = await getUpss($(this).val(), valorFinal);
      if (upss && upss.length > 0) {
        const $select = $("#merv_upss");
        $select.val("").trigger("change");
        let optionsSelectUpss = '<option value="">-- Seleccionar --</option>';
        upss.forEach((upss) => {
          optionsSelectUpss += `<option value="${upss.id_upss}">${upss.codigo}</option>`;
        });
        $("#merv_upss").html(optionsSelectUpss);
      }
      $("#merv_upss").val("").trigger("change.select2");
    }
  }); */
  $("#merv_servicios").on("change", async function () {
    if (isClearingForm) return;
    const servicioId = $(this).val();

    if (!servicioId) {
      $("#merv_upss")
        .html('<option value="">-- Seleccionar --</option>')
        .val("")
        .prop("disabled", false)
        .trigger("change.select2");

      return; // salimos, no llamamos al backend
    }

    const tipoAtencion = $("#merv_id_tipo_atencion").val();
    const valorFinal = tipoAtencion == 4 ? 3 : tipoAtencion;

    if (tipoAtencion != 4) {
      const upss = await getUpss(servicioId, valorFinal);
      const $select = $("#merv_upss");
      let optionsSelectUpss = '<option value="">-- Seleccionar --</option>';
      if (upss && upss.length > 0) {
        upss.forEach((item) => {
          optionsSelectUpss += `<option value="${item.id_upss}">${item.codigo}</option>`;
        });
        $select.prop("disabled", false);
      } else {
        $select.prop("disabled", true);
      }
      $select.html(optionsSelectUpss).val("").trigger("change.select2");
      $("#merv_medico").html("");
      $("#merv_medico").append(`<option value="">--Seleccionar--</option>`);
      $("#merv_medico")
        .val("")
        .prop("disabled", false)
        .trigger("change.select2");
      if (servicioId !== "" && $("#merv_turno").val() !== "") {
        const medicos = await getMedicos(
          $("#merv_turno").val(),
          window.APP_CONFIG.idEstablecimiento,
          servicioId,
          $("#farmacia_id").val(),
        );

        const $select_medicos = $("#merv_medico");

        let optionsSelectMedicos =
          '<option value="">-- Seleccionar --</option>';

        if (medicos && medicos.length > 0) {
          medicos.forEach((medico) => {
            optionsSelectMedicos += `
        <option value="${medico.id}">
          ${medico.dni} | ${medico.colegiatura} | 
          ${medico.nombre} ${medico.paterno} ${medico.materno}
        </option>`;
          });
        }

        $select_medicos.html(optionsSelectMedicos);

        $select_medicos.val("");

        $select_medicos.trigger("change.select2");
      }
    }
  });

  $("#merv_id_tipo_atencion").on("change", async function () {
    if (isClearingForm) return;
    if (this.value == 4) {
      $("#merv_upss").html("");
      $("#merv_upss").append(`<option value="${"66"}">040000</option>`);
      $("#merv_upss").val("66").prop("disabled", true).trigger("change");
    } else {
      if ($("#merv_servicios").val() != "") {
        const upss = await getUpss($("#merv_servicios").val(), this.value);
        if (upss && upss.length > 0) {
          const $select = $("#merv_upss");
          $select.val("").trigger("change");
          let optionsSelectUpss = '<option value="">-- Seleccionar --</option>';
          upss.forEach((upss) => {
            optionsSelectUpss += `<option value="${upss.id_upss}">${upss.codigo}</option>`;
          });
          $("#merv_upss").html(optionsSelectUpss);
        }
      } else {
        $("#merv_upss").html("");
        $("#merv_upss").append(`<option value="">--Seleccionar--</option>`);
      }
      $("#merv_upss").val("").prop("disabled", false).trigger("change.select2");
      $("#merv_servicios").prop("disabled", false);
    }
  });

  function buildTableDiagnosticos(data, typeRecipeId) {
    if (data && data.diagnosticos && data.diagnosticos.length > 0) {
      let newRow = ``;
      let optionsTipos = ``;
      const diagnosticos = data.diagnosticos;

      const idPrestacion = data.diagnosticos[0].id_prestacion;
      if (idPrestacion) {
        $("#merv_id_prestacion").val(idPrestacion);
      }

      diagnosticos.forEach((diagnostico, index) => {
        addItemListDiagnosticos({
          diagnostico_id: diagnostico.id_diagnostico,
          nombre_diagnostico: `${diagnostico.codigo} - ${diagnostico.nombre}`,
        });
        optionsTipos = "<option value=''>--Seleccionar--</option>";
        listTiposDiagnostico.forEach((opcion) => {
          optionsTipos += `<option value="${opcion.id}" ${diagnostico.id_tipo_diagnostico == opcion.id ? "selected" : ""}>${opcion.value}</option>`;
        });

        newRow = `<tr id="merv_tr_diagnosticos_${index}">
                    <td><input type="text" name="merv_diagnostico" id="merv_diagnostico_${index}" class="form-control input-sm input-readonly" value="${diagnostico.codigo} - ${diagnostico.nombre}" readonly></td>
                    <td>
                      <select 
                        class="form-control input-sm"
                        name="merv_id_tipo_diagnostico"
                        id="merv_id_tipo_diagnostico_${index}"
                      >
                        ${optionsTipos}
                      </select>
                    </td>
                    <td></td>
                    <td><input type="hidden" name="merv_id_diagnostico" id="merv_id_diagnostico_${index}" value="${diagnostico.id_diagnostico}"></td>
                    <td><input type="hidden" name="merv_id_receta_diagnostico" id="merv_id_receta_diagnostico_${index}" value="${diagnostico.id}"></td>
                    <td><input type="hidden" name="merv_id_prestacion" id="merv_id_prestacion_${index}" value="${diagnostico.id_prestacion}"></td>
                  </tr>`;
        $("#mervTableDiagnosticos tbody").append(newRow);

        form.push({
          name: `merv_diagnostico_${index}`,
          enable_edit:
            parseInt(typeRecipeId) === typesRecipe.DIGITAL ? false : true,
          required:
            parseInt(typeRecipeId) === typesRecipe.DIGITAL ? false : true,
          tr_id: `merv_tr_diagnosticos_${index}`,
        });
        form.push({
          name: `merv_id_tipo_diagnostico_${index}`,
          enable_edit:
            parseInt(typeRecipeId) === typesRecipe.DIGITAL ? false : true,
          required:
            parseInt(typeRecipeId) === typesRecipe.DIGITAL ? false : true,
          tr_id: `merv_tr_diagnosticos_${index}`,
        });
      });
    }
  }

  function buildTableProductos(
    data,
    viasAdministracion,
    observaciones,
    typeRecipeId,
  ) {
    console.log("[data.productos] =>", data.productos);
    if (data && data.productos && data.productos.length > 0) {
      let newRow = ``;
      let optionsSelectDiagnosticos = ``;
      let optionsSelectViasAdministracion = ``;
      let optionsSelectObservaciones = ``;
      let diagnosticos = data.diagnosticos;
      const productos = data.productos;

      productos.forEach((producto, index) => {
        optionsSelectDiagnosticos = `<option value="">--Seleccionar--</option>`;
        diagnosticos.forEach((diagnostico) => {
          optionsSelectDiagnosticos += `<option value="${diagnostico.id_diagnostico}" data-id_receta_diagnostico="${diagnostico.id}" ${diagnostico.id_diagnostico == producto.id_diagnosticos ? "selected" : ""}>${diagnostico.codigo} - ${diagnostico.nombre}</option>`;
        });

        optionsSelectViasAdministracion = `<option value="">--Seleccionar--</option>`;
        viasAdministracion.forEach((viaAdministracion) => {
          optionsSelectViasAdministracion += `<option value="${viaAdministracion.id}"${viaAdministracion.id == producto.id_via_administracion ? "selected" : ""}>${viaAdministracion.descripcion}</option>`;
        });

        optionsSelectObservaciones = `<option value="">--Seleccionar--</option>`;
        observaciones.forEach((observacion) => {
          optionsSelectObservaciones += `<option value="${observacion.id}"${observacion.id == producto.id_observacion ? "selected" : ""}>${observacion.text}</option>`;
        });

        newRow = `<tr id="merv_tr_productos_${index}">
                    <td><span>${producto.codigo} - ${producto.nombre}</span></td>
                    <td><input type="text" name="merv_stock_establecimiento" id="merv_stock_establecimiento_${index}" class="form-control input-sm input-readonly" value="${producto.stock_establecimiento}" readonly></td>
                    <td><input type="text" name="merv_stock_farmacia" id="merv_stock_farmacia_${index}" class="form-control input-sm input-readonly" value="${producto.stock_farmacia}" readonly></td>
                    <td>
                      <input type="number" name="merv_tiempo_periodo" id="merv_tiempo_periodo_${index}" class="form-control input-sm" value="${producto.tiempo_periodo}"
                        min="0" step="1" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                      >
                    </td>
                    <td>
                      <select 
                        class="form-control input-sm"
                        name="merv_id_via_administracion"
                        id="merv_id_via_administracion_${index}"
                        ${parseInt(typeRecipeId) === typesRecipe.DIGITAL ? "disabled" : ""}
                      >
                         ${optionsSelectViasAdministracion}
                      </select>
                    </td>
                    <td>
                      <select 
                        class="form-control input-sm"
                        name="merv_id_diagnostico_producto"
                        id="merv_id_diagnostico_producto_${index}"
                        ${parseInt(typeRecipeId) === typesRecipe.DIGITAL ? "disabled" : ""}
                      >
                        ${optionsSelectDiagnosticos}
                      </select>
                    </td>
                    <td><input type="text" name="merv_posologia" id="merv_posologia_${index}" class="form-control input-sm" value="${producto.posologia}"></td>
                    <td>
                      <input type="number" name="merv_cantidad_prescrita" id="merv_cantidad_prescrita_${index}" class="form-control input-sm" value="${producto.cantidad_prescrita}"
                        min="0" step="1" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                      >
                    </td>
                    <td>
                      <input type="number" name="merv_cantidad_dispensada" id="merv_cantidad_dispensada_${index}" class="form-control input-sm" value="${producto.cantidad_dispensada}"
                        min="0" step="1" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                      >
                    </td>
                    <td>
                      <select class="form-control input-sm" name="merv_id_observacion" id="merv_id_observacion_${index}">
                        ${optionsSelectObservaciones}
                      </select>
                    </td>
                    <td><input type="hidden" name="merv_id_producto_receta" id="merv_id_producto_receta_${index}" value="${producto.id_producto_receta}"></td>
                    <td><input type="hidden" name="merv_id_producto" id="merv_id_producto_${index}" value="${producto.id_producto}"></td>
                  </tr>`;
        $("#mervTablePrescripcion tbody").append(newRow);

        form.push({
          name: `merv_tiempo_periodo_${index}`,
          enable_edit:
            parseInt(typeRecipeId) === typesRecipe.DIGITAL ? false : true,
          required:
            parseInt(typeRecipeId) === typesRecipe.DIGITAL ? false : true,
          tr_id: `merv_tr_productos_${index}`,
        });
        form.push({
          name: `merv_id_via_administracion_${index}`,
          enable_edit:
            parseInt(typeRecipeId) === typesRecipe.DIGITAL ? false : true,
          required:
            parseInt(typeRecipeId) === typesRecipe.DIGITAL ? false : true,
          tr_id: `merv_tr_productos_${index}`,
        });
        form.push({
          name: `merv_id_diagnostico_producto_${index}`,
          enable_edit:
            parseInt(typeRecipeId) === typesRecipe.DIGITAL ? false : true,
          required:
            parseInt(typeRecipeId) === typesRecipe.DIGITAL ? false : true,
          tr_id: `merv_tr_productos_${index}`,
        });
        form.push({
          name: `merv_posologia_${index}`,
          enable_edit:
            parseInt(typeRecipeId) === typesRecipe.DIGITAL ? false : true,
          required:
            parseInt(typeRecipeId) === typesRecipe.DIGITAL ? false : true,
          tr_id: `merv_tr_productos_${index}`,
        });
        form.push({
          name: `merv_cantidad_prescrita_${index}`,
          enable_edit:
            parseInt(typeRecipeId) === typesRecipe.DIGITAL ? false : true,
          required:
            parseInt(typeRecipeId) === typesRecipe.DIGITAL ? false : true,
          tr_id: `merv_tr_productos_${index}`,
        });
        form.push({
          name: `merv_cantidad_dispensada_${index}`,
          enable_edit: true,
          required: true,
          tr_id: `merv_tr_productos_${index}`,
        });
        form.push({
          name: `merv_id_observacion_${index}`,
          enable_edit: true,
          required: false,
          tr_id: `merv_tr_productos_${index}`,
        });
      });
    }
  }

  function getViasAdministracion() {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: `/api/via-administracion`,
        type: "GET",
        dataType: "json",
      })
        .done(function (response) {
          resolve(response);
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
          reject({ jqXHR, textStatus, errorThrown });
        });
    });
  }

  function getConsultorios() {
    const farmacia_id = window.APP_CONFIG.idFarmacia;
    return new Promise((resolve, reject) => {
      $.ajax({
        url: `/api/consultorios/farmacia/${farmacia_id}`,
        type: "GET",
        dataType: "json",
      })
        .done(function (response) {
          resolve(response);
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
          reject({ jqXHR, textStatus, errorThrown });
        });
    });
  }

  function getUpss(consultorio_id, tipo_atencion) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: `/obtener_upss/${consultorio_id}/${tipo_atencion}`,
        type: "GET",
        dataType: "json",
      })
        .done(function (response) {
          resolve(response);
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
          reject({ jqXHR, textStatus, errorThrown });
        });
    });
  }

  function getObservaciones() {
    const ID_FARMACIA = window.APP_CONFIG.idFarmacia;
    if (ID_FARMACIA == 128) {
      return OBSERVACIONES.farmacia128;
    }
    let opciones = [...OBSERVACIONES.default];
    if (ID_FARMACIA == 18) {
      opciones = opciones.concat(OBSERVACIONES.farmacia18Extra);
    }
    return opciones;
  }

  function getTurnos() {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: `/api/turnos`,
        type: "GET",
        dataType: "json",
      })
        .done(function (response) {
          resolve(response);
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
          reject({ jqXHR, textStatus, errorThrown });
        });
    });
  }

  function getMedicos(turno, establecimiento_id, consultorio_id, farmacia_id) {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: [19, 128].includes(parseInt(farmacia_id)) ? `/api/medicos/establecimiento/${establecimiento_id}/consultorio/${consultorio_id}` : `/api/medicos/turno/${turno}/establecimiento/${establecimiento_id}/consultorio/${consultorio_id}`,
        type: "GET",
        dataType: "json",
      })
        .done(function (response) {
          resolve(response);
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
          reject({ jqXHR, textStatus, errorThrown });
        });
    });
  }

  function onClickAddRowTableDiagnosticos() {
    const lastRowValid = validateLastRowTable("mervTableDiagnosticos");
    if (!lastRowValid) {
      Swal.fire({
        icon: "warning",
        title: "<p>MÓDULO DE GESTIÓN DE RECETAS</p>",
        html: '<p class="descripcion-alert">TIENE QUE RELLENAR TODO LOS CAMPOS PARA PODER AGREGAR UN NUEVO DIAGNOSTICO</p>',
      });
      return null;
    }
    const indexRow = $("#mervTableDiagnosticos tbody tr").length + 1;

    let optionsTipos = "<option value=''>--Seleccionar--</option>";
    listTiposDiagnostico.forEach((opcion) => {
      optionsTipos += `<option value="${opcion.id}">${opcion.value}</option>`;
    });

    const id_prestacion = $("#merv_id_prestacion").val();

    const newRow = `<tr id="merv_tr_diagnosticos_${indexRow}">
                      <td><input type="text" name="merv_diagnostico" id="merv_diagnostico_${indexRow}" class="form-control input-sm"></td>
                      <td>
                        <select class="form-control input-sm" name="merv_id_tipo_diagnostico" id="merv_id_tipo_diagnostico_${indexRow}">
                          ${optionsTipos}
                        </select>
                      </td>
                      <td><button id="mervBtnRemoveRowDiagnosticos" type="button" style="margin-left: 10px" class="btn btn-sm btn-danger btn"><i class="fa fa-trash"></i></button></td>
                      <td><input type="hidden" name="merv_id_diagnostico" id="merv_id_diagnostico_${indexRow}"></td>
                      <td><input type="hidden" name="merv_id_receta_diagnostico" id="merv_id_receta_diagnostico_${indexRow}"></td>
                      <td><input type="hidden" name="merv_id_prestacion" id="merv_id_prestacion_${indexRow}" value="${id_prestacion}"></td>
                    </tr>`;

    $("#mervTableDiagnosticos tbody").append(newRow);
    $("#mervTableDiagnosticosHead")
      .removeClass("table-error danger")
      .nextAll(".label-help")
      .remove();
    const sexo =
      String($("#merv_sexo_beneficiario").val()).toUpperCase() === "M" ? 1 : 2;

    $(`#merv_diagnostico_${indexRow}`).autocomplete({
      minLength: 1,
      delay: 400,
      source: function (request, response) {
        $.ajax({
          url: `/obtener_diagnostico_genero/${request.term}/${sexo}`,
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
        const isRepeated = validateRepeatedRowTable(
          "mervTableDiagnosticos",
          "merv_id_diagnostico",
          idItem,
        );
        if (!isRepeated) {
          $(this).val(ui.item.label);
          $(`#merv_id_diagnostico_${indexRow}`).val(idItem);
          addItemListDiagnosticos({
            diagnostico_id: idItem,
            nombre_diagnostico: ui.item.label,
          });
        } else {
          Swal.fire({
            icon: "warning",
            title: "<p>NOTA DE INGRESO DE ENFERMERÍA</p>",
            html: '<p class="descripcion-alert">EL DIAGNÓSTICO YA FUE AGREGADO, NO SE PERMITEN DIAGNÓSTICO REPETIDOS</p>',
          });
          $(this).val("");
          $(`#merv_diagnostico_${indexRow}`).val("");
          $(`#merv_id_diagnostico_${indexRow}`).val("");
          event.preventDefault();
        }
      },
    });

    $("#mervTableDiagnosticos").on(
      "click",
      "#mervBtnRemoveRowDiagnosticos",
      function () {
        const row = $(this).closest("tr");
        const rowId = row.attr("id");
        const diagnosticoId = row
          .find('input[name="merv_id_diagnostico"]')
          .val();
        removeItemListDiagnosticos(diagnosticoId);
        form = form.filter((obj) => String(obj.tr_id) !== String(rowId));
        row.remove();
      },
    );

    form.push({
      id: indexRow,
      name: `merv_diagnostico_${indexRow}`,
      required: true,
      tr_id: `merv_tr_diagnosticos_${indexRow}`,
    });
    form.push({
      id: indexRow,
      name: `merv_id_tipo_diagnostico_${indexRow}`,
      required: true,
      tr_id: `merv_tr_diagnosticos_${indexRow}`,
    });
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
      if ($(this).val() === "" || $(this).val() == 0) {
        markInputError($(this).attr("id"));
        isValid = false;
      }
    });
    return isValid;
  }

  function validateRepeatedRowTable(TableId, InputName, idRowSearch) {
    let isRepeated = false;
    $(`#${TableId} tbody tr`).each(function () {
      const idRow = $(this).find(`input[name='${InputName}']`).val();
      if (idRow && idRowSearch && parseInt(idRow) === parseInt(idRowSearch)) {
        isRepeated = true;
        return false;
      }
    });
    return isRepeated;
  }

  function addItemListDiagnosticos(newDiagnosticos) {
    listDiagnosticos.push(newDiagnosticos);
    actualizarSelectDiagnosticos();
  }

  function removeItemListDiagnosticos(diagnosticoId) {
    listDiagnosticos = listDiagnosticos.filter(
      (item) => parseInt(item.diagnostico_id) !== parseInt(diagnosticoId),
    );
    actualizarSelectDiagnosticos();
  }

  function generarOptionsDiagnosticos() {
    let options = "<option value=''>--Seleccionar--</option>";
    listDiagnosticos.forEach((opcion) => {
      options += `<option value="${opcion.diagnostico_id}">${opcion.nombre_diagnostico}</option>`;
    });
    return options;
  }

  function actualizarSelectDiagnosticos() {
    const options = generarOptionsDiagnosticos();

    $('select[name="merv_id_diagnostico_producto"]').each(function () {
      const selected = $(this).val();
      $(this).html(options);
      $(this).val(selected);
    });
  }

  function clearInputError(nameInput) {
    const inputElement = $(`#${nameInput}`);
    const parentDiv = inputElement.closest(".form-group, td");
    inputElement.siblings(".form-control-feedback, .label-help").remove();
    parentDiv.removeClass("has-error has-success");
  }

  function markInputError(nameInput, messsageError = null) {
    const inputElement = $(`#${nameInput}`);
    const parentDiv = inputElement.closest(".form-group, td");
    parentDiv.addClass("has-error");
    inputElement.after(
      `<span class="label-help">${messsageError || "campo requerido"}</span>`,
    );
  }

  function showHistoryRecipes() {
    const dni = $("#merv_nro_doc_ident").val();
    const mesActual = moment().month() + 1;
    $.ajax({
      url: `/obtenerRecetasMesActualByBeneficiario/${dni}`,
      type: "GET",
      success: function (result) {
        let html = "";
        let filaGrupo = 1;
        let nroAtencion = 1;
        result.forEach((item) => {
          const mesExpedicion = parseInt(item.fecha_registro.split("-")[1], 10);
          const rowspan = item.colspan ? `rowspan="${item.colspan}"` : "";
          const botonDetalle = `<button type="button" class="btn btn-primary btn-xs btn-det-atencion" data-toggle="modal" data-id="${item.id_receta_vale}">
                                  <i class="fa fa-list"></i>
                                </button>`;
          html += `<tr>
                    <td><small>${item.nro_receta}</small></td>
                    <td><small>${item.fecha_expedicion}</small></td>
                    <td><small>${item.fecha_registro}</small></td>
                    <td><small>${item.nom_consultorio}</small></td>
                    <td><small>${item.nom_farmacia}</small></td>
                    <td><small>${item.apepat_medico} ${item.apemat_medico} ${item.nom_medico}</small></td>
                    <td><small>${item.nom_diagnostico}</small></td>`;
          if (filaGrupo === 1) {
            const valorAtencion =
              mesActual > mesExpedicion ? "-" : nroAtencion++;
            html += `<td class="text-center" ${rowspan} style="vertical-align: middle">
                      <small>${valorAtencion}</small>
                    </td>`;
          }
          html += ` <td class="text-center" style="vertical-align: middle">
                      ${botonDetalle}
                    </td>
                  </tr>`;
          filaGrupo = filaGrupo === item.colspan ? 1 : filaGrupo + 1;
        });
        $("#muestra-atenciones").html(html);
      },
    });
    $("#showAtencionesMensual").modal("show");
  }

  function showProductsOnHistoryRecipe(idRecetaVale) {
    $.ajax({
      url: `/obtenerProductobyRecetasVale/${idRecetaVale}`,
      type: "GET",
      success: function (result) {
        let html = "";
        result.forEach((item) => {
          html += `<tr>
                    <td class="text-center"><small>${item.codigo}</small></td>
                    <td><small>${item.nombre}</small></td>
                    <td class="text-center"><small>${item.abreviatura}</small></td>
                    <td class="text-center"><small>${item.cantidad_prescrita}</small></td>
                    <td class="text-center"><small>${item.cantidad_dispensada}</small></td>
                  </tr>`;
        });
        $("#muestra-productos").html(html);
      },
    });
    $("#showProductosReceta").modal("show");
  }

  function getValuesRowByTable(tableName) {
    const rows = $(`#${tableName} tbody tr`);
    const resultados = [];
    rows.each(function () {
      let valores = {};
      $(this)
        .find("input, select")
        .each(function () {
          let name = $(this).attr("name");
          if (!name) return;
          name = name.replace(/^merv_/, "");
          name = toCamelCase(name);
          valores[name] = $(this).val();
        });
      resultados.push(valores);
    });
    return resultados;
  }

  function toCamelCase(str) {
    return str.replace(/_([a-z])/g, (_, letra) => letra.toUpperCase());
  }

  function buildRequestSave() {
    const request = {
      prestacionId: $("#merv_id_prestacion").val(),
      recetaValeId: $("#merv_id_receta_vale").val(),
      sexoBeneficiario: $("#merv_sexo_beneficiario").val(),
      tipoAtencionId: $("#merv_id_tipo_atencion").val(),
      nroReceta: $("#merv_nro_receta").val(),
      anioReceta: $("#merv_anio_receta").val(),
      fechaExpedicion: $("#merv_fecha_expedicion").val(),
      fechaExpiracion: $("#merv_fecha_expiracion").val(),
      nroGuia: $("#merv_nro_guia").val(),
      interconsulta: $("#merv_interconsulta").val(),
      interconsulta_dias: $("#merv_diastto").val(),
      fechaDispensacion: $("#merv_fecha_dispensacion").val(),
      tipoDocIdent: $("#merv_tipo_doc_ident").val(),
      nroDocIdent: $("#merv_nro_doc_ident").val(),
      apellidosNombres: $("#merv_apellidos_nombres").val(),
      parentesco: $("#merv_parentesco").val(),
      nroHc: $("#merv_nro_hc").val(),
      grado: $("#merv_grado").val(),
      servicios: $("#merv_servicios").val(),
      upss: $("#merv_upss").val(),
      turno: $("#merv_turno").val(),
      medico: $("#merv_medico").val(),
      typeRecipeId: $("#merv_type_recipe").val(),
      diagnosticos: getValuesRowByTable("mervTableDiagnosticos") || [],
      medicamentos: getValuesRowByTable("mervTablePrescripcion") || [],
    };

    return request;
  }

  function onSubmitForm() {
    const formValid = validateForm();
    if (!formValid) {
      Swal.fire({
        icon: "error",
        title:
          "<p style='font-size: 16px; font-weight: bold;'>Campos requeridos incompletos</p>",
        html: `<div style='padding: 10px 20px; font-size: 18px; text-align: center;'>
                <p>Por favor, complete los campos obligatorios marcados con <span style="color: #dc3545;">*</span> para continuar.</p>
              </div>`,
        confirmButtonText: "OK",
      });
      return null;
    }
    const body = buildRequestSave();
    $("#mervBtnSubmit").prop("disabled", true);
    $.ajax({
      url: "/api/receta-vale/updated",
      type: "PUT",
      dataType: "json",
      data: body,
      beforeSend: function () {
        Swal.fire({
          title: "Procesando solicitud",
          text: "Estamos atendiendo la intervención. Por favor, espera...",
          allowOutsideClick: false,
          allowEscapeKey: false,
          didOpen: () => Swal.showLoading(),
        });
      },
    })
      .done(function (response) {
        if (response && response.data) {
          Swal.fire({
            icon: "success",
            title: "GESTIÓN DE RECETAS",
            text: response.message,
            confirmButtonText: "Aceptar",
          });
          $("#modalEditRecetaVale").modal("hide");
          clearFormAndTables();
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        let mensajeError =
          "Ocurrió un error al procesar la solicitud. Por favor, inténtalo nuevamente.";
        if (jqXHR.responseJSON && jqXHR.responseJSON.mensaje) {
          mensajeError = jqXHR.responseJSON.mensaje;
        }
        Swal.fire({
          icon: "error",
          title: "No se pudo completar la operación",
          text: mensajeError,
          confirmButtonText: "Entendido",
        });
      })
      .always(function () {
        $("#mervBtnSubmit").prop("disabled", false);
      });
  }

  function validateForm() {
    let controlsError = [];
    for (const control of form) {
      if (control.tipo_receta) {
        const farmaciaId = parseInt($("#farmacia_id").val(), 10);
        if (!control.tipo_receta.includes(farmaciaId)) {
          continue;
        }
      }
      if (!control.required) continue;
      const inputElement = $(`#${control.name}`);
      const select2Container = getSelect2Container(inputElement);
      if (select2Container) {
        select2Container.nextAll(".label-help").remove();
      } else {
        inputElement.nextAll(".label-help").remove();
      }

      let parentDiv = null;
      if (control.tr_id) {
        parentDiv = inputElement.closest("td");
      } else {
        parentDiv = inputElement.closest(".form-group");
      }
      if (!parentDiv || parentDiv.length === 0) continue;
      const value = inputElement.val();
      const isSelect2 = !!select2Container;
      // if (value === "" || value === null || value === undefined || value == 0) {
      if (value === "" || value === null || value === undefined) {
        parentDiv.addClass("has-error");

        if (isSelect2) {
          select2Container.after(
            '<span class="label-help">campo requerido</span>',
          );
          const $selVis = select2Container
            .find(".select2-selection, .select2-selection--single")
            .first();
          if ($selVis && $selVis.length) {
            $selVis.addClass("select2-error");
          } else {
            select2Container.addClass("select2-error");
          }
        } else {
          inputElement.after('<span class="label-help">campo requerido</span>');
          inputElement.addClass("input-error");
        }

        controlsError.push(control);
      } else {
        parentDiv.removeClass("has-error");

        if (isSelect2) {
          select2Container.nextAll(".label-help").remove();
          select2Container
            .find(".select2-selection, .select2-selection--single")
            .removeClass("select2-error");
          select2Container.removeClass("select2-error");
        } else {
          inputElement.nextAll(".label-help").remove();
          inputElement.removeClass("input-error");
        }
      }
    }

    // tu validación de tablas (dejé tal cual)
    for (const table of tables) {
      $(`#${table.id}`)
        .removeClass("table-error danger")
        .nextAll(".label-help")
        .remove();
      const rowsFaTableCirujano = $(`#${table.id} tbody tr`);
      if (table.minLength > 0 && rowsFaTableCirujano.length === 0) {
        $(`#${table.headId}`)
          .addClass("table-error danger")
          .after(`<span class="label-help">${table.messageEmpty}</span>`);
        controlsError.push({ name: table.id });
      } else {
        $(`#${table.headId}`)
          .removeClass("table-error danger")
          .nextAll(".label-help")
          .remove();
      }
    }

    for (const controlError of controlsError) {
      if (controlError.name === "faTableCirujano") {
        errorFaTableCirujano = true;
        break;
      }
    }

    focusFirstInputError(controlsError);
    return controlsError.length === 0;
  }

  function getSelect2Container($select) {
    // intento 1: normalmente está justo después
    let $c = $select.nextAll("span.select2").first();
    if ($c && $c.length) return $c;

    // intento 2: algunos temas usan .select2-container
    $c = $select.nextAll(".select2-container").first();
    if ($c && $c.length) return $c;

    // intento 3: buscar en el parent .form-group (por si el DOM se reorganizó)
    $c = $select.closest(".form-group").find("span.select2").first();
    if ($c && $c.length) return $c;

    // intento 4: buscar por atributo aria-hidden (Select2 lo usa en algunos casos)
    $c = $select.siblings("span.select2[aria-hidden]").first();
    if ($c && $c.length) return $c;

    return null;
  }

  function focusFirstInputError(errors = []) {
    if (errors && errors.length > 0) {
      const controFirst = errors[0];
      $(`#${controFirst.name}`).focus();

      if ($(`#${controFirst.name}`).data("select2")) {
        $(`#${controFirst.name}`).select2("open");
      }
    }
  }

  function clearFormAndTables() {
    isClearingForm = true;
    form.forEach((field) => {
      if (field.tipo_receta) {
        const farmaciaId = parseInt($("#farmacia_id").val(), 10);
        if (!field.tipo_receta.includes(farmaciaId)) {
          $(`#${field.name}`).parent().hide();
          return;
        }
      }
      const $el = $(`#${field.name}`);
      if (!$el.length) return;
      if (field.is_select2) {
        $el.val(null).trigger("change");
      } else {
        $el.val("");
      }
      $el.removeClass("is-invalid is-valid");
    });
    tables.forEach((table) => {
      const $tbody = $(`#${table.id} tbody`);
      if ($tbody.length) {
        $tbody.empty();
      }
    });
    isClearingForm = false;
    form = [
      {
        name: "merv_id_tipo_atencion",
        enable_edit: true,
        required: true,
        is_select2: true,
      },
      { name: "merv_nro_receta", enable_edit: true, required: true },
      { name: "merv_anio_receta", enable_edit: false, required: false },
      { name: "merv_fecha_expedicion", enable_edit: true, required: true },
      { name: "merv_fecha_expiracion", enable_edit: true, required: true },
      { name: "merv_fecha_dispensacion", enable_edit: false, required: false },
      { name: "merv_tipo_doc_ident", enable_edit: false, required: false },
      { name: "merv_nro_doc_ident", enable_edit: false, required: false },
      { name: "merv_apellidos_nombres", enable_edit: false, required: false },
      { name: "merv_parentesco", enable_edit: false, required: false },
      {
        name: "merv_interconsulta",
        enable_edit: true,
        required: true,
        is_select2: true,
        tipo_receta: [128],
      },
      {
        name: "merv_diastto",
        enable_edit: false,
        required: true,
        tipo_receta: [128],
      },
      {
        name: "merv_nro_guia",
        enable_edit: true,
        required: true,
        tipo_receta: [128],
      },
      { name: "merv_nro_hc", enable_edit: false, required: true },
      {
        name: "merv_grado",
        enable_edit: false,
        required: false,
        is_select2: false,
      },
      {
        name: "merv_servicios",
        enable_edit: true,
        required: true,
        is_select2: true,
      },
      {
        name: "merv_upss",
        enable_edit: true,
        required: true,
        is_select2: true,
      },
      {
        name: "merv_turno",
        enable_edit: false,
        required: false,
        is_select2: false,
      },
      {
        name: "merv_medico",
        enable_edit: true,
        required: true,
        is_select2: true,
      },
    ];
  }
});
