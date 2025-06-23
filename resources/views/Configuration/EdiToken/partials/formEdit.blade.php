
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="baseuri">Url base </label>
            <input type="text" class="form-control" required id="baseuri" name="baseuri" maxlength="190"
                   aria-describedby="baseuri" value="{{$token->baseuri}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="client_id">Cliente Id </label>
            <input type="text" class="form-control" id="client_id" name="client_id" maxlength="190"
                   aria-describedby="client_id" value="{{$token->client_id}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="client_secret">Token </label>
            <input type="text" class="form-control" id="client_secret" name="client_secret" maxlength="100"
                   aria-describedby="client_secret" value="{{$token->client_secret}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="audience">Audiencia </label>
            <input type="text" class="form-control" id="audience" name="audience" maxlength="100"
                   aria-describedby="audience" value="{{$token->audience}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="timeout">Tiempo de respuesta </label>
            <input type="text" class="form-control" id="timeout" name="timeout" maxlength="100"
                   aria-describedby="timeout" value="{{$token->timeout}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="tokentype">Tipo de token </label>
            <input type="text" class="form-control" id="tokentype" name="tokentype" maxlength="100"
                   aria-describedby="tokentype" value="{{$token->tokentype}}">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="servicetype">Tipo de servicio </label>
            <input type="text" class="form-control" id="servicetype" name="servicetype" maxlength="100"
                   aria-describedby="servicetype" value="{{$token->servicetype}}">
        </div>
    </div>
</div>
