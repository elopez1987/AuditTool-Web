<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerid">Centros de distribución <span style="color: red">*</span></label>
            <select name="centerid" id="centerid" required class="form-control select2">
                @foreach($centers as $center)
                    <option value="{{$center->centerid}}" {{ old('centerid') == $center->centerid ? 'selected' : '' }} > {{$center->name}} </option>
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
            <select name="warehouseid" required id="warehouseid" class="form-control select2">
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
            <label for="operatorid">Nombre de Operador<span style="color: red">*</span></label>
            <select name="operatorid" id='operatorid' required class="form-control select2">
                <option value="" selected>Seleccione una opción</option>
                @foreach($operators as $model)
                    <option value="{{$model->id}}" {{ old('operatorid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                @endforeach
            </select>
            @error('operatorid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="boxid">Caja<span style="color: red">*</span></label>
            <select name="boxid" id='boxid' required class="form-control select2">
                <option value="" selected>Seleccione una opción</option>
                @foreach($boxes as $model)
                    <option value="{{$model->id}}" {{ old('boxid') == $model->id ? 'selected' : '' }}> {{$model->boxnumber}} </option>
                @endforeach
            </select>
            @error('boxid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="truckid">Tracto<span style="color: red">*</span></label>
            <select name="truckid" id='truckid' required class="form-control select2">
                <option value="" selected>Seleccione una opción</option>
                @foreach($trucks as $model)
                    <option value="{{$model->id}}" {{ old('truckid') == $model->id ? 'selected' : '' }}> {{$model->boxnumber}} </option>
                @endforeach
            </select>
            @error('truckid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="datestart">F.Inicio</label>
            <input type="text" class="form-control date" required id="datestart" name="datestart">
            @error('datestart')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="datefinish">F.Final</label>
            <input type="text" class="form-control date" required id="datefinish" name="datefinish">
            @error('datefinish')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
<div class="row mb-4 mt-2">
    <div class="col-lg-6">
        <span class="font-12 text-danger"> <i class="ti ti-alert"></i> El código de acceso solo será válido por 24 horas a partir de la fecha de inicio selecionada.</span>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="shipmentid">Embarque</label>
            <input type="text" class="form-control numeric" id="shipmentid" name="shipmentid" onKeyUp=this.value=this.value.toUpperCase()>
            @error('shipmentid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="confirmationnumber">Número de confirmación</label>
            <input type="text" class="form-control" id="confirmationnumber" name="confirmationnumber" onKeyUp=this.value=this.value.toUpperCase()>
            @error('confirmationnumber')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>


