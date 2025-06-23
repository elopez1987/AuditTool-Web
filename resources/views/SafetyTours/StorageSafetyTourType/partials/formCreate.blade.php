<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Nombre
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   required id="name" name="name"
                   aria-describedby="name" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{old('name')}}"
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
            <input type="text" class="form-control  @error('value01') is-invalid @enderror"
                    id="value01" name="value01"
                   aria-describedby="value01"
                   value="{{old('value01')}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="value02">Auxiliar 02
                @error("value02")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <input type="text" class="form-control  @error('value02') is-invalid @enderror"
                    id="value02" name="value02"
                   aria-describedby="value02"
                   value="{{old('value02')}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="value03">Auxiliar 03
                @error("value03")
                <span class="message-error">{{$message}}</span>
                @enderror
            </label>
            <input type="text" class="form-control  @error('value03') is-invalid @enderror"
                   id="value03" name="value03"
                   aria-describedby="value03"
                   value="{{old('value03')}}"
            >
        </div>
    </div>
</div>

