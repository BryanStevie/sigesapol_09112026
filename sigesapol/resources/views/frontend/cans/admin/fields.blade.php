<div class="form-group col-sm-12">
    <!-- Nombre -->
    <div class="form-group col-sm-3">
        {!! Form::label('nombre_can', 'Nombre CAN:') !!}
        {!! Form::text('nombre_can', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-2">
        {!! Form::label('tiempo', 'Tiempo:') !!}
        <select class="form-control select2" id="tiempo" name="tiempo"
          tabindex="5" style="width: 100%">
          <option value="1" <?php if($tiempo==1) echo "selected"; ?> >1 años</option>
          <option value="2" <?php if($tiempo==2) echo "selected"; ?> >2 años</option>
          <option value="3" <?php if($tiempo==3) echo "selected"; ?> >3 años</option>
        </select>
    </div>
</div>

<div class="form-group col-sm-12">
    <button type="submit" value="Guardar" class="btn btn-success">Guardar <i class="fa fa-save"></i></button>
    <a href="{!! route('cans.index') !!}" class="btn btn-danger">Cancelar <i class="glyphicon glyphicon-remove"></i></a>
    
</div>

