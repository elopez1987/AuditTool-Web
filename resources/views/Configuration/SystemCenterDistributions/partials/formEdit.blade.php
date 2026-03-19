<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Centro de distribución <span style="color: red">*</span></label>
            <input type="text" class="form-control" required id="centerid"  readonly name="centerid" maxlength="15"
                   value="{{$systemcenterdistribution->centerid}}"
                   aria-describedby="centerid" onKeyUp=this.value=this.value.toUpperCase();>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Descripción <span style="color: red">*</span></label>
            <input type="text" class="form-control" required id="description" name="description" maxlength="50"
                   aria-describedby="description" onKeyUp=this.value=this.value.toUpperCase();
                   value="{{$systemcenterdistribution->description}}">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Nombre <span style="color: red">*</span></label>
            <input type="text" class="form-control" required id="name" name="name" maxlength="50"
                   aria-describedby="name" onKeyUp=this.value=this.value.toUpperCase(); value="{{$systemcenterdistribution->name}}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Código de planta ( SAP ) <span style="color: red">*</span></label>
            <input type="text" class="form-control"  id="code" name="code" maxlength="15"
                   aria-describedby="code" onKeyUp=this.value=this.value.toUpperCase(); value="{{$systemcenterdistribution->code}}">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Nombre del formato del folio de patios </label>
            <input type="text" class="form-control"  id="formatinvoice" name="formatinvoice" maxlength="50"
                   aria-describedby="formatinvoice"
                   value="{{$systemcenterdistribution->formatinvoice}}">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Capacidad maxima de patio.</label>
            <input type="number" class="form-control numeric"  id="capacityyard" name="capacityyard"
                   maxlength="15"
                   aria-describedby="code" onKeyUp=this.value=this.value.toUpperCase(); value="{{$systemcenterdistribution->capacityyard}}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 01 </label>
            <input type="text" class="form-control" id="auxiliary01" name="auxiliary01" maxlength="100"
                   aria-describedby="auxiliary01" value="{{$systemcenterdistribution->auxiliary01}}">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 02 </label>
            <input type="text" class="form-control" id="auxiliary02" name="auxiliary02" maxlength="100"
                   aria-describedby="auxiliary02" value="{{$systemcenterdistribution->auxiliary02}}">
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Auxiliar 03 </label>
            <input type="text" class="form-control" id="auxiliary03" name="auxiliary03" maxlength="100"
                   aria-describedby="auxiliary03" value="{{$systemcenterdistribution->auxiliary03}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">Logo de la empresa</label>
            <input type="file" class="form-control" id="file" name="file"
                   value="{{$systemcenterdistribution->auxiliary04}}">
        </div>
    </div>
</div>


{{--<div class="row">--}}
{{--    <div class="col-lg-4 col-md-6 col-sm-12">--}}
{{--        <div class="form-group">--}}
{{--            <label for="name">URL del web service a donde el sistema enviara la información en los eventos--}}
{{--                logisticos. </label>--}}
{{--            <input type="text" class="form-control" id="url" name="url" maxlength="150" aria-describedby="url"--}}
{{--                   value="{{$systemcenterdistribution->url}}">--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="col-lg-4 col-md-6 col-sm-12">--}}
{{--        <div class="form-group">--}}
{{--            <label for="name">Usuario del web service proporcionado por el proveedor al cual se conectara el--}}
{{--                sistema. </label>--}}
{{--            <input type="text" class="form-control" id="userapi" name="userapi" maxlength="150"--}}
{{--                   aria-describedby="userapi" value="{{$systemcenterdistribution->userapi}}">--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="col-lg-4 col-md-6 col-sm-12">--}}
{{--        <div class="form-group">--}}
{{--            <label for="name">Contraseña del web service proporcionado por el proveedor al cual se conectara el--}}
{{--                sistema. </label>--}}
{{--            <input type="text" class="form-control" id="passwordapi" name="passwordapi" maxlength="150"--}}
{{--                   aria-describedby="passwordapi" value="{{$systemcenterdistribution->passwordapi}}">--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
