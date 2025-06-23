<div class="tab-pane" id="detailslpns" role="tabpanel">
    <div class="row">
        <div class="col-lg-10">
            <table
                class="table table-striped table-condensed table-bordered"
                style="width:80%">
                <thead>
                <tr>
                    <th>IDH</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Número de lpn</th>
                    <th>Escaneado por</th>
                </tr>
                </thead>
                <tbody>
                @foreach($detailLpns as $lpn)
                    <tr>
                        <td>{{$lpn->partnumberid}}</td>
                        <td>{{$lpn->description}}</td>
                        <td>{{$lpn->total}}</td>
                        <td>{{$lpn->lpnid}}</td>
                        <td>{{$lpn->createuser}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
