<div class="row mt-4">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="moduleid">Modulo<span style="color: red">*</span>
                @error("moduleid")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <select name="moduleid" id='moduleid' required class="form-control select2 @error('moduleid') is-invalid @enderror">
                <option value="" selected>Seleccione un valor</option>
                @foreach($modules as $model)
                    <option value="{{$model->moduleid}}"> {{$model->description}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="functionid">Función<span style="color: red">*</span>
                @error("functionid")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <select name="functionid" id='functionid' required class="form-control select2 @error('functionid') is-invalid @enderror">
                <option value="" >Seleccione un valor</option>
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
                   required id="name" name="name" maxlength="15"
                   aria-describedby="name" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('name')}}"
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
                   value="{{old('description')}}"
            >
        </div>
    </div>
</div>

