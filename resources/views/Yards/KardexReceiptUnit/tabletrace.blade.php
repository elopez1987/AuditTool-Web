<div class="row">
    <div class="col-lg-12">
        <table width="100%" class="table table-bordered table-condensed table-striped " id="table-detailkardex">
            <thead>
            <tr>
                <th class="bg-inverse text-white">Folio</th>
                <th class="bg-inverse text-white">Linea</th>
                <th class="bg-inverse text-white">Placas de Caja</th>
                <th class="bg-inverse text-white">Eco. De Caja</th>
                <th class="bg-inverse text-white">Placas de Tracto</th>
                <th class="bg-inverse text-white">Sellos</th>
                <th class="bg-inverse text-white">Operador</th>
                <th class="bg-inverse text-white">Embarque</th>
                <th class="bg-inverse text-white">E. de Caja</th>
                <th class="bg-inverse text-white">Zona</th>
                <th class="bg-inverse text-white">Ubicación</th>
                <th class="bg-inverse text-white">Evento</th>
                <th class="bg-inverse text-white">F. de Movimiento</th>
                <th class="bg-inverse text-white">Usuario</th>
            </tr>
            </thead>
            <tbody>
            @if (!empty($traces))
                @foreach($traces as $trace)
                    <tr>
                        <td>{{$trace->receiptunitid}}</td>
                        <td>{{$trace->carrier ? $trace->carrier->name : ''}}</td>
                        <td>{{$trace->boxnumber}}</td>
                        <td>{{$trace->unit ? $trace->unit->econumber : ''}}</td>
                        <td>{{$trace->trucknumber}}</td>
                        <td>{{$trace->containerseal}}</td>
                        <td>{{$trace->operatorname}}</td>
                        <td>{{$trace->shipmentid}}</td>
                        <td>{{$trace->unitstatus ? $trace->unitstatus->name : ''}}</td>
                        <td>{{$trace->zone ? $trace->zone->name : ''}}</td>
                        <td>{{$trace->location ? $trace->location->name : ''}}</td>
                        <td>{{$trace->event ? $trace->event->name : ''}}</td>
                        <td>{{$trace->created_at}}</td>
                        <td>{{$trace->user->name}}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
