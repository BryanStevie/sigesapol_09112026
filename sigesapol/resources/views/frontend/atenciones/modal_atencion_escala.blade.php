<style>
  .app-model-escala .radio {
    margin: 0;
  }
  .app-model-escala h3 {
    margin-top: 0;
  }
  /* Menu */
  .app-model-escala .nav,
  .app-model-escala .nav li {
    background-color: #eeeeee;
  }
  .app-modal-dialog-900 {
    max-width: 900px!important;
  }
  /*
  .app-model-escala .nav li.active a {
    font-weight: bold;
  }
  */
  .app-model-escala .nav li a:hover {
    background-color: #fff;
  }
  .app-model-escala .nav li a {
    outline: 0;
  }
  /* Tabla */
  .app-model-escala .table tr th {
    color: #3c763d;
    background-color: #e0e9e0;
    text-transform: uppercase;
  }
  .app-model-escala .total tr th:nth-child(2) {
    width: 58px!important;
  }  
  .app-model-escala .tipo-3 tr th:nth-child(2) {
    width: auto!important;
    text-align: right!important;
  }
  .app-model-escala .table tr th:nth-child(2),
  .app-model-escala .table tr th:nth-child(3),
  .app-model-escala .table tr td:nth-child(2),
  .app-model-escala .table tr td:nth-child(3) {
    width: 40px;
    text-align: center;
  }
  .app-model-escala .table tr td .radio {
    height: 22px;
    width: 40px;
  }
  .app-model-escala .table tr td .radio label {
    margin-left: auto;
    margin-right: auto;
    padding-left: 24px
  }
  
  @media (min-width: 768px) {
    .app-modal-reset .modal-dialog {
      width: 600px;
      margin: 30px auto;
    }
  }

</style>
<script>
  var aEscalaId = new Array(<?php echo count($aEscala) ?>);
  var aEscalaValue = new Array(<?php echo count($aEscala) ?>);
  var inicializarEscala = function() {
    <?php
    $index = 0;
    foreach ($aEscala as $kEscala => $vEscala) : ?>
      aEscalaId[<?php echo $index ?>] = 0;
      <?php if ($vEscala['tipo'] == 3) { ?>
        aEscalaValue[<?php echo $index ?>] = '';
      <?php } else { ?>
        aEscalaValue[<?php echo $index ?>] = 0;
      <?php } ?>
    <?php
      $index++;
    endforeach; ?>
  }
  $(document).ready(function() {
    inicializarEscala();
    $("#btnEscalaGuardar").on("click", function() {
      inicializarEscala();
      $('.escala_input input:checked').each(function() {
        aEscalaId[$(this).attr('data-index')] = $(this).attr('data-id');
        if ($(this).attr('data-tipo') == 3) {
          if (aEscalaValue[$(this).attr('data-index')] != '') {
            aEscalaValue[$(this).attr('data-index')] += ', ';
          }
          aEscalaValue[$(this).attr('data-index')] += $(this).attr('data-orden') + $(this).val();
        } else {
          aEscalaValue[$(this).attr('data-index')] = aEscalaValue[$(this).attr('data-index')] + parseInt($(this).val(), 10);
        }        
      });
      $('#modalEscala .modal-footer button').attr("disabled", true);
      $.ajax({
        type: "POST",
        url: "{{ route('formulario_atencion_escala_resultado') }}",
        data: {
          id: aEscalaId,
          value: aEscalaValue
        },
        dataType: 'json',
        success: function(json) {
          var data = '';
          $.each(json, function(index) {
            if (data != '') {
              data += "\n";
            }
            data += json[index].escala + ': ' + json[index].puntaje;
          });
          $('#aspectos_generales').val(data);
          $('#modalEscala').modal('hide')
          $('#modalEscala .modal-footer button').attr("disabled", false);
        }
      });
    });
    $("#modalEscala .tab-pane input").on("click", function() {
      var id = $(this).attr("data-id");
      var inputs = $('#e_' + id + ' input:checked');
      var total = 0;
      var stotal = '';
      var dataOrden = 1;
      var dataTipo = $(this).attr("data-tipo");
      $.each(inputs, function(index) {
        dataOrden = $(inputs[index]).attr("data-orden");
        if (dataTipo == 3) {
          if (stotal != '') {
            stotal += ', ';
          }
          stotal += dataOrden + $(inputs[index]).val();
        } else {
          total += parseInt($(inputs[index]).val(), 10);
        }
      });
      if (dataTipo == 3) {
        $("#modalEscala #e_" + id + " .total .rtotal").html(stotal);
      } else {
        $("#modalEscala #e_" + id + " .total .rtotal").html(total);
      }      
    });
    $('#addAspectosGenerales').on('click', function () {
      if ($('#addAspectosGenerales').attr("aria-pressed") == 'false') {
        $("#blockAspectosGenerales").show();
      } else {
        $("#blockAspectosGenerales").hide();  
      }
    });
  });
</script>
<?php
$tabActive = 0;
$tabIndex = 0;
?>
<div class="modal fade app-modal-reset" id="modalEscala" tabindex="-1" role="dialog" aria-labelledby="modalEscalaLabel">
  <div class="modal-dialog app-modal-dialog-900" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 class="modal-title">ESCALAS DE VALOR</h5>
      </div>
      <div class="modal-body app-model-escala">
        <ul class="nav nav-tabs" role="tablist">
          <?php foreach ($aEscala as $kEscala => $vEscala) : ?>
            <?php
            $nombreEscala = 'e_' . $kEscala;
            $clase = '';
            if ($tabActive == $tabIndex):
              $clase = ' class="active"';
            endif;
            ?>
            <li role="presentation" <?php echo $clase ?>>
              <a href="#<?php echo $nombreEscala ?>" aria-controls="<?php echo $nombreEscala ?>" role="tab" data-toggle="tab"><?php echo $vEscala['nombre'] ?></a>
            </li>
          <?php
            $tabIndex++;
          endforeach;
          ?>
        </ul>
        <div class="tab-content escala_input">
          <?php
          $tabIndex = 0;

          foreach ($aEscala as $kEscala => $vEscala) : ?>
            <?php
            $nombreEscala = 'e_' . $kEscala;
            $clase = '';
            if ($tabActive == $tabIndex) :
              $clase = ' active';
            endif;
            ?>
            <div role="tabpanel" class="tab-pane<?php echo $clase ?>" id="<?php echo $nombreEscala; ?>">
              <br>
              <?php
              $flagTipo = 0;
              $indiceNivel1 = 1;
              ?>
              <?php foreach ($vEscala['item'] as $kOpciones => $vOpciones) : ?>
                <?php if (!empty($vOpciones['item'])) : ?>
                  <table class="table table-hover table-bordered">
                    <thead>
                      <tr>
                        <th colspan="2"><?php echo $indiceNivel1 . '. ' . $vOpciones['nombre'] ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($vOpciones['item'] as $kItem => $vItem) : ?>
                        <tr>
                          <td><?php echo $vItem['nombre'] ?></td>
                          <td>
                            <?php
                            $nombreInput = 'in_' . $kEscala . '_' . $kOpciones;
                            ?>
                            <div class="radio">
                              <label>
                                <input 
                                  type="radio" name="<?php echo $nombreInput ?>"
                                  data-index="<?php echo $tabIndex ?>" 
                                  data-id="<?php echo $kEscala ?>" 
                                  data-orden="<?php echo $indiceNivel1 ?>" 
                                  data-tipo="<?php echo $vEscala['tipo'] ?>" 
                                  value="<?php echo $vItem['puntaje'] ?>">
                              </label>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                <?php else : ?>
                  <?php if ($flagTipo == 0) : ?>
                    <table class="table table-hover table-bordered">
                      <thead>
                        <tr>
                          <th>Función a evaluar</th>
                          <th>Si</th>
                          <th>No</th>
                        </tr>
                      </thead>
                      <?php $flagTipo = 1; ?>
                    <?php endif; ?>
                    <tr>
                      <td><?php echo $vOpciones['nombre'] ?></td>
                      <td>
                        <div class="radio">
                          <label>
                            <input type="radio" name="<?php echo $nombreEscala . '_' . $kOpciones ?>" data-index="<?php echo $tabIndex ?>" data-id="<?php echo $kEscala ?>" value="0">
                          </label>
                        </div>
                      </td>
                      <td>
                        <div class="radio">
                          <label>
                            <input type="radio" name="<?php echo $nombreEscala . '_' . $kOpciones ?>" data-index="<?php echo $tabIndex ?>" data-id="<?php echo $kEscala ?>" value="1">
                          </label>
                        </div>
                      </td>
                    </tr>
                  <?php endif; ?>
                <?php $indiceNivel1++; ?>
                <?php endforeach; ?>
                <?php if ($flagTipo == 1) : ?>
                    </tbody>
                  </table>
                <?php endif; ?>
                <table class="table table-hover table-bordered total tipo-<?php echo $vEscala['tipo'] ?>">
                  <thead>
                    <tr>
                      <th>Total</th>
                      <th class="rtotal">0</th>
                    </tr>
                  </thead>
                </table>
            </div>
            <?php $tabIndex++; ?>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary btnEscalaGuardar" id="btnEscalaGuardar">Aceptar</button>
      </div>
    </div>
  </div>
</div>