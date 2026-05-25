<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Nombre<span style="color: red">*</span></label>
            <input type="text" class="form-control"  id="name" name="name"
                   value="{{$inventoryarea->name}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary01">Auxiliar 01 </label>
            <input type="text" class="form-control" id="auxiliary01" name="auxiliary01" maxlength="100"
                   aria-describedby="auxiliary01" value="{{$inventoryarea->auxiliary01}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary02">Auxiliar 02 </label>
            <input type="text" class="form-control" id="auxiliary02" name="auxiliary02" maxlength="100"
                   aria-describedby="auxiliary02" value="{{$inventoryarea->auxiliary02}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="auxiliary03">Auxiliar 03 </label>
            <input type="text" class="form-control" id="auxiliary03" name="auxiliary03" maxlength="100"
                   aria-describedby="auxiliary03" value="{{$inventoryarea->auxiliary03}}">
        </div>
    </div>
</div>

