    <div class="container">
        @if ($asegurado != null)
            <div class="row mt-150">
                <div class="col-xs-offset-1 col-xs-10">
                    @foreach($totem_opciones as $t)
                        <button type="button" class="btn btn-success form-control" onclick="{{$t->funcion}}({{$t->id_totem_opcion}})"><i class="fa fa-arrow-right"></i>&nbsp;{{$t->nombre}}</button>
                    @endforeach
                    <!-- <button type="button" class="btn btn-primary form-control" onclick="sub_menu_triaje()"><i class="fa fa-arrow-right"></i>&nbsp;TRIAJE</button> -->
                    <!-- <button type="button" class="btn btn-primary form-control" disabled="true"><i class="fa fa-arrow-right"></i>&nbsp;RECEPCIÓN</button> -->
                    <!-- <button type="button" class="btn btn-primary form-control" onclick="sub_menu_indicar_presencia()"><i class="fa fa-arrow-right"></i>&nbsp;INDICAR PRESENCIA</button> -->
                </div>
            </div>
        @else
            <div class="row mt-50">
                <p class="fs-large" style="color:red" id="mensaje">No existe el paciente con el N° Documento ingresado, verifique.</p>
            </div>
        @endif

        <div class="row mt-150">
            <div class="col-xs-offset-1 col-xs-10 ">
                <button type="button" class="btn btn-success" onclick="menu_principal()">ATRAS / [BACK]</button>    
            </div>
        </div>
    </div>