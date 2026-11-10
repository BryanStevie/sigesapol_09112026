@extends('frontend.layouts.master')

@section('content')
<div class="row">

    <div class="col-md-10 col-md-offset-1">

        <div class="panel panel-primary">
            <div class="panel-heading">Dashboard</div>

            <div class="panel-body">
                <div class="row">
                    <!-- ./col -->
                    <div class="col-lg-4 col-xs-6">
                      <!-- small box -->
                      <div class="panel panel-danger">
                        <div class="panel-heading">Productos Criticos</div>
                        <div class="panel-body">En esta opcion se mostraran los productos que estan por vencerse y necesitan estar abastecido lo mas prodPanel Content
                            <br/>
                            <a href="{!!url('famarcia_cpm/'.Auth::user()->id.'/4')!!}" class="small-box-footer">Ver Información <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                      </div>
                    </div>        
                    <!-- ./col -->
                    <div class="col-lg-4 col-xs-6">
                      <!-- small box -->
                      <div class="panel panel-danger">
                        <div class="panel-heading">Productos Desabastecido</div>
                        <div class="panel-body">Panel Content
                            <br/>
                            <br/>
                            <a href="{!!url('famarcia_cpm/'.Auth::user()->id.'/1')!!}" class="small-box-footer">Ver Información <i class="fa fa-arrow-circle-right"></i></a>
                            
                        </div>
                      </div>
                    </div>  
                    <!-- ./col -->
                    <div class="col-lg-4 col-xs-6">
                      <!-- small box -->
                      <div class="panel panel-danger">
                        <div class="panel-heading">Productos Próximos a Vencer</div>
                        <div class="panel-body">Visualizar los productos que estar por vencer
                            <br/><br/>
                            <a href="{!!url('productos_proximos_vencer/'.Auth::user()->id_farmacia)!!}" class="small-box-footer">Ver Información <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                      </div>
                    </div>  
                    <!-- ./col -->
                    <div class="col-lg-4 col-xs-6">
                      <!-- small box -->
                      <div class="panel panel-danger">
                        <div class="panel-heading">Productos Vencidos</div>
                        <div class="panel-body">Visualizar los productos vencidos
                            <br/><br/>
                            <a href="{!!url('productos_vencidos/'.Auth::user()->id_farmacia)!!}" class="small-box-footer">Ver Información <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                      </div>                    
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-4 col-xs-6">
                      <!-- small box -->
                      <div class="panel panel-danger">
                        <div class="panel-heading">Productos Nuevos Ingresados</div>
                        <div class="panel-body">Panel Content
                            <br/>
                            <a href="{!!url('nuevos_registros_sb')!!}" class="small-box-footer">Ver Información <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                      </div>
                    </div>  
                </div> 

            </div><!--panel body-->

        </div><!-- panel -->

    </div><!-- col-md-10 -->

</div><!-- row -->
@include('frontend.includes.footer')
@endsection
