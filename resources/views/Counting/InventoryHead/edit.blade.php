@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detalle de inventario de cajas
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('inventoryhead/' . $inventoryhead->id)}}">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('inventoryhead.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-2">
                        @include('Counting.InventoryHead.partials.formEdit')
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                @if (isset($errors) && $errors->any())
                    <div class="alert alert-danger" role="alert">
                        @foreach ($errors->all() as $error)
                            {{$error}}
                        @endforeach
                    </div>
                @endif
                <div class="card-body">
                    <table class="table table-condensed  table-bordered  table-striped">
                        <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                @if($inventoryhead->statusid == 'PN')
                                    @can('imporlocationinventory')
                                        <a href="" data-target="#modal-imporlocationinventory" data-toggle="modal"
                                           data-placement="top">
                                            <button type="button"
                                                    class="btn waves-effect waves-light btn-outline-info btn-md"
                                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                                <img src="{{asset('/ico/uploadcsv.png')}}" alt="" class="img-16">
                                                <span
                                                    style="font-size: 0.675rem !important;;"> Importar Ubicaciones </span>
                                            </button>
                                        </a>
                                        @include('Counting.InventoryHead.Imports.importlocations')
                                    @endcan
                                @endif
                            </td>
                            <td>
                                @if($inventoryhead->statusid == 'PN')
                                    @can('importinventorystock')
                                        <a href="" data-target="#modal-importinventorystock" data-toggle="modal"
                                           data-placement="top">
                                            <button type="button"
                                                    class="btn waves-effect waves-light btn-outline-info btn-md"
                                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                                <img src="{{asset('/ico/uploadcsv.png')}}" alt="" class="img-16">
                                                <span
                                                    style="font-size: 0.675rem !important;;"> Importar existencia </span>
                                            </button>
                                        </a>
                                        @include('Counting.InventoryHead.Imports.importstock')
                                    @endcan
                                @endif
                            </td>
                            <td>
                                @if($inventoryhead->statusid == 'PN')
                                    <a href="" data-target="#modal-armedcard-{{$inventoryhead->id}}"
                                       data-toggle="modal"
                                       data-placement="top">
                                        <button type="button"
                                                class="btn waves-effect waves-light btn-outline-info btn-md">
                                            <img src="{{asset('/ico/engine_gear_internet_option_security_setting_web_icon_127066.png')}}" alt="" class="img-16">
                                            <span style="font-size: 0.675rem !important;;"> Armar Cédulas </span>
                                        </button>
                                    </a>
                                    @include('Counting.InventoryHead.armedCards')
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <li class="nav-item" style="list-style-type: none">
                                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" role="tab"
                                       aria-selected="false">
                                        <span class="text-muted">Total de Ubicaciones</span>
                                        <h3 class="m-b-0 text-dark">{{$totalLocations}} </h3>
                                    </a>
                                </li>
                            </td>
                            <td>
                                <li class="nav-item" style="list-style-type: none">
                                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" role="tab"
                                       aria-selected="false">
                                        <span class="text-muted">Sku Totales</span>
                                        <h3 class="m-b-0 text-dark">{{$totalSkus}}</h3>
                                    </a>
                                </li>
                            </td>
                            <td>
                                <li class="nav-item" style="list-style-type: none">
                                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" role="tab"
                                       aria-selected="false">
                                        <span class="text-muted">Total de Cedulas</span>
                                        <h3 class="m-b-0 text-dark">{{$totalCards}} </h3>
                                    </a>
                                </li>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

