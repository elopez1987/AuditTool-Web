<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Centro de distribución</label>
            <input type="text" class="form-control " readonly value="{{$storagechecklist->center ? $storagechecklist->center->name : ''}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="warehouseid">Bodega <span style="color: red">*</span></label>
            <select required name="warehouseid" id="warehouseid" class="form-control select2">
                @foreach($warehouses as $model)
                    <option value="{{$model->warehouseid}}"
                            @if($model->warehouseid == $storagechecklist->warehouseid)
                                selected="selected"
                            @endif>
                        {{$model->name}}
                    </option>
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
                   value="{{$storagechecklist->name}}"
            >
        </div>
    </div>
</div>

<div class="row">
    <x-select2
            name="type"
            label="Tipo de check list"
            :required="true"
            :selected="$storagechecklist->type"
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
            :selected="$storagechecklist->auxiliary01"
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
            :selected="$storagechecklist->auxiliary02"
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
            :selected="$storagechecklist->auxiliary03"
            textField="name"
            :required="true"
    />
    <x-select2
            name="auxiliary04"
            label="¿Check list por defecto?"
            :required="true"
            :selected="$storagechecklist->auxiliary04"
            :options="[
                    ['id' => 'N', 'name' => 'NO'],
                    ['id' => 'S', 'name' => 'SI'],
                ]"
    />
    <x-text-input
            name="auxiliary05"
            label="ID de tipo de unidad"
            :value="$storagechecklist->auxiliary05"
            uppercase
    />
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="statusid">Estatus <span style="color: red">*</span></label>
            <select name="statusid" class="form-control select2">
                @foreach($status as $statu)
                    <option value="{{$statu->statusid}}"
                            @if($statu->statusid == $storagechecklist->statusid)
                                selected="selected"
                            @endif>
                        {{$statu->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
