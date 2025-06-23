@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar Folio
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('systemfolios')}}">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('systemfolios.create')}}">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('systemfolios.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        @include('Configuration.SystemFolios.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection

