<div class="row">
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="id">Folio de entrada <span style="color: red">*</span></label>
            <input type="text" class="form-control" readonly id="id" name="id"
                   value="{{$receiptunit->id}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="boxnumber">Placa de caja <span style="color: red">*</span></label>
            <input type="text" class="form-control" readonly  id="boxnumber" name="boxnumber"
                   value="{{$receiptunit->boxnumber}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="barcode">Código de caja <span style="color: red">*</span></label>
            <input type="text" class="form-control" readonly id="barcode" name="barcode"
                   value="{{$receiptunit->boxnumber}}"
                   onKeyUp=this.value=this.value.toUpperCase()
            >
        </div>
    </div>
</div>
<hr class="m-t-0">

<div class="row">
    <div class="col-lg-10">
        <div class="card-input">
            <div class="alert alert-success">
                Seleecione un tracto camion para el arrastre del remolque, en caso de no ver valores en la lista notificar a Vigilancia el ingreso si es necesario.
            </div>
            <div class="card-body ">
                <div class="row mt-2">
                    <div class="col-lg-12">
                        <table class="table table-bordered table-condensed table-striped tablegeneral">
                            <thead>
                            <tr>
                                <th>Seleccionar</th>
                                <th>Placa de tracto</th>
                                <th>Código de barra</th>
                                <th>Operador</th>
                                <th>Telefono</th>
                                <th>N. de Licencia</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($trucks as $truck)
                                <tr>
                                    <td width="15%" class="radio-switch">
                                        <input  type="radio" value="{{$truck->id}}"
                                               name="truckid" id="truckid" data-size="mini"
                                               data-on-color="success" data-on-text="Seleccionado"
                                               data-off-text="Asignar">
                                    </td>
                                    <td>{{$truck->trucknumber}}</td>
                                    <td>{{$truck->barcode}}</td>
                                    <td>{{$truck->operatorname}}</td>
                                    <td>{{$truck->phone}}</td>
                                    <td>{{$truck->drivernumber}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

