<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Nombre del recorrido
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   required id="name" name="name"
                   aria-describedby="name" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{$tour->name}}"
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
                   value="{{$tour->datetours}}"
            >
        </div>
    </div>

</div>
<div class="row">
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
                   value="{{$tour->sequence}}"
            >
        </div>
    </div>
</div>

