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

    function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8))
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }
	
	function escapeRegExp(string){
		return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
	}

	function replaceAll(str, term, replacement) {
	  return str.replace(new RegExp(escapeRegExp(term), 'g'), replacement);
	}

    $(document).ready(function () {
        $('#example-select-all').on('click', function(){
            var rows = $('#tblCita').DataTable().rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
            addMovimientoAll();
        });
        
        datatablenew();
        AddBusquedaDNIHC();
        
        $('#tblDni tbody').on('click', 'button.deleteFila', function () {
        var obj = $(this);
            obj.parent().parent().remove();
        });
        
        $('#addDniHc2').on('click',function(){
            AddBusquedaDNIHC();
        });

        $('#btnBuscar').click(function () {
            fn_ListarBusqueda();
        });

        $('#fecha_ini').appendDtpicker({
        "dateOnly": true,
                "autodateOnStart": false,
                "minuteInterval": 5,
                "amPmInTimeList": false,
                "locale": "es",
                "closeOnSelected": true,
                "dateFormat": "DD-MM-YYYY"
        });
        $('#fecha_fin').appendDtpicker({
        "dateOnly": true,
                "autodateOnStart": false,
                "minuteInterval": 5,
                "amPmInTimeList": false,
                "locale": "es",
                "closeOnSelected": true,
                "dateFormat": "DD-MM-YYYY"
                //"dateFormat": "YYYY-MM-DD hh:mm",
        });
        $('#fecha_ini').click(function () {
        var fechaDesdeIni = $('#fecha_ini').val();
        $('#fecha_ini').change(function () {
        var fechaDesde = $('#fecha_ini').val();
        var fechaHasta = $('#fecha_fin').val();
        f1 = fechaDesde.split("-");
        f2 = fechaHasta.split("-");
        f3 = new Date();
        var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
        var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

        if (f1 > f2) {
        bootbox.alert("La Fecha Desde no debe ser mayor a la Fecha Hasta");
        $('#fecha_ini').val(fechaDesdeIni);
        return false;
        }
        });
        });
        $('#fecha_fin').click(function () {
        var fechaHastaIni = $('#fecha_fin').val();
        $('#fecha_fin').change(function () {
        var fechaDesde = $('#fecha_ini').val();
        var fechaHasta = $('#fecha_fin').val();
        f1 = fechaDesde.split("-");
        f2 = fechaHasta.split("-");
        f3 = new Date();
        var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
        var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014

        if (f2 < f1) {
        bootbox.alert("La Fecha Hasta no debe ser menor a la Fecha Desde");
        $('#fecha_fin').val(fechaHastaIni);
        return false;
        }

        /*if (f2 > f3) {
        bootbox.alert("La Fecha Hasta no debe ser mayor a la Fecha Actual");
        $('#fecha_fin').val(fechaHastaIni);
        return false;
        }*/
        });
        });
        $('#fecha_fin').trigger('change');
        $('#fecha_ini').trigger('change');
        var activeSystemClass = $('.list-group-item.active');
        
        
    });



    function fn_ListarBusqueda() {
        datatablenew();
    };

    function guardarAsegurado() {
        var msg = "";
        var sw = true;
        
        if (sw == false) {
            bootbox.alert(msg);
            return sw;
        } else {
            submitFrmAsegurado();
        }
        return false;
    }

    function submitFrmAsegurado() {
        bootbox.confirm({
            message: "Se registrarán el paciente con su historia clinica, ¿Está seguro de continuar?",
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
                    document.frmAsegurado.submit();
                }
            }
        });
    }

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
            message: "Se registrarán las historias seleccionadas, ¿Está seguro de continuar?",
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
                    document.frm_movimiento_hc.submit();
                }
            }
        });
    }
    //-------------------------------------------------------------------------
    //-------------------------------------------------------------------------
    function validateFrm2() {
        var msg = "";
        var sw = true;
        
        if (sw == false) {
            bootbox.alert(msg);
            return sw;
        } else {
            submitFrm2();
        }
        return false;
    }
    function submitFrm2() {
        bootbox.confirm({
            message: "Se registrará la(s) historia(s) ingresadas, ¿Está seguro de continuar?",
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
                    document.frm_movimiento_hc2.submit();
                }
            }
        });
    }
    //-------------------------------------------------------------------------
    var m = 0;

