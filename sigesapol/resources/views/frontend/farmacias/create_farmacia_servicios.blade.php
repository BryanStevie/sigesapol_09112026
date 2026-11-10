
<script>
        
    $(document).ready(function () {
        /*
        $cmbEst = $("#frmfarmacia").find("#id_establecimiento");
        $cmbEst.select2();
        $cmbNom = $("#frmfarmacia").find("#nombre");
        $cmbNom.select2();
        $cmbEst.change(function () {
            $this = $(this);
            cmbEst = $cmbEst.val();
            option = {
                url: 'cargarnombre/' + cmbEst,
                type: 'GET',
                dataType: 'json',
                data: {}
            };
            $.ajax(option).done(function (data) {
                cargarComboNombre($cmbNom, data);
                $cmbNom.val(null).trigger("change");
                document.getElementById("nombre").disabled = false;
            });
        });
        */
    });
    /*
    function cargarComboNombre($select, data) {
        $select.html('');
        $(data).each(function (ii, oo) {
            $select.append('<option value="" required="required" >' + "Selecciona el nombre de la Farmacia"+ '</option>')
            $select.append('<option value="' + oo.nombre + '">' + oo.nombre + '</option>')
        });
    }
    */
</script>

<div class="container" style="width: 100%!important">
    <div class="row" >
        
        <div class="col-md-12 well">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <div class="panel-title">Agregar Nuevo Centro de Atención</div>                    
                </div>     
                <div style="padding-top:30px;" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmfarmacia" class="form-horizontal" role="form" method="post"  action="{{ route('store_farmacia_servicios') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id_farmacia" id="id_farmacia" value="{{$id_farmacia}}" />
                        <div style="margin-bottom: 25px">                            
                            <select class="form-control" name="id_servicio" required="" id="id_servicio" >
                                <option value="" selected="">--Seleccionar Servicio--</option>
                                @foreach($consultorios as $e)
                                <option value="{{$e->id}}">{{$e->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--<div style="margin-bottom: 25px">
                            <select id="nombre" name="nombre" class="form-control" tabindex="11"  style="margin-bottom: 10px" disabled="true" required="">
                                <option value=""  selected style="color: #888888">Selecionar nombre de la Farmacia</option>      
                            </select>
                        </div>-->                        
                        <div style="margin-top:10px" class="form-group">
                            <!-- Button -->
                            <div class="col-sm-12 controls">
                                <input type="submit" value="Guardar" class="btn btn-success">
                            </div>
                        </div> 
                    </form>     
                </div>                     
            </div>
        </div>
    </div>
</div>
