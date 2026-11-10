<script type="text/javascript">  
            $(document).ready(function() {
                $("#nomostrar").show();
                $("#nomostrar1").show();
            });

              function mostrar1(g_examen_pap) {
                console.log(g_examen_pap);
                    if (g_examen_pap == "3") {
                        $("#nomostrar").hide();
                        $("#nomostrar1").show();
                        console.log(g_examen_pap);
                    }else{
                        $("#nomostrar").show();
                        $("#nomostrar1").hide();
                    }
                }          
    </script>                    
                    
                    <div class="panel panel-info ">
                            <div class="panel-heading">
                                <h3 class="panel-title">EXAMEN GINECOLÓGICO</h3>
                            </div>
                            <div class="panel-body ">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">1. EXAMEN CLÍNICO DE MAMAS</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">G</label>
                                            <input value="@if(isset($examen)){{$examen->g}}@endif" @if(isset($examen)) readonly @endif type="text" name="g_g" id="g_g" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">P</label>
                                            <div class="col-md-12">
                                                <?php
                                                if(isset($examen)):
                                                    $p_temporal = $examen->p;
                                                    //strlen($p_temporal) - 3
                                                    $ind_final = strpos($p_temporal, "-");
                                                    $p_1 = substr($p_temporal, 0, $ind_final);
                                                    $p_temporal = substr($p_temporal, $ind_final + 1);
                                                    
                                                    $ind_final = strpos($p_temporal, "-");
                                                    $p_2 = substr($p_temporal, 0, $ind_final);
                                                    $p_temporal = substr($p_temporal, $ind_final + 1);

                                                    $ind_final = strpos($p_temporal, "-");
                                                    $p_3 = substr($p_temporal, 0, $ind_final);
                                                    $p_temporal = substr($p_temporal, $ind_final + 1);

                                                    $p_4 = substr($p_temporal, 0, $ind_final);
                                                endif;
                                                ?>
                                                <div class="col-md-3">
                                                    <input value="@if(isset($examen)){{$p_1}}@endif" @if(isset($examen)) readonly @endif type="text" name="g_p_1" id="g_p_1" class="form-control" placeholder="" tabindex="" maxlength="5">
                                                </div>
                                                <div class="col-md-3">
                                                    <input value="@if(isset($examen)){{$p_2}}@endif" @if(isset($examen)) readonly @endif type="text" name="g_p_2" id="g_p_2" class="form-control" placeholder="" tabindex="" maxlength="5">
                                                </div>
                                                <div class="col-md-3">
                                                    <input value="@if(isset($examen)){{$p_3}}@endif" @if(isset($examen)) readonly @endif type="text" name="g_p_3" id="g_p_3" class="form-control" placeholder="" tabindex="" maxlength="5">
                                                </div>
                                                <div class="col-md-3">
                                                    <input value="@if(isset($examen)){{$p_4}}@endif" @if(isset($examen)) readonly @endif type="text" name="g_p_4" id="g_p_4" class="form-control" placeholder="" tabindex="" maxlength="5">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">MENARQUIA</label>
                                            <input value="@if(isset($examen)){{$examen->menarquia}}@endif" @if(isset($examen)) disabled @endif type="text" name="g_menarquia" id="g_menarquia" class="form-control" placeholder="" tabindex="" maxlength="2" min="11" max="15">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">IRS</label>
                                            <input value="@if(isset($examen)){{$examen->irs}}@endif" @if(isset($examen)) readonly @endif type="text" name="g_irs" id="g_irs" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">FUR</label>
                                            <input value="@if(isset($examen)){{$examen->urs}}@endif" @if(isset($examen)) readonly @endif type="text" name="g_fur" id="g_fur" class="form-control datepicker" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">MAC</label>
                                            <input value="@if(isset($examen)){{$examen->fur}}@endif" @if(isset($examen)) readonly @endif type="text" name="g_mac" id="g_mac" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">URS</label>
                                            <input value="@if(isset($examen)){{$examen->andria}}@endif" @if(isset($examen)) readonly @endif type="text" name="g_urs" id="g_urs" class="form-control datepicker" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">ANDRIA</label>
                                            <input value="@if(isset($examen)){{$examen->mac}}@endif" @if(isset($examen)) readonly @endif type="text" name="g_andria" id="g_andria" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">MAMOGRAFÍA</label>
                                            <input value="@if(isset($examen)){{$examen->mamografia}}@endif" @if(isset($examen)) readonly @endif type="text" name="g_mamografia" id="g_mamografia" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">ANTECEDENTES PERSONALES Y FAMILIARES (MADRE, HERMANA Y OTROS) CON ENFERMEDADES TUMORALES DE UTERO, OVARIO, MAMAS, HIPOFISIS, TIROIDES, FALLECIMIENTOS U OTROS</label>
                                            <textarea name="g_antecedentes" id="g_antecedentes" class="form-control" @if(isset($examen)) readonly @endif>@if(isset($examen)){{$examen->antecedentes_personales}}@endif</textarea>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">2. EXAMEN COMPLEMENTARIO: PAP</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" >
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            Se realizo el examen <input value="1" onChange="mostrar1(this.value);" type="radio" name="g_examen_pap" id="g_examen_pap_se_realizo_examen" class="" placeholder="" tabindex="" @if(isset($examen))@if($examen->examen_pap=='1') checked @endif @endif @if(isset($examen)) disabled @endif>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            No se realizo el examen <input value="3" onChange="mostrar1(this.value);" type="radio" name="g_examen_pap" id="g_examen_pap_no_se_realizo_examen"  class="" placeholder="" tabindex="" @if(isset($examen))@if($examen->examen_pap=='3') checked @endif @endif @if(isset($examen)) disabled @endif>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            Trae examen <input value="2" type="radio" onChange="mostrar1(this.value);" name="g_examen_pap" id="g_examen_pap_trae_examen" class="" placeholder="" tabindex="" @if(isset($examen))@if($examen->examen_pap=='2') checked @endif @endif @if(isset($examen)) disabled @endif>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="nomostrar"  style="display: none;" >
                                
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">FECHA</label>
                                            <input value="@if(isset($examen)){{$examen->fecha_examen_pap}}@endif" @if(isset($examen)) disabled @endif type="text" name="g_fecha" id="g_fecha" class="form-control datepicker" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">RESULTADO</label>
                                            <select name="g_resultado_pap" id="g_resultado_pap" class="form-control" @if(isset($examen)) disabled @endif>
                                                <option value="">-Seleccionar-</option>
                                                <option @if(isset($examen))@if($examen->resultado_examen_pap=='1') selected @endif @endif value="1">POSITIVO</option>
                                                <option @if(isset($examen))@if($examen->resultado_examen_pap=='2') selected @endif @endif value="2">NEGATIVO</option>
                                                <option @if(isset($examen))@if($examen->resultado_examen_pap=='3') selected @endif @endif value="3">SOSPECHA DE CANCER</option>
                                                <option @if(isset($examen))@if($examen->resultado_examen_pap=='4') selected @endif @endif value="4">NO APLICA</option>
                                            </select>
                                        </div>
                                    </div>

                                   
                                </div>
                                <div class="row" id="nomostrar1"   style="display: none;" >
                                  
                                   <div class="col-md-3">
                                       <div class="form-group">
                                           <label for="">MOTIVO DE NO REALIZARCE</label>
                                           <input value="@if(isset($examen)){{$examen->motivo_no_pap}}@endif" @if(isset($examen)) disabled @endif type="text" name="motivo_no_pap" id="motivo_no_pap" class="form-control " placeholder="" tabindex="">
                                       </div>
                                   </div>
                             

                               </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">3. PRUEBA DE IVAA: </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input value="1" type="radio" name="g_prueba_iva" id="g_prueba_iva_si" class="" placeholder="" tabindex="" @if(isset($examen))@if($examen->prueba_iva=='1') checked @endif @endif @if(isset($examen)) disabled @endif> SI
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input value="0" type="radio" name="g_prueba_iva" id="g_prueba_iva_no" class="" placeholder="" tabindex="" @if(isset($examen))@if($examen->prueba_iva=='0') checked @endif @endif @if(isset($examen)) disabled @endif> NO
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">RESULTADO</label>
                                            <select name="g_resultado_iva" id="g_resultado_iva" class="form-control" @if(isset($examen)) disabled @endif>
                                                <option value="">-Seleccionar-</option>
                                                <option @if(isset($examen))@if($examen->resultado_examen_pap=='1') selected @endif @endif value="1">POSITIVO</option>
                                                <option @if(isset($examen))@if($examen->resultado_examen_pap=='2') selected @endif @endif value="2">NEGATIVO</option>
                                                <option @if(isset($examen))@if($examen->resultado_examen_pap=='3') selected @endif @endif value="3">SOSPECHA DE CANCER</option>
                                                <option @if(isset($examen))@if($examen->resultado_examen_pap=='4') selected @endif @endif value="4">NO APLICA</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">4.- GESTANTE</label>
                                            <select name="g_gestante" id="g_gestante" class="form-control" @if(isset($examen)) disabled @endif>
                                                <option @if(isset($examen))@if($examen->gestante=='NO') selected @endif @endif value="NO">NO</option>
                                                <option @if(isset($examen))@if($examen->gestante=='SI') selected @endif @endif value="SI">SI</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>