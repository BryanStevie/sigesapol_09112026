var listIdDiagnostico = [];
var listIdDiagnosticoObjetos = [];
var procedimientosSeleccionados = [];
var filasmedicamentos = 0;

$('#tblDiagnostico2 tr').each(function() {
    let idDiagnostico = $(this).find('input.nuevo_id_diagnostico').val();
    let nombreDiagnostico = $(this).find('input.nuevo_nombre_diagnostico').val();
    let codigoDiagnostico = $(this).find('input.nuevo_codigo_diagnostico').val();
    if (idDiagnostico && nombreDiagnostico) {
        if (listIdDiagnosticoObjetos.findIndex(e => e.id === idDiagnostico) === -1) {
            listIdDiagnosticoObjetos.push({
                id: idDiagnostico,
                nombre: nombreDiagnostico,
                codigo: codigoDiagnostico
            });
        }
        updateSelectElements();
    }
});

$("#id_departamento").select2({ max_selected_options: 4 }).focus();
$("#id_distrito").select2({ max_selected_options: 4 }).focus();
$("#id_provincia").select2({ max_selected_options: 4 }).focus();
$("#med_id_especialidad_prof").select2({ max_selected_options: 4 }).focus();



$('#addProducto').on('click', function() {
    AddProducto();
});

$('#example tbody').on('click', 'button.deleteFilaProducto', function() {

    var obj = $(this);
    // Selecciona la fila completa
    var fila = obj.closest('tr');

    // Obtiene la posición de la fila
    var filaPosition = fila.index();

    // Imprime la posición del elemento 'input' en la consola
    console.log("La posición del input es: " + filaPosition);
    eliminarDiagnosticoXProducto(filaPosition);
    // Elimina la fila
    obj.parent().parent().remove();
    filasmedicamentos--;
});

function eliminarDiagnosticoXProducto(posicion) {
    // Se elimina el elemento en la posición dada
    procedimientosSeleccionados.splice(posicion, 1);

    // Se actualiza la posición de los elementos restantes
    procedimientosSeleccionados.forEach((item, index) => {
        item.posicion = index;
    });
}

function getProducto() {

    $('#example tbody').html('');
    var id_ipress = $('#id_establecimiento').val();
    var id_consultorio = $('#id_servicio').val();
    var id_tiporeceta = $('#id_tipo_atencion').val();
    var id_farmacia = $('#id_farmacia_receta').val();

    var nivel = $('#id_nivel').val();
    var x = $('#nro_interconsultas').val();
    var dni = $('#dni_beneficiario').val();
    var interconsulta = $('#interconsulta').val();

    if (id_tiporeceta == "3") {

        if (interconsulta == 'SI') {
            if (id_consultorio == 104) {
                id_consultorio2 = 104
            } else {
                id_consultorio2 = 105
            }
            $.ajax({
                url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia + '/' + id_consultorio + '/' + id_consultorio2,
                success: function(result) {
                    var newOption = "";
                    newOption = "<option value=''>--Seleccionar--</option>";
                    $(result).each(function(ii, oo) {
                        newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
                    });
                    $('#cmbMedicamentoTemp').html(newOption);
                }
            });
        } else {

            if (x == 0) { //SIN INTERCONSULTA
                if (id_consultorio == 104) {
                    $.ajax({
                        url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia + '/' + 104,

                        success: function(result) {
                            var newOption = "";
                            newOption = "<option value=''>--Seleccionar--</option>";
                            $(result).each(function(ii, oo) {
                                newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
                            });
                            $('#cmbMedicamentoTemp').html(newOption);
                        }
                    });
                } else {
                    if (id_consultorio == 21) {
                        $.ajax({
                            url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia + '/' + 21,

                            success: function(result) {
                                var newOption = "";
                                newOption = "<option value=''>--Seleccionar-</option>";
                                $(result).each(function(ii, oo) {
                                    newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
                                });
                                $('#cmbMedicamentoTemp').html(newOption);

                            }
                        });
                    } else {
                        if (id_consultorio == 72) {
                            $.ajax({

                                url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia + '/' + 72,
                                success: function(result) {
                                    var newOption = "";
                                    newOption = "<option value=''>--Seleccionar-</option>";
                                    $(result).each(function(ii, oo) {
                                        newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
                                    });
                                    $('#cmbMedicamentoTemp').html(newOption);
                                }
                            });
                        } else {
                            $.ajax({
                                url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia + '/' + id_consultorio + '/' + 105,

                                success: function(result) {
                                    var newOption = "";
                                    newOption = "<option value=''>--Seleccionar-</option>";
                                    $(result).each(function(ii, oo) {
                                        newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
                                    });
                                    $('#cmbMedicamentoTemp').html(newOption);
                                }
                            });
                        }

                    }
                }
            } else {
                if (id_consultorio == 104) {
                    $.ajax({
                        url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress + '/' + 104 + '/' + dni,
                        success: function(result) {
                            var newOption = "";
                            newOption = "<option value=''>--Seleccionar--</option>";
                            $(result).each(function(ii, oo) {
                                newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
                            });
                            $('#cmbMedicamentoTemp').html(newOption);
                        }
                    });
                } else {
                    if (id_consultorio == 21) {
                        $.ajax({
                            url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress + '/' + 21 + '/' + dni,
                            success: function(result) {
                                var newOption = "";
                                newOption = "<option value=''>--Seleccionar-</option>";
                                $(result).each(function(ii, oo) {
                                    newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
                                });
                                $('#cmbMedicamentoTemp').html(newOption);
                            }
                        });
                    } else {
                        if (id_consultorio == 72) {
                            $.ajax({
                                url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia + '/' + 72,
                                success: function(result) {
                                    var newOption = "";
                                    newOption = "<option value=''>--Seleccionar-</option>";
                                    $(result).each(function(ii, oo) {
                                        newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
                                    });
                                    $('#cmbMedicamentoTemp').html(newOption);
                                }
                            });
                        } else {
                            $.ajax({
                                url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress + '/' + 105 + '/' + dni,
                                success: function(result) {
                                    var newOption = "";
                                    newOption = "<option value=''>--Seleccionar-</option>";
                                    $(result).each(function(ii, oo) {
                                        newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
                                    });
                                    $('#cmbMedicamentoTemp').html(newOption);
                                }
                            });
                        }

                    }
                }
            }
        }
    } else {

        if (id_tiporeceta == 2 && id_farmacia == 21) { //nutricional

            if (id_tiporeceta == 5 || id_tiporeceta == 2) {
                id_consultorio = 110;
            } else {
                id_consultorio = 111;
            }


            $.ajax({
                url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia + '/' + id_consultorio,
                success: function(result) {
                    var newOption = "";
                    newOption = "<option value=''>--Seleccionar-</option>";
                    $(result).each(function(ii, oo) {
                        newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
                    });
                    $('#cmbMedicamentoTemp').html(newOption);
                }
            });

        } else {
            if (id_tiporeceta == 2 && id_farmacia == 19) {
                id_consultorio = 76;
            }
            $.ajax({
                url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia + '/' + id_consultorio,
                success: function(result) {
                    var newOption = "";
                    newOption = "<option value=''>--Seleccionar-</option>";
                    $(result).each(function(ii, oo) {
                        newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo.nombre + ", " + oo.abreviatura + "</option>";
                    });
                    $('#cmbMedicamentoTemp').html(newOption);
                }
            });
        }
    }

}

function obtenerCodigoPorId(id) {
    const idNumero = parseInt(id, 10);
    const objeto = listIdDiagnosticoObjetos.find(objeto => {
        return objeto.id === idNumero;
    });
    return objeto ? objeto.codigo : null;
}
$(document).on('change', 'select[name="miSelectProd[]"]', function() {
    var idSeleccionado = $(this).val();
    var codigo = obtenerCodigoPorId(idSeleccionado);
    var ind = $('select[name="miSelectProd[]"]').index(this);
    if (codigo) {
        console.log('Código encontrado:', codigo);
        var ind = $(this).index('select[name="miSelectProd[]"]');
        $('#id_cie10_producto' + ind).val(codigo);
    }else{
        var cod_cie110 = $('#miSelectProd' + ind).find(':selected').data('codigo');
        $('#id_cie10_producto' + ind).val(cod_cie110);
    }
});

$(document).on('change', 'select[name="id_producto[]"]', function() {
    var selectedOption = $(this).find('option:selected');
    var codigo_producto = selectedOption.data('codigo');
    if (codigo_producto) {
        var ind = $(this).index('select[name="id_producto[]"]');
        $('#codigo_producto' + ind).val(codigo_producto);
    }
});


