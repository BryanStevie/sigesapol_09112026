@extends('frontend.layouts.master2')
@section('renderjs')
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}
<script src="{{asset('js/jquery.inputmask.bundle.min.js')}}"></script>
<style>
    .etiqueta{margin-top: 5px}
    .panel_medicamentos{border: 1px solid gray; padding-top: 10px}
    .ui-autocomplete {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 160px;
    padding: 5px 7px;
    margin: 2px 0 0;
    list-style: none;
    font-size: 15px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: left;
    background-color: #ffffff;
    border: 1px solid #cccccc;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    background-clip: padding-box;
  }

  .ui-autocomplete>li>div {
    display: block;
    padding: 3px 20px;
    clear: both;
    font-weight: normal;
    line-height: 1.42857143;
    color: #333333;
    white-space: nowrap;
  }

  .ui-state-hover,
  .ui-state-active,
  .ui-state-focus {
    text-decoration: none;
    color: #FFFFFF !important;
    background: #1d71b8 !important;
    cursor: pointer;
  }

  .ui-helper-hidden-accessible {
    border: 0;
    clip: rect(0 0 0 0);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute;
    width: 1px;
  }

</style>

<script src="<?php echo URL::to('/') ?>/js/js.util.grid.js"></script>
<script>

    $(document).ready(function () {
        $('#example-select-all').on('click', function(){
            var rows = $('#tblCita').DataTable().rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
            addMovimientoAll();
        });

        datatablenew();
        AddBusquedaDNIHC();
        
        $('#addDniHc2').on('click',function(){
            AddBusquedaDNIHC();
        });

        $('#btnBuscar').click(function () {
            fn_ListarBusqueda();
        });
    });



    function fn_ListarBusqueda() {
        datatablenew();
    };

    
    var m = 0;

function datatablenew(){
    var oTable1 = $('#tblCita').dataTable({
        "bServerSide": true,
        "sAjaxSource": "/buscar_historias_movimientos",
        "bProcessing": true,
        "sPaginationType": "full_numbers",
        //"paging":false,
        "bFilter": false,
        "bSort": false,
        "info": true,
        "language": {"url": "/js/Spanish.json"},
        "autoWidth": false,
        "bLengthChange": true,
        "destroy": true,
        "lengthMenu": [[ 20, 100, 200, 60000], [ 20, 100, 200, "Todos"]],
        "aoColumns": [
                        {},
        ],
        "dom": '<"top">rt<"bottom"flpi><"clear">',
        "fnDrawCallback": function(json) {
            $('[data-toggle="tooltip"]').tooltip();
        },

        "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
            var sEcho           = aoData[0].value;
            var iNroPagina  = parseFloat(fn_util_obtieneNroPagina(aoData[3].value, aoData[4].value)).toFixed();
            var iCantMostrar    = aoData[4].value;
            var id_establecimiento = {{Auth::user()->id_establecimiento}};    
            var id_dni_hc = $('#id_dni_hc0').val();
            var _token = $('#_token').val();
            oSettings.jqXHR = $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data":{NumeroPagina:iNroPagina,NumeroRegistros:iCantMostrar,
                        id_establecimiento:id_establecimiento,id_dni_hc:id_dni_hc,
                        _token:_token
                       },
                "success": function (result) {
                    fnCallback(result);
                },
                "error": function (msg, textStatus, errorThrown) {
                }
            });
        },

        "aoColumnDefs":
            [   
                {
                "mRender": function (data, type, row) {
                    var dia = "";
                    if(row.fecha!= null)dia = row.fecha;
                    return dia;
                },
                "bSortable": false,
                "aTargets": [0]
                },
                {
                "mRender": function (data, type, row) {
                    var asegurado = "";
                    if(row.tipo_doc_ident!= null)asegurado = row.tipo_doc_ident;
                    if(row.nro_doc_ident!= null)asegurado += " : "+ row.nro_doc_ident;
                    if(row.asegurado_paterno!= null)asegurado += " - "+row.asegurado_paterno;
                    if(row.asegurado_materno!= null)asegurado += " "+row.asegurado_materno;
                    if(row.asegurado_nombre!= null)asegurado += " ,"+row.asegurado_nombre;
                    return asegurado;
                },
                "bSortable": false,
                "aTargets": [1]
                },
                {
                "mRender": function (data, type, row) {
                    var nro_historia = "";
                    var newRow = "";
                    if(row.nro_historia!= null)newRow += row.nro_historia;
                    return newRow;
                },
                "bSortable": false,
                "aTargets": [2]
                },
                {
                "mRender": function (data, type, row) {
                    var nro_movimiento = "";
                    var newRow = "";
                    if(row.nro_movimiento!= null)newRow += row.nro_movimiento;
                    return newRow;
                },
                "bSortable": false,
                "aTargets": [3]
                },
                {
                "mRender": function (data, type, row) {
                    var servicio_origen = "";
                    var newRow = "";
                    if(row.servicio_origen!= null)newRow += row.servicio_origen;
                    return newRow;
                },
                "bSortable": false,
                "aTargets": [4]
                },
                {
                "mRender": function (data, type, row) {
                    var responsable_origen = "";
                    var newRow = "";
                    if(row.responsable_origen!= null)newRow += row.responsable_origen;
                    return newRow;
                },
                "bSortable": false,
                "aTargets": [5]
                },
                {
                "mRender": function (data, type, row) {
                    var nro_entrada = "";
                    var newRow = "";
                    if(row.nro_entrada!= null)newRow += row.nro_entrada;
                    return newRow;
                },
                "bSortable": false,
                "aTargets": [6]
                },
                {
                "mRender": function (data, type, row) {
                    var servicio_destino = "";
                    var newRow = "";
                    if(row.servicio_destino!= null)newRow += row.servicio_destino;
                    return newRow;
                },
                "bSortable": false,
                "aTargets": [7]
                },
                {
                "mRender": function (data, type, row) {
                    var responsable_destino = "";
                    var newRow = "";
                    if(row.responsable_destino!= null)newRow += row.responsable_destino;
                    return newRow;
                },
                "bSortable": false,
                "aTargets": [8]
                },
                {
                "mRender": function (data, type, row) {
                    var estado_movimiento = "";
                    var newRow = "";
                    if(row.estado_movimiento!= null)newRow += row.estado_movimiento;
                    return newRow;
                },
                "bSortable": false,
                "aTargets": [9]
                }
            ]
    });
}


