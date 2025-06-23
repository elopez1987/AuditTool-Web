@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar categoria
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('shipmenttruckcategories')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('shipmenttruckcategories.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('shipmenttruckcategories.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Yards.ShipmentTruckCategories.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

