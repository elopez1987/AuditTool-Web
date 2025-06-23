@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modificar token
                </div>
                <form method="POST" action="{{url('editokens/' . $token->id)}}">
                    {{ method_field('PUT') }}
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('editokens.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-2">
                        @include('Configuration.EdiToken.partials.formEdit')
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

