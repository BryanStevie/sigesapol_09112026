

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

		
                @if(count($cita)>0)                                
                <div class="panel-body" style="padding-top:30px; font-size: 13px">   
				
                    <div class="col-md-7" style="text-align: center">
                        <h4></h4>   
                    </div>
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
                    
						
                </div>
                @else
                <div class="panel-body" style="padding-top:10px; font-size: 13px">
                    No se encuentra el numero de cita que registro.
                </div>
                @endif
        