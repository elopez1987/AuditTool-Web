<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="">Cortina</label>
            <input type="text" value="{{$location->name}}" readonly class="form-control">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <select multiple="multiple" size="15"
                class="duallistbox-with-filter"
                name="doorid[]"
                required>
            @foreach($doors as $model)
                <option value="{{$model->id}}"> {{$model->name}}</option>
            @endforeach
        </select>
    </div>
</div>
