<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerid">Centro de distribución</label>
            <input type="text" class="form-control" readonly id="centerid" name="centerid"
                   onKeyUp=this.value=this.value.toUpperCase()
                   value="{{$storageoperator->center->name}}"
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="barcode">Código de barra
                @error("barcode")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('barcode') is-invalid @enderror"
                   required id="barcode" name="barcode"
                   aria-describedby="barcode" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{$storageoperator->barcode}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Nombre
                @error("name")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   required id="name" name="name"
                   aria-describedby="name" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{$storageoperator->name}}"
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
                    id="drivernumber" name="drivernumber"
                   aria-describedby="drivernumber" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{$storageoperator->drivernumber}}"
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
                   value="{{$storageoperator->phone}}"
            >
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="statusid">Estatus <span style="color: red">*</span></label>
            <select name="statusid" class="form-control select2">
                @foreach($status as $statu)
                    <option value="{{$statu->statusid}}"
                            @if($statu->statusid == $storageoperator->statusid)
                                selected="selected"
                            @endif>
                        {{$statu->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
