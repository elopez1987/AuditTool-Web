<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" tabindex="-1"
     aria-hidden="true" style="display: none;" id="modal-comment">
    <form method="post" action="{{url('importcommentshipment')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 12px;font-style: normal;font-weight: 400;">
                        Importar comentarios para embarques
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="list-group">
                                <li class="list-group-item font-12"> Seleecione archivo a importar (Csv)</li>
                                <li class="list-group-item font-12">
                                    <input type="file" class="form-control" required id="file" name="file"
                                           accept=".csv">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-12">
                            <table class="table table-bordered table-condensed table-striped">
                                    <thead>
                                        <tr>
                                            <th>Descripción</th>
                                            <th>Cabecera</th>
                                            <th>Tipo de dato</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Embarque</td>
                                            <td>shipmentid</td>
                                            <td>Texto</td>
                                        </tr>
                                        <tr>
                                            <td>Comentario</td>
                                            <td>comment</td>
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

