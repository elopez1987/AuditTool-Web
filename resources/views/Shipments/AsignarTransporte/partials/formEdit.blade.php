<div class="card-input">
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="id">Linea de transporte</label>
                <input type="text" class="form-control" readonly value="{{$order->carrier ? $order->carrier->name : ''}}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="id">Embarque</label>
                <input type="text" class="form-control" readonly value="{{$order->shipmentid}}">
            </div>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="boxnumber">Cliente <span style="color: red">*</span></label>
                <input type="text" class="form-control" readonly
                       value="{{$order->customer ? $order->customer->name : ''}}">
            </div>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="barcode">Destino <span style="color: red">*</span></label>
                <input type="text" class="form-control" readonly
                       value="{{$order->customer ? $order->customer->city : ''}}">
            </div>
        </div>
    </div>
</div>
<hr class="m-t-0">

<div class="row">
    <div class="col-lg-10">
        <div class="card-input">
            <div class="card-body ">
                <div class="row ">
                    <div class="col-lg-12">
                        <table class="table table-bordered table-condensed table-striped tablegeneral">
                            <thead>
                            <tr>
                                <th>Seleccionar</th>
                                <th>Folio de unidad</th>
                                <th>Placa de caja</th>
                                <th>Código de barra</th>
                                <th>Eco.Caja</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($units as $unit)
                                <tr>
                                    <td width="15%" class="radio-switch">
                                        <input type="radio" value="{{$unit->id}}"
                                               name="folionumber" id="folionumber" data-size="mini"
                                               data-on-color="success" data-on-text="Seleccionado"
                                               data-off-text="Asignar">
                                    </td>
                                    <td class="font-12">{{$unit->id}}</td>
                                    <td class="font-12">{{$unit->boxnumber}}</td>
                                    <td class="font-12">{{$unit->barcode}}</td>
                                    <td class="font-12">{{$unit->econumber}}</td>
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

