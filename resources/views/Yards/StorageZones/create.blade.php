@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar Zona
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storagezones')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                @can('storagezones.create')
                                    <a href="{{route('storagezones.create')}}">
                                        @include('buttons.save')
                                    </a>
                                @endcan
                                <a href="{{route('storagezones.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Yards.StorageZones.partials.formCreate')
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

@section('script')
    <script>
        $("#zoneid").change(event => {
            var zoneid = document.getElementById("zoneid").value;
            if (zoneid === 'CO') {
                $("#auxiliary01").val('CORTINAS');
            }
            if (zoneid === 'PA') {
                $("#auxiliary01").val('DROP-LOT');
            }
            if (zoneid === 'ST') {
                $("#auxiliary01").val('PRECARGA');
            }
        });
    </script>
@endsection

