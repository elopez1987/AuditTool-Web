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
    <x-text-input
            name="type"
            label="Tipo de check list"
            uppercase
            required
            :value="$storagechecklist->type"
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
