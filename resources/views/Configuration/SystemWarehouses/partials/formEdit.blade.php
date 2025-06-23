<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="description">Centro de distribución <span style="color: red">*</span></label>
            <input type="text" class="form-control" required id="centerid" name="centerid" maxlength="15"
                   aria-describedby="centerid" readonly onKeyUp=this.value=this.value.toUpperCase();
                   value="{{$systemwarehouse->centerdistribution ? $systemwarehouse->centerdistribution->name : ''}}">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="description">Bodega <span style="color: red">*</span></label>
            <input type="text" class="form-control" readonly required id="warehouseid" name="warehouseid" maxlength="15"
                   aria-describedby="warehouseid" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{$systemwarehouse->warehouseid}}">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Nombre <span style="color: red">*</span></label>
            <input type="text" class="form-control" required id="name" name="name" maxlength="50"
                   aria-describedby="name" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{$systemwarehouse->name}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="center">Centro de distribución WMS <span style="color: red">*</span></label>
            <input type="text" class="form-control"  id="center" name="center" maxlength="15"
                   value="{{$systemwarehouse->center}}" aria-describedby="center">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="warehouse">Bodega WMS <span style="color: red">*</span></label>
            <input type="text" class="form-control"  id="warehouse" name="warehouse" maxlength="30"
                   value="{{$systemwarehouse->warehouse}}" aria-describedby="warehouse">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 01 </label>
            <input type="text" class="form-control" id="auxiliary01" name="auxiliary01" maxlength="100"
                   aria-describedby="auxiliary01" value="{{$systemwarehouse->auxiliary01}}">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 02 </label>
            <input type="text" class="form-control" id="auxiliary02" name="auxiliary02" maxlength="100"
                   aria-describedby="auxiliary02" value="{{$systemwarehouse->auxiliary02}}">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 03 </label>
            <input type="text" class="form-control" id="auxiliary03" name="auxiliary03" maxlength="100"
                   aria-describedby="auxiliary03" value="{{$systemwarehouse->auxiliary03}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 04 </label>
            <input type="text" class="form-control" id="auxiliary04" name="auxiliary04" maxlength="100"
                   aria-describedby="auxiliary04" value="{{$systemwarehouse->auxiliary04}}">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 05 </label>
            <input type="text" class="form-control" id="auxiliary05" name="auxiliary05" maxlength="100"
                   aria-describedby="auxiliary05" value="{{$systemwarehouse->auxiliary05}}">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 06 </label>
            <input type="text" class="form-control" id="auxiliary06" name="auxiliary06" maxlength="100"
                   aria-describedby="auxiliary06" value="{{$systemwarehouse->auxiliary06}}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 07 </label>
            <input type="text" class="form-control" id="auxiliary07" name="auxiliary07" maxlength="100"
                   aria-describedby="auxiliary07" value="{{$systemwarehouse->auxiliary07}}">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 08 </label>
            <input type="text" class="form-control" id="auxiliary08" name="auxiliary08" maxlength="100"
                   aria-describedby="auxiliary08" value="{{$systemwarehouse->auxiliary08}}">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Logotipo de la empresa </label>
            <input type="file" class="form-control" id="file" name="file" maxlength="100"
                     value="{{$systemwarehouse->auxiliary09}}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerid">Estatus <span style="color: red">*</span></label>
            <select name="centerid" class="form-control select2">
                @foreach($status as $statu)
                    <option value="{{$statu->statusid}}"
                        @if($statu->statusid == $systemwarehouse->statusid)
                                selected="selected"
                        @endif>
                        {{$statu->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

@error('warehouseid', 'name','center')
<div class="alert alert-danger">{{ $message }}</div>
@enderror
