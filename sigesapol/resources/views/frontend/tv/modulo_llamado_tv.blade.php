<?php
    use Carbon\Carbon;
?>
@extends('frontend.layouts.master2')
@section('renderjs')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $(document).ready(function (e) {
    
    let ventanilla = $('#ventanilla').val();
    if (ventanilla == '0') {
      $('#estado_llamado').val('false');
      $.ajax({
        type: "GET",
        url: "modal_ventanillas",
        dataType: "HTML",
        success: function (response) {
          $('#modal_ventanillas').html(response);
        }
      });
    }

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
    loading();
  });

  let loading = () => {
    setTimeout( () => {
      $('.loading').hide();
    }, 2500);
  }
  
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
    }, 2500);/*
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
  $('#btnBuscar').click(function (e) { 
    e.preventDefault();
    let fechaDesde = $('#fechaDesde').val();
    let fechaHasta = $('#fechaHasta').val();
    let estado = $('#estado').val();
    let id_ticket = $('#id_ticket').val();
    if (id_ticket !== '') {
      Swal.fire(
      'No puedes hacer una busqueda!',
      "Si seleccionas o haces un llamado a un paciente no puedes realizar una busqueda!"
      ).then((ok)=>{
          location.reload();
      });
    } else {
      $.ajax({
        type: "GET",
        url: "/busca_ticket_totem2/" + fechaDesde + '/' + fechaHasta + '/' + estado,
        dataType: "html",
        success: function (response) {
          $(document).ready(function () {
            $('#estado_llamado').val('false');
            $('#estado_llamado2').val('true');
            $('#seccion_listado').html(response);
            let estado_llamado2 = $('#estado_llamado2').val();
            if (estado_llamado2 == 'true') {
            $('#btnLlamar').attr('disabled', true);
            $('#btnCancelar').attr('disabled', true);
            fnHover();
            limpiarInput();
            }
          });  
        }
      });
    }
  });
  $('#btnLlamar').click(function (e) { 
    e.preventDefault();
    $('.loading').show();
    let estado_llamado = $('#estado_llamado').val();
    let estado_llamado2 = $('#estado_llamado2').val();
    let flagEstado = $('#flagEstado').val();
    if (estado_llamado2 == 'false' && flagEstado == '0') {
    let id_ticket = $('#id_ticket').val();
    $.ajax({
      url: "/tv_llamar_ticket/" + id_ticket,
      async: false,
      type: "GET",
      success: function(result) {
        if (result > 0) {
          $.ajax({
              type: "GET",
              url: "/listado_tickets",
              dataType: "html",
              success: function (response) {
              $(document).ready(function () {
                  $('#seccion_listado').html(response);
                  $(`.tbReg[data-id=${id_ticket}]`).css('background', 'rgb(211,249,211)',);
                  $(`.tbReg[data-id=${id_ticket}] td`).css('font-weight', 'bold');
                  loading();
              });    
              }
          });
        } else {
          bootbox.alert("Actualmente se está llamando a otro paciente de este consultorio."); //... de esta locación
        }
      },
      error: function(xhr, status) {
        console.log("error");
      },
      complete : function(xhr, status) {
        return false;
      }
    });
  }
  if (estado_llamado == 'false' && flagEstado == '1') {
    let estado_busqueda = $('#estado').val();
    if (estado_busqueda == '3') {
        Swal.fire({
        title: '¿Estás seguro de cambiar el estado?',
        /*text: "Si realizas el cambio se rediri!",*/
        /*icon: 'warning',*/
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '¡Si, cambiar!',
        cancelButtonText: '¡Cancelar!'
        }).then((result) => {
          if (result.isConfirmed) {
            let id_ticket = $('#id_ticket').val();
            $.ajax({
              url: "/tv_llamar_ticket/" + id_ticket,
              async: false,
              type: "GET",
              success: function(result) {
                if (result > 0) {
                  let fechaDesde = $('#fechaDesde').val();
                  let fechaHasta = $('#fechaHasta').val();
                  let estado = $('#estado').val();
                  $.ajax({
                  type: "GET",
                  url: "/busca_ticket_totem2/" + fechaDesde + '/' + fechaHasta + '/' + estado,
                  dataType: "html",
                  success: function (response) {
                  location.reload();
                  /*
                  $(document).ready(function () {
                      $('#seccion_listado').html(response);
                      $(`.tbReg[data-id=${id_ticket}]`).css('background', 'rgb(211,249,211)',);
                      $(`.tbReg[data-id=${id_ticket}] td`).css('font-weight', 'bold');
                  });*/    
                  }
                  });
                } else {
                  bootbox.alert("Actualmente se está llamando a otro paciente de este consultorio."); //... de esta locación
                }
              },
              error: function(xhr, status) {
                console.log("error");
              },
              complete : function(xhr, status) {
                return false;
              }
            });    
          } else {
              $('.loading').hide();
          }
        });
    } else {
      let id_ticket = $('#id_ticket').val();
      $.ajax({
        url: "/tv_llamar_ticket/" + id_ticket,
        async: false,
        type: "GET",
        success: function(result) {
          if (result > 0) {
            let fechaDesde = $('#fechaDesde').val();
            let fechaHasta = $('#fechaHasta').val();
            let estado = $('#estado').val();
            $.ajax({
            type: "GET",
            url: "/busca_ticket_totem2/" + fechaDesde + '/' + fechaHasta + '/' + estado,
            dataType: "html",
            success: function (response) {
            location.reload();
            /*
            $(document).ready(function () {
                $('#seccion_listado').html(response);
                $(`.tbReg[data-id=${id_ticket}]`).css('background', 'rgb(211,249,211)',);
                $(`.tbReg[data-id=${id_ticket}] td`).css('font-weight', 'bold');
            });*/    
            }
            });
          } else {
            bootbox.alert("Actualmente se está llamando a otro paciente de este consultorio."); //... de esta locación
          }
        },
        error: function(xhr, status) {
          console.log("error");
        },
        complete : function(xhr, status) {
          return false;
        }
      });
    }
  }
  });
  
  $('#btnCancelar').click(function (e) { 
    e.preventDefault();
    $('.loading').show();
    let estado_llamado = $('#estado_llamado').val();
    let estado_llamado2 = $('#estado_llamado2').val();
    let flagEstado = $('#flagEstado').val();
    if (estado_llamado2 == 'false' && flagEstado == '0') {
      let id_ticket = $('#id_ticket').val();
      $.ajax({
        url: "/posponer_llamado_ticket/" + id_ticket,
        async: false,
        type: "GET",
        success: function(result) {
          if (result=="2") {
            $(document).ready(function () {
              $.ajax({
                type: "GET",
                url: "/listado_tickets",
                dataType: "html",
                success: function (response) {
                $(document).ready(function () {
                    $('#estado_llamado').val('true');
                    $('#seccion_listado').html(response);
                    loading();
                    limpiarInput();
                });    
                }
              });
            });
          } else {
            console.log = "error";
          }
        },
        complete : function(xhr, status) {
          return false;
        }
      });
    }
    if (estado_llamado == 'false' && flagEstado == '1') {
      let estado_busqueda = $('#estado').val();
      if (estado_busqueda == '3') {
        Swal.fire({
        title: '¿Estás seguro de cambiar el estado?',
        /*text: "Si realizas el cambio se rediri!",*/
        /*icon: 'warning',*/
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '¡Si, cambiar!',
        cancelButtonText: '¡Cancelar!'
        }).then((result) => {
        if (result.isConfirmed) {
          let id_ticket = $('#id_ticket').val();
          $.ajax({
            url: "/posponer_llamado_ticket/" + id_ticket,
            async: false,
            type: "GET",
            success: function(result) {
              if (result=="2") {
                $(document).ready(function () {
                  let fechaDesde = $('#fechaDesde').val();
                  let fechaHasta = $('#fechaHasta').val();
                  let estado = $('#estado').val();
                  $.ajax({
                    type: "GET",
                    url: "/busca_ticket_totem2/" + fechaDesde + '/' + fechaHasta + '/' + estado,
                    dataType: "html",
                    success: function (response) {
                    location.reload();
                    /*
                    $(document).ready(function () {
                        $('#estado_llamado2').val('true');
                        $('#seccion_listado').html(response);
                        loading();
                        limpiarInput();
                    });*/    
                    }
                  });
                });
              } else {
                console.log = "error";
              }
            },
            complete : function(xhr, status) {
              return false;
            }
          });
        } else {
          $('.loading').hide();
        }
        });
      } else {
        let id_ticket = $('#id_ticket').val();
        $.ajax({
          url: "/posponer_llamado_ticket/" + id_ticket,
          async: false,
          type: "GET",
          success: function(result) {
            if (result=="2") {
              $(document).ready(function () {
                let fechaDesde = $('#fechaDesde').val();
                let fechaHasta = $('#fechaHasta').val();
                let estado = $('#estado').val();
                $.ajax({
                  type: "GET",
                  url: "/busca_ticket_totem2/" + fechaDesde + '/' + fechaHasta + '/' + estado,
                  dataType: "html",
                  success: function (response) {
                  location.reload();
                  /*
                  $(document).ready(function () {
                      $('#estado_llamado2').val('true');
                      $('#seccion_listado').html(response);
                      loading();
                      limpiarInput();
                  });*/    
                  }
                });
              });
            } else {
              console.log = "error";
            }
          },
          complete : function(xhr, status) {
            return false;
          }
        });
      }
    }
  });
  $('#btnAtendido').click(function (e) {
    e.preventDefault();
    $('.loading').show();
    let estado_llamado = $('#estado_llamado').val();
    let estado_llamado2 = $('#estado_llamado2').val();
    let flagEstado = $('#flagEstado').val();
    let id_ticket = $('#id_ticket').val();
    if (estado_llamado2 == 'false' && flagEstado == '0') {
      $.ajax({
      url: "/confirmar_atencion_ticket/" + id_ticket,
      async: false,
      type: "GET",
      success: function(result) {
        if (result=="3") {
          $(document).ready(function () {
            $.ajax({
              type: "GET",
              url: "/listado_tickets",
              dataType: "html",
              success: function (response) {
              $(document).ready(function () {
                  $('#estado_llamado').val('true');
                  $('#seccion_listado').html(response);
                  loading();
                  limpiarInput();
              });    
              }
            });
          }); 
        } else {
          console.log = "error";
        }
      },
      complete: function(xhr, status) {
        return false;
      }
      });
    }
    if (estado_llamado == 'false' && flagEstado == '1') {
      $.ajax({
      url: "/confirmar_atencion_ticket/" + id_ticket,
      async: false,
      type: "GET",
      success: function(result) {
        if (result=="3") {
          $(document).ready(function () {
            let fechaDesde = $('#fechaDesde').val();
            let fechaHasta = $('#fechaHasta').val();
            let estado = $('#estado').val();
            $.ajax({
              type: "GET",
              url: "/busca_ticket_totem2/" + fechaDesde + '/' + fechaHasta + '/' + estado,
              dataType: "html",
              success: function (response) {
              location.reload();
              /*
              $(document).ready(function () {
                  $('#estado_llamado2').val('true');
                  $('#seccion_listado').html(response);
                  loading();
                  limpiarInput();
              });
              */    
              }
            });
          }); 
        } else {
          console.log = "error";
        }
      },
      complete: function(xhr, status) {
        return false;
      }
    });
    }
  });

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

  $('#llamado_seleccionado_us').ready(function () {
    let user = $('#user').val();
    let op = $('#opcion').val();
    if (op == "1") {
      $.ajax({
        type: "GET",
        url: "obtenerPacienteLlamadoUs/"+ user,
        dataType: "json",
        success: function (result) {
          //console.log(result['id_ticket']);
          //$(document).ready(function () {
          //e.preventDefault();
          let llamado_seleccionado_us = $('#llamado_seleccionado_us').val();
          if (llamado_seleccionado_us == 'true') {
          $.ajax({
            type: "GET",
            url: "/listado_tickets",
            dataType: "html",
            success: function (response) {
              //$(document).ready(function () {
                $('#seccion_listado').html(response);
                let estado_llamado = $('#estado_llamado').val();
                let id_ticket = result['id_ticket'];
                //console.log(estado_llamado);
                if (estado_llamado == 'false') {
                  $('#btnLlamar').attr('disabled', false);
                  $('#btnCancelar').attr('disabled', false);
                  $('#btnAtendido').attr('disabled', false);
                  $(`.tbReg[data-id=${id_ticket}]`).css('background', 'rgb(211,249,211)',);
                  $(`.tbReg[data-id=${id_ticket}] td`).css('font-weight', 'bold');
                }
              //});   
            }
          });
          }
          //});
        }    
      });
    } else if (op == "2") {
      $.ajax({
        type: "GET",
        url: "obtenerPacienteSeleccionadoUs/"+ user,
        dataType: "json",
        success: function (result) {
          //console.log(result['id_ticket']);
          //$(document).ready(function () {
          //e.preventDefault();
          let llamado_seleccionado_us = $('#llamado_seleccionado_us').val();
          if (llamado_seleccionado_us == 'true') {
          $.ajax({
            type: "GET",
            url: "/listado_tickets",
            dataType: "html",
            success: function (response) {
              //$(document).ready(function () {
                $('#seccion_listado').html(response);
                let estado_llamado = $('#estado_llamado').val();
                let id_ticket = result['id_ticket'];
                //console.log(estado_llamado);
                if (estado_llamado == 'false') {
                  $('#btnLlamar').attr('disabled', false);
                  $('#btnCancelar').attr('disabled', false);
                  $('#btnAtendido').attr('disabled', false);
                  $(`.tbReg[data-id=${id_ticket}]`).css('background', 'rgb(211,249,211)',);
                  $(`.tbReg[data-id=${id_ticket}] td`).css('font-weight', 'bold');
                }
              //});   
            }
          });
          }
          //});
        }    
      });
    }   
  });

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
            let estado = $('#estado').val();
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
  
  const limpiarInput = () => {
    $('#turno').val('');
    $('#nombre_paciente').val('');
    $('#id_ticket').val('');
  }

  const anularTicket = (id_ticket)  => {
    bootbox.confirm({
      size: "small",
      message: "¿Deseas anular la cita?",
      callback: function(result) {
        if (result == true) {
          fn_anularTicket(id_ticket);
        }
      }
    });
  }
  
  const fn_anularTicket = (id_ticket) => {
    $.ajax({
      url: '/anular_ticket/' + id_ticket,
      dataType: "json",
      success: function(result) {
        if (result.sw == true) {
          
          location.reload();
        }
      }
    });
  }
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
  .btn-contenedor{
    display: flex;
    justify-content: space-evenly;
    align-items: center;
    height: 10rem;
  }
  
  .btn-control{
      height: 5rem;
  }

  .text-input{
    font-weight: 700;
    text-align: center;
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
  .iconControl{
    display: flex;
    justify-content: center;
    align-items: center;
    width: 3rem;
    height: 100%;
  }
  .iconflex{
    display: flex;
    flex-direction: row;
  }
  .textControl{
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
    margin-left: 1rem;
    font-size: 2rem;
  }
  /*LOADER CIRCULAR*/
  .loader{
    pointer-events: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 3px solid transparent;
    border-top-color: #5cb85c;
    animation: an1 1s ease infinite;
  }
  @keyframes an1{
    0% {
      transform: rotate(0turn);
    }
    100% {
      transform: rotate(1turn);
    }
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
</style>
@endsection
@section('content')
<div id="modal_ventanillas"></div>
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-body">
        <legend style="text-align:center">Módulo de Llamadas</legend>
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">Busquedas de Llamadas</h3>
          </div>
          <div class="panel-body">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="user" id="user" value="{{auth()->user()->id}}">
              <div clas="row">       
                <div class="row">                               
                  <div class="col-md-3">                            
                    <label class="help-block">Fecha Desde</label>
                    <input class="form-control input-sm" type="text" name="fechaDesde" id="fechaDesde" placeholder="Fecha Desde" value="{{$fechaDesde}}" maxlength="10">
                  </div>                        
                  <div class="col-md-3">
                    <label class="help-block">Fecha Hasta</label>
                    <input class="form-control input-sm" type="text" name="fechaHasta" id="fechaHasta" placeholder="Fecha Hasta" value="{{$fechaHasta}}" maxlength="10">
                  </div>
                  <div class="col-md-3">
                    <label class="help-block">Estado</label>
                    <select name="estado" class="form-control input-sm" id="estado">
                      <option @if($estado=="0,1,2") selected @endif value="0,1,2">Pendiente y Llamando</option>
                      <option @if($estado=="0") selected @endif value="0">Pendiente</option>
                      <option @if($estado=="1") selected @endif value="1">Llamando</option>
                      <option @if($estado=="2") selected @endif value="2">Pospuesto</option>
                      <option @if($estado=="3") selected @endif value="3">Atendido</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <br/><br/>
                    <button id="btnBuscar" class="form-control btn btn-info input-sm">Buscar</button>
                  </div>
                </div>
              </div>
          </div>
        </div>     
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">Control de gestión de colas</h3>
          </div>
          <div class="panel-body">
            <div class="col-md-12">
              <div class="col-md-4">
                @if ($pData['0']['id_ticket'] !== '')
                <div class="row">
                  <div class="flex-col">
                    <label>Turno</label>
                    <input type="hidden" name="id_ticket" id="id_ticket" value="{{$pData['0']['id_ticket']}}"/>
                    <input readonly type="text" name="turno" id="turno" class="form-control input-sm text-input" value="{{$pData['0']['turno']}}"/>
                  </div>
                </div>
                <div class="row">
                  <div class="flex-col">
                    <label>Paciente</label>
                    <input readonly type="text" name="nombre_paciente" id="nombre_paciente" class="form-control input-sm text-input" value="{{$pData['0']['paciente']}}"/>
                  </div>
                </div>
                @elseif ($pData['1']['id_ticket'] !== '')
                <div class="row">
                  <div class="flex-col">
                    <label>Turno</label>
                    <input type="hidden" name="id_ticket" id="id_ticket" value="{{$pData['1']['id_ticket']}}"/>
                    <input readonly type="text" name="turno" id="turno" class="form-control input-sm text-input" value="{{$pData['1']['turno']}}"/>
                  </div>
                </div>
                <div class="row">
                  <div class="flex-col">
                    <label>Paciente</label>
                    <input readonly type="text" name="nombre_paciente" id="nombre_paciente" class="form-control input-sm text-input" value="{{$pData['1']['paciente']}}"/>
                  </div>
                </div>
              @elseif ($pData['0']['id_ticket'] == '' && $pData['1']['id_ticket'] == '')
                <div class="row">
                  <div class="flex-col">
                    <label>Turno</label>
                    <input type="hidden" name="id_ticket" id="id_ticket" value=""/>
                    <input readonly type="text" name="turno" id="turno" class="form-control input-sm text-input" value=""/>
                  </div>
                </div>
                <div class="row">
                  <div class="flex-col">
                    <label>Paciente</label>
                    <input readonly type="text" name="nombre_paciente" id="nombre_paciente" class="form-control input-sm text-input" value=""/>
                  </div>
                </div>
              @endif
                <div class="row">
                  <div class="flex-col">
                    <label class="help-block">Ventanilla</label>
                    @if ($ventServ['servicio']['flagventanilla'] == 0 && $ventServ['servicio']['id_ventanilla'] == null)
                      <select class="form-control" name="ventanilla" id="ventanilla">
                        <option value="{{$ventanillas['value']}}">{{$ventanillas['nombre']}}</option>
                      </select>
                    @elseif ($ventServ['servicio']['id_ventanilla'] !== null)
                      <select class="form-control" name="ventanilla" id="ventanilla">
                        <option value="{{$vent[0]->id}}" selected="selected">{{$vent[0]->nombre}}</option>
                      </select>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-md-8 btn-contenedor">
                <div class="col-md-2">
                  <button id="btnLlamar" class="btn btn-sm btn-success ancho btn-control iconflex">
                    <div class="iconControl">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125z" />
                      </svg>
                    </div>
                    <div class="textControl">
                      Llamar
                    </div>
                  </button>
                </div>
                <div class="col-md-2">
                  <button id="btnCancelar" class="btn btn-sm btn-danger ancho btn-control iconflex">
                    <div class="iconControl">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </div>
                    <div class="textControl">
                      Cancelar
                    </div>
                  </button>
                </div>
                <div class="col-md-2">
                  <button id="btnAtendido" class="btn btn-sm btn-primary ancho btn-control iconflex">
                    <div class="iconControl">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                      </svg> 
                    </div>
                    <div class="textControl">                  
                      Atendido
                    </div>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        @if ($pData['0']['id_ticket'] !== '')
          <input type="hidden" name="estado_llamado" id="estado_llamado" value="{{$pData['0']['estado_llamado']}}"/>
          <input type="hidden" name="flagEstado" id="flagEstado" value="0"/>
          <input type="hidden" name="estado_llamado2" id="estado_llamado2" value="false"/>
          <input type="hidden" name="llamado_seleccionado_us" id="llamado_seleccionado_us" value="{{$pData['0']['llamado_seleccionado_us']}}"/>
          <input type="hidden" name="opcion" id="opcion" value="{{$pData['0']['opcion']}}"/>
        @elseif ($pData['1']['id_ticket'] !== '')
          <input type="hidden" name="estado_llamado" id="estado_llamado" value="{{$pData['1']['estado_llamado']}}"/>
          <input type="hidden" name="flagEstado" id="flagEstado" value="0"/>
          <input type="hidden" name="estado_llamado2" id="estado_llamado2" value="false"/>
          <input type="hidden" name="llamado_seleccionado_us" id="llamado_seleccionado_us" value="{{$pData['1']['llamado_seleccionado_us']}}"/>
          <input type="hidden" name="opcion" id="opcion" value="{{$pData['1']['opcion']}}"/>
        @elseif ($pData['0']['id_ticket'] == '' && $pData['1']['id_ticket'] == '')
          <input type="hidden" name="estado_llamado" id="estado_llamado" value="true"/>
          <input type="hidden" name="flagEstado" id="flagEstado" value="0"/>
          <input type="hidden" name="estado_llamado2" id="estado_llamado2" value="false"/>
          <input type="hidden" name="llamado_seleccionado_us" id="llamado_seleccionado_us" value="false"/>
          <input type="hidden" name="opcion" id="opcion" value="0"/>
        @endif
        <div class="row flex-row loading">
          <div class="loader"></div>
        </div>
        <div id="seccion_listado">

        </div>
      </div>
    </div>
  </div>
</div>
@include('frontend.includes.footer')
@endsection