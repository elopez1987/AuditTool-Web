<div class="modal fade bs-example-modal-xl" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-addshipmenttoreceipt-{{$id}}">
    <form method="POST" action="{{url('updateShipmentiToReceiptUnit/' . $id)}}" enctype="multipart/form-data">
        {{ method_field('PUT') }}
        @csrf
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 11px;font-style: normal;font-weight: 400;">Asignar embarque a la unidad.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-condensed  table-bordered">
                                <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Código de barra</th>
                                    <th>Línea de transporte</th>
                                    <th>Placa</th>
                                    <th>Operador</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{$id}}</td>
                                    <td>{{$barcode}}</td>
                                    <td>{{$carriername}}</td>
                                    <td>{{$boxnumber}}</td>
                                    <td>{{$operatorname}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="shipmentid">Número de embarque
                                    @error("shipmentid")
                                    <span class="message-error">{{$message}}</span>
                                    @enderror
                                    <span style="color: red">*</span></label>
                                <input type="text" class="form-control @error('shipmentid') is-invalid @enderror"
                                       required id="shipmentid" name="shipmentid" onKeyUp=this.value=this.value.toUpperCase();
                                       value="{{old('shipmentid')}}"
                                >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm  waves-effect waves-light"
                            data-dismiss="modal">
                        <img src="{{asset('/ico/close.png')}}" alt="" class="img-16 mr-2 ml-2">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-outline-secondary btn-sm  waves-effect waves-light">
                        <img src="{{asset('/ico/accept.png')}}" alt="" class="img-16 mr-2 ml-2">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
