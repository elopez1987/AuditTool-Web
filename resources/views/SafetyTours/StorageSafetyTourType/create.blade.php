@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar Tag
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="POST" action="{{url('storagesafetytourtype')}}" enctype="multipart/form-data">
                                @csrf
                                <hr class="b-3">
                                @include('SafetyTours.StorageSafetyTourType.partials.formCreate')
                                <hr class="b-3">
                                <div class="row">
                                    <div class="col-12">
                                        <a href="{{route('storagesafetytourtype.create')}}">
                                            @include('buttons.save')
                                        </a>
                                        <a href="{{route('storagesafetytourtype.index')}}">
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

