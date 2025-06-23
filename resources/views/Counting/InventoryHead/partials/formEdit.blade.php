<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="tableid">Centro de distribución</label>
            <input type="text" class="form-control" readonly value="{{$inventoryhead->center->name}}" >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="tableid">Bodega</label>
            <input type="text" class="form-control" readonly value="{{$inventoryhead->warehouse->name}}" >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="tableid">Tipo de Inventario</label>
            <input type="text" class="form-control" readonly value="{{$inventoryhead->type->name}}" >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="description">Descripción</label>
            <input type="text" class="form-control" name="description"  value="{{$inventoryhead->description}}" >
        </div>
    </div>
</div>



