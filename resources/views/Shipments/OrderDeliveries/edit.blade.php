@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar Embarque
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('orderdeliveries/' . $orderdelivery->id )}}"
                          enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('orderdeliveries.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        @include('Shipments.OrderDeliveries.partials.formEdit')
                        <hr class="mb-3">
                    </form>
                    <div class="row">
                        <div class="col-lg-12">
                            @include('layouts.Partials.Errors')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/quill/dist/quill.snow.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/quill/dist/quill.min.js')}}"></script>
    <script src="{{asset('js/selects/warehouse-for-center.js')}}"></script>
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
