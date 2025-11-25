@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Programar embarque
                </div>
                <div class="card-body">
                    <form method="POST" id="createorderdelivery" action="{{url('orderdeliveries')}}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('orderdeliveries.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('orderdeliveries.index')}}">
                                    @include('buttons.back')
                                </a>
                                <h5 class="float-right"
                                    style="vertical-align: middle;font-size: 10px;padding-top: 8px;">Registrar nueva
                                    cita</h5>
                            </div>
                        </div>
                        <br>
                        <hr class="m-t-0">
                        @include('Shipments.OrderDeliveries.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('css')
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/quill/dist/quill.snow.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/quill/dist/quill.min.js')}}"></script>
    <script src="{{asset('/js/selects/warehouse-for-center.js')}}"></script>



    <script>
        $('#workday').bootstrapMaterialDatePicker({format: 'YYYY-MM-DD', weekStart: 1, time: false});
        $('#loadingdate').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD HH:mm',
            lang: 'es',
            weekStart: 1,
            cancelText: 'Cancelar'
        });
        $('#downloaddate').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD HH:mm',
            lang: 'es',
            weekStart: 1,
            cancelText: 'Cancelar'
        });
    </script>
    <script>
        var quill = new Quill('#comment', {
            theme: 'snow'
        });
    </script>

    <script>
        const form = document.getElementById('createorderdelivery');
        form.addEventListener('submit', function(event) {
            const input = document.getElementById('shipmentid').value;
            const regex = /^[a-zA-Z0-9]+$/;
            if (!regex.test(input)) {
                alert("Por favor, ingrese solo números y letras.");
                event.preventDefault();
            }
        });
    </script>

@endsection
