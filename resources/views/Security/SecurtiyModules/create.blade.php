@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    agregar nuevo modulo
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('securitymodules')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('securitymodules.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('securitymodules.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        @include('Security.SecurtiyModules.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

