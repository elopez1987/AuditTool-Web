@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Creación de usuarios
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('superusers')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('superusers.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('superusers.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        @include('Security.SuperUsers.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('SelectDynamics.SelectWarehouseForCenterForCreate')
@endsection

