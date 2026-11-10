                        <div class="panel panel-info ">
                            <div class="panel-heading">
                                <h3 class="panel-title">EXAMENES</h3>
                            </div>
                            <div class="panel-body "> <!-- .col-md-12 -->
                            <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="">CÓDIGO DE MUESTRA</label>
                                            <input value="@if(isset($ficha)){{$ficha->codigo_laboratorio}}@endif" @if(isset($ficha)) readonly @endif type="text" name="" id="" class="form-control" placeholder="" tabindex="">
                                          </div>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">VDRL</label>
                                            @if(isset($examen))
                                            <input value="@if(isset($examen)){{$examen->vdrl}}@endif" @if(isset($examen)) readonly @endif type="text" name="lab_vdrl" id="lab_vdrl" class="form-control" placeholder="" tabindex="">
                                          
                                            @else
                                            <select name="lab_vdrl" id="lab_vdrl" class="form-control">
                                            <option value="0"> -Seleccionar- </option>
                                            <option value="Reactivo">Reactivos</option>
                                            <option value="No Reactivo">No Reactivos</option>
                                            <option value="Sin Reactivo">Sin Reactivo</option>
                                            </select>
                                              @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="">GLUCOSA</label>
                                            <input value="@if(isset($examen)){{$examen->glucosa}}@endif" @if(isset($examen)) readonly @endif type="text" name="lab_glucosa" id="lab_glucosa" class="form-control" placeholder="" tabindex="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">COLESTEROL</label>
                                            <input value="@if(isset($examen)){{$examen->colesterol}}@endif" @if(isset($examen)) readonly @endif type="text" name="lab_colesterol" id="lab_colesterol" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    <!--
                                        <div class="form-group">
                                           <center> <label for="">ECO</label> </center>
                                        </div>
                                        <div class="form-group">
                                            <label for="">LEUCOCITOS</label>
                                            <input value="@if(isset($examen)){{$examen->leucocitos}}@endif" @if(isset($examen)) readonly @endif type="text" name="lab_leucocitos" id="lab_leucocitos" class="form-control" placeholder="" tabindex="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">HEMATIES</label>
                                            <input value="@if(isset($examen)){{$examen->hematies}}@endif" @if(isset($examen)) readonly @endif type="text" name="lab_hematies" id="lab_hematies" class="form-control" placeholder="" tabindex="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">GLUCOSA</label>
                                            <input value="@if(isset($examen)){{$examen->glucosa_paquete}}@endif" @if(isset($examen)) readonly @endif type="text" name="lab_glucosa_paquete" id="lab_glucosa_paquete" class="form-control" placeholder="" tabindex="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">ALBUMINA</label>
                                            <input value="@if(isset($examen)){{$examen->albumina}}@endif" @if(isset($examen)) readonly @endif type="text" name="lab_albumina" id="lab_albumina" class="form-control" placeholder="" tabindex="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">NITRITOS</label>
                                            <input value="@if(isset($examen)){{$examen->nitritos}}@endif" @if(isset($examen)) readonly @endif type="text" name="lab_nitritos" id="lab_nitritos" class="form-control" placeholder="" tabindex="">
                                        </div>
                    -->
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">HIV</label>
                                            @if(isset($examen))
                                            <input value="@if(isset($examen)){{$examen->hiv}}@endif" @if(isset($examen)) readonly @endif type="text" name="lab_hiv" id="lab_hiv" class="form-control" placeholder="" tabindex="">
                                            @else
                                            <select name="lab_hiv" id="lab_hiv" class="form-control">
                                            <option value="0"> -Seleccionar- </option>
                                            <option value="Reactivo">Reactivo</option>
                                            <option value="No Reactivo">No Reactivo</option>
                                            <option value="Sin Reactivo">Sin Reactivo</option>
                                            </select>
                                              @endif
                                            
                                        </div>

                                        <div class="form-group">
                                            <label for="">TRIGLICÉRIDOS</label>
                                            <input value="@if(isset($examen)){{$examen->trigliceridos}}@endif" @if(isset($examen)) readonly @endif type="text" name="lab_trigliceridos" id="lab_trigliceridos" class="form-control" placeholder="" tabindex="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">HEPATITIS B</label>
                                             @if(isset($examen))
                                             <input value="@if(isset($examen)){{$examen->hepatitis_b}}@endif" @if(isset($examen)) readonly @endif type="text" name="lab_hepatitis_b" id="lab_hepatitis_b" class="form-control" placeholder="" tabindex="">
                                             @else
                                            <select name="lab_hepatitis_b" id="lab_hepatitis_b" class="form-control">
                                            <option value="0"> -Seleccionar- </option>
                                            <option value="Reactivo">Reactivo</option>
                                            <option value="No Reactivo">No Reactivo</option>
                                            <option value="Sin Reactivo">Sin Reactivo</option>
                                            </select>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">HEPATITIS C</label>
                                            @if(isset($examen))
                                               <input value="@if(isset($examen)){{$examen->anti_hct}}@endif" @if(isset($examen)) readonly @endif type="text" name="lab_anti_hct" id="lab_anti_hct" class="form-control" placeholder="" tabindex="">
                                            @else
                                            <select name="lab_anti_hct" id="lab_anti_hct" class="form-control">
                                            <option value="0"> -Seleccionar- </option>
                                            <option value="Reactivo">Reactivo</option>
                                            <option value="No Reactivo">No Reactivo</option>
                                            <option value="Sin Reactivo">Sin Reactivo</option>
                                            </select>
                                            @endif
                                        </div>
                                        <!--
                                        <div class="form-group">
                                            <label for="">HTO</label>
                                            <input value="@if(isset($examen)){{$examen->hto}}@endif" @if(isset($examen)) readonly @endif type="text" name="lab_hto" id="lab_hto" class="form-control" placeholder="" tabindex="">
                                        </div>
                                            -->
                                        @if($ficha->edad>39 and $ficha->sexo == 'M')
                                          <div class="form-group">
                                              <label for="">PSA</label>
                                                @if(isset($examen))
                                                <input value="@if(isset($examen)){{$examen->psa}}@endif" @if(isset($examen)) readonly @endif type="text" name="lab_psa" id="lab_psa" class="form-control" placeholder="" tabindex="">
                                              @else
                                              <select name="lab_psa" id="lab_psa" class="form-control">
                                              <option value="0"> -Seleccionar- </option>
                                              <option value="Positivo">Positivo</option>
                                              <option value="Negativo">Negativo</option>
                                              <option value="Sin Reactivo">Sin Reactivo</option>
                                              </select>
                                              @endif
                                          </div>
                                        @endif
                                        <div class="form-group">
                                            <label for="">OTROS</label>
                                            <input value="@if(isset($examen)){{$examen->otros}}@endif" @if(isset($examen)) readonly @endif type="text" name="lab_otros" id="lab_otros" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>