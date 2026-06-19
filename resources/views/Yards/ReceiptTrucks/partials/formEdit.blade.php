<div class="row">
    <x-text-input
            name="folio"
            label="Folio de tracto"
            disabled="disabled"
            :value="$receipttruck->id"
    />

    <x-select2
            name="warehouseid"
            label="Bodega"
            :options="$warehouses"
            valueField="warehouseid"
            textField="name"
            :required="true"
            :selected="$receipttruck->warehouseid"
    />
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="barcodeoperator">Código de Barra Operador</label>
            <div class="input-group">
                <input type="text" class="form-control" id="barcodeoperator" value="{{$receipttruck->barcodeoperator}}"
                       name="barcodeoperator"
                       aria-describedby="basic-addon1">
                <div class="input-group-append">
                    <button class="btn btn-info btn-xs" id="searchOperator" type="button">Buscar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="operatorname">Nombre de operador <span style="color: red">*</span></label>
            <input type="text" class="form-control" id="operatorname" name="operatorname"
                   value="{{$receipttruck->operatorname}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="drivernumber">Número de licencia <span style="color: red">*</span></label>
            <input type="text" class="form-control" id="drivernumber" name="drivernumber"
                   value="{{$receipttruck->drivernumber}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="phone">Número de celular <span style="color: red">*</span></label>
            <input type="text" class="form-control" maxlength="10" minlength="10" id="phone" name="phone"
                   value="{{$receipttruck->phone}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>
<br>

<hr class="m-t-0">
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="barcodetruck">Código de barra de tracto</label>
            <div class="input-group">
                <input type="text" class="form-control" id="barcodetruck" value="{{$receipttruck->barcode}}"
                       name="barcodetruck"
                       aria-describedby="basic-addon1">
                <div class="input-group-append">
                    <button class="btn btn-info btn-xs" id="searchTruck" type="button">Buscar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="trucknumber">Placa de tracto <span style="color: red">*</span></label>
            <input type="text" class="form-control" id="trucknumber" name="trucknumber"
                   value="{{$receipttruck->trucknumber}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="econumber">Economico de tracto <span style="color: red">*</span></label>
            <input type="text" class="form-control" id="econumber" name="econumber"
                   value="{{$receipttruck->econumber}}" maxlength="19"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="carrierid">Linea de transporte <span style="color: red">*</span></label>
            <select name="carrierid" class="form-control select2">
                @foreach($carriers as $model)
                    <option value="{{$model->id}}"
                            @if($model->id == $receipttruck->carrierid)
                                selected="selected"
                        @endif>
                        {{$model->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

