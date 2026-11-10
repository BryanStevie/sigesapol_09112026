@extends('frontend.layouts.master')
@section('renderjs')
<style>
    .row{
        margin-top:40px;
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
    #squarespaceModal .modal-dialog{
        width: 1200px;
    }
</style>

<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
    
    $(document).ready(function () {
        $('#example2').DataTable({            
            "info": false
        });
    });
    
    
    
    $(function () {
     
    });
});
</script>
@endsection
@section('content')

<?php
/*
$txtfechaDesde_=explode("-",$fecha_inicio);
$txtfechaHasta_=explode("-",$fecha_fin);

$fechaIni=$txtfechaDesde_[2]."/".$txtfechaDesde_[1]."/01"; 
$fechaFin=$txtfechaHasta_[2]."/".$txtfechaHasta_[1]."/01";

$mes = date("m", strtotime($fechaIni));
$ano=  date("Y", strtotime($fechaIni));

$limite=$txtfechaHasta_[1].'/'.$txtfechaHasta_[2];
$nuevo=0;

$arrayMeses=array();
$arrayMeses[]=$mes."/".$ano;
*/

?>

<div class="container">    
    <div class="row">

        <div class="col-md-9">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Lista de Beneficiarios con Receta</h3>
					<div class="pull-right">                        
                        <div class="btn-group">
							<button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">Nuevo Reporte
							<span class="caret"></span>
							</button>
							
							<ul class="dropdown-menu" role="menu">
								<?php foreach($menu as $rowMenu):?>
									<?php if($rowMenu->lectura=='1'):?>
									<li><a href="<?php echo $rowMenu->url."/".$id_farmacia?>"><?php echo $rowMenu->nombremenu?></a></li>
									<?php endif;?>
								<?php endforeach;?>
							</ul>
						</div>
                    </div>
                </div>
				
				@if(count($recetas)>0)
                
                <table id="example" class="table" cellspacing="0" width="100%" style="font-size: 11px">
                    <thead>
                        <tr>
							<th>#</th>
                            <th>DNI</th>
                            <th>Beneficiario</th>
							<th>Tipo Beneficiario</th>
                            <th>Sexo</th>
                            <th>Edad</th>
							<th>Numero de Receta</th>
							<th>Fecha de Receta</th>
							<th>Servicio</th>
							<!--<th>Importe Total</th>-->
                        </tr>
                    </thead>
                    
                    <tbody>
                        
                        
                    <?php 
                    $n=0; 
					$cantidadTotal = 0;
					$importe_total = 0;
					?>
                        @foreach($recetas as $f)
						<?php 
						//$importe_total += $f->importe;
						//$sexoafiliado="";
						//$edadafiliado="";
						//$beneficiario = App\Models\Receta_vale::ShowbyDniBeneficiario($f->dni_beneficiario);
						//if(isset($beneficiario[0]->sexoafiliado))$sexoafiliado=$beneficiario[0]->sexoafiliado;
						//if(isset($beneficiario[0]->edadafiliado))$edadafiliado=$beneficiario[0]->edadafiliado;
						?>
						
                        <tr>
							<td><?php $n++; echo $n ?></td>
                            <td>{{$f->dni_beneficiario}}</td>
                            <td>{{$f->paterno_beneficiario." ".$f->materno_beneficiario.", ".$f->nombre_beneficiario}}</td>
							<td>{{$f->tipo_beneficiario}}</td>
							<td>{{$f->sexoafiliado}}</td>
							<td>{{$f->edadafiliado}}</td>
                            <td>{{$f->nro_receta}}</td>
							<td>{{$f->fecha_expedicion}}</td>
                            <td>{{$f->servicio}}</td>
							<!-- number_format($f->importe, 6, '.', ',')-->
                        </tr>
                        @endforeach
                    </tbody>
					<tfoot>
						<tr>
							<td colspan="5">&nbsp;</td>
                            <td align="right">&nbsp;</td>
							<td>&nbsp;</td>
							<td align="right"><?php //echo number_format($importe_total, 6, '.', ',') ?></td>
						</tr>
					</tfoot>
                </table>
                @else                                         
                <div class="alert alert-warning" style="margin: 10px 10px 10px 10px">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <strong>Información</strong> no aplicable.
                </div>
                @endif
                
                
                <div style="margin: 10px 10px 10px 10px; border-top: 1px solid gray">
                    <div id="container" style="min-width: 300px; height: 400px; margin: 0 auto"></div>
                </div>
            </div>
        </div>
        
		
		<div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$nombre_farmacia}}</h3>
                </div>
                <div class="panel-body"  style="display: block;">
                    @if(count($recetas)>0)
                    <div class="col-md-12" style="margin-bottom: 10px">Lista de Beneficiarios con Receta: <?php echo $n ?></div>                    
                    <div class="col-md-12" style="margin-bottom: 10px">
                        <a class="btn btn-success form-control" href="/exportar_lista_beneficiario/{{$fecha_inicio}}/{{$fecha_fin}}/{{$id_farmacia}}">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar Reporte
                        </a>                        
                    </div>
                    <!--<div class="col-md-12" style="margin-bottom: 10px">
                          <a class="btn btn-default btn-xs" data-toggle="modal" data-target="#squarespaceModal"  href="">Ver historial de diagnosticos</a>
                    </div>-->
                    @else                                         
                    <div class="alert alert-warning" style="margin: 10px 10px 10px 10px">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <strong>Información</strong> no aplicable.
                    </div>
                    @endif
                </div>                
            </div>
        </div>
		
		 
     
@endsection
