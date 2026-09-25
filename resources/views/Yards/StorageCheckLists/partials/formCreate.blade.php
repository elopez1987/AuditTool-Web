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
</div>

<div class="row">
    <x-select2
            name="type"
            label="Tipo de check list"
            :required="true"
            :options="[
                    ['id' => 'AS', 'name' => 'CHECK LIST DE AUTORIZACION DE SALIDA'],
                    ['id' => 'QI', 'name' => 'CHECK LIST DE CALIDAD'],
                    ['id' => 'IN', 'name' => 'CHECK LIST DE ENTRADA'],
                    ['id' => 'OUT', 'name' => 'CHECK LIST DE SALIDA'],
                    ['id' => 'REC', 'name' => '	CHECK LIST PROCESO DE RECIBO'],
                ]"
    />
    <x-select2
            name="auxiliary01"
            label="Origen"
            :required="true"
            :options="[
                    ['id' => 'NAC', 'name' => 'NACIONAL'],
                    ['id' => 'EXP', 'name' => 'EXPORTACIÓN'],
                    ['id' => 'IMP', 'name' => 'IMPORTACION'],
                ]"
    />
    <x-select2
            name="auxiliary02"
            label="Tipo de check list"
            :required="true"
            :options="[
                    ['id' => 'ESTANDAR', 'name' => 'ESTANDAR'],
                    ['id' => 'CTPAT', 'name' => 'CTPAT'],
                    ['id' => 'PUNTAJE', 'name' => 'PUNTAJE'],
                ]"
    />
</div>

<div class="row">
    <x-select2
            name="auxiliary03"
            label="Concepto de entrada"
            :options="$concetps"
            valueField="id"
            textField="name"
            :required="true"
    />
    <x-select2
            name="auxiliary04"
            label="¿Check list por defecto?"
            :required="true"
            :options="[
                    ['id' => 'N', 'name' => 'NO'],
                    ['id' => 'S', 'name' => 'SI'],
                ]"
    />
    <x-text-input
            name="auxiliary05"
            label="ID de tipo de unidad"
            uppercase
    />
</div>
