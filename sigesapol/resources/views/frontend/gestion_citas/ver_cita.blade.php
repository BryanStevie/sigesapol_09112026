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
                @if(count($cita)>0)                                
                <div class="panel-heading">
                    <h2 class="panel-title">CITA Nro: <?php echo $cita->nro_cita; ?></h2>                    
                </div>     
                <div class="panel-body" style="padding-top:30px; font-size: 13px">   
				
                    <div class="col-md-7" style="text-align: center">
                        <h4></h4>   
                    </div>
					<!--
                    <div class="col-md-2">
                        <label>Nro CITA</label>                        
                        <p></p>
                    </div>
					-->
					<div style="clear:both"></div>
					
                    <div class="col-md-3">
                        <label>DNI</label>
                        <p>
						<?php echo $cita->nro_doc_ident?>
						</p>

                    </div>
                    <div class="col-md-4">
                        <label>NOMBRE Y APELLIDOS</label>                        
                        <p>
						<?php echo $cita->asegurado_nombre." ".$cita->asegurado_paterno." ".$cita->asegurado_materno?>
						</p>
                    </div>
                    <div class="col-md-2">
                        <label>PARENTESCO</label>                        
                        <p>
							<?php echo $cita->parentesco?>
                        </p>
                    </div>
                    <div class="col-md-2">
                        <label>GRADO</label>                        
                        <p>
						<?php echo $cita->grado?>
						</p>
                    </div>
					
                    <div style="clear:both"></div>
					
					<div class="col-md-3">
                        <label>ESTABLECIMIENTO</label>                        
                        <p>
                           <?php echo $cita->establecimiento?>
                        </p>
                    </div>
					
                    <div class="col-md-4">
                        <label>SERVICIO</label>                        
                        <p>
                           <?php echo $cita->servicio?>
                        </p>
                    </div>
                    
                    <div class="col-md-5">
                        <label>MEDICO</label>                        
                        <p>
                           <?php echo $cita->medico_nombre." ".$cita->medico_paterno." ".$cita->medico_materno?>
                        </p>
                    </div>
                    <div style="clear:both"></div>
					<div class="col-md-3">
                        <label>CONSULTORIO</label>                        
                        <p>
							<?php echo $cita->consultorio?>
                        </p>
                    </div>
					<div class="col-md-4">
                        <label>FECHA DE CITA</label>                        
                        <p>
							<?php echo $cita->dia?>
                        </p>
                    </div>
                    <div class="col-md-2">
                        <label>HORA DE CITA</label>                        
                        <p>
						<?php echo $cita->hora?>
						</p>
                    </div>
					<div class="col-md-2">
                        <label>CORREO</label>                        
                        <p>
						<?php echo $cita->correo?>
						</p>
                    </div>
					<div style="clear:both"></div>
                    <div class="col-md-3">
                        <label>CELULAR </label>
                        <p><?php echo $cita->celular?></p> 
                    </div> 
                    <div class="col-md-6">
                        <label>DOMICILIO</label>  
                        <p><?php echo $cita->domicilio?></p> 
                    </div> 
					
                    <div class="col-md-1" style="font-size: 9px">
                        <label></label>
                    </div>

					<div class="col-md-12" style="border-top: 1px solid gray; padding-top: 20px">
						
							<div class="col-md-3" style="padding-left:0px;margin-left:0px">
								<a id="btnReg" class="form-control btn btn-success" href="/formulario_gestion_cita">Nueva Cita</a> 
							</div>
							<!--<a href="/imprimir_admision/" class="btn btn-danger pull-right" target="_blank">
                                Imprimir<i class="fa fa-file-pdf-o"></i>
                            </a>-->
                        </div> 
						
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

@include('frontend.includes.footer')
@endsection