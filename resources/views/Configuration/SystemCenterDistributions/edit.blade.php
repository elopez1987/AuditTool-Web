@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar Centro de distribución
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('systemcenterdistributions/' . $systemcenterdistribution->centerid)}}" enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('systemcenterdistributions.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-8">
                                @include('Configuration.SystemCenterDistributions.partials.formEdit')
                            </div>
                            <div class="col-lg-4">
                                <img src="{{asset('storage/' . $systemcenterdistribution->auxiliary04)}}"
                                        alt="Logo"
                                        style="height: 300px;max-height: 400px"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

