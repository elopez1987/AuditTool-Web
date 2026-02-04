
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="edi">EDI</label>
            <input type="text" class="form-control" required id="edi" name="edi" maxlength="190"
                   aria-describedby="edi" value="{{old('edi')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="type">Tipo</label>
            <select name="type" id="type" class="form-control select2">
                <option value="Cliente-Proveedor">Cliente => Proveedor</option>
                <option value="Proveedor-Cliente">Proveedor => Cliente</option>
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="standard">Estandar</label>
            <select name="standard" id="standard" class="form-control select2">
                <option value="XML">XML</option>
                <option value="X12">X12</option>
                <option value="EDIFACT">EDIFACT</option>
                <option value="TXT">TXT</option>
                <option value="Web-Service">Web Service</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="description">Descripcion </label>
            <input type="text" class="form-control" id="description" name="description" maxlength="190"
                   aria-describedby="description" value="{{old('description')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="sendername">Remitente </label>
            <input type="text" class="form-control" id="sendername" name="sendername" maxlength="100"
                   aria-describedby="sendername" value="{{old('sendername')}}">
        </div>
    </div>

</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centersender">C. distribución Origen </label>
            <input type="text" class="form-control" id="centersender" name="centersender" maxlength="100"
                   aria-describedby="centersender" value="{{old('centersender')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="warehousesender">Bodega Origen </label>
            <input type="text" class="form-control" id="warehousesender" name="warehousesender" maxlength="100"
                   aria-describedby="warehousesender" value="{{old('warehousesender')}}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerrecipient">C. distribución Destino </label>
            <input type="text" class="form-control" id="centerrecipient" name="centerrecipient" maxlength="100"
                   aria-describedby="centerrecipient" value="{{old('centerrecipient')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="warehouserecipient">Bodega Destino </label>
            <input type="text" class="form-control" id="warehouserecipient" name="warehouserecipient" maxlength="100"
                   aria-describedby="warehouserecipient" value="{{old('warehouserecipient')}}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="url">Url </label>
            <input type="text" class="form-control" id="url" name="url"
                   aria-describedby="url" value="{{old('url')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="user">Usuario </label>
            <input type="text" class="form-control" id="user" name="user"
                   aria-describedby="user" value="{{old('user')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="password">Contraseña </label>
            <input type="text" class="form-control" id="password" name="password" maxlength="100"
                   aria-describedby="password" value="{{old('password')}}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="version">Versión </label>
            <input type="text" class="form-control" id="version" name="version"
                   aria-describedby="version" value="{{old('version')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="enviroment">Entorno </label>
            <input type="text" class="form-control" id="enviroment" name="enviroment"
                   aria-describedby="enviroment" value="{{old('enviroment')}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="systemclient">Sistema cliente </label>
            <input type="text" class="form-control" id="systemclient" name="systemclient" maxlength="100"
                   aria-describedby="systemclient" value="{{old('systemclient')}}">
        </div>
    </div>
</div>