<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-addtransporttoshipment-{{$id}}">
    <form method="POST" action="{{url('updateDataTransportToShipment/' . $id)}}" enctype="multipart/form-data">
        {{ method_field('PUT') }}
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 12px;font-style: normal;font-weight: 400;"> Agregar
                        datos del transporte.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="turno">Turno <span style="color: red">*</span></label>
                                <input type="text" class="form-control" readonly id="turno" name="turno"
                                       value="{{$turno}}"
                                       onKeyUp=this.value=this.value.toUpperCase()
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="shipmentid">Embarque <span style="color: red">*</span></label>
                                <input type="text" class="form-control" readonly id="shipmentid" name="shipmentid"
                                       value="{{$shipmentid}}"
                                       onKeyUp=this.value=this.value.toUpperCase()
                                >
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="carriername">Linea de transporte <span style="color: red">*</span></label>
                                <input type="text" class="form-control" readonly id="carriername" name="carriername"
                                       value="{{$carriername}}"
                                       onKeyUp=this.value=this.value.toUpperCase()
                                >
                            </div>
                        </div>
                    </div>
                    <hr class="mt-2">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="operatorname">Nombre de operador</label>
                                <input type="text" class="form-control" required id="operatorname" name="operatorname"
                                       onKeyUp=this.value=this.value.toUpperCase()
                                       value="{{$operatorname}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="phone">Número de contacto</label>
                                <input type="text" class="form-control numeric" required id="phone" name="phone"
                                       onKeyUp=this.value=this.value.toUpperCase()
                                       value="{{$phone}}"
                                       maxlength="10" min="10"
                                >
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="drivernumber">Numero de licencia</label>
                                <input type="text" class="form-control" required id="drivernumber" name="drivernumber"
                                       onKeyUp=this.value=this.value.toUpperCase() maxlength="20"
                                       value="{{$drivernumber}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="boxnumber">Placa de caja</label>
                                <input type="text" class="form-control" required id="boxnumber" name="boxnumber"
                                       onKeyUp=this.value=this.value.toUpperCase() maxlength="10"
                                       value="{{$boxnumber}}"
                                >
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="auxiliary03">Eco. De caja</label>
                                <input type="text" class="form-control" required id="auxiliary03" name="auxiliary03"
                                       onKeyUp=this.value=this.value.toUpperCase() maxlength="10"
                                       value="{{$auxiliary03}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="trucknumber">Placa de tracto</label>
                                <input type="text" class="form-control "  required id="trucknumber" name="trucknumber"
                                       onKeyUp=this.value=this.value.toUpperCase() maxlength="10"
                                       value="{{$trucknumber}}"
                                >
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="auxiliary04">Eco. De tracto</label>
                                <input type="text" class="form-control" required id="auxiliary04" name="auxiliary04"
                                       onKeyUp=this.value=this.value.toUpperCase() maxlength="10"
                                       value="{{$auxiliary04}}"
                                >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm  waves-effect waves-light"
                            data-dismiss="modal">
                        <img src="{{asset('/ico/adeliminar.png')}}" class="img-16" alt="">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-outline-secondary btn-sm  waves-effect waves-light">
                        <img src="{{asset('/ico/ok.png')}}" class="img-16" alt="">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
