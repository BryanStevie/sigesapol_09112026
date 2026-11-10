<!-- Modal -->
<div class="modal fade" id="modalListarEscalaMorse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="font-size: 20px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <b id="tituloFormularioListarEscalaMorse"></b>
            </div>

            <div class="modal-body">
                <form id="FormularioListarEscalaMorse" name="FormularioListarEscalaMorse" enctype="multipart/form-data"
                    method="POST">
                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}"> 
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" style="font-size: 11px;"> 
                                <fieldset> 
                                    <div class="book">
                                        <!-- For Next/Prev Controls -->
                                        <input type="checkbox" id="c1">
                                        <input type="checkbox" id="c2">
                                        <input type="checkbox" id="c3">
                                        <!-- Book Cover -->
                                        <div id="cover">
                                          <img src="{{URL::asset('/images/mallampati/mallampati_1.png')}}" alt="Cover">
                                        </div>
                                        <!-- Pages -->
                                        <div class="flip-book">
                                          <!-- Page 1 -->
                                          <div class="flip" id="p1">
                                            <div class="back" style="background-image: url('{{URL::asset('/images/mallampati/mallampati_2.png')}}'); width: 100; height: 100; ">
                                        
                                              <label for="c1" class="back-btn">Before</label>
                                            </div>
                                            <div class="front" style="background-image: url('{{URL::asset('/images/mallampati/mallampati_2.png')}}'); width: 100; height: 100; ">
                                                <tbody>
                                                    <tr>
                                                        <td colspan="1" rowspan="4"
                                                        style="background-color: #f1f1f1; width: 30%;"> 
                                                        <b style="text-align: center">ACTIVIDAD</b>  
                                                        </td>
                                                        <td style="width: 50%;"><b>PLENA</b> </td>
                                                        <td style="width: 10%;">
                                                            <left><input id="A" type="radio" name="A" value="4" size="2"></left>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 50%;"><b>ALGO LIMITADA</b> </td>
                                                        <td style="width: 10%;">
                                                            <left><input id="A" type="radio" name="A" value="3" size="2"></left>
                                                        </td>
                                                    </tr>
        
                                                    <tr>
                                                        <input type="text"
                                                            class="form-control"
                                                            style="height: 30px !important;"
                                                            >
                                                    </tr>
                                                    <tr>
                                                        <input type="text"
                                                            class="form-control"
                                                            style="height: 30px !important;"
                                                            >
                                                    </tr>
                                                    <input type="text"
                                                        class="form-control"
                                                        style="height: 30px !important;"
                                                        >
                                                </tbody>
                                              <label for="c1" class="next-btn">NEXT</label>
                                            </div>
                                          </div>
                                          <!-- Page 2 -->
                                          <div class="flip" id="p2">
                                            <div class="back" style="background-image: url('{{URL::asset('/images/mallampati/mallampati_3.png')}}'); width: 100; height: 100; ">
                                        
                                              <label for="c2" class="back-btn">Before</label>
                                            </div>
                                            <div class="front" style="background-image: url('{{URL::asset('/images/mallampati/mallampati_2.png')}}'); width: 100; height: 100; ">
                                                <tbody>
                                                    <tr>
                                                        <td colspan="1" rowspan="4"
                                                        style="background-color: #f1f1f1; width: 30%;"> 
                                                        <b style="text-align: center">ACTIVIDAD</b>  
                                                        </td>
                                                        <td style="width: 50%;"><b>PLENA</b> </td>
                                                        <td style="width: 10%;">
                                                            <left><input id="A" type="radio" name="A" value="4" size="2"></left>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <input type="text"
                                                            class="form-control"
                                                            style="height: 30px !important;"
                                                            >
                                                    </tr>
        
                                                    <tr>
                                                        <td style="width: 50%;"><b>MUY LIMITADA</b> </td>
                                                        <td style="width: 10%;">
                                                            <left><input id="A" type="radio" name="A" value="2" size="2"></left>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <input type="text"
                                                            class="form-control"
                                                            style="height: 30px !important;"
                                                            >
                                                    </tr>
                                                </tbody>
                                              <label for="c2" class="next-btn">NEXT</label>
                                            </div>
                                          </div>
                                          <!-- Page 3 -->
                                          <div class="flip" id="p3">
                                            <div class="back" style="background-image: url('{{URL::asset('/images/mallampati/mallampati_3.png')}}'); width: 100; height: 100; ">
                                        
                                              <label for="c3" class="back-btn">Before</label>
                                            </div>
                                            <div class="front" style="background-image: url('{{URL::asset('/images/mallampati/mallampati_2.png')}}'); width: 100; height: 100; ">
                                                <tbody>
                                                    <tr>
                                                        <td colspan="1" rowspan="4"
                                                        style="background-color: #f1f1f1; width: 30%;"> 
                                                        <b style="text-align: center">ACTIVIDAD</b>  
                                                        </td>
                                                        <td style="width: 50%;"><b>PLENA</b> </td>
                                                        <td style="width: 10%;">
                                                            <left><input id="A" type="radio" name="A" value="4" size="2"></left>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <input type="text"
                                                            class="form-control"
                                                            style="height: 30px !important;"
                                                            >
                                                    </tr>
        
                                                    <tr>
                                                        <input type="text"
                                                            class="form-control"
                                                            style="height: 30px !important;"
                                                            >
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 50%;"><b>NULA</b> </td>
                                                        <td style="width: 10%;">
                                                            <left><input id="A" type="radio" name="A" value="1" size="2"></left>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                              <label for="c3" class="next-btn">NEXT</label>
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
<style>
 .book {
  display: flex;
}
#cover {
  width: 250px;
  height: 400px;
}
.flip-book {
  width: 250px;
  height: 400px;
  position: relative;
  perspective: 1500px;
}
.flip {
  width: 100%;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
  transform-origin: left;
  transform-style: preserve-3d;
  transform: rotateY(0deg);
  transition: .5s;
  color: #000;
}
.front {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  background-color: #fff;
  box-sizing: border-box;
  padding: 0 13px;
}
.back {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  z-index: 99;
  transform: rotateY(180deg);
  backface-visibility: hidden;
  background-color: #000;
}
.next-btn {
  position: absolute;
  bottom: 13px;
  right: 13px;
  cursor: pointer;
  color: #000;
}
.back-btn {
  position: absolute;
  bottom: 13px;
  right: 13px;
  cursor: pointer;
  color: #fff;
}
#p1 {
  z-index: 3;
}
#p2 {
  z-index: 2;
}
#p3 {
  z-index: 1;
}
#c1:checked ~ .flip-book #p1 {
  transform: rotateY(-180deg);
  z-index: 1;
}
#c2:checked ~ .flip-book #p2 {
  transform: rotateY(-180deg);
  z-index: 2;
}
#c3:checked ~ .flip-book #p3 {
  transform: rotateY(-180deg);
  z-index: 3;
}
</style>