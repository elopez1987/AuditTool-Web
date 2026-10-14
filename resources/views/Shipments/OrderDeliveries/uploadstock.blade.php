<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" tabindex="-1"
     aria-hidden="true" style="display: none;" id="modal-uploadstock">
    <form method="POST" action="{{url('uploadFileCheck')}}" enctype="multipart/form-data">
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 12px;font-style: normal;font-weight: 400;">
                        Importar pedidos
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="list-group">
                                <li class="list-group-item font-12 d-flex justify-content-between align-items-center">
                                    <span>Seleccione el archivo de Excel a importar.</span>
                                    <a href="{{ route('uploadFileCheck.template') }}"
                                       class="btn btn-outline-success btn-sm waves-effect waves-light"
                                       title="Descargar plantilla Excel">
                                        <img src="{{ asset('/ico/excel.png') }}" alt="" class="img-16">
                                        Descargar plantilla
                                    </a>
                                </li>
                                <li class="list-group-item font-12">
                                    <input type="file" class="form-control" name="file" id="file" required
                                           accept=".xls,.xlsx">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-12">
                            <table class="table table-condensed table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th>Campo</th>
                                    <th>Llave de cabecera</th>
                                    <th>Tipo de dato</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Embarques</td>
                                    <td>shipmentid</td>
                                    <td>Texto</td>
                                </tr>
                                <tr>
                                    <td>Número de parte / Sku / IDH</td>
                                    <td>partnumberid</td>
                                    <td>Texto</td>
                                </tr>
                                <tr>
                                    <td>Cantidad</td>
                                    <td>quantity</td>
                                    <td>Entero</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm waves-effect waves-light"
                            data-dismiss="modal">
                        <img src="{{asset('/pics/adcancelar.png')}}" alt="">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-outline-secondary btn-sm waves-effect waves-light">
                        <img src="{{asset('/pics/adaceptar.png')}}" alt="">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
