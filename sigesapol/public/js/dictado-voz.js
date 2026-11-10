(function () {
  var SR = window.SpeechRecognition || window.webkitSpeechRecognition;
  var buttons = document.querySelectorAll('.btn-dictado');

  if (!SR) {
    for (var i = 0; i < buttons.length; i++) {
      buttons[i].disabled = true;
      buttons[i].title = 'Dictado por voz no soportado en este navegador';
    }
    return;
  }

  var activeRecognition = null;
  var activeButton = null;

  function warn(msg) {
    if (window.toastr && typeof toastr.warning === 'function') {
      toastr.warning(msg);
    } else {
      console.warn(msg);
    }
  }

  function stopActive() {
    if (activeRecognition) {
      try { activeRecognition.stop(); } catch (e) {}
    }
  }

  function setListening(btn, isListening) {
    btn.classList.toggle('btn-danger', isListening);
    btn.classList.toggle('btn-default', !isListening);
  }

  function iniciarDictado(btn) {
    var targetId = btn.getAttribute('data-target-id');
    var textarea = document.getElementById(targetId);
    if (!textarea) { return; }

    if (activeButton === btn) {
      stopActive();
      return;
    }

    stopActive();

    var recognition = new SR();
    recognition.lang = 'es-PE';
    recognition.interimResults = false;

    recognition.onresult = function (event) {
      var transcript = event.results[0][0].transcript;
      if (textarea.value.trim().length === 0) {
        textarea.value = transcript;
      } else {
        textarea.value += '\n' + transcript;
      }
    };

    recognition.onerror = function (event) {
      warn('Dictado por voz: ' + (event.error || 'error desconocido'));
    };

    recognition.onend = function () {
      setListening(btn, false);
      if (activeRecognition === recognition) {
        activeRecognition = null;
        activeButton = null;
      }
    };

    activeRecognition = recognition;
    activeButton = btn;
    setListening(btn, true);

    try {
      recognition.start();
    } catch (e) {
      warn('No se pudo iniciar el dictado por voz.');
      setListening(btn, false);
      activeRecognition = null;
      activeButton = null;
    }
  }

  for (var j = 0; j < buttons.length; j++) {
    (function (btn) {
      btn.addEventListener('click', function () {
        iniciarDictado(btn);
      });
    })(buttons[j]);
  }
})();
