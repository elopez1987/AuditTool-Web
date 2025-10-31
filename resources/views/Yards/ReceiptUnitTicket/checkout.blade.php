<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-checkout-{{$id}}">
    <form method="POST" action="{{url('receiptunitticket/' . $id)}}">
        {{ method_field('PUT') }}
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;"> Registrar datos de salida</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="weightout">Peso de salida
                                    <span style="color: red">*</span></label>
                                <input type="number" class="form-control numeric @error('weightout') is-invalid @enderror"
                                       required id="weightout" name="weightout" maxlength="20"
                                       value="{{old('weightout')}}"
                                >
                                @error('weightout')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="weightoutsap">Peso de producto (SAP)
                                    <span style="color: red">*</span></label>
                                <input type="number" class="form-control numeric @error('weightoutsap') is-invalid @enderror"
                                       required id="weightoutsap" name="weightoutsap" maxlength="20"
                                       value="{{old('weightoutsap')}}"
                                >
                                @error('weightoutsap')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
