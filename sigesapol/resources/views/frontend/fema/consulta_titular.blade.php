@extends('frontend.layouts.master')
@section('renderjs')
<style>
</style>
<script>
  function maxlength_doc_usu() {
    $('#nroDocSaludpol').val('');
    if ($("#tipo_doc_saludpol").val() == "1") {
      $("#nroDocSaludpol").attr('maxlength', '8');
    } else {
      $("#nroDocSaludpol").attr('maxlength', '12');
    }
    document.getElementById("nroDocSaludpol").focus();
  }

$("#divreniec").hide();
$("#divdirrehum").hide();
$("#divdirrehum_saludpol").hide();
$("#divapisaludpol").hide();

function getDirrehumByDni() {
  limpiar();
    var dni_paciente = $("#dni").val();

    if(dni_paciente==""){
       bootbox.alert("Falta ingresar el Documento del paciente");
       $('#dni').focus();
       return 0;
    }

    if (dni_paciente.length != 8) {
      bootbox.alert("<b>El N° DNI debe tener 8 dígitos.</b>")
      $('#dni').focus();
      return 0;
    }

    $('#content').html('<div class="loading"><img src="images/loader.gif" alt="loading" /><br/>Consultando ...</div>');
    $('#btnBuscar').prop('disabled', true);
    $('#btnBuscarReniec').prop('disabled', true);

    var paciente_no_existe=false;
    $.ajax({
      url: "/ws_dirrehum/1/"+dni_paciente,   ///ws_dirrehum/{tipo_doc}/{nro_doc}
      type: "GET",
      dataType: "JSON",
      success: function(result) { //alert(result.id);
         //alert(result[0]);
         console.log(result.length);
        if (result.length > 1) {
          $('#primer_apellido').val(result[0].PATERNO);
          $('#segundo_apellido').val(result[0].MATERNO);
          $('#nombres').val(result[0].NOMBRES);
          $('#dni_titular').val(result[0].DNI);
          // $('#tipo').val(result.TIPO);
          $('#carnet').val(result[0].CARNE);
          $('#grado').val(result[0].GRADO);
          $('#situacion').val(result[0].SITUACION);
          $('#estado_civil').val(result[0].EST_CIVIL);
          $('#sexo').val(result[0].SEXO);
          $('#fecha_nacimiento').val(result[0].NACIMIENTO);
          $('#tipo_sangre').val(result[0].TSANGRE_DES);
          $('#unidad').val(result[0].UNIDAD);
          $('#situacion_especial').val(result[0].TSITE_DES);
          $('#fecha_retiro').val(result[0].FRET);
        } else {
          $('#primer_apellido').val(result.PATERNO);
          $('#segundo_apellido').val(result.MATERNO);
          $('#nombres').val(result.NOMBRES);
          $('#dni_titular').val(result.DNI);
          // $('#tipo').val(result.TIPO);
          $('#carnet').val(result.CARNE);
          $('#grado').val(result.GRADO);
          $('#situacion').val(result.SITUACION);
          $('#estado_civil').val(result.EST_CIVIL);
          $('#sexo').val(result.SEXO);
          $('#fecha_nacimiento').val(result.NACIMIENTO);
          $('#tipo_sangre').val(result.TSANGRE_DES);
          $('#unidad').val(result.UNIDAD);
          $('#situacion_especial').val(result.TSITE_DES);
          $('#fecha_retiro').val(result.FRET);
        }

        $('#content').html('');
        $('#btnBuscar').prop('disabled', false);
        $('#btnBuscarReniec').prop('disabled', false);
        $("#divdirrehum").show();
        $("#divreniec").hide();
        $("#divdirrehum_saludpol").show();
        $("#divapisaludpol").hide();
        

      },
      error: function(xhr, status) {
        bootbox.alert("Ha ocurrido un error al consultar el N° Documento del paciente.");
      },
      complete : function(xhr, status) {
        $('#content').html('');
        $('#btnBuscar').prop('disabled', false);
        $('#btnBuscarReniec').prop('disabled', false);
      }
    });

  }

  function getReniecByDni() {
    limpiar();
    var dni_paciente = $("#dniReniec").val();

    if(dni_paciente==""){
       bootbox.alert("Falta ingresar el Documento del paciente");
       $('#dniReniec').focus();
       return 0;
    }

    if (dni_paciente.length != 8) {
      bootbox.alert("<b>El N° DNI debe tener 8 dígitos.</b>");
      $('#dniReniec').focus();
      return 0;
    }

    $('#content').html('<div class="loading"><img src="images/loader.gif" alt="loading" /><br/>Consultando ...</div>');
    $('#btnBuscar').prop('disabled', true);
    $('#btnBuscarReniec').prop('disabled', true);

    var paciente_no_existe=false;
    $.ajax({
      //url: '/validar_documento/' + dni_paciente,
      url: "/validate_entity/"+dni_paciente,
      type: "GET",
      dataType: "JSON",
      success: function(result) {
        if (result.codigoMensaje == '00') {
          
          $('#primer_apellido').val(result.paterno);
          $('#segundo_apellido').val(result.materno);
          $('#nombres').val(result.nombres);
          $('#dni_titular').val(result.dni);
          @if ($permiso->crear == 1)
          $('#foto').val(result.foto);
          $('#foto-reniec').html('<img src="data:image/png;base64,'+result.foto+'" title="foto reniec" width="150" alt=""/>')
          @endif
          // $('#tipo').val(result.TIPO);
          $('#carnet').val('');
          $('#grado').val('');
          $('#situacion').val('');
          $('#estado_civil').val(result.estado_civil);
          $('#sexo').val(result.sexo);
          $('#fecha_nacimiento').val(result.vfecha_nac);
          $('#tipo_sangre').val('');
          $('#unidad').val('');
          $('#situacion_especial').val('');
          $('#fecha_retiro').val('');
          $('#fecha_emision').val(result.vfecha_expedicion);
          $('#fecha_caducidad').val(result.vfecha_caducidad);
          $('#distrito_nacimiento').val(result.departamento_nacimiento+'/'+result.provincia_nacimiento+'/'+result.distrito_nacimiento)
          $('#distrito_domicilio').val(result.departamentoDomicilio+'/'+result.provinciaDomicilio+'/'+result.distritoDomicilio)
          $('#localidad_domicilio').val(result.localidad_domicilio);
          $('#direccion_domicilio').val(result.direccionDomicilio);
        }

        $('#content').html('');
        $('#btnBuscar').prop('disabled', false);
        $('#btnBuscarReniec').prop('disabled', false);
        $("#divreniec").show();
        $("#divdirrehum").hide();
        $("#divapisaludpol").hide();
        $("#divdirrehum_saludpol").hide();
      },
      error: function(xhr, status) {
        bootbox.alert("Ha ocurrido un error al consultar el N° Documento del paciente.");
      },
      complete : function(xhr, status) {
        $('#content').html('');
        $('#btnBuscar').prop('disabled', false);
        $('#btnBuscarReniec').prop('disabled', false);
      }
    });

  }