function datatablenew(){
    var oTable1 = $('#tblCita').dataTable({
        "bServerSide": true,
        "sAjaxSource": "/listar_cita_historia",
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
            
            var fecha_ini = $('#fecha_ini').val();
            var fecha_fin = $('#fecha_fin').val();
            var estado = $('#estado').val();
            var id_establecimiento = {{Auth::user()->id_establecimiento}};            
            var id_servicio = $('#id_servicio').val();
            var _token = $('#_token').val();
            oSettings.jqXHR = $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data":{NumeroPagina:iNroPagina,NumeroRegistros:iCantMostrar,
                        fecha_ini:fecha_ini,fecha_fin:fecha_fin,estado:estado,id_establecimiento:id_establecimiento,
                        id_servicio:id_servicio,
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
                    var id="";
                    if(row.id_asegurado_historia!=null)id=row.id_asegurado_historia;
                    var newRow = "<input type='checkbox' value='"+row.id_asegurado_historia+"' name='id_dni_hc[]' codigo='"+id+"' id='movimientos["+m+"].id' onchange=addMovimiento("+row.id_asegurado_historia+",'"+id+"',this) />";
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
                    var dia = "";
                    if(row.dia!= null)dia = row.dia;
                    return dia;
                },
                "bSortable": false,
                "aTargets": [1]
                },
                {
                "mRender": function (data, type, row) {
                    var tipo_doc_ident = "";
                    if(row.tipo_doc_ident!= null)tipo_doc_ident = row.tipo_doc_ident;
                    return tipo_doc_ident;
                },
                "bSortable": false,
                "aTargets": [2]
                },
                {
                "mRender": function (data, type, row) {
                    var nro_doc_ident = "";
                    if(row.nro_doc_ident!= null)nro_doc_ident = row.nro_doc_ident;
                    return nro_doc_ident;
                },
                "bSortable": false,
                "aTargets": [3],
                "className": 'control'
                },
                {
                "mRender": function (data, type, row) {
                    var nro_historia = "";
                    var newRow = "";
                    if(row.nro_historia!= null)newRow += row.nro_historia;
                    return newRow;
                },
                "bSortable": false,
                "aTargets": [4]
                },
                {
                "mRender": function (data, type, row) {
                    var asegurado = "";
                    if(row.asegurado_paterno!= null)asegurado += row.asegurado_paterno;
                    if(row.asegurado_materno!= null)asegurado += " "+row.asegurado_materno;
                    if(row.asegurado_nombre!= null)asegurado += " ,"+row.asegurado_nombre;
                    return asegurado;
                },
                "bSortable": false,
                "aTargets": [5]
                }
            ]
    });
}

