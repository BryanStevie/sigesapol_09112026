<style>
    .table-sortable tbody tr {
        cursor: move;
    }
    
    #global {
        min-height: 650px !important;
        /*width: auto;*/
        width: 100%;
        border: 1px solid #ddd;
        margin:15px
            /* background: #f1f1f1;*/
            /*overflow-y: scroll !important;*/
    }

    .margin{

        margin-bottom: 20px;
    }
    .margin-buscar{
        margin-bottom: 5px;
        margin-top: 5px;
    }

    .row{
        margin-top:10px;
        padding: 0 10px;
    }
    .clickable{
        cursor: pointer;   
    }

    .panel-heading div {
        margin-top: -18px;
        font-size: 15px;        
    }
    .panel-heading div span{
        margin-left:5px;
    }
    .panel-body{
        display: block;
    }

    .dataTables_filter {
        display: none;
    }
    
    .help-block {
        display: block;
        margin-top: 1px;
        margin-bottom: 10px;
        color: #737373;
    }
    
    .modal-dialog{
        /* width: 70% !important; */
    }
    
    #tarjetas .panel {
        text-align: center;
        font-weight: bold;
    }

    #tarjetas-sala-seleccionada .panel {
        text-align: center;
        font-weight: bold;
    }

    #tarjetas-cama .panel {
        text-align: center ;
        /* font-weight: bold; */
    }

    #tarjetas-cama .panel-body {
        /* font-size: 10.5px; */
    }
    
    .bg-blanco {
        background-color: rgb(255, 255, 255);
    }

    .bg-celeste {
        background-color: rgb(217, 237, 247);
    }

    .bg-rojo {
        background-color: rgb(242, 222, 222);
    }

    .bg-plomo {
        background-color: rgb(183, 183, 183);
    }

    .bg-verde-vomito {
        background-color: rgb(135, 148, 48);
    }

    /* if ($item->id_situacion=='4'): $style = "background-color: rgb(217, 237, 247);"; // celeste
                                    elseif ($item->id_situacion=='2'): $style = "background-color: rgb(242, 222, 222);"; //rojo
                                    elseif ($item->id_situacion=='3'): $style = "background-color: rgb(242, 222, 222);"; //rojo rgb(245, 245, 245);
                                    elseif ($item->id_situacion=='5'): $style = "background-color: rgb(135, 148, 48);"; //rojo rgb(245, 245, 245);
                                    elseif ($item->id_situacion=="" || $item->id_situacion=="0"): $style = "background-color: rgb(183, 183, 183);"; //plomo */
</style>

<script>

    function modalCamaMasInfo(id_cama, tipo_atencion){
        if (true) {
            // bootbox.alert('Mensaje prueba - eliminar');
            $.ajax({
                url: "/modal_censocama_masinfo/"+id_cama+"/"+tipo_atencion,
                type: "GET",
                success: function (result) {
                    if(result!=""){
                        // $('#modal2 .modal-dialog').css('width', '90%');
                        // $('#detalle-info-cama').html(result);
                        // $('#modal2').modal('show');
                        bootbox.alert(result);
                    }
                }
            });
        } else {
            bootbox.alert("Aun no se cuenta con información para Hospitalización.")
        }
    }

    function cambiarSituacionCama(id_cama) {
        // if (tipo_atencion=="EMERGENCIA") {
            // $.ajax({
            //     url: "/modal_censocama_masinfo/"+id_cama+"/"+tipo_atencion,
            //     type: "GET",
            //     success: function (result) {
            //         if(result!=""){
                        // $('#panel-cama-' + id_cama).css('width', '90%');
                        // $('#detalle-info-cama').html(result);
                        // $('#modal2').modal('show');
                        $('#panel-cama-' + id_cama + ' .btn').text('Cargando');
                        $('#panel-cama-' + id_cama + ' .btn').attr('disabled', "false");
                        
                        $('#panel-cama-' + id_cama + ' #icono-cargando').addClass("fa-refresh fa-spin");

                        setTimeout(function(){
                            $('#panel-cama-' + id_cama).removeClass('bg-verde-vomito');
                            $('#panel-cama-' + id_cama).addClass('bg-blanco');
                            $('#panel-cama-' + id_cama + ' .div-cambio-situacion').hide();
                            $('#panel-cama-' + id_cama + ' .span-situacion').text("LIBRE");
                        }, 4000);
                        
            //         }
            //     }
            // });
        // } else {
        //     bootbox.alert("Aun no se cuenta con información para Hospitalización.")
        // }
    }

    // var sleep = function(ms){
    //     return new Promise(resolve => setTimeout(resolve, ms));
    // };

    // async function pruebaES6(){
    //     console.log('Inicio de la función de prueba.');
    //     await sleep(5000);    //Dormimos la ejecución durante 3 segundos
    //     console.log('Fin de la función de prueba.');
    // };


    $('.toggle-icon').on('click', function () {
        var $icon = $(this).find('i');
        var $panelBody = $(this).closest('.panel').find('.panel-body');
        
        // Alternar visibilidad del panel-body
        $panelBody.slideToggle();
        
        // Cambiar el ícono de la flecha
        if ($icon.hasClass('fa-chevron-down')) {
            $icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
        } else {
            $icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
        }
    });