function  AddBusquedaDNIHC(){
    var newRow = "";
    var ind = $('#tblDni tbody tr').length;
    
    if(ind > 0){
        $('.id_dni_hc').each(function(){
            var ind_tmp = $(this).data("id");
            if(ind_tmp => ind){
                ind=Number(ind_tmp)+1;
            }
        });
                    
        fila_medicamentos=$('#tblDni tbody').children('tr');
        rowIndex=$('#hidden_rowIndex').val();
        var idval = ind - 1;
        fila_medicamento=fila_medicamentos[idval];
        
    }
    
    newRow +='<tr>';
    newRow +='<td><div class="col-md-12" style="margin-left:0px;padding-left:0px"><br/> <input type="text" name="dni_hc[]" tabindex="112" required="" id="dni_hc'+ind+'" class="input-sm" style="margin-left:4px; width:100%; text-align:left" /> </div></td>';
    newRow +='<input type="hidden" class="form-control typeahead tt-query id_dni_hc" id="id_dni_hc'+ind+'" name="id_dni_hc[]" value="" data-id='+ind+'></td>';
    newRow +='</tr>';
    
    $('#tblDni tbody').append(newRow);

    $("#dni_hc"+ind).autocomplete({
        minLength: 1,
        delay : 400,
        source: function(request, response) {
            var id_establecimiento       = {{Auth::user()->id_establecimiento}}; //$('#id_establecimiento').val();
            var id_consultorio       = {{Auth::user()->id_consultorio}};
            $.ajax({
                url: '../../../../obtener_hc_dni/' + request.term +'/' + id_establecimiento +'/' + id_consultorio,
                dataType: "json",
                success: function(data)  {
                    let listaServicioSelecionado = $(".id_dni_hc").map(function(){return parseInt($(this).val());}).get();
          let filtro = data.filter((element)=>!listaServicioSelecionado.includes(element.id));
          response(filtro);
                }                   
            })
    
        },select:  function(e, ui) {
            var id_dni_hc = ui.item.id;
            $("#id_dni_hc"+ind).val(id_dni_hc);
            $("#dni_hc"+ind).prop("readonly",false);
        }
    });
}
</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-2">
            <div class="col-sm-12 pull-right">
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="/all_movimiento_hc/"><i class="fa fa-table fa-fw"></i> Listar Salidas de Historias</a></li>
                    <li><a href="/add_hc_registro_salida/{{ Auth::user()->id}}"><i class="fa fa-table fa-fw"></i> Registrar Salidas</a></li>
                    <li><a href="/all_historias_recibidas/{{ Auth::user()->id}}"><i class="fa fa-table fa-fw"></i> Ingreso de Historias</a></li>
                    <li><a href="/nueva_historia/"><i class="fa fa-table fa-fw"></i> Nueva Historia</a></li>
                    <li class="active"><a href="/buscar_movimiento_historia/"><i class="fa fa-table fa-fw"></i> Buscar Historias</a></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="panel panel-body">
                <legend style="text-align:center">{{$farmacia->nombre}} <br/> {{$nombre_consultorio->nombre}}</legend>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Buscar el Movimientos de la Historia Clínica</h3>
                    </div>
                    <div class="panel with-nav-tabs panel-default">
                        <div class="panel-heading">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab2default" data-toggle="tab" style="font-size:12px">BUSCAR HISTORIA</a></li>
                            </ul>
                        </div>                
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab2default">
                                <form name="frm_movimiento_hc" id="frm_movimiento_hc" method="POST" action="{{ route('agregar_historias_clinicas')}}" onsubmit="return validateFrm();">
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                   <table class="display" id="tblDni" cellspacing="0" width="100%" style="padding-top:20px;padding-right:0px">
                                                        <thead>
                                                            <tr>
                                                               <th width="100%"></th>
                                                            </tr>
                                                        </thead>
                                                        <tfoot>
                                                        </tfoot>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="padding-top:20px;padding-right:0px">
                                                    <input id="btnBuscar" class="form-control btn btn-info input-sm" value="Buscar" type="button">
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="tblCita" class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>Paciente</th>
                                                            <th>Nro Historia</th>
                                                            <th>Nro Salida</th>
                                                            <th>Servicio Origen</th>
                                                            <th>Responsable Origen</th>
                                                            <th>Nro Ingreso</th>
                                                            <th>Servicio Destino</th>
                                                            <th>Responsable Destino</th>
                                                            <th>Estado</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
