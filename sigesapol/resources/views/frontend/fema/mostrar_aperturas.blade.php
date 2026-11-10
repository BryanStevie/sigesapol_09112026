<div id="blur-container">
@extends('frontend.layouts.master')
@section('renderjs')
<script>
    
    $(document).ready(function () {
        $('#example').DataTable();
    });
    
    function reporteApertura(idUser) {

        Swal.fire({
            title: '<div class="titulo-padding"><p>Generando el reporte...</p></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                header : 'swal2-header-generar'
            },
            onBeforeOpen: () => {
                $('#blur-container').append('<div class="blur-background"></div>');
                var fecha_ini = $('#fechaDesde').val();
                var fecha_fin = $('#fechaHasta').val();
                var dni = $('#nroDocumento').val();

                if (fecha_ini == "")fecha_ini = 0;
                if (fecha_fin == "")fecha_fin = 0;
                if (dni == "")dni = 0;
                Swal.showLoading();
                $.ajax({
                    type: "POST",
                    url: "exportar_reporte_apertura",
                    data: {
                        "fecha_ini" : fecha_ini,
                        "fecha_fin" : fecha_fin,
                        "dni" : dni,
                    },
                    success: function (response) {
                    
                        if (response.excelNameResponse) {
                            // Obtener la URL del archivo del response
                            let fileUrl = `/download_reportes/${response.excelNameResponse}`;
                            // Crear un enlace de descarga
                            let link = document.createElement('a');
                            console.log(fileUrl);
                            link.href = fileUrl;
                            link.download = 'Reporte_Fema_Apertura.xlsx';
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

        }

</script>
@endsection
@section('content')
<div class="container-fluid">
      <div class="panel panel-body">
        <legend style="text-align:center"></legend>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Busquedas de Aperturas</h3>
            </div>
            <div class="panel-body">
                <form id="frm_llamado_ticket" class="" method="post" action="{{ route('busca_apertura_fema')}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div clas="row">                              
                        <div class="col-md-12"> 
                        <div class="col-md-2">
                                <div class="form-group">
                                    <label class="">N° DOCUMENTO</label>
                                    <input class="form-control input-sm" type="text" name="nroDocumento" id="nroDocumento" placeholder="" value="{{$nroDocumento}}" maxlength="8">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="">&nbsp;</label>
                                    <input class="form-control btn btn-primary input-sm" value="BUSCAR" type="submit">
                                </div>
                            </div>
                        
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="">FECHA DESDE</label>
                                    <input class="form-control input-sm datepicker" type="text" name="fechaDesde" id="fechaDesde" value="{{$fechaDesde}}" autocomplete="off">
                                </div> 
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="">FECHA HASTA</label>
                                    <input class="form-control input-sm datepicker" type="text" name="fechaHasta" id="fechaHasta" value="{{$fechaHasta}}" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-2">
                              <div class="form-group">
                                  <label class="">&nbsp;</label>
                                  <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteApertura('{{ Auth::user()->id }}')" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                  </a>
                              </div>
                          </div>
                        </div>   
                    </div>
                </form>
            </div>
        </div> 
        <div class="panel panel-primary">
        <div class="panel-heading">
                <h3 class="panel-title" align="CENTER"><strong>LISTA DEL APERTURA - FEMA</strong></h3>
            </div>
            <div class="panel-body">
                <!--
        <div class="col-sm-12" style="border: 1px solid gray; padding-top: 10px; margin-top: 10px">
        -->
        <div class="col-sm-12">
          <div class="table-responsive">
            <table id="example" class="display table" cellspacing="0" width="100%">
              <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                  <!--  <th>Hora</th> -->
                    <th>Tipo Doc</th>
                    <th>Núm Doc</th>
                    <th>Apellidos y Nombres</th>
                    <th>Proceso</th>
                    <th>Sub-Proceso</th>
                    <th>Establecimiento</th>
                    <th>Personal de Salud</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=0; ?>
                @foreach($fichas as $f)
                <?php $i++; ?>
                <tr>
                    <td>{{$i}}</td>
                    <td>{{date('d/m/Y', strtotime($f->fecha_reg))}}</td>
                  <!--  <td>{{$f->hora_reg}}</td>-->

                    @if($f->tipo_doc_ident == 1)
                        <td>DNI</td>
                    @else
                        <td>{{$f->tipo_doc_ident}}</td>
                    @endif
                    <td>{{$f->nro_doc_ident}}</td>
                    <td>{{$f->paterno}} {{$f->materno}} {{$f->nombre}}</td>
                    <td>{{$f->proceso}}</td>
                    <td>{{$f->tipo_proceso}}</td>
                    <td>{{$f->establecimiento}}</td>
                    <td>{{$f->usu_paterno}} {{$f->usu_materno}} {{$f->usu_nombre}}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        </div>
        </div>
      </div>
</div>
<div id="link-reporte"></div>
@include('frontend.includes.footer')
@endsection
