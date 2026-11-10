@extends('frontend.layouts.master')
@section('renderjs')
<script>
 //-------------------AGREGAR PRODUCTOS--------------------------------------
    $(document).ready(function () {
        //alert("hola");
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
                    codigo_producto = $(cmbMedicamento).val();
                    //id_user={{Auth::user()->id}};
                    //alert(id_user);
                    option = {
                        url: '/buscar_codigo_producto/' + codigo_producto,
                        type: 'GET',
                        dataType: 'json',
                        data: {}
                    };
                    $.ajax(option).done(function (data) {
                        //alert(data);                        
                        $(cmb).closest("tr").find("#codigo_producto").val(data);                        
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
                '<input style="" type="text" name="codigo_producto[]" required id="codigo_producto" class="ancho" style="margin-left:4px; width:100px"/>',
                '<input type="text" name="cantidad[]" required id="cantidad" class="ancho"/>',
                '<input type="text" name="registro_sanitario[]" required id="registro_sanitario" class="ancho"/>',
                '<input type="text" name="lote[]" required id="lote" class="ancho" required/>',
                '<input type="text" name="fecha_vencimiento[]" required id="fecha_vencimiento"  onKeyUp = "this.value=formateafecha(this.value);"/>',
                '<button type="button"  class="btn btn-danger deleteFila btn-xs"><i class="fa fa-times"></i> Eliminar</button>'
            ]).draw(false);
        });
    }); 
    
    
    
    function IsNumeric(valor)
    {
        var log = valor.length;
        var sw = "S";
        for (x = 0; x < log; x++)
        {
            v1 = valor.substr(x, 1);
            v2 = parseInt(v1);
//Compruebo si es un valor numérico 
            if (isNaN(v2)) {
                sw = "N";
            }
        }
        if (sw == "S") {
            return true;
        } else {
            return false;
        }
    }
    var primerslap = false;
    var segundoslap = false;
    function formateafecha(fecha)
    {
        var long = fecha.length;
        var dia;
        var mes;
        var ano;
        if ((long >= 2) && (primerslap == false)) {
            dia = fecha.substr(0, 2);
            if ((IsNumeric(dia) == true) && (dia <= 31) && (dia != "00")) {
                fecha = fecha.substr(0, 2) + "-" + fecha.substr(3, 7);
                primerslap = true;
            }
            else {
                fecha = "";
                primerslap = false;
            }
        }
        else
        {
            dia = fecha.substr(0, 1);
            if (IsNumeric(dia) == false)
            {
                fecha = "";
            }
            if ((long <= 2) && (primerslap = true)) {
                fecha = fecha.substr(0, 1);
                primerslap = false;
            }
        }
        if ((long >= 5) && (segundoslap == false))
        {
            mes = fecha.substr(3, 2);
            if ((IsNumeric(mes) == true) && (mes <= 12) && (mes != "00")) {
                fecha = fecha.substr(0, 5) + "-" + fecha.substr(6, 4);
                segundoslap = true;
            }
            else {
                fecha = fecha.substr(0, 3);
                ;
                segundoslap = false;
            }
        }
        else {
            if ((long <= 5) && (segundoslap = true)) {
                fecha = fecha.substr(0, 4);
                segundoslap = false;
            }
        }
        if (long >= 7)
        {
            ano = fecha.substr(6, 4);
            if (IsNumeric(ano) == false) {
                fecha = fecha.substr(0, 6);
            }
            else {
                if (long == 10) {
                    if ((ano == 0) || (ano < 1900) || (ano > 2100)) {
                        fecha = fecha.substr(0, 6);
                    }
                }
            }
        }
        if (long >= 10)
        {
            fecha = fecha.substr(0, 10);
            dia = fecha.substr(0, 2);
            mes = fecha.substr(3, 2);
            ano = fecha.substr(6, 4);
// Año no viciesto y es febrero y el dia es mayor a 28 
            if ((ano % 4 != 0) && (mes == 02) && (dia > 28)) {
                fecha = fecha.substr(0, 2) + "-";
            }
        }
        return (fecha);
    }
</script>
<style>
    .ancho{width: 85px}
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
                    <h3 class="panel-title">REGISTRAR STOCK INICIAL</h3>                    
                </div>
                <div class="panel-body">
                    <form id="frm_carga" method="POST" action="{{ route('add_carga_inicial')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-12 etiqueta panel_medicamentos">                                
                            <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$id_farmacia}}">
                            <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}">
                            <div style="display: none">
                                <select class="form-control" id="cmbMedicamento" tabindex="12" name="tmp_id_producto[]" style="width: 500px">
                                    <option value="">Seleccionar Producto</option>                                                                                                                                                            
                                    @foreach($productos as $p)                                                                                                
                                    <option value="{{$p->codigo}}">{{$p->nombre}}, {{$p->abreviatura}}</option>         
                                    @endforeach
                                </select> 
                            </div>
                            <table id="example" class="display" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Producto farmacéutico o dispositivo médico</th>
                                        <th>Codigo</th>
                                        <th>Cantidad</th>
                                        <th>Reg. Sanitario</th>
                                        <th>Lote</th>
                                        <th>Fecha Ven.(dd-mm-aaaa)</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                </tbody>
                            </table>
                            <button type="button" id="addRow" class="btn btn-primary btn-xs" style="margin-bottom: 10px">
                                <i class="fa fa-plus"></i> Agregar producto(s)
                            </button>
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