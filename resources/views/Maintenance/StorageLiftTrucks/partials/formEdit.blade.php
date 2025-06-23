<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="serialnumber">Número de serie</label>
            <input type="text" class="form-control" value="{{$liftTruck->serialnumber}}" readonly id="serialnumber"
                   name="serialnumber">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="numbertruck">Número interno</label>
            <input type="text" class="form-control" value="{{$liftTruck->numbertruck}}" id="numbertruck"
                   name="numbertruck">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="typeid">Tipo de equipo <span style="color: red">*</span></label>
            <select name="typeid" class="form-control select2">
                @foreach($typesLiftTrucks as $statu)
                    <option value="{{$statu->statusid}}"
                            @if($statu->statusid == $liftTruck->typeid)
                                selected="selected"
                        @endif>
                        {{$statu->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="brandid">Marca <span style="color: red">*</span></label>
            <select name="brandid" class="form-control select2">
                @foreach($brandLiftTrucks as $statu)
                    <option value="{{$statu->statusid}}"
                            @if($statu->statusid == $liftTruck->brandid)
                                selected="selected"
                        @endif>
                        {{$statu->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="modelid">Modelo <span style="color: red">*</span></label>
            <select name="modelid" class="form-control select2">
                @foreach($modelLiftTrucks as $statu)
                    <option value="{{$statu->statusid}}"
                            @if($statu->statusid == $liftTruck->modelid)
                                selected="selected"
                        @endif>
                        {{$statu->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary01">Auxiliar 01 </label>
            <input type="text" class="form-control" id="auxiliary01" name="auxiliary01" maxlength="100"
                   aria-describedby="auxiliary01" value="{{$liftTruck->auxiliary01}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary02">Auxiliar 02 </label>
            <input type="text" class="form-control" id="auxiliary02" name="auxiliary02" maxlength="100"
                   aria-describedby="auxiliary02" value="{{$liftTruck->auxiliary02}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary03">Auxiliar 03 </label>
            <input type="text" class="form-control" id="auxiliary03" name="auxiliary03" maxlength="100"
                   aria-describedby="auxiliary03" value="{{$liftTruck->auxiliary03}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary04">Auxiliar 04 </label>
            <input type="text" class="form-control" id="auxiliary04" name="auxiliary04" maxlength="100"
                   aria-describedby="auxiliary04" value="{{$liftTruck->auxiliary04}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary05">Auxiliar 05 </label>
            <input type="text" class="form-control" id="auxiliary05" name="auxiliary05" maxlength="100"
                   aria-describedby="auxiliary05" value="{{$liftTruck->auxiliary05}}">
        </div>
    </div>

    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="statusid">Estatus del equipo <span style="color: red">*</span></label>
            <select name="statusid" class="form-control select2">
                @foreach($status as $statu)
                    <option value="{{$statu->statusid}}"
                            @if($statu->statusid == $liftTruck->statusid)
                                selected="selected"
                        @endif>
                        {{$statu->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
