    <div class="container">
        @if ($asegurado != null)
            <div class="row mt-50 mb-50" >
                <div class="col-xs-offset-1 col-xs-10">
                    <p class="fs-large">
                        <b>BIENVENIDO / WELCOME :</b><br>
                        {{$asegurado->paterno}} {{$asegurado->materno}}, {{$asegurado->nombre}}
                    </p>
                </div>
            </div>
        @else
            <div class="row mt-50">
                <p class="fs-large" style="color:red" id="mensaje">No existe el paciente con el N° Documento ingresado, verifique.</p>
            </div>
        @endif

        @if ($asegurado != null)
        <div class="row">
            <div class="col-xs-offset-1 col-xs-10">
            <table class="table" style="width:100% !important;" id="table-presencia">
                <tr>
                    <th>HORA / TIME</th>
                    <th>CITA / APPOINTMENT</th>
                    <th>&nbsp;</th>
                </tr>
                @if ($citas != null)
                    @foreach ($citas as $c)
                    <tr>
                        <td>{{$c->hora_cita}}</td>
                        <td>{{$c->servicio}}</td>
                        <td>
                            @if ($c->estado_presencia != '1')
                            <button type="button" class="btn btn-success" onclick="indicarPresencia({{$c->id}})" style="font-size: 28px !important;">Indicar Presencia</button>
                            @else 
                            Se ha informado al servicio que ya se encuentra presente.
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @else
                <tr>
                    <td colspan="3">
                        El paciente no cuenta con citas para hoydía.<br>
                        The patient has not appointments for today.
                    </td>
                </tr>
                @endif
            </table>
            </div>
        </div>
        @endif

        <div class="row mt-150">
            <div class="col-xs-offset-1 col-xs-10 ">
                <button type="button" class="btn btn-info" onclick="menu_citas_medicas()">ATRAS / [BACK]</button>    
            </div>
        </div>
    </div>