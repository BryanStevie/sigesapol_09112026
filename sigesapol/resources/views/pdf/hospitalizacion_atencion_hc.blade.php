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
        .tbl_antecedentes td {
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
            /* El padding de los subtitulos se omiten porque estan en una tabla diferente */
            /* padding-bottom:1px; */
        }

        /**
        Estilos para la tabla de ordenes opcionales (Recetas Vale, Ordenes de Laboratorio,
        Ordenes de Imagenologia e Interconsultas).
         */
        .tbl_ordenes td,
        .tbl_ordenes th  {
            border: 1px solid #000;
            padding: 3px;
        }

        table.tbl_antecedentes {
            margin-bottom: 0 !important;
        }

        table.tbl_antecedentes td,
        table.tbl_antecedentes th {
            border: 1px solid #000;
            font-weight: 600;
            /* padding-top: 5px;
            padding-right: 5px; */
            padding: 3px;
        }
        table.tbl_antecedentes td span,
        table.tbl_antecedentes td p {
            font-weight: normal;
        }

        .no-border-bottom {
            border-bottom: none !important;
        }

        .no-border-top {
            border-top: none !important;
        }

        .no-border-last-row {
            border-bottom: none !important;
            border-left: none !important;
            border-right: none !important;
        }

        .page_break {
        page-break-before: always;
        }

    </style>
</head>

