    <div class="container">
        <div class="row mt-50 mb-50" >
            <div class="col-xs-1"></div>
            <div class="col-xs-10">
                <p class="fs-large">Teclee su número de documento y presione "[SIGUIENTE]".</p>
                <p class="fs-large">Enter your document number and press "[NEXT]"</p>
            </div>
        </div>
                
        <div class="row">
            <div class="col-xs-offset-1 col-xs-5 ">
                <div class="form-group">
                    <label for="" class="control-label">DOCUMENTO / DOCUMENT</label>
                    <select name="tipo_documento" id="tipo_documento" class="form-control" onchange="maxlength_doc_usu()">
                        <option value="1">DNI</option>
                        <option value="2">PAS</option>
                        <option value="3">CE</option>
                        <option value="4">DIE</option>
                    </select>
                </div>
            </div>

            <div class="col-xs-5">
                <div class="form-group">
                    <label for="" class="control-label">NÚMERO / NUMBER</label>
                    <input type="text" name="nro_documento" class="form-control" id="nro_documento" maxlength="8">
                </div>
            </div>
        </div>
        <div class="row mt-50">
            <p class="fs-large" style="color:red" id="mensaje"></p>
        </div>
        <div class="row">
            <div id="number-keyboard">
                <table>
                    <tr>
                        <td><button type="button" class="btn btn-success" onclick="push('7')"> 7 </button></td>
                        <td><button type="button" class="btn btn-success" onclick="push('8')"> 8 </button></td>
                        <td><button type="button" class="btn btn-success" onclick="push('9')"> 9 </button></td>
                    </tr>
                    <tr>
                        <td><button type="button" class="btn btn-success" onclick="push('4')"> 4 </button></td>
                        <td><button type="button" class="btn btn-success" onclick="push('5')"> 5 </button></td>
                        <td><button type="button" class="btn btn-success" onclick="push('6')"> 6 </button></td>
                    </tr>
                    <tr>
                        <td><button type="button" class="btn btn-success" onclick="push('1')"> 1 </button></td>
                        <td><button type="button" class="btn btn-success" onclick="push('2')"> 2 </button></td>
                        <td><button type="button" class="btn btn-success" onclick="push('3')"> 3 </button></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><button type="button" class="btn btn-success" onclick="push('0')"> 0 </button></td>
                        <td><button type="button" class="btn btn-success" onclick="push('reset')"><i class="fa fa-arrow-left"></i></button></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row mt-150">
            <div class="col-xs-offset-1 col-xs-10 ">
                <button type="button" class="btn btn-info" onclick="ir_inicio()">ATRAS / [BACK]</button>
                <button type="button" class="btn btn-info pull-right" onclick="siguiente()">SIGUIENTE / [NEXT]</button>
            </div>
        </div>
    </div>