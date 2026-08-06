@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Inventario Fiscal
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{url('inventorylocations-card/' . $locationCount->inventorycardcount->id)}}">
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/ico/back.png')}}" alt="" class="img-16 ml-1">
                                        <span class="text-button"> Regresar</span>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-8 mt-4">
                            <div class="card">
                                <div class="card-header">
                                    Evidencias
                                </div>
                                <div class="card-body">
                                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                        <div class="carousel-inner" role="listbox">
                                            @foreach($evidences as $evidence)
                                                <div class="carousel-item @if ($loop->first) active @endif ">
                                                    <img class="img-fluid align-items-lg-center"
                                                         src="{{asset('storage/' . $evidence->path)}}" alt=""
                                                         style="max-height: 900px;max-width: 1300px">
                                                    <div class="carousel-caption d-none d-md-block">
                                                        <h2 class="text-danger ">Foto : {{$loop->iteration}}
                                                            / {{$loop->count}}</h2>
                                                        <p class="text-danger font-14">
                                                            {{$evidence->usercreated ? $evidence->usercreated->name : ''}} </p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                                           data-slide="prev"> <span class="carousel-control-prev-icon"
                                                                    aria-hidden="true"></span>
                                            <span class="sr-only">Anterior</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                                           data-slide="next"> <span class="carousel-control-next-icon"
                                                                    aria-hidden="true"></span>
                                            <span class="sr-only">Siguiente</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mt-4">
                            <div class="card">
                                <div class="card-header">
                                    Detalle de la ubicación
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="card p-10">
                                                <div class="card-header"></div>
                                                <span style="font-size: 16px" class="text-black-50">
                                                        <i class="mdi mdi-map-marker"></i>
                                                        UBICACIÓN:</span>
                                                <h3 class="m-b-0" style="font-size: 25px; color: #3f51b5">
                                                    {{$locationCount->inventorylocation ? $locationCount->inventorylocation->name :''}}
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="card p-10">
                                                <div class="card-header"></div>
                                                <div class="d-flex no-block align-items-center justify-content-between">
                                                    <div>
                                                        <span style="font-size: 16px;" class="text-black-50">
                                                             <i class="mdi mdi-barcode"></i> NÚMERO DE PARTE:
                                                        </span>
                                                        <h3 class="m-b-0 m-t-5"
                                                            style="font-size: 25px; color: #3f51b5; line-height: 1;">
                                                            {{$locationCount->inventorypartnumber01->prtnum}}
                                                        </h3>
                                                    </div>
                                                    <div>
                                                        <span class="text-red display-6"><i class="ti-check-box text-danger"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card p-10">
                                                <div class="card-header"></div>
                                                <span style="font-size: 16px" class="text-black-50">
                                                        <i class="mdi mdi-tag"></i>
                                                        DESCRIPCIÓN:</span>
                                                <h3 class="m-b-0" style="font-size: 25px; color: #3f51b5">
                                                    {{$locationCount->inventorypartnumber01 ? $locationCount->inventorypartnumber01->lngdsc :''}}
                                                </h3>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card p-10">
                                                <div class="card-header"></div>
                                                <div class="d-flex no-block align-items-center justify-content-between">
                                                    <div>
                                                        <span style="font-size: 16px;" class="text-black-50">
                                                            <i class="mdi mdi-layers"></i> CANTIDAD
                                                        </span>
                                                        <h3 class="m-b-0 m-t-5"
                                                            style="font-size: 45px; color: #3f51b5; line-height: 1;">
                                                            {{ $locationCount->quantity01 }}
                                                        </h3>
                                                    </div>
                                                    <div>
                                                        <span class="text-red display-6"><i class="ti-check-box text-success"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            @if($locationCount->statusid_count01 === 'C')
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header"></div>
                                            <div class="card-body d-flex justify-content-center">
                                                <a id="saveSuccess">
                                                    <button type="submit"
                                                            class="btn waves-effect waves-light btn-outline-secondary btn-md"
                                                            value="0">
                                                        <img src="{{asset('/ico/accept.png')}}" alt=""
                                                             class="img-16 ml-1">
                                                        <span style="font-size: 11px;"> Ubicación correcta</span>
                                                    </button>
                                                </a>
                                                <a id="saveErrorLocation" style="margin-left: 20px">
                                                    <button type="submit"
                                                            class="btn waves-effect waves-light btn-outline-secondary btn-md"
                                                            value="1">
                                                        <img src="{{asset('/ico/refuseunit.png')}}" alt=""
                                                             class="img-16 ml-1">
                                                        <span style="font-size: 11px;"> Ubicación con error</span>
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/extra-libs/prism/prism.css')}}">
    <style>
        .carousel-control-prev-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23ff0000' viewBox='0 0 8 8'%3e%3cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3e%3c/svg%3e");
        }

        .carousel-control-next-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23ff0000' viewBox='0 0 8 8'%3e%3cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3e%3c/svg%3e");
        }
    </style>
@endsection

@section('script')
    <script src="{{asset('/theme/assets/extra-libs/prism/prism.js')}}"></script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const redirectUrl = "{{ url('inventorylocations-card/' . $locationCount->inventorycardcount->id) }}";

            $('#saveSuccess').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: '{!! route('saveResponseSuccessLocation', $locationCount->id) !!}',
                    type: 'POST',
                    dataType: 'json',
                    success: function (data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Ubicación registrada correctamente',
                            width: 350,
                        }).then(function () {
                            window.location.href = redirectUrl;
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ?? 'Error al procesar la solicitud.',
                            width: 350,
                        });
                    },
                });
            });

            $('#saveErrorLocation').click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: '{!! route('saveResponseErrorLocation', $locationCount->id) !!}',
                    type: 'POST',
                    dataType: 'json',
                    success: function (data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Ubicación registrada correctamente',
                            width: 350,
                        }).then(function () {
                            window.location.href = redirectUrl;
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ?? 'Error al procesar la solicitud.',
                            width: 350,
                        });
                    },
                });
            });
        });
    </script>

@endsection
