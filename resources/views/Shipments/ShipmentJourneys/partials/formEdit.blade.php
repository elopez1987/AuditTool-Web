<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="location">Ubicación <span style="color: red">*</span></label>
            <input type="text" class="form-control" id="location" name="location"
                   value="{{$shipmentjourney->location}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="eta">Eta. con cliente <span style="color: red">*</span></label>
            <input type="text" class="form-control date" id="eta" name="eta"
                   value="{{$shipmentjourney->eta}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="arrivedate">Arribo con cliente <span style="color: red">*</span></label>
            <input type="text" class="form-control date" id="arrivedate" name="arrivedate"
                   value="{{$shipmentjourney->arrivedate}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="receiptenddate">Fin de descarga <span style="color: red">*</span></label>
            <input type="text" class="form-control date" id="receiptenddate" name="receiptenddate"
                   value="{{$shipmentjourney->receiptenddate}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="razonoutputid">Causa de salida tarde<span style="color: red">*</span></label>
            <select name="razonoutputid" id='razonoutputid' required class="form-control select2">
                <option value="" selected>Seleccione una opción</option>
                @foreach($razonoutput as $model)
                    <option value="{{$model->categoryincdetailid}}"
                            @if($model->categoryincdetailid == $shipmentjourney->razonoutputid)
                                selected="selected"
                        @endif>
                        {{$model->description}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="statusid">Estatus <span style="color: red">*</span></label>
            <select name="statusid" class="form-control select2">
                @foreach($status as $statu)
                    <option value="{{$statu->statusid}}"
                            @if($statu->statusid == $shipmentjourney->statusid)
                                selected="selected"
                        @endif>
                        {{$statu->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
