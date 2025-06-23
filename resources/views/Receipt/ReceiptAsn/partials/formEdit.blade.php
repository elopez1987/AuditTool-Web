<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="razonstatusid">Tipo de entrada<span style="color: red">*</span></label>
            <select name="razonstatusid" required class="form-control select2">
                @foreach($reasons as $model)
                    <option value="{{$model->id}}"
                        {{ $receiptasn->razonstatusid == $model->id ? 'selected' : '' }} >
                        {{$model->name}}
                    </option>
                @endforeach
            </select>
            @error('razonstatusid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="stockcategoryid">Origen
                <span style="color: red">*</span>
            </label>
            <select name="stockcategoryid" required class="form-control select2">
                @foreach($categorystocks as $model)
                    <option value="{{$model->id}}"
                        {{ $receiptasn->stockcategoryid == $model->id ? 'selected' : '' }} >
                        {{$model->description}}
                    </option>
                @endforeach
            </select>
            @error('stockcategoryid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="providerid">Proveedor<span style="color: red">*</span></label>
            <select name="providerid" id='providerid' required class="form-control select2">
                @foreach($providers as $model)
                    <option value="{{$model->id}}"
                            @if($model->id == $receiptasn->providerid)
                                selected="selected"
                        @endif>
                        {{$model->name}}
                    </option>
                @endforeach
            </select>
            @error('providerid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="turnid">Turno<span style="color: red">*</span></label>
            <select name="turnid" class="form-control select2">
                @foreach($turns as $model)
                    <option value="{{$model->id}}"
                            @if($model->id == $receiptasn->turnid)
                                selected="selected"
                        @endif>
                        {{$model->name}}
                    </option>
                @endforeach
            </select>
        </div>
        @error('turnid')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="shipmentid">Recibo
                @error("shipmentid")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span>
            </label>
            <input type="text" class="form-control @error('shipmentid') is-invalid @enderror"
                   id="shipmentid" name="shipmentid" required
                   onKeyUp=this.value=this.value.toUpperCase();
                   value="{{$receiptasn->shipmentid}}"
            >
        </div>
        @error('shipmentid')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="billoflading">Factura
                @error("billoflading")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <input type="text" class="form-control @error('billoflading') is-invalid @enderror"
                   id="billoflading" name="billoflading"
                   onKeyUp=this.value=this.value.toUpperCase();
                   value="{{$receiptasn->billoflading}}"
            >
            @error('billoflading')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="oc">Orden de compra
                @error("oc")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <input type="text" class="form-control @error('oc') is-invalid @enderror"
                   id="oc" name="oc"
                   onKeyUp=this.value=this.value.toUpperCase();
                   value="{{$receiptasn->oc}}"
            >
            @error('oc')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="folionumber">Folio de patios
                @error("folionumber")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <input type="text" class="form-control numeric @error('folionumber') is-invalid @enderror"
                   id="folionumber" name="folionumber"
                   value="{{$receiptasn->folionumber}}"
            >
            @error('folionumber')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="carrierid">Linea de transporte <span style="color: red">*</span></label>
            <select name="carrierid" required class="form-control select2">
                @foreach($carriers as $model)
                    <option value="{{$model->id}}"
                            @if($model->id == $receiptasn->carrierid)
                                selected="selected"
                        @endif>
                        {{$model->name}}
                    </option>
                @endforeach
            </select>
            @error('carrierid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12 ">
        <div class="form-group">
            <label for="addasndate">F. Cita de Descarga
                <span style="color: red">*</span>
            </label>
            <input type="text" class="form-control date @error('addasndate') is-invalid @enderror"
                    id="addasndate" name="addasndate" required
                   value="{{$receiptasn->addasndate}}"
            >
            @error('addasndate')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>


</div>

