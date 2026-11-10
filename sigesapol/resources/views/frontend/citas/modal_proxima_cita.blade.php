<?php //dd($horario); ?>
<title>Sistema de Farmacia</title>
<div class="container" style="width:100%">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="row">
                <div class="panel panel-primary filterable" style="margin-bottom:0px">
                    <div class="panel-heading">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="panel-title">ALERTA CITA</h3>      
                    </div>
                    <div class="panel-body" >
                        <div class="table-responsive" >
                        <div style="font-size:15px!important">El paciente tiene Cita pr&oacute;xima</div>
                            <table class="table table-hover table-sm dataTable no-footer" style="margin-top:10px">
                                <thead>
                                    <tr>
                                        <th style="width:30%">Establecimiento</th>
                                        <th style="width:20%">Servicio</th>
                                        <th style="width:30%">Consultorio</th>
                                        <th style="width:10%">Fecha</th>
                                        <th style="width:10%">Hora</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($horario as $rowHorario):?>
                                        <tr>
                                            <td><?php echo $rowHorario->establecimiento?></td>
                                            <td><?php echo $rowHorario->servicio?></td>
                                            @if($rowHorario->id_servicio == 114)
                                            <!--td><--?php echo $rowHorario->consultorio." - (". $rowHorario->codigo_rad." - ".$rowHorario->nombre_examen. ") " ?></td-->
                                            <td>
                                                <?php 
                                                    if (empty($rowHorario->codigo_rad)) {
                                                        echo $rowHorario->consultorio . 
                                                            ' - (<span style="color:red; font-weight:bold;">ORDEN FÍSICA</span>)';
                                                    } else {
                                                        echo $rowHorario->consultorio . 
                                                            ' - (' . $rowHorario->codigo_rad . ' - ' . $rowHorario->nombre_examen . ')';
                                                    }
                                                ?>
                                            </td>
                                            @else
                                            <td><?php echo $rowHorario->consultorio ?></td>
                                            @endif
                                            <td><?php  echo $fechaE = date("d/m/Y", strtotime($rowHorario->dia)); ?></td>
                                            <td><?php echo $rowHorario->hora?></td>
                                        </tr>
                                    <?php endforeach;?>
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