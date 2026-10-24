@extends('layouts.app')

@section('content')
    @can('dashboard01')


    @else
        <div class="row ">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if(!empty($image->auxiliary05))
                            <img src="{{asset('storage/' . $image->auxiliary05)}}"
                                 class="align-self-center img-responsive" alt="" style="width: 100%;height: 80%">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div style=" display: grid;margin: 0;padding: 0;">
                                        <div style="margin: auto;text-align: center">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <img src="{{asset('/img/login-register.jpg')}}" alt="" style="width: 100%;height: 80%">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection

@section('css')

@endsection
@section('script')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.highcharts.com/modules/cylinder.js"></script>
    <script src="https://code.highcharts.com/modules/funnel3d.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/dumbbell.js"></script>
    <script src="https://code.highcharts.com/modules/lollipop.js"></script>

    <script src="{{asset('/theme/assets/libs/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>



@endsection