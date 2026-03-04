<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-importdata-{{$storageevidenceprocess->recordid}}">
    <form method="post" action="{{url('importrequestshipmenttoload')}}" enctype="multipart/form-data">
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 14px;font-style: normal;font-weight: 400;">Importar
                        solicitud de material</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="text" class="form-control" readonly
                                   value="{{$storageevidenceprocess->recordid}}"
                                   id="recordid" name="recordid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <ul class="list-group">
                                        <li class="list-group-item font-12"> Seleecione archivo a importar (Excel)</li>
                                        <li class="list-group-item font-12">
                                            <span>Embarque : {{$storageevidenceprocess->shipmentid}}</span></li>
                                        <li class="list-group-item font-12">
                                            <input type='file' name="file" id="file" required>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-8">
                            <table class="table table-condensed table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Columna</th>
                                    <th>Nombre de cabecera</th>
                                    <th>Tipo</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Numero de parte</td>
                                    <td>Material</td>
                                    <td>Texto</td>
                                </tr>
                                <tr>
                                    <td>Cantidad</td>
                                    <td>Quantity</td>
                                    <td>Número</td>
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
