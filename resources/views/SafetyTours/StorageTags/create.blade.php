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
                            <form method="POST" action="{{url('storagetags')}}" enctype="multipart/form-data">
                                @csrf
                                <hr class="b-3">
                                @include('SafetyTours.StorageTags.partials.formCreate')
                                <hr class="b-3">
                                <div class="row">
                                    <div class="col-12">
                                        <a href="{{route('storagetags.create')}}">
                                            @include('buttons.save')
                                        </a>
                                        <a href="{{route('storagetags.index')}}">
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

