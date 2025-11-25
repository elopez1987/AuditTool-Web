@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Captura de recibo
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('receiptasn')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('receiptasn.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('receiptasn.index')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        @include('Receipt.ReceiptAsn.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/quill/dist/quill.snow.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/quill/dist/quill.min.js')}}"></script>
    <script src="{{asset('js/selects/warehouse-for-center.js')}}"></script>


    @include('SelectDynamics.SelectProvidersForWarehouseForCreate')

    <script>
        $('.date').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD HH:mm',
            lang: 'es',
            weekStart: 1,
            cancelText: 'Cancelar'
        });
    </script>



@endsection

