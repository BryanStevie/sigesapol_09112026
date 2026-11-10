<title>Sistema de Farmacia</title>

<style>
/*****************/
.modal-dialog {
  width: 90%;
  }
  
#tablemodal{
    border-spacing: 0;
    display: flex;/*Se ajuste dinamicamente al tamano del dispositivo**/
    max-height: 80vh; /*El alto que necesitemos**/
    overflow-y: auto; /**El scroll verticalmente cuando sea necesario*/
    overflow-x: hidden;Sin scroll horizontal
    table-layout: fixed;/**Forzamos a que las filas tenga el mismo ancho**/
    width: 98vw; /*El ancho que necesitemos*/
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
  /*background-color: red!important;*/
  font-weight:bold;
  /*mix-blend-mode: difference;*/
  
}

#tablemodalm1{
  
}

.list-categorias{
    padding: 0;
    margin: 0;
    font-size: 1rem;
}

.hide-element {
    display: none;
}

.hide-button-text {
    font-size: 0;
    line-height: 0;
    width: 1px;
    height: 1px;
    overflow: hidden;
}

.icon-visible {
    color: rgb(95, 129, 95);
}

.icon-visible:hover {
    color: green;
}

.icon-hidden {
    color: red;
}

.toggleExamenes {
    font-size: 2rem;
}

    #modalExamenes .modal-dialog {
        width: 400px !important;
        margin: 30px auto;
    }

    @media (max-width: 768px) {
        #modalExamenes .modal-dialog {
            width: auto !important;
            margin: 10px;
        }
    }

    div.div-icon {
        position: absolute;
        right: 10px;
        top: 10px;
        bottom: 8px;
        display: flex;
        align-items: flex-start;
        align-content: stretch;
        flex-wrap: wrap;
    }

</style>

<script type="text/javascript">

$(document).ready(function() {
    
});

