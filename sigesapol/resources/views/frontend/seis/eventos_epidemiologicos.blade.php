<?php
ini_set('memory_limit', '-1');
set_time_limit(0);
?>
@extends('frontend.layouts.master2')
@section('renderjs')
<style>
.table-sortable tbody tr {
  cursor: move;
}
#global {
  min-height: 650px !important;
  width: 100%;
  border: 1px solid #ddd;
  margin: 15px
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

</style>

<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>
//--------------------------------------------------------------------------
$(document).ready(function() {

  $('#fecha_ini').appendDtpicker({
    "dateOnly": true,
    "autodateOnStart": false,
    "minuteInterval": 5,
    "amPmInTimeList": false,
    "locale": "es",
    "closeOnSelected": true,
    "dateFormat": "DD-MM-YYYY"
  });
  $('#fecha_fin').appendDtpicker({
    "dateOnly": true,
    "autodateOnStart": false,
    "minuteInterval": 5,
    "amPmInTimeList": false,
    "locale": "es",
    "closeOnSelected": true,
    "dateFormat": "DD-MM-YYYY"
    //"dateFormat": "YYYY-MM-DD hh:mm",
  });
  $('#fecha_ini').click(function() {
    var fechaDesdeIni = $('#fecha_ini').val();
    $('#fecha_ini').change(function() {
      var fechaDesde = $('#fecha_ini').val();
      var fechaHasta = $('#fecha_fin').val();
      f1 = fechaDesde.split("-");
      f2 = fechaHasta.split("-");
      f3 = new Date();
      var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
      var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

      if (f1 > f2) {
        bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta");
        $('#fecha_ini').val(fechaDesdeIni);
        return false;
      }
    });
  });
  $('#fecha_fin').click(function() {
    var fechaHastaIni = $('#fecha_fin').val();
    $('#fecha_fin').change(function() {
      var fechaDesde = $('#fecha_ini').val();
      var fechaHasta = $('#fecha_fin').val();
      f1 = fechaDesde.split("-");
      f2 = fechaHasta.split("-");
      f3 = new Date();
      var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
      var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

      if (f2 < f1) {
        bootbox.alert("La Fecha Hasta no debe ser menor a la Fecha Desde");
        $('#fecha_fin').val(fechaHastaIni);
        return false;
      }
    });
  });
  $('#fecha_fin').trigger('change');
  $('#fecha_ini').trigger('change');
  var activeSystemClass = $('.list-group-item.active');
});

$(document).ready(function() {
  $('#example').DataTable({
    /*"searching": false*/
    "language": {
      "emptyTable": "No se encontraron resultados"
    }
  });
  $("#system-search").keyup(function() {
    var dataTable = $('#example').dataTable();
    dataTable.fnFilter(this.value);
  });
});


function reporteAtencionEvento(idUser) {

  var fecha_ini = $('#fecha_ini').val();
  var fecha_fin = $('#fecha_fin').val();
  var id_evento = $('#id_evento').val();
  var id_establecimiento = $('#id_establecimiento').val();
  
  if (fecha_ini == "") fecha_ini = 0;
  if (fecha_fin == "") fecha_fin = 0;
  if (id_evento == "") id_evento = 0;
  if (id_establecimiento == "") id_establecimiento = 0;
  location.href = 'exportar_reporte_eventos_epidemiologicos/' + idUser + '/' + fecha_ini + '/' + fecha_fin + '/'  + id_evento + '/' + id_establecimiento ;

}


function soloNumeros(e) {
  var key = window.Event ? e.which : e.keyCode;
  return ((key >= 48 && key <= 57) || key == 0 || key == 8)
}

$(document).ready(function() {
  $('#btnBuscar').click(function() {
    fn_ListarBusqueda();
  });
  $("#id_establecimiento").select2({
    width: 'resolve'
  });
  $("#id_evento").select2({
      width: 'resolve'
    });
  datatablenew();
});

var m = 0;

