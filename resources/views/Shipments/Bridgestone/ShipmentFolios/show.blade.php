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
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-2">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Embarque</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           aria-describedby="name">
                                </div>
                            </div>
                        </div>
                        <hr class="mt-4">
                    </form>
                    <div class="row">
                        <div class="col-lg-12">
                            <table  class="table table-bordered table-striped table-condensed tablegeneral">
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
                                            <td>{{$detail->order ? $detail->order->shipmentid : ''}}</td>
                                            <td>{{$detail->order ? $detail->order->customer->name : ''}}</td>
                                            <td>{{$detail->quantity}}</td>
                                            <td>
                                                <img src="{{asset('storage/' . $detail->keyguard)}}" alt="Firma" style="width: 100px; height: 100px;">
                                            </td>
                                            <td>
                                                <img src="{{asset('storage/' . $detail->keysupervisor)}}" alt="Firma" style="width: 100px; height: 100px;">
                                            </td>
                                            <td>
                                                <img src="{{asset('storage/' . $detail->keyoperator)}}" alt="Firma" style="width: 100px; height: 100px;">
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
