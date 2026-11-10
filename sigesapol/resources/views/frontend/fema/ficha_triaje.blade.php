<div id="blur-container">
@extends('frontend.layouts.master')
@section('renderjs')
<script src='funciones/function.js'></script>
<script src="{{asset('js/jquery.inputmask.bundle.min.js')}}"></script>
<script>

$( "#guarda" ).click(function() {

/****************************/
    // Validar información de signos vitales
    var value = '';

    value = $('#temperatura').val()
    if (value == "") {
      bootbox.alert('Falta registrar temperatura');
      $('#temperatura').focus();
      return 0;
    }else{
        if (!Validar.esVacio(value) && !Validar.decimalEnRango(value, 35.0, 40.0)) {
            //alert('La temperatura debe estar entre 35 a 40');
            bootbox.alert('La temperatura debe estar entre 35 a 40');
            return 0;
        }
    }

    value = $('#talla').val()
    if (value == "") {
      bootbox.alert('Falta registrar talla');
      $('#talla').focus();
      return 0;
    }
    else{
        if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 132, 200)) {
          bootbox.alert('La talla debe estar entre 132 a 200');
          return 0;
        }    
    }
    

    value = $('#peso').val()
    if (value == "") {
        bootbox.alert('Falta registrar el peso');
        $('#peso').focus();
        return 0;
    }
    else{
        if (!Validar.esVacio(value) && !Validar.decimalEnRango(value, 30.0, 180.0)) {
          bootbox.alert('El peso debe estar entre 30 a 180');
          return 0;
        }    
    }

    value = $('#sa02').val()
    if (value == "") {
        bootbox.alert('Falta registrar la saturación');
        $('#sa02').focus();
        return 0;
    }
    else{
        if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 60, 100)) {
          bootbox.alert('La Sa02 debe estar entre 60 a 100');
          return 0;
        }
    }

    value = $('#presionarteriax').val()
    if (value == "") {
        bootbox.alert('Falta registrar la presión arterial X');
        $('#presionarteriax').focus();
        return 0;
    }
    else{
        if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 10, 250)) {
          bootbox.alert('La presión arterial debe estar entre 10 a 250');
          return 0;
        }
    }

    value = $('#presionarteriay').val()
    if (value == "") {
        bootbox.alert('Falta registrar la presión arterial Y');
        $('#presionarteriay').focus();
        return 0;
    }
    else{
        if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 50, 140)) {
            bootbox.alert('La presión arterial debe estar entre 50 a 140');
            return 0;
        }
    }

    value = $('#frecrespiratoria').val()
    if (value == "") {
        bootbox.alert('Falta registrar la frecuencia respiratoria');
        $('#frecrespiratoria').focus();
        return 0;
    }
    else{
        if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 0, 60)) {
            bootbox.alert('La frecuencia respiratoria debe estar entre 0 a 60');
            return 0;
        }
    }

    value = $('#freccardiaca').val()
    if (value == "") {
        bootbox.alert('Falta registrar la frecuencia cardiaca');
        $('#freccardiaca').focus();
        return 0;
    }
    else{
        if (!Validar.esVacio(value) && !Validar.enteroEnRango(value, 0, 170)) {
          bootbox.alert('La frecuencia cardiaca debe estar entre 0 a 170');
          return 0;
        }
    }

    
    //
/****************************/

