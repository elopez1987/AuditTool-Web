<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" tabindex="-1"
     aria-hidden="true" style="display: none;" id="modal-upload">
    <form method="POST" action="{{url('import-status/' . $systemschema->schemaid)}}" enctype="multipart/form-data">
        @csrf
        {{ method_field('PUT') }}
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 12px;font-style: normal;font-weight: 400;">
                        Importar Estatus
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
                                    <span class="font-14 text-dark mb-3">Importa una lista de estatus, el archivo debe de contener los siguientes datos y formatos</span>
                                </tr>
                                <tr>
                                    <thead>
                                        <th>Estatus</th>
                                        <th>Nombre</th>
                                        <th>Comentario</th>
                                    </thead>
                                </tr>
                                <tbody>
                                <tr>
                                    <td>statusid</td>
                                    <td>Estatus</td>
                                    <td>String</td>
                                </tr>
                                <tr>
                                    <td>name</td>
                                    <td>Nombre</td>
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

