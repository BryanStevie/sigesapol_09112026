    <div class="container">
        @if ($asegurado != null)
            <div class="row mt-50 mb-50" >
                <div class="col-xs-offset-1 col-xs-10">
                    <p class="fs-large">
                        <b>MENU PRINCIPAL</b><br>
                        {{$asegurado->paterno}} {{$asegurado->materno}}, {{$asegurado->nombre}}
                    </p>
                </div>
            </div>

            <div class="row mt-150">
                <table class="table" id="table-presencia">
                    <tr>
                        <th>HORA / TIME</th>
                        <th>CITA / APPOINTMENT</th>
                        @foreach ($totem_opciones as $t)
                        @if($t->id_totem_opcion == 1)
                        <th>TRIAJE</th>
                        @endif
                        @endforeach
                        @foreach ($totem_opciones as $t)
                        @if($t->id_totem_opcion == 4)
                        <th>PRESENCIA</th>
                        @endif
                        @endforeach
                    </tr>
                    @if (count($citas) > 0)
                        @foreach ($citas as $c)
                        <tr>
                            <td>{{$c->hora_cita}}</td>
                            <td>{{$c->servicio}}</td>
                            @foreach ($totem_opciones as $t)
                            @if($t->id_totem_opcion == 1)
                            <td>
                                @if ($c->coalesce_estado_triaje != '1')
                                    <button type="button" class="btn btn-success form-control" onclick="sub_menu_triaje(1,{{$c->id}})">TRIAJE</button>
                                @else
                                Ticket de Triaje<br>registrado
                                @endif
                            </td>
                            @endif
                            @endforeach
                            @foreach ($totem_opciones as $t)
                            @if($t->id_totem_opcion == 4)
                            <td>
                                @if ($c->estado_presencia != '1')
                                    <button type="button" class="btn btn-success" onclick="indicarPresencia({{$c->id}})">PRESENTE</button>
                                @else 
                                Indicación de presencia<br>registrado
                                @endif
                            </td>
                            @endif
                            @endforeach
                        </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="4">
                            El paciente no cuenta con citas para hoydía.<br>
                            The patient has not appointments for today.
                        </td>
                    </tr>
                    @endif
                </table>
            </div>

            <div class="row mt-150">
                <div class="col-xs-offset-1 col-xs-10">
                    @foreach ($totem_opciones as $t)
                        @if($t->id_totem_opcion == 5)
                        <button type="button" style="margin-bottom: 50px;" class="btn btn-success form-control" onclick="{{$t->funcion}}({{$t->id_totem_opcion}})"><i class="fa fa-arrow-right"></i>&nbsp;{{$t->nombre}}</button>
                        @endif
                    @endforeach
                    <!-- <button type="button" class="btn btn-primary form-control" onclick="menu_citas_medicas()"><i class="fa fa-arrow-right"></i>&nbsp;CITAS MÉDICAS</button> -->
                    <!-- <button type="button" class="btn btn-primary form-control" disabled="true"><i class="fa fa-arrow-right"></i>&nbsp;RECEPCIÓN</button> -->
                    <!-- <button type="button" class="btn btn-primary form-control" onclick="menu_otros_tramites()"><i class="fa fa-arrow-right"></i>&nbsp;OTROS TRÁMITES</button> -->
                </div>
            </div>
        @else
            <div class="row mt-50">
                <p class="fs-large" style="color:red" id="mensaje">No existe el paciente con el N° Documento ingresado, verifique.</p>
            </div>
        @endif

        <div class="row mt-150">
            <div class="col-xs-offset-1 col-xs-10 ">
                <button type="button" class="btn btn-success" onclick="ir_inicio()">ATRAS / [BACK]</button>    
            </div>
        </div>
    </div>