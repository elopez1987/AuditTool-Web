<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="parameterid">Parametro</label>
            <input type="text" class="form-control" id="parameterid" readonly name="parameterid" maxlength="100"
                   aria-describedby="parameterid" value="{{$systemparameter->parameterid}}"
                   onKeyUp=this.value=this.value.toUpperCase();
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Detalle</label>
            <textarea id="name" name="name" class="form-control" cols="150" rows="10">{{$systemparameter->name}}</textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-8">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="name">Auxiliar 01 </label>
                    <input type="text" class="form-control" id="auxiliary01" name="auxiliary01" maxlength="100"
                           aria-describedby="auxiliary01" value="{{$systemparameter->auxiliary01}}">
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="name">Auxiliar 02 </label>
                    <input type="text" class="form-control" id="auxiliary02" name="auxiliary02" maxlength="100"
                           aria-describedby="auxiliary02" value="{{$systemparameter->auxiliary02}}">
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="name">Auxiliar 03 </label>
                    <input type="text" class="form-control" id="auxiliary03" name="auxiliary03" maxlength="100"
                           aria-describedby="auxiliary03" value="{{$systemparameter->auxiliary03}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="name">Auxiliar 04 </label>
                    <input type="text" class="form-control" id="auxiliary04" name="auxiliary04" maxlength="100"
                           aria-describedby="auxiliary04" value="{{$systemparameter->auxiliary04}}">
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="name">Auxiliar 05 </label>
                    <input type="text" class="form-control" id="auxiliary05" name="auxiliary05" maxlength="100"
                           aria-describedby="auxiliary05" value="{{$systemparameter->auxiliary05}}">
                </div>
            </div>
        </div>
    </div>
</div>


