@extends('frontend.layouts.master2')
@section('renderjs')
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">{!!$nombre_establecimiento!!}</h3>
                 <br/>
        <a href="/listar_archivos_can/{{$can_id}}/{{$establecimiento_id}}" class='btn btn-info'><i class="glyphicon glyphicon-hand-left"></i> Regresar</a>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div>
                            <div class="box-header">
                                    <h3 class="box-title">ARCHIVOS</h3>
                            </div>

                            <div class="box-body">
                                <div class="row col-sm-12">
                                     {!! Form::model($archivos, ['route' => ['subir_archivo_correccion'], 'method' => 'post','enctype'=>"multipart/form-data"]) !!}
                                     <div class="form-group col-sm-12">
                                        <!-- Establecimiento Field -->
                                        <input type="hidden" name="can_id" value="<?php echo $can_id ?>">
                                        <input type="hidden" name="establecimiento_id" value="<?php echo $establecimiento_id ?>">
                                        <div class="form-group col-sm-6">
                                            {!! Form::label('descripcion', 'Descripcion del Archivo:') !!}
                                            {!! Form::text('descripcion', null, ['id'=>'descripcion','name'=>'descripcion','class' => 'form-control','required'=>'required']) !!}
                                        </div>
                                        <div class="form-group col-sm-6">
                                            {!! Form::label('photo', 'Extensiones permitidas(doc,docx,pdf), archivo Max. 10MB:') !!}                            <input id="photo" name="photo" accept="tipo_de_archivo|image/*|media_type" name="archivo" type="file" value="" required="required" />
                                            <span class="help-block with-errors"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-sm-12">
                                            {!! Form::submit('Subir Archivo', ['class' => 'btn btn-success ']) !!}
                                    </div>
                                     {!! Form::close() !!}
                                </div>
                            </div>
                        </div>        
                    </div>    
                </div>
            </div>
        </div>
    </div>

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
            case 'doc':
            case 'docx':
            case 'xls':
            case 'xlsx':
            case 'PDF':
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
@include('frontend.includes.footer')
@endsection