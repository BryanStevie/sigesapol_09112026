@extends('frontend.layouts.master')
@section('renderjs')
<script>
    $cmbtipo = $("#frm_transferencia").find("#tipo");
    $cmbtipo.select2();
    $cmbid_origen = $("#frm_transferencia").find("#id_origen");
    $cmbid_origen.select2();
    $cmbid_lugar = $("#frm_transferencia").find("#id_lugar");
    $cmbid_lugar.select2();
    //--------------------------------------------------------------------------
    $(document).ready(function () {
        $cmbtipo.change(function () {
            $this = $(this);
            cmbtipo = $cmbtipo.val();
            option = {
                url: '/cargardestino/' + cmbtipo,
                type: 'GET',
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                //alert(data);
                cargarComboDestino($cmbid_lugar, data);
                $cmbid_lugar.val(null).trigger("change");
            });
        });
    });
    function cargarComboDestino($select, data) {
        $select.html('');
        $(data).each(function (ii, oo) {
            $select.append('<option value="' + oo.id + '">' + oo.nombre + '</option>')
        });
    }    
    //-------------------AGREGAR PRODUCTOS--------------------------------------
    $(document).ready(function () {
        var t = $('#example').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            bFilter: false,
            bPaginate: false,
            bSort: false,
            "createdRow": function (row, data, index) {
                cmbMedicamento = $("#cmbMedicamento").clone();
                $(cmbMedicamento).attr('name', 'id_producto[]');
                $(cmbMedicamento).on("change", function (e) {
                    cmb = $(this);
                    id_producto = $(cmbMedicamento).val();
                    id_user={{Auth::user()->id}};
                    //id_farmacia={{Auth::user()->id}};
                    //alert(id_user);
                    option = {
                        url: '/cargarstock_farmacia/' + id_producto+ '/' + id_user,
                        type: 'GET',
                        dataType: 'json',
                        data: {}
                    };
                    $.ajax(option).done(function (data) {
                        //alert(data);                        
                        $(cmb).closest("tr").find("#nro_stocks").val(data);                        
                        //alert($(cmb).closest("tr").html());
                    });
                    //alert($id_producto);
                });                                
                $(row).find('#dvMedicamento').append(cmbMedicamento);
                $(cmbMedicamento).select2({max_selected_options: 4});
                //alert(.html());
            },
            "language": {
                "emptyTable": "Agregar Productos Farmacéuticos o Dispositivos Médicos"
            }
        });
        var counter = 1;

        $('#example tbody').on('click', 'button.deleteFila', function () {
            r = confirm("¿Desea eliminar el Producto Farmacéutico o Dispositivo Médico?");
            if (r) {
                t.row($(this).closest('tr')).remove().draw();
            }

        });
        $('#addRow').on('click', function () {
            t.row.add([
                '<div id="dvMedicamento"></div>',
                '<input type="number" name="nro_stocks[]" required id="nro_stocks" class="nro_solicitado input-sm" style="margin-left:4px; width:100px" disabled=""/>',
                '<input type="number" name="nro_med_solictados[]" required id="nro_med_solictados" class="form-control nro_solicitado input-sm"/>',
                '<button type="button"  class="btn btn-danger deleteFila btn-xs"><i class="fa fa-times"></i> Eliminar</button>'
            ]).draw(false);
        });

        // Automatically add a first row of data
        //$('#addRow').click();
        $("#frm_transferencia").submit(function (e) {
        rows = $("#example tbody").children('tr');
        $.each(rows, function (ii, oo) {
            medicina = $(oo).find("#cmbMedicamento").select2("data");                               
            //nuevaCantidad = parseFloat($(oo).find("#nro_med_entregados").val());
            cantidadPrescrita = parseFloat($(oo).find("#nro_med_solictados").val());
            stockActual = parseFloat($(oo).find("#nro_stocks").val());
            //alert(cantidadPrescrita);
            if (stockActual == 0) {                    
                alert("La medicina: " +medicina[0].text+ " no tiene stock, y no se puede realizar una transferencia");                
                e.preventDefault();
                return false;                 
                } 
            else 
                {
                    if (cantidadPrescrita > stockActual) {
                    alert("La medicina: " + medicina[0].text + ", la cantidad a transferir no tiene que ser mayor que el stock");                                        
                    e.preventDefault();
                    return false;
                    }
                }
            });
        });
    });    
    //----------------REGISTRAR LA TRANSFERENCIA---------------------------------

