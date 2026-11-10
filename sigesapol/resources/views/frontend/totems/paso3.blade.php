    <div class="container">
        <div class="row mt-50 mb-50" >
            <div class="col-xs-1"></div>
            <div class="col-xs-10">
                @if ($registrado)
                <p class="fs-large">Se ha informado al servicio que ya se encuentra presente.</p>
                @else
                <p class="fs-large">Ha ocurrido un error en el sistema, vuelva a intentarlo.</p>
                @endif
            </div>
        </div>

        <div class="row mt-150">
            <div class="col-xs-offset-1 col-xs-10 ">
                <button type="button" class="btn btn-success" onclick="menu_principal()">INICIO / [HOME]</button>
            </div>
        </div>
    </div>