<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" tabindex="-1"
     aria-hidden="true" style="display: none;" id="modal-responseinventory">
    <form method="post" action="{{url('importresponseinventory')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 12px;font-style: normal;font-weight: 400;">
                        Importar respuestas de inventario.
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group d-flex justify-content-between align-items-center">
                                <label for="file" class="mb-0">Archivo a importar</label>
                                <a href="{{ route('importresponseinventory.template') }}"
                                   class="btn btn-outline-success btn-sm waves-effect waves-light"
                                   title="Descargar plantilla Excel">
                                    <img src="{{ asset('/ico/excel.png') }}" alt="" class="img-16">
                                    Descargar plantilla
                                </a>
                            </div>
                            <input type="file" class="form-control" required id="file" name="file"
                                   accept=".xlsx,.xls">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <table class="table table-bordered table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>Cabecera</th>
                                    <th>Descripción</th>
                                    <th>Formato</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>shipmentid</td>
                                    <td>No. Embarque</td>
                                    <td>Texto</td>
                                </tr>
                                <tr>
                                    <td>delivery</td>
                                    <td>Factura</td>
                                    <td>Texto</td>
                                </tr>
                                <tr>
                                    <td>note</td>
                                    <td>Comentario</td>
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
