                        <div class="panel panel-info ">
                            <div class="panel-heading">
                                <h3 class="panel-title">EXAMEN OFTALMOLÓGICO</h3>
                            </div>
                            <div class="panel-body ">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">1. VISIÓN CERCANA</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">OD:</label>
                                                <input value="@if(isset($examen)){{$examen->vision_cercana_od}}@endif" @if(isset($examen)) readonly @endif type="text" name="o_vision_cercana_od" id="o_vision_cercana_od" class="form-control" placeholder="" tabindex="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">CORREGIDA</label>
                                                <input value="@if(isset($examen)){{$examen->vision_cercana_od_corregida}}@endif" @if(isset($examen)) readonly @endif type="text" name="o_vision_cercana_od_corregida" id="o_vision_cercana_od_corregida" class="form-control" placeholder="" tabindex="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">OI:</label>
                                                <input value="@if(isset($examen)){{$examen->vision_cercana_oi}}@endif" @if(isset($examen)) readonly @endif type="text" name="o_vision_cercana_oi" id="o_vision_cercana_oi" class="form-control" placeholder="" tabindex="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">CORREGIDA</label>
                                                <input value="@if(isset($examen)){{$examen->vision_cercana_oi_corregida}}@endif" @if(isset($examen)) readonly @endif type="text" name="o_vision_cercana_oi_corregida" id="o_vision_cercana_oi_corregida" class="form-control" placeholder="" tabindex="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">2. VISIÓN LEJANA</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">OD:</label>
                                                <input value="@if(isset($examen)){{$examen->vision_cercana_od2}}@endif" @if(isset($examen)) readonly @endif type="text" name="o_vision_cercana_od2" id="o_vision_cercana_od2" class="form-control" placeholder="" tabindex="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">CORREGIDA</label>
                                                <input value="@if(isset($examen)){{$examen->vision_cercana_od_corregida2}}@endif" @if(isset($examen)) readonly @endif type="text" name="o_vision_cercana_od_corregida2" id="o_vision_cercana_od_corregida2" class="form-control" placeholder="" tabindex="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">OI:</label>
                                                <input value="@if(isset($examen)){{$examen->vision_cercana_oi2}}@endif" @if(isset($examen)) readonly @endif type="text" name="o_vision_cercana_oi2" id="o_vision_cercana_oi2" class="form-control" placeholder="" tabindex="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">CORREGIDA</label>
                                                <input value="@if(isset($examen)){{$examen->vision_cercana_oi_corregida2}}@endif" @if(isset($examen)) readonly @endif type="text" name="o_vision_cercana_oi_corregida2" id="o_vision_cercana_oi_corregida2" class="form-control" placeholder="" tabindex="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">TONOMETRÍA</label>
                                            <input value="@if(isset($examen)){{$examen->tonometria}}@endif" @if(isset($examen)) readonly @endif type="text" name="o_tonometria" id="o_tonometria" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">PUPILAS</label>
                                            <input value="@if(isset($examen)){{$examen->pupilas}}@endif" @if(isset($examen)) readonly @endif type="text" name="o_pupilas" id="o_pupilas" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">MOTIL, OCULAR</label>
                                            <input value="@if(isset($examen)){{$examen->motil_ocular}}@endif" @if(isset($examen)) readonly @endif type="text" name="o_motil_ocular" id="o_motil_ocular" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">FONDO OJO</label>
                                            <input value="@if(isset($examen)){{$examen->fondo_ojo}}@endif" @if(isset($examen)) readonly @endif type="text" name="o_fondo_ojo" id="o_fondo_ojo" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">VISIÓN DE COLORES (TEST HISHIHARA)</label>
                                            <input value="@if(isset($examen)){{$examen->vision_colores}}@endif" @if(isset($examen)) readonly @endif type="text" name="o_vision_colores" id="o_vision_colores" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>