                        <div class="panel panel-info ">
                            <div class="panel-heading">
                                <h3 class="panel-title">APARATO RESPIRATORIO</h3>
                            </div>
                            <div class="panel-body ">
                            <div class="row">
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="">CÓDIGO DE PLACA RADIOGRAFICA</label>
                                            <input value="@if(isset($ficha)){{$ficha->codigo_respiratorio}}@endif" @if(isset($ficha)) readonly @endif type="text" name="" id="" class="form-control" placeholder="" tabindex="">
                                          </div>
                                    </div>
                                </div>
                                <div class="row">
                                    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">1. EXAMEN CLÍNICO</label>
                                            <input value="@if(isset($examen)){{$examen->examen_clinico}}@endif" @if(isset($examen)) readonly @endif type="text" name="r_examen_clinico" id="r_examen_clinico" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">2. EXAMEN RADIOLÓGICO</label>
                                            <input value="@if(isset($examen)){{$examen->examen_radiologico}}@endif" @if(isset($examen)) readonly @endif type="text" name="r_examen_radiologico" id="r_examen_radiologico" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">3. EXAMEN DE ESPUTO (OPCIONAL)</label>
                                            <input value="@if(isset($examen)){{$examen->examen_esputo}}@endif" @if(isset($examen)) readonly @endif type="text" name="r_examen_esputo" id="r_examen_esputo" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>