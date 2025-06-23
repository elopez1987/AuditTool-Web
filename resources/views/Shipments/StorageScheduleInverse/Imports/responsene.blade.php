<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" tabindex="-1"
     aria-hidden="true" style="display: none;" id="modal-responsene">
    <form method="post" action="{{url('importresponsene')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 12px;font-style: normal;font-weight: 400;">
                        Importar respuesta de NE
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="file">Archivo a importar</label>
                                <input type="file" class="form-control" required id="file" name="file" accept=".xlsx, .xls">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <table class="table table-no-bordered table-condensed">
                                <tr>
                                    <thead>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Formato</th>
                                    </thead>
                                </tr>
                                <tbody>
                                <tr>
                                    <td>delivery</td>
                                    <td>Factura</td>
                                    <td>String</td>
                                </tr>
                                <tr>
                                    <td>zare</td>
                                    <td>No.Zare</td>
                                    <td>String</td>
                                </tr>
                                <tr>
                                    <td>ne</td>
                                    <td>No.NE</td>
                                    <td>String</td>
                                </tr>
                                <tr>
                                    <td>adjust</td>
                                    <td>¿Aplica ajuste ?</td>
                                    <td>String (S / N)</td>
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

