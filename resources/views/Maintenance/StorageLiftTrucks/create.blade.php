@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar cliente
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storagelifttrucks')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a>
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('storagelifttrucks.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-1">
                        @include('Maintenance.StorageLiftTrucks.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('SelectDynamics.SelectWarehouseForCenterForCreate')
@endsection

