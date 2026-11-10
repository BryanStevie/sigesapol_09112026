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
</style>
<nav class="navbar navbar-default" style='min-height:90px!important'>
    <div class="container-fluid">
        <!--
        <div class="row">
                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                <img src="<?php //echo URL::to('/') ?>/images/logo_pnp.png" width="65">
                </div>
                <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                ssacc
                </div>
                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                <img src="<?php //echo URL::to('/') ?>/images/logo_sanidad.png" width="65">
                </div>
        </div>
        -->
        <?php //exit();?>

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
                @else

                <?php
                $menu = App\Models\Menu::getMenuByUsuario(Auth::user()->id);
                //print_r($menu);
                $dropdown_toggle = "";
                ?>
                <ul class="nav navbar-nav navbar-left">
                    <!--<li class="dropdown">
                        <a href="/" class="dropdown-toggle" role="button" aria-expanded="false">
                            Inicio
                        </a>
                    </li>-->
                    <?php
                    foreach ($menu as $key => $rowMenu) {

                        if ($rowMenu->parentid == '0' and $rowMenu->lectura == '1') {
                            $url = "#";
                            if ($rowMenu->url == '#') {
                                $url = "#";
                            } else {
                                $url = $rowMenu->url;
                            }

                            if ($rowMenu->hijos == '0') {
                                $dropdown_toggle = "";
                            } else {
                                $dropdown_toggle = "dropdown-toggle";
                            }

                            echo '<li class="dropdown"><a href="' . $url . '" class="' . $dropdown_toggle . '" data-toggle="dropdown" role="button" aria-expanded="false">' . $rowMenu->nombremenu . ' <span class="caret"></span></a>';

                            $idmenu = $rowMenu->idmenu;

                            if ($rowMenu->hijos == '0') {
                                $dropdown_toggle = "";
                            } else {
                                echo '<ul class="dropdown-menu" role="menu"><li>';
                            }


                            foreach ($menu as $key => $rowMenu) {
                                if ($rowMenu->parentid == $idmenu and $rowMenu->lectura == '1') {
                                    echo '<a href="' . $rowMenu->url . '">' . $rowMenu->nombremenu . '</a>';
                                }
                            }

                            if ($rowMenu->hijos == '0') {
                                $dropdown_toggle = "";
                            } else {
                                echo '</li>';
                            }

                            echo '</ul>';
                        }
                    }
//echo '</ul>';
                    ?>


                    <!--<li class="dropdown">
                        <a href="http://www.prototipo03.com" target="_blank" class="dropdown-toggle" role="button" aria-expanded="false">
                            <span class="glyphicon glyphicon-question-sign"></span>
                            Ayuda
                        </a>
                    </li>-->
                    <li class="dropdown">
                        <!--<a href="/stocksbyFamarcia/" target="_blank" class="dropdown-toggle" role="button" aria-expanded="false">
                            <span class="glyphicon glyphicon-ok-sign"></span>
                            Stock Local
                        </a>-->
                        <a href="#" class="dropdown-toggle" role="button" aria-expanded="false" onclick="show_stock_local('<?php echo Auth::user()->id;?>')">
                            <span class="glyphicon glyphicon-ok-sign"></span>
                            Stock Local
                        </a>
                        <!--<button type="button" class="btn btn-default btn-md" id="myBtnStock" style="margin-top:6px">
                            <span class="glyphicon glyphicon-ok-sign"></span> Stock Local
                        </button>-->
                    </li>	
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-user"></i>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li>{!! link_to('dashboard', trans('navs.dashboard')) !!}</li>
                            <li>{!! link_to('auth/password/change', trans('navs.change_password')) !!}</li>
                            <li>{!! link_to('auth/logout', trans('navs.logout')) !!}</li>
                        </ul>
                    </li>

                </ul>


                @endif

        </div>
        <div class="img-header" style="position:relative;padding:0px;margin:0px;float:right;">
            <img src="<?php echo URL::to('/') ?>/images/logo_sanidad.png" width="60" style="position:absolute;top:5px;right:0px;">
        </div>
        <?php if (!Auth::guest()): ?>
            <div class="pull-right" style="padding:0px;margin:0px;text-align:right;color:#5e5e5e;margin-right:95px;max-width:350px">
                <i class="fa" style="font-size:14px;line-height:20px;margin-top:15px;">
                    <?php
                    $model_farma = new \App\Models\Farmacia;
                    $farma = $model_farma->GetFarmaByIdfarma(Auth::user()->id_farmacia);
                    if ($farma)
                        echo $farma->nombre;

                    $id_user = Auth::user()->id;
                    $farmacia = $model_farma->GetFarmaByUser($id_user);
                    ?>
                </i>
            </div>

            <?php if ($farmacia->flagfarmacias == 1): ?>
                <button type="button" class="btn btn-default btn-md" id="myBtn" style="margin-top:6px">
                    <span class="glyphicon glyphicon-th-list"></span>
                    Farmacias
                </button>
            <?php endif; ?>

        <?php endif; ?>
        <div class="clearfix"></div>
        <div class="pull-right" style="padding:0px;margin:0px;text-align:right;color:#5e5e5e;margin-right:95px;max-width:350px; font-size: 11px;">
            <i class="fa fa-calendar" aria-hidden="true"></i> 
            <?php
