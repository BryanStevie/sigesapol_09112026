@extends('frontend.layouts.master')
@section('renderjs')
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}
<style>
.title-dialog {
  font-size: 18px;
  font-family:Arial, Helvetica, sans-serif;
}
    
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
  font-family:Arial, Helvetica, sans-serif;
  text-align: left;
  background-color: #ffffff;
  border: 1px solid #cccccc;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: 4px;
  -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
  background-clip: padding-box;
}

.ui-autocomplete > li > div {
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
  color: #FFFFFF!important;
  background: #1d71b8!important;
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

input[readonly]{
    background-color:#eeeeee;
    text-align: center
}


span:focus,
textarea:focus,
input[type="text"]:focus,
input[type="password"]:focus,
input[type="datetime"]:focus,
input[type="datetime-local"]:focus,
input[type="date"]:focus,
input[type="month"]:focus,
input[type="time"]:focus,
input[type="week"]:focus,
input[type="number"]:focus,
input[type="email"]:focus,
input[type="url"]:focus,
input[type="search"]:focus,
input[type="tel"]:focus,
input[type="color"]:focus,
.uneditable-input:focus{   
  border-color: #2196F3;
  box-shadow: 0 1px 1px #2196F3 inset, 0 0 8px #2196F3;
  outline: 0 none;
}


#contenido{
  
}


</style>

<script>

  function __autocomplete__AjaxBusqueda(){
    
    $("#nombre_diagnostico").autocomplete({
    minLength: 1,
    delay : 400,
    source: function(request, response) {
    $.ajax({
      url: '/obtener_diagnostico/' + request.term,
      dataType: "json",
      success: function(data)  {
      response(data);
      }
      
    })
    
      },select:  function(e, ui) {
        var id_diagnostico = ui.item.id;
        $('#id_diagnostico').val(id_diagnostico);
      }
    });
    
    
  }


    function justNumbers(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46) && (keynum == 9))
            return true;
        return /\d/.test(String.fromCharCode(keynum));
    }
    
    function soloNumeros(e)
        {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)
        }
        
    function soloNumerosMenosCero(e)
        {
            var key = window.Event ? e.which : e.keyCode;
            //alert(key);
            return ((key >= 48 && key <= 57) || key == 0 || key == 8)       
        }
    
    $consul = $("#frmReceta").find("#id_consultorio");
    //$consul.select2();
    $consul.select2({
        width: 'resolve' // need to override the changed default
    });

    $tipo_receta = $("#frmReceta").find("#tipo_receta");
    //$consul.select2();
    $tipo_receta.select2({
        width: 'resolve' // need to override the changed default
    });
    
    $tipo_vias = $("#frmReceta").find("#tipo_via");
    //$consul.select2();
    $tipo_vias.select2({
        width: 'resolve' // need to override the changed default
    });
    

    $medicos = $('#frmReceta').find("#id_medico")
    //$medicos.select2();
    $medicos.select2({
        width: 'resolve' // need to override the changed default
    });

 
    //$tipo_receta = $('#frmReceta').find("#tipo_receta")
    //$tipo_receta.select2();
  /*
    $tipo_receta.select2({
        width: 'resolve' // need to override the changed default
    });
    */
  /*
  .on("select2:close", function(e) {
        var nextId = getNextFocusableFieldId(jQuery(this).attr('id'));
        jQuery('#' + nextId).focus().select();
    })
  */
  
  jQuery(document).on('focus', '.select2', function() {
      jQuery(this).siblings('select').select2('open');
  });


// return the id of the next focusable field
function getNextFocusableFieldId(idIn) {
    var focusables = jQuery("input, select, textarea");
    var reachedId = false;
    var id = '';
    var nextId = '';
    jQuery.each(focusables, function(index, value) {
        id = jQuery(this).attr('id');
        // if we reached the id last time set the nextId and exit each
        if (reachedId) {
            nextId = id;
            return false;
        }
        // if the ids match set the flag for the next iteration
        if (id == idIn) {
            reachedId = true;
        }
    });
    return nextId;
}

  
  
    function sumarCantidadLotes(opc){
        
        trs=$('#tabla_lote tbody').children('tr');                
        total=0;
        lote_lote=[];
  lote_registro_sanitario=[];
        lote_precio_unitario=[];
  lote_fecha_vencimiento=[];
        lote_cantidad=[];
        $(trs).each(function(ii,oo){  
            valor=$(oo).find('#cant').val(); 
            valor_lote_actual=$(oo).find('#lote_actual').val();                       
            valor_actual=$(oo).find('#cant_actual').val();            
            if(parseFloat(valor_actual)<parseFloat(valor)){
                //alert("La Cantidad digitada ("+valor+") del lote "+valor_lote_actual+" es mayor a su cantidad actual ("+valor_actual+")");
    bootbox.alert("La Cantidad digitada ("+valor+") del lote "+valor_lote_actual+" es mayor a su cantidad actual ("+valor_actual+")");
                return false;
            }
            lote=$(oo).find('#lote').val();
            registro_sanitario=$(oo).find('#registro_sanitario').val();
            precio_unitario=$(oo).find('#precio_unitario').val();
            fecha_vencimiento=$(oo).find('#fecha_vencimiento').val();
            lote=$(oo).find('#lote').val();
            if(valor!=0 && valor!=''){
                total+=parseFloat(valor);
                lote_lote.push(lote);
    lote_registro_sanitario.push(registro_sanitario);
                lote_precio_unitario.push(precio_unitario);
    lote_fecha_vencimiento.push(fecha_vencimiento);
                lote_cantidad.push(valor);
            }            
        });
        //alert(total);                
        fila_medicamentos=$('#example tbody').children('tr');
        rowIndex=$('#hidden_rowIndex').val();
        fila_medicamento=fila_medicamentos[rowIndex];
        cantidad_prescrita=$(fila_medicamento).find('.nro_med_solictados').val();
        if(total>cantidad_prescrita)
        {
            xx=total;
            total=cantidad_prescrita;
            cantidad_prescrita=xx;
            //alert("Error: el Total digitado("+total+") es mayor a la cantidad prescrita("+cantidad_prescrita+")");
      //bootbox.alert("Error: el Total digitado("+total+") es mayor a la cantidad prescrita("+cantidad_prescrita+")");
        //    return false;
        }        
        $(fila_medicamento).find('#nro_med_entregados').val(total);
        cadena_lote_lote=lote_lote.join("||");
        cadena_lote_registro_sanitario=lote_registro_sanitario.join("||");
        cadena_lote_precio_unitario=lote_precio_unitario.join("||");
  cadena_lote_fecha_vencimiento=lote_fecha_vencimiento.join("||");
  cadena_lote_cantidad=lote_cantidad.join("||");
    
    
        $(fila_medicamento).find('#lotes_lote').val(cadena_lote_lote);
  $(fila_medicamento).find('#lotes_registro_sanitario').val(cadena_lote_registro_sanitario);
        $(fila_medicamento).find('#lotes_precio_unitario').val(cadena_lote_precio_unitario);
  $(fila_medicamento).find('#lotes_fecha_vencimiento').val(cadena_lote_fecha_vencimiento);
        $(fila_medicamento).find('#lotes_cantidad').val(cadena_lote_cantidad);
        //alert(cadena_lote_lote);
        //alert(cadena_lote_cantidad);
        $('#squarespaceModal').modal('hide');
    $(fila_medicamento).find('.nro_med_solictados').focus();
    if(opc==1)AddFila();
    setTimeout(function(){$(fila_medicamento).find('.nro_med_solictados').focus();}, 10);
    
    }

