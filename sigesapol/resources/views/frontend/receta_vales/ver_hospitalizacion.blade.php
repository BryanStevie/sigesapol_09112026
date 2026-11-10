@extends('frontend.layouts.master')
@section('renderjs')
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
                    <h2 class="panel-title">HOSPITALIZACI&Oacute;N - {{$farmacia->nombre}}</h2>                    
                </div>     
                @if(count($admision)>0)                                
                <div class="panel-body" style="padding-top:30px; font-size: 13px">   
                    <div class="col-md-3" style="position:relative">
                        <img src="../../../images/logo_sanidad.png" width="70" style="position:absolute;top:-20px">
                    </div>
                    <div class="col-md-6" style="text-align: center">
                        <h4>{{$admision[0]->ipress}}</h4>                            
                    </div>
                    <div class="col-md-3" style="position:relative">
                        <img src="../../../images/logo_sanidad.png" width="70" style="position:absolute;top:-20px">
                    </div>
                    
                    <div class="clearfix">&nbsp;</div>
                    <div class="col-md-3">
                        <label>Nro HOSPITALIZACI&Oacute;N</label>                        
                        <p>{{$admision[0]->nro_hospitalizacion}}</p>
                    </div>
                    
                    <div class="col-md-2">
                        <label>DNI</label>
                        <p>{{$admision[0]->dni_beneficiario}}</p>

                    </div>
                    <div class="col-md-5">
                        <label>NOMBRE Y APELLIDOS</label>                        
                        <p><?php echo utf8_decode($admision[0]->nombre_beneficiario." ".$admision[0]->paterno_beneficiario." ".$admision[0]->materno_beneficiario)?></p>
                    </div>
                    <div class="col-md-2">
                        <label>BENEFICIARIO</label>                        
                        <p>{{$admision[0]->tipo_beneficiario}}</p>
                    </div>                
                    <div class="clearfix">&nbsp;</div>    
                    
                    <div class="col-md-3">
                        <label>FEC. INGRESO</label>                        
                        <p>
                        <?php
                            $fecha_ingreso = $admision[0]->fecha_ingreso;
                            $fecha_ingreso = date("d-m-Y (H:i:s)", strtotime($fecha_ingreso));
                            echo $fecha_ingreso;
                        ?>
                        </p>
                    </div>
                    <div class="col-md-2">
                        <label>H. C.</label>                        
                        <p>{{$admision[0]->nro_historia}}</p>
                    </div>

                    <div class="col-md-3">
                        <label>ESTADO</label>                        
                        <p>
                            @if($id_user_consultorio!=17)
                            <?php  
                            $fecha_salida = $admision[0]->fecha_salida;
                            if(is_null($fecha_salida)){
                                echo "HOSPITALIZADO";
                            }
                            else
                            {
                                echo "ALTA";
                            }
                            ?>
                            @else
                                HEMODIALISIS
                            @endif
                        </p>
                    </div>



                    <div class="col-md-2">
                        <label>EDAD</label>                        
                        <p>
                            <?php echo $admision[0]->edad;?>
                        </p>
                    </div>

                    <div class="col-md-2">
                        <label>SEXO</label>                        
                        <p>
                            <?php echo $admision[0]->sexo;?>
                        </p>
                    </div>
                    
                    <div class="clearfix"></div>                    
                    @if($nivel==3)
                        <div class="col-md-3">
                            <label>SERVICIO</label>                        
                            <p>
                                <?php echo $admision[0]->nombre_consultorio;?>
                            </p>
                        </div>
                    @endif
                    @if($id_user_consultorio!=17)
                    <div class="col-md-3">
                        <label>SALA</label>                        
                        <p>
                            <?php echo $admision[0]->consultorio_hospitalizacion;?>
                        </p>
                    </div>
                    <div class="col-md-3">
                        <label>ORIGEN</label>                        
                        @if($admision[0]->origen=='O1')
                        <p>EMERGENCIA</p>
                        @else
                            @if($admision[0]->origen=='O2')
                                <p>CONSULTORIO EXTERNO</p>
                            @else
                                <p>MOPRI</p>
                            @endif
                        @endif
                    </div>
                  
                    <div class="col-md-6">

                        <label>MEDICO ORDENO HOSPITALIZACION</label>                        
                        <p>
                        <?php
                            echo $admision[0]->nombre_medico_hospitalizacion." ".$admision[0]->paterno_medico_hospitalizacion." ".$admision[0]->materno_medico_hospitalizacion;
                        ?>
                        </p>
                    </div>
                    @else
                    <div class="col-md-6">

                        <label>MEDICO</label>                        
                        <p>
                        <?php
                            echo $admision[0]->nombre_medico_hospitalizacion." ".$admision[0]->paterno_medico_hospitalizacion." ".$admision[0]->materno_medico_hospitalizacion;
                        ?>
                        </p>
                    </div>
                    @endif
                    @if($id_user_consultorio!=17)
                    <div class="clearfix"></div>
                    
                    
                    <div class="col-md-3">
                        <label>PABELLON</label>                        
                        <p>
                            <?php echo $admision[0]->pabellon;?>
                        </p>
                    </div>

                    <div class="col-md-3">
                        <label>PISO</label>                        
                        <p>
                            <?php echo $admision[0]->piso;?>
                        </p>
                    </div>

                    <div class="col-md-3">
                        <label>CUARTO</label>                        
                        <p>
                            <?php echo $admision[0]->cuarto;?>
                        </p>
                    </div>
                    <div class="col-md-3">
                        <label>CAMA</label>                        
                        <p>
                            <?php echo $admision[0]->cama;?>
                        </p>
                    </div>
                @endif
                    <div class="clearfix"></div>
                    <div class="col-md-6">
                        <label>REGISTRADO POR </label>
                        <p>{{$admision[0]->nombre_usuario}} {{$admision[0]->paterno_usuario}} {{$admision[0]->materno_usuario}}</p> 
                    </div> 
                    <div class="col-md-6">
                        <label>FECHA DE REGISTRO</label>  
                        <?php
                            $originalDate5 = $admision[0]->fecha_creacion;
                            $fecha_creacion = date("d-m-Y (H:i:s)", strtotime($originalDate5));
                        ?>
                        <p><?php echo $fecha_creacion?></p> 
                    </div> 
                    <div class="clearfix"></div>
                    <div class="col-md-1" style="font-size: 9px">
                        <label></label>
                    </div>
                        @if($admision[0]->estado==0)
                        <div class="col-md-12" style="border-top: 1px solid gray; padding-top: 10px">                                                  
                            Receta Invalidada
                        </div>
                        @else
                        <div class="col-md-12" style="border-top: 1px solid gray; padding-top: 10px"> 
                            <div class="col-md-4">
                                <a id="btnReg" class="form-control btn btn-success" href="/nueva_admision">NUEVO REGISTRO</a> 
                            </div>
                            <div class="col-md-4">
                                <a id="btnReg" class="form-control btn btn-primary" href="/modifica_registro_hospitalizado/{{$admision[0]->id_farmacia}}/{{$admision[0]->id}}">MODIFICAR REGISTRO</a> 
                            </div>
                            <div class="col-md-4">
                                <a href="/imprimir_nuevo_hospitalizado/{{$admision[0]->id}}/{{$admision[0]->id_farmacia}}/{{$id_user}}" class="btn btn-danger pull-right" target="_blank">
                                        Imprimir<i class="fa fa-file-pdf-o"></i>
                                    </a>
                            </div>
                        </div>
                        @endif
                </div>
                @else
                <div class="panel-body" style="padding-top:10px; font-size: 13px">
                    Datos del Paciente no registrado, o el paciente ya ha sido dado de alta.
                </div>
                @endif
            </div>         
        </div>
    </div>
</div>

@include('frontend.includes.footer')
@endsection