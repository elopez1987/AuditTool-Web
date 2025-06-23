<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="center">Centro de distribución <span style="color: red">*</span></label>
            <input type="text" class="form-control" id="center" name="center"
                   value="{{$securitymenustructure->center ? $securitymenustructure->center->name : ''}}"
                   disabled
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="menustrucid">Nombre <span style="color: red">*</span></label>
            <input type="text" class="form-control" id="menustrucid" name="menustrucid" readonly
                   value="{{$securitymenustructure->menustrucid}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="description">Descripción <span style="color: red">*</span></label>
            <input type="text" class="form-control" id="description" name="description"
                   value="{{$securitymenustructure->description}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="statusid">Estatus <span style="color: red">*</span></label>
            <select name="statusid" class="form-control select2">
                @foreach($status as $statu)
                    <option value="{{$statu->statusid}}"
                            @if($statu->statusid == $securitymenustructure->statusid)
                                selected="selected"
                        @endif>
                        {{$statu->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
