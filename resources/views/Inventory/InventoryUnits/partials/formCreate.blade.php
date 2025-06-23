
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="unitmeasureid">Unidad de medida </label>
            <input type="text" class="form-control" required id="unitmeasureid" name="unitmeasureid"
                   aria-describedby="unitmeasureid" value="{{old('unitmeasureid')}}" maxlength="3"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="description">Descripción </label>
            <input type="text" class="form-control" required id="description" name="description"
                   aria-describedby="description" value="{{old('description')}}" maxlength="50"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="ediunitmeasurecode">Código EDI</label>
            <input type="text" class="form-control" id="ediunitmeasurecode" name="ediunitmeasurecode" maxlength="10"
                   aria-describedby="ediunitmeasurecode" value="{{old('ediunitmeasurecode')}}">
        </div>
    </div>
</div>
