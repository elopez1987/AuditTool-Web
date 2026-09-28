@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar nuevo equipo.
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('storagelifttrucks')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a>
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('storagelifttrucks.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-1">
                        @include('Maintenance.StorageLiftTrucks.partials.formCreate')
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')

@endsection

