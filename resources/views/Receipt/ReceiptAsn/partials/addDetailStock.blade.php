<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="searchSku">Numero de material <span style="color: red">*</span> </label>
            <div class="input-group">
                <input type="text" class="form-control" id="prtnum" name="prtnum" value="{{ old('prtnum') }}"
                       aria-describedby="basic-addon1">
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="lngdsc">Descripción</label>
            <input type="text" readonly class="form-control" id="lngdsc" name="lngdsc" value="{{old('lngdsc')}}">
        </div>
    </div>
</div>

<div class="row">
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



