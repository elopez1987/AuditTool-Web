<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerid">Centros de distribución <span style="color: red">*</span></label>
            <select name="centerid" id="centerid"
                    class="form-control select2 @error('centerid') is-invalid @enderror">
                <option value="">-- Seleccionar centro --</option>
                @foreach($centers as $center)
                    <option value="{{$center->centerid}}" {{ old('centerid') == $center->centerid ? 'selected' : '' }}> {{$center->name}} </option>
                @endforeach
            </select>
            @error('centerid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="warehouseid">Bodega <span style="color: red">*</span></label>
            <select name="warehouseid" id="warehouseid" class="form-control select2 @error('warehouseid') is-invalid @enderror">
                <option value="">-- Almacenes --</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{$warehouse->warehouseid}}" {{ old('warehouseid') == $warehouse->warehouseid ? 'selected' : '' }}> {{$warehouse->name}} </option>
                @endforeach
            </select>
            @error('warehouseid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Nombre <span style="color: red">*</span></label>
            <input type="text" class="form-control" required id="name" name="name"
                   aria-describedby="name" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('name')}}">
        </div>
    </div>
</div>


