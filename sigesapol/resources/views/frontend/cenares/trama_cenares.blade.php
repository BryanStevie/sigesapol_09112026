<?php
    use Carbon\Carbon;
?>
@extends('frontend.layouts.master2')

@section('renderjs')
<script>
    
  $(document).ready(function () {
    $('#example').DataTable();

    $('#fechaDesde').appendDtpicker({
    "dateOnly": true,
            "autodateOnStart": false,
            "minuteInterval": 5,
            "amPmInTimeList": false,
            "locale": "es",
            "closeOnSelected": true,
            "dateFormat": "DD-MM-YYYY"
    });

    $('#fechaHasta').appendDtpicker({
    "dateOnly": true,
            "autodateOnStart": false,
            "minuteInterval": 5,
            "amPmInTimeList": false,
            "locale": "es",
            "closeOnSelected": true,
            "dateFormat": "DD-MM-YYYY"
            //"dateFormat": "YYYY-MM-DD hh:mm",
    });   
  });
</script>
<style>

</style>
@endsection
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">TRAMA CENARES</h3>                    
        </div>
        <div class="panel-body">          
          <div class="panel panel-primary filterable" >
            <div class="panel-body">
              <div class="col-sm-12">
                  <form id="frm_transferencia" class="form-horizontal" method="post" action="{{ route('listar_trama_cenares')}}">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <div clas="row">       
                          <div class="row">                               
                            <div class="col-md-2">                            
                                <label class="help-block">Fecha Desde</label>
                                <input class="form-control input-sm" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde" value="<?php echo $fechaDesde ?>" maxlength="10">
                                
                            </div>                        
                            <div class="col-md-2">
                                <br/><br/>
                                <input class="form-control btn btn-info input-sm" value="Buscar" type="submit">
                            </div>
                          </div>
                      </div>                    
                      <div class="row">                        
                      </div>
                  </form>
                  <div class="row">
                    <div class="col-md-12">
                        <div class="panel-group">
                            <br/>
                            <div class="panel panel-info">
                                <div class="panel-body"> 
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr style='font-size: 11px;'>
                                                    <th style="border:1px solid #999999">#</th>
                                                    <th style="border:1px solid #999999">Cod Pre</th>
                                                    <th style="border:1px solid #999999">SIGA</th>
                                                    <th style="border:1px solid #999999">COD MED</th>
                                                    <th style="border:1px solid #999999">SALDO</th>
                                                    <th style="border:1px solid #999999">PRECIO</th>
                                                    <th style="border:1px solid #999999">INGRESOS</th>
                                                    <th style="border:1px solid #999999">FECHA ING</th>
                                                    <th style="border:1px solid #999999">SAL 1</th>
                                                    <th style="border:1px solid #999999">SAL 2</th>
                                                    <th style="border:1px solid #999999">SAL 3</th>
                                                    <th style="border:1px solid #999999">SAL 4</th>
                                                    <th style="border:1px solid #999999">SAL 5</th>
                                                    <th style="border:1px solid #999999">SALIDAS</th>
                                                    <th style="border:1px solid #999999">STOCK</th>
                                                    <th style="border:1px solid #999999">FECHA EXP</th>
                                                    <th style="border:1px solid #999999">LOTE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (isset($result)):
                                                    foreach ($result as $key => $rowKardex):
                                                        ?>
                                                        <tr  style='font-size: 11px;'>
                                                            <td align="center" class="con0"><?= ($key + 1) ?></td>  
                                                            <td align="left" class="con1"><?= $rowKardex->cod_pre ?></td>
                                                            <td class="left" class="con0"><?= $rowKardex->cod_siga ?></td>
                                                            <td class="left" class="con0"><?= $rowKardex->cod_med ?></td>
                                                            <td class="con1" align="right"><?= $rowKardex->saldo ?></td>
                                                            <td align="left" class="con1"><?= $rowKardex->precio ?></td>
                                                            <td class="left" class="con0"><?= $rowKardex->ingresos ?></td>
                                                            <td class="left" class="con0"><?= $rowKardex->fec_ing ?></td>
                                                            <td class="con1" align="right"><?= $rowKardex->st_sal1 ?></td>
                                                            <td align="left" class="con1"><?= $rowKardex->st_sal2 ?></td>
                                                            <td class="left" class="con0"><?= $rowKardex->st_sal3 ?></td>
                                                            <td class="left" class="con0"><?= $rowKardex->st_sal4 ?></td>
                                                            <td class="con1" align="right"><?= $rowKardex->st_sal5 ?></td>
                                                            <td class="con1" align="right"><?= $rowKardex->t_salidas ?></td>
                                                            <td align="left" class="con1"><?= $rowKardex->stock_final ?></td>
                                                            <td class="left" class="con0"><?= $rowKardex->fec_exp ?></td>
                                                            <td class="left" class="con0"><?= $rowKardex->lote ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>   
            </div>
        </div>
      </div>
    </div>
</div>

@include('frontend.includes.footer')
@endsection
