@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar ubicación
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storagelocations')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('storagelocations.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('storagelocations.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Yards.StorageLocations.partials.formCreate')
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
    <script type="module">
        const moduleUrl = "{{ asset('js/selects/zone-for-warehouse.js') }}";
        const {initializeZoneLookup} = await import(moduleUrl);
        initializeZoneLookup("warehouseid", "zoneid");
    </script>
@endsection

