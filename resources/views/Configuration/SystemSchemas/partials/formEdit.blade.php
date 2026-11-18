<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="schemaid">Esquema</label>
            <input type="text" disabled class="form-control" id="schemaid" name="schemaid" maxlength="100"
                   aria-describedby="schemaid" value="{{$systemschema->schemaid}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text"  class="form-control" id="name" name="name" maxlength="100"
                   aria-describedby="name" value="{{$systemschema->name}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>
