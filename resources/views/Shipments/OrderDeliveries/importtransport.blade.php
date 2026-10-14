<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" tabindex="-1"
     aria-hidden="true" style="display: none;" id="modal-transport">
    <form method="post" action="{{url('importtransportshipment')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 12px;font-style: normal;font-weight: 400;">
                        Importar asignación de transporte para embarques.
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
<div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="list-group">
                                <li class="list-group-item font-12 d-flex justify-content-between align-items-center">
                                    <span>Seleccione archivo a importar (Xlsx)</span>
                                    <a href="{{ route('import-transport.template') }}"
                                       class="btn btn-outline-success btn-sm waves-effect waves-light"
                                       title="Descargar plantilla Excel">
                                        <img src="{{ asset('/ico/excel.png') }}" alt="" class="img-16">
                                        Descargar plantilla
                                    </a>
                                </li>
                                <li class="list-group-item font-12">
                                    <input type="file" class="form-control" required id="file" name="file"
                                           accept=".xls,.xlsx">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-12">
                            <table class="table table-bordered table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>Descripción</th>
                                    <th>Cabecera</th>
                                    <th>Tipo de dato</th>
                                    <th>Formato</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Embarque</td>
                                    <td>shipment</td>
                                    <td>Texto</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Clave de transporte</td>
                                    <td>carrier</td>
                                    <td>Texto</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Nombre de transporte</td>
                                    <td>name</td>
                                    <td>Texto</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Cita de carga en cedis</td>
                                    <td>arrival_date</td>
                                    <td>DateTime</td>
                                    <td>aaaa-mm-dd H:mm</td>
                                </tr>
                                <tr>
                                    <td>Cita de entrega a cliente</td>
                                    <td>appointment_date</td>
                                    <td>DateTime</td>
                                    <td>aaaa-mm-dd H:mm</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm waves-effect waves-light"
                            data-dismiss="modal">
                        <img src="{{ asset('/pics/adcancelar.png') }}" alt="">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-outline-secondary btn-sm waves-effect waves-light">
                        <img src="{{ asset('/pics/adaceptar.png') }}" alt="">
                        Aceptar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

