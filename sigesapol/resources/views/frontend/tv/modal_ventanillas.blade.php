<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<style>
    .modal-header, h4, .close {
      background-color: #3c763d;
      color:white !important;
      text-align: center;
      font-size: 30px;
    }
    .modal-footer {
      background-color: #f9f9f9;
    }
    .tituloIconflex{
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        gap: 1rem;
    }
    .tituloIconControl{
        display: flex;
        justify-content: center;
        align-items: center;
        width: 3rem;
        height: 100%;
    }
    .iconflex{
        display: flex;
        flex-direction: row;
        justify-content: left;
        align-items: center;
        gap: 1rem;
    }
    .iconControl{
        display: flex;
        justify-content: center;
        align-items: center;
        width: 1rem;
        height: 100%;
    }
</style>
<script>
    $(document).ready(function(){
        $("#myModalModuloLlamada").modal();
        $('#id_corredor').select2();
        $('#id_ventanilla').select2();	
    });
    
    //==============Get Ventanilla por Corredor============================//
    function getCorredores(){
        let id_corredor = $('#id_corredor').val();
        $.ajax({
            url: '/obtenerCorredores/' + id_corredor,
            success: function(result){                    
                let newOption = "";
                newOption = "<option value='0'>--Seleccionar-</option>";
                $(result).each(function (ii, oo) {
                    newOption += "<option value='"+oo.id+"'>"+oo.nombre_ventanilla+"</option>";
                });                 
                $('#id_ventanilla').html(newOption);
                //$("#id_ventanilla").select2({max_selected_options: 4});
            }
        });
    }
    
    function getVentanilla(){
        
        //let id_corredor = $('#id_corredor').val();
        let id_corredores_detalle = $('#id_ventanilla').val();
        $.ajax({
            url: '/obtenerVentanillaPorCorredor/' + id_corredores_detalle,
            //url: 'obtenerVentanillaPorCorredor',
            success: function(result){
                //console.log(result);
                location.reload();
            }
        });
    }
</script>
    <!-- Modal Modulo Llamada-->
  <div class="modal fade" id="myModalModuloLlamada" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="tituloIconflex">
            <div class="tituloIconControl">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125z" />
                </svg>
            </div>
            Ir a Módulo de Llamada
          </h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form">
            <div class="form-group">
              <label for="usrname" class="iconflex">
                <div class="iconControl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11.25l-3-3m0 0l-3 3m3-3v7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                Corredor
              </label>
			  <div class="col-lg-12">
                <select class="form-control" name="id_corredor" id="id_corredor" onchange="getCorredores()" style="width:100%">
                    <option value="0">-Seleccionar-</option>
                    @foreach($corredores as $c)
                    <option value="{{$c->id}}">{{$c->nombre}}</option>
                    @endforeach
                </select>
			  </div>
            </div>
			<div class="clearfix" style="margin-bottom:20px"></div>
            <div class="form-group">
              <label for="psw" class="iconflex">
                <div class="iconControl">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                  </svg>
                </div>  
                Ventanilla
              </label>
			  <div class="col-lg-12">
          <select class="form-control" name="id_ventanilla" id="id_ventanilla" style="width:100%" onchange="getVentanilla()" >
            <option value="0">-Seleccionar-</option>
			    </select>
			  </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>