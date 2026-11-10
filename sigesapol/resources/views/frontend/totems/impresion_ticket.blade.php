    <div class="container">
        <div class="row mt-150">
            <table class="table" id="table-impresion">
                @if ($registrado)
                <tr>
                    <td>Se generó ticket correctamente</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; font-size:50px !important">{{$letra}} - {{$numero}}</td>
                </tr>
                @else
                <tr>
                    <td class="fs-large">Ha ocurrido un error en el sistema.</td>
                </tr>
                @endif
            </table>
        </div>

        <div class="row mt-150">
            <div class="col-xs-offset-1 col-xs-10 ">
                <button type="button" class="btn btn-success noprint" onclick="menu_principal()">INICIO / [HOME]</button>
                <button type="button" class="btn btn-success pull-right noprint" onclick="window.print();" target="_blank">IMPRIMIR / [PRINT]</button>
            </div>
        </div>
    </div>