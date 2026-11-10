
<title>Sistema de Farmacia</title>

<style>
/*****************/
.modal-dialog {
  width: 90%;
  }

#tablemodal{
    border-spacing: 0;
    display: flex;
    max-height: 80vh;
    overflow-y: auto; 
    overflow-x: hidden;
    table-layout: fixed;
    width: 98vw;
    border:1px solid #c4c0c9;
}

#tablemodal thead{
    background-color: #e2e3e5;
    position: fixed !important;
}


#tablemodal th{
    border-bottom: 1px solid #c4c0c9;
    border-right: 1px solid #c4c0c9;
}

#tablemodal th{
    font-weight: normal;
    margin: 0;
    max-width: 9.5vw; 
    min-width: 9.5vw;
    word-wrap: break-word;
    font-size: 10px;
  font-weight:bold;
    height: 3.5vh !important;
  line-height:12px;
  vertical-align:middle;
  /*height:20px;*/
    padding: 4px;
    border-right: 1px solid #c4c0c9;
}

#tablemodal td{
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

#tablemodal tbody tr:hover td, #tablemodal1 tbody tr:hover th {
  font-weight:bold;
}

</style>
<script type="text/javascript">
    function ver_det_atencion(id){
        $('#nrorecmodal').text(id);
        $.ajax({
        url: '/mostrar_atencion/' + id,
                async: false,
                success: function(result){
                $("#muestra-det-atencion").html(result);
                },
                complete: function () {
                $("#myModalAtencion").modal();
                }
        });
    }

    // Detalle de examen OBSERVADO (examen de imágenes).
    function verObsExamenHistorial(btn) {
        var $btn = $(btn);
        $btn.blur();
        try { $btn.closest('[data-toggle="tooltip"]').tooltip('hide'); } catch (e) {}
        $('.tooltip, .ui-tooltip').remove();

        var esc = function (v) { return $('<div>').text(v || '-').html(); };

        $('#overlayObsExamenHistorial').remove();
        var html =
            '<div id="overlayObsExamenHistorial" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:99999;">' +
              '<div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:550px; max-width:95%; background:#fff; border-radius:6px; padding:20px; box-shadow:0 5px 15px rgba(0,0,0,.5);">' +
                '<div class="panel panel-primary" style="margin-bottom:15px;">' +
                  '<div class="panel-heading" style="text-align:center; font-size:16px;"><b>EXAMEN <span style="color:#ff6b6b;">OBSERVADO</span></b></div>' +
                  '<div class="panel-body">' +
                    '<table class="table" style="margin-bottom:0; font-size:13px;">' +
                      '<tr><td style="width:150px; white-space:nowrap;"><b>EXAMEN</b></td><td>: ' + esc($btn.data('examen')) + '</td></tr>' +
                      '<tr><td><b>ESTADO</b></td><td>: <b style="color:#d9534f;">OBSERVADO</b></td></tr>' +
                      '<tr><td><b>FECHA Y HORA</b></td><td>: ' + esc($btn.data('fecha')) + '</td></tr>' +
                      '<tr><td><b>RESPONSABLE</b></td><td>: ' + esc($btn.data('responsable')) + '</td></tr>' +
                      '<tr><td><b>MOTIVO</b></td><td style="word-break:break-word;">: ' + esc($btn.data('obs')) + '</td></tr>' +
                    '</table>' +
                  '</div>' +
                '</div>' +
                '<div style="text-align:center;"><button type="button" class="btn btn-success" id="btnCerrarObsExamenHistorial"><b>CERRAR</b></button></div>' +
              '</div>' +
            '</div>';
        $('body').append(html);
    }

    $(document).off('click.obsHist').on('click.obsHist', '#btnCerrarObsExamenHistorial', function () {
        $('#overlayObsExamenHistorial').remove();
    }).on('click.obsHist', '#overlayObsExamenHistorial', function (e) {
        if (e.target === this) { $('#overlayObsExamenHistorial').remove(); }
    });
</script>

