<div class="row">
    <div class="col-lg-10 col-sm-12">
        <div class="card">
            <div class="card-header">Datos Generales</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="centerid">Centros de distribución <span style="color: red">*</span></label>
                            <select name="centerid" id="centerid"
                                    class="form-control select2 @error('centerid') is-invalid @enderror">
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
                            <select name="warehouseid" id="warehouseid"
                                    class="form-control select2 @error('warehouseid') is-invalid @enderror">
                                @foreach($warehouses as $warehouse)
                                    <option value="{{$warehouse->warehouseid}}" {{ old('warehouseid') == $warehouse->warehouseid ? 'selected' : '' }}> {{$warehouse->name}} </option>
                                @endforeach
                            </select>
                            @error('warehouseid')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="razonstatusid">Tipo de entrada<span style="color: red">*</span></label>
                            <select name="razonstatusid" id="razonstatusid"
                                    class="form-control select2 @error('razonstatusid') is-invalid @enderror">
                                @foreach($movents as $model)
                                    <option value="{{$model->id}}" {{ old('razonstatusid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                                @endforeach
                            </select>
                            @error('razonstatusid')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div id="unitstatus">
                            <div class="form-group">
                                <label for="unitstatusid">Estatus de unidad<span style="color: red">*</span></label>
                                <select name="unitstatusid" id="unitstatusid"
                                        class="form-control select2 @error('unitstatusid') is-invalid @enderror">
                                    @foreach($unitsstatus as $model)
                                        <option value="{{$model->id}}" {{ old('unitstatusid') == $model->id ? 'selected' : '' }} > {{$model->name}} </option>
                                    @endforeach
                                </select>
                                @error('unitstatusid')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="shipmentid">Embarque/Recibo
                                <span style="color: red">*</span></label>
                            <input type="text" class="form-control @error('shipmentid') is-invalid @enderror"
                                   required id="shipmentid" name="shipmentid" maxlength="20"
                                   onKeyUp=this.value=this.value.toUpperCase();
                                   value="{{old('shipmentid')}}"
                            >
                            @error('shipmentid')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12" id="sello">
                        <div class="form-group">
                            <label for="containerseal">Número de sellos</label>
                            <input type="text" class="form-control @error('containerseal') is-invalid @enderror"
                                   id="containerseal" name="containerseal" maxlength="15"
                                   onKeyUp=this.value=this.value.toUpperCase();
                                   value="{{old('containerseal')}}"
                            >
                            @error('containerseal')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12" id="proveedor">
                        <div class="form-group">
                            <label for="providerid">Proveedor</label>
                            <select name="providerid" id='providerid'
                                    class="form-control select2 @error('providerid') is-invalid @enderror">
                                <option value="" selected>Seleccione una opción</option>
                                @foreach($providers as $model)
                                    <option value="{{$model->id}}" {{ old('providerid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
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
                            <label for="zoneid">Zonas<span style="color: red">*</span></label>
                            <select name="zoneid" id='zoneid' required
                                    class="form-control select2 @error('zoneid') is-invalid @enderror">
                                @foreach($zones as $model)
                                    <option value="{{$model->id}}" {{ old('zoneid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                                @endforeach
                            </select>
                            @error('zoneid')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="locationid">Ubicación<span style="color: red">*</span></label>
                            <select name="locationid" id='locationid' required
                                    class="form-control select2 @error('locationid') is-invalid @enderror">
                                @foreach($locations as $model)
                                    <option value="{{$model->id}}" {{ old('locationid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                                @endforeach
                            </select>
                            @error('locationid')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-10 col-sm-12">
        <div class="card">
            <div class="card-header">Datos del operador</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="barcodeoperator">Código de barra de operador
                                <span style="color: red">*</span>
                                @error("barcodeoperator")
                                <span class="message-error">{{$message}}</span>
                                @enderror
                            </label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control numeric @error('barcodeoperator') is-invalid @enderror"
                                       id="barcodeoperator"
                                       value="{{ old('barcodeoperator') }}" minlength="4" maxlength="10"
                                       name="barcodeoperator" required>
                                <div class="input-group-append">
                                    <button class="btn btn-info btn-xs" style="font-size: 11px;" id="searchOperator"
                                            type="button"><i class="ti ti-search"></i>
                                    </button>
                                </div>
                            </div>
                            @error('barcodeoperator')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="operatorname">Nombre de operador
                                <span style="color: red">*</span></label>
                            <input type="text" class="form-control @error('operatorname') is-invalid @enderror"
                                   required id="operatorname" name="operatorname" maxlength="100"
                                   onKeyUp=this.value=this.value.toUpperCase();
                                   value="{{old('operatorname')}}"
                            >
                            @error('operatorname')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="drivernumber">Número de licencia
                                <span style="color: red">*</span></label>
                            <input type="text" class="form-control @error('drivernumber') is-invalid @enderror"
                                   required id="drivernumber" name="drivernumber" maxlength="19"
                                   onKeyUp=this.value=this.value.toUpperCase();
                                   value="{{old('drivernumber')}}"
                            >
                            @error('drivernumber')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="phone">Celular
                                <span style="color: red">*</span></label>
                            <input type="text" class="form-control numeric @error('phone') is-invalid @enderror"
                                   required id="phone" name="phone" minlength="1" maxlength="10"
                                   value="{{old('phone')}}"
                            >
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-10 col-sm-12">
        <div class="card">
            <div class="card-header">Datos de la unidad</div>
            <div class="card-body">

                <div class="row">
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="barcodebox">Código de barra de caja
                                <span style="color: red">*</span>
                                @error("barcodebox")
                                <span class="message-error">{{$message}}</span>
                                @enderror
                            </label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control numeric @error('barcodebox') is-invalid @enderror"
                                       id="barcodebox"
                                       value="{{ old('barcodebox') }}" minlength="4"
                                       name="barcodebox" required>
                                <div class="input-group-append">
                                    <button class="btn btn-info btn-xs" id="searchBox" style="font-size: 11px;"
                                            type="button"><i class="ti ti-search"></i>
                                    </button>
                                </div>
                            </div>
                            @error('barcodebox')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="boxnumber">Placa de caja
                                <span style="color: red">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('boxnumber') is-invalid @enderror"
                                       id="boxnumber" value="{{ old('boxnumber') }}"
                                       name="boxnumber" required maxlength="10"
                                       onKeyUp=this.value=this.value.toUpperCase();
                                >
                                <div class="input-group-append">
                                    <button class="btn btn-info btn-xs" id="searchBoxNumber" style="font-size: 11px;"
                                            type="button"><i class="ti ti-search"></i>
                                    </button>
                                </div>
                            </div>
                            @error('boxnumber')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="ecobox">Economico de caja
                                <span style="color: red">*</span></label>
                            <input type="text" class="form-control @error('ecobox') is-invalid @enderror"
                                   required id="ecobox" name="ecobox" maxlength="19"
                                   onKeyUp=this.value=this.value.toUpperCase();
                                   value="{{old('ecobox')}}"
                            >
                            @error('ecobox')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="carrierid">Linea de transporte<span style="color: red">*</span></label>
                            <select name="carrierid" id="carrierid"
                                    class="form-control select2 @error('carrierid') is-invalid @enderror">
                                @foreach($carriers as $model)
                                    <option value="{{$model->id}}" {{ old('carrierid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                                @endforeach
                            </select>
                            @error('carrierid')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="unittypeid">Tipo<span style="color: red">*</span></label>
                            <select name="unittypeid" id="unittypeid"
                                    class="form-control select2 @error('unittypeid') is-invalid @enderror">
                                @foreach($unittypeid as $model)
                                    <option value="{{$model->id}}" {{ old('unittypeid') == $model->id ? 'selected' : '' }}> {{$model->description}} </option>
                                @endforeach
                            </select>
                            @error('unittypeid')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="truckcategoryid">Tipo de unidad<span style="color: red">*</span></label>
                            <select name="truckcategoryid" id="truckcategoryid"
                                    class="form-control select2 @error('truckcategoryid') is-invalid @enderror">
                                @foreach($truckcategories as $model)
                                    <option value="{{$model->truckcategoryid}}" {{ old('truckcategoryid') == $model->id ? 'selected' : '' }}> {{$model->description}} </option>
                                @endforeach
                            </select>
                            @error('truckcategoryid')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="truckid">Capacidad<span style="color: red">*</span></label>
                            <select name="truckid" id="truckid"
                                    class="form-control select2 @error('truckid') is-invalid @enderror">
                                @foreach($trucks as $model)
                                    <option value="{{$model->id}}" {{ old('truckid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                                @endforeach
                            </select>
                            @error('truckid')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row" id="tracto">
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="barcodetruck">Código de barra de tracto
                                <span style="color: red">*</span>
                                @error("barcodetruck")
                                <span class="message-error">{{$message}}</span>
                                @enderror
                            </label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control numeric @error('barcodetruck') is-invalid @enderror"
                                       id="barcodetruck"
                                       value="{{ old('barcodetruck') }}" minlength="4" maxlength="10"
                                       name="barcodetruck">
                                <div class="input-group-append">
                                    <button class="btn btn-info btn-xs" id="searchTruck" style="font-size: 11px;"
                                            type="button"><i class="ti ti-search"></i>
                                    </button>
                                </div>
                            </div>
                            @error('barcodetruck')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="trucknumber">Placa de tracto
                                <span style="color: red">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('trucknumber') is-invalid @enderror"
                                       id="trucknumber"
                                       value="{{ old('trucknumber') }}" maxlength="10"
                                       name="trucknumber" onKeyUp=this.value=this.value.toUpperCase();>
                                <div class="input-group-append">
                                    <button class="btn btn-info btn-xs" id="searchTruckNumber" style="font-size: 11px;"
                                            type="button"><i class="ti ti-search"></i>
                                    </button>
                                </div>
                            </div>
                            @error('trucknumber')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="ecotruck">Economico de tracto
                                @error("ecotruck")
                                <span class="message-error">{{$message}}</span>
                                @enderror
                                <span style="color: red">*</span></label>
                            <input type="text" class="form-control @error('ecotruck') is-invalid @enderror"
                                   id="ecotruck" name="ecotruck" maxlength="19"
                                   onKeyUp=this.value=this.value.toUpperCase();
                                   value="{{old('ecotruck')}}"
                            >
                            @error('ecotruck')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="">Comentario</label>
            <textarea class="form-control" rows="10" name="comment"
                      placeholder="Ingrese un comentario corto..."></textarea>
        </div>
    </div>
</div>





