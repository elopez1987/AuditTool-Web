<div class="tab-pane" id="view02" role="tabpanel">
    <div class="row">
        <div class="col-lg-10">
            <table
                class="table table-striped table-condensed table-bordered"
                style="width:80%">
                <thead>
                <tr>
                    <th>Número de Lpn</th>
                    <th>Numero de parte</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Usuario</th>
                    <th>Fecha de Movimiento</th>
                    <th>Tipo</th>
                </tr>
                </thead>
                <tbody>
                @foreach($kardexMoveBox as $kardex)
                    <tr>
                        <td>{{$kardex->lpnid}}</td>
                        <td>{{$kardex->sku ? $kardex->sku->prtnum : ''}}</td>
                        <td>{{$kardex->sku ? $kardex->sku->lngdsc : ''}}</td>
                        <td>{{$kardex->quantity}}</td>
                        <td>{{$kardex->user ? $kardex->user->name : ''}}</td>
                        <td>{{$kardex->created_at}}</td>
                        <td>{{$kardex->type}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
