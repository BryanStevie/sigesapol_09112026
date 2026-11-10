@extends('frontend.layouts.master')
@section('renderjs')
<script>
    $cmbUni = $("#frmcrear_prod").find("#id_unidad");
    $cmbUni.select2();
</script>
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
                                <tr>
                                    <td>
                                        <span class="glyphicon glyphicon-plus text-primary"></span>
                                        <a href="/nueva_receta/{{ Auth::user()->id }}/{{ Auth::user()->id_farmacia }}"> Nueva Receta</a>
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
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-9 col-md-9">
            <div class="row">
                <div class="panel panel-primary">            
                    <div class="panel-heading" style="margin-bottom: 20px">
                        <h3 class="panel-title">Recetas Atendidas</h3>                
                    </div> 
                    <div class="panel-body">
                        <legend style="text-align: center">{{$farmacia->nombre}}</legend>
                        @if(count($recetas)>0)
                        <div class="col-md-12">
                            <legend>Beneficiario: {{$beneficiario[0]->nomafiliado}} {{$beneficiario[0]->apepatafiliado}} {{$beneficiario[0]->apematafiliado}}</legend>                              
                        </div>   
                        <table class="table " style="font-size: 13px !important;" >
                            <thead>
                                <tr>
                                    <th>N</th>
                                    <th>Nro. Receta</th>
                                    <th>DNI</th>
                                    <th>Beneficiario</th>
                                    <th>Tipo Beneficiario</th>
                                    <th>Fecha Atención</th>
                                    <th>Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $n = 1; ?>
                                @foreach($recetas as $r) 
                                <tr>
                                    <td><?php echo $n++; ?></td>
                                    <td>{{$r->nro_receta}}</td>
                                    <td>{{$r->dni_beneficiario}}</td>
                                    <td>{{$r->nombre_beneficiario}} {{$r->paterno_beneficiario}} {{$r->materno_beneficiario}}</td>
                                    <td>{{$r->tipo_beneficiario}}</td>
                                    <td>
                                        <?php
                                        $originalDate2 = $r->fecha_registro;
                                        $fecha = date("d-m-Y", strtotime($originalDate2));
                                        echo $fecha;
                                        ?> 
                                    </td>
                                    <td>
                                        <a href="/ver_receta_atendida/{{ Auth::user()->id }}/{{$r->nro_receta}}" class="btn btn-success btn-xs"><i class="fa fa-search"></i></a>
                                    </td>                                                                
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="col-md-12">
                            "El Beneficiario no cuenta con recetas atendidas".
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection