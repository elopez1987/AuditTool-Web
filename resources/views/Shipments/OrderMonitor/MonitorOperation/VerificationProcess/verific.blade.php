<div class="modal fade bs-example-modal-lg modal-verific" role="dialog" aria-labelledby="myLargeModalLabel"
     aria-hidden="true"
     style="display: none;" id="modal-verific-{{$check->recordid}}">
    <form method="POST" action="{{url('registermissingconcep/' . $check->recordid)}}" enctype="multipart/form-data">
        {{ method_field('PUT') }}
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">
                        Verificación manual</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="partnumberid">Producto <span style="color: red">*</span></label>
                                <input type="text" class="form-control"  readonly id="partnumberid" name="partnumberid"
                                       value="{{$check->sku}}"
                                       onKeyUp=this.value=this.value.toUpperCase()
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="partnumberid">Descripción <span style="color: red">*</span></label>
                                <input type="text" class="form-control"  readonly id="partnumberid" name="partnumberid"
                                       value="{{$check->description}}"
                                       onKeyUp=this.value=this.value.toUpperCase()
                                >
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="quantity">Cantidad <span style="color: red">*</span></label>
                                <input type="text" class="form-control"  readonly id="quantity" name="quantity"
                                       value="{{$check->quantityfail}}"
                                       onKeyUp=this.value=this.value.toUpperCase()
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="missingconceptid">Concepto de faltante<span style="color: red">*</span></label>
                                <select name="missingconceptid" id='missingconceptid' required class="form-control select2">
                                    @foreach($concepts as $model)
                                        <option value="{{$model->categoryincdetailid}}"> {{$model->description}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm  waves-effect waves-light"
                            data-dismiss="modal">
                        <img src="{{asset('/ico/adcancelar.png')}}" alt="" class="img-16">Cancelar
                    </button>
                    <button type="submit" class="btn btn-outline-secondary btn-sm  waves-effect waves-light">
                        <img src="{{asset('/ico/unitstatus.png')}}" alt="" class="img-16">Aceptar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
