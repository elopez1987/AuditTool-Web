<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" tabindex="-1"
     aria-hidden="true" style="display: none;" id="modal-upload">
        <form method="POST" action="{{url('importincidences')}}" enctype="multipart/form-data">
            @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 12px;font-style: normal;font-weight: 400;">
                        Importar incidencias
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="list-group">
                                <li class="list-group-item font-11"> Seleecione archivo a importar (Excel)</li>
                                <li class="list-group-item font-12">
                                    <input type='file' name="file" id="file" required>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <table class="table table-bordered table-condensed">
                                <tr>
                                    <span class="font-14 text-dark mb-3">Importa una lista de embarques y su detalle para reportar una incidencia.</span>
                                </tr>
                                <tr>
                                    <thead>
                                    <th>Campo</th>
                                    <th>Nombre</th>
                                    <th>Formato</th>
                                    </thead>
                                </tr>
                                <tbody>
                                <tr>
                                    <td>shipmentid</td>
                                    <td>Embarque</td>
                                    <td>String</td>
                                </tr>
                                <tr>
                                    <td>delivery</td>
                                    <td>Factura</td>
                                    <td>String</td>
                                </tr>
                                <tr>
                                    <td>razoncode</td>
                                    <td>Motivo de la incidencia</td>
                                    <td>String</td>
                                </tr>
                                <tr>
                                    <td>downloaddate</td>
                                    <td>Fecha de descarga</td>
                                    <td>Date</td>
                                </tr>
                                <tr>
                                    <td>partnumberid</td>
                                    <td>Número de parte / Sku</td>
                                    <td>String</td>
                                </tr>
                                <tr>
                                    <td>quantity</td>
                                    <td>Cantidad</td>
                                    <td>Number</td>
                                </tr>
                                <tr>
                                    <td>unitmeasure</td>
                                    <td>Unidad de medida</td>
                                    <td>String</td>
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

