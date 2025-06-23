<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href=" {{asset('/img/YMS.png')}}">

    <link href="{{asset('/theme/dist/css/style.min.css')}}" rel="stylesheet">
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    {{--    <link rel="preconnect" href="https://fonts.googleapis.com">--}}
    {{--    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>--}}
    {{--    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap" rel="stylesheet">--}}

    {{--    <link rel="preconnect" href="https://fonts.googleapis.com">--}}
    {{--    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>--}}
    {{--    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200&display=swap" rel="stylesheet">--}}


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&display=swap" rel="stylesheet">

    <style>


        body {
            font-family: 'Open Sans', sans-serif !important;
            font-size: 12px;
            background-color: #b2c326;
        }

        html {
            font-family: 'Open Sans', sans-serif !important;
        }

        label {
            font-family: 'Open Sans', sans-serif !important;
        }

        input[type=text], input[type=password], input[type=email], input[type=tel], input[type=number] {
            height: 30px !important;
            border-radius: 3px;
            font-size: 12px;
            font-family: 'Open Sans', sans-serif;
        }

        .btn-trax {
            color: black;
            background-color: #ced868;
            border-color: #9ba739;
        }

        .btn-trax:hover {
            color: black;
            font-weight: 600;
            background-color: #9ba739;
            border-color: #9ba739;

        }

        span {
            font-family: 'Open Sans', sans-serif !important;
        }
    </style>
    <title></title>


</head>

<body>
<div class="main-wrapper">
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center"
         style="background:url({{asset('img/login-register.jpg')}}) no-repeat center center;">
        <div class="auth-box on-sidebar">
            <div id="loginform">
                <div class="logo">
                    <span class="db"><img src="{{asset('/img/trax.jpg')}}" alt="logo"
                                          style="width: 90%;height: 50%" class=""/></span>
                </div>
                <div class="row mt-3 mb-3 text-center">
                    <div class="col-12"><span class="font-16 font-bold">Ingrese sus credenciales</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <label for="login">Usuario :</label>
                                <input type="text" id="login" class="form-control form-control-lg"
                                       name="login"
                                       aria-describedby="basic-addon1" autofocus
                                       required
                                       value="{{ old('login') }}">
                            </div>

                            <div class="form-group">
                                <label for="password">Contraseña:</label>
                                <input type="password" name="password" class="form-control form-control-lg"
                                       required="required" aria-label="Password"
                                       aria-describedby="basic-addon1">
                            </div>
                            @if ($errors->has('login'))
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                          <span class="help-block" style="color: red;font-weight: 400;">
                                              <strong>{{ $errors->first('login') }}</strong>
                                           </span>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group text-center">
                                <div class="col-xs-12 p-b-20">
                                    <button class="btn btn-block btn-md btn-trax" type="submit">Entrar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{--            <div class="row">--}}
            {{--                <div class="col-lg-12">--}}
            {{--                    <div class="card">--}}
            {{--                        <div class="card-body text-center ">--}}
            {{--                            <h3 class="text-danger font-bold ">AVISO</h3>--}}
            {{--                            <h4 class="text-justify" style="font-family: 'Open Sans', sans-serif !important;">--}}
            {{--                                <i class="mdi mdi-alert text-danger"></i>--}}
            {{--                                El sistema se encontrará en mantenimiento el dia viernes 25/11/2022 a las 12 pm con--}}
            {{--                                duración de 1 hora, lamentamos las molestias que esto te ocacione.--}}
            {{--                                <i class="mdi mdi-alert text-danger"></i>--}}
            {{--                            </h4>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}

        </div>
    </div>
</div>
<script src="{{asset('/theme/assets/libs/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('/theme/assets/libs/popper.js/dist/umd/popper.min.js')}}"></script>
<script src="{{asset('/theme/assets/libs/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script>
    $(".preloader").fadeOut();
</script>
</body>

</html>
