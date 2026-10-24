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
    <x-text-input
            name="type"
            label="Tipo de check list"
            uppercase
    />
</div>

<div class="row">
    <x-text-input
            name="auxiliary01"
            label="Auxiliar 01"
            uppercase
    />
    <x-text-input
            name="auxiliary02"
            label="Auxiliar 02"
            uppercase
    />
</div>

<div class="row">
    <x-text-input
            name="auxiliary03"
            label="Auxiliar 03"
            uppercase
    />
    <x-text-input
            name="auxiliary04"
            label="Auxiliar 04"
            uppercase
    />
    <x-text-input
            name="auxiliary05"
            label="Auxiliar 05"
            uppercase
    />
</div>
