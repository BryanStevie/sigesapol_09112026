@extends('frontend.layouts.master')
@section('renderjs')
<script>
    
    function soloNumeros(e)
        {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)
        }
    function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46))
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }
    
    $consul = $("#frmReceta").find("#id_consultorio");
    $consul.select2();
    /*$consul = $("#frmConsultorio").find("#id_consultorios");
     $consul.select2();*/
     //-------------------AGREGAR PRODUCTOS--------------------------------------    
    function sumarCantidadLotes(){
        trs=$('#tabla_lote tbody').children('tr');
        total=0;
        lote_id=[];
        lote_cantidad=[];
        $(trs).each(function(ii,oo){
            valor=$(oo).find('#cant').val();
            valor_lote_actual=$(oo).find('#lote_actual').val();                       
            valor_actual=$(oo).find('#cant_actual').val();            
            if(parseFloat(valor_actual)<parseFloat(valor)){
                alert("La Cantidad digitada ("+valor+") del lote "+valor_lote_actual+" es mayor a su cantidad actual ("+valor_actual+")");
                return false;
            }
            id=$(oo).find('#id_lote').val();
            if(valor!=0 && valor!=''){
                total+=parseFloat(valor);
                lote_id.push(id);
                lote_cantidad.push(valor);
            }            
        });
        //alert(total);                
        fila_medicamentos=$('#example tbody').children('tr');
        rowIndex=$('#hidden_rowIndex').val();
        fila_medicamento=fila_medicamentos[rowIndex];
        cantidad_prescrita=$(fila_medicamento).find('#nro_med_solictados').val();
        if(total>cantidad_prescrita)
        {
            alert("Error: el Total digitado("+total+") es mayor a la cantidad prescrita("+cantidad_prescrita+")");
            return false;
        }   
        $(fila_medicamento).find('#nro_med_entregados').val(total);
        cadena_lote_id=lote_id.join("||");
        cadena_lote_cantidad=lote_cantidad.join("||");
        $(fila_medicamento).find('#lotes_id').val(cadena_lote_id);
        $(fila_medicamento).find('#lotes_cantidad').val(cadena_lote_cantidad);
        //alert(cadena_lote_id);
        //alert(cadena_lote_cantidad);
        $('#squarespaceModalLotes').modal('hide');
        
    }
    
    function verlotes(cod_producto, id_farmacia, btn){        
        objTr = $(btn).closest("tr");        
        rowIndex = $('#example tbody').children('tr').index(objTr);
        $('#hidden_rowIndex').val(rowIndex);
        codigo_real=objTr.find("#cmbMedicamento").val();
        
        cantidad_prescrita=objTr.find("#nro_med_solictados").val();
        option = {             
                url: '/obtener_lotes_por_codprod_idfarma/' + cod_producto+ '/' + id_farmacia,
                type: 'GET',
                async:false,
                dataType: 'json',
                data: {}
            };             

            $.ajax({
                
                url: '/obtener_lotes_por_idprod_idfarma/' + codigo_real+ '/' + id_farmacia+'/'+cantidad_prescrita,
                success: function(result){
                    $("#tabla_lote tbody").html("");
                    $(result).each(function (ii, oo) {                       
                        $("#tabla_lote tbody").append(
                            '<tr style="height:30px"> \n\
                                <td style="width:15%"><input type="hidden" value="'+ oo.id +'" id="id_lote">' + oo.codigo_producto + '</td>\n\
                                <td style="width:33%">' + oo.nombre_producto + '</td>\n\
                                <td style="width:13%">' + oo.lote + '<input type="hidden" id="lote_actual" name="lote_actual" value="' + oo.lote + '" style="width:90px"></td>\n\
                                <td style="width:12%">' + oo.fecha_vencimiento + '</td>\n\
                                <td style="width:15%">' + oo.cantidad + '<input type="hidden" id="cant_actual" name="cant_actual" value="'+ oo.cantidad +'" style="width:90px"></td>\n\
                                <td style="width:12%"><input onKeyPress="return soloNumeros(event)" type="text" id="cant" name="cant" value="" style="width:90px"></td>\n\
                            </tr>'
                        );
                    });
                    if(cantidad_prescrita>=10){
                    $("#alerta").html("");
                    $("#alerta").append(
                            'Cantidad Prescrita: '+cantidad_prescrita+' unidades.<br>Advertencia: La cantidad prescrita es superior a 10.'
                    );
                    }else{
                        $("#alerta").html("");
                        $("#alerta").append(
                            'Cantidad Prescrita: '+cantidad_prescrita+' unidades.'
                        );                        
                    }
                }
            });
        
        $('#squarespaceModalLotes').modal();
    }
    //--------------------------------------------------------------------------
    $(".chosen-select").chosen();

    
    //--------------------------------------------------------------------------    
