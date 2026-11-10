@extends('frontend.layouts.master')
@section('renderjs')
<script>
    function jsDecimals(e) {

        var evt = (e) ? e : window.event;
        var key = (evt.keyCode) ? evt.keyCode : evt.which;
        if (key != null) {
            key = parseInt(key, 10);
            if ((key < 48 || key > 57) && (key < 96 || key > 105)) {
                //Aca tenemos que reemplazar "Decimals" por "NoDecimals" si queremos que no se permitan decimales
                if (!jsIsUserFriendlyChar(key, "Decimals")) {
                    return false;
                }
            } else {
                if (evt.shiftKey) {
                    return false;
                }
            }
        }
        return true;
    }
    function jsIsUserFriendlyChar(val, step) {
        // Backspace, Tab, Enter, Insert, y Delete
        if (val == 8 || val == 9 || val == 13 || val == 45 || val == 46) {
            return true;
        }
        // Ctrl, Alt, CapsLock, Home, End, y flechas
        if ((val > 16 && val < 21) || (val > 34 && val < 41)) {
            return true;
        }
        if (step == "Decimals") {
            if (val == 190 || val == 110) {  //Check dot key code should be allowed
                return true;
            }
        }
        // The rest
        return false;
    }

    function IsNumeric(valor)
    {
        var log = valor.length;
        var sw = "S";
        for (x = 0; x < log; x++)
        {
            v1 = valor.substr(x, 1);
            v2 = parseInt(v1);
//Compruebo si es un valor numérico 
            if (isNaN(v2)) {
                sw = "N";
            }
        }
        if (sw == "S") {
            return true;
        } else {
            return false;
        }
    }
    var primerslap = false;
    var segundoslap = false;
    function formateafecha(fecha)
    {
        var long = fecha.length;
        var dia;
        var mes;
        var ano;
        if ((long >= 2) && (primerslap == false)) {
            dia = fecha.substr(0, 2);
            if ((IsNumeric(dia) == true) && (dia <= 31) && (dia != "00")) {
                fecha = fecha.substr(0, 2) + "-" + fecha.substr(3, 7);
                primerslap = true;
            } else {
                fecha = "";
                primerslap = false;
            }
        } else
        {
            dia = fecha.substr(0, 1);
            if (IsNumeric(dia) == false)
            {
                fecha = "";
            }
            if ((long <= 2) && (primerslap = true)) {
                fecha = fecha.substr(0, 1);
                primerslap = false;
            }
        }
        if ((long >= 5) && (segundoslap == false))
        {
            mes = fecha.substr(3, 2);
            if ((IsNumeric(mes) == true) && (mes <= 12) && (mes != "00")) {
                fecha = fecha.substr(0, 5) + "-" + fecha.substr(6, 4);
                segundoslap = true;
            } else {
                fecha = fecha.substr(0, 3);
                ;
                segundoslap = false;
            }
        } else {
            if ((long <= 5) && (segundoslap = true)) {
                fecha = fecha.substr(0, 4);
                segundoslap = false;
            }
        }
        if (long >= 7)
        {
            ano = fecha.substr(6, 4);
            if (IsNumeric(ano) == false) {
                fecha = fecha.substr(0, 6);
            } else {
                if (long == 10) {
                    if ((ano == 0) || (ano < 1900) || (ano > 2100)) {
                        fecha = fecha.substr(0, 6);
                    }
                }
            }
        }
        if (long >= 10)
        {
            fecha = fecha.substr(0, 10);
            dia = fecha.substr(0, 2);
            mes = fecha.substr(3, 2);
            ano = fecha.substr(6, 4);
// Año no viciesto y es febrero y el dia es mayor a 28 
            if ((ano % 4 != 0) && (mes == 02) && (dia > 28)) {
                fecha = fecha.substr(0, 2) + "-";
            }
        }
        return (fecha);
    }
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-9 well">
            <form  method="post" action="{{ route('update_datos_user')}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="panel panel-primary" >
                    <div class="panel-heading">
                        <div class="panel-title">
                            Editar Mis Datos   
                            <div class="pull-right" style="margin-left: 5px">
                                <a href="{!!url('auth/password/change')!!}" class=" btn btn-warning btn-xs">{{ trans('navs.change_password') }}</a>
                            </div>
                            <div class="pull-right" style="margin-left: 5px">
                                <a href="{!!url('dashboard')!!}" class="btn btn-default btn-xs " style="color:black">Ver mi perfil</a>
                            </div>
                        </div>                    
                    </div>     
                    <div style="padding-top:30px" class="panel-body" >
                        <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                        <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('store_farmacia') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div style="margin-bottom: 5px" class="col-md-3">
                                <label>DNI</label>                             
                                <input class="form-control" type="text" maxlength="8" name="dni" value="{{$usuario->dni}}"  autofocus onkeydown="return jsDecimals(event);">
                                <input class="form-control" type="hidden" name="id" value="{{$usuario->id}}">
                            </div>
                            <div style="margin-bottom: 5px" class="col-md-3">
                                <label>Nombre</label>                             
                                <input class="form-control" type="text"  value="{{$usuario->name}}" name="name" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" required>
                            </div>
                            <div style="margin-bottom: 5px" class="col-md-3">
                                <label>Ape. Paterno</label>                             
                                <input class="form-control" type="text"  value="{{$usuario->paterno}}" name="paterno" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" required>
                            </div>
                            <div style="margin-bottom: 5px" class="col-md-3">
                                <label>Ape. Materno</label>                             
                                <input class="form-control" type="text"  value="{{$usuario->materno}}" name="materno" style="text-transform:uppercase;" onkeyup="javascript:this.value = this.value.toUpperCase();" required>
                            </div>
                            <div style="margin-bottom: 5px" class="col-md-6">
                                <label>E-mail</label>                             
                                <input class="form-control" type="email"  value="{{$usuario->email}}" name="email" required>
                            </div>
                            <div style="margin-bottom: 5px" class="col-md-3">
                                <label>Celular</label>                             
                                <input class="form-control" type="text"  value="{{$usuario->celular}}" name="celular" maxlength="9" onkeydown="return jsDecimals(event);">
                            </div>                      
                            <div style="margin-top:5px" class="col-md-12">
                                <input type="submit" value="Actualizar" class="btn btn-primary pull-right">
                            </div> 
                        </form>     
                    </div>                     
                </div>
            </form>
        </div>

    </div>
</div>
@endsection