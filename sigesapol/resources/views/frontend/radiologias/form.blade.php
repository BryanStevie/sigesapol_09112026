<div class="modal" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form_contact" method="post" class="form-horizontal" data-toggle="validator" enctype="multipart/form-data">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"> &times; </span>
                    </button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="box-body"><br/><br/>
                    <input type="hidden" id="id" name="id">
                    <div class="col-md-12">
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-11 form-group has-error">                        
                            <div class="input-group">
                                <span class="input-group-addon" style="font-size:12px;"><b> Decripcion del informe</b></span>
                                <textarea style="font-size:12px;" rows="2" id="id" placeholder="id"  name="id" class="form-control">                                    
                                </textarea>
                                <span class="help-block with-errors"></span>
                            </div>                           
                        </div>
                    </div>
                <div class="modal-footer">
                    <button id="guardar" class="btn btn-primary btn-save">Guardar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