</script>
<style>
    .etiqueta{margin-top: 15px}
    .panel_medicamentos{border: 1px solid gray; padding-top: 10px}
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">REGISTRAR</h3>                    
                    <a href="/all_transferencias/{{ Auth::user()->id }}" class="btn btn-default pull-right">
                        <i class="fa fa-list"></i> Listado de Transferencias
                    </a>
                </div>
                <div class="panel-body">
                    <form id="frm_transferencia" method="POST" action="{{ route('registrar_transferencia')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <legend>
                            Registrar Transferencia o entrega de Productos Farmacéuticos
                        </legend>
                        <!------- Fila 01------------------------------------------>                        
                        <div class="col-md-6">
                            <label>Nro Acta</label>
                            <input name="nro_acta" value="" required="" class="form-control" type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                        </div>>     
                            <input name="id_user" value="{{ Auth::user()->id }}" class="form-control" type="hidden"> 
                        </div>                        
                        <div class="col-md-6">
                            <label>Fecha</label>
                            <input name="fecha" value="" required="" class="form-control datepicker">
                        </div>                        
                        <!------- Fila 02------------------------------------------>                        
                        <div class="col-md-6 etiqueta">
                            <label>Lugar Origen</label>
                            <select class="form-control" required="" name="id_origen" id="id_origen">
                                <option value="{{$farmacia->id}}">{{$farmacia->nombre}}</option>
                            </select>
                        </div>
                        <div class="col-md-6 etiqueta">
                            <label>Responsable (Lugar Origen)</label>
                            <input name="responsable_origen" value="" required="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                        </div>
                        <!------- Fila 03------------------------------------------>                        
                        <div class="col-md-12 etiqueta">
                            <label>Tipo</label>
                            <select name="tipo" id="tipo" class="form-control" required="">
                                <option value="" selected="">Seleccionar</option>
                                <option value="1">Transferencia Externa</option>
                                <option value="2">Transferencia Interna</option>
                            </select>
                        </div>
                        <!------- Fila 04------------------------------------------>                        
                        <div class="col-md-6 etiqueta">
                            <label>Lugar Destino</label>
                            <select id="id_lugar" name="id_lugar" class="form-control"  required="">                                                                
                                <option value="" disabled selected style="color: #888888">Seleccionar uno</option>      
                            </select>
                        </div>
                        <div class="col-md-6 etiqueta">
                            <label>Responsable (Lugar Destino)</label>
                            <input name="responsable_destino" value="" required="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                        </div>
                        <legend>Productos Farmacéuticos y Dispositivos Médicos</legend>
                        <div class="col-md-12 etiqueta panel_medicamentos">
                            <button type="button" id="addRow" class="btn btn-primary btn-xs">
                                <i class="fa fa-plus"></i> Agregar producto(s)
                            </button>
                            <div style="display: none" class="form-control">
                                <select class="form-control" id="cmbMedicamento" tabindex="12" name="tmp_id_producto[]" style="width: 500px">
                                    <option value="">Seleccionar Producto</option>                                                                                                                                                            
                                    @foreach($productos as $p)                                                                                                
                                    <option value="{{$p->id}}">{{$p->codigo}} - {{$p->nombre}}, {{$p->abreviatura}}</option>         
                                    @endforeach
                                </select> 
                            </div>
                            <table id="example" class="display" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Producto farmacéutico o dispositivo médico</th>
                                        <th>Stock Actual</th>
                                        <th>Cantidad a transferir</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4 etiqueta pull-right">
                            <input class="btn btn-success form-control" value="Registrar" type="submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection