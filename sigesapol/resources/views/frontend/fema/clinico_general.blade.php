                        <!-- EN ESTA FICHA "CLINICO GENERAL. EL CAMPO DE ALERTA DEBE OCULTARSE Y LAS OBSERVACIONES DEBEN PASAR A SER TATUAJES" -->
                        <div class="panel panel-info ">
                            <div class="panel-heading">
                                <h3 class="panel-title">CLÍNICO GENERAL</h3>
                            </div>
                            <div class="panel-body "> <!-- .col-md-12 -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">ANTECEDENTES</label>
                                            <textarea name="cli_gen_antecedentes" id="cli_gen_antecedentes" class="form-control" @if(isset($examen)) readonly @endif>@if(isset($examen)){{$examen->antecedentes}}@endif</textarea>
                                        </div>
                                    </div>
                                </div>
                                <!--<hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">ANTECEDENTES PERSONALES</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">LEY 12633</label>
                                            <input value="" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">FECHA</label>
                                            <input value="" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">PERIODO</label>
                                            <input value="" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                </div-->
                                <!--div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">CERTIFICADO DISCAPACIDAD N°</label>
                                            <input value="" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">FECHA</label>
                                            <input value="" type="text" name="" id="" class="form-control" placeholder="" readonly="" tabindex="">
                                        </div>
                                    </div>
                                </div>
                                <hr>-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">EXAMEN CLÍNICO</label>
                                            <textarea name="cli_gen_examen_clinico" id="cli_gen_examen_clinico" class="form-control" @if(isset($examen)) readonly @endif>@if(isset($examen)){{$examen->examen_clinico}}@endif</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>