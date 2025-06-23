@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar cliente
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('ordercustomers/' . $ordercustomer->id)}}" enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                    <div class="row">
                        <div class="col-12">
                            <a href="">
                              @include('buttons.save')
                            </a>
                            <a href="{{route('ordercustomers.index')}}">
                               @include('buttons.back')
                            </a>
                        </div>
                    </div>
                    <hr class="mb-3">
                    @include('Yards.OrderCustomers.partials.formEdit')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

