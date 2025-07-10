<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-updatequantity-{{$stock->id}}">

    <form method="POST" action="{{url('updateQuantityStock/' . $stock->id)}}" enctype="multipart/form-data">
        {{ method_field('PUT') }}
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">Modificar
                        cantidad solicitada</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="partnumber">Número de parte <span style="color: red">*</span></label>
                                <input type="text" class="form-control" readonly id="partnumber" name="partnumber"
                                       value="{{$stock->prtnum}}"
                                >
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="description">Descripción <span style="color: red">*</span></label>
                                <input type="text" class="form-control" readonly id="description" name="description"
                                       value="{{$stock->lngdsc}}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="quantitytrue">Cantidad <span style="color: red">*</span></label>
                                <input type="text" class="form-control numeric" required id="quantitytrue" name="quantitytrue"
                                       value="{{$stock->quantitytrue}}"
                                >
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="expirecode">Lote <span style="color: red">*</span></label>
                                <input type="text" class="form-control" required id="expirecode" name="expirecode"
                                       value="{{$stock->expirecode}}"
                                       onKeyUp=this.value=this.value.toUpperCase()
                                >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm  waves-effect waves-light"
                            data-dismiss="modal">
                        <img src="{{asset('/pics/adcancelar.png')}}" alt="">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-outline-secondary btn-sm  waves-effect waves-light">
                        <img src="{{asset('/pics/adaceptar.png')}}" alt="">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
