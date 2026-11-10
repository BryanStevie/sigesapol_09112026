@extends('frontend.layouts.master')
@section('renderjs')

<style>
  #programacionAsistencial {
    padding: 15px;
    background: #fff;
    border: 1px solid #ddd;
    border-top: none;
    border-radius: 0 0 4px 4px;
  }

  /* Estilos para las pestañas */
  .tab-content {
    padding: 20px 0;
  }
  .nav-tabs {
    border-bottom: 2px solid #ddd;
  }
  .nav-tabs > li > a {
    margin-right: 2px;
    line-height: 1.42857143;
    border: 1px solid transparent;
    border-radius: 4px 4px 0 0;
    color: #555;
  }
  .nav-tabs > li > a:hover {
    border-color: #eee #eee #ddd;
  }
  .nav-tabs > li.active > a,
  .nav-tabs > li.active > a:hover,
  .nav-tabs > li.active > a:focus {
    color: #555;
    cursor: default;
    background-color: #fff;
    border: 1px solid #ddd;
    border-bottom-color: transparent;
    font-weight: bold;
  }
</style>

<script>
function calcularTotales() {
    let total1 = 0;
    let total2 = 0;

    $('.col1').each(function () {
        total1 += parseFloat($(this).val()) || 0;
    });

    $('.col2').each(function () {
        total2 += parseFloat($(this).val()) || 0;
    });

    $('#total_col1').val(total1);
    $('#total_col2').val(total2);
}

$(document).on('input', '.col1, .col2', function () {
    calcularTotales();
});

$('#btnBuscar').on('click', function () {

    let periodo = $('#periodo').val();
    let id_personal = $('#id_personal').val();

    $.ajax({
        url: '/buscar_programacion_asistencial_1',
        type: 'GET',
        data: {
            periodo: periodo,
            id_personal: id_personal
        },
        success: function (response) {

            let html = '';

            if (response.length > 0) {

                response.forEach((item) => {
                    html += `
                        <tr>
                            <td>${item.periodo}</td>
                            <td>${item.id_establecimiento}</td>
                            <td>${item.horas_programadas}</td>
                            <td>${item.horas_efectivas}</td>
                            <td><button class="btn btn-info btn-sm">Ver</button></td>
                        </tr>
                    `;
                });

            } else {

                html = `
                    <tr>
                        <td colspan="6" style="text-align:center; font-weight:bold;">
                            SIN REGISTROS
                        </td>
                    </tr>
                `;
            }

            $('#tablaResumen tbody').html(html);
        }
    });
});

$('#btnNuevo').on('click', function () {
    var id_personal = $('#id_personal').val();
    var periodo = encodeURIComponent($('#periodo').val());
    var ipress = 0;
    if (!periodo) {
        alert('Ingrese el periodo');
        return;
    }

    window.location.href = '/editar_programacion_asistencial_1/' + id_personal + '/' + periodo + '/' + ipress;
});

$('#btnGuardar').on('click', function () {

    let valido = true;

    $('.col1').each(function (index) {

        let prog = parseFloat($(this).val()) || 0;
        let efec = parseFloat($('.col2').eq(index).val()) || 0;
        let concepto = $('input[name="concepto[]"]').eq(index).val();

        if (concepto == '21' && prog != efec) {
            valido = false;

            $(this).css('border', '2px solid red');
            $('.col2').eq(index).css('border', '2px solid red');

            alert('Error: En FERIADOS las Horas Programadas y Horas Efectivas no deben ser diferentes');
            return false;
        }

        if (prog < efec) {
            valido = false;

            $(this).css('border', '2px solid red');
            $('.col2').eq(index).css('border', '2px solid red');

            alert('Error: Horas Programadas no pueden ser menores que Horas Efectivas');
            return false;
        }

        $(this).css('border', '');
        $('.col2').eq(index).css('border', '');

    });

    if (!valido) return;
    let formData = $('#formProgramacion').serialize();

    $.ajax({
        url: '/guardar_programacion_asistencial_1',
        type: 'POST',
        data: formData,

        success: function (response) {

            alert(response.message);

            if (response.redirect) {
                window.location.href = response.redirect;
            }

        },

        error: function (error) {
            console.log(error);
            alert('Error al guardar');
        }
        
    });
    
});
</script>
@endsection

