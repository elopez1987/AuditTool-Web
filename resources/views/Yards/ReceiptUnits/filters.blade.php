<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-filter">
    <div class="modal-dialog modal-xl">
        <div class="modal-content rounded">
            <div class="modal-header">
                <h5 class="modal-title"> Agregar filtros</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-lg-6 col-md-6 col-sm-12 ">
                        <div class="form-group">
                            <label for="rasonid">Concepto de entrada </label>
                            <select name="rasonid" id="rasonid" class="form-control select2">
                                <option value="" selected>Seleccione una opción</option>
                                @foreach($rason as $model)
                                    <option value="{{$model->id}}"> {{$model->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="carrierid">Linea de transporte</label>
                            <select name="carrierid" id="carrierid" class="form-control select2">
                                <option value="" selected>Seleccione una opción</option>
                                @foreach($carriers as $model)
                                    <option value="{{$model->id}}"> {{$model->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="trucknumber">Placas de Tracto</label>
                            <input type="text" class="form-control" id="trucknumber" name="trucknumber"
                                   onKeyUp=this.value=this.value.toUpperCase()
                            >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="statusid">Estatus <span style="color: red">*</span></label>
                            <select name="statusid" id="statusid" class="form-control select2">
                                <option value="" selected>Seleccione una opción</option>
                                @foreach($status as $model)
                                    <option value="{{$model->statusid}}"> {{$model->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="location">Ubicación</label>
                            <input type="text" class="form-control" id="location" name="location"
                                   onKeyUp=this.value=this.value.toUpperCase()
                            >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="unitstatusid">Estatus de unidad</label>
                            <select name="unitstatusid" id="unitstatusid" class="form-control select2">
                                <option value="" selected>Seleccione una opción</option>
                                @foreach($unitstatus as $model)
                                    <option value="{{$model->id}}"> {{$model->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-secondary btn-md  waves-effect waves-light"
                        data-dismiss="modal" onclick="reloadtable()">
                    <i class="ti ti-search text-success"></i>
                    Buscar
                </button>

            </div>
        </div>
    </div>
</div>
