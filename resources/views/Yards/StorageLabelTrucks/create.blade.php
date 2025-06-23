@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar caja o tracto
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storagelabeltrucks')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('storagelabeltrucks.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('storagelabeltrucks.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Yards.StorageLabelTrucks.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')
    <script>
        $("#typeid").change(event => {
            let typeid = document.getElementById("typeid").value;
            if (typeid === 'TR') {
                $("#truckdiv").hide();
            }
            if (typeid === 'BX') {
                $("#truckdiv").show();
            }
        });
    </script>
@endsection

