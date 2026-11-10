<?php
    $bloquear_nav = isset($bloquear_nav) && $bloquear_nav === true;
?>

<style>
    .filterable {
        margin-top: 0px;
    }

    .filterable .panel-heading .pull-right {
        margin-top: -20px;
    }

    .filterable .filters input[disabled] {
        background-color: transparent;
        border: none;
        cursor: auto;
        box-shadow: none;
        padding: 0;
        height: auto;
    }

    .filterable .filters input[disabled]::-webkit-input-placeholder {
        color: #333;
    }

    .filterable .filters input[disabled]::-moz-placeholder {
        color: #333;
    }

    .filterable .filters input[disabled]:-ms-input-placeholder {
        color: #333;
    }

    .icon-desapareciendo {
        -moz-animation: parpadeando 2s infinite linear;
        -o-animation: parpadeando 2s infinite linear;
        -webkit-animation: parpadeando 2s infinite linear;
        animation: parpadeando 2s infinite linear;
    }

    .rojo {
        color: red;
    }

    /* Estilos para el dropdown submenu */
    .dropdown-submenu {
        position: relative;
    }

    .dropdown-submenu>.dropdown-menu {
        top: 0;
        left: 100%;
        margin-top: -6px;
        margin-left: -1px;
        border-radius: 0 6px 6px 6px;
    }

    .dropdown-submenu:hover>.dropdown-menu {
        display: block;
    }

    .dropdown-submenu>a:after {
        content: none; /* Desactiva el caret por defecto */
    }

    .dropdown-submenu.has-children>a:after {
        display: block;
        content: " ";
        float: right;
        width: 0;
        height: 0;
        border-color: transparent;
        border-style: solid;
        border-width: 5px 0 5px 5px;
        border-left-color: #bb2323;
        margin-top: 5px;
        margin-right: -10px;
    }

    .dropdown-submenu:hover>a:after {
        border-left-color: #4c28a1;
    }

    .dropdown-submenu.pull-left {
        float: none;
    }

    .dropdown-submenu.pull-left>.dropdown-menu {
        left: -100%;
        margin-left: 10px;
        border-radius: 6px 0 6px 6px;
    }

    .punto_notificacion_interconsulta {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 12px;
        height: 12px;
        background-color: #1f6feb;
        border: 2px solid white;
        border-radius: 50%;
        z-index: 10;
    }

    .parpadeante {
        animation: parpadeo 1s infinite;
    }

    @keyframes parpadeo {
        0%, 100% { opacity: 1; }
        50% { opacity: 0; }
    }
</style>

@if (Auth::user()->id_farmacia != 0 or Auth::user()->id_farmacia != '')
<nav class="navbar navbar-default" style='min-height:90px!important'>
    <div class="container-fluid">
        <div class="navbar-header" style="padding:0px;margin:0px">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">{{ trans('labels.toggle_navigation') }}</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"  style="padding:0px;margin:0px"><img src="<?php echo URL::to('/') ?>/images/logo_pnp_nom.png" class="img-responsive" width="180" alt="Cinque Terre"/></a>

        </div>
        <div class="collapse navbar-collapse col-md-1" id="bs-example-navbar-collapse-1">
            @if (Auth::guest())
                <ul class="nav navbar-nav navbar-right">
                    <li>{!! link_to('auth/login', trans('navs.login')) !!}</li>
                </ul>
            @else
                        <?php
                            $menu = App\Models\Menu::getMenuByUsuario(Auth::user()->id);
                            $dropdown_toggle = "";
                        ?>

                <ul class="nav navbar-nav navbar-left">
                    @if(!$bloquear_nav)
                        <?php foreach ($menu as $rowMenu) : ?>
                            <?php if ($rowMenu->parentid == '0' && $rowMenu->lectura == '1') : ?>
                                <?php
                                    $url = $rowMenu->url == '#' ? '#' : $rowMenu->url;                       
                                    $dropdown_toggle = $rowMenu->hijos == '0' ? '' : 'dropdown-toggle';
                                ?>
                            
                                <li class="dropdown">
                                    <a href="<?php echo $url; ?>" class="<?php echo $dropdown_toggle; ?>" data-toggle="dropdown" role="button" aria-expanded="true">
                                        <?php echo $rowMenu->nombremenu; ?>
                                        <?php if ($rowMenu->hijos != '0') : ?>
                                            <span class="caret"></span>
                                        <?php endif; ?>
                                    </a>
                                    <?php if ($rowMenu->hijos != '0') : ?>
                                        <ul class="dropdown-menu" role="menu">
                                            <?php foreach ($menu as $subMenu) : ?>
                                                <?php if ($subMenu->parentid == $rowMenu->idmenu && $subMenu->lectura == '1') : ?>
                                                    <?php
                                                        $subUrl = $subMenu->url == '#' ? '#' : $subMenu->url;
                                                        $has_children_class = $subMenu->hijos != '0' ? ' has-children' : '';
                                                    ?>
                                                    <li class="dropdown-submenu<?php echo $has_children_class; ?>">
                                                        <a href="<?php echo $subUrl; ?>"><?php echo $subMenu->nombremenu; ?></a>
                                                        <?php if ($subMenu->hijos != '0') : ?>
                                                            <ul class="dropdown-menu">
                                                                <?php foreach ($menu as $subSubMenu) : ?>
                                                                    <?php if ($subSubMenu->parentid == $subMenu->idmenu && $subSubMenu->lectura == '1') : ?>
                                                                        <?php
                                                                            $subSubUrl = $subSubMenu->url == '#' ? '#' : $subSubMenu->url;
                                                                        ?>
                                                                        <li><a href="<?php echo $subSubUrl; ?>"><?php echo $subSubMenu->nombremenu; ?></a></li>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" role="button" aria-expanded="false" onclick="show_stock_local('<?php echo Auth::user()->id; ?>')">
                                <span class="glyphicon glyphicon-ok-sign"></span>
                                Stock Local
                            </a>
                        </li>
                        <li class="dropdown">                        
                            @if(Auth::user()->id_farmacia!=113)
                                <a href="#" class="dropdown-toggle" role="button" aria-expanded="false" onclick="show_stock_almacen()">
                                    <span class="glyphicon glyphicon-ok-sign"></span>
                                    <span style="color: red">Stock almacen</span>
                                </a>
                            @endif                        
                        </li> 
                    @endif

                    
                    {{-- visible siempre --}}
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-user"></i>
                            <?php echo Auth::user()->name; ?> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            @if(!$bloquear_nav)
                                <li>{!! link_to('dashboard', trans('navs.dashboard')) !!}</li>
                                <li>{!! link_to('auth/password/change', trans('navs.change_password')) !!}</li>
                            @endif
                            <li>{!! link_to('auth/logout', trans('navs.logout')) !!}</li>
                        </ul>
                    </li>
                    {{-- --- --}}

                </ul>
            @endif
        </div>
        
        <?php if (!Auth::guest()) : ?>
          
            <div class="pull-right" style="padding:0px;margin:0px;text-align:right;color:#5e5e5e;margin-right:95px;max-width:350px">
                <i class="fa" style="font-size:14px;line-height:20px;margin-top:15px;">
                    <?php
                    $model_farma = new \App\Models\Farmacia();
                    $farma = $model_farma->GetFarmaByIdfarma(Auth::user()->id_farmacia);
                    if ($farma) {
                        echo $farma->nombre;
                    }
                    $id_user = Auth::user()->id;
                    $farmacia = $model_farma->GetFarmaByUser($id_user);
                    ?>
                </i>
            </div>
            @if(!$bloquear_nav)
                <?php if ($farmacia->flagfarmacias == 1) : ?>
                    <button type="button" class="btn btn-default btn-md" id="myBtn" style="margin-top:6px">
                        <span class="glyphicon glyphicon-th-list"></span>
                        Farmacias
                    </button>
                <?php endif; ?>
                <?php if (!Auth::guest()) : ?>
                    <?php
                        $model_establecimiento = new \App\Models\Establecimiento();
                        $establecimiento = $model_establecimiento->Get(Auth::user()->id_establecimiento);
                    ?>
                    <?php if ($establecimiento->nivel == 3) : ?>
                        <button type="button" class="btn btn-default btn-md" id="btnCenso" style="margin-top:6px; position: relative;">
                            <i class="fa fa-bed"></i>
                            Censo de camas
                            <span id="censo_camas" class="badge badge-danger"
                                style="position: absolute; top: -8px; right: -8px; font-size: 12px; display: none;">
                            </span>
                        </button>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (Auth::user()->tipo_registro == 1):?>
                    <button type="button" class="btn btn-default btn-md" id="button_alerta_sinfirmar" style="margin-top:6px; position: relative;">
                        <i class="fa fa-pencil-square-o"></i> Firmas Pendientes
                        <span id="label_cantidad_docs_sin_firmar" class="badge badge-danger parpadeante"
                            style="position: absolute; top: -4px; right: -8px; font-size: 12px; height: 22px; min-width: 22px; line-height: 22px; display: none; background-color: #dc3545; color: #ffffff; text-align: center; padding: 0 6px; border-radius: 12px;">
                        </span>
                    </button>
                <?php endif;?>
            @endif
        <?php endif; ?>
        <div class="clearfix"></div>
        
    
        <div class="pull-right" style="padding:0px;margin:0px;text-align:right;color:#5e5e5e;margin-right:95px;max-width:350px; font-size: 11px;">
            <i class="fa fa-calendar" aria-hidden="true"></i>
            <?php
            $script_tz = date_default_timezone_get();
            $fecha_hoy = date('d-m-Y');
            $arrayMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            $arrayDias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            echo $arrayDias[date('w')] . ' ' . date('d') . ' de ' . $arrayMeses[date('m') - 1] . ' de ' . date('Y');
            ?>
        </div>
        <div class="clearfix"></div>
        <div class="pull-right" style="padding:0px;margin:0px;text-align:right;color:#5e5e5e;margin-right:95px;max-width:350px; font-size: 11px;">
          <i class="fa fa-users" aria-hidden="true"></i>
          usuarios conectados: <span class="label label-success" id="countUsersActives"></span>
          <button type="button" class="btn btn-link btn-xs" id="btnGetUsersActives"><i class="fa fa-refresh" aria-hidden="true" id="iconRefresh"></i></button>
        </div>
    </div>
