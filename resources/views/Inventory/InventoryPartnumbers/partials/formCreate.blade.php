<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerid">Centros de distribución <span style="color: red">*</span></label>
            <select name="centerid" id="centerid"
                    class="form-control select2 @error('centerid') is-invalid @enderror">
                <option value="">-- Seleccionar centro --</option>
                @foreach($centers as $center)
                    <option value="{{$center->centerid}}" {{ old('centerid') == $center->centerid ? 'selected' : '' }}> {{$center->name}} </option>
                @endforeach
            </select>
            @error('centerid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="warehouseid">Bodega <span style="color: red">*</span></label>
            <select name="warehouseid" id="warehouseid" class="form-control select2 @error('warehouseid') is-invalid @enderror">
                <option value="">-- Almacenes --</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{$warehouse->warehouseid}}" {{ old('warehouseid') == $warehouse->warehouseid ? 'selected' : '' }}> {{$warehouse->name}} </option>
                @endforeach
            </select>
            @error('warehouseid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="prtnum">{{ ui_label('inventorypartnumbers_prtnum_create','Número de parte ') }}
                @error("prtnum")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('prtnum') is-invalid @enderror"
                   required id="prtnum" name="prtnum"
                   aria-describedby="prtnum" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('prtnum')}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="lngdsc"> {{ ui_label('inventorypartnumbers_lngdsc_create','Descripción') }}
                @error("lngdsc")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('lngdsc') is-invalid @enderror"
                   required id="lngdsc" name="lngdsc"
                   aria-describedby="lngdsc" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('lngdsc')}}"
            >
        </div>
    </div>
</div>
<div class="row mt-2">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="category01id"> {{ ui_label('inventorypartnumbers_category01id_create','Categoria de inventario 01 ') }}
                <span style="color: red">*</span></label>
            <select name="category01id" required
                    class="form-control select2 @error('category01id') is-invalid @enderror">
                @foreach($category01 as $model)
                    <option value="{{$model->id}}"> {{$model->description}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="category02id"> {{ ui_label('inventorypartnumbers_category02id_create','Categoria de inventario 02 ') }}
                <span style="color: red">*</span></label>
            <select name="category02id" required
                    class="form-control select2 @error('category02id') is-invalid @enderror">
                @foreach($category02 as $model)
                    <option value="{{$model->id}}"> {{$model->description}} </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">

    @if(ui_visible('inventorypartnumbers_stkuom_create'))
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="stkuom"> {{ ui_label('inventorypartnumbers_stkuom_create','Unidad de medida') }}
                    @if(ui_required('inventorypartnumbers_stkuom_create'))
                        <span style="color: red">*</span>
                    @endif
                </label>
                <select name="stkuom" class="form-control select2 @error('stkuom') is-invalid @enderror"
                        @if(ui_required('inventorypartnumbers_stkuom_create')) required @endif >
                    @foreach($units as $model)
                        <option value="{{$model->unitmeasureid}}"> {{$model->description}} </option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    @if(ui_visible('inventorypartnumbers_shelflife_create'))
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="shelflife"> {{ ui_label('inventorypartnumbers_shelflife_create','Tiempo de vida ') }}
                    @if(ui_required('inventorypartnumbers_shelflife_create'))
                        <span style="color: red">*</span>
                    @endif
                </label>
                <input type="text" class="form-control numeric @error('shelflife') is-invalid @enderror"
                       id="shelflife" name="shelflife" required value="{{old('shelflife')}}"
                       @if(ui_required('inventorypartnumbers_shelflife_create')) required @endif
                >
            </div>
        </div>
    @endif
</div>

<div class="row">
    @if(ui_visible('inventorypartnumbers_untcas_create'))
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="untcas">{{ ui_label('inventorypartnumbers_untcas_create','Piezas por caja ') }}
                    @if(ui_required('inventorypartnumbers_untcas_create'))
                        <span style="color: red">*</span>
                    @endif
                </label>
                <input type="text" class="form-control numeric @error('untcas') is-invalid @enderror" id="untcas"
                       name="untcas" value="{{old('untcas')}}"
                       @if(ui_required('inventorypartnumbers_untcas_create')) required @endif
                >
            </div>
        </div>
    @endif
    @if(ui_visible('inventorypartnumbers_untpal_create'))
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="untpal"> {{ ui_label('inventorypartnumbers_untpal_create','Cajas por pallet ') }}
                    @if(ui_required('inventorypartnumbers_untpal_create'))
                        <span style="color: red">*</span>
                    @endif
                </label>
                <input type="text" class="form-control numeric @error('untpal') is-invalid @enderror" id="untpal"
                       name="untpal" required value="{{old('untpal')}}"
                       @if(ui_required('inventorypartnumbers_untpal_create')) required @endif
                >
            </div>
        </div>
    @endif
</div>

<div class="row">
    @if(ui_visible('inventorypartnumbers_barcode_create'))
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="barcode"> {{ ui_label('inventorypartnumbers_barcode_create','Código de barra ') }}
                    @if(ui_required('inventorypartnumbers_barcode_create'))
                        <span style="color: red">*</span>
                    @endif
                </label>
                <input type="text" class="form-control @error('barcode') is-invalid @enderror" id="barcode"
                       name="barcode" value="{{old('barcode')}}"
                       @if(ui_required('inventorypartnumbers_barcode_create')) required @endif
                >
            </div>
        </div>
    @endif
    @if(ui_visible('inventorypartnumbers_codeean_create'))
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="codeean"> {{ ui_label('inventorypartnumbers_codeean_create','EAN-13 ') }} </label>
                <input type="text" class="form-control" id="codeean" name="codeean" maxlength="20"
                       aria-describedby="codeean" value="{{old('codeean')}}"
                       @if(ui_required('inventorypartnumbers_codeean_create')) required @endif
                >
            </div>
        </div>
    @endif

</div>
<div class="row">
    @if(ui_visible('inventorypartnumbers_codedun_create'))
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="codedun"> {{ ui_label('inventorypartnumbers_codedun_create','DUN-14') }} </label>
                <input type="text" class="form-control" id="codedun" name="codedun" maxlength="100"
                       aria-describedby="codedun" value="{{old('codedun')}}"
                       @if(ui_required('inventorypartnumbers_codedun_create')) required @endif
                >
            </div>
        </div>
    @endif
        @if(ui_visible('inventorypartnumbers_location_create'))
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="auxiliary01"> {{ ui_label('inventorypartnumbers_location_create','Ubicación del producto') }} </label>
                    <input type="text" class="form-control" id="auxiliary01" name="auxiliary01" maxlength="100"
                           aria-describedby="auxiliary01" value="{{old('auxiliary01')}}"
                           @if(ui_required('inventorypartnumbers_location_create')) required @endif
                    >
                </div>
            </div>
        @endif
</div>
