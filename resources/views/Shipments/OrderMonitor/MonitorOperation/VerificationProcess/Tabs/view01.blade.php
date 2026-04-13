<div class="tab-pane" id="view01" role="tabpanel">
    <div class="row">
        <div class="col-lg-10">
            <table
                class="table  table-condensed table-bordered"
                style="width:80%">
                <thead>
                <tr>
                    <th>Lpn Original</th>
                    <th>Lpn Fusionado</th>
                    <th>Usuario</th>
                    <th>Fecha de Movimiento</th>
                </tr>
                </thead>
                <tbody>
                @foreach($lpsFusionados as $fusion)
                    <tr>
                        <td>{{$fusion->lpnid}}</td>
                        <td>{{$fusion->lpnchild}}</td>
                        <td>{{$fusion->user ? $fusion->user->name : ''}}</td>
                        <td>{{$fusion->created_at}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
