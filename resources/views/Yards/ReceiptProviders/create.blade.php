@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Crear ruta
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
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')
    <script src="{{asset('js/selects/warehouse-for-center.js')}}"></script>
@endsection

