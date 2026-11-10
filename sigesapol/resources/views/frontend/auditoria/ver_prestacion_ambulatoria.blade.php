
@extends('frontend.layouts.master')
@section('renderjs')
<?php use Carbon\Carbon; ?>
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
  .modal-dialog {
    width: 90%;
  }

  .title-dialog {
    font-size: 18px;
    font-family: Arial, Helvetica, sans-serif;
  }

  .ui-autocomplete {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 160px;
    padding: 5px 7px;
    margin: 2px 0 0;
    list-style: none;
    font-size: 15px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: left;
    background-color: #ffffff;
    border: 1px solid #cccccc;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    background-clip: padding-box;
  }

  .diagnosticos-button-container {
    display: flex;
  }

  .diagnosticos-button-container button {
    margin-right: 10px;
  }
  .ui-autocomplete>li>div {
    display: block;
    padding: 3px 20px;
    clear: both;
    font-weight: normal;
    line-height: 1.42857143;
    color: #333333;
    white-space: nowrap;
  }

  .table-row {
    cursor: pointer;
    transition: background-color 0.3s  !important;
  }

  .table-row:hover {
    background-color: lightgreen !important;
  }
  .ui-state-hover,
  .ui-state-active,
  .ui-state-focus {
    text-decoration: none;
    color: #FFFFFF !important;
    background: #1d71b8 !important;
    cursor: pointer;
  }

  .ui-helper-hidden-accessible {
    border: 0;
    clip: rect(0 0 0 0);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute;
    width: 1px;
  }

  input[readonly] {
    background-color: #eeeeee;
    text-align: center
  }


  span:focus,
  textarea:focus,
  input[type="text"]:focus,
  input[type="password"]:focus,
  input[type="datetime"]:focus,
  input[type="datetime-local"]:focus,
  input[type="date"]:focus,
  input[type="month"]:focus,
  input[type="time"]:focus,
  input[type="week"]:focus,
  input[type="number"]:focus,
  input[type="email"]:focus,
  input[type="url"]:focus,
  input[type="search"]:focus,
  input[type="tel"]:focus,
  input[type="color"]:focus,
  .uneditable-input:focus {
    border-color: #2196F3;
    box-shadow: 0 1px 1px #2196F3 inset, 0 0 8px #2196F3;
    outline: 0 none;
  }

  /*.fc-event-container{margin:20px!important}*/
  .fc-day-grid-event {
    /*width:150px;*/
    margin: 10px;
    text-align: center;
    margin-top: 40px;
    /*background:#5cb85c*/
    background: transparent;
    color: #009966
  }

  .fc-day-grid-event :hover {
    color: #009966
  }

  .fc-content {
    padding: 5px !important;
  }

  .fc-title {
    text-align: center;
    font-size: 12px
  }

</style>

<script>
  //<![CDATA[
    var obtenerDiagnosticoGeneroUrl = "{{ route('obtener_diagnostico_genero_ver', ['buscar' => '_buscar_', 'genero' => '_genero_']) }}";
    var id_prestacion = "{{ $id_prestacion }}";
    var listIdDiagnosticoObjetos =[];
    var codigos = [];
    var codigosprocedimientos = [];

    var procedimientosSeleccionados =[];
    var listIdDiagnostico =[];

    var filasmedicamentos = 0;


  $(document).ready(function() {
    listIdDiagnosticoObjetos = {!! json_encode($diagnostico) !!};
    console.log(listIdDiagnosticoObjetos);
 

    $('#tblDiagnostico tbody tr').each(function() {
        let codigo = $(this).find('td:first-child input.input-diagnostico').val();
        codigos.push(codigo);
    });

    $('#tblProcedimiento tbody tr').each(function() {
        let codigop = $(this).find('td:first-child input.input-procedimiento').val();
        codigosprocedimientos.push(codigop);
    });

    $('.select-2').select2();
  });

var documentName_ = null;

