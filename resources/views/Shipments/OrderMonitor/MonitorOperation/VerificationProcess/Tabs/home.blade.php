<div class="tab-pane active" id="home" role="tabpanel">
    <div class="row">
        <div class="col-lg-12 co-md-10 col-sm-12">
            <table
                class="table table-condensed table-bordered  table-hover tablegeneral" style="width:100%">
                <thead>
                <tr>
                    <th>Embarque</th>
                    <th>Número de parte</th>
                    <th>Descripción</th>
                    <th>Unidad de medida</th>
                    <th>Cantidad solicitada</th>
                    <th>Cantidad verificada</th>
                    <th>Diferencia</th>
                    <th>Concepto de faltante</th>
                </tr>
                </thead>
                <tbody>
                @foreach($checks as $check)
                    <tr>
                        <td>{{$check->shipmentid}}</td>
                        <td>{{$check->sku}}</td>
                        <td>{{$check->description}}</td>
                        <td>{{$check->unitmeasureid}}</td>
                        <td>{{$check->requested}}</td>
                        <td>
                            @if(empty($check->totalver))
                                0
                            @else
                                {{$check->totalver}}
                            @endif
                        </td>
                        <td @if($check->quantityfail > 0 ) class="bg-warning text-center"
                            @elseif($check->quantityfail < 0 || $check->totalver == 0) class="bg-danger text-center text-white"
                            @elseif($check->quantityfail ==  0) class="bg-success text-center"
                            @endif>

                            {{ $check->requested - $check->totalver }}
                        </td>
                        <td>
                            @if($check->quantityfail != '0' || $check->quantityfail < 0)
                                @if($check->missingconceptid != null)
                                    {{$check->concept}}
                                @else
                                    <a href=""
                                       data-target="#modal-verific-{{$check->recordid}}"
                                       data-toggle="modal">
                                        <img
                                            src="{{asset('/ico/shipmentheader (2).png')}}"
                                            alt=""
                                            class="img-16">
                                        <span
                                            style="font-size: 11px;"> Verificación manual</span>
                                    </a>
                                    @include('Shipments.OrderMonitor.MonitorOperation.VerificationProcess.verific')
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <hr class="mt-5">
    <div class="row mt-5">
        <div class="col-lg-8">
            <table   class="table table-condensed table-bordered table-striped  " style="width:100%">
                <thead>
                <tr>
                    <th>Número de parte</th>
                    <th>Descripción</th>
                    <th>Cantidad Solicitada</th>
                    <th>Cajas por pallet</th>
                    <th>Full Palelts</th>
                    <th>Cajas Picking</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pedidos as $item)
                    <tr>
                        <td>{{$item->product ? $item->product->prtnum : ''}}</td>
                        <td>{{$item->product ? $item->product->lngdsc : ''}}</td>
                        <td>{{$item->quantityrequested}}</td>
                        <td>{{$item->product ? $item->product->untpal : ''}}</td>
                        <td>{{ intdiv($item->quantityrequested,$item->product->untpal) }}</td>
                        <td>{{ fmod($item->quantityrequested,$item->product->untpal) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