function validarDni() {
  var dni = $('#dni').val();
  let tipoDocumento = $("#tipo_documento").val();
  $('#tipo_documento_text').val($("#tipo_documento option:selected").text());

  var len = dni.length;
  if (tipoDocumento == "1" && len != 8) {
    bootbox.alert("El DNI tiene que ser de 8 digitos");
    return false;
  } else if (tipoDocumento != "1" && len < 9) {
    bootbox.alert("El Documento tiene que ser de 9 a más digitos");
    return false;
  }
  document.getElementById("frmfarmacia").submit();
}

// function maxlength_doc_usu() {

//   $('#dni').val('');

//   if ($("#tipo_documento").val() == "1") {
//     $("#dni").attr('maxlength', '8');
//   } else {
//     $("#dni").attr('maxlength', '12');
//   }
//   document.getElementById("dni").focus();
// }

function limpiar() {
  $('#primer_apellido').val("");
  $('#segundo_apellido').val("");
  $('#nombres').val("");
  $('#dni_titular').val("");
  // $('#tipo').val(result.TIPO);
  $('#carnet').val("");
  $('#grado').val("");
  $('#situacion').val("");
  $('#estado_civil').val("");
  $('#sexo').val("");
  $('#fecha_nacimiento').val("");
  $('#tipo_sangre').val("");
  $('#unidad').val("");
  $('#situacion_especial').val("");
  $('#fecha_retiro').val("");
  $('#fecha_emision').val("");
  $('#fecha_caducidad').val("");
  $('#distrito_nacimiento').val("");
  $('#distrito_domicilio').val("");
  $('#localidad_domicilio').val("");
  $('#direccion_domicilio').val("");
  $('#foto').val("");
  $('#foto-reniec').html("");
}

  function getSaludpolByTipoDocAndNroDoc() {
    limpiar();
    var nro_doc = $("#nroDocSaludpol").val();
    var tipo_doc = $("#tipo_doc_saludpol").val();

    if(nro_doc==""){
       bootbox.alert("Falta ingresar el Documento del paciente");
       $('#nroDocSaludpol').focus();
       return 0;
    }

    if (nro_doc.length > 0) {
      if (tipo_doc == '1' && nro_doc.length != 8) {
        bootbox.alert("<b>El N° DNI debe tener 8 dígitos.</b>");
        $('#nroDocSaludpol').focus();
        return 0;
      }
    }

    $('#content').html('<div class="loading"><img src="images/loader.gif" alt="loading" /><br/>Consultando ...</div>');
    $('#btnBuscar').prop('disabled', true);
    $('#btnBuscarReniec').prop('disabled', true);
    $('#btnBuscarSaludpol').prop('disabled', true);

    var paciente_no_existe=false;
    $.ajax({
      //url: '/validar_documento/' + dni_paciente,
      url: "/ws_saludpol/"+tipo_doc+"/"+nro_doc.toUpperCase(),
      type: "GET",
      dataType: "JSON",
      success: function(result) {
        if (result.nompaisdelafiliado != '') {
          
          $('#primer_apellido').val(result.apepatafiliado);
          $('#segundo_apellido').val(result.apematafiliado + " " + result.apecasafiliado);
          $('#nombres').val(result.nomafiliado);
          $('#dni_titular').val(result.nrodocafiliado);
          // $('#tipo').val(result.TIPO);
          $('#carnet').val(result.cip);
          $('#grado').val(result.grado);
          $('#situacion').val(result.situacion);
          $('#estado_civil').val('');
          $('#sexo').val(result.nomsexo);
          $('#fecha_nacimiento').val(result.fecnacafiliado);
          $('#tipo_sangre').val('');
          $('#unidad').val(result.unidad_pnp);
          $('#situacion_especial').val('');
          $('#fecha_retiro').val('');
          $('#fecha_emision').val('');
          $('#fecha_caducidad').val(result.caducidad != "" ? result.caducidad.substring(6, 8)+'/'+result.caducidad.substring(4, 6)+'/'+result.caducidad.substring(0, 4) : "");
          $('#distrito_nacimiento').val('')
          $('#distrito_domicilio').val('')
          $('#localidad_domicilio').val('');
          $('#direccion_domicilio').val('');
        }

        $('#content').html('');
        $('#btnBuscar').prop('disabled', false);
        $('#btnBuscarReniec').prop('disabled', false);
        $('#btnBuscarSaludpol').prop('disabled', false);
        $("#divreniec").hide();
        $("#divdirrehum").hide();
        $("#divapisaludpol").hide();
        $("#divdirrehum_saludpol").show();
      },
      error: function(xhr, status) {
        bootbox.alert("Ha ocurrido un error al consultar el N° Documento del paciente.");
      },
      complete : function(xhr, status) {
        $('#content').html('');
        $('#btnBuscar').prop('disabled', false);
        $('#btnBuscarReniec').prop('disabled', false);
      }
    });
  }

  function getSaludpolAPIByTipoDocAndNroDoc() {
    limpiar();
    var nro_doc = $("#nroDocSaludpolapi").val();
    var tipo_doc = $("#tipo_doc_saludpolapi").val();

    if(nro_doc==""){
       bootbox.alert("Falta ingresar el Documento del paciente");
       $('#nroDocSaludpolapi').focus();
       return 0;
    }

    if (nro_doc.length > 0) {
      if (tipo_doc == '1' && nro_doc.length != 8) {
        bootbox.alert("<b>El N° DNI debe tener 8 dígitos.</b>");
        $('#nroDocSaludpolapi').focus();
        return 0;
      }
    }

    $('#content').html('<div class="loading"><img src="images/loader.gif" alt="loading" /><br/>Consultando ...</div>');
    $('#btnBuscar').prop('disabled', true);
    $('#btnBuscarReniec').prop('disabled', true);
    $('#btnBuscarSaludpol').prop('disabled', true);

    var paciente_no_existe=false;
    $.ajax({
      //url: '/validar_documento/' + dni_paciente,
      url: '/saludpol/consultar',
      type: "POST",
      data: {
        tipoDocumento: tipo_doc,
        numeroDocumento: nro_doc,
      },
      dataType: "JSON",
      success: function(result) {

        var datos = result.data.data;
        $('#primer_apellido').val(datos.apellidoPaternoAsegurado);
        $('#segundo_apellido').val(datos.apellidoMaternoAsegurado);
        $('#nombres').val(datos.nombreAsegurado);
        $('#dni_titular').val(datos.numeroDocumentoAsegurado);

        $('#discapacidad_api').val(datos.discapacidad);
        $('#grado_api').val(datos.gradoTitular);
        $('#situacion_api').val(datos.situacionTitular);
        $('#condicion_api').val(datos.condicionAsegurado);
        $('#estado_civil_api').val(datos.estadoCivilAsegurado);
        $('#sexo').val(datos.sexo);
        $('#fecha_nacimiento').val(datos.fechaNacimientoAsegurado);

        $('#fin_afi_api').val(datos.fechaFinAfiliacion);
        $('#ini_afi_api').val(datos.fechaInicioAfiliacion);
        $('#estado_afi_api').val(datos.estadoAsegurado);
        if (datos.estadoAsegurado !== 'ACTIVO') {
          $('#estado_afi_api').css({
            'border': '2px solid #D42700',
            'box-shadow': '0 0 7px #D42700'
          });
        } else {
          $('#estado_afi_api').css({
            'border': '',
            'box-shadow': ''
          });
        }


        $('#content').html('');
        $('#btnBuscar').prop('disabled', false);
        $('#btnBuscarReniec').prop('disabled', false);
        $('#btnBuscarSaludpol').prop('disabled', false);
        $("#divreniec").hide();
        $("#divdirrehum").hide();
        $("#divapisaludpol").show();
        $("#divdirrehum_saludpol").hide();
      },
      error: function(xhr, status) {
        bootbox.alert("Ha ocurrido un error al consultar el N° Documento del paciente.");
      },
      complete : function(xhr, status) {
        $('#content').html('');
        $('#btnBuscar').prop('disabled', false);
        $('#btnBuscarReniec').prop('disabled', false);
      }
    });

  }
  
