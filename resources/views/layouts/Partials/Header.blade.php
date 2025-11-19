<header class="topbar" data-navbarbg="skin1">
    <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <div class="navbar-header">
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                    class="ti-menu ti-close"></i></a>
            <a class="navbar-brand" href="{{route('home')}}">
                {{--                <span class="logo-text font-weight-bold text-white font-20">--}}
                {{--                    YardTrax--}}
                {{--               </span>--}}
                <link rel="icon" type="image/png" sizes="16x16" href=" {{asset('/img/verde.png')}}">
                <img src="{{asset('img/verde.png')}}" alt="" style="width: 150px;height: 35px">
            </a>
            <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
               data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
               aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
        </div>
        <div class="navbar-collapse collapse" id="navbarSupportedContent">
            <ul class="navbar-nav float-left mr-auto">
                <li class="nav-item d-none d-md-block">
                    <a class="nav-link sidebartoggler waves-effect waves-light"
                       href="javascript:void(0)" data-sidebartype="mini-sidebar"><i
                            class="mdi mdi-menu font-24">
                        </i>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-none d-md-block text-white">Frecuentes <i class="fa fa-angle-down"></i></span>
                        <span class="d-block d-md-none"><i class="fa fa-plus"></i></span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                        @can('receiptunits.create')
                            <a href="{{route('receiptunits.create')}}" class="dropdown-item">
                                <img src="{{asset('/ico/window_add_12815.png')}}" alt="" class="img-16">
                                <span class="hide-menu mr-left"> Registrar Unidad</span></a>
                        @endcan
                        @can('viewmovementsofbox')
                            <a href="{{route('viewmovementsofbox')}}" class="dropdown-item">
                                <img src="{{asset('/ico/unitstatus.png')}}" alt="" class="img-16">
                                <span class="hide-menu mr-left"> Mesa de control </span>
                            </a>
                        @endcan
                        @can('autorizeoutput')
                            <a href="{{route('autorizeoutput')}}" class="dropdown-item">
                                <img src="{{asset('/ico/adcancelar.png')}}" alt="" class="img-16">
                                <span class="hide-menu mr-left"> Autorizar salida </span>
                            </a>
                        @endcan
                        {{--                        @can('setwarehouse')--}}
                        {{--                            <a href="{{route('setwarehouse')}}" class="dropdown-item">--}}
                        {{--                                <img src="{{asset('/ico/fingerprint_117641.png')}}" alt="" class="img-16">--}}
                        {{--                                <span class="hide-menu mr-left"> Fijar bodega </span>--}}
                        {{--                            </a>--}}
                        {{--                        @endcan--}}
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-none d-md-block text-white font-12">BIENVENIDO:
                         @if(Auth::check())
                                {{Auth::user()->name}}
                                ||  {!! Auth::user()->access ? Auth::user()->access->center->name : '' !!}
                                ||
                                @if(App::environment('production'))
                                    PRODUCCION
                                @else
                                    DESARROLLO
                                @endif
                            @endif
                        </span>
                        <span class="d-block d-md-none"><i class="fa fa-plus"></i></span>
                    </a>
                </li>


            </ul>
            <ul class="navbar-nav float-right">
                <li class="nav-item dropdown">
                    @auth()
                        <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href=""
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img
                                src=" {{asset('/img/exit.png')}} " alt="user" width="30"></a>

                        <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                            <span class="with-arrow"><span class="bg-info"></span></span>
                            <div class="d-flex no-block align-items-center p-15 bg-grey mb-2">
                                <div class="ml-2">
                                    <h5 class="mb-0 font-14 mt-3"> {{Auth::user()->name}}</h5>
                                </div>
                            </div>

                            {{--    <a class="dropdown-item" href="{{url('profile/' . Auth::user()->uuiduser)}}"><i class="ti-user mr-1 ml-1 text-success font-14"></i> Mi perfil</a>--}}
                            <div class="d-flex no-block align-items-center p-15  mb-2">
                                <div class="ml-2">
                                    <p class=" mb-1 mt-1 font-12 ">
                                        PERFIL: {{Auth::user()->roles ? Auth::user()->roles()->first()->description : ''}}</p>
                                </div>
                            </div>


                            <a class="dropdown-item" href="{{route('logout')}}"><i
                                        class="fa fa-power-off mr-1 ml-1 text-danger font-14"></i> Cerrar Sesión</a>
                        </div>
                    @endauth
                </li>
            </ul>
        </div>
    </nav>
</header>
