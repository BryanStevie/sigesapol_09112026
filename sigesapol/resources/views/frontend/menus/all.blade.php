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
    
    $(document).ready(function () {
        $('#example').DataTable();
    });


	function addCategoria(id){
		
	$('#openOverlayOpc').modal('show');
	$('#openOverlayOpc .modal-body').css('height', '250px');
	
	$.ajax({
		url: "/create_farmacia_servicios/"+id,
		type: "GET",
		success: function (result) {  
			$("#diveditpregOpc").html(result);
		}
	});
	
    }
    
    function viewCategoria(id){
		
	$('#openOverlayOpc').modal('show');
	$('#openOverlayOpc .modal-body').css('height', '250px');
	
	$.ajax({
		url: "/view_farmacia_servicios/"+id,
		type: "GET",
		success: function (result) {  
			$("#diveditpregOpc").html(result);
		}
	});
	
    }


</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <?php if($permiso->lectura == 1):?>
                <li class="active"><a href="/all_menu"><i class="fa fa-table fa-fw"></i> Menu</a></li>
                <?php endif;?>
                <?php if($permiso->crear == 1):?>
                <li><a href="/create_menu"><i class="fa fa-plus"></i> Nuevo </a></li>
                <?php endif;?>
            </ul>
        </div>
		
        <div class="col-md-10 well col-md-offset-1" style="padding-top:0px">
			
			<div class="row margin" style=" font-size: 13px">
                <div class="form-group">                     
                    <form id="frmtipo_prod" method="post" action="{{ route('all_menu') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-5 margin-buscar">
                            <select class="form-control" required="" name="id_tipo">
                                <option value="">--Seleccionar por Tipos de Menu--</option>
                                @foreach($tipos as $t)
                                <option value="{{$t->id}}">{{$t->nombremenu}}</option>
                                @endforeach
                            </select>                        
                        </div>
                        <div class="col-md-1 margin-buscar">
                            <input id="" name="" type="submit" value="Buscar" class="btn btn-success">                        
                        </div>
                    </form>
                </div>
            </div>
			
			<div class="clearfix" style="margin-bottom:15px"></div>
			
            <div class="row">
                <div class="panel panel-primary filterable">
                    <div class="panel-heading">
                        <h3 class="panel-title">Pabell&oacute;n</h3>
						
                        <div class="pull-right">
                            <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Buscar</button>
                        </div>
						                    </div>
                    <table class="table"  id="example">
                        <thead>
                            <tr class="filters">
                                <th>N</th>
                                <th><input type="text" class="form-control" placeholder="Menu Principal" disabled></th>
                                <th><input type="text" class="form-control" placeholder="Menu" disabled></th>
								<th><input type="text" class="form-control" placeholder="Orden" disabled></th>
								<?php if($permiso->modificar == 1):?>
                                <th>Editar</th>
								<?php endif;?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $n = 0;
                            ?>
                            @foreach($menus as $far)
                            <tr>
                                <td><?php $n = $n + 1;
                            echo $n; ?></td>
                                <!--<td></td>-->
								<td>{{$far->menuprincipal}}</td>
                                <td>{{$far->nombremenu}}</td>
								<td>{{$far->orden}}</td>
								<?php if($permiso->modificar == 1):?>
                                <td>
                                    <a class="btn btn-success" href="/edit_menu/{{ $far->id }}"><i class="fa fa-edit"></i></a>                                    
                                </td>
								<?php endif;?>
								<!--<td>
                                    <a style="color:red;padding: 0px;margin: 0px;float: left" href="javascript:void(0)" onclick="addCategoria({{ $far->id }})"><i class="fa fa-medkit fa-2x"></i></a>
                                    
                                    <a class="btn btn-primary" style="float: left;margin:3px 0px 0px 5px" href="javascript:void(0)" onclick="viewCategoria({{ $far->id }})"><i class="fa fa-list-ol fa-1x"></i></a>
                                </td>-->
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                    
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection


<div id="openOverlayOpc" class="modal fade" role="dialog">
  <div class="modal-dialog" >

    <!-- Modal content-->
    <div id="id_content_OverlayoneOpc" class="modal-content" style="padding: 0px;margin: 0px">
      <!--
      <div class="modal-header" style="padding: 0px;margin: 0px">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Categorias</h4>
      </div>
      -->  
      <div class="modal-body" style="padding: 0px;margin: 0px">

      		<div id="diveditpregOpc"></div>

      </div>
      <div class="modal-footer">
      	
      </div>
    </div>

  </div>
</div>
