<title>Sistema de Farmacia</title>

<style>
/*****************/
.modal-dialog {
	width: 90%;
  }
  
#tablemodal{
    border-spacing: 0;
    display: flex;/*Se ajuste dinamicamente al tamano del dispositivo**/
    max-height: 80vh; /*El alto que necesitemos**/
    overflow-y: auto; /**El scroll verticalmente cuando sea necesario*/
    overflow-x: hidden;/*Sin scroll horizontal*/
    table-layout: fixed;/**Forzamos a que las filas tenga el mismo ancho**/
    width: 98vw; /*El ancho que necesitemos*/
    border:1px solid #c4c0c9;
}

#tablemodal thead{
    background-color: #e2e3e5;
    position: fixed !important;
}


#tablemodal th{
    border-bottom: 1px solid #c4c0c9;
    border-right: 1px solid #c4c0c9;
}

#tablemodal th{
    font-weight: normal;
    margin: 0;
    max-width: 9.5vw; 
    min-width: 9.5vw;
    word-wrap: break-word;
    font-size: 10px;
	font-weight:bold;
    height: 3.5vh !important;
	line-height:12px;
	vertical-align:middle;
	/*height:20px;*/
    padding: 4px;
    border-right: 1px solid #c4c0c9;
}

#tablemodal td{
    font-weight: normal;
    margin: 0;
    max-width: 9.5vw; 
    min-width: 9.5vw;
    word-wrap: break-word;
    font-size: 11px;
    height: 3.5vh !important;
    padding: 4px;
    border-right: 1px solid #c4c0c9;
}

#tablemodal tbody tr:hover td, #tablemodal tbody tr:hover th {
  /*background-color: red!important;*/
  font-weight:bold;
  /*mix-blend-mode: difference;*/
  
}


#tablemodalm{
	
}
</style>

<script type="text/javascript">

$(document).ready(function() {
    
});

</script>


<body class="hold-transition skin-blue sidebar-mini">
  <div>
		<section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <br>
              <div class="box-body">
				        <div class="table-responsive">
                  <table class="table table-bordered table-sm" id="tablemodalm">
                      <tbody id="muestra-atenciones" style="padding-top:32px">
                        <?php $x=0;?>
        							<?php foreach($atenciones as $rowAtencion): ?>
                          <?php if($x==0): $x++; ?>
                          <tr><td colspan="6"><b>NOMBRE DEL BENEFICIARIO :  <?php echo $rowAtencion->_nrodocbene?> - <?php echo $rowAtencion->_beneficiario?> </b> </td></tr>
                          <tr>
                            <th>FECHA_ATENCION</th>
                            <th>ESTABLECIMIENTO</th>
                            <th>TIPO DE ATENCION</th>
                            <th>SERVICIO</th>
                            <th>MEDICO</th>
                            <th>DIAGNOSTICO</th>
                            <!--th class="text-center">ATENCIONES</th-->
                          </tr>
                        <?php endif;?>
            							<tr>
                            <?php
                                $originalDate1 = $rowAtencion->_fecha;
                                $fecha2 = date("d-m-Y", strtotime($originalDate1));
                                ?> 
            								<td><?php echo $fecha2?></td>
            								<td><?php echo $rowAtencion->establecimiento?></td>
            								<td><?php echo $rowAtencion->_atencion?></td>
                            <td><?php echo $rowAtencion->_servicio?></td>
            								<td><?php echo $rowAtencion->_medico?></td>
                            <td><?php echo $rowAtencion->_diagnostico?></td>
            								<!--td class="text-center">
          										<div data-toggle="tooltip" data-placement="top" data-html="true" title="<b>Ver Atencion</b>">
          											<a href="javascript:void(0)" onClick="anularCita({{$rowAtencion->id}})" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
          										</div>
            								</td-->
            							</tr>
        							<?php endforeach;?>
					          </tbody>
                  </table>
                </div>
              </div>
          </div>
        </div>
      </div>
    </section>
  </div>

    
    