function esNumero(evt,id)
{
        try{
        var charCode = (evt.which) ? evt.which : evt.keyCode;
  
        if(charCode==46){
            var txt=document.getElementById(id).value;
            if(!(txt.indexOf(".") > -1)){
        
                return true;
            }
        }
        if (charCode > 31 && (charCode < 48 || charCode > 57) )
            return false;

        return true;
        }catch(w){
                alert(w);
        }
}

function esEntero(evt){			
	// code is the decimal ASCII representation of the pressed key.
	var code = (evt.which) ? evt.which : evt.keyCode;
	
	if(code==8) { // backspace.
	  return true;
	} else if(code>=48 && code<=57) { // is a number.
	  return true;
	} else{ // other keys.
	  return false;
	}
}

var Validar = {
	esVacio: function (valor) {
		if (valor == ''){
			return true;
		}
		return false;
	},
	decimalEnRango: function(valor, minimo, maximo) {
		valor = parseFloat(valor);
		minimo = parseFloat(minimo); 
		maximo = parseFloat(maximo);
		if (valor >= minimo && valor <= maximo) {
			return true;
		} 
		return false;
	},
	enteroEnRango: function(valor, minimo, maximo) {
		valor = parseInt(valor, 10);
		minimo = parseInt(minimo, 10); 
		maximo = parseInt(maximo, 10);
		if (valor >= minimo && valor <= maximo) {
			return true;
		} 
		return false;
	}
};

function calcularEdad(fechax) {
	// Si la fecha es correcta, calculamos la edad
	var fecha=fechax.substring(0,10);
	if (typeof fecha != "string" && fecha && esNumero(fecha.getTime())) {
		fecha = formatDate(fecha, "yyyy-MM-dd");
	}

	var values = fecha.split("-");
	var dia = values[2];
	var mes = values[1];
	var ano = values[0];

	// cogemos los valores actuales
	var fecha_hoy = new Date();
	var ahora_ano = fecha_hoy.getYear();
	var ahora_mes = fecha_hoy.getMonth() + 1;
	var ahora_dia = fecha_hoy.getDate();

	// realizamos el calculo
	var edad = (ahora_ano + 1900) - ano;
	if (ahora_mes < mes) {
		edad--;
	}
	if ((mes == ahora_mes) && (ahora_dia < dia)) {
		edad--;
	}
	if (edad > 1900) {
		edad -= 1900;
	}

	// calculamos los meses
	var meses = 0;

	if (ahora_mes > mes && dia > ahora_dia)
		meses = ahora_mes - mes - 1;
	else if (ahora_mes > mes)
		meses = ahora_mes - mes
	if (ahora_mes < mes && dia < ahora_dia)
		meses = 12 - (mes - ahora_mes);
	else if (ahora_mes < mes)
		meses = 12 - (mes - ahora_mes + 1);
	if (ahora_mes == mes && dia > ahora_dia)
		meses = 11;

	// calculamos los dias
	var dias = 0;
	if (ahora_dia > dia)
		dias = ahora_dia - dia;
	if (ahora_dia < dia) {
		ultimoDiaMes = new Date(ahora_ano, ahora_mes - 1, 0);
		dias = ultimoDiaMes.getDate() - (dia - ahora_dia);
	}

	return edad + "|" + meses + "|" + dias;
}

function vfecha(fechax) { //2021-11-03 A 03/11/2021
	var xfecha=fechax.substring(0,10);
	var porciones = xfecha.split('-');
	var fecha2 = porciones[2]+'/'+porciones[1]+'/'+porciones[0];
	return fecha2;
}

function dfecha(fechax) { //2021-11-03 A 03-11-2021
	var xfecha=fechax.substring(0,10);
	var porciones = xfecha.split('-');
	var fecha2 = porciones[2]+'-'+porciones[1]+'-'+porciones[0];
	return fecha2;
}

function gfecha(fechax) { //03/11/2021 A 2021-11-03
	var xfecha=fechax.substring(0,10);
	var porciones = xfecha.split('/');
	var fecha2 = porciones[2]+'-'+porciones[1]+'-'+porciones[0];
	return fecha2;
}

function completar_ceros(number, width) {
	var lengthx=0;	
    var numberOutput = Math.abs(number); /* Valor absoluto del número */
	var zero = "0"; /* String de cero */  
	
	if(number!="" && number!=null){
		lengthx = number.toString().length; /* Largo del número */		    
		if (width <= lengthx) {
			if (number < 0) {
				 return ("-" + numberOutput.toString()); 
			} else {
				 return numberOutput.toString(); 
			}
		} else {
			if (number < 0) {
				return ("-" + (zero.repeat(width - lengthx)) + numberOutput.toString()); 
			} else {
				return ((zero.repeat(width - lengthx)) + numberOutput.toString()); 
			}
		}
	}else{
		return "0";
	}	
}


