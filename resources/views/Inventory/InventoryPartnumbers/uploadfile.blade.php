<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" tabindex="-1"
     aria-hidden="true" style="display: none;" id="modal-upload">
    <form method="post" action="{{url('uploadfilepartnumbers')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 12px;font-style: normal;font-weight: 400;">
                        Importar catálogo de productos
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="list-group">
                                <li class="list-group-item font-12"> Seleecione archivo a importar (Excel)</li>
                                <li class="list-group-item font-12">
                                    <input type='file' name="file" id="file" required>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mt-3">
                            <table class="table table-no-bordered table-condensed">
                                <tr>
                                    <thead>
                                    <th>Campo</th>
                                    <th>Nombre</th>
                                    <th>Formato</th>
                                    <th>Tipo</th>
                                    </thead>
                                </tr>
                                <tbody>
                                <tr>
                                    <td>prtnum</td>
                                    <td>Número de Sku</td>
                                    <td>String</td>
                                    <td style="color: red">Obligatorio</td>
                                </tr>
                                <tr>
                                    <td>lngdsc</td>
                                    <td>Descripción</td>
                                    <td>String</td>
                                    <td>Obligatorio</td>
                                </tr>
                                <tr>
                                    <td>abccod</td>
                                    <td>Tipo de acomodo</td>
                                    <td>String</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>shelflife</td>
                                    <td>Tiempo de vida</td>
                                    <td>Numeric</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>untcas</td>
                                    <td>Unidades por caja</td>
                                    <td>Numeric</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>untpal</td>
                                    <td>Cajas por pallet</td>
                                    <td>Numeric</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>category01id</td>
                                    <td>Categoria 01</td>
                                    <td>String</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>category02id</td>
                                    <td>Categoria 02</td>
                                    <td>String</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>barcode</td>
                                    <td>Código de barra</td>
                                    <td>String</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>location</td>
                                    <td>Ubicación</td>
                                    <td>String</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>codeean</td>
                                    <td>Código EAN</td>
                                    <td>String</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>codedun</td>
                                    <td>DUN 13</td>
                                    <td>String</td>
                                    <td></td>
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

