<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" tabindex="-1"
     aria-hidden="true" style="display: none;" id="modal-upload">
    <form method="post" action="{{url('uploadfileordercustomers')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 12px;font-style: normal;font-weight: 400;">
                        Importar Clientes
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="list-group">
                                <li class="list-group-item font-12 d-flex justify-content-between align-items-center">
                                    <span>Seleccione archivo a importar (CSV)</span>
                                    <a href="{{ route('uploadfileordercustomers.template') }}"
                                       class="btn btn-outline-success btn-sm waves-effect waves-light"
                                       title="Descargar plantilla CSV">
                                        <img src="{{ asset('/ico/csv.png') }}" alt="" class="img-16">
                                        Descargar plantilla
                                    </a>
                                </li>
                                <li class="list-group-item font-12">
                                    <input type="file" class="form-control" name="file" id="file" required
                                           accept=".csv,.txt,.xlsx,.xls">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <p class="font-14 text-dark mb-2">
                                Importa una lista de clientes. El archivo debe contener los siguientes datos y formatos.
                            </p>
                            <table class="table table-bordered table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>Campo</th>
                                    <th>Nombre</th>
                                    <th>Formato</th>
                                    <th>Requerido</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>centerid</td>
                                    <td>C.Distribución</td>
                                    <td>Texto</td>
                                    <td>SI</td>
                                </tr>
                                <tr>
                                    <td>warehouseid</td>
                                    <td>Bodega</td>
                                    <td>Texto</td>
                                    <td>SI</td>
                                </tr>
                                <tr>
                                    <td>shiptoid</td>
                                    <td>Cliente</td>
                                    <td>Texto/Numérico</td>
                                    <td>SI</td>
                                </tr>
                                <tr>
                                    <td>name</td>
                                    <td>Nombre</td>
                                    <td>Texto</td>
                                    <td>SI</td>
                                </tr>
                                <tr>
                                    <td>city</td>
                                    <td>Ciudad</td>
                                    <td>Texto</td>
                                    <td>SI</td>
                                </tr>
                                <tr>
                                    <td>timetransfer</td>
                                    <td>Tiempo de trayecto (Min)</td>
                                    <td>Numérico</td>
                                    <td>SI</td>
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
