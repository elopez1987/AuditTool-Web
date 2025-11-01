<!DOCTYPE html>
<html dir="ltr" lang="es">
@include('layouts.Partials.Head')
<body>
<div class="preloader">
    <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
    </div>
</div>
<div id="main-wrapper">
    @include('layouts.Partials.Header')
    <aside class="left-sidebar">
        <div class="scroll-sidebar">
            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    @can('CONTROL DE PATIOS')
                        @include('layouts.Menu.Yard.Yard')
                    @endcan
                    @can('EMBARQUES')
                        @include('layouts.Menu.Shipments.Shipments')
                    @endcan
                    @can('RECIBO')
                        @include('layouts.Menu.Receipt.Receipt')
                    @endcan
                    @can('INVENTARIO')
                        @include('layouts.Menu.Inventory.Inventory')
                    @endcan
                    @can('MTNTRUCK')
                        <li class="sidebar-item">
                            <a href="{{route('menumaintenance')}}" class="sidebar-link">
                                <img src="{{asset('ico/addreceipt.png')}}" alt="" class="img-16">
                                <span class="hide-menu mr-left"> MANTENIMIENTO</span>
                            </a>
                        </li>
                    @endcan
                    @can('SafetyTours')
                        <li class="sidebar-item">
                            <a href="{{route('tours')}}" class="sidebar-link">
                                <img src="{{asset('ico/receipunits.png')}}" alt="" class="img-16">
                                <span class="hide-menu mr-left"> RECORRIDOS</span>
                            </a>
                        </li>
                    @endcan
                    @can('security')
                        @include('layouts.Menu.Security.Security')
                    @endcan
                    @can('configuration')
                        @include('layouts.Menu.Security.Configuration')
                    @endcan
                </ul>
            </nav>
        </div>
    </aside>
    <div class="page-wrapper">
        <div class="container-fluid">
            @yield('content')
        </div>
        <footer class="footer text-center">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-3">
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
        </footer>
    </div>
</div>


{{--<div class="chat-windows"></div>--}}

@include('layouts.Partials.Scripts')
@include('layouts.Scripts.Messages')
@yield('script')



</body>

</html>
