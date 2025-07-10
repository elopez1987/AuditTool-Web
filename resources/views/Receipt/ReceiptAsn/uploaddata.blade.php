<div class="modal fade bs-example-modal-xl" role="dialog" aria-labelledby="myLargeModalLabel" tabindex="-1" aria-hidden="true" style="display: none;" id="modal-uploaddata-{{$receiptasn->id}}">
    <form method="POST" action="{{url('importdatareceipt/' . $receiptasn->id)}}" enctype="multipart/form-data">
        @csrf
        {{ method_field('PUT') }}
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 12px;font-style: normal;font-weight: 400;">
                        Importar datos
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="list-group">
                                <li class="list-group-item font-12"> Seleecione archivo a importar.
                                </li>
                                <li class="list-group-item font-12">
                                    <input type='file' name="file" id="file" required>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <table  class="table table-bordered table-hover table-condensed table-striped" style="width: 100% !important;">
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
                                        <td>sku</td>
                                        <td>Número de parte del material</td>
                                        <td>Texto</td>
                                        <td>SI</td>
                                    </tr>
                                    <tr>
                                        <td>batch</td>
                                        <td>Lote</td>
                                        <td>Texto</td>
                                        <td>SI</td>
                                    </tr>
                                    <tr>
                                        <td>category</td>
                                        <td>Categoria</td>
                                        <td>Texto ( NAC / IMP)</td>
                                        <td>SI</td>
                                    </tr>
                                    <tr>
                                        <td>quantity</td>
                                        <td>Cantidad</td>
                                        <td>Numérico</td>
                                        <td>SI</td>
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

