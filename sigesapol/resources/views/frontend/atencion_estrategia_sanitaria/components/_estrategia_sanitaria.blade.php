<style>
  .ui-autocomplete {
      z-index: 99999 !important;
      background-color: #fff;
      max-height: 250px;
      overflow-y: auto;
      overflow-x: hidden;
      border: 1px solid #ccc;
      position: absolute;
  }
</style>
<script src="{{ asset('js/programacion/estrategia_sanitaria.js') }}"></script>
<div class="panel panel-default">
    <div class="panel-heading bg-lightgreen">
        <strong>LISTADO DE ESTRATEGIAS SANITARIAS</strong>
    </div>
    <div class="panel-body">
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-md-5">
                <label>TIPO/CPT/ESTRATEGIA SANITARIA</label>
                <input type="text" id="inputEstrategia" class="form-control" style="width: 100%;" placeholder="Buscar estrategia sanitaria...">
                <input type="hidden" id="estrategiaId" class="form-control" style="width: 100%;" >
            </div>
            
            <div class="col-md-3">
                <label>OBS./LAB.</label>
                <input type="text" id="observacionEstrategia" class="form-control" style="width: 100%;" placeholder="Observaciones">
            </div>
            <div class="col-md-2" style="margin-bottom: 10px;">
                <button type="button" class="btn btn-success" id="btnAgregarEstrategia">
                    <i class="fa fa-plus"></i> Agregar
                </button>
            </div>
        </div>

        <table class="table table-bordered table-condensed" id="tablaEstrategias">
            <thead>
                <tr>
                    <th>Estrategia</th>
                    
                    <th>Observación</th>
                    <th style="width: 40px" id="thDeleteEstrategia">Eliminar</th>
                </tr>
            </thead>
            <tbody>
                {{-- Estrategias agregadas dinámicamente vía JS --}}
            </tbody>
        </table>
    </div>
</div>
