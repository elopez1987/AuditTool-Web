@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Crear Linea de transporte
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('shipmentcarriers')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('shipmentcarriers.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('shipmentcarriers.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-4">
                        @include('Yards.ShipmentCarriers.partials.formCreate')
                    </form>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')

@endsection

