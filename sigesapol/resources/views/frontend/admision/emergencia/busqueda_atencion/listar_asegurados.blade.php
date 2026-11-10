 <script>
 

  $('body').on('click', '#consultar_busqueda', function () {  
    listar_asegurados_consultados();
  });

  presionar_keypress_input('#valor_de_busqueda'); 
    function presionar_keypress_input(input){ 
        $(input).keypress(function(event) {
            if (event.which === 13) { 
              event.preventDefault();  
              $("#listaAsegurados").empty();
              $("#listaAtenciones_by_asegurado").empty();
              $("#valor_de_busqueda").focus();
              listar_asegurados_consultados();
            }
        });
    }

 
  function listar_asegurados_consultados(){ 
      $("#listaAsegurados").empty();
      $("#listaAtenciones_by_asegurado").empty();
      var campo_de_busqueda = $("#campo_de_busqueda").val();
      var valor_de_busqueda = $("#valor_de_busqueda").val(); 
      var valor_de_busqueda_fecha = $("#valor_de_busqueda_fecha").val(); 
      if(campo_de_busqueda == 3){
        valor_de_busqueda = valor_de_busqueda_fecha;
      }
      var var_url="{{url('asegurados_by_post')}}";
        $.ajax({
          url: var_url,
          type: 'POST',  // Cambio de GET a POST
          dataType: 'json',
          data: {
            campo_de_busqueda:campo_de_busqueda,
            valor_de_busqueda:valor_de_busqueda
          }, 
          beforeSend: function() { 
            Swal.fire({
              title: '<div class="titulo-padding"><p>Alerta de Triaje</p></div>',
              html: '<p class="descripcion-alert">Espere un momento mientras se genera la consulta de datos.</p><br>',
              confirmButtonColor: '#3085d6', 
              allowOutsideClick: false,
              showConfirmButton: false  
            });
          },
          success: function(data){ 
              $("#listaAsegurados").empty();
                var valor = '' ; 
                    $.each(data, function (key, value) {
                        valor = valor+
                        '<tr style="cursor:pointer"  onclick="seleccionar_fila(this)">'+
                        ' <td id="numero" style="vertical-align: middle;"><b>' + value.nro_doc_ident + '</b></td>'; 

                        if(value.cip ==null){
                          valor = valor+' <td id="numero" style="vertical-align: middle;">-</td>';
                        }else{
                          valor = valor+' <td id="numero" style="vertical-align: middle;">' + value.cip + '</td>';
                        }

                        if( value.grado == null){
                          valor = valor+' <td id="numero" style="vertical-align: middle;">-</td>'+
                          ' <td id="numero" style="vertical-align: middle;">' + value.nro_historia + '</td>';
                        }else{ 
                          valor = valor+' <td id="numero" style="vertical-align: middle;">' + value.grado + '</td>'+
                          ' <td id="numero" style="vertical-align: middle;">' + value.nro_historia + '</td>';
                        }

                        if(value.tipo_beneficiario == null){
                          valor = valor+' <td id="numero" style="vertical-align: middle;">-</td>';
                        }else{
                          valor = valor+' <td id="numero" style="vertical-align: middle;">' + value.tipo_beneficiario + '</td>';
                        }
                      
                        if(value.id_estado_reg == 1){
                          valor = valor+ ' <td id="numero" style="vertical-align: middle;">' + value.paterno + ' ' + value.materno + ' ' + value.nombre + '</td>'+ 
                          ' <td style="vertical-align: middle;">REGISTRADO</td>';
                        }else{
                          valor = valor+ ' <td id="numero" style="vertical-align: middle;">' + value.paterno + ' ' + value.materno + ' ' + value.nombre + '</td>'+ 
                          ' <td style="vertical-align: middle;">ELIMINADO</td>'; 
                        }
                        
                        valor = valor+ '</tr>';
                    }); 
                $("#listaAsegurados").html(valor);
                Swal.fire({
                  title: '<div class="titulo-padding"><p>Alerta de Búsqueda</p></div>',
                  html: '<p class="descripcion-alert"><span>CONSULTA DE DATOS EXITOSA.<br><br></span></p>', 
                  allowOutsideClick: false,
                  timer: 1500,
                  timerProgressBar: true,
                  showConfirmButton: false,
                  allowOutsideClick: false,
                });
          }
        })
    }; 

    function seleccionar_fila(x) {  
        var elementos = document.getElementById('miTabla').getElementsByTagName('tbody')[0].getElementsByTagName('tr'); 
        for (var i = 0; i < elementos.length; i++) { 
            elementos[i].style.background='white'; 
        }  
        x.style.background="rgb(233, 233, 233)";    
        listar_atenciones_by_asegurado(x.getElementsByTagName('td')[0].innerText);
    }


</script>