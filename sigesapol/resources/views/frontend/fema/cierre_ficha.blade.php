<div id="blur-container">
@extends('frontend.layouts.master')
@section('renderjs')
<script>
    
    $(document).ready(function () {
        $('#example').DataTable();
    });

function reporteCierre(idUser) {

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
            url: "exportar_reporte_fema_cierre",
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
                    link.download = 'Reporte_Fema_Cierre.xlsx';
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
                <h3 class="panel-title">Busquedas de FEMA - CIERRE</h3>
            </div>
            <div class="panel-body">
                <form id="frm_llamado_ticket" class="" method="post" action="{{ route('busca_fema_cierre')}}">
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
                                  <a class="btn btn-success form-control input-sm" href="javascript:void(0)" onclick="reporteCierre('{{ Auth::user()->id }}')" >
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
                <h3 class="panel-title" align="CENTER"><strong>LISTA FEMA - CIERRE</strong></h3>
            </div>
            <div class="panel-body">
        @if($variable == 1)
        <div class="col-sm-12" style="border: 1px solid gray; padding-top: 10px; margin-top: 10px">
              <!-- <legend>Historias Clínicas Enviadas a los Servicios</legend> -->
              <div class="table-responsive">
                <table id="example" class="display table" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                        <th>Fecha Apertura</th>
                        <th>Fecha Cierre</th>
                        <th>Tipo Doc</th>
                        <th>Num Doc</th>
                        <th>Apellidos y Nombres</th>
                        <th>Grado</th>
                        <th>Edad</th>
                        <th>Proceso</th>
                        <th>Sub-Proceso</th>
                        <th><center>Ficha Derivacion</center></th>
                        <th><center>Levantar Diagnostico</center></th>
                        <th><center>Concluir Ficha</center></th>
                        <th><center>Constancias</center></th>
                        <th><center>resumen</center></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="col-sm-12" style="border: 1px solid gray; padding-top: 10px; margin-top: 10px">
          <!-- <legend>Historias Clínicas Enviadas a los Servicios</legend> -->
          <div class="table-responsive">
            <table id="example" class="display table" cellspacing="0" width="100%">
              <thead>
                <tr>
                    <th>Fecha Apertura</th>
                    <th>Fecha Cierre</th>
                    <th>Tipo Doc</th>
                    <th>Num Doc</th>
                    <th>Apellidos y Nombres</th>
                    <th>Grado</th>
                    <th>Edad</th>
                    <th>Proceso</th>
                    <th>Sub-Proceso</th>
                    <th><center>Ficha Derivacion</center></th>
                    <th><center>Levantar Diagnostico</center></th>
                    <th><center>Concluir Ficha</center></th>
                    <th><center>Constancia</center></th>
                    <th><center>consolidado</center></th>
                </tr>
              </thead>
              <tbody>
                <?php $i = 1; ?> 
                @foreach($fichas as $f)

                    <tr> 
                        @if($f->sexo == 'M' && $f->validacionfiltro == '1')
                    
                            @if($f->cie10 == '0')  
                                <td>{{date('d/m/Y', strtotime($f->fecha_reg))}} {{$f->hora_reg}}</td>
                                <td><?php  if(!empty($f->fecha_cierre)): ?>{{date('d/m/Y', strtotime($f->fecha_cierre))}} {{$f->hora_cierre}}<?php endif; ?></td>
                                <td>{{$f->tipo_doc_ident}}</td>
                                <td>{{$f->nro_doc_ident}}</td>
                                <td>{{$f->paterno}} {{$f->materno}} {{$f->nombre}}</td>
                                <td>{{$f->grado}}</td>
				                <td>{{$f->edad}}</td>
                                <td>{{$f->proceso}}</td>
                                <td>{{$f->tipo_proceso}}</td>
                                <td></td>
                                <td></td>
                                <td> 
                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cerrar Ficha">
                                    @if($f->fh_reg_cierre>0)
                                    @else
                                        @if($f->edad < 40 )
                                        
                                            @if($f->atencionlaboratorio > 0 and $f->atencionrespiratorio > 0)
                                          
                                                <center><a href="/cierre/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> </a></center>
                                            @else
                                            @endif
                                        @else
                                            @if($f->atencionlaboratorio > 0 and $f->atencionrespiratorio > 0 and $f->atencioncardiologia > 0)
                                                <center><a href="/cierre/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> </a></center>
                                            @else
                                            @endif
                                        @endif    
                                    @endif
                                </div>  
                                </td>
                                <td>
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver PDF">
                                        <center>
                                        @if($f->fh_reg_cierre>0)
                                            <a target="_blank" href="/cierre/verpdfcierre/{{$f->id}}" class="btn btn-sm btn-danger"> <i class="fa fa-file-pdf-o"></i> </a>
                                        @else
                                            <a  href="#" class="btn btn-sm btn-danger" disabled="disabled" > <i class="fa fa-file-pdf-o"></i> </a>
                                        @endif
                                        </center>
                                    </div>
                                </td>
                                <td>
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver PDF Consolidado">
                                        <center>
                                        @if($f->fh_reg_cierre>0)
                                            <a target="_blank" href="/cierre/verpdfconsolidado/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-file-pdf-o"></i> </a>
                                        @else
                                            <a  href="#" class="btn btn-sm btn-danger" disabled="disabled" > <i class="fa fa-file-pdf-o"></i> </a>
                                        @endif
                                        </center>
                                    </div>
                                </td>
                            @else
                                @if($f->yaderivado == '0') 
                                                                               <td>{{date('d/m/Y', strtotime($f->fecha_reg))}} {{$f->hora_reg}}</td>
                                        <td><?php  if(!empty($f->fecha_cierre)): ?>{{date('d/m/Y', strtotime($f->fecha_cierre))}} {{$f->hora_cierre}}<?php endif; ?></td>
                                        <td>{{$f->tipo_doc_ident}}</td>
                                        <td>{{$f->nro_doc_ident}}</td>
                                        <td>{{$f->paterno}} {{$f->materno}} {{$f->nombre}}</td>
                                        <td>{{$f->grado}}</td>
					                    <td>{{$f->edad}}</td>
                                        <td>{{$f->proceso}}</td>
                                        <td>{{$f->tipo_proceso}}</td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Derivaciones">
                                            <center><a href="/verderivacion/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-search"></i></a></center>
                                            </div> 
                                        </td>
                                        <td></td>
                                        <td> 
                                          <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cerrar Ficha">
                                              @if($f->fh_reg_cierre>0)
                                              @else
                                          <center><a href="/cierre/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> </a></center>
                                              @endif
                                          </div>  
                                        </td>    
                                        <td><div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver PDF">
                                                <center>
                                                    @if($f->fh_reg_cierre>0)
                                                        <a target="_blank" href="/cierre/verpdfcierre/{{$f->id}}" class="btn btn-sm btn-danger"> <i class="fa fa-file-pdf-o"></i> </a>
                                                    @else
                                                        <a  href="#" class="btn btn-sm btn-danger" disabled="disabled" > <i class="fa fa-file-pdf-o"></i> </a>
                                                    @endif
                                                </center>
                                                </div> 
                                        </td>
                                        <td><div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver PDF Consolidado">
                                                <center>
                                                    @if($f->fh_reg_cierre>0)
                                                        <a target="_blank" href="/cierre/verpdfconsolidado/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-file-pdf-o"></i> </a>
                                                    @else
                                                        <a  href="#" class="btn btn-sm btn-danger" disabled="disabled" > <i class="fa fa-file-pdf-o"></i> </a>
                                                    @endif
                                                </center>
                                                </div> 
                                        </td>
                                @else 
                                @if($f->tatuaje == '0')
                                        <td>{{date('d/m/Y', strtotime($f->fecha_reg))}} {{$f->hora_reg}}</td>
                                        <td><?php  if(!empty($f->fecha_cierre)): ?>{{date('d/m/Y', strtotime($f->fecha_cierre))}} {{$f->hora_cierre}}<?php endif; ?></td>
                                        <td>{{$f->tipo_doc_ident}}</td>
                                        <td>{{$f->nro_doc_ident}}</td>
                                        <td>{{$f->paterno}} {{$f->materno}} {{$f->nombre}}</td>
                                        <td>{{$f->grado}}</td>
					                    <td>{{$f->edad}}</td>
                                        <td>{{$f->proceso}}</td>
                                        <td>{{$f->tipo_proceso}}</td>
                                        <td>
                                            <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Derivaciones">
                                            <center><a href="/verderivacion/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-search"></i></a></center>
                                            </div> 
                                        </td>
                                        <td></td>
                                        <td> 
                                          <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cerrar Ficha">
                                              @if($f->fh_reg_cierre>0)
                                              @else
                                          <center><a href="/cierre/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> </a></center>
                                              @endif
                                          </div>  
                                        </td>    
                                        <td><div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver PDF">
                                                <center>
                                                    @if($f->fh_reg_cierre>0)
                                                        <a target="_blank" href="/cierre/verpdfcierre/{{$f->id}}" class="btn btn-sm btn-danger"> <i class="fa fa-file-pdf-o"></i> </a>
                                                    @else
                                                        <a  href="#" class="btn btn-sm btn-danger" disabled="disabled" > <i class="fa fa-file-pdf-o"></i> </a>
                                                    @endif
                                                </center>
                                                </div> 
                                        </td>
                                        <td><div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver PDF Consolidado">
                                                <center>
                                                    @if($f->fh_reg_cierre>0)
                                                        <a target="_blank" href="/cierre/verpdfconsolidado/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-file-pdf-o"></i> </a>
                                                    @else
                                                        <a  href="#" class="btn btn-sm btn-danger" disabled="disabled" > <i class="fa fa-file-pdf-o"></i> </a>
                                                    @endif
                                                </center>
                                                </div> 
                                        </td>
                                    @else
                                        @if($f->levantartatuaje == '0')
                                            <td>{{date('d/m/Y', strtotime($f->fecha_reg))}} {{$f->hora_reg}}</td>
                                            <td><?php  if(!empty($f->fecha_cierre)): ?>{{date('d/m/Y', strtotime($f->fecha_cierre))}} {{$f->hora_cierre}}<?php endif; ?></td>
                                            <td>{{$f->tipo_doc_ident}}</td>
                                            <td>{{$f->nro_doc_ident}}</td>
                                            <td>{{$f->paterno}} {{$f->materno}} {{$f->nombre}}</td>
                                            <td>{{$f->grado}}</td>
					                        <td>{{$f->edad}}</td>
                                            <td>{{$f->proceso}}</td>
                                            <td>{{$f->tipo_proceso}}</td>
                                            <td>
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Derivaciones">
                                                <center><a href="/verderivacion/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-search"></i></a></center>
                                                </div> 
                                            </td>
                                            <td>
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Levantar Observaciones">
                                                <center><a href="/levantarobservacion/{{$f->id}}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> </a></center>
                                                </div>
                                            </td>
                                            <td> 
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cerrar Ficha">
                                                    @if($f->fh_reg_cierre>0)
                                                <center><a class="btn btn-sm btn-warning" disabled="disabled" > <i class="fa fa-edit"></i> </a></center>
                                                    @endif
                                                </div>  
                                            </td> 
                                            <td><div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver PDF">
                                                <center>
                                                    @if($f->fh_reg_cierre>0)
                                                        <a target="_blank" href="/cierre/verpdfcierre/{{$f->id}}" class="btn btn-sm btn-danger"> <i class="fa fa-file-pdf-o"></i> </a>
                                                    @else
                                                        <a  href="#" class="btn btn-sm btn-danger" disabled="disabled" > <i class="fa fa-file-pdf-o"></i> </a>
                                                    @endif
                                                </center>
                                                </div> 
                                            </td>
                                            <td><div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver PDF Consolidado">
                                                <center>
                                                    @if($f->fh_reg_cierre>0)
                                                        <a target="_blank" href="/cierre/verpdfconsolidado/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-file-pdf-o"></i> </a>
                                                    @else
                                                        <a  href="#" class="btn btn-sm btn-danger" disabled="disabled" > <i class="fa fa-file-pdf-o"></i> </a>
                                                    @endif
                                                </center>
                                                </div> 
                                            </td>
                                        @else  
                                            <td>{{date('d/m/Y', strtotime($f->fecha_reg))}} {{$f->hora_reg}}</td>
                                            <td><?php  if(!empty($f->fecha_cierre)): ?>{{date('d/m/Y', strtotime($f->fecha_cierre))}} {{$f->hora_cierre}}<?php endif; ?></td>
                                            <td>{{$f->tipo_doc_ident}}</td>
                                            <td>{{$f->nro_doc_ident}}</td>
                                            <td>{{$f->paterno}} {{$f->materno}} {{$f->nombre}}</td>
                                            <td>{{$f->grado}}</td>
					                        <td>{{$f->edad}}</td>
                                            <td>{{$f->proceso}}</td>
                                            <td>{{$f->tipo_proceso}}</td>
                                            <td>
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Derivaciones">
                                                <center><a href="/verderivacion/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-search"></i></a></center>
                                                </div> 
                                            </td>
                                            <td>
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Levantar Observaciones">
                                                <center><a class="btn btn-sm btn-warning" disabled="disabled" ><i class="fa fa-edit"></i> </a></center>
                                                </div>
                                            </td>
                                            <td> 
                                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cerrar Ficha">
                                                    @if($f->fh_reg_cierre>0)
                                                        <center><a class="btn btn-sm btn-warning" disabled="disabled" > <i class="fa fa-edit"></i> </a>
                                                    @else
                                                        <center><a href="/cierre/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> </a>
                                                    @endif
                                                </center>
                                                </div>  
                                            </td> 
                                            <td><div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver PDF">
                                                <center>
                                                    @if($f->fh_reg_cierre>0)
                                                        <a target="_blank" href="/cierre/verpdfcierre/{{$f->id}}" class="btn btn-sm btn-danger"> <i class="fa fa-file-pdf-o"></i> </a>
                                                    @else
                                                        <a  href="#" class="btn btn-sm btn-danger" disabled="disabled" > <i class="fa fa-file-pdf-o"></i> </a>
                                                    @endif
                                                </center>
                                                </div> 
                                            </td>
                                            <td><div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver PDF Consolidado">
                                                <center>
                                                    @if($f->fh_reg_cierre>0)
                                                        <a target="_blank" href="/cierre/verpdfconsolidado/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-file-pdf-o"></i> </a>
                                                    @else
                                                        <a  href="#" class="btn btn-sm btn-danger" disabled="disabled" > <i class="fa fa-file-pdf-o"></i> </a>
                                                    @endif
                                                </center>
                                                </div> 
                                            </td>
                                        @endif
                                    @endif
                                @endif    
                            @endif
                        @elseif($f->sexo == 'F' && $f->validacionfiltro == '1' )
                            @if($f->cie10 == '0') 
                                <td>{{date('d/m/Y', strtotime($f->fecha_reg))}} {{$f->hora_reg}}</td>
                                <td><?php  if(!empty($f->fecha_cierre)): ?>{{date('d/m/Y', strtotime($f->fecha_cierre))}} {{$f->hora_cierre}}<?php endif; ?></td>
                                <td>{{$f->tipo_doc_ident}}</td>
                                <td>{{$f->nro_doc_ident}}</td>
                                <td>{{$f->paterno}} {{$f->materno}} {{$f->nombre}}</td>
                                <td>{{$f->grado}}</td>
				                <td>{{$f->edad}}</td>
                                <td>{{$f->proceso}}</td>
                                <td>{{$f->tipo_proceso}}</td>
                                <td></td>
                                <td></td>
                                <td> 
                                <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cerrar Ficha">
                                @if($f->fh_reg_cierre>0)
                                @else
                                    @if($f->edad < 40 )
                                            @if($f->atencionlaboratorio > 0 and $f->atencionrespiratorio>0)
                                                <center><a href="/cierre/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> </a></center>
                                            @else
                                            @endif
                                    @else
                                            @if($f->atencionlaboratorio > 0 and $f->atencionrespiratorio>0 and $f->atencioncardiologia>0)
                                                <center><a href="/cierre/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> </a></center>
                                            @else
                                            @endif
                                    @endif 
                                @endif
                                </div>  
                                </td>
                                <td>
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver PDF">
                                                <center>
                                                    @if($f->fh_reg_cierre>0)
                                                        <a target="_blank" href="/cierre/verpdfcierre/{{$f->id}}" class="btn btn-sm btn-danger"> <i class="fa fa-file-pdf-o"></i> </a>
                                                    @else
                                                        <a  href="#" class="btn btn-sm btn-danger" disabled="disabled" > <i class="fa fa-file-pdf-o"></i> </a>
                                                    @endif
                                                </center>
                                                </div>
                                             </td>
                                             <td>
                                    <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver PDF Consolidado">
                                                <center>
                                                    @if($f->fh_reg_cierre>0)
                                                        <a target="_blank" href="/cierre/verpdfconsolidado/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-file-pdf-o"></i> </a>
                                                    @else
                                                        <a  href="#" class="btn btn-sm btn-danger" disabled="disabled" > <i class="fa fa-file-pdf-o"></i> </a>
                                                    @endif
                                                </center>
                                                </div>
                                             </td>
                            @else
                                @if($f->yaderivado == '0') 
                                @else 
                                  @if($f->tatuaje == '0') 
                                          <td>{{date('d/m/Y', strtotime($f->fecha_reg))}} {{$f->hora_reg}}</td>
                                          <td><?php  if(!empty($f->fecha_cierre)): ?>{{date('d/m/Y', strtotime($f->fecha_cierre))}} {{$f->hora_cierre}}<?php endif; ?></td>
                                          <td>{{$f->tipo_doc_ident}}</td>
                                          <td>{{$f->nro_doc_ident}}</td>
                                          <td>{{$f->paterno}} {{$f->materno}} {{$f->nombre}}</td>
                                          <td>{{$f->grado}}</td>
					                      <td>{{$f->edad}}</td>
                                          <td>{{$f->proceso}}</td>
                                          <td>{{$f->tipo_proceso}}</td>
                                          <td>
                                              <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Derivaciones">
                                              <center><a href="/verderivacion/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-search"></i></a></center>
                                              </div> 
                                          </td>
                                          <td></td>
                                          <td> 
                                          <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cerrar Ficha">
                                              @if($f->fh_reg_cierre>0)
                                              @else
                                          <center><a href="/cierre/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> </a></center>
                                              @endif
                                          </div>  
                                          </td> 
                                          <td><div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver PDF">
                                                  <center>
                                                      @if($f->fh_reg_cierre>0)
                                                          <a target="_blank" href="/cierre/verpdfcierre/{{$f->id}}" class="btn btn-sm btn-danger"> <i class="fa fa-file-pdf-o"></i> </a>
                                                      @else
                                                          <a  href="#" class="btn btn-sm btn-danger" disabled="disabled" > <i class="fa fa-file-pdf-o"></i> </a>
                                                      @endif
                                                  </center>
                                                  </div> 
                                          </td>
                                          <td><div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver PDF Consolidado">
                                                  <center>
                                                      @if($f->fh_reg_cierre>0)
                                                          <a target="_blank" href="/cierre/verpdfconsolidado/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-file-pdf-o"></i> </a>
                                                      @else
                                                          <a  href="#" class="btn btn-sm btn-danger" disabled="disabled" > <i class="fa fa-file-pdf-o"></i> </a>
                                                      @endif
                                                  </center>
                                                  </div> 
                                          </td>
                                      @else
                                          @if($f->levantartatuaje == '0')
                                              <td>{{date('d/m/Y', strtotime($f->fecha_reg))}} {{$f->hora_reg}}</td>
                                              <td><?php  if(!empty($f->fecha_cierre)): ?>{{date('d/m/Y', strtotime($f->fecha_cierre))}} {{$f->hora_cierre}}<?php endif; ?></td>
                                              <td>{{$f->tipo_doc_ident}}</td>
                                              <td>{{$f->nro_doc_ident}}</td>
                                              <td>{{$f->paterno}} {{$f->materno}} {{$f->nombre}}</td>
                                              <td>{{$f->grado}}</td>
					                          <td>{{$f->edad}}</td>
                                              <td>{{$f->proceso}}</td>
                                              <td>{{$f->tipo_proceso}}</td>
                                              <td>
                                                  <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Derivaciones">
                                                  <center><a href="/verderivacion/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-search"></i></a></center>
                                                  </div> 
                                              </td>
                                              <td>
                                                  <div data-toggle="tooltip" data-placement="top" data-html="true" title="Levantar Observaciones">
                                                  <center><a href="/levantarobservacion/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> </a></center>
                                                  </div>
                                              </td>
                                              <td> 
                                                  <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cerrar Ficha">
                                                      @if($f->fh_reg_cierre>0)
                                                  <center><a class="btn btn-sm btn-warning" disabled="disabled" > <i class="fa fa-edit"></i> </a></center>
                                                      @endif
                                                  </div>  
                                              </td> 
                                              <td><div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver PDF">
                                                  <center>
                                                      @if($f->fh_reg_cierre>0)
                                                          <a target="_blank" href="/cierre/verpdfcierre/{{$f->id}}" class="btn btn-sm btn-danger"> <i class="fa fa-file-pdf-o"></i> </a>
                                                      @else
                                                          <a  href="#" class="btn btn-sm btn-danger" disabled="disabled" > <i class="fa fa-file-pdf-o"></i> </a>
                                                      @endif
                                                  </center>
                                                  </div> 
                                              </td>
                                              <td><div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver PDF Consolidado">
                                                  <center>
                                                      @if($f->fh_reg_cierre>0)
                                                          <a target="_blank" href="/cierre/verpdfconsolidado/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-file-pdf-o"></i> </a>
                                                      @else
                                                          <a  href="#" class="btn btn-sm btn-danger" disabled="disabled" > <i class="fa fa-file-pdf-o"></i> </a>
                                                      @endif
                                                  </center>
                                                  </div> 
                                              </td>
                                          @else
                                              <td>{{date('d/m/Y', strtotime($f->fecha_reg))}} {{$f->hora_reg}}</td>
                                              <td><?php  if(!empty($f->fecha_cierre)): ?>{{date('d/m/Y', strtotime($f->fecha_cierre))}} {{$f->hora_cierre}}<?php endif; ?></td>
                                              <td>{{$f->tipo_doc_ident}}</td>
                                              <td>{{$f->nro_doc_ident}}</td>
                                              <td>{{$f->paterno}} {{$f->materno}} {{$f->nombre}}</td>
                                              <td>{{$f->grado}}</td>
					                          <td>{{$f->edad}}</td>
                                              <td>{{$f->proceso}}</td>
                                              <td>{{$f->tipo_proceso}}</td>
                                              <td>
                                                  <div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver Derivaciones">
                                                  <center><a href="/verderivacion/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-search"></i></a></center>
                                                  </div> 
                                              </td>
                                              <td>
                                                  <div data-toggle="tooltip" data-placement="top" data-html="true" title="Levantar Observaciones">
                                                  <center><a class="btn btn-sm btn-warning" disabled="disabled" ><i class="fa fa-edit"></i></a></center>
                                                  </div>
                                              </td>
                                              <td> 
                                                  <div data-toggle="tooltip" data-placement="top" data-html="true" title="Cerrar Ficha">
                                                    @if($f->fh_reg_cierre>0)
                                                        <center><a class="btn btn-sm btn-warning" disabled="disabled" > <i class="fa fa-edit"></i> </a>
                                                    @else
                                                        <center><a href="/cierre/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> </a>
                                                    @endif
                                                  </div>  
                                              </td> 
                                              <td><div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver PDF">
                                                  <center>
                                                      @if($f->fh_reg_cierre>0)
                                                          <a target="_blank" href="/cierre/verpdfcierre/{{$f->id}}" class="btn btn-sm btn-danger"> <i class="fa fa-file-pdf-o"></i> </a>
                                                      @else
                                                          <a  href="#" class="btn btn-sm btn-danger" disabled="disabled" > <i class="fa fa-file-pdf-o"></i> </a>
                                                      @endif
                                                  </center>
                                                  </div> 
                                              </td>
                                              <td><div data-toggle="tooltip" data-placement="top" data-html="true" title="Ver PDF Consolidado">
                                                  <center>
                                                      @if($f->fh_reg_cierre>0)
                                                          <a target="_blank" href="/cierre/verpdfconsolidado/{{$f->id}}" class="btn btn-sm btn-primary"> <i class="fa fa-file-pdf-o"></i> </a>
                                                      @else
                                                          <a  href="#" class="btn btn-sm btn-danger" disabled="disabled" > <i class="fa fa-file-pdf-o"></i> </a>
                                                      @endif
                                                  </center>
                                                  </div> 
                                              </td>
                                          @endif
                                      @endif
                                @endif    
                            @endif
                        @else                      
                        @endif
                    </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        </div>
        </div>
        @endif  
      </div>
</div>
<div id="link-reporte"></div>
@include('frontend.includes.footer')
@endsection
