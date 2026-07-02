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
                                <a href="{{url('inventorylocations-card/' . $locationCount->inventory_card_id)}}">
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
                                                        <p class="text-danger font-14">Piloto: {{$evidence->user_id}} </p>
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
                        <div class="col-lg-4 mt-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mt-4">
                                        <div class="col-lg-12">
                                            <span style="font-size: 25px">Ubicación</span>
                                            <h3 class="m-b-0" style="font-size: 35px; color: red">{{$locationCount->inventorylocation ? $locationCount->inventorylocation->name :''}}</h3>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 20px">
                                        <div class="col-lg-12">
                                            <span style="font-size: 25px">Número de parte</span>
                                            <h3 class="m-b-0" style="color: red;font-size: 35px">{{$locationCount->inventorypartnumber01->prtnum}}</h3>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 20px">
                                        <div class="col-lg-12">
                                            <span style="font-size: 25px">Descripción</span>
                                            <h3 class="m-b-0"
                                                style="color: red;font-size: 35px">{{$locationCount->inventorypartnumber01 ? $locationCount->inventorypartnumber01->lngdsc :''}}</h3>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 20px">
                                        <div class="col-lg-12">
                                            <span style="font-size: 25px">Cantidad</span>
                                            <h3 class="m-b-0" style="color: red;font-size: 35px">{{$locationCount->quantity01}}</h3>
                                        </div>
                                    </div>

                                </div>
                            </div>

{{--                            @if(!empty($stock))--}}
{{--                                @if(empty($locationCount->error))--}}
{{--                                    @if($locationCount->partnumberid != $stock->partnumberid || $locationCount->quantity != $stock->lpnid)--}}
{{--                                        <div class="row">--}}
{{--                                            <div class="col-lg-12">--}}
{{--                                                <div class="card">--}}
{{--                                                    <div class="card-body">--}}
{{--                                                        <a id="saveErrorLocation" style="margin-left: 20px">--}}
{{--                                                            <button type="submit"--}}
{{--                                                                    class="btn waves-effect waves-light btn-outline-danger btn-md btn-block"--}}
{{--                                                                    value="1">--}}
{{--                                                                <img src="{{asset('/ico/adcancelar.png')}}" alt="" class="img-16 ml-1">--}}
{{--                                                                <span style="font-size: 11px;"> AUDITORIA</span>--}}
{{--                                                            </button>--}}
{{--                                                        </a>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    @else--}}
{{--                                        <div class="row">--}}
{{--                                            <div class="col-lg-12">--}}
{{--                                                <div class="card">--}}
{{--                                                    <div class="card-body d-flex justify-content-center">--}}
{{--                                                        @if(empty($locationCount->error))--}}
{{--                                                            <a id="saveSuccess">--}}
{{--                                                                <button type="submit"--}}
{{--                                                                        class="btn waves-effect waves-light btn-outline-success btn-md"--}}
{{--                                                                        value="0">--}}
{{--                                                                    <img src="{{asset('/ico/ok.png')}}" alt="" class="img-16 ml-1">--}}
{{--                                                                    <span style="font-size: 11px;"> Ubicación correcta</span>--}}
{{--                                                                </button>--}}
{{--                                                            </a>--}}
{{--                                                            <a id="saveErrorLocation" style="margin-left: 20px">--}}
{{--                                                                <button type="submit"--}}
{{--                                                                        class="btn waves-effect waves-light btn-outline-danger btn-md"--}}
{{--                                                                        value="1">--}}
{{--                                                                    <img src="{{asset('/ico/adcancelar.png')}}" alt=""--}}
{{--                                                                         class="img-16 ml-1">--}}
{{--                                                                    <span style="font-size: 11px;"> Ubicación con error</span>--}}
{{--                                                                </button>--}}
{{--                                                            </a>--}}
{{--                                                        @endif--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
{{--                                @else--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-lg-12">--}}
{{--                                            <div class="card">--}}
{{--                                                <div class="card-body d-flex justify-content-center">--}}
{{--                                                    @if(empty($locationCount->error))--}}
{{--                                                        <a id="saveSuccess">--}}
{{--                                                            <button type="submit"--}}
{{--                                                                    class="btn waves-effect waves-light btn-outline-success btn-md"--}}
{{--                                                                    value="0">--}}
{{--                                                                <img src="{{asset('/ico/ok.png')}}" alt="" class="img-16 ml-1">--}}
{{--                                                                <span style="font-size: 11px;"> Ubicación correcta</span>--}}
{{--                                                            </button>--}}
{{--                                                        </a>--}}
{{--                                                        <a id="saveErrorLocation" style="margin-left: 20px">--}}
{{--                                                            <button type="submit"--}}
{{--                                                                    class="btn waves-effect waves-light btn-outline-danger btn-md"--}}
{{--                                                                    value="1">--}}
{{--                                                                <img src="{{asset('/ico/adcancelar.png')}}" alt="" class="img-16 ml-1">--}}
{{--                                                                <span style="font-size: 11px;"> Ubicación con error</span>--}}
{{--                                                            </button>--}}
{{--                                                        </a>--}}
{{--                                                    @endif--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                            @endif--}}

                        </div>

                    </div>
                </div>
            </div>
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
@endsection
