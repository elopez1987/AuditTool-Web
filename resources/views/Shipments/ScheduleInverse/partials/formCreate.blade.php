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
            <label for="warehouseid">Centros de distribución <span style="color: red">*</span></label>
            <select name="warehouseid" id="warehouseid" class="form-control select2">
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
            <label for="name">Nombre <span style="color: red">*</span></label>
            <input type="text" class="form-control" id="name" name="name"
                   value="{{old('name')}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="startdate">Fecha de inicio</label>
            <input type="text" class="form-control workday @error('startdate') is-invalid @enderror"
                   id="startdate" name="startdate"
                   aria-describedby="startdate" value="{{old('startdate')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="finishdate">Fecha final</label>
            <input type="text" class="form-control workday" id="finishdate" name="finishdate"
                   aria-describedby="finishdate" value="{{old('finishdate')}}">
        </div>
    </div>
</div>


