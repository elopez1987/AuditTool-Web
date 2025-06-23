<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="description">Descripción
                @error("name")
                <span class="message-error">{{$message}}</span>
                @enderror
                <span style="color: red">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   required id="name" name="name" max="18"
                   aria-describedby="name" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{$shipmenttruck->name}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="truckid">Tipo de unidad</label>
            <input type="text" class="form-control" id="truckid" name="truckid" maxlength="15"
                   aria-describedby="truckid"  value="{{$shipmenttruck->truckid}}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="value01">Valor 01 </label>
            <input type="text" class="form-control" id="value01" name="value01" maxlength="15"
                   aria-describedby="value01" >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="value02">Valor 02</label>
            <input type="text" class="form-control" id="value02" name="value02" maxlength="15"
                   aria-describedby="value02" >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="value03">Valor 03 </label>
            <input type="text" class="form-control" id="value03" name="value03" maxlength="15"
                   aria-describedby="value03" >
        </div>
    </div>

</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="value04">Valor 04 </label>
            <input type="text" class="form-control" id="value04" name="value04" maxlength="15"
                   aria-describedby="value04" >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="value05">Valor 05 </label>
            <input type="text" class="form-control" id="value05" name="value05" maxlength="15"
                   aria-describedby="value05" >
        </div>
    </div>
</div>


