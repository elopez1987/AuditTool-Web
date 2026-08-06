<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href=" {{asset('/img/verde.png')}}">
{{--        <link rel="icon" type="image/png" sizes="16x16" href=" {{asset('/img/logo.png')}}">--}}
    <title class="text-white"> SGIO </title>
    <link rel="stylesheet" href="{{asset('theme/assets/extra-libs/toastr/dist/build/toastr.min.css')}}">


    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.11.4/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/cr-1.5.5/fc-4.0.2/fh-3.2.2/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sr-1.1.0/datatables.min.css"/>

{{--    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.8/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">--}}
{{--    <link href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-3.0.0/af-3.0.0/b-4.0.0/cr-3.0.0/cc-2.0.0/date-2.0.0/fc-6.0.0/fh-5.0.0/kt-3.0.0/r-4.0.0/rg-2.0.0/rr-2.0.0/sc-3.0.0/sb-2.0.0/sl-4.0.0/datatables.min.css" rel="stylesheet" integrity="sha384-Nal0AGAXusyIl+lnx7Pu7zQc4fCySYiyYmXVO2MRn2Fyj53cW8w25WqIukGm3Xh4" crossorigin="anonymous">--}}


    <link rel="stylesheet" href="{{asset('/theme/assets/libs/select2/dist/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/dist/css/style.css')}}">
    <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.min.css">
    @yield('css')
    <link rel="stylesheet" href="{{asset('/css/app.css')}}">
</head>
