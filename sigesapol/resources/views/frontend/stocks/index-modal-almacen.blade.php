<table id="table-stock-almacen" class="table margenes" style="font-size: 11px !important;" >
    <thead>
        <tr class="filters">
            <th>Nro</th>
            <th>Código</th>
            <th>Producto Farmacéutico o Dispositivo Médico</th>
            <th>Unidad</th>
            <th>Stock Actual</th>
            <th>Situación</th>
            <th>Ver Lotes</th>                            
        </tr>
    </thead>
    <tbody style="font-size: 11px !important;">        
        {{-- */$x=0;/* --}}
        @foreach($resultado as $r)
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
                }
            }
        }
        if (($r['egresos'] - $r['recetas_devueltas']) == 0) {
            $meses_provicion = 0;
        } else {
            $provision = round(($r['cantidad'] / $cpma), 1);
            $meses_provicion = $provision;
        }
        $color = "";
        $situacionTemp = "";
        if ($r['cantidad'] <= 0):
            $color = "#FF0000"; //rojo
            $situacionTemp = 1;
        else:
            if ($cpma <= 0):
                $color = "#4472C4"; //Sin Rotacion Azul
                $situacionTemp = 5;
            endif;
            if ($meses_provicion > 0 && $meses_provicion < 2):
                $color = "#FFC000"; //SubStock amarillo
                $situacionTemp = 4;
            endif;
            if ($meses_provicion >= 2 && $meses_provicion <= 6):
                $color = "#70AD47"; //NormoStock verde
                $situacionTemp = 3;
            endif;
            if ($meses_provicion > 6):
                $color = "#ED7D31"; //SobreStock anaranjado
                $situacionTemp = 2;
            endif;
        endif;
        ?>
        <?php if ($situacion == 0): ?>
            {{-- */$x++;/* --}}
            <tr>
                <td>{{$x}}</td>
                <td>{{$r['codigo']}}</td>
                <td>{{$r['nombre']}} </td>
                <td>{{$r['unidad']}}</td>
                <td>{{$r['cantidad']}}</td>
                <td align="left" style="padding-right:0px;padding-left:0px;margin-left:0px;margin-right:0px">
                    <i class="fa fa-flag fa-2x" style="color:<?php echo $color ?>;position:relative">
                    </i>
                    <div style="color:<?php echo $color ?>;float:right;margin-right:0%;font-size:11px">{{ $meses_provicion }} meses</div>
                </td>
                <td>
                    <a href="/asignar_lote_vencimientos/{{$r['id_producto']}}/{{$id_farmacia}}" class="btn btn-primary btn-xs">
                        <i class="fa fa-edit"></i>
                    </a>
                </td>
            </tr>
        <?php elseif ($situacion > 0 && $situacion == $situacionTemp): ?>
            {{-- */$x++;/* --}}
            <tr>
                <td>{{$x}}</td>
                <td>{{$r['codigo']}}</td>
                <td>{{$r['nombre']}} </td>
                <td>{{$r['unidad']}}</td>
                <td>{{$r['cantidad']}}</td>
                <td align="left" style="padding-right:0px;padding-left:0px;margin-left:0px;margin-right:0px">
                    <i class="fa fa-flag fa-2x" style="color:<?php echo $color ?>;position:relative">
                    </i>
                    <div style="color:<?php echo $color ?>;float:right;margin-right:0%;font-size:11px">{{ $meses_provicion }} meses</div>
                </td>
                <td>
                    <a href="/asignar_lote_vencimientos/{{$r['id_producto']}}/{{$id_farmacia}}" class="btn btn-primary btn-xs">
                            <i class="fa fa-edit"></i>
                        </a>
                </td>
            </tr>
<?php endif; ?>
@endforeach
</tbody>
</table>
