<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Libro de reclamaciones</title>
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
                width: 15%;
            }
            .td_ancho_codigo{
                width: 8%;
                text-align: left !important;
            } 
            
            .td_ancho_pu{
                width: 9%;
                text-align: left !important;
            } 
            .td_ancho_dispensar{
                width: 7%;
                text-align: left !important;
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
                width: 35%;
            }
            .titulos{
                font-weight: bold;
            }
            h2{
                font-size: 15px !important
            }
            .titulo_principal{
                 width: 60%;
            }
            .logo{
                 width: 20%;
            }
            .grado{
                width: 14%;
            }
            .dni{
                width: 8%;
            }
            .subtotal{
                text-align: center;
                width: 8%;
            }
        </style>
    </head>
    <body style="font-size: 10px; font-family: arial">
        <table>
            <tbody>
                <tr>
                    <td colspan="2" class="td_left logo">
                        <img src="images/logo_cabecera.png" width="auto" height="30px"/>
                    </td>
                    <td colspan="2" class="titulo_principal">
                        <h2>
                            {{$establecimiento->nombre}}<br>
                            RECETA VALE - CIRUGIA
                        </h2>
                    </td>
                    <td colspan="2" class="titulos logo">   
                        <div style="border: 1px solid black;">
                            {{$receta->nro_receta}}
                        </div>
                        <div>
                            <?php
                            $originalDate3 = $receta->fecha_registro;
                            $fecha_registro = date("d-m-Y", strtotime($originalDate3));
                            echo $fecha_registro;
                            ?>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        @if($receta->tipo_beneficiario!="TITULAR")
        <table border="1px" style="background-color:white !important">
            <tr>
                <td class="grado titulos">GRADO</td>
                <td class="dni titulos">DNI</td>
                <td class="titulos">NOMBRE Y APELLIDOS DEL TITULAR</td>
                              
            </tr>
            <tr>
                <td class="td_left">{{$receta->grado}}</td>
                <td class="td_left dni">{{$beneficiario[0]->nrodoctitular}}</td>
                <td class="td_left">{{$beneficiario[0]->nomtitular}} {{$beneficiario[0]->apepattitular}} {{$beneficiario[0]->apemattitular}}</td>                
            </tr>
        </table>
        <table border="1px" style="background-color:white !important">
            <tr>
                <td class="grado titulos">PARENTESCO</td>
                <td class="dni titulos">DNI</td>
                <td class="titulos">NOMBRE Y APELLIDOS DEL DERECHOHABIENTE</td>
                <td class="grado titulos">NRO. HISTORIA</td>     
            </tr>
            <tr>
                <td class="td_left">{{$beneficiario[0]->parentesco}}</td>
                <td class="td_left dni">{{$beneficiario[0]->nrodocafiliado}}</td>
                <td class="td_left">{{$beneficiario[0]->nomafiliado}} {{$beneficiario[0]->apepatafiliado}} {{$beneficiario[0]->apematafiliado}}</td>   
                <td class="td_left">{{$receta->nro_historia}}</td>   
            </tr>
        </table>
        @else
        <table border="1px" style="background-color:white !important">
            <tr>
                <td class="grado titulos">GRADO</td>
                <td class="dni titulos">DNI</td>
                <td class="titulos">NOMBRE Y APELLIDOS DEL TITULAR</td>
                <td class="grado titulos">NRO. HISTORIA</td>                
            </tr>
            <tr>
                <td class="td_left">{{$receta->grado}}</td>
                <td class="td_left dni">{{$beneficiario[0]->nrodocafiliado}}</td>
                <td class="td_left">{{$beneficiario[0]->nomafiliado}} {{$beneficiario[0]->apepatafiliado}} {{$beneficiario[0]->apematafiliado}}</td>
                <td class="td_left">{{$receta->nro_historia}}</td>                
            </tr>
        </table>
        @endif
        <table border="1px" style="background-color:white !important">
            <tbody>
                <tr>
                    <td colspan="3" class=" titulos">DIAGNOSTICOS</td>
                </tr>
                <tr>
                    <td class="td_ancho_n titulos">N</td>
                    <td class="td_ancho_codigo titulos">CODIGO</td>
                    <td class=" titulos">DESCRIPCION</td>
                </tr>
                <tr>
                    <td class="">1</td>
                    <td class="td_left">{{$receta->codigo_diagnostico_principal}}</td>
                    <td class="td_left">{{$receta->diagnostico_principal}}</td>
                </tr>
                <?php $n = 1; ?>
                @foreach($diagnosticos as $d)
                <tr>
                    <td class=""><?php $n = $n + 1;   echo $n; ?></td>
                    <td class="td_left">{{$d->codigo}}</td>
                    <td class="td_left">{{$d->nombre}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <table border="1px" style="background-color:white !important">
            <tbody>
                <tr>
                    <td colspan="3" class="titulos">PROCEDIMIENTOS</td>
                </tr>
                <tr>
                    <td class="td_ancho_n titulos">N</td>
                    <td class="td_ancho_codigo titulos">CODIGO</td>
                    <td class="titulos">DESCRIPCION</td>
                </tr>
                <?php $n = 0; ?>
                @foreach($procedimientos as $p)
                <tr>
                    <td class="td_ancho_n"><?php $n = $n + 1;   echo $n; ?></td>
                    <td class="td_left">{{$p->codigo}}</td>
                    <td class="td_left">{{$p->descripcion}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <table border="1px" style="background-color:white !important">
            <tbody>
                <tr>
                    <td colspan="4" class="titulos">PROFESIONALES DE LA SALUD</td>
                </tr>
                <tr>
                    <td class="td_ancho_n titulos">N</td>
                    <td class="td_ancho_codigo titulos">DNI</td>
                    <td class="titulos">NOMBRE Y APELLIDOS</td>
                    <td class="td_ancho_codigo titulos">ESPECIALIDAD</td>
                </tr>
                <tr>
                    <td >1</td>
                    <td class="td_ancho_codigo td_left">{{$receta->dni_cirujano_principal}}</td>
                    <td class="td_left">{{$receta->nombre_cirujano_principal}} {{$receta->paterno_cirujano_principal}} {{$receta->materno_cirujano_principal}}</td>
                    <td class="td_left td_ancho_especialidad">{{$receta->especializacion}}</td>
                </tr>
                <?php $n = 1; ?>
                @foreach($cirujanos as $c)
                <tr>
                    <td ><?php $n = $n + 1;   echo $n; ?></td>
                    <td class="td_ancho_codigo td_left">{{$c->dni}}</td>
                    <td class="td_left">{{$c->nombre}} {{$c->paterno}} {{$c->materno}}</td>
                    <td class="td_left td_ancho_especialidad">{{$c->especializacion}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <table border="1px" style="background-color:white !important">
            <tbody >
                <tr style="border: 1px solid black">
                    <td rowspan="2" class="td_ancho_n titulos">N</td>
                    <td rowspan="2" class="td_ancho_codigo titulos">CODIGO</td>
                    <td rowspan="2" class="ancho_producto titulos">PRODUCTO</td>
                    <td rowspan="2" class="titulos">UNIDAD</td>
                    <td colspan="2" class="titulos">CANTIDAD</td>  
                    <td rowspan="2" class="titulos">P. UNI.</td>  
                    <td rowspan="2" class="titulos subtotal">SUB TOTAL</td>
                </tr>
                <tr>
                    <td class="td_ancho_dispensar titulos">PRESC.</td>
                    <td class="td_ancho_dispensar titulos">DISPE.</td>
                </tr>
                <?php $n = 0;   $suma = 0; ?>
                @foreach($productos as $p)
                <tr>
                    <td><?php $n = $n + 1;  echo $n ?></td>
                    <td class="td_left">{{$p->codigo}}</td>
                    <td class="td_left">{{$p->nombre}}</td>
                    <td>{{$p->abreviatura}}</td>
                    <!--<td>{{$p->abreviatura}}</td>-->
                    <td>{{$p->cantidad_prescrita}}</td>
                    <td>{{$p->cantidad_dispensada}}</td>
                    <!--<td>{{$p->cantidad_dispensada}}</td>
                    <td>{{($p->cantidad_dispensada)*($p->cantidad_dispensada)}}</td>-->
                    <td class="td_ancho_pu" style="text-align: center !important">
                        @foreach($pu_productos as $pu)
                            @if($pu->codigo_producto==$p->codigo)
                                {{$pu->pu}}
                            @endif
                        @endforeach
                    </td>
                    <td>
                        <?php
                            foreach($pu_productos as $pu){
                                if($pu->codigo_producto==$p->codigo){
                                    $suma=(($pu->pu)*$p->cantidad_dispensada)+$suma;
                                    echo ($pu->pu)*$p->cantidad_dispensada;                                    
                                }
                            }
                        ?>                        
                    </td>                    
                </tr>
                @endforeach
                <tr>
                    <td colspan="7" style="text-align: right !important;"><b>TOTAL</b></td>
                    <td><b><?php echo $suma ?></b></td>
                </tr>
            </tbody>
        </table>
        <table style="margin-top: 80px">
            <tr>
                <td style="border-top: 1px solid black">
                    {{$enfermera_principal->nombre}} {{$enfermera_principal->paterno}} {{$enfermera_principal->materno}}<br>
                        {{$enfermera_principal->especializacion}}
                </td>
                <td class="td_ancho_espacios"></td>
                <td style="border-top: 1px solid black">
                    {{$receta->nombre_cirujano_principal}} {{$receta->paterno_cirujano_principal}} {{$receta->materno_cirujano_principal}}<br>
                    {{$receta->especializacion}}
                </td>
                <td class="td_ancho_espacios"></td>
                @foreach($cirujanos as $c)
                    @if($c->especializacion=="ANESTESIOLOGIA")
                    <td style="border-top: 1px solid black">
                        {{$c->nombre}} {{$c->paterno}} {{$c->materno}}<br>
                        {{$c->especializacion}}
                    </td>
                    @endif
                @endforeach
            </tr>
        </table>

        <footer>
            SALUDPOL.
        </footer>
    </body>
</html>