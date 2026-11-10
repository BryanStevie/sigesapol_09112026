
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

<div class="container" style="width: 100%!important;margin-bottom: 0px">
    <div class="row" style="margin-bottom: 0px">
        <div class="col-md-12 well" style="margin-bottom: 0px">
            <div class="row">
                <div class="panel panel-primary filterable" style="margin-bottom: 0px">
                    <div class="panel-heading">
                        <h3 class="panel-title">Centro de Atención</h3>
                        <!--
                        <div class="pull-right">
                            <button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Buscar</button>
                        </div>
                        -->
                    </div>
                    <table class="table"  id="example">
                        <thead>
                            <tr class="filters">
                                <th>N</th>
                                <th><input type="text" class="form-control" placeholder="Farmacia" disabled></th>
                                <th><input type="text" class="form-control" placeholder="Servicio Asistencial" disabled></th>
                                <!--<th>Editar</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $n = 0;
                            //var_dump($servicios);exit();
                            ?>
                            @foreach($servicios as $ser)
                            <tr>
                                <td><?php $n = $n + 1;
                            echo $n; ?></td>
                                <td>{{$ser->farmacia}}</td>
                                <td>{{$ser->servicio}}</td>
                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                    
                </div>
            </div>
        </div>
    </div>
</div>
