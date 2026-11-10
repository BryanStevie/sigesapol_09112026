<div class="panel panel-info ">
                            <div class="panel-heading">
                                <h3 class="panel-title">EXAMEN OTORRINOLARINGOLÓGICO</h3>
                            </div>
                            <div class="panel-body ">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">1. AGUDEZA AUDITIVA</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">OD:</label>
                                            <input value="@if(isset($examen)){{$examen->agudeza_auditiva_od}}@endif" @if(isset($examen)) disabled @endif type="text" name="agudezaod" id="agudezaod" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">SUFICIENTE</label>
                                            <input value="@if(isset($examen)){{$examen->agudeza_auditiva_od_suficiente}}@endif" @if(isset($examen)) disabled @endif type="text" name="suficienciaod" id="suficienciaod" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">OI:</label>
                                            <input value="@if(isset($examen)){{$examen->agudeza_auditiva_oi}}@endif" @if(isset($examen)) disabled @endif type="text" name="agudezaoi" id="agudezaoi" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">SUFICIENTE</label>
                                            <input value="@if(isset($examen)){{$examen->agudeza_auditiva_oi_suficiente}}@endif" @if(isset($examen)) disabled @endif type="text" name="suficienciaoi" id="suficienciaoi" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">2. EXAMEN DE NARIZ / GARGANTA</label>
                                            <input value="@if(isset($examen)){{$examen->examen_nariz_garganta}}@endif" @if(isset($examen)) disabled @endif type="text" name="examennariz" id="examennariz" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">3. MEMBRANA TIMPANICA</label>
                                            <input value="@if(isset($examen)){{$examen->membrana_timpanica}}@endif" @if(isset($examen)) disabled @endif type="text" name="menbranatimpanica" id="" class="form-control" placeholder="" tabindex="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>