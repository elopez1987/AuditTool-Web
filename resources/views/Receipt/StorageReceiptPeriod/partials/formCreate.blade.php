<div class="row">
    <div class="col-lg-8">
        <div class="card-input">
            <div class="card">
                <div class="card-header">Datos Generales</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="centerid">Centros de distribución <span style="color: red">*</span></label>
                                <select name="centerid" id="centerid" required class="form-control select2">
                                    @foreach($centers as $center)
                                        <option value="{{$center->centerid}}" {{ old('centerid') == $center->centerid ? 'selected' : '' }}> {{$center->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="warehouseid">Bodega <span style="color: red">*</span></label>
                                <select name="warehouseid" id="warehouseid" required class="form-control select2">
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{$warehouse->warehouseid}}" {{ old('warehouseid') == $warehouse->warehouseid ? 'selected' : '' }}> {{$warehouse->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="name">Nombre del período
                                    <span style="color: red">*</span>
                                    @error("name")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                </label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       required
                                       maxlength="50"
                                       onKeyUp=this.value=this.value.toUpperCase();
                                       value="{{old('name')}}"
                                >
                                @error('oc')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="startdate">Fecha de inicio
                                    @error("startdate")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                    <span style="color: red">*</span></label>
                                <input type="text" class="form-control workday @error('startdate') is-invalid @enderror"
                                       required id="startdate" name="startdate" value="{{old('startdate')}}"
                                >
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="finishdate">Fecha Final
                                    @error("finishdate")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                    <span style="color: red">*</span></label>
                                <input type="text" class="form-control workday @error('finishdate') is-invalid @enderror"
                                       required id="finishdate" name="finishdate" value="{{old('finishdate')}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


