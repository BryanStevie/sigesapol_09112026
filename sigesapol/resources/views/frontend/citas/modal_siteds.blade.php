<title>Sistema de Gestion Hospitalaria</title>

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
  font-weight:bold;  
}


#tablemodalmc{
	
}
</style>

<script>
  // TAGSITEDS
  function generarCodigoSiteds() {
    $("#btn_generate_siteds").prop("disabled", true);
    $("#codigo_siteds").prop("readonly", true); 

    Swal.fire ({
      title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Generando Código SITEDS</span></div>",
      html: "<div style='margin: 0px 10px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
      showCancelButton: false,
      showConfirmButton: false,
      allowOutsideClick: false,
      allowEscapeKey: false,
      onBeforeOpen: () => {
        Swal.showLoading ()
      },
    })
    
    const id= {!! json_encode($id) !!};
    const establecimiento = {!! json_encode($establecimiento) !!};
    const consultorio = {!! json_encode($consultorio) !!};

    const servicioId = consultorio && consultorio.id ? consultorio.id : null;

    const numberDocument = $("#dni").val();
    const id_tipo_doc_ident = $("#id_tipo_doc_ident").val();
    const body = {
      "idRemitente": establecimiento.codigo || "00013591",
      "idCorrelativo": id,
      "tiDoPaciente": id_tipo_doc_ident,
      "nuDoPaciente": numberDocument,
      "noResponsableAut": "",
      "noPaResponsableAut": "",
      "noMaResponsableAut": "",
      "nuDoResponsableAut": "",
      "coAdmisionista": numberDocument,
      "beMaxInicial": "0",
      "coTiMoneda": "1",
      "coPagoFijo": "0",
      "coCalServicio": "VS",
      "coPagoVariable": "100",
      "flagCaGarantia": "0",
      "deFlagCartaGarantia": "Sigesapol 1.0",
      "tipoAtencionId": "19", // TODO
      "servicioId": servicioId
    };
    $.ajax({
      url:'/ws-dirsapol-siteds',
      type:'POST',
      contentType: "application/json",
      dataType: "json",
      data: JSON.stringify(body),
      success: (result) => {
        $("#codigo_siteds").val(result.nuAutorizacion)
        Swal.fire({
            icon: "success",
            title:  "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Código SITEDS</span></div>",
            html: `<div style='margin: 0px 10px'><span style='color: #000000; font-size: 18px'>${result.message || " Operación Exitosa"}</span></div>`,
            confirmButtonText: 'ENTENDIDO',
            allowOutsideClick: false,
            allowEscapeKey: false,
        });
      },
      error: function(xhr, status, error){
        $("#codigo_siteds").val("");
        $("#codigo_siteds").prop("readonly", false); 
        $("#btn_generate_siteds").prop("disabled", false);
        const messageError = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Servicio no disponible. intentelo en un minutos.";
        Swal.fire({
            icon: 'error',
            title: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 20px'>Error Inesperado</span></div>",
            html: `<div style='margin: 0px 10px; padding: 0px 20px'><span style='color: #000000; font-size: 18px'>${messageError}</span></div>`,
            confirmButtonText: 'ENTENDIDO',
        });
      }, 
      complete: function(xhr, status) {
        Swal.hideLoading();
      }
    })
  }

</script>

<body class="hold-transition skin-blue sidebar-mini">
    <div>
        <section class="content">
            <div class="row">
			 
        <div class="col-md-12">
          <div class="box box-primary">
            <br>
              <div class="box-body">
                	<form name="frmsiteds" id="frmsiteds" class="form-horizontal" role="form" method="post"  action="{{ route('update_siteds') }}" data-toggle="validator">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="id" id="id" value="<?php echo $id?>" />
          <input type="hidden" name="id_tipo_doc_ident" id="id_tipo_doc_ident" value="{{ $asegurado->id_tipo_doc_ident }}">
                    <input type="hidden" name="retorno" id="retorno" value="<?php echo $retorno?>" />
					<input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
					<div class="form-group">
						<label class="col-lg-4 control-label">Documento de Identidad</label>
						<div class="col-lg-8">
							<input type="text" value="<?php echo $asegurado->nro_doc_ident;?>" name="dni" id="dni" required="" class="form-control" placeholder="DNI" maxlength="8" onKeyPress="return IsNumber(event)" readonly="readonly" >
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-lg-4 control-label">Numero de SITEDS</label>
						<div class="col-lg-8">
              <div class="input-group">
                <input type="text" class="form-control"  id="codigo_siteds" name="codigo_siteds"  onkeypress="return esEntero(event);"
                style="text-transform: uppercase;border-color: red !important;border: 2px red solid !important"   maxlength="12"  pattern="[0-9]*" readonly />
                <span class="input-group-btn">
                  <button type="button" id="btn_generate_siteds" onclick="generarCodigoSiteds();"
                    class="btn btn-info">Generar</button>
                </span>
              </div>
							{{-- <input  type="text" class="form-control"  id="codigo_siteds" name="codigo_siteds"  onkeypress="return esEntero(event);" value="" 
                                        style="text-transform: uppercase;border-color: red !important;border: 2px red solid !important"   maxlength="12"  pattern="[0-9]*"> --}}
						</div>
					</div>
					
					<div style="margin-top:10px" class="form-group">
						<div class="col-sm-12 controls">
							<input value="Guardar" onclick="validateSitedsFrm()"  tabindex="99999" class="btn btn-success" id="btnReg">
                        <?php if($retorno==1): ?>
                            <a href="/all_citas_medico_paciente" class="btn btn-danger" data-dismiss="modal">Cancelar</a>
                        <?php else: ?>
                            <a href="/all_citas_medico_atenciones" class="btn btn-danger" data-dismiss="modal">Cancelar</a>
                        <?php endif; ?>
						</div>
					</div> 
					</form>
              </div>
          </div>
          <!-- /.box -->
        </div>
        <!--/.col (left) -->
          </div>
          <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    
    