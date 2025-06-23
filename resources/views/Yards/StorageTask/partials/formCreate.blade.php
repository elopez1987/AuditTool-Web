<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="searchInvoice">Número de Folio</label>
            <div class="input-group">
                <input type="number" class="form-control" id="folionumber" name="folionumber"  required value="{{ old('folionumber') }}">
                <div class="input-group-append">
                    <button class="btn btn-info btn-xs" id="searchInvoice" type="button">Buscar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="barcode">Etiqueta</label>
            <div class="input-group">
                <input type="number" readonly class="form-control" required id="barcode" name="barcode" value="{{ old('barcode') }}">
            </div>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="linea">Línea de Transporte</label>
            <input type="text" class="form-control " id="linea" name="linea" readonly value="{{old('linea')}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="boxnumber">Placa de caja</label>
            <input type="text" class="form-control" id="boxnumber" name="boxnumber" readonly value="{{old('boxnumber')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="containerseal">Número de sello</label>
            <input type="text" class="form-control" readonly id="containerseal" name="containerseal">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="originzoneid">Zona de origen</label>
            <input type="text" class="form-control" readonly id="originzoneid" name="originzoneid">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="originlocationid">Ubicación de origen</label>
            <input type="text" class="form-control" readonly id="originlocationid" name="originlocationid">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="operatorname">Nombre del operador</label>
            <input type="text" class="form-control" readonly id="operatorname" name="operatorname">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="phone">Número de contacto</label>
            <input type="text" class="form-control" readonly id="phone" name="phone">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="econumber">Eco. De caja</label>
            <input type="text" class="form-control" readonly id="econumber" name="econumber">
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="typeid">Enviar tarea a<span style="color: red">*</span></label>
            <select name="typeid" id='typeid' class="form-control select2">
                <option value="0" selected>Seleccione una opción</option>
                <option value="1" {{ old('typeid') == '1'? 'selected' : '' }}>OPERADOR</option>
                <option value="2" {{ old('typeid') == '2' ? 'selected' : '' }}>PATIERO</option>
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div id="patiero" class="hide">
            <div class="form-group">
                <label for="patieroid">Patiero
                    <span style="color: red">*</span>
                    @error("patieroid")
                    <span class="message-error">{{$message}}</span>
                    @enderror
                </label>
                <select name="patieroid" id='patieroid' class="form-control select2 @error('patieroid') is-invalid @enderror">
                    <option value="" selected>Seleccione una opción</option>
                    @foreach($patieros as $model)
                        <option value="{{$model->id}}" {{ old('patieroid') == $model->id ? 'selected' : '' }} > {{$model->name}} </option>
                    @endforeach
                </select>
            </div>
            @error('patieroid')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="zoneid">Zonas<span style="color: red">*</span>
                @error("zoneid")
                <span class="message-error">{{$message}}</span>
                @enderror</label>
            <select name="zoneid" id='zoneid' required class="form-control select2 @error('zoneid') is-invalid @enderror" >
                <option value="" selected>Seleccione una Zona</option>
                @foreach($zones as $model)
                    <option value="{{$model->id}}" {{ old('zoneid') == $model->id ? 'selected' : '' }} > {{$model->name}} </option>
                @endforeach
            </select>
            @error('zoneid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="locationid">Ubicación<span style="color: red">*</span>
                @error("locationid")
                <span class="message-error">{{$message}}</span>
                @enderror</label>
            <select name="locationid" id='locationid' required class="form-control select2 @error('locationid') is-invalid @enderror">
                <option value="" selected>Seleccione una ubicación</option>
                @foreach($locations as $model)
                    <option value="{{$model->id}}" {{ old('locationid') == $model->id ? 'selected' : '' }} > {{$model->name}} </option>
                @endforeach
            </select>
            @error('locationid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
