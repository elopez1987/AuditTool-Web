@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{url('namesystem')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('namesystem.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('namesystem.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <br>
                        @include('Configuration.NameSystem.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

