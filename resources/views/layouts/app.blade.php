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
{{--            @include('layouts.Partials.soporte')--}}
        </div>
        <footer class="footer text-center">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-3">
                    @if(Auth::check())
                        @if( Auth::user()->access->center->centerid == 'BRIDCUE'  )
                            <img src="{{asset('/img/Bridgestone.png')}}" alt="" style="width:580px;height: 50px">
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

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery(".numeric").on('input', function (evt) {
            jQuery(this).val(jQuery(this).val().replace(/[^0-9]/g, ''));
        });
    });
</script>
<script>
    $(document).ready(function () {
        setInterval(keepTokenAlive, 1000 * 60 * 15);

        function keepTokenAlive() {
            $.ajax({
                url: '/keep-token-alive',
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).then(function (result) {
            });
        }
    });
</script>

</body>

</html>