</script>
<style>
    .search-form .form-group {
        float: left !important;
        transition: all 0.35s, border-radius 0s;
        width: 32px;
        height: 32px;
        background-color: #fff;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
        border-radius: 25px;
        border: 1px solid #ccc;
        margin-left: 0px;
    }
    .search-form .form-group input.form-control {
        padding-right: 20px;
        border: 0 none;
        background: transparent;
        box-shadow: none;
        display:block;
    }
    .search-form .form-group input.form-control::-webkit-input-placeholder {
        display: none;
    }
    .search-form .form-group input.form-control:-moz-placeholder {
        /* Firefox 18- */
        display: none;
    }
    .search-form .form-group input.form-control::-moz-placeholder {
        /* Firefox 19+ */
        display: none;
    }
    .search-form .form-group input.form-control:-ms-input-placeholder {
        display: none;
    }
    .search-form .form-group:hover,
    .search-form .form-group.hover {
        width: 60px;
        border-radius: 4px 25px 25px 4px;
    }
    .search-form .form-group span.form-control-feedback {
        position: absolute;
        top: -1px;
        right: -2px;
        z-index: 2;
        display: block;
        width: 34px;
        height: 34px;
        line-height: 34px;
        text-align: center;
        color: #3596e0;
        left: initial;
        font-size: 14px;
    }

    .modal-dialog{
        width: 850px !important;
    }    
    
    .table {
        padding: 8px;
        line-height: 1.428571429;
        vertical-align: top;
        border-top: 1px solid #ddd;
    }

    .center {
        margin-top:50px;   
    }

    .modal-header {
        padding-bottom: 5px;
    }

    .modal-footer {
        padding: 0;
    }

    .modal-footer .btn-group button {
        height:40px;
        border-top-left-radius : 0;
        border-top-right-radius : 0;
        border: none;
        border-right: 1px solid #ddd;
    }

    .modal-footer .btn-group:last-child > button {
        border-right: 0;
    }
    td{
        padding-left: 10px;
        padding-right: 10px
    }
    .alert {
    background-position: 2% 7px;
    background-repeat: no-repeat;
    background-size: auto 35px;
    background-color: rgba(0, 0, 0, 0);
    border: 0;
    min-width: auto !important;
    text-align: left;
    padding-left: 68px;
    }
    .alert.alert-danger {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAARVBMVEX////7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0NAQECdhUHYsUKGdEG1l0JLSEBuYkHkukPKU8NPAAAADnRSTlMA4EDAoCAQ8IBgkDDQUFItelQAAAC0SURBVHherZBLDsMwCAVD7Nj5gvPr/Y/a0FpGLTibdnbozQJN829agPZu7/CiuxE8C76+T/hiqgruLbjqh5ip/TljZrb3AQuDKYAIYO09L3TBRq/3EHk4shCDEkZkTqITmdGMLIIO7r8FbzfaiDappSNjIkpZcCqyCDo4WAJYkR8prTp4ADSBUCIX9uPY5epL5AJdyBWDRDYFHCWyCiXBObKwrh+nl4g2TiLVhSXW97g0v/MEHIQbCYeFmYAAAAAASUVORK5CYII=);
    border-top: 1px solid rgba(140, 0, 0, 0.4);
    border-bottom: 1px solid rgba(140, 0, 0, 0.4);
    }
    .alert.alert-info {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAADBklEQVR42u3YyWsTURwHcP8aQUWtWlu7JEbtpngQvIqXevDWm1fXehO12BTErdQiVMFQ8ODBusWkmSa1lVrTxdrG0FA9WJtkljfL19/LTDKMibl0EorkwReSCQ8+fN+bl2G2AdhSqYH+P1CpAS0DfXUI+ufzUCfaoQptYMIxsMhRsE/d0L4/BNR0qcnug4y159CmTkOLdkKNdnBQAaNEjkAZ90EOH4YcOQ51dQQVBRnLN6DHuqDFOqGZGEpbaVDICynkAVvoRUVAxuqQE8PjXC7C/AX64KG0gq3ch6sgQ1zkEBtTph2FY8JeApkYKdgCMdgMPTMP10D64kUOsTDtDsw/27FAIge9b4Y8e8EdELS0jTAh5TF2Ow5Q9l0TDDWNTYOMjSkLQREcEAdGCRfvHdFcLgI1EegQtPXY5kFactAE2JDyGHvvONrhILZ81w3QIxNQCEfw2JvYxpRYKrMdSqNLoNSzPMBGTJwEmz4HuSSGMnkWYrgjv1Rm3jZATQ5vHqT//sgRVhtmI8p0NwADbOF6EUaOX4GiZCHGzhAkD2ogUD20XxNw5baXhRN2G2EvR+QwhqFDme91YJgiQfpyCTamkTAHaQm97p1DauIBR1jxWI14CHMNuqZCmbsKmcKYBLkYQzkA9m3APRA/P6TxrvztbCfYkoOoqgKVydTQ5ZIYMdgKQ91wD8SHvj7JAYXb2T5jmqEkhiEnHnNIESb7po72joCK/Ntray8sRD7UBkVeGoBEIQilwYFRUwFU9HmIrdzjCHtpKNJXP48FqSfIfspe2jf+Sj4P2UOMnOKQfCOE6Ye0eMdqZR+yr/fwc8icXA2QmnzCD7pCI+JiH8SFPt4KYXYjO7YTLDFYPZCensu3QakjzC2I8zcJsguZsR3IvNoOfWO2eiA+tJ9jfI9Q+qEkn1JGoCz1UW5D+/HSnFhNUCqVQjwezyUajSIUChW+02/VB42OjqJMaqCtB5qZmUEgEIDf73eEXxMEofogPtLpNHp6euDz+Xhyn+lauUm1tx81UMXzBw5wT9JYWi53AAAAAElFTkSuQmCC);
    border-top:1px solid rgba(255, 165, 0,0.4);
    border-bottom:1px solid rgba(255, 165, 0,0.4);
    color:  rgb(225, 75, 0);
    }
    .alert.alert-success {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAqFBMVEX////w8PDw8PDw8PDw8PDw8PDw8PDw8PDW1tbn5+ff398PnVjd3d3i4uIPnVjk5OQPnVjg4OAPnVgzqW8PnVgPnVjT09Pw8PDG4NOp1sBjvJHu7u4PmVYqp2ri6+YcomFHsn4bmlzHzsoPnFeuxbl9tJk0n2t/xqSVvqqNzK1ZqYKJuaJVt4c4rHQnnWPl5eXj4+MPm1fe3t6b0bfc3NxBo3TY2NjX19eKj23kAAAAFXRSTlMA8BCwcFCgkP4f3NDnrzCHoM8Qc/Avz3otAAAA9klEQVR4Xq2T2XKDMAwAMRACNGdbyUDus/d9/f+f1VHEiFamfcm+7qIZGTs4IWEcGeswURz6dGIbJCrp0MeC6QTBCADO2Hetokv+vNXbyvnLAc9v8cA+NH4/veAFEr/f7HIe0OJvETMKYhLlLw8fiNinICKxnHHCfr92QY8CcwwAloX41cFjSoElCnBU89pvkeCAeAPH4or89Bp1YG+g5uUOPQEP5wWbgZzjPRDiU1mTmD/wggyvGVspZgCP4rGvjrpYuAWFTP+sounzwPO7n8SnNEBdmGesGZLUV+6V/ZilLt7F+6/95BMxHf71cCZfefbf0zsl3w3QOjVy6QFpAAAAAElFTkSuQmCC);
    border-top:1px solid limegreen;
    border-bottom:1px solid limegreen;
    }
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-3 col-md-3">
            <div class="panel-group" id="accordion">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-th">
                                </span>Atención de Recetas</a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <table class="table">
                                @role('Dispensador_recetas')
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-plus text-primary"></span>
                                        <a href="/nueva_receta/{{ Auth::user()->id }}/{{ Auth::user()->id_farmacia }}">Nueva Receta</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-search text-primary"></span>
                                        <a href="/buscar_receta/{{ Auth::user()->id }}"> Buscar Receta</a>
                                    </td>
                                </tr> 
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-list text-primary"></span>
                                        <a href="/recetas_atendidas/{{ Auth::user()->id }}"> Receta atendidas</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-list text-primary"></span>
                                        <a href="/recetas_invalidadas/{{ Auth::user()->id_farmacia }}/{{ Auth::user()->id }}"> Recetas Invalidadas</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-tasks text-primary"></span>
                                        <a href="/resumen_diario/{{ Auth::user()->id }}"> Resumen Diario</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-arrow-left text-primary"></span>
                                        <a href="/stocksbyFamarcia/{{ Auth::user()->id }}"> Ir al Stock</a>
                                    </td>
                                </tr>
                                @endauth
                                @role('Almacenero_recetas')
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-search text-primary"></span>
                                        <a href="/all_recetas_atendidas_by_farmacia/{{ Auth::user()->id }}">Recetas atendidas</a>
                                    </td>
                                </tr>
                                @endauth
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-9 col-md-9">
            <div class="panel panel-primary">                
                <div class="panel-heading">
                    <h2 class="panel-title">RECETA - {{$farmacia->nombre}}</h2>                    
                </div>     
                @if(count($recetas)>0)                                
                <div class="panel-body" style="padding-top:30px; font-size: 13px">   
                    @if(count($medicamentos)>0)
                    <div class="col-md-3">
                        <img src="../../images/logo.png" width="120" height="auto">
                    </div>
                    <div class="col-md-7" style="text-align: center">
                        <h4>{{$recetas[0]->ipress}}</h4>                            
                    </div>
                    <div class="col-md-2">
                        <label>Nro RECETA</label>                        
                        <p>{{$recetas[0]->nro_receta}}</p>
                    </div>
                    <div class="col-md-2">
                        <label>DNI</label>
                        <p>{{$recetas[0]->dni_beneficiario}}</p>

                    </div>
                    <div class="col-md-5">
                        <label>NOMBRE Y APELLIDOS</label>                        
                        <p>{{$recetas[0]->nombre_beneficiario}} {{$recetas[0]->paterno_beneficiario}} {{$recetas[0]->materno_beneficiario}}</p>
                    </div>
                    <div class="col-md-3">
                        <label>FECHA ATENCIÓN</label>                        
                        <p>                            
                            <?php
                            $originalDate2 = $recetas[0]->fecha_registro;
                            $fecha = date("d-m-Y", strtotime($originalDate2));
                            echo $fecha;
                            ?>
                            @if($recetas[0]->estado==1)
                            <?php
                            if ($fecha >= '19-12-2016') {
                                ?>
                                <a data-toggle="modal" data-target="#squarespaceModal2">
                                    <i class="fa fa-edit"></i> <!--<span style="font-size:10px">Editar</span>-->
                                </a>
                                <?php
                            }
                            ?>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-2">
                        <label>BENEFICIARIO</label>                        
                        <p>{{$recetas[0]->tipo_beneficiario}}</p>
                    </div>                    
                    @if($recetas[0]->tipo_receta=="CIRUGIA")
                    <div class="col-md-3">
                        <label>CONSULTORIO</label>                        
                        <p>
                            {{$recetas[0]->consultorio}}
                            @if($recetas[0]->estado==1)
                            <a data-toggle="modal" data-target="#squarespaceModal3">
                                <i class="fa fa-edit"></i> 
                            </a>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-3">
                        <label>TIPO DE RECETA</label>                        
                        <p>
                            {{$recetas[0]->tipo_receta}}                            
                        </p>
                    </div>
                    <div class="col-md-3">
                        <label>FECHA DE INTERVENCION</label>                        
                        <p>
                            {{$recetas[0]->fecha_intervencion}}                            
                        </p>
                    </div>
                    <div class="col-md-3">
                        <label>NRO. HISTORIA</label>                        
                        <p>
                            {{$recetas[0]->nro_historia}}                            
                        </p>
                    </div>                    
                    <div class="col-md-12" style="margin-bottom: 10px; width: 100%">
                        <table border="1px" style="background-color:white !important">
                            <tbody>
                                <tr>
                                    <td colspan="3" class="td_left" style="text-align: center; font-weight: bold; background-color: #ECF5FF">DIAGNOSTICOS</td>
                                </tr>
                                <tr>
                                    <td class="td_left td_ancho_n" style="text-align: center; font-weight: bold; background-color: #ECF5FF">N</td>
                                    <td class="td_left td_ancho_codigo" style="text-align: center; font-weight: bold; background-color: #ECF5FF">CODIGO</td>
                                    <td class="td_left" style="text-align: center; font-weight: bold; background-color: #ECF5FF">DESCRIPCION</td>
                                </tr>
                                <tr>
                                    <td class="td_left td_ancho_n">1</td>
                                    <td class="td_left">{{$recetas[0]->cie}}</td>
                                    <td class="td_left">{{$recetas[0]->diagnostico}}</td>
                                </tr>
                                <?php $n = 1; ?>
                                @foreach($diagnosticos as $d)
                                <tr>
                                    <td class="td_left td_ancho_n"><?php $n = $n + 1; echo $n; ?></td>
                                    <td class="td_left">{{$d->codigo}}</td>
                                    <td class="td_left">{{$d->nombre}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>                     
                    </div>
                    <div class="col-md-12" style="margin-bottom: 10px; width: 100%">                        
                        <table border="1px" style="background-color:white !important">
                            <tbody>
                                <tr>
                                    <td colspan="3" class="td_left" style="text-align: center; font-weight: bold; background-color: #ECF5FF">PROCEDIMIENTOS</td>
                                </tr>
                                <tr>
                                    <td class="td_left td_ancho_n" style="text-align: center; font-weight: bold; background-color: #ECF5FF">N</td>
                                    <td class="td_left td_ancho_codigo" style="text-align: center; font-weight: bold; background-color: #ECF5FF">CODIGO</td>
                                    <td class="td_left" style="text-align: center; font-weight: bold; background-color: #ECF5FF">DESCRIPCION</td>
                                </tr>
                                <?php $n = 0; ?>
                                @foreach($procedimientos as $p)
                                <tr>
                                    <td class="td_left td_ancho_n"><?php $n = $n + 1;   echo $n; ?></td>
                                    <td class="td_left">{{$p->codigo}}</td>
                                    <td class="td_left">{{$p->descripcion}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>                      
                    </div>
                    <div class="col-md-12" style="margin-bottom: 10px">
                        <!----------------------------------------------------->
                        <table border="1px" style="background-color:white !important; width: 100%" >
                            <tbody>
                                <tr>
                                    <td colspan="4" class="td_left" style="text-align: center; font-weight: bold; background-color: #ECF5FF  ">PROFESIONALES DE LA SALUD</td>
                                </tr>
                                <tr>
                                    <td class="td_left td_ancho_n" style="text-align: center; font-weight: bold; background-color: #ECF5FF">N</td>
                                    <td class="td_left td_ancho_codigo" style="text-align: center; font-weight: bold; background-color: #ECF5FF">DNI</td>
                                    <td class="td_left" style="text-align: center; font-weight: bold; background-color: #ECF5FF">NOMBRE Y APELLIDOS</td>
                                    <td class="td_left td_ancho_codigo" style="text-align: center; font-weight: bold; background-color: #ECF5FF">ESPECIALIDAD</td>
                                </tr>
                                <tr>
                                    <td class="td_left td_ancho_n">1</td>
                                    <td class="td_left td_ancho_codigo">{{$recetas[0]->dni_medico}}</td>
                                    <td class="td_left">
                                        {{$recetas[0]->nombre_medico}} {{$recetas[0]->paterno_medico}} {{$recetas[0]->materno_medico}}
                                        <a href="/cambiar_medico_principal/{{$recetas[0]->id}}"><i class="fa fa-edit fa-1x"></i></a>
                                    </td>
                                    <td class="td_left td_ancho_especialidad">{{$recetas[0]->especializacion}}</td>
                                </tr>
                                <?php $n = 1; ?>
                                @foreach($cirujanos as $cir)
                                <tr>
                                    <td class="td_left td_ancho_n"><?php $n = $n + 1; echo $n; ?></td>
                                    <td class="td_left td_ancho_codigo">
                                        {{$cir->dni}}                                        
                                    </td>
                                    <td class="td_left">
                                        {{$cir->nombre}} {{$cir->paterno}} {{$cir->materno}} 
                                        <a href="/cambiar_profesional_salud/{{$cir->id_receta_medico}}"><i class="fa fa-edit fa-1x"></i></a>
                                    </td>
                                    <td class="td_left td_ancho_especialidad">{{$cir->nombre_especialidad}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!----------------------------------------------------->
                    </div>
                    @else
                    <div class="col-md-3">
                        <label>CONSULTORIO</label>                        
                        <p>
                            {{$recetas[0]->consultorio}}
                            @if($recetas[0]->estado==1)
                            <a data-toggle="modal" data-target="#squarespaceModal3">
                                <i class="fa fa-edit"></i> 
                            </a>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-3">
                        <label>TIPO DE RECETA</label>                        
                        <p>
                            {{$recetas[0]->tipo_receta}}                            
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label>PROFESIONAL PRESCRIPTOR</label>                        
                        <p>{{$recetas[0]->nombre_medico}} {{$recetas[0]->paterno_medico}} {{$recetas[0]->materno_medico}}
                            @if($recetas[0]->estado==1)
                            <a data-toggle="modal" data-target="#squarespaceModal4">
                                <i class="fa fa-edit"></i> 
                            </a>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-12">
                        <label>DIAGNOSTICO</label>                        
                        <p>{{$recetas[0]->cie}} - {{$recetas[0]->diagnostico}}</p>
                    </div>                                                                                        
                    @endif     
                    <!-----Invalidación invalidada ----------------------------
                    @if($rol_responsable==1 && $rol_dispensador==1)
                    @if($recetas[0]->estado==1) 
                    <div class="col-md-12" style="margin-bottom:10px">
                        <a data-toggle="modal" data-target="#squarespaceModal" class="btn btn-danger">
                            <i class="fa fa-xing"></i>Invalidar Receta (Permiso-Reponsable Farmacia)
                        </a>                          
                    </div>
                    @else
                    <div class="col-md-12">
                        <button class="btn btn-danger" disabled="">
                            Registro de Receta invalidada
                        </button>
                    </div>
                    <div class="col-md-8" style="border: 1px solid red; margin-top: 10px; background-color: #FFDA6A">
                        Motivo: {{$receta_devuelta[0]->motivo}}<br>
                        Fecha de Invalidación: {{$receta_devuelta[0]->fecha_devolucion}}<br>
                        Usuario: {{$receta_devuelta[0]->email}}
                    </div>
                    @endif
                    @endif

                    @if($rol_responsable==1 && $rol_dispensador==0)
                    @if($recetas[0]->estado==1) 
                    <div class="col-md-12" style="margin-bottom:10px">
                        <a data-toggle="modal" data-target="#squarespaceModal" class="btn btn-danger">
                            <i class="fa fa-xing"></i>Invalidar Receta (Permiso-Reponsable Farmacia)
                        </a>                          
                    </div>
                    @else
                    <div class="col-md-12">
                        <button class="btn btn-danger" disabled="">
                            Registro de Receta invalidada
                        </button>
                    </div>
                    <div class="col-md-8" style="border: 1px solid red; margin-top: 10px; background-color: #FFDA6A">
                        Motivo: {{$receta_devuelta[0]->motivo}}<br>
                        Fecha de Invalidación: {{$receta_devuelta[0]->fecha_devolucion}}<br>
                        Usuario: {{$receta_devuelta[0]->email}}
                    </div>
                    @endif
                    @endif

                    @if($rol_responsable==0 && $rol_dispensador==1)
                    @if($recetas[0]->estado==1)    
                    <?php
                    $datatime = $recetas[0]->fecha_registro;
                    $fecha_registro_receta = date("d-m-Y", strtotime($datatime));
                    ?>
                    @if($fecha_actual==$fecha_registro_receta)
                    <div class="col-md-3">                        
                        <a data-toggle="modal" data-target="#squarespaceModal" class="btn btn-danger">
                            <i class="fa fa-xing"></i>Invalidar Receta 
                        </a>
                    </div>
                    @else
                    <div class="col-md-3">
                        <button class="btn btn-danger" disabled="">
                            Invalidar Receta
                        </button>
                    </div>
                    @endif
                    @else
                    <div class="col-md-12">
                        <button class="btn btn-danger" disabled="">
                            Registro de Receta invalidada
                        </button>
                    </div>
                    <div class="col-md-8" style="border: 1px solid red; margin-top: 10px; background-color: #FFDA6A">
                        Motivo: {{$receta_devuelta[0]->motivo}}<br>
                        Fecha de Invalidación: {{$receta_devuelta[0]->fecha_devolucion}}<br>
                        Usuario: {{$receta_devuelta[0]->email}}
                    </div>
                    @endif
                    @endif-->
                    <form  id="frmDispensar" class="form-horizontal" method="post" action="{{ route('dispensar_producto_cirugia')}}" id="frmReceta" style="font-size:10px;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="col-md-12" style="text-align: left; font-size: 18px">
                                    PRESCRIPCION 
                                </div>                                                    
                            <div class="row col-md-12" style="padding-top:10px; padding-bottom:10px; border: 1px solid gray; margin-bottom: 20px; margin-left: 5px">                                
                                <input type="hidden" value="0" name="hidden_rowIndex" id="hidden_rowIndex">
                                <input type="hidden" value="{{$recetas[0]->id}}" name="id_receta_vale"> 
                                <input type="hidden" value="{{$recetas[0]->nro_receta}}" name="nro_receta">
                                <input type="hidden" value="{{$recetas[0]->id_farmacia}}" name="id_farmacia">
                                <input type="hidden" value="{{$recetas[0]->fecha_expedicion}}" name="fecha_expedicion">
                                <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                                <table id="example" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Producto farmacéutico o dispositivo médico</th>
                                            <th>Cantidad Prescrita</th>
                                            <th>Cantidad Usada</th>
                                            <th>Add</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                        @foreach($medicamentos as $med)                                        
                                        <tr>
                                            <td>                                                
                                                <input type="text" class="form-control" name="" id="medicina" value="{{$med->codigo}} - {{$med->nombre}} - {{$med->abreviatura}}">                                                
                                                <input type="hidden" class="form-control" name="id_producto[]" id="cmbMedicamento" value="{{$med->id_producto}}">
                                                <input type="hidden" name="lotes_id[]" id="lotes_id" value="">
                                                <input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value="">
                                                <input type="hidden" name="id_producto_receta[]" id="id_producto_receta" value="{{$med->id_producto_receta}}">                                                
                                            </td>
                                            <td>
                                                <input type="text" name="nro_med_solictados[]" required id="nro_med_solictados" class="form-control" style="margin-left:4px; width:100px" value="{{$med->cantidad_prescrita}}" onKeyPress="return soloNumeros(event)" readonly=""/>
                                                
                                            </td>                                            
                                            <td>
                                                <input type="text" name="nro_med_entregados[]" required id="nro_med_entregados" class="form-control" style="margin-left:4px; width:100px" readonly="" value="{{$med->cantidad_dispensada}}" onKeyPress="return soloNumeros(event)"/>                                                
                                            </td>
                                            <td>
                                                <button data-toggle="modal" class="btn btn-success btn-xs" type="button" onclick="verlotes({{$med->codigo}},{{$recetas[0]->id_farmacia}},this)">Add</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>  
                        @if($verdispensado>0)
                        <div class="col-md-12" style="text-align: right; margin-bottom: 10px">
                            <i class="fa fa-info-circle fa-2x"></i> Productos dispensados</div>
                        @else
                        <div class="col-md-12" style="text-align: right; margin-bottom: 10px">
                            <button type="submit" class="btn btn-primary btn-xs"><i class="fa fa-save"> Dispensar</i></button>
                        </div>    
                        @endif
                        <!----------------------------------------------------------------------------->
                        @if($recetas[0]->estado==0)
                        <div class="col-md-12" style="border-top: 1px solid gray; padding-top: 10px">                                                  
                            Receta Invalidada
                        </div>
                        @else
                        @if($recetas[0]->tipo_receta!="CIRUGIA")
                        <div class="col-md-12" style="border-top: 1px solid gray; padding-top: 10px">                                                       
                            <a href="/imprimir_receta/{{$recetas[0]->nro_receta}}/{{$recetas[0]->id_farmacia}}/Hola" class="btn btn-danger pull-right" target="_blank">
                                Generar Ticket <i class="fa fa-file-pdf-o"></i>
                            </a>                                                      
                        </div> 
                        @else
                        <div class="col-md-9" style="border-top: 1px solid gray; padding-top: 10px; text-align: right">                                                                                 
                            <a href="/imprimir_receta_cirugia/{{$recetas[0]->id}}/{{$recetas[0]->id_farmacia}}" class="btn btn-default btn-xs pull-right" target="_blank">
                                <i class="fa fa-print"></i> Imprimir receta
                            </a>                            
                        </div>
                        <div class="col-md-3" style="border-top: 1px solid gray; padding-top: 10px"> 
                            <!--<button class="btn btn-default btn-xs pull-right" disabled="" ><i class="fa fa-print"></i> Imprimir receta valorizado</button>-->
                            <a href="/imprimir_receta_cirugia_valorizado/{{$recetas[0]->id}}/{{$recetas[0]->id_farmacia}}" class="btn btn-default btn-xs pull-right" target="_blank">
                                <i class="fa fa-print"></i> Imprimir receta valorizado
                            </a>                            
                        </div>
                        @endif
                        @endif
                    </form> 
                    @endif
                </div>
                @else
                <div class="panel-body" style="padding-top:10px; font-size: 13px">
                    No se encuentra el numero de receta que digito.
                </div>
                @endif
            </div>         
        </div>
    </div>
</div>
@if(count($recetas)>0) 
<!-- line modal -->
<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title" id="lineModalLabel">Invalidar Receta</h3>
            </div>
            <div class="modal-body">

                <!-- content goes here -->
                <form class="" method="post" action="{{ route('invalidar_receta')}}" id="">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Escribir el motivo para invalidar el registro de esta receta</label>
                        <input type="text" class="form-control" id="motivo" name="motivo" placeholder="" required="">
                        <input type="hidden" class="form-control" id="id_farmacia" name="id_farmacia" value="{{$recetas[0]->id_farmacia}}">
                        <input type="hidden" class="form-control" id="nro_receta" name="nro_receta" value="{{$recetas[0]->nro_receta}}">
                        <input type="hidden" class="form-control" id="id_receta" name="id_receta" value="{{$recetas[0]->id}}">
                        <!--Se añadio la fecha -->
                        <input type="hidden" class="form-control" id="fecha_receta_origen" name="fecha_receta_origen" value="{{$recetas[0]->fecha_registro}}">                        
                        <!-- fin -->
                        <input type="hidden" class="form-control" id="id_user" name="id_user" value="{{ Auth::user()->id }}">
                    </div>                    
                    <div class="form-group">
                        <input id="btnInvalidar" class="btn btn-danger" type="submit" value="Invalidar">                        
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="squarespaceModal2" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title" id="lineModalLabel">Editar Fecha</h3>
            </div>
            <div class="modal-body">

                <!-- content goes here -->
                <form class="" method="post" action="{{ route('editar_fecha_receta')}}" id="">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">       
                        <?php
                        $data2 = $recetas[0]->fecha_registro;
                        $fecha2 = date("d-m-Y", strtotime($data2));
                        ?>
                        <input type="text" class="form-control datepicker" id="fecha_atencion" name="fecha_atencion" value="<?php echo $fecha2; ?>" required="">
                        <input type="hidden" class="form-control" id="id_receta" name="id_receta" value="{{$recetas[0]->id}}">
                        <input type="hidden" class="form-control" id="nro_receta" name="nro_receta" value="{{$recetas[0]->nro_receta}}">
                        <input type="hidden" class="form-control" id="id_user" name="id_user" value="{{ Auth::user()->id }}">
                        <input type="hidden" class="form-control" id="id_farmacia" name="id_farmacia" value="{{$recetas[0]->id_farmacia}}">
                    </div>                    
                    <div class="form-group">
                        <input id="btnInvalidar" class="btn btn-primary" type="submit" value="Actualizar">                        
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="squarespaceModal3" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title" id="lineModalLabel">Editar Consultorio</h3>
            </div>
            <div class="modal-body">

                <!-- content goes here -->
                <form class="" method="post" action="{{ route('editar_consultorio_receta')}}" id="frmConsultorio">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">      
                        <label>Consultorio actual</label>
                        <input type="text" class="form-control" id="consultorio" name="consultorio" value="{{$recetas[0]->consultorio}}" readonly="">
                        <label style="margin-top:10px">Nuevo Consultorio</label><br>
                        <select name="id_consultorios" id="id_consultorios" class="form-control" style="" required="">
                            <option value="">Seleccionar</option>
                            @foreach($consultorios as $c)
                            <option value="{{$c->id}}">{{$c->nombre}}</option>
                            @endforeach
                        </select>                        
                        <input type="hidden" class="form-control" id="id_receta" name="id_receta" value="{{$recetas[0]->id}}">
                        <input type="hidden" class="form-control" id="nro_receta" name="nro_receta" value="{{$recetas[0]->nro_receta}}">
                        <input type="hidden" class="form-control" id="id_user" name="id_user" value="{{ Auth::user()->id }}">
                    </div>                    
                    <div class="form-group">
                        <input id="btnInvalidar" class="btn btn-primary" type="submit" value="Actualizar">                        
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="squarespaceModal4" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title" id="lineModalLabel">Editar Profesional de Salud Prescriptor</h3>
            </div>
            <div class="modal-body">

                <!-- content goes here -->
                <form class="" method="post" action="{{ route('editar_medico_receta')}}" id="frmConsultorio">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">      
                        <label>Prescriptor actual</label>
                        <input type="text" class="form-control" id="medico" name="medico" value="{{$recetas[0]->nombre_medico}} {{$recetas[0]->paterno_medico}} {{$recetas[0]->materno_medico}}" readonly="">
                        <label style="margin-top:10px">Nuevo Prescriptor</label><br>
                        <select name="id_medico" id="id_medico" class="form-control" style="" required="">
                            <option value="">Seleccionar</option>
                            @foreach($medicos as $m)
                            <option value="{{$m->id}}">{{$m->dni}} - {{$m->nombre}} {{$m->paterno}} {{$m->materno}}</option>
                            @endforeach
                        </select>                        
                        <input type="hidden" class="form-control" id="id_receta" name="id_receta" value="{{$recetas[0]->id}}">
                        <input type="hidden" class="form-control" id="nro_receta" name="nro_receta" value="{{$recetas[0]->nro_receta}}">
                        <input type="hidden" class="form-control" id="id_user" name="id_user" value="{{ Auth::user()->id }}">
                    </div>                    
                    <div class="form-group">
                        <input id="btnInvalidar" class="btn btn-primary" type="submit" value="Actualizar">                        
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

@endif

<div class="modal fade" id="squarespaceModalLotes" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" style="">
    <div class="modal-dialog">
        <div class="modal-content">            
            <form method="post" action="{{ route('reasignar_lotes')}}" id="frmlotizar">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-header" id="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="lineModalLabel">Dispensar</h4>
                    <div id="alerta" class="alert alert-danger"></div>
                </div>
                <div class="modal-body">
                    <table border="0px" id="tabla_lote">
                        <thead>
                            <tr>
                                <th style="width:15%">Código</th>   
                                <th style="width:33%">Nombre</th> 
                                <th style="width:13%">Lote</th> 
                                <th style="width:12%">Fec. ven.</th> 
                                <th style="width:15%">Cant. Actual.</th> 
                                <th style="width:12%">Cant. Entregar</th>
                            </tr>
                        </thead>
                        <tbody>
                         
                        </tbody>                        
                    </table>
                </div>
                <div class="modal-footer">
                    <div class="btn-group btn-group-justified" role="group" aria-label="group button">                        
                        <div class="btn-group" role="group">
                            <button type="button" onclick="sumarCantidadLotes()" id="saveImage" class="btn btn-success btn-hover-green" data-action="save" role="button">Agregar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection