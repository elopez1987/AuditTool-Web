<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerid">Centro de distribución</label>
            <input type="text" class="form-control" id="centerid" name="centerid"
                   onKeyUp=this.value=this.value.toUpperCase() readonly
                   value="{{$storagelabeltruck->center->name}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="warehouseid">Bodega</label>
            <input type="text" class="form-control" id="warehouseid" name="warehouseid"
                   onKeyUp=this.value=this.value.toUpperCase() readonly
                   value="{{$storagelabeltruck->warehouse->name}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="typeid">Tipo de registro<span style="color: red">*</span></label>
            <select name="typeid" id="typeid" class="form-control select2">
                <option value="" selected>Seleccione una opción</option>
                <option value="BX" @if($storagelabeltruck->typeid == 'BX') selected @endif>CAJA</option>
                <option value="TR" @if($storagelabeltruck->typeid == 'TR') selected @endif>TRACTO</option>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="carrierid">Linea de transporte <span style="color: red">*</span></label>
            <select name="carrierid" class="form-control select2">
                @foreach($carriers as $model)
                    <option value="{{$model->id}}"
                            @if($model->id == $storagelabeltruck->carrierid)
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
            <label for="boxnumber">Placa de caja
                @error("boxnumber")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('boxnumber') is-invalid @enderror"
                   required id="boxnumber" name="boxnumber"
                   aria-describedby="boxnumber" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{$storagelabeltruck->boxnumber}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="econumber">Número de caja
                @error("econumber")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('econumber') is-invalid @enderror"
                   required id="econumber" name="econumber" maxlength="19"
                   aria-describedby="econumber" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{$storagelabeltruck->econumber}}"
            >
        </div>
    </div>
</div>

@if($storagelabeltruck->typeid == 'BX')
    <div class="row">
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="unittypeid">Origen<span style="color: red">*</span></label>
                <select name="unittypeid" id="unittypeid" class="form-control select2">
                    @foreach($unittypes as $model)
                        <option value="{{$model->id}}"
                                @if($model->id == $storagelabeltruck->unittypeid)
                                    selected="selected"
                            @endif>
                            {{$model->description}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div id="truckdiv">
                <div class="form-group">
                    <label for="truckid">Capacidad de unidad<span style="color: red">*</span></label>
                    <select name="truckid" id="truckid" class="form-control select2">
                        @foreach($trucks as $model)
                            <option value="{{$model->id}}"
                                    @if($model->id == $storagelabeltruck->truckid)
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
@endif
