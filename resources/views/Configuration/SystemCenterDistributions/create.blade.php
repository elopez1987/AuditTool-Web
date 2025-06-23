@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                   Crear Centro de distribución
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('systemcenterdistributions.store')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a>
                                  @include('buttons.save')
                                </a>
                                <a href="{{route('systemcenterdistributions.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                    <hr class="mb-3">
                         @include('Configuration.SystemCenterDistributions.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')

@endsection

