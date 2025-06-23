<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="operatorid">Nombre del operador <span style="color: red">*</span></label>
            <select required name="operatorid" id="operatorid" class="form-control select2">
                @foreach($operators as $model)
                    <option value="{{$model->id}}"
                            @if($model->id == $generateaccesswarehouse->operatorid)
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
            <label for="boxid">Placa de caja<span style="color: red">*</span></label>
            <select required name="boxid" id="boxid" class="form-control select2">
                @foreach($boxes as $model)
                    <option value="{{$model->id}}"
                            @if($model->id == $generateaccesswarehouse->boxid)
                                selected="selected"
                        @endif>
                        {{$model->boxnumber}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="truckid">Placa de tracto<span style="color: red">*</span></label>
            <select required name="truckid" id="truckid" class="form-control select2">
                @foreach($trucks as $model)
                    <option value="{{$model->id}}"
                            @if($model->id == $generateaccesswarehouse->truckid)
                                selected="selected"
                        @endif>
                        {{$model->boxnumber}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="datestart">F.Inicio</label>
                <input type="text" class="form-control date" required id="datestart" name="datestart"
                       value="{{$generateaccesswarehouse->datestart}}"
                >
            </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="datefinish">F.Final</label>
                <input type="text" class="form-control date" required id="datefinish" name="datefinish"
                       value="{{$generateaccesswarehouse->datefinish}}"
                >
            </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="shipmentid">Embarque</label>
            <input type="text" class="form-control" id="shipmentid" name="shipmentid"
                   onKeyUp=this.value=this.value.toUpperCase()
                   value="{{$generateaccesswarehouse->shipmentid}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="confirmationnumber">Número de confirmación</label>
            <input type="text" class="form-control" id="confirmationnumber" name="confirmationnumber"
                   onKeyUp=this.value=this.value.toUpperCase()
                   value="{{$generateaccesswarehouse->confirmationnumber}}"
            >
        </div>
    </div>
</div>

