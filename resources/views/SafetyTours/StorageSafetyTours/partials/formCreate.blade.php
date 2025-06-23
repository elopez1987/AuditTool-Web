<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Nombre del recorrido
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   required id="name" name="name"
                   aria-describedby="name" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('name')}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="datetours">Fecha del recorrido
                <span style="color: red">*</span></label>
            <input type="text" class="form-control workday @error('datetours') is-invalid @enderror"
                   required id="datetours" name="datetours"
                   aria-describedby="datetours" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('datetours')}}"
            >
        </div>
    </div>

</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="typeid">Tipo<span style="color: red">*</span></label>
            <select name="typeid" id="typeid" class="form-control select2">
                @foreach($types as $model)
                    <option value="{{$model->id}}"> {{$model->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="sequence">Secuencia
                @error("sequence")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="number" class="form-control numeric @error('sequence') is-invalid @enderror"
                   required id="sequence" name="sequence"
                   aria-describedby="sequence"
                   value="{{old('sequence')}}"
            >
        </div>
    </div>
</div>

