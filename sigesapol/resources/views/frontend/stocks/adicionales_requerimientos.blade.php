@extends('frontend.layouts.master')
@section('renderjs')
<script>
    //-------------------------------------------------------------
    $("#btnReg").on("click", function () {
        if (confirm("¿Desea Grabar?") == true) {
            $("#frmReceta").submit();
        }
    });
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
                $(row).find('#dvMedicamento').append(cmbMedicamento);
                $(cmbMedicamento).select2({max_selected_options: 4});
                //alert(.html());
            },
            "language": {
                "emptyTable": "Agregar Productos Farmacéuticos o Dispositivos Médicos al requerimiento"
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
                '<input type="number" name="cantidad[]" required id="cantidad[]" class="form-control nro_solicitado input-sm"/>',
                '<button type="button"  class="btn btn-danger deleteFila btn-xs"><i class="fa fa-times"></i> Eliminar</button>'
            ]).draw(false);
        });

        // Automatically add a first row of data
        //$('#addRow').click();
    });

</script>
@endsection
@section('content')
<div class="container">
    <div class="row">        
        <div class="col-sm-9 col-md-9">
            <div class="row">
                <div class="panel panel-primary">            
                    <div class="panel-heading" style="margin-bottom: 20px">
                        <h3 class="panel-title">Agregar Productos adicionales en el Requerimiento {{$requerimiento->numero}}</h3>                
                    </div> 
                    <div style=" font-size: 11px" class="panel-body">
                        <form class="form-horizontal" method="post" action="{{ route('registrar_adicionales_requerimiento')}}" id="frmReceta">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">                           
                             <div class="col-md-12" style="padding-top:10px; padding-bottom:10px; border: 1px solid gray; margin-bottom: 20px">
                                <button type="button" id="addRow" class="btn btn-primary btn-xs">
                                    <i class="fa fa-plus"></i> Agregar producto(s)
                                    <input value="{{$id_requerimiento}}" name="id_requerimiento" type="hidden">
                                    <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                                </button>
                                <div style="display: none">
                                    <select class="form-control" id="cmbMedicamento" tabindex="9" name="tmp_id_producto[]" style="width: 500px" >
                                        <option value="">Seleccionar Producto</option>   
                                        @foreach($productos as $p)
                                        <option value="{{$p->codigo}}">{{$p->codigo}} - {{$p->nombre}},{{$p->abreviatura}}</option>
                                        @endforeach
                                    </select> 
                                </div>
                                <table id="example" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Producto farmacéutico o dispositivo médico</th>
                                            <th>Cantidad </th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                    </tfoot>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>                            
                            <div class="form-group">
                                <div class="col-md-6">
                                    <a href="/ver_requerimiento/{{ Auth::user()->id }}" class="form-control btn btn-danger" tabindex="17">CANCELAR</a>
                                </div>
                                <div class="col-md-6">
                                    <input id="btnReg" class="form-control btn btn-success" type="button" value="AGREGRA PRODUCTOS ADICIONALES" tabindex="16">
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection