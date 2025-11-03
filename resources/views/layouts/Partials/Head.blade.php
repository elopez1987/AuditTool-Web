<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href=" {{asset('/img/negro.png')}}">
    {{--    <link rel="icon" type="image/png" sizes="16x16" href=" {{asset('/img/logo.png')}}">--}}
    <title class="text-white"> YardTrax </title>
    <link rel="stylesheet" href="{{asset('theme/assets/extra-libs/toastr/dist/build/toastr.min.css')}}">
    <link rel="stylesheet"
          href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.11.4/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/cr-1.5.5/fc-4.0.2/fh-3.2.2/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sr-1.1.0/datatables.min.css"/>
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/select2/dist/css/select2.min.css')}}">
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}">
    @yield('css')
    <link rel="stylesheet" href="{{asset('/theme/dist/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('/css/app.css')}}">
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.min.css">
</head>
