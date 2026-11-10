<script>
    
    let tempListaTickets = setTimeout( () => {
    let estado_llamado = $('#estado_llamado').val();
      if (estado_llamado == 'true') {
        $.ajax({
            type: "GET",
            url: "/listado_tickets",
            dataType: "html",
            success: function (response) {
            $(document).ready(function () {
                $('#seccion_listado').html(response);
                let estado_llamado = $('#estado_llamado').val();
                if (estado_llamado == 'true') {
                $('#btnLlamar').attr('disabled', true);
                $('#btnCancelar').attr('disabled', true);
                $('#btnAtendido').attr('disabled', true);
                fnHover();
                tempListaTickets = setTimeout(tempListaTickets, 2500);
                }
            });    
            }
        });
      }else{
        clearTimeout(tempListaTickets);
      }
    }, 2500);
    /*
    let tempListaTickets2 = setTimeout( () => {
    let estado_llamado2 = $('#estado_llamado2').val();
      if (estado_llamado2 == 'true') {
        let fechaDesde = $('#fechaDesde').val();
        let fechaHasta = $('#fechaHasta').val();
        let estado = $('#estado').val();
        $.ajax({
          type: "GET",
          url: "/busca_ticket_totem2/" + fechaDesde + '/' + fechaHasta + '/' + estado,
          dataType: "html",
          success: function (response) {
            $(document).ready(function () {
                $('#seccion_listado').html(response);
                let estado_llamado2 = $('#estado_llamado2').val();
                if (estado_llamado2 == 'true') {
                $('#btnLlamar').attr('disabled', true);
                $('#btnCancelar').attr('disabled', true);
                $('#btnAtendido').attr('disabled', true);
                console.log('tempListaTickets2');
                fnHover();
                tempListaTickets2 = setTimeout(tempListaTickets2, 2500);
                }
            });    
          }
        });
      }else{
        clearTimeout(tempListaTickets2);
      }
    }, 2500);
    */
    const compararFecha = (fr) => {
      let fecha = new Date();
      let diaActual = fecha.getDate();
      let mesActual = fecha.getMonth() + 1;
      let yearActual = fecha.getFullYear();
      let fechaActual = (diaActual <= 9 ? '0' + diaActual : diaActual) + '/' + (mesActual <= 9 ? '0' + mesActual : mesActual) + '/' + yearActual;
      if (fechaActual !== fr) {
        return false;
      } else {
        return true;
      }
    }

    $('.tbReg').click(function (e) { 
      e.preventDefault();
      let estado_llamado = $('#estado_llamado').val();
      let estado_llamado2 = $('#estado_llamado2').val();
      let fecha = $(this).attr('data-fecha');
      let llamadoFechaActual = compararFecha(fecha);
      let estado = $(this).attr('data-idEstadoTv');
      let user = $('#user').val();
      if (llamadoFechaActual == true) {
        if (estado == '4' || estado == '1') {
          Swal.fire(
          'No puedes llamar!',
          'Esta seleccionado por otra ventanilla o esta siendo llamado!'
          );
        } else {
          if (estado_llamado2 == 'false') {
            let estado_llamado = $('#estado_llamado').val();
            let ventanilla = $('#ventanilla').val();
            if (estado_llamado == 'true') {
              let id_ticket = $(this).attr('data-id');
              $.ajax({
                type: "GET",
                url: "/obtenerPaciente/"+ id_ticket+"/"+ventanilla,
                dataType: "json",
                success: function (response) {
                  $('#turno').val(response['turno']);
                  if (response['paciente'] !== '0') {
                    $('#nombre_paciente').val(response['paciente']);
                    $('#id_ticket').val(id_ticket);
                  } 
                  if (response['paciente'] == '0') {
                    $('#nombre_paciente').val('');
                    $('#id_ticket').val(id_ticket);
                  }
                  $('#btnLlamar').attr('disabled', false);
                  $('#btnCancelar').attr('disabled', false);
                  $('#btnAtendido').attr('disabled', false);
                  $('#estado_llamado').val('false');
                  $('#flagEstado').val('0');
                  //$('tbody').css('background-color', '#b1b1b1');
                  //$(`.tbReg[data-id=${id_ticket}]`).css('background-color', '#5cb85c'); 
                  $(`.tbReg[data-id=${id_ticket}]`).css('background', 'rgb(211,249,211)');
                }
              });
            }
          }
          if (estado_llamado == 'false') {
            let estado_llamado2 = $('#estado_llamado2').val();
            let ventanilla = $('#ventanilla').val();
            if (estado_llamado2 == 'true') {
              let id_ticket = $(this).attr('data-id');
              $.ajax({
                type: "GET",
                url: "/obtenerPaciente2/"+ id_ticket+"/"+ventanilla,
                dataType: "json",
                success: function (response) {
                  $('#turno').val(response['turno']);
                  if (response['paciente'] !== '0') {
                    $('#nombre_paciente').val(response['paciente']);
                    $('#id_ticket').val(id_ticket);
                  } 
                  if (response['paciente'] == '0') {
                    $('#nombre_paciente').val('');
                    $('#id_ticket').val(id_ticket);
                  }
                  $('#btnLlamar').attr('disabled', false);
                  $('#btnCancelar').attr('disabled', false);
                  if (estado == '3') {
                    $('#btnAtendido').attr('disabled', true);  
                  }else{
                    $('#btnAtendido').attr('disabled', false);
                  }
                  $('#estado_llamado2').val('false');
                  $('#flagEstado').val('1');
                  //$('tbody').css('background-color', '#b1b1b1');
                  //$(`.tbReg[data-id=${id_ticket}]`).css('background-color', '#5cb85c'); 
                  $(`.tbReg[data-id=${id_ticket}]`).css('background', 'rgb(211,249,211)');
                }
              });
            }
          }
        }
      } else {
      Swal.fire(
      'No puedes llamar!',
      'Solo puedes llamar en la fecha de hoy!'
      ).then((ok)=>{
          location.reload();
        });
      }  
    });
  
    let fnHover = () => {
      $('.tbReg').hover(function () {
            $(this).addClass('resaltar').fadeIn();
          }, function () {
            $(this).removeClass('resaltar').fadeIn();
          }
        );
    }
    $('#example').DataTable({
      "pageLength": 100
    });
  </script>
  <style>
    .ancho {
      width: 100%;
    }
  
    .flex-row{
      display: flex;
      justify-content: space-evenly;
      align-items: center;
    }
    
    .flex-col{
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      align-items: center;
      gap: 2rem;
      padding: 0.5rem;
    }
  
    .resaltar{
      background: rgb(211,249,211);
      background: linear-gradient(90deg, rgba(211,249,211,1) 0%, rgba(92,184,92,0.8407738095238095) 52%, rgba(211,249,211,1) 100%); 
    }
  
    .background-table{
      background: rgb(217,217,217);
      background: radial-gradient(circle, rgba(217,217,217,1) 0%, rgba(184,184,193,0.8995327102803738) 50%, rgba(217,217,217,1) 100%);
    }

    /*LOADER 3 PUNTOS*/
  .loader-animation{
      display: flex;
      justify-content: center;
      align-self: center;
      margin: 0 auto;
      height: 2rem;
    }
    .loader-animation > div{
      width: 1rem;
      height: 1rem;
      margin: 1.5rem .8rem;
      border-radius: 50%;
      background: rgb(255,156,90);
      background: linear-gradient(90deg, rgba(255,156,90,1) 0%, rgba(255,239,0,1) 52%, rgba(255,156,90,1) 100%);
      animation: loader-animation .6s infinite alternate;
    }
    @keyframes loader-animation{
      to{
        opacity: .1;
        transform: translate3d(0,-1rem,0);
      }
    }
    .loader-animation > div:nth-child(2){
      animation-delay: .2s;
    }
    .loader-animation > div:nth-child(2){
      animation-delay: .2s;
    }
    /*=================FIN-LOADER-3-PUNTOS==================*/
  /* Estilos para el botón del paginador */
  .paginate_button {
      padding: 0 !important;
    }
  </style>

