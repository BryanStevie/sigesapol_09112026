<title>Sistema de Farmacia</title>

<style>
    /*****************/
    .modal-dialog {
        width: 90%;
    }

    #tablemodal {
        border-spacing: 0;
        display: flex;
        /*Se ajuste dinamicamente al tamano del dispositivo**/
        max-height: 80vh;
        /*El alto que necesitemos**/
        overflow-y: auto;
        /**El scroll verticalmente cuando sea necesario*/
        overflow-x: hidden;
        /*Sin scroll horizontal*/
        table-layout: fixed;
        /**Forzamos a que las filas tenga el mismo ancho**/
        width: 98vw;
        /*El ancho que necesitemos*/
        border: 1px solid #c4c0c9;
    }

    #tablemodal thead {
        background-color: #e2e3e5;
        position: fixed !important;
    }


    #tablemodal th {
        border-bottom: 1px solid #c4c0c9;
        border-right: 1px solid #c4c0c9;
    }

    #tablemodal th {
        font-weight: normal;
        margin: 0;
        max-width: 9.5vw;
        min-width: 9.5vw;
        word-wrap: break-word;
        font-size: 10px;
        font-weight: bold;
        height: 3.5vh !important;
        line-height: 12px;
        vertical-align: middle;
        /*height:20px;*/
        padding: 4px;
        border-right: 1px solid #c4c0c9;
    }

    #tablemodal td {
        font-weight: normal;
        margin: 0;
        max-width: 9.5vw;
        min-width: 9.5vw;
        word-wrap: break-word;
        font-size: 11px;
        height: 3.5vh !important;
        padding: 4px;
        border-right: 1px solid #c4c0c9;
    }

    #tablemodal tbody tr:hover td,
    #tablemodal1 tbody tr:hover th {
        /*background-color: red!important;*/
        font-weight: bold;
        /*mix-blend-mode: difference;*/

    }

    #tablemodalm4 {}
</style>