$(document).ready(function() {

  $('#fecha_atencion').datepicker();
  $('input[name="parent"]').on("change", function(e) {
    getServicio();
  });

  $('#example tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  $('#tblDiagnostico tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  $('#tblProcedimiento tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  $('#tblExamenLab tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  
  $('#tblExamenRad tbody').on('click', 'button.deleteFila', function() {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  $('#tblInterconsulta tbody').on('click', 'button.deleteFila', function () {
    var obj = $(this);
    obj.parent().parent().remove();
  });

  $("#id_servicio_derivar").select2({
    width: 'resolve'
  });

  $("#id_farmacia_receta").select2({
    width: 'resolve'
  });
  $("#id_farmacia_receta").val($("#id_farmacia").val()).trigger("change");

});

function obtenerBeneficiario() {

  var id_tiporeceta = 1;
  var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
  var dni_beneficiario = $("#dni_beneficiario").val();
  var msg = "";

  if (msg != "") {
    bootbox.alert(msg);
    return false;
  }

  $.ajax({
    url: '../obtener_beneficiario/' + id_tiporeceta + '/' + tipodoc_beneficiario + '/' + dni_beneficiario,
    dataType: "json",
    success: function(result) {
      if (result.sw == false) {
        bootbox.alert(result.msg);
        $('#beneficiario').val("");
        $('#tipo_beneficiario').val("");
        $('#nombre_beneficiario').val("");
        $('#paterno_beneficiario').val("");
        $('#materno_beneficiario').val("");
        $('#tipodoc_beneficiario').val("");
        $('#cip_beneficiario').val("");
        $('#grado').val("");
        return false;
      }

      var beneficiario = result.beneficiario.apepatafiliado + " " + result.beneficiario.apematafiliado + " " +
        result.beneficiario.apecasafiliado + ", " + result.beneficiario.nomafiliado;
      $('#nrodocafiliado').val(result.beneficiario.nrodocafiliado);
      $('#beneficiario').val(beneficiario);
      $('#tipo_beneficiario').val(result.beneficiario.parentesco);
      $('#nombre_beneficiario').val(result.beneficiario.nomafiliado);
      $('#paterno_beneficiario').val(result.beneficiario.apepatafiliado);
      $('#materno_beneficiario').val(result.beneficiario.apematafiliado + " " + result.beneficiario
        .apecasafiliado);
      $('#tipodoc_beneficiario').val(result.beneficiario.nomtipdocafiliado);
      $('#cip_beneficiario').val(result.beneficiario.cip);
      $('#grado').val(result.beneficiario.grado);

      var parentesco = result.beneficiario.parentesco;
      var ubigeo = result.beneficiario.ubigeo;

      obtenerEstablecimiento(parentesco, ubigeo);
    }

  });

}

function obtenerEstablecimiento(parentesco, ubigeo) {
  var newOption = "";
  newOption = "<option value=''>--Seleccionar--</option>";
  $('#id_establecimiento').html(newOption);

  $.ajax({
    url: '../obtener_establecimiento/' + parentesco + "/" + ubigeo,
    success: function(result) {
      var newOption = "";
      newOption = "<option value=''>--Seleccionar--</option>";
      $(result).each(function(ii, oo) {
        newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
      });
      $('#id_establecimiento').html(newOption);
      $("#id_establecimiento").select2({
        max_selected_options: 4
      });

    }
  });
}

function limpiarBeneficiario() {
  var dni_beneficiario = $('#dni_beneficiario').val();
  var nrodocafiliado = $('#nrodocafiliado').val();
  if (dni_beneficiario != nrodocafiliado) {
    $('#beneficiario').val("");
    $('#tipo_beneficiario').val("");
    $('#nombre_beneficiario').val("");
    $('#paterno_beneficiario').val("");
    $('#materno_beneficiario').val("");
    $('#grado').val("");
    $('#nro_historia').val("");
    $('#tipodoc_beneficiario').val("");
    $('#cip_beneficiario').val("");
    $('#id_admision').val("");
    $('#grado').val("");
  }
  obtenerBeneficiario();
}

function getServicio() {
  var newOption = "";
  newOption = "<option value=''>--Seleccionar--</option>";
  $('#id_servicio').html(newOption);
  //$("#id_servicio").val('').trigger("change");
  var parent = $('input[name="parent"]:checked').val();
  //var parent	= $('#parent').val();

  $.ajax({
    url: '../obtener_consultorio_parent/' + parent,
    success: function(result) {
      var newOption = "";
      newOption = "<option value=''>--Seleccionar--</option>";
      $(result).each(function(ii, oo) {
        newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
      });
      $('#id_servicio').html(newOption);
      $("#id_servicio").select2({
        max_selected_options: 4
      });

    }
  });
}

function getProducto() {
  $('#example tbody').html('');
  if ($('#id_servicio').val() == "") return false;
  //var id_ipress 		= $('#id_ipress').val();
  var id_ipress = $('#id_establecimiento').val();
  var id_consultorio = $('#id_servicio').val();
  //var tipo_receta		= $('#tipo_receta').val();
  //var id_tiporeceta 	= $('#id_tiporeceta').val();
  var id_tiporeceta = $('#id_tipo_atencion').val();

  var id_farmacia = $('#id_farmacia_receta').val();
  var nivel = $('#id_nivel').val();
  //var x  = $('#nro_interconsultas').val();
  var x = 0;
  var x = nro_interconsulta();
  var dni = $('#dni_beneficiario').val();
  var interconsulta = "NO";
  //$('#interconsulta').val();	
  if (id_tiporeceta == "3") {
    if (nivel == 3)
      var id_farmacia = 20;

    if (interconsulta == 'SI') {
      if (id_consultorio == 104) {
        id_consultorio2 = 104
      } else {
        id_consultorio2 = 105
      }
      $.ajax({
        //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + id_consultorio2+ '/' + dni,
        url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia + '/' + id_consultorio + '/' +
          id_consultorio2,
        success: function(result) {
          var newOption = "";
          newOption = "<option value=''>--Seleccionar--</option>";
          $(result).each(function(ii, oo) {
            newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
              .nombre + ", " + oo.abreviatura + "</option>";
          });
          $('#cmbMedicamentoTemp').html(newOption);
        }
      });

    } else {

      if (x == 0) { //SIN INTERCONSULTA
        if (id_consultorio == 104) {
          $.ajax({
            url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia + '/' +
              104,
            //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
            success: function(result) {
              var newOption = "";
              newOption = "<option value=''>--Seleccionar--</option>";
              $(result).each(function(ii, oo) {
                newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
                  .nombre + ", " + oo.abreviatura + "</option>";
              });
              $('#cmbMedicamentoTemp').html(newOption);
            }
          });
        } else {
          if (id_consultorio == 21) {
            $.ajax({
              url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia +
                '/' + 21,
              //url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress+ '/' + 104+ '/' + dni,
              success: function(result) {
                var newOption = "";
                newOption = "<option value=''>--Seleccionar-</option>";
                $(result).each(function(ii, oo) {
                  newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
                    .nombre + ", " + oo.abreviatura + "</option>";
                });
                $('#cmbMedicamentoTemp').html(newOption);
                //$("#id_medico").select2({max_selected_options: 4});

              }
            });
          } else {
            if (id_consultorio == 72) {
              $.ajax({
                //url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
                url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia +
                  '/' + 72,
                success: function(result) {
                  var newOption = "";
                  newOption = "<option value=''>--Seleccionar-</option>";
                  $(result).each(function(ii, oo) {
                    newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
                      .nombre + ", " + oo.abreviatura + "</option>";
                  });
                  $('#cmbMedicamentoTemp').html(newOption);
                }
              });
            } else {
              $.ajax({
                url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia + '/' +
                  id_consultorio + '/' + 105,
                //url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + 21,
                success: function(result) {
                  var newOption = "";
                  newOption = "<option value=''>--Seleccionar-</option>";
                  $(result).each(function(ii, oo) {
                    newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
                      .nombre + ", " + oo.abreviatura + "</option>";
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
            url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress + '/' + 104 + '/' +
              dni,
            success: function(result) {
              var newOption = "";
              newOption = "<option value=''>--Seleccionar--</option>";
              $(result).each(function(ii, oo) {
                newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
                  .nombre + ", " + oo.abreviatura + "</option>";
              });
              $('#cmbMedicamentoTemp').html(newOption);
            }
          });
        } else {
          if (id_consultorio == 21) {
            $.ajax({
              url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress + '/' + 21 + '/' +
                dni,
              success: function(result) {
                var newOption = "";
                newOption = "<option value=''>--Seleccionar-</option>";
                $(result).each(function(ii, oo) {
                  newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
                    .nombre + ", " + oo.abreviatura + "</option>";
                });
                $('#cmbMedicamentoTemp').html(newOption);
              }
            });
          } else {
            if (id_consultorio == 72) {
              $.ajax({
                //url: '/obtener_producto_por_hospitalizacion_mas_idconsultorio/' + id_farmacia+ '/' + id_consultorio+ '/' +105,
                url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia +
                  '/' + 72,
                success: function(result) {
                  var newOption = "";
                  newOption = "<option value=''>--Seleccionar-</option>";
                  $(result).each(function(ii, oo) {
                    newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
                      .nombre + ", " + oo.abreviatura + "</option>";
                  });
                  $('#cmbMedicamentoTemp').html(newOption);
                }
              });
            } else {
              $.ajax({
                url: '/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/' + id_ipress + '/' + 105 +
                  '/' + dni,
                success: function(result) {
                  var newOption = "";
                  newOption = "<option value=''>--Seleccionar-</option>";
                  $(result).each(function(ii, oo) {
                    newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
                      .nombre + ", " + oo.abreviatura + "</option>";
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

    
    if (id_tiporeceta != 6 && id_tiporeceta != 5 && id_tiporeceta != 4 && id_farmacia != 18) {
      if(id_tiporeceta==2){
        id_consultorio=76;
      }
      $.ajax({
        url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia + '/' +
          id_consultorio,
        success: function(result) {
          var newOption = "";
          newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function(ii, oo) {
            newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
              .nombre + ", " + oo.abreviatura + "</option>";
          });
          $('#cmbMedicamentoTemp').html(newOption);

        }
      });

    }

    if (id_tiporeceta == 5) {

      $.ajax({
        url: '/obtener_producto_por_idipress_idconsultorio_idfarmacia/' + id_ipress + '/' + id_farmacia + '/110',
        success: function(result) {
          var newOption = "";
          newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function(ii, oo) {
            newOption += "<option value='" + oo.id + "' ru='" + oo.ru + "' >" + oo.codigo + " - " + oo
              .nombre + ", " + oo.abreviatura + "</option>";
          });
          $('#cmbMedicamentoTemp').html(newOption);

        }
      });

    }

  }

}

function nro_interconsulta() {
  var dni_beneficiario = $('#dni_beneficiario').val();
  var id_farmacia = $('#id_farmacia').val();
  var x = 0;
  if (dni_beneficiario != "") {
    $.ajax({
      url: '/obtenerCantInterconsultas/' + dni_beneficiario + '/' + id_farmacia,
      success: function(result) {
        if (result != null && result != "") {
          for (var key in result) {
            x++;
          }
        }
      }

    });

    return x;
  }
}

</script>

@endsection
@section('content')
<div class="container" style="width:90%">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="row">
        <div class="panel panel-primary filterable">
          <div class="panel-heading">
            <h3 class="panel-title">MÓDULO DE GESTIÓN DE ATENCIONES MEDICAS</h3>
          </div>
          <div class="panel-body">

              <div class="panel panel-info panel-bordered panel-sm panel-body">
                <fieldset>

                  <legend>
                    <b>DATOS DE LA ATENCI&Oacute;N</b>
                  </legend>
                  <div>
                    <div class="form-group">
                      <div class="col-md-4">
                        <label for="tipo">TIPO DE ATENCI&Oacute;N</label>
                        <p><?php echo $prestacion->tipo_atencion?></p>
                      </div>
                      <div class="col-md-3">
                        <label for="fecha_atencion">FECHA DE ATENCION</label>
                        <p><?php echo $prestacion->fecha_atencion?></p>
                      </div>
                      <div class="col-md-2">
                        <label>N ° HISTORIA CLINICA</label>
                        <p><?php echo $prestacion->nro_historia?></p>
                      </div>
                      <div class="col-md-3">
                        <label for="modalidad_beneficiario">MODALIDAD</label><br/>
                        <?php 
                          if($prestacion->id_modalidad==1){
                            echo "PRESENCIAL";
                          }
                          else
                          {
                            echo "VIRTUAL";
                          }
                        ?>
                      </div>
                    </div>
                    <?php 
                      switch ($prestacion->id_tipo_atencion) {
                        case '1': //AMBULATORIO
                          $descripcion='SERVICIO'; $descripcion2='CONSULTORIO';break;
                        
                        case '2': //EMERGENCIA
                          $descripcion='PRIORIDAD'; $descripcion2='TOPICO';break;

                        case '3': //HOSPITALIZACION
                          $descripcion='SALA'; $descripcion2='SERVICIO';break;
                      }
                    ?>
                    <div class="form-group">
                      <div class="col-md-4">
                        <label for="tipo">NOMBRE DE LA IPRESS</label>
                        <p><?php echo $prestacion->establecimiento?></p>
                      </div>
                      <div class="col-md-3">
                        <label for="nombre_ipres"><?php echo $descripcion ?></label>
                        <p><?php echo $prestacion->servicio?></p>
                      </div>
                      <div class="col-md-2">
                        <label>CODIGO UPSS</label>
                        <p><?php echo $prestacion->codigo_upss?></p>
                      </div>
                      
                      @if($prestacion->id_tipo_atencion==1)
                        @if($prestacion->id_modalidad==1)
                          <div class="col-md-2">
                            <label><?php echo $descripcion2 ?></label>
                            <p><?php echo $prestacion->consultorio?></p>
                          </div>
                        @endif
                      @endif
                    </div>
                    @if($prestacion->id_tipo_atencion==3)
                    <div class="form-group">
                      <div class="col-md-3">
                        <label for="tipo">PABELLON</label>
                        <p><?php //echo $prestacion->pabellon?></p>
                      </div>
                      <div class="col-md-3">
                        <label for="dni_beneficiario">PISO</label>
                        <p><?php //echo $prestacion->piso?></p>
                      </div>
                      <div class="col-md-3">
                        <label>HABITACION</label>
                        <p><?php //echo $prestacion->cuarto?></p>
                      </div>
                      <div class="col-md-3">
                        <label>CAMA</label>
                        <p><?php //echo $prestacion->cama?></p>
                      </div>
                    </div>
                    @endif
                  </div>
                </fieldset>
              </div>

              <fieldset>

                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>INFORMACIÓN DEL PACIENTE</b>
                    </div>
                  </div>
                  <div class="panel-body" id="collapseprestacionespaciente">


                    <div class="form-group">

                      <div class="col-md-3">
                        <label for="tipo">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                        <p><?php echo $prestacion->tipo_doc_ident?></p>
                      </div>

                      <div class="col-md-3">
                        <label for="dni_beneficiario">N ° DE DOCUMENTO DE IDENTIDAD</label>
                        <p><?php echo $prestacion->nro_doc_ident?></p>
                      </div>

                      <div class="col-md-3">
                        <label for="dni_beneficiario">SEXO</label>
                        <p><?php echo $prestacion->sexo?></p>
                      </div>
                      <div class="col-md-3">
                        <label>PARENTESCO &nbsp;</label>
                        <p><?php echo $prestacion->parentesco?></p>
                      </div>
                    </div>



                    <div class="form-group">
                      <div class="col-md-3">
                        <label>APELLIDO PATERNO</label>
                        <p><?php echo $prestacion->asegurado_paterno?></p>
                      </div>

                      <div class="col-md-3">
                        <label>APELLIDO MATERNO</label>
                        <p><?php echo $prestacion->asegurado_materno?></p>
                      </div>

                      <div class="col-md-3">
                        <label>NOMBRES</label>
                        <p><?php echo $prestacion->asegurado_nombre?></p>
                      </div>
                      <div class="col-md-3">
                        <label>FECHA DE NACIMIENTO</label>
                        <p><?php echo $prestacion->fecha_nac?></p>
                      </div>
                    </div>


                    <div class="form-group">
                      
                      <div class="col-md-3">
                        <label>GRADO &nbsp;</label>
                        <p><?php echo $prestacion->grado?></p>
                      </div>
                      <div class="col-md-3">
                        <label>SITUACION &nbsp;</label>
                        <p><?php echo $prestacion->situacion_paciente?></p>
                      </div>
                      <div class="col-md-3">
                        <label>TELEFONO &nbsp;</label>
                        <p><?php echo $prestacion->telefono?> <?php echo $prestacion->telefono2?></p>
                      </div>

                      <div class="col-md-3">
                        <label>DOMICILIO &nbsp;</label>
                        <p><?php echo $prestacion->domicilio?></p>
                      </div>
                    </div>

                  </div>
                </div>

              </fieldset>

              <?php if(isset($cita->id_referencia)):?>     
                    <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                            <div class="panel-title text-center">
                            <b>DATOS DE LA REFERENCIA</b>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="displa" id="tblDiagnostico111" cellspacing="0" width="100%" style="margin-top:5px;">
                            <thead>
                                <tr>
                                <th width="10%"></th>
                                <th width="30%">Establecimiento Origen</th>
                                <th width="30%">Servicio Origen</th>
                                <th width="30%">Motivo de Referencia</th>
                                </tr>
                            </thead>
                            <tfoot>
                            </tfoot>
                            <tbody>
                                <tr>
                                <td></td>
                                <td><?php echo $referencias->nombre_establecimiento?></td>
                                <td><?php echo $referencias->nombre_servicio?></td>
                                <td><?php echo $referencias->observacion_referencia?></td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                        </div>
                    </fieldset>        
                    <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                            <div class="panel-title text-center">
                            <b>DIAGNOSTICOS DE REFERENCIA</b>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="displa" id="tblDiagnostico11" cellspacing="0" width="100%" style="margin-top:5px;">
                            <thead>
                                <tr>
                                <th width="20%">Codigo</th>
                                <th width="60%">Nombre</th>
                                <th width="20%">Tipo</th>
                                </tr>
                            </thead>
                            <tfoot>
                            </tfoot>
                            <tbody>
                                <?php foreach($diagnostico_prestacion_referencia as $row):
                                $tipo_diagnostico = "";
                                if($row->id_tipo_diagnostico==1)$tipo_diagnostico = "PRESUNTIVO";
                                if($row->id_tipo_diagnostico==2)$tipo_diagnostico = "DEFINITIVO";
                                if($row->id_tipo_diagnostico==3)$tipo_diagnostico = "REITERATIVO";
                                ?>
                                <tr>
                                <td><?php echo $row->codigo?></td>
                                <td><?php echo $row->nombre?></td>
                                <td><?php echo $tipo_diagnostico?></td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                            </table>
                        </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                            <div class="panel-title text-center">
                            <b>PROCEDIMIENTOS DE REFERENCIA</b>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="display" id="tblProcedimiento1" cellspacing="0" width="100%" style="margin-top:5px;">
                            <thead>
                                <tr>
                                <th width="20%">Codigo</th>
                                <th width="60%">Nombre</th>
                                <th width="20%">Observacion</th>
                                </tr>
                            </thead>
                            <tfoot>
                            </tfoot>
                            <tbody>
                                <?php foreach($procedimientos_refencia as $row):?>
                                <tr>
                                <td><?php echo $row->codigo?></td>
                                <td><?php echo $row->descripcion?></td>
                                <td><?php echo $row->observacion?></td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                            </table>
                        </div>
                        </div>
                    </fieldset>
                <?php endif; ?> 
                <fieldset>
                    <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                        <div class="panel-title text-center">
                        <b>INFORMACIÓN DEL RESPONSABLE DE ATENCION</b>
                        </div>
                    </div>
                    <div class="panel-body">

                        <div class="form-group">

                        <div class="col-md-4">
                            <label for="tipo">TIPO DE DOCUMENTO DE IDENTIDAD</label>
                            <p>DNI</p>

                        </div>

                        <div class="col-md-4">
                            <label for="dni_beneficiario">N ° DE DOCUMENTO DE IDENTIDAD</label>
                            <p><?php echo $prestacion->dni?></p>
                        </div>

                        </div>

                        <div class="form-group">
                        <div class="col-md-4">
                            <label>APELLIDO PATERNO</label>
                            <p><?php echo $prestacion->medico_paterno?></p>
                        </div>

                        <div class="col-md-4">
                            <label>APELLIDO MATERNO</label>
                            <p><?php echo $prestacion->medico_materno?></p>
                        </div>

                        <div class="col-md-4">
                            <label>NOMBRES</label>
                            <p><?php echo $prestacion->medico_nombre?></p>
                        </div>
                        </div>

                        <div class="form-group">
                        <div class="col-md-2">
                            <label>N ° COLEGIATURA</label>
                            <p><?php echo $prestacion->colegiatura?></p>
                        </div>

                        <div class="col-md-2">
                            <label>N ° RNE</label>
                            <p><?php echo $prestacion->rne?></p>
                        </div>

                        <div class="col-md-4">
                            <label>PROFESION</label>
                            <p><?php echo $prestacion->profesion?></p>
                        </div>

                        <div class="col-md-4">
                            <label>ESPECIALIDAD</label>
                            <p><?php echo $prestacion->especializacion?></p>
                        </div>

                        </div>

                    </div>
                    </div>
                </fieldset>
                      <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center" style="position: relative">
                              <b>DIAGNÓSTICOS</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            <table class="display" id="tblDiagnostico" cellspacing="0" width="100%" style="margin-top:5px;">
                              <thead>
                                <tr>
                                  <th width="20%" style="text-align: center;">Codigo</th>
                                  <th width="60%" style="text-align: center;">Nombre</th>
                                  <th width="20%" style="text-align: center;">Tipo</th>
                                </tr>
                              </thead>
                              <tfoot>
                              </tfoot>
                              <tbody>
                                <?php foreach($diagnostico as $row):
                                  $tipo_diagnostico = "";
                                  if($row->id_tipo_diagnostico==1)$tipo_diagnostico = "PRESUNTIVO";
                                  if($row->id_tipo_diagnostico==2)$tipo_diagnostico = "DEFINITIVO";
                                  if($row->id_tipo_diagnostico==3)$tipo_diagnostico = "REITERATIVO";
                                ?>
                                     <tr class="row-diagnosticosfinal">
                                       <td width="20%">
                                    <input type="text" name="id_diagnisticos['{{ $row->id_diagnostico }}]'" id="" value="{{ $row->codigo }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly>
                                    </td>
                                   <td width="60%">
                                 
                                  <input type="text" name="id_diagnisticos['{{ $row->id_diagnostico }}]'" id="" value="{{ $row->nombre }}" class=" input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly>
                                 </td>
                                   <td width="20%">
                               <input name="id_tipo_diagnosticos['{{ $row->id_diagnostico }}']" id="" value="{{ $tipo_diagnostico }}" class="input-diagnostico form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly>
                              </td>
                              </tr>
                                <?php endforeach;?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </fieldset>
                      <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                               <div class="panel-title text-center" style="position: relative">
                              <b>PROCEDIMIENTOS</b>
                            </div>
                          </div>
                          <div class="panel-body">
                              <table class="display" id="tblProcedimiento" cellspacing="0" width="100%" style="margin-top:5px;">
                                     <thead>
                                          <tr>
                                     <th width="10%" style="text-align: center;">codigo</th>
                                    <th width="40%" style="text-align: center;">Nombre</th>
                                  <th width="10%" style="text-align: center;">Cantidad</th>
                                  @if($id_prestacion>1910698)
                                    <th width="40%" style="text-align: center;">diagnostico</th>
                                  @endif
                                           </tr>
                                        </thead>
                                            <tbody>
                                      @foreach($procedimiento as $row)
                                   <input type="hidden" value="{{ $row->cod }}" name="prestacion_procedimiento_id[]" style="width : 1px; heigth : 1px">
                                       <tr class="row2-{{ $row->cod }}">
                                        <td style="text-align: center;"><input type="text" name="codigodeprod[{{ $row->cod }}]"  class="form-control input-procedimiento" disabled value="{{ $row->codigo }}"  style="border: none; outline: none; background-color: transparent;" readonly></td>
                                             <td>
                                            {{$row->nombre}}
                                            <input name="id_procedimientos[{{ $row->cod }}]" id="idProcedimientos" class=" input-procedimiento form-control" value="{{$row->nombre}}" type="hidden" disabled style="border: none; outline: none; background-color: transparent;" readonly>
                                    </td>
                                      <td style="text-align: center;"><input type="text" name="cantidad_procedimientos[{{ $row->cod }}]"  class="form-control input-procedimiento" disabled value="{{ $row->cantidad }}"  style="border: none; outline: none; background-color: transparent;" readonly></td>
                                       @if($id_prestacion>1910698)
                                       <td style="text-align: center;">
                                     <input name="iddiagnosticosxProcedimiento[{{ $row->id_diagnostico }}]" id="iddiagnosticosxProcedimiento" class="input-procedimiento form-control" disabled style="border: none; outline: none; background-color: transparent;" readonly
                                  value="<?php
                                    $model_diagnosticos = new \App\Models\Producto_receta;
                                     $diagnostico_id = $row->id_diagnostico;
                                    $diagnostico_nombre = $model_diagnosticos->get_diagnostico_nombrexProcedimiento($diagnostico_id);
                                         echo $diagnostico_nombre;
                                                    ?>">
                                         </td>
                                         @endif
                                        </tr>
                                      @endforeach
                                         </tbody>
                                                <!--tfoot>
                                                 <tr>
                                                    <td>
                                                    <button type="button" style="margin-top: 6px;display:none;" class="btn btn-warning" id="btnSaveProcedimiento" disabled>Guardar</button>
                                </td>
                              </tr>
                              </tfoot-->
                              </table>
                          </div>
                        </div>
                      </fieldset>
                      <fieldset>
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>REFERENCIA</b>
                            </div>
                          </div>
                        <?php if ($prestaciones->flag_ref_cref == '1'): ?>
                          <div class="panel-body">
                            <div class="panel panel-info panel-bordered panel-sm panel-body">
                              <div class="form-group">
                                <div class="col-sm-1" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                  <label>REFERIR CONSULTA</label>
                                </div>
                                <div class="col-sm-1" style="margin-left:0px;padding-left:0px">
                                   <button type="button" id="addreferenciacontra" class="btn btn-lg btn-toggle active"
                                    data-toggle="button" aria-pressed="false" autocomplete="off" disabled>
                                    <div class="handle"></div>
                                  </button>
                                </div>
                                <div id="fsReferenciacontra" >
                                  <div class="col-md-2"
                                    style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                    <label>SERVICIO DE REFERENCIA</label>
                                  </div>
                                  <div class="col-md-3" style="margin-left:0px;padding-left:0px">
                                    <select class="form-control input-sm" id="servicio_referencia" name="servicio_referencia" disabled>
                                      <option value="" selected=""> <?php echo $dato_servicio_ref ?></option>
                                    </select>
                                  </div>
                                  <div class="col-md-2"
                                    style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                    <label>COMENTARIO DE LA REFERENCIA</label>
                                  </div>
                                  <div class="col-md-3" style="margin-left:0px;padding-left:0px">
                                      <textarea class="form-control" cols="5" id="texto_referencia" name="texto_referencia"
                                          placeholder="Antecedente Familiar" disabled> <?php echo $dato_texto_obs ?></textarea>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                         <?php endif; ?>
                        </div>
                      </fieldset>
                              <?php if($prestaciones->flag_ref_cref == '2'): ?>
                                <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                    <div class="panel-heading">
                                      <div class="panel-title text-center">
                                        <b>CONTRAREFERENCIA</b>
                                      </div>
                                    </div>
                                    <?php if ($prestaciones->flag_ref_cref == '2'): ?>
                                    <div class="panel-body">
                                        <div class="panel panel-info panel-bordered panel-sm panel-body">
                                            <div class="form-group">
                                              <div class="col-sm-1" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                <label>CONTRAREFERIR CONSULTA</label>
                                              </div>
                                              <div class="col-sm-1" style="margin-left:0px;padding-left:0px">
                                                 <button type="button" id="addreferenciacontra" class="btn btn-lg btn-toggle active"
                                                  data-toggle="button" aria-pressed="false" autocomplete="off" disabled>
                                                  <div class="handle"></div>
                                                </button>
                                              </div>
                                              <div id="fsReferenciacontra" >
                                                <div class="col-md-2"
                                                  style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                                  <label>SERVICIO DE CONTRAREFERENCIA</label>
                                                </div>
                                                <div class="col-md-3" style="margin-left:0px;padding-left:0px">
                                                  <select class="form-control input-sm" id="servicio_referencia" name="servicio_referencia" disabled>
                                                    <option value="" selected=""> <?php echo $dato_servicio_ref ?></option>
                                                  </select>
                                                </div>
                                                <div class="col-md-2"
                                                  style="padding-top:5px;margin-left:0px;margin-right:0px;padding-right:0px">
                                                  <label>COMENTARIO DE LA REFERENCIA</label>
                                                </div>
                                                <div class="col-md-3" style="margin-left:0px;padding-left:0px">
                                                    <textarea class="form-control" cols="5" id="texto_referencia" name="texto_referencia"
                                                        placeholder="Antecedente Familiar" disabled> <?php echo $dato_texto_obs ?></textarea>
                                                </div>
                                              </div>

                                            </div>
                                          </div>
                                     </div>
                                     <?php else: ?>
                                      <div class="panel-body">
                                        <div class="panel panel-info panel-bordered panel-sm panel-body">
                                            <div class="form-group">
                                              <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                                                <label>CONTRAREFERIR CONSULTA</label>
                                              </div>
                                              <div class="col-sm-2" style="margin-left:0px;padding-left:0px">
                                                <button type="button" id="addreferenciacontra" class="btn btn-lg btn-toggle"
                                                  data-toggle="button" aria-pressed="false" autocomplete="off" disabled>
                                                  <div class="handle"></div>
                                                </button>
                                              </div>
                                            </div>
                                          </div>
                                     </div>
                                     <?php endif; ?>
                                  </div>
                              </fieldset>
                              <?php else: ?>
                              <?php endif; ?>
                      <fieldset id="fsAntecedente">
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                                <b>ANTECEDENTES</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            <div class="col-md-12">
                              <label>ANTECEDENTE</label>
                              <p><?php echo $prestacion->antecedente?></p>
                            </div>
                          </div>
                          <div class="panel-body">
                            <div class="col-md-12">
                              <label>ANTECEDENTE FAMILIAR</label>
                                <p><?php echo $prestacion->antecedente_familiar?></p>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                      <fieldset id="fsAnamnesis">
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                                <b>ANAMNESIS</b>
                            </div>
                          </div>
                        <div class="panel-body">
                          <div class="col-md-12">
                            <label>MOTIVO DE VISITA</label>
                            <p><?php echo $prestacion->motivo_visita?></p>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="col-md-12">
                            <label>TIEMPO DE ENFERMEDAD</label>
                              <p><?php echo $prestacion->tiempo_enfermedad?></p>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="col-md-12">
                            <label>RELATO CRONOLOGICO</label>
                            <p><?php echo $prestacion->anamnesis?></p>
                          </div>
                        </div>
                      </fieldset>
                      <fieldset id="fsAspectoGeneral">
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>EXAMEN FÍSICO</b>
                            </div>
                          </div>
                      @if(Auth::user()->id_establecimiento==75)
                        @if($prestacion->id_consultorio == 28 || $prestacion->id_consultorio == 106 || $prestacion->id_consultorio == 107)
                          <div class="panel-body">
                            <div class="panel panel-info panel-bordered panel-sm">
                              <div class="panel-heading">
                                <div class="panel-title text-center">
                                  <b>ASPECTOS GENERALES</b>
                                </div>
                              </div>
                              <div class="panel-body">
                                <p>
                                  <?php 
                                    $aux_aspectos_generales = $prestacion->aspectos_generales;
                                    if (!empty($aux_aspectos_generales)) {
                                      echo str_replace(chr(13), '<br>', $prestacion->aspectos_generales);
                                    }
                                  ?>
                                </p>
                              </div>
                            </div>
                          </div>
                        @endif
                      @endif
                        @if($prestacion->temperatura>0)
                          <div class="panel-body">
                            <div class="panel panel-info panel-bordered panel-sm">
                              <div class="panel-heading">
                                <div class="panel-title text-center">
                                  <b>INFORMACIÓN DE SIGNOS VITALES</b>
                                </div>
                              </div>
                              <div class="panel-body">
                                <div class="form-group">
                                  <div class="col-md-2">
                                    <label>TEMPERATURA ( °C) &nbsp;</label>
                                    <p><?php echo $prestacion->temperatura?></p>
                                  </div>
                                  <div class="col-md-2">
                                    <label>TALLA (cm) &nbsp;</label>
                                    <p><?php echo $prestacion->talla_cm?></p>
                                  </div>
                                  <div class="col-md-2">
                                    <label>PESO (Kg) &nbsp;</label>
                                    <p><?php echo $prestacion->peso_kg?></p>
                                  </div>
                                  <div class="col-md-2">
                                    <label>IMC &nbsp;</label>
                                    <p>
                                    <?php
                                      if($prestacion->talla_cm!=0):
                                       echo ($prestacion->peso_kg / (($prestacion->talla_cm * $prestacion->talla_cm)*(0.01*0.01)));
                                      endif;
                                    ?>
                                    </p>
                                  </div>
                                  <div class="col-md-2">
                                    <label>PESO IDEAL &nbsp;</label>
                                    <p><?php echo 45+(($prestacion->peso_kg-150)*3/4)?></p>
                                  </div>
                                  <div class="col-md-2">
                                    <label>Sa02 (%) &nbsp;</label>
                                    <p><?php echo $prestacion->sa02_porcentaje?></p>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-md-2">
                                    <label>TAMAÃ‘O DE CINTURA (cm)</label>
                                    <p><?php echo $prestacion->tamano_cintura_cm?></p>
                                  </div>
                                  <div class="col-md-2" id="frecuencia_fetal" style="display:none">
                                    <label>FREC. CARDIACA FETAL(/min)</label>
                                    <p><?php echo $prestacion->frecuencia_cardiaca_fetal?></p>
                                  </div>
                                  <div class="col-md-2">
                                    <label>PRESIÓN ARTERIAL &nbsp;</label>
                                    <div class="row">
                                      <div class="col-xs-5">                          
                                        <p><?php echo $prestacion->presion_arterial_x?></p>
                                      </div>  
                                        <div class="col-xs-2">
                                           <label>/</label> 
                                        </div>
                                      <div class="col-xs-5">
                                        <p><?php echo $prestacion->presion_arterial_y?></p>
                                      </div>  
                                    </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-md-2">
                                    <label>FREC.RESPIRATORIA(/min)</label>
                                    <p><?php echo $prestacion->frecuencia_respiratoria?></p>
                                  </div>
                                  <div class="col-md-2">
                                    <label>FREC. CARDIACA (/min) &nbsp;</label>
                                    <p><?php echo $prestacion->frecuencia_cardiaca?></p>
                                  </div>
                                  <!--div class="col-md-2">
                                    <label>FREC. CARDIACA (/min) &nbsp;</label>
                                    <p><?php //echo $prestacion->frecuencia_cardiaca?></p>
                                  </div-->
                                  <div class="col-md-2">
                                    <label>Fi02 (%) &nbsp;</label>
                                    <p><?php echo $prestacion->fi02?></p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        @endif
                        @if(($cita->id_establecimiento==76 &&  $cita->id_servicio == 13))
                          @if($datos_oftalmo!=null)
                            <div class="panel-body">
                              <label>DATOS DE OFTALMOLOGIA</label>
                              <div class="panel panel-info panel-bordered panel-sm" style="padding-bottom: 0px">
                                <div class="form-group">
                                  <div class="panel-body">
                                    <div class="col-md-12">
                                      <label for=""><b style="font-size: 16px;">1. AV</b></label>
                                    </div>
                                    <div class="col-md-12">
                                      <table class="table table-striped table-list-search">
                                        <thead>
                                          <tr>
                                            <th style="text-align: center;"> </th>
                                            <th style="text-align: center;">OD</th>
                                            <th style="text-align: center;">OI</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td style="width: 30%;">
                                              <label style="font-size: 12px; padding-right: 15px;">AV SC</label>
                                            </td>
                                            <td>

                                              <input type="text" class="form-control" disabled
                                                value="@if($datos_oftalmo!=null) {{$datos_oftalmo->od_av_sc}} @endif">
                                            </td>
                                            <td>
                                              <input type="text" class="form-control" disabled
                                                value="@if($datos_oftalmo!=null) {{$datos_oftalmo->oi_av_sc}} @endif">
                                            </td>
                                          </tr>
                                          <tr>
                                            <td style="width: 30%;">
                                              <label style="font-size: 12px;padding-right: 15px;">AV CC</label>
                                            </td>
                                            <td>
                                              <input type="text" class="form-control" disabled
                                                value="@if($datos_oftalmo!=null) {{$datos_oftalmo->od_av_cc}} @endif">
                                            </td>
                                            <td>
                                              <input type="text" class="form-control" disabled
                                                value="@if($datos_oftalmo!=null) {{$datos_oftalmo->oi_av_cc}} @endif">
                                            </td>
                                          </tr>
                                          <tr>
                                            <td style="width: 30%;">
                                              <label style="font-size: 12px;padding-right: 15px;">AV CAE</label>
                                            </td>
                                            <td>
                                              <input type="text" class="form-control" disabled
                                                value="@if($datos_oftalmo!=null) {{$datos_oftalmo->od_av_cae}} @endif">
                                            </td>
                                            <td>
                                              <input type="text" class="form-control" disabled
                                                value="@if($datos_oftalmo!=null) {{$datos_oftalmo->oi_av_cae}} @endif">
                                            </td>
                                          </tr>
                                          <tr>
                                            <td style="width: 30%;">
                                              <label style="font-size: 12px;padding-right: 15px;">PIO</label>
                                            </td>
                                            <td>
                                              <input type="text" class="form-control" disabled
                                                value="@if($datos_oftalmo!=null) {{$datos_oftalmo->od_pio}} @endif">
                                            </td>
                                            <td>
                                              <input type="text" class="form-control" disabled
                                                value="@if($datos_oftalmo!=null) {{$datos_oftalmo->oi_pio}} @endif">
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="form-group">
                                  <div class="panel-body">
                                    <div class="col-md-12">
                                      <label for=""><b style="font-size: 16px;">2. REFRACCION</b></label>
                                    </div>
                                    <div class="col-md-1">
                                      <br><label style="font-size: 14px;">OD LEJOS</label>
                                    </div>
                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">SPH</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_lejos_sph}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">CIL</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_lejos_cil}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">EJE</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_lejos_eje}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">DIP</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_lejos_dip}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">AV</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_lejos_av}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <br><label style="font-size: 14px; margin-bottom: 0px !important;">OI LEJOS</label>
                                    </div>
                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">SPH</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_lejos_sph}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">CIL</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_lejos_cil}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">EJE</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_lejos_eje}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">DIP</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_lejos_dip}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">AV</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_lejos_av}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <br><label style="font-size: 14px; margin-bottom: 0px !important;">OD CERCA</label>
                                    </div>
                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">SPH</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_cerca_sph}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">CIL</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_cerca_cil}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">EJE</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_cerca_eje}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">DIP</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_cerca_dip}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-1">
                                      <br><label style="font-size: 14px; margin-bottom: 0px !important;">OI CERCA</label>
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">SPH</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_cerca_sph}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">CIL</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_cerca_cil}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">EJE</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_cerca_eje}}@endif">
                                    </div>

                                    <div class="col-md-1">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">DIP</label><br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_cerca_dip}}@endif">
                                    </div>

                                    <div class="col-md-1"><br>
                                      @if($datos_oftalmo!=null)
                                      <a href="/imprimir_oftalmo/{{$datos_oftalmo->prestacion_id}}" aria-hidden="true"
                                        title="PDF" class="btn btn-danger btn-xs" target="_blank">
                                        <i class="fa fa-file-pdf-o"></i>
                                      </a>
                                      @endif
                                    </div>
                                    <div class="col-md-3" style="padding-top: 10px">
                                      <table>
                                        <tr>
                                          <td>
                                            <label
                                              style="font-size: 12px; margin-bottom: 0px !important; padding-right: 15px;">ADD</label>
                                          </td>
                                          <td>
                                            <input type="text" class="form-control" disabled
                                              value="@if($datos_oftalmo!=null){{$datos_oftalmo->refraccion_add}}@endif">
                                          </td>
                                        </tr>
                                      </table>
                                    </div>
                                  </div>
                                </div>
                              </div>



                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="form-group">
                                  <div class="panel-body">
                                    <div class="col-md-12">
                                      <label for=""><b style="font-size: 16px;">3. MOTILIDAD OCULAR</b></label>
                                    </div>
                                    <div class="col-md-1" style="padding-right:0px;">
                                      <br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_motilidad_1}}@endif">
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_motilidad_2}}@endif">
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_motilidad_3}}@endif">
                                    </div>
                                    <div class="col-md-1" style="padding-right:0px;">
                                      <br><img src="../../images/oftalmologia/motalidad.png" width="105" height="auto">
                                    </div>
                                    <div class="col-md-1" style="padding-right:0px;">
                                      <br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_motilidad_4}}@endif">
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_motilidad_5}}@endif">
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->od_motilidad_6}}@endif">
                                    </div>
                                    <div class="col-md-1" style="padding-right:0px;">
                                      <br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_motilidad_1}}@endif">
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_motilidad_2}}@endif">
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_motilidad_3}}@endif">
                                    </div>
                                    <div class="col-md-1" style="padding-right:0px;">
                                      <br>
                                      <img src="../../images/oftalmologia/motalidad2.png" width="105" height="auto">
                                    </div>
                                    <div class="col-md-1" style="padding-right:0px;">
                                      <br>
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_motilidad_4}}@endif">
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_motilidad_5}}@endif">
                                      <input type="text" class="form-control" disabled
                                        value="@if($datos_oftalmo!=null){{$datos_oftalmo->oi_motilidad_6}}@endif">
                                    </div>
                                    <div class="col-md-6">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">TEST HIRSCHBERG-COVER
                                        TEST-OTROS</label><br>
                                      <textarea class="form-control" cols="30" rows="4"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->motilidad_test}}@endif</textarea>
                                    </div>
                                  </div>
                                </div>
                              </div>




                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="form-group">
                                  <div class="panel-body">
                                    <div class="col-md-12">
                                      <label for=""><b style="font-size: 16px;">4. BIOMICROSCOPIA</b></label>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD PARPADO</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_parpado}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD CONJUNTIVA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_conjuntiva}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD CORNEA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_cornea}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD CA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_ca}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD PUPILA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_pupila}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD CRISTALINO</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_cristalino}}@endif</textarea>
                                    </div>
                                    <div class="col-md-12">
                                      <hr>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI PARPADO</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_parpado}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI CONJUNTIVA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_conjuntiva}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI CORNEA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_cornea}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI CA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_ca}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI PUPILA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_pupila}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI CRISTALINO</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_cristalino}}@endif</textarea>
                                    </div>
                                  </div>
                                </div>
                              </div>


                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="form-group">
                                  <div class="panel-body">
                                    <div class="col-md-12">
                                      <label for=""><b style="font-size: 16px;">5. FONDO DE OJO</b></label>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD CRISTALINO</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_cristalino_fondo}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD DISCO
                                        OPTICO</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_disco_optico}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD DISCO
                                        MACULA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_disco_macula}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD RETINA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_retina}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OD VASOS
                                        RETINIANOS</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->od_vasos_retinianos}}@endif</textarea>
                                    </div>
                                    <div class="col-md-12">
                                      <hr>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI CRISTALINO</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_cristalino_fondo}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI DISCO
                                        OPTICO</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_disco_optico}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI DISCO
                                        MACULA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_disco_macula}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI RETINA</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_retina}}@endif</textarea>
                                    </div>
                                    <div class="col-md-4">
                                      <label style="font-size: 12px; margin-bottom: 0px !important;">OI VASOS
                                        RETINIANOS</label><br>
                                      <textarea class="form-control" cols="30" rows="2"
                                        disabled>@if($datos_oftalmo!=null){{$datos_oftalmo->oi_vasos_retinianos}}@endif</textarea>
                                    </div>
                                  </div>
                                </div>
                              </div>




                              <div class="panel panel-info panel-bordered panel-sm"
                                style="padding-left: 14px;padding-right: 14px; padding-bottom: 0px important;">
                                <div class="form-group">
                                  <div class="panel-body">
                                    <div class="col-md-12">
                                      <label for=""><b style="font-size: 16px;">6. IMAGENES AUXILIARES</b></label>
                                    </div>
                                    <div class="col-md-3 panel panel-info panel-bordered panel-sm">
                                      <br><label style="font-size: 14px;">BIOMETRIA</label>
                                      <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" disabled
                                          value="@if($datos_oftalmo!=null){{$datos_oftalmo->imag_biometria}}@endif">
                                        <span class="input-group-btn">
                                          @if($datos_oftalmo!=null)
                                          @if($datos_oftalmo->imag_biometria == null)
                                          <a class="btn btn-success btn-flat" disabled>DESCARGAR</a>
                                          @else
                                          <a href="{{ asset('uploads_oftalmo/') }}/{{$datos_oftalmo->imag_biometria}}"
                                            download class="btn btn-success btn-flat">DESCARGAR</a>
                                          @endif
                                          @else
                                          <a class="btn btn-success btn-flat" disabled>DESCARGAR</a>
                                          @endif
                                        </span>
                                      </div> <br>
                                    </div>
                                    <div class="col-md-3 panel panel-info panel-bordered panel-sm">
                                      <br><label style="font-size: 14px;">RETINOGRAFIA</label>
                                      <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" disabled
                                          value="@if($datos_oftalmo!=null){{$datos_oftalmo->imag_retinografia}}@endif">
                                        <span class="input-group-btn">
                                          @if($datos_oftalmo!=null)
                                          @if($datos_oftalmo->imag_retinografia == null)
                                          <a class="btn btn-success btn-flat" disabled>DESCARGAR</a>
                                          @else
                                          <a href="{{ asset('uploads_oftalmo/') }}/{{$datos_oftalmo->imag_retinografia}}"
                                            download class="btn btn-success btn-flat">DESCARGAR</a>
                                          @endif
                                          @else
                                          <a class="btn btn-success btn-flat" disabled>DESCARGAR</a>
                                          @endif
                                        </span>
                                      </div> <br>
                                    </div>
                                    <div class="col-md-3 panel panel-info panel-bordered panel-sm">
                                      <br><label style="font-size: 14px;">CAMPIMETRIA</label>
                                      <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" disabled
                                          value="@if($datos_oftalmo!=null){{$datos_oftalmo->imag_campimetria}}@endif">
                                        <span class="input-group-btn">
                                          @if($datos_oftalmo!=null)
                                          @if($datos_oftalmo->imag_campimetria == null)
                                          <a class="btn btn-success btn-flat" disabled>DESCARGAR</a>
                                          @else
                                          <a href="{{ asset('uploads_oftalmo/') }}/{{$datos_oftalmo->imag_campimetria}}"
                                            download class="btn btn-success btn-flat">DESCARGAR</a>
                                          @endif
                                          @else
                                          <a class="btn btn-success btn-flat" disabled>DESCARGAR</a>
                                          @endif
                                        </span>
                                      </div> <br>
                                    </div>
                                    <div class="col-md-3 panel panel-info panel-bordered panel-sm">
                                      <br><label style="font-size: 14px;">ECOGRAFÍA OCULAR</label>
                                      <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" disabled
                                          value="@if($datos_oftalmo!=null){{$datos_oftalmo->imag_ecografia_ocular}}@endif">
                                        <span class="input-group-btn">
                                          @if($datos_oftalmo!=null)
                                          @if($datos_oftalmo->imag_ecografia_ocular == null)
                                          <a class="btn btn-success btn-flat" disabled>DESCARGAR</a>
                                          @else
                                          <a href="{{ asset('uploads_oftalmo/') }}/{{$datos_oftalmo->imag_ecografia_ocular}}"
                                            download class="btn btn-success btn-flat">DESCARGAR</a>
                                          @endif
                                          @else
                                          <a class="btn btn-success btn-flat" disabled>DESCARGAR</a>
                                          @endif
                                        </span>
                                      </div> <br>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          @endif
                        @endif
                          <div class="panel-body">
                            <div class="form-group">
                              <div class="col-md-12">
                                <label>EXAMEN FISICO</label>
                                  <p><?php echo $prestacion->examen_fisico?></p>
                              </div>
                            </div>
                          </div>
                          <div class="panel-body">
                            <div class="form-group">
                              <div class="col-md-12">
                                <label>EXAMEN CLINICO</label>
                                <p><?php echo $prestacion->examen_clinico?></p>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-md-12">
                                <label>EXAMENES AUXILIARES</label>
                                <p><?php echo $prestacion->examen_auxiliar?></p>
                              </div>
                            </div>
                          </div>
      	                </div>
                      </fieldset>
                      @if(($cita->id_establecimiento==76 &&  $cita->id_servicio == 102))
                        @if($datos_gastro!=null)
                          <div class="panel-body" id="VerGastro">
                            <div id="fsGastro" >
                              <div class="panel with-nav-tabs panel-default">
                                <div class="panel-heading">
                                  <ul class="nav nav-tabs">
                                    @if($datos_gastro->baja==1 && $datos_gastro->alta==0)
                                    <li class="active"><a href="#tab_baja" data-toggle="tab" style="font-size:12px">INFORME DE ENDOSCOPIA DIGESTIVA BAJA</a></li>
                                    @else
                                      @if($datos_gastro->alta==1 && $datos_gastro->baja==0)
                                      <li class="active"><a href="#tab_alta" data-toggle="tab" style="font-size:12px">INFORME DE ENDOSCOPIA DIGESTIVA ALTA</a></li>
                                      @else
                                        @if($datos_gastro->baja==1 && $datos_gastro->alta==1)
                                        <li class="active"><a href="#tab_baja" data-toggle="tab" style="font-size:12px">INFORME DE ENDOSCOPIA DIGESTIVA BAJA</a></li>
                                        <li><a href="#tab_alta" data-toggle="tab" style="font-size:12px">INFORME DE ENDOSCOPIA DIGESTIVA ALTA</a></li>
                                        @endif
                                      @endif
                                    @endif
                                  </ul>
                                </div>
                                <div class="panel-body">
                                  <div class="tab-content">
                                    @if($datos_gastro->baja==1 && $datos_gastro->alta==0)
                                    <div class="tab-pane fade in active" id="tab_baja">
                                    @else
                                      @if($datos_gastro->baja==0 && $datos_gastro->alta==1)
                                      <div class="tab-pane fade" id="tab_baja">
                                      @else
                                        @if($datos_gastro->baja==1 && $datos_gastro->alta==1)
                                        <div class="tab-pane fade in active" id="tab_baja">
                                        @endif
                                      @endif
                                    @endif
                                      <fieldset>
                                        <div class="panel-body">
                                          <div class="table-responsive">
                                            <table class="table table-striped table-list-search" id="tblGastroBaja" cellspacing="0" width="100%" style="margin-top:5px;">
                                              <tfoot>
                                              </tfoot>
                                              <tbody>
                                                <tr>
                                                    <td width="15%"><b>INDICACIONES</b></td>
                                                    <td width="35%" colspan="4">
                                                      {{$datos_gastro->baja_indicaciones}}
                                                    </td>
                                                    <td width="15%"><b>MEDICACION:</b></td>
                                                    <td width="35%" colspan="3">
                                                      {{$datos_gastro->baja_medicacion}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>INSPECCION:</b></td>
                                                    <td width="85%" colspan="8">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"></td>
                                                    <td width="15%"><b>ESTATICA:</b></td>
                                                    <td width="85%" colspan="7">
                                                      {{$datos_gastro->baja_estatica}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"></td>
                                                    <td width="15%"><b>DINAMICA:</b></td>
                                                    <td width="85%" colspan="7">
                                                      {{$datos_gastro->baja_dinamica}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"></td>
                                                    <td width="15%"><b>TACTO RECTAL:</b></td>
                                                    <td width="85%" colspan="7">
                                                      {{$datos_gastro->baja_tacto_rectal}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>PREPARACION:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->baja_preparacion}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>ESCALA DE BOSTON:</b></td>
                                                    <td>CD</td>
                                                    <td>
                                                      {{$datos_gastro->baja_escala_cd}}
                                                    </td>
                                                    <td>CT</td>
                                                    <td>
                                                      {{$datos_gastro->baja_escala_ct}}
                                                    </td>
                                                    <td>CI</td>
                                                    <td>
                                                      {{$datos_gastro->baja_escala_ci}}
                                                    </td>
                                                    <td>TOTAL</td>
                                                    <td>
                                                      {{$datos_gastro->baja_escala_total}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>INSERCION:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->baja_insercion}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>HALLAZGOS:</b></td>
                                                    <td width="85%" colspan="8">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>CANAL ANAL:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->baja_canal_anal}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>RECTO:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->baja_recto}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>SIGMOIDES:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->baja_sigmoide}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>DESCENDENTE:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->baja_descendente}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>Transverso:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->baja_transverso}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>Ascendente:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->baja_ascendente}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>Ciego:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->baja_ciego}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>BIOPSIA:</b></td>
                                                    <td width="30%" colspan="3">
                                                      {{$datos_gastro->baja_biopsia}}
                                                    </td>
                                                    <td width="15%"><b>COMPLICACIONES</b></td>
                                                    <td width="30%" colspan="4">
                                                      {{$datos_gastro->baja_complicaciones}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%" colspan="4"><b>OBSERVACIONES:</b></td>
                                                    <td width="85%" colspan="5">
                                                      {{$datos_gastro->baja_observaciones}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%" colspan="4"><b>DIAGNOSTICO ENDOSCOPICO:</b></td>
                                                    <td width="50%" colspan="5">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="100%" colspan="9">
                                                      {{$datos_gastro->baja_diag_endoscopico}}
                                                    </td>
                                                </tr>
                                              </tbody>
                                            </table>
                                          </div>
                                        </div>
                                      </fieldset>
                                    </div>
                                    @if($datos_gastro->alta==1 && $datos_gastro->baja==0)
                                    <div class="tab-pane fade in active" id="tab_alta">
                                    @else
                                      @if($datos_gastro->baja==1 && $datos_gastro->alta==1)
                                      <div class="tab-pane fade" id="tab_alta">
                                      @else
                                        @if($datos_gastro->baja==1 && $datos_gastro->alta==0)
                                        <div class="tab-pane fade" id="tab_alta">
                                        @endif
                                      @endif
                                    @endif
                                    
                                      <fieldset>
                                        <div class="panel-body">
                                          <div class="row">
                                            <table class="table table-striped table-list-search" id="tblGastroAlta" cellspacing="0" width="100%" style="margin-top:5px;">
                                              <tfoot>
                                              </tfoot>
                                              <tbody>
                                                <tr>
                                                    <td width="15%"><b>INDICACIONES</b></td>
                                                    <td width="30%" colspan="4">
                                                      {{$datos_gastro->alta_indicaciones}}
                                                    </td>
                                                    <td width="15%"><b>MEDICACION:</b></td>
                                                    <td width="30%" colspan="3">
                                                      {{$datos_gastro->alta_medicacion}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>HALLAZGOS</b></td>
                                                    <td width="85%" colspan="8">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>ESOFAGO:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->alta_esofago}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>ESTOMAGO</b></td>
                                                    <td width="85%" colspan="8">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>Cardias:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->alta_cardias}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>Fondo:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->alta_fondo}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>Cuerpo:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->alta_cuerpo}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>Antro:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->alta_antro}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>DUODENO</b></td>
                                                    <td width="85%" colspan="8">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>Bulbo:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->alta_bulbo}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>Segunda porción:</b></td>
                                                    <td width="85%" colspan="8">
                                                      {{$datos_gastro->alta_seg_porcion}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%"><b>BIOPSIA:</b></td>
                                                    <td width="30%" colspan="3">
                                                      {{$datos_gastro->alta_biopsia}}
                                                    </td>
                                                    <td width="15%"><b>COMPLICACIONES</b></td>
                                                    <td width="30%" colspan="4">
                                                      {{$datos_gastro->alta_complicaciones}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="15%" colspan="4"><b>OBSERVACIONES:</b></td>
                                                    <td width="85%" colspan="5">
                                                      {{$datos_gastro->alta_observaciones}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%" colspan="3"><b>DIAGNOSTICO ENDOSCOPICO:</b></td>
                                                    <td width="50%" colspan="6">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="100%" colspan="9">
                                                      {{$datos_gastro->alta_diag_endoscopico}}
                                                    </td>
                                                </tr>
                                              </tbody>
                                            </table>
                                          </div>
                                        </div>
                                      </fieldset>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>    
                        @endif
                      @endif
                      <fieldset id="fsAntecedente">
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>TRATAMIENTO Y/O TERAPIA</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            <div class="form-group">
                              <div class="col-md-12">
                                <label>TRATAMIENTO AUXILIAR</label>
                                <p><?php echo $prestacion->tratamiento_auxiliar?></p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                      <fieldset id="fsAntecedente">
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="panel-heading">
                            <div class="panel-title text-center">
                              <b>PLAN DE TRABAJO</b>
                            </div>
                          </div>
                          <div class="panel-body">
                            <div class="form-group">
                              <div class="col-md-12">
                                <label>PLAN DE TRABAJO</label>
                                <p><?php echo $prestacion->plan_trabajo?></p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </fieldset>
                      <fieldset>
                      @if($prestacion->id_tipo_atencion==1)
                        <div class="panel panel-info panel-bordered panel-sm panel-body">
                          <div class="form-group">
                            <div class="col-md-12">
                              <label class="col-md-2" style="padding-left:0px">DERIVAR CONSULTA</label>
                              <div class="col-md-2">
                                <p><?php echo ($prestacion->servicio_derivar!="")?"SI":"NO"?></p>
                              </div>
                              <?php if($prestacion->servicio_derivar!=""):?>
                              <label class="col-md-1" style="padding-left:0px">SERVICIO</label>
                              <div class="col-md-4">
                                <p><?php echo $prestacion->servicio_derivar?></p>
                              </div>
                              <?php endif;?>
                            </div>
                          </div>
                        </div>
                      @endif
                    </fieldset>
                    <fieldset>
                      <div class="panel panel-info panel-bordered panel-sm panel-body">
                        <div class="form-group">
                          <div class="col-md-12">
                            <label class="col-md-2" style="padding-left:0px">PRESCRIBIR RECETA</label>
                            <div class="col-md-8">
                              <p><?php echo (count($receta)>0)?"SI":"NO"?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                    {{-- GESTACIÃ“N --}}
                    @if (isset($gestacion->id))
                    <div class="panel panel-info panel-bordered panel-sm panel-body">
                        <div class="row">
                        <div class="col-2 col-sm-2">
                            <label >GESTACIÓN</label>
                        </div>
                        </div>
                        <hr />
                        <div class="row">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <label>ULTIMA MESTRUACIÓN:</label>
                            <p>{{$gestacion->fech_ult_regla}}</p>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <label>FECHA PROBABLE DE PARTO:</label>
                            <p>{{$gestacion->fech_prob_parto}}</p>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <label>SEMANAS DE GESTACIÓN:</label>
                            <p>{{$gestacion->semanas_gestacion}}</p>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <label>N ° DE CONTROLES PRENATALES:</label>
                            <p>{{$gestacion->num_controles}}</p>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <label>DUDA:</label>
                            <p>{{$gestacion->duda ? 'SI' : 'NO'}}</p>
                        </div>
                        </div>
                    </div>
                    @endif
                    {{-- GESTACIÃ“N END --}}

                    {{-- PUERPERA --}}
                    @if (isset($puerperio->id))
                      <div class="panel panel-info panel-bordered panel-sm panel-body">
                        <div class="row">
                          <div class="col-2 col-sm-2">
                            <label >PUERPERA</label>
                          </div>
                        </div>
                        <hr />
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <label>FECHA Y HORA PARTO:</label>
                            <p>{{$puerperio->fecha_parto}}</p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <label>TIPO DE PARTO:</label>
                            <p>{{$puerperio->id_tipo_parto}}</p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <label>COMPLICACIÓN:</label>
                            <p>{{$puerperio->complicaciones}}</p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                            <label>DESCRIPCIÓN:</label>
                            <p>{{$puerperio->descripcion}}</p>
                          </div>
                        </div>
                        <hr />
                        @if (isset($natis) && count($natis) > 0)
                          <div class="container-fluid">
                            <div class="row">
                              <div class="col-2 col-sm-2"><label >NEONATOS</label></div>
                            </div>
                            <div class="panel panel-info panel-bordered panel-sm panel-body">
                            @foreach ($natis as $row)
                              <div class="row">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                  <label>ESTADO DE VIDA:</label>
                                  <p><?php echo $row->estado?></p>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                  <label>TIPO DE DOCUMENTOS:</label>
                                  <p><?php echo $row->medico_paterno?></p>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                  <label>NUM. DOCUMENTOS:</label>
                                  <p><?php echo $row->num_doc?></p>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                  <label>SEXO:</label>
                                  <p><?php echo $row->sexo == 'M' ? 'MASCULINO' : 'FEMENINO' ?></p>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-2">
                                  <label>TALLA:</label>
                                  <p><?php echo $row->talla?></p>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                  <label>PESO:</label>
                                  <p><?php echo $row->peso?></p>
                                </div>
                              </div>
                              <hr>
                            @endforeach
                            </div>
                          </div>
                        @endif
                      </div>
                    @endif 
                    {{-- PUERPERA END --}}
                    <fieldset id="fsRecetaVale">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center">
                            <b>PRODUCTOS MEDICOS</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <?php foreach($receta as $rowReceta):
                    				if ($rowReceta->fecha_firma == "") {
                                            $flag_firmar = 1;
                                          }

                    				$model_productos_receta = new \App\Models\Producto_receta;
                    				$medicamentos = $model_productos_receta->ShowMedicinasById_receta_vale($rowReceta->id);
                    			?>
                        
                          <br />
                          <div style="clear:both"></div>
                            <fieldset>
                              <div class="panel panel-info panel-bordered panel-sm">
                                <div class="panel-body">
                                  <div class="form-group">
                                    <div class="col-md-12">
                                      <label class="col-md-2" style="padding-left:0px">RECETA VALE N °</label>
                                      <div class="col-md-1">
                                        <p><?php echo $rowReceta->nro_receta; ?></p>
                                      </div>
                                      <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. EXPEDICIÓN</label>
                                      <div class="col-md-2">
                                        <p><?php echo date("d-m-Y", strtotime($rowReceta->fecha_registro)); ?></p>
                                      </div>
                                      <label class="col-md-1" style="padding-left:0px">FARMACIA</label>
                                      <div class="col-md-4" style="padding:0px">
                                        <p><?php echo $rowReceta->farmacia; ?></p>
                                      </div>
                                    </div>
                                  </div>
                                  <table class="display" cellspacing="0" width="100%">
                                    <thead>
                                      <tr>
                                        <th width="10%">Codigo</th>
                                        <th width="30%">Producto farmacético o dispositivo médico</th>
                                        <th width="10%">Unidad Medida</th>
                                        <th width="7%">Tiempo(Dias)</th>
                                        <th width="15%">Posologia</th>
                                				<th width="10%">Via Administración</th>
                                				<th width="8%">Cantidad Prescrita</th>
                                				<th width="10%">diagnosticos</th>
                                      </tr>
                                    </thead>
                                    <tfoot>
                                    </tfoot>
                                    <?php foreach($medicamentos as $row):?>
                                    <tr>
                                      <td><?php echo $row->codigo?></td>
                                      <td><?php echo $row->nombre?>
                                          <?php 
                                            if($row->codigo=='59075700'):
                                              echo "(".$row->cantidad_prescrita."L)";
                                            endif; 
                                          ?>
                                      </td>
                                      <td><?php echo $row->abreviatura?></td>
                                      <td><?php echo $row->tiempo_periodo?></td>
                                      <td><?php echo $row->posologia?></td>
                                            <td>{{$row->via}}</td>
                                            <td><?php if($row->codigo=='59075700' || $row->codigo=='PF00718' || $row->codigo=='PF01047' || $row->codigo=='PF01007' || $row->codigo=='PF01008'):
                                               echo $row->cantidad_prescrita/1000;
                                               else:
                                                echo $row->cantidad_prescrita;
                                               endif; ?>
                                            </td>
                                            <td><?php
                                            $model_productos_receta = new \App\Models\Producto_receta;
                                                  $diagnostico_id = $row->id_diagnosticos;
                                                  $diagnostico_nombre = $model_productos_receta->get_diagnostico_nombre($diagnostico_id);
                                                   echo $diagnostico_nombre; ?>    
                                              </td>
                                          </tr>
                                          <?php endforeach;?>
                                    <tbody>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </fieldset>
                          <?php endforeach;?>
                         
                    <div style="clear:both"></div>
                    <fieldset id="fsNuevaRecetaVale" style="display:none">
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-md-2">
                              <label>FEC. EXPEDICIÓN (A)</label>
                              <input type="text" class="form-control datepicker"
                                value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_expedicion"
                                id="fecha_expedicion" tabindex="2" autocomplete="off" />
                            </div>
                            <div class="col-md-5">
                              <label>FARMACIA</label>
                              <select class="form-control" name="id_farmacia_receta" id="id_farmacia_receta"
                                style="width:100%" onchange="getProducto()">
                                <option value="">-Seleccionar-</option>
                                <?php foreach($farmacias as $row):?>
                                <option value="<?php echo $row->id?>"><?php echo $row->nombre?></option>
                                <?php endforeach;?>
                              </select>
                            </div>
                          </div>
                      
                          <br />
                          <div style="display: none">
                            <select class="form-control" id="cmbMedicamentoTemp" tabindex="16" style="width: 500px">
                              <option value="">Seleccionar Producto</option>
                              @foreach($producto as $p)
                              <option value="{{$p->id}}" ru="{{$p->ru}}">{{$p->codigo}} - {{$p->nombre}},
                                {{$p->abreviatura}}</option>
                              @endforeach
                            </select>
                          </div>
                          <input type="hidden" value="0" name="hidden_rowIndex" id="hidden_rowIndex">
                          <table id="example" class="display" cellspacing="0" width="100%">
                            <thead>
                              <tr>
                                <th>Producto farmaceútico o dispositivo médico</th>
                                <th style="width:10%">Stock Farmacia</th>
                                <th>Tiempo(Dias)</th>
                                <th> Via Adm. </th>
                                <th>Posologia</th>
                                <th> Cant. Pres.</th>
                                <th> DiagnosticoxProducto </th>
                                <th>  </th>
                                <th>  </th>
                              </tr>
                            </thead>
                            <tfoot>
                            </tfoot>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </fieldset>

              {{--CENTRO QUIRURGICO INICIO--}}
              @if ($data_cqx_cita == null)
              <?php
              $hay_riesgo_qx = "NO";
              $hay_riesgo_qx = (count($riesgos_qx) > 0) ? "SI" : "NO";
              ?>
              <div class="panel panel-info panel-bordered panel-sm panel-body">
                <div class="form-group">
                  <div class="col-sm-2">
                    <label>INTERVENCIÓN QUIRÚRGICA</label>
                  </div>
                  <div class="col-sm-2">
                    <p>{{$hay_riesgo_qx}}</p>
                  </div>
                </div>
                @if ($hay_riesgo_qx == "SI")
                <fieldset id="fsInterQuirurgica">
                  <div class="panel panel-info panel-bordered panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center">
                        <b>RIESGO QUIRÚRGICO</b>
                      </div>
                    </div>
                    <div class="panel-body">
                      <!-- <div style="display: flex; flex-direction: column; gap: 1rem; padding-bottom: 3rem;"> -->
                        @foreach ($riesgos_qx as $r)
                        <div class="form-group">
                          <div class="col-sm-1">
                            <input type="checkbox" name="rq_cardiologia" id="rq_cardiologia" value="1" class="" disabled checked="checked" />
                          </div>
                          <div class="col-sm-10" style="">
                            <label>{{$r->servicio}}</label>
                          </div>
                        </div>
                        @endforeach
                      <!-- </div> -->

                      <?php
                      $hay_interconsulta_pre_qx = "NO";
                      $hay_interconsulta_pre_qx = (count($interconsultas_pre_qx) > 0) ? "SI" : "NO";
                      ?>
                      <div class="panel panel-info panel-bordered panel-sm panel-body">
                        <div class="form-group">
                          <div class="col-sm-2">
                            <label>INTERCONSULTA PRE-QUIRÚRGICA</label>
                          </div>
                          <div class="col-sm-2">
                            <p>{{$hay_interconsulta_pre_qx}}</p>
                          </div>
                        </div>
                        @if ($hay_interconsulta_pre_qx == "SI")
                        <fieldset id="fsInterPreQuirurgica">
                          <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-heading">
                              <div class="panel-title text-center">
                                <b>INTERCONSULTA</b>
                              </div>
                            </div>
                            <div class="panel-body">
                              <div class="form-group">
                                <div class="col-sm-3">
                                  <p><b>SERVICIO</b></p>
                                </div>
                                <div class="col-sm-7">
                                  <p><b>MOTIVO DE INTERCONSULTA</b></p>
                                </div>
                              </div>
                              @foreach($interconsultas_pre_qx as $i)
                              <div class="form-group">
                                <div class="col-sm-3">
                                  <p>{{$i->servicio}}</p>
                                </div>
                                <div class="col-sm-7">
                                  <p>{{$i->motivo_interconsulta}}</p>
                                </div>
                              </div>
                              @endforeach
                            </div>
                          </div>
                        </fieldset>
                        @endif
                      </div>


                    </div>
                  </div>
                </fieldset>
                @endif
              </div>
              {{----------------------------}}
              @endif

              <!-- Inicio Programación Quirúrgica -->
              @if ($data_cqx_cita != null && $data_cqx_cita->tipo == 2)
              <!-- Es una Cita de Seguimiento -->
              <div class="panel panel-info panel-bordered panel-sm panel-body">
                <div class="form-group">
                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                    <label>PROGRAMACIÓN QUIRÚRGICA</label>
                  </div>
                  <div class="col-sm-2">
                    <p>@if ($data_cqx_cita->is_programacion_quirurgica) SI @else NO @endif</p>
                  </div>
                </div>
                @if ($data_cqx_cita->is_programacion_quirurgica)
                <fieldset id="fsProgramacionQx">
                  <div class="panel panel-info panel-sm">
                    <div class="panel-heading">
                      <div class="panel-title text-center"><b>REGISTRAR PROGRAMACIÓN QUIRÚRGICA</b></div>
                    </div>
                    <div class="panel-body">
                      <div class="form-group">
                        <div class="col-sm-2">
                          <label for="">PRIORIDAD DE PROGRAMACION EN SEMANAS</label>
                        </div>
                        <div class="col-sm-2">
                          <p>{{ $data_cqx_cita->prioridad }}</p>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                          <label>REQUIERE INTERNAMIENTO PRE QX</label>
                        </div>
                        <div class="col-sm-2">
                          <p>@if ($data_cqx_cita->is_requiere_internamiento_preqx) SI @else NO @endif</p>
                        </div>
                        <div class="col-sm-2">
                          @if ($data_cqx_cita->is_requiere_internamiento_preqx)
                          <p>{{ $data_cqx_cita->tiempo_internamiento_preqx }}</p>
                          @endif
                        </div>
                        <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                          <label>DIAS DE HOSPITALIZADO POST QX</label>
                        </div>
                        <div class="col-sm-2">
                          <p>@if ($data_cqx_cita->is_hospitalizacion_postqx) SI @else NO @endif</p>
                        </div>
                        <div class="col-sm-2">
                          @if ($data_cqx_cita->is_hospitalizacion_postqx)
                          <p>{{ $data_cqx_cita->tiempo_hospitalizacion_postqx }}</p>
                          @endif
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-2">
                          <label for="">A SER OPERADO EN:</label>
                        </div>
                        <div class="col-sm-2">
                          <p>{{ $data_cqx_cita->lugar_operacion }}</p>
                        </div>
                      </div>
                      <div class="panel panel-info panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center"><b>CIRUJANOS QUE INTERVIENEN EN LA OPERACIÓN</b></div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-sm-12">
                              <table id="table_medico_cirujano" class="display" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th>Apellidos y Nombres</th>
                                    <th style="width:30%">Cargo</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach ($cqx_cita_profesionales as $p)
                                  <tr>
                                    <td>{{ $p->paterno }} {{ $p->materno }}, {{ $p->nombre }}</td>
                                    <td>{{ $p->tipo_profesional }}</td>
                                  </tr>
                                  @endforeach
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="panel panel-info panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title text-center"><b>PROCEDIMIENTOS DE INTERVENCIÓN</b></div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-sm-12">
                              <table id="table_procedimiento_intervencion" class="display" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th>Código y Descripción</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach ($cqx_cita_procedimientos as $p)
                                  <tr>
                                    <td>{{ $p->codigo_cpms }} - {{ $p->descripcion_cpms }}</td>
                                  </tr>
                                  @endforeach
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </fieldset>
                @endif
              </div>
              @elseif ($data_cqx_cita != null && $data_cqx_cita->tipo == 1)
              <div class="panel panel-info panel-bordered panel-sm panel-body">
                <div class="form-group">
                  <div class="col-md-2">
                    @if ($data_cqx_cita->id_consultorio == 72)
                    <label for="">Sociedad Americana de Anestesiología - ASA:</label>
                    @else
                    <label for="">RIESGO:</label>
                    @endif
                  </div>
                  <div class="col-md-1">
                    <p>{{ $data_cqx_cita->numero_riesgo }}</p>
                  </div>
                  @if ($data_cqx_cita->id_consultorio == 72)
                  <div class="col-md-2">
                    <label for="">TIPO CIRUGÍA:</label>
                  </div>
                  <div class="col-md-2">
                    <p>{{ $data_cqx_cita->tipo_cirugia }}</p>
                  </div>
                  <div class="col-md-2">
                    <label for="">OBSERVACIÓN DE ASA:</label>
                  </div>
                  <div class="col-md-3">
                    <p>{{ $data_cqx_cita->observacion_asa }}</p>
                  </div>
                  @endif
                </div>
                <hr>
                <!--  -->
                <div class="form-group">
                  <div class="col-sm-2" style="padding-top:5px;margin-right:0px;padding-right:0px">
                    <label>OBSERVACION DE INTERVENCION QX</label>
                  </div>
                  <div class="col-sm-2">
                    <p>@if ($data_cqx_cita->flag == 3) NO @elseif ($data_cqx_cita->flag == 4) SI @endif</p>
                  </div>
                </div>
                @if ($data_cqx_cita->flag == 4) 
                <div class="form-group">
                  <div class="col-md-12">
                    <label for="">MOTIVO DE OBSERVACION PARA INTERVENCION QUIRÚRGICA</label>
                    <p>{{ $data_cqx_cita->observacion }}</p>
                  </div>
                </div>
                @endif
              </div>
              @endif
              <!-- Fin Programación Quirúrgica -->
              <fieldset id="fsExamenLab">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>EXAMEN DE LABORATORIO</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <?php foreach($laboratorio as $rowLaboratorio):

                        if ($rowLaboratorio->fecha_firma == "") {
                            $flag_firmar_lab = 1;
                        }

                      $model_prestacion = new \App\Models\Prestaciones;
                      $proclaboratorio = $model_prestacion->GetPrestacionLaboratorio($id_prestacion, $rowLaboratorio->id);
                    ?>
                    <br />
                    <div style="clear:both"></div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <label class="col-md-2" style="padding-left:0px">N ° ORDEN</label>
                        <div class="col-md-3">
                          <p><?php echo $id_prestacion.' - '. $rowLaboratorio->id; ?></p>
                        </div>
                        <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. ORDEN</label>
                        <div class="col-md-3">
                          <p><?php echo date("d-m-Y", strtotime($rowLaboratorio->created_at)); ?></p>
                        </div>
                      </div>
                    </div>
                    <fieldset>
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-md-12">
                              
                              <table class="display" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th width="5%">Codigo</th>
                                    <th width="45%">Nombre</th>
                                    <th width="30%">Observacion</th>
                                    <th width="10%">Cantidad</th>
                                    <th width="10%">Diagnosticos</th>
                                  </tr>
                                  </tr>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                  <?php foreach($proclaboratorio as $row):?>
                                  <tr>
                                    <td><?php echo $row->codigo?></td>
                                    <td><?php echo $row->nombre?></td>
                                    <td><?php echo $row->observacion?></td>
                                    <td><?php echo $row->cantidad?></td>
                                    <td><?php echo $row->cod_diag?></td>
                                  </tr>
                                  <?php endforeach;?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                    <?php endforeach;?>
                    
                    <?php //endif; ?>
                    <div style="clear:both"></div>
                    <fieldset id="fsNuevoExamenLab" style="display:none">
                      <div class="panel-body">
                        <br />
                        <table class="display" id="tblExamenLab" cellspacing="0" width="100%" style="margin-top:5px;">
                          <thead>
                            <tr>
                                    <th width="45%">Examen</th>
                                    <th width="25%">Observacion</th>
                                    <th width="5%">Cantidad</th>
                                    <th width="25%">Diagnosticos</th>
                            <th></th>
                            </tr>
                          </thead>
                          <tfoot>
                          </tfoot>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </fieldset>
              <fieldset id="fsExamenRad">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>EXAMEN DE IMAGENOLOGIA</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    @if ( count($ordenImagenesAnulada) > 0 )
                      @foreach ($ordenes as $orden )
                        <div class="alert alert-danger" role="alert">
                          <div class="col-lg-12">
                            Examenes Invalidados por el servicio de imagenes
                          </div>
                          <div class="col-lg-6">
                            <label class="control-label">Tecnologo o Profesional de Imagenes: </label>
                            <span>{{ $orden->usuario }}</span>
                          </div>
                          <div class="col-lg-3">
                              <label class="control-label">Orden: </label>
                              <span>{{ $orden->id_radiologia }}</span>
                          </div>
                          <div class="col-lg-3">
                              <label class="control-label">Fecha de Anulación: </label>
                              <span>{{ $orden->fecha_orden_invalidado }}</span>
                          </div>
                          <div class="col-lg-12">
                            <label class="control-label">Motivo: </label>
                            <span>{{ $orden->obs_examen_invalidado }}</span>
                          </div>
                          <table  class="table table-bordered" cellspacing="0" width="100%" style="margin-top:5px;">
                              <thead>
                                  <tr>
                                      <th>N°</th>
                                      <th class="col-lg-2">CÓDIGO</th>
                                      <th class="col-lg-8">DESCRIPCIÓN</th>
                                      <th class="col-lg-2">DIAGNOSTICO</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php 
                                      $i = 1;
                                      foreach( $orden->cpms as $row):
                                  ?>
                                  <tr>
                                      <td>{{$i}}</td>
                                      <td class="col-lg-2">{{$row->codigo_rad}}</td>
                                      <td class="col-lg-8">{{$row->descripcion_corta}}</td>
                                      <td class="col-lg-2">{{$row->diagnostico}}</td>
                                  </tr>
                                  <?php 
                                      $i++;
                                      endforeach;
                                  ?>
                              </tbody>
                          </table>
                        </div>  
                      @endforeach
                    @endif

                    <?php foreach($radiologia as $rowRadiologia):

                        if ($rowRadiologia->fecha_firma == "") {
                            $flag_firmar_lab = 1;
                        }

                      $model_prestacion = new \App\Models\Prestaciones;
                      $procradiologia = $model_prestacion->GetProcedimientosByIdPrestacionAndIdLaboratorio($id_prestacion, $rowRadiologia->id,3);
                    ?>
                    <br />
                    <div style="clear:both"></div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <label class="col-md-2" style="padding-left:0px">N ° ORDEN</label>
                        <div class="col-md-3">
                          <p><?php echo $id_prestacion.' - '. $rowRadiologia->id; ?></p>
                        </div>
                        <label class="col-md-2" style="padding-left:0px;text-align:right">FEC. ORDEN</label>
                        <div class="col-md-3">
                          <p><?php echo date("d-m-Y", strtotime($fechaServidor)); ?></p>
                        </div>
                       
                      </div>
                    </div>
                    <br />
                    <div style="clear:both"></div>                    
              <fieldset>
                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-body">
                          <div class="form-group">
                            <div class="col-md-12">
                              <table class="display" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th width="5%">Codigo</th>
                                    <th width="40%">Nombre</th>
                                    <th width="10%">Comparativo</th>
                                    <th width="10%">Lado</th>
                                    <th width="15%">Observacion</th>
                                    <th width="5%">Cantidad</th>
                                    <th width="10%">Diagnostico</th>
                                    <th width="10%">Radiología Portátil</th>
                                  </tr>
                                  
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                  <?php foreach($procradiologia as $row):?>
                                  <tr>
                                    <td><?php echo $row->codigo?></td>
                                    <td><?php echo $row->nombre_corto?></td>
                          					<td><?php 
                                              if($row->comparativo_radio!=null):
                                  						   if($row->comparativo_radio==1):
                                                  echo "SI";
                                  						   else:
                                  						    echo "NO";
                                  						   endif;
                                              endif;
                                       ?>
                                    </td>
                          					<td><?php 
                                              if($row->lado_radio!=null):
                                  						   if($row->lado_radio==1):
                                                    echo "Izquierdo";
                                  						   else:
                                  						      echo "Derecho";
                                  						   endif;
                                              endif;
                                         ?>
                                      </td>
                                      <td><?php 
                                                if($row->observacion!='0'):
                                                    echo $row->observacion;
                                                endif;
                                            ?>
                                      </td>
                                      <td><?php echo $row->cantidad?></td>
                                      <td><?php echo $row->cod_diag?></td>
                                      <td><?php echo $row->radiologia_portatil?></td>
                                  </tr>
                                  <?php endforeach;?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </fieldset>
                    <?php endforeach;?>
                  
                    <?php //endif; ?>
                    <div style="clear:both"></div>
                    <fieldset id="fsNuevoExamenRad" style="display:none">
                      <div class="panel-body">
                        <br />
                        <table class="display" id="tblExamenRad" cellspacing="0" width="100%" style="margin-top:5px;">
                          <thead>
                            <tr>
                                    <th width="35%">Nombre</th>
                                    <th width="3%">Comparativo</th>
                                    <th width="3%">Lado</th>
                                    <th width="20%">Observacion</th>
                                    <th width="5%">Cantidad</th>
                                    <th width="20%">Diagnostico</th>
                                    <th width="14%">Radiología Portátil</th>
                            </tr>
                          </thead>
                          <tfoot>
                          </tfoot>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </fieldset>
              <!--INTERCONSULTA 
              <fieldset id="fsInterconsulta">
                <div class="panel panel-info panel-bordered panel-sm">
                  <div class="panel-heading">
                    <div class="panel-title text-center">
                      <b>INTERCONSULTA</b>
                    </div>
                  </div>
                  <div class="panel-body">
                    <br />
                    <div style="clear:both"></div>
                    <fieldset>
                      <div class="col-md-12">
                        <?php foreach($interconsultas as $rowi):?>
                        <div class="panel panel-info panel-bordered panel-sm">
                          <div class="form-group"> 
                            <div class="panel-body"> 
                              <div class="col-md-12">
                                <div class="col-md-2"> 
                                  <label>SERVICIO: </label>
                                </div> 
                                <div class="col-md-10"> 
                                  <?php echo $rowi->servicio ?>
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div class="col-md-2"> 
                                  <label>MOTIVO:</label> 
                                </div> 
                                <div class="col-md-10"> 
                                  <?php echo $rowi->motivo_interconsulta ?>
                                </div>
                              </div>
                              <div class="col-md-12">
                                <div class="col-md-12"><br/> 
                                  <label>RESUMEN</label><br/>  
                                  <?php echo $rowi->resumen_interconsulta ?>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <?php endforeach;?>
                      </div>
                    </fieldset>
                    
                    <div style="clear:both"></div>
                    <fieldset id="fsNuevaInterconsulta" style="display:none">
                      <div class="panel-body">
                       
                        <br />
                        <table class="display" id="tblInterconsulta" cellspacing="0" width="100%" style="margin-top:5px;">
                          <thead>
                              <tr>
                               <th width="90%"></th>
                               <th width="10%"></th>
                            </tr>
                          </thead>
                          <tfoot>
                          </tfoot>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </fieldset>
              -->
              

              <?php //endif;?>

              <!-- Inicio Fecha Probable Intervencion -->
              @if ($data_cqx_cita != null && $data_cqx_cita->tipo == 2)
              <div class="panel panel-info panel-sm">
                <div class="panel-heading">
                  <div class="panel-title text-center"><b>FECHA PROBABLE DE INTERVENCIÓN QUIRÚRGICA</b></div>
                </div>
                <div class="panel-body">
                  <div class="form-group">
                    <div class="col-md-offset-5 col-md-2">
                      <input type="text" id="fecha_probable" class="form-control" value="@if ($data_cqx_cita->fecha_probable != null) {{ date('d-m-Y', strtotime($data_cqx_cita->fecha_probable)) }} @endif" readonly>
                    </div>
                  </div>
                </div>
              </div>
              @endif
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

@include('frontend.includes.footer')
@endsection


