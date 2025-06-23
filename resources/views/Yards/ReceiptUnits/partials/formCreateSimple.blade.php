<div class="row">
    <div class="col-lg-10 col-sm-12">
        <div class="card">
            <div class="card-header">Datos Generales</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="warehouseid">Bodega <span style="color: red">*</span></label>
                            <select name="warehouseid" id="warehouseid" class="form-control select2">
                                @foreach($warehouses as $warehouse)
                                    <option value="{{$warehouse->warehouseid}}" {{ old('warehouseid') == $warehouse->warehouseid ? 'selected' : '' }}> {{$warehouse->name}} </option>
                                @endforeach
                            </select>
                            @error('warehouseid')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="razonstatusid">Tipo de entrada<span style="color: red">*</span></label>
                            <select name="razonstatusid" id='razonstatusid' required class="form-control select2">
                                @foreach($movents as $model)
                                    <option value="{{$model->id}}" {{ old('razonstatusid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                                @endforeach
                            </select>
                            @error('razonstatusid')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div id="proveedor">
                            <div class="form-group">
                                <label for="providerid">Proveedor<span style="color: red">*</span></label>
                                <select name="providerid" id='providerid' class="form-control select2">
                                    @foreach($providers as $model)
                                        <option
                                            value="{{$model->id}}" {{ old('providerid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('providerid')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="shipmentid">Número de embarque
                                <span style="color: red">*</span>
                            </label>
                            <input type="text" class="form-control @error('shipmentid') is-invalid @enderror"
                                   required id="shipmentid" name="shipmentid" maxlength="10"
                                   value="{{old('shipmentid')}}">
                            @error('shipmentid')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12" id="sello">
                        <div id="seal">
                            <div class="form-group">
                                <label for="containerseal">Número de sello</label>
                                <input type="text" class="form-control @error('containerseal') is-invalid @enderror"
                                       id="containerseal" name="containerseal" maxlength="15"
                                       onKeyUp=this.value=this.value.toUpperCase();
                                       value="{{old('containerseal')}}">
                            </div>
                            @error('containerseal')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12">
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
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="phone">Número de celular</label>
                            <input type="text" class="form-control numeric @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" maxlength="10" value="{{old('phone')}}">
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
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="boxnumber">Placa de caja
                                <span style="color: red">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="boxnumber" value="{{ old('boxnumber') }}"
                                       name="boxnumber" required
                                       onKeyUp=this.value=this.value.toUpperCase() maxlength="10"
                                >
                            </div>
                            @error('boxnumber')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="ecobox">Económico de caja<span style="color: red">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="ecobox" value="{{ old('ecobox') }}"
                                       name="ecobox" required
                                       onKeyUp=this.value=this.value.toUpperCase() maxlength="19"
                                >
                            </div>
                            @error('ecobox')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="trucknumber">Placa de tracto <span style="color: red">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="trucknumber"
                                       value="{{ old('trucknumber') }}" maxlength="10"
                                       name="trucknumber" required
                                       onKeyUp=this.value=this.value.toUpperCase()
                                >
                                @error('trucknumber')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="econumber">Económico de tracto <span style="color: red">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="econumber" value="{{ old('econumber') }}"
                                       name="econumber" required
                                       onKeyUp=this.value=this.value.toUpperCase() maxlength="19"
                                >
                                @error('econumber')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="unittypeid">Origen<span style="color: red">*</span></label>
                            <select name="unittypeid" required id="unittypeid" class="form-control select2">
                                @foreach($unittypeid as $model)
                                    <option
                                        value="{{$model->id}}" {{ old('unittypeid') == $model->id ? 'selected' : '' }}> {{$model->description}} </option>
                                @endforeach
                            </select>
                            @error('unittypeid')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="carrierid">Linea de transporte<span style="color: red">*</span></label>
                            <select name="carrierid" required class="form-control select2">
                                @foreach($carriers as $model)
                                    <option
                                        value="{{$model->id}}" {{ old('carrierid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                                @endforeach
                            </select>
                            @error('carrierid')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="truckcategoryid">Categoria de unidad<span style="color: red">*</span></label>
                            <select name="truckcategoryid" required id="truckcategoryid" class="form-control select2">
                                @foreach($truckcategories as $model)
                                    <option value="{{$model->truckcategoryid}}" {{ old('truckcategoryid') == $model->truckcategoryid ? 'selected' : '' }}> {{$model->description}} </option>
                                @endforeach
                            </select>
                            @error('truckcategoryid')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="truckid">Capacidad de unidad<span style="color: red">*</span></label>
                            <select name="truckid" required id="truckid" class="form-control select2">
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
                @php
                    $existsParameter = !empty($parameter) && in_array('SWH-42',$parameter);
                @endphp
                @if($existsParameter)
                    <div class="row">
                        <div class="col-lg-3 col-md-6  col-xs-12 mt-2">
                            <div class="form-group">
                                <label for="zoneid">Zonas</label>
                                <select name="zoneid" id='zoneid' class="form-control select2">
                                    <option value="" selected>Seleccione una opción</option>
                                    @foreach($zones as $model)
                                        <option
                                            value="{{$model->id}}" {{ old('zoneid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                                    @endforeach
                                </select>
                                @error('zoneid')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6  col-xs-12 mt-2">
                            <div class="form-group">
                                <label for="locationid">Ubicación</label>
                                <select name="locationid" id='locationid' class="form-control select2">
                                    <option value="" selected>Seleccione una opción</option>
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
                @endif
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="">Comentario</label>
                            <textarea class="form-control" rows="10" name="comment" placeholder="Ingrese un comentario corto..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