@section('content')

<div class="container">
  <form id="formProgramacion">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Editar Personal de Salud</h3> 
        </div>
        <div class="panel-body">
          
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation">
              <a href="" aria-controls="programacionAsistencial" role="tab" data-toggle="tab">PROGRAMACIÓN ASISTENCIAL</a>
            </li>
          </ul>
          
          <div class="tab-content">
     <input type="hidden" id="establecimiento" name="establecimiento" class="form-control" value="{{$establecimiento}}" maxlength="7" required>
      <input type="hidden" id="id_persona" name="id_persona" class="form-control" value="{{$id_persona}}" maxlength="7" required>
       <input type="hidden" id="periodo" name="periodo" class="form-control" value="{{$periodo}}" maxlength="7" required>
              <div class="panel panel-primary">
                  <div class="panel-body" style="padding:0; overflow-x: auto;">
                      <div class="panel-heading">
                          <strong>Periodo de creacion {{ $periodo }}</strong>
                          
                      </div>
                         </div>
                         <div class="row">
                            <div class="col-md-12">
                             <div class="table-responsive">
                                            <table class="table table-bordered" id="">
                            <thead>
                                <tr>
                                    <th>PNP</th>
                                    <th>SUSALUD</th>
                                    <th>CLASE</th>
                                    <th>TIPO PROG.</th>
                                    <th>H. PROG.</th>
                                    <th>H. EFEC.</th>
                                </tr>
                            </thead>

