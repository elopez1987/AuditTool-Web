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
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="lngdsc">Descripción <span style="color: red">*</span></label>
            <input type="text" readonly class="form-control" id="lngdsc" name="lngdsc" value="{{old('lngdsc')}}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="productiondate">Fecha de producción <span style="color: red">*</span></label>
            <input type="text" required class="form-control workday" id="productiondate" name="productiondate" value="{{old('productiondate')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="batch">Lote <span style="color: red">*</span></label>
            <input type="text" required class="form-control" id="batch" name="batch"
                   onKeyUp=this.value=this.value.toUpperCase() value="{{old('batch')}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="quantity">Cantidad <span style="color: red">*</span></label>
            <input type="text" required class="form-control numeric" id="quantity" name="quantity"
                   value="{{old('quantity')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="rotary">Rotativa <span style="color: red">*</span></label>
            <input type="text" required class="form-control numeric" id="rotary" name="rotary"
                   value="{{old('rotary')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="definitive">Definitiva <span style="color: red">*</span></label>
            <input type="text" required class="form-control numeric" id="definitive" name="definitive"
                   value="{{old('definitive')}}">
        </div>
    </div>
</div>



