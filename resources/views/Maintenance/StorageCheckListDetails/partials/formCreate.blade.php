<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Nombre del check list</label>
            <input type="text" value="{{$checkList->name}}" readonly class="form-control" id="name" name="name">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="description">Punto de revisión</label>
            <input type="text" class="form-control" required id="description" name="description"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="typeid">Nivel critico <span style="color: red">*</span></label>
            <select name="typeid" required class="form-control select2">
                @foreach($types as $model)
                    <option value="{{$model->statusid}}"> {{$model->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="statuslistid">Tipo de validación <span style="color: red">*</span></label>
            <select name="statuslistid" id="statuslistid" required class="form-control select2">
                <option value="0" selected>Seleccione una opción</option>
                <option value="ON">ENCENDIDO</option>
                <option value="OFF">APAGADO</option>
            </select>
        </div>
    </div>
</div>