<script type="text/javascript">
    $(document).on('click', '#btnShowDetailBhEnf', async function() {
        const bh_id = $(this).data('id');
        var codigo_fecha_24 = $(this).data('codigo_fecha_24');
        const tipo_atencion_id = $(this).data('tipo_atencion_id');

        ver_hoja_balance(codigo_fecha_24, bh_id, tipo_atencion_id);
        $('#modalVerBh').modal('show');
    });

    $(document).ready(function() {});

    function ver_hoja_balance(codigo_fecha_24, id_balance, tipo_atencion_id) {
        var var_url = '{{ url('hoja_balance_hidrico') }}';
        $.ajax({
            url: var_url,
            type: 'POST',
            dataType: 'JSON',
            data: {
                codigo_fecha_24: codigo_fecha_24,
                id_asegurado: $('#id_asegurado').val(),
                id_tipo_atencion: tipo_atencion_id,
                id_balance: id_balance
            },
            success: function(data) {
                $("#peso_hoja_bh").html(data.bh_general[0].peso_al_registrar_bh);
                var html_ingresos = '';
                var html_egresos = '';

                let ingreso_total_m = 0;
                let ingreso_total_t = 0;
                let ingreso_total_n = 0;
                let ingreso_total_md = 0;
                let ingreso_total_ingreso = 0;

                let egreso_total_m = 0;
                let egreso_total_t = 0;
                let egreso_total_n = 0;
                let egreso_total_md = 0;
                let egreso_total_ingreso = 0;

                $("#lista_ingresos").empty();
                $.each(data.ingresos, function(key, ingreso) {
                    if (ingreso.codigo_agrupacion == 460) {
                        egreso_total_m += parseFloat(ingreso.total_m) || 0;
                        egreso_total_t += parseFloat(ingreso.total_t) || 0;
                        egreso_total_n += parseFloat(ingreso.total_n) || 0;
                        egreso_total_md += parseFloat(ingreso.total_md) || 0;
                        egreso_total_ingreso += parseFloat(ingreso.total_caracter_fisico) || 0;

                        html_egresos += '<tr>';
                        html_egresos += '<td>' + ingreso.caracter_fisico + '</td>';
                        html_egresos += '<td style="text-align: center;"><b>' + ingreso.total_m +
                            '</b></td>';
                        html_egresos += '<td style="text-align: center;"><b>' + ingreso.total_t +
                            '</b></td>';
                        html_egresos += '<td style="text-align: center;"><b>' + ingreso.total_n +
                            '</b></td>';
                        html_egresos += '<td style="text-align: center;"><b>' + ingreso.total_md +
                            '</b></td>';
                        html_egresos += '<td style="text-align: center;"><b>' + ingreso
                            .total_caracter_fisico + '</b></td>';
                        html_egresos += '</tr>';
                    } else {
                        ingreso_total_m += parseFloat(ingreso.total_m) || 0;
                        ingreso_total_t += parseFloat(ingreso.total_t) || 0;
                        ingreso_total_n += parseFloat(ingreso.total_n) || 0;
                        ingreso_total_md += parseFloat(ingreso.total_md) || 0;
                        ingreso_total_ingreso += parseFloat(ingreso.total_caracter_fisico) || 0;
                        html_ingresos += '<tr>';
                        html_ingresos += '<td>' + ingreso.caracter_fisico + '</td>';
                        html_ingresos += '<td style="text-align: center;"><b>' + ingreso.total_m +
                            '</b></td>';
                        html_ingresos += '<td style="text-align: center;"><b>' + ingreso.total_t +
                            '</b></td>';
                        html_ingresos += '<td style="text-align: center;"><b>' + ingreso.total_n +
                            '</b></td>';
                        html_ingresos += '<td style="text-align: center;"><b>' + ingreso.total_md +
                            '</b></td>';
                        html_ingresos += '<td style="text-align: center;"><b>' + ingreso
                            .total_caracter_fisico + '</b></td>';
                        html_ingresos += '</tr>';
                    }
                });

                $("#lista_egresos").empty();
                $.each(data.egresos, function(key, egreso) {
                    egreso_total_m += parseFloat(egreso.total_m) || 0;
                    egreso_total_t += parseFloat(egreso.total_t) || 0;
                    egreso_total_n += parseFloat(egreso.total_n) || 0;
                    egreso_total_md += parseFloat(egreso.total_md) || 0;
                    egreso_total_ingreso += parseFloat(egreso.total_caracter_fisico) || 0;

                    html_egresos += '<tr>';
                    html_egresos += '<td>' + egreso.caracter_fisico + '</td>';
                    html_egresos += '<td style="text-align: center;"><b>' + egreso.total_m +
                        '</b></td>';
                    html_egresos += '<td style="text-align: center;"><b>' + egreso.total_t +
                        '</b></td>';
                    html_egresos += '<td style="text-align: center;"><b>' + egreso.total_n +
                        '</b></td>';
                    html_egresos += '<td style="text-align: center;"><b>' + egreso.total_md +
                        '</b></td>';
                    html_egresos += '<td style="text-align: center;"><b>' + egreso
                        .total_caracter_fisico + '</b></td>';
                    html_egresos += '</tr>';
                });
                html_egresos += '<tr>';
                html_egresos += '<td><b>TOTAL</b></td>';
                html_egresos += '<td style="text-align: center;"><b>' + egreso_total_m + '</b></td>';
                html_egresos += '<td style="text-align: center;"><b>' + egreso_total_t + '</b></td>';
                html_egresos += '<td style="text-align: center;"><b>' + egreso_total_n + '</b></td>';
                html_egresos += '<td style="text-align: center;"><b>' + egreso_total_md + '</b></td>';
                html_egresos += '<td style="text-align: center;"><b>' + egreso_total_ingreso + '</b></tn>';
                html_egresos += '</tr>';
                html_egresos += '<tr>';
                html_egresos += '<td><b>BH 24 HORAS</b></td>';
                html_egresos += '<td style="text-align: center;"> - </td>';
                html_egresos += '<td style="text-align: center;"> - </td>';
                html_egresos += '<td style="text-align: center;"> - </td>';
                html_egresos += '<td style="text-align: center;"> - </td>';
                html_egresos += '<td style="text-align: center;"><b>' + (ingreso_total_ingreso -
                    egreso_total_ingreso) + '</b></tn>';
                html_egresos += '</tr>';
                $('#lista_egresos').append(html_egresos);
                html_ingresos += '<tr>';
                html_ingresos += '<td><b>TOTAL</b></td>';
                html_ingresos += '<td style="text-align: center;"><b>' + ingreso_total_m + '</b></td>';
                html_ingresos += '<td style="text-align: center;"><b>' + ingreso_total_t + '</b></td>';
                html_ingresos += '<td style="text-align: center;"><b>' + ingreso_total_n + '</b></td>';
                html_ingresos += '<td style="text-align: center;"><b>' + ingreso_total_md + '</b></td>';
                html_ingresos += '<td style="text-align: center;"><b>' + ingreso_total_ingreso +
                    '</b></tn>';
                html_ingresos += '</tr>';
                html_ingresos += '<tr>';
                html_ingresos += '<td><b>BALANCE</b></td>';
                html_ingresos += '<td style="text-align: center;"><b>' + (ingreso_total_m -
                    egreso_total_m) + '</b></td>';
                html_ingresos += '<td style="text-align: center;"><b>' + (ingreso_total_t -
                    egreso_total_t) + '</b></td>';
                html_ingresos += '<td style="text-align: center;"><b>' + (ingreso_total_n -
                    egreso_total_n) + '</b></td>';
                html_ingresos += '<td style="text-align: center;"><b>' + (ingreso_total_md -
                    egreso_total_md) + '</b></td>';
                html_ingresos += '<td style="text-align: center;"> - </tn>';
                html_ingresos += '</tr>';
                $('#lista_ingresos').append(html_ingresos);
            }
        })
    };
