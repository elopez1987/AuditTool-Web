@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar concepto de ajuste
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storagereasons')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('storagereasons.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('storagereasons.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        @include('Configuration.StorageReasons.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')

@endsection

