<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="shipmentid">Embarque</label>
            <input type="text" class="form-control" readonly  id="shipmentid" name="shipmentid"
                   onKeyUp=this.value=this.value.toUpperCase()
                   value="{{$shipment->order->shipmentid}}"
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="customername">Cliente</label>
            <input type="text" class="form-control" readonly  id="customername" name="customername"
                   onKeyUp=this.value=this.value.toUpperCase()
                   value="{{$shipment->order->customer->name}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="city">Ciudad</label>
            <input type="text" class="form-control" readonly  id="city" name="city"
                   onKeyUp=this.value=this.value.toUpperCase()
                   value="{{$shipment->order->customer->city}}"
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="downloaddate">Fecha de descarga</label>
            <input type="text" class="form-control" readonly  id="downloaddate" name="downloaddate"
                   onKeyUp=this.value=this.value.toUpperCase()
                   value="{{$shipment->order->downloaddate}}"
            >
        </div>
    </div>
</div>
