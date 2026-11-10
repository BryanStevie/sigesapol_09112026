<style>
body {
  background: #555;
  background-image: url('http://subtlepatterns.subtlepatterns.netdna-cdn.com/patterns/debut_dark.png'); 
}

h1, h2 {
  color: grey;
  font: 30px Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  text-shadow: 0px 1px black;
  text-align: center;
  margin: 10px 0 0;
}

.ondisplay{
  text-align:center;
  padding:0;
}

.ondisplay section{
  width:100px;
  height:100px;
  background: #555;
  display:inline-block;
  border: 1px solid gray;
  text-align: center;
  margin-top:5px;
  border-radius:5px;
  box-shadow: 0 1px 4px
     rgba(0, 0, 0, 0.3), 0 0 40px
     rgba(0, 0, 0, 0.1) inset;
}

.ondisplay section::before {
  content:'click it'; 
  color: #bbb;
  font: 15px Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  text-shadow: 0px 1px black;
}

input[type=checkbox] {
  visibility: hidden;
}

/* SQUARED ONE */
.squaredOne {
  width: 28px;
  height: 28px;
  background: #fcfff4;

  background: -webkit-linear-gradient(top, #fcfff4 0%, #dfe5d7 40%, #b3bead 100%);
  background: -moz-linear-gradient(top, #fcfff4 0%, #dfe5d7 40%, #b3bead 100%);
  background: -o-linear-gradient(top, #fcfff4 0%, #dfe5d7 40%, #b3bead 100%);
  background: -ms-linear-gradient(top, #fcfff4 0%, #dfe5d7 40%, #b3bead 100%);
  background: linear-gradient(top, #fcfff4 0%, #dfe5d7 40%, #b3bead 100%);
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fcfff4', endColorstr='#b3bead',GradientType=0 );
  margin: 20px auto;
  -webkit-box-shadow: inset 0px 1px 1px white, 0px 1px 3px rgba(0,0,0,0.5);
  -moz-box-shadow: inset 0px 1px 1px white, 0px 1px 3px rgba(0,0,0,0.5);
  box-shadow: inset 0px 1px 1px white, 0px 1px 3px rgba(0,0,0,0.5);
  position: relative;
}

.squaredOne label {
  cursor: pointer;
  position: absolute;
  width: 20px;
  height: 20px;
  left: 4px;
  top: 4px;

  -webkit-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.5), 0px 1px 0px rgba(255,255,255,1);
  -moz-box-shadow: inset 0px 1px 1px rgba(0,0,0,0.5), 0px 1px 0px rgba(255,255,255,1);
  box-shadow: inset 0px 1px 1px rgba(0,0,0,0.5), 0px 1px 0px rgba(255,255,255,1);

  background: -webkit-linear-gradient(top, #222 0%, #45484d 100%);
  background: -moz-linear-gradient(top, #222 0%, #45484d 100%);
  background: -o-linear-gradient(top, #222 0%, #45484d 100%);
  background: -ms-linear-gradient(top, #222 0%, #45484d 100%);
  background: linear-gradient(top, #222 0%, #45484d 100%);
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#222', endColorstr='#45484d',GradientType=0 );
}

.squaredOne label:after {
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
  filter: alpha(opacity=0);
  opacity: 0;
  content: '';
  position: absolute;
  width: 16px;
  height: 16px;
  background: #00bf00;

  background: -webkit-linear-gradient(top, #00bf00 0%, #009400 100%);
  background: -moz-linear-gradient(top, #00bf00 0%, #009400 100%);
  background: -o-linear-gradient(top, #00bf00 0%, #009400 100%);
  background: -ms-linear-gradient(top, #00bf00 0%, #009400 100%);
  background: linear-gradient(top, #00bf00 0%, #009400 100%);

  top: 2px;
  left: 2px;

  -webkit-box-shadow: inset 0px 1px 1px white, 0px 1px 3px rgba(0,0,0,0.5);
  -moz-box-shadow: inset 0px 1px 1px white, 0px 1px 3px rgba(0,0,0,0.5);
  box-shadow: inset 0px 1px 1px white, 0px 1px 3px rgba(0,0,0,0.5);
}

.squaredOne label:hover::after {
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=30)";
  filter: alpha(opacity=30);
  opacity: 0.3;
}

.squaredOne input[type=checkbox]:checked + label:after {
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
  filter: alpha(opacity=100);
  opacity: 1;
}
</style>
                  
                  <div class="panel panel-info ">
                        <div class="panel-heading">
                            <h3 class="panel-title">ODONTOGRAMA</h3>
                        </div>
                        <div class="panel-body "> <!-- .col-md-12 -->
                            <!--div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <div class="col-sm-6">

                                                <div class="col-sm-3">18<button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3">17<button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3">16<button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3">15<button type="button" class="btn btn-success">D</button></div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="col-sm-3">14<button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3">13<button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3">12<button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3">11<button type="button" class="btn btn-success">D</button></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="col-sm-6">
                                                <div class="col-sm-3">21<button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3">22<button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3">23<button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3">24<button type="button" class="btn btn-success">D</button></div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="col-sm-3">25<button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3">26<button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3">27<button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3">28<button type="button" class="btn btn-success">D</button></div>
                                            </div>
                                        </div>
                                    </div> 
                                </div--> <!-- .col-md-12 -->
                            </div> <!-- .row -->
                            <br>

                            <div class="row">
                                <div class="col-sm-12" >
                                    <div align='center'>     
                                    <img src="/images/odonto.png" alt="" width="50%">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <!--div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <div class="col-sm-6">
                                                <div class="col-sm-3"><center>48</center><button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3"><center>47</center><button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3"><center>46</center><button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3"><center>45</center><button type="button" class="btn btn-success">D</button></div>
       
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="col-sm-3"><center>44</center><button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3"><center>43</center><button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3"><center>42</center><button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3"><center>41</center><button type="button" class="btn btn-success">D</button></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="col-sm-6">
                                                <div class="col-sm-3"><center>31</center><button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3"><center>32</center><button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3"><center>33</center><button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3"><center>34</center><button type="button" class="btn btn-success">D</button></div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="col-sm-3"><center>35</center><button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3"><center>36</center><button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3"><center>37</center><button type="button" class="btn btn-success">D</button></div>
                                                <div class="col-sm-3"><center>38</center><button type="button" class="btn btn-success">D</button></div>
                                            </div>
                                        </div>
                                    </div> 
                                </div--> <!-- .col-md-12 -->
                            </div> <!-- .row -->
                            <br>
                            <div class="row">
                            <div class="col-md-1">
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">COD. ODONTOGRAMA</label>
                                        <input value="@if(isset($examen)){{$examen->cod_odontograma}}@else{{$ficha->nro_doc_ident}}@endif" @if(isset($examen)) readonly @endif type="text" name="codigo_odontograma" id="codigo_odontograma" class="form-control" placeholder="" tabindex="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">CAPACIDAD MASTICATORIA</label>
                                        <input maxlength="3" value="@if(isset($examen)){{$examen->capacidad_masticatoria}}@endif" @if(isset($examen)) readonly @endif type="text" name="capacidadmasticatoria" id="capacidadmasticatoria" class="form-control" placeholder="" tabindex="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">DESCRIPCIÓN</label>
                                        <textarea name="descripcion_odontograma" id="descripcion_odontograma" class="form-control" @if(isset($examen)) readonly @endif>@if(isset($examen)){{$examen->descripcion_odontograma}}@endif</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>