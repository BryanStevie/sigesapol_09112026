@extends('frontend.layouts.master')
@section('renderjs')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.10.111/pdf.min.js"></script>

<script>
   // Función para mostrar PDF desde base64
   function mostrarPDF(base64Data, nombre) {
    const container = document.getElementById('pdf-render-container');
    container.innerHTML = ''; // Limpiar antes de mostrar

    if (!base64Data) {
        container.innerHTML = '<p>No hay PDF disponible.</p>';
        $('#btn-imprimir, #btn-descargar').prop('disabled', true);
        return;
    }

    if (!base64Data.startsWith('data:application/pdf;base64,')) {
        base64Data = 'data:application/pdf;base64,' + base64Data;
    }

    $('#btn-imprimir, #btn-descargar').prop('disabled', false);
    $('#nombre-examen').text(': ' + nombre);

    // Convertir base64 a array buffer
    const base64 = base64Data.split(',')[1];
    const binary = atob(base64);
    const len = binary.length;
    const bytes = new Uint8Array(len);
    for (let i = 0; i < len; i++) {
        bytes[i] = binary.charCodeAt(i);
    }

    // Cargar el PDF
    pdfjsLib.getDocument({ data: bytes }).promise.then(function(pdf) {
        for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
            pdf.getPage(pageNumber).then(function(page) {
                const scale = 1.5;
                const viewport = page.getViewport({ scale });

                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                container.appendChild(canvas);

                page.render({
                    canvasContext: context,
                    viewport: viewport
                });
            });
        }
    });
}
const pdfEjemplo = 'data:application/pdf;base64,<?php echo $pdfBase64 ?>';
mostrarPDF(pdfEjemplo, "Resultados");



document.getElementById('btn-abrir-nueva-pestana').onclick = function () {
    // if (!window.base64GlobalPdf) return alert("No hay PDF cargado");

    // Crear un Blob a partir del base64
    // const base64 = window.base64GlobalPdf.split(',')[1];
    const binary = atob('<?php echo $pdfBase64 ?>');
    const len = binary.length;
    const bytes = new Uint8Array(len);
    for (let i = 0; i < len; i++) {
        bytes[i] = binary.charCodeAt(i);
    }
    const blob = new Blob([bytes], { type: 'application/pdf' });

    // Crear URL y abrir en nueva pestaña
    const blobUrl = URL.createObjectURL(blob);
    window.open(blobUrl, '_blank');
};

            
            // Evento para imprimir
            $('#btn-imprimir').click(function() {
                if($('#pdf-viewer').attr('src')) {
                    const pdfWindow = window.open($('#pdf-viewer').attr('src'));
                    pdfWindow.addEventListener('load', function() {
                        pdfWindow.print();
                    });
                }
            });
            
            // Evento para descargar
            $('#btn-descargar').click(function() {
                if($('#pdf-viewer').attr('src')) {
                    const a = document.createElement('a');
                    a.href = $('#pdf-viewer').attr('src');
                    a.download = 'examen_' + $('#nombre-examen').text().replace(': ', '') + '.pdf';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                }
            });
            
            // Filtros de exámenes
            $('.filtro-todos').click(function(e) {
                e.preventDefault();
                $('.examen-item').show();
            });
            
            $('.filtro-pendientes').click(function(e) {
                e.preventDefault();
                $('.examen-item').hide();
                $('.examen-pendiente').show();
            });
            
            $('.filtro-procesados').click(function(e) {
                e.preventDefault();
                $('.examen-item').hide();
                $('.examen-procesado').show();
            });
            
            // Inicialización: ocultar visor de PDF
            //$('#pdf-viewer').hide();
           
</script>
@endsection
<style>
.pdf-container {
    width: 100%;
    overflow-x: auto;
}

.lista-examenes {
            max-height: calc(100vh - 150px);
            overflow-y: auto;
        }
        .examen-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }
        .examen-item:hover {
            background-color: #f5f5f5;
        }
        .examen-item.activo {
            background-color: #e8f4f8;
            border-left: 4px solid #337ab7;
        }
        .examen-pendiente {
            color: #f39c12;
        }
        .examen-procesado {
            color: #27ae60;
        }
        .pdf-container {
            height: calc(100vh - 150px);
            border: 1px solid #ddd;
            background-color: #fff;
            border-radius: 4px;
        }
        #pdf-viewer {
            width: 100%;
            height: 100%;
        }
        .btn-action {
            margin-right: 5px;
        }
        .no-examenes {
            padding: 20px;
            text-align: center;
            color: #777;
        }
</style>
@section('content')

<div class="panel panel-primary filterable">
 <div class="panel-heading">
  <h3 class="panel-title">Resultados</h3>
 </div>
 <div style="font-size: 11px" class="panel-body">
    <div class="row" style="padding-bottom: 1rem;">
        <div class="col-md-4 text-center">
            
        </div>
        <div class="col-md-4" style="text-align: center">
            <label>HOSPITAL NACIONAL PNP LUIS N. SAENZ</label><br>
            <label style="font-size: 24px">ANALISIS DE LABORATORIO</label><br>
        </div>
        <div class="col-md-4 text-center">
            
        </div>
    </div>
    <div class="row">
          <!-- Columna izquierda (3) - Lista de exámenes -->
          <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-8">
                            <h3 class="panel-title">Exámenes</h3>
                        </div>
                        <div class="col-xs-4 text-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
                                    Filtrar <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="#" class="filtro-todos">Todos</a></li>
                                    <li><a href="#" class="filtro-pendientes">Pendientes</a></li>
                                    <li><a href="#" class="filtro-procesados">Procesados</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lista-examenes">
                    <!-- Datos ficticios para demostración -->
                    @foreach ($examenes as $item)
                    <div class="examen-item 
                    @if($item->estado == "Con resultado") 
                    examen-procesado 
                    @else 
                      examen-pendiente 
                    @endif
                    activo" data-id="1">
                        <h6>{{$item->nombre}}</h6>
                        <div>
                            <small>
                                <i class="glyphicon glyphicon-check"></i>
                                {{$item->estado}}
                            </small>
                            <small class="pull-right"></small>
                        </div>
                    </div>
                    @endforeach
                    
               
                </div>
            </div>
          </div>


          <!-- Columna derecha (9) - Visor PDF -->
          <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">

                  @if(count($existeResultado)!= 0)
                  <div class="container-fluid">
                    <div class="row " style="margin-bottom: 10px;">
                      <div class="col-4">
                        <button id="btn-abrir-nueva-pestana" class="btn btn-primary">
                          imprimir PDF
                        </button>
                      </div>
                    </div>
                  </div>
                    

                  @endif
                    
                    <div class="pdf-container">
                        <!-- Aquí se mostrará el PDF en base64 -->
                        @if(count($existeResultado)!= 0)
                         <div id="pdf-render-container"></div>

                        @else
                        <div id="no-pdf-mensaje" class="no-examenes">
                          <i class="glyphicon glyphicon-file" style="font-size: 48px; display: block; margin-bottom: 15px;"></i>
                          <h6>No hay resultados para visualizar</h6>
                      </div>
                        @endif
                        
                       
                    </div>
                </div>
            </div>
          </div>


    
 </div>
</div>
@endsection