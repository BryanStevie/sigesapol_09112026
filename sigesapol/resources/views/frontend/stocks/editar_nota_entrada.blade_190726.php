@extends('frontend.layouts.master')
@section('renderjs')
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}
<script>
    
    function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46) || (keynum == 45))
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }

    function getCargaCampos(valor){

        var concepto  = $('#concepto').val();


        if(concepto==1){

            $('#nota_entrada').attr('readonly', false); 
            $('#nota_entrada').val(''); 
            getSiaf();
            getRB();
            getProceso();            
            getNroProceso();            
            getCG();
            getFactura();
            getProveedor();            
            $id_proveedor = $("#frm_transferencia").find("#id_proveedor");
            $id_proveedor.select2();
            $('#id_proveedor').attr('required', true); 
            $('#f_r').attr('required', true); 
            $('#tipo_proceso').attr('required', true); 
            $('#siaf').attr('required', true); 
            $('#c_g').attr('required', true); 
            $('#nota_entrada').attr('required', true); 
            
        }else
        { 
                //ELIMINAR
                
                $('#proveedor_label').remove();                
                $('#id_proveedor').select2('destroy');
                $('#id_proveedor').remove();
                $('#siaf_label').remove();
                $('#siaf').remove();            
                $('#fecha_destino_label').remove();
                $('#fecha_destino').remove();                
                $('#factura_label').remove();
                $('#factura').remove();
                $('#rb_label').remove();
                $('#f_r').select2('destroy');
                $('#f_r').remove();
                $('#cg_label').remove();
                $('#c_g').select2('destroy');
                $('#c_g').remove();
                $('#tipo_proceso_label').remove();
                $('#tipo_proceso').select2('destroy');
                $('#tipo_proceso').remove();
                $('#nro_proceso').remove();
                $('#nro_proceso_label').remove();
                
                if(concepto==7){    
                    $('#nota_entrada').attr('readonly', false); 
                    $('#nota_entrada').val(''); 
                }
                else{
                    getnronotaautomatico();
                }
        }
    }

    function getnronotaautomatico() {
        id_farmacia=46;
        $concepto = $("#frm_transferencia").find("concepto");
        
        $.ajax({
            url: '/cargarnronota/'+ id_farmacia,
            dataType: "text",
            success : function (data) {                
                if($concepto != 1 || $concepto != 7)  {
                  $('#nota_entrada').val(data); 
                  $('#nota_entrada').attr('readonly', true);     
                }
            }
        });    
    }

    function getSiaf(){
        var label = document.createElement("label"); 
        var text = document.createTextNode("SIAF"); 
          
          label.setAttribute("name", "siaf_label");
          label.setAttribute("id", "siaf_label");
          label.appendChild(text);
        $('#cargasiaf').append(label);

        var text = document.createElement("input");
           text.setAttribute("onkeypress", "return soloNumeros(event)");
           text.setAttribute("id", "siaf");    
           text.setAttribute("name", "siaf");           
           text.setAttribute("tabindex", "5");           
           text.setAttribute("class", "form-control");
           text.setAttribute("type", "text");          
                          
        $('#cargasiaf').append(text);
        
    }

    
    function getFactura(){
        var label = document.createElement("label"); 
        var text = document.createTextNode("Factura"); 
          
          label.setAttribute("name", "factura_label");
          label.setAttribute("id", "factura_label");
          label.appendChild(text);
        $('#cargafactura').append(label);

        var text = document.createElement("input");
           text.setAttribute("name", "factura");
           text.setAttribute("type", "text");
           text.setAttribute("class", "form-control");
           text.setAttribute("id", "factura");
           text.setAttribute("tabindex", "6");           
        $('#cargafactura').append(text);

        text.setAttribute("name", "factura");
           
           
        
    }
    
    function getProveedor(){

        var label = document.createElement("label"); 
        var text = document.createTextNode("Proveedor"); 
        label.setAttribute("name", "proveedor_label");
        label.setAttribute("id", "proveedor_label");
        label.appendChild(text);        
        $('#cargaproveedor').append(label);

        var select = document.createElement("select");
        select.setAttribute("name", "id_proveedor");
        select.setAttribute("class", "form-control select2");
        select.setAttribute("id", "id_proveedor"); 
        select.setAttribute("tabindex", "7");           
        select.style.width = "100%";            
        $('#cargaproveedor').append(select);


        $.ajax({
            url: '/cargaproveedor/',
            success: function (result) {
                var newOption = "";
                newOption = "<option value=''>--Seleccionar--</option>";  
                $(result).each(function (ii, oo) {
                    newOption += '<option value="'+oo.id+'">'+oo.ruc_proveedor+ ' - ' +oo.nombre_proveedor+'</option>';
                });
                $('#id_proveedor').html(newOption)
            }
        });
    }

        function getFechaDestino(){
        var label = document.createElement("label"); 
        var text = document.createTextNode("Fecha Destino"); 
          
        label.setAttribute("name", "fecha_destino_label");
        label.setAttribute("id", "fecha_destino_label");
        label.appendChild(text);
        $('#cargafechadestino').append(label);

        var text = document.createElement("input");
        text.setAttribute("name", "fecha_destino");
        text.setAttribute("type", "text");
        text.setAttribute("tabindex", "8");           
        text.setAttribute("class", "form-control datepicker");
        text.setAttribute("id", "fecha_destino");        
            
        $('#cargafechadestino').append(text);
        
    }    


    function getProceso(){        

        var label = document.createElement("label"); 
        var text = document.createTextNode("T/Proceso"); 
        label.setAttribute("name", "tipo_proceso_label");
        label.setAttribute("id", "tipo_proceso_label");

        label.appendChild(text);        
        $('#cargartipoproceso').append(label);

        var select = document.createElement("select");
        select.setAttribute("name", "tipo_proceso");
        select.setAttribute("class", "form-control select2");
        select.setAttribute("id", "tipo_proceso");
        select.setAttribute("tabindex", "9");           
        select.style.width = "100%";            
        $('#cargartipoproceso').append(select);

        $.ajax({
            url: '/cargartipoproceso/',
            success: function (result) {
                var newOption = "";
                newOption = "<option value=''>--Seleccionar--</option>";  
                $(result).each(function (ii, oo) {
                    newOption += '<option value="'+oo.id+'">'+oo.abreviatura_tipo_proceso+ ' - ' +oo.descripcion_tipo_proceso+'</option>';
                });
                $('#tipo_proceso').html(newOption)
            }
        });

        $tipo_proceso = $("#frm_transferencia").find("#tipo_proceso");
        $tipo_proceso.select2();
    }


    
    function getNroProceso(){
        var label = document.createElement("label"); 
        var text = document.createTextNode("Nro Proceso"); 
          
          label.setAttribute("name", "nro_proceso_label");
          label.setAttribute("id", "nro_proceso_label");
          label.appendChild(text);
        $('#carganro_proceso').append(label);

        var text = document.createElement("input");
           text.setAttribute("name", "nro_proceso");
           text.setAttribute("class", "form-control");
           text.setAttribute("type", "text");
           text.setAttribute("tabindex", "10");           
           text.setAttribute("id", "nro_proceso");
        $('#carganro_proceso').append(text);
    }
    

    function getRB(){        

        var label = document.createElement("label"); 
        var text = document.createTextNode("FF/RB"); 
        label.setAttribute("name", "rb_label");
        label.setAttribute("id", "rb_label");
        label.appendChild(text);        
        $('#cargarrb').append(label);

        var select = document.createElement("select");
        select.setAttribute("name", "f_r");
        select.setAttribute("class", "form-control select2");
        select.setAttribute("id", "f_r");        
        select.setAttribute("tabindex", "11");           
        select.style.width = "100%";            
        $('#cargarrb').append(select);

        
        var newOption = "";
        newOption = "<option value=''>--Seleccionar--</option>";              
        newOption += "<option value='1'>00-RO</option>";  
        newOption += "<option value='2'>09-RDR</option>";  
        $('#f_r').html(newOption);

        $f_r = $("#frm_transferencia").find("#f_r");
        $f_r.select2();

    }

    
    function getCG(){        

        var label = document.createElement("label"); 
        var text = document.createTextNode("C. G."); 
        label.setAttribute("name", "cg_label");
          label.setAttribute("id", "cg_label");

        label.appendChild(text);        
        $('#cargacg').append(label);

        var select = document.createElement("select");
        select.setAttribute("name", "c_g");
        select.setAttribute("class", "form-control select2");
        select.setAttribute("id", "c_g");
        select.setAttribute("tabindex", "12");           
        select.style.width = "100%";            
        $('#cargacg').append(select);

        $.ajax({
            url: '/cargarcg/',
            success: function (result) {
                var newOption = "";
                newOption = "<option value=''>--Seleccionar--</option>";  
                $(result).each(function (ii, oo) {
                    newOption += '<option value="'+oo.id+'">'+oo.descripcion_cg+'</option>';
                });
                $('#c_g').html(newOption)
            }
        });

        $c_g = $("#frm_transferencia").find("#c_g");
        $c_g.select2();

    }
    
    
    
    
    function soloNumeros(e)
        {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8 )
        }

    function soloNumeros2(e)
        {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8 || key == 45 )
        }
    
    $cmbconcepto = $("#frm_transferencia").find("#concepto");
    $cmbconcepto.select2();
    $cmbid_id_lugar_origen = $("#frm_transferencia").find("#id_lugar_origen");
    $cmbid_id_lugar_origen.select2();
    $cmbid_proveedor = $("#frm_transferencia").find("#id_proveedor");
    $cmbid_proveedor.select2();
    $cmbid_id_c_g = $("#frm_transferencia").find("#c_g");
    $cmbid_id_c_g.select2();
    $cmbid_f_r= $("#frm_transferencia").find("#f_r");
    $cmbid_f_r.select2();
    $cmbid_id_c_g = $("#frm_transferencia").find("#c_g");
    $cmbid_id_c_g.select2();
    $cmbid_id_tipo_proceso = $("#frm_transferencia").find("#tipo_proceso");
    $cmbid_id_tipo_proceso.select2();

        /**/
        $("#nota_entrada").on("keydown", function (e) {
            nro_nota_entrada=$('#frm_transferencia').find("#nota_entrada").val();
            id_farmacia=46;
            var code = e.keyCode || e.which;
            if (code == '9' || code == '13') {
                validar(nro_nota_entrada, id_farmacia,1);
            }
        });

        $("#siaf").on("keydown", function (e) {
            
            siaf=$('#frm_transferencia').find("#siaf").val();
            
            id_farmacia=46;
            var code = e.keyCode || e.which;
            if (code == '9' || code == '13') {
                validar(siaf, id_farmacia,2);
            }
            
        });

        function validar(nro, id_farmacia, tipo){
            id_guia=$('#frm_transferencia').find("#id").val();
            concepto=$('#frm_transferencia').find("#concepto").val();

            
            if(concepto==1){
                option = {                
                url: '/evitar_duplicados_edicion/' + nro + '/' + tipo +  '/' + id_farmacia + '/' + id_guia, 
                type: 'GET',
                async:false,
                dataType: 'json',
                data: {}
                };
                $.ajax(option).done(function (data) {
                    existe=data; 
                    if(tipo==1){
                        texto=' de la Nota de entrada ';
                    }else{
                        texto=' de SIAF ';
                    }
                    
                    if(existe>0){
                        bootbox.confirm({
                             message: "Aviso: El numero: " + nro + texto + " se encuentra actualmente registrada. ¿Desea Modificar?.",
                             className: 'title-dialog',
                             buttons: {
                                 confirm: {
                                     label: 'Si',
                                     className: 'btn-success btn-lg'
                                 },
                                 cancel: {
                                     label: 'No',
                                     className: 'btn-danger btn-lg'
                                 }
                             },
                             callback: function (result) {
                                 if (result==false) {
                                     $('#frm_transferencia').find("#nota_entrada").val("");
                                     $('#nota_entrada').focus();
                                 }
                             }
                         });
                    }
                });     
            }
            
        }
    

        /**/

        function validateFrm(){
            concepto=$('#frm_transferencia').find("#concepto").val();

            if(concepto==1){
                option = {                
                    url: '/evitar_duplicados/' + nro + '/' + tipo +  '/' + id_farmacia, 
                    type: 'GET',
                    async:false,
                    dataType: 'json',
                    data: {}
                };

                $.ajax(option).done(function (data) {
                    existe=data; 
                    if(existe>0){
                        $('#frm_transferencia').find("#nota_entrada").focus();
                        bootbox.alert("El numero: " + nota_entrada+ " se encuentra actualmente registrada");
                        $('#btnReg').prop('disabled', false);
                        return false;
                    } else {
                        validate_otros();
                       return false;
                    }
                });     
            }
        }

    
    

        


    
