<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="description">Nombre del sello
                @error("description")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('description') is-invalid @enderror"
                   required id="description" name="description" max="50"
                   aria-describedby="description" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{$storagesealloto->description}}"
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="value01">Auxiliar 01
                @error("value01")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <input type="text" class="form-control @error('value01') is-invalid @enderror"
                   id="value01" name="value01" maxlength="100"
                   aria-describedby="value01" value="{{$storagesealloto->value01}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="value02">Auxiliar 02 </label>
            <input type="text" class="form-control" id="value02" name="value02" maxlength="100"
                   aria-describedby="value02" value="{{$storagesealloto->value02}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="value03">Auxiliar 03 </label>
            <input type="text" class="form-control" id="value03" name="value03" maxlength="100"
                   aria-describedby="value03" value="{{$storagesealloto->value03}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="value04">Auxiliar 04 </label>
            <input type="text" class="form-control" id="value04" name="value04" maxlength="100"
                   aria-describedby="value04" value="{{$storagesealloto->value04}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="value05">Auxiliar 05 </label>
            <input type="text" class="form-control" id="value05" name="value05" maxlength="100"
                   aria-describedby="value05" value="{{$storagesealloto->value05}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="value06">Auxiliar 06 </label>
            <input type="text" class="form-control" id="value06" name="value06" maxlength="100"
                   aria-describedby="value06" value="{{$storagesealloto->value06}}">
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
                            @if($statu->statusid == $storagesealloto->statusid)
                                selected="selected"
                        @endif>
                        {{$statu->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
