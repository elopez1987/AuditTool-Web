@extends('layouts.app')


@section('content')
    <main>
        {{--        <h1 class="name-menu">MANTENIMIENTO</h1>--}}
        <div class="container__box">

            @can('dashboardtrucks')
                <a href="{{route('dashboardtrucks')}}">
                    <div class="box">
                        <i class="lni lni-dashboard"></i>
                        <h5>Asignaciones</h5>
                        <h4>Asignaciones</h4>
                    </div>
                </a>
            @endcan

            @can('checklistlifttrucks.index')
                <a href="{{route('checklistlifttrucks.index')}}">
                    <div class="box">
                        <i class="lni lni-list"></i>
                        <h5>Detalles</h5>
                        <h4>Detalles</h4>
                    </div>
                </a>
            @endcan
            @can('storagechecklistlifttrucks.index')
                <a href="{{route('storagechecklistlifttrucks.index')}}">
                    <div class="box">
                        <i class="lni lni-layers"></i>
                        <h5>Check List</h5>
                        <h4>Check List</h4>
                    </div>
                </a>
            @endcan
            @can('storageassignlifttrucks.index')
                <a href="{{route('storageassignlifttrucks.index')}}">
                    <div class="box">
                        <i class="lni lni-network"></i>
                        <h5>Asignación</h5>
                        <h4>Asignación</h4>
                    </div>
                </a>
            @endcan
            @can('storagelifttrucks.index')
                <a href="{{route('storagelifttrucks.index')}}">
                    <div class="box">
                        <i class="lni lni-weight"></i>
                        <h5>Montacargas</h5>
                        <h4>Montacargas</h4>
                    </div>
                </a>
            @endcan
            @can('storagelifttrucks.reports')
                <a href="{{route('storagelifttrucksreports')}}">
                    <div class="box">
                        <i class="lni lni-weight"></i>
                        <h5>Reportes</h5>
                        <h4>Reportes</h4>
                    </div>
                </a>
            @endcan
            @can('storagelifttruckscatalogs')
                <a href="{{route('storagelifttruckscatalogs')}}">
                    <div class="box">
                        <i class="mdi mdi-book"></i>
                        <h5>Catalogos</h5>
                        <h4>Catalogos</h4>
                    </div>
                </a>
            @endcan
        </div>
    </main>

@endsection

