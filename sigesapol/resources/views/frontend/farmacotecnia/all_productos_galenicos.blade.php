@extends('frontend.layouts.master')
@section('renderjs')
<style>


    .filterable {
        margin-top: 0px;
    }
    .filterable .panel-heading .pull-right {
        margin-top: -20px;
    }
    .filterable .filters input[disabled] {
        background-color: transparent;
        border: none;
        cursor: auto;
        box-shadow: none;
        padding: 0;
        height: auto;
    }
    .filterable .filters input[disabled]::-webkit-input-placeholder {
        color: #333;
    }
    .filterable .filters input[disabled]::-moz-placeholder {
        color: #333;
    }
    .filterable .filters input[disabled]:-ms-input-placeholder {
        color: #333;
    }
    #global {
        height: 480px !important;
        width: auto;
        border: 1px solid #ddd;
        background: #f1f1f1;
        overflow-y: scroll !important;
    }
</style>

<script>
function abrirModal(id) {
    $.ajax({
        url: '/ver_formula_galenico/' + id,
        type: 'GET',
        success: function(response) {
            $("#contenido-modal-galenico").html(response);
            $('#modalGalenico').modal('show');
        },
        error: function() {
            alert("No se pudo cargar la información.");
        }
    });
}

function ModalCalcularFormula(id) {
    $.ajax({
        url: '/calculo_formula_galenicos/' + id,
        type: 'GET',
        success: function(response) {
            $("#contenido-modal-calculo").html(response);
            $('#modalCalculo').modal('show');
        },
        error: function() {
            alert("No se pudo cargar la información.");
        }
    });
}

function exportargeneral(id) {
        Swal.fire({
            title: '<div class="titulo-padding"><p>Generando el reporte...</p></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                header : 'swal2-header-generar'
            },
            onBeforeOpen: () => {
                $('#blur-container').append('<div class="blur-background"></div>');
                Swal.showLoading();
                $.ajax({
                    type: "POST",
                    url: "reporte_galenicos_precio",
                    data: {
                    },
                    success: function (response) {        
                        if (response.excelNameResponse) {
                            let fileUrl = `/download_reportes/${response.excelNameResponse}`;
                            let link = document.createElement('a');
                            console.log(fileUrl);
                            link.href = fileUrl;
                            link.download = 'Reporte_Galenicos_Precio.xlsx';
                            link.innerText = 'Descargar archivo';
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
                                    $('.blur-background').remove();
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
                                $('.blur-background').remove(); 
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
<div class="container">
    <div class="row">
        <div class="col-md-10 well">
            <div class="panel panel-primary filterable">

                <div class="panel-heading">
                    <h4 class="panel-title">Productos Farmacéuticos</h4>
                </div>
                <div class="panel-body">
                            <label class="">&nbsp;</label>
                                  <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="exportargeneral('{{ Auth::user()->id }}')" >
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar
                                  </a>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Identificador</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Formula</th>
                                <th>Ver Calculo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($galenicos as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->codigo }}</td>
                                    <td>{{ $row->nombre }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm"
                                                onclick="abrirModal({{ $row->id }})">
                                            Ver
                                        </button>
                                    </td>
                                                                        <td>
                                        <button class="btn btn-primary btn-sm"
                                                onclick="ModalCalcularFormula({{ $row->id }})">
                                            Calculo
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalGalenico" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h4 class="modal-title">Detalle de la Formula</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body" id="contenido-modal-galenico">  

            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalCalculo" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document"
         style="max-width:95% !important; width:95%;">
        <div class="modal-content" style="height:85vh;">
            <div class="modal-header">
                <h4 class="modal-title">Detalle del Calculo</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="contenido-modal-calculo"
                 style="overflow-x:auto; white-space:nowrap; max-height:82vh; overflow-y:auto;">
            </div>
        </div>
    </div>
</div>
<div id="link-reporte" style="display:none;"></div>
@include('frontend.includes.footer')
@endsection