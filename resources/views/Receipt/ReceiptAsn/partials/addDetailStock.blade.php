<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="searchSku">Numero de material</label>
            <div class="input-group">
                <input type="text" class="form-control" id="prtnum" name="prtnum" value="{{ old('prtnum') }}"
                       aria-describedby="basic-addon1">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="lngdsc">Descripción <span style="color: red">*</span></label>
            <input type="text" readonly class="form-control" id="lngdsc" name="lngdsc">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="untpal">Cajas por pallet <span style="color: red">*</span></label>
            <input type="text" readonly class="form-control" id="untpal" name="untpal">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="productiondate">Fecha de producción <span style="color: red">*</span></label>
            <input type="text" required class="form-control workday" id="productiondate" name="productiondate">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="batch">Lote <span style="color: red">*</span></label>
            <input type="text" required class="form-control" id="batch" name="batch"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="quantity">Cantidad <span style="color: red">*</span></label>
            <input type="text" required class="form-control numeric" id="quantity" name="quantity">
        </div>
    </div>
</div>
<br>


