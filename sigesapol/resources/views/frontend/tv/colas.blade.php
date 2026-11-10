@extends('frontend.layouts.master_tv')
@section('renderjs')

<style type="text/css">
    .letra {
        font-size:4.5rem;
        font-weight: bold;
    }
    .resaltado {
        /* background: green; */
        font-size:4.5rem;
        font-weight: bold;
        color: green;
    }
    
</style>
<script>
    var timer = setInterval(listarPacientes, 2000);
    const music = new Audio("{{ asset('assets/sound/woohoo.wav') }}");
    var utterance  = new SpeechSynthesisUtterance();
    var var_while = true;
    var pantalla_completa = false;

    $(document).ready(function() {
        utterance.rate = 0.7;
    });

    function decir(texto) {
        
        utterance.rate = 0.7;
        utterance.text = texto;
        speechSynthesis.speak(utterance);
    }

    function actualizarEstadoLlamada(id_cita, estado) {
        $.ajax({
            url: "/tv_cambiar_estado_llamada/" + id_cita+"/"+estado,
            async: false,
            type: "GET",
            success: function(result) {
                
                return "Y";
            },
            complete : function(xhr, status) {
                return false;
            }
        });
    }

    function listarPacientes() {
        var id_tv = document.getElementById("id_tv").value;
        var id_area = document.getElementById("id_area").value;
        var texto = "";
        var newOption = "";
        var nuevo = "";

        $.ajax({
            url: "/tv_llamado_iterativo/" + id_tv + "/" + id_area,
            async: false,
            type: "GET",
            success: function(result) {
                console.log("Nuevo llamado");
                newOption = "";

				if (result.length > 0) {

                    for (let i= 0; i < result.length; i++) {
                        console.log(result[i].nombre);
                        nuevo = "";
                        
                        let consultorio = result[i].descripcion_tv == null ? result[i].consultorio : result[i].descripcion_tv;

                        if (result[i].estado_llamada == "0") {
                            
                            $.ajax({
                                url: "/tv_cambiar_estado_llamada/"+result[i].id+"/1",
                                async: false,
                                type: "GET",
                                success: function(result) {
                                    return "Y";
                                },
                                complete : function(xhr, status) {
                                    return false;
                                }
                            });

                            texto = "Paciente "+result[i].nombre+" "+result[i].paterno+" acercarse al "+consultorio;
                            music.play();
                            utterance.text = texto;
                            speechSynthesis.speak(utterance);

                            nuevo = "SI";
                        }

                        newOption += "<tr>";
                        newOption += "<th width='2%' >&nbsp;</th>";
                        if (nuevo == "SI") {
                            // newOption += "<th width='2%' ><span class='resaltado'>*</span></th>";
                            newOption += "<td width='63%'><span class='resaltado'>"+result[i].nombre+", "+result[i].paterno+"</span></td>";
                            newOption += "<td width='20%'><span class='resaltado'>"+result[i].abrev_servicio+"</span></td>";
                            newOption += "<td width='15%'><span class='resaltado'>"+result[i].consultorio+"</span></td>";
                        } else {
                            // newOption += "<th width='2%' ><span class='letra'>*</span></th>";
                            newOption += "<td width='63%'><span class='letra'>"+result[i].nombre+", "+result[i].paterno+"</span></td>";
                            newOption += "<td width='20%'><span class='letra'>"+result[i].abrev_servicio+"</span></td>";
                            newOption += "<td width='15%'><span class='letra'>"+result[i].consultorio+"</span></td>";
                        }
                        newOption += "</tr>";
                        $('#tbl_paciente').html(newOption);

                        texto = "";
                    }

                } else {
                    $('#tbl_paciente').html(newOption);
                }
                
            },
            complete : function(xhr, status) {
                return false;
            }
        });
    }

    function maximizarYMinimizarPantalla() {
        if (!pantalla_completa) {
            var docElm = document.documentElement;
            if (docElm.requestFullscreen) {
                docElm.requestFullscreen();
            }
            else if (docElm.mozRequestFullScreen) {
                docElm.mozRequestFullScreen();
            }
            else if (docElm.webkitRequestFullScreen) {
                docElm.webkitRequestFullScreen();
            }
            pantalla_completa = true;
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
            else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            }
            else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            }
            pantalla_completa = false;
        }
    }


</script>
@endsection
@section('content')
    <div style="margin: 1rem" >
        <div class="row">
            <?php
            
            $dia_semana = date("l");
            $numero_dia = date("d");
            $nombre_mes = date("F");

            $codigo_dia = date("N");
            switch ($codigo_dia) {
                case "1": $dia_semana = "Lunes"; break;
                case "2": $dia_semana = "Martes"; break;
                case "3": $dia_semana = "Miércoles"; break;
                case "4": $dia_semana = "Jueves"; break;
                case "5": $dia_semana = "Viernes"; break;
                case "6": $dia_semana = "Sábado"; break;
                case "7": $dia_semana = "Domingo"; break;
            }
            $codigo_mes = date("m");
            switch ($codigo_mes) {
                case "01": $nombre_mes = "Enero"; break;
                case "02": $nombre_mes = "Febrero"; break;
                case "03": $nombre_mes = "Marzo"; break;
                case "04": $nombre_mes = "Abril"; break;
                case "05": $nombre_mes = "Mayo"; break;
                case "06": $nombre_mes = "Junio"; break;
                case "07": $nombre_mes = "Julio"; break;
                case "08": $nombre_mes = "Agosto"; break;
                case "09": $nombre_mes = "Septiembre"; break;
                case "10": $nombre_mes = "Octubre"; break;
                case "11": $nombre_mes = "Noviembre"; break;
                case "12": $nombre_mes = "Diciembre"; break;
            }
            $anio = date("Y");
            ?>
            <table border="0" style="margin-bottom:1rem;" width="100%" >
                <tr>
                    <td style="text-align:center;font-size:3rem;font-weight: bold;" width="30%"><span style="text-align:right" id="dia">{{$dia_semana}}</span></td>
                    <td style="text-align:right;padding-right:1rem;" rowspan="2"><a onclick="maximizarYMinimizarPantalla()"><img src="{{ asset('images/logo/dirsapol.png') }}" width="60px" alt=""></a></td>
                    <td style="text-align:left;font-size:3rem;font-weight: bold;" rowspan="2" width="30%"><span>{{$establecimiento->nombre}}</span></td>
                </tr>
                <tr style="text-align:center;font-size:3rem;font-weight: bold;"><td><span id="fecha">{{$numero_dia}} de {{$nombre_mes}} del {{$anio}}</span></td></tr>
            </table>
        </div>
        <div class="row">
            <input type="hidden" value="{{$id_tv}}" id="id_tv">
            <input type="hidden" value="{{$id_area}}" id="id_area">

            <div class="panel panel-primary">
                <div class="panel-heading" style="font-size:3.5rem">Pacientes</div>
                <table id="tbl_paciente" class="table">
                </table>
            </div>
        </div>
    </div>

@endsection