//            date_default_timezone_set('America/Lima');
            $script_tz = date_default_timezone_get();
            $fecha_hoy = date('d-m-Y');
            $arrayMeses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciemnbre');
            $arrayDias = array('Domingo', 'Lunes', 'Martes',
                'Miercoles', 'Jueves', 'Viernes', 'Sabado');
//            echo $script_tz;
            echo $arrayDias[date('w')] . " " . date('d') . " de " . $arrayMeses[date('m') - 1] . " de " . date('Y');
            ?>
        </div>
    </div>
</nav>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<style>
.modal-header, h4, .close {
  background-color: #337ab7;
  color:white !important;
  text-align: center;
  font-size: 30px;
}
.modal-footer {
  background-color: #f9f9f9;
}
</style>
<script>
$(document).ready(function(){
    $("#myBtn").click(function(){
        $("#myModal").modal();
    });
    
    $('#id_establecimiento_edit').select2();
    $('#id_farmacia_edit').select2();	
});

function getFarmacia_edit(){
	
	var id_establecimiento = $('#id_establecimiento_edit').val();
	$.ajax({
		url: '/obtener_farmacia_por_id_establecimiento/' + id_establecimiento,
		success: function(result){                    
			var newOption = "";
			newOption = "<option value='0'>--Seleccionar-</option>";
			$(result).each(function (ii, oo) {
				newOption += "<option value='"+oo.id+"'>"+oo.nombre+"</option>";
			});                 
			$('#id_farmacia_edit').html(newOption);
			$("#id_farmacia_edit").select2({max_selected_options: 4});
		
		}
	});

}

function editFarmacia(){
	
	var id_establecimiento = $('#id_establecimiento_edit').val();
	var id_farmacia = $('#id_farmacia_edit').val();
	$.ajax({
		url: '/editar_usuario/' + id_establecimiento + "/" + id_farmacia,
		success: function(result){
			//window.location.reload();
                        window.location = '/';
		}
	});
	
}

function show_stock_local(idUser){
    $.ajax({
            url: '/stocksbyFamarciaModal/'+idUser,
            async: false,
            success: function(result){
                $("#muestra-stock-local").html(result);
            },
             complete: function () {
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

</script>

  <!-- Modal -->
  <?php 
  
  	if(!Auth::guest() && $farmacia->flagfarmacias==1):
		//echo $farmacia->flagfarmacias."xxx";
		
		if($farmacia)$id_establecimiento = $farmacia->id_establecimiento;
		$model_establecimiento= new App\Models\Establecimiento();
		$establecimientos=$model_establecimiento->ShowAll();
  		
		//$evento = "";
		if($farmacia->flagestablecimientos==0):
			//$evento = "onchange='editFarmacia()'";
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
			  	<label for="usrname"><?php echo $establecimiento->codigo." - ".$establecimiento->nombre?></label>
			  	<input type="hidden" name="id_establecimiento_edit" id="id_establecimiento_edit" value="<?php echo $id_establecimiento?>"  />
			  <?php endif;?>
			  
			  <?php if($farmacia->flagestablecimientos==1):?>
              <select class="form-control" name="id_establecimiento_edit" id="id_establecimiento_edit" onchange="getFarmacia_edit()" style="width:100%">
				<option value="0">-Seleccionar-</option>
				@foreach($establecimientos as $f)
				<option value="{{$f->id}}">{{$f->codigo}} - {{$f->nombre}}</option>
				@endforeach
			  </select>
			  <?php endif;?>
			  </div>
            </div>
			<div class="clearfix" style="margin-bottom:20px"></div>
            <div class="form-group">
              <label for="psw"><span class="glyphicon glyphicon-eye-open"></span> Farmacia</label>
			  <div class="col-lg-12">
			  
              <select class="form-control" name="id_farmacia_edit" id="id_farmacia_edit" style="width:100%" onchange="editFarmacia()" >
				<option value="0">-Seleccionar-</option>
				<?php if($farmacia->flagestablecimientos==0):?>
					<?php foreach($allfarmacias as $rowFarmacia):?>
						<option value="<?php echo $rowFarmacia->id?>"><?php echo $rowFarmacia->nombre?></option>
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

	<?php endif;?>

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

