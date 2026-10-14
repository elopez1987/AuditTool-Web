<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" tabindex="-1"
     aria-hidden="true" style="display: none;" id="modal-upload">
    <form method="POST" action="{{url('uploadfilecarriers')}}" enctype="multipart/form-data">
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 12px;font-style: normal;font-weight: 400;">
                        Importar Líneas de Transporte
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="list-group">
                                <li class="list-group-item font-12 d-flex justify-content-between align-items-center">
                                    <span>Seleccione archivo a importar (Excel)</span>
                                    <a href="{{ route('uploadfilecarriers.template') }}"
                                       class="btn btn-outline-success btn-sm waves-effect waves-light"
                                       title="Descargar plantilla Excel">
                                        <img src="{{ asset('/ico/excel.png') }}" alt="" class="img-16">
                                        Descargar plantilla
                                    </a>
                                </li>
                                <li class="list-group-item font-12">
                                    <input type="file" class="form-control" name="file" id="file" required
                                           accept=".xlsx,.xls">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <p class="font-14 text-dark mb-2">
                                Importa una lista de líneas de transporte. El archivo debe contener los siguientes datos y formatos.
                            </p>
                            <table class="table table-bordered table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>Campo</th>
                                    <th>Nombre</th>
                                    <th>Formato</th>
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
                                    <td>carrierid</td>
                                    <td>Vendor SAP</td>
                                    <td>Texto</td>
                                </tr>
                                <tr>
                                    <td>name</td>
                                    <td>Nombre</td>
                                    <td>Texto</td>
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
