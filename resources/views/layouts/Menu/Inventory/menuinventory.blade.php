@extends('layouts.app')


@section('content')
    <main>
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
        </div>


        <div class="container__box">
            @can('inventoryareas.index')
                <a href="{{route('inventoryareas.index')}}">
                    <div class="box">
                        <i class="lni lni-list"></i>
                        <h5>Areas</h5>
                        <h4>Areas</h4>
                    </div>
                </a>
            @endcan
                @can('inventoryaisles.index')
                <a href="{{route('inventoryaisles.index')}}">
                    <div class="box">
                        <i class="lni lni-list"></i>
                        <h5>Pasillos</h5>
                        <h4>Pasillos</h4>
                    </div>
                </a>
            @endcan

        </div>
    </main>

@endsection

