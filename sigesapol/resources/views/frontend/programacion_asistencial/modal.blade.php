<div class="modal fade" id="modalProgramacion" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        
        <form id="formModalProgramacion">
            {!! csrf_field() !!}
            <input type="hidden" id="id_registro_edit" name="id_registro_edit">

            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title">Registro de Programación</h4>
                </div>
                <div class="modal-body">
                    <!-- Sección de datos principales -->
                 <div id="formularioModal" >    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="modal_periodo">Periodo*</label>
                                <input type="text" id="modal_periodo" name="modal_periodo" class="form-control" placeholder="mm/aaaa" required>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="modal_establecimiento">IPRESS*</label>
                                <select id="modal_establecimiento" name="modal_establecimiento" class="form-control select2" required style="width: 250px;">
                                    <option value="" {{ empty($id_establecimiento) ? 'selected' : '' }}>- Seleccionar IPRESS -</option>
                                        @foreach($establecimientos as $e)
                                           <option value="{{ $e->id }}" {{ $e->id == $id_establecimiento ? 'selected' : '' }}>
                                                {{ $e->nombre }}
                                           </option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Columna 1 -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_clase_modal">Clase</label>
                                <select id="id_clase_modal" name="id_clase" class="form-control select2" required>
                                    <option value="">- Seleccione -</option>
                                    @foreach(App\Models\ClaseProgramacion::where('estado', 1)->get() as $c)
                                        <option value="{{ $c->id_clase }}">{{ $c->nombre_clase }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <!-- Columna 2 -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_tipo_modal">Tipo</label>
                                <select id="id_tipo_modal" name="id_tipo" class="form-control select2" required>
                                    <option value="">- Seleccione -</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Responsables</label>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="responsable_pnp" name="responsable_pnp"> PNP
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="responsable_susalud" name="responsable_susalud"> SUSALUD
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                
                    <div class="row">
                
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="horas_programadas">Horas Programadas</label>
                                <input type="number" id="horas_programadas" name="horas_programadas" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="horas_efectivas">Horas Efectivas</label>
                                <input type="number" id="horas_efectivas" name="horas_efectivas" class="form-control" required>
                            </div>
                        </div>
                        
                        <!-- Columna 4 -->
                        <div class="col-md-3" style="padding-top: 25px;">
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fa fa-save"></i> Guardar
                            </button>
                        </div>
                    </div>
</div>
                    <hr>

                    <!-- Tabla de detalle -->
                    <h5><strong>Detalle de Programación</strong></h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tablaDetalle">
                            <thead>
                                <tr>
                                    <th>PNP</th>
                                    <th>SUSALUD</th>
                                    <th>CLASE</th>
                                    <th>TIPO</th>
                                    
                                    <th>H. PROG.</th>
                                    <th>H. EFEC.</th>
                                     <th class="columna-acciones">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Contenido dinámico -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </form>
    </div>
</div>