<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="schemaid">Esquema
                @error("schemaid")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span>
            </label>
            <input type="text" class="form-control" id="schemaid" name="schemaid" maxlength="10"
                   aria-describedby="schemaid" value="{{old('schemaid')}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Nombre
                <span style="color: red">*</span>
            </label>
            <input type="text" class="form-control" id="name" name="name" maxlength="100"
                   aria-describedby="name" value="{{old('name')}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>