<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Productos por Servicios</title>

<style type="text/css">
      table tr:nth-child(2n-1) td {
        
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
    </head>
    <body>
    <div class="row">
        <div class="col-xs-12">
          <h3 alig="center">
           <i class="fa fa-globe"></i> PRODUCTOS POR SERVICIO DE <?php echo $consultorio->nombre?>
          </h3>
        </div>
      </div>
      <div class="row invoice-info">
       <div class="col-sm-4 invoice-col">
         <address>
             DE <?php echo $fecha_inicio; ?> A <?php echo $fecha_fin; ?>
         </address>
       </div>
    </div>

<table class="table table-condensed">
    <thead>
    <tr>
            <th>#</th>
            <th>PRODUCTOS FARMACÉUTICOS Y DISPOSITIVOS MÉDICOS</th>
            <th>UND.</th>
            <th>CANT PRESC.</th>
            <th>CANT DISP.</th>
        </tr>
    </thead>
    <tbody>
        <?php $i=1;?>
        @foreach($medicamentos as $med)
        <tr>
            <td height="20" class="desc"><?php echo $i++;?></td>
            <td class="desc">{{$med->codigo}} - {{$med->nombre}}</td>
            <td class="desc">{{$med->unidad}}</td>
            <td class="total">{{$med->cantidad_prescrita}}</td>
            <td class="total">{{$med->cantidad_dispensada}}</td>
        </tr>
        @endforeach
    </tbody>
</table>    

<br/><br/>
        <script type="text/php">
            if (isset($pdf)) {
                $x = 510;
                $y = 800;
                $text = "Pagina {PAGE_NUM} de {PAGE_COUNT}";
                $font = null;
                $size = 10;
                $color = array(255,0,0);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle = 0.0;   //  default
                $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
            }
        </script>        
</body>
</html>