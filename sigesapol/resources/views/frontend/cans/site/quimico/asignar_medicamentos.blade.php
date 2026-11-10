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
            var rows = $('#tblConsultorio').DataTable().rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
            addConsultorioAll();
        });
        
        datatablenew();
        
        var activeSystemClass = $('.list-group-item.active');
        
    });

    

    //-------------------------------------------------------------------------
    function validateFrm() {
        var msg = "";
        var sw = true;
        
        if (sw == false) {
            bootbox.alert(msg);
            return sw;
        } else {
            submitFrm();
        }
        return false;
    }
    function submitFrm() {
        bootbox.confirm({
            message: "Se registrarán los consultorios seleccionados, ¿Está seguro de continuar?",
            buttons: {
                confirm: {
                    label: 'Si',
                    className: 'btn-success btn-md'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger btn-md'
                }
            },
            callback: function (result) {
                if (result == true) {
                    document.frm_consultorio_est.submit();
                }
            }
        });
    }
    //-------------------------------------------------------------------------
    var m = 0;

function datatablenew(){
    var oTable1 = $('#tblConsultorio').dataTable({
        "bServerSide": true,
        "sAjaxSource": "/listar_consultorio_establecimiento",
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
        "lengthMenu": [[ 50, 100, 200, 60000], [ 50, 100, 200, "Todos"]],
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
            
            var id_establecimiento = {{$id_establecimiento}};            
            var _token = $('#_token').val();
            
            oSettings.jqXHR = $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data":{NumeroPagina:iNroPagina,NumeroRegistros:iCantMostrar,
                        id_establecimiento:id_establecimiento,
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
                    var id="";var checkeado="";
                    if(row.id!=null)id=row.id;
                    if(row.checkeado==row.id)checkeado='checked';
                    var newRow = "<input type='checkbox' value='"+row.id+"' name='id_cons_est[]' codigo='"+id+"' id='movimientos["+m+"].id' onchange=addConsultorio("+row.id+",'"+id+"',this)' "+checkeado+"  />";
                    m++;
                    return newRow;
                },
                "bSortable": false,
                "className": "dt-center",
                "aTargets": [0],
                "visible": true,
                },
                {
                "mRender": function (data, type, row) {
                    var servicio = "";
                    if(row.nombre!= null)servicio = row.nombre;
                    return servicio;
                },
                "bSortable": false,
                "aTargets": [1]
                }
            ]
    });
}

function addConsultorio(id,codigo,obj){
        
    var newRow = "";
    
    if ($(obj).is(':checked')){
        var pht = 0;
        $("input[name='mov']").each(function(){
            var rs = $(this).val().toString()
            if(rs == id){
                pht++;
            }
        });
        
        if(pht == 0){
            newRow +="<td style='padding-right:0px;float:left;border:0px'><input name='mov' type='hidden' value='"+id+"' /><span class='label alert-warning'>"+codigo+"</span></td>";
            $('#Grd2 tbody tr').append(newRow);
        }
        
        var cantht = $('#Grd2 tbody tr td').length;
        $('#spanContador').html(cantht);
        
    }else{
        var cantht = $('#Grd2 tbody tr td').length;
        if(cantht > 0){
            $("input[name='mov']").each(function(){
                var rs = $(this).val().toString()
                if(rs == id){
                    $(this).parent().remove();
                }
            });
        }
        cantht = $('#Grd2 tbody tr td').length;
        $('#spanContador').html(cantht);
    }

}

function addConsultorioAll(){
        
    var newRow = "";
    
    if ($("input[name='select_all']").is(':checked')){
        var pht = 0;
        $("input[name='id_cons_est[]']").each(function(){
            var id = $(this).val().toString();
            var codigo = $(this).attr("codigo").toString();
            newRow ="<td style='padding-right:0px;float:left'><input name='mov' type='hidden' value='"+id+"' /><span class='label alert-warning'>"+codigo+"</span></td>";
            $('#Grd2 tbody tr').append(newRow);
        });
        
    }else{
        var cantht = $('#Grd2 tbody tr td').length;
        if(cantht > 0){
            $("input[name='id_cons_est[]']").each(function(){
                var id = $(this).val().toString();
                $("input[name='mov']").each(function(){
                    var rs = $(this).val().toString()
                    if(rs == id){
                        $(this).parent().remove();   
                    }
                });
            });
        }
    }
    
    var cantht = $('#Grd2 tbody tr td').length;
    $('#spanContador').html(cantht);
    
}

</script>
@endsection
@section('content')
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">
            {!!$establecimiento->nombre!!}
        </h3>      
        <a class="btn btn-info pull-right" href="/listar_establecimientos">
            <i class="fa fa-list"></i> Regresar
        </a>
    </div>
    <div class="panel-body">
        <div class="col-md-7" style="margin-top:15px">
            <div class="panel with-nav-tabs panel-default">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab2default" data-toggle="tab" style="font-size:12px">SERVICIOS DEL ESTABLECIMIENTO</a></li>
                    </ul>
                </div>                
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab2default">
                        <form name="frm_consultorio_est" id="frm_consultorio_est" method="POST" action="{{ route('store_consultorio_establecimiento_can')}}" onsubmit="return validateFrm();">
                            <input type="hidden" name="id_establecimiento" id="id_establecimiento" value="{{$id_establecimiento}}">
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                    <input class="btn btn-success form-control" value="Agregar" type="submit" id="btnAgregar">
                                    <div class="table-responsive">
                                        <table id="tblConsultorio" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="text-align:left; padding-bottom:0px;padding-right:5px;margin-bottom: 0px; vertical-align: middle">
                                                        Todos<br>
                                                        <input type="checkbox" name="select_all" value="1" id="example-select-all">
                                                    </th>
                                                    <th>Consultorio</th>
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
    
@include('frontend.includes.footer')
@endsection
