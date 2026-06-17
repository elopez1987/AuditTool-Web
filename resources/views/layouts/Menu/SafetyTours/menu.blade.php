@extends('layouts.app')


@section('content')
    <main>
        <h5 class="name-menu"> Recorridos de Seguridad</h5>
        <div class="container__box">
            @can('storagetags.index')
                <a href="{{route('storagetags.index')}}">
                    <div class="box">
                        <i class="lni lni-tag"></i>
                        <h5>Tags</h5>
                        <h4>Tags</h4>
                    </div>
                </a>
            @endcan
            @can('storagesafetytourtype.index')
                <a href="{{route('storagesafetytourtype.index')}}">
                    <div class="box">
                        <i class="lni lni-tag"></i>
                        <h5>Armar recorridos</h5>
                        <h4>Armar recorridos</h4>
                    </div>
                </a>
            @endcan
            @can('storagesafetytours.index')
                <a href="{{route('storagesafetytours.index')}}">
                    <div class="box">
                        <i class="lni lni-tag"></i>
                        <h5>Recorridos</h5>
                        <h4>Recorridos</h4>
                    </div>
                </a>
            @endcan
        </div>

        <h5 class="name-menu"> Incidencias</h5>
        <div class="container__box">
            @can('storagetags.index')
                <a href="{{route('storagetags.index')}}">
                    <div class="box">
                        <i class="mdi mdi-menu"></i>
                        <h5>Incidencias</h5>
                        <h4>Incidencias</h4>
                    </div>
                </a>
            @endcan
            @can('storagetags.index')
                <a href="{{route('storagetags.index')}}">
                    <div class="box">
                        <i class="mdi mdi-menu"></i>
                        <h5>Categorias </h5>
                        <h4>Categorias </h4>
                    </div>
                </a>
            @endcan
                @can('responsible-area-security.index')
                    <a href="{{route('responsible-area-security.index')}}">
                        <div class="box">
                            <i class="mdi mdi-menu"></i>
                            <h5>Areas </h5>
                            <h4>Areas </h4>
                        </div>
                    </a>
                @endcan

        </div>
    </main>

@endsection