<div>
    <div class="col-sm-12" style="border: 1px solid gray; padding-top: 10px; margin-top: 10px">
    <!-- <legend>Historias Clínicas Enviadas a los Servicios</legend> -->
    <div class="table-responsive">
        @if (empty($listado))
        <p style="text-align: center;">No hay registros</p>
        @else
        <table id="example" class="display table" cellspacing="0" width="100%">
        <thead>
            <tr>
              <th>#</th>
              <th style="text-align: center;">Estado</th>
              <th>Turno</th>
              <th>Destino</th>
              <th>Paciente</th>
              <th>Hora Ticket</th>
              <th>Fecha Ticket</th>
              <th>Ventanilla</th>
              <th>Cantidad</th>
              @if ($listado[0]->servicio_cita == 114)
                <th>Fecha y Hora Cita</th>
                <th>Consultorio</th>
              @endif
              <th>Anular</th>
            </tr>
        </thead>
        <tbody>
            <?php $n = 1; ?>
            @foreach($listado as $r)
            <tr class="tbReg" data-id="{{$r->id}}" data-fecha="{{$r->fecha_ticket}}" data-idEstadoTv="{{$r->id_estado_tv}}" data-llamado_us="{{$r->llamado_us}}" data-seleccionado_us="{{$r->seleccionado_us}}" @if($r->id_estado_tv == 1) class="warning" @endif >
                <td>{{$n}}</td>
                @if ($r->id_estado_tv !== 4)
                <td style="text-align: center;">{{$r->estado}}</td>
                @else
                <td style=" padding: 0;">
                <div class="loader-animation">
                  <div></div>
                  <div></div>
                  <div></div>
                </div>
                </td>
                @endif
                <td>{{$r->letra}}-{{$r->numero}}</td>
                <td>{{$r->totem_opcion}}</td>
                <td>
                    @if ($r->paciente !== '')
                    {{$r->paciente}}  
                    @else
                    ----------------------
                    @endif
                </td>
                <td>{{$r->hora_ticket}}</td>
                <td>{{$r->fecha_ticket}}</td>
                <td>
                  @if ($r->nombre_ventanilla !== null)
                  {{$r->nombre_ventanilla}}  
                  @endif
                </td>
                <td>{{$r->cantidad_llamada}}</td>
                @if ($r->servicio_cita == 114)
                  <td>{{$r->fecha_hora_cita}}</td>
                  <td>{{$r->nom_sc_cita}}</td>
                @endif
                <td><a href="javascript:void(0)" onClick="anularTicket({{$r->id}})" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></a></td>
            </tr>
            <?php $n++; ?>
            @endforeach
        </tbody>
        </table>
        @endif
    </div>
    </div>
</div>