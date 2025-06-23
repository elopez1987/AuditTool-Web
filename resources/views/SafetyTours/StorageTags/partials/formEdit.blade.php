<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="tagid">Tad<span style="color: red">*</span></label>
            <input type="text" class="form-control @error('tagid') is-invalid @enderror"
                   required id="tagid" name="tagid"
                   aria-describedby="tagid" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{$tag->tagid}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Ubicación del tag
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   required id="name" name="name"
                   aria-describedby="name" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{$tag->name}}"
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="latitude">Latitud
                @error("latitude")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control  @error('latitude') is-invalid @enderror"
                   required id="latitude" name="latitude"
                   aria-describedby="latitude"
                   value="{{$tag->latitude}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="longitude">Longitud
                @error("longitude")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('longitude') is-invalid @enderror"
                   required id="longitude" name="longitude"
                   aria-describedby="longitude"
                   value="{{$tag->longitude}}"
            >
        </div>
    </div>
</div>