function verlotes(id_farmacia, btn){
  objTr = $(btn).closest("tr");        
  rowIndex = $('#example tbody').children('tr').index(objTr);
  $('#hidden_rowIndex').val(rowIndex);
  codigo=objTr.find(".cmbMedicamento").val();
  cantidad_prescrita=objTr.find(".nro_med_solictados").val();
  if(cantidad_prescrita==""){
    bootbox.alert('Es necesario digitar la cantidad prescrita'); return false;
  }
  if(cantidad_prescrita==0){
    bootbox.alert('La Cantidad prescrita debe ser mayor a cero'); return false;
  }

  tiempo_periodo=objTr.find(".tiempo_periodo").val();
  if(tiempo_periodo==""){
    bootbox.alert('Es necesario digitar el Tiempo(Días)'); return false;
  }
  <?php if(!isset($diasDiferencia)){?>
          //var fecha_atencion = $('#fecha_atencion').val();
          //var dayName = $.datepicker.formatDate('DD', fecha_atencion); 
          //alert("fecha_atencion"+ dayName);
          var fecha_atencion = new Date();
          if(fecha_atencion.getDay() == 6){
            if(tiempo_periodo>30){
                bootbox.alert('El periodo de tiempo debe ser 30 Días'); return false;
              }
          }
          else
          {
            if(fecha_atencion.getDay() !=0)
            {
              if(tiempo_periodo>30){
                bootbox.alert('El periodo de tiempo debe ser un Día'); return false;
              }
            }
          }
          
  <?php }  ?>
          
  cantidad_stock_actual=objTr.find("#nro_stocks").val();
  analisis=cantidad_stock_actual-cantidad_prescrita;
  if(cantidad_stock_actual==0){ // Aqui no toco nada 
    $("#alerta").html("");
    $("#alerta").append(
        'El Producto no contiene stock. Dar click en cualquier <b style="color:green">botón</b> para continuar con la atención.'
    );
    $.ajax({
      url: '/obtener_lotes_por_idprod_idfarma/' + codigo+ '/' + {{Auth::user()->id_farmacia}} +'/'+cantidad_prescrita, 
      async: false,
      success: function(result){
        $("#tabla_lote tbody").html("");
        $(result).each(function (ii, oo) {                       
          $("#tabla_lote tbody").append(
            '<tr style="height:30px"> \n\
                <td style="width:15%"><input type="hidden" value="'+ oo.lote +'" id="lote"><input type="hidden" value="'+ oo.registro_sanitario +'" id="registro_sanitario"><input type="hidden" value="'+ oo.precio_unitario +'" id="precio_unitario"><input type="hidden" value="'+ oo.fecha_vencimiento +'" id="fecha_vencimiento">' + oo.codigo_producto + '</td>\n\
                <td style="width:33%">' + oo.prod_farmaceutico + '</td>\n\
                <td style="width:13%">' + oo.lote + '<input type="hidden" id="lote_actual" name="lote_actual" value="' + oo.lote + '" style="width:90px"></td>\n\
                <td style="width:13%">' + oo.registro_sanitario + '</td>\n\
                <td style="width:12%">' + oo.precio_unitario + '</td>\n\
                <td style="width:12%">' + oo.fecha_vencimiento + '</td>\n\
                <td style="width:15%">' + oo.cantidad + '<input type="hidden" id="cant_actual" name="cant_actual" value="'+ oo.cantidad +'" style="width:90px"></td>\n\
                <td style="width:12%"><input onKeyPress="return soloNumeros(event)" type="text" id="cant" name="cant" value="" style="width:90px" class="text-center"></td>\n\
            </tr>'
          );
        });                                         
      },
      complete: function () {
          setTimeout(function(){sumarCantidadLotes(1)}, 150);
      }
    });
  }else{//Aqui si tengo sacar las sumas empecemos
    $.ajax({
      url: '/obtener_lotes_por_idprod_idfarma/' + codigo+ '/' + {{Auth::user()->id_farmacia}}+'/'+cantidad_prescrita, 
      async: false,
      success: function(result){                    
        $("#tabla_lote tbody").html("");
        var cntxlote = 0;
        var canting = 0;
        var cantactu = 0;
        cntxlote = cantidad_prescrita;
        $(result).each(function (ii, oo) {
          if(cntxlote != 0){
            cantactu = oo.cantidad;
            if (cantactu >= cntxlote){
              canting = cntxlote;
              cntxlote = 0;
            } else {
              canting = cantactu;
              cntxlote = cntxlote - cantactu;
            }
          } else {
              canting = 0;
          }
          $("#tabla_lote tbody").append(
            '<tr style="height:30px"> \n\
              <td style="width:15%"><input type="hidden" value="'+ oo.lote +'" id="lote"><input type="hidden" value="'+ oo.registro_sanitario +'" id="registro_sanitario"><input type="hidden" value="'+ oo.precio_unitario +'" id="precio_unitario"><input type="hidden" value="'+ oo.fecha_vencimiento +'" id="fecha_vencimiento">' + oo.codigo_producto + '</td>\n\
              <td style="width:33%">' + oo.prod_farmaceutico + '</td>\n\
              <td style="width:13%">' + oo.lote + '<input type="hidden" id="lote_actual" name="lote_actual" value="' + oo.lote + '" style="width:90px"></td>\n\
              <td style="width:13%">' + oo.registro_sanitario + '</td>\n\
                                                                    <td style="width:12%">' + oo.precio_unitario + '</td>\n\
              <td style="width:12%">' + oo.fecha_vencimiento + '</td>\n\
              <td style="width:15%">' + oo.cantidad + '<input type="hidden" id="cant_actual" name="cant_actual" value="' + oo.cantidad + '" style="width:90px"></td>\n\
              <td style="width:12%"><input onKeyPress="return soloNumeros(event)" type="text" id="cant" name="cant" value="'+canting+'" style="width:90px"></td>\n\
            </tr>'
          );
        });
        if(cantidad_prescrita>=10){
          $("#alerta").html("");
          $("#alerta").append(
            //'Cantidad Prescrita: '+cantidad_prescrita+' unidades.<br>Advertencia: La cantidad prescrita es superior a 10.'--Wilian
            'Cantidad Prescrita: '+cantidad_prescrita+' unidad(es).'
          );
        }else{
          $("#alerta").html("");
          $("#alerta").append(
            'Cantidad Prescrita: '+cantidad_prescrita+' unidad(es).'
          );                        
        }
      },
      complete: function () {
          setTimeout(function(){sumarCantidadLotes(1)}, 150);         
      }
    });
  }
} 
  

  
     
    //-------------------AGREGAR PRODUCTOS--------------------------------------
    $(document).ready(function () {
    
    __autocomplete__AjaxBusqueda();
    //$('[data-toggle="tooltip"]').tooltip(); 
    
    var counter = 1;

    $('#example tbody').on('click', 'button.deleteFila', function () {
        var obj = $(this);
        obj.parent().parent().remove();
    });

    
    $('#addRow').on('click', function () {
      AddFila();
    });

    
    <?php if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
    $("#frmReceta").submit(function (e) {//Jose
      $.ajax({
        url: '/validacion_beneficiario_dias_productos/', 
        type:'POST',
        data:$("#frmReceta").serialize(),
        context: this,
        success: function(result){
          var msg   = "";
          var n     = 0;
          var flag  = true;
          for(var key in result) {
            if(n == 0){
              msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:42px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario "+(result[key].paterno_beneficiario)+" "+(result[key].materno_beneficiario)+", "+(result[key].nombre_beneficiario)+" con DNI "+(result[key].dni_beneficiario)+" tiene receta menor a 30 dias</h1>";              
              msg += "<table class='table table-list-search' style='font-size: 11px !important;' >";
                          msg += "<thead>";
                            msg += "<tr>";
              msg += "<th>Farmacia</th>";
              msg += "<th>Nro. Receta</th>";
              msg += "<th>Fecha Expedicion</th>";
              msg += "<th>Producto</th>";
              msg += "<th>Tiempo(Dias)</th>";
              msg += "<th>Dias Transcurridos</th>";
              msg += "<th>Dias Pendientes</th>";
              msg += "<th>Cantidad Prescrita</th>";
              msg += "<th>Cantidad Entregada</th>";
                            msg += "</tr>";
                          msg += "</thead>";
              msg += "<tbody>";
            }
            
            msg += "<tr><td>"+(result[key].farmacia)+"</td>";
            msg += "<td>"+(result[key].nro_receta)+"</td>";
            msg += "<td>"+(result[key].fecha_expedicion)+"</td>";
            msg += "<td>"+(result[key].codigo)+" - "+(result[key].producto)+"</td>";
            msg += "<td>"+(result[key].tiempo_periodo)+"</td>";
            msg += "<td>"+(result[key].dias_transcurridos)+"</td>";
            msg += "<td>"+(result[key].tiempo_periodo - result[key].dias_transcurridos)+"</td>";
            msg += "<td>"+(result[key].cantidad_prescrita)+"</td>";
            msg += "<td>"+(result[key].cantidad_dispensada)+"</td></tr>";
            n++;
          }
          if($.trim(msg) == ""){
            this.submit();
          }else{
            this.submit();
          }
        }
      });
      return false;            
    });
    <?php endif;?>
    
    <?php if(!isset($flag_receta_dias) || (isset($flag_receta_dias) && $flag_receta_dias == "")):?>
    $("#frmReceta").submit(function (e) {
      rows = $("#example tbody").children('tr');            
      var i=0;
      $.each(rows, function (ii, oo) {                          
        medicina = $(oo).find(".cmbMedicamento").select2("data");
        i=i+1;
        nuevaCantidad = parseFloat($(oo).find("#nro_med_entregados").val());
        cantidadPrescrita = parseFloat($(oo).find("#nro_med_solictados").val());
        stockActual = parseFloat($(oo).find("#nro_stocks").val());

        if(cantidadPrescrita==0){
            alert("La medicina: " +medicina[0].text+ ", tiene que tener cantidad prescrita mayor a cero");
            e.preventDefault();
            return false;                     
        }else{                 
            if (stockActual == 0) {                    
                if (nuevaCantidad != 0) {                           
                    alert("La medicina: " +medicina[0].text+ ", tiene que ser cantidad cero");
                    e.preventDefault();
                    return false;
                }
            } else {
                if (nuevaCantidad > stockActual) {
                    alert("La medicina: " + medicina[0].text + ", la cantidad no tiene que ser mayor que el stock");                        
                    e.preventDefault();
                    return false;
                } else if (nuevaCantidad > cantidadPrescrita) {
                    alert("La medicina: " + medicina[0].text + ", la cantidad no tiene que ser mayor que la prescrita");                        
                    e.preventDefault();
                    return false;
                }
            }                        
        }
      });
    });
    <?php endif;?>
    
    $id_diagnostico = $('#frmReceta').find("#id_diagnostico");
    $cies = $('#frmReceta').find("#cies");
    $cies.change(function () {
        $this = $(this);           
        cie = $this.val();
        option = {
            url: 'buscar_diagnostico/' + cie,
            type: 'GET',
            dataType: 'json',
            data: {}
        };
        $.ajax(option).done(function (data) {
            cargarComboUbigeo($id_diagnostico, data);

        });
    });
        
    $dispensado = $('#frmReceta').find("#nro_med_solictados");
      $dispensado.change(function () {
        $this = $(this);           
        dispensado = $this.val();
        if(dispensado==0){
          bootbox.alert("La cantidad prescrita no puede ser cero"); exit;
            return false;
        }
      });        
    });
    

    function cargarComboUbigeo($select, data) {
        if((data.length)>0){
            $select.html('');
            $(data).each(function (ii, oo) {
                $select.append('<option value="' + oo.id + '">'+oo.codigo  +' - ' + oo.nombre + '</option>')
            });
            $('#frmReceta').find("#id_medico").focus();
        }else{
            bootbox.alert('Codigo Cie10 que digitó no es correcto, intente nuevamente');
            $('#frmReceta').find("#cies").val("");
            $('#frmReceta').find("#cies").focus();
            $select.html('');
        }
    }
    //-------------------------------------------------------------------------
 
    $("#nro_receta").on("keydown", function (e) {
        nro_receta='P-'+$('#frmReceta').find("#nro_receta").val()+'-'+$('#frmReceta').find("#digitos_adicionales").val();
        id_farmacia=$('#frmReceta').find("#id_farmacia").val();
        //alert(id_farmacia);
        var code = e.keyCode || e.which;
        if (code == '9' || code == '13') {
            var flagfiscalizador = "<?php echo $flagfiscalizador?>";
            if(flagfiscalizador != 1){
                validar(nro_receta, id_farmacia);
            } else {
                validar_receta_fisanulada(nro_receta, id_farmacia);
                validar(nro_receta, id_farmacia);
            }
        }
    });
    
    $("#nro_historia").on("keydown", function (e) {
        id_farmacia=$('#frmReceta').find("#id_farmacia").val();
        nro_documento=$('#frmReceta').find("#dni_beneficiario").val();
        nro_historia=$('#frmReceta').find("#nro_historia").val();
        var code = e.keyCode || e.which;
        if (code == '9' || code == '13') {
            validar_hc_duplicada(id_farmacia, nro_documento, nro_historia);
        }
    });

    function validar_hc_duplicada(id_farmacia, nro_documento, nro_historia){
            if(nro_historia == "") return false;
            option = {              
                url: '/validar_hc_duplicada/' + id_farmacia+ '/' + nro_documento+ '/' + nro_historia,
                type: 'GET',
                async:false,
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                existe=data;
                if(existe>0){
                    nro_historia=$('#frmReceta').find("#nro_historia").val();
                    bootbox.alert("El Número de Historia Clínica: " + nro_historia + " se encuentra actualmente registrado con otro Beneficiario");
                }
            });     
        }


    //--------------------------------------------------------------------------
    
    function validar(nro_receta, id_farmacia){
            var consultorio = $("#id_consultorio option:selected").text();
            option = {                
                url: '/evitar_duplicados_rectas/' + nro_receta+ '/' + id_farmacia,
                type: 'GET',
                async:false,
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                existe=data; 
                if(existe>0){
                    /*$('#frmReceta').find("#digitos_adicionales").val("");
                    $('#frmReceta').find("#nro_receta").val("");
                    bootbox.alert("La receta: " + nro_receta+ " se encuentra actualmente registrada");
                    */
                    bootbox.confirm({
                         message: "Aviso: La receta: " + nro_receta+ " se encuentra actualmente registrada. ¿Desea Continuar?.",
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
                             if (result==true) {
                                 genera_receta_duplicado();
                             } else {
                                 $('#frmReceta').find("#nro_receta").val("");
                                 $('#nro_receta').focus();
                             }
                         }
                     });
                }
            });     
        }
        
    function validar_receta_fisanulada(nro_receta, id_farmacia){
            option = {
                url: '/evitar_registro_recetas_anuladas_by_fiscalizador/' + nro_receta+ '/' + id_farmacia,
                type: 'GET',
                async:false,
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                existe=data; 
                if(existe==0){
                    $('#frmReceta').find("#digitos_adicionales").val("");
                    $('#frmReceta').find("#nro_receta").val("");
                    $('#frmReceta').find("#nro_receta").focus();
                    //alert("La receta: " + nro_receta+ " se encuentra actualmente registrada");
                    bootbox.alert("La receta: " + nro_receta+ " no fue anulada por su persona o no existe dicho número");
                    e.preventDefault();
                }
            });     
        }

    //-------------------------------------------------------------
    function validar_rec(nro_receta, id_farmacia){
        var consultorio = $("#id_consultorio option:selected").text();
        option = {                
            url: '/evitar_duplicados_rectas/' + nro_receta+ '/' + id_farmacia,
            type: 'GET',
            async:false,
            dataType: 'json',
            data: {}
        };
        $.ajax(option).done(function (data) {
            existe=data; 
            if(existe>0){
                $('#frmReceta').find("#nro_receta").focus();
                bootbox.alert("La receta: " + nro_receta+ " se encuentra actualmente registrada");
                $('#btnReg').prop('disabled', false);
                return false;
            } else {
                validate_otros();
            }
        });     
    }
        
    function validar_receta_fisanulada_rec(nro_receta, id_farmacia){
      option = {                
          url: '/evitar_registro_recetas_anuladas_by_fiscalizador/' + nro_receta+ '/' + id_farmacia,
          type: 'GET',
          async:false,
          dataType: 'json',
          data: {}
      };
      $.ajax(option).done(function (data) {
          existe=data; 
          if(existe==0){
              $('#frmReceta').find("#nro_receta").focus();
              bootbox.alert("La receta: " + nro_receta+ " no fue anulada por su persona o no existe dicho número");
              $('#btnReg').prop('disabled', false);
              return false;
          } else {
              validate_otros();
          }
      });     
    }
        
    function genera_receta_duplicado(){
      nro_receta='P-'+$('#frmReceta').find("#nro_receta").val();
      id_farmacia=$('#frmReceta').find("#id_farmacia").val();
      option = {                
          url: '/genera_receta_duplicado/' + nro_receta+ '/' + id_farmacia,
          type: 'GET',
          async:false,
          data: {}
      };
      $.ajax(option).done(function (data) {
          $('#frmReceta').find("#nro_receta").val($('#frmReceta').find("#nro_receta").val() + "." + data);
          $('#frmReceta').find("#valid_nro_receta").val('1');
          $('#nro_receta').prop('readonly', true);
          $('#digitos_adicionales').prop('readonly', true);
          $('#fecha_atencion').focus();
      });
    }
    
    function validateFrm(){
      val_nro_rec=$('#frmReceta').find("#valid_nro_receta").val();
      val_nro_receta = $('#frmReceta').find("#nro_receta").val();
      var arrayval = val_nro_receta.split(".");

      if (val_nro_rec == "0") {
          if (isNumber(val_nro_receta)== false){
              bootbox.alert("El Número de Receta debe ser numérico");
              $('#btnReg').prop('disabled', false);
              return false;
          }
      }

      if (arrayval.length > 2) {
          bootbox.alert("El Número de Receta no puede tener más de un punto");
          $('#btnReg').prop('disabled', false);
          return false;
      }
        
      $('#btnReg').prop('disabled', true);
      nro_receta='P-'+$('#frmReceta').find("#nro_receta").val()+'-'+$('#frmReceta').find("#digitos_adicionales").val();
      id_farmacia=$('#frmReceta').find("#id_farmacia").val();
      id_consultorio=$('#frmReceta').find("#id_consultorio").val();
      var flagfiscalizador = "<?php echo $flagfiscalizador?>";
      if(flagfiscalizador != 1){
          validar_rec(nro_receta, id_farmacia);
      } else {
           validar_receta_fisanulada_rec(nro_receta, id_farmacia);
           //validar_rec(nro_receta, id_farmacia);
      }
    }
    
    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }
    
    function validate_otros(){
      if ($('#frmReceta').find("#nro_receta").val() == "") {
        bootbox.alert("Falta Digitar el Número de Receta");
        $('#btnReg').prop('disabled', false);
        return false;
      }                
      if ($('#frmReceta').find("#digitos_adicionales").val() == "") {
        bootbox.alert("Falta Digitar el Número Receta");
        $('#btnReg').prop('disabled', false);
        return false;
      }        
      if ($('#frmReceta').find("#id_consultorio").val() == "") {
        bootbox.alert("Falta seleccionar el Consultorio");
        $('#btnReg').prop('disabled', false);
        return false;
      }
      
      if ($('#frmReceta').find("#grado").val() == "") {
          bootbox.alert("Falta seleccionar el grado");
          $('#btnReg').prop('disabled', false);
          return false;
      }
      farmacos=$('#frmReceta').find("#nro_stocks").val();
      if(farmacos==undefined){                    
          bootbox.alert("La receta no tiene productos agregados, corregir o cancelar el proceso");
          $('#btnReg').prop('disabled', false);
          return false;        
      }
        
      if ($('#frmReceta').find("#fecha_expedicion").val() == "") {
          //alert("Falta seleccionar la fecha de expedición de la receta");
          bootbox.alert("Falta seleccionar la fecha de expedición de la receta");
          $('#btnReg').prop('disabled', false);
          return false;
      }

      
      if ($('#frmReceta').find("#interconsulta").val() == "") {
        bootbox.alert("Falta Seleccionar Si es o No una interconsulta");
        $('#btnReg').prop('disabled', false);
        return false;
      }
        
      //----------------------------------------------------------------------
      fecha_hace_treinta_dias=$('#frmReceta').find("#fecha_pasada").val();                        
      fecha_dispensar=$('#frmReceta').find("#fecha_expedicion").val();   
      var xMonth=fecha_hace_treinta_dias.substring(3, 5);                 
      var xDay=fecha_hace_treinta_dias.substring(0, 2);  
      var xYear=fecha_hace_treinta_dias.substring(6,10);         
      var yMonth=fecha_dispensar.substring(3, 5);          
      var yDay=fecha_dispensar.substring(0, 2);  
      var yYear=fecha_dispensar.substring(6,10);         
      if (xYear> yYear) {
        bootbox.alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
        $('#btnReg').prop('disabled', false);
        return false; 
      } else{
        if (xYear == yYear){
          if (xMonth> yMonth){
            bootbox.alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
            $('#btnReg').prop('disabled', false);
            return false;  
          } else {
            if (xMonth == yMonth){
              if (xDay> yDay){
                bootbox.alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
                $('#btnReg').prop('disabled', false);
                return false;  
              }
            }
          }
        }
      }
      //------------------------------------------------------------------------
      var hoy = new Date();
      var dd = hoy.getDate();
      var mm = hoy.getMonth()+1; //hoy es 0!
      var yyyy = hoy.getFullYear();        
      if(dd<10) {
          dd='0'+dd
      } 
      if(mm<10) {
          mm='0'+mm
      } 
      hoy = dd+'-'+mm+'-'+yyyy;
      fecha_dispensar=$('#frmReceta').find("#fecha_expedicion").val();                        
      var xMonth=fecha_dispensar.substring(3, 5);                 
      var xDay=fecha_dispensar.substring(0, 2);  
      var xYear=fecha_dispensar.substring(6,10);  
      var yMonth=hoy.substring(3, 5);          
      var yDay=hoy.substring(0, 2);  
      var yYear=hoy.substring(6,10);         
      if (xYear> yYear) {  
        bootbox.alert("La fecha de atención solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
        $('#btnReg').prop('disabled', false);
        return false; 
      }  
      else{  
        if (xYear == yYear){   
          if (xMonth> yMonth){  
            bootbox.alert("La fecha de atención solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
            $('#btnReg').prop('disabled', false);
            return false;
          }
          else{
            if (xMonth == yMonth){  
              if (xDay> yDay){  
                bootbox.alert("La fecha de atención solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
                $('#btnReg').prop('disabled', false);
                return false;  
              }
            }
          }  
        }
      }
      //----------------------------------------------------------------------         
      if ($('#frmReceta').find("#id_diagnostico").val() == "") {
        bootbox.alert("Falta seleccionar el diagnostico (CIE10)");
        $('#btnReg').prop('disabled', false);
        return false;
      }
      
      if ($('#frmReceta').find("#id_medico").val() == "") {
        bootbox.alert("Falta seleccionar el Profesional de Salud");
        $('#btnReg').prop('disabled', false);
        return false;
      }
              
      <?php if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
        if ($('#frmReceta').find(".tiempo_periodo").val() == "") {        
          bootbox.alert("Falta digitar el tiempo(dias)");
          $('#btnReg').prop('disabled', false);
          return false;
        }
        
        <?php if(!isset($diasDiferencia)){?>
                var fecha_atencion = new Date();
                if(fecha_atencion.getDay() == 6){
                  if(tiempo_periodo>30){
                      bootbox.alert('El periodo de tiempo debe ser 30 Días'); return false;
                    }
                }
                else
                {
                  if(fecha_atencion.getDay() !=0)
                  {
                    if(tiempo_periodo>30){
                      bootbox.alert('El periodo de tiempo debe ser de 30 Días'); return false;
                    }
                  }
                }                
        <?php }  ?>
        
      <?php endif;?>
    
      if ($('#frmReceta').find("#nro_med_solictados").val() == "") {
          bootbox.alert("Falta digitar la cantidad prescrita");
          $('#btnReg').prop('disabled', false);
          return false;
      }
      
      if ($('#frmReceta').find("#nro_med_entregados").val() == "") {
          bootbox.alert("Falta digitar la cantidad entregada");
          $('#btnReg').prop('disabled', false);
          return false;
      }
       
      var ind = $('#example tbody tr').length;
        
      if(ind > 0){
        var nind = ind-1;
        var cmbMedicamento = $("#cmbMedicamento"+nind).val()
        if(cmbMedicamento == "ru='undefined'" || cmbMedicamento == ""){
          $('.deleteFila').eq(ind - 1).parent().parent().remove();
          ind = ind-1;
        }
        fila_medicamentos=$('#example tbody').children('tr');            
        for (i = 0; i < ind; i++) {
          fila_medicamento=fila_medicamentos[i];
          var valmedentre = $(fila_medicamento).find('#nro_med_entregados').val();
          if(valmedentre == ""){
              bootbox.alert("Uno de los productos no cuenta con la cantidad entregada");
              $('#btnReg').prop('disabled', false);
              return false;
          }
        }
      }
    
      submitFrm();
      return false;
    };

    function validate_otros2(){
      if ($('#frmReceta').find("#nro_receta").val() == "") {
        bootbox.alert("Falta Digitar el Número de Receta");
        $('#btnReg').prop('disabled', false);
        return false;
      }                
      if ($('#frmReceta').find("#digitos_adicionales").val() == "") {
        bootbox.alert("Falta Digitar el Número Receta");
        $('#btnReg').prop('disabled', false);
        return false;
      }        
      if ($('#frmReceta').find("#id_consultorio").val() == "") {
        bootbox.alert("Falta seleccionar el Consultorio");
        $('#btnReg').prop('disabled', false);
        return false;
      }
      
      if ($('#frmReceta').find("#grado").val() == "") {
          bootbox.alert("Falta seleccionar el grado");
          $('#btnReg').prop('disabled', false);
          return false;
      }
      farmacos=$('#frmReceta').find("#nro_stocks").val();
      if(farmacos==undefined){                    
          bootbox.alert("La receta no tiene productos agregados, corregir o cancelar el proceso");
          $('#btnReg').prop('disabled', false);
          return false;        
      }
        
      if ($('#frmReceta').find("#fecha_expedicion").val() == "") {
          //alert("Falta seleccionar la fecha de expedición de la receta");
          bootbox.alert("Falta seleccionar la fecha de expedición de la receta");
          $('#btnReg').prop('disabled', false);
          return false;
      }
        
      //----------------------------------------------------------------------
      fecha_hace_treinta_dias=$('#frmReceta').find("#fecha_pasada").val();                        
      fecha_dispensar=$('#frmReceta').find("#fecha_expedicion").val();   
      var xMonth=fecha_hace_treinta_dias.substring(3, 5);                 
      var xDay=fecha_hace_treinta_dias.substring(0, 2);  
      var xYear=fecha_hace_treinta_dias.substring(6,10);         
      var yMonth=fecha_dispensar.substring(3, 5);          
      var yDay=fecha_dispensar.substring(0, 2);  
      var yYear=fecha_dispensar.substring(6,10);         
      if (xYear> yYear) {
        bootbox.alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
        $('#btnReg').prop('disabled', false);
        return false; 
      } else{
        if (xYear == yYear){
          if (xMonth> yMonth){
            bootbox.alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
            $('#btnReg').prop('disabled', false);
            return false;  
          } else {
            if (xMonth == yMonth){
              if (xDay> yDay){
                bootbox.alert("La fecha de expedición solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
                $('#btnReg').prop('disabled', false);
                return false;  
              }
            }
          }
        }
      }
      //------------------------------------------------------------------------
      var hoy = new Date();
      var dd = hoy.getDate();
      var mm = hoy.getMonth()+1; //hoy es 0!
      var yyyy = hoy.getFullYear();        
      if(dd<10) {
          dd='0'+dd
      } 
      if(mm<10) {
          mm='0'+mm
      } 
      hoy = dd+'-'+mm+'-'+yyyy;
      fecha_dispensar=$('#frmReceta').find("#fecha_expedicion").val();                        
      var xMonth=fecha_dispensar.substring(3, 5);                 
      var xDay=fecha_dispensar.substring(0, 2);  
      var xYear=fecha_dispensar.substring(6,10);  
      var yMonth=hoy.substring(3, 5);          
      var yDay=hoy.substring(0, 2);  
      var yYear=hoy.substring(6,10);         
      if (xYear> yYear) {  
        bootbox.alert("La fecha de atención solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
        $('#btnReg').prop('disabled', false);
        return false; 
      }  
      else{  
        if (xYear == yYear){   
          if (xMonth> yMonth){  
            bootbox.alert("La fecha de atención solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
            $('#btnReg').prop('disabled', false);
            return false;
          }
          else{
            if (xMonth == yMonth){  
              if (xDay> yDay){  
                bootbox.alert("La fecha de atención solo puede estar comprendida entre hoy y <?php echo $rango_registro?> días atrás");
                $('#btnReg').prop('disabled', false);
                return false;  
              }
            }
          }  
        }
      }
      //----------------------------------------------------------------------         
      if ($('#frmReceta').find("#id_diagnostico").val() == "") {
        bootbox.alert("Falta seleccionar el diagnostico (CIE10)");
        $('#btnReg').prop('disabled', false);
        return false;
      }
      
      if ($('#frmReceta').find("#id_medico").val() == "") {
        bootbox.alert("Falta seleccionar el Profesional de Salud");
        $('#btnReg').prop('disabled', false);
        return false;
      }
              
      <?php if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
        if ($('#frmReceta').find(".tiempo_periodo").val() == "") {        
          bootbox.alert("Falta digitar el tiempo(dias)");
          $('#btnReg').prop('disabled', false);
          return false;
        }
        
        <?php if(!isset($diasDiferencia)){?>
                var fecha_atencion = new Date();
                if(fecha_atencion.getDay() == 6){
                  if(tiempo_periodo>30){
                      bootbox.alert('El periodo de tiempo debe ser 30 Días'); return false;
                    }
                }
                else
                {
                  if(fecha_atencion.getDay() !=0)
                  {
                    if(tiempo_periodo>30){
                      bootbox.alert('El periodo de tiempo debe ser un 30 días'); return false;
                    }
                  }
                }                
        <?php }  ?>
        
      <?php endif;?>
    
      if ($('#frmReceta').find("#nro_med_solictados").val() == "") {
          bootbox.alert("Falta digitar la cantidad prescrita");
          $('#btnReg').prop('disabled', false);
          return false;
      }
      
      if ($('#frmReceta').find("#nro_med_entregados").val() == "") {
          bootbox.alert("Falta digitar la cantidad entregada");
          $('#btnReg').prop('disabled', false);
          return false;
      }
       
      var ind = $('#example tbody tr').length;
        
      if(ind > 0){
        var nind = ind-1;
        var cmbMedicamento = $("#cmbMedicamento"+nind).val()
        if(cmbMedicamento == "ru='undefined'" || cmbMedicamento == ""){
          $('.deleteFila').eq(ind - 1).parent().parent().remove();
          ind = ind-1;
        }
        fila_medicamentos=$('#example tbody').children('tr');            
        for (i = 0; i < ind; i++) {
          fila_medicamento=fila_medicamentos[i];
          var valmedentre = $(fila_medicamento).find('#nro_med_entregados').val();
          if(valmedentre == ""){
              bootbox.alert("Uno de los productos no cuenta con la cantidad entregada");
              $('#btnReg').prop('disabled', false);
              return false;
          }
        }
      }
    
      submitFrm();
      return false;
    };
    
    function submitFrm() {
        if ($('#frmReceta').find("#nro_med_solictados").val() >= 10) {
            bootbox.confirm({
                message: "Aviso: Revise la(s) cantidad(s) prescrita(s) y dispensada(s) antes de registrar. ÓDesea Grabar?.",
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
                    if (result==true) {
                        $("#frmReceta").submit();
                    } else {
                        $('#btnReg').prop('disabled', false);
                    }
                }
            });
        } else {
            bootbox.confirm({
                message: "Revise que los datos de la receta se encuentren correctos antes de registrar. ¿Desea Grabar?.",
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
                    if (result==true) {
                        $("#frmReceta").submit();
                    } else {
                        $('#btnReg').prop('disabled', false);
                    }
                }
            });
        }
    }
    //--------------------------------------------------------------------------
  $(".chosen-select").select2(
    {
      minimumInputLength: 3,
        tags: true,
      cache: true,
      language: { inputTooShort: function () { return 'Ingresar 3 caracteres para buscar el diagnostico'; } },

    }
  );
  
  
  $('.chosen-select').on("select2:select", function (e) {
    if(e.params.data.id > 0){
    
    }else{
      $(this).select2('val', '');
    }
    
   });
   
  function AddFilaMed(){
            if($('#id_medico').val()=="") return false;
            var newRow = "";
            var ind = $('#example tbody tr').length;
            if(ind == 0){
               AddFila(); 
            } 
         }
  function AddFila(){
                var tipo_receta = $('#tipo_receta').val();
                var id_consultorio = $('#id_consultorio').val();
    
                if(tipo_receta == ""){
                    bootbox.alert("Seleccione el tipo atención");
                    return false;
                }   
                if(id_consultorio == ""){
                    bootbox.alert("Seleccione un servicio");
                    return false;
                }
    var newRow = "";
    var ind = $('#example tbody tr').length;
    var tabindex = 11;
                var nuevalperiodo = "";
    if(ind > 0){
      $('.cmbMedicamento').each(function(){
        var ind_tmp = $(this).attr("ind");
        if(ind_tmp => ind){
                                    ind=Number(ind_tmp)+1;
                                    tabindex+=3;
        }
      });
                        
                    fila_medicamentos=$('#example tbody').children('tr');
                    rowIndex=$('#hidden_rowIndex').val();
                    var idval = ind - 1;
                    fila_medicamento=fila_medicamentos[idval];
                    var valante = $(fila_medicamento).find('#nro_med_entregados').val();
                    if(valante == ""){
                        bootbox.alert("Ingrese cantidad entregada en el producto anterior");
                        return false;
                    }
                    nuevalperiodo = document.getElementById('tiempo_periodo'+idval).value;
    }
    
    
    var item_producto   = "";
    $('#cmbMedicamentoTemp option').each(function(){
      item_producto += "<option value="+$(this).val()+" ru='"+$(this).attr("ru")+"'>"+$(this).html()+"</option>"  
    });
    
    newRow +='<tr>';
    //newRow +='<div id="dvMedicamento"></div>';
    newRow +='<td><select class="form-control cmbMedicamento" id="cmbMedicamento'+ind+'" ind="'+ind+'" tabindex="'+tabindex+'" name="id_producto[]" style="width: 500px">'+item_producto+'</select></td>';
    newRow +='<td><input type="text" name="nro_stocks[]" required id="nro_stocks" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/>';
    <?php if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
    newRow +='<td><input onkeypress="return soloNumerosMenosCero(event)" type="text" name="tiempo_periodo[]" tabindex="'+(tabindex+1)+'" required="" id="tiempo_periodo'+ind+'" class="nro_solicitado tiempo_periodo input-sm" style="margin-left:4px; width:100px" value="'+nuevalperiodo+'"/>';
    <?php endif;?>
    newRow +='<input type="hidden" name="lotes_lote[]" id="lotes_lote" value=""/>';
    newRow +='<input type="hidden" name="lotes_registro_sanitario[]" id="lotes_registro_sanitario" value=""/>';
    newRow +='<input type="hidden" name="lotes_fecha_vencimiento[]" id="lotes_fecha_vencimiento" value=""/>';
                newRow +='<input type="hidden" name="lotes_precio_unitario[]" id="lotes_precio_unitario" value=""/>';
    newRow +='<input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value=""/></td>';
    newRow +='<td><input onKeyPress="return soloNumerosMenosCero(event)" type="text" tabindex="'+(tabindex+2)+'" data-toggle="tooltip" data-placement="top" title="Ingresar la cantidad prescrita y presionar Enter para ingresar la cantidad entregada" name="nro_med_solictados[]" required="" id="nro_med_solictados'+ind+'" class="limpia_text nro_solicitado nro_med_solictados input-sm" style="margin-left:4px; width:100px" /></td>';
    newRow +='<td><input onKeyPress="return soloNumeros(event)" type="text" name="nro_med_entregados[]" required="" id="nro_med_entregados" class="limpia_text nro_solicitado input-sm" style="margin-left:4px; width:100px" readonly=""/></td>';
    newRow +='<td><input type="hidden" name="exit" id="exit" id="" style="width:10px" value=""/></td>';
    //newRow +='<td><button data-toggle="modal" class="btn btn-primary btn-xs" type="button" onclick="verlotes({{Auth::user()->id_farmacia}},this)">Add</button></td>'; //Wilian
                //newRow +='<td><button data-toggle="modal" class="btn btn-primary btn-xs" type="button" onclick="validproductoseleccionado({{Auth::user()->id_farmacia}},this)">Add</button></td>';//Jose
    newRow +='<td><button type="button"  class="btn btn-danger deleteFila btn-xs" style="margin-left:4px"><i class="fa fa-times"></i> Eliminar</button></td>';
    newRow +='</tr>';
    
    $('#example tbody').append(newRow);

    $("#cmbMedicamento"+ind).select2({max_selected_options: 4}).focus();
    
    $("#nro_med_solictados"+ind).keypress(function(e){
      if(e.which == 13) {
        validproductoseleccionado({{Auth::user()->id_farmacia}},this);
      }
    });
    
    $("#cmbMedicamento"+ind).on("change", function (e) {
      var flagx = 0;
      cmb = $(this);
      id_producto = $("#cmbMedicamento"+ind).val();
      id_user={{Auth::user()->id}};
      
      $('.cmbMedicamento').each(function(){
        var ind_tmp = $(this).val();
        if($(this).val() == id_producto)flagx++;
      });
      
      if(flagx > 1){
        bootbox.alert("El producto farmaceutico ya ha sido ingresado");
        $("#cmbMedicamento"+ind).val("").trigger("change");
                                return false;
      }
      
      option = {
        url: 'cargarstock_farmacia/' + id_producto+ '/' + id_user,
        type: 'GET',
        dataType: 'json',
        data: {}
      };
      $.ajax(option).done(function (data) {
                                $(cmb).closest("tr").find(".limpia_text").val("");
                                
        $(cmb).closest("tr").find("#nro_stocks").val(data);
        <?php if(isset($flag_receta_dias) && $flag_receta_dias == 1):?>
        //$(cmb).closest("tr").find(".tiempo_periodo").val("");
                                $(cmb).closest("tr").find(".tiempo_periodo").focus();
        <?php endif;?>
        $(cmb).closest("tr").find("#nro_med_solictados").val("");  
        $(cmb).closest("tr").find("#nro_med_entregados").val("");
        $(cmb).closest("tr").find("#lotes_lote").val("");
        $(cmb).closest("tr").find("#lotes_cantidad").val("");
        $(cmb).closest("tr").find("#lotes_registro_sanitario").val("");
        $(cmb).closest("tr").find("#lotes_fecha_vencimiento").val("");
      });
    });       
  }


        function show_Atenciones(dni){
            var dni_beneficiario = dni;
            var newOption = "";
            $.ajax({
                url: '/obtenerRecetasMesActualByBeneficiarioHospitalizacion/' + dni_beneficiario,
                success: function (result) {
                    var newOption = "";
                    var i = 1;
                    var colspan = "";
                    var nroaten = 1;
                    var mesactu = parseInt("<?php echo date("m")?>");
                    $(result).each(function (ii, oo) {
                        var colspan = "rowspan="+oo.colspan;
                        var fecexpe = oo.fecha_registro;
                        var res = fecexpe.split("-");
                        var mesexpe = parseInt(res[1]);
                        
                        var btn = "<button type=\"button\" data-toggle=\"modal\" class=\"btn btn-primary btn-xs\" onclick=\"show_Det_Atenciones('" + oo.id_receta_vale + "')\"><i class=\"fa fa-list\"></i></button>";
                        newOption += "<tr><td><small>" + oo.nro_receta + "</small></td><td><small>" + oo.fecha_expedicion + "</small></td><td><small>" + oo.fecha_registro + "</small></td><td><small>" + oo.nom_consultorio + "</small></td><td><small>" + oo.nom_farmacia + "</small></td><td><small>" + oo.apepat_medico + " " + oo.apemat_medico + " " + oo.nom_medico + "</small></td><td><small>" + oo.nom_diagnostico + "</small></td>";
                        if(i == 1){
                            if (mesactu > mesexpe){
                                newOption += "<td class=\"text-center\" "+ colspan +" style=\"vertical-align: middle\"><small>-</small>";
                            } else {
                                newOption += "<td class=\"text-center\" "+ colspan +" style=\"vertical-align: middle\"><small>" + nroaten + "</small>";
                                nroaten = nroaten + 1;                                
                            }
                        }
                        
                        newOption += "<td class=\"text-center\" style=\"vertical-align: middle\">" + btn + "</td></tr>";
                        
                        if(i == oo.colspan){
                                i=1;
                                colspan = "";
                        }else{
                                i = i + 1;
                        };
                        
                    });
                    $('#muestra-atenciones').html(newOption);
                }
            });
            $('#showAtencionesMensual').modal();
        }
        
        function show_Det_Atenciones(id_receta_vale){
            $.ajax({
                url: '/obtenerProductobyRecetasVale/' + id_receta_vale,
                success: function (result) {
                    var newOption = "";
                    $(result).each(function (ii, oo) {
                        newOption += "<tr><td class=\"text-center\"><small>" + oo.codigo + "</small></td><td><small>" + oo.nombre + "</small></td><td class=\"text-center\"><small>" + oo.abreviatura + "</small></td><td class=\"text-center\"><small>" + oo.cantidad_prescrita + "</small></td><td class=\"text-center\"><small>" + oo.cantidad_dispensada + "</small></td>";
                    });
                    $('#muestra-productos').html(newOption);
                }
            });
            $('#showProductosReceta').modal();
        }
        
        function valid_servicios_by_mes(){
        if($('#id_medico').val()=="") return false;
        var dni_beneficiario = $('#dni_beneficiario').val();
        var id_consultorio = $('#id_consultorio').val();
        var fecha_atencion = $('#fecha_atencion').val();
        var id_medico = $('#id_medico').val();
            $.ajax({
                url: '/obtenerCantAtencionesMesActualbyBeneficiarioHospitalizacion/' + dni_beneficiario+ '/' + id_consultorio+ '/' + fecha_atencion+ '/' + id_medico,
                success: function (result) {
                    result = result.split('|', 5);
                    maxnrousu = Number(result[0]);
                    maxnrores = Number(result[1]);
                    validaten = Number(result[2]);
                    totaten = Number(result[3]);
                    totmismaesp = Number(result[4]);
                    
                    var newOption = "";
                    newOption += "Existen <b><a href='#' onclick=valid_det_servicios_by_mes() style='text-decoration: none;'>" + totaten + "</a></b> atenciones en el servicio de HOSPITALIZACION, al mes de la fecha de expedición seleccionada<br/><br/>";
                    
                    
                    $('#msj-atenciones').removeClass("alert alert-success").addClass("alert alert-danger");
                    $('#det-msj-atenciones').html(newOption);
                  
                    //if (validaten >= maxnrousu){
                      /*
                            if (validaten == 3 && totmismaesp >= 1){
                                $("#addRow").prop("disabled", false);
                                $("#btnReg").prop("disabled", false);  
                            } else{
                                var flagresponsablefarmacia = "<?php echo $flagresponsablefarmacia?>";
                                if(flagresponsablefarmacia != 1){
                                    $("#addRow").prop("disabled", true);
                                    $("#btnReg").prop("disabled", true);
                                } else {
                                    if (validaten < maxnrores){//maxnrores sacar del procedimiento almacenado
                                        $("#addRow").prop("disabled", false);
                                        $("#btnReg").prop("disabled", false);                                
                                    } else {
                                        $("#addRow").prop("disabled", true);
                                        $("#btnReg").prop("disabled", true);  
                                    }
                                }
                            }
                      
                      $('#msj-atenciones').show();*/
                    //} else {
                        $('#msj-atenciones').removeClass("alert alert-danger").addClass("alert alert-success");
                        $('#det-msj-atenciones').html(newOption);
                        $("#addRow").prop("disabled", false);
                        $("#btnReg").prop("disabled", false);
                        $('#msj-atenciones').show();
                    //}
                }
               
            });
        }
        
        function valid_det_servicios_by_mes(){
            var dni_beneficiario = $('#dni_beneficiario').val();
            var id_consultorio = $('#id_consultorio').val();
            var fecha_atencion = $('#fecha_atencion').val();
            var id_medico = $('#id_medico').val();
            var newOption = "";
            $.ajax({
                url: '/obtenerDetAtencionesMesActualbyBeneficiario/' + dni_beneficiario+ '/' + id_consultorio+ '/' + fecha_atencion+ '/' + id_medico,
                success: function (result) {
                    var newOption = "";
                    var i = 1;
                    var colspan = "";
                    var nroaten = 1;
                    $(result).each(function (ii, oo) {
                        var colspan = "rowspan="+oo.cant_receta;
                        newOption += "<tr>";
                        if(i == 1){
                            newOption += "<td class=\"text-center\" "+ colspan +" style=\"vertical-align: middle\"><b>" + nroaten + "</b>";
                            nroaten = nroaten + 1;
                        }
                        var btn = "<button type=\"button\" data-toggle=\"modal\" class=\"btn btn-primary btn-xs\" onclick=\"show_Det_Atenciones('" + oo.id_receta + "')\"><i class=\"fa fa-list\"></i></button>";
                        newOption += "<td><small>" + oo.nom_farmacia + "</small></td><td><small>" + oo.fec_expedicion + "</small></td><td><small>" + oo.nom_consultorio + "</small></td><td><small>" + oo.apepat_medico + " " + oo.apemat_medico + " " + oo.nom_medico + "</small></td><td>"+oo.nro_receta+"</td><td>"+oo.fec_dispensacion+"</td><td class=\"text-center\" style=\"vertical-align: middle\">"+btn+"</td>";
                        newOption += "</tr>";
                        
                        if(i == oo.cant_receta){
                                i=1;
                                colspan = "";
                        }else{
                                i = i + 1;
                        };
                        
                    });
                    $('#muestra-det-atenciones').html(newOption);
                }
            });
            $('#showAtencionesDetMensual').modal();
            return false;
        }
 
 
 
 function validproductosrestringidos(id_farmacia, btn) {//Jose
    var dni_beneficiario = $('#dni_beneficiario').val();
    var id_medico = $('#id_medico').val();

    
    var objTr = $(btn).closest("tr");
    var id_producto = objTr.find(".cmbMedicamento").val();
    $.ajax({
      //url: '/validacion_beneficiario_dias_by_producto/' + dni_beneficiario+ '/' + nro_dias+ '/' + id_producto,
      url: '/validacion_productos_restringidos/' + id_medico+ '/' + id_producto,
      success: function (result) {
        var msg   = "";
        var n     = 0;
        var flag  = true;
        for(var key in result) {
          if(n == 0){
            msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:42px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario "+(result[key].paterno_beneficiario)+" "+(result[key].materno_beneficiario)+", "+(result[key].nombre_beneficiario)+" con DNI "+(result[key].dni_beneficiario)+" tiene receta con el mismo producto</h1>";              
            msg += "<table class='table table-list-search' style='font-size: 11px !important;' >";
                        msg += "<thead>";
                          msg += "<tr>";
            msg += "<th>Farmacia</th>";
            msg += "<th>Nro. Receta</th>";
            msg += "<th>Fecha Expedicion</th>";
            msg += "<th>Producto</th>";
            msg += "<th>Tiempo(Dias)</th>";
            msg += "<th>Dias Transcurridos</th>";
            msg += "<th>Dias Pendientes</th>";
            msg += "<th>Cantidad Prescrita</th>";
            msg += "<th>Cantidad Entregada</th>";
                          msg += "</tr>";
                        msg += "</thead>";
            msg += "<tbody>";
          }
              
          msg += "<tr><td>"+(result[key].farmacia)+"</td>";
          msg += "<td>"+(result[key].nro_receta)+"</td>";
          msg += "<td>"+(result[key].fecha_expedicion)+"</td>";
          msg += "<td>"+(result[key].codigo)+" - "+(result[key].producto)+"</td>";
          msg += "<td>"+(result[key].tiempo_periodo)+"</td>";
          msg += "<td>"+(result[key].dias_transcurridos)+"</td>";
          msg += "<td>"+(result[key].tiempo_periodo - result[key].dias_transcurridos)+"</td>";
          msg += "<td>"+(result[key].cantidad_prescrita)+"</td>";
          msg += "<td>"+(result[key].cantidad_dispensada)+"</td></tr>";
          n++;
        }
        if(msg == ""){
          verlotes(id_farmacia, btn);      
        } else {
          bootbox.alert(msg);
        }
      }
    });
  }

  function validproductoseleccionado(id_farmacia, btn) {//Jose
    var dni_beneficiario = $('#dni_beneficiario').val();
    var nro_dias = 30;
    var objTr = $(btn).closest("tr");
    var id_producto = objTr.find(".cmbMedicamento").val();
    var nivel=$('#nivel').val();
    $.ajax({
      url: '/validacion_beneficiario_dias_by_producto/' + dni_beneficiario+ '/' + nro_dias+ '/' + id_producto,
      success: function (result) {
        var msg   = "";
        var n     = 0;
        var flag  = true;
        for(var key in result) {
          if(n == 0){
            msg += "<h1 style='font-size:17px;color:#337ab7;padding:0px;margin:0px;line-height:42px;text-shadow:1px 0px #337ab7;border-radius:2px'>El beneficiario "+(result[key].paterno_beneficiario)+" "+(result[key].materno_beneficiario)+", "+(result[key].nombre_beneficiario)+" con DNI "+(result[key].dni_beneficiario)+" tiene receta con el mismo producto</h1>";              
            msg += "<table class='table table-list-search' style='font-size: 11px !important;' >";
                        msg += "<thead>";
                          msg += "<tr>";
            msg += "<th>Farmacia</th>";
            msg += "<th>Nro. Receta</th>";
            msg += "<th>Fecha Expedicion</th>";
            msg += "<th>Producto</th>";
            msg += "<th>Tiempo(Dias)</th>";
            msg += "<th>Dias Transcurridos</th>";
            msg += "<th>Dias Pendientes</th>";
            msg += "<th>Cantidad Prescrita</th>";
            msg += "<th>Cantidad Entregada</th>";
                          msg += "</tr>";
                        msg += "</thead>";
            msg += "<tbody>";
          }
              
          msg += "<tr><td>"+(result[key].farmacia)+"</td>";
          msg += "<td>"+(result[key].nro_receta)+"</td>";
          msg += "<td>"+(result[key].fecha_expedicion)+"</td>";
          msg += "<td>"+(result[key].codigo)+" - "+(result[key].producto)+"</td>";
          msg += "<td>"+(result[key].tiempo_periodo)+"</td>";
          msg += "<td>"+(result[key].dias_transcurridos)+"</td>";
          msg += "<td>"+(result[key].tiempo_periodo - result[key].dias_transcurridos)+"</td>";
          msg += "<td>"+(result[key].cantidad_prescrita)+"</td>";
          msg += "<td>"+(result[key].cantidad_dispensada)+"</td></tr>";
          n++;
        }
        if(msg == ""){
          verlotes(id_farmacia, btn);      
        } else {
          
            bootbox.confirm({
            title: "Alerta",
            message: msg,
            buttons: {
              cancel: {
                  label: '<i class="fa fa-times"></i> Cancelar'
              },
              confirm: {
                  label: '<i class="fa fa-check"></i> Continuar'
              }
            },
            callback: function (result) {
              //alert(result);
              if(result==true){
                  verlotes(id_farmacia, btn);
              }
            }
            });  
          
        }
      }
    });
  }
                    

  function select2Focus() {
          var select2 = $(this).data('select2');
          setTimeout(function() {
                  if (!select2.opened()) {
                          select2.open();
                  }
          }, 0);  
  }

  
  
  

  function muestra_select(){
   
    var tipo_receta  = $('#tipo_receta').val();

    var newOption = "";
    newOption = "<option value=''>--Seleccionar-</option>";
    $('#id_consultorio').html(newOption);
    $("#id_consultorio").val('').trigger("change");
    
    
    var newOption = "";
    newOption = "<option value=''>--Seleccionar-</option>";              
    $('#id_medico').html(newOption);
    $("#medico").val('').trigger("change");
    
    var newOption = "";
    newOption = "<option value=''>--Seleccionar-</option>";
    $('#tipo_via').html(newOption);
    $("#tipo_via").val('').trigger("change");

    

    if(tipo_receta=='AMBULATORIO-NUT'){
      
      var newOption = "";
      newOption = "<option value=''>--Seleccionar-</option>";
      newOption += "<option value=61>CIRUGIA GENERAL</option>";
      newOption += "<option value=8>MEDICINA INTERNA</option>";
      newOption += "<option value=17>NEFROLOGIA</option>";
      newOption += "<option value=49>PEDIATRIA</option>";
      $('#id_consultorio').html(newOption);

      $('#tipo_via').remove();
      $('#tipo_via_label').remove();
      $('#sub_via').remove();
      $('#sub_via_label').remove();
      $('#final_via').remove();
      $('#final_via_label').remove();

    }
    else
    {
      
      $.ajax({
        url: '/carga_consultorios/' + 20,
        success: function(result){                    
          var newOption = "";
          newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id+"'>" +oo.nombre+"</option>";
          });                 
          $('#id_consultorio').html(newOption);
          //$("#id_medico").select2({max_selected_options: 4});
        
        }
      }); 

      var label = document.createElement("label"); 
      var text = document.createTextNode("TIPO VIA"); 
      label.setAttribute("name", "tipo_via_label");
      label.setAttribute("id", "tipo_via_label");
      label.appendChild(text);
      $('#cargaTipoVia').append(label);

      var select = document.createElement("select");
       select.setAttribute("name", "tipo_via");
       select.setAttribute("class", "form-control select2");
       select.setAttribute("id", "tipo_via");
       select.setAttribute("tabindex", "4");
       select.setAttribute("onchange","getProducto_Via()");
       select.style.width = "100%";
        
       $('#cargaTipoVia').append(select);

      var newOption = "";
      newOption = "<option value=''>--Seleccionar Via-</option>";              
      newOption += "<option value='110'>ENTERAL</option>";  
      newOption += "<option value='111'>PARENTERAL</option>";  
      $('#tipo_via').html(newOption);

            
    }
  
  }
  
  function getMedico(){
      var newOption = "";
      newOption = "<option value=''>--Seleccionar-</option>";
      $('#id_medico').html(newOption);
      $("#id_medico").val('').trigger("change");
      var id_ipress     = $('#id_ipress').val();
      var id_consultorio  = $('#id_consultorio').val();
      var tipo_receta   = $('#tipo_receta').val();    
      
        $.ajax({
            url: '/obtener_medico_por_idipress_idconsultorio/' + id_ipress+ '/' + id_consultorio,
            success: function(result){                    
              var newOption = "";
              newOption = "<option value=''>--Seleccionar-</option>";
              $(result).each(function (ii, oo) {
                newOption += "<option value='"+oo.id+"'>"+oo.paterno+" "+oo.materno+", "+oo.nombre+" - "+oo.colegiatura+" - "+oo.rne+"</option>";
              });                 
              $('#id_medico').html(newOption);
              $("#id_medico").select2({max_selected_options: 4});
            
            }
          });  
      
     
  }
  function getProducto(){
       
    var tipo_receta   = $('#tipo_receta').val();    
    $('#example tbody').html('');

    if(tipo_receta == "AMBULATORIO-NUT"){
      
      if($('#id_consultorio').val()=="") return false;
      var id_ipress     = $('#id_ipress').val();
      var id_consultorio  = $('#id_consultorio').val();

      $.ajax({
        url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + id_consultorio,
        success: function(result){                    
          var newOption = "";
          newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          });                 
          $('#cmbMedicamentoTemp').html(newOption);
        }
      }); 
      
    }
    else
    {
      if($('#tipo_via').val()=="") return false;
      var id_ipress     = $('#id_ipress').val();
      var tipo_via  = $('#tipo_via').val();



      $.ajax({
        url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + tipo_via,
        success: function(result){                    
          var newOption = "";
          newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          });                 
          $('#cmbMedicamentoTemp').html(newOption);
        }
      });
    }
    
  }

  function getProducto_Via(){
       
    $.ajax({
          url: '/carga_consultorios/' + 20,
          success: function(result){                    
            var newOption = "";
            newOption = "<option value=''>--Seleccionar-</option>";
            $(result).each(function (ii, oo) {
              newOption += "<option value='"+oo.id+"'>" +oo.nombre+"</option>";
            });                 
            $('#id_consultorio').html(newOption);           
          
          }
        });

    var newOption = "";
    newOption = "<option value=''>--Seleccionar-</option>";
    $('#id_consultorio').html(newOption);
    $("#id_consultorio").val('').trigger("change");
    
    
    var newOption = "";
    newOption = "<option value=''>--Seleccionar-</option>";              
    $('#id_medico').html(newOption);
    $("#medico").val('').trigger("change");


      if($('#tipo_via').val()=="") return false;
      var id_ipress     = $('#id_ipress').val();
      var tipo_via  = $('#tipo_via').val();

      $.ajax({
        url: '/obtener_producto_por_idipress_idconsultorio/' + id_ipress+ '/' + tipo_via,
        success: function(result){                    
          var newOption = "";
          newOption = "<option value=''>--Seleccionar-</option>";
          $(result).each(function (ii, oo) {
            newOption += "<option value='"+oo.id+"' ru='"+oo.ru+"' >"+oo.codigo+" - "+oo.nombre+", "+oo.abreviatura+"</option>";
          });                 
          $('#cmbMedicamentoTemp').html(newOption);
        }
      });



      if(tipo_via==111){
         $('#sub_via').remove();
         $('#sub_via_label').remove();
         $('#final_via').remove();
         $('#final_via_label').remove();


      var label = document.createElement("label"); 
      var text = document.createTextNode("SUB VIA"); 
      label.setAttribute("name", "sub_via_label");
      label.setAttribute("id", "sub_via_label");
      label.appendChild(text);
      $('#cargaSubVia').append(label);
      
        var select = document.createElement("select");
         select.setAttribute("name", "sub_via");
         select.setAttribute("class", "form-control");
         select.setAttribute("id", "sub_via");
         select.setAttribute("tabindex", "5");
         select.setAttribute("onchange","getCargaFinalVia()");
         select.style.width = "100%";          
         $('#cargaSubVia').append(select);

         var newOption = "";
          newOption = "<option value=''>--Seleccionar Sub Via-</option>";              
          newOption += "<option value='PERIFERICA'>PERIFERICA</option>";  
          newOption += "<option value='TOTAL'>TOTAL</option>";  
          $('#sub_via').html(newOption);

      }
      else
      {
         $('#sub_via').remove();
         $('#sub_via_label').remove();
         $('#final_via').remove();
         $('#final_via_label').remove();

        var label = document.createElement("label"); 
        var text = document.createTextNode("SUB VIA"); 
        label.setAttribute("name", "sub_via_label");
      label.setAttribute("id", "sub_via_label");
        label.appendChild(text);
        $('#cargaSubVia').append(label);

        var select = document.createElement("select");
         select.setAttribute("name", "sub_via");
         select.setAttribute("class", "form-control");
         select.setAttribute("id", "sub_via");
         select.setAttribute("tabindex", "5");
         select.style.width = "100%";          
         $('#cargaSubVia').append(select);

         var newOption = "";
          newOption = "<option value=''>--Seleccionar Sub Via-</option>";              
          newOption += "<option value='VIA_ORAL'>VIA ORAL</option>";  
          newOption += "<option value='SONDA'>SONDA</option>";  
          $('#sub_via').html(newOption);

      }
    
    
  }

    function getCargaFinalVia(){
    
      if($('#sub_via').val()=="") return false;      
      var sub_via  = $('#sub_via').val();

      $('#final_via').remove();
      $('#final_via_label').remove();

      if(sub_via=='TOTAL'){

        var label = document.createElement("label"); 
        label.setAttribute("name", "final_via_label");
        label.setAttribute("id", "final_via_label");
        var text = document.createTextNode("VIA FINAL"); 
        label.appendChild(text);
        $('#cargaFinalVia').append(label);

        var select = document.createElement("select");
         select.setAttribute("name", "final_via");
         select.setAttribute("class", "form-control");
         select.setAttribute("id", "final_via");
         select.setAttribute("tabindex", "6");
         select.style.width = "100%";          
         $('#cargaFinalVia').append(select);

         var newOption = "";
          newOption = "<option value=''>--Seleccionar --</option>";              
          newOption += "<option value='2_1'>2:1</option>";  
          newOption += "<option value='3_1'>3:1</option>";  
          $('#final_via').html(newOption);

      }
      else
      {
        $('#final_via').remove();
        $('#final_via_label').remove();
      }
    
    
  }

    $(document).ready(function () {
        var idgrado = "<?php echo trim($beneficiario->grado);?>";
        if (idgrado == ""){
            $("#grado").val('NINGUNO').trigger("change");
        } else {
            $("#grado").val(idgrado).trigger("change");
        }
 
        $('#fecha_atencion').click(function(){
            var fec_expedicion = $('#fecha_expedicion').val();
            if(fec_expedicion == ""){
                alert('ingrese la fecha de expedición.');
                $('#fecha_expedicion').focus();
            }
        });
        
        $('#fecha_expedicion').click(function(){
            var fecha_atencion = $('#fecha_atencion').val();
            if(fecha_atencion == ""){
                alert('ingrese la fecha de atención.');
                $('#fecha_atencion').focus();
            }
        });
 
 
 
         $('#fecha_atencion').datepicker().on('hide', function (ev) {
            var fechaFinIni = $('#fecha_expedicion').val();
            var fechaIni = $('#fecha_atencion').val();
            f1 = fechaIni.split("-");
            var fechaFin = $('#fecha_expedicion').val();
            f2 = fechaFin.split("-");
            f3 = new Date();
            var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0], 0, 0, 59));
            var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0], 0, 0, 59));
            
            var diasdif= f2.getTime()-f1.getTime();
            var contdias = Math.round(diasdif/(1000*60*60*24));
            
            if (f1 > f2) {
                bootbox.alert('La Fecha de Expedición debe ser menor o igual a la fecha Dispensación.');
            }
            
            if (f1 > f3) {
                 bootbox.alert('La Fecha de Expedición debe ser menor o igual a la fecha Actual.');
            }
            
            <?php if(isset($diasDiferencia)){ ?>
              if (contdias >= 7){
                bootbox.alert('Existe una diferencia mayor a 7 días despues que se le ha dado de alta al paciente, Verifique por favor.');
                $('#btnReg').prop('disabled', true);
              }
              else
              {
                $('#btnReg').prop('disabled', false);
              }
           <?php }
            else
            {?>
              if (contdias >= 30){
                bootbox.alert('Existe una diferencia mayor a 30 días entre la fecha de Expedición y la fecha de Dispensación, Verifique por favor.');
                $('#btnReg').prop('disabled', true);
              }
              else
              {
                $('#btnReg').prop('disabled', false);
              }  
            <?php }?>
            
            
            $("#id_consultorio").val('').trigger("change");
  
            var newOption = "";
            newOption = "<option value=''>--Seleccionar-</option>";
            $('#id_medico').html(newOption);
            $("#id_medico").val('').trigger("change");
           
            $('#fecha_expedicion').focus();
            
        });
       
        $('#fecha_expedicion').change(function(){
            var fec_atencion = $('#fecha_atencion').val();
            var fec_expedicion = $('#fecha_expedicion').val();
            
            f1 = fec_atencion.split("-");
            f2 = fec_expedicion.split("-");
            f3 = new Date();

            var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0]));
            var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0])); //30 de noviembre de 2014
            
            var diasdif= f2.getTime()-f1.getTime();
            var contdias = Math.round(diasdif/(1000*60*60*24));

            if (f2 < f1) {
                 alert('La Fecha de Dispensación debe ser mayor o igual a la fecha Expedición.');
                 $('#fecha_expedicion').val('');
            }
            
            if (f2 > f3) {
                 alert('La Fecha de Dispensación debe ser menor o igual a la fecha Actual.');
                 $('#fecha_expedicion').val('');
            }

            <?php if(isset($diasDiferencia)){ ?>
              if (contdias >= 7){
                bootbox.alert('Existe una diferencia mayor a 7 días despues que se le ha dado de alta al paciente, Verifique por favor.');
                $('#btnReg').prop('disabled', true);
              }
              else
              {
                $('#btnReg').prop('disabled', false);
              }
           <?php }
            else
            {?>
              if (contdias >= 30){
                bootbox.alert('Existe una diferencia mayor a 30 días entre la fecha de Expedición y la fecha de Dispensación, Verifique por favor.');
                $('#btnReg').prop('disabled', true);
              }
              else
              {
                $('#btnReg').prop('disabled', false);
              }  
            <?php }?>
            
            $('#fecha_expedicion').focus();
       });
    });
  
    function ocultar_titu(){
        if($("#muestra-datos-titu").is(':hidden')){
            $('#muestra-datos-titu').show();
        } else {
            $('#muestra-datos-titu').hide();
        }
    }
    
    /*esta es la función estática*/
    function calcular(fecini,fecfin){
            var fechaini = new Date(fecini);
            var fechafin = new Date(fecfin);
            var diasdif= fechafin.getTime()-fechaini.getTime();
            var contdias = Math.round(diasdif/(1000*60*60*24));
            alert(contdias);
    }
        
