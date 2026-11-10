<title>Sistema de Farmacia</title>

<script src="{{ asset('js/jquery.inputmask.bundle.min.js') }}"></script>
<style>
    .mostrarimc {
  background-repeat: no-repeat;
  background-position: 10px 30px;
}
    </style>
<script>
       document.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' && event.target.tagName === 'INPUT') {
            event.preventDefault(); // Cancelar el comportamiento predeterminado del Enter en el input
            const form = event.target.form;
            const fields = Array.from(form.querySelectorAll('input'));
            const index = fields.indexOf(event.target);
            const nextField = fields[index + 1];

            if (nextField) {
                nextField.focus(); // Mover el foco al siguiente campo de entrada
            }
        }
    });
    compruebaIMC();
    $("#temperatura").inputmask("decimal", {
        digits: 2,
        placeholder: "0.00",
        allowMinus: false,
        allowPlus: false,
        max: 50,
        digitsOptional: false
    });

    $("#talla").inputmask("decimal", {
        digits: 0,
        placeholder: "0",
        allowMinus: false,
        allowPlus: false,
        max: 225,
        digitsOptional: false
    });

    $("#peso").inputmask("decimal", {
    radixPoint: ".",
    groupSeparator: "",
    autoGroup: false,
    digits: 2,
    allowMinus: false,
    allowPlus: false,
    placeholder: "0.00",
    max: 250
});
    $("#sa02").inputmask("decimal", {
        digits: 0,
        placeholder: "0",
        allowMinus: false,
        allowPlus: false,
        max: 100,
        digitsOptional: false
    });
    $("#imc").inputmask("decimal", {
        digits: 0,
        placeholder: "0",
        allowMinus: false,
        allowPlus: false,
        max: 100,
        digitsOptional: false
    });
   

    $("#frecuencia_respiratoria").inputmask("decimal", {
        digits: 0,
        placeholder: "0",
        allowMinus: false,
        allowPlus: false,
        max: 30,
        digitsOptional: false
    });

    $("#tamano_cintura").inputmask("decimal", {
        digits: 0,
        placeholder: "0",
        allowMinus: false,
        allowPlus: false,
        max: 250,
        digitsOptional: false
    });


    $("#frecuencia_cardiaca").inputmask("decimal", {
        digits: 0,
        placeholder: "0",
        allowMinus: false,
        allowPlus: false,
        max: 160,
        digitsOptional: false
    });

    $("#presion_arterial_x").inputmask("decimal", {
        digits: 0,
        placeholder: "0",
        allowMinus: false,
        allowPlus: false,
        max: 220,
        digitsOptional: false
    });

    $("#presion_arterial_y").inputmask("decimal", {
        digits: 0,
        placeholder: "0",
        allowMinus: false,
        allowPlus: false,
        max: 160,
        digitsOptional: false
    });

    $("#fi02").inputmask("decimal", {
        digits: 0,
        placeholder: "0",
        allowMinus: false,
        allowPlus: false,
        max: 100,
        digitsOptional: false
    });
    function cancelarEstadoAJAX() {

console.log($('#id_cita').val());
const ids = $('#id_cita').val();
$.ajax({
    dataType: 'json',
    type: 'POST',
    url: '/desaparecervalortvTriaje',
    data: {
        ids: ids
    },
    success: function(result) {

        console.log("funciono");
        // Realizar acciones adicionales después de cancelar el triaje
    },
    error: function(xhr, status, error) {
        console.log(error);
        // Manejar el error de la solicitud AJAX
    }
});
document.getElementById("countdown").innerHTML = "Cancelada";

}
    function cambiarprex() {

        a = document.getElementById("presion_arterial_x").value;
        b = document.getElementById("presion_arterial_y").value;

        if (a <= 100) {

            if (b == 0) {

                document.getElementById("mostrar_pre_art").innerHTML = 'LLENAR EL CAMPO Y';

            } else if (b < 60) {

                document.getElementById("mostrar_pre_art").innerHTML = 'BRADIPNEA';

            } else {
                document.getElementById("mostrar_pre_art").innerHTML = "";
            }

        } else if (a <= 120) {

            if (b == 0) {

                document.getElementById("mostrar_pre_art").innerHTML = 'LLENAR EL CAMPO Y';

            } else if (b < 60) {

                document.getElementById("mostrar_pre_art").innerHTML = ' ';

            } else if (b < 81) {

                document.getElementById("mostrar_pre_art").innerHTML = 'NORMOTENSION';

            } else {

                document.getElementById("mostrar_pre_art").innerHTML = ' ';

            }

        } else if (a <= 180) {

            if (b == 0) {

                document.getElementById("mostrar_pre_art").innerHTML = 'LLENAR EL CAMPO Y';

            } else if (b < 120) {

                document.getElementById("mostrar_pre_art").innerHTML = ' ';

            } else {

                document.getElementById("mostrar_pre_art").innerHTML = 'HIPERTENSION';

            }

        } else {

            document.getElementById("mostrar_pre_art").innerHTML = ' ';

        }

    }


    function cambiarFC(x) {

        a = document.getElementById("frecuencia_cardiaca").value;

        if (a < 60) {

            document.getElementById("mostrar_fre_car").innerHTML = 'BRADICARDIA';
            document.getElementById(x).style.background = "white";

        } else if (a < 101) {

            document.getElementById("mostrar_fre_car").innerHTML = 'NORMOCARDIA';
            document.getElementById(x).style.background = "#5cb85c";

        } else {

            document.getElementById("mostrar_fre_car").innerHTML = 'TAQUICARDIA';
            document.getElementById(x).style.background = "white";

        }

    }


    function cambiarSA(x) {

        a = document.getElementById("sa02").value;

        if (a < 87) {

            document.getElementById("mostrar_sa02").innerHTML = 'HIPOXIA SEVERA';
            document.getElementById(x).style.background = "#d9534f";

        } else if (a < 93) {

            document.getElementById("mostrar_sa02").innerHTML = 'HIPOXIA MODERADA';
            document.getElementById(x).style.background = "yellow";

        } else if (a < 95) {

            document.getElementById("mostrar_sa02").innerHTML = 'HIPOXIA LEVE';
            document.getElementById(x).style.background = "white";

        } else {

            document.getElementById("mostrar_sa02").innerHTML = 'NORMOSATURACION';
            document.getElementById(x).style.background = "#5cb85c";
        }

    }

    function cambiarFR(x) {

        a = document.getElementById(x).value

        if (a < 12) {

            if (document.getElementById(x).style.background == "5cb85c") {

                document.getElementById(x).style.background = "white";

            } else {

                document.getElementById(x).style.background = "white"

            }

            document.getElementById("mostrar_fre_respiratorio").innerHTML = 'BRADIPNEA';

        } else if (a < 20) {

            if (document.getElementById(x).style.background == "white") {

                document.getElementById(x).style.background = "#5cb85c";

            } else {

                document.getElementById(x).style.background = "#5cb85c"

            }

            document.getElementById("mostrar_fre_respiratorio").innerHTML = 'EUPNEA';


        } else {

            if (document.getElementById(x).style.background == "5cb85c") {

                document.getElementById(x).style.background = "white";

            } else {

                document.getElementById(x).style.background = "white"

            }

            document.getElementById("mostrar_fre_respiratorio").innerHTML = 'TAQUIPNEA';

        }

    }

    function compruebaIMC() {
        var talla = document.getElementById('talla').value;
        var peso = document.getElementById('peso').value;
        var imc = document.getElementById('imc').value;
            var alturaEnMetros = talla/ 100;;
            var pesoEnKilos = parseInt(document.getElementById('peso').value);

            //IMC
            var imcx = Math.round(pesoEnKilos / (alturaEnMetros * alturaEnMetros));
            //  document.imc.value = imcx;
            document.getElementById('imc').value = imcx;
            var clasificacion;
            if (imcx <= 18) {
                document.getElementById("clasificacion").innerHTML = "<span style='color: #ffffff;'>BAJO PESO</span>";
                document.getElementById("clasificacion").style.background = "#45d5d5";;
                

            } else if (imcx > 18 && imcx <= 24) {
                document.getElementById("clasificacion").innerHTML = ' PESO NORMAL';
                document.getElementById("clasificacion").style.background = "#bac851";

            } else if (imcx > 25 && imcx <= 29) {
                document.getElementById("clasificacion").innerHTML = 'SOBRE PESO';
                document.getElementById("clasificacion").style.background = "#efd158";

            } else if (imcx > 29 && imcx <=34) {
                document.getElementById("clasificacion").innerHTML = 'OBESIDAD TIPO 1';
                document.getElementById("clasificacion").style.background = "#edb366";
            } else if (imcx > 34 && imcx <=39) {
                document.getElementById("clasificacion").innerHTML = 'OBESIDAD TIPO 2';
                document.getElementById("clasificacion").style.background = "#f08234";
            } else if (imcx >40) {
                document.getElementById("clasificacion").innerHTML = "<span style='color: #ffffff;'>OBESIDAD TIPO 3</span>";
                document.getElementById("clasificacion").style.background = "#f08234";

            }
        }
    
    
    function cambiar(x) {

        a = document.getElementById(x).value

        if (a < 30) {
            /*blanco*/

            if (document.getElementById(x).style.background == "1E90FF") {

                document.getElementById(x).style.background = "white";

            } else if (document.getElementById(x).style.background == "green") {

                document.getElementById(x).style.background = "white";

            } else if (document.getElementById(x).style.background == "yellow") {

                document.getElementById(x).style.background = "white";

            } else if (document.getElementById(x).style.background == "orange") {

                document.getElementById(x).style.background = "white";

            } else if (document.getElementById(x).style.background == "#d9534f") {

                document.getElementById(x).style.background = "white";

            } else {

                document.getElementById(x).style.background = "white";

            }

            document.getElementById("mostrar_temperatura").innerHTML = '';

        } else if (a < 36) {
            /*azul*/

            if (document.getElementById(x).style.background == "white") {

                document.getElementById(x).style.background = "#1E90FF";

            } else if (document.getElementById(x).style.background == "green") {

                document.getElementById(x).style.background = "#1E90FF";

            } else if (document.getElementById(x).style.background == "yellow") {

                document.getElementById(x).style.background = "#1E90FF";

            } else if (document.getElementById(x).style.background == "orange") {

                document.getElementById(x).style.background = "#1E90FF";

            } else if (document.getElementById(x).style.background == "#d9534f") {

                document.getElementById(x).style.background = "#1E90FF";

            } else {

                document.getElementById(x).style.background = "#1E90FF";

            }

            document.getElementById("mostrar_temperatura").innerHTML = 'HIPOTERMIA';

        } else if (a <= 37.5) {
            /*verde*/

            if (document.getElementById(x).style.background == "white") {

                document.getElementById(x).style.background = "#5cb85c";

            } else if (document.getElementById(x).style.background == "#1E90FF") {

                document.getElementById(x).style.background = "#5cb85c";

            } else if (document.getElementById(x).style.background == "yellow") {

                document.getElementById(x).style.background = "#5cb85c";

            } else if (document.getElementById(x).style.background == "orange") {

                document.getElementById(x).style.background = "#5cb85c";

            } else if (document.getElementById(x).style.background == "#d9534f") {

                document.getElementById(x).style.background = "#5cb85c";

            } else {

                document.getElementById(x).style.background = "#5cb85c";

            }

            document.getElementById("mostrar_temperatura").innerHTML = 'NORMAL';

        } else if (a <= 39.5) {
            /*amarillo*/

            if (document.getElementById(x).style.background == "white") {

                document.getElementById(x).style.background = "yellow";

            } else if (document.getElementById(x).style.background == "#1E90FF") {

                document.getElementById(x).style.background = "yellow";

            } else if (document.getElementById(x).style.background == "#5cb85c") {

                document.getElementById(x).style.background = "yellow";

            } else if (document.getElementById(x).style.background == "orange") {

                document.getElementById(x).style.background = "yellow";

            } else if (document.getElementById(x).style.background == "#d9534f") {

                document.getElementById(x).style.background = "yellow";

            } else {

                document.getElementById(x).style.background = "yellow";

            }

            document.getElementById("mostrar_temperatura").innerHTML = 'FIEBRE';

        } else if (a <= 41) {
            /*naranja*/

            if (document.getElementById(x).style.background == "white") {

                document.getElementById(x).style.background = "orange";

            } else if (document.getElementById(x).style.background == "#1E90FF") {

                document.getElementById(x).style.background = "orange";

            } else if (document.getElementById(x).style.background == "#5cb85c") {

                document.getElementById(x).style.background = "orange";

            } else if (document.getElementById(x).style.background == "yellow") {

                document.getElementById(x).style.background = "orange";

            } else if (document.getElementById(x).style.background == "#d9534f") {

                document.getElementById(x).style.background = "orange";

            } else {

                document.getElementById(x).style.background = "orange";

            }

            document.getElementById("mostrar_temperatura").innerHTML = 'FIEBRE ALTA';

        } else if (a <= 45) {
            /*rojo*/

            if (document.getElementById(x).style.background == "white") {

                document.getElementById(x).style.background = "#d9534f";
                /*azul*/
            } else if (document.getElementById(x).style.background == "#1E90FF") {

                document.getElementById(x).style.background = "#d9534f";

            } else if (document.getElementById(x).style.background == "#5cb85c") {

                document.getElementById(x).style.background = "#d9534f";

            } else if (document.getElementById(x).style.background == "yellow") {

                document.getElementById(x).style.background = "#d9534f";

            } else if (document.getElementById(x).style.background == "orange") {

                document.getElementById(x).style.background = "#d9534f";

            } else {

                document.getElementById(x).style.background = "#d9534f";

            }

            document.getElementById("mostrar_temperatura").innerHTML = 'HIPERTERMIA';

        } else {
            /*blanco*/

            /*rojo*/
            if (document.getElementById(x).style.background == "#d9534f") {

                document.getElementById(x).style.background = "white";

            } else if (document.getElementById(x).style.background == "#1E90FF") {

                document.getElementById(x).style.background = "white";
                /*verde*/
            } else if (document.getElementById(x).style.background == "#5cb85c") {

                document.getElementById(x).style.background = "white";

            } else if (document.getElementById(x).style.background == "yellow") {

                document.getElementById(x).style.background = "white";

            } else if (document.getElementById(x).style.background == "orange") {

                document.getElementById(x).style.background = "white";

            } else {

                document.getElementById(x).style.background = "white";

            }

            document.getElementById("mostrar_temperatura").innerHTML = '';

        }

    }
     
        $('#registrarModalTriaje').click(function (e) {
            e.preventDefault(); 
            var token=$('#_token').val();
            var route='{{url('triaje_usuario')}}';
  
            var formData = new FormData($("#FormularioModalTriaje") [0]);
            $.ajax({
                data: formData,
                url:route,
                contentType: false,
                processData: false,
                headers: {'X-CSRF-TOKEN':token},
                type:'POST',
                enctype: 'multipart/form-data',
                dataType:'JSON',  
                success: function(data){
                    if(data!=null){
                        $('#modalForm2').modal('hide');
                        fn_ListarBusqueda();
                    }
                }
            });  
    }); 



    
</script>


<div class="panel-heading">
    <h3>SIGNOS VITALES</h3>
</div>

<!-- 
    if(data==1){
                        $('#FormularioModalTriaje').trigger("reset");    
                    }  
<form id="" class="form-horizontal" role="form" method="post" action="{{ route('triaje_usuario') }}">
-->

<div class="tab-content">
    <form id="FormularioModalTriaje" name="FormularioModalTriaje" enctype="multipart/form-data" method="POST" > 
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" id="id_cita" name="id_cita" value="{{ $id_cita }}">
        <input type="hidden" name="id_prestacion" value="@if (isset($cita)) {{ $cita->id }} @endif">
        <div role="tabpanel" class="tab-pane active" id="profile">
            <table class="table table-striped table-hover table-bordered dashboard-table">

                <tr>
                    <th>TEMPERATURA</th>
                    <td><input type="text" name="temperatura" id="temperatura" onkeyup="cambiar('temperatura')"
                            placeholder="temperatura C° " class="form-control"
                            value="@if (isset($cita)) {{ $cita->temperatura }} @endif"></td>
                    <th>
                        <div id="mostrar_temperatura" align="center"></div>
                    </th>
                </tr>

                <tr>
                    <th>TALLA</th>
                    <td><input type="text" name="talla" id="talla" placeholder=" talla cm" class="form-control"
                            onkeyup="compruebaIMC()" onkeypress="return esEntero(event);"
                            value="@if (isset($cita)) {{ $cita->talla_cm }} @endif"></td>
                    <th></th>
                </tr>
                <tr>
                    <th>PESO</th>
                    <td>
                        <input type="text" name="peso" id="peso" placeholder="Peso Kg" class="form-control"
                               onkeyup="compruebaIMC()" step="0.01"
                               value="@if (isset($cita)) {{ $cita->peso_kg }} @endif">
                    </td>
                    <th></th>
                </tr>
                <tr>

                <tr>
                    <th>IMC</th>
                    <td><input type="text" name="imc" id="imc"placeholder="IMC"
                            class="form-control "readonly="IMC"></td>
                    <th>
                        <div id="clasificacion" align="center">
                    </th>
                    <th></th>
                </tr>
                <th>Sa02</th>
                <td><input type="text" name="sa02" id="sa02" onkeyup="cambiarSA('sa02')" placeholder=" % "
                        class="form-control"
                        value="@if (isset($cita)) {{ $cita->sa02_porcentaje }} @endif"></td>
                <th>
                    <div id="mostrar_sa02" align="center">
                </th>
                </tr>

                <tr>
                    <th>TAMAÑO DE CINTURA</th>
                    <td><input type="text" name="tamano_cintura" id="tamano_cintura" placeholder=" tamaño cm "
                            class="form-control"
                            value="@if (isset($cita)) {{ $cita->tamano_cintura_cm }} @endif"></td>
                    <th></th>
                </tr>

                <tr>
                    <th>PRESION ARTERIAL</th>
                    <td>
                        <input type="text" name="presion_arterial_x" id="presion_arterial_x" onkeyup="cambiarprex()"
                            maxlength="" placeholder=" mm " class="form-control"
                            value="@if (isset($cita)) {{ $cita->presion_arterial_x }} @endif">
                        <br>
                        <input type="text" name="presion_arterial_y" id="presion_arterial_y" onkeyup="cambiarprex()"
                            placeholder=" Hg " maxlength="" class="form-control"
                            value="@if (isset($cita)) {{ $cita->presion_arterial_y }} @endif">
                    </td>
                    <th>
                        <div id="mostrar_pre_art" align="center"></div>
                    </th>
                </tr>

                <tr>
                    <th>FRECUENCIA RESPIRATORIA</th>
                    <td><input type="text" name="frecuencia_respiratoria"
                            onkeyup="cambiarFR('frecuencia_respiratoria')" id="frecuencia_respiratoria"
                            placeholder="/min" class="form-control"
                            value="@if (isset($cita)) {{ $cita->frecuencia_respiratoria }} @endif"></td>
                    <th>
                        <div id="mostrar_fre_respiratorio" align="center"></div>
                    </th>
                </tr>

                <tr>
                    <th>FRECUENCIA CARDIACA</th>
                    <td><input type="text" name="frecuencia_cardiaca" id="frecuencia_cardiaca"
                            onkeyup="cambiarFC('frecuencia_cardiaca')" placeholder=" LPM " class="form-control"
                            value="@if (isset($cita)) {{ $cita->frecuencia_cardiaca }} @endif"></td>
                    <th>
                        <div id="mostrar_fre_car" align="center"></div>
                    </th>
                </tr>
                <tr>
                    <th>Fi02</th>
                    <td><input type="text" name="fi02" id="fi02" maxlength="3" placeholder=" % "
                            class="form-control"
                            value="@if (isset($cita)) {{ $cita->fi02 }} @endif"></td>
                    <th></th>
                </tr>
            </table>

            <div class="row">
                <div class="col-md-6" align="center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"
                        onclick="cancelarEstadoAJAX()">Cancelar</button>
                </div>
                <div class="col-md-6" align="center">
                    <button type="submit" class="btn btn-success" id="registrarModalTriaje" value="create" > Registrar</button>
                    
                </div>
            </div>
        </div>

    </form>
    

    <!-- 
        <input type="submit" value="Guardar" class="btn btn-success"> 
    -->
</div>