/*function ver_det_atencion(id){
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
    }*/

    

    $(".toggleExamenes").click(function(){
        var registro = $(this).data('registro');
        $("#elementoOcultar" + registro).toggle();

        var icono = $(this).find('i');

        // icono.toggleClass('icon-visible');
        icono.toggleClass('icon-hidden');
    });

    // Modal de Examenes
    $('#modalExamenes').on('show.bs.modal', function (event) {
        var button   = $(event.relatedTarget);
        var examenes = button.data('examenes');
        var orden    = button.data('orden');

        $('#modalOrden').text(orden);

        var lista = $('#listaExamenes');
        lista.empty();

        // Agrupar por clasificacion
        var atendidos   = [];
        var pendientes  = [];
        var sinReactivo = [];

        if (Array.isArray(examenes)) {
            $.each(examenes, function(i, examen) {
                if (examen.clasificacion === 'E')       atendidos.push(examen.descripcion);
                else if (examen.clasificacion === 'P')  pendientes.push(examen.descripcion);
                else if (examen.clasificacion === 'SR') sinReactivo.push(examen.descripcion);
            });
        }

        // Construir HTML del body
        let html = `<p style="color:#2d6a2d;"><b>SOLICITADOS: ${examenes.length}</b></p>`;
        
        html += `<p style="color:#2d6a2d; margin:10px 0 0 0;"><b>ATENDIDOS: ${atendidos.length}</b></p>`;
        $.each(atendidos, function(i, nombre) {
            html += `<p style="margin: 0 0;">${nombre}</p>`;
        });

        html += `<p style="color:#2d6a2d; margin:10px 0 0 0;"><b>PENDIENTES: ${pendientes.length}</b></p>`;
        $.each(pendientes, function(i, nombre) {
            html += `<p style="margin: 0 0;">${nombre}</p>`;
        });

        html += `<p style="color:#2d6a2d; margin:10px 0 0 0;"><b>SIN REACTIVO: ${sinReactivo.length}</b></p>`;
        $.each(sinReactivo, function(i, nombre) {
            html += `<p style="margin: 0 0;">${nombre}</p>`;
        });

        lista.html(html);
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
                        <!-- <p><span class="label label-info"><i class="fa fa-info"></i></span> Exámenes registrados antes de la Implementacion del <b>SIGESAPOL</b></p> -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm" id="tablemodalm1">
                                <thead>
                                    <tr>
                                        <!-- <th>TIPO DOC</th> -->
                                        <!-- <th>NUMERO DOC</th>
                                        <th>FECHA</th>
                                        <th>ESTABLECIMIENTO</th>
                                        <th>SERVICIO</th>
                                        <th>N° ORDEN</th>
                                        <th>RESULTADOS</th>
                                        <th class="text-center">VER</th> -->
                                        <th class="text-center">Establecimiento</th>
                                        <th class="text-center" style="width: 60px !important;">N° de Doc (Paciente)</th>
                                        <th class="text-center">Servicio solicitante</th>
                                        <th class="text-center">Toma de Muestras</th>
                                        <th class="text-center">Resultados</th>
                                        <th class="text-center">Observaciones de Laboratorio</th>
                                        <th class="text-center">Ver Resultado</th>
                                    </tr>
                                </thead>
                                <tbody id="muestra-atenciones" style="padding-top:32px">
                                    <?php
                                    $n=1;
                                    foreach($examenes as $r):?>
                                    <tr>
                                        <td>
                                            <?php 
                                                $elementoOcultar = "elementoOcultar" . $n;
                                            ?>
                                            <a class="toggleExamenes" data-registro="<?php echo $n;?>"><i class="fa fa-plus-circle icon-visible" aria-hidden="true"></i></a>
                                            <?php echo $r->tipo_doc_ident?>
                                        </td>
                                        <td><?php echo $r->nro_doc_ident?></td>
                                        <td><?php echo date("d/m/Y", strtotime($r->dia)) . " " . $r->hora;?></td>
                                        <td><?php echo $r->establecimiento?></td>
                                        <td><?php echo $r->tipo_atencion . " - " . $r->consultorio?></td>
                                        <!-- <td><?php //echo $r->nombre_medico." ".$r->paterno_medico." ".$r->materno_medico?></td> -->
                                        <td></td>
                                        <td></td>
                                        <td class="text-center">
                                
                                            <?php 
                                            if ($r->id_establecimiento == 76):
                                                if ($r->fecha_toma_muestra != null):
                                            ?>
                                                <a target="_blank" href="/print_examen_laboratorio_lns/{{$r->id}}/{{$r->nro_doc_ident}}/{{$r->fecha_toma_muestra}}/{{$r->cod_orden_lab}}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                                <!-- <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button> -->
                                            <?php
                                                else:
                                            ?>
                                                    <a target="_blank" href="" class="btn btn-info btn-xs" disabled><i class="fa fa-eye"></i></a>
                                            <?php
                                                endif;
                                            else:

                                            if ($r->ruta_laboratorio == "") {
                                            ?>
                                            <!-- si no esta firmado se pone BOTON DE EDITAR -->
                                                <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-edit"></i></button>
                                            <?php 
                                            } else {
                                            ?>      <table>
                                                    <tr><td>
                                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b> Bioquimica</b>">
                                                    <?php 
                                                        if($r->tipo_bioquimica=='SI'):
                                                            if($r->rpta_bioquimica=='SI'):
                                                    ?>
                                                                <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/1" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                                    <?php 
                                                            else: ?>
                                                                <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                                    <?php   endif;
                                                        endif;
                                                    ?>
                                                    </div>
                                                    </td>
                                                    <td>
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Hematologia</b>">
                                                    <?php
                                                        if($r->tipo_hematologia=='SI'):
                                                            if($r->rpta_hematologia=='SI'):
                                                    ?>
                                                                <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/2" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                                    <?php 
                                                            else: ?>
                                                                <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                                    <?php   endif;
                                                        endif;
                                                    ?>
                                                    </div>
                                                    </td>
                                                    <td>
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Inmunologia</b>">
                                                    <?php
                                                        if($r->tipo_inmunologia=='SI'):
                                                            if($r->rpta_inmunologia=='SI'):
                                                    ?>
                                                                <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/3" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                                    <?php 
                                                            else: ?>
                                                                <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                                    <?php   endif;
                                                        endif;
                                                    ?>
                                                    </div>
                                                    </td>
                                                    <td>
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Microbiologia</b>">
                                                    <?php
                                                        if($r->tipo_microbiologia=='SI'):
                                                            if($r->rpta_microbiologia=='SI'):
                                                    ?>
                                                                <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/4" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                                    <?php 
                                                            else: ?>
                                                                <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                                    <?php   endif;
                                                        endif;
                                                    ?>
                                                    </div>
                                                    </td>
                                                    <td>
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Anatomia Patologica</b>">
                                                    <?php
                                                        if($r->tipo_anatomia=='SI'):
                                                            if($r->rpta_anatomia=='SI'):
                                                    ?>
                                                                <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/5" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                                    <?php 
                                                            else: ?>
                                                                <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                                    <?php   endif;
                                                        endif;
                                                    ?>
                                                    </div>
                                                    </td>
                                                    <td>
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Citopatologia</b>">
                                                    <?php
                                                        if($r->tipo_citopatologia=='SI'):
                                                            if($r->rpta_citopatologia=='SI'):
                                                    ?>
                                                                <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/6" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                                    <?php 
                                                            else: ?>
                                                                <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                                    <?php   endif;
                                                        endif;
                                                    ?>
                                                    </div>
                                                    </td>
                                                    <td>
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Citogenetico</b>">
                                                    <?php
                                                        if($r->tipo_citogenetico=='SI'):
                                                            if($r->rpta_citogenetico=='SI'):
                                                    ?>
                                                                <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/7" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                                    <?php 
                                                            else: ?>
                                                                <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                                    <?php   endif;
                                                        endif;
                                                    ?>
                                                    </div>
                                                    </td>
                                                    <td>
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Quirurgica</b>">
                                                    <?php
                                                        if($r->tipo_quirurgica=='SI'):
                                                            if($r->rpta_quirurgica=='SI'):
                                                    ?>
                                                                <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/8" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                                    <?php 
                                                            else: ?>
                                                                <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                                    <?php   endif;
                                                        endif;
                                                    ?>
                                                    </div>
                                                    </td>
                                                    <td>
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Otro</b>">
                                                    <?php
                                                        if($r->tipo_otro=='SI'):
                                                            if($r->rpta_otro=='SI'):
                                                    ?>
                                                                <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/9" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                                    <?php 
                                                            else: ?>
                                                                <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                                    <?php   endif;
                                                        endif;
                                                    ?>
                                                    </div>
                                                    </td>
                                                    <td>
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Reproductivo</b>">
                                                    <?php
                                                        if($r->tipo_reproductiva=='SI'):
                                                            if($r->rpta_reproductiva=='SI'):
                                                    ?>
                                                                <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/10" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                                    <?php 
                                                            else: ?>
                                                                <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                                    <?php   endif;
                                                        endif;
                                                    ?>
                                                    </div>
                                                    </td>
                                                    <td>
                                                        <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Covid-19</b>">
                                                    <?php
                                                        if($r->tipo_covid=='SI'):
                                                            if($r->rpta_covid=='SI'):
                                                    ?>
                                                                <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/11" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                                    <?php 
                                                            else: ?>
                                                                <button class="btn btn-success btn-xs" disabled=""><i class="fa fa-eye"></i></button>
                                                    <?php   endif;
                                                        endif;
                                                    ?>
                                                    </div>
                                                    </td>
                                                    </tr>
                                                    </table>
                                                    <?php
                                                }

                                            endif;
                                            ?>
                                        </td>
                                    </tr>
                                    <tr id="<?php echo $elementoOcultar;?>" class="hide-element">
                                        <?php 
                                            $item_categorias = "";
                                            $item_categorias = explode('","', $r->procedimiento_laboratorio);
                                            $item_categorias = str_replace('{','', $item_categorias);
                                            $item_categorias = str_replace('}','', $item_categorias);
                                            $item_categorias = str_replace('"','', $item_categorias);
                                            
                                            
                                        ?>
                                        <td colspan="9"><?php
                                            if ($item_categorias !== "") {
                                                echo "<ul>";
                                                foreach ($item_categorias as $key => $value) {
                                                    echo "<li><p class='list-categorias'>" . $item_categorias[$key] . "</p></li>";
                                                }
                                                echo "</ul>";
                                            } else {
                                                echo "&nbsp"; 
                                            } 
                                        ?></td>
                                    </tr>
                                    <?php 
                                    $n++;
                                    endforeach;
                                    ?>

                                    @if (!empty($ordenes_laboratorio))
                                        @foreach($ordenes_laboratorio as $q)
                                                <tr>
                                                    <td style="font-size:13px">{{ $q->establecimiento }}</td>
                                                    <td>{{ $q->nroDocumento }}</td>
                                                    <td>{{ $q->tipoAtencion }}</td>
                                                    <td>
                                                        <b>Fecha: </b>{{ $q->fechaMuestra }}<br/>
                                                        <b>N° de Orden: </b>{{ $q->orden }}
                                                    </td>
                                                    <td style="position: relative;">
                                                        @if ($q->tiene_info)
                                                            <b>Solicitados: </b><span>{{$q->solicitado}}</span><br/>
                                                            <b>Atendidos: </b><span>{{$q->atendido}}</span><br/>
                                                            <b>Pendientes: </b><span>{{$q->pendiente}}</span><br/>
                                                            <b>Sin Reactivo: </b><span>{{$q->sin_reactivo}}</span>

                                                            <div class="div-icon">
                                                                <button type="button" class="btn btn-primary btn-xs" 
                                                                    data-toggle="modal" 
                                                                    data-target="#modalExamenes" 
                                                                    data-examenes="{{ json_encode($q->examenes) }}"
                                                                    data-orden="{{ $q->orden }}">
                                                                    <i class="fa fa-eye"></i>
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($q->tiene_info)
                                                            <b>Obs 1: </b>{{ $q->toma_muestra_primera_obs }}<br/>
                                                            <b>Obs 2: </b>{{ $q->toma_muestra_segunda_obs }}
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <a target="_blank" href="/print_examen_laboratorio_lns/0/{{$q->nroDocumento}}/{{$q->fechaMuestra}}/{{$q->orden}}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                  </div>
                <!-- /.box -->
                </div>
                <!--/.col (left) -->
            </div>
          <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalExamenes" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                    <h4 class="modal-title"><span style="font-size: 20px;">EXAMENES SOLICITADOS</span></h4>
                </div>
                <div class="modal-body">
                    <ul id="listaExamenes" class="list-group"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

<!-- /.content-wrapper -->
<!-- <div class="modal fade" id="myModalAtencion" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">DETALLE DEL EXAMEN DE LABORATORIO. <span id="nrorecmodal"></span></h5>
            </div>
            <div class="modal-body">
                <div id="muestra-det-atencion"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div> -->
</body>

    
    