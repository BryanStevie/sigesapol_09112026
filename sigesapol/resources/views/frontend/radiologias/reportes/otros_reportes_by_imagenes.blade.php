<div id="blur-container">
    @extends('frontend.layouts.master')
    @section('renderjs')
    <script type="text/javascript">
    
        function validacion(valor) {
            $('#divDni').hide();
            $('#dni').attr('disabled', true).hide();
            if (valor == 13) {
                $('#divDni').show();
                $('#dni').attr('disabled', false).show();
            }
        }
    
        function validarDni() {
            var dni = $('#dni').val();
            var len = dni.length;
            /*if(len!=8){
             bootbox.alert("En Dni tiene que ser de 8 digitos");
             return false;
             }*/
        }
    
        // $('#id_farmaciaBus').select2();
        // $('#id_producto').select2();
        $('#id_estado').select2();
    
        $(document).ready(function () {
    
            $('#fecha_inicio').datepicker().on('hide', function (ev) {
                var fechaFinIni = $('#fecha_fin').val();
                var fechaIni = $('#fecha_inicio').val();
                f1 = fechaIni.split("-");
                var fechaFin = $('#fecha_fin').val();
                f2 = fechaFin.split("-");
                f3 = new Date();
                var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0], 0, 0, 59));
                var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0], 0, 0, 59));
                if (f1 > f2) {
                    $('#fecha_inicio').val(fechaFinIni);
                    $('#fecha_inicio').change();
                    bootbox.alert("La Fecha de Inicio no debe ser mayor a la Fecha Fin");
                    return false;
                }
            });
    
    
            $('#fecha_fin').datepicker().on('hide', function (ev) {
                var fechaFinIni = $('#fecha_inicio').val();
                var fechaIni = $('#fecha_inicio').val();
                f1 = fechaIni.split("-");
                var fechaFin = $('#fecha_fin').val();
                f2 = fechaFin.split("-");
                f3 = new Date();
                var f1 = new Date(parseInt(f1[2]), parseInt(f1[1] - 1), parseInt(f1[0], 0, 0, 59));
                var f2 = new Date(parseInt(f2[2]), parseInt(f2[1] - 1), parseInt(f2[0], 0, 0, 59));
                if (f1 > f2) {
                    $('#fecha_fin').val(fechaFinIni);
                    $('#fecha_fin').change();
                    bootbox.alert("La Fecha Fin no debe ser menor a la Fecha de Inicio");
                    return false;
                }
            });
    
        });
    
        // function reporteVarios(idUser) {
    
        //     Swal.fire({
        //         title: '<div class="titulo-padding"><p>Generando el reporte...</p></div>',
        //         allowOutsideClick: false,
        //         showConfirmButton: false,
        //         customClass: {
        //             header : 'swal2-header-generar'
        //         },
        //         onBeforeOpen: () => {
        //             $('#blur-container').append('<div class="blur-background"></div>');
        //             var fecha_inicio = $('#fecha_inicio').val();
        //             var fecha_fin = $('#fecha_fin').val();
        //             var id_farmacia = $('#id_farmacia').val();
        //             var tipo = $('#tipo').val();
        //             if (fecha_inicio == "")fecha_inicio = 0;
        //             if (fecha_fin == "")fecha_fin = 0;
        //             if (id_farmacia == "")id_farmacia = 0;
        //             if (tipo == "")tipo = 0;
        //             Swal.showLoading();
        //             $("#frmIci").submit();
        //         }
        //     });
    
        // }

        $("#reportePorEstudio").click(function (e) {
            Swal.fire({
                title: '<div class="titulo-padding"><p>Generando el reporte...</p></div>',
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: {
                    header : 'swal2-header-generar'
                },
                onBeforeOpen: () => {
                    $('#blur-container').append('<div class="blur-background"></div>');
                    
                    let fecha_inicio = $("#fecha_inicio").val();
                    let fecha_fin = $("#fecha_fin").val();
                    let id_estado = $("#id_estado").val();
                    let url = `{{ route('exportar_reporte_imagenes_por_estudio') }}`
                    Swal.showLoading();
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            "fecha_inicio" : fecha_inicio,
                            "fecha_fin" : fecha_fin,
                            "id_estado" : id_estado
                        },
                        success: function (response) {
                            console.log(response);
                            
                            console.log(response);
                            console.log(response.excelNameResponse);
                            if (response.excelNameResponse) {
                                
                                // Obtener la URL del archivo del response
                                let fileUrl = `/download_reportes/${response.excelNameResponse}`;
                                
                                // Crear un enlace de descarga
                                let link = document.createElement('a');
                                link.href = fileUrl;
                                link.download = 'Exportar Reportes de Imagenes.xlsx';
                                link.innerText = 'Descargar archivo';

                                // Simular un clic en el enlace para iniciar la descarga
                                let divLinkReporte = document.getElementById('link-reporte');
                                divLinkReporte.appendChild(link);
                                link.click();
                                divLinkReporte.removeChild(link);

                                Swal.hideLoading();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'El reporte se ha generado correctamente!',
                                    showConfirmButton: false,
                                    showConfirmButton: false,
                                    timer: 1500,
                                    onClose: () => {
                                        $('.blur-background').remove(); // Elimina el fondo desenfocado cuando se cierra la alerta
                                    }
                                    
                                });
                            }
                        },
                        error: function() {
                            Swal.hideLoading();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al generar el reporte',
                                text: 'Por favor, inténtelo de nuevo más tarde',
                                confirmButtonText: 'Cerrar',
                                customClass: {
                                    confirmButton : 'custom-swal-button-cerrar'
                                },
                                onClose: () => {
                                    $('.blur-background').remove(); // Elimina el fondo desenfocado cuando se cierra la alerta
                                }
                            });
                        }
                    });
                }
            });
        });
    
    </script>
    @endsection
    @section('content')
    <div class="container col-md-6 col-md-offset-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    Reporte por Estudio
                </div>
            </div>
            <div class="panel-body">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div class="row" style="display: flex; justify-content: center; align-items: center;">
                        <div class="col-md-4">
                            <label class="help-block">Fecha de Inicio <i class="fa fa-calendar"></i></label>
                            <input id="fecha_inicio" name="fecha_inicio" type="text" placeholder="" class="form-control input-md datepicker" required="" value="{{$fecha_actual}}" data-date-end-date="0d" autocomplete="Off" readonly="" maxlength="10"/>
                        </div>
                        <div class="col-md-4">
                            <label class="help-block">Fecha Fin <i class="fa fa-calendar"></i></label>
                            <input id="fecha_fin" name="fecha_fin" type="text" placeholder="" class="form-control input-md datepicker" required="" value="{{$fecha_actual}}" data-date-end-date="0d" autocomplete="Off" readonly="" maxlength="10"/>
                        </div>  
                        <div class="col-md-4">
                            <a class="btn btn-success form-control input-sm" id="reportePorEstudio">
                                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label">Estado</label>
                            <select class="form-control input-sm" name="id_estado" id="id_estado">
                                <option value='0'>Procesados</option>
                                <option value='4'>No procesados</option>
                            </select>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="link-reporte"></div>
    @include('frontend.includes.footer')
    @endsection
    