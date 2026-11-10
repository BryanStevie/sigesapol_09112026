@extends('frontend.layouts.master')
@section('renderjs')
<script>
    $consul = $("#frmReceta").find("#id_consultorio");
    $consul.select2();
    /*$consul = $("#frmConsultorio").find("#id_consultorios");
     $consul.select2();*/

    //--------------------------------------------------------------------------
    $(".chosen-select").chosen();

    $(document).ready(function () {
        $("#frmDispensar").submit(function (e) {
            rows = $("#frmDispensar").children(".row");
            $.each(rows, function (ii, oo) {
                medicina = $(oo).find("#lblNombre").text();
                nuevaCantidad = parseFloat($(oo).find("#nueva_cantidad").val());
                cantidadPrescrita = parseFloat($(oo).find("#txtCantidadPrescrita").val());
                stockActual = parseFloat($(oo).find("#lblStockActual").text());
                if (stockActual == 0) {
                    if (nuevaCantidad != 0) {
                        alert("La medicina: " + medicina + ", tiene que ser cantidad cero");
                        e.preventDefault();
                        return false;
                    }
                } else {
                    if (nuevaCantidad > stockActual) {
                        alert("La medicina: " + medicina + ", la cantidad no tiene que ser mayor que el stock");
                        e.preventDefault();
                        return false;
                    } else if (nuevaCantidad > cantidadPrescrita) {
                        alert("La medicina: " + medicina + ", la cantidad no tiene que ser mayor que la prescrita");
                        e.preventDefault();
                        return false;
                    }
                }
            });
        });
    });
    //--------------------------------------------------------------------------    
</script>
<style>
    .search-form .form-group {
        float: left !important;
        transition: all 0.35s, border-radius 0s;
        width: 32px;
        height: 32px;
        background-color: #fff;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
        border-radius: 25px;
        border: 1px solid #ccc;
        margin-left: 0px;
    }
    .search-form .form-group input.form-control {
        padding-right: 20px;
        border: 0 none;
        background: transparent;
        box-shadow: none;
        display:block;
    }
    .search-form .form-group input.form-control::-webkit-input-placeholder {
        display: none;
    }
    .search-form .form-group input.form-control:-moz-placeholder {
        /* Firefox 18- */
        display: none;
    }
    .search-form .form-group input.form-control::-moz-placeholder {
        /* Firefox 19+ */
        display: none;
    }
    .search-form .form-group input.form-control:-ms-input-placeholder {
        display: none;
    }
    .search-form .form-group:hover,
    .search-form .form-group.hover {
        width: 60px;
        border-radius: 4px 25px 25px 4px;
    }
    .search-form .form-group span.form-control-feedback {
        position: absolute;
        top: -1px;
        right: -2px;
        z-index: 2;
        display: block;
        width: 34px;
        height: 34px;
        line-height: 34px;
        text-align: center;
        color: #3596e0;
        left: initial;
        font-size: 14px;
    }

    .table {
        padding: 8px;
        line-height: 1.428571429;
        vertical-align: top;
        border-top: 1px solid #ddd;
    }

    .center {
        margin-top:50px;   
    }

    .modal-header {
        padding-bottom: 5px;
    }

    .modal-footer {
        padding: 0;
    }

    .modal-footer .btn-group button {
        height:40px;
        border-top-left-radius : 0;
        border-top-right-radius : 0;
        border: none;
        border-right: 1px solid #ddd;
    }

    .modal-footer .btn-group:last-child > button {
        border-right: 0;
    }
    td{
        padding-left: 10px;
        padding-right: 10px
    }
</style>
@endsection
@section('content')
<div class="container" style="width:95%">
    <div class="row">
        <!--
<div class="col-sm-3 col-md-3">
    <div class="panel-group" id="accordion">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-th">
                        </span>Atención de Recetas</a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body">
                    <table class="table">
                        <tr>
                            <td>
                                <span class="glyphicon glyphicon-plus text-primary"></span>
                                <a href="/nueva_receta/{{ Auth::user()->id }}/{{ Auth::user()->id_farmacia }}">Nueva Receta</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="glyphicon glyphicon-search text-primary"></span>
                                <a href="/buscar_receta/{{ Auth::user()->id }}"> Buscar Receta</a>
                            </td>
                        </tr> 
                        <tr>
                            <td>
                                <span class="glyphicon glyphicon-list text-primary"></span>
                                <a href="/recetas_atendidas/{{ Auth::user()->id }}"> Receta atendidas</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="glyphicon glyphicon-list text-primary"></span>
                                <a href="/recetas_invalidadas/{{ Auth::user()->id_farmacia }}/{{ Auth::user()->id }}"> Recetas Invalidadas</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="glyphicon glyphicon-tasks text-primary"></span>
                                <a href="/resumen_diario/{{ Auth::user()->id }}"> Resumen Diario</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="glyphicon glyphicon-arrow-left text-primary"></span>
                                <a href="/stocksbyFamarcia/{{ Auth::user()->id }}"> Ir al Stock</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="glyphicon glyphicon-search text-primary"></span>
                                <a href="/all_recetas_atendidas_by_farmacia/{{ Auth::user()->id }}">Recetas atendidas</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
        -->
        <div class="col-sm-12 col-md-12 col-md-offset-0">
            <div class="panel panel-primary">                
                <div class="panel-heading">
                    <h2 class="panel-title">RECETA - {{$farmacia->nombre}}</h2>                    
                </div>     
                @if(count($recetas)>0) 
                <div class="panel-body" style="padding-top:30px; font-size: 13px">
                    @if(count($medicamentos)>0)
                    <div class="col-md-3" style="position:relative">
                        <img src="/../../images/logo_sanidad.png" width="70" style="position:absolute;top:-20px;left:10px">
                    </div>
                    <div class="col-md-7" style="text-align: center">
                        <h4>{{$recetas[0]->ipress}}</h4>                            
                    </div>
                    <div class="col-md-2">
                        <label>Nro RECETA</label>                        
                        <p>{{$recetas[0]->nro_receta}}</p>
                    </div>

                    <div class="clearfix">&nbsp;</div>

                    <div class="col-md-2">
                        <label>{{$recetas[0]->tipodoc_beneficiario}}</label>
                        <p>{{$recetas[0]->dni_beneficiario}}</p>

                    </div>
                    <div class="col-md-4">
                        <label>NOMBRE Y APELLIDOS</label>                        
                        <p><?php echo $recetas[0]->nombre_beneficiario . " " . $recetas[0]->paterno_beneficiario . " " . $recetas[0]->materno_beneficiario ?></p>
                    </div>
                    <div class="col-md-3">
                        <label>HISTORIA CL&Iacute;NICA</label>
                        <p class="text-center">                            
{{$recetas[0]->nro_historia}}
                        </p>
                    </div>
                    <div class="col-md-2">
                        <label>BENEFICIARIO</label>                        
                        <p>{{$recetas[0]->tipo_beneficiario}}</p>
                    </div>                    
                    @if($recetas[0]->tipo_receta=="CIRUGIA")
                    <div class="col-md-3">
                        <label>SERVICIO</label>                        
                        <p>
                            {{$recetas[0]->consultorio}}
                            @if($recetas[0]->estado==1)
                            <!--<a data-toggle="modal" data-target="#squarespaceModal3">
                                <i class="fa fa-edit"></i> 
                            </a>-->
                            @endif
                        </p>
                    </div>
                    <div class="col-md-3">
                        <label>TIPO DE ATENCI&Oacute;N</label>                        
                        <p>
                            {{$recetas[0]->tipo_receta}}                            
                        </p>
                    </div>
                    <div class="col-md-3">
                        <label>FECHA DE INTERVENCI&Oacute;N</label>                        
                        <p>
                            {{$recetas[0]->fecha_intervencion}}                            
                        </p>
                    </div>
                    <div class="col-md-3">
                        <label>NRO. HISTORIA</label>                        
                        <p>
                            {{$recetas[0]->nro_historia}}                            
                        </p>
                    </div>                    
                    <div class="col-md-6" style="margin-bottom: 10px">
                        <table border="1px" style="background-color:white !important">
                            <tbody>
                                <tr>
                                    <td colspan="3" class="td_left">DIAGNOSTICOS</td>
                                </tr>
                                <tr>
                                    <td class="td_left td_ancho_n">N</td>
                                    <td class="td_left td_ancho_codigo">CODIGO</td>
                                    <td class="td_left">DESCRIPCI&Oacute;N</td>
                                </tr>
                                <tr>
                                    <td class="td_left td_ancho_n">1</td>
                                    <td class="td_left">{{$recetas[0]->cie}}</td>
                                    <td class="td_left">{{$recetas[0]->diagnostico}}</td>
                                </tr>
                                <?php $n = 1; ?>
                                @foreach($diagnosticos as $d)
                                <tr>
                                    <td class="td_left td_ancho_n"><?php $n = $n + 1;
                                echo $n; ?></td>
                                    <td class="td_left">{{$d->codigo}}</td>
                                    <td class="td_left">{{$d->nombre}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>                     
                    </div>
                    <div class="col-md-12" style="margin-bottom: 10px">
                        <!----------------------------------------------------->
                        <table border="1px" style="background-color:white !important" >
                            <tbody>
                                <tr>
                                    <td colspan="4" class="td_left">PROFESIONALES DE LA SALUD</td>
                                </tr>
                                <tr>
                                    <td class="td_left td_ancho_n">N</td>
                                    <td class="td_left td_ancho_codigo">DNI</td>
                                    <td class="td_left">NOMBRE Y APELLIDOS</td>
                                    <td class="td_left td_ancho_codigo">ESPECIALIDAD</td>
                                </tr>
                                <tr>
                                    <td class="td_left td_ancho_n">1</td>
                                    <td class="td_left td_ancho_codigo">{{$recetas[0]->dni_medico}}</td>
                                    <td class="td_left">{{$recetas[0]->nombre_medico}} {{$recetas[0]->paterno_medico}} {{$recetas[0]->materno_medico}}</td>
                                    <td class="td_left td_ancho_especialidad">{{$recetas[0]->especializacion}}</td>
                                </tr>
<?php $n = 1; ?>
                                @foreach($cirujanos as $cir)
                                <tr>
                                    <td class="td_left td_ancho_n"><?php $n = $n + 1;
echo $n; ?></td>
                                    <td class="td_left td_ancho_codigo">{{$cir->dni}}</td>
                                    <td class="td_left">{{$cir->nombre}} {{$cir->paterno}} {{$cir->materno}} </td>
                                    <td class="td_left td_ancho_especialidad">{{$cir->nombre_especialidad}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!----------------------------------------------------->
                    </div>
                    @else
                    @if(Auth::user()->id_farmacia == 19 || Auth::user()->id_farmacia == 128)
                        <div class="col-md-2">
                            <label>SEXO </label>
                            <p><?php echo $recetas[0]->sexo_beneficiario;?></p>
                        </div>
                        <?php if($recetas[0]->id_prestacion!=0): ?>
                        <div class="col-md-2">
                            <label>TALLA </label>
                            <p><?php echo $recetas[0]->talla;?></p>
                        </div>
                        <div class="col-md-2">
                            <label>PESO </label>
                            <p><?php echo $recetas[0]->peso;?></p>
                        </div>
                        <div class="col-md-3">
                          <label>CAMA </label>
                            <p>  <?php
                                echo $recetas[0]->cama;
                              ?>
                          </p>
                        </div>
                    <?php endif; ?>
                        
                    @endif
                    <div class="col-md-3">
                        <label>SERVICIO</label>                        
                        <p>
                            {{$recetas[0]->consultorio}}
                            @if($recetas[0]->estado==1)
                            <!--<a data-toggle="modal" data-target="#squarespaceModal3">
                                <i class="fa fa-edit"></i> 
                            </a>-->
                            @endif
                        </p>
                    </div>
                    <div class="col-md-2">
                        <label>TIPO DE ATENCI&Oacute;N</label>                        
                        <p>
                            {{$recetas[0]->tipo_receta}}                            
                        </p>
                    </div>
                    <div class="col-md-4">
                        <label>PROFESIONAL PRESCRIPTOR</label>
                        <p>{{$recetas[0]->nombre_medico}} {{$recetas[0]->paterno_medico}} {{$recetas[0]->materno_medico}}
                            @if($recetas[0]->estado==1)
                            <!--<a data-toggle="modal" data-target="#squarespaceModal4">
                                <i class="fa fa-edit"></i> 
                            </a>-->
                            @endif
                        </p>
                    </div>
                    <div class="col-md-3">
                        <label>FECHA EXPEDICI&Oacute;N</label>                        
                        <p>                            
                            <?php
                            $originalDate2 = $recetas[0]->fecha_registro;
                            $fecha = date("d-m-Y", strtotime($originalDate2));
                            echo $fecha;
                            ?>
                            @if($recetas[0]->estado==1)
                            <?php
                            if ($fecha >= '19-12-2016') {
                                ?>
                                <!--<a data-toggle="modal" data-target="#squarespaceModal2">
                                    <i class="fa fa-edit"></i> 
                                </a>-->
                                <?php
                            }
                            ?>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-2">
                        <label>FECHA EXPIRACI&Oacute;N</label>                        
                        <p>                            
                            <?php
                            $originalDate4 = $recetas[0]->fecha_expiracion;
                            $fecha4 = date("d-m-Y", strtotime($originalDate4));
                            echo $fecha4;
                            ?>
                            @if($recetas[0]->estado==1)
                            <?php
                            if ($fecha >= '19-12-2016') {
                                ?>
                                <a data-toggle="modal" data-target="#squarespaceModal2">
                                    <i class="fa fa-edit"></i> 
                                </a>
                                <?php
                            }
                            ?>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label>DIAGNOSTICOS</label>                        
                        <p> 
						<?php 
							$cad = "";
							$diagnosticos_mostrar = (count($diagnosticos_egreso) > 0) ? $diagnosticos_egreso : $diagnosticos;
                            foreach($diagnosticos_mostrar as $row):
                                $cad .= $row->codigo." - ".$row->nombre." - ".$row->tipo."<br>";
                            endforeach;
                            echo $cad;
                        ?>
						</p>
                    </div>  
                    <div class="col-md-3">
                        <label>REGISTRADOR</label>                        
                        <p>{{$recetas[0]->nombre_usuario}} {{$recetas[0]->paterno_usuario}} {{$recetas[0]->materno_usuario}}</p> 
                    </div> 
                    <div class="col-md-2">
                        <label>FECHA DE DISPENSACI&Oacute;N</label>
                        <?php
                        $fecha_expedicion = $recetas[0]->fecha_expedicion;
                        $fecha_expedicion = date("d-m-Y", strtotime($fecha_expedicion));
                        ?>
                        <p><?php echo $fecha_expedicion ?></p> 
                    </div>
                    @endif
                    @if (isset($recetas[0]->codigo_siteds))
                    <div class="col-md-4">
                        <label>CÓDIGO SITEDS</label>                        
                        <p>{{$recetas[0]->codigo_siteds}}</p>
                    </div>
                    @endif
                    @if($recetas[0]->estado==1) 
                    <div class="col-md-12" style="margin-bottom:10px">
                        <a data-toggle="modal" data-target="#squarespaceModal" class="btn btn-danger">
                            <i class="fa fa-xing"></i>Invalidar Receta
                        </a>                          
                    </div>
                    @else
                    <div class="col-md-12">
                        <button class="btn btn-danger" disabled="">
                            Registro de Receta invalidada
                        </button>
                    </div>
                    <div class="col-md-8" style="border: 1px solid red; margin-top: 10px; background-color: #FFDA6A">
                        Motivo: {{$receta_devuelta[0]->motivo}}<br>
                        <?php
                        $originalDate8 = $receta_devuelta[0]->fecha_devolucion;
                        $fecha_devolucion = date("d-m-Y", strtotime($originalDate8));
                        ?>
                        Fecha de Invalidaci&oacute;n: <?php echo $fecha_devolucion ?><br>
                        Usuario Invalidador: {{$receta_devuelta[0]->name}} {{$receta_devuelta[0]->paterno}} {{$receta_devuelta[0]->materno}}
                    </div>
                    @endif


                    <div class="col-md-12" style="text-align: left; font-size: 14px; margin-top: 15px">
                        <legend>PRODUCTOS FARMAC&Eacute;UTICOS Y DISPOSITIVOS M&Eacute;DICOS DISPENSADOS</legend>
                    </div>
					<div class="col-md-1" style="font-size: 9px;">
                        <label>Nro</label>
                    </div>
                    <div class="col-md-2" style="font-size: 9px;">
                        <label>PRODUCTOS FARMAC&Eacute;UTICOS Y DISPOSITIVOS M&Eacute;DICOS</label>
                    </div>
                    <?php if (isset($flag_receta_dias) && $flag_receta_dias == 1): ?>
                        <div class="col-md-1" style="font-size: 10px">
                            <label>TIEMPO<br/>(DIAS)</label>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-1" style="font-size: 10px">
                        <label>CANT.<br/>PRESCRITA</label>
                    </div>
                    <div class="col-md-1" style="font-size: 10px">
                        <label>CANT.<br/>DISPENSADA</label>
                    </div>
                    <div class="col-md-1" style="font-size: 10px">
                        <label>STOCK Establecimiento</label>
                    </div>
					<div class="col-md-1" style="font-size: 10px">
                        <label>STOCK Farmacia</label>
                    </div>
                    <?php if($recetas[0]->id_prestacion!=0): ?>
					<div class="col-md-1" style="font-size: 10px">
                        <label>Via Adm.</label>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-1" style="font-size: 10px">
                        <label>Diagnostico</label>
                    </div>
                    <?php if($recetas[0]->id_prestacion!=0): ?>
					<div class="col-md-1" style="font-size: 10px">
                        <label>Posologia</label>
                    </div>
                    <?php endif; ?>
                    <?php if($recetas[0]->id_prestacion!=0): ?>
					<div class="col-md-1" style="font-size: 10px">
                        <label>Observación</label>
                    </div>
                    <?php endif; ?>
					<div style="clear:both"></div>
                    <form  id="frmDispensar" class="form-horizontal" method="post" action="{{ route('dispensar_producto')}}" id="frmReceta" style="font-size:12px;"><?php $n=1; ?>
                        @foreach($medicamentos as $med)                    
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" value="{{$recetas[0]->id}}" name="id_receta_vale">                             
                        <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                        <div class="row">
                            <div class="col-md-1">
                                <p align="text-center"><?php echo $n++; ?> </p>
                            </div>
                            <div class="col-md-2" style=" margin-bottom: 5px">
                                <spam id="lblNombre">{{$med->codigo}} - {{$med->nombre}} 
                                    @if($med->presentacion==1 && $med->cantidad_dispensada>0)
                                    ({{$med->cantidad_dispensada}} X {{$med->presentacion_comercial}})
                                    @endif
                                    @if($med->id_producto==11045 || $med->id_producto==15938 || $med->id_producto==14607 || $med->id_producto==14608)
                                    ({{$med->cantidad_dispensada}} L )
                                    @endif
                                    - {{$med->abreviatura}}
                                </spam> 
                                <input type="hidden" class="form-control" value="{{$med->codigo}}" name="codigo_producto[]" id="codigo_producto[]">
                                <input type="hidden" class="form-control" value="{{$med->id_producto}}" name="id_producto[]" id="id_producto[]">
                                <input type="hidden" class="form-control" value="{{$med->id_producto_receta}}"  name="id_producto_receta[]" id="id_producto_receta[]">
                                <input type="hidden" value="{{$recetas[0]->id_farmacia}}" name="id_farmacia[]">                                                                         
                                <input type="hidden" value="{{$recetas[0]->nro_receta}}" name="nro_receta[]">
                            </div>

                            <?php if (isset($flag_receta_dias) && $flag_receta_dias == 1): ?>
                                <div class="col-md-1" style=" margin-bottom: 5px">
                                    <input type="text" id="txtTiempoPeriodo" class="" value="{{$med->tiempo_periodo}}" disabled="" style="width: 50px">
                                    <input type="hidden" class="" value="{{$med->tiempo_periodo}}" name="tiempo_periodo[]" id="tiempo_periodo[]">
                                </div>
                            <?php endif; ?>

                            <div class="col-md-1" style=" margin-bottom: 5px">
                                <input type="text" id="txtCantidadPrescrita" class="" value="<?php if($med->id_producto==11045 || $med->id_producto==15938 || $med->id_producto==14607 || $med->id_producto==14608) {echo ($med->cantidad_prescrita)/1000; }else {echo $med->cantidad_prescrita;} ?>" disabled="" style="width: 50px">
                                <input type="hidden" class="" value="<?php if($med->id_producto==11045){ echo ($med->cantidad_prescrita)/1000;} else{ echo $med->cantidad_prescrita;} ?>" name="cantidad_prescrita[]" id="cantidad_prescrita[]">
                            </div>
                            <div class="col-md-1" style=" margin-bottom: 5px">
                                <input type="text" id="txtCantidadDispensada" class="" value="<?php if($med->id_producto==11045 || $med->id_producto==15938 || $med->id_producto==14607 || $med->id_producto==14608){ echo ($med->cantidad_dispensada)/1000; }else {echo $med->cantidad_dispensada;} ?>" disabled="" style="width: 50px">
                                <input type="hidden" class="" value="<?php if($med->id_producto==11045){ echo ($med->cantidad_dispensada)/1000; }else{  echo $med->cantidad_dispensada;} ?>" name="cantidad_dispensada[]" id="cantidad_dispensada[]">
                            </div>
                            <div class="col-md-1" style=" margin-bottom: 5px">
                                <spam id="lblNombre"><?php if($med->id_producto==11045 || $med->id_producto==15938 || $med->id_producto==14607 || $med->id_producto==14608){ echo ($med->stock_establecimiento)/1000; } else { echo $med->stock_establecimiento; } ?></spam> 
                            </div>
							<div class="col-md-1" style=" margin-bottom: 5px">
                                <spam id="lblNombre"><?php if($med->id_producto==11045 || $med->id_producto==15938 || $med->id_producto==14607 || $med->id_producto==14608){ echo ($med->stock_farmacia)/1000;} else {echo  $med->stock_farmacia;} ?></spam> 
                            </div>
                            <?php if($recetas[0]->id_prestacion!=0): ?>
							<div class="col-md-1" style=" margin-bottom: 5px">
                                <spam id="lblNombre"><?php echo $med->via; ?></spam> 
                            </div>
                            <?php endif; ?>
                            <div class="col-md-1" style=" margin-bottom: 5px">
                                <span id="lblNombre"><?php echo $med->cod_diagnostico; ?></span>
                            </div>
                            <?php if($recetas[0]->id_prestacion!=0): ?>
							<div class="col-md-1" style=" margin-bottom: 5px">
                                <spam id="lblNombre"><?php echo $med->posologia; ?></spam> 
                            </div>
                            <?php endif; ?>
							<?php if($recetas[0]->id_prestacion!=0): ?>
                            <div class="col-md-1"  style="margin-bottom: 5px" >  
                                <?php   
                                    if($med->cantidad_prescrita>$med->cantidad_dispensada ):
                                        switch ($med->id_observacion) {
                                            case 0: 
                                                if (Auth::user()->id_farmacia == 128):
                                                    if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)): echo "Sin Stock"; else: echo " "; endif;
                                                else:
                                                    if($med->cantidad_dispensada==0): echo "Sin Stock"; else: echo " "; endif;
                                                endif;
                                                break;
                                            case 1: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)) echo "Sin Stock"; break;
                                            case 2: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)) echo "PT"; break;
                                            case 3: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0))  echo "NR"; break;
                                            case 4: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0))  echo "NC"; break;
                                            case 5: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0))  echo "MP"; break;
                                            case 6: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0))  echo "Alerta DIGEMID"; break;
                                            case 7: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0))  echo "Suspendido"; break;
                                            case 8: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0))  echo "MP"; break;
                                            case 9: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0))  echo "MP"; break;
                                            case 10: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0))  echo "MP"; break;
                                            case 11: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0))  echo "MP"; break;
                                            case 12: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0))  echo "MP"; break;
                                            case 13: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0))  echo "MP"; break;
                                            case 14: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0)) echo "MP"; break;
                                            case 15: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0))  echo "MP"; break;
                                            case 16: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0))  echo "MP"; break;
                                            case 17: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0))  echo "RP"; break;
                                            case 18: if (Auth::user()->id_farmacia == 128):
                                                        if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0))  echo "Atendido"." - ".$med->receta_nro;
                                                     endif;
                                                     break;
                                            case 19: if(Auth::user()->id_farmacia == 18):
                                                        if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0))  echo "Por Recoger";
                                                     endif;
                                                     break;
                                            case 20: if(($med->cantidad_dispensada==0) || ($med->cantidad_dispensada!=0))  echo "No Petitorio"; break;
                                        }  
                                    else:
                                        echo " ";
                                    endif;
                                ?>
                            </div>
                            <?php endif; ?>
                        </div>                                         
                        @endforeach
                        <hr/>
                    <!--<div class="col-md-3 pull-right">
                        <label>FECHA DE REGISTRO</label>                        
                        <p>                            
                            <?php
                            $originalDate2 = $recetas[0]->created_at;
                            $fecha = date("d-m-Y (H:i:s)", strtotime($originalDate2));
                            echo $fecha;
                            ?>
                            
                        </p>
                    </div>-->
                        @if($recetas[0]->estado==0)
                        <div class="col-md-12" style="border-top: 1px solid gray; padding-top: 10px">                                                  
                            Receta Invalidada
                        </div>
                        @else
                        @if($recetas[0]->tipo_receta!="SOPORTE")
                            @if($recetas[0]->tipo_receta=="QUIRURGICO" )
                                <div class="col-md-12" style="border-top: 1px solid gray; padding-top: 10px">
                                    <div class="col-md-3">
                                        <a id="btnReg" class="form-control btn btn-success" href="/formulario_receta">NUEVA RECETA</a> 
                                    </div>
                                </div> 
                            @else
                                @if($recetas[0]->tipo_receta=="HOSPITALIZACION")
                                    <div class="col-md-12" style="border-top: 1px solid gray; padding-top: 10px">
                                        <div class="col-md-3">
                                            <a id="btnReg" class="form-control btn btn-success" href="/formulario_receta">NUEVA RECETA</a> 
                                        </div>
                                    </div> 
                                @else
                                    @if($recetas[0]->tipo_receta=="EMERGENCIA")
                                        <div class="col-md-12" style="border-top: 1px solid gray; padding-top: 10px">
                                            <div class="col-md-3">
                                                <a id="btnReg" class="form-control btn btn-success" href="/formulario_receta">NUEVA RECETA</a> 
                                            </div>
                                        </div> 
                                    @else
                                        @if($recetas[0]->tipo_receta=="AMBULATORIO")
                                            @if($recetas[0]->tipo_receta=="AMBULATORIO" && $recetas[0]->id_farmacia!=18)
                                                <div class="col-md-12" style="border-top: 1px solid gray;   padding-top: 10px">
                                                    <div class="col-md-3">
                                                        <a id="btnReg" class="form-control btn btn-success" href="/formulario_receta">NUEVA RECETA</a> 
                                                    </div>
                                                
                                            @else
                                                    <div class="col-md-12" style="border-top: 1px solid gray;   padding-top: 10px">
                                                    <div class="col-md-3">
                                                        <a id="btnReg" class="form-control btn btn-success" href="/formulario_receta">NUEVA RECETA</a> 
                                                    </div>
                                                
                                            @endif
                                        @else
                                            <div class="col-md-12" style="border-top: 1px solid gray; padding-top: 10px">
                                                <div class="col-md-3">
                                                    <a id="btnReg" class="form-control btn btn-success" href="/formulario_receta">NUEVA RECETA</a>
                                                </div>
                                            
                                        @endif    
                                   @endif     
                                @endif
                            @endif
                            		<!--<a href="/imprimir_receta_vale/{{$recetas[0]->id}}/{{$recetas[0]->id_farmacia}}" class="btn btn-danger pull-right" target="_blank">
                                        Imprimir<i class="fa fa-file-pdf-o"></i>
                                    </a>-->
									
									
									
									<a href="/imprimir_receta_vale/{{$recetas[0]->id}}/{{$recetas[0]->id_farmacia}}" class="btn btn-danger pull-right" target="_blank">
										Imprimir Receta Dispensada <i class="fa fa-file-pdf-o"></i>
									</a>
									<?php 
										if ($recetas[0]->ruta_receta == "") {
									?>
										<button class="btn btn-danger pull-right" style="margin-right:15px" disabled="">Imprimir Receta Prescrita <i class="fa fa-file-pdf-o"></i></button>
									<?php 
										} else {
									?>
										<a href="/{{$recetas[0]->flag}}/{{$recetas[0]->ruta_receta}}" class="btn btn-danger pull-right" target="_blank" style="margin-right:15px">
											Imprimir Receta Prescrita <i class="fa fa-file-pdf-o"></i>
										</a>
									<?php 
									}
									?>
									
									<?php 
										if (!isset($recetas[0]->ruta_prestacion) || $recetas[0]->ruta_prestacion == "") {
									?>
										<button class="btn btn-danger pull-right" style="margin-right:15px" disabled="">Imprimir Prestación Medica <i class="fa fa-file-pdf-o"></i></button>
									<?php 
										} else {
									?>
										<a href="/{{$recetas[0]->flag}}/{{$recetas[0]->ruta_prestacion}}" class="btn btn-danger pull-right" target="_blank" style="margin-right:15px">
											Imprimir Prestación Medica <i class="fa fa-file-pdf-o"></i>
										</a>
									<?php 
									}
									?>
									
                        @else
						
                            <div class="col-md-9" style="border-top: 1px solid gray; padding-top: 10px; text-align: right">                                                                                 
                                <a href="/imprimir_receta_cirugia/{{$recetas[0]->id}}/{{$recetas[0]->id_farmacia}}" class="btn btn-default btn-xs pull-right" target="_blank">
                                    <i class="fa fa-print"></i> Imprimir receta
                                </a>                            
                            </div>
                            <div class="col-md-3" style="border-top: 1px solid gray; padding-top: 10px"> 
                                <button class="btn btn-default btn-xs pull-right" disabled="" ><i class="fa fa-print"></i> Imprimir receta valorizado</button>

                                <!--<a href="/imprimir_receta_cirugia_valorizado/{{$recetas[0]->id}}/{{$recetas[0]->id_farmacia}}" class="btn btn-default btn-xs pull-right" target="_blank">
                                    <i class="fa fa-print"></i> Imprimir receta valorizado
                                </a>-->                            
                            </div>
                        @endif
                    @endif
                    </form> 
                    @endif
                </div>
                @else
                <div class="panel-body" style="padding-top:10px; font-size: 13px">
                    No se encuentra el n&uacute;mero de receta que digito.
                </div>
                @endif
            </div>         
        </div>
    </div>
</div>
@if(count($recetas)>0) 
<!-- line modal -->
<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title" id="lineModalLabel">Invalidar Receta</h3>
            </div>
            <div class="modal-body">
                <!-- content goes here -->
                <form class="" method="post" action="{{ route('invalidar_receta')}}" id="">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Escribir el motivo para invalidar el registro de esta receta</label>
                        <input type="text" class="form-control" id="motivo" name="motivo" placeholder="" required="">
                        <input type="hidden" class="form-control" id="id_farmacia" name="id_farmacia" value="{{$recetas[0]->id_farmacia}}">
                        <input type="hidden" class="form-control" id="nro_receta" name="nro_receta" value="{{$recetas[0]->nro_receta}}">
                        <input type="hidden" class="form-control" id="id_receta" name="id_receta" value="{{$recetas[0]->id}}">
                        <!--Se añadio la fecha -->
                        <input type="hidden" class="form-control" id="fecha_receta_origen" name="fecha_receta_origen" value="{{$recetas[0]->fecha_expedicion}}">                        
                        <!-- fin -->
                        <input type="hidden" class="form-control" id="id_user" name="id_user" value="{{ Auth::user()->id }}">
                    </div>                    
                    <div class="form-group">
                        <input id="btnInvalidar" class="btn btn-danger" type="submit" value="Invalidar">                        
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="squarespaceModal2" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title" id="lineModalLabel">Editar Fecha</h3>
            </div>
            <div class="modal-body">

                <!-- content goes here -->
                <form class="" method="post" action="{{ route('editar_fecha_receta')}}" id="">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">       
                        <?php
                        $data2 = $recetas[0]->fecha_registro;
                        $fecha2 = date("d-m-Y", strtotime($data2));
                        ?>
                        <input type="text" class="form-control datepicker" id="fecha_atencion" name="fecha_atencion" value="<?php echo $fecha2; ?>" required="">
                        <input type="hidden" class="form-control" id="id_receta" name="id_receta" value="{{$recetas[0]->id}}">
                        <input type="hidden" class="form-control" id="nro_receta" name="nro_receta" value="{{$recetas[0]->nro_receta}}">
                        <input type="hidden" class="form-control" id="id_user" name="id_user" value="{{ Auth::user()->id }}">
                        <input type="hidden" class="form-control" id="id_farmacia" name="id_farmacia" value="{{$recetas[0]->id_farmacia}}">
                    </div>                    
                    <div class="form-group">
                        <input id="btnInvalidar" class="btn btn-primary" type="submit" value="Actualizar">                        
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="squarespaceModal3" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title" id="lineModalLabel">Editar Servicio</h3>
            </div>
            <div class="modal-body">

                <!-- content goes here -->
                <form class="" method="post" action="{{ route('editar_consultorio_receta')}}" id="frmConsultorio">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">      
                        <label>Servicio actual</label>
                        <input type="text" class="form-control" id="consultorio" name="consultorio" value="{{$recetas[0]->consultorio}}" readonly="">
                        <label style="margin-top:10px">Nuevo Servicio</label><br>
                        <select name="id_consultorios" id="id_consultorios" class="form-control" style="" required="">
                            <option value="">Seleccionar</option>
                            @foreach($consultorios as $c)
                            <option value="{{$c->id}}">{{$c->nombre}}</option>
                            @endforeach
                        </select>                        
                        <input type="hidden" class="form-control" id="id_receta" name="id_receta" value="{{$recetas[0]->id}}">
                        <input type="hidden" class="form-control" id="nro_receta" name="nro_receta" value="{{$recetas[0]->nro_receta}}">
                        <input type="hidden" class="form-control" id="id_user" name="id_user" value="{{ Auth::user()->id }}">
                    </div>                    
                    <div class="form-group">
                        <input id="btnInvalidar" class="btn btn-primary" type="submit" value="Actualizar">                        
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="squarespaceModal4" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title" id="lineModalLabel">Editar Profesional de Salud Prescriptor</h3>
            </div>
            <div class="modal-body">

                <!-- content goes here -->
                <form class="" method="post" action="{{ route('editar_medico_receta')}}" id="frmConsultorio">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">      
                        <label>Prescriptor actual</label>
                        <input type="text" class="form-control" id="medico" name="medico" value="{{$recetas[0]->nombre_medico}} {{$recetas[0]->paterno_medico}} {{$recetas[0]->materno_medico}}" readonly="">
                        <label style="margin-top:10px">Nuevo Prescriptor</label><br>
                        <select name="id_medico" id="id_medico" class="form-control" style="" required="">
                            <option value="">Seleccionar</option>
                            @foreach($medicos as $m)
                            <option value="{{$m->id}}">{{$m->dni}} - {{$m->nombre}} {{$m->paterno}} {{$m->materno}}</option>
                            @endforeach
                        </select>                        
                        <input type="hidden" class="form-control" id="id_receta" name="id_receta" value="{{$recetas[0]->id}}">
                        <input type="hidden" class="form-control" id="nro_receta" name="nro_receta" value="{{$recetas[0]->nro_receta}}">
                        <input type="hidden" class="form-control" id="id_user" name="id_user" value="{{ Auth::user()->id }}">
                    </div>                    
                    <div class="form-group">
                        <input id="btnInvalidar" class="btn btn-primary" type="submit" value="Actualizar">                        
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>


@endif
@include('frontend.includes.footer')
@endsection