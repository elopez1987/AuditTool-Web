<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-kgneto-{{$id}}">
    <form method="POST" action="{{url('saveweightforoperation/' . $id)}}">
        {{ method_field('PUT') }}
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;"> Registrar peso de producto.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="kgneto">Peso Neto
                                    <span style="color: red">*</span></label>
                                <input type="number" class="form-control numeric @error('kgneto') is-invalid @enderror"
                                       required id="kgneto" name="kgneto" maxlength="20"
                                       value="{{old('kgneto')}}"
                                >
                                @error('kgneto')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="embalaje">Embalaje
                                    <span style="color: red">*</span></label>
                                <input type="number" class="form-control numeric @error('embalaje') is-invalid @enderror"
                                       required id="embalaje" name="embalaje"
                                       value="{{old('embalaje')}}"
                                >
                                @error('embalaje')
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