<body class="hold-transition skin-blue sidebar-mini">
    <div>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <br>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" id="tablemodalm2">
                                    <thead>
                                        <tr>
                                            <th>TIPO DOC</th>
                                            <th>NUMERO DOC</th>
                                            <th>FECHA</th>
                                            <th>HORA</th>
                                            <th>ESTABLECIMIENTO</th>
                                            <th>TIPO ATENCION</th>
                                            <th>CONSULTORIO</th>
                                            <th>MEDICO</th>
                                            <th>EXÁMENES</th>
                                            <th class="text-center">VER</th>
                                        </tr>
                                    </thead>
                                    <tbody id="muestra-atenciones" style="padding-top:32px">
                                        <?php foreach($examenes as $r):?>
                                        <tr>
                                            <td><?php echo $r->tipo_doc_ident?></td>
                                            <td><?php echo $r->nro_doc_ident?></td>
                                            <td><?php echo $r->dia?></td>
                                            <td><?php echo $r->hora?></td>
                                            <td><?php echo $r->establecimiento?></td>
                                            <td><?php
                                                if ($r->id_tipo_atencion == 1) {
                                                    echo $r->tipo_atencion;
                                                }else if ($r->id_tipo_atencion == 2) {
                                                    if ($r->id_evaluacion !== 0) {
                                                        echo $r->tipo_atencion . " - EVALUACIÓN";
                                                    } else {
                                                        echo $r->tipo_atencion;
                                                    }
                                                } else {
                                                    echo $r->tipo_atencion;
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $r->consultorio?></td>
                                            <td><?php echo $r->nombre_medico." ".$r->paterno_medico." ".$r->materno_medico?></td>
                                            <td>
                                                <div style="display: flex; flex-direction: column;">
                                                    <?php foreach($servicio_examen as $servicio):
                                                        if($servicio->id == $r->id):
                                                            if(!empty($servicio->obs_imagenes)):
                                                                echo "<span style='font-size : 0.8rem; font-weight: bold; color: #d9534f;'>". e($servicio->codigo_rad . " - " . $servicio->descripcion_corta) ." (OBSERVADO)</span>";
                                                            else:
                                                                echo "<span style='font-size : 0.8rem; font-weight: bold;'>". $servicio->codigo_rad . " - " . $servicio->descripcion_corta ."</span>";
                                                            endif;
                                                        endif;
                                                    endforeach;?>
                                                </div></td>
                                            <td>
                                                <?php 
                                                if ($r->ruta_radiologia == "") {
                                                ?>
                                                    <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-edit"></i></button>
                                                <?php 
                                                } else {
                                                        echo "<table><tr>";
                                                        foreach($servicio_examen as $servicio):
                                                            echo "<td>";
                                                            if($servicio->id == $r->id):
                                                                if(!empty($servicio->obs_imagenes)):
                                                ?>
                                                                    <div style="display: inline-block;" data-toggle="tooltip" data-placement="top" data-html="true" title="<b style='color:#ff6b6b'>OBSERVADO</b><br>{{ e($servicio->obs_imagenes) }}">
                                                                        <button type="button" class="btn btn-xs" onclick="verObsExamenHistorial(this)"
                                                                            style="background-color: #d9534f; color: #fff; outline: none; box-shadow: none;"
                                                                            data-examen="{{ $servicio->codigo_rad }} - {{ $servicio->descripcion_corta }}"
                                                                            data-obs="{{ $servicio->obs_imagenes }}"
                                                                            data-fecha="{{ $servicio->obs_imagenes_at ? \Carbon\Carbon::parse($servicio->obs_imagenes_at)->format('d/m/Y H:i:s') : '' }}"
                                                                            data-responsable="{{ $servicio->obs_imagenes_responsable }}">
                                                                            <i class="fa fa-eye"></i>
                                                                        </button>
                                                                    </div>
                                                <?php
                                                                elseif($servicio->flag_informe == "1" && $servicio->flag_validated == "1"):
                                                                    $adendas_servicio = collect($adendas)->where('id_pp', $servicio->id_pp);
                                                                    $cant_adendas = $adendas_servicio->count();
                                                                    $id_informe_adicional_ultimo = $cant_adendas > 0 ? $adendas_servicio->last()->id_informe_adicional : null;
                                                                    $ruta_adenda =$cant_adendas > 0 ? $adendas_servicio->last()->ruta_informe_radiologia_adicional: null;
                                                ?>              
                                                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="{{$servicio->codigo_rad}} - {{$servicio->descripcion_corta}}">
                                                                        <!--a target="_blank" href="/print_examen_radiologia_lns/{{$r->id}}/{{$servicio->id_servicio_radiologia}}/{{$servicio->id_pp}}" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a-->
                                                                        <?php if($cant_adendas > 0): ?>
                                                                            <a target="_blank" href="/informe_imagenes/{{$r->id}}/{{$servicio->id_servicio_radiologia}}/{{$servicio->id_pp}}/{{$id_informe_adicional_ultimo}}/{{$servicio->flag}}/{{$ruta_adenda}}" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a>
                                                                        <?php else:?>
                                                                            <a target="_blank" href="/informe_imagenes/{{$r->id}}/{{$servicio->id_servicio_radiologia}}/{{$servicio->id_pp}}/0/{{$servicio->flag}}/{{$servicio->ruta_prestacion_procedimiento}}" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a>
                                                                        <?php endif;?>

                                                                    </div>
                                                                <?php
                                                                else:
                                                                ?>
                                                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="{{$servicio->codigo_rad}} - {{$servicio->descripcion_corta}}">
                                                                        <button class="btn btn-info btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                                                    </div>
                                                <?php
                                                                endif;
                                                            endif;
                                                            echo "</td>";
                                                        endforeach;
                                                        echo "</tr></table>";

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
                </div>
            </div>
        </section>
    </div>

<!-- /.content-wrapper -->
<div class="modal fade" id="myModalAtencion" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">DETALLE DEL EXAMEN DE IMAGENOLOGIA. <span id="nrorecmodal"></span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-atencion"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
</body>

    
    