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
        font-size: 10px
    }
    .filterable .filters input[disabled]::-webkit-input-placeholder {
        color: #333; font-size: 10px
    }
    .filterable .filters input[disabled]::-moz-placeholder {
        color: #333; font-size: 10px
    }
    .filterable .filters input[disabled]:-ms-input-placeholder {
        color: #333; font-size: 10px
    }
    #global {
        height: 640px !important;
        width: auto;
        border: 1px solid #ddd;
        background: #f1f1f1;
        overflow-y: scroll !important;
    }
    .margin{
        margin-bottom: 20px;
    }
    .margin-buscar{
        margin-bottom: 5px;
        margin-top: 5px;
    }
</style>
<script>
        $(document).ready(function () {
        $('.filterable .btn-filter').click(function () {
            var $panel = $(this).parents('.filterable'),
                    $filters = $panel.find('.filters input'),
                    $tbody = $panel.find('.table tbody');
            if ($filters.prop('disabled') == true) {
                $filters.prop('disabled', false);
                $filters.first().focus();
            } else {
                $filters.val('').prop('disabled', true);
                $tbody.find('.no-result').remove();
                $tbody.find('tr').show();
            }
        });

        $('.filterable .filters input').keyup(function (e) {
            /* Ignore tab key */
            var code = e.keyCode || e.which;
            if (code == '9')
                return;
            /* Useful DOM data and selectors */
            var $input = $(this),
                    inputContent = $input.val().toLowerCase(),
                    $panel = $input.parents('.filterable'),
                    column = $panel.find('.filters th').index($input.parents('th')),
                    $table = $panel.find('.table'),
                    $rows = $table.find('tbody tr');
            /* Dirtiest filter function ever ;) */
            var $filteredRows = $rows.filter(function () {
                var value = $(this).find('td').eq(column).text().toLowerCase();
                return value.indexOf(inputContent) === -1;
            });
            /* Clean previous no-result if exist */
            $table.find('tbody .no-result').remove();
            /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
            $rows.show();
            $filteredRows.hide();
            /* Prepend no-result row if all rows are filtered */
            if ($filteredRows.length === $rows.length) {
                $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="' + $table.find('.filters th').length + '">No se encuentra el laboratorio</td></tr>'));
            }
        });
		
		
    });
    
