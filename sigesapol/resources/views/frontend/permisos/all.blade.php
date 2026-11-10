@extends('frontend.layouts.master')
@section('renderjs')
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
    #global {
        height: 480px !important;
        width: auto;
        border: 1px solid #ddd;
        background: #f1f1f1;
        overflow-y: scroll !important;
    }
</style>

<script>
    $(document).ready(function () {
        $('.filterable .btn-filter').click(function () {
            var $panel = $(this).parents('.filterable'),
                    $filters = $panel.find('.filters input'),
                    $tbody = $panel.find('.table tbody');
            if ($filters.prop('disabled') == true) {
                $filters.prop('disabled', false);
                $filters.first().focus();
            } else {
                $filters.val('').prop('disabled', true);
                $tbody.find('.no-result').remove();
                $tbody.find('tr').show();
            }
        });

        $('.filterable .filters input').keyup(function (e) {
            /* Ignore tab key */
            var code = e.keyCode || e.which;
            if (code == '9')
                return;
            /* Useful DOM data and selectors */
            var $input = $(this),
                    inputContent = $input.val().toLowerCase(),
                    $panel = $input.parents('.filterable'),
                    column = $panel.find('.filters th').index($input.parents('th')),
                    $table = $panel.find('.table'),
                    $rows = $table.find('tbody tr');
            /* Dirtiest filter function ever ;) */
            var $filteredRows = $rows.filter(function () {
                var value = $(this).find('td').eq(column).text().toLowerCase();
                return value.indexOf(inputContent) === -1;
            });
            /* Clean previous no-result if exist */
            $table.find('tbody .no-result').remove();
            /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
            $rows.show();
            $filteredRows.hide();
            /* Prepend no-result row if all rows are filtered */
            if ($filteredRows.length === $rows.length) {
                $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="' + $table.find('.filters th').length + '">No se encuentra el laboratorio</td></tr>'));
            }
        });
    });
	
	function getPermisoByUsuario(){
        
		var id_usuario = $('#id_usuario').val();
        console.log(id_usuario);
		$('.table tbody').html("");
		
		$.ajax({
			url: '/obtener_permiso_por_idusuario_menuprincipal/'+id_usuario,
			success: function(result) {        
                console.log(result);
                //let contPadres = result.length;              
              /*    Swal.fire({
                    title:
                    "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Cargando Menus</span></div>",
                    html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    onBeforeOpen: () => {
                    Swal.showLoading();
                    },
                });             
              result.forEach((i) => {
                    $.ajax({
                        url: `/obtener_bloques_por_menu/${i.idmenu}/${id_usuario}`, 
                        success: function(result) {
                            contPadres--;*/
                            var newFila = "";
                            $(result).each(function (ii, oo) {
                                newFila += "<tr>";
                                console.log(oo);
                                var nivel_orden=oo.nivel_orden;
                                if(nivel_orden == 1){
                                    newFila += "<td style='background:#84C8A6;color:#31708F'><div class='col-lg-4'><b>"+oo.nombremenu+"</b><input type='hidden' name='idmenu[]' value='"+oo.id+"'></div>";
                                    var chk=oo.lectura=='1'?'checked':'';
                                    newFila += '<td width="10%" style="background:#84C8A6" align="center"><input type="checkbox" id="'+oo.id+'_lectura" name="lectura['+oo.id+']" ' + chk + ' value="1" onchange=seleccionGrupo('+oo.id+',this,"lectura")></td>';
                                    var chk=oo.crear=='1'?'checked':'';
                                    newFila += '<td width="10%" style="background:#84C8A6" align="center"><input type="checkbox" id="'+oo.id+'_crear" name="crear['+oo.id+']" ' + chk + ' value="1" onchange=seleccionGrupo('+oo.id+',this,"crear")></td>';
                                    var chk=oo.modificar=='1'?'checked':'';
                                    newFila += '<td width="10%" style="background:#84C8A6" align="center"><input type="checkbox" id="'+oo.id+'_modificar" name="modificar['+oo.id+']" ' + chk + ' value="1" onchange=seleccionGrupo('+oo.id+',this,"modificar")></td>';
                                    var chk=oo.eliminar=='1'?'checked':'';
                                    newFila += '<td width="10%" style="background:#84C8A6" align="center"><input type="checkbox" id="'+oo.id+'_eliminar" name="eliminar['+oo.id+']" ' + chk + ' value="1" onchange=seleccionGrupo('+oo.id+',this,"eliminar")></td>';
                                }
                               
                                
                                if(nivel_orden == 2){
                                    newFila += "<td style='background:#B7D6D0;color:#31708F'><div class='col-lg-10'><b>"+oo.nombremenu+"</b><input type='hidden' name='idmenu[]' value='"+oo.id+"'></div>";
                                    var chk=oo.lectura=='1'?'checked':'';
                                    newFila += '<td width="10%" align="center"><input type="checkbox" parent='+oo.parentid+' idmenu='+oo.id+' class="'+oo.parentid+'_lectura" id="'+oo.id+'_lectura"  name="lectura['+oo.id+']" ' + chk + ' value="1" onchange=seleccionPadre("'+oo.parentid+'_lectura",this,'+oo.parentid+','+oo.id+')></td>';
                                    var chk=oo.crear=='1'?'checked':'';
                                    newFila += '<td width="10%" align="center"><input type="checkbox" parent='+oo.parentid+' idmenu='+oo.id+' class="'+oo.parentid+'_crear" id="'+oo.id+'_crear" name="crear['+oo.id+']" ' + chk + ' value="1" onchange=seleccionPadre("'+oo.parentid+'_crear",this,'+oo.parentid+','+oo.id+')></td>';
                                    var chk=oo.modificar=='1'?'checked':'';
                                    newFila += '<td width="10%" align="center"><input type="checkbox" parent='+oo.parentid+' idmenu='+oo.id+' class="'+oo.parentid+'_modificar" id="'+oo.id+'_modificar" name="modificar['+oo.id+']" ' + chk + ' value="1" onchange=seleccionPadre("'+oo.parentid+'_modificar",this,'+oo.parentid+','+oo.id+')></td>';
                                    var chk=oo.eliminar=='1'?'checked':'';
                                    newFila += '<td width="10%" align="center"><input type="checkbox" parent='+oo.parentid+' idmenu='+oo.id+' class="'+oo.parentid+'_eliminar" id="'+oo.id+'_eliminar" name="eliminar['+oo.id+']" ' + chk + ' value="1" onchange=seleccionPadre("'+oo.parentid+'_eliminar",this,'+oo.parentid+','+oo.id+')></td>';
                                }

                                if(nivel_orden == 3){
                                    newFila += "<td style='background:#E2FAF0;color:#6A6E70'><div class='col-lg-10'><b>"+oo.nombremenu+"</b><input type='hidden' name='idmenu[]' value='"+oo.id+"'></div>";
                                    var chk=oo.lectura=='1'?'checked':'';
                                    newFila += '<td width="10%" align="center"><input type="checkbox" parent='+oo.parentid+' idmenu='+oo.id+' class="'+oo.parentid+'_lectura" id="'+oo.id+'_lectura"  name="lectura['+oo.id+']" ' + chk + ' value="1" onchange=seleccionPadre("'+oo.parentid+'_lectura",this,'+oo.parentid+','+oo.id+')></td>';
                                    var chk=oo.crear=='1'?'checked':'';
                                    newFila += '<td width="10%" align="center"><input type="checkbox" parent='+oo.parentid+' idmenu='+oo.id+' class="'+oo.parentid+'_crear" id="'+oo.id+'_crear" name="crear['+oo.id+']" ' + chk + ' value="1" onchange=seleccionPadre("'+oo.parentid+'_crear",this,'+oo.parentid+','+oo.id+')></td>';
                                    var chk=oo.modificar=='1'?'checked':'';
                                    newFila += '<td width="10%" align="center"><input type="checkbox" parent='+oo.parentid+' idmenu='+oo.id+' class="'+oo.parentid+'_modificar" id="'+oo.id+'_modificar" name="modificar['+oo.id+']" ' + chk + ' value="1" onchange=seleccionPadre("'+oo.parentid+'_modificar",this,'+oo.parentid+','+oo.id+')></td>';
                                    var chk=oo.eliminar=='1'?'checked':'';
                                    newFila += '<td width="10%" align="center"><input type="checkbox" parent='+oo.parentid+' idmenu='+oo.id+' class="'+oo.parentid+'_eliminar" id="'+oo.id+'_eliminar" name="eliminar['+oo.id+']" ' + chk + ' value="1" onchange=seleccionPadre("'+oo.parentid+'_eliminar",this,'+oo.parentid+','+oo.id+')></td>';
                                }

                                if(nivel_orden == 4){
                                    newFila += "<td style='color:#6A6E70'><div class='col-lg-10'><b>"+oo.nombremenu+"</b><input type='hidden' name='idmenu[]' value='"+oo.id+"'></div>";
                                    var chk=oo.lectura=='1'?'checked':'';
                                    newFila += '<td width="10%" align="center"><input type="checkbox" parent='+oo.parentid+' idmenu='+oo.id+' class="'+oo.parentid+'_lectura" id="'+oo.id+'_lectura"  name="lectura['+oo.id+']" ' + chk + ' value="1" onchange=seleccionPadre("'+oo.parentid+'_lectura",this,'+oo.parentid+','+oo.id+')></td>';
                                    var chk=oo.crear=='1'?'checked':'';
                                    newFila += '<td width="10%" align="center"><input type="checkbox" parent='+oo.parentid+' idmenu='+oo.id+' class="'+oo.parentid+'_crear" id="'+oo.id+'_crear" name="crear['+oo.id+']" ' + chk + ' value="1" onchange=seleccionPadre("'+oo.parentid+'_crear",this,'+oo.parentid+','+oo.id+')></td>';
                                    var chk=oo.modificar=='1'?'checked':'';
                                    newFila += '<td width="10%" align="center"><input type="checkbox" parent='+oo.parentid+' idmenu='+oo.id+' class="'+oo.parentid+'_modificar" id="'+oo.id+'_modificar" name="modificar['+oo.id+']" ' + chk + ' value="1" onchange=seleccionPadre("'+oo.parentid+'_modificar",this,'+oo.parentid+','+oo.id+')></td>';
                                    var chk=oo.eliminar=='1'?'checked':'';
                                    newFila += '<td width="10%" align="center"><input type="checkbox" parent='+oo.parentid+' idmenu='+oo.id+' class="'+oo.parentid+'_eliminar" id="'+oo.id+'_eliminar" name="eliminar['+oo.id+']" ' + chk + ' value="1" onchange=seleccionPadre("'+oo.parentid+'_eliminar",this,'+oo.parentid+','+oo.id+')></td>';
                                }
                                newFila += "</tr>";
                            });                 
                            $('.table tbody').append(newFila);
                            if(contPadres == 0) {
                                Swal.fire({
                                    icon: "success",
                                    html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
                                    confirmButtonText: "ENTENDIDO",
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    confirmButtonText: "ENTENDIDO",
                                });
                            }
                     /*   } 
                    });
                });*/
			}
		});
        
    }	
	
	function seleccionGrupo(idmenu,obj,tipo){
		if($(obj).is(':checked')){
			$('.'+idmenu+'_'+tipo).prop("checked",true);
			$('.'+idmenu+'_lectura').prop("checked",true);
			$('#'+idmenu+'_lectura').prop("checked",true);
		}else{
			$('.'+idmenu+'_'+tipo).prop("checked",false);
		}
		
	}
	
	function seleccionPadre(clsparentid,obj,parentid,idmenu){
		
		var m 	= 0;
		var c 	= 0;
		$('input[idmenu='+idmenu+']').each(function(data){
			if($(this).attr('class') == parentid+'_crear' || $(this).attr('class') == parentid+'_modificar' || $(this).attr('class') == parentid+'_eliminar'){
				if($(this).is(':checked')){
					m++;
				}
			}
			
			if(clsparentid != parentid+'_lectura'){
				if(m > 0)$('#'+idmenu+'_lectura').prop("checked",true);
				else $('#'+idmenu+'_lectura').prop("checked",false);
			}
		})
		
		$('input[parent='+parentid+']').each(function(data){
			if($(this).is(':checked')){
				c++;
			}
		})

		if(c > 0)$('#'+parentid+'_lectura').prop("checked",true);
		else $('#'+parentid+'_lectura').prop("checked",false);
		
		/**************************/
		var i = 0;
		$('.'+clsparentid).each(function(data){
			if($(this).is(':checked')){
				i++;
			}
		})
		
		if(i > 0)$('#'+clsparentid).prop("checked",true);
		else $('#'+clsparentid).prop("checked",false);
		
	}
	
	
	function seleccionPadre_(parentid,obj){
		
		var i = 0;
		$('.'+parentid).each(function(data){
			if($(this).is(':checked')){
				i++;
			}
		})
		
		
		var m 			= 0;
		var idparent 	= 0;
		var idmenu 		= 0;
		if($(obj).is(':checked')){
			var clase 	= ($(obj).attr("class")).split("_");
			var menu 	= ($(obj).attr("id")).split("_");
			idparent 	= clase[0];
			opcion 		= clase[1];
			idmenu		= menu[0];
			if(opcion == "crear" || opcion == "modificar" || opcion == "eliminar"){
				m = 0;
				$('input[parent='+idparent+']').each(function(data){
					if($(this).is(':checked')){
						m++;
					}
				})
				
			}
		}else{
			var menu 	= ($(obj).attr("id")).split("_");
			idmenu		= menu[0];
		}
		
		
		if(m > 0)$('#'+idmenu+'_lectura').prop("checked",true);
		
		if(i > 0)$('#'+parentid).prop("checked",true);
		else $('#'+parentid).prop("checked",false);
		
	}
	
	
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked">
                <?php if ($permiso->lectura == 1): ?>
                    <li class="active"><a href="/all_permiso"><i class="fa fa-table fa-fw"></i>Permisos</a></li>
                <?php endif; ?>
                <!--<li><a href="/create_usuario"><i class="fa fa-plus"></i> Nuevo</a></li>-->
            </ul>
        </div>
        <div class="col-md-10 well">
            <div class="row">
                <div class="panel panel-primary filterable">
                    <div class="panel-heading">
                        <h3 class="panel-title">Permisos por usuario</h3>
                        <div class="pull-right">
                            <!--<button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Buscar</button>-->
                        </div>
                    </div>
                    <br />

                    <form id="" class="form-horizontal" role="form" method="post"  action="{{ route('store_permiso') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Usuarios</label>
                            <div class="col-lg-10">
                                <select class="form-control" name="id_usuario" id="id_usuario" onchange="getPermisoByUsuario()">
                                    <option value="">Seleccionar Usuario</option>
                                    @foreach($usuarios as $usu)
                                    <option value="{{$usu->id}}">{{$usu->paterno}} {{$usu->materno}}, {{$usu->name}} <?php echo " (" . $usu->email . ")" ?></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div style="clear:both;height:10px"></div>
                        <table class="tablex">
                            <thead>
                                <tr class="filters">
                                    <th width="62%">&nbsp;&nbsp;&nbsp;&nbsp;Formulario</th>
                                    <th width="9%" align="center"><input type="text" class="form-control" placeholder="Ver" disabled></th>
                                    <th width="9%" align="center"><input type="text" class="form-control" placeholder="Crear" disabled></th>
                                    <th width="10%" align="center"><input type="text" class="form-control" placeholder="Modificar" disabled></th>
                                    <th width="10%" align="center"><input type="text" class="form-control" placeholder="Eliminar" disabled></th>
                                </tr>
                            </thead>
                        </table>

                        <div id="global">
                            <table class="table">
                                <thead>
                                    <tr class="filters" style="display:none">
                                        <th><input type="text" class="form-control" placeholder="Formulario" disabled></th>
                                        <th><input type="text" class="form-control" placeholder="Nuevo" disabled></th>
                                        <th><input type="text" class="form-control" placeholder="Modificar" disabled></th>
                                        <th><input type="text" class="form-control" placeholder="Eliminar" disabled></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>

                        </div>

                </div>

                <div style="margin-top:10px" class="form-group">
                    <div class="col-sm-12 controls">
                        <?php if ($permiso->crear == 1 || $permiso->modificar == 1): ?>
                            <input type="submit" value="Guardar" class="btn btn-success">
                        <?php endif; ?>
                        <a href="/all_permiso" class="btn btn-danger">Cancelar</a>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection