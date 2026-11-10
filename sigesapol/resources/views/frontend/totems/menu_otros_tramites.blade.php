<div class="container">
    <div class="row mt-50 mb-50" >
        <div class="col-xs-offset-1 col-xs-10">
            <p class="fs-large">
                <b>MENU OTROS TRAMITES</b><br>
                {{$asegurado->paterno}} {{$asegurado->materno}}, {{$asegurado->nombre}}
            </p>
        </div>
    </div>

    <div class="row mt-150">
        <div class="col-xs-offset-1 col-xs-10">
            @foreach($totem_opciones as $t)
                <button type="button" class="btn btn-success form-control" onclick="{{$t->funcion}}({{$t->id_totem_opcion}})"><i class="fa fa-arrow-right"></i>&nbsp;{{$t->nombre}}</button>
            @endforeach
        </div>
    </div>

    <div class="row mt-150">
        <div class="col-xs-offset-1 col-xs-10 ">
            <button type="button" class="btn btn-success" onclick="menu_principal()">ATRAS / [BACK]</button>    
        </div>
    </div>
</div>