</nav>
@else
<div class="container-fluid">
    <div class="col-md-12">
        <div><br /><br />
            <p align="center"><span style="color: red;">
                    <?php
                    echo 'Ups!!!... Hubo un problema con su usuario. Comunicarse con el administrador del Sistema, para poder ingresar correctamente';
                    ?></span>
            </p><br /><br />
        </div>
    </div>
</div>
@endif



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<style>
    .modal-header,
    h4,
    .close {
        background-color: #3c763d;
        color: white !important;
        text-align: center;
        font-size: 30px;
    }

    .modal-footer {
        background-color: #f9f9f9;
    }

    .boton-interconsulta-parpadeo {
        animation: interconsulta_parpadeo 2s infinite;
        background-color: #0d6efd !important;
        color: white !important;
        border-color: #0a58ca !important;
    }

    @keyframes interconsulta_parpadeo {
        0%   { opacity: 1; }
        50%  { opacity: 0.4; }
        100% { opacity: 1; }
    }
</style>
<script>
    var intervalId;
    let interconsulta_tipo_atencion_default = 0;

    @if (Auth::user()->tipo_registro == 1)
        intervalId = setInterval(getCantidadInterconsultasPendientes, 30000); // 900000 = 15 minutos
    @endif

    $(document).ready(function() {
        $("#myBtn").click(function() {
            $("#myModal").modal();
        });
        
        $("#button_notificacion_interconsulta").click(function() {
            window.location = "{{ url('/all_interconsultas') }}/" + interconsulta_tipo_atencion_default;
        });

        <?php if (Auth::user()->flagestablecimientos == 1) { ?> // Correcion para que no salga error en consola, pero no afecta en nada del funcionamiento
        $('#id_establecimiento_edit').select2();
        <?php } ?>
        $('#id_farmacia_edit').select2();

        @if (Auth::user()->tipo_registro == 1)
            getCantidadInterconsultasPendientes();
        @endif
        
    });

    $("#btnCenso").click(function() {
        window.open("{{ url('/censo_camas') }}", "_blank");
    });

    function getFarmacia_edit() {

        var id_establecimiento = $('#id_establecimiento_edit').val();
        $.ajax({
            url: '/obtener_farmacia_por_id_establecimiento/' + id_establecimiento,
            success: function(result) {
                var newOption = "";
                newOption = "<option value='0'>--Seleccionar-</option>";
                $(result).each(function(ii, oo) {
                    newOption += "<option value='" + oo.id + "'>" + oo.nombre + "</option>";
                });
                $('#id_farmacia_edit').html(newOption);
                $("#id_farmacia_edit").select2({
                    max_selected_options: 4
                });

            }
        });

    }

    function editFarmacia() {

        var id_establecimiento = $('#id_establecimiento_edit').val();
        var id_farmacia = $('#id_farmacia_edit').val();
        $.ajax({
            url: '/editar_usuario/' + id_establecimiento + "/" + id_farmacia,
            success: function(result) {
                //window.location.reload();
                window.location = '/';
            }
        });

    }

    function show_stock_local(idUser) {
        $.ajax({
            url: '/stocksbyFamarciaModal/' + idUser,
            async: false,
            success: function(result) {
                $("#muestra-stock-local").html(result);
            },
            complete: function() {
                $('#table-stock-local').dataTable({
                    "language": {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar _MENU_ registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": "Ningún dato disponible en esta tabla",
                        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sSearch": "Buscar:",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
                $("#myModalStock").modal();
            }
        });
    }

    function show_stock_almacen() {
        $.ajax({
            url: '/stocksbyAlmacenModal/',
            async: false,
            success: function(result) {
                $("#muestra-stock-almacen").html(result);
            },
            complete: function() {
                $('#table-stock-almacen').dataTable({
                    "language": {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar _MENU_ registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": "Ningún dato disponible en esta tabla",
                        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sSearch": "Buscar:",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
                $("#myModalStockAlmacen").modal();
            }
        });
    }

    function getInterconsultasPendientes() {
        $.ajax({
            url: '/obtener_interconsultas_pendientes',
            success: function(result) {
                if (result !== "") {
                    $("#tbl-interconsulta-pendiente").html(result);
                    $("#myModalAvisoInterconsulta").modal();
                }
            }
        });
    }

    function getCantidadInterconsultasPendientes() {
        $.ajax({
            url: '/obtener_cantidad_interconsultas_pendientes_by_user',
            success: function(result) {
                if (Array.isArray(result) && result.length > 0) {
                    interconsulta_tipo_atencion_default = result[0].id_tipo_atencion;
                    
                    let interconsulta_cantidad = 0;
                    result.forEach(element => {
                        interconsulta_cantidad += parseFloat(element.cantidad);
                    });

                    if (interconsulta_cantidad > 0) {
                        if (interconsulta_cantidad == 1) {
                            $("#label_cantidad_interconsulta_pendiente").html("<b> " + interconsulta_cantidad + "</b> Interconsulta pendiente.");
                        } else {
                            $("#label_cantidad_interconsulta_pendiente").html("<b> " + interconsulta_cantidad + "</b> Interconsultas pendientes.");
                        }
                        $("#button_notificacion_interconsulta i").removeClass("fa-bell-slash-o").addClass("fa-bell-o");
                        $("#punto_notificacion_interconsulta").show();

                        // Activar efecto de parpadeo
                        $("#button_notificacion_interconsulta").addClass("boton-interconsulta-parpadeo");

                    }
                } else {
                    $("#label_cantidad_interconsulta_pendiente").html("");
                    $("#button_notificacion_interconsulta i").removeClass("fa-bell-slash-o").addClass("fa-bell-o");
                    $("#punto_notificacion_interconsulta").hide();

                    // Desactivar efecto de parpadeo
                    $("#button_notificacion_interconsulta").removeClass("boton-interconsulta-parpadeo");
                }
            },
            error: function(xhr, status, error) {
                $("#label_cantidad_interconsulta_pendiente").html("");
                $("#button_notificacion_interconsulta i").removeClass("fa-bell-o").addClass("fa-bell-slash-o");
                $("#punto_notificacion_interconsulta").hide();
                
                // Desactivar efecto de parpadeo
                $("#button_notificacion_interconsulta").removeClass("boton-interconsulta-parpadeo");
            }
        });
    }

    function getCountUsersActives() {
      $.ajax({
        url: '/api/users/actives',
        method: 'GET',
        dataType: 'json',
      })
      .done(function(response) {
        if (response) {
          $("#countUsersActives").html(response.count_users_actives);
        }
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        console.error('Error en la solicitud:', textStatus, errorThrown);
      })
      .always(function() {
        $("#iconRefresh").removeClass("fa-spin");
      });
    }

    $('#btnGetUsersActives').click(function() {
      const $icon = $(this).find('i');
      $icon.addClass('fa-spin');
      getCountUsersActives();
    });

    //============================================ DOCS SIN FIRMAR ==============================================================
    var docs_sinfirmarvalId;
    //let docs_sinfirmar_tipo_atencion_default = 0;

    @if (Auth::user()->tipo_registro == 1)
        docs_sinfirmarvalId = setInterval(getCantidadFirmasPendientes, 30000); // ejecuta cada 30 segundos
    @endif

    $(document).ready(function() {
        $("#myBtn").click(function() {
            $("#myModal").modal();
        });

        $("#button_alerta_sinfirmar").click(function() {
            //window.open("{{ url('/all_docs_sin_firmar') }}/" + docs_sinfirmar_tipo_atencion_default, "_blank");
            //window.location = "{{ url('/all_docs_sin_firmar') }}/" + docs_sinfirmar_tipo_atencion_default;
            window.location = "{{ url('/all_docs_sin_firmar') }}";
        });

        <?php if (Auth::user()->flagestablecimientos == 1) { ?> 
        $('#id_establecimiento_edit').select2();
        <?php } ?>
        $('#id_farmacia_edit').select2();

        @if (Auth::user()->tipo_registro == 1)
            getCantidadFirmasPendientes();
        @endif
    });

    function getCantidadFirmasPendientes() {
        $.ajax({
            url: '/obtener_cantidad_docs_sinfirmar_by_user', 
            success: function(result) {
                if (Array.isArray(result) && result.length > 0) {
                    docs_sinfirmar_tipo_atencion_default = result[0].id_tipo_atencion;
                    
                    let docs_cantidad = 0;
                    result.forEach(element => {
                        docs_cantidad += parseFloat(element.cantidad);
                    });

                    if (docs_cantidad > 0) {
                        // mostrar numerito en badge del botón
                        $("#label_cantidad_docs_sin_firmar").text(docs_cantidad).show();

                        // mostrar puntito de notificación (opcional)
                        //$("#punto_notificacion_interconsulta").show();

                        
                        $("#button_alerta_sinfirmar").addClass("boton-firma-parpadeo"); // activar efecto de parpadeo
                    }
                } else {
                    $("#label_cantidad_docs_sin_firmar").hide();
                    //$("#punto_notificacion_interconsulta").hide();

                    
                    $("#button_alerta_sinfirmar").removeClass("boton-firma-parpadeo"); // desactivar efecto de parpadeo
                }
            },
            error: function(xhr, status, error) {
                $("#label_cantidad_docs_sin_firmar").hide();
                //$("#punto_notificacion_interconsulta").hide();

                
                $("#button_alerta_sinfirmar").removeClass("boton-firma-parpadeo"); // desactivar efecto de parpadeo
            }
        });
    } 
    //=========================================================================================================================
</script>

<!-- Modal -->
<?php 
    if(Auth::user()->id_farmacia!=0 or Auth::user()->id_farmacia!="")
      	if(!Auth::guest() && $farmacia->flagfarmacias==1):
    		if($farmacia)$id_establecimiento = $farmacia->id_establecimiento;
    		$model_establecimiento= new App\Models\Establecimiento();
    		$establecimientos=$model_establecimiento->ShowAll();
      		
    		if($farmacia->flagestablecimientos==0):
    			$allfarmacias 		= $model_farma->OnlyFarmacias($id_establecimiento);
    			$establecimiento	= $model_establecimiento->GetById($id_establecimiento);
    			$establecimiento    = $establecimiento[0];
    	    endif;
        
  ?>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="padding:35px 50px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><span class="glyphicon glyphicon-lock"></span> Ir a Farmacia</h4>
            </div>
            <div class="modal-body" style="padding:40px 50px;">
                <form role="form">
                    <div class="form-group">
                        <label for="usrname"><span class="glyphicon glyphicon-home"></span> Hospital</label>
                        <div class="col-lg-12">
                            <?php if($farmacia->flagestablecimientos==0):?>
                            <label for="usrname"><?php echo $establecimiento->codigo . ' - ' . $establecimiento->nombre; ?></label>
                            <input type="hidden" name="id_establecimiento_edit" id="id_establecimiento_edit"
                                value="<?php echo $id_establecimiento; ?>" />
                            <?php endif;?>
                            <?php if($farmacia->flagestablecimientos==1):?>
                            <select class="form-control" name="id_establecimiento_edit" id="id_establecimiento_edit"
                                onchange="getFarmacia_edit()" style="width:100%">
                                <option value="0">-Seleccionar-</option>
                                @foreach ($establecimientos as $f)
                                    <option value="{{ $f->id }}">{{ $f->codigo }} - {{ $f->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="clearfix" style="margin-bottom:20px"></div>
                    <div class="form-group">
                        <label for="psw"><span class="glyphicon glyphicon-eye-open"></span> Farmacia</label>
                        <div class="col-lg-12">
                            <select class="form-control" name="id_farmacia_edit" id="id_farmacia_edit"
                                style="width:100%" onchange="editFarmacia()">
                                <option value="0">-Seleccionar-</option>
                                <?php if($farmacia->flagestablecimientos==0):?>
                                <?php foreach($allfarmacias as $rowFarmacia):?>
                                <option value="<?php echo $rowFarmacia->id; ?>"><?php echo $rowFarmacia->nombre; ?></option>
                                <?php endforeach;?>
                                <?php endif;?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<?php endif;
    
    ?>

<div class="modal fade" id="myModalStock" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">PROD. FARMACÉUTICOS Y DISPOSITIVOS MÉDICOS</h5>
            </div>
            <div class="modal-body">
                <div id="muestra-stock-local"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="myModalStockAlmacen" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">PROD. FARMACÉUTICOS Y DISPOSITIVOS MÉDICOS - ALMACEN ESPECIALIZADO DE PRODUCTOS
                    FARMACEUTICOS Y DISPOSITIVOS MEDICOS DE DIRSAPOL</h5>
            </div>
            <div class="modal-body">
                <div id="muestra-stock-almacen"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Modal Aviso Interconsulta - myModalAvisoInterconsulta -->
<div class="modal fade" id="myModalAvisoInterconsulta" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Solicitudes de Interconsulta</h4>
            </div>
            <div class="modal-body">
                <div id="tbl-interconsulta-pendiente"></div>
            </div>
            <div class="modal-footer">
                <a href="/all_interconsultas" class="btn btn-primary">Ir a Interconsultas</a>
                <!-- <button type="button" class="btn btn-primary" data-dismiss="modal"></button> -->
            </div>
        </div>
    </div>
</div>
