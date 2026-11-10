<style type="text/css">
    table tr:nth-child(2n-1) td {
        background: #eee;
      }

      table th,
      table td {
        text-align: center;

      }

      table th {
        padding: 0px 0px;
        color: #5D6975;
        border-bottom: 0.5px solid #C1CED9;
        white-space: nowrap;        
        
      }

      table .service,
      table .desc {
        text-align: left;
      }

      table td {
        padding: 0px;
        text-align: center;
      }

      table td.service,
      table td.desc {
        border-bottom:0.5px solid #5D6975;
        font-size: 0.7em;
      }

      table td.unit,
      table td.qty,
      table td.total {
        font-size: 0.7em;
        border-bottom:0.5px solid #5D6975;

      }

      table td.grand {
        border-top: 1px solid #5D6975;
      }

</style>
<div class="row">    
    @if(count($medicamentos)>0)
        <div class="row">
            <div class="col-md-6"><strong>
                            SERVICIO: {{$consultorio->nombre}}
                        </strong></div>
            <div class="col-md-6">FECHA DE <strong>{{$fecha_inicio}}</strong> HASTA <strong>{{$fecha_fin}}
                        </strong></div>
        
        </div>

        <table class="table table-condensed">
            <tbody>
                <tr>
                    <th style="border:1px solid #999999">#</th>
                    <th style="border:1px solid #999999">
                        <label>PRODUCTOS FARMACÉUTICOS Y DISPOSITIVOS MÉDICOS</label>
                    </th>
                    <th style="border:1px solid #999999">
                        <label>UND.</label>
                    </th>
                    <th style="border:1px solid #999999">
                        <label>CANT PRESCRITA</label>
                    </th>
                    <th style="border:1px solid #999999">
                        <label>CANT DISPENSADA</label>
                    </th>
                </tr>
                
                    <form  id="frmDispensar" class="form-horizontal" method="post" action="{{ route('dispensar_producto')}}" id="frmReceta" style="font-size:12px;">
            <?php $i=1;?>
            @foreach($medicamentos as $med)
                <tr>
                <td height="20" class="desc"><?php echo $i++;?></td>
                <td class="desc">
                    <spam id="lblNombre">{{$med->codigo}} - {{$med->nombre}}</spam>
                </td>
                    <td class="total">
                        <spam id="lblNombre">{{$med->unidad}}</spam>
                    </td>
                    <td class="total">
                        <spam id="lblNombre">{{$med->cantidad_prescrita}}</spam>
                    </td>
                    <td class="total">
                        <spam id="lblNombre">{{$med->cantidad_dispensada}}</spam>
                    </td>
                </tr>                                 
            @endforeach            
        </form> 

                </tr>
            </tbody>
        </table>            
            
        
        
    @endif
    
</div>