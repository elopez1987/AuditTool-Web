@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Master en Procesos
                </div>

                <div class="card-body">
                    <div class="row">
                        @foreach($masters as $master)
                            <div class="col-lg-3">
                                <div class="card-input" style="border-radius: 20px">
                                    <div class="card order-widget" style="border-radius: 20px">
                                        <a href="{{url('detailmasteraudit/'. $master->masterid)}}">
                                            <div class="card-body">
                                                <div class="row">
                                                    <!-- column -->
                                                    <div class="col-sm-12">

                                                        <h3 class="card-title font-bold">Master : {{$master->masterid}}</h3>
                                                        <h4 class="card-subtitle m-b-2">
                                                            <span style="font-weight: 700;color: red">{{$master->value01}}</span> | {{$master->createdate}}
                                                        </h4>

                                                        <div class="row m-t-20">
                                                            <div class="col-6 border-right text-center">
                                                                <i class="fa fa-circle text-danger"></i>
                                                                <h3 class="m-b-0 font-medium">{{$master->totallpns}}</h3>
                                                                <span>Total ID.Container</span>
                                                            </div>
                                                            <div class="col-6 text-center">
                                                                <i class="fa fa-circle text-warning"></i>
                                                                <h3 class="m-b-0 font-medium">{{$master->totalpiezas}}</h3>
                                                                <span>Total Piezas</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>


                </div>
                <div class="card-footer text-center">
                    Seguimiento de embarques
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

@endsection
