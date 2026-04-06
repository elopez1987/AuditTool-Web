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
            <label for="razonstatusid">Evento WMS</label>
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

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 01 </label>
            <input type="text" class="form-control" id="auxiliary01" name="auxiliary01" maxlength="100"
                   aria-describedby="auxiliary01" value="{{old('auxiliary01')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 02 </label>
            <input type="text" class="form-control" id="auxiliary02" name="auxiliary02" maxlength="100"
                   aria-describedby="auxiliary02" value="{{old('auxiliary02')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 03 </label>
            <input type="text" class="form-control" id="auxiliary03" name="auxiliary03" maxlength="100"
                   aria-describedby="auxiliary03" value="{{old('auxiliary03')}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 04 </label>
            <input type="text" class="form-control" id="auxiliary04" name="auxiliary04" maxlength="100"
                   aria-describedby="auxiliary04" value="{{old('auxiliary04')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 05 </label>
            <input type="text" class="form-control" id="auxiliary05" name="auxiliary05" maxlength="100"
                   aria-describedby="auxiliary05" value="{{old('auxiliary05')}}">
        </div>
    </div>
</div>
