@extends('layouts.app')


@section('content')
    <main>
        {{--        <h1 class="name-menu">Inventario</h1>--}}
        <div class="container__box">
            @can('inventorypartnumbers.index')
                <a href="{{route('inventorypartnumbers.index')}}">
                    <div class="box">
                        <i class="lni lni-list"></i>
                        <h5>M.Materiales</h5>
                        <h4>M.Materiales</h4>
                    </div>
                </a>
            @endcan
            @can('inventoryunitmeasure.index')
                <a href="{{route('inventoryunitmeasure.index')}}">
                    <div class="box">
                        <i class="lni lni-control-panel"></i>
                        <h5>U.Medida</h5>
                        <h4>U.Medida</h4>
                    </div>
                </a>
            @endcan
                @can('inventorypartnumbercategory01.index')
                <a href="{{route('inventorypartnumbercategory01.index')}}">
                    <div class="box">
                        <i class="lni lni-control-panel"></i>
                        <h5>Tipos</h5>
                        <h4>Tipos</h4>
                    </div>
                </a>
            @endcan
            @can('inventorypartnumbercategory02.index')
                <a href="{{route('inventorypartnumbercategory02.index')}}">
                    <div class="box">
                        <i class="lni lni-arrow-right-circle"></i>
                        <h5>Categoria 02</h5>
                        <h4>Categoria 02</h4>
                    </div>
                </a>
            @endcan
            @can('inventorystockcategories.index')
                <a href="{{route('inventorystockcategories.index')}}">
                    <div class="box">
                        <i class="lni lni-star-half"></i>
                        <h5>Origenes</h5>
                        <h4>Origenes</h4>
                    </div>
                </a>
            @endcan
            @can('inventoryhead.index')
                <a href="{{route('inventoryhead.index')}}">
                    <div class="box">
                        <i class="lni lni-list"></i>
                        <h5>Conteo Fiscal</h5>
                        <h4>Conteo Fiscal</h4>
                    </div>
                </a>
            @endcan
            @can('storagecountdronhead.index')
                <a href="{{route('storagecountdronhead.index')}}">
                    <div class="box">
                        <i class="lni lni-download"></i>
                        <h5>Conteo Dron</h5>
                        <h4>Conteo Dron</h4>
                    </div>
                </a>
            @endcan

        </div>
    </main>

@endsection

