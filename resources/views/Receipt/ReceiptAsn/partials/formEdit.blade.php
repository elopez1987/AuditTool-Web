<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="razonstatusid">{{ ui_label('razonstatusid_receiptasn_edit','Tipo de entrada ') }} <span style="color: red">*</span></label>
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
            <label for="stockcategoryid"> {{ ui_label('stockcategoryid_receiptasn_edit','Origen') }}
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
            <label for="providerid"> {{ ui_label('providerid_receiptasn_edit','Ruta') }} <span style="color: red">*</span></label>
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

</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="shipmentid"> {{ ui_label('shipmentid_receiptasn_edit','Número de recibo') }}
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
            <label for="billoflading"> {{ ui_label('billoflading_receiptasn_edit','Factura') }}
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

</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="folionumber"> {{ ui_label('folionumber_receiptasn_edit','Folio de patios') }}
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
    <div class="col-lg-2 col-md-6 col-sm-12 ">
        <div class="form-group">
            <label for="addasndate">{{ ui_label('addasndate_receiptasn_edit','F. Cita de Descarga ') }}
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