<body >
    <?php 
    foreach ($prestaciones as $prestacion) {
        foreach ($atenciones as $atencion) {
            if ($prestacion->id == $atencion->id_prestacion) {
    ?>
    <table id="tbl_header">
        <tr>

            <th style="width:30%"></th>

            <th style="width:30%"></th>
            <th style="width:40%"></th>
        </tr>
        <tr>
            <td>
                <img src="images/logo_pnp.png" alt="" width="40px;"/>
                <p><b>MINISTERIO DEL INTERIOR<br>POLICÍA NACIONAL DEL PERÚ</b></p>
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
            <td colspan="3" style="font-size: 18px;"><b>HISTORIA CLÍNICA DE HOSPITALIZACIÓN</b></td>
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
        {{-- <script type="text/php">
            if ( isset($pdf) ) {
                $pdf->page_script('
                    $pdf->text(560, 20, "$PAGE_NUM/$PAGE_COUNT", "Arial", 8);
                ');
            }
        </script> --}}
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
            <td colspan="2">Apellidos y Nombres del titular: <b>{{$titular->paterno}} {{$titular->materno}} {{$titular->nombre}}</b></td>
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
            <td colspan="2">Parentesco del paciente: <b>{{$hospitalizacion->tipo_beneficiario}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Fecha y hora de ingreso: <b>{{ date("d/m/Y", strtotime($hospitalizacion->fecha_ingreso)) }} {{ date("H:i:s A", strtotime($hospitalizacion->hora_ingreso)) }}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">Fecha y hora de Historia Clínica: <b>{{ date("d/m/Y H:i:s A", strtotime($hospitalizacion->created_at)) }}</b></td>
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

    <table border="0">
        <tr>
            <td colspan="3" class="subtitulo_2"><b>2.- ENFERMEDAD ACTUAL</b></td>
        </tr>
    </table>
     
    <table border="1" class="tbl_antecedentes">
        <tr>
            <td style="width:50%;">Tiempo de enfermedad: <span>{{$atencion->tiempo_enfermedad}}</span></td>
            <td style="width:30%">Forma de inicio: <span>{{$atencion->forma_inicio}}</span></td>
            <td style="width:20%">Curso: <span>{{$atencion->curso}}</span></td>
        </tr>
        <tr>
            <td colspan="3" class="no-border-bottom">Síntomas y signos principales:</td>
        </tr>
        <tr>
            <td colspan="3" class="no-border-top"><p>{!! nl2br(e($atencion->sintomas_signos_principal)) !!}</p></td>
        </tr>
        <tr>
            <td colspan="3" class="no-border-bottom">Relato:</td>
        </tr>
        <tr>
            <td colspan="3" class="no-border-top"><p>{!! nl2br(e($atencion->relato)) !!}</p></td>
        </tr>
        <tr>
            <td colspan="3" class="no-border-bottom">Tratamiento recibido antes de la hospitalización:</td>
        </tr>
        <tr>
            <td colspan="3" class="no-border-top"><p>{!! nl2br(e($atencion->tratamiento_recibido_antes)) !!}</p></td>
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
    <br>
    <table border="0"> 
        <tr>
            <td class="subtitulo_2"><b>3.- ANTECEDENTES PERSONALES</b></td>
        </tr>
    </table>

    <table border="1" class="tbl_antecedentes">
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
    <table border="1" class="tbl_antecedentes">
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

    <table border="1" class="tbl_antecedentes">
        <tr>
            <td colspan="3" style="font-weight: bold;">Fisiológicos</td>
        </tr>
        <tr>
            <td style="width: 40%">Nacimiento: <span>{{$atencion->antc_9}}</span></td>
            <td style="width: 30%">Desarrollo Psicomotor: <span>{{$atencion->antc_10}}</span></td>
            <td style="width: 30%">Vacunas: <span>{{$atencion->antc_11}}</span></td>
        </tr>
        <tr>
            <td style="width: 50%">Inicio de relaciones sexuales: <span>{{$atencion->antc_12}}</span></td>
            <td colspan="2" style="width: 50%">Comportamiento Sexual: <span>{{$atencion->antc_13}}</span></td>
        </tr>
        <tr>
            <td style="width: 35%">Menarquía: <span>{{$atencion->antc_14}}</span></td>
            <td style="width: 35%">Régimen Catamenial: <span>{{$atencion->antc_15}}</span></td>
            <td style="width: 30%">Fecha de última regla: <span>{{$atencion->antc_16}}</span></td>
        </tr>
        <tr>
            <td style="width: 50%">Método Anticonceptivo: <span>{{$atencion->antc_17}}</span></td>
            <td colspan="2" style="width: 50%">Uso de hormonas: <span>{{$atencion->antc_18}}</span></td>
        </tr>
        <tr>
            <td style="width: 25%">Gesta: <span>{{$atencion->antc_19}}</span></td>
            <td colspan="2" style="width: 75%">PARA: <span>{{$atencion->antc_20}}</span></td>
        </tr>
    </table>

    <table border="1" class="tbl_antecedentes">
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

    <table border="1" class="tbl_antecedentes">
        <tr>
            <td><b>FAMILIARES</b></td>
        </tr>
        <tr>
            <td><p style="margin:0;">{!! nl2br(e($prestacion->antecedente_familiar)) !!}</p></td>
        </tr>
    </table>
    <br>
    <table border="0">
        <tr>
            <td colspan="4" class="subtitulo_1"><b>II.- EXAMEN FÍSICO</b></td>
        </tr>
    </table>
       
    <table border="0">
        <tr>
            <td colspan="4" class="subtitulo_2"><b>1.- FUNCIONES VITALES</b></td>
        </tr>
    </table>
     
    <table id="tbl_hospitalizacion_atencion" class="tbl_antecedentes">
        <tr>
            <td><span>Presión arterial: </span>{{ $prestacion->presion_arterial_x }} / {{$prestacion->presion_arterial_y}}</td>
            <td><span>Frecuencia Cardiaca: </span>{{ $prestacion->frecuencia_cardiaca }}</td>
            <td><span>Frecuencia Respiratoria: </span>{{ $prestacion->frecuencia_respiratoria }}</td>
            <td><span>Temperatura: </span>{{ $prestacion->temperatura }}</td>
        </tr>
        <tr>
            <td><span>Tamaño de Cintura: </span>{{ $prestacion->tamano_cintura_cm }}</td>
            <td><span>SaO2: </span>{{ $prestacion->sa02_porcentaje }}</td>
            <td><span>FiO2: </span>{{ $prestacion->fi02 }}</td>
            <td><span>Frecuencia Cardiaca Fetal: </span>{{ $prestacion->frecuencia_cardiaca_fetal }}</td>
        </tr>
        <tr>
            <td><span>Talla: </span>{{ $prestacion->talla_cm }}</td>
            <td><span>Peso: </span>{{ $prestacion->peso_kg }}</td>
            <td colspan="2"></td>
        </tr>
    </table>
    <br>
    <table>
        <tr>
            <td colspan="4" class="subtitulo_2" style="text-align: left;"><b>2.- EXAMEN GENERAL</b></td>
        </tr>
    </table>
       

    <table border="1" class="tbl_antecedentes">
        <tr>
            <td colspan="4" style="text-align: left;">Ectoscopía: <span>{!! nl2br(e($atencion->exam_gen_1)) !!}</span></td>
        </tr>
        <tr>
            <td style="width: 30%; text-align: left;">Tipo morfológico: <span>{{$atencion->exam_gen_2}}</span></td>
            <td style="width: 30%; text-align: left;">Fascies: <span>{{$atencion->exam_gen_3}}</span></td>
            <td style="width: 20%; text-align: left;">Talla: <span>{{$atencion->exam_gen_4}}</span></td>
            <td style="width: 20%; text-align: left;">Peso: <span>{{$atencion->exam_gen_5}}</span></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: left;">Piel y faneras: <span>{{$atencion->exam_gen_6}}</span></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: left;">Tejido celular subcútaneo: <span>{{$atencion->exam_gen_7}}</span></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: left;">Sistema Linfático: <span>{!! nl2br(e($atencion->exam_gen_8)) !!}</span></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: left;">
                Sistema Locomotor: <span>{!! nl2br(e($atencion->exam_gen_9)) !!}</span>
                @if($atencion->ruta_img_exam_gen_9 != null)
                <div style="text-align: center; width: 100%;">
                    <img src="images/atenciones/{{$atencion->ruta_img_exam_gen_9}}" alt="" style="max-width: 680px;">
                </div>
                @endif
            </td>
        </tr>
    </table>
    <br>
    <table border="0">
        <tr>
            <td class="subtitulo_2"><b>3.- EXAMEN REGIONAL</b></td>
        </tr>
    </table>
    <table border="1" class="tbl_antecedentes">
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
    <br>
    <table border="0">
        <tr>
            <td colspan="3" class="subtitulo_1"><b>III.- DIAGNÓSTICO</b></td>
        </tr>
    </table>
       
     <table style="width: 100%;" border="0" class="tbl_diagnostico">
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
        @if ($dx->id_prestacion == $prestacion->id)
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
        @endif
        @endforeach
    </table>

    <table border="0">
        <tr>
            <td class="subtitulo_1"><b>IV.- TRATAMIENTO</b></td>
        </tr>
        <tr>
            <td><p>{!! nl2br(e($prestacion->indicacion_terapeutica)) !!}</p></td>
        </tr>
    </table>

    <table border="0">
        <tr>
            <td class="subtitulo_1"><b>V.- PLAN DE TRABAJO</b></td>
        </tr>
        <tr>
            <td class="subtitulo_2"><b>1.- EXÁMENES DE AYUDA DIAGNÓSTICA</b></td>
        </tr>
        <tr>
            <td><p>{!! nl2br(e($prestacion->examen_ayuda_diagnostica)) !!}</p></td>
        </tr>
    </table>
    
    <table border="0" class="tbl_diagnostico">
        <tr>
            <td colspan="4" class="subtitulo_2"><b>PROCEDIMIENTO</b></td>
        </tr>
        <?php
        $i = 1;
        ?>
        @foreach($procedimientos as $cpms)
        @if ($cpms->id_prestacion == $prestacion->id)
        <tr>
            <td style="width:5%; border-bottom: 1px solid gray;"><b>{{ $i }}.-</b></td>
            <td style="width:10%; border-bottom: 1px solid gray;">{{ $cpms->cod_cpms }}</td>
            <td style="width:70%; border-bottom: 1px solid gray;"><p>{{ $cpms->descripcion_cpms }}</p></td>
            <td style="width:15%; border-bottom: 1px solid gray;"><b>Cantidad:</b> {{ $cpms->cantidad }}</td>
            <?php
            $i++;
            ?>
        </tr>
        @endif
        @endforeach
    </table>

    <table border="0" class="tbl_antecedentes">
        <tr>
            <td class="subtitulo_2 no-border-bottom"><b>2.- PROCEDIMIENTOS MÉDICO - QUIRÚRGICOS</b></td>
        </tr>
        <tr>
            <td class="no-border-top"><p>{!! nl2br(e($prestacion->procedimiento_medico_quirurgico)) !!}</p></td>
        </tr>
    </table>

    <br>
    <table border="0">
        <tr>
            <td class="subtitulo_2"><b>MEDICO ENCARGADO</b></td>
        </tr>
    </table>

    <table border="1" class="tbl_antecedentes">
        <tr>
            <td><b>APELLIDOS Y NOMBRES:</b></td>
            <td colspan="3"><span>{{ $prestacion->profesional_paterno }} {{ $prestacion->profesional_materno }} {{ $prestacion->profesional_nombre }}</span></td>
        </tr>
        <tr>
            <td style="width:23%; padding-top: 5px;"><b>PROFESIÓN:</b></td>
            <td style=""><span>{{ $prestacion->profesional_profesion }}</span></td>
            <td style="width:20%"><b>N° COLEGIATURA:</b></td>
            <td style="width:10%"><span>{{ $prestacion->profesional_colegiatura }}</span></td>
        </tr>
    </table>
    <br>
    <table border="0">
        <tr>
            <td class="subtitulo_2"><b>RECETAS VALE</b></td>
        </tr>
    </table>

    <table style="font-size:10px;" class="tbl_antecedentes">
        @foreach ($recetas as $receta)
            @if ($receta->id_prestacion == $prestacion->id)
            <tr>
                <td colspan="6">FARMACIA: <span>{{$receta->farmacia}}</span></td>
                <td>N°: <span>{{$receta->nro_receta}}</span></td>
            </tr>
            <tr>
                <th>N°</th>
                <th>PRODUCTO</th>
                <th>UNIDAD<br>MEDIDA</th>
                <th>CANT.</th>
                <th>DÍAS</th>
                <th>VÍA<br>ADMINISTRACIÓN</th>
                <th>POSOLOGÍA</th>
            </tr>
            <?php $i=1; ?>
            @foreach ($receta_productos as $row)
                @if ($receta->id == $row->id_receta)
                <tr>
                    <td>{{$i}}.-</td>
                    <td><span>{{$row->codigo}} - {{$row->nombre}}
                        <?php 
                            if($row->codigo=='59075700'):
                            echo "(".$row->cantidad_prescrita."L)";
                            endif; 
                        ?>
                        </span>
                    </td>
                    <td style="text-align:center"><span>{{$row->abreviatura}}</span></td>
                    <td style="text-align:center"><span><?php if($row->codigo=='59075700' || $row->codigo=='PF00718'):
                        echo $row->cantidad_prescrita/1000;
                        else:
                        echo $row->cantidad_prescrita;
                        endif; ?>
                        </span>
                    </td>
                    <td style="text-align:center"><span>{{$row->tiempo_periodo}}</span></td>
                    <td style="text-align:center"><span>{{$row->via}}</span></td>
                    <td><span>{{$row->posologia}}</span></td>
                </tr>
                <?php $i++; ?>
                @endif
            @endforeach
            <tr>
                <td colspan="7" class="no-border-last-row">&nbsp;</td>
            </tr>
            @endif
        @endforeach
    </table>

    <table border="0">
        <tr>
            <td class="subtitulo_2"><b>ORDEN DE LABORATORIO</b></td>
        </tr>
    </table>

    <table style="font-size:10px;" clas="tbl_antecedentes">
        @foreach ($ordenes_laboratorio as $orden_lab)
            @if ($orden_lab->id_prestacion == $prestacion->id)
            <tr>
                <td colspan="4"></td>
                <td>N°: <span>{{$orden_lab->id}}</span></td>
            </tr>
            <tr>
                <th>N°</th>
                <th>CÓDIGO</th>
                <th>NOMBRE EXAMEN</th>
                <th>OBSERVACIÓN</th>
                <th>CANTIDAD</th>
            </tr>
            <?php $i=1; ?>
            @foreach ($orden_lab_examenes as $examen)
                @if ($orden_lab->id == $examen->id_orden)
                <tr>
                    <td>{{$i}}.-</td>
                    <td><span>{{$examen->codigo}}</span></td>
                    <td><span>{{$examen->nombre}}</span></td>
                    <td><span>{{$examen->observacion}}</span></td>
                    <td><span>{{$examen->cantidad}}</span></td>
                </tr>
                <?php $i++; ?>
                @endif
            @endforeach
            <tr>
                <td colspan="5" class="no-border-last-row">&nbsp;</td>
            </tr>
            @endif
        @endforeach
    </table>

    <table border="0" >
        <tr>
            <td class="subtitulo_2"><b>ORDEN DE IMAGENOLOGIA</b></td>
        </tr>
    </table>

    <table style="font-size:10px;" class="tbl_antecedentes">
        @foreach ($ordenes_imagenologia as $orden_img)
            @if ($orden_img->id_prestacion == $prestacion->id)
            <tr>
                <td colspan="6"></td>
                <td>N°: <span>{{$orden_img->id}}</span></td>
            </tr>
            <tr>
                <th>N°</th>
                <th>CÓDIGO</th>
                <th>NOMBRE EXAMEN</th>
                <th>COMPARATIVO</th>
                <th>LADO</th>
                <th>OBSERVACIÓN</th>
                <th>CANTIDAD</th>
            </tr>
            <?php $i=1; ?>
            @foreach ($orden_img_examenes as $examen)
                @if ($orden_img->id == $examen->id_orden)
                <tr>
                    <td>{{$i}}.-</td>
                    <td><span>{{$examen->codigo}}</span></td>
                    <td><span>{{$examen->nombre}}</span></td>
                    <td><span>
                    <?php 
                    if($examen->comparativo_radio!=null):
                        if($examen->comparativo_radio==1):
                            echo "SI";
                        else:
                            echo "NO";
                        endif;
                    endif;
                    ?>
                    </span>
                    </td>
                    <td><span>
                    <?php 
                    if($examen->lado_radio != null):
                        if($examen->lado_radio == 1):
                            echo "Izquierdo";
                        else:
                            echo "Derecho";
                        endif;
                    endif;
                    ?>
                    </span>
                    </td>
                    <td><span>
                    <?php 
                    if($examen->observacion!='0'):
                        echo $examen->observacion;
                    endif;
                    ?>
                    </span>
                    </td>
                    <td><span>{{$examen->cantidad}}</span></td>
                </tr>
                <?php $i++; ?>
                @endif
            @endforeach
            <tr>
                <td colspan="7" class="no-border-last-row">&nbsp;</td>
            </tr>
            @endif
        @endforeach
    </table>

    <table border="0" >
        <tr>
            <td class="subtitulo_2"><b>INTERCONSULTA</b></td>
        </tr>
    </table>

    <table style="font-size:10px;" class="tbl_antecedentes">
        <?php $i=1; ?>
        @foreach ($interconsultas as $interconsulta)
            @if ($interconsulta->id_prestacion == $prestacion->id)
            <tr>
                <td rowspan="2">{{$i}}.-</td>
                <td>SERVICIO: </td>
                <td><span>{{$interconsulta->codigo_interconsulta}} - {{$interconsulta->servicio}}</span></td>
            </tr>
            <tr>
                <td>MOTIVO: </td>
                <td><p>{!! nl2br(e($interconsulta->motivo_interconsulta)) !!}</p></td>
            </tr>
            <tr>
                <td colspan="3" class="no-border-last-row">&nbsp;</td>
            </tr>
            <?php $i++; ?>
            @endif
        @endforeach
    </table>

    <div style="page-break-after:always;"></div>

        <table border="0" id="tbl_hospitalizacion_atencion" >
            <tr>
                <td colspan="4" class="subtitulo_1"><b>VI.- EVOLUCIONES</b></td>
            </tr>
        </table>
        @foreach($evoluciones as $evolucion)
            @if ($evolucion->id_prestacion == $prestacion->id)
            <table border="0" id="" >
                <tr>
                    <td>Fecha y Hora de Evolución de la atención: <b>{{ date("d/m/Y H:i:s", strtotime($evolucion->created_at))}}</b></td>
                </tr>
                <tr>
                    <td>Código SITEDS: <b>{{ $evolucion->cod_siteds_susalud}}</b></td>
                </tr>
            </table>
    
            <table border="0">
                <tr>
                    <td colspan="4" class="subtitulo_2"><b>FUNCIONES VITALES</b></td>
                </tr>
            </table>
            <table border="1" class="tbl_antecedentes">
                <tr>
                    <td>Presión arterial: <b>{{ $evolucion->presion_arterial_x }} / {{$evolucion->presion_arterial_y}}</b></td>
                    <td>Frecuencia Cardiaca: <b>{{ $evolucion->frecuencia_cardiaca }}</b></td>
                    <td>Frecuencia Respiratoria: <b>{{ $evolucion->frecuencia_respiratoria }}</b></td>
                    <td>Temperatura: <b>{{ $evolucion->temperatura }}</b></td>
                </tr>
                <tr>
                    <td>Talla: <b>{{ $evolucion->talla_cm }}</b></td>
                    <td>Peso: <b>{{ $evolucion->peso_kg }}</b></td>
                    <td>Tamaño de Cintura: <b>{{ $evolucion->tamano_cintura_cm }}</b></td>
                    <td>SaO2: <b>{{ $evolucion->sa02_porcentaje }}</b></td>
                </tr>
                <tr>
                    <td>FiO2: <b>{{ $evolucion->fi02 }}</b></td>
                    <td>Frecuencia Cardiaca Fetal: <b>{{ $evolucion->frecuencia_cardiaca_fetal }}</b></td>
                    <td>Diuresis: <b>{{ $evolucion->diuresis }}</b></td>
                    <td>Balance Hídrico: <b>{{ $evolucion->balance_hidrico }}</b></td>
                </tr>
            </table>

            <table border="1" class="tbl_antecedentes">
                <tr>
                    <td class="subtitulo_2"><b>APRECIACIÓN OBJETIVA</b></td>
                </tr>
                <tr>
                    <td><p>{!! nl2br(e($evolucion->apreciacion_objetiva)) !!}</p></td>
                </tr>
            </table>
    
            <table border="1" class="tbl_antecedentes">
                <tr>
                    <td class="subtitulo_2"><b>APRECIACIÓN SUBJETIVA</b></td>
                </tr>
                <tr>
                    <td><p>{!! nl2br(e($evolucion->apreciacion_subjetiva)) !!}</p></td>
                </tr>
            </table>
    
            <table border="1" class="tbl_antecedentes">
                <tr>
                    <td class="subtitulo_2"><b>VERIFICACIÓN DEL TRATAMIENTO Y DIETA</b></td>
                </tr>
                <tr>
                    <td><p>{!! nl2br(e($evolucion->verificacion_tratamiento)) !!}</p></td>
                </tr>
            </table>
    
            <table border="1" class="tbl_antecedentes">
                <tr>
                    <td class="subtitulo_2"><b>INTERPRETACIÓN DE EXAMENES Y COMENTARIO</b></td>
                </tr>
                <tr>
                    <td><p>{!! nl2br(e($evolucion->interpretacion_examenes)) !!}</p></td>
                </tr>
            </table>
    
            <table border="1" class="tbl_antecedentes">
                <tr>
                    <td class="subtitulo_2"><b>TERAPEUTICA Y PLAN DE TRABAJO</b></td>
                </tr>
                <tr>
                    <td><p>{!! nl2br(e($evolucion->plan_trabajo)) !!}</p></td>
                </tr>
            </table>

            <table border="0">
                <tr>
                    <td colspan="3" class="subtitulo_1"><b>DIAGNÓSTICO</b></td>
                </tr>
            </table>

            <table border="1" class="tbl_antecedentes">
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
                @foreach($diagnosticos_evoluciones as $dx)
                    @if($evolucion->id == $dx->id_evolucion && $dx->id_clasificacion_diagnosticos != 9)
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
                            <td style="width:55%; border-bottom: 1px solid gray;"><p>{{ $dx->nombre_dx }}.</p></td>
                            <td style="width:15%; border-bottom: 1px solid gray;">{{ $tipo }}</td>
                            <td style="width:15%; border-bottom: 1px solid gray;">{{ $categoria }}</td>
                            <?php
                            $i++;
                            ?>
                        </tr>
                    @endif
                @endforeach
            </table>
    
            <table border="1" class="tbl_antecedentes">
                <tr>
                    <td colspan="4" class="subtitulo_2"><b>PROCEDIMIENTO</b></td>
                </tr>
                @foreach($procedimientos_evoluciones as $cpms)
                @if($evolucion->id == $cpms->id_evolucion)
                <tr>
                    <?php
                    $i = 1;
                    ?>
                    <td style="width:5%; vertical-align:top;"><b>{{ $i }}.-</b></td>
                    <td style="padding-left:2px;"><p>{{ $cpms->descripcion_cpms }}</p></td>
                    <td style="width:15%; vertical-align:top;"><b>CPMS:</b> {{ $cpms->cod_cpms }}</td>
                    <td style="width:15%; vertical-align:top;"><b>Cantidad:</b> {{ $cpms->cantidad }}</td>
                    <?php
                    $i++;
                    ?>
                </tr>
                @endif
                @endforeach
            </table>

            <table border="1" class="tbl_antecedentes">
                <tr>
                    <td class="subtitulo_2"><b>INDICACIONES TERAPÉUTICAS</b></td>
                </tr>
                <tr>
                    <td><p>{!! nl2br(e($evolucion->tratamiento_auxiliar)) !!}</p></td>
                </tr>
            </table>

            <table border="0" >
                <tr>
                    <td class="subtitulo_2"><b>MEDICO ENCARGADO</b></td>
                </tr>
            </table>
    
            <table style="" class="tbl_ordenes">
                <!-- <tr>
                    <td><b>Apellido Paterno</b></td>
                    <td><b>Apellido Materno</b></td>
                    <td><b>Nombres</b></td>
                    <td style="width:15%"><b>N° Colegiatura</b></td>
                </tr>
                <tr>
                    <td>{{ $evolucion->profesional_paterno }}</td>
                    <td>{{ $evolucion->profesional_materno }}</td>
                    <td>{{ $evolucion->profesional_nombre }}</td>
                    <td>{{ $evolucion->profesional_colegiatura }}</td>
                </tr> -->
                <tr>
                    <td><b>APELLIDOS Y NOMBRES:</b></td>
                    <td colspan="3">{{ $evolucion->profesional_paterno }} {{ $evolucion->profesional_materno }} {{ $evolucion->profesional_nombre }}</td>
                </tr>
                <tr>
                    <td style="width:23%; padding-top: 5px;"><b>PROFESIÓN:</b></td>
                    <td style="">{{ $evolucion->profesional_profesion }}</td>
                    <td style="width:17%"><b>N° COLEGIATURA:</b></td>
                    <td style="width:10%">{{ $evolucion->profesional_colegiatura }}</td>
                </tr>
            </table>
        
            <table border="0">
                <tr>
                    <td class="subtitulo_2"><b>RECETAS VALE</b></td>
                </tr>
            </table>
    
            <table style="font-size:10px;" class="tbl_ordenes">
                @foreach ($recetas_evolucion as $receta)
                    @if ($evolucion->id == $receta->id_evaluacion)
                        <tr>
                            <td colspan="6"><b>FARMACIA: </b>{{$receta->farmacia}}</td>
                            <td><b>N°: </b>{{$receta->nro_receta}}</td>
                        </tr>
                        <tr>
                            <th>N°</th>
                            <th>PRODUCTO</th>
                            <th>UNIDAD<br>MEDIDA</th>
                            <th>CANT.</th>
                            <th>DÍAS</th>
                            <th>VÍA<br>ADMINISTRACIÓN</th>
                            <th>POSOLOGÍA</th>
                        </tr>
                        <?php $i=1; ?>
                        @foreach ($receta_productos_evolucion as $row)
                            @if ($receta->id == $row->id_receta)
                            <tr>
                                <td>{{$i}}.-</td>
                                <td>{{$row->codigo}} - {{$row->nombre}}
                                    <?php 
                                        if($row->codigo=='59075700'):
                                        echo "(".$row->cantidad_prescrita."L)";
                                        endif; 
                                    ?>
                                </td>
                                <td style="text-align:center">{{$row->abreviatura}}</td>
                                <td style="text-align:center"><?php if($row->codigo=='59075700' || $row->codigo=='PF00718' || $row->codigo=='PF01047' || $row->codigo=='PF01007' || $row->codigo=='PF01008'):
                                    echo $row->cantidad_prescrita/1000;
                                    else:
                                    echo $row->cantidad_prescrita;
                                    endif; ?>
                                </td>
                                <td style="text-align:center">{{$row->tiempo_periodo}}</td>
                                <td style="text-align:center">{{$row->via}}</td>
                                <td>{{$row->posologia}}</td>
                            </tr>
                            <?php $i++; ?>
                            @endif
                        @endforeach
                        <tr>
                            <td colspan="7" class="no-border-last-row">&nbsp;</td>
                        </tr>
                    @endif
                @endforeach
            </table>
    
            <table border="0" >
                <tr>
                    <td class="subtitulo_2"><b>ORDEN DE LABORATORIO</b></td>
                </tr>
            </table>
    
            <table style="font-size:10px;" class="tbl_ordenes">
                @foreach ($ordenes_laboratorio_evolucion as $orden_lab)
                    @if ($evolucion->id == $orden_lab->id_evaluacion)
                    <tr>
                        <td colspan="4"></td>
                        <td><b>N°: </b>{{$orden_lab->id}}</td>
                    </tr>
                    <tr>
                        <th>N°</th>
                        <th>CÓDIGO</th>
                        <th>NOMBRE EXAMEN</th>
                        <th>OBSERVACIÓN</th>
                        <th>CANTIDAD</th>
                    </tr>
                    <?php $i=1; ?>
                    @foreach ($orden_lab_examenes_evolucion as $examen)
                        @if ($orden_lab->id == $examen->id_orden)
                        <tr>
                            <td>{{$i}}.-</td>
                            <td>{{$examen->codigo}}</td>
                            <td>{{$examen->nombre}}</td>
                            <td>{{$examen->observacion}}</td>
                            <td>{{$examen->cantidad}}</td>
                        </tr>
                        <?php $i++; ?>
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="5" class="no-border-last-row">&nbsp;</td>
                    </tr>
                    @endif
                @endforeach
            </table>
    
            <table border="0" >
                <tr>
                    <td class="subtitulo_2"><b>ORDEN DE IMAGENOLOGIA</b></td>
                </tr>
            </table>
    
            <table style="font-size:10px;" class="tbl_ordenes">
                @foreach ($ordenes_imagenologia_evolucion as $orden_img)
                    @if ($evolucion->id == $orden_img->id_evaluacion)
                        <tr>
                            <td colspan="6"></td>
                            <td><b>N°: </b>{{$orden_img->id}}</td>
                        </tr>
                        <tr>
                            <th>N°</th>
                            <th>CÓDIGO</th>
                            <th>NOMBRE EXAMEN</th>
                            <th>COMPARATIVO</th>
                            <th>LADO</th>
                            <th>OBSERVACIÓN</th>
                            <th>CANTIDAD</th>
                        </tr>
                        <?php $i=1; ?>
                        @foreach ($orden_img_examenes_evolucion as $examen)
                            @if ($orden_img->id == $examen->id_orden)
                            <tr>
                                <td><b>{{$i}}.-</b></td>
                                <td>{{$examen->codigo}}</td>
                                <td>{{$examen->nombre}}</td>
                                <td>
                                <?php 
                                if($examen->comparativo_radio!=null):
                                    if($examen->comparativo_radio==1):
                                        echo "SI";
                                    else:
                                        echo "NO";
                                    endif;
                                endif;
                                ?>
                                </td>
                                <td>
                                <?php 
                                if($examen->lado_radio != null):
                                    if($examen->lado_radio == 1):
                                        echo "Izquierdo";
                                    else:
                                        echo "Derecho";
                                    endif;
                                endif;
                                ?>
                                </td>
                                <td>
                                <?php 
                                if($examen->observacion!='0'):
                                    echo $examen->observacion;
                                endif;
                                ?>
                                </td>
                                <td>{{$examen->cantidad}}</td>
                            </tr>
                            <?php $i++; ?>
                            @endif
                        @endforeach
                        <tr>
                            <td colspan="7" class="no-border-last-row">&nbsp;</td>
                        </tr>
                    @endif
                @endforeach
            </table>
    
            <table border="0" >
                <tr>
                    <td class="subtitulo_2"><b>INTERCONSULTA</b></td>
                </tr>
            </table>
    
            <table style="font-size:10px;" class="tbl_ordenes">
                <?php $i=1; ?>
                @foreach ($interconsultas_evolucion as $interconsulta)
                    @if ($evolucion->id == $interconsulta->id_evolucion)
                    <tr>
                        <td colspan="2"><b>{{$i}}.-</b></td>
                    </tr>
                    <tr>
                        <td><b>SERVICIO: </b></td>
                        <td>{{$interconsulta->codigo_interconsulta}} - {{$interconsulta->servicio}}</th>
                    </tr>
                    <tr>
                        <td><b>MOTIVO: </b></td>
                        <td>{{$interconsulta->motivo_interconsulta}}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="no-border-last-row">&nbsp;</td>
                    </tr>
                    <?php $i++; ?>
                    @endif
                @endforeach
            </table>
            @endif
        @endforeach

    <!--SALTAO DE PAGINA PARA NUEVAS HISTORIAS CLINICA EN HOJA NUEVA -->
    <div style="page-break-before:always;"></div>
    <?php
                break;
            }
        }
    }
    ?>

    <?php
    $tipo_egreso = "";
    if($hospitalizacion->id_tipo_egreso=="1"){
        $tipo_egreso = "ALTA";
    }else if($hospitalizacion->id_tipo_egreso=="2"){
        $tipo_egreso = "HOSPITALIZACION";
    }else if($hospitalizacion->id_tipo_egreso=="3"){
        $tipo_egreso = "TRASNFERIDO";
    }else if($hospitalizacion->id_tipo_egreso=="4"){
        $tipo_egreso = "FALLECIDO";
    }else if($hospitalizacion->id_tipo_egreso=="7"){
        $tipo_egreso = "RETIRO VOLUNTARIO";
    }else if($hospitalizacion->id_tipo_egreso=="6"){
        $tipo_egreso = "FUGA";
    }

    $condicion_egreso = "";
    if($hospitalizacion->id_condicion_alta=="1"){
        $condicion_egreso = "MEJORADO";
    }else if($hospitalizacion->id_condicion_alta=="2"){
        $condicion_egreso = "CURADO";
    }else if($hospitalizacion->id_condicion_alta=="3"){
        $condicion_egreso = "TRASLADO";
    }else if($hospitalizacion->id_condicion_alta=="4"){
        $condicion_egreso = "RETIRO VOLUNTARIO";
    }else if($hospitalizacion->id_condicion_alta=="5"){
        $condicion_egreso = "FUGADO";
    }else if($hospitalizacion->id_condicion_alta=="6"){
        $condicion_egreso = "FALLECIDO";
    }
    
    ?>

        <table border="0">
            <tr>
                <td colspan="4" class="subtitulo_1"><b>V.- ALTA</b></td>
            </tr>
        </table>

        <table border="0" id="" >
            <tr>
                <td class="subtitulo_2"><b>RESUMEN DE LA ENFERMEDAD ACTUAL, EXAMEN FÍSICO, EXÁMENES AUXILIARES, EVOLUCIÓN Y TRATAMIENTO</b></td>
            </tr>
            <tr>
                <td><p>{!! nl2br(e($hospitalizacion->egreso_resumen)) !!}</p></td>
            </tr>
        </table>
    
        <table border="0" id="" >
            <tr>
                <td class="subtitulo_2"><b>COMPLICACIONES</b></td>
            </tr>
            <tr>
                <td><p>{!! nl2br(e($hospitalizacion->egreso_complicacion)) !!}</p></td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="subtitulo_2"><b>DIAGNÓSTICO</b></td>
            </tr>
        </table>

        <table class="tbl_antecedentes">
            @if($hospitalizacion->id_diag_cab != null)
            <tr>
                <td style="width:5%">1.-</td>
                <td style="width:70%"><p>{{ $hospitalizacion->nombre_cie_egreso_1 }}</p></td>
                <td style="width:10%">CIE: <span>{{ $hospitalizacion->codigo_cie_egreso_1 }}</span></td>
                <td style="width:15%"><span>PRINCIPAL</span></td>
            </tr>
            @endif
            @if($hospitalizacion->id_diag_cuer1 != null)
            <tr>
                <td style="width:5%">2.-</td>
                <td style="width:70%"><p>{{ $hospitalizacion->nombre_cie_egreso_2 }}</p></td>
                <td style="width:10%">CIE: <span>{{ $hospitalizacion->codigo_cie_egreso_2 }}</span></td>
                <td style="width:15%"><span>PRINCIPAL</span></td>
            </tr>
            @endif
            @if($hospitalizacion->id_diag_cuer2 != null)
            <tr>
                <td style="width:5%">3.-</td>
                <td style="width:70%"><p>{{ $hospitalizacion->nombre_cie_egreso_3 }}</p></td>
                <td style="width:10%">CIE: <span>{{ $hospitalizacion->codigo_cie_egreso_3 }}</span></td>
                <td style="width:15%"><span>PRINCIPAL</span></td>
            </tr>
            @endif
        </table>
        <br>
        <table class="tbl_antecedentes">
            <tr>
                <td style="width:200px"><b>FECHA Y HORA DE EGRESO</b></td>
                <td style="width:150px"><b>TIPO DE EGRESO</b></td>
                <td style="width:150px"><b>CONDICION DE EGRESO</b></td>
            </tr>
            <tr>
                <td><span>{{ date("d/m/Y", strtotime($hospitalizacion->fecha_salida)) }} {{ date("H:i:s A", strtotime($hospitalizacion->hora_egreso)) }}</span></td>
                <td><span>{{ $tipo_egreso }}</span></td>
                <td><span>{{ $condicion_egreso }}</span></td>
            </tr>
            @if ($hospitalizacion->id_tipo_egreso == 3)
            <tr><td class="interlineado_tabla_seccion_alta">&nbsp;</td></tr>
            <tr>
                <td colspan="2"><b>ESTABLECIMIENTO DE TRASLADO</b></td>
                <td colspan="2"><b>COMENTARIO DEL TRASLADO</b></td>
            </tr>
            <tr>
                <td colspan="2">{{ $hospitalizacion->establecimiento_traslado }}</td>
                <td colspan="2">{{ $hospitalizacion->obs_traslado }}</td>
            </tr>
            <tr><td class="interlineado_tabla_seccion_alta">&nbsp;</td></tr>
            <tr>
                <td colspan="1"><b>ESPECIALIDAD DE DESTINO</b></td>
                <td colspan="3"><b>DETALLE</b></td>
            </tr>
            <tr>
                <td colspan="1">{{ $hospitalizacion->especialida_destino }}</td>
                <td colspan="3">{{ $hospitalizacion->especialidad_destino_otro }}</td>
            </tr>
            <tr><td class="interlineado_tabla_seccion_alta">&nbsp;</td></tr>
            <tr>
                <td colspan="4"><b>CONDICION DEL PACIENTE A LA HORA DEL TRASLADO</b></td>
            </tr>
            <tr>
                <td colspan="4">{{ $hospitalizacion->condicion_traslado }}</td>
            </tr>
            @elseif ($hospitalizacion->id_tipo_egreso == 4)
            <tr><td class="interlineado_tabla_seccion_alta">&nbsp;</td></tr>
            <tr>
                <td colspan="4"><b>FECHA Y HORA DE DEFUNCIÓN</b></td>
            </tr>
            <tr>
                <td colspan="4">{{ date("d/m/Y", strtotime($hospitalizacion->fecha_defuncion)) }} {{ date("H:i:s", strtotime($hospitalizacion->hora_defuncion)) }}</td>
            </tr>
            <tr><td class="interlineado_tabla_seccion_alta">&nbsp;</td></tr>
            <tr>
                <td><b>SE REALIZÓ NECROPSIA</b></td>
                <td colspan="3"><b>CAUSAS DE LA MUERTE</b></td>
            </tr>
            <tr>
                <td>{{ $hospitalizacion->necropsia }}</td>
                <td colspan="3">{{ $hospitalizacion->causas_muerte }}</td>
            </tr>
            @if ($hospitalizacion->id_servicio_sala_egreso == 77)
            <tr><td class="interlineado_tabla_seccion_alta">&nbsp;</td></tr>
            <tr>
                <td colspan="4"><b>MUERTE PERINATAL</b></td>
            </tr>
            <tr>
                <td colspan="4"><b>{{ $hospitalizacion->muerte_perinatal }}</b></td>
            </tr>
            @endif
            @elseif ($hospitalizacion->id_tipo_egreso == 7 || $hospitalizacion->id_tipo_egreso == 8)
            <tr><td class="interlineado_tabla_seccion_alta">&nbsp;</td></tr>
            <tr>
                <td colspan="4"><b>OBSERVACION</b></td>
            </tr>
            <tr>
                <td colspan="2">{{ $hospitalizacion->observacion_egreso }}</td>
            </tr>
            @endif
        </table>
        <br>
        <table>
            <tr>
                <td class="subtitulo_2"><b>MEDICO ENCARGADO</b></td>
            </tr>
        </table>

        <table class="tbl_antecedentes">
            <tr>
                <td><b>APELLIDOS Y NOMBRES:</b></td>
                <td colspan="3"><span>{{ $hospitalizacion->profesional_egreso_paterno }} {{ $hospitalizacion->profesional_egreso_materno }} {{ $hospitalizacion->profesional_egreso_nombre }}</span></td>
            </tr>
            <tr>
                <td style="width:23%; padding-top: 5px;">PROFESIÓN:</td>
                <td style=""><span>{{ $hospitalizacion->profesional_egreso_profesion }}</span></td>
                <td style="width:20%">N° COLEGIATURA:</td>
                <td style="width:10%"><span>{{ $hospitalizacion->profesional_egreso_colegiatura }}</span></td>
            </tr>
        </table>
    <script type="text/php">
    if ( isset($pdf) ) {
        $pdf->page_script('
            $pdf->text(560, 20, "$PAGE_NUM/$PAGE_COUNT", "Arial", 8);
        ');
    }
</script>
</body>
</html>