</script>
@endsection
@section('content')
<div class="container">
    <h3>
        {{$farma->nombre}}  
    </h3>
    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">Reporte ICI Al {{$fecha_fin}})</h3>
                <div class="pull-right">
                    <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filtar</button>
                    <a class="btn btn-default btn-xs" href="/form_generar_rerporte_ici">
                        <i class="fa fa-mail-reply" aria-hidden="true"></i> Regresar
                    </a>
                </div>
            </div>
            <div id="global">
                <table class="table" style="font-size: 11px !important;">
                    <thead>
                        <tr class="filters">
                            <th>Nro</th>                            
                            <th>Código</th>    
                            <th><input type="text" class="form-control input-sm" placeholder="Productos Farmacéuticos y Disp. Médicos" disabled></th>
                            <th>CAN 2016</th>
                            @foreach($movimientos as $m)                                
                            <th> 
                                @if($m->id>=2 && $m->id<=3)
                                <span style="color:blue">
                                    <i class="fa fa-plus-circle"></i> {{$m->codgo}}
                                </span>
                                @else
                                @if($m->id>3)
                                @if($m->id==8 || $m->id==9)
                                <span style="color:blue">
                                    <i class="fa fa-plus-circle"></i> {{$m->codgo}}                                      
                                </span>
                                @endif
                                @if($m->id==4 || $m->id==7 )
                                <span style="color:red">
                                    <i class="fa fa-minus-circle"></i> {{$m->codgo}}                                      
                                </span>
                                @else
                                @role('Dispensador_consolidados')
                                <span style="color:red">
                                    <i class="fa fa-minus-circle"></i> {{$m->codgo}}                                     
                                </span>
                                @endauth                                        
                                @role('Almacenero_consolidados')
                                <span style="color:red">
                                    <i class="fa fa-minus-circle"></i> {{$m->codgo}}                                      
                                </span>
                                @endauth
                                @endif
                                @else
                                {{$m->codgo}}
                                @endif
                                @endif
                            </th>                               
                            @endforeach                                                    
                            <th>Saldo Final</th>
                            <th>CPM</th>
                            <th>CPMA</th>
                            <th>Meses Provisión</th>
                            <th>Situaci&oacute;n</th>
                            @role('Almacenero_recetas')
                            <!--<th>
                                <button type="submit">Requerir</button>
                            </th>-->
                            @endauth
                            @role('Almacenero_consolidados')
                            <!--<th>
                                <button type="submit">Solicitar</button>
                            </th>-->
                            @endauth
                        </tr>
                    </thead>
                    <tbody style="font-size: 11px !important;">
                        {{-- */$x=0;/* --}}
                        @foreach($resultado as $r)
                        {{-- */$x++;/* --}}


                        <?php
                        $cpm = "";
                        $cpma = "";
                        $meses_provicion = "";

                        if ($r['cantidad'] > 0 && ($r['egresos'] - $r['recetas_devueltas']) == 0) {
                            $cpma = -1; //-1
                        } else {
                            if ($r['nro_mes'] == 0) {
                                $cpma = -1;
                            } else {
                                if (($r['egresos'] - $r['recetas_devueltas']) == 0) {
                                    $cpma = 0;
                                } else {
                                    $cpma = ($r['egresos'] - $r['recetas_devueltas']) / $r['nro_mes'];
                                    //$cpma = round($g,0);
                                    $cpma = round($cpma, 1);
                                }
                            }
                        }

                        if (($r['egresos'] - $r['recetas_devueltas']) == 0) {
                            $meses_provicion = 0;
                        } else {
                            $provision = round(($r['cantidad'] / $cpma), 1);
                            $meses_provicion = $provision;
                        }

                        /*                         * *********************** */
                        $situac = "";
                        $situacionTemp = "";
                        if ($r['cantidad'] <= 0):
                            $situac = "Desabastecido";
                            $situacionTemp = 1;
                        else:
                            if ($cpma <= 0):
                                $situac = "Sin Rotacion";
                                $situacionTemp = 5;
                            endif;
                            if ($meses_provicion > 0 && $meses_provicion < 2):
                                $situac = "SubStock";
                                $situacionTemp = 4;
                            endif;
                            if ($meses_provicion >= 2 && $meses_provicion <= 6):
                                $situac = "NormoStock";
                                $situacionTemp = 3;
                            endif;
                            if ($meses_provicion > 6):
                                $situac = "SobreStock";
                                $situacionTemp = 2;
                            endif;

                        endif;
                        ?>


                        <tr>
                            <td>{{$x}}</td>
                            <td>
                                {{$r['codigo']}}
                            </td>
                            <td>{{$r['nombre']}} - {{$r['unidad']}}</td>  
                            <td>{{$r['can']}}</td>
                            @foreach($r['movimiento'] as $mo)    
                            @if($mo['id']>=2 && $mo['id']<=3)
                            <td>
                                <span style="color: blue">{{$mo['total']}}</span>
                            </td>
                            @else
                            @if($mo['id']==1)
                            <td>
                                <span style="color: black">{{$mo['total']}}</span>
                            </td>
                            @else
                            <td>
                                @if($mo['id']==8 || $mo['id']==9)
                                <span style="color: blue">{{$mo['total']}}</span>                                            
                                @endif
                                @if($mo['id']==4 || $mo['id']==7 )
                                <span style="color: red">{{$mo['total']}}</span>                                            
                                @else 
                                @role('Dispensador_consolidados')
                                <span style="color: red">{{$mo['total']}}</span>
                                @endauth
                                @role('Almacenero_consolidados')
                                <span style="color: red">{{$mo['total']}}</span>
                                @endauth
                                @endif
                            </td>
                            @endif
                            @endif
                            @endforeach                                
                            <td>{{$r['cantidad']}}</td>                                
                            <td>                                   
                                <?php
                                if (($r['egresos'] - $r['recetas_devueltas']) == 0) {
                                    echo "0";
                                } else {
                                    /* if($mes<1){
                                      $f = ($r['egresos']-$r['recetas_devueltas']) / 1;
                                      echo $f;
                                      }else{ */
                                    //$f = ($r['egresos']-$r['recetas_devueltas']) / $mes;
                                    $f = ($r['egresos'] - $r['recetas_devueltas']) / $r['nro_mes'];
                                    echo round($f, 1);
                                    //}
                                }
                                ?>
                            </td> 
                            <td>                                
                                <?php
                                /*
                                  if($r['cantidad']>0 && ($r['egresos']-$r['recetas_devueltas'])==0){
                                  echo "Sin historial de consumo";
                                  }else{
                                  if ($r['nro_mes'] == 0) {
                                  echo "0";
                                  }
                                  else
                                  {
                                  if(($r['egresos']-$r['recetas_devueltas'])==0){
                                  echo "0";
                                  }else{
                                  $g = ($r['egresos']-$r['recetas_devueltas']) / $r['nro_mes'];
                                  echo round($g,0);
                                  }
                                  }
                                  }
                                 */
                                //echo " # ";

                                if ($cpma == "-1")
                                    echo "Sin historial de consumo";
                                else
                                    echo $cpma;
                                ?>
                            </td>
                            <td>
                                <?php
                                /*
                                  if ($r['nro_mes'] == 0) {
                                  echo "0";
                                  } else {
                                  if($r['cantidad']==0){
                                  echo "0";
                                  }else{
                                  if(($r['egresos']-$r['recetas_devueltas'])==0){
                                  echo "0";
                                  }else {
                                  $provision = round(($r['cantidad'] / $g),0);
                                  echo $provision;
                                  }
                                  }
                                  }
                                 */
                                echo $meses_provicion;
                                ?>
                            </td>

                            <td><?php echo $situac ?></td>

                            @role('Almacenero_recetas')
                            <!--<td>                            
                                <input value="" type="number" style="width: 50px" id="cantidad_solictada" name="cantidad_solicitada[]">                            
                            </td>--> 
                            @endauth
                            @role('Almacenero_consolidados')
                            <td>                            
                                <input value="" type="number" style="width: 50px" id="cantidad_solictada" name="cantidad_solicitada[]">                            
                            </td>
                            @endauth
                        </tr>
                        @endforeach                                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
