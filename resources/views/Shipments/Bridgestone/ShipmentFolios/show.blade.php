@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-10">
            <div class="card">
                <div class="card-header">
                   Detall de conteos
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{route('storageshipmentfolios.index')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                    </form>
                    <hr class="mt-3">
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <table  class="table table-bordered table-hover table-condensed tablegeneral">
                                <thead>
                                <tr>
                                    <th >Embarque</th>
                                    <th >Cliente</th>
                                    <th >Cantidad</th>
                                    <th >Firma de Vigilancia</th>
                                    <th >Firma de Supervisor</th>
                                    <th >Firma de Operador</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($details as $detail)
                                        <tr>
                                            <td style="font-size: 14px !important;">{{$detail->order ? $detail->order->shipmentid : ''}}</td>
                                            <td style="font-size: 14px !important;">{{$detail->order ? $detail->order->customer->name : ''}}</td>
                                            <td style="font-size: 18px !important;color: red">{{$detail->quantity}}</td>
                                            <td>
                                                <img src="{{asset('storage/' . $detail->keyguard)}}" alt="Firma" style="width: 100px; height: 100px;">
                                            </td>
                                            <td>
                                                <img src="{{asset('storage/' . $detail->keysupervisor)}}" alt="Firma" style="width: 100px; height: 100px;">
                                            </td>
                                            <td>
                                                @if($detail->keyoperator == null)
                                                    Pendiente de firma
                                                @else
                                                <img src="{{asset('storage/' . $detail->keyoperator)}}" alt="Firma" style="width: 100px; height: 100px;">
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
