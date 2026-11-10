@extends('frontend.layouts.master')
@section('renderjs')
<style>

</style>

<script>

</script>
@endsection
@section('content')
<div class="container">    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
				
					<?php
					
					$nombre_farmacia = 0;
					$farmacia_id = 0;
						
					if($farmacia):
						$nombre_farmacia = $farmacia->nombre;
						$farmacia_id = $farmacia->id;
					endif;
					
					?>
				
                    <h3 class="panel-title">Personal Usuario del Sistema: <?php if($nombre_farmacia!=0):?>{{$nombre_farmacia}}<?php endif;?></h3>
                    <div class="pull-right"> 
                        <!--<a class="btn btn-danger btn-xs">Descargar PDF</a>-->
                        <a class="btn btn-success btn-xs" href="/usuarios_por_ipress_excel/{{$farmacia_id}}/{{$nombre_farmacia}}">Descargar Excel</a>
                        <a class="btb btn-default btn-xs" href="/all_usuarios">
                            <i class="fa fa-arrow-left"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    @if(count($usuarios)>0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>N</th>
                                <th>DNI</th>
                                <th>Nombre y Apellidos</th>
                                <th>E-mail</th>
                                <th>Celular</th>
                                <th>Farmacia</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n=0;?>
                            @foreach($usuarios as $u)
                            <tr>
                                <td><?php $n++; echo $n ?></td>
                                <td>{{$u->dni}}</td>
                                <td>{{$u->name}} {{$u->paterno}} {{$u->materno}}</td>
                                <td>{{$u->email}}</td>
                                <td>{{$u->celular}}</td>
                                <td>{{$u->nombre_farmacia}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p>No existe usuarios creado en la {{$nombre_farmacia}} </p>
                    @endif
                </div>
            </div>
        </div>       
    </div>
</div>
@include('frontend.includes.footer')
@endsection
