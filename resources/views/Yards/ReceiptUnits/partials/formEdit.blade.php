<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="operatorname">Nombre de operador </label>
            <input type="text" class="form-control" id="operatorname" required name="operatorname" maxlength="100"
                   aria-describedby="auxiliary01" value="{{$receiptunit->operatorname}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="drivernumber">Número de licencia </label>
            <input type="text" class="form-control" id="drivernumber" required name="drivernumber" maxlength="19"
                   aria-describedby="auxiliary01" value="{{$receiptunit->drivernumber}}" minlength="1"
                   onKeyUp=this.value=this.value.toUpperCase()>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="phone">Número de celular </label>
            <input type="text" class="form-control numeric" required id="phone" name="phone" maxlength="10"
                   aria-describedby="auxiliary01" value="{{$receiptunit->phone}}" minlength="1"
                   onKeyUp=this.value=this.value.toUpperCase()>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="carrierid">Linea de transporte <span style="color: red">*</span></label>
            <select required name="carrierid" id="carrierid" class="form-control select2">
                @foreach($carriers as $model)
                    <option value="{{$model->id}}"
                            @if($model->id == $receiptunit->carrierid)
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
            <label for="boxnumber">Placa de caja </label>
            <input type="text" class="form-control" required id="boxnumber" name="boxnumber" maxlength="10"
                   aria-describedby="auxiliary01" value="{{$receiptunit->boxnumber}}"
                   onKeyUp=this.value=this.value.toUpperCase()>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="econumber">Economico de caja </label>
            <input type="text" class="form-control " required id="econumber" name="econumber" maxlength="19"
                   aria-describedby="auxiliary01" value="{{$receiptunit->econumber}}"
                   onKeyUp=this.value=this.value.toUpperCase()>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="trucknumber">Placa de tracto </label>
            <input type="text" class="form-control " required id="trucknumber" name="trucknumber" maxlength="10"
                   aria-describedby="auxiliary01" value="{{$receiptunit->trucknumber}}"
                   onKeyUp=this.value=this.value.toUpperCase()>
        </div>
    </div>
</div>
{{--<div class="row">--}}
{{--    <div class="col-lg-2 col-md-6 col-sm-12">--}}
{{--        {!! Field::select('razonstatusid',$movents,['class' => 'select2', 'required']) !!}--}}
{{--    </div>--}}
{{--    <div class="col-lg-2 col-md-6 col-sm-12">--}}
{{--        {!! Field::select('truckid',$trucks,['class' => 'select2', 'required']) !!}--}}
{{--    </div>--}}
{{--    <div class="col-lg-2 col-md-6 col-sm-12">--}}
{{--        {!! Field::select('truckcategoryid',$truckcategories,['class' => 'select2','required']) !!}--}}
{{--    </div>--}}
{{--</div>--}}
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="containerseal">Sellos de caja </label>
            <input type="text" class="form-control " id="containerseal" name="containerseal" maxlength="15"
                   aria-describedby="auxiliary01" value="{{$receiptunit->containerseal}}"
                   onKeyUp=this.value=this.value.toUpperCase()>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="shipmentid">Número de embarque </label>
            <input type="text" class="form-control " id="shipmentid" name="shipmentid" maxlength="15"
                   aria-describedby="auxiliary01" value="{{$receiptunit->shipmentid}}"
                   onKeyUp=this.value=this.value.toUpperCase()>
        </div>
    </div>
</div>