function noEspaciosInicioSoloLetras(event,id){
  var regex = /^[a-zA-ZÀ-ÿŸ]+(\s*[a-zA-ZÀ-ÿŸ]*)*$/;
  var inputValue = $('#'+id).val();
  var charTyped = String.fromCharCode(!event.charCode ? event.which : event.charCode);
  if (!(inputValue === "" && charTyped === " ")) {
      inputValue += charTyped;
  }

  if (!regex.test(inputValue)) {
      event.preventDefault();
      return false;
  }
}
function noEspaciosInicioSoloLetrasOtros(event,id){
  var regex = /^[a-zA-ZÀ-ÿŸ.-]+(\s*[a-zA-ZÀ-ÿŸ.-]*)*$/;
  var inputValue = $('#'+id).val();
  var charTyped = String.fromCharCode(!event.charCode ? event.which : event.charCode);
  if (!(inputValue === "" && charTyped === " ")) {
      inputValue += charTyped;
  }

  if (!regex.test(inputValue)) {
      event.preventDefault();
      return false;
  }
}

function noEspaciosInicioSoloNumero(event,id){
  var regex = /^[0-9]+(\s*[0-9]*)*$/;
  var inputValue = $('#'+id).val();
  var charTyped = String.fromCharCode(!event.charCode ? event.which : event.charCode);
  if (!(inputValue === "" && charTyped === " ")) {
      inputValue += charTyped;
  }

  if (!regex.test(inputValue)) {
      event.preventDefault();
      return false;
  }
}

function noEspaciosInicioSoloNumeroPuntos(event,id){
  var regex = /^[0-9.]+(\s*[0-9.]*)*$/;
  var inputValue = $('#'+id).val();
  var charTyped = String.fromCharCode(!event.charCode ? event.which : event.charCode);
  if (!(inputValue === "" && charTyped === " ")) {
      inputValue += charTyped;
  }

  if (!regex.test(inputValue)) {
      event.preventDefault();
      return false;
  }
}

function noEspaciosInicioLetrasNumerosOtros(event,id){
  var regex = /^[a-zA-ZÀ-ÿŸ0-9°"'.()/%¿?,-]+(\s*[a-zA-ZÀ-ÿŸ0-9°"'.()/%¿?,-]*)*$/;
  var inputValue = $('#'+id).val();
  var charTyped = String.fromCharCode(!event.charCode ? event.which : event.charCode);
  if (!(inputValue === "" && charTyped === " ")) {
      inputValue += charTyped;
  }

  if (!regex.test(inputValue)) {
      event.preventDefault();
      return false;
  }
}
function noEspaciosInicioLetrasNumeros(event,id){
  var regex = /^[a-zA-ZÀ-ÿŸ0-9]+(\s*[a-zA-ZÀ-ÿŸ0-9]*)*$/;
  var inputValue = $('#'+id).val();
  var charTyped = String.fromCharCode(!event.charCode ? event.which : event.charCode);
  if (!(inputValue === "" && charTyped === " ")) {
      inputValue += charTyped;
  }

  if (!regex.test(inputValue)) {
      event.preventDefault();
      return false;
  }
}

function soloIP(event,id){
  var regexIPv4 = /^(25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})(\.(25[0-5]|2[0-4][0-9]|[0-1]?[0-9]{1,2})){3}$/;
  var regexIPv6 = /^([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}$|^([0-9a-fA-F]{1,4}:){1,7}:?$|^([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}$|^([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}$|^([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}$|^([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}$|^([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}$|^[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})?$|^:((:[0-9a-fA-F]{1,4}){1,7}|:)$/;

  var inputValue = $('#' + id).val();
  var charTyped = String.fromCharCode(!event.charCode ? event.which : event.charCode);
  if (!(inputValue === "" && charTyped === " ")) {
      inputValue += charTyped;
  }
  // Validar la dirección IP
  if (!regexIPv4.test(inputValue) && !regexIPv6.test(inputValue)) {
    $('#' + id).addClass('borde-alerta');
  } else {
    $('#' + id).removeClass('borde-alerta');
  }
}

function noEspaciosInicioSoloPrecios(event,id,ndecimal){
  var regex = new RegExp("^\\d+(\\.\\d{0," + ndecimal + "})?$");
  var inputValue = $('#' + id).val();
  var charTyped = String.fromCharCode(!event.charCode ? event.which : event.charCode);

  if (!(inputValue === "" && charTyped === " ")) {
      inputValue += charTyped;
  }

  if (!regex.test(inputValue)) {
      event.preventDefault();
      return false;
  }
} 

function noEspaciosInicioSoloEmail(id){
  var emailField = document.getElementById(id);

    emailField.addEventListener('paste', function () {
        setTimeout(function () {
            var validEmail = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;

            if (validEmail.test(emailField.value)) {
                emailField.style.borderColor = "";
            } else {
                emailField.style.borderColor = "red";
            }
        }, 0);
    });

    emailField.addEventListener('input', function () {
        var validEmail = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;

        if (validEmail.test(emailField.value)) {
            emailField.style.borderColor = "";
        } else {
            emailField.style.borderColor = "red";
        }
    });
}
