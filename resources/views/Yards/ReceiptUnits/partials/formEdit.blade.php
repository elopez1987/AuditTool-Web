<div class="row">
    <div class="col-lg-12">
        <div class="card-header">
            Datos generales
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="centerid">Centros de distribución <span style="color: red">*</span></label>
                        <select name="centerid" id="centerid"
                                class="form-control select2 @error('centerid') is-invalid @enderror">
                            @foreach($centers as $center)
                                <option value="{{$center->centerid}}"
                                        @if($center->centerid == $receiptunit->centerid)
                                            selected="selected"
                                        @endif>
                                    {{$center->name}}
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
                        <label for="warehouseid">Bodega <span style="color: red">*</span></label>
                        <select name="warehouseid" id="warehouseid"
                                class="form-control select2 @error('warehouseid') is-invalid @enderror">
                            @foreach($warehouses as $model)
                                <option value="{{$model->warehouseid}}"
                                        @if($model->warehouseid == $receiptunit->warehouseid)
                                            selected="selected"
                                        @endif>
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
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-header">
            Datos del operador
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="foliotruck">Folio de tracto </label>
                        <input type="text" class="form-control"  readonly
                               aria-describedby="auxiliary01" value="{{$receiptunit->foliotruck ?? ''}}"
                        >
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="foliotruck">CB. de tracto </label>
                        <input type="text" class="form-control"  readonly
                               aria-describedby="auxiliary01" value="{{$receiptunit->tracto  ? $receiptunit->tracto->barcode : ''}}"
                        >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="operatorname">Nombre de operador </label>
                        <input type="text" class="form-control" id="operatorname"   name="operatorname" maxlength="100"
                               aria-describedby="auxiliary01" value="{{$receiptunit->tracto ? $receiptunit->tracto->operatorname : ''}}"
                               onKeyUp=this.value=this.value.toUpperCase()
                        >
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="drivernumber">Número de licencia </label>
                        <input type="text" class="form-control" id="drivernumber"  name="drivernumber" maxlength="19"
                               aria-describedby="auxiliary01" value="{{$receiptunit->tracto ? $receiptunit->tracto->drivernumber : ''}}" minlength="1"
                               onKeyUp=this.value=this.value.toUpperCase()>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="phone">Número de celular </label>
                        <input type="text" class="form-control numeric"  id="phone" name="phone" maxlength="10"
                               aria-describedby="auxiliary01" value="{{$receiptunit->tracto ? $receiptunit->tracto->phone : ''}}" minlength="1">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-header">
            Datos de la unidad
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="carrierid">Linea de transporte <span style="color: red">*</span></label>
                        <select required name="carrierid" id="carrierid" class="form-control select2">
                            @foreach($carriers as $model)
                                <option value="{{$model->id}}"
                                        @if($model->id == $receiptunit->carrierid)
                                            selected="selected"
                                        @endif>
                                    {{$model->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="boxnumber">Placa de caja </label>
                        <input type="text" class="form-control" required id="boxnumber" name="boxnumber" maxlength="10"
                               aria-describedby="auxiliary01" value="{{$receiptunit->boxnumber}}"
                               onKeyUp=this.value=this.value.toUpperCase()>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="econumber">Economico de caja </label>
                        <input type="text" class="form-control " required id="econumber" name="econumber" maxlength="19"
                               aria-describedby="auxiliary01" value="{{$receiptunit->econumber}}"
                               onKeyUp=this.value=this.value.toUpperCase()>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="razonstatusid">Tipo de entrada<span style="color: red">*</span></label>
                        <select name="razonstatusid" id="razonstatusid"
                                class="form-control select2 @error('razonstatusid') is-invalid @enderror">
                            @foreach($movents as $model)
                                <option value="{{$model->id}}"
                                        @if($model->id == $receiptunit->razonstatusid)
                                            selected="selected"
                                        @endif>
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
                        <label for="unittypeid">Origen<span style="color: red">*</span></label>
                        <select name="unittypeid" id="unittypeid"
                                class="form-control select2 @error('unittypeid') is-invalid @enderror">
                            @foreach($unittypes as $model)
                                <option value="{{$model->id}}"
                                        @if($model->id == $receiptunit->unittypeid)
                                            selected="selected"
                                        @endif>
                                    {{$model->description}}
                                </option>
                            @endforeach
                        </select>
                        @error('unittypeid')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <x-select2
                        name="truckid"
                        label="Capacidad"
                        :options="$trucks"
                        valueField="id"
                        textField="name"
                        :selected="$receiptunit->truckid"
                        :required="true"
                />
                <x-select2
                        name="truckcategoryid"
                        label="Tipo de unidad"
                        :options="$truckcategories"
                        valueField="truckcategoryid"
                        textField="description"
                        :selected="$receiptunit->truckcategoryid"
                        :required="true"
                />
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="shipmentid">Número de embarque </label>
                        <input type="text" class="form-control " id="shipmentid" name="shipmentid" maxlength="15"
                               aria-describedby="auxiliary01" value="{{$receiptunit->shipmentid}}"
                               onKeyUp=this.value=this.value.toUpperCase()>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="containerseal">Sellos de caja </label>
                        <input type="text" class="form-control " id="containerseal" name="containerseal" maxlength="15"
                               aria-describedby="auxiliary01" value="{{$receiptunit->containerseal}}"
                               onKeyUp=this.value=this.value.toUpperCase()>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="trucknumber">Placa de tracto </label>
                        <input type="text" class="form-control "  id="trucknumber" name="trucknumber" maxlength="10"
                               aria-describedby="auxiliary01" value="{{$receiptunit->tracto ? $receiptunit->tracto->trucknumber : ''}}"
                               onKeyUp=this.value=this.value.toUpperCase()>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="ecotruck">Eco. de tracto </label>
                        <input type="text" class="form-control "  id="ecotruck" name="ecotruck" maxlength="100"
                               aria-describedby="ecotruck" value="{{$receiptunit->tracto ? $receiptunit->tracto->econumber : ''}}"
                               onKeyUp=this.value=this.value.toUpperCase()>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