</script>


<body class="hold-transition skin-blue sidebar-mini">
    <div>
        <section class="content">
            <div class="col-md-12">
                <div class="box box-primary">
                    <br>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" id="tablemodalm6"
                                style="font-size: 11px; margin: 0;">
                                <thead>
                                    <tr>
                                        <th>FECHA Y HORA</th>
                                        <th>PROFESIONAL DE SALUD RESPONSABLE</th>
                                        <th>PESO</th>
                                        <th>TURNO</th>
                                        <th>BH POR TURNO</th>
                                        <th>BH 24H</th>
                                        <th>DETALLE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($results) && count($results) > 0)
                                        @foreach ($results as $r)
                                            <tr>
                                                <td style="vertical-align: middle;">
                                                    {{ $r->fecha_registro . ' - ' . $r->hora_registro }}
                                                </td>
                                                <td>{{ $r->nombre . ' ' . $r->paterno . ' ' . $r->materno }}</td>
                                                <td>{{ $r->peso_al_registrar_bh }}</td>
                                                <td>{{ $r->turno }}</td>
                                                <td>{{ $r->diferencia }}</td>
                                                <td>{{ $r->bh24 }}</td>
                                                <td style="text-align: center;">
                                                    <button type="button" class="btn btn-success btn-xs"
                                                        data-toggle="tooltip" data-placement="top" title="Ver Detalle"
                                                        id="btnShowDetailBhEnf" data-id="{{ $r->id }}"
                                                        data-codigo_fecha_24="{{ $r->codigo_fecha_24 }}"
                                                        data-tipo_atencion_id="{{ $r->tipo_atencion_bh }}"><i
                                                            class="fa fa-eye" aria-hidden="true"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" style="text-align: center;">No hay registros.</td>
                                        </tr>   
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="modal fade" id="modalVerBh" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg" style="width: 1350px;" role="document">
            <div class="modal-content">

                <div class="modal-header" style="font-size: 20px !important;">
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close"><span>&times;</span></button>
                    <b id="tituloFormularioBh-ver">DETALLE DE BALANCED HIDRICO</b>
                </div>

                <div class="modal-body">
                    <form id="FormularioEvolucionesVer" name="FormularioEvolucionesVer" enctype="multipart/form-data"
                        method="POST">
                        <div class="nav-tabs-custom">
                            <div class="tab-content">
                                <div class="tab-pane active" style="font-size: 11px;">
                                    <div class="form-group" id="container_emergencia">
                                        <div class="panel with-nav-tabs panel-default"
                                            style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <table class="table table-striped table-list-search">
                                                            <tr>
                                                                <td style="text-align: center;">Peso:</td>
                                                                <td style="text-align: center;"><b><span
                                                                            id="peso_hoja_bh"></span></b></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="table-responsive">
                                                            <div class="col-md-12" style="text-align: center;">
                                                                <b>INGRESOS</b>
                                                            </div>
                                                            <table id="example"
                                                                class="table table-striped table-list-search">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="text-align: center;" width="40%">
                                                                            DESCRIPCIÓN</th>
                                                                        <th style="text-align: center;" width="5%">M
                                                                        </th>
                                                                        <th style="text-align: center;" width="5%">T
                                                                        </th>
                                                                        <th style="text-align: center;" width="5%">N
                                                                        </th>
                                                                        <th style="text-align: center;" width="5%">
                                                                            MD</th>
                                                                        <th style="text-align: center;"width="5%">
                                                                            TOTAL</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="lista_ingresos">
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="table-responsive">
                                                            <div class="col-md-12" style="text-align: center;">
                                                                <b>EGRESOS</b>
                                                            </div>
                                                            <table id="example"
                                                                class="table table-striped table-list-search">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="text-align: center;" width="40%">
                                                                            DESCRIPCIÓN</th>
                                                                        <th style="text-align: center;" width="5%">M
                                                                        </th>
                                                                        <th style="text-align: center;">T</th>
                                                                        <th style="text-align: center;">N</th>
                                                                        <th style="text-align: center;">MD</th>
                                                                        <th style="text-align: center;">TOTAL</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="lista_egresos">
                                                                </tbody>
                                                            </table>
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
            </div>
        </div>
</body>
