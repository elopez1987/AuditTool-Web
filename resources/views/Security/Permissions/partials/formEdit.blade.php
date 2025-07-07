<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="moduleid">Modulo <span style="color: red">*</span></label>
            <select required name="moduleid" id="moduleid" class="form-control select2">
                @foreach($modules as $model)
                    <option value="{{$model->moduleid}}"
                            @if($model->moduleid == $securitypermission->moduleid)
                                selected="selected"
                        @endif>
                        {{$model->description}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="functionid">Función <span style="color: red">*</span></label>
            <select required name="functionid" id="functionid" class="form-control select2">
                @foreach($functions as $model)
                    <option value="{{$model->functionid}}"
                            @if($model->functionid == $securitypermission->functionid)
                                selected="selected"
                        @endif>
                        {{$model->description}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Nombre del permiso
                @error("name")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   required id="name" name="name" maxlength="20" aria-describedby="name"
                   value="{{$securitypermission->name}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="description">Descripción
                @error("description")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('description') is-invalid @enderror"
                   required id="description" name="description"
                   aria-describedby="description" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{$securitypermission->description}}"
            >
        </div>
    </div>
</div>



