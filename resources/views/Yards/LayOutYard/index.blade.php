@extends('layouts.app')

@section('content')

    <style>
        .donut-chart {
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
        }

        .donut-chart::before {
            content: '';
            position: absolute;
            width: 100px;
            height: 100px;
            background: #fff;
            border-radius: 50%;
        }

        .donut-chart .donut-label {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .donut-chart .donut-label .donut-percent {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            line-height: 1;
        }

        .donut-chart .donut-label .donut-text {
            font-size: 11px;
            color: #888;
        }

        .sidebar-yard-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
        }

        .sidebar-yard-card .card-body {
            padding: 18px 14px;
        }

        .yard-section-title {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            color: #222;
            text-align: center;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }

        .yard-stat-label {
            font-size: 12px;
            font-weight: 600;
            color: #555;
            margin-bottom: 2px;
        }

        .yard-stat-value {
            font-size: 22px;
            font-weight: 700;
            color: #222;
        }

        .yard-stat-row {
            text-align: center;
            margin-bottom: 8px;
        }

        .yard-divider {
            border-top: 1px solid #eee;
            margin: 10px 0;
        }

        .reason-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 10px 12px;
            margin-bottom: 8px;
            border-left: 4px solid #78909c;
            border-bottom: 2px solid #78909c;
        }

        .reason-card .reason-total {
            font-size: 26px;
            font-weight: 700;
            color: #222;
            line-height: 1.1;
        }

        .reason-card .reason-name {
            font-size: 11px;
            font-weight: 600;
            color: #777;
            text-transform: uppercase;
        }

        .capacity-bar-container {
            margin-bottom: 8px;
        }

        .capacity-bar-container .cap-label {
            font-size: 12px;
            font-weight: 700;
            color: #333;
        }

        .capacity-bar-container .cap-detail {
            font-size: 10px;
            color: #999;
        }

        .capacity-bar {
            height: 8px;
            border-radius: 4px;
            background: #e9ecef;
            overflow: hidden;
            margin-top: 3px;
        }

        .capacity-bar .capacity-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.5s ease;
        }
    </style>

    <div class="row">
        <div class="col-lg-10">
            <div class="card" style="background-color: #0b0b0b">
                <div class="card-body">
                    <div style="max-width: 100%;">
                        @if(Auth::user()->access->center->centerid == 'HENKELTOL01')
                            @include('Yards.LayOutYard.Yards.HenkelTolucaOneRoof.HenkelTol')
                        @endif
                        @if(Auth::user()->access->center->centerid == 'BRIDCUE')
                            @include('Yards.LayOutYard.Yards.BridgestoneCva.index')
                        @endif

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-6">
            <div class="card sidebar-yard-card">
                <div class="card-body">

                    <div class="row item-center">
                        <div class="col-lg-12">
                            @if(Auth::check())
                                @if(Auth::user()->access->center->centerid == 'SAHUARO' || Auth::user()->access->center->centerid == 'CONDESA')
                                    <img src="{{asset('/img/Garrett.jpg')}}" alt="" style="width: 280px;height: 100px">
                                @endif
                                @if(Auth::user()->access->center->centerid == 'PLANHENKTOL' || Auth::user()->access->center->centerid == 'HENKELTOL01')
                                    <img src="{{asset('/img/henkel.png')}}" alt="" style="width: 280px;height: 100px">
                                @endif
                                @if(Auth::user()->access->center->centerid == 'PRONUMEX02' || Auth::user()->access->center->centerid == 'KELLOGGS01'|| Auth::user()->access->center->centerid == 'KELLOGGS02')
                                    <img src="{{asset('/img/Kellogg.png')}}" alt="" style="width: 280px;height: 100px">
                                @endif
                                @if(Auth::user()->access->center->centerid == 'BRIDGESTONES' || Auth::user()->access->center->centerid == 'AVBSMX' || Auth::user()->access->center->centerid == 'BRIDCUE'  )
                                    <img src="{{asset('/img/Bridgestone.png')}}" alt="" style="width: 500px;height: 30px">
                                @endif
                                @if(Auth::user()->access->center->centerid == 'BARRYTOL')
                                    <img src="{{asset('/img/Barry.png')}}" alt="" style="width: 280px;height: 80px">
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- Título --}}
                    <div class="yard-section-title mt-4">Ocupación del Patio</div>

                    {{-- Gráfica de dona --}}
                    <div id="grafica">
                        <div class="donut-chart"
                             style="background: conic-gradient(#3a8fd6 0% {{ $totales->porcentaje_ocupacion }}%, #e0e0e0 {{ $totales->porcentaje_ocupacion }}% 100%);">
                            <div class="donut-label">
                                <div class="donut-percent">{{ number_format($totales->porcentaje_ocupacion, 0) }}%</div>
                                <div class="donut-text">Ocupado</div>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-4">

                    {{-- Cajones ocupados --}}
                    <div class="yard-stat-row mt-4">
                        <div class="yard-stat-label">Cajones ocupados:</div>
                        <div class="yard-stat-value">{{ $totales->total_ocupadas }}
                            / {{ $totales->total_ubicaciones }}</div>
                    </div>

                    <hr class="mt-4">

                    {{-- Cajones libres --}}
                    <div class="yard-stat-row mt-4">
                        <div class="yard-stat-label">Cajones libres:</div>
                        <div class="yard-stat-value">{{ $totales->total_disponibles }}</div>
                    </div>

                    <hr class="mt-4">
                    <div class="row">
                        <div class="yard-stat-label">Total de unidades por concepto de entrada:</div>
                    </div>
                    {{-- Tarjetas por razón de estatus --}}
                    @foreach($result as $item)
                        <div class="reason-card mt-4">
                            <div class="reason-total">{{ $item->total }}</div>
                            <div class="reason-name">{{ $item->razonname }}</div>
                        </div>
                    @endforeach
                    <hr class="mt-4">
                    <div class="row">
                        <div class="yard-stat-label">Capacidad por almacén:</div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-12">
                            @foreach($bodegas as $item)
                                <div class="reason-card mt-2">
                                    <div class="reason-total">{{ $item->total_ubicaciones }} / {{$item->total_ocupadas}}
                                        <span style="font-size: 10px !important;" class="text-muted">
                                            ({{ number_format($item->porcentaje_ocupacion, 2) }} %)
                                        </span>
                                    </div>
                                    <div class="reason-name">{{ $item->warehouse }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

@endsection

