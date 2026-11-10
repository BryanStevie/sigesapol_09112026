<!-- Modal -->
<div class="modal fade" id="modalKardex" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioKardex"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioKardex" name="FormularioKardex" enctype="multipart/form-data" method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="id_hospitalizacion_kardex" name="id_hospitalizacion_kardex">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;">
                                <fieldset>
                                    <!-- Cabecera de pacientes -->
                                    @include('frontend.hospitalizacion.paciente_cabecera.paciente_cabecera') 
                                    <div class="col-md-12">
                                        <hr>
                                    </div>
                                </fieldset>

                                <fieldset>

                                    <div class="panel with-nav-tabs panel-default"
                                        style="font-size: 11px;padding-bottom: 0px !important; visibility: ">
                                        <div class="panel-heading">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a href="#tab0defaultKardex" data-toggle="tab"
                                                        style="font-size:12px" aria-expanded="true"><b><i
                                                                class="fa fa-file-text-o" aria-hidden="true"></i>
                                                            KARDEX</b></a></li>
                                                <li><a href="#tab1defaultKardex" data-toggle="tab"
                                                        style="font-size:12px" aria-expanded="true"><b><i
                                                                class="fa fa-file-text-o" aria-hidden="true"></i>
                                                            CUIDADOS DE ENFERMERÍA</b></a></li>
                                                <li><a href="#tab2defaultKardex" data-toggle="tab"
                                                        style="font-size:12px" aria-expanded="true"><b><i
                                                                class="fa fa-file-text-o" aria-hidden="true"></i>
                                                            INVASIVOS</b></a></li>
                                            </ul>
                                        </div>
                                        <div class="panel-body" style="padding-bottom: 0px !important;">
                                            <div class="tab-content">

                                                <div class="tab-pane fade active in" id="tab0defaultKardex">
                                                    <fieldset>
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table id="example"
                                                                        class="table table-striped table-list-search">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="width: 60%;">MEDICAMENTO</th>
                                                                                <th>VIA</th>
                                                                                <th>FRE</th>
                                                                                <th>D.ADM</th>
                                                                                <th>F/H</th>
                                                                                <th>SUSP</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @for($i = 1; $i <= 5; $i++) <tr>
                                                                                @if ($i%2==0)
                                                                                <td>DESCRIPCIÓN DE MEDICAMENTO RECETADO
                                                                                    POR EL DOCTOR {{$i }} <br>
                                                                                    <b>6:00 - 14:00 - 14:00 - 14:00 -
                                                                                        14:00 - 14:00 - 14:00 - 14:00 -
                                                                                        14:00 - 14:00 - 14:00 - 14:00 -
                                                                                        14:00 - 14:00 - 14:00 - 14:00 -
                                                                                        14:00 - 14:00 - 14:00 -
                                                                                        14:00</b>
                                                                                </td>
                                                                                <td>ORAL</td>
                                                                                <td><b>C/8</b></td>
                                                                                <td><b>6 DÍAS</b></td>
                                                                                @else
                                                                                <td>DESCRIPCIÓN DE MEDICAMENTO RECETADO
                                                                                    POR EL DOCTOR {{$i }} <br>
                                                                                    <b>8:00 - 11:00 - 14:00 - 14:00 -
                                                                                        14:00 - 14:00 - 14:00 - 14:00 -
                                                                                        14:00</b>
                                                                                </td>
                                                                                <td>ORAL</td>
                                                                                <td><b>C/3</b></td>
                                                                                <td><b>3 DÍAS</b></td>
                                                                                @endif
                                                                                <td>
                                                                                    <a href="javascript:void(0)"
                                                                                        aria-hidden="true"
                                                                                        data-toggle="tooltip"
                                                                                        title="Ver Horario"
                                                                                        class="btn btn-success btn-xs listarHorarioKardex"
                                                                                        id="listarHorarioKardex"> <i
                                                                                            class="fa fa-clock-o"></i>
                                                                                    </a>
                                                                                </td>
                                                                                <td>
                                                                                    <a href="javascript:void(0)"
                                                                                        aria-hidden="true"
                                                                                        data-toggle="tooltip"
                                                                                        title="Suspender Medicamento"
                                                                                        class="btn btn-danger btn-xs suspender_MedicamentoKardex">
                                                                                        <i class="fa fa-clock-o"></i>
                                                                                    </a>
                                                                                </td>
                                                                                </tr>
                                                                                @endfor
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>

                                                <div class="tab-pane fade" id="tab1defaultKardex">
                                                    <fieldset>
                                                        <div class="form-group">
                                                            <div class="table-responsive">
                                                                <table id="example"
                                                                    class="table table-striped table-list-search">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 100%;">DESCRIPCIÓN DE
                                                                                EVOLUCIÓN</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @for($i = 1; $i <= 5; $i++) <tr>
                                                                            <td>DESCRIPCIÓN DE CUIDADOS DE ENFERMERÍA
                                                                                {{$i }}
                                                                            </td>
                                                                            @endfor
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>


                                                <div class="tab-pane fade" id="tab2defaultKardex">
                                                    <fieldset>
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                <div class="table-responsive text-center">
                                                                    <table id="example" 
                                                                        class="table table-striped table-list-search">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="text-align: center; width: 8%;">TIPO </th>
                                                                                <th style="text-align: center; width: 15%;">FECHA </th>
                                                                                <th style="text-align: center; width: 25%;">LUGAR </th>
                                                                                <th style="text-align: center; width: 10%;">SUSPEND </th>
                                                                                <th style="text-align: center; width: 15%;">FECHA DE RETIRO</th>
                                                                                <th style="text-align: center; width: 25%;">OBSERVACIÓN DE RETIRO</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <label for="tipo" style="margin-top: 6px;"><b>SOG / SNG</b></label>
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;"
                                                                                        name="fecha_kardex_cvc" id="fecha_kardex_cvc"
                                                                                        autocomplete="off"
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control" style="height: 28px !important;">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="checkbox" data-tipo="cvc" id="cvc_invasivoskardex" name="cvc_invasivoskardex">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;"
                                                                                        name="fecha_kardex_cvc_susp" id="fecha_kardex_cvc_susp"
                                                                                        autocomplete="off"
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 

                                                                                <td> 
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" style="height: 30px !important;">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs" style="height: 30px !important;" type="button">
                                                                                               <i class="fa fa-check" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div> 
                                                                                </td> 
                                                                            </tr>

                                                                            <tr>
                                                                                <td>
                                                                                    <label for="tipo" style="margin-top: 6px;"><b>SNY</b></label>
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;"
                                                                                        name="fecha_kardex_tet" id="fecha_kardex_tet"
                                                                                        autocomplete="off"
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control" style="height: 28px !important;">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="checkbox" id="tet_invasivoskardex" name="tet_invasivoskardex">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;"
                                                                                        name="fecha_kardex_tet_susp" id="fecha_kardex_tet_susp"
                                                                                        autocomplete="off"
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 

                                                                                <td> 
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" style="height: 30px !important;">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs" style="height: 30px !important;" type="button">
                                                                                               <i class="fa fa-check" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div> 
                                                                                </td> 
                                                                            </tr>

                                                                            <tr>
                                                                                <td>
                                                                                    <label for="tipo" style="margin-top: 6px;"><b>CVC</b></label>
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;"
                                                                                        name="fecha_kardex_traq" id="fecha_kardex_traq"
                                                                                        autocomplete="off"
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control" style="height: 28px !important;">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="checkbox" id="traq_invasivoskardex" name="traq_invasivoskardex">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;"
                                                                                        name="fecha_kardex_traq_susp" id="fecha_kardex_traq_susp"
                                                                                        autocomplete="off"
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 

                                                                                <td> 
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" style="height: 30px !important;">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs" style="height: 30px !important;" type="button">
                                                                                               <i class="fa fa-check" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div> 
                                                                                </td> 
                                                                            </tr>

                                                                            <tr>
                                                                                <td>
                                                                                    <label for="tipo" style="margin-top: 6px;"><b>CVC</b></label>
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;"
                                                                                        name="fecha_kardex_la" id="fecha_kardex_la"
                                                                                        autocomplete="off"
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control" style="height: 28px !important;">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="checkbox" id="la_invasivoskardex" name="la_invasivoskardex">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;"
                                                                                        name="fecha_kardex_la_susp" id="fecha_kardex_la_susp"
                                                                                        autocomplete="off"
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 

                                                                                <td> 
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" style="height: 30px !important;">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs" style="height: 30px !important;" type="button">
                                                                                               <i class="fa fa-check" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div> 
                                                                                </td> 
                                                                            </tr>

                                                                            <tr>
                                                                                <td>
                                                                                    <label for="tipo" style="margin-top: 6px;"><b>CVP</b></label>
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;"
                                                                                        name="fecha_kardex_sng" id="fecha_kardex_sng"
                                                                                        autocomplete="off"
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control" style="height: 28px !important;">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="checkbox" id="sng_invasivoskardex" name="sng_invasivoskardex">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;"
                                                                                        name="fecha_kardex_sng_susp" id="fecha_kardex_sng_susp"
                                                                                        autocomplete="off"
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 

                                                                                <td> 
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" style="height: 30px !important;">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs" style="height: 30px !important;" type="button">
                                                                                               <i class="fa fa-check" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div> 
                                                                                </td> 
                                                                            </tr>

                                                                            <tr>
                                                                                <td>
                                                                                    <label for="tipo" style="margin-top: 6px;"><b>L.A</b></label>
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;"
                                                                                        name="fecha_kardex_sf" id="fecha_kardex_sf"
                                                                                        autocomplete="off"
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td>
                                                                                <td> 
                                                                                    <input type="text" class="form-control" style="height: 28px !important;">
                                                                                </td>  
                                                                                <td> 
                                                                                    <input type="checkbox" id="sf_invasivoskardex" name="sf_invasivoskardex">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;"
                                                                                        name="fecha_kardex_sf_susp" id="fecha_kardex_sf_susp"
                                                                                        autocomplete="off"
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 

                                                                                <td> 
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" style="height: 30px !important;">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs" style="height: 30px !important;" type="button">
                                                                                               <i class="fa fa-check" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div> 
                                                                                </td> 
                                                                            </tr>

                                                                            <tr>
                                                                                <td>
                                                                                    <label for="tipo" style="margin-top: 6px;"><b>CAF</b></label>
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp" id="fecha_kardex_cvp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control" style="height: 28px !important;">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="checkbox" id="cvp_invasivoskardex" name="cvp_invasivoskardex">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp_susp" id="fecha_kardex_cvp_susp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" style="height: 30px !important;">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs" style="height: 30px !important;" type="button">
                                                                                               <i class="fa fa-check" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div> 
                                                                                </td> 
                                                                            </tr>

                                                                            <tr>
                                                                                <td>
                                                                                    <label for="tipo" style="margin-top: 6px;"><b>TET/ TQT</b></label>
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp" id="fecha_kardex_cvp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control" style="height: 28px !important;">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="checkbox" id="cvp_invasivoskardex" name="cvp_invasivoskardex">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp_susp" id="fecha_kardex_cvp_susp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" style="height: 30px !important;">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs" style="height: 30px !important;" type="button">
                                                                                               <i class="fa fa-check" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div> 
                                                                                </td> 
                                                                            </tr>

                                                                            <tr>
                                                                                <td>
                                                                                    <label for="tipo" style="margin-top: 6px;"><b>TALLA VESICAL</b></label>
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp" id="fecha_kardex_cvp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control" style="height: 28px !important;">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="checkbox" id="cvp_invasivoskardex" name="cvp_invasivoskardex">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp_susp" id="fecha_kardex_cvp_susp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" style="height: 30px !important;">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs" style="height: 30px !important;" type="button">
                                                                                               <i class="fa fa-check" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div> 
                                                                                </td> 
                                                                            </tr>

                                                                            <tr>
                                                                                <td>
                                                                                    <label for="tipo" style="margin-top: 6px;"><b>SONDA FOLEY</b></label>
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp" id="fecha_kardex_cvp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control" style="height: 28px !important;">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="checkbox" id="cvp_invasivoskardex" name="cvp_invasivoskardex">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp_susp" id="fecha_kardex_cvp_susp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" style="height: 30px !important;">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs" style="height: 30px !important;" type="button">
                                                                                               <i class="fa fa-check" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div> 
                                                                                </td> 
                                                                            </tr>

                                                                            <tr>
                                                                                <td>
                                                                                    <label for="tipo" style="margin-top: 6px;"><b>CARDIO Q</b></label>
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp" id="fecha_kardex_cvp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control" style="height: 28px !important;">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="checkbox" id="cvp_invasivoskardex" name="cvp_invasivoskardex">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp_susp" id="fecha_kardex_cvp_susp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" style="height: 30px !important;">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs" style="height: 30px !important;" type="button">
                                                                                               <i class="fa fa-check" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div> 
                                                                                </td> 
                                                                            </tr>

                                                                            <tr>
                                                                                <td>
                                                                                    <label for="tipo" style="margin-top: 6px;"><b>DREN LAMINAR</b></label>
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp" id="fecha_kardex_cvp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control" style="height: 28px !important;">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="checkbox" id="cvp_invasivoskardex" name="cvp_invasivoskardex">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp_susp" id="fecha_kardex_cvp_susp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" style="height: 30px !important;">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs" style="height: 30px !important;" type="button">
                                                                                               <i class="fa fa-check" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div> 
                                                                                </td> 
                                                                            </tr>

                                                                            <tr>
                                                                                <td>
                                                                                    <label for="tipo" style="margin-top: 6px;"><b>DREN TUBULAR</b></label>
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp" id="fecha_kardex_cvp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control" style="height: 28px !important;">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="checkbox" id="cvp_invasivoskardex" name="cvp_invasivoskardex">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp_susp" id="fecha_kardex_cvp_susp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" style="height: 30px !important;">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs" style="height: 30px !important;" type="button">
                                                                                               <i class="fa fa-check" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div> 
                                                                                </td> 
                                                                            </tr>

                                                                            <tr>
                                                                                <td>
                                                                                    <label for="tipo" style="margin-top: 6px;"><b>COLOSTOMIA</b></label>
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp" id="fecha_kardex_cvp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control" style="height: 28px !important;">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="checkbox" id="cvp_invasivoskardex" name="cvp_invasivoskardex">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp_susp" id="fecha_kardex_cvp_susp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" style="height: 30px !important;">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs" style="height: 30px !important;" type="button">
                                                                                               <i class="fa fa-check" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div> 
                                                                                </td> 
                                                                            </tr>

                                                                            <tr>
                                                                                <td>
                                                                                    <label for="tipo" style="margin-top: 6px;"><b>ILEIOSTOMIA</b></label>
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp" id="fecha_kardex_cvp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control" style="height: 28px !important;">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="checkbox" id="cvp_invasivoskardex" name="cvp_invasivoskardex">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp_susp" id="fecha_kardex_cvp_susp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" style="height: 30px !important;">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs" style="height: 30px !important;" type="button">
                                                                                               <i class="fa fa-check" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div> 
                                                                                </td> 
                                                                            </tr>

                                                                            <tr>
                                                                                <td>
                                                                                    <label for="tipo" style="margin-top: 6px;"><b>YEYUNOSTOMIA</b></label>
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp" id="fecha_kardex_cvp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control" style="height: 28px !important;">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="checkbox" id="cvp_invasivoskardex" name="cvp_invasivoskardex">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp_susp" id="fecha_kardex_cvp_susp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" style="height: 30px !important;">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs" style="height: 30px !important;" type="button">
                                                                                               <i class="fa fa-check" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div> 
                                                                                </td> 
                                                                            </tr>

                                                                            <tr>
                                                                                <td>
                                                                                    <label for="tipo" style="margin-top: 6px;"><b>DREN TORACICO</b></label>
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp" id="fecha_kardex_cvp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control" style="height: 28px !important;">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="checkbox" id="cvp_invasivoskardex" name="cvp_invasivoskardex">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp_susp" id="fecha_kardex_cvp_susp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" style="height: 30px !important;">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs" style="height: 30px !important;" type="button">
                                                                                               <i class="fa fa-check" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div> 
                                                                                </td> 
                                                                            </tr>

                                                                            <tr>
                                                                                <td>
                                                                                    <label for="tipo" style="margin-top: 6px;"><b>NEFROSTOMA</b></label>
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp" id="fecha_kardex_cvp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control" style="height: 28px !important;">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="checkbox" id="cvp_invasivoskardex" name="cvp_invasivoskardex">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp_susp" id="fecha_kardex_cvp_susp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" style="height: 30px !important;">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs" style="height: 30px !important;" type="button">
                                                                                               <i class="fa fa-check" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div> 
                                                                                </td> 
                                                                            </tr>

                                                                            <tr>
                                                                                <td>
                                                                                    <label for="tipo" style="margin-top: 6px;"><b>URETEROSTOMA</b></label>
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp" id="fecha_kardex_cvp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control" style="height: 28px !important;">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="checkbox" id="cvp_invasivoskardex" name="cvp_invasivoskardex">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp_susp" id="fecha_kardex_cvp_susp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" style="height: 30px !important;">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs" style="height: 30px !important;" type="button">
                                                                                               <i class="fa fa-check" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div> 
                                                                                </td> 
                                                                            </tr>

                                                                            <tr>
                                                                                <td>
                                                                                    <label for="tipo" style="margin-top: 6px;"><b>SDVE</b></label>
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp" id="fecha_kardex_cvp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control" style="height: 28px !important;">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="checkbox" id="cvp_invasivoskardex" name="cvp_invasivoskardex">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp_susp" id="fecha_kardex_cvp_susp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" style="height: 30px !important;">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs" style="height: 30px !important;" type="button">
                                                                                               <i class="fa fa-check" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div> 
                                                                                </td> 
                                                                            </tr>

                                                                            <tr>
                                                                                <td>
                                                                                    <label for="tipo" style="margin-top: 6px;"><b>VENTANA PERICAR</b></label>
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp" id="fecha_kardex_cvp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control" style="height: 28px !important;">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="checkbox" id="cvp_invasivoskardex" name="cvp_invasivoskardex">
                                                                                </td> 
                                                                                <td> 
                                                                                    <input type="text" class="form-control datepicker"
                                                                                        style="height: 28px !important;" autocomplete="off"
                                                                                        name="fecha_kardex_cvp_susp" id="fecha_kardex_cvp_susp" 
                                                                                        value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                                                </td> 
                                                                                <td> 
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" style="height: 30px !important;">
                                                                                        <span class="input-group-btn">
                                                                                            <button class="btn btn-success btn-xs" style="height: 30px !important;" type="button">
                                                                                               <i class="fa fa-check" style="padding-left: 7px;padding-right: 7px;"></i>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div> 
                                                                                </td> 
                                                                            </tr>                                   



                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
   
                                                        </div>
                                                    </fieldset>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>