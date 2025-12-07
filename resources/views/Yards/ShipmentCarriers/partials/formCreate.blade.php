<div class="row">
    <div class="col-lg-2 col-md-6 col-xs-12">
        <div class="form-group">
            <label for="centerid">Centros de distribución <span style="color: red">*</span></label>
            <select name="centerid" id="centerid" class="form-control select2 @error('centerid') is-invalid @enderror">
                @foreach($centers as $center)
                    <option value="{{$center->centerid}}" {{ old('centerid') == $center->centerid ? 'selected' : '' }}> {{$center->name}} </option>
                @endforeach
            </select>
            @error('centerid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-xs-12">
        <div class="form-group">
            <label for="warehouseid">Bodega <span style="color: red">*</span></label>
            <select name="warehouseid" id="warehouseid" class="form-control select2 @error('warehouseid') is-invalid @enderror">
                @foreach($warehouses as $model)
                    <option value="{{$model->warehouseid}}" {{ old('warehouseid') == $model->warehouseid ? 'selected' : '' }}> {{$model->name}} </option>
                @endforeach
            </select>
            @error('warehouseid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-xs-12">
        <div class="form-group">
            <label for="name">Nombre de la línea de transporte
                <span style="color: red">*</span>
            </label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   required id="name" name="name" maxlength="99"
                   onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('name')}}"
            >
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-xs-12">
        <div class="form-group">
            <label for="carrierid">Clave de línea de transporte
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('carrierid') is-invalid @enderror"
                   required id="carrierid" name="carrierid" maxlength="49"
                   value="{{old('carrierid')}}"
            >
            @error('carrierid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
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
            <input type="text" class="form-control @error('auxiliary01') is-invalid @enderror"
                   id="auxiliary01" name="auxiliary01" maxlength="100"
                   value="{{old('auxiliary01')}}">
            @error('auxiliary01')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary02">Auxiliar 02 </label>
            <input type="text" class="form-control @error('auxiliary02') is-invalid @enderror" id="auxiliary02" name="auxiliary02" maxlength="100"
                   value="{{old('auxiliary02')}}">
            @error('auxiliary02')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary03">Auxiliar 03 </label>
            <input type="text" class="form-control @error('auxiliary03') is-invalid @enderror" id="auxiliary03" name="auxiliary03" maxlength="100"
                 value="{{old('auxiliary03')}}">
            @error('auxiliary03')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
