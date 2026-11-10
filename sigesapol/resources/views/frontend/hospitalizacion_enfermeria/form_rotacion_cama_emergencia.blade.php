@extends('frontend.layouts.master3')
@section('renderjs')
<style>
  .form-control {
    height: 28px;
    text-align: left !important;
  }

  .form-group {
    margin-bottom: 10px;
  }

  .btn-toggle {
    margin: 0 4rem;
    padding: 0;
    position: relative;
    border: none;
    height: 1.5rem;
    width: 3rem;
    border-radius: 1.5rem;
    color: #6b7381;
    background: #bdc1c8;
  }
  .btn-toggle:focus,
  .btn-toggle.focus,
  .btn-toggle:focus.active,
  .btn-toggle.focus.active {
    outline: none;
  }
  .btn-toggle:before,
  .btn-toggle:after {
    line-height: 1.5rem;
    width: 4rem;
    text-align: center;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }
  .btn-toggle:before {
    /* content: 'NO'; */
    left: -4rem;
  }
  .btn-toggle:after {
    /* content: 'SI'; */
    right: -4rem;
    opacity: 0.5;
  }
  .btn-toggle>.handle {
    position: absolute;
    top: 0.1875rem;
    left: 0.1875rem;
    width: 1.125rem;
    height: 1.125rem;
    border-radius: 1.125rem;
    background: #fff;
    transition: left 0.25s;
  }
  .btn-toggle.active {
    transition: background-color 0.25s;
  }
  .btn-toggle.active>.handle {
    left: 1.6875rem;
    transition: left 0.25s;
  }
  .btn-toggle.active:before {
    opacity: 0.5;
  }
  .btn-toggle.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-sm:before,
  .btn-toggle.btn-sm:after {
    line-height: -0.5rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.4125rem;
    width: 2.325rem;
  }
  .btn-toggle.btn-sm:before {
    text-align: right;
  }
  .btn-toggle.btn-sm:after {
    text-align: left;
    opacity: 0;
  }
  .btn-toggle.btn-sm.active:before {
    opacity: 0;
  }
  .btn-toggle.btn-sm.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-xs:before,
  .btn-toggle.btn-xs:after {
    display: none;
  }
  .btn-toggle:before,
  .btn-toggle:after {
    color: #6b7381;
  }
  .btn-toggle.active {
    background-color: #29b5a8;
  }
  .btn-toggle.btn-lg {
    margin: 0 5rem;
    padding: 0;
    position: relative;
    border: none;
    height: 2.5rem;
    width: 5rem;
    border-radius: 2.5rem;
  }
  .btn-toggle.btn-lg:focus,
  .btn-toggle.btn-lg.focus,
  .btn-toggle.btn-lg:focus.active,
  .btn-toggle.btn-lg.focus.active {
    outline: none;
  }
  .btn-toggle.btn-lg:before,
  .btn-toggle.btn-lg:after {
    line-height: 2.5rem;
    width: 5rem;
    text-align: center;
    font-weight: 600;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }
  .btn-toggle.btn-lg:before {
    /* content: 'NO'; */
    left: -5rem;
  }
  .btn-toggle.btn-lg:after {
   /*  content: 'SI'; */
    right: -5rem;
    opacity: 0.5;
  }
  .btn-toggle.btn-lg>.handle {
    position: absolute;
    top: 0.3125rem;
    left: 0.3125rem;
    width: 1.875rem;
    height: 1.875rem;
    border-radius: 1.875rem;
    background: #fff;
    transition: left 0.25s;
  }
  .btn-toggle.btn-lg.active {
    transition: background-color 0.25s;
  }
  .btn-toggle.btn-lg.active>.handle {
    left: 2.8125rem;
    transition: left 0.25s;
  }
  .btn-toggle.btn-lg.active:before {
    opacity: 0.5;
  }
  .btn-toggle.btn-lg.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-lg.btn-sm:before,
  .btn-toggle.btn-lg.btn-sm:after {
    line-height: 0.5rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.6875rem;
    width: 3.875rem;
  }
  .btn-toggle.btn-lg.btn-sm:before {
    text-align: right;
  }
  .btn-toggle.btn-lg.btn-sm:after {
    text-align: left;
    opacity: 0;
  }
  .btn-toggle.btn-lg.btn-sm.active:before {
    opacity: 0;
  }
  .btn-toggle.btn-lg.btn-sm.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-lg.btn-xs:before,
  .btn-toggle.btn-lg.btn-xs:after {
    display: none;
  }
  .btn-toggle.btn-sm {
    margin: 0 0.5rem;
    padding: 0;
    position: relative;
    border: none;
    height: 1.5rem;
    width: 3rem;
    border-radius: 1.5rem;
  }
  .btn-toggle.btn-sm:focus,
  .btn-toggle.btn-sm.focus,
  .btn-toggle.btn-sm:focus.active,
  .btn-toggle.btn-sm.focus.active {
    outline: none;
  }
  .btn-toggle.btn-sm:before,
  .btn-toggle.btn-sm:after {
    line-height: 1.5rem;
    width: 0.5rem;
    text-align: center;
    font-weight: 600;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }
  .btn-toggle.btn-sm:before {
    content: 'NO';
    left: -0.5rem;
  }
  .btn-toggle.btn-sm:after {
    content: 'SI';
    right: -0.5rem;
    opacity: 0.5;
  }
  .btn-toggle.btn-sm>.handle {
    position: absolute;
    top: 0.1875rem;
    left: 0.1875rem;
    width: 1.125rem;
    height: 1.125rem;
    border-radius: 1.125rem;
    background: #fff;
    transition: left 0.25s;
  }
  .btn-toggle.btn-sm.active {
    transition: background-color 0.25s;
  }
  .btn-toggle.btn-sm.active>.handle {
    left: 1.6875rem;
    transition: left 0.25s;
  }
  .btn-toggle.btn-sm.active:before {
    opacity: 0.5;
  }
  .btn-toggle.btn-sm.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-sm.btn-sm:before,
  .btn-toggle.btn-sm.btn-sm:after {
    line-height: -0.5rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.4125rem;
    width: 2.325rem;
  }
  .btn-toggle.btn-sm.btn-sm:before {
    text-align: right;
  }
  .btn-toggle.btn-sm.btn-sm:after {
    text-align: left;
    opacity: 0;
  }
  .btn-toggle.btn-sm.btn-sm.active:before {
    opacity: 0;
  }
  .btn-toggle.btn-sm.btn-sm.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-sm.btn-xs:before,
  .btn-toggle.btn-sm.btn-xs:after {
    display: none;
  }
  .btn-toggle.btn-xs {
    margin: 0 0;
    padding: 0;
    position: relative;
    border: none;
    height: 1rem;
    width: 2rem;
    border-radius: 1rem;
  }
  .btn-toggle.btn-xs:focus,
  .btn-toggle.btn-xs.focus,
  .btn-toggle.btn-xs:focus.active,
  .btn-toggle.btn-xs.focus.active {
    outline: none;
  }
  .btn-toggle.btn-xs:before,
  .btn-toggle.btn-xs:after {
    line-height: 1rem;
    width: 0;
    text-align: center;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }
  .btn-toggle.btn-xs:before {
    content: 'NO';
    left: 0;
  }
  .btn-toggle.btn-xs:after {
    content: 'SI';
    right: 0;
    opacity: 0.5;
  }
  .btn-toggle.btn-xs>.handle {
    position: absolute;
    top: 0.125rem;
    left: 0.125rem;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 0.75rem;
    background: #fff;
    transition: left 0.25s;
  }
  .btn-toggle.btn-xs.active {
    transition: background-color 0.25s;
  }
  .btn-toggle.btn-xs.active>.handle {
    left: 1.125rem;
    transition: left 0.25s;
  }
  .btn-toggle.btn-xs.active:before {
    opacity: 0.5;
  }
  .btn-toggle.btn-xs.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-xs.btn-sm:before,
  .btn-toggle.btn-xs.btn-sm:after {
    line-height: -1rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.275rem;
    width: 1.55rem;
  }
  .btn-toggle.btn-xs.btn-sm:before {
    text-align: right;
  }
  .btn-toggle.btn-xs.btn-sm:after {
    text-align: left;
    opacity: 0;
  }
  .btn-toggle.btn-xs.btn-sm.active:before {
    opacity: 0;
  }
  .btn-toggle.btn-xs.btn-sm.active:after {
    opacity: 1;
  }
  .btn-toggle.btn-xs.btn-xs:before,
  .btn-toggle.btn-xs.btn-xs:after {
    display: none;
  }
  .btn-toggle.btn-secondary {
    color: #6b7381;
    background: #bdc1c8;
  }
  .btn-toggle.btn-secondary:before,
  .btn-toggle.btn-secondary:after {
    color: #6b7381;
  }
  .btn-toggle.btn-secondary.active {
    background-color: #ff8300;
  }
  
  .input-error {
    border: 1px solid #dc3545 !important;
    border-radius: 5px;
  }

  .input-hint {
    margin-top: 5px;
    color: #dc3545;
    font-size: 11px;
  }

  label {
    color: #2c3e50;
    font-size: 11px;
  }
  
</style>
<script>
  const formTraslacionCama = [
    // { name: 'prioridad_traslacion', type: "select", required: true },
    // { name: 'fecha_traslacion', type: "date", required: true },
    // { name: 'hora_traslacion', type: "time", required: true },
    // { name: 'area_traslacion', type: "select", required: true },
    // { name: 'cama_traslacion', type: "select", required: true },
    // { name: 'medico_traslacion', type: "select", required: true }
  ];
  const formRotacionCama = [
    // { name: 'prioridad_rotacion', type: "select", required: true },
    // { name: 'area_rotacion', type: "select", required: true },
    { name: 'hora_rotacion', type: "time", required: true },
    { name: 'fecha_rotacion', type: "date", required: true },
    { name: 'cama_rotacion', type: "select", required: true },
  ];

  $(document).ready(function() {

    // const fechaInput = document.getElementById("fecha_rotacion");
    // const today = new Date();
    // const formattedCurrentDate = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

    // fechaInput.addEventListener("change", function (event) {
    //   const fechaInput = this.value;
    //   if (fechaInput < formattedCurrentDate) {
    //     event.preventDefault();
    //     this.value = ''
    //     bootbox.alert("No puedes seleccionar una fecha anterior a hoy.");
    //   }
    // });

    

    const estado = parseInt($("#estadoRegistro").val());
    if(estado >= 5) {
      $('a[href="#tabRotacionCama1"]').on('click', function (e) {
        e.preventDefault(); // Bloquea el clic
        e.stopPropagation(); // Evita propagación de eventos
      });
      $('a[href="#tabRotacionCama1"]').prop('title', 'El paciente ya se encuentra de alta, no se puede registrar esta informacion.');
      $('a[href="#tabRotacionCama1"]').removeClass('active');
      $('a[href="#tabRotacionCama1"]').addClass('disabled');
      $('a[href="#tabRotacionCama2"]').tab('show');
    }

    // $('#fecha_traslacion').datepicker({
    //   language: "es", 
    //   format: "dd/mm/yyyy", 
    //   autoclose: true,  
    //   endDate: new Date(),
    // });
    $('#fecha_rotacion').datepicker({
      language: "es", 
      format: "dd/mm/yyyy", 
      autoclose: true,
      endDate: new Date(),
    });
    $("#fecha_rotacion").datepicker("setDate", moment().format("DD/MM/YYYY"));
    $("#hora_rotacion").val(moment().format("HH:mm"));

    // $("#area_traslacion").select2({ width: '100%' }); 
    // $("#cama_traslacion").select2({ width: '100%' }); 
    // $("#medico_traslacion").select2({ width: '100%' }); 

    $("#pabellon_rotacion").select2({ width: '100%' });
    // $("#sala_destino_rotacion").select2({ width: '100%' });
    $("#cama_rotacion").select2({width: '100%' });
    // $("#section_rotacion_cama").css("display", "none");

    // $('#area_traslacion').on('select2:select', async (e) => {      
    //   const object = e.params.data;
    //   resetSelect("cama_traslacion");

    //   if (object && object.id && parseInt(object.id) > 0) {
    //     const result = await obtenerCamasPorArea(object.id);
    //     buildOptionsCamasTraslacion(result);
    //   }
    // });

    $('#tabs a').on('shown.bs.tab', function (e) {
        if ($(this).attr('href') === "#tabRotacionCama2"){
          showHistoryChangeBeds();
        }
    });
  });

  // $('#toogle_traslacion_cama').on('click', function() {
  //   const toogleValue = String($(this).attr("aria-pressed")) === "false" ? true : false;
  //   handleSectionCollapse(toogleValue, "section_traslacion_cama");
  //   handleSectionCollapse(!toogleValue, "section_rotacion_cama");
  //   handleToogleBtn(!toogleValue, "toogle_rotacion_cama");
  //   clearErrorsForm();
  // });
  
  $('#toogle_rotacion_cama').on('click', function() {
    const toogleValue = String($(this).attr("aria-pressed")) === "false" ? true : false;
    handleSectionCollapse(toogleValue, "section_rotacion_cama");
    handleSectionCollapse(!toogleValue, "section_traslacion_cama");
    // handleToogleBtn(!toogleValue, "toogle_traslacion_cama");
    clearErrorsForm();
  });

  function handleToogleBtn(valueToogle, nameToogle) {
    if (valueToogle) {
      $(`#${nameToogle}`).addClass('active');
      $(`#${nameToogle}`).attr('aria-pressed', 'true');
    } else {
      $(`#${nameToogle}`).removeClass('active');
      $(`#${nameToogle}`).attr('aria-pressed', 'false');
    }
  }

  function handleSectionCollapse(valueToogle, nameCollapse) {
    if (valueToogle) {
      $(`#${nameCollapse}`).slideDown("slow");
    } else {
      $(`#${nameCollapse}`).slideUp("slow");
    }
  }

  function clearErrorsForm() {
    for (const formItem of formTraslacionCama) {
      $(`#${formItem.name}`).nextAll('.input-hint').remove();
      if (formItem.type === "select") {
        $(`#${formItem.name}`).next('.select2-container').removeClass('input-error');
      } else {
        $(`#${formItem.name}`).removeClass('input-error');
      }
    }
    for (const formItem of formRotacionCama) {
      $(`#${formItem.name}`).nextAll('.input-hint').remove();
      if (formItem.type === "select") {
        $(`#${formItem.name}`).next('.select2-container').removeClass('input-error');
      } else {
        $(`#${formItem.name}`).removeClass('input-error');
      }
    }
  }

  function onClickCancel() {
    window.location.href = "/emergencia_by_enfermeria";
  }

  function onSubmit() {
    const resultValidate = validateForm();
    if (resultValidate) {
      addInputsTooglesOnForm();
      $("#formRotacionCama").submit();
    }
  }

  function addInputsTooglesOnForm(params) {
    // $('#traslacion_cama').remove();
    $('#rotacion_cama').remove();
    // const toogle_traslacion_cama = $("#toogle_traslacion_cama").attr("aria-pressed");
    const toogle_rotacion_cama = $("#toogle_rotacion_cama").attr("aria-pressed");
    // const toogle_traslacion_cama_input = $('<input>').attr('type', 'hidden').attr('name', 'traslacion_cama').val(toogle_traslacion_cama);
    const toogle_rotacion_cama_input = $('<input>').attr('type', 'hidden').attr('name', 'rotacion_cama').val(toogle_rotacion_cama);
    // $('#formRotacionCama').append(toogle_traslacion_cama_input);
    $('#formRotacionCama').append(toogle_rotacion_cama_input);
  }

  function validateForm() {
    clearErrorsForm();

    let controlsErros = 0;
    let resultValidate = false;

    // const toogleTraslacionCama = String($("#toogle_traslacion_cama").attr("aria-pressed")) === "true" ? true : false;
    // if (toogleTraslacionCama) {
    //   for (const formTraslacionItem of formTraslacionCama) {
    //     resultValidate = validateInput(formTraslacionItem)
    //     if (!resultValidate) {
    //       controlsErros++;
    //     }
    //   }
    // }
    
    const toogleRotacionCama = String($("#toogle_rotacion_cama").attr("aria-pressed")) === "true" ? true : false;
    if (toogleRotacionCama) {
      for (const formRotacionItem of formRotacionCama) {
        resultValidate = validateInput(formRotacionItem)
        if (!resultValidate) {
          controlsErros++;
        }
      }
    }
    return controlsErros <= 0 ? true : false
  }

  function validateInput(control) {
    const valueInput = $(`#${control.name}`).val();
    if (!valueInput || valueInput == 0) {
      markInputOnError(control, "Este campo es obligatorio.");
      return false;
    }
    return true;
  }

  function markInputOnError(control, messageError) {
    if (control.type === "select") {
      $(`#${control.name}`).next('.select2-container').addClass('input-error');
      $(`#${control.name}`).next('.select2-container').after(`<span class="input-hint">${messageError}</span>`);
    } else {
      $(`#${control.name}`).addClass('input-error');
      $(`#${control.name}`).after(`<span class="input-hint">${messageError}</span>`);
      
    }
  }

  function obtenerCamasPorArea(id_cuarto){ 
    const id_establecimiento = $("#id_establecimiento").val();
    return new Promise((resolve, reject) => {
      $.ajax({
        url: `/get_camas_by_idestablecimiento_nombretipoatencion_idcuarto/${id_establecimiento}/EMERGENCIA/${id_cuarto}`,
        success: function(result) {
          resolve(result);
        },
        error: function(xhr, status, error) {
          reject(error);
          console.log(xhr.responseText);
        }
      });
    })
  }

  function buildOptionsCamasTraslacion(listaCamas) {
    $("#cama_traslacion").prop("disabled", false);
    $('#cama_traslacion').empty();  
    $('#cama_traslacion').append("<option value='0'>-Seleccione-</option>"); 
    if (listaCamas && listaCamas.length > 0) {
      for (const item of listaCamas) {
        $('#cama_traslacion').append("<option value='" + item.id + "'>" + item.nombre + "</option>");
      }
      $('#cama_traslacion').val("0").change(); 
      if (listaCamas.length === 1) {
        $('#cama_traslacion').val(listaCamas[0].id).change(); 
      }
    }
  }

  function resetSelect(nameSelect) {
    $(`#${nameSelect}`).val("0").change(); 
    $(`#${nameSelect}`).prop("disabled", true);
  }

  function showHistoryChangeBeds() {
    const emergencia = {!! json_encode($emergencia) !!}
    
    const renderTableHistory = $('#historyTable').DataTable();
    if ($.fn.DataTable.isDataTable('#historyTable') ) {
      $('#historyTable').DataTable().destroy();
      $('#historyTable tbody').empty();
    }

    /* if(emergencia.id_prestacion) {
      $('#historyTable').DataTable( {
        processing: true,
        serverSide: true,
        paging: false,
        searching: false,
        ajax: {
          url: `/api/cambio-cama/prestacion/${emergencia.id_prestacion}`,
          type: 'GET',
          dataSrc: function (json) {
            return json;
          }
        },
        columns: [
          { data: 'fecha_cambio' },
          { data: 'hora_cambio' },
          { data: 'cuarto_origen_nombre' },
          {
            data: null,
            render: function (data, type, row) {
              if (parseInt(row.estado_ubicacion) === 3) {
                return `${row.cuarto_destino_nombre || ""}`;
              }
              return "-";
            },
          },
          { 
            data: null,
            render: function (data, type, row) {
              if (parseInt(row.estado_ubicacion) === 3) {
                return `${row.medico_dni || ""} - ${row.medico_nombre || ""} ${row.medico_paterno || ""} ${row.medico_materno || ""}`;
              }
              return "-";
            },
          },
          { data: 'cambio_cama_tipo_nombre' },
          { data: 'cama_destino_nombre' }
        ]
      });
    } */
   
    $('#historyTable').DataTable( {
        processing: true,
        serverSide: true,
        paging: false,
        searching: false,
        ajax: {
          url: `/api/cambio-cama/ext-id/${emergencia.id_ext_hosp}`,
          type: 'GET',
          dataSrc: function (json) {
            console.log("[json] =>", json);
            return json;
          }
        },
        columns: [
          { data: 'fecha_cambio' },
          { data: 'hora_cambio' },
          { data: 'cuarto_origen_nombre' },
          // {
          //   data: null,
          //   render: function (data, type, row) {
          //     if (parseInt(row.estado_ubicacion) === 3) {
          //       return `${row.cuarto_destino_nombre || ""}`;
          //     }
          //     return "-";
          //   },
          // },
          // { 
          //   data: null,
          //   render: function (data, type, row) {
          //     if (parseInt(row.estado_ubicacion) === 3) {
          //       return `${row.medico_dni || ""} - ${row.medico_nombre || ""} ${row.medico_paterno || ""} ${row.medico_materno || ""}`;
          //     }
          //     return "-";
          //   },
          // },
          { data: 'cambio_cama_tipo_nombre' },
          { data: 'cama_destino_nombre' },
          { 
            data: null,
            render: function (data, type, row) {
              return `
                <div>
                  <span><b>Dni: </b>${row.medico_dni || ""}</span><br>
                  <span><b>Nombres y Apellidos: </b>${row.medico_nombre || ""} ${row.medico_paterno || ""} ${row.medico_materno || ""}</span>	
                </div>
              `;
            },
          },
        ]
      });
  }

  $("#pabellon_rotacion").change(function (e) { 
    e.preventDefault();
    let id_pabellon = $(this).val();
    let id_establecimiento = $('#id_establecimiento').val();
    $('#cama_rotacion').val('').trigger('change'); 
    $('#cama_rotacion').prop('disabled', true);
    $('#cama_rotacion').empty();
    if (id_pabellon != "0") {
      $.ajax({
        url: `/get_camas_by_idestablecimiento_nombretipoatencion_idpabellon/${id_establecimiento}/HOSPITALIZACION/${id_pabellon}`,
        success: function(result){
          if (result && result.length > 0) {
            let newOption = "<option value='0'>--SELECCIONAR--</option>";
            if (result.length === 1) {
              newOption += "<option value='"+result[0].id+"' selected>"+result[0].nombre+"</option>";
            } else {
              $(result).each(function (ii, oo) {
                  newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
              });
            }
            $('#cama_rotacion').html(newOption).change();
            $('#cama_rotacion').prop('disabled', false);
            $('#cama_rotacion').focus();
          }
        }
      });
    }
  });

  // $("#sala_destino_rotacion").change(function (e) { 
  //   e.preventDefault();
  //   let id_servicio_destino = $(this).val();
  //   $('#cama_rotacion').prop('disabled', true);
  //   $('#cama_rotacion').val('').trigger('change'); 
  //   $('#cama_rotacion').empty();
  //   // $("#div_cama_destino").show();
  //   if (id_servicio_destino != "0") {
  //     let id_establecimiento = $('#id_establecimiento').val();
  //     if (id_servicio_destino != "0" && id_servicio_destino != null) {
  //       $.ajax({
  //         url: `/get_camas_by_idestablecimiento_nombretipoatencion_idcuarto_activos/${id_establecimiento}/HOSPITALIZACION/${id_servicio_destino}`,
  //         success: function(result){
  //       console.log("camas: ", result);
  //           let newOption = "<option value='0'>--SELECCIONAR--</option>";
  //           $(result).each(function (ii, oo) {
  //             newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
  //           });
  //           $('#cama_rotacion').html(newOption);
  //           $('#cama_rotacion').prop('disabled', false);
  //           $("#cama_rotacion").val('0').trigger("change");
  //         }
  //       });
  //     }
  //   } else {
  //     $("#div_cama_destino").hide();
  //   }
  // });

  
</script>
@endsection
@section('content')
<?php 
?>
<section>
  <input type="hidden" value="{{$emergencia->estado}}" name="estadoRegistro" id="estadoRegistro">
  <div class="container" style="width: 90%">
    <div class="panel with-nav-tabs panel-default">
      <div class="panel-heading">
        <ul id="tabs" class="nav nav-tabs">
          <li class="active"><a href="#tabRotacionCama1" data-toggle="tab" style="font-size:12px">NUEVA ROTACION DE UNA CAMA</a></li>
          {{-- <li class="active"><a href="#tabRotacionCama1" data-toggle="tab" style="font-size:12px">NUEVO TRASLADO O ROTACION DE UNA CAMA</a></li> --}}
          <li><a href="#tabRotacionCama2" data-toggle="tab" style="font-size:12px">HISTORIAL DE ROTACION DE UNA CAMA</a></li>
          {{-- <li><a href="#tabRotacionCama2" data-toggle="tab" style="font-size:12px">HISTORIAL DE TRASLADO O ROTACION DE UNA CAMA</a></li> --}}
        </ul>
      </div>
      <div class="panel-body">
        <div class="tab-content">
          <div class="tab-content">

            <div role="tabpanel" class="tab-pane fade in active" id="tabRotacionCama1">
              <div class="panel panel-primary filterable">
              <div class="panel-heading">
                <h3 class="panel-title" style="padding-top: 5px;">MODULO PARA ROTACIÓN DE CAMAS - PACIENTES EN HOSPITALIZACION</h3>
              </div>
              <div class="panel-body">
                <form id="formRotacionCama" action="{{'/enfermeria/registrar-rotacion-camas-hospi'}}" method="post">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{$emergencia->id_establecimiento}}">
                  <input type="hidden" name="id_hospi" id="id_hospi" value="{{$emergencia->id_ext_hosp}}">
                  {{-- <input type="hidden" name="id_prestacion" id="id_prestacion" value="{{$emergencia->id_prestacion}}"> --}}
                  <input type="hidden" name="id_tipo_atencion" id="id_tipo_atencion" value="3">
                  <input type="hidden" name="cama_id" id="cama_id" value="{{$emergencia->cama_id}}">
          
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>INFORMACIÓN DEL PACIENTE</b>
                      </div>
                    </div>
          
                    <div class="panel-body">
                      <div class="row">
                        <div class="form-group col-12 col-sm-6 col-md-6 col-lg-2 col-xl-2">
                          <label for="nro_doc_ident">DOCUMENTO DE IDENTIDAD</label>
                          <input type="text" class="form-control input-sm" name="nro_doc_ident"  id="nro_doc_ident" value="{{$emergencia->nro_doc_ident}}" readonly >
                        </div>
          
                        <div class="form-group col-12 col-sm-6 col-md-6 col-lg-2 col-xl-2">
                          <label for="asegurado_paterno">APELLIDO PATERNO</label>
                          <input type="text" class="form-control input-sm" name="asegurado_paterno" id="asegurado_paterno" value="{{$emergencia->asegurado_paterno}}" readonly>
                        </div>
                        
                        <div class="form-group col-12 col-sm-6 col-md-6 col-lg-2 col-xl-2">
                          <label for="asegurado_materno">APELLIDO MATERNO</label>
                          <input type="text" class="form-control input-sm" name="asegurado_materno" id="asegurado_materno" value="{{$emergencia->asegurado_materno}}" readonly> 
                        </div>
          
                        <div class="form-group col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                          <label for="asegurado_nombre">NOMBRES</label>
                          <input type="text" class="form-control input-sm" name="asegurado_nombre" id="asegurado_nombre" value="{{$emergencia->asegurado_nombre}}" readonly> 
                        </div>
          
                        <div class="form-group col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                          <label for="tipo_beneficiario_descripcion">PARENTESCO</label>
                          <input type="text" class="form-control input-sm" name="tipo_beneficiario_descripcion" id="tipo_beneficiario_descripcion" value="{{$emergencia->tipo_beneficiario_descripcion}}" readonly> 
                        </div>
          
                        <div class="form-group col-12 col-sm-6 col-md-3 col-lg-1 col-xl-1">
                          <label for="asegurado_fecha_nac">F NACIMIENTO</label>
                          <input type="text" class="form-control input-sm" name="asegurado_fecha_nac" id="asegurado_fecha_nac" value="{{$emergencia->asegurado_fecha_nac}}" readonly> 
                        </div>
          
                        @if (isset($emergencia->id_tipo_beneficiario) && intval($emergencia->id_tipo_beneficiario) === 8)
                          <div class="form-group col-12 col-sm-6 col-md-3 col-lg-1 col-xl-1">
                            <label for="asegurado_cip">CIP</label>
                            <input type="text" class="form-control input-sm" name="asegurado_cip" id="asegurado_cip" value="{{$emergencia->asegurado_cip}}" readonly> 
                          </div>
          
                          <div class="form-group col-12 col-sm-6 col-md-3 col-lg-2 col-xl-2">
                            <label for="asegurado_grado">GRADO</label>
                            <input type="text" class="form-control input-sm" name="asegurado_grado" id="asegurado_grado" value="{{$emergencia->asegurado_grado}}" readonly> 
                          </div>
          
                          <div class="form-group col-12 col-sm-6 col-md-3 col-lg-2 col-xl-2">
                            <label for="asegurado_situacion_paciente">SITUACIÓN</label>
                            <input type="text" class="form-control input-sm" name="asegurado_situacion_paciente" id="asegurado_situacion_paciente" value="{{$emergencia->asegurado_situacion_paciente}}" readonly> 
                          </div>
                        @endif
          
                        <div class="form-group col-12 col-sm-6 col-md-3 col-lg-1 col-xl-1">
                          <label for="asegurado_sexo">SEXO</label>
                          <input type="text" class="form-control input-sm" name="asegurado_sexo" id="asegurado_sexo" value="{{strval($emergencia->asegurado_sexo) === "M" ? "MASCULINO" : "FEMENINO"}}" readonly> 
                        </div>
          
                        <div class="form-group col-12 col-sm-6 col-md-3 col-lg-1 col-xl-1">
                          <label for="asegurado_edad">EDAD</label>
                          <input type="text" class="form-control input-sm" name="asegurado_edad" id="asegurado_edad" value="{{$emergencia->asegurado_edad}}" readonly> 
                        </div>
          
                        <div class="form-group col-12 col-sm-6 col-md-3 col-lg-1 col-xl-1">
                          <label for="asegurado_tipo_sangre_descripcion_corta">T.SANGRE</label>
                          <input type="text" class="form-control input-sm" name="asegurado_tipo_sangre_descripcion_corta" id="asegurado_tipo_sangre_descripcion_corta" value="{{$emergencia->asegurado_tipo_sangre_descripcion_corta}}" readonly> 
                        </div>
          
                        <div class="form-group col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                          <label for="asegurado_direccion_nacimiento">DIRECCIÓN</label>
                          <input type="text" class="form-control input-sm" name="asegurado_direccion_nacimiento" id="asegurado_direccion_nacimiento" value="{{$emergencia->asegurado_direccion_nacimiento}}" readonly> 
                        </div>
                      </div>
          
                      @if (isset($emergencia->id_tipo_beneficiario) && intval($emergencia->id_tipo_beneficiario) !== 8)
                        <p style="font-weight: bold; margin: 30px 0px -10px 0px">DATOS DEL TITULAR</p>
                        <hr style="border-top: 1px solid #606060"> 
                        <div class="row">
                          <div class="form-group col-12 col-sm-6 col-md-2 col-lg-1 col-xl-1">
                            <label for="dni_titular">DOC.TITULAR</label>
                            <input type="text" class="form-control input-sm" name="dni_titular" id="dni_titular" value="{{$emergencia->dni_titular}}" readonly> 
                          </div>
          
                          <div class="form-group col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <label for="nombre_titular">TITULAR</label>
                            <input type="text" class="form-control input-sm" name="nombre_titular" id="nombre_titular" value="{{$emergencia->nombre_titular . " " . $emergencia->paterno_titular . " " . $emergencia->materno_titular }}" readonly> 
                          </div>
          
                          <div class="form-group col-12 col-sm-6 col-md-3 col-lg-2 col-xl-2">
                            <label for="cip_titular">CIP</label>
                            <input type="text" class="form-control input-sm" name="cip_titular" id="cip_titular" value="{{$emergencia->cip_titular}}" readonly> 
                          </div>
          
                          <div class="form-group col-12 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                            <label for="grado_titular">GRADO</label>
                            <input type="text" class="form-control input-sm" name="grado_titular" id="grado_titular" value="{{$emergencia->grado_titular}}" readonly> 
                          </div>
          
                          <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                            <label for="situacion_paciente_titular">SITUACIÓN</label>
                            <input type="text" class="form-control input-sm" name="situacion_paciente_titular" id="situacion_paciente_titular" value="{{$emergencia->situacion_paciente_titular}}" readonly> 
                          </div>
          
                          <div class="form-group col-12 col-sm-6 col-md-2 col-lg-1 col-xl-1">
                            <label for="telefono_titular">TELÉFONO</label>
                            <input type="text" class="form-control input-sm" name="telefono_titular" id="telefono_titular" value="{{$emergencia->telefono_titular}}" readonly> 
                          </div>
                        </div>
                      @endif
          
                      <p style="font-weight: bold; margin: 30px 0px -10px 0px">DATOS DEL ACOMPAÑANTE</p>
                      <hr style="border-top: 1px solid #606060"> 
                      <div class="row">
                        <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                          <label for="tipo_parentesco">CONDUCIDO POR</label>
                          <input type="text" class="form-control input-sm" name="tipo_parentesco" id="tipo_parentesco" value="{{$emergencia->tipo_parentesco}}" readonly>
                        </div>
          
                        @if (isset($emergencia->id_tipo_parentesco) && intval($emergencia->id_tipo_parentesco) !== 8)
                          <div class="form-group col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                            <label for="dni_conducido">DOCUMENTO DE IDENTIDAD</label>
                            <input type="text" class="form-control input-sm" name="dni_conducido" id="dni_conducido" value="{{$emergencia->dni_conducido}}" readonly> 
                          </div>
          
                          <div class="form-group col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                            <label for="nombre_conducido">NOMBRES</label>
                            <input type="text" class="form-control input-sm" name="nombre_conducido" id="nombre_conducido" value="{{$emergencia->nombre_conducido}}" readonly> 
                          </div>
          
                          <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                            <label for="telf_conducido">TELEFONO</label>
                            <input type="text" class="form-control input-sm" name="telf_conducido" id="telf_conducido" value="{{$emergencia->telf_conducido}}" readonly> 
                          </div> 
                        @endif
                      </div>
                    </div>
                  </div>
          
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>INFORMACIÓN DEL RESPONSABLE DE ATENCIÓN</b>
                      </div>
                    </div>
          
                    <div class="panel-body">
                      <div class="row">
          
                        <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                          <label for="medico_dni">DOCUMENTO DE IDENTIDAD</label>
                          <input type="text" class="form-control input-sm" name="medico_dni" id="medico_dni" value="{{$emergencia->medico_dni}}" readonly>
                        </div>
          
                        <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                          <label for="medico_paterno">APELLIDO PATERNO</label>
                          <input type="text" class="form-control input-sm" name="medico_paterno" id="medico_paterno" value="{{$emergencia->medico_paterno}}" readonly>
                        </div>
                        
                        <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                          <label for="medico_materno">APELLIDO MATERNO</label>
                          <input type="text" class="form-control input-sm" name="medico_materno" id="medico_materno" value="{{$emergencia->medico_materno}}" readonly> 
                        </div>
          
                        <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                          <label for="medico_nombre">NOMBRES</label>
                          <input type="text" class="form-control input-sm" name="medico_nombre" id="medico_nombre" value="{{$emergencia->medico_nombre}}" readonly> 
                        </div>
          
                        <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                          <label for="especializacion_nombre">ESPECIALIDAD</label>
                          <input type="text" class="form-control input-sm" name="especializacion_nombre" id="especializacion_nombre" value="{{$emergencia->especializacion_nombre}}" readonly> 
                        </div>
          
                        <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                          <label for="profesion_nombre">PROFESION</label>
                          <input type="text" class="form-control input-sm" name="profesion_nombre" id="profesion_nombre" value="{{$emergencia->profesion_nombre}}" readonly> 
                        </div>
          
                        <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                          <label for="medico_colegiatura">N° COLEGIATURA</label>
                          <input type="text" class="form-control input-sm" name="medico_colegiatura" id="medico_colegiatura" value="{{$emergencia->medico_colegiatura}}" readonly> 
                        </div>
          
                        <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                          <label for="medico_rne">N° RNE</label>
                          <input type="text" class="form-control input-sm" name="medico_rne" id="medico_rne" value="{{$emergencia->medico_rne}}" readonly> 
                        </div>
          
                      </div>
                    </div>
                  </div>
          
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>INFORMACIÓN ESTADO PACIENTE (PRIORIDAD)</b>
                      </div>
                    </div>
          
                    <div class="panel-body">
                      <div class="row">      
                        <div class="form-group col-12 col-sm-6 col-md-3 col-lg-2 col-xl-2">
                          <label for="origen_consultorio_nombre">ÁREA ORIGEN</label>
                          <input type="text" class="form-control input-sm" name="origen_consultorio_nombre" id="origen_consultorio_nombre" value="{{$emergencia->cuarto_origen == null ? $emergencia->cuartos_nombre : $emergencia->cuarto_origen}}" readonly>
                        </div>
                        
                        <div class="form-group col-12 col-sm-6 col-md-3 col-lg-2 col-xl-2">
                          <label for="fecha_registro_ingreso">FECHA INICIO</label>
                          <input type="text" class="form-control input-sm" name="fecha_registro_ingreso" id="fecha_registro_ingreso" value="{{$emergencia->fecha_registro_ingreso}}" readonly> 
                        </div>
          
                        <div class="form-group col-12 col-sm-6 col-md-3 col-lg-2 col-xl-2">
                          <label for="hora_registro_ingreso">HORA</label>
                          <input type="text" class="form-control input-sm" name="hora_registro_ingreso" id="hora_registro_ingreso" value="{{$emergencia->hora_registro_ingreso}}" readonly> 
                        </div>
          
                        <div class="form-group col-12 col-sm-6 col-md-3 col-lg-2 col-xl-2">
                          <label for="destino_consultorio_nombre">ÁREA DESTINO</label>
                          <input type="text" class="form-control input-sm" name="destino_consultorio_nombre" id="destino_consultorio_nombre" value="{{$pabellon->nombre.' - '.$emergencia->cuartos_nombre}}" readonly> 
                        </div>
                        
                        <div class="form-group col-12 col-sm-6 col-md-3 col-lg-2 col-xl-2">
                          <label for="cama">CAMA / CAMILLA</label>
                          <input type="text" class="form-control input-sm" name="cama" id="cama" value="{{$emergencia->cama}}" readonly> 
                        </div>
                        
                      </div>
                    </div>
                  </div>
          
                  {{-- <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-6 col-sm-5 col-md-3 col-lg-2 col-xl-2">
                          <label>TRASLACIÓN DE CAMA</label>
                        </div>
                        <div class="col-6 col-sm-7 col-md-9 col-lg-10 col-xl-10">
                          <button 
                            type="button" 
                            id="toogle_traslacion_cama"
                            class="btn btn-lg btn-toggle active" 
                            data-toggle="button"
                            aria-pressed="true">
                              <div class="handle"></div>
                          </button>
                        </div>
                      </div>
                      <div id="section_traslacion_cama">
                        <hr>
                        <div class="row">
                          <div class="form-group col-12 col-sm-6 col-md-4 col-lg-2 col-xl-2">
                            <label for="fecha_traslacion">FECHA</label>
                            <input 
                              class="form-control input-sm datepicker" 
                              type="text" 
                              id="fecha_traslacion" 
                              name="fecha_traslacion" 
                              placeholder="01-01-2020" 
                              style="background-color: #ffffff"
                              readonly>
                          </div>
            
                          <div class="form-group col-12 col-sm-6 col-md-4 col-lg-2 col-xl-2">
                            <label for="hora_traslacion">HORA</label>
                            <input 
                              class="form-control input-sm" 
                              type="time" 
                              id="hora_traslacion" 
                              name="hora_traslacion" 
                              placeholder="00:00:00" >
                          </div>
            
                          <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                            <label for="area_traslacion">ÁREA TRASLADO</label>
                            <select class="form-control input-sm" name="area_traslacion" id="area_traslacion">
                              <option value="0" selected> -Seleccionar- </option>
                              @if (isset($cuartos) && count($cuartos) > 0)
                                  @foreach ($cuartos as $item)
                                    <option value="{{$item->id}}">{{$item->nombre}}</option>
                                  @endforeach
                              @endif
                            </select>
                          </div>
            
                          <div class="form-group col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                            <label for="cama_traslacion">CAMA / CAMILLA</label>
                              <select class="form-control input-sm" name="cama_traslacion" id="cama_traslacion" disabled>
                              <option value="0" selected> -Seleccionar-</option>
                              @if (isset($camas) && count($camas) > 0)
                                  @foreach ($camas as $item)
                                    <option value="{{$item->id}}">{{$item->nombre}}</option>
                                  @endforeach
                              @endif
                            </select>
                          </div>
                        </div>
                        <div class="row" style="margin-top: 20px">
                          <div class="col-12 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                            <label for="medico_traslacion">MEDICO QUE AUTORIZA EL TRASLADO: </label>
                          </div>
                          <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                            <select class="form-control input-sm" name="medico_traslacion" id="medico_traslacion">
                              <option value="0" selected> -Seleccionar-</option>
                              @if (count($medicos) > 0)
                                  @foreach ($medicos as $row)
                                      <option value="{{$row->id}}">{{ $row->dni }} - {{ $row->nombre }} {{ $row->paterno }} {{ $row->materno }}</option>
                                  @endforeach
                              @endif
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div> --}}
          
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-6 col-sm-5 col-md-3 col-lg-2 col-xl-2">
                          <label>ROTACIÓN DE CAMA</label>
                        </div>
                        <div class="col-6 col-sm-7 col-md-9 col-lg-10 col-xl-10">
                          <button 
                              type="button" 
                              id="toogle_rotacion_cama"
                              class="btn btn-lg btn-toggle active" 
                              data-toggle="button"
                              aria-pressed="true">
                              <div class="handle"></div>
                          </button>
                        </div>
                      </div>
          
                      <div id="section_rotacion_cama">
                        <hr>
                        <div class="row">
                          <div class="form-group col-12 col-sm-6 col-md-6 col-lg-2 col-xl-2">
                            <label for="fecha_rotacion">FECHA</label>
                            <input 
                            class="form-control input-sm datepicker" 
                            type="text" 
                            id="fecha_rotacion" 
                            name="fecha_rotacion" 
                            placeholder="01-01-2020" 
                            style="background-color: #ffffff"
                            readonly>
                          </div>
                          <div class="form-group col-12 col-sm-6 col-md-4 col-lg-2 col-xl-2">
                            <label for="hora_rotacion">HORA</label>
                            <input 
                              class="form-control input-sm" 
                              type="time" 
                              id="hora_rotacion" 
                              name="hora_rotacion" 
                              placeholder="00:00:00" >
                          </div>
                        </div>
                        {{-- <div class="row">
                          <div class="form-group col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                            <label for="fecha_rotacion">FECHA</label>
                            <input 
                              class="form-control input-sm datepicker" 
                              type="text" 
                              id="fecha_rotacion" 
                              name="fecha_rotacion" 
                              placeholder="01-01-2020" 
                              style="background-color: #ffffff"
                              readonly>
                          </div>
                          <div class="form-group col-12 col-sm-6 col-md-4 col-lg-2 col-xl-2">
                            <label for="hora_rotacion">HORA</label>
                            <input 
                              class="form-control input-sm" 
                              type="time" 
                              id="hora_rotacion" 
                              name="hora_rotacion" 
                              placeholder="00:00:00" >
                          </div>
                          <div class="form-group col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                            <label for="cama_rotacion">CAMA</label>
                            <select class="form-control input-sm" name="cama_rotacion" id="cama_rotacion">
                              <option value="0" selected> -Seleccionar-</option>
                              @if (isset($camas) && count($camas) > 0)
                                  @foreach ($camas as $item)
                                    <option value="{{$item->id}}">{{$item->nombre}}</option>
                                  @endforeach
                              @endif
                            </select>
                          </div>
                        </div> --}}
                        <div class="row">
                          <div class="form-group col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                            <label for="upss_cargo">UPSS A CARGO</label>
                            <input 
                              class="form-control input-sm" 
                              type="text" 
                              id="upss_cargo" 
                              name="upss_cargo" 
                              value="{{$emergencia->upss_encargado}}"
                              readonly>
                          </div>
                          <div class="form-group col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                            <label for="departamento_cargo">DEPARTAMENTO A CARGO</label>
                            <input 
                              class="form-control input-sm" 
                              type="text" 
                              id="departamento_cargo" 
                              name="departamento_cargo"
                              value="{{$emergencia->consultorio}}"
                              readonly>
                          </div>
                          <div class="form-group col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                            <label for="pabellon_rotacion">PABELLÓN/PISO</label>
                            <select class="form-control input-sm" name="pabellon_rotacion" id="pabellon_rotacion">
                              <option value="0" selected> -Seleccionar-</option>
                              @if (isset($pabellones) && count($pabellones) > 0)
                                  @foreach ($pabellones as $pabellon)
                                    <option value="{{$pabellon->id}}">{{$pabellon->pabellon}}</option>
                                  @endforeach
                              @endif
                            </select>
                          </div>
                          {{-- <div class="form-group col-12 col-sm-6 col-md-6 col-lg-2 col-xl-2">
                            <label for="sala_destino_rotacion">SERVICIO O SALA DESTINO</label>
                            <select class="form-control input-sm" name="sala_destino_rotacion" id="sala_destino_rotacion" disabled></select>
                          </div> --}}
                          <div class="form-group col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                            <label for="cama_rotacion">CAMA</label>
                            <select class="form-control input-sm" name="cama_rotacion" id="cama_rotacion" disabled>
                              {{-- <option value="0" selected> -Seleccionar-</option>
                              @if (isset($camas) && count($camas) > 0)
                                  @foreach ($camas as $item)
                                    <option value="{{$item->id}}">{{$item->nombre}}</option>
                                  @endforeach
                              @endif --}}
                            </select>
                          </div>  
                        </div>
                      </div>
                    </div>
                  </div>
          
                  <br>
                  <div class="row">
                    <div class="form-group col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      <button type="button" class="btn btn-danger btn-block" onclick="onClickCancel()">CANCELAR</button>
                    </div>
                    <div class="form-group col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      <button type="button" class="btn btn-success btn-block" onclick="onSubmit()">GUARDAR</button>
                    </div>
                  </div>
                  <br>
          
              </form>
              </div>
              </div>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="tabRotacionCama2">
              <table id="historyTable" class="table table-striped" style="width: 100%;">
                <thead>
                  <tr>
                    <th style="width: 10%">FECHA</th>
                    <th style="width: 5%">HORA</th>
                    <th style="width: 20%">ÁREA ORIGEN</th>
                    {{-- <th>ÁREA TRASLADO</th>
                    <th>MEDICO QUE AUTORIZO EL TRASLADO</th> --}}
                    <th style="width: 15%">MOVIMIENTO DE UNA CAMA </th>
                    <th style="width: 15%">CAMA / CAMILLA</th>
                    <th style="width: 35%">RESPONSABLE</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


</section>
@include('frontend.includes.footer')
@endsection