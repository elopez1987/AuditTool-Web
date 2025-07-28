@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Detalle de cédula -- {{$location->cardid}} || Ubicación -- {{$location->recordid}}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="{{url('detailcard/' . $card->recordid)}}">
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/adback.png')}}" alt="" class="img-16 ml-1">
                                    <span style="font-size: 11px;"> Regresar</span>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row d-flex justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner" role="listbox">
                            @foreach($evidences as $evidence)
                                <div class="carousel-item @if ($loop->first) active @endif ">
                                    <img class="img-fluid align-items-lg-center"
                                         src="{{asset('storage/' . $evidence->path)}}" alt=""
                                         style="height: 600px;max-width: 600px">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h2 class="text-danger ">Foto : {{$loop->iteration}} / {{$loop->count}}</h2>
                                        <p class="text-danger font-14">Piloto: {{$evidence->createuser}} || Fecha de
                                            registro: {{$evidence->created_at}}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                           data-slide="prev"> <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span> </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                           data-slide="next"> <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span> </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <span style="font-size: 25px">Ubicación</span>
                            <h3 class="m-b-0" style="font-size: 35px; color: red">{{$location->locationid}}</h3>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 20px">
                        <div class="col-lg-12">
                            <span style="font-size: 25px">Número de parte</span>
                            <h3 class="m-b-0" style="color: red;font-size: 35px">{{$location->partnumberid}}</h3>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 20px">
                        <div class="col-lg-12">
                            <span style="font-size: 25px">Descripción</span>
                            <h3 class="m-b-0" style="color: red;font-size: 35px">{{$location->sku ? $location->sku->lngdsc :''}}</h3>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 20px">
                        <div class="col-lg-12">
                            <span style="font-size: 25px">Cantidad</span>
                            <h3 class="m-b-0" style="color: red;font-size: 35px">{{$location->quantity}}</h3>
                        </div>
                    </div>

                </div>
            </div>

            @if(!empty($stock))
                @if(empty($location->error))
                    @if($location->partnumberid != $stock->partnumberid || $location->quantity != $stock->quantity)
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <a id="saveErrorLocation" style="margin-left: 20px">
                                            <button type="submit"
                                                    class="btn waves-effect waves-light btn-outline-danger btn-md btn-block"
                                                    value="1">
                                                <img src="{{asset('/ico/adcancelar.png')}}" alt="" class="img-16 ml-1">
                                                <span style="font-size: 11px;"> AUDITORIA</span>
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body d-flex justify-content-center">
                                    @if(empty($location->error))
                                        <a id="saveSuccess">
                                            <button type="submit"
                                                    class="btn waves-effect waves-light btn-outline-success btn-md"
                                                    value="0">
                                                <img src="{{asset('/ico/ok.png')}}" alt="" class="img-16 ml-1">
                                                <span style="font-size: 11px;"> Ubicación correcta</span>
                                            </button>
                                        </a>
                                        <a id="saveErrorLocation" style="margin-left: 20px">
                                            <button type="submit"
                                                    class="btn waves-effect waves-light btn-outline-danger btn-md"
                                                    value="1">
                                                <img src="{{asset('/ico/adcancelar.png')}}" alt="" class="img-16 ml-1">
                                                <span style="font-size: 11px;"> Ubicación con error</span>
                                            </button>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body d-flex justify-content-center">
                                @if(empty($location->error))
                                    <a id="saveSuccess">
                                        <button type="submit"
                                                class="btn waves-effect waves-light btn-outline-success btn-md"
                                                value="0">
                                            <img src="{{asset('/ico/ok.png')}}" alt="" class="img-16 ml-1">
                                            <span style="font-size: 11px;"> Ubicación correcta</span>
                                        </button>
                                    </a>
                                    <a id="saveErrorLocation" style="margin-left: 20px">
                                        <button type="submit"
                                                class="btn waves-effect waves-light btn-outline-danger btn-md"
                                                value="1">
                                            <img src="{{asset('/ico/adcancelar.png')}}" alt="" class="img-16 ml-1">
                                            <span style="font-size: 11px;"> Ubicación con error</span>
                                        </button>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif


        </div>
    </div>



@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/extra-libs/prism/prism.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/extra-libs/prism/prism.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#saveSuccess').click(function (e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        locationid: {{$location->recordid}},
                    },
                    url: '{!! route('saveResponseSuccessLocation') !!}',
                    type: "POST",
                    dataType: 'json',
                    success: data => {
                        Swal.fire({
                            type: 'success',
                            title: `Ubicación registrada correctamente`,
                            width: 350,
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 3000)
                        window.location.href = "{{ url('detailcard/' . $card->recordid) }}";
                    },
                });
            });
        });
    </script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#saveErrorLocation').click(function (e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        locationid: {{$location->recordid}},
                    },
                    url: '{!! route('saveResponseErrorLocation') !!}',
                    type: "POST",
                    dataType: 'json',
                    success: data => {
                        Swal.fire({
                            type: 'success',
                            title: `Ubicación registrada correctamente`,
                            width: 350,
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 3000)
                        window.location.href = "{{ url('detailcard/' . $card->recordid) }}";
                    },
                });
            });
        });
    </script>
@endsection

