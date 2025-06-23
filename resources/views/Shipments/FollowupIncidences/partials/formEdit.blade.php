<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="shipmentid">Embarque</label>
            <input type="text" class="form-control" readonly value="{{$shipment->order->shipmentid}}"  id="shipmentid" name="shipmentid">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="customername">Cliente</label>
            <input type="text" class="form-control" readonly value="{{$shipment->order->customer->name}}"  id="customername" name="customername">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="city">Ciudad</label>
            <input type="text" class="form-control" readonly value="{{$shipment->order->customer->city}}"  id="city" name="city">
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="downloaddate">Fecha de descarga</label>
            <input type="text" class="form-control" readonly value="{{$shipment->order->downloaddate}}"  id="downloaddate" name="downloaddate">
        </div>
    </div>
</div>
