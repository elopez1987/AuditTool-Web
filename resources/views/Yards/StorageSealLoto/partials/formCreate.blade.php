<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerid">Centros de distribución <span style="color: red">*</span></label>
            <select name="centerid" id="centerid" class="form-control select2 @error('centerid') is-invalid @enderror">
                @foreach($centers as $center)
                    <option value="{{$center->centerid}}" {{ old('centerid') == $center->centerid ? 'selected' : '' }}> {{$center->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="description">Prefijo
                @error("description")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('description') is-invalid @enderror"
                   required id="description" name="description" max="50"
                    onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('description')}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="quantity">Cantidad de candados
                @error("quantity")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control numeric @error('quantity') is-invalid @enderror"
                   required id="quantity" name="quantity" maxlength="3"
                   value="{{old('quantity')}}"
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="value01">Auxiliar 01
                @error("value01")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <input type="text" class="form-control @error('value01') is-invalid @enderror" id="value01" name="value01" maxlength="100" value="{{old('value01')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="value02">Auxiliar 02
                @error("value02")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <input type="text" class="form-control @error('value02') is-invalid @enderror" id="value02" name="value02" maxlength="100" value="{{old('value02')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="value03">Auxiliar 03
                @error("value03")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <input type="text" class="form-control @error('value03') is-invalid @enderror" id="value03" name="value03" maxlength="100" value="{{old('value03')}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="value04">Auxiliar 04
                @error("value04")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <input type="text" class="form-control @error('value04') is-invalid @enderror" id="value04" name="value04" maxlength="100" value="{{old('value04')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="value05">Auxiliar 05
                @error("value05")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <input type="text" class="form-control @error('value05') is-invalid @enderror" id="value05" name="value05" maxlength="100" value="{{old('value05')}}">
        </div>
    </div>
</div>
