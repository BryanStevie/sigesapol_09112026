<style>
    /* Asegurar que el autocomplete se muestre por encima de la modal */
    .ui-autocomplete {
        z-index: 99999 !important;
        max-height: 200px;
        overflow-y: auto;
        overflow-x: hidden;
    }
    
    /* Estilo para los items del autocomplete */
    .ui-menu-item {
        padding: 5px 10px;
        white-space: nowrap;
    }
    
    /* Resaltar texto coincidente */
    .ui-state-active, .ui-widget-content .ui-state-active {
        background: #0073aa;
        color: white;
    }
</style>


<div class="panel panel-default">
    <div class="panel-heading bg-lightgreen">
        <strong>DIAGNOSTICO</strong>
    </div>
    <div class="panel-body">

        <div class="row" style="margin-bottom: 10px;">
            <div class="col-md-5">
                <label>Diagnóstico</label>
                <input type="text" id="inputDiagnostico" class="form-control" style="width: 100%;" placeholder="Buscar diagnóstico...">
                <input type="hidden" id="diagnosticoId" class="form-control" style="width: 100%;" >
                
            </div>
            <div class="col-md-2">
                <label>Tipo Diagnóstico</label>
                <select id="tipoDiagnostico" class="form-control" style="width: 100%;">
                    <option value="">- Seleccionar -</option>
                    <option value="1">Presuntivo</option>
                    <option value="2">Definitivo</option>
                    <option value="3">Repetitivo</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>OBS./LAB.</label>
                <input type="text" id="observacionDiagnostico" class="form-control" style="width: 100%;" placeholder="Observaciones">
            </div>

            <div class="col-md-2 " style="margin-top: 20px;">
                <button type="button" class="btn btn-success" id="btnAgregarDiagnostico">
                    <i class="fa fa-plus"></i> Agregar
                </button>
            </div>
        </div>

        <table class="table table-bordered table-condensed" id="tablaDiagnostico">
            <thead>
                <tr>
                    <th>Diagnóstico</th>
                    <th>Tipo</th>
                    <th>Observación</th>
                    <th style="width: 40px" id="thDeleteDiag" >Eliminar</th>
                </tr>
            </thead>
            <tbody>
                {{-- Diagnósticos agregados dinámicamente vía JS --}}
            </tbody>
        </table>
    </div>
</div>
