@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Registro de transporte de descargas
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menureceipt')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                    </form>
                    <hr class="mb-3">
                    <div class="row">
                        <div class="col-12">
                            <table id="table-receipts"
                                   class="table table-bordered  table-condensed table-hover tablegeneral">
                                <thead>
                                <tr>
                                    <th>{{ ui_label('receiptasn_cdistribucion_index','C.Distribución') }}</th>
                                    <th>{{ ui_label('receiptasn_warehouse_index','Bodega') }}</th>
                                    <th>{{ ui_label('receiptasn_shipmentid_index','Recibo') }}</th>
                                    <th>{{ ui_label('receiptasn_provider_index','Proveedor') }}</th>
                                    <th>{{ ui_label('receiptasn_cita_index','Cita de descarga') }}</th>
                                    <th>{{ ui_label('receiptasn_created_by_index','Creado por') }}</th>
                                    <th>{{ ui_label('receiptasn_statusid_index','Estatus') }}</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($receipts as $receipt)
                                    <tr>
                                        <td>{{$receipt->center ? $receipt->center->description : ''}}</td>
                                        <td>{{$receipt->warehouse ? $receipt->warehouse->name : ''}}</td>
                                        <td>{{$receipt->shipmentid ?? ''}}</td>
                                        <td>{{$receipt->provider  ? $receipt->provider->name :''}}</td>
                                        <td>{{$receipt->addasndate}}</td>
                                        <td>{{$receipt->auxiliary01 ?? ''}}</td>
                                        <td>{{$receipt->status ? $receipt->status->name : ''}}</td>
                                        <td>
                                            <a href="{{route('monitor-transport-receipt.edit',$receipt->id)}}"
                                               class="btn waves-effect waves-light  btn-md">
                                                <img src="{{asset('/ico/viewdetail.png')}}" alt="" class="img-16">
                                            </a>
                                            @if($receipt->transport->has_data == true)
                                                <a href="{{route('printFormatQr',$receipt->id)}}"
                                                   class="btn waves-effect waves-light  btn-md">
                                                    <img src="{{asset('/ico/pdf.png')}}" alt="" class="img-16">
                                                </a>
                                            @endif
                                        </td>
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
@endsection

@section('script')
    @include('layouts.Partials.DataTable')
@endsection
