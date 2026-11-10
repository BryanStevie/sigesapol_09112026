@extends('frontend.layouts.master')
@section('renderjs')
    <style>
        .filterable {
            margin-top: 0px;
        }

        .filterable .panel-heading .pull-right {
            margin-top: -20px;
        }

        .filterable .filters input[disabled] {
            background-color: transparent;
            border: none;
            cursor: auto;
            box-shadow: none;
            padding: 0;
            height: auto;
        }

        .filterable .filters input[disabled]::-webkit-input-placeholder {
            color: #333;
        }

        .filterable .filters input[disabled]::-moz-placeholder {
            color: #333;
        }

        .filterable .filters input[disabled]:-ms-input-placeholder {
            color: #333;
        }

        #global {
            height: 600px !important;
            width: auto;
            border: 1px solid #ddd;
            background: #f1f1f1;
            overflow-y: scroll !important;
        }

        .margin {

            margin-bottom: 20px;
        }

        .margin-buscar {
            margin-bottom: 5px;
            margin-top: 5px;
        }

        .panel {
            margin-bottom: 0px
        }
        /* ================= LOADER ================= */
#loadingOverlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,.85);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 999999;
}

.loading-content {
    text-align: center;
}

.loading-content h3 {
    margin-top: 20px;
    color: #337ab7;
    font-weight: bold;
}

.loading-content p {
    color: #555;
    font-size: 16px;
}

.spinner-loader {
    width: 80px;
    height: 80px;
    border: 8px solid #ddd;
    border-top: 8px solid #337ab7;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: auto;
}

@keyframes spin {
    100% {
        transform: rotate(360deg);
    }
}
    </style>
    <script>
        $(document).ready(function() {
            $('#btnGenerarTxt').on('click', function(e) {
                e.preventDefault();
                let letra = 'b2'; // o lo obtienes dinámicamente si también varía
                let periodo = $('#periodo').val();
                let anio = $('#anio').val();
                let id_establecimiento = $('#id_establecimiento').val();
                let url = '/generar-txt_reportes-seis/' + letra + '/' + periodo + '/' + id_establecimiento + '/' + anio;
                window.location.href = url;
            });
        });
        $(document).ready(function() {
            $('#btnGenerarCsv').on('click', function(e) {
                e.preventDefault();
                let letra = 'b2';
                let periodo = $('#periodo').val();
                let anio = $('#anio').val();
                let id_establecimiento = $('#id_establecimiento').val();
                let url = '/generar-excel_reportes-seis/' + letra + '/' + periodo + '/' + id_establecimiento + '/' + anio;
                window.location.href = url;
            });
        });
        $(document).ready(function() {
            const table = $('#example').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/reporte_b2_recursos',
                    type: 'POST',
                    data: function(d) {
                        d.periodo = $('#periodo').val();
                        d.anio = $('#anio').val();
                        d.id_establecimiento = $('#id_establecimiento').val();
                        d.cod_ipress = $('#id_press').val();
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                columns: [{
                        data: null,
                        render: function(data, type, row) {
                            let periodo = $("#periodo option:selected").text();
                            let periodoMayuscula = periodo.toUpperCase();
                            return row.periodo_reporte;
                        },
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return row.codigo_ipress ?? "Sin data";
                        },
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return $("#id_establecimiento option:selected").text();
                        },
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return row.codigo_ugipress ?? "Sin data";
                        },
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return $("#id_establecimiento option:selected").text();
                        },
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return row.descripcion_sexo_paciente ?? "Sin data";
                        },
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return row.descripcion_grupo_edad ?? 'Sin data';
                        },
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return row.diagnostico_definitivo ?? 'Sin data';
                        },
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return row.total_atendidos ?? 'Sin data';
                        },
                    },
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                    emptyTable: "No hay datos disponibles"
                }
            });
            // Mostrar loader antes de la consulta
table.on('preXhr.dt', function () {
    $("#loadingOverlay").css("display", "flex");
});

// Ocultar loader cuando termine
table.on('xhr.dt', function () {
    $("#loadingOverlay").hide();
});

// Ocultar loader si ocurre un error
table.on('error.dt', function () {
    $("#loadingOverlay").hide();
});
        $("#btnBuscar").on("click", function () {
    $("#loadingOverlay").css("display", "flex");
    table.ajax.reload();
});
            $("#id_establecimiento").on("change", function(e) {
                let opcion = $(this).find('option:selected');
                let ugipress = opcion.data('ugipress');
                $("#ugipress").val(ugipress);
            })
        });
    </script>
    <script>
        $(document).ready(function() {
            const select = document.getElementById("anio");
            const anioInicio = 2019;
            const anioActual = new Date().getFullYear();

            for (let anio = anioInicio; anio <= anioActual; anio++) {
                const option = document.createElement("option");
                option.value = anio;
                option.textContent = anio;
                if (anio === anioActual) {
                    option.selected = true;
                }
                select.appendChild(option);
            }
        });
    </script>
@endsection
@section('content')
    <div class="panel panel-primary filterable">
        <div class="panel-heading">
            <h3 class="panel-title" style="font-size: 13px !important">TABLA B2 - REPORTE CONSOLIDADO DE MORBILIDAD EN CONSULTA AMBULATORIA</h3>
        </div>
        <div class="panel-body">
            {{-- @foreach ($ipress as $i)
                @endforeach --}}
            <form id="frmStock" class="form-horizontal" method="post" action="stockFarmaciaByEstablecimiento_prueba">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <div class="col-md-2">
                        <label><small>Año</small></label>
                        <select class="form-control" name="anio" id="anio" style="width: 100%">
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label><small>Periodo</small></label>
                        <select class="form-control" name="periodo" id="periodo" style="width: 100%">
                            <option value="1" selected>Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                    </div>
                    <div class="col-md-4">
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
                    <div class="col-md-2">
                        <label><small>UGIPRESS</small></label>
                        <input readonly type="text" class="form-control" id="ugipress" name="ugipress"
                            value="{{ $establecimientoActual->codigo }}">
                    </div>
                    <div class="col-md-2">
                        <label><small>Accion</small></label>
                        <input id="btnBuscar" class="btn btn-info form-control" value="Buscar" type="button">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-3">
                        <a id="btnGenerarTxt" class="btn btn-warning form-control">
                            <i class="fa fa-file-text-o" aria-hidden="true"></i>Generar Archivo Plano
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a id="btnGenerarCsv" class="btn btn-primary form-control">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i>Generar Excel
                        </a>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="responsive"></div>
                <div class="col-sm-12">
                    <br />
                    <table id="example" class="table table-hover" style="font-size: 12px !important;">
                        <thead>
                            <tr class="filters">
                                <th>PERIODO</th>
                                <th>COD. IPRESS</th>
                                <th>DESCRIPCION IPRESS</th>
                                <th>COD. UGIPRESS</th>
                                <th>DESCRIPCION UGIPRESS</th>
                                <th>SEXO PACIENTE</th>
                                <th>GRUPO DE EDAD</th>
                                <th>DIAGNOSTICO DEFINITIVO</th>
                                <th>TOTAL ATENDIDO</th>
                            </tr>
                        </thead>
                        <tbody id="finalTable" style="font-size: 11px !important;">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div id="loadingOverlay">
    <div class="loading-content">
        <div class="spinner-loader"></div>
        <h3>Cargando información...</h3>
        <p>Por favor espere mientras se genera el reporte.</p>
    </div>
</div>
    @include('frontend.includes.footer')
@endsection
