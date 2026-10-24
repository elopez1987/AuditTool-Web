<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerid">Centros de distribución <span style="color: red">*</span></label>
            <select name="centerid" id="centerid" class="form-control select2">
                @foreach($centers as $center)
                    <option value="{{$center->centerid}}"> {{$center->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="barcode">Clave de operador
                @error("barcode")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('barcode') is-invalid @enderror"
                   required id="barcode" name="barcode" maxlength="100"
                   aria-describedby="barcode" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('barcode')}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Nombre de operador
                @error("name")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   required id="name" name="name" maxlength="100"
                   aria-describedby="name" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('name')}}"
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="drivernumber">Número de licencia
                @error("drivernumber")
                <span class="message-error">{{$message}}</span>
                @enderror
               </label>
            <input type="text" class="form-control @error('drivernumber') is-invalid @enderror"
                    id="drivernumber" name="drivernumber" maxlength="100"
                   aria-describedby="drivernumber" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('drivernumber')}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="phone">Número de celular
                @error("phone")
                <span class="message-error">{{$message}}</span>
                @enderror
             </label>
            <input type="text" class="form-control numeric @error('phone') is-invalid @enderror"
                    id="phone" name="phone" minlength="1" maxlength="10"
                   aria-describedby="phone" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('phone')}}"
            >
        </div>
    </div>
</div>

