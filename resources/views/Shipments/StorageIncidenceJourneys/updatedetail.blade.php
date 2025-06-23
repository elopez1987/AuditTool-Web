<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-updatedetail-{{$detail->folioid}}">
    <form method="POST" action="{{url('storageincidencesjourneysupdatedetail/' . $detail->folioid)}}"
          enctype="multipart/form-data">
        {{ method_field('PUT') }}
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">Modificar
                        reporte de incidencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="razoncode">Categoria <span style="color: red">*</span></label>
                                <select name="razoncode" class="form-control select2">
                                    @foreach($razoncodes as $model)
                                        <option value="{{$model->categoryincdetailid}}"
                                                @if($model->categoryincdetailid == $detail->razoncode)
                                                    selected="selected"
                                            @endif>
                                            {{$model->description}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="partnumberid">Producto<span style="color: red">*</span></label>
                                <input type="text" class="form-control" id="partnumberid" name="partnumberid"
                                       value="{{$detail->partnumberid}}"
                                       onKeyUp=this.value=this.value.toUpperCase()
                                >
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="quantity">Cantidad<span style="color: red">*</span></label>
                                <input type="text" class="form-control numeric" id="quantity" name="quantity"
                                       value="{{$detail->quantity}}"
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
                    <button type="submit" class="btn waves-effect waves-light btn-outline-secondary btn-sm ">
                        <img src="{{asset('/ico/accept.png')}}" alt="" class="img-16">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
