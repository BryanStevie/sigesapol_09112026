<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>DIRECCION DE SANIDAD POLICIAL</title>
        <link rel="stylesheet" type="text/css" href="assets/css/pdf.css"  />
        <style>
            

            .td_left{
                text-align: left !important;
            }
            .td_ancho_n{
                width: 4%;
                text-align: center
            }
            .cantidad{
                width: 11%;
            }
            .td_ancho_espacios{
                width: 5%;
            }
            .td_ancho_codigo{
                width: 10%;
            } 
            .td_ancho_especialidad{
                width: 25%;
            }
            td{
                padding-left: 2px !important;
            }
            table{
                font-size: 10px !important; 
                font-family: Roboto,"Helvetica Neue",Helvetica,Arial,sans-serif;
            }
            .ancho_producto{
                width: 45%;
            }
            .titulos{
                font-weight: bold;
                padding:0px 10px;
            }
            h2{
                font-size: 15px !important
            }
            .titulo_principal{
                 width: 60%;
            }
            .logo{
                 width: 15%;
            }
            .grado{
                width: 14%;
            }
            .dni{
                width: 8%;
            }
            .rectangulo{
                padding:3px
            }
            footer{
                margin-bottom:0px;
                padding:0px;
                position: fixed;
                height:60px;
                line-height:60px;
                text-align:left;
                border:0px;
                margin-left:10px;
                font-size: 7px !important;
                font-family:Arial, Helvetica, sans-serif
            }
        </style>
    </head>
    <body style="font-size: 10px; font-family: arial;padding:10px">
        <table id="example" class="table table-list-search table-hover" style="font-size: 11px !important;" >
                    <thead>
                        <tr>
                            <th>N</th>                            
                            <th>Origen</th>
                            <th>Fecha</th>
                            <th>Num Documento</th>
                            <th>Tipo Movimiento</th>
                            <th>Tipo Producto</th> 
                            <th>Codigo</th>                            
                            <th>Producto</th>                            
                            <th>R.S.</th>
                            <th>Lote</th>
                            <th>F. Venc</th> 
                            <th>P.U.</th>
                            <th>Cantidad</th>                             
                            <th>Responsable</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 0; ?>
                        @foreach($ordenes_compra as $r)
                        <?php 
                            if($r->pc!=0):
                                $descripcion= $r->nombre_producto.' ('.$r->cantidad.' X '.$r->presentacion_comercial.' )';
                            else :
                                $descripcion= $r->nombre_producto;
                            endif;
                        ?>
                        <tr>
                            <td><?php
                                $n++; echo $n; ?>                            
                            </td>
                            <?php if($r->id_tipo_movimiento==9){
                                      $lugar=$r->nombre_farmacia;
                                  }
                                  else
                                  {   
                                    $lugar= $r->nombre_farmacia;
                                  }
                            ?>
                            <td>{{$lugar}}</td>
                            <td>
                                <?php
                                $originalDate = $r->fecha_movimiento;
                                $fecha = date("d-m-Y", strtotime($originalDate));
                                echo $fecha;
                                ?> 
                            </td>
                            
                            <td>{{$r->numero_movimiento}}</td>
                            <td>{{$r->tipo_movimiento}}</td>
                                                   
                            <td>{{$r->tipo_producto}}</td>
                            
                            
                            <td>{{$r->codigo_producto}}</td>
                            <td>{{$descripcion}}</td>
                            <td>{{$r->registro_sanitario}}</td>
                            <td>{{$r->lote}}</td>
                            <td>
                                <?php
                                $originalDate2 = $r->fecha_vencimiento;
                                $fecha = date("d-m-Y", strtotime($originalDate2));
                                echo $fecha;
                                ?> 
                            </td>
                            <td>{{$r->p_unitario}}</td>
                            <td>{{$r->cantidad*$r->presentacion_comercial}}</td>                            
                            <td>{{$r->paterno_usuario}} {{$r->materno_usuario}}, {{$r->nombre_usuario}}                           
                        </tr>
                        @endforeach                      
                    </tbody>
                </table>  
        <script type="text/javascript"> 
            window.addEventListener("load", window.print());
        </script>
    </body>
</html>