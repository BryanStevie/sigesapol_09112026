@extends('frontend.layouts.master3')
@section('renderjs')

<script>
    $(document).ready(function() {  
        $("#seccion_de_responsable").hide();
        $("#seccion_de_apoderado").hide();
    });
    
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">

        <div class="col-sm-12 col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-12">  
                            <div class="col-md-8">
                                <h2 class="panel-title">HOSPITALIZACIÓN - {{$admision->ipress}}</h2>
                            </div>  
                        </div>
                    </div>
                </div>
                @if(count($admision)>0)
                <div class="panel-body" style=" font-size: 13px">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="{{url('/')}}/images/logo_sanidad.png" width="70px">
                        </div>
                        <div class="col-md-6" style="text-align: center">
                            <h4>{{$admision->ipress}}</h4>                            
                        </div>
                        <div class="col-md-3">
                            <img src="{{url('/')}}/images/logo_sanidad.png" width="70px" class="pull-right">
                        </div>
                    </div>
                    <input type="hidden" name="edad" id="edad" class="form-control text-uppercase" readonly=""value="{{$admision->edad}}">
  
                    <div class="row">
                        <div class="col-md-12">  
                            <div class="col-md-8">
                                <p><b style="font-size: 16px;">DATOS DEL PACIENTE</b></p>
                            </div>
                        </div> 
                    </div>  
                    
                    <div class="row">
                    <div class="col-md-12">
                    <div class="col-md-2">
                        <label>BENEFICIARIO</label>
                        <p>{{$admision->tipo_beneficiario}}</p>
                    </div>

                    <div class="col-md-2">
                        <label>N° HOSPITALIZACION</label>
                        <p>{{$admision->nro_hospitalizacion}}</p>
                    </div>
 

                    <div class="col-md-4">
                        <label>{{$admision->tipo_doc_ident}} - PACIENTE</label>
                        <p>
                            {{$admision->dni_beneficiario." - ".$admision->nombre_beneficiario." ".$admision->paterno_beneficiario." ".$admision->materno_beneficiario}}
                        </p>
                    </div>
                    <div class="col-md-2">
                        <label>F.NACIMIENTO</label>
                        <p>{{$admision->fecha_nacimiento_beneficiario}}</p>
                    </div>
                    <div class="col-md-2">
                        <label>EDAD</label>
                        <p>{{$admision->fecha_nac_descripcion}}</p>
                    </div>
                    </div> <!-- .col-md-12 -->
                    </div> <!-- .row -->

                    {{-- <div class="row">
                        <div class="col-md-12">

                            <div class="col-md-2">
                                <label>ETNIA</label>
                                <p>{{$admision->etnia}}</p>
                            </div>
                            <div class="col-md-2">
                                <label>IDIOMA</label>
                                <p>{{$admision->idioma}}</p>
                            </div>

                            <div class="col-md-2">
                                <label>RELIGIÓN</label><br>
                                @if($admision->religion=='NO SABE / NO RESPONDE')
                                -
                                @else
                                {{$admision->religion}}
                                @endif
                            </div>


                            <div class="col-md-3">
                                <label>GRADO DE INSTRUCCIÓN</label><br>
                                @if($admision->grado_instruccion=='NO SABE / NO RESPONDE')
                                -
                                @else
                                {{$admision->grado_instruccion}}
                                @endif
                            </div>

                            <div class="col-md-3">
                                <label>OCUPACIÓN</label><br>
                                @if($admision->ocupacion=='NO SABE / NO RESPONDE')
                                -
                                @else
                                {{$admision->ocupacion}}
                                @endif
                            </div>
                        </div>
                    </div> --}}
                    
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <label>SEXO</label>
                                    <p>@if($admision->sexo=='F') FEMENINO @else MASCULINO @endif</p>
                                </div>
                                <div class="col-md-2">
                                    <label>ESTADO CIVIL</label>
                                    <p>{{$admision->estado_civil}}</p>
                                </div>
                                <div class="col-md-2">
                                    <label>ETNIA</label>
                                    <p>{{$admision->etnia}}</p>
                                </div>
                                {{-- <div class="col-md-2">
                                    <label>IDIOMA</label>
                                    <p>{{$admision->idioma}}</p>
                                </div> --}}
                                <div class="col-md-2">
                                    <label>RELIGIÓN</label>
                                    <p>{{$admision->religion}}</p>
                                </div>
                                <div class="col-md-2">
                                    <label>GRADO DE INSTRUCCIÓN</label><br>
                                    <p>{{$admision->grado_instruccion}}</p>
                                </div>
                                <div class="col-md-2">
                                    <label>OCUPACIÓN</label><br>
                                    <p>{{$admision->ocupacion}}</p>
                                </div>
                                @if($admision->tipo_beneficiario =='TITULAR')
                                <div class="col-md-2">
                                    <label>GRADO</label>
                                    <p>{{$admision->grado}}</p>
                                </div>

                                @endif
                            </div> 
                        </div>

                    <div class="row">
                    <div class="col-md-12">
                    <div class="col-md-12">
                        <p><b>DIRECCIÓN ACTUAL: </b><br>{{$admision->domicilio_actual}}
                            <b>({{$admision->departamento_actual}} - {{$admision->provincia_actual}} - {{$admision->distrito_actual}})</b>
                        </p>
                    </div>
                    </div> <!-- .col-md-12 -->
                    </div> <!-- .row -->


                    <div class="col-md-12">
                        <p><b>DIRECCIÓN DE NACIMIENTO: </b><br>{{$admision->direccion_nacimiento}}
                            <b>({{$admision->departamento_nacimiento}} - {{$admision->provincia_nacimiento}} - {{$admision->distrito_nacimiento}})</b>
                        </p>
                    </div>


                    <div class="col-md-12">
                        <p><b>DIRECCIÓN DE PROCEDENCIA: </b><br>{{$admision->direccion_procedencia}}
                            <b>({{$admision->departamento_procedencia}} - {{$admision->provincia_procedencia}} - {{$admision->distrito_procedencia}})</b>
                        </p>
                    </div>


                    {{-- <div id="seccion_de_responsable" name="seccion_de_responsable">
                        <div class="col-md-12">
                            <hr>
                            <p><b style="font-size: 16px;">DATOS DEL RESPONSABLE</b></p>
                        </div>

                        <div class="col-md-12">
                            <label>PARENTESCO: </label> 
                            {{$admision->tipo_parentesco_responsable}}
                            
                            <label  style="margin-left: 35px;">{{$admision->tipo_documento_responsable}} - NOMBRE Y APELLIDOS: </label>
                            {{$admision->numero_documento_responsable}} - {{$admision->nombre_responsable}}
                       
                            @if($admision->observacion_responsable!='')
                                <label style="margin-left: 35px;">OBSERVACIÓN: </label>
                                {{$admision->observacion_responsable}}
                            @endif

                            @if($admision->telefono_responsable!='')
                                <label style="margin-left: 35px;">CONTACTO: </label>
                                {{$admision->telefono_responsable}}
                            @endif 
                             
                             
                            @if($admision->observacion_responsable == null && $admision->telefono_responsable == null)
                                <label style="margin-left: 35px;">DIRECCCIÓN: </label>
                            @else  
                                <br>
                                <label>DIRECCCIÓN: </label>
                            @endif  
                        
                            @if($admision->codigo_ubig_responsable != null) 
                                {{$admision->direccion_responsable}} -
                                    <b>({{$admision->codigo_ubig_responsable}} |
                                        {{$admision->departamento_responsable}} -
                                        {{$admision->pronvincia_responsable}} -
                                        {{$admision->distrito_responsable}})</b>
                            @endif
                        </div>
                    </div> --}}


 
                    {{-- <div id="seccion_de_apoderado" name="seccion_de_apoderado">
                        <div class="col-md-12">
                            <hr>
                            <p><b style="font-size: 16px;">DATOS DE MADRE, PADRE O APODERADO</b></p>
                        </div>

                        <div class="col-md-12">
                            <label>PRENTESCO: </label>
                            {{$admision->tipo_de_apoderado}} 

                            <label style="margin-left: 35px;">CONTACTO: </label>
                            {{$admision->telefono_apoderado}}

                            <label style="margin-left: 35px;">{{$admision->tipo_documento_apoderado}}: </label>
                            {{$admision->numero_documento_apoderado}}

                            <label style="margin-left: 35px;">NOMBRE Y APELLIDOS: </label>
                            {{$admision->nombre_apoderado}} 

                            @if($admision->codigo_ubig_apoderado != null) 
                                <p><b>DIRECCCIÓN: </b> {{$admision->direccion_apoderado}} -
                                    <b>({{$admision->codigo_ubig_apoderado}} |
                                        {{$admision->departamento_apoderado}} - {{$admision->pronvincia_apoderado}} -
                                        {{$admision->distrito_apoderado}})</b>
                                </p>
                            @endif
                        </div>
                    </div> --}}



                    <hr>
                    <div class="row">
                    <div class="col-md-12">
                    <div class="col-md-12">
                        
                        <p><b style="font-size: 16px;">DATOS DE INGRESO A LA HOSPITALIZACIÓN</b></p>
                    </div>
                    </div>
                    </div> <!-- .row -->

                    <div class="row">
                    <div class="col-md-12">
                    <div class="col-md-3">
                        <label style="color: red;">COD.SITEDS: {{$admision->cod_siteds_susalud}}  </label> 
                    </div> 
                    </div> <!-- .col-md-12 -->
                    </div> <!-- .row -->

                    <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                        <label>REGISTRADO POR: </label>
                        <p>{{$admision->nombre_registrador}}</p>
                        </div>

                        <div class="col-md-3">
                        <label>FEC. INGRESO: </label>
                        <p>{{$admision->fecha_ingreso}} ({{$admision->hora_ingreso}})</p>
                        </div>
                        
                        <div class="col-md-3">
                        <label>FEC. REGISTRO: </label>
                        <p>{{$admision->fecha_creacion}} ({{$admision->hora_creacion}})</p>
                        </div>

                        <div class="col-md-3">
                        <label>H.C: </label>
                        <p>{{$admision->nro_historia}}</p>
                        </div>
                        
                        <div class="col-md-3">
                        <label>ESTADO: </label> 
                            <p>@if($id_user_consultorio!=17)
                            <?php  
                            $fecha_salida = $admision->fecha_salida;
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
                    <!--</div>  .col-md-12 -->
                    <!--</div>  .row -->

                    <!-- <div class="row"> -->
                    <!-- <div class="col-md-12">  -->
                        <div class="col-md-3">
                        <label>ORIGEN: </label>
                        <p>{{$admision->tipo_origen}}</p>
                        </div>
