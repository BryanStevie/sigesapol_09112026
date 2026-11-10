<title>Sistema de Gestión de Salud Policial</title>

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

/*
tr:nth-child(2n) {
    background: none repeat scroll 0 0 #edebeb;
}  
*/

#tablemodalm{
	/*
	width: 30em;
	overflow-x: auto;
	white-space: nowrap;
	*/
	
	/*background-color: #fed9ff; 
      width: 600px; 
      height: 150px; 
      overflow-x: hidden;
      overflow-y: auto; 
      text-align: center; 
      padding: 20px;*/
}
</style>

<body class="hold-transition skin-blue sidebar-mini">
    <div>
        <section class="content">
            <div class="row">
			 
        <div class="col-md-12">
          <div class="box box-primary">
            <br>
              <div class="box-body">
                <?php //print_r($medicos);?>
					
					<form name="frm" id="frm" class="form-horizontal" role="form" method="post"  action="{{ route('anular_paciente_emergencia') }}" onSubmit="return validarDni()" data-toggle="validator">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="id" id="id" value="<?php echo $id?>" />
					<input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
					<div class="form-group">
            <label class="col-lg-4 control-label">Documento de Identidad</label>
            <div class="col-lg-8">
              <input type="text" value="<?php echo $emergencia->dni_beneficiario;?>" name="dni" id="dni" required="" class="form-control" placeholder="DNI" maxlength="8" onKeyPress="return IsNumber(event)" readonly="readonly" >
            </div>
          </div>
          <div class="form-group">
						<div class="col-lg-12">
              <label>Motivo de Eliminación de Paciente al momento de atención</label><br/>
							<textarea tabindex="73" rows="4" cols="50" type="text" class="form-control" name="motivo_anulacion" id="motivo_anulacion"></textarea>
						</div>
					</div>
					<div style="margin-top:10px" class="form-group">
						<div class="col-sm-12 controls">
							<input type="submit" value="Guardar" class="btn btn-success" id="btnReg">
							<a href="/all_citas_medico_paciente" class="btn btn-danger" data-dismiss="modal">Cancelar</a>
						</div>
					</div> 
					</form>
              </div>
          </div>
          
        </div>
          </div>
        </section>
    </div>
    