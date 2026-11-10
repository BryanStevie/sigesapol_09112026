(() => {
  $(document).ready(function () {
    const tipoAtencionSelect = document.querySelector("#tipo_atencion");
    const ipressSelectJQuery = $("#ipress");
    const ipressSelect = document.querySelector("#ipress");
    const btnBuscarDataUser = document.querySelector("#btnBuscarDataUser");
    const dniCargadoCurrent = $("#dni").val();
    const btnSubmitSolicitud = document.querySelector("#btnReg");

    const atencionOptions = [...tipoAtencionSelect.children];

    tipoAtencionSelect.addEventListener("mousedown", (e) => {
      if (ipressSelectJQuery.val() == "") {
        e.preventDefault();
        bootbox.alert("Debe seleccionar la IPRESS de origen en primer lugar.");
      }
    });

    ipressSelect.addEventListener("change", function (e) {
      tipoAtencionSelect.value = "";
      validarNivelIpressParaTipoAtencion();
    });

    const validarNivelIpressParaTipoAtencion = () => {
      const nivelIpressCurrent =
        ipressSelect.options[ipressSelect.selectedIndex].getAttribute("nivel");
      switch (nivelIpressCurrent) {
        case "3":
          tipoAtencionSelect.innerHTML = "";
          tipoAtencionSelect.append(atencionOptions[0]);
          tipoAtencionSelect.append(atencionOptions[1]);
          tipoAtencionSelect.append(atencionOptions[2]);
          tipoAtencionSelect.append(atencionOptions[3]);
          break;
        case "2":
          tipoAtencionSelect.innerHTML = "";
          tipoAtencionSelect.append(atencionOptions[0]);
          tipoAtencionSelect.append(atencionOptions[1]);
          break;
        case "1":
          tipoAtencionSelect.innerHTML = "";
          tipoAtencionSelect.append(atencionOptions[0]);
          tipoAtencionSelect.append(atencionOptions[1]);
          break;
        default:
          break;
      }
    };

    const getDirrehumData = async (dni) => {
      const res = await fetch(
        `${
          window.location.href.split("/")[0]
        }/mesa/buscar_by_dni_dirrehum/${dni}`,
        {
          body: {
            dni,
          },
          method: "POST",
          headers: {
            "X-CSRF-Token": $("#_token").val(),
            "Content-Type": "application/json",
          },
        }
      ); /* .then((response) => {
        if (!response.ok) {
            throw new Error("Ocurrio un error al consultar la WS de Reniec.");
        }
        return response.json();
      }); */
      return res.json();
    };

    btnBuscarDataUser.addEventListener("click", (e) => {
      console.log("buscando data...");
      Swal.fire({
        title:
          "<div style='margin: 10px 0px'><span style='color: #ffffff; font-size: 20px'>Consultado datos policiales</span></div>",
        html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Espere un momento por favor ...</span></div>",
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        onBeforeOpen: () => {
          Swal.showLoading();
        },
      });
      getDirrehumData(dniCargadoCurrent)
        .then((data) => {
          if (data.BuscarTitularFamiliarResult.TitularFamiliar.CARNE.includes("IP incorrecto")) {
            throw new Error("No hay conexion, IP incorrecta.");
          }
          Swal.fire({
            icon: "success",
            html: "<div style='margin: 10px 0px'><span style='color: #000000; font-size: 18px'>Se cargo la informacion correctamente.</span></div>",
            confirmButtonText: "ENTENDIDO",
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonText: "ENTENDIDO",
          });
          $("#cip").val(data.BuscarTitularFamiliarResult.TitularFamiliar.CARNE);
          $("#grado").val(
            data.BuscarTitularFamiliarResult.TitularFamiliar.GRADO
          );
          console.log("se termino de encontrar");
        })
        .catch((err) => {
          console.log("qweqeqdasd213e");
          Swal.fire({
            icon: "error",
            title: "ERROR ",
            text: "Servicio no disponible. intentelo en un minutos.",
            confirmButtonText: "ENTENDIDO",
          });
          console.error(err);
        })
        .finally(() => {
          console.log("se termino de encontrar");
          Swal.hideLoading();
        });
    });
    validarNivelIpressParaTipoAtencion();

    const validadorCamporObligatorios = () => {
      let mes = "";

      const prioridad = $("#prio_requerimiento").val();
      const ipress = $("#ipress").val();
      const tipoAtencion = $("#tipo_atencion").val();
      const servicio = $("#servicio").val();
      const dni = $("#dni").val();
      const nombres = $("#nombres").val();
      const paterno = $("#paterno").val();
      const materno = $("#materno").val();
      const email = $("#email").val();
      const especialidad = $("#especialidad").val();
      const descripcion = $("#desc_requerimiento").val();

      if (prioridad == "") mes += "Debe seleccionar el campo PRIORIDAD <br>";
      if (ipress == "") mes += "Debe seleccionar el campo IPRESS <br>";
      if (tipoAtencion == "")
        mes += "Debe seleccionar el TIPO DE ATENCION <br>";
      if (servicio == "") mes += "Debe seleccionar el campo SERVICIO <br>";
      if (dni == "") mes += "Debe ingresar el campo DNI <br>";
      if (nombres == "") mes += "Debe ingresar los nombres <br>";
      if (paterno == "") mes += "Debe ingresar el APELLIDO PATERNO <br>";
      if (materno == "") mes += "Debe ingresar el APELLIDO MATERNO <br>";
      if (email == "") mes += "Debe ingresar el campo EMAIL <br>";
      if (especialidad == "") mes += "Debe ingresar el campo ESPECIALIDAD <br>";
      if (descripcion == "") mes += "Debe ingresar el campo DESCRIPCION <br>";

      if (mes != "") {
        bootbox.alert(mes);
      } else {
        guardarRequerimiento();
      }
    };

    const guardarRequerimiento = () => {
      const formRequerimiento = $("#frmSolicitudReq");
      formRequerimiento.submit();
    };

    btnSubmitSolicitud.addEventListener("click", () => {
      const formSolicitudReq = document.querySelector("#frmSolicitudReq");
      validadorCamporObligatorios();
    });
  });
})();