</script>


<div class="panel panel-primary filterable" >
    <div class="panel-body">
        <div id="tarjetas-sala-seleccionada">
            <div class="row">
                <div class="col-xs-6 col-sm-4 col-md-2 col-md-offset-1">
                    <div class="panel panel-primary" style="background-color: rgb(223, 240, 216)">
                        <div class="panel-body">
                            Total<br>camas<br>
                            <i class="fa fa-bed fa-2x" aria-hidden="true"></i>&emsp;{{$conteoSituaciones['TOTAL']}}
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-2">
                  <div class="panel panel-info" style="background-color: rgb(184, 185, 185)">
                      <div class="panel-body">
                          Camas<br>Sin Situacion<br>
                          <i class="fa fa-bed fa-2x" aria-hidden="true"></i>&emsp;{{$conteoSituaciones['SIN SITUACIÓN']}}
                      </div>
                  </div>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-2">
                  <div class="panel panel-info" style="background-color: rgb(217, 237, 247)">
                      <div class="panel-body">
                          Camas<br>ocupadas<br>
                          <i class="fa fa-bed fa-2x" aria-hidden="true"></i>&emsp;{{$conteoSituaciones['OCUPADO']}}
                      </div>
                  </div>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-2">
                  <div class="panel panel-default">
                      <div class="panel-body">
                          Camas<br>Libres<br>
                          <span class="glyphicon glyphicon-bed" style="font-size: 25px" aria-hidden="true"></span>&emsp;{{$conteoSituaciones['LIBRE']}}
                      </div>
                  </div>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-2">
                  <div class="panel panel-danger" style="background-color: rgb(242, 222, 222)">
                      <div class="panel-body">
                          Camas<br>bloqueado<br>
                          <i class="fa fa-wrench fa-2x" aria-hidden="true"></i>&emsp;{{($conteoSituaciones['EN REPARACION']+$conteoSituaciones['MALOGRADO'])}}
                      </div>
                  </div>
                </div>
    
            </div>
        </div>
      {{-- ------------------------------------------------- --}}
    
      <div class="w-100">
        @foreach($camasAgrupadas as $nombreservicio => $cuartos)
        <!-- Panel para el Servicio -->
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Servicio: {{ $nombreservicio }} 
                 
                </h3>
                <span class="toggle-icon pull-right" style="cursor: pointer;">
                  <i class="fa fa-chevron-down"></i> <!-- Icono de flecha hacia abajo -->
                </span>
            </div>
            <div class="panel-body">
                @foreach($cuartos as $nombrecuarto => $camas)
                    <!-- Panel para el Cuarto -->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                          <h3 class="panel-title">
                            Cuarto: {{ $nombrecuarto }}
                          
                          </h3>
                        </div>
                        <div class="panel-body">
                            <div id="tarjetas-cama">
                                <div class="row">
                                    @foreach($camas as $item)
                                        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                                            <div class="panel {{ $item->class }}" style="height:140px;" id="panel-cama-{{ $item->id }}">
                                                <div class="panel-body">
                                                    <!-- Nombre de la Cama -->
                                                    <span class="glyphicon glyphicon-bed" aria-hidden="true"></span>&emsp;
                                                    <span class="label {{ $item->tipo_label }}">
                                                        {{ $item->tipocama == "" ? "SIN TIPO" : $item->tipocama }}
                                                    </span> <br> {{ $item->nombrecama }}<br><br>

                                                    <!-- Situación de la Cama -->
                                                    <span class="span-situacion">
                                                       {{ $item->situacioncamadesc == "" || $item->situacioncamadesc == null ? 'SIN SITUACIÓN' : $item->situacioncamadesc }}
                                                    </span><br>

                                                    <!-- Botones según la Situación de la Cama -->
                                                    @if($item->situacioncama == "4")
                                                        <button class="btn btn-default" onclick="modalCamaMasInfo({{ $item->id }}, '{{ $item->tipo_atencion }}')">
                                                            <i class="fa fa-info"></i> MÁS INFO
                                                        </button>
                                                    @elseif($item->situacioncama == "5")
                                                        <div class="div-cambio-situacion">
                                                            <select class="form-control">
                                                                <option value="">CAMA ACTUAL</option>
                                                                <option value="">LIBRE</option>
                                                            </select>
                                                            <span id="icono-cargando" class="fa"></span>
                                                            <button class="btn btn-default" onclick="cambiarSituacionCama({{ $item->id }})">
                                                                Guardar
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
      
      </div>
    
      
      
      {{-- --------------------------------- --}}


    </div>
</div>

<div class="modal fade" tabindex="-1" id="modal2" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div id="detalle-info-cama">
            </div>
        </div>
    </div>
</div>