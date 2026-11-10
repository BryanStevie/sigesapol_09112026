@extends('frontend.layouts.master')
@section('renderjs')
<style>
    #login { display: none; }
    .login,
    .logout { 
        position: absolute; 
        top: -3px;
        right: 0;
    }
    .page-header { position: relative; }
    .reviews {
        color: #555;    
        font-weight: bold;
        margin: 10px auto 20px;
    }
    .notes {
        color: #999;
        font-size: 12px;
    }
    .media .media-object { max-width: 120px; }
    .media-body { position: relative; }
    .media-date { 
        position: absolute; 
        right: 25px;
        top: 25px;
    }
    .media-date li { padding: 0; }
    .media-date li:first-child:before { content: ''; }
    .media-date li:before { 
        content: '.'; 
        margin-left: -2px; 
        margin-right: 2px;
    }
    .media-comment { margin-bottom: 20px; }
    .media-replied { margin: 0 0 20px 50px; }
    .media-replied .media-heading { padding-left: 6px; }

    .btn-circle {
        font-weight: bold;
        font-size: 12px;
        padding: 6px 15px;
        border-radius: 20px;
    }
    .btn-circle span { padding-right: 6px; }
    .embed-responsive { margin-bottom: 20px; }
    .tab-content {
        padding: 50px 15px;
        border: 1px solid #ddd;
        border-top: 0;
        border-bottom-right-radius: 4px;
        border-bottom-left-radius: 4px;
    }
    .custom-input-file {
        overflow: hidden;
        position: relative;
        width: 120px;
        height: 120px;
        background: #eee url('https://s3.amazonaws.com/uifaces/faces/twitter/walterstephanie/128.jpg');    
        background-size: 120px;
        border-radius: 120px;
    }
    input[type="file"]{
        z-index: 999;
        line-height: 0;
        font-size: 0;
        position: absolute;
        opacity: 0;
        filter: alpha(opacity = 0);-ms-filter: "alpha(opacity=0)";
        margin: 0;
        padding:0;
        left:0;
    }
    .uploadPhoto {
        position: absolute;
        top: 25%;
        left: 25%;
        display: none;
        width: 50%;
        height: 50%;
        color: #fff;    
        text-align: center;
        line-height: 60px;
        text-transform: uppercase;    
        background-color: rgba(0,0,0,.3);
        border-radius: 50px;
        cursor: pointer;
    }
    .custom-input-file:hover .uploadPhoto { display: block; }

</style>
<script>
    $(function () {

        $('#login-form-link').click(function (e) {
            $("#login-form").delay(100).fadeIn(100);
            $("#register-form").fadeOut(100);
            $('#register-form-link').removeClass('active');
            $(this).addClass('active');
            e.preventDefault();
        });
        $('#register-form-link').click(function (e) {
            $("#register-form").delay(100).fadeIn(100);
            $("#login-form").fadeOut(100);
            $('#login-form-link').removeClass('active');
            $(this).addClass('active');
            e.preventDefault();
        });

    });

</script>
@endsection
@section('content')
<div class="container">   
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Buscar Receta</h3>
        </div>
        <div class="comment-tabs" style="margin:15px 15px 15px 15px">
            <ul class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#comments-logout" role="tab" data-toggle="tab"><h4 class="reviews text-capitalize">DNI</h4></a></li>
                <li><a href="#add-comment" role="tab" data-toggle="tab"><h4 class="reviews text-capitalize">RUA</h4></a></li>
            </ul>            
            <div class="tab-content">
                <div class="tab-pane active" id="comments-logout">                
                    <form action="{{ route('add_recetas_by_paciente')}}" method="post" class="form-horizontal" id="commentForm" role="form"> 
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <div class="col-sm-5">                    
                                <input type="text" name="dni" id="dni" tabindex="1" class="form-control" placeholder="DNI del paciente" value="" maxlength="8">
                                <input type="hidden" name="user" id="user" value="{{$usuario}}">
                                <input type="hidden" name="farmacia" id="farmacia" value="{{$farmacia->id}}">  
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-0 col-sm-10">                    
                                <input class="btn btn-success btn-circle text-uppercase" type="submit" id="submitComment" value="Atender">
                            </div>
                        </div>            
                    </form>
                </div>
                <div class="tab-pane" id="add-comment">
                    <form action="{{ route('add_receta')}}" method="post" class="form-horizontal" id="commentForm" role="form"> 
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div >
                            <div style="float:left; margin-bottom: 10px">
                                <select style="width: 200px" required="">
                                    <option value="1">Registro Único de Atención</option>
                                </select> -
                                <input type="text" name="serie" id="serie" placeholder="Serie" value="" style="width: 50px" required=""> -
                                <input type="text" name="nro_ticket" id="nro_ticket" placeholder="Ticket" value="" style="width: 50px" required="">
                                <input type="hidden" name="user" id="user" value="{{$usuario}}">
                                <input type="hidden" name="farmacia" id="farmacia" value="{{$farmacia->id}}">                                    
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-0 col-sm-10">                    
                                <input class="btn btn-success btn-circle text-uppercase" type="submit" id="submitComment" value="Atender">
                            </div>
                        </div>            
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection