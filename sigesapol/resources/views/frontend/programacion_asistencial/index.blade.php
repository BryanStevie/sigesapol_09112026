
@push('renderjs')
<script src="{{ asset('js/programacion/programacion_asistencial.js') }}"></script>
@endpush


<div class="container" style="margin-top: 20px;">
    <!-- Panel de Búsqueda -->
    <div class="panel panel-success">
        <div class="panel-heading">
            <strong>Búsqueda de Programación</strong>
        </div>
        <div class="panel-body">
            <form class="form-inline" id="formBusqueda">
                {!! csrf_field() !!}
                <div class="form-group col-md-3">
                    <label for="periodo">Periodo</label>
                    
                    <input type="hidden" id="id_personal" name="id_personal" class="form-control" value="{{$medico->id}}" maxlength="7" required>
                    <input type="text" id="periodo" name="periodo" class="form-control" placeholder="mm/aaaa" maxlength="7" required>
                </div>
                <div class="form-group col-md-5" style="margin-left: 10px;">
                    <label for="id_establecimiento_programacion">IPRESS</label>
                    <select id="id_establecimiento_programacion" name="id_establecimiento_programacion" class="form-control select2" required style="width: 250px;">
                      <option value="" {{ empty($id_establecimiento) ? 'selected' : '' }}>- Seleccionar IPRESS -</option>
                       @foreach($establecimientos as $e)
                            <option value="{{ $e->id }}" {{ $e->id == $id_establecimiento ? 'selected' : '' }}>
                                      {{ $e->nombre }}
                            </option>
                      @endforeach
                    </select>
                </div>
                <button type="button" class="btn btn-primary" id="btnBuscar">
                    <i class="fa fa-search"></i> Buscar
                </button>
                <button type="button" class="btn btn-success" id="btnNuevo">
                    <i class="fa fa-plus"></i> Nuevo
                </button>
            </form>
        </div>
    </div>

    <!-- Panel de Resultados -->
    <div class="panel panel-default">
        <div class="panel-heading bg-lightgreen">
            <strong>Resumen de Programación</strong>
        </div>
        <div class="panel-body" style="padding: 0">
            <!-- Actualizar la tabla en el index para incluir data-attributes -->
<table class="table table-bordered table-striped" id="tablaResumen" style="font-size: 13px; margin: 0">
    <thead>
        <tr>
            <th style="width: 3%">N°</th>
            <th style="width: 5%">PERIODO</th>
            <th style="width: 18%">IPRESS</th>
            
            <th style="width: 6%">T. Hrs. Prog.</th>
            <th style="width: 6%">T. Hrs. Efec.</th>
            <th style="width: 2%">VER</th>
            <th style="width: 2%">EDITAR</th>
            <th style="width: 10%">ANULAR</th>
        </tr>
    </thead>
    <tbody>
        <!-- El contenido dinámico ahora incluirá data-attributes -->
    </tbody>
</table>
        </div>
    </div>
</div>

@include('frontend.programacion_asistencial.modal')
