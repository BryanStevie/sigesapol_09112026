@extends('frontend.layouts.master')

@section('renderjs')
<script>

$(document).ready(function() {

    let hoy = new Date();
    let mes = hoy.toISOString().slice(0,7);
    $('#periodoFiltro').val(mes);

    cargarReporteD1();
    cargarReporteGuardado();

    $('#periodoFiltro').on('change', function() {
        cargarReporteD1();
        cargarReporteGuardado();
    });

    $('#frmfarmacia').on('submit', function(e) {
        e.preventDefault();

        if (!confirm("⚠️ ¿Seguro que deseas REGISTRAR estos datos?")) return;
        if (!confirm("🚨 Última confirmación: ¿Deseas GUARDAR definitivamente?")) return;

        let filas = [];

        $('#tablaReporteD1 tbody tr').each(function() {

            let checked = $(this).find('.chk-guardar').is(':checked');

            if (checked) {

                filas.push({
                    periodo: $(this).find('td:eq(0)').text().trim(),
                    cod_ipress: $(this).find('td:eq(1)').text().trim(),
                    descripcion_ipress: $(this).find('td:eq(2)').text().trim(),
                    cod_ugipress: $(this).find('td:eq(3)').text().trim(),
                    especialidad: $(this).find('td:eq(4)').text().trim(),

                    ingresos: parseInt($(this).find('td:eq(5)').text()) || 0,
                    egresos: parseInt($(this).find('td:eq(6)').text()) || 0,
                    estancias: parseInt($(this).find('td:eq(7)').text()) || 0,

                    pacientes_dia: parseInt($(this).find('td:eq(8)').text()) || 0,
                    camas: parseInt($(this).find('td:eq(9)').text()) || 0,
                    dias_cama: parseInt($(this).find('td:eq(10)').text()) || 0,

                    fallecidos: parseInt($(this).find('td:eq(11)').text()) || 0
                });
            }
        });

        $.ajax({
            url: "{{ route('guardar_d1_camas') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                data: filas
            },
            success: function(res) {
                alert('Guardado correctamente');
                cargarReporteGuardado();
            },
            error: function(err) {
                console.log(err);
                alert('Error al guardar');
            }
        });

    });

});

function cargarReporteD1() {

    let periodo = $('#periodoFiltro').val();

    $.ajax({
        url: '/reporte_d1_data',
        type: 'GET',
        data: { periodo: periodo },
        success: function(response) {

            let tbody = $('#tablaReporteD1 tbody');
            tbody.empty();

            $.each(response, function(index, item) {

                let fila = `
                    <tr>
                        <td>${item.periodo_reporte ?? ''}</td>
                        <td>${item.codigo_ipress}</td>
                        <td>${item.nombre_ipress}</td>
                        <td>${item.codigo_ugipress}</td>
                        <td>${item.codigo_upss}</td>

                        <td contenteditable="true">${item.ingresos}</td>
                        <td contenteditable="true">${item.egresos}</td>
                        <td contenteditable="true">${item.estancias}</td>
                        <td contenteditable="true">${item.pacientes_dias}</td>
                        <td contenteditable="true">${item.camas_disponibles}</td>
                        <td contenteditable="true">${item.dias_cama_disponible}</td>

                        <td>${item.defunciones}</td>

                        <td style="text-align:center;">
                            <input type="checkbox" class="chk-guardar">
                        </td>
                    </tr>
                `;

                tbody.append(fila);
            });
        },
        error: function(err) {
            console.log(err);
            alert('Error al cargar datos D1');
        }
    });
}

