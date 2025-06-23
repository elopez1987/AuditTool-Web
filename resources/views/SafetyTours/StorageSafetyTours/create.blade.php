@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar Recorrido
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="POST" action="{{url('storagesafetytours')}}" enctype="multipart/form-data">
                                @csrf
                                <hr class="b-3">
                                @include('SafetyTours.StorageSafetyTours.partials.formCreate')
                                <hr class="b-3">
                                <div class="row">
                                    <div class="col-12">
                                        <a href="{{route('storagesafetytours.create')}}">
                                            @include('buttons.save')
                                        </a>
                                        <a href="{{route('storagesafetytours.index')}}">
                                            @include('buttons.back')
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   @include('layouts.Partials.Errors')
@endsection

@section('css')
@endsection


@section('script')

@endsection

