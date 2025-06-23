<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerid">Centro de distribución
                <span style="color: red">*</span>
            </label>
            <select name="centerid" class="form-control select2 @error('centerid') is-invalid @enderror" required>
                @foreach($centers as $model)
                    <option value="{{$model->centerid}}"
                        {{ $storageAreas->centerid == $model->centerid ? 'selected' : '' }} >
                        {{$model->name}}
                    </option>
                @endforeach
            </select>
            @error('centerid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="warehouseid">Centro de distribución
                <span style="color: red">*</span>
            </label>
            <select name="warehouseid" class="form-control select2 @error('warehouseid') is-invalid @enderror" required>
                @foreach($warehouses as $model)
                    <option value="{{$model->warehouseid}}"
                        {{ $storageAreas->warehouseid == $model->warehouseid ? 'selected' : '' }} >
                        {{$model->name}}
                    </option>
                @endforeach
            </select>
            @error('warehouseid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Nombre
                @error("name")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   required id="name" name="name"
                   aria-describedby="name" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{$storageAreas->name}}"
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary01">Auxiliar 01
                @error("auxiliary01")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <input type="text" class="form-control @error('auxiliary01') is-invalid @enderror" id="auxiliary01"
                   name="auxiliary01" maxlength="100" value="{{$storageAreas->auxiliary01}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary02">Auxiliar 02
                @error("auxiliary02")
                <span class="message-error">{{$message}}</span>
                @enderror</label>
            <input type="text" class="form-control @error('auxiliary02') is-invalid @enderror" id="auxiliary02"
                   name="auxiliary02" maxlength="100" value="{{$storageAreas->auxiliary02}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary03">Auxiliar 03
                @error("auxiliary03")
                <span class="message-error">{{$message}}</span>
                @enderror</label>
            <input type="text" class="form-control @error('auxiliary03') is-invalid @enderror" id="auxiliary03"
                   name="auxiliary03" maxlength="100" value="{{$storageAreas->auxiliary03}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="statusid">Estatus
                <span style="color: red">*</span>
            </label>
            <select name="statusid" class="form-control select2 @error('statusid') is-invalid @enderror" required>
                @foreach($status as $model)
                    <option value="{{$model->statusid}}"
                        {{ $storageAreas->statusid == $model->statusid ? 'selected' : '' }} >
                        {{$model->name}}
                    </option>
                @endforeach
            </select>
            @error('statusid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
