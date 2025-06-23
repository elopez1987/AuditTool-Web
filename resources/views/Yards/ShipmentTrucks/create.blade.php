@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Crear Registro de capacidad de trailer
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('shipmenttrucks')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('shipmenttrucks.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('shipmenttrucks.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Yards.ShipmentTrucks.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')

@endsection

