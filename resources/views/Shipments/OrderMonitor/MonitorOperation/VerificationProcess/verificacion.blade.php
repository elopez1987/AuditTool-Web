<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-verificacion">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">Detalle de la verificación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="startdate">Inicio de verificación <span style="color: red">*</span></label>
                            <input type="text" class="form-control" readonly id="startdate" name="startdate"
                                   value="{{$orderDelivery->check->startdate}}"
                                   onKeyUp=this.value=this.value.toUpperCase()
                            >
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="finishdate">Fin de verificación <span style="color: red">*</span></label>
                            <input type="text" class="form-control" readonly id="finishdate" name="finishdate"
                                   value="{{$orderDelivery->check->finishdate}}"
                                   onKeyUp=this.value=this.value.toUpperCase()
                            >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="username">Verificador <span style="color: red">*</span></label>
                            <input type="text" class="form-control" readonly id="username" name="username"
                                   value="{{$orderDelivery->check->user ? $orderDelivery->check->user->name : ''}}"
                                   onKeyUp=this.value=this.value.toUpperCase()
                            >
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm  waves-effect waves-light"
                        data-dismiss="modal">
                    <img src="{{asset('/ico/close.png')}}" alt="" class="img-16">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
