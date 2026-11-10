<div class="row">
    @if(count($hospitalizado)>0)                                
        
        <div class="col-md-2">
            <label>DNI PACIENTE</label>
            <p>{{$hospitalizado[0]->dni_beneficiario}}</p>
        </div>
        <div class="col-md-5">
            <label>NOMBRE Y APELLIDOS</label>                        
            <p><?php echo $hospitalizado[0]->nombre_beneficiario . " " . $hospitalizado[0]->paterno_beneficiario . " " . $hospitalizado[0]->materno_beneficiario ?></p>
        </div>
        <div class="col-md-3">
            <label>HISTORIA CLÍNICA</label>
            <p class="text-center">                            
                {{$hospitalizado[0]->nro_historia}}
            </p>
        </div>
        <div class="col-md-2">
            <label>BENEFICIARIO</label>                        
            <p>{{$hospitalizado[0]->tipo_beneficiario}}</p>
        </div>                    
        <div class="col-md-3">
            <label>FECHA INGRESO</label>                        
            <p>                            
                <?php
                $originalDate2 = $hospitalizado[0]->fecha_ingreso;
                $fecha = date("d-m-Y", strtotime($originalDate2));
                echo $fecha;
                ?>
            </p>
        </div>
        @if(!is_null($hospitalizado[0]->fecha_salida))
            <div class="col-md-3">
                <label>FECHA DE SALIDA</label>
                <?php
                    $fecha_salida = $hospitalizado[0]->fecha_salida;
                    $fecha_salida = date("d-m-Y", strtotime($fecha_salida));
                ?>
                <p><?php echo $fecha_salida ?></p> 
            </div>
            <div class="col-md-3">
                <label>TIPO DE ATENCI&Oacute;N</label>                        
                <p>ALTA</p>        
            </div>
            <div class="col-md-3">
                <label>SERVICIO</label>                        
                <p>
                    {{$hospitalizado[0]->consultorio}}
                </p>
            </div>
            
            <div class="col-md-6">
                @if($estado==1)
                    <label>PROFESIONAL QUE ORDENO INGRESO</label>
                @else
                    <label>PROFESIONAL QUE ORDENO ALTA</label>
                @endif
                <p>{{$hospitalizado[0]->nombre_medico}} {{$hospitalizado[0]->paterno_medico}} {{$hospitalizado[0]->materno_medico}}
                </p>
            </div>
            <div class="col-md-6">
                <label>DIAGNOSTICO</label>                        
                <p>{{$hospitalizado[0]->cie}} - {{$hospitalizado[0]->nombre_diagnostico}}</p> 
            </div>  

            <div class="col-md-3">
                <label>PABELLON</label>                        
                <p>
                    {{$hospitalizado[0]->pabellon}}
                </p>
            </div>
            <div class="col-md-3">
                <label>PISO</label>                        
                <p>
                    {{$hospitalizado[0]->piso}}
                </p>
            </div>
            <div class="col-md-3">
                <label>CAMA</label>                        
                <p>
                    {{$hospitalizado[0]->cama}}
                </p>
            </div>
            <div class="col-md-3">
                <label>CUARTO</label>                        
                <p>
                    {{$hospitalizado[0]->cuarto}}
                </p>
            </div>

            <div class="col-md-4">
                <label>REGISTRADOR</label>                        
                <p>{{$hospitalizado[0]->nombre_usuario}} {{$hospitalizado[0]->paterno_usuario}} {{$hospitalizado[0]->materno_usuario}}</p> 
            </div>
        @else 
            <div class="col-md-3">
                <label>SERVICIO</label>                        
                <p>
                    {{$hospitalizado[0]->consultorio}}
                </p>
            </div>
            <div class="col-md-3">
                <label>TIPO DE ATENCI&Oacute;N</label>                        
                <p>HOSPITALIZADO</p>        
            </div>
            <div class="col-md-6">
                <label>PROFESIONAL ORDENO INGRESO</label>
                <p>{{$hospitalizado[0]->nombre_medico}} {{$hospitalizado[0]->paterno_medico}} {{$hospitalizado[0]->materno_medico}}
                </p>
            </div>
            <div class="col-md-6">
                <label>DIAGNOSTICO</label>                        
                <p>{{$hospitalizado[0]->cie}} - {{$hospitalizado[0]->nombre_diagnostico}}</p> 
            </div>  
            <div class="col-md-3">
                <label>PABELLON</label>                        
                <p>
                    {{$hospitalizado[0]->pabellon}}
                </p>
            </div>
            <div class="col-md-3">
                <label>PISO</label>                        
                <p>
                    {{$hospitalizado[0]->piso}}
                </p>
            </div>
            <div class="col-md-3">
                <label>CAMA</label>                        
                <p>
                    {{$hospitalizado[0]->cama}}
                </p>
            </div>
            <div class="col-md-3">
                <label>CUARTO</label>                        
                <p>
                    {{$hospitalizado[0]->cuarto}}
                </p>
            </div>
            <div class="col-md-4">
                <label>REGISTRADOR</label>                        
                <p>{{$hospitalizado[0]->nombre_usuario}} {{$hospitalizado[0]->paterno_usuario}} {{$hospitalizado[0]->materno_usuario}}</p> 
            </div>   
        @endif
        
    @else
        <div class="panel-body" style="padding-top:10px; font-size: 13px">
            No se encuentra el paciente hospitalizado.
        </div>
    @endif
</div>