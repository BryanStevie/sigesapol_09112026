@extends('frontend.layouts.master')
@section('renderjs')

<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<style>
  .ui-autocomplete {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 10000000;
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

  .ui-autocomplete>li>div {
    display: block;
    padding: 3px 20px;
    clear: both;
    font-weight: normal;
    line-height: 1.42857143;
    color: #333333;
    white-space: nowrap;
  }
  .ui-state-hover, .ui-state-active, .ui-state-focus {
      text-decoration: none;
      color: #FFFFFF !important;
      background: #1d71b8 !important;
      cursor: pointer;
  }
</style>

<style>
  .modal-header, h4, .close{
    font-size: 18px !important;
  }

  .panel-heading div {
    margin-top: auto !important;
  }

  .table-sortable tbody tr {
    cursor: move;
  }

  #global {
    min-height: 650px !important;
    margin: 15px;
  }

  .margin {

    margin-bottom: 20px;
  }

  .margin-buscar {
    margin-bottom: 5px;
    margin-top: 5px;
  }

  .row {
    margin-top: 10px;
    padding: 0 10px;
  }

  .clickable {
    cursor: pointer;
  }

  .panel-heading div {
    margin-top: -18px;
    font-size: 15px;
  }

  .panel-heading div span {
    margin-left: 5px;
  }

  .panel-body {
    display: block;
  }

  .dataTables_filter {
    display: none;
  }

  .help-block {
    display: block;
    margin-top: 1px;
    margin-bottom: 10px;
    color: #737373;
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

  .custom-form-group {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
  }

  .custom-form-group label {
    width: 100px; /* Ajusta el ancho según sea necesario */
    margin-bottom: 0;
  }

  .custom-form-group input {
    flex: 1;
  }

  .form-control.cant-input-sm{
    width: 65px;
    margin-left: -180%;
    height: 26px;
 }

  .cant{
    margin-left:16.5%;
    font-size: 17px;
  }

  .titulo-modal{
    margin-left: -76%;
  }
  .panel-heading2 {
    padding: 15px ;
    color: #3c763d;
    background-color: #e0e9e0;
    border-color: #e0e9e0;
  }

    .tooltip-inner {
        background-color: #ffffff; /* Color de fondo personalizado */
        color: #000000; /* Color del texto */
        border: 2px solid #3c763d; /* Borde opcional */
        width: 100% !important;
    }

    .item-tooltip {
        width: 100%;
        text-align: left;
    }
  /**********Imagenes Inicio**********/
  .btn-ready {
      position: relative;
      overflow: hidden;
      transition: all 0.3s ease;
  }
  
  .btn-ready:focus, .btn-ready:hover {
      box-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
  }

  .btn-ready:before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 300%;
      height: 300%;
      background: rgba(255, 255, 255, 0.2);
      transition: all 0.6s ease;
      border-radius: 50%;
      transform: translate(-50%, -50%) scale(0);
  }

  .btn-ready:hover:before {
      transform: translate(-50%, -50%) scale(1);
  }

  .pdfView {
    margin: auto !important;
    display: block !important;
    width: 850px !important;
    height: 100vh !important;
    border-radius: 20px !important;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19)
  }
  /**Imagenes Fin**/
  .smaller-swal {
    width: 300px !important; /* Ajusta el ancho */
    font-size: 14px !important; /* Ajusta el tamaño de la fuente */
  }
  .smaller-swal .swal2-title {
      font-size: 18px !important; /* Tamaño de la fuente del título */
  }
  .smaller-swal .swal2-icon {
      font-size: 24px !important; /* Ajusta el tamaño del ícono */
  }

  .swal2-warning {
    font-size: 1rem !important;
  }
  
  .interconsultas {
    display: flex;
    gap: 4px;
  }

  /** Modal responsive **/
  @media (max-width: 950px) {
      #modalAnestesia .modal-dialog {
          width: 90% !important; /* Ajusta el ancho del modal en dispositivos pequeños */
          margin: 10px auto !important; /* Centra el modal */
      }
  }

  @media (min-width: 950px) {
        #modalAnestesia .modal-dialog {
            width: 60% !important; /* Ajusta el ancho del modal en dispositivos medianos o mayores */
        }
    }
  
</style>
<script src="{{URL::to('/')}}/js/js.util.grid.js"></script>
@include('frontend.centro_quirurgico.firma_cqx_handler')

