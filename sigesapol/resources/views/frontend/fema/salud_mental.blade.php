<div class="panel panel-info ">
                            <div class="panel-heading">
                                <h3 class="panel-title">SALUD MENTAL</h3>
                            </div>
                            <div class="panel-body ">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">1. EVALUACIÓN PSICOLÓGICA</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">AREA EMOCIONAL</label>
                                            <input value="@if(isset($examen)){{$examen->area_emocional}}@endif" @if(isset($examen)) readonly @endif type="text" name="sm_area_emocional" id="sm_area_emocional" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">AREA COGNITIVA</label>
                                            <input value="@if(isset($examen)){{$examen->area_cognitiva}}@endif" @if(isset($examen)) readonly @endif type="text" name="sm_area_cognitiva" id="sm_area_cognitiva" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                  <!--  <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">AREA NEUROBIOLOGICA</label>
                                            <input value="@if(isset($examen)){{$examen->area_neurobiologica}}@endif" @if(isset($examen)) readonly @endif type="text" name="sm_area_neurobiologica" id="sm_area_neurobiologica" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>  -->
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">2. DERIVACIÓN</label>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input value="1" type="radio" name="sm_derivacion" id="sm_derivacion_si" @if(isset($examen))@if($examen->derivacion=='1') checked @endif @endif @if(isset($examen)) disabled @endif class="" placeholder="" readonly="" tabindex=""> SI
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input value="0" type="radio" name="sm_derivacion" id="sm_derivacion_no" @if(isset($examen))@if($examen->derivacion=='0') checked disabled @endif @endif @if(isset($examen)) disabled @endif class="" placeholder="" readonly="" tabindex=""> NO
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">3. OBSERVACIONES</label>
                                            <select name="sm_observaciones" id="sm_observaciones" class="form-control" @if(isset($examen)) disabled @endif>
                                                <option value="">-Seleccionar-</option>
                                                <option value="0" @if(isset($examen))@if($examen->observacion=='2') selected @endif @endif>REFIERE NO HABER LLEVADO TRATAMIENTO</option>
                                                <option value="1" @if(isset($examen))@if($examen->observacion=='1') selected @endif @endif>REFIERE HABER LLEVADO TERAPIA PSICOLOGICA</option>
                                                <option value="2" @if(isset($examen))@if($examen->observacion=='2') selected @endif @endif>REFIERE HABER LLEVADO TERAPIA PSIQUIATRICA</option>
                                            </select>
                                        </div>
                                    </div>
                            </div>
                        </div>