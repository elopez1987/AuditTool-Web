<div class="row">
    <div class="col-lg-6">
        <div class="card-input">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="shipmentid">Número de embarque </label>
                        <input type="text" class="form-control" readonly id="shipmentid" name="shipmentid" value="{{$receipt->shipmentid}}">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 disabled">
                    <div class="form-group">
                        <label for="workday">Fecha de trabajo </label>
                        <input type="text" class="form-control" readonly id="workday" name="workday" value="{{$receipt->workday}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="addasndate">Cita de descarga </label>
                        <input type="text" class="form-control" readonly id="addasndate" value="{{$receipt->addasndate}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card-input">
            <div class="card">
                <div class="card-header">
                    Datos de Operador
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="operatorname">Nombre de operador
                                    @error("operatorname")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                    <span style="color: red">*</span></label>
                                <input type="text" class="form-control @error('operatorname') is-invalid @enderror"
                                       required id="operatorname" name="operatorname"
                                       aria-describedby="operatorname" onKeyUp=this.value=this.value.toUpperCase();
                                       value="{{$transport?->operatorname}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="phone">Número de contacto
                                    @error("phone")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                    <span style="color: red">*</span></label>
                                <input type="text" class="form-control numeric @error('phone') is-invalid @enderror"
                                       required id="phone" name="phone" minlength="10" maxlength="10"
                                       aria-describedby="phone" onKeyUp=this.value=this.value.toUpperCase();
                                       value="{{$transport?->phone}}"
                                >
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="drivernumber">Número de licencia
                                    @error("drivernumber")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                    <span style="color: red">*</span></label>
                                <input type="text" class="form-control  @error('drivernumber') is-invalid @enderror"
                                       required id="drivernumber" name="drivernumber" maxlength="30"
                                       aria-describedby="drivernumber" onKeyUp=this.value=this.value.toUpperCase();
                                       value="{{$transport?->drivernumber}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="carrierid">Linea de transporte</label>
                                <select name="carrierid" id="carrierid" required class="form-control select2">
                                    <option value="" selected>Seleccione una opción</option>
                                    @foreach($carriers as $model)
                                        <option value="{{$model->id}}"
                                                @if($model->id == $transport?->carrierid)
                                                    selected="selected"
                                                @endif>
                                            {{$model->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card-input">
            <div class="card" >
                <div class="card-header">
                    Datos de Transporte
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="boxnumber">Placa de caja o remolque
                                    @error("boxnumber")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                    <span style="color: red">*</span></label>
                                <input type="text"
                                       class="form-control LetrasNumeros @error('boxnumber') is-invalid @enderror"
                                       required id="boxnumber" name="boxnumber" maxlength="10"
                                       aria-describedby="boxnumber" onKeyUp=this.value=this.value.toUpperCase();
                                       value="{{$transport?->boxnumber}}"
                                >
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="ecobox">Eco. De Caja
                                    @error("ecobox")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                    <span style="color: red">*</span></label>
                                <input type="text" class="form-control @error('ecobox') is-invalid @enderror"
                                       required id="ecobox" name="ecobox" maxlength="9"
                                       aria-describedby="ecobox" onKeyUp=this.value=this.value.toUpperCase();
                                       value="{{$transport?->ecobox}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div id="tracto">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="trucknumber">Placa de tracto
                                        @error("trucknumber")
                                        <span class="message-error">{{$message}}</span>
                                        @enderror
                                        <span style="color: red">*</span></label>
                                    <input type="text" class="form-control  @error('trucknumber') is-invalid @enderror"
                                           required id="trucknumber" name="trucknumber" maxlength="10"
                                           aria-describedby="trucknumber" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{$transport?->trucknumber}}"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="ecotruck">Eco. De tracto
                                        @error("ecotruck")
                                        <span class="message-error">{{$message}}</span>
                                        @enderror
                                        <span style="color: red">*</span></label>
                                    <input type="text" class="form-control @error('ecotruck') is-invalid @enderror"
                                           required id="ecotruck" name="ecotruck" maxlength="9"
                                           aria-describedby="ecotruck" onKeyUp=this.value=this.value.toUpperCase();
                                           value="{{$transport?->ecotruck}}"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