</script>

@endsection
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-2">
      
    </div>
    <div class="col-md-8">
      <div class="row">
        <div class="panel panel-primary filterable">
          <div class="panel-body">
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#dirrehum">Consulta a DIRREHUM</a></li>
              <li><a data-toggle="tab" href="#reniec">Consulta a RENIEC</a></li>
              <li><a data-toggle="tab" href="#saludpol">Consulta a SALUDPOL</a></li>
              <li><a data-toggle="tab" href="#saludpolAPI">Consulta API SALUDPOL</a></li>
            </ul>
            <div class="tab-content">
              <div id="dirrehum" class="tab-pane fade in active">
                <div class="form-group">
                  <label class="col-md-2 control-label" for="textinput">N° DNI</label>
                  <div class="col-md-4">
                    <input id="dni" name="dni" type="text" placeholder="" class="form-control input-md" maxlength="8" required="" onkeypress="return IsNumber(event)">
                  </div>
                  <div class="col-md-2">
                    <button class="btn btn-info" type="button" name="btnBuscar" id="btnBuscar" onclick="getDirrehumByDni();">Buscar en DIRREHUM</button>
                  </div>
                </div>
              </div>
              <div id="reniec" class="tab-pane fade">
                <div class="form-group">
                  <label class="col-md-2 control-label" for="textinput">N° DNI</label>
                  <div class="col-md-4">
                    <input id="dniReniec" name="dniReniec" type="text" placeholder="" class="form-control input-md" maxlength="8" required="" onkeypress="return IsNumber(event)">
                  </div>
                  <div class="col-md-2">
                    <button class="btn btn-info" type="button" name="btnBuscarReniec" id="btnBuscarReniec" onclick="getReniecByDni();">Buscar en RENIEC</button>
                  </div>
                </div>
              </div>
              <div id="saludpol" class="tab-pane fade">
                <div class="form-group">
                  <label class="col-md-2 control-label" for="tipo_doc_saludpol" >Tipo Documento</label>
                  <div class="col-md-2">
                    <select name="tipo_doc_saludpol" id="tipo_doc_saludpol" class="form-control" onchange="maxlength_doc_usu()">
                      <option value="1">DNI</option>
                      <option value="2">CE</option>
                      <option value="3">PAS</option>
                      <option value="4">DIE</option>
                    </select>
                  </div>
                  <label class="col-md-1 control-label" for="textinput">N° </label>
                  <div class="col-md-3">
                    <input id="nroDocSaludpol" name="nroDocSaludpol" type="text" placeholder="" class="form-control input-md solomayusculas" maxlength="8" required="">
                  </div>
                  <div class="col-md-2">
                    <button class="btn btn-info" type="button" name="btnBuscarSaludpol" id="btnBuscarSaludpol" onclick="getSaludpolByTipoDocAndNroDoc();">Buscar en SALUDPOL</button>
                  </div>
                </div>
              </div>
              <div id="saludpolAPI" class="tab-pane fade">
                <div class="form-group">
                  <label class="col-md-2 control-label" for="tipo_doc_saludpolapi" >Tipo Documento</label>
                  <div class="col-md-2">
                    <select name="tipo_doc_saludpolapi" id="tipo_doc_saludpolapi" class="form-control" onchange="maxlength_doc_usu()">
                      <option value="1">DNI</option>
                      <option value="2">CE</option>
                      <option value="3">PAS</option>
                      <option value="4">DIE</option>
                    </select>
                  </div>
                  <label class="col-md-1 control-label" for="textinput">N° </label>
                  <div class="col-md-3">
                    <input id="nroDocSaludpolapi" name="nroDocSaludpolapi" type="text" placeholder="" class="form-control input-md solomayusculas" maxlength="8" required="">
                  </div>
                  <div class="col-md-2">
                    <button class="btn btn-info" type="button" name="btnBuscarapiSaludpol" id="btnBuscarapiSaludpol" onclick="getSaludpolAPIByTipoDocAndNroDoc();">Buscar en SALUDPOL</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="panel panel-primary filterable">
          <div class="panel-heading">
            <h3 class="panel-title">Datos obtenidos</h3>
            <div id="content"></div>
          </div>
          <div class="panel-body">
            <div class="panel-body">
              <div class="table-responsive">
                <div class="col-md-12"><br/>
                  <label class="col-md-4 control-label" for="apellido" >Primer Apellido</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control input-sm" readonly id="primer_apellido">
                  </div>
                </div>
                <div class="col-md-12"><br/>
                  <label class="col-md-4 control-label" for="apellido" >Segundo Apellido</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control input-sm" readonly id="segundo_apellido">
                  </div>
                </div>
                <div class="col-md-12"><br/>
                  <label class="col-md-4 control-label" for="apellido" >Nombres</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control input-sm" readonly id="nombres">
                  </div>
                </div>
                <div class="col-md-12"><br/>
                  <label class="col-md-4 control-label" for="apellido" >DNI</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control input-sm" readonly id="dni_titular">
                  </div>
                </div>
                <div class="col-md-12"><br/>
                  <label class="col-md-4 control-label" for="apellido" >Sexo</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control input-sm" readonly id="sexo">
                  </div>
                </div>
                <div class="col-md-12"><br/>
                  <label class="col-md-4 control-label" for="apellido" >Fecha Nacimiento</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control input-sm" readonly id="fecha_nacimiento">
                  </div>
                </div>
                <div id="divdirrehum_saludpol">
                  <div class="col-md-12"><br/>
                    <label class="col-md-4 control-label" for="apellido" >Estado Civil</label>
                    <div class="col-md-8">
                      <input type="text" class="form-control input-sm" readonly id="estado_civil">
                    </div>
                  </div>
                  <div class="col-md-12"><br/>
                    <label class="col-md-4 control-label" for="apellido" >Carnet</label>
                    <div class="col-md-8">
                      <input type="text" class="form-control input-sm" readonly id="carnet">
                    </div>
                  </div>
                  <div class="col-md-12"><br/>
                    <label class="col-md-4 control-label" for="apellido" >Grado</label>
                    <div class="col-md-8">
                      <input type="text" class="form-control input-sm" readonly id="grado">
                    </div>
                  </div>
                  <div class="col-md-12"><br/>
                    <label class="col-md-4 control-label" for="apellido" >Situación</label>
                    <div class="col-md-8">
                      <input type="text" class="form-control input-sm" readonly id="situacion">
                    </div>
                  </div>
                </div>
                <div id="divdirrehum">
                  <div class="col-md-12"><br/>
                    <label class="col-md-4 control-label" for="apellido" >Tipo de Sangre</label>
                    <div class="col-md-8">
                      <input type="text" class="form-control input-sm" readonly id="tipo_sangre">
                    </div>
                  </div>
                  <div class="col-md-12"><br/>
                    <label class="col-md-4 control-label" for="apellido" >Unidad</label>
                    <div class="col-md-8">
                      <textarea name="unidad" id="unidad" cols="30" rows="2" class="form-control input-sm" readonly></textarea>
                    </div>
                  </div>
                <!--  <div class="col-md-12"><br/>
                    <label class="col-md-4 control-label" for="apellido" >Situación Especial</label>
                    <div class="col-md-8">
                      <input type="text" class="form-control input-sm" readonly id="situacion_especial">
                    </div>
                  </div> -->
                  <div class="col-md-12"><br/>
                    <label class="col-md-4 control-label" for="apellido" >Fecha Retiro</label>
                    <div class="col-md-8">
                      <input type="text" class="form-control input-sm" readonly id="fecha_retiro">
                    </div>
                  </div>
                </div>
                @if($id_usuario == 2327  || $id_usuario == 115 || $id_usuario == 3263 || $id_usuario == 2597 || $id_usuario == 3068)
                <!--<div class="col-md-12"><br/>
                  <label class="col-md-4 control-label" for="apellido" >Fecha Caducidad</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control input-sm" readonly id="fecha_caducidad">
                  </div>
                </div>  -->
                <div id="divreniec">
                  <div class="col-md-12"><br/>
                    <label class="col-md-4 control-label" for="apellido" >Fecha Emisión</label>
                    <div class="col-md-8">
                      <input type="text" class="form-control input-sm" readonly id="fecha_emision">
                    </div>
                  </div>
                  <div class="col-md-12"><br/>
                    <div class="col-md-4"><label>Departamento/Provincia/Distrito Nacimiento</label></div>
                    <div class="col-md-8">
                      <input type="text" class="form-control input-sm" readonly id="distrito_nacimiento">
                    </div>
                  </div>
                  <div class="col-md-12"><br/>
                    <div class="col-md-4"><label>Departamento/Provincia/Distrito Domicilio</label></div>
                    <div class="col-md-8">
                      <input type="text" class="form-control input-sm" readonly id="distrito_domicilio">
                    </div>
                  </div>
                  <div class="col-md-12"><br/>
                    <div class="col-md-4"><label>Localidad Domicilio</label></div>
                    <div class="col-md-8">
                      <input type="text" class="form-control input-sm" readonly id="localidad_domicilio">
                    </div>
                  </div>
                  <div class="col-md-12"><br/>
                    <div class="col-md-4"><label>Dirección Domicilio</label></div>
                    <div class="col-md-8">
                      <input type="text" class="form-control input-sm" readonly id="direccion_domicilio">
                    </div>
                  </div>
                  @if ($permiso->crear == 1)
                  <!--div class="col-md-12">
                    <br>
                    <div class="col-md-4"><label>Foto Reniec</label></div>
                    <div class="col-md-8" id="foto-reniec"></div>
                  </div-->
                  @endif
                </div>
              </div>
              
                @endif
                <!--table class="table table-condensed">
                  <tr>
                    <td width="150px"><b>Primer Apellido</b></td>
                    <td><input type="text" class="form-control input-sm" readonly id="primer_apellido"></td>
                  </tr>
                  <tr>
                    <td><b>Segundo Apellido</b></td>
                    <td><input type="text" class="form-control input-sm" readonly id="segundo_apellido"></td>
                  </tr>
                  <tr>
                    <td><b>Nombres</b></td>
                    <td><input type="text" class="form-control input-sm" readonly id="nombres"></td>
                  </tr>
                  <tr>
                    <td><b>DNI</b></td>
                    <td><input type="text" class="form-control input-sm" readonly id="dni_titular"></td>
                  </tr>
                  <tr>
                    <td><b>Estado Civil</b></td>
                    <td><input type="text" class="form-control input-sm" readonly id="estado_civil"></td>
                  </tr>
                  <tr>
                    <td><b>Sexo</b></td>
                    <td><input type="text" class="form-control input-sm" readonly id="sexo"></td>
                  </tr>
                  <tr>
                    <td><b>Fecha Nacimiento</b></td>
                    <td><input type="text" class="form-control input-sm" readonly id="fecha_nacimiento"></td>
                  </tr>
                  <tr>
                  <tr>
                    <td><b>Carnet</b></td>
                    <td><input type="text" class="form-control input-sm" readonly id="carnet"></td>
                  </tr>
                  <tr>
                    <td><b>Grado</b></td>
                    <td><input type="text" class="form-control input-sm" readonly id="grado"></td>
                  </tr>
                  <tr>
                    <td><b>Situación</b></td>
                    <td><input type="text" class="form-control input-sm" readonly id="situacion"></td>
                  </tr>
                  <td><b>Tipo de Sangre</b></td>
                    <td><input type="text" class="form-control input-sm" readonly id="tipo_sangre"></td>
                  </tr>
                  <tr>
                    <td><b>Unidad</b></td>
                    <td><textarea name="unidad" id="unidad" cols="30" rows="2" class="form-control input-sm" readonly></textarea></td>
                  </tr>
                  <tr>
                    <td><b>Situación Especial</b></td>
                    <td><input type="text" class="form-control input-sm" readonly id="situacion_especial"></td>
                  </tr>
                  <tr>
                    <td><b>Fecha Retiro</b></td>
                    <td><input type="text" class="form-control input-sm" readonly id="fecha_retiro"></td>
                  </tr>
                  @if($id_usuario == 2324 || $id_usuario == 2327 || $id_usuario == 115 || $id_usuario == 2597 || $id_usuario == 3068)
                  <tr>
                    <td><b>Fecha Emisión</b></td>
                    <td><input type="text" class="form-control input-sm" readonly id="fecha_emision"></td>
                  </tr>
                  <tr>
                    <td><b>Fecha Caducidad</b></td>
                    <td><input type="text" class="form-control input-sm" readonly id="fecha_caducidad"></td>
                  </tr>
                  <tr>
                    <td><b>Departamento/Provincia/Distrito Nacimiento</b></td>
                    <td><input type="text" class="form-control input-sm" readonly id="distrito_nacimiento"></td>
                  </tr>
                  <tr>
                    <td><b>Departamento/Provincia/Distrito Domicilio</b></td>
                    <td><input type="text" class="form-control input-sm" readonly id="distrito_domicilio"></td>
                  </tr>
                  <tr>
                    <td><b>Localidad Domicilio</b></td>
                    <td><input type="text" class="form-control input-sm" readonly id="localidad_domicilio"></td>
                  </tr>
                  <tr>
                    <td><b>Dirección Domicilio</b></td>
                    <td><input type="text" class="form-control input-sm" readonly id="direccion_domicilio"></td>
                  </tr>
                  @endif
                </table-->
                <div id="divapisaludpol">
                  <div class="col-md-12"><br/>
                    <label class="col-md-4 control-label" for="apellido" >Estado Civil</label>
                    <div class="col-md-8">
                      <input type="text" class="form-control input-sm" readonly id="estado_civil_api">
                    </div>
                  </div>
                  <div class="col-md-12"><br/>
                    <label class="col-md-4 control-label" for="apellido" >Discapacidad</label>
                    <div class="col-md-8">
                      <input type="text" class="form-control input-sm" readonly id="discapacidad_api">
                    </div>
                  </div>
                  <div class="col-md-12"><br/>
                    <label class="col-md-4 control-label" for="apellido" >Grado</label>
                    <div class="col-md-8">
                      <input type="text" class="form-control input-sm" readonly id="grado_api">
                    </div>
                  </div>
                  <div class="col-md-12"><br/>
                    <label class="col-md-4 control-label" for="apellido" >Situación</label>
                    <div class="col-md-8">
                      <input type="text" class="form-control input-sm" readonly id="situacion_api">
                    </div>
                  </div>
                  <div class="col-md-12"><br/>
                    <label class="col-md-4 control-label" for="apellido" >Condición</label>
                    <div class="col-md-8">
                      <input type="text" class="form-control input-sm" readonly id="condicion_api">
                    </div>
                  </div>
                  <div class="col-md-12"><br/>
                    <label class="col-md-4 control-label" for="apellido" >Fecha inicio Afiliación</label>
                    <div class="col-md-8">
                      <input type="text" class="form-control input-sm" readonly id="ini_afi_api">
                    </div>
                  </div>
                  <div class="col-md-12"><br/>
                    <label class="col-md-4 control-label" for="apellido" >Fecha fin Afiliación</label>
                    <div class="col-md-8">
                      <input type="text" class="form-control input-sm" readonly id="fin_afi_api">
                    </div>
                  </div>
                  <div class="col-md-12"><br/>
                    <label class="col-md-4 control-label" for="apellido" >Estado Afiliación</label>
                    <div class="col-md-8">
                      <input type="text" class="form-control input-sm" readonly id="estado_afi_api">
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
  @include('frontend.includes.footer')
  @endsection