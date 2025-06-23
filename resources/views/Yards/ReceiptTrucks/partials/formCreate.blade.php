<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerid">Centros de distribución <span style="color: red">*</span></label>
            <select name="centerid" id="centerid" required class="form-control select2">
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
            <select name="warehouseid" id="warehouseid" required class="form-control select2">
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
            <label for="barcodeoperator">Código de Barra Operador<span style="color: red">*</span></label>
            <div class="input-group">
                <input type="text" class="form-control numeric" id="barcodeoperator" value="{{ old('barcodeoperator') }}"
                       name="barcodeoperator" maxlength="15" required>
                <div class="input-group-append">
                    <button class="btn btn-info btn-xs" id="searchOperator" type="button"><i class="ti ti-search"></i>
                    </button>
                </div>
                @error('barcodeoperator')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="operatorname">Nombre de operador<span style="color: red">*</span></label>
            <input type="text" class="form-control" required id="operatorname" name="operatorname"
                   onKeyUp=this.value=this.value.toUpperCase() maxlength="100"
                   value="{{ old('operatorname') }}"
            >
            @error('operatorname')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="drivernumber">Número de licencia<span style="color: red">*</span></label>
            <input type="text" class="form-control" required id="drivernumber" name="drivernumber"
                   onKeyUp=this.value=this.value.toUpperCase() maxlength="50"
                   value="{{ old('drivernumber') }}"
            >
            @error('drivernumber')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="phone">Número de celular<span style="color: red">*</span></label>
            <input type="text" class="form-control numeric" required maxlength="10"  id="phone"
                   name="phone"
                   onKeyUp=this.value=this.value.toUpperCase()
                   value="{{ old('phone') }}"
            >
            @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
<hr class="m-t-0">
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="barcodetruck">Código de barra de tracto<span style="color: red">*</span></label>
            <div class="input-group">
                <input type="text" class="form-control numeric" id="barcodetruck" value="{{ old('barcodetruck') }}"
                       name="barcodetruck" maxlength="15" required>
                <div class="input-group-append">
                    <button class="btn btn-info btn-xs" id="searchTruck" type="button"><i class="ti ti-search"></i>
                    </button>
                </div>
                @error('barcodetruck')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="trucknumber">Placa de tracto<span style="color: red">*</span></label>
            <input type="text" class="form-control" required id="trucknumber" name="trucknumber"
                   onKeyUp=this.value=this.value.toUpperCase() maxlength="10"
                   value="{{ old('trucknumber') }}"
            >
            @error('trucknumber')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="econumber">Eco. De tracto<span style="color: red">*</span></label>
            <input type="text" class="form-control" maxlength="19" id="econumber" name="econumber"
                   onKeyUp=this.value=this.value.toUpperCase()
                   value="{{ old('econumber') }}"
            >
            @error('econumber')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="carrierid">Linea de transporte<span style="color: red">*</span></label>
            <select name="carrierid" class="form-control select2">
                @foreach($carriers as $model)
                    <option value="{{$model->id}}" {{ old('carrierid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                @endforeach
            </select>
            @error('carrierid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

