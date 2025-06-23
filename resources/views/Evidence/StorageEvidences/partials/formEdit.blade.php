<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="razonstatusid">Concepto de entrada <span style="color: red">*</span></label>
            <select name="razonstatusid" required class="form-control select2">
                @foreach($types as $model)
                    <option value="{{$model->id}}"
                            @if($model->id == $storageevidenceprocess->razonstatusid)
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
            <label for="stockcategoryid">Origen <span style="color: red">*</span></label>
            <select name="stockcategoryid" required class="form-control select2">
                @foreach($categories as $model)
                    <option value="{{$model->id}}"
                            @if($model->id == $storageevidenceprocess->stockcategoryid)
                                selected="selected"
                        @endif>
                        {{$model->description}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="shipmentid">Embarque:</label>
            <input type="text" class="form-control" required value="{{$storageevidenceprocess->shipmentid}}"
                   id="shipmentid" name="shipmentid">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="turnid">Turno <span style="color: red">*</span></label>
            <select name="turnid" required class="form-control select2">
                @foreach($turns as $model)
                    <option value="{{$model->id}}"
                            @if($model->id == $storageevidenceprocess->turnid)
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
            <label for="workday">Fecha de trabajo:</label>
            <input type="text" class="form-control workday" required value="{{$storageevidenceprocess->workday}}"
                   id="workday" name="workday">
        </div>
    </div>
    <div id="appointmentdiv" class="hide">
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="appointment">Fecha de cita:</label>
                <input type="text" class="form-control datefilter" required
                       value="{{$storageevidenceprocess->appointment}}"
                       id="appointment" name="appointment">
            </div>
        </div>
    </div>
</div>
<div class="row">
    @if($storageevidenceprocess->razonstatusid == 1)
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div id="customer">
                <div class="form-group">
                    <label for="customerid">Cliente <span style="color: red">*</span></label>
                    <select name="customerid" required class="form-control select2">
                        @foreach($customers as $model)
                            <option value="{{$model->id}}"
                                    @if($model->id == $storageevidenceprocess->customerid)
                                        selected="selected"
                                @endif>
                                {{$model->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endif
    @if($storageevidenceprocess->razonstatusid == 2)
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div id="provider">
                <div class="form-group">
                    <label for="providerid">Proveedor <span style="color: red">*</span></label>
                    <select name="providerid" required class="form-control select2">
                        @foreach($providers as $model)
                            <option value="{{$model->id}}"
                                    @if($model->id == $storageevidenceprocess->providerid)
                                        selected="selected"
                                @endif>
                                {{$model->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endif
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="locationid">Cortina <span style="color: red">*</span></label>
            <select name="locationid" required class="form-control select2">
                @foreach($locations as $model)
                    <option value="{{$model->id}}"
                            @if($model->id == $storageevidenceprocess->locationid)
                                selected="selected"
                        @endif>
                        {{$model->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="carrierid">Linea de transporte <span style="color: red">*</span></label>
            <select name="carrierid" required class="form-control select2">
                @foreach($carriers as $model)
                    <option value="{{$model->id}}"
                            @if($model->id == $storageevidenceprocess->carrierid)
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
            <label for="boxnumber">Placa de caja</label>
            <input type="text"  class="form-control" value="{{$storageevidenceprocess->boxnumber}}"
                   id="boxnumber" name="boxnumber"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="econumber">Eco. de caja</label>
            <input type="text"  class="form-control" value="{{$storageevidenceprocess->econumber}}"
                   id="econumber" name="econumber"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>
