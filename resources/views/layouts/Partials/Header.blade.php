<header class="topbar" data-navbarbg="skin1">
    <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <div class="navbar-header">
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                        class="ti-menu ti-close"></i></a>
            <a class="navbar-brand" href="{{route('home')}}">
                                <span class="logo-text font-weight-bold text-white font-18">
{{--                                    SGIO--}}
                               </span>
{{--                <link rel="icon" type="image/ta" sizes="16x16" href=" {{asset('/img/verde.png')}}">--}}
                <img src="{{asset('img/taxion-logistic.png')}}" alt="" style="width: 150px;height: 35px">
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


                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-none d-md-block text-white ">BIENVENIDO:
                         @if(Auth::check())
                                {{Auth::user()->name}}
                                ||  {!! Auth::user()->access ? Auth::user()->access->center->name : '' !!}
                                ||
                                @if(App::environment('production'))
                                    <span class="badge badge-success ml-2" style="font-style: italic; text-transform: uppercase;font-size: 11px;padding-left: 10px !important;padding-right: 10px !important;font-weight: lighter">
                                          {{ app()->environment() }}
                                        </span>
                                 @else
                                         <span class="badge badge-danger ml-2" style="font-style: normal; text-transform: uppercase;font-size: 11px;padding-left: 10px !important;padding-right: 10px !important;font-weight: lighter">
                                            {{ app()->environment() }}
                                        </span>
                                @endif
                            @endif
                        </span>
                        <span class="d-block d-md-none"><i class="fa fa-plus"></i></span>
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav float-right">
                <!-- ============================================================== -->
                <!-- Comment -->
                <!-- ============================================================== -->
{{--                <li class="nav-item dropdown">--}}
{{--                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-bell font-24 text-white"></i>--}}

{{--                    </a>--}}
{{--                    <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">--}}
{{--                        <span class="with-arrow"><span class="bg-primary"></span></span>--}}
{{--                        <ul class="list-style-none">--}}
{{--                            <li>--}}
{{--                                <div class="drop-title bg-primary text-white">--}}
{{--                                    <h4 class="m-b-0 m-t-5">4 Nuevas</h4>--}}
{{--                                    <span class="font-light">Notificaciones</span>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <div class="message-center notifications">--}}
{{--                                    <!-- Message -->--}}
{{--                                    <a href="javascript:void(0)" class="message-item">--}}
{{--                                        <span class="btn btn-danger btn-circle"><i class="fa fa-link"></i></span>--}}
{{--                                        <div class="mail-contnet">--}}
{{--                                            <h5 class="message-title">Luanch Admin</h5> <span class="mail-desc">Just see the my new admin!</span> <span class="time">9:30 AM</span> </div>--}}
{{--                                    </a>--}}
{{--                                    <!-- Message -->--}}
{{--                                    <a href="javascript:void(0)" class="message-item">--}}
{{--                                        <span class="btn btn-success btn-circle"><i class="ti-calendar"></i></span>--}}
{{--                                        <div class="mail-contnet">--}}
{{--                                            <h5 class="message-title">Event today</h5> <span class="mail-desc">Just a reminder that you have event</span> <span class="time">9:10 AM</span> </div>--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a class="nav-link text-center m-b-5 text-dark" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </li>--}}
                <!-- ============================================================== -->
                <!-- End Comment -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Messages -->
                <!-- ============================================================== -->
{{--                <li class="nav-item dropdown">--}}
{{--                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="font-24 mdi mdi-comment-processing text-white"></i>--}}

{{--                    </a>--}}
{{--                    <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown" aria-labelledby="2">--}}
{{--                        <span class="with-arrow"><span class="bg-danger"></span></span>--}}
{{--                        <ul class="list-style-none">--}}
{{--                            <li>--}}
{{--                                <div class="drop-title text-white bg-danger">--}}
{{--                                    <h4 class="m-b-0 m-t-5">5 New</h4>--}}
{{--                                    <span class="font-light">Messages</span>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <div class="message-center message-body">--}}
{{--                                    <!-- Message -->--}}
{{--                                    <a href="javascript:void(0)" class="message-item">--}}
{{--                                        <span class="user-img"> <img src="../../assets/images/users/1.jpg" alt="user" class="rounded-circle"> <span class="profile-status online pull-right"></span> </span>--}}
{{--                                        <div class="mail-contnet">--}}
{{--                                            <h5 class="message-title">Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:30 AM</span> </div>--}}
{{--                                    </a>--}}
{{--                                    <!-- Message -->--}}
{{--                                    <a href="javascript:void(0)" class="message-item">--}}
{{--                                        <span class="user-img"> <img src="../../assets/images/users/2.jpg" alt="user" class="rounded-circle"> <span class="profile-status busy pull-right"></span> </span>--}}
{{--                                        <div class="mail-contnet">--}}
{{--                                            <h5 class="message-title">Sonu Nigam</h5> <span class="mail-desc">I've sung a song! See you at</span> <span class="time">9:10 AM</span> </div>--}}
{{--                                    </a>--}}
{{--                                    <!-- Message -->--}}
{{--                                    <a href="javascript:void(0)" class="message-item">--}}
{{--                                        <span class="user-img"> <img src="../../assets/images/users/3.jpg" alt="user" class="rounded-circle"> <span class="profile-status away pull-right"></span> </span>--}}
{{--                                        <div class="mail-contnet">--}}
{{--                                            <h5 class="message-title">Arijit Sinh</h5> <span class="mail-desc">I am a singer!</span> <span class="time">9:08 AM</span> </div>--}}
{{--                                    </a>--}}
{{--                                    <!-- Message -->--}}
{{--                                    <a href="javascript:void(0)" class="message-item">--}}
{{--                                        <span class="user-img"> <img src="../../assets/images/users/4.jpg" alt="user" class="rounded-circle"> <span class="profile-status offline pull-right"></span> </span>--}}
{{--                                        <div class="mail-contnet">--}}
{{--                                            <h5 class="message-title">Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span> </div>--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a class="nav-link text-center link text-dark" href="javascript:void(0);"> <b>See all e-Mails</b> <i class="fa fa-angle-right"></i> </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </li>--}}
                <!-- ============================================================== -->
                <!-- End Messages -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
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
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
            </ul>
        </div>
    </nav>
</header>
