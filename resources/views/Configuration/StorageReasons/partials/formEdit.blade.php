<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" id="name" required name="name" maxlength="50"
                   aria-describedby="name" value="{{$storagereason->name}}" onKeyUp=this.value=this.value.toUpperCase()>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="razonstatusid">Evento WMS</label>
            <input type="text" class="form-control" id="razonstatusid" required name="razonstatusid" maxlength="10"
                   aria-describedby="razonstatusid" value="{{$storagereason->razonstatusid}}"
                   onKeyUp=this.value=this.value.toUpperCase()>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="type">Tipo de movimiento</label>
            <input type="text" class="form-control" id="type" required name="type" maxlength="10"
                   aria-describedby="type" value="{{$storagereason->type}}" onKeyUp=this.value=this.value.toUpperCase()>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 01 </label>
            <input type="text" class="form-control" id="auxiliary01" name="auxiliary01" maxlength="100"
                   aria-describedby="auxiliary01" value="{{$storagereason->auxiliary01}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 02 </label>
            <input type="text" class="form-control" id="auxiliary02" name="auxiliary02" maxlength="100"
                   aria-describedby="auxiliary02" value="{{$storagereason->auxiliary02}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 03 </label>
            <input type="text" class="form-control" id="auxiliary03" name="auxiliary03" maxlength="100"
                   aria-describedby="auxiliary03" value="{{$storagereason->auxiliary03}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 04 </label>
            <input type="text" class="form-control" id="auxiliary04" name="auxiliary04" maxlength="100"
                   aria-describedby="auxiliary04" value="{{$storagereason->auxiliary04}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 05 </label>
            <input type="text" class="form-control" id="auxiliary05" name="auxiliary05" maxlength="100"
                   aria-describedby="auxiliary05" value="{{$storagereason->auxiliary05}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 06 </label>
            <input type="text" class="form-control" id="auxiliary06" name="auxiliary06" maxlength="100"
                   aria-describedby="auxiliary06" value="{{$storagereason->auxiliary06}}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="statusid">Estatus <span style="color: red">*</span></label>
            <select name="statusid" class="form-control select2">
                @foreach($status as $statu)
                    <option value="{{$statu->statusid}}"
                            @if($statu->statusid == $storagereason->statusid)
                                selected="selected"
                        @endif>
                        {{$statu->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
