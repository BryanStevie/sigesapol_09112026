<title>Sistema de Farmacia</title>

<style>
/*****************/
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}

</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />

<script type="text/javascript">

$(document).ready(function() {
    
});
</script>



<div class="col-md-12">
    <p></p>
    <p><span class="label label-info"><i class="glyphicon glyphicon-flash"></i></span> Exámenes de Laboratorios registrados antes de la Implementacion del <b>SIGESAPOL</b></p>
    <div class="box box-primary">
        <br>
        <div class="box-body">
            <div class="table-responsive">
                <table id="customers">
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
                            <th class="text-center">VER</th>
                        </tr>
                    </thead>
                    <tbody id="muestra-atenciones" style="padding-top:32px">
                        <?php
                            foreach($examenes as $r):?>
                        <tr>
                            <td><small><?php echo $r->tipo_doc_ident?></small></td>
                            <td><small><?php echo $r->nro_doc_ident?></small></td>
                            <td><small><?php echo date("d/m/Y", strtotime($r->dia));?></small></td>
                            <td><small><?php echo $r->hora?></small></td>
                            <td><small><?php echo $r->establecimiento?></small></td>
                            <td><small><?php echo $r->tipo_atencion?></small></td>
                            <td><small><?php echo $r->consultorio?></small></td>
                            <td><small><?php echo $r->nombre_medico." ".$r->paterno_medico." ".$r->materno_medico?></small></td>
                            <td class="text-center">
                            <?php 
                            if ($r->id_establecimiento == 76):
                                if ($r->fecha_toma_muestra != null):
                            ?>
                                    <a target="_blank" href="/print_examen_laboratorio_lns/{{$r->id}}/{{$r->nro_doc_ident}}/{{$r->fecha_toma_muestra}}" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-download-alt"></i></a>
                            <?php
                                else:
                            ?>
                                    <a target="_blank" href="/print_examen_laboratorio_lns/{{$r->id}}/{{$r->nro_doc_ident}}/" class="btn btn-info btn-xs" disabled><i class="glyphicon glyphicon-download-alt"></i></a>
                            <?php
                                endif;
                            else:
                                if ($r->ruta_laboratorio == "") { ?>
                                    <button class="btn btn-success" disabled=""></button>
                                <?php 
                                } else { ?>      
                                    <table>
                                        <tr>
                                            <td>
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b> Bioquimica</b>">
                                                <?php 
                                                    if($r->tipo_bioquimica=='SI'):
                                                        if($r->rpta_bioquimica=='SI'):
                                                ?>
                                                            <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/1" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-download-alt"></i></a>
                                                <?php 
                                                        else: ?>
                                                             <button class="btn btn-success btn-xs" disabled=""><i class="glyphicon glyphicon-download-alt"></i></button>
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
                                                                <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/2" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-download-alt"></i></a>
                                                    <?php 
                                                            else: ?>
                                                                <button class="btn btn-success btn-xs" disabled=""><i class="glyphicon glyphicon-download-alt"></i></button>
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
                                                                <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/3" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-download-alt"></i></a>
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
                                                        <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/4" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-download-alt"></i></a>
                                                    <?php 
                                                            else: ?>
                                                                 <button class="btn btn-success btn-xs" disabled=""><i class="glyphicon glyphicon-download-alt"></i></button>
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
                                                                <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/5" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-download-alt"></i></a>
                                                    <?php 
                                                            else: ?>
                                                                 <button class="btn btn-success btn-xs" disabled=""><i class="glyphicon glyphicon-download-alt"></i></button>
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
                                                        <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/6" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-download-alt"></i></a>
                                                    <?php 
                                                            else: ?>
                                                                 <button class="btn btn-success btn-xs" disabled=""><i class="glyphicon glyphicon-download-alt"></i></button>
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
                                                                <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/7" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-download-alt"></i></a>
                                                    <?php 
                                                            else: ?>
                                                                 <button class="btn btn-success btn-xs" disabled=""><i class="glyphicon glyphicon-download-alt"></i></button>
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
                                                                 <button class="btn btn-success btn-xs" disabled=""><i class="glyphicon glyphicon-download-alt"></i></button>
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
                                                                 <button class="btn btn-success btn-xs" disabled=""><i class="glyphicon glyphicon-download-alt"></i></button>
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
                                                                <a target="_blank" href="/print_examen_laboratorio/{{$r->id}}/10" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-download-alt"></i></a>
                                                    <?php 
                                                            else: ?>
                                                                 <button class="btn btn-success btn-xs" disabled=""><i class="glyphicon glyphicon-download-alt"></i></button>
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
                                                                 <button class="btn btn-success btn-xs" disabled=""><i class="glyphicon glyphicon-download-alt"></i></button>
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
                        <?php endforeach;?>

                        <?php
                        if (!empty($ordenes_laboratorio->RespuestaOrdenLab)):
                            if(count($ordenes_laboratorio->RespuestaOrdenLab)>1):
                                foreach($ordenes_laboratorio->RespuestaOrdenLab as $q):
                        ?>
                                    <tr>
                                        <td><small><span class="label label-info"><i class="glyphicon glyphicon-flash"></i></span><?php ?></small></td>
                                        <td><small><?php echo $q->nroDocumento?></small></td>
                                        <td><small><?php echo $q->fechaMuestra?></small></td>
                                        <td><small><?php ?></small></td>
                                        <td style="font-size:13px"><small><?php echo $q->establecimiento?></small></td>
                                        <td><small><?php ?></small></td>
                                        <td><small><?php echo $q->tipoAtencion?></small></td>
                                        <td><small><?php ?></small></td>
                                        <td class="text-center">
                                            <a target="_blank" href="/print_examen_laboratorio_lns/0/{{$q->nroDocumento}}/{{$q->fechaMuestra}}/{{$q->orden}}" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-download-alt"></i></a>
                                        </td>
                                    </tr>
                        <?php
                                endforeach;
                            else: 
                        ?>
                                <tr>
                                    <td><small><span class="label label-info"><i class="glyphicon glyphicon-flash"></i></span><?php ?></small></td>
                                    <td><small><?php echo $ordenes_laboratorio->RespuestaOrdenLab->nroDocumento?></small></td>
                                    <td><small><?php echo $ordenes_laboratorio->RespuestaOrdenLab->fechaMuestra?></small></td>
                                    <td><small><?php ?></small></td>
                                    <td style="font-size:13px"><small><?php echo $ordenes_laboratorio->RespuestaOrdenLab->establecimiento?></small></td>
                                    <td><small><?php ?></small></td>
                                    <td><small><?php echo $ordenes_laboratorio->RespuestaOrdenLab->tipoAtencion?></small></td>
                                    <td><small><?php ?></small></td>
                                    <td class="text-center">
                                        <a target="_blank" href="/print_examen_laboratorio_lns/0/{{$ordenes_laboratorio->RespuestaOrdenLab->nroDocumento}}/{{$ordenes_laboratorio->RespuestaOrdenLab->fechaMuestra}}/{{$ordenes_laboratorio->RespuestaOrdenLab->orden}}" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-download-alt"></i></a>
                                    </td>
                                </tr>
                        <?php
                            endif;
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


    
    