<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerid">Centros de distribución <span style="color: red">*</span></label>
            <select name="centerid" id="centerid" class="form-control select2">
                @foreach($centers as $center)
                    <option value="{{$center->centerid}}"> {{$center->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="warehouseid">Bodega <span style="color: red">*</span></label>
            <select name="warehouseid" id="warehouseid" required class="form-control select2">
                @foreach($warehouses as $model)
                    <option value="{{$model->warehouseid}}"> {{$model->name}} </option>
                @endforeach
            </select>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Nombre
                @error("name")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   required id="name" name="name" maxlength="50"
                   aria-describedby="name" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('name')}}"
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="capacity">Capacidad
                @error("capacity")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control numeric @error('capacity') is-invalid @enderror"
                   required id="capacity" name="capacity" maxlength="3"
                   aria-describedby="capacity" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('capacity')}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="zoneid">Tipo de Zona<span style="color: red">*</span></label>
            <select name="zoneid" id="zoneid" required class="form-control select2">
                <option value="" selected>Seleccione una opción</option>
                <option value="CO">CORTINAS</option>
                <option value="ST">PRECARGA</option>
            </select>
        </div>
    </div>
</div>

