@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detalle de Master
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <a href="{{route('reportauditshipments.index')}}">
                                @include('buttons.back')
                            </a>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="masterid">Etiqueta Master</label>
                                <input type="text" class="form-control" id="masterid"
                                       name="masterid" readonly
                                       aria-describedby="name" value="{{$master->masterid}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#view01"
                                                role="tab"><span
                                    class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Detalle Master</span></a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="view01" role="tabpanel">
                            <div class="row mt-3">
                                <div class="col-lg-8">
                                    <table width="100%" id="table-detailmaster"
                                           class="table table-condensed table-bordered table-hover tablegeneral">
                                        <thead>
                                        <tr>
                                            <th>Master</th>
                                            <th>ID. Container</th>
                                            <th>Sku</th>
                                            <th>Descripción</th>
                                            <th>Cantidad</th>
                                            <th>Fecha de movimiento</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($details as $item)
                                                <tr>
                                                    <td>{{$item->masterid}}</td>
                                                    <td>{{$item->lpnid}}</td>
                                                    <td>{{$item->partnumberid}}</td>
                                                    <td>{{$item->description}}</td>
                                                    <td>{{$item->quantity}}</td>
                                                    <td>{{$item->created_at}}</td>
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
        </div>
    </div>
@endsection

@section('css')

@endsection

@section('script')
    @include('layouts.Partials.DataTable')

@endsection

