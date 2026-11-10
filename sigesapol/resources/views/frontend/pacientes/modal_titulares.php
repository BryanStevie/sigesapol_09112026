<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <p>El paciente no puede cambiarse de parentesco porque cuenta con carga familiar dependiente.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="tablemodalm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>TIPO DOC.</th>
                                    <th>NUMERO DOC.</th>
                                    <th>APELLIDOS Y NOMBRES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                foreach($data as $row):
                                ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $row->tipo_doc_ident ?></td>
                                    <td><?php echo $row->nro_doc_ident ?></td>
                                    <td><?php echo $row->paterno.' '.$row->materno.', '.$row->nombre ?></td>
                                </tr>
                                <?php endforeach;?>
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