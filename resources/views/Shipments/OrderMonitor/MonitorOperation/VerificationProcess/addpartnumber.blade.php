<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-addpartnumber">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">Solicitud de material</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="shipmentid">Embarqque <span style="color: red">*</span></label>
                            <input type="text" class="form-control" readonly id="shipmentid" name="shipmentid"
                                   value="{{$orderDelivery->shipmentid}}"
                                   onKeyUp=this.value=this.value.toUpperCase()
                            >
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="customername">Cliente <span style="color: red">*</span></label>
                            <input type="text" class="form-control" readonly id="customername" name="customername"
                                   value="{{$orderDelivery->customer->name}}"
                            >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="partnumberid">Número de parte <span style="color: red">*</span></label>
                            <input type="text" class="form-control" id="partnumberid" name="partnumberid"
                                   value="{{old('partnumberid')}}" maxlength="20"
                                   onKeyUp=this.value=this.value.toUpperCase()
                            >
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="quantity">Cantidad<span style="color: red">*</span></label>
                            <input type="text" class="form-control numeric" id="quantity" name="quantity"
                                   value="{{old('quantity')}}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm  waves-effect waves-light"
                        data-dismiss="modal">
                    <img src="{{asset('/ico/close.png')}}" alt="" class="img-16">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-outline-secondary btn-sm  waves-effect waves-light"
                        id="addsku">
                    <img src="{{asset('/ico/ok.png')}}" alt="" class="img-16">
                    Aceptar
                </button>
            </div>
        </div>
    </div>
</div>
