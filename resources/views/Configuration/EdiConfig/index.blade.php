@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Configuración de interfaces
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('edi-configurations.create')
                                    <a href="{{route('edi-configurations.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reload')
                                </a>
                                <a href="{{route('menuconfiguration')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                    </form>
                    <div class="card-input">
                        <div class="row">
                            <div class="col-lg-12 col-xs-12">
                                <table class="table table-bordered  table-condensed table-hover tablegeneral">
                                    <thead>
                                    <tr>
                                        <th>EDI</th>
                                        <th>Tipo</th>
                                        <th>Estandar</th>
                                        <th>Descripción</th>
                                        <th>Nombre</th>
                                        <th>C.Distribución remitente</th>
                                        <th>Bodega remitente</th>
                                        <th>C.Distribución receptor</th>
                                        <th>Bodega receptor</th>
                                        <th>Version</th>
                                        <th>Entorno</th>
                                        <th>Sistema cliente</th>
                                        <th>Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $item)
                                            <tr>
                                                <td>{{$item->edi}}</td>
                                                <td>{{$item->type}}</td>
                                                <td>{{$item->standard}}</td>
                                                <td>{{$item->description}}</td>
                                                <td>{{$item->sendername}}</td>
                                                <td>{{$item->centersender}}</td>
                                                <td>{{$item->warehousesender}}</td>
                                                <td>{{$item->centerrecipient}}</td>
                                                <td>{{$item->warehouserecipient}}</td>
                                                <td>{{$item->version}}</td>
                                                <td>{{$item->enviroment}}</td>
                                                <td>{{$item->systemclient}}</td>
                                                <td>
                                                    @can('edi-configurations.edit')
                                                        <a href="{{route('edi-configurations.edit',$item->id)}}">
                                                            @include('buttons.edit')
                                                        </a>
                                                    @endcan
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
    </div>

@endsection

@section('css')
@endsection

@section('script')
    @include('layouts.Partials.DataTable')
@endsection
