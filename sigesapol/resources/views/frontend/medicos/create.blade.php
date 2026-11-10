@extends('frontend.layouts.master')
@section('renderjs')
<style>
    .filterable {
        margin-top: 0px;
    }
    .filterable .panel-heading .pull-right {
        margin-top: -20px;
    }
    .filterable .filters input[disabled] {
        background-color: transparent;
        border: none;
        cursor: auto;
        box-shadow: none;
        padding: 0;
        height: auto;
    }
    .filterable .filters input[disabled]::-webkit-input-placeholder {
        color: #333;
    }
    .filterable .filters input[disabled]::-moz-placeholder {
        color: #333;
    }
    .filterable .filters input[disabled]:-ms-input-placeholder {
        color: #333;
    }

</style>

<script>
    $(document).ready(function () {
        $('.filterable .btn-filter').click(function () {
            var $panel = $(this).parents('.filterable'),
                    $filters = $panel.find('.filters input'),
                    $tbody = $panel.find('.table tbody');
            if ($filters.prop('disabled') == true) {
                $filters.prop('disabled', false);
                $filters.first().focus();
            } else {
                $filters.val('').prop('disabled', true);
                $tbody.find('.no-result').remove();
                $tbody.find('tr').show();
            }
        });

        $('.filterable .filters input').keyup(function (e) {
            /* Ignore tab key */
            var code = e.keyCode || e.which;
            if (code == '9')
                return;
            /* Useful DOM data and selectors */
            var $input = $(this),
                    inputContent = $input.val().toLowerCase(),
                    $panel = $input.parents('.filterable'),
                    column = $panel.find('.filters th').index($input.parents('th')),
                    $table = $panel.find('.table'),
                    $rows = $table.find('tbody tr');
            /* Dirtiest filter function ever ;) */
            var $filteredRows = $rows.filter(function () {
                var value = $(this).find('td').eq(column).text().toLowerCase();
                return value.indexOf(inputContent) === -1;
            });
            /* Clean previous no-result if exist */
            $table.find('tbody .no-result').remove();
            /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
            $rows.show();
            $filteredRows.hide();
            /* Prepend no-result row if all rows are filtered */
            if ($filteredRows.length === $rows.length) {
                $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="' + $table.find('.filters th').length + '">No se encuentra el laboratorio</td></tr>'));
            }
        });
    });
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/gestion_medicos/{{ Auth::user()->id }}"><i class="fa fa-table fa-fw"></i> Personal de Salud</a></li>
                <li class="active"><a href="/select_tipo_personal"><i class="fa fa-plus"></i> Nuevo Personal de Salud</a></li>
                <!--<li class="active"><a href="/create_medico/{{Auth::user()->id}}"><i class="fa fa-plus"></i> Nuevo Personal de Salud</a></li>-->
                <li><a href="/formatoxls_personal_medico/{{Auth::user()->id}}"><i class="fa fa-download"></i> Descargar Formato</a></li>
                <li><a href="/importaciones_medicos/{{Auth::user()->id}}"><i class="fa fa-info-circle"></i> Importarciones</a></li>
            </ul>
        </div>
        <div class="col-md-7 well">
            <div class="row">
                <div class="panel panel-primary filterable">
                    <div class="panel-heading">
                        <h3 class="panel-title">Agregar Personal de Salud Contratado</h3>
                        <a style="float: right" class="btn btn-xs btn-default" href="/select_tipo_personal"><i class="fa fa-search"></i> Personal Institucional</a>
                    </div>
                    <div style="margin-top: 20px">
                        <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('store_medico') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">DNI</label>  
                                    <div class="col-md-6">
                                        <input id="dni" name="dni" type="text" placeholder="" class="form-control input-md" maxlength="8">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">COLEGIATURA</label>  
                                    <div class="col-md-6">
                                        <input id="colegiatura" name="colegiatura" type="text" placeholder="" class="form-control input-md">
                                        <input id="id_user" name="id_user" type="hidden" placeholder="" value="{{ Auth::user()->id }}">
                                    </div>                                   
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">IPRESS</label> 
                                    <div class="col-md-6">
                                        <select id="id_establecimiento" name="id_establecimiento" class="form-control">
                                            @foreach($establecimiento as $e)
                                            <option value="{{$e->id}}">{{$e->nombre}}</option>
                                            @endforeach                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">NOMBRE</label>  
                                    <div class="col-md-6">
                                        <input id="nombre" name="nombre" type="text" placeholder="" class="form-control input-md" style="text-transform: uppercase;">
                                    </div>                                   
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">APE. PATERNO</label>  
                                    <div class="col-md-6">
                                        <input id="paterno" name="paterno" type="text" placeholder="" class="form-control input-md" style="text-transform: uppercase;">
                                    </div>                                   
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">APE. MATERNO</label>  
                                    <div class="col-md-6">
                                        <input id="materno" name="materno" type="text" placeholder="" class="form-control input-md" style="text-transform: uppercase;">
                                    </div>                                   
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">ESPECIALIDAD</label>  
                                    <div class="col-md-6">
                                        <input id="especialidad" name="especialidad" type="text" placeholder="" class="form-control input-md" style="text-transform: uppercase;">
                                    </div>                                   
                                </div>
                                <div class="form-group">                                     
                                    <div class="col-md-5" style="margin-left: 45px">
                                        <a href="/gestion_medicos/{{ Auth::user()->id }}" class="form-control btn btn-danger" >Cancelar</a>
                                    </div> 
                                    <div class="col-md-5">
                                        <input type="submit" class="form-control btn btn-success" value="Guardar">
                                    </div>                                   
                                </div>

                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection