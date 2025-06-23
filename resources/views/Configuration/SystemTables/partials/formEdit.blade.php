<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="tableid">Nombre de tabla <span style="color: red">*</span></label>
            <input type="text" class="form-control" id="tableid" name="tableid"
                   value="{{$systemtable->tableid}}"
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="description">Detalle de tabla <span style="color: red">*</span></label>
            <input type="text" class="form-control" id="description" name="description"
                   value="{{$systemtable->description}}"
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="notes">Comentario <span style="color: red">*</span></label>
            <textarea class="form-control" cols="10" rows="20" id="notes" name="notes">{{$systemtable->notes}}</textarea>
        </div>
    </div>
</div>
