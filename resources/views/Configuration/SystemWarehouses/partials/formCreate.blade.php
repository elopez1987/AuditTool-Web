<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerid">Centros de distribución <span style="color: red">*</span></label>
            <select name="centerid" class="form-control select2">
                @foreach($centers as $center)
                    <option
                        value="{{$center->centerid}}" {{ old('centerid') == $center->centerid ? 'selected' : '' }} > {{$center->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="description">Bodega <span style="color: red">*</span></label>
            <input type="text" class="form-control" required id="warehouseid" name="warehouseid" maxlength="15"
                   aria-describedby="warehouseid" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('warehouseid')}}">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Nombre <span style="color: red">*</span></label>
            <input type="text" class="form-control" required id="name" name="name" maxlength="50"
                   aria-describedby="name" onKeyUp=this.value=this.value.toUpperCase(); value="{{old('name')}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="center">Centro de distribución WMS <span style="color: red">*</span></label>
            <input type="text" class="form-control" id="center" name="center" maxlength="30"
                   value="{{old('center')}}" aria-describedby="center">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="warehouse">Bodega WMS <span style="color: red">*</span></label>
            <input type="text" class="form-control" id="warehouse" name="warehouse" maxlength="30"
                   value="{{old('warehouse')}}" aria-describedby="warehouse">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 01 </label>
            <input type="text" class="form-control" id="auxiliary01" name="auxiliary01" maxlength="100"
                   aria-describedby="auxiliary01" value="{{old('auxiliary01')}}">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 02 </label>
            <input type="text" class="form-control" id="auxiliary02" name="auxiliary02" maxlength="100"
                   aria-describedby="auxiliary02" value="{{old('auxiliary02')}}">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 03 </label>
            <input type="text" class="form-control" id="auxiliary03" name="auxiliary03" maxlength="100"
                   aria-describedby="auxiliary03" value="{{old('auxiliary03')}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 04 </label>
            <input type="text" class="form-control" id="auxiliary04" name="auxiliary04" maxlength="100"
                   aria-describedby="auxiliary04" value="{{old('auxiliary04')}}">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 05 </label>
            <input type="text" class="form-control" id="auxiliary05" name="auxiliary05" maxlength="100"
                   aria-describedby="auxiliary05" value="{{old('auxiliary05')}}">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 06 </label>
            <input type="text" class="form-control" id="auxiliary06" name="auxiliary06" maxlength="100"
                   aria-describedby="auxiliary06" value="{{old('auxiliary06')}}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 07 </label>
            <input type="text" class="form-control" id="auxiliary07" name="auxiliary07" maxlength="100"
                   aria-describedby="auxiliary07" value="{{old('auxiliary07')}}">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 08 </label>
            <input type="text" class="form-control" id="auxiliary08" name="auxiliary08" maxlength="100"
                   aria-describedby="auxiliary08" value="{{old('auxiliary08')}}">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 09 </label>
            <input type="text" class="form-control" id="auxiliary09" name="auxiliary09" maxlength="100"
                   aria-describedby="auxiliary0" value="{{old('auxiliary09')}}">
        </div>
    </div>
</div>
