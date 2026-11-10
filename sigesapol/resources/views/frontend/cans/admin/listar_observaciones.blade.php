@extends('frontend.layouts.master2')
<style>
 .bg-blue {
      color: #fff;
      background-color: #0073b7;
      border-color: #46b8da;
    }
</style>
@section('content')
<div class="content">
  <div class="clearfix"></div>
    <div class="container-fluid">
        <div class="row">
            <a href="/all_cans/" class='btn btn-info'><i class="glyphicon glyphicon-hand-left"></i> Regresar</a><br/><br/>
            <div class="panel panel-primary filterable">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$nombre_establecimiento}} </h3>
                </div>
                <div class="panel-body">
                    <div class="clearfix"></div>
                    @if($nivel==1)
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box box-danger">
                                    <div class="box-body">
                                        <div class="box-header">
                                            <h3 class="box-title">Observaciones</h3>
                                        </div>
                                        <div class="box-body">
                                            <table id="example" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Descripcion de la Observacion</th>
                                                        <th>Observaciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($observaciones as $key => $observ)
                                                    <tr>
                                                        <td>{{$key+1}}</td>                            
                                                        <td>{!! $observ->descripcion_archivo !!}</td>
                                                        <td>
                                                            <?php 
                                                                if($observ->extension_archivo=='pdf'){
                                                                    $imagen='fa fa-file-pdf-o';
                                                                    $color='bg-red';
                                                                }else
                                                                {   if($observ->extension_archivo=='xls' || $observ->extension_archivo=='xlsx'){
                                                                        $imagen='fa-file-excel-o';
                                                                        $color='bg-green';
                                                                    }
                                                                    else
                                                                    {
                                                                        if($observ->extension_archivo=='doc' || $observ->extension_archivo=='docx'){
                                                                            $imagen='fa-file-word-o';
                                                                            $color='bg-blue';
                                                                        }
                                                                        else
                                                                        {
                                                                            if($observ->extension_archivo=='jpg' || $observ->extension_archivo=='gif' || $observ->extension_archivo=='jpeg' || $observ->extension_archivo=='png' || $observ->extension_archivo=='svg' || $observ->extension_archivo=='eps' || $observ->extension_archivo=='psd' ){
                                                                                $imagen='fa-file-image-o';
                                                                                $color='bg-purple';
                                                                            }
                                                                            else
                                                                            {
                                                                                $imagen='fa-archive';
                                                                                $color='bg-orange';      
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            ?>
                                                            <a data-toggle="tooltip" title="<?php echo $observ->descripcion_archivo?>" target="_blank" href='{{ asset ("$observ->descarga_archivo") }}' class='btn <?php echo $color; ?>'><i class="fa <?php echo $imagen; ?>"></i></a>
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
                    @else
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box box-danger">
                                    <div class="box-body">
                                        <div class="box-header">
                                            <h3 class="box-title">Observaciones</h3>
                                        </div>
                                        <div class="box-body">
                                            <table id="example" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Descripcion de la Observacion</th>
                                                        <th>Observaciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($observaciones as $key => $observ)
                                                    <tr>
                                                        <td>{{$key+1}}</td>                            
                                                        <td>{!! $observ->descripcion_archivo !!}</td>
                                                        <td>
                                                            <?php 
                                                                if($observ->extension_archivo=='pdf'){
                                                                    $imagen='fa fa-file-pdf-o';
                                                                    $color='bg-red';
                                                                }else
                                                                {   if($observ->extension_archivo=='xls' || $observ->extension_archivo=='xlsx'){
                                                                        $imagen='fa-file-excel-o';
                                                                        $color='bg-green';
                                                                    }
                                                                    else
                                                                    {
                                                                        if($observ->extension_archivo=='doc' || $observ->extension_archivo=='docx'){
                                                                            $imagen='fa-file-word-o';
                                                                            $color='bg-blue';
                                                                        }
                                                                        else
                                                                        {
                                                                            if($observ->extension_archivo=='jpg' || $observ->extension_archivo=='gif' || $observ->extension_archivo=='jpeg' || $observ->extension_archivo=='png' || $observ->extension_archivo=='svg' || $observ->extension_archivo=='eps' || $observ->extension_archivo=='psd' ){
                                                                                $imagen='fa-file-image-o';
                                                                                $color='bg-purple';
                                                                            }
                                                                            else
                                                                            {
                                                                                $imagen='fa-archive';
                                                                                $color='bg-orange';      
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            ?>
                                                            <a data-toggle="tooltip" title="<?php echo $observ->descripcion_archivo?>" target="_blank" href='{{ asset ("$observ->descarga_archivo") }}' class='btn <?php echo $color; ?>'><i class="fa <?php echo $imagen; ?>"></i></a>
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
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).on('change','input[type="file"]',function(){
    // this.files[0].size recupera el tamaño del archivo
    // alert(this.files[0].size);
    
    var fileName = this.files[0].name;
    var fileSize = this.files[0].size;

    if(fileSize > 10000000){
        alert('El archivo no debe superar los 10MB');
        this.value = '';
        this.files[0].name = '';
    }else{
        // recuperamos la extensión del archivo
        var ext = fileName.split('.').pop();

        // console.log(ext);
        switch (ext) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'doc':
            case 'docx':
            case 'xls':
            case 'xlsx':
            case 'pdf': break;
            default:
                alert('El archivo no tiene la extensión adecuada');
                this.value = ''; // reset del valor
                this.files[0].name = '';
        }
    }
});
</script>
@stop

