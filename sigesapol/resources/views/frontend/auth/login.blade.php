
@extends('frontend.layouts.login')

@section('content')

  <main class="form-signin">
    <script src="https://kit.fontawesome.com/397c168af6.js" crossorigin="anonymous"></script>


    <img class="mb-4" src="{{ asset('/images/logo/dirsapol.png') }} " width="140" title="DIRSAPOL – Dirección de sanidad policial">

    <h4 class="h4 mb-3 fw-normal">Sistema de Gestión<br>de Salud Policial</h4>
    <h1 class="h3 mb-3 fw-normal">SIGESAPOL - DIRSAPOL</h1>

    {{-- {{trans('labels.login_box_title')}} --}}

    @include('includes.partials.messages')

    {!! Form::open(['url' => 'auth/login', 'class' => 'needs-validation', 'novalidate' =>'']) !!}

      <div class="form-floating mb-3">
        {!! Form::input('email', 'email', old('email'), ['class' => 'form-control', 'maxlength' => '50', 'required' => '', 'placeholder' => 'email']) !!}
        {!! Form::label('email', trans('validation.attributes.email'), ['class' => '']) !!}
      </div>

      <div class="form-floating mb-3">
        <div class="input_and_button">
          {!! Form::input('password', 'password', null, ['class' => 'form-control input-pass', 'maxlength' => '20', 'required', 'autocomplete' => 'off', 'placeholder' => trans('validation.attributes.password')]) !!}
          <button type="button" id="btnShowPass" class="btn-view-pass"><span><i
                      class="fa-solid fa-eye-slash"></i></span></button>
      </div>
      </div>

      <div class="form-group mb-3 text-start">
        {!! Form::checkbox('remember') !!} {{ trans('labels.remember_me') }}

        {{-- 
        <div class="col-md-6 col-md-offset-2">
          <div class="checkbox">
            <label>
              
            </label>
          </div>
        </div>
        --}}
      </div>

      {!! Form::submit(trans('labels.login_button'), ['class' => 'w-100 btn btn-lg btn-success']) !!}
      {{-- {!! link_to('password/email', trans('labels.forgot_password')) !!} --}}

    {!! Form::close() !!}

    {{--
    <div class="row text-center">
      {!! $socialite_links !!}
    </div>
    --}}

    <p class="mt-5 mb-3 text-muted">&copy; {{ date('Y') }} - {{-- date('Y-m-d H:i:s') --}} All Rights Reserved.</p>

  </main>

@endsection

@section('renderjs')

  {!! HTML::script("assets/bootstrap/js/form-validation.js") !!}
  {!! HTML::style('css/view-pass-login.css') !!}
  {!! HTML::script('js/view-pass-login.js') !!}

@endsection