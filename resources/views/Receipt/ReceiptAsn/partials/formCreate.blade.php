<div class="row">
    <div class="col-lg-8">
        <div class="card" style="border: 2px solid #a3b0c9;border-radius: 4px;">
            <div class="card-header">Datos Generales</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="centerid">{{ ui_label('centerid_receiptasn_create','Centro de distribución') }}  <span style="color: red">*</span></label>
                            <select name="centerid" id="centerid" required class="form-control select2">
                                @foreach($centers as $center)
                                    <option value="{{$center->centerid}}" {{ old('centerid') == $center->centerid ? 'selected' : '' }}> {{$center->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="warehouseid">{{ ui_label('warehouseid_receiptasn_create','Bodega') }} <span style="color: red">*</span></label>
                            <select name="warehouseid" id="warehouseid" required class="form-control select2">
                                @foreach($warehouses as $warehouse)
                                    <option
                                            value="{{$warehouse->warehouseid}}" {{ old('warehouseid') == $warehouse->warehouseid ? 'selected' : '' }}> {{$warehouse->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="addasndate">{{ ui_label('addasndate_receiptasn_create','Fecha de cita de descarga') }}
                                @error("addasndate")
                                <span class="message-error">{{$message}}</span>
                                @enderror
                                <span style="color: red">*</span></label>
                            <input type="text" class="form-control date @error('addasndate') is-invalid @enderror"
                                   required id="addasndate" name="addasndate"
                                   aria-describedby="inputname" onKeyUp=this.value=this.value.toUpperCase();
                                   value="{{old('addasndate')}}"
                            >
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="turnid">{{ ui_label('turnid_receiptasn_create','Turno de trabajo') }}
                                @error("turnid")
                                <span class="message-error">{{$message}}</span>
                                @enderror
                                <span style="color: red">*</span>
                            </label>
                            <select name="turnid" required class="form-control select2">
                                <option value="" selected>Seleccione una opción</option>
                                @foreach($turns as $model)
                                    <option
                                            value="{{$model->id}}" {{ old('turnid') == $model->id ? 'selected' : '' }} > {{$model->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card" style="border: 2px solid #a3b0c9;border-radius: 4px;">
            <div class="card-header">Datos del recibo</div>
            <div class="card-body">
                <div class="row mt-3">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="razonstatusid">{{ ui_label('razonstatusid_receiptasn_create','Tipo de entrada') }}
                                @error("razonstatusid")
                                <span class="message-error">{{$message}}</span>
                                @enderror
                                <span style="color: red">*</span>
                            </label>
                            <select name="razonstatusid" required class="form-control select2">
                                <option value="" selected>Seleccione una opción</option>
                                @foreach($reasons as $model)
                                    <option
                                            value="{{$model->id}}" {{ old('razonstatusid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="stockcategoryid">{{ ui_label('stockcategoryid_receiptasn_create','Origen de la descarga') }}
                                @error("stockcategoryid")
                                <span class="message-error">{{$message}}</span>
                                @enderror
                                <span style="color: red">*</span></label>
                            <select name="stockcategoryid" required class="form-control select2">
                                <option value="" selected>Seleccione una opción</option>
                                @foreach($categorystocks as $model)
                                    <option
                                            value="{{$model->id}}" {{ old('stockcategoryid') == $model->id ? 'selected' : '' }}> {{$model->description}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="providerid">{{ ui_label('providerid_receiptasn_create','Proveedor') }}
                                @error("providerid")
                                <span class="message-error">{{$message}}</span>
                                @enderror
                                <span style="color: red">*</span>
                            </label>
                            <select name="providerid" id='providerid' required class="form-control select2">
                                <option value="" selected>Seleccione una opción</option>
                                @foreach($providers as $model)
                                    <option
                                            value="{{$model->id}}" {{ old('providerid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="shipmentid">{{ ui_label('shipmentid_receiptasn_create','Número de recibo') }}
                                @error("shipmentid")
                                <span class="message-error">{{$message}}</span>
                                @enderror
                                <span style="color: red">*</span></label>
                            <input type="text" class="form-control @error('shipmentid') is-invalid @enderror"
                                   id="shipmentid" name="shipmentid"
                                   aria-describedby="shipmentid" onKeyUp=this.value=this.value.toUpperCase();
                                   value="{{old('shipmentid')}}"
                            >
                            @error('shipmentid')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="billoflading">{{ ui_label('billoflading_receiptasn_create','Factura') }}</label>
                            <input type="text" class="form-control @error('billoflading') is-invalid @enderror"
                                   id="billoflading" name="billoflading"
                                   aria-describedby="billoflading" onKeyUp=this.value=this.value.toUpperCase();
                                   value="{{old('billoflading')}}"
                            >
                            @error('billoflading')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="oc">{{ ui_label('oc_receiptasn_create','Orden de compra') }}</label>
                            <input type="text" class="form-control @error('oc') is-invalid @enderror"
                                   id="oc" name="oc"
                                   aria-describedby="oc" onKeyUp=this.value=this.value.toUpperCase();
                                   value="{{old('oc')}}"
                            >
                            @error('oc')
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
    <div class="col-lg-8">
        <div class="card" style="border: 2px solid #a3b0c9;border-radius: 4px;">
            <div class="card-header">Datos del transporte</div>
            <div class="card-body">
                <div class="row mt-3">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="folionumber">{{ ui_label('folionumber_receiptasn_create','Folio de control de patios') }}
                                @error("folionumber")
                                <span class="message-error">{{$message}}</span>
                                @enderror
                            </label>
                            <input type="text" class="form-control @error('folionumber') is-invalid @enderror"
                                   id="folionumber" name="folionumber"
                                   aria-describedby="folionumber"
                                   value="{{old('folionumber')}}"
                            >
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="carrierid">{{ ui_label('carrierid_receiptasn_create','Linea de transporte') }}
                                @error("carrierid")
                                <span class="message-error">{{$message}}</span>
                                @enderror
                                <span style="color: red">*</span>
                            </label>
                            <select name="carrierid" class="form-control select2" required>
                                <option value="" selected>Seleccione una opción</option>
                                @foreach($carriers as $model)
                                    <option value="{{$model->id}}" {{ old('carrierid') == $model->id ? 'selected' : '' }} > {{$model->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

