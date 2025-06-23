@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Creacion de nuevo perfil
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('securityroles')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('securityroles.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('securityroles.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        @include('Security.Roles.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

