@section('renderjs')
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::script("plugins/jQuery/jquery-ui.css") !!}
@endsection
@section('content')
<div class="container" style="width:100%">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="row">
        <form id="frmTriaje" class="form-horizontal" role="form" method="post"  action="{{ route('update_paciente_antecedentes') }}">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <fieldset>
            <div class="panel panel-info panel-bordered panel-sm">
              <div class="panel-heading">
                <div class="panel-title text-center">
                  <b>Antecedentes de enfermedades personales</b>
                </div>
              </div>
              <div class="panel-body">
                <table class="display" id="tblAntecedente" cellspacing="0" width="100%" style="margin-top:5px;">
                  <?php $x=1; ?>
                    @foreach($antecedente_1 as $id => $antecedente)
                      <?php if($x==1): ?>
                        <tr>
                      <?php endif; ?>
                            <td>
                                <input 
                                type="checkbox" 
                                value="{{ $antecedente->id }}" 
                                @foreach($antecedente_paciente as $id => $ant_pac)
                                  {{ $ant_pac->tipo_antecedente_id==$antecedente->id ? 'checked' : '' }}
                                 @endforeach
                                name="antecedentes_1[]">
                            </td>
                            <td>&nbsp;{{ $antecedente->descripcion }}</td>
                            <?php  
                              if($x==3):
                                $x=1;
                            ?>
                              </tr>
                            <?php 
                              else:
                                $x++;
                              endif;
                              ?>
                     
                    @endforeach
                </table>
              </div>
            </div>
          </fieldset>
          <fieldset>
            <div class="panel panel-info panel-bordered panel-sm">
              <div class="panel-heading">
                <div class="panel-title text-center">
                  <b>Antecedentes Quirúrgicos</b>
                </div>
              </div>
              <div class="panel-body">
                <table class="display" id="tblAntecedente" cellspacing="0" width="100%" style="margin-top:5px;">
                  <?php $x=1; ?>
                    @foreach($antecedente_2 as $id => $antecedente)
                      <?php if($x==1): ?>
                        <tr>
                      <?php endif; ?>
                            <td>
                                <input 
                                type="checkbox" 
                                value="{{ $antecedente->id }}" 
                                @foreach($antecedente_paciente as $id => $ant_pac)
                                  {{ $ant_pac->tipo_antecedente_id==$antecedente->id ? 'checked' : '' }}
                                @endforeach
                                name="antecedentes_1[]">
                            </td>
                            <td>&nbsp;{{ $antecedente->descripcion }}</td>
                            <?php  
                              if($x==3):
                                $x=1;
                            ?>
                              </tr>
                            <?php 
                              else:
                                $x++;
                              endif;
                              ?>
                    @endforeach
                </table>
            </div>
          </fieldset>
          <fieldset>
            <div class="panel panel-info panel-bordered panel-sm">
              <div class="panel-heading">
                <div class="panel-title text-center">
                  <b>Antecedentes Familiares</b>
                </div>
              </div>
              <div class="panel-body">
                <table class="display" id="tblAntecedente" cellspacing="0" width="100%" style="margin-top:5px;">
                  <?php $x=1; ?>
                    @foreach($antecedente_3 as $id => $antecedente)
                      <?php if($x==1): ?>
                        <tr>
                      <?php endif; ?>
                            <td>
                                <input 
                                type="checkbox" 
                                value="{{ $antecedente->id }}" 
                                @foreach($antecedente_paciente as $id => $ant_pac)
                                  {{ $ant_pac->tipo_antecedente_id==$antecedente->id ? 'checked' : '' }}
                                @endforeach
                                name="antecedentes_1[]">
                            </td>
                            <td>&nbsp;{{ $antecedente->descripcion }}</td>
                            <?php  
                              if($x==3):
                                $x=1;
                            ?>
                              </tr>
                            <?php 
                              else:
                                $x++;
                              endif;
                              ?>
                    @endforeach
                </table>
            </div>
          </fieldset>
          <fieldset>
            <div class="panel panel-info panel-bordered panel-sm">
              <div class="panel-heading">
                <div class="panel-title text-center">
                  <b>Antecedentes de Alergias</b>
                </div>
              </div>
              <div class="panel-body">
                <table class="display" id="tblAntecedente" cellspacing="0" width="100%" style="margin-top:5px;">
                  <?php $x=1; ?>
                      <tr>
                        <td>1.-</td>
                        <td>Medicamentos</td>
                        <td>Detallar: </td>
                        <td><input type="text" class="form-control" value="<?php echo $antecedente_pac->alergia_medicamentos ?>" name="alergia_medicamentos" id="alergia_medicamentos" style="text-align:left"></td>
                      </tr>
                      <tr>
                        <td>2.-</td>
                        <td>Alimentos</td>
                        <td>Detallar: </td>
                        <td><input type="text" class="form-control" value="{{$antecedente_pac->alergia_alimentos}}" name="alergia_alimentos" id="alergia_alimentos" style="text-align:left"></td>
                      </tr>
                      <tr>
                        <td>3.-</td>
                        <td>Picaduras de Insextos</td>
                        <td>Detallar: </td>
                        <td><input type="text" class="form-control" value="{{$antecedente_pac->alergia_picadura_insectos}}" name="alergia_picadura_insectos" id="alergia_picadura_insectos" style="text-align:left"></td>
                      </tr>
                      <tr>
                        <td>4.-</td>
                        <td>Otros</td>
                        <td>Detallar: </td>
                        <td><input type="text" class="form-control" value="{{$antecedente_pac->alergia_otros}}" name="alergia_otros" id="alergia_otros" style="text-align:left"></td>
                      </tr>
                </table>
            </div>
          </fieldset>
          <fieldset>
            <div class="panel panel-info panel-bordered panel-sm">
              <div class="panel-heading">
                <div class="panel-title text-center">
                  <b>Antecedentes de Vacunación</b>
                </div>
              </div>
              <div class="panel-body">
                <table class="display" id="tblAntecedente" cellspacing="0" width="100%" style="margin-top:5px;">
                      <tr>
                        <td>1.-</td>
                        <td>&nbsp;Antitetanica</td>
                        <td>Cuantas dosis: </td>
                        <td width="30%"><input type="text" max="30%" class="form-control" value="{{$antecedente_pac->vacuna_antitetano_dosis}}" name="vacuna_antitetano_dosis" id="vacuna_antitetano_dosis"></td>
                        <td>Fecha ultima dosis: </td>
                        <td><input type="date" class="form-control" value="{{$antecedente_pac->vacuna_antitetano_fecha}}" name="vacuna_antitetano_fecha" id="vacuna_antitetano_fecha" style="text-align:left"></td>
                      </tr>
                      <tr>
                        <td>2.-</td>
                        <td>&nbsp;Hepatites</td>
                        <td>Cuantas dosis: </td>
                        <td width="30%"><input type="text" max="30%" class="form-control" value="{{$antecedente_pac->vacuna_hepatitis_dosis}}" name="vacuna_hepatitis_dosis" id="vacuna_hepatitis_dosis"></td>
                        <td>Fecha ultima dosis: </td>
                        <td><input type="date" class="form-control" value="{{$antecedente_pac->vacuna_hepatitis_fecha}}" name="vacuna_hepatitis_fecha" id="vacuna_hepatitis_fecha" style="text-align:left"></td>
                      </tr>
                      <tr>
                        <td>3.-</td>
                        <td>&nbsp;Influenza</td>
                        <td>Cuantas dosis: </td>
                        <td width="30%"><input type="text" max="30%" class="form-control" value="{{$antecedente_pac->vacuna_influenza_dosis}}" name="vacuna_influenza_dosis" id="vacuna_influenza_dosis"></td>
                        <td>Fecha ultima dosis: </td>
                        <td><input type="date" class="form-control" value="{{$antecedente_pac->vacuna_influenza_fecha}}" name="vacuna_influenza_fecha" id="vacuna_influenza_fecha" style="text-align:left"></td>
                      </tr>
                      <tr>
                        <td>4.-</td>
                        <td>&nbsp;Covid-19</td>
                        <td>Cuantas dosis: </td>
                        <td width="30%"><input type="text" max="30%" class="form-control" value="{{$antecedente_pac->vacuna_covid_dosis}}" name="vacuna_covid_dosis" id="vacuna_covid_dosis"></td>
                        <td>Fecha ultima dosis: </td>
                        <td><input type="date" class="form-control" value="{{$antecedente_pac->vacuna_covid_fecha}}" name="vacuna_covid_fecha" id="vacuna_covid_fecha" style="text-align:left"></td>
                      </tr>
                </table>
            </div>
          </fieldset>
          <fieldset>
            <div class="panel panel-info panel-bordered panel-sm">
              <div class="panel-heading">
                <div class="panel-title text-center">
                  <b>Recibe algún tratamiento médico actual o toma alguna medicación de forma continua</b>
                </div>
              </div>
              <div class="panel-body">
                <textarea class="form-control" cols="5" id="tratamiento_actual" name="tratamiento_actual" placeholder="Relato m&eacute;dico o caracteristicas">{{$antecedente_pac->tratamiento_actual}}</textarea>
            </div>
          </fieldset>
          <div class="box-body">
            <div class="form-group col-sm-12">
              <input type="hidden" name="id_paciente" id="id_paciente" value="<?php echo $id_paciente?>">
              <input type="hidden" name="id_tipo_atencion" id="id_tipo_atencion" value="<?php echo $tipo_atencion?>">
              <input type="hidden" name="id_cita" id="id_cita" value="<?php echo $id_cita?>">
                {!! Form::submit('Actualizar', ['class' => 'btn btn-success pull-right btn-block btn-sm']) !!}
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>