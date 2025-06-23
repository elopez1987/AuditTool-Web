@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Crear proveedor
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('receiptproviders')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('receiptproviders.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('receiptproviders.index')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Yards.ReceiptProviders.partials.formCreate')
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
    @include('SelectDynamics.SelectWarehouseForCenterForCreate')
@endsection