</script>
<style>
    .modal-dialog{
        width: 850px !important;
    }    
    .suggest-element{
        margin-left:5px;
        margin-top:5px;
        width:350px;
        cursor:pointer;
    }
    #suggestions {
        width:350px;
        height:150px;
        overflow: auto;
    }
    
        
    .alert {
        padding: 10px;
background-position: 2% 7px;
background-repeat: no-repeat;
background-size: auto 35px;
/*background-color: rgba(0, 0, 0, 0);*/
border: 0;
min-width: auto !important;
text-align: left;
padding-left: 68px;
font-size: 14px;
margin-bottom: 10px;
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

.form-control{
    height: 28px;
}

.form-group{
    margin-bottom: 10px;
}
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="row">
                <div class="panel panel-primary filterable">            
                    <div class="panel-heading">
                        <h3 class="panel-title">MÓDULO DE GESTIÓN DE RECETAS - SOPORTE NUTRICIONAL</h3>    
                    </div>

                    <div style=" font-size: 11px" class="panel-body">
                      @if($nro_interconsultas>0)
                      <div id="msjes" class="alert alert-danger">
                        <div id="mensaje-atenciones">Existen interconsulta(s), en  
                          <?php $x=1;?>
                          @foreach($interconsultas as $i)
                            {{$i->consultorio}} ({{$i->dif_dias}} dias)
                            @if($x==$nro_interconsultas)
                              <br/><br/>
                            @else
                              ,
                            @endif
                            <?php $x++;?>
                          @endforeach                                       
                        </div>
                      </div>
                      @endif
                        <form class="form-horizontal" method="post" action="{{ route('registrar_receta_nutricional')}}" id="frmReceta" autocomplete="off">
                            <!--  action="{{ route('registrar_receta_rua')}}" -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="col-md-4 text-center">
                                    <input type="hidden" value="<?php echo $nro_interconsultas;?>" name="nro_interconsultas" id="nro_interconsultas">
                                    <?php if ($opcReceta == 1): ?>
                                        <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
                                        <input type="hidden" value="{{ Auth::user()->id }}" name="id_user">
                                        <input type="hidden" value="{{$fecha_pasada}}" name="fecha_pasada" id="fecha_pasada">
                                        <input type="hidden" value="dni" name="tipo">
                                        <input type="hidden" name="valid_nro_receta" id="valid_nro_receta" value="0"/>
                                    <?php endif; ?>

                                    <?php if ($opcReceta == 2): ?>
                                        <input type="hidden" value="{{$id_farmacia[0]}}" name="id_farmacia" id="id_farmacia">
                                        <input type="hidden" value="{{$nro_rua}}" name="nro_rua">
                                        <input type="hidden" value="rua" name="tipo">
                                        <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                                        <input type="hidden" value="{{$fecha_pasada}}" name="fecha_pasada" id="fecha_pasada">
                                    <?php endif; ?>
                                    <input type="hidden" value="<?php echo $id_ipress ?>" name="id_ipress" id="id_ipress">
                                    <input type="hidden" value="<?php echo $nivel ?>" name="nivel" id="nivel">
                                    <input type="hidden" value="<?php echo date("Y") ?>" name="digitos_adicionales" id="digitos_adicionales">

                                </div>
                            </div>
                            <div class="form-group">
                              <div class="col-sm-12">
                                  <div id="msj-atenciones">
                                      <div id="det-msj-atenciones"></div>
                                  </div>
                              </div>
                              <div class="col-md-2">
                                  <label>TIPO ATENCIÓN</label>
                                  <select class="form-control" id="tipo_receta" autofocus="" name="tipo_receta" tabindex="1" style="width: 100%" onchange="muestra_select()">
                                      <option value="AMBULATORIO-NUT">AMBULATORIO</option>
                                      <option value="HOSPITALIZACION-NUT">HOSPITALIZACION</option>
                                  </select>
                              </div>
                              <div class="col-md-2">
                                  <label>N° RECETA</label>
                                  <input class="form-control" onKeyPress="return soloNumeros(event)" type="text" class="" name="nro_receta"  id="nro_receta" tabindex="2">
                              </div>
                              <div class="col-md-2">
                                  <label>FEC. EXPEDICIÓN (A)</label>
                                  <input type="text" class="form-control datepicker" value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_atencion" id="fecha_atencion" tabindex="4"  data-date-end-date="0d" autocomplete="off"/>
                              </div>
                              <div class="col-md-2" id="cargaTipoVia"></div>
                              <div class="col-md-2" id="cargaSubVia"></div>
                              <div class="col-md-2" id="cargaFinalVia"></div>
                            </div>
                            <div class="form-group">
                              <div class="col-md-2">
                                  <label>FEC. DISPENSACIÓN (R)</label>
                                  <input type="text" class="form-control" value="<?php echo date("d-m-Y", strtotime($fechaServidor)); ?>" name="fecha_expedicion" id="fecha_expedicion" tabindex="7" readonly=""/>
                              </div>
                              <div class="col-md-3">
                                  <label>SERVICIOS</label>
                                  <select class="form-control" id="id_consultorio" name="id_consultorio" tabindex="8" style="width: 100%" onchange="getProducto(); getMedico()">
                                      <option value="">--Seleccionar--</option>
                                      <option value="61">CIRUGIA GENERAL</option>
                                      <option value="8">MEDICINA INTERNA</option>
                                      <option value="17">NEFROLOGIA</option>
                                      <option value="49">PEDIATRIA</option>
                                  </select>
                              </div>
                              <div class="col-md-4">
                                <label>MEDICO (DNI | CMP | Nombre y Apellidos)</label>
                                <?php if (isset($existe_medico) && $existe_medico == 1): ?>
                                    <input  name="exite_medico" tabindex="9"  value="{{$existe_medico}}" type="hidden">
                                <?php endif; ?>
                                <select class="form-control" tabindex="9"  name="id_medico"  id="id_medico"  style="width: 100%" onchange="AddFilaMed();">
                                    <option value="">--Seleccionar--</option>
                                </select>
                              </div>
                              <div class="col-md-2">
                                        <label>HISTORIA CLINICA</label>
                                        <input type="text" tabindex="10" class="form-control" value="{{$numero_historia}}" id="nro_historia" name="nro_historia"onkeyup="javascript:this.value = this.value.toUpperCase();">
                                    </div>
                              
                              
                              
                            </div>
                            @if($beneficiario->parentesco!="TITULAR")
                            <div id="muestra-datos-titu" style="display: none;">
                                <div class="form-group">
                                     <div class="col-sm-12">
                                        <label><a href="#" onclick="ocultar_titu()">Ocultar Datos del Titular</a></label>
                                     </div>
                                    <div class="col-md-2">
                                        <label>DNI (TITULAR)</label>
                                        <?php if ($opcReceta == 1): ?>
                                            <input type="hidden" class="form-control" value="{{$beneficiario->nomtipdoctitular}}" name="tipdoc_titular" id="tipdoc_titular" readonly=""/>
                                            <input readonly="" type="text" class="form-control" value="{{$beneficiario->nrodoctitular}}" name="dni_titular">
                                        <?php endif; ?>
                                        <?php if ($opcReceta == 2): ?>
                                            <input type="hidden" class="form-control" value="{{$beneficiario->nomtipdoctitular}}" name="tipdoc_titular" id="tipdoc_titular" readonly=""/>
                                            <input type="text" class="form-control" value="{{$beneficiario->dni_titular}}" name="dni_titular" readonly="">
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-7">
                                        <label>APELLIDOS Y NOMBRES (TITULAR)</label>
                                        <?php if ($opcReceta == 1): ?>
                                            <input type="text" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apepattitular . " " . trim($beneficiario->apemattitular . " " . $beneficiario->apecastitular)) . ", " . $beneficiario->nomtitular) ?>" name="paterno_beneficiario"  readonly="">
                                        <?php endif; ?>

                                        <?php if ($opcReceta == 2): ?>
                                            <input type="text" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apepattitular . " " . trim($beneficiario->apemattitular . " " . $beneficiario->apecastitular)) . ", " . $beneficiario->nomtitular) ?>" name="paterno_beneficiario" readonly="">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <div class="col-md-2">
                                    <label>PARENTESCO &nbsp;
                                        <?php 
                                        if($beneficiario->parentesco<>"TITULAR") {
                                            ?>
                                        <a href="#" onclick="ocultar_titu()">Datos Titular</a>
                                        <?php
                                        }
                                            ?></label>
                                    <input readonly="" value="{{$beneficiario->parentesco}}" type="text" name="tipo_beneficiario"  tabindex="-3" id="" class="form-control" >
                                </div>
                                <div class="col-md-2">
                                    <label>{{$beneficiario->nomtipdocafiliado}}</label>
                                    <?php if ($opcReceta == 1): ?>
                                        <input type="hidden" class="form-control" value="{{$beneficiario->nomtipdocafiliado}}" name="tipdoc_beneficiario" id="tipdoc_beneficiario" readonly=""/>
                                        <input type="hidden" class="form-control" value="{{$beneficiario->cip}}" name="cip_beneficiario" id="cip_beneficiario" readonly=""/>
                                        <input type="text"  tabindex="-4" class="form-control" value="{{$beneficiario->nrodocafiliado}}" name="dni_beneficiario" id="dni_beneficiario" readonly=""/>
                                    <?php endif; ?>

                                    <?php if ($opcReceta == 2): ?>
                                        <input type="hidden" class="form-control" value="{{$beneficiario->nomtipdocafiliado}}" name="tipodoc_beneficiario" id="tipo_beneficiario" readonly="">
                                        <input type="hidden" class="form-control" value="{{$beneficiario->cip}}" name="cip_beneficiario" id="cip_beneficiario" readonly="">
                                        <input type="text" class="form-control" value="{{$beneficiario->dni}}" tabindex="-5" name="dni_beneficiario" id="dni_beneficiario" required="" readonly="">
                                    <?php endif; ?>

                                </div>
                                @if($beneficiario->parentesco=="TITULAR")<?php //print_r($beneficiario);?> <!-- titular  -->
                                <div class="col-md-4">
                                    <label>APELLIDOS Y NOMBRES</label>
                                    <?php if ($opcReceta == 1): ?>
                                        <input readonly="" tabindex="-6" type="text" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apepatafiliado . " " . trim($beneficiario->apematafiliado . " " . $beneficiario->apecasafiliado)) . ", " . $beneficiario->nomafiliado) ?>" name="paterno_beneficiario" >
                                        <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->nomafiliado)) ?>" name="nombre_beneficiario"/>
                                        <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apepatafiliado)) ?>" name="paterno_beneficiario"/>
                                        <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apematafiliado . " " . $beneficiario->apecasafiliado)) ?>" name="materno_beneficiario"/>
                                    <?php endif; ?>

                                    <?php if ($opcReceta == 2): ?>
                                        <input type="text" tabindex="-7" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apepatafiliado . " " . trim($beneficiario->apematafiliado . " " . $beneficiario->apecasafiliado)) . ", " . $beneficiario->nomafiliado) ?>" name="paterno_beneficiario" readonly="">
                                        <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->nomafiliado)) ?>" name="nombre_beneficiario"/>
                                        <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apepatafiliado)) ?>" name="paterno_beneficiario"/>
                                        <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apematafiliado . " " . $beneficiario->apecasafiliado)) ?>" name="materno_beneficiario"/>
                                    <?php endif; ?>
                                </div> 
                                @else <!-- Derechohabienre  -->
                                    <div class="col-md-4">
                                        <label>APELLIDOS Y NOMBRES</label>
                                        <?php if ($opcReceta == 1): ?>
                                            <input readonly="" tabindex="-8" type="text" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apepatafiliado . " " . trim($beneficiario->apematafiliado . " " . $beneficiario->apecasafiliado)) . ", " . $beneficiario->nomafiliado) ?>" name="paterno_beneficiario" >
                                            <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->nomafiliado)) ?>" name="nombre_beneficiario">
                                            <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apepatafiliado)) ?>" name="paterno_beneficiario">
                                            <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apematafiliado . " " . $beneficiario->apecasafiliado)) ?>" name="materno_beneficiario">
                                        <?php endif; ?>

                                        <?php if ($opcReceta == 2): ?>
                                            <input type="text" tabindex="-9" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apepatafiliado . " " . trim($beneficiario->apematafiliado . " " . $beneficiario->apecasafiliado)) . ", " . $beneficiario->nomafiliado) ?>" name="paterno_beneficiario" id="paterno_beneficiario" readonly="">
                                            <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->nomafiliado)) ?>" name="nombre_beneficiario">
                                            <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apepatafiliado)) ?>" name="paterno_beneficiario">
                                            <input type="hidden" class="form-control" value="<?php echo utf8_decode(trim($beneficiario->apematafiliado . " " . $beneficiario->apecasafiliado)) ?>" name="materno_beneficiario">
                                        <?php endif; ?>
                                    </div>
                                @endif                                 
                            </div>  
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>DIAGNOSTICO</label>
                                    <?php if (isset($existe_cie) && $existe_cie == 1): ?>
                                        <select tabindex="7" class="form-control" name="id_diagnostico" id="id_diagnostico" required="" >
                                            <option value="{{$cie->id}}">{{$cie->codigo}} - {{$cie->nombre}}</option>                                        
                                        </select>
                                    <?php else: ?>

                                        <!--
    <select class="form-control chosen-select" name="id_diagnostico" id="id_diagnostico" tabindex="14">
    <option value="">--Seleccionar--</option>
    @foreach($diagnostico as $d)
    <option value="{{$d->id}}">{{$d->codigo}} - {{$d->nombre}}</option>
    @endforeach
    </select>
                                        -->
                                        <input type="text" class="form-control typeahead tt-query" id="nombre_diagnostico" tabindex="11" name="nombre_diagnostico">
                                        <input type="hidden" class="form-control typeahead tt-query" id="id_diagnostico" name="id_diagnostico">

                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="">
                                <?php if (isset($nro_rua) && $nro_rua != ""): ?>
                                    <div class="col-md-2" style="text-align: center; margin-top: 20px;float:right">
                                        <label>N° RUA</label>
                                        <input type="text" value="{{$nro_rua}}" name="nro_rua" class="form-control" readonly="readonly" >
                                    </div>
                                <?php else: ?>
                                    <input type="hidden" value="" name="nro_rua" class="form-control" >
                                <?php endif; ?>

                            </div>
                        <!--</form>-->
                        <?php
                        $exit = 0;
                        if (isset($flag) && $flag == 1)
                            $exit = 1;
                        ?>
                        <input type="hidden" class="form-control" value="<?php echo $exit ?>" name="exit" id="exit">
                        <div class="col-md-12" style="text-align: left; font-size: 18px">
                            PRESCRIPCIÓN 
                        </div>                                
                        <div id="entregar_receta" class="row col-md-12" style="padding-top:10px; padding-bottom:0px; border: 1px solid gray; margin-bottom: 10px; margin-left: 3px">
                          <?php if (!isset($flag)): ?>
                            <button type="button" id="addRow" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Agregar producto(s)</button>
                            <div style="display: none">
                              <select class="form-control" id="cmbMedicamentoTemp" tabindex="16" style="width: 500px">
                                  <option value="">Seleccionar Producto</option>
                                @foreach($producto as $p)     
                                  <option value="{{$p->id}}" ru="{{$p->ru}}">{{$p->codigo}} - {{$p->nombre}}, {{$p->abreviatura}}</option>
                                @endforeach
                              </select>  
                            </div>
                          <?php endif; ?>
                          <input type="hidden" value="0" name="hidden_rowIndex" id="hidden_rowIndex">
                          <table id="example" class="display" cellspacing="0" width="100%">
                            <thead>
                              <tr>
                                <th>Producto farmacéutico o dispositivo médico</th>
                                <th>Stock Actual</th>
                                  <?php if (isset($flag_receta_dias) && $flag_receta_dias == 1): ?>
                                <th>Tiempo(Dias)</th>
                                <?php endif; ?>
                                <th>Cantidad Prescrita</th>
                                <th>Cantidad Entregada</th>
                                  <?php if (isset($flag) && $flag == 1): ?>
                                      <th>Add</th>
                                  <?php else: ?>
                                      <th></th>                                      
                                      <th>Eliminar</th>
                                  <?php endif; ?>
                              </tr>
                            </thead>
                            <tfoot>
                            </tfoot>
                            <tbody>
                              <?php if (isset($productos))://print_r($productos); ?>
                                @foreach($productos as $med)
                                  <tr>
                                    <td>            
                                      <input type="text" class="form-control" name="" id="medicina" value="{{$med[0]}} - {{$med[1]}} - {{$med[2]}}">                                  
                                      <input type="hidden" class="form-control" name="id_producto[]" id="cmbMedicamento" value="{{$med[3]->id_producto}}">
                                    </td>
                                    <td>
                                      <input type="text" name="nro_stocks[]" required id="nro_stocks" class="form-control" style="margin-left:4px; width:100px;" readonly="" value="{{$med[3]->cantidad}}"/>
                                      <input type="hidden" name="lotes_id[]" id="lotes_id" value="">
                                      <input type="hidden" name="lotes_cantidad[]" id="lotes_cantidad" value="">
                                    </td>
                                    <td>
                                      <input type="text" name="nro_med_solictados[]" required=""  id="nro_med_solictados" class="form-control text-center" style="margin-left:4px; width:100px; text-align: center;" value="<?php echo $med[4] ?>" onKeyPress="return soloNumeros(event)"/>
                                    </td>                                    
                                    <td>
                                        <input type="text" name="nro_med_entregados[]"  id="nro_med_entregados" class="form-control" style="margin-left:4px; width:100px" readonly="" required=""/>                                               
                                    </td>
                                    <td>
                                        <button data-toggle="modal" class="btn btn-success btn-xs" type="button" onclick="verlotes2({{$med[0]}},{{$id_farmacia[0]}},this)">Add</button>
                                    </td>
                                  </tr>
                                @endforeach
                              <?php endif; ?>
                            </tbody>
                          </table>
                        </div>
                        <div class="col-md-12 text-center">
                          <label>Nro. Recetas dispensadas y atenciones en el mes actual: </label>&nbsp;&nbsp;
                          <button type="button" data-toggle="modal" class="btn btn-primary btn-xs" onclick="show_Atenciones('{{$beneficiario->nrodocafiliado}}')"><i class="fa fa-list"></i> Detalle</button>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <a href="/nuevo_nutricional/" class="form-control btn btn-danger" tabindex="99922">CANCELAR</a>
                            </div>
                            <div class="col-md-6">
                                <!--<input id="btnReg" class="form-control btn btn-success" type="submit" value="GUARDAR" tabindex="99921">-->
                                <button id="btnReg" class="form-control btn btn-success" type="button" tabindex="99921" onclick="validateFrm()">GUARDAR</button>
                            </div>
                        </div>
                      </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" style="">
    <div class="modal-dialog">
        <div class="modal-content">            
            <form method="post" action="{{ route('reasignar_lotes')}}" id="frmlotizar">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-header" id="modal-header">
                    <!--<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title" id="lineModalLabel">Dispensar</h4>
                    <div id="alerta" class="alert alert-danger"></div>
                </div>
                <div class="modal-body">
                    <table border="0px" id="tabla_lote">
                        <thead>
                            <tr>
                                <th style="width:10%">CÓdigo</th>   
                                <th style="width:40%">Nombre</th> 
                                <th style="width:10%">Lote</th> 
                                <th style="width:10%">Reg. San.</th> 
                                <th style="width:10%">Precio</th>
                                <th style="width:10%">Fec. ven.</th>
                                <th style="width:10%">Cant. Actual.</th> 
                                <th style="width:10%">Cant. Entregar</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>                        
                    </table>
                </div>
                <div class="modal-footer">
                    <div class="btn-group btn-group-justified" role="group" aria-label="group button">                        
                        <div class="btn-group" role="group">
                            <div class="col-md-6 text-center">
                                <button type="button" onclick="sumarCantidadLotes(0)" id="saveImage" class="btn btn-success btn-hover-green" data-action="save" role="button">Guardar</button>
                            </div>
                            <div class="col-md-6 text-center">
                                <button type="button" onclick="sumarCantidadLotes(1)" id="saveImage" class="btn btn-success btn-hover-green" data-action="save" role="button">Guardar y Agregar Nueva Fila</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="showAtencionesMensual" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="width: 900px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><small style="color: #fff">Nro. Recetas dispensadas y atenciones en el mes actual</small></h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><small>Nro. Receta</small></th>
                                <th><small>Fecha <br/>Dispensación</small></th>
                                <th><small>Fecha <br/>Expedición</small></th>
                                <th><small>Consultorio</small></th>
                                <th><small>Farmacia</small></th>
                                <th><small>Médico</small></th>
                                <th><small>Diagnóstico</small></th>
                                <th><small>Nro. Aten-<br>ciones</small></th>
                                <th><small>Detalle<br/>Producto</small></th>
                            </tr>
                        </thead>
                        <tbody id="muestra-atenciones"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="showAtencionesDetMensual" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" style="width: 900px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-size: 22px; color: #fff">Nro. Atenciones según fecha de expedición</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered" style="font-size: 13px;">
                        <thead>
                            <tr>
                                <th><small>Nro. <br/>Atención</small></th>
                                <th><small>Farmacia</small></th>
                                <th><small>Fecha <br/>Expedición</small></th>
                                <th><small>Consultorio</small></th>
                                <th><small>Médico</small></th>
                                <th><small>Nro Receta</small></th>
                                <th><small>Fecha <br/>Dispensación</small></th>
                                <th><small>Detalle<br/>Producto</small></th>
                            </tr>
                        </thead>
                        <tbody id="muestra-det-atenciones"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="showProductosReceta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Detalle de Recetas</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><small>Código</small></th>
                                <th><small>Descipción</small></th>
                                <th><small>Unidad</small></th>
                                <th><small>Pedido</small></th>
                                <th><small>Despachado</small></th>
                            </tr>
                        </thead>
                        <tbody id="muestra-productos"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
