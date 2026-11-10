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
<div class="container">
    <div class="row">
		
        <div class="col-sm-9 col-md-9 col-md-offset-2">
            <div class="panel panel-primary">                
                <div class="panel-heading">
                    <h2 class="panel-title">ADMISI&Oacute;N - {{$farmacia->nombre}}</h2>                    
                </div>     
                @if(count($admision)>0)                                
                <div class="panel-body" style="padding-top:30px; font-size: 13px">   
                    <div class="col-md-3" style="position:relative">
                        <img src="../../../images/logo_sanidad.png" width="70" style="position:absolute;top:-20px">
                    </div>
                    <div class="col-md-7" style="text-align: center">
                        <h4>{{$admision[0]->ipress}}</h4>                            
                    </div>
                    <div class="col-md-2">
                        <label>Nro ADMISI&Oacute;N</label>                        
                        <p>{{$admision[0]->nro_admision}}</p>
                    </div>
					
					<div class="clearfix">&nbsp;</div>
					
                    <div class="col-md-2">
                        <label>DNI</label>
                        <p>{{$admision[0]->dni_beneficiario}}</p>

                    </div>
                    <div class="col-md-5">
                        <label>NOMBRE Y APELLIDOS</label>                        
                        <p><?php echo utf8_decode($admision[0]->nombre_beneficiario." ".$admision[0]->paterno_beneficiario." ".$admision[0]->materno_beneficiario)?></p>
                    </div>
                    <div class="col-md-3">
                        <label>FECHA ADMISI&Oacute;N</label>                        
                        <p>                            
                            <?php
                            $originalDate2 = $admision[0]->fecha_admision;
                            $fecha = date("d-m-Y", strtotime($originalDate2));
                            echo $fecha;
                            ?>
                            @if($admision[0]->estado==1)
                            <?php
                            /*if ($fecha >= '19-12-2016') {
                                ?>
                                <a data-toggle="modal" data-target="#squarespaceModal2">
                                    <i class="fa fa-edit"></i> <!--<span style="font-size:10px">Editar</span>-->
                                </a>
                                <?php
                            }*/
                            ?>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-2">
                        <label>BENEFICIARIO</label>                        
                        <p>{{$admision[0]->tipo_beneficiario}}</p>
                    </div>                    
                    
                    <div class="col-md-3">
                        <label>SERVICIO</label>                        
                        <p>
                            {{$admision[0]->consultorio}}
                            <!--@if($admision[0]->estado==1)
                            <a data-toggle="modal" data-target="#squarespaceModal3">
                                <i class="fa fa-edit"></i> 
                            </a>
                            @endif-->
                        </p>
                    </div>
                    <div class="col-md-3">
                        <label>TIPO DE ATENCI&Oacute;N</label>                        
                        <p>
                            {{$admision[0]->tipo_admision}}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label>PROFESIONAL PRESCRIPTOR</label>                        
                        <p>{{$admision[0]->nombre_medico}} {{$admision[0]->paterno_medico}} {{$admision[0]->materno_medico}}
                            <!--@if($admision[0]->estado==1)
                            <a data-toggle="modal" data-target="#squarespaceModal4">
                                <i class="fa fa-edit"></i> 
                            </a>
                            @endif-->
                        </p>
                    </div>
                    
                    <div class="col-md-3">
                        <label>CREADO POR </label>
                        <p>{{$admision[0]->nombre_usuario}} {{$admision[0]->paterno_usuario}} {{$admision[0]->materno_usuario}}</p> 
                    </div> 
                    <div class="col-md-3">
                        <label>FECHA DE CREACION</label>  
                        <?php
                            $originalDate5 = $admision[0]->fecha_creacion;
                            $fecha_creacion = date("d-m-Y (H:i:s)", strtotime($originalDate5));                                    
                        ?>
                        <p><?php echo $fecha_creacion?></p> 
                    </div> 
                    
					<!--
                    @if($admision[0]->estado==1) 
                    <div class="col-md-12" style="margin-bottom:10px">
                        <a data-toggle="modal" data-target="#squarespaceModal" class="btn btn-danger">
                            <i class="fa fa-xing"></i>Invalidar Receta (Permiso-Reponsable Farmacia)
                        </a>                          
                    </div>
                    @else
                    
                    <div class="col-md-8" style="border: 1px solid red; margin-top: 10px; background-color: #FFDA6A">
                        Motivo: {{$receta_devuelta[0]->motivo}}<br>
                        <?php
                            $originalDate8 = $receta_devuelta[0]->fecha_devolucion;
                            $fecha_devolucion = date("d-m-Y", strtotime($originalDate8));                                    
                        ?>
                        Fecha de Invalidación: <?php echo $fecha_devolucion ?><br>
                        Usuario Invalidador: {{$receta_devuelta[0]->name}} {{$receta_devuelta[0]->paterno}} {{$receta_devuelta[0]->materno}}
                    </div>
                    @endif
					-->
					
                    <div class="col-md-1" style="font-size: 9px">
                        <label></label>
                    </div>
                        @if($admision[0]->estado==0)
                        <div class="col-md-12" style="border-top: 1px solid gray; padding-top: 10px">                                                  
                            Receta Invalidada
                        </div>
                        @else
                        @if($admision[0]->tipo_admision!="CIRUGIA")
						
                        <div class="col-md-12" style="border-top: 1px solid gray; padding-top: 10px">                                                       
						
							<div class="col-md-3">
								<a id="btnReg" class="form-control btn btn-success" href="/nueva_admision">Nueva Admisi&oacute;n</a> 
							</div>
							<a href="/imprimir_admision/{{$admision[0]->nro_admision}}/{{$admision[0]->id_farmacia}}" class="btn btn-danger pull-right" target="_blank">
                                Imprimir<i class="fa fa-file-pdf-o"></i>
                            </a>
                        </div> 
                        @else
                        <div class="col-md-9" style="border-top: 1px solid gray; padding-top: 10px; text-align: right">                                                                                 
                            <a href="/imprimir_receta_cirugia/{{$admision[0]->id}}/{{$admision[0]->id_farmacia}}" class="btn btn-default btn-xs pull-right" target="_blank">
                                <i class="fa fa-print"></i> Imprimir receta
                            </a>                            
                        </div>
                        <div class="col-md-3" style="border-top: 1px solid gray; padding-top: 10px"> 
                            <button class="btn btn-default btn-xs pull-right" disabled="" ><i class="fa fa-print"></i> Imprimir receta valorizado</button>
                        </div>
                        @endif
                        @endif
                </div>
                @else
                <div class="panel-body" style="padding-top:10px; font-size: 13px">
                    No se encuentra el numero de receta que digito.
                </div>
                @endif
            </div>         
        </div>
    </div>
