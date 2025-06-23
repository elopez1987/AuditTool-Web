<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerid">Centros de distribución <span style="color: red">*</span></label>
            <select name="centerid" required id="centerid" class="form-control select2">
                @foreach($centers as $center)
                    <option value="{{$center->centerid}}" {{ old('centerid') == $center->centerid ? 'selected' : '' }} > {{$center->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="statustypeid">Tipo de inventario <span style="color: red">*</span></label>
            <select name="statustypeid" class="form-control select2" required>
                @foreach($types as $model)
                    <option value="{{$model->statusid}}" {{ old('statustypeid') == $model->id ? 'selected' : '' }} > {{$model->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="description">Nombre del inventario</label>
            <input type="text" class="form-control"  id="description" name="description"
                   onKeyUp=this.value=this.value.toUpperCase() required maxlength="100"
            >
        </div>
    </div>
</div>