<tbody>
    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" disabled><input type="hidden" name="check2[]" value="0"></td>
        <td>DESCUENTOS</td>
        <td>DESCANSO MEDICO</td>
        <input type="hidden" name="concepto[]" value="24">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c24 ? $c24->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c24 ? $c24->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" disabled><input type="hidden" name="check2[]" value="0"></td>
        <td></td>
        <td>FERIADOS</td>
        <input type="hidden" name="concepto[]" value="21">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c21 ? $c21->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c21 ? $c21->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" disabled><input type="hidden" name="check2[]" value="0"></td>
        <td></td>
        <td>OTROS</td>
        <input type="hidden" name="concepto[]" value="23">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c23 ? $c23->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c23 ? $c23->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" disabled><input type="hidden" name="check2[]" value="0"></td>
        <td></td>
        <td>VACACIONES</td>
        <input type="hidden" name="concepto[]" value="22">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c22 ? $c22->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c22 ? $c22->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check2[]" value="1"></td>
        <td>EXTRAMUROS</td>
        <td>CAPACITACION</td>
        <input type="hidden" name="concepto[]" value="4">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c4 ? $c4->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c4 ? $c4->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" disabled><input type="hidden" name="check2[]" value="0"></td>
        <td></td>
        <td>COMISION ADMINISTRATIVO</td>
        <input type="hidden" name="concepto[]" value="8">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c8 ? $c8->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c8 ? $c8->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" disabled><input type="hidden" name="check2[]" value="0"></td>
        <td></td>
        <td>COMISION ASISTENCIALES</td>
        <input type="hidden" name="concepto[]" value="9">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c9 ? $c9->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c9 ? $c9->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" disabled><input type="hidden" name="check2[]" value="0"></td>
        <td></td>
        <td>COMISION OTROS</td>
        <input type="hidden" name="concepto[]" value="10">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c10 ? $c10->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c10 ? $c10->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" disabled><input type="hidden" name="check2[]" value="0"></td>
        <td></td>
        <td>DOMICILIARIA</td>
        <input type="hidden" name="concepto[]" value="12">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c12 ? $c12->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c12 ? $c12->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" disabled><input type="hidden" name="check2[]" value="0"></td>
        <td></td>
        <td>OTROS EXTRAMURAL</td>
        <input type="hidden" name="concepto[]" value="16">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c16 ? $c16->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c16 ? $c16->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" disabled><input type="hidden" name="check2[]" value="0"></td>
        <td></td>
        <td>PREVENTIVO PROMOCIONAL</td>
        <input type="hidden" name="concepto[]" value="17">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c17 ? $c17->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c17 ? $c17->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check2[]" value="1"></td>
        <td>INTRAMUROS</td>
        <td>ADMINISTRATIVO</td>
        <input type="hidden" name="concepto[]" value="1">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c1 ? $c1->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c1 ? $c1->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" disabled><input type="hidden" name="check2[]" value="0"></td>
        <td></td>
        <td>APOYO DIAGNOSTICO</td>
        <input type="hidden" name="concepto[]" value="2">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c2 ? $c2->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c2 ? $c2->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" disabled><input type="hidden" name="check2[]" value="0"></td>
        <td></td>
        <td>APOYO TRATAMIENTO</td>
        <input type="hidden" name="concepto[]" value="3">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c3 ? $c3->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c3 ? $c3->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check2[]" value="1"></td>
        <td></td>
        <td>CAPACITACION PERFECCIONAMIENTO</td>
        <input type="hidden" name="concepto[]" value="5">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c5 ? $c5->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c5 ? $c5->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check2[]" value="1"></td>
        <td></td>
        <td>CENTRO OBSTETRICO</td>
        <input type="hidden" name="concepto[]" value="6">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c6 ? $c6->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c6 ? $c6->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check2[]" value="1"></td>
        <td></td>
        <td>CENTRO QUIRURGICO</td>
        <input type="hidden" name="concepto[]" value="7">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c7 ? $c7->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c7 ? $c7->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" disabled><input type="hidden" name="check1[]" value="0"></td>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check2[]" value="1"></td>
        <td></td>
        <td>COMPLEMENTARIAS</td>
        <input type="hidden" name="concepto[]" value="11">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c11 ? $c11->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c11 ? $c11->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check2[]" value="1"></td>
        <td></td>
        <td>CONSULTA EXTERNA</td>
        <input type="hidden" name="concepto[]" value="13">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c13 ? $c13->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c13 ? $c13->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check2[]" value="1"></td>
        <td></td>
        <td>EMERGENCIA</td>
        <input type="hidden" name="concepto[]" value="14">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c14 ? $c14->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c14 ? $c14->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check2[]" value="1"></td>
        <td></td>
        <td>HOSPITALIZACION</td>
        <input type="hidden" name="concepto[]" value="15">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c15 ? $c15->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c15 ? $c15->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check2[]" value="1"></td>
        <td></td>
        <td>PREVENTIVO PROMOCIONAL INTRAMURAL</td>
        <input type="hidden" name="concepto[]" value="18">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c18 ? $c18->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c18 ? $c18->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check1[]" value="1"></td>
        <td><input type="checkbox" checked disabled><input type="hidden" name="check2[]" value="1"></td>
        <td></td>
        <td>PROCEDIMIENTOS</td>
        <input type="hidden" name="concepto[]" value="19">
        <td><input type="number" class="col1" name="col1[]" value="{{ $c19 ? $c19->horas_programadas : 0 }}"></td>
        <td><input type="number" class="col2" name="col2[]" value="{{ $c19 ? $c19->horas_efectivas : 0 }}"></td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: right;">TOTALES</td>
<td><input type="number" id="total_col1" value="{{ $total_col1 }}" readonly></td>
<td><input type="number" id="total_col2" value="{{ $total_col2 }}" readonly></td>
    </tr>
</tbody>
                        </table>
                    </div>
                </div>
                         </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="btnGuardar">
                        <i class="fa fa-save"></i> Guardar
                    </button>

                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

                </div>
                 </div>
                  </div>
              </div>
            </div>
        
      </div>
    </div>
  </div>
  </form>
</div>

@include('frontend.includes.footer')
@endsection