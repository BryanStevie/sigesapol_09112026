(() => {
  // Implementacion de las reglas de validacion y consitencia para el formulario de auditoria (modificacion de trama prestacion, diagnosticos, procedimientos y productos)
  const reglaValidacionMultiple = (
    fields,
    ruleNumber,
    id_tipo_atencion,
    fecha_atencion
  ) => {
    const messages = [];

    for (const [fieldName, value] of Object.entries(fields)) {
      switch (fieldName) {
        case "id_tipo_doc_paciente":
          if (value === null || value === undefined || value === "") {
            messages.push({
              columnName: fieldName,
              message: `${fieldName} es nulo o indefinido.`,
            });
          }
          if (value.length > 1) {
            messages.push({
              columnName: fieldName,
              message: `${fieldName} no es valido, su valor excede 1 digito.`,
            });
          }
          break;
        case "nro_doc_paciente":
          if (value === null || value === undefined || value === "") {
            messages.push({
              columnName: fieldName,
              message: `${fieldName} es nulo o indefinido.`,
            });
          }
          if (value.length > 15) {
            messages.push({
              columnName: fieldName,
              message: `${fieldName} no es valido, excede los 15 digitos.`,
            });
          }
          break;
        case "fecha_nac_paciente":
          if (!isRealDateDDMMYYYY(value)) {
            messages.push({
              columnName: fieldName,
              message: `${fieldName} no cuenta con el formato dd/mm/YYYY.`,
            });
          }

          const parsedDate = parseDateFlexible(value);
          if (!parsedDate) {
            messages.push({
              columnName: fieldName,
              message: `${fieldName} tiene un formato de fecha inválido.`,
            });
          } else if (isFutureDate(parsedDate)) {
            messages.push({
              columnName: fieldName,
              message: `${fieldName} es mayor a la fecha actual.`,
            });
          }

          if (value.length > 10) {
            messages.push({
              columnName: fieldName,
              message: `${fieldName} no es valido, excede los 10 caracteres.`,
            });
          }
          break;
        case "fecha_atenc":
          if (!isRealDateDDMMYYYY(value)) {
            messages.push({
              columnName: fieldName,
              message: `${fieldName} no cuenta con el formato dd/mm/YYYY.`,
            });
          }

          const parsedDateAten = parseDateFlexible(value);
          if (!parsedDateAten) {
            messages.push({
              columnName: fieldName,
              message: `${fieldName} tiene un formato de fecha inválido.`,
            });
          } else if (isFutureDate(parsedDateAten)) {
            messages.push({
              columnName: fieldName,
              message: `${fieldName} es mayor a la fecha actual.`,
            });
          }

          if (value.length > 10) {
            messages.push({
              columnName: fieldName,
              message: `${fieldName} no es valido, excede los 10 caracteres.`,
            });
          }
          break;
        case "fecha_alta":
          if (
            id_tipo_atencion != 1 &&
            (value === null || value === undefined || value === "")
          ) {
            messages.push({
              columnName: fieldName,
              message: `${fieldName} es nulo o indefinido, para ${
                id_tipo_atencion == 2 ? "EMERGENCIA" : "HOSPITALIZACION"
              } no debe ser nulo.`,
            });
          } else if (
            id_tipo_atencion == 1 &&
            (value != null && value != undefined && value != "")
          ) {
            messages.push({
              columnName: fieldName,
              message: `${fieldName} debe ser nulo, para CONSULTA EXTERNA.`,
            });
          }

          if (id_tipo_atencion != 1 && !isRealDateDDMMYYYY(value)) {
            messages.push({
              columnName: fieldName,
              message: `${fieldName} no cuenta con el formato dd/mm/YYYY.`,
            });
          }

          const parsedDateAlta = parseDateFlexible(value);
          const parsedDateAtencion = parseDateFlexible(fecha_atencion);
          if (id_tipo_atencion != 1 && !parsedDateAlta) {
            messages.push({
              columnName: fieldName,
              message: `${fieldName} tiene un formato de fecha inválido.`,
            });
          } else if (id_tipo_atencion != 1 && isFutureDate(parsedDateAlta)) {
            messages.push({
              columnName: fieldName,
              message: `${fieldName} es mayor a la fecha actual.`,
            });
          } else if (id_tipo_atencion != 1 && parsedDateAlta < parsedDateAtencion) {
            messages.push({
              columnName: fieldName,
              message: `${fieldName} es menor a la fecha de atencion.`,
            });
          }

          if (value.length > 10) {
            messages.push({
              columnName: fieldName,
              message: `${fieldName} no es valido, excede los 10 caracteres.`,
            });
          }
          break;
        case "id_sexo_paciente":
          if (value === null || value === undefined || value === "") {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} es nulo o indefinido.`,
            });
          }
          if (value.length > 1) {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} no es valido, excede 1 digito.`,
            });
          }
          break;
        case "tipo_atencion":
          if (value === null || value === undefined || value === "") {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} es nulo o indefinido.`,
            });
          }
          if (value.length > 1) {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} no es valido, excede 1 digito.`,
            });
          }
          break;
        case "codigo_ipress":
          if (value === null || value === undefined || value === "") {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} es nulo o indefinido.`,
            });
          }
          if (value.length > 8) {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} no es valido, excede los 8 digitos.`,
            });
          }
          break;
        case "id_tipo_doc_responsable":
          if (value === null || value === undefined || value === "") {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} es nulo o indefinido.`,
            });
          }
          if (value.length > 15) {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} no es valido, excede 1 digito.`,
            });
          }
          break;
        case "nro_doc_responsable":
          if (value === null || value === undefined || value === "") {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} es nulo o indefinido.`,
            });
          }
          if (value.length > 15) {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} no es valido, excede los 15 digitos.`,
            });
          }
          break;
        case "codigo_upss":
          if (value === null || value === undefined || value === "") {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} es nulo o indefinido.`,
            });
          }
          if (value.length > 15) {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} no es valido, excede los 6 digitos.`,
            });
          }
          break;
        case "codigo_diagnostico":
          if (value === null || value === undefined || value === "") {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} es nulo o indefinido.`,
            });
          }
          if (value.length > 15) {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} no es valido, excede los 6 digitos.`,
            });
          }
          break;
        case "id_tipo_diagnostico":
          if (value === null || value === undefined || value === "") {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} es nulo o indefinido.`,
            });
          }
          if (value.length > 15) {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} no es valido, excede 1 digito.`,
            });
          }
          break;
        case "codigo_procedimiento":
          if (value === null || value === undefined || value === "") {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} es nulo o indefinido.`,
            });
          }
          if (value.length > 15) {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} no es valido, excede los 8 digitos.`,
            });
          }
          break;
        case "cantidad_procedimiento":
          if (value === null || value === undefined || value === "") {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} es nulo o indefinido.`,
            });
          }
          if (!(/^\d+$/.test(value))) {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} no es valido, no es un digito.`,
            });
          }
          break;
        case "id_tipo_medicamento":
          if (value === null || value === undefined || value === "") {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} es nulo o indefinido.`,
            });
          }
          if (value.length > 15) {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} no es valido, excede 1 digito.`,
            });
          }
          break;
        case "codigo_petitorio":
          if (value === null || value === undefined || value === "") {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} es nulo o indefinido.`,
            });
          }
          break;
        case "cantidad_medicamento":
          if (value === null || value === undefined || value === "") {
            messages.push({
              columnName: fieldName,
              message: `El ${fieldName} es nulo o indefinido.`,
            });
          }
          break;
        default:
          break;
      }
    }

    return {
      ruleNumber,
      isOk: messages.length > 0 ? false : true,
      messages,
    };
  };

  function isValidDDMMYYYY(dateStr) {
    const regex = /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/;
    return regex.test(dateStr);
  }

  function isRealDateDDMMYYYY(dateStr) {
    if (!isValidDDMMYYYY(dateStr)) return false;

    const [day, month, year] = dateStr.split("/").map(Number);
    const date = new Date(year, month - 1, day);

    return (
      date.getFullYear() === year &&
      date.getMonth() === month - 1 &&
      date.getDate() === day
    );
  }

  function parseDateFlexible(dateStr) {
    // dd/mm/YYYY
    if (/^\d{2}\/\d{2}\/\d{4}$/.test(dateStr)) {
      const [day, month, year] = dateStr.split("/").map(Number);
      const date = new Date(year, month - 1, day);
      return isNaN(date.getTime()) ? null : date;
    }

    // yyyy-mm-dd (ISO)
    if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
      const date = new Date(dateStr);
      return isNaN(date.getTime()) ? null : date;
    }

    return null; // formato desconocido
  }

  function isFutureDate(date) {
    const today = new Date();
    today.setHours(0, 0, 0, 0); // eliminar horas
    date.setHours(0, 0, 0, 0);

    return date > today;
  }

  /* const errors = [
    {
      ruleNumber: 1,
      isOk: true,
      messages: [
        {
          columnName: columnName,
          message: `El ${columnName} es nulo o indefinido.`,
        },
      ],
    },
  ]; */

  const id_tipo_doc_paciente = "8";
  const nro_doc_paciente = "43568089";
  /* const fecha_nac_paciente = "27/03/2026"; */
  const fecha_nac_paciente = /* "2026-01-18" */"18/01/2026";
  const fecha_atenc = "17/01/2026";
  const fecha_alta = "";
  const id_sexo_paciente = "1";
  const tipo_atencion = "1";
  const codigo_ipress = "43568089123141xxas";
  const id_tipo_doc_responsable = "43568089123141xxas";
  const nro_doc_responsable = "43568089123141xxas";
  const codigo_upss = "43568089123141xxas";
  const codigo_diagnostico = "43568089123141xxas";
  const id_tipo_diagnostico = "43568089123141xxas";
  const codigo_procedimiento = "43568089123141xxas";
  const cantidad_procedimiento = "6";
  const id_tipo_medicamento = "43568089123141xxas";
  const codigo_petitorio = "43568089123141xxas";
  const cantidad_medicamento = "43568089123141xxas";

  const result = reglaValidacionMultiple(
    {
      id_tipo_doc_paciente,
      nro_doc_paciente,
      fecha_nac_paciente,
      fecha_atenc,
      fecha_alta,
      id_sexo_paciente,
      tipo_atencion,
      cantidad_procedimiento
      /* fecha_atenc,
      fecha_alta,
      id_sexo_paciente,
      tipo_atencion,
      codigo_ipress,
      id_tipo_doc_responsable,
      nro_doc_responsable,
      codigo_upss,
      codigo_diagnostico,
      id_tipo_diagnostico,
      codigo_procedimiento,
      cantidad_procedimiento,
      id_tipo_medicamento,
      codigo_petitorio,
      cantidad_medicamento, */
    },
    1,
    tipo_atencion,
    fecha_atenc
  );

  console.log(result);
})();
