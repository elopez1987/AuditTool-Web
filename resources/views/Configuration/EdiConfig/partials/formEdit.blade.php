
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="edi">EDI</label>
            <input type="text" class="form-control" required id="edi" name="edi" maxlength="190"
                   aria-describedby="edi" value="{{$ediConfig->edi}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="type">Tipo</label>
            <select name="type" id="type" class="form-control select2">
                <option value="Cliente-Proveedor" {{$ediConfig->type == 'Cliente-Proveedor' ? 'selected' : ''}}>Cliente => Proveedor</option>
                <option value="Proveedor-Cliente" {{$ediConfig->type == 'Proveedor-Cliente' ? 'selected' : ''}}>Proveedor => Cliente</option>
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="standard">Estandar</label>
            <select name="standard" id="standard" class="form-control select2">
                <option value="XML" {{$ediConfig->standard == 'XML' ? 'selected' : ''}}>XML</option>
                <option value="X12" {{$ediConfig->standard == 'X12' ? 'selected' : ''}}>X12</option>
                <option value="EDIFACT" {{$ediConfig->standard == 'EDIFACT' ? 'selected' : ''}}>EDIFACT</option>
                <option value="TXT" {{$ediConfig->standard == 'TXT' ? 'selected' : ''}}>TXT</option>
                <option value="Web-Service" {{$ediConfig->standard == 'Web-Service' ? 'selected' : ''}}>Web Service</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="description">Descripcion </label>
            <input type="text" class="form-control" id="description" name="description" maxlength="190"
                   aria-describedby="description" value="{{$ediConfig->description}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="sendername">Remitente </label>
            <input type="text" class="form-control" id="sendername" name="sendername" maxlength="100"
                   aria-describedby="sendername" value="{{$ediConfig->sendername}}">
        </div>
    </div>

</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centersender">C. distribución Origen </label>
            <input type="text" class="form-control" id="centersender" name="centersender" maxlength="100"
                   aria-describedby="centersender" value="{{$ediConfig->centersender}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="warehousesender">Bodega Origen </label>
            <input type="text" class="form-control" id="warehousesender" name="warehousesender" maxlength="100"
                   aria-describedby="warehousesender" value="{{$ediConfig->warehousesender}}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="centerrecipient">C. distribución Destino </label>
            <input type="text" class="form-control" id="centerrecipient" name="centerrecipient" maxlength="100"
                   aria-describedby="centerrecipient" value="{{$ediConfig->centerrecipient}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="warehouserecipient">Bodega Destino </label>
            <input type="text" class="form-control" id="warehouserecipient" name="warehouserecipient" maxlength="100"
                   aria-describedby="warehouserecipient" value="{{$ediConfig->warehouserecipient}}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="url">Url </label>
            <input type="text" class="form-control" id="url" name="url"
                   aria-describedby="url" value="{{$ediConfig->url}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="user">Usuario </label>
            <input type="text" class="form-control" id="user" name="user"
                   aria-describedby="user" value="{{$ediConfig->user}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="password">Contraseña </label>
            <input type="text" class="form-control" id="password" name="password" maxlength="100"
                   aria-describedby="password" value="{{$ediConfig->password}}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="version">Versión </label>
            <input type="text" class="form-control" id="version" name="version"
                   aria-describedby="version" value="{{$ediConfig->version}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="enviroment">Entorno </label>
            <input type="text" class="form-control" id="enviroment" name="enviroment"
                   aria-describedby="enviroment" value="{{$ediConfig->enviroment}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="systemclient">Sistema cliente </label>
            <input type="text" class="form-control" id="systemclient" name="systemclient" maxlength="100"
                   aria-describedby="systemclient" value="{{$ediConfig->systemclient}}">
        </div>
    </div>
</div>