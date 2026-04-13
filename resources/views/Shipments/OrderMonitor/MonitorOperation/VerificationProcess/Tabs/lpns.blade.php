<div class="tab-pane" id="lpns" role="tabpanel">
    <div class="row">
        <div class="col-lg-8 col-md-6 col-sm-12">
            <table
                class="table table-condensed table-bordered"
                style="width:80%">
                <thead>
                <tr>
                    <th>Número de lpn</th>
                    <th>Escaneado por</th>
                    <th>Fecha</th>
                    <th>Estatus</th>
                    <th>Eliminar</th>
                </tr>
                </thead>
                <tbody>
                @foreach($lpns as $lpn)
                    <tr>
                        <td>{{$lpn->lpnid}}</td>
                        <td>{{$lpn->user ? $lpn->user->name : ''}}</td>
                        <td>{{$lpn->scandate}}</td>
                        <td>{{$lpn->status ? $lpn->status->name : ''}}</td>
                        <td>
                            @if( $orderDelivery->check->finishdate == null)
                                <button class="btn btn-sm btn-outline-info"
                                        onclick="deleteLpn( {!! $lpn->id !!} )">
                                    Desasignar
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
