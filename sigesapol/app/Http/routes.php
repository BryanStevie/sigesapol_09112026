<?php

/***
 * Switch between the included languages
 */
require(__DIR__ . "/Routes/Global/Lang.php");

/**
 * Frontend Routes
 * Namespaces indicate folder structure
 */
$router->group(['namespace' => 'Frontend'], function () use ($router) {
  require(__DIR__ . "/Routes/Frontend/Frontend.php");
  require(__DIR__ . "/Routes/Frontend/Access.php");
});

/**
 * Backend Routes
 * Namespaces indicate folder structure
 */

$router->group(['namespace' => 'Backend'], function () use ($router) {
  $router->group(['prefix' => 'admin', 'middleware' => 'auth'], function () use ($router) {
    /**
     * These routes need view-backend permission (good if you want to allow more than one group in the backend, then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     */
    $router->group(['middleware' => 'access.routeNeedsPermission:view-backend'], function () use ($router) {
      require(__DIR__ . "/Routes/Backend/Dashboard.php");
      require(__DIR__ . "/Routes/Backend/Access.php");
    });
  });
});


//----------------RUTAS PARA CRUD LABORATORIOS--------------------------------------
Route::get('/create_laboratorio', 'LaboratorioController@create');
Route::post('store_laboratorio', [
  'uses' => 'LaboratorioController@store',
  'as' => 'store_laboratorio'
]);
Route::post('update_laboratorio', [
  'uses' => 'LaboratorioController@update',
  'as' => 'update_laboratorio'
]);
Route::get('/all_laboratorios', 'LaboratorioController@index');
Route::get('/edit_laboratorios/{id}', 'LaboratorioController@edit');
Route::get('/laboratorio_agrupado/{idPaciente}/{idCita}', 'LaboratorioController@laboratorio_agrupado');
Route::post('/laboratorio_emergencia_insert', 'LaboratorioController@insertLab_post');

Route::get('/obtener_upss_x_servicio/{id?}', 'ReferenciasController@obtener_upss_x_servicio');

Route::post('/laboratorio_emergencia_actualizar', 'LaboratorioController@updateLab_post');

Route::get('/laboratorio_agrupado_editar/{idPaciente}/{idLabs}', 'LaboratorioController@laboratorio_agrupado_editar');


  // Obtener todas las opciones
    Route::get('/getAllOptions', 'ConfigController@getAllOptions')->name('config.getAllOptions');
    
    // Obtener opciones por prefijo
    Route::get('/getOptionsByPrefix', 'ConfigController@getOptionsByPrefix')->name('config.getOptionsByPrefix');
    
    // Obtener grupos
    Route::get('/getGroupOptions', 'ConfigController@getGroupOptions')->name('config.getGroupOptions');
    
    // Guardar múltiples opciones
    Route::post('/saveOptions', 'ConfigController@saveOptions')->name('config.saveOptions');
    
    // Actualizar una opción
    Route::post('/updateOption', 'ConfigController@updateOption')->name('config.updateOption');
    
    // Crear una nueva opción
    Route::post('/createOption', 'ConfigController@createOption')->name('config.createOption');

    Route::get('/mantenimiento', 'ConfigController@mantenimiento')->name('config.mentenimiento');

    Route::get('/laboratorioObtenerTopicos/{id_establecimiento}', 'LaboratorioController@laboratorioObtenerTopicos');
    
//------------------------------------------------------------------------------
//-------------RUTAS PARA CRUD FARMACIAS----------------------------------------
Route::get('/all_farmacias', 'FarmaciaController@index');
Route::get('/create_farmacia', 'FarmaciaController@create');
Route::get('/create_farmacia_servicios/{id}', 'FarmaciaController@create_farmacia_servicios');
Route::get('/view_farmacia_servicios/{id}', 'FarmaciaController@view_farmacia_servicios');


Route::post('store_farmacia', [
  'uses' => 'FarmaciaController@store',
  'as' => 'store_farmacia'
]);

Route::get('/store_farmacia', 'FarmaciaController@store');


Route::post('store_farmacia_servicios', [
  'uses' => 'FarmaciaController@store_farmacia_servicios',
  'as' => 'store_farmacia_servicios'
]);

Route::get('/edit_farmacia/{id}', 'FarmaciaController@edit');
Route::get('/edit_farmacia_config/{id}', 'FarmaciaController@edit_config');
Route::get('/cargarnombre/{cmbest}', 'FarmaciaController@getnombre');
Route::get('/evitar_duplicados_rectas/{id_receta}/{id_farmacia}', 'RecetaValeController@evitar_duplicados_rectas');
Route::get('/evitar_registro_recetas_anuladas_by_fiscalizador/{id_receta}/{id_farmacia}', 'RecetaValeController@evitar_registro_recetas_anuladas_by_fiscalizador');
Route::get('/evitar_duplicados_medicos/{dni}', 'MedicoController@evitar_duplicados_medicos');
Route::get('/genera_receta_duplicado/{id_receta}/{id_farmacia}', 'RecetaValeController@genera_receta_duplicado');
Route::get('/validar_hc_duplicada/{id_farmacia}/{nro_documento}/{nro_historia}', 'RecetaValeController@validar_hc_duplicada');


Route::post('update_farmacia', [
  'uses' => 'FarmaciaController@update',
  'as' => 'update_farmacia'
]);

Route::post('update_farmacia_config', [
  'uses' => 'FarmaciaController@update_config',
  'as' => 'update_farmacia_config'
]);
//------------------------------------------------------------------------------
//-------------RUTAS PARA CRUD PRODUCTOS----------------------------------------
Route::get('/all_productos', 'ProductoController@index');
Route::post('search_tipo_producto', [
  'uses' => 'ProductoController@search_tipo',
  'as' => 'search_tipo_producto'
]);

Route::get('/search_tipo_producto', 'ProductoController@search_tipo');

Route::get('/create_producto', 'ProductoController@create');
Route::post('store_producto', [
  'uses' => 'ProductoController@store',
  'as' => 'store_producto'
]);

Route::get('/edit_producto/{id}', 'ProductoController@edit');
Route::post('update_producto', [
  'uses' => 'ProductoController@update',
  'as' => 'update_producto'
]);

//------------------------------------------------------------------------------

Route::get('/all_medicos_programacion', 'MedicoController@all_medicos_programacion')->middleware('menu.permission:35');
Route::get('/reporte/programacion_medicos', 'ReporteController@programacion_medicos');
Route::post('/exportar-excel/programacion_medicos', ['uses' => 'ReporteController@exportar_programacion_medicos', 'as' => 'exportar-excel-programacion_medicos']);

Route::get('/datos_madrugada', 'MedicoController@registrar_datos_madrugada');
//Route::get('/all_medicos_programacion_detalle/{id_establecimiento}/{id_medico}/{id_consultorio}/{mes}/{ano}/{id_tipo_atencion}', 'MedicoController@all_medicos_programacion_detalle');
Route::get('/all_medicos_programacion_detalle/{id_establecimiento}/{id_medico}/{id_sub_consultorio}/{mes}/{ano}/{id_tipo_atencion}/{id_consultorio}', 'MedicoController@all_medicos_programacion_detalle');
Route::post('registrar_medico_programacion', [
  'uses' => 'MedicoController@registrar_medico_programacion',
  'as' => 'registrar_medico_programacion'
]);


Route::get('/all_permiso', 'UsuarioController@all_permiso')->middleware('menu.permission:19');
Route::post('store_permiso', [
  'uses' => 'UsuarioController@store_permiso',
  'as' => 'store_permiso'
]);

Route::get('/all_usuario', 'UsuarioController@index')->middleware('menu.permission:1');
Route::get('/create_usuario', 'UsuarioController@create');
Route::get('/edit_usuario/{id}', 'UsuarioController@edit');
Route::get('/asignar_opciones/{id}', 'UsuarioController@asignar_opciones');
Route::post('store_usuario', [
  'uses' => 'UsuarioController@store',
  'as' => 'store_usuario'
]);
Route::post('update_usuario', [
  'uses' => 'UsuarioController@update',
  'as' => 'update_usuario'
]);

Route::get('validar_documento/{num_documento}', 'UsuarioController@validar_documento');

//-------------RUTAS PARA CRUD UNIDADES-----------------------------------------
Route::get('/all_unidades', 'UnidadController@index');
Route::post('store_unidad', [
  'uses' => 'UnidadController@store',
  'as' => 'store_unidad'
]);
Route::get('/create_unidad', 'UnidadController@create');
Route::get('/edit_unidad/{id}', 'UnidadController@edit');
Route::post('update_unidad', [
  'uses' => 'UnidadController@update',
  'as' => 'update_unidad'
]);
//------------------------------------------------------------------------------
//-------------RUTAS PARA CRUD LOTES--------------------------------------------


Route::get('/form_generar_rerporte_ici/{iduser?}', 'StocksController@form_generar_rerporte_ici');

Route::get('/create_lote', 'ProductoController@create_lote');
Route::get('/stocksbyuser/{iduser}', 'StocksController@stocksbyuser');
Route::get('/corregir_receta/{iduser}', 'StocksController@corregir_receta');

Route::get('/editar_stock_farmacia/{iduser}/{codigo_producto}', 'StocksController@editar_stock_farmacia');

Route::get('/modulo_ingresos', 'StocksController@modulo_ingresos');

Route::get('/cerrar_carga_inicial/{idfarmacia}/{id_user}', 'StocksController@cerrar_carga_inicial');
// LEGACY
Route::get('/stocksbyFamarciaLegacy/{iduser?}/{situacion?}', 'StocksController@stocksbyFamarcia');
Route::get('/stocksbyFamarciaModal/{iduser}', 'StocksController@stocksbyFamarciaModal');
Route::get('/stocksbyFamarciasLegacy/{idfarmacia}/{situacion?}', 'StocksController@stocksbyFamarcias');
Route::get('/addstock/{iduser}', 'StocksController@addstock');
Route::get('/add_kardex_sinicial', 'StocksController@add_kardex_sinicial');
Route::get('/formulario_add_guia/{iduser}/{id_requerimiento}', 'StocksController@formulario_add_guia');
//Route::get('/stockFarmaciaByEstablecimientoLegacy', 'StocksController@stockFarmaciaByEstablecimiento');
//Route::post('stockFarmaciaByEstablecimiento', [
//  'uses' => 'StocksController@stockFarmaciaByEstablecimiento',
//  'as' => 'stockFarmaciaByEstablecimiento'
//]);
// LEGACY - FIN

Route::get('/imprimir_add_medicamentos/{iduser}/{id_requerimiento}', 'ExcelController@imprimir_add_medicamentos');

Route::get('/formulario_add_juntamedica/{iduser}', 'StocksController@formulario_add_juntamedica');

Route::get('/imprimir_stock_byiduser/{iduser}/{situacion?}', 'ExcelController@imprimir_stock_byiduser');

Route::get('/imprimir_stock_byidfarmacia/{idfarmacia}/{situacion?}', 'ExcelController@imprimir_stock_byidfarmacia');

Route::get('/imprimir_constock_byidfarmacia/{idfarmacia}', 'ExcelController@imprimir_constock_byidfarmacia');

Route::get('/imprimir_sinstock_byidfarmacia/{idfarmacia}', 'ExcelController@imprimir_sinstock_byidfarmacia');

Route::get('/imprimir_minstock_byidfarmacia/{idfarmacia}', 'ExcelController@imprimir_minstock_byidfarmacia');

Route::get('/imprimir_stock_tipo_producto/{idfarmacia}/{tipo}', 'ExcelController@imprimir_stock_tipo_producto');

Route::get('/imprimir_requerimientos/{iduser}/{id_requerimiento}', 'ExcelController@imprimir_requerimientos');

Route::get('/imprimir_ici/{fecinicio}/{fecfin}/{mes}/{iduser}', 'ExcelController@imprimir_ici');

Route::get('/imprimir_productos_vencidos/{id_farmacia}', 'ExcelController@imprimir_productos_vencidos');

Route::get('/imprimir_productos_proximos_vencer/{id_farmacia}/{dias}', 'ExcelController@imprimir_productos_proximos_vencer');

Route::get('/imprimir_productos_porlotes/{id_farmacia}/{fecha_fin}', 'ExcelController@imprimir_productos_porlotes');

// LEGACY
Route::get('/productos_sinstock/{id_farmacia}', 'StocksController@productos_sinstock');
Route::get('/productos_constock/{id_farmacia}', 'StocksController@productos_constock');
Route::get('/productos_porlotesLegacy/', 'StocksController@productos_porlotes');
Route::post('productos_porlotesLegacy/', [
  'uses' => 'StocksController@productos_porlotes',
  'as' => 'productos_porlotes'
]);
// LEGACY FIN
Route::get('/productos_minstock/{id_farmacia}', 'StocksController@productos_minstock');
Route::get('/productos_proximos_vencerLegacy/{id_farmacia}', 'StocksController@productos_proximos_vencer');
Route::get('/productos_vencidosLegacy/{id_farmacia}', 'StocksController@productos_vencidos');
Route::get('/productos_por_tipoLegacy/{id_farmacia}/{tipo}', 'StocksController@productos_por_tipo');

Route::get('/pecosa_anterior_kardex/{numero}/{id_farmacia}/{fecha}', 'StocksController@pecosa_anterior_kardex');
Route::get('/ver_requerimiento/{iduser?}', 'StocksController@ver_requerimientos');
Route::get('/all_requerimientos_recibidos/{iduser?}', 'StocksController@all_requerimientos_recibidos');
Route::get('/ver_productos_del_pedido/{id_requerimiento}/{id_tipo_producto_egestion}/{id_farmacia}', 'StocksController@ver_productos_del_pedido');
Route::get('/ver_productos_del_pedido_enviado/{id_requerimiento}/{id_tipo_producto_egestion}/{id_farmacia}', 'StocksController@ver_productos_del_pedido_enviado');
Route::get('/seleccion_requerimiento/{iduser}', 'StocksController@seleccion_requerimiento');

Route::get('/eliminar_producto_del_pedido/{id_req_producto}/{id_requerimiento}/{tipo_producto_egestion}/{id_farmacia}', 'StocksController@eliminar_producto_del_pedido');
Route::get('/editar_producto_del_pedido/{id_req_producto}/{id_requerimiento}/{tipo_producto_egestion}/{id_farmacia}', 'StocksController@editar_producto_del_pedido');


Route::post('update_producto_del_pedido', [
  'uses' => 'StocksController@update_producto_del_pedido',
  'as' => 'update_producto_del_pedido'
]);

Route::post('enviar_pedido_almacen', [
  'uses' => 'StocksController@enviar_pedido_almacen',
  'as' => 'enviar_pedido_almacen'
]);

Route::post('buscar_prodvencidos_porfecha', [
  'uses' => 'StocksController@buscar_prodvencidos_porfecha',
  'as' => 'buscar_prodvencidos_porfecha'
]);

Route::get('/buscar_prodvencidos_porfecha', 'StocksController@buscar_prodvencidos_porfecha');

Route::post('add_producto_al_pedido', [
  'uses' => 'StocksController@add_producto_al_pedido',
  'as' => 'add_producto_al_pedido'
]);

Route::post('busqueda_receta_personalizada', [
  'uses' => 'StocksController@busqueda_receta_personalizada',
  'as' => 'busqueda_receta_personalizada'
]);

Route::post('update_receta_personalizada', [
  'uses' => 'StocksController@update_receta_personalizada',
  'as' => 'update_receta_personalizada'
]);
Route::post('opcion_ingreso_regular', [
  'uses' => 'StocksController@opcion_ingreso_regular',
  'as' => 'opcion_ingreso_regular'
]);

Route::post('add_stock_farmacia', [
  'uses' => 'StocksController@add_stock_farmacia',
  'as' => 'add_stock_farmacia'
]);

Route::post('actualizar_stock_farmacia', [
  'uses' => 'StocksController@actualizar_stock_farmacia',
  'as' => 'actualizar_stock_farmacia'
]);

Route::get('/stocksbyFamarciaAndConsultorio', 'StocksController@stocksbyFamarciaAndConsultorio');
Route::post('stocksbyFamarciaAndConsultorioPost', [
  'uses' => 'StocksController@stocksbyFamarciaAndConsultorioPost',
  'as' => 'stocksbyFamarciaAndConsultorio'
]);

Route::resource('stocks', 'StocksController');
Route::get('/formato_importar_stock/{iduser}', 'ExcelController@formato_importar_stock');
Route::get('/formulario_importar_stock/{iduser}', 'ImportacionesController@formulario_importar_stock');
Route::get('/actualiza_req_productos/', 'ProductoController@actualiza_req_productos');
Route::get('/formulario_add_adicionales/{iduser}/{id_requerimiento}', 'StocksController@formulario_add_adicionales');

Route::post('subir_stock_farmacias', [
  'uses' => 'ImportacionesController@subir_stock_farmacias',
  'as' => 'subir_stock_farmacias'
]);

Route::post('registrar_adicionales_requerimiento', [
  'uses' => 'StocksController@registrar_adicionales_requerimiento',
  'as' => 'registrar_adicionales_requerimiento'
]);


Route::post('add_guia', [
  'uses' => 'StocksController@add_guia',
  'as' => 'add_guia'
]);

Route::post('otros_resporte_by_farmacia', [
  'uses' => 'StocksController@otros_resporte_by_farmacia',
  'as' => 'otros_resporte_by_farmacia'
]);
Route::get('/otros_resporte_by_farmacia', 'StocksController@otros_resporte_by_farmacia');

Route::get('/inicio', 'Frontend\FrontendController@index');

Route::get('/registrar_pedido', 'Frontend\FrontendController@index');
Route::get('/registrar_admision', 'Frontend\FrontendController@index');
Route::get('/registrar_cita', 'Frontend\FrontendController@index');
Route::get('/registrar_prestacion', 'Frontend\FrontendController@index');
Route::get('/registrar_centro_quirurgico', 'Frontend\FrontendController@index');

Route::get('/prescriptoresByIpress/{id_farmacia}', 'MedicoController@prescriptoresByIpress');
Route::get('/otros_reportes_by_farma/{tipo?}/{id_farmacia?}', 'StocksController@otros_reportes_by_farma');
Route::get('/importar_desabastecimiento_porfarma/{fecha_inicio}/{fecha_fin}/{id_farmacia}', 'ExcelController@importar_desabastecimiento_porfarma')->middleware('check.server.resources');
Route::get('/importar_prod_atendidos_porfarma/{fecha_inicio}/{fecha_fin}/{id_farmacia}', 'ExcelController@importar_prod_atendidos_porfarma');//->middleware('check.server.resources');

Route::get('/descarga_reportes/{fecha_inicio}/{fecha_fin}/', 'ExcelController@descarga_reportes')->middleware('check.server.resources');

Route::get('/exportar_reporte_por_diagnosticos/{fecha_inicio}/{fecha_fin}/{id_farmacia}', 'ExcelController@exportar_reporte_por_diagnosticos')->middleware('check.server.resources');
Route::get('/exportar_reporte_por_medicos/{fecha_inicio}/{fecha_fin}/{id_farmacia}', 'ExcelController@exportar_reporte_por_medicos')->middleware('check.server.resources');
Route::get('/exportar_reporte_por_beneficiario/{fecha_inicio}/{fecha_fin}/{id_farmacia}', 'ExcelController@exportar_reporte_por_beneficiario')->middleware('check.server.resources');
Route::get('/exportar_reporte_por_beneficiario2/{fecha_inicio}/{fecha_fin}/{id_farmacia}', 'ExcelController@exportar_reporte_por_beneficiario2')->middleware('check.server.resources');
Route::get('/exportar_reporte_por_consultorios/{fecha_inicio}/{fecha_fin}/{id_farmacia}', 'ExcelController@exportar_reporte_por_consultorios')->middleware('check.server.resources');
Route::get('/exportar_reporte_por_consultorios2/{fecha_inicio}/{fecha_fin}/{id_farmacia}', 'ExcelController@exportar_reporte_por_consultorios2')->middleware('check.server.resources');
Route::get('/exportar_reporte_productos_por_servicios/{id_consultorio}/{fecha_inicio}/{fecha_fin}/{id_farmacia}', 'ExcelController@exportar_reporte_productos_por_servicios')->middleware('check.server.resources');

Route::get('/exportar_reporte_receta_por_beneficiario/{fecha_inicio}/{fecha_fin}/{id_farmacia}', 'ExcelController@exportar_reporte_receta_por_beneficiario')->middleware('check.server.resources');
Route::get('/exportar_stocksbyFamarciaAndConsultorio/{id_consultorio}/{id_user}', 'ExcelController@exportar_stocksbyFamarciaAndConsultorio');//->middleware('check.server.resources');
//Route::get('/exportar_stockFarmaciaByEstablecimiento/{id_establecimiento}', 'ExcelController@exportar_stockFarmaciaByEstablecimiento');

Route::get('/exportar_lista_beneficiario/{fecha_inicio}/{fecha_fin}/{id_farmacia}', 'ExcelController@exportar_lista_beneficiario')->middleware('check.server.resources');

Route::get('/exportar_reporte_productos_atendidos/{fecha_inicio}/{fecha_fin}/{nombre_farmacia}', 'ExcelController@exportar_reporte_productos_atendidos')->middleware('check.server.resources');
Route::get('/exportar_reporte_productos_fecha_vencimiento/{fecha_inicio}/{fecha_fin}/{id_farmacia}/{nombre_farmacia}', 'ExcelController@exportar_reporte_productos_fecha_vencimiento');//->middleware('check.server.resources');
Route::get('/exportar_reporte_productos_sin_stock/{fecha_inicio}/{fecha_fin}/{id_farmacia}', 'ExcelController@exportar_reporte_productos_sin_stock')->middleware('check.server.resources');

Route::get('/exportar_reporte_admision/{fecha_inicio}/{fecha_fin}/{id_farmacia}/{dni}/{nro_admision}', 'ExcelController@exportar_reporte_admision')->middleware('check.server.resources');
Route::get('/exportar_reporte_receta/{fecha_inicio}/{fecha_fin}/{id_farmacia}/{dni}/{nro_receta}/{estado}/{iddispensador}/{idc}', 'ExcelController@exportar_reporte_receta');//->middleware('check.server.resources');
Route::get('/exportar_reporte_servicio/{fecha_inicio}/{fecha_fin}/{id_farmacia}/{id_consultorio}', 'ExcelController@exportar_reporte_servicio')->middleware('check.server.resources');
Route::get('/exportar_reporte_trama/{fecha_inicio}/{fecha_fin}/{id_establecimiento}', 'ExcelController@exportar_reporte_trama')->middleware('check.server.resources');
Route::get('/exportar_hospitalizados/{fecha_inicio}/{fecha_fin}/{id_farmacia}/{dni}/{nro_hospitalizacion}/{estado}/{iddispensador}', 'ExcelController@exportar_hospitalizados')->middleware('check.server.resources');
Route::get('/exportar_reporte_quirurgico/{fecha_inicio}/{fecha_fin}/{id_farmacia}/{dni}/{nro_admision}', 'ExcelController@exportar_reporte_quirurgico')->middleware('check.server.resources');
Route::get('/exportar_reporte_kardex_lote/{fecha_inicio}/{fecha_fin}/{id_producto}/{id_user}', 'ExcelController@exportar_reporte_kardex_lote');//->middleware('check.server.resources');
Route::get('/exportar_reporte_movimiento_producto_receta/{fecha_inicio}/{fecha_fin}/{id_producto}/{id_user}', 'ExcelController@exportar_reporte_movimiento_producto_receta')->middleware('check.server.resources');
Route::get('/exportar_reporte_recetas_by_producto/{id_farmacia}/{id_producto}/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_recetas_by_producto')->middleware('check.server.resources');
//Route::get('/exportar_consolidado_stock/{fecha_inicio}/{fecha_fin}/{id_user}/{id_anaquel}/{id_consultorio}', 'ExcelController@exportar_consolidado_stock')->middleware('check.server.resources');
Route::get('/exportar_consolidado_stock/{fecha_inicio}/{fecha_fin}/{id_user}/{id_anaquel}/{id_consultorio}', 'ExcelController@exportar_consolidado_stock');
Route::get('/exportar_reporte_cita/{idUser}/{fecha_ini}/{fecha_fin}/{fecha_reg_ini}/{fecha_reg_fin}/{dni}/{paciente}/{estado}/{id_servicio}/{id_consultorio}/{id_medico}/{id_condicion_establecimiento}/{id_condicion_servicio}', 'ExcelController@exportar_reporte_cita');//->middleware('check.server.resources');
Route::get('/exportar_gestante/{idUser}/{fecha_ini}/{fecha_fin}/{fecha_reg_ini}/{fecha_reg_fin}/{dni}/{paciente}/{estado}/{id_servicio}/{id_consultorio}/{id_medico}/{id_condicion_establecimiento}/{id_condicion_servicio}', 'ExcelController@exportar_reporte_gestando')->middleware('check.server.resources');
Route::get('/exportar_reporte_para_callcenter/{idUser}/{fecha_ini}/{fecha_fin}/{fecha_reg_ini}/{fecha_reg_fin}/{dni}/{paciente}/{estado}/{id_servicio}/{id_consultorio}/{id_medico}/{id_condicion_establecimiento}/{id_condicion_servicio}', 'ExcelController@exportar_reporte_para_callcenter')->middleware('check.server.resources');

Route::get('/exportar_reporte_atencion/{idUser}/{fecha_ini}/{fecha_fin}/{dni}/{paciente}/{estado}/{id_servicio}/{id_consultorio}/{id_medico}/{id_atencione}', 'ExcelController@exportar_reporte_atencion');//->middleware('check.server.resources');
Route::post('/exportar_reporte_atencion_detallado', 'ExcelController@exportar_reporte_atencion_detallado');
//Route::post('/exportar_reporte_atencion_detallado_consejeria', 'ExcelController@exportar_reporte_atencion_detallado_consejeria');//->middleware('check.server.resources');
Route::post('/exportar_reporte_atencion_detallado_nuevo', 'ExcelController@exportar_reporte_atencion_detallado_nuevo');
Route::get('/exporta_reporte_productividad_medico/{fecha_ini}/{fecha_fin}', 'ExcelController@exportar_reporte_productividad_medico')->middleware('check.server.resources');

Route::get('/imprimir_reporte_historia/{idUser}/{fecha_ini}/{fecha_fin}/{fecha_reg_ini}/{fecha_reg_fin}/{dni}/{paciente}/{estado}/{id_servicio}/{id_consultorio}/{id_medico}', 'PdfController@imprimir_reporte_historia');

Route::get('/exportar_ordenes_ingresadas/{fecha_inicio}/{fecha_fin}/{id_farmacia}/{num_doc}/{num_siaf}/{reg_sanitario}/{lote}/{l_origen}/{id_proveedor}/{id_tipo_proceso}/{id_tipo_producto}/{idregistrador}/{id_producto}', 'ExcelController@exportar_ordenes_ingresadas')->middleware('check.server.resources');

Route::get('/exportar_ingresos_farmacia/{fecha_inicio}/{fecha_fin}/{id_farmacia}/{num_doc}/{reg_sanitario}/{lote}/{id_tipo_producto}/{id_producto}', 'ExcelController@exportar_ingresos_farmacia');//->middleware('check.server.resources');

Route::get('/exportar_ordenes_salidas/{fecha_inicio}/{fecha_fin}/{id_farmacia}/{reg_sanitario}/{lote}/{l_dependencia}/{l_establecimiento}/{id_tipo_producto}/{id_producto}/{estado}', 'ExcelController@exportar_ordenes_salidas')->middleware('check.server.resources');
Route::get('/exportar_salidas_farmacia/{fecha_inicio}/{fecha_fin}/{id_farmacia}/{reg_sanitario}/{lote}/{id_tipo_producto}/{id_producto}', 'ExcelController@exportar_salidas_farmacia')->middleware('check.server.resources');

//Route::get('/exportar_reporte_ici/{fecha_inicio}/{fecha_fin}/{id_farmacia}/{tipo}', 'ExcelController@exportar_reporte_ici');
Route::post('/exportar_reporte_ici/', 'ExcelController@exportar_reporte_ici');//->middleware('check.server.resources');

Route::post('form_demanda_nosatisfecha', [
  'uses' => 'StocksController@form_demanda_nosatisfecha',
  'as' => 'form_demanda_nosatisfecha'
]);

Route::get('/form_generar_rerporte_ici_by_farma/{id_farmacia}', 'StocksController@form_generar_rerporte_ici_by_farma');
Route::get('/generar_reporte_demanda_nosatisfecha/{id_farmacia}', 'StocksController@generar_reporte_demanda_nosatisfecha');

Route::post('generar_reporte_ici_by_farma', [
  'uses' => 'StocksController@generar_reporte_ici_by_farma',
  'as' => 'generar_reporte_ici_by_farma'
]);

Route::post('generar_ici', [
  'uses' => 'StocksController@generar_reporte_ici',
  'as' => 'generar_ici'
]);
Route::get('/generar_ici', 'StocksController@generar_reporte_ici');

Route::post('add_junta_medica', [
  'uses' => 'StocksController@add_junta_medica',
  'as' => 'add_junta_medica'
]);
Route::get('/formulario_consumo_diario/{iduser}', 'StocksController@formulario_consumo_diario');
Route::get('/formulario_salida_diaria/{iduser}', 'StocksController@formulario_salida_diaria');
Route::get('/eliminar_pedido/{id_requerimiento}/{iduser}', 'StocksController@eliminar_pedido');
Route::get('/formulario_consumo_juntamedica/{iduser}', 'StocksController@formulario_consumo_juntamedica');

//Route::get('/formulario_solicitar_requerimiento/{iduser}', 'StocksController@formulario_solicitar_requerimiento');

Route::post('registrar_pedido', [
  'uses' => 'StocksController@registrar_pedido',
  'as' => 'registrar_pedido'
]);
Route::post('update_pedido', [
  'uses' => 'StocksController@update_pedido',
  'as' => 'update_pedido'
]);

Route::get('/reporte_salidad_diaria/{iduser}', 'StocksController@reporte_salidad_diaria');

Route::post('reporte_salida_diaria_ok', [
  'uses' => 'StocksController@reporte_salida_diaria_ok',
  'as' => 'reporte_salida_diaria_ok'
]);

Route::post('registrar_requerimiento', [
  'uses' => 'StocksController@registrar_requerimiento',
  'as' => 'registrar_requerimiento'
]);

Route::post('generar_requerimiento', [
  'uses' => 'StocksController@generar_requerimiento',
  'as' => 'generar_requerimiento'
]);

Route::post('add_productos_sinrequerimiento', [
  'uses' => 'StocksController@add_productos_sinrequerimiento',
  'as' => 'add_productos_sinrequerimiento'
]);

Route::get('/generar_reporte_ici/{iduser}', 'StocksController@generar_reporte_ici');
Route::get('/reporte_juntas_medicas/{id_farmacia}', 'StocksController@reporte_juntas_medicas');

Route::post('add_consumo_juntamedica', [
  'uses' => 'StocksController@add_consumo_juntamedica',
  'as' => 'add_consumo_juntamedica'
]);

Route::post('add_consumo_diario', [
  'uses' => 'StocksController@add_consumo_diario',
  'as' => 'add_consumo_diario'
]);

Route::post('update_kardex_sinicial', [
  'uses' => 'StocksController@update_kardex_sinicial',
  'as' => 'update_kardex_sinicial'
]);

Route::post('anadir_kardex_sinicial', [
  'uses' => 'StocksController@anadir_kardex_sinicial',
  'as' => 'anadir_kardex_sinicial'
]);

Route::get('/modificar_kardex_sinicial/{id_kardex}', 'StocksController@modificar_kardex_sinicial');
Route::get('/editar_kardex_sinicial/{id_user}', 'StocksController@editar_kardex_sinicial');

//------------------------------------------------------------------------------
//-------------RUTAS PARA RECETAS-----------------------------------------------
Route::get('/atender_receta/{iduser}', 'RecetasController@search');
Route::get('/resumen_diario/{iduser?}', 'RecetaValeController@resumen_diario');
Route::get('/recetas_atendidas/{iduser}', 'RecetasController@recetas_atendidas');
Route::get('/recetas_invalidadas/{id_farmacia}/{id_user}', 'RecetasController@recetas_invalidadas');
Route::get('/editar_medicamento/{id_receta_stock}', 'RecetasController@editar_medicamento');
Route::get('/buscar_medico', 'MedicoController@buscar_medico');

Route::get('/prueba_webservice', 'RecetaValeController@prueba_webservice');


Route::post('add_receta', [
  'uses' => 'RecetasController@recetabynro_historia',
  'as' => 'add_receta'
]);

Route::post('buscar_medico_dni', [
  'uses' => 'MedicoController@buscar_medico_dni',
  'as' => 'buscar_medico_dni'
]);
Route::get('/buscar_medico_dni', 'MedicoController@buscar_medico_dni');

Route::post('listar_medicos_ajax', [
  'uses' => 'MedicoController@listar_personal_ajax',
  'as' => 'listar_personal_ajax'
]);

Route::post('listar_medicos_ajax_general', [
  'uses' => 'MedicoController@listar_medicos_ajax_general',
  'as' => 'listar_medicos_ajax_general'
]);

Route::post('listar_medicos_by_ipress', [
  'uses' => 'MedicoController@listar_medicos_ajax_por_ipress',
  'as' => 'listar_medicos_by_ipress'
]);

Route::post('listar_recetas', [
  'uses' => 'RecetasController@listar_recetas',
  'as' => 'listar_recetas'
]);

Route::post('listar_recetas_nuevo', [
  'uses' => 'RecetasController@listar_recetas_nuevo',
  'as' => 'listar_recetas_nuevo'
]);

Route::get('/listar_recetas_nuevo', 'RecetasController@listar_recetas_nuevo');

Route::post('listar_recetas_prescrita', [
  'uses' => 'RecetasController@listar_recetas_prescrita',
  'as' => 'listar_recetas_prescrita'
]);

Route::get('/listar_recetas_prescrita', 'RecetasController@listar_recetas_prescrita');


Route::post('listar_beneficiarios_servicios', [
  'uses' => 'RecetasController@listar_beneficiarios_servicios',
  'as' => 'listar_beneficiarios_servicios'
]);

Route::get('/listar_beneficiarios_servicios', 'RecetasController@listar_beneficiarios_servicios');

Route::post('listar_trama', [
  'uses' => 'RecetasController@listar_trama',
  'as' => 'listar_trama'
]);

Route::get('/listar_trama', 'RecetasController@listar_trama');

Route::post('listar_hospitalizados', [
  'uses' => 'RecetasController@listar_hospitalizados',
  'as' => 'listar_hospitalizados'
]);

Route::get('/listar_hospitalizados', 'RecetasController@listar_hospitalizados');

Route::post('listar_admision', [
  'uses' => 'RecetasController@listar_admision',
  'as' => 'listar_admision'
]);
Route::get('/listar_admision', 'RecetasController@listar_admision');

Route::post('listar_quirurgico', [
  'uses' => 'RecetasController@listar_quirurgico',
  'as' => 'listar_quirurgico'
]);

Route::get('/listar_quirurgico', 'RecetasController@listar_quirurgico');

Route::post('listar_resumen_recetas_atendidas', [
  'uses' => 'RecetasController@listar_resumen_recetas_atendidas',
  'as' => 'listar_resumen_recetas_atendidas'
]);

Route::get('/listar_resumen_recetas_atendidas', 'RecetasController@listar_resumen_recetas_atendidas');

Route::post('listar_kardex', [
  'uses' => 'RecetasController@listar_kardex',
  'as' => 'listar_kardex'
]);

Route::get('/listar_kardex', 'RecetasController@listar_kardex');

Route::post('listar_kardex_lote_movimiento_producto_receta', [
  'uses' => 'RecetasController@listar_kardex_lote_movimiento_producto_receta',
  'as' => 'listar_kardex_lote_movimiento_producto_receta'
]);

Route::get('/listar_kardex_lote_movimiento_producto_receta', 'RecetasController@listar_kardex_lote_movimiento_producto_receta');

Route::post('consolidado_stock', [
  'uses' => 'RecetasController@consolidado_stock',
  'as' => 'consolidado_stock'
]);
Route::get('/consolidado_stockLegacy', 'RecetasController@consolidado_stock')->middleware('check.server.resources');
//Route::get('/consolidado_stock', 'RecetasController@consolidado_stock');

Route::post('listar_cita', [
  'uses' => 'RecetasController@listar_cita',
  'as' => 'listar_cita'
]);
Route::get('/listar_cita', 'RecetasController@listar_cita');

Route::post('listar_hospitalizacion', [
  'uses' => 'RecetasController@listar_hospitalizacion',
  'as' => 'listar_hospitalizacion'
]);

Route::get('/listar_hospitalizacion', 'RecetasController@listar_hospitalizacion');

Route::post('listar_recetas_invalidadas', [
  'uses' => 'RecetasController@listar_recetas_invalidadas',
  'as' => 'listar_recetas_invalidadas'
]);

Route::post('add_recetas_by_paciente', [
  'uses' => 'RecetasController@recetasby_dni',
  'as' => 'add_recetas_by_paciente'
]);
Route::post('add_datos_receta', [
  'uses' => 'RecetasController@add_datos_receta',
  'as' => 'add_datos_receta'
]);
Route::post('medicamento_entregado', [
  'uses' => 'RecetasController@medicamento_entregado',
  'as' => 'medicamento_entregado'
]);

Route::post('post_resumen_diario', [
  'uses' => 'RecetaValeController@post_resumen_diario',
  'as' => 'post_resumen_diario'
]);

Route::get('/post_resumen_diario', 'RecetaValeController@post_resumen_diario');

Route::post('update_medicamento_atendido', [
  'uses' => 'RecetasController@update_medicamento_atendido',
  'as' => 'update_medicamento_atendido'
]);
//---------------- RUTAS DE TRANFERENCIAS --------------------------------------------
Route::get('/all_transferencias/{id_farmacia?}', 'TransferenciaController@index');
Route::get('/all_transferencias_almacen/{id_farmacia}', 'TransferenciaController@index_almacen');
Route::get('/mostrar_productos_recibidos/{id_transferencia}', 'TransferenciaController@mostrar_productos_recibidos');
Route::get('/all_transferencias_recibidas/{id_farmacia?}', 'TransferenciaController@all_transferencias_recibidas');
Route::get('/form_transferencia/{id_user}', 'TransferenciaController@create');
Route::get('/add_documento_transferencia/{id_user}', 'TransferenciaController@add_documento_transferencia');
Route::get('/add_documento_transferencia_almacen/{id_user}', 'TransferenciaController@add_documento_transferencia_almacen');

Route::get('/edit_documento_transferencia/{id_user}', 'TransferenciaController@edit_documento_transferencia');

Route::get('/registrar_productos_transferir/{id_transferencia}/{id_farmacia}', 'TransferenciaController@registrar_productos_transferir');
Route::get('/obtener_lote_byIdlote_Idfarmacia/{id_lote}/{id_farmacia}', 'TransferenciaController@obtener_lote_byIdlote_Idfarmacia');
Route::get('/obtener_Cantidad_by_CodigoProducto_Lote_FechaVencimiento_RegistroSanitario_Idfarmacia/{codigo_producto}/{lote}/{fecha_vencimiento}/{registro_sanitario}/{id_farmacia}/{precio_unitario}/{id_transferencia}', 'TransferenciaController@obtener_Cantidad_by_CodigoProducto_Lote_FechaVencimiento_RegistroSanitario_Idfarmacia');
Route::get('/obtenerLoteByIdProductoAndIdfarmacia/{id_producto}/{id_farmacia}', 'TransferenciaController@obtenerLoteByIdProductoAndIdfarmacia');
Route::get('/existeTransferenciaPendienteByProducto/{id_producto}/{id_farmacia}/{id_transferencia}', 'TransferenciaController@existeTransferenciaPendienteByProducto');

Route::get('/cerrar_carga_transferencia/{id_transferencia}/{tipo}/{id_farmacia}', 'TransferenciaController@cerrar_carga_transferencia');
Route::get('/cambiar_estado_transferencia/{id_transferencia}/{tipo}/{id_farmacia}', 'TransferenciaController@cambiar_estado_transferencia');

Route::get('/eliminar_producto_para_transferir/{id_transferencia_producto}/{id_farmacia}', 'TransferenciaController@eliminar_producto_para_transferir');
Route::get('/editar_producto_para_transferir/{id_transferencia_producto}', 'TransferenciaController@editar_producto_para_transferir');

Route::get('/add_producto_anaquel/{id_anaquel}', 'AnaquelController@add_producto_anaquel');
Route::get('/eliminar_producto_anaquel/{id}', 'AnaquelController@eliminar_producto_anaquel');

Route::get('/add_producto_servicio/{id_servicio}/{id_farmacia}/{tipo}', 'ConsultorioController@add_producto_servicio');
Route::get('/add_tipo_atencion_servicio/{id_servicio}/{id_farmacia}', 'ConsultorioController@add_tipo_atencion_servicio');
Route::get('/eliminar_producto_en_servicio/{id}', 'ConsultorioController@eliminar_producto_en_servicio');
Route::get('/eliminar_tipo_atencion_servicio_farmacia/{id}', 'ConsultorioController@eliminar_tipo_atencion_servicio_farmacia');

Route::get('/add_centro_atencion_servicio/{id_servicio}/{tipo}', 'ConsultorioController@add_centro_atencion_servicio');
Route::get('/add_upss_servicio/{id_servicio}', 'ConsultorioController@add_upss_servicio');
Route::get('/desactivar_centro_atencion_servicio/{id}/{estado}', 'ConsultorioController@desactivar_centro_atencion_servicio');

Route::get('/desactivar_consultorio_servicio/{id}/{estado}', 'ConsultorioController@desactivar_consultorio_servicio');
Route::get('/desactivar_upss_servicio/{id}/{estado}', 'ConsultorioController@desactivar_upss_servicio');
Route::get('/desactivar_consultorio_turno/{id}/{estado}', 'ConsultorioController@desactivar_consultorio_turno');

Route::get('/add_consultorios_servicio/{id_servicio}', 'ConsultorioController@add_consultorios_servicio');
Route::get('/add_procprogramados_turnos/{id_sub_consultorio}/{id_servicio}', 'ConsultorioController@add_procprogramados_turnos');
Route::post('/pp_guardar_turno_dia', 'ConsultorioController@pp_guardar_turno_dia');
Route::post('/pp_estado_turno_dia', 'ConsultorioController@pp_estado_turno_dia');
Route::post('/pp_eliminar_turno_dia', 'ConsultorioController@pp_eliminar_turno_dia');
Route::post('/pp_copiar_turno_dias', 'ConsultorioController@pp_copiar_turno_dias');
Route::get('/add_procprogramados_solicitud/{id_servicio}', 'ConsultorioController@add_procprogramados_solicitud');
Route::get('/pp_buscar_procedimiento_convenio', 'ConsultorioController@pp_buscar_procedimiento_convenio');
Route::post('/pp_agregar_procedimiento_servicio', 'ConsultorioController@pp_agregar_procedimiento_servicio');
Route::post('/pp_estado_procedimiento_servicio', 'ConsultorioController@pp_estado_procedimiento_servicio');
Route::post('/pp_editar_procedimiento_servicio', 'ConsultorioController@pp_editar_procedimiento_servicio');
Route::post('/pp_requiere_informe_procedimiento_servicio', 'ConsultorioController@pp_requiere_informe_procedimiento_servicio');
Route::get('/pp_obtener_procedimiento_servicio', 'RecetaValeController@pp_obtener_procedimiento_servicio');
Route::get('/edit_subconsultorios_servicio/{id_sub_consultorio}', 'ConsultorioController@edit_consultorios_servicio');
Route::post('update_subconsultorios_servicio', [
  'uses' => 'ConsultorioController@update_consultorios_servicio',
  'as' => 'update_consultorios_servicio'
]);
Route::get('/add_consultorio_turnos/{id_sub_consultorio}/{id_servicio}', 'ConsultorioController@add_consultorio_turnos');


Route::post('enviar_productos_al_stock', [
  'uses' => 'TransferenciaController@enviar_productos_al_stock',
  'as' => 'enviar_productos_al_stock'
]);

Route::post('update_producto_transferencia', [
  'uses' => 'TransferenciaController@update_producto_transferencia',
  'as' => 'update_producto_transferencia'
]);

Route::post('agregar_producto_transferencia', [
  'uses' => 'TransferenciaController@agregar_producto_transferencia',
  'as' => 'agregar_producto_transferencia'
]);
Route::post('registrar_documento_transferencia', [
  'uses' => 'TransferenciaController@registrar_documento_transferencia',
  'as' => 'registrar_documento_transferencia'
]);

Route::post('editar_documento_transferencia', [
  'uses' => 'TransferenciaController@editar_documento_transferencia',
  'as' => 'editar_documento_transferencia'
]);


Route::get('/all_transferencias_salidas/{iduser?}', 'TransferenciaController@all_transferencias_salidas');

Route::get('/buscar_nota_salida/{nro_nota}', 'TransferenciaController@buscar_nota_salida');

Route::post('buscar_orden_salidas', [
  'uses' => 'TransferenciaController@buscar_orden_salidas',
  'as' => 'buscar_orden_salidas'
]);

Route::get('/buscar_orden_salidas', 'TransferenciaController@buscar_orden_salidas');

/*****************************************************************************/
Route::post('agregar_producto_anaquel', [
  'uses' => 'AnaquelController@agregar_producto_anaquel',
  'as' => 'agregar_producto_anaquel'
]);

Route::post('agregar_producto_servicio', [
  'uses' => 'ConsultorioController@agregar_producto_servicio',
  'as' => 'agregar_producto_servicio'
]);

Route::post('agregar_farmacia_servicio', [
  'uses' => 'ConsultorioController@agregar_farmacia_servicio',
  'as' => 'agregar_farmacia_servicio'
]);

Route::post('agregar_consultorio_servicio', [
  'uses' => 'ConsultorioController@agregar_consultorio_servicio',
  'as' => 'agregar_consultorio_servicio'
]);

Route::post('agregar_upss_servicio', [
  'uses' => 'ConsultorioController@agregar_upss_servicio',
  'as' => 'agregar_upss_servicio'
]);

Route::post('agregar_consultorio_turno', [
  'uses' => 'ConsultorioController@agregar_consultorio_turno',
  'as' => 'agregar_consultorio_turno'
]);

Route::get('editar_upss/{id_servicio}/{upss}', 'ConsultorioController@editar_upss');

Route::post('update_upss_servicio', [
  'uses' => 'ConsultorioController@update_upss_servicio',
  'as' => 'update_upss_servicio'
]);

Route::post('agregar_tipo_atencion_farmacia_servicio', [
  'uses' => 'ConsultorioController@agregar_tipo_atencion_farmacia_servicio',
  'as' => 'agregar_tipo_atencion_farmacia_servicio'
]);


//---------------- RUTAS DE RECETAS --------------------------------------------
Route::get('/carga_consultorios/{id_farmacia}', 'RecetaValeController@carga_consultorios');
Route::get('/modulo_recetas', 'RecetaValeController@index');
Route::get('/editar_datos_basico_receta/{id_farmacia}/{nro_receta}', 'RecetaValeController@editar_datos_basico_receta');

Route::get('/all_recetas_atendidas_by_servicio/{iduser?}', 'RecetaValeController@all_recetas_atendidas_by_servicio');
Route::get('/all_recetas_atendidas_by_farmacia/{iduser?}', 'RecetaValeController@all_recetas_atendidas_by_farmacia')->middleware('menu.permission:33');
Route::get('/all_recetas_prescritas_by_farmacia/{iduser?}', 'RecetaValeController@all_recetas_prescritas_by_farmacia')->middleware('menu.permission:85');
Route::get('/trama/{iduser?}', 'RecetaValeController@trama_saludpol');
Route::get('/all_hospitalizados_registrados_by_farmacia/{iduser?}', 'RecetaValeController@all_hospitalizados_registrados_by_farmacia')->middleware('menu.permission:36');
Route::get('/all_hospitalizado_by_farmacia/{iduser?}', 'RecetaValeController@all_hospitalizado_by_farmacia')->middleware('menu.permission:44');
Route::get('/all_hospitalizado_alta_by_farmacia/{iduser?}', 'RecetaValeController@all_hospitalizado_alta_by_farmacia')->middleware('menu.permission:66');
Route::get('/all_hospitalizacion_by_farmacia/{iduser?}', 'RecetaValeController@all_hospitalizacion_by_farmacia');
Route::get('/all_cita_by_farmacia/{iduser?}', 'RecetaValeController@all_cita_by_farmacia');
Route::get('/all_quirurgico_by_farmacia/{iduser?}', 'RecetaValeController@all_quirurgico_by_farmacia');
Route::get('/nueva_receta/{id_user?}/{id_farmacia?}', 'RecetaValeController@nueva_receta');
Route::get('/nueva_atencion_receta/{id_user?}/{id_farmacia?}', 'RecetaValeController@nueva_atencion_receta');
Route::get('/nueva_admision/{id_user?}/{id_farmacia?}', 'RecetaValeController@nueva_admision')->middleware('menu.permission:4');
Route::get('/buscar_receta/{id_user}', 'RecetaValeController@buscar_receta');
Route::get('/productividad_medicos', 'RecetaValeController@productividad_medicos');
Route::post('/get_productividad_medicos', 'RecetaValeController@get_productividad_medicos');

Route::get('/all_resumen_recetas_atendidas/{iduser?}', 'RecetaValeController@all_resumen_recetas_atendidas');

Route::get('/all_kardex_by_farmacia/{id_producto?}', 'RecetaValeController@all_kardex_by_farmacia');
Route::get('/all_kardex_no_atendido_by_farmacia/{id_producto?}', 'RecetaValeController@all_kardex_no_atendido_by_farmacia')->middleware('menu.permission:136');

Route::get('/nueva_cita/{id_user?}/{id_farmacia?}', 'RecetaValeController@nueva_cita');



Route::get('/nueva_hospitalizacion/{id_user?}/{id_farmacia?}', 'RecetaValeController@nueva_hospitalizacion');

Route::get('/nueva_emergencia/{id_user?}/{id_farmacia?}', 'RecetaValeController@nueva_emergencia');

Route::get('/nuevo_quirurgico', 'RecetaValeController@nuevo_quirurgico');
Route::get('/nuevo_nutricional', 'RecetaValeController@nuevo_nutricional');

Route::get('/nuevo_centro_quirurgico', 'RecetaValeController@nuevo_centro_quirurgico');

Route::get('/ver_receta_vale/{nro_receta}', 'PdfController@ver_receta_vale');
Route::get('/ver_receta_vale_sinfirma/{nro_receta}', 'PdfController@ver_receta_vale_sinfirma');
Route::get('/ver_prestacion_imprimir/{id_prestacion}', 'PdfController@ver_prestacion_imprimir');

Route::get('/pdf_receta_vale_alta/{nro_receta}', 'PdfController@ver_receta_vale_alta');
Route::get('/pdf_receta_vale_alta_sinfirma/{nro_receta}', 'PdfController@ver_receta_vale_alta_sinfirma');

Route::get('/ver_prestacion_imprimir_openpol/{id_prestacion}', 'ExcelController@ver_prestacion_imprimir_openpol');
Route::get('/prestacion_imprimir_openpol/{id_prestacion}', 'PdfController@prestacion_imprimir_openpol');


Route::get('/genera_csv_prestacion/{id_prestacion}/{id_transaccion}', 'ExcelController@genera_csv_prestacion');
Route::get('/validar_firma_prestacion/{id_prestacion}/{id_transaccion}', 'ExcelController@validar_firma_prestacion');

Route::get('/test_open', 'ExcelController@test_open');

Route::post('genera_csv', [
  'uses' => 'ExcelController@genera_csv',
  'as' => 'genera_csv'
]);

Route::post('genera_csvx', [
  'uses' => 'ExcelController@genera_csvx',
  'as' => 'genera_csvx'
]);

Route::post('validarFirma', [
  'uses' => 'ExcelController@validarFirma',
  'as' => 'validarFirma'
]);

Route::get('/subirFtpReceta', 'ExcelController@subirFtpReceta');
Route::get('/leerFtp', 'ExcelController@leerFtp');

//Route::get('/upload_firma/{file}', 'ExcelController@upload_firma');

Route::get('/testftp', 'ExcelController@testftp');

Route::get('/genera_qr', 'ExcelController@genera_qr');

Route::post('/upload_firma', [
  'uses' => 'ExcelController@upload_firma',
  'as' => 'upload_firma'
]);

Route::post('/upload_firma_openpol', [
  'uses' => 'ExcelController@upload_firma_openpol',
  'as' => 'upload_firma_openpol'
]);

Route::get('/imprimir_receta/{nro_receta}/{id_farmacia}/{observacion}', 'PdfController@imprimir_receta');
Route::get('/imprimir_receta_vale/{nro_receta}/{id_farmacia}', 'PdfController@imprimir_receta_vale');
Route::get('/imprimir_nuevo_hospitalizado/{nro_hospitalizacion}/{id_farmacia}/{id_user}', 'PdfController@imprimir_hospitalizado_nuevo');
Route::get('/imprimir_nuevo_alta/{id_hospitalizacion}/{id_farmacia}', 'PdfController@imprimir_nuevo_alta');
Route::get('/imprimir_admision/{nro_admision}/{id_farmacia}', 'PdfController@imprimir_admision');
Route::get('/imprimir_centro_quirurgico/{nro_quirurgico}/{id_farmacia}', 'PdfController@imprimir_centro_quirurgico');
Route::get('/imprimir_ficha_estadistica/{id_hospitalizacion}', 'PdfController@mostrarPdfHospitalizacionFichaEstadistica');
//Route::get('/imprimir_transferencia/{id}', 'PdfController@imprimir_transferencia');
Route::get('/imprimir_transferencia/{id}/{tipo?}', 'PdfController@imprimir_transferencia');
Route::get('/imprimir_nota_entrada/{id}', 'PdfController@imprimir_nota_entrada');
Route::get('/imprimir_nota_entrada_pecosa/{id}', 'PdfController@imprimir_nota_entrada_pecosa');
Route::get('/imprimir_requerimiento_pdf/{id}', 'PdfController@imprimir_requerimiento_pdf');
Route::get('/imprimir_consolidado_stock_pdf/{fecha_inicio}/{fecha_fin}/{id_user}/{id_anaquel}/{id_consultorio}', 'PdfController@imprimir_consolidado_stock_pdf')->middleware('check.server.resources');

Route::get('/imprimir_reporte_productos_por_servicios/{id_consultorio}/{fecha_inicio}/{fecha_fin}/{id_farmacia}', 'PdfController@imprimir_reporte_productos_por_servicios');
Route::get('/imprimir_consolidado_stock/{fecha_inicio}/{fecha_fin}/{id_user}/{id_anaquel}/{id_consultorio}', 'RecetasController@imprimir_consolidado_stock')->middleware('check.server.resources');
Route::get('/imprimir_receta_cirugia_valorizado/{id_receta}/{id_farmacia}', 'PdfController@imprimir_receta_cirugia_valorizado');
Route::get('/imprimir_receta_cirugia/{id_receta}/{id_farmacia}', 'PdfController@imprimir_receta_cirugia');

Route::get('/cargarstock_farmacia/{id_producto}/{id_user}', 'StocksController@cargarstock_farmacia');
Route::get('/cargarstock_farmacia2/{id_producto}/{id_user}', 'StocksController@cargarstock_farmacia2');
Route::get('/cargarstock_establecimiento/{id_producto}/{id_user}', 'StocksController@cargarstock_establecimiento');

Route::get('/buscar_codigo_producto/{codigo_producto}', 'StocksController@buscar_codigo_producto');

Route::get('/cargardestino/{tipo}/{id_establecimiento?}', 'TransferenciaController@cargardestino');
Route::get('/cargardestino_almacen/{tipo}/{id_farmacia}', 'TransferenciaController@cargardestino_almacen');


Route::post('actualizar_receta_datosbasicos', [
  'uses' => 'RecetaValeController@actualizar_receta_datosbasicos',
  'as' => 'actualizar_receta_datosbasicos'
]);


Route::post('add_carga_inicial', [
  'uses' => 'StocksController@add_carga_inicial',
  'as' => 'add_carga_inicial'
]);
Route::get('/obtenerCantAtencionesMesActualbyBeneficiario/{dni_beneficiario}/{id_consultorio}/{fecha_expedicion}/{id_medico}', 'RecetaValeController@obtenerCantAtencionesMesActualbyBeneficiario');
Route::get('/obtenerCantAtencionesMesActualbyBeneficiarioEmergencia/{dni_beneficiario}/{id_consultorio}/{fecha_expedicion}/{id_medico}', 'RecetaValeController@obtenerCantAtencionesMesActualbyBeneficiarioEmergencia');
Route::get('/obtenerCantAtencionesMesActualbyBeneficiarioHospitalizacion/{dni_beneficiario}/{id_consultorio}/{fecha_expedicion}/{id_medico}', 'RecetaValeController@obtenerCantAtencionesMesActualbyBeneficiarioHospitalizacion');
Route::get('/obtenerCantAtencionesMesActualbyBeneficiarioQuirurgico/{dni_beneficiario}/{id_consultorio}/{fecha_expedicion}/{id_medico}', 'RecetaValeController@obtenerCantAtencionesMesActualbyBeneficiarioQuirurgico');
Route::get('/obtenerCantAtencionesMesActualbyBeneficiarioNutricional/{dni_beneficiario}/{id_consultorio}/{fecha_expedicion}/{id_medico}', 'RecetaValeController@obtenerCantAtencionesMesActualbyBeneficiarioNutricional');
Route::get('/obtenerDetAtencionesMesActualbyBeneficiario/{dni_beneficiario}/{id_consultorio}/{fecha_expedicion}/{id_medico}', 'RecetaValeController@obtenerDetAtencionesMesActualbyBeneficiario');

Route::get('/obtenerCantInterconsultas/{dni_beneficiario}/{id_farmacia}', 'RecetaValeController@obtenerCantInterconsultas');

Route::get('/obtenerDetAtencionesInterconsultabyBeneficiario/{dni_beneficiario}/{id_consultorio}', 'RecetaValeController@obtenerDetAtencionesInterconsultabyBeneficiario');
Route::post('formulario_receta', [
  'uses' => 'RecetaValeController@formulario_receta',
  'as' => 'formulario_receta'
]);
Route::get('/formulario_receta/{id_user?}/{id_farmacia?}', 'RecetaValeController@formulario_receta')->middleware('menu.permission:82');

Route::get('/editar_receta_vale/{id_receta}', 'RecetaValeController@editar_receta_vale');

Route::post('formulario_receta_unificada', [
  'uses' => 'RecetaValeController@formulario_receta_unificada',
  'as' => 'formulario_receta_unificada'
]);

Route::post('formulario_receta_admision', [
  'uses' => 'RecetaValeController@formulario_receta_admision',
  'as' => 'formulario_receta_admision'
]);
Route::get('/modifica_registro_hospitalizado/{id_user}/{nro_hospitalizacion}', 'RecetaValeController@modifica_registro_hospitalizado');
Route::get('/modifica_alta_hospitalizado/{id_user}/{nro_hospitalizacion}', 'RecetaValeController@modifica_alta_hospitalizado');

Route::get('/alta_hospitalizado/{id_user}/{nro_hospitalizacion}', 'RecetaValeController@alta_hospitalizado');
Route::get('/formulario_receta_admision', 'RecetaValeController@formulario_receta_admision');
Route::get('/obtenerRecetasMesActualByBeneficiario/{dni_beneficiario}', 'RecetaValeController@obtenerRecetasMesActualByBeneficiario');
Route::get('/obtenerRecetasMesActualByBeneficiarioEmergencia/{dni_beneficiario}', 'RecetaValeController@obtenerRecetasMesActualByBeneficiarioEmergencia');
Route::get('/obtenerRecetasMesActualByBeneficiarioHospitalizacion/{dni_beneficiario}', 'RecetaValeController@obtenerRecetasMesActualByBeneficiarioHospitalizacion');
Route::get('/obtenerBeneficiarioByServicio/{dni_beneficiario}/{fecha_ini}/{fecha_fin}/{id_farmacia}/{id_consultorio}', 'RecetaValeController@obtenerBeneficiarioByServicio');
Route::get('/obtenerProductobyRecetasVale/{id_receta_vale}', 'RecetaValeController@obtenerProductobyRecetasVale');
Route::get('/obtenerProductosFarmaciasByNivel/{id_farmacia}/{tipo_receta}/{id_consultorio}', 'RecetaValeController@obtenerProductosFarmaciasByNivel');
Route::post('formulario_receta_hospitalizacion', [
  'uses' => 'RecetaValeController@formulario_receta_hospitalizacion',
  'as' => 'formulario_receta_hospitalizacion'
]);
Route::get('/formulario_receta_hospitalizacion', 'RecetaValeController@formulario_receta_hospitalizacion');

Route::post('formulario_receta_quirurgico', [
  'uses' => 'RecetaValeController@formulario_receta_quirurgico',
  'as' => 'formulario_receta_quirurgico'
]);
Route::get('/formulario_receta_hospitalizacion', 'RecetaValeController@formulario_receta_quirurgico');

Route::post('formulario_receta_emergencia', [
  'uses' => 'RecetaValeController@formulario_receta_emergencia',
  'as' => 'formulario_receta_emergencia'
]);
Route::get('/formulario_receta_emergencia', 'RecetaValeController@formulario_receta_emergencia');

Route::post('formulario_receta_nutricional', [
  'uses' => 'RecetaValeController@formulario_receta_nutricional',
  'as' => 'formulario_receta_nutricional'
]);
Route::get('/formulario_receta_nutricional', 'RecetaValeController@formulario_receta_nutricional');


Route::post('formulario_admision', [
  'uses' => 'RecetaValeController@formulario_admision',
  'as' => 'formulario_admision'
]);

Route::get('/formulario_admision', 'RecetaValeController@formulario_admision');

Route::post('formulario_cita', [
  'uses' => 'RecetaValeController@formulario_cita',
  'as' => 'formulario_cita'
]);

Route::get('/formulario_cita', 'RecetaValeController@formulario_cita')->middleware('menu.permission:81');
Route::get('/formulario_atencion/{id_cita}', 'RecetaValeController@formulario_atencion');
Route::get('/consultarExistenciaContraReferenciaPendiente/{id_servicio}/{dni_beneficiario}', 'RecetaValeController@consultarExistenciaContraReferenciaPendiente');
Route::get('/formulario_atencion_nuevo', 'RecetaValeController@formulario_atencion_nuevo');
Route::get('recetas/historial/{dni}', 'RecetaValeController@GetHistorialByDni');
Route::get('/formulario_atencion_nuevo_emergencia', 'RecetaValeController@formulario_atencion_nuevo_sinreceta');
Route::get('/formulario_recetanuevo', 'RecetaValeController@formulario_recetanuevo');
Route::get('/formulario_atencion_nuevo_sinReceta', 'RecetaValeController@formulario_atencion_nuevo_sinreceta');
Route::post('/formulario_atencion_escala_resultado', ['uses' => 'RecetaValeController@formulario_atencion_escala_resultado', 'as' => 'formulario_atencion_escala_resultado']);
Route::get('/formulario_receta_dispension/{id_cita}', 'RecetaValeController@formulario_receta_dispension');
Route::get('/modal_cita_medico/{id_ipress}/{id_consultorio}/{fecha_atencion}', 'RecetaValeController@modal_cita_medico');
Route::get('/modal_cita_horario/{id_ipress}/{id_consultorio}/{fecha_atencion}/{documento}/{id_servicio_estudio?}', 'RecetaValeController@modal_cita_horario_lista');
Route::get('/modal_cita_horario_pp/{id_ipress}/{id_consultorio}/{fecha_atencion}/{documento}/{id_procedimiento}/{id_servicio_estudio?}', 'RecetaValeController@modal_cita_horario_lista_pp');
Route::get('/modal_cita_horario/reprogramar-laboratorio/nivel_1_2/{id_ipress}/{id_consultorio}/{fecha_atencion}/{documento}/{id_laboratorio}', 'RecetaValeController@modal_cita_horario_lista_reprogramacion_lab_nivel_1_2');
Route::get('/modal_cita_horario_car/{id_consultorio}/{documento}', 'RecetaValeController@modal_cita_horario_listaCAR');
Route::get('/modal_turnos_atq/{id_ipress}/{id_consultorio}/{fecha_atencion}', 'RecetaValeController@modal_turnos_atq');
Route::get('/modal_turnos/{id_ipress}/{id_consultorio}/{fecha_atencion}', 'RecetaValeController@modal_turnos');
Route::get('/modal_turnos/{id_ipress}/{id_consultorio}/{fecha_atencion}', 'RecetaValeController@modal_turnos');
Route::get('/modal_historial_cita/{dni_beneficiario}', 'RecetaValeController@modal_historial_cita');
Route::get('/modal_historia/{id}', 'RecetaValeController@modal_historia');

Route::post('update_historia', [
  'uses' => 'CitaController@update_historia',
  'as' => 'update_historia'
]);

Route::get('/modal_comentario/{id}', 'RecetaValeController@modal_comentario');
Route::post('update_comentario', ['uses' => 'RecetaValeController@update_comentario', 'as' => 'update_comentario']);

Route::get('/modal_telefono/{id}', 'RecetaValeController@modal_telefono');
Route::post('update_telefono', [
  'uses' => 'CitaController@update_telefono',
  'as' => 'update_telefono'
]);


Route::get('/modal_asignar_consultorio', 'CitaController@modal_asignar_consultorio');
Route::post('asignar_cita', [
  'uses' => 'CitaController@asignar_cita',
  'as' => 'asignar_cita'
]);

Route::get('/cronograma_cita/{id_ipress}/{id_consultorio}', 'RecetaValeController@cronograma_cita');


Route::post('formulario_receta_cirugia', [
  'uses' => 'RecetaValeController@formulario_receta_cirugia',
  'as' => 'formulario_receta_cirugia'
]);

Route::post('formulario_receta_cirugia_alterno', [
  'uses' => 'RecetaValeController@formulario_receta_cirugia_alterno',
  'as' => 'formulario_receta_cirugia_alterno'
]);

Route::post('formulario_receta_temporal', [
  'uses' => 'RecetaValeController@formulario_receta_temporal',
  'as' => 'formulario_receta_temporal'
]);


Route::get('/editar_medico/{id}/{id_farmacia}', 'MedicoController@editar_medico');
Route::get('/ver_receta/{id_user}/{nro_receta}/{id_receta}', 'RecetaValeController@ver_receta');
Route::get('/ver_receta_invalidada/{id_user}/{nro_receta}/{id_receta}', 'RecetaValeController@ver_receta_invalidada');
Route::get('/ver_receta_atendida/{tipo_repor}/{id_user}/{nro_receta}/{id_receta?}', 'RecetaValeController@ver_receta_atendida');
Route::get('/ver_det_servicio/{tipo_repor}/{id_user}/{id_consultorio}/{fecha_inicio}/{fecha_fin}', 'StocksController@ver_det_servicio');
Route::get('/ver_hospitalizado/{tipo_repor}/{id_user}/{nro_receta}/{id_hospitalizacion?}/{estado}', 'RecetaValeController@ver_hospitalizado');

Route::get('/ver_admision/{id_user}/{nro_admision}', 'RecetaValeController@ver_admision');
Route::get('/ver_centro_quirurgico/{id_user}/{nro_quirurgico}', 'RecetaValeController@ver_centro_quirurgico');
Route::get('/ver_hospitalizacion/{id_user}/{nro_admision}', 'RecetaValeController@ver_hospitalizacion');
Route::get('/ver_paciente_alta/{id_user}/{nro_admision}', 'RecetaValeController@ver_alta_paciente');

//Route::get('/ver_cita/{id_user}/{nro_cita}', 'RecetaValeController@ver_cita');
Route::get('/ver_cita/{id_user}/{id_cita}', 'RecetaValeController@ver_cita');
Route::get('/ver_cita_laboratorio/{id_cita}', 'RecetaValeController@ver_cita_laboratorio');
Route::get('/anular_cita/{id_cita}', 'CitaController@anular_cita');
Route::get('/anular_cita_radiologia/{id_cita}', 'CitaController@anular_cita_radiologia');
Route::get('/anular_prestacion/{id_prestacion}', 'CitaController@anular_prestacion');


Route::get('/ver_prestacion/{id_user}/{id_prestacion}', 'RecetaValeController@ver_prestacion');
Route::get('/ver_informe_procedimiento_pdf/{id_prestacion}', 'RecetaValeController@ver_informe_solicitud_procedimiento');
Route::get('/modal_historial_informes_pp/{id_asegurado}', 'RecetaValeController@modal_historial_informes_pp');
Route::get('/modal_historial_informes_pp_v2/{nrodocafiliado}', 'RecetaValeController@modal_historial_informes_pp_v2');
Route::post('/consultarSolicitudProcedimientoPendiente', 'ProcedimientoProgramadoController@consultarSolicitudProcedimientoPendiente');
Route::get('/all_procprogramados', 'ProcedimientoProgramadoController@all_procprogramados');
Route::get('/atender_procprogramado/{id_solicitud}/{id_procedimiento}', 'ProcedimientoProgramadoController@atender_procprogramado');
Route::get('/ver_atencion_procprogramado/{id_solicitud}/{id_procedimiento}', 'ProcedimientoProgramadoController@ver_atencion_procprogramado');
Route::get('/modal_ver_atencion_procprogramado/{id_solicitud}/{id_procedimiento}', 'ProcedimientoProgramadoController@modal_ver_atencion_procprogramado');
Route::get('/historial_atenciones_procprogramado/{nro_doc}', 'ProcedimientoProgramadoController@historial_atenciones_procprogramado');
Route::post('/guardar_atencion_procprogramado', 'ProcedimientoProgramadoController@guardar_atencion_procprogramado');
Route::post('/finalizar_atencion_procprogramado', 'ProcedimientoProgramadoController@finalizar_atencion_procprogramado');
Route::post('/pp_editar_observacion_atencion', 'ProcedimientoProgramadoController@pp_editar_observacion_atencion');
Route::post('/anular_procprogramado', 'ProcedimientoProgramadoController@anular_procprogramado');
Route::post('/observar_procprogramado', 'ProcedimientoProgramadoController@observar_procprogramado');
Route::get('/pp_informe_gastro_pdf/{id_gastro}/{parte}', 'ProcedimientoProgramadoController@pp_informe_gastro_pdf');
Route::post('/pp_preparar_firma_token', 'ProcedimientoProgramadoController@pp_preparar_firma_token');
Route::post('/pp_gastro_post_arguments', 'ProcedimientoProgramadoController@pp_gastro_post_arguments')->name('pp_gastro_post_arguments');
Route::post('/pp_confirmar_firma_token', 'ProcedimientoProgramadoController@pp_confirmar_firma_token');
Route::post('/pp_agregar_ordenes', 'ProcedimientoProgramadoController@pp_agregar_ordenes');
Route::post('/pp_invalidar_receta', 'ProcedimientoProgramadoController@pp_invalidar_receta');
Route::post('/pp_invalidar_laboratorio', 'ProcedimientoProgramadoController@pp_invalidar_laboratorio');
Route::post('/pp_invalidar_imagenologia', 'ProcedimientoProgramadoController@pp_invalidar_imagenologia');
Route::post('/pp_subir_informe', 'ProcedimientoProgramadoController@pp_subir_informe');
Route::post('/pp_eliminar_informe', 'ProcedimientoProgramadoController@pp_eliminar_informe');
Route::get('/pp_ver_informe/{id_solicitud}/{id_procedimiento}/{id_informe?}', 'ProcedimientoProgramadoController@pp_ver_informe');
Route::get('/pp_ordenes_atencion/{id_solicitud}/{id_procedimiento}', 'ProcedimientoProgramadoController@pp_ordenes_atencion');
Route::post('listar_procprogramados_ajax', [
  'uses' => 'ProcedimientoProgramadoController@listar_procprogramados_ajax',
  'as' => 'listar_procprogramados_ajax'
]);
Route::get('/ver_mopri/{id_prestacion}/{id_intervencion}/{id_cita}/{id_hospitalizacion}', 'RecetaValeController@ver_mopri');
Route::get('/ver_receta2/{id_prestacion}', 'RecetaValeController@asignarReceta');
Route::post('listar_citas_medico_paciente', [
  'uses' => 'CitaController@listar_citas_medico_paciente',
  'as' => 'listar_citas_medico_paciente'
]);

Route::get('/all_citas_medico_paciente/{iduser?}', 'CitaController@all_citas_medico_paciente');
Route::get('/listar_citas_medico_paciente/{iduser?}', 'CitaController@all_citas_medico_paciente');

Route::get('/all_citas_medico_atenciones/{iduser?}', 'CitaController@all_citas_medico_atenciones');
Route::get('/listar_citas_medico_atenciones/{iduser?}', 'CitaController@all_citas_medico_atenciones');

Route::post('listar_cita_ajax', [
  'uses' => 'CitaController@listar_cita_ajax',
  'as' => 'listar_cita_ajax'
]);



Route::get('listar_feriados/{anio}/{mes?}', 'CitaController@listar_feriados');
Route::get('validar_fecha_actual_es_feriado', 'FeriadoController@validar_fecha_actual_es_feriado');
Route::get('get_datos_dia_actual', 'FeriadoController@get_datos_dia_actual');
Route::post('valida_programacion_cita', 'CitaController@valida_programacion_cita');

Route::post('listar_prestacion_ajax', [
  'uses' => 'CitaController@listar_prestacion_ajax',
  'as' => 'listar_prestacion_ajax'
]);

Route::post('listar_citas_medico_atenciones', [
  'uses' => 'CitaController@listar_citas_medico_atenciones',
  'as' => 'listar_citas_medico_atenciones'
]);


Route::get('/ver_receta_atendida_cirugia/{id_user}/{nro_receta}', 'RecetaValeController@ver_receta_atendida_cirugia');

Route::get('/imprimir_lista_recetas_atendidas/{id_user}/{fecha}/{fechaHasta}', 'ExcelController@imprimir_lista_recetas_atendidas');
Route::get('/imprimir_lista_recetas_invalidadas/{id_user}/{fecha}', 'ExcelController@imprimir_lista_recetas_invalidadas');
Route::post('busqueda_nro_receta', [
  'uses' => 'RecetaValeController@busqueda_nro_receta',
  'as' => 'busqueda_nro_receta'
]);


Route::post('update_medico', [
  'uses' => 'MedicoController@update_medico',
  'as' => 'update_medico'
]);

Route::post('recetas_atendidas_by_fecha_and_farmacia', [
  'uses' => 'RecetaValeController@recetas_atendidas_by_fecha_and_farmacia',
  'as' => 'recetas_atendidas_by_fecha_and_farmacia'
]);


Route::post('busqueda_dni', [
  'uses' => 'RecetaValeController@busqueda_dni',
  'as' => 'busqueda_dni'
]);
Route::post('registrar_receta', [
  'uses' => 'RecetaValeController@registrar_receta',
  'as' => 'registrar_receta'
]);

/*Route::post('registrar_receta_rua', [
    'uses' => 'RecetaValeController@registrar_receta_rua',
    'as' => 'registrar_receta_rua'
]);*/

Route::post('registrar_receta_rua', [
  'uses' => 'RecetaValeController@registrar_receta_rua_transaction',
  'as' => 'registrar_receta_rua'
]);

Route::post('registrar_receta_quirurgico', [
  'uses' => 'RecetaValeController@registrar_receta_quirurgico_transaction',
  'as' => 'registrar_receta_quirurgico'
]);

Route::post('registrar_receta_hospitalizacion', [
  'uses' => 'RecetaValeController@registrar_receta_hospitalizacion_transaction',
  'as' => 'registrar_receta_hospitalizacion'
]);



Route::post('registrar_receta_nutricional', [
  'uses' => 'RecetaValeController@registrar_receta_nutricional_transaction',
  'as' => 'registrar_receta_nutricional'
]);


Route::post('registrar_admision', [
  'uses' => 'RecetaValeController@registrar_admision_transaction',
  'as' => 'registrar_admision'
]);

Route::post('registrar_centro_quirurgico', [
  'uses' => 'RecetaValeController@registrar_centro_quirurgico_transaction',
  'as' => 'registrar_centro_quirurgico'
]);

Route::post('registrar_cita', [
  'uses' => 'RecetaValeController@registrar_cita_transaction',
  'as' => 'registrar_cita'
]);

Route::post('registrar_cita_v2', [
  'uses' => 'RecetaValeController@registrar_cita_transaction_v2',
  'as' => 'registrar_cita_v2'
]);

Route::post('registrar_cita_riesgoqx', [
  'uses' => 'RecetaValeController@registrar_cita_riesgoqx_transaction',
  'as' => 'registrar_cita_riesgoqx'
]);

Route::post('registrar_cita_triaje', [
  'uses' => 'RecetaValeController@registrar_cita_transactionTriaje',
  'as' => 'registrar_cita_triaje'
]);
Route::post('registrar_prestacion', [
  'uses' => 'RecetaValeController@registrar_prestacion_transaction',
  'as' => 'registrar_prestacion'
]);

Route::post('actualizar_prestacion', [
  'uses' => 'RecetaValeController@actualizar_prestacion_transaction',
  'as' => 'actualizar_prestacion'
]);

Route::post('registrar_receta_prestacion', [
  'uses' => 'RecetaValeController@registrar_receta_prestacion',
  'as' => 'registrar_receta_prestacion'
]);

Route::post('registrar_hospitalizacion', [
  'uses' => 'RecetaValeController@registrar_hospitalizacion_transaction',
  'as' => 'registrar_hospitalizacion'
]);

Route::post('actualizar_hospitalizacion', [
  'uses' => 'RecetaValeController@actualizar_hospitalizacion_transaction',
  'as' => 'actualizar_hospitalizacion'
]);

Route::post('actualizar_alta_hospitalizacion', [
  'uses' => 'RecetaValeController@actualizar_alta_hospitalizacion_transaction',
  'as' => 'actualizar_alta_hospitalizacion'
]);

Route::post('actualiza_interconsulta', [
  'uses' => 'ExcelController@registra_firma_interconsulta_rpta',
  'as' => 'actualiza_interconsulta'
]);


Route::post('validacion_beneficiario_dias_productos', [
  'uses' => 'RecetaValeController@validacion_beneficiario_dias_productos',
  'as' => 'validacion_beneficiario_dias_productos'
]);
Route::get('/validacion_beneficiario_dias_by_producto/{dni_beneficiario}/{dias}/{idProducto}', 'RecetaValeController@validacion_beneficiario_dias_by_producto');

Route::get('/producto_por_farmacia/{idProducto}', 'ProductoController@producto_por_farmacia');
Route::get('/validacion_beneficiario_dias_by_producto_medico/{dni_beneficiario}/{dias}/{idProducto}', 'RecetaValeController@validacion_beneficiario_dias_by_producto_medico');

Route::get('/validacion_beneficiario_dias_by_producto_hospitalizacion/{dni_beneficiario}/{dias}/{idProducto}/{id_farmacia}', 'RecetaValeController@validacion_beneficiario_dias_by_producto_hospitalizacion');

Route::post('registrar_receta_rua_cirugia', [
  'uses' => 'RecetaValeController@registrar_receta_rua_transaction_cirugia',
  'as' => 'registrar_receta_rua_cirugia'
]);

Route::post('dispensar_producto_cirugia', [
  'uses' => 'RecetaValeController@dispensar_producto_cirugia',
  'as' => 'dispensar_producto_cirugia'
]);

Route::post('registrar_receta_rua_cirugia_alterno', [
  'uses' => 'RecetaValeController@registrar_receta_rua_transaction_cirugia_alterno',
  'as' => 'registrar_receta_rua_cirugia_alterno'
]);



Route::get('/buscar_diagnostico/{cie}', 'RecetaValeController@buscar_diagnostico');
Route::get('/buscar_procedimiento/{codigo}', 'RecetaValeController@buscar_procedimiento');


Route::post('registrar_receta_rua_ubapol', [
  'uses' => 'RecetaValeController@registrar_receta_rua_ubapol',
  'as' => 'registrar_receta_rua_ubapol'
]);

Route::post('registrar_transferencia', [
  'uses' => 'RecetaValeController@registrar_transferencia',
  'as' => 'registrar_transferencia'
]);
Route::post('dispensar_receta', [
  'uses' => 'RecetaValeController@dispensar_receta',
  'as' => 'dispensar_receta'
]);
Route::post('dispensar_producto', [
  'uses' => 'RecetaValeController@dispensar_producto',
  'as' => 'dispensar_producto'
]);
Route::post('update_profesional_receta_vale', [
  'uses' => 'RecetaValeController@update_profesional_receta_vale',
  'as' => 'update_profesional_receta_vale'
]);
Route::post('update_medico_principal_receta_vale', [
  'uses' => 'RecetaValeController@update_medico_principal_receta_vale',
  'as' => 'update_medico_principal_receta_vale'
]);
//-----------------------RUTAS MEDICOS -----------------------------------------
Route::get('/cambiar_medico_principal/{id_receta_vale}', 'MedicoController@cambiar_medico_principal');
Route::get('/cambiar_profesional_salud/{id_receta_medico}', 'MedicoController@cambiar_profesional_salud');
Route::get('/gestion_medicos/{id_user?}', 'MedicoController@index')->middleware('menu.permission:12');
Route::get('/create_medico/{id_user}', 'MedicoController@create');
Route::get('/select_tipo_personal', 'MedicoController@select_tipo_personal');
Route::get('/create_personal_institucional/{id_user}', 'MedicoController@create_personal_institucional');

Route::get('/all_servicios', 'ConsultorioController@index');
Route::get('/api_departamentos_servicio/{id?}', [
  'uses' => 'ConsultorioController@api_departamentos',
  'as' => 'api_departamentos_servicio'
]);
Route::get('/create_servicio', 'ConsultorioController@create');
Route::post('store_consultorio', [
  'uses' => 'ConsultorioController@store',
  'as' => 'store_consultorio'
]);
Route::get('/edit_servicio/{id}/{ff?}', 'ConsultorioController@edit');
Route::post('update_servicio', [
  'uses' => 'ConsultorioController@update',
  'as' => 'update_servicio'
]);

Route::get('/all_especialidad', 'EspecialidadController@index');
Route::get('/create_especialidad', 'EspecialidadController@create');
Route::post('store_especialidad', [
  'uses' => 'EspecialidadController@store',
  'as' => 'store_especialidad'
]);
Route::get('/edit_especialidad/{id}', 'EspecialidadController@edit');
Route::post('update_especialidad', [
  'uses' => 'EspecialidadController@update',
  'as' => 'update_especialidad'
]);



Route::get('/all_origen_paciente', 'OrigenPacienteController@index');
Route::get('/create_origen_paciente', 'OrigenPacienteController@create');
Route::post('store_origen_paciente', [
  'uses' => 'OrigenPacienteController@store',
  'as' => 'store_origen_paciente'
]);
Route::get('/edit_origen_paciente/{id}', 'OrigenPacienteController@edit');
Route::post('update_origen_paciente', [
  'uses' => 'OrigenPacienteController@update',
  'as' => 'update_origen_paciente'
]);


Route::get('/all_profesiones', 'ProfesionController@index');
Route::get('/create_profesion', 'ProfesionController@create');
Route::post('store_profesion', [
  'uses' => 'ProfesionController@store',
  'as' => 'store_profesion'
]);
Route::get('/edit_profesion/{id}', 'ProfesionController@edit');
Route::post('update_profesion', [
  'uses' => 'ProfesionController@update',
  'as' => 'update_profesion'
]);


Route::get('/all_tipo_contrato', 'TipoContratoController@index');
Route::get('/create_tipo_contrato', 'TipoContratoController@create');
Route::post('store_tipo_contrato', [
  'uses' => 'TipoContratoController@store',
  'as' => 'store_tipo_contrato'
]);
Route::get('/edit_tipo_contrato/{id}', 'TipoContratoController@edit');
Route::post('update_tipo_contrato', [
  'uses' => 'TipoContratoController@update',
  'as' => 'update_tipo_contrato'
]);


Route::get('/all_sala_quirurgica', 'SalaQuirurgicaController@index');
Route::get('/create_sala_quirurgica', 'SalaQuirurgicaController@create');
Route::post('store_sala_quirurgica', [
  'uses' => 'SalaQuirurgicaController@store',
  'as' => 'store_sala_quirurgica'
]);
Route::get('/edit_sala_quirurgica/{id}', 'SalaQuirurgicaController@edit');
Route::post('update_sala_quirurgica', [
  'uses' => 'SalaQuirurgicaController@update',
  'as' => 'update_sala_quirurgica'
]);



Route::get('/all_menu', 'MenuController@index')->middleware('menu.permission:47');
Route::post('all_menu', [
  'uses' => 'MenuController@index',
  'as' => 'all_menu'
]);

Route::get('/edit_menu/{id}', 'MenuController@edit');
Route::post('update_menu', [
  'uses' => 'MenuController@update',
  'as' => 'update_menu'
]);

Route::get('/create_menu', 'MenuController@create');
Route::post('store_menu', [
  'uses' => 'MenuController@store',
  'as' => 'store_menu'
]);

Route::get('/all_pabellon/{id_user?}', 'PabellonController@index');
Route::get('/create_pabellon', 'PabellonController@create');
Route::post('store_pabellon', [
  'uses' => 'PabellonController@store',
  'as' => 'store_pabellon'
]);
Route::get('/edit_pabellon/{id}', 'PabellonController@edit');
Route::post('update_pabellon', [
  'uses' => 'PabellonController@update',
  'as' => 'update_pabellon'
]);

Route::get('/all_piso/{id_user?}', 'PisoController@index');
Route::get('/create_piso', 'PisoController@create');
Route::post('store_piso', [
  'uses' => 'PisoController@store',
  'as' => 'store_piso'
]);
Route::get('/edit_piso/{id}', 'PisoController@edit');
Route::post('update_piso', [
  'uses' => 'PisoController@update',
  'as' => 'update_piso'
]);



Route::get('/all_topico/{id_user?}', 'TopicoController@index');
Route::get('/create_topico', 'TopicoController@create');
Route::post('store_topico', [
  'uses' => 'TopicoController@store',
  'as' => 'store_topico'
]);
Route::get('/edit_topico/{id}', 'TopicoController@edit');
Route::post('update_topico', [
  'uses' => 'TopicoController@update',
  'as' => 'update_topico'
]);


Route::get('/all_anaquel/{id_user?}', 'AnaquelController@index');
Route::get('/create_anaquel', 'AnaquelController@create');
Route::post('store_anaquel', [
  'uses' => 'AnaquelController@store',
  'as' => 'store_anaquel'
]);
Route::get('/edit_anaquel/{id}', 'AnaquelController@edit');
Route::post('update_anaquel', [
  'uses' => 'AnaquelController@update',
  'as' => 'update_anaquel'
]);


Route::get('/all_cama_emergencia/{id_user?}', 'CamaEmergenciaController@index');
Route::get('/create_cama_emergencia', 'CamaEmergenciaController@create');
Route::post('store_cama_emergencia', [
  'uses' => 'CamaEmergenciaController@store',
  'as' => 'store_cama_emergencia'
]);
Route::get('/edit_cama_emergencia/{id}', 'CamaEmergenciaController@edit');
Route::post('update_cama_emergencia', [
  'uses' => 'CamaEmergenciaController@update',
  'as' => 'update_cama_emergencia'
]);





Route::get('/all_asignacion_servicio/{id_user?}', 'AsignacionServicioController@index');
Route::get('/create_asignacion_servicio', 'AsignacionServicioController@create');
Route::post('store_asignacion_servicio', [
  'uses' => 'AsignacionServicioController@store',
  'as' => 'store_asignacion_servicio'
]);
Route::get('/edit_asignacion_servicio/{id}', 'AsignacionServicioController@edit');
Route::post('update_asignacion_servicio', [
  'uses' => 'AsignacionServicioController@update',
  'as' => 'update_asignacion_servicio'
]);
Route::get('/obtener_piso_por_idpabellon/{id_pabellon}', 'AsignacionServicioController@obtener_piso_por_idpabellon');
Route::get('/obtener_servicios_atencion/{id_atencion}', 'AsignacionServicioController@obtener_servicios_atencion');
Route::get('/obtener_prioridad/', 'AsignacionServicioController@obtener_prioridades');
Route::get('/obtener_prioridad_piso/{piso}/{obtener_prioridad_piso}', 'PisoController@obtener_prioridad_piso');
Route::get('/obtener_prioridad_piso_prioridad/{piso}/{obtener_prioridad_piso}/{prioridad}', 'PisoController@obtener_prioridad_piso_prioridad');
Route::get('/obtener_pabellon/{atencion}', 'PisoController@obtener_pabellon');

Route::get('/all_cuarto/{id_user?}', 'CuartoController@index');
Route::get('/create_cuarto', 'CuartoController@create');
Route::post('store_cuarto', [
  'uses' => 'CuartoController@store',
  'as' => 'store_cuarto'
]);
Route::get('/edit_cuarto/{id}', 'CuartoController@edit');
Route::post('update_cuarto', [
  'uses' => 'CuartoController@update',
  'as' => 'update_cuarto'
]);
Route::get('/obtener_consultorio_por_idpiso/{id_piso}', 'CuartoController@obtener_consultorio_por_idpiso');
Route::get('/all_cama/{id_user?}', 'CamaController@index');
Route::get('/all_historial', 'CamasHistorialController@index');
Route::get('/create_cama', 'CamaController@create');
Route::get('/reporte_d1_cama', 'CamaController@reporte_d1_cama');
Route::get('/reporte_d1_data', 'CamaController@reporte_d1_data');
Route::post('/reporte_d1_guardado', 'CamaController@reporte_d1_guardado');
Route::post('/anular_reporte_d1/{id}', 'CamaController@anular_reporte_d1');
Route::get('/exportar_d1_txt', 'CamaController@exportar_d1_txt');
Route::get('/exportar_d1_excel', 'CamaController@exportar_d1_excel');
Route::post('guardar_d1_camas', [
  'uses' => 'CamaController@guardar_d1_camas',
  'as' => 'guardar_d1_camas'
]);
Route::get('/obtener_cama_disponible_sala/{id_ipress}', 'CamaController@obtener_cama_disponible_sala');
Route::post('store_cama', [
  'uses' => 'CamaController@store',
  'as' => 'store_cama'
]);
Route::get('/edit_cama/{id}', 'CamaController@edit');
Route::post('update_cama', [
  'uses' => 'CamaController@update',
  'as' => 'update_cama'
]);

Route::get('/obtener_cuarto_por_idasignacionservicio/{id_asignacion_servicio}', 'CamaController@obtener_cuarto_por_idasignacionservicio');
Route::get('/obtener_cama_upsses/{id_asignacion_servicio}', 'CamaController@obtener_cama_upsses');

Route::get('obtener_cama_por_idcuarto/{id_cuarto}', 'CamaController@obtener_cama_por_idcuarto');
Route::get('obtener_cama_por_idcuarto_todos/{id_cuarto}', 'CamaController@obtener_cama_por_idcuarto_todos');

Route::post('store_medico', [
  'uses' => 'MedicoController@store',
  'as' => 'store_medico'
]);

Route::post('subir_data_medicos', [
  'uses' => 'ImportacionesController@subir_data_medicos',
  'as' => 'subir_data_medicos'
]);


Route::post('add_carga_inicial_personalizado', [
  'uses' => 'StocksController@add_carga_inicial_personalizado',
  'as' => 'add_carga_inicial_personalizado'
]);

Route::post('add_registrar_productos_iniciales', [
  'uses' => 'StocksController@add_registrar_productos_iniciales',
  'as' => 'add_registrar_productos_iniciales'
]);

$router->get('import', 'ExcelController@import');

Route::post('insert_cabecera_carga_inicial', [
  'uses' => 'StocksController@insert_cabecera_carga_inicial',
  'as' => 'insert_cabecera_carga_inicial'
]);


Route::post('agregar_producto_tabla_cargas', [
  'uses' => 'StocksController@agregar_producto_tabla_cargas',
  'as' => 'agregar_producto_tabla_cargas'
]);

Route::post('update_producto_tabla_cargas', [
  'uses' => 'StocksController@update_producto_tabla_cargas',
  'as' => 'update_producto_tabla_cargas'
]);

Route::post('insert_productos_tabla_stock_farmacias', [
  'uses' => 'StocksController@insert_productos_tabla_stock_farmacias',
  'as' => 'insert_productos_tabla_stock_farmacias'
]);


Route::get('/editar_producto_cargado_tabla_cargas/{id_carga}', 'StocksController@editar_producto_cargado_tabla_cargas');

Route::get('/eliminar_producto_cargado_tabla_cargas/{id_carga}/{id_carga_inicial}/{id_farmacia}', 'StocksController@eliminar_producto_cargado_tabla_cargas');

Route::get('/get_insert_cabecera_carga_inicial/{id_farmacia}/{id_cabecera_carga}', 'StocksController@get_insert_cabecera_carga_inicial');

Route::get('/from_registrar_cabecera_carga_inicial/{id_farmacia}', 'StocksController@from_registrar_cabecera_carga_inicial');
//Route::get('/from_registrar_cabecera_carga_inicial/{id_farmacia}/{id_carga_inicial}', 'StocksController@from_registrar_cabecera_carga_inicial');

Route::get('/importaciones_medicos/{iduser}', 'ImportacionesController@indexmedicos');

Route::get('/add_importacion_medicos/{id}/{id_usuario}', 'ExcelController@add_importacion_medicos');
Route::get('/validacion_add_importacion_medicos/{id}/{id_usuario}', 'ExcelController@validacion_add_importacion_medicos');

Route::get('/add_importacion_stocks/{id}/{id_usuario}', 'ExcelController@add_importacion_stocks');

Route::get('/form_carga_inicial/{id_usuario}', 'StocksController@form_carga_inicial');

Route::get('/form_carga_inicial_personalizado/{id_farmacia}', 'StocksController@form_carga_inicial_personalizado');

Route::get('/verpersonal/{dni}', 'MedicoController@verpersonal');
Route::get('/existe_personal/{dni}', 'MedicoController@existe_personal');


Route::get('/editar_lotes/{id}', 'StocksController@editar_lotes');

Route::get('/usuarios_all_ipress_excel', 'ExcelController@usuarios_all_ipress')->middleware('check.server.resources');

Route::get('/formatoxls_personal_medico/{iduser}', 'ExcelController@formatoxls_personal_medico')->middleware('check.server.resources');
Route::get('/usuarios_por_ipress_excel/{id_farmacia}/{nombre_farmacia}', 'ExcelController@usuarios_por_ipress_excel')->middleware('check.server.resources');

//--------------------DIREOSS---------------------------------------------------
Route::get('/allFarmacias_withStocks', 'StocksController@allFarmacias_withStocks');
Route::get('/all_usuarios', 'FarmaciaController@all_usuarios');


//-------------- FECHAS Y LOTES DE VENCIMIENTO ---------------------------------
Route::get('/asignar_lote_vencimiento/{id_stock_farmacia}', 'StocksController@asignar_lote_vencimiento');
Route::get('/asignar_lote_vencimientos/{codigo_producto}/{id_farmacia}', 'StocksController@asignar_lote_vencimientos');


Route::post('editar_medico_receta', [
  'uses' => 'RecetaValeController@editar_medico_receta',
  'as' => 'editar_medico_receta'
]);

Route::post('editar_consultorio_receta', [
  'uses' => 'RecetaValeController@editar_consultorio_receta',
  'as' => 'editar_consultorio_receta'
]);

Route::post('editar_fecha_receta', [
  'uses' => 'RecetaValeController@editar_fecha_receta',
  'as' => 'editar_fecha_receta'
]);

Route::post('editar_fecha_salida_centro_quirurgico', [
  'uses' => 'RecetaValeController@editar_fecha_salida_centro_quirurgico',
  'as' => 'editar_fecha_salida_centro_quirurgico'
]);

Route::post('usuarios_por_ipress', [
  'uses' => 'FarmaciaController@usuarios_por_ipress',
  'as' => 'usuarios_por_ipress'
]);
Route::post('update_lote', [
  'uses' => 'StocksController@update_lote',
  'as' => 'update_lote'
]);

Route::post('reasignar_lotes', [
  'uses' => 'StocksController@reasignar_lotes',
  'as' => 'reasignar_lotes'
]);

Route::post('add_pecosa', [
  'uses' => 'StocksController@add_pecosa',
  'as' => 'add_pecosa'
]);

Route::post('insert_producto_pecosa', [
  'uses' => 'StocksController@insert_producto_pecosa',
  'as' => 'insert_producto_pecosa'
]);


Route::get('/eliminar_producto_carga_pecosa/{id_carga_pecosa}/{id_guia_entrada}/{id_user}', 'StocksController@eliminar_producto_carga_pecosa');
Route::get('/editar_producto_carga_pecosa/{id_carga_pecosa}/{id_farmacia}', 'StocksController@editar_producto_carga_pecosa');
Route::post('actualizar_producto_carga_pecosa', [
  'uses' => 'StocksController@actualizar_producto_carga_pecosa',
  'as' => 'actualizar_producto_carga_pecosa'
]);
Route::get('/editar_producto_pecosa_actualizar/{id_carga_pecosa}/{id_farmacia}', 'StocksController@editar_producto_pecosa_actualizar');
Route::post('actualizar_producto_pecosa_directo', [
  'uses' => 'StocksController@actualizar_producto_pecosa_directo',
  'as' => 'actualizar_producto_pecosa_directo'
]);
Route::get('/eliminar_producto_pecosa_actualizar/{id_carga_pecosa}/{id_guia_entrada}/{id_user}/{id_producto}', 'StocksController@eliminar_producto_pecosa_actualizar');

Route::post('add_producto_pecosa', [
  'uses' => 'StocksController@add_producto_pecosa_mejorado',
  'as' => 'add_producto_pecosa'
]);

//Route::get('/add_producto_pecosa_mejorado_2/{id_guia_entrada}/{id_farmacia}/{id_producto}/{cantidad}/{registro_sanitario}/{lote}/{fecha_vencimiento}', 'StocksController@add_producto_pecosa_mejorado_2');




Route::post('update_datos_user', [
  'uses' => 'FarmaciaController@update_datos_user',
  'as' => 'update_datos_user'
]);

//Route::get('/editar_mis_datos/{id_user}', 'FarmaciaController@editar_mis_datos');

Route::get('/recepcionar_productos_pecosa/{id_pecosa}/{id_user}', 'StocksController@recepcionar_productos_pecosa');

Route::get('/eliminar_pecosa/{id_pecosa}/{id_farmacia}', 'StocksController@eliminar_pecosa');

Route::get('/registrar_pecosa/{id_farmacia?}', 'StocksController@registrar_pecosa');

Route::get('/devolver_receta/{cod_producto}/{id_farmacia}/{iduser}', 'RecetaValeController@devolver_receta');

Route::post('invalidar_receta', [
  'uses' => 'RecetaValeController@invalidar_receta',
  'as' => 'invalidar_receta'
]);

Route::post('invalidar_receta_digital', [
  'uses' => 'RecetaValeController@invalidar_receta_digital',
  'as' => 'invalidar_receta_digital'
]);

Route::post('registrar_alta_medica_anular', [
  'uses' => 'MedicoController@registrar_alta_medica_anular',
  'as' => 'registrar_alta_medica_anular'
]);
Route::post('cambiar_estado_hospitalizado', [
  'uses' => 'RecetaValeController@cambiar_estado_hospitalizado',
  'as' => 'cambiar_estado_hospitalizado'
]);

Route::get('/tabular_stockgeneral_ipress', 'StocksController@tabular_stockgeneral_ipress');
Route::get('/tabular_stockgeneral_ipress_otros_biomedicos', 'StocksController@tabular_stockgeneral_ipress_otros_biomedicos');
Route::get('/tabular_stockgeneral_por_tipo/{tipo}', 'StocksController@tabular_stockgeneral_por_tipo');
Route::get('/tabular_stockgeneral_ipress_medicamentos', 'StocksController@tabular_stockgeneral_ipress_medicamentos');

Route::get('/stockGeneral', 'StocksController@stockGeneral');
Route::get('/obtener_lotes_por_codprod_idfarma/{cod_producto}/{id_farmacia}/{cantidad_prescrita}', 'StocksController@obtener_lotes_por_codprod_idfarma');
Route::get('/obtener_lotes_por_idprod_idfarma/{id_producto}/{id_farmacia}/{cantidad_prescrita}', 'StocksController@obtener_lotes_por_idprod_idfarma');
Route::get('/show_almacen_central', 'StocksController@show_almacen_central');

Route::get('/responsable_destino/{id_farmacia}', 'StocksController@responsable_destino');
Route::get('/profesionales_destino/{id_servicio}/{id_ipress}', 'StocksController@profesionales_destino');


Route::get('/obtener_consultorio_parent/{parent}', 'ConsultorioController@obtener_consultorio_parent');
Route::get('/obtener_consultorio_cita/{id_establecimiento}/{tipodoc_beneficiario}/{dni_beneficiario}', 'CitaController@obtener_consultorio_cita');
Route::get('/obtener_consultorio_cita_general/{id_establecimiento}/{tipodoc_beneficiario}/{dni_beneficiario}', 'CitaController@obtener_consultorio_cita_general');
Route::get('/obtener_establecimiento/{parentesco}/{ubigeo}', 'FarmaciaController@obtener_establecimiento'); 
Route::get('/obtener_establecimiento_cita/{parentesco}/{ubigeo}', 'FarmaciaController@obtener_establecimiento_cita'); 
Route::get('/obtener_turno_por_idipress_idconsultorio/{id_ipress}/{id_consultorio}/{fecha}', 'MedicoController@obtener_turno_por_idipress_idconsultorio');
Route::get('/obtener_medico_por_idipress_idconsultorio_turno/{id_ipress}/{id_consultorio}/{turno}/{fecha}', 'MedicoController@obtener_medico_por_idipress_idconsultorio_turno');
Route::get('/obtener_consultorios_ambulatorios/{id_farmacia}', 'RecetaValeController@obtener_consultorios');
Route::get('/obtener_consultorios_anestesiologia/{id_farmacia}', 'RecetaValeController@obtener_consultorios_anestesiologia');
Route::get('/obtener_medico_por_idipress_idconsultorio_turno_anestesiologo/{id_ipress}/{id_consultorio}', 'MedicoController@obtener_medico_por_idipress_idconsultorio_turno_anestesiologo');
Route::get('/obtener_medico_por_idipress_idconsultorio_turno_quirurgico/{id_ipress}/{id_consultorio}/', 'MedicoController@obtener_medico_por_idipress_idconsultorio_turno_quirurgico');
Route::get('/obtener_medico_por_idipress_idconsultorio/{id_ipress}/{id_consultorio}/', 'MedicoController@obtener_medico_por_idipress_idconsultorio');
Route::get('/obtener_medico_por_idipress_idconsultorio_turno_hospitalizacion/{id_ipress}/{id_consultorio}/{turno}/{fecha}', 'MedicoController@obtener_medico_por_idipress_idconsultorio_turno_hospitalizacion');
Route::get('/obtener_medico_por_idipress_idconsultorio_turno_hospitalizacion_motivo/{id_ipress}/{id_consultorio}/{turno}/{fecha}/{motivo}', 'MedicoController@obtener_medico_por_idipress_idconsultorio_turno_hospitalizacion_motivo');
Route::get('/obtener_medico_por_idipress_idconsultorio_turno_emergencia/{id_ipress}/{id_consultorio}/{turno}/{fecha}', 'MedicoController@obtener_medico_por_idipress_idconsultorio_turno_emergencia');
Route::get('/obtener_medico_por_idipress_idconsultorio_solo_emergencia/{id_ipress}/{id_consultorio}', 'MedicoController@obtener_medico_por_idipress_idconsultorio_solo_emergencia');
Route::get('/obtener_medico_por_idipress_idconsultorio_turno_origen/{id_ipress}/{id_consultorio}/{turno}/{fecha}', 'MedicoController@obtener_medico_por_idipress_idconsultorio_turno_origen');
Route::get('/obtener_producto_por_idipress_idconsultorio/{id_ipress}/{id_consultorio}', 'ProductoController@obtener_producto_por_idipress_idconsultorio');
Route::get('/obtener_producto_por_idipress_idconsultorio_idfarmacia/{id_ipress}/{id_farmacia}/{id_consultorio}', 'ProductoController@obtener_producto_por_idipress_idconsultorio_idfarmacia');
Route::get('/obtener_producto_por_idipress_idconsultorio_idfarmacia_nutricial/{id_ipress}/{id_farmacia}/{id_consultorio}', 'ProductoController@obtener_producto_por_idipress_idconsultorio_idfarmacia_nutricial');
Route::get('/obtener_producto_por_hospitalizacion_mas_idconsultorio/{id_ipress}/{id_consultorio}/{id_consultorio2}', 'ProductoController@obtener_producto_por_hospitalizacion_mas_idconsultorio');
Route::get('/obtener_producto_por_idipress_idconsultorio_mas_interconsulta/{id_ipress}/{id_consultorio}/{dni}', 'ProductoController@obtener_producto_por_idipress_idconsultorio_mas_interconsulta');
Route::get('/obtener_producto_por_idipress_idconsultorio_medico/{id_ipress}/{id_consultorio}/{id_consultorio2}/{id_medico}', 'ProductoController@obtener_producto_por_idipress_idconsultorio_medico');
Route::get('/obtener_producto_por_idipress_idconsultorio_quirurgico/{id_ipress}/{id_farmacia}/{id_consultorio}', 'ProductoController@obtener_producto_por_idipress_idconsultorio_quirurgico');
Route::get('/obtener_producto_farmacotecnia/{id_ipress}', 'ProductoController@obtener_producto_farmacotecnia');
Route::get('/obtener_servicio_por_idipress_fecha/{id_ipress}/{id_consultorio}', 'MedicoController@obtener_servicio_por_idipress_fecha');
Route::get('/obtener_farmacia_por_id_establecimiento/{id_establecimiento}', 'FarmaciaController@obtener_farmacia_por_id_establecimiento');
Route::get('/obtener_nroreque_por_id_origen/{id_origen}', 'StocksController@obtener_nroreque_por_id_origen');

Route::get('/obtener_consultorio_por_id_establecimiento/{id_establecimiento}/{id_consultorio}', 'ConsultorioController@obtener_consultorio_por_id_establecimiento');

Route::get('/obtener_servicio_por_id_establecimiento_and_id_medico/{id_establecimiento}/{id_medico}', 'MedicoController@obtener_servicio_por_id_establecimiento_and_id_medico');
Route::get('/obtener_medico_por_id_establecimiento_and_id_servicio/{id_establecimiento}/{id_servicio}', 'MedicoController@obtener_medico_por_id_establecimiento_and_id_servicio');
Route::get('/obtener_sub_consultorio_por_id_establecimiento_and_id_servicio/{id_establecimiento}/{id_servicio}/{id_tipo_atencion?}', 'MedicoController@obtener_sub_consultorio_por_id_establecimiento_and_id_servicio');

Route::get('/obtener_medico_por_id_establecimiento_and_id_sub_consultorio/{id_establecimiento}/{id_sub_consultorio}/{fecha}', 'MedicoController@obtener_medico_por_id_establecimiento_and_id_sub_consultorio');
Route::get('/obtener_upss/{id_servicio}/{id_tipo_atencion}', 'ConsultorioController@obtener_upss');
Route::get('/obtener_upss_hos/{id_servicio}/{id_tipo_atencion}', 'ConsultorioController@obtener_upss_hos');
Route::get('/obtener_upss2/{id_servicio}/{id_tipo_atencion}', 'ConsultorioController@obtener_upss2');
Route::get('/eliminar_consultorio/{id}', 'ConsultorioController@eliminar');
Route::get('/eliminar_pabellon/{id}', 'PabellonController@eliminar');
Route::get('/eliminar_piso/{id}', 'PisoController@eliminar');
Route::get('/eliminar_asignacion_servicio/{id}', 'AsignacionServicioController@eliminar');
Route::get('/eliminar_cuarto/{id}', 'CuartoController@eliminar');
//Route::get('/eliminar_cama/{id}', 'CamaController@eliminar');
Route::get('/eliminar_cama/{id}/{estado}', 'CamaController@eliminar');
Route::get('/eliminar_farmacia/{id}', 'FarmaciaController@eliminar');
Route::get('/desactivar_medico/{id}/{estado}', 'MedicoController@desactivar');
Route::get('/eliminar_topico/{id}', 'TopicoController@eliminar');
Route::get('/eliminar_cama_emergencia/{id}', 'CamaEmergenciaController@eliminar');
Route::get('/eliminar_anaquel/{id}', 'AnaquelController@eliminar');
Route::get('/eliminar_especialidad/{id}', 'EspecialidadController@eliminar');
Route::get('/eliminar_origen_paciente/{id}', 'OrigenPacienteController@eliminar');
Route::get('/eliminar_profesion/{id}', 'ProfesionController@eliminar');
Route::get('/eliminar_tipo_contrato/{id}', 'TipoContratoController@eliminar');
Route::get('/eliminar_sala_quirurgica/{id}', 'SalaQuirurgicaController@eliminar');
Route::get('/eliminar_transferencia/{id}', 'TransferenciaController@eliminar');
Route::get('/anular_transferencia/{id}', 'TransferenciaController@anular');



Route::get('/editar_usuario/{id_establecimiento}/{id_farmacia}', 'UsuarioController@editar_usuario');


Route::get('/obtener_admision_por_idfarmacia_dni/{id_farmacia}/{dni}', 'RecetaValeController@obtener_admision_por_idfarmacia_dni');

Route::get('/obtener_admision_por_id/{id}', 'RecetaValeController@obtener_admision_por_id');


Route::get('/buscar_ajax_medico_dni/{dni}', 'MedicoController@buscar_ajax_medico_dni');

Route::get('/obtener_permiso_por_idusuario/{iduser}', 'UsuarioController@obtener_permiso_por_idusuario');


Route::get('/obtener_cita_por_idfarmacia_dni/{id_farmacia}/{dni}', 'RecetaValeController@obtener_cita_por_idfarmacia_dni');


Route::get('/obtener_diagnostico/{buscar}', 'RecetaValeController@obtener_diagnostico');

Route::get('/obtener_producto/{buscar}', 'RecetaValeController@obtener_producto');

Route::get('/obtener_procedimiento/{buscar}', 'RecetaValeController@obtener_procedimiento');

///////////////////////////////////////////////////////////////////////////////////////////////
Route::get('/all_notas_entradas/{id_farmacia?}', 'StocksController@all_nota_entrada');
Route::get('/add_documento_nota_entrada/{id_user}', 'StocksController@add_documento_nota_entrada');
Route::get('/cargartipodocumento/{tipo}', 'StocksController@cargartipodocumento');
Route::get('/cargarnronota/{id_farmacia}', 'StocksController@cargarnronota');
Route::get('/cargartipoproceso/', 'StocksController@cargartipoproceso');
Route::get('/cargarcg/', 'StocksController@cargarcg');
Route::post('registrar_documento_nota_entrada', [
  'uses' => 'StocksController@registrar_documento_nota_entrada',
  'as' => 'registrar_documento_nota_entrada'
]);
Route::post('registrar_documento_nota_entrada_almacen', [
  'uses' => 'StocksController@registrar_documento_nota_entrada_almacen',
  'as' => 'registrar_documento_nota_entrada_almacen'
]);
Route::get('/recepcionar_productos_guia_entrada/{id_pecosa}/{id_user}', 'StocksController@recepcionar_productos_guia_entrada');

Route::post('add_producto_nota_entrada', [
  'uses' => 'StocksController@add_producto_nota_entrada',
  'as' => 'add_producto_nota_entrada'
]);

Route::post('insert_producto_nota_entrada', [
  'uses' => 'StocksController@insert_producto_nota_entrada',
  'as' => 'insert_producto_nota_entrada'
]);
Route::get('/print_nota_entrada/{id}', 'PdfController@print_nota_entrada');
//Route::get('/print_nota_entrada_almacen/{id}', 'PdfController@print_nota_entrada_almacen');
Route::get('/print_nota_entrada_almacen/{id}/{tipo}', 'PdfController@print_nota_entrada_almacen');

Route::get('/exportar_reporte_ici_establecimiento_total/{fecha_inicio}/{fecha_fin}/{tipo}', 'ExcelController@exportar_reporte_ici_establecimiento_total')->middleware('check.server.resources');
Route::get('/exportar_productos/{id_tipo}', 'ExcelController@exportar_productos')->middleware('check.server.resources');
Route::get('/exportar_productos_precio/{id_tipo}', 'ExcelController@exportar_productos_precio'); //->middleware('check.server.resources');
Route::get('/print_salidas_almacen/{fecha_inicio}/{fecha_fin}/{id_farmacia}/{reg_sanitario}/{lote}/{l_dependencia}/{l_establecimiento}/{id_tipo_producto}/{id_producto}/{estado}', 'TransferenciaController@print_salidas_almacen');
Route::get('/print_ingresos_almacen/{fecha_inicio}/{fecha_fin}/{id_farmacia}/{num_doc}/{num_siaf}/{reg_sanitario}/{lote}/{l_origen}/{id_proveedor}/{id_tipo_proceso}/{id_tipo_producto}/{idconcepto}/{id_producto}', 'StocksController@print_ingresos_almacen');

Route::get('/print_ingresos_farmacia/{fecha_inicio}/{fecha_fin}/{id_farmacia}/{num_doc}/{reg_sanitario}/{lote}/{id_tipo_producto}/{id_producto}', 'StocksController@print_ingresos_farmacia');
Route::get('/print_salidas_farmacia/{fecha_inicio}/{fecha_fin}/{id_farmacia}/{reg_sanitario}/{lote}/{id_tipo_producto}/{id_producto}', 'TransferenciaController@print_salidas_farmacia');

Route::get('/exportar_ordenes_ingresadas_producto/{fecha_inicio}/{fecha_fin}/{id_producto}/{id_user}', 'ExcelController@exportar_ordenes_ingresadas_producto')->middleware('check.server.resources');
Route::get('/exportar_ordenes_ingresadas_producto_farmacia/{fecha_inicio}/{fecha_fin}/{id_producto}/{id_user}', 'ExcelController@exportar_ordenes_ingresadas_producto_farmacia')->middleware('check.server.resources');
Route::get('/print_movimiento_producto/{fecha_inicio}/{fecha_fin}/{id_producto}/{id_user}', 'StocksController@print_movimiento_producto');

Route::get('/editar_producto_nota_entrada/{id_carga_pecosa}/{id_farmacia}', 'StocksController@editar_producto_nota_entrada');
Route::get('/editar_producto_nota_entrada_actualizar/{id_carga_pecosa}/{id_farmacia}', 'StocksController@editar_producto_nota_entrada_actualizar');
Route::post('actualizar_producto_nota_entrada', [
  'uses' => 'StocksController@actualizar_producto_nota_entrada',
  'as' => 'actualizar_producto_nota_entrada'
]);
Route::post('actualizar_producto_nota_entrada_directo', [
  'uses' => 'StocksController@actualizar_producto_nota_entrada_directo',
  'as' => 'actualizar_producto_nota_entrada_directo'
]);

Route::get('/eliminar_producto_nota_entrada/{id_carga_pecosa}/{id_guia_entrada}/{id_user}', 'StocksController@eliminar_producto_nota_entrada');
Route::get('/eliminar_producto_nota_entrada_actualizar/{id_carga_pecosa}/{id_guia_entrada}/{id_user}/{id_producto}', 'StocksController@eliminar_producto_nota_entrada_actualizar');
Route::get('/edit_documento_nota_entrada/{id_user}', 'StocksController@edit_documento_nota_entrada');

Route::get('/all_orden_ingresadas/{iduser?}', 'StocksController@all_orden_ingresadas');

//Route::get('/buscar_nota_entrada/{nro_nota}', 'StocksController@buscar_nota_entrada');
//Route::get('/buscar_nota_entrada/{nro_nota}/{id_farmacia}', 'StocksController@buscar_nota_entrada');
Route::get('/buscar_nota_entrada/{nro_nota}/{id_farmacia}/{concepto}', 'StocksController@buscar_nota_entrada');

Route::get('/anular_ingreso/{id}', 'StocksController@anular');

Route::get('/obtener_establecimiento_nivel/{nivel}', 'FarmaciaController@obtener_establecimiento_nivel');

Route::get('/busca_productos_guia_entrada/{id_pecosa}/{id_producto}', 'StocksController@busca_productos_guia_entrada');
Route::get('/valida_productos_guia_entrada/{id_pecosa}/{id_producto}/{cantidad}', 'StocksController@valida_productos_guia_entrada');
Route::get('/valida_productos_guia_entrada_modificado/{id_pecosa}/{id_producto}/{cantidad}/{id_carga_pecosa}', 'StocksController@valida_productos_guia_entrada_modificado');

Route::get('/cambiar_estado_envio/{id_transferencia}/{id_farmacia}', 'TransferenciaController@cambiar_estado_envio');

//------------------------------ ESTABLECIMIENTOS---------------------------------------------------------------
Route::get('/editar_establecimiento/{id}', 'EstablecimientoController@editar_establecimiento');
Route::get('/listar_establecimientos/{id_user?}', 'EstablecimientoController@index');
Route::get('/formulario_establecimiento/', 'EstablecimientoController@formulario_establecimiento');
Route::post('store_establecimiento', [
  'uses' => 'EstablecimientoController@store',
  'as' => 'store_establecimiento'
]);

Route::post('update_establecimiento', ['uses' => 'EstablecimientoController@update_establecimiento', 'as' => 'update_establecimiento']);

Route::get('/desactivar_establecimiento/{id}/{estado}', 'EstablecimientoController@desactivar');
Route::get('establecimiento/asignar_servicios/{id_establecimiento}', 'EstablecimientoController@asignar_servicios');

Route::post('buscar_orden_internamiento', [
  'uses' => 'StocksController@buscar_orden_internamiento',
  'as' => 'buscar_orden_internamiento'
]);

Route::get('/buscar_orden_internamiento', 'StocksController@buscar_orden_internamiento');

//----------------------------PROVEEDORES----------------------------------------------------------------
Route::get('/editar_proveedor/{id}', 'ProveedorController@editar_proveedor');
Route::get('/listar_proveedores/{id_user?}', 'ProveedorController@index');
Route::get('/formulario_proveedor/', 'ProveedorController@formulario_proveedor');
Route::post('store_proveedor', [
  'uses' => 'ProveedorController@store',
  'as' => 'store_proveedor'
]);
Route::get('/all_motivo_transferencia', 'MotivoTransferenciaController@index');
Route::get('/create_motivo_transferencia', 'MotivoTransferenciaController@create');
Route::post('store_motivo_transferencia', [
  'uses' => 'MotivoTransferenciaController@store',
  'as' => 'store_motivo_transferencia'
]);
Route::get('/edit_motivo_transferencia/{id}', 'MotivoTransferenciaController@edit');
Route::post('update_motivo_transferencia', [
  'uses' => 'MotivoTransferenciaController@update',
  'as' => 'update_motivo_transferencia'
]);
Route::get('/eliminar_motivo_transferencia/{id}', 'MotivoTransferenciaController@eliminar');
Route::get('/obtener_receta/{id_farmacia}/{nro_receta}', 'RecetaValeController@obtener_receta');
//Route::get('/obtener_beneficiario/{id_tiporeceta}/{tipodoc_beneficiario}/{dni_beneficiario}', 'RecetaValeController@obtener_beneficiario');
// EN REEMPLAZO DE LA ANTERIOR CON UN NUEVO PARÁMETRO PARA COMENZAR POR PARTES CON EL API SP
Route::get('/obtener_beneficiario/{id_tiporeceta}/{tipodoc_beneficiario}/{dni_beneficiario}/{usar_api?}/{origen?}', 'RecetaValeController@obtener_beneficiario');

Route::get('/condicion_paciente/{dni}/{id_establecimiento}/{tipo}/{id_servicio}', 'RecetaValeController@condicion_paciente');

Route::post('listar_proveedores_ajax', [
  'uses' => 'ProveedorController@listar_proveedores_ajax',
  'as' => 'listar_proveedores_ajax'
]);
/*************************/
Route::get('/obtener_beneficiario_dirrehum/{id_tiporeceta}/{tipodoc_beneficiario}/{dni_beneficiario}', 'RecetaValeController@obtener_beneficiario_dirrehum');

Route::get('/all_tipo_atencion', 'TipoAtencionController@index');
Route::get('/create_tipo_atencion', 'TipoAtencionController@create');
Route::post('store_tipo_atencion', [
  'uses' => 'TipoAtencionController@store',
  'as' => 'store_tipo_atencion'
]);
Route::get('/edit_tipo_atencion/{id}', 'TipoAtencionController@edit');
Route::post('update_tipo_atencion', [
  'uses' => 'TipoAtencionController@update',
  'as' => 'update_tipo_atencion'
]);
Route::get('/eliminar_tipo_atencion/{id}', 'TipoAtencionController@eliminar');
Route::post('update_proveedor', [
  'uses' => 'ProveedorController@update_proveedor',
  'as' => 'update_proveedor'
]);
Route::get('/cargaproveedor/', 'ProveedorController@cargaproveedor');

Route::get('/desactivar_proveedor/{id}/{estado}', 'ProveedorController@desactivar');
//------------------------------DEPENDENCIAS---------------------------------------------------------------
Route::get('/editar_dependencia/{id}', 'DependenciaController@editar_dependencia');
Route::get('/listar_dependencias/{id_user?}', 'DependenciaController@index');
Route::get('/formulario_dependencia/', 'DependenciaController@formulario_dependencia');
Route::post('store_dependencia', [
  'uses' => 'DependenciaController@store',
  'as' => 'store_dependencia'
]);

Route::post('update_dependencia', [
  'uses' => 'DependenciaController@update_dependencia',
  'as' => 'update_dependencia'
]);

Route::get('/desactivar_dependencia/{id}/{estado}', 'DependenciaController@desactivar');

Route::post('listar_dependencias_ajax', [
  'uses' => 'DependenciaController@listar_dependencias_ajax',
  'as' => 'listar_dependencias_ajax'
]);
//------------------------------TIPO DE PROCESOS---------------------------------------------------------------
Route::get('/editar_tipo_proceso/{id}', 'TipoProcesoController@editar_tipo_proceso');
Route::get('/listar_tipo_procesos/{id_user?}', 'TipoProcesoController@index');
Route::get('/formulario_tipo_proceso/', 'TipoProcesoController@formulario_tipo_proceso');
Route::post('store_tipo_proceso', [
  'uses' => 'TipoProcesoController@store',
  'as' => 'store_tipo_proceso'
]);

Route::post('update_tipo_proceso', [
  'uses' => 'TipoProcesoController@update_tipo_proceso',
  'as' => 'update_tipo_proceso'
]);

Route::get('/desactivar_tipo_proceso/{id}/{estado}', 'TipoProcesoController@desactivar');

//------------------------------CGs---------------------------------------------------------------
Route::get('/editar_cg/{id}', 'ClasificadorGastoController@editar_cg');
Route::get('/listar_cgs/{id_user?}', 'ClasificadorGastoController@index');
Route::get('/formulario_cg/', 'ClasificadorGastoController@formulario_cg');
Route::post('store_cg', [
  'uses' => 'ClasificadorGastoController@store',
  'as' => 'store_cg'
]);

Route::post('update_cg', [
  'uses' => 'ClasificadorGastoController@update_cg',
  'as' => 'update_cg'
]);

Route::get('/desactivar_cg/{id}/{estado}', 'ClasificadorGastoController@desactivar');

Route::get('/evitar_duplicados/{nro}/{tipo}/{id_farmacia}', 'StocksController@evitar_duplicados');
Route::get('/evitar_duplicados_edicion/{nro}/{tipo}/{id_farmacia}/{id}', 'StocksController@evitar_duplicados_edicion');

Route::get('/edit_nota_entrada/{id}/{id_user}', 'StocksController@edit_nota_entrada');

Route::post('editar_documento_nota_entrada', [
  'uses' => 'StocksController@editar_documento_nota_entrada',
  'as' => 'editar_documento_nota_entrada'
]);

Route::post('editar_documento_nota_entrada_almacen', [
  'uses' => 'StocksController@editar_documento_nota_entrada_almacen',
  'as' => 'editar_documento_nota_entrada_almacen'
]);

Route::post('update_producto_transferencia_actualizado', [
  'uses' => 'TransferenciaController@update_producto_transferencia_actualizado',
  'as' => 'update_producto_transferencia_actualizado'
]);

Route::get('/editar_producto_para_transferir_actualizado/{id_transferencia_producto}', 'TransferenciaController@editar_producto_para_transferir_actualizado');

Route::get('/eliminar_producto_para_transferir_actualizado/{id_transferencia_producto}/{id_farmacia}', 'TransferenciaController@eliminar_producto_para_transferir_actualizado');

Route::get('/all_transferencias_productos/', 'TransferenciaController@all_transferencias_productos');

Route::get('/eliminar_nota_entrada/{id}', 'StocksController@eliminar');
Route::get('/eliminar_nota_entrada_almacen/{id}', 'StocksController@eliminar_almacen');

Route::get('/nueva_receta_beneficiario/', 'BeneficiarioController@nueva_receta_beneficiario');

Route::get('/qr_genera/', 'BeneficiarioController@qr_genera');

Route::get('/obtener_producto_presentacion_comercial/{id_producto}', 'ProductoController@obtener_producto_pc');

Route::post('allEstablecimientos_withStocks', [
  'uses' => 'StocksController@allEstablecimientos_withStocks',
  'as' => 'allEstablecimientos_withStocks'
]);
Route::get('/allEstablecimientos_withStocks', 'StocksController@allEstablecimientos_withStocks');

Route::get('/exportar_reporte_ici_establecimiento/{fecha_inicio}/{fecha_fin}/{id_establecimiento}/{tipo}', 'ExcelController@exportar_reporte_ici_establecimiento')->middleware('check.server.resources');

Route::get('/cargarorigen/{tipo}', 'StocksController@cargarorigen');
Route::post('store_concepto', [
  'uses' => 'ClasificadorGastoController@store_concepto',
  'as' => 'store_concepto'
]);
Route::post('update_concepto', [
  'uses' => 'ClasificadorGastoController@update_concepto',
  'as' => 'update_concepto'
]);
Route::get('/desactivar_concepto/{id}/{estado}', 'ClasificadorGastoController@desactivar_c');
Route::get('/editar_concepto/{id}', 'ClasificadorGastoController@editar_concepto');
Route::get('/formulario_concepto/', 'ClasificadorGastoController@formulario_concepto');
Route::get('/movimiento_producto', 'StocksController@movimiento_producto');
Route::post('buscar_movimiento_producto', [
  'uses' => 'StocksController@buscar_movimiento_producto',
  'as' => 'buscar_movimiento_producto'
]);
Route::get('/buscar_movimiento_producto', 'StocksController@buscar_movimiento_producto');

Route::get('/stocksbyAlmacenModal/', 'StocksController@stocksbyAlmacenModal');
Route::post('busca_transferencia', [
  'uses' => 'TransferenciaController@busca_transferencia',
  'as' => 'busca_transferencia'
]);
Route::get('/busca_transferencia', 'TransferenciaController@busca_transferencia');


Route::get('/establecimiento_producto/{id_producto?}', 'ProductoController@establecimiento_producto');

Route::post('listar_establecimiento_producto', [
  'uses' => 'ProductoController@listar_establecimiento_producto',
  'as' => 'listar_establecimiento_producto'
]);
Route::get('/listar_establecimiento_producto', 'ProductoController@listar_establecimiento_producto');
Route::get('/exportar_producto_establecimiento/{codigo_producto}', 'ExcelController@exportar_producto_establecimiento')->middleware('check.server.resources');

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('/establecimiento_producto_total', 'ExcelController@exportar_total_producto_establecimiento');

/**************************************************************************************************************/
Route::get('/add_producto_cpm', 'ConsumoPromedioController@add_producto_farmacia_cpm');

Route::post('agregar_producto_cpm', [
  'uses' => 'ConsumoPromedioController@agregar_producto_cpm',
  'as' => 'agregar_producto_cpm'
]);
Route::get('/eliminar_producto_servicio/{id}', 'ConsumoPromedioController@eliminar_producto_servicio');
Route::get('/eliminar_producto_consumo/{id}', 'ConsumoPromedioController@eliminar_producto_consumo');
Route::get('/edit_producto_cpm/{id}', 'ConsumoPromedioController@edit');
Route::post('update_producto_cpm', [
  'uses' => 'ConsumoPromedioController@update',
  'as' => 'update_producto_cpm'
]);
Route::get('/famarcia_cpm/{iduser?}/{situacion?}', 'ConsumoPromedioController@stocksbyFamarciabyCPM');
Route::get('/productos_vencidos_cpm/{id_farmacia}', 'ConsumoPromedioController@productos_vencidos_new');
Route::get('/productos_proximos_vencer_cpm/{id_farmacia}', 'ConsumoPromedioController@productos_proximos_vencer_new');
Route::get('/imprimir_stock_bycpmfarmacia/{idfarmacia}/{situacion?}', 'ConsumoPromedioController@imprimir_stock_byidcpmfarmacia');
Route::get('/descargar_stock_almacen_sb/{fechaDesde?}/{fechaHasta?}', 'ConsumoPromedioController@descargar_nuevos_productos_ingresados_almacen');
Route::get('/nuevos_registros_sb', 'ConsumoPromedioController@nuevos_registros_sb');
Route::post('busca_nuevo_registro_sb', [
  'uses' => 'ConsumoPromedioController@busca_nuevo_registro_sb',
  'as' => 'busca_nuevo_registro_sb'
]);

Route::get('/allMonitoreo_withStocks/', 'ConsumoPromedioController@allMonitoreo_withStocks');
Route::get('/visualizar_productos_nivel/{idsituacion?}/{nivel?}', 'ConsumoPromedioController@allMonitoreo');
Route::get('/allNivel_withStocks', 'ConsumoPromedioController@allNivel_withStocks');

//------------------------------CENARES---------------------------------------------------------------
Route::get('/trama_cenares/{id_producto?}', 'CenaresController@trama_cenares');

Route::post('listar_trama_cenares', [
  'uses' => 'CenaresController@listar_trama_cenares',
  'as' => 'listar_trama_cenares'
]);
Route::get('/listar_trama_cenares', 'CenaresController@listar_trama_cenares');
Route::get('/muestra_trama_cenares/{fecha}', 'CenaresController@muestra_trama_cenares');
Route::get('/muestra_trama_cenares_hoy/', 'CenaresController@muestra_trama_cenares_hoy');
Route::get('/exportar_trama_cenares/{fecha}/{id}', 'ExcelController@exportar_trama_cenares')->middleware('check.server.resources');

/*****************************************************************************************************************************/
/*___________________________________________________________________________________________________________________________*/


/*****************************************************************************************************************************/
Route::post('formulario_gestion_cita', [
  'uses' => 'GestionCitaController@formulario_gestion_cita',
  'as' => 'formulario_gestion_cita'
]);

Route::get('/formulario_gestion_cita', 'GestionCitaController@formulario_gestion_cita');

Route::get('/mostrar_cita_medico/{id_ipress}/{id_consultorio}/{fecha_atencion?}/{id_turno?}/{id_medico?}', 'GestionCitaController@mostrar_cita_medico');
Route::get('/mostrar_turnos/{id_ipress}/{id_consultorio}/{fecha_atencion}', 'GestionCitaController@mostrar_turnos');
Route::get('/mostrar_historial_cita/{dni_beneficiario}', 'GestionCitaController@mostrar_historial_cita');
Route::get('/mostrar_historia/{id}', 'GestionCitaController@mostrar_historia');

Route::post('registrar_gestion_cita', [
  'uses' => 'GestionCitaController@registrar_gestion_cita',
  'as' => 'registrar_gestion_cita'
]);

Route::get('/ver_cita_registrada/{id_cita}', 'GestionCitaController@ver_cita_registrada');
Route::get('/ver_modal_cita_registrada/{id_cita}', 'GestionCitaController@ver_modal_cita_registrada');

Route::get('/cronograma_gestion_cita/{id_ipress}/{id_consultorio}', 'GestionCitaController@cronograma_gestion_cita');
Route::get('/cronograma_gestion_cita_medico/{id_ipress}/{id_consultorio}/{id_medico}', 'GestionCitaController@cronograma_gestion_cita_medico');

Route::get('/buscar_obtener_beneficiario/{tipodoc_beneficiario}/{dni_beneficiario}/{id_establecimiento}/{id_servicio}', 'GestionCitaController@buscar_obtener_beneficiario');
Route::get('/buscar_cita_beneficiario/{fecha_cita}/{dni_beneficiario}/{id_establecimiento}/{id_servicio}', 'GestionCitaController@buscar_cita_beneficiario');

Route::get('/all_citas_de_paciente/{iduser?}', 'GestionCitaController@all_citas_de_paciente');
Route::get('/listar_citas_de_paciente/{iduser?}', 'GestionCitaController@all_citas_de_paciente');
Route::post('listar_citas_de_paciente', [
  'uses' => 'GestionCitaController@listar_citas_de_paciente',
  'as' => 'listar_citas_de_paciente'
]);

Route::post('listar_citas_ajax', [
  'uses' => 'GestionCitaController@listar_citas_ajax',
  'as' => 'listar_citas_ajax'
]);

Route::get('/all_cita_por_farmacia/{iduser?}', 'GestionCitaController@all_cita_por_farmacia');

Route::get('/modal_nro_historial_cita/{dni_beneficiario}', 'GestionCitaController@modal_historial_cita');
Route::get('/modal_nro_historia/{id}', 'GestionCitaController@modal_nro_historia');
Route::post('update_nro_historia', [
  'uses' => 'GestionCitaController@update_nro_historia',
  'as' => 'update_nro_historia'
]);
Route::get('/anular_nro_cita/{id_cita}', 'GestionCitaController@anular_cita');
Route::get('/exportar_citas/{idUser}/{fecha_ini}/{fecha_fin}/{dni}/{paciente}/{estado}/{id_servicio}/{id_consultorio}/{id_medico}', 'ExcelController@exportar_citas')->middleware('check.server.resources');
Route::get('/imprimir_citas/{idUser}/{fecha_ini}/{fecha_fin}/{dni}/{paciente}/{estado}/{id_servicio}/{id_consultorio}/{id_medico}', 'PdfController@imprimir_citas');

Route::get('/obtener_establecimiento_nivel/{nivel}', 'FarmaciaController@obtener_establecimiento_nivel');
Route::get('/mostrar_consultorios/{id_ipress}', 'ConsultorioController@mostrar_consultorios');

/*_________________________________________________________________________________________________________________________________________________________________*/
//Route::get('/ver_receta_vale_digital/{nro_receta}', 'ExcelController@ver_receta_vale_digital');
Route::get('/{flag}/{ano}/{mes}/{dia}/{name}' . '.pdf', 'ExcelController@ver_prestacion_receta_digital');
Route::get('/{ano}/{mes}/{dia}/{name}' . '.pdf', 'ExcelController@ver_prestacion_receta_sigesapol');
Route::post('sinTokens', [
  'uses' => 'ExcelController@sinTokens',
  'as' => 'sinTokens'
]);
Route::post('sinTokensAdmision', [
  'uses' => 'ExcelController@sinTokensAdmision',
  'as' => 'sinTokensAdmision'
]);

Route::post('sinTokensEgresoEmergencia', [
  'uses' => 'ExcelController@sinTokensEgresoEmergencia',
  'as' => 'sinTokensEgresoEmergencia'
]);

Route::post('sinTokensRadiologia', [
  'uses' => 'ExcelController@sinTokensRadiologia',
  'as' => 'sinTokensRadiologia'
]);

Route::post('sinTokenAltaEmergencia', [
  'uses' => 'ExcelController@sinTokenAltaEmergencia',
  'as' => 'sinTokenAltaEmergencia'
]);

/*----------------------------------------------------------------------------------------------------------------------------*/
//Route::get('buscar_personal_dni/{nro_doc}/{tipo_doc}', 'UsuarioController@buscar_personal_dni')->name('users.buscar_personal_dni');
//------------------------------DIAGNOSTICOS---------------------------------------------------------------
Route::get('/editar_diagnostico/{id}', 'DiagnosticoController@editar_diagnostico');
Route::get('/listar_diagnosticos/{id_user?}', 'DiagnosticoController@index');
Route::get('/formulario_diagnostico/', 'DiagnosticoController@formulario_diagnostico');
Route::post('store_diagnostico', [
  'uses' => 'DiagnosticoController@store',
  'as' => 'store_diagnostico'
]);

Route::post('update_diagnostico', [
  'uses' => 'DiagnosticoController@update_diagnostico',
  'as' => 'update_diagnostico'
]);

Route::get('/desactivar_diagnostico/{id}/{estado}', 'DiagnosticoController@desactivar');

Route::post('listar_diagnosticos_ajax', [
  'uses' => 'DiagnosticoController@listar_diagnosticos_ajax',
  'as' => 'listar_diagnosticos_ajax'
]);
//-------------TRIAJE----------------------------------
Route::get('/obtener_triaje_por_prioridad/{prioridad}', 'TriajeController@obtener_triaje_por_prioridad');

Route::post('registrar_emergencia', [
  'uses' => 'RecetaValeController@registrar_emergencia_transaction',
  'as' => 'registrar_emergencia'
]);

Route::post('actualizar_emergencia', [
  'uses' => 'RecetaValeController@actualizar_emergencia_transaction',
  'as' => 'actualizar_emergencia'
]);

Route::post('actualizar_alta_emergencia', [
  'uses' => 'RecetaValeController@actualizar_alta_emergencia_transaction',
  'as' => 'actualizar_alta_emergencia'
]);

Route::get('/obtener_provincia_por_departamento/{departamento}', 'DistritoController@obtener_provincia_por_departamento');
Route::get('/obtener_distrito_por_provincia/{provincia}', 'DistritoController@obtener_distrito_por_provincia');
Route::get('/obtener_tipo_violencia/{id_violencia}', 'ViolenciaController@obtener_tipo_violencia');
//Route::get('/all_comisarias', 'ComisariaController@index');
Route::get('/all_emergencia_alta_by_farmacia/{iduser?}', 'EmergenciaController@all_emergencia_alta_by_farmacia');
Route::get('/dar_alta_emergencia/{iduser?}/{nro_hospitalizacion}', 'EmergenciaController@dar_alta_emergencia');
Route::post('registrar_receta_emergencia', [
  'uses' => 'EmergenciaController@registrarExamenComplementarios',
  'as' => 'registrar_receta_emergencia'
]);

Route::get('/modificar_alta_emergencia/{iduser?}/{nro_hospitalizacion}', 'EmergenciaController@modificar_alta_emergencia');

Route::get('/obtener_destino_por_condicion/{condicion}', 'DestinoController@obtener_destino_por_condicion');

Route::post('guardar_alta_emergencia', [
  'uses' => 'EmergenciaController@guardar_alta_emergencia',
  'as' => 'guardar_alta_emergencia'
]);

Route::get('/exportar_reporte_atencion_emergencia/{idUser}/{fecha_ini}/{fecha_fin}/{dni}/{paciente}/{estado}/{id_prioridad}/{id_topico}', 'ExcelController@exportar_reporte_atencion_emergencia')->middleware('check.server.resources');
Route::get('/exportar_cuadro_atencion_emergencia/{idUser}/{fecha_ini}/{fecha_fin}/{dni}/{paciente}/{estado}/{id_prioridad}/{id_topico}', 'ExcelController@exportar_cuadro_atencion_emergencia')->middleware('check.server.resources');

Route::get('/exportar_reporte_atencion_detallado_emergencia/{idUser}/{fecha_ini}/{fecha_fin}/{dni}/{paciente}/{estado}/{id_prioridad}/{id_topico}', 'ExcelController@exportar_reporte_atencion_detallado_emergencia')->middleware('check.server.resources');


Route::post('listar_prestacion_ajax_emergencia', [
  'uses' => 'EmergenciaController@listar_prestacion_ajax_emergencia',
  'as' => 'listar_prestacion_ajax_emergencia'
]);
Route::get('/all_forma_ingreso', 'FormaIngresoController@index');
Route::get('/deshabilitar_forma_ingreso/{id}', 'FormaIngresoController@deshabilitar');
Route::get('/habilitar_forma_ingreso/{id}', 'FormaIngresoController@habilitar');
Route::get('/edit_forma_ingreso/{id}', 'FormaIngresoController@edit');
Route::get('/create_forma_ingreso', 'FormaIngresoController@create');

Route::post('update_forma_ingreso', [
  'uses' => 'FormaIngresoController@update',
  'as' => 'update_forma_ingreso'
]);

Route::post('store_forma_ingreso', [
  'uses' => 'FormaIngresoController@store',
  'as' => 'store_forma_ingreso'
]);

/**********************Hospitalizacion**************************************/
Route::get('/all_atencion_hospitalizacion/{iduser?}', 'HospitalizacionController@all_atencion_hospitalizacion');
Route::get('/listar_atenciones_hospitalizacion/{iduser?}', 'HospitalizacionController@all_atencion_hospitalizacion');
Route::get('/formulario_atencion_hospitalizacion', 'HospitalizacionController@formulario_atencion_hospitalizacion');

Route::post('listar_prestacion_hospitalizacion', [
  'uses' => 'HospitalizacionController@listar_prestacion_hospitalizacion',
  'as' => 'listar_prestacion_hospitalizacion'
]);

Route::get('/cargarstock_porfarmacia/{id_producto}/{id_farmacia}', 'HospitalizacionController@cargarstock_porfarmacia');

/*--------------------------------------------------------------------------------------------------------------------------------------*/
Route::get('/atencion_teleconsulta', 'RecetaValeController@formulario_atencion_teleconsulta');
Route::get('/obtener_consultorios_por_ipress/{id_establecimiento}/{id_servicio}', 'ConsultorioController@obtener_consultorios_por_ipress');
Route::get('/all_atenciones_medico_ipress/{iduser?}', 'CitaController@all_medico_atenciones_ipress');
Route::post('listar_atenciones_ipress', [
  'uses' => 'CitaController@listar_atenciones_ipress',
  'as' => 'listar_atenciones_ipress'
]);
Route::get('/obtener_farmacia_por_id_establecimiento_atencion/{id_establecimiento}/{id_tipo_atencion}', 'FarmaciaController@obtener_farmacia_por_id_establecimiento_atencion');
Route::get('/ver_prestacion_teleconsulta/{id_user}/{id_prestacion}', 'RecetaValeController@ver_prestacion_teleconsulta');


/***********************************************************/
Route::get('/crea_beneficiario', 'RecetaValeController@crea_beneficiario');
Route::get('/crea_beneficiario_todo', 'RecetaValeController@crea_beneficiario_todo');
Route::get('/crea_data_final', 'RecetaValeController@crea_data_final');


//Route::get('/nuevo_query', 'QueryEditorController@nuevo_query')->name('nuevo_query');
//Route::post('/procesa_query', 'QueryEditorController@procesa_query')->name('procesa_query');

//Route::get('/nuevo_query_posgres', 'QueryEditorController@nuevo_query');
Route::get('/query_posgres_dirsapol', 'QueryEditorController@nuevo_query_ruta');
Route::get('/editor_posgres_dirsapol', 'QueryEditorController@nuevo_query');
Route::post('procesa_query', [
  'uses' => 'QueryEditorController@procesa_query',
  'as' => 'procesa_query'
]);

//------------------------------PROCEDIMIENTOS---------------------------------------------------------------
Route::get('/editar_procedimiento/{id}', 'ProcedimientoController@editar_procedimiento');
Route::get('/listar_procedimientos/{id_user?}', 'ProcedimientoController@index');
Route::get('/formulario_procedimiento/', 'ProcedimientoController@formulario_procedimiento');
Route::post('store_procedimiento', [
  'uses' => 'ProcedimientoController@store',
  'as' => 'store_procedimiento'
]);

Route::post('update_procedimiento', [
  'uses' => 'ProcedimientoController@update_procedimiento',
  'as' => 'update_procedimiento'
]);

Route::get('/desactivar_procedimiento/{id}/{estado}', 'ProcedimientoController@desactivar');
Route::get('/cargarseccion/{tipo}', 'ProcedimientoController@cargarseccion');
Route::get('/cargarsubseccion/{tipo}', 'ProcedimientoController@cargarsubseccion');

//----------------------------------PROCEDIMIENTO-----------------------------------------------------------------------------
Route::get('/all_tipo_procedimiento', 'TipoProcedimientoController@index');
Route::get('/create_tipo_procedimiento', 'TipoProcedimientoController@create');
Route::post('store_tipo_procedimiento', [
  'uses' => 'TipoProcedimientoController@store',
  'as' => 'store_tipo_procedimiento'
]);
Route::get('/edit_tipo_procedimiento/{id}', 'TipoProcedimientoController@edit');
Route::post('update_tipo_procedimiento', [
  'uses' => 'TipoProcedimientoController@update',
  'as' => 'update_tipo_procedimiento'
]);
Route::get('/eliminar_tipo_procedimiento/{id}', 'TipoProcedimientoController@eliminar');


//----------------------------------ADMISION-------------------------------------------------------------------------
Route::post('formulario_consulta', [
  'uses' => 'RecetaValeController@formulario_consulta',
  'as' => 'formulario_consulta'
]);

Route::get('/formulario_consulta', 'RecetaValeController@formulario_consulta');

Route::post('buscar_nota_entrada_almacen', [
  'uses' => 'StocksController@buscar_nota_entrada_almacen',
  'as' => 'buscar_nota_entrada_almacen'
]);

Route::get('/buscar_nota_entrada_almacen', 'StocksController@buscar_nota_entrada_almacen');


//--------------------------------------------------------------------------------------------------------------
Route::get('/obtener_diagnostico_genero/{buscar}/{genero}', 'RecetaValeController@obtener_diagnostico_genero');
Route::get('/obtener_diagnostico_genero_ver/{buscar}/{genero}', 'RecetaValeController@obtener_diagnostico_genero')->name('obtener_diagnostico_genero_ver');
Route::get('/obtener_procedimiento_convenio/{buscar}/{nivel}/{genero}/{tipo}', 'RecetaValeController@obtener_procedimiento_convenio');
Route::get('/obtener_procedimiento_convenio_enfermeria/{buscar}/{nivel}/{genero}/{tipo}', 'RecetaValeController@obtener_procedimiento_convenio_enfermeria');
Route::get('/obtener_procedimiento_convenio_emergencia/{buscar}/{nivel}/{genero}/{tipo}', 'EmergenciaController@obtener_procedimiento_convenio_emergencia');
Route::get('/obtener_laboratorio_convenio/{buscar}/{nivel}/{genero}/{tipo_atencion}/{id_servicio?}/{id_perfil_preoperatorio?}', 'RecetaValeController@obtener_laboratorio_convenio');
Route::get('/obtener_laboratorio_convenio_emergencia/{buscar}/{nivel}/{genero}/{tipo_atencion}/{id_servicio?}', 'RecetaValeController@obtener_laboratorio_convenio_emergencia');


Route::get('/obtener_procedimiento_convenio_tipo_atencion/{buscar}/{nivel}/{genero}/{tipo_atencion}', 'RecetaValeController@obtener_procedimiento_convenio_tipo_atencion');
//---------------------------------------------------------------------------------------------------------------
Route::post('registrar_prestacion_sintoken', [
  'uses' => 'RecetaValeController@registrar_prestacion_transaction_sintoken',
  'as' => 'registrar_prestacion_sintoken'
]);


Route::get('/ver_prestacion_sintoken/{id_user}/{id_prestacion}', 'RecetaValeController@ver_prestacion_sintoken');

Route::get('/formulario_atencion_nuevo_sintoken', 'RecetaValeController@formulario_atencion_nuevo_sintoken');

Route::post('registrar_prestacion_sintoken_mejorado', [
  'uses' => 'RecetaValeController@registrar_prestacion_transaction_sintoken_mejorado',
  'as' => 'registrar_prestacion_sintoken_mejorado'
]);


Route::get('/ver_prestacion_sintoken_mejorado/{id_user}/{id_prestacion}', 'RecetaValeController@ver_prestacion_sintoken_mejorado');

Route::get('/formulario_atencion_nuevo_sintoken_mejorado', 'RecetaValeController@formulario_atencion_nuevo_sintoken_mejorado');


/***********************Laboratorio**************/
Route::get('/all_laboratorio_prescritos_by_ipress/{iduser?}', 'LaboratorioController@all_laboratorio_prescritos_by_ipress');
Route::post('listar_examenes_laboratorios', [
  'uses' => 'LaboratorioController@listar_examenes_laboratorios',
  'as' => 'listar_examenes_laboratorios'
]);
Route::get('/listar_examenes_laboratorios/{data_array}', 'LaboratorioController@listar_examenes_laboratorios')->name('listar_examenes_laboratorios');
Route::get('/api/listar_examenes_laboratorios_nivel2', 'LaboratorioController@listar_examenes_laboratorios_nivel2')->name('listar_examenes_laboratorios_nivel2');
Route::get('/nivel_1_2/laboratorio/{id_laboratorio}/prestacion/{id_prestacion}', 'LaboratorioController@verDetalleAtencion')->name('verDetalleAtencion');
Route::get('/api/nivel_1_2/laboratorio/{id_laboratorio}/prestacion/{id_prestacion}', 'LaboratorioController@getExamenesByIdPrestacionIdLaboratorio')->name('getExamenesByIdPrestacionIdLaboratorio');
Route::get('/api/nivel_1_2/laboratorio/resultado/{nombre_archivo}', 'LaboratorioController@descargarArchivoResultado')->where('nombre_archivo', '.*');
Route::get('/all_rua', 'LaboratorioController@all_rua');
// Route::get('/add_rua_nivel/{nivel}/{tipo}/{categoria}', 'LaboratorioController@add_rua_nivel');
Route::get('/add_rua_nivel/{nivel}/{tipo}/{categoria}', 'LaboratorioController@add_rua_nivel');
Route::post('agregar_rua_nivel_tipo_atencion', [
  'uses' => 'LaboratorioController@agregar_rua_nivel_tipo_atencion',
  'as' => 'agregar_rua_nivel_tipo_atencion'
]);
Route::post('/api/agregar_rua_nivel_tipo_atencion_nivel_1_2', 'LaboratorioController@agregar_rua_nivel_tipo_atencion_nivel_1_2');

Route::get('/eliminar_rua_nivel_tipo_atencion/{id}/{tipo}/{nivel}/{categoria}/{servicio}/{id_categoria_rua_nivel}', 'LaboratorioController@eliminar_rua_nivel_tipo_atencion');
Route::put('/api/nivel_1_2/rua/{id_labnivelatencion}/cambiar-estado', 'LaboratorioController@cambiarEstadoNive1y2');
// Route::get('/add_categoria_rua/{nivel}/{tipo}', 'LaboratorioController@add_categoria_rua');
Route::get('/add_categoria_rua/{nivel}/{tipo}', 'LaboratorioController@add_categoria_rua');
Route::get('/api/categoria_rua_nivel/{id_categoria_rua_nivel}', 'LaboratorioController@getCategoriaRuaNivelById');
Route::put('/api/categoria_rua_nivel/{id}', 'LaboratorioController@updateCategoriaRuaNivel');
Route::post('agregar_categoria_rua_atencion', [
  'uses' => 'LaboratorioController@agregar_categoria_rua_atencion',
  'as' => 'agregar_categoria_rua_atencion'
]);
Route::post('/api/nivel_1_2/agregar_categoria_rua_atencion', 'LaboratorioController@agregar_categoria_rua_atencion_nivel_1_2');
Route::get('/desactivar_nivel_categoria/{id}/{estado}', 'LaboratorioController@desactivar_nivel_categoria');

/*Route::get('/all_laboratorio_atendidas_by_ipress/{iduser?}', 'LaboratorioController@all_laboratorio_atendidas_by_ipress');
Route::post('listar_atenciones_laboratorios', [
    'uses' => 'LaboratorioController@listar_atenciones_laboratorios',
    'as' => 'listar_atenciones_laboratorios'
]);
Route::get('/listar_atenciones_laboratorios', 'LaboratorioController@listar_atenciones_laboratorios');
*/
Route::get('/formulario_atencion_laboratorio/{id_lab}/{id_tipo_examen}', 'LaboratorioController@formulario_atencion_laboratorio');
Route::get('/ReprogramarCitaLaboratorio/{idCita}/{idSubConsultorio}/{fecha}', 'LaboratorioController@ReprogramarCitaLaboratorio');

/***********************Radiologia**************/
Route::get('/all_radiologia_prescritas_by_ipress/{iduser?}', 'RadiologiaController@all_radiologia_prescritas_by_ipress');
Route::post('listar_examenes_radiologicos', [
  'uses' => 'RadiologiaController@listar_examenes_radiologicos',
  'as' => 'listar_examenes_radiologicos'
]);

Route::get('/ver_radiologia_imprimir/{id}', 'ExcelController@ver_pdf_radiologia_imprimir');
Route::get('/ver_radiologia_imprimir_nivel1y2/{id}', 'ExcelController@ver_pdf_radiologia_imprimir_nivel1y2');
Route::get('/ver_radiologia_imprimir2/{id}', 'ExcelController@ver_pdf_radiologia_imprimir2');
Route::get('/ver_laboratorio_imprimir/{id}', 'ExcelController@ver_pdf_laboratorio_print');
Route::get('/ver_lab_orden_sin_reactivo/{id}', 'ExcelController@ver_lab_orden_sin_reactivo');
Route::get('/listar_examenes_radiologicos', 'RadiologiaController@listar_examenes_radiologicos');
Route::get('/formulario_atencion_radiologia/{id}', 'RadiologiaController@formulario_atencion_radiologia');
Route::get('/editar_radiologia/{id}', 'RadiologiaController@editar_radiologia');

Route::get('/print_examen_radiologico/{id_radiologia}', 'PdfController@print_examen_radiologico');

Route::get('/ver_atencion_radiologia/{id_radiologia}/{id_servicio_radiologia}/{id_pp}', 'RadiologiaController@ver_atencion_radiologia');
Route::get('/ver_atencion_radiologia_adicional/{id_radiologia}/{id_servicio_radiologia}/{idpp}/{id_informe_adicional?}', 'RadiologiaController@ver_atencion_radiologia_adicional');

/****************************************************/
Route::get('/formularios/{id}', 'LaboratorioController@formularios');

Route::post('registrar_examen_laboratorio', [
  'uses' => 'LaboratorioController@registrar_examen_laboratorio_transaction',
  'as' => 'registrar_examen_laboratorio'
]);

Route::get('/print_examen_laboratorio/{id_laboratorio}/{tipo_examen}', 'PdfController@print_examen_laboratorio');
Route::get('/laboratorio_ver_rua_sr/{id_laboratorio}', 'PdfController@laboratorio_ver_rua_sr');
Route::get('/print_examen_laboratorio_lns/{id_laboratorio}/{dni_beneficiario}/{dia}/{mes}/{anio}/{nro_orden}', 'PdfController@print_examen_laboratorio_lns');

Route::get('/modal_historial_atenciones/{dni_beneficiario}', 'RecetaValeController@modal_historial_atenciones');
Route::get('/modal_historial_atenciones_1/{dni_beneficiario}', 'RecetaValeController@modal_historial_atenciones_1');
Route::get('/modal_historial_atenciones_2/{dni_beneficiario}', 'RecetaValeController@modal_historial_atenciones_2');
Route::get('/modal_historial_atenciones_3/{dni_beneficiario}', 'RecetaValeController@modal_historial_atenciones_3');
Route::get('/modal_historial_evaluaciones_emergencia/{id_asegurado}/{id_tipo_atencion}', 'RecetaValeController@modal_historial_evaluaciones_emergencia');
Route::get('/modal_historial_evaluaciones_emergencia_v2/{nro_doc_ident}/{id_tipo_atencion}', 'RecetaValeController@modal_historial_evaluaciones_emergencia_v2');
Route::get('/modal_historial_laboratorio/{dni_beneficiario}', 'LaboratorioController@modal_historial_laboratorio');
Route::get('/modal_historial_laboratorio_nivel_1_2/{dni_beneficiario}', 'LaboratorioController@modal_historial_laboratorio_nivel_1_2');
Route::get('/modal_historial_laboratorio2/{dni_beneficiario}', 'LaboratorioController@modal_historial_laboratorio2');
Route::get('/modal_historial_laboratorio_paciente/{dni_beneficiario}', 'LaboratorioController@modal_historial_laboratorio_paciente');
Route::get('/modal_historial_radiologia/{dni_beneficiario}', 'RadiologiaController@modal_historial_radiologico');
Route::get('/modal_historial_interconsulta/{dni_beneficiario}', 'InterconsultasController@modal_historial_interconsulta');
Route::get('/modal_historial_interconsulta_enf/{dni_beneficiario}', 'InterconsultasController@modal_historial_interconsulta_enf');
Route::get('/modal_historial_enfermeria/{id_asegurado}', 'EnfermeriaController@modal_historial_enfermeria');
Route::get('/modal_historial_enfermeria_v2/{dni_beneficiario}', 'EnfermeriaController@modal_historial_enfermeria_v2');
Route::get('/modal_historial_enf_bh_v2/{dni_beneficiario}/{tipo_atencion}', 'EnfermeriaController@modal_historial_enf_bh_v2');
Route::get('/modal_historial_enf_sv_v2/{dni_beneficiario}/{tipo_atencion}', 'EnfermeriaController@modal_historial_enf_sv_v2');
Route::get('/modal_historial_enf_moni_v2/{dni_beneficiario}/{tipo_atencion}', 'EnfermeriaController@modal_historial_enf_moni_v2');
Route::get('/modal_historial_enf_kardex_v2/{dni_beneficiario}/{tipo_atencion}', 'EnfermeriaController@modal_historial_enf_kardex_v2');
Route::get('/modal_historial_enf_evo_v2/{dni_beneficiario}/{tipo_atencion}', 'EnfermeriaController@modal_historial_enf_evo_v2');

Route::post('listar_procedimientos_ajax', [
  'uses' => 'ProcedimientoController@listar_procedimientos_ajax',
  'as' => 'listar_procedimientos_ajax'
]);

Route::get('/refirma/getArguments', 'RefirmaController@getArguments')->name('getArguments');
Route::post('/refirma/postArguments', 'RefirmaController@postArguments')->name('postArguments');
Route::post('/refirma/postArgumentsImagenologia', 'RefirmaController@postArgumentsImagenologia')->name('postArgumentsImagenologia');
Route::post('/refirma/postArgumentsHojaHis', 'RefirmaController@postArgumentsHojaHis')->name('postArgumentsHojaHis');
Route::post('/refirma/postArgumentsEmergenciaAlta', 'RefirmaController@postArgumentsEmergenciaAlta')->name('postArgumentsEmergenciaAlta');
// Route::post('/refirma/upload', 'RefirmaController@upload');
Route::post('/refirma/upload', 'RefirmaController@uploadAsync');
Route::post('/refirma/actualiza_prestacion', 'ExcelController@actualizar_prestacion')->name('actualiza_prestacion');
Route::post('/refirma/actualiza_monitoreo', 'ExcelController@actualizar_monitoreo')->name('actualiza_monitoreo');
Route::post('/refirma/actualizar_prestacion_modificada', 'ExcelController@actualizar_prestacion_modificada')->name('actualizar_prestacion_modificada');
Route::post('/refirma/registra_firma_admision_emergencia', 'ExcelController@registra_firma_admision_emergencia')->name('registra_firma_admision_emergencia');
Route::post('/refirma/registra_firma_egreso', 'ExcelController@registra_firma_egreso')->name('registra_firma_egreso');
Route::post('/refirma/actualiza_evolucion', 'ExcelController@actualizar_evolucion')->name('actualiza_evolucion');
Route::post('/refirma/actualiza_evaluacion', 'ExcelController@actualizar_evaluacion')->name('actualiza_evaluacion');
Route::post('/refirma/actualiza_hoja_his', 'ExcelController@actualiza_hoja_his')->name('actualiza_hoja_his');
Route::post('/refirma/registrar_firma_kardex_enfermeria', 'ExcelController@registrar_firma_kardex_enfermeria')->name('registrar_firma_kardex_enfermeria');
Route::post('registrar_examen_radiologia', [
  'uses' => 'RadiologiaController@registrar_examen_radiologia',
  'as' => 'registrar_examen_radiologia'
]);
Route::post('/refirma/postArgumentsKardexEnfermeria', 'RefirmaController@postArgumentsKardexEnfermeria')->name('postArgumentsKardexEnfermeria');
Route::post('/refirma/postArgumentsConsejeriaHospitalizacion', 'RefirmaController@postArgumentsConsejeriaHospitalizacion')->name('postArgumentsConsejeriaHospitalizacion');
Route::post('/refirma/postArgumentsConsejeriaEmergencia', 'RefirmaController@postArgumentsConsejeriaEmergencia')->name('postArgumentsConsejeriaEmergencia');
Route::post('/refirma/postArgumentsNotaEnfermeria', 'RefirmaController@postArgumentsNotaEnfermeria')->name('postArgumentsNotaEnfermeria');
Route::post('/refirma/postArgumentsConsultaExternaEnfermeria', 'RefirmaController@postArgumentsConsultaExternaEnfermeria')->name('postArgumentsConsultaExternaEnfermeria');

Route::post('/refirma/postArgumentsOrdenTrasladoSolicitar', 'RefirmaController@postArgumentsOrdenTrasladoSolicitar')->name('postArgumentsOrdenTrasladoSolicitar');
Route::post('/refirma/postArgumentsOrdenTrasladoRecibir', 'RefirmaController@postArgumentsOrdenTrasladoRecibir')->name('postArgumentsOrdenTrasladoRecibir');

Route::get('/all_radiologia_atendidas_by_ipress/{iduser?}', 'RadiologiaController@all_radiologia_atendidas_by_ipress');
Route::post('listar_examenes_radiologicos_atendidos', [
  'uses' => 'RadiologiaController@listar_examenes_radiologicos_atendidos',
  'as' => 'listar_examenes_radiologicos_atendidos'
]);

Route::get('/listar_examenes_radiologicos_atendidos', 'RadiologiaController@listar_examenes_radiologicos_atendidos');
Route::get('/print_examen_radiologia/{id_radiologia}', 'PdfController@print_examen_radiologia');

Route::get('/obtener_medico_pnp/{id}/{id_establecimiento}', 'RecetaValeController@obtener_medico_pnp');

Route::get('/obtener_reniec/{dni}', 'MedicoController@obtener_reniec');

//------------TRIAJE--------------------
Route::get('/all_triaje_topico', 'TriajeController@index');
Route::get('/deshabilitar_triaje_topico/{id}', 'TriajeController@deshabilitar');
Route::get('/habilitar_triaje_topico/{id}', 'TriajeController@habilitar');
Route::get('/edit_triaje_topico/{id}', 'TriajeController@edit');
Route::get('/create_prioridad_triaje', 'TriajeController@create');

Route::post('update_triaje_topico', [
  'uses' => 'TriajeController@update',
  'as' => 'update_triaje_topico'
]);

Route::post('store_triaje_topico', [
  'uses' => 'TriajeController@store',
  'as' => 'store_triaje_topico'
]);

//------------TRIAJE-EMERGENCIA-------------------
Route::get('/all_emergencia_triaje', 'EmergenciaController@triaje');
Route::get('/all_emergencia_triaje_ref/{tipo}/{dni}', 'EmergenciaController@triaje_ref');
Route::get('/all_emergencia_triaje/{id_emergencia}', 'EmergenciaController@triaje');
Route::get('/get_topico_por_id_prioridad/{id_prioridad}', 'EmergenciaController@get_topico_por_id_prioridad');
Route::get('/get_topico_por_id_prioridad_sexo_edad/{id_prioridad}/{sexo}/{edad}', 'EmergenciaController@get_topico_por_id_prioridad_sexo_edad');
Route::get('/get_tipo_violencia/', 'EmergenciaController@get_tipo_violencia');
Route::get('/buscar_beneficiario/{tipodoc_beneficiario}/{dni_beneficiario}', 'EmergenciaController@buscar_beneficiario');

Route::post('store_emergencia_triaje', [
  'uses' => 'EmergenciaController@registrar_emergencia_triaje',
  'as' => 'store_emergencia_triaje'
]);
Route::post('store_emergencia_triaje_ref', [
  'uses' => 'EmergenciaController@registrar_emergencia_triaje_ref',
  'as' => 'store_emergencia_triaje_ref'
]);

Route::get('/all_triajes_atendidas_by_emergencia/{id_user?}', 'EmergenciaController@all_triajes_atendidas_by_emergencia')->middleware('menu.permission:125');

Route::post('listar_triaje_emergencia_ajax', [
  'uses' => 'EmergenciaController@listar_triaje_emergencia_ajax',
  'as' => 'listar_triaje_emergencia_ajax'
]);

Route::post('listar_atencion_emergencia_ajax', [
  'uses' => 'EmergenciaController@listar_atencion_emergencia_ajax',
  'as' => 'listar_atencion_emergencia_ajax'
]);
Route::post('listar_evolucion_enfermeria_ajax', [
  'uses' => 'EvolucionController@listar_evolucion_enfermeria_ajax',
  'as' => 'listar_evolucion_enfermeria_ajax'
]);

Route::get('/desactivar_emergencia_triaje/{id}/{estado}', 'EmergenciaController@desactivar');

Route::get('/cerrar_emergencia_triaje/{id}', 'EmergenciaController@cerrar_emergencia_triaje');

Route::get('/exportar_reporte_emergencia_triaje/{idUser}/{fecha_ini}/{fecha_fin}/{estado_admision}', 'ExcelController@exportar_reporte_emergencia_triaje')->middleware('check.server.resources');

Route::get('/modal_paciente_emergencia/{id}', 'EmergenciaController@modal_paciente_emergencia');
//----------------------Admision----------------------
Route::get('/atender_admision/{id}/{estado}', 'EmergenciaController@atender_admision');
Route::post('registrar_admision_emergencia', [
  'uses' => 'EmergenciaController@registrar_admision_emergencia_transaction',
  'as' => 'registrar_admision_emergencia'
]);
Route::get('/nueva_admision_emergencia', 'EmergenciaController@nueva_admision');

Route::get('/all_admisiones_atendidas_by_emergencia/{id_user?}', 'EmergenciaController@all_admisiones_atendidas_by_emergencia')->middleware('menu.permission:127');
Route::get('/ver_atencion_emergencia/{id_emergencia}', 'EmergenciaController@ver_atencion_emergencia');

Route::post('listar_admision_emergencia_ajax', [
  'uses' => 'EmergenciaController@listar_admision_emergencia_ajax',
  'as' => 'listar_admision_emergencia_ajax'
]);

Route::get('/listar_cita_ajax2/{fecha_ini}/{fecha_fin}/{fecha_reg_ini}/{fecha_reg_fin}/{dni_beneficiario}/{nombre_beneficiario}/{id_servicio}/{id_consultorio}/{id_medico}/{estado}/{NumeroPagina}/{NumeroRegistros}', 'CitaController@listar_cita_ajax2');

//ASEGURADOS
Route::get('/all_asegurados/{id_user?}', 'PacienteController@index');
Route::get('/buscar_paciente', 'PacienteController@buscar_paciente');

Route::get('buscar_persona', [
  'uses' => 'PacienteController@buscar_persona',
  'as' => 'buscar_persona'
]);
Route::get('buscar_paciente_ipress', [
  'uses' => 'PacienteController@buscar_paciente_ipress',
  'as' => 'buscar_paciente_ipress'
]);

Route::post('buscar_paciente_dni', [
  'uses' => 'PacienteController@buscar_paciente_dni',
  'as' => 'buscar_paciente_dni'
]);
Route::get('/buscar_paciente_dni', 'PacienteController@buscar_paciente_dni');
Route::get('/editar_paciente/{id}', 'PacienteController@editar_paciente');
Route::get('/editar_paciente2/{id}', 'PacienteController@editar_paciente2');

Route::post('update_paciente', [
  'uses' => 'PacienteController@update_paciente2',
  'as' => 'update_paciente'
]);

Route::post('listar_pacientes_ajax', [
  'uses' => 'PacienteController@listar_pacientes_ajax',
  'as' => 'listar_pacientes_ajax'
]);

Route::post('registrar_paciente',[
  'uses' => 'PacienteController@registrar_paciente',
  'as' => 'registrar_paciente'
]);

Route::post('/validar-documento', 'PacienteController@validarDocumento');

//ALTAS MEDICAS
Route::get('/alta_medica/{id_user}/{id_prestacion}', 'MedicoController@alta_medica');
Route::get('/listar_consultorios_ajax/{id_tipo_servicio}', 'MedicoController@listar_consultorios_ajax'); 
Route::get('/listar_consultorios_hospitalizacion_desocupados/{id_ipress}', 'MedicoController@listar_consultorios_hospitalizacion_desocupados'); 
Route::get('/listar_consultorios/{id_ipress}/{id_tipo_atencion}', 'MedicoController@listar_consultorios'); 
Route::post('registrar_alta_medica', [
  'uses' => 'MedicoController@registrar_alta_medica',
  'as' => 'registrar_alta_medica'
]);
Route::get('/pdf_indicaciones/{id_user}/{id_prestacion}', 'PdfController@mostrar_indicaciones');
Route::get('/pdf_epicrisis_emer/{id_user}/{id_prestacion}', 'PdfController@mostrar_epicrisis_emer');
Route::get('/pdf_ref_contra_emer/{id_user}/{id_prestacion}', 'PdfController@mostrar_ref_contra_emer');
Route::get('/pdf_alta_medica/{id_user}/{id_prestacion}', 'PdfController@mostrar_alta_medica');
Route::get('/alta_medica_ver/{id_user}/{id_prestacion}', 'MedicoController@alta_medica_ver');
Route::get('/anular_alta_medica_ver/{id_user}/{id_emergencia}', 'MedicoController@anular_alta_medica_ver');
Route::get('/ver_alta_medica_anulada/{id_user}/{id_emergencia}', 'MedicoController@ver_alta_medica_anulada');
Route::get('/all_atenciones_by_emergencia/{id_user?}', 'EmergenciaController@all_atenciones_by_emergencia');
Route::get('/alta_medica_hospitalizado/{id_user}/{id_prestacion}', 'MedicoController@alta_medica_hospitalizado');
Route::post('registrar_alta_medica_hospitalizado', [
    'uses' => 'MedicoController@registrar_alta_medica_hospitalizado',
    'as' => 'registrar_alta_medica_hospitalizado'
  ]);
  Route::post('registrar_alta_medica_emer', [
    'uses' => 'MedicoController@registrar_alta_medica_emer',
    'as' => 'registrar_alta_medica_emer'
  ]);

//ALTAS ADMINISTRATIVAS
Route::get('/alta_administrativa_ver/{id_user}/{id_prestacion}', 'MedicoController@alta_administrativa_ver');
Route::get('/alta_medica_administrativa/{id_user}/{id_prestacion}', 'MedicoController@alta_medica_administrativa');
Route::get('/all_altas_medica_by_emergencia/{id_user?}', 'EmergenciaController@all_altas_medica_by_emergencia');
Route::post('registrar_alta_administrativa', [
  'uses' => 'MedicoController@registrar_alta_administrativa',
  'as' => 'registrar_alta_administrativa'
]);
Route::post('registrar_alta_adminisa', [
  'uses' => 'MedicoController@registrar_alta_adminisa',
  'as' => 'registrar_alta_adminisa'
]);

Route::get('/desactivar_paciente/{id}/{estado}', 'PacienteController@desactivar');
Route::post('/bloquear_atencion_paciente/{id}/{flag}', 'PacienteController@bloquear_atencion');

Route::get('/all_triajes_atendidos', 'EmergenciaController@all_triajes_atendidos')->middleware('menu.permission:126');
Route::get('/all_reporte_emergencia', 'EmergenciaController@all_reporte_emergencia');
Route::post('listar_triaje_emergencia_atendidos', [
  'uses' => 'EmergenciaController@listar_triaje_emergencia_atendidos',
  'as' => 'listar_triaje_emergencia_atendidos'
]); 

Route::get('/edit_emergencia_triaje/{id}', 'EmergenciaController@edit_emergencia_triaje');
Route::post('update_emergencia_triaje', [
  'uses' => 'EmergenciaController@update_emergencia_triaje',
  'as' => 'update_emergencia_triaje'
]);
//Route::get('/anular_emergencia_admision/{id}/{motivo_anulacion}', 'EmergenciaController@anular_emergencia_admision');
//Route::get('/anular_emergencia_triaje/{id}/{motivo_anulacion}', 'EmergenciaController@anular_emergencia_triaje');
//Route::get('/anular_emergencia_ticket/{id}/{motivo_anulacion}', 'EmergenciaController@anular_emergencia_ticket');
Route::post('/anular_emergencia_admision', 'EmergenciaController@anular_emergencia_admision');
Route::post('/anular_emergencia_triaje', 'EmergenciaController@anular_emergencia_triaje');
Route::post('/anular_emergencia_ticket', 'EmergenciaController@anular_emergencia_ticket');

Route::get('/buscar_persona_ajax/{tipo_doc}/{nro_doc}', 'PacienteController@buscar_persona_ajax');

//----------------------Atenciones Emergencia----------------------
Route::get('/formulario_atencion_emergencia/{id_emergencia}', 'EmergenciaController@formulario_atencion_emergencia');
Route::get('/mostrar_atencion/{id}', 'RecetaValeController@mostrar_atencion');
Route::get('/mostrar_atencion_1/{id}', 'RecetaValeController@mostrar_atencion_1');
Route::get('/mostrar_atencion_2/{id}', 'RecetaValeController@mostrar_atencion_2');
Route::get('/mostrar_atencion_3/{id}', 'RecetaValeController@mostrar_atencion_3');
Route::get('paciente_antecedentes/{dni}/{id_tipo_atencion}/{id_cita}', 'PacienteController@paciente_antecedentes');
Route::post('update_paciente_antecedentes', [
  'uses' => 'PacienteController@update_paciente_antecedentes',
  'as' => 'update_paciente_antecedentes'
]);
//----------------------------INTERCONSULTAS-----------------------------
Route::get('/all_interconsultas/{id_tipo_atencion?}', 'InterconsultasController@all_interconsultas');
Route::get('/atender_interconsulta/{id_pre}/{id_interconsulta}/{id_evaluacion}', 'InterconsultasController@atender_interconsulta');
Route::post('listar_interconsulta_ajax', [
  'uses' => 'InterconsultasController@listar_interconsulta_ajax',
  'as' => 'listar_interconsulta_ajax'
]);

Route::get('/ver_atencion_interconsultas/{id_prestacion}/{id_interconsulta}/{tipo_atencion}/{id_evaluacion}/{id_establecimiento}', 'InterconsultasController@ver_atencion_interconsultas');
Route::get('/obtener_consultorio_interconsulta/{buscar}/{id_ipress}', 'ConsultorioController@obtener_consultorio_interconsulta');
Route::get('/pdf_interconsulta/{id_prestacion}/{id}/{id_evaluacion}', 'PdfController@print_interconsulta');

Route::post('registrar_interconsulta_rpta', [
  'uses' => 'InterconsultasController@registrar_interconsulta_rpta_transaction',
  'as' => 'registrar_interconsulta_rpta'
]);
Route::get('/ver_atencion_interconsulta_rptas/{id_prestacion}/{id}/{id_evaluacion}/{id_tipo_atencion}', 'InterconsultasController@ver_atencion_interconsulta_rptas');
Route::get('/mostrar_interconsulta_envio/{id}/{id_prestacion}/{id_evaluacion}', 'InterconsultasController@mostrar_interconsulta');
Route::get('/mostrar_interconsulta_rpta/{id}/{id_prestacion}/{id_evaluacion}', 'InterconsultasController@mostrar_interconsulta_rpta');

//----------------------------REFERENCIAS-----------------------------
Route::get('/all_referencias/{id_user?}', 'ReferenciasController@all_referencias')->middleware('menu.permission:102');
Route::post('listar_referencia_ajax', [
  'uses' => 'ReferenciasController@listar_referencia_ajax',
  'as' => 'listar_referencia_ajax'
]);
Route::get('/ver_atencion_referencia/{id_prestacion}/{id}', 'ReferenciasController@ver_atencion_referencia');
Route::get('/listar_referencia_ajax2', 'ReferenciasController@listar_referencia_ajax2');
Route::get('/pdf_referencia/{id_prestacion}/{id}', 'PdfController@print_referencia');
Route::get('/ver_formato_ref/{id_referencia}', 'PdfController@ver_formato_ref');
//Route::get('formulario_referencia', 'ReferenciasController@formulario_referencia');

Route::get('/form_solicitud/{id_referido}', 'ReferenciasController@form_solicitud');
Route::get('/get_ipress/{id}', 'ReferenciasController@get_ipress');
//Route::get('/pdf_reporte_referencia', 'ReferenciasController@pdf_reporte_referencia'); //link del formato de futps
Route::get('/pdf_formato_fups_emergencia', 'ReferenciasController@pdf_formato_fups_emergencia'); //ormato de futps emergencia
Route::get('/pdf_formato_fups_consultaexterna', 'ReferenciasController@pdf_formato_fups_consultaexterna'); //formato de futps consulta externa
Route::get('/pdf_formato_fups_hospitalizacion', 'ReferenciasController@pdf_formato_fups_hospitalizacion'); //ormato de futps emergencia
Route::get('/pdf_formato_contrarreferencia', 'ReferenciasController@pdf_formato_contrarreferencia');// formato de contrarreferencia
Route::get('/get_datosasegurado/{id_aseg}/{id_ipress}', 'ReferenciasController@get_datosasegurado');
Route::post('registrar_solicitud', [
  'uses' => 'ReferenciasController@registrar_solicitud',
  'as' => 'registrar_solicitud'
]);
Route::post('validar_referencia', ['uses' => 'ReferenciasController@validar_referencia','as' => 'validar_referencia']);
Route::get('/modal_historial_rc/{id_asegurado}', 'ReferenciasController@modal_historial_rc');
Route::get('/exportar_reporte_referencia/{fecha_inicio}/{fecha_fin}/{dni}/{paciente}/{estado}/{establecimiento_actual}/{buscar_servicio}/{buscar_servicio_destino}', 'ExcelController@exportar_reporte_referencia');

//----------------------TELEREFERENCIA----------------
Route::get('/all_tele_referencia/{id_user?}', 'ReferenciasController@all_tele_referencia')->middleware('menu.permission:102');
Route::post('listar_telemedicina_ajax', [
  'uses' => 'ReferenciasController@listar_telemedicina_ajax',
  'as' => 'listar_telemedicina_ajax'
]);

//WS
// Route::get('/ws_reniec/{nro_doc}', 'WebServiceController@Reniec');
Route::get('/validate_entity/{nro_doc}', 'WebServiceController@Reniec');
Route::get('/ws_dirrehum/{tipo_doc}/{nro_doc}', 'WebServiceController@Dirrehum');
Route::get('/ws_dirrehum_foto/{cip}', 'WebServiceController@DirrehumFoto');
Route::get('/ws_dirrehum_maspe/{cip}', 'WebServiceController@DirrehumMaspe');
Route::get('/ws_saludpol_titular/{tipo_doc}/{nro_doc}/{fec_val?}', 'WebServiceController@SaludpolBuscarTitularFamiliar');
//Route::get('/ws_saludpol_medico/{nro_doc}', 'WebServiceController@SaludpolBuscarMedico');

Route::get('/obtener_consultorio_tipo_atencion/{id_ipress}/{id_tipo_atencion}', 'ConsultorioController@obtener_consultorio_tipo_atencion');

//--------------------------------- Evaluacion Emergencia ------------------------------------------------------//
Route::get('/all_pacientes_evaluaciones_by_emergencia/{id_user?}', 'EvaluacionController@all_pacientes_evaluaciones_by_emergencia');
Route::get('/formulario_evaluacion_emergencia/{id_emergencia}', 'EvaluacionController@formulario_evaluacion_emergencia');
Route::get('/modal_historial_evaluaciones/{id_emergencia}/{estado}/{id_tipo_atencion}', 'EvaluacionController@modal_historial_evaluaciones');
Route::get('/modal_historial_evaluaciones_atenciones/{id_emergencia}/{id_tipo_atencion}', 'EvaluacionController@modal_historial_evaluaciones_atenciones');
Route::get('/modal_historial_indicaciones_terapeuticas/{id_hospitalizacion}', 'EvolucionHospitalizacionController@modal_historial_indicaciones_terapeuticas');
Route::get('/historial_atenciones_evaluacion/{id}/{estado}', 'EvaluacionController@historial_atenciones_evaluacion');
Route::get('/ver_examenreceta/{id_emergencia}', 'EvaluacionController@asignarExamenRecetaEvaluacion');
Route::post('/exportar_reporte_evaluacion_detallado', 'ExcelController@exportar_reporte_evaluacion_detallado')->middleware('check.server.resources');
Route::post('registrar_evaluacion_emergencia', [
  'uses' => 'EvaluacionController@registrar_evaluacion_transaction_emergencia',
  'as' => 'registrar_evaluacion_emergencia'
]);

Route::post('registrar_examene_complementarios', [
  'uses' => 'EvaluacionController@registrarExamenComplementarios',
  'as' => 'registrar_examene_complementarios'
]);

Route::get('/mostrar_evaluacion/{id}/{id_tipo_atencion}', 'EvaluacionController@mostrar_evaluacion');
Route::get('/mostrar_evaluacion_emergencia/{id}/{id_tipo_atencion}', 'EvaluacionController@mostrar_evaluacion_emergencia');

Route::get('/ver_evaluacion/{iduser}/{id_evolucion}', 'EvaluacionController@ver_evaluacion');

Route::get('/ver_atenciones_emergencia/{id}/{estado}', 'EmergenciaController@ver_atenciones_emergencia');
Route::get('/historial_atenciones_emergencia/{id}/{estado}', 'EmergenciaController@historial_atenciones_emergencia');
Route::get('/modal_historial_atenciones_emergencia/{id}/{estado}', 'EmergenciaController@modal_historial_atenciones_emergencia');
Route::get('/modal_historial_triaje_emergencia/{dni_beneficiario}', 'EmergenciaController@modal_historial_triaje_emergencia');
Route::get('/ver_atenciones_evaluacion/{id}/{estado}/{id_tipo_atencion}', 'EvaluacionController@ver_atenciones_evaluacion');
Route::get('/mostrar_atencion_emergencia/{id}', 'EmergenciaController@mostrar_atencion_emergencia');

Route::post('registrar_evaluacion_adicional', [
  'uses' => 'EvaluacionController@registrar_evaluacion_adicional',
  'as' => 'registrar_evaluacion_adicional'
]);

Route::post('listar_evaluacion_ajax', [
  'uses' => 'EvaluacionController@listar_evaluacion_ajax',
  'as' => 'listar_evaluacion_ajax'
]);
Route::get('/emergencia/evaluacion/edit/{id_user}/{id_evaluacion}/{id_emergencia?}', 'EvaluacionController@edit_evaluacion');
Route::post('emergencia_evaluacion_store', [
  'uses' => 'EvaluacionController@emergencia_evaluacion_store',
  'as' => 'emergencia_evaluacion_store'
]);
Route::post('listar_evaluacion_by_prestacion_ajax', [
  'uses' => 'EvaluacionController@listar_evaluacion_by_prestacion_ajax',
  'as' => 'listar_evaluacion_by_prestacion_ajax'
]);

Route::post('listar_evaluacion_emergencia_ajax', [
  'uses' => 'EvaluacionController@listar_evaluacion_emergencia_ajax',
  'as' => 'listar_evaluacion_emergencia_ajax'
]);

Route::post('/exportar_reporte_evaluacion2/', 'ExcelController@exportar_reporte_evaluacion2')->middleware('check.server.resources');
Route::get('/anular_evaluacion/{id_evaluacion}', 'EvaluacionController@anular_evaluacion');
Route::get('/formulario_atencion_laboratorio_lns/{id_lab}/{id_tipo_atencion}', 'LaboratorioController@formulario_atencion_laboratorio_lns');
Route::get('/formulario_atencion_laboratorio_nivel2/{id_lab}/{id_tipo_atencion}', 'LaboratorioController@formulario_atencion_laboratorio_nivel2');
Route::post('registrar_examen_laboratorio_lns', [
  'uses' => 'LaboratorioController@registrar_examen_laboratorio_lns',
  'as' => 'registrar_examen_laboratorio_lns'
]);

Route::post('registrar_examen_laboratorio_lns_agrupado', [
  'uses' => 'LaboratorioController@registrar_examen_laboratorio_lns_agrupado',
  'as' => 'registrar_examen_laboratorio_lns_agrupado'
]);

Route::post('/api/nivel-2/laboratorio/atencion', 'LaboratorioController@registrar_examen_laboratorio_nivel2');
Route::post('/api/nivel-2/laboratorio/{id_laboratorio}', 'LaboratorioController@editOrdenLabNivel1y2');
Route::get('/api/laboratorio/historial/asegurado/{id_asegurado}', 'LaboratorioController@getHistorialAtencionesByIdAsegurado');
Route::get('/api/laboratorio/{id_laboratorio}/prestacion/{id_prestacion}/examenes', 'LaboratorioController@getExamenesByIdLaboratorio');

Route::get('/formulario_edicion_atencion_laboratorio_lns/{id_lab}/{id_tipo_atencion}', 'LaboratorioController@formulario_edicion_atencion_laboratorio_lns');
Route::post('editar_examen_laboratorio_lns', [
  'uses' => 'LaboratorioController@editar_examen_laboratorio_lns',
  'as' => 'editar_examen_laboratorio_lns'
]);

Route::post('editar_examen_laboratorio_lns_agrupado', [
  'uses' => 'LaboratorioController@editar_examen_laboratorio_lns_agrupado',
  'as' => 'editar_examen_laboratorio_lns_agrupado'
]);

//Route::get('/buscar_persona_ajax/{tipo_doc}/{nro_doc}', 'PacienteController@buscar_persona_ajax');

Route::get('obtener_datos_persona_ajax', [
  'uses' => 'PacienteController@obtener_datos_persona_ajax',
  'as' => 'obtener_datos_persona_ajax'
]);
Route::get('obtener_datos_persona_ajax2', [
  'uses' => 'PacienteController@obtener_datos_persona_ajax2',
  'as' => 'obtener_datos_persona_ajax2'
]);

Route::get('obtener_datos_persona_ajax_local', [
  'uses' => 'PacienteController@obtener_datos_persona_ajax_local',
  'as' => 'obtener_datos_persona_ajax_local'
]);

Route::get('obtener_datos_paciente_ajax', [
  'uses' => 'PacienteController@obtener_datos_paciente_ajax',
  'as' => 'obtener_datos_paciente_ajax'
]);

Route::get('/ver_datos_admision/{id}/{estado}', 'EmergenciaController@ver_admision');
Route::get('/pdf_alta_hospitalizacion/{id}', 'ExcelController@pdf_alta_hospitalizacion');
Route::get('/all_pendientes_hospitalizacion/{id_user?}', 'HospitalizacionController@all_pendientes_hospitalizacion')->middleware('menu.permission:142');
Route::post('listar_emergencia_a_hospitalizar_ajax', [
  'uses' => 'HospitalizacionController@listar_emergencia_a_hospitalizar_ajax',
  'as' => 'listar_emergencia_a_hospitalizar_ajax'
]);


Route::get('/hospitalazacionValidarAtendidaForDni', 'HospitalizacionController@hospitalazacionValidarAtendidaForDni');
Route::post('/hospitalazacionValidarAtendida', 'HospitalizacionController@hospitalazacionValidarAtendida');

Route::get('/all_hospitalizado_by_establecimiento/{iduser?}', 'HospitalizacionController@all_hospitalizado_by_establecimiento')->middleware('menu.permission:143');

Route::get('/admision_hospitalizacion/{id_user?}/{id_emergencia?}', 'HospitalizacionController@admision_hospitalizacion');

Route::post('registrar_hospitalizaciones', [
  'uses' => 'HospitalizacionController@registrar_hospitalizaciones',
  'as' => 'registrar_hospitalizaciones'
]);


Route::get('obtener_gestaciones_by_dni_paciente', [
  'uses' => 'HospitalizacionController@obtener_gestaciones_by_dni_paciente',
  'as' => 'obtener_gestaciones_by_dni_paciente'
]);

//epicrisis
Route::get('/modal_diagnostico_paciente/{id_prestacion}', 'DiagnosticoController@modal_diagnostico_paciente');
Route::get('/modal_procedimiento_paciente/{id_prestacion}', 'ProcedimientoController@modal_procedimiento_paciente');
Route::get('/modal_laboratorio_paciente/{id_prestacion}', 'LaboratorioController@modal_laboratorio_paciente');
Route::get('/modal_radiologia_paciente/{id_prestacion}', 'RadiologiaController@modal_radiologia_paciente');
Route::get('/modal_interconsulta_paciente/{id_prestacion}', 'InterconsultasController@modal_interconsulta_paciente');
Route::get('/modal_referencia_paciente/{id_prestacion}', 'ReferenciasController@modal_referencia_paciente');
Route::get('/modal_productos_paciente/{id_prestacion}', 'RecetaValeController@modal_productos_paciente');
Route::get('/modal_atencion_paciente/{id_ext}', 'EvaluacionController@modal_atencion_paciente');
Route::get('/modal_evoluciones_paciente/{id_ext}', 'EvaluacionController@modal_evoluciones_paciente');

//atencion hospitalizados
Route::get('/hospitalizado_by_atender/{iduser?}', 'HospitalizacionController@hospitalizado_by_atender');
Route::get('/form_atenciones_hospitalizacion/{id_hospitalizacion}/{id_servicio_encargado?}', 'HospitalizacionController@form_atenciones_hospitalizacion');
Route::get('/form_atenciones_hospitalizacion/{id_hospitalizacion}', 'HospitalizacionController@form_atenciones_hospitalizacion');
Route::get('/hospitalizado_by_evolucion/', 'HospitalizacionController@hospitalizado_by_evolucion');
Route::get('/formulario_evolucion_hospitalizacion/{id_hospitalizacion}', 'EvaluacionController@formulario_evolucion_hospitalizacion');
Route::get('/modal_historial_atenciones_hospitalizacion/{id}/{estado}', 'HospitalizacionController@modal_historial_atenciones_hospitalizacion');
Route::get('/nueva_admision_hospitalizacion', 'HospitalizacionController@nueva_admision_hospitalizacion');
Route::get('/modal_historial_atenciones_hospitalizacion/{id}/{estado}', 'HospitalizacionController@modal_historial_atenciones_hospitalizacion');
//atencion enfermeria y evolucion
Route::get('/hospitalizado_by_enfermeria/{iduser?}', 'HospitalizacionController@hospitalizado_by_enfermeria');
Route::get('/formulario_nota_ingreso/{id_hospitalizacion}', 'HospitalizacionController@formulario_nota_ingreso');
//Route::post('registrar_nota_ingreso_hospitalizado', [
//  'uses' => 'HospitalizacionController@registrar_nota_ingreso_hospitalizado',
//  'as' => 'registrar_nota_ingreso_hospitalizado'
//]);
Route::get('/ver_nota_ingreso/{iduser}/{id_nota_ingreso}', 'HospitalizacionController@ver_nota_ingreso');
Route::get('/all_evolucion_enfermeria', 'EvaluacionController@all_evolucion_enfermeria');
Route::get('/formulario_evolucion_enfermeria/{id_hospitalizacion}', 'EvaluacionController@formulario_evolucion_enfermeria_hospitalizacion');
Route::post('registrar_evolucion_enfermeria', [
  'uses' => 'EvaluacionController@registrar_evolucion_enfermeria',
  'as' => 'registrar_evolucion_enfermeria'
]);
Route::get('/ver_evolucion_imprimir/{id_evolucion}', 'ExcelController@ver_evolucion_imprimir2');
Route::get('/ver_evolucion_enfermeria/{iduser}/{id_evolucion}', 'EvaluacionController@ver_evolucion_enfermeria');
Route::get('/ver_atenciones_evolucion_enfermeria/{id}/{estado}/{id_tipo_atencion}', 'EvaluacionController@ver_atenciones_evolucion_enfermeria');
Route::get('/modal_historial_evoluciones_enfermeria/{id_paciente}/{estado}/{id_tipo_atencion}', 'EvaluacionController@modal_historial_evoluciones_enfermeria');
Route::get('/historial_atenciones_evolucion/{id}/{estado}', 'EvaluacionController@historial_atenciones_evolucion');
Route::get('/ver_evolucion_enfermeria/{id_evolucion}', 'PdfController@print_evolucion_enfermeria');

//rotacion cama
Route::get('/rotacion_cama_hospitalizado/{id_user}/{nro_hospitalizacion}', 'HospitalizacionController@rotacion_cama_hospitalizado');
Route::get('/all_historial/{id_user}/{nro_hospitalizacion}', 'CamasHistorialController@all_historial');
Route::post('rotacion_cama', [
  'uses' => 'HospitalizacionController@rotacion_cama',
  'as' => 'rotacion_cama'
]);
Route::get('/modal_historial_cama/{id_hospitalizado}', 'HospitalizacionController@modal_historial_cama');
Route::get('/modal_historial_cama_historial/{id_hospitalizado}', 'HospitalizacionController@modal_historial_cama_historial');
Route::get('/modifica_admision_hospitalizacion/{id_user?}/{nro_hospitalizacion?}', 'HospitalizacionController@modifica_admision_hospitalizacion');

Route::get('Buscar_persona_triaje_ajax', [
  'uses' => 'EmergenciaController@Buscar_persona_triaje_ajax',
  'as' => 'Buscar_persona_triaje_ajax'
]);

//monitoreo
Route::group(['prefix' => 'api'], function() {
  Route::group(['prefix' => 'intervenciones-infusiones'], function() {
    Route::get('/listado', 'EnfermeriaController@buscar_infusiones_monitoreo_enfermeria');
  });
});

Route::get('buscar_dia_estancia_ajax', [
  'uses' => 'EmergenciaController@buscar_dia_estancia_ajax',
  'as' => 'buscar_dia_estancia_ajax'
]);
//gneracion de pdf para firma digital
Route::post('genera_csv_imagenologia', [
  'uses' => 'ExcelController@genera_zip_imagenologia',
  'as' => 'genera_csv_imagenologia'
]);
Route::post('genera_interconsulta_rpta_csv', [
  'uses' => 'ExcelController@genera_zip_interconsulta_rpta',
  'as' => 'genera_interconsulta_rpta_csv'
]);
Route::post('genera_emer_triaje_csv', [
  'uses' => 'ExcelController@genera_zip_triaje_emergencia',
  'as' => 'genera_emer_triaje_csv'
]);
Route::post('genera_csv_evaluacion', [
  'uses' => 'ExcelController@genera_zip_evaluacion',
  'as' => 'genera_csv_evaluacion'
]);
Route::post('genera_csv_hoja_his', [
  'uses' => 'ExcelController@genera_hoja_his_pdf',
  'as' => 'genera_csv_hoja_his'
]);
Route::post('genera_alta_emergencia_csv', [
  'uses' => 'ExcelController@genera_zip_alta_emergencia',
  'as' => 'genera_alta_emergencia_csv'
]);
Route::post('genera_alta_hospitalizacion_csv', [
  'uses' => 'ExcelController@genera_zip_alta_hospitalizacion',
  'as' => 'genera_alta_hospitalizacion_csv'
]);

Route::post('genera_csv_admision_emergencia', [
  'uses' => 'ExcelController@genera_zip_admision_emergencia',
  'as' => 'genera_csv_admision_emergencia'
]);

Route::post('genera_csv_egreso', [
  'uses' => 'ExcelController@genera_zip_egreso',
  'as' => 'genera_csv_egreso'
]);

Route::post('genera_csv_evolucion', [
  'uses' => 'ExcelController@genera_zip_evolucion',
  'as' => 'genera_csv_evolucion'
]);

Route::get('/obtener_estadoingreso_por_formaingreso/{id_forma_ingreso}', 'EstadoIngresoController@obtener_estadoingreso_por_formaingreso');
Route::get('/print_hoja_his/{id_atencion}/{id_establecimiento}/{fecha_atencion}/{id_medico}/{id_servicio}', 'PdfController@print_hoja_his');
Route::get('/print_hoja_his_evaluacion/{id_atencion}/{id_establecimiento}/{fecha_atencion}/{id_medico}/{id_servicio}', 'PdfController@print_hoja_his_evaluacion');
Route::get('/buscar_medico_ipress/{dni_medico}', 'MedicoController@buscar_medico_ipress');
Route::get('/all_reporte_hoja_his', 'MedicoController@all_reporte_hoja_his');
Route::get('/exportar_reporte_hoja_his/{id_atencion}/{id_medico}/{id_servicio}/{fecha_atencion}', 'ExcelController@exportar_reporte_hoja_his');

Route::post('print_hoja_his_evaluacion', [
  'uses' => 'PdfController@print_hoja_his_evaluacion',
  'as' => 'print_hoja_his_evaluacion'
]);

Route::post('print_hoja_his', [
  'uses' => 'PdfController@print_hoja_his',
  'as' => 'print_hoja_his'
]);

Route::post('ver_hoja_his', [
  'uses' => 'MedicoController@ver_hoja_his',
  'as' => 'ver_hoja_his'
]);

//FEMA
Route::get('/consulta_titular', 'FemaController@index')->middleware('menu.permission:103');

Route::get('/radiologia_atencion_vista/{id_radiologia}/{id_servicio_radiologia}/{id_pp}/{id_cita?}', 'RadiologiaController@radiologia_atencion_vista');

Route::get('/radiologia_informe/{id_radiologia}/{id_servicio_radiologia}/{id_pp}/{id_cita?}', 'RadiologiaController@radiologia_informe');
Route::post('registrar_radiologia_informe', [
  'uses' => 'RadiologiaController@registrar_radiologia_informe',
  'as' => 'registrar_radiologia_informe'
]);
Route::post('registrar_observacion_examen_imagenes', [
  'uses' => 'RadiologiaController@registrar_observacion_examen_imagenes',
  'as' => 'registrar_observacion_examen_imagenes'
]);
//Route::get('/radiologia_informe_validacion/{id_radiologia}/{id_servicio_radiologia}/{id_pp}', 'RadiologiaController@radiologia_informe_validacion');
Route::post('modificar_validar_radiologia_informe', [
  'uses' => 'RadiologiaController@modificar_validar_radiologia_informe',
  'as' => 'modificar_validar_radiologia_informe'
]);

Route::get('/radiologia_informe_validacion/{id_radiologia}/{id_servicio_radiologia}/{id_pp}', 
    'RadiologiaController@radiologia_informe_validacion')
    ->name('radiologia_informe_validacion');

Route::get('/print_examen_radiologia_lns/{id_radiologia}/{id_servicio_radiologia}/{idpp}', 'PdfController@print_examen_radiologia_lns');
Route::get('/ver_informe_para_firmar_imagenologia_lns/{id_radiologia}/{id_servicio_radiologia}/{id_pp}', 'RadiologiaController@radiologia_firmar_informe');
Route::post('validar_radiologia_informe', [
  'uses' => 'RadiologiaController@validar_radiologia_informe',
  'as' => 'validar_radiologia_informe'
]);
//RUA
Route::get('/ver_plantilla_rua/{nivel}/{tipo_examen}/{id_consultorio?}', 'PdfController@print_plantilla_rua');

Route::get('/titular_busqueda/{tipo_busqueda}/{valor}', 'PacienteController@titular_busqueda');
// Route::post('/titular_actualizar', 'PacienteController@titular_actualizar');

Route::get('/consulta_carga_familiar/{tipo_busqueda}/{id_asegurado_titular}', 'PacienteController@consulta_carga_familiar');
Route::get('/modal_carga_familiar/{tipo_busqueda}/{id_asegurado_titular}', 'PacienteController@modal_carga_familiar');
Route::get('/listar_camas_ocupadas_por_cuarto/{id_cuarto}', 'CamaController@listar_camas_ocupadas_por_cuarto');
Route::get('/activar_cuarto/{id}', 'CuartoController@activar');
//TV
Route::get('/tv_llamar/{id_cita}', 'TvController@llamar');
Route::get('/tv_posponer_llamado/{id_cita}', 'TvController@posponer_llamado');
Route::get('/tv/{id_tv}/{id_area}', 'TvController@tv');
Route::get('/tv_llamado_iterativo/{id_tv}/{id_area}', 'TvController@tv_llamado_iterativo');
Route::get('/tv_all', 'TvController@index');
Route::post('tv_listar', [
  'uses' => 'TvController@tv_listar',
  'as' => 'tv_listar'
]);
Route::get('/tv_area_all', 'TvController@area_all');
Route::post('tv_listar_area', [
  'uses' => 'TvController@tv_listar_area',
  'as' => 'tv_listar_area'
]);
Route::get('/tv_cambiar_estado_llamada/{id_cita}/{estado}/{tipo}', 'TvController@cambiar_estado_llamada');
Route::get('/tv_edit/{id_tv}', 'TvController@edit');
Route::get('/tv_create', 'TvController@create');
Route::get('/get_consultorio_por_establecimiento/{id_ipress}', 'ConsultorioController@get_consultorio_por_establecimiento');
Route::post('registrar_tv', [
  'uses' => 'TvController@store',
  'as' => 'registrar_tv'
]);
Route::get('/tv_actualizar_estado/{id_tv}/{estado}', 'TvController@actualizar_estado');
Route::get('/tv_create_area', 'TvController@create_area');
Route::get('/get_tv_por_establecimiento/{id_ipress}', 'TvController@get_tv_por_establecimiento');
Route::get('/get_subconsultorio_por_televisor/{id_tv}', 'TvController@get_subconsultorio_por_televisor');
Route::post('registrar_tv_area', [
  'uses' => 'TvController@store_area',
  'as' => 'registrar_tv_area'
]);
Route::get('/tv_area_edit/{id_tv_area}', 'TvController@edit_area');
Route::get('/tv_area_actualizar_estado/{id_tv_area}/{estado}', 'TvController@actualizar_estado_tv_area');
Route::get('/gestion_cola/{ip?}', 'TvController@tv_sin_auth');
//Totem
Route::get('/totem/{ip?}', 'TotemController@totem_sin_auth');
// Route::get('/totem_citas/{parent}', 'TotemController@totem_citas');
Route::post('totem_validar_documento', [
  'uses' => 'TotemController@validar_documento',
  'as' => 'totem_validar_documento'
]);
// // Route::post('buscar_cita_dia', [
// //   'uses' => 'TotemController@buscar_cita_dia',
// //   'as' => 'buscar_cita_dia'
// // ]);
Route::post('indicar_presencia_cita', [
  'uses' => 'TotemController@indicar_presencia_cita',
  'as' => 'indicar_presencia_cita'
]);

Route::get('/modal_proxima_cita/{tipodoc_beneficiario}/{dni_beneficiario}', 'CitaController@modal_proxima_cita');
Route::get('/modal_proxima_cita_servicio/{tipodoc_beneficiario}/{dni_beneficiario}/{servicio}/{id_establecimiento}', 'CitaController@modal_proxima_cita_servicio');

Route::get('/obtener_subconsultorios_servicio_establecimiento_medico/{id_establecimiento}/{id_medico}/{id_fecha_atencion}/{id_consultorio}', 'MedicoController@obtener_subconsultorios_servicio_establecimiento_medico');
Route::get('/hc_electronica/{id_asegurado}', 'RecetaValeController@hc_electronica');
Route::get('/mostrar_atencion_completa/{id}', 'RecetaValeController@mostrar_atencion_completa');
Route::get('/mostrar_hc_hospitalizacion_evolucion_form/{id_atencion}/{id_tipo_atencion}', 'RecetaValeController@mostrar_hc_evolucion_form');
Route::get('/mostrar_hc_emergencia_evaluacion_form/{id}/{id_tipo_atencion}', 'RecetaValeController@mostrar_hc_evaluacion_form');
Route::get('/busqueda_hc_electronica', 'RecetaValeController@busqueda_hc_electronica')->middleware('menu.permission:107');
Route::get('/mostrar_atencion_hospitalizacion_hc/{id}', 'RecetaValeController@mostrar_atencion_hospitalizacion');
Route::post('listar_paciente_ajax', [
  'uses' => 'PacienteController@listar_paciente_ajax',
  'as' => 'listar_paciente_ajax'
]);
Route::get('/print_atencion/{id_prestacion}', 'ExcelController@print_atencion');
Route::get('/print_atencion_completa/{id_asegurado}', 'ExcelController@print_atencion_completa');
//Movimiento de HC
Route::get('/all_movimiento_hc/{iduser?}', 'PacienteController@all_movimiento_hc');
Route::get('/add_hc_registro_salida/{id_user}', 'PacienteController@add_hc_registro_salida');
Route::post('registrar_salida_hc', [
  'uses' => 'PacienteController@registrar_salida_hc',
  'as' => 'registrar_salida_hc'
]);
Route::get('/registrar_movimiento_hc/{id_nro_mov}/{id_origen}/{fecha_ini}/{fecha_fin}', 'PacienteController@registrar_movimiento_hc');
Route::get('/editar_movimiento_hc/{id_nro_mov}', 'PacienteController@editar_movimiento_hc');
Route::get('/listar_citas_medico_paciente_prueba/{iduser?}', 'PacienteController@all_citas_medico_paciente_prueba');
Route::get('/obtener_hc_dni/{buscar}/{id_establecimiento}/{id_consultorio}', 'PacienteController@obtener_hc_dni');
Route::post('agregar_historias_clinicas', [
  'uses' => 'PacienteController@agregar_historias_clinicas',
  'as' => 'agregar_historias_clinicas'
]);
Route::post('agregar_historias_clinicas2', [
  'uses' => 'PacienteController@agregar_historias_clinicas2',
  'as' => 'agregar_historias_clinicas2'
]);
Route::post('registrar_paciente_historia', [
  'uses' => 'PacienteController@registrar_paciente_historia',
  'as' => 'registrar_paciente_historia'
]);
Route::get('/eliminar_hc_servicio/{id}/{id_mov}/{fecha_ini}/{fecha_fin}', 'PacienteController@eliminar_hc_servicio');
Route::get('/cerrar_envio_hc_servicio/{id}/{id_estado}', 'PacienteController@cerrar_envio_hc_servicio');
Route::get('/imprimir_historias_servicios/{id}', 'PdfController@imprimir_historias_servicios');
Route::get('/all_historias_recibidas/{id_farmacia?}', 'PacienteController@all_historias_recibidas');
Route::get('/ver_movimiento_hc/{id_nro_mov}/{id_origen}', 'PacienteController@ver_movimiento_hc');
Route::get('/eliminar_movimiento_hc/{id}', 'PacienteController@eliminar_movimiento_hc');
Route::post('listar_cita_historia', [
  'uses' => 'CitaController@listar_cita_historia_ajax',
  'as' => 'listar_cita_historia'
]);
Route::post('recepcionar_hc_servicio', [
  'uses' => 'PacienteController@recepcionar_hc_servicio',
  'as' => 'recepcionar_hc_servicio'
]);
Route::post('busca_movimiento_hc', [
  'uses' => 'PacienteController@busca_movimiento_hc',
  'as' => 'busca_movimiento_hc'
]);
Route::post('busca_hc_recibidas', [
  'uses' => 'PacienteController@busca_hc_recibidas',
  'as' => 'busca_hc_recibidas'
]);
Route::get('/buscar_movimiento_historia/', 'PacienteController@buscar_movimiento_historia');
Route::get('/nueva_historia/', 'PacienteController@nueva_historia');
Route::post('listar_historia_consultorio', [
  'uses' => 'PacienteController@listar_historia_consultorio',
  'as' => 'listar_historia_consultorio'
]);
Route::post('buscar_historias_movimientos', [
  'uses' => 'PacienteController@buscar_historias_movimientos',
  'as' => 'buscar_historias_movimientos'
]);
Route::get('/mostrar_receta_cita/{id_cita}', 'RecetaValeController@mostrar_receta_cita');
Route::get('/buscar_cita_dia', 'TotemController@buscar_cita_dia');
Route::get('/totem_menu_principal', 'TotemController@menu_principal');
Route::get('/menu_otros_tramites/{parent}', 'TotemController@menu_otros_tramites');
Route::get('/generar_ticket/{id_totem_opcion}/{id_cita?}', 'TotemController@generar_ticket');
Route::get('/modulo_llamada_tv', 'TvController@modulo_llamada_tv');
Route::get('/tv_llamar_ticket/{id_ticket}', 'TvController@llamar_ticket');
Route::get('/posponer_llamado_ticket/{id_ticket}', 'TvController@posponer_llamado_ticket');
Route::post('busca_ticket_totem', [
  'uses' => 'TvController@busca_ticket_totem',
  'as' => 'busca_ticket_totem'
]);
Route::get('/totem_all', 'TotemController@index');
Route::get('/totem_create', 'TotemController@create');
Route::post('registrar_totem', [
  'uses' => 'TotemController@store',
  'as' => 'registrar_totem'
]);
Route::get('/totem_edit/{id_totem}', 'TotemController@edit');
Route::get('/totem_actualizar_estado/{id_totem}/{estado}', 'TotemController@actualizar_estado');
Route::get('/tv_llamar_ticket_triaje/{id_cita}', 'TvController@llamar_ticket_triaje');
Route::get('/get_opciones_totem', 'TotemController@get_opciones_totem');
//Route::get('/totem_configuracion/{id_dispositivo}', 'TotemController@totem_configuracion');
/*Route::post('store_opciones_totem', [
  'uses' => 'TotemController@store_opciones_totem',
  'as' => 'store_opciones_totem'
]);*/
Route::post('ficha_psicologica', [
  'uses' => 'RecetaValeController@psicologico_envio',
  'as' => 'psicologico_envio'
]);

Route::get('/new_trama_saludpol/{iduser?}', 'RecetaValeController@new_trama_saludpol');
Route::get('/listar_trama_saludpol/{iduser?}', 'RecetaValeController@new_trama_saludpol');

Route::post('invalidar_examen_laboratorio', [
  'uses' => 'LaboratorioController@invalidar_examen_laboratorio',
  'as' => 'invalidar_examen_laboratorio'
]);

Route::post('invalidar_examen_imagenologia', [
  'uses' => 'RadiologiaController@invalidar_examen_imagenologia',
  'as' => 'invalidar_examen_imagenologia'
]);

Route::post('editar_examen_niv_cat_rad', [
  'uses' => 'RadiologiaController@editar_examen_niv_cat_rad',
  'as' => 'editar_examen_niv_cat_rad'
]);


Route::get('/all_plantilla_radiologias', 'RadiologiaController@index_plantilla');
Route::get('/create_plantilla_radiologia', 'RadiologiaController@create_plantilla');
Route::post('store_plantilla_radiologia', [
  'uses' => 'RadiologiaController@store_plantilla',
  'as' => 'store_plantilla_radiologia'
]);
Route::get('/edit_tipo_plantilla/{id}', 'RadiologiaController@edit_plantilla');
Route::post('update_tipo_plantilla', [
  'uses' => 'RadiologiaController@update_plantilla',
  'as' => 'update_tipo_plantilla'
]);

Route::get('/eliminar_tipo_plantilla/{id}/{estado}', 'RadiologiaController@eliminar_plantilla');
Route::get('/cargarplantilla/{cmbest}', 'RadiologiaController@getcargarplantilla');
Route::post('/refirma/registra_firma_informe_imagenologia', 'ExcelController@registra_firma_informe_imagenologia')->name('registra_firma_informe_imagenologia');

Route::get('/confirmar_atencion_ticket/{id_ticket}', 'TvController@confirmar_atencion_ticket');
Route::get('/ver_hoja_his_firmada/{id}', 'Medico@ver_hoja_his_firmada');

//Antecedentes
Route::get('/obtener_antecedentes_by_id_beneficiario/{dni_beneficiario}', 'RecetaValeController@obtener_antecedentes_by_id_beneficiario');

//Button exportar
Route::get('/exportar_reporte_atencion_estados/{fecha_inicio}/{fecha_fin}/{idconsultorio}/{id_farmacia}/{id_estado}', 'ExcelController@exportar_reporte_atencion_estados')->name('exportar_reporte_atencion_estados');

//Ir a formulario reporte atenciones y estados
Route::get('/all_atencion_estados_by_servicio/{iduser?}', 'CitaController@all_atencion_estados_by_servicio');

//Obtener lista reporte atenciones y estados
Route::post('listar_reporte_atenciones_estados', [
  'uses' => 'CitaController@listar_reporte_atenciones_estados',
  'as' => 'listar_reporte_atenciones_estados'
]);

//==================================================
Route::get('/editar_subconsultorio_dia/{id_subconsultorio_turno?}/{dia?}/{turno_selected?}/{hora_ini?}/{hora_fin?}/{tiem_atencion?}/{idservicio?}/{id_sub_consultorio?}/{fecha_inicio_turno?}/{fecha_fin_turno?}','ConsultorioController@editar_subconsultorio_dia')->name('editar_subconsultorio_dia');

//Actualizar Turno
Route::post('update_subconsultorios_turnos', [
  'uses' => 'ConsultorioController@update_subconsultorios_turnos',
  'as' => 'update_subconsultorios_turnos'
]);

Route::post('editar/diagnostico/', 'RecetaValeController@editDiagnostico')->name('edit.receta.diagnositco');
Route::post('editar/procedimiento/', 'RecetaValeController@editProcedimiento')->name('edit.receta.procedimiento');

Route::get('/add_consultorio_turnos_dia/{id_sub_consultorio}/{id_servicio}', 'ConsultorioController@add_consultorio_turnos_dia');

Route::post('agregar_consultorio_turno_dia', [
  'uses' => 'ConsultorioController@agregar_consultorio_turno_dia',
  'as' => 'agregar_consultorio_turno_dia'
]);

Route::get('/edit_subconsultorios_servicio_2/{id_sub_consultorio}', 'ConsultorioController@edit_consultorios_servicio_2');
Route::get('/desactivar_consultorio_turno_con_dia/{id}/{estado}', 'ConsultorioController@desactivar_consultorio_turno_con_dia');
Route::post('id_tabla_psicologia', [
  'uses' => 'PsicologicoController@id_tabla_psicologia',
  'as' => 'id_tabla_psicologia'
]);
/*
Route::post('ficha_fema_envio', [
  'uses' => 'RecetaValeController@ficha_fema_envio',
  'as' => 'ficha_fema_envio'
]);

Route::get('/fema_triaje', 'RecetaValeController@fema_triaje');

Route::post('buscar_dni_fema', [
  'uses' => 'RecetaValeController@buscar_dni_fema',
  'as' => 'buscar_dni_fema'
]);

Route::post('ficha_fema_triaje', [
  'uses' => 'RecetaValeController@ficha_fema_triaje',
  'as' => 'ficha_fema_triaje'
]);
*/
Route::post('ficha_fema_envio', [
  'uses' => 'FemaController@ficha_fema_envio',
  'as' => 'ficha_fema_envio'
]);

Route::get('/fema_triaje', 'FemaController@fema_triaje');

Route::post('buscar_dni_fema', [
  'uses' => 'FemaController@buscar_dni_fema',
  'as' => 'buscar_dni_fema'
]);

Route::post('ficha_fema_triaje', [
  'uses' => 'FemaController@ficha_fema_triaje',
  'as' => 'ficha_fema_triaje'
]);

Route::get('/busqueda_fema_ficha', 'FemaController@busqueda_fema_ficha');
Route::post('busca_fema_ficha', [
  'uses' => 'FemaController@busca_fema_ficha',
  'as' => 'busca_fema_ficha'
]);
Route::get('/fema_atencion/{id_ficha}/{id_proceso}/{id_tipo_examen}', 'FemaController@fema_atencion');
Route::get('/fema_atencion_anular/{id_ficha}/{id_proceso}/{id_tipo_examen}', 'FemaController@fema_atencion_anular');
Route::get('/anular_fema_ficha/{id_ficha}/{id_proceso}/{id_tipo_examen}', 'FemaController@anular_fema_ficha');
Route::post('guardarFichaFema', [
  'uses' => 'FemaController@guardarFichaFema',
  'as' => 'guardarFichaFema'
]);
Route::get('/ver_fema_ficha/{id_ficha}/{id_proceso}/{id_tipo_examen}', 'FemaController@verFichaFema');
Route::post('anularFichaFema', [
  'uses' => 'FemaController@anularFichaFema',
  'as' => 'anularFichaFema'
]);
Route::post('anularfichacompletafema', [
  'uses' => 'FemaController@anularfichacompletafema',
  'as' => 'anularfichacompletafema'
]);
Route::post('anularficha_triaje_ft', [
  'uses' => 'FemaController@anularficha_triaje_ft',
  'as' => 'anularficha_triaje_ft'
]);
Route::get('/anular_fema_ficha/{id_ficha}/{id_proceso}/{id_tipo_examen}', 'FemaController@anularfemaficha');
Route::get('/anular_triaje/{id_ficha}/{id_proceso}', 'FemaController@anular_triaje');
Route::get('/anular_ficha_completa/{id_ficha}/{id_proceso}', 'FemaController@anular_ficha_completa');
Route::get('/anularfichacompleta/{id_ficha}/{id_proceso}', 'FemaController@anularfichacompleta');
Route::get('/anularficha_triaje/{id_ficha}/{id_proceso}', 'FemaController@anularficha_triaje');

Route::get('/formulario_cita_por_medico/{id_prestacion}','RecetaValeController@formulario_cita_por_medico');
Route::get('/modal_proxima_cita_medico/{tipodoc_beneficiario}/{dni_beneficiario}', 'CitaController@modal_proxima_cita_medico');
Route::get('/obtener_consultorio_cita_especifico/{id_establecimiento}/{id_consultorio}', 'CitaController@obtener_consultorio_cita_especifico');
Route::get('/ver_cita_por_medico/{id_user}/{id_cita}', 'RecetaValeController@ver_cita_por_medico');
Route::get('/modal_cita_horario_lista_cita_medico/{id_ipress}/{id_consultorio}/{fecha_atencion}/{documento}', 'RecetaValeController@modal_cita_horario_lista_cita_medico');

Route::get('/apertura_fema', 'FemaController@aperturaFicha');

Route::get('/obtener_prestacion/{id_prestacion}','RecetaValeController@obtener_prestacion');
Route::post('prueba', [
  'uses' => 'CitaController@pruebas',
  'as' => 'prueba'
]);
Route::post('Posponerllamado', [
  'uses' => 'CitaController@finalCola',
  'as' => 'Posponerllamado'
]);

Route::post('obtenerdatosDiagnosticos', [
  'uses' => 'RecetaValeController@obtenerdatosdiagnosticos',
  'as' => 'obtenerdatosDiagnosticos'
]);
Route::post('obtenerdatosDiagnosticos2', [
  'uses' => 'RecetaValeController@obtenerdatosdiagnosticos2',
  'as' => 'obtenerdatosDiagnosticos2'
]);
Route::post('desaparecervalortv', [
  'uses' => 'CitaController@Cancelar',
  'as' => 'desaparecervalortv'
]);

Route::get('/modulo_triaje','TriajeController@triaje_nuevo_totem');
Route::post('listar_cita_ajaxTriaje', [
  'uses' => 'TriajeController@listar_cita_ajaxTriaje',
  'as' => 'listar_cita_ajaxTriaje'
]);
Route::get('/listar_cita_ajax/{fecha_ini}/{fecha_fin}/{fecha_reg_ini}/{fecha_reg_fin}/{dni_beneficiario}/{nombre_beneficiario}/{id_servicio}/{id_consultorio}/{id_medico}/{estado}/{NumeroPagina}/{NumeroRegistros}', 'CitaController@listar_cita_ajax');
Route::get('/listar_cita_ajaxTriaje/{fecha_ini}/{fecha_fin}/{fecha_reg_ini}/{fecha_reg_fin}/{dni_beneficiario}/{nombre_beneficiario}/{id_servicio}/{id_consultorio}/{id_medico}/{estado}/{NumeroPagina}/{NumeroRegistros}', 'CitaController@listar_cita_ajaxTriaje');
Route::get('/ver_fema_ficha/{id_ficha}/{id_proceso}/{id_tipo_examen}', 'FemaController@verFichaFema');
Route::get('/modal_ficha_triaje/{id_cita}', 'RecetaValeController@modal_ficha_triaje');
Route::post('triaje_usuario', [
  'uses' => 'TriajeController@triaje_usuario',
  'as' => 'triaje_usuario'
]);

Route::get('/epidemiologia/{f}', 'FemaController@epidemiologia');

Route::get('/validar_fema_sub/{nro_doc}','FemaController@validar_fema_sub');

Route::post('derivacion_epidemiologia', [
  'uses' => 'FemaController@derivacion_epidemiologia',
  'as' => 'derivacion_epidemiologia'
]);

Route::get('/cierre_ficha', 'FemaController@cierre_ficha');

Route::get('/busqueda_fema_cierre', 'FemaController@busqueda_fema_cierre');
Route::post('busca_fema_cierre', [
  'uses' => 'FemaController@busca_fema_cierre',
  'as' => 'busca_fema_cierre'
]);

Route::get('/verderivacion/{f}', 'FemaController@verderivacion');

Route::get('/mostrarpdffema1/{f}', 'PdfController@mostrarpdffema1');

Route::get('/cierre/{f}', 'FemaController@cierre');

Route::get('/levantarobservacion/{f}', 'FemaController@levantarobservacion');

Route::post('levantar_observacion', [
  'uses' => 'FemaController@levantar_observacion',
  'as' => 'levantar_observacion'
]);

Route::get('/obtenerPaciente/{id_ticket}/{ventanilla}','TvController@obtenerPaciente');
Route::get('/busca_ticket_totem2/{fechaDesde}/{fechaHasta}/{estado}','TvController@busca_ticket_totem2');
Route::get('/listado_tickets','TvController@listado_tickets');
Route::get('/obtenerVentanilla/{id_servicio}','TvController@obtenerVentanillaPorServicio');

Route::post('triajeActualizacion', [
  'uses' => 'TriajeController@triajeActualizacion',
  'as' => 'triajeActualizacion'
]);

Route::post('PosponerllamadoTriaje', [
  'uses' => 'TriajeController@finalColaTriaje',
  'as' => 'PosponerllamadoTriaje'
]);
Route::post('desaparecervalortvTriaje', [
  'uses' => 'TriajeController@CancelarTriaje',
  'as' => 'desaparecervalortvTriaje'
]);

Route::get('/buscafichaabierta/{valor}','FemaController@buscafichaabierta');
//Route::get('/exportar_reporte_fema_cierre/{fecha_inicio}/{fecha_fin}/{dni}', 'ExcelController@exportar_reporte_fema_cierre');
Route::post('/exportar_reporte_fema_cierre', 'ExcelController@exportar_reporte_fema_cierre')->middleware('check.server.resources');
Route::post('final_fema', [
  'uses' => 'FemaController@final_fema',
  'as' => 'final_fema'
]);

Route::get('/buscafemageneral/{valor}','FemaController@buscafemageneral');
Route::get('fema/laboratorio/{dni}','PdfController@mostrarpdflaboratoriofema');
Route::get('cierre/verpdfcierre/{dni}','PdfController@mostrarpdffema1');
Route::get('cierre/verpdfconsolidado/{dni}','PdfController@mostrarpdfconsolidado');
Route::get('verderivacion/verpdfderivado/{dni}','PdfController@mostrarpdfderivado');
Route::get('/corredores', 'CorredoreController@index');
Route::get('/corredores/desactivar/{id}', 'CorredoreController@desactivar');
Route::get('/corredores/activar/{id}', 'CorredoreController@activar');
Route::get('/corredores/create', 'CorredoreController@create');
Route::post('registrar_corredor', [
  'uses' => 'CorredoreController@store',
  'as' => 'registrar_corredor'
]);
Route::get('/corredores/edit/{id}', 'CorredoreController@edit');
Route::post('editar_corredor', [
  'uses' => 'CorredoreController@store',
  'as' => 'editar_corredor'
]);
Route::get('/get_corredores_por_establecimiento/{id_ipress}', 'CorredoreController@get_corredores_por_establecimiento');

Route::get('/editar_estado_triaje/{id_corredor}', 'CorredorController@editar_estado_triaje');
Route::get('/modal_ficha_triajecontroller/{id_cita}', 'TriajeController@modal_ficha_triajecontroller');

Route::get('obtenerPacienteLlamadoUs/{id_user}','TvController@obtenerPacienteLlamadoUs');
Route::get('obtenerPacienteSeleccionadoUs/{id_user}','TvController@obtenerPacienteSeleccionadoUs');
Route::get('modal_ventanillas','TvController@modal_ventanillas');
Route::get('obtenerCorredores/{id_corredor}','TvController@obtenerCorredores');
Route::get('obtenerVentanillaPorCorredor/{id_corredor_detalle}','TvController@obtenerVentanillaPorCorredor');
Route::get('/obtenerPaciente2/{id_ticket}/{ventanilla}','TvController@obtenerPaciente2');
// WS Saludpol JSON
Route::get('/ws_saludpol/{tipo_doc}/{nro_doc}/{fec_val?}', 'WebServiceController@Saludpol');
Route::get('/ws_saludpol/{id_tipo_doc_ident}/{nro_doc_ident}/{fec_val?}', 'WebServiceController@Saludpol_paciente');
Route::get('/fema_preatencion/{id_ficha}/{id_proceso}/{id_tipo_examen}','FemaController@fema_preatencion');
Route::post('guardarFichaFemapre', [
  'uses' => 'FemaController@guardarFichaFemapre',
  'as' => 'guardarFichaFemapre'
]);

Route::get('/exportar_reporte_fema/{fecha_inicio}/{fecha_fin}/{dni}/{opcion}', 'ExcelController@exportar_reporte_fema')->middleware('check.server.resources');

Route::get('/all_aperturas_fema/{iduser?}', 'FemaController@all_aperturas_fema');
Route::post('busca_apertura_fema', [
  'uses' => 'FemaController@busca_apertura_fema',
  'as' => 'busca_apertura_fema'
]);

Route::get('/all_triajes_fema/{iduser?}', 'FemaController@all_triajes_fema');
Route::post('busca_triaje_fema', [
  'uses' => 'FemaController@busca_triaje_fema',
  'as' => 'busca_triaje_fema'
]);

Route::get('/all_epidemiologias_fema/{iduser?}', 'FemaController@all_epidemiologias_fema');
Route::post('busca_epidemiologia_fema', [
  'uses' => 'FemaController@busca_epidemiologia_fema',
  'as' => 'busca_epidemiologia_fema'
]);

Route::get('/obtener_diagnostico_genero_fema/{buscar}/{genero}/{examen}', 'FemaController@obtener_diagnostico_genero_fema');
Route::get('/reporte_laboratorio_fema/{iduser?}', 'FemaController@all_reporte_laboratorio_fema');
//Route::get('/exportar_reporte_apertura/{fecha_inicio}/{fecha_fin}/{dni}', 'ExcelController@exportar_reporte_apertura');
Route::post('/exportar_reporte_apertura', 'ExcelController@exportar_reporte_apertura')->middleware('check.server.resources');
//Route::get('/exportar_reporte_fema_triaje/{fecha_inicio}/{fecha_fin}/{dni}', 'ExcelController@exportar_reporte_fema_triaje');
Route::post('/exportar_reporte_fema_triaje', 'ExcelController@exportar_reporte_fema_triaje')->middleware('check.server.resources');
//Route::get('/exportar_reporte_fema_epidemiologia/{fecha_inicio}/{fecha_fin}/{dni}', 'ExcelController@exportar_reporte_fema_epidemiologia');
Route::post('/exportar_reporte_fema_epidemiologia', 'ExcelController@exportar_reporte_fema_epidemiologia')->middleware('check.server.resources');
Route::post('/exportar_reporte_fema_labo_1', 'ExcelController@exportar_reporte_fema_labo_1');
Route::post('/exportar_reporte_fema_labo_2', 'ExcelController@exportar_reporte_fema_labo_2');

Route::post('registrar_prestacionDiagnosticos', [
  'uses' => 'RecetaValeController@registrarRecetaDiagnosticos',
  'as' => 'registrar_prestacionDiagnosticos'
]);

Route::post('eliminardiagnosticos', [
  'uses' => 'RecetaValeController@eliminardiagnosticos',
  'as' => 'eliminardiagnosticos'
]);

Route::post('updatediagnosticos', [
  'uses' => 'RecetaValeController@updatediagnosticos',
  'as' => 'updatediagnosticos'
]);

Route::post('updateprocedimientos', [
  'uses' => 'RecetaValeController@updateprocedimientos',
  'as' => 'updateprocedimientos'
]);

Route::post('updaterecetas', [
  'uses' => 'RecetaValeController@updaterecetas',
  'as' => 'updaterecetas'
]);


Route::post('obtenernombrediagnostico', [
  'uses' => 'RecetaValeController@obtenernombrediagnostico',
  'as' => 'obtenernombrediagnostico'
]);
Route::post('obtenermedicamentos', [
  'uses' => 'RecetaValeController@obtenermedicamentos',
  'as' => 'obtenermedicamentos'
]);


Route::post('obtenerdiagnosticonombreCodigo', [
  'uses' => 'RecetaValeController@obtenerdiagnosticonombreCodigo',
  'as' => 'obtenerdiagnosticonombreCodigo'
]);

Route::post('obtener_diagnostico_genero_ver_xd', [
  'uses' => 'RecetaValeController@obtenerdiagnosticosgeneronuevo',
  'as' => 'obtener_diagnostico_genero_ver_xd'
]);

Route::post('guardar_nuevos_diagnosticos', [
  'uses' => 'RecetaValeController@guardar_nuevos_diagnosticos',
  'as' => 'guardar_nuevos_diagnosticos'
]);

Route::post('eliminarprocedimientos', [
  'uses' => 'RecetaValeController@eliminarprocedimientosupdate',
  'as' => 'eliminarprocedimientos'
]);

Route::post('obtener_procedimiento_convenio_ver_prestacion', [
  'uses' => 'RecetaValeController@obtener_procedimiento_convenio_ver_prestacion',
  'as' => 'obtener_procedimiento_convenio_ver_prestacion'
]);

Route::post('guardar_nuevos_procedimientos_x_diagnosticos', [
  'uses' => 'RecetaValeController@guardar_nuevos_procedimientos_x_diagnosticos',
  'as' => 'guardar_nuevos_procedimientos_x_diagnosticos'
]);

Route::post('registrar_receta_prestacionnuevo', [
  'uses' => 'RecetaValeController@registrar_receta_prestacionnuevo',
  'as' => 'registrar_receta_prestacionnuevo'
]);

//==================================Emergencia=======================================//
Route::get('/exportar_emergencia_triaje/{idUser}/{fecha_ini}/{fecha_fin}/{dni_beneficiario}/{nombre_beneficiario}/{estado_triaje?}', 'ExcelController@exportar_emergencia_triaje')->middleware('check.server.resources');
Route::get('/exportar_emergencia_atenciones_repo/{idUser}/{fecha_ini}/{fecha_fin}/{dni_beneficiario}/{nombre_beneficiario}/{estado_triaje?}/{ubicacion?}/{id_prioridad?}/{id_topico?}/{id_motivo_ingreso?}/{id_tipo_movimiento?}/{edad_desde?}/{edad_hasta?}/{tipo_consulta?}', 'ExcelController@exportar_emergencia_atenciones_repo');

Route::post('listar_emergencia_atenciones_repo', [
  'uses' => 'EmergenciaController@listar_emergencia_atenciones_repo',
  'as' => 'listar_emergencia_atenciones_repo'
]);
Route::get('/ver_datos_triaje/{id}', 'EmergenciaController@ver_emergencia_triaje');
Route::get('/ver_datos_triaje_modal/{id}', 'EmergenciaController@ver_emergencia_triaje_modal');
Route::get('/ver_modal_emergencia_triaje/{id}', 'EmergenciaController@ver_modal_emergencia_triaje');
Route::get('/validar_dato_siteds_emergencia/{codigo_siteds}', 'EmergenciaController@validar_dato_siteds_emergencia'); 
Route::get('/ver_atenciones_asegurado/{dni_beneficiario}', 'EmergenciaController@modal_alerta_atenciones');
Route::get('/validar_pendientes_alta/{dni_beneficiario}', 'EmergenciaController@validar_pendientes_alta');
Route::get('/ver_prestacion_emergencias/{id_user}/{id_prestacion}', 'EmergenciaController@ver_prestacion_emergencias');
Route::post('serviciosxMedicos', [
  'uses' => 'EmergenciaController@serviciosxMedicos',
  'as' => 'serviciosxMedicos'
]);

Route::post('/refirma/postArgumentsEmergenciaTriaje', 'RefirmaController@postArgumentsEmergenciaTriaje')->name('postArgumentsEmergenciaTriaje');

Route::get('/firma_emergencia_triaje','ExcelController@firma_emergencia_triaje');
Route::get('/PdfEmergenciaTriaje/{id}','PdfController@mostrarPdfEmergenciaTriaje');
Route::get('/PdfEmergenciaAdmision/{id}','PdfController@mostrarPdfEmergenciaAdmision');

Route::post('medicosxServicio', [
  'uses' => 'EmergenciaController@medicosxServicio',
  'as' => 'medicosxServicio'
]);

Route::post('turnosmedico', [
  'uses' => 'EmergenciaController@turnosmedico',
  'as' => 'turnosmedico'
]);

Route::post('registrar_prestacion_emergencia', [
  'uses' => 'EmergenciaController@registrar_prestacion_transaction_emergencia',
  'as' => 'registrar_prestacion_emergencia'
]);

//====================================================================================
Route::get('/obtener_destinos_emergencia', 'DestinoController@obtener_destinos_emergencia');
Route::get('/get_servicios_administrativos_by_establecimiento/{id_establecimiento}', 'ConsultorioController@get_servicios_administrativos_by_establecimiento');
Route::get('/obtener_condiciones_emergencia', 'DestinoController@obtener_condiciones_emergencia');

Route::get('/obtener_departamento_by_unidad_policial/{id_unidad_policial}', 'EstablecimientoController@obtener_departamento_by_unidad_policial');
Route::get('/obtener_ubigeo_by_comisaria/{id_comisaria}', 'EstablecimientoController@obtener_ubigeo_by_comisaria');

Route::get('/get_topico_por_id_prioridad_sexo_edad_triaje/{id_prioridad}/{sexo}/{edad}', 'EmergenciaController@get_topico_por_id_prioridad_sexo_edad_triaje');
Route::get('/get_topico_por_id_prioridad_sexo_edad/{id_prioridad}/{sexo}/{edad}', 'EmergenciaController@get_topico_por_id_prioridad_sexo_edad');
Route::get('/buscar_ticket_by_id/{id_ticket}', 'EmergenciaController@buscar_ticket_by_id');
Route::get('/obtener_codigo_triaje/{id_triaje}', 'EmergenciaController@obtener_codigo_triaje');
Route::get('/modal_cita_horario_car/{id_consultorio}/{documento}/{tiene_turnos}', 'RecetaValeController@modal_cita_horario_listaCAR');
Route::get('/llamada_triaje_amergencia/{id_ticket}/{id_estado_tv}/{estado_llamada}/{cantidad_llamada}', 'EmergenciaController@llamada_triaje_amergencia');
//===========================Mantenedor Corredores======================================
Route::get('/corredores_detalle_all', 'CorredoresDetalleController@index');
Route::get('/corredor_detalle_create', 'CorredoresDetalleController@create');
Route::get('/corredor_detalle_edit/{id}', 'CorredoresDetalleController@edit');
Route::get('/modulos_por_servicio/{id_servicio_ticket}', 'CorredoresDetalleController@obtenerModulosPorServicio');
Route::get('/get_servicio_ticket', 'CorredoresDetalleController@get_servicio_ticket');
Route::post('registrar_corredores_detalle', [
  'uses' => 'CorredoresDetalleController@store',
  'as' => 'registrar_corredores_detalle'
]);
Route::get('/corredores_detalle_actualizar_estado/{id_corredor_detalle}/{estado}', 'CorredoresDetalleController@actualizar_estado');
Route::get('/liberar_modulo/{id_user}','CorredoresDetalleController@liberar_modulo');
Route::get('/modulo_all','ModulosController@index');
Route::get('/modulos_actualizar_estado/{id_ventanilla}/{estado}','ModulosController@actualizar_estado');
Route::get('/modulo_edit/{id_ventanilla}','ModulosController@edit');
Route::get('/modulo_create','ModulosController@create');
Route::post('registrar_modulo', [
  'uses' => 'ModulosController@store',
  'as' => 'registrar_modulo'
]);
//Route::get('/anular_paciente/{id_emergencia}', 'EmergenciaController@anular_paciente_emergencia');
Route::post('anular_paciente_emergencia', [
  'uses' => 'EmergenciaController@anular_paciente_emergencia',
  'as' => 'anular_paciente_emergencia'
]);
Route::get('/anular_prestacion_emergencia/{id_pre}/{id_emergencia}', 'EmergenciaController@anular_prestacion_emergencia');
Route::get('/buscar_atenciones', 'EmergenciaController@buscar_atenciones')->middleware('menu.permission:135');
Route::post('/asegurados_by_post', 'EmergenciaController@asegurados_by_post');
Route::post('/asegurados_atenciones_by_post', 'EmergenciaController@asegurados_atenciones_by_post');

Route::get('/validar_seleccion_llamado/{id_ticket}', 'EmergenciaController@validar_seleccion_llamado');
// Route::get('/validacion_beneficiario_dias_por_examen_rua_emergencia/{dni_beneficiario}/{id_examen_lab}','LaboratorioController@validacion_beneficiario_dias_por_examen_rua_emergencia');
// Route::get('/validacion_beneficiario_dias_por_examen_rua_emergencia_evaluaciones/{nrodoctitular}/{id_examen_lab}','EvaluacionController@validacion_beneficiario_dias_por_examen_rua_emergencia_evaluaciones');

//=======================RUA===========================//
Route::get('/add_categoria_rua2/{id_servicio}', 'LaboratorioController@add_categoria_rua2');
Route::get('/add_categoria_rua_modal/{nivel}/{id_servicio?}/{id_tipo_atencion}', 'LaboratorioController@add_categoria_rua_modal');
Route::get('/add_rua_nivel/{nivel}/{tipo}/{categoria}/{servicio}/{id_categoria_rua_nivel}', 'LaboratorioController@add_rua_nivel2');
Route::get('/obtener_tipo_atencion/{nivel}','LaboratorioController@obtenerTipoDeAtenciones');
Route::get('/obtener_nivel_establecimiento/{id_establecimiento}','LaboratorioController@obtenerNivelPorEstablecimiento');
Route::get('/validacion_beneficiario_dias_por_examen_rua/{dni_beneficiario}/{id_examen_lab}','LaboratorioController@validacion_beneficiario_dias_por_examen_rua');
Route::get('/ver_atencion_laboratorio/{id_tipo_atencion}/{id_lab}','LaboratorioController@verAtencionLaboratorio');
Route::get('/obtener_prioridades_emergencia','LaboratorioController@obtenerPrioridadesEmergencia');
Route::get('/obtener_detalle_prestaciones_laboratorio/{dni_paciente}','LaboratorioController@obtenerDetallePrestacionesLaboratorio');
Route::get('/obtener_items_detalle_prestaciones_laboratorio/{id_laboratorio}','LaboratorioController@obtenerItemsDetallePrestacionesLaboratorio');
Route::get('/reporte_cita_nueva_lab/{flag}/{ano}/{mes}/{dia}/{name}' . '.pdf', 'ExcelController@ver_prestacion_cita_nueva_laboratorio');

Route::get('/prueba_resultado_lab_core/{dni_beneficiario}/{fecha_toma_muestra}/{nro_orden}', 'LaboratorioController@pruebaResultadoLabCore');

Route::get('/busca_ubicacion/{ubicacion}', 'CitaController@buscar_ubicacion');

//=======================Laboratorio Firma ==========================//
Route::post('/refirma/postArgumentsLaboratorio', 'RefirmaController@postArgumentsLaboratorio')->name('postArgumentsLaboratorio');
Route::post('genera_csv_laboratorio', [
  'uses' => 'ExcelController@genera_csv_laboratorio',
  'as' => 'genera_csv_laboratorio'
]);
Route::post('sinTokensLaboratorio', [
  'uses' => 'ExcelController@sinTokensLaboratorio',
  'as' => 'sinTokensLaboratorio'
]);
Route::post('genera_csv_laboratorio_evaluacion', [
  'uses' => 'ExcelController@genera_csv_laboratorio_evaluacion',
  'as' => 'genera_csv_laboratorio_evaluacion'
]);
//=======================Laboratorio ==========================//
//Route::get('/obtener_detalle_prestaciones_laboratorio_emergencia/{dni_paciente}/{id_evaluacion}','LaboratorioController@obtenerDetallePrestacionesLaboratorioEmergencia');
// Route::get('/obtener_items_detalle_prestaciones_laboratorio_emergencia/{id_prestacion}/{id_evaluacion}','LaboratorioController@obtenerItemsDetallePrestacionesLaboratorioEmergencia');
//===========================Laboratorio =========================//
//Route::get('/exportar_reporte_laboratorio/{data_array}', 'ExcelController@exportar_reporte_laboratorio')->name('exportar_reporte_laboratorio');
Route::get('/consulta_ws_result_beneficiario_examen_rua/{dni_beneficiario}/{id_examen_lab}','LaboratorioController@consultaWsResultBeneficiarioExamenRua');
Route::get('/reporte_checklist_laboratorio/{id_laboratorio}','ExcelController@reporteChecklistLaboratorio')->middleware('check.server.resources');
//===================Anular Ticket================================
Route::get('/anular_ticket/{id_ticket}', 'TvController@anular_ticket');
Route::get('/get_sc_servicio_ticket/{id_establecimiento}/{id_servicio}', 'UsuarioController@get_sc_servicio_ticket');


//------------------------SUPER USUARIO------------------------

Route::get('/ver_usuarios_su/{id_user?}', 'SuperUsuarioController@ver_usuarios_su');

Route::post('listar_usuarios_modificados_ajax', [
  'uses' => 'SuperUsuarioController@listar_usuarios_modificados_ajax',
  'as' => 'listar_usuarios_modificados_ajax'
]);

Route::get('/ver_permisos/{id_user}/{dni}', 'SuperUsuarioController@ver_permisos');

Route::get('/bitacora_modificacion/{id_user}/{dni}', 'SuperUsuarioController@bitacora_modificacion');

//Reporte emergencia J

Route::get('/busca_ubicacion/{ubicacion}', 'CitaController@buscar_ubicacion');

/////
Route::get('/exportar_reporte_alta_emergencia/{fecha_inicio}/{fecha_fin}/{dni}/{paciente}/{estado}/{id_tipo_egreso}/{id_prioridad}/{id_topico}', 'ExcelController@exportar_reporte_alta_emergencia');

Route::get('/reportes_emergencia_general/{id_user?}', 'MedicoController@reportes_emergencia_general');

Route::post('buscar_reporte_alta', [
  'uses' => 'EmergenciaController@buscar_reporte_alta',
  'as' => 'buscar_reporte_alta'
]);

Route::post('listar_reporte_2_ajax', [
  'uses' => 'EmergenciaController@listar_reporte_2_ajax',
  'as' => 'listar_reporte_2_ajax'
]);

Route::post('listar_reporte_1_ajax', [
  'uses' => 'EmergenciaController@listar_reporte_1_ajax',
  'as' => 'listar_reporte_1_ajax'
]);

Route::post('listar_reporte_5_ajax', [
  'uses' => 'EmergenciaController@listar_reporte_5_ajax',
  'as' => 'listar_reporte_5_ajax'
]);

Route::post('listar_reporte_3_ajax', [
  'uses' => 'EmergenciaController@listar_reporte_3_ajax',
  'as' => 'listar_reporte_3_ajax'
]);

Route::post('listar_reporte_16_ajax', [
  'uses' => 'EmergenciaController@listar_reporte_16_ajax',
  'as' => 'listar_reporte_16_ajax'
]);

Route::post('listar_reporte_17_ajax', [
  'uses' => 'EmergenciaController@listar_reporte_17_ajax',
  'as' => 'listar_reporte_17_ajax'
]);

Route::get('/exportar_reporte_emergencia_1/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_1');//->middleware('check.server.resources');

Route::get('/exportar_reporte_emergencia_2/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_2');//->middleware('check.server.resources');

Route::get('/exportar_reporte_emergencia_3/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_3');//->middleware('check.server.resources');

Route::get('/exportar_reporte_emergencia_4/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_4');//->middleware('check.server.resources');

Route::get('/exportar_reporte_emergencia_5/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_5');//->middleware('check.server.resources');

Route::get('/exportar_reporte_emergencia_6/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_6');//->middleware('check.server.resources');

Route::get('/exportar_reporte_emergencia_7/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_7');//->middleware('check.server.resources');

Route::get('/exportar_reporte_emergencia_8/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_8');//->middleware('check.server.resources');

Route::get('/exportar_reporte_emergencia_9/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_9');//->middleware('check.server.resources');

Route::get('/exportar_reporte_emergencia_10/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_10');//->middleware('check.server.resources');

Route::get('/exportar_reporte_emergencia_11/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_11');//->middleware('check.server.resources');

Route::get('/exportar_reporte_emergencia_12/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_12');//->middleware('check.server.resources');

Route::get('/exportar_reporte_emergencia_13/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_13');//->middleware('check.server.resources');

Route::get('/exportar_reporte_emergencia_14/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_14');//->middleware('check.server.resources');

Route::get('/exportar_reporte_emergencia_15/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_15');//->middleware('check.server.resources');

Route::get('/exportar_reporte_emergencia_16/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_16');//->middleware('check.server.resources');

Route::get('/exportar_reporte_emergencia_17/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_17');//->middleware('check.server.resources');

Route::get('/exportar_reporte_emergencia_18/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_18');//->middleware('check.server.resources');

Route::get('/exportar_reporte_emergencia_19/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_19');//->middleware('check.server.resources');

Route::get('/exportar_reporte_emergencia_20/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_20');//->middleware('check.server.resources');

Route::get('/exportar_reporte_emergencia_21/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_21');//->middleware('check.server.resources');

Route::get('/exportar_reporte_emergencia_22/{fecha_inicio}/{fecha_fin}', 'ExcelController@exportar_reporte_emergencia_22');//->middleware('check.server.resources');

Route::post('lista_reporte_defuncion_ajax', [
  'uses' => 'MedicoController@lista_reporte_defuncion_ajax',
  'as' => 'lista_reporte_defuncion_ajax'
]);

Route::get('/exportar_reporte_alta_emergencia/{fecha_inicio}/{fecha_fin}/{dni}/{paciente}/{estado}', 'ExcelController@exportar_reporte_alta_emergencia');
/////
Route::get('/actualiza_beneficiario_datos/', 'PacienteController@actualiza_beneficiario_datos');

/* CENSO DE CAMAS*/
Route::get('/censo_camas','CensoCamaController@censo');
Route::post('registrar_censos', [
  'uses' => 'CensoCamaController@registrar_censos',
  'as'   => 'registrar_censos'
]);

Route::get('/obtener_servicio_por_idestablecimiento_tipoatencion2/{id_establecimiento}/{tipo_atencion}', 'CensoCamaController@obtener_servicio_por_idestablecimiento_tipoatencion2');

Route::get('/obtener_servicio_cama_consultorio/{id_establecimiento}/{id_consultorio}/{tipo_atencion}', [
    'uses' => 'CensoCamaController@obtener_servicio_cama_consultorio',
    'as'   => 'obtener_servicio_cama_consultorio'
]);

Route::get('/obtener_upss/{id_consultorio}',[
  'uses' => 'CensoCamaController@obtener_upss',
  'as'   => 'obtener_upss'
]);

Route::get('/obtener_especialidades/{id_upss}', [
    'uses' => 'CensoCamaController@obtener_especialidades',
    'as'   => 'obtener_especialidades'
]);

Route::get('/obtener_upss_cama/{id_consultorio}',[
  'uses' => 'CensoCamaController@obtener_upss_cama',
  'as'   => 'obtener_upss_cama'
]);

Route::get('/medicos/{id}', [
    'uses' => 'CensoCamaController@medicosPorConsultorio',
    'as'   => 'medicos.por.consultorio'
]);

Route::get('/obtener_situacion_cama', [
    'uses' => 'CensoCamaController@obtener_situacion_cama',
    'as'   => 'obtener_situacion_cama'
]);

Route::post('registrar_rotacion_cama', [
  'uses' => 'CensoCamaController@registrar_rotacion_cama',
  'as'   => 'registrar_rotacion_cama'
]);

Route::post('transferencia_cama', [
  'uses' => 'CensoCamaController@transferencia_cama',
  'as'   => 'transferencia_cama'
]);

Route::post('liberar_cama', [
  'uses' => 'CensoCamaController@liberar_cama',
  'as'   => 'liberar_cama'
]);

Route::post('modificar_estado_cama', [
  'uses' => 'CensoCamaController@modificar_estado_cama',
  'as'   => 'modificar_estado_cama'
]);

Route::post('/censo_camas_filtro/getFiltros', 'CensoCamaController@GetCensoCamaForFiltros');
Route::post('censo_camas_filtro', [
  'uses' => 'CensoCamaController@censo_camas_filtro',
  'as' => 'censo_camas_filtro'
]);

Route::post('listado_censo_camas', [
  'uses' => 'CensoCamaController@listado_censo_camas',
  'as' => 'listado_censo_camas'
]);

Route::get('/visor_camas', 'CamaController@visor');
Route::post('visor_camas_detalle2', [
  'uses' => 'CamaController@visor_camas_detalle2',
  'as' => 'visor_camas_detalle2'
]);
Route::get('/obtener_establecimiento_camas', 'CamaController@obtener_establecimiento_camas');
Route::get('/obtener_atencion_por_idestablecimiento/{id_establecimiento}', 'CamaController@obtener_atencion_por_idestablecimiento');
Route::get('/obtener_servicio_por_idestablecimiento_tipoatencion/{id_establecimiento}/{tipo_atencion}', 'CamaController@obtener_servicio_por_idestablecimiento_tipoatencion');
Route::get('/obtener_pabellon_por_idestablecimiento_tipoatencion_idservicio/{id_establecimiento}/{tipo_atencion}/{id_servicio}', 'CamaController@obtener_pabellon_por_idestablecimiento_tipoatencion_idservicio');
Route::post('visor_camas_filtro', [
  'uses' => 'CamaController@visor_camas_filtro',
  'as' => 'visor_camas_filtro'
]);
Route::get('/modal_cama_masinfo/{id_cama}/{tipo_atencion}', 'CamaController@modal_cama_masinfo');
Route::get('/obtener_interconsultas_pendientes/{id_user?}', 'InterconsultasController@obtener_interconsultas_pendientes');
Route::get('/obtener_cantidad_interconsultas_pendientes_by_user', 'InterconsultasController@obtener_cantidad_interconsultas_pendientes_by_user');
Route::get('/get_camas_por_id_cuarto/{id_ipress}/{id_cuarto}/{id_tipo_atencion}', 'CamaController@get_camas_por_id_cuarto');
Route::get('/obtener_cuarto_por_idestablecimiento_tipoatencion/{id_establecimiento}/{tipo_atencion}', 'CamaController@obtener_cuarto_por_idestablecimiento_tipoatencion');

Route::post('/exportar_reporte_cita2', 'ExcelController@exportar_reporte_cita2')->middleware('check.server.resources');
Route::get('/download_reportes/{name_reporte}', 'ExcelController@descargarReporteDelStorage');
Route::get('/atender_en_consultorio_actual/{id_cita}/{id_medico}/{id_tipo_atencion}/{id_servicio}', 'CitaController@atender_en_consultorio_actual');
Route::get('/formulario_atencion_tomado/{id_cita}/{id_medico}/{id_sub_consultorio}', 'RecetaValeController@formulario_atencion_tomado');
Route::get('/modal_proxima_cita_servicio2/{tipodoc_beneficiario}/{dni_beneficiario}/{servicio}/{id_establecimiento}/{id_medico?}', 'CitaController@modal_proxima_cita_servicio2');
// ==================== SERVICES FOR GESTACION ==================
Route::get('/obtener_ultima_gestacion_dni_asegurado_ajax/{asegurado_dni}', 'GestacionController@obtener_ultima_gestacion_dni_asegurado_ajax');
/******EMBARAZOS */
Route::post('listar_paginados_gestantes_modulos', [
  'uses' => 'CitaController@listar_paginados_gestantes_modulos',
  'as' => 'listar_paginados_gestantes_modulos'
]);

//Route::get('/all_atenciones_gestantes_puerpera/{iduser?}', 'CitaController@all_atenciones_gestantes_puerpera');
/////// salud materna corregido
Route::get('/all_atenciones_gestantes_puerpera/{iduser?}', 'SaludMaternaController@all_atenciones_gestantes_puerpera');
Route::get('/all_atenciones_puerpera/{iduser?}', 'SaludMaternaController@all_atenciones_puerpera');
Route::get('/all_atenciones_gestantes_individual/{iduser?}', 'SaludMaternaController@all_gestantes_individual')->middleware('menu.permission:176');

Route::post('listar_salud_materna', [
  'uses' => 'SaludMaternaController@listar_salud_materna',
  'as' => 'listar_salud_materna'
]);

Route::post('listar_salud_materna_puerpera', [
  'uses' => 'SaludMaternaController@listar_salud_materna_puerpera',
  'as' => 'listar_salud_materna_puerpera'
]);
Route::post('listar_gestantes_individual_vista', [
  'uses' => 'SaludMaternaController@listar_gestantes_individual_vista',
  'as' => 'listar_gestantes_individual_vista'
]);
////// fin correcion

//****** salud materna irwin exportar excel xd */
//Route::get('/exportar_reporte_saludMaterna/{idUser}/{fecha_ini}/{fecha_fin}/{dni}/{paciente}/{estado}/{id_servicio}/{id_consultorio}/{id_medico}/{id_atencione}', 'ExcelController@exportar_reporte_saludMaterna');
// ==================== SERVICES FOR GESTACION END ==================
//ProgressBar de reporte de Laboratorio
Route::post('/exportar_reporte_laboratorio', 'ExcelController@exportar_reporte_laboratorio');//->middleware('check.server.resources');
Route::post('ficha_psicologica', [
  'uses' => 'Psicologia@psicologico_envio',
  'as' => 'psicologico_envio'
]);
Route::get('/obtener_consultorio_tipo_atencion/{id_ipress}/{id_consulto}/{id_tipo_atencion}', 'ConsultorioController@obtener_consultorio_tipo_atencion');
Route::get('/obtener_consultorio_tipo_atencion_medico/{id_ipress}/{id_medico}', 'ConsultorioController@obtener_consultorio_tipo_atencion_medico');
Route::get('/obtener_consultorio_tipo_atencion_emergencia/{id_establecimiento}/{id_tipo_atencion}', 'ConsultorioController@obtener_consultorio_tipo_atencion_medico');

Route::get('/exportar_reporte_saludMaterna_gestantes/{idUser}/{fecha_ini}/{fecha_fin}/{dni}/{paciente}/{estado}/{id_servicio}/{id_consultorio}/{id_medico}/{id_atencione}', 'ExcelController@exportar_reporte_saludMaterna_gestantes')->middleware('check.server.resources');
Route::get('/exportar_reporte_saludMaterna_puerperios/{idUser}/{fecha_ini}/{fecha_fin}/{dni}/{paciente}/{estado}/{id_servicio}/{id_consultorio}/{id_medico}/{id_atencione}', 'ExcelController@exportar_reporte_saludMaterna_puerperios')->middleware('check.server.resources');

//referencia
Route::get('/obtener_servicios/{dni_beneficiario}', 'RecetaValeController@obtener_servicios');
Route::get('/form_solicitud/{id_referido}/{id_asegurado}', 'ReferenciasController@form_solicitud');
Route::get('/form_solicitud_contrareferencia/{id_referido}/{id_asegurado}', 'ReferenciasController@form_solicitud_contrareferencia');
Route::get('/get_ipress/{id}', 'ReferenciasController@get_ipress');
Route::get('/get_datosasegurado/{id_aseg}/{id_ipress}', 'ReferenciasController@get_datosasegurado');
Route::get('/obtener_si_existe_referencia/{tipodoc_beneficiario}/{dni_beneficiario}', 'ReferenciasController@obtener_si_existe_referencia');

Route::post('registrar_solicitud', [
  'uses' => 'ReferenciasController@registrar_solicitud',
  'as' => 'registrar_solicitud'
]);

Route::post('registrar_solicitud_formulario', [
  'uses' => 'ReferenciasController@registrar_solicitud_formulario',
  'as' => 'registrar_solicitud_formulario'
]);

Route::post('registrar_formulario_contrareferencia', [
  'uses' => 'ReferenciasController@registrar_formulario_contrareferencia',
  'as' => 'registrar_formulario_contrareferencia'
]);

Route::get('/formulario_cita_referencia/{id_referencia}','ReferenciasController@formulario_cita_referencia');
Route::get('/get_procedimientos', 'ReferenciasController@get_procedimientos');
Route::post('validar_referencia', ['uses' => 'ReferenciasController@validar_referencia','as' => 'validar_referencia']);

Route::get('/modal_cita_horario_referencia/{id_ipress}/{id_consultorio}/{fecha_atencion}/{documento}', 'RecetaValeController@modal_cita_horario_lista_referencia');
/*Route::post('registrar_cita_referencia', [
  'uses' => 'RecetaValeController@registrar_cita_transaction_referencia',
  'as' => 'registrar_cita_referencia'
]);
*/
Route::post('registrar_cita_referencia', [
  'uses' => 'ReferenciasController@registrar_cita_transaction_referencia',
  'as' => 'registrar_cita_referencia'
]);

Route::get('obtener_datos_persona_ajax_local', [
  'uses' => 'PacienteController@obtener_datos_persona_ajax_local',
  'as' => 'obtener_datos_persona_ajax_local'
]);

Route::get('/ver_cita_referido/{id_user}/{id_cita}', 'RecetaValeController@ver_cita_referido');

Route::get('/ver_anexo_3/{id_referencia}/{id_cita}', 'PdfController@ver_anexo_3');

Route::get('/all_emergencia_triaje_ref/{tipo}/{dni}', 'EmergenciaController@triaje_ref');

Route::post('store_emergencia_triaje_ref', [
  'uses' => 'EmergenciaController@registrar_emergencia_triaje_ref',
  'as' => 'store_emergencia_triaje_ref'
]);

//=============== siteds ambulatorio ===============
Route::get('/modal_siteds/{id}/{id_asegurado}/{retorno}', 'RecetaValeController@modal_siteds');
Route::post('update_siteds', [
  'uses' => 'CitaController@update_siteds',
  'as' => 'update_siteds'
]);
Route::get('/validar_dato_siteds_ambulatorio/{cod_siteds}', 'RecetaValeController@validar_dato_siteds_ambulatorio'); //=============== hospitalzacion ===============
Route::get('/admision_hospitalizacion_nuevo/{id_emergencia?}', 'HospitalizacionController@admision_hospitalizacion_nuevo');
Route::get('obtener_datos_solo_reniec_ajax', [
  'uses' => 'PacienteController@obtener_datos_solo_reniec_ajax',
  'as' => 'obtener_datos_solo_reniec_ajax'
]);
Route::get('/admision_hospitalizacion_nuevo_trama/{id_emergencia?}', 'HospitalizacionController@obtener_datos_por_id_emergencia');
//Route::get('/datos_adicionales_paciente_dni/{dni}/{tipo_documento}', 'ParametrosController@datos_adicionales_paciente_by_dni');
Route::get('/parametro/profesional/{buscar_medico}', 'ParametrosController@listar_profesional_autocomplete_by_dni'); 
Route::get('/obtener_cama_disponible_sala_by_id_consultorio/{id_ipress}/{id_consultorio}', 'CamaController@obtener_cama_disponible_sala_by_id_consultorio');
Route::get('/obtener_diagnostico_genero_by_id/{id_diagnostico}', 'RecetaValeController@obtener_diagnostico_genero_by_id');
Route::get('/parametro/profesional/by_id/{id_profesional}', 'MedicoController@obtener_profesional_by_id');
Route::post('all_hospitalizado_by_establecimiento_ajax', [
  'uses' => 'HospitalizacionController@all_hospitalizado_by_establecimiento_ajax',
  'as' => 'all_hospitalizado_by_establecimiento_ajax'
]);

Route::post('all_hospitalizados_asegurados', [
  'uses' => 'HospitalizacionController@all_hospitalizados_asegurados',
  'as' => 'all_hospitalizados_asegurados'
]);

Route::post('UpdateAsegurados', [
  'uses' => 'HospitalizacionController@UpdateAsegurados',
  'as' => 'UpdateAsegurados'
]);

Route::post('listado_receta_vale',[
  'uses' => 'RecetaValeController@listado_receta_vale',
  'as' => 'listado_receta_vale'
]);

Route::post('UpdateRecetaVale', [
  'uses' => 'RecetaValeController@UpdateRecetaVale',
  'as' => 'UpdateRecetaVale'
]);

Route::post('registrar_prestacion_hospitalizacion', [
  'uses' => 'HospitalizacionController@registrar_prestacion_transaction_hospitalizacion',
  'as' => 'registrar_prestacion_hospitalizacion'
]);
Route::get('/ver_prestaciones_hospitalizacion/{id}', 'HospitalizacionController@ver_prestaciones_hospitalizacion');
Route::get('/ver_prestacion_hospitalizacion/{id_user}/{id_prestacion}', 'HospitalizacionController@ver_prestacion_hospitalizacion');
Route::post('registrar_examenes_complementarios_hospitalizacion', [
  'uses' => 'HospitalizacionController@registrarExamenComplementarios',
  'as' => 'registrar_examenes_complementarios_hospitalizacion'
]);
Route::get('/all_atenciones_by_hospitalizacion/{id_user?}', 'HospitalizacionController@all_atenciones_by_hospitalizacion')->middleware('menu.permission:145');
Route::post('listar_atencion_hospitalizacion_ajax', [
  'uses' => 'HospitalizacionController@listar_atencion_hospitalizacion_ajax',
  'as' => 'listar_atencion_hospitalizacion_ajax'
]);
Route::get('/hospitalizacion_alta_medica/{id_user}/{id_prestacion}/{vista?}', 'HospitalizacionController@alta_medica');
Route::post('registrar_alta_medica_hospitalizacion', [
  'uses' => 'HospitalizacionController@registrar_alta_medica_hospitalizacion',
  'as' => 'registrar_alta_medica_hospitalizacion'
]);
Route::get('/hospitalizacion_ver_alta_medica/{id_user}/{id_prestacion}', 'HospitalizacionController@ver_alta_medica');
Route::get('hospitalizacion_buscar_dia_estancia_ajax', [
  'uses' => 'HospitalizacionController@buscar_dia_estancia_ajax',
  'as' => 'hospitalizacion_buscar_dia_estancia_ajax'
]);

// =================== LISTA DE HOSPITALIZACIONES ==============
Route::post('listado_hospitalizaciones', [
  'uses' => 'HospitalizacionController@listado_hospitalizaciones',
  'as' => 'listado_hospitalizaciones'
]);
// ==================== SERVICES FOR GESTACION ==================
Route::get('/obtener_ultima_gestacion_dni_asegurado_ajax/{asegurado_dni}', 'GestacionController@obtener_ultima_gestacion_dni_asegurado_ajax');
// ==================== SERVICES FOR GESTACION END ==================
Route::get('/all_pacientes_evoluciones_by_hospitalizacion/{id_user?}', 'EvolucionHospitalizacionController@all_pacientes')->middleware('menu.permission:144');
Route::post('listar_evolucion_hospitalizacion_ajax', [
  'uses' => 'EvolucionHospitalizacionController@listar_evolucion_hospitalizacion_ajax',
  'as' => 'listar_evolucion_hospitalizacion_ajax'
]);
Route::get('/ajax/verificar_hc_evolucion/{id_hospitalizacion}', 'EvolucionHospitalizacionController@verificarHistoriaClinicaEvolucion');
Route::get('/form_evolucion_hospitalizacion/{id_hospitalizacion}', 'EvolucionHospitalizacionController@form_evolucion_hospitalizacion');
Route::post('registrar_evolucion_hospitalizacion', [
  'uses' => 'EvolucionHospitalizacionController@registrar_evolucion_transaction_hospitalizacion',
  'as' => 'registrar_evolucion_hospitalizacion'
]);
Route::post('registrar_evolucion_hospitalizacion_adicional', [
  'uses' => 'EvolucionHospitalizacionController@registrar_evolucion_hospitalizacion_adicional',
  'as' => 'registrar_evolucion_hospitalizacion_adicional'
]);
Route::get('/mostrar_evolucion/{id}/{id_tipo_atencion}', 'EvolucionHospitalizacionController@mostrar_evolucion');
Route::get('/mostrar_evolucion_hospitalizacion/{id}/{id_tipo_atencion}', 'EvolucionHospitalizacionController@mostrar_evolucion_hospitalizacion');
Route::get('/ver_evaluacion_hospitalizacion/{iduser}/{id_evolucion}', 'EvolucionHospitalizacionController@ver_evaluacion');
Route::get('/edit_evolucion_hospitalizacion/{iduser}/{id_evolucion}', 'EvolucionHospitalizacionController@edit_evolucion');
Route::get('/edit_atencion_hospitalizacion/{iduser}/{id_evolucion}', 'HospitalizacionController@edit_atencion');
Route::get('/ver_atenciones_evaluacion_hospitalizacion/{id_hospitalizacion}/{estado}/{id_tipo_atencion}', 'EvolucionHospitalizacionController@ver_atenciones_evaluacion');
Route::post('listar_evolucion_hospi_ajax', [
  'uses' => 'EvolucionHospitalizacionController@listar_evolucion_hospi_ajax',
  'as' => 'listar_evolucion_hospi_ajax'
]);
Route::get('/ver_atenciones_evaluacion_farmacia/{id_hospitalizacion}/{estado}/{id_tipo_atencion}', 'HospitalizacionController@ver_atenciones_evaluacion_farmacia');
Route::get('/all_hospitalizaciones_by_alta_administrativa/{id_user?}', 'HospitalizacionController@all_hospitalizaciones_by_alta_administrativa')->middleware('menu.permission:146');
Route::get('/alta_admin_hospitalizacion/{id_user}/{id_prestacion}', 'HospitalizacionController@alta_admin_hospitalizacion');
Route::post('registrar_alta_administrativa_hospitalizacion', [
  'uses' => 'HospitalizacionController@registrar_alta_administrativa_hospitalizacion',
  'as' => 'registrar_alta_administrativa_hospitalizacion'
]);
Route::get('/validar_codigo_siteds/{codigo_siteds}', 'EvolucionHospitalizacionController@validar_codigo_siteds');
Route::get('/obtener_servicio_by_id_upss/{id_upss}', 'ConsultorioController@obtener_servicio_by_id_upss');
Route::get('/obtener_servicio_by_id_upss2/{id_upss}', 'ConsultorioController@obtener_servicio_by_id_upss2');
//FIRMA DEL RESPONSABLE DE RESULTADOS EN LABORATORIO
Route::post('genera_csv_ordenes_laboratorio', [
  'uses' => 'ExcelController@genera_csv_ordenes_laboratorio',
  'as' => 'genera_csv_ordenes_laboratorio'
]);
Route::get('/hospitalizacion_ver_alta_administrativa/{iduser}/{id_hospitalizacion}', 'HospitalizacionController@ver_alta_administrativa');

Route::get('/modal_historial_rc/{id_asegurado}', 'ReferenciasController@modal_historial_rc');
Route::get('/modal_historial_rc_dni/{dni_beneficiario}', 'ReferenciasController@modal_historial_rc_dni');

Route::get('/PdfHospitalizacionAdmision/{id}','PdfController@mostrarPdfhospitalizacionAdmision');
Route::get('/pdf_hospitalizacion_alta_administrativa/{id_prestacion}', 'PdfController@mostrarPdfHospitalizacionAltaAdministrativa');
Route::get('/print_hospitalizacion_atencion/{id_hospitalizacion}', 'PdfController@print_hospitalizacion_atencion');
Route::get('/print_hospitalizacion_evolucion/{id_evolucion}', 'PdfController@print_hospitalizacion_evolucion');
//exportar reportes evalvoluciones hospitalizacion excel
Route::post('/exportar_reporte_hospitalizacion_evaluaciones/', 'ExcelController@exportar_reporte_hospitalizacion_evaluaciones');
//exportar reportes altas hospitalizacion excel
Route::post('/exportar_reporte_alta_hospitalizacion/', 'ExcelController@exportar_reporte_alta_hospitalizacion');
Route::post('/exportar_reporte_altasAdministrativa_hospitalizacion/', 'ExcelController@exportar_reporte_altasAdministrativa_hospitalizacion');



// *********************** ROUTES FOR WS SUSALUD (MODELO SITEDS) TAGSITEDS **************************
Route::post('/ws-dirsapol-siteds', 'WsDirsapolSitedsController@generateCodeSiteds');
Route::post('/v2/ws-dirsapol-siteds', 'WsDirsapolSitedsController@generateCodeSitedsv2');
Route::get('/ws-dirsapol-get-updated-catalog/{numVersion}', 'WsDirsapolSitedsController@getUpdatedCatalogByVersion');
Route::put('/ws-dirsapol-updated-catalog', 'WsDirsapolSitedsController@updatedCatalog');
Route::put('/receta-vale-actualizar-siteds/{id}', 'RecetaValeController@actualizarCodigoSiteds');
Route::post('/generate-siteds-massive-citas', 'WsDirsapolSitedsController@generateCodeSitedsMassiveCitas');
Route::post('/generate-siteds-massive-receta', 'WsDirsapolSitedsController@generateCodeSitedsMassiveRecetas');

Route::post('/regularize-multiple-siteds-temporary', 'WsDirsapolSitedsController@regularizeMultipleSitedsTemp');

Route::put('/emergencia/{id}/update-code-siteds', 'EmergenciaController@updateCodeSiteds');
Route::put('/evaluacion-emergencia/{id}/update-code-siteds', 'EvaluacionController@updateCodeSiteds');
Route::put('/hospitalizacion/{id}/update-code-siteds', 'HospitalizacionController@updateCodeSiteds');
Route::put('/hospitalizacion-evaluacion/{id}/update-code-siteds', 'EvolucionHospitalizacionController@updateCodeSiteds');


// *********************** ROUTES FOR WS SUSALUD (MODELO SITEDS) **************************

Route::get('/modal_diagnostico_paciente_nuevo/{id_prestacion}', 'DiagnosticoController@modal_diagnostico_paciente_nuevo');
Route::get('/modal_diagnostico_paciente_nuevo_ho/{id_prestacion}', 'DiagnosticoController@modal_diagnostico_paciente_nuevo_ho');
Route::get('/modal_diagnostico_paciente_nuevo_ho_evo/{id_prestacion}', 'DiagnosticoController@modal_diagnostico_paciente_nuevo_ho_evo');
Route::get('/modal_procedimiento_paciente_nuevo/{id_prestacion}', 'ProcedimientoController@modal_procedimiento_paciente_nuevo');
Route::get('/modal_procedimiento_paciente_nuevo_ho/{id_prestacion}', 'ProcedimientoController@modal_procedimiento_paciente_nuevo_ho');
Route::get('/modal_procedimiento_paciente_nuevo_ho_evo/{id_prestacion}', 'ProcedimientoController@modal_procedimiento_paciente_nuevo_ho_evo');
Route::get('/print_hospitalizacion_alta_medica_hc/{id_hospitalizacion}', 'PdfController@print_hospitalizacion_alta_medica_hc');
Route::get('/print_hospitalizacion_papeleta_egreso/{id_hospitalizacion}', 'PdfController@print_hospitalizacion_papeleta_egreso');
Route::get('/print_hospitalizacion_informe_egreso/{id_hospitalizacion}', 'PdfController@print_hospitalizacion_informe_egreso');
Route::post('/exportar_admision_hospitalizacion/', 'ExcelController@exportar_admision_hospitalizacion');
Route::post('/export_admin_hospitalizacion/', 'ExcelController@export_admision_hospitalizacion');
Route::post('/exportar_atencion_hospitalizacion', 'ExcelController@exportar_atencion_hospitalizacion');
Route::get('/get_camas_by_idestablecimiento_nombretipoatencion_idcuarto/{id_establecimiento}/{nombre_tipo_atencion}/{id_cuarto}', 'CamaController@get_camas_by_idestablecimiento_nombretipoatencion_idcuarto');
Route::get('/get_camas_by_idestablecimiento_nombretipoatencion_idcuarto_activos/{id_establecimiento}/{nombre_tipo_atencion}/{id_cuarto}', 'CamaController@get_camas_by_idestablecimiento_nombretipoatencion_idcuarto_activos');
Route::get('/get_servicios_by_idestablecimiento_nombretipoatencion_idpabellon/{id_establecimiento}/{nombre_tipo_atencion}/{id_pabellon}', 'CamaController@get_servicios_by_idestablecimiento_nombretipoatencion_idpabellon');
Route::get('/get_camas_by_idestablecimiento_nombretipoatencion_idpabellon/{id_establecimiento}/{nombre_tipo_atencion}/{id_pabellon}', 'CamaController@get_camas_by_idestablecimiento_nombretipoatencion_idpabellon');
//Valida siteds evaluaciones emergencia
Route::get('/validar_dato_siteds_evaluacion/{cod_siteds}', 'EvaluacionController@validar_dato_siteds_evaluacion');
Route::get('/buscar_siteds_receta_vale/{fecha_expedicion}/{dni_beneficiario}', 'RecetaValeController@buscar_siteds_receta_vale');
Route::get('/buscar_siteds_generado_hoy/{dni_beneficiario}/{id_press}', 'RecetaValeController@buscar_siteds_generado_hoy');
Route::get('/validar_dato_siteds_receta_vale/{cod_siteds}', 'RecetaValeController@validar_dato_siteds_receta_vale');
Route::post('genera_csv_receta_vale', [
  'uses' => 'ExcelController@genera_csv_receta_vale',
  'as' => 'genera_csv_receta_vale'
]);

/*imagenologia*/
Route::post('/exportar_reporte_imagenologia', 'ExcelController@exportar_reporte_imagenologia')->middleware('check.server.resources');
Route::get('/modal_siteds_imagenologia/{id}/{id_asegurado}', 'RadiologiaController@modal_siteds_imagenologia');
Route::post('update_siteds_imagenologia', [
  'uses' => 'RadiologiaController@update_siteds_imagenologia',
  'as' => 'update_siteds_imagenologia'
]);
//================================ Laboratorio Nuevos cambios y mejoras para el servicio INICIO ================================//
Route::post('registrar_observacion_asistencia_paciente', [
  'uses' => 'LaboratorioController@registrarObservacionAsistenciaPaciente',
  'as' => 'registrar_observacion_asistencia_paciente'
]);
Route::post('registrar_solicitud_tipo_muestra_microbiologia', [
  'uses' => 'LaboratorioController@registrarSolicitudTipoMuestraMicrobiologia',
  'as' => 'registrar_solicitud_tipo_muestra_microbiologia'
]);
Route::get('/obtener_solicitud_procedimiento/{id_solicitud_procedimiento}', 'LaboratorioController@obtenerSolicitudProcedimiento');
Route::get('/obtener_columnas_servicio_consultorios/{tipo_atencion}/{id_consultorio?}', 'LaboratorioController@obtenerColumnasServicioConsultorios');
Route::post('registrar_columna_reporte_laboratorio', [
  'uses' => 'LaboratorioController@registrarColumnaReporteLaboratorio',
  'as' => 'registrar_columna_reporte_laboratorio'
]);


//ws interoperatividad
Route::get('/ws_ministerio/{id_prestacion}', 'WebServiceController@Ws_mininter');

//----------------------------MODULO AUDITORIA --------------
Route::post('listar_auditoria_ajax', [
  'uses' => 'AuditoriaController@listar_auditoria_ajax',
  'as' => 'listar_auditoria_ajax'
]);

Route::post('listar_auditoria_hospi_ajax', [
  'uses' => 'AuditoriaController@listar_auditoria_hospi_ajax',
  'as' => 'listar_auditoria_hospi_ajax'
]);

Route::post('listar_auditoria_ambu_ajax', [
  'uses' => 'AuditoriaController@listar_auditoria_ambu_ajax',
  'as' => 'listar_auditoria_ambu_ajax'
]);

Route::get('obtener_establecimientos_por_nivel', [
  'uses' => 'AuditoriaController@obtenerEstablecimientosPorNivel',
  'as' => 'obtener_establecimientos_por_nivel'
]);
Route::get('obtener_servicios_por_establecimiento', [
  'uses' => 'AuditoriaController@obtenerServiciosPorEstablecimiento',
  'as' => 'obtener_servicios_por_establecimiento'
]);
Route::get('obtener_todos_establecimientos', 'AuditoriaController@obtenerTodosEstablecimientos')->name('obtener_todos_establecimientos');
Route::get('obtener_medicos_por_servicio', [
  'uses' => 'AuditoriaController@obtenerMedicosPorServicio',
   'as' => 'obtener_medicos_por_servicio'
 ]);

 Route::post('guardar_diagnostico_auditoria', [
  'uses' => 'AuditoriaController@guardar_diagnostico_auditoria',
  'as' => 'guardar_diagnostico_auditoria'
]);

Route::post('guardar_procedimiento_auditoria', [
  'uses' => 'AuditoriaController@guardar_procedimiento_auditoria',
  'as' => 'guardar_procedimiento_auditoria'
]);

Route::post('guardar_producto_auditoria', [
  'uses' => 'AuditoriaController@guardar_producto_auditoria',
  'as' => 'guardar_producto_auditoria'
]);

Route::post('guardar_observacion_auditoria', [
  'uses' => 'AuditoriaController@guardar_observacion_auditoria',
  'as' => 'guardar_observacion_auditoria'
]);

 Route::post('envio_masivo_10', [
  'uses' => 'AuditoriaController@envio_masivo_10',
  'as' => 'envio_masivo_10'
]);

Route::post('listar_auditoria_dinamico_ajax', [
  'uses' => 'AuditoriaController@listar_auditoria_dinamico_ajax',
  'as' => 'listar_auditoria_dinamico_ajax'
]);

Route::post('listar_auditoria_cups_ajax', [
  'uses' => 'AuditoriaController@listar_auditoria_cups_ajax',
  'as' => 'listar_auditoria_cups_ajax'
]);
Route::get('/ficha_auditoria_emergencia/{id_trama?}', 'AuditoriaController@ficha_auditoria_emergencia');
Route::get('/ficha_auditoria_consulta_externa/{id_trama?}', 'AuditoriaController@ficha_auditoria_consulta_externa');
Route::get('/ficha_auditoria_hospitalizacion/{id_trama?}', 'AuditoriaController@ficha_auditoria_hospitalizacion');
Route::get('/modificar_validacion_auditoria/{id_trama?}', 'AuditoriaController@all_auditoria_observados');
Route::get('/modificar_validacion_auditoria/{id_trama?}', 'AuditoriaController@modificar_validacion_auditoria');
Route::get('/all_cpms_ficha/{id_trama?}', 'AuditoriaController@all_cpms_ficha');
Route::get('/all_auditoria_observados/{id_user?}', 'AuditoriaController@all_auditoria_observados');
Route::get('/all_auditoria_empaquetado/{id_user?}', 'AuditoriaController@all_auditoria_empaquetado');
Route::get('/ver_detalle_codigo_paquete/{id}', 'AuditoriaController@ver_detalle_codigo_paquete');
Route::get('/obtener_procedimiento_auditorias/{buscar}/{nivel}/{genero}/{tipo}', 'AuditoriaController@obtener_procedimiento_auditorias');
Route::get('/editar_all_auditoria/{numero_correlativo?}', 'AuditoriaController@editarAllAuditoria')->name('editar.all.auditoria');
Route::get('/modificar_auditoria/{numero_correlativo?}', 'AuditoriaController@modificar_auditoria')->name('modificar.all.auditoria');
Route::get('/all_auditoria/{id_user?}', 'AuditoriaController@all_auditoria')->middleware('menu.permission:148');
Route::get('/all_auditoria_hospi/{id_user?}', 'AuditoriaController@all_auditoria_hospi')->middleware('menu.permission:182');
Route::get('/all_auditoria_ambulatorio/{id_user?}', 'AuditoriaController@all_auditoria_ambulatorio')->middleware('menu.permission:181');
Route::get('editar_auditoria/{id}', 'AuditoriaController@editarAuditoria')->name('editar_auditoria');
Route::get('/envio_masivo_1a1', 'AuditoriaController@envio_masivo_1a1');
Route::get('/all_auditoria_vista_mejorada/{id_user?}', 'AuditoriaController@vista_mejorada')->middleware('menu.permission:185');
Route::get('/all_auditoria_vista_reportes/{id_user?}', 'AuditoriaController@vista_reporte')->middleware('menu.permission:186');
Route::get('/exportar_reporte_auditoria_mejorado/{anio}/{mes}/{tipo_atencion_detalle}/{ipress}/{estado_trama}/{cod_paquete}/{resultado}', 'AuditoriaController@exportar_reporte_auditoria_mejorado');
Route::get('/reglas_aplicadas/{id_cups}', 'AuditoriaController@reglas_aplicadas');
//Route::get('/estados_tramaprestacion_grafico', 'AuditoriaController@estadosTramaPrestacionGrafico');
Route::post('validar_ws_SaludPol', ['uses' => 'AuditoriaController@validar_ws_SaludPol','as' => 'validar_ws_SaludPol']);
Route::get('/ver_datos_enviados/{id_trama}', 'AuditoriaController@ver_datos_enviados');
Route::get('/hospitalizacion_admision/{id_orden_hospitalizacion?}', 'HospitalizacionController@hospitalizacion_admision');
Route::get('/hospitalizacion_admision/verificarExisteHospitalizacionOrden/{id_asegurado}', 'HospitalizacionController@verificarExisteHospitalizacionOrden');
Route::get('/obtener_ids_ubigeo/{departamento_nombre}/{provincia_nombre}/{distrito_nombre}', 'DistritoController@obtener_ids_ubigeo');
Route::post('actualizar_auditoria/{id}', 'AuditoriaController@actualizarAuditoria')->name('actualizar_auditoria');
Route::match(['get', 'post'], '/estados_trama_prestacion_grafico', 'AuditoriaController@estadosTramaPrestacionGrafico')->name('estados_trama_prestacion_grafico');
Route::get('/ver_atenciones_auditoria/{id}/{estado}/{id_tipo_atencion}', 'AuditoriaController@ver_atenciones');
Route::get('/ver_prestacion_auditoria/{id_user}/{id_prestacion}', 'AuditoriaController@ver_prestacion');
Route::get('/mostrar_evolucion_auditoria/{id_atencion}/{id_tipo_atencion}', 'AuditoriaController@mostrar_evolucion');
Route::get('/mostrar_evaluacion_auditoria/{id}/{id_tipo_atencion}', 'AuditoriaController@mostrar_evaluacion');
Route::get('/mostrar_atencion_hospitalizacion_auditoria/{id}', 'AuditoriaController@mostrar_atencion_hospitalizacion');
Route::post('incluir_productos_trama', 'AuditoriaController@saveIncluirProductosTrama')->name('incluir_productos_trama');
Route::post('/api/auditoria/validacion-masiva', 'AuditoriaController@validarTramasMasivas')->name('api.auditoria.validacion-masiva');
/////-----formato fups-------

Route::get('/pdf_formato_fups/{id_prestacion}', 'ReferenciasController@pdf_formato_fups');
Route::get('/pdf_formato_fups_vinculante_receta/{id_prestacion}', 'ReferenciasController@pdf_formato_fups_vinculante_receta');
/////////--------reporte mensaje texto cita -----
Route::get('/exportar_reporte_para_callcenter/{idUser}/{fecha_ini}/{fecha_fin}/{fecha_reg_ini}/{fecha_reg_fin}/{dni}/{paciente}/{estado}/{id_servicio}/{id_consultorio}/{id_medico}/{id_condicion_establecimiento}/{id_condicion_servicio}', 'ExcelController@exportar_reporte_para_callcenter');

///Actualiza siteds receta_vale
Route::get('/modal_siteds_ambulatorio/{id}', 'RecetaValeController@modal_siteds_ambulatorio');
Route::post('update_siteds_receta', [
  'uses' => 'RecetaValeController@update_siteds_receta',
  'as' => 'update_siteds_receta'
]);
Route::get('/validar_dato_siteds_evaluacion/{cod_siteds}', 'EvaluacionController@validar_dato_siteds_evaluacion');
Route::get('/buscar_siteds_atencion/{fecha_atencion}/{dni_beneficiario}', 'CitaController@buscar_siteds_atencion');
//Sistema de Colas
Route::get('/obtener_sub_consultorio_para_tv/{id_establecimiento}/{id_servicio}/{id_tv}', 'TvController@obtenerSubConsultorioParaTv');
Route::get('/registrar_sub_consultorio_para_tv/{id_tv}/{id_sub_consultorio}', 'TvController@registrarSubConsultorioParaTv');
Route::get('/delete_tv_sub_consultorio/{id_tv_sub_consultorio}', 'TvController@deleteTvSubConsultorio');

//MESA DE PARTES
Route::get('/mesa/solicitud_requerimiento', 'MesaController@FormSolicitudRequerimientoView');
Route::post('solicitud_requerimiento_transaccion',[
  'uses' => 'MesaController@GenerarSolicitudRequerimiento',
  'as' => 'solicitud_requerimiento_transaccion'
]);
Route::get('/mesa/all_solicitudes_requerimiento', 'MesaController@ListadoSolicitudesRequerimiento');
Route::post('listar_solicitudes_paginadas', [
  'uses' => 'MesaController@listar_solicitudes_paginadas',
  'as' => 'listar_solicitudes_paginadas'
]);
Route::get('/mesa/requerimiento/{id_solicitud}', 'MesaController@DetailSolicitudRequerimientoView');
Route::get('/mesa/buscar_by_dni_reniec/{dni}', 'MesaController@GetDataOfReniecWsByDni');
Route::post('/mesa/buscar_by_dni_dirrehum/{dni}', 'MesaController@GetDataOfDirrehumWsByDni');
Route::get('/mesa/buscar_by_dni_database/{dni}', 'MesaController@GetDataOfDatabaseByDni');
Route::post('/mesa/genera_csv_requerimiento', 'MesaController@genera_csv_requerimiento');
Route::get('/mesa/pdf/{id_req}', 'MesaController@ver_requerimiento_pdf');
Route::post('/mesa/actualiza_firma_requerimiento', 'MesaController@actualizar_requerimiento')->name('actualizar_requerimiento');
// Hospitalizacion epicrisis
Route::get('/print_hospitalizacion_epicrisis/{id_hospitalizacion}', 'PdfController@print_hospitalizacion_epicrisis');
//Actualizar Prestacion
Route::get('/edit_prestacion/{id_prestacion}', 'RecetaValeController@edit_prestacion');
Route::post('editar_prestacion', [
  'uses' => 'RecetaValeController@editar_prestacion',
  'as' => 'editar_prestacion'
]);
Route::get('/ver_prestacion_modificada/{id_prestacion}', 'RecetaValeController@ver_prestacion_modificada');
Route::post('genera_csv_modificacion', [
  'uses' => 'ExcelController@genera_csv_modificacion',
  'as' => 'genera_csv_modificacion'
]);
////////////////////////////----------------------------ENFERMERIA ---------------------
Route::get('/hospitalizado_by_enfermeria/{iduser?}', 'HospitalizacionController@hospitalizado_by_enfermeria');
Route::post('hospitalizado_by_enfermeria_ajax', [
  'uses' => 'HospitalizacionController@hospitalizado_by_enfermeria_ajax',
  'as' => 'hospitalizado_by_enfermeria_ajax'
]);

Route::get('/parametro/CATEGORIA_EV_ADV/{descrip_tipo_parametro}', 'ParametrosController@listar_parametros_by_tipo');
Route::get('/parametro/listar_tipo_parametro_detalle/{id_parametro}', 'ParametrosController@listar_parametros_by_id');
Route::get('/parametro/listar_tipo_parametro/{buscar}/{descrip_tipo_parametro}', 'ParametrosController@listar_parametros_by_tipo_autocomplete');
Route::get('/pintar_puntos_monitoreo/{id_atencion}/{tipo_registro}/{id_tipo_atenciones}', 'PlanoMonitoreoController@pintarPuntosMonitoreo');
Route::get('/destroy_pintar_puntos_monitoreo/{punto_id}', 'PlanoMonitoreoController@destroyPuntosMonitoreo');

//////////nuevas rutas
Route::get('/plano', 'PlanoController@index');
Route::post('/plano_agregar', 'PlanoController@agregarPunto');
Route::get('/pintar_puntos/{id_atencion}/{tipo_registro}/{id_tipo_atenciones}', 'PlanoController@pintarPuntos');
Route::get('/destroy_pintar_puntos/{punto_id}', 'PlanoController@destroyPuntos');
Route::post('/plano_agregar_monitoreo', 'PlanoMonitoreoController@agregarPuntoMonitoreo');

Route::get('/ver_nota_ingreso_enfermeria/{id_nota_ingreso}/{id_tipo_atenciones}', 'Nota_enfermeriaController@ver_nota_ingreso_enfermeria');

// ver notA _INGRESO PARA EMERGENCIA

Route::get('/ver_nota_ingreso_enfermeria_emergencia/{id_nota_ingreso}/{id_tipo_atenciones}', 'Nota_enfermeriaController@ver_nota_ingreso_enfermeria_emergencia');
Route::get('/ver_nota_ingreso_enfermeria_hospitalizacion/{id_nota_ingreso}/{id_tipo_atenciones}', 'Nota_enfermeriaController@ver_nota_ingreso_enfermeria_hospitalizacion');

Route::get('/hospitalizado_by_enfermeria/{iduser?}', 'HospitalizacionController@hospitalizado_by_enfermeria');



// SE USA PARA EL LISTADO DE DATAS COMO FECHA_INGRESO , SERVICIO _NRO DOCUMENTO , ETC
Route::post('emergencia_by_enfermeria_ajax', [
  'uses' => 'EmergenciaController@emergencia_by_enfermeria_ajax',
  'as' => 'emergencia_by_enfermeria_ajax'
]);

Route::post('hospitalizacion_by_enfermeria_ajax', [
  'uses' => 'HospitalizacionController@hospitalizacion_by_enfermeria_ajax',
  'as' => 'hospitalizacion_by_enfermeria_ajax'
]);

Route::get('/emergencia_by_enfermeria/{iduser?}', 'EmergenciaController@emergencia_by_enfermeria');

Route::get('/hospitalizacion_by_enfermeria', 'HospitalizacionController@hospitalizacion_by_enfermeria');
Route::get('/buscar_asegurado_id/{id_asegurado}', 'PacienteController@buscar_asegurado_id');

// MODIFICAR LA DATA PARA REGISTRAR EN EMERGENCIA AQUI JALA LA DATA
Route::get('/formulario_nota_ingreso/{id_atencion}/{id_tipo_atenciones}', 'Nota_enfermeriaController@formulario_nota_ingreso');

Route::post('registrar_nota_ingreso_hospitalizado', [
  'uses' => 'Nota_enfermeriaController@registrar_nota_ingreso_hospitalizado',
  'as' => 'registrar_nota_ingreso_hospitalizado'
]);
Route::get('/formulario_nota_ingreso_emergencia/{id_emergencia}', 'EmergenciaController@formulario_nota_ingreso_emergencia');
Route::get('/formulario_nota_ingreso_emergencia/{id_atencion}/{id_tipo_atenciones}', 'Nota_enfermeriaController@formulario_nota_ingreso_emergencia');
Route::get('/formulario_nota_ingreso_hospitalizacion/{id_hospitalizacion}/{id_tipo_atenciones}', 'Nota_enfermeriaController@formulario_nota_ingreso_hospitalizacion');


// EMERGENCIA REGISTRAR NOTA 
Route::post('registrar_nota_ingreso_emergencia', [
  'uses' => 'Nota_enfermeriaController@registrar_nota_ingreso_emergencia',
  'as' => 'registrar_nota_ingreso_emergencia'
]);

Route::get('/pdf_nota_ingreso_enfermeria/{id_nota_ingreso}/{id_tipo_atenciones}', 'Nota_enfermeriaController@pdf_nota_ingreso_enfermeria');
Route::get('/pdf_nota_ingreso_enfermeria_emergencia/{id_nota_ingreso}/{id_tipo_atenciones}', 'Nota_enfermeriaController@pdf_nota_ingreso_enfermeria_emergencia');
Route::get('/pdf_nota_ingreso_enfermeria_hospitalizacion/{id_nota_ingreso}/{id_tipo_atenciones}', 'Nota_enfermeriaController@pdf_nota_ingreso_enfermeria_hospitalizacion');

Route::post('registrar_nota_ingreso_emergencia', [
  'uses' => 'Nota_enfermeriaController@registrar_nota_ingreso_emergencia',
  'as' => 'registrar_nota_ingreso_emergencia'
]);

Route::post('registrar_nota_ingreso_hospitalizacion', [
  'uses' => 'Nota_enfermeriaController@registrar_nota_ingreso_hospitalizacion',
  'as' => 'registrar_nota_ingreso_hospitalizacion'
]);

// LISTAR CAMAS EMERGENCIAS
Route::get('/getAseguradoby_id/{id_asegurado}', 'PacienteController@getAseguradoby_id');
Route::get('/getAseguradoby_id_withSP/{id_enfermeria}', 'PacienteController@getAseguradoby_id_withSP');
Route::get('/getAseguradoby_id_withSP_sa/{id_enfermeria}', 'PacienteController@getAseguradoby_id_withSP_sa');
Route::get('/getAseguradoby_id_withSPforHospi/{id_enfermeria}', 'PacienteController@getAseguradoby_id_withSPforHospi');
Route::get('/rotacion_cama_emergencia/{id_user}/{nro_hospitalizacion}', 'EmergenciaController@rotacion_cama_emergencia');
Route::get('/rotacion_cama_hospitalizacion/{id_user}/{nro_hospitalizacion}', 'HospitalizacionController@rotacion_cama_hospitalizacion');

//MODAL HISTORIAL CAMAS PARA EMERGENCIA

Route::get('/modal_historial_cama_emergencia/{id_emergencia}', 'EmergenciaController@modal_historial_cama_emergencia');


//funcioN PARA TRAER EL HISTORIAL DE EMERGENCIA DE CAMAS 
Route::post('rotacion_cama_emergencia_historial', [
  'uses' => 'EmergenciaController@rotacion_cama_emergencia_historial',
  'as' => 'rotacion_cama_emergencia_historial'
]);

// INICIANDO EVENTOS ADVERSOS PARA HOSPITALIZACIONES
Route::post('lista_incidencias_eventos_a','FormatoIncidenteEventoAdversoController@listaFormatoIncidenciaEventoA');
//Route::get('tipo_atenciones','FormatoIncidenteEventoAdversoController@tipoAtenciones');
Route::post('incidente_diagnosticos', 'FormatoIncidenteEventoAdversoController@getDiagnosticosByIdPacienteAndIdHospitalizacion');
Route::get('/parametro/personal_convocado/{buscar_medico}', 'ParametrosController@listar_personal_convocado_by_medico');
Route::post('/ver_incidencia', 'FormatoIncidenteEventoAdversoController@ver_incidencia');
Route::get('/pdf_formato_incidencia_evento_adverso/{id_incidente}', 'FormatoIncidenteEventoAdversoController@pdf_formato_incidencia_evento_adverso');

Route::get('/obtener_medicina/{buscar}', 'ProductoController@search_tipo_medicina');
Route::post('/lista_alergias_medicas_filtro', 'PacienteMedicamentoAlergiasController@listaAlergiasMedicasByFiltro');
Route::post('/lista_alergias_medicas_filtro_V2', 'PacienteMedicamentoAlergiasController@listaAlergiasMedicasByFiltroV2');
Route::post('/registrar_medicamento_alergias', 'PacienteMedicamentoAlergiasController@registrarAlergia');
Route::post('/registrar_medicamento_alergias_v2', 'PacienteMedicamentoAlergiasController@registrarAlergiaV2');
Route::post('/validar_alergias_medicas', 'PacienteMedicamentoAlergiasController@validarAlergiasMedicas');
Route::post('/ver_alergias_medicas', 'PacienteMedicamentoAlergiasController@verAlergiasMedicasById');
Route::post('/actualizar_medicamento_alergias', 'PacienteMedicamentoAlergiasController@actualizar');

// ACTUALIZANDO RUTAS DE EVENTOS ADVERSOS SOLAMENTE PARA EMERGENCIAS

Route::post('lista_incidencias_eventos_a_emergencias','FormatoIncidenteEventoAdversoController@listaFormatoIncidenciaEventoA_Emergencias');
Route::post('incidente_diagnosticos_emergencia', 'FormatoIncidenteEventoAdversoController@getDiagnosticosByIdPacienteAndId_Emergencia');
Route::post('registrar_formato_incidente','FormatoIncidenteEventoAdversoController@registrarFormato');
Route::get('/parametro/listar_tipo_parametro/{descrip_tipo_parametro}', 'ParametrosController@listar_parametros_by_tipo');
Route::post('/ver_incidencia_emergencia', 'FormatoIncidenteEventoAdversoController@ver_incidencia_emergencia');
Route::post('actualizar_formato_incidente','FormatoIncidenteEventoAdversoController@actualizarFormato');
Route::get('/pdf_formato_incidencia_evento_adverso_emergencia/{id_incidente}', 'FormatoIncidenteEventoAdversoController@pdf_formato_incidencia_evento_adverso_emergencia');

//EVOLUCIONES ENFERMERIA

Route::post('cargar_mas_historiaL_invasivos', [
  'uses' => 'EnfermeriaEvolucionesController@cargarMasHistorialInvasivos',
  'as' => 'cargarMasHistorialInvasivos'
]);
Route::post('lista_nota_ingreso_evoluciones', [
  'uses' => 'EnfermeriaEvolucionesController@lista_nota_ingreso_evoluciones',
  'as' => 'lista_nota_ingreso_evoluciones'
]);

Route::post('lista_nota_ingreso_cpt', [
  'uses' => 'CptEnfermeriaController@lista_nota_ingreso_cpt',
  'as' => 'lista_nota_ingreso_cpt'
]);
Route::post('/datos_globales_evoluciones', 'EnfermeriaEvolucionesController@datosGlobales');
Route::post('/datos_globales_evoluciones_hospi', 'EnfermeriaEvolucionesController@datosGlobalesHospi');
Route::post('/datos_globales_evoluciones_v2', 'CptEnfermeriaController@datosGlobales');
Route::post('anular_item_procedimiento_enfermeria', [
  'uses' => 'EnfermeriaEvolucionesController@anularItemProcedimientoEnfermeria',
  'as' => 'anularItemProcedimientoEnfermeria'
]);
Route::post('anular_item_procedimiento_enfermeria_cpt', [
  'uses' => 'CptEnfermeriaController@anularItemProcedimientoEnfermeriaCpt',
  'as' => 'anularItemProcedimientoEnfermeriaCpt'
]);
Route::post('anular_item_evoluciones_detalle', [
  'uses' => 'EnfermeriaEvolucionesController@anularItemEvolucionDetalle',
  'as' => 'anularItemEvolucionDetalle'
]);
Route::get('/ver_historial_invasivos','EnfermeriaEvolucionesController@verHistorialInvasivos');
Route::post('/evolucion_firmar_por_turno/{evolucion_id}/{id_tipo_atencion?}','EnfermeriaEvolucionesController@evolucionesPDFfirmar');
Route::post('/cpt_firmar_por_turno/{cpt_id}/{id_tipo_atencion?}','CptEnfermeriaController@cptPDFfirmar');
Route::post('/evolucion_post_arguments', 'EnfermeriaEvolucionesController@postArgumentsEvoluciones')->name('evolucion_post_arguments');
Route::post('/actualiza_datos_firma_con_token_evolucion','EnfermeriaEvolucionesController@actualizarFirmaConTokenEvoluciones')->name('actualiza_datos_firma_con_token_evolucion');
Route::post('/actualiza_datos_firma_con_token_cpt','CptEnfermeriaController@actualizarFirmaConTokenCpt')->name('actualiza_datos_firma_con_token_cpt');
Route::post('/actualiza_datos_firma_sin_token_evolucion','EnfermeriaEvolucionesController@actualizarFirmaSinTokenEvoluciones')->name('actualiza_datos_firma_sin_token_evolucion');
//registrar_nota_ingreso_evoluciones
Route::post('registrar_nota_ingreso_evoluciones', [
  'uses' => 'EnfermeriaEvolucionesController@registrar_nota_ingreso_evoluciones',
  'as' => 'registrar_nota_ingreso_evoluciones'
]);

Route::post('registrar_nota_ingreso_cpt', [
  'uses' => 'CptEnfermeriaController@registrar_nota_ingreso_cpt',
  'as' => 'registrar_nota_ingreso_cpt'
]);

Route::post('actualizar_nota_ingreso_evoluciones', [
  'uses' => 'EnfermeriaEvolucionesController@actualizar_nota_ingreso_evoluciones',
  'as' => 'actualizar_nota_ingreso_evoluciones'
]);
Route::post('actualizar_cpt_cpms', [
  'uses' => 'CptEnfermeriaController@actualizar_cpt_cpms',
  'as' => 'actualizar_cpt_cpms'
]);
Route::get('/pdf_nota_ingreso_evoluciones/{id_evoluciones}', 'EnfermeriaEvolucionesController@pdf_nota_ingreso_evoluciones');
Route::get('/pdf_nota_ingreso_cpt/{id_cpt}', 'CptEnfermeriaController@pdf_nota_ingreso_cpt');
Route::post('ver_nota_ingreso_evoluciones', [
  'uses' => 'EnfermeriaEvolucionesController@ver_nota_ingreso_evoluciones',
  'as' => 'ver_nota_ingreso_evoluciones'
]);
Route::post('ver_nota_ingreso_cpt', [
  'uses' => 'CptEnfermeriaController@ver_nota_ingreso_cpt',
  'as' => 'ver_nota_ingreso_cpt'
]);
Route::post('cargar_mas_historiaL_invasivos', [
  'uses' => 'EnfermeriaEvolucionesController@cargarMasHistorialInvasivos',
  'as' => 'cargarMasHistorialInvasivos'
]);
Route::post('lista_nota_ingreso_evoluciones', [
  'uses' => 'EnfermeriaEvolucionesController@lista_nota_ingreso_evoluciones',
  'as' => 'lista_nota_ingreso_evoluciones'
]);
Route::post('/datos_globales_evoluciones', 'EnfermeriaEvolucionesController@datosGlobales');
Route::post('anular_item_procedimiento_enfermeria', [
  'uses' => 'EnfermeriaEvolucionesController@anularItemProcedimientoEnfermeria',
  'as' => 'anularItemProcedimientoEnfermeria'
]);
Route::post('anular_item_evoluciones_detalle', [
  'uses' => 'EnfermeriaEvolucionesController@anularItemEvolucionDetalle',
  'as' => 'anularItemEvolucionDetalle'
]);
Route::get('/ver_historial_invasivos','EnfermeriaEvolucionesController@verHistorialInvasivos');
Route::post('/evolucion_firmar_por_turno/{evolucion_id}','EnfermeriaEvolucionesController@evolucionesPDFfirmar');
Route::post('/evolucion_post_arguments', 'EnfermeriaEvolucionesController@postArgumentsEvoluciones')->name('evolucion_post_arguments');
Route::post('/cpt_post_arguments', 'CptEnfermeriaController@postArgumentsCpt')->name('cpt_post_arguments');
Route::post('/actualiza_datos_firma_con_token_evolucion','EnfermeriaEvolucionesController@actualizarFirmaConTokenEvoluciones')->name('actualiza_datos_firma_con_token_evolucion');
Route::post('/actualiza_datos_firma_sin_token_evolucion','EnfermeriaEvolucionesController@actualizarFirmaSinTokenEvoluciones')->name('actualiza_datos_firma_sin_token_evolucion');

Route::get('/buscar_registro_emergencia/{id_emergencia}', 'MonitoreoEnfermeriaController@buscar_registro_emergencia');
Route::get('/buscar_ptje_glasgow_nota/{id_nota}', 'MonitoreoEnfermeriaController@buscar_ptje_glasgow_nota');


Route::get('/diagnosticos_pacientes_prestacion/{id_prestacion}', 'EnfermeriaEvolucionesController@diagnosticos_pacientes_prestacion');//PROCEDIMIENTOS DE LA PRESTACION
Route::get('/diagnosticos_pacientes_prestacion_combo/{id_prestacion}', 'EnfermeriaEvolucionesController@diagnosticos_pacientes_prestacion_combo');//DIAGNOSTICOS PARA EL COMBO EN LALISTA DE PROCEDIMIENTOS
Route::get('tipo_atenciones','Nota_enfermeriaController@tipoAtenciones');//TIPO DE FUNCIONES PARA TODOS LOS TIPOS DE ATENCION
Route::get('turnos','Nota_enfermeriaController@turnos');//TIPO DE FUNCIONES PARA TODOS LOS TIPOS DE ATENCION

//========= Plano para Puntos en Evolucion
Route::post('/plano_agregar_evolucion', 'EnfermeriaEvolucionesController@agregarPuntos');
Route::get('/pintar_puntos_evolucion/{id_enfermeria}/{id_tipo_atenciones}', 'EnfermeriaEvolucionesController@pintarPuntosEvolucion');
Route::get('/destroy_pintar_puntos_evolucion/{punto_id}', 'EnfermeriaEvolucionesController@destroyPuntos');
Route::get('/invasivos', 'EnfermeriaEvolucionesController@invasivos');
Route::post('/motivo_anulacion_evolucion', 'EnfermeriaEvolucionesController@motivoAnulacionEvolucion');
Route::post('/motivo_anulacion_cpt', 'CptEnfermeriaController@motivoAnulacionCpt');
Route::get('/lista_invasivos_historial_atencion/{id_atencion}', 'EnfermeriaEvolucionesController@listaInvasivosIdAtencion');
Route::get('/lista_invasivos_historial_evolucion/{id_evolucion}', 'EnfermeriaEvolucionesController@listaInvasivosIdEvolucion');
Route::get('/editar_nota_ingreso_enfermeria_emergencia/{id_nota_ingreso}/{id_tipo_atenciones}', 'Nota_enfermeriaController@editar_nota_ingreso_enfermeria_emergencia');
Route::post('/update-nota-enfermeria', 'Nota_enfermeriaController@updateNotaIngreso');
Route::post('/firmar-nota', 'Nota_enfermeriaController@FirmarPDFEnfermeria');
Route::post('/firmar-signos', 'SignosVitalesController@FirmarPDFSignos');


/* =============== MONITOREO ENFERMERIA ================ */
Route::post('/registrar_monitoreo_paciente', 'MonitoreoEnfermeriaController@registrar_monitoreo_paciente');

//probando

/* Route::get('/insertando_valores', [ValorController::class, 'create']);
Route::post('/insertando_valores', [ValorController::class, 'store'])->name('valores.store'); */
//SIGNOS VITALES 
//Route::get('/signosVitales_Global/{id_asegurado}', 'SignosVitalesController@signosVitales_Global');
//OBTENER SIGNOS VITALES POR ID ASEGURADO
// guardando signos vitales
//Route::post('/guardarSignosVitales', [SignosVitalesController::class, 'guardarSignosVitales']);

Route::get('/getDatosPacienteForEnfermeria/{id_asegurado}', 'Nota_enfermeriaController@getDatosPacienteForEnfermeria');

Route::post('/guardarSignosVitales', 'SignosVitalesController@guardarSignosVitales');
Route::get('/insertando_valores', [ValorController::class, 'create']);
Route::post('/insertando_valores', [ValorController::class, 'store'])->name('valores.store');

//BALANCE HIDRICO
Route::post('/datos_globales_balance', 'EnfermeriaBalanceHidricoController@datosGlobales');
Route::get('/datos_globales_evol/{id_asegurado}', 'EnfermeriaEvolucionesController@getDatosPacienteAjax');
Route::get('/peso_desde_nota/{id_nota}', 'EnfermeriaBalanceHidricoController@getPesoDesdeNotaEnfermeria');
Route::post('registrar_balance_hidrico', [
  'uses' => 'EnfermeriaBalanceHidricoController@registrarBalanceHidrico',
  'as' => 'registrar_balance_hidrico'
]);
Route::post('listar_balance', [
  'uses' => 'EnfermeriaBalanceHidricoController@listar',
  'as' => 'listar'
]);
Route::post('hoja_balance_hidrico', [
  'uses' => 'EnfermeriaBalanceHidricoController@hojaBalanceHidrico',
  'as' => 'hojaBalanceHidrico'
]);
Route::post('actualizar_balance_hidrico', [
  'uses' => 'EnfermeriaBalanceHidricoController@actualizarBalanceHidrico',
  'as' => 'actualizarBalanceHidrico'
]);
Route::get('/pdf_balance_hidrico/{id_balance}', 'EnfermeriaBalanceHidricoController@pdf_balance_hidrico');
Route::get('/pdf_balance_hidrico_hospi/{id_balance}', 'EnfermeriaBalanceHidricoController@pdf_balance_hidrico_hospi');
Route::get('/pdf_balance_hidrico_turno/{id_balance}', 'EnfermeriaBalanceHidricoController@pdf_balance_hidrico_turno');
Route::post('anular_item_balance_detalle', [
  'uses' => 'EnfermeriaBalanceHidricoController@anularItemBalanceDetalle',
  'as' => 'anularItemBalanceDetalle'
]);
Route::post('/bh_firmar_por_turno/{bh_id}/{id_tipo_atencion?}', 'EnfermeriaBalanceHidricoController@balanceHidricoPDFfirmar');
Route::post('/bh_post_arguments', 'EnfermeriaBalanceHidricoController@postArguments')->name('bh_post_arguments');
Route::post('/actualiza_datos_firma_con_token', 'EnfermeriaBalanceHidricoController@actualizarFirmaConToken')->name('actualiza_datos_firma_con_token');
Route::post('actualiza_datos_firma_sin_token', ['uses' => 'EnfermeriaBalanceHidricoController@actualizarFirmaSinToken','as' => 'actualiza_datos_firma_sin_token']);

Route::get('/intervencion_terapeuticas_ajax', 'EnfermeriaBalanceHidricoController@intervencionTerapeuticasAJax');







Route::get('/buscar_datos_prestacion_enfermeria/{id_prestacion}', 'EnfermeriaController@buscar_datos_prestacion_enfermeria');

Route::group(['prefix' => 'api'], function() {
  Route::group(['prefix' => 'asegurado-historias'], function() {
    Route::post('/actualizar', 'AseguradoHistoriasController@actualizarNroHistoria');
    Route::post('/aperturar', 'AseguradoHistoriasController@aperturarNroHistoria');
    Route::get('/asegurado/{asegurado_id}/establecimiento/{establecimiento_id}', 'AseguradoHistoriasController@getHistoriaByAseguradoIdAndEstablecimientoId');
  });
    
  Route::group(['prefix' => 'turnos'], function() {
    Route::get('/', "TurnosController@getAllApi");
  });

  Route::group(['prefix' => 'diagnoticos'], function() {
    Route::get('/', "TurnosController@getAllApi");
  });

  Route::group(['prefix' => 'consultorios'], function() {
    Route::get('/search', "ConsultorioController@searchByFilters");
  });

  Route::group(['prefix' => 'cambio-cama'], function() {
    Route::get('/prestacion/{prestacion_id}', "CambioCamasController@getByPrestacionId");
    Route::get('/ext-id/{id_ext}', "CambioCamasController@getByExtId");
  });

  //Route::put('/actualizarEstadoFirmaDeNotaDeEnfermeria/{enfermeria_id}', 'Nota_enfermeriaController@ActualizarFirmaEnfermeria');
  //Route::put('/actualizarEstadoFirmaDeSignosVitales/{enfermeria_id}', 'SignosVitalesController@ActualizarFirmaSignosConsolidado');
  Route::post('/actualizarEstadoFirmaDeNotaDeEnfermeria/{enfermeria_id}', 'Nota_enfermeriaController@ActualizarFirmaEnfermeria');
  Route::post('/postFirmaSV/{enfermeria_id}', 'SignosVitalesController@ActualizarFirmaSignosConsolidado');

  Route::group(['prefix' => 'kardex-enfermerias'], function() {
    Route::post('/', "kardexEnfermeriasController@create");
    Route::get('/search', "kardexEnfermeriasController@searchByFilters");
    Route::get('/search-hospi', "kardexEnfermeriasController@searchByFiltersHospi");
    Route::get('/{id}', "kardexEnfermeriasController@getById");
    Route::get('/full/{id}', "kardexEnfermeriasController@getFullById");
    Route::get('/full-hospi/{id}', "kardexEnfermeriasController@getFullByIdHospi");
    Route::get('/detalle/{id}/{tipo_atencion_id}', "kardexEnfermeriasController@getDetalleKardex");
    Route::get('/emergencia/{emergenciaId}', "kardexEnfermeriasController@getAllByEmergenciaId");
    Route::put('/{id}', "kardexEnfermeriasController@update");
    Route::put('/change-estado/{id}', "kardexEnfermeriasController@updateEstado");
    Route::get('/pdf/{id}', 'PdfController@generatePdfKardexEnfermeria');
    Route::get('/pdf/hospi/{id}', 'PdfController@generatePdfKardexEnfermeriaHospi');
    Route::post('/pdf-firmar/{kardex_enfermeria_id}/{fecha_hora}/{id_tipo_atencion?}', 'kardexEnfermeriasController@firmarPdf');
    Route::post('/registrar-firma', 'kardexEnfermeriasController@registrarFirma');
  });

  Route::group(['prefix' => 'via-administracion'], function() {
    Route::get('/', "ViaAdministracionController@getAll");
  });

  Route::group(['prefix' => 'productos'], function() {
    Route::get('/buscar', "ProductoController@search");
  });

  Route::group(['prefix' => 'procedimientos'], function() {
    Route::get('/prestacion/{prestacionId}/tipo-procedimiento/{tipoProcedimiento}', "ProcedimientoController@getByPrestacionIdAndTipo");
  });

  Route::group(['prefix' => 'infusiones-enfermerias'], function() {
    Route::get('/', "InfusionEnfermeriaController@getAll");
    Route::get('/buscar', "InfusionEnfermeriaController@buscar");
  });

  Route::group(['prefix' => 'kardex-medicamentos-enfermerias'], function() {
    Route::get('/kardex-enfermerias/{id_kardex_enfermeria}', "KardexMedicamentoEnfermeriaController@getByidKardexEnfermeria");
    Route::put('update-array', "KardexMedicamentoEnfermeriaController@updateArray");
    Route::put('/cambiar-estado/{id}', "KardexMedicamentoEnfermeriaController@changeState");
  });

  Route::group(['prefix' => 'horarios-kardex-medicamentos'], function() {
    Route::get('/kardex_medicamentos_enfermerias/{id_medicamento}', "HorarioKardexMedicamentoController@getByMedicamentoId");
    Route::put('/update-array', "HorarioKardexMedicamentoController@updateArrayByMedicamento");
  });

  Route::group(['prefix' => 'interconsultas'], function() {
    Route::get('/consultorios/prestacion/{prestacionId}', "InterconsultasController@getConsultorioByPrestacionId");
  });

  Route::group(['prefix' => 'enfermerias'], function() {
    Route::get('/{id}/cabecera', "EnfermeriaController@obtenerCabeceraById");
  });

  Route::group(['prefix' => 'procedimientos-diagnoticos'], function() {
    Route::get('/prestacion/{prestacionId}/tipo-procedimiento/{tipoProcedimiento}', "ProcedimientoDiagnoticoController@getByPrestacionIdAndTipo");
  });
  
  Route::group(['prefix' => 'procedimientos'], function() {
    Route::get('/search/tipo-procedimiento/{tipo_procedimiento}', "ProcedimientoController@searchByTipoProcedimientoAndText");
  });
  
  Route::group(['prefix' => 'laboratorio'], function() {
    Route::get('/buscar-siteds-generado-hoy/{asegurado_dni}/{tipo_atencion_id}/{id_press}', "LaboratorioController@obtenerSitedsGeneradoHoy");
  });
});

////////// Enfermeria - jhon
Route::post('lista_pcientes_enfermeria', [
  'uses' => 'EnfermeriaController@lista_pcientes_enfermeria',
  'as' => 'lista_pcientes_enfermeria'
]);

Route::get('/emergencia_by_enfermeria_listado/{iduser?}', 'EnfermeriaController@emergencia_by_enfermeria_listado');


Route::post('/enfermeria/registrar-rotacion-camas', 'EnfermeriaController@registrar_rotacion_cama');
Route::post('/enfermeria/registrar-rotacion-camas-hospi', 'EnfermeriaController@registrar_rotacion_cama_hospi');
Route::get('/signosVitalesAsegurado/{id_asegurado}', 'SignosVitalesController@obtenerSignoVitalPorAsegurado');
Route::post('/RegistrarDetalleDeMonitoreo', 'MonitoreoEnfermeriaController@RegistrarDetalleDeMonitoreo');
Route::group(['prefix' => 'api/monitoreo'], function() {
  Route::get('/listarMonitoreoDetalles', 'MonitoreoEnfermeriaController@listarMonitoreoDetalles');
  Route::get('/listarMonitoreoDetalles_hospi', 'MonitoreoEnfermeriaController@listarMonitoreoDetalles_hospi');
  Route::get('/buscarMonitoreoParaEditar/{id_monitoreo}/{fecha_hora}', 'MonitoreoEnfermeriaController@buscarMonitoreoParaEditar');
  Route::put('/editarMonitoreo', 'MonitoreoEnfermeriaController@editarMonitoreo');
  Route::get('/detalleMonitoreo/{id_monitoreo}/{fecha_hora}', 'MonitoreoEnfermeriaController@detalleMonitoreo');
  Route::get('/exportarMonitoreo/{id_monitoreo}/{fecha_hora}/{id_tipo_atencion?}', 'MonitoreoEnfermeriaController@exportarMonitoreo');
  Route::post('/exportarMonitoreoParaFirmar/{id_monitoreo}/{fecha_hora}/{id_tipo_atencion?}', 'MonitoreoEnfermeriaController@exportarMonitoreoParaFirmar');
  Route::get('/exportarMonitoreoPorDia/{id_monitoreo}/{fecha_hora}/{id_tipo_atencion?}', 'MonitoreoEnfermeriaController@exportarMonitoreoPorDia');
});
Route::get('/signos_vitales_listado/{id_asegurado}/{id_enfermeria}', 'SignosVitalesController@signos_vitales_listado');
Route::get('/signos_vitales_listado_hospi/{id_asegurado}/{id_enfermeria}', 'SignosVitalesController@signos_vitales_listado_hospi');

Route::post('guarda_signos_vitales_vista', [
  'uses' => 'SignosVitalesController@guarda_signos_vitales_vista',
  'as' => 'guarda_signos_vitales_vista'
]);

Route::get('/ver_pdf_signos_vitales/{id_asegurado}/{id_enfermeria}', 'SignosVitalesController@ver_pdf_signos_vitales');
Route::get('/ver_pdf_signos_vitales_hospi/{id_asegurado}/{id_enfermeria}', 'SignosVitalesController@ver_pdf_signos_vitales_hospi');
Route::get('/editar_signovital/{id_signo_vital}/{id_tipo_atencion?}', 'SignosVitalesController@editar_signo_vitales');

Route::post('guardar_edicion_signos_vitales', [
  'uses' => 'SignosVitalesController@guardar_edicion_signos_vitales',
  'as' => 'guardar_edicion_signos_vitales'
]);

Route::get('/ver_edicion_signo_vitales/{id_signo_vital}/{id_tipo_atencion?}', 'SignosVitalesController@ver_edicion_signo_vitales');
Route::get('/ver_pdf_signos_vitales_unidad/{id_signo_vital}', 'SignosVitalesController@ver_pdf_signos_vitales_unidad');
Route::get('/ver_pdf_signos_vitales_unidad_hospi/{id_signo_vital}', 'SignosVitalesController@ver_pdf_signos_vitales_unidad_hospi');

Route::get('/buscarIdPrestacionParaNotaDeHospitalizacion/{id_hospitalizacion}/{id_tipo_atencion}', 'EnfermeriaController@buscarIdPrestacionParaNotaDeHospitalizacion');
Route::get('/buscarIdPrestacionParaNotaDeEmergencia/{id_emergencia}/{id_tipo_atencion}', 'EnfermeriaController@buscarIdPrestacionParaNotaDeEmergencia');
Route::get('/getEvolucionDetail/{id_tipo_atencion}/{id_evolucion}', 'EnfermeriaEvolucionesController@getEvolucionDetail');
Route::get('/api/obtener-terapeuticas-asegurado/{id_asegurado}', 'EnfermeriaController@obtenerTerapeuticasByAseguradoId');
Route::get('/api/enfermeria/historial/asegurado/{id_asegurado}', 'EnfermeriaController@obtenerHistorialEnfermeriaByAseguradoId');
// PRUEBA EXTRACION DE REPORTE A METODO ASINCRONO
Route::get('/stocksbyFamarcia/{iduser?}/{situacion?}', 'StocksController@newStocksByFarmacia');
Route::get('/stocksbyFamarcias/{idfarmacia}/{situacion?}', 'StocksController@newStocksByFarmacias');
Route::get('/productos_porlotes', 'StocksController@buscarProductosPorLotes');
Route::get('/productos_por_tipo/{id_farmacia}/{tipo}', 'StocksController@newProductosPorTipo');
//Route::get('/productos_proximos_vencer/{id_farmacia}', 'StocksController@buscarProductosProximosAVencer');
Route::get('/productos_proximos_vencer/{id_farmacia}/{meses?}', 'StocksController@buscarProductosProximosAVencer');
Route::get('/productos_vencidos/{id_farmacia}', 'StocksController@buscarProductosVencidos');
////Route::get('/stockFarmaciaByEstablecimiento', 'StocksController@buscarStockFarmaciaPorEstablecimiento');
Route::get('/consolidado_stock', 'RecetasController@newConsolidadoStock');
//Route::get('/pruebamodal/{iduser?}/{situacion?}','StocksController@pruebamodal');
Route::get('/obtener_cantidades_para_leyenda_stock', 'StocksController@GetCountsForReporteStockLocal');
Route::get('/obtener_reporte_kardex_lotes', 'StocksController@GetReporteKardexLotes');
Route::get('/obtener_reporte_kardex_lotes2', 'StocksController@GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento');
Route::get('/obtener_reporte_kardex_lotes3', 'StocksController@GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencido');
Route::get('/obtener_reporte_kardex_establecimiento', 'StocksController@GetStockActualByIdEstablecimientoAndIdProducto');
//Route::get('/obtener_reporte_kardex_consolidado', 'RecetasController@ShowTotalKardexByFarmaciaConsolidado')->middleware('check.server.resources');
Route::get('/obtener_reporte_kardex_consolidado', 'RecetasController@ShowTotalKardexByFarmaciaConsolidado');
Route::get('/obtener_kardexs_para_stock/{tipo?}', "StocksController@GetKardexsForReporte");

//REPORTES DEL SEIS (RSEIS)
Route::get('/reporteAtenciones', 'ReportesSeisController@reporteAtenciones');
Route::get('/reporteAtendidos', 'ReportesSeisController@reporteAtendidos');
Route::get('/reporteConsolidadosAcciones', 'ReportesSeisController@reporteConsolidadosAcciones');
Route::get('/reporteDiasCamas', 'ReportesSeisController@reporteDiasCamas');
Route::get('/reporteProcedimientos', 'ReportesSeisController@reporteProcedimientos');
Route::get('/reportePorTipo', 'ReportesSeisController@reportePorTipo');
Route::get('/reportePorPaciente', 'ReportesSeisController@reportePorPaciente');
Route::get('/reporteMortalidad', 'ReportesSeisController@reporteMortalidad');
Route::get('/reporteMorbilidad', 'ReportesSeisController@reporteMorbilidad');
Route::get('/reporteReferencia', 'ReportesSeisController@reporteReferencia');
Route::get('/reporteContraReferencia', 'ReportesSeisController@reporteContraReferencia');
Route::get('/reporteEgresosHospitalarios', 'ReportesSeisController@reporteEgresosHospitalarios');
Route::get('/reporteIntervenciones', 'ReportesSeisController@reporteIntervenciones');
Route::get('/reporteRecursos', 'ReportesSeisController@reporteRecursos');
Route::get('/reportePartos', 'ReportesSeisController@reportePartos');
Route::get('/reporteVigilancia', 'ReportesSeisController@reporteVigilancia');
Route::get('/tabla_a', 'ReportesSeisController@tabla_a');
Route::get('/tabla_b1', 'ReportesSeisController@tabla_b1');
Route::get('/tabla_b2', 'ReportesSeisController@tabla_b2');
Route::get('/tabla_c1', 'ReportesSeisController@tabla_c1');
Route::get('/tabla_c2', 'ReportesSeisController@tabla_c2');
Route::get('/tabla_d1', 'ReportesSeisController@tabla_d1');
Route::get('/tabla_d2', 'ReportesSeisController@tabla_d2');
Route::get('/tabla_e', 'ReportesSeisController@tabla_e');
Route::get('/tabla_f', 'ReportesSeisController@tabla_f');
Route::get('/tabla_g', 'ReportesSeisController@tabla_g');
Route::get('/tabla_h', 'ReportesSeisController@tabla_h');
Route::get('/tabla_i', 'ReportesSeisController@tabla_i');
Route::get('/tabla_j', 'ReportesSeisController@tabla_j');
Route::get('/reporte_vista_estadistica', 'ReportesSeisController@reporte_vista_estadistica');
Route::post('/exportar_reporte_atencion_detallado_estadistica', 'ExcelController@exportar_reporte_atencion_detallado_estadistica')->middleware('check.server.resources');
Route::get('/exportar_reporte_atencion_estadistica/{idUser}/{fecha_ini}/{fecha_fin}/{dni}/{paciente}/{estado}/{id_servicio}/{id_consultorio}/{id_medico}/{id_atencione}', 'ExcelController@exportar_reporte_atencion_estadistica')->middleware('check.server.resources');
Route::get('/exportar_reporte_atencion_estadistica_diagnostico/{idUser}/{fecha_ini}/{fecha_fin}/{dni}/{paciente}/{estado}/{id_servicio}/{id_consultorio}/{id_medico}/{id_atencione}', 'ExcelController@exportar_reporte_atencion_estadistica_diagnostico')->middleware('check.server.resources');
Route::post("/reporte_a_recursos", "ReportesSeisController@getReporte_a");
Route::post("/reporte_b1_recursos", "ReportesSeisController@getReporte_b1");
Route::post("/reporte_b2_recursos", "ReportesSeisController@getReporte_b2");
Route::post("/reporte_c1_recursos", "ReportesSeisController@getReporte_c1");
Route::post("/reporte_c2_recursos", "ReportesSeisController@getReporte_c2");
Route::post("/reporte_d1_recursos", "ReportesSeisController@getReporte_d1");
Route::post("/reporte_d2_recursos", "ReportesSeisController@getReporte_d2");
Route::post("/reporte_e_recursos", "ReportesSeisController@getReporte_e");
Route::post("/reporte_f_recursos", "ReportesSeisController@getReporte_f");
Route::post("/reporte_g_recursos", "ReportesSeisController@getReporte_g");
Route::post("/reporte_h_recursos", "ReportesSeisController@getReporte_h");
Route::post("/reporte_i_recursos", "ReportesSeisController@getReporte_i");
Route::post("/reporte_j_recursos", "ReportesSeisController@getReporte_j");

Route::post("/buscarUps", "ReportesSeisController@buscarUps");
Route::post("/buscarIpress", "ReportesSeisController@buscarIpress");
Route::post("/buscarDiagnostico", "ReportesSeisController@buscarDiagnostico");
Route::post("/buscarServ", "ReportesSeisController@buscarServ");
Route::post("/buscarMedicoPorDni", "ReportesSeisController@buscarMedicoPorDni");
Route::post("/buscarAseguradoPorDni", "ReportesSeisController@buscarAseguradoPorDni");
Route::post("/buscarProvinciaPorDepartamento", "ReportesSeisController@buscarProvinciaPorDepartamento");
Route::post("/buscarDistritoPorProvincia", "ReportesSeisController@buscarDistritoPorProvincia");
Route::post("/buscarUsuarioPorDni", "ReportesSeisController@buscarUsuarioPorDni");

// REPORTE ATENCIONES
Route::post("/reporteAtencionesUps", "ReportesSeisController@reporteAtencionesUps");
Route::get("/reporteAtencionesUpsExport", "ReportesSeisController@reporteAtencionesUpsExport");
Route::post("/reporteAtencionesDA", "ReportesSeisController@reporteAtencionesDA");
Route::get("/reporteAtencionesDAExport", "ReportesSeisController@reporteAtencionesDAExport");
Route::post("/reporteAtencionesTA", "ReportesSeisController@reporteAtencionesTA");
Route::get("/reporteAtencionesTAExport", "ReportesSeisController@reporteAtencionesTAExport");
Route::post("/reporteAtencionesIpress", "ReportesSeisController@reporteAtencionesIpress");
Route::get("/reporteAtencionesIpressExport", "ReportesSeisController@reporteAtencionesIpressExport");
Route::post("/reporteAtencionesDiagnosticos", "ReportesSeisController@reporteAtencionesDiagnosticos");
Route::get("/reporteAtencionesDiagnosticosExport", "ReportesSeisController@reporteAtencionesDiagnosticosExport");
Route::post("/reporteAtencionesServicios", "ReportesSeisController@reporteAtencionesServicios");
Route::get("/reporteAtencionesServiciosExport", "ReportesSeisController@reporteAtencionesServiciosExport");
Route::post("/reporteAtencionesResponsables", "ReportesSeisController@reporteAtencionesResponsables");
Route::get("/reporteAtencionesResponsablesExport", "ReportesSeisController@reporteAtencionesResponsablesExport");
Route::post("/reporteAtencionesPaciente", "ReportesSeisController@reporteAtencionesPaciente");
Route::get("/reporteAtencionesPacienteExport", "ReportesSeisController@reporteAtencionesPacienteExport");
Route::post("/reporteAtencionesPacienteUbigeo", "ReportesSeisController@reporteAtencionesPacienteUbigeo");
Route::get("/reporteAtencionesPacienteUbigeoExport", "ReportesSeisController@reporteAtencionesPacienteUbigeoExport");
Route::post("/reporteAtencionesAuditoria", "ReportesSeisController@reporteAtencionesAuditoria");
Route::get("/reporteAtencionesAuditoriaExport", "ReportesSeisController@reporteAtencionesAuditoriaExport");

// REPORTE ATENDIDOS
Route::post("/reporteAtendidosIpress", "ReportesSeisController@reporteAtendidosIpress");
Route::get("/reporteAtendidosIpressExport", "ReportesSeisController@reporteAtendidosIpressExport");

// REPORTE CONSOLIDADOS
Route::post("/listarConsolidadoAcciones", "ReportesSeisController@listarConsolidadoAcciones");
Route::get("/exportConsolidadoAcciones", "ReportesSeisController@exportConsolidadoAcciones");
Route::post("/listarConsolidadoAccionesAtenciones", "ReportesSeisController@listarConsolidadoAccionesAtenciones");
Route::get("/exportConsolidadoAccionesAtenciones", "ReportesSeisController@exportConsolidadoAccionesAtenciones");
Route::post("/listarConsolidadoAccionesFarmacia", "ReportesSeisController@listarConsolidadoAccionesFarmacia");
Route::post("/listarConsolidadoAccionesFarmaciaExtra", "ReportesSeisController@listarConsolidadoAccionesFarmaciaExtra");
Route::get("/exportConsolidadoAccionesFarmacia", "ReportesSeisController@exportConsolidadoAccionesFarmacia");
Route::get("/exportConsolidadoAccionesFarmaciaExtra", "ReportesSeisController@exportConsolidadoAccionesFarmaciaExtra");
Route::post("/listarConsolidadoAccionesProcedimiento", "ReportesSeisController@listarConsolidadoAccionesProcedimiento");
Route::post("/listarConsolidadoAccionesProcedimientoExtra", "ReportesSeisController@listarConsolidadoAccionesProcedimientoExtra");
Route::get("/exportConsolidadoAccionesProcedimiento", "ReportesSeisController@exportConsolidadoAccionesProcedimiento");
Route::get("/exportConsolidadoAccionesProcedimientoExtra", "ReportesSeisController@exportConsolidadoAccionesProcedimientoExtra");

// REPORTE PROCEDIMIENTOS
Route::post("/listarReporteProcedimientosCPT", "ReportesSeisController@listarReporteProcedimientosCPT");
Route::post("/listarReporteProcedimientosMedico", "ReportesSeisController@listarReporteProcedimientosMedico");
Route::post("/listarReporteProcedimientosCE", "ReportesSeisController@listarReporteProcedimientosCE");
Route::post("/listarReporteProcedimientosPaciente", "ReportesSeisController@listarReporteProcedimientosPaciente");
Route::post("/listarReporteProcedimientosProduccionIndividual", "ReportesSeisController@listarReporteProcedimientosProduccionIndividual");
Route::get("/exportarReporteProcedimientosCPT", "ReportesSeisController@exportarReporteProcedimientosCPT");
Route::get("/exportarReporteProcedimientosMedico", "ReportesSeisController@exportarReporteProcedimientosMedico");
Route::get("/exportarReporteProcedimientosCE", "ReportesSeisController@exportarReporteProcedimientosCE");
Route::get("/exportarReporteProcedimientosProduccionIndividual", "ReportesSeisController@exportarReporteProcedimientosProduccionIndividual");
Route::get("/exportarReporteProcedimientosPaciente", "ReportesSeisController@exportarReporteProcedimientosPaciente");
Route::get("/exportarReporteProcedimientosPacienteU", "ReportesSeisController@exportarReporteProcedimientosPacienteU");
Route::post("/listarReporteProcedimientosPacienteU", "ReportesSeisController@listarReporteProcedimientosPacienteU");

// REPORTE ESTRATEGIAS
Route::post("/listarReporteEstrategiasTipoIndividual", "ReportesSeisController@listarReporteEstrategiasTipoIndividual");
Route::post("/listarReporteEstrategiasTipoMasivo", "ReportesSeisController@listarReporteEstrategiasTipoMasivo");
Route::get("/exportarReporteEstrategiasTipoIndividual", "ReportesSeisController@exportarReporteEstrategiasTipoIndividual");
Route::get("/exportarReporteEstrategiasTipoMasivo", "ReportesSeisController@exportarReporteEstrategiasTipoMasivo");

Route::post("/listarReporteEstrategiasPaciente", "ReportesSeisController@listarReporteEstrategiasPaciente");
Route::get("/exportarReporteEstrategiasPaciente", "ReportesSeisController@exportarReporteEstrategiasPaciente");

// REPORTE REFERENCIA
Route::post("/listarReporteReferencias", "ReportesSeisController@listarReporteReferencias");
Route::get("/exportarReporteReferencias", "ReportesSeisController@exportarReporteReferencias");

// REPORTE CONTRAREFERENCIA
Route::post("/listarReporteContraReferencias", "ReportesSeisController@listarReporteContraReferencias");
Route::get("/exportarReporteContraReferencias", "ReportesSeisController@exportarReporteContraReferencias");

// REPORTE MORTALIDAD
Route::post("/listarReporteMortalidadDiagnostico", "ReportesSeisController@listarReporteMortalidadDiagnostico");
Route::post("/listarReporteMortalidadNumerico", "ReportesSeisController@listarReporteMortalidadNumerico");
Route::post("/listarReporteMortalidadPaciente", "ReportesSeisController@listarReporteMortalidadPaciente");
Route::get("/exportarReporteMortalidadDiagnostico", "ReportesSeisController@exportarReporteMortalidadDiagnostico");
Route::get("/exportarReporteMortalidadNumerico", "ReportesSeisController@exportarReporteMortalidadNumerico");
Route::get("/exportarReporteMortalidadPaciente", "ReportesSeisController@exportarReporteMortalidadPaciente");

// REPORTE MORBILIDAD
Route::post("/listarReporteMorbilidad", "ReportesSeisController@listarReporteMorbilidad");
Route::get("/exportarReporteMorbilidad", "ReportesSeisController@exportarReporteMorbilidad");

// REPORTE VIGILANCIA
Route::post("/listarReporteVigilancia", "ReportesSeisController@listarReporteVigilancia");
Route::post("/listarReporteVigilanciaAlertas", "ReportesSeisController@listarReporteVigilanciaAlertas");
Route::get("/exportarReporteVigilancia", "ReportesSeisController@exportarReporteVigilancia");
Route::get("/exportarReporteVigilanciaAlertas", "ReportesSeisController@exportarReporteVigilanciaAlertas");
Route::get("/all_eventos_epidemiologicos", "ReportesSeisController@all_eventos_epidemiologicos");
Route::post('listar_eventos_ajax', [
  'uses' => 'ReportesSeisController@listar_eventos_ajax',
  'as' => 'listar_eventos_ajax'
]);
Route::get("/exportar_reporte_eventos_epidemiologicos/{idUser}/{fecha_ini}/{fecha_fin}/{id_evento}/{id_establecimiento}/", "ReportesSeisController@exportar_reporte_eventos_epidemiologicos");

// REPORTE PARTOS
Route::post("/listarReportePartos", "ReportesSeisController@listarReportePartos");
Route::get("/exportarReportePartos", "ReportesSeisController@exportarReportePartos");

// REPORTE RECURSOS
Route::post("/listarReporteRecursos", "ReportesSeisController@listarReporteRecursos");
Route::get("/exportarReporteRecursos", "ReportesSeisController@exportarReporteRecursos");

// REPORTE CAMAS
Route::post("/listarReporteCamas", "ReportesSeisController@listarReporteCamas");
Route::get("/exportarReporteCamas", "ReportesSeisController@exportarReporteCamas");

// REPORTE EGRESOS HOSPI
Route::post("/listarReporteEgresosHospi", "ReportesSeisController@listarReporteEgresosHospi");
Route::get("/exportarReporteEgresosHospi", "ReportesSeisController@exportarReporteEgresosHospi");

// REPORTE INTERVENCIONES
Route::post("/listarReporteIntervenciones", "ReportesSeisController@listarReporteIntervenciones");
Route::get("/exportarReporteIntervenciones", "ReportesSeisController@exportarReporteIntervenciones");

Route::post("/buscarTipoExamenCA", "ReportesSeisController@buscarTipoExamenCA");

Route::get('/generar-txt_reportes-seis/{letra_tabla}/{periodo}/{id_establecimiento}/{anio}', 'ReportesSeisController@generarTxt');
Route::get('/generar-excel_reportes-seis/{letra}/{periodo}/{id_establecimiento}/{anio}', 'ReportesSeisController@generarExcel');

Route::post("/es/registrar-historia", "PacienteController@registrarNroHistoria");
Route::post("/es/registrar-asegurado", "PacienteController@registrarAseguradoES");

Route::get('/obtener_bloques_por_menu/{idmenu}/{iduser}', 'MenuController@GetBloqueMenuByIdMenu');
// Pruebas de tabla de stock historia kardex_points_2 (29jun2024)
Route::get('/stockFarmaciaByEstablecimiento', 'StocksController@buscarStockFarmaciaPorEstablecimiento_prueba');
Route::get('/obtener_reporte_kardex_establecimiento_prueba', 'StocksController@GetStockActualByIdEstablecimientoAndIdProducto_prueba');
Route::post('stockFarmaciaByEstablecimiento', [
  'uses' => 'StocksController@stockFarmaciaByEstablecimiento_prueba',
  'as' => 'stockFarmaciaByEstablecimiento'
]);
Route::get('/exportar_stockFarmaciaByEstablecimiento_prueba/{id_establecimiento}', 'ExcelController@exportar_stockFarmaciaByEstablecimiento_prueba');
// Route::get('/ver_prestaciones_hospitalizacion/{id_hospitalizacion}', 'HospitalizacionController@ver_prestaciones_hospitalizacion');
Route::get('/actualizar_kardex_point/{id_tran}/{id_farmacia}/{id_establecimiento}', 'TransferenciaController@actualizar_kardex_point');
////// permisos de los menus

Route::get('/obtener_permiso_por_idusuario_menuprincipal/{iduser}', 'UsuarioController@obtener_permiso_por_idusuario_menuprincipal');

Route::post('listar_alta_administrativa_ajax', [
  'uses' => 'HospitalizacionController@listar_alta_administrativa_ajax',
  'as' => 'listar_alta_administrativa_ajax'
]);
Route::get('/mostrar_atencion_hospitalizacion/{id}', 'HospitalizacionController@mostrar_atencion_hospitalizacion');

//========================== IMAGENES =====================================================
Route::get('/add_categoria_radiologias/{id_servicio}', 'RadiologiaController@addCategoriaRadiologias');
Route::get('/add_categoria_radiologias_nivel/{id_servicio}/{nivel}', 'RadiologiaController@addCategoriaRadiologiasNivel');
Route::post('agregar_categoria_radiologia_atencion', [
  'uses' => 'RadiologiaController@agregar_categoria_radiologia_atencion',
  'as' => 'agregar_categoria_radiologia_atencion'
]);
Route::get('/add_rad_nivel/{nivel}/{tipo}/{categoria}/{servicio}', 'RadiologiaController@addRadNivel');
Route::post('/desactivar_examen_niv_cat_rad/{id}/{estado}', 'RadiologiaController@desactivar_examen_niv_cat_rad');
Route::post(
    '/eliminar_rad_nivel_tipo_atencion/{id}/{tipo}/{nivel}/{categoria}/{consultorio}',
    'RadiologiaController@eliminarRadNivelTipoAtencion'
)->name('eliminar_rad_nivel_tipo_atencion');

Route::get('/listar_examenes_relacionados/{id}', 'RadiologiaController@exam_relacionados_CAT_RAD');
Route::get('/desactivar_nivel_categoria_rad/{id}/{estado}', 'RadiologiaController@desactivar_nivel_categoria_rad');
Route::post('agregar_rad_nivel_tipo_atencion', [
  'uses' => 'RadiologiaController@agregarRadNivelTipoAtencion',
  'as' => 'agregar_rad_nivel_tipo_atencion'
]);
Route::post('registrar_columna_reporte_radiologia', [
  'uses' => 'RadiologiaController@registrarColumnaReporteRadiologia',
  'as' => 'registrar_columna_reporte_radiologia'
]);
Route::get('/obtener_columnas_radiologia_consultorios/{tipo_atencion}/{id_consultorio?}', 'RadiologiaController@obtenerColumnasRadiologiaConsultorios');
Route::get('/ver_plantilla_rad/{nivel}/{tipo_examen}/{id_consultorio?}', 'PdfController@print_plantilla_rad');
Route::get('/ver_plantilla_rad_nivel_1y2/{nivel}/{tipo_examen}/{id_consultorio?}', 'PdfController@print_plantilla_rad_nivel1y2');
Route::get('/obtener_radiologia_convenio/{buscar}/{nivel}/{genero}/{tipo_atencion}/{id_servicio?}', 'RadiologiaController@obtener_radiologia_convenio');
Route::get('/lista_servicios_imagenes', 'RadiologiaController@listaServiciosImagenes');
Route::get('/lista_registrador_imagenes', 'RadiologiaController@listaRegistradorImagenes');

//===================================== Imagenes =====================================
Route::get('/create_servicio_radiologia', 'RadiologiaController@create_servicio');
Route::get('/desactivar_categoria_radiologia/{id}', 'RadiologiaController@desactivar_categoria_radiologia');
Route::post('registrar_categoria_radiologia', [
  'uses' => 'RadiologiaController@registrarCategoriaRadiologia',
  'as' => 'registrar_categoria_radiologia'
]);
Route::get('/list_plantillas_informes/{data_array}', 'RadiologiaController@list_plantillas_informes')->name('list_plantillas_informes');
Route::get('/list_plantillas_informes_validacion/{data_array}', 'RadiologiaController@list_plantillas_informes_validacion')->name('list_plantillas_informes_validacion');
Route::post('sinTokensRadiologiaOrden', [
  'uses' => 'ExcelController@sinTokensRadiologiaOrden',
  'as' => 'sinTokensRadiologiaOrden'
]);
Route::post('genera_csv_radiologia', [
  'uses' => 'ExcelController@genera_csv_radiologia',
  'as' => 'genera_csv_radiologia'
]);
Route::get('/obtener_pdfs_detalle_radiologia/{id_radiologia}/{id_servicio_radiologia}/{id_pp}', 'RadiologiaController@obtener_pdfs_detalle_radiologia');
Route::post('/refirma/postArgumentsRadiologia', 'RefirmaController@postArgumentsRadiologia')->name('postArgumentsRadiologia');

Route::get('/listar_ordenes_imagenes_pendientes/{nro_doc}/{id_servicio_estudio?}', 'RadiologiaController@listarOrdenesImagenesPendientes');
Route::get('/modal_historial_cita_imagenes/{dni_beneficiario}', 'RadiologiaController@modal_historial_cita_imagenes');
//Route::get('/llamada_imagenes/{id_cita}', 'RadiologiaController@llamada_imagenes');
//Route::get('/cancela_llamada_imagenes/{id_cita}', 'RadiologiaController@cancela_llamada_imagenes');
Route::get('/llamada_imagenes/{id_cita}/{id_ticket}', 'RadiologiaController@llamada_imagenes');
Route::get('/cancela_llamada_imagenes/{id_cita}/{id_ticket}', 'RadiologiaController@cancela_llamada_imagenes');
Route::get('/modal_comentario_cita_imagenes/{id}/{id_pp}', 'RadiologiaController@modal_comentario');
Route::post('update_comentario_radiologia', ['uses' => 'RadiologiaController@update_comentario_radiologia', 'as' => 'update_comentario_radiologia']);

Route::get('/validacion_beneficiario_dias_por_examen_rad/{dni_beneficiario}/{id_examen_rad}','RadiologiaController@validacion_beneficiario_dias_por_examen_rad');
Route::get('/radiologia_informe_adicional/{id_radiologia}/{id_servicio_radiologia}/{id_pp}/{id_cita?}', 'RadiologiaController@radiologia_informe_adicional');
Route::post('registrar_radiologia_informe_adicional', [
  'uses' => 'RadiologiaController@registrar_radiologia_informe_adicional',
  'as' => 'registrar_radiologia_informe_adicional'
]);
//Route::get('/radiologia_informe_validacion_adicional/{id_radiologia}/{id_servicio_radiologia}/{id_pp}/{id_informe_adicional}', 'RadiologiaController@radiologia_informe_validacion_adicional');
Route::post('modificar_validar_radiologia_informe_adicional', [
  'uses' => 'RadiologiaController@modificar_validar_radiologia_informe_adicional',
  'as' => 'modificar_validar_radiologia_informe_adicional'
]);

Route::get('/radiologia_informe_validacion_adicional/{id_radiologia}/{id_servicio_radiologia?}/{id_pp}/{id_informe_adicional}', 
    'RadiologiaController@radiologia_informe_validacion_adicional'
)->name('radiologia_informe_validacion_adicional');

Route::get('/print_examen_radiologia_lns_adicional/{id_radiologia}/{id_servicio_radiologia}/{idpp}/{id_informe_adicional}', 'PdfController@print_examen_radiologia_lns_adicional');
Route::get('/ver_informe_para_firmar_imagenologia_lns_adicional/{id_radiologia}/{id_servicio_radiologia}/{id_pp}/{id_informe_adicional}', 'RadiologiaController@radiologia_firmar_informe_adicional');
Route::post('sinTokensRadiologiaAdicional', [
  'uses' => 'ExcelController@sinTokensRadiologiaAdicional',
  'as' => 'sinTokensRadiologiaAdicional'
]);
Route::post('genera_csv_imagenologia_adicional', [
  'uses' => 'ExcelController@genera_zip_imagenologia_adicional',
  'as' => 'genera_csv_imagenologia_adicional'
]);
Route::post('/refirma/registra_firma_informe_imagenologia_adicional', 'ExcelController@registra_firma_informe_imagenologia_adicional')->name('registra_firma_informe_imagenologia_adicional');
Route::get('/informe_imagenes/{id_radiologia}/{id_servicio_radiologia}/{idpp}/{id_informe_adicional?}/{flag}/{ano}/{mes}/{dia}/{name}' . '.pdf', 'ExcelController@ver_radiologia_informe_digital');
//Route::post('/exportar_reporte_imagenes', 'ExcelController@exportar_reporte_imagenes');
/***************************************************** REPORTE IMAGENES ********************************************************************* */
Route::post('exportar_reporte_imagenes', [
  'uses' => 'ExcelController@exportar_reporte_imagenes',
  'as' => 'exportar_reporte_imagenes'
]);
Route::get('/obtener_nivel_usuario/{id_user}', 'RadiologiaController@getNivelradreportebyuser');
/**************************************************************************************************************************************************** */
Route::get('/hc_electronica_salud_materna/{id_asegurado}', 'SaludMaternaController@hc_electronica_salud_materna');
//===================================== CONSULTA SALUD PACIENTE =====================================
Route::get('/salud_consulta_paciente', 'SaludConsultaPacienteController@index')->middleware('menu.permission:177');
Route::get('getAseguradoDataByDNI/{dni}','SaludConsultaPacienteController@getAseguradoDataByDNI');
Route::get('getAseguradoDataByDocument/{tipo_doc}/{nro_doc}','SaludConsultaPacienteController@getAseguradoDataByDocument');
Route::get('getServiciosByIpress/{ipress}','SaludConsultaPacienteController@getServiciosByIpress');
// CITAS
Route::POST('/listarCitas','SaludConsultaPacienteController@listarCitas')->name('listarCitas');
Route::get('getCitaById/{id_cita}','SaludConsultaPacienteController@getCitaById');
// ATENCIONES MEDICAS
Route::POST('/listar_atenciones_medicas','SaludConsultaPacienteController@listar_atenciones_medicas')->name('listarAtencionesMedicas');
Route::POST('/authPDF','SaludConsultaPacienteController@accesoAtencionMedicaPdf')->name('authPDF');
// PROCEDIMIENTOS
Route::POST('/listar_procedimientos_consultor','SaludConsultaPacienteController@listar_procedimientos')->name('listarProcedimientosConsultor');
// RECETAS
Route::POST('/listar_recetas','SaludConsultaPacienteController@listar_recetas')->name('listarRecetas');
//Route::get('getRecetaDetail/{id_receta}','SaludConsultaPacienteController@getDetallesRecetaById');
Route::get('getUrlPdfRecetaById/{id_receta}','SaludConsultaPacienteController@getUrlPdfRecetaById');
// EXAMENES AUXILIARES
Route::POST('/listar_examenes_auxiliares','SaludConsultaPacienteController@listar_examenes_auxiliares')->name('listarExamenesAuxiliaresConsultor');
//===================================== CENTRO QUIRURGICO =====================================
Route::get('/cq_lista_pendiente', 'CentroQuirurgicoController@lista_pendiente');
Route::get('/cq_pre_anestesiologo', 'CentroQuirurgicoController@pre_anestesiologo');
Route::get('/cq_post_atencion', 'CentroQuirurgicoController@post_atencion');
//Route::get('/registrar_intraoperario', 'CentroQuirurgicoController@registrar_intraoperario');
Route::get('/registro_seguridad', 'CentroQuirurgicoController@registro_seguridad');
Route::get('/instrumentos_quirurgicos', 'CentroQuirurgicoController@instrumentos_quirurgicos');
Route::post('listar_prestacion_intervencionqx_ajax', [
  'uses' => 'CentroQuirurgicoController@listar_prestacion_intervencionqx_ajax',
  'as' => 'listar_prestacion_intervencionqx_ajax'
]);
Route::get('/formulario_cita_riesgo_quirurgico/{id_prestacion}/{id_cqx_cita}/{id_servicio}/{id_cqx_intervencion}','CentroQuirurgicoController@formulario_cita_riesgo_quirurgico');
Route::get('/ver_cita_cqx/{id_user}/{id_cita}', 'RecetaValeController@ver_cita_cqx');
Route::get('/modal_cita_horario_lista_cita_medico_cqx/{id_ipress}/{id_consultorio}/{fecha_atencion}/{documento}/{id_intervencion}', 'RecetaValeController@modal_cita_horario_lista_cita_medico_cqx');
Route::get('/ver_cita_por_medico_cqx/{id_user}/{id_cita}', 'RecetaValeController@ver_cita_por_medico_cqx');
Route::get('/modal_proxima_cita_cqx/{tipodoc_beneficiario}/{dni_beneficiario}', 'CitaController@modal_proxima_cita_cqx');
Route::get('/ver_prestacion_cqx/{id_user}/{id_prestacion}', 'RecetaValeController@ver_prestacion_cqx');
Route::get('/ver_prestacion_alta_emergencia_cqx/{id_user}/{id_prestacion}', 'RecetaValeController@ver_prestacion_alta_emergencia_cqx');
Route::get('/mostrar_modal_hc_cqx/{id_asegurado}', 'CentroQuirurgicoController@mostrar_modal_hc_cqx');
// Route::get('/print_mopri_cqx/{id_hospitalizacion}', 'PdfController@print_mopri_cqx');
Route::get('/print_mopri_cqx/{id_intervencion}', 'PdfController@print_mopri_cqx');
Route::post('atencion_cqx_invalidar_receta_digital', [
  'uses' => 'AtencionQuirurgicaController@atencion_invalidar_receta_digital',
  'as' => 'atencion_cqx_invalidar_receta_digital'
]);
Route::post('/atencion_cqx_receta_firmar/{id_receta}', 'AtencionQuirurgicaController@recetaPDFfirmar');
Route::post('/atencion_cqx_receta_post_arguments', 'AtencionQuirurgicaController@recetaPostArguments')->name('atencion_cqx_receta_post_arguments');
Route::post('/atencion_cqx_receta_actualiza_datos_firma_con_token_io', 'AtencionQuirurgicaController@recetaActualizarFirmaConToken')->name('atencion_cqx_receta_actualiza_datos_firma_con_token_io');

Route::post('/atencion_cqx_firmar/{id_atencion}', 'AtencionQuirurgicaController@atencionPDFfirmar');
Route::post('/atencion_cqx_post_arguments', 'AtencionQuirurgicaController@atencionPostArguments')->name('atencion_cqx_post_arguments');
Route::post('/atencion_cqx_actualiza_datos_firma_con_token_io', 'AtencionQuirurgicaController@atencionActualizarFirmaConToken')->name('atencion_cqx_actualiza_datos_firma_con_token_io');
Route::post('/atencion_cqx_actualiza_datos_firma_sin_token_io', 'AtencionQuirurgicaController@atencionActualizarFirmaSinToken')->name('atencion_cqx_actualiza_datos_firma_sin_token_io');
Route::get('/pdf_atencion_quirurgica_sin_firma/{id_atencion}', 'AtencionQuirurgicaController@pdf_atencion_quirurgica_sin_firma');
Route::get('/pdf_sop_cqx/{id_intervencion}', 'PdfController@print_sop_cqx');

Route::post('/atencion_anestesio_cqx_receta_firmar/{id_receta}', 'CentroQuirurgicoController@recetaPDFfirmarAnestesio');
Route::post('/atencion_anestesio_cqx_receta_post_arguments', 'CentroQuirurgicoController@recetaPostArgumentsAnestesio')->name('atencion_anestesio_cqx_receta_post_arguments');
Route::post('/atencion_anestesio_cqx_receta_actualiza_datos_firma_con_token_io', 'CentroQuirurgicoController@recetaActualizarFirmaConTokenAnestesio')->name('atencion_anestesio_cqx_receta_actualiza_datos_firma_con_token_io');
Route::post('/atencion_anestesio_cqx_receta_firmar_sin_token/{id_receta}', 'CentroQuirurgicoController@recetaPDFfirmarAnestesioSinToken');

Route::post('/atencion_anestesio_cqx_receta_firmar/{id_receta}', 'CentroQuirurgicoController@recetaPDFfirmarAnestesio');
Route::post('/atencion_anestesio_cqx_receta_post_arguments', 'CentroQuirurgicoController@recetaPostArgumentsAnestesio')->name('atencion_anestesio_cqx_receta_post_arguments');
Route::post('/atencion_anestesio_cqx_receta_actualiza_datos_firma_con_token_io', 'CentroQuirurgicoController@recetaActualizarFirmaConTokenAnestesio')->name('atencion_anestesio_cqx_receta_actualiza_datos_firma_con_token_io');

Route::post('genera_csv_anestesiologia_cqx', [
  'uses' => 'ExcelController@genera_csv_anestesiologia_cqx',
  'as' => 'genera_csv_anestesiologia_cqx'
]);
Route::get('/get_procedimientos_examen_patologia', 'ReferenciasController@procedimientos_examen_patologia_cqx');

Route::get('/programacion_salas_qx', 'CentroQuirurgicoController@programacion_salas');
Route::get('/all_programacion_detalle/{id_establecimiento}/{id_sala}/{mes}/{ano}/{id_consultorio}', 'CentroQuirurgicoController@all_programacion_detalle');
Route::post('registrar_programacion_salas', [
  'uses' => 'CentroQuirurgicoController@registrar_programacion_salas',
  'as' => 'registrar_programacion_salas'
]);
Route::post('/registrar_intraoperario', 'IntraOperatorioController@registrar_intraoperario')->name('registrar_intraoperario');
Route::post('/registrar_intraoperario_info_ajax', 'IntraOperatorioController@registrar_intraoperario_info_ajax');
Route::post('/guardar_intraoperario', 'IntraOperatorioController@guardar_intraoperario')->name('guardar_intraoperario');
Route::post('/guardar_intraoperario_v2', 'IntraOperatorioController@guardar_intraoperario_v2')->name('guardar_intraoperario_v2');
Route::get('/profesional_que_interviene/{buscar_medico}/{id_especialidad?}', 'IntraOperatorioController@medicosByEspecialidadConsultorio');
Route::get('/editar_intraoperatorio_ajax/{id_intraoperatorio}', 'IntraOperatorioController@editarIntraOperatorio_ajax');
Route::post('motivo_anulacion_cirujano', 'IntraOperatorioController@motivoAnulacionCirujano');
Route::get('/detalle_intraoperario/{id_intraoperatorio}', 'IntraOperatorioController@detalleIntraoperatorio');
Route::get('/detalle_intraoperario_ajax/{id_intraoperatorio}', 'IntraOperatorioController@detalleIntraoperatorio_ajax');
Route::post('/actualizar_intraoperatorio_v2', 'IntraOperatorioController@updateIntraOperatorioV2')->name('actualizar_intraoperatorio_v2');
Route::post('/anular_intraoperatorio', 'IntraOperatorioController@anularIntraOperatorio');
// Route::get('/intraoperarotio_id_hospitalizacion/{id_hospitalizacion}', 'IntraOperatorioController@getIntraoperatorioByIdHospitalizacion');
Route::get('/intraoperarotio_id_intervencion/{id_intervencion}', 'IntraOperatorioController@getIntraoperatorioByIdIntervencion');
Route::get('/pdf_intraoperatorio/{id_intraoperatorio}', 'IntraOperatorioController@pdf_intraoperatorio');
Route::post('/intraoperatorio_firmar/{id_intraoperatorio}', 'IntraOperatorioController@intraoperatorioPDFfirmar');
Route::post('/intraoperatorio_post_arguments', 'IntraOperatorioController@postArguments')->name('intraoperatorio_post_arguments');
Route::post('/actualiza_datos_firma_con_token_io', 'IntraOperatorioController@actualizarFirmaConToken')->name('actualiza_datos_firma_con_token_io');
Route::post('/actualiza_datos_firma_sin_token_io', 'IntraOperatorioController@actualizarFirmaSinToken')->name('actualiza_datos_firma_sin_token_io');

Route::get('/hoja_recuperacion', 'CentroQuirurgicoController@hoja_recuperacion');
Route::post('/registrar_hoja_recuperacion', 'HojadeRecuperacionController@registrar')->name('registrar_hoja_recuperacion');
Route::post('/guardar_hoja_recuperacion', 'HojadeRecuperacionController@guardar')->name('guardar_hoja_recuperacion');
Route::get('/existe_intraoperatorio_paciente/{id_intervencion}/{id_asegurado}', 'HojadeRecuperacionController@existRegistroIntraoperatorioByIdItervencion');
Route::post('/hoja_recuperacion_datos_globales_balance', 'HojadeRecuperacionController@datosGlobales');
Route::post('/hoja_recuperacion_registrar_balance_hidrico','HojadeRecuperacionController@registrarBalanceHidrico')->name('hoja_recuperacion_registrar_balance_hidrico');
Route::get('/hoja_recuperacion_bh_listado/{id_hoja_recuperacion}', 'HojadeRecuperacionController@listarBalanceHidricoByHojaRecuperacion');
Route::get('/hoja_recuperacion_pdf_balance_hidrico/{id_balance}', 'HojadeRecuperacionController@pdf_balance_hidrico');
Route::get('/hoja_recuperacion_pdf_balance_hidrico_turno/{id_balance}', 'HojadeRecuperacionController@pdf_balance_hidrico_turno');
Route::get('/hoja_recuperacion_caracter_fisico_vitales', 'HojadeRecuperacionController@caracterFisicoVitales');
Route::post('hoja_recuperacion_registrar_funciones_vitales','HojadeRecuperacionController@registrarFuncionesVitales')->name('hoja_recuperacion_registrar_funciones_vitales');
Route::get('/hoja_recuperacion_vitales_listado/{id_hoja_recuperacion}', 'HojadeRecuperacionController@listarFuncionesVitalesByHojaRecuperacion');
Route::get('/hoja_recuperacion_pdf/{id_hoja_recuperacion}', 'HojadeRecuperacionController@pdfGeneral');
Route::get('/detalle_hoja_recuperacion/{id_hoja_recuperacion}', 'HojadeRecuperacionController@detalleHojaRecuperacion');
Route::get('/editar_hoja_recuperacion/{id_hoja_recuperacion}', 'HojadeRecuperacionController@editarHojaRecuperacion');
Route::post('/update_hoja_recuperacion', 'HojadeRecuperacionController@updateHojaRecuperacion');


Route::post('/hoja_recuperacion_firmar/{id_hoja_recuperacion}', 'HojadeRecuperacionController@hojarecuperacionPDFfirmar');
Route::post('/hoja_recuperacion_post_arguments', 'HojadeRecuperacionController@postArguments')->name('hoja_recuperacion_post_arguments');
Route::post('/actualiza_datos_firma_con_token_io_hr', 'HojadeRecuperacionController@actualizarFirmaConToken')->name('actualiza_datos_firma_con_token_io_hr');
Route::post('/actualiza_datos_firma_sin_token_io_hr', 'HojadeRecuperacionController@actualizarFirmaSinToken')->name('actualiza_datos_firma_sin_token_io_hr');


Route::get('/validar_paciente_con_intervencion_quirurgica/{tipo_doc_asegurado}/{numero_doc_asegurado}/{id_establecimiento}/{id_servicio}', 'CentroQuirurgicoController@validar_paciente_con_intervencion_quirurgica');
Route::get('/get_profesionales_x_especialidad/{id_establecimiento}/{id_especialidad}', 'MedicoController@get_profesionales_x_especialidad');
Route::get('/form_validacion_programacion_qx/{id_intervencion_quirurgica}/{id_tipo_atencion}', 'CentroQuirurgicoController@form_validacion_programacion_qx');
Route::get('/form_validacion_programacion_qx_ajax/{id_intervencion_quirurgica}/{id_tipo_atencion}', 'CentroQuirurgicoController@form_validacion_programacion_qx_ajax');
Route::post('validar_programacion_quirurgica', [
  'uses' => 'CentroQuirurgicoController@validar_programacion_quirurgica',
  'as' => 'validar_programacion_quirurgica'
]);
Route::post('registrar_suspencion_intervencion', [
  'uses' => 'CentroQuirurgicoController@registrar_suspencion_intervencion',
  'as' => 'registrar_suspencion_intervencion'
]);
//CQ ANESTESIOLOGIA
Route::get('/cqanestesia/{id_prestacion}/{id_intervencion}', 'CentroQuirurgicoController@showCqanestesia');
Route::post('cqanestesia', [
  'uses' => 'CentroQuirurgicoController@storeOrUpdateCqanestesia',
  'as' => 'cqanestesia.storeOrUpdate'
]);

//CQ ATENCION QUIRURGICA
Route::get('/modal_atencion_quirurgica/{id_cita}/{id_intervencion}/{id_tipo_atencion}','AtencionQuirurgicaController@modal_atencion_quirurgica');
Route::get('/cirujanos_que_interviene/{buscar_medico}/{id_especialidad?}', 'AtencionQuirurgicaController@medicosByEspecialidadConsultorio');
Route::post('motivo_cirujano_anulado', 'AtencionQuirurgicaController@motivoAnulacionCirujano');
// Route::post('registrar_alta_medica_hospitalizacion_AQ', [
//   'uses' => 'AtencionQuirurgicaController@registrar_alta_medica_hospitalizacion_AQ',
//   'as' => 'registrar_alta_medica_hospitalizacion_AQ'
// ]);
Route::post('atencion_quirurgica_adicional', [
  'uses' => 'AtencionQuirurgicaController@atencion_quirurgica_adicional',
  'as' => 'atencion_quirurgica_adicional'
]);

Route::get('AQ_buscar_dia_estancia_ajax', [
  'uses' => 'AtencionQuirurgicaController@AQ_buscar_dia_estancia_ajax',
  'as' => 'AQ_buscar_dia_estancia_ajax'
]);

Route::get('/detalle_atencion_quirurgica/{id_intervencion}/{id_prestacion}/{id_cita}/{id_hospitalizacion}', 'AtencionQuirurgicaController@detalleAtencionQuirurgica');

Route::post('/registro_prestacion', [
  'uses' => 'AtencionQuirurgicaController@registro_prestacion_transaction',
  'as' => 'registro_prestacion'
]);

Route::post('registro_prestacion_transaction', [
  'uses' => 'AtencionQuirurgicaController@registro_prestacion_transaction',
  'as' => 'registro_prestacion_transaction'
]);

Route::post('registro_receta_prestacion',[
  'uses' => 'AtencionQuirurgicaController@registro_receta_prestacion',
  'as' => 'registro_receta_prestacion'
]);

Route::get('/observar_prestacion/{id_user}/{id_prestacion}/{id_intervencion}', 'AtencionQuirurgicaController@observar_prestacion');
//Route::get('/pdf_atencion_quirurgica/{id_intervencion}/{id_prestacion}/{id_cita}/', 'AtencionQuirurgicaController@pdf_atencion_quirurgica');
//Route::get('/pdf_receta_vale/{id_prestacion}/{id_intervencion}', 'AtencionQuirurgicaController@pdf_receta_vale');

Route::post('registro_evolucion_hospitalizacion', [
  'uses' => 'EvolucionHospitalizacionController@registro_evolucion_transaction_hospitalizacion',
  'as' => 'registro_evolucion_hospitalizacion'
]);

Route::get('/obtener_estado_hospitalizacion/{id_hospitalizacion}', 'AtencionQuirurgicaController@getAtencionQuirurgicaByIdHospitalizacion');
//FORMATO ESCANEADOS
Route::post('/registrar_formatos_escaneados', 'FormatoEscaneadoController@formDocumento')->name('registrar_formatos_escaneados');
Route::post('/subir_formatos_escaneados','FormatoEscaneadoController@subirDocumento')->name('subir_formatos_escaneados');
Route::post('/subirDocumento_archivo','FormatoEscaneadoController@subirDocumento_archivo')->name('subirDocumento_archivo');
Route::post('/obtener_formatos_subidos', 'FormatoEscaneadoController@obtenerFormatosSubidos')->name('obtener_formatos_subidos');
Route::get('/lista_formatos', 'FormatoEscaneadoController@formatos')->name('lista_formatos');
Route::get('/formatos_escaneados/{nombre_formato}', 'FormatoEscaneadoController@formato_escaneado_view');
Route::get('/formatos/{nombre_formato}', 'FormatoEscaneadoController@formato_view');

Route::get('/ver_formatos_escaneado_tele_salud/{id}', 'FormatoEscaneadoController@ver_formatos_escaneado_tele_salud');
Route::post('registrar_cita_tele_salud',[
  'uses' => 'ReferenciasController@registrar_cita_tele_salud',
  'as' => 'registrar_cita_tele_salud'
]);
Route::post('registrar_resultado_tele_salud',[
  'uses' => 'ReferenciasController@registrar_resultado_tele_salud',
  'as' => 'registrar_resultado_tele_salud'
]);
//===================================== FIN CENTRO QUIRURGICO =====================================

Route::get('/hospitalizacion_orden/{estado}/{id_orden}', 'PdfController@hospitalizacion_orden');

////////pruebas de la nueva vista percy beltran
/*
Route::get('/crear_nueva_lista', 'SuperUsuarioController@crear_nueva_lista');

Route::post('guardar_ficha_pebe', [
  'uses' => 'SuperUsuarioController@guardar_ficha_pebe',
  'as' => 'guardar_ficha_pebe'
]);

Route::get('/all_fichas_registradas', 'SuperUsuarioController@all_fichas_registradas');
Route::get('/generar_lista_archivo', 'SuperUsuarioController@generar_lista_archivo');
Route::get('/generar_reporte_lista', 'SuperUsuarioController@generar_reporte_lista');
Route::get('/editar_ficha_pebe/{id_ficha}', 'SuperUsuarioController@editar_ficha_pebe');
Route::get('/eliminar_ficha_pebe/{id_ficha}', 'SuperUsuarioController@eliminar_ficha_pebe');

Route::post('edicion_ficha_pebe', [
  'uses' => 'SuperUsuarioController@edicion_ficha_pebe',
  'as' => 'edicion_ficha_pebe'
]);
*/
/////ENFERMERIA CE //////////////////////////
Route::get('/enfermeria/consulta-externa', 'EnfermeriaController@consultaExternaListarNotaEnfermeria');
Route::get('/enfermeria/consulta-externa/registrar_nota_ingreso', 'EnfermeriaController@consultaExternaRegistraNotaIngreso');
Route::get('/enfermeria/consulta-externa/ver-detalle/{enfermeria_id}', 'EnfermeriaController@consultaExternaVerNotaEnfermeria');
Route::get('/enfermeria/consulta-externa/editar/{enfermeria_id}', 'EnfermeriaController@consultaExternaEditarEnfermeria');
Route::get('/enfermeria/consulta-externa/pdf/{enfermeria_id}', 'EnfermeriaController@consultaExternaGenerarPDFEnfermeria');
Route::post('/api/enfermeria/consulta-externa/pdf-firmar', 'EnfermeriaController@consultaExternaFirmarPDFEnfermeria');
Route::put('/api/enfermeria/consulta-externa/editar/{id}', 'EnfermeriaController@consultaExternaActualizarEnfermeria');
Route::put('/api/enfermeria/consulta-externa/actualizar-firma/{enfermeria_id}', 'EnfermeriaController@consultaExternaActualizarFirmaEnfermeria');
Route::get('/api/enfermeria/consulta-externa/validar/procedimiento/{id_procedimiento}', 'EnfermeriaController@validateDuplicateProcedure');

Route::get('/api/enfermeria/obtener-citas/search', 'EnfermeriaController@obtenerCitasEnfermeria');
Route::get('/api/citas/buscar-ultima-atencion-asegurado', 'CitaController@buscarUltimaAtencionAsegurado');
Route::get('/api/citas/buscar-atenciones-enfermeria/filtros', 'CitaController@buscarAtencionesForEnfermeriaCE');
Route::get('/api/receta-vale/prestacion/{prestacion_id}', 'RecetaValeController@obtenerProductosPorPrestacionId');
Route::post('/api/nota-enfermeria/consulta-externa/registrar', 'Nota_enfermeriaController@registrarNotaEnfermeriaConsultaExterna');

Route::get('/api/enfermeria/cita/{id_ext_enfermeria}', 'EnfermeriaController@obtenerEnfermeriaByIdExtEnfermeria');
Route::get('/api/enfermeria/report', 'EnfermeriaController@exportarReporteEnfermeria');


Route::get('/api/citas/prestacion/{cita_id}', 'CitaController@getAtentionByPrestacionIdFormEnfermeriaCE');
Route::get('/api/atenciones/enfermeria/{id_ext_enfermeria}', 'EnfermeriaController@getEnfermeriasByIdExtEnfermeria');
//////GATRO///////////////////////
Route::get('/informe_gastro/{id}/{id_prestacion}/{contador}', 'ExcelController@ver_informe_gastro_imprimir');
Route::get('/informe_gastro_pdf/{id_prestacion}/{contador}', 'PdfController@ver_pdf_informe_gastro_imprimir');

Route::get('/api/emergencia/alta/obtener-ultimo-siteds-generado', 'EmergenciaController@obtenerUltimoSitedsGenerado');
Route::get('/api/enfermeria/obtener-consultorios/{establecimiento_id}/{tipo_atencion_id}', 'EnfermeriaController@getConsultoriosByEstablecimientoTipoAtencion');

////////// referencia pdf //////
Route::get('/vista_dirrehum_pacientes/{id?}', 'ReferenciasController@vista_dirrehum_pacientes');
Route::get('/pdf_referencia_ver_antes/{id_prestacion}/{id}', 'PdfController@ver_pdf_referencia');
Route::get('/dirrehum_asegurado/{id_asegurado}', 'ReferenciasController@dirrehum_asegurado');
//Route::get('/vista_dirrehum_pacientes/{id?}', 'ReferenciasController@vista_dirrehum_pacientes');
//Route::get('/{id_prestacion}/{id}', 'PdfController@pdf_solicitud_teleinterconsulta');
Route::get('/resultado_validacion_referencia/{id?}', 'ReferenciasController@resultado_validacion_referencia');
Route::get('/hospitalizacion/evolucion/generar-orden-traslado/{id_evaluacion}', 'EvolucionHospitalizacionController@generateOrdenTrasladoPdf');
Route::post('/hospitalizacion/evolucion/firmar-order-traslado/{id_evaluacion}', 'EvolucionHospitalizacionController@firmarOrdenTraslado');

Route::put('/hospitalizacion/evolucion/actualizar-order-traslado/{id_evaluacion}', 'EvolucionHospitalizacionController@updateEvaluacionOrdenTraslado');

Route::get('/obtener_intervencion_para_anestesia/{id_intervencion}', 'CentroQuirurgicoController@obtenerIntervencionParaAnestesia');

Route::get('/invalidar_orden_imagenes/{opcion}/{tipo}/{id_radiologia}/{observacion}/{id_cita?}', 'RadiologiaController@invalidarOrdenImagenes');

Route::get('/enviar_masivo_ws/{opcion}', 'AuditoriaController@enviar_masivo_ws');
Route::post('validar_productos_auditoria', ['uses' => 'AuditoriaController@validar_productos_auditoria','as' => 'validar_productos_auditoria']);

Route::post('/refirma/actualiza_rua', 'ExcelController@actualizar_rua')->name('actualiza_rua');
Route::post('/api/historial-consulta/registrar-session', 'HistorialConsultaController@saveReasonWithAuthSession');
Route::post('/auditoria-hc/registrar', 'AuditoriaHcController@registrar');
Route::get('/obtener_intervencion_para_anestesia/{id_intervencion}', 'CentroQuirurgicoController@obtenerIntervencionParaAnestesia');
Route::get('/otros_reportes_by_imagenes', 'RadiologiaController@otros_reportes_by_imagenes');

//Reporte Imagenes
Route::post('/exportar_reporte_imagenes_por_estudio', 'ExcelController@exportar_reporte_imagenes_por_estudio')->name('exportar_reporte_imagenes_por_estudio');;
Route::get('/download_reportes_imagenes_por_estudio/{name_reporte}', 'ExcelController@descargarReporteDelStorageImagenesPorEstudio')->middleware('check.server.resources');

//Laboratorio Registro de citas
Route::get('/listar_ordenes_laboratorio_cita_origen/{nro_doc}/{id_servicio?}', 'LaboratorioController@listarOrdenesLaboratorioCitaOrigen');
Route::get('/listar_ordenes_laboratorio_cita_origen2/{nro_doc}/{id_cqx_intervencion}', 'LaboratorioController@listarOrdenesLaboratorioCitaOrigen2');
Route::get('/listar_ordenes_laboratorio_cita_origen_cita_resultado/{nro_doc}', 'LaboratorioController@listarOrdenesLaboratorioCitaOrigenCitaResultado');
Route::get('/modal_historial_cita_laboratorio/{dni_beneficiario}', 'LaboratorioController@modal_historial_cita_laboratorio');
//Consejeria
Route::get('/all_consejeria/{iduser?}', 'ConsejeriaController@all_consejeria');
Route::get('/ver_prestaciones_consejeria/{id}', 'ConsejeriaController@ver_prestaciones_consejeria');
Route::get('/consejeria/generar-pdf/{id}', 'ConsejeriaController@generatePdf');
Route::post('/consejeria/firmar-pdf/{id}', 'ConsejeriaController@firmarPdf');
Route::put('/consejeria/actualizar-firma/{id}', 'ConsejeriaController@updateFirma');
Route::get('/consejeria/{id}', 'ConsejeriaController@edit');
Route::put('/api/consejeria/{id}', 'ConsejeriaController@update');
Route::put('/api/consejeria/cambio-estado/{id}', 'ConsejeriaController@changeStatus');
Route::get('/consejeria/{id}', 'ConsejeriaController@edit');
Route::put('/api/consejeria/{id}', 'ConsejeriaController@update');
Route::put('/api/consejeria/cambio-estado/{id}', 'ConsejeriaController@changeStatus');

//CONSEJERIA FORM
Route::get('/formulario_atencion_hospitalizacion_consejeria/{id_hospitalizacion}', 'ConsejeriaController@create');
Route::post('/formulario_atencion_hospitalizacion_consejeria', 'ConsejeriaController@store')->name('consejeria.store');
Route::get('/ver_atencion_consejeria/{id_consejeria}/{id_hospitalizacion}', 'ConsejeriaController@ver_atencion_consejeria');

Route::post('all_hospitalizado_consejeria_ajax', [
  'uses' => 'ConsejeriaController@all_hospitalizado_consejeria_ajax',
  'as' => 'all_hospitalizado_consejeria_ajax'
]);

Route::post('all_hospitalizado_consejeria_ajax2', [
  'uses' => 'ConsejeriaController@all_hospitalizado_consejeria_ajax2',
  'as' => 'all_hospitalizado_consejeria_ajax2'
]);


Route::post('all_emergencia_consejeria_ajax', [
  'uses' => 'ConsejeriaController@all_emergencia_consejeria_ajax',
  'as' => 'all_emergencia_consejeria_ajax'
]);


Route::post('all_emergencia_consejeria_ajax2', [
  'uses' => 'ConsejeriaController@all_emergencia_consejeria_ajax2',
  'as' => 'all_emergencia_consejeria_ajax2'
]);

Route::post('all_hospitalizado_consejeria_ajax_params/{fecha_ini}/{fecha_fin}/{ndocumento}/{id_servicio_destino}/{busqueda_datos}/{estado}/{NuemroPagina}/{NumeroRegistros}',
'ConsejeriaController@all_hospitalizado_consejeria_ajax_params');

Route::post('all_emergencia_consejeria_ajax_params/{fecha_ini}/{fecha_fin}/{ndocumento}/{id_servicio_destino}/{busqueda_datos}/{estado}/{NuemroPagina}/{NumeroRegistros}',
'ConsejeriaController@all_emergencia_consejeria_ajax_params');

Route::post('all_historial_consejeria_ajax', [
  'uses' => 'ConsejeriaController@all_historial_consejeria_ajax',
  'as' => 'all_historial_consejeria_ajax'
]);

Route::post('all_historial_emergencia_consejeria_ajax', [
  'uses' => 'ConsejeriaController@all_historial_emergencia_consejeria_ajax',
  'as' => 'all_historial_emergencia_consejeria_ajax'
]);

//CONSEJERIA
Route::post('/validar_registro_consejeria', 'ConsejeriaController@validarRegistroConsejeria')->name('consejeria.verificacion');
Route::post('/visor_camas_filtro/getFiltros', 'CamaController@GetCamaForFiltros');
Route::post('visor_camas_filtro', [
  'uses' => 'CamaController@visor_camas_filtro',
  'as' => 'visor_camas_filtro'
]);

//CONSEJERIA EMERGENCIA
Route::get('/all_consejeria_by_emergencia/{iduser?}', 'ConsejeriaController@all_consejeria_by_emergencia');
Route::get('/ver_prestaciones_emergencia_consejeria/{id}', 'ConsejeriaController@ver_prestaciones_emergencia_consejeria');
Route::get('/consejeria_emergencia/generar-pdf/{id}', 'ConsejeriaController@generatePdfEmergencia');
Route::post('/consejeria_emergencia/firmar-pdf/{id}', 'ConsejeriaController@firmarPdfEmergencia');
Route::put('/consejeria_emergencia/actualizar-firma/{id}', 'ConsejeriaController@updateEmergenciaFirma');
Route::get('/consejeria_emergencia/{id}', 'ConsejeriaController@edit_emergencia');
Route::put('/api/consejeria_emergencia/{id}', 'ConsejeriaController@update_emergencia');
Route::put('/api/consejeria_emergencia/cambio-estado/{id}', 'ConsejeriaController@changeEmergenciaStatus');

Route::post('all_emergencia_by_establecimiento_ajax', [
  'uses' => 'EmergenciaController@all_emergencia_by_establecimiento_ajax',
  'as' => 'all_emergencia_by_establecimiento_ajax'
]);

//CONSEJERIA EMERGENCIA FORM
Route::get('/formulario_atencion_emergencia_consejeria/{id_emergencia}', 'ConsejeriaController@create_consejeria_emergencia');
Route::post('/formulario_atencion_emergencia_consejeria', 'ConsejeriaController@store_emergencia')->name('consejeria.store_emergencia');
Route::get('/ver_atencion_consejeria_emergencia/{id_consejeria}/{id_emergencia}', 'ConsejeriaController@ver_atencion_consejeria_emergencia');

// ALTA FIRMA EPICRISIS Y PAPELETA DE EGRESO
Route::post('genera_alta_hosp_csv', [
  'uses' => 'ExcelController@genera_alta_hosp_csv',
  'as' => 'genera_alta_hosp_csv'
]);
//===================================== CAN =====================================
Route::get('/all_cans', 'CanController@index');
Route::get('/create_can', 'CanController@create')->name('create');
Route::post('store_can', [
  'uses' => 'CanController@store',
  'as' => 'store_can'
]);
Route::get('/edit_can/{id}', 'CanController@edit');
Route::get('/edit_producto_can/{id}', 'CanController@edit_producto_can');
Route::post('update_can', [
  'uses' => 'CanController@update',
  'as' => 'update_can'
]);
Route::post('listar_consultorio_establecimiento', [
  'uses' => 'EstablecimientoController@listar_consultorio_establecimiento',
  'as' => 'listar_consultorio_establecimiento'
]);
Route::post('store_consultorio_establecimiento_can', [
  'uses' => 'EstablecimientoController@store_consultorio_establecimiento_can',
  'as' => 'store_consultorio_establecimiento_can'
]);
Route::get('/monitorear_can/{id}', 'CanController@monitorear_can');
Route::get('/monitorear_can_servicio/{id_can}/{id_establecimiento}', 'CanController@monitorear_can_servicio');
Route::post('listar_can_ajax', [
  'uses' => 'CanController@listar_can_ajax',
  'as' => 'listar_can_ajax'
]);
Route::post('listar_productos_ajax', [
  'uses' => 'CanController@listar_productos_ajax',
  'as' => 'listar_productos_ajax'
]);

Route::get('/activar_establecimiento_servicio/{id}/{id_can}/{id_establecimiento}/{id_servicio}/{accion}', 'CanController@activar_establecimiento_servicio');
Route::post('update_activar', [
  'uses' => 'CanController@update_activar',
  'as' => 'update_activar'
]);
Route::get('/activar_todo_establecimiento_servicio/{id_can}/{id_establecimiento}', 'CanController@activar_todos_establecimiento_servicio');
Route::post('update_activar_todos', [
  'uses' => 'CanController@update_activar_todos',
  'as' => 'update_activar_todos'
]);
Route::get('/ampliar_tiempo_establecimiento_servicio/{id}/{id_can}/{id_establecimiento}/{id_servicio}', 'CanController@ampliar_tiempo_establecimiento_servicio');
Route::post('update_tiempo', [
  'uses' => 'CanController@update_tiempo',
  'as' => 'update_tiempo'
]);
Route::get('/ampliar_tiempo_total_establecimiento/{id_can}/{id_establecimiento}', 'CanController@ampliar_tiempo_total_establecimiento');
Route::get('/productos_servicios_can/{id_can}/{id_establecimiento}/{id_servicio}/{id_tipo_producto}', 'CanController@productos_servicios_can');
Route::post('listar_can_productos_servicio_ajax', [
  'uses' => 'CanController@listar_can_productos_servicio_ajax',
  'as' => 'listar_can_productos_servicio_ajax'
]);
Route::get('/listar_archivos_servicios_can/{id_can}/{id_establecimiento}/{id_servicio}/{accion}', 'CanController@listar_archivos_servicios_can');
Route::get('/productos_establecimientos_can/{id_can}/{id_establecimiento}/{id_tipo_producto}', 'CanController@productos_establecimientos_can');
Route::post('listar_can_productos_establecimiento_ajax', [
  'uses' => 'CanController@listar_can_productos_establecimiento_ajax',
  'as' => 'listar_can_productos_establecimiento_ajax'
]);
Route::get('/ver_productos_can/{can_id}/{tipo}', 'CanController@cargar_productos');
Route::post('update_consultorio_can', [
  'uses' => 'CanController@update_consultorio_can',
  'as' => 'update_consultorio_can'
]);
Route::post('listar_productos_establecimiento', [
  'uses' => 'CanController@listar_productos_establecimiento',
  'as' => 'listar_productos_establecimiento'
]);

Route::get('can/datos_consolidado/{can_id}/{establecimiento_id}/{tipo}', 'CanController@cargar_datos_consolidado')->name('can.cargar_datos_consolidado');

Route::post('grabar_producto_can', [
  'uses' => 'CanController@grabar_producto_can',
  'as' => 'grabar_producto_can'
]);

Route::get('/eliminar_producto_can/{id}', 'CanController@eliminar_producto_can');
Route::get('/cerrar_petitorio_can/{can_id}/{establecimiento_id}/{tipo}', 'CanController@cerrar_petitorio_can');
Route::get('/descargar_avance/{can_id}/{establecimiento_id}/{opt}/{type}/{valor}', 'CanController@exportEstimacionDataConsolidada');
Route::get('responsables/{can_id}', 'CanController@index_responsable');
Route::get('responsables/create/{can_id}/{opcion}', 'CanController@create_responsable');
Route::get('responsables/edit_responsable/{id}/{opcion}', 'CanController@edit_responsable');
Route::post('store_responsable', [
  'uses' => 'CanController@store_responsable',
  'as' => 'store_responsable'
]);
Route::post('update_responsable', [
  'uses' => 'CanController@update_responsable',
  'as' => 'update_responsable'
]);

Route::get('atencion_consultorios/{can_id}', 'CanController@atencion_consultorios');
Route::get('ver_consolidado/{tipo}/{can_id}', 'CanController@ver_consolidado_farmacia');

Route::get('pdf-export-can/{can_id}/{establecimiento_id}/{opt}/{servicio_id}/{ano}', 'CanController@pdf_estimacion_nivel1');

Route::get('listar_archivos_can/{can_id}/{establecimiento_id}', 'CanController@listar_archivos_can');

Route::get('consolidado_nacional_tipo/{id}/{tipo}', 'CanController@consolidado_nacional_tipo');
Route::get('consolidado_x_establecimiento_and_tp/{id}/{tipo_dispositivo}', 'CanController@consolidado_establecimiento_producto_tipo_dispositivo');
Route::get('can_x_servicio/{id}/{tipo}', 'CanController@establecimientos_can_x_servicio');
Route::get('nivel_total/{id}', 'CanController@nivel_total');
Route::get('establecimientos_can_tipo/{id}', 'CanController@establecimientos_can_tipo');
Route::get('up_archivos_can/{can_id}/{establecimiento_id}', 'CanController@up_archivos_can');
Route::get('up_archivos_rubro/{can_id}/{establecimiento_id}/{servicio_id}', 'CanController@up_archivos_rubro');
Route::post('subir_archivo_correccion', [
  'uses' => 'CanController@subir_archivo_correccion',
  'as' => 'subir_archivo_correccion'
]);

//Route::patch('can/subir_archivo_rubro/{id}/{can_id}/{establecimiento_id}/{servicio_id}', 'CanController@subir_archivo_rubro');
Route::get('listar_observaciones/{id}', 'CanController@listar_observaciones_nivel1');
Route::get('listar_archivos/{can_id}', 'CanController@listar_archivos_nivel1');
/****************/
Route::post('invalidar_receta_digital_quirurgica', [
  'uses' => 'AtencionQuirurgicaController@invalidar_receta_digital_quirurgica',
  'as' => 'invalidar_receta_digital_quirurgica'
]);


Route::get('api/obtener_prestaciones_por_upss_asegurado_fecha_atencion/{codigo_upss}/{nro_doc_ident}/{id_tipo_doc_ident}/{fecha_atencion}', 'RecetaValeController@findByCodigoUpssAseguradoFechaAtencion');
/***********Reglas de Validacion y Consistencia ***********/
Route::get('regla1/{dni_asegurado}', 'ReglasController@regla1');
// Route::get('regla13/{sexo}/{upss}/{cpms}/{tipo}', 'ReglasController@regla13');
// Route::get('regla16/{cpms}/{tipo_atencion}', 'ReglasController@regla16');
// Route::get('regla17/{cpms}/{tipo_atencion}', 'ReglasController@regla17');
Route::get('reglas/{sexo}/{upss}/{cpms}/{tipo}', 'ReglasController@reglas');// **** INTERCONSULTA
// **** INTERCONSULTA
Route::get('/exportar_lista_interconsulta/{id_user}/{fecha_ini}/{fecha_fin}/{num_documento}/{apellidos_nombres}/{estado}/{id_medico}/{id_servicio}/{tipo_atencion}', 'ExcelController@exportar_lista_interconsulta');
Route::get('/exportar_reporte_atencion_detallado_consejeria/{id_user}/{fecha_ini}/{fecha_fin}/{fecha_ini_registro}/{fecha_fin_registro}/{num_documento}/{estado}/{id_medico}/{id_servicio_destino}/{busqueda_datos}/{tipo_atencion}', 
'ExcelController@exportar_reporte_atencion_detallado_consejeria');

Route::get('get-atenciones-idasegurado/{id_asegurado}', 'EnfermeriaController@GetAtencionesByIdAsegurado');
/*******PRINT HC****/
Route::get('/print_hc/{id_prestacion}', 'PdfController@print_hc');

// Ruta para cargar datos de la tabla `upsses` otorrino
Route::get('/cargar_upsses', [
  'uses' => 'InterconsultasController@cargarUpsses',
  'as' => 'cargar_upsses'
]);
// Ruta para cargar datos de la tabla `upsses` imagenes
Route::get('/cargar_ups_diagnostico', [
  'uses' => 'InterconsultasController@cargarUpsDIAG',
  'as' => 'cargarupsdiag'
]);
Route::get('interconsulta_obtener_cantidad/{id_establecimiento}/{id_servicio}/{id_tipo_atencion}', 'InterconsultasController@interconsulta_obtener_cantidad');
Route::get('interconsulta_obtener_dia_semana_json', 'InterconsultasController@interconsulta_obtener_dia_semana_json');
/**************COSTOS **************/
Route::get('/mantenedor_costos', 'MantenedorCostosController@index');
Route::get('/nuevo_rhus', 'RecursosHumanosController@nuevoRhus');
Route::get('/listado_rhus', 'RecursosHumanosController@listadoRhus');

/**************TARIFARIO**************/
Route::get('/obtener_rhus', 'Tarifario@obtenerRHUS');
Route::get('/buscarRhusPorGrupoOcupacional', 'Tarifario@buscarRhusPorGrupoOcupacional');
Route::get('/buscarInsumosPorNombreProducto', 'Tarifario@buscarInsumosPorNombreProducto');
Route::get('/buscarEquipamientoPorNombre', 'Tarifario@buscarEquipamientoPorNombre');
Route::get('/buscarEquipamientoAdicionalPorNombre', 'Tarifario@buscarEquipamientoAdicionalPorNombre');
Route::get('/obtenerRhusPorId', 'Tarifario@obtenerRhusPorId');
Route::get('/obtenerInsumoPorId', 'Tarifario@obtenerInsumoPorId');
Route::get('/obtenerEquipamientoPorId', 'Tarifario@obtenerEquipamientoPorId');
Route::get('/obtenerEquipamientoAdicionalPorId', 'Tarifario@obtenerEquipamientoAdicionalPorId');
Route::get('/api/tm7_sg_y_sa/procedimiento/{procedimiento_id}', 'Tarifario@getServicioAdmsGrlsByProcedureId');
Route::post('listar_procedimientos_ajax_for_ficha', [
  'uses' => 'ProcedimientoController@listar_procedimientos_ajax_for_ficha',
  'as' => 'listar_procedimientos_ajax_for_ficha'
]);
Route::get('/listar_ficha_procedimientos/{id_user?}', 'ProcedimientoController@listFichaProcedimientos');
Route::get('/all_procedimientos_costeo', 'ProcedimientoController@listarProcedimientosCosteo');
Route::get('/editar_ficha_procedimiento', 'ProcedimientoController@editarFichaProcedimiento');
Route::get('/ficha_procedimiento/{procedimiento_id}', 'ProcedimientoController@fichaProcedimiento');


Route::put('/api/radiologia/{id}/update-code-siteds', 'RadiologiaController@updateCodeSiteds');

Route::get('/api/enfermeria/tipo-atencion/{id}/{id_tipo_atencion}', 'EnfermeriaController@getDetailByIdTipoAtencionId');

Route::get('/api/obtener-ultima-terapeutica/asegurado/{id_asegurado}', 'EnfermeriaController@getLatestTerapeuticaByIdAsegurado');

Route::post('anular_atencion_hospitalizacion', [
  'uses' => 'HospitalizacionController@anularAtencionHospitalizacion',
  'as' => 'anularAtencionHospitalizacion'
]);
Route::get('/modal_paciente_hospitalizacion/{id}', 'HospitalizacionController@modal_paciente_hospitalizacion');
Route::post('anular_paciente_hospitalizacion', [
  'uses' => 'HospitalizacionController@anular_paciente_hospitalizacion',
  'as' => 'anular_paciente_hospitalizacion'
]);
Route::post('/anular_prestacion_emergencia', 'EmergenciaController@anular_prestacion_emergencia');
Route::post('/anular_prestacion', 'CitaController@anular_prestacion');
Route::post('/anular_evaluacion', 'EvaluacionController@anular_evaluacion');

Route::post('anular_interconsulta', [
  'uses' => 'InterconsultasController@anular_interconsulta',
  'as' => 'anular_interconsulta'
]);

Route::post('anular_interconsulta2', [
  'uses' => 'InterconsultasController@anular_interconsulta2',
  'as' => 'anular_interconsulta2'
]);

Route::post('receta_anular_json', [
  'uses' => 'RecetaValeController@receta_anular_json',
  'as' => 'receta_anular_json'
]);

Route::post('laboratorio_orden_anular_json', [
  'uses' => 'LaboratorioController@laboratorio_orden_anular_json',
  'as' => 'laboratorio_orden_anular_json'
]);

Route::post('radiologia_orden_anular_json', [
  'uses' => 'RadiologiaController@radiologia_orden_anular_json',
  'as' => 'radiologia_orden_anular_json'
]);

Route::post('diagnostico_anulacion_validar_json', [
  'uses' => 'DiagnosticoController@diagnostico_anulacion_validar_json',
  'as' => 'diagnostico_anulacion_validar_json'
]);

Route::get('/verifica_pendiente_alta_emergencia_por_dni', 'HospitalizacionController@verificaPendienteAltaEmergenciaByDni');
Route::get('/verifica_pendiente_alta_emergencia_hospitalizacion_por_dni', 'RecetaValeController@verificaPendienteAltaEmergenciaHospitalizacionByDni');
Route::get('/listar_ordenes_procedimientos_pendientes/{dni_beneficiario}', 'RecetaValeController@listar_ordenes_procedimientos_pendientes');
Route::get('/pp_servicios_procedimiento', 'RecetaValeController@pp_servicios_procedimiento');
Route::post('/pp_eliminar_procedimiento_solicitud', 'RecetaValeController@pp_eliminar_procedimiento_solicitud');
Route::get('/ver_solicitud_pp_pdf/{nro_solicitud}', 'RecetaValeController@ver_solicitud_pp_pdf');

Route::get('/api/ultima-receta/asegurado/{id_asegurado}/tipo-atencione/{id_tipo_atencion}', 'RecetaValeController@getLastRecipeAseguradoIdAndTypeAttention');

Route::get('/api/new-receta/dni/{dni_beneficiario}/tipo-atencion/{id_tipo_atencion}', 'RecetaValeController@getNewReceta');

Route::get('/api/ultima-receta/prestacion/{id_prestacion}', 'RecetaValeController@getLastRecipeByPrestacionId');

Route::get('/print_hospitalizacion_papeleta_egreso_atencion_quirurgica/{id_hospitalizacion}', 'PdfController@print_hospitalizacion_papeleta_egreso_atencion_quirurgica');
Route::get('/print_hospitalizacion_reporte_operatorio_cq/{id_hospitalizacion}', 'PdfController@print_hospitalizacion_reporte_operatorio_cq');
Route::get('/buscarEvolucion/{evolucion_id}', 'EnfermeriaEvolucionesController@obtenerEvolucion');

Route::get('buscar_diagnostico_por_id', [
  'uses' => 'EnfermeriaEvolucionesController@buscar_diagnostico_por_id',
  'as' => 'buscar_diagnostico_por_id'
]);

Route::get("/cargarDatosPrestacionEmergencia/{id_prestacion}", 'EmergenciaController@mostrar_atencion_emergencia_ajax');
Route::get("/cargarDatosPrestacionHospitalizacion/{id_prestacion}", 'HospitalizacionController@mostrar_atencion_hospitalizacion_ajax');

Route::get('/exportar_reporte_atencion_detallado_emergencia_consejeria/{id_user}/{fecha_ini}/{fecha_fin}/{fecha_ini_registro}/{fecha_fin_registro}/{num_documento}/{estado}/{id_medico}/{id_servicio_destino}/{busqueda_datos}/{tipo_atencion}', 
'ExcelController@exportar_reporte_atencion_detallado_emergencia_consejeria');
Route::get('get-atenciones-idasegurado/{id_asegurado}', 'EnfermeriaController@GetAtencionesByIdAsegurado');

Route::get('/api/users/actives', 'LogeoController@getCountUsersActives');
//Route::get("/cargarDatosPrestacionEmergencia/{id_prestacion}", 'EmergenciaController@mostrar_atencion_emergencia_ajax');
// Servicios por Establecimiento
Route::get('/servicio_establecimiento/{id_servicio}', 'ConsultorioController@servicio_establecimiento_listar');
Route::get('/servicio_establecimiento/validar_existe/{id_servicio}/{id_establecimiento}', 'EstablecimientoController@servicio_establecimiento_validar_existe');
Route::get('/servicio_establecimiento/activar/{id}', 'EstablecimientoController@servicio_establecimiento_activar');
Route::get('/servicio_establecimiento/desactivar/{id}', 'EstablecimientoController@servicio_establecimiento_desactivar');
Route::get('/servicio_establecimiento/editar/{id}', 'ConsultorioController@servicio_establecimiento_editar');
Route::post('servicio_establecimiento_update', [
  'uses' => 'EstablecimientoController@servicio_establecimiento_update',
  'as' => 'servicio_establecimiento_update'
]);
Route::post('servicio_establecimiento_store', [
  'uses' => 'EstablecimientoController@servicio_establecimiento_store',
  'as' => 'servicio_establecimiento_store'
]);
Route::post('sinTokensInterconsultaRpta', [
  'uses' => 'ExcelController@sinTokensInterconsultaRpta',
  'as' => 'sinTokensInterconsultaRpta'
]);

Route::get('/api/nivel-1-2/laboratorio/reporte','LaboratorioController@generateReportOrdenesNivel1y2');
Route::get('/laboratorio/rua/pdf/{id_laboratorio}','LaboratorioController@generateRUAPdf');
Route::put('/api/nivel-1-2/laboratorio/{id_laboratorio}/reprogramar-cita','LaboratorioController@reprogramarCitaNivel12');
Route::put('/api/nivel-1-2/laboratorio/{id_laboratorio}/anular','LaboratorioController@anularNivel1y2');

/*SEIS*/
Route::get('/listar_recursos_salud', 'RecursosSaludController@index');
Route::post('agregar_ipress_recurso', [
  'uses' => 'RecursosSaludController@agregar_ipress_recurso',
  'as' => 'agregar_ipress_recurso'
]);
Route::get('/desactivar_ipress_recursos_salud/{id}', 'RecursosSaludController@desactivar_ipress_recurso');
Route::get('/desactivar_ipress_recurso_detalle/{id}', 'RecursosSaludController@desactivar_ipress_recurso_detalle');
Route::post('/recursos_salud/store', 'RecursosSaludController@store')->name('recursos_salud.store');
Route::get('/recursos_salud/show/{id}', 'RecursosSaludController@show')->name('recursos_salud.show');
Route::get('/recursos_salud/edit/{id}', 'RecursosSaludController@edit')->name('recursos_salud.edit');
Route::post('/recursos_salud/update/{id}', 'RecursosSaludController@update')->name('recursos_salud.update');
Route::post('/recursos_salud/filtrar_por_fecha', 'RecursosSaludController@filtrarPorFecha')
     ->name('recursos_salud.filtrar_por_fecha');


Route::get('/add_ipress_recurso_detalle/{id_ipress_recurso_salud}/{id_ipress}', 'RecursosSaludController@add_ipress_recurso_detalle');
Route::get('/recursos_salud/create/{id_ipress_recurso_salud}/{id_ipress}', 'RecursosSaludController@create')->name('recursos_salud.create');

Route::get('/ubicacion_servicio', 'UbicacionServicioController@index')->name('ubicacion_servicio.index');
Route::post('/ubicacion_servicio/clase/store', 'UbicacionServicioController@storeClase')->name('ubicacion_servicio.clase.store');
Route::post('/ubicacion_servicio/tipo/store', 'UbicacionServicioController@storeTipo')->name('ubicacion_servicio.tipo.store');
Route::get('/ubicacion_servicio/listar_tipos/{id_clase}', 'UbicacionServicioController@listarTipos')->name('ubicacion_servicio.listar_tipos');
Route::get('/ubicacion_servicio/clase/delete/{id}', 'UbicacionServicioController@eliminarClase')->name('ubicacion_servicio.clase.delete');
Route::get('/ubicacion_servicio/tipo/delete/{id}', 'UbicacionServicioController@eliminarTipo')->name('ubicacion_servicio.tipo.delete');
Route::post('/ubicacion_servicio/clase/update', 'UbicacionServicioController@updateClase')->name('ubicacion_servicio.clase.update');
Route::post('/ubicacion_servicio/tipo/update', 'UbicacionServicioController@updateTipo')->name('ubicacion_servicio.tipo.update');
Route::get('/ubicacion_servicio/listar_todos_tipos', 'UbicacionServicioController@listarTodosTipos');

// Vista principal
Route::get('programacion_asistencial', [
    'as' => 'programacion_asistencial.index',
    'uses' => 'ProgramacionAsistencialController@index'
]);

// AJAX: listar tipos por clase
Route::get('programacion_asistencial/listar_tipos/{id_clase}', [
    'as' => 'programacion_asistencial.listar_tipos',
    'uses' => 'ProgramacionAsistencialController@listarTipos'
]);

// AJAX: buscar programación por establecimiento y periodo
Route::post('/programacion_asistencial/buscar', [
    'as' => 'programacion_asistencial.buscar',
    'uses' => 'ProgramacionAsistencialController@buscarPorEstablecimiento'
]);

// AJAX: guardar nuevo registro
Route::post('programacion_asistencial/store', [
    'as' => 'programacion_asistencial.store',
    'uses' => 'ProgramacionAsistencialController@store'
]);

// AJAX: actualizar registro
Route::post('programacion_asistencial/update/{id}', [
    'as' => 'programacion_asistencial.update',
    'uses' => 'ProgramacionAsistencialController@update'
]);

// AJAX: anular registro
Route::get('programacion_asistencial/destroy/{id}', [
    'as' => 'programacion_asistencial.destroy',
    'uses' => 'ProgramacionAsistencialController@destroy'
]);


Route::post('programacion_asistencial/eliminar', [
    'as' => 'programacion.eliminar',
    'uses' => 'ProgramacionAsistencialController@eliminar'
]);
Route::post('programacion_asistencial/eliminarTodos', [
    'as' => 'programacion.eliminarTodos',
    'uses' => 'ProgramacionAsistencialController@eliminarTodos'
]);


Route::group(['prefix' => 'atencion_estrategia_sanitaria'], function () {
    Route::get('/', 'AtencionEstrategiaSanitariaController@index')->name('atencion.estrategia.index');
    Route::post('listar', 'AtencionEstrategiaSanitariaController@listar');
    Route::post('guardar', 'AtencionEstrategiaSanitariaController@guardar')->name('atencion.estrategia.guardar');
    Route::get('obtener/{id}', 'AtencionEstrategiaSanitariaController@obtener');
    Route::delete('eliminar/{id}', 'AtencionEstrategiaSanitariaController@eliminar');
    Route::put('/{id}', 'AtencionEstrategiaSanitariaController@actualizar')
     ->name('atencion.estrategia.actualizar');
});


Route::get('diagnostico/autocomplete', 'DiagnosticoController@diagnostico_autocomplete');

// Estrategias sanitarias - búsqueda y gestión en sesión
Route::get('estrategia_sanitaria/autocomplete', 'EstrategiaSanitariaController@buscar');
Route::get('paciente/datos', 'PacienteController@datosPaciente');
Route::get('detalle_paciente_creacion/{id}', 'PacienteController@detalle_paciente_creacion');
Route::get('/exportar_asegurados_ipress/{id}', 'ExcelController@exportar_asegurados_ipress');
Route::get('evoHosCtrl_obtener_datos_persona', [ 
  'uses' => 'EvolucionHospitalizacionController@evoHosCtrl_obtener_datos_persona',
  'as' => 'evoHosCtrl_obtener_datos_persona'
]);
Route::get('evoHosCtrl_guardar_datos_persona', [ 
  'uses' => 'EvolucionHospitalizacionController@evoHosCtrl_guardar_datos_persona',
  'as' => 'evoHosCtrl_guardar_datos_persona'
]);
Route::get('/print_atencion_quirurgica_epicrisis/{id_intervencion}', 'PdfController@print_atencion_quirurgica_epicrisis');
Route::post('/api/consulta-externa/atencion-adicional/intervencion-quirurgica', 'RecetaValeController@registrarIntervencionQuirurgicaAtencionAdicional');

//================================================================ DOCUMENTOS SIN FIRMAR =================================================================================
Route::get('/ver_interconsulta_imprimir/{id}/false', 'ExcelController@genera_pdf_interconsulta');
Route::get('/ver_interconsulta_rpta/{id}/false', 'ExcelController@genera_pdf_interconsulta_rpta');
Route::post('/refirma/actualiza_prestacion_mod_docsinfirmar', 'DocSinFirmarController@actualiza_prestacion_mod_docsinfirmar')->name('actualiza_prestacion_mod_docsinfirmar');
Route::get('/all_docs_sin_firmar/', 'DocSinFirmarController@all_docs_sin_firmar');
Route::get('/obtener_cantidad_docs_sinfirmar_by_user', 'DocSinFirmarController@obtener_cantidad_docs_sinfirmar_by_user');
Route::post('listar_docsinfirmar_ajax', [
  'uses' => 'DocSinFirmarController@listar_docsinfirmar_ajax',
  'as' => 'listar_docsinfirmar_ajax'
]);
Route::post('genera_csv_docsinfirmar', [
  'uses' => 'DocSinFirmarController@genera_csv_docsinfirmar',
  'as' => 'genera_csv_docsinfirmar'
]);
Route::post('listar_notas_enfermeria_ajax', [
  'uses' => 'DocSinFirmarController@listar_notas_enfermeria_ajax',
  'as' => 'listar_notas_enfermeria_ajax'
]);
Route::post('genera_csv_notas_enfermeria', [
  'uses' => 'DocSinFirmarController@genera_csv_notas_enfermeria',
  'as' => 'genera_csv_notas_enfermeria'
]);
Route::post('actualizar_flag_docsinfirmar_enfermeria', [
  'uses' => 'DocSinFirmarController@actualizar_flag_docsinfirmar_enfermeria',
  'as' => 'actualizar_flag_docsinfirmar_enfermeria'
]);
Route::get('/ver_prestacion_atencion_imprimir/{id_prestacion}', 'PdfController@ver_prestacion_atencion_imprimir');
Route::get('obtener_tipo_atencion_prestacion/{id_prestacion}/{doc_firmar}', [
  'uses' => 'DocSinFirmarController@obtener_tipo_atencion_prestacion',
  'as' => 'obtener_tipo_atencion_prestacion'
]);
Route::get('/verificar_firma_doc/{tipo}/{id}/{id_principal}', 'DocSinFirmarController@verificar_firma_doc');

Route::post('listar_docsfirmados_ajax', [
  'uses' => 'DocSinFirmarController@listar_docsfirmados_ajax',
  'as' => 'listar_docsfirmados_ajax'
]);
Route::get('/ver_evaluacion_firmar/{id}', 'PdfController@ver_evaluacion_firmar');
Route::get('/ver_evolucion_firmar/{id}', 'PdfController@ver_evolucion_firmar');
Route::get('/verificar_firma_evol/{idEmergencia}/{idtipoAt}','DocSinFirmarController@verificar_firma_evol'); 
Route::get('/firmado_evol_id/{idEvol}','DocSinFirmarController@firmado_evol_id');
Route::get('/firmado_interconsulta_rpta_id/{idInterconsulta}','DocSinFirmarController@firmado_interconsulta_rpta_id');
Route::get('/datos_regreso_interconsulta_rpta/{idInterconsulta}','DocSinFirmarController@datos_regreso_interconsulta_rpta');
Route::get('/ver_alta_hospitalizacion/{id}', 'PdfController@ver_alta_hospitalizacion');
Route::post('/es_quirurgico_docsinfirmar/{idPrestacion}','DocSinFirmarController@es_quirurgico_docsinfirmar');
Route::post('exportar_reporte_firmas_pendientes', [
  'uses' => 'ExcelController@exportar_reporte_firmas_pendientes',
  'as' => 'exportar_reporte_firmas_pendientes'
]);
Route::get('/obtener-departamentos', 'ConsultorioController@obtenerDepartamentos');
Route::post('listar_firm_pend_ajax', [
  'uses' => 'DocSinFirmarController@listar_firm_pend_ajax',
  'as' => 'listar_firm_pend_ajax'
]);
Route::post('listar_firm_pend_enfermeria_ajax', [
  'uses' => 'DocSinFirmarController@listar_firm_pend_enfermeria_ajax',
  'as' => 'listar_firm_pend_enfermeria_ajax'
]);
Route::post('exportar_reporte_firmas_pendientes_enfermeria', [
  'uses' => 'ExcelController@exportar_reporte_firmas_pendientes_enfermeria',
  'as' => 'exportar_reporte_firmas_pendientes_enfermeria'
]);
Route::post('listar_altas_pendientes_ajax', [
  'uses' => 'DocSinFirmarController@listar_altas_pendientes_ajax',
  'as' => 'listar_altas_pendientes_ajax'
]);
Route::post('exportar_reporte_altasemg_pendientes', [
  'uses' => 'ExcelController@exportar_reporte_altasemg_pendientes',
  'as' => 'exportar_reporte_altasemg_pendientes'
]);
Route::post('exportar_reporte_altasemg_pendientes_det', [
  'uses' => 'ExcelController@exportar_reporte_altasemg_pendientes_det',
  'as' => 'exportar_reporte_altasemg_pendientes_det'
]);
Route::post('listar_altas_pendientes_hsp_ajax', [
  'uses' => 'DocSinFirmarController@listar_altas_pendientes_hsp_ajax',
  'as' => 'listar_altas_pendientes_hsp_ajax'
]);
Route::post('exportar_reporte_altashsp_pendientes', [
  'uses' => 'ExcelController@exportar_reporte_altashsp_pendientes',
  'as' => 'exportar_reporte_altashsp_pendientes'
]);
Route::post('exportar_reporte_altashsp_pendientes_det', [
  'uses' => 'ExcelController@exportar_reporte_altashsp_pendientes_det',
  'as' => 'exportar_reporte_altashsp_pendientes_det'
]);
//========================================================================================================================================================================
Route::get('/api/cqx_profesionales/establecimiento/{id_establecimiento}/consultorio/{id_consultorio}', 'CqxProfesionalController@getByEstablecimientoConsultorio');
Route::get('/api/cqx_tipo_professionales', 'CqxTipoProfesionalController@getAllActivos');
Route::get('/api/cqx_dias', 'CqxDiaController@getAllActivos');
Route::get('/api/centro-quirurgico/intervencion/validar', 'CentroQuirurgicoController@validarIntervencion');
Route::get('/api/centro-quirurgico/intervencion/{id_cqx_intervenciones}', 'CentroQuirurgicoController@obtenerIntervencionPorId');

Route::get('/obtener_subunidad_por_unidad/{id_unidad}', 'EstablecimientoController@obtenerSubUnidadXUnidad');
Route::get('/detalle_medico/{id}/{id_farmacia}', 'MedicoController@obtenerDetalleMedico');
Route::post('/actualizar-telefono', 'CentroQuirurgicoController@actualizarTelefono');
Route::get('/servicio-pertenece-cqx/{id}', 'RecetaValeController@servicioFlagCentroQuirurgico');


//================================= ROUTES FOR CQX - NIVEL 2 ===============================================================
Route::get('/intervenciones/{id}/atender', 'CentroQuirurgicoController@atenderIntervencionNivel2');
Route::get('/intervenciones/{id}/ver-detalle', 'CentroQuirurgicoController@verDetalleIntervencionNivel2');
Route::get('/api/cqx_intervenciones/alta/nivel2/{id}', 'CentroQuirurgicoController@altaIntervencionNivel2');
Route::get('/api/cqx_intervenciones', 'CentroQuirurgicoController@obtenerIntervencionesNivel2');
Route::get('/api/cqx_intervenciones/{id}', 'CentroQuirurgicoController@obtenerIntervencionNivel2ById');
Route::patch('/api/cqx_intervenciones/{id}/suspender', 'CentroQuirurgicoController@suspenderIntervencionNivel2');
Route::get('/api/cqx_tipo_professionales/flag_profesional/{flag_profesional}', 'CqxTipoProfesionalController@getByFlagProfesional');
Route::post('/api/cqx_intervenciones/atender/nivel2', 'CentroQuirurgicoController@registrarAtencionCqxNivel2');
Route::get('/api/cqx_intervenciones/{id}/alta/nivel2', 'CentroQuirurgicoController@obtenerAltaIntervencionNivel2');
Route::put('/api/cqx_intervenciones/alta/nivel2', 'CentroQuirurgicoController@registrarAltaIntervencionNivel2');
Route::get('/api/cqx_intervenciones/{id}/pdf', 'CentroQuirurgicoController@generarPdfAtencionCqxNivel2');
Route::get('/api/cqx_intervenciones/{id}/pdf_alta_crisis/nivel_2', 'CentroQuirurgicoController@generarPdfAltaCrisisCqxNivel2');
Route::get('/api/cqx_intervenciones/{id}/pdf_papelete_egreso/nivel_2', 'CentroQuirurgicoController@generarPdfPapeletaEgresoCqxNivel2');
Route::get('/api/cqx_intervenciones/{id}/firmar-pdf', 'CentroQuirurgicoController@firmarPdfIntervencionNivel2');
Route::post('/refirma/postArgumentsCqxIntervencionNivel2', 'RefirmaController@postArgumentsCqxIntervencionNivel2')->name('postArgumentsCqxIntervencionNivel2');
Route::post('/api/cqx_intervenciones/firmar-pdf/confirmar', 'CentroQuirurgicoController@confirmarFirmaPdfIntervencionNivel2');
Route::get('/api/cqx_intervenciones/{id}/firmar-alta', 'CentroQuirurgicoController@firmarPdfAltaIntervencionNivel2');
Route::post('/api/cqx_intervenciones/firmar-alta/confirmar', 'CentroQuirurgicoController@confirmarFirmaAltaIntervencionNivel2');
Route::get('/api/cqx-intervenciones/{id}/hospitalizacion-admision/pdf', 'CentroQuirurgicoController@generaPdfAdmisionHospitalizacion');
Route::get('/api/cqx-intervenciones/report/nivel_2', 'CentroQuirurgicoController@generarReporteNivel2');
//==========================================================================================================================
Route::get('/verificar-alta-hospitalizacion-cqx/{dni}', 'HospitalizacionController@verificarAltaHospitalizacionCqx');
Route::post('/medico-datos-anulacion-cqx', 'CentroQuirurgicoController@medicoDatosAnulacion');
Route::post('/anular-intervencion-cqx', 'CentroQuirurgicoController@anularIntervencion');

Route::get('/vista_mopri_nivel_2/{id_trama?}', 'CentroQuirurgicoController@vista_mopri_nivel_2');
Route::post('listar_mopri_nivel2_ajax', [
  'uses' => 'CentroQuirurgicoController@listar_mopri_nivel2_ajax',
  'as' => 'listar_mopri_nivel2_ajax'
]);
Route::get('/ver_prestacion_cqx_sin_editar/{id_user}/{id_prestacion}', 'RecetaValeController@ver_prestacion_cqx_sin_editar');
Route::get('/api/cqx_tipo_professionales_cirujanos', 'CqxTipoProfesionalController@getAllCirujanosActivos');

Route::post('/buscar-datos-programacion-qx','CentroQuirurgicoController@buscarDatosProgramacion')->name('buscar.datos.programacion');
Route::post('/eliminar-formato-mopri', 'CentroQuirurgicoController@eliminarFormatoMopri')->name('eliminar.formato.mopri');
Route::post('/eliminar-procedimiento-cqx','CentroQuirurgicoController@eliminarProcedimiento')->name('eliminar.procedimiento');
Route::post('/eliminar-profesional-cqx','CentroQuirurgicoController@eliminarProfesional')->name('cirujano.eliminar');
Route::post('/actualizar-suspension-general-cqx/{id_intervencion}/{id_prestacion}', 'CentroQuirurgicoController@actualizarSuspensionGeneral');
Route::get('/print_hospitalizacion_papeleta_egreso_atencion_quirurgica_no_tratado/{id_hospitalizacion}/{id_prestacion_programacion}', 'PdfController@print_hospitalizacion_papeleta_egreso_atencion_quirurgica_no_tratado');
//***FARMACOTECNIA***//
Route::get('/formula_galenicos/{id_trama?}', 'FarmacotecniaController@all_productos_galenicos');
Route::get('/ver_formula_galenico/{id_trama?}', 'FarmacotecniaController@ver_formula_galenico');
Route::get('/calculo_formula_galenicos/{id_trama?}', 'FarmacotecniaController@calculo_formula_galenicos');
Route::post('/reporte_galenicos_precio', 'FarmacotecniaController@reporte_galenicos_precio')->middleware('check.server.resources');

Route::post('/reporte-excel', 'MedicoController@generar_reporte');

Route::get('/hospitalizacion/evolucion/edit/{id_evaluacion}', 'EvolucionHospitalizacionController@edit_evolucion_2');
Route::post('hospitalizacion_evolucion_update', [
  'uses' => 'EvolucionHospitalizacionController@hospitalizacion_evolucion_update',
  'as' => 'hospitalizacion_evolucion_update'
]);

Route::get('/descargar_detalle_medico/{id}/{id_farmacia}', 'MedicoController@descargarDetallePDF');

Route::post('/desactivar_medico_saenz/{id}/{estado}', 'MedicoController@desactivar_saenz');

Route::post('obtener_estados_historial', 'MedicoController@get_estados_historial');

Route::post('listar_medicos_by_ipress_saenz', [
  'uses' => 'MedicoController@listar_medicos_ajax_por_ipress_saenz',
  'as' => 'listar_medicos_by_ipress_saenz'
]);

Route::get('/editar_medico_saenz/{id}/{id_farmacia}', 'MedicoController@editar_medico_saenz');

Route::post('/desactivar_medico/{id}/{estado}', 'MedicoController@desactivar');

Route::post('store_medico_saenz', [
  'uses' => 'MedicoController@store_saenz',
  'as' => 'store_medico_saenz'
]);

Route::post('update_medico_saenz', [
  'uses' => 'MedicoController@update_medico_saenz',
  'as' => 'update_medico_saenz'
]);
Route::post('ajax_validar_evolucion_qx', 'EvolucionHospitalizacionController@ajax_validar_evolucionqx');
Route::post('guardar_tipo_anestesia_producto','ConsultorioController@guardarTipoAnestesiaProducto');

Route::get('/descargar_constancia_trabajo/{id}/{id_farmacia}', 'MedicoController@descargarConstanciaTrabajo');

Route::get('usuarios/datatable', 'UsuarioController@datatable');

Route::get('/api/consultorios/farmacia/{farmacia_id}', 'ConsultorioController@getConsultoriosByFarmaciaId');
Route::get('/api/receta-vale/{id}', 'RecetaValeController@obtenerRecetaById');
Route::get('/api/medicos/turno/{turno}/establecimiento/{establecimiento_id}/consultorio/{consultorio_id}', 'MedicoController@getMedicoByTurnoEstablecimientoConsultorio');
Route::get('/api/medicos/establecimiento/{establecimiento_id}/consultorio/{consultorio_id}', 'MedicoController@obtenerMedicosPorIdIpress_IdConsultorio');
Route::put('/api/receta-vale/updated', 'RecetaValeController@actualizarRecetaVale');
Route::get('/api/receta-vale/farmacia/tipo_atencion/{farmacia_id}', 'RecetaValeController@geTipoAtencionesByFarmacia');

Route::post('/validar_pendiente_alta_emergencia_qx','CentroQuirurgicoController@validarIntervencionAltaPendienteEmergencia')->name('validar.pendiente.alta.emergencia.qx');
Route::post('/exportar_detallado_quirurgico', 'ExcelController@exportar_detallado_quirurgico');
Route::get('/obtener_productos_atencion_quirurgica/{id_ipress}/{id_farmacia}/{id_consultorio}', 'ProductoController@obtener_productos_atencion_quirurgica');

Route::get('/all_radiologia_prescritas_by_ipress_ticket/{iduser?}', 'TvController@all_radiologia_prescritas_by_ipress_ticket');

Route::post('listar_examenes_radiologicos_ticket', [
  'uses' => 'TvController@listar_examenes_radiologicos_ticket',
  'as' => 'listar_examenes_radiologicos_ticket'
]);

Route::get('/listar_examenes_radiologicos_ticket', 'TvController@listar_examenes_radiologicos_ticket');
Route::get('/llamadaAtendido_imagenes/{id_cita}/{id_ticket}', 'RadiologiaController@llamadaAtendido_imagenes');

Route::get('/detalle_medico_creacion/{id}', 'MedicoController@detalle_medico_creacion');
Route::get('/buscar_programacion_asistencial_1', 'ProgramacionAsistencialController@buscar_programacion_asistencial_1');
Route::get('/editar_programacion_asistencial_1/{id_personal}/{periodo}/{establecimiento}', 'ProgramacionAsistencialController@editar_programacion_asistencial_1');
Route::get('/editar_programacion_asistencial_2/{id_personal}/{periodo}/{establecimiento}', 'ProgramacionAsistencialController@editar_programacion_asistencial_2');
Route::post('guardar_programacion_asistencial_1', [
  'uses' => 'ProgramacionAsistencialController@guardar_programacion_asistencial_1',
  'as' => 'guardar_programacion_asistencial_1'
]);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////// API ASEGURADOS SALUDPOL 15-04-26
Route::post('/saludpol/consultar', 'WebServiceController@consultarSaludpol');
///////////////////////////////////////////////////
Route::post('update_paciente_new', [
  'uses' => 'PacienteController@update_paciente_new',
  'as' => 'update_paciente_new'
]);
Route::post('registrar_historia_clinica', [
  'uses' => 'PacienteController@registrar_historia_clinica',
  'as' => 'registrar_historia_clinica'
]);
Route::get('detalle_asegurado/{id}','PacienteController@obtenerDetalleAsegurado');
Route::post('registrar_paciente2',[
  'uses' => 'PacienteController@registrar_paciente2',
  'as' => 'registrar_paciente2'
]);
Route::post('/verificar_atq', 'AtencionQuirurgicaController@verificarAtq');
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::post('/guardar_anamnesis_imagenologia', 'RadiologiaController@guardarAnamnesisImagenologia');
Route::post('/actualizar_estado_imagenologia', 'RadiologiaController@actualizarEstadoImagenologia');

Route::get('/formulario_prestacion_hospitalizacionCU/{id_user}/{id_prestacion}', 'HospitalizacionController@formulario_prestacion_hospitalizacionCU');
Route::get('/form_atencion_prestacion_hspcu/{id_user}/{id_principal}', 'HospitalizacionController@form_atencion_prestacion_hspcu');
/////////////////////////////////////////modulo requeriemiento///////////////////////////////////////////////////////////////////////////////////////
Route::get('obtener_ultimo_diagnostico_emergencia/{id_emergencia}', 'MedicoController@obtener_ultimo_diagnostico_emergencia');
Route::get('/all_gestion_requerimientos', 'RequerimientosController@all_gestion_requerimientos');
Route::get('/all_historial_movimientos', 'RequerimientosController@all_historial_movimientos');
Route::get('/all_bandeja_ue020', 'RequerimientosController@all_bandeja_ue020');
Route::get('/all_ue020_area', 'RequerimientosController@all_ue020_area');
Route::get('/all_ue020_seccion', 'RequerimientosController@all_ue020_seccion');
Route::get('/all_ue020_actos_preparatorios', 'RequerimientosController@all_ue020_sub_seccion');
Route::get('/all_ue020_proc_seleccion', 'RequerimientosController@all_ue020_proc_seleccion');
Route::get('/all_ue020_ejecucion', 'RequerimientosController@all_ue020_ejecucion');
Route::get('/all_ue020_almacen', 'RequerimientosController@all_ue020_almacen');
Route::post("/guardar-requerimiento_pfdm", "RequerimientosController@guardarRequerimiento");
Route::post("/actualizar-requerimiento_pfdm", "RequerimientosController@actualizarRequerimiento");
Route::get("/listado_requerimientos-pfdm_paginado", "RequerimientosController@listarRequerimientos");
Route::get("/listado_requerimientos_all", "RequerimientosController@listarRequerimientosAll");
Route::get("/listado_requerimientos_entrada", "RequerimientosController@listarRequerimientosEntrada");
Route::get("/listado_requerimientos_procesos", "RequerimientosController@listarRequerimientosProceso");
Route::get("/listado_requerimientos_salidas", "RequerimientosController@listarRequerimientosSalida");
Route::get("/listado_requerimientos_procesados-pfdm_paginado", "RequerimientosController@listarRequerimientosProcesadosUE020");
Route::get("/listado_requerimientos_recibidos_abast", "RequerimientosController@listarRequerimientosRecibidosUE020");
Route::get("/listado_requerimientos_remitidos_seccion-pfdm_paginado", "RequerimientosController@listarRequerimientosRemitidosToSeccion");
Route::get("/ver-requerimiento_pfdm/{req_id}", "RequerimientosController@verRequerimientoPorId");
Route::get("/ver-movimiento_ap/{mov_id}", "RequerimientosController@verMovimientoInternoAp");
Route::get("/buscar-pfdm", "RequerimientosController@buscarPfDm");
Route::get("/buscar-responsable", "RequerimientosController@buscarResponsable");
Route::get("/buscar-ruc", "RequerimientosController@buscarPorRUC");
Route::get("/obtener-detalle-proveedor_productos", "RequerimientosController@detalleProductosAndProvedor");
Route::get("/exportar-csv_requerimiento-pfdm", "RequerimientosController@exportarCsv");
Route::post("/anular_requerimiento-pfdm/{req_id}", "RequerimientosController@anularRequerimientoPorId");
Route::get("/requerimiento/ver-movimientos/{req_id}", "RequerimientosController@verMovimientosRequerimiento");
Route::post("/requerimientos_pfdm/actualizar", "RequerimientosController@remitirRequerimiento");
Route::post("/requerimientos_pfdm/recepcionar_entrada", "RequerimientosController@recepcionarRequerimientoEntrada");
Route::post("/requerimientos_pfdm/recepcionar_abastecimiento", "RequerimientosController@recepcionarRequerimientoAbastecimiento");
Route::post("/requerimientos_pfdm/recepcionar_seccion", "RequerimientosController@recepcionarRequerimientoSeccion");
Route::post("/requerimientos_pfdm/observar_requerimiento_abast", "RequerimientosController@observarRequerimientoAbastecimiento");
Route::post("/requerimientos_pfdm/enviar_proceso", "RequerimientosController@enviarRequerimientoProceso");
Route::post("/requerimientos_pfdm/enviar_req_seccion", "RequerimientosController@enviarRequerimientoToSeccion");
Route::post("/requerimientos_pfdm/enviar_req_ss", "RequerimientosController@enviarRequerimientoToSs");
Route::post("/requerimientos_pfdm/enviar_req_pc", "RequerimientosController@enviarRequerimientoToPc");
Route::post("/requerimientos_pfdm/enviar_req_ej", "RequerimientosController@enviarRequerimientoToEj");
Route::post("/requerimientos_pfdm/enviar_req_al", "RequerimientosController@enviarRequerimientoToAl");
Route::get("/listado_requerimientos_entrada_ss", "RequerimientosController@listarRequerimientosEntradaSubSeccion");
Route::get("/listado_requerimientos_entrada_pc", "RequerimientosController@listarRequerimientosEntradaSubSeccionPc");
Route::get("/listado_requerimientos_entrada_ej", "RequerimientosController@listarRequerimientosEntradaSubSeccionEj");
Route::get("/listado_requerimientos_entrada_al", "RequerimientosController@listarRequerimientosEntradaSubSeccionAl");
Route::get("/listado_requerimientos_procesos_ss", "RequerimientosController@listarRequerimientosProcesoSs");
Route::get("/listado_requerimientos_procesos_pc", "RequerimientosController@listarRequerimientosProcesoPc");
Route::get("/listado_requerimientos_procesos_ej", "RequerimientosController@listarRequerimientosProcesoEj");
Route::get("/listado_requerimientos_procesos_al", "RequerimientosController@listarRequerimientosProcesoAl");
Route::get("/listado_requerimientos_salidas_ss", "RequerimientosController@listarRequerimientosSalidaSs");
Route::get("/listado_requerimientos_salidas_pc", "RequerimientosController@listarRequerimientosSalidaPc");
Route::get("/listado_requerimientos_salidas_ej", "RequerimientosController@listarRequerimientosSalidaEj");
Route::get("/listado_requerimientos_salidas_al", "RequerimientosController@listarRequerimientosSalidaAl");
Route::get("/listado_movimientos_ap", "RequerimientosController@listarMovimientosAp");
Route::post("/requerimientos_pfdm/recepcionar_entrada_ss", "RequerimientosController@recepcionarRequerimientoEntradaSs");
Route::post("/requerimientos_pfdm/recepcionar_entrada_pc", "RequerimientosController@recepcionarRequerimientoEntradaPc");
Route::post("/requerimientos_pfdm/recepcionar_entrada_ej", "RequerimientosController@recepcionarRequerimientoEntradaEj");
Route::post("/requerimientos_pfdm/recepcionar_entrada_al", "RequerimientosController@recepcionarRequerimientoEntradaAl");
Route::post("/requerimientos_pfdm/enviar_req_subseccion", "RequerimientosController@enviarRequerimientoToSubSeccion");
Route::post("/requerimientos_pfdm/registrar_movimiento_ap", "RequerimientosController@registrarMovimientoAp");
Route::post("/requerimientos_pfdm/registrar_movimiento_pc", "RequerimientosController@registrarMovimientoPc");
Route::post("/requerimientos_pfdm/registrar_movimiento_ej", "RequerimientosController@registrarMovimientoEj");

// Movimiento de Historias Clínicas
Route::get('/hc/movimiento/solicitud', [
    'uses' => 'HCMovimientoController@formSolicitud',
    'as' => 'hc_movimiento.form_solicitud',
]);
Route::post('/hc/movimiento/solicitud', [
    'uses' => 'HCMovimientoController@guardarSolicitud',
    'as' => 'hc_movimiento.guardar_solicitud',
]);
Route::get('/hc/movimiento/buscar-historia', [
    'uses' => 'HCMovimientoController@buscarHistorias',
    'as' => 'hc_movimiento.buscar_historias',
]);
Route::get('/hc/movimiento/usuarios-servicio', [
    'uses' => 'HCMovimientoController@usuariosPorServicio',
    'as' => 'hc_movimiento.usuarios_servicio',
]);
Route::get('/hc/movimiento/solicitudes', [
    'uses' => 'HCMovimientoController@listarSolicitudes',
    'as' => 'hc_movimiento.lista_solicitudes',
]);
Route::get('/hc/movimiento/solicitudes/exportar', [
    'uses' => 'HCMovimientoController@exportarSolicitudes',
    'as' => 'hc_movimiento.exportar_solicitudes',
]);
Route::get('/hc/movimiento/solicitud/{id}/detalle', [
    'uses' => 'HCMovimientoController@verSolicitud',
    'as' => 'hc_movimiento.ver_solicitud',
]);
Route::get('/hc/movimiento/solicitud/{id}/editar', [
    'uses' => 'HCMovimientoController@editarSolicitud',
    'as' => 'hc_movimiento.editar_solicitud',
]);
Route::post('/hc/movimiento/solicitud/{id}/actualizar', [
    'uses' => 'HCMovimientoController@actualizarSolicitud',
    'as' => 'hc_movimiento.actualizar_solicitud',
]);
Route::get('/hc/movimiento/recepcion', [
    'uses' => 'HCMovimientoController@formRecepcion',
    'as' => 'hc_movimiento.form_recepcion',
]);
Route::post('/hc/movimiento/recepcion/guardar', [
    'uses' => 'HCMovimientoController@guardarRecepcion',
    'as' => 'hc_movimiento.guardar_recepcion',
]);
Route::get('/hc/movimiento/solicitud/{id}/documento', [
    'uses' => 'HCMovimientoController@descargarDocumento',
    'as' => 'hc_movimiento.descargar_documento',
]);
Route::get('/hc/movimiento/ingresos', [
    'uses' => 'HCMovimientoController@listarIngresos',
    'as' => 'hc_movimiento.listar_ingresos',
]);
Route::get('/hc/movimiento/buscar-salida', [
    'uses' => 'HCMovimientoController@buscarSalida',
    'as' => 'hc_movimiento.buscar_salida',
]);
