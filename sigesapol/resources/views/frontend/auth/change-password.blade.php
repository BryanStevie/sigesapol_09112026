@extends('frontend.layouts.master')
<link rel="stylesheet" type="text/css" media="all" href="style.css" />   
<style>

#pswd_info {
 position:absolute;
 bottom:-75px;
 bottom: -115px\9; /* IE Specific */
 right:55px;
 width:250px;
 padding:15px;
 background:#fefefe;
 font-size:.875em;
 border-radius:5px;
 box-shadow:0 1px 3px #ccc;
 border:1px solid #ddd;
}

#pswd_info h4 {
 margin:0 0 10px 0;
 padding:0;
 font-weight:normal;
}

#pswd_info::before {
 content: "\25B2";
 position:absolute;
 top:-12px;
 left:45%;
 font-size:14px;
 line-height:14px;
 color:#ddd;
 text-shadow:none;
 display:block;
}

.invalid {
 background:url(../../images/invalid.png) no-repeat 0 10%;
 padding-left:22px;
 line-height:24px;
 color:#ec3f41;
}
.valid {
 background:url(../../images/valid.png) no-repeat 0 50%;
 padding-left:22px;
 line-height:24px;
 color:#3a7d34;
}

#pswd_info {
 display:none;
}

</style>
<script src="https://code.jquery.com/jquery-1.7.min.js"></script>
<script>


$(document).ready(function() {
    $('#boton').hide();
    $('input[name=password]').keyup(function() {
        // set password variable
        var pswd = $(this).val();
        var contador = 1; 
        //validate the length
        if ( pswd.length < 10 ) {
            $('#length').removeClass('valid').addClass('invalid');
        } else {
            $('#length').removeClass('invalid').addClass('valid');
            contador++;
        }

        //validate letter
        if ( pswd.match(/[a-z]/) ) {
            $('#letter').removeClass('invalid').addClass('valid');
            contador++;
        } else {
            $('#letter').removeClass('valid').addClass('invalid');
            
        }

        //validate capital letter
        if ( pswd.match(/[A-Z]/) ) {
            $('#capital').removeClass('invalid').addClass('valid');
            contador++;
        } else {
            $('#capital').removeClass('valid').addClass('invalid');
            
        }

        //validate number
        if ( pswd.match(/\d/) ) {
            $('#number').removeClass('invalid').addClass('valid');
            contador++;
        } else {
            $('#number').removeClass('valid').addClass('invalid');
            
        }

        //validate number
        if ( pswd.match(/[^\w\d\s:]/) ) {
            $('#especial').removeClass('invalid').addClass('valid');
            contador++;
        } else {
            $('#especial').removeClass('valid').addClass('invalid');
            
        }
        
        if(contador>5){
          $('#boton').show();  
        }
        else{
          $('#boton').hide();
        }

    }).focus(function() {
        $('#pswd_info').show();
    }).blur(function() {
        $('#pswd_info').hide();
    });

});
</script>
@section('content')
    <script src="https://kit.fontawesome.com/397c168af6.js" crossorigin="anonymous"></script>
	<div class="row">

		<div class="col-md-10 col-md-offset-1">

			<div class="panel panel-default">
				<div class="panel-heading">{{ trans('labels.change_password_box_title') }}</div>

				<div class="panel-body">

                       {!! Form::open(['route' => ['password.change'], 'class' => 'form-horizontal']) !!}
                       <div class="col-md-8">
                              <div class="form-group">
                                  {!! Form::label('old_password', trans('validation.attributes.old_password'), ['class' => 'col-md-4 control-label']) !!}
                                  <div class="col-md-6">
                                <div class="input_and_button">
                                      {!! Form::input('password', 'old_password', null, ['class' => 'form-control  input-pass']) !!}
                                                        <button type="button" id="btnShowOldPass" class="btn-view-pass"><span><i
                                                class="fa-solid fa-eye-slash"></i></span></button>
                                </div>
				</div>
                              </div>

                              <div class="form-group">
                                  {!! Form::label('password', trans('validation.attributes.new_password'), ['class' => 'col-md-4 control-label']) !!}
                                  <div class="col-md-6">
                                <div class="input_and_button">
                                      {!! Form::input('password', 'password', null, ['class' => 'form-control  input-pass']) !!}
                                    <button type="button" id="btnShowNewPass" class="btn-view-pass"><span><i
                                                class="fa-solid fa-eye-slash"></i></span></button>
                                </div>
                            </div>
                        </div>
                              <div class="form-group">
                                  {!! Form::label('password_confirmation', trans('validation.attributes.new_password_confirmation'), ['class' => 'col-md-4 control-label']) !!}
                                  <div class="col-md-6">
                                <div class="input_and_button">
                                      {!! Form::input('password', 'password_confirmation', null, ['class' => 'form-control input-pass']) !!}
                                    <button type="button" id="btnShowConfirmPass" class="btn-view-pass"><span><i
                                                class="fa-solid fa-eye-slash"></i></span></button>
                                </div>
                                  </div>
                                  
                              </div>
                              
                              <div class="form-group">
                                <div id="boton" name="boton">
                                  <div class="col-md-6 col-md-offset-4">
                                      {!! Form::submit(trans('labels.change_password_button'), ['class' => 'btn btn-primary']) !!}
                                  </div>
                                </div>
                              </div>
                        </div>
                        <div class="col-md-4">
                           <p>La nueva contraseña debe cumplir:</p>
                           <ul>
                              <li id="letter">Al menos debería tener <strong>una letra en minúscula</strong></li>
                              <li id="capital">Al menos debería tener <strong>una letra en mayúsculas</strong></li>
                              <li id="number">Al menos debería tener <strong>un número</strong></li>
                              <li id="especial">Al menos debería tener <strong>un caracter especial</strong></li>
                              <li id="length">Debería tener <strong>10 carácteres</strong> como mínimo</li>
                           </ul>
                        </div>
                        <b style="color:#FF0000";>* Recuerde que cada 90 días se le pedirá cambiar de contraseña </b>
                       {!! Form::close() !!}

				</div><!--panel body-->

			</div><!-- panel -->

		</div><!-- col-md-10 -->

	</div><!-- row -->
    {!! HTML::style('css/view-pass.css') !!}
    {!! HTML::script('js/view-pass.js') !!}
@endsection