@extends('frontend.layouts.master')
@section('renderjs')
<style>

</style>

<script>
  function jsDecimals(e) {

        var evt = (e) ? e : window.event;
        var key = (evt.keyCode) ? evt.keyCode : evt.which;
        if (key != null) {
            key = parseInt(key, 10);
            if ((key < 48 || key > 57) && (key < 96 || key > 105)) {
                //Aca tenemos que reemplazar "Decimals" por "NoDecimals" si queremos que no se permitan decimales
                if (!jsIsUserFriendlyChar(key, "Decimals")) {
                    return false;
                }
            } else {
                if (evt.shiftKey) {
                    return false;
                }
            }
        }
        return true;
    }
    
$(document).ready(function () {
        $(".js-example-basic-multiple").select2({
            width: 'resolve', // need to override the changed default
            max_selected_options: 4
        });
        
        var selectedValues = new Array();
        <?php 
            $n = 0;
             foreach ($consultoriomedico as $rowConsul):
                 ?>
                    selectedValues[<?php echo $n?>] = "<?php echo $rowConsul->id_consultorio?>";
                 <?php
                 $n++;
             endforeach;
        ?>
        $('.js-example-basic-multiple').val(selectedValues).trigger('change')
	
	$("#btnReg").on("click", function () {		
		var id_establecimiento = $('#id_establecimiento').val();
    var id_colegio_profesional = $('#id_colegio_profesional').val();

    if (id_colegio_profesional == "") {
        bootbox.alert("Debe seleccionar un colegio profesional.");
        return false;
    }
    
		if (id_establecimiento != "") {		
			bootbox.confirm("Aviso: Revise los datos antes de actualizar.", function(result) {
				if (result==true) {
					$("#frmlotizar").submit();
				}
			});
			return false;
		}
	});	
	
	
});

$('#addRow').on('click', function () {
	AddFila();
});


$('#example tbody').on('click', 'button.deleteFila', function () {
	var obj = $(this);
	bootbox.confirm("Desea eliminar la disponibilidad?", function(result) {
		if (result==true) {
			var id_del = obj.parent().parent().find('.id').val();
			var newDelete = '<input type="hidden" name="ids_delete[]" value="'+id_del+'" />';
			$('#example tbody').append(newDelete);
			obj.parent().parent().remove();
		}
	});

});
		
function AddFila(){
		
	var newRow 				= "";
	
	var ind = $('#example tbody tr').length;
	
	if(ind > 0){
		$('.cmbDia').each(function(){
			var ind_tmp = $(this).attr("ind");
			if(ind_tmp => ind)ind=Number(ind_tmp)+1;
		});
	}
	
	var item_dia	= "";
	$('#diaTemp option').each(function(){
		item_dia += "<option value="+$(this).val()+">"+$(this).html()+"</option>"	
	});
	
	var item_turno	= "";
	$('#turnoTemp option').each(function(){
		item_turno += "<option value="+$(this).val()+">"+$(this).html()+"</option>"	
	});
	
	newRow +='<tr>';
	//newRow +='<div id="dvMedicamento"></div>';
	newRow +='<td><select class="form-control cmbDia" id="dia'+ind+'" ind="'+ind+'" tabindex="12" name="dia[]" style="width:200px" >'+item_dia+'</select><input type="hidden" name="id[]" class="id" value=""/></td>';
	newRow +='<td><select class="form-control cmbTurno" id="turno'+ind+'" ind="'+ind+'" tabindex="12" name="turno[]" style="width:200px">'+item_turno+'</select></td>';
	newRow +='<td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="numero_atencion[]" required="" id="numero_atencion" class="nro_solicitado input-sm" style="margin-left:4px; width:100px" value=""/>';
	newRow +='<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
	newRow +='</tr>';
	//alert(newRow);
	$('#example tbody').append(newRow);
	//$("#dia"+ind).select2({max_selected_options: 4});
	//$("#turno"+ind).select2({max_selected_options: 4});

}

  $est = $("#frmlotizar").find("#id_establecimiento");
  $est.select2({
      width: 'resolve' // need to override the changed default
  });
    
	
</script>
@endsection
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Editar Personal de Salud Prescriptor</h3>
        </div>
        <div class="panel-body">

          <form method="post" action="{{ route('update_medico')}}" id="frmlotizar" name="frmlotizar" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input id="id_medico" name="id_medico" value="{{$medico->id}}" type="hidden">
            <input id="id_medico" name="id_farmacia" value="{{$id_farmacia}}" type="hidden">
            <div class="col-md-3">
              <label>Tipo Documento</label>
              <input value="{{$tipo_documento_text}}" class="form-control input-sm" readonly=""
                onkeypress="return IsNumber(event)">
              <input type="hidden" name="tipo_documento_id" id="tipo_documento_id" value="{{$tipo_documento_id}}">
            </div>
            <div class="col-md-3">
              <label>Documento</label>
              <input value="{{$medico->dni}}" class="form-control input-sm" readonly=""
                onkeypress="return IsNumber(event)">
            </div>
            <div class="col-md-3">
              <label>Nombre</label>
              <input value="{{$medico->nombre}}" class="form-control input-sm" readonly="" name="nombre"
                onkeypress="return solo_letra(event)">
            </div>
            <div class="col-md-3">
              <label>Ape. Paterno</label>
              <input value="{{$medico->paterno}}" class="form-control input-sm" readonly="" name="paterno"
                onkeypress="return solo_letra(event)">
            </div>
            <div class="col-md-3" style="margin-top:10px">
              <label>Ape. Materno</label>
              <input value="{{$medico->materno}}" class="form-control input-sm" readonly="" name="materno"
                onkeypress="return solo_letra(event)">
            </div>
            <div class="col-md-6" style="margin-top:10px">
              <label>Colegio</label>
              <select id="id_colegio_profesional" name="id_colegio_profesional" class="form-control input-sm" required="">
                <option value="">--Seleccionar--</option>
                @foreach($colegio_profesional as $col)
                <option value="{{$col->id}}"
                  <?php if ($col->id == $medico->id_colegio_profesional) echo "selected='selected'"; ?>>{{$col->descripcion}}
                </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3" style="margin-top:10px">
            </div>
            <div class="col-md-6" style="margin-top:10px">
              <label>Especialidad del personal de salud</label>
              <select name="id_especializacion" class="form-control input-sm" required="">
                <option value="">--Seleccionar--</option>
                @foreach($especializaciones as $esp)
                <option value="{{$esp->id}}"
                  <?php if ($esp->id == $medico->id_especializacion) echo "selected='selected'"; ?>>{{$esp->nombre}}
                </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6" style="margin-top:10px">
              <label>Profesi&oacute;n</label>
              <select name="id_profesion" class="form-control input-sm" required="">
                <option value="">Seleccionar</option>
                @foreach($profesion as $e)
                <option value="{{$e->id}}" <?php if ($e->id == $medico->id_profesion) echo "selected='selected'"; ?>>
                  {{$e->nombre}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3" style="margin-top:10px">
              <label>Colegiatura</label>
              <input name="colegiatura" value="{{$medico->colegiatura}}" class="form-control input-sm" maxlength="6">
            </div>
            <div class="col-md-3" style="margin-top:10px">
              <label>Nro. RNE</label>
              <input id="rne" name="rne" type="text" value="{{$medico->rne}}" placeholder=""
                class="form-control input-sm" onkeydown="return jsDecimals(event);">
            </div>
            <div class="col-md-6" style="margin-top:10px">
              <label>E-mail</label>
              <input name="email" type="email" value="{{$medico->email}}" class="form-control input-sm">
            </div>
            
            <div class="clearfix"></div>
            <div class="col-md-6" style="margin-top:10px">
              <label>Contrato</label>
              <select name="tipo_contrato" class="form-control input-sm" required="">
                <option value="">Seleccionar</option>
                <?php foreach ($tipo as $rowTipo): ?>
                <option value="<?php echo $rowTipo->id ?>"
                  <?php if ($rowTipo->id == $medico->tipo_contrato) echo "selected='selected'"; ?>>
                  <?php echo $rowTipo->nombre ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <!--
            <div class="col-md-6" style="margin-top:10px">
              <input id="id_establecimiento" name="id_establecimiento" value="{{$medico->id_establecimiento}}" type="hidden">
                            <label>IPRESS PNP</label>
                            <input value="{{$medico->ipress}}" class="form-control input-sm" readonly="">
            </div>
            -->
            <div class="col-md-6" style="margin-top:10px">
              <label>Asignar nueva IPRESS PNP</label>
              <select name="id_establecimiento" id="id_establecimiento" class="form-control input-sm">
                <option value="">Seleccionar</option>
                @foreach($establecimientos as $e)
                <option value="{{$e->id}}" @if ($e->id == $medico->id_establecimiento) selected="selected" @endif >
                  {{$e->nombre}}
                </option>
                @endforeach
              </select>
            </div>
            
            <div class="col-md-6" style="margin-top:10px">
              <label>Foto Firma</label>
              <input type="file" name="archivo_firma" id="archivo_firma" class="form-control input-sm">
              @if($medico->nombre_firma != null)
              <div style="text-align:center">
                <img src="/images/profesionales/{{$medico->nombre_firma}}" alt="" width="220px" class="img-thumbnail">
              </div>
              @endif
            </div>

            <div class="col-md-12" style="margin-top:10px">
              <label>Servicio de Atenci&oacute;n</label>
              <select class="form-control js-example-basic-multiple" name="id_consultorio[]" required=""
                style="width: 100%" multiple="multiple">
                @foreach($consultorio as $e)
                <option value="{{$e->id}}" <?php if ($e->id == $medico->id_consultorio) echo "selected='selected'"; ?>>
                  {{$e->nombre}}</optio>
                  @endforeach
              </select>
              <p class="text-danger"><b>(*)</b> Si elimina un Servicio, se desactivará y se cancelará la programación de
                sus atenciones a partir de mañana.</p>
            </div>
            <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
              <a href="/gestion_medicos" class="form-control btn btn-danger">Cancelar</a>
            </div>
            <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
              <input id="btnReg" class="btn btn-success form-control" value="Actualizar" type="submit">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@include('frontend.includes.footer')
@endsection