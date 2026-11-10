<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>
<script>
var documentName_ = null;
//
window.addEventListener('getArguments', function(e) {
  type = e.detail;
  if (type === 'W') {
    ObtieneArgumentosParaFirmaDesdeLaWeb();
  } else if (type === 'L') {
    ObtieneArgumentosParaFirmaDesdeArchivoLocal();
  }
});

function getArguments() {
  arg = document.getElementById("argumentos").value;
  dispatchEventClient('sendArguments', arg);
}

window.addEventListener('invokerOk', function(e) {
  type = e.detail;
  if (type === 'W') {
    MiFuncionOkWeb();
  } else if (type === 'L') {
    MiFuncionOkLocal();
  }
});

window.addEventListener('invokerCancel', function(e) {
  MiFuncionCancel();
});

function ObtieneArgumentosParaFirmaDesdeLaWeb() {
  document.getElementById("signedDocument").href = "#";
  $.get("controller/getArguments.php", {}, function(data, status) {
    documentName_ = data;
    //Obtiene argumentos
    $.post("controller/postArguments.php", {
      type: "W",
      documentName: documentName_
    }, function(data, status) {
      document.getElementById("argumentos").value = data;
      getArguments();
    });

  });
}

function ObtieneArgumentosParaFirmaDesdeArchivoLocal() {
  document.getElementById("signedDocument").href = "#";
  $.get("controller/getArguments.php", {}, function(data, status) {
    documentName_ = data;
    //Obtiene argumentos
    $.post("controller/postArguments.php", {
      type: "L",
      documentName: documentName_
    }, function(data, status) {
      document.getElementById("argumentos").value = data;
      getArguments();
    });

  });

}

function MiFuncionOkWeb() {
  alert("Documento firmado desde una URL correctamente.");
  document.getElementById("signedDocument").href = "controller/getFile.php?documentName=" + documentName_;
}

function MiFuncionOkLocal() {
  alert("Documento firmado desde la PC correctamente.");
  document.getElementById("signedDocument").href = "controller/getFile.php?documentName=" + documentName_;
}

function MiFuncionCancel() {
  alert("El proceso de firma digital fue cancelado.");
  document.getElementById("signedDocument").href = "#";
}
</script>

<p>
  <a class="btn btn-success" href="#" role="button" onclick="initInvoker('W');">Firmar documento desde la Web</a>
</p>
<p>
  <a id="signedDocument" class="btn btn-default" href="#" role="button">Ver último documento firmado</a>
</p>

<input type="hidden" id="argumentos" value="" />
<div id="addComponent"></div>