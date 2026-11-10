                        <div class="panel panel-info ">
                            <div class="panel-heading">
                                <h3 class="panel-title">EXAMEN CARDIOVASCULAR</h3>
                            </div>
                            <div class="panel-body ">
                            <div class="row">
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="">CÓDIGO DE EXAMEN</label>
                                            <input value="@if(isset($ficha)){{$ficha->codigo_cardiologia}}@endif" @if(isset($ficha)) readonly @endif type="text" name="" id="" class="form-control" placeholder="" tabindex="">
                                          </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">1. EXAMEN CLÍNICO</label>
                                            <input value="@if(isset($examen)){{$examen->examen_clinico}}@endif" @if(isset($examen)) readonly @endif type="text" name="c_examen_clinico" id="c_examen_clinico" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">2. PA SISTÓOLICA</label>
                                            <input value="@if(isset($examen)){{$examen->pa_sistolica}}@endif" @if(isset($examen)) readonly @endif type="text" name="c_pa_sistolica" id="c_pa_sistolica" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">3. PA DIASTÓLICA</label>
                                            <input value="@if(isset($examen)){{$examen->pa_diastolica}}@endif" @if(isset($examen)) readonly @endif type="text" name="c_pa_diastolica" id="c_pa_diastolica" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                       <? php @if($ficha->codigo_cardiologia <=40)?>
                                        <div class="form-group">
                                            <label for="">3. ECG</label>
                                            <input value="@if(isset($examen)){{$examen->ecg}}@endif" @if(isset($examen)) readonly @endif type="text" name="c_ecg" id="c_ecg" class="form-control" placeholder="" tabindex="">
                                        </div>
                                        @endif 
                                        
                                    </div>
                                </div>
                            </div>
                        </div>