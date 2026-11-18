<div class="row">
    <div class="col-lg-8">
        <div class="card" style="border: 2px solid #a3b0c9;border-radius: 4px;">
            <div class="card-header">Datos Generales</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="centerid">{{ ui_label('centerid_receiptasn_create','Centro de distribución') }}
                                <span style="color: red">*</span></label>
                            <select name="centerid" id="centerid" required class="form-control select2">
                                @foreach($centers as $center)
                                    <option value="{{$center->centerid}}" {{ old('centerid') == $center->centerid ? 'selected' : '' }}> {{$center->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="warehouseid">{{ ui_label('warehouseid_receiptasn_create','Bodega') }} <span
                                        style="color: red">*</span></label>
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
                <div class="row">
                    <x-text-input
                            name="shipmentid"
                            label="Número de vale de recepción"
                            type="text"
                            required
                            placeholder="6374"
                            colClass="col-lg-3 col-md-12"
                    />
                </div>
                <div class="row mt-3">
                    <x-select2
                            name="providerid"
                            label="Nombre de la ruta"
                            :options="$providers"
                            valueField="id"
                            textField="name"
                            :required="true"
                            colClass="col-lg-3 col-md-12"
                    />
                    <x-select2
                            name="operatorid"
                            label="Nombre del operador"
                            :options="$operators"
                            valueField="id"
                            textField="name"
                            :required="true"
                            colClass="col-lg-3 col-md-12"
                    />
                </div>
            </div>
        </div>
    </div>
</div>


