<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-add">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded">
            <div class="modal-header">
                <h5 class="modal-title"> Solicitud de Marterial</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="shipmentid">ShipmentID</label>
                            <input type="text" class="form-control" readonly value="{{$storageevidenceprocess->recordid}}"
                                   id="shipmentid" name="shipmentid"
                            >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="partnumberid">Número de parte</label>
                            <input type="text" class="form-control"
                                   id="partnumberid" name="partnumberid"
                                   onKeyUp=this.value=this.value.toUpperCase()
                            >
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="quantity">Cantidad</label>
                            <input type="number" class="form-control numeric"
                                   id="quantity" name="quantity"
                                   onKeyUp=this.value=this.value.toUpperCase()
                            >
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn waves-effect waves-light btn-outline-secondary btn-sm "
                        data-dismiss="modal">
                    <img src="{{asset('/ico/close.png')}}" alt="" class="img-16">
                    Cancelar
                </button>
                <button class="btn waves-effect waves-light btn-outline-secondary btn-sm" id="savePartNumber">
                    <img src="{{asset('/ico/accept.png')}}" alt="" class="img-16">
                    Aceptar
                </button>
            </div>
        </div>
    </div>
</div>
