<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerid">Centro de distribucion <span style="color: red">*</span></label>
            <input type="text" class="form-control" disabled id="centerid" name="centerid"
                   value="{{$securityrole->center ? $securityrole->center->name : ''}}"
            >
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="menustrucid">Estructura de menu <span style="color: red">*</span></label>
            <select name="menustrucid" class="form-control select2">
                @foreach($menus as $menu)
                    <option value="{{$menu->menustrucid}}"
                            @if($menu->menustrucid == $securityrole->menustrucid)
                                selected="selected"
                        @endif>
                        {{$menu->description}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Nombre del perfil <span style="color: red">*</span></label>
            <input type="text" class="form-control" readonly id="name" name="name"
                   value="{{$securityrole->name}}"
            >
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="description">Descripción del perfil <span style="color: red">*</span></label>
            <input type="text" class="form-control" required id="description" name="description"
                   value="{{$securityrole->description}}"
            >
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="statusid">Estatus <span style="color: red">*</span></label>
            <select name="statusid" class="form-control select2">
                @foreach($status as $statu)
                    <option value="{{$statu->statusid}}"
                            @if($statu->statusid == $securityrole->statusid)
                                selected="selected"
                        @endif>
                        {{$statu->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<hr class="mt-3 text-black-50">

