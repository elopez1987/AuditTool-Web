@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar proveedor
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('receiptproviders/' . $receiptprovider->id)}}"
                          enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('receiptproviders.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Yards.ReceiptProviders.partials.formEdit')
                    </form>
                    @include('layouts.Partials.Errors')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

