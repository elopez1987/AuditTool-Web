@extends('layouts.app')


@section('content')
    <main>
        {{--        <h1 class="name-menu">Recibo</h1>--}}
        <div class="container__box">
            @can('receiptasn.index')
                <a href="{{route('receiptasn.index')}}">
                    <div class="box">
                        <i class="lni lni-calendar"></i>
                        <h5>Descargas</h5>
                        <h4>Descargas</h4>
                    </div>
                </a>
            @endcan
            @can('monitortransportreceipt')
                <a href="{{route('monitortransportreceipt')}}">
                    <div class="box">
                        <i class="fa fa-qrcode"></i>
                        <h5>Generar QR</h5>
                        <h4>Generar QR</h4>
                    </div>
                </a>
            @endcan
            @can('schedulereceipt.index')
                <a href="{{route('schedulereceipt.index')}}">
                    <div class="box">
                        <i class="lni lni-timer"></i>
                        <h5>Horario V1</h5>
                        <h4>Horario V1</h4>
                    </div>
                </a>
            @endcan
            @can('reportreceipts')
                <a href="{{route('reportreceipts')}}">
                    <div class="box">
                        <i class="lni lni-graph"></i>
                        <h5>Reportes</h5>
                        <h4>Reportes</h4>
                    </div>
                </a>
            @endcan
            @can('monitorreceiptplant')
                <a href="{{route('monitorreceiptplant')}}">
                    <div class="box">
                        <i class="lni lni-grid"></i>
                        <h5>Transitos Planta</h5>
                        <h4>Transitos Planta</h4>
                    </div>
                </a>
            @endcan
            @can('storagereceiptperiods.index')
                <a href="{{route('storagereceiptperiods.index')}}">
                    <div class="box">
                        <i class="lni lni-calendar"></i>
                        <h5>Periodos</h5>
                        <h4>Periodos</h4>
                    </div>
                </a>
            @endcan
            @can('appointmentschedule')
                <a href="{{route('appointmentschedule')}}">
                    <div class="box">
                        <i class="lni lni-calendar"></i>
                        <h5>Horario V2</h5>
                        <h4>Horario V2</h4>
                    </div>
                </a>
            @endcan
        </div>
    </main>

@endsection

