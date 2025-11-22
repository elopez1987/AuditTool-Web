<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="prtnum">Número de parte
                @error("prtnum")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('prtnum') is-invalid @enderror"
                   required id="prtnum" name="prtnum"
                   aria-describedby="prtnum" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('prtnum')}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="lngdsc">Descripción
                @error("lngdsc")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('lngdsc') is-invalid @enderror"
                   required id="lngdsc" name="lngdsc"
                   aria-describedby="lngdsc" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('lngdsc')}}"
            >
        </div>
    </div>
</div>
<div class="row mt-2">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="category01id">Categoria de inventario 01 <span style="color: red">*</span></label>
            <select name="category01id" required
                    class="form-control select2 @error('category01id') is-invalid @enderror">
                @foreach($category01 as $model)
                    <option value="{{$model->id}}"> {{$model->description}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="category02id">Categoria de inventario 02 <span style="color: red">*</span></label>
            <select name="category02id" required
                    class="form-control select2 @error('category02id') is-invalid @enderror">
                @foreach($category02 as $model)
                    <option value="{{$model->id}}"> {{$model->description}} </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="stkuom">Unidad de medida <span style="color: red">*</span></label>
            <select name="stkuom" required
                    class="form-control select2 @error('stkuom') is-invalid @enderror">
                @foreach($units as $model)
                    <option value="{{$model->unitmeasureid}}"> {{$model->description}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="shelflife">Tiempo de vida <span style="color: red">*</span>
                @error("shelflife")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <input type="text" class="form-control numeric @error('shelflife') is-invalid @enderror"
                   id="shelflife" name="shelflife" required value="{{old('shelflife')}}"
            >
        </div>
    </div>
</div>
<div class="row">

</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="untcas">Piezas por caja
                @error("untcas")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control numeric @error('untcas') is-invalid @enderror"
                   id="untcas" name="untcas" required
                   aria-describedby="untcas" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('untcas')}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="untpal">Cajas por pallet
                @error("untpal")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control numeric @error('untpal') is-invalid @enderror"
                   id="untpal" name="untpal" required
                   aria-describedby="untpal" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('untpal')}}"
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
                   value="{{old('barcode')}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="codeean">EAN-13 </label>
            <input type="text" class="form-control" id="codeean" name="codeean" maxlength="20"
                   aria-describedby="codeean" value="{{old('codeean')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="codedun">DUN-14 </label>
            <input type="text" class="form-control" id="codedun" name="codedun" maxlength="100"
                   aria-describedby="codedun" value="{{old('codedun')}}">
        </div>
    </div>
</div>