function addMovimiento(id,codigo,obj){
        
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

function addMovimientoAll(){
        
    var newRow = "";
    
    if ($("input[name='select_all']").is(':checked')){
        var pht = 0;
        $("input[name='id_dni_hc[]']").each(function(){
            var id = $(this).val().toString();
            var codigo = $(this).attr("codigo").toString();
            newRow ="<td style='padding-right:0px;float:left'><input name='mov' type='hidden' value='"+id+"' /><span class='label alert-warning'>"+codigo+"</span></td>";
            $('#Grd2 tbody tr').append(newRow);
        });
        
    }else{
        var cantht = $('#Grd2 tbody tr td').length;
        if(cantht > 0){
            $("input[name='id_dni_hc[]']").each(function(){
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
    newRow +='<td><div class="col-md-12" style="margin-left:0px;padding-left:0px"> <input type="text" name="dni_hc[]" tabindex="112" required="" id="dni_hc'+ind+'" class="input-sm" style="margin-left:4px; width:100%; text-align:left" /> </div></td>';
    newRow +='<input type="hidden" class="form-control typeahead tt-query id_dni_hc" id="id_dni_hc'+ind+'" name="id_dni_hc[]" value="" data-id='+ind+'></td>';
    newRow +='<td><button tabindex="114" type="button" class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i></button></td>';
    newRow +='</tr>';
    
    $('#tblDni tbody').append(newRow);

    $("#dni_hc"+ind).autocomplete({
        minLength: 1,
        delay : 400,
        source: function(request, response) {
            var id_establecimiento       = $('#id_establecimiento').val();
            $.ajax({
                url: '../../../../obtener_hc_dni/' + request.term +'/' + id_establecimiento,
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

function obtenerBeneficiario() {

    
    var tipodoc_beneficiario = $("#tipodoc_beneficiario_bus").val();
    var dni_beneficiario = $("#dni_beneficiario_bus").val();
    var msg = "";

    if (msg != "") {
      bootbox.alert(msg);
      return false;
    }

    $.ajax({
      url: '/obtener_beneficiario/' + 1 + '/' + tipodoc_beneficiario + '/' + dni_beneficiario,
      dataType: "json",
      success: function(result) {
        if (result.sw == false) {
          bootbox.alert(result.msg);
          $('#beneficiario').val("");
          $('#tipo_beneficiario').val("");
          $('#nombre_beneficiario_hc').val("");
          $('#paterno_beneficiario').val("");
          $('#materno_beneficiario').val("");
          $('#tipodoc_beneficiario').val("");
          $('#cip_beneficiario').val("");
          $('#grado').val("");

          $('#fecnacafiliado').val("");
          $('#nomsexo').val("");
          $('#nro_historia').val("");
          $('#domicilio').val("");
          $('#telefono').val("");
          $('#correo').val("");
          $('#cip_beneficiario').val("");
          return false;
        }

        var beneficiario = result.beneficiario.apepatafiliado + " " + result.beneficiario.apematafiliado + " " +
          result.beneficiario.apecasafiliado + ", " + result.beneficiario.nomafiliado;
        $('#nrodocafiliado').val(result.beneficiario.nrodocafiliado);
        $('#beneficiario').val(beneficiario);
        $('#tipo_beneficiario').val(result.beneficiario.parentesco);
        $('#nombre_beneficiario_hc').val(result.beneficiario.nomafiliado);
        $('#paterno_beneficiario').val(result.beneficiario.apepatafiliado);
        $('#materno_beneficiario').val(result.beneficiario.apematafiliado + " " + result.beneficiario
          .apecasafiliado);
        $('#tipodoc_beneficiario').val(result.beneficiario.nomtipdocafiliado);
        $('#cip_beneficiario').val(result.beneficiario.cip);
        $('#grado').val(result.beneficiario.grado);
        $('#fecnacafiliado').val(result.beneficiario.fecnacafiliado);
        $('#nomsexo').val(result.beneficiario.nomsexo);

        if (result.numero_historia != null) {
          if (result.numero_historia != '')
            $('#nro_historia').val(result.numero_historia);
          else
            $('#nro_historia').val(result.beneficiario.nrodocafiliado);
        } else
          $('#nro_historia').val(result.beneficiario.nrodocafiliado);

        $('#domicilio').val(result.domicilio);
        $('#telefono').val(result.telefono);
        $('#correo').val(result.correo);
        $('#cip_beneficiario').val(result.beneficiario.cip);
        $('#tipodoc_titular').val(result.beneficiario.nomtipdoctitular);
        $('#nrodoctitular').val(result.beneficiario.nrodoctitular);
        $('#nombre_titular').val(result.beneficiario.nomtitular);
        $('#paterno_titular').val(result.beneficiario.apepattitular);
        $('#materno_titular').val(result.beneficiario.apemattitular + " " + result.beneficiario.apecastitular);
        

        var parentesco = result.beneficiario.parentesco;

        id_establecimiento = <?php echo Auth::user()->id_establecimiento; ?>;
      }

    });

  }

  function limpiarBeneficiario() {

    var dni_beneficiario = $('#dni_beneficiario_bus').val();
    var nrodocafiliado = $('#nrodocafiliado').val();
    if (dni_beneficiario != nrodocafiliado) {
      $('#beneficiario').val("");
      $('#tipo_beneficiario').val("");
      $('#nombre_beneficiario_hc').val("");
      $('#paterno_beneficiario').val("");
      $('#materno_beneficiario').val("");
      $('#grado').val("");
      $('#nro_historia').val("");
      $('#tipodoc_beneficiario').val("");
      $('#cip_beneficiario').val("");
      $('#id_admision').val("");
      $('#grado').val("");
    }

    obtenerBeneficiario();

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
                    <li class="active"><a href="/nueva_historia/"><i class="fa fa-table fa-fw"></i> Nueva Historia</a></li>
                    <li><a href="/buscar_movimiento_historia/"><i class="fa fa-table fa-fw"></i> Buscar Historias</a></li>
                </ul>
            </div>
        </div>        
        <div class="col-md-10">
            <div class="panel panel-body">
                <legend style="text-align:center">{{$farmacia->nombre}} <br/> {{$nombre_consultorio->nombre}}</legend>
                    <div class="col-md-12" style="margin-top:15px">
                        <div class="panel with-nav-tabs panel-default">
                            <div class="panel-heading">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab2default" data-toggle="tab" style="font-size:12px">NUEVA HISTORIAS</a></li>
                                </ul>
                            </div>                
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="tab2default">
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <form class="app-form" method="post" action="{{ route('registrar_paciente_historia')}}" id="frmAsegurado" autocomplete="off">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                                                <div class="row">
                                                    <div class="col-md-4 col-sm-4">
                                                        <div class="form-group">
                                                            <label for="tipo">TIPO DOC.</label>
                                                            <select class="form-control input-sm" id="tipodoc_beneficiario_bus" name="tipodoc_beneficiario_bus" tabindex="3">
                                                                <option value="1" selected="">DNI</option>
                                                                <option value="2">CARNET DE EXTRANJERIA</option>
                                                                <option value="3">PASAPORTE</option>
                                                                <option value="4">DOCUMENTO DE IDENTIDAD EXTRANJERO</option>
                                                            </select>
                                                            <input type="hidden" value="" name="tipodoc_beneficiario" id="tipodoc_beneficiario" class="form-control">
                                                            <input type="hidden" value="" name="tipodoc_titular" id="tipodoc_titular" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4">
                                                        <label for="dni_beneficiario_bus">NRO DOC.</label>
                                                        <div class="input-group">
                                                            <input id="dni_beneficiario_bus" name="dni_beneficiario_bus" type="text" placeholder="" class="form-control input-sm text-uppercase"  autofocus="" onblur="limpiarBeneficiario()" tabindex="4" maxlength="12">
                                                            <span class="input-group-btn" style="height:28px!important;">
                                                            <button type="button" id="btnBuscar" onclick="obtenerBeneficiario()" class="btn btn-info" style="height:28px!important;line-height:10px">Buscar</button>
                                                            </span>
                                                        </div>
                                                        <input type="hidden" class="form-control" value="" name="cip_beneficiario" id="cip_beneficiario" readonly="" />
                                                        <input type="hidden" class="form-control" value="" name="nrodocafiliado" id="nrodocafiliado" readonly="" />
                                                        <input type="hidden" class="form-control" value="" name="nrodoctitular" id="nrodoctitular" readonly="" />
                                                    </div>
                                                    <div class="col-md-4 col-sm-4">
                                                        <div class="form-group">
                                                            <label>PARENTESCO</label>
                                                            <input readonly value="" type="text" name="tipo_beneficiario" id="tipo_beneficiario" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label>APELLIDOS Y NOMBRES</label>
                                                            <input readonly="" type="text" class="form-control" value="" name="beneficiario" id="beneficiario" style="text-align:left">
                                                            <input type="hidden" class="form-control" value="" name="nombre_beneficiario_hc" id="nombre_beneficiario_hc" />
                                                            <input type="hidden" class="form-control" value="" name="paterno_beneficiario" id="paterno_beneficiario" />
                                                            <input type="hidden" class="form-control" value="" name="materno_beneficiario" id="materno_beneficiario" />
                                                            <input type="hidden" class="form-control" value="" name="nombre_titular" id="nombre_titular" />
                                                            <input type="hidden" class="form-control" value="" name="paterno_titular" id="paterno_titular" />
                                                            <input type="hidden" class="form-control" value="" name="materno_titular" id="materno_titular" />
                                                            <input type="hidden" class="form-control" value="" name="fecnacafiliado" id="fecnacafiliado" />
                                                            <input type="hidden" class="form-control" value="" name="nomsexo" id="nomsexo" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>GRADO</label>
                                                            <input readonly value="" type="text" name="grado" id="grado" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>HISTORIA CLINICA</label>
                                                            <input value="" type="text" name="nro_historia" id="nro_historia" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>TELÉFONO</label>
                                                            <input value="" type="text" name="telefono" id="telefono" class="form-control" maxlength="9">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>DOMICILIO</label>
                                                            <input value="" type="text" name="domicilio" id="domicilio" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                    <input class="btn btn-success form-control" value="CREAR" type="submit" id="btnAsegurado">
                                            </form>
                                        </div>
                                    </div>
                                </div>
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
