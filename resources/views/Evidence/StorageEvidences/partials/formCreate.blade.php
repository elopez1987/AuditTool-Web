<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="razonstatusid">
                Conceptos de ajuste
                @error("razonstatusid")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <select name="razonstatusid" id="razonstatusid" required class="form-control select2 @error('razonstatusid') is-invalid @enderror">
                @foreach($types as $model)
                    <option value="{{$model->id}}"> {{$model->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="stockcategoryid">Categoria <span style="color: red">*</span></label>
            <select name="stockcategoryid" id="stockcategoryid" required class="form-control select2 @error('stockcategoryid') is-invalid @enderror">
                @foreach($categories as $model)
                    <option value="{{$model->id}}"> {{$model->description}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div id="customer">
            <div class="form-group">
                <label for="customerid">Cliente <span style="color: red">*</span></label>
                <select name="customerid" id="customerid" required class="form-control select2 @error('customerid') is-invalid @enderror">
                    @foreach($customers as $model)
                        <option value="{{$model->id}}"> {{$model->name}} </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div id="provider" class="hide">
            <div class="form-group">
                <label for="providerid">Proveedor <span style="color: red">*</span></label>
                <select name="providerid" id="providerid" required class="form-control  select2 @error('providerid') is-invalid @enderror">
                    @foreach($providers as $model)
                        <option value="{{$model->id}}"> {{$model->name}} </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="shipmentid">Embarque / Cargo <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('shipmentid') is-invalid @enderror" required id="shipmentid" name="shipmentid"
                   aria-describedby="shipmentid" onKeyUp=this.value=this.value.toUpperCase(); maxlength="10"
                   value="{{old('shipmentid')}}">
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="turnid">Turno <span style="color: red">*</span></label>
            <select name="turnid" id="turnid" required class="form-control select2 @error('turnid') is-invalid @enderror">
                @foreach($turns as $model)
                    <option value="{{$model->id}}"> {{$model->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="workday">Fecha de trabajo <span style="color: red">*</span></label>
            <input type="text" class="form-control workday @error('workday') is-invalid @enderror" required id="workday" name="workday"
                   aria-describedby="workday" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('workday')}}">
        </div>
    </div>
{{--    <div class="col-lg-2 col-md-6 col-sm-12">--}}
{{--        <div class="form-group">--}}
{{--            <label for="appointment">Fecha de cita <span style="color: red">*</span></label>--}}
{{--            <input type="text" class="form-control datefilter @error('appointment') is-invalid @enderror" required id="appointment" name="appointment"--}}
{{--                   aria-describedby="appointment" onKeyUp=this.value=this.value.toUpperCase();--}}
{{--                   value="{{old('appointment')}}">--}}
{{--        </div>--}}
{{--    </div>--}}
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="locationid">Ubicación <span style="color: red">*</span></label>
            <select name="locationid" id="locationid" required class="form-control select2 @error('locationid') is-invalid @enderror">
                @foreach($locations as $model)
                    <option value="{{$model->id}}"> {{$model->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="carrierid">Linea de transporte <span style="color: red">*</span></label>
            <select name="carrierid" id="carrierid" required class="form-control select2 @error('carrierid') is-invalid @enderror">
                @foreach($carriers as $model)
                    <option value="{{$model->id}}"> {{$model->name}} </option>
                @endforeach
            </select>
        </div>

    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="boxnumber">Placa de caja</label>
            <input type="text" class="form-control @error('boxnumber') is-invalid @enderror"  id="boxnumber" name="boxnumber"
                   aria-describedby="boxnumber" onKeyUp=this.value=this.value.toUpperCase(); maxlength="10"
                   value="{{old('boxnumber')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="econumber">Eco. De caja</label>
            <input type="text" class="form-control @error('econumber') is-invalid @enderror"  id="econumber" name="econumber"
                   aria-describedby="econumber" onKeyUp=this.value=this.value.toUpperCase(); maxlength="9"
                   value="{{old('econumber')}}">
        </div>
    </div>
</div>
{{--<div class="row">--}}
{{--    <div class="col-lg-2 col-md-6 col-sm-12">--}}
{{--        <div class="form-group">--}}
{{--            <label for="name">Auxiliar 01 </label>--}}
{{--            <input type="text" class="form-control" id="auxiliary01" name="auxiliary01" maxlength="100"--}}
{{--                   aria-describedby="auxiliary01" value="{{old('auxiliary01')}}">--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="col-lg-2 col-md-6 col-sm-12">--}}
{{--        <div class="form-group">--}}
{{--            <label for="name">Auxiliar 02 </label>--}}
{{--            <input type="text" class="form-control" id="auxiliary02" name="auxiliary02" maxlength="100"--}}
{{--                   aria-describedby="auxiliary02" value="{{old('auxiliary02')}}">--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="col-lg-2 col-md-6 col-sm-12">--}}
{{--        <div class="form-group">--}}
{{--            <label for="name">Auxiliar 03 </label>--}}
{{--            <input type="text" class="form-control" id="auxiliary03" name="auxiliary03" maxlength="100"--}}
{{--                   aria-describedby="auxiliary03" value="{{old('auxiliary03')}}">--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<div class="row">--}}
{{--    <div class="col-lg-2 col-md-6 col-sm-12">--}}
{{--        <div class="form-group">--}}
{{--            <label for="name">Auxiliar 04 </label>--}}
{{--            <input type="text" class="form-control" id="auxiliary04" name="auxiliary04" maxlength="100"--}}
{{--                   aria-describedby="auxiliary04" value="{{old('auxiliary04')}}">--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="col-lg-2 col-md-6 col-sm-12">--}}
{{--        <div class="form-group">--}}
{{--            <label for="name">Auxiliar 05 </label>--}}
{{--            <input type="text" class="form-control" id="auxiliary05" name="auxiliary05" maxlength="100"--}}
{{--                   aria-describedby="auxiliary05" value="{{old('auxiliary05')}}">--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