</div>
@if(count($admision)>0) 
<!-- line modal -->
<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title" id="lineModalLabel">Invalidar Receta</h3>
            </div>
            <div class="modal-body">

                <!-- content goes here -->
                <form class="" method="post" action="{{ route('invalidar_receta')}}" id="">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Escribir el motivo para invalidar el registro de esta receta</label>
                        <input type="text" class="form-control" id="motivo" name="motivo" placeholder="" required="">
                        <input type="hidden" class="form-control" id="id_farmacia" name="id_farmacia" value="{{$admision[0]->id_farmacia}}">
                        <input type="hidden" class="form-control" id="nro_admision" name="nro_admision" value="{{$admision[0]->nro_admision}}">
                        <input type="hidden" class="form-control" id="id_receta" name="id_receta" value="{{$admision[0]->id}}">
                        <!--Se añadio la fecha -->
                        <input type="hidden" class="form-control" id="fecha_receta_origen" name="fecha_receta_origen" value="{{$admision[0]->fecha_admision}}">                        
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
                        $data2 = $admision[0]->fecha_admision;
                        $fecha2 = date("d-m-Y", strtotime($data2));
                        ?>
                        <input type="text" class="form-control datepicker" id="fecha_atencion" name="fecha_atencion" value="<?php echo $fecha2; ?>" required="">
                        <input type="hidden" class="form-control" id="id_receta" name="id_receta" value="{{$admision[0]->id}}">
                        <input type="hidden" class="form-control" id="nro_admision" name="nro_admision" value="{{$admision[0]->nro_admision}}">
                        <input type="hidden" class="form-control" id="id_user" name="id_user" value="{{ Auth::user()->id }}">
                        <input type="hidden" class="form-control" id="id_farmacia" name="id_farmacia" value="{{$admision[0]->id_farmacia}}">
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
                        <input type="text" class="form-control" id="consultorio" name="consultorio" value="{{$admision[0]->consultorio}}" readonly="">
                        <label style="margin-top:10px">Nuevo Servicio</label><br>
                        <select name="id_consultorios" id="id_consultorios" class="form-control" style="" required="">
                            <option value="">Seleccionar</option>
                            @foreach($consultorios as $c)
                            <option value="{{$c->id}}">{{$c->nombre}}</option>
                            @endforeach
                        </select>                        
                        <input type="hidden" class="form-control" id="id_receta" name="id_receta" value="{{$admision[0]->id}}">
                        <input type="hidden" class="form-control" id="nro_admision" name="nro_admision" value="{{$admision[0]->nro_admision}}">
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
                        <input type="text" class="form-control" id="medico" name="medico" value="{{$admision[0]->nombre_medico}} {{$admision[0]->paterno_medico}} {{$admision[0]->materno_medico}}" readonly="">
                        <label style="margin-top:10px">Nuevo Prescriptor</label><br>
                        <select name="id_medico" id="id_medico" class="form-control" style="" required="">
                            <option value="">Seleccionar</option>
                            @foreach($medicos as $m)
                            <option value="{{$m->id}}">{{$m->dni}} - {{$m->nombre}} {{$m->paterno}} {{$m->materno}}</option>
                            @endforeach
                        </select>                        
                        <input type="hidden" class="form-control" id="id_receta" name="id_receta" value="{{$admision[0]->id}}">
                        <input type="hidden" class="form-control" id="nro_admision" name="nro_admision" value="{{$admision[0]->nro_admision}}">
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