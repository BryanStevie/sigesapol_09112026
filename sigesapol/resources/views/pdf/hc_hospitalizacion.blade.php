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
            margin: 1cm 1.5cm 3cm 1.5cm !important; /* Asegurar espacio suficiente para el footer */
            color: #001028;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px !important;
            font-family: Arial, sans-serif;
        }

        .footer {
            position: fixed;
            bottom: 1.5cm;
            left: 1.5cm;
            right: 1.5cm;
            width: 700px;
            text-align: center;
        } 

        #tbl_hospitalizacion_footer td {
            border: 1px black solid !important;
            font-size: 9px;
            padding: 3px;
            text-align: center;
        }
        .pagina-siguiente {
            page-break-before: always;
        }

        .ultima-pagina {
            page-break-after: avoid !important;
        }
        .marca {
            position: absolute;
            top: -0.2%;
            right: 30%;
            z-index: 2;
            color: rgba(22, 125, 189, 0.1);
            opacity: 0.7; /* Opacidad del 50% */
            font-size: 18px; 
            transform: translate(-50%, -50%); 
            font-weight: lighter;
            font-family: Helvetica,Arial,sans-serif;
        }
        .contenedor {
            position: relative;
            width: 30px; /* Ajusta el ancho según sea necesario */
            height: 17px; /* Ajusta la altura según sea necesario */
        }
        .valor {
            top: 1px;
            position: relative;
            z-index: 1;
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
                <img src="images/logo_pnp.png" alt="" width="40px;"/>
                <p><b>MINISTERIO DEL INTERIOR<br>POLICÍA NACIONAL DEL PERÚ</b></p>
            </td>
            <td></td>
            <td>
                <p><br><br><br><br><b>DIRECCIÓN DE SANIDAD<br>HOSPITAL NACIONAL PNP "LUIS N. SÁENZ"</b></p>
            </td> 
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" style="font-size: 18px;"><b>HISTORIA CLÍNICA DE HOSPITALIZACIÓN</b></td>
        </tr>
    </table>

    <?php  
    $contador = 0;
    $totalAtenciones = count($atenciones);
    foreach ($atenciones as $key => $hospitalizacion) { 
        $contador++;
        $claseExtra = ($contador == $totalAtenciones) ? 'ultima-pagina' : ''; 
    ?>
        
        <?php
            $prestacion = DB::table('prestaciones')
                        ->join('medicos as m', 'prestaciones.id_medico', '=', 'm.id')
                        ->leftJoin('profesiones as p', 'm.id_profesion', '=', 'p.id')
                        ->select('prestaciones.*','m.paterno as profesional_paterno', 'm.materno as profesional_materno', 'm.nombre as profesional_nombre', 'm.colegiatura as profesional_colegiatura','p.nombre as profesional_profesion')
                        ->where('prestaciones.id',$hospitalizacion->id_prestacion)
                        ->first();
            // Obtener la atencion
            $atencion = DB::table('hospitalizacion_atenciones')
                ->where('id_prestacion', $hospitalizacion->id_prestacion) // Corrección aquí
                ->first();

            // Obtener recetas relacionadas
            $recetas = DB::table('receta_vales')
                ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                ->select('receta_vales.*', 'farmacias.nombre as farmacia')
                ->where('receta_vales.id_prestacion', '=', $hospitalizacion->id_prestacion) // Uso de $hospitalizacion directamente
                ->where('receta_vales.estado', '<>', 0)
                ->where('receta_vales.id_evaluacion', '=', 0)
                ->where('receta_vales.id_interconsulta', '=', 0)
                ->orderBy('receta_vales.id', 'asc')
                ->get();
            
                $receta_productos = DB::table('receta_vales')
                ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                ->join('productos', 'producto_recetas.id_producto', '=', 'productos.id')
                ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                ->leftJoin('farmacias', 'producto_recetas.id_farmacia', '=', 'farmacias.id')
                ->leftJoin('via_administracion', 'producto_recetas.id_via_administracion', '=', 'via_administracion.id')
                ->where('receta_vales.id_prestacion', $hospitalizacion->id_prestacion)
                ->select(
                    'receta_vales.id as id_receta',
                    'producto_recetas.id as id_producto_receta',
                    'productos.codigo',
                    'productos.nombre',
                    'productos.id as id_producto',
                    'productos.presentacion_comercial as presentacion',
                    'producto_recetas.*',
                    'unidades.abreviatura',
                    'farmacias.nombre as farmacia',
                    'via_administracion.descripcion as via'
                )
                ->orderBy('receta_vales.id', 'asc')
                ->orderBy('producto_recetas.id', 'asc')
                ->get();
            // PRODUCTOSDE RECETA COMBINADOS 
            $receta_productos_combinados = DB::table('receta_vales')
                ->join('producto_recetas_combinacion', 'receta_vales.id', '=', 'producto_recetas_combinacion.id_receta_vale')
                ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                ->join('productos', 'producto_recetas_combinacion.id_producto', '=', 'productos.id')
                ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                ->leftJoin('via_administracion as va', 'producto_recetas.id_via_administracion', '=', 'va.id')
                ->where('receta_vales.id_prestacion', $hospitalizacion->id_prestacion)
                ->groupBy(
                    'receta_vales.id',
                    'productos.id',
                    'productos.codigo',
                    'productos.nombre',
                    'productos.presentacion_comercial',
                    'unidades.abreviatura'
                )
                ->selectRaw("
                    receta_vales.id AS id_receta,
                    productos.id AS id_producto,
                    productos.codigo,
                    productos.nombre,
                    COALESCE(NULLIF(CAST(productos.presentacion_comercial AS TEXT), '0'), '') AS presentacion,
                    STRING_AGG(DISTINCT producto_recetas.posologia, ', ') AS dosis,
                    STRING_AGG(DISTINCT CAST(producto_recetas.tiempo_periodo AS TEXT), ', ') AS frecuencia,
                    STRING_AGG(DISTINCT va.descripcion, ', ') AS via_administracion,
                    unidades.abreviatura
                ")
                ->orderBy('receta_vales.id', 'asc')
                ->orderBy('productos.id', 'asc')
                ->get();


            
            // FIN RECETAPRODUCTOS COMBINADOS

            $ordenes_laboratorio = DB::table('laboratorios')
                ->where('id_prestacion', $hospitalizacion->id_prestacion)
                ->where('estado', '<>', 0)
                ->where('id_evaluacion', 0)
                ->select(
                    'id',
                    'fecha_atencion',
                    'fecha_firma',
                    'ruta_laboratorio',
                    'flag',
                    'secuencial',
                    'fecha_registro_toma_muestra',
                    'estado',
                    DB::raw("to_char(fecha_registro_toma_muestra, 'DD/MM/YYYY') AS fecha_toma_muestra")
                )
                ->orderBy('id', 'asc')
                ->get();
            
            $orden_lab_examenes = DB::table('prestacion_procedimientos')
                ->join('labnivelatenciones', 'prestacion_procedimientos.id_rua', '=', 'labnivelatenciones.id')
                ->join('procedimientos', 'prestacion_procedimientos.id_procedimiento', '=', 'procedimientos.id')
                ->leftJoin('diagnosticos', 'diagnosticos.id', '=', 'prestacion_procedimientos.id_diagnostico')
                ->where('prestacion_procedimientos.id_prestacion', $hospitalizacion->id_prestacion)
                ->where('prestacion_procedimientos.id_estado_reg', 1)
                ->select(
                    'labnivelatenciones.id as id',
                    'prestacion_procedimientos.id_lab_rad as id_orden',
                    'labnivelatenciones.codigo_lab as codigo',
                    'labnivelatenciones.descripcion_corta as nombre',
                    'procedimientos.descripcion as nombre_completo',
                    'prestacion_procedimientos.cantidad',
                    'prestacion_procedimientos.created_at',
                    'labnivelatenciones.id_procedimiento',
                    'prestacion_procedimientos.observacion',
                    'diagnosticos.codigo as codigo_diagnostico',
                    'diagnosticos.nombre as diagnostico',
                    'prestacion_procedimientos.tipo_muestra_microbiologia',
                    'prestacion_procedimientos.id_solicitud_procedimiento'
                )
                ->orderBy('prestacion_procedimientos.id', 'asc')
                ->get();
            
            $ordenes_imagenologia = DB::table('radiologias')
                ->where('id_prestacion', '=', $hospitalizacion->id_prestacion)
                ->where('estado', '<>', 0)
                ->where('id_evaluacion', '=', 0)
                ->orderBy('id','asc')
                ->get();

            $orden_img_examenes = DB::table('prestacion_procedimientos as t1')
                ->join('procedimientos as t2', 't1.id_procedimiento', '=', 't2.id')
                ->leftJoin('diagnosticos as t3', 't1.id_diagnostico', '=', 't3.id')
                ->where('t1.id_prestacion', $hospitalizacion->id_prestacion)
                ->where('t1.tipo_procedimiento', 3)
                ->where('t1.id_estado_reg', 1)
                ->select(
                    't1.id',
                    't1.id_lab_rad as id_orden',
                    't2.codigo',
                    't2.descripcion as nombre',
                    't1.cantidad',
                    't1.observacion',
                    't3.nombre as diagnostico',
                    't3.codigo as cod_diag',
                    't1.comparativo_radio',
                    't1.lado_radio'
                )
                ->orderBy('t1.id')
                ->get();

            $interconsultas = DB::table('interconsultas as t8')
                ->join('establecimientos as t3', 't8.id_establecimiento', '=', 't3.id')
                ->join('consultorios as t6', 't8.id_servicio', '=', 't6.id')
                ->leftjoin('medicos as tr', 't8.personal_salud_interconsulta', '=', 'tr.id')
                ->where('t8.id_prestacion', $hospitalizacion->id_prestacion)
                ->where('t8.id_evaluacion', 0)
                ->where('t8.id_estado_reg', 1)
                ->select(
                    't8.id',
                    't3.nombre as establecimiento',
                    't8.id_servicio',
                    't6.nombre as servicio',
                    'tr.nombre as profesional_nombre',
                    'tr.paterno as profesional_paterno',
                    'tr.materno as profesional_materno',
                    'tr.colegiatura as profesional_colegiatura',
                    't8.codigo_upss as codigo_interconsulta',
                    't8.motivo_interconsulta',
                    't8.resumen_interconsulta',
                    't8.fecha_firma_interconsulta',
                    't8.id as id_interconsulta',
                    't8.created_at'
                )
                ->orderBy('t8.created_at', 'ASC')
                ->get();

            $diagnosticos = DB::table('receta_diagnosticos as rd')
                ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
                ->select('rd.*', 'd.codigo as cod_dx', 'd.nombre as nombre_dx')
                ->where('rd.id_prestacion', $hospitalizacion->id_prestacion)
                ->where(function ($sql) {
                    $sql->whereNull('rd.id_evaluacion')
                        ->orWhere('rd.id_evaluacion', 0);
                })
                // ->whereNull('rd.id_receta_vale')
                ->orderBy('rd.id')
                ->orderBy('rd.id_categoria')
                ->orderBy('rd.id_tipo_diagnostico')
                ->get();
            
            $procedimientos = DB::table('prestacion_procedimientos as pp')
                ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
                ->select('pp.id', 'pp.cantidad', 'p.codigo as cod_cpms', 'p.descripcion as descripcion_cpms')
                ->where('pp.id_prestacion', $hospitalizacion->id_prestacion)
                ->where('pp.id_evaluacion', 0)
                ->where('pp.tipo_procedimiento', 1)
                ->where('pp.id_estado_reg', 1)
                ->orderBy('pp.id')
                ->get();

            $evoluciones = DB::table('evaluaciones as e')
                ->select('e.*','m.paterno as profesional_paterno', 'm.materno as profesional_materno', 'm.nombre as profesional_nombre', 'm.colegiatura as profesional_colegiatura','p.nombre as profesional_profesion','camas.nombre as cama','consultorios.nombre as servicio_sala')
                ->join('medicos as m', 'e.id_medico', '=', 'm.id')
                ->join('camas','e.cama_estado','=','camas.id')
                ->join('cuartos','camas.id_cuarto','=','cuartos.id')
                ->join('asignacion_servicios','cuartos.id_asignacion_servicio','=','asignacion_servicios.id')
                ->join('consultorios','asignacion_servicios.id_consultorio','=','consultorios.id')
                ->leftJoin('profesiones as p', 'm.id_profesion', '=', 'p.id')
                ->where('e.id_prestacion', $hospitalizacion->id_prestacion)
                ->where('e.id_estado_reg', 1)
                ->orderBy('e.created_at', 'desc')
                ->get();
            $recetas_evolucion = DB::table('receta_vales')
                ->join('farmacias', 'receta_vales.id_farmacia', '=', 'farmacias.id')
                ->select('receta_vales.*', 'farmacias.nombre as farmacia')
                ->where('receta_vales.id_prestacion', '=', $hospitalizacion->id_prestacion)
                ->where('receta_vales.estado', '<>', 0)
                ->orderBy('receta_vales.id_evaluacion', 'desc')
                ->orderBy('receta_vales.id', 'asc')
                ->get();

            $receta_productos_evolucion = DB::table('receta_vales')
                ->join('producto_recetas', 'receta_vales.id', '=', 'producto_recetas.id_receta_vale')
                ->join('productos', 'producto_recetas.id_producto', '=', 'productos.id')
                ->join('unidades', 'productos.id_unidad', '=', 'unidades.id')
                ->leftJoin('farmacias', 'producto_recetas.id_farmacia', '=', 'farmacias.id')
                ->leftJoin('via_administracion', 'producto_recetas.id_via_administracion', '=', 'via_administracion.id')
                ->select(
                    'producto_recetas.id as id_producto_receta',
                    'receta_vales.id_evaluacion as id_evolucion',
                    'producto_recetas.id_receta_vale as id_receta',
                    'productos.codigo',
                    'productos.nombre',
                    'productos.id as id_producto',
                    'productos.presentacion_comercial as presentacion',
                    'producto_recetas.*',
                    'unidades.abreviatura',
                    'farmacias.nombre as farmacia',
                    'via_administracion.descripcion as via'
                )
                // ->where('receta_vales.id_evaluacion', $id_evolucion)
                ->where('receta_vales.id_prestacion', '=', $hospitalizacion->id_prestacion)
                ->orderBy('receta_vales.id_evaluacion', 'desc')
                ->orderBy('producto_recetas.id', 'asc')
                ->get();
                
            $ordenes_laboratorio_evolucion = DB::table('laboratorios')
                ->where('id_prestacion', '=', $hospitalizacion->id_prestacion)
                ->where('estado', '<>', 0)
                ->orderBy('id', 'ASC')
                ->get();

            $orden_lab_examenes_evolucion = DB::table('prestacion_procedimientos as t1')
                ->join('labnivelatenciones as t2', 't1.id_rua', '=', 't2.id')
                ->join('procedimientos as p', 't1.id_procedimiento', '=', 'p.id')
                ->leftJoin('diagnosticos as d', 'd.id', '=', 't1.id_diagnostico')
                ->where('t1.id_prestacion', $hospitalizacion->id_prestacion)
                ->where('t1.id_estado_reg', 1)
                ->select(
                    't1.id',
                    't1.id_lab_rad as id_orden',
                    't2.codigo_lab as codigo',
                    't2.descripcion_corta as nombre',
                    'p.descripcion as nombre_completo',
                    't1.cantidad',
                    't1.created_at',
                    't2.id_procedimiento',
                    't1.observacion',
                    'd.codigo as codigo_diagnostico',
                    'd.nombre as diagnostico'
                )
                ->orderBy('t1.id', 'ASC')
                ->get();

            $ordenes_imagenologia_evolucion = DB::table('radiologias')
                ->where('id_prestacion', $hospitalizacion->id_prestacion)
                ->where('estado', '<>', 0)
                ->orderBy('id', 'ASC')
                ->get();

            $orden_img_examenes_evolucion = DB::table('prestacion_procedimientos as t1')
                ->join('procedimientos as t2', 't1.id_procedimiento', '=', 't2.id')
                ->leftJoin('diagnosticos as t3', 't1.id_diagnostico', '=', 't3.id')
                ->where('t1.id_prestacion', $hospitalizacion->id_prestacion)
                ->where('t1.tipo_procedimiento', 3)
                ->where('t1.id_estado_reg', 1)
                ->select(
                    't1.id',
                    't1.id_lab_rad as id_orden',
                    't2.codigo',
                    't2.descripcion as nombre',
                    't1.cantidad',
                    't1.observacion',
                    't3.nombre as diagnostico',
                    't3.codigo as cod_diag',
                    't1.comparativo_radio',
                    't1.lado_radio'
                )
                ->orderBy('t1.id', 'asc')
                ->get();
            
            $interconsultas_evolucion = DB::table('interconsultas as t8')
                ->join('establecimientos as t3', 't8.id_establecimiento', '=', 't3.id')
                ->join('consultorios as t6', 't8.id_servicio', '=', 't6.id')
                ->leftjoin('medicos as t5', 't8.personal_salud_interconsulta', '=', 't5.id')
                ->where('t8.id_prestacion', $hospitalizacion->id_prestacion)
                ->where('t8.id_estado_reg', 1)
                ->select(
                    't8.id',
                    't8.id_evaluacion as id_evolucion',
                    't3.nombre as establecimiento',
                    't6.nombre as servicio',
                    't5.nombre as profesional_nombre',
                    't5.paterno as profesional_paterno',
                    't5.materno as profesional_materno',
                    't8.motivo_interconsulta',
                    't8.codigo_upss as codigo_interconsulta',
                    't5.colegiatura as profesional_colegiatura'
                )
                ->orderBy('t8.id')
                ->get();

            $diagnosticos_evoluciones = DB::table('evaluaciones as e')
                ->join('receta_diagnosticos as rd', 'e.id', '=', 'rd.id_evaluacion')
                ->join('diagnosticos as d', 'rd.id_diagnostico', '=', 'd.id')
                ->select('e.id as id_evolucion', 'd.codigo as cod_dx', 'd.nombre as nombre_dx', 'rd.id_tipo_diagnostico', 'rd.id_clasificacion_diagnosticos', 'rd.id_categoria')
                ->where('e.id_prestacion', $hospitalizacion->id_prestacion)
                ->where('e.id_estado_reg', 1)
                ->where('rd.estado', 1)
                ->orderBy('e.id')
                ->orderBy('rd.id')
                ->get();

            $procedimientos_evoluciones = DB::table('evaluaciones as e')
                ->join('prestacion_procedimientos as pp', 'e.id', '=', 'pp.id_evaluacion')
                ->join('procedimientos as p', 'pp.id_procedimiento', '=', 'p.id')
                ->select('e.id as id_evolucion', 'p.codigo as cod_cpms', 'p.descripcion as descripcion_cpms', 'pp.cantidad')
                ->where('e.id_prestacion', $hospitalizacion->id_prestacion)
                ->where('e.id_estado_reg', 1)
                ->where('pp.tipo_procedimiento', 1)
                ->where('pp.id_estado_reg', 1)
                ->orderBy('e.id')
                ->orderBy('pp.id')
                ->get();

            $asegurado = DB::table('asegurados')
                ->leftJoin('distritos', 'asegurados.id_distrito','=','distritos.id_distrito')
                ->leftJoin('provincias', 'distritos.id_provincia','=','provincias.id_provincia')
                ->leftJoin('departamentos', 'provincias.id_departamento','=','departamentos.id_departamento')
                ->leftJoin('distritos as distrito_nacimiento', 'asegurados.id_distrito_nacimiento','=','distrito_nacimiento.id_distrito')
                ->leftJoin('provincias as provincia_nacimiento', 'distrito_nacimiento.id_provincia','=','provincia_nacimiento.id_provincia')
                ->leftJoin('departamentos as departamento_nacimiento', 'provincia_nacimiento.id_departamento','=','departamento_nacimiento.id_departamento')
                ->leftJoin('distritos as distrito_procedencia', 'asegurados.id_distrito_procedencia','=','distrito_procedencia.id_distrito')
                ->leftJoin('provincias as provincia_procedencia', 'distrito_procedencia.id_provincia','=','provincia_procedencia.id_provincia')
                ->leftJoin('departamentos as departamento_procedencia', 'provincia_procedencia.id_departamento','=','departamento_procedencia.id_departamento')
                ->leftJoin('razas', 'asegurados.id_raza','=','razas.id')
                ->leftJoin('estado_civiles', 'asegurados.id_estado_civil','=','estado_civiles.id')
                ->leftJoin('grado_instrucciones', 'asegurados.id_grado_instruccion','=','grado_instrucciones.id')
                ->leftJoin('ocupaciones', 'asegurados.id_ocupacion','=','ocupaciones.id')
                ->leftJoin('religiones', 'asegurados.id_religion','=','religiones.id')
                ->select('asegurados.*', 'distritos.descripcion as distrito', 'provincias.descripcion as provincia', 'departamentos.descripcion as departamento', 'razas.nombre as etnia',
                    'estado_civiles.nombre as estado_civil', 'grado_instrucciones.nombre as grado_instruccion', 'ocupaciones.nombre as ocupacion', 'religiones.nombre as religion',
                    'distrito_nacimiento.descripcion as distrito_nacimiento2', 'provincia_nacimiento.descripcion as provincia_nacimiento2', 'departamento_nacimiento.descripcion as departamento_nacimiento2',
                    'distrito_procedencia.descripcion as distrito_procedencia2', 'provincia_procedencia.descripcion as provincia_procedencia2', 'departamento_procedencia.descripcion as departamento_procedencia2')
                ->where('asegurados.id',$hospitalizacion->id_asegurado)
                ->where('asegurados.id_estado_reg', 1)
                ->first();

                $hospitalizacion_epicrisis = DB::table('hospitalizaciones')
                ->select(
                    'hospitalizaciones.*',
                    'diagnosticos.codigo as codigo_cie',
                    'diagnosticos.nombre as nombre_cie',
                    'd5.codigo as codigo_cie_ingreso_2',
                    'd5.nombre as nombre_cie_ingreso_2',
                    'd6.codigo as codigo_cie_ingreso_3',
                    'd6.nombre as nombre_cie_ingreso_3',
                    'd2.codigo as codigo_cie_egreso_1',
                    'd2.nombre as nombre_cie_egreso_1',
                    'd3.codigo as codigo_cie_egreso_2',
                    'd3.nombre as nombre_cie_egreso_2',
                    'd4.codigo as codigo_cie_egreso_3',
                    'd4.nombre as nombre_cie_egreso_3',
                    'm.paterno as profesional_egreso_paterno',
                    'm.materno as profesional_egreso_materno',
                    'm.nombre as profesional_egreso_nombre',
                    'm.colegiatura as profesional_egreso_colegiatura',
                    'camas.nombre as cama',
                    'consultorios.nombre as servicio_sala',
                    'establecimientos.nombre as establecimiento_traslado',
                    'camas_2.nombre as cama_egreso',
                    'consultorios_2.id as id_servicio_sala_egreso',
                    'consultorios_2.nombre as servicio_sala_egreso',
                    DB::raw("COALESCE(servicio_encargado.nombre, '') as servicio_encargado_nombre")
                )
                ->join('diagnosticos', 'hospitalizaciones.id_diagnostico_inicial', '=', 'diagnosticos.id')
                ->leftJoin('diagnosticos as d5', 'hospitalizaciones.id_dx_2', '=', 'd5.id')
                ->leftJoin('diagnosticos as d6', 'hospitalizaciones.id_dx_3', '=', 'd6.id')
                ->leftJoin('diagnosticos as d2', 'hospitalizaciones.id_diag_cab', '=', 'd2.id')
                ->leftJoin('diagnosticos as d3', 'hospitalizaciones.id_diag_cuer1', '=', 'd3.id')
                ->leftJoin('diagnosticos as d4', 'hospitalizaciones.id_diag_cuer2', '=', 'd4.id')
                ->join('users as u', 'hospitalizaciones.id_medico_alta', '=', 'u.id')
                ->join('medicos as m', function ($join) {
                    $join->on('u.dni', '=', 'm.dni')
                        ->on('u.id_establecimiento', '=', 'm.id_establecimiento');
                })
                ->join('camas', 'hospitalizaciones.id_cama_estado', '=', 'camas.id')
                ->join('cuartos', 'camas.id_cuarto', '=', 'cuartos.id')
                ->join('asignacion_servicios', 'cuartos.id_asignacion_servicio', '=', 'asignacion_servicios.id')
                ->join('consultorios', 'asignacion_servicios.id_consultorio', '=', 'consultorios.id')
                ->leftJoin('establecimientos', 'hospitalizaciones.id_establecimiento_traslado', '=', 'establecimientos.id')
                ->leftJoin('camas as camas_2', 'hospitalizaciones.id_cama_egreso', '=', 'camas_2.id')
                ->leftJoin('cuartos as cuartos_2', 'camas_2.id_cuarto', '=', 'cuartos_2.id')
                ->leftJoin('asignacion_servicios as as3', 'cuartos_2.id_asignacion_servicio', '=', 'as3.id')
                ->leftJoin('consultorios as consultorios_2', 'as3.id_consultorio', '=', 'consultorios_2.id')
                ->leftJoin('consultorios as servicio_encargado', 'hospitalizaciones.id_servicio_encargado', '=', 'servicio_encargado.id')
                ->where('hospitalizaciones.id',$hospitalizacion->id_hospitalizacion)
                ->limit(1)
                ->first();


                $data_estancia = DB::table('hospitalizaciones as t1')
                ->select(
                    // 'use.name', // Descomentar si se necesita el nombre del usuario
                    't1.id_user_alta_administrativa',
                    't1.fecha_alta_administrativa',
                    't1.dni_beneficiario',
                    DB::raw("to_char(t1.fecha_ingreso::date, 'DD/MM/YYYY') as fecha_ingreso"),
                    DB::raw("to_char(t1.hora_ingreso::time, 'HH24:MI') as hora_ingreso"),
                    DB::raw("
                        DATE_PART('day', now()::timestamp - t1.fecha_ingreso::timestamp) * 24 + 
                        DATE_PART('hour', now()::timestamp - t1.fecha_ingreso::timestamp) as dif_horas
                    "),
                    DB::raw("DATE_PART('day', now()::timestamp - t1.fecha_ingreso::timestamp) as dia_estancia")
                )
                //->join('users as use', 't1.id_user_alta_administrativa', '=', 'use.id') // Descomentar si se necesita la tabla users
                ->where('t1.id', $hospitalizacion->id_hospitalizacion)
                ->where('t1.estado', '<>', 0)
                ->orderBy('t1.fecha_ingreso', 'desc')
                ->limit(1)
                ->first();


                $dia_estancia = $data_estancia->dia_estancia;
                $servicios_concurrencia = null;
                if ($hospitalizacion->id_tipo_egreso == 1) {
                    $servicios_concurrencia = DB::table('hospitalizacion_concurrencias')
                                                ->join('consultorios', 'hospitalizacion_concurrencias.id_servicio', '=', 'consultorios.id')
                                                ->where('id_hospitalizacion',$hospitalizacion->id_hospitalizacion)
                                                ->select('hospitalizacion_concurrencias.*', 'consultorios.nombre as servicio')
                                                ->orderBy('id', 'asc')
                                                ->get();
                }

                $titular = null;
                $persona_datos_adicionales = null;
                $grado = null;

                if ($asegurado->id_asegurado_titular != null || $asegurado->id_asegurado_titular > 0) {

                    $titular = DB::table('asegurados')
                        ->select('asegurados.*')
                        ->where('id',$asegurado->id_asegurado_titular)
                        ->where('id_estado_reg', 1)
                        ->first();
                    
                    if ($titular != null) {
                        $persona_datos_adicionales = DB::table('persona_datos_adicionales')
                            ->select('persona_datos_adicionales.*')
                            ->where('id_asegurado',$titular->id)
                            ->first();

                        if ($persona_datos_adicionales != null) {
                            $grado = DB::table('titular_grados')
                            ->select('titular_grados.*')
                            ->where('id',$persona_datos_adicionales->id_grado)
                            ->first();
                        }
                    }
                }
        ?>
        {{-- I.- ANAMNESIS --}}
        <br>
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
                <td>
                    @if(isset($persona_datos_adicionales) && $persona_datos_adicionales != null)
                    {{$persona_datos_adicionales->cip}}
                    @endif
                </td>
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
                <td></td>
                <td></td>
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
                <td colspan="2">Parentesco con el paciente: <b>{{$hospitalizacion->tipo_beneficiario}}</b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">Fecha y hora de ingreso: <b>{{$hospitalizacion->fecha_ingreso}} {{$hospitalizacion->hora_ingreso}}</b></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">Fecha y hora de Historia Clínica: <b>{{$hospitalizacion->created_at}}</b></td>
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
            <tr>
                <td colspan="2">CAMA: <b>{{ $hospitalizacion->cama }}</b></td>
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
                <td colspan="3">Síntomas y signos principales:</td>
            </tr>
            <tr>
                <td colspan="3"><p>{!! nl2br(e($atencion->sintomas_signos_principal)) !!}</p></td>
            </tr>
            <tr>
                <td colspan="3">Relato:</td>
            </tr>
            <tr>
                <td colspan="3"><p>{!! nl2br(e($atencion->relato)) !!}</p></td>
            </tr>
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
        <br>
        <table border="0"> 
            <tr>
                <td class="subtitulo_2" colspan="3"><b>3.- ANTECEDENTES PERSONALES</b></td>
            </tr>
        </table>

        <table border="1" class="tbl_antecedentes">
            <tr>
                <td colspan="4"><b>Generales</b></td>
            </tr>
            <tr>
                <td style="width: 30%;">Grupo sanguíneo y factor RH: <span>{{$atencion->antc_1}}</span></td>
                <td style="width: 25%;">Alergias-RAM: <span>{{$atencion->antc_2}}</span></td>
                <td style="width: 25%;">Drogas: <span>{{$atencion->antc_3}}</span></td>
                <td style="width: 20%;">Alcohol: <span>{{$atencion->antc_4}}</span></td>
            </tr>
            <tr>
                <td style="width: 20%;">Tabaco: <span>{{$atencion->antc_5}}</span></td>
                <td style="width: 20%;">Café: <span>{{$atencion->antc_6}}</span></td>
                <td style="width: 20%;">Transfusiones: <span>{{$atencion->antc_7}}</span></td>
                <td style="width: 40%;">Otros de importancia: <span>{!! nl2br(e($atencion->antc_8)) !!}</span></td>
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
        {{-- FIN I.- ANAMNESIS --}}
        <br>
        {{-- II.- EXAMEN FÍSICO --}}
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
        
        <table border="1" id="tbl_hospitalizacion_atencion" class="tbl_antecedentes">
            <tr>
                <td>Presión arterial: <b>{{ $prestacion->presion_arterial_x }} / {{$prestacion->presion_arterial_y}}</b></td>
                <td>Frecuencia Cardiaca: <b>{{ $prestacion->frecuencia_cardiaca }}</b></td>
                <td>Frecuencia Respiratoria: <b>{{ $prestacion->frecuencia_respiratoria }}</b></td>
                <td>Temperatura: <b>{{ $prestacion->temperatura }}</b></td>
                <td>Talla: <b>{{ $prestacion->talla_cm }}</b></td>
            </tr>
            <tr>
                <td>Peso: <b>{{ $prestacion->peso_kg }}</b></td>
                <td>Tamaño de Cintura: <b>{{ $prestacion->tamano_cintura_cm }}</b></td>
                <td>SaO2: <b>{{ $prestacion->sa02_porcentaje }}</b></td>
                <td>FiO2: <b>{{ $prestacion->fi02 }}</b></td>
                <td>Frecuencia Cardiaca Fetal: <b>{{ $prestacion->frecuencia_cardiaca_fetal }}</b></td>
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
        {{-- FIN II.- EXAMEN FÍSICO --}}

        {{-- III.- DIAGNÓSTICO --}}
        <table border="0">
            <tr>
                <td colspan="3" class="subtitulo_1"><b>III.- DIAGNÓSTICO</b></td>
            </tr>
        </table>
        
        <table style="width: 100%;" border="1">
            <tr>
              <td  class="subtitulo_2" style="width: 1%; text-align: center;"><b>N°</b></td>
              <td  class="subtitulo_2" style="width: 10%; text-align: center;"><b>CIE - 10</b></td>
              <td  class="subtitulo_2" style="text-align: center;"><b>DESCRIPCIÓN DEL DIAGNÓSTICO</b></td>
              <td  class="subtitulo_2" colspan="3"  style="width: 20%; text-align: center;"> <b>TIPO DE DIAGNÓSTICO</b></td>
            </tr>
            <?php 
            if(!empty($diagnosticos)):
            $n=1;
            foreach($diagnosticos as $row):?>
            <tr>
              <td><?php echo $n++;?></td>
              <td><?php echo $row->cod_dx;?></td>
              <td ><?php echo $row->nombre_dx;?></td>
              <td>
                <div class="contenedor">
                  <div class="valor"  style="text-align: center; font-size: 12px;"> P </div>
                  <div class="marca"><?php if($row->id_tipo_diagnostico==1){echo 'X';}?></div>
                </div>
              </td>
              <td>
                <div class="contenedor">
                <div class="valor"  style="text-align: center; font-size: 12px;">D</div>
                <div class="marca"><?php if($row->id_tipo_diagnostico==2){echo 'X';}?></div>
                </div>
              </td>
              <td>
                <div class="contenedor">
                  <div class="valor"  style="text-align: center; font-size: 12px;">R</div>
                  <div class="marca"><?php if($row->id_tipo_diagnostico==3){echo 'X';}?></div>
                </div>

               </td>
            </tr>
            <?php endforeach;
              endif;
            ?>
        </table>
        {{-- FIN III.- DIAGNÓSTICO --}}

        {{-- IV.- TRATAMIENTO --}}
        <table border="0">
            <tr>
                <td class="subtitulo_1"><b>IV.- TRATAMIENTO</b></td>
            </tr>
            <tr>
                <td><p>{!! nl2br(e($prestacion->indicacion_terapeutica)) !!}</p></td>
            </tr>
        </table>
        {{-- FIN IV.- TRATAMIENTO --}}

         {{-- V.- PLAN DE TRABAJO --}}
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
        
        <table border="1" class="tbl_antecedentes">
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

        <table border="1" class="tbl_antecedentes">
            <tr>
                <td class="subtitulo_2"><b>PROCEDIMIENTOS MÉDICO - QUIRÚRGICOS</b></td>
            </tr>
            <tr>
                <td><p>{!! nl2br(e($prestacion->procedimiento_medico_quirurgico)) !!}</p></td>
            </tr>
        </table>

        <table border="0">
            <tr>
                <td class="subtitulo_2"><b>2.- INTERCONSULTAS</b></td>
            </tr>
            <tr>
                <td><p>{!! nl2br(e($prestacion->interconsulta)) !!}</p></td>
            </tr>
        </table>

        <table border="0">
            <tr>
                <td class="subtitulo_2"><b>MEDICO ENCARGADO</b></td>
            </tr>
        </table>

        <table border="1" class="tbl_antecedentes">
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

        <table border="0">
            <tr>
                <td class="subtitulo_2"><b>RECETAS VALE</b></td>
            </tr>
        </table>

        <table border="1" style="font-size:10px;">
            @foreach ($recetas as $receta)
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
                @foreach ($receta_productos as $row)
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
                        <td style="text-align:center"><?php if($row->codigo=='59075700' || $row->codigo=='PF00718'):
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
                    <td colspan="7">&nbsp;</td>
                </tr>
            @endforeach
        </table>

        <table border="0">
            <tr>
                <td class="subtitulo_2"><b>ORDEN DE LABORATORIO</b></td>
            </tr>
        </table>

        <table border="1" style="font-size:10px;" >
            @foreach ($ordenes_laboratorio as $orden_lab)
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
                @foreach ($orden_lab_examenes as $examen)
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
                    <td colspan="5">&nbsp;</td>
                </tr>
            @endforeach
        </table>

        <table border="1" style="font-size:10px;" >
            <tbody>
            <tr>
                <td width="300px" style="font-weight:bold;text-align:center;"> <!-- style="text-align:left;padding-top:7px;padding-bottom:7px;font-weight:bold;background-color:rgb(213,213,213);" border="1" -->
                <div class="rectangulo" style="border: 1px solid #A4A4A4;">EXAMEN</div>
                </td>
                <td width="100px" style="font-weight:bold;text-align:center;">
                <div class="rectangulo" style="border: 1px solid #A4A4A4;">RESULTADO</div>
                </td>
                <td style="font-weight:bold;text-align:center;">
                <div class="rectangulo" style="border: 1px solid #A4A4A4;">RANGO REFERENCIAL</div>
                </td>
                <td width="80px" style="font-weight:bold;text-align:center;">
                <div class="rectangulo" style="border: 1px solid #A4A4A4;">UNIDADES</div>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="font-weight:bold;text-align:center;font-size:12px;">
                <div class="rectangulo" style="border: 1px solid #A4A4A4;"><?php echo $hospitalizacion->servicio_encargado_nombre; ?></div>
                </td>
            </tr>

            <?php
            foreach ($ordenes_laboratorio as $orden_lab){
                $resultados1 = $results = DB::table('laboratorio_resultados')
                                            ->select([
                                                'p_nombre',
                                                'l_resultado',
                                                DB::raw("(CASE WHEN ra_texto <> '' THEN ra_texto ELSE CONCAT(l_ref_inf,' - ',l_ref_sup) END) AS rango_referencial_ant"),
                                                'l_unidades',
                                                'g_nombre',
                                                'usr_name',
                                                'l_resultcomp AS Compuesto',
                                                'p_comentario',
                                                DB::raw("(CASE WHEN l_ralt_id = '0' THEN CONCAT(l_ref_inf,' - ',l_ref_sup) ELSE ra_texto END)::TEXT AS rango_referencial")
                                            ])
                                            ->where('l_estado', '>=', 4)
                                            ->where('h_numero', $hospitalizacion->dni_beneficiario)
                                            ->where('o_numero', $orden_lab->id)
                                            ->where('l_resultcomp', '')
                                            ->orderBy('l_orden_imp')
                                            ->get();
                if ($resultados1 != null){
                    foreach ($resultados1 as $obj) {
                    $rango_referencial = "";
                    if ($obj->rango_referencial == "0 - 0"){
                        $rango_referencial = "";
                    }else{
                        $rango_referencial = $obj->rango_referencial;
                        $rango_referencial = str_replace("&", "&amp;", $rango_referencial);
                        $rango_referencial = str_replace("<", "&lt;", $rango_referencial);
                        $rango_referencial = str_replace(">", "&gt;", $rango_referencial);
                        $rango_referencial = str_replace("\n"," <br>",$rango_referencial);
                    };
            ?>
                    <tr>
                        <td><?php echo $obj->p_nombre; ?></td> 
                        <td>&nbsp;&nbsp;<?php echo $obj->l_resultado; ?></td> 
                        <td><?php echo $rango_referencial."<br>".($obj->p_comentario==""? "" : $obj->p_comentario."<br><br>") ?></td>
                        <td><?php echo $obj->l_unidades; ?></td> 
                    </tr>
            <?php
                    }
                }
            }
            ?>
        </table>
        

        <table border="0" >
            <tr>
                <td class="subtitulo_2"><b>ORDEN DE IMAGENOLOGIA</b></td>
            </tr>
        </table>

        <table border="1" style="font-size:10px;" >
            @foreach ($ordenes_imagenologia as $orden_img)
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
                @foreach ($orden_img_examenes as $examen)
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
                    <td colspan="7">&nbsp;</td>
                </tr>
            @endforeach
        </table>

        <table border="0" >
            <tr>
                <td class="subtitulo_2"><b>INTERCONSULTAS</b></td>
            </tr>
        </table>

        <table border="1" style="font-size:10px;" >
            <?php $i=1; ?>
            <?php foreach ($interconsultas as $interconsulta){?>
                <tr>
                    <td rowspan="3"><b>INTERCONSULTA N°{{$i}}</b></td>
                    <td><b>SERVICIO: </b></td>
                    <td>{{$interconsulta->codigo_interconsulta}} - {{$interconsulta->servicio}}</th>
                </tr>
                <tr>
                    <td><b>MOTIVO: </b></td>
                    <td>{{$interconsulta->motivo_interconsulta}}</td>
                </tr>
                <tr>
                    <td><b>MEDICO: </b></td>
                    <td>{{$interconsulta->profesional_paterno}} {{$interconsulta->profesional_materno}} {{$interconsulta->profesional_nombre}}</td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <?php $i++; ?>
            <?php }?>
        </table>
         {{-- FIN V.- PLAN DE TRABAJO --}}
         
         {{-- VI.- HOJA TERAPEUTICA --}}
        <table border="0">
            <tr>
                <td colspan="4" class="subtitulo_1"><b>VI.- HOJA TERAPEUTICA</b></td>
            </tr>
        </table>
        <br>
        <table border="1" style="font-size:10px;">
            <tr>
                <th rowspan="2">N°</th>
                <th rowspan="2">NOMBRE DEL MEDICAMENTO</th>
                <th rowspan="2">PRESENT</th>
                <th rowspan="2">DOSIS Y FRECUENCIA</th>
                <th rowspan="2" style="height: 8%;">VÍA DE ADMINISTRACIÓN </th>
                <th colspan="4">FECHA Y HORA</th>
            </tr>
            <tr>
                <th> &nbsp;</th>
                <th> &nbsp;</th>
                <th> &nbsp;</th>
                <th> &nbsp;</th>
            </tr>
           @foreach($receta_productos_combinados as $key => $pc)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$pc->nombre}}</td>
                <td>{{$pc->presentacion }}</td>
                <td>{{$pc->dosis}} {{$pc->frecuencia}}</td>
                <td>{{$pc->via_administracion}}</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="9" class="section">DIETA</td>
            </tr>
            <tr>
                <td colspan="9" style="height: 40px;">{!! nl2br(e($atencion->dieta_terapeutica)) !!}</td>
            </tr>
            <tr>
                <td colspan="9" class="section">INDICACIÓN COMPLEMENTARIA</td>
            </tr>
            <tr>
                <td colspan="9" style="height: 60px;">{!! nl2br(e($atencion->indicacion_terapeutica)) !!}</td>
            </tr>
        </table>
         {{-- FIN VI.- HOJA TERAPEUTICA --}}
        {{-- VII.- EVOLUCIONES --}}
        <div style="page-break-after:always;"></div>

        <table border="0" id="tbl_hospitalizacion_atencion" >
            <tr>
                <td colspan="4" class="subtitulo_1"><b>VII.- EVOLUCIONES </b></td>
            </tr>
        </table>
        {{-- EVOLUCIONES --}}
        <?php foreach($evoluciones as $key=> $evolucion){?>
            <table border="0" id="" >
                <tr>
                    <td><b>EVOLUCIÓN  N° {{ $key+1 }}</b></td>
                </tr>
            </table>
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
            <table style="width: 100%;" border="1">
                <tr>
                  <td  class="subtitulo_2" style="width: 1%; text-align: center;"><b>N°</b></td>
                  <td  class="subtitulo_2" style="width: 10%; text-align: center;"><b>CIE - 10</b></td>
                  <td  class="subtitulo_2" style="text-align: center;"><b>DESCRIPCIÓN DEL DIAGNÓSTICO</b></td>
                  <td  class="subtitulo_2" colspan="3"  style="width: 20%; text-align: center;"> <b>TIPO DE DIAGNÓSTICO</b></td>
                  <td class="subtitulo_2"   style="width: 20%; text-align: center;"> CATEGORIA</td>
                </tr>
                <?php 
                if(!empty($diagnosticos_evoluciones)):
                $n=1;
                foreach($diagnosticos_evoluciones as $row):?>
                <?php
               
                $categoria = "";
                switch($row->id_categoria): 
                    case 1: $categoria = "PRINCIPAL"; break;
                    case 2: $categoria = "SECUNDARIO"; break;
                endswitch
                ?>
                @if($evolucion->id == $row->id_evolucion)
                <tr>
                  <td><?php echo $n++;?></td>
                  <td><?php echo $row->cod_dx;?></td>
                  <td ><?php echo $row->nombre_dx;?></td>
                  <td>
                    <div class="contenedor">
                      <div class="valor"  style="text-align: center; font-size: 12px;"> P </div>
                      <div class="marca"><?php if($row->id_tipo_diagnostico==1){echo 'X';}?></div>
                    </div>
                  </td>
                  <td>
                    <div class="contenedor">
                    <div class="valor"  style="text-align: center; font-size: 12px;">D</div>
                    <div class="marca"><?php if($row->id_tipo_diagnostico==2){echo 'X';}?></div>
                    </div>
                  </td>
                  <td>
                    <div class="contenedor">
                      <div class="valor"  style="text-align: center; font-size: 12px;">R</div>
                      <div class="marca"><?php if($row->id_tipo_diagnostico==3){echo 'X';}?></div>
                    </div>
                   </td>
                   <td>{{ $categoria }}</td>
                </tr>
                @endif
                <?php endforeach;
                  endif;
                ?>
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
    
            <table border="1" style="" >
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
    
            <table border="1" style="font-size:10px;" class="tbl_ordenes">
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
                            <td colspan="7">&nbsp;</td>
                        </tr>
                    @endif
                @endforeach
            </table>
    
            <table border="0" >
                <tr>
                    <td class="subtitulo_2"><b>ORDEN DE LABORATORIO</b></td>
                </tr>
            </table>
    
            <table border="1" style="font-size:10px;" class="tbl_ordenes">
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
                        <td colspan="5">&nbsp;</td>
                    </tr>
                    @endif
                @endforeach
            </table>
           
    
            <table border="0" >
                <tr>
                    <td class="subtitulo_2"><b>ORDEN DE IMAGENOLOGIA</b></td>
                </tr>
            </table>
    
            <table border="1" style="font-size:10px;" class="tbl_ordenes">
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
                            <td colspan="7">&nbsp;</td>
                        </tr>
                    @endif
                @endforeach
            </table>
    
            <table border="0" >
                <tr>
                    <td class="subtitulo_2"><b>INTERCONSULTAS</b></td>
                </tr>
            </table>
    
            <table border="1" style="font-size:10px;" class="tbl_ordenes">
                <?php $i=1; ?>
                @foreach ($interconsultas_evolucion as $interconsulta)
                    @if ($evolucion->id == $interconsulta->id_evolucion)
                    <tr>
                        <td colspan="2"><b>INTERCONSULTA N°{{$i}}</b></td>
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
                        <td><b>MEDICO: </b></td>
                        <td>{{$interconsulta->profesional_paterno}} {{$interconsulta->profesional_materno}} {{$interconsulta->profesional_nombre}}</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <?php $i++; ?>
                    @endif
                @endforeach
            </table>
        <?php 
        }?>
        {{-- FIN EVOLUCIONES --}}

        {{-- FIN VII.- EVOLUCIONES --}}
       
        {{-- VIII.- EPICRISIS --}}
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
        <br>
        <table border="0">
            <tr>
                <td colspan="4" class="subtitulo_1"><b>VIII.- EPICRISIS</b></td>
            </tr>
        </table>

        <div class="border">
            <table border="1" style="font-size:10px;" class="tbl_ordenes">
                <tr>
                    <td style="width:30%"><b>FECHA INGRESO: </b><span>{{date("d/m/Y", strtotime($hospitalizacion->fecha_ingreso))}}</span></td>
                    <td><b>HORA: </b><span>{{date("H:i a", strtotime($hospitalizacion->hora_ingreso))}}</span></td>
                    <td style="width:50%">
                        <b>SERVICIO A CARGO: </b><span>{{$hospitalizacion->servicio_encargado_nombre}}</span><br>
                        <b>CAMA: </b><span>{{$hospitalizacion->servicio_sala}} - {{$hospitalizacion->cama}}</span>
                    </td>
                </tr>
            </table>

            <table border="1" style="font-size:10px;" class="tbl_ordenes">
                <!-- <tr>
                    <th style="width:5%"></th>
                    <th style="width:90%"></th>
                    <th style="width:5%"></th>
                </tr> -->
                <tr>
                    <td style="width:92%"><b>DIAGNOSTICOS DE INGRESO</b></td>
                    <td style="width:8%"><b>CIE</b></td>
                </tr>
                @if($hospitalizacion->id_diagnostico_inicial != null)
                <tr>
                    <td><b>1.- </b>{{ $hospitalizacion->nombre_cie }}</td>
                    <td>{{ $hospitalizacion->codigo_cie }}</td>
                </tr>
                @endif
                @if($hospitalizacion->id_dx_2 != null)
                <tr>
                    <td><b>2.- </b>{{ $hospitalizacion->nombre_cie_ingreso_2 }}</td>
                    <td>{{ $hospitalizacion->codigo_cie_ingreso_2 }}</td>
                </tr>
                @endif
                @if($hospitalizacion->id_dx_3 != null)
                <tr>
                    <td><b>3.- </b>{{ $hospitalizacion->nombre_cie_ingreso_3 }}</td>
                    <td>{{ $hospitalizacion->codigo_cie_ingreso_3 }}</td>
                </tr>
                @endif
            </table>

            <table border="1" style="font-size:10px;" class="tbl_ordenes">
                <tr>
                    <td colspan="2"><b>RESUMEN DE LA ENFERMEDAD ACTUAL</b></td>
                </tr>
                <tr>
                    <td colspan="2"><p>{{$hospitalizacion->egreso_resumen}}</p></td>
                </tr>
            </table>

            <table border="1" style="font-size:10px;" class="tbl_ordenes">
                <tr>
                    <td colspan="2"><b>EXAMEN CLÍNICO</b></td>
                </tr>
                <tr>
                    <td colspan="2"><p>{{$hospitalizacion->egreso_examen_clinico}}</p></td>
                </tr>
            </table>
    
      
            <table border="1" style="font-size:10px;" class="tbl_ordenes">
                <tr>
                    <td colspan="2"><b>EXAMENES AUXILIARES</b></td>
                </tr>
                <tr>
                    <td colspan="2"><p>{{$hospitalizacion->egreso_examen_auxiliar}}</p></td>
                </tr>
            </table>

            <table border="1" style="font-size:10px;" class="tbl_ordenes">
                <tr>
                    <td colspan="2"><b>EVOLUCIÓN</b></td>
                </tr>
                <tr>
                    <td colspan="2"><p>{{$hospitalizacion->egreso_evolucion}}</p></td>
                </tr>
            </table>
     
       
            <table border="1" style="font-size:10px;" class="tbl_ordenes">
                <tr>
                    <td colspan="2"><b>TRATAMIENTO</b></td>
                </tr>
                <tr>
                    <td colspan="2"><p>{{$hospitalizacion->egreso_tratamiento}}</p></td>
                </tr>
            </table>
       
    
            <table border="1" style="font-size:10px;" class="tbl_ordenes">
                <tr>
                    <td colspan="2"><b>PROCEDIMIENTOS TERAPÉUTICOS Y/O DIAGNÓSTICOS</b></td>
                </tr>
                <tr>
                    <td colspan="2"><p>{{$hospitalizacion->egreso_procedimiento}}</p></td>
                </tr>
            </table>
       
            <table border="1" style="font-size:10px;" class="tbl_ordenes">
                <tr>
                    <td colspan="2"><b>COMPLICACIONES</b></td>
                </tr>
                <tr>
                    <td colspan="2"><p>{!! nl2br(e($hospitalizacion->egreso_complicacion)) !!}</p></td>
                </tr>
            </table>
      
        
            <table border="1" style="font-size:10px;" class="tbl_ordenes">
                <tr>
                    <td colspan="4" class="subtitulo_2"><b>DIAGNÓSTICO</b></td>
                </tr>
                @if($hospitalizacion->id_diag_cab != null)
                <tr>
                    <td style="width:5%"><b>1.-</b></td>
                    <td style="width:70%"><p>{{ $hospitalizacion->nombre_cie_egreso_1 }}</p></td>
                    <td style="width:10%"><b>CIE:</b> {{ $hospitalizacion->codigo_cie_egreso_1 }}</td>
                    <td style="width:15%">PRINCIPAL</td>
                </tr>
                @endif
                @if($hospitalizacion->id_diag_cuer1 != null)
                <tr>
                    <td style="width:5%"><b>2.-</b></td>
                    <td style="width:70%"><p>{{ $hospitalizacion->nombre_cie_egreso_2 }}</p></td>
                    <td style="width:10%"><b>CIE:</b> {{ $hospitalizacion->codigo_cie_egreso_2 }}</td>
                    <td style="width:15%">PRINCIPAL</td>
                </tr>
                @endif
                @if($hospitalizacion->id_diag_cuer2 != null)
                <tr>
                    <td style="width:5%"><b>3.-</b></td>
                    <td style="width:70%"><p>{{ $hospitalizacion->nombre_cie_egreso_3 }}</p></td>
                    <td style="width:10%"><b>CIE:</b> {{ $hospitalizacion->codigo_cie_egreso_3 }}</td>
                    <td style="width:15%">PRINCIPAL</td>
                </tr>
                @endif
            </table>
        
     
            <table border="1" style="font-size:10px;" class="tbl_ordenes">
                <tr>
                    <td style="width:200px"><b>FECHA Y HORA DE EGRESO</b></td>
                    <td style="width:150px"><b>TIPO DE EGRESO</b></td>
                    <td style="width:150px"><b>CONDICION DE EGRESO</b></td>
                </tr>
                <tr>
                    <td>{{ date("d/m/Y", strtotime($hospitalizacion->fecha_salida)) }} {{ date("H:i:s", strtotime($hospitalizacion->hora_egreso)) }}</b></td>
                    <td>{{ $tipo_egreso }}</td>
                    <td>{{ $condicion_egreso }}</td>
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
        </div>
        <?php if ($key < count($atenciones) - 1) { ?>
            <div class="pagina-siguiente"></div>
        <?php } else { ?>
            <div class="ultima-pagina"></div>
        <?php } ?>
         {{-- FIN VIII.- EPICRISIS --}}
        {{-- <table border="0" >
            <tr>
                <td class="subtitulo_2"><b>MEDICO ENCARGADO</b></td>
            </tr>
        </table>

        <table border="1" style="" >
            <tr>
                <td><b>APELLIDOS Y NOMBRES:</b></td>
                <td colspan="3">{{ $hospitalizacion->profesional_egreso_paterno }} {{ $hospitalizacion->profesional_egreso_materno }} {{ $hospitalizacion->profesional_egreso_nombre }}</td>
            </tr>
            <tr>
                <td style="width:23%; padding-top: 5px;"><b>PROFESIÓN:</b></td>
                <td style="">NO INDICA</td>
                <td style="width:17%"><b>N° COLEGIATURA:</b></td>
                <td style="width:10%">{{ $hospitalizacion->profesional_egreso_colegiatura }}</td>
            </tr>
        </table> --}}
         {{-- FIN IX.- ALTA --}}
    
<?php }?>

@if(isset($autoPrint) && $autoPrint)
<script>
    window.onload = function() { window.print(); };
</script>
@endif

</body>
</html>
