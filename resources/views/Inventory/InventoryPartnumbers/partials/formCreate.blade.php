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
<div class="row">
    <div class="col-lg-6">
        <hr class="mt-1">
        <span style="color: #cfdd01;font-size: 14px;font-weight: 600">Categorización</span>
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
<div class="row mb-3">
    <div class="col-lg-6">
        <hr class="mt-1">
        <span style="color: #cfdd01;font-size: 14px;font-weight: 600">Datos generales</span>
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
            <label for="stkuom">Unidad de medida <span style="color: red">*</span></label>
            <select name="stkuom" required
                    class="form-control select2 @error('stkuom') is-invalid @enderror">
                @foreach($units as $model)
                    <option value="{{$model->unitmeasureid}}"> {{$model->description}} </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="shelflife">Tiempo de vida
                @error("shelflife")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <input type="text" class="form-control numeric @error('shelflife') is-invalid @enderror"
                   id="shelflife" name="shelflife"
                   aria-describedby="shelflife" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('shelflife')}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="abccod">Rotación
                @error("abccod")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <input type="text" class="form-control @error('abccod') is-invalid @enderror"
                    id="abccod" name="abccod" maxlength="1"
                   aria-describedby="abccod" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('abccod')}}"
            >
        </div>
    </div>
</div>


<div class="row mb-3">
    <div class="col-lg-6">
        <hr class="mt-1">
        <span style="color: #cfdd01;font-size: 14px;font-weight: 600">Detalle del pallet</span>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="untcas">Unidades por Caja
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
            <label for="untpal">Cajas por PAL
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
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="caslvl">Cajas por Cama
                @error("caslvl")
                <span class="message-error">{{$message}}</span>
                @enderror
                </label>
            <input type="text" class="form-control @error('caslvl') is-invalid @enderror"
                    id="caslvl" name="caslvl"
                   aria-describedby="caslvl"
                   value="{{old('caslvl')}}"
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="palnetwgt">Peso Neto PAL
                @error("palnetwgt")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <input type="text" class="form-control @error('palnetwgt') is-invalid @enderror"
                   id="palnetwgt" name="palnetwgt"
                   aria-describedby="palnetwgt"
                   value="{{old('palnetwgt')}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="palgrswgt">Peso Bruto PAL
                @error("palgrswgt")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <input type="text" class="form-control @error('palgrswgt') is-invalid @enderror"
                   id="palgrswgt" name="palgrswgt"
                   aria-describedby="palgrswgt"
                   value="{{old('palgrswgt')}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="palwid">Ancho de PAL
                @error("palwid")
                <span class="message-error">{{$message}}</span>
                @enderror
                </label>
            <input type="text" class="form-control numeric @error('palwid') is-invalid @enderror"
                    id="palwid" name="palwid"
                   aria-describedby="palwid" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('palwid')}}"
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="pallen">Largo de PAL
                @error("pallen")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <input type="text" class="form-control numeric @error('pallen') is-invalid @enderror"
                    id="pallen" name="pallen"
                   aria-describedby="pallen"
                   value="{{old('pallen')}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="palhgt">Alto de PAL
                @error("palhgt")
                <span class="message-error">{{$message}}</span>
                @enderror
             </label>
            <input type="text" class="form-control numeric @error('palhgt') is-invalid @enderror"
                    id="palhgt" name="palhgt"
                   aria-describedby="palhgt"
                   value="{{old('palhgt')}}"
            >
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-lg-6">
        <hr class="mt-1">
        <span style="color: #cfdd01;font-size: 14px;font-weight: 600">Auxiliares</span>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary01">Auxiliar 01
                @error("auxiliary01")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <input type="text" class="form-control @error('auxiliary01') is-invalid @enderror"
                   id="auxiliary01" name="auxiliary01" maxlength="100"
                   aria-describedby="auxiliary01" value="{{old('auxiliary01')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary02">Auxiliar 02 </label>
            <input type="text" class="form-control" id="auxiliary02" name="auxiliary02" maxlength="100"
                   aria-describedby="auxiliary02" value="{{old('auxiliary02')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary03">Auxiliar 03 </label>
            <input type="text" class="form-control" id="auxiliary03" name="auxiliary03" maxlength="100"
                   aria-describedby="auxiliary03" value="{{old('auxiliary03')}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary04">Auxiliar 04 </label>
            <input type="text" class="form-control" id="auxiliary04" name="auxiliary04" maxlength="100"
                   aria-describedby="auxiliary04" value="{{old('auxiliary04')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary05">Auxiliar 05 </label>
            <input type="text" class="form-control" id="auxiliary05" name="auxiliary05" maxlength="100"
                   aria-describedby="auxiliary05" value="{{old('auxiliary05')}}">
        </div>
    </div>
</div>
<div class="row">
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
