<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerid">Centro de distribución <span style="color: red">*</span></label>
            <input type="text" class="form-control" readonly id="centerid" name="centerid"
                   value="{{$schedulle->center->name}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="warehouseid">Bodega <span style="color: red">*</span></label>
            <input type="text" class="form-control" readonly id="warehouseid" name="warehouseid"
                   value="{{$schedulle->warehouse->name}}"
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Nombre <span style="color: red">*</span></label>
            <input type="text" class="form-control" id="name" name="name"
                   value="{{$schedulle->name}}" required
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="startdate">Fecha de inicio</label>
            <input type="text" class="form-control workday @error('startdate') is-invalid @enderror"
                   id="startdate" name="startdate"
                   aria-describedby="startdate" value="{{$schedulle->startdate}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="finishdate">Fecha final</label>
            <input type="text" class="form-control workday" id="finishdate" name="finishdate"
                   aria-describedby="finishdate" value="{{$schedulle->finishdate}}">
        </div>
    </div>
</div>
