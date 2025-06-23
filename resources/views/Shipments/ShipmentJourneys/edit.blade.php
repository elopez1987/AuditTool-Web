@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{url('shipmentjourney/' . $shipmentjourney->id)}}"
                          enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('shipmentjourney.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <br>
                        @include('Shipments.ShipmentJourneys.partials.formEdit')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}">
@endsection


@section('script')
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>
    <script
        src="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js')}}"></script>
    <script>
        $('.date').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD HH:mm',
            lang: 'es',
            weekStart: 1,
            cancelText: 'Cancelar'
        });
    </script>
@endsection

