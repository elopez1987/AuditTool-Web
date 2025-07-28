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

                    <div class="row">
                        <div class="col-lg-12">
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
                        </div>
                    </div>
                    <hr class="mb-2">
                    <div class="row">
                        <div class="col-sm-12 col-lg-4">
                            <div class="card card-hover">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="m-r-10">
                                            <span>Total de ubicaciones</span>
                                            <h3>{{$totalLocations}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-4">
                            <div class="card card-hover">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="m-r-10">
                                            <span>Total de Skus</span>
                                            <h3>{{$totalSkus}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-4">
                            <div class="card card-hover">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="m-r-10">
                                            <span>Total de cédulas</span>
                                            <h3>{{$totalCards}} </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

