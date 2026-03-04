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
                @if(!app()->runningInConsole() && config('auth0.domain'))
                    <ul id="sidebarnav">
                        @can('CONTROL DE PATIOS')
                            <li class="sidebar-item">
                                <a href="{{route('menuyard')}}" class="sidebar-link">
                                    <img src="{{asset('ico/storagezone.png')}}" alt="" class="img-16">
                                    <span class="hide-menu mr-left"> CONTROL DE PATIOS</span>
                                </a>
                            </li>
                        @endcan
                        @can('EMBARQUES')
                            <li class="sidebar-item">
                                <a href="{{route('menushipment')}}" class="sidebar-link">
                                    <img src="{{asset('ico/receiptunits.png')}}" alt="" class="img-16">
                                    <span class="hide-menu mr-left"> EMBARQUES</span>
                                </a>
                            </li>
                        @endcan
                        @can('RECIBO')
                            <li class="sidebar-item">
                                <a href="{{route('menureceipt')}}" class="sidebar-link">
                                    <img src="{{asset('ico/semaforo.png')}}" alt="" class="img-16">
                                    <span class="hide-menu mr-left"> RECIBO</span>
                                </a>
                            </li>
                        @endcan
                        @can('INVENTARIO')
                            <li class="sidebar-item">
                                <a href="{{route('menuinventory')}}" class="sidebar-link">
                                    <img src="{{asset('ico/pallets.png')}}" alt="" class="img-16">
                                    <span class="hide-menu mr-left"> INVENTARIO</span>
                                </a>
                            </li>
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
                            <li class="sidebar-item">
                                <a href="{{route('menusecurity')}}" class="sidebar-link">
                                    <img src="{{asset('ico/securityrole.png')}}" alt="" class="img-16">
                                    <span class="hide-menu mr-left"> SEGURIDAD</span>
                                </a>
                            </li>
                        @endcan
                        @can('configuration')
                            <li class="sidebar-item">
                                <a href="{{route('menuconfiguration')}}" class="sidebar-link">
                                    <img src="{{asset('ico/engine_gear_internet_option_security_setting_web_icon_127066.png')}}"
                                         alt="" class="img-16">
                                    <span class="hide-menu mr-left"> CONFIGURACION</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                @endif
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
                    @if(!app()->runningInConsole() && config('auth0.domain'))
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
                    @endif
                </div>
            </div>
        </footer>
    </div>

</div>


<div class="chat-windows"></div>

@include('layouts.Partials.Scripts')
@include('layouts.Scripts.Messages')
@yield('script')


</body>

</html>
