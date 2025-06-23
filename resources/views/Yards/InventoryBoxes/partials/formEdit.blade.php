<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerid">Centro de distribución<span style="color: red">*</span></label>
            <input type="text" class="form-control" readonly id="centerid" name="centerid"
                   value="{{$inventorybox->center->name}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="statustypeid">Tipo de Inventario<span style="color: red">*</span></label>
                <input type="text" class="form-control" readonly id="statustypeid" name="statustypeid"
                       value="{{$inventorybox->type->name}}"
                       onKeyUp=this.value=this.value.toUpperCase()
                >
            </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="description">Nombre del Inventario<span style="color: red">*</span></label>
                <input type="text" class="form-control" readonly id="description" name="description"
                       value="{{$inventorybox->description}}"
                       onKeyUp=this.value=this.value.toUpperCase()
                >
            </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="description">Estatus del Inventario<span style="color: red">*</span></label>
            <input type="text" class="form-control" readonly id="description" name="description"
                   value="{{$inventorybox->status->name}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>
<hr class="m-t-0">

