<div class="modal fade bs-example-modal-xl" role="dialog" aria-labelledby="myLargeModalLabel" tabindex="-1"
     aria-hidden="true" style="display: none;" id="modal-upload">
    <form method="POST" action="{{url('uploadfiledeliveries')}}" enctype="multipart/form-data">
        @csrf
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 12px;font-style: normal;font-weight: 400;">
                        Importar embarques
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="list-group">
                                <li class="list-group-item font-12 d-flex justify-content-between align-items-center">
                                    <span>Seleccione archivo a importar (CSV Delimitado por comas)</span>
                                    <a href="{{ route('uploadfiledeliveries.template') }}"
                                       class="btn btn-outline-success btn-sm waves-effect waves-light"
                                       title="Descargar plantilla CSV">
                                        <img src="{{ asset('/ico/csv.png') }}" alt="" class="img-16">
                                        Descargar plantilla
                                    </a>
                                </li>
                                <li class="list-group-item font-12">
                                    <input type="file" class="form-control" name="file" id="file" required
                                           accept=".csv,.txt">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-12">
                            <table class="table table-bordered table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th>Nombre en encabezado</th>
                                    <th>Descripción</th>
                                    <th>Formato</th>
                                    <th>Requerido</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>workday</td>
                                    <td>Fecha de trabajo</td>
                                    <td>dd/mm/aaaa</td>
                                    <td>SI</td>
                                </tr>
                                <tr>
                                    <td>shipmentid</td>
                                    <td>Número de embarque</td>
                                    <td>Texto</td>
                                    <td>SI</td>
                                </tr>
                                <tr>
                                    <td>customerid</td>
                                    <td>Clave de cliente</td>
                                    <td>Numérico</td>
                                    <td>SI</td>
                                </tr>
                                <tr>
                                    <td>turnid</td>
                                    <td>Clave de turno (1,2,3)</td>
                                    <td>Numérico</td>
                                    <td>SI</td>
                                </tr>
                                <tr>
                                    <td>carrierid</td>
                                    <td>Clave de línea de transporte</td>
                                    <td>Numérico</td>
                                    <td>SI</td>
                                </tr>
                                <tr>
                                    <td>loadingdate</td>
                                    <td>Fecha de carga</td>
                                    <td>dd/mm/aaaa H:mm</td>
                                    <td>SI</td>
                                </tr>
                                <tr>
                                    <td>appointdate</td>
                                    <td>Fecha de entrega a cliente</td>
                                    <td>dd/mm/aaaa H:mm</td>
                                    <td>SI</td>
                                </tr>
                                <tr>
                                    <td>confirmation</td>
                                    <td>Número de confirmación de cliente</td>
                                    <td>Texto</td>
                                    <td>NO</td>
                                </tr>
                                <tr>
                                    <td>dvbusiness</td>
                                    <td>Division de negocio</td>
                                    <td>Texto</td>
                                    <td>NO</td>
                                </tr>
                                <tr>
                                    <td>responsible</td>
                                    <td>Responsable del embarque</td>
                                    <td>Texto</td>
                                    <td>NO</td>
                                </tr>
                                <tr>
                                    <td>picking</td>
                                    <td>Total Picking</td>
                                    <td>Numérico</td>
                                    <td>NO</td>
                                </tr>
                                <tr>
                                    <td>pallets</td>
                                    <td>Total Pallets</td>
                                    <td>Numérico</td>
                                    <td>NO</td>
                                </tr>
                                <tr>
                                    <td>weight</td>
                                    <td>Peso Total</td>
                                    <td>Numérico</td>
                                    <td>NO</td>
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