function datatablenew() {
  var oTable1 = $('#tblPrestacion').dataTable({
    "bServerSide": true,
    "sAjaxSource": "/listar_eventos_ajax",
    "bProcessing": true,
    "sPaginationType": "full_numbers",
    "bFilter": false,
    "bSort": false,
    "info": true,
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
      $('[data-toggle="tooltip"]').tooltip();
    },

    "fnServerData": function(sSource, aoData, fnCallback, oSettings) {

      var sEcho = aoData[0].value;
      var iNroPagina = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
      var iCantMostrar = aoData[4].value;
      var fecha_ini = $('#fecha_ini').val();
      var fecha_fin = $('#fecha_fin').val();
      var id_evento = $('#id_evento').val();
      var id_establecimiento = $('#id_establecimiento').val();
      var _token = $('#_token').val();
      oSettings.jqXHR = $.ajax({
        "dataType": 'json',
        "type": "POST",
        "url": sSource,
        "data": {
          NumeroPagina: iNroPagina,
          NumeroRegistros: iCantMostrar,
          fecha_ini: fecha_ini,
          fecha_fin: fecha_fin,
          id_evento: id_evento, 
          id_establecimiento: id_establecimiento,
          _token: _token
        },
        "success": function(result) {
          fnCallback(result);
        },
        "error": function(msg, textStatus, errorThrown) {
        }
      });
    },

    "aoColumnDefs": [{
        "mRender": function(data, type, row) {
          var id = "";
          if (row.id != null) id = row.id;
          return id;
        },
        "bSortable": false,
        "aTargets": [0]
      },
      {
        "mRender": function(data, type, row) {
          var nombre_evento = "";
          if (row.nombre_evento != null) nombre_evento = row.nombre_evento;
          return nombre_evento;
        },
        "bSortable": false,
        "aTargets": [1]
      },
      {
        "mRender": function(data, type, row) {
          var nombre_establecimiento = "";
          if (row.nombre_establecimiento != null) nombre_establecimiento = row.nombre_establecimiento;
          return nombre_establecimiento;
        },
        "bSortable": false,
        "aTargets": [2]
      },
      {
        "mRender": function(data, type, row) {
          var codigo_diagnostico = "";
          if (row.codigo_diagnostico != null) codigo_diagnostico = row.codigo_diagnostico;
          return codigo_diagnostico;
        },
        "bSortable": false,
        "aTargets": [3]
      },
      {
        "mRender": function(data, type, row) {
          var nombre_diagnostico = "";
          if (row.nombre_diagnostico != null) nombre_diagnostico = row.nombre_diagnostico;
          return nombre_diagnostico;
        },
        "bSortable": false,
        "aTargets": [4],
        "className": 'control'
      },
      {
        "mRender": function(data, type, row) {
          var fecha_atencion = "";
          if (row.fecha_atencion != null) fecha_atencion = row.fecha_atencion;
          return fecha_atencion;
        },
        "bSortable": false,
        "aTargets": [5],
        "className": 'control'
      },
      {
        "mRender": function(data, type, row) {
          var nro_doc_ident = "";
          if (row.nro_doc_ident != null) nro_doc_ident = row.nro_doc_ident;
          return nro_doc_ident;
        },
        "bSortable": false,
        "aTargets": [6]
      },
      {
        "mRender": function(data, type, row) {
          var asegurado = "";
          if (row.asegurado_paterno != null) asegurado += row.asegurado_paterno;
          if (row.asegurado_materno != null) asegurado += " " + row.asegurado_materno;
          if (row.asegurado_nombre != null) asegurado += " ," + row.asegurado_nombre;
          return asegurado;
        },
        "bSortable": false,
        "aTargets": [7]
      },
      {
        "mRender": function(data, type, row) {
          var servicio = "";
          if (row.servicio != null) servicio = row.servicio;
          return servicio;
        },
        "bSortable": false,
        "aTargets": [8]
      },
      {
        "mRender": function(data, type, row) {
          var sexo = "";
          if (row.sexo != null) sexo = row.sexo;
          return sexo;
        },
        "bSortable": false,
        "aTargets": [9]
      },
      {
        "mRender": function(data, type, row) {
          var edadafiliado = ""
          if (row.edad != null) edadafiliado = row.edad;
          return edadafiliado;
        },
        "bSortable": false,
        "aTargets": [10]
      },
      /*{
        "mRender": function(data, type, row) {
          var html = "";
          if (row.id_estado_reg == 1) {
            html +=
              '<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">';
            html += '<a href="/ver_prestacion/<?php echo Auth::user()->id ?>/' + row.id +
              '" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>';
          } else {
            html += '<button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></a></button>';
          }

          return html;
        },
        "bSortable": false,
        "aTargets": [11],
        "className": "text-center",
      },*/
    ]
  });
}

