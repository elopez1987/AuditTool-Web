
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="shipmentid">Embarque
                @error("shipmentid")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('shipmentid') is-invalid @enderror"
                   required id="shipmentid" name="shipmentid"
                   aria-describedby="shipmentid" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('shipmentid')}}"
            >
        </div>
    </div>

</div>
