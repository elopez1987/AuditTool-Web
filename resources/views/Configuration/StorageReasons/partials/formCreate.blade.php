
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" id="name" required name="name" maxlength="50"
                   aria-describedby="name" value="{{old('name')}}" onKeyUp=this.value=this.value.toUpperCase()>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="razonstatusid">Evento</label>
            <input type="text" class="form-control" id="razonstatusid"  name="razonstatusid" maxlength="10"
                   aria-describedby="razonstatusid" value="{{old('razonstatusid')}}"
                   onKeyUp=this.value=this.value.toUpperCase()>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="type">Tipo de movimiento</label>
            <input type="text" class="form-control" id="type"  name="type" maxlength="10"
                   aria-describedby="type" value="{{old('type')}}" onKeyUp=this.value=this.value.toUpperCase()>
        </div>
    </div>
</div>