function cargarReporteGuardado() {

    let periodo = $('#periodoFiltro').val();

$.ajax({
    url: '/reporte_d1_guardado',
    type: 'POST',
    data: {
        _token: "{{ csrf_token() }}",
        periodo: periodo
    },
        success: function(data) {

            let tbody = $('#tablaGuardados tbody');
            tbody.empty();

            if (!data || data.length === 0) {
                tbody.append(`
                    <tr>
                        <td colspan="13" style="text-align:center;">
                            No hay registros guardados
                        </td>
                    </tr>
                `);
                return;
            }

            data.forEach(function(item) {

                let fila = `
                    <tr>
                        <td>${item.periodo}</td>
                        <td>${item.cod_ipress}</td>
                        <td>${item.descripcion_ipress}</td>
                        <td>${item.cod_ugipress}</td>
                        <td>${item.especialidad}</td>

                        <td>${item.ingresos}</td>
                        <td>${item.egresos}</td>
                        <td>${item.estancias}</td>
                        <td>${item.pacientes_dia}</td>
                        <td>${item.camas}</td>
                        <td>${item.dias_cama}</td>
                        <td>${item.fallecidos}</td>

                        <td>
                            ${
                                item.id_estado_reg == 0
                                ? '<span style="color:red;font-weight:bold;">ANULADO</span>'
                                : `<button class="btn btn-danger btn-sm" onclick="anularRegistro(${item.id})">
                                    Anular
                                   </button>`
                            }
                        </td>
                    </tr>
                `;

                tbody.append(fila);
            });
        }
    });
}

function anularRegistro(id) {

    if (!confirm("⚠️ ¿Seguro que deseas ANULAR este registro?")) return;
    if (!confirm("🚨 Esta acción no se puede deshacer. ¿Confirmas?")) return;

    $.ajax({
        url: '/anular_reporte_d1/' + id,
        type: 'POST',
        data: {
            _token: "{{ csrf_token() }}"
        },
        success: function() {
            alert("Registro anulado");
            cargarReporteGuardado();
        },
        error: function(err) {
            console.log(err);
            alert("Error al anular");
        }
    });
}

function descargarTXT() {

    let periodo = $('#periodoFiltro').val();

    if (!periodo) {
        alert("Selecciona un periodo primero");
        return;
    }

    window.location.href = '/exportar_d1_txt?periodo=' + periodo;
}
</script>

<script src='../../funciones/function.js'></script>
@endsection


@section('content')
<div class="container-fluid">
    <div class="row">

        <!-- MENU -->
        <div class="col-sm-2">
            <div class="col-sm-12 pull-right">
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="/all_pabellon/">Pabellón</a></li>
                    <li><a href="/all_piso">Pisos</a></li>
                    <li><a href="/all_asignacion_servicio">Servicio</a></li>
                    <li><a href="/all_cuarto/">Cuarto</a></li>
                    <li><a href="/create_cama/">Nueva Cama</a></li>
                    <li class="active"><a href="/reporte_d1_cama/">Reporte D1</a></li>
                </ul>
            </div>
        </div>

        <div class="col-md-10">

            <!-- FILTRO -->
            <div class="form-group">
                <label>📅 Seleccionar periodo (mes)</label>
                <input type="month" id="periodoFiltro" class="form-control" />
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">Editar Reporte D1</div>
                </div>

                <div class="panel-body">

                    <form id="frmfarmacia" method="post">

                        <table class="table table-bordered table-striped" id="tablaReporteD1">
                            <thead>
                                <tr>
                                    <th>Periodo</th>
                                    <th>IPRESS</th>
                                    <th>Descripción</th>
                                    <th>UGIPRESS</th>
                                    <th>Especialidad</th>
                                    <th>Ingresos</th>
                                    <th>Egresos</th>
                                    <th>Estancias</th>
                                    <th>Pacientes Día</th>
                                    <th>Camas</th>
                                    <th>Días Cama</th>
                                    <th>Fallecidos</th>
                                    <th>Guardar</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                        <br>

                        <button type="submit" class="btn btn-success">
                            Guardar
                        </button>

                    </form>

                    <hr>

                    <h4>📊 Reportes Guardados</h4>

                    <table class="table table-bordered" id="tablaGuardados">
                        <thead>
                            <tr>
                                <th>Periodo</th>
                                <th>IPRESS</th>
                                <th>Descripción</th>
                                <th>UGIPRESS</th>
                                <th>Especialidad</th>
                                <th>Ingresos</th>
                                <th>Egresos</th>
                                <th>Estancias</th>
                                <th>Pacientes Día</th>
                                <th>Camas</th>
                                <th>Días Cama</th>
                                <th>Fallecidos</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
            </div>
<div style="margin-bottom:10px;">
    <a href="javascript:void(0)" class="btn btn-primary" onclick="descargarTXT()">
    📄 Descargar TXT
</a>

</div>
        </div>

    </div>
</div>

@include('frontend.includes.footer')
@endsection