@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{url('namesystem/' . $namesystem->id)}}" enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('namesystem.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <br>
                        @include('Configuration.NameSystem.partials.formEdit')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