Swal.fire({
  position: 'top-end',
  icon: 'success',
  title: 'Se guardo correctamente',
  showConfirmButton: false,
  timer: 2000,
  didOpen: () => {
    Swal.showLoading()
  },
  willClose: () => {
    $('#frm').submit();
  }
})

});

    $("#celular").inputmask("decimal", {
        digits: 0,
        placeholder: "",
        allowMinus: false,
        allowPlus: false,
        max: 999999999,
        digitsOptional: false
    });

    $("#temperatura").inputmask("decimal", {
        digits: 2,
        placeholder: "0.00",
        allowMinus: false,
        allowPlus: false,
        max: 50,
        digitsOptional: false
    });

    $("#talla").inputmask("decimal", {
        digits: 2,
        placeholder: "0.00",
        allowMinus: false,
        allowPlus: false,
        max: 225,
        digitsOptional: false
    });

    $("#peso").inputmask("decimal", {
        digits: 2,
        placeholder: "0.00",
        allowMinus: false,
        allowPlus: false,
        max: 250,
        digitsOptional: false
    });
    
    $("#presionarteria").inputmask("decimal", {
        digits: 0,
        placeholder: "0",
        allowMinus: false,
        allowPlus: false,
        max: 250,
        digitsOptional: false
    });

    $("#frecrespiratoria").inputmask("decimal", {
        digits: 0,
        placeholder: "0",
        allowMinus: false,
        allowPlus: false,
        max: 350,
        digitsOptional: false
    });

    $("#freccardiaca").inputmask("decimal", {
        digits: 0,
        placeholder: "0",
        allowMinus: false,
        allowPlus: false,
        max: 350,
        digitsOptional: false
    });
    
    $("#freccardiaca").inputmask("decimal", {
        digits: 0,
        placeholder: "0",
        allowMinus: false,
        allowPlus: false,
        max: 350,
        digitsOptional: false
    });

    $("#sa02").inputmask("decimal", {
        digits: 0,
        placeholder: "0",
        allowMinus: false,
        allowPlus: false,
        max: 350,
        digitsOptional: false
    });

    $("#presionarteriax").inputmask("decimal", {
        digits: 0,
        placeholder: "0",
        allowMinus: false,
        allowPlus: false,
        max: 350,
        digitsOptional: false
    });

    $("#presionarteriay").inputmask("decimal", {
        digits: 0,
        placeholder: "0",
        allowMinus: false,
        allowPlus: false,
        max: 350,
        digitsOptional: false
    });

    function mostrarimc(){

        a=document.getElementById("peso").value;
        b=document.getElementById("talla").value;

            imc= a/((b/100)*(b/100))
            
            i = Math.round(imc)

            if(imc-i>0){
                f=(i-imc)*-1000
                g=(i*1000)+f
                g = Math.round(g)/1000
           
            }else{
                f=((i-1)-imc)*-1000
                g=((i-1)*1000)+f
                g = Math.round(g)/1000
            }

            if((a/=0) && (b/=0)){

                if(imc<18.5){

                    document.getElementById("mostrardatoimc").innerHTML=g;
                    
                }else if(imc<25){

                    document.getElementById("mostrardatoimc").innerHTML=g;
                    document.getElementById("mostrarindiceimc").innerHTML="NORMAL";
                    
                }else if(imc<30){

                    document.getElementById("mostrardatoimc").innerHTML=g;
                    document.getElementById("mostrarindiceimc").innerHTML="SOBRE PESO";
                    
                }else if(imc<35){

                    document.getElementById("mostrardatoimc").innerHTML=g;
                    document.getElementById("mostrarindiceimc").innerHTML="OBESIDAD GRADO I";
                    
                }else if(imc<40){

                    document.getElementById("mostrardatoimc").innerHTML=g;
                    document.getElementById("mostrarindiceimc").innerHTML="OBESIDAD GRADO II";
                    
                }else if(imc<50){

                    document.getElementById("mostrardatoimc").innerHTML=g;
                    document.getElementById("mostrarindiceimc").innerHTML="OBESIDAD GRADO III";
                    
                }else{
                    document.getElementById("mostrarindiceimc").innerHTML="";
                }
            }
     }
    
</script>

@endsection
@section('content')

<form id="frm" method="post"  action="{{ route('ficha_fema_triaje')}}" >  
  <input type="hidden" name="_token" value="{{csrf_token()}}">
  <input type="hidden" name="nro_doc_ident" value="{{$personal->nro_doc_ident}}">
  <input type="hidden" name="id_prestacion" value="@if(isset($personal)) {{$personal->id}} @endif">
  <input type="hidden" name="dni" value="{{$profesional->dni}}">
  <input type="hidden" name="id_profesional" value="{{$profesional->id}}">
    <div class="row ">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">TRIAJE DE FICHA MEDICA - PNP</h3>
                </div>
                <div class="panel-body">
                    <div class="panel panel-info ">
                        <div class="panel-body ">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group text-center">
                                        <label for="">FICHA MEDICA  N°  @if(isset($establecimiento)) {{$establecimiento->codigo}} @endif - @if(isset($procesos)) {{$procesos->abreviatura}} @endif  - @if(isset($personal)) {{$personal->id}} @endif - {{$anio}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">DATOS DE REGISTROS</h3>
                        </div>
                        <div class="panel-body "> <!-- .col-md-12 -->
                            <div class="row">
                                <div class="col-md-4">
                                    <!-- <br> -->
                                    <div class="form-group">
                                        <label for="">TIPO PROCESO</label>
                                        <input value="@if(isset($procesos)) {{$procesos->nombre}} @endif" type="text" name="hora_registro_general" id="hora_registro_general"
                                            class="form-control" readonly="" tabindex="" placeholder="TIPO PROCESO">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!-- <br> -->
                                    <div class="form-group">
                                        <label for="">SUB PROCESO</label>
                                        <input value="@if(isset($subprocesos)) {{$subprocesos->nombre}} @endif" type="text" name="nombre_ipress_general" id="nombre_ipress_general"
                                            class="form-control" readonly="" tabindex="" placeholder="SUB PROCESO">    
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!-- <br> -->
                                    <div class="form-group">
                                        <label for="">FECHA DE ATENCIÓN DE TRIAJE</label>
                                        <input value="@if(isset($date)) {{$date}} @endif" type="text" name="tipo_proceso" id="tipo_proceso" class="form-control"
                                            readonly="" tabindex="" placeholder="FECHA ATENCION">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">ESTABLECIMIENTO</label>
                                    <input value="@if(isset($establecimiento)) {{$establecimiento->nombre}} @endif " type="text" name="establecimiento" id="establecimiento" class="form-control" readonly="" tabindex="" placeholder="NOMBRE ESTABLECIMIENTO">
                                    <!-- <br> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">INFORMACIÓN PERSONAL</h3>
                        </div>
                        <div class="panel-body "> <!-- .col-md-12 -->
                            <div class="col-md-9">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">TIPO DOCUMENTO</label>
                                            <input value="DNI" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">N° DOCUMENTO</label>
                                            <input value="@if(isset($personal)) {{$personal->nro_doc_ident}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">N° CIP</label>
                                            <input value="@if(isset($personal)) {{$personal->carnet}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">APE PATERNO</label>
                                            <input value="@if(isset($personal)) {{$personal->paterno}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">APE MATERNO</label>
                                            <input value="@if(isset($personal)) {{$personal->materno}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">NOMBRES</label>
                                            <input value="@if(isset($personal)) {{$personal->nombre}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">SITUACIÓN PNP</label>
                                            <input value="@if(isset($situacion)) {{$situacion->descripcion}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">GRADO</label>
                                            <input value="@if(isset($personal)) {{$personal->grado}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                           <label for="">UNIDAD</label>
                                            <input value="@if(isset($personal)) {{$personal->unidades_pnp}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">SEXO</label>
                                            <input value="@if(isset($personal)) @if($personal->sexo=='M') MASCULINO @else FEMENINO @endif @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">FECHA NACIMIENTO</label>
                                            <input value="@if(isset($personal)) {{$personal->fecha_nac}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">ESTADO CIVIL</label>
                                            <input value="@if(isset($personal)) {{$personal->estado_civil}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                   
                                </div>
                            </div>
                            <div class="col-md-12">

                                    <!--div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">TIPO TRABAJO</label>
                                            <input value="" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">DOMICILIO</label>
                                            <input value="@if(isset($personal)) {{$personal->domicilio}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">RAZA</label>
                                            <select name="raza" id="raza" class="form-control">
                                                <option value="0"> -Seleccionar- </option>
                                                @foreach($razas as $r)
                                                <option value="{{$r->id}}">{{$r->nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">RESIDENCIA</label>
                                            <input value="" type="text" name="alojamiento" id="alojamiento" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">OCUPACIÓN</label>
                                            <select name="ocupacion" id="ocupacion" class="form-control">
                                                <option value="0"> -Seleccionar- </option>
                                                @foreach($profesiones as $p)
                                                <option value="{{$p->id}}">{{$p->nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">N° HISTORIA CLINICA DE IPRESS</label>
                                            <input value="@if(isset($historiaasegurado)) {{$historiaasegurado->nro_historia}} @endif" type="text" name="numero_historia_ipress" id="numero_historia_ipress" class="form-control" placeholder="N° HISTORIA" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">N° HISTORIA</label>
                                            <input value="" type="text" name="numhis" id="numhis" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">CELULAR</label>
                                            <input value="" type="text" name="celular" id="celular" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                              
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">INFORMACIÓN DEL RESPONSABLE DE REGISTRO</h3>
                        </div>
                        <div class="panel-body ">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="" >TIPO DOCUMENTO</label>
                                        <input value="DNI" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="" >N° DOCUMENTO</label>
                                        <input value="@if(isset($profesional)) {{$profesional->dni}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="" >APE PATERNO</label>
                                        <input value="@if(isset($profesional)) {{$profesional->paterno}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="" >APE MATERNO</label>
                                        <input value="@if(isset($profesional)) {{$profesional->materno}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="" >NOMBRES</label>
                                        <input value="@if(isset($profesional)) {{$profesional->name}} @endif" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="" >N° COLEGIATURA</label>
                                      
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!--    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        VACUNAS COVID-19 <button class="btn btn-sm btn-success">DETALLE</button>
                                    </div>
                                </div>
                            </h3>
                        </div>
                    </div>  -->
                    <!-- TRIAJE -->
                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">INFORMACIÓN DE SIGNOS VITALES</h3>
                        </div>
                        <div class="panel-body "> <!-- .col-md-12 -->
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >TEMPERATURA</label>
                                       <input type="number" name="temperatura" id="temperatura" placeholder="temperatura C°" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >TALLA (cm)</label>
                                        <input type="number" onkeyup="mostrarimc('')" name="talla" id="talla" placeholder="talla (cm)" class="form-control" > 
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >PESO (KG)</label>
                                        <input type="number" onkeyup="mostrarimc('')" name="peso" id="peso" placeholder="peso (kg)" class="form-control" > 
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >IMC</label>
                                        <div class="form-control" readonly=""><div id="mostrardatoimc"></div></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" >DESCRIPCIÓN IMC</label>
                                       
                                        <div class="form-control" readonly=""> <div id="mostrarindiceimc"></div></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label><center>PRESIÓN ARTERIAL (MM/HG)</center></label></br>
                                        
                                        <div class="col-md-6"><input value="" type="number" name="presionarteriax" id="presionarteriax" placeholder="presion arterial" class="form-control"></div>
                                        <div class="col-md-6"><input value="" type="number" name="presionarteriay" id="presionarteriay" placeholder="presion arterial" class="form-control"></div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >FREC. RESPIRATORIA (EN UN MIN)</label>
                                        <input value="" type="number" name="frecrespiratoria" id="frecrespiratoria" placeholder="frec. respiratoria (por min)" class="form-control" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >FREC. CARDIACA (LPM/MIN)</label>
                                        <input value="" type="number" name="freccardiaca" id="freccardiaca" placeholder="frec. cardiaca (lpm/min)" class="form-control" placeholder="" tabindex="">  
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" >SaO2</label></br>
                                      
                                        <input value="" type="number" name="sa02" id="sa02" placeholder="sa02" class="form-control" placeholder="" tabindex="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">OBSERVACIONES</h3>
                        </div>
                        <div class="panel-body "> <!-- .col-md-12 -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input value="" type="text" name="observaciones_triaje" id="observaciones_triaje" class="form-control" placeholder="OBSERVACIONES" tabindex="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">ALERTA</h3>
                        </div>
                        <div class="panel-body ">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input value="" type="checkbox" name="check_alerta" id="check_alerta" class="" readonly="" tabindex=""> ALERTA
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <textarea name="alerta" id="alerta" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        
                            <input id="guarda" class="btn btn-primary pull-right" value="Registrar">
                        </div>
                    </div>
                    <!-- ***************************** -->
                </div> <!-- .panel-body -->
            </div> <!-- .panel -->
        </div> <!-- .col -->
    </div> <!-- .row -->
</div>
</form>
@include('frontend.includes.footer')
@endsection