<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>
<script>
  function capitalizarPrimeraLetra(texto) {
    texto = texto.toLowerCase(); // Convierte todo a minúsculas
    return texto.charAt(0).toUpperCase() + texto.slice(1);
  }

  $(document).ready(function() {

    $('#btnBuscar').click(function() {
      fn_ListarBusqueda();
    });

    $('#btnSuspender').on("click", function (e) {
      let msg = "";
		  if (msg != "") {
        return false;
      } else {
        bootbox.confirm("¿Está seguro de Suspender esta Intervención?", function(result) {
          if (result) {
            // Acción cuando se confirma
            $("#frmSuspenderIntervencion").submit();
          }
        });
      }
	  });
    
    $("#id_servicio").select2({
	    width: 'resolve'
	  });
    $("#id_medico").select2({
	    width: 'resolve'
	  });

    //fn_ListarBusqueda();
    // console.log("ready2");
  });

  function fn_ListarBusqueda() {
    datatablenew();
  };

  function datatablenew() {
    let id_user = $('#id_user').val();
    var oTable1 = $('#tblIntervencionQx').dataTable({
    "bServerSide": true,
    "sAjaxSource": "/listar_mopri_nivel2_ajax",
    "bProcessing": false,
    "sPaginationType": "full_numbers",
    //"paging":false,
    "bFilter": false,
    "bSort": false,
    "info": true,
    //"responsive": true,
    "language": {
      "url": "/js/Spanish.json"
    },
    "autoWidth": false,
    "bLengthChange": true,
    "destroy": true,
    "lengthMenu": [
      [10, 50, 100, 200, 60000],
      [10, 50, 100, 200, "Todos"]
    ],
    "aoColumns": [{}, ],
    "dom": '<"top">rt<"bottom"flpi><"clear">',
    "fnDrawCallback": function(json) {
      $('[data-toggle="popover"]').popover(
        {
          container: 'body'
        }
      );
    },
    // Se muestra el spinner antes de que DataTables haga la petición AJAX
    "fnPreDrawCallback": function () {
        $("#spinnerOverlay").fadeIn(200);
    },

    // Cuando DataTables termina de renderizar, ocultamos el spinner
    "fnDrawCallback": function () {
        $("#spinnerOverlay").fadeOut(200);

        // Inicializamos popovers después de cargar la data
        $('[data-toggle="popover"]').popover({ container: 'body' });
    },

    "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

      var sEcho = aoData[0].value;
      var iNroPagina = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
      var iCantMostrar = aoData[4].value;

      var fecha_ini = $('#fecha_ini').val();
      var fecha_fin = $('#fecha_fin').val();
      var fecha_ini_intervencion = $('#fecha_ini_intervencion').val();
      var fecha_fin_intervencion = $('#fecha_fin_intervencion').val();
      var dni_beneficiario = $('#dni_beneficiario').val();
      var nombre_beneficiario = $('#nombre_beneficiario').val();
      var estado = $('#estado').val();
      var id_servicio = $('#id_servicio').val();
      var id_tipo_atencion = $('#id_tipo_atencion').val();
      var id_medico = $('#id_medico').length && $('#id_medico').val().trim() !== ''? parseInt($('#id_medico').val(), 10): null;
      var id_estado_pendiente = $('#estado_pendiente').val();
      var _token = $('#_token').val();
      oSettings.jqXHR = $.ajax({
        "dataType": 'json',
        //"contentType": "application/json; charset=utf-8",
        "type": "POST",
        "url": sSource,
        "data": {
          NumeroPagina: iNroPagina,
          NumeroRegistros: iCantMostrar,
          fecha_ini: fecha_ini,
          fecha_fin: fecha_fin,
          fecha_ini_intervencion: fecha_ini_intervencion,
          fecha_fin_intervencion: fecha_fin_intervencion,
          dni_beneficiario: dni_beneficiario,
          nombre_beneficiario: nombre_beneficiario,
          estado: estado,
          id_servicio: id_servicio, 
          id_tipo_atencion: id_tipo_atencion,
          id_medico: id_medico,
          id_estado_pendiente: id_estado_pendiente,
          _token: _token
        },
        "beforeSend": function() {
          $("#spinnerOverlay").show();
        },
        "success": function(result) {
          $("#spinnerOverlay").hide();
          fnCallback(result);
        },
        "error": function(msg, textStatus, errorThrown) {
          $("#spinnerOverlay").hide();
          // bootbox.alert("Error: " + msg);
        }
      });
    },

    "aoColumnDefs": [{
        "mRender": function(data, type, row) {
          var id = "";
          if (row.id_intervencion != null) id = row.id_intervencion;
          return id;
        },
        "bSortable": false,
        "aTargets": [0]
      },
      {
        "mRender": function(data, type, row) {
          //Tipo de Atencion Origen
          return row.nombre_tipo_atencion;
        },
        "bSortable": false,
        "aTargets": [1]
      },
      {
        "mRender": function(data, type, row) {
          // var fecha_atencion = "";
          // if (row.fecha_atencion != null) fecha_atencion = row.fecha_atencion;
          // return fecha_atencion;
          let fecha_solicitud = row.fecha_atencion ? row.fecha_atencion : '';
          let medico_solicitante = row.medico_solicitante ? row.medico_solicitante.toUpperCase() : '';
          if (!fecha_solicitud && !medico_solicitante) {
              return '';
          }
          return `
              ${fecha_solicitud ? `<b>F. DE SOLICITUD:</b> ${fecha_solicitud}<br>` : ''}
              ${medico_solicitante ? `<b>MÉDICO:</b> ${medico_solicitante}` : ''}
          `;
        },
        "bSortable": false,
        "aTargets": [2]
      },
      {
        "mRender": function(data, type, row) {
          let fecha_probable_intervencion = row.fecha_probable ? row.fecha_probable : '';
          let medico_programador = row.medico_programador ? row.medico_programador.toUpperCase() : '';
          let turno_quirurgico = row.id_prioridad_programacion ? row.id_prioridad_programacion : '';
          
          if (!fecha_probable_intervencion && !medico_programador) {
              return '';
          }
          return `
              ${fecha_probable_intervencion ? `<b>F. DE PROGRAMACIÓN:</b> ${fecha_probable_intervencion}<br>` : ''}
              ${medico_programador ? `<b>MÉDICO:</b> ${medico_programador}` : ''}
              ${turno_quirurgico ? `<b>TURNO PROG:</b> ${turno_quirurgico}` : ''}
          `;
        },
        "bSortable": false,
        "aTargets": [3]
      },
      {
        "mRender": function(data, type, row) {
            let html = "";
            let datos_adicionales = "";
          var asegurado = "";
          if (row.asegurado_paterno != null) asegurado += row.asegurado_paterno;
          if (row.asegurado_materno != null) asegurado += " " + row.asegurado_materno;
          if (row.asegurado_nombre != null) asegurado += ", " + row.asegurado_nombre;
           datos_adicionales += "<b>Nombres :</b> " + asegurado + "<br>" +
                  "<b>DNI: </b>" + row.nro_doc_ident + " <br>" +
                  "<b>Telefono 1 : </b>" + (row.telefono ? row.telefono : '')+"<br>"+
                  "<b>Telefono 2 : </b>" + (row.telefono2 ? row.telefono2 : '');

              
                      
          return html;
        },
        "bSortable": false,
        "aTargets": [4]
      },
      {
        "mRender": function(data, type, row) {
          //Especialidad Quirurgica
          let servicio = "";
          let clasificacion = "";
          if (row.nombre_servicio != null && row.nombre_servicio !== "")
            servicio = "<b>ESPECIALIDAD:</b> " + row.nombre_servicio;

          if (row.tipo_cirugia != null && row.tipo_cirugia !== "")
            clasificacion = "<b>TIPO DE CIRUGÍA: </b>" + row.tipo_cirugia;

          let detalleCirugia = servicio;
          if (clasificacion !== "") {
            if (detalleCirugia !== "") detalleCirugia += "<br>";
            detalleCirugia += clasificacion;
          }

          return detalleCirugia;
        },
        "bSortable": false,
        "aTargets": [5]
      },
      {
        "mRender": function(data, type, row) {
          //Procedimiento
          let html = "";

          if (row.id_estado_reg == 1) {
            if (row.cpms_intervencion != null) {
              // Elimina los caracteres de las llaves y las comillas
              let diag_intervencion = row.diagnosticos_intervencion?row.diagnosticos_intervencion.replace("{", "").replace("}", "").replace(/"/g, ""): "";
              let cpms_intervencion = row.cpms_intervencion.replace("{", "").replace("}", "").replace(/"/g, "");
              
              // Divide las tuplas en un array
              let diagnosticos_array = diag_intervencion.split("),(");
              let cpms_array = cpms_intervencion.split("),(");
              // DIAGNOSTICOS CQX
              if (Array.isArray(diagnosticos_array) && diagnosticos_array.length > 0 && diagnosticos_array[0]) {
                for (let i = 0; i < diagnosticos_array.length; i++) {
                  let diag = diagnosticos_array[i].replace("(", "").replace(")", "");
                  let codigo = diag.split(",")[0];
                  let descripcion = diag.split(",")[1];

                  descripcion = String(descripcion).replaceAll("|", ",").replaceAll("\\", "");
                  html += '<table><tr>';
                  html +=
                    '<td>' +
                      '<span ' +
                        'data-toggle="popover" ' +
                        'data-trigger="hover" ' +
                        'data-placement="top" ' +
                        'data-html="true" ' +
                        'title="<strong>DIAGNOSTICO:'+codigo+'</strong>" ' +
                        'data-content="'+descripcion+'">' +
                        'DIAGNOSTICO: '+codigo+
                      '</span>' +
                    '</td>';
                  html += '</tr></table>';
                  if ((i+1) < diagnosticos_array.length) {
                    html += "<br>";
                  }
                }
              }
              html += "<br><br>";
              //PROCEDIMIENTOS CQX
                if (cpms_array.length > 0) {
                  let codigos = [];
                  let descripciones = [];

                  for (let i = 0; i < cpms_array.length; i++) {
                      let cpms = cpms_array[i].replace("(", "").replace(")", "");
                      let codigo = cpms.split(",")[0];
                      let descripcion = cpms.split(",")[1];

                      descripcion = descripcion.replaceAll("|", ",").replaceAll("\\", "");

                      codigos.push(codigo);
                      descripciones.push('<strong>' + codigo + '</strong>: ' + descripcion);
                  }

                  let codigos_str = codigos.join(", ");
                  let descripciones_html = descripciones.join("<br>");

                  html += '<table><tr><td>';
                  html += '<span ' +
                            'data-toggle="popover" ' +
                            'data-trigger="hover" ' +
                            'data-placement="top" ' +
                            'data-html="true" ' +
                            'title="<strong>DETALLE DE PROCEDIMIENTOS</strong>" ' +
                            'data-content="'+descripciones_html+'">' +
                            'PROCEDIMIENTO: ' + codigos_str +
                          '</span>';
                  html += '</td></tr></table>';
                }
            }
          }else{
            let diag_intervencion = row.diagnosticos_intervencion?row.diagnosticos_intervencion.replace("{", "").replace("}", "").replace(/"/g, ""): "";
            // Divide las tuplas en un array
            let diagnosticos_array = diag_intervencion.split("),(");
            // DIAGNOSTICOS CQX
            if (Array.isArray(diagnosticos_array) && diagnosticos_array.length > 0 && diagnosticos_array[0]) {
              for (let i = 0; i < diagnosticos_array.length; i++) {
                let diag = diagnosticos_array[i].replace("(", "").replace(")", "");
                let codigo = diag.split(",")[0];
                let descripcion = diag.split(",")[1];

                descripcion = String(descripcion).replaceAll("|", ",").replaceAll("\\", "");
                html += '<table><tr>';
                html +=
                  '<td>' +
                    '<span ' +
                      'data-toggle="popover" ' +
                      'data-trigger="hover" ' +
                      'data-placement="top" ' +
                      'data-html="true" ' +
                      'title="<strong>DIAGNOSTICO:'+codigo+'</strong>" ' +
                      'data-content="'+descripcion+'">' +
                      'DIAGNOSTICO: '+codigo+
                    '</span>' +
                  '</td>';
                html += '</tr></table>';
                if ((i+1) < diagnosticos_array.length) {
                  html += "<br>";
                }
              }
            }
          }

          return html;
        },
        "bSortable": false,
        "aTargets": [6]
      },
      {
        "mRender": function(data, type, row) {
          //Fecha  Hora
          return "";
        },
        "bSortable": false,
        "aTargets": [7],
        "visible": false
      },
      {
        "mRender": function(data, type, row) {
          //Duración
          return "";
        },
        "bSortable": false,
        "aTargets": [8],
        "visible": false
      },
      {
        "mRender": function(data, type, row) {
          let html = "";
          let estado = $('#estado').val();
          if (row.flag_intervencion == 1 && estado == 1) {
            html += "Pendiente";
          } else if (row.flag_intervencion == 2) {
            html += "Programado";
          } else if (row.flag_intervencion == 3) {
            html += "Validado";
          } else if (row.flag_intervencion == 4) {
            html += "Alta";
          } else if (row.flag_intervencion == 1 && estado == 5) {
            html += "Suspendido";
          }

          return html;
        },
        "bSortable": false,
        "aTargets": [9]
      },
      {
        "mRender": function(data, type, row) {
          var html = "";
          let id_servicio = 0;
          let id_cita = 0;
          let id_prestacion = 0;
          let flag = 0;

          if (row.id_estado_reg == 1) {
            html += '<table><tr>';
           
            // MOPRI
              if (row.id_tipo_atencion==1) {
                // if(row.id_hospitalizacion==null){
                //   html +=
                //   '<td style="padding-left: 5px;">' +
                //   '<span ' +
                //     'data-toggle="popover" ' +
                //     'data-trigger="hover" ' +
                //     'data-placement="top" ' +
                //     'data-html="true" ' +
                //     'title="<strong>Información del MOPRI no disponible</strong>" ' +
                //     'data-content="El paciente tiene que pasar por Hospitalización">' +
                //     '<a href="javascript:void(0)" class="btn btn-primary btn-xs disabled">' +
                //       '<i class="fa fa-download"></i>' +
                //     '</a>' +
                //   '</span>'+
                //   '</td>';
                // }else{
                  html +=
                  '<td style="padding-left: 5px;">' +
                    '<span ' +
                      'data-toggle="popover" ' +
                      'data-trigger="hover" ' +
                      'data-placement="top" ' +
                      'data-html="true" ' +
                      'title="<strong>Ver MOPRI</strong>" ' +
                      'data-content="Haz clic para ver el contenido del formato MOPRI">' +
                      `<a href="/print_mopri_cqx/${row.id_intervencion}" ` +
                        'target="_blank" ' +
                        'class="btn btn-success btn-xs">' +
                        '<i class="fa fa-download"></i>' +
                      '</a>' +
                    '</span>' +
                  '</td>';
               // }
            } else {
              html +=
                '<td style="padding-left: 5px;">' +
                '<span ' +
                  'data-toggle="popover" ' +
                  'data-trigger="hover" ' +
                  'data-placement="top" ' +
                  'data-html="true" ' +
                  'title="<strong>Información del MOPRI no disponible</strong>" ' +
                  'data-content="Solo para consulta externa">' +
                  '<a href="javascript:void(0)" class="btn btn-primary btn-xs disabled">' +
                    '<i class="fa fa-download"></i>' +
                  '</a>' +
                '</span>'+
                '</td>';
            }
                       
            html += '</tr></table>';
            
          } else {
            html += '<button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></a></button>';
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [10],
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          /**LABORATORIO**/
          var html = "";
          if (row.laboratorios != null && row.id_estado_reg == 1) {
            let laboratorios = row.laboratorios.replace("{", "").replace("}", "").replace(/"/g, "");

              let laboratorios_array = laboratorios.split("),(");
              
              for (let i = 0; i < laboratorios_array.length; i++) {
                let laboratorio = laboratorios_array[i].replace("(", "").replace(")", "");
                
                let id_laboratorio = laboratorio.split(",")[0];
                let estado_laboratorio = laboratorio.split(",")[1];
                let fecha_toma_muestra = laboratorio.split(",")[2];
                let orden_laboratorio = laboratorio.split(",")[3];
                let flag_laboratorio = laboratorio.split(",")[4];
                let cita_laboratorio = laboratorio.split(",")[5];
                let ruta_laboratorio = laboratorio.split(",")[6];
                const examenesLaboratorio = encodeURIComponent(row.descripcion_corta || '');
                
                if (id_laboratorio != "NULL") {
                  if (cita_laboratorio == null || cita_laboratorio == '') {
                    html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="Nuevo" style="float:left;padding-left:3px">';
                    html +='<button class="btn btn-default btn-xs" ><i class="fa fa-eye"></i></button></div>';
                  } else {

                    if ((fecha_toma_muestra !== null || fecha_toma_muestra !== '') ) {
                       // orden_laboratorio = orden_laboratorio.replace(/[{}]/g, '');

                        // let orden_laboratorio_array = orden_laboratorio.split('","');

                        // orden_laboratorio_array = orden_laboratorio_array.map(function(item) {
                        //     return item.replace(/"/g, '');
                        // });

                        //console.log(orden_laboratorio_array);
                        //======Color verde - cuando genera la orden de apoyo al diagnostico y se registra la cita y se atiende
                        html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="Orden Atendida" style="float:left;padding-left:3px">';
                        html +=`<button class="btn btn-primary btn-xs btn-modal-laboratorio" data-id-laboratorio="${id_laboratorio}" data-nro-doc="${row.nro_doc_ident}" data-fecha-toma-muestra="${fecha_toma_muestra}" data-cod-orden-lab="${orden_laboratorio}" data-ruta_laboratorio="${ruta_laboratorio}" data-examenes-laboratorio="${examenesLaboratorio}" ><i class="fa fa-eye"></i></button></div>`;
                    } else {
                      //======Color amarrillo - cuando genera la orden de apoyo al diagnostico y se registra la cita
                      html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="Orden Pendiente" style="float:left;padding-left:3px">';
                      html +='<button class="btn btn-warning btn-xs" ><i class="fa fa-eye"></i></button></div>';
                    }

                  }
                }
              }

            // var ruta_laboratorio = row.ruta_laboratorio.replace("{", "").replace("}", "");
            // var ruta = ruta_laboratorio.split(",");
            // let flag_laboratorio = row.flag_laboratorio.replace("{", "").replace("}", "");
            // let flag_firma = flag_laboratorio.split(",");
            // let orden_laboratorio = row.orden_laboratorio;

            // for (var r = 0; r < ruta.length; r++) {
            //   if (ruta[r] != "NULL") {

            //     if (row.id_cita_laboratorio == null || row.id_cita_laboratorio == '') {
            //       //======Color default - cuando genera la orden de apoyo al diagnostico
            //       html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="Nuevo" style="float:left;padding-left:3px">';
            //       html +='<button class="btn btn-default btn-xs" ><i class="fa fa-eye"></i></button></div>';
            //     } else {

            //       if ((row.fecha_toma_muestra !== null || row.fecha_toma_muestra !== '') ) {
            //           orden_laboratorio = orden_laboratorio.replace(/[{}]/g, '');

            //           let orden_laboratorio_array = orden_laboratorio.split('","');

            //           orden_laboratorio_array = orden_laboratorio_array.map(function(item) {
            //               return item.replace(/"/g, '');
            //           });

            //           console.log(orden_laboratorio_array);
            //           //======Color verde - cuando genera la orden de apoyo al diagnostico y se registra la cita y se atiende
            //           html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="Orden Atendida" style="float:left;padding-left:3px">';
            //           html +=`<button class="btn btn-primary btn-xs btn-modal-laboratorio" data-id-laboratorio="${row.id_laboratorio}" data-nro-doc="${row.nro_doc_ident}" data-fecha-toma-muestra="${row.fecha_toma_muestra}" data-cod-orden-lab="${row.cod_orden_lab}" data-orden-laboratorio-array="${orden_laboratorio_array}"><i class="fa fa-eye"></i></button></div>`;
            //       } else {
            //         //======Color amarrillo - cuando genera la orden de apoyo al diagnostico y se registra la cita
            //         html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="Orden Pendiente" style="float:left;padding-left:3px">';
            //         html +='<button class="btn btn-warning btn-xs" ><i class="fa fa-eye"></i></button></div>';
            //       }

            //     }

            //   }
            // }
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [11],
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          /**IMAGENES**/
          var html = "";
          if (row.ids_radiologias != null) {
            /***************************************************************/
              let radiologias = row.ids_radiologias.replace("{", "").replace("}", "").replace(/"/g, "");
              let radiologias_array = radiologias.split("),(");
              const examenesRadiologias = encodeURIComponent(row.radiologias || '');
              
              for (let i = 0; i < radiologias_array.length; i++) {
                let radio = radiologias_array[i].replace("(", "").replace(")", "");
                
                let id_radiologia = radio.split(",")[0];
                //let citas_imagenes = row.citas_imagenes;
                html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver examenes" style="float:left;padding-left:3px">';
                        html +=`<button class="btn btn-primary btn-xs btn-modal-imagenes" data-id-radiologia="${id_radiologia}" data-examenes-array="${examenesRadiologias}"><i class="fa fa-eye"></i></button></div>`;
                //if (ruta_radiologia!= "NULL") {
                  // if (citas_imagenes == null || citas_imagenes == '') {
                  //   //======Color default - cuando genera la orden de apoyo al diagnostico
                  //   html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="Nuevo" style="float:left;padding-left:3px;">';
                  //   html +='<button class="btn btn-default btn-xs" ><i class="fa fa-eye"></i></button></div>';
                  // } else {
                    // citas_imagenes = citas_imagenes.replace(/[{}]/g, '');

                    // let citas_imagenes_array = citas_imagenes.split('","');

                    // citas_imagenes_array = citas_imagenes_array.map(function(item) {
                    //     return item.replace(/"/g, '');
                    // });
                    
                    // let iestado = '';
                    // for (let item of citas_imagenes_array) {

                    //   let cleanItem = item.replace(/[()]/g, '');
                    //   let values = cleanItem.split(',');

                    //   let id_cita_radiologia = values[0];
                    //   let id_pp = values[1];
                    //   let id_estado_cita = values[2];

                    //   if (id_estado_cita == 4) {
                    //     iestado = 4;
                    //     break;
                    //   }
                    // }
                    // if (iestado == 4) {
                    //     //======Color verde - cuando genera la orden de apoyo al diagnostico y se registra la cita y se atiende
                    //     html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="Orden Atendida" style="float:left;padding-left:3px">';
                    //     html +=`<button class="btn btn-primary btn-xs btn-modal-imagenes" data-id-radiologia="${id_radiologia}" data-examenes-array="${citas_imagenes_array}"><i class="fa fa-eye"></i></button></div>`;
                    // } else {
                    //   //======Color amarrillo - cuando genera la orden de apoyo al diagnostico y se registra la cita
                    //   html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="Orden Pendiente" style="float:left;padding-left:3px">';
                    //   html +='<button class="btn btn-warning btn-xs" ><i class="fa fa-eye"></i></button></div>';
                    // }
                  //} 
                //}
              }
            /***********************************************************/

            // var ruta_radiologia = row.ruta_radiologia.replace("{", "").replace("}", "");
            // var ruta = ruta_radiologia.split(",");
            // let flag_radiologia = row.flag_radiologia.replace("{", "").replace("}", "");
            // let flag_firma = flag_radiologia.split(",");
            // let citas_imagenes = row.citas_imagenes;
            // for (var r = 0; r < ruta.length; r++) {
            //   if (ruta[r] != "NULL") {
            //     if (citas_imagenes == null || citas_imagenes == '') {
            //       //======Color default - cuando genera la orden de apoyo al diagnostico
            //       html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="Nuevo" style="float:left;padding-left:3px;">';
            //       html +='<button class="btn btn-default btn-xs" ><i class="fa fa-eye"></i></button></div>';
            //     } else {
            //       citas_imagenes = citas_imagenes.replace(/[{}]/g, '');

            //       let citas_imagenes_array = citas_imagenes.split('","');

            //       citas_imagenes_array = citas_imagenes_array.map(function(item) {
            //           return item.replace(/"/g, '');
            //       });
                  
            //       console.log(citas_imagenes_array);
                  
            //       let iestado = '';
            //       for (let item of citas_imagenes_array) {

            //         let cleanItem = item.replace(/[()]/g, '');
            //         let values = cleanItem.split(',');

            //         let id_cita_radiologia = values[0];
            //         let id_pp = values[1];
            //         let id_estado_cita = values[2];

            //         if (id_estado_cita == 4) {
            //           iestado = 4;
            //           break;
            //         }
            //       }
            //       if (iestado == 4) {
            //           //======Color verde - cuando genera la orden de apoyo al diagnostico y se registra la cita y se atiende
            //           html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="Orden Atendida" style="float:left;padding-left:3px">';
            //           html +=`<button class="btn btn-primary btn-xs btn-modal-imagenes" data-id-radiologia="${row.id_radiologia}" data-examenes-array="${citas_imagenes_array}"><i class="fa fa-eye"></i></button></div>`;
            //       } else {
            //         //======Color amarrillo - cuando genera la orden de apoyo al diagnostico y se registra la cita
            //         html +='<div data-toggle="tooltip" data-placement="top" data-html="true" title="Orden Pendiente" style="float:left;padding-left:3px">';
            //         html +='<button class="btn btn-warning btn-xs" ><i class="fa fa-eye"></i></button></div>';
            //       }
            //     } 
      
            //   }
            // }
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [12],
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          //Banco de Sangre
          return "";
        },
        "bSortable": false,
        "aTargets": [13],
        "visible": false
      },
      {
        "mRender": function(data, type, row) {
          //Cardiologia
          let html = "";
          let cita_solicitada = false;
          let cita_programada = false;
          let id_cita = 0;
          let id_prestacion = 0;
          let flag = 0;
          let id_cqx_cita = 0;

          if (row.cita_riesgo_qx_cardio != null) {
            // Elimina los caracteres de las llaves y las comillas
            let cita_riesgo_qx_cardio = row.cita_riesgo_qx_cardio.replace("{", "").replace("}", "").replace(/"/g, "");
            
            // Divide las tuplas en un array
            let citas_array = cita_riesgo_qx_cardio.split("),(");

            html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="Cita Cardiología">';
            
            for (let i = 0; i < citas_array.length; i++) {
              
              let cita = citas_array[i].replace("(", "").replace(")", "");
              
              //let id_servicio = cita.split(",")[0];
              id_cita = cita.split(",")[1];
              id_prestacion = cita.split(",")[2];
              flag = cita.split(",")[3];
              id_cqx_cita = cita.split(",")[4];
              console.log('LOG_CITA: '+flag);
              
              cita_solicitada = true;
              cita_programada = (id_cita != 0) ? true : false;
              
              if (cita_solicitada) {
                if (cita_programada) {
                  if (id_prestacion == 0) {
                    

                    //html += '<a href="/ver_cita_por_medico/' + id_user + '/'+id_cita +'" target="_blank" class="btn btn-warning btn-xs" onClick=""><i class="fa fa-eye"></i></a>';
                  } else {
                    if (flag == 3) {
                      
                      html +=
                        '<span ' +
                          'data-toggle="popover" ' +
                          'data-trigger="hover" ' +
                          'data-placement="top" ' +
                          'data-html="true" ' +
                          'title="<strong>Ver prestación del servicio de Cardiología</strong>" ' +
                          'data-content="Haz clic para ver los detalles de la prestación">' +
                          '<a href="javascript:void(0)" ' +
                            'data-id_user="{{ Auth::user()->id }}" ' +
                            `data-id_prestacion="${id_prestacion}" ` +
                            'id="mostrarModalVerPrestacionDetalle" ' +
                            'name="mostrarModalVerPrestacionDetalle" ' +
                            'class="btn btn-success btn-xs">' +
                            '<i class="fa fa-eye"></i>' +
                          '</a>' +
                        '</span>';
                    } else {
                    
                      html +=
                        '<span ' +
                          'data-toggle="popover" ' +
                          'data-trigger="hover" ' +
                          'data-placement="top" ' +
                          'data-html="true" ' +
                          'title="<strong>Ver prestación del servicio de Cardiología</strong>" ' +
                          'data-content="La prestación aún no está completa">' +
                          '<a href="javascript:void(0)" ' +
                            'data-id_user="{{ Auth::user()->id }}" ' +
                            `data-id_prestacion="${id_prestacion}" ` +
                            'id="mostrarModalVerPrestacionDetalle" ' +
                            'name="mostrarModalVerPrestacionDetalle" ' +
                            'class="btn btn-danger btn-xs">' +
                            '<i class="fa fa-eye"></i>' +
                          '</a>' +
                        '</span>';
                    }

                  }
                } else {
                  if(row.id_tipo_atencion==1){//SOLO AMBULATORIO
                    if (row.id_intervencion == null) {
                      // Botón deshabilitado sin popover
                      html +=
                        '<a href="javascript:void(0)" ' +
                          'class="btn btn-default btn-xs disabled" ' +
                          'style="pointer-events: none; opacity: 0.6;"' +
                        '>' +
                          '<i class="fa fa-edit"></i>' +
                        '</a>';
                    } else {
                      <?php if(empty(Auth::user()->flaghistoria)){ ?>
                        html +=
                        '<span ' +
                          'data-toggle="popover" ' +
                          'data-trigger="hover" ' +
                          'data-placement="top" ' +
                          'data-html="true" ' +
                          'title="<strong>Editar cita de Cardiología</strong>" ' +
                          'data-content="No tienes acceso a registrar citas">' +
                          '<a href="javascript:void(0)" ' +
                            'class="btn btn-default btn-xs disable" style="pointer-events: none; opacity: 0.6;">' +
                            '<i class="fa fa-edit"></i>' +
                          '</a>' +
                        '</span>';
                      <?php }else{?>
                      html +=
                        '<span ' +
                          'data-toggle="popover" ' +
                          'data-trigger="hover" ' +
                          'data-placement="top" ' +
                          'data-html="true" ' +
                          'title="<strong>Editar cita de Cardiología</strong>" ' +
                          'data-content="Haz clic para modificar los detalles de la intervención">' +
                          '<a href="javascript:void(0)" ' +
                            `data-id_prestacion="${row.id_prestacion}" ` +
                            `data-id_cqx_cita="${id_cqx_cita}" ` +
                            'data-id_servicio="27" ' +
                            `data-id_cqx_intervencion="${row.id_intervencion}" ` +
                            'id="mostrarModalCitaQuirurgica" ' +
                            'name="mostrarModalCitaQuirurgica" ' +
                            'class="btn btn-default btn-xs">' +
                            '<i class="fa fa-edit"></i>' +
                          '</a>' +
                        '</span>';
                      <?php }?>
                    }

                  }
                  //html += '<a href="/formulario_cita_riesgo_quirurgico/' + row.id + '/' + id_cqx_cita + '/27" target="_blank" class="btn btn-default btn-xs" onClick=""><i class="fa fa-edit"></i></a>';
                }
              }
            }
            html += '</div>';
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [14],
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          //Neumologia
          let html = "";
          let cita_solicitada = false;
          let cita_programada = false;
          let id_cita = 0;
          let id_prestacion = 0;
          let flag = 0;
          let id_cqx_cita = 0;

          if (row.cita_riesgo_qx_neumo != null) {
            // Elimina los caracteres de las llaves y las comillas
            let cita_riesgo_qx_neumo = row.cita_riesgo_qx_neumo.replace("{", "").replace("}", "").replace(/"/g, "");
            
            // Divide las tuplas en un array
            let citas_array = cita_riesgo_qx_neumo.split("),(");

            html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="Cita Neumología">';
            
            for (let i = 0; i < citas_array.length; i++) {
              
              let cita = citas_array[i].replace("(", "").replace(")", "");
              
              //let id_servicio = cita.split(",")[0];
              id_cita = cita.split(",")[1];
              id_prestacion = cita.split(",")[2];
              flag = cita.split(",")[3];
              id_cqx_cita = cita.split(",")[4];

              cita_solicitada = true;
              cita_programada = (id_cita != 0) ? true : false;
              
              if (cita_solicitada) {
                if (cita_programada) {
                  if (id_prestacion == 0) {
                   
                    //html += '<a href="/ver_cita_por_medico/' + id_user + '/'+id_cita +'" target="_blank" class="btn btn-warning btn-xs" onClick=""><i class="fa fa-eye"></i></a>';
                  } else {
                    if (flag == 3) {
                      // Prestación completa: botón verde con popover
                      html +=
                        '<span ' +
                          'data-toggle="popover" ' +
                          'data-trigger="hover" ' +
                          'data-placement="top" ' +
                          'data-html="true" ' +
                          'title="<strong>Ver prestación del servicio Neumología</strong>" ' +
                          'data-content="Pulsa para revisar los detalles de la prestación completada">' +
                          '<a href="javascript:void(0)" ' +
                            'data-id_user="{{ Auth::user()->id }}" ' +
                            `data-id_prestacion="${id_prestacion}" ` +
                            'id="mostrarModalVerPrestacionDetalle" ' +
                            'class="btn btn-success btn-xs">' +
                            '<i class="fa fa-eye"></i>' +
                          '</a>' +
                        '</span>';
                    } else {
                      // Prestación no completa: botón rojo con popover
                      html +=
                        '<span ' +
                          'data-toggle="popover" ' +
                          'data-trigger="hover" ' +
                          'data-placement="top" ' +
                          'data-html="true" ' +
                          'title="<strong>Ver prestación del servicio Neumología</strong>" ' +
                          'data-content="La prestación aún no está completa o requiere revisión">' +
                          '<a href="javascript:void(0)" ' +
                            'data-id_user="{{ Auth::user()->id }}" ' +
                            `data-id_prestacion="${id_prestacion}" ` +
                            'id="mostrarModalVerPrestacionDetalle" ' +
                            'class="btn btn-danger btn-xs">' +
                            '<i class="fa fa-eye"></i>' +
                          '</a>' +
                        '</span>';
                    }
                    // if (flag == 3) {
                    //   html += '<a href="/ver_prestacion/{{Auth::user()->id}}/' + id_prestacion + '" target="_blank" class="btn btn-success btn-xs" onClick=""><i class="fa fa-eye"></i></a>';
                    // } else {
                    //   html += '<a href="/ver_prestacion/{{Auth::user()->id}}/' + id_prestacion + '" target="_blank" class="btn btn-danger btn-xs" onClick=""><i class="fa fa-eye"></i></a>';
                    // }
                  }
                } else {
                  if(row.id_tipo_atencion==1){//SOLO AMBULATORIO
                    if (row.id_intervencion == null) {
                      // Botón deshabilitado sin popover
                      html +=
                        '<a href="javascript:void(0)" ' +
                          'class="btn btn-default btn-xs disabled" ' +
                          'style="pointer-events: none; opacity: 0.6;"' +
                        '>' +
                          '<i class="fa fa-edit"></i>' +
                        '</a>';
                    } else {
                      // Botón activo envuelto en <span> para popover
                      <?php if(empty(Auth::user()->flaghistoria)){ ?>
                      html +=
                        '<span ' +
                          'data-toggle="popover" ' +
                          'data-trigger="hover" ' +
                          'data-placement="top" ' +
                          'data-html="true" ' +
                          'title="<strong>Editar cita de Neumologia</strong>" ' +
                          'data-content="No tienes acceso a registrar citas">' +
                          '<a href="javascript:void(0)" ' +
                            'class="btn btn-default btn-xs disable" style="pointer-events: none; opacity: 0.6;">' +
                            '<i class="fa fa-edit"></i>' +
                          '</a>' +
                        '</span>';
                      <?php }else{?>
                        html +=
                        '<span ' +
                          'data-toggle="popover" ' +
                          'data-trigger="hover" ' +
                          'data-placement="top" ' +
                          'data-html="true" ' +
                          'title="<strong>Editar cita de Neumologia</strong>" ' +
                          'data-content="Abre el modal para editar los detalles de la cita">' +
                          '<a href="javascript:void(0)" ' +
                            `data-id_prestacion="${row.id_prestacion}" ` +
                            `data-id_cqx_cita="${id_cqx_cita}" ` +
                            'data-id_servicio="63" ' +
                            `data-id_cqx_intervencion="${row.id_intervencion}" ` +
                            'id="mostrarModalCitaQuirurgica" ' +
                            'class="btn btn-default btn-xs">' +
                            '<i class="fa fa-edit"></i>' +
                          '</a>' +
                        '</span>';
                      <?php }?>


                    }
                  }
                  //html += '<a href="/formulario_cita_riesgo_quirurgico/' + row.id + '/' + id_cqx_cita + '/63" target="_blank" class="btn btn-default btn-xs" onClick=""><i class="fa fa-edit"></i></a>';
                }
              }
            }
            html += '</div>';
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [15],
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          //Anestesiologia
          let html = "";
          let cita_solicitada = false;
          let cita_programada = false;
          let id_cita = 0;
          let id_prestacion = 0;
          let flag = 0;
          let id_cqx_cita = 0;

          if (row.cita_riesgo_qx_anestesio != null) {
            // Elimina los caracteres de las llaves y las comillas
            let cita_riesgo_qx_anestesio = row.cita_riesgo_qx_anestesio.replace("{", "").replace("}", "").replace(/"/g, "");
            
            // Divide las tuplas en un array
            let citas_array = cita_riesgo_qx_anestesio.split("),(");

            html += '<div data-toggle="tooltip" data-placement="top" data-html="true">';
            
            for (let i = 0; i < citas_array.length; i++) {
              
              let cita = citas_array[i].replace("(", "").replace(")", "");
              
              //let id_servicio = cita.split(",")[0];
              id_cita = cita.split(",")[1];
              id_prestacion = cita.split(",")[2];
              flag = cita.split(",")[3];
              id_cqx_cita = cita.split(",")[4];

              cita_solicitada = true;
              cita_programada = (id_cita != 0) ? true : false;
              
              if (cita_solicitada) {
                if (cita_programada) {
                  if (id_prestacion == 0) {
                

                   //html += '<a href="/ver_cita_por_medico/' + id_user + '/'+id_cita +'" target="_blank" class="btn btn-warning btn-xs" onClick=""><i class="fa fa-eye"></i></a>';
                  } else {
                    if (flag == 3) {
                      // Prestación exitosa: botón verde con popover
                      html +=
                        '<span ' +
                          'data-toggle="popover" ' +
                          'data-trigger="hover" ' +
                          'data-placement="top" ' +
                          'data-html="true" ' +
                          'title="<strong>Ver prestación del servicio Anestesiología</strong>" ' +
                          'data-content="Consulta los detalles de la prestación realizada">' +
                          '<a href="javascript:void(0)" ' +
                            'data-id_user="{{ Auth::user()->id }}" ' +
                            `data-id_prestacion="${id_prestacion}" ` +
                            'id="mostrarModalVerPrestacionDetalle" ' +
                            'class="btn btn-success btn-xs">' +
                            '<i class="fa fa-eye"></i>' +
                          '</a>' +
                        '</span>';
                    } else {
                      // Prestación con observaciones o error: botón rojo con popover
                      html +=
                        '<span ' +
                          'data-toggle="popover" ' +
                          'data-trigger="hover" ' +
                          'data-placement="top" ' +
                          'data-html="true" ' +
                          'title="<strong>Ver prestación del servicio Anestesiología</strong>" ' +
                          'data-content="Revisa los detalles; puede haber observaciones">' +
                          '<a href="javascript:void(0)" ' +
                            'data-id_user="{{ Auth::user()->id }}" ' +
                            `data-id_prestacion="${id_prestacion}" ` +
                            'id="mostrarModalVerPrestacionDetalle" ' +
                            'class="btn btn-danger btn-xs">' +
                            '<i class="fa fa-eye"></i>' +
                          '</a>' +
                        '</span>';
                    }
                    // if (flag == 3) {
                    //   html += '<a href="/ver_prestacion/{{Auth::user()->id}}/' + id_prestacion + '" target="_blank" class="btn btn-success btn-xs" onClick=""><i class="fa fa-eye"></i></a>';
                    // } else {
                    //   html += '<a href="/ver_prestacion/{{Auth::user()->id}}/' + id_prestacion + '" target="_blank" class="btn btn-danger btn-xs" onClick=""><i class="fa fa-eye"></i></a>';
                    // }
                  }
                } else {
                  if(row.id_tipo_atencion==1){//SOLO AMBULATORIO
                    if (row.id_intervencion == null) {
                      // Botón deshabilitado sin popover
                      html +=
                        '<a href="javascript:void(0)" ' +
                          'class="btn btn-default btn-xs disabled" ' +
                          'style="pointer-events: none; opacity: 0.6;"' +
                        '>' +
                          '<i class="fa fa-edit"></i>' +
                        '</a>';
                    } else {
                      // Botón activo envuelto en <span> para popover
                      <?php if(empty(Auth::user()->flaghistoria)){ ?>
                      html +=
                        '<span ' +
                          'data-toggle="popover" ' +
                          'data-trigger="hover" ' +
                          'data-placement="top" ' +
                          'data-html="true" ' +
                          'title="<strong>Cita Anestesiología</strong>" ' +
                          'data-content="No tienes acceso a registrar citas">' +
                          `<a href="javascript:void(0)" ` +
                            'class="btn btn-default btn-xs disable" style="pointer-events: none; opacity: 0.6;">' +
                            '<i class="fa fa-edit"></i>' +
                          '</a>' +
                        '</span>';
                        <?php }else{?>
                           html +=
                        '<span ' +
                          'data-toggle="popover" ' +
                          'data-trigger="hover" ' +
                          'data-placement="top" ' +
                          'data-html="true" ' +
                          'title="<strong>Cita Anestesiología</strong>" ' +
                          'data-content="Editar detalles de la cita de Anestesiología">' +
                          `<a href="javascript:void(0)" ` +
                            `data-id_prestacion="${row.id_prestacion}" ` +
                            `data-id_cqx_cita="${id_cqx_cita}" ` +
                            `data-id_servicio="72" ` +
                            `data-id_cqx_intervencion="${row.id_intervencion}" ` +
                            'id="mostrarModalCitaQuirurgica" ' +
                            'class="btn btn-default btn-xs">' +
                            '<i class="fa fa-edit"></i>' +
                          '</a>' +
                        '</span>';
                        <?php }?>
                    }
                  }
                  //html += '<a href="/formulario_cita_riesgo_quirurgico/' + row.id + '/' + id_cqx_cita + '/72" target="_blank" class="btn btn-default btn-xs" onClick=""><i class="fa fa-edit"></i></a>';
                }
              }
            }
            html += '</div>';
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [16],
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          
          let html = "";
          let cita_solicitada = false;
          let cita_programada = false;
          let id_cita = 0;
          let id_prestacion = 0;
          let flag = 0;
          let id_cqx_cita = 0;
          //SERVICIOS INTERCONSULTAS QUE AN SIDO REGISTRADOS EN CQX_CITAS
          if (row.cita_riesgo_qx_interconsulta != null) {
            // Elimina los caracteres de las llaves y las comillas
            let cita_riesgo_qx_interconsulta = row.cita_riesgo_qx_interconsulta.replace("{", "").replace("}", "").replace(/"/g, "");
            
            // Divide las tuplas en un array
            let citas_array = cita_riesgo_qx_interconsulta.split("),(");

            html += '<div data-toggle="tooltip" data-placement="top" data-html="true" >';
            
            for (let i = 0; i < citas_array.length; i++) {
              
              let cita = citas_array[i].replace("(", "").replace(")", "");
              
              let id_servicio = cita.split(",")[0];
              id_cita = cita.split(",")[1];
              id_prestacion = cita.split(",")[2];
              flag = cita.split(",")[3];
              id_cqx_cita = cita.split(",")[4];
              cqx__servicio = cita.split(",")[5];

              cita_solicitada = true;
              cita_programada = (id_cita != 0) ? true : false;
              
              if (cita_solicitada) {
                if (cita_programada) {
                  if (id_prestacion == 0) {
                    // Cita aún no prestada: botón amarillo
                    html +=
                      '<span ' +
                        'data-toggle="popover" ' +
                        'data-trigger="hover" ' +
                        'data-placement="top" ' +
                        'data-html="true" ' +
                        `title="<strong>Ver cita de ${cqx__servicio}</strong>" ` +
                        'data-content="Cita pendiente, sin prestación asociada"' +
                      '>' +
                        `<a href="javascript:void(0)" ` +
                          `data-id_user="${id_user}" ` +
                          `data-id_cita="${id_cita}" ` +
                          `id="mostrarModalCitaQuirurgicaDetalle" ` +
                          'class="btn btn-warning btn-xs">' +
                          '<i class="fa fa-eye"></i>' +
                        '</a>' +
                      '</span> ';
                  } else {
                    // Cita ya prestada: color según flag
                    var btnClass = flag == 3 ? 'btn-success' : 'btn-danger';
                    html +=
                      '<span ' +
                        'data-toggle="popover" ' +
                        'data-trigger="hover" ' +
                        'data-placement="top" ' +
                        'data-html="true" ' +
                        `title="<strong>Ver cita de ${cqx__servicio}</strong>" ` +
                        'data-content="Cita con prestación registrada"' +
                      '>' +
                        `<a href="javascript:void(0)" ` +
                          `data-id_user="{{ Auth::user()->id }}" ` +
                          `data-id_prestacion="${id_prestacion}" ` +
                          'id="mostrarModalVerPrestacionDetalle" ' +
                          `class="btn ${btnClass} btn-xs">` +
                          '<i class="fa fa-eye"></i>' +
                        '</a>' +
                      '</span> ';
                  }
                } else {
                  if(row.id_tipo_atencion==1){//SOLO AMBULATORIO
                    if (row.id_intervencion == null) {
                      // Botón deshabilitado sin popover
                      html +=
                        '<a href="javascript:void(0)" ' +
                          'class="btn btn-default btn-xs disabled" ' +
                          'style="pointer-events: none; opacity: 0.6;"' +
                        '>' +
                          '<i class="fa fa-edit"></i>' +
                        '</a>';
                    } else {
                      // Botón activo con popover HTML
                      <?php if(empty(Auth::user()->flaghistoria)){ ?>
                      html +=
                        '<span ' +
                          'data-toggle="popover" ' +
                          'data-trigger="hover" ' +
                          'data-placement="top" ' +
                          'data-html="true" ' +
                          `title="<strong>Cita ${cqx__servicio} </strong>" ` +
                          `data-content="No tienes acceso a registrar citas de ${cqx__servicio}"` +
                        '>' +
                          `<a href="javascript:void(0)" ` +
                            'class="btn btn-default btn-xs disable" style="pointer-events: none; opacity: 0.6;">' +
                            '<i class="fa fa-edit"></i>' +
                          '</a>' +
                        '</span>';
                        <?php }else{ ?>
                          html +=
                        '<span ' +
                          'data-toggle="popover" ' +
                          'data-trigger="hover" ' +
                          'data-placement="top" ' +
                          'data-html="true" ' +
                          `title="<strong>Cita ${cqx__servicio} </strong>" ` +
                          `data-content="Cita quirúrgica del servicio ${cqx__servicio}"` +
                        '>' +
                          `<a href="javascript:void(0)" ` +
                            `data-id_prestacion="${row.id_prestacion}" ` +
                            `data-id_cqx_cita="${id_cqx_cita}" ` +
                            `data-id_servicio="${id_servicio}" ` +
                            `data-id_cqx_intervencion="${row.id_intervencion}" ` +
                            'id="mostrarModalCitaQuirurgica" ' +
                            'class="btn btn-default btn-xs">' +
                            '<i class="fa fa-edit"></i>' +
                          '</a>' +
                        '</span>';
                        <?php } ?>
                    }
                  }
                  //html += '<a href="/formulario_cita_riesgo_quirurgico/' + row.id + '/' + id_cqx_cita + '/72" target="_blank" class="btn btn-default btn-xs" onClick=""><i class="fa fa-edit"></i></a>';
                }
              }
            }
            html += '</div>';
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [17],
        "className": "text-center",
      },
      {
        "mRender": function(data, type, row) {
          var estado = $('#estado').val();
          if(estado == 5 && row.suspended_us){
            html = '<span ' +
                    '<a href="javascript:void(0)" ' +
                      'class="btn btn-success btn-xs" disabled>' +
                      '<i class="fa fa-eye"></i>' +
                    '</a>' +
                  '</span>';
          }else{
            html = '<span ' +
                    'data-toggle="popover" ' +
                    'data-trigger="hover" ' +
                    'data-placement="top" ' +
                    'data-html="true" ' +
                    'title="<strong>Ver datos generales de las Historias clinicas</strong>" ' +
                    'data-content="Pulse para ver la historia clínica completa del asegurado">' +
                    '<a href="javascript:void(0)" ' +
                      `onclick="ver_modal_hc('${row.id_asegurado}')" ` +
                      'class="btn btn-success btn-xs">' +
                      '<i class="fa fa-eye"></i>' +
                    '</a>' +
                  '</span>';
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [18],
        "className": "text-center",
      },

      {
        "mRender": function(data, type, row) {
          // Registro de CQ
          let button_dropdown_disabled = row.is_validated ? '' : ' disabled="true"';
          let html = "";
          var estado = $('#estado').val();
          html += '<div class="btn-group">';
         
          if(estado == 5 && row.suspended_us){
             html += '<div>';
              html +=
                '<span ' +
                  'data-toggle="popover" ' +
                  'data-trigger="hover" ' +
                  'data-placement="top" ' +
                  'data-html="true" ' +
                  'title="<strong>Reporte operatorio no disponible</strong>" ' +
                  'data-content="Falta el registro de tipo de egreso">' +
                  '<a href="javascript:void(0)" class="btn btn-primary btn-xs disabled">' +
                    '<i class="fa fa-download"></i>' +
                  '</a>' +
                '</span>';
              html += '</div>';
          }else{
            if (row.id_tipo_agreso != null || row.id_tipo_agreso != '') {
              html += '<div>';

              if (row.ruta_firma_reporte_operatorio != null && row.ruta_firma_reporte_operatorio != "") {
                html += '<a href="' + row.ruta_firma_reporte_operatorio +'" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>';
              } else {
                html +=
                  '<span ' +
                    'data-toggle="popover" ' +
                    'data-trigger="hover" ' +
                    'data-placement="top" ' +
                    'data-html="true" ' +
                    'title="<strong>Reporte operatorio</strong>" ' +
                    'data-content="Descarga el reporte operatorio completo">' +
                    '<a ' +
                      'href="/print_hospitalizacion_reporte_operatorio_cq/' + row.id_intervencion + '" ' +
                      'target="_blank" ' +
                      'class="btn btn-primary btn-xs">' +
                      '<i class="fa fa-download"></i>' +
                    '</a>' +
                  '</span>';
              }
              html += '</div>';
            } else {
              html += '<div>';
              html +=
                '<span ' +
                  'data-toggle="popover" ' +
                  'data-trigger="hover" ' +
                  'data-placement="top" ' +
                  'data-html="true" ' +
                  'title="<strong>Reporte operatorio no disponible</strong>" ' +
                  'data-content="Falta el registro de tipo de egreso">' +
                  '<a href="javascript:void(0)" class="btn btn-primary btn-xs disabled">' +
                    '<i class="fa fa-download"></i>' +
                  '</a>' +
                '</span>';
              html += '</div>';
            }
          }

          if(row.id_tipo_egreso==1){
            html += '<div>';
            if (row.ruta_papeleta_egreso != null && row.ruta_papeleta_egreso != "") {
                html += '<a href="' + row.ruta_papeleta_egreso +'" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>';
            } else {
                html += '<span ' +
                'data-toggle="popover" ' +
                'data-trigger="hover" ' +
                'data-placement="top" ' +
                'data-html="true" ' +
                'title="Papeleta de egreso" ' +
                'data-content="Descarga la papeleta de egreso quirúrgico">' +
                '<a ' +
                  'href="/print_hospitalizacion_papeleta_egreso_atencion_quirurgica/' + row.id_hospitalizacion + '" ' +
                  'target="_blank" ' +
                  'class="btn btn-primary btn-xs">' +
                  '<i class="fa fa-download"></i>' +
                '</a>' +
                '</span>';
            }
            html += '</div>';
          }else{
            html += '<div>';
            html += '<span ' +
            'data-toggle="popover" ' +
            'data-trigger="hover" ' +
            'data-placement="top" ' +
            'data-html="true" ' +
            'title="<strong>Papeleta de egreso no disponible</strong>" ' +
              'data-content="Falta el registro de tipo de egreso">' +
              '<a href="javascript:void(0)" class="btn btn-primary btn-xs disabled">' +
                '<i class="fa fa-download"></i>' +
              '</a>' +
            '</span>';
            html += '</div>';
          }
          
          if(row.id_tipo_atencion==1 && !row.es_nuevo_admision_hospitalizacion && row.flag_atencion_qx==1 && row.id_tipo_egreso==1){
            html += '<div>';
            if (row.ruta_epicrisis != null && row.ruta_epicrisis != "") {
                html += '<a href="' + row.ruta_epicrisis +'" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-download"></i></a>';
            } else {
                html += '<span ' +
                'data-toggle="popover" ' +
                'data-trigger="hover" ' +
                'data-placement="top" ' +
                'data-html="true" ' +
                'title="EPICRISIS" ' +
                'data-content="Descargar el documento de EPICRISIS - Atención quirurgica">' +
                '<a ' +
                  'href="/print_atencion_quirurgica_epicrisis/' + row.id_intervencion + '" ' +
                  'target="_blank" ' +
                  'class="btn btn-primary btn-xs">' +
                  '<i class="fa fa-download"></i>' +
                '</a>' +
                '</span>';
            }
            html += '</div>';
          }else{
            html += '<div>';
            html += '<span ' +
            'data-toggle="popover" ' +
            'data-trigger="hover" ' +
            'data-placement="top" ' +
            'data-html="true" ' +
            'title="<strong>EPICRIS no disponible</strong>" ' +
              'data-content="Solo para tipo de atención ambulatoria; no hay registro para destino domiciliario.">' +
              '<a href="javascript:void(0)" class="btn btn-primary btn-xs disabled">' +
                '<i class="fa fa-download"></i>' +
              '</a>' +
            '</span>';
            html += '</div>';
          } 

          html += ' <ul class="dropdown-menu" style="left: auto !important;"> ';
          //if (row.flag_intervencion == 1) {
          html += ' <li>';
          html += ` <a href="javascript:void(0)" data-asegurado='${JSON.stringify(row)}'` + 'id="mostrarModalIntraoperatorio" name="listarHojaGrafica" readonly>';
          html += ' 	<i class="fa fa-edit" aria-hidden="true"></i> Intraoperatorio';
          html += ' </a>';
          html += ' </li>';                                  
          //}
          //if (row.flag_intervencion == 1 || row.flag_intervencion == 2) {
          html += '<li>';
          html += '<a href="javascript:void(0)" onClick="mostrarModalAtencion_Quirurgica(' + (row.id_cita || 0) + ',' + row.id_intervencion + ',' + row.id_tipo_atencion + ',' + row.id_hospitalizacion+ ',' + row.flag_atencion_qx+ ',' +(row.id_prestacion || 0) +')" id="listarBalanceHidrico" name="listarBalanceHidrico">';
          html += '    <i class="fa fa-medkit" aria-hidden="true"></i> Atención Quirúrgica';
          html += ' </a>';
          html += '</li>';
          //}
          //if (row.flag_intervencion == 1) {
          html += ' <li>';

            let cita_solicitada = false;
            let cita_programada = false;
            let id_cita = 0;
            let id_prestacion = 0;
            let id_intervencion = 0;
            let flag = 0;

            if (row.cita_riesgo_qx != null) {
              // Elimina los caracteres de las llaves y las comillas
              let cita_riesgo_qx = row.cita_riesgo_qx.replace("{", "").replace("}", "").replace(/"/g, "");
              
              // Divide las tuplas en un array
              let citas_array = cita_riesgo_qx.split("),(");
              
              for (let i = 0; i < citas_array.length; i++) {
                
                let cita = citas_array[i].replace("(", "").replace(")", "");
                
                let id_servicio = cita.split(",")[0];
                let id_pres = cita.split(",")[2];

                if (id_servicio == 72) {
                  id_prestacion = id_pres;
                  break;
                }
              }
            }
            id_prestacion = row.id_prestacion;
          
            if (id_prestacion > 0 || row.es_nuevo_admision_hospitalizacion) {
              html += ` <a href="javascript:void(0)" onClick="mostrarModalAnestesiologia(${id_prestacion || 0},${row.id_intervencion})" class="mostrarModalAnestesiologia" id="mostrarModalAnestesiologia" data-id-prestacion="${id_prestacion || 0}" name="listarMonitoreos">`;
              html += ` <i class="fa fa-edit" aria-hidden="true"></i> Atención Anestesiología`;
              html += ' </a>  ';
            } else {
             html += ` <a href="javascript:void(0)" disabled >`;
             html += ` <i class="fa fa-edit" aria-hidden="true"></i> Atención Anestesiología`;
             html += ' </a>  ';
            }

          html += ' </li>  ';
          //}
          /*html += ' <li>';
          html += ' <a href="javascript:void(0)" onclick="mostrarModalRegistroSeguridad()" id="listarKardex" name="listarKardex">';
          html += ' 	<i class="fa fa-shield" aria-hidden="true"></i> Registro de Seguridad';
          html += ' </a> ';
          html += ' </li>';

          html += '<li>';
          html += '<a href="javascript:void(0)" onClick="mostrarModalConAlmacen()" name="listarConsumoAlmacen">';
          html += '<i class="fa fa-university" aria-hidden="true"></i> Consumo de Almacen';
          html += '</a>';
          html += '</li>';

          html += ' <li>';
          html += ' <a href="javascript:void(0)" onClick="mostrarModalReporteOperatorio()" id="listarHojaConduccion" name="listarHojaConduccion" >';
          html += ' 	<i class="fa fa-edit" aria-hidden="true"></i> Reporte Operatorio';
          html += ' </a>';
          html += ' </li>';

          html += ' <li>';
          html += ' <a href="/instrumentos_quirurgicos" target="_blank" id="listarHojaConduccion" name="listarHojaConduccion" >';
          html += '   <i class="fa fa-edit" aria-hidden="true"></i> Instrumentos Quirúrgicos';
          html += ' </a>';
          html += ' </li>';*/

          // html += ' <li>';
          // html += ' <a href="/hoja_recuperacion" id="recuperacion" name="recuperacion" target="_blank">';
          // html += ' 	<i class="fa fa-edit" aria-hidden="true"></i> Hoja de Recuperación ';
          // html += ' </a>';
          // html += ' </li>';
          html += ' <li>';
          html += ` <a href="javascript:void(0)" data-asegurado='${JSON.stringify(row)}'` + 'id="mostrarModalHojaRecuperacion">';
          html += ' 	<i class="fa fa-edit" aria-hidden="true"></i> Hoja de recuperación';
          html += ' </a>';
          html += ' </li>';    

          html += ' </ul>';
          html += ' </div>';
          return html;
        },
        "bSortable": false,
        "aTargets": [19],
        "className": "text-center",
      },
      /*{
        "mRender": function(data, type, row) {
          var html = "";
          if (row.id_estado_reg == 1) {
            html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b></b>">';
            html += '<a href="/registrar_intraoperario" target="_blank" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>';
          } else {
            html += '<button class="btn btn-default btn-xs" disabled=""><i class="fa fa-edit"></i></button>';
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [17],
        "className": "text-center",
      },*/
      {
        "mRender": function(data, type, row) {
          console.log("[row] => ", row);
          // Validar
          let html = "";
          let estado = 0; // Estado que indica si tiene el registro de riesgo quirurgico necesario para continuar.
          var flag = $('#estado').val();
          if(flag == 5 && row.suspended_us){
              html += '<button class="btn btn-default btn-xs" disabled=""><i class="fa fa-check"></i></button>';
          }else{
            // if (row.id_prioridad_programacion == null) {
            if (!row.is_validated) {
              // if (row.id_tipo_atencion == 1 && !row.es_nuevo_admision_hospitalizacion && !row.es_traslado_hospitalizacion_acqx) {
              // html += '<button class="btn btn-default btn-xs" disabled="" title="'+row.id_tipo_atencion+'"><i class="fa fa-check"></i></button>';
              if (row.id_tipo_atencion == 1) {
                estado = 1; 
                html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="Validar">';
                html += '<a href="javascript:void(0)" onclick="mostrarModalValidacionProgramacionQx(' + row.id_intervencion + ','+row.id_tipo_atencion+',' + estado + ')" class="btn btn-default btn-xs"><i class="fa fa-check"></i></a>';
                html += '</div>';
              
              } else if (row.id_tipo_atencion == 2) { // Emergencia
                if (row.is_validated) {
                  html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="Validar">';
                  html += '<a href="javascript:void(0)" onclick="mostrarModalValidacionProgramacionQx(' + row.id_intervencion + ','+row.id_tipo_atencion+', 1)" class="btn btn-success btn-xs"><i class="fa fa-check"></i></a>';
                  html += '</div>';
                }else{
                  estado = 1; // No es necesario registrar algun riesgo quirurgico
                  html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="Validar">';
                  html += '<a href="javascript:void(0)" onclick="mostrarModalValidacionProgramacionQx(' + row.id_intervencion + ','+row.id_tipo_atencion+',' + estado + ')" class="btn btn-default btn-xs"><i class="fa fa-check"></i></a>';
                  html += '</div>';
                }
                // html +=
                // '<span ' +
                //   'data-toggle="popover" ' +
                //   'data-trigger="hover" ' +
                //   'data-placement="top" ' +
                //   'data-html="true" ' +
                //   'title="<strong>PDF SOP</strong>" ' +
                //   'data-content="Descarga el reporte Programación de salas de operaciones">' +
                //   '<a ' +
                //     'href="/pdf_sop_cqx/' + row.id_intervencion_quirurgica + '" ' +
                //     'target="_blank" ' +
                //     'class="btn btn-primary btn-xs">' +
                //     '<i class="fa fa-download"></i>' +
                //   '</a>' +
                // '</span>';
              }else if (row.id_tipo_atencion == 3) { // HOSPI
                if (row.is_validated) {
                  html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="Validar">';
                  html += '<a href="javascript:void(0)" onclick="mostrarModalValidacionProgramacionQx(' + row.id_intervencion + ','+row.id_tipo_atencion+', 1)" class="btn btn-success btn-xs"><i class="fa fa-check"></i></a>';
                  html += '</div>';
                }else{
                  estado = 1; // No es necesario registrar algun riesgo quirurgico
                  html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="Validar">';
                  html += '<a href="javascript:void(0)" onclick="mostrarModalValidacionProgramacionQx(' + row.id_intervencion + ','+row.id_tipo_atencion+',' + estado + ')" class="btn btn-default btn-xs"><i class="fa fa-check"></i></a>';
                  html += '</div>';
                }
              }else{
                //ES TEMPORAL FALTA TERMINAR DE IMPLEMENTAR PARA PACIENTES NUEVO ADMISION EN HOSPITALIZACION
                estado = 1; 
                html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="Validar">';
                html += '<a href="javascript:void(0)" onclick="mostrarModalValidacionProgramacionQx(' + row.id_intervencion + ','+row.id_tipo_atencion+',' + estado + ')" class="btn btn-default btn-xs"><i class="fa fa-check"></i></a>';
                html += '</div>';
              }
            } else {
              
              if (row.id_tipo_atencion == 1) { // Origen de Consulta externa
                if (row.cita_riesgo_qx != null) {
                  // Elimina los caracteres de las llaves y las comillas
                  let cita_riesgo_qx = row.cita_riesgo_qx.replace("{", "").replace("}", "").replace(/"/g, "");
                  
                  // Divide las tuplas en un array
                  let citas_array = cita_riesgo_qx.split("),(");

                  citas_array.some(cita => {
                    let [id_servicio, , , flag] = cita.replace(/[()]/g, "").split(",");
                    
                    if (id_servicio == 72 && flag == 3) { // Servicio Anestesiologia y Atención sin Observación
                      estado = 1;
                      return true;
                    }
                  });
                }
              } else if (row.id_tipo_atencion == 2) { // Origen de Emergencia
                estado = 1;
              }else{
                 estado = 1;
              }

              if (!row.is_validated) {
                //DE MANERA TEMPORAL SERA COMENTADO ESTAS LINEAS
                // html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="Validar">';
                // html += '<a href="javascript:void(0)" onclick="mostrarModalValidacionProgramacionQx(' + row.id_intervencion + ','+row.id_tipo_atencion+',' + estado + ')" class="btn btn-default btn-xs"><i class="fa fa-check"></i></a>';
                // html += '</div>';
                html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="Validar">';
                html += '<a href="javascript:void(0)" onclick="mostrarModalValidacionProgramacionQx(' + row.id_intervencion + ','+row.id_tipo_atencion+', 1)" class="btn btn-default btn-xs"><i class="fa fa-check"></i></a>';
                html += '</div>';

              } else if (row.is_validated) {
                html += '<div style="display: flex; flex-direction: column; gap: 2px; align-items: start;">';
                html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="Validar">';
                html += '<a href="javascript:void(0)" onclick="mostrarModalValidacionProgramacionQx(' + row.id_intervencion + ','+row.id_tipo_atencion+', 1)" class="btn btn-success btn-xs"><i class="fa fa-check"></i></a>';
                html += '</div>';

                let sugerencia = '';
                let tieneSugerencia = row.is_sugerencia_medica && row.sugerencia_medica && row.sugerencia_medica.trim() !== '';

                if (tieneSugerencia) {
                  sugerencia = row.sugerencia_medica;
                } else {
                  sugerencia = 'No tiene sugerencia médica registrada.';
                }
                html +=
                '<button type="button" ' +
                  'class="btn btn-success btn-xs" ' +
                  (tieneSugerencia ? '' : 'disabled ') + // deshabilita si no tiene sugerencia
                  'data-toggle="popover" ' +
                  'data-trigger="hover" ' +
                  'data-placement="top" ' +
                  'data-html="true" ' +
                  'title="<strong>Sugerencia médica</strong>" ' +
                  'data-content="' + sugerencia.replace(/"/g, '&quot;') + '">' +
                    '<i class="fa fa-eye"></i>' +
                '</button>';
                html += '</div>';

              }
            }
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [20],
        "className": "text-center"
      },
     
      /*{
        "mRender": function(data, type, row) {
          var html = "";
          if (row.id_estado_reg == 1) {
            html += '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b></b>">';
            html += '<a href="javascript:void(0)" class="btn btn-success btn-xs" ><i class="fa fa-file"></i></a></div>';
          } else {
            html += '<button class="btn btn-success btn-xs" disabled=""><i class="fa fa-file"></i></button>';
          }
          return html;
        },
        "bSortable": false,
        "aTargets": [18],
        "className": "text-center",
      },*/
    ]
    });
  }

  /**************LABORATORIO MODAL********************************/
  // $('#tblIntervencionQx tbody').on('click', '.btn-modal-laboratorio', function () {
  //   $('#tblModalLaboratorio tbody').html('');
  //   let html = '';
  //   let html2 = '';
  //   const idLaboratorio = $(this).data('id-laboratorio');
  //   const nroDoc = $(this).data('nro-doc');
  //   const fechaTomaMuestra = $(this).data('fecha-toma-muestra');
  //   const codOrdenLab = $(this).data('cod-orden-lab');
  //   descripcion_corta
  //   //const ordenLaboratorioArrayStr = $(this).data('orden-laboratorio-array');

  //   // const ordenLaboratorioArray = ordenLaboratorioArrayStr
  //   // .split('),(') // Dividir los pares por el delimitador
  //   // .map(str => str
  //   //     .replace(/\(|\)/g, '') // Eliminar paréntesis
  //   //     .split(/,(?=(?:[^\*]*\*[^*]*\*)*[^\*]*$)/) // Dividir por comas, pero ignorar las comas dentro de descripciones con \*
  //   // );

  //   // let i = 0;
  //   const  descripcion_corta=""{\"(9180,\\\"{\\\"\\\"(86901,\\\\\\\\\\\"\\\"FENOTIPO RH.\\\\\\\\\\\"\\\")\\\"\\\",\\\"\\\"(86900,\\\\\\\\\\\"\\\"GRUPO SANGUINEO\\\\\\\\\\\"\\\")\\\"\\\",\\\"\\\"(85345,\\\\\\\\\\\"\\\"TIEMPO DE COAGULACIÓN T\\\\\\\\\\\"\\\")\\\"\\\",\\\"\\\"(85002,\\\\\\\\\\\"\\\"TIEMPO DE SANGRIA\\\\\\\\\\\"\\\")\\\"\\\",\\\"\\\"(85732,\\\\\\\\\\\"\\\"TIEMPO PARCIAL DE TROMBOPLASTINA\\\\\\\\\\\"\\\")\\\"\\\",\\\"\\\"(85610,\\\\\\\\\\\"\\\"TIEMPO PROTROMBINA\\\\\\\\\\\"\\\")\\\"\\\"}\\\")\"}"
  //   for (let item of descripcion_corta) {
  //     if(idLaboratorio == 9180){
  //       i++;
  //       const idpp = item[0]
  //       // const codigoLab = item[1]
  //       // let str = item[2]
  //       // const descripcion = str.slice(2, -2);

  //       html +=`<tr>`;
  //       html +=`<td>1</td>`;
  //       html +=`<td>${86901}</td>`;
  //       html +=`<td>${FENOTIPO RH}</td>`;
  //       html +=`<td><button class="btn btn-default btn-xs verPdfVisorLaboratorio" id="verPdfVisorLaboratorio">
  //                   <i class="fa fa-search"></i>
  //                 </button></td>`;
  //     }
  //   }

  //   html2 +=`<button style="padding : 0.8rem 1rem;" class="btn btn-primary btn-xs verPdfVisorLaboratorio" id="verPdfVisorLaboratorio" data-id-laboratorio="${idLaboratorio}" data-nro-doc="${nroDoc}" data-fecha-toma-muestra="${fechaTomaMuestra}" data-cod-orden-lab="${codOrdenLab}" =>
  //             <i style="font-size : 2rem;" class="fa fa-search"></i>
  //           </button>`;

            
    
  //   $('#tblModalLaboratorio tbody').html(html);
  //   $('#btnPdfLaboratorio').html(html2);
    
  //   $('#modal-laboratorio-resultado').modal('show'); 
  // });
  
  
  $('#tblIntervencionQx tbody').on('click', '.btn-modal-laboratorio', function () {
    $('#tblModalLaboratorio tbody').html('');
    let html = '';
    let html2 = '';

    const idLaboratorio = parseInt($(this).data('id-laboratorio'), 10);
    const nroDoc = $(this).data('nro-doc');
    const fechaTomaMuestra = $(this).data('fecha-toma-muestra');
    const codOrdenLab = $(this).data('cod-orden-lab');
    const ruta_laboratorio = $(this).data('ruta_laboratorio');

    $('#verPdfVisorLaboratorio').attr('data-cod-orden-lab', codOrdenLab);

    //1. Obtenemos la cadena con todos los laboratorios y exámenes
    const examenesCodificados = $(this).data('examenes-laboratorio');
    const examenesDecodificados = decodeURIComponent(examenesCodificados);

    //2. Limpieza robusta de caracteres problemáticos
    let cadenaLimpia = examenesDecodificados
        .replace(/^\{+|}+$/g, '')       // eliminar llaves externas
        .replace(/"{2,}/g, '"')         // eliminar comillas dobles redundantes
        .replace(/\\\\+/g, '\\')        // reducir múltiples backslashes a uno solo
        .replace(/\\"/g, '"')           // quitar escapes simples de comillas
        .replace(/"{2,}/g, '"')         // limpiar comillas sobrantes otra vez
        .trim();

    // 3. Regex mejorado para separar laboratorios
    // Estructura: (9180,"{(86901,"FENOTIPO RH."),(86900,"GRUPO SANGUINEO"),...}")
    const regexLabs = /\((\d+),\s*"\{([^}]*)\}"\)/g;
    let match;
    let examenesPorLaboratorio = {};

    while ((match = regexLabs.exec(cadenaLimpia)) !== null) {
        const idLab = parseInt(match[1], 10);
        const examenesTexto = match[2];

        //4. Regex para extraer exámenes dentro de cada bloque
        const regexExamen = /\((\d+),\s*"([^"]+)"\)/g;
        let matchExamen;
        let examenes = [];

        while ((matchExamen = regexExamen.exec(examenesTexto)) !== null) {
            examenes.push({
                codigo: matchExamen[1],
                descripcion: matchExamen[2]
            });
        }
        examenesPorLaboratorio[idLab] = examenes;
    }

    //5. Obtenemos exámenes del laboratorio clickeado
    const examenesEncontrados = examenesPorLaboratorio[idLaboratorio] || [];

    if (examenesEncontrados.length === 0) {
        html = `<tr><td colspan="4" class="text-center">No hay resultados para este laboratorio</td></tr>`;
        $('#tblModalLaboratorio tbody').html(html);
        $('#modal-laboratorio-resultado').modal('show');
        return;
    }

    //6. Generamos la tabla con exámenes encontrados
    let i = 1;
    for (const examen of examenesEncontrados) {
        html += `<tr>
                    <td>${i}</td>
                    <td>${examen.codigo}</td>
                    <td>${examen.descripcion}</td>
                </tr>`;
        i++;
    }

    //7. Botón PDF dinámico
    html2 = `
        <button style="padding: 0.8rem 1rem;"
                class="btn btn-primary btn-xs verPdfVisorLaboratorio"
                id="verPdfVisorLaboratorio"
                data-id-laboratorio="${idLaboratorio}"
                data-nro-doc="${nroDoc}"
                data-fecha-toma-muestra="${fechaTomaMuestra}"
                data-cod-orden-lab="${codOrdenLab}">
            <i style="font-size: 2rem;" class="fa fa-search"></i>
        </button>`;

    //8. Actualizamos modal
    $('#tblModalLaboratorio tbody').html(html);
    $('#btnPdfLaboratorio').html(html2);
    $('#modal-laboratorio-resultado').modal('show');
  });


  $(document).on('click', '.verPdfVisorLaboratorio', function () {

  Swal.fire({
    title: '<div class="titulo-padding"><p>Cargando...</p></div>',
    allowOutsideClick: false,
    showConfirmButton: false,
    customClass: {
        header : 'swal2-header-generar'
    },
    onBeforeOpen: () => {
      $('#blur-container').append('<div class="blur-background"></div>');
      Swal.showLoading();
      $('#contenedorVisorPdsLaboratorio').html('');
      let html = '';

      

      const idLaboratorio = $(this).data('id-laboratorio');
      const nroDoc = $(this).data('nro-doc');
      const fechaTomaMuestra = $(this).data('fecha-toma-muestra');
      const codOrdenLab = $(this).data('cod-orden-lab');

      //html += `<object class="pdfView" type="application/pdf" data="/print_examen_laboratorio_lns/${idLaboratorio}/${$r->nroDoc}/${fechaTomaMuestra}/${codOrdenLab}" ></object>`
                                                                    //print_examen_laboratorio_lns/{id_laboratorio}/{dni_beneficiario}/{dia}/{mes}/{anio}/{nro_orden}', 'PdfController@print_examen_laboratorio_lns');
      html += `<object class="pdfView" type="application/pdf" data="/print_examen_laboratorio_lns/0/10131233/30/01/2024/${codOrdenLab}" ></object>`

      $('#contenedorVisorPdsLaboratorio').html(html);
      setTimeout(() => {
          Swal.hideLoading();
          Swal.fire({
              icon: 'success',
              title: 'Cargado correctamente!',
              showConfirmButton: false,
              timer: 1500,
              onClose: () => {
                  $('.blur-background').remove(); // Elimina el fondo desenfocado cuando se cierra la alerta
              }
          });
      }, 3000); // 3000 milisegundos = 3 segundos
    }
  });
  });
  /**************IMAGENES MODAL********************************/
 $('#tblIntervencionQx tbody').on('click', '.btn-modal-imagenes', function () {
    const data = $(this).data('examenes-array'); // cadena codificada
    if (!data) {
        console.warn("No hay datos de radiologías.");
        return;
    }

    // 1. Limpiar cadena
    const cadenaLimpia = decodeURIComponent(data)
        .replace(/\\/g, '')               // quitar backslashes
        .replace(/^\{\"|\}\"$/g, '')      // quitar comillas externas
        .trim();

    console.log("Cadena limpia:", cadenaLimpia);

    // 2. Regex para extraer cada radiología (lo que está dentro de paréntesis)
    const regexRad = /\((.*?)\)/g;
    let match;
    const examenesPorRadiologia = {};

    while ((match = regexRad.exec(cadenaLimpia)) !== null) {
        // match[1] = todo lo dentro del paréntesis
        const campos = match[1]
            .split(/,(?=(?:[^"]*"[^"]*")*[^"]*$)/) // divide respetando comas dentro de comillas
            .map(v => v.replace(/^"|"$/g, '').trim()); // quitar comillas externas y espacios

        // 3. Asignar cada campo y manejar vacíos
        const idRad = parseInt(campos[0], 10) || null;
        const id_cita = campos[1] || null;
        const id_pp = campos[2] || null;
        const id_estado_cita = campos[3] || null;
        const codigo_radiologia = campos[4] || null;
        const descripcion_corta = campos[5] || "";
        const id_servicio = campos[6] || null;
        const ruta_prestacion_procedimiento = campos[7] || "";
        const flag = campos[8] || null;
        const estado_atencion = campos[9] || null;

        // 4. Guardar en objeto por idRad
        if (!examenesPorRadiologia[idRad]) examenesPorRadiologia[idRad] = [];
        examenesPorRadiologia[idRad].push({
            idRad,
            id_cita,
            id_pp,
            id_estado_cita,
            codigo_radiologia,
            descripcion_corta,
            id_servicio,
            ruta_prestacion_procedimiento,
            flag,
            estado_atencion,
        });
    }

    console.log("Objeto examenesPorRadiologia:", examenesPorRadiologia);

    // 5. Construir tabla dinámica solo para el idRadiologia clickeado
    const idRadiologia = parseInt($(this).data('id-radiologia'), 10);
    const examenesEncontrados = examenesPorRadiologia[idRadiologia] || [];

    $('#tblModalImagenes tbody').html('');
    let html = '';

    if (examenesEncontrados.length === 0) {
        html = `<tr><td colspan="4" class="text-center">No hay imágenes para esta radiología</td></tr>`;
    } else {
        let i = 1;
        
        for (const examen of examenesEncontrados) {
            html += `<tr>
                        <td>${i}</td>
                        <td>${examen.codigo_radiologia || ''}</td>
                        <td>${examen.descripcion_corta}</td>
                        <td>
                            <button class="btn btn-default btn-xs verPdfVisor"
                                data-id-rad="${examen.idRad}"
                                data-id-pp="${examen.id_pp}"
                                data-id-servicio="${examen.id_servicio}"
                                data-flag="${examen.flag}"
                                data-ruta="${examen.ruta_prestacion_procedimiento}"
                                ${examen.ruta_prestacion_procedimiento ? '' : 'disabled'}>
                                <i class="fa fa-search"></i>
                            </button>
                        </td>
                     </tr>`;
            i++;
        }
    }

    $('#tblModalImagenes tbody').html(html);
    $('#modal-imagenes-resultado').modal('show');
});



  $(document).on('click', '.verPdfVisor', function () {

    Swal.fire({
      title: '<div class="titulo-padding"><p>Cargando...</p></div>',
      allowOutsideClick: false,
      showConfirmButton: false,
      customClass: {
          header : 'swal2-header-generar'
      },
      onBeforeOpen: () => {
        $('#blur-container').append('<div class="blur-background"></div>');
        Swal.showLoading();
        $('#contenedorVisorPds').html('');
        let html = '';

        const id_radiologia = $(this).data('id-rad');
        const id_servicio = $(this).data('id-servicio');
        const id_pp = $(this).data('id-pp');
        const ruta_pp = $(this).data('ruta');
        const flag = $(this).data('flag');
        const ruta = $(this).data('ruta');
                                                
        html += `<object class="pdfView" type="application/pdf" data="/informe_imagenes/${id_radiologia}/${id_servicio}/${id_pp}/0/${flag}/${ruta_pp}"></object>`

        $('#contenedorVisorPds').html(html);
        setTimeout(() => {
            Swal.hideLoading();
            Swal.fire({
                icon: 'success',
                title: 'Cargado correctamente!',
                showConfirmButton: false,
                timer: 1500,
                onClose: () => {
                    $('.blur-background').remove(); // Elimina el fondo desenfocado cuando se cierra la alerta
                }
            });
        }, 3000); // 3000 milisegundos = 3 segundos
      }
    });
  });

  $(".close-modal-imagenes").click(function() {
        $('#contenedorVisorPds').html('');
        $('#tblModalImagenes tbody').html('');
        $("#modal-imagenes-resultado").hide();
    });
  /*******************CONSUMO DE ALMACEN******************/

  $('#addFase1').on('click', function() {
      const addFase1 = $('#addFase1').attr("aria-pressed");
      $(".body-fase-1").hide();
      if (addFase1 == "false") {
          $(".body-fase-1").show();
      }
  });
  $('#addFase2').on('click', function() {
      const addFase2 = $('#addFase2').attr("aria-pressed");
      $(".body-fase-2").hide();
      if (addFase2 == "false") {
          $(".body-fase-2").show();
      }
  });
  $('#addFase3').on('click', function() {
      const addFase3 = $('#addFase3').attr("aria-pressed");
      $(".body-fase-3").hide();
      if (addFase3 == "false") {
          $(".body-fase-3").show();
      }
  });
  $('#addFase4').on('click', function() {
      const addFase3 = $('#addFase4').attr("aria-pressed");
      $(".body-fase-4").hide();
      if (addFase3 == "false") {
          $(".body-fase-4").show();
      }
  });
  $('#addFase5').on('click', function() {
      const addFase3 = $('#addFase5').attr("aria-pressed");
      $(".body-fase-5").hide();
      if (addFase3 == "false") {
          $(".body-fase-5").show();
      }
  });

  /**************FIN DE CONSUMO DE ALMACEN************/
  function ver_modal_hc(id_paciente){
    // Mostrar el loading con SweetAlert2
    Swal.fire({
        title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando toda la información del paciente</span></div>",
        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
        Swal.showLoading();
        }
    });
    $.ajax({
      url: '/mostrar_modal_hc_cqx/' + id_paciente,
      //async: false,
      success: function(result){
        $("#muestra-modal-hc").html(result);
      },
      complete: function () {
        // Cerrar el loading
        Swal.close();
        // Mostrar el modal
        $("#myModalHC").modal();
        },
      error: function () {
        Swal.fire('Error', 'Ocurrió un error al cargar la atención.', 'error');
      }
    });
  }

      function reporteAdmisionHopitalizacion(idUser) {
        Swal.fire({
            title: '<div class="titulo-padding"><p>Generando el reporte...</p></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                header : 'swal2-header-generar'
            },
            onBeforeOpen: () => {
                $('#blur-container').append('<div class="blur-background"></div>');
                //let id_establecimiento = $('#id_establecimiento').val();
                let fecha_ini = $('#fecha_ini').val();
                let fecha_fin = $('#fecha_fin').val();
                let dni_beneficiario = $('#dni_beneficiario').val();
                let nombre_beneficiario = $('#nombre_beneficiario').val();
                let estado = $('#estado').val();
                let id_servicio = $('#id_servicio').val();
                let id_tipo_atencion = $('#id_tipo_atencion').val();
                let fecha_ini_intervencion = $('#fecha_ini_intervencion').val();
                let fecha_fin_intervencion = $('#fecha_fin_intervencion').val();
                
                Swal.showLoading();
                $.ajax({
                    type: "POST",
                    url: "exportar_admision_hospitalizacion",
                    data: {
                        //"id_establecimiento" : id_establecimiento,
                        "fecha_ini" : fecha_ini,
                        "fecha_fin" : fecha_fin,
                        "dni_beneficiario" : dni_beneficiario,
                        "nombre_beneficiario" : nombre_beneficiario,
                        "estado" : estado,
                        "id_servicio" : id_servicio,
                        "id_tipo_atencion" : id_tipo_atencion,
                    },
                    success: function (response) {
                        console.log(response);
                        console.log(response.excelNameResponse);
                        if (response.excelNameResponse) {
                            // Obtener la URL del archivo del response
                            let fileUrl = `/download_reportes/${response.excelNameResponse}`;
                            console.log('********************');
                            console.log(fileUrl);
                            console.log('********************');
                            
                            // Crear un enlace de descarga
                            let link = document.createElement('a');
                            link.href = fileUrl;
                            link.download = 'Exportar_Reportes_de_Evaluacion_.xlsx';
                            link.innerText = 'Descargar archivo';
                            // Simular un clic en el enlace para iniciar la descarga
                            let divLinkReporte = document.getElementById('link-reporte');
                            divLinkReporte.appendChild(link);
                            link.click();
                            divLinkReporte.removeChild(link);
                            Swal.hideLoading();
                            Swal.fire({
                                icon: 'success',
                                title: 'El reporte se ha generado correctamente!',
                                showConfirmButton: false,
                                showConfirmButton: false,
                                timer: 1500,
                                onClose: () => {
                                    $('.blur-background').remove(); // Elimina el fondo desenfocado cuando se cierra la alerta
                                }
                            });
                        }
                    },
                    error: function() {
                        Swal.hideLoading();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al generar el reporte',
                            text: 'Por favor, inténtelo de nuevo más tarde',
                            confirmButtonText: 'Cerrar',
                            customClass: {
                                confirmButton : 'custom-swal-button-cerrar'
                            },
                            onClose: () => {
                                $('.blur-background').remove(); // Elimina el fondo desenfocado cuando se cierra la alerta
                            }
                        });
                    }
                });
            }
        });
    }
  
  // Abrir modal y cargar datos del paciente
$(document).on('click', '.editar-telefono', function () {
    let id = $(this).data('id');
    let dni = $(this).data('dni');
    let tel1 = $(this).data('telefono1');
    let tel2 = $(this).data('telefono2');
    let asegurado = $(this).data('asegurado');

    $('#id_paciente_edit').val(id);
    $('#dni_edit').val(dni);
    $('#telefono1_edit').val(tel1);
    $('#telefono2_edit').val(tel2);
    $('#asegurado').val(asegurado);

    $('#modalEditarTelefono').modal('show');
});

// Guardar los cambios con AJAX
$('#btnGuardarTelefono').on('click', function () {
    let id_asegurado = $('#id_paciente_edit').val();
    let telefono1 = $('#telefono1_edit').val();
    let telefono2 = $('#telefono2_edit').val();
    let asegurado = $('#asegurado').val();
    let dni = $('#dni_edit').val();
    

    Swal.fire({
        title: 'Actualizando datos...',
        text: 'Por favor espere un momento mientras se actualizan los teléfonos.',
        icon: 'info',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: '/actualizar-telefono',
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id_asegurado: id_asegurado,
            telefono1: telefono1,
            telefono2: telefono2
        },
        success: function (response) {
            Swal.close();

            if (response.success) {
                $('#modalEditarTelefono').modal('hide');
                Swal.fire({
                    title: 'Teléfonos actualizados',
                    text: 'Los datos del teléfono han sido actualizados correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });
                $('#id_paciente_edit').val('');
                $('#telefono1_edit').val('');
                $('#telefono2_edit').val('');
                $('#asegurado').val('');
                $('#dni_edit').val('');

                $('a.editar-telefono[data-id="' + id_asegurado + '"]')
                    .data('telefono1', telefono1)
                    .data('telefono2', telefono2)
                    .closest('td')
                    .find('strong')
                    .html(
                        "<b>Nombres:</b> "+asegurado+"<br><b>DNI</b> "+dni+"<br><b>Teléfono 1 :</b> " + (telefono1 || '') + " <br><b>Teléfono 2 :</b> " + (telefono2 || '')
                    );

            } else {
                Swal.fire({
                    title: 'No se pudo actualizar',
                    text: 'Ocurrió un problema al guardar los datos.',
                    icon: 'warning',
                    confirmButtonText: 'Cerrar'
                });
            }
        },
        error: function () {
            Swal.close();

            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un error al actualizar los teléfonos.',
                icon: 'error',
                confirmButtonText: 'Cerrar'
            });
        }
    });
});
$(document).ready(function() {
  $('#estado').on('change', function() {
    let valor = $(this).val();

    if (valor == '1') {
      $('#show_estado_pendiente').show(); 
    } else {
      $('#show_estado_pendiente').hide(); 
    }
  });
});


</script>
@endsection
@section('content')
<div id="addComponent"></div>
<div id="link-reporte"></div>
<div class="container-fluid">
  <div class="panel panel-primary filterable">
    <div class="panel-heading">
      <h3 class="panel-title">Lista Pendientes MOPRI</h3>
    </div>
    <div class="panel-body">
      <form class="form-horizontal" method="post" action="{{ route('listar_emergencia_a_hospitalizar_ajax')}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" value="{{$id_user}}" id="id_user">
        <div class="row">
          <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <label class="form-control-sm">Fecha Desde <span>(Fecha Solicitud)</span></label>
            <!-- <input class="form-control input-sm datepicker" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde"
              value="{{ $fechaDesde }}" maxlength="10"> -->
            <input class="form-control input-sm datepicker" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde"
               maxlength="10">
          </div>
          <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <label class="form-control-sm">Fecha Hasta <span>(Fecha Solicitud)</span></label>
            <!-- <input class="form-control input-sm datepicker" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta"
              value="{{ $fechaHasta }}" maxlength="10"> -->
            <input class="form-control input-sm datepicker" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta"
               maxlength="10">
          </div>
          <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <label class="form-control-sm">Fecha Desde <span>(Fecha Intervención)</span></label>
            <input class="form-control input-sm datepicker" type="text" name="fecha_ini_intervencion" id="fecha_ini_intervencion" placeholder="Fecha Desde"
              value="" maxlength="10">
          </div>
          <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <label class="form-control-sm">Fecha Hasta <span>(Fecha Intervención)</span></label>
            <input class="form-control input-sm datepicker" type="text" name="fecha_fin_intervencion" id="fecha_fin_intervencion" placeholder="Fecha Hasta"
              value="" maxlength="10">
          </div>
          <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <label class="form-control-sm">N° Documento</label>
            <input id="dni_beneficiario" name="dni_beneficiario" type="text" placeholder=""
              class="form-control input-sm" maxlength="12">
          </div>
          <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <label class="form-control-sm">Apellidos y Nombres</label>
            <input id="nombre_beneficiario" name="nombre_beneficiario" type="text" placeholder=""
              class="form-control input-sm" maxlength="100" style="">
          </div>
          <!-- <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <label>&nbsp;</label>
            <input id="btnBuscar" class="form-control btn btn-info input-sm" value="BUSCAR" ype="button">
          </div>
          <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12" id="opcion_exportar" name="opcion_exportar">
            <label>&nbsp;</label>
            <a href="javascript:void(0)" class="btn btn-success btn-block input-sm" id="btn_exportar_repo"
              name="btn_exportar_repo" onclick="reporteAdmisionHopitalizacion('{{ Auth::user()->id }}')">
              <i class="fa fa-file-excel-o" aria-hidden="true"></i> EXPORTAR</a>
          </div> -->
          
        </div>
        <div class="row">
          <!-- <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <label>Tipo Atención</label>
            <select id="id_tipo_atencion" name="id_tipo_atencion" class="form-control" style="width: 100%">
              <option value="1">Ambulatorio</option>
              <option value="">Emergencia</option>
              <option value="">Anulado</option>
              <option value="">Sin Admisión</option>
            </select>
          </div> -->
          <?php if(!empty(Auth::user()->flagfiscalizador)){ ?>
           <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
            <label>Médico</label>
            <select id="id_medico" name="id_medico" class="form-control" style="width: 100%">
              <option value="0">Seleccionar</option>
               @foreach($medicos_quirurgicos as $row)
              <option value="{{$row->id}}">{{$row->dni}} - {{$row->paterno}} {{$row->materno}} {{$row->nombre}}</option>
              @endforeach
            </select>
          </div>
          <?php } ?>

          <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
            <label>Servicio / Especialidad</label>
            <select id="id_servicio" name="id_servicio" class="form-control" style="width: 100%">
              <option value="0">Todos</option>
              <!-- <option value="">Cirugía General</option>
              <option value="">Reproducción Humana</option>
              <option value="">Traumatología</option> -->
              @foreach($upss as $row)
              <option value="{{$row->id_upss}}">{{$row->nombre}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <label>Tipo de servicio</label>
            <select id="id_tipo_atencion" name="id_tipo_atencion" class="form-control" style="width: 100%">
              <option value="0">Todos</option>
              <option value="1">AMBULATORIO</option>
              <option value="2">EMERGENCIA</option>
              <option value="3">HOSPITALIZACION</option>
            </select>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <label>Estado</label>
            <select id="estado" name="estado" class="form-control" style="width: 100%">
              <option value="3">Validado</option>
            </select>
          </div>
           <?php if(!empty(Auth::user()->flagfiscalizador)){ ?>
           <div id="show_estado_pendiente" class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
            <label>Estado (cardiología, neumología, anestesiología)</label>
            <select id="estado_pendiente" name="estado_pendiente" class="form-control" style="width: 100%">
              <option value="0">Seleccionar</option>
              <option value="I">Incompleto</option>
              <option value="O">Observado</option>
              <option value="C">Completo</option>
            </select>
          </div>
          <?php } ?>

          <div class="col-lg-1 col-md-2 col-sm-6 col-xs-12">
            <label>&nbsp;</label>
            <input id="btnBuscar" class="form-control btn btn-info input-sm" value="BUSCAR" type="button">
          </div>
          <div class="col-lg-1 col-md-2 col-sm-6 col-xs-12" id="opcion_exportar" name="opcion_exportar">
            <label>&nbsp;</label>
            <a href="javascript:void(0)" class="btn btn-success btn-block input-sm" id="btn_exportar_repo"
              name="btn_exportar_repo" onclick="reporteAdmisionHopitalizacion('{{ Auth::user()->id }}')">
              <i class="fa fa-file-excel-o" aria-hidden="true"></i> EXPORTAR</a>
          </div>
        </div>
      </form>
      <div id="global">
        <div class="table-responsive">
          <table id="tblIntervencionQx" class="table table-condensed table-bordered" style="width:100%">
            <thead>
              <tr>
                <th>Cod</th>
                <th>Origen de Atención</th>
                <th>Solicitud de intervención quirurgica</th>
                <th>programación de intervención quirurgica</th>
                <!-- <th>Numero Documento</th> -->
                <th width="250px;">Paciente</th>
                <!-- <th>Telefono</th> -->
                <th>Especialidad / tipo cirugía</th>
                <th width="250px;">Procedimiento</th>
                <!-- <th>Sala</th> -->
                <th>Fecha y Hora</th>
                <th>Duración</th>
                <th>Estado</th>
                <th>Atención</th>
                <th>Laboratorio</th>
                <th>Imágenes</th>
                <th>Banco de Sangre</th>
                <th>Cardiología</th>
                <th>Neumología</th>
                <th>Anestesiología</th>
                <th>Interconsultas</th>
                <th>HC</th>
                <th>Registro de CQ</th>
                <th>Validar</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <!-- SPINNER DE CARGA -->
          <div id="spinnerOverlay" style="
              display: none;
              position: fixed;
              top: 0;
              left: 0;
              width: 100%;
              height: 100%;
              background: rgba(255,255,255,0.7);
              z-index: 9999;
              text-align: center;
          ">
              <div style="position: relative; top: 40%;">
                  <i class="fa fa-spinner fa-spin fa-5x" style="color:#006400;"></i>
                  <p style="margin-top: 15px; font-size: 18px; color:#006400; font-weight: bold;">
                      Cargando datos, por favor espere...
                  </p>
              </div>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</div>


<script>

  function mostrarModalConAlmacen() {
    $('#modalListaConAlmacen').modal('show');
  }
  
  function mostrarModalReporteOperatorio() {
    $('#modal-reporte-operatorio').modal('show');
  }

  /****************ANESTESIOLOGIA*************/
  const mostrarModalAnestesiologia = (id_prestacion_pre_anestesia, id_intervencion) => {
    console.log(id_prestacion_pre_anestesia);
    Swal.fire({
        title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando datos de usuario</span></div>",
        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    
    const url = `{{ url('cqanestesia') }}`

    $.ajax({
        type: "GET",
        url: `${url}/${id_prestacion_pre_anestesia}/${id_intervencion}`,
        success: function(response) {
           Swal.fire({
              icon: "success",
              html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
              confirmButtonText: "ENTENDIDO",
              allowOutsideClick: false,
              allowEscapeKey: false,
              confirmButtonText: "ENTENDIDO",
              timer: 2000,
              timerProgressBar: true,
          });
          if(response){
            $('#modalAnestesiaBody').html(response);
            $('#modalAnestesia').modal('show');
          }else{
            Swal.fire({
              icon: 'error',
              title: 'Error de información',
              text: response.msg
            });
          }
        },
        error: function(xhr, status, error) {
          Swal.fire({
              icon: 'error',
              title: 'Error de información',
              text: "Error al obtener los datos: " + error
          });
        }
    });
    
  }

    // Modal Ver grafica
  function verGrafica(){
    $('#modalVerGrafica').modal('show');
  }

  function mostrarModalIntraoperatorio_antes() {
    console.log("Mostrando modal intraoperatorio");
    $('#modal-intraoperatorio').modal('show');
  }

  $('body').on('click', '#mostrarModalIntraoperatorio', function () {
    Swal.fire({
        title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando datos de usuario</span></div>",
        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onBeforeOpen: () => {
            Swal.showLoading();
        },
    });  
    const asegurado = JSON.parse(this.getAttribute('data-asegurado'));
    $.ajax({
        url: '/intraoperarotio_id_intervencion/' + asegurado.id_intervencion, 
        type: 'GET',
        success: function(response) {
          if(response.success==true){
            if(response.id_intraoperatorio){
              $.ajax({
                url: "/detalle_intraoperario/"+response.id_intraoperatorio,
                type: "GET",
                success: function(result) { 
                  Swal.close();
                  if(result.success ==false){
                    Swal.fire({
                              icon: 'error',
                              title: 'Error de información',
                              text: result.msg
                          });
                  }else{
                    $("#div-modal-detalle-intraoperatorio").html(result);
                    $('#modal-detalle-intraoperatorio').modal('show');
                  }
                }
              });
            }else{
              if(response.esta_admisionado){
                Swal.fire({
                    title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando datos de usuario</span></div>",
                    html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    },
                }); 
                 $.ajax({
                  url: "/registrar_intraoperario",
                  type: "POST",
                  data: {
                    asegurado: asegurado,
                    _token: $('input[name="_token"]').val()
                  },
                  success: function(result) { 
                    Swal.close();
                      Swal.fire({
                        icon: "success",
                        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
                        confirmButtonText: "ENTENDIDO",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        confirmButtonText: "ENTENDIDO",
                        timer: 2000,
                        timerProgressBar: true,
                    });
                    $("#div-modal-intraoperatorio").html(result);
                    $('#modal-intraoperatorio').modal('show');
                  }
                }); 
              }else{
                Swal.fire({
                  icon: "warning",
                  title: "Admisión pendiente",
                  html: `
                      <div style='margin: 10px 0px'>
                          <span style='color: #000000; font-size: 16px;'>
                              El paciente está <strong>pendiente de admisión</strong> en hospitalización. 
                              ¿Desea continuar bajo responsabilidad?
                          </span>
                      </div>
                  `,
                  showCancelButton: true,
                  confirmButtonText: "CONTINUAR",
                  cancelButtonText: "CANCELAR REGISTRO",
                  reverseButtons: true,
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  customClass: {
                      confirmButton: 'swal2-confirm btn btn-primary',
                      cancelButton: 'swal2-cancel btn btn-danger'
                  }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando datos de usuario</span></div>",
                            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                            showCancelButton: false,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            onBeforeOpen: () => {
                                Swal.showLoading();
                            },
                        }); 
                        
                        $.ajax({
                          url: "/registrar_intraoperario",
                          type: "POST",
                          data: {
                            asegurado: asegurado,
                            _token: $('input[name="_token"]').val()
                          },
                          success: function(result) { 
                            Swal.fire({
                                icon: "success",
                                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
                                confirmButtonText: "ENTENDIDO",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                confirmButtonText: "ENTENDIDO",
                                timer: 2000,
                                timerProgressBar: true,
                            });

                            $("#div-modal-intraoperatorio").html(result);
                            $('#modal-intraoperatorio').modal('show');
                          }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        console.log("Registro cancelado");
                    }
                });
              }
            }
          }else{
            Swal.fire({
              icon: 'error',
              title: 'Error de información',
              text: response.msg
            });
          }
        },
        error: function(xhr, status, error) {
          Swal.fire({
              icon: 'error',
              title: 'Error de información',
              text: "Error al obtener los datos: " + error
          });
        }
    });
  });
  $('body').on('click', '#mostrarModalHojaRecuperacion', function () {  
    Swal.fire({
        title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando datos de usuario</span></div>",
        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onBeforeOpen: () => {
            Swal.showLoading();
        },
    });
    const asegurado = JSON.parse(this.getAttribute('data-asegurado'));
    $.ajax({
        url: "/existe_intraoperatorio_paciente/"+asegurado.id_intervencion+'/'+asegurado.id_asegurado,
        type: "GET",
        success: function(result) { 
          if(result.success){
            
            if(result.hoja_recuperacion){
                let hoja_firmado = result.flag_firma;
                if (hoja_firmado!=1) {
                  // Mostrar modal personalizado con opciones si NO está firmado
                  Swal.fire({
                    title: 'Hoja de recuperación no firmada',
                    text: '¿Qué deseas hacer?',
                    icon: 'warning',
                    showCancelButton: true,
                    showDenyButton: true,
                    confirmButtonText: 'Ver Detalle / Firmar',
                    denyButtonText: 'Editar',
                    cancelButtonText: 'Cancelar',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                  }).then((resultSwal) => {
                    if (resultSwal.isConfirmed) {
                      // Opción: Ver Detalle
                      $.ajax({
                        url: "/detalle_hoja_recuperacion/" + result.id_hoja_recuperacion,
                        type: "GET",
                        success: function(result) {
                          if (result.success == false) {
                            Swal.fire({
                              icon: 'error',
                              title: 'Error de información',
                              text: result.msg
                            });
                          } else {
                            Swal.fire({
                              icon: "success",
                              html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargó la información correctamente.</span></div>",
                              confirmButtonText: "ENTENDIDO",
                              timer: 2000,
                              timerProgressBar: true,
                              allowOutsideClick: false,
                              allowEscapeKey: false
                            });
                            $("#div-modal-detalle-hojarecuperacion").html(result);
                            $('#modal-detalle-hojarecuperacion').modal('show');
                          }
                        }
                      });
                    } else if (resultSwal.isDenied) {
                       Swal.fire({
                          title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando datos de usuario</span></div>",
                          html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor...</span></div>",
                          showCancelButton: false,
                          showConfirmButton: false,
                          allowOutsideClick: false,
                          allowEscapeKey: false,
                          didOpen: () => {
                              Swal.showLoading();
                          },
                      });

                      $.ajax({
                        url: "/editar_hoja_recuperacion/" + result.id_hoja_recuperacion,
                        type: "GET",
                        success: function(response) {
                          Swal.close();
                          $("#div-modal-editar-hojarecuperacion").html(response);
                          $('#modal-editar-hojarecuperacion').modal('show');
                        }
                      });
                    }
                  });

                } else {
                  // Ya está firmado → solo muestra el detalle
                  $.ajax({
                    url: "/detalle_hoja_recuperacion/" + result.id_hoja_recuperacion,
                    type: "GET",
                    success: function(result) {
                      if (result.success == false) {
                        Swal.fire({
                          icon: 'error',
                          title: 'Error de información',
                          text: result.msg
                        });
                      } else {
                        Swal.fire({
                          icon: "success",
                          html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargó la información correctamente.</span></div>",
                          confirmButtonText: "ENTENDIDO",
                          timer: 2000,
                          timerProgressBar: true,
                          allowOutsideClick: false,
                          allowEscapeKey: false
                        });
                        $("#div-modal-detalle-hojarecuperacion").html(result);
                        $('#modal-detalle-hojarecuperacion').modal('show');
                      }
                    }
                  });
                }

            }else{
               //NUEVA HOJA DE RECUPERACION
              $.ajax({
                url: "/registrar_hoja_recuperacion",
                type: "POST",
                data: {
                  intraoperatorio:result.data,
                  asegurado:asegurado,
                  _token: $('input[name="_token"]').val()
                },
                success: function(result) { 
                  Swal.fire({
                    icon: "success",
                    html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
                    confirmButtonText: "ENTENDIDO",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonText: "ENTENDIDO",
                    timer: 2000,
                    timerProgressBar: true,
                  });
                  $("#div-modal-hoja-recuperacion").html(result);
                  $('#modal-hoja-recuperacion').modal('show');
                }
              });
            }  
          }else{
           Swal.fire({
                icon: 'warning',
                title: 'Error de información',
                text: result.msg,
                customClass: {
                    popup: 'smaller-swal' 
                }
            });
          }
        }
      });
  });

  function mostrarModalRegistroSeguridad() {
    $.ajax({
      url: "/registro_seguridad",
      type: "GET",
      success: function(result) { 
        $("#div-modal-registro-seguridad").html(result);
        $('#modal-registro-seguridad').modal('show');
      }
    });
  }

  function mostrarModalAtencion_Quirurgica(id_cita, id_intervencion, id_tipo_atencion, id_hospitalizacion,flag_atencion_qx,id_prestacion) {
        // Determinar si existe un registro previo basado en flag_atencion_qx
        if (flag_atencion_qx) {
            Swal.fire({
                title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando datos de usuario</span></div>",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor...</span></div>",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            // Cargar el detalle de atención quirúrgica
            $.ajax({
                url: "/detalle_atencion_quirurgica/"+id_intervencion+"/"+id_prestacion+"/"+id_cita+"/"+id_hospitalizacion,
                type: "GET",
                success: function (result) {
                    Swal.fire({
                        icon: "success",
                        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargó la información correctamente.</span></div>",
                        timer: 2000,
                        timerProgressBar: true,
                    });
                    // Insertar y mostrar el contenido del modal de detalle
                    $("#div-modal-detalle-atencion-quirurgica").html(result);
                    $('#modal-detalle-atencion-quirurgica').modal('show');

                     // Inicializa todos los selects con clase .select-tom
                      $('#modal-detalle-atencion-quirurgica .select-tom').each(function () {
                        new TomSelect(this, {
                          create: false,
                          allowEmptyOption: true,
                          placeholder: 'Seleccione una opción',
                          sortField: {
                            field: "text",
                            direction: "asc"
                          }
                        });
                      });
                },
                error: function () {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "No se pudo cargar el detalle de la atención quirúrgica.",
                    });
                },
            });
        }else{
          if(!id_hospitalizacion){
            Swal.fire({
              icon: "warning",
              title: "Admisión pendiente",
              html: `
                  <div style='margin: 10px 0px'>
                      <span style='color: #000000; font-size: 16px;'>
                          El paciente está <strong>pendiente de admisión</strong> en hospitalización. 
                          ¿Desea continuar bajo responsabilidad?
                      </span>
                  </div>
              `,
              showCancelButton: true,
              confirmButtonText: "CONTINUAR",
              cancelButtonText: "CANCELAR REGISTRO",
              reverseButtons: true,
              allowOutsideClick: false,
              allowEscapeKey: false,
              customClass: {
                  confirmButton: 'swal2-confirm btn btn-primary',
                  cancelButton: 'swal2-cancel btn btn-danger'
              }
            }).then((result) => {
                if (result.isConfirmed) {
                  Swal.fire({
                      title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando datos de usuario</span></div>",
                      html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor...</span></div>",
                      showCancelButton: false,
                      showConfirmButton: false,
                      allowOutsideClick: false,
                      allowEscapeKey: false,
                      didOpen: () => {
                          Swal.showLoading();
                      },
                  });
                  $.ajax({
                    url: `/modal_atencion_quirurgica/${id_cita}/${id_intervencion}/${id_tipo_atencion}`,
                    type: "GET",
                    success: function (result) {
                        Swal.close();
                        Swal.fire({
                            icon: "success",
                            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargó la información correctamente.</span></div>",
                            timer: 2000,
                            timerProgressBar: true,
                        });
                        // Insertar y mostrar el contenido del modal de atención quirúrgica inicial
                        $("#div-modal-atencion-quirurgica").html(result);
                        $('#modal-atencion-quirurgica').modal('show');
                        
                        // Inicializa todos los selects con clase .select-tom
                        $('#modal-atencion-quirurgica .select-tom').each(function () {
                          new TomSelect(this, {
                            create: false,
                            allowEmptyOption: true,
                            placeholder: 'Seleccione una opción',
                            sortField: {
                              field: "text",
                              direction: "asc"
                            }
                          });
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                      let mensaje = "Ocurrió un error inesperado.";
                      if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                          mensaje = jqXHR.responseJSON.message;
                      } else if (jqXHR.responseText) {
                          mensaje = jqXHR.responseText;
                      }
                      Swal.fire({
                          icon: "error",
                          title: "Error",
                          text: mensaje,
                      });
                    },
                  });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                   
                }
            });
           
          }else{
            Swal.fire({
                title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando datos de usuario</span></div>",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor...</span></div>",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            // No hay registro previo, cargar el modal inicial de atención quirúrgica
            $.ajax({
                url: `/modal_atencion_quirurgica/${id_cita}/${id_intervencion}/${id_tipo_atencion}`,
                type: "GET",
                success: function (result) {
                    Swal.fire({
                        icon: "success",
                        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargó la información correctamente.</span></div>",
                        timer: 2000,
                        timerProgressBar: true,
                    });
                    // Insertar y mostrar el contenido del modal de atención quirúrgica inicial
                    $("#div-modal-atencion-quirurgica").html(result);
                    $('#modal-atencion-quirurgica').modal('show');
                    
                    // Inicializa todos los selects con clase .select-tom
                    $('#modal-atencion-quirurgica .select-tom').each(function () {
                      new TomSelect(this, {
                        create: false,
                        allowEmptyOption: true,
                        placeholder: 'Seleccione una opción',
                        sortField: {
                          field: "text",
                          direction: "asc"
                        }
                      });
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                  let mensaje = "Ocurrió un error inesperado.";
                  if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                      mensaje = jqXHR.responseJSON.message;
                  } else if (jqXHR.responseText) {
                      mensaje = jqXHR.responseText;
                  }
                  Swal.fire({
                      icon: "error",
                      title: "Error",
                      text: mensaje,
                  });
                },
            });
          }
        }
   }    
  

    $('body').on('click', '#mostrarModalFormatoEscaneado', function () {
      // Mostrar mensaje de carga con SweetAlert
      Swal.fire({
          title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando datos de usuario</span></div>",
          html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
          showCancelButton: false,
          showConfirmButton: false,
          allowOutsideClick: false,
          allowEscapeKey: false,
          didOpen: () => {
              Swal.showLoading();
          },
      });

      // Obtener los datos del asegurado desde el atributo data-asegurado
      const asegurado = JSON.parse(this.getAttribute('data-asegurado'));

      $.ajax({
          url: "/registrar_formatos_escaneados",
          type: "POST",
          data: {
              asegurado: asegurado,
              _token: $('input[name="_token"]').val()
          },
          success: function(result) { 
            Swal.fire({
                icon: "success",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
                confirmButtonText: "ENTENDIDO",
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: "ENTENDIDO",
                timer: 2000,
                timerProgressBar: true,
            });
            $("#div-modal-formato-escaneado").html(result);
            $('#modal-formato-escaneado').modal('show');
          }
      });
  });


  function mostrarModalValidacionProgramacionQx(id_intervencion_quirurgica,id_tipo_atencion,estado) {
    if (estado == 1) {
      Swal.fire({
        title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando datos de usuario</span></div>",
        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
          Swal.showLoading();
        },
      });

      $.ajax({
        url: "/form_validacion_programacion_qx/" + id_intervencion_quirurgica+"/"+id_tipo_atencion,
        type: "GET",
        success: function(result) { 
          Swal.close();
          $("#div-modal-validacion-programacion").html(result);
          $('#modal-validacion-programacion').modal('show');
        },
        error: function () {
          Swal.close();
          Swal.fire('Error', 'Ocurrió un error al cargar los datos.', 'error');
        }
      });
    } else {
      Swal.fire({
        title: 'Alerta',
        text: 'La atención de Anestesiología es obligatoria para acceder a esta opción.',
        icon: 'warning',
        confirmButtonText: 'Aceptar'
      });
    }
  }


  function mostrarModalSuspenderIntervencion(tipo, id_intervencion_quirurgica, fecha, usuario, motivo) {
    if (tipo == 1) {
      // Modal registro
      $('#id_intervencion_quirurgica_suspender').val(id_intervencion_quirurgica);
      $('#seccion-registro-suspension').show();
      console.log('Registro');
      $('#suspended_fecha_registrado').val();
      $('#suspended_usuario_registrado').val();
      $('#suspended_motivo_registrado').val();
      $('#seccion-ver-motivo-suspension').hide();
    } else if (tipo == 2) {
      // Modal Ver
      $('#suspended_fecha_registrado').val(fecha);
      $('#suspended_usuario_registrado').val(usuario);
      $('#suspended_motivo_registrado').val(motivo);
      $('#seccion-ver-motivo-suspension').show();
      $('#seccion-registro-suspension').hide();
      console.log(motivo);
    }
    $('#modal-suspender-intervencion').modal('show');
  }

  $(document).ready(function(){

    $('#modalVerGrafica').on('hidden.bs.modal', function () {
      $('#modalAnestesia').modal('handleUpdate');
      $('body').addClass('modal-open'); 
    });

    $('#addInduccion').on('click', function () {
      var ariaPressed = $('#addInduccion').attr("aria-pressed");
      $("#fsaddInduccion").hide();
      if(ariaPressed == "false"){
        $("#fsaddInduccion").show();
      }
    });
    $('#addMantenimiento').on('click', function () {
      var ariaPressed = $('#addMantenimiento').attr("aria-pressed");
      $("#fsaddMantenimiento").hide();
      if(ariaPressed == "false"){
        $("#fsaddMantenimiento").show();
      }
    });
    $('#addFluidoTerapia').on('click', function () {
      var ariaPressed = $('#addFluidoTerapia').attr("aria-pressed");
      $("#fsaddFluidoTerapia").hide();
      if(ariaPressed == "false"){
        $("#fsaddFluidoTerapia").show();
      }
    });
    $('#addMetodoAnestesia').on('click', function () {
      var ariaPressed = $('#addMetodoAnestesia').attr("aria-pressed");
      $("#fsaddMetodoAnestesia").hide();
      if(ariaPressed == "false"){
        $("#fsaddMetodoAnestesia").show();
      }
    });
    $('#addObservacionesInduccion').on('click', function () {
      var ariaPressed = $('#addObservacionesInduccion').attr("aria-pressed");
      $("#fsaddObservacionesInduccion").hide();
      if(ariaPressed == "false"){
        $("#fsaddObservacionesInduccion").show();
      }
    });
    $('#addTecnicaAnestesia').on('click', function () {
      var ariaPressed = $('#addTecnicaAnestesia').attr("aria-pressed");
      $("#fsaddTecnicaAnestesia").hide();
      if(ariaPressed == "false"){
        $("#fsaddTecnicaAnestesia").show();
      }
    });
    $('#addTecnicaAnestesiaERCCB').on('click', function () {
      var ariaPressed = $('#addTecnicaAnestesiaERCCB').attr("aria-pressed");
      $("#fsaddTecnicaAnestesiaERCCB").hide();
      if(ariaPressed == "false"){
        $("#fsaddTecnicaAnestesiaERCCB").show();
      }
    });
    $('#addPosicionPaciente').on('click', function () {
      var ariaPressed = $('#addPosicionPaciente').attr("aria-pressed");
      $("#fsaddPosicionPaciente").hide();
      if(ariaPressed == "false"){
        $("#fsaddPosicionPaciente").show();
      }
    });
    $('#addQuirofano').on('click', function () {
      var ariaPressed = $('#addQuirofano').attr("aria-pressed");
      $("#fsaddQuirofano").hide();
      if(ariaPressed == "false"){
        $("#fsaddQuirofano").show();
      }
    });
    $('#addComplicacionesIntraoperatorio').on('click', function () {
      var ariaPressed = $('#addComplicacionesIntraoperatorio').attr("aria-pressed");
      $("#fsaddComplicacionesIntraoperatorio").hide();
      if(ariaPressed == "false"){
        $("#fsaddComplicacionesIntraoperatorio").show();
      }
    });
    $('#addAnalgesicosOtros').on('click', function () {
      var ariaPressed = $('#addAnalgesicosOtros').attr("aria-pressed");
      $("#fsaddAnalgesicosOtros").hide();
      if(ariaPressed == "false"){
        $("#fsaddAnalgesicosOtros").show();
      }
    });
    $('#addObservaciones').on('click', function () {
      var ariaPressed = $('#addObservaciones').attr("aria-pressed");
      $("#fsaddObservaciones").hide();
      if(ariaPressed == "false"){
        $("#fsaddObservaciones").show();
      }
    });
    $('#addBalanceHidrico').on('click', function () {
      var ariaPressed = $('#addBalanceHidrico').attr("aria-pressed");
      $("#fsaddBalanceHidrico").hide();
      if(ariaPressed == "false"){
        $("#fsaddBalanceHidrico").show();
      }
    });
    $('#addIngresoUrpa').on('click', function () {
      var ariaPressed = $('#addIngresoUrpa').attr("aria-pressed");
      $("#fsaddIngresoUrpa").hide();
      if(ariaPressed == "false"){
        $("#fsaddIngresoUrpa").show();
      }
    });
    $('#addCondicionAlta').on('click', function () {
      var ariaPressed = $('#addCondicionAlta').attr("aria-pressed");
      $("#fsaddCondicionAlta").hide();
      if(ariaPressed == "false"){
        $("#fsaddCondicionAlta").show();
      }
    });

    
  });
  /****************FIN ANESTESIOLOGIA*************/
  
  $(document).ready(function() {
    $('#switch1').on('click', function () {
      var switch1 = $('#switch1').attr("aria-pressed");
      $("#fsSwitch1").hide();
      if(switch1 == "false"){
        $("#fsSwitch1").show();
      }
    });

    $('#switch2').on('click', function () {
      var switch2 = $('#switch2').attr("aria-pressed");
      $("#fsSwitch2").hide();
      if(switch2 == "false"){
        $("#fsSwitch2").show();
      }
    });

    $('#addRecetaVale').on('click', function () {
		var addRecetaVale = $('#addRecetaVale').attr("aria-pressed");
		$("#fsRecetaVale").hide();
		if(addRecetaVale == "false"){
        // getProductoAndAddProducto();
        $("#fsRecetaVale").show();
        }
	  });
  });

  const datosPuntos =  [
        { x: 1, y: 3 },
        { x: 2, y: 5 },
        { x: 3, y: 7 },
        { x: 4, y: 10 },
        { x: 5, y: 12 },
        { x: 6, y: 15 },
        { x: 7, y: 8 },
        { x: 8, y: 3 },
        { x: 9, y: 2 }
      ];
    const datosPuntos2 =  [
        { x: 1, y: 3 },
        { x: 2, y: 5 },
        { x: 3, y: 7 },
        { x: 4, y: 10 },
        { x: 5, y: 12 },
        { x: 6, y: 15 },
        { x: 7, y: 17 },
        { x: 8, y: 19 },
        { x: 9, y: 21 }
      ];;
    const datosPuntos3 =  [
        { x: 1, y: 3 },
        { x: 2, y: 5 },
        { x: 3, y: 7 },
        { x: 4, y: 10 },
        { x: 5, y: 12 },
        { x: 6, y: 15 },
        { x: 7, y: 17 },
        { x: 8, y: 19 },
        { x: 9, y: 21 }
      ];
    const datosPuntos4 =  [
          { x: 1, y: 3 },
          { x: 2, y: 5 },
          { x: 3, y: 7 },
          { x: 4, y: 10 },
          { x: 5, y: 12 },
          { x: 6, y: 15 },
          { x: 7, y: 17 },
          { x: 8, y: 19 },
          { x: 9, y: 21 }
        ];

    function crearGrafico(id, datos, titulo) {
      const ctx = document.getElementById(id).getContext('2d');
      new Chart(ctx, {
          type: 'scatter',
          data: {
              datasets: [{
                  label: '',
                  data: datos,
                  backgroundColor: 'lightgreen',
                  borderColor: 'lightblue',
                  borderWidth: 2,
                  pointRadius: 5,
                  showLine: true
              }]
          },
          options: {
              plugins: {
                  title: {
                      display: true,
                      text: titulo, // El título personalizado
                      font: {
                          size: 16
                      },
                      color: '#333',
                      padding: {
                          top: 10, // Ajusta el espacio superior del título
                          bottom: 10 // Ajusta el espacio inferior del título
                      }
                  },
                  legend: {
                      display: false // Ocultar leyenda
                  }
              },
              layout: {
                  padding: {
                      top: 0, // Ajusta el espaciado superior del gráfico
                      bottom: 0, // Ajusta el espaciado inferior del gráfico
                      left: 0, // Ajusta el espaciado izquierdo del gráfico
                      right: 0 // Ajusta el espaciado derecho del gráfico
                  }
              },
              scales: {
                  x: {
                      beginAtZero: true
                  },
                  y: {
                      beginAtZero: true
                  }
              }
          }
      });
    }

  // Crear los gráficos de graficos de anestesia
  document.addEventListener('DOMContentLoaded', function() {
    crearGrafico('grafico1', datosPuntos, 'SIGNOS VITALES');
    crearGrafico('grafico2', datosPuntos2, 'INDUCCIÓN');
    crearGrafico('grafico3', datosPuntos3, 'MANTENIMIENTO');
    crearGrafico('grafico4', datosPuntos4, 'FLUIDOTERAPIA');
  });

  $('body').on('click', '#mostrarModalCitaQuirurgica', function () {
    Swal.fire({
        title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando datos</span></div>",
        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onBeforeOpen: () => {
            Swal.showLoading();
        },
    });

    const id_prestacion = this.getAttribute('data-id_prestacion');
    const id_cqx_cita = this.getAttribute('data-id_cqx_cita');
    const id_servicio = this.getAttribute('data-id_servicio');
    const id_cqx_intervencion = this.getAttribute('data-id_cqx_intervencion');
    $.ajax({
        url: '/formulario_cita_riesgo_quirurgico/' + id_prestacion+'/'+id_cqx_cita+'/'+id_servicio+'/'+id_cqx_intervencion,
        type: 'GET',
        success: function(response) {
         
            Swal.fire({
                icon: "success",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
                confirmButtonText: "ENTENDIDO",
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: "ENTENDIDO",
                timer: 2000,
                timerProgressBar: true,
            });
              $("#div-modal-cita-quirurgica").html(response);
              $('#modal-cita-quirurgica').modal('show');
          
        },
        error: function(xhr, status, error) {
          Swal.fire({
              icon: 'error',
              title: 'Error de información',
              text: "Error al obtener los datos: " + error
          });
        }
    });
  });

  $('body').on('click', '#mostrarModalCitaQuirurgicaDetalle', function () {
    Swal.fire({
        title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando datos</span></div>",
        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onBeforeOpen: () => {
            Swal.showLoading();
        },
    });

    const id_user = this.getAttribute('data-id_user');
    const id_cita = this.getAttribute('data-id_cita');
    $.ajax({
        url: '/ver_cita_por_medico_cqx/' + id_user+'/'+id_cita,
        type: 'GET',
        success: function(response) {
         
            Swal.fire({
                icon: "success",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
                confirmButtonText: "ENTENDIDO",
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: "ENTENDIDO",
                timer: 2000,
                timerProgressBar: true,
            });
              $("#div-modal-detalle-citas-cqx").html(response);
              $('#modal-detalle-citas-cqx').modal('show');
          
        },
        error: function(xhr, status, error) {
          Swal.fire({
              icon: 'error',
              title: 'Error de información',
              text: "Error al obtener los datos: " + error
          });
        }
    });
  });

  $('body').on('click', '#mostrarModalVerPrestacionDetalle', function () {
    Swal.fire({
        title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando datos</span></div>",
        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onBeforeOpen: () => {
            Swal.showLoading();
        },
    });
    const id_user = this.getAttribute('data-id_user');
    const id_prestacion = this.getAttribute('data-id_prestacion');
    $.ajax({
        url: '/ver_prestacion_cqx_sin_editar/' + id_user+'/'+id_prestacion,
        type: 'GET',
        success: function(response) {
         
            Swal.fire({
                icon: "success",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
                confirmButtonText: "ENTENDIDO",
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: "ENTENDIDO",
                timer: 2000,
                timerProgressBar: true,
            });
              $("#div-modal-ver-prestacion-cqx").html(response);
              $('#modal-ver-prestacion-cqx').modal('show');

        },
        error: function(xhr, status, error) {
          Swal.fire({
              icon: 'error',
              title: 'Error de información',
              text: "Error al obtener los datos: " + error
          });
        }
    });
  });

  //EMERGENCIA
  $('body').on('click', '#mostrarModalVerPrestacionDetalleAltaEmergencia', function () {
    Swal.fire({
        title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando datos</span></div>",
        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onBeforeOpen: () => {
            Swal.showLoading();
        },
    });
    const id_user = this.getAttribute('data-id_user');
    const id_prestacion = this.getAttribute('data-id_prestacion');
    $.ajax({
        url: '/ver_prestacion_alta_emergencia_cqx/' + id_user+'/'+id_prestacion,
        type: 'GET',
        success: function(response) {
         
            Swal.fire({
                icon: "success",
                html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
                confirmButtonText: "ENTENDIDO",
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: "ENTENDIDO",
                timer: 2000,
                timerProgressBar: true,
            });
              $("#div-modal-ver-prestacion-cqx").html(response);
              $('#modal-ver-prestacion-cqx').modal('show');
          
        },
        error: function(xhr, status, error) {
          Swal.fire({
              icon: 'error',
              title: 'Error de información',
              text: "Error al obtener los datos: " + error
          });
        }
    });
  });
  


</script>

<div class="modal fade" id="modal-reporte-operatorio" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <b>REPORTE OPERATORIO</b>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                      <div class="col-md-2">
                      </div>
                      <div class="col-md-2">
                        <label for="">REPORTE OPERATORIO</label>
                      </div>
                      <div class="col-md-1">
                        <label for="">DNI:</label>
                      </div>
                      <div class="col-md-1">
                        <p>11555221</p>
                      </div>
                      <div class="col-md-1">
                        <label for="">CIP:</label>
                      </div>
                      <div class="col-md-1">
                        <p>11555221</p>
                      </div>
                      <div class="col-md-1">
                        <label for="">HC:</label>
                      </div>
                      <div class="col-md-1">
                        <p>11555221</p>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-2">
                        <label for="">PACIENTE:</label>
                      </div>
                      <div class="col-md-6">
                        <p>CARRASCO ROMERO CARLA</p>
                      </div>
                      <div class="col-md-1">
                        <label for="">EDAD:</label>
                      </div>
                      <div class="col-md-1">
                        <p>36</p>
                      </div>
                      <div class="col-md-1">
                        <label for="">SEXO:</label>
                      </div>
                      <div class="col-md-1">
                        <p>F</p>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-2">
                        <label for="">SERVICIO:</label>
                      </div>
                      <div class="col-md-4">
                        <p>GINECOLOGIA</p>
                      </div>
                      <div class="col-md-1">
                        <label for="">N° CAMA:</label>
                      </div>
                      <div class="col-md-1">
                        <p>16</p>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-2">
                        <label for="">OPERACIÓN:</label>
                      </div>
                      <div class="col-md-4">
                        <p>PROGRAMADA</p>
                      </div>
                      <div class="col-md-2">
                        <label for="">FECHA:</label>
                      </div>
                      <div class="col-md-4">
                        <p>12/10/2024</p>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-12">
                        <label for="">DIAGNÓSTICOS PRE-OPERATORIO:</label>
                      </div>
                      <div class="col-md-12">
                        <table class="table table-condensed">
                          <tr>
                            <th>N°</th>
                            <th>CÓDIGO</th>
                            <th>DESCRIPCIÓN</th>
                          </tr>
                          <tr>
                            <td>1</td>
                            <td>N81.1</td>
                            <td>CISTOCELE</td>
                          </tr>
                          <tr>
                            <td>2</td>
                            <td>R15.X</td>
                            <td>INCONTINGENCIA FECA</td>
                          </tr>
                        </table>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-2">
                        <label for="">OPERACIÓN PROPUESTA:</label>
                      </div>
                      <div class="col-md-10">
                        <p>CORRECCION SITIO ESPECIFICO + CAP</p>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-2">
                        <label for="">OPERACIÓN EFECTUADA:</label>
                      </div>
                      <div class="col-md-10">
                        <p>CORRECCION SITIO ESPECIFICO + CAP</p>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-2">
                        <label for="">HALLAZGOS:</label>
                      </div>
                      <div class="col-md-10">
                        <p>POPQ: +3|+3|-2 || 5 | 2-5 |</p>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-12">
                        <label for="">PROCEDIMIENTO:</label>
                      </div>
                      <div class="col-md-12">
                        <table class="table table-condensed">
                          <tr>
                            <th>N°</th>
                            <th>DESCRIPCIÓN</th>
                          </tr>
                          <tr>
                            <td>1</td>
                            <td>DORSOLITOTOMIA</td>
                          </tr>
                          <tr>
                            <td>2</td>
                            <td>LAVADO DE SITIO OPERATORIO</td>
                          </tr>
                          <tr>
                            <td>3</td>
                            <td>IDENTIFICACION DE HALLAZGOS</td>
                          </tr>
                        </table>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-6">
                        <label for="">COMPLICACIONES DURANTE LA INTERVECIÓN QUIRURGICA:</label>
                      </div>
                      <div class="col-md-6">
                        <p>NINGUNA </p>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-2">
                        <label for="">HORA DE INICIO:</label>
                      </div>
                      <div class="col-md-2">
                        <p>16:51</p>
                      </div>
                      <div class="col-md-2">
                        <label for="">HORA DE TÉRMINO:</label>
                      </div>
                      <div class="col-md-2">
                        <p>18:00</p>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-12">
                        <label for="">PROFESIONALES PARTICIPANTES:</label>
                      </div>
                      <div class="col-md-12">
                        <table class="table table-condensed">
                          <tr>
                            <th>N°</th>
                            <th>CARGO</th>
                            <th>PROFESIONAL</th>
                          </tr>
                          <tr>
                            <td>1</td>
                            <td>MOCKPLUS-TABLE-COPIED</td>
                            <td>DRA. RAMIREZ</td>
                          </tr>
                          <tr>
                            <td>2</td>
                            <td>PRIMER ASISTENTE</td>
                            <td>MR 3 ROMERO</td>
                          </tr>
                          <tr>
                            <td>3</td>
                            <td>ANESTESIOLOGO</td>
                            <td>DRA CARRANZA</td>
                          </tr>
                          <tr>
                            <td>4</td>
                            <td>ENFERMERA INSTRUMENTISTA</td>
                            <td>LIC. GUEVARA</td>
                          </tr>
                          <tr>
                            <td>5</td>
                            <td>ENFERMERA CIRCULANTE</td>
                            <td>LIC CABRERA</td>
                          </tr>
                        </table>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-2">
                        <label for="">PIEZA OPERATORIA:</label>
                      </div>
                      <div class="col-md-10">
                        <p>NINGUNA</p>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-2">
                        <label for="">CONTEO DE GASAS:</label>
                      </div>
                      <div class="col-md-10">
                        <p>SI</p>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-2">
                        <label for="">OBSERVACIONES:</label>
                      </div>
                      <div class="col-md-10">
                        <p>NINGUNA</p>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-md-2 pull-right">
                        <button class="btn btn-sm btn-primary form-control">FIRMAR</button>
                      </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal ANESTESIA -->
<div class="modal fade" id="modalAnestesia" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="width:80%">
        <div class="modal-content">
            <div class="modal-header" style="font-size: 20px !important; padding: 0.3rem 1rem !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b>ANESTESIA</b>
            </div>
            <div class="modal-body" id="modalAnestesiaBody"></div>
            
        </div>
    </div>
</div>

<!-- Modal CONSUMO DE ALMACEN -->
<div class="modal fade" id="modalListaConAlmacen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="width:80%;">
    <div class="modal-content">
        <div class="modal-header" style="font-size: 20px !important;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <b>LISTADO DE CONSUMO DE ALMACÉN</b>
        </div>
        <div style=" font-size: 11px" class="panel-body">
          <form name="frmDatoAlmacenConsumo" id="frmDatoAlmacenConsumo" class="form-horizontal" role="form"
              method="post">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                  <label class="form-control-sm">FECHA :</label>
                  <input class="form-control input-sm" type="text" name="fecha" id="fecha" placeholder=""
                   value="15-08-2024" disabled>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                  <label class="form-control-sm">NOMBRE DEL PACIENTE :</label>
                  <input class="form-control input-sm" type="text" name="nombre_paciente" id="nombre_paciente" placeholder=""
                   value="GOMEZ DIAS YHOEL" disabled>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                  <label class="form-control-sm">Nª DE CIP :</label>
                  <input class="form-control input-sm" type="text" name="numero_cip" id="numero_cip" placeholder=""
                   value="45435345" disabled>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                  <label class="form-control-sm">GRADO :</label>
                  <input class="form-control input-sm" type="text" name="grado" id="grado" placeholder=""
                   value="MAYOR" disabled>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12" style="margin-top: 16px;">
                  <label class="form-control-sm">OPERACIÒN :</label>
                  <input class="form-control input-sm" type="text" style="width: 213%;" name="operacion" id="operacion" placeholder="">
                </div>
              </div>

              <div class="panel panel-info contenedor-datos-generales" style="margin-top: 16px;">
                <div class="panel-heading2">
                  <div class="row">
                    <div class="col-md-2">
                      <h3 class="panel-title" style="display: inline;">
                        ANESTESIA
                      </h3>
                    </div>
                    <div class="col-dm-2">
                      <button tabindex="90" type="button" id="addFase1"
                        class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false"
                        autocomplete="off">
                        <div class="handle"></div>
                    </button>
                    </div>
                  </div>
                </div>
                <div class="panel-body body-fase-1" style="display: none;">
                    <div class="col-lg-12">
                        <div class="panel-heading2" style="background:#dfd5d5;">
                            <h3 class="panel-title" style="display: inline;color:black;">
                              DESCRIPCION
                            </h3>
                           <span class="cant" style="color:black;">CANT.</span>
                        </div>
                        <div class="panel-body body-fase-1" style="display: none;">
                            <div class="col-lg-12">
                                <table class="table">
                                    <tr>
                                      <td>1.- SET DESCARTABLE</td>
                                      <td><input class="form-control cant-input-sm" type="number" value="2"></td>
                                    </tr>
                                    <tr>
                                      <td>2.- CIRCULO COACCIAL</td>
                                      <td><input class="form-control cant-input-sm" type="number" value="4"></td>
                                    </tr>
                                    <tr>
                                      <td>3.- AGUJA EPIDURAL</td>
                                      <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                    </tr>
                                    <tr>
                                      <td>4.- GUANTES DE PROCEDIMIENTO</td>
                                      <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                    </tr>
                                    <tr>
                                      <td>5.- TUBOS ENDOTRAQUEAL</td>
                                      <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                    </tr>
                                    <tr>
                                      <td>6.- TUBOS ENDOTRAQUEAL ANILLADO</td>
                                      <td><input class="form-control cant-input-sm" type="number" value="2"></td>
                                    </tr>
                                    <tr>
                                      <td>7.- CONTENEDOR DE RESIDUOS</td>
                                      <td><input class="form-control cant-input-sm" type="number" value="3"></td>
                                    </tr>
                                    <tr>
                                      <td>8.- EQUIPO DE INF EV PARA BOMBA</td>
                                      <td><input class="form-control cant-input-sm" type="number" value="6"></td>
                                    </tr>
                                    <tr>
                                      <td>9.- SENSOR VIS</td>
                                      <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                    </tr>
                                    <tr>
                                      <td>10.- MASCARA LARINGEA</td>
                                      <td><input class="form-control cant-input-sm" type="number" value="2"></td>
                                    </tr>
                                    <tr>
                                      <td>11.- MASCARARILLA DE OXIGENO</td>
                                      <td><input class="form-control cant-input-sm" type="number" value="3"></td>
                                    </tr>
                                    <tr>
                                      <td>12.- TUBOS DE MAYO</td>
                                      <td><input class="form-control cant-input-sm" type="number" value="6"></td>
                                    </tr>
                                    <tr>
                                      <td>13.- CAL BARITADA O CAL SODADA</td>
                                      <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                    </tr>
                                    <tr>
                                      <td>14.- LARINGOSCOPIO OPTICO Nª3</td>
                                      <td><input class="form-control cant-input-sm" type="number" value="2"></td>
                                    </tr>
                                  </table>
                            </div>
                        </div>
                    </div>
                </div>
              </div>

              <div class="panel panel-info contenedor-datos-generales">
                <div class="panel-heading2">
                  <div class="row">
                    <div class="col-md-2">
                      <h3 class="panel-title" style="display: inline;">
                        LAPAROSCOPIO
                      </h3>
                    </div>
                    <div class="col-dm-2">
                      <button tabindex="90" type="button" id="addFase2"
                        class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false"
                        autocomplete="off">
                        <div class="handle"></div>
                    </button>
                    </div>
                  </div>
                </div>
                <div class="panel-body body-fase-2" style="display: none;">
                    <div class="panel panel-default contenedor-datos-generales">
                      <div class="panel-heading2" style="background:#dfd5d5;">
                        <h3 class="panel-title" style="display: inline;color:black;">
                                DESCRIPCION
                            </h3>
                            <span class="cant" style="color: black;">CANT.</span>
                        </div>
                        <div class="panel-body body-fase-2">
                            <div class="col-lg-12">
                                <table class="table">
                                  <tr>
                                    <td>1.- FILTRO DE CO2</td>
                                    <td><input class="form-control cant-input-sm" type="number" value="2"></td>
                                  </tr>
                                  <tr>
                                    <td>2.- FILTRO TROCAR</td>
                                    <td><input class="form-control cant-input-sm" type="number" value="4"></td>
                                  </tr>
                                  <tr>
                                    <td>3.- MANGA LAPARASCOPICA</td>
                                    <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                  </tr>
                                  <tr>
                                    <td>4.- REDUCTOR DE TROCAR</td>
                                    <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                  </tr>
                                  <tr>
                                    <td>5.- CLIPS DE LAPARASCOPIA</td>
                                    <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                  </tr>
                                  <tr>
                                    <td>6.- PINZA MERILAN</td>
                                    <td><input class="form-control cant-input-sm" type="number" value="2"></td>
                                  </tr>
                                  <tr>
                                    <td>7.- TIJERA LAPARASCOPIA</td>
                                    <td><input class="form-control cant-input-sm" type="number" value="3"></td>
                                  </tr>
                                  <tr>
                                    <td>8.- PINZA ENDOCLINCH</td>
                                    <td><input class="form-control cant-input-sm" type="number" value="6"></td>
                                  </tr>
                                  <tr>
                                    <td>9.- AUTO SUTURAS</td>
                                    <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                  </tr>
                                  <tr>
                                    <td>10.- AGUJA PARA NEUMOPERITONEO</td>
                                    <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                  </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
              </div>

              <div class="panel panel-info contenedor-datos-generales">
                <div class="panel-heading2">
                    <div class="row">
                      <div class="col-md-2">
                        <h3 class="panel-title" style="display: inline;">
                          CIRUGIA CARDIACA
                        </h3>
                      </div>
                      <div class="col-dm-2">
                        <button tabindex="90" type="button" id="addFase3"
                        class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false"
                        autocomplete="off">
                        <div class="handle"></div>
                      </button>
                      </div>
                    </div>
                </div>
                <div class="panel-body body-fase-3" style="display: none;">
                    <div class="col-lg-12">
                      <div class="panel-heading2" style="background:#dfd5d5;">
                        <h3 class="panel-title" style="display: inline;color:black;">
                                DESCRIPCION
                            </h3>
                            <span class="cant" style="color: black;">CANT.</span>
                        </div>
                        <div class="panel-body body-fase-3">
                            <div class="col-lg-12">
                                <table class="table">
                                  <tr>
                                    <td>1.- OXIGENADOR DE MENBRANA</td>
                                    <td><input class="form-control cant-input-sm" type="number" value="2"></td>
                                  </tr>
                                  <tr>
                                    <td>2.- SET DE TUBULADURAS</td>
                                    <td><input class="form-control cant-input-sm" type="number" value="4"></td>
                                  </tr>
                                  <tr>
                                    <td>3.- HEMOCONCENTRADOR</td>
                                    <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                  </tr>
                                  <tr>
                                    <td>4.- CLIPS DE MAMARIA</td>
                                    <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                  </tr>
                                  <tr>
                                    <td>5.- CARTUCHO DE GASES ARTERIALES</td>
                                    <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                  </tr>
                                  <tr>
                                    <td>6.- SET DE MONITOREO CON DOMO</td>
                                    <td><input class="form-control cant-input-sm" type="number" value="2"></td>
                                  </tr>
                                  <tr>
                                    <td>7.- TUBO DE HEMOCROM</td>
                                    <td><input class="form-control cant-input-sm" type="number" value="3"></td>
                                  </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
              </div>

              <div class="panel panel-info contenedor-datos-generales">
                <div class="panel-heading2">
                  <div class="row">
                    <div class="col-md-2">
                      <h3 class="panel-title" style="display: inline;">
                        USO MULTIPLE
                      </h3>
                    </div>
                    <div class="col-dm-2">
                      <button tabindex="90" type="button" id="addFase4"
                        class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false"
                        autocomplete="off">
                        <div class="handle"></div>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="panel-body body-fase-4" style="display: none;">
                  <div class="col-lg-12">
                      <div class="panel-heading2" style="background:#dfd5d5;">
                          <h3 class="panel-title" style="display: inline;color:black;">
                              DESCRIPCION
                          </h3>
                          <span class="cant" style="color:black;">CANT.</span>
                      </div>
                      <div class="panel-body body-fase-4">
                          <div class="col-lg-12">
                              <table class="table">
                                <tr>
                                  <td>1.- FILTRO DE CO2</td>
                                  <td><input class="form-control cant-input-sm" type="number" value="2"></td>
                                </tr>
                                <tr>
                                  <td>2.- FILTRO TROCAR</td>
                                  <td><input class="form-control cant-input-sm" type="number" value="4"></td>
                                </tr>
                                <tr>
                                  <td>3.- MANGA LAPARASCOPICA</td>
                                  <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                </tr>
                                <tr>
                                  <td>4.- REDUCTOR DE TROCAR</td>
                                  <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                </tr>
                                <tr>
                                  <td>5.- CLIPS DE LAPARASCOPIA</td>
                                  <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                </tr>
                                <tr>
                                  <td>6.- PINZA MERILAN</td>
                                  <td><input class="form-control cant-input-sm" type="number" value="2"></td>
                                </tr>
                                <tr>
                                  <td>7.- TIJERA LAPARASCOPIA</td>
                                  <td><input class="form-control cant-input-sm" type="number" value="3"></td>
                                </tr>
                                <tr>
                                  <td>8.- PINZA ENDOCLINCH</td>
                                  <td><input class="form-control cant-input-sm" type="number" value="6"></td>
                                </tr>
                                <tr>
                                  <td>9.- AUTO SUTURAS</td>
                                  <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                </tr>
                                <tr>
                                  <td>10.- AGUJA PARA NEUMOPERITONEO</td>
                                  <td><input class="form-control cant-input-sm" type="number" value="2"></td>
                                </tr>
                                <tr>
                                  <td>1.- FILTRO DE CO2</td>
                                  <td><input class="form-control cant-input-sm" type="number" value="2"></td>
                                </tr>
                                <tr>
                                  <td>2.- FILTRO TROCAR</td>
                                  <td><input class="form-control cant-input-sm" type="number" value="4"></td>
                                </tr>
                                <tr>
                                  <td>3.- MANGA LAPARASCOPICA</td>
                                  <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                </tr>
                                <tr>
                                  <td>4.- REDUCTOR DE TROCAR</td>
                                  <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                </tr>
                                <tr>
                                  <td>5.- CLIPS DE LAPARASCOPIA</td>
                                  <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                </tr>
                                <tr>
                                  <td>6.- PINZA MERILAN</td>
                                  <td><input class="form-control cant-input-sm" type="number" value="2"></td>
                                </tr>
                                <tr>
                                  <td>7.- TIJERA LAPARASCOPIA</td>
                                  <td><input class="form-control cant-input-sm" type="number" value="3"></td>
                                </tr>
                                <tr>
                                  <td>8.- PINZA ENDOCLINCH</td>
                                  <td><input class="form-control cant-input-sm" type="number" value="6"></td>
                                </tr>
                                <tr>
                                  <td>9.- AUTO SUTURAS</td>
                                  <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                </tr>
                                <tr>
                                  <td>10.- AGUJA PARA NEUMOPERITONEO</td>
                                  <td><input class="form-control cant-input-sm" type="number" value="1"></td>
                                </tr>
                              </table>
                          </div>
                      </div>
                  </div>
               </div>
              </div>

              <div class="panel panel-info contenedor-datos-generales">
                <div class="panel-heading2">
                  <div class="row">
                    <div class="col-md-2">
                      <h3 class="panel-title" style="display: inline;">
                        ADICIONAL
                      </h3>
                    </div>
                    <div class="col-dm-2">
                      <button tabindex="90" type="button" id="addFase5"
                      class="btn btn-lg btn-toggle" data-toggle="button" aria-pressed="false"
                      autocomplete="off">
                      <div class="handle"></div>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="panel-body body-fase-5" style="display: none;">
                  <button type="button" class="btn btn-success" style="margin-bottom: 20px;">AGREGAR</button>
                  <div class="col-lg-12" style="margin-left:-15px;margin-top:-6px;">
                    <div class="panel-heading2" style="background:#dfd5d5;">
                      <h3 class="panel-title" style="display: inline;color:black;">
                              DESCRIPCION
                          </h3>
                          <span class="cant" style="color:black;">CANT.</span>
                      </div>
                    </div>
                 </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success" id="RegistrarDatoAlmacenConsumo"
                    value="create">Registrar</button>
              </div>
          </form>
       </div>          
     </div>
  </div>
</div>

<!-- Modal VER GRAFICA -->
<div class="modal fade" id="modalVerGrafica" tabindex="-1" role="dialog" aria-labelledby="modalVerGraficaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 1100px;" role="document">
    <div class="modal-content">
      <div class="modal-header" style="font-size: 20px !important;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
        <b>GRAFICOS DE ANESTESIA</b>
      </div>
      <div class="panel-body" id="collapseprestacionespaciente">
        <div class="form-group">
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
          <div class="grafico-container">
              <!-- Gráfico 1 -->
              <div class="grafico-item1">
                  <canvas id="grafico1"></canvas>
              </div>
              <!-- Gráfico 2 -->
              <div class="grafico-item2">
                  <canvas id="grafico2"></canvas>
              </div>
              <!-- Gráfico 3 -->
              <div class="grafico-item2">
                  <canvas id="grafico3"></canvas>
              </div>
              <!-- Gráfico 4 -->
              <div class="grafico-item2">
                  <canvas id="grafico4"></canvas>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal INTRAOPERATORIO -->
<div class="modal fade" id="modal-intraoperatorio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document" style="width:80%">
        <div class="modal-content">
            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">REGISTRO DE ENFERMERIA INTRAOPERARIO</h4>
            </div>
            <div class="modal-body">
              <div id="div-modal-intraoperatorio"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-detalle-intraoperatorio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document" style="width:70%">
      <div class="modal-content">
          <div class="modal-header" style="font-size: 20px !important;">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">DETALLE INTRAOPERARIO</h4>
          </div>
          <div class="modal-body">
            <div id="div-modal-detalle-intraoperatorio"></div>
          </div>
      </div>
  </div>
</div>
<div class="modal fade" id="modal-detalle-hojarecuperacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document" style="width:50%">
      <div class="modal-content">
          <div class="modal-header" style="font-size: 20px !important;">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">REGISTRO DE HOJA DE RECUPERACIÓN</h4>
          </div>
          <div class="modal-body">
            <div id="div-modal-detalle-hojarecuperacion"></div>
          </div>
      </div>
  </div>
</div>

<div class="modal fade" id="modal-editar-hojarecuperacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document" style="width:50%">
      <div class="modal-content">
          <div class="modal-header" style="font-size: 20px !important;">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">EDITAR HOJA DE RECUPERACIÓN</h4>
          </div>
          <div class="modal-body">
            <div id="div-modal-editar-hojarecuperacion"></div>
          </div>
      </div>
  </div>
</div>

<!-- Modal REGISTRO SEGURIDAD -->
<div class="modal fade" id="modal-registro-seguridad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document" style="width:70%">
        <div class="modal-content">
            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">MÓDULO QUIRÚRGICO</h4>
            </div>
            <div class="modal-body">
              <div id="div-modal-registro-seguridad"></div>
            </div>
        </div>
    </div>
</div>


<!-- Modal FORMATOS ESCANEADOS -->
<div class="modal fade" id="modal-formato-escaneado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document" style="width:70%">
        <div class="modal-content">
            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">FORMATOS ESCANEADOS</h4>
            </div>
            <div class="modal-body">
              <div id="div-modal-formato-escaneado">
              </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal RESPUESTA DE IMAGENES -->
<div class="modal fade" id="modal-imagenes-resultado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document" style="width:70%">
        <div class="modal-content">
            <div class="modal-header" style="font-size: 20px !important; padding: 0.3rem 1rem !important;">
                <button type="button" class="close close-modal-imagenes" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">RESULTADOS DE IMAGENES</h4>
            </div>
            <div class="modal-body">
              <div class="panel-body">
                <div class="form-group">
                  <div class="col-md-12">
                      <table class="table table-striped" id="tblModalImagenes">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">CÓDIGO</th>
                            <th scope="col">DESCRIPCIÓN</th>
                            <th scope="col"></th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-12">
                      <label for="">VISOR DE PDFS</label>
                      <div id="contenedorVisorPds">
                      </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
    </div>
</div>

<!-- Modal RESPUESTA DE LABORATORIO -->
<div class="modal fade" id="modal-laboratorio-resultado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document" style="width:70%">
        <div class="modal-content">
            <div class="modal-header" style="font-size: 20px !important; padding: 0 !important;">
                <button type="button" class="close close-modal-laboratorio" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">RESULTADOS DE LABORATORIO</h4>
            </div>
            <div class="modal-body">
              <div class="panel-body">
                <div class="form-group">
                  <div class="col-md-12">
                    <div id="btnPdfLaboratorio"></div>
                      <table class="table table-striped" id="tblModalLaboratorio">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">CÓDIGO</th>
                            <th scope="col">DESCRIPCIÓN</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-12">
                      <label for="">VISOR DE PDFS</label>
                      <div id="contenedorVisorPdsLaboratorio">
                      </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
    </div>
</div>

<!-- Modal ATENCION QUIRURGICA -->
<div class="modal fade" id="modal-atencion-quirurgica" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="width:85%;">
      <div class="modal-content">
          <div class="modal-header" style="font-size: 20px !important;">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">MÓDULO DE CENTRO QUIRÚRGICO - ESPECIALISTA QUIRÚRGICO</h4>
          </div>
          <div class="modal-body">
            <div id="div-modal-atencion-quirurgica"></div>
          </div>
      </div>
  </div>
</div>
<div class="modal fade" id="modal-detalle-atencion-quirurgica" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="width:70%;">
    <div class="modal-content">
        <div class="modal-header" style="font-size: 20px !important;">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">MÓDULO DE CENTRO QUIRÚRGICO - ESPECIALISTA QUIRÚRGICO</h4>
          </div>
          <div class="modal-body">
            <div id="div-modal-detalle-atencion-quirurgica"></div>
          </div>
      </div>
   </div>
</div>

<!-- Modal de VALIDACION DE PROGRAMACION QUIRURGICA -->
<div class="modal fade" id="modal-validacion-programacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document" style="width:70%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">MÓDULO DE VALIDACIÓN DE LA PROGRAMACIÓN QUIRÚGICA</h4>
            </div>
            <div class="modal-body">
              <div id="div-modal-validacion-programacion"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de MOTIVO SUSPENDER -->
<div class="modal fade" id="modal-suspender-intervencion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document" style="width:70%">
        <div class="modal-content">
            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">SUSPENDER INTERVENCIÓN QUIRÚRGICA</h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" id="frmSuspenderIntervencion" name="frmSuspenderIntervencion" method="post" action="{{ route('registrar_suspencion_intervencion')}}">
                <div id="seccion-registro-suspension" style="display:none">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <input type="hidden" id="id_intervencion_quirurgica_suspender" name="id_intervencion_quirurgica_suspender">
                  <div class="form-group">
                    <div class="col-md-12">
                      <label for="">Motivo de Suspensión:</label>
                      <textarea name="suspended_motivo" id="suspended_motivo" rows="4" class="form-control"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-3">
                      <button type="button" class="form-control btn btn-sm btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                    <div class="col-md-offset-6 col-md-3">
                      <input type="button" value="Registrar" class="form-control btn btn-sm btn-success" id="btnSuspender" >
                    </div>
                  </div>
                </div>
                <!--  -->
                <div id="seccion-ver-motivo-suspension" style="display:none">
                  <div class="form-group">
                    <div class="col-md-3">
                      <label for="">Suspendido el:</label>
                      <input type="text" id="suspended_fecha_registrado" class="form-control" readonly>
                    </div>
                    <div class="col-md-9">
                      <label for="">Suspendido por:</label>
                      <input type="text" id="suspended_usuario_registrado" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-12">
                      <label for="">Motivo de Suspensión:</label>
                      <textarea id="suspended_motivo_registrado" rows="4" class="form-control" readonly></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-offset-9 col-md-3">
                      <button type="button" class="form-control btn btn-sm btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal HOJA DE RECUPERACION -->
<div class="modal fade" id="modal-hoja-recuperacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width:80%">
      <div class="modal-content">
          <div class="modal-header" style="font-size: 20px !important;">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">HOJA DE RECUPERACIÓN</h4>
          </div>
          <div class="modal-body">
            <div id="div-modal-hoja-recuperacion"></div>
          </div>
      </div>
  </div>
</div>
<!-- Modal  BALANCE HIDRICO-->
<div class="modal fade" id="modalBalanceHidrico" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 1100px;" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioBalanceHidrico"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioBalanceHidrico" name="FormularioBalanceHidrico" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_asegurado_bh" name="id_asegurado">
                    <input type="hidden" id="id_hoja_recuperacion_balance" name="id_hoja_recuperacion">
                    
                    
                    <div class="nav-tabs-custom" style="margin-bottom: -40px;">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                              <fieldset>
                                <div class="panel panel-info panel-bordered panel-sm">
                                  <div class="panel-heading">
                                    <div class="panel-title text-center"><br>
                                        <b>INFORMACIÓN DEL PACIENTE <p id="id_area_disabled"></p></b>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <table class="table" cellspacing="0" width="100%" style="margin-top:5px;">
                                      <tr>
                                        <th>DOCUMENTO</th>
                                        <th>PACIENTE</th>
                                        <th>PARENTESCO</th>
                                        <th>SEXO</th>
                                        <th>EDAD</th>
                                        <th>T. SANG.</th>
                                        <th>H. CLINICA</th>
                                      </tr>
                                      <tr>
                                        <td>
                                          <div  class="input_general" id="num_documento_bh_nuevo"></div>
                                        </td>
                                        <td>
                                          <div  class="input_paciente" id="nom_paciente_bh_nuevo"></div>
                                        </td>
                                        <td>
                                          <div  class="input_m" id="parentesgo_bh_nuevo"></div>
                                        </td>
                                        <td>
                                          <div  class="input_p" id="sexo_bh_nuevo"></div>
                                        </td>
                                        <td>
                                          <div  class="input_p" id="edad_bh_nuevo"></div>
                                        </td>
                                        <td>
                                          <div  class="input_p" id="tipo_sangre_bh_nuevo"></div>
                                        </td>
                                        <td>
                                          <div  class="input_hc" id="hc_bh_nuevo"></div>
                                        </td>
                                        
                                      </tr>
                                  </table>
                                  </div>
                                </div>
                            </fieldset>
                            <fieldset>
                              <div class="panel panel-info panel-bordered panel-sm">
                            
                                  <div class="panel-body">
                                    <table class="table" id="cpms_listado" cellspacing="0" width="100%" style="margin-top:5px;">
                                      <thead>
                                        
                                        <tr>
                                          <th colspan="5"><b>DATOS DE LA ATENCIÓN </b></th>
                                        </tr>
                                        <tr>
                                          <th width="20%">TIPO DE ATENCIÓN</th>
                                          <th width="20%">FECHA DE ATENCIÓN</th>
                                          <th width="15%">HORA DE ATENCIÓN</th>
                                          <th width="30%">PRIORIDAD</th>
                                          <th width="25%">TURNO</th>
                                        </tr>
                                      </thead>
                                      
                                        <tr>
                                          <td width="20%">
                                            <div  class="input_general" id="tipo_atencion_disabled"></div>
                                          </td>
                                          <td width="20%">
                                            <div  class="input_general" id="fecha_atencion_disabled"></div>
                                          </td>
                                          <td width="15%">
                                            <div  class="input_general" id="hora_atencion_disabled"></div>
                                          </td>
                                          <td width="25%">
                                            <div  class="input_300" id="prioridad_disabled"></div>
                                           
                                          </td>
                                          <td width="30%">
                                            <select class="form-control" style="width: 200px;" id="turnos_ver" name="turno">
                                            </select>
                                          </td>
                                        </tr>
                                    </table>
                                 </div>
                              </div>
                            </fieldset>
                                <fieldset style="margin-bottom: 0px !important;">
                                    <div class="form-group" style="margin-bottom: 0px !important">
                                      <div id="balance_hidrico">
                                         
                                      </div>

                                    </div>
                                </fieldset>
                            </div>

                        </div><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" id="registrarBalanceHidricoNuevo"
                            value="create">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal MÓDULO DE GESTIÓN DE CITAS -->
<div class="modal fade" id="modal-cita-quirurgica" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document" style="width:80%">
        <div class="modal-content">
            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">MÓDULO DE GESTIÓN DE CITAS</h4>
            </div>
            <div class="modal-body">
              <div id="div-modal-cita-quirurgica"></div>
            </div>
        </div>
    </div>
</div>
<!-- Modal MÓDULO DETALLE DE GESTIÓN DE CITAS -->
<div class="modal fade" id="modal-detalle-citas-cqx" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document" style="width:60%">
        <div class="modal-content">
            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">MÓDULO DE GESTIÓN DE CITAS</h4>
            </div>
            <div class="modal-body">
              <div id="div-modal-detalle-citas-cqx"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal VER PRESTACION -->
<div class="modal fade" id="modal-ver-prestacion-cqx" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document" style="width:60%">
        <div class="modal-content">
            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">MÓDULO DE GESTIÓN DE ATENCIONES MEDICAS</h4>
            </div>
            <div class="modal-body">
              <div id="div-modal-ver-prestacion-cqx"></div>
            </div>
        </div>
    </div>
</div>
<!-- Modal VER  HC -->
<div id="myModalHC" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width: 1600px !important; max-width: 95% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">MÓDULO DE HISTORIA CLINICA DE ATENCIONES</h5>
            </div>
            <div class="modal-body">
                <div id="muestra-modal-hc"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- EDITAR TELEFONOS -->
<div class="modal fade" id="modalEditarTelefono" tabindex="-1" role="dialog" aria-labelledby="modalEditarTelefonoLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Editar Teléfonos</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="id_paciente_edit">
        <input type="hidden" id="asegurado">
        <div class="form-group">
          <label for="telefono1_edit">DNI</label>
          <input type="text" class="form-control" id="dni_edit" disabled>
        </div>
        <div class="form-group">
          <label for="telefono1_edit">Teléfono 1</label>
          <input type="text" class="form-control" id="telefono1_edit">
        </div>
        <div class="form-group">
          <label for="telefono2_edit">Teléfono 2</label>
          <input type="text" class="form-control" id="telefono2_edit">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnGuardarTelefono" class="btn btn-success">Guardar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

@include('frontend.includes.footer')
@endsection