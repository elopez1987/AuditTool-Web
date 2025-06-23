<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="moduleid">Módulo <span style="color: red">*</span></label>
            <select name="moduleid" class="form-control select2">
                @foreach($modules as $module)
                    <option value="{{$module->moduleid}}"
                            @if($module->moduleid == $securityfunction->moduleid)
                                selected="selected"
                        @endif>
                        {{$module->description}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="description">Función</label>
            <input type="text" class="form-control" id="description" name="description" maxlength="100"
                   aria-describedby="description" value="{{$securityfunction->description}}">
        </div>
    </div>
</div>

