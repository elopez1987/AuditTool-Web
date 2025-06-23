@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Crear menu
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('securitymenustructure')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('securitymenustructure.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('securitymenustructure.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Security.SecurityMenu.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')

@endsection

