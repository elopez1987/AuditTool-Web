@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar función
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('securityfunctions')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('securityfunctions.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('securityfunctions.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Security.SecurtiyFunctions.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

