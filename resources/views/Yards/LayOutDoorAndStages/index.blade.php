@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-6" style="height: 700px;overflow-y: scroll;">
            <div class="card">
                <div class="card-header">
                    Cortinas -- <span class="badge badge-danger font-12">Embarque</span> -- <span class="badge badge-success font-12">Descarga</span>
                </div>
                <div class="card-body">
                    <table class="table table-condensed table-hover table-bordered">
                        <thead style="position: sticky;top: 0;">
                            <tr>
                                <th style="width: 20%">Cortina</th>
                                <th>Linea</th>
                                <th>Embarque</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($locations as $location)
                            @if($location->zoneid == 'CO')
                                <tr>
                                    <td class="td-events">{{$location->name}}</td>
                                    <td class="td-events">
                                        @foreach($units as $unit)
                                            @if ($unit->locationid == $location->id)
                                                {{$unit->carrier->name}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="td-events">
                                        @foreach($units as $unit)
                                            @if ($unit->locationid == $location->id)
                                                <span
                                                    class="badge {{$unit->razonstatusid == 1 ? 'badge-danger' : 'badge-success' }} font-12"
                                                    data-html="true"
                                                    data-toggle="tooltip"
                                                    data-placement="bottom"
                                                    title="
                                                                Folio: {{$unit->id}}   <br/>
                                                                Placa: {{$unit->boxnumber}}  <br/>
                                                                Linea de Transporte: {{$unit->carrier->name}} <br/>
                                                                Operador: 	{{$unit->operatorname}} <br/>
                                                                F.Enrampe: {{$unit->ensnare}}  <br/>
                                                    "
                                                >
                                                  {{$unit->shipmentid}}
                                                </span>
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6" style="height: 700px;overflow-y: scroll;">
            <div class="card">
                <div class="card-header">
                    Precargas
                </div>
                <div class="card-body">
                    <table class="table table-condensed table-hover table-bordered">
                        <thead style="position: sticky;top: 0;">
                            <tr>
                                <th style="width: 20%">Precargas</th>
                                <th>Embarque</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($locations as $location)
                            @if($location->zoneid == 'ST')
                                <tr>
                                    <td class="td-events">{{$location->name}}</td>
                                    <td class="td-events">
                                        @foreach($shipments as $shipment)
                                            @if ($shipment->locationid == $location->id)
                                                <a href="{{url('monitoroperation/' . $shipment->id . '/edit')}}">
                                                <span class="badge badge-success font-12">
                                                {{$shipment->shipmentid}}
                                                </span>
                                                </a>
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')

@endsection