function AddProducto() {
    var tipo_receta = $('#tipo_receta').val();
    var id_consultorio = $('#id_consultorio').val();
    var id_farmacia = $('#id_farmacia_receta').val();
    if (tipo_receta == "") {
        bootbox.alert("Seleccione el tipo atención");
        return false;
    }
    if (id_consultorio == "") {
        bootbox.alert("Seleccione un servicio");
        return false;
    }
    var newRow = "";
    //var ind = $('#example tbody tr').length;
    var ind = filasmedicamentos
    var tabindex = 101;
    var nuevalperiodo = "";
    if (ind > 0) {
        $('.cmbMedicamento').each(function() {
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
    $('#cmbMedicamentoTemp option').each(function() {
        item_producto += "<option value=" + $(this).val() + " data-codigo='" + $(this).data('codigo') + "'>" + $(this).html() +
            "</option>"
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
    item_via_administracion += '<option value="9">Oftalmológica</option>';
    item_via_administracion += '<option value="10">Inhalatoria</option>';
    item_via_administracion += '<option value="11">Epidural</option>';
    item_via_administracion += '<option value="12">Intratecal</option>';

    newRow += '<tr>';
    newRow += '<td></td>';
    newRow += '<td><select class="form-control cmbMedicamento" id="cmbMedicamento' + ind + '" ind="' + ind + '" tabindex="' + tabindex + '" name="id_producto[]" style="width: 450px">' + item_producto + '</select></td>';
    newRow += '<input type="hidden" name="nro_stocks_almacen[]" id="nro_stocks_almacen" value=""/>';
    newRow += '<input type="hidden" name="nro_stocks_establecimiento[]" id="nro_stocks_establecimiento" value=""/>';
    newRow += '<td><input type="text" name="nro_stocks[]" required id="nro_stocks" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:70px" readonly=""/>';

    newRow += '<td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo[]" tabindex="' + (tabindex + 1) + '" required="" id="tiempo_periodo' + ind + '" class="nro_solicitado tiempo_periodo input-sm" style="margin-left:4px; width:70px" value="' + nuevalperiodo + '"/>';
    newRow += '<td><select tabindex="' + (tabindex + 1) + '" class="form-control id_via_administracion" id="cmbTipoViaAdministracion' + ind + '" name="id_via_administracion[]" style="margin:0 20px;width:130px">' + item_via_administracion + '</select></td>';
    newRow += '<td><input type="text" name="posologia[]" tabindex="' + (tabindex + 2) + '" required="" id="posologia' +
        ind + '" class="nro_solicitado posologia input-sm" style="margin-left:4px; width:180px" value=""/>';

    // newRow += '<input type="hidden" name="lotes_lote[]" id="lotes_lote" value=""/>';
    // newRow += '<input type="hidden" name="lotes_registro_sanitario[]" id="lotes_registro_sanitario" value=""/>';
    // newRow += '<input type="hidden" name="lotes_fecha_vencimiento[]" id="lotes_fecha_vencimiento" value=""/>';
    // newRow += '<input type="hidden" name="lotes_precio_unitario[]" id="lotes_precio_unitario" value=""/>';
    // newRow += '<input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/></td>';
    newRow += '<td><input onKeyPress="return soloNumerosMenosCero(event)" type="text" tabindex="' + (tabindex + 3) + '" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados[]" required="" id="nro_med_solictados' + ind + '" class="limpia_text nro_solicitado nro_med_solictados input-sm" style="margin-left:4px; width:50px" /></td>';
    newRow += '<td><input onKeyPress="return soloNumerosMenosCero(event)" type="text" tabindex="' + (tabindex + 3) + '" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad dispensada y presionar Enter para ingresar la cantidad dispensada" name="nro_med_dispensados[]" required="" id="nro_med_dispensados' + ind + '" class="limpia_text nro_dispensado nro_med_dispensados input-sm" style="margin-left:4px; width:50px" /></td>';
    newRow += '<td><input type="hidden" name="id_cie10_producto[]" id="id_cie10_producto' + ind + '"><input type="hidden" name="codigo_producto[]" id="codigo_producto' + ind + '"><select tabindex="' + (tabindex + 4) + '" class="form-control select-diagnostico"  id="miSelectProd' + ind + '" name="miSelectProd[]"></select></td>';
    newRow += '<td><button type="button" class="btn btn-primary btn-xs" id="add_producto' + ind + '"><i class="fa fa-plus"></i> </button>  <button type="button"  class="btn btn-danger deleteFilaProducto btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> </button></td>';
    newRow += '</tr>';
    $('#example tbody').append(newRow);
    $("#cmbMedicamento" + ind).select2({ max_selected_options: 4 }).focus();


    const selectElementProd = document.getElementById('miSelectProd' + ind);

    listIdDiagnosticoObjetos.forEach((item,index) => {
        let option = document.createElement('option');
        option.value = item.id;
        option.setAttribute('data-codigo', item.codigo); 
        option.textContent = item.nombre;
        selectElementProd.appendChild(option);
        if (index === 0) {
            console.log(index);
            $('#id_cie10_producto0').val(item.codigo);
            console.log(item.codigo);
           
        }
        
    });

    // var cod_diag = $('#miSelectProd' + ind).val();
    // var codigo = obtenerCodigoPorId(cod_diag);
    // $('#id_cie10_producto' + ind).val(codigo);

    $("#nro_med_solictados" + ind).keypress(function(e) {
        if (e.which == 13) {
            AddProducto();
            var id_farmacia = $('#id_farmacia').val();
            validproductoseleccionado(id_farmacia, this);
        }
    });
    $("#nro_med_dispensados" + ind).keypress(function(e) {
        if (e.which == 13) {
            AddProducto();
            var id_farmacia = $('#id_farmacia').val();
            validproductoseleccionado(id_farmacia, this);
        }
    });

    $("#add_producto" + ind).click(function(e) {
        var id_farmacia = $('#id_farmacia').val();
        AddProducto();
        validproductoseleccionado(id_farmacia, this);
        btnAgregarProducto = $(this);
        $(btnAgregarProducto).attr('disabled', 'disabled');
    });

    function validproductoseleccionado(id_farmacia, btn) { //Jose
        var dni_beneficiario = $('#dni_beneficiario').val();
        var nro_dias = 30;
        var objTr = $(btn).closest("tr");
        var id_producto = objTr.find(".cmbMedicamento").val();
        $.ajax({
            url: '/validacion_beneficiario_dias_by_producto_medico/' + dni_beneficiario + '/' + nro_dias + '/' + id_producto,
            success: function(result) {
                var msg = "";
                var n = 0;
                var flag = true;
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
                if (msg != "")
                /*{
                            verlotes(id_farmacia, btn);      
                        } 
                        else */
                {
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
                        callback: function(result) {
                            if (result == true) {
                                verlotes(id_farmacia, btn);
                            }
                        }
                    });
                }
            }
        });
    }

    $("#cmbMedicamento" + ind).on("change", function(e) {
        var flagx = 0;
        cmb = $(this);
        id_producto = $("#cmbMedicamento" + ind).val();

        $('.cmbMedicamento').each(function() {
            var ind_tmp = $(this).val();
            if ($(this).val() == id_producto) flagx++;
        });

        if (flagx > 1) {
            bootbox.alert("El producto farmaceutico ya ha sido ingresado");
            $("#cmbMedicamento" + ind).val("").trigger("change");
            return false;
        }
        id_farmacia_receta = $('#id_farmacia_receta').val();

        option = {
            url: '../cargarstock_porfarmacia/' + id_producto + '/' + id_farmacia_receta,
            type: 'GET',
            dataType: 'json',
            data: {}
        };

        $.ajax(option).done(function(data) {

            var cantidad = data.cantidad;
            var cantidadEstablecimiento = data.cantidadEstablecimiento;
            var cantidadAlmacen = data.cantidadAlmacen;
            $(cmb).closest("tr").find(".limpia_text").val("");
            //$(cmb).closest("tr").find("#nro_stocks").val(data);
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
}


function maxlength_doc_usu(tipodoc, numerodoc) {
    const combo = document.getElementById(tipodoc);
    const input = document.getElementById(numerodoc);

    if (!combo || !input) {
        console.error("El elemento select o input no existe");
        return;
    }
    $(input).val('');

    const selectedIndex = combo.selectedIndex;
    if (selectedIndex === -1) {
        console.error("No hay un elemento seleccionado en el select");
        return;
    }
    const tipo_doc_ident = combo.options[selectedIndex].text;
    if ($(combo).val() === "1") {
        $(input).attr('maxlength', '8'); // DNI o similar
    } else {
        $(input).attr('maxlength', '12'); // Otros documentos
    }
    $(input).focus();
}

function datosretornadosDiagnosticoAutocomplete(ind, n) {
    var orden = '';
    if (n == 2) { orden = 2; } else { orden = ''; }
    var sexo = $('#sexo').val();
    let genero = 0;
    if (sexo == 'M') {
        genero = 1;
    } else if (sexo == 'F') {
        genero = 2;
    }

    $("#nombre_diagnostico" + orden + ind).autocomplete({
        minLength: 1,
        delay: 400,
        source: function(request, response) {
            $.ajax({
                url: '../obtener_diagnostico_genero/' + request.term + '/' + genero,
                dataType: "json",
                success: function(data) {
                    let listaDiagnosticoSeleccionado = $(".id_diagnostico" + orden).map(function() { return parseInt($(this).val()); }).get();
                    let filtro = data.filter((element) => !listaDiagnosticoSeleccionado.includes(element.id));
                    response(filtro);
                }
            });
        },
        select: function(e, ui) {
            var id_diagnostico = ui.item.id;
            $("#id_diagnostico" + orden + ind).val(id_diagnostico);
            $("#nombre_diagnostico" + orden + ind).prop("readonly", true);
        }
    });

    $("#nombre_diagnostico" + orden + ind).on('blur', function() {
        var id_diagnostico = $("#id_diagnostico" + orden + ind).val();
        if (id_diagnostico == "") {
            bootbox.alert("Falta ingresar el diagnostico en esta fila");
            $("#nombre_diagnostico" + orden + ind).val("");
            return false;
        } else {
            addNewArray(id_diagnostico);
        }
    });

}

function datosretornadosDiagnosticoAltaAutocomplete(ind) {
    var sexo = $('#sexo').val();
    let genero = 0;
    if (sexo == 'M') {
        genero = 1;
    } else if (sexo == 'F') {
        genero = 2;
    }

    $("#nombre_diagnostico_alta" + ind).autocomplete({
        minLength: 1,
        delay: 400,
        source: function(request, response) {
            $.ajax({
                url: '../obtener_diagnostico_genero/' + request.term + '/' + genero,
                dataType: "json",
                success: function(data) {
                    let listaDiagnosticoSeleccionado = $(".id_diagnostico_alta" + ind).map(function() { return parseInt($(this).val()); }).get();
                    let filtro = data.filter((element) => !listaDiagnosticoSeleccionado.includes(element.id));
                    response(filtro);
                }
            });
        },
        select: function(e, ui) {
            var id_diagnostico = ui.item.id;
            $("#id_diagnostico_alta" + ind).val(id_diagnostico);
            $("#nombre_diagnostico_alta" + ind).prop("readonly", true);
        }
    });

    $("#nombre_diagnostico_alta" + ind).on('blur', function() {
        var id_diagnostico = $("#id_diagnostico_alta" + ind).val();
        if (id_diagnostico == "") {
            bootbox.alert("Falta ingresar el diagnostico en esta fila");
            $("#nombre_diagnostico" + ind).val("");
            return false;
        } else {
            addNewArray(id_diagnostico);
        }
    });

}

function datosretornadosProcedimientoAutocomplete(ind) {
    var nivel = $('#id_nivel').val();
    var sexo = $('#nomsexo').val();
    let genero = 0;
    if (sexo == 'M')
        genero = 1;
    else
        genero = 2;

    $("#nombre_procedimiento_ext" + ind).autocomplete({
        minLength: 1,
        delay: 400,
        source: function(request, response) {
            console.log("Realizando solicitud AJAX para: ", request.term);
            $.ajax({
                url: '../obtener_procedimiento_convenio_emergencia/' + request.term + '/' + nivel + '/' + genero + '/' + 1,
                //url: '../obtener_procedimiento/' + request.term,
                dataType: "json",
                success: function(data) {
                    let listaProcedimientoSelecionado = $(".id_procedimiento").map(function() { return parseInt($(this).val()); }).get();
                    let filtro = data.filter((element) => !listaProcedimientoSelecionado.includes(element.id));
                    response(filtro);
                }
            });
        },
        select: function(e, ui) {
            var id_procedimiento = ui.item.id;
            $("#id_procedimiento" + ind).val(id_procedimiento);
            $("#nombre_procedimiento_ext" + ind).prop("readonly", true);
        }
    });

    $("#nombre_procedimiento_ext" + ind).on('blur', function() {
        var id_procedimiento = $("#id_procedimiento" + ind).val();
        if (id_procedimiento == "") {
            bootbox.alert("Falta ingresar el procedimiento en esta fila");
            $("#nombre_procedimiento" + ind).val("");
            return false;
        }
    });
    $("#procedimiento_cantidad" + ind).on('blur', function() {
        var id_procedimiento = $("#id_procedimiento" + ind).val();
        var cantidad = $("#procedimiento_cantidad" + ind).val();
        if (id_procedimiento == "") {
            bootbox.alert("Falta ingresar la cantidad en este procedimiento");
            $("#procedimiento_cantidad" + ind).val("");
            return false;
        }
        // Verifica si la cantidad es menor o igual a 0
        if (parseFloat(cantidad) <= 0) {
            bootbox.alert("Por favor, ingrese una cantidad mayor a 0");
            $("#procedimiento_cantidad" + ind).val("");
            return false;
        }
    });

}

function AddDiagnostico() {
    var newRow = "";
    var ind = $('#tblDiagnostico tbody tr').length;

    if (ind > 0) {
        $('.id_diagnostico').each(function() {
            var ind_tmp = $(this).data("id");
            if (ind_tmp => ind) {
                ind = Number(ind_tmp) + 1;
            }
        });

        fila_medicamentos = $('#tblDiagnostico tbody').children('tr');
        rowIndex = $('#hidden_rowIndex').val();
        var idval = ind - 1;
        fila_medicamento = fila_medicamentos[idval];

    }

    var item_tipo_diagnostico = '';
    item_tipo_diagnostico += '<option value="0">--Seleccionar--</option>';
    item_tipo_diagnostico += '<option value="1">PRESUNTIVO</option>';
    item_tipo_diagnostico += '<option value="2">DEFINITIVO</option>';
    item_tipo_diagnostico += '<option value="3">REITERATIVO</option>';

    var item_clasificacion = '';
    item_clasificacion += '<option value="0">--Seleccionar--</option>';
    item_clasificacion += '<option value="1">ATENCIÓN</option>';
    item_clasificacion += '<option value="2">EVALUACIÓN</option>';

    newRow += '<tr>';
    newRow +=
        '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico[]" tabindex="8" required="" id="nombre_diagnostico' +
        ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
    newRow += '<input type="hidden" class="form-control id_diagnostico typeahead tt-query" id="id_diagnostico' + ind +
        '" name="id_diagnostico_ingreso_nuevos[]" data-id=' + ind + '></td>';
    newRow += '<td><select class="form-control id_tipo_diagnostico" id="cmbTipoDiagnostico' + ind +
        '" name="id_diagnostico_ingreso_nuevos_tipo_diagnostico[]" style="margin:0 20px;width:150px">' + item_tipo_diagnostico + '</select></td>';
    newRow += '<td><select class="form-control id_clasificacion_diagnostico" id="cmbClasificacion' + ind +
        '" name="id_clasificacion[]" style="margin:0 20px;width:150px">' + item_clasificacion + '</select></td>';
    newRow +=
        '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow += '</tr>';
    $('#tblDiagnostico tbody').append(newRow);


    var sexo = $('#sexo').val();
    let genero = 0;
    if (sexo == 'M') {
        genero = 1;
    } else {
        if (sexo == 'F') {
            genero = 2;
        }
    }

    $("#nombre_diagnostico" + ind).autocomplete({
        minLength: 1,
        delay: 400,
        source: function(request, response) {
            $.ajax({
                url: '../obtener_diagnostico_genero/' + request.term + '/' + genero,
                dataType: "json",
                success: function(data) {
                    let listaDiagnosticoSeleccionado = $(".id_diagnostico").map(function() { return parseInt($(this).val()); }).get();
                    let filtro = data.filter((element) => !listaDiagnosticoSeleccionado.includes(element.id));
                    response(filtro);
                }
            })
        },
        select: function(e, ui) {
            var id_diagnostico = ui.item.id;
            $("#id_diagnostico" + ind).val(id_diagnostico);
            $("#nombre_diagnostico" + ind).prop("readonly", true);
        }
    });

    $("#nombre_diagnostico" + ind).on('blur', function() {
        var id_diagnostico = $("#id_diagnostico" + ind).val();
        if (id_diagnostico == "") {
            bootbox.alert("Falta ingresar el diagnostico en esta fila");
            $("#nombre_diagnostico" + ind).val("");
            return false;
        }
    });
}

function updateSelectElements() {
    // Obtener todos los elementos <select> con la clase "select-diagnostico"
    let selectElements = document.querySelectorAll(".select-diagnostico");

    selectElements.forEach((selectElement) => {
        // Almacenar el valor actualmente seleccionado
        let currentValue = selectElement.value;

        // Eliminar las opciones actuales
        selectElement.innerHTML = "";


        // Agregar las opciones basadas en listIdDiagnosticoObjetos
        listIdDiagnosticoObjetos.forEach((item) => {
            let option = document.createElement("option");
            option.value = item.id;
            option.textContent = item.nombre;
            option.setAttribute('data-codigo', item.codigo); 
            selectElement.appendChild(option);
        });

        // Verificar si el valor seleccionado aún está disponible en las opciones
        let optionExists = [];
        if (selectElement.options) {
            optionExists = Array.from(selectElement.options).some(
                (option) => option.value === currentValue
            );
        }
        // Si el valor 1  e2  ado no existe en las opciones, cambiar a "Seleccione"
        if (!optionExists) {
            selectElement.value = "";
        } else {
            // Si el valor seleccionado aún está disponible en las opciones, restablecer el valor seleccionado
            selectElement.value = currentValue;
            console.log()
        }
    });


}

function addNewArray(id_diagnostico) {
    $('#tblDiagnostico2 tr').each(function() {
        let idDiagnostico = $(this).find('input.nuevo_id_diagnostico').val();
        let nombreDiagnostico = $(this).find('input.nuevo_nombre_diagnostico').val();
        let codigoDiagnostico = $(this).find('input.nuevo_codigo_diagnostico').val();
        
        if (idDiagnostico && nombreDiagnostico) {
            if (listIdDiagnosticoObjetos.findIndex(e => e.id === idDiagnostico) === -1) {
                listIdDiagnosticoObjetos.push({
                    id: idDiagnostico,
                    nombre: nombreDiagnostico,
                    codigo: codigoDiagnostico
                });
            }
        }
    });

    if (listIdDiagnosticoObjetos.findIndex(e => e.id == id_diagnostico) == -1) {
        listIdDiagnostico.push(id_diagnostico); // solo es para validar si existen datos

        if (listIdDiagnostico.length > 0) {

            let codigo = JSON.stringify(id_diagnostico);
            // Realizar la petición AJAX usando el código id_diagnostico
            $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": "/obtenerdatosDiagnosticos",
                "data": {
                    codigo: codigo
                },
                "success": function(result) {
                    listIdDiagnosticoObjetos.push(result.objeto);
                    console.log(JSON.stringify(listIdDiagnosticoObjetos));
                    updateSelectElements();
                },
                "error": function(msg, textStatus, errorThrown) {
                    //location.href="login";
                }
            });
        }
    }


}

function AddDiagnostico2() {
    var newRow = "";
    var ind = $('#tblDiagnostico2 tbody tr').length;

    if (ind > 0) {
        $('.id_diagnostico2').each(function() {
            var ind_tmp = $(this).data("id");
            if (ind_tmp => ind) {
                ind = Number(ind_tmp) + 1;
            }
        });

        fila_medicamentos = $('#tblDiagnostico2 tbody').children('tr');
        rowIndex = $('#hidden_rowIndex').val();
        var idval = ind - 1;
        fila_medicamento = fila_medicamentos[idval];

    }

    var item_tipo_diagnostico = '';
    item_tipo_diagnostico += '<option value="0">--Seleccionar--</option>';
    item_tipo_diagnostico += '<option value="1">PRESUNTIVO</option>';
    item_tipo_diagnostico += '<option value="2">DEFINITIVO</option>';
    item_tipo_diagnostico += '<option value="3">REITERATIVO</option>';

    var item_clasificacion = '';
    item_clasificacion += '<option value="N">--Seleccionar--</option>';
    item_clasificacion += '<option value="0">ATENCIÓN</option>';
    item_clasificacion += '<option value="1">EVALUACIÓN</option>';

    var item_ingreso_egreso = '';
    item_ingreso_egreso += '<option value="I">Ingreso</option>';
    item_ingreso_egreso += '<option value="E">Egreso</option>';

    newRow += '<tr>';
    newRow +=
        '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico2[]" tabindex="8" required="" id="nombre_diagnostico2' +
        ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
    newRow += '<input type="hidden" class="form-control id_diagnostico2 typeahead tt-query" id="id_diagnostico2' + ind +
        '" name="id_diagnostico_externo_nuevos[]" data-id=' + ind + '></td>';
    newRow += '<td><select class="form-control id_tipo_diagnostico2" id="cmbTipoDiagnostico2' + ind +
        '" name="id_diagnostico_externo_nuevos_tipo_diagnostico[]" style="margin:0 20px;width:150px">' + item_tipo_diagnostico + '</select></td>';
    newRow += '<td><select class="form-control id_clasificacion_diagnostico2" id="cmbClasificacion2' + ind +
        '" name="id_clasificacion2[]" style="margin:0 20px;width:150px">' + item_clasificacion + '</select></td>';
    newRow += '<td><select class="form-control" id="cmb_ingreso_egreso' + ind +
        '" name="ingreso_egreso[]" style="margin:0 20px;width:150px">' + item_ingreso_egreso + '</select></td>';
    newRow +=
        '<td><button type="button" class="btn btn-danger deleteFila2 btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow += '</tr>';
    $('#tblDiagnostico2 tbody').append(newRow);
    var sexo = $('#nomsexo').val();
    let genero = 0;
    if (sexo == 'M') {
        genero = 1;
    } else {
        if (sexo == 'F') {
            genero = 2;
        }
    }

    $("#nombre_diagnostico2" + ind).autocomplete({
        minLength: 1,
        delay: 400,
        source: function(request, response) {
            $.ajax({
                //url: '../obtener_diagnostico/' + request.term,
                url: '../obtener_diagnostico_genero/' + request.term + '/' + genero,
                dataType: "json",
                success: function(data) {
                    let listaDiagnosticoSeleccionado = $(".id_diagnostico2").map(function() { return parseInt($(this).val()); }).get();
                    let filtro = data.filter((element) => !listaDiagnosticoSeleccionado.includes(element.id));
                    response(filtro);
                }
            })

        },
        select: function(e, ui) {
            var id_diagnostico = ui.item.id;
            $("#id_diagnostico2" + ind).val(id_diagnostico);
            $("#nombre_diagnostico2" + ind).prop("readonly", true);
        }
    });

    $("#nombre_diagnostico2" + ind).on('blur', function() {
        var id_diagnostico = $("#id_diagnostico2" + ind).val();
        if (id_diagnostico == "") {
            bootbox.alert("Falta ingresar el diagnostico en esta fila");
            $("#nombre_diagnostico2" + ind).val("");
            return false;
        } else {
            addNewArray(id_diagnostico);
        }
    });

}

function AddDiagnosticoAlta() {
    var newRow = "";
    var ind = $('#tblDiagnosticoAlta tbody tr').length;

    if (ind > 0) {
        $('.id_diagnostico').each(function() {
            var ind_tmp = $(this).data("id");
            if (ind_tmp => ind) {
                ind = Number(ind_tmp) + 1;
            }
        });

        fila_medicamentos = $('#tblDiagnosticoAlta tbody').children('tr');
        rowIndex = $('#hidden_rowIndex').val();
        var idval = ind - 1;
        fila_medicamento = fila_medicamentos[idval];

    }

    var item_clasificacion = '';
    item_clasificacion += '<option value="N">--Seleccionar--</option>';
    item_clasificacion += '<option value="0">SECUNDARIO</option>';
    item_clasificacion += '<option value="2">DIAGNOSTICO DE CABECERA</option>';

    newRow += '<tr>';
    newRow +=
        '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el diagnostico" name="nombre_diagnostico_alta[]" tabindex="8" required="" id="nombre_diagnostico_alta' +
        ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
    newRow += '<input type="hidden" id="existe_datos_diagnostico_alta' + ind + '"  value="1"><input type="hidden" class="form-control id_diagnostico_alta typeahead tt-query" id="id_diagnostico_alta' + ind +
        '" name="id_diagnostico_alta[]" value="" data-id=' + ind + '></td>';
    newRow += '<td>DEFINITIVO</td>';
    newRow += '<td><select tabindex="45" class="form-control id_clasificacion_alta" id="cmbClasificacion_alta' + ind + '" name="id_clasificacion_alta[]" style="margin:0 20px;width:150px">' + item_clasificacion + '</select></td>';
    newRow +=
        '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow += '</tr>';
    $('#tblDiagnosticoAlta tbody').append(newRow);


    var sexo = $('#sexo').val();
    let genero = 0;
    if (sexo == 'M') {
        genero = 1;
    } else {
        if (sexo == 'F') {
            genero = 2;
        }
    }

    $("#nombre_diagnostico_alta" + ind).autocomplete({
        minLength: 1,
        delay: 400,
        source: function(request, response) {
            $.ajax({
                url: '../obtener_diagnostico_genero/' + request.term + '/' + genero,
                dataType: "json",
                success: function(data) {
                    let listaDiagnosticoSeleccionado = $(".id_diagnostico_alta").map(function() { return parseInt($(this).val()); }).get();
                    let filtro = data.filter((element) => !listaDiagnosticoSeleccionado.includes(element.id));
                    response(filtro);
                }
            })
        },
        select: function(e, ui) {
            var id_diagnostico = ui.item.id;
            $("#id_diagnostico_alta" + ind).val(id_diagnostico);
            $("#nombre_diagnostico_alta" + ind).prop("readonly", true);
        }
    });

    $("#nombre_diagnostico_alta" + ind).on('blur', function() {
        var id_diagnostico = $("#id_diagnostico_alta" + ind).val();
        if (id_diagnostico == "") {
            bootbox.alert("Falta ingresar el diagnostico en esta fila");
            $("#nombre_diagnostico_alta" + ind).val("");
            return false;
        }
    });
}

function AddProcedimiento() {
    var newRow = "";
    var ind = $('#tblProcedimiento tbody tr').length;

    if (ind > 0) {
        $('.id_procedimiento').each(function() {
            var ind_tmp = $(this).data("id");
            if (ind_tmp => ind) {
                ind = Number(ind_tmp) + 1;
            }
        });

        fila_medicamentos = $('#tblProcedimiento tbody').children('tr');
        rowIndex = $('#hidden_rowIndex').val();
        var idval = ind - 1;
        fila_medicamento = fila_medicamentos[idval];
    }

    newRow += '<tr>';
    newRow +=
        '<td><input type="text" data-toggle="tooltip" data-placement="top" title="Ingresar el procedimiento" name="nombre_procedimiento[]" tabindex="8" required="" id="nombre_procedimiento' +
        ind + '" class="input-sm" style="margin-left:4px; width:100%; text-align:left" />';
    newRow += '<input type="hidden" id="existe_datos_procedimiento' + ind + '"  value="1"><input type="hidden" class="form-control typeahead tt-query id_procedimiento" id="id_procedimiento' + ind +
        '" name="id_procedimiento[]" value="" data-id=' + ind + '></td>';
    newRow +=
        '<td><input type="text" maxlength="2" placeholder="Cantidad" class="form-control procedimiento_cantidad typeahead tt-query" style="margin:0 20px;width:150px" id="procedimiento_cantidad' +
        ind + '" name="procedimiento_cantidad[]" value="1"></td>';
    newRow += '<td><select class="form-control select-diagnostico"  id="miSelect' + ind + '" name="miSelect[]"></select></td>';
    newRow +=
        '<td><button type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow += '</tr>';
    $('#tblProcedimiento tbody').append(newRow);
    const selectElement = document.getElementById('miSelect' + ind);
    let defaultOption = document.createElement('option');
    //si entra la primera vez
    var i = 0;
    listIdDiagnosticoObjetos.forEach((item) => {
        let option = document.createElement('option');
        option.value = item.id;
        option.textContent = item.nombre;
        if (i == 0) {
            option.setAttribute("selected", "selected");
            i++;
        }
        selectElement.appendChild(option);
    });

    var nivel = $('#id_nivel').val();
    var sexo = $('#nomsexo').val();
    if (sexo == 'M')
        genero = 1;
    else
        genero = 2;

    //tipo = 1
    $("#nombre_procedimiento" + ind).autocomplete({
        minLength: 1,
        delay: 400,
        source: function(request, response) {
            $.ajax({
                //url: '../obtener_procedimiento_convenio_emergencia/' + request.term + '/' + nivel + '/' + genero + '/' + 1,
                url: '../obtener_procedimiento_auditorias/' + request.term + '/' + nivel + '/' + genero + '/' + 1,
                dataType: "json",
                success: function(data) {
                    let listaProcedimientoSelecionado = $(".id_procedimiento").map(function() { return parseInt($(this).val()); }).get();
                    let filtro = data.filter((element) => !listaProcedimientoSelecionado.includes(element.id));
                    response(filtro);
                }
            });
        },
        select: function(e, ui) {
            var id_procedimiento = ui.item.id;
            $("#id_procedimiento" + ind).val(id_procedimiento);
            $("#nombre_procedimiento" + ind).prop("readonly", true);
        }
    });

    $("#nombre_procedimiento" + ind).on('blur', function() {
        var id_procedimiento = $("#id_procedimiento" + ind).val();
        if (id_procedimiento == "") {
            bootbox.alert("Falta ingresar el procedimiento en esta fila");
            $("#nombre_procedimiento" + ind).val("");
            return false;
        }
    });
    $("#procedimiento_cantidad" + ind).on('blur', function() {
        var id_procedimiento = $("#id_procedimiento" + ind).val();
        var cantidad = $("#procedimiento_cantidad" + ind).val();
        if (id_procedimiento == "") {
            bootbox.alert("Falta ingresar la cantidad en este procedimiento");
            $("#procedimiento_cantidad" + ind).val("");
            return false;
        }
        // Verifica si la cantidad es menor o igual a 0
        if (parseFloat(cantidad) <= 0) {
            bootbox.alert("Por favor, ingrese una cantidad mayor a 0");
            $("#procedimiento_cantidad" + ind).val("");
            return false;
        }
    });
}

//LUGAR DE NACIMIENTO DEL AFILIADO
function getProvincia() {
    var newOption = "";
    newOption = "<option value=''>SELECCIONE</option>";
    $('#id_provincia').html(newOption);


    var Departamento = $('#id_departamento').val();

    $.ajax({
        url: '/obtener_provincia_por_departamento/' + Departamento,
        async: false,
        success: function(result) {
            var newOption = ""; //var cant=0;
            newOption = "<option value=''>SELECCIONE</option>";
            $(result).each(function(ii, oo) {
                newOption += "<option value='" + oo.id_provincia + "'>" + oo.descripcion + "</option>";
            });
            $('#id_provincia').html(newOption);
        }
    });
}

function getDistrito() {
    var newOption = "";
    newOption = "<option value=''>SELECCIONE</option>";
    $('#id_distrito').html(newOption);

    var Provincia = $('#id_provincia').val();

    $.ajax({
        url: '/obtener_distrito_por_provincia/' + Provincia,
        async: false,
        success: function(result) {
            var newOption = "";
            newOption = "<option value=''>SELECCIONE</option>";
            $(result).each(function(ii, oo) {
                newOption += "<option value='" + oo.id_distrito + "'>" + oo.descripcion + "</option>";
            });
            $('#id_distrito').html(newOption);
        }
    });
}

//FIN FUNCIONES

//----------------------------------TEMPERATURA--------------------------------------------------
$("#temperatura").on('blur', function(event) {
    var value = $(this).val();
    //console.log('blur');  
    if (Validar.esVacio(value) == false) {
        if (!Validar.decimalEnRango(value, 35.0, 40.0)) {
            $('#temperatura').addClass('borde-temperatura-hipertermia');
            $('#texto-aceptado').html("<p><strong>" + 'La temperatura debe estar entre 35.00 a 40.00 ( °C )' + "</strong></p>");
            $('#texto-aceptado strong').addClass('borde-temperatura-valida');

        } else if (parseFloat(value) >= 35.0 && parseFloat(value) < 36.0) { //! AZUL
            $('#temperatura').addClass('borde-temperatura-hipotermia');
            $('#texto-aceptado').html("<strong>" + 'Paciente con Hipotermia' + "</strong>");

        } else if (parseFloat(value) >= 36.0 && parseFloat(value) < 37.5) { //! VERDE
            $('#temperatura').addClass('borde-temperatura-normal');
            $('#texto-aceptado').html("<strong>" + 'Paciente con temperatura Normal' + "</strong>");

        } else if (parseFloat(value) >= 37.5 && parseFloat(value) < 39.5) { // AMARILLO
            $('#temperatura').addClass('borde-temperatura-fiebre');
            $('#texto-aceptado').html("<strong>" + 'Paciente con Fiebre' + "</strong>");

        } else if (parseFloat(value) >= 39.5 && parseFloat(value) < 41.0) { // NARANJA
            $('#temperatura').addClass('borde-temperatura-fiebre-alta');
            $('#texto-aceptado').html("<strong>" + 'Paciente con Fiebre Alta' + "</strong>");

        } else if (parseFloat(value) >= 41.0 && parseFloat(value) <= 45.0) {
            $('#temperatura').addClass('borde-temperatura-hipertermia');
            $('#texto-aceptado').html("<strong>" + 'Paciente con Hipertermia' + "</strong>");
        }
    } else if (Validar.esVacio(value)) {
        $('#temperatura').addClass('borde-temperatura-hipertermia');
        $('#texto-aceptado').html("<p><strong>" + 'Ingrese el valor de la temperatura ( °C )' + "</strong></p>");
        $('#texto-aceptado strong').addClass('borde-temperatura-valida');
    }
});
$("#temperatura").on('input', function(event) {
    var value = $(this).val();
    //console.log('input');
    if (Validar.esVacio(value)) {
        $('#temperatura').addClass('borde-temperatura-hipertermia');
        $('#texto-aceptado').html("<p><strong>" + 'Ingrese el valor de la temperatura ( °C )' + "</strong></p>");
        $('#texto-aceptado strong').addClass('borde-temperatura-valida');
    } else if (!Validar.decimalEnRango(value, 35.0, 40.0)) {
        $('#temperatura').addClass('borde-temperatura-hipertermia');
        $('#texto-aceptado').html("<p><strong>" + 'La temperatura debe estar entre 35.00 a 40.00 ( °C )' + "</strong></p>");
        $('#texto-aceptado strong').addClass('borde-temperatura-valida');
    } else if (parseFloat(value) >= 35.0 && parseFloat(value) < 36.0) { //! AZUL
        $('#temperatura').addClass('borde-temperatura-hipotermia');
        $('#texto-aceptado').html("<strong>" + 'Paciente con Hipotermia' + "</strong>");

    } else if (parseFloat(value) >= 36.0 && parseFloat(value) < 37.5) { //! VERDE
        $('#temperatura').addClass('borde-temperatura-normal');
        $('#texto-aceptado').html("<strong>" + 'Paciente con temperatura Normal' + "</strong>");

    } else if (parseFloat(value) >= 37.5 && parseFloat(value) < 39.5) { // AMARILLO
        $('#temperatura').addClass('borde-temperatura-fiebre');
        $('#texto-aceptado').html("<strong>" + 'Paciente con Fiebre' + "</strong>");

    } else if (parseFloat(value) >= 39.5 && parseFloat(value) < 41.0) { // NARANJA
        $('#temperatura').addClass('borde-temperatura-fiebre-alta');
        $('#texto-aceptado').html("<strong>" + 'Paciente con Fiebre Alta' + "</strong>");

    } else if (parseFloat(value) >= 41.0 && parseFloat(value) <= 45.0) {
        $('#temperatura').addClass('borde-temperatura-hipertermia');
        $('#texto-aceptado').html("<strong>" + 'Paciente con Hipertermia' + "</strong>");

    }

});
$("#temperatura").on('input', function() {
    $(this).removeClass('borde-temperatura borde-temperatura-normal borde-temperatura-fiebre borde-temperatura-fiebre-alta borde-temperatura-hipertermia');
    $('#texto-aceptado').html('');
});
//-------------------------------------FIN TEMPERATURA----------------------------------------------

//-------------------------------------FRECUENCIA RESPIRATORIA-------------------------------------

$("#frecuencia_respiratoria").on('blur', function(event) {
    var value = $('#frecuencia_respiratoria').val();
    if (Validar.esVacio(value) == false) {
        if (!Validar.enteroEnRango(value, 10, 35)) {
            $('#frecuencia_respiratoria').addClass('borde-temperatura-hipertermia');
            $('#frecuencia_respiratoria-aceptado').html("<p><strong>" + 'El valor de la Frec. Respiratoria  debe estar entre 10 a 35 ( /min )' + "</strong></p>");
            $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');
        }
    } else if (Validar.esVacio(value)) {
        $('#frecuencia_respiratoria').addClass('borde-temperatura-hipertermia');
        $('#frecuencia_respiratoria-aceptado').html("<p><strong>" + 'Ingrese el valor de la Frecuencia Respiratoria ( /min )' + "</strong></p>");
        $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');
    }
});
$("#frecuencia_respiratoria").on('input', function(event) {
    var value = $('#frecuencia_respiratoria').val();
    if (Validar.esVacio(value)) {
        $('#frecuencia_respiratoria').addClass('borde-temperatura-hipertermia');
        $('#frecuencia_respiratoria-aceptado').html("<p><strong>" + 'Ingrese el valor de la Frecuencia Respiratoria ( /min )' + "</strong></p>");
        $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');

    } else if (!Validar.enteroEnRango(value, 10, 35)) {
        $('#frecuencia_respiratoria').addClass('borde-temperatura-hipertermia');
        $('#frecuencia_respiratoria-aceptado').html("<p><strong>" + 'El valor de la Frec. Respiratoria  debe estar entre 10 a 35 ( /min )' + "</strong></p>");
        $('#frecuencia_respiratoria-aceptado strong').addClass('borde-temperatura-valida');

    }
});
$("#frecuencia_respiratoria").on('input', function() {
    $('#frecuencia_respiratoria').removeClass('borde-temperatura-hipertermia');
    $('#frecuencia_respiratoria-aceptado').html('');
});
//----------------------------------FIN FRECUENCIA RESPIRATORIA---------------------------------

//---------------------------------FRECUENCIA CARDIACA----------------------------------------------
$("#frecuencia_cardiaca").on('blur', function(event) {
    var value = $('#frecuencia_cardiaca').val();
    if (Validar.esVacio(value) == false) {
        if (!Validar.enteroEnRango(value, 50, 150)) {
            $('#frecuencia_cardiaca').addClass('borde-temperatura-hipertermia');
            $('#frecuencia_cardiaca-aceptado').html("<p><strong>" + 'El valor de la Frecuencia Cardiaca debe ser entre 50 a 150 ( /min )' + "</strong></p>");
            $('#frecuencia_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
        }
    } else if (Validar.esVacio(value)) {
        $('#frecuencia_cardiaca').addClass('borde-temperatura-hipertermia');
        $('#frecuencia_cardiaca-aceptado').html("<p><strong>" + 'Ingrese el valor de la Frecuencia Cardiaca ( /min )' + "</strong></p>");
        $('#frecuencia_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
    }

});
$("#frecuencia_cardiaca").on('input', function(event) {
    var value = $('#frecuencia_cardiaca').val();
    if (Validar.esVacio(value)) {
        $('#frecuencia_cardiaca').addClass('borde-temperatura-hipertermia');
        $('#frecuencia_cardiaca-aceptado').html("<p><strong>" + 'Ingrese el valor de la Frecuencia Cardiaca ( /min )' + "</strong></p>");
        $('#frecuencia_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
    } else if (!Validar.enteroEnRango(value, 50, 150)) {
        $('#frecuencia_cardiaca').addClass('borde-temperatura-hipertermia');
        $('#frecuencia_cardiaca-aceptado').html("<p><strong>" + 'El valor de la Frecuencia Cardiaca debe ser entre 50 a 150 ( /min )' + "</strong></p>");
        $('#frecuencia_cardiaca-aceptado strong').addClass('borde-temperatura-valida');
    }
});
$("#frecuencia_cardiaca").on('input', function() {
    $('#frecuencia_cardiaca').removeClass('borde-temperatura-hipertermia');
    $('#frecuencia_cardiaca-aceptado').html('');
});
//---------------------------------FIN FRECUENCIA CARDIACA----------------------------------------------

//------------------------------------PRESION ARTERIAL------------------------------------------
$("#presion_arterial_1").on('blur', function(event) {
    var value = $(this).val();
    if (Validar.esVacio(value) == false) {
        if (!Validar.enteroEnRango(value, 0, 250)) {
            $('#presion_arterial_1').addClass('borde-temperatura-hipertermia');
            $('#presion_arterial_1-aceptado').html("<p><strong>" + 'El valor debe estar entre 0 a 250' + "</strong></p>");
            $('#presion_arterial_1-aceptado strong').addClass('borde-temperatura-valida');
        }
    } else if (Validar.esVacio(value)) {
        $('#presion_arterial_1').addClass('borde-temperatura-hipertermia');
        $('#presion_arterial_1-aceptado').html("<p><strong>" + 'Ingrese el valor de la Presion arterial' + "</strong></p>");
        $('#presion_arterial_1-aceptado strong').addClass('borde-temperatura-valida');
    }
});

$("#presion_arterial_1").on('input', function(event) {

    var value = $('#presion_arterial_1').val();
    if (Validar.esVacio(value)) {
        $('#presion_arterial_1').addClass('borde-temperatura-hipertermia');
        $('#presion_arterial_1-aceptado').html("<p><strong>" + 'Ingrese el valor de la Presion arterial' + "</strong></p>");
        $('#presion_arterial_1-aceptado strong').addClass('borde-temperatura-valida');

    } else if (!Validar.enteroEnRango(value, 0, 250)) {
        $('#presion_arterial_1').addClass('borde-temperatura-hipertermia');
        $('#presion_arterial_1-aceptado').html("<p><strong>" + 'El valor debe estar entre 0 a 250' + "</strong></p>");
        $('#presion_arterial_1-aceptado strong').addClass('borde-temperatura-valida');
    }

});

$("#presion_arterial_1").on('input', function() {
    $('#presion_arterial_1').removeClass('borde-temperatura-hipertermia');
    $('#presion_arterial_1-aceptado').html('');
});

$("#presion_arterial_2").on('blur', function(event) {
    var value = $(this).val();
    if (Validar.esVacio(value) == false) {
        if (!Validar.enteroEnRango(value, 0, 140)) {
            $('#presion_arterial_2').addClass('borde-temperatura-hipertermia');
            $('#presion_arterial_2-aceptado').html("<p><strong>" + 'El valor debe estar entre 0 a 140' + "</strong></p>");
            $('#presion_arterial_2-aceptado strong').addClass('borde-temperatura-valida');
        }
    } else if (Validar.esVacio(value)) {
        $('#presion_arterial_2').addClass('borde-temperatura-hipertermia');
        $('#presion_arterial_2-aceptado').html("<p><strong>" + 'Ingrese el valor de la Presion arterial' + "</strong></p>");
        $('#presion_arterial_2-aceptado strong').addClass('borde-temperatura-valida');
    }
});
$("#presion_arterial_2").on('input', function(event) {
    var value = $('#presion_arterial_2').val();
    if (Validar.esVacio(value)) {
        $('#presion_arterial_2').addClass('borde-temperatura-hipertermia');
        $('#presion_arterial_2-aceptado').html("<p><strong>" + 'Ingrese el valor de la Presion arterial' + "</strong></p>");
        $('#presion_arterial_2-aceptado strong').addClass('borde-temperatura-valida');
    } else if (!Validar.enteroEnRango(value, 0, 140)) {
        $('#presion_arterial_2').addClass('borde-temperatura-hipertermia');
        $('#presion_arterial_2-aceptado').html("<p><strong>" + 'El valor debe estar entre 0 a 140' + "</strong></p>");
        $('#presion_arterial_2-aceptado strong').addClass('borde-temperatura-valida');
    }
});
$("#presion_arterial_2").on('input', function() {
    $('#presion_arterial_2').removeClass('borde-temperatura-hipertermia');
    $('#presion_arterial_2-aceptado').html('');
});
//----------------------------------FIN PRESION ARTERIAL-----------------------------------------------

//---------------------------------saturacion_oxigeno OXIGENO--------------------------------------------------
$("#saturacion_oxigeno").on('blur', function(event) {
    var value = $(this).val();
    //console.log('blur');  
    if (Validar.esVacio(value) == false) {
        if (!Validar.enteroEnRango(value, 60, 100)) {
            $('#saturacion_oxigeno').addClass('borde-temperatura-hipertermia');
            $('#saturacion_oxigeno-aceptado').html("<p><strong>" + 'El valor de la Saturación debe ser entre 60 a 100 ( % )' + "</strong></p>");
            $('#saturacion_oxigeno-aceptado strong').addClass('borde-temperatura-valida');

        } else if (value >= 60 && value <= 85) {
            //HIPOXIA GRAVE  - ROJO
            $('#saturacion_oxigeno').addClass('borde-saturacion_oxigeno-hipoxia-grave');
            $('#saturacion_oxigeno-aceptado').html("<strong>" + 'Paciente con Hipoxia Grave' + "</strong>");
        } else if (value >= 86 && value <= 90) {
            //HIPOXIA MODERADA - NARANJA
            $('#saturacion_oxigeno').addClass('borde-saturacion_oxigeno-hipoxia-moderada');
            $('#saturacion_oxigeno-aceptado').html("<strong>" + 'Paciente con Hipoxia Moderada' + "</strong>");
        } else if (value >= 91 && value <= 94) {
            // HIPOXIA LEVE - AMARILLO
            $('#saturacion_oxigeno').addClass('borde-saturacion_oxigeno-hipoxia-leve');
            $('#saturacion_oxigeno-aceptado').html("<strong>" + 'Paciente con Hipoxia Leve' + "</strong>");
        } else if (value >= 95 && value <= 100) {
            //NORMAL - VERDE
            $('#saturacion_oxigeno').addClass('borde-saturacion_oxigeno-normal');
            $('#saturacion_oxigeno-aceptado').html("<strong>" + 'Saturación Normal' + "</strong>");
        }
    } else if (Validar.esVacio(value)) {
        $('#saturacion_oxigeno').addClass('borde-temperatura-hipertermia');
        $('#saturacion_oxigeno-aceptado').html("<p><strong>" + 'Ingrese el valor de la Saturación ( % )' + "</strong></p>");
        $('#saturacion_oxigeno-aceptado strong').addClass('borde-temperatura-valida');
    }

});

$("#saturacion_oxigeno").on('input', function(event) {

    var value = $(this).val();

    if (Validar.esVacio(value)) {
        $('#saturacion_oxigeno').addClass('borde-temperatura-hipertermia');
        $('#saturacion_oxigeno-aceptado').html("<p><strong>" + 'Ingrese el valor de la Saturación ( % )' + "</strong></p>");
        $('#saturacion_oxigeno-aceptado strong').addClass('borde-temperatura-valida');

    } else if (!Validar.enteroEnRango(value, 60, 100)) {
        $('#saturacion_oxigeno').addClass('borde-temperatura-hipertermia');
        $('#saturacion_oxigeno-aceptado').html("<p><strong>" + 'El valor de la Saturación debe ser entre 60 a 100 ( % )' + "</strong></p>");
        $('#saturacion_oxigeno-aceptado strong').addClass('borde-temperatura-valida');

    } else if (value >= 60 && value <= 85) {
        //HIPOXIA GRAVE  - ROJO
        $('#saturacion_oxigeno').addClass('borde-saturacion_oxigeno-hipoxia-grave');
        $('#saturacion_oxigeno-aceptado').html("<strong>" + 'Paciente con Hipoxia Grave' + "</strong>");
    } else if (value >= 86 && value <= 90) {
        //HIPOXIA MODERADA - NARANJA
        $('#saturacion_oxigeno').addClass('borde-saturacion_oxigeno-hipoxia-moderada');
        $('#saturacion_oxigeno-aceptado').html("<strong>" + 'Paciente con Hipoxia Moderada' + "</strong>");
    } else if (value >= 91 && value <= 94) {
        // HIPOXIA LEVE - AMARILLO
        $('#saturacion_oxigeno').addClass('borde-saturacion_oxigeno-hipoxia-leve');
        $('#saturacion_oxigeno-aceptado').html("<strong>" + 'Paciente con Hipoxia Leve' + "</strong>");
    } else if (value >= 95 && value <= 100) {
        //NORMAL - VERDE
        $('#saturacion_oxigeno').addClass('borde-saturacion_oxigeno-normal');
        $('#saturacion_oxigeno-aceptado').html("<strong>" + 'Saturación Normal' + "</strong>");
    }
});

$("#saturacion_oxigeno").on('input', function() {
    $('#saturacion_oxigeno').removeClass('borde-saturacion_oxigeno-hipoxia-grave borde-saturacion_oxigeno-hipoxia-moderada borde-saturacion_oxigeno-hipoxia-leve borde-saturacion_oxigeno-normal borde-temperatura-hipertermia');
    $('#saturacion_oxigeno-aceptado').html('');
});
//---------------------------------FIN saturacion_oxigeno OXIGENO--------------------------------------------------

//----------------------------------------------PESO-----------------------------------------------------------
$("#peso").on('blur', function(event) {
    var value = $(this).val();
    if (Validar.esVacio(value) == false) {
        if (!Validar.decimalEnRango(value, 1.0, 180.0)) {
            $('#peso').addClass('borde-temperatura-hipertermia');
            $('#peso-aceptado').html("<p><strong>" + 'El valor del peso debe ser entre 1.0 a 180 ( Kg )' + "</strong></p>");
            $('#peso-aceptado strong').addClass('borde-temperatura-valida');

        }
    } else if (Validar.esVacio(value)) {
        $('#peso').addClass('borde-temperatura-hipertermia');
        $('#peso-aceptado').html("<p><strong>" + 'Ingrese el valor del Peso ( Kg )' + "</strong></p>");
        $('#peso-aceptado strong').addClass('borde-temperatura-valida');
    }
});

$("#peso").on('input', function(event) {
    var value = $(this).val();
    if (Validar.esVacio(value)) {
        $('#peso').addClass('borde-temperatura-hipertermia');
        $('#peso-aceptado').html("<p><strong>" + 'Ingrese el valor del Peso ( Kg )' + "</strong></p>");
        $('#peso-aceptado strong').addClass('borde-temperatura-valida');

    } else if (!Validar.decimalEnRango(value, 1.0, 180.0)) {
        $('#peso').addClass('borde-temperatura-hipertermia');
        $('#peso-aceptado').html("<p><strong>" + 'El valor del peso debe ser entre 1.0 a 180 ( Kg )' + "</strong></p>");
        $('#peso-aceptado strong').addClass('borde-temperatura-valida');

    }
});

$("#peso").on('input', function() {
    $('#peso').removeClass('borde-temperatura-hipertermia');
    $('#peso-aceptado').html('');
});

$("#peso").click(function() {
    $('#imc').removeClass('borde-imc-bajopeso borde-imc-normal borde-imc-sobrepeso borde-imc-obesidad1 borde-imc-obesidad2 borde-imc-obesidad3');
    $('#imc-aceptado').html('');
});
//------------------------------------------------------FIN PESO-------------------------------------------------

//---------------------------------------------------TALLA----------------------------------------------------

$("#talla").click(function() {
    $('#imc').removeClass('borde-imc-bajopeso borde-imc-normal borde-imc-sobrepeso borde-imc-obesidad1 borde-imc-obesidad2 borde-imc-obesidad3');
    $('#imc-aceptado').html('');
});

$("#talla").on('blur', function(event) {
    var value = $(this).val();
    if (Validar.esVacio(value) == false) {
        if (!Validar.enteroEnRango(value, 30, 200)) {
            $('#talla').addClass('borde-temperatura-hipertermia');
            $('#talla-aceptado').html("<p><strong>" + 'El valor de la talla debe ser entre 30 a 200 ( cm )' + "</strong></p>");
            $('#talla-aceptado strong').addClass('borde-temperatura-valida');
        } else {
            // compruebaIMC();
        }
    } else if (Validar.esVacio(value)) {
        $('#talla').addClass('borde-temperatura-hipertermia');
        $('#talla-aceptado').html("<p><strong>" + 'Ingrese el valor de la Talla ( cm )' + "</strong></p>");
        $('#talla-aceptado strong').addClass('borde-temperatura-valida');
    }
});

$("#talla").on('input', function(event) {
    var value = $(this).val();
    if (Validar.esVacio(value)) {
        $('#talla').addClass('borde-temperatura-hipertermia');
        $('#talla-aceptado').html("<p><strong>" + 'Ingrese el valor de la Talla ( cm )' + "</strong></p>");
        $('#talla-aceptado strong').addClass('borde-temperatura-valida');

    } else if (!Validar.enteroEnRango(value, 30, 200)) {
        $('#talla').addClass('borde-temperatura-hipertermia');
        $('#talla-aceptado').html("<p><strong>" + 'El valor de la talla debe ser entre 30 a 200 ( cm )' + "</strong></p>");
        $('#talla-aceptado strong').addClass('borde-temperatura-valida');
    } else {
        //compruebaIMC();
    }
});

$("#talla").on('input', function() {
    $('#talla').removeClass('borde-temperatura-hipertermia');
    $('#talla-aceptado').html('');
});
//-------------------------------------FIN TALLA-----------------------------------------

//------------------------------------FI02()%)--------------------------------------------

$("#fi02").on('blur', function(event) {
    var value = $('#fi02').val();
    if (Validar.esVacio(value) == false) {
        if (!Validar.enteroEnRango(value, 21, 100)) {
            $('#fi02').addClass('borde-temperatura-hipertermia');
            $('#fi02-aceptado').html("<p><strong>" + 'El valor de la fIO2 debe estar entre 21 a 100 ( % )' + "</strong></p>");
            $('#fi02-aceptado strong').addClass('borde-temperatura-valida');
        }
    } else if (Validar.esVacio(value)) {
        $('#fi02').addClass('borde-temperatura-hipertermia');
        $('#fi02-aceptado').html("<p><strong>" + 'Ingrese el valor de la fi02 ( % )' + "</strong></p>");
        $('#fi02-aceptado strong').addClass('borde-temperatura-valida');
    }
});

$("#fi02").on('input', function(event) {
    var value = $('#fi02').val();
    if (Validar.esVacio(value)) {
        $('#fi02').addClass('borde-temperatura-hipertermia');
        $('#fi02-aceptado').html("<p><strong>" + 'Ingrese el valor de la fi02 ( % )' + "</strong></p>");
        $('#fi02-aceptado strong').addClass('borde-temperatura-valida');
    } else if (!Validar.enteroEnRango(value, 21, 100)) {
        $('#fi02').addClass('borde-temperatura-hipertermia');
        $('#fi02-aceptado').html("<p><strong>" + 'El valor de la fIO2 debe estar entre 21 a 100 ( % )' + "</strong></p>");
        $('#fi02-aceptado strong').addClass('borde-temperatura-valida');
    }
});

$("#fi02").on('input', function() {
    $('#fi02').removeClass('borde-temperatura-hipertermia');
    $('#fi02-aceptado').html('');
});
//-----------------------------------FI02(%)-------------------------------------------

//FECHAS
$('#fecha_auditoria').appendDtpicker({
    "dateOnly": true,
    "autodateOnStart": false,
    "minuteInterval": 5,
    "amPmInTimeList": false,
    "locale": "es",
    "closeOnSelected": true,
    "dateFormat": "DD-MM-YYYY"
});
$('#fecha_nacimiento').appendDtpicker({
    "dateOnly": true,
    "autodateOnStart": false,
    "minuteInterval": 5,
    "amPmInTimeList": false,
    "locale": "es",
    "closeOnSelected": true,
    "dateFormat": "DD-MM-YYYY"
});
$('#fecha_atencion').appendDtpicker({
    "dateOnly": true,
    "autodateOnStart": false,
    "minuteInterval": 5,
    "amPmInTimeList": false,
    "locale": "es",
    "closeOnSelected": true,
    "dateFormat": "DD-MM-YYYY"
});
//FIN FECHAS

//--------------------------------------------------------------------
//--------------------------DIAGNOSTICOS-------------------------------
$('#addDiagnostico').on('click', function() {
    AddDiagnostico();
});

//CUANDO HAY DATOS RETORNO VALIDADOR
//DIAGNOSTICOS INGRESO
$('#tblDiagnostico tbody tr').each(function(index) {
    if ($('#datosretorno_diagnostico_ingreso' + index).val() == '1') {
        datosretornadosDiagnosticoAutocomplete(index, 1);
    }
});
//DIAGNOSTICOS - AUTOCOMPLETE
$('#tblDiagnostico2 tbody tr').each(function(index) {
    if ($('#datosretorno_diagnostico_externo' + index).val() == '1') {
        datosretornadosDiagnosticoAutocomplete(index, 2);
    }
});
//DIAGNOSTICOS ALTA - AUTOCOMPLETE
$('#tblDiagnosticoAlta tbody tr').each(function(index) {
    if ($('#datosretorno_diagnostico_alta' + index).val() == '1') {
        datosretornadosDiagnosticoAltaAutocomplete(index);
    }
});
//PROCEDIMIENTOS - AUTOCOMPLETE
$('#tblProcedimiento tbody tr').each(function(index) {
    if ($('#datosretorno_procedimiento' + index).val() == '1') {
        datosretornadosProcedimientoAutocomplete(index);
    }
});
//FIN CUANDO HAY DATOS RETORNO VALIDADOR

//COLOCANDO DIAGNOSTICOS AL PROCEDIMIENTO
$('#tblProcedimiento tbody tr').each(function(p_index) {
    if ($('#id_procedimiento_miselect' + p_index).val() == '1') {
        const diagnosticosInputs = $("input[name='nombre_diagnostico2[]']").map(function() {
            return $(this).val();
        }).get();

        diagnosticosInputs.forEach((nombre, d_index) => {
            console.log(nombre);
            const selectElement = document.getElementById('miSelect' + p_index);
            const valor = $('#id_diagnostico2' + d_index);
            var i = 0;
            let option = document.createElement('option');
            option.value = valor.val();
            option.textContent = nombre;
            if (i == 0) {
                option.setAttribute("selected", "selected");
                i++;
            }
            selectElement.appendChild(option);
            console.log(selectElement);
        });
    }
});
//FIN COLOCANDO DIAGNOSTICOS AL PROCEDIMIENTO

//FIN COLOCANDO DIAGNOSTICOS AL PRODUCTO MEDICO

$('#addDiagnosticoAlta').on('click', function() {
    AddDiagnosticoAlta();
});
$('#tblDiagnostico tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
});
$('#tblDiagnostico2 tbody').on('click', 'button.deleteFila2', function() {
    var obj = $(this);
    var inputVals = obj.parent().siblings().find('input[type="text"]').map(function() {
        return $(this).val();
    }).get();
    let targetVal = inputVals.find(function(val) {
        return val.includes("-");
    });
    if (targetVal) {
        let posicion = targetVal.indexOf("-");
        let codigo = targetVal.substring(0, posicion);
        eliminarDiagnosticosarr(codigo);
        console.log(codigo);
    }
    obj.parent().parent().remove();
});

function eliminarDiagnosticosarr(codigon) {
    let codigo2 = codigon.trim()

    let listIdDiagnosticoObjetos2 = [];
    for (let index = 0; index < listIdDiagnosticoObjetos.length; index++) {
        const element = listIdDiagnosticoObjetos[index];
        if (element.codigo !== codigo2) {
            listIdDiagnosticoObjetos2.push(element)
        }

    }
    listIdDiagnosticoObjetos = [];
    listIdDiagnosticoObjetos = listIdDiagnosticoObjetos.concat(listIdDiagnosticoObjetos2);



    procedimientosSeleccionados = procedimientosSeleccionados.map((item) => {
        if (item.codigo === codigo2) {
            return {...item, id: "" };
        } else {
            return item;
        }
    });
    updateSelectElements();

}
$('#tblDiagnosticoAlta tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
});
$('#tblProcedimiento tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
});

$('#addDiagnostico2').on('click', function() {
    AddDiagnostico2();
});

//------------------------------FIN DIAGNOSTICOS-----------------------
//---------------------------------------------------------------------

//------------PROCEDIMIENTOS-------------
$('#addProcedimiento').on('click', function() {
    AddProcedimiento();
});

$('#tblProcedimiento tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
});


$(".deleteFilaExamenPatologia").click(function() {
    // Obtener el índice de la fila
    var index = $(this).closest("tr").index();
    // Obtener el campo oculto correspondiente
    var id_examen_patologia = $("#id_examen_patologia" + index);
    var id_examen_patologia_anuladoInput = $("#id_examen_patologia_anulado" + index);
    var patologia = {
        codigo: codigo.val(),
        nombre: nombre.val(),
        cantidad: cantidad.val(),
        nombre_completo: nombre_completo.val(),
        observacion: observacion.val(),
        diagnostico: diagnostico.val()
    };

    // Verificar si la fila está marcada como eliminada
    if (id_examen_patologia_anuladoInput.val() == id_examen_patologia.val()) { //D->DELETE
        // Restablecer el valor del campo oculto
        id_examen_patologia_anuladoInput.val('');
        // Eliminar la clase 'eliminado' para quitar el estilo de tachado
        $(this).closest("tr").removeClass("eliminado");
    } else {
        // Establecer el valor del campo oculto en 1
        id_examen_patologia_anuladoInput.val(JSON.stringify(patologia));
        // Agregar la clase 'eliminado' para aplicar el estilo de tachado
        $(this).closest("tr").addClass("eliminado");
    }
});

$(".deleteFilaExamenImagenologia").click(function() {
    // Obtener el índice de la fila
    var index = $(this).closest("tr").index();
    // Obtener el campo oculto correspondiente

    var id_examen_imagenologia = $("#id_examen_imagenologia" + index);
    var id_examen_imagenologia2 = $("#id_examen_imagenologia2" + index);
    var id_examen_imagenologia_anuladoInput = $("#id_examen_imagenologia_anulado" + index);

    var codigo = $("#codigo" + index);
    var nombre = $("#nombre" + index);
    var cantidad = $("#cantidad" + index);
    var observacion = $("#observacion" + index);
    var diagnostico = $("#diagnostico" + index);
    var cod_diag = $("#cod_diag" + index);
    var comparativo_radio = $("#comparativo_radio" + index);
    var lado_radio = $("#lado_radio" + index);

    var imagenologia = {
        codigo: codigo.val(),
        nombre: nombre.val(),
        cantidad: cantidad.val(),
        observacion: observacion.val(),
        diagnostico: diagnostico.val(),
        cod_diag: cod_diag.val(),
        comparativo_radio: comparativo_radio.val(),
        lado_radio: lado_radio.val(),
        estado_eliminado: 1
    };
    // Verificar si la fila está marcada como eliminada
    if (id_examen_imagenologia.val() == id_examen_imagenologia2.val()) { //D->DELETE
        // Restablecer el valor del campo oculto
        id_examen_imagenologia_anuladoInput.val('');
        id_examen_imagenologia2.val('');
        // Eliminar la clase 'eliminado' para quitar el estilo de tachado
        $(this).closest("tr").removeClass("eliminado");
    } else {
        // Establecer el valor del campo oculto en 1
        //id_examen_imagenologia_anuladoInput.val(JSON.stringify(imagenologia));
        // Agregar la clase 'eliminado' para aplicar el estilo de tachado
        $(this).closest("tr").addClass("eliminado");
        id_examen_imagenologia_anuladoInput.val(JSON.stringify(imagenologia));
        id_examen_imagenologia2.val(id_examen_imagenologia.val());
    }
});
$(".deleteFilaDiagnosticoIngreso").click(function() {
    // Obtener el índice de la fila
    var index = $(this).closest("tr").index();
    // Obtener el campo oculto correspondiente
    var id_diagnostico_ingreso = $("#id_diagnostico_ingreso" + index);
    var id_diagnostico_ingreso_anuladoInput = $("#id_diagnostico_ingreso_anulado" + index);

    var codigo_diagnostico_ingreso = $("#codigo_diagnostico_ingreso" + index).val();
    var id_tipo_diag_diagnostico_ingreso = $("#id_tipo_diag_diagnostico_ingreso" + index).val();
    var tipo_diag_diagnostico_ingreso = $("#tipo_diag_diagnostico_ingreso" + index).val();
    var renipress_diagnostico_ingreso = $("#renipress_diagnostico_ingreso" + index).val();


    var diagnostico = {
        id: id_diagnostico_ingreso.val(),
        id_anulado: id_diagnostico_ingreso_anuladoInput.val(),
        id_cie10: codigo_diagnostico_ingreso,
        id_tipo_diag: id_tipo_diag_diagnostico_ingreso,
        tipo_diag: tipo_diag_diagnostico_ingreso,
        renipress: renipress_diagnostico_ingreso,
        estado_eliminado: 1
    };

    // Verificar si la fila está marcada como eliminada
    if (id_diagnostico_ingreso_anuladoInput.val() == id_diagnostico_ingreso.val()) { //D->DELETE
        // Restablecer el valor del campo oculto
        id_diagnostico_ingreso_anuladoInput.val('');
        // Eliminar la clase 'eliminado' para quitar el estilo de tachado
        $(this).closest("tr").removeClass("eliminado");
    } else {
        // Establecer el valor del campo oculto en 1
        id_diagnostico_ingreso_anuladoInput.val(JSON.stringify(diagnostico));
        // Agregar la clase 'eliminado' para aplicar el estilo de tachado
        $(this).closest("tr").addClass("eliminado");
    }
});

$(".deleteFilaDiagnosticoAlta").click(function() {
    // Obtener el índice de la fila
    var index = $(this).closest("tr").index();
    // Obtener el campo oculto correspondiente

    var id_diagnostico_alta = $("#id_diagnostico_alta" + index);
    var id_diagnostico_alta2 = $("#id_diagnostico_alta2" + index);

    var id_diagnostico_alta_anuladoInput = $("#id_diagnostico_alta_anulado" + index);

    var renipress_alta = $("#renipress_alta" + index);
    var numero_lote_fups_alta = $("#numero_lote_fups_alta" + index);
    var numero_fups_alta = $("#numero_fups_alta" + index);
    var id_cie10_alta = $("#id_cie10_alta" + index);
    var id_identificador_diag_alta = $("#id_identificador_diag_alta" + index);
    var id_tipo_diag_alta = $("#id_tipo_diag_alta" + index);

    var id_diagnostico_alta = $("#id_diagnostico_alta" + index);
    var tipo_diag_alta = $("#tipo_diag_alta" + index);
    var descripcion_alta = $("#descripcion_alta" + index);

    var diagnostico_alta = {
        renipress: renipress_alta.val(),
        numero_lote_fups: numero_lote_fups_alta.val(),
        numero_fups: numero_fups_alta.val(),
        id_cie10: id_cie10_alta.val(),
        id_identificador_diag: id_identificador_diag_alta.val(),
        id_tipo_diag: id_tipo_diag_alta.val(),

        id_diagnostico: id_diagnostico_alta.val(),
        tipo_diag: tipo_diag_alta.val(),
        descripcion: descripcion_alta.val(),
        estado_eliminado: 1
    };
    // Verificar si la fila está marcada como eliminada
    if (id_diagnostico_alta.val() == id_diagnostico_alta2.val()) { //D->DELETE
        // Restablecer el valor del campo oculto
        id_diagnostico_alta_anuladoInput.val('');
        id_diagnostico_alta2.val('');
        // Eliminar la clase 'eliminado' para quitar el estilo de tachado
        $(this).closest("tr").removeClass("eliminado");
    } else {
        // Establecer el valor del campo oculto en 1
        // Agregar la clase 'eliminado' para aplicar el estilo de tachado
        $(this).closest("tr").addClass("eliminado");
        id_diagnostico_alta_anuladoInput.val(JSON.stringify(diagnostico_alta));
        id_diagnostico_alta2.val(id_diagnostico_alta.val());
    }
});

$(".deleteFilaDiagnosticoExterno").click(function() {
    // Obtener el índice de la fila
    var index = $(this).closest("tr").index();
    // Obtener el campo oculto correspondiente

    var id_diagnosticos_externo = $("#id_diagnosticos_externo" + index);
    var id_diagnosticos_externo2 = $("#id_diagnosticos_externo2" + index);
    var id_diagnosticos_externo_anuladoInput = $("#id_diagnosticos_externo_anulado" + index);

    var renipress = $("#renipress" + index);
    var numero_lote_fups = $("#numero_lote_fups" + index);
    var numero_fups = $("#numero_fups" + index);
    var id_cie10 = $("#id_cie10" + index);
    var id_identificador_diag = $("#id_identificador_diag" + index);
    var id_tipo_diag = $("#id_tipo_diag" + index);

    var id_diagnostico = $("#id_diagnostico" + index);

    var tipo_diag = $("#tipo_diag" + index);
    var descripcion = $("#descripcion" + index);

    var diagnosticos_externo = {
        renipress: renipress.val(),
        numero_lote_fups: numero_lote_fups.val(),
        numero_fups: numero_fups.val(),
        id_cie10: id_cie10.val(),
        id_identificador_diag: id_identificador_diag.val(),
        id_tipo_diag: id_tipo_diag.val(),
        id_diagnostico: id_diagnostico.val(),
        tipo_diag: tipo_diag.val(),
        descripcion: descripcion.val(),
        estado_eliminado: 1
    };

    // Verificar si la fila está marcada como eliminada
    if (id_diagnosticos_externo.val() == id_diagnosticos_externo2.val()) { //D->DELETE
        // Restablecer el valor del campo oculto
        id_diagnosticos_externo_anuladoInput.val('');
        id_diagnosticos_externo2.val('');
        // Eliminar la clase 'eliminado' para quitar el estilo de tachado
        $(this).closest("tr").removeClass("eliminado");
    } else {
        // Establecer el valor del campo oculto en 1
        id_diagnosticos_externo_anuladoInput.val(JSON.stringify(diagnosticos_externo));
        id_diagnosticos_externo2.val(id_diagnosticos_externo.val());
        // Agregar la clase 'eliminado' para aplicar el estilo de tachado
        $(this).closest("tr").addClass("eliminado");
    }
});

$(".deleteFilaProcedimientoExterno").click(function() {
    // Obtener el índice de la fila
    var index = $(this).closest("tr").index();
    // Obtener el campo oculto correspondiente

    var id_procedimiento_externo = $("#id_procedimiento_externo" + index);
    var id_procedimiento_externo2 = $("#id_procedimiento_externo2" + index);

    var id_procedimiento_externo_anuladoInput = $("#id_procedimiento_externo_anulado" + index);

    var renipress = $("#renipress" + index);
    var numero_lote_fups = $("#numero_lote_fups" + index);
    var numero_fups = $("#numero_fups" + index);
    var id_cie10 = $("#id_cie10" + index);
    var id_proced = $("#id_proced" + index);
    var cantidad_indicada = $("#cantidad_indicada" + index);
    var cantidad_ejecutada = $("#cantidad_ejecutada" + index);
    var precio = $("#precio" + index);
    var valorizacion = $("#valorizacion" + index);
    var correlativo = $("#correlativo" + index);
    var fecha_procedimiento = $("#fecha_procedimiento" + index);
    var resultado_procedimiento = $("#resultado_procedimiento" + index);
    var id_diagnostico = $("#id_diagnostico" + index);
    var nombre_diagnostico = $("#nombre_diagnostico" + index);
    var id_procedimiento = $("#id_procedimiento" + index);
    var codigo_procedimiento = $("#codigo_procedimiento" + index);
    var nombre_procedimiento = $("#nombre_procedimiento" + index);

    var procedimientos = {
        renipress: renipress.val(),
        numero_lote_fups: numero_lote_fups.val(),
        numero_fups: numero_fups.val(),
        id_cie10: id_cie10.val(),
        id_proced: id_proced.val(),
        cantidad_indicada: cantidad_indicada.val(),
        cantidad_ejecutada: cantidad_ejecutada.val(),
        precio: precio.val(),
        valorizacion: valorizacion.val(),
        correlativo: correlativo.val(),
        fecha_procedimiento: fecha_procedimiento.val(),
        resultado_procedimiento: resultado_procedimiento.val(),
        id_diagnostico: id_diagnostico.val(),
        nombre_diagnostico: nombre_diagnostico.val(),
        id_procedimiento: id_procedimiento.val(),
        codigo_procedimiento: codigo_procedimiento.val(),
        nombre_procedimiento: nombre_procedimiento.val(),
        estado_eliminado: 1
    };

    // Verificar si la fila está marcada como eliminada
    if (id_procedimiento_externo.val() == id_procedimiento_externo2.val()) { //D->DELETE
        // Restablecer el valor del campo oculto
        id_procedimiento_externo_anuladoInput.val('');
        id_procedimiento_externo2.val('');
        // Eliminar la clase 'eliminado' para quitar el estilo de tachado
        $(this).closest("tr").removeClass("eliminado");
    } else {
        // Establecer el valor del campo oculto en 1
        id_procedimiento_externo_anuladoInput.val(JSON.stringify(procedimientos));
        id_procedimiento_externo2.val(id_procedimiento_externo.val());
        // Agregar la clase 'eliminado' para aplicar el estilo de tachado
        $(this).closest("tr").addClass("eliminado");
    }
});

$(".deleteFilaProductoAnulado").click(function() {
    // Obtener el índice de la fila
    var index = $(this).closest("tr").index();
    // Obtener el campo oculto correspondiente
    var id_producto = $("#id_producto1" + index);
    var id_producto2 = $("#id_producto2" + index);
    var id_producto_anuladoInput = $("#id_producto_anulado" + index);

    var renipress = $("#renipress" + index);
    var numero_lote_fups = $("#numero_lote_fups" + index);
    var numero_fups = $("#numero_fups" + index);
    var id_cie10 = $("#id_cie10" + index);
    var id_prod_med = $("#id_prod_med" + index);
    var cantidad_prescrita = $("#cantidad_prescrita" + index);
    var precio = $("#precio" + index);
    var cantidad_entregada = $("#cantidad_entregada" + index);
    var valorizacion = $("#valorizacion" + index);
    var correlativo = $("#correlativo" + index);

    var producto = {
        renipress: renipress.val(),
        numero_lote_fups: numero_lote_fups.val(),
        numero_fups: numero_fups.val(),
        id_cie10: id_cie10.val(),
        id_prod_med: id_prod_med.val(),
        cantidad_prescrita: cantidad_prescrita.val(),
        precio: precio.val(),
        cantidad_entregada: cantidad_entregada.val(),
        valorizacion: valorizacion.val(),
        correlativo: correlativo.val(),
    };

    // Verificar si la fila está marcada como eliminada
    if (id_producto.val() == id_producto2.val()) { //D->DELETE
        // Restablecer el valor del campo oculto
        id_producto_anuladoInput.val('');
        id_producto2.val('');
        // Eliminar la clase 'eliminado' para quitar el estilo de tachado
        $(this).closest("tr").removeClass("eliminado");
    } else {
        // Establecer el valor del campo oculto en 1
        id_producto_anuladoInput.val(JSON.stringify(producto));
        id_producto2.val(id_producto.val());
        // Agregar la clase 'eliminado' para aplicar el estilo de tachado
        $(this).closest("tr").addClass("eliminado");
    }
});



$('#btnPrestacion').on("click", function(e) {
    guardarPrestacion();
});

function guardarPrestacion() {
    var msg = "";
    //DIAGNOSTICOS INGRESO
    // var ind_diag = $('#tblDiagnostico tbody tr').length;
    // if (ind_diag > 0) {
    //     fila_dignosticos = $('#tblDiagnostico tbody').children('tr');
    //     for (i = 0; i < ind_diag; i++) {
    //         fila_dignostico = fila_dignosticos[i];
    //         var id_diagnostico = $(fila_dignostico).find('.id_diagnostico').val();
    //         var id_tipo_diagnostico = $(fila_dignostico).find('.id_tipo_diagnostico').val();
    //         var id_clasificacion_diagnostico = $(fila_dignostico).find('.id_clasificacion_diagnostico').val();

    //         if (id_diagnostico == "") msg += "Debe ingresar el nombre de diagnostico<br>";
    //         if (id_tipo_diagnostico == "0") msg += "Debe ingresar el tipo de diagnostico<br>";
    //         if (id_clasificacion_diagnostico == "0") msg += "Debe ingresar la clasificación del diagnostico<br>";
    //     }
    // } else {
    //     msg += "Debe ingresar minimo un diagnostico<br>";
    //     msg += "Debe ingresar el tipo de diagnostico<br>";
    // }
    //FIN DIAGNOSTICO INGRESO

    //DIAGNOSTICOS 
    var ind_diag2 = $('#tblDiagnostico2 tbody tr').length;
    if (ind_diag2 > 0) {
        fila_dignosticos2 = $('#tblDiagnostico2 tbody').children('tr');
        for (i = 0; i < ind_diag2; i++) {
            fila_dignostico2 = fila_dignosticos2[i];
            var id_diagnostico2 = $(fila_dignostico2).find('.id_diagnostico2').val();
            var id_tipo_diagnostico2 = $(fila_dignostico2).find('.id_tipo_diagnostico2').val();
            var id_clasificacion_diagnostico2 = $(fila_dignostico2).find('.id_clasificacion_diagnostico2').val();

            if (id_diagnostico2 == "") msg += "Debe ingresar el nombre de diagnostico<br>";
            if (id_tipo_diagnostico2 == "0") msg += "Debe ingresar el tipo de diagnostico<br>";
            if (id_clasificacion_diagnostico2 == "N") msg += "Debe ingresar la clasificación del diagnostico<br>";
        }
    }
    //FIN DIAGNOSTICOS
    //DIAGNOSTICOS DE ALTA

    var ind_diag_alta = $('#tblDiagnosticoAlta tbody tr').length;
    if (ind_diag_alta > 0) {
        fila_dignosticos_alta = $('#tblDiagnosticoAlta tbody').children('tr');
        for (i = 0; i < ind_diag_alta; i++) {

            var_datos = $('#existe_datos_diagnostico_alta' + i).val();
            if (var_datos == '1') {
                fila_dignosticos_alta = fila_dignosticos_alta[i];
                var id_diagnostico_alta = $(fila_dignosticos_alta).find('.id_diagnostico_alta').val();
                // var id_tipo_diagnostico2 = $(fila_dignosticos_alta).find('.id_tipo_diagnostico2').val();
                var id_clasificacion_alta = $(fila_dignosticos_alta).find('.id_clasificacion_alta').val();

                if (id_diagnostico_alta == "") msg += "Debe ingresar el nombre de diagnostico de alta<br>";
                // if (id_tipo_diagnostico2 == "0") msg += "Debe ingresar el tipo de diagnostico<br>";
                if (id_clasificacion_alta == "N") msg += "Debe ingresar la clasificación del diagnostico de alta<br>";
            }
        }
    }
    //FIN DIAGNOSTICOS DE ALTA

    //PROCEDIMIENTOS
    var ind_procedimiento = $('#tblProcedimiento tbody tr').length;
    if (ind_procedimiento > 0) {
        fila_procedimiento = $('#tblProcedimiento tbody').children('tr');
        for (i = 0; i < ind_procedimiento; i++) {
            var_datos = $('#existe_datos_procedimiento' + i).val();
            if (var_datos == '1') {

                fila_procedimiento = fila_procedimiento[i];
                var id_procedimiento = $(fila_procedimiento).find('.id_procedimiento').val();
                var procedimiento_cantidad = $(fila_procedimiento).find('.procedimiento_cantidad').val();
                var select_diagnostico = $('#miSelect' + i).val();

                if (id_procedimiento == "") msg += "Debe ingresar el nombre de procedimiento<br>";
                if (procedimiento_cantidad == "0" || procedimiento_cantidad == "") msg += "Debe ingresar la cantidad del procedimiento<br>";
                if (select_diagnostico == "" || select_diagnostico == null) msg += "Debe seleccionar un diagnostico<br>";
            }
        }
    }
    //FIN PROCEDIMIENTOS


    //PRODUCTOS MEDICOS
    var ind_productos = $('#example tbody tr').length;

    if (ind_productos > 0) {
        var nind = ind_productos - 1;
        var cmbMedicamento = $("#cmbMedicamento" + nind).val();
        fila_productosmedicos = $('#example tbody').children('tr');
        for (i = 0; i < ind_productos; i++) {

            fila_medicamento = fila_productosmedicos[i];
            var cmbMedicamento = $(fila_medicamento).find('.cmbMedicamento').val();

            var tiempo_periodo = $(fila_medicamento).find('.tiempo_periodo').val();
            var posologia = $(fila_medicamento).find('.posologia').val();
            var valmedentre = $(fila_medicamento).find('.nro_med_solictados').val();
            var valmedicamento_dispensados = $(fila_medicamento).find('.nro_med_dispensados').val();
            var via_administracion = $(fila_medicamento).find('.id_via_administracion').val();
            var select_diagnostico_producto = $(fila_medicamento).find('.select-diagnostico').val();

            //var select_diagnostico_producto = $('#miSelectProd' + i).val();

            if (cmbMedicamento == "ru='undefined'" || cmbMedicamento == "undefined") msg += "Falta seleccionar un producto (dispositivo médico)<br>";
            if (tiempo_periodo == "" || tiempo_periodo == 0) msg += "Uno de los productos no cuenta el tiempo(dias), tiene que ser mayor a 0<br>";
            if (via_administracion == 0 || via_administracion == "undefined") msg += "Uno de los productos no cuenta con la via de administración<br>";
            if (posologia == "") msg += "Uno de los productos no cuenta con la posologia<br>";
            if (valmedentre == "" || valmedentre == 0) msg += "Uno de los productos no cuenta con la cantidad prescrita, tiene que ser mayor a 0<br>";
            if (valmedicamento_dispensados == "" || valmedicamento_dispensados == 0) msg += "Uno de los productos no cuenta con la cantidad dispensada, tiene que ser mayor a 0<br>";
            if (select_diagnostico_producto == "" || select_diagnostico_producto == null) msg += "Falta seleccionar un diagnostico x producto<br>";



        }
    }
    //FIN PRODUCTOS MEDICOS

    if (msg != "") {
        bootbox.alert(msg);
        return false;
    }

    Swal.fire({
        title: '<div class="titulo-padding"><p>Procesando el envio...</p></div>',
        allowOutsideClick: false,
        showConfirmButton: false,
        customClass: {
            header: 'swal2-header-generar'
        },
        onBeforeOpen: () => {
            $('#blur-container').append('<div class="blur-background"></div>');
            Swal.showLoading();
            $("#frmAuditoriaEditar").submit();
        }
    });
}

////////////FUNCIONES////////////////
function soloNumerosMenosCero(e) {
    var key = window.Event ? e.which : e.keyCode;
    return ((key >= 48 && key <= 57) || key == 0 || key == 8)
}

function verificarEspacios(event) {
    var campo = event.target;

    // Verificar si la tecla presionada es la barra espaciadora (código de tecla 32)
    if (event.keyCode === 32 && campo.selectionStart === 0) {
        event.preventDefault(); // Evitar que se escriba el espacio
    }
}
$(document).on('change', '[id^="cmb_ingreso_egreso"]', function () {
    const ind = $(this).attr('id').replace('cmb_ingreso_egreso', ''); 
    const cmbTipoDiagnostico = $(`#cmbTipoDiagnostico2${ind}`); 
    const selectedValue = $(this).val(); // Obtén el valor seleccionado (I o E)
    
    if (cmbTipoDiagnostico.length > 0) {
        let item_clasificacion = ''; 
        if (selectedValue === 'E') {
            // Opciones para el caso "EGRESO"
            item_clasificacion += '<option value="N">--Seleccionar--</option>';
            item_clasificacion += '<option value="0">SECUNDARIO</option>';
            item_clasificacion += '<option value="2">DIAGNOSTICO DE CABECERA</option>';
            cmbTipoDiagnostico.val('2').change(); // Selecciona "DEFINITIVO"
        } else {
            // Opciones para el caso "INGRESO"
            item_clasificacion += '<option value="N">--Seleccionar--</option>';
            item_clasificacion += '<option value="0">ATENCIÓN</option>';
            item_clasificacion += '<option value="1">EVALUACIÓN</option>';
            cmbTipoDiagnostico.val('0').change(); 
        }
        // Verifica si el combo de clasificación ya existe
        const existingClasificacion = $(`#cmbClasificacion2${ind}`);
        if (existingClasificacion.length > 0) {
            // Si existe, actualiza las opciones
            existingClasificacion.html(item_clasificacion);
        } else {
            // Si no existe, crea el combo de clasificación y lo inserta después del combo tipo diagnóstico
            const cmbClasificacion = `
                <select class="form-control id_clasificacion_diagnostico2" 
                        id="cmbClasificacion2${ind}" 
                        name="id_clasificacion2[]" 
                        style="margin:0 20px;width:150px">
                    ${item_clasificacion}
                </select>
            `;
            cmbTipoDiagnostico.after(cmbClasificacion); 
        }
    } else {
        console.error(`No se encontró el combo cmbTipoDiagnostico con índice ${ind}`);
    }
});