function fn_ListarBusqueda() {
  datatablenew();
};

</script>
@endsection
@section('content')
<div id="blur-container">
<div class="container-fluid">
  <div class="panel panel-primary filterable">
  <div class="panel-heading" style="padding: 0;">
      <h3 style="display: block; height: 100%; padding: 0 15px;" class="panel-title"> <span style="display: inline-block; padding: 10px 15px; background-color: rgb(109, 218, 132); color: green;">EV</span> EVENTOS EPIDEMIOLOGICOS</h3>
    </div>
    <div class="panel-body">

      <form class="form-horizontal" method="post" action="#">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">
        <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$farmacia->id}}">
        

        <div class="row">
          <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-sm">Fecha Desde</label>
            <input class="form-control input-sm" type="text" name="fecha_ini" id="fecha_ini" placeholder="Fecha Desde"
              value="<?php echo $fechaDesde ?>" maxlength="10">
          </div>
          <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <label class="form-control-sm">Fecha Hasta</label>
            <input class="form-control input-sm" type="text" name="fecha_fin" id="fecha_fin" placeholder="Fecha Hasta"
              value="<?php echo $fechaDesde ?>" maxlength="10">
          </div>
          <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <label class="form-control-sm">Eventos</label>
            <select class="form-control input-sm" name="id_evento" id="id_evento" style="width: 100%">
            <option value="0">-Seleccionar-</option>
            <?php foreach ($eventos as $row) : ?>
                <option value="<?php echo $row->id ?>"><?php echo $row->descripcion ?></option>
                <?php endforeach; ?>
                </select>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
              <label><small>Nombre de la IPRESS</small></label>
              <select {{ $flagestablecimientos != "1" ? 'disabled' : ''}} class="form-control" name="id_establecimiento" id="id_establecimiento" style="width: 100%">
                  <?php
                  foreach ($establecimientos as $d) {
                      echo '<option data-ugipress="' . $d->codigo . '" value="' . $d->id . '"';
                      if (isset($id_establecimiento)) {
                          if ($id_establecimiento == $d->id) {
                              echo ' selected';
                          }
                      }
                      echo '>' . $d->codigo . ' - ' . $d->nombre . '</option>';
                  }
                  ?>
              </select>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="padding-top:20px;padding-right:0px">
            <div class="col-md-6">
              <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
            </div>
            <div class="col-md-6">
              <a class="btn btn-success form-control input-sm" href="javascript:void(0)"
                onclick="reporteAtencionEvento('{{ Auth::user()->id }}')">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
              </a>
            </div>
          </div>
        </div>
      </form>
      <div class="col-md-12">
        <div class="table-responsive">
          <table id="tblPrestacion" class="table table-bordered">
            <thead>
              <tr>
                <th>N</th>
                <th>Evento</th>
                <th>Establecimiento</th>
                <th>Cod Diag</th>
                <th>Descripcion Diag</th>
                <th>F. Atencion</th>
                <th>Nro Doc</th>
                <th>Paciente</th>
                <th>Servicio</th>
                <th>Sexo</th>
                <th>Edad</th>
                <!--th>Ver</th-->
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

<div id="link-reporte"></div>

@include('frontend.includes.footer')
@endsection
