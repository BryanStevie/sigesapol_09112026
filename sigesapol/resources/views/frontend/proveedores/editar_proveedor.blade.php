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
        
	$("#btnReg").on("click", function () {		
		var id_establecimiento = $('#id_establecimiento').val();
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

	
</script>
@endsection
@section('content')
<div class="container">    
    <div class="row">
        <div class="col-md-10">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Editar Proveedor de Salud</h3>
                </div>
                <div class="panel-body">

                    <form method="post" action="{{ route('update_proveedor')}}" id="frmlotizar" name="frmlotizar">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input id="id_proveedor" name="id_proveedor" value="{{$proveedor->id}}" type="hidden">
                        <div class="col-md-6">
                                    <label>RUC</label>
                                    <input autofocus="" id="ruc_proveedor" name="ruc_proveedor" type="text" class="form-control input-sm" required="" maxlength="11"  value="{{$proveedor->ruc_proveedor}}" onkeypress="return IsNumber(event)">
                                    <input id="id_user" name="id_user" type="hidden" value="{{Auth::user()->id}}">
                                </div>
                                <div class="col-md-6">
                                    <label>E-mail</label>
                                    <input name="email" type="email" value="{{$proveedor->email_proveedor}}" class="form-control input-sm" >
                                </div>
                                <div class="col-md-12">
                                    <label>Nombre o Descripcion del Proveedor</label>
                                    <textarea id="nombre_proveedor" name="nombre_proveedor" type="text" class="form-control input-sm" required="" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" ><?php echo $proveedor->nombre_proveedor; ?>
                                    </textarea> 
                                </div>
                                <div class="col-md-12">
                                    <label>Direccion del Proveedor</label>
                                    <textarea id="direccion_proveedor" name="direccion_proveedor" type="text" class="form-control input-sm" required=""  style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();"><?php echo $proveedor->direccion_proveedor; ?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label>Telefono</label>
                                    <input id="telefono" value="{{$proveedor->telefono_proveedor}}" name="telefono" type="text"  class="form-control input-sm" maxlength="9" class="form-control input-sm" required="" style="text-transform:uppercase;" onkeypress="return IsNumber(event)">
                                </div>
                                <div class="col-md-6">
                                    <label>Fax</label>
                                    <input id="fax" name="fax"  value="{{$proveedor->telefono_proveedor}}" maxlength="9" type="text" placeholder="" class="form-control input-sm" onkeypress="return IsNumber(event)">
                                </div>
                        <div class="col-md-6" style="margin-top: 10px; margin-bottom: 10px">
                            <a href="/listar_proveedores" class="form-control btn btn-danger" >Cancelar</a>
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
