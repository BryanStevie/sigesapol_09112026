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
    $(document).ready(function() {

    });

    function ver_det_interconsulta(id, id_prestacion, id_evaluacion) {
        $('#nrorecmodal').text(id);
        $.ajax({
            url: '/mostrar_interconsulta_envio/' + id + '/' + id_prestacion + '/' + id_evaluacion,
            async: false,
            success: function(result) {
                $("#muestra-det-interconsulta1").html(result);
            },
            complete: function() {
                $("#myModalInterconsulta1").modal();
            }
        });
    }

    function ver_rpta_interconsulta(id, id_prestacion, id_evaluacion) {
        $('#nrorecmodal').text(id);
        $.ajax({
            url: '/mostrar_interconsulta_rpta/' + id + '/' + id_prestacion + '/' + id_evaluacion,
            async: false,
            success: function(result) {
                $("#muestra-det-interconsulta2").html(result);
            },
            complete: function() {
                $("#myModalInterconsulta2").modal();
            }
        });
    }
</script>


<body class="hold-transition skin-blue sidebar-mini">
    <div>
        <section class="content">

            <div class="col-md-12">
                <div class="box box-primary">
                    <br>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" id="tablemodalm5">
                                <thead>
                                    <tr>
                                        <th>N</th>
                                        <th>Fecha / Hora Solicitud</th>
                                        <th>Tipo de Atencion</th>
                                        <th>Médico Solicitante</th>
                                        <th>Especialidad</th>
                                        <th>Médico Especialista</th>
                                        <th>Fecha / Hora Respuesta</th>
                                        <th style="text-align:center">Ver Atencion</th>
                                        <th style="text-align:center">Ver Rpta</th>
                                    </tr>
                                </thead>
                                <tbody id="muestra-interconsultas2" style="padding-top:32px">
                                    <?php foreach($interconsultas as $rowHorario):?>
                                    <tr>
                                        <td><?php echo $rowHorario->id; ?></td>
                                        <td><?php echo $rowHorario->fecha_atencion . ' ' . $rowHorario->hora_atencion; ?></td>
                                        <td><?php echo $rowHorario->tipo_atencion; ?></td>
                                        <td><?php echo $rowHorario->nombre_medico . ' ' . $rowHorario->paterno_medico . ' ' . $rowHorario->materno_medico; ?></td>
                                        <td><?php echo $rowHorario->nombre_consultorio; ?></td>
                                        <td><?php echo $rowHorario->nombre_medico2 . ' ' . $rowHorario->paterno_medico2 . ' ' . $rowHorario->materno_medico2; ?></td>
                                        <td><?php echo $rowHorario->fecha_rpta_atencion != null ? $rowHorario->fecha_rpta_atencion . ' ' . $rowHorario->hora_rpta_atencion : ''; ?></td>
                                        <td class="text-center">
                                            <?php 
                                          if ( $rowHorario->id_estado_reg != 0) {
                                          ?>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true"
                                                title="<b>Ver Atencion</b>">
                                                <a href="javascript:void(0)"
                                                    onClick="ver_det_interconsulta({{ $rowHorario->id }},{{ $rowHorario->id_prestacion }},{{ $rowHorario->id_evaluacion }})"
                                                    class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>
                                            </div>
                                            <?php
                                          } else {
                                          ?>
                                            <button class="btn btn-primary btn-xs" disabled=""><i
                                                    class="fa fa-eye"></i></button>
                                            <?php 
                                          }
                                          ?>
                                        </td>
                                        <td class="text-center">
                                            <?php 
                                          if ( $rowHorario->id_estado_reg != 0  && $rowHorario->fecha_rpta_atencion != null) {
                                          ?>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true"
                                                title="<b>Ver Atencion</b>">
                                                <a href="javascript:void(0)"
                                                    onClick="ver_rpta_interconsulta({{ $rowHorario->id }},{{ $rowHorario->id_prestacion }},{{ $rowHorario->id_evaluacion }})"
                                                    class="btn btn-warning btn-xs"><i class="fa fa-eye"></i></a>
                                            </div>
                                            <?php
                                          } else {
                                          ?>
                                            <button class="btn btn-warning btn-xs" disabled=""><i
                                                    class="fa fa-eye"></i></button>
                                            <?php 
                                          }
                                          ?>
                                        </td>
                                    </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <!--/.col (left) -->

            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

</body>
