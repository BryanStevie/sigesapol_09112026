<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ATENCION HOSPITALIZACION</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        @page {
            margin: 0cm 0cm;
            font-family: Arial;
        }

        body {
            margin: 1cm 1.5cm 2cm 1.5cm !important;
            color: #001028;
            background: #FFFFFF; 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            font-family: Arial;
            /* border: 1px solid red; */
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 15px !important;
            font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        footer {               
            position: fixed;
            bottom: 2cm;
            left: 1.5cm;
            right: 1.5cm;
            width: 100%;
            text-align: center; 
            overflow: auto;
        }
        #tbl_header {
            text-align: center;
        }
        #tbl_hospitalizacion td,
        #tbl_hospitalizacion_atencion td,
        .tbl_antecedente td {
            text-align: left;
            vertical-align: top;
        }

        .tbl_diagnostico td {
            text-align: left;
            vertical-align: top;
        }
        table.tbl_diagnostico th {
            text-align: left;
        }
        
        #tbl_hospitalizacion_footer td {
            border: 1px black solid !important;
            font-size: 9px;
            padding-top: 1px;
            padding-bottom: 1px;
            text-align: center;
            margin-bottom: 0 !important;
        }

        /**
        Para los subtitulos que tengan Numero Romanos
         */
        .subtitulo_1 {
            font-size: 14px; 
            padding-bottom:10px;
        }

        /**
        Para los subtitulos que tengan Numero enteros
         */
        .subtitulo_2 {
            font-size: 13px; 
            padding-bottom:1px;
        }

        /**
        Estilos para la tabla de ordenes opcionales (Recetas Vale, Ordenes de Laboratorio,
        Ordenes de Imagenologia e Interconsultas).
         */
        .tbl_ordenes td {
            padding: 3px;
        }

        table.tbl_antecedente {
            margin-bottom: 0 !important;
        }

        table.tbl_antecedente td {
            font-weight: 600;
            padding-top: 5px;
            padding-right: 5px;
        }
        table.tbl_antecedente td span,
        table.tbl_antecedente td p {
            font-weight: normal;
        }

    </style>
</head>

<body >
    <table id="tbl_header">
        <tr>
            <th style="width:30%"></th>
            <th style="width:30%"></th>
            <th style="width:40%"></th>
        </tr>
        <tr>
            <td>
                <div style="font-size: 8px; text-align: left;">
                    <table width="100%">
                        <tr>
                            <td width="30%">
                                <p align="right">
                                    <img src="images/logo_sanidad.png" width="60px;" />
                                </p>
                            </td>
                            <td width="70%">
                                <p align="left">Firmado electrónicamente por: 
                                <br/>{{ $prestacion->profesional_paterno }} {{ $prestacion->profesional_materno }} {{ ucwords(strtolower($prestacion->profesional_nombre)) }}
                                <br/>Motivo: Soy el autor del documento
                                <br/>Fecha: {{ date("d/m/Y H:i:s ", strtotime($prestacion->fecha_firma)) }}
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--img src="images/logo_pnp.png" alt="" width="40px;"/>
                <p><b>MINISTERIO DEL INTERIOR<br>POLICÍA NACIONAL DEL PERÚ</b></p-->
            </td>
            <td></td>
            <td>
                <p><br><br><br><br><b>DIRECCIÓN DE SANIDAD<br>{{$nombre_establecimiento}}</b></p>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" style="font-size: 18px;"><br><b>HISTORIA CLÍNICA DE HOSPITALIZACIÓN</b></td>
        </tr>
    </table>
    
    <footer>
        
        <table id="tbl_hospitalizacion_footer"  width="100%">
            <tr>
                <td>
                    <b>Apellido Paterno</b>
                </td>
                <td>
                    <b>Apellido Materno</b>
                </td>
                <td>
                    <b>Nombres</b>
                </td>
                <td style="width: 80px">
                    <b>Historia Clínica</b>
                </td>
                <td style="width: 150px">
                    <b>Servicio</b>
                </td>
                <td style="width: 60px">
                    <b>N° de Cama</b>
                </td>
                <!-- <td style="width: 50px">
                    <b>Episodio</b>
                </td> -->
            </tr>
            <tr>
                <td style="height:22px !important">{{$hospitalizacion->paterno_beneficiario}}</td>
                <td>{{$hospitalizacion->materno_beneficiario}}</td>
                <td>{{$hospitalizacion->nombre_beneficiario}}</td>
                <td>{{$hospitalizacion->nro_historia}}</td>
                <td>{{$hospitalizacion->servicio_sala}}</td>
                <td>{{$hospitalizacion->cama}}</td>
                <!-- <td></td> -->
            </tr>
        </table>
    </footer>
    
    <table border="0" id="tbl_hospitalizacion" >
        <tr>
            <th style="width:38%"></th>
            <th style="width:38%"></th>
            <th style="width:2%"></th>
            <th style="width:7%"></th>
            <th style="width:15%"></th>
        </tr>
        <tr>
            <td colspan="5" class="subtitulo_1"><b>I.- ANAMNESIS</b></td>
        </tr>
        <tr>
            <td colspan="5" class="subtitulo_2"><b>1.- FILIACIÓN</b></td>
        </tr>
        <tr>
            <td colspan="2">Apellidos y Nombres: <b>{{$hospitalizacion->paterno_beneficiario}} {{$hospitalizacion->materno_beneficiario}} {{$hospitalizacion->nombre_beneficiario}}</b></td>
            <td></td>
            <td><b>DNI</b></td>
            <td>{{$hospitalizacion->dni_beneficiario}}</td>
        </tr>
        <tr>
            <td>Edad: <b>{{$hospitalizacion->edad}}</b></td>
            <td>Sexo: <b>{{$hospitalizacion->sexo}}</b></td>
            <td></td>
            <td><b>CIP</b></td>
            <td>@if($persona_datos_adicionales != null) {{$persona_datos_adicionales->cip}} @endif</td>
        </tr>
        <tr>
            <td>Raza: <b>{{$asegurado->etnia}}</b></td>
            <td>Estado civil: <b>{{$asegurado->estado_civil}}</b></td>
            <td></td>
            <td><b>HC</b></td>
            <td>{{$hospitalizacion->nro_historia}}</td>
        </tr>
        <tr>
            <td>Grado de Instrucción: <b>{{$asegurado->grado_instruccion}}</b></td>
            <td>Ocupación: <b>{{$asegurado->ocupacion}}</b></td>
            <td></td>
            <td><b>RX N°</b></td>
            <td>-</td>
        </tr>
        <tr>
            <td>Lugar de Nacimiento: <b>{{$asegurado->departamento_nacimiento2}} / {{$asegurado->provincia_nacimiento2}} / {{$asegurado->distrito_nacimiento2}} / {{strtoupper($asegurado->direccion_nacimiento)}}</b></td>
            <td>Fecha de nacimiento: <b>{{$asegurado->fecha_nac}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Procedencia: <b>{{$asegurado->departamento_procedencia2}} / {{$asegurado->provincia_procedencia2}} / {{$asegurado->distrito_procedencia2}} / {{strtoupper($asegurado->direccion_procedencia)}}</b></td>
            <td>Religión: <b>{{$asegurado->religion}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Domicilio: <b>{{strtoupper($asegurado->domicilio)}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Distrito: <b>{{$asegurado->distrito}}</b></td>
            <td>Departamento: <b>{{$asegurado->departamento}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Teléfono fijo: <b>{{$asegurado->telefono}}</b></td>
            <td>Celular: <b>{{$asegurado->telefono2}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Apellidos y Nombres del titular: <b>@if($titular != null){{$titular->paterno}} {{$titular->materno}} {{$titular->nombre}}@endif</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Grado de Situación del titular: <b>@if($grado != null) {{$grado->descripcion_corta}} @endif</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Parentesco con el paciente: <b>{{$hospitalizacion->tipo_beneficiario}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Fecha y hora de ingreso: <b>{{date("d/m/Y", strtotime($hospitalizacion->fecha_ingreso))}} {{date("H:i a", strtotime($hospitalizacion->hora_ingreso))}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Fecha y hora de Historia Clínica: <b>{{date("d/m/Y H:i a", strtotime($hospitalizacion->created_at))}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Código SITEDS: <b>{{$hospitalizacion->cod_siteds_susalud}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">CUPS: <b>{{$hospitalizacion->cups_pnp}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

    </table>

    <table border="0" class="tbl_antecedente">
        <tr>
            <td colspan="3" class="subtitulo_2"><b>2.- ENFERMEDAD ACTUAL</b></td>
        </tr>
        <tr>
            <td style="width:50%;">Tiempo de enfermedad: <span>{{$atencion->tiempo_enfermedad}}</span></td>
            <td style="width:30%">Forma de inicio: <span>{{$atencion->forma_inicio}}</span></td>
            <td style="width:20%">Curso: <span>{{$atencion->curso}}</span></td>
        </tr>
        <tr>
            <td colspan="3">Síntomas y signos principales:</td>
        </tr>
        <tr>
            <td colspan="3"><p>{!! nl2br(e($atencion->sintomas_signos_principal)) !!}</p></td>
        </tr>
    </table>
    <table class="tbl_antecedente">
        <tr>
            <td colspan="3">Relato:</td>
        </tr>
        <p>{!! nl2br(e($atencion->relato)) !!}</p>
    </table>
    <table class="tbl_antecedente">
        <tr>
            <td colspan="3">Tratamiento recibido antes de la hospitalización:</td>
        </tr>
        <tr>
            <td colspan="3"><p>{!! nl2br(e($atencion->tratamiento_recibido_antes)) !!}</p></td>
        </tr>
        <tr>
            <td colspan="3">Funciones biológicas:</td>
        </tr>
        <tr>
            <td>Apetito: <span>{{$atencion->fn_bio_1}}</span></td>
            <td>Sed: <span>{{$atencion->fn_bio_2}}</span></td>
            <td>Peso: <span>{{$atencion->fn_bio_3}}</span></td>
        </tr>
        <tr>
            <td>Orina: <span>{{$atencion->fn_bio_4}}</span></td>
            <td>Deposiciones: <span>{{$atencion->fn_bio_5}}</span></td>
            <td>Sueño: <span>{{$atencion->fn_bio_6}}</span></td>
        </tr>
    </table>

    <!-- table.tbl_antecedente ponerle margin bottom 0 -->
    <table border="0" class="tbl_antecedente" >
        <tr>
            <td class="subtitulo_2" colspan="3"><b>3.- ANTECEDENTES</b></td>
        </tr>
        <tr>
            <td colspan="3"><b>PERSONALES</b></td>
        </tr>
        <tr>
            <td colspan="3"><b>Generales</b></td>
        </tr>
        <tr>
            <td style="width:50%">Grupo sanguíneo y factor RH: <span>{{$atencion->antc_1}}</span></td>
            <td style="width:25%">Alergias-RAM: <span>{{$atencion->antc_2}}</span></td>
            <td style="width:25%">Drogas: <span>{{$atencion->antc_3}}</span></td>
        </tr>
    </table>
    <table class="tbl_antecedente">
        <tr>
            <td style="width:20%">Alcohol: <span>{{$atencion->antc_4}}</span></td>
            <td style="width:20%">Tabaco: <span>{{$atencion->antc_5}}</span></td>
            <td style="width:20%">Café: <span>{{$atencion->antc_6}}</span></td>
            <td style="width:40%">Transfusiones: <span>{{$atencion->antc_7}}</span></td>
        </tr>
        <tr>
            <td colspan="4">Otros de importancia: <span>{!! nl2br(e($atencion->antc_8)) !!}</span></td>
        </tr>
    </table>
    <table border="0" class="tbl_antecedente" >
        <tr>
            <td colspan="3" style="padding-top:8px"><b>Fisiológicos</b></td>
        </tr>
        <tr>
            <td style="width:40%">Nacimiento: <span>{{$atencion->antc_9}}</span></td>
            <td style="width:30%">Desarrollo Psicomotor: <span>{{$atencion->antc_10}}</span></td>
            <td style="width:30%">Vacunas: <span>{{$atencion->antc_11}}</span></td>
        </tr>
    </table>
    <table border="0" class="tbl_antecedente" >
        <tr>
            <td style="width:50%">Inicio de relaciones sexuales: <span>{{$atencion->antc_12}}</span></td>
            <td style="width:50%">Comportamiento Sexual: <span>{{$atencion->antc_13}}</span></td>
        </tr>
    </table>
    <table border="0" class="tbl_antecedente" >
        <tr>
            <td style="width:35%">Menarquía: <span>{{$atencion->antc_14}}</span></td>
            <td style="width:35%">Régimen Catamenial: <span>{{$atencion->antc_15}}</span></td>
            <td>Fecha de última regla: <span>{{$atencion->antc_16}}</span></td>
        </tr>
    </table>
    <table border="0" class="tbl_antecedente" >
        <tr>
            <td style="width:50%">Método Anticonceptivo: <span>{{$atencion->antc_17}}</span></td>
            <td style="width:50%">Uso de hormonas: <span>{{$atencion->antc_18}}</span></td>
        </tr>
    </table>
    <table border="0" class="tbl_antecedente" >
        <tr>
            <td style="width:25%">Gesta: <span>{{$atencion->antc_19}}</span></td>
            <td style="width:75%">PARA: <span>{{$atencion->antc_20}}</span></td>
        </tr>
    </table>

    <table border="0" class="tbl_antecedente">
        <tr>
            <td colspan="4" style="padding-top:8px"><b>Patológicos</b></td>
        </tr>
        <tr>
            <td colspan="4">Enfermedades de la infancia: <span>{{$atencion->antc_21}}</span></td>
        </tr>
        <tr>
            <td style="width:25%">Diabetes: <span>{{$atencion->antc_22}}</span></td>
            <td style="width:25%">Hipertensión: <span>{{$atencion->antc_23}}</span></td>
            <td style="width:25%">TBC: <span>{{$atencion->antc_24}}</span></td>
            <td style="width:25%">Dislipidemias: <span>{{$atencion->antc_25}}</span></td>
        </tr>
        <tr>
            <td style="width:25%">Hepatitis: <span>{{$atencion->antc_26}}</span></td>
            <td style="width:25%">Convulsiones: <span>{{$atencion->antc_27}}</span></td>
            <td style="width:25%">Asma: <span>{{$atencion->antc_28}}</span></td>
            <td style="width:25%">Otros: <span>{{$atencion->antc_29}}</span></td>
        </tr>
        <tr>
            <td colspan="4">Intervenciones Quirúrgicas: <span>{{$atencion->antc_30}}</span></td>
        </tr>
        <tr>
            <td colspan="4">Traumatismo y Accidentes: <span>{{$atencion->antc_31}}</span></td>
        </tr>
        <tr>
            <td colspan="4">Medicación de uso habitual: <span>{{$atencion->antc_32}}</span></td>
        </tr>
        <tr>
            <td colspan="4">Hospitalizaciones previas: <span>{{$atencion->antc_33}}</span></td>
        </tr>
    </table>

    <table border="0" id="tbl_hospitalizacion_atencion" >
        <tr>
            <td><b>FAMILIARES</b></td>
        </tr>
        <tr>
            <td><p style="margin:0;">{!! nl2br(e($prestacion->antecedente_familiar)) !!}</p></td>
        </tr>
    </table>

    <table border="0" id="tbl_hospitalizacion_atencion" >
        <tr>
            <td colspan="4" class="subtitulo_1"><b>II.- EXAMEN FÍSICO</b></td>
        </tr>
        <tr>
            <td colspan="4" class="subtitulo_2"><b>1.- FUNCIONES VITALES</b></td>
        </tr>
        <tr>
            <td>Presión arterial: <b>{{ $prestacion->presion_arterial_x }} / {{$prestacion->presion_arterial_y}}</b></td>
            <td>Frecuencia Cardiaca: <b>{{ $prestacion->frecuencia_cardiaca }}</b></td>
            <td>Frecuencia Respiratoria: <b>{{ $prestacion->frecuencia_respiratoria }}</b></td>
            <td>Temperatura: <b>{{ $prestacion->temperatura }}</b></td>
        </tr>
        <tr>
            <!-- <td>Talla: <b>{{ $prestacion->talla_cm }}</b></td>
            <td>Peso: <b>{{ $prestacion->peso_kg }}</b></td> -->
            <td>Tamaño de Cintura: <b>{{ $prestacion->tamano_cintura_cm }}</b></td>
            <td>SaO2: <b>{{ $prestacion->sa02_porcentaje }}</b></td>
            <td>FiO2: <b>{{ $prestacion->fi02 }}</b></td>
            <td>Frecuencia Cardiaca Fetal: <b>{{ $prestacion->frecuencia_cardiaca_fetal }}</b></td>
        </tr>
        <!-- <tr>
            <td>FiO2: <b>{{ $prestacion->fi02 }}</b></td>
            <td>Frecuencia Cardiaca Fetal: <b>{{ $prestacion->frecuencia_cardiaca_fetal }}</b></td>
        </tr> -->
    </table>

    <table border="0" class="tbl_antecedente" >
        <tr>
            <td class="subtitulo_2"><b>2.- EXAMEN GENERAL</b></td>
        </tr>
        <tr>
            <!-- <td><p>{!! nl2br(e($prestacion->examen_clinico)) !!}</p></td> -->
            <td colspan="4">Ectoscopía: <span>{!! nl2br(e($atencion->exam_gen_1)) !!}</span></td>
        </tr>
        <tr>
            <td style="width:30%">Tipo morfológico: <span>{{$atencion->exam_gen_2}}</span></td>
            <td style="width:30%">Fascies: <span>{{$atencion->exam_gen_3}}</span></td>
            <td style="width:20%">Talla: <span>{{$atencion->exam_gen_4}}</span></td>
            <td style="width:20%">Peso: <span>{{$atencion->exam_gen_5}}</span></td>
        </tr>
        <tr>
            <td colspan="4">Piel y faneras: <span>{{$atencion->exam_gen_6}}</span></td>
        </tr>
        <tr>
            <td colspan="4">Tejido celular subcútaneo: <span>{{$atencion->exam_gen_7}}</span></td>
        </tr>
        <tr>
            <td colspan="4">Sistema Linfático: <span>{!! nl2br(e($atencion->exam_gen_8)) !!}</span></td>
        </tr>
        <tr>
            <td colspan="4">Sistema Locomotor: <span>{!! nl2br(e($atencion->exam_gen_9)) !!}</span>
            @if($atencion->ruta_img_exam_gen_9 != null)
              <div style="text-align:center; width:100%">
                <img src="images/atenciones/{{$atencion->ruta_img_exam_gen_9}}" alt="" style="max-width:680px;">
              </div>
            @endif
            </td>
        </tr>
    </table>

    <table border="0" class="tbl_antecedente">
        <tr>
            <td class="subtitulo_2"><b>3.- EXAMEN REGIONAL</b></td>
        </tr>
        <tr>
            <!-- <td><p>{!! nl2br(e($prestacion->examen_clinico_regional)) !!}</p></td> -->
            <td><b>CABEZA Y CUELLO</b></td>
        </tr>
        <tr>
            <td>Cráneo: <span>{{$atencion->exam_reg_1}}</span></td>
        </tr>
        <tr>
            <td>Cabello: <span>{{$atencion->exam_reg_2}}</span></td>
        </tr>
        <tr>
            <td>Ojos: <span>{{$atencion->exam_reg_3}}</span></td>
        </tr>
        <tr>
            <td>Nariz: <span>{{$atencion->exam_reg_4}}</span></td>
        </tr>
        <tr>
            <td>Oídos: <span>{{$atencion->exam_reg_5}}</span></td>
        </tr>
        <tr>
            <td>Boca: <span>{{$atencion->exam_reg_6}}</span></td>
        </tr>
        <tr>
            <td>Cuello: <span>{{$atencion->exam_reg_7}}</span>
            @if($atencion->ruta_img_cabeza_cuello != null)
              <div style="text-align:center; width:100%">
                <img src="images/atenciones/{{$atencion->ruta_img_cabeza_cuello}}" alt="" style="max-width:680px;">
              </div>
            @endif
            </td>
        </tr>
        <tr>
            <td><b>MAMAS</b></td>
        </tr>
        <tr>
            <td>Derecha: <span>{{$atencion->exam_reg_8}}</span></td>
        </tr>
        <tr>
            <td>Izquierda: <span>{{$atencion->exam_reg_9}}</span>
            @if($atencion->ruta_img_mama != null)
              <div style="text-align:center;width:100%;">
                <img src="images/atenciones/{{$atencion->ruta_img_mama}}" alt="" style="max-width:680px;" >
              </div>
            @endif
            </td>
        </tr>
        <tr>
            <td><b>TÓRAX Y PULMONES</b><p>{!! nl2br(e($atencion->exam_reg_10)) !!}</p>
            @if($atencion->ruta_img_exam_reg_10 != null)
              <div style="text-align:center;width:100%;">
                <img src="images/atenciones/{{$atencion->ruta_img_exam_reg_10}}" alt="" style="max-width:680px;" >
              </div>
            @endif
            </td>
        </tr>
        <tr>
            <td><b>CARDIOVASCULAR</b><p>{!! nl2br(e($atencion->exam_reg_11)) !!}</p>
            @if($atencion->ruta_img_exam_reg_11 != null)
              <div style="text-align:center;width:100%;">
                <img src="images/atenciones/{{$atencion->ruta_img_exam_reg_11}}" alt="" style="max-width:680px;" >
              </div>
            @endif
            </td>
        </tr>
        <tr>
            <td><b>ABDOMEN</b><p>{!! nl2br(e($atencion->exam_reg_12)) !!}</p>
            @if($atencion->ruta_img_exam_reg_12 != null)
              <div style="text-align:center;width:100%;">
                <img src="images/atenciones/{{$atencion->ruta_img_exam_reg_12}}" alt="" style="max-width:680px;" >
              </div>
            @endif
            </td>
        </tr>
        <tr>
            <td><b>GENITO URINARIO</b><p>{!! nl2br(e($atencion->exam_reg_13)) !!}</p>
            @if($atencion->ruta_img_exam_reg_13 != null)
              <div style="text-align:center;width:100%;">
                <img src="images/atenciones/{{$atencion->ruta_img_exam_reg_13}}" alt="" style="max-width:680px;" >
              </div>
            @endif
            </td>
        </tr>
        <tr>
            <td><b>TACTO RECTAL</b><p>{!! nl2br(e($atencion->exam_reg_14)) !!}</p></td>
        </tr>
        <tr>
            <td><b>NEUROLÓGICO</b></td>
        </tr>
        <tr>
            <td>Estado mental (conciencia-orientación): <span>{!! nl2br(e($atencion->exam_reg_15)) !!}</span></td>
        </tr>
        <tr>
            <td>Funciones superiores: <span>{!! nl2br(e($atencion->exam_reg_16)) !!}</span></td>
        </tr>
        <tr>
            <td>Función motora (fuerza y tono muscular, movimientos involuntarios, coordinación y reflejos): <span>{!! nl2br(e($atencion->exam_reg_17)) !!}</span></td>
        </tr>
        <tr>
            <td>Función sensitiva (superficial y profunda): <span>{!! nl2br(e($atencion->exam_reg_18)) !!}</span>
            @if($atencion->ruta_img_neurologico != null)
              <div style="text-align:center;width:100%;">
                <img src="images/atenciones/{{$atencion->ruta_img_neurologico}}" alt="" width="100%" >
              </div>
            @endif
            </td>
        </tr>

        <tr>
            <td><b>Pares craneales</b></td>
        </tr>
        <tr>
            <td>I Olfatorio: <span>{{$atencion->exam_reg_19}}</span></td>
        </tr>
        <tr>
            <td>II Óptico (agudeza visual, campos visuales, colore, fondo de ojo, presión intraocular): <span>{!! nl2br(e($atencion->exam_reg_20)) !!}</span></td>
        </tr>
        <tr>
            <td>III IV VI Oculomotores (movimientos oculares, pupilas): <span>{!! nl2br(e($atencion->exam_reg_21)) !!}</span></td>
        </tr>
        <tr>
            <td>V Trigésimo (reflejo corneal, sensibilidad facial, fuerza músculos masticadores): <span>{!! nl2br(e($atencion->exam_reg_22)) !!}</span></td>
        </tr>

        <tr>
            <td>VII Facial (simetría facial, gusto): <span>{{$atencion->exam_reg_23}}</span></td>
        </tr>
        <tr>
            <td>VIII Cocleovesticular (audición, equilibrio): <span>{{$atencion->exam_reg_24}}</span></td>
        </tr>
        <tr>
            <td>IX y X Glosofaríngeo y Neumogástrico (voz, deglución, gusto): <span>{{$atencion->exam_reg_25}}</span></td>
        </tr>
        <tr>
            <td>XI Espinal (esternocleidomastoideo): <span>{{$atencion->exam_reg_26}}</span></td>
        </tr>
        <tr>
            <td>XII Hipogloso (lengua): <span>{{$atencion->exam_reg_27}}</span></td>
        </tr>
        <tr>
            <td>AMPLIACIÓN DEL EXAMEN FÍSICO (si fuese necesario) <p>{!! nl2br(e($atencion->ampliacion_exam_fisico)) !!}</p></td>
        </tr>
        
    </table>

    <table style="width: 100%;" border="0" class="tbl_diagnostico" >
        <tr>
            <td colspan="5" class="subtitulo_1"><b>DIAGNÓSTICO</b></td>
        </tr>
        <tr>
            <th></th>
            <th>CIE</th>
            <th>Descripción</th>
            <th>Tipo</th>
            <th>Prioridad</th>
        </tr>
        <?php
        $i = 1;
        ?>
        @foreach($diagnosticos as $dx)
        <tr>
            <?php
            $tipo = "";
            switch($dx->id_tipo_diagnostico): 
                case 1: $tipo = "PRESUNTIVO"; break;
                case 2: $tipo = "DEFINITIVO"; break;
                case 3: $tipo = "REITERATIVO"; break;
            endswitch;
            $categoria = "";
            switch($dx->id_categoria): 
                case 1: $categoria = "PRINCIPAL"; break;
                case 2: $categoria = "SECUNDARIO"; break;
            endswitch;
            ?>
            <td style="width:5%; border-bottom: 1px solid gray;"><b>{{ $i }}.-</b></td>
            <td style="width:10%; border-bottom: 1px solid gray;">{{ $dx->cod_dx }}</td>
            <td style="width:55%; border-bottom: 1px solid gray;"><p>{{ $dx->nombre_dx }}</p></td>
            <td style="width:15%; border-bottom: 1px solid gray;">{{ $tipo }}</td>
            <td style="width:15%; border-bottom: 1px solid gray;">{{ $categoria }}</td>
            <?php
            $i++;
            ?>
        </tr>
        @endforeach
    </table>

    <table border="0" class="tbl_diagnostico">
        <tr>
            <td colspan="4" class="subtitulo_2"><b>PROCEDIMIENTO</b></td>
        </tr>
        <?php
        $i = 1;
        ?>
        @foreach($procedimientos as $cpms)
        <tr>
            <td style="width:5%; border-bottom: 1px solid gray;"><b>{{ $i }}.-</b></td>
            <td style="width:10%; border-bottom: 1px solid gray;">{{ $cpms->cod_cpms }}</td>
            <td style="width:70%; border-bottom: 1px solid gray;"><p>{{ $cpms->descripcion_cpms }}</p></td>
            <td style="width:15%; border-bottom: 1px solid gray;"><b>Cantidad:</b> {{ $cpms->cantidad }}</td>
            <?php
            $i++;
            ?>
        </tr>
        @endforeach
    </table>

    @if(isset($gestacion))
        <table style="background-color:white !important" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <th colspan="4" class="subtitulo_2">SALUD MATERNA</th>
            </tr>
            <tr>
                <td colspan="4" valign="top">
                    <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
                        <tbody>
                            <tr>
                                <td colspan="4" style="text-align: center; border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                                    <b>GESTACION</b>
                                </td>
                            </tr>
                            <tr>
                                <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">FECHA REGLA</td>
                                <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">FECHA_PROBABLE_PARTO</td>
                                <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">SEMANAS_GESTACION</td>
                                <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">N° CONTROLES</td>
                            </tr>
                            <tr>
                                <td style="border:1px solid #A4A4A4">{{$gestacion->fech_ult_regla}}</td>
                                <td style="border:1px solid #A4A4A4">{{$gestacion->fech_prob_parto}}</td>
                                <td style="border:1px solid #A4A4A4">{{$gestacion->semanas_gestacion}}</td>
                                <td style="border:1px solid #A4A4A4">{{$gestacion->num_controles}}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    @endif

    @if (isset($obj_hospitalizacion_atencion->id_puerperio))
    <fieldset>
        <div class="panel panel-info panel-bordered panel-sm">
            <div class="panel-heading">
            <div class="panel-title text-center">
                <b>EVENTOS EPIDEMIOLÓGICOS</b>
            </div>
            </div>
            <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                [{!! in_array(11, $eventosMarcados) ? 'x' : ' ' !!}] Gestantes que han recibido por lo menos un control prenatal en la IPRESS
                </div>
                <div class="col-md-6">
                [{!! in_array(12, $eventosMarcados) ? 'x' : ' ' !!}] Gestantes que han recibido por lo menos 6 controles prenatales
                </div>
                <div class="col-md-6">
                [{!! in_array(13, $eventosMarcados) ? 'x' : ' ' !!}] Gestantes que han recibido la primera consulta en el primer trimestre del embarazo
                </div>
                <div class="col-md-6">
                [{!! in_array(16, $eventosMarcados) ? 'x' : ' ' !!}] Nacidos vivos con bajo peso al nacer (menos de 2500 gramos)
                </div>
                <div class="col-md-6">
                [{!! in_array(21, $eventosMarcados) ? 'x' : ' ' !!}] Nacidos vivos con edad gestacional menor a 37 semanas de gestación (según fecha última regla)
                </div>
                <div class="col-md-6">
                [{!! in_array(15, $eventosMarcados) ? 'x' : ' ' !!}] Gestantes con diagnóstico confirmado de infección HIV
                </div>
                <div class="col-md-6">
                [{!! in_array(14, $eventosMarcados) ? 'x' : ' ' !!}] Gestantes con diagnóstico confirmado de Tuberculosis
                </div>
                <div class="col-md-6">
                [{!! in_array(8, $eventosMarcados) ? 'x' : ' ' !!}] Fallecimiento del paciente
                </div>
            </div>
            </div>
        </div>
    </fieldset>
    @endif

    @if(isset($puerperio))

    <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
        <thead>
            <tr>
                <th colspan="3" class="subtitulo_2">SALUD MATERNA</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="3" style="text-align: center; border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                    <b>PUERPERIO</b>
                </td>
            </tr>
            <tr>
                <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">FECHA PARTO</td>
                <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">TIPO PARTO</td>
                <td class="ancho_nro titulos" style="border:1px solid #A4A4A4;font-style:italic;font-weight:bold">COMPLICACIONES</td>
            </tr>
            <tr>
                <td style="border:1px solid #A4A4A4">{{$puerperio->fecha_parto}}</td>
                <td style="border:1px solid #A4A4A4">
                    @if($puerperio->id_tipo_parto == 1)
                    VAGINAL
                    @elseif($puerperio->id_tipo_parto == 2)
                    CESÁREA
                    @else
                    {{$puerperio->id_tipo_parto}}
                    @endif
                </td>
                <td style="border:1px solid #A4A4A4">{{$puerperio->complicaciones}}</td>
            </tr>
            <tr>
                <td colspan="3" style="border:1px solid #A4A4A4">
                    <b>Descripción:</b> {{$puerperio->descripcion}}
                </td>
            </tr>
        </tbody>
    </table>

    @endif

    @if (isset($puerperio))
        <table style="background-color:white !important;border-collapse:separate;border-spacing:1px">
            <tbody>
                <tr>
                    <td colspan="4" style="text-align: center; border:1px solid #A4A4A4;font-size:12px!important;font-style:italic;">
                        <b>NEONATOS</b>
                    </td>
                </tr>
                @foreach ($puerperio->natis as $nati)
                <tr>
                    <td style="border:1px solid #A4A4A4; padding: 5px; width: 15%;">
                        <label style="font-weight: bold;">ESTADO DE VIDA:</label><br>
                        <p>{{$nati->estado}}</p>
                    </td>
                    <td style="border:1px solid #A4A4A4; padding: 5px; width: 20%;">
                        <label style="font-weight: bold;">TIPO DE DOCUMENTOS:</label><br>
                        <p>
                            @if($nati->id_tipo_doc_nati == 1)
                                DNI
                            @elseif($nati->id_tipo_doc_nati == 2)
                                CNV
                            @elseif($nati->id_tipo_doc_nati == 3)
                                AFILIACION DEL RNE
                            @else
                                Desconocido
                            @endif
                        </p>
                    </td>
                    <td style="border:1px solid #A4A4A4; padding: 5px; width: 15%;">
                        <label style="font-weight: bold;">NUM. DOCUMENTOS:</label><br>
                        <p>{{$nati->num_doc}}</p>
                    </td>
                    <td style="border:1px solid #A4A4A4; padding: 5px; width: 15%;">
                        <label style="font-weight: bold;">SEXO:</label><br>
                        <p>
                            @if($nati->sexo == 'M')
                                MASCULINO
                            @elseif($nati->sexo == 'F')
                                FEMENINO
                            @else
                                Desconocido
                            @endif
                        </p>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- SE QUITO LA SECCION -->

    <table border="0" >
        <tr>
            <td class="subtitulo_2"><b>MEDICO ENCARGADO</b></td>
        </tr>
    </table>

    <table border="0" style="" >
        <tr>
            <td><b>APELLIDOS Y NOMBRES:</b></td>
            <td colspan="3">{{ $prestacion->profesional_paterno }} {{ $prestacion->profesional_materno }} {{ $prestacion->profesional_nombre }}</td>
        </tr>
        <tr>
            <td style="width:23%; padding-top: 5px;"><b>PROFESIÓN:</b></td>
            <td style="">{{ $prestacion->profesional_profesion }}</td>
            <td style="width:17%"><b>N° COLEGIATURA:</b></td>
            <td style="width:10%">{{ $prestacion->profesional_colegiatura }}</td>
        </tr>
    </table>

</body>

</html>