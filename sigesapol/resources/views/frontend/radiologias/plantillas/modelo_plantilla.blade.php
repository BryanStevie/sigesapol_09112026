<title>Sistema de Farmacia</title>
<link rel="stylesheet" href="../../sceditor/minified/themes/office.min.css" id="theme-style" />
<div class="container" style="width:100%">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                  <div class="form-group">
                    <div class="col-md-12">
                      <label>Título de Plantilla</label>
                        <textarea id="titulo" required="" name="titulo" rows="2" style="height:300px;width:100%;">
                            {{$plantilla->cabecera}}
                            {{$plantilla->cuerpo}}
                            {{$plantilla->pie_pagina}}
                        </textarea>
                        <div class="form-group">
                            <div class="col-md-6">
                              <a href="/all_radiologia_prescritas_by_ipress" class="form-control btn btn-danger" tabindex="99922" style="text-align:center!important">CANCELAR</a>
                            </div>
                            <div class="col-md-6">
                              <input <?php echo $disabled_radiologia?> type="submit" value="GUARDAR" class="form-control btn btn-success" id="btnReg" style="text-align:center!important">
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--script src="{{ asset('/ckeditor/ckeditor.js') }}"></script-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="{{ asset('../../sceditor/minified/sceditor.min.js') }}"></script>
<script src="{{ asset('../../sceditor/languages/es.js') }}"></script>
<script src="{{ asset('../../sceditor/minified/icons/monocons.js') }}"></script>
<script src="{{ asset('../../sceditor/minified/formats/bbcode.js') }}"></script>
<script>
    var textarea = document.getElementById('parrafo1');
    sceditor.create(textarea, {
        icons: 'monocons',
        locale: 'es-ES',
        value
        //style: '../../sceditor/minified/themes/content/default.min.css'
        
    });
</script>