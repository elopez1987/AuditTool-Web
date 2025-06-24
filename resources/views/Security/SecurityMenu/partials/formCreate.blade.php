<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerid">Centro de distribución <span style="color: red">*</span></label>
            <select name="centerid" class="form-control select2">
                @foreach($centers as $center)
                    <option value="{{$center->centerid}}"> {{$center->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="menustrucid">Nombre <span style="color: red">*</span></label>
            <input type="text" class="form-control" id="menustrucid" name="menustrucid"
                   value="{{old('menustrucid')}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="description">Descripción <span style="color: red">*</span></label>
            <input type="text" class="form-control" id="description" name="description"
                   value="{{old('description')}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>




