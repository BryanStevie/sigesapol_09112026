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
  width: 100%;
  min-height: 650px !important;
  max-width: 100%;
  border: 1px solid #ddd;
  margin: 15px auto;
  padding: 15px;
  box-sizing: border-box; /* Asegura que el padding no afecte el tamaño total del contenedor */
  overflow-x: auto; /* Permite desplazamiento horizontal si es necesario */
  overflow-y: hidden; /* No permite el desplazamiento vertical */
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

  .modal-dialog {
    width: 70% !important;
  }
</style>

<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>


    $(document).ready(function () {
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
        });
    
        $('#fecha_ini').click(function () {
            var fechaDesdeIni = $('#fecha_ini').val();
        
            $('#fecha_ini').change(function () {
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

        $('#fecha_fin').click(function () {
            var fechaHastaIni = $('#fecha_fin').val();
            $('#fecha_fin').change(function () {
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
        
    $(document).ready(function () {
        $('#example').DataTable({
            "language": {
            "emptyTable": "No se encontraron resultados"
            }
        });
    
        $("#system-search").keyup(function() {
            var dataTable = $('#example').dataTable();
            dataTable.fnFilter(this.value);
        });
    });
    function reporteFema(id_opcion,idUser){

location.href = 'enviar_masivo_ws/'  + id_opcion;

}
	
    function soloNumeros(e) {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)
    }

    function controlarMeses() {
  const anioSeleccionado = parseInt(document.getElementById("anio").value);
  const mesSeleccionado = parseInt(document.getElementById("mes").value);
  const anioActual = new Date().getFullYear();
  const mesActual = new Date().getMonth() + 1;

  const anioSelect = document.getElementById("anio");
  for (let i = 0; i < anioSelect.options.length; i++) {
    const option = anioSelect.options[i];
    const opcionAnio = parseInt(option.value);
    if (opcionAnio > anioActual) {
      option.disabled = true;
    } else {
      option.disabled = false;
    }
  }

  if (anioSeleccionado > anioActual) {
    document.getElementById("mes").value = "0";
  }

  const mesSelect = document.getElementById("mes");
  for (let i = 1; i <= mesSelect.options.length; i++) {
    const option = mesSelect.options[i];
    if (anioSeleccionado === 2024) {
      if (i < 6 || i > mesActual) {
        option.disabled = true;
      } else {
        option.disabled = false;
      }
    } else {
      option.disabled = false;
    }
  }
}

document.addEventListener("DOMContentLoaded", function() {
  controlarMeses();
});

    $(document).ready(function () {
    	
 
    	actualizarUbigeo();
    	 	
    });

    $('body').on('click', '#validarws', function () {
        var id_trama = $(this).data('id_trama');
        var id_estado = $(this).data('id_estado');
        $id_trama = id_trama;
        $id_estado = id_estado;
        $('#id_estado').html(id_estado);
        $('#id_trama').html(id_trama);
       if(id_trama == '0'){
        console.log(id_trama);
        console.log(id_estado);
        validar_x_ws(id_trama,id_estado)
       }
    });

    function validar_x_ws(id_trama,id_estado){
      Swal.fire({
            title: '<div class="titulo-padding"><p>Procesando el envio...</p></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                header : 'swal2-header-generar'
            },
            onBeforeOpen: () => {
                $('#blur-container').append('<div class="blur-background"></div>');
                Swal.showLoading();
                var postData = {
                  id_trama: id_trama,
                    id_estado: id_estado,
                  };
                $.ajax({
                    url: '/validar_ws_SaludPol',
                    type: 'POST',
                    data: postData,
                    success: function(response) {
                        console.log('Success:', response);
                        datatablenew();
                        Swal.hideLoading();
                        Swal.fire({
                          icon: 'success',
                          title: 'La información fue enviado correctamente!',
                          showConfirmButton: false,
                          showConfirmButton: false,
                          timer: 1500,
                          onClose: () => {
                              $('.blur-background').remove();
                          }
                      });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });               
            }
        });
    }

    function reporteAtencion(idUser) {

var anio = $('#anio').val();
var mes = $('#mes').val();
var tipo_atencion_detalle = $('#tipo_atencion_detalle').val();
var ipress = $('#ipress').val();
var estado_trama = $('#estado_trama').val();
var cod_paquete = $('#cod_paquete').val();
var resultado = $('#resultado').val();
if (cod_paquete == "") cod_paquete = 0;

location.href = '/exportar_reporte_auditoria_mejorado/' + anio + '/' + mes + '/' + tipo_atencion_detalle + '/' +
ipress+ '/' + estado_trama+ '/' + cod_paquete+ '/' + resultado;

}

function actualizarUbigeo() {
    var select = document.getElementById('ipress');
    var selectedOption = select.options[select.selectedIndex];

    var departamento = selectedOption.getAttribute('data-departamento');
    var provincia = selectedOption.getAttribute('data-provincia');
    var distrito = selectedOption.getAttribute('data-distrito');

    document.getElementById('departamento').value = departamento;
    document.getElementById('provincia').value = provincia;
    document.getElementById('distrito').value = distrito;
}

window.onload = function() {
    var select = document.getElementById('ipress');
    var selectedOption = select.querySelector('option[selected]'); 

    if (selectedOption) {
        actualizarUbigeo();
    } else {
        select.selectedIndex = 0;
        actualizarUbigeo();
    }
};

function controlarEdicion() {
    var estado = document.getElementById('estado_trama') ? document.getElementById('estado_trama').value : ''; 
    var dniInput = document.getElementById('dni'); 
    var codPaqueteInput = document.getElementById('cod_paquete');  
    var observacionSelect = document.getElementById('observacionSelect'); 

    if (estado == '0' || estado == 0) {
        if (dniInput) {
            dniInput.disabled = true;  
            dniInput.value = ''; 
        }
        if (codPaqueteInput) {
            codPaqueteInput.disabled = true; 
            codPaqueteInput.value = ''; 
        }
        observacionSelect.style.display = 'none'; 
    } else {
        if (dniInput) {
            dniInput.disabled = false; 
        }
        if (codPaqueteInput) {
            codPaqueteInput.disabled = false; 
        }
        observacionSelect.style.display = 'block'; 
    }
}

window.onload = function() {
    controlarEdicion();
};
  
    
</script>
@endsection
@section('content')
<div class="container-fluid">
  <div class="panel panel-primary filterable">
    <div class="panel-heading" style="padding: 0;">
      <h3 style="display: block; height: 100%; padding: 0 15px;" class="panel-title">
        <span style="display: inline-block; padding: 10px 15px; background-color: rgb(51, 61, 183); color: white;">A</span> Reportes de Auditoria
      </h3>
    </div>
    
    <div class="panel-body">
      <form class="form-horizontal" method="post" action="{{ route('listar_referencia_ajax') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input value="{{ Auth::user()->id }}" name="id_user" type="hidden">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border: 2px solid #ddd; padding: 10px; margin-bottom: 20px;">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <label class="form-control-sm">Año</label>
                <select id="anio" name="anio" class="form-control input-sm" onchange="controlarMeses();">
                  <option value="2024" {{ $anioActual == 2024 ? 'selected' : '' }}>2024</option>
                  <option value="2025" {{ $anioActual == 2025 ? 'selected' : '' }}>2025</option>
                  <option value="2026" {{ $anioActual == 2026 ? 'selected' : '' }}>2026</option>
                  <option value="2027" {{ $anioActual == 2027 ? 'selected' : '' }}>2027</option>
                </select>
              </div>

              <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <label class="form-control-sm">Mes</label>
                <select id="mes" name="mes" class="form-control input-sm">
                  <option value="0" {{ $mesActual == 0 ? 'selected' : '' }}>Seleccionar</option>
                  <option value="1" {{ $mesActual == 1 ? 'selected' : '' }}>Enero</option>
                  <option value="2" {{ $mesActual == 2 ? 'selected' : '' }}>Febrero</option>
                  <option value="3" {{ $mesActual == 3 ? 'selected' : '' }}>Marzo</option>
                  <option value="4" {{ $mesActual == 4 ? 'selected' : '' }}>Abril</option>
                  <option value="5" {{ $mesActual == 5 ? 'selected' : '' }}>Mayo</option>
                  <option value="6" {{ $mesActual == 6 ? 'selected' : '' }}>Junio</option>
                  <option value="7" {{ $mesActual == 7 ? 'selected' : '' }}>Julio</option>
                  <option value="8" {{ $mesActual == 8 ? 'selected' : '' }}>Agosto</option>
                  <option value="9" {{ $mesActual == 9 ? 'selected' : '' }}>Septiembre</option>
                  <option value="10" {{ $mesActual == 10 ? 'selected' : '' }}>Octubre</option>
                  <option value="11" {{ $mesActual == 11 ? 'selected' : '' }}>Noviembre</option>
                  <option value="12" {{ $mesActual == 12 ? 'selected' : '' }}>Diciembre</option>
                </select>
              </div>

              <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <label class="form-control-sm">Tipo Atención</label>
                <select id="tipo_atencion_detalle" name="tipo_atencion_detalle" class="form-control input-sm">
                  <option value="0">TODOS</option>
                  <option value="1">Consulta Externa</option>
                  <option value="2">Emergencia</option>
                  <option value="3">Hospitalización</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
              <label class="form-control-sm">Codigo IPRESS</label>
              <select class="form-control input-sm" id="ipress" name="ipress" tabindex="3" onchange="actualizarUbigeo();">
                
                @foreach($establecimientos as $row)
                  <option value="{{ $row->codigo }}"
                          data-departamento="{{ $row->departamento }}"
                          data-provincia="{{ $row->provincia }}"
                          data-distrito="{{ $row->distrito }}">
                    {{ $row->nombre }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">
              <label class="form-control-sm">Departamento</label>
              <input type="text" class="form-control input-sm" id="departamento" name="departamento" value="" tabindex="4" readonly>
            </div>

            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">
              <label class="form-control-sm">Provincia</label>
              <input type="text" class="form-control input-sm" id="provincia" name="provincia" value="" tabindex="5" readonly>
            </div>

            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">
              <label class="form-control-sm">Distrito</label>
              <input type="text" class="form-control input-sm" id="distrito" name="distrito" value="" tabindex="6" readonly>
            </div>
          </div>

          <div class="row">
    <div class="col-lg-4 col-md-3 col-sm-12 col-xs-12">
        <label class="form-control-sm">Estado Trama</label>
        <select id="estado_trama" name="estado_trama" class="form-control input-sm" onchange="controlarEdicion();">
            <option value="1">Enviados</option>
            <option value="0">No Enviados</option>
        </select>
    </div>

    <div class="col-lg-4 col-md-3 col-sm-12 col-xs-12">
        <label class="form-control-sm">Cod Empaquetado</label>
        <input id="cod_paquete" name="cod_paquete" type="text" placeholder="" class="form-control input-sm" maxlength="12" onkeypress="return esEntero(event);" value="" disabled>
    </div>

    <div class="col-lg-4 col-md-3 col-sm-12 col-xs-12" id="observacionSelect" style="display:none;">
        <label class="form-control-sm">Resultado de Envio</label>
        <select id="resultado" name="resultado" class="form-control input-sm">
            <option value="0">Todos</option>
            <option value="1">Observados</option>
            <option value="2">Re-Procesados</option>
            <option value="3">Aprobados</option>
        </select>
    </div>
</div>
          <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
              <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteAtencion('{{ Auth::user()->id }}')">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
              </a>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

@include('frontend.includes.footer')
@endsection