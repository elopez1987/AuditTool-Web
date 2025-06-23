@extends('layouts.app')
@section('css')
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('generateaccesswarehouse/' . $generateaccesswarehouse->id)}}"
                          enctype="multipart/form-data" >
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('generateaccesswarehouse.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('generateaccesswarehouse.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Yards.GenerateAccess.partials.formEdit')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection
