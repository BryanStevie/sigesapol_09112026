{{-- Receta / examen de laboratorio / examen de imagenología (copiado de formulario_atencion_nuevo) --}}
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::style("plugins/jQuery/jquery-ui.css") !!}
<style>
/*===============TABLA IMAGENOLOGÍA RESPONSIVE INICIO=====================================*/
#tblExamenRad {
    width: 100%;
    table-layout: auto; 
    border-collapse: collapse;
}

#tblExamenRad th,
#tblExamenRad td {
    white-space: nowrap; 
    text-align: center; 
    padding: 8px;
}

#tblExamenRad thead th {
    vertical-align: middle;
}

#fsRecetaVale .panel-body,
#fsExamenLab .panel-body,
#fsExamenRad .panel-body {
    overflow-x: auto;
}
/*===============TABLA IMAGENOLOGÍA RESPONSIVE FIN=====================================*/

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
    content: 'NO';
    left: -4rem;
  }

  .btn-toggle:after {
    content: 'SI';
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
    content: 'NO';
    left: -5rem;
  }

  .btn-toggle.btn-lg:after {
    content: 'SI';
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

  /* RUA Laboratorio Checklist (copiado de formulario_atencion) */
  #modalDX .modal-dialog {
    width: auto;
  }

  .modal-col1 .modal-dialog {
    max-width: 450px !important;
  }

  .modal-col2 .modal-dialog {
    max-width: 950px !important;
  }

  .modal-col3 .modal-dialog {
    max-width: 1200px !important;
  }

  .modal-col4 .modal-dialog {
    max-width: 1400px !important;
  }

  #contenedorDX.col1 { grid-template-columns: 350px; }
  #contenedorDX.col2 { grid-template-columns: repeat(2, 350px); }
  #contenedorDX.col3 { grid-template-columns: repeat(3, 320px); }
  #contenedorDX.col4 { grid-template-columns: repeat(4, 300px); }

  #contenedorDX {
    display: grid;
    justify-content: center;
    gap: 10px;
    max-height: calc((50px + 10px) * 10);
    overflow-y: auto;
    padding-right: 10px;
  }
</style>
<?php
  $pp_diagnosticos = [];
  foreach ($procedimientos as $p) {
    if (!empty($p->id_diagnostico) && !isset($pp_diagnosticos[$p->id_diagnostico])) {
      $pp_diagnosticos[$p->id_diagnostico] = ['id' => intval($p->id_diagnostico), 'codigo' => $p->diagnostico_codigo, 'nombre' => $p->diagnostico];
    }
  }
?>
<script>
  var procedimientosSeleccionados = [];
  var listIdDiagnosticoObjetos = {!! json_encode(array_values($pp_diagnosticos)) !!};
  var filasmedicamentos = 0;

  function soloNumerosMenosCero(e) {
    var key = window.Event ? e.which : e.keyCode;
    return ((key >= 48 && key <= 57) || key == 0 || key == 8)
  }

  function validproductoseleccionado(id_farmacia, btn) {
    var dni_beneficiario = $('#dni_beneficiario').val();
    var nro_dias = 30;
    var objTr = $(btn).closest("tr");
    var id_producto = objTr.find(".cmbMedicamento").val();
    $.ajax({
      url: '/validacion_beneficiario_dias_by_producto_medico/' + dni_beneficiario + '/' + nro_dias + '/' + id_producto,
      success: function (result) {
        var msg = "";
        var n = 0;
        for (var key in result) {
          if (n == 0) {
            msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:42px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario " + (result[key].paterno_beneficiario) + " " + (result[key].materno_beneficiario) + ", " + (result[key].nombre_beneficiario) + " con DNI " + (result[key].dni_beneficiario) + " tiene receta con el mismo producto</h1>";
            msg += "<table class='table table-list-search' style='font-size: 11px !important;' >";
            msg += "<thead>";
            msg += "<tr>";
            msg += "<th>Farmacia</th>";
            msg += "<th>Nro. Receta</th>";
            msg += "<th>Fecha Expedicion</th>";
            msg += "<th>Producto</th>";
            msg += "<th>Tiempo(Dias)</th>";
            msg += "<th>Dias Transcurridos</th>";
            msg += "<th>Dias Pendientes</th>";
            msg += "<th>Cantidad Prescrita</th>";
            msg += "</tr>";
            msg += "</thead>";
            msg += "<tbody>";
          }

          msg += "<tr><td>" + (result[key].farmacia) + "</td>";
          msg += "<td>" + (result[key].nro_receta) + "</td>";
          msg += "<td>" + (result[key].fecha_expedicion) + "</td>";
          msg += "<td>" + (result[key].codigo) + " - " + (result[key].producto) + "</td>";
          msg += "<td>" + (result[key].tiempo_periodo) + "</td>";
          msg += "<td>" + (result[key].dias_transcurridos) + "</td>";
          msg += "<td>" + (result[key].tiempo_periodo - result[key].dias_transcurridos) + "</td>";
          msg += "<td>" + (result[key].cantidad_prescrita) + "</td></tr>";
          n++;
        }
        if (msg != "") {
          bootbox.confirm({
            title: "Alerta",
            message: msg,
            buttons: {
              cancel: {
                label: '<i class="fa fa-times"></i> Cancelar'
              },
              confirm: {
                label: '<i class="fa fa-check"></i> Continuar'
              }
            },
            callback: function (result) {
              if (result == true && typeof verlotes === 'function') {
                verlotes(id_farmacia, btn);
              }
            }
          });
        }
      }
    });
  }

  const validaExamenRadiologiaSeleccionado = (id_examen_rad) => {
    let dni_beneficiario = $('#dni_beneficiario').val();
    $.ajax({
      url: '/validacion_beneficiario_dias_por_examen_rad/' + dni_beneficiario + '/' + id_examen_rad,
      success: function (result) {
        let msg = "";
        let n = 0;
        if (result.length > 0) {
          for (let key of result) {
            if (n == 0) {
              msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:20px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario " + key.paterno_beneficiario + " " + key.materno_beneficiario + ", " + key.nombre_beneficiario + " con DNI " + key.dni + " tiene análisis de Imagenes pendientes del examen seleccionado en los últimos 3 meses, CANTIDAD : " + key.total_registros + "</h1>";
              msg += "<table class='table table-list-search' style='font-size: 11px !important;' >";
              msg += "<thead>";
              msg += "<tr>";
              msg += "<th>Código</th>";
              msg += "<th>Descripción Corta</th>";
              msg += "<th>Tipo de Atención</th>";
              msg += "<th>Servicio</th>";
              msg += "<th>Especialista</th>";
              msg += "<th>Fecha de Orden</th>";
              msg += "<th>Estado</th>";
              msg += "<th>Dias Transcurridos</th>";
              msg += "<th>Solicitud</th>";
              msg += "</tr>";
              msg += "</thead>";
              msg += "<tbody>";
            }
            msg += "<tr><td>" + key.codigo_rad + "</td>";
            msg += "<td>" + key.descripcion_corta + "</td>";
            if (key.id_tipo_atencion == 1) {
              msg += "<td>" + `${key.tipo_atencion_nombre}` + "</td>";
            }
            if (key.id_tipo_atencion == 2) {
              if (key.id_evaluacion > 0) {
                msg += "<td>" + `EMERGENCIA - EVALUACIÓN` + "</td>";
              } else {
                msg += "<td>" + `${key.tipo_atencion_nombre}` + "</td>";
              }
            }
            if (key.id_tipo_atencion == 1) {
              msg += "<td>" + `${key.nombre_servicio}` + "</td>";
            }
            if (key.id_tipo_atencion == 2) {
              msg += "<td>" + `${key.nombre_servicio_emergencia}` + "</td>";
            }
            msg += "<td>" + `${key.medico_paterno} ${key.medico_materno}, ${key.medico_nombre}` + "</td>";
            msg += "<td>" + (key.fecha_expedicion) + "</td>";
            if (key.flag_validated == '1') {
              msg += "<td style='font-weight : 700;'>ATENDIDO</td>";
            } else {
              if (key.flag_informe == '1') {
                msg += "<td style='font-weight : 700;'>PENDIENTE VALIDAR</td>";
              } else {
                if (key.flag_cita_imagenes == 1) {
                  msg += "<td style='font-weight : 700;'>PENDIENTE CITADO</td>";
                } else if (key.confirmar_toma == null || key.confirmar_toma == '') {
                  msg += "<td style='font-weight : 700;'>RECIENTE</td>";
                }
              }
            }

            msg += "<td>" + (key.dias_transcurridos) + "</td>";
            msg += "<td>" + (parseInt(key.flag) == 0 ? "SIN FIRMAR" : "FIRMADO") + "</td></tr>";
            n++;
          }
          msg += "</tbody></table>";
          $("#contenido-table-pendiente-radiologia").html(msg);
          $("#showDetalleExamenPendienteRadiologia").modal();
        }
      }
    });
  }

  // ===================== RECETA =====================
  function getProducto(farmaciaIdLastRecipe = null) {
    $('#example tbody').html('');

    if ($('#id_servicio').val() == "") return false;
    var id_ipress = $('#id_establecimiento').val();
    var id_consultorio = $('#id_servicio').val();
    var id_tiporeceta = $('#id_tipo_atencion').val();
    var id_farmacia = farmaciaIdLastRecipe != null
      ? farmaciaIdLastRecipe
      : $('#id_farmacia_receta').val();

    if (id_tiporeceta != 6 && id_tiporeceta != 5 && id_tiporeceta != 4 && id_farmacia != 18 && id_farmacia != 25) {
      if (id_tiporeceta == 2) {
        id_consultorio = 76;
      }
      $.ajax({
        url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia + '/' + id_consultorio,
        success: function (result) {
          var newOption = "";
          newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
          });
          $('#cmbMedicamentoTemp').html(newOption);
        }
      });
    }

    if ((id_tiporeceta == 1 || id_tiporeceta == 5 || id_tiporeceta == 6) && id_farmacia == 21) { //nutricional
      if (id_tiporeceta == 5 || id_tiporeceta == 1) {
        id_consultorio = 110;
      }
      else {
        id_consultorio = 111;
      }
      $.ajax({
        url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia + '/' + id_consultorio,
        success: function (result) {
          var newOption = "";
          newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
          });
          $('#cmbMedicamentoTemp').html(newOption);
        }
      });
    }

    if (id_farmacia == 18 || id_farmacia == 25 || id_tiporeceta == 4) { //centro quirurgico
      if (id_tiporeceta == 1) {
        if (id_farmacia == 25) {
          $.ajax({
            url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress + '/' + id_consultorio,
            success: function (result) {
              var newOption = "";
              newOption = "<option value=''>--Seleccionar-</option>";
              $(result).each(function (ii, oo) {
                newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
              });
              $('#cmbMedicamentoTemp').html(newOption);
            }
          });
        }
        else {
          if (id_consultorio == 72) {
            $.ajax({
              url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia + '/' + 72,
              success: function (result) {
                var newOption = "";
                newOption = "<option value=''>--Seleccionar-</option>";
                $(result).each(function (ii, oo) {
                  newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
                });
                $('#cmbMedicamentoTemp').html(newOption);
              }
            });
          }
          else {
            $.ajax({
              url: '/obtenerProductosFarmaciasByNivel/' + id_farmacia + '/' + id_tiporeceta + '/' + id_consultorio,
              success: function (result) {
                var newOption = "";
                newOption = "<option value=''>--Seleccionar-</option>";
                $(result).each(function (ii, oo) {
                  newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
                });
                $('#cmbMedicamentoTemp').html(newOption);
              }
            });
          }
        }
      }
      else {
        if (id_tiporeceta == 4) {
          if (id_consultorio == 72) {
            $.ajax({
              url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia + '/' + 72,
              success: function (result) {
                var newOption = "";
                newOption = "<option value=''>--Seleccionar-</option>";
                $(result).each(function (ii, oo) {
                  newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
                });
                $('#cmbMedicamentoTemp').html(newOption);
              }
            });
          }
          else {
            $.ajax({
              url: '/obtener_producto_por_idipress_idconsultorio_quirurgico/' + id_ipress + '/' + id_consultorio,
              success: function (result) {
                var newOption = "";
                newOption = "<option value=''>--Seleccionar-</option>";
                $(result).each(function (ii, oo) {
                  newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
                });
                $('#cmbMedicamentoTemp').html(newOption);
              }
            });
          }
        }
      }
    }
    if (id_farmacia == 132) {
      $.ajax({
        url: '/obtener_producto_farmacotecnia/' + id_ipress,
        success: function (result) {
          var newOption = "";
          newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
          });
          $('#cmbMedicamentoTemp').html(newOption);
        }
      });
    }
  }

  function getProductoUltimaReceta(id_farmacia) {
    getProducto(id_farmacia);
    return new Promise(function (resolve) {
      var intentos = 0;
      var verificarProductos = setInterval(function () {
        intentos++;
        if ($('#cmbMedicamentoTemp option').length > 1 || intentos >= 20) {
          clearInterval(verificarProductos);
          resolve();
        }
      }, 100);
    });
  }

  async function getLastRecipeConsultaExterna() {
    var dni_beneficiario = $('#dni_beneficiario').val();
    var id_tipo_atencion = $('#id_tipo_atencion').val();

    if (!dni_beneficiario || !id_tipo_atencion) {
      Swal.fire({
        title: 'Información',
        text: 'No se pudo identificar al asegurado o el tipo de atención.',
        icon: 'info'
      });
      return;
    }

    Swal.fire({
      title: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 18px'>Buscando Receta</span></div>",
      html: "<div style='margin: 0px 0px'><span style='color: #000000; font-size: 16px'>Espere un momento por favor ...</span></div>",
      showCancelButton: false,
      showConfirmButton: false,
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => Swal.showLoading()
    });

    try {
      var response = await $.ajax({
        type: "GET",
        url: "/api/new-receta/dni/" + dni_beneficiario + "/tipo-atencion/" + id_tipo_atencion,
        dataType: "json"
      });

      if (
        response &&
        response.data &&
        response.data.productos &&
        response.data.productos.length > 0 &&
        response.data.id_farmacia
      ) {
        var id_farmacia_actual = $('#id_farmacia_receta').val();

        if (!id_farmacia_actual) {
          Swal.fire({
            title: 'Información',
            text: 'Debe seleccionar una farmacia antes de copiar la última receta.',
            icon: 'info'
          });
          return;
        }

        await getProductoUltimaReceta(id_farmacia_actual);

        for (const producto of response.data.productos) {
          var existeProducto = $('#cmbMedicamentoTemp option[value="' + producto.id_producto + '"]').length > 0;
          if (!existeProducto) {
            $('#cmbMedicamentoTemp').append(
              $('<option>', {
                value: producto.id_producto,
                text: producto.codigo + ' - ' + producto.nombre + ', ' + producto.abreviatura
              })
            );
          }
        }
        $('#example tbody').empty();
        filasmedicamentos = 0;

        for (const producto of response.data.productos) {
          AddProducto(producto);
        }
      } else {
        Swal.fire({
          title: 'Información',
          text: 'No se encontraron productos en la última receta.',
          icon: 'info'
        });
        return;
      }

      Swal.close();

    } catch (xhr) {
      var messageError =
        xhr.responseJSON && xhr.responseJSON.message
          ? xhr.responseJSON.message
          : "Ocurrió un problema al buscar la última receta.";

      Swal.fire({
        title: 'Error',
        text: messageError,
        icon: 'error',
        timer: 4000,
        timerProgressBar: true
      });

    } finally {
      Swal.hideLoading();
    }
  }

  function AddProducto(productoParam = null) {
    const selects = document.querySelectorAll('select[name="id_producto[]"]');
    const ultimoSelect = selects[selects.length - 1]; // Obtiene el último select
    const ultimoValor = ultimoSelect ? ultimoSelect.value : null; // Obtiene su valor si existe
    if (ultimoValor == "ru='undefined'") {
      bootbox.alert("Ingrese un medicamento antes de agregar otro.");
      return false;
    }
    var tipo_receta = $('#tipo_receta').val();
    var id_consultorio = $('#id_consultorio').val();
    var id_farmacia = $('#id_farmacia_receta').val();
    if (tipo_receta == "") {
      bootbox.alert("Seleccione el tipo atencion");
      return false;
    }
    if (id_consultorio == "") {
      bootbox.alert("Seleccione un servicio");
      return false;
    }
    var newRow = "";
    var ind = filasmedicamentos
    var tabindex = 11;
    var nuevalperiodo = "";
    if (ind > 0) {
      $('.cmbMedicamento').each(function () {
        var ind_tmp = $(this).attr("ind");
        if (ind_tmp => ind) {
          tabindex += 3;
        }
      });

      fila_medicamentos = $('#example tbody').children('tr');
      rowIndex = $('#hidden_rowIndex').val();
      var idval = ind - 1;
      fila_medicamento = fila_medicamentos[idval];
      var valante = $(fila_medicamento).find('#nro_med_entregados').val();
      if (valante == "") {
        bootbox.alert("Ingrese cantidad entregada en el producto anterior");
        return false;
      }
    }

    var item_producto = "";
    $('#cmbMedicamentoTemp option').each(function () {
      item_producto += "<option value=" + $(this).val() + " ru='" + $(this).attr("ru") + "'>" + $(this).html() + "</option>"
    });

    var item_via_administracion = '';
    item_via_administracion += '<option value="0">--Seleccionar--</option>';
    item_via_administracion += '<option value="1">Oral</option>';
    item_via_administracion += '<option value="2">Sublingual</option>';
    item_via_administracion += '<option value="3">Rectal</option>';
    item_via_administracion += '<option value="4">Intravenosa</option>';
    item_via_administracion += '<option value="5">Intramuscular</option>';
    item_via_administracion += '<option value="6">Subcutánea</option>';
    item_via_administracion += '<option value="7">Dérmica</option>';
    item_via_administracion += '<option value="8">Nasal</option>';
    item_via_administracion += '<option value="9">Oftalmologica</option>';
    item_via_administracion += '<option value="10">Inhalatoria</option>';
    item_via_administracion += '<option value="11">Epidural</option>';
    item_via_administracion += '<option value="12">Intratecal</option>';
    item_via_administracion += '<option value="13">Vaginal</option>';
    item_via_administracion += '<option value="14">Intraarticular</option>';
    item_via_administracion += '<option value="17">Parenteral</option>';
    item_via_administracion += '<option value="15">No Aplica</option>';
    item_via_administracion += '<option value="16">Otros</option>';

    newRow += '<tr>';
    newRow += '<td><select class="form-control cmbMedicamento" id="cmbMedicamento' + ind + '" ind="' + ind + '" tabindex="' + tabindex + '" name="id_producto[]" style="width: 450px">' + item_producto + '</select></td>';
    newRow += '<input type="hidden" name="nro_stocks_almacen[]" id="nro_stocks_almacen" value=""/>';
    newRow += '<input type="hidden" name="nro_stocks_establecimiento[]" id="nro_stocks_establecimiento" value=""/>';
    newRow += '<td><input type="text" name="nro_stocks[]" required id="nro_stocks" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
    newRow += '<td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo[]" maxlength="3" tabindex="' + (tabindex + 1) + '" required="" id="tiempo_periodo' + ind + '" class="nro_solicitado tiempo_periodo input-sm" style="margin-left:4px; width:100px" value="' + nuevalperiodo + '"/>';
    newRow += '<td><select tabindex="' + (tabindex + 1) + '" class="form-control id_via_administracion" id="cmbTipoViaAdministracion' + ind + '" name="id_via_administracion[]" style="margin:0 20px;width:150px">' + item_via_administracion + '</select></td>';
    newRow += '<td><input type="text" name="posologia[]" maxlength="150" tabindex="' + (tabindex + 2) + '" required="" id="posologia' + ind + '" class="nro_solicitado posologia input-sm" style="margin-left:4px; width:200px" value=""/>';
    newRow += '<input type="hidden" name="lotes_lote[]" id="lotes_lote" value=""/>';
    newRow += '<input type="hidden" name="lotes_registro_sanitario[]" id="lotes_registro_sanitario" value=""/>';
    newRow += '<input type="hidden" name="lotes_fecha_vencimiento[]" id="lotes_fecha_vencimiento" value=""/>';
    newRow += '<input type="hidden" name="lotes_precio_unitario[]" id="lotes_precio_unitario" value=""/>';
    newRow += '<input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/></td>';
    newRow += '<td><input onKeyPress="return soloNumerosMenosCero(event)" type="text" tabindex="' + (tabindex + 3) + '" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados[]" required="" id="nro_med_solictados' + ind + '" class="limpia_text nro_solicitado nro_med_solictados input-sm" style="margin-left:4px; width:100px" /></td>';
    newRow += '<td><select tabindex="' + (tabindex + 4) + '" class="form-control select-diagnostico"  id="miSelectProd' + ind + '" name="miSelectProd[]"></select></td>';
    newRow += '<td><button type="button" class="btn btn-primary btn-xs btnAddProductoOfList" id="add_producto' + ind + '"><i class="fa fa-plus"></i> </button></td>';
    newRow += '<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';
    newRow += '<td><input type="hidden" name="exit" id="exit" id="" style="width:10px" value=""/></td>';
    newRow += '</tr>';
    $('#example tbody').append(newRow);
    $("#cmbMedicamento" + ind).select2({ max_selected_options: 4 }).focus();

    const selectElementProd = document.getElementById('miSelectProd' + ind);

    $('.tiempo_periodo').on('input', function () {
      const valor = parseInt($(this).val(), 10);
      if (!isNaN(valor) && valor > 180) {
        Swal.fire({
          icon: 'warning',
          title: '¡Atención!',
          text: 'El tiempo de periodo excede los 180 días.',
          confirmButtonText: 'Cerrar'
        });
      }
    });

    listIdDiagnosticoObjetos.forEach((item) => {
      let option = document.createElement('option');
      option.value = item.id;
      option.textContent = item.nombre;
      selectElementProd.appendChild(option);
    });

    $("#nro_med_solictados" + ind).keypress(function (e) {
      if (e.which == 13) {
        AddProducto();
        var id_farmacia = $('#id_farmacia_receta').val();
        validproductoseleccionado(id_farmacia, this);
      }
    });

    $("#add_producto" + ind).click(function (e) {
      AddProducto();
      var id_farmacia = $('#id_farmacia_receta').val();
      validproductoseleccionado(id_farmacia, this);
      btnAgregarProducto = $(this);
      $(btnAgregarProducto).attr('disabled', 'disabled');
    });

    $("#cmbMedicamento" + ind).on("change", function (e) {
      var flagx = 0;
      cmb = $(this);
      id_producto = $("#cmbMedicamento" + ind).val();

      $('.cmbMedicamento').each(function () {
        if ($(this).val() == id_producto) flagx++;
      });

      if (flagx > 1) {
        bootbox.alert("El producto farmaceutico ya ha sido ingresado");
        $("#cmbMedicamento" + ind).val("").trigger("change");
        return false;
      }
      id_farmacia_receta = $('#id_farmacia_receta').val();

      option = {
        url: '/cargarstock_porfarmacia/' + id_producto + '/' + id_farmacia_receta,
        type: 'GET',
        dataType: 'json',
        data: {}
      };

      $.ajax(option).done(function (data) {
        var cantidad = data.cantidad;
        var cantidadEstablecimiento = data.cantidadEstablecimiento;
        var cantidadAlmacen = data.cantidadAlmacen;
        $(cmb).closest("tr").find(".limpia_text").val("");
        $(cmb).closest("tr").find("#nro_stocks").val(cantidad);
        $(cmb).closest("tr").find("#nro_stocks_establecimiento").val(cantidadEstablecimiento);
        $(cmb).closest("tr").find("#nro_stocks_almacen").val(cantidadAlmacen);
        $(cmb).closest("tr").find(".tiempo_periodo").focus();
        $(cmb).closest("tr").find("#nro_med_solictados").val("");
        $(cmb).closest("tr").find("#nro_med_entregados").val("");
        $(cmb).closest("tr").find("#lotes_lote").val("");
        $(cmb).closest("tr").find("#lotes_cantidad").val("");
        $(cmb).closest("tr").find("#lotes_registro_sanitario").val("");
        $(cmb).closest("tr").find("#lotes_fecha_vencimiento").val("");
      });
    });
    filasmedicamentos++;

    if (productoParam) {
      var $medicamentoCopiado = $("#cmbMedicamento" + ind);
      $medicamentoCopiado.val(productoParam.id_producto);
      if ($medicamentoCopiado.val() == productoParam.id_producto) {
        $medicamentoCopiado.trigger('change');
      }
      $("#tiempo_periodo" + ind).val(productoParam.tiempo_periodo);
      $("#cmbTipoViaAdministracion" + ind).val(productoParam.id_via_administracion);
      $("#posologia" + ind).val(productoParam.posologia);
    }
  }

  function eliminarDiagnosticoXProducto(posicion) {
    // Se elimina el elemento en la posicion dada
    procedimientosSeleccionados.splice(posicion, 1);
    // Se actualiza la posicion de los elementos restantes
    procedimientosSeleccionados.forEach((item, index) => {
      item.posicion = index;
    });
  }

  // ===================== EXAMEN DE LABORATORIO =====================
  function obtenerIndiceTblExamenLab() {
    let indice = $('#tblExamenLab tbody tr').length;
    $('.id_laboratorio').each(function () {
      let ind_tmp = $(this).data("id");
      if (ind_tmp >= indice) {
        indice = Number(ind_tmp) + 1;
      }
    });
    return indice;
  }

  function asignarValoresSelect(ui, ind) {
    var id_examen_lab = ui.item.id;
    let id_categoria_examen_lab = ui.item.id_categoria;
    $("#id_examen_lab" + ind).val(id_examen_lab);
    $("#id_categoria_examen_lab" + ind).val(id_categoria_examen_lab);
    $("#nombre_examen_lab" + ind).val(ui.item.value);
    $("#nombre_examen_lab" + ind).prop("readonly", true);
    $("#cod_lab" + ind).val(ui.item.codigo_procedimiento);
  }

  function AddExamenLab() {
    var newRow = "";
    var ind = $('#tblExamenLab tbody tr').length;
    if (ind > 0) {
      ind = obtenerIndiceTblExamenLab();
      fila_medicamentos = $('#tblExamenLab tbody').children('tr');
      rowIndex = $('#hidden_rowIndex').val();
      var idval = ind - 1;
      fila_medicamento = fila_medicamentos[idval];
    }
    const id_nivel = $("#id_nivel").val();
    newRow += '<tr>';
    newRow += '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de laboratorio" name="nombre_examen_lab[]" tabindex="8" required="" id="nombre_examen_lab' + ind + '" class="input-sm" style="width:100%; text-align:left" />';
    newRow += '<input type="hidden" class="form-control typeahead tt-query id_laboratorio" id="id_examen_lab' + ind + '" name="id_examen_lab[]" value="" data-id="' + ind + '">';
    newRow += '<input type="hidden" class="form-control typeahead tt-query cod_lab" id="cod_lab' + ind + '" name="cod_lab[]" value="" data-id=' + ind + '>';
    newRow += '<input type="hidden" class="form-control typeahead tt-query examenRepetida" id="examenRepetida' + ind + '" name="examenRepetida[]" value="" data-id=' + ind + '>';
    newRow += '<input type="hidden" class="form-control typeahead tt-query id_categoria" id="id_categoria_examen_lab' + ind + '" name="id_categoria_examen_lab[]" value="" data-id=' + ind + '></td>';

    if (id_nivel && parseInt(id_nivel) < 3) {
      newRow += '<td><input type="text" placeholder="Observacion" class="form-control examen_lab_observacion typeahead tt-query" id="examen_lab_observacion' + ind + '" name="examen_lab_observacion[]"></td>';
    }
    newRow += '<td><input type="text" maxlength="2" placeholder="Cantidad" readonly class="form-control examen_lab_cantidad typeahead tt-query" id="examen_lab_cantidad' + ind + '" name="examen_lab_cantidad[]" value="1"></td>';
    newRow += '<td><select class="form-control select-diagnostico"  id="miSelectLab' + ind + '" name="miSelectLab[]"><option value="">-- Seleccionar --</option></select></td>';
    newRow += '<td></td>';
    newRow += '<td></td>';
    newRow += '<td></td>';
    newRow += '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow += '</tr>';
    $('#tblExamenLab tbody').append(newRow);
    $("#examen_lab_cantidad" + ind).on('input', function () {
      var valor = $(this).val();
      if (valor === '0') {
        $(this).val(''); // Si ingresan 0, eliminarlo
      }
    });
    const selectElementLab = document.getElementById('miSelectLab' + ind);

    listIdDiagnosticoObjetos.forEach((item) => {
      let option = document.createElement('option');
      option.value = item.id;
      option.textContent = item.nombre;
      selectElementLab.appendChild(option);
    });

    var nivel = $('#id_nivel').val();
    var sexo = $('#nomsexo').val();
    var id_tipo_atencion = $('#id_tipo_atencion').val();
    let id_servicio = $("#id_servicio").val();
    if (sexo == 'M')
      genero = 1;
    else
      genero = 2;
    $("#nombre_examen_lab" + ind).autocomplete({
      minLength: 1,
      delay: 400,
      source: function (request, response) {
        $.ajax({
          url: '/obtener_laboratorio_convenio/' + request.term + '/' + nivel + '/' + genero + '/' + id_tipo_atencion + '/' + id_servicio,
          dataType: "json",
          success: function (data) {
            let listaLaboratorioSelecionado = $(".id_laboratorio").map(function () { return parseInt($(this).val()); }).get();
            let filtro = data.filter((element) => !listaLaboratorioSelecionado.includes(element.id));
            response(filtro);
          }
        })
      },
      select: function (e, ui) {
        asignarValoresSelect(ui, ind);
      }
    });

    $("#nombre_examen_lab" + ind).on('blur', function () {
      var id_examen_lab = $("#id_examen_lab" + ind).val();
      if (id_examen_lab == "") {
        bootbox.alert("Falta ingresar el examen laboratorio en esta fila");
        return false;
      }
    });
    $("#examen_lab_cantidad" + ind).on('blur', function () {
      var id_examen_lab = $("#id_examen_lab" + ind).val();
      if (id_examen_lab == "") {
        bootbox.alert("Falta ingresar la cantidad en este examen laboratorio");
        return false;
      }
    });
  }

  // ===================== EXAMEN DE IMAGENOLOGÍA =====================
  $(document).on('click', '.addRadioPortatil', function () {
    let id = $(this).data("id");
    let addRadioPortatil = $(`#addRadioPortatil${id}`).attr("aria-pressed");
    $(`#radio_portatil${id}`).val('NO');
    if (addRadioPortatil == "true") {
      $(`#radio_portatil${id}`).val('SI');
    }
  });

  function AddExamenRad() {
    var newRow = "";
    var ind = $('#tblExamenRad tbody tr').length;

    if (ind > 0) {
      $('.id_radiologia').each(function () {
        var ind_tmp = $(this).data("id");
        if (ind_tmp => ind) {
          ind = Number(ind_tmp) + 1;
        }
      });

      fila_medicamentos = $('#tblExamenRad tbody').children('tr');
      rowIndex = $('#hidden_rowIndex').val();
      var idval = ind - 1;
      fila_medicamento = fila_medicamentos[idval];
    }

    var item_comparativo = '';
    item_comparativo += '<option value="0">--Seleccionar--</option>';
    item_comparativo += '<option value="1">SI</option>';
    item_comparativo += '<option value="2">NO</option>';

    var item_lado = '';
    item_lado += '<option value="0">--Seleccionar--</option>';
    item_lado += '<option value="1">Izquierdo</option>';
    item_lado += '<option value="2">Derecho</option>';
    item_lado += '<option value="3">Ambos</option>';

    newRow += '<tr>';
    newRow += '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el examen de Radiografia" name="nombre_examen_rad[]" tabindex="8" required="" id="nombre_examen_rad' + ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
    newRow += '<input type="hidden" class="form-control typeahead tt-query id_radiologia" id="id_examen_rad' + ind + '" name="id_examen_rad[]" value="" data-id=' + ind + '>';
    newRow += '<input type="hidden" class="form-control typeahead tt-query id_codigo_rad" id="id_codigo_rad' + ind + '" name="id_codigo_rad[]" value="" data-id=' + ind + '></td>';
    newRow += '<td><select class="form-control id_comparativo" id="cmbComparativo' + ind + '" name="id_comparativo[]" style="margin:0 20px;width:150px">' + item_comparativo + '</select></td>';
    newRow += '<td><select class="form-control id_lado" id="cmbLado' + ind + '" name="id_lado[]" style="margin:0 20px;width:150px">' + item_lado + '</select></td>';
    newRow += '<td><input type="text" placeholder="Observacion" class="form-control examen_rad_observacion typeahead tt-query" style="margin:0 10px;width:95%" id="examen_rad_observacion' + ind + '" name="examen_rad_observacion[]"></td>';
    newRow += '<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control examen_rad_cantidad typeahead tt-query" style="margin:0 5px;width:100%" id="examen_rad_cantidad' + ind + '" name="examen_rad_cantidad[]" value="1"></td>';
    newRow += '<td><select class="form-control select-diagnostico"  id="miSelectRad' + ind + '" name="miSelectRad[]" style="margin:0 10px;width:95%"></select></td>';
    newRow += `<td>
                <input type="hidden" id="radio_portatil${ind}" name="radio_portatil[]" value="NO" data-id="${ind}" />
                <button tabindex="100" type="button" id="addRadioPortatil${ind}" class="btn btn-lg btn-toggle addRadioPortatil" data-id="${ind}" data-toggle="button" aria-pressed="false" autocomplete="off">
                  <div class="handle"></div>
                </button>
               </td>`;
    newRow += '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow += '</tr>';
    $('#tblExamenRad tbody').append(newRow);
    var nivel = $('#id_nivel').val();
    var sexo = $('#nomsexo').val();
    var id_tipo_atencion = $('#id_tipo_atencion').val();
    let id_servicio_rad = $("#id_servicio").val();
    if (nivel != 3)
      id_servicio_rad = 8;

    if (sexo == 'M')
      genero = 1;
    else
      genero = 2;
    const selectElementRad = document.getElementById('miSelectRad' + ind);

    listIdDiagnosticoObjetos.forEach((item) => {
      let option = document.createElement('option');
      option.value = item.id;
      option.textContent = item.nombre;
      selectElementRad.appendChild(option);
    });

    $("#nombre_examen_rad" + ind).autocomplete({
      minLength: 1,
      delay: 400,
      source: function (request, response) {
        $.ajax({
          url: `/obtener_radiologia_convenio/${request.term}/${nivel}/${genero}/${id_tipo_atencion}/${id_servicio_rad}`,
          dataType: "json",
          success: function (data) {
            let listaRadiologiaSelecionado = $(".id_radiologia").map(function () { return parseInt($(this).val()); }).get();
            let filtro = data.filter((element) => !listaRadiologiaSelecionado.includes(element.id));
            response(filtro);
          }
        })
      },
      select: function (e, ui) {
        var id_examen_rad = ui.item.id;
        var id_codigo_rad = ui.item.codigo_procedimiento;
        $("#id_examen_rad" + ind).val(id_examen_rad);
        $("#id_codigo_rad" + ind).val(id_codigo_rad);
        $("#nombre_examen_rad" + ind).prop("readonly", true);
        validaExamenRadiologiaSeleccionado($("#id_codigo_rad" + ind).val());
      }
    });

    $("#nombre_examen_rad" + ind).on('blur', function () {
      var id_examen_rad = $("#id_examen_rad" + ind).val();
      if (id_examen_rad == "") {
        bootbox.alert("Falta ingresar el examen radiografia en esta fila");
        $("#examen_rad_cantidad" + ind).val("1");
        return false;
      }
    });
    $("#examen_rad_cantidad" + ind).on('blur', function () {
      var id_examen_rad = $("#id_examen_rad" + ind).val();
      if (id_examen_rad == "") {
        bootbox.alert("Falta ingresar la cantidad en este examen radiografia");
        $("#examen_rad_cantidad" + ind).val("1");
        return false;
      }
    });
  }

  $(document).ready(function () {
    $('#addRecetaVale').on('click', function () {
      var addRecetaVale = $('#addRecetaVale').attr("aria-pressed");
      $("#fsRecetaVale").hide();
      $("#divCopiarUltimaReceta").hide();
      if (addRecetaVale == "false") {
        $("#fsRecetaVale").show();
        getProducto();
        var idEstablecimiento = parseInt($('#id_establecimiento').val());
        var idServicio = parseInt($('#id_servicio').val());
        if (idEstablecimiento === 75 && idServicio === 106) {
          $("#divCopiarUltimaReceta").show();
        }
      } else {
        $('#example tbody').empty();
        filasmedicamentos = 0;
      }
    });

    $('#addCondicion').on('click', function () {
      var addCondicion = $('#addCondicion').attr("aria-pressed");
      $("#divCondicion").hide();
      $("#condicion").val(0);
      if (addCondicion == "false") {
        $("#divCondicion").show();
        $("#condicion").val(1);
      }
    });

    $('#addExamenLab').on('click', function () {
      var addExamenLab = $('#addExamenLab').attr("aria-pressed");
      $("#fsExamenLab").hide();
      if (addExamenLab == "false") {
        $("#fsExamenLab").show();
      } else {
        $('#id_perfil_preoperatorio').val('0')
        $('#tblExamenLab tbody').empty();
      }
    });

    $('#addGastro').on('click', function () {
      var addGastro = $('#addGastro').attr("aria-pressed");
      $("#fsGastro").hide();
      if (addGastro == "false") {
        $("#fsGastro").show();
      }
    });

    @if(!$confirmacion && !empty($pp_gastro_tipo))
    if ($('#addGastro').length > 0) {
      if ($('#addGastro').attr('aria-pressed') == 'false') $('#addGastro').trigger('click');
      $('#addGastro').prop('disabled', true).css({ opacity: 1, cursor: 'not-allowed' }).attr('title', 'Informe obligatorio para este consultorio');
    }
    @endif

    $('#addExamenRad').on('click', function () {
      var addExamenRad = $('#addExamenRad').attr("aria-pressed");
      $("#fsExamenRad").hide();
      if (addExamenRad == "false") {
        $("#fsExamenRad").show();
      }
    });

    // ---------- Agregar filas ----------
    $('#addProducto').on('click', function () {
      const botonesAddProducto = document.querySelectorAll('.btnAddProductoOfList');
      const ultimoBoton = botonesAddProducto.length > 0 ? botonesAddProducto[botonesAddProducto.length - 1] : null;
      var id_farmacia = $('#id_farmacia_receta').val();
      validproductoseleccionado(id_farmacia, ultimoBoton);
      AddProducto();
    });

    $('#addExamenLab2').on('click', function () {
      AddExamenLab();
    });

    // ---------- RUA LABORATORIO (checklist) ----------
    function cargarDiagnosticosTodos() {
      $("#contenedorDX").html("Cargando...");
      const nivel = $('#id_nivel').val();
      const sexo = $('#nomsexo').val();
      const id_tipo_atencion = $('#id_tipo_atencion').val();
      const id_servicio = $('#id_servicio').val();
      const genero = (sexo == 'M') ? 1 : 2;

      $.ajax({
        url: '/obtener_laboratorio_convenio/all/' + nivel + '/' + genero + '/' + id_tipo_atencion + '/' + id_servicio,
        method: 'GET',
        dataType: 'json',
        success: function (data) {
          let total = data.length;
          let clase = 'col1';
          let modalClase = 'modal-col1';

          if (total > 5 && total <= 10) { clase = 'col2'; modalClase = 'modal-col2'; }
          else if (total > 10 && total <= 20) { clase = 'col3'; modalClase = 'modal-col3'; }
          else if (total > 20) { clase = 'col4'; modalClase = 'modal-col4'; }

          $("#contenedorDX").removeClass("col1 col2 col3 col4").addClass(clase);
          $("#modalDX").removeClass("modal-col1 modal-col2 modal-col3 modal-col4").addClass(modalClase);

          let grupos = {};
          data.forEach(function (item) {
            let idCat = item.id_categoria || "0";
            let nombreCat = item.categoria_nombre || "SIN CATEGORÍA";
            if (!grupos[idCat]) {
              grupos[idCat] = { nombre: nombreCat, items: [] };
            }
            grupos[idCat].items.push(item);
          });

          let html = "";
          Object.keys(grupos).sort().forEach(function (idCat) {
            html += `
              <h4 style="margin: 6px 0 4px 0 !important;font-weight: 600;color: #3c763d;border-bottom: 1px solid white;padding-bottom: 2px;padding-top: 12px;font-size: 15px;text-transform: uppercase;">
                  ${grupos[idCat].nombre}
              </h4>
            `;
            grupos[idCat].items.forEach(function (item) {
              html += `
                <label style="display:block; margin-left:10px;">
                  <input type="checkbox"
                    class="chk_rualab"
                    name="chk_rualab[]"
                    data-id="${item.id}"
                    data-codigo="${item.codigo_procedimiento || ''}"
                    data-categoria="${item.id_categoria || ''}"
                    data-label="${item.label || ''}"
                    style="transform: scale(1.4); margin-right:8px;">
                  ${item.label || ''}
                </label>
              `;
            });
          });

          $("#contenedorDX").html(html);
        }
      });
    }

    $('#modalDX').on('show.bs.modal', function () {
      cargarDiagnosticosTodos();
    });

    $("#guardarRUALab").on('click', function () {
      const checked = $("input[name='chk_rualab[]']:checked");
      if (checked.length === 0) {
        $("#modalDX").modal('hide');
        return;
      }

      checked.each(function () {
        const $this = $(this);
        const label = $this.data('label');
        const id_examen_lab = $this.data('id');
        const codigo = $this.data('codigo');
        const categoria = $this.data('categoria');

        const yaAgregado = $(".id_laboratorio").filter(function () { return String($(this).val()) === String(id_examen_lab); }).length > 0;
        if (yaAgregado) {
          return true;
        }

        const ind = obtenerIndiceTblExamenLab();
        const id_nivel = $("#id_nivel").val();
        const columnaObservacion = (id_nivel && parseInt(id_nivel) < 3)
          ? `<td><input type="text" placeholder="Observacion" class="form-control examen_lab_observacion" id="examen_lab_observacion${ind}" name="examen_lab_observacion[]"></td>`
          : '';

        const newRow = `
          <tr>
            <td>
              <input type="text" name="nombre_examen_lab[]" id="nombre_examen_lab${ind}" class="input-sm" style="text-align: left; width: 100%;" value="${label}" readonly />
              <input type="hidden" name="id_examen_lab[]" id="id_examen_lab${ind}" value="${id_examen_lab}" class="id_laboratorio" data-id="${ind}" />
              <input type="hidden" name="cod_lab[]" id="cod_lab${ind}" value="${codigo}" />
              <input type="hidden" class="examenRepetida" id="examenRepetida${ind}" name="examenRepetida[]" value="" data-id="${ind}" />
              <input type="hidden" name="id_categoria_examen_lab[]" id="id_categoria_examen_lab${ind}" value="${categoria}" />
            </td>
            ${columnaObservacion}
            <td>
              <input type="number" name="examen_lab_cantidad[]" id="examen_lab_cantidad${ind}" class="form-control" value="1" readonly />
            </td>
            <td>
              <select name="miSelectLab[]" id="miSelectLab${ind}" class="form-control select-diagnostico">
                <option value="">-- Seleccionar --</option>
              </select>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td>
              <button type="button" class="btn btn-danger btn-xs deleteFila" style="margin-left: 4px">
                <i class="fa fa-times"></i> Eliminar
              </button>
            </td>
          </tr>
        `;

        $("#tblExamenLab tbody").append(newRow);

        const select = document.getElementById(`miSelectLab${ind}`);
        if (Array.isArray(listIdDiagnosticoObjetos) && select) {
          listIdDiagnosticoObjetos.forEach(diagnostico => {
            const option = document.createElement('option');
            option.value = diagnostico.id;
            option.textContent = diagnostico.nombre;
            select.appendChild(option);
          });
        }
      });

      $("#modalDX").modal('hide');
      $("input[name='chk_rualab[]']").prop('checked', false);
    });

    ////////////////////////////////////////////////////////////////////////////// EXAMEN DE IMÁGENES NIVEL 3: anamnesis antes del primer examen
    var nivel = {{ intval($nivel) }};
    if (nivel === 3) {
      let enviandoFormulario = false;
      var idImagenologiaActual = null;
      var imagenologiaModalMostrado = false;
      $('#addExamenRad2').off('click').on('click', function () {
        if (!imagenologiaModalMostrado) {
          $('#rad_anamnesis').val('');
          $('#rad_examen_fisico').val('');
          $('#rad_examen_auxiliar').val('');
          $('#detalleModal').modal('show');
        } else {
          AddExamenRad();
        }
      });

      $('#tblExamenRad tbody').on('click', 'button.deleteFila', function () {
        var obj = $(this);
        obj.parent().parent().remove();

        var totalFilas = $('#tblExamenRad tbody tr').length;
        if (totalFilas === 0) {
          imagenologiaModalMostrado = false;
          if (idImagenologiaActual) {
            $.ajax({
              url: '/actualizar_estado_imagenologia',
              type: 'POST',
              data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: idImagenologiaActual
              }
            });
          }
        }
      });

      $('#btnConfirmarImagenologia').off('click').on('click', function () {
        var anamnesis = $.trim($('#rad_anamnesis').val());
        var examen_fisico = $.trim($('#rad_examen_fisico').val());
        var examen_auxiliar = $.trim($('#rad_examen_auxiliar').val());
        var errores = [];

        function validarCampo(texto, nombreCampo) {
          if (texto === '') {
            errores.push('Debe ingresar ' + nombreCampo + '.');
            return;
          }
          if (texto.length < 20) {
            errores.push(nombreCampo + ' debe tener mínimo 20 caracteres.');
          }
          if (!/[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9]/.test(texto)) {
            errores.push(nombreCampo + ' no puede contener solo símbolos.');
          }
          if (/^[^a-zA-ZáéíóúÁÉÍÓÚñÑ0-9]+$/.test(texto)) {
            errores.push('Ingrese ' + nombreCampo + ' válido(a).');
          }
          if (/^([^\w\s])\1+$/.test(texto)) {
            errores.push(nombreCampo + ' contiene caracteres inválidos.');
          }
        }

        validarCampo(anamnesis, '<b>Anamnesis</b>');
        validarCampo(examen_fisico, '<b>Examen Físico</b>');
        validarCampo(examen_auxiliar, '<b>Examen Auxiliar</b>');

        if (errores.length > 0) {
          Swal.fire({
            icon: 'error',
            title: 'Campos requeridos',
            html: errores.join('<br>'),
            confirmButtonText: 'ENTENDIDO',
          });
          return false;
        }

        var id_emergencia = 0;
        var id_asegurado = $('#id_asegurado').val();
        var id_establecimiento = $('#id_establecimiento').val();
        var id_tipo_atencion = $('#id_tipo_atencion').val();

        $('#btnConfirmarImagenologia').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');

        $.ajax({
          url: '/guardar_anamnesis_imagenologia',
          type: 'POST',
          data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id_emergencia: id_emergencia,
            id_asegurado: id_asegurado,
            id_establecimiento: id_establecimiento,
            anamnesis: anamnesis,
            examen_fisico: examen_fisico,
            examen_auxiliar: examen_auxiliar,
            id_tipo_atencion: id_tipo_atencion,
          },
          success: function (response) {
            $('#btnConfirmarImagenologia').prop('disabled', false).html('<i class="fa fa-save"></i> <strong>CONFIRMAR Y AGREGAR EXAMEN</strong>');
            if (response.success) {
              idImagenologiaActual = response.id;
              $('#id_rua_imagenes').val(idImagenologiaActual);
              $('#detalleModal').modal('hide');
              imagenologiaModalMostrado = true;
              Swal.fire({
                icon: 'success',
                title: 'Guardado correctamente',
                showConfirmButton: false,
                timer: 1500,
              }).then(function () {
                AddExamenRad();
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error al guardar',
                html: response.message || 'Intente nuevamente.',
                confirmButtonText: 'ENTENDIDO',
              });
            }
          },
          error: function (xhr) {
            $('#btnConfirmarImagenologia').prop('disabled', false).html('<i class="fa fa-save"></i> <strong>CONFIRMAR Y AGREGAR EXAMEN</strong>');
            var msg = 'Ocurrió un error al guardar los datos.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
              msg = xhr.responseJSON.message;
            }
            Swal.fire({
              icon: 'error',
              title: 'Error inesperado',
              html: msg,
              confirmButtonText: 'ENTENDIDO',
            });
          }
        });
      });

      $('#detalleModal').on('hidden.bs.modal', function () {
        if (!imagenologiaModalMostrado) {
          imagenologiaModalMostrado = false;
        }
      });

      window.addEventListener('beforeunload', function (e) {
        var radGuardada = window.PP_ORDENES_GUARDADAS && $('#addExamenRad').attr('aria-pressed') == 'true' && $('#tblExamenRad tbody tr').length > 0;
        if (!enviandoFormulario && !radGuardada && idImagenologiaActual) {
          navigator.sendBeacon('/actualizar_estado_imagenologia', new URLSearchParams({
            _token: $('meta[name="csrf-token"]').attr('content'),
            id: idImagenologiaActual
          }));
        }
      });
    } else {
      $('#addExamenRad2').on('click', function () {
        AddExamenRad();
      });
      $('#tblExamenRad tbody').on('click', 'button.deleteFila', function () {
        var obj = $(this);
        obj.parent().parent().remove();
      });
    }
    ////////////////////////////////////////////////////////////////////////////// FIN EXAMEN DE IMÁGENES

    $('#example tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      var fila = obj.closest('tr');
      var filaPosition = fila.index();
      eliminarDiagnosticoXProducto(filaPosition);
      obj.parent().parent().remove();
      filasmedicamentos--;
    });

    $('#tblExamenLab tbody').on('click', 'button.deleteFila', function () {
      var obj = $(this);
      obj.parent().parent().remove();
    });
  });

  function ppValidarOrdenes() {
    var msg = '';
    if ($('#addRecetaVale').attr('aria-pressed') == 'true') {
      if ($('#fecha_expedicion').val() == '') msg += 'Debe ingresar una Fecha de Expedicion<br>';
      if ($('#id_farmacia_receta').val() == '') msg += 'Debe seleccionar la Farmacia de la receta<br>';
      if ($('#addCondicion').attr('aria-pressed') == 'true' && $('#cantidadReceta').val() == '0') msg += 'Debe ingresar la cantidad de dias de la Receta Vale<br>';
      var ind = $('#example tbody tr').length;
      if (ind > 0) {
        var nind = ind - 1;
        var cmbUltimo = $('#cmbMedicamento' + nind).val();
        if (cmbUltimo == "ru='undefined'" || cmbUltimo == '' || cmbUltimo == null) {
          $('#example .deleteFila').eq(ind - 1).parent().parent().remove();
          filasmedicamentos--;
          ind = ind - 1;
        }
        if (ind == 0) msg += 'Debe ingresar minimo un producto medico a la Receta Vale<br>';
        $('#example tbody tr').each(function () {
          var cmbMedicamento = $(this).find('.cmbMedicamento').val();
          var tiempo_periodo = $(this).find('.tiempo_periodo').val();
          var posologia = $(this).find('.posologia').val();
          var valmedentre = $(this).find('.nro_med_solictados').val();
          var via_administracion = $(this).find('.id_via_administracion').val();
          if (cmbMedicamento == "ru='undefined'" || cmbMedicamento == 'undefined' || cmbMedicamento == '') msg += 'Falta seleccionar un producto (dispositivo médico)<br>';
          if (tiempo_periodo == '' || tiempo_periodo == 0) msg += 'Uno de los productos no cuenta el tiempo(dias), tiene que ser mayor a 0<br>';
          if (via_administracion == 0 || via_administracion == 'undefined') msg += 'Uno de los productos no cuenta con la via de administracion<br>';
          if (posologia == '') msg += 'Uno de los productos no cuenta con la posologia<br>';
          if (valmedentre == '' || valmedentre == 0) msg += 'Uno de los productos no cuenta con la cantidad prescrita, tiene que ser mayor a 0<br>';
        });
      } else {
        msg += 'Debe ingresar minimo un producto medico a la Receta Vale<br>';
      }
    }
    if ($('#addExamenLab').attr('aria-pressed') == 'true') {
      var filasLab = $('#tblExamenLab tbody tr');
      if (filasLab.length == 0) msg += 'Debe registrar un examen de laboratorio<br>';
      filasLab.each(function () {
        var id_examen_lab = $(this).find('input[name="id_examen_lab[]"]').val();
        var cantidad_lab = $(this).find('input[name="examen_lab_cantidad[]"]').val();
        var id_diagnostico_lab = $(this).find('select[name="miSelectLab[]"]').val();
        var cpms_lab = $(this).find('input[name="cod_lab[]"]').val();
        if (id_examen_lab == '') {
          msg += 'Debe registrar un examen de laboratorio<br>';
        } else {
          if (cantidad_lab == '') msg += `Debe registrar una cantidad para el <b>Examen de laboratorio: ${cpms_lab}</b><br>`;
          if (cantidad_lab == 0) msg += `Debe registrar una cantidad mayor a 0 para el <b>Examen de laboratorio: ${cpms_lab}</b><br>`;
          if (id_diagnostico_lab == '' || id_diagnostico_lab == null) msg += `Debe seleccionar el diagnóstico para el <b>Examen de laboratorio: ${cpms_lab}</b><br>`;
        }
      });
    }
    if ($('#addExamenRad').attr('aria-pressed') == 'true') {
      var id_examen_rad = $('input[name="id_examen_rad[]"]').map(function () { return $(this).val(); }).get();
      var proce_rad = $('input[name="examen_rad_cantidad[]"]').map(function () { return $(this).val(); }).get();
      if (id_examen_rad == '') msg += 'Debe registrar un examen de imagenologia<br>';
      if (proce_rad == '') msg += 'Debe registrar una cantidad en imagenologia<br>';
      if (proce_rad == 0) msg += 'Debe registrar una cantidad mayor a 0 en imagenologia<br>';
    }
    msg += ppValidarGastro();
    return msg;
  }

  function ppValidarGastro() {
    $('#fsGastro textarea').removeClass('pp-campo-error');
    if ($('#addGastro').length == 0 || $('#addGastro').attr('aria-pressed') != 'true') return '';

    var partes = $.grep(['baja', 'alta'], function (p) { return $('#fsGastro a[href="#tab_' + p + '"]').length > 0; });
    var conDatos = $.grep(partes, function (p) {
      return $('#tab_' + p + ' textarea').filter(function () { return $.trim($(this).val()) != ''; }).length > 0;
    });
    var validar = partes.length == 1 ? partes : conDatos;
    if (validar.length == 0) return 'Debe registrar el <b>Informe de Endoscopia Digestiva</b> (baja o alta)<br>';

    var msg = '';
    var primeraParte = null;
    $.each(validar, function (i, p) {
      var faltan = [];
      $('#tab_' + p + ' textarea').each(function () {
        if ($.trim($(this).val()) != '') return;
        $(this).addClass('pp-campo-error');
        var $td = $(this).closest('td');
        var etiqueta = '';
        if ($td.prev('td').length == 0) {
          etiqueta = $.trim($td.closest('tr').prev('tr').find('td:first').text());
        } else {
          etiqueta = $.trim($td.prev('td').text());
          if ($td.prev('td').find('b').length == 0) etiqueta = $.trim($td.closest('tr').find('td:first').text()).replace(/:$/, '') + ' ' + etiqueta;
        }
        faltan.push($.trim(etiqueta.replace(/:$/, '')).toUpperCase());
      });
      if (faltan.length > 0) {
        if (!primeraParte) primeraParte = p;
        msg += 'Informe de Endoscopia Digestiva <b>' + p.toUpperCase() + '</b>, falta completar: <b>' + faltan.join(', ') + '</b><br>';
      }
    });
    if (primeraParte) $('#fsGastro a[href="#tab_' + primeraParte + '"]').tab('show');
    return msg;
  }

  $(document).on('input', '#fsGastro textarea', function () {
    if ($.trim($(this).val()) != '') $(this).removeClass('pp-campo-error');
  });

  function ppDatosOrdenes() {
    var datos = [];
    var bloques = [
      { toggle: '#addRecetaVale', bandera: 'receta_activa', contenedor: '#fsRecetaVale' },
      { toggle: '#addExamenLab', bandera: 'lab_activo', contenedor: '#fsExamenLab' },
      { toggle: '#addExamenRad', bandera: 'rad_activo', contenedor: '#fsExamenRad' },
      { toggle: '#addGastro', bandera: 'gastro_activo', contenedor: '#fsGastro' }
    ];
    $.each(bloques, function (i, b) {
      var activo = $(b.toggle).length > 0 && $(b.toggle).attr('aria-pressed') == 'true';
      datos.push({ name: b.bandera, value: activo ? 1 : 0 });
      if (activo) {
        datos = datos.concat($(b.contenedor).find(':input').serializeArray());
      }
    });
    datos.push({ name: 'id_rua_imagenes', value: $('#id_rua_imagenes').val() });
    return datos;
  }


  $(document).on('click', '#btnGuardarOrdenesPP', function () {
    var activos = $.grep(['#addRecetaVale', '#addExamenLab', '#addExamenRad', '#addGastro'], function (t) { return $(t).attr('aria-pressed') == 'true'; });
    if (activos.length == 0) {
      Swal.fire({ icon: 'warning', title: 'Alerta', html: 'Active la <b>receta</b> o el <b>examen</b> que desea agregar.', showConfirmButton: true, customClass: { popup: 'swal-afiliacion' } });
      return;
    }
    var msg = ppValidarOrdenes();
    if (msg != '') {
      Swal.fire({ icon: 'warning', title: 'Alerta', html: msg, showConfirmButton: true });
      return;
    }
    var datos = ppDatosOrdenes();
    datos.push({ name: '_token', value: $('meta[name="csrf-token"]').attr('content') });
    datos.push({ name: 'id_solicitud', value: {{ intval($solicitud->id_solicitud) }} });
    datos.push({ name: 'id_procedimiento', value: {{ intval($id_procedimiento) }} });
    var $btn = $(this).prop('disabled', true);
    $.ajax({
      url: '/pp_agregar_ordenes',
      type: 'POST',
      data: datos,
      success: function (res) {
        if (res.success) {
          window.PP_ORDENES_GUARDADAS = true;
          if (window.self !== window.top) window.parent.PP_LISTADO_CAMBIO = true;
          var recargar = function () { window.location.reload(); };
          if (res.pendiente_token && typeof ppIniciarFirmaToken === 'function') {
            ppIniciarFirmaToken();
          } else {
            Swal.fire({ icon: 'success', title: res.message, showConfirmButton: false, timer: 1500 }).then(recargar);
          }
        } else {
          Swal.fire({ icon: 'warning', title: 'Alerta', html: res.message, showConfirmButton: true });
          $btn.prop('disabled', false);
        }
      },
      error: function () {
        Swal.fire({ icon: 'error', title: 'Alerta', html: 'No se pudo registrar las órdenes.', showConfirmButton: true });
        $btn.prop('disabled', false);
      }
    });
  });
</script>
