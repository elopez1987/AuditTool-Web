@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-header">Registrar tipo de embarque.
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('shipmenttypes')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('shipmenttypes.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('shipmenttypes.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-4">
                        @include('Shipments.ShipmentTypes.partials.formCreate')
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

@endsection

