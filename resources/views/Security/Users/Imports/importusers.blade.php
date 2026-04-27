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
                                <li class="list-group-item font-12"> Seleecione archivo a importar</li>
                                <li class="list-group-item font-12">
                                    <input type='file' name="file" id="file" required>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-bordered table-condensed ">
                                <thead>
                                    <tr>
                                        <th>Nombre del campo</th>
                                        <th>Descripción</th>
                                        <th>Tipo de dato</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>centerid</td>
                                        <td>ID del centro de distribución</td>
                                        <td>Texto</td>
                                    </tr>
                                    <tr>
                                        <td>warehouseid</td>
                                        <td>ID de la bodega</td>
                                        <td>Texto</td>
                                    </tr>
                                    <tr>
                                        <td>username</td>
                                        <td>Nombre de usuario</td>
                                        <td>Texto</td>
                                    </tr>
                                    <tr>
                                        <td>name</td>
                                        <td>Nombre del usuario</td>
                                        <td>Texto</td>
                                    </tr>
                                    <tr>
                                        <td>email</td>
                                        <td>Correo del usuario</td>
                                        <td>Texto</td>
                                    </tr>
                                    <tr>
                                        <td>roleid</td>
                                        <td>ID del perfil a asginar</td>
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

