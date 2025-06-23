<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerid">Centro de distribución <span style="color: red">*</span></label>
            <select name="centerid" required class="form-control select2">
                @foreach($centers as $center)
                    <option value="{{$center->centerid}}"> {{$center->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="warehouseid">Bodega <span style="color: red">*</span></label>
            <select name="warehouseid" required class="form-control select2">
                @foreach($warehouses as $warehouse)
                    <option value="{{$warehouse->warehouseid}}"> {{$warehouse->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="statustypeid">Tipo de conteo <span style="color: red">*</span></label>
            <select name="statustypeid" required class="form-control select2">
                @foreach($types as $type)
                    <option value="{{$type->statusid}}"> {{$type->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="description">Descripción del conteo <span style="color: red">*</span></label>
            <input type="text" class="form-control" required id="description" name="description" maxlength="100"
                   aria-describedby="description" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('description')}}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="inventorytype">Tipo inventario   <span style="color: red">*</span></label>
            <select name="inventorytype" required class="form-control select2">
                <option value="DRON">Conteo por Dron</option>
                <option value="APP">Conteo por App</option>
            </select>
        </div>
    </div>

</div>


