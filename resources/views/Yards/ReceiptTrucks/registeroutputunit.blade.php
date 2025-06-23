<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none;" id="modal-registeroutputunit-{{$id}}">
    <form method="POST" action="{{url('registerOutPutTruck/' . $id)}}" enctype="multipart/form-data">
        {{ method_field('PUT') }}
        @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="" style="font-size: 12px;font-style: normal;font-weight: 400;">Registrar
                        salida de unidad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="font-12">¿Desea generar la salida del tracto?</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p style="font-size: 16px;color:red;font-weight: 700">Folio : {{$id}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-bordered table-striped table-condensed">
                                <thead>
                                <tr class="bg-light-info font-14">
                                    <th>Nombre del operador</th>
                                    <th>Placas de tracto</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{$operatorname}}</td>
                                    <td>{{$trucknumber}}</td>
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
