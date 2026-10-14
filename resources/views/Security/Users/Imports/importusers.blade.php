<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" tabindex="-1"
     aria-hidden="true" style="display: none;" id="modal-importusers">
    <form method="post" action="{{url('importusers')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 12px;font-style: normal;font-weight: 400;">
                        Importar usuarios
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="list-group">
                                <li class="list-group-item font-12 d-flex justify-content-between align-items-center">
                                    <span>Seleccione archivo a importar (Excel)</span>
                                    <a href="{{ route('importusers.template') }}"
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

                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <table class="table table-bordered table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>Nombre del campo</th>
                                    <th>Descripción</th>
                                    <th>Tipo de dato</th>
                                    <th>Requerido</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>centerid</td>
                                    <td>ID del centro de distribución</td>
                                    <td>Texto</td>
                                    <td>SI</td>
                                </tr>
                                <tr>
                                    <td>warehouseid</td>
                                    <td>ID de la bodega</td>
                                    <td>Texto</td>
                                    <td>SI</td>
                                </tr>
                                <tr>
                                    <td>username</td>
                                    <td>Nombre de usuario (mín. 4 caracteres)</td>
                                    <td>Texto</td>
                                    <td>SI</td>
                                </tr>
                                <tr>
                                    <td>name</td>
                                    <td>Nombre del usuario</td>
                                    <td>Texto</td>
                                    <td>SI</td>
                                </tr>
                                <tr>
                                    <td>email</td>
                                    <td>Correo del usuario</td>
                                    <td>Email</td>
                                    <td>SI</td>
                                </tr>
                                <tr>
                                    <td>roleid</td>
                                    <td>ID numérico o name del perfil a asignar</td>
                                    <td>Texto</td>
                                    <td>SI</td>
                                </tr>
                                </tbody>
                            </table>
                            <p class="font-12 text-muted mb-0">
                                Contraseña inicial para usuarios nuevos: <strong>Temporal12##</strong>
                            </p>
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
