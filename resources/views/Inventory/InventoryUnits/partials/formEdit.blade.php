<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="unitmeasureid">Unidad de medida </label>
            <input type="text" class="form-control" readonly id="unitmeasureid" name="unitmeasureid"
                   aria-describedby="unitmeasureid" value="{{$inventoryunitmeasure->unitmeasureid}}"
                   onKeyUp=this.value=this.value.toUpperCase(); maxlength="3"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="description">Nombre </label>
            <input type="text" class="form-control" id="description" name="description"
                   aria-describedby="description" value="{{$inventoryunitmeasure->description}}"
                   onKeyUp=this.value=this.value.toUpperCase(); maxlength="50"
             >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="ediunitmeasurecode">Código EDI </label>
            <input type="text" class="form-control" id="ediunitmeasurecode" name="ediunitmeasurecode"
                   aria-describedby="ediunitmeasurecode"
                   value="{{$inventoryunitmeasure->ediunitmeasurecode}}"
                   onKeyUp=this.value=this.value.toUpperCase(); maxlength="10"
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
                            @if($statu->statusid == $inventoryunitmeasure->statusid)
                                selected="selected"
                        @endif>
                        {{$statu->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
