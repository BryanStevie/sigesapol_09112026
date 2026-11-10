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
                                        <th>N</th>
                                        <th>FECHA/HORA REGISTRO</th>
                                        <th>PERSONAL DE SALUD RESPONSABLE</th>
                                        <th>UBICACION</th>
                                        <th>RESP</th>
                                        <th>PULSO </th>
                                        <th>PRESION ARTERIAL</th>
                                        <th>TEMP</th>
                                        <th>PESO</th>
                                        <th>TURNO</th>
                                        <th>BH POR TURNO</th>
                                        <th>BH 24 HORAS</th>
                                        <th>VER</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($arreglo_lista) && count($arreglo_lista) > 0)
                                        @foreach ($arreglo_lista as $dato)
                                            <tr>
                                                <th>{{ $dato->numero }}</th>
                                                <th>{{ $dato->fecha }}</th>
                                                <th>{{ $dato->responsable }}</th>
                                                <th>{{ $dato->ubica }}</th>
                                                <th>{{ $dato->respiratorio }}</th>
                                                <th>{{ $dato->cardiaca }}</th>
                                                <th>{{ $dato->presionx }} / {{ $dato->presiony }}</th>
                                                <th>{{ $dato->temperatura }}</th>
                                                <th style="color: #003366;"> {{ $dato->peso }}</th>
                                                <th>{{ $dato->turno }}</th>
                                                <th style="color: #003366;">{{ $dato->balance_turno }}</th>
                                                <th style="color: #003366;">{{ $dato->balance_x24 }}</th>
                                                <th style="color: #003366;">
                                                    <a href="/ver_pdf_signos_vitales_unidad/<?php echo $dato->id_signo_vital; ?>"
                                                        class="btn btn-primary btn-xs" title="ver_pdf"
                                                        target="_blank"><i class="fa fa-eye"></i></a>
                                                </th>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="13" style="text-align: center;">No hay registros.</td>
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
</body>
