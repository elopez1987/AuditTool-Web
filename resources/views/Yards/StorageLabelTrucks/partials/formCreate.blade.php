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
            <select name="warehouseid" id="warehouseid" class="form-control select2">
                @foreach($warehouses as $warehouse)
                    <option value="{{$warehouse->warehouseid}}"> {{$warehouse->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="typeid">Tipo de registro<span style="color: red">*</span></label>
            <select name="typeid" id="typeid" class="form-control select2">
                <option value="" selected>Seleccione una opción</option>
                <option value="BX">CAJA</option>
                <option value="TR">TRACTO</option>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="carrierid">Linea de transporte<span style="color: red">*</span></label>
            <select name="carrierid" class="form-control select2">
                @foreach($carriers as $model)
                    <option value="{{$model->id}}"> {{$model->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="boxnumber">Placa de caja
                @error("boxnumber")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('boxnumber') is-invalid @enderror"
                   required id="boxnumber" name="boxnumber"
                   aria-describedby="boxnumber" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('boxnumber')}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="econumber">Número de caja
                @error("econumber")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('econumber') is-invalid @enderror"
                   required id="econumber" name="econumber" maxlength="19"
                   aria-describedby="econumber" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('econumber')}}"
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div id="truckdiv">
            <div class="form-group">
                <label for="truckid">Tipo<span style="color: red">*</span></label>
                <select name="truckid" id="truckid" class="form-control select2">
                    @foreach($trucks as $model)
                        <option value="{{$model->id}}"> {{$model->name}} </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
