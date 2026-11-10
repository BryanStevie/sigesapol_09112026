@extends('frontend.layouts.master2')
@section('renderjs')
<style>
    .table-sortable tbody tr {
        cursor: move;
    }
	/*
    #global {        
        width: 95%;        
        margin: 15px 15px 15px 15px;     
        height: 380px !important;        
        border: 1px solid #ddd;
        overflow-y: scroll !important;
    }
	*/
	#global {
        min-height: 650px !important;
        /*width: auto;*/
		width: 100%;
        border: 1px solid #ddd;
		margin:15px
       /* background: #f1f1f1;*/
        /*overflow-y: scroll !important;*/
    }
	
    .margin{

        margin-bottom: 20px;
    }
    .margin-buscar{
        margin-bottom: 5px;
        margin-top: 5px;
    }

    .row{
        margin-top:10px;
        padding: 0 10px;
    }
    .clickable{
        cursor: pointer;   
    }

    .panel-heading div {
        margin-top: -18px;
        font-size: 15px;        
    }
    .panel-heading div span{
        margin-left:5px;
    }
    .panel-body{
        display: block;
    }
	
	.dataTables_filter {
	   display: none;
	}

</style>

<script>

	function ver_det_receta(idUser, nro_hospitalizacion, id_receta){
    $('#nrorecmodal').text(nro_hospitalizacion);
    document.getElementById("id_hospitalizacion").value = id_receta;
    var estado = $('#estado').val();
    $.ajax({
    url: '/ver_hospitalizado/2/' + idUser + '/' + nro_hospitalizacion + '/' + id_receta + '/' + estado,
            async: false,
            success: function(result){
            $("#squarespaceModal").modal();
            }
    });
    }


    $consul = $("#frmRequerimiento").find("#id_requerimiento");
    $consul.select2();
    //--------------------------------------------------------------------------
    $(document).ready(function () {
		
		
	
	
        var activeSystemClass = $('.list-group-item.active');
		
    });
	
	
	$(document).ready(function () {
        $('#example').DataTable({
  			/*"searching": false*/
			"language": {
			  "emptyTable": "No se encontraron resultados"
			}
	
		});
		
		
		$("#system-search").keyup(function() {
			var dataTable = $('#example').dataTable();
		   dataTable.fnFilter(this.value);
		}); 

    });
	
	
	
	
</script>
@endsection
@section('content')
<div class="container">

    
    <div class="row col-md-12">
        <div class="panel panel-primary filterable" >
            <div class="panel-heading">
                <h3 class="panel-title">Pacientes en Emergencia</h3>
            </div>  
            <div class="panel-body">
                <legend style="text-align: center; text-transform: uppercase;">{{$farmacia->nombre}}</legend>
				<!--recetas_atendidas_by_fecha_and_farmacia-->
				
                <div class="col-md-12">
                    <input class="form-control" id="system-search" name="q" placeholder="Buscar paciente en Emergencia">                        
                </div>
				
				<?php //print_r($recetas);?>
                <div class="col-md-12" id="global">
                    <!--<table  class="table table-list-search">-->
					<table id="example" class="table table-list-search" style="font-size: 11px !important;" >
                        <thead>
                            <tr>
                                <th>N</th>                                
								<th>Fecha Ingreso</th>
								<th>Servicio Triaje</th>
                                <th>DNI</th>
                                <th>Beneficiario</th>
                                <th>Tipo</th>
                                <th>Medico Triaje</th>
                                <th>Dar Alta</th>
                                <!--<th>Modificar</th>
                                <th>Imprimir</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n = 0; ?>
                            @foreach($hospitalizado as $r)
                            <tr>
                                <td><?php
                                    $n++;
                                    echo $n;
                                    ?></td>
                                <td>
                                    <?php
                                    $originalDate2 = $r->fecha_ingreso;
                                    $fecha = date("d-m-Y", strtotime($originalDate2));
                                    echo $fecha;
                                    ?> 
                                </td>
                                <td><?php echo $r->descripciontriaje?></td>
                                <td>{{$r->nro_doc_ident}}</td>
                                <td>{{$r->paterno}} {{$r->materno}}, {{$r->nombre}} </td>
                                <td>{{$r->tipo_beneficiario}}</td>
                                <td>{{$r->paterno_medico}} {{$r->materno_medico}},{{$r->nombre_medico}}</td>
                                <td>
                                    <?php 
                                        if($r->fecha_egreso == null){
                                    ?>
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Registrar Alta</b>">
                                        <a href="/dar_alta_emergencia/{{ $id_farmacia }}/{{$r->id}}" class="btn btn-success btn-xs"><i class="fa fa-file"></i></a>
                                    </div>
                                    <?php 
                                        }else{
                                    ?>
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Modificar Alta</b>">
                                        <a href="/modificar_alta_emergencia/{{ $id_farmacia }}/{{$r->id}}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                                    </div>

                                    <?php 
                                        }
                                    ?>
                                </td>

                            </tr>
                            @endforeach                      
                        </tbody>
                    </table>   
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                <h5 class="modal-title">INVALIDAR ALTA PACIENTE NRO. <span id="nrorecmodal"></span></h5>
            </div>
            <div class="modal-body">
                <!-- content goes here -->
                <form class="" method="post" action="{{ route('cambiar_estado_hospitalizado')}}" id="modal_estado" name="modal_estado">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Escribir el motivo para invalidar el alta del paciente</label>
                        <input type="text" class="form-control" id="motivo" name="motivo" placeholder="" required="">
                        <input type="hidden" class="form-control" id="id_hospitalizacion" name="id_hospitalizacion">
                    </div>                    
                    <div class="form-group">
                        <input id="btnInvalidar" class="btn btn-danger" type="submit" value="Invalidar">                        
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

@include('frontend.includes.footer')
@endsection
