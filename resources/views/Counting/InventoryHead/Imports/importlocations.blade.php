<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" tabindex="-1"
     aria-hidden="true" style="display: none;" id="modal-imporlocationinventory">
    <form method="post" action="{{url('importlocationinventory')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 12px;font-style: normal;font-weight: 400;">
                        Importar ubicaciones de inventario.
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="inventoryid">Número de inventario</label>
                                <input type="text" class="form-control" readonly value="{{$inventoryhead->id}}" id="inventoryid" name="inventoryid">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="list-group">
                                <li class="list-group-item font-12"> Seleecione archivo a importar (CSV)</li>
                                <li class="list-group-item font-12">
                                    <input type='file' name="file" id="file" required>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="row mt-4" >
                        <div class="col-lg-12">
                            <table class="table table-bordered table-condensed" >
                                <thead>
                                <tr>
                                    <th>Nombre de columna</th>
                                    <th>Descriptión</th>
                                    <th>Tipo de dato</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>centerid</td>
                                    <td>C.Distribución</td>
                                    <td>Texto</td>
                                </tr>
                                <tr>
                                    <td>warehouseid</td>
                                    <td>Bodega</td>
                                    <td>Texto</td>
                                </tr>
                                <tr>
                                    <td>inventoryid</td>
                                    <td>N°.Inventario</td>
                                    <td>Númerico</td>
                                </tr>
                                <tr>
                                    <td>locationid</td>
                                    <td>Ubicación</td>
                                    <td>Texto</td>
                                </tr>
                                <tr>
                                    <td>cardid</td>
                                    <td>Cédula</td>
                                    <td>Texto</td>
                                </tr>
                                <tr>
                                    <td>areaid</td>
                                    <td>Área</td>
                                    <td>Texto</td>
                                </tr>
                                <tr>
                                    <td>aisleid</td>
                                    <td>Pasillo</td>
                                    <td>Texto</td>
                                </tr>
                                </tbody>
                            </table>
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

