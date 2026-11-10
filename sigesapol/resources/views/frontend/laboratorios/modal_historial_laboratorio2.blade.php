<title>SIGESAPOL</title>

<style>
/*****************/
.modal-dialog {
  width: 90%;
  }
  
#tablemodal{
    border-spacing: 0;
    display: flex;/*Se ajuste dinamicamente al tamano del dispositivo**/
    max-height: 80vh; /*El alto que necesitemos**/
    overflow-y: auto; /**El scroll verticalmente cuando sea necesario*/
    overflow-x: hidden;Sin scroll horizontal
    table-layout: fixed;/**Forzamos a que las filas tenga el mismo ancho**/
    width: 98vw; /*El ancho que necesitemos*/
    border:1px solid #c4c0c9;
}

#tablemodal thead{
    background-color: #e2e3e5;
    position: fixed !important;
}


#tablemodal th{
    border-bottom: 1px solid #c4c0c9;
    border-right: 1px solid #c4c0c9;
}

#tablemodal th{
    font-weight: normal;
    margin: 0;
    max-width: 9.5vw; 
    min-width: 9.5vw;
    word-wrap: break-word;
    font-size: 10px;
  font-weight:bold;
    height: 3.5vh !important;
  line-height:12px;
  vertical-align:middle;
  /*height:20px;*/
    padding: 4px;
    border-right: 1px solid #c4c0c9;
}

#tablemodal td{
    font-weight: normal;
    margin: 0;
    max-width: 9.5vw; 
    min-width: 9.5vw;
    word-wrap: break-word;
    font-size: 11px;
    height: 3.5vh !important;
    padding: 4px;
    border-right: 1px solid #c4c0c9;
}

#tablemodal tbody tr:hover td, #tablemodal1 tbody tr:hover th {
  /*background-color: red!important;*/
  font-weight:bold;
  /*mix-blend-mode: difference;*/
  
}

#tablemodalm1{
  
}

.list-categorias{
    padding: 0;
    margin: 0;
    font-size: 1rem;
}

.hide-element {
    display: none;
}

.hide-button-text {
    font-size: 0;
    line-height: 0;
    width: 1px;
    height: 1px;
    overflow: hidden;
}

.icon-visible {
    color: rgb(95, 129, 95);
}

.icon-visible:hover {
    color: green;
}

.icon-hidden {
    color: red;
}

.toggleExamenes {
    font-size: 2rem;
}

</style>

<script type="text/javascript">

    $(".toggleExamenes").click(function(){
        var registro = $(this).data('registro');
        $("#elementoOcultar" + registro).toggle();

        var icono = $(this).find('i');

        // icono.toggleClass('icon-visible');
        icono.toggleClass('icon-hidden');
    });

</script>


<body class="hold-transition skin-blue sidebar-mini">
    <div>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                  <div class="box box-primary">
                    <br>
                    <div class="box-body">
                        <p><span class="label label-info"><i class="fa fa-info"></i></span> Exámenes registrados antes de la Implementacion del <b>SIGESAPOL</b></p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm" id="tablemodalm1">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>TIPO DOC</th>
                                        <th>NUMERO DOC</th>
                                        <th>FECHA</th>
                                        <th>ESTABLECIMIENTO</th>
                                        <th>TIPO ATENCION</th>
                                        <th>CONSULTORIO</th>
                                        <th>MEDICO</th>
                                        <th>N° ORDEN</th>
                                        <th class="text-center">VER</th>
                                    </tr>
                                </thead>
                                <tbody id="muestra-atenciones" style="padding-top:32px">
                                    <?php
                                    $n=1;
                                    ?>
                                    @if (!empty($ordenes_laboratorio->RespuestaOrdenLab))
                                        @if (count($ordenes_laboratorio->RespuestaOrdenLab)>1)
                                            @foreach ($ordenes_laboratorio->RespuestaOrdenLab as $q)
                                                <tr>
                                                    <td>{{ $n }}</td>
                                                    <td><?php ?></td>
                                                    <td><?php echo $q->nroDocumento?></td>
                                                    <td><?php echo $q->fechaMuestra?></td> <!-- Fecha y Hora de Toma muestra -->
                                                    <td style="font-size:13px"><?php echo $q->establecimiento?></td>
                                                    <td><?php ?></td>
                                                    <td><?php echo $q->tipoAtencion?></td>
                                                    <td><?php ?></td>
                                                    <td>{{ $q->orden }}</td>
                                                    <td class="text-center">
                                                        <a target="_blank" href="/print_examen_laboratorio_lns/0/{{$q->nroDocumento}}/{{$q->fechaMuestra}}/{{$q->orden}}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                                <?php
                                                $n++;
                                                ?>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>{{ $n }}</td>
                                                <td><?php ?></td>
                                                <td><?php echo $ordenes_laboratorio->RespuestaOrdenLab->nroDocumento?></td>
                                                <td><?php echo $ordenes_laboratorio->RespuestaOrdenLab->fechaMuestra?></td>
                                                <td style="font-size:13px"><?php echo $ordenes_laboratorio->RespuestaOrdenLab->establecimiento?></td>
                                                <td><?php ?></td>
                                                <td><?php echo $ordenes_laboratorio->RespuestaOrdenLab->tipoAtencion?></td>
                                                <td><?php ?></td>
                                                <td>{{ $ordenes_laboratorio->RespuestaOrdenLab->orden }}</td>
                                                <td class="text-center">
                                                    <a target="_blank" href="/print_examen_laboratorio_lns/0/{{$ordenes_laboratorio->RespuestaOrdenLab->nroDocumento}}/{{$ordenes_laboratorio->RespuestaOrdenLab->fechaMuestra}}/{{$ordenes_laboratorio->RespuestaOrdenLab->orden}}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                  </div>
                <!-- /.box -->
                </div>
                <!--/.col (left) -->
            </div>
          <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
</body>