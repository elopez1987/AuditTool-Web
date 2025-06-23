@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar cliente
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('ordercustomers')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('ordercustomers.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('ordercustomers.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-1">
                        @include('Yards.OrderCustomers.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('SelectDynamics.SelectWarehouseForCenterForCreate')
@endsection