<!-- ==================================>>>>>>>>>>>> AQUI ME QUEDE, CON EL FORMATO -->
                        <div class="col-md-3">
                        <label>PABELLON: </label>
                        <p>{{$admision->pabellon}}</p>
                        </div>

                    </div> <!-- .col-md-12 -->
                    </div> <!-- .row -->

                    <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-3">
                        <label>PISO: </label>
                        <p>{{$admision->piso}}</p>
                        </div>

                        <div class="col-md-3">
                        <label>SERVICIO: </label>
                        <p>{{$admision->consultorio_hospitalizacion}}</p>
                        </div>
                            
                        <div class="col-md-2">
                        <label>CUARTO: </label>
                        <p>{{$admision->cuarto}}</p>
                        </div>
                            
                        <div class="col-md-2">
                        <label>N° CAMA: </label>
                        <p>{{$admision->cama}}</p>
                        </div>
                    </div> <!-- .col-md-12 -->
                    </div> <!-- .row -->



                    <hr>
                    <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-12">
                        
                        <p><b style="font-size: 16px;">PROFESIONAL QUE ORDENA LA HOSPITALIZACIÓN</b></p>
                        </div>
                    </div>
                    </div> <!-- .row -->

                    <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-2">
                        <label>N° DOCUMENTO: </label>
                        <p>{{$admision->dni_medico}}</p>
                        </div>
                        
                        <div class="col-md-3">
                        <label>MÉDICO: </label>
                        <p>{{$admision->paterno_medico}} {{$admision->materno_medico}} {{$admision->nombre_medico}}</p>
                        </div>
                            
                        <div class="col-md-3">
                        <label>PROFESION: </label>
                        <p>{{$admision->profesion}}</p>
                        </div>

                        <div class="col-md-2">
                        <label>CMP: </label>
                        <p>{{$admision->colegiatura_medico}}</p>
                        </div>
                        
                        <div class="col-md-2">
                        <label>RNE: </label>
                        <p>{{$admision->rne_medico}}</p>
                        </div>
                    </div>
                    </div> <!-- .row -->

                    


                    {{-- @if ($admision->es_referido=='1')
                    <div id="seccion_de_referencia" name="seccion_de_referencia">
                        <div class="col-md-12">
                            <hr>
                            <p><b style="font-size: 16px;">DATOS DE REFERENCIA</b></p>
                        </div>

                        <div class="col-md-12">
                            <label>IPRESS PNP:</label>
                            @if ($admision->es_pnp=='1')
                            SI
                            @else
                            NO
                            @endif 
                            <label style="margin-left: 35px;">NÚMERO: </label>
                            {{$admision->numero_de_referencia}}  
                            
                            <label style="margin-left: 35px;">FECHA: </label>
                            {{$admision->fecha_de_referencia}} 
                            
                            <label style="margin-left: 35px;">IPRESS DE ORIGEN: </label>
                            {{$admision->descripcion_ipress_origen_referencia}} 
                        </div>
                    </div>
                    @endif --}}


                    


                    <div class="col-md-12">
                        <hr>
                        <p><b style="font-size: 16px;">DIAGNÓSTICOS</b></p>
                    </div>


                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="example" class="table table-condensed table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>CIE10</th>
                                        <th>DESCRIPCIÓN</th>
                                        <th style="text-align: center;">TIPO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($admision->codigo_dx_1 != null)
                                    <tr>
                                        <td>{{$admision->codigo_dx_1}}</td>
                                        <td>{{$admision->descripcion_dx_1}}</td>
                                        <td style="text-align: center;">DEFINITIVO</td>
                                    </tr>
                                    @endif
                                    @if($admision->codigo_dx_2 != null)
                                    <tr>
                                        <td>{{$admision->codigo_dx_2}}</td>
                                        <td>{{$admision->descripcion_dx_2}}</td>
                                        <td style="text-align: center;">DEFINITIVO</td>
                                    </tr>
                                    @endif
                                    @if($admision->codigo_dx_3 != null)
                                    <tr>
                                        <td>{{$admision->codigo_dx_3}}</td>
                                        <td>{{$admision->descripcion_dx_3}}</td>
                                        <td style="text-align: center;">DEFINITIVO</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>


                    

                    {{-- <div class="col-md-12">
                        <hr>
                        <p><b style="font-size: 16px;">DATOS DEL ACOMPAÑANATE</b></p>
                    </div>

                    <div class="col-md-12">
                        <label>ACOMPAÑADO POR: </label>
                        {{$admision->tipo_acompaniante}}
                              
                        @if($admision->tipo_acompaniante!='SOLO')
                          <label style="margin-left: 35px;">{{$admision->tipo_documento_acompaniante}} - ACOMPAÑANTE: </label>
                          {{$admision->numero_documento_acompaniante}} - {{$admision->nombre_acompaniante}}
                          
                          @if($admision->observacion_acompaniante!='')
                              <label style="margin-left: 35px;">OBSERVACIÓN: </label>
                              {{$admision->observacion_acompaniante}}
                          @endif
                          
                          @if($admision->telefono_acompaniante!='')
                              <label style="margin-left: 35px;">CONTACTO: </label>
                              {{$admision->telefono_acompaniante}}
                          @endif  

                          @if($admision->observacion_acompaniante == '' && $admision->telefono_acompaniante == '')
                              <label style="margin-left: 35px;">DIRECCCIÓN: </label>
                          @else  
                              <br>
                              <label>DIRECCCIÓN: </label>
                          @endif  

                          {{$admision->direccion_acompaniante}} -
                          <b>({{$admision->codigo_ubig_acompaniante}} | {{$admision->departamento_acompaniante}} - 
                              {{$admision->pronvincia_acompaniante}}- {{$admision->distrito_acompaniante}})</b>
                            
                        @endif
                        
                    </div> --}}



                    <div class="col-md-12">
                        <hr>
                    </div>


                    @if($admision->estado==0)
                    <div class="col-md-12" style="">
                        Receta Invalidada
                    </div>
                    @else

                    <div class="col-md-12" style="">
                        <div class="col-lg-offset-8 col-md-offset-6 col-lg-2 col-md-3">
                            <a id="btnReg" class="form-control btn btn-success"
                                href="/all_pendientes_hospitalizacion"><span class="glyphicon glyphicon-list"> LISTADO</a>
                        </div>
                        <!-- <div class="col-md-4">
                            <a id="btnReg" class="form-control btn btn-primary"
                                href="/all_hospitalizado_by_establecimiento">HOSPITALIZADOS</a>
                        </div> -->
                        <div class="col-lg-2 col-md-3">
                            <a href="/PdfHospitalizacionAdmision/{{$admision->id}}" class="form-control btn btn-danger" target="_blank"><i class="fa fa-file-pdf-o"></i> Imprimir</a>
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