</script>
<style>
    .etiqueta{margin-top: 15px}
    .panel_medicamentos{border: 1px solid gray; padding-top: 10px}
</style>
@endsection
@section('content')
<?php 
    if($pecosa->estado<=2):
        $disabled = "";
        $title = "EDITAR";
        $msg = " Modificar Nota de Entrada de Productos Farmacéuticos ";
    else:
        $disabled = "disabled='disabled'";
        $title = "VER";
        $msg = "Ver Nota de Enrada Culminada de Productos Farmacéuticos";
    endif;
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">REGISTRAR</h3>                    
                    <a href="/all_notas_entradas/{{ Auth::user()->id_farmacia }}" class="btn btn-default pull-right">
                        <i class="fa fa-list"></i> Listado de Nota Entrada
                    </a>
                </div>
                <div class="panel-body">
                    <form id="frm_transferencia" method="POST" action="{{ route('editar_documento_nota_entrada')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <!------- Fila 01--------------------------------------->
                        <div class="row">
                            
                                <input name="nro_nota_entrada" value="<?php echo $pecosa->nota_entrada?>" class="form-control" type="hidden">
                                <input name="id_farmacia" value="{{ $id_farmacia }}" class="form-control" type="hidden"> 
                                <input name="id_user" value="{{ Auth::user()->id }}" class="form-control" type="hidden"> 
                                <input name="id" value="{{ $pecosa->id }}" id="id" class="form-control" type="hidden"> 
                                <input name="fecha_hace_treinta_dias" id="fecha_hace_treinta_dias" value="{{ $fecha_hace_treinta_dias }}" class="form-control" type="hidden"> 
                                <input type="hidden" name="ano_actual" value="<?php echo date("Y") ?>" id="ano_actual" class="form-control">
                            
                            <div class="col-md-2 etiqueta">
                                    <label>FEC. REGISTRO (R)</label>
                                    <input type="text" class="form-control" value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_registro" id="fecha_registro"  readonly=""/>
                            </div>
                            <div class="col-md-6 etiqueta">
                                <label>Registrador </label>
                                <input readonly="" name="responsable_origen" value="{{ Auth::user()->paterno }} {{ Auth::user()->materno }}, {{ Auth::user()->name }}" required="" class="form-control" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                            </div>
                            <div class="col-md-4 etiqueta">
                                <label>Lugar Origen</label>
                                <select name="id_lugar_origen" autofocus  tabindex="1" id="id_lugar_origen" class="form-control" required="">
                                    <?php $idOrigen = $pecosa->lugar_origen;?>
                                    <option value="" disabled selected style="color: #888888">Seleccionar uno</option>
                                    @foreach($dependencia as $dep)
                                    <?php $idO = $dep->id;?>
                                        <option value="{{$dep->id}}" <?php if($idOrigen == $idO) {echo " selected";}?>>{{$dep->nombre_dependencia}}</option>
                                        
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <!------- Fila 02--------------------------------------->
                        <div class="row">
                            <div class="col-md-4 etiqueta">
                                <label>Concepto</label>
                                <select class="form-control" required="" tabindex="2" onchange="getCargaCampos('<?php echo $pecosa->nota_entrada; ?>');" name="concepto" id="concepto">
                                    <?php $idConcepto = $pecosa->concepto;?>
                                    <option value="" disabled selected style="color: #888888">- Seleccione Concepto -</option>
                                    @foreach($concepto as $con)
                                    <?php $idCo = $con->id;?>
                                        <option value="{{$con->id}}" <?php if($idConcepto == $idCo) {echo " selected";}?>>{{$con->descripcion_conceptos}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 etiqueta">
                                <label>Fecha Origen</label>
                                <input name="fecha_entrada"  tabindex="3" value="<?php echo date("d-m-Y", strtotime($pecosa->fecha_entrada)); ?>" id="fecha_entrada" class="form-control datepicker" required="" <?php echo $disabled ?>>
                            </div>
                            <div class="col-md-2 etiqueta">
                                <label>Numero Documento</label>
                                <?php $Concepto = $pecosa->concepto;?>
                                <input  <?php if($Concepto == 1 && $Concepto == 7) echo 'readonly=""';?>

                                tabindex="4" onKeyPress="return soloNumeros2(event)" id="nota_entrada" name="nota_entrada" required="" value="<?php echo $pecosa->nota_entrada; ?>" class="form-control" type="text" style="text-transform:uppercase;">
                            </div>
                            <div class="col-md-4 etiqueta">
                            
                                <label id="referencia_label">REFERENCIA</label>
                                <input  id="referencia" name="referencia" required="" tabindex="8" value="<?php echo $pecosa->referencia; ?>" class="form-control" type="text" style="text-transform:uppercase;">
                            
                            </div>

                            
                        </div>
                        <!---------------------------------------------------------------------------------------->
                        <div class="row">
                            <div class="col-md-6 etiqueta" id="cargaproveedor">
                            @if($pecosa->concepto==1)
                                <label id="proveedor_label">PROVEEDOR</label>
                                <select class="form-control" required="" tabindex="7" name="id_proveedor" id="id_proveedor">
                                <?php $idProveedor = $pecosa->proveedor_id;?>
                                    <option value="" disabled selected style="color: #888888">- Seleccione Proveedor -</option>
                                    @foreach($proveedores as $prov)
                                    <?php $idProv = $prov->id;?>
                                        <option value="{{$prov->id}}" <?php if($idProv == $idProveedor) {echo " selected";}?>>{{$prov->nombre_proveedor}}</option>
                                    @endforeach
                                </select>
                            @endif
                            </div>
                            <div class="col-md-2 etiqueta" id="cargasiaf">
                            @if($pecosa->concepto==1)
                                <label id="siaf_label">SIAF</label>
                                <input  onKeyPress="return soloNumeros(event)" id="siaf" name="siaf" required="" tabindex="5" value="<?php echo $pecosa->siaf; ?>" class="form-control" type="text" style="text-transform:uppercase;">
                            @endif
                            </div>
                            <div class="col-md-2 etiqueta" id="cargafactura">
                            @if($pecosa->concepto==1)
                                <label id="factura_label">FACTURA</label>
                                <input  id="factura" name="factura" required="" tabindex="6" value="<?php echo $pecosa->factura; ?>" class="form-control" type="text" style="text-transform:uppercase;">
                            @endif
                            </div>                            
                            
                            
                            <div class="col-md-2 etiqueta" id="cargafechadestino">
                            @if($pecosa->concepto==11)
                                <label id="fecha_destino_label">FECHA DESTINO</label>
                                <input name="fecha_destino" value="<?php echo date("d-m-Y", strtotime($pecosa->fecha_doc_recepcion)); ?>"  tabindex="8" id="fecha_destino" class="form-control datepicker" required="" <?php echo $disabled ?>>
                            @endif
                            </div>                                    
                        </div>
                        <div class="row">
                            <div class="col-md-4 etiqueta" id="cargartipoproceso">
                            @if($pecosa->concepto==1)
                                <label id="tipo_proceso_label">T/Proceso</label>
                                <select class="form-control" required="" tabindex="9" name="tipo_proceso" id="tipo_proceso">
                                <?php $idTipo = $pecosa->tipo_proceso;?>
                                    <option value="" disabled selected style="color: #888888">- Seleccione -</option>
                                    @foreach($tipo_procesos as $tp)
                                    <?php $idTp = $tp->id;?>
                                        <option value="{{$tp->id}}" <?php if($idTp == $idTipo) {echo " selected";}?>>{{$tp->descripcion_tipo_proceso}}</option>
                                    @endforeach
                                </select>
                            @endif
                            </div>

                            <div class="col-md-4 etiqueta" id="carganro_proceso">
                            @if($pecosa->concepto==1)
                                <label id="nro_proceso_label">Nro Proceso</label>
                                <input  id="nro_proceso" name="nro_proceso" required="" tabindex="10" value="<?php echo $pecosa->nro_proceso; ?>" class="form-control" type="text" style="text-transform:uppercase;">
                            @endif
                            </div>
                            <div class="col-md-2 etiqueta" id="cargarrb">
                            @if($pecosa->concepto==1)
                                <label id="rb_label">FF/RB</label>
                                <select class="form-control" required="" tabindex="11" name="f_r" id="f_r">
                                <?php $idFRS = $pecosa->ff_rb;?>
                                    <option value="" disabled selected style="color: #888888">- Seleccione -</option>
                                        <option value='1' <?php if($idFRS == '1') {echo " selected";}?>>00-RO</option>
                                        <option value='2' <?php if($idFRS == '2') {echo " selected";}?>>09-RDR</option>     
                                </select>             
                            @endif                  
                            </div>
                            <div class="col-md-2 etiqueta" id="cargacg">
                            @if($pecosa->concepto==1)
                                <label id="cg_label">C.G.</label>
                                <select class="form-control" required="" tabindex="12" name="c_g" id="c_g">
                                <?php $idCGS = $pecosa->c_g;?>
                                    <option value="" disabled selected style="color: #888888">- Seleccione -</option>
                                    @foreach($cgs as $cg)
                                    <?php $idcg = $cg->id;?>
                                        <option value="{{$cg->id}}" <?php if($idCGS == $idcg) {echo " selected";}?>>{{$cg->descripcion_cg}}</option>
                                    @endforeach
                                </select>
                            @endif
                            </div>
                        </div>
                        <!-------------------------------------------------------------------------------------------->                        
                        <div class="clearfix" style="margin-bottom:15px"></div>
                        <!--div class="col-md-6" style="margin-top:10px">  
                            <label>Observaci&oacute;n</label>
                            <textarea id="observacion" name="observacion" class="form-control input-md" ></textarea>
                        </div-->
                        <a href="/print_nota_entrada/<?php echo $pecosa->id ?>" class="btn btn-danger pull-right" target="_blank">
                            Imprimir<i class="fa fa-file-pdf-o"></i>
                        </a>

                        <div class="col-md-3 pull-right">
                            <input tabindex="13"     class="btn btn-success form-control" value="Editar" type="submit" id="btnReg" onclick="validateFrm()">
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection