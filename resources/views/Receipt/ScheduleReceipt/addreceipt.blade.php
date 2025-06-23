<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;" id="modal-add">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded">
            <div class="modal-header">
                <h5 class="modal-title"> Programar descarga</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="centerid">Centros de distribución <span style="color: red">*</span></label>
                            <select name="centeridadd" id="centeridadd" class="form-control select2">
                                @foreach($centers as $center)
                                    <option value="{{$center->centerid}}"> {{$center->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="warehouseid">Bodega <span style="color: red">*</span></label>
                            <select name="warehouseidadd" id="warehouseidadd" class="form-control select2">
                                @foreach($warehouses as $warehouse)
                                    <option value="{{$warehouse->warehouseid}}"> {{$warehouse->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="providerid">Proveedor<span style="color: red">*</span></label>
                            <select name="provideridadd" id='provideridadd' required class="form-control select2">
                                <option value="" selected>Seleccione una ópcion</option>
                                @foreach($providers as $model)
                                    <option value="{{$model->id}}"> {{$model->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 ">
                        <div class="form-group">
                            <label for="addasndate">Fecha de descarga <span style="color: red">*</span></label>
                            <input type="text" class="form-control datefilter"  id="addasndate" name="addasndate"
                                   value="{{old('addasndate')}}"
                            >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="carrierid">Linea de transporte<span style="color: red">*</span></label>
                            <select name="carrierid" id='carrierid' required class="form-control select2">
                                <option value="" selected>Seleccione una ópcion</option>
                                @foreach($carriers as $model)
                                    <option value="{{$model->id}}"> {{$model->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="razonstatusid">Concepto de entrada<span style="color: red">*</span></label>
                            <select name="razonstatusid" id='razonstatusid' required class="form-control select2">
                                <option value="" selected>Seleccione una ópcion</option>
                                @foreach($reasons as $model)
                                    <option value="{{$model->id}}"> {{$model->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn waves-effect waves-light btn-outline-secondary btn-sm " data-dismiss="modal">
                    <img src="{{asset('/ico/close.png')}}" alt="" class="img-16">
                    Cancelar
                </button>
                <button class="btn waves-effect waves-light btn-outline-secondary btn-sm" id="saveReceipt">
                    <img src="{{asset('/ico/accept.png')}}" alt="" class="img-16" >
                    Aceptar
                </button>
            </div>
        </div>
    </div>
</div>
