@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar label
                </div>
                <form method="POST" action="{{url('label-dictionary/' . $id)}}">
                    {{ method_field('PUT') }}
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('label-dictionary.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-2">
                        @include('Configuration.UiLabels.partials.formEdit')
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

