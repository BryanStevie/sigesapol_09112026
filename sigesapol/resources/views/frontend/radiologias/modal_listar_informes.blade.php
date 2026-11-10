<link rel="stylesheet" href="{{ asset('sceditor/minified/themes/office.min.css') }}" id="theme-style" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="{{ asset('sceditor/minified/sceditor.min.js') }}"></script>
<script src="{{ asset('sceditor/languages/es.js') }}"></script>
<script src="{{ asset('sceditor/minified/icons/monocons.js') }}"></script>
<script src="{{ asset('sceditor/minified/formats/bbcode.js') }}"></script>
<script src="{{ asset('js/select2-4/dist/js/select2.js') }}"></script>
<script>
    $(document).ready(function () {
        // Inicializar SCEditor solo para elementos de párrafo con id mayor a 'parrafo1'
        $('[id^=parrafo]').each(function() {
            let id = $(this).attr('id');
            let num = parseInt(id.replace('parrafo', ''), 10);
            if (num >= 2) {
                sceditor.create(this, {
                    icons: 'monocons',
                    locale: 'es-ES',
                    style: '{{ asset("sceditor/minified/themes/content/default.min.css") }}',
                    emoticonsRoot: '{{ asset("sceditor/emoticons/") }}/'
                });
            }
        });

        // Manejar cambios en select y cargar plantilla correspondiente
        $('.id_plantilla').change(function () {
            let selectId = $(this).attr('id');
            let selectValue = $(this).val();
            let textareaId = selectId.replace('id_plantilla', 'parrafo');
            let textarea = document.getElementById(textareaId);
            let instance = sceditor.instance(textarea);
            if (selectValue == '0') {
                //console.log(`El id es : ${selectId} | el value es : ${textareaId}`);
                instance.val('');
                $(`#${textareaId}`).val('');
            } else {
                const url = "{{ url('cargarplantilla') }}";
                $.ajax({
                    type: "GET",
                    url: `${url}/${selectValue}`,
                    data: {}
                }).done(function(msg) {
                    instance.val('');
                    $(`#${textareaId}`).val('');
                    instance.insert(msg);
                });
            }
        });
    });
</script>
<div>
    <br/>
    <?php 
    $i=1; 
    foreach($procradiologia as $row):
        $i++;
    ?>
        <div class="col-sm-12 col-md-12">
            <br/>
            <p><b><?php echo $row->codigo." - ".$row->nombre?></b></p>
            <br/>
        </div>
        <div class="col-sm-12 col-md-12">
            <div class="col-md-10">
                <select  class="form-control input-sm id_plantilla" name="id_plantilla[]" id="id_plantilla{{$i}}" data-codigo="{{$row->codigo}}"  style="width: 100%">
                    <?php if(count($plantillas)>1): ?>
                    <option value="0">-Seleccionar-</option>
                    <?php endif; ?>
                        <?php
                        // foreach ($plantillas as $row):
                        //     echo '<option value="' . $row->id . '" >' . $row->nombre_plantilla. '</option>';
                        // endforeach
                        foreach ($plantillas as $row1):
                            $value = $row1->id;
                            $nombre_plantilla = $row1->nombre_plantilla;
                            $selected = '';
                            if($row->tipo_plantilla==$row1->id): 
                                $selected = 'selected'; 
                            endif;
                            $option = '<option value="'. $value .'" '. $selected .'>'. $nombre_plantilla .'</option>';
                            echo $option;
                        endforeach
                        ?>
                    </select>
                <br/>
            </div>
            <div class="col-md-2">
                
            </div>
        </div>
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                <div class="col-md-12"><br/>
                <!--?php dd($row)?-->
                    <label>PÁRRAFO {{$i-1}}</label>
                    <textarea id="parrafo{{$i}}" class="parrafo" name="parrafo[]" data-indice="{{$i-1}}" rows="2" style="height:300px;width:100%;"></textarea>
                </div>
            </div>
        </div>  
    <?php 
    endforeach;
    ?>
